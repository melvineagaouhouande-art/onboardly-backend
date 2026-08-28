<?php

namespace App\Http\Controllers;

use App\Models\StagiaireBadge;
use Illuminate\Http\Request;

class StagiaireBadgeController extends Controller
{
    public function index()
    {
        return response()->json(StagiaireBadge::all(), 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'stagiaire_id' => 'required|exists:stagiaires,id',
            'badge_id' => 'required|exists:badges,id',
            'debloque_le' => 'nullable|date',
        ]);

        $stagiaireBadge = StagiaireBadge::create($validatedData);
        return response()->json($stagiaireBadge, 201);
    }

    public function show($id)
    {
        $stagiaireBadge = StagiaireBadge::findOrFail($id);
        return response()->json($stagiaireBadge, 200);
    }

    public function update(Request $request, $id)
    {
        $stagiaireBadge = StagiaireBadge::findOrFail($id);

        $validatedData = $request->validate([
            'stagiaire_id' => 'sometimes|exists:stagiaires,id',
            'badge_id' => 'sometimes|exists:badges,id',
            'debloque_le' => 'nullable|date',
        ]);

        $stagiaireBadge->update($validatedData);
        return response()->json($stagiaireBadge, 200);
    }

    public function destroy($id)
    {
        $stagiaireBadge = StagiaireBadge::findOrFail($id);
        $stagiaireBadge->delete();
        return response()->json(['message' => 'Badge débloqué supprimé avec succès'], 200);
    }
}