<?php

namespace App\Livewire\Auth;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email, $password;

    #[Layout('layouts.login')]
    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login');
    }


    public function mount()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
    }


    public function doLogin()
    {

        $this->validate(
            [
                'email'    => 'required|email',
                'password' => 'required'
            ],
            [
                'password.required' => 'The Password Field is required.',
                'email.required'    => 'The Email Field is required.',
                'email.email'       => 'Please enter valid Email address.'
            ]
        );

        try {
            if (!Auth::attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => 1])) {
                throw new Exception('Access denied. Please enter valid credential.');
            }
            return redirect()->intended('dashboard');
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
        }
    }
}
