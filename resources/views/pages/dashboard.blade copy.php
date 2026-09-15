@extends('layouts.app')


@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar Navigation -->
    @include('components.sidebar')

    <!-- Main Content Section -->
    <main class="flex-1 flex flex-col overflow-y-auto">
        
        <!-- Dashboard Top Navbar -->
        <header class="bg-white border-b border-gray-200 px-4 py-4 flex items-center justify-between sticky top-0 z-10 shadow-xs min-h-16 max-h-16">
            
            <span id="sidebarToggle"
                class="p-2 hover:bg-gray-100 rounded-lg text-xl transition-all cursor-pointer">
                <!-- <i class="fa-solid fa-bars text-slate-800"></i> -->
            </span>
            
            <!-- Search -->
            <form action="{{ route('dashboard') }}" method="GET" class="w-full max-w-xl flex">
                @csrf
                <div class="relative w-full flex items-center">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search ?? '' }}"
                        placeholder="Search by Employee ID or Name across all modules..." 
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-tl-lg rounded-bl-lg focus:ring-blue-500 focus:border-blue-500 block py-2 pl-4 outline-none transition"
                    />
                    @if($search)
                        <a href="{{ route('dashboard') }}" class="absolute right-3 text-sm text-gray-400 hover:text-gray-600">✕ Clear</a>
                    @endif
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 rounded-tr-lg rounded-br-lg text-sm cursor-pointer transition-all active:translate-0.25">
                    Search
                </button>
            </form>
            <div class="max-lg:hidden text-sm font-medium text-gray-600">
                {{ now()->format('F d, Y') }}
            </div>
        </header>
        
        <!-- Dashboard Content -->
        <div class="p-8 max-w-7xl w-full mx-auto space-y-8">
            <section>
                <h2 id="employee-name"
                    class="font-bold text-gray-900 text-center
                    {{ $search ? 'text-5xl' : 'text-2xl' }}">
                    {{ $search? strtoupper($search) : 'Search an employee to display their activities...'}}
                </h2>
            </section>
            <hr class="border-gray-300">
            <!-- Stat Cards Grid -->
            <section>
                <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-4">Module Statistics (Grand Totals)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-5 gap-6">
                    
                    <!-- Carenderia Card -->
                    <div class="module-card transition-all hover:-translate-y-0.5">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Carenderia</p>
                            <p class="text-lg xl:text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($stats['carenderia'], 2) }}</p>
                        </div>
                        <span class="absolute top-2 right-2 p-2 bg-gray-100 rounded-lg text-xl">
                            <i class="fa-solid fa-sm fa-utensils text-amber-400"></i>
                        </span>
                    </div>

                    <!-- Loans Card -->
                    <div class="module-card transition-all hover:-translate-y-0.5">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Active Loans</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($stats['loans'], 2) }}</p>
                        </div>
                        <span class="absolute top-2 right-2 p-2 bg-gray-100 rounded-lg text-xl">
                            <i class="fa-solid fa-sm fa-sack-dollar text-green-400"></i>
                        </span>
                    </div>

                    <!-- Grocery Card -->
                    <div class="module-card transition-all hover:-translate-y-0.5">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Grocery Orders</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($stats['grocery'], 2) }}</p>
                        </div>
                        <span class="absolute top-2 right-2 p-2 bg-gray-100 rounded-lg text-xl">
                            <i class="fa-solid fa-sm fa-cart-shopping text-blue-400"></i>
                        </span>
                    </div>

                    <!-- Payments Card -->
                    <div class="module-card transition-all hover:-translate-y-0.5">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Payments</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($stats['payments'], 2) }}</p>
                        </div>
                        <span class="absolute top-2 right-2 p-2 bg-gray-100 rounded-lg text-xl">
                            <i class="fa-solid fa-sm fa-hand-holding-dollar text-red-400"></i>
                        </span>
                    </div>

                    <!-- Balance Card -->
                    <div class="module-card transition-all hover:-translate-y-0.5">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Current Balance</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($stats['balance'], 2) }}</p>
                        </div>
                        <span class="absolute top-2 right-2 p-2 bg-gray-100 rounded-lg text-xl">
                            <i class="fa-solid fa-sm fa-wallet text-slate-800"></i>
                        </span>
                    </div>

                </div>
            </section>

            <!-- Data Table Section -->
            <section class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">
                        @if($search)
                            <i class="fa-solid fa-magnifying-glass text-slate-800"></i>
                            Search Results for "{{ $search }}"
                        @else
                            <i class="fa-solid fa-clock-rotate-left text-slate-800"></i>
                            Unified Activity Log
                        @endif
                    </h3>
                    <span class="text-xs bg-gray-200 text-gray-700 px-2.5 py-1 rounded-full font-medium">
                        {{ $results->count() }} records displayed
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th class="px-6 py-3">Employee Details</th>
                                <th class="px-6 py-3">Module Source</th>
                                <th class="px-6 py-3">Transaction Date</th>
                                <th class="px-6 py-3 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($results as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">
                                            {{ $item->employee->name ?? 'Unknown Employee' }}
                                        </div>
                                        <div class="text-xs text-gray-400 font-mono">
                                            {{ $item->employee->employee_code ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @switch($item->module)
                                            @case('carenderia')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200">🍔 Carenderia</span>
                                                @break
                                            @case('loan')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">💰 Loan</span>
                                                @break
                                            @case('grocery')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-800 border border-indigo-200">🛒 Grocery</span>
                                                @break
                                            @case('payment')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-800 border border-rose-200">💳 Payment</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $item->date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold {{ $item->module == 'payment' ? 'text-emerald-600' : 'text-gray-900' }}">
                                        {{ $item->module == 'payment' ? '-' : '' }}₱{{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                        📭 No records found matching the query criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>
</div>
@endsection