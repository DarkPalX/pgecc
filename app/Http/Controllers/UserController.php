<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display both Employees and Administrators lists.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tab = $request->input('tab', 'employees'); // default to employees directory

        // Query Employees
        $employees = Employee::when($search && $tab === 'employees', function ($query) use ($search) {
            $query->where('employee_code', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%");
        })->latest()->paginate(10, ['*'], 'emp_page')->withQueryString();

        // Query System Admins
        $admins = User::when($search && $tab === 'admins', function ($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
        })->latest()->paginate(10, ['*'], 'adm_page')->withQueryString();

        return view('pages.users.index', compact('employees', 'admins', 'tab', 'search'));
    }

    /**
     * Store a newly created employee.
     */
    public function storeEmployee(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|string|unique:employees,employee_code',
            'name'          => 'required|string|max:255',
        ]);

        Employee::create([
            'employee_code' => strtoupper($request->employee_code),
            'name'          => $request->name,
            'is_active'     => true,
        ]);

        return redirect()->route('users.index', ['tab' => 'employees'])
            ->with('success', 'Employee profile registered successfully!');
    }

    /**
     * Store a newly created administrator.
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $checkUser = User::where('email', $request->email)->first();
        if ($checkUser) {
            return redirect()->route('users.index', ['tab' => 'admins'])
                ->with('error', 'Email already exists. Please use a different email address.')
                ->withInput();
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index', ['tab' => 'admins'])
            ->with('success', 'System Administrator created successfully!');
    }
}