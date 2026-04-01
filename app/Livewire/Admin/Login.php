<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $email = '';
    public string $password = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            return redirect()->intended('/admin');
        }

        $this->addError('email', 'E-posta veya şifre hatalı.');
    }

    public function render()
    {
        return view('livewire.admin.login')->layout('components.layouts.guest');
    }
}