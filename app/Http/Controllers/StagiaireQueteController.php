<?php

namespace App\Http\Controllers;

use App\Models\StagiaireQuete;
use Illuminate\Http\Request;

class StagiaireQueteController extends Controller
{
    public function index()
    {
        return response()->json(StagiaireQuete::all(), 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'stagiaire_id' => 'required|exists:stagiaires,id',
            'quete_id' => 'required|exists:quetes,id',
            'statut' => 'nullable|in:en_attente,en_cours,termine,alerte',
            'termine_le' => 'nullable|date',
        ]);

        $stagiaireQuete = StagiaireQuete::create($validatedData);
        return response()->json($stagiaireQuete, 201);
    }

    public function show($id)
    {
        $stagiaireQuete = StagiaireQuete::findOrFail($id);
        return response()->json($stagiaireQuete, 200);
    }

    public function update(Request $request, $id)
    {
        $stagiaireQuete = StagiaireQuete::findOrFail($id);

        $validatedData = $request->validate([
            'stagiaire_id' => 'sometimes|exists:stagiaires,id',
            'quete_id' => 'sometimes|exists:quetes,id',
            'statut' => 'sometimes|in:en_attente,en_cours,termine,alerte',
            'termine_le' => 'nullable|date',
        ]);

        $stagiaireQuete->update($validatedData);
        return response()->json($stagiaireQuete, 200);
    }

    public function destroy($id)
    {
        $stagiaireQuete = StagiaireQuete::findOrFail($id);
        $stagiaireQuete->delete();
        return response()->json(['message' => 'Affectation supprimée avec succès'], 200);
    }
}