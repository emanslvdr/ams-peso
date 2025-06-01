<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class UserDashboardController extends Controller
{
    // UserDashboardController.php
    public function index()
    {
        $jobs = Job::where('status', 'open')->latest()->get();
        $appliedJobIds = \App\Models\UserApplication::where('user_id', auth()->id())
            ->pluck('job_id')
            ->toArray();

        return view('user.dashboard', compact('jobs', 'appliedJobIds'));
    }
}
