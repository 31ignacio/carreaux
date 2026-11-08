<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::withCount('produits')->orderBy('nom')->paginate(15);
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('fournisseurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'             => 'required|string|unique:fournisseurs,nom',
            'telephone'       => 'nullable|string',
            'email'           => 'nullable|email',
            'adresse'         => 'nullable|string',
            'ville'           => 'nullable|string',
            'contact_personne' => 'nullable|string',
        ]);

        Fournisseur::create($request->all());

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur créé avec succès.');
    }

    public function show(Fournisseur $fournisseur)
{
    $fournisseur->load(['produits.categorie']);
 
    $mouvements = $fournisseur->mouvementsStock()
        ->with(['produit', 'user'])
        ->where('type', 'entree')
        ->latest()
        ->limit(15)
        ->get();
 
    return view('fournisseurs.show', compact('fournisseur', 'mouvements'));
}

    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $request->validate([
            'nom'             => 'required|string|unique:fournisseurs,nom,' . $fournisseur->id,
            'telephone'       => 'nullable|string',
            'email'           => 'nullable|email',
            'adresse'         => 'nullable|string',
            'ville'           => 'nullable|string',
            'contact_personne' => 'nullable|string',
        ]);

        $fournisseur->update($request->all());

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur mis à jour avec succès.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        if ($fournisseur->produits()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un fournisseur avec des produits.');
        }

        $fournisseur->delete();
        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur supprimé.');
    }
}