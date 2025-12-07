<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', config('app.name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark Mode Script -->
    <script>
        if (localStorage.getItem("theme") === "dark") {
            document.documentElement.classList.add("dark");
        }

        function toggleDarkMode() {
            const html = document.documentElement;
            const dark = html.classList.toggle("dark");
            localStorage.setItem("theme", dark ? "dark" : "light");

            document.getElementById("darkIcon").classList.toggle("hidden", !dark);
            document.getElementById("lightIcon").classList.toggle("hidden", dark);
        }
    </script>
</head>

<body class="bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-200">

<header class="bg-white dark:bg-slate-800 shadow-sm border-b border-slate-200 dark:border-slate-700">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">

        <!-- LOGO -->
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-full overflow-hidden">
                <img src="{{ asset('logo.png') }}" class="w-full h-full object-cover rounded-full">
            </div>
            <span class="font-semibold text-xl dark:text-white">{{ config('app.name') }}</span>
        </a>

        <!-- SEARCH BAR -->
        <form action="{{ route('products.index') }}" method="get" class="hidden md:flex items-center gap-2">
            <input type="text" name="q"
                placeholder="Search products"
                value="{{ request('q') }}"
                class="px-3 py-2 rounded-lg text-sm w-64 
                       bg-white dark:bg-slate-700 
                       border border-slate-300 dark:border-slate-600 
                       text-slate-900 dark:text-slate-100
                       placeholder-slate-400 dark:placeholder-slate-500">
            <button class="btn-primary px-4 py-2 rounded-lg text-sm">Search</button>
        </form>

        <!-- RIGHT SIDE -->
        <div class="flex items-center gap-4">

            <!-- CART ICON -->
            <a href="{{ route('cart.index') }}"
                class="hover:scale-110 transition text-slate-700 dark:text-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m11-9l2 9m-6-9v9" />
                </svg>
            </a>

            <!-- DARK MODE BUTTON -->
            <button onclick="toggleDarkMode()"
                class="p-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700">

                <!-- Moon (dark mode ON) -->
                <svg id="darkIcon" class="h-5 w-5 hidden dark:block"
                     fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21.64 13A9 9 0 1111 2.36 7 7 0 0021.64 13z" />
                </svg>

                <!-- Sun (light mode) -->
                <svg id="lightIcon" class="h-5 w-5 dark:hidden"
                     fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 18a6 6 0 100-12 6 6 0 000 12zM12 2v2m0 18v-2m10-8h-2M4 12H2m15.364 6.364l-1.414-1.414M6.05 
                             6.05L4.636 4.636m12.728 0l-1.414 1.414M6.05 17.95l-1.414 1.414" />
                </svg>
            </button>

            @auth
            <!-- USER AVATAR -->
            <div class="relative">
                <button id="userMenuBtn"
                    class="w-8 h-8 rounded-full overflow-hidden border border-slate-300 dark:border-slate-600">
                    <img src="{{ auth()->user()->avatar_url }}"
                         class="w-full h-full object-cover rounded-full">
                </button>

                <!-- DROPDOWN -->
                <!-- DROPDOWN -->
            <div id="userMenu"
                class="hidden absolute right-0 mt-2 w-44 bg-white dark:bg-slate-700 
                    border border-slate-200 dark:border-slate-600
                    rounded-lg shadow-lg">

                <a href="{{ route('profile.edit') }}"
                class="block px-4 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-600">
                My Profile
                </a>

                <a href="{{ route('orders.index') }}"
                class="block px-4 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-600">
                My Orders
                </a>

                @role('admin')
                    <a href="{{ route('admin.dashboard') }}"
                    class="block px-4 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-600 
                            text-emerald-600 dark:text-emerald-300 font-semibold">
                        🛠 Admin Panel
                    </a>
                @endrole

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-4 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-600">
                        Logout
                    </button>
                </form>
            </div>


            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const btn = document.getElementById("userMenuBtn");
                    const menu = document.getElementById("userMenu");

                    btn.addEventListener("click", e => {
                        e.stopPropagation();
                        menu.classList.toggle("hidden");
                    });

                    document.addEventListener("click", () => menu.classList.add("hidden"));
                });
            </script>

            @else
                <a href="{{ route('login') }}" class="text-sm hover:text-white">Log in</a>
                <a href="{{ route('register') }}" class="btn-primary px-4 py-2 rounded-lg text-sm">Sign up</a>
            @endauth

        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 py-8">
    @yield('content')
</main>

<footer class="footer px-4 py-6 text-xs">
    <div class="max-w-7xl mx-auto flex justify-between">
        <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
        <span>We are not Amazon btw.</span>
    </div>
</footer>

</body>
</html>
