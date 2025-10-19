<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Login | PT. Jatim Autocomp Indonesia' }}</title>

    {{-- Script anti-FLASH dark mode --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    {{-- Asset & dependency --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="bg-white dark:bg-gray-900 min-h-screen flex items-center justify-center">

    {{-- Slot untuk konten halaman --}}
    {{ $slot }}

    @livewireScripts
</body>

</html>