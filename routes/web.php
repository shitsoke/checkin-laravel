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

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| We use Auth::routes() here but limit it to ONLY the password reset
| functionality to avoid conflicts with custom login/logout/register definitions.
*/
Auth::routes(['only' => ['reset', 'email']]);

// Home page redirects to login
Route::get('/', function () {
    // Check if the user is logged in
    if (Auth::check()) {
        // If logged in, redirect to dashboard
        return redirect()->route('dashboard');
    }
    // If not logged in, redirect to login page
    return redirect()->route('login');
});

// Custom Login Routes (as you defined)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// Custom Registration Route (assuming you need one, if not defined by Auth::routes)
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Logout route (as you defined)
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Application Routes (Protected by 'auth' middleware)
|--------------------------------------------------------------------------
| These routes correspond to the files you provided (dashboard, bookings, etc.)
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rooms (Browse Rooms)
    Route::get('/browse-rooms', [RoomController::class, 'index'])->name('rooms.browse');
    // For "View Details" button from browse_rooms.php
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show'); 

    // Bookings (My Bookings List & Details)
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    // For "View" button from bookings.php/booking_history.php
    Route::get('/my-bookings/{id}', [BookingController::class, 'show'])->name('bookings.show'); 
    // For actual booking submission (from book_room.php logic)
    Route::post('/book', [BookingController::class, 'store'])->name('booking.store');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    // For review submission form (from booking_history.php snippet)
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store'); 

    // Settings (replaces change_password.php and handles profile update)
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update.profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update.password');

    // About (replaces about.php)
    Route::get('/about', function () {
        // This assumes you create a view file at resources/views/about/index.blade.php
        return view('about.index'); 
    })->name('about.index');
});