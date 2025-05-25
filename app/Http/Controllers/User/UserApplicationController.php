<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserApplicationController extends Controller
{
    /**
     * Display a listing of the user's applications.
     */
    public function index()
    {
        $applications = UserApplication::where('user_id', Auth::id())->get();
        return view('user.application.index', compact('applications'));
    }

    /**
     * Show the form for creating a new application.
     */
    public function create()
    {
        return view('user.application.create');
    }

    /**
     * Store a newly created application.
     */
    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user_application,email',
            'phone_number' => 'required|string|max:15',
            'age' => 'required|integer|min:18|max:120',
            'education_level' => 'required|string|max:255',
            'other_education' => 'nullable|string|max:255',
            'graduation_year' => 'required|integer|min:1900|max:' . date('Y'),
            'institution' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'job_description' => 'nullable|string',
            'skills' => 'nullable|string',
            'resume' => 'required|file|mimes:pdf,docx|max:5120', // 5MB max
        ]);


        // Upload Resume
        $resumePath = $request->file('resume')->store('resumes', 'public');

        UserApplication::create([
            'user_id' => Auth::id(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'age' => $request->age,
            'education_level' => $request->education_level,
            'other_education' => $request->other_education,
            'graduation_year' => $request->graduation_year,
            'institution' => $request->institution,
            'company_name' => $request->company_name,
            'position' => $request->position,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'job_description' => $request->job_description,
            'skills' => $request->skills,
            'resume' => $resumePath,
            'status' => 'new',
        ]);



        return redirect()->route('user.application.index')->with('success', 'Application submitted successfully.');
    }


    /**
     * Show the form for editing an existing application.
     */
    public function edit($id)
    {
        $userApplication = UserApplication::where('id', $id)
            ->where('user_id', Auth::id()) // Ensure user can only edit their own application
            ->firstOrFail(); // Throws 404 if not found

        return view('user.application.edit', compact('userApplication'));
    }

    /**
     * Update the specified application.
     */
    public function update(Request $request, UserApplication $userApplication)
    {
        if ($userApplication->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:user_application,email,' . $userApplication->id,
            'phone_number' => 'required',
            'age' => 'required|integer|min:18',
            'education_level' => 'required',
            'graduation_year' => 'required|integer',
            'institution' => 'required',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'skills' => 'nullable|string',
        ]);

        $data = $request->except('resume');

        // If new resume uploaded, replace the old one
        if ($request->hasFile('resume')) {
            Storage::disk('public')->delete($userApplication->resume);
            $data['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        $userApplication->update($data);

        return redirect()->route('user.application.index')->with('success', 'Application updated successfully.');
    }

    /**
     * Remove the specified application from storage.
     */
    public function destroy(UserApplication $userApplication)
    {
        if ($userApplication->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        Storage::disk('public')->delete($userApplication->resume);
        $userApplication->delete();

        return redirect()->route('user.application.index')->with('success', 'Application deleted.');
    }
}
