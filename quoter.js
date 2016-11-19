<script type="text/javascript">
	/**
	$Author: Ambroise Dhenain
	$Description: Génère aléatoirement une citation par jour différente de la précédente.

	$Utilisation: 
		* Si vous souhaitez rajouter des citations, il suffit de suivre le modèle existant. (Voir plus bas)

	$Fonctionnement: Un tableau de citations est écrit en dur, le script tire au sort une case de ce tableau afin d'en afficher la citation et l'auteur.
		Il est tout à fait possible de 'customiser' une citation, le code HTML fonctionnera. 

	$Date_création: 22 décembre 2011
	$Date_dernière_modification: 22 décembre 2011

	$Email: ambroisedhenain@hotmail.fr

	*/
	
	/**
	$Description: Fonctions servant à manipuler les cookies.
	
	*/
		function ecrire_cookie(nom, valeur, expires) {
		  document.cookie=nom+"="+escape(valeur)+
		  ((expires==null) ? "" : ("; expires="+expires.toGMTString()));
		}

		function arguments_cookie(offset){
		  var endstr=document.cookie.indexOf (";", offset);
		  if (endstr==-1) endstr=document.cookie.length;
		  return unescape(document.cookie.substring(offset, endstr)); 
		}

		function lire_cookie(nom) {
		  var arg=nom+"=";
		  var alen=arg.length;
		  var clen=document.cookie.length;
		  var i=0;
		  while (i<clen){
			var j=i+alen;
			if (document.cookie.substring(i, j)==arg)
			   return arguments_cookie(j);
			i=document.cookie.indexOf(" ",i)+1;
			if (i==0) break;
		  }
		  return null; 
		}

	
	// champs correspond à notre tableau qui va contenir toutes nos citations.
	champs = new Array();
	
	/**
	$Name: LesCitations
	$Description: Objet contenant les attributs d'une citation. (Équivaut à une classe en POO ou une structure en langage C.)

	$Utilisation: Si vous souhaitez rajouter un attribut, par exemple la date de parution de la citation.
		Rajoutez dans cette fonction:
			this.date_parution = date_parution;
		Modifiez function LesCitations(citation, auteur)
		En function LesCitations(citation, auteur, date_parution) {

	Ensuite pour chaque champs[champs.length] = new LesCitations("...", "..."); rajoutez un troisième argument:
	champs[champs.length] = new LesCitations("...", "...", "...");
	Ce troisième argument sera la date de parution (Dans notre exemple).
	Il faudra aussi modifier la fonction Citation() pour afficher ce nouveau champs:
		function Citation() {
			var x = Aleatoire(0, champs.length-1);

			document.write('<i>');
			document.write(champs[x].citation);
			document.write('</i><br /><b><br />');
			document.write(champs[x].auteur);
			document.write('<b><br />');
			document.write(champs[x].date_parution);
			document.write('<b><br />');
		}
		
	C'est tout !
	*/
	function LesCitations(citation, auteur) {
		this.citation = citation;
		this.auteur = auteur;
	}
	
	
	// ----------------------------------------------------- RAJOUT DE CITATIONS -------------------------------------------------------------------------
	
	/* 
		Pour rajouter une citation il suffit de copier coller une ligne et de modifier ce qu'il y a entre les deux premières doubles quotes (c'est la citation) 
		et de modifier l'auteur. (Entre les deux dernières double quote) 
		Double quote: "
	*/
	champs[champs.length] = new LesCitations("Un homme qui ne boit que de l'eau a un secret à cacher à ses semblables.", "Charles Baudelaire");
	champs[champs.length] = new LesCitations("Si tous ceux qui croient avoir raison n'avaient pas tort, la vérité ne serait pas loin.", "Pierre Dac");
	champs[champs.length] = new LesCitations("Le Dieu des chrétiens est un père qui fait grand cas de ses pommes et fort peu de ses enfants.", "Diderot");
	champs[champs.length] = new LesCitations("On peut faire semblant d'être grave, on ne peut pas faire semblant d'avoir de l'esprit.", "Sacha Guitry");
	champs[champs.length] = new LesCitations("La politique est l'art d'empêcher les gens de se mêler de ce qui les regarde.", "Paul Valéry");
	champs[champs.length] = new LesCitations("<b>Politesse</b> : Forme la plus acceptable de l'hypocrisie.", "Ambrose Bierce");
	champs[champs.length] = new LesCitations("Lorsque vous avez éliminé l'impossible, ce qui reste, si improbable soit-il, est nécessairement la vérité.", "Sir Arthur Conan Doyle");
	champs[champs.length] = new LesCitations("Il n'y a jamais eu de bonne guerre ni de mauvaise paix.", "Benjamin Franklin");
	champs[champs.length] = new LesCitations("La politique est peut-être la seule profession pour laquelle nulle préparation n'est jugée nécessaire.", "Robert Louis Stevenson");
	champs[champs.length] = new LesCitations("Le seul moyen de se délivrer de la tentation, c'est d'y céder.", "Oscar Wilde");

	// ----------------------------------------------------- FIN D'AJOUT DE CITATIONS -------------------------------------------------------------------------

	
	/**
	$Name: Aleatoire
	$Description: Tire au sort un chiffre compris en le mini et le maxi fournis, retourne ce chiffre.

	*/
	function Aleatoire(mini, maxi) {
		var x = -1;

		while (x < mini) {
			x = Math.round(Math.random() * maxi);
		}

		return x;
	}

	/**
	$Name: Citation
	$Description: Tire au sort une des citations existantes et l'affiche.

	*/
	function Citation() {
		// On appelle la fonction Aleatoire en lui demandant de nous tirer un chiffre aléatoire entre 0 et la taille du tableau - 1, ce chiffre sera contenu dans la variable index.
		var index = Aleatoire(0, champs.length-1);

		// On vérifie que l'index tiré au sort soit différent du dernier tiré.
		if(index == lire_cookie('dernier_index')){
			index++;
		}
		
		// On sauvegarde l'index tiré au sort dans le cookie.
		ecrire_cookie('dernier_index', index, null);
		
		
		// On affiche la citation.
		document.write('<i>');
		document.write(champs[index].citation);
		document.write('</i><br /><b><br />');
		document.write(champs[index].auteur);
		document.write('<b><br />');
	}

	// La ligne suivante affiche la citation.
	Citation();

</script>
