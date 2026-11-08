var stop = false;
var initial_timer = (1000*10);
var timer = initial_timer;

function logOut() {
    // Configuration des en-têtes de la requête AJAX avec le jeton CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    // Envoi de la requête AJAX de déconnexion
    $.ajax({
        //url: "http://superette.local/logout", // URL du point de terminaison de déconnexion
        method: 'GET', // Méthode HTTP utilisée pour la requête
        success: function (response) {
            // En cas de succès, rediriger l'utilisateur vers la page de connexion
            //window.location = "http://superette.local/login";
            window.location = "{{ route('logout') }}";

        },
        error: function (error) {
            // En cas d'erreur, afficher un message d'erreur à l'utilisateur
            console.error("Something went wrong");

            $('#error').html(`<div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    <strong>Une erreur est survenue!</strong> Veuillez réessayer!
                </div>
            </div>`);
        }
    });
}

if(!stop){
	setInterval(function(){

		timer-=1000;
        console.log(timer);
		if(timer==0 || timer<0){
			logOut()
			stop = true;
		}
	}, 1000);
}

$('body').bind('click dblclick mousedown mouseenter mouseleave keyup mouseover',
function(e){
	timer = initial_timer;
});
