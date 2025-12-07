<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-800">
    <div class="flex">

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-slate-900 text-slate-100 min-h-screen flex flex-col">
            <div class="px-6 py-6 border-b border-slate-700">
                <h1 class="text-xl font-semibold tracking-tight">Admin Panel</h1>
                <p class="text-xs text-slate-400">Manage your store</p>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-4 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="block px-4 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.products.*') ? 'bg-slate-800' : '' }}">
                    Products
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="block px-4 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.orders.*') ? 'bg-slate-800' : '' }}">
                    Orders
                </a>
            </nav>

            <div class="px-4 py-6 border-t border-slate-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN --}}
        <main class="flex-1">
            <header class="bg-white border-b px-8 py-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold">@yield('title')</h2>

                <div class="text-sm text-slate-600">
                    Logged in as <strong>{{ auth()->user()->name }}</strong>
                </div>
            </header>

            <div class="p-8">
                @if(session('success'))
                    <div class="mb-4 p-3 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 border border-red-200">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

    </div>
</body>
</html>
