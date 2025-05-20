<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileAdmin;
use App\Http\Controllers\ProfileClient;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Client\ClientJobController;
use App\Http\Controllers\User\UserApplicationController;
use App\Http\Controllers\Admin\ApplicantsController;


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

    Route::get('/admin/create/user', function () {
        return view('admin.create.user');
    })->name('admin.create.user');

    //admin profile edit
    Route::get('/profile/admin', [ProfileAdmin::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/profile/update', [ProfileAdmin::class, 'update'])->name('admin.profile.update');

    //create client CRUD
    Route::get('/admin/create/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/admin/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/admin/clients/store', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/admin/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/admin/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/admin/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // create user CRUD
    Route::get('/admin/create/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    //create organization CRUD
    Route::get('/admin/create/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
    Route::get('/admin/organizations/create', [OrganizationController::class, 'create'])->name('organizations.create');
    Route::post('/admin/organizations/create', [OrganizationController::class, 'store'])->name('organizations.store');
    Route::get('/admin/organizations/{organization}/edit', [OrganizationController::class, 'edit'])->name('organizations.edit');
    Route::put('/admin/organizations/{organization}', [OrganizationController::class, 'update'])->name('organizations.update');
    Route::delete('/admin/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');

    Route::get('/organizations/{organization}/jobs', [JobController::class, 'byOrganization'])->name('organizations.jobs');

    // create jobs CRUD
    Route::get('/admin/create/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/admin/jobs/create', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/admin/jobs/create', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/admin/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit');
    Route::put('/admin/jobs/{job}', [JobController::class, 'update'])->name('jobs.update');
    Route::delete('/admin/jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');

    Route::get('/jobs/{job}/pipeline', [JobController::class, 'pipeline'])->name('jobs.pipeline');
    Route::post('/applications/{application}/stage', function (\App\Models\UserApplication $application, \Illuminate\Http\Request $request) {
        $application->update([
            'stage' => $request->input('stage'),
        ]);
        return response()->json(['success' => true]);
    });



    // applicants CRUD
    Route::get('/admin/applicants', [ApplicantsController::class, 'index'])->name('applicants.index');


});


//client routes
Route::middleware(['auth', 'role:client'])->group(function () {

    //client navigation
    Route::get('/client/home', function () {
        return view('client.home');
    })->name('client.home');
    Route::get('/client/dashboard', function () {
        return view('client.dashboard');
    })->name('client.dashboard');
    Route::get('/client/applicants', function () {
        return view('client.applicants');
    })->name('client.applicants');

    //client profile edit
    Route::get('/profile/client', [ProfileClient::class, 'edit'])->name('client.profile.edit');
    Route::patch('/profile/client/update', [ProfileClient::class, 'update'])->name('client.profile.update');
    Route::delete('/profile/client', [ProfileClient::class, 'destroy'])->name('client.profile.destroy');

    // create jobs CRUD
    Route::get('/client/create/jobs', [ClientJobController::class, 'index'])->name('client.jobs.index');
    Route::get('/client/jobs/create', [ClientJobController::class, 'create'])->name('client.jobs.create');
    Route::post('/client/jobs/create', [ClientJobController::class, 'store'])->name('client.jobs.store');
    Route::get('/client/jobs/{job}/edit', [ClientJobController::class, 'edit'])->name('client.jobs.edit');
    Route::put('/client/jobs/{job}', [ClientJobController::class, 'update'])->name('client.jobs.update');
    Route::delete('/client/jobs/{job}', [ClientJobController::class, 'destroy'])->name('client.jobs.destroy');

});


//user routes
Route::middleware(['auth', 'role:user'])->group(function () {

    // user navigation
    Route::get('dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');
    // Route::get('application', function () {
    //     return view('user.application');
    // })->name('application');

    // user profile edit
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // crud for user application
    Route::get('/user/application', [UserApplicationController::class, 'index'])->name('user.application.index');
    Route::get('/user/application/create', [UserApplicationController::class, 'create'])->name('user.application.create');
    Route::post('/user/application/store', [UserApplicationController::class, 'store'])->name('user.application.store');
    Route::get('/user/application/{userApplication}/edit', [UserApplicationController::class, 'edit'])->name('user.application.edit');
    Route::put('/user/application/{userApplication}', [UserApplicationController::class, 'update'])->name('user.application.update');
    Route::delete('/user/application/{userApplication}', [UserApplicationController::class, 'destroy'])->name('user.application.destroy');


});

require __DIR__ . '/auth.php';
