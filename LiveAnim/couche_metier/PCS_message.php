<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_message.php';
require_once 'couche_metier/MPG_message_personne.php';
require_once 'couche_metier/VIEW_message.php';

class PCS_message{

	private $oCAD;
	private $oMPG_message;
	private $oMPG_departement;
	private $oVIEW_message;
	
	public function __construct(){
		$this->oCAD = new CAD();
		$this->oMPG_message = new MPG_message();
		$this->oMPG_message_personne = new MPG_message_personne();
		$this->oVIEW_message = new VIEW_message();
	
	}
	// ---------------------------------------------- GetRow --------------------------------------------

	// ---------------------- MPG_message_personne ------------------
	
	public function fx_compter_message_by_ID_MESSAGE_et_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_message_personne->SELECT_COUNT_message_by_ID_MESSAGE_et_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	// ------------------------ VIEW_message -----------------------

	public function fx_compter_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_message->SELECT_COUNT_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_message->SELECT_min_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_message->SELECT_COUNT_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_message->SELECT_min_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_message_by_ID_MESSAGE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_message->SELECT_message_by_ID_MESSAGE($oMSG));
		
		return $oMSG;
	}
	
	// ---------------------------------------------- ActionRows --------------------------------------------
	
	// ---------------------- MPG_message_personne ------------------

	public function fx_creer_message($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_message->INSERT($oMSG), true);# Récupération de l'ID crée.
		
		return $oMSG;
	}
	// ------------------------- MPG_message_personne -----------------
	
	public function fx_lier_message($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_message_personne->INSERT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_message_lu($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_message_personne->UPDATE_message_lu_by_ID_MESSAGE_et_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_supprimer_message($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_message_personne->UPDATE_message_supprime_by_ID_MESSAGE_et_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}

}
?>