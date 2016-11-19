<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_slide.php';

class PCS_slide{

	private $oCAD;
	private $oMPG_slide;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_slide = new MPG_slide();
	}
	
	// ----------------------- MPG_slide --------------------------
	
	// ----------- GetRows -------------
	public function fx_recuperer_slides($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_slide->SELECT_slides($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_slides_by_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_slide->SELECT_slides_by_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_slide_by_ID_SLIDE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_slide->SELECT_slide_by_ID_SLIDE($oMSG));
		
		return $oMSG;
	}
	
	
	// ----------- ActionRows -------------
	public function fx_ajouter_slide($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_slide->INSERT($oMSG), true);
		
		return $oMSG;
	}
	
	public function fx_modifier_slide($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_slide->UPDATE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_slide_sauf_URL($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_slide->UPDATE_sauf_URL($oMSG));
		
		return $oMSG;
	}
}
?>