<?php

namespace App\Http\Controllers;

use App\Models\Stagiaire;
use Illuminate\Http\Request;

class StagiaireController extends Controller
{
    public function index()
    {
        return response()->json(Stagiaire::all(), 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:stagiaires',
            'poste' => 'required|string|max:255',
            'departement' => 'nullable|string|max:255',
            'points' => 'nullable|integer',
            'avatar' => 'nullable|string',
            'date_debut' => 'nullable|date',
        ]);

        $stagiaire = Stagiaire::create($validatedData);
        return response()->json($stagiaire, 201);
    }

    public function show($id)
    {
        $stagiaire = Stagiaire::findOrFail($id);
        return response()->json($stagiaire, 200);
    }

    public function update(Request $request, $id)
    {
        $stagiaire = Stagiaire::findOrFail($id);

        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:stagiaires,email,'.$id,
            'poste' => 'sometimes|string|max:255',
            'departement' => 'nullable|string|max:255',
            'points' => 'nullable|integer',
            'avatar' => 'nullable|string',
            'date_debut' => 'nullable|date',
        ]);

        $stagiaire->update($validatedData);
        return response()->json($stagiaire, 200);
    }

    public function destroy($id)
    {
        $stagiaire = Stagiaire::findOrFail($id);
        $stagiaire->delete();
        return response()->json(['message' => 'Stagiaire supprimé avec succès'], 200);
    }
}