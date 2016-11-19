/**
* Auto poke back anyone who pokes you.
* 10000 ms delay.
*/
setInterval(function(){
	var aTags = document.getElementsByTagName("a");
	var searchText = "Envoyer un poke en retour";// Replace this by your language's sentence

	for (var i = 0; i < aTags.length; i++) {
	  if (aTags[i].textContent == searchText) {
	  	var elementFound = aTags[i];

		elementFound.click();
		console.log('Vous avez automatiquement poké en retour !')
	  }
	}

}, 10000);
