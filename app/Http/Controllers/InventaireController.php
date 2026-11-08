<?php

namespace App\Http\Controllers;


use App\Models\Inventaire;
use App\Models\InventaireLigne;
use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventaireController extends Controller
{
    public function index()
    {
        $inventaires = Inventaire::with('user')
            ->withCount('lignes')
            ->latest()
            ->paginate(15);

        return view('inventaires.index', compact('inventaires'));
    }

    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('inventaires.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'            => 'required|string',
            'categorie_filtre' => 'required|string',
            'notes'            => 'nullable|string',
        ]);

        $inventaire = Inventaire::create([
            'user_id'          => auth()->id(),
            'titre'            => $request->titre,
            'categorie_filtre' => $request->categorie_filtre,
            'statut'           => 'en_cours',
            'notes'            => $request->notes,
        ]);

        // Créer les lignes d'inventaire
        $query = Produit::where('actif', true);
        if ($request->categorie_filtre !== 'toutes') {
            $query->where('categorie_id', $request->categorie_filtre);
        }

        $produits = $query->get();
        foreach ($produits as $produit) {
            InventaireLigne::create([
                'inventaire_id'   => $inventaire->id,
                'produit_id'      => $produit->id,
                'stock_theorique' => $produit->stock_actuel,
                'stock_physique'  => null,
                'ecart'           => 0,
                'ecart_valeur'    => 0,
            ]);
        }

        return redirect()->route('inventaires.show', $inventaire)
            ->with('success', 'Inventaire créé avec succès.');
    }

    public function show(Inventaire $inventaire)
    {
        $inventaire->load([
            'lignes.produit.categorie',
            'user'
        ]);

        // Trier par catégorie puis produit
        $inventaire->lignes = $inventaire->lignes
            ->sortBy(function ($ligne) {
                return $ligne->produit->categorie->nom . ' ' . $ligne->produit->libelle;
            });

        return view('inventaires.show', compact('inventaire'));
    }

    public function update(Request $request, Inventaire $inventaire)
    {
        if ($inventaire->statut !== 'en_cours') {
            return back()->with('error', 'Cet inventaire ne peut plus être modifié.');
        }

        foreach ($request->lignes as $ligneId => $data) {
            $ligne = InventaireLigne::find($ligneId);
            if ($ligne && isset($data['stock_physique'])) {
                $ligne->stock_physique = (float) $data['stock_physique'];
                $ligne->ecart          = $ligne->stock_physique - (float) $ligne->stock_theorique;
                $ligne->ecart_valeur   = $ligne->ecart * (float) $ligne->produit->prix_detail;
                $ligne->save();
            }
        }

        // Recalculer le total écart
        $inventaire->total_ecart_valeur = $inventaire->lignes()->sum('ecart_valeur');
        $inventaire->save();

        return back()->with('success', 'Inventaire sauvegardé.');
    }

    public function cloturer(Inventaire $inventaire)
    {
        if ($inventaire->statut !== 'en_cours') {
            return back()->with('error', 'Cet inventaire est déjà clôturé.');
        }

        $inventaire->update([
            'statut'       => 'termine',
            'date_cloture' => now(),
        ]);

        return redirect()->route('inventaires.index')
            ->with('success', 'Inventaire clôturé avec succès.');
    }

    public function pdf(Inventaire $inventaire)
{
    $inventaire->load(['lignes.produit.categorie', 'user']);

    // Trier produit puis catégorie
    $inventaire->lignes = $inventaire->lignes
    ->sortBy([
        ['produit.categorie.nom', 'asc'],
        ['produit.libelle', 'asc']
    ])
    ->values();

    $pdf = Pdf::loadView('inventaires.pdf', compact('inventaire'))
        ->setPaper('a4', 'landscape');

    return $pdf->download('inventaire-' . $inventaire->id . '.pdf');
}

    public function pdfSansTheorique(Inventaire $inventaire)
    {
        $inventaire->load('lignes.produit.categorie');

        // Tri par catégorie puis par produit
        $lignes = $inventaire->lignes
            ->sortBy(function ($ligne) {
                return $ligne->produit->categorie->nom . ' ' . $ligne->produit->libelle;
            })
            ->values();

        $pdf = Pdf::loadView('inventaires.pdf_comptage', [
            'inventaire' => $inventaire,
            'lignes' => $lignes
        ]);

        return $pdf->stream('inventaire-comptage-'.$inventaire->id.'.pdf');
    }

    public function destroy(Inventaire $inventaire)
    {
        $inventaire->delete();
        return redirect()->route('inventaires.index')
            ->with('success', 'Inventaire supprimé.');
    }
}