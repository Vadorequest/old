<?php
class MPG_slide{

	private $ID_SLIDE;
	private $TITRE;
	private $URL;
	private $LIEN;
	private $CLASSE;
	private $ORDRE;
	private $ACCES;
	private $VISIBLE;

	private $sql;

	public function __construct() {
		$this->sql = "";
		$this->ID_SLIDE = "";
		$this->TITRE = "";
		$this->URL = "";
		$this->LIEN = "";
		$this->CLASSE = "";
		$this->ORDRE = "";
		$this->ACCES = "";
		$this->VISIBLE = "";
	}
	
	// ----------------------------------------------- SELECT -----------------------------------------------------
	
	public function SELECT_slides($oMSG){
		$this->sql = "SELECT ID_SLIDE, TITRE, URL, LIEN, CLASSE, ORDRE, ACCES, VISIBLE ".
		"FROM slide ".
		"ORDER BY ORDRE;";
		
		$params = array(
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_slides_by_VISIBLE($oMSG){
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "SELECT ID_SLIDE, TITRE, URL, LIEN, CLASSE, ORDRE, ACCES, VISIBLE ".
		"FROM slide ".
		"WHERE VISIBLE=:VISIBLE ".
		"ORDER BY ORDRE;";
		
		$params = array(
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_slide_by_ID_SLIDE($oMSG){
		$this->ID_SLIDE = $oMSG->getData("ID_SLIDE");
		
		$this->sql = "SELECT ID_SLIDE, TITRE, URL, LIEN, CLASSE, ORDRE, ACCES, VISIBLE ".
		"FROM slide ".
		"WHERE ID_SLIDE=:ID_SLIDE;";
		
		$params = array(
				":ID_SLIDE"=>$this->ID_SLIDE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ----------------------------------------------- INSERT -----------------------------------------------------

	public function INSERT($oMSG){
		$this->TITRE = $oMSG->getData("TITRE");
		$this->URL = $oMSG->getData("URL");
		$this->LIEN = $oMSG->getData("LIEN");
		$this->CLASSE = $oMSG->getData("CLASSE");
		$this->ORDRE = $oMSG->getData("ORDRE");
		$this->ACCES = $oMSG->getData("ACCES");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "INSERT INTO slide (TITRE, URL, LIEN, CLASSE, ORDRE, ACCES, VISIBLE) VALUES (:TITRE, :URL, :LIEN, :CLASSE, :ORDRE, :ACCES, :VISIBLE);";
		
		$params = array(
				":TITRE"=>$this->TITRE,
				":URL"=>$this->URL,
				":LIEN"=>$this->LIEN,
				":CLASSE"=>$this->CLASSE,
				":ORDRE"=>$this->ORDRE,
				":ACCES"=>$this->ACCES,
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ----------------------------------------------- UDPATE -----------------------------------------------------

	public function UPDATE($oMSG){
		$this->ID_SLIDE = $oMSG->getData("ID_SLIDE");
		$this->TITRE = $oMSG->getData("TITRE");
		$this->URL = $oMSG->getData("URL");
		$this->LIEN = $oMSG->getData("LIEN");
		$this->CLASSE = $oMSG->getData("CLASSE");
		$this->ORDRE = $oMSG->getData("ORDRE");
		$this->ACCES = $oMSG->getData("ACCES");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "UPDATE slide SET TITRE=:TITRE, URL=:URL, LIEN=:LIEN, CLASSE=:CLASSE, ORDRE=:ORDRE, ACCES=:ACCES, VISIBLE=:VISIBLE ".
		"WHERE ID_SLIDE=:ID_SLIDE;";
		
		$params = array(
				":ID_SLIDE"=>$this->ID_SLIDE,
				":TITRE"=>$this->TITRE,
				":URL"=>$this->URL,
				":LIEN"=>$this->LIEN,
				":CLASSE"=>$this->CLASSE,
				":ORDRE"=>$this->ORDRE,
				":ACCES"=>$this->ACCES,
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}	
	
	public function UPDATE_sauf_URL($oMSG){
		$this->ID_SLIDE = $oMSG->getData("ID_SLIDE");
		$this->TITRE = $oMSG->getData("TITRE");
		$this->LIEN = $oMSG->getData("LIEN");
		$this->CLASSE = $oMSG->getData("CLASSE");
		$this->ORDRE = $oMSG->getData("ORDRE");
		$this->ACCES = $oMSG->getData("ACCES");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "UPDATE slide SET TITRE=:TITRE, LIEN=:LIEN, CLASSE=:CLASSE, ORDRE=:ORDRE, ACCES=:ACCES, VISIBLE=:VISIBLE ".
		"WHERE ID_SLIDE=:ID_SLIDE;";
		
		$params = array(
				":ID_SLIDE"=>$this->ID_SLIDE,
				":TITRE"=>$this->TITRE,
				":LIEN"=>$this->LIEN,
				":CLASSE"=>$this->CLASSE,
				":ORDRE"=>$this->ORDRE,
				":ACCES"=>$this->ACCES,
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}	

}
?>