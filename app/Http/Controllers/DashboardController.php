<?php

namespace App\Http\Controllers;

use App\Models\EmployeeSearch;
use App\Models\CarenderiaItem;
use App\Models\LoanItem;
use App\Models\GroceryItem;
use App\Models\PaymentItem;
use App\Models\EmployeeBalance;
use App\Models\FileUpload;
use App\Models\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));
        $employee = null;

        if ($search) {
            // Check employee balance record by ID/Code or Name
            $employee = EmployeeBalance::where('pmc_id', $search)
                ->orWhere('pmc_id', 'LIKE', "%{$search}")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->first();
        }

        // Fetch Search Results safely matching existing table columns
        $results = EmployeeSearch::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    // Search directly on view fields or employee relation
                    $q->where('name', 'LIKE', "%{$search}%");

                    if (Schema::hasColumn('employee_search_view', 'pmc_id')) {
                        $q->orWhere('pmc_id', 'LIKE', "%{$search}%");
                    }

                    if (Schema::hasColumn('employee_search_view', 'employee_code')) {
                        $q->orWhere('employee_code', 'LIKE', "%{$search}%");
                    }
                });
            })
            ->latest('date')
            ->take(50)
            ->get();

        // Calculate Grand Totals
        $stats = [
            'carenderia' => CarenderiaItem::searchModuleByEmployee($search, 'completed')->sum('total'),
            'loans'      => LoanItem::searchModuleByEmployee($search, 'completed')->sum('total'),
            'grocery'    => GroceryItem::searchModuleByEmployee($search, 'completed')->sum('total'),
            'payments'   => PaymentItem::searchModuleByEmployee($search, 'completed')->sum('total'),
        ];

        $employee_balances = EmployeeBalance::all();

        $stats['balance'] = $stats['payments'] - ($stats['loans'] + $stats['carenderia'] + $stats['grocery']);

        // Dashboard history includes legacy dashboard uploads and module uploads.
        $dashboardUploads = FileUpload::latest('created_at')->get();
        $knownPaths = $dashboardUploads->pluck('filename')->all();

        // Include module uploads created before they were added to the global history.
        $legacyModuleUploads = UploadedFile::latest('created_at')
            ->get()
            ->reject(fn (UploadedFile $upload) => in_array($upload->storage_path, $knownPaths, true))
            ->map(function (UploadedFile $upload) {
                $history = new FileUpload(['filename' => $upload->storage_path]);
                $history->created_at = $upload->created_at;
                $history->is_module_upload = true;
                $history->module_upload_id = $upload->getKey();

                return $history;
            });

        $fileUploads = $dashboardUploads
            ->concat($legacyModuleUploads)
            ->sortByDesc('created_at')
            ->take(50)
            ->values();

        return view('pages.dashboard', compact('stats', 'results', 'search', 'employee', 'employee_balances', 'fileUploads'));
    }

    // public function index(Request $request)
    // {
    //     // 1. Handle Search Query against the DB View
    //     $search = $request->input('search');

    //     $results = EmployeeSearch::with('employee')
    //         ->when($search, function ($query) use ($search) {
    //             $query->whereHas('employee', function ($q) use ($search) {
    //                 $q->where('employee_code', 'LIKE', "%{$search}%")
    //                   ->orWhere('name', 'LIKE', "%{$search}%");
    //             });
    //         })
    //         ->latest('date')
    //         ->take(50) // Limit to top 50 results for rapid UI rendering
    //         ->get();

            
    //     // 2. Get Grand Totals for Summary Cards
    //     // Using cache or standard queries (Indexes on the tables keep this fast!)
    //     $stats = [
    //         'carenderia' => CarenderiaItem::searchModuleByEmployee($search, 'completed')->sum('total'),
    //         'loans'      => LoanItem::searchModuleByEmployee($search, 'completed')->sum('total'),
    //         'grocery'    => GroceryItem::searchModuleByEmployee($search, 'completed')->sum('total'),
    //         'payments'   => PaymentItem::searchModuleByEmployee($search, 'completed')->sum('total'),
    //     ];

    //     $stats['balance'] = $stats['payments'] - ($stats['loans'] + $stats['carenderia'] + $stats['grocery']);

    //     return view('pages.dashboard', compact('stats', 'results', 'search'));
    // }
}
