<?php
class MPG_pub{

	private $ID_PUB;
	private $TITRE;
	private $CONTENU;
	private $POSITION;

	private $sql;

	public function __construct() {
		$this->sql = "";
		$this->ID_PUB = "";
		$this->TITRE = "";
		$this->CONTENU = "";
		$this->POSITION = "";
	}
	
	// ----------------------------------------------- SELECT -----------------------------------------------------
	
	public function SELECT_COUNT_all_pubs($oMSG){
		$this->sql = "SELECT COUNT(ID_PUB) as nb_pubs ".
		"FROM pub;";
		
		$params = array(
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;

	}
	
	public function SELECT_COUNT_pubs_by_POSITION($oMSG){
		$this->POSITION = $oMSG->getData("POSITION");
	
		$this->sql = "SELECT COUNT(ID_PUB) as nb_pubs ".
		"FROM pub ".
		"WHERE POSITION=:POSITION;";
		
		$params = array(
				":POSITION"=>$this->POSITION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;

	}
	
	public function SELECT_all_pubs($oMSG){
	
		$this->sql = "SELECT ID_PUB, TITRE, CONTENU, POSITION ".
		"FROM pub ".
		"ORDER BY POSITION;";
		
		$params = array(
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;

	}
	
	public function SELECT_pubs_by_POSITION($oMSG){
		$this->POSITION = $oMSG->getData("POSITION");
	
		$this->sql = "SELECT ID_PUB, TITRE, CONTENU, POSITION ".
		"FROM pub ".
		"WHERE POSITION=:POSITION;";
		
		$params = array(
				":POSITION"=>$this->POSITION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;

	}
	
	public function SELECT_pub_by_ID_PUB($oMSG){
		$this->ID_PUB = $oMSG->getData("ID_PUB");
	
		$this->sql = "SELECT ID_PUB, TITRE, CONTENU, POSITION ".
		"FROM pub ".
		"WHERE ID_PUB=:ID_PUB;";
		
		$params = array(
				":ID_PUB"=>$this->ID_PUB,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;

	}
	
	// ----------------------------------------------- INSERT -----------------------------------------------------
	
	
	public function INSERT($oMSG){
		$this->ID_PUB = $oMSG->getData("ID_PUB");
		$this->TITRE = $oMSG->getData("TITRE");
		$this->CONTENU = $oMSG->getData("CONTENU");
		$this->POSITION = $oMSG->getData("POSITION");
	
		$this->sql = "INSERT INTO pub (ID_PUB, TITRE, CONTENU, POSITION) VALUES (:ID_PUB, :TITRE, :CONTENU, :POSITION);";
		
		$params = array(
				":ID_PUB"=>$this->ID_PUB,
				":TITRE"=>$this->TITRE,
				":CONTENU"=>$this->CONTENU,
				":POSITION"=>$this->POSITION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;

	}
	
	// ----------------------------------------------- UPDATE -----------------------------------------------------
	
	
	public function UPDATE($oMSG){
		$this->ID_PUB = $oMSG->getData("ID_PUB");
		$this->TITRE = $oMSG->getData("TITRE");
		$this->CONTENU = $oMSG->getData("CONTENU");
		$this->POSITION = $oMSG->getData("POSITION");
	
		$this->sql = "UPDATE pub SET TITRE=:TITRE, CONTENU=:CONTENU, POSITION=:POSITION WHERE ID_PUB=:ID_PUB;";
		
		$params = array(
				":ID_PUB"=>$this->ID_PUB,
				":TITRE"=>$this->TITRE,
				":CONTENU"=>$this->CONTENU,
				":POSITION"=>$this->POSITION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;

	}
	
	// ----------------------------------------------- DELETE -----------------------------------------------------
	
	
	public function DELETE($oMSG){
		$this->ID_PUB = $oMSG->getData("ID_PUB");
	
		$this->sql = "DELETE FROM pub WHERE ID_PUB=:ID_PUB;";
		
		$params = array(
				":ID_PUB"=>$this->ID_PUB,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;

	}
}