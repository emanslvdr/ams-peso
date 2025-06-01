<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use App\Models\UserApplication;

class ClientJobController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Only show jobs from their own organization
        $jobs = Job::with('organization')
            ->where('organization_id', $user->organization_id)
            ->get();

        return view('client.jobs.index', compact('jobs'));
    }

    public function create()
    {
        // No organizations needed; org is implied by logged-in client
        return view('client.jobs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,closed,draft',
            'skills' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        Job::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'skills' => $request->skills,
            'organization_id' => $user->organization_id,
        ]);

        return redirect()->route('client.jobs.index')->with('success', 'Job posted successfully.');
    }

    public function edit(Job $job)
    {
        $user = Auth::user();
        // Make sure the client only edits their own org's jobs
        if ($user->organization_id !== $job->organization_id) {
            abort(403, 'Unauthorized action.');
        }

        // No organization select
        return view('client.jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $user = Auth::user();
        if ($user->organization_id !== $job->organization_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,closed,draft',
            'skills' => 'nullable|string|max:255',
        ]);

        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'skills' => $request->skills,
        ]);

        return redirect()->route('client.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $user = Auth::user();
        if ($user->organization_id !== $job->organization_id) {
            abort(403, 'Unauthorized action.');
        }

        $job->delete();
        return redirect()->route('client.jobs.index')->with('success', 'Job deleted successfully.');
    }

    public function close(Job $job)
    {
        $user = Auth::user();
        if ($user->organization_id !== $job->organization_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $job->status = 'closed';
        $job->save();

        return response()->json(['success' => true]);
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

        return view('client.jobs.pipeline', [
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


    public function updateStatus(Request $request, Job $job)
    {
        $request->validate(['status' => 'required|in:open,closed,draft']);

        if (auth()->user()->organization_id !== $job->organization_id) {
            abort(403, 'Unauthorized');
        }

        $job->status = $request->status;
        $job->save();

        return response()->json(['success' => true]);
    }
}
