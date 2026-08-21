<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Imports\ItemImport;
use App\Models\{CarenderiaItem, LoanItem, GroceryItem, PaymentItem, UploadedFile};
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile as LaravelFile;

class UploadExcelService
{
    /**
     * Resolve the target Eloquent Model class matching the module slug.
     */
    public function resolveItemClass(string $moduleType): string
    {
        switch (strtolower($moduleType)) {
            case 'carenderia': return CarenderiaItem::class;
            case 'loan':        return LoanItem::class;
            case 'grocery':     return GroceryItem::class;
            case 'payments':    return PaymentItem::class;
            default:
                throw new \InvalidArgumentException("Invalid module type for import: {$moduleType}");
        }
    }
    
    /**
     * Process and queue the Excel file.
     * * @param LaravelFile $file
     * @param string $moduleType
     * @return UploadedFile
     * @throws \Exception
     */
    public function make(LaravelFile $file, string $moduleType): UploadedFile
    {
        // 1. Validate module type structure immediately
        $this->resolveItemClass($moduleType);

        // 2. Store file asset safely
        $path = $file->store("uploads/{$moduleType}");

        // 3. Document track entry
        $upload = UploadedFile::create([
            'original_filename' => $file->getClientOriginalName(),
            'storage_path'      => $path,
            'module_type'       => $moduleType,
            'status'            => 'processing',
            'row_count'         => 0,
            'uploaded_by'       => auth()->id() ?? 1,
        ]);

        try {
            // 4. Pass execution to Excel background processing engine
            Excel::queueImport(new ItemImport($upload->id, $moduleType), storage_path('app/' . $path));
        } catch (\Exception $e) {
            $upload->update(['status' => 'failed']);
            throw new \RuntimeException('Could not queue the spreadsheet processing engine: ' . $e->getMessage());
        }

        // Return the clean Eloquent model reference object
        return $upload;
    }
}