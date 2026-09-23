<aside id="sidebar" class="w-64 bg-slate-900 text-white flex flex-col">
    <div class="p-5 text-2xl font-bold text-center tracking-wider bg-slate-950">
        PGECC
    </div>
    <nav class="mt-6 px-4 flex-1 space-y-2">
        
        <a href="{{ route('dashboard') }}" 
           class="block px-4 py-2.5 rounded transition-all 
           {{ request()->routeIs('dashboard') ? 'bg-amber-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800' }}">
            <i class="fa-solid fa-chart-column {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-300' }}"></i>
            Dashboard
        </a>

        <a href="{{ route('carenderia.index') }}" 
           class="block px-4 py-2.5 rounded transition-all 
           {{ request()->routeIs('carenderia.*') ? 'bg-amber-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800' }}">
            <i class="fa-solid fa-utensils {{ request()->routeIs('carenderia.*') ? 'text-amber-300' : 'text-slate-300' }}"></i>
            Carenderia
        </a>

        <a href="{{ route('loan.index') }}" 
           class="block px-4 py-2.5 rounded transition-all 
           {{ request()->routeIs('loan.*') ? 'bg-amber-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800' }}">
            <i class="fa-solid fa-sack-dollar {{ request()->routeIs('loan.*') ? 'text-green-300' : 'text-slate-300' }}"></i>
            Loans
        </a>

        <a href="{{ route('consumer-balances.index') }}"
           class="block px-4 py-2.5 rounded transition-all
           {{ request()->routeIs('consumer-balances.*') ? 'bg-amber-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800' }}">
            <i class="fa-solid fa-wallet {{ request()->routeIs('consumer-balances.*') ? 'text-cyan-300' : 'text-slate-300' }}"></i>
            Consumer Balances
        </a>

        {{-- <a href="{{ route('grocery.index') }}" 
           class="block px-4 py-2.5 rounded transition-all 
           {{ request()->routeIs('grocery.*') ? 'bg-amber-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800' }}">
            <i class="fa-solid fa-cart-shopping {{ request()->routeIs('grocery.*') ? 'text-blue-300' : 'text-slate-300' }}"></i>
            Grocery
        </a> --}}

        {{-- <a href="{{ route('payments.index') }}" 
           class="block px-4 py-2.5 rounded transition-all 
           {{ request()->routeIs('payments.*') ? 'bg-amber-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800' }}">
            <i class="fa-solid fa-hand-holding-dollar {{ request()->routeIs('payments.*') ? 'text-red-300' : 'text-slate-300' }}"></i>
            Payments
        </a> --}}

        <hr class="border-slate-300 my-4">
                
        @if(auth()->user()?->hasPermission('manage_users'))
        <a href="{{ route('users.index') }}" 
           class="block px-4 py-2.5 rounded transition-all 
           {{ request()->routeIs('users.*') ? 'bg-amber-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800' }}">
            <i class="fa-solid fa-user-group {{ request()->routeIs('users.*') ? 'text-violet-300' : 'text-slate-300' }}"></i>
            User Management
        </a>
        @endif

        @if(auth()->user()?->hasPermission('manage_member_classes'))
        <a href="{{ route('member-classes.index') }}"
           class="block px-4 py-2.5 rounded transition-all
           {{ request()->routeIs('member-classes.*') ? 'bg-amber-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800' }}">
            <i class="fa-solid fa-layer-group {{ request()->routeIs('member-classes.*') ? 'text-indigo-200' : 'text-slate-300' }}"></i>
            Member Classes
        </a>
        @endif
           
    </nav>

    <div class="mt-auto border-t border-slate-700 p-4">
        @auth
            <div class="mb-3 text-xs text-slate-400 truncate"><i class="fa-solid fa-circle-user mr-1 text-emerald-400"></i>{{ auth()->user()->name }}</div>
            <button type="button" onclick="const modal = document.getElementById('passwordModal'); modal.classList.remove('hidden'); modal.style.display='flex';" class="mb-1 w-full rounded-lg px-3 py-2 text-left text-sm text-slate-300 hover:bg-slate-800 hover:text-white"><i class="fa-solid fa-key mr-2"></i>Change password</button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full rounded-lg px-3 py-2 text-left text-sm text-slate-300 hover:bg-slate-800 hover:text-white"><i class="fa-solid fa-right-from-bracket mr-2"></i>Log out</button>
            </form>
        @else
            <button type="button" onclick="const modal = document.getElementById('loginModal'); modal.classList.remove('hidden'); modal.style.display='flex';" class="w-full rounded-lg px-3 py-2 text-left text-sm text-slate-300 hover:bg-slate-800 hover:text-white"><i class="fa-solid fa-right-to-bracket mr-2"></i>Admin login</button>
        @endauth
        <h6 class="text-center pt-4 text-xs italic text-slate-500">{{ now()->format('F d, Y') }}</h6>
    </div>
</aside>

@guest
<div id="loginModal" class="{{ request('login') ? '' : 'hidden' }}" style="{{ request('login') ? 'display:flex;' : 'display:none;' }} position:fixed; inset:0; z-index:50; align-items:center; justify-content:center; padding:1rem; background:rgba(15, 23, 42, 0.72);" onclick="if(event.target === this) { this.classList.add('hidden'); this.style.display='none'; }">
    <div style="width:min(92vw, 28rem); background:#ffffff; color:#0f172a; border-radius:1rem; padding:1.75rem; box-shadow:0 24px 70px rgba(15,23,42,.35);">
        <div class="mb-6 flex items-start justify-between"><div><div class="text-2xl font-black text-slate-900">PGECC</div><p class="mt-1 text-sm text-gray-500">Sign in to access admin tools.</p></div><button type="button" onclick="const modal = document.getElementById('loginModal'); modal.classList.add('hidden'); modal.style.display='none';" class="text-gray-400 hover:text-gray-700"><i class="fa-solid fa-xmark"></i></button></div>
        @if($errors->any())<div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">@csrf<input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email address" class="w-full p-3 border border-gray-300 rounded-lg"><input type="password" name="password" required placeholder="Password" class="w-full p-3 border border-gray-300 rounded-lg"><label class="flex items-center gap-2 text-sm text-gray-600"><input type="checkbox" name="remember" class="rounded border-gray-300"> Remember me</label><button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg">Sign in</button></form>
    </div>
</div>
@endguest

@auth
<div id="passwordModal" class="hidden" style="display:none; position:fixed; inset:0; z-index:50; align-items:center; justify-content:center; padding:1rem; background:rgba(15, 23, 42, 0.72);" onclick="if(event.target === this) { this.classList.add('hidden'); this.style.display='none'; }">
    <div style="width:min(92vw, 28rem); background:#ffffff; color:#0f172a; border-radius:1rem; padding:1.75rem; box-shadow:0 24px 70px rgba(15,23,42,.35);">
        <div class="mb-6 flex items-start justify-between"><div><div class="text-xl font-black text-slate-900">Change password</div><p class="mt-1 text-sm text-gray-500">Use a new password with at least 8 characters.</p></div><button type="button" onclick="const modal = document.getElementById('passwordModal'); modal.classList.add('hidden'); modal.style.display='none';" class="text-gray-400 hover:text-gray-700"><i class="fa-solid fa-xmark"></i></button></div>
        <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4">@csrf @method('PUT')<input type="password" name="current_password" required placeholder="Current password" class="w-full rounded-lg border border-gray-300 p-3"><input type="password" name="password" required placeholder="New password" class="w-full rounded-lg border border-gray-300 p-3"><input type="password" name="password_confirmation" required placeholder="Confirm new password" class="w-full rounded-lg border border-gray-300 p-3"><button class="w-full rounded-lg bg-indigo-600 py-3 font-semibold text-white hover:bg-indigo-700">Update password</button></form>
    </div>
</div>
@endauth
