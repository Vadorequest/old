var map;
var panel;
var direction;

function initialiser_GMap(){
	var latLng = new google.maps.LatLng(50.6371834, 3.063017400000035); // Correspond au coordonnées de Lille
	var myOptions = {
		zoom      : 14, // Zoom par défaut
		center    : latLng, // Coordonnées de départ de la carte de type latLng 
		mapTypeId : google.maps.MapTypeId.ROADMAP, // Type de carte, différentes valeurs possible HYBRID, ROADMAP, SATELLITE, TERRAIN
		maxZoom   : 20,
		scrollwheel: false
	};
	
	map      = new google.maps.Map(document.getElementById('map'), myOptions);
	panel    = document.getElementById('panel');
	
	direction = new google.maps.DirectionsRenderer({
		map: map,
		panel: panel
	});
}

function calculate(){
    origin      = document.getElementById('origin').value; // Le point départ
    destination = document.getElementById('destination').value; // Le point d'arrivé
    if(origin && destination){
        var request = {
            origin      : origin,
            destination : destination,
            travelMode  : google.maps.DirectionsTravelMode.DRIVING // Type de transport
        }
        var directionsService = new google.maps.DirectionsService(); // Service de calcul d'itinéraire
        directionsService.route(request, function(response, status){ // Envoie de la requête pour calculer le parcours
            if(status == google.maps.DirectionsStatus.OK){
                direction.setDirections(response); // Trace l'itinéraire sur la carte et les différentes étapes du parcours
            }
        });
    }
}

function verifier_donnees(){
	if(document.getElementById('origin').value == ",  "){
		document.getElementById('map').style.display = "none";
		document.getElementById('map_infos').style.display = "none";
		document.getElementById('map_erreur').innerHTML = "<center><span class='alert'>Vous devez être connecté pour bénéficier de cette fonctionnalité !<br /></span></center><br /><br />";
	}
	if(document.getElementById('origin').value == ", 0 "){
		document.getElementById('map').style.display = "none";
		document.getElementById('map_infos').style.display = "none";
		document.getElementById('map_erreur').innerHTML = "<center><span class='orange'>Vous devez renseigner votre adresse dans votre profil pour bénéficier de cette fonctionnalité.<br /></span>"+
		"<br /><br /><a href='http://liveanim.com/modifier_fiche_perso.php#form_fiche_membre_adresse'>Modifier mon addresse.</a></center><br /><br />";
		document.getElementById('panel').innerHTML = "<center><span class='orange'>Vous devez renseigner votre adresse dans votre profil pour voir le trajet entre vous et l'organisateur.</span></center>";
	}
	if(document.getElementById('destination').value == ", 0 "){
		document.getElementById('map').style.display = "none";
		document.getElementById('map_infos').style.display = "none";
		document.getElementById('map_erreur').innerHTML = "<center><span class='orange'>L'organisateur n'a pas rentré d'adresse postale, la carte n'a donc pas été activée.<br />Signalez le lui si vous le pouvez afin qu'il prenne des mesures.<br />Merci.</center><br /><br />";
		document.getElementById('panel').innerHTML = "<center><span class='orange'>Nous ne pouvons pas vous guider jusqu'à l'organisateur.</span></center>";
	}
}