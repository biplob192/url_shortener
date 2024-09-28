<?php

use App\Livewire\Auth\Login;
use App\Livewire\Admin\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
use App\Livewire\Admin\User\ManageUser;
use App\Http\Controllers\AuthController;



// Public Routes
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register_view');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('/', [AuthController::class, 'home']);
Route::get('/login', Login::class)->name('login');


// Protected Routes
Route::group(['middleware' => ['auth']], function () {
    // Dashboard route (manage by LiveWire)
    Route::get('/dashboard', Dashboard::class)->name('dashboard');


    // URL Related Route
    Route::resource('urls', UrlController::class);
    Route::get('/u/{url}', [UrlController::class, 'visitUrl'])->name('urls.visit');
    Route::get('/urls/ajax_show/{id}', [UrlController::class, 'ajaxShow'])->name('urls.ajaxShow');
    Route::delete('/urls/ajax_destroy/{id}', [UrlController::class, 'ajaxDestroy'])->name('urls.ajaxDestroy');


    // User Related Routes (manage by LiveWire)
    Route::get('/users', ManageUser::class)->name('user.manage');


    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
