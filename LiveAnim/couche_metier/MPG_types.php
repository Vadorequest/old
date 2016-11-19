<?php
class MPG_types{

	private $ID_TYPES;
	private $ID_FAMILLE_TYPES;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_TYPES = "";
	$this->ID_FAMILLE_TYPES = "";
	}
	
	
	public function SELECT_COUNT_ID_TYPES_by_ID_TYPES($oMSG){
		$this->ID_TYPES = $oMSG->getData("ID_TYPES");
	
		$this->sql = "SELECT COUNT(ID_TYPES) as nb_types FROM types WHERE ID_TYPES=:ID_TYPES;";
		$params = array(    
					":ID_TYPES"=>$this->ID_TYPES,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ------------------------------------- INSERT ----------------------------------
	
	public function INSERT_ID_TYPES($oMSG){
		$this->ID_TYPES = $oMSG->getData("ID_TYPES");
		$this->ID_FAMILLE_TYPES = $oMSG->getData("ID_FAMILLE_TYPES");

		$this->sql = "INSERT INTO types (ID_TYPES, ID_FAMILLE_TYPES) VALUES (:ID_TYPES, :ID_FAMILLE_TYPES);";
		$params = array(    
					":ID_TYPES"=>$this->ID_TYPES,
					":ID_FAMILLE_TYPES"=>$this->ID_FAMILLE_TYPES,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ------------------------------------- UDPATE ----------------------------------
	
	public function UPDATE_ID_TYPES($oMSG){
		$this->new_ID_TYPES = $oMSG->getData("new_ID_TYPES");
		$this->last_ID_TYPES = $oMSG->getData("last_ID_TYPES");

		$this->sql = "UPDATE types SET ID_TYPES=:new_ID_TYPES WHERE ID_TYPES=:last_ID_TYPES;";
		$params = array(    
					":new_ID_TYPES"=>$this->new_ID_TYPES,
					":last_ID_TYPES"=>$this->last_ID_TYPES,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ------------------------------------- DELETE ----------------------------------
	
	public function DELETE_ID_TYPES($oMSG){
		$this->ID_TYPES = $oMSG->getData("ID_TYPES");

		$this->sql = "DELETE FROM types WHERE ID_TYPES=:ID_TYPES;";
		$params = array(    
					":ID_TYPES"=>$this->ID_TYPES,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}