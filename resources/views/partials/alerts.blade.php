@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
        <h6><i class="fas fa-exclamation-triangle mr-2"></i><strong>Erreurs de validation :</strong></h6>
        <ul class="mb-0 pl-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif