<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class Toast extends Component
{
    public bool $show = false;
    public string $message = '';
    public string $type = 'success'; // 'success', 'error', 'warning', 'info'

    /**
     * Listen for the 'show-toast' event.
     * * This can be dispatched from any other Livewire component using:
     * $this->dispatch('show-toast', message: 'Your message here', type: 'success');
     */
    #[On('show-toast')]
    public function showToast(string $message, string $type = 'success'): void
    {
        $this->message = $message;
        $this->type = $type;
        $this->show = true;
    }

    /**
     * Get the CSS classes for the ICON.
     * (Hanya warna teks)
     */
    public function getIconClasses(): string
    {
        return [
            'success' => 'text-green-500',
            'error'   => 'text-red-500',
            'warning' => 'text-yellow-500',
            'info'    => 'text-blue-500',
        ][$this->type] ?? 'text-green-500';
    }
    
    /**
     * Get the CSS classes for the PROGRESS BAR.
     * (Hanya warna background)
     */
    public function getProgressBarClasses(): string
    {
        return [
            'success' => 'bg-green-500',
            'error'   => 'bg-red-500',
            'warning' => 'bg-yellow-500',
            'info'    => 'bg-blue-500',
        ][$this->type] ?? 'bg-green-500';
    }

    /**
     * Get the SVG path for the icon.
     * (Pastikan Anda menggunakan versi v2 yang saya berikan sebelumnya)
     */
    public function getIconSvg(): string
    {
        $paths = [
            'success' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
            'error'   => '<path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
            'warning' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z" />',
            'info'    => '<path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />',
        ];

        return $paths[$this->type] ?? $paths['success'];
    }

    public function render()
    {
        return view('livewire.components.toast');
    }
}
