<?php
class MPG_evaluation{

	private $ID_EVALUATION;
	private $ID_CONTRAT;
	private $EVALUATION;
	private $COMMENTAIRE;
	private $TYPE_EVALUATION;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_EVALUATION = "";
	$this->ID_CONTRAT = "";
	$this->EVALUATION = "";
	$this->COMMENTAIRE = "";
	$this->TYPE_EVALUATION = "";
	}
	
	
	// ------------------------------------------------------- SELECT ---------------------------------------------------------
	
	
	
	public function SELECT_COUNT_ID_EVALUATION_by_ID_CONTRAT($oMSG){
		$this->ID_CONTRAT = $oMSG->getData("ID_CONTRAT");
	
		$this->sql = "SELECT COUNT(ID_EVALUATION) as nb_evaluation FROM evaluation WHERE ID_CONTRAT=:ID_CONTRAT;";
		$params = array(    
					":ID_CONTRAT"=>$this->ID_CONTRAT,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_evaluations_by_ID_CONTRAT($oMSG){
		$this->ID_CONTRAT = $oMSG->getData("ID_CONTRAT");
	
		$this->sql = "SELECT ID_EVALUATION, ID_CONTRAT, EVALUATION, COMMENTAIRE, TYPE_EVALUATION ".
		"FROM evaluation WHERE ID_CONTRAT=:ID_CONTRAT;";
		$params = array(    
					":ID_CONTRAT"=>$this->ID_CONTRAT,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);

		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_EVALUATION_by_ID_CONTRAT_et_TYPE_EVALUATION($oMSG){
		$this->ID_CONTRAT = $oMSG->getData("ID_CONTRAT");
		$this->TYPE_EVALUATION = $oMSG->getData("TYPE_EVALUATION");
	
		$this->sql = "SELECT COUNT(ID_EVALUATION) as nb_evaluation FROM evaluation WHERE ID_CONTRAT=:ID_CONTRAT AND TYPE_EVALUATION=:TYPE_EVALUATION;";
		$params = array(    
					":ID_CONTRAT"=>$this->ID_CONTRAT,
					":TYPE_EVALUATION"=>$this->TYPE_EVALUATION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_evaluations_by_ID_CONTRAT_et_TYPE_EVALUATION($oMSG){
		$this->ID_CONTRAT = $oMSG->getData("ID_CONTRAT");
		$this->TYPE_EVALUATION = $oMSG->getData("TYPE_EVALUATION");
	
		$this->sql = "SELECT ID_EVALUATION, ID_CONTRAT, EVALUATION, COMMENTAIRE, TYPE_EVALUATION ".
		"FROM evaluation WHERE ID_CONTRAT=:ID_CONTRAT AND TYPE_EVALUATION=:TYPE_EVALUATION;";
		$params = array(    
					":ID_CONTRAT"=>$this->ID_CONTRAT,
					":TYPE_EVALUATION"=>$this->TYPE_EVALUATION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
	// ------------------------------------------------------- INSERT ---------------------------------------------------------
	
	
	public function INSERT($oMSG){
		$this->ID_CONTRAT = $oMSG->getData("ID_CONTRAT");
		$this->EVALUATION = $oMSG->getData("EVALUATION");
		$this->COMMENTAIRE = $oMSG->getData("COMMENTAIRE");
		$this->TYPE_EVALUATION = $oMSG->getData("TYPE_EVALUATION");
	
		$this->sql = "INSERT INTO evaluation (ID_CONTRAT, EVALUATION, COMMENTAIRE, TYPE_EVALUATION) ".
		"VALUES (:ID_CONTRAT, :EVALUATION, :COMMENTAIRE, :TYPE_EVALUATION);";
		$params = array(    
					":ID_CONTRAT"=>$this->ID_CONTRAT,
					":EVALUATION"=>$this->EVALUATION,
					":COMMENTAIRE"=>$this->COMMENTAIRE,
					":TYPE_EVALUATION"=>$this->TYPE_EVALUATION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
	// ------------------------------------------------------- UPDATE ---------------------------------------------------------
	
	
	public function UPDATE($oMSG){
		$this->ID_CONTRAT = $oMSG->getData("ID_CONTRAT");
		$this->EVALUATION = $oMSG->getData("EVALUATION");
		$this->COMMENTAIRE = $oMSG->getData("COMMENTAIRE");
		$this->TYPE_EVALUATION = $oMSG->getData("TYPE_EVALUATION");
	
		$this->sql = "UPDATE evaluation SET EVALUATION=:EVALUATION, COMMENTAIRE=:COMMENTAIRE ".
		"WHERE ID_CONTRAT=:ID_CONTRAT AND TYPE_EVALUATION=:TYPE_EVALUATION;";
		$params = array(    
					":ID_CONTRAT"=>$this->ID_CONTRAT,
					":EVALUATION"=>$this->EVALUATION,
					":COMMENTAIRE"=>$this->COMMENTAIRE,
					":TYPE_EVALUATION"=>$this->TYPE_EVALUATION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}