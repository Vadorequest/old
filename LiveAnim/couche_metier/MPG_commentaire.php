<?php
class MPG_commentaire{

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
	
	
	public function SELECT_commentaire_by_ID_COMMENTAIRE($oMSG){
		$this->ID_COMMENTAIRE = $oMSG->getData("ID_COMMENTAIRE");
		
		$this->sql = "SELECT ID_COMMENTAIRE, ID_PERSONNE, ID_NOUVEAUTE, CONTENU, DATE_CREATION, VISIBLE ".
		"FROM commentaire ".
		"WHERE ID_COMMENTAIRE=:ID_COMMENTAIRE ".
		"ORDER BY DATE_CREATION DESC;";
		
		$params = array(
				":ID_COMMENTAIRE"=>$this->ID_COMMENTAIRE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
	public function SELECT_COUNT_tous_commentaires_by_ID_NOUVEAUTE($oMSG){
		$this->ID_NOUVEAUTE = $oMSG->getData("ID_NOUVEAUTE");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "SELECT COUNT(ID_COMMENTAIRE) AS nb_commentaire FROM commentaire ".
		"WHERE ID_NOUVEAUTE=:ID_NOUVEAUTE AND VISIBLE=:VISIBLE;";
		
		$params = array(
				":ID_NOUVEAUTE"=>$this->ID_NOUVEAUTE,
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
	
	// -------------------------------------------------- INSERT -------------------------------------
	
	public function INSERT($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->ID_NOUVEAUTE = $oMSG->getData("ID_NOUVEAUTE");
		$this->CONTENU = $oMSG->getData("CONTENU");
		$this->DATE_CREATION = $oMSG->getData("DATE_CREATION");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "INSERT INTO commentaire (ID_PERSONNE, ID_NOUVEAUTE, CONTENU, DATE_CREATION, VISIBLE) ".
		"VALUES (:ID_PERSONNE, :ID_NOUVEAUTE, :CONTENU, :DATE_CREATION, :VISIBLE);";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
				":ID_NOUVEAUTE"=>$this->ID_NOUVEAUTE,
				":CONTENU"=>$this->CONTENU,
				":DATE_CREATION"=>$this->DATE_CREATION,
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	public function UPDATE_chacher_commentaire($oMSG){
		$this->ID_COMMENTAIRE = $oMSG->getData("ID_COMMENTAIRE");
		$this->ID_NOUVEAUTE = $oMSG->getData("ID_NOUVEAUTE");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "UPDATE commentaire SET VISIBLE=:VISIBLE WHERE ID_COMMENTAIRE=:ID_COMMENTAIRE AND ID_NOUVEAUTE=:ID_NOUVEAUTE";
		
		$params = array(
				":ID_COMMENTAIRE"=>$this->ID_COMMENTAIRE,
				":ID_NOUVEAUTE"=>$this->ID_NOUVEAUTE,
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
}
?>