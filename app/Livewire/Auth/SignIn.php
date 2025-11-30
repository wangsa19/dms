<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class SignIn extends Component
{
    #[Layout('components.layouts.sign-in')]

    #[Title('Sign In')]

    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'email'    => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {

            session()->regenerate();

            $user = Auth::user();

            return redirect()->intended(route($user->getRedirectRoute()));
        }

        $this->addError('email', 'These credentials do not match our records.');
        $this->reset('password');
    }

    public function render()
    {
        return view('livewire.auth.sign-in');
    }
}
