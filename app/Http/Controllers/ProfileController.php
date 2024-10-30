<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{

    public function index()
    {
        $profiles = Profile::where('status', 'actif')->get(['id', 'name', 'firstName', 'imagePath', 'created_at', 'updated_at']);

        return response()->json($profiles);
    }

    public function store(Request $request)
    {
        return "coucou";
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'imagePath' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'in:inactive,pending,active',
        ]);

        //$path = $request->file('imagePath')->store('profile-imagePath', 'public');

        $profile = Profile::create([
            'name' => $validated['name'],
            'firstName' => $validated['firstName'],
            'imagePath' => $validated['imagePath'],
            'status' => $validated['status'] ?? 'inactive',
        ]);

        return response()->json($profile, 201);
    }

    public function update(Request $request, $id)
    {
        $profile = Profile::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'firstName' => 'string|max:255',
            'imagePath' => 'image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'in:inactive,pending,active',
        ]);

        if ($request->hasFile('imagePath')) {
            // Supprime l'ancienne image
            Storage::disk('public')->delete($profile->image);
            $path = $request->file('imagePath')->store('profile-imagePath', 'public');
            $profile->image = $path;
        }

        $profile->update(array_filter($validated));

        return response()->json($profile);
    }

    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);

        // Supprimer l'image associée
        Storage::disk('public')->delete($profile->image);

        $profile->delete();

        return response()->json(['message' => 'Profile sucessfuly deleted']);
    }
}
