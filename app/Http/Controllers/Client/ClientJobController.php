<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class ClientJobController extends Controller
{
    public function index()
    {

        // $jobs = Job::with('organization')->get();
        // return view('client.jobs.index', compact('jobs'));
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin sees all jobs
            $jobs = Job::with('organization')->get();
        } else {
            // Client sees only jobs from their organization
            $jobs = Job::with('organization')
                ->where('organization_id', $user->organization_id)
                ->get();
        }

        return view('client.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $organizations = Organization::all(); // Get all organizations
        return view('client.jobs.create', compact('organizations'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required',
    //         'description' => 'required',
    //         'organization_id' => 'required|exists:organizations,id'
    //     ]);

    //     Job::create($request->all());
    //     return redirect()->route('client.jobs.index')->with('success', 'Job posted successfully.');
    // }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $user = Auth::user(); // Get logged-in user

        Job::create([
            'title' => $request->title,
            'description' => $request->description,
            'organization_id' => $user->organization_id, // Automatically assign organization
        ]);

        return redirect()->route('client.jobs.index')->with('success', 'Job posted successfully.');
    }

    public function edit(Job $job)
    {
        $organizations = Organization::all();
        return view('client.jobs.edit', compact('job', 'organizations'));
    }

    // public function update(Request $request, Job $job)
    // {
    //     $request->validate([
    //         'title' => 'required',
    //         'description' => 'required',
    //         'organization_id' => 'required|exists:organizations,id'
    //     ]);

    //     $job->update($request->all());
    //     return redirect()->route('client.jobs.index')->with('success', 'Job updated successfully.');
    // }
    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        // Ensure the job belongs to the client's organization
        if (Auth::user()->role !== 'admin' && Auth::user()->organization_id !== $job->organization_id) {
            abort(403, 'Unauthorized action.');
        }

        $job->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('client.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->route('client.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
