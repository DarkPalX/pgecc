@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    
    <!-- Sidebar Navigation -->
    @include('components.sidebar')
    
    <div class="flex-1 flex flex-col overflow-y-auto">

        @include('components.topbar')
        <main class="flex-1 flex flex-col overflow-y-auto p-8 max-w-7xl mx-auto w-full space-y-8">
            
            <div class="module-header border-b border-gray-200 pb-5">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight"> 
                    <i class="fa-solid fa-user-group text-violet-500"></i>
                    User & Employee Directory
                </h1>
                <p class="text-sm text-gray-500 mt-1">Manage system platform administrators and monitor registered trackable company profiles.</p>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium">
                    ⚠️ Registration Error: Please confirm all form fields match parameters correctly.
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <div class="space-y-6">
                    
                    <section class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-4">
                        <h2 class="flex items-center font-bold text-gray-800 text-md"> 
                            <i class="fa-solid fa-plus text-indigo-600"></i>
                            Register New Employee
                        </h2>
                        <p class="text-xs text-gray-400">Creates a reference profile for logging Excel transaction entries.</p>
                        
                        <form action="{{ route('users.store.employee') }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Employee ID Code</label>
                                <input type="text" name="employee_code" required placeholder="e.g., EMP-005" class="w-full text-sm p-2 border border-gray-300 rounded-lg outline-none focus:border-slate-800" />
                                @error('employee_code') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Full Name</label>
                                <input type="text" name="name" required placeholder="e.g., Juan Dela Cruz" class="w-full text-sm p-2 border border-gray-300 rounded-lg outline-none focus:border-slate-800" />
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg text-xs transition">
                                Save Employee Profile
                            </button>
                        </form>
                    </section>

                    <section class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-4">
                        <h2 class="font-bold text-gray-800 text-md">
                            <i class="fa-solid fa-user-lock text-slate-900"></i>
                            Create Platform Admin
                        </h2>
                        <p class="text-xs text-gray-400">Grants login and upload execution authorizations to managers.</p>
                        
                        <form action="{{ route('users.store.admin') }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Admin Name</label>
                                <input type="text" name="name" required placeholder="e.g., Sarah Smith" class="w-full text-sm p-2 border border-gray-300 rounded-lg outline-none focus:border-slate-800" />
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Email Address</label>
                                <input type="email" name="email" required placeholder="sarah@company.com" class="w-full text-sm p-2 border border-gray-300 rounded-lg outline-none focus:border-slate-800" />
                                @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Password</label>
                                <input type="password" name="password" required class="w-full text-sm p-2 border border-gray-300 rounded-lg outline-none focus:border-slate-800" />
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" required class="w-full text-sm p-2 border border-gray-300 rounded-lg outline-none focus:border-slate-800" />
                            </div>
                            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2 rounded-lg text-xs transition">
                                Grant Administrative Access
                            </button>
                        </form>
                    </section>
                </div>

                <section class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
                    
                    <div class="flex border-b border-gray-100 bg-gray-50/70">
                        <a href="{{ route('users.index', ['tab' => 'employees']) }}" class="flex-1 text-center py-3 font-semibold text-sm transition {{ $tab === 'employees' ? 'bg-white border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">
                           <i class="fa-solid fa-user-group text-violet-400"></i>
                            Registered Employees ({{ $employees->total() }})
                        </a>
                        <a href="{{ route('users.index', ['tab' => 'admins']) }}" class="flex-1 text-center py-3 font-semibold text-sm transition {{ $tab === 'admins' ? 'bg-white border-b-2 border-slate-800 text-slate-900' : 'text-gray-400 hover:text-gray-600' }}"> 
                            <i class="fa-solid fa-user-lock text-slate-900"></i>
                            System Operators/Admins ({{ $admins->total() }})
                        </a>
                    </div>

                    <div class="p-4 border-b border-gray-100 bg-white">
                        <form action="{{ route('users.index') }}" method="GET" class="flex gap-2">
                            <input type="hidden" name="tab" value="{{ $tab }}" />
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ $search ?? '' }}" 
                                placeholder="Search {{ $tab === 'employees' ? 'by Employee Code or Name...' : 'by Admin Name or Email...' }}" 
                                class="w-full text-xs p-2.5 border border-gray-300 rounded-lg outline-none focus:border-slate-400"
                            />
                            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold px-4 rounded-lg transition">
                                Filter
                            </button>
                            @if($search)
                                <a href="{{ route('users.index', ['tab' => $tab]) }}" class="bg-gray-100 text-gray-500 hover:bg-gray-200 text-xs font-semibold px-3 rounded-lg flex items-center justify-center transition">✕</a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto flex-1">
                        @if($tab === 'employees')
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-200 bg-gray-50/50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        <th class="px-6 py-3">Code ID</th>
                                        <th class="px-6 py-3">Full Name</th>
                                        <th class="px-6 py-3">Monitoring Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @forelse($employees as $emp)
                                        <tr class="hover:bg-gray-50/70 transition">
                                            <td class="px-6 py-4 font-mono font-bold text-gray-600 text-xs">{{ $emp->employee_code }}</td>
                                            <td class="px-6 py-4 text-gray-900 font-medium">{{ $emp->name ?? 'Unnamed Profile' }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Tracking Active</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-gray-400 text-sm">📭 No matching trackable employee records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                            @if($employees->hasPages())
                                <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/50">{{ $employees->links() }}</div>
                            @endif

                        @else
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-200 bg-gray-50/50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        <th class="px-6 py-3">Admin Operator Name</th>
                                        <th class="px-6 py-3">Email Address</th>
                                        <th class="px-6 py-3">Privilege Level</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm overflow-y-auto">
                                    @forelse($admins as $admin)
                                        <tr class="hover:bg-gray-50/70 transition">
                                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $admin->name }}</td>
                                            <td class="px-6 py-4 text-gray-600 text-xs">{{ $admin->email }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-100">Full System Admin</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-gray-400 text-sm">📭 No administrative operators match the filtering search.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            @if($admins->hasPages())
                                <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/50">{{ $admins->links() }}</div>
                            @endif
                        @endif
                    </div>

                </section>

            </div>
        </main>
    </div>
</div>

</body>
</html>
@endsection