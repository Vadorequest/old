var activer_pack_maintenant;

function maj_formulaire_paiement(div, id_personne, id_pack){
	var custom = document.getElementById(div);

	if(document.getElementById('activer_pack_maintenant').checked == true){
		activer_pack_maintenant = 1;
	}else{
		activer_pack_maintenant = 0;
	}
	
	custom.value = "id_personne="+id_personne+"&id_pack="+id_pack+"&duree=1&activer_pack_maintenant="+activer_pack_maintenant;

}