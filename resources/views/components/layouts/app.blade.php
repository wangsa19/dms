<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'YAZAKI' }}</title>
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @livewireStyles
</head>

<body x-data="{ 
        isMobileOpen: false,
        isDesktopCollapsed: false,
        isDesktop: false, 
        
        init() {
            this.checkBreakpoint();
            window.addEventListener('resize', () => {
                this.checkBreakpoint();
            });
        },

        checkBreakpoint() {
            const checkElem = document.getElementById('breakpoint-check');
            if (checkElem) {
                const style = window.getComputedStyle(checkElem);
                const isLgActive = style.display !== 'none';
                
                if (isLgActive && !this.isDesktop) {
                    this.isMobileOpen = false; 
                    console.log('Switching to Desktop Mode');
                }

                this.isDesktop = isLgActive;
            }
        },

        toggleSidebar() {
            if (this.isDesktop) {
                this.isDesktopCollapsed = !this.isDesktopCollapsed;
            } else {
                this.isMobileOpen = !this.isMobileOpen;
            }
        }
    }" x-init="init()" :class="{ 
        'sidebar-mobile-open': isMobileOpen, 
        'sidebar-collapsed': isDesktopCollapsed 
    }"
    class="bg-gray-200 dark:bg-gray-950 font-sans text-gray-900 dark:text-gray-200 flex flex-col md:flex-row min-h-screen group/sidebar">

    <div id="breakpoint-check" class="hidden lg:block w-0 h-0 absolute -z-50"></div>

    {{-- Sidebar Component --}}
    <x-layouts.partials.sidebar />

    <div x-on:click="isMobileOpen = false" class="sidebar-overlay fixed inset-0 bg-black/50 z-40 transition-opacity opacity-0 invisible 
        group-[.sidebar-mobile-open]/sidebar:opacity-100 group-[.sidebar-mobile-open]/sidebar:visible
        lg:hidden">
    </div>

    {{-- Main Content --}}
    <main class="flex flex-col flex-1 overflow-y-auto p-6 transition-all duration-300 ease-in-out min-h-screen 
        ml-0 
        lg:ml-[260px] 
        group-[.sidebar-collapsed]/sidebar:lg:ml-0">

        {{-- Header --}}
        <header
            class="main-header bg-white dark:bg-gray-900 rounded-lg p-3 shadow-sm flex items-center justify-between mb-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-5">
                {{-- Menu Icon Trigger --}}
                <div @click="toggleSidebar()" class="menu-icon cursor-pointer text-gray-700 dark:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </div>
                {{-- Search Bar --}}
                <div class="search-bar relative max-md:hidden">
                    <svg class="absolute left-2 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" placeholder="Search..." class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-md py-1.5 pl-8 pr-4 w-64 
                        focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>

            <div class="flex items-center gap-5">
                {{-- Dark Mode Toggle --}}
                <button id="theme-toggle" type="button" aria-label="Toggle color theme" title="Toggle color theme"
                    class="inline-flex items-center justify-center h-10 w-10 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition">
                    <x-heroicon-o-moon id="theme-toggle-dark-icon" class="hidden w-5 h-5" />
                    <x-heroicon-o-sun id="theme-toggle-light-icon" class="hidden w-5 h-5" />
                </button>
                {{-- Notification --}}
                <livewire:admin.components.notification-bell />
                {{-- User Info --}}
                {{-- User Info & Dropdown --}}
                <div class="relative" x-data="{ userDropdownOpen: false }">
                    <button @click="userDropdownOpen = !userDropdownOpen" @click.outside="userDropdownOpen = false"
                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 p-1.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-700">

                        {{-- Avatar --}}
                        <div
                            class="user-avatar w-9 h-9 bg-gray-300 dark:bg-gray-700 rounded-full flex items-center justify-center font-bold text-gray-700 dark:text-gray-100">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>

                        {{-- Name --}}
                        <div class="hidden md:block text-left">
                            <div class="text-sm font-semibold text-gray-700 dark:text-gray-200 leading-tight">
                                {{ auth()->user()->name }}
                            </div>
                            <div class="text-[10px] text-gray-500 font-medium">
                                {{-- Optional: Show role or email --}}
                                {{ auth()->user()->email }}
                            </div>
                        </div>

                        {{-- Chevron Icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-gray-400 dark:text-gray-300 transition-transform duration-200"
                            :class="userDropdownOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="userDropdownOpen" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-300 py-1 z-50 origin-top-right"
                        style="display: none;">

                        {{-- Profile Link --}}
                        <a wire:navigate href="{{ route('profile') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Profile
                        </a>

                        {{-- Divider --}}
                        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                        {{-- Logout Button --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 transition-colors">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{ $slot }}

        <footer class="mt-auto pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-between text-sm text-gray-500 dark:text-gray-400 
            max-md:flex-col max-md:text-center max-md:gap-2">
            <p>&copy; 2025 Document Management System.</p>
            <p>Develop by Politeknik Elektronika Negeri Surabaya</p>
        </footer>
    </main>

    <livewire:components.toast />
    @livewireScripts
</body>

</html>
