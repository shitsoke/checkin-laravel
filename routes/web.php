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
use App\Http\Controllers\Admin\AdminRoomController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminRoomTypeController;
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Auth::routes();

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
| ADMIN ROUTES (Requires Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // Admin Pages (matching sidebar!)
    Route::get('/rooms', [AdminRoomController::class, 'index'])->name('rooms');
    Route::post('/rooms', [AdminRoomController::class, 'store'])->name('rooms.store');
    Route::post('/rooms/{room}/toggle', [AdminRoomController::class, 'toggleVisibility'])
        ->name('rooms.toggle');
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings');
    Route::post('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update');
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews');
    Route::post('/reviews/{review}/toggle', [AdminReviewController::class, 'toggleVisibility'])->name('reviews.toggle');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/roomtypes', [AdminRoomTypeController::class, 'index'])->name('roomtypes');
    Route::get('/roomtypes/{roomType}/edit', [AdminRoomTypeController::class, 'edit'])->name('roomtypes.edit');
    Route::put('/roomtypes/{roomType}', [AdminRoomTypeController::class, 'update'])->name('roomtypes.update');
    Route::get('/rooms/{room}', [AdminRoomController::class, 'show'])
        ->name('rooms.show');

    Route::post('/rooms/{room}/update', [AdminRoomController::class, 'update'])
        ->name('rooms.update');

    // Room Images
    Route::post('/room-images/{image}/primary', [AdminRoomController::class, 'makePrimary'])
        ->name('rooms.images.primary');

    Route::delete('/room-images/{image}', [AdminRoomController::class, 'deleteImage'])
        ->name('rooms.images.delete');
    Route::get('/rooms/{room}/images', [AdminRoomController::class, 'showImages'])
    ->name('rooms.images.upload');

    Route::post('/rooms/{room}/images', [AdminRoomController::class, 'storeImage'])
    ->name('rooms.images.store');

    // Users management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');
});

/*
|--------------------------------------------------------------------------
| USER ROUTES (Normal users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/browse-rooms', [RoomController::class, 'index'])->name('rooms.browse');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');

    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/my-bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/book', [BookingController::class, 'store'])->name('booking.store');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Receipt viewing and download
    Route::get('/receipt/{booking}', [App\Http\Controllers\ReceiptController::class, 'show'])->name('receipt.show');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::get('/settings/password', [SettingsController::class, 'password'])->name('settings.password');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update.profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update.password');

    Route::get('/about', function () {
        return view('about.index');
    })->name('about.index');
});
