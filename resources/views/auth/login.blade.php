@extends('layouts.app')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-slate-950 px-4">
    <div class="w-full grid grid-cols-12 justify-center">
        <div class="col-span-12 sm:col-span-8 sm:col-start-3 md:col-span-6 md:col-start-4 lg:col-span-4 lg:col-start-5 rounded-2xl bg-white p-8 shadow-2xl">
            <div class="mb-8 text-center">
                <div class="text-3xl font-black text-slate-900">PGECC</div>
                <p class="mt-2 text-sm text-gray-500">Sign in to continue</p>
            </div>

            @if($errors->any())
                <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus placeholder="Email address" class="w-full rounded-lg border border-gray-300 p-3">
                <input type="password" name="password" autocomplete="current-password" required placeholder="Password" class="w-full rounded-lg border border-gray-300 p-3">
                
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="rounded border-gray-300">
                    <label for="remember" class="text-sm text-gray-600 select-none cursor-pointer">Remember me</label>
                </div>

                <button class="w-full rounded-lg bg-indigo-600 py-3 font-semibold text-white hover:bg-indigo-700">
                    Sign in
                </button>
            </form>
        </div>
    </div>
</div>
@endsection