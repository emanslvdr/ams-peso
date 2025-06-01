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
            'organization_id' => 'required|exists:organizations,id',
            'status' => 'required|in:open,closed,draft',
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
            'organization_id' => 'required|exists:organizations,id',
            'status' => 'required|in:open,closed,draft',
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
        $jobs = $organization->jobs()->withCount('applications')->latest()->get();

        return view('admin.organizations.view', [
            'organization' => $organization,
            'jobs' => $jobs,
        ]);
    }


    public function pipeline(Job $job)
    {
        $stages = ['New', 'Screening', 'Interview', 'Assessment', 'Offer', 'Hired'];
        $organization = $job->organization;
        $jobSkills = $job->skills;

        $jobApps = UserApplication::with('user')
            ->where('job_id', $job->id)
            ->get()
            ->map(function ($app) use ($jobSkills) {
                $app->match_score = $this->calculateMatchScore($jobSkills, $app->skills);
                return $app;
            })
            ->groupBy('stage');

        $generalPool = UserApplication::with('user')
            ->whereNull('job_id')
            ->get()
            ->map(function ($app) use ($jobSkills) {
                $app->match_score = $this->calculateMatchScore($jobSkills, $app->skills);
                return $app;
            });

        return view('admin.jobs.pipeline', [
            'job' => $job,
            'stages' => $stages,
            'organization' => $organization,
            'jobApps' => $jobApps,
            'generalPool' => $generalPool,
        ]);
    }



    public function calculateMatchScore($jobSkills, $candidateSkills)
    {
        $jobSkillsArr = array_filter(array_map('trim', explode(',', strtolower($jobSkills))));
        $candidateSkillsArr = array_filter(array_map('trim', explode(',', strtolower($candidateSkills))));
        if (empty($jobSkillsArr) || empty($candidateSkillsArr))
            return 0;

        // Fuzzy match: check if any skill partially matches
        $matches = 0;
        foreach ($jobSkillsArr as $jobSkill) {
            foreach ($candidateSkillsArr as $candSkill) {
                // If you want to allow partial matches (eg: 'php' vs 'php 8')
                if (str_contains($candSkill, $jobSkill) || str_contains($jobSkill, $candSkill)) {
                    $matches++;
                    break;
                }
            }
        }
        return round(($matches / count($jobSkillsArr)) * 100);
    }


    public function updateStatus(Request $request, Job $job)
    {
        $request->validate(['status' => 'required|in:open,closed,draft']);
        $job->status = $request->status;
        $job->save();
        return response()->json(['success' => true]);
    }

    public function markAsHired(Request $request, $appId)
    {
        $application = UserApplication::findOrFail($appId);
        $job = $application->job;

        // 1. Mark as hired
        $application->update([
            'status' => 'hired',
            'stage' => 'Hired',
        ]);

        // 2. Close the job
        $job->update([
            'status' => 'closed',
            'hired_at' => now(),
        ]);

        // 3. Move all others to pool
        UserApplication::where('job_id', $job->id)
            ->where('id', '!=', $appId)
            ->update([
                'job_id' => null,
                'stage' => null,
                'status' => 'new',
            ]);

        // 4. AJAX? Send simple success.
        return response()->json(['success' => true]);
    }




}
