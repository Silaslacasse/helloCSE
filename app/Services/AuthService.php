<?php

namespace App\Services;

use App\Models\Admin;
use App\DTO\AdminDTO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthService
{
    public function register(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors(),
                'status' => 422,
            ];
        }

        $adminDTO = new AdminDTO(
            $data['name'],
            $data['email'],
            Hash::make($data['password']) 
        );

        $admin = Admin::create($adminDTO->toArray());

        $token = $admin->createToken('auth_token')->plainTextToken;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'status' => 201,
        ];
    }

    public function login(array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors(),
                'status' => 422,
            ];
        }

        $admin = Admin::where('email', $data['email'])->first();

        if (!$admin || !Hash::check($data['password'], $admin->password)) {
            return [
                'message' => 'Invalid credentials',
                'status' => 401,
            ];
        }

        $token = $admin->createToken('auth_token')->plainTextToken;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'status' => 200,
        ];
    }
}
