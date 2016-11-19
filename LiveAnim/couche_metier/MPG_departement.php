<?php
class MPG_departement{

	private $ID_DEPARTEMENT;
	private $ID_REGION;
	private $NOM;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_DEPARTEMENT = "";
	$this->ID_REGION = "";
	$this->NOM = "";

	}
	
	
	public function SELECT_all($oMSG){
	
		$this->sql = "SELECT ID_DEPARTEMENT, ID_REGION, NOM FROM departement ORDER BY ID_DEPARTEMENT;";
		$params = array(    
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
}