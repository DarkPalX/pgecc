@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden bg-gray-100">
    @include('components.sidebar')
    <div class="flex-1 flex flex-col overflow-y-auto">
        @include('components.topbar')
        <main class="w-full flex-1 p-6 lg:p-8">
            <div class="mb-6 border-b border-gray-200 pb-5"><h1 class="text-2xl font-bold text-gray-900"><i class="fa-solid fa-user-group text-violet-500 mr-2"></i>User & Employee Directory</h1><p class="mt-1 text-sm text-gray-500">Create administrators, assign access, and register employee profiles.</p></div>
            @if(session('success'))<div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ $errors->first() }}</div>@endif
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <nav class="flex border-b border-gray-200 bg-gray-50"><a href="{{ route('users.index',['tab'=>'admins']) }}" class="flex-1 px-5 py-4 text-center text-sm font-bold {{ $tab==='admins' ? 'border-b-2 border-slate-900 bg-white text-slate-900' : 'text-gray-500' }}"><i class="fa-solid fa-user-shield mr-2"></i>Platform Admins ({{ $admins->total() }})</a><a hidden href="{{ route('users.index',['tab'=>'employees']) }}" class="flex-1 px-5 py-4 text-center text-sm font-bold {{ $tab==='employees' ? 'border-b-2 border-indigo-600 bg-white text-indigo-600' : 'text-gray-500' }}"><i class="fa-solid fa-users mr-2"></i>Registered Employees ({{ $employees->total() }})</a></nav>
                <div class="p-5 lg:p-7">
                @if($tab === 'admins')
                    <section class="mb-7 rounded-xl border border-slate-200 bg-slate-50 p-5"><div class="mb-4"><h2 class="font-bold text-slate-900"><i class="fa-solid fa-user-plus mr-2 text-indigo-600"></i>Create Platform Admin</h2><p class="mt-1 text-xs text-gray-500">Create login credentials and assign access immediately.</p></div><form action="{{ route('users.store.admin') }}" method="POST" class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-5">@csrf<input name="name" value="{{ old('name') }}" required placeholder="Full name" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm"><input type="email" name="email" value="{{ old('email') }}" required placeholder="Email address" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm"><input type="password" name="password" required placeholder="Password (8+ characters)" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm"><input type="password" name="password_confirmation" required placeholder="Confirm password" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm"><button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Create admin</button><div class="md:col-span-2 xl:col-span-5 flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-slate-200 pt-3"><span class="text-xs font-bold uppercase tracking-wide text-gray-500">Permissions:</span>@foreach(['upload_file'=>'Upload files','manage_member_classes'=>'Manage member classes','manage_users'=>'Manage users'] as $permission=>$label)<label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="permissions[]" value="{{ $permission }}" class="rounded border-gray-300 text-indigo-600" {{ in_array($permission,old('permissions',[])) ? 'checked' : '' }}> {{ $label }}</label>@endforeach</div></form></section>
                    <section><form action="{{ route('users.index') }}" method="GET" class="mb-4 flex gap-2"><input type="hidden" name="tab" value="admins"><input name="search" value="{{ $search ?? '' }}" placeholder="Search by name or email" class="flex-1 rounded-lg border border-gray-300 p-2.5 text-sm"><button class="rounded-lg bg-slate-900 px-5 text-sm text-white">Search</button></form><div class="overflow-x-auto rounded-xl border border-gray-200"><table class="w-full min-w-[760px] text-left text-sm"><thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="w-1/4 px-5 py-3">Admin</th><th class="w-1/3 px-5 py-3">Assigned access</th><th class="px-5 py-3">Update permissions</th></tr></thead><tbody class="divide-y divide-gray-100">@forelse($admins as $admin)<tr><td class="px-5 py-4 align-top"><div class="font-semibold text-gray-900">{{ $admin->name }}</div><div class="text-xs text-gray-500">{{ $admin->email }}</div><span class="mt-2 inline-block rounded-full bg-slate-100 px-2 py-1 text-xs text-slate-600">{{ $admin->role==='super_admin' ? 'Super admin' : 'Platform admin' }}</span></td><td class="px-5 py-4 align-top"><div class="flex flex-wrap gap-1">@forelse($admin->permissions as $permission)<span class="rounded-full bg-indigo-50 px-2 py-1 text-xs text-indigo-700">{{ ucwords(str_replace('_',' ',$permission->permission)) }}</span>@empty<span class="text-xs text-gray-400">No assigned permissions</span>@endforelse</div></td><td class="px-5 py-4 align-top"><form action="{{ route('users.permissions.update',$admin) }}" method="POST">@csrf @method('PUT')<div class="flex flex-wrap gap-x-4 gap-y-2">@foreach(['upload_file'=>'Upload files','manage_member_classes'=>'Member classes','manage_users'=>'Manage users'] as $permission=>$label)<label class="text-xs"><input type="checkbox" name="permissions[]" value="{{ $permission }}" class="mr-1 rounded border-gray-300 text-indigo-600" {{ $admin->hasPermission($permission) && $admin->role !== 'super_admin' ? 'checked' : '' }}> {{ $label }}</label>@endforeach</div><button class="mt-3 text-xs font-semibold text-indigo-600 hover:text-indigo-800">Save access</button></form></td></tr>@empty<tr><td colspan="3" class="px-5 py-12 text-center text-gray-400">No administrator accounts found.</td></tr>@endforelse</tbody></table></div></section>
                @else
                    <section class="mb-7 rounded-xl border border-indigo-100 bg-indigo-50 p-5"><div class="mb-4"><h2 class="font-bold text-slate-900"><i class="fa-solid fa-user-plus mr-2 text-indigo-600"></i>Register Employee</h2><p class="mt-1 text-xs text-gray-500">Create a reference profile for transaction records.</p></div><form action="{{ route('users.store.employee') }}" method="POST" class="grid grid-cols-1 gap-3 md:grid-cols-3">@csrf<input name="employee_code" required placeholder="Employee ID code" class="rounded-lg border border-gray-300 p-2.5 text-sm"><input name="name" required placeholder="Full name" class="rounded-lg border border-gray-300 p-2.5 text-sm"><button class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Save employee profile</button></form></section>
                    <section><form action="{{ route('users.index') }}" method="GET" class="mb-4 flex gap-2"><input type="hidden" name="tab" value="employees"><input name="search" value="{{ $search ?? '' }}" placeholder="Search by employee code or name" class="flex-1 rounded-lg border border-gray-300 p-2.5 text-sm"><button class="rounded-lg bg-slate-900 px-5 text-sm text-white">Search</button></form><div class="overflow-x-auto rounded-xl border border-gray-200"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="px-5 py-3">Employee code</th><th class="px-5 py-3">Full name</th><th class="px-5 py-3">Status</th></tr></thead><tbody class="divide-y divide-gray-100">@forelse($employees as $employee)<tr><td class="px-5 py-4 font-mono text-xs font-bold">{{ $employee->employee_code }}</td><td class="px-5 py-4 font-medium">{{ $employee->name }}</td><td class="px-5 py-4"><span class="rounded-full bg-emerald-50 px-2 py-1 text-xs text-emerald-700">Active</span></td></tr>@empty<tr><td colspan="3" class="px-5 py-12 text-center text-gray-400">No employee profiles found.</td></tr>@endforelse</tbody></table></div></section>
                @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Prevent the browser from inserting saved credentials into the new-admin form.
    document.querySelectorAll('form[action*="/users/admin"] input').forEach((input) => {
        input.setAttribute('autocomplete', input.type === 'password' ? 'new-password' : 'off');
        if (!{{ $errors->any() ? 'true' : 'false' }} && input.type !== 'hidden' && input.type !== 'checkbox') input.value = '';
    });
    document.querySelectorAll('input[type="checkbox"]').forEach((input) => input.style.marginRight = '.45rem');
</script>
@endpush

@push('page-css')
<style>
    label:has(> input[type="checkbox"]) { display: inline-flex !important; align-items: center; gap: .45rem !important; }
    label:has(> input[type="checkbox"]) input[type="checkbox"] { margin: 0 !important; }
</style>
@endpush
