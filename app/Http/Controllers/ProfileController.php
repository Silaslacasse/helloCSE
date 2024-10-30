<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\DTO\ProfileDTO;

class ProfileController extends Controller
{

    public function getAllProfiles()
    {
        if (auth('sanctum')->check()) {
            // If the user is logged in, retrieve all profiles
            $profiles = Profile::all(['id', 'name', 'firstName', 'imagePath', 'status', 'created_at', 'updated_at']);
        } else {
            // If the user is not logged in, retrieve only profiles with active status
            $profiles = Profile::where('status', 'active')
                ->get(['id', 'name', 'firstName', 'imagePath', 'created_at', 'updated_at']);
        }

        return response()->json($profiles);
    }

    public function getProfileById($id)
    {
        $profiles = Profile::where('id', $id)->get(['id', 'name', 'firstName', 'imagePath', 'created_at', 'updated_at']);

        return response()->json($profiles);
    }

    public function store(StoreProfileRequest $request)
    {

        $profileDTO = new ProfileDTO(
            $request->input('name'),
            $request->input('firstName'),
            $request->input('imagePath'),
            $request->input('status', 'inactive')
        );
    
        $profile = Profile::create((array)$profileDTO); 
        return response()->json($profile, 201);

    }

    public function update(StoreProfileRequest $request, $id)
    {
        $profile = Profile::findOrFail($id);

        $validated = $request->validated();

        $profileDTO = new ProfileDTO(
            $validated['name'] ?? $profile->name,
            $validated['firstName'] ?? $profile->firstName,
            $validated['imagePath'] ?? $profile->imagePath,
            $validated['status'] ?? $profile->status
        );

        $profile->update(array_filter($profileDTO));

        return response()->json($profile);
    }

    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);

        $profile->delete();

        return response()->json(['message' => 'Profile sucessfuly deleted']);
    }
}
