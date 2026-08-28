<?php

namespace App\Http\Controllers;

use App\Models\Parcours;
use Illuminate\Http\Request;

class ParcoursController extends Controller
{
    public function index()
    {
        return response()->json(Parcours::all(), 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icone' => 'nullable|string',
        ]);

        $parcours = Parcours::create($validatedData);
        return response()->json($parcours, 201);
    }

    public function show($id)
    {
        $parcours = Parcours::findOrFail($id);
        return response()->json($parcours, 200);
    }

    public function update(Request $request, $id)
    {
        $parcours = Parcours::findOrFail($id);

        $validatedData = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'icone' => 'nullable|string',
        ]);

        $parcours->update($validatedData);
        return response()->json($parcours, 200);
    }

    public function destroy($id)
    {
        $parcours = Parcours::findOrFail($id);
        $parcours->delete();
        return response()->json(['message' => 'Parcours supprimé avec succès'], 200);
    }
}