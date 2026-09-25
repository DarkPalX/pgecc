@extends('layouts.app')

@section('content')
<div class="flex min-h-screen w-full items-center justify-center bg-white px-4">
    <div class="w-full max-w-xl mx-auto rounded-3xl bg-white p-10 sm:p-14 shadow-2xl shadow-slate-200 border border-slate-100 p-6">
        
        <!-- Logo and Header -->
        <div class="mb-10 text-center flex flex-col items-center">
            <div class="mb-4 h-0 w-auto">
                <img src="{{ asset('pmc.png') }}" alt="PMC Logo" class="h-full w-auto object-contain" style="height: 170px;">
            </div>
            <div class="text-xs font-bold tracking-widest uppercase text-emerald-600">Philsaga Mining Corporation</div>
            <div class="text-2xl font-extrabold text-slate-900 mt-1">PGECC Portal</div>
            <p class="mt-1 text-sm text-gray-500">Sign in to continue</p>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus placeholder="name@philsagaming.com" class="w-full rounded-xl border border-gray-300 p-4 text-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 outline-none">
            </div>
            
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Password</label>
                <input type="password" name="password" autocomplete="current-password" required placeholder="••••••••" class="w-full rounded-xl border border-gray-300 p-4 text-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 outline-none">
            </div>
            
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="remember" name="remember" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="remember" class="text-sm text-gray-600 select-none cursor-pointer">Remember me</label>
            </div>

            <button class="w-full mt-3 rounded-xl bg-indigo-600 py-4 font-semibold text-white hover:bg-indigo-700 transition duration-200 shadow-md">
                Sign in
            </button>
        </form>
    </div>
</div>
@endsection