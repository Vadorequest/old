<?php
class MPG_message{

	private $ID_MESSAGE;
	private $TITRE;
	private $CONTENU;
	private $DATE_ENVOI;
	private $EXPEDITEUR;
	private $DESTINATAIRE;
	private $TYPE_MESSAGE;
	private $VISIBLE;
	
	public function __construct(){
		$this->ID_MESSAGE="";
		$this->TITRE="";
		$this->CONTENU="";
		$this->DATE_ENVOI="";
		$this->EXPEDITEUR="";
		$this->DESTINATAIRE="";
		$this->TYPE_MESSAGE="";
		$this->VISIBLE="";
	
	}
	
	// ----------------------------------------------------- INSERT ---------------------------------------------------------
	
	public function INSERT($oMSG){
		$this->TITRE = $oMSG->getData('TITRE');
		$this->CONTENU = $oMSG->getData('CONTENU');
		$this->DATE_ENVOI = $oMSG->getData('DATE_ENVOI');
		$this->EXPEDITEUR = $oMSG->getData('EXPEDITEUR');
		$this->DESTINATAIRE = $oMSG->getData('DESTINATAIRE');
		$this->TYPE_MESSAGE = $oMSG->getData('TYPE_MESSAGE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
	
		$this->sql = "INSERT INTO message (TITRE, CONTENU, DATE_ENVOI, EXPEDITEUR, DESTINATAIRE, TYPE_MESSAGE, VISIBLE) ".
		"VALUES (:TITRE, :CONTENU, :DATE_ENVOI, :EXPEDITEUR, :DESTINATAIRE, :TYPE_MESSAGE, :VISIBLE);";
		
		$params = array(
					':TITRE'=>$this->TITRE,
					':CONTENU'=>$this->CONTENU,
					':DATE_ENVOI'=>$this->DATE_ENVOI,
					':EXPEDITEUR'=>$this->EXPEDITEUR,
					':DESTINATAIRE'=>$this->DESTINATAIRE,
					':TYPE_MESSAGE'=>$this->TYPE_MESSAGE,
					':VISIBLE'=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
}