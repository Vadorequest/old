<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_types.php';
require_once 'couche_metier/MPG_famille_types.php';
require_once 'couche_metier/VIEW_types.php';

class PCS_types{

	private $oCAD;
	private $oMPG_types;
	private $oMPG_famille_types;
	private $oVIEW_types;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_types = new MPG_types();
		$this->oMPG_famille_types = new MPG_famille_types();
		$this->oVIEW_types = new VIEW_types();
	}
	
	public function fx_recuperer_tous_types_par_famille($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_types->SELECT_ALL_BY_ID_FAMILLE_TYPES($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_types_by_ID_TYPES($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_types->SELECT_COUNT_ID_TYPES_by_ID_TYPES($oMSG));
		
		return $oMSG;
	}
	
	// ------------------------------------- ActionRows -------------------------------------
	
	
	public function fx_ajouter_ID_TYPES($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_types->INSERT_ID_TYPES($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_ID_TYPES($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_types->UPDATE_ID_TYPES($oMSG));
		
		return $oMSG;
	}
	
	public function fx_supprimer_ID_TYPES($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_types->DELETE_ID_TYPES($oMSG));
		
		return $oMSG;
	}
}