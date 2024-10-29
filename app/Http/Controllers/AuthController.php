<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function index()
    {
        $profils = Profil::where('statut', 'actif')->get(['id', 'nom', 'prenom', 'image', 'created_at', 'updated_at']);

        return response()->json($profils);
    }

    public function update(Request $request, $id)
    {
        $profil = Profil::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'string|max:255',
            'prenom' => 'string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'statut' => 'in:inactif,en attente,actif',
        ]);

        if ($request->hasFile('image')) {
            // Supprime l'ancienne image
            Storage::disk('public')->delete($profil->image);
            $path = $request->file('image')->store('images', 'public');
            $profil->image = $path;
        }

        $profil->update(array_filter($validated));

        return response()->json($profil);
    }

    public function destroy($id)
    {
        $profil = Profil::findOrFail($id);

        // Supprimer l'image associée
        Storage::disk('public')->delete($profil->image);

        $profil->delete();

        return response()->json(['message' => 'Profil supprimé avec succès']);
    }

}
