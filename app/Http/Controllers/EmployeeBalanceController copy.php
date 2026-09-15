<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeBalance;
use App\Imports\EmployeeBalancesImport;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeBalanceController extends Controller
{
    public function import(Request $request)
    {
        // Prevent PhpSpreadsheet from hanging on complex Excel formulas
        Calculation::getInstance()->disableCalculationCache();

        // Force reader to return cached calculated values from Excel instead of raw formulas
        config(['excel.imports.read_only' => true]);

        Excel::import(new EmployeeBalancesImport, $request->file('file'));

        return back()->with('success', 'Import completed successfully!');
    }

    public function search(Request $request)
    {
        $query = trim($request->input('query'));

        if (!$query) {
            return response()->json(['success' => false, 'message' => 'Query empty'], 400);
        }

        $employee = EmployeeBalance::where('pmc_id', $query)
            ->orWhere('pmc_id', 'LIKE', '%' . $query)
            ->orWhere('name', 'LIKE', '%' . $query . '%')
            ->first();

        if ($employee) {
            return response()->json([
                'success' => true, 
                'data' => $employee
            ], 200);
        }

        // Return 200 OK with success: false (or 404)
        return response()->json([
            'success' => false, 
            'message' => 'Employee not found'
        ], 200);
    }
}