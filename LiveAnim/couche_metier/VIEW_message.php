<?php
class VIEW_message{

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
	
	public function SELECT_COUNT_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
	
		$this->sql = "SELECT COUNT(message.ID_MESSAGE) as nb_message FROM message LEFT OUTER JOIN message_personne ".
		"ON message.ID_MESSAGE = message_personne.ID_MESSAGE WHERE ID_PERSONNE=:ID_PERSONNE AND STATUT_MESSAGE<>:STATUT_MESSAGE AND VISIBLE=:VISIBLE;";# /!\ <>
		
		$params = array(
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					':VISIBLE'=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_min_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
		$debut_affichage = $oMSG->getData('debut_affichage');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
	
		$this->sql = "SELECT message.ID_MESSAGE as ID_MESSAGE, TITRE, DATE_ENVOI, EXPEDITEUR, message_personne.ID_PERSONNE, STATUT_MESSAGE FROM message ".
		"LEFT OUTER JOIN message_personne ON message.ID_MESSAGE = message_personne.ID_MESSAGE ".
		"WHERE ID_PERSONNE=:ID_PERSONNE AND STATUT_MESSAGE<>:STATUT_MESSAGE AND VISIBLE=:VISIBLE ".
		"ORDER BY DATE_ENVOI DESC ".
		"LIMIT $debut_affichage, $nb_result_affiches;";# /!\ <> !
		
		$params = array(
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					':VISIBLE'=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
	
		$this->sql = "SELECT COUNT(message.ID_MESSAGE) as nb_message FROM message LEFT OUTER JOIN message_personne ".
		"ON message.ID_MESSAGE = message_personne.ID_MESSAGE WHERE ID_PERSONNE=:ID_PERSONNE AND STATUT_MESSAGE=:STATUT_MESSAGE AND VISIBLE=:VISIBLE;";
		
		$params = array(
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					':VISIBLE'=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_min_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
	
		$this->sql = "SELECT message.ID_MESSAGE as ID_MESSAGE, TITRE, DATE_ENVOI, EXPEDITEUR, ID_PERSONNE, STATUT_MESSAGE FROM message ".
		"LEFT OUTER JOIN message_personne ON message.ID_MESSAGE = message_personne.ID_MESSAGE ".
		"WHERE ID_PERSONNE=:ID_PERSONNE AND STATUT_MESSAGE=:STATUT_MESSAGE AND VISIBLE=:VISIBLE;";
		
		$params = array(
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					':VISIBLE'=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_message_by_ID_MESSAGE($oMSG){
		$this->ID_MESSAGE = $oMSG->getData('ID_MESSAGE');
	
		$this->sql = "SELECT message.ID_MESSAGE as ID_MESSAGE, TITRE, CONTENU, DATE_ENVOI, EXPEDITEUR, DESTINATAIRE, TYPE_MESSAGE, ID_PERSONNE, ".
		"STATUT_MESSAGE, DATE_LECTURE, DATE_REPONSE FROM message LEFT OUTER JOIN message_personne ON message.ID_MESSAGE = message_personne.ID_MESSAGE ".
		"WHERE message.ID_MESSAGE=:ID_MESSAGE;";
		
		$params = array(
					':ID_MESSAGE'=>$this->ID_MESSAGE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}