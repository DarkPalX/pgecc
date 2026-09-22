<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use App\Services\UploadExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsumerBalanceController extends Controller
{
    public function __construct(public UploadExcelService $uploadExcelService) {}

    public function index(Request $request)
    {
        $query = UploadedFile::with('admin')->ofModule('consumer_balances');

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $files = $query->latest()->paginate(10)->withQueryString();

        return view('pages.consumer-balances.index', compact('files'));
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if ($validator->fails()) {
            return back()->with('notification', [
                'status' => 'error',
                'title' => 'Validation Error',
                'messages' => $validator->errors()->messages(),
            ])->with('errors', $validator->errors()->all())->withInput();
        }

        try {
            $upload = $this->uploadExcelService->make(
                $request->file('excel_file'),
                'consumer_balances'
            );

            return back()->with('success', "File \"{$upload->original_filename}\" uploaded and is processing in the background!");
        } catch (\Exception $e) {
            return back()->with('errors', [$e->getMessage()]);
        }
    }
}
