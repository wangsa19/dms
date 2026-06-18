<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Login | PT. Jatim Autocomp Indonesia' }}</title>

    {{-- Script anti-FLASH dark mode --}}
    <style>
        /* Auto-toggle icons based on HTML dark class */
        html.dark .theme-icon-dark { display: none !important; }
        html.dark .theme-icon-light { display: block !important; }
        html:not(.dark) .theme-icon-dark { display: block !important; }
        html:not(.dark) .theme-icon-light { display: none !important; }
    </style>
    <script>
        if (localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }
    </script>

    {{-- Asset & dependency --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="bg-white dark:bg-gray-900 min-h-screen flex items-center justify-center relative transition-colors duration-300">

    <!-- Theme Toggle Button -->
    <button onclick="toggleTheme()" type="button" aria-label="Toggle color theme" title="Toggle color theme"
        class="absolute top-6 right-6 z-50 inline-flex items-center justify-center h-10 w-10 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition">
        <x-heroicon-o-moon class="theme-icon-dark w-5 h-5" />
        <x-heroicon-o-sun class="theme-icon-light w-5 h-5" />
    </button>

    {{-- Slot untuk konten halaman --}}
    {{ $slot }}

    @livewireScripts
</body>
</html>