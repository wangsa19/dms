<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

class SignIn extends Component
{
    #[Layout('components.layouts.sign-in')]

    public function render()
    {
        return view('livewire.auth.sign-in');
    }
}
