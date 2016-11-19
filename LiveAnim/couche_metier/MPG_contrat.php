<?php
class MPG_contrat{

	private $ID_CONTRAT;
	private $ID_ANNONCE;
	private $DATE_CONTRAT;
	private $STATUT_CONTRAT;
	private $URL_CONTRAT_PDF;
	private $DATE_EVALUATION;
	private $DESCRIPTION;
	private $DATE_DEBUT;
	private $DATE_FIN;
	private $PRIX;
	private $GOLDLIVE;
	private $DESTINATAIRE;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_CONTRAT = "";
	$this->ID_ANNONCE = "";
	$this->DATE_CONTRAT = "";
	$this->STATUT_CONTRAT = "";
	$this->URL_CONTRAT_PDF = "";
	$this->DATE_EVALUATION = "";
	$this->DESCRIPTION = "";
	$this->DATE_DEBUT = "";
	$this->DATE_FIN = "";
	$this->PRIX = "";
	$this->GOLDLIVE = "";
	$this->DESTINATAIRE = "";
	}
	
	
	public function SELECT_COUNT_ID_CONTRAT_by_ID_ANNONCE($oMSG){
		$this->ID_ANNONCE = $oMSG->getData('ID_ANNONCE');
	
		$this->sql = "SELECT COUNT(ID_CONTRAT) as nb_contrat FROM contrat WHERE ID_ANNONCE=:ID_ANNONCE;";
		
		$params = array(  
					':ID_ANNONCE' =>$this->ID_ANNONCE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_CONTRAT_by_ID_CONTRAT_et_DESTINATAIRE($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');
		$this->DESTINATAIRE = $oMSG->getData('DESTINATAIRE');
	
		$this->sql = "SELECT COUNT(ID_CONTRAT) as nb_contrat FROM contrat WHERE ID_CONTRAT=:ID_CONTRAT AND DESTINATAIRE=:DESTINATAIRE;";
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					':DESTINATAIRE' =>$this->DESTINATAIRE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
		
	public function SELECT_COUNT_by_STATUT($oMSG){
		$this->STATUT_CONTRAT = $oMSG->getData('STATUT_CONTRAT');
	
		$this->sql = "SELECT COUNT(ID_CONTRAT) as nb_contrat FROM contrat WHERE STATUT_CONTRAT=:STATUT_CONTRAT;";
		
		$params = array(  
					':STATUT_CONTRAT' =>$this->STATUT_CONTRAT,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
		
	public function SELECT_COUNT_all($oMSG){
	
		$this->sql = "SELECT COUNT(ID_CONTRAT) as nb_contrat FROM contrat;";
		
		$params = array(  
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// --------------------------------------------- INSERT -----------------------------------------
	
	public function INSERT($oMSG){
		$this->ID_ANNONCE = $oMSG->getData('ID_ANNONCE');
		$this->DATE_CONTRAT = $oMSG->getData('DATE_CONTRAT');
		$this->DATE_DEBUT = $oMSG->getData('DATE_DEBUT');
		$this->DATE_FIN = $oMSG->getData('DATE_FIN');
		$this->PRIX = $oMSG->getData('PRIX');
		$this->DESCRIPTION = $oMSG->getData('DESCRIPTION');
		$this->STATUT_CONTRAT = $oMSG->getData('STATUT_CONTRAT');
		$this->DESTINATAIRE = $oMSG->getData('DESTINATAIRE');
	
		$this->sql = "INSERT INTO contrat (ID_ANNONCE, DATE_CONTRAT, STATUT_CONTRAT, DESCRIPTION, DATE_DEBUT, DATE_FIN, PRIX, DESTINATAIRE) ".
		"VALUES (:ID_ANNONCE, :DATE_CONTRAT, :STATUT_CONTRAT, :DESCRIPTION, :DATE_DEBUT, :DATE_FIN, :PRIX, :DESTINATAIRE);";
		
		$params = array(  
					':ID_ANNONCE' =>$this->ID_ANNONCE,		
					':DATE_CONTRAT' =>$this->DATE_CONTRAT,		
					':DATE_DEBUT' =>$this->DATE_DEBUT,		
					':DATE_FIN' =>$this->DATE_FIN,		
					':PRIX' =>$this->PRIX,		
					':DESCRIPTION' =>$this->DESCRIPTION,		
					':STATUT_CONTRAT' =>$this->STATUT_CONTRAT,		
					':DESTINATAIRE' =>$this->DESTINATAIRE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// --------------------------------------------- UPDATE -----------------------------------------
	
	public function UPDATE_refus($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');
		$this->DATE_DEBUT = $oMSG->getData('DATE_DEBUT');
		$this->DATE_FIN = $oMSG->getData('DATE_FIN');
		$this->PRIX = $oMSG->getData('PRIX');
		$this->DESCRIPTION = $oMSG->getData('DESCRIPTION');
		$this->STATUT_CONTRAT = $oMSG->getData('STATUT_CONTRAT');
		$this->DESTINATAIRE = $oMSG->getData('DESTINATAIRE');
	
		$this->sql = "UPDATE contrat SET DATE_DEBUT=:DATE_DEBUT, DATE_FIN=:DATE_FIN, PRIX=:PRIX, DESCRIPTION=:DESCRIPTION, DESTINATAIRE=:DESTINATAIRE, ".
		"STATUT_CONTRAT=:STATUT_CONTRAT WHERE ID_CONTRAT=:ID_CONTRAT;";
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					':DATE_DEBUT' =>$this->DATE_DEBUT,		
					':DATE_FIN' =>$this->DATE_FIN,		
					':PRIX' =>$this->PRIX,		
					':DESCRIPTION' =>$this->DESCRIPTION,		
					':STATUT_CONTRAT' =>$this->STATUT_CONTRAT,		
					':DESTINATAIRE' =>$this->DESTINATAIRE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UPDATE_statut_by_ID_CONTRAT($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');
		$this->STATUT_CONTRAT = $oMSG->getData('STATUT_CONTRAT');
	
		$this->sql = "UPDATE contrat SET STATUT_CONTRAT=:STATUT_CONTRAT WHERE ID_CONTRAT=:ID_CONTRAT;";
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					':STATUT_CONTRAT' =>$this->STATUT_CONTRAT,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UPDATE_validation($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');
		$this->STATUT_CONTRAT = $oMSG->getData('STATUT_CONTRAT');
		$this->URL_CONTRAT_PDF = $oMSG->getData('URL_CONTRAT_PDF');
	
		$this->sql = "UPDATE contrat SET STATUT_CONTRAT=:STATUT_CONTRAT, URL_CONTRAT_PDF=:URL_CONTRAT_PDF WHERE ID_CONTRAT=:ID_CONTRAT;";
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					':STATUT_CONTRAT' =>$this->STATUT_CONTRAT,		
					':URL_CONTRAT_PDF' =>$this->URL_CONTRAT_PDF,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UPDATE_DATE_EVALUATION_by_ID_CONTRAT($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');
		$this->DATE_EVALUATION = $oMSG->getData('DATE_EVALUATION');
	
		$this->sql = "UPDATE contrat SET DATE_EVALUATION=:DATE_EVALUATION WHERE ID_CONTRAT=:ID_CONTRAT;";
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					':DATE_EVALUATION' =>$this->DATE_EVALUATION,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
		
}