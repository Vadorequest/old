<?php
class VIEW_types{

	private $ID_TYPES;
	private $ID_FAMILLE_TYPES;
	
	private $sql;
	
	public function __construct() {
		$this->sql = "";
		$this->ID_TYPES = "";
		$this->ID_FAMILLE_TYPES = "";
	}
	
	public function SELECT_ALL_BY_ID_FAMILLE_TYPES($oMSG){
		$this->ID_FAMILLE_TYPES = $oMSG->getData("ID_FAMILLE_TYPES");
	
		$this->sql = "SELECT * FROM types NATURAL JOIN famille_types WHERE ID_FAMILLE_TYPES=:ID_FAMILLE_TYPES;";
		$params = array(    
					":ID_FAMILLE_TYPES"=>$this->ID_FAMILLE_TYPES,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}