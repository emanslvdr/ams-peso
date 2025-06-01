<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        $clients = User::where('role', 'client')->with('organization')->get();
        return view('admin.client.index', compact('clients'));
    }

    public function create()
    {
        $organizations = Organization::all();
        return view('admin.client.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'organization_id' => 'required|exists:organizations,id',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $filename = null;
        if ($request->hasFile('profile_photo')) {
            $filename = $request->file('profile_photo')->store('profile-photos', 'public');
        }


        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => \Hash::make($validatedData['password']),
            'role' => 'client',
            'organization_id' => $validatedData['organization_id'],
            'profile_photo' => $filename,
        ]);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }


    public function edit(User $client)
    {
        $organizations = Organization::all();
        return view('admin.client.edit', compact('client', 'organizations'));
    }

    public function update(Request $request, User $client)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($client->id),
            ],
            'organization_id' => 'required|exists:organizations,id',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            // Optionally: Delete old photo
            if ($client->profile_photo && \Storage::disk('public')->exists($client->profile_photo)) {
                \Storage::disk('public')->delete($client->profile_photo);
            }
            $filename = $request->file('profile_photo')->store('profile-photos', 'public');
            $client->profile_photo = $filename;
        }


        $client->name = $validatedData['name'];
        $client->email = $validatedData['email'];
        $client->organization_id = $validatedData['organization_id'];
        $client->save();

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }


    public function destroy(User $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted.');
    }
}
