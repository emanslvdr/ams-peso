<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\UserApplication;
use App\Models\Organization;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with('organization')->get();
        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $organizations = Organization::all(); // Get all organizations
        return view('admin.jobs.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'organization_id' => 'required|exists:organizations,id'
        ]);

        Job::create($request->all());
        return redirect()->route('jobs.index')->with('success', 'Job posted successfully.');
    }

    public function edit(Job $job)
    {
        $organizations = Organization::all();
        return view('admin.jobs.edit', compact('job', 'organizations'));
    }

    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'organization_id' => 'required|exists:organizations,id'
        ]);

        $job->update($request->all());
        return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }

    //additionals for organization page
    public function byOrganization(Organization $organization)
    {
        // Load jobs for this organization
        $jobs = $organization->jobs()->latest()->get();

        return view('admin.organizations.view', [
            'organization' => $organization,
            'jobs' => $jobs,
        ]);
    }

    public function pipeline(Job $job)
    {
        $stages = ['New', 'Screening', 'Interview', 'Assessment', 'Offer', 'Hired'];

        // Fetch ALL applications (not filtered by job)
        $applications = UserApplication::with('user')->get();

        // Group by stage
        $grouped = $applications->groupBy('stage');

        return view('admin.jobs.pipeline', [
            'job' => $job,
            'stages' => $stages,
            'applications' => $grouped,
            'unassigned' => $applications->whereNull('stage'),
        ]);
    }





}
