<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::all();
        return response()->json([
            'status' => 200,
            'categories' => $categories
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name',
        ]);
        $categorie = Categorie::create($validated);
        return response()->json([
            'status' => 201,
            'message' => 'Catégorie créée avec succès.',
        ], 201);
    }
    
    public function show($id)
    {
        $categorie = Categorie::find($id);
        return response()->json([
            'status' => 200,
            'categorie' => $categorie
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $categorie = Categorie::find($id);
        if (!$categorie) {
            return response()->json([
                'status' => 404,
                'message' => 'Catégorie non trouvée.'
            ], 404);
        }
        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name,' . $categorie->id,
        ]);
        $categorie->update($validated);
        return response()->json([
            'status' => 200,
            'message' => 'Catégorie mise à jour avec succès.',
        ], 200);
    }

    public function destroy($id)
    {
        $categorie = Categorie::find($id);
        if (!$categorie) {
            return response()->json([
                'status' => 404,
                'message' => 'Catégorie non trouvée.'
            ], 404);
        }
        $categorie->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Catégorie supprimée avec succès.',
        ], 200);
    }
}
