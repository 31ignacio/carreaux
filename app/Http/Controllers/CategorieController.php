<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    // ─── Liste + recherche ────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $categories = Categorie::withCount('produits')
            ->when($search, fn($q) =>
                $q->where('nom',         'like', "%{$search}%")
                  ->orWhere('description','like', "%{$search}%")
            )
            ->orderBy('nom')
            ->paginate(24)
            ->withQueryString();   // conserve ?search= dans les liens de pagination

        // Réponse JSON pour la recherche AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'html'        => view('categories._grid', compact('categories', 'search'))->render(),
                'pagination'  => view('partials.pagination', ['paginator' => $categories])->render(),
                'total'       => $categories->total(),
                'from'        => $categories->firstItem() ?? 0,
                'to'          => $categories->lastItem()  ?? 0,
                'count'       => $categories->count(),
                'currentPage' => $categories->currentPage(),
            ]);
        }

        $totalCategories = $categories->total();
        $totalProduits   = Categorie::withCount('produits')
                            ->get()
                            ->sum('produits_count');

        return view('categories.index', compact('categories', 'totalCategories', 'totalProduits', 'search'));
    }

    // ─── Créer ────────────────────────────────────────────────────────────────
   public function store(Request $request)
{
    $data = $request->validate([
        'nom'         => 'required|string|max:255|unique:categories,nom',
        'description' => 'nullable|string',
        'couleur'     => 'required|string|max:20',
    ]);
 
    $categorie = Categorie::create($data);
 
    // ── Réponse JSON pour les requêtes AJAX (création rapide depuis modal produit) ──
    if ($request->expectsJson()) {
        return response()->json([
            'id'      => $categorie->id,
            'nom'     => $categorie->nom,
            'couleur' => $categorie->couleur,
            'message' => 'Catégorie créée avec succès.',
        ], 201);
    }
 
    // ── Réponse normale pour les formulaires classiques ──
    return back()->with('success', 'Catégorie créée avec succès.');
}

    // ─── Modifier ─────────────────────────────────────────────────────────────
    public function update(Request $request, Categorie $categorie)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:255|unique:categories,nom,' . $categorie->id,
            'description' => 'nullable|string',
            'couleur'     => 'required|string|max:20',
        ]);

        $categorie->update($data);

        return back()->with('success', 'Catégorie mise à jour.');
    }

    // ─── Supprimer ────────────────────────────────────────────────────────────
    public function destroy(Categorie $categorie)
    {
        if ($categorie->produits()->count() > 0) {
            return back()->with('error', 'Impossible : des produits sont liés à cette catégorie.');
        }

        $categorie->delete();

        return back()->with('success', 'Catégorie supprimée.');
    }
}