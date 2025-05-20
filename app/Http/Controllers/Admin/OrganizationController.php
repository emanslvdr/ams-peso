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
        $organizations = Organization::withCount('jobs')->get();
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
        ]);

        Organization::create(['name' => $request->name]);

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
        ]);

        $organization->update(['name' => $request->name]);

        return redirect()->route('organizations.index')->with('success', 'Organization updated.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect()->route('organizations.index')->with('success', 'Organization deleted.');
    }
}
