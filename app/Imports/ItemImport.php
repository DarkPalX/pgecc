<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\{CarenderiaItem, LoanItem, GroceryItem, PaymentItem};
use App\Models\UploadedFile;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class ItemImport implements ToModel, ShouldQueue, WithChunkReading, WithEvents, WithStartRow, WithValidation
{
    use InteractsWithQueue;
    
    public $uploadId;
    public $module;
    public $itemClass;

    public function __construct($uploadId, $module)
    {
        $this->uploadId = $uploadId;
        $this->module = $module;

        switch($this->module){
            case 'carenderia': $this->itemClass = CarenderiaItem::class; break;
            case 'loan'      : $this->itemClass = LoanItem::class; break;
            case 'grocery'   : $this->itemClass = GroceryItem::class; break;
            case 'payments'  : $this->itemClass = PaymentItem::class; break;
            default: throw new \Exception('Invalid module type for import: ' . $this->module);
        }
    }

    public function model(array $row)
    {
        // Expecting columns without a header row. Map numeric indexes:
        // 0 => empid, 1 => total, 2 => date
        $empIdRaw = isset($row[0]) ? trim((string)$row[0]) : null;
        $totalRaw = $row[1] ?? null;
        $dateRaw = $row[2] ?? null;

        $employee = Employee::firstOrCreate(
            ['employee_code' => $empIdRaw],
            ['name' => 'Employee ' . $empIdRaw]
        );

        // Normalize date: handle Excel numeric dates and common string formats
        $dateValue = null;
        if (is_numeric($dateRaw)) {
            try {
                $dateValue = ExcelDate::excelToDateTimeObject($dateRaw)->format('m/d/Y');
            } catch (\Exception $e) {
                $dateValue = null;
            }
        } else {
            try {
                $dateValue = Carbon::parse($dateRaw)->format('m/d/Y');
            } catch (\Exception $e) {
                $dateValue = null;
            }
        }

        return new $this->itemClass([
            'upload_id'   => $this->uploadId,
            'employee_id' => $employee->id,
            'total'       => $totalRaw,
            'date'        => $dateValue,
        ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * DataType and Value Validation per Row
     */
    public function rules(): array
    {
        $antiInjectionRegex = 'regex:/^(?![\=\+\-\@]).*$/';

        // Using numeric indexes because there's no header row.
        // 0 => empid, 1 => total, 2 => date
        return [
            '0' => ['required', 'string', 'max:255', $antiInjectionRegex],
            '1' => ['required', 'numeric', 'min:0'],
            // Accept any non-empty value for the date column; normalization is handled in model()
            '2' => ['required'],
        ];
    }

    /**
     * Start reading from row 7 (1-based index). This skips any top metadata.
     */
    public function startRow(): int
    {
        return 7;
    }
    
    /**
     * Register events explicitly using closures instead of static methods
     */
    public function registerEvents(): array
    {
        return [
            // 1. When the entire import succeeds
            AfterImport::class => function(AfterImport $event) {
                Log::info('Finished importing Excel.');
                Log::info('Updating upload status of UploadedFile ID: ' . $this->uploadId);
                
                $upload = UploadedFile::find($this->uploadId);
                if ($upload && $this->itemClass) {
                    $count = $this->itemClass::where('upload_id', $this->uploadId)->count();
                    $upload->update([
                        'status' => 'completed',
                        'row_count' => $count
                    ]);
                }
                Log::info('Finished updating status of UploadedFile ID: ' . $this->uploadId);
            },

            // 2. When the import crashes
            ImportFailed::class => function(ImportFailed $event) {
                Log::error('Excel Import Error: ' . $event->getException()->getMessage());

                $upload = UploadedFile::find($this->uploadId);
                if ($upload) {
                    $upload->update(['status' => 'failed']);
                }
            },
        ];
    }
}