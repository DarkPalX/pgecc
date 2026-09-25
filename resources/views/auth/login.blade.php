@extends('layouts.app')

@section('content')
<div class="login-page">
    <section class="login-brand" aria-label="PMC">
        <img src="{{ asset('pmc.png') }}" alt="PMC Logo" class="login-logo">
    </section>

    <main class="login-panel">
        <div class="login-form-wrap">
            <header class="login-header">
                <h1>Log In</h1>
                <p>Welcome to PGECC Admin Portal.</p>
                <p>Please sign in to continue.</p>
            </header>

            @if($errors->any())
                <div class="login-error" role="alert">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="login-form">
                @csrf
                <div class="login-field">
                    <label for="email"><span aria-hidden="true">*</span> Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                </div>

                <div class="login-field">
                    <label for="password"><span aria-hidden="true">*</span> Password</label>
                    <input id="password" type="password" name="password" autocomplete="current-password" required>
                </div>

                <label class="remember-field" for="remember">
                    <input type="checkbox" id="remember" name="remember">
                    <span>Remember me</span>
                </label>

                <button type="submit" class="login-button">Log In</button>
            </form>

            <footer class="login-footer">PGECC Admin Portal &bull; &copy; 2026</footer>
        </div>
    </main>
</div>

<style>
    .login-page {
        min-height: 100vh;
        display: grid;
        grid-template-columns: 1fr 1fr;
        background: #fff;
    }

    .login-brand {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
    }

    .login-logo {
        width: min(70%, 420px);
        height: auto;
        border-radius: .35rem;
    }

    .login-panel {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        box-sizing: border-box;
        padding: 3rem clamp(1.5rem, 4vw, 4.5rem);
        background: #f8fafc;
    }

    .login-form-wrap {
        width: 100%;
        max-width: 760px;
        box-sizing: border-box;
        padding: 3.5rem clamp(2rem, 5vw, 5rem);
        border-radius: 1.25rem;
        /* box-shadow: 0 18px 45px rgba(31, 55, 88, .1); */
    }

    .login-header h1 { margin: 0 0 .35rem; color: #14243b; font-size: 1.65rem; line-height: 1.2; font-weight: 700; }
    .login-header p { margin: 0; color: #70819a; font-size: .78rem; line-height: 1.6; }
    .login-form { margin-top: 2rem; }
    .login-field + .login-field { margin-top: 1.15rem; }
    .login-field label { display: block; margin-bottom: .45rem; color: #334d70; font-size: .78rem; font-weight: 600; }
    .login-field label span { color: #e25b5b; }
    .login-field input:not([type="checkbox"]) { display: block; width: 100%; height: 3rem; box-sizing: border-box; border: 1px solid #d5deea; border-radius: .65rem; background: #f8fafc; padding: .75rem .85rem; color: #111827; font-size: .85rem; outline: none; transition: border-color .2s, box-shadow .2s, background .2s; }
    .login-field input:focus { border-color: #3978d9; background: #fff; box-shadow: 0 0 0 4px rgba(57,120,217,.12); }
    .remember-field { display: flex; align-items: center; gap: .5rem; margin-top: 1rem; color: #607795; font-size: .75rem; cursor: pointer; }
    .remember-field input { width: 1rem; height: 1rem; accent-color: #087cf0; }
    .login-button { width: 100%; margin-top: 1.35rem; border: 0; border-radius: .65rem; background: #087cf0; padding: .85rem 1rem; color: #fff; font-size: .85rem; font-weight: 600; line-height: 1; cursor: pointer; box-shadow: 0 8px 16px rgba(8,124,240,.2); transition: background .2s, transform .2s, box-shadow .2s; }
    .login-button:hover { background: #056bd0; }
    .login-button:hover { transform: translateY(-1px); box-shadow: 0 10px 20px rgba(8,124,240,.24); }
    .login-error { margin-top: 1rem; border: 1px solid #f2b9b9; border-radius: .65rem; background: #fff4f4; padding: .7rem .8rem; color: #b42318; font-size: .75rem; }
    .login-footer { margin-top: 2rem; border-top: 1px solid #e5eaf1; padding-top: 1rem; color: #91a1ba; font-size: .65rem; text-align: center; }

    @media (max-width: 700px) {
        .login-page { display: block; }
        .login-brand { min-height: 12rem; }
        .login-logo { width: min(52%, 220px); }
        .login-panel { min-height: calc(100vh - 12rem); padding: 2rem 1.25rem; }
        .login-form-wrap { padding: 2rem 1.5rem; }
    }
</style>
@endsection
