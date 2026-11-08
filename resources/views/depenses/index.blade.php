@extends('layouts.app')
@section('title', 'Dépenses')
@section('page-title', 'Gestion des dépenses')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap');

:root {
    --bg:     #F8F9FB;
    --card:   #ffffff;
    --border: #E8ECF0;
    --text:   #141C26;
    --muted:  #7A8899;
    --blue:   #2563EB;
    --blue-l: #EEF4FF;
    --green:  #16A34A;
    --green-l:#E8F8EE;
    --amber:  #D97706;
    --amber-l:#FEF3C7;
    --red:    #DC2626;
    --red-l:  #FEF2F2;
    --sh:     0 2px 12px rgba(20,28,38,.07);
    --sh-md:  0 6px 24px rgba(20,28,38,.10);
    --r:      14px;
}

.dp-wrap { font-family: 'DM Sans', sans-serif; color: var(--text); }

/* â”€â”€ KPI â”€â”€ */
.dp-kpi { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 22px; }
.dp-kpi-card {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--r); padding: 18px 20px;
    display: flex; align-items: center; gap: 14px;
    box-shadow: var(--sh);
}
.dp-kpi-ico {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; flex-shrink: 0;
}
.dp-kpi-ico.blue  { background: var(--blue-l);  color: var(--blue); }
.dp-kpi-ico.amber { background: var(--amber-l); color: var(--amber); }
.dp-kpi-ico.red   { background: var(--red-l);   color: var(--red); }
.dp-kpi-val { font-size: 1.3rem; font-weight: 800; line-height: 1; margin-bottom: 3px; }
.dp-kpi-lbl { font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }

/* â”€â”€ Section card â”€â”€ */
.dp-card {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--r); box-shadow: var(--sh);
    margin-bottom: 20px; overflow: hidden;
}
.dp-card-head {
    padding: 14px 20px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px; background: #FAFBFD;
}
.dp-card-head h2 {
    margin: 0; font-size: 13px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .5px; color: var(--text);
    display: flex; align-items: center; gap: 8px;
}
.dp-card-head h2 i { color: var(--red); }
.dp-card-body { padding: 20px; }

/* â”€â”€ Formulaire â”€â”€ */
.dp-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 14px;
}
.dp-field { display: flex; flex-direction: column; gap: 5px; }
.dp-field label {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .5px; color: var(--muted);
}
.dp-inp-wrap {
    display: flex; align-items: center;
    background: var(--bg); border: 1.5px solid var(--border);
    border-radius: 10px; overflow: hidden; transition: border-color .15s, box-shadow .15s;
}
.dp-inp-wrap:focus-within {
    border-color: var(--red); background: #fff;
    box-shadow: 0 0 0 3px rgba(220,38,38,.08);
}
.dp-inp-wrap i {
    width: 36px; text-align: center; color: var(--muted); font-size: 12px; flex-shrink: 0;
}
.dp-inp-wrap:focus-within i { color: var(--red); }
.dp-inp-wrap input,
.dp-inp-wrap select,
.dp-inp-wrap textarea {
    flex: 1; border: none; background: transparent;
    padding: 9px 10px 9px 0; font-size: 13px; color: var(--text);
    outline: none; font-family: 'DM Sans', sans-serif;
}
.dp-inp-wrap select { appearance: none; }
.dp-inp-wrap.file-wrap { padding: 8px 12px; cursor: pointer; }
.dp-inp-wrap.file-wrap input[type=file] {
    padding: 0; font-size: 12px; cursor: pointer;
}

.dp-btn-submit {
    height: 42px; padding: 0 24px; border-radius: 10px; border: none;
    background: linear-gradient(135deg, var(--red), #b91c1c);
    color: #fff; font-size: 13px; font-weight: 700;
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    display: inline-flex; align-items: center; gap: 7px;
    box-shadow: 0 4px 14px rgba(220,38,38,.25); transition: all .15s;
    align-self: flex-end;
}
.dp-btn-submit:hover { opacity: .9; transform: translateY(-1px); }

/* â”€â”€ Filtres â”€â”€ */
.dp-filter-bar {
    display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
    padding: 14px 20px; border-bottom: 1px solid var(--border);
    background: #FAFBFD;
}
.dp-fi {
    flex: 1; min-width: 150px;
    display: flex; align-items: center;
    background: var(--bg); border: 1.5px solid var(--border);
    border-radius: 10px; overflow: hidden; transition: border-color .15s;
}
.dp-fi:focus-within { border-color: var(--blue); }
.dp-fi i { width: 34px; text-align: center; color: var(--muted); font-size: 12px; flex-shrink: 0; }
.dp-fi input, .dp-fi select {
    flex: 1; border: none; background: transparent;
    padding: 8px 8px 8px 0; font-size: 12.5px; color: var(--text);
    outline: none; font-family: 'DM Sans', sans-serif;
}
.dp-fi select { appearance: none; }
.dp-btn-filter {
    height: 38px; padding: 0 16px; border-radius: 10px; border: none;
    background: var(--blue); color: #fff; font-size: 12.5px; font-weight: 700;
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    display: inline-flex; align-items: center; gap: 6px; flex-shrink: 0;
}
.dp-btn-reset {
    height: 38px; padding: 0 12px; border-radius: 10px;
    border: 1px solid var(--border); background: var(--card);
    color: var(--muted); font-size: 12.5px; font-weight: 600;
    cursor: pointer; text-decoration: none; flex-shrink: 0;
    display: inline-flex; align-items: center; gap: 5px;
}
.dp-btn-reset:hover { background: var(--bg); color: var(--text); text-decoration: none; }

/* â”€â”€ Table â”€â”€ */
.dp-table-wrap { overflow-x: auto; }
.dp-table {
    width: 100%; border-collapse: collapse;
    font-size: 12.5px; min-width: 800px;
}
.dp-table thead th {
    padding: 10px 16px; text-align: left;
    font-size: 10.5px; color: var(--muted);
    text-transform: uppercase; letter-spacing: .5px; font-weight: 600;
    background: var(--bg); border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.dp-table tbody tr { border-bottom: 1px solid #F1F4F7; transition: background .1s; }
.dp-table tbody tr:last-child { border-bottom: none; }
.dp-table tbody tr:hover td { background: #FAFBFD; }
.dp-table tbody tr.annulee td { opacity: .55; }
.dp-table td { padding: 11px 16px; vertical-align: middle; }

.dp-designation { font-weight: 600; color: var(--text); }
.dp-obs { font-size: 11.5px; color: var(--muted); margin-top: 3px; }
.dp-clip { color: var(--blue); font-size: 12px; margin-left: 5px; }

.dp-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 10.5px; font-weight: 700;
}
.dp-badge.active  { background: var(--green-l); color: var(--green); }
.dp-badge.annulee { background: var(--border); color: var(--muted); }

.dp-annul-info { font-size: 11px; color: var(--muted); margin-top: 4px; line-height: 1.5; }

.dp-mode {
    font-size: 11.5px; font-weight: 600; padding: 3px 9px;
    border-radius: 20px; background: var(--bg); color: var(--muted);
    border: 1px solid var(--border); white-space: nowrap;
}

.dp-montant { font-weight: 800; color: var(--text); white-space: nowrap; }

/* Actions */
.dp-acts { display: flex; gap: 5px; align-items: center; }
.dp-act-btn {
    height: 30px; padding: 0 10px; border-radius: 8px; font-size: 11.5px;
    font-weight: 600; display: inline-flex; align-items: center; gap: 4px;
    cursor: pointer; border: none; font-family: 'DM Sans', sans-serif;
    text-decoration: none; transition: all .15s; white-space: nowrap;
}
.dp-act-btn.edit {
    background: var(--blue-l); color: var(--blue); border: 1px solid #BFDBFE;
}
.dp-act-btn.edit:hover { background: var(--blue); color: #fff; text-decoration: none; }
.dp-act-btn.cancel {
    background: var(--red-l); color: var(--red); border: 1px solid #FECACA;
}
.dp-act-btn.cancel:hover { background: var(--red); color: #fff; }

/* Empty */
.dp-empty { text-align: center; padding: 50px 20px; color: var(--muted); }
.dp-empty i { font-size: 32px; opacity: .2; display: block; margin-bottom: 10px; }

@media(max-width: 768px) {
    .dp-kpi { grid-template-columns: 1fr; }
    .dp-form-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="dp-wrap">

    {{-- â”€â”€ KPI â”€â”€ --}}
    <div class="dp-kpi">
        <div class="dp-kpi-card">
            <div class="dp-kpi-ico blue"><i class="fas fa-calendar-day"></i></div>
            <div>
                <div class="dp-kpi-val">{{ number_format($totalJour, 0, ',', ' ') }} F</div>
                <div class="dp-kpi-lbl">Dépenses aujourd'hui</div>
            </div>
        </div>
        <div class="dp-kpi-card">
            <div class="dp-kpi-ico amber"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <div class="dp-kpi-val">{{ number_format($totalMois, 0, ',', ' ') }} F</div>
                <div class="dp-kpi-lbl">Dépenses du mois</div>
            </div>
        </div>
        <div class="dp-kpi-card">
            <div class="dp-kpi-ico red"><i class="fas fa-filter"></i></div>
            <div>
                <div class="dp-kpi-val" style="color:var(--red)">{{ number_format($total, 0, ',', ' ') }} F</div>
                <div class="dp-kpi-lbl">Total période filtrée</div>
            </div>
        </div>
    </div>

    <button type="button" class="dp-btn-submit mb-3" data-toggle="modal" data-target="#modalCreateDepense"><i class="fas fa-plus"></i> Enregistrer une dépense</button>
    <div class="modal fade" id="modalCreateDepense" tabindex="-1" role="dialog" aria-labelledby="modalCreateDepenseTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document"><div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="modalCreateDepenseTitle">Enregistrer une dépense</h5><button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button></div>
            <div class="modal-body">    {{-- â”€â”€ Formulaire d'enregistrement â”€â”€ --}}
    <div class="dp-card">
        <div class="dp-card-head">
            <h2><i class="fas fa-plus-circle"></i> Enregistrer une dépense</h2>
        </div>
        <div class="dp-card-body">
            <form method="POST" action="{{ route('depenses.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="dp-form-grid">

                    <div class="dp-field" style="grid-column: span 2">
                        <label>Désignation *</label>
                        <div class="dp-inp-wrap">
                            <i class="fas fa-tag"></i>
                            <input type="text" name="designation" required maxlength="255"
                                   value="{{ old('designation') }}" placeholder="Ex: Achat fournitures">
                        </div>
                    </div>

                    <div class="dp-field">
                        <label>Catégorie *</label>
                        <div class="dp-inp-wrap">
                            <i class="fas fa-layer-group"></i>
                            <input type="text" name="categorie" required maxlength="100"
                                   list="categories-depense" placeholder="Choisir ou saisir">
                            <datalist id="categories-depense">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <div class="dp-field">
                        <label>Montant (FCFA) *</label>
                        <div class="dp-inp-wrap">
                            <i class="fas fa-coins"></i>
                            <input type="number" name="montant" required min="1" step="0.01" placeholder="0">
                        </div>
                    </div>

                    <div class="dp-field">
                        <label>Date *</label>
                        <div class="dp-inp-wrap">
                            <i class="fas fa-calendar"></i>
                            <input type="date" name="date_depense" required value="{{ today()->toDateString() }}">
                        </div>
                    </div>

                    <div class="dp-field">
                        <label>Mode de paiement</label>
                        <div class="dp-inp-wrap">
                            <i class="fas fa-credit-card"></i>
                            <select name="mode_paiement">
                                <option value="espece">Espèces</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="carte">Carte bancaire</option>
                            </select>
                        </div>
                    </div>

                    <div class="dp-field">
                        <label>Bénéficiaire / fournisseur</label>
                        <div class="dp-inp-wrap">
                            <i class="fas fa-user"></i>
                            <input type="text" name="beneficiaire" maxlength="255" placeholder="Optionnel">
                        </div>
                    </div>

                    <div class="dp-field" style="grid-column: span 2">
                        <label>Observations</label>
                        <div class="dp-inp-wrap">
                            <i class="fas fa-comment-alt"></i>
                            <input type="text" name="observations" maxlength="3000" placeholder="Optionnel">
                        </div>
                    </div>

                    <div class="dp-field">
                        <label>Justificatif (PDF/image, 5 Mo max.)</label>
                        <div class="dp-inp-wrap file-wrap">
                            <i class="fas fa-paperclip"></i>
                            <input type="file" name="justificatif" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>

                    <div class="dp-field" style="justify-content:flex-end">
                        <label>&nbsp;</label>
                        <button type="submit" class="dp-btn-submit">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

            </div>
        </div></div>
    </div>
    {{--  Liste des dépenses  --}}
    <div class="dp-card">
        <div class="dp-card-head">
            <h2><i class="fas fa-list"></i> Liste des dépenses</h2>
            <span style="font-size:12.5px;font-weight:700;color:var(--red)">
                Total filtré : {{ number_format($total, 0, ',', ' ') }} FCFA
            </span>
        </div>

        {{-- Filtres --}}
        <form method="GET" class="dp-filter-bar">
            <div class="dp-fi" style="flex:2;min-width:200px">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Désignation, bénéficiaire¦"
                       value="{{ request('search') }}">
            </div>
            <div class="dp-fi">
                <i class="fas fa-layer-group"></i>
                <select name="categorie">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $cat)
                        <option @selected(request('categorie') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="dp-fi">
                <i class="fas fa-calendar"></i>
                <input type="date" name="date_debut" value="{{ request('date_debut') }}">
            </div>
            <div class="dp-fi">
                <i class="fas fa-calendar"></i>
                <input type="date" name="date_fin" value="{{ request('date_fin') }}">
            </div>
            <button type="submit" class="dp-btn-filter">
                <i class="fas fa-search"></i> Filtrer
            </button>
            @if(request()->hasAny(['search','categorie','date_debut','date_fin']))
                <a href="{{ route('depenses.index') }}" class="dp-btn-reset">
                    <i class="fas fa-times"></i> Effacer
                </a>
            @endif
        </form>

        {{-- Table --}}
        <div class="dp-table-wrap">
            <table class="dp-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Désignation</th>
                        <th>Catégorie</th>
                        <th>Bénéficiaire</th>
                        <th>Mode</th>
                        <th>Montant</th>
                        <th>Enregistré par</th>
                        <th>Etat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($depenses as $depense)
                        <tr class="{{ $depense->annulee_at ? 'annulee' : '' }}">

                            <td style="white-space:nowrap;color:var(--muted)">
                                {{ $depense->date_depense->format('d/m/Y') }}
                            </td>

                            <td>
                                <div class="dp-designation">
                                    {{ $depense->designation }}
                                    @if($depense->justificatif)
                                        <a href="{{ route('depenses.justificatif', $depense) }}"
                                           target="_blank" title="Voir le justificatif" class="dp-clip">
                                            <i class="fas fa-paperclip"></i>
                                        </a>
                                    @endif
                                </div>
                                @if($depense->observations)
                                    <div class="dp-obs">{{ $depense->observations }}</div>
                                @endif
                            </td>

                            <td>
                                <span style="font-size:12px;font-weight:600;color:var(--text)">
                                    {{ $depense->categorie }}
                                </span>
                            </td>

                            <td style="color:var(--muted)">{{ $depense->beneficiaire ?: ' - ' }}</td>

                            <td>
                                <span class="dp-mode">
                                    {{ str_replace('_', ' ', ucfirst($depense->mode_paiement)) }}
                                </span>
                            </td>

                            <td class="dp-montant">
                                {{ number_format($depense->montant, 0, ',', ' ') }} F
                            </td>

                            <td style="color:var(--muted);font-size:12px">
                                {{ $depense->user->nom_complet }}
                            </td>

                            <td>
                                @if($depense->annulee_at)
                                    <span class="dp-badge annulee">
                                        <i class="fas fa-ban" style="font-size:9px"></i> Annulée
                                    </span>
                                    <div class="dp-annul-info">
                                        {{ $depense->annulee_at->format('d/m/Y H:i') }}<br>
                                        {{ $depense->annuleur->nom_complet ?? ' - ' }}<br>
                                        <em>{{ $depense->motif_annulation }}</em>
                                    </div>
                                @else
                                    <span class="dp-badge active">
                                        <i class="fas fa-check-circle" style="font-size:9px"></i> Active
                                    </span>
                                @endif
                            </td>

                            <td>
                                @unless($depense->annulee_at)
                                    <div class="dp-acts">
                                        <button type="button" class="dp-act-btn edit"
                                           onclick="modifierDepense(this)"
                                           data-id="{{ $depense->id }}"
                                           data-designation="{{ $depense->designation }}"
                                           data-categorie="{{ $depense->categorie }}"
                                           data-montant="{{ $depense->montant }}"
                                           data-date="{{ $depense->date_depense->toDateString() }}"
                                           data-mode="{{ $depense->mode_paiement }}"
                                           data-beneficiaire="{{ $depense->beneficiaire }}"
                                           data-observations="{{ $depense->observations }}">
                                            <i class="fas fa-pen"></i> Modifier
                                        </button>
                                        <button type="button" class="dp-act-btn cancel"
                                                onclick="annulerDepense({{ $depense->id }})">
                                            <i class="fas fa-times"></i> Annuler
                                        </button>
                                    </div>
                                    <form id="cancel-{{ $depense->id }}"
                                          method="POST"
                                          action="{{ route('depenses.annuler', $depense) }}"
                                          style="display:none">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="motif_annulation">
                                    </form>
                                @endunless
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="dp-empty">
                                    <i class="fas fa-inbox"></i>
                                    <p style="font-size:13px;font-weight:600;margin:0">
                                        Aucune dépense sur cette sélection.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding:14px 20px;border-top:1px solid var(--border);background:#FAFBFD">
            {{ $depenses->links() }}
        </div>
    </div>

</div>

<div class="modal fade" id="modalEditDepense" tabindex="-1" role="dialog" aria-labelledby="modalEditDepenseTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <form id="formEditDepense" method="POST" enctype="multipart/form-data" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditDepenseTitle">Modifier la dépense</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6"><label>Désignation</label><input class="form-control" name="designation" required maxlength="255"></div>
                        <div class="form-group col-md-6"><label>Catégorie</label><input class="form-control" name="categorie" required maxlength="100" list="categories-depense"></div>
                        <div class="form-group col-md-4"><label>Montant (FCFA)</label><input class="form-control" name="montant" type="number" min="0.01" step="0.01" required></div>
                        <div class="form-group col-md-4"><label>Date</label><input class="form-control" name="date_depense" type="date" required></div>
                        <div class="form-group col-md-4"><label>Mode de paiement</label><select class="form-control" name="mode_paiement" required><option value="espece">Espèces</option><option value="mobile_money">Mobile Money</option><option value="carte">Carte bancaire</option></select></div>
                        <div class="form-group col-md-6"><label>Bénéficiaire / fournisseur</label><input class="form-control" name="beneficiaire" maxlength="255"></div>
                        <div class="form-group col-md-6"><label>Observations</label><input class="form-control" name="observations" maxlength="3000"></div>
                        <div class="form-group col-12"><label>Remplacer la pièce justificative (facultatif)</label><input class="form-control-file" name="justificatif" type="file" accept=".pdf,.jpg,.jpeg,.png"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button><button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Enregistrer</button></div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function modifierDepense(button) {
    const form = document.getElementById('formEditDepense');
    form.action = `{{ url('depenses') }}/${button.dataset.id}`;
    ['designation', 'categorie', 'montant', 'date_depense', 'mode_paiement', 'beneficiaire', 'observations'].forEach(name => {
        const field = form.elements.namedItem(name);
        if (field) field.value = button.dataset[name.replace('date_depense', 'date').replace('mode_paiement', 'mode')] || '';
    });
    $('#modalEditDepense').modal('show');
}

function annulerDepense(id) {
    Swal.fire({
        title: 'Annuler cette dépense ?',
        input: 'textarea',
        inputLabel: 'Motif (obligatoire)',
        inputPlaceholder: 'Expliquez la raison de l\'annulation',
        inputValidator: v => !v ? 'Le motif est obligatoire.' : null,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Confirmer l\'annulation',
        cancelButtonText: 'Retour',
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
    }).then(r => {
        if (r.isConfirmed) {
            const f = document.getElementById('cancel-' + id);
            f.querySelector('[name=motif_annulation]').value = r.value;
            f.submit();
        }
    });
}
</script>
@endpush
