<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_pack.php';
require_once 'couche_metier/MPG_pack_personne.php';
require_once 'couche_metier/VIEW_pack.php';

class PCS_pack{

	private $oCAD;
	private $oMPG_pack;
	private $oMPG_pack_personne;
	private $oVIEW_pack;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_pack = new MPG_pack();
		$this->oMPG_pack_personne = new MPG_pack_personne();
		$this->oVIEW_pack = new VIEW_pack();
	}
	
	public function fx_compter_tous_packs($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack->SELECT_COUNT_all_packs($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_packs_by_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack->SELECT_COUNT_PACK_by_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_tous_packs($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack->SELECT_all_minimum($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_tous_packs_by_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack->SELECT_all_by_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_pack_by_ID_PACK($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack->SELECT_all_by_ID_PACK($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_pack_by_TYPE_PACK_et_LIMIT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack->SELECT_ALL_by_TYPE_PACK_et_LIMIT($oMSG));
		
		return $oMSG;
	}

	// ---------------------- oMPG_pack_personne -------------
	
	public function fx_compter_nb_packs_achetes_by_ID_PACK($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack_personne->SELECT_COUNT_packs_achetes_by_ID_PACK($oMSG));
		
		return $oMSG;
	}
	
	
	
	// ---------------------- oVIEW_pack ---------------------
	
	public function fx_recuperer_pack_actif_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_pack->SELECT_ALL_by_DATE_ACHAT_et_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_dernier_pack_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_pack->SELECT_dernier_achat_by_DATE_ACHAT_et_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_tous_packs_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_pack->SELECT_COUNT_ID_PACK_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_packs_by_ID_PERSONNE_et_by_LIMIT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_pack->SELECT_ALL_by_ID_PERSONNE_et_by_LIMIT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_pack_by_ID_PERSONNE_et_DATE_ACHAT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_pack->SELECT_pack_by_ID_PERSONNE_et_DATE_ACHAT($oMSG));
		
		return $oMSG;
	}
		
	public function fx_calculer_couts_pack_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_pack->SELECT_SUM_couts_packs_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	// ------------------------------------------------------------- ActionRows  ------------------------------------------
	
	public function fx_creer_pack($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pack->INSERT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_pack($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pack->UPDATE($oMSG));
		
		return $oMSG;
	}
	
	// ---------------------- oMPG_pack_personne --------------
	
	public function fx_lier_pack_personne($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pack_personne->INSERT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_DATE_FIN_by_IDs($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pack_personne->UPDATE_DATE_FIN_by_IDs($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_DATE_DEBUT_et_DATE_FIN_by_IDs($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pack_personne->UPDATE_DATE_DEBUT_et_DATE_FIN_by_IDs($oMSG));
		
		return $oMSG;
	}
	
	public function fx_decrementer_NB_FICHES_VISITABLES_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pack_personne->UPDATE_decremente_NB_FICHES_VISITABLES_by_IDs($oMSG));
		
		return $oMSG;
	}
	
}