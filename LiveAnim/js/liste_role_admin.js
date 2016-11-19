var $j = jQuery.noConflict();

function fx_add_role(element){
	//var id = $j(element).attr('id');// Inutile mais pourra servir.
	
	// On récupère les valeurs des textboxes.
	var role_singulier = $j('#role_singulier_new').val();
	var role_pluriel = $j('#role_pluriel_new').val();
	
	var datas = {
			'role_singulier': role_singulier,
			'role_pluriel': role_pluriel	
	};
	
	// On fait une requête AJAX.
	$j.ajax({
		type: 'POST',
		url: 'script_ajouter_role.php',
		data: datas,
		dataType: 'text',
		success: function(reponse){
			var reponses = reponse.split('|||');
			$j('#liste_role_admin_reponse').html(reponses[1]);
			if(reponses[0] == true){
				$j('#table_maj_event').prepend('<tr class="tr_'+role_singulier+'_'+role_pluriel+'"><th colspan="4"><hr /></th></tr><tr class="tr_'+role_singulier+'_'+role_pluriel+'"><th width="30%"><input id="role_singulier_'+role_singulier+'" name="role_singulier_'+role_singulier+'[]" type="text" value="'+role_singulier+'" /></th><th width="30%"><input id="role_pluriel_'+role_pluriel+'" name="role_pluriel_'+role_pluriel+'[]" type="text" value="'+role_pluriel+'" /></th><th width="20%"><input type="button" id="btn_upd_role_'+role_singulier+'" name="role_'+role_pluriel+'" value="Modifier" onclick="javascript: fx_upd_role(\''+role_singulier+'\', \''+role_pluriel+'\');" /></th><th width="20%"><input type="button" id="btn_upd_role_'+role_singulier+'" name="role_'+role_singulier+'" value="Supprimer" onclick="javascript: fx_del_role(\''+role_singulier+'\', \''+role_pluriel+'\');" /></th></tr>');
				$j('#role_singulier_new').val('');
				$j('#role_pluriel_new').val('');
			}
			new ElementMaxHeight();
		}
	});
}

function fx_upd_role(singulier, pluriel){
	var new_ROLE = $j('#role_singulier_'+singulier).val();
	var new_ROLES = $j('#role_pluriel_'+pluriel).val();
	
	var last_ROLE = singulier;
	var last_ROLES = pluriel;
	
	var datas = {
			'new_ROLE': new_ROLE,
			'new_ROLES': new_ROLES,
			'last_ROLE': last_ROLE,
			'last_ROLES': last_ROLES	
	};

	// On fait une requête AJAX.
	$j.ajax({
		type: 'POST',
		url: 'script_modifier_role.php',
		data: datas,
		dataType: 'text',
		success: function(reponse){
			var reponses = reponse.split('|||');
			$j('#liste_role_admin_reponse').html(reponses[1]);
			if(reponses[0] == true){
				if($j('.tr_'+last_ROLE+'_'+last_ROLES+'').html() != '<th colspan="4"><hr /></th>'){
					$j('.tr_'+last_ROLE+'_'+last_ROLES+'').html('<th width="30%"><input id="role_singulier_'+new_ROLE+'" name="role_singulier_'+new_ROLES+'[]" type="text" value="'+new_ROLE+'" /></th><th width="30%"><input id="role_pluriel_'+new_ROLES+'" name="role_pluriel_'+new_ROLES+'[]" type="text" value="'+new_ROLES+'" /></th><th width="20%"><input type="button" id="btn_upd_role_'+new_ROLE+'" name="role_'+new_ROLES+'" value="Modifier" onclick="javascript: fx_upd_role(\''+new_ROLE+'\', \''+new_ROLES+'\');" /></th><th width="20%"><input type="button" id="btn_upd_role_'+new_ROLE+'" name="role_'+new_ROLE+'" value="Supprimer" onclick="javascript: fx_del_role(\''+new_ROLE+'\', \''+new_ROLES+'\');" /></th>');
				}
			}
			new ElementMaxHeight();
		}
	});
}


function fx_del_role(singulier, pluriel){
	
	var datas = {
			'ROLE': singulier,
			'ROLES': pluriel,
	};

	// On fait une requête AJAX.
	$j.ajax({
		type: 'POST',
		url: 'script_supprimer_role.php',
		data: datas,
		dataType: 'text',
		success: function(reponse){
			var reponses = reponse.split('|||');
			$j('#liste_role_admin_reponse').html(reponses[1]);
			if(reponses[0] == true){
					$j('.tr_'+singulier+'_'+pluriel+'').css('display', 'none');
			}
			new ElementMaxHeight();
		}
	});
}
