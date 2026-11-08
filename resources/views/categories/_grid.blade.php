{{-- resources/views/categories/_grid.blade.php --}}
{{-- Partiel rendu par AJAX — ne pas inclure de layout --}}

@forelse($categories as $i => $cat)
    <div class="cat-card"
         id="cat-{{ $cat->id }}"
         data-name="{{ strtolower($cat->nom) }}"
         data-desc="{{ strtolower($cat->description ?? '') }}"
         style="--cat-color:{{ $cat->couleur }};animation-delay:{{ $i * 0.04 }}s">

        <div class="cat-card-stripe"></div>

        <div class="cat-card-body">
            <div class="cat-card-top">
                <div class="cat-color-dot" style="background:{{ $cat->couleur }}">
                    {{ strtoupper(substr($cat->nom, 0, 1)) }}
                </div>
                <div class="cat-info">
                    <p class="cat-name" id="cat-name-{{ $cat->id }}">
                        @if($search)
                            {!! preg_replace('/(' . preg_quote(e($search), '/') . ')/i',
                                '<mark class="cat-highlight">$1</mark>',
                                e($cat->nom))
                            !!}
                        @else
                            {{ $cat->nom }}
                        @endif
                    </p>
                    <p class="cat-desc">{{ $cat->description ?: 'Aucune description' }}</p>
                </div>
            </div>

            <div class="cat-card-foot">
                <span class="cat-prod-badge">
                    <i class="fas fa-box"></i>
                    {{ $cat->produits_count }} produit(s)
                </span>
                <div class="cat-actions">
                    <button class="cat-act-btn edit" title="Modifier"
                        onclick="openEdit({{ $cat->id }},'{{ addslashes($cat->nom) }}','{{ addslashes($cat->description ?? '') }}','{{ $cat->couleur }}')">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="cat-act-btn del"
                        title="{{ $cat->produits_count > 0 ? 'Impossible : produits liés' : 'Supprimer' }}"
                        {{ $cat->produits_count > 0 ? 'disabled' : '' }}
                        onclick="openDelete({{ $cat->id }},'{{ addslashes($cat->nom) }}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@empty
    {{-- État vide — géré par JS dans index.blade --}}
@endforelse