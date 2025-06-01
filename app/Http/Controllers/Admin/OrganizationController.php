<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;


class OrganizationController extends Controller
{
    public function index()
    {
        // $organizations = Organization::all();
        // return view('admin.organizations.index', compact('organizations'));
        $organizations = Organization::withCount('jobs')->paginate(99); // 10 per page (adjust as needed)
        return view('admin.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('admin.organizations.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:organizations,name',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048', // for upload
        ]);

        $data = $request->only(['name', 'address', 'phone', 'email', 'website', 'description']);
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('organization_logos', 'public');
        }
        Organization::create($data);

        return redirect()->route('organizations.index')->with('success', 'Organization created.');
    }


    public function edit(Organization $organization)
    {
        return view('admin.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $request->validate([
            'name' => 'required|unique:organizations,name,' . $organization->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);
        $data = $request->only(['name', 'address', 'phone', 'email', 'website', 'description']);
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('organization_logos', 'public');
        }
        $organization->update($data);

        return redirect()->route('organizations.index')->with('success', 'Organization updated.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect()->route('organizations.index')->with('success', 'Organization deleted.');
    }
}
