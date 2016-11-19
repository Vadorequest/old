var $j = jQuery.noConflict();
// Lorsque le DOM est chargé on applique le Javascript 
$j(document).ready(function() {
	// On ajoute la classe "js" à la liste pour mettre en place par la suite du code CSS uniquement dans le cas où le Javascript est activé
	$j("ul.notes-echelle").addClass("js");
	// On passe chaque note à l'état grisé par défaut
	$j("ul.notes-echelle li").addClass("note-off");
	// Au survol de chaque note à la souris
	
	$j("ul.notes-echelle li").mouseover(function() {
		// On passe les notes supérieures à l'état inactif (par défaut)
		$j(this).nextAll("li").addClass("note-off");
		// On passe les notes inférieures à l'état actif
		$j(this).prevAll("li").removeClass("note-off");
		// On passe la note survolée à l'état actif (par défaut)
		$j(this).removeClass("note-off");
	});
	
	// Lorsque l'on sort du sytème de notation à la souris
	$j("ul.notes-echelle").mouseout(function() {
		// On passe toutes les notes à l'état inactif
		$j(this).children("li").addClass("note-off");
		// On simule (trigger) un mouseover sur la note cochée s'il y a lieu
		$j(this).find("li input:checked").parent("li").trigger("mouseover");
	});
	
	$j("ul.notes-echelle input")
	// Lorsque le focus est sur un bouton radio
	.focus(function() {
		// On passe les notes supérieures à l'état inactif (par défaut)
		$j(this).parent("li").nextAll("li").addClass("note-off");
		// On passe les notes inférieures à l'état actif
		$j(this).parent("li").prevAll("li").removeClass("note-off");
		// On passe la note du focus à l'état actif (par défaut)
		$j(this).parent("li").removeClass("note-off");
	})
	
	// Lorsque l'on sort du sytème de notation au clavier
	.blur(function() {
		// Si il n'y a pas de case cochée
		if($j(this).parents("ul.notes-echelle").find("li input:checked").length == 0) {
			// On passe toutes les notes à l'état inactif
			$j(this).parents("ul.notes-echelle").find("li").addClass("note-off");
		}
	});
	
	$j("ul.notes-echelle input")
	// Lorsque le focus est sur un bouton radio
	.focus(function() {
		// On supprime les classes de focus
		$j(this).parents("ul.notes-echelle").find("li").removeClass("note-focus");
		// On applique la classe de focus sur l'item tabulé
		$j(this).parent("li").addClass("note-focus");
		// [...] cf. code précédent
	})
	// Lorsque l'on sort du sytème de notation au clavier
	.blur(function() {
		// On supprime les classes de focus
		$j(this).parents("ul.notes-echelle").find("li").removeClass("note-focus");
		// [...] cf. code précédent
	})
	// Lorsque la note est cochée
	.click(function() {
		// On supprime les classes de note cochée
		$j(this).parents("ul.notes-echelle").find("li").removeClass("note-checked");
		// On applique la classe de note cochée sur l'item choisi
		$j(this).parent("li").addClass("note-checked");
	});
	
	// On simule un survol souris des boutons cochés par défaut
	$j("ul.notes-echelle input:checked").parent("li").trigger("mouseover");
	// On simule un click souris des boutons cochés
	$j("ul.notes-echelle input:checked").trigger("click");
});