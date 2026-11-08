<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DepenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Depense::with(['user', 'annuleur']);
        if ($request->filled('date_debut')) $query->whereDate('date_depense', '>=', $request->date_debut);
        if ($request->filled('date_fin')) $query->whereDate('date_depense', '<=', $request->date_fin);
        if ($request->filled('categorie')) $query->where('categorie', $request->categorie);
        if ($request->filled('search')) $query->where(fn ($q) => $q->where('designation', 'like', '%' . $request->search . '%')->orWhere('beneficiaire', 'like', '%' . $request->search . '%'));
        $total = (clone $query)->whereNull('annulee_at')->sum('montant');
        $depenses = $query->latest('date_depense')->paginate(25)->withQueryString();
        $totalJour = Depense::whereNull('annulee_at')->whereDate('date_depense', today())->sum('montant');
        $totalMois = Depense::whereNull('annulee_at')->whereYear('date_depense', today()->year)->whereMonth('date_depense', today()->month)->sum('montant');
        $categories = Depense::select('categorie')->distinct()->orderBy('categorie')->pluck('categorie');
        return view('depenses.index', compact('depenses', 'total', 'totalJour', 'totalMois', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'designation' => ['required', 'string', 'max:255'], 'categorie' => ['required', 'string', 'max:100'],
            'montant' => ['required', 'numeric', 'gt:0'], 'date_depense' => ['required', 'date'],
            'mode_paiement' => ['required', Rule::in(['espece', 'mobile_money', 'carte'])],
            'beneficiaire' => ['nullable', 'string', 'max:255'], 'observations' => ['nullable', 'string', 'max:3000'],
            'justificatif' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);
        if ($request->hasFile('justificatif')) $data['justificatif'] = $request->file('justificatif')->store('depenses');
        $data['user_id'] = auth()->id();
        Depense::create($data);
        return redirect()->route('depenses.index')->with('success', 'Dépense enregistrée.');
    }

    public function edit(Depense $depense)
    {
        abort_if($depense->annulee_at, 409, 'Une dépense annulée ne peut pas Etre modifiée.');
        return redirect()->route('depenses.index')->with('info', 'Ouvrez la modification depuis le bouton de la dépense.');
    }

    public function justificatif(Depense $depense)
    {
        abort_unless($depense->justificatif && Storage::exists($depense->justificatif), 404);
        return Storage::download($depense->justificatif);
    }

    public function update(Request $request, Depense $depense)
    {
        $data = $request->validate([
            'designation' => ['required', 'string', 'max:255'], 'categorie' => ['required', 'string', 'max:100'],
            'montant' => ['required', 'numeric', 'gt:0'], 'date_depense' => ['required', 'date'],
            'mode_paiement' => ['required', Rule::in(['espece', 'mobile_money', 'carte'])],
            'beneficiaire' => ['nullable', 'string', 'max:255'], 'observations' => ['nullable', 'string', 'max:3000'],
            'justificatif' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);
        DB::transaction(function () use ($request, $depense, &$data) {
            $locked = Depense::whereKey($depense->id)->lockForUpdate()->firstOrFail();
            if ($locked->annulee_at) abort(409, 'Une dépense annulée ne peut pas Etre modifiée.');
            if ($request->hasFile('justificatif')) $data['justificatif'] = $request->file('justificatif')->store('depenses');
            $locked->update($data);
        }, 3);
        return redirect()->route('depenses.index')->with('success', 'Dépense modifiée.');
    }

    public function cancel(Request $request, Depense $depense)
    {
        $request->validate(['motif_annulation' => ['required', 'string', 'max:1000']]);
        DB::transaction(function () use ($request, $depense) {
            $locked = Depense::whereKey($depense->id)->lockForUpdate()->firstOrFail();
            if ($locked->annulee_at) abort(409, 'Cette dépense est déjà  annulée.');
            $locked->update(['annulee_at' => now(), 'annulee_par' => auth()->id(), 'motif_annulation' => $request->motif_annulation]);
        });
        return redirect()->route('depenses.index')->with('success', 'Dépense annulée.');
    }
}
