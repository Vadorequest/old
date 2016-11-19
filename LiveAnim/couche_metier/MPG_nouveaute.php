<?php
class MPG_nouveaute{

	private $ID_NOUVEAUTE;
	private $AUTEUR;
	private $TITRE;
	private $ENTETE;
	private $CONTENU;
	private $URL_PHOTO;
	private $DATE_CREATION;
	private $VISIBLE;

	private $sql;

	public function __construct() {
		$this->sql = "";
		$this->ID_NOUVEAUTE = "";
		$this->AUTEUR = "";
		$this->TITRE = "";
		$this->ENTETE = "";
		$this->CONTENU = "";
		$this->URL_PHOTO = "";
		$this->DATE_CREATION = "";
		$this->VISIBLE = "";
	}
	
	
	public function SELECT_COUNT_nouveaute_by_VISIBLE($oMSG){
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "SELECT COUNT(ID_NOUVEAUTE) AS nb_nouveautees FROM nouveaute WHERE VISIBLE=:VISIBLE;";
		
		$params = array(
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_nouveautees_by_VISIBLE($oMSG){
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$debut_affichage = $oMSG->getData('debut_affichage');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		
		$this->sql = "SELECT ID_NOUVEAUTE, AUTEUR, TITRE, ENTETE, CONTENU, URL_PHOTO, DATE_CREATION, VISIBLE ".
		"FROM nouveaute ".
		"WHERE VISIBLE=:VISIBLE ".
		"ORDER BY DATE_CREATION DESC ".
		"LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_nouveautee_by_ID_NOUVEAUTE_and_VISIBLE($oMSG){
		$this->ID_NOUVEAUTE = $oMSG->getData("ID_NOUVEAUTE");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "SELECT ID_NOUVEAUTE, AUTEUR, TITRE, ENTETE, CONTENU, URL_PHOTO, DATE_CREATION, VISIBLE ".
		"FROM nouveaute ".
		"WHERE ID_NOUVEAUTE=:ID_NOUVEAUTE AND VISIBLE=:VISIBLE;";
		
		$params = array(
				":ID_NOUVEAUTE"=>$this->ID_NOUVEAUTE,
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_nouveautee_by_ID_NOUVEAUTE($oMSG){
		$this->ID_NOUVEAUTE = $oMSG->getData("ID_NOUVEAUTE");
		
		$this->sql = "SELECT ID_NOUVEAUTE, AUTEUR, TITRE, ENTETE, CONTENU, URL_PHOTO, DATE_CREATION, VISIBLE ".
		"FROM nouveaute ".
		"WHERE ID_NOUVEAUTE=:ID_NOUVEAUTE;";
		
		$params = array(
				":ID_NOUVEAUTE"=>$this->ID_NOUVEAUTE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_toutes_nouveautes($oMSG){
		
		$this->sql = "SELECT COUNT(ID_NOUVEAUTE) AS nb_nouveautees FROM nouveaute;";
		
		$params = array(
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_nouveautees($oMSG){
		$debut_affichage = $oMSG->getData('debut_affichage');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		
		$this->sql = "SELECT ID_NOUVEAUTE, AUTEUR, TITRE, ENTETE, CONTENU, URL_PHOTO, DATE_CREATION, VISIBLE ".
		"FROM nouveaute ".
		"ORDER BY DATE_CREATION DESC ".
		"LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
	// -------------------------------------------------- INSERT -------------------------------------
	
	public function INSERT($oMSG){
		$this->AUTEUR = $oMSG->getData("AUTEUR");
		$this->TITRE = $oMSG->getData("TITRE");
		$this->ENTETE = $oMSG->getData("ENTETE");
		$this->CONTENU = $oMSG->getData("CONTENU");
		$this->URL_PHOTO = $oMSG->getData("URL_PHOTO");
		$this->DATE_CREATION = $oMSG->getData("DATE_CREATION");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "INSERT INTO nouveaute (AUTEUR, TITRE, ENTETE, CONTENU, URL_PHOTO, DATE_CREATION, VISIBLE) ".
		"VALUES (:AUTEUR, :TITRE, :ENTETE, :CONTENU, :URL_PHOTO, :DATE_CREATION, :VISIBLE);";
		
		$params = array(
				":AUTEUR"=>$this->AUTEUR,
				":TITRE"=>$this->TITRE,
				":ENTETE"=>$this->ENTETE,
				":CONTENU"=>$this->CONTENU,
				":URL_PHOTO"=>$this->URL_PHOTO,
				":DATE_CREATION"=>$this->DATE_CREATION,
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	public function UPDATE($oMSG){
		$this->ID_NOUVEAUTE = $oMSG->getData("ID_NOUVEAUTE");
		$this->AUTEUR = $oMSG->getData("AUTEUR");
		$this->TITRE = $oMSG->getData("TITRE");
		$this->ENTETE = $oMSG->getData("ENTETE");
		$this->CONTENU = $oMSG->getData("CONTENU");
		$this->URL_PHOTO = $oMSG->getData("URL_PHOTO");
		$this->DATE_CREATION = $oMSG->getData("DATE_CREATION");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "UPDATE nouveaute SET AUTEUR=:AUTEUR, TITRE=:TITRE, ENTETE=:ENTETE, CONTENU=:CONTENU, URL_PHOTO=:URL_PHOTO, ".
		"DATE_CREATION=:DATE_CREATION, VISIBLE=:VISIBLE ".
		"WHERE ID_NOUVEAUTE=:ID_NOUVEAUTE";
		
		$params = array(
				":ID_NOUVEAUTE"=>$this->ID_NOUVEAUTE,
				":AUTEUR"=>$this->AUTEUR,
				":TITRE"=>$this->TITRE,
				":ENTETE"=>$this->ENTETE,
				":CONTENU"=>$this->CONTENU,
				":URL_PHOTO"=>$this->URL_PHOTO,
				":DATE_CREATION"=>$this->DATE_CREATION,
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
}
?>