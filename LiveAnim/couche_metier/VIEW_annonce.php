<?php
class VIEW_annonce{

	private $ID_ANNONCE;
	private $ID_DEPARTEMENT;
	private $VISIBLE;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_ANNONCE = "";
	$this->ID_DEPARTEMENT = "";
	$this->VISIBLE = "";
	}
	
	
	public function SELECT_min_by_VISIBLE($oMSG){
		$this->VISIBLE = $oMSG->getData('VISIBLE');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');
	
		$this->sql = "SELECT ID_ANNONCE, annonce.ID_PERSONNE AS ID_PERSONNE, TITRE, TYPE_ANNONCE, DATE_ANNONCE, GOLDLIVE, STATUT, PSEUDO ".
		"FROM personne RIGHT OUTER JOIN annonce ON personne.ID_PERSONNE=annonce.ID_PERSONNE WHERE annonce.VISIBLE=:VISIBLE ORDER BY STATUT, GOLDLIVE DESC, DATE_ANNONCE ".
		"LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(  
					':VISIBLE' =>$this->VISIBLE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_annonces_par_criteres($oMSG){
		$this->VISIBLE = $oMSG->getData('VISIBLE');
		$this->STATUT = $oMSG->getData('STATUT');
		$criteres = $oMSG->getData('criteres');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');
	
		$this->sql = 
		"SELECT annonce.ID_ANNONCE as ID_ANNONCE, annonce.TITRE as TITRE, annonce.TYPE_ANNONCE, annonce.DATE_ANNONCE, annonce.DATE_DEBUT, annonce.DATE_FIN, ".
		"annonce.BUDGET AS BUDGET, NB_CONVIVES, annonce.DESCRIPTION, annonce.CP, annonce.VILLE ".
		"FROM annonce LEFT OUTER JOIN contrat ON annonce.ID_ANNONCE = contrat.ID_ANNONCE ".
		"WHERE annonce.VISIBLE=:VISIBLE AND annonce.STATUT=:STATUT $criteres ".
		"GROUP BY annonce.ID_ANNONCE ".
		"ORDER BY annonce.GOLDLIVE DESC, annonce.DATE_DEBUT DESC ".
		"LIMIT $debut_affichage, $nb_result_affiches;";

		$params = array(  
					':VISIBLE' =>$this->VISIBLE,
					':STATUT' =>$this->STATUT,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_annonce_complete_by_ID_ANNONCE($oMSG){
		$this->ID_ANNONCE = $oMSG->getData('ID_ANNONCE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
	
		$this->sql = "SELECT annonce.ID_ANNONCE, annonce.ID_PERSONNE AS ID_PERSONNE, departement.ID_DEPARTEMENT AS ID_DEPARTEMENT, annonce.TITRE, ".
		"annonce.TYPE_ANNONCE, annonce.DATE_ANNONCE, annonce.GOLDLIVE, annonce.STATUT, annonce.DATE_DEBUT, annonce.DATE_FIN, ".
		"annonce.ARTISTES_RECHERCHES, annonce.BUDGET, annonce.NB_CONVIVES, annonce.DESCRIPTION, annonce.ADRESSE, annonce.VILLE, annonce.CP, ".
		"personne.PSEUDO, departement.NOM FROM annonce LEFT OUTER JOIN departement ON departement.ID_DEPARTEMENT=annonce.ID_DEPARTEMENT LEFT OUTER JOIN personne ".
		"ON personne.ID_PERSONNE = annonce.ID_PERSONNE WHERE annonce.VISIBLE=:VISIBLE AND annonce.ID_ANNONCE=:ID_ANNONCE";
		
		$params = array(  
					':ID_ANNONCE' =>$this->ID_ANNONCE,
					':VISIBLE' =>$this->VISIBLE,					
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_min($oMSG){
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');
		
		$this->sql = "SELECT ID_ANNONCE, annonce.ID_PERSONNE AS ID_PERSONNE, TITRE, TYPE_ANNONCE, DATE_ANNONCE, GOLDLIVE, STATUT, PSEUDO ".
		"FROM personne RIGHT OUTER JOIN annonce ON personne.ID_PERSONNE=annonce.ID_PERSONNE ".
		"GROUP BY annonce.ID_ANNONCE ".
		"ORDER BY annonce.DATE_ANNONCE DESC ";
		if(!empty($nb_result_affiches) && !empty($debut_affichage)){
			$this->sql.= "LIMIT $debut_affichage, $nb_result_affiches;";
		}
		
		$params = array(  
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_annonces_by_GOLDLIVE_et_ID_PERSONNE($oMSG){
		$this->GOLDLIVE = $oMSG->getData('GOLDLIVE');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		
		$this->sql = "SELECT COUNT(annonce.ID_ANNONCE) as nb_annonce ".
		"FROM personne RIGHT OUTER JOIN annonce ON personne.ID_PERSONNE=annonce.ID_PERSONNE ".
		"WHERE GOLDLIVE=:GOLDLIVE AND annonce.ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array( 
					':GOLDLIVE'=>$this->GOLDLIVE,		
					':ID_PERSONNE'=>$this->ID_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);

		return $oMSG;
	}
	
	public function SELECT_annonces_by_GOLDLIVE_et_ID_PERSONNE($oMSG){
		$this->GOLDLIVE = $oMSG->getData('GOLDLIVE');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');
		
		$this->sql = "SELECT ID_ANNONCE, annonce.ID_PERSONNE, TITRE, DATE_ANNONCE, STATUT ".
		"FROM personne RIGHT OUTER JOIN annonce ON personne.ID_PERSONNE=annonce.ID_PERSONNE ".
		"WHERE GOLDLIVE=:GOLDLIVE AND annonce.ID_PERSONNE=:ID_PERSONNE ".
		"GROUP BY annonce.ID_ANNONCE ".
		"ORDER BY annonce.DATE_ANNONCE DESC ".
		"LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array( 
					':GOLDLIVE'=>$this->GOLDLIVE,		
					':ID_PERSONNE'=>$this->ID_PERSONNE,		
					);

		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_SUM_couts_annonces_goldlive_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		
		$this->sql = "SELECT SUM(annonce.GOLDLIVE) as prix_total".
		"FROM personne RIGHT OUTER JOIN annonce ON personne.ID_PERSONNE=annonce.ID_PERSONNE ".
		"WHERE annonce.ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array( 
					':ID_PERSONNE'=>$this->ID_PERSONNE,		
					);

		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
}