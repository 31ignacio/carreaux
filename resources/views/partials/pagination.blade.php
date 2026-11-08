@if($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">
            Affichage de {{ $paginator->firstItem() }} à {{ $paginator->lastItem() }}
            sur {{ $paginator->total() }} résultats
        </small>
        {{ $paginator->links() }}
    </div>
@endif