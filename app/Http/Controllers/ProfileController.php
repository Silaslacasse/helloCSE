<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use App\Http\Requests\StoreProfileRequest;
use App\DTO\ProfileDTO;


class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function getAllProfiles()
    {
        $profiles = $this->profileService->getAllProfiles();
        return response()->json($profiles);
    }

    public function getProfileById($id)
    {
        $profile = $this->profileService->getProfileById($id);
        return response()->json($profile);
    }

    public function store(StoreProfileRequest $request)
    {
        $profileDTO = new ProfileDTO(
            $request->input('name'),
            $request->input('firstName'),
            $request->input('imagePath'),
            $request->input('status', 'inactive')
        );

        $profile = $this->profileService->createProfile($profileDTO);
        return response()->json($profile, 201);
    }

    public function update(StoreProfileRequest $request, $id)
    {
        $profileDTO = new ProfileDTO(
            $request->input('name'),
            $request->input('firstName'),
            $request->input('imagePath'),
            $request->input('status')
        );

        $profile = $this->profileService->updateProfile($profileDTO, $id);
        return response()->json($profile);
    }

    public function destroy($id)
    {
        $this->profileService->deleteProfile($id);
        return response()->json(['message' => 'Profile successfully deleted']);
    }
}