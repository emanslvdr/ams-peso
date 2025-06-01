<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\UserApplication;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $organization_id = $user->organization_id;

        $jobsCount = Job::where('organization_id', $organization_id)->count();

        $applicantsCount = UserApplication::whereHas('job', function ($q) use ($organization_id) {
            $q->where('organization_id', $organization_id);
        })->count();

        $recentApplicants = UserApplication::with('user')
            ->whereHas('job', function ($q) use ($organization_id) {
                $q->where('organization_id', $organization_id);
            })
            ->latest()
            ->limit(5)
            ->get();

        // HERE: Use withCount('applications') so each job has applications_count
        $recentJobs = Job::withCount('applications')
            ->where('organization_id', $organization_id)
            ->latest()
            ->limit(5)
            ->get();

        // Pass the organization model for logo/name
        $organization = $user->organization;

        return view('client.dashboard', compact(
            'jobsCount',
            'applicantsCount',
            'recentApplicants',
            'recentJobs',
            'organization'
        ));
    }
}
