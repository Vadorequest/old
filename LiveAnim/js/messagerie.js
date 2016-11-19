	
/* Inverse la sélection pour un tableau de checkbox */
function invertselection (field) {
	var checkbox = document.getElementsByName(field);
	for (var i=0; i<checkbox.length;i++) {
		if (checkbox[i].type == 'checkbox') { 
			checkbox[i].checked = (checkbox[i].checked) ? false : true;
		}
	}
}