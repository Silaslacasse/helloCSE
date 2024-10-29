<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'statut' => 'in:inactif,en attente,actif',
        ]);

        $path = $request->file('image')->store('images', 'public');

        $profil = Profil::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'image' => $path,
            'statut' => $validated['statut'] ?? 'inactif',
        ]);

        return response()->json($profil, 201);
    }
}
