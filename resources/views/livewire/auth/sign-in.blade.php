<div
    class="flex min-h-screen w-full bg-gradient-to-br from-red-50 via-white to-red-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">

    {{-- =============================== --}}
    {{-- 1. Bagian Ilustrasi (Kiri) --}}
    {{-- =============================== --}}
    <div class="hidden lg:flex w-1/2 items-center justify-center p-12 relative overflow-hidden">
        {{-- Animated Background Circles --}}
        <div
            class="absolute top-32 left-32 w-96 h-96 bg-red-100 dark:bg-red-900/20 rounded-full mix-blend-multiply dark:mix-blend-normal filter blur-2xl opacity-70 animate-blob">
        </div>

        <div class="text-center relative z-10">
            <div class="relative inline-block">
                <x-heroicon-s-document-text class="h-16 w-16 text-red-600" />
            </div>
            <h2
                class="py-4 text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                Document Management System
            </h2>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                Manage your digital assets efficiently and securely with our advanced platform.
            </p>
        </div>
    </div>

    {{-- =============================== --}}
    {{-- 2. Bagian Form Login (Kanan) --}}
    {{-- =============================== --}}
    <div class="flex w-full lg:w-1/2 items-center justify-center p-6 sm:p-8 lg:p-12">
        <div class="w-full max-w-md">

            {{-- Card Container with Glass Effect --}}
            <div
                class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50 p-8 sm:p-10">

                {{-- Logo --}}
                <div class="text-center mb-8">
                    <div
                        class="inline-block p-3 bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 rounded-2xl mb-4">
                        <img class="w-24 sm:w-28 mx-auto" src="{{ asset('assets/logo/yazaki.svg') }}" alt="Logo">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">Welcome Back</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        PT. JATIM AUTOCOMP INDONESIA
                    </p>
                </div>

                {{-- Form Login --}}
                <form wire:submit.prevent="login" class="space-y-5">
                    {{-- Email --}}
                    <div>
                        <label for="email"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email
                            Address</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-red-600 transition-colors">
                                <x-heroicon-s-envelope class="w-6 h-6" />
                            </span>
                            <input wire:model.defer="email" id="email" type="email" required
                                class="block w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-200 dark:border-gray-700 rounded-xl shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-600"
                                placeholder="you@example.com">
                        </div>
                        @error('email') <p class="text-sm text-red-600 mt-2 flex items-center"><i
                                class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div x-data="{ showPassword: false }">
                        <label for="password"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Password</label>
                        <div class="relative group">
                            {{-- Icon Gembok (Kiri) --}}
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-red-600 transition-colors">
                                <x-heroicon-s-lock-closed class="w-6 h-6" />
                            </span>

                            {{-- Input Password --}}
                            <input wire:model.defer="password" id="password" :type="showPassword ? 'text' : 'password'"
                                required
                                class="block w-full pl-12 pr-12 py-3 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-200 dark:border-gray-700 rounded-xl shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-600"
                                placeholder="••••••••">

                            {{-- Tombol Show/Hide (Kanan) --}}
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-red-600 cursor-pointer focus:outline-none transition-colors">

                                {{-- Icon Mata Terbuka (Show) --}}
                                <x-heroicon-s-eye x-show="!showPassword" class="w-5 h-5" />

                                {{-- Icon Mata Tertutup (Hide) --}}
                                <x-heroicon-s-eye-slash x-show="showPassword" style="display: none;" class="w-5 h-5" />
                            </button>
                        </div>
                        @error('password') <p class="text-sm text-red-600 mt-2 flex items-center"><i
                                class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    {{-- Remember me & Forgot password --}}
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center text-gray-700 dark:text-gray-300 cursor-pointer group">
                            <input type="checkbox" wire:model="remember"
                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded cursor-pointer dark:bg-gray-700 dark:border-gray-600 transition">
                            <span class="ml-2 group-hover:text-gray-900 dark:group-hover:text-white transition">Remember
                                me</span>
                        </label>
                        <a href="#"
                            class="font-semibold text-red-600 hover:text-red-700 dark:text-red-500 dark:hover:text-red-400 transition">
                            Forgot password?
                        </a>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full relative py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:ring-4 focus:ring-red-500/50 shadow-lg shadow-red-500/50 transition-all duration-200 transform cursor-pointer hover:scale-[1.02] active:scale-[0.98]">
                        <span class="flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Sign in to your account
                        </span>
                    </button>
                </form>
            </div>

            {{-- Footer --}}
            <p class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                © 2025 PT. Jatim Autocomp Indonesia. All rights reserved.
            </p>
        </div>
    </div>
</div>