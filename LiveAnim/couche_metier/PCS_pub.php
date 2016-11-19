<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_pub.php';

class PCS_pub{

	private $oCAD;
	private $oMPG_pub;
	
	# Définition des constantes de classe.
	const POSITION_1 = "Sous le slider";
	const POSITION_2 = "Tout en bas";

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_pub = new MPG_pub();
	}
	
	public function fx_compter_toutes_pubs($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pub->SELECT_COUNT_all_pubs($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_pubs_by_POSITION($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pub->SELECT_COUNT_pubs_by_POSITION($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_toutes_pubs($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pub->SELECT_all_pubs($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_pubs_by_POSITION($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pub->SELECT_pubs_by_POSITION($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_pub_by_ID_PUB($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pub->SELECT_pub_by_ID_PUB($oMSG));
		
		return $oMSG;
	}

	// ------------------------------------------------------------- ActionRows  ------------------------------------------
		
	public function fx_ajouter_pub($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pub->INSERT($oMSG), true);
		
		return $oMSG;
	}	
	
	public function fx_modifier_pub($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pub->UPDATE($oMSG));
		
		return $oMSG;
	}	
	
	public function fx_supprimer_pub($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pub->DELETE($oMSG));
		
		return $oMSG;
	}
		
}