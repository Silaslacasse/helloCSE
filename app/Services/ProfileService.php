<?php

namespace App\Services;

use App\Models\Profile;
use App\Services\ProfileLogger;
use App\DTO\ProfileDTO;
use Illuminate\Support\Facades\Auth;

class ProfileService
{
    protected $profileLogger;

    public function __construct(ProfileLogger $profileLogger)
    {
        $this->profileLogger = $profileLogger;
    }

    public function getAllProfiles()
    {
        if (Auth::guard('sanctum')->check()) {
            return Profile::all(['id', 'name', 'firstName', 'imagePath', 'status', 'created_at', 'updated_at']);
        } else {
            return Profile::where('status', 'active')->get(['id', 'name', 'firstName', 'imagePath', 'created_at', 'updated_at']);
        }
    }

    public function getProfileById($id)
    {
        return Profile::findOrFail($id, ['id', 'name', 'firstName', 'imagePath', 'created_at', 'updated_at']);
    }

    public function createProfile(ProfileDTO $profileDTO)
    {
        $profile = Profile::create((array) $profileDTO);
        $this->profileLogger->logCreation($profile);
        return $profile;
    }

    public function updateProfile(ProfileDTO $profileDTO, $id)
    {
        $profile = Profile::findOrFail($id);
        $profile->update(array_filter((array) $profileDTO));
        $this->profileLogger->logUpdate($profile);
        return $profile;
    }

    public function deleteProfile($id)
    {
        $profile = Profile::findOrFail($id);
        $this->profileLogger->logDeletion($profile);
        $profile->delete();
    }
}
