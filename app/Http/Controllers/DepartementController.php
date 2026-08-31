<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    /**
     * Liste tous les départements.
     */
    public function index()
    {
        return response()->json(Departement::all(), 200);
    }
}
