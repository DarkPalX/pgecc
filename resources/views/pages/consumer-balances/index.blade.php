@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-y-auto">
        @include('components.topbar')

        <main class="flex-1 flex flex-col overflow-y-auto p-8 max-w-7xl mx-auto w-full space-y-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-200 pb-5">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                        <i class="fa-solid fa-wallet text-cyan-500"></i>
                        Consumer Balances
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Upload the employee-balance CSV and update consumer balances only.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('errors'))
                @foreach(session('errors') as $error)
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium">
                        {{ $error }}
                    </div>
                @endforeach
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                @if(auth()->check() && auth()->user()->hasPermission('upload_file'))
                <section class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-4" style="grid-column: 1 / -1; width: 100%;">
                    <h2 class="font-bold text-gray-800 text-lg">Batch Import Excel</h2>
                    <p class="text-xs text-gray-400">Use the same employee-balance CSV uploaded from the dashboard.</p>

                    <form action="{{ route('consumer-balances.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div id="dropzone" class="group border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-cyan-500 transition cursor-pointer relative bg-gray-50/50">
                            <input type="file" id="fileInput" name="excel_file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                            <span class="text-3xl block mb-2">
                                <i id="fileIcon" class="fa-solid fa-file-excel text-gray-300 group-hover:text-cyan-500 transition"></i>
                            </span>
                            <span id="mainText" class="text-sm font-medium text-gray-400 group-hover:text-gray-600 block pointer-events-none transition">Click to select or drag document here</span>
                            <span id="subText" class="text-xs text-gray-300 group-hover:text-gray-400 mt-1 block pointer-events-none transition">XLSX, XLS, or CSV format up to 10MB</span>
                            <div id="removeButtonContainer" class="mt-3 hidden relative z-20">
                                <button type="button" id="btnRemove" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition cursor-pointer">
                                    × Remove File
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition shadow-sm cursor-pointer">
                            Process Batch Upload
                        </button>
                    </form>
                </section>

                </section>
                @endif

                <section class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col" style="grid-column: 1 / -1; width: 100%;">
                    <form action="{{ route('consumer-balances.index') }}" method="GET" class="p-4 border-b border-gray-100 bg-gray-50/50 grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                        <div class="md:col-span-4">
                            <label class="text-xs font-bold uppercase text-gray-400 block mb-1">Filename Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search files..." class="w-full text-xs p-2 border border-gray-300 rounded-lg outline-none bg-white focus:border-cyan-500" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold uppercase text-gray-400 block mb-1">Status</label>
                            <select name="status" class="w-full text-xs p-2 border border-gray-300 rounded-lg outline-none bg-white focus:border-cyan-500">
                                <option value="">All States</option>
                                @foreach(['completed' => 'Completed', 'processing' => 'Processing', 'failed' => 'Failed'] as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold uppercase text-gray-400 block mb-1">From</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-xs p-1.5 border border-gray-300 rounded-lg outline-none bg-white focus:border-cyan-500" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold uppercase text-gray-400 block mb-1">To</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-xs p-1.5 border border-gray-300 rounded-lg outline-none bg-white focus:border-cyan-500" />
                        </div>
                        <div class="md:col-span-2 flex gap-1">
                            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold py-2 px-3 rounded-lg">Apply</button>
                            <a href="{{ route('consumer-balances.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold py-2 px-2 rounded-lg">×</a>
                        </div>
                    </form>

                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200 bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-3">File Information</th>
                                    <th class="px-6 py-3">Metrics</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Uploaded By</th>
                                    <th class="px-6 py-3">Date Uploaded</th>
                                    <th class="px-6 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse($files as $file)
                                    <tr class="hover:bg-gray-50/70 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900 truncate max-w-xs" title="{{ $file->original_filename }}">{{ $file->original_filename }}</div>
                                        </td>
                                        <td class="px-6 py-4"><span class="text-gray-900 font-medium">{{ $file->row_count }} balances listed</span></td>
                                        <td class="px-6 py-4">
                                            @if($file->status === 'completed')
                                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Completed</span>
                                            @elseif($file->status === 'processing')
                                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Processing</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">Failed</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 text-xs font-medium">{{ $file->admin->name ?? 'Unknown user' }}</td>
                                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $file->created_at->format('M d, Y h:i A') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('file-uploads.module.download', $file) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition">
                                                <i class="fa-solid fa-download"></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">No uploaded consumer balance documents found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($files->hasPages())
                        <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/50">{{ $files->links() }}</div>
                    @endif
                </section>
            </div>
        </main>
    </div>
</div>
@endsection
