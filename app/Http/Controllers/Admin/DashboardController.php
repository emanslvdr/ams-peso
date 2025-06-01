<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;
use App\Models\Organization;

class DashboardController extends Controller
{
    public function index()
    {
        // Count
        $applicantsCount = User::where('role', 'user')->count();
        $clientsCount = User::where('role', 'client')->count();
        $jobsCount = Job::count();
        $organizationsCount = Organization::count();

        // Recent applicants
        $recentApplicants = User::where('role', 'user')->orderByDesc('created_at')->limit(5)->get();

        // Organizations with total jobs
        $organizations = Organization::withCount('jobs')->get();

        // Top 5 organizations with most jobs
        $topOrganizations = Organization::withCount('jobs')->orderByDesc('jobs_count')->limit(5)->get();

        return view('admin.dashboard.index', compact(
            'applicantsCount',
            'clientsCount',
            'jobsCount',
            'organizationsCount',
            'recentApplicants',
            'topOrganizations'
        ));
    }
}
