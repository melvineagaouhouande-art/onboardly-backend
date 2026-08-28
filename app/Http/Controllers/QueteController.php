<?php

namespace App\Http\Controllers;

use App\Models\Quete;
use Illuminate\Http\Request;

class QueteController extends Controller
{
    public function index()
    {
        return response()->json(Quete::all(), 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'parcours_id' => 'required|exists:parcours,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points' => 'nullable|integer',
            'ordre' => 'nullable|integer',
        ]);

        $quete = Quete::create($validatedData);
        return response()->json($quete, 201);
    }

    public function show($id)
    {
        $quete = Quete::findOrFail($id);
        return response()->json($quete, 200);
    }

    public function update(Request $request, $id)
    {
        $quete = Quete::findOrFail($id);

        $validatedData = $request->validate([
            'parcours_id' => 'sometimes|exists:parcours,id',
            'titre' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'points' => 'nullable|integer',
            'ordre' => 'nullable|integer',
        ]);

        $quete->update($validatedData);
        return response()->json($quete, 200);
    }

    public function destroy($id)
    {
        $quete = Quete::findOrFail($id);
        $quete->delete();
        return response()->json(['message' => 'Quête supprimée avec succès'], 200);
    }
}