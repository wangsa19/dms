<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DMS - Sistem Manajemen Dokumen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        
        .glass-panel-dark {
            background: rgba(20, 20, 20, 0.4);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-panel-light {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        /* Auto-toggle icons based on HTML dark class */
        html.dark .theme-icon-dark { display: none !important; }
        html.dark .theme-icon-light { display: block !important; }
        html:not(.dark) .theme-icon-dark { display: block !important; }
        html:not(.dark) .theme-icon-light { display: none !important; }
    </style>
    <script>
        // Init theme
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Toggle function
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
</head>
<body class="bg-[#FDFDFC] dark:bg-[#050505] text-gray-900 dark:text-white antialiased overflow-x-hidden selection:bg-red-500 selection:text-white relative min-h-screen flex flex-col transition-colors duration-300">

    <!-- Background Decorative Elements -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <!-- Glowing Orbs (visible in both modes, slightly different opacity via tailwind if needed) -->
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-red-600/10 dark:bg-red-600/20 blur-[120px] mix-blend-multiply dark:mix-blend-screen"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-red-700/10 dark:bg-red-800/15 blur-[120px] mix-blend-multiply dark:mix-blend-screen"></div>
        
        <!-- Grid pattern Light -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDAsIDAsIDAsIDAuMDUpIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDQwaDQwVjBIMHoiLz48L2c+PC9zdmc+')] opacity-50 dark:hidden"></div>
        <!-- Grid pattern Dark -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDI1NSwgMjU1LCAyNTUsIDAuMDIpIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDQwaDQwVjBIMHoiLz48L2c+PC9zdmc+')] opacity-50 hidden dark:block"></div>
    </div>

    <!-- Navbar -->
    <nav class="relative z-10 w-full px-6 py-6 md:px-12 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center shadow-[0_0_20px_rgba(220,38,38,0.3)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <span class="text-2xl font-black tracking-tight text-gray-900 dark:text-white">DMS<span class="text-red-500">.</span></span>
        </div>
        <div class="flex items-center gap-4">
            <!-- Theme Toggle Button -->
            <button onclick="toggleTheme()" type="button" aria-label="Toggle color theme" title="Toggle color theme"
                class="inline-flex items-center justify-center h-10 w-10 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500/50 transition">
                <x-heroicon-o-moon class="theme-icon-dark w-5 h-5" />
                <x-heroicon-o-sun class="theme-icon-light w-5 h-5" />
            </button>
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-white transition-colors duration-200">Dashboard &rarr;</a>
            @else
                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-white transition-colors duration-200">Sign In &rarr;</a>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="relative z-10 flex-grow flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8 pt-6 pb-20 text-center">
        
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-panel-light dark:glass-panel-dark mb-8 shadow-[0_0_15px_rgba(220,38,38,0.1)] animate-float" style="animation-duration: 8s;">
            <span class="flex w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
            <span class="text-xs font-semibold text-red-600 dark:text-red-100 tracking-wide uppercase">Sistem Terintegrasi Penuh</span>
        </div>

        <!-- Headline -->
        <h1 class="max-w-5xl text-5xl md:text-7xl lg:text-8xl font-black tracking-tight mb-6 text-gray-900 dark:text-white">
            <span class="block dark:text-transparent dark:bg-clip-text dark:bg-gradient-to-b dark:from-white dark:to-gray-400">
                Manajemen Dokumen
            </span>
            <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-red-600 to-red-700 dark:from-red-400 dark:via-red-500 dark:to-red-600 drop-shadow-[0_0_30px_rgba(239,68,68,0.2)]">
                Lebih Cepat.
            </span>
        </h1>

        <!-- Description -->
        <p class="max-w-2xl mx-auto text-lg md:text-xl text-gray-600 dark:text-gray-400 font-light mb-10 leading-relaxed">
            Platform terpadu untuk menyimpan, melacak, dan mengelola seluruh dokumen instansi Anda dengan tingkat keamanan serta efisiensi tertinggi.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full max-w-sm mx-auto sm:max-w-none">
            <a href="{{ route('login') }}" 
               class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold rounded-2xl hover:from-red-500 hover:to-red-600 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_10px_30px_-10px_rgba(220,38,38,0.6)] flex items-center justify-center gap-2">
                <span>Sign In ke Portal</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>

        <!-- Features -->
        <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto text-left">
            <!-- Feature 1 -->
            <div class="p-6 rounded-2xl bg-white/60 dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-xl shadow-red-500/5 backdrop-blur-sm transition-transform hover:-translate-y-1">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 rounded-xl flex items-center justify-center mb-5">
                    <!-- Icon Version Control -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-2 text-lg">File Version Control</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Pantau setiap perubahan dokumen dengan riwayat versi yang tersimpan rapi dan aman.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="p-6 rounded-2xl bg-white/60 dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-xl shadow-red-500/5 backdrop-blur-sm transition-transform hover:-translate-y-1">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 rounded-xl flex items-center justify-center mb-5">
                    <!-- Icon Bell (Reminder) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-2 text-lg">In-App Reminder</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Dapatkan notifikasi dan pengingat jadwal penting secara real-time langsung di dalam dashboard.</p>
            </div>

            <!-- Feature 3 -->
            <div class="p-6 rounded-2xl bg-white/60 dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-xl shadow-red-500/5 backdrop-blur-sm transition-transform hover:-translate-y-1">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 rounded-xl flex items-center justify-center mb-5">
                    <!-- Icon Email -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-2 text-lg">Email Reminder</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Notifikasi otomatis via email untuk memastikan Anda tidak melewatkan tenggat waktu (deadline).</p>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full text-center py-6 border-t border-gray-200 dark:border-white/5 mt-auto">
        <p class="text-sm text-gray-500 dark:text-gray-600 font-medium">&copy; 2025 Document Management System. Develop by Politeknik Elektronika Negeri Surabaya</p>
    </footer>

</body>
</html>
