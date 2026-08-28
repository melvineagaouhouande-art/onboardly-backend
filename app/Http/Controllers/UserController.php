<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'mot_de_passe' => 'required|string|min:6',
            'role' => 'nullable|in:rh,manager,admin',
        ]);

        $validatedData['password'] = Hash::make($validatedData['mot_de_passe']);

        $user = User::create($validatedData);
        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validatedData = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$id,
            'mot_de_passe' => 'nullable|string|min:6',
            'role' => 'sometimes|in:rh,manager,admin',
        ]);

        if (isset($validatedData['mot_de_passe'])) {
            $validatedData['password'] = Hash::make($validatedData['mot_de_passe']);
        }

        $user->update($validatedData);
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé avec succès'], 200);
    }
}