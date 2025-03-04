<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileAdmin;


Route::get('/', function () {
    return view('welcome');
});

//admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {

    //admin navigation
    Route::get('/admin/home', function () {
        return view('admin.home');
    })->name('admin.home');
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::get('/admin/organization', function () {
        return view('admin.organization');
    })->name('admin.organization');
    Route::get('/admin/jobs', function () {
        return view('admin.jobs');
    })->name('admin.jobs');
    Route::get('/admin/applicants', function () {
        return view('admin.applicants');
    })->name('admin.applicants');

    //admin profile edit
    Route::get('/profile/admin', [ProfileAdmin::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/profile/update', [ProfileAdmin::class, 'update'])->name('admin.profile.update');


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
