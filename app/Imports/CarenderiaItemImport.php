<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\CarenderiaItem;
use App\Models\UploadedFile;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CarenderiaItemImport implements ToModel, WithHeadingRow, ShouldQueue, WithChunkReading
{
    use InteractsWithQueue;
    
    protected $uploadId;
    protected $module;

    public function __construct($uploadId, $module)
    {
        $this->uploadId = $uploadId;
        $this->module = $module;
    }

    /** 
     * This handles row-by-row processing in the background queue.
     */
    public function model(array $row)
    {
        // Log::info('Processing row for upload ID ' . $this->uploadId);
        // $encodedRow = json_encode($row);
        // Log::info('Raw row data: ');
        // Log::info($row);
        // Log::info('Encoded row data: ' . $encodedRow);

        // 1. Find or create the employee profile safely
        $employee = Employee::firstOrCreate(
            ['employee_code' => $row['empid']],
            ['name' => 'Employee ' . $row['empid']]
        );

        // 2. Return the item model (Laravel Excel saves it automatically)
        return new CarenderiaItem([
            'upload_id'   => $this->uploadId,
            'employee_id' => $employee->id,
            'total'       => $row['total'],
            'date'        => $row['date'],
        ]);
    }

    /**
     * Process the file in chunks of 500 rows to keep memory usage low
     */
    public function chunkSize(): int
    {
        return 500;
    }
    
    /**
     * Trait automatically detects this static method for AfterImport
     */
    public static function afterImport(AfterImport $event)
    {
        // Version-compatible way to get your import class instance properties
        $importInstance = $event->importable; 
        $uploadId = $importInstance->uploadId;

        $upload = UploadedFile::find($uploadId);
        if ($upload) {
            $count = CarenderiaItem::where('upload_id', $uploadId)->count();
            $upload->update([
                'status' => 'completed',
                'row_count' => $count
            ]);
        }
    }

    /**
     * Trait automatically detects this static method for ImportFailed
     */
    public static function importFailed(ImportFailed $event)
    {
        // Version-compatible way to get your import class instance properties
        $importInstance = $event->importable;
        $uploadId = $importInstance->uploadId;

        $upload = UploadedFile::find($uploadId);
        if ($upload) {
            $upload->update(['status' => 'failed']);
        }
    }
}