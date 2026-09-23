<header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-10 shadow-xs min-h-16 max-h-16">
    <form action="{{ route('dashboard') }}" method="GET" class="mx-auto flex w-full max-w-xl gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search PMC ID or employee name..." class="min-w-0 flex-1 rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-blue-500">
        <button class="rounded-lg bg-blue-600 px-5 text-sm font-medium text-white hover:bg-blue-700">Search</button>
    </form>
    <div class="text-sm font-medium text-gray-600">
        {{ now()->format('F d, Y') }}
    </div>
</header>
