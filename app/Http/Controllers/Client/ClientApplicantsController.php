<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserApplication;
use Illuminate\Support\Facades\Auth;

class ClientApplicantsController extends Controller
{
    public function index()
    {
        $applications = UserApplication::with('user')->latest()->get();

        $usersWithoutApplications = User::where('role', 'user')
            ->whereDoesntHave('applications')
            ->get();

        return view('client.applicants.index', compact('applications', 'usersWithoutApplications'));
    }

}
