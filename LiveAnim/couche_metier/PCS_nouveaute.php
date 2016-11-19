<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_nouveaute.php';

class PCS_nouveaute{

	private $oCAD;
	private $oMPG_nouveaute;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_nouveaute = new MPG_nouveaute();
	}
	
	public function fx_compter_toutes_nouveautees_by_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_nouveaute->SELECT_COUNT_nouveaute_by_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_selectionner_nouveautees_BY_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_nouveaute->SELECT_nouveautees_by_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_selectionner_nouveautee_by_ID_NOUVEAUTE_and_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_nouveaute->SELECT_nouveautee_by_ID_NOUVEAUTE_and_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_selectionner_nouveautee_by_ID_NOUVEAUTE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_nouveaute->SELECT_nouveautee_by_ID_NOUVEAUTE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_toutes_nouveautees($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_nouveaute->SELECT_COUNT_toutes_nouveautes($oMSG));
		
		return $oMSG;
	}
	
	public function fx_selectionner_toutes_nouveautees($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_nouveaute->SELECT_all_nouveautees($oMSG));
		
		return $oMSG;
	}
	
	
	// ------------------------------------------------------------- ActionRows  ------------------------------------------
	
	public function fx_creer_nouveautee($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_nouveaute->INSERT($oMSG), true);
		
		return $oMSG;
	}
	public function fx_modifier_nouveautee($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_nouveaute->UPDATE($oMSG), true);
		
		return $oMSG;
	}
	
}
?>