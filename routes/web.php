<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileAdmin;


Route::get('/', function () {
    return view('welcome');
});

//admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {

    //admin dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    //admin profile edit
    Route::get('/profile/admin', [ProfileAdmin::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');


});

//client routes
Route::get('/client/dashboard', function () {
    return view('client.dashboard');
})->middleware(['auth', 'role:client']);

//user routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'role:user'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
