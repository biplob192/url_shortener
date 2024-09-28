<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function showRegistrationForm()
    {
        try {
            if (Auth::check()) {
                return redirect()->route('dashboard');
            }

            return view('auth.register');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function register(RegisterRequest $request)
    {
        try {
            // Validate the request and get the validated data
            $validatedData = $request->validated();

            // Register the user and assign default role
            $user = $this->authService->register($validatedData);

            // Log the user in
            Auth::login($user);

            // Redirect to the dashboard
            return redirect()->route('dashboard')->with('success', 'Registration successful! Welcome!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request);
            return redirect()->route('login');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function home()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login');
        }
    }
}
