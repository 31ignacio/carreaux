{{-- resources/views/inventaires/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nouvel Inventaire')
@section('page-title', 'Nouvel Inventaire')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('inventaires.index') }}">Inventaires</a></li>
    <li class="breadcrumb-item active">Nouveau</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-purple text-white" style="background:#6f42c1">
                <h3 class="card-title mb-0">
                    <i class="fas fa-clipboard-list mr-2"></i> Nouvel inventaire
                </h3>
            </div>
            <form action="{{ route('inventaires.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label class="font-weight-bold">
                            Titre <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="titre"
                               class="form-control @error('titre') is-invalid @enderror"
                               value="{{ old('titre', 'Inventaire du ' . now()->format('d/m/Y')) }}"
                               required>
                        @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Filtrer par catégorie</label>
                        <select name="categorie_filtre" class="form-control select2">
                            <option value="toutes">Toutes les catégories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            Sélectionnez une catégorie pour un inventaire partiel
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"
                                  placeholder="Observations...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        L'inventaire sera créé avec le stock théorique actuel de chaque produit.
                        Vous pourrez ensuite saisir les stocks physiques.
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('inventaires.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-play mr-1"></i> Démarrer l'inventaire
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection