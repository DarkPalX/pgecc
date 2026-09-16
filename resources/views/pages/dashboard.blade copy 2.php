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
                {{-- <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-5 gap-6">
                    <div class="module-card transition-all hover:-translate-y-0.5 bg-white p-5 rounded-xl border border-gray-200 shadow-xs relative">
                        <p class="text-sm font-medium text-gray-500">Carenderia</p>
                        <p class="text-lg xl:text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($stats['carenderia'] ?? 0, 2) }}</p>
                    </div>

                    <div class="module-card transition-all hover:-translate-y-0.5 bg-white p-5 rounded-xl border border-gray-200 shadow-xs relative">
                        <p class="text-sm font-medium text-gray-500">Active Loans</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($stats['loans'] ?? 0, 2) }}</p>
                    </div>

                    <div class="module-card transition-all hover:-translate-y-0.5 bg-white p-5 rounded-xl border border-gray-200 shadow-xs relative">
                        <p class="text-sm font-medium text-gray-500">Grocery Orders</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($stats['grocery'] ?? 0, 2) }}</p>
                    </div>

                    <div class="module-card transition-all hover:-translate-y-0.5 bg-white p-5 rounded-xl border border-gray-200 shadow-xs relative">
                        <p class="text-sm font-medium text-gray-500">Total Payments</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($stats['payments'] ?? 0, 2) }}</p>
                    </div>

                    <div class="module-card transition-all hover:-translate-y-0.5 bg-white p-5 rounded-xl border border-gray-200 shadow-xs relative">
                        <p class="text-sm font-medium text-gray-500">Current Balance</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($stats['balance'] ?? 0, 2) }}</p>
                    </div>
                </div> --}}
            </section>

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

        </div>
    </main>
</div>

<!-- Employee Search Result Modal (Opaque Solid Retro Gray Frame) -->
<div id="searchModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <!-- Outer Retro Gray Dialog Card (Solid Background Fix) -->
    <div class="bg-[#d4d4d4] p-3 shadow-2xl border-2 border-black w-[480px] max-w-full font-sans relative">
        {{-- <button type="button" onclick="closeSearchModal()" class="absolute top-1 right-2 text-black font-extrabold text-lg hover:text-gray-700 cursor-pointer z-10">✕</button> --}}

        <!-- Inner Double Border Box -->
        <div class="border-2 border-black bg-white p-2 space-y-2 p-5">
            
            <!-- Result Box Outer Frame -->
            <div class="border-2 border-black bg-white p-5">
                <!-- Header Title -->
                <div class="bg-white text-center font-extrabold text-xs text-black py-1 tracking-wider uppercase border-b-2 border-black">
                    SEARCH RESULT
                </div>
                
                <!-- Date Bar -->
                <div class="bg-[#e2e2e2] text-center font-bold text-[11px] text-black py-0.5 border-b-2 border-black">
                    Balance Summary as of {{ now()->format('F d, Y') }}
                </div>

                <!-- Excel Exact Form Fields Table -->
                <table class="w-full text-[11px] font-bold text-black border-collapse bg-[#d4d4d4]">
                    <tbody class="divide-y-2 divide-black">
                        
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
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">CARENDERIA</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_carenderia_bal">-</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">CONSUMER</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_consumer_bal">-</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">LONG TERM LOAN</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_long_term_loan">-</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Excel Matching Field: TOTAL BALANCES -->
                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">TOTAL BALANCES</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate font-mono shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_total_balances">-</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Excel Matching Field: CONSUMER REMARK -->
                        <tr>
                            <td class="pl-2 py-1 uppercase whitespace-nowrap align-middle">CONSUMER REMARK</td>
                            <td class="text-center align-middle">:</td>
                            <td class="pr-2 py-0.5 align-middle">
                                <div class="bg-white px-2 py-0.5 border-2 border-black text-right truncate shadow-[inset_1px_1px_2px_rgba(0,0,0,0.4)]">
                                    <span id="m_consumer_remark">-</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Excel Matching Field: EXIT / CANCELLATION -->
                        <tr>
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

            <!-- Footer Action Button -->
            <div class="flex justify-start pt-1">
                <button type="button" onclick="closeSearchModal()" class="bg-white hover:bg-gray-200 border-2 border-black text-[11px] px-5 py-0.5 font-extrabold text-black cursor-pointer shadow-sm active:translate-y-0.5">
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
    if (isNaN(num) || num === 0) return '-';
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