<?php 
namespace App\Services;

use Illuminate\Support\Facades\Log;

class ProfileLogger
{
    public function logCreation($profile)
    {
        Log::info('Profile created', [
            'profile_id' => $profile->id,
            'name' => $profile->name,
            'firstName' => $profile->firstName,
            'imagePath' => $profile->imagePath,
            'status' => $profile->status,
            'created_at' => $profile->created_at,
            'updated_at' => $profile->updated_at,
        ]);
    }

    public function logUpdate($profile)
    {
        Log::info('Profile updated', [
            'profile_id' => $profile->id,
            'name' => $profile->name,
            'firstName' => $profile->firstName,
            'imagePath' => $profile->imagePath,
            'status' => $profile->status,
            'updated_at' => $profile->updated_at,
        ]);
    }

    public function logDeletion($profile)
    {
        Log::info('Profile deleted', [
            'profile_id' => $profile->id,
            'name' => $profile->name,
            'firstName' => $profile->firstName,
            'imagePath' => $profile->imagePath,
            'status' => $profile->status,
            'deleted_at' => now(),
        ]);
    }
}
