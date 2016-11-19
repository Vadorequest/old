<?php
class MPG_message_personne{

	private $ID_MESSAGE;
	private $ID_PERSONNE;
	private $STATUT_MESSAGE;
	private $DATE_LECTURE;
	private $DATE_REPONSE;

	
	public function __construct(){
		$this->ID_MESSAGE="";
		$this->ID_PERSONNE="";
		$this->STATUT_MESSAGE="";
		$this->DATE_LECTURE="";
		$this->DATE_REPONSE="";	
	}
	
	// ----------------------------------------------------- SELECT -----------------------------------------------
	
	public function SELECT_COUNT_message_by_ID_MESSAGE_et_ID_PERSONNE($oMSG){
		$this->ID_MESSAGE = $oMSG->getData('ID_MESSAGE');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		
		$this->sql = "SELECT COUNT(ID_MESSAGE) as nb_message FROM message_personne WHERE ID_MESSAGE=:ID_MESSAGE AND ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(
					':ID_MESSAGE'=>$this->ID_MESSAGE,
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ----------------------------------------------------- INSERT -----------------------------------------------
	
	public function INSERT($oMSG){
		$this->ID_MESSAGE = $oMSG->getData('ID_MESSAGE');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		
		$this->sql = "INSERT INTO message_personne (ID_MESSAGE, ID_PERSONNE, STATUT_MESSAGE) ".
		"VALUES (:ID_MESSAGE, :ID_PERSONNE, :STATUT_MESSAGE);";
		
		$params = array(
					':ID_MESSAGE'=>$this->ID_MESSAGE,
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ----------------------------------------------------- UPDATE -----------------------------------------------
	
	public function UPDATE_message_lu_by_ID_MESSAGE_et_ID_PERSONNE($oMSG){
		$this->ID_MESSAGE = $oMSG->getData('ID_MESSAGE');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->DATE_LECTURE = $oMSG->getData('DATE_LECTURE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		
		$this->sql = "UPDATE message_personne SET DATE_LECTURE=:DATE_LECTURE, STATUT_MESSAGE=:STATUT_MESSAGE WHERE ID_MESSAGE=:ID_MESSAGE ".
		"AND ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(
					':ID_MESSAGE'=>$this->ID_MESSAGE,
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					':DATE_LECTURE'=>$this->DATE_LECTURE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UPDATE_message_supprime_by_ID_MESSAGE_et_ID_PERSONNE($oMSG){
		$this->ID_MESSAGE = $oMSG->getData('ID_MESSAGE');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		
		$this->sql = "UPDATE message_personne SET STATUT_MESSAGE=:STATUT_MESSAGE WHERE ID_MESSAGE=:ID_MESSAGE AND ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(
					':ID_MESSAGE'=>$this->ID_MESSAGE,
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
}