<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use Illuminate\Http\Request;

class AlerteController extends Controller
{
    public function index(Request $request)
    {
        $query = Alerte::with(['produit.categorie', 'traitePar']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('traitee')) {
            $query->where('traitee', $request->traitee);
        } else {
            $query->where('traitee', false);
        }

        $alertes = $query->latest()->paginate(20)->withQueryString();

        return view('alertes.index', compact('alertes'));
    }

    public function traiter(Alerte $alerte)
    {
        $alerte->traiter(auth()->id());
        return back()->with('success', 'Alerte marquée comme traitée.');
    }

    public function traiterTout()
    {
        Alerte::where('traitee', false)->each(function ($alerte) {
            $alerte->traiter(auth()->id());
        });

        return back()->with('success', 'Toutes les alertes ont été traitées.');
    }
}