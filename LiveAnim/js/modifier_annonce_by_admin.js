
function fx_cacher(p, textarea){
	document.getElementById(p).style.visibility = 'hidden';
}

function fx_afficher(p, select, textarea){
	var liste = document.getElementById(select);
	
	if( liste.options[liste.selectedIndex].value == 'Refusée'){
		document.getElementById(p).style.visibility = 'visible';
	}else{
		document.getElementById(p).style.visibility = 'hidden';
	}
}
