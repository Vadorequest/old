<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_commentaire.php';
require_once 'couche_metier/VIEW_commentaire.php';

class PCS_commentaire{

	private $oCAD;
	private $oMPG_commentaire;
	private $oVIEW_commentaire;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_commentaire = new MPG_commentaire();
		$this->oVIEW_commentaire = new VIEW_commentaire();
	}
	
	public function fx_selectionner_commentaire_by_ID_COMMENTAIRE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_commentaire->SELECT_commentaire_by_ID_COMMENTAIRE($oMSG));
		
		return $oMSG;
	}
	
	
	public function fx_compter_tous_commentaires_by_ID_NOUVEAUTE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_commentaire->SELECT_COUNT_tous_commentaires_by_ID_NOUVEAUTE($oMSG));
		
		return $oMSG;
	}
	
	// --------------------- VIEW_commentaire -------------------
	
	public function fx_selectionner_commentaires_by_ID_NOUVEAUTE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_commentaire->SELECT_commentaires_by_ID_NOUVEAUTE($oMSG));
		
		return $oMSG;
	}
	
	// ------------------------------------------------------------- ActionRows  ------------------------------------------
	
	public function fx_ajouter_commentaire($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_commentaire->INSERT($oMSG), true);
		
		return $oMSG;
	}
	
	public function fx_cacher_commentaire($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_commentaire->UPDATE_chacher_commentaire($oMSG), true);
		
		return $oMSG;
	}
	
	
	
}
?>