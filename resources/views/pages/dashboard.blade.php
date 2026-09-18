@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden bg-gray-100">
    <!-- Sidebar Navigation -->
    @include('components.sidebar')

    <!-- Main Content Section -->
    <main class="flex-1 flex flex-col overflow-y-auto">
        
        <!-- Dashboard Top Navbar -->
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10 shadow-xs h-16">
            <span id="sidebarToggle" class="p-2 hover:bg-gray-100 rounded-lg text-xl transition-all cursor-pointer"></span>
            
            <!-- Ajax Header Search -->
            <div class="w-full max-w-xl flex gap-2">
                <div class="relative w-full flex items-center">
                    <input 
                        type="text" 
                        id="modalSearchInput" 
                        placeholder="Search PMC ID or Employee Name..." 
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block py-2 pl-4 pr-10 outline-none transition text-sm"
                    />
                    <button type="button" onclick="clearSearchInput()" class="absolute right-3 text-xs text-gray-400 hover:text-gray-600 font-medium cursor-pointer">✕</button>
                </div>
                <button type="button" onclick="performModalSearch()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 rounded-lg text-sm transition-all active:scale-95 cursor-pointer">
                    Search
                </button>
            </div>

            <div class="hidden lg:block text-sm font-medium text-gray-600">
                {{ now()->format('F d, Y') }}
            </div>
        </header>

        <!-- Main Workspace -->
        <div class="p-8 max-w-7xl w-full mx-auto space-y-8">

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <p><i class="fa-solid fa-circle-exclamation text-rose-600"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Batch Upload Section (Draggable Dropzone + Action Button) -->
            <section class="bg-white rounded-2xl p-6 border border-gray-200 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Batch Upload Excel Data</h3>
                        <p class="text-xs text-gray-500">Drag and drop spreadsheet file or click to select.</p>
                        <p class="text-xs text-rose-500">Note: Convert the file to CSV(Comma delimited) first before uploading</p>
                    </div>
                </div>

                <form id="batchUploadForm" action="{{ route('balances.import') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoadingOverlay()">
                    @csrf
                    <div 
                        id="dropZone" 
                        class="border-2 border-dashed border-gray-300 hover:border-blue-500 hover:bg-blue-50/50 rounded-xl p-8 text-center transition-all cursor-pointer bg-gray-50 flex flex-col items-center justify-center gap-2"
                        onclick="document.getElementById('fileInput').click()"
                    >
                        <input type="file" id="fileInput" name="file" accept=".xlsx, .xls, .csv" class="hidden" onchange="handleFileSelect(this.files)" required>
                        
                        <div class="p-3 bg-white rounded-full shadow-xs border border-gray-200 text-blue-600">
                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                        </div>
                        <p id="uploadPrompt" class="text-sm font-medium text-gray-600">
                            <span class="text-blue-600 font-semibold">Click to upload</span> or drag and drop Excel file here
                        </p>
                        <p class="text-xs text-gray-400">Supports .xlsx, .xls, or .csv files</p>

                        <!-- File Info Display -->
                        <div id="fileDetails" class="hidden mt-2 p-2 px-4 bg-blue-100 text-blue-800 text-xs font-semibold rounded-lg border border-blue-200 flex items-center gap-3">
                            <i class="fa-solid fa-file-excel text-green-600 text-sm"></i>
                            <span id="fileName" class="truncate max-w-[300px]"></span>
                            <button type="button" onclick="removeFile(event)" class="text-rose-600 hover:text-rose-800 font-bold ml-2 cursor-pointer">✕</button>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end gap-3">
                        <button type="submit" onclick="triggerBatchUpload(event)" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg text-sm transition cursor-pointer flex items-center gap-2 shadow-xs">
                            <i class="fa-solid fa-paper-plane"></i> Process Batch Upload
                        </button>
                        <a href="{{ asset('EMPLOYEE_BALANCE SUMMARY.csv') }}" download="EMPLOYEE_BALANCE SUMMARY.csv" class="text-white font-semibold py-2.5 px-6 rounded-lg text-sm transition cursor-pointer flex items-center gap-2 shadow-xs" style="background-color: #4b5563;">
                            <i class="fa-solid fa-download"></i> Download Sample Excel
                        </a>
                    </div>
                </form>
            </section>

            <!-- Stat Cards Grid -->
            <section>
                <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-4">Module Statistics (Grand Totals)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-5 gap-6">
                    <!-- Carenderia -->
                    <div class="module-card transition-all hover:-translate-y-0.5 bg-white p-5 rounded-xl border border-gray-200 shadow-xs relative flex flex-col items-center justify-center text-center">
                        <p class="text-xl xl:text-2xl font-black text-gray-900 tracking-tight">₱{{ number_format($employee_balances->sum('carenderia_bal') ?? 0, 2) }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mt-1">Carenderia</p>
                    </div>

                    <!-- Short Term Loans -->
                    <div class="module-card transition-all hover:-translate-y-0.5 bg-white p-5 rounded-xl border border-gray-200 shadow-xs relative flex flex-col items-center justify-center text-center">
                        <p class="text-xl xl:text-2xl font-black text-gray-900 tracking-tight">₱{{ number_format($employee_balances->sum('short_term_loan') ?? 0, 2) }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mt-1">Short Term Loans</p>
                    </div>

                    <!-- Long Term Loans -->
                    <div class="module-card transition-all hover:-translate-y-0.5 bg-white p-5 rounded-xl border border-gray-200 shadow-xs relative flex flex-col items-center justify-center text-center">
                        <p class="text-xl xl:text-2xl font-black text-gray-900 tracking-tight">₱{{ number_format($employee_balances->sum('long_term_loan') ?? 0, 2) }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mt-1">Long Term Loans</p>
                    </div>

                    <!-- Consumer Balances -->
                    <div class="module-card transition-all hover:-translate-y-0.5 bg-white p-5 rounded-xl border border-gray-200 shadow-xs relative flex flex-col items-center justify-center text-center">
                        <p class="text-xl xl:text-2xl font-black text-gray-900 tracking-tight">₱{{ number_format($employee_balances->sum('consumer_bal') ?? 0, 2) }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mt-1">Consumer Balances</p>
                    </div>

                    <!-- Current Balance -->
                    <div class="module-card transition-all hover:-translate-y-0.5 bg-white p-5 rounded-xl border border-gray-200 shadow-xs relative flex flex-col items-center justify-center text-center">
                        <p class="text-xl xl:text-2xl font-black text-gray-900 tracking-tight">₱{{ number_format($employee_balances->sum('total_balances') ?? 0, 2) }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mt-1">Current Balance</p>
                    </div>
                </div>
            </section>

            <!-- Excel Upload History -->
            <section class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Excel Upload History</h3>
                    <span class="text-xs bg-gray-200 text-gray-700 px-2.5 py-1 rounded-full font-medium">{{ $fileUploads->count() }} uploads displayed</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th class="px-6 py-3">Filename</th>
                                <th class="px-6 py-3">Uploaded At</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($fileUploads as $fileUpload)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $fileUpload->display_filename }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $fileUpload->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('file-uploads.download', $fileUpload) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition"><i class="fa-solid fa-download"></i> Download</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-12 text-center text-gray-400"><i class="fa-solid fa-file-excel mb-2 text-lg"></i><div>No Excel uploads found.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            @if(false)
            <!-- Data Table Section -->
            <section class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Unified Activity Log</h3>
                    <span class="text-xs bg-gray-200 text-gray-700 px-2.5 py-1 rounded-full font-medium">
                        {{ isset($results) ? $results->count() : 0 }} records displayed
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
                            @forelse($results ?? [] as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $item->employee->name ?? 'Unknown Employee' }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ $item->employee->employee_code ?? 'N/A' }}</div>
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
                                            @default
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">{{ ucfirst($item->module) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ optional($item->date)->format('M d, Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-right font-semibold {{ $item->module == 'payment' ? 'text-emerald-600' : 'text-gray-900' }}">
                                        {{ $item->module == 'payment' ? '-' : '' }}₱{{ number_format($item->total ?? 0, 2) }}
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
            @endif

        </div>
    </main>
</div>

<style>
    #searchModalCard table td:first-child {
        width: 42%;
        padding: 0.55rem 0.35rem 0.55rem 0;
        color: #475569;
        letter-spacing: 0.025em;
    }

    #searchModalCard,
    #searchModalCard > .bg-slate-50\/70 {
        background: #ffffff !important;
    }

    #m_employee_details tbody {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0 1rem;
    }

    #m_employee_details tbody tr {
        display: grid;
        grid-template-columns: minmax(0, 42%) 1rem minmax(0, 1fr);
        align-items: center;
        border: 0 !important;
    }

    #m_employee_details tbody tr:has(#m_name) {
        display: none;
    }

    #m_balance_details tbody tr {
        display: grid;
        grid-template-columns: minmax(0, 42%) 1rem minmax(0, 1fr);
        align-items: center;
    }

    #m_balance_details tbody {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.75rem;
    }

    #m_balance_details tbody tr:not(#m_loan_status_row) {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 0.35rem;
        padding: 0.9rem;
        min-height: 6.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.85rem;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
    }

    #m_balance_details tbody tr:not(#m_loan_status_row):hover {
        transform: translateY(-3px);
        border-color: #93c5fd;
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.12);
    }

    #m_balance_details tbody tr.hidden {
        display: none !important;
    }

    #m_balance_details tbody tr:not(#m_loan_status_row) td:first-child {
        width: 100%;
        padding: 0;
        color: #64748b;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.06em;
    }

    #m_balance_details tbody tr:not(#m_loan_status_row) td:nth-child(2) {
        display: none;
    }

    #m_balance_details tbody tr:not(#m_loan_status_row) td:nth-child(3) {
        width: 100%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.65rem;
    }

    #m_balance_details tbody tr:not(#m_loan_status_row) td:nth-child(3) > div {
        min-height: 0;
        justify-content: flex-start;
        padding: 0;
        background: transparent !important;
        border: 0 !important;
        color: #0f172a;
        font-size: 1rem;
        font-weight: 800;
    }

    #m_balance_details .balance-ring {
        flex: 0 0 auto;
        width: 3.25rem;
        height: 3.25rem;
        display: grid !important;
        place-items: center;
        padding: 0 !important;
        border: 4px solid transparent !important;
        border-radius: 999px;
        background: conic-gradient(var(--ring-color, #2dd4bf) var(--ring-progress, 0deg), #e2e8f0 0deg) !important;
        position: relative;
        color: #64748b;
        font-size: 0.65rem;
        font-weight: 800;
    }

    #m_balance_details .balance-ring::before {
        content: '';
        position: absolute;
        inset: 4px;
        background: #ffffff;
        border-radius: inherit;
    }

    #m_balance_details .balance-ring span {
        position: relative;
        z-index: 1;
    }

    #m_balance_details .balance-ring.is-exceeded {
        --ring-color: #ef4444;
        color: #dc2626;
    }

    #m_balance_details #m_loan_status_row {
        grid-column: 1 / -1;
    }

    @media (max-width: 900px) {
        #m_balance_details tbody {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        #m_employee_details tbody {
            grid-template-columns: 1fr;
        }

        #m_balance_details tbody {
            grid-template-columns: 1fr;
        }
    }

    #searchModalCard table td:nth-child(2) {
        width: 1rem;
        color: #cbd5e1;
        text-align: center;
    }

    #searchModalCard table tr:not(#m_loan_status_row) td:nth-child(3) {
        padding: 0.35rem 0 0.35rem 0.5rem;
    }

    #searchModalCard table tr:not(#m_loan_status_row) td:nth-child(3) > div {
        min-height: 2rem;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 0.45rem 0.75rem;
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.5rem;
        box-shadow: none !important;
        color: #1e293b;
    }

    #searchModalCard table tr:not(#m_loan_status_row) td:nth-child(3) > div span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    #searchModalCard #m_loan_limit_box.bg-red-100 {
        background: #fef2f2 !important;
        border-color: #fca5a5 !important;
        color: #b91c1c !important;
    }

    @media (max-width: 420px) {
        #searchModalCard table td:first-child {
            width: 45%;
            font-size: 9px;
        }

        #searchModalCard table tr:not(#m_loan_status_row) td:nth-child(3) > div {
            padding: 0.4rem 0.55rem;
        }
    }
</style>

<!-- Employee Search Result Modal (Opaque Solid Retro Gray Frame) -->
<div id="searchModal" class="fixed inset-0 bg-slate-950/55 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4 sm:p-6">
    <!-- Responsive result card -->
    <div id="searchModalCard" class="bg-white p-2 sm:p-3 shadow-2xl border border-slate-200 rounded-2xl w-full max-w-4xl max-h-[92vh] overflow-y-auto font-sans relative transition-colors duration-300">
        {{-- <button type="button" onclick="closeSearchModal()" class="absolute top-1 right-2 text-black font-extrabold text-lg hover:text-gray-700 cursor-pointer z-10">✕</button> --}}

        <!-- Inner Double Border Box -->
        <div class="bg-slate-50/70 p-1 sm:p-2 space-y-3 rounded-xl">
            
            <!-- Result Box Outer Frame -->
            <div class="border border-slate-200 bg-white p-4 sm:p-6 rounded-xl shadow-sm">
                <!-- Header Title -->
                <div class="text-center font-bold text-[10px] text-slate-400 py-1 tracking-[0.22em] uppercase">
                    SEARCH RESULT
                </div>
                
                <!-- Date Bar -->
                        <div class="bg-slate-50 text-center font-bold text-xs sm:text-sm text-slate-700 py-2 border-y border-slate-200 rounded-lg">
                            Balance Summary as of {{ now()->format('F d, Y') }}
                        </div>

                        <div class="mt-5 mb-4">
                            <div class="text-[10px] font-bold tracking-[0.2em] uppercase text-slate-400">Employee Profile</div>
                            <div class="mt-1 text-2xl sm:text-4xl font-black tracking-tight text-slate-900 truncate">
                                <span id="m_name_header">-</span>
                            </div>
                        </div>

                <!-- Balance fields -->
                <div class="space-y-5 mt-3">
                    <div class="pt-5 sm:pt-7">
                <table id="m_employee_details" class="w-full text-[10px] sm:text-xs font-semibold text-slate-700 border-collapse bg-white mt-3">
                    <tbody class="divide-y divide-slate-100">
                        
                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle w-[160px]">ID NUMBER</td>
                            <td class="text-center align-middle w-4">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_pmc_id">-</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">NAME</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_name">-</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">DEPARTMENT</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_department">-</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">MEMBER STATUS</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_member_status">-</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">MEMBERSHIP CLASS</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_mem_class">-</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">CARENDERIA WAIVED</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_carenderia_waived">-</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">SHARE CAPITAL</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_share_capital">-</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">SHORT TERM LOAN</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_short_term_loan">-</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">MAXIMUM LOAN</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div id="m_loan_limit_box" class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_loan_limit">-</span>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>

                    </div>
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div class="flex items-center gap-2 text-[10px] font-bold tracking-widest uppercase text-slate-400">
                                <span class="text-base text-blue-500">◉</span> Balance Overview
                            </div>
                            <div id="m_balance_percentage" class="text-sm font-black text-slate-700">0%</div>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden mb-4">
                            <div id="m_balance_progress" class="h-full w-0 bg-blue-500 rounded-full transition-all duration-700"></div>
                        </div>
                        <table id="m_balance_details" class="w-full text-[10px] sm:text-xs font-semibold text-slate-700 border-collapse bg-white">
                            <tbody>

                                <tr id="m_loan_status_row" class="hidden">
                                    <td colspan="3" class="px-2 py-2 text-center uppercase font-extrabold text-red-700 bg-red-50 border border-red-300 rounded-lg" style="width: 100%;">
                                        <span aria-hidden="true" class="mr-1">⚠</span><span id="m_loan_status">BALANCE EXCEEDED</span>
                                    </td>
                                </tr>

                                <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle"><span class="mr-1">🍽</span>CARENDERIA</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_carenderia_bal">-</span>
                                </div>
                                <div id="m_carenderia_ring" class="balance-ring" style="--ring-progress: 0deg"><span id="m_carenderia_pct">0%</span></div>
                            </td>
                                </tr>

                                <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle"><span class="mr-1">🛒</span>CONSUMER</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_consumer_bal">-</span>
                                </div>
                                <div id="m_consumer_ring" class="balance-ring" style="--ring-progress: 0deg"><span id="m_consumer_pct">0%</span></div>
                            </td>
                                </tr>

                                <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle"><span class="mr-1">📈</span>LONG TERM LOAN</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_long_term_loan">-</span>
                                </div>
                                <div id="m_long_term_ring" class="balance-ring" style="--ring-progress: 0deg"><span id="m_long_term_pct">0%</span></div>
                            </td>
                                </tr>

                                <!-- Excel Matching Field: TOTAL BALANCES -->
                                <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle"><span class="mr-1">💰</span>TOTAL BALANCES</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_total_balances">-</span>
                                </div>
                                <div id="m_total_ring" class="balance-ring" style="--ring-progress: 0deg"><span id="m_total_pct">0%</span></div>
                            </td>
                                </tr>

                                <!-- Excel Matching Field: CONSUMER REMARK -->
                                <tr hidden>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">CONSUMER REMARK</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_consumer_remark">-</span>
                                </div>
                            </td>
                                </tr>

                                <!-- Excel Matching Field: EXIT / CANCELLATION -->
                                <tr hidden>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">EXIT / CANCELLATION</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_exit_cancellation">-</span>
                                </div>
                            </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer Action Button -->
            <div class="flex justify-end pt-1 px-1 sm:px-2">
                <button type="button" onclick="closeSearchModal()" class="bg-slate-900 hover:bg-slate-700 border border-slate-900 rounded-lg text-xs px-5 py-2 font-semibold text-white cursor-pointer shadow-sm transition active:scale-95">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Full Screen Processing Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-slate-900/80 bg-white flex flex-col items-center justify-center text-dark hidden">
    <div class="animate-spin rounded-full h-14 w-14 border-4 border-white border-t-transparent mb-4"></div>
    <h3 class="text-lg font-bold">Uploading & Processing Records...</h3>
    <p class="text-xs text-gray-300 mt-1">Please wait, do not close or refresh this tab.</p>
</div>

<script>
// File Selection Handler
function handleFileSelect(files) {
    if (files && files.length > 0) {
        const file = files[0];
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileDetails').classList.remove('hidden');
        document.getElementById('uploadPrompt').classList.add('hidden');
    }
}

function removeFile(event) {
    event.stopPropagation();
    document.getElementById('fileInput').value = '';
    document.getElementById('fileDetails').classList.add('hidden');
    document.getElementById('uploadPrompt').classList.remove('hidden');
}

function triggerBatchUpload(e) {
    const fileInput = document.getElementById('fileInput');
    if (fileInput && fileInput.files.length > 0) {
        showLoadingOverlay();
    }
}

function showLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.remove('hidden');
        overlay.style.display = 'flex';
    }
}

// Drag & Drop Configuration
const dropZone = document.getElementById('dropZone');
if (dropZone) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        document.getElementById('fileInput').files = files;
        handleFileSelect(files);
    });
}

// Modal Search Logic via POST
const shortTermLoanLimits = {
    COPPER: 4000,
    BRONZE: 10000,
    SILVER: 15000,
    GOLD: 20000,
    DIAMOND: 25000,
    TITANIUM: 30000,
    PLATINUM: 35000,
};

function resetLoanIndicator() {
    const modalCard = document.getElementById('searchModalCard');
    const limitBox = document.getElementById('m_loan_limit_box');
    const statusRow = document.getElementById('m_loan_status_row');
    const percentage = document.getElementById('m_balance_percentage');
    const progress = document.getElementById('m_balance_progress');

    modalCard.classList.remove('bg-red-100', 'border-red-700');
    modalCard.classList.add('bg-white', 'border-slate-200');
    limitBox.classList.remove('bg-red-100', 'border-red-700', 'text-red-700');
    limitBox.classList.add('bg-white', 'border-slate-200', 'text-black');
    statusRow.classList.add('hidden');
    percentage.textContent = '0%';
    percentage.classList.remove('text-red-600');
    percentage.classList.add('text-slate-700');
    progress.style.width = '0%';
    progress.classList.remove('bg-red-500');
    progress.classList.add('bg-blue-500');

    resetBalanceRings();
}

function resetBalanceRings() {
    ['carenderia', 'consumer', 'long_term', 'total'].forEach((name) => {
        const ring = document.getElementById(`m_${name}_ring`);
        const percentage = document.getElementById(`m_${name}_pct`);
        if (!ring || !percentage) return;
        ring.style.setProperty('--ring-progress', '0deg');
        ring.classList.remove('is-exceeded');
        percentage.textContent = '0%';
    });
}

function updateBalanceRings(maximumLoan, balances) {
    const ringColors = {
        carenderia: '#2dd4bf',
        consumer: '#38bdf8',
        long_term: '#ec4899',
        total: '#f59e0b',
    };

    Object.entries(balances).forEach(([name, balance]) => {
        const ring = document.getElementById(`m_${name}_ring`);
        const percentage = document.getElementById(`m_${name}_pct`);
        if (!ring || !percentage || !maximumLoan) return;

        const actualPercentage = Math.max(0, (balance / maximumLoan) * 100);
        const displayPercentage = Math.min(Math.round(actualPercentage), 100);
        ring.style.setProperty('--ring-progress', `${displayPercentage * 3.6}deg`);
        ring.style.setProperty('--ring-color', ringColors[name]);
        ring.classList.toggle('is-exceeded', actualPercentage > 100);
        percentage.textContent = `${displayPercentage}%`;
        ring.title = `${Math.round(actualPercentage)}% of maximum`;
    });
}

function updateLoanIndicator(memClass, totalBalances, balances = {}) {
    const normalizedClass = String(memClass || '').trim().toUpperCase();
    const maximumLoan = shortTermLoanLimits[normalizedClass];
    const loanLimit = document.getElementById('m_loan_limit');
    const statusRow = document.getElementById('m_loan_status_row');
    const limitBox = document.getElementById('m_loan_limit_box');
    const percentage = document.getElementById('m_balance_percentage');
    const progress = document.getElementById('m_balance_progress');
    const modalCard = document.getElementById('searchModalCard');

    resetLoanIndicator();
    document.getElementById('m_mem_class').textContent = normalizedClass || '-';

    if (maximumLoan === undefined) {
        loanLimit.textContent = '-';
        return;
    }

    loanLimit.textContent = formatCurrency(maximumLoan);

    const balancePercentage = Math.max(0, (totalBalances / maximumLoan) * 100);
    percentage.textContent = `${Math.min(Math.round(balancePercentage), 100)}%`;
    progress.style.width = `${Math.min(balancePercentage, 100)}%`;
    updateBalanceRings(maximumLoan, { ...balances, total: totalBalances });

    if (totalBalances > maximumLoan) {
        statusRow.classList.remove('hidden');
        limitBox.classList.remove('bg-white', 'border-slate-200', 'text-black');
        limitBox.classList.add('bg-red-100', 'border-red-700', 'text-red-700');
        percentage.classList.remove('text-slate-700');
        percentage.classList.add('text-red-600');
        progress.classList.remove('bg-blue-500');
        progress.classList.add('bg-red-500');
    }
}

function performModalSearch() {
    const input = document.getElementById('modalSearchInput');
    const query = input ? input.value.trim() : '';
    if (!query) return;

    fetch("{{ route('balances.search') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ query: query })
    })
    .then(res => res.json())
    .then(res => {
        if (res.success && res.data) {
            const data = res.data;
            document.getElementById('m_pmc_id').textContent = data.pmc_id ? ('PMC-' + data.pmc_id.replace(/^PMC-?/i, '')) : (data.employee_code || '-');
            document.getElementById('m_name').textContent = (data.name || '-').toUpperCase();
            document.getElementById('m_name_header').textContent = (data.name || '-').toUpperCase();
            document.getElementById('m_department').textContent = (data.department || '-').toUpperCase();
            document.getElementById('m_member_status').textContent = (data.member_status || 'ACTIVE').toUpperCase();
            
            // Numeric Balances
            const stLoan = parseFloat(data.short_term_loan) || 0;
            const carenderia = parseFloat(data.carenderia_bal) || 0;
            const consumer = parseFloat(data.consumer_bal) || 0;
            const ltLoan = parseFloat(data.long_term_loan) || 0;

            document.getElementById('m_carenderia_waived').textContent = formatCurrency(data.carenderia_waived);
            document.getElementById('m_share_capital').textContent = formatCurrency(data.share_capital);
            document.getElementById('m_short_term_loan').textContent = formatCurrency(stLoan);
            document.getElementById('m_carenderia_bal').textContent = formatCurrency(carenderia);
            document.getElementById('m_consumer_bal').textContent = formatCurrency(consumer);
            document.getElementById('m_long_term_loan').textContent = formatCurrency(ltLoan);

            // Compute TOTAL BALANCES automatically (or use database field if calculated in backend)
            const computedTotal = data.total_balances !== undefined && data.total_balances !== null 
                ? parseFloat(data.total_balances) 
                : (stLoan + carenderia + consumer + ltLoan);

            document.getElementById('m_total_balances').textContent = formatCurrency(computedTotal);

            // Compare the employee's complete balance against the maximum for their class.
            updateLoanIndicator(data.mem_class, computedTotal, {
                carenderia: carenderia,
                consumer: consumer,
                long_term: ltLoan,
            });

            // Remarks & Status Text Fields
            document.getElementById('m_consumer_remark').textContent = data.consumer_remark || '-';
            document.getElementById('m_exit_cancellation').textContent = data.exit_cancellation || '-';

            document.getElementById('searchModal').classList.remove('hidden');
        } else {
            alert(res.message || ('No employee balance record found for: ' + query));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Failed to retrieve search results.');
    });
}

function closeSearchModal() {
    document.getElementById('searchModal').classList.add('hidden');
}

function clearSearchInput() {
    const input = document.getElementById('modalSearchInput');
    if (input) input.value = '';
}

function formatCurrency(val) {
    if (val === null || val === undefined) return '-';
    const num = parseFloat(val);
    if (isNaN(num)) return '-';
    return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

document.getElementById('modalSearchInput')?.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        performModalSearch();
    }
});
</script>
@endsection
