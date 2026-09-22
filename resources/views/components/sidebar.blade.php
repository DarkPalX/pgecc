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
                
        <a href="{{ route('users.index') }}" 
           class="block px-4 py-2.5 rounded transition-all 
           {{ request()->routeIs('users.*') ? 'bg-amber-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800' }}">
            <i class="fa-solid fa-user-group {{ request()->routeIs('users.*') ? 'text-violet-300' : 'text-slate-300' }}"></i>
            User Management
        </a>

        <a href="{{ route('member-classes.index') }}"
           class="block px-4 py-2.5 rounded transition-all
           {{ request()->routeIs('member-classes.*') ? 'bg-amber-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800' }}">
            <i class="fa-solid fa-layer-group {{ request()->routeIs('member-classes.*') ? 'text-indigo-200' : 'text-slate-300' }}"></i>
            Member Classes
        </a>
           
    </nav>

    <h6 class="text-center p-4 text-sm italic text-gray-400">
        {{ now()->format('F d, Y') }}
    </h6>
</aside>
