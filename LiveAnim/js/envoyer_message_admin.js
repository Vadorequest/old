var $j = jQuery.noConflict();

function verif_choix(select){
	var choix = $j('#'+select).val();
	
	if(choix == 1){
		fx_modif_css('destinataire', 'display', 'block')
	}else{
		fx_modif_css('destinataire', 'display', 'none')
	}
}
