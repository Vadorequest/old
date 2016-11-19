<?php
class CL_date{

	private $seconde;
	private $minute;
	private $heure;
	private $jour;
	private $mois;
	private $annee;
	
	private $date;
	private $now;
	
	private $tab_date;
	
	public function __construct(){
	
		$this->seconde = date("s");
		$this->minute = date("i");
		$this->heure = date("H");
		$this->jour = date("d");
		$this->mois = date("m");
		$this->annee = date("Y");
		
		$this->date = date("Y-m-d");
		$this->now = date("Y-m-d H:i:s");
		
		$this->tab_date = array();
	}
	
	/**
    * @desc Convertit la date au format desiré.
    * @param date $date : Date que l'on souhaite modifier.
    * @return date
	*
	*	/!\ Utiliser plutôt la fonction fx_ajouter_date qui permet de faire la même chose et plus encore. /!\
	*
	*
    */
	public function fx_convertir_date($date, $datetime = false, $return_date_formatee = false, $langue = "fr", $mois_ajout = 0){
		# On remplace toutes les "/" en "-".
		$this->date = str_replace("/", "-", $date);
		
		if($langue == "fr"){
			if(!$datetime){
				$this->tab_date = explode('-' , $this->date);
				$this->date  = $this->tab_date[2].'-'.$this->tab_date[1].'-'.$this->tab_date[0];
				return $this->date;
			
			}else{
				$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([Y][M][D + H:i:s])	
				$tab_date2 = explode(' ', $tab_date[2]);# On récupère le reste de la date dans un tableau à deux cases ([D][H:i:s])
				$tab_date3 = explode(':', $tab_date2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
				$date = date("d-m-Y H:i:s", mktime($tab_date3[0], $tab_date3[1], $tab_date3[2], $tab_date[1]+$mois_ajout, $tab_date2[0],  $tab_date[0]));
				$date_formatee = date("YmdHis", mktime($tab_date3[0], $tab_date3[1], $tab_date3[2], $tab_date[1]+$mois_ajout, $tab_date2[0],  $tab_date[0]));
				if(!$return_date_formatee){
					return $date;
				}else{
					return $date_formatee;
				}
			}
			# A vérifier car non testé.
		}else if($langue == "en"){
			if(!$datetime){
				$this->tab_date = explode('-' , $this->date);
				$this->date  = $this->tab_date[0].'-'.$this->tab_date[1].'-'.$this->tab_date[2];
				return $this->date;
			
			}else{
				$tab_date = explode('-', $date);# On récupère la date dans un tableau de trois cases ([Y][M][D + H:i:s])	
				$tab_date2 = explode(' ', $tab_date[2]);# On récupère le reste de la date dans un tableau à deux cases ([D][H:i:s])
				$tab_date3 = explode(':', $tab_date2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
				$date = date("Y-m-d H:i:s", mktime($tab_date3[0], $tab_date3[1], $tab_date3[2], $tab_date[1]+$mois_ajout, $tab_date2[0],  $tab_date[0]));
				$date_formatee = date("YmdHis", mktime($tab_date3[0], $tab_date3[1], $tab_date3[2], $tab_date[1]+$mois_ajout, $tab_date2[0],  $tab_date[0]));
				if(!$return_date_formatee){
					return $date;
				}else{
					return $date_formatee;
				}
			}
		}
	}
	
	/**
    * @desc Vérifie que la date fournit est bien au format voulu.
    * @param date $date : Date que l'on souhaite vérifier.
	* @param string $format_voulu : Format de la date, en ou fr.	
    * @return date
    */
	public function fx_verif_date($date, $format_voulu = "fr", $datetime = false){
		# On remplace toutes les "/" en "-".
		$this->date = str_replace("/", "-", $date);
		
		$datetime_fr= "/\d{2}\-\d{2}\-\d{4} \d{2}:\d{2}:\d{2}/";
		$datetime_en= "/\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}/";
		
		# On vérifie que le format de $date recu est bien une date.
		$date_fr = "/^\d{2}\-\d{2}\-\d{4}$/";
		$date_en = "/^\d{4}\-\d{2}\-\d{2}$/";
		
		if(!$datetime){
			# Si on souhaite un format date (Y-m-d ou d-m-Y) en sortie.
			if($format_voulu == "fr"){
				if(preg_match($date_fr, $this->date)){
					return true;
				}else{
					return false;
				}
			}else{
				if(preg_match($date_en, $this->date)){
					return true;
				}else{
					return false;
				}
			}
		}else{
			# Si on souhaite un format datetime (Y-m-d H:i:s ou d-m-Y H:i:s) en sortie.
			if($format_voulu == "fr"){
				if(preg_match($datetime_fr, $this->date)){
					return true;
				}else{
					return false;
				}
			}else{
				if(preg_match($datetime_en, $this->date)){
					return true;
				}else{
					return false;
				}
			}
			
		}
	}
	
	/**
	* @author  Ambroise Dhenain
	* @since [Création] 15 septembre 2011
	* @since [Modification] 07 octobre 2011
	* 
	* @desc Transforme la date de départ en lui rajoutant du temps.
	*		Permet de passer du format en->fr ou fr->en
	*		Gère les date et datetime
	*		Peut renvoyer une date formatée permettant d'effectuer des opérations d'égalité entre date. (<, >, =)
	*
	* @return Une date ou datetime.
	*
	*
	*/
	public function fx_ajouter_date($date, $datetime = false, $return_date_formatee = false, $format_fournit = "en", $format_voulu = "en", $jour = 0, $mois = 0, $annee = 0, $heure = 0, $minute = 0, $seconde = 0){
		# On remplace toutes les "/" en "-".
		$this->date = str_replace("/", "-", $date);
		
		if($datetime){
			if($format_fournit == 'en'){
				if($format_voulu == 'en'){
					$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([Y][M][D + H:i:s])	
					$tab_date2 = explode(' ', $tab_date[2]);# On récupère le reste de la date dans un tableau à deux cases ([D][H:i:s])
					$tab_date3 = explode(':', $tab_date2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
					
					if($return_date_formatee){
						# On retourne une date formatée.
						$this->date = date("YmdHis", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date2[0]+$jour,  $tab_date[0]+$annee));
						return $this->date;
					}else{
						# on retourne une date affichable.
						$this->date = date("Y-m-d H:i:s", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date2[0]+$jour,  $tab_date[0]+$annee));
						return $this->date;
					}
				}else if($format_voulu == 'fr'){
					$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([Y][M][D + H:i:s])	
					$tab_date2 = explode(' ', $tab_date[2]);# On récupère le reste de la date dans un tableau à deux cases ([D][H:i:s])
					$tab_date3 = explode(':', $tab_date2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
					if($return_date_formatee){
						# On retourne une date formatée.
						$this->date = date("dmYHis", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date2[0]+$jour,  $tab_date[0]+$annee));
						return $this->date;
					}else{
						# on retourne une date affichable.
						$this->date = date("d-m-Y H:i:s", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date2[0]+$jour,  $tab_date[0]+$annee));
						return $this->date;
					}
				}else{
					return "Erreur: Format voulu invalide. (arg 4)";
				}
			}else if($format_fournit == 'fr'){
				if($format_voulu == 'en'){
					$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([D][M][Y + H:i:s])	
					$tab_date2 = explode(' ', $tab_date[2]);# On récupère le reste de la date dans un tableau à deux cases ([Y][H:i:s])
					$tab_date3 = explode(':', $tab_date2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
					
					if($return_date_formatee){
						# On retourne une date formatée.
						$this->date = date("YmdHis", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date[0]+$jour,  $tab_date2[0]+$annee));
						return $this->date;
					}else{
						# on retourne une date affichable.
						$this->date = date("Y-m-d H:i:s", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date[0]+$jour,  $tab_date2[0]+$annee));
						return $this->date;
					}
				}else if($format_voulu == 'fr'){
					$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([D][M][Y + H:i:s])	
					$tab_date2 = explode(' ', $tab_date[2]);# On récupère le reste de la date dans un tableau à deux cases ([Y][H:i:s])
					$tab_date3 = explode(':', $tab_date2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
					
					if($return_date_formatee){
						# On retourne une date formatée.
						$this->date = date("YmdHis", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date[0]+$jour,  $tab_date2[0]+$annee));
						return $this->date;
					}else{
						# on retourne une date affichable.
						$this->date = date("d-m-Y H:i:s", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date[0]+$jour,  $tab_date2[0]+$annee));
						return $this->date;
					}
				}else{
					return "Erreur: Format voulu invalide. (arg 4)";
				}
			}else{
				return "Erreur: Format fournit invalide. (arg 3)";
			}
		}else{
			if($format_fournit == 'en'){
				if($format_voulu == 'en'){
				
				}else if($format_voulu == 'fr'){
					$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([Y][m][d])
					if($return_date_formatee){
						# On retourne une date formatée.
						$this->date = date("Ymd", mktime(0, 0, 0, $tab_date[1]+$mois, $tab_date[2]+$jour,  $tab_date[0]+$annee));
						return $this->date;
					}else{
						# on retourne une date affichable.
						$this->date = date("d-m-Y", mktime(0, 0, 0, $tab_date[1]+$mois, $tab_date[2]+$jour,  $tab_date[0]+$annee));
						return $this->date;
					}
				}else{
					return "Erreur: Format voulu invalide. (arg 4)";
				}
			}else if($format_fournit == 'fr'){
				if($format_voulu == 'en'){
					$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([d][m][Y])
					if($return_date_formatee){
						# On retourne une date formatée.
						$this->date = date("Ymd", mktime(0, 0, 0, $tab_date[1]+$mois, $tab_date[0]+$jour,  $tab_date[2]+$annee));
						return $this->date;
					}else{
						# on retourne une date affichable.
						$this->date = date("Y-m-d", mktime(0, 0, 0, $tab_date[1]+$mois, $tab_date[0]+$jour,  $tab_date[2]+$annee));
						return $this->date;
					}
					
				}else if($format_voulu == 'fr'){
					
				}else{
					return "Erreur: Format voulu invalide. (arg 4)";
				}
			}else{
				return "Erreur: Format fournit invalide. (arg 3)";
			}
		}
		
	}
	
	/**
	* @author  Ambroise Dhenain
	* @since [Création] 07 octobre 2011
	* @since [Modification] 07 octobre 2011
	* 
	* @desc Formatte un datetime de manière à ce qu'il soit affichable.
	*		Peut supprimer les heures, minutes, secondes.
	*
	* @return Une date ou datetime.
	* @return false si la date n'est pas valide
	*
	*/
	public function fx_formatter_heure($date, $datetime = true, $format_fournit='en', $supprimer_Hms = false, $ajouter_A = true, $return_format_Hhm = true){
		# On remplace toutes les "/" en "-".
		$this->date = str_replace("/", "-", $date);
		
		if(!self::fx_verif_date($this->date, $format_fournit, $datetime)){
			return false;
		}
		
		if($datetime){
			if($supprimer_Hms){
				$this->tab_date = split(' ', $this->date);
				$this->date = $this->tab_date[0];
				
				return $this->date;
			}else if($ajouter_A && $return_format_Hhm){
				$this->date = str_replace(' ', ' à ', $this->date);
				$this->date = substr(str_replace(':', 'h', $this->date), 0, -3);
				
				return $this->date;
			}else if($return_format_Hhm){
				$this->date = substr(str_replace(':', 'h', $this->date), 0, -3);
				
				return $this->date;
			}else if($ajouter_A){
				$this->date = str_replace(' ', ' à ', $this->date);
				
				return $this->date;
			}
		}else{
		
		}
	}
	
}
?>