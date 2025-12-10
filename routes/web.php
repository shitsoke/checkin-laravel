<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController; 
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Auth::routes(['only' => ['reset', 'email']]);

// Redirect root to login or dashboard
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Custom Login & Registration
|--------------------------------------------------------------------------
*/
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Requires Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // 👉 ADMIN DASHBOARD ROUTE
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

});

/*
|--------------------------------------------------------------------------
| USER APPLICATION ROUTES (Requires Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rooms
    Route::get('/browse-rooms', [RoomController::class, 'index'])->name('rooms.browse');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');

    // Bookings
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/my-bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/book', [BookingController::class, 'store'])->name('booking.store');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update.profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update.password');

    // About
    Route::get('/about', function () {
        return view('about.index');
    })->name('about.index');
});
