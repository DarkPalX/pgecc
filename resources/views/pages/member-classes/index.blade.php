@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden bg-gray-100">
    @include('components.sidebar')

    <main class="flex-1 overflow-y-auto p-8">
        <div class="max-w-7xl mx-auto space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><i class="fa-solid fa-layer-group text-indigo-600 mr-2"></i>Member Classes</h1>
                <p class="text-sm text-gray-500 mt-1">Set the balance limits used to determine each employee's status.</p>
            </div>

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">{{ $errors->first() }}</div>
            @endif

            <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="font-bold text-gray-800 mb-4">Add Member Class</h2>
                <form action="{{ route('member-classes.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <label class="text-xs font-bold uppercase text-gray-500">Class Name<input name="name" required class="mt-1 w-full text-sm p-2.5 border border-gray-300 rounded-lg" placeholder="e.g. TITANIUM"></label>
                    <label class="text-xs font-bold uppercase text-gray-500">Carenderia Limit<input name="carenderia_limit" type="number" min="0" step="0.01" required class="mt-1 w-full text-sm p-2.5 border border-gray-300 rounded-lg" placeholder="0.00"></label>
                    <label class="text-xs font-bold uppercase text-gray-500">Consumer Limit<input name="consumer_limit" type="number" min="0" step="0.01" required class="mt-1 w-full text-sm p-2.5 border border-gray-300 rounded-lg" placeholder="0.00"></label>
                    <label class="text-xs font-bold uppercase text-gray-500">Maximum Loan<input name="maximum_loan" type="number" min="0" step="0.01" required class="mt-1 w-full text-sm p-2.5 border border-gray-300 rounded-lg" placeholder="0.00"></label>
                    <button class="md:col-span-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg text-sm">Create Class</button>
                </form>
            </section>

            <section class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/70"><h2 class="font-bold text-gray-800">Configured Classes</h2></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[760px]">
                        <thead><tr class="border-b border-gray-200 bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500"><th class="px-6 py-3">Class</th><th class="px-6 py-3">Carenderia Limit</th><th class="px-6 py-3">Consumer Limit</th><th class="px-6 py-3">Maximum Loan</th><th class="px-6 py-3 text-right">Actions</th></tr></thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($memberClasses as $memberClass)
                                <tr class="hover:bg-gray-50/70">
                                    <form id="edit-member-class-{{ $memberClass->id }}" action="{{ route('member-classes.update', $memberClass) }}" method="POST">@csrf @method('PUT')</form>
                                    <td class="px-6 py-4"><input form="edit-member-class-{{ $memberClass->id }}" name="name" value="{{ $memberClass->name }}" required class="w-full p-2 border border-gray-200 rounded-lg font-semibold uppercase"></td>
                                    <td class="px-6 py-4"><input form="edit-member-class-{{ $memberClass->id }}" name="carenderia_limit" type="number" min="0" step="0.01" value="{{ $memberClass->carenderia_limit }}" required class="w-full p-2 border border-gray-200 rounded-lg"></td>
                                    <td class="px-6 py-4"><input form="edit-member-class-{{ $memberClass->id }}" name="consumer_limit" type="number" min="0" step="0.01" value="{{ $memberClass->consumer_limit }}" required class="w-full p-2 border border-gray-200 rounded-lg"></td>
                                    <td class="px-6 py-4"><input form="edit-member-class-{{ $memberClass->id }}" name="maximum_loan" type="number" min="0" step="0.01" value="{{ $memberClass->maximum_loan }}" required class="w-full p-2 border border-gray-200 rounded-lg"></td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap"><button form="edit-member-class-{{ $memberClass->id }}" class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-2 rounded-lg text-xs font-semibold">Save</button> <button form="delete-member-class-{{ $memberClass->id }}" class="bg-rose-50 hover:bg-rose-100 text-rose-700 px-3 py-2 rounded-lg text-xs font-semibold">Delete</button></td>
                                </tr>
                                <form id="delete-member-class-{{ $memberClass->id }}" action="{{ route('member-classes.destroy', $memberClass) }}" method="POST" onsubmit="return confirm('Delete this member class?')">@csrf @method('DELETE')</form>
                            @empty
                                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No member classes configured.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
</div>
@endsection
