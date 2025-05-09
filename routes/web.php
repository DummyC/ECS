<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified', 'admin'])->name('dashboard');

Route::get('/calendar', function () {
    return view('user/dashboard');
})->middleware(['auth', 'verified'])->name('user.dashboard');


Route::middleware(['auth', 'admin', 'verified'])->prefix('admin')->group(function () {
    Route::get('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
    Route::get('/settings', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('settings');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('admin.users.update');
    Route::put('/users/{user}/deactivate', [\App\Http\Controllers\Admin\UserManagementController::class, 'deactivate'])->name('admin.users.deactivate');
    Route::patch('/users/{user}/toggle-admin', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleAdmin'])->name('admin.users.toggleAdmin');
});

Route::get('/events', [EventController::class, 'index']);
Route::post('/events', [EventController::class, 'store']);
Route::put('/events/{id}', [EventController::class, 'update']);
Route::delete('/events/{id}', [EventController::class, 'destroy']);


// Route::get('/settings', function () {
//     return view('settings');
// })->middleware(['auth', 'verified', 'admin'])->name('settings');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::post('save-subscription', [PushNotificationController::class, 'saveSubscription'])->name('save.subscription');
Route::post('send-notification', [PushNotificationController::class, 'sendNotification'])->name('send.notification');

require __DIR__.'/auth.php';
