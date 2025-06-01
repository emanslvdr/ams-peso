<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserApplication;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ApplicantsController extends Controller
{
    public function index()
    {
        // Get users who have submitted applications
        $applications = UserApplication::with('user')->get();

        // Get users who haven't submitted applications
        $usersWithoutApplications = User::where('role', 'user')
            ->whereDoesntHave('applications') // Users with no applications
            ->get();

        return view('admin.applicants.index', compact('applications', 'usersWithoutApplications'));
    }
}
