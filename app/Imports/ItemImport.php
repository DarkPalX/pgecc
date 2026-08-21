<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\{CarenderiaItem, LoanItem, GroceryItem, PaymentItem};
use App\Models\UploadedFile;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ItemImport implements ToModel, WithHeadingRow, ShouldQueue, WithChunkReading, WithEvents
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
        $employee = Employee::firstOrCreate(
            ['employee_code' => $row['empid']],
            ['name' => 'Employee ' . $row['empid']]
        );

        return new $this->itemClass([
            'upload_id'   => $this->uploadId,
            'employee_id' => $employee->id,
            'total'       => $row['total'],
            'date'        => $row['date'],
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

        return [
            'empid'  => ['required', 'string', 'max:255', $antiInjectionRegex],
            'total' => ['required', 'numeric', 'min:0'],
            'date'   => ['required', 'date_format:m/d/Y'],
        ];
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