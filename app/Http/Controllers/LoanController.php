<?php

namespace App\Http\Controllers;

use App\Models\{
    Employee,
    LoanItem,
    UploadedFile,
};
use App\Services\UploadExcelService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Imports\ItemImport;
use Maatwebsite\Excel\Facades\Excel;

class LoanController extends Controller
{
    public $uploadExcelService;
    public function __construct(UploadExcelService $uploadExcelService)
    {
        $this->uploadExcelService = $uploadExcelService;
    }

    /**
     * Display the loan files list with search and filters.
     */
    public function index(Request $request)
    {
        $query = UploadedFile::with('admin')->ofModule('loan');

        // Filter by Filename Search
        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        // Filter by Status (completed, processing, failed)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $files = $query->latest()->paginate(10)->withQueryString();

        return view('pages.loan.index', compact('files'));
    }

    /**
     * Handle the Excel batch upload and process rows.
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if ($validator->fails()) {
            return back()->with('notification', [
                'status'   => 'error',
                'title'    => 'Validation Error',
                'messages' => $validator->errors()->messages(),
            ])->withInput();
        }

        try {
            // Extract the file object explicitly out of the request payload
            $file = $request->file('excel_file');

            // Set the module type
            $module_type = 'loan';
            
            // Service handles the heavy lifting and returns the tracking record
            $upload = $this->uploadExcelService->make($file, $module_type);

            return back()->with('success', "File \"{$upload->original_filename}\" uploaded and is processing in the background!");

        } catch (\Exception $e) {
            // Any structural bugs/failures gracefully fallback here
            return back()->with('error', $e->getMessage());
        }
    }
}