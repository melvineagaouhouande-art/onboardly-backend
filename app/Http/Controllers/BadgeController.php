<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        return response()->json(Badge::all(), 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icone' => 'nullable|string',
            'points_requis' => 'nullable|integer',
        ]);

        $badge = Badge::create($validatedData);
        return response()->json($badge, 201);
    }

    public function show($id)
    {
        $badge = Badge::findOrFail($id);
        return response()->json($badge, 200);
    }

    public function update(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);

        $validatedData = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'icone' => 'nullable|string',
            'points_requis' => 'nullable|integer',
        ]);

        $badge->update($validatedData);
        return response()->json($badge, 200);
    }

    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();
        return response()->json(['message' => 'Badge supprimé avec succès'], 200);
    }
}