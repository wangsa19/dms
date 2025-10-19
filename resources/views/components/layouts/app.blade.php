<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'YAZAKI' }}</title>

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    {{-- Assets via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @livewireStyles
</head>

<body class="bg-gray-100 font-sans text-gray-900 flex flex-col md:flex-row min-h-screen group/sidebar">

    {{-- Sidebar --}}
    <x-layouts.partials.sidebar />

    {{-- Overlay untuk sidebar mobile --}}
    <div class="sidebar-overlay fixed inset-0 bg-black/50 z-40 transition-opacity opacity-0 invisible 
        group-[.sidebar-mobile-open]/sidebar:opacity-100 group-[.sidebar-mobile-open]/sidebar:visible">
    </div>

    {{-- Main content --}}
    <main class="flex flex-col flex-1 overflow-y-auto ml-[260px] p-6 transition-all duration-300 ease-in-out
        lg:group-[.sidebar-collapsed]/sidebar:ml-[90px] max-lg:ml-0 min-h-screen">

        {{-- Header --}}
        <header
            class="main-header bg-white rounded-lg p-3 shadow-sm flex items-center justify-between mb-6 border border-gray-200">
            <div class="flex items-center gap-5">
                <div class="menu-icon cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </div>

                <div class="search-bar relative max-md:hidden">
                    <svg class="absolute left-2 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" placeholder="Search..." class="border border-gray-200 rounded-md py-1.5 pl-8 pr-4 w-64 
                        focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>

            <div class="flex items-center gap-5">
                {{-- Dark mode toggle --}}
                <button id="theme-toggle" type="button" class="text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 
            focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 
            rounded-lg text-sm p-2.5 transition">
                    {{-- Moon (show in light mode) --}}
                    <x-heroicon-o-moon id="theme-toggle-dark-icon" class="hidden w-5 h-5" />
                    {{-- Sun (show in dark mode) --}}
                    <x-heroicon-o-sun id="theme-toggle-light-icon" class="hidden w-5 h-5" />
                </button>

                {{-- Notification --}}
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    <div class="notification-badge absolute -top-1 -right-2 bg-[#d90000]
                        text-white text-[10px] w-5 h-5 rounded-full flex items-center
                        justify-center font-bold">
                        3
                    </div>
                </div>

                {{-- User info --}}
                <div class="flex items-center gap-2">
                    <div class="user-avatar w-9 h-9 bg-gray-300 rounded-full flex items-center
                        justify-center font-bold">SA</div>
                    <div class="user-name font-semibold max-sm:hidden">Super Admin</div>
                </div>
            </div>
        </header>

        {{-- Main content slot --}}
        {{ $slot }}

        {{-- Footer --}}
        <footer class="mt-auto pt-6 border-t border-gray-200 flex justify-between text-sm text-gray-500 
            max-md:flex-col max-md:text-center max-md:gap-2">
            <p>2025 © Jatim Autocomp Indonesia.</p>
            <p>Develop by Politeknik Elektronika Negeri Surabaya</p>
        </footer>
    </main>

    @livewireScripts
</body>

</html>