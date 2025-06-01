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
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Client\ClientApplicantsController;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\User\UserDashboardController;


Route::get('/', function () {
    return view('welcome');
});

//admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {

    //admin navigation
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

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

    Route::post('/admin/jobs/{job}/close', [App\Http\Controllers\Admin\JobController::class, 'close'])->name('admin.jobs.close');

    Route::post('/admin/jobs/{job}/status', [App\Http\Controllers\Admin\JobController::class, 'updateStatus']);


    Route::post('/applications/{application}/stage', function (\App\Models\UserApplication $application, \Illuminate\Http\Request $request) {
        $data = [];
        // Accept explicit nulls from JSON for job_id and stage
        if ($request->has('job_id')) {
            $data['job_id'] = $request->input('job_id');
        }
        if ($request->has('stage')) {
            $data['stage'] = $request->input('stage');
        }
        $application->update($data);
        return response()->json(['success' => true]);
    });

    Route::post('/applications/bulk-assign', function (\Illuminate\Http\Request $request) {
        \App\Models\UserApplication::whereIn('id', $request->input('ids'))
            ->update([
                'job_id' => $request->input('job_id'),
                'stage' => $request->input('stage'),
            ]);
        return response()->json(['success' => true]);
    });

    Route::get('/applications/{application}/details', function (\App\Models\UserApplication $application, \Illuminate\Http\Request $request) {
        $application->load('user');
        $jobId = $request->get('job_id');
        $jobSkills = [];
        $jobTitle = '';
        if ($jobId) {
            $job = \App\Models\Job::find($jobId);
            $jobTitle = $job?->title ?? '';
            $jobSkills = collect(explode(',', strtolower($job?->skills ?? '')))
                ->map(fn($s) => trim($s))->filter()->unique();
        }
        $appSkills = collect(explode(',', strtolower($application->skills ?? '')))
            ->map(fn($s) => trim($s))->filter()->unique();

        $matchedSkills = $jobSkills ? $jobSkills->intersect($appSkills)->count() : 0;
        $requiredSkills = max($jobSkills->count(), 1);
        $matchScore = $jobSkills->count() ? round(($matchedSkills / $requiredSkills) * 100) : 0;
        $isBest = $matchScore >= 70;

        $data = $application->toArray();
        $data['user_profile_photo'] = $application->user?->profile_photo ? asset('storage/' . $application->user->profile_photo) : null;
        $data['user_name'] = $application->user?->name ?? trim(($application->first_name ?? '') . ' ' . ($application->last_name ?? ''));
        $data['user_initials'] = collect(explode(' ', $data['user_name']))->map(fn($part) => strtoupper(substr($part, 0, 1)))->join('');
        $data['match_score'] = $matchScore;
        $data['isBest'] = $isBest;
        $data['job_title'] = $jobTitle;

        return response()->json($data);
    });


    Route::post('/applications/bulk-update-stage', function (\Illuminate\Http\Request $request) {
        $appIds = $request->app_ids;
        $stage = $request->stage;
        $jobId = $request->job_id;

        \App\Models\UserApplication::whereIn('id', $appIds)->update([
            'stage' => $stage,
            'job_id' => $jobId
        ]);
        return response()->json(['success' => true]);
    });

    Route::post('/applications/{application}/hire', [JobController::class, 'markAsHired']);

    // applicants CRUD
    Route::get('/admin/applicants', [ApplicantsController::class, 'index'])->name('applicants.index');


});


//client routes
Route::middleware(['auth', 'role:client'])->group(function () {

    //client navigation
    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])
        ->name('client.dashboard');
    Route::get('/client/applicants', [ClientApplicantsController::class, 'index'])
        ->name('client.applicants.index');
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

    // Pipeline view for a job (client side)
    Route::get('/client/jobs/{job}/pipeline', [ClientJobController::class, 'pipeline'])->name('client.jobs.pipeline');

    // Move a candidate between stages
    Route::post('/client/applications/{application}/stage', function (\App\Models\UserApplication $application, \Illuminate\Http\Request $request) {
        $data = ['stage' => $request->input('stage')];
        if ($request->has('job_id')) {
            $data['job_id'] = $request->input('job_id');
        }
        $application->update($data);
        return response()->json(['success' => true]);
    });

    // Bulk update stages
    Route::post('/client/applications/bulk-update-stage', function (\Illuminate\Http\Request $request) {
        $appIds = $request->input('app_ids');
        $stage = $request->input('stage');
        $jobId = $request->input('job_id');
        \App\Models\UserApplication::whereIn('id', $appIds)->update([
            'stage' => $stage,
            'job_id' => $jobId
        ]);
        return response()->json(['success' => true]);
    });

    // Application details for modal
    Route::get('/client/applications/{application}/details', function (\App\Models\UserApplication $application, \Illuminate\Http\Request $request) {
        $application->load('user');
        // ... your details logic here, copy/adapt from admin ...
        $data = $application->toArray();
        // ... etc
        return response()->json($data);
    });

    // Mark as Hired and close job, if needed
    Route::post('/client/applications/{application}/hire', [ClientJobController::class, 'markAsHired']);


    Route::post('/client/jobs/{job}/status', [ClientJobController::class, 'updateStatus']);

    Route::post('/client/jobs/{job}/close', [ClientJobController::class, 'close'])->name('client.jobs.close');



});


//user routes
Route::middleware(['auth', 'role:user'])->group(function () {

    // user navigation
    Route::get('/dashboard', [UserDashboardController::class, 'index'])
        ->middleware(['auth', 'role:user', 'verified'])
        ->name('dashboard');


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


    Route::get('/user/application/job/{job}', [UserApplicationController::class, 'createForJob'])
        ->name('user.application.create_for_job');



});

Route::get('/force-logout', function () {
    Auth::logout();
    return redirect('/login');
});

require __DIR__ . '/auth.php';
