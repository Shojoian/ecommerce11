<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', config('app.name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900">

{{-- ========================================================== --}}
{{-- HEADER --}}
{{-- ========================================================== --}}
<header class="border-b bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">

        {{-- LOGO (small circle) --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img src="{{ asset('logo.png') }}"
                 alt="Logo"
                 class="h-10 w-10 rounded-full object-cover">

            <span class="font-semibold text-xl tracking-tight select-none">
                {{ config('app.name') }}
            </span>
        </a>

        {{-- SEARCH BAR --}}
        <form action="{{ route('products.index') }}" method="get"
              class="hidden md:flex items-center gap-2">
            <input type="text" name="q"
                   value="{{ request('q') }}"
                   placeholder="Search products"
                   class="border rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring focus:ring-emerald-500/30">

            <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                Search
            </button>
        </form>

        {{-- RIGHT SIDE --}}
        <div class="flex items-center gap-4">

            {{-- Cart link --}}
            <a href="{{ route('cart.index') }}"
               class="text-sm font-medium text-slate-700 hover:text-slate-900">
                Cart
            </a>

            @auth
                <div class="relative">

                    {{-- USER AVATAR BUTTON (small circle) --}}
                    <button id="userMenuBtn"
                            class="w-10 h-10 rounded-full overflow-hidden border border-slate-300 hover:ring hover:ring-emerald-300/40 transition">

                        <img src="{{ auth()->user()->avatar_url }}"
                             alt="avatar"
                             class="w-full h-full object-cover rounded-full"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=E2E8F0&color=475569'">
                    </button>

                    {{-- DROPDOWN MENU --}}
                    <div id="userMenu"
                         class="hidden absolute right-0 mt-2 w-44 bg-white border rounded-lg shadow-lg z-50">

                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-2 text-sm hover:bg-slate-100">
                            My Profile
                        </a>

                        <a href="{{ route('orders.index') }}"
                           class="block px-4 py-2 text-sm hover:bg-slate-100">
                            My Orders
                        </a>

                        @role('admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="block px-4 py-2 text-sm hover:bg-slate-100">
                                Admin Panel
                            </a>
                        @endrole

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm hover:bg-slate-100">
                                Logout
                            </button>
                        </form>

                    </div>
                </div>

                {{-- JS for dropdown --}}
                <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        const btn = document.getElementById("userMenuBtn");
                        const menu = document.getElementById("userMenu");

                        btn.addEventListener("click", (e) => {
                            e.stopPropagation();
                            menu.classList.toggle("hidden");
                        });

                        document.addEventListener("click", () => menu.classList.add("hidden"));
                    });
                </script>

            @else
                {{-- GUEST LINKS --}}
                <a href="{{ route('login') }}"
                   class="text-sm font-medium text-slate-700 hover:text-slate-900">
                    Log in
                </a>

                <a href="{{ route('register') }}"
                   class="px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-medium hover:bg-black">
                    Sign up
                </a>
            @endauth

        </div>
    </div>
</header>



{{-- ========================================================== --}}
{{-- MAIN CONTENT --}}
{{-- ========================================================== --}}
<main class="max-w-7xl mx-auto px-4 py-8">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')

</main>



{{-- ========================================================== --}}
{{-- FOOTER --}}
{{-- ========================================================== --}}
<footer class="border-t bg-white">
    <div class="max-w-7xl mx-auto px-4 py-6 text-xs text-slate-500 flex justify-between">
        <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
        <span>We are not Amazon btw.</span>
    </div>
</footer>

</body>
</html>
