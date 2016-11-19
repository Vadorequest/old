<?php
class VIEW_commentaire{

	private $ID_COMMENTAIRE;
	private $ID_PERSONNE;
	private $ID_NOUVEAUTE;
	private $CONTENU;
	private $DATE_CREATION;
	private $VISIBLE;

	private $sql;

	public function __construct() {
		$this->sql = "";
		$this->ID_COMMENTAIRE = "";
		$this->ID_PERSONNE = "";
		$this->ID_NOUVEAUTE = "";
		$this->CONTENU = "";
		$this->DATE_CREATION = "";
		$this->VISIBLE = "";
	}	
	
	public function SELECT_commentaires_by_ID_NOUVEAUTE($oMSG){
		$this->ID_NOUVEAUTE = $oMSG->getData("ID_NOUVEAUTE");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$debut_affichage = $oMSG->getData('debut_affichage');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		
		$this->sql = "SELECT commentaire.ID_COMMENTAIRE, commentaire.ID_PERSONNE, commentaire.ID_NOUVEAUTE, commentaire.CONTENU, commentaire.DATE_CREATION, ".
		"commentaire.VISIBLE, personne.PSEUDO ".
		"FROM commentaire LEFT OUTER JOIN personne ON personne.ID_PERSONNE = commentaire.ID_PERSONNE ".
		"WHERE commentaire.ID_NOUVEAUTE=:ID_NOUVEAUTE AND commentaire.VISIBLE=:VISIBLE ".
		"ORDER BY commentaire.DATE_CREATION DESC ".
		"LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(
				":ID_NOUVEAUTE"=>$this->ID_NOUVEAUTE,
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}