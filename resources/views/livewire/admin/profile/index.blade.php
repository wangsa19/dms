<div>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">MY PROFILE</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your account information and security settings.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Info Card --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 text-center">
                <div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center font-bold text-3xl text-white mx-auto mb-4">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $user->name }}</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $user->email }}</p>

                <div class="mt-4 flex flex-wrap gap-2 justify-center">
                    @foreach($user->roles as $role)
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full text-xs font-semibold">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </div>
            </div>

            @if($user->employee)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 border-b border-gray-100 dark:border-gray-700 pb-3 mb-4">Employee Details</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider mb-1">NIK</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $user->employee->nik ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider mb-1">Department</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $user->employee->department->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider mb-1">Section</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $user->employee->section->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider mb-1">Position</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $user->employee->position->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Change Password Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 border-b border-gray-100 dark:border-gray-700 pb-3 mb-6">Security Settings</h3>
                
                <form wire:submit="updatePassword" class="space-y-5 max-w-md">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Password</label>
                        <input type="password" wire:model="current_password" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                        <input type="password" wire:model="password" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm New Password</label>
                        <input type="password" wire:model="password_confirmation" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center justify-center">
                            <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                            <span wire:loading wire:target="updatePassword">Updating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
