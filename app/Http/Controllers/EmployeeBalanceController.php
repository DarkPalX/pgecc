<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeBalance;
use App\Models\FileUpload;
use App\Imports\EmployeeBalancesImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeBalanceController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        // Prevent PhpSpreadsheet from hanging on complex Excel formulas
        Calculation::getInstance()->disableCalculationCache();

        // Force reader to return cached calculated values from Excel instead of raw formulas
        config(['excel.imports.read_only' => true]);

        $uploadedFile = $request->file('file');
        $storedFilename = $uploadedFile->storeAs(
            'file_uploads',
            md5(uniqid('', true)) . '-' . $uploadedFile->getClientOriginalName()
        );

        try {
            Excel::import(new EmployeeBalancesImport, $uploadedFile);
            FileUpload::create(['filename' => $storedFilename]);
        } catch (\Throwable $exception) {
            Storage::delete($storedFilename);
            throw $exception;
        }

        return back()->with('success', 'Import completed successfully!');
    }

    public function download(FileUpload $fileUpload)
    {
        abort_unless(Storage::exists($fileUpload->filename), 404);

        return Storage::download($fileUpload->filename, $fileUpload->display_filename);
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = trim($request->input('query'));

        // Scope OR conditions together for boolean safety
        $employee = EmployeeBalance::where(function ($q) use ($query) {
            $q->where('pmc_id', $query)
              ->orWhere('pmc_id', 'LIKE', '%' . $query . '%')
              ->orWhere('name', 'LIKE', '%' . $query . '%');
        })->first();

        if ($employee) {
            return response()->json([
                'success' => true,
                'data'    => $employee
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'No employee balance record found for: ' . $query
        ], 200);
    }
}
