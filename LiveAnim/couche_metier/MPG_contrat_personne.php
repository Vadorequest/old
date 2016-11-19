<?php
class MPG_contrat_personne{

	private $ID_CONTRAT;
	private $ID_PERSONNE;

	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_CONTRAT = "";
	$this->ID_PERSONNE = "";

	}
	
	
	// --------------------------------------------- SELECT -----------------------------------------
	
	public function SELECT_COUNT_ID_CONTRAT_by_ID_CONTRAT_et_ID_PERSONNE($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
	
		$this->sql = "SELECT COUNT(ID_CONTRAT) as nb_contrat FROM contrat_personne WHERE ID_CONTRAT=:ID_CONTRAT AND ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					':ID_PERSONNE' =>$this->ID_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_destinataire_by_ID_CONTRAT_et_nonID_PERSONNE($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
	
		$this->sql = "SELECT ID_PERSONNE FROM contrat_personne WHERE ID_CONTRAT=:ID_CONTRAT AND ID_PERSONNE<>:ID_PERSONNE;";#/!\ <>
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					':ID_PERSONNE' =>$this->ID_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// --------------------------------------------- INSERT -----------------------------------------
	
	public function INSERT($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
	
		$this->sql = "INSERT INTO contrat_personne (ID_CONTRAT, ID_PERSONNE) VALUES (:ID_CONTRAT, :ID_PERSONNE);";
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					':ID_PERSONNE' =>$this->ID_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}