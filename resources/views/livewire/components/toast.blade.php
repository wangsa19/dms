<div>
    @if($show)
    <div x-data="{ show: true }" x-init="setTimeout(() => { show = false; $wire.set('show', false) }, 4000)"
        x-show="show" x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-300 transform"
        x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
        class="fixed top-6 right-6 z-[100] w-full max-w-xs px-4 py-3 bg-white rounded-xl shadow-md border border-gray-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg @class([ 'w-6 h-6' , $this->getIconClasses() ]) fill="none" stroke="currentColor"
                    stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    {!! $this->getIconSvg() !!}
                </svg>
            </div>

            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-gray-800">{{ $message }}</p>
            </div>

            <div class="ml-4 flex-shrink-0">
                <button @click="show = false; $wire.set('show', false)"
                    class="text-gray-400 hover:text-gray-600 transition ease-in-out duration-150">
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <div @class([ 'absolute bottom-0 left-0 h-1 rounded-full animate-progress-toast' , $this->
            getProgressBarClasses()])>
        </div>
    </div>
    @endif
</div>