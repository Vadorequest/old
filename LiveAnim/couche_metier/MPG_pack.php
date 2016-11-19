<?php
class MPG_pack{

	private $ID_PACK;
	private $NOM;
	private $DESCRIPTION;
	private $TYPE_PACK;
	private $PRIX_BASE;
	private $DUREE;
	private $SOUMIS_REDUCTIONS_PARRAINAGE;
	private $GAIN_PARRAINAGE_MAX;
	private $REDUCTION;
	private $VISIBLE;
	private $CV_VISIBILITE;
	private $CV_ACCESSIBLE;
	private $NB_FICHES_VISITABLES;
	private $CV_VIDEO_ACCESSIBLE;
	private $ALERTE_NON_DISPONIBILITE;
	private $NB_DEPARTEMENTS_ALERTE;
	private $PARRAINAGE_ACTIVE;
	private $PREVISUALISATION_FICHES;
	private $CONTRATS_PDF;
	private $SUIVI;
	private $PUBS;

	private $sql;

	public function __construct() {
		$this->sql = "";
		$this->ID_PACK = "";
		$this->NOM = "";
		$this->DESCRIPTION = "";
		$this->TYPE_PACK = "";
		$this->PRIX_BASE = "";
		$this->DUREE = "";
		$this->SOUMIS_REDUCTIONS_PARRAINAGE = "";
		$this->GAIN_PARRAINAGE_MAX = "";
		$this->REDUCTION = "";
		$this->VISIBLE = "";
		$this->CV_VISIBILITE = "";
		$this->CV_ACCESSIBLE = "";
		$this->NB_FICHES_VISITABLES = "";
		$this->CV_VIDEO_ACCESSIBLE = "";
		$this->ALERTE_NON_DISPONIBILITE = "";
		$this->NB_DEPARTEMENTS_ALERTE = "";
		$this->PARRAINAGE_ACTIVE = "";
		$this->PREVISUALISATION_FICHES = "";
		$this->CONTRATS_PDF = "";
		$this->SUIVI = "";
		$this->PUBS = "";
	}
	
	public function SELECT_COUNT_all_packs($oMSG){

		$this->sql = "SELECT COUNT(ID_PACK) AS nb_packs from pack;";
		
		$params = array(
		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_PACK_by_VISIBLE($oMSG){
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "SELECT COUNT(ID_PACK) AS nb_packs from pack WHERE VISIBLE=:VISIBLE;";
		
		$params = array(
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_by_ID_PACK($oMSG){		
		$this->ID_PACK = $oMSG->getData("ID_PACK");
		
		$this->sql = "SELECT * from pack WHERE ID_PACK=:ID_PACK;";
		
		$params = array(
				":ID_PACK"=>$this->ID_PACK,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_minimum($oMSG){		
		$this->sql = "SELECT ID_PACK, NOM, TYPE_PACK, PRIX_BASE, DUREE, VISIBLE from pack ORDER BY PRIX_BASE DESC;";
		
		$params = array(
		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_by_VISIBLE($oMSG){	
		$this->VISIBLE = $oMSG->getData("VISIBLE");
	
		$this->sql = "SELECT * from pack WHERE VISIBLE=:VISIBLE ORDER BY PRIX_BASE DESC;";
		
		$params = array(
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_ALL_by_TYPE_PACK_et_LIMIT($oMSG){		
		$this->TYPE_PACK = $oMSG->getData("TYPE_PACK");
		$limit = $oMSG->getData("limit");
		
		$this->sql = "SELECT * from pack WHERE TYPE_PACK=:TYPE_PACK $limit;";
		
		$params = array(
				":TYPE_PACK"=>$this->TYPE_PACK,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// --------------------------------------------------- INSERT ---------------------------------------------------
	
	public function INSERT($oMSG){
		$this->NOM = $oMSG->getData("NOM");
		$this->DESCRIPTION = $oMSG->getData("DESCRIPTION");
		$this->TYPE_PACK = $oMSG->getData("TYPE_PACK");
		$this->PRIX_BASE = $oMSG->getData("PRIX_BASE");
		$this->DUREE = $oMSG->getData("DUREE");
		$this->SOUMIS_REDUCTIONS_PARRAINAGE = $oMSG->getData("SOUMIS_REDUCTIONS_PARRAINAGE");
		$this->GAIN_PARRAINAGE_MAX = $oMSG->getData("GAIN_PARRAINAGE_MAX");
		$this->REDUCTION = $oMSG->getData("REDUCTION");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->CV_VISIBILITE = $oMSG->getData("CV_VISIBILITE");
		$this->CV_ACCESSIBLE = $oMSG->getData("CV_ACCESSIBLE");
		$this->NB_FICHES_VISITABLES = $oMSG->getData("NB_FICHES_VISITABLES");
		$this->CV_VIDEO_ACCESSIBLE = $oMSG->getData("CV_VIDEO_ACCESSIBLE");
		$this->ALERTE_NON_DISPONIBILITE = $oMSG->getData("ALERTE_NON_DISPONIBILITE");
		$this->NB_DEPARTEMENTS_ALERTE = $oMSG->getData("NB_DEPARTEMENTS_ALERTE");
		$this->PARRAINAGE_ACTIVE = $oMSG->getData("PARRAINAGE_ACTIVE");
		$this->PREVISUALISATION_FICHES = $oMSG->getData("PREVISUALISATION_FICHES");
		$this->CONTRATS_PDF = $oMSG->getData("CONTRATS_PDF");
		$this->SUIVI = $oMSG->getData("SUIVI");
		$this->PUBS = $oMSG->getData("PUBS");
	
		$this->sql = "INSERT INTO pack (NOM, DESCRIPTION, TYPE_PACK, PRIX_BASE, DUREE, SOUMIS_REDUCTIONS_PARRAINAGE, GAIN_PARRAINAGE_MAX, REDUCTION, ".
		"VISIBLE, CV_VISIBILITE, CV_ACCESSIBLE, NB_FICHES_VISITABLES, CV_VIDEO_ACCESSIBLE, ALERTE_NON_DISPONIBILITE, NB_DEPARTEMENTS_ALERTE, ".
		"PARRAINAGE_ACTIVE, PREVISUALISATION_FICHES, CONTRATS_PDF, SUIVI, PUBS) ".
		"VALUES (:NOM, :DESCRIPTION, :TYPE_PACK, :PRIX_BASE, :DUREE, :SOUMIS_REDUCTIONS_PARRAINAGE, :GAIN_PARRAINAGE_MAX, :REDUCTION, :VISIBLE, :CV_VISIBILITE, ".
		":CV_ACCESSIBLE, :NB_FICHES_VISITABLES, :CV_VIDEO_ACCESSIBLE, :ALERTE_NON_DISPONIBILITE, :NB_DEPARTEMENTS_ALERTE, :PARRAINAGE_ACTIVE, ".
		":PREVISUALISATION_FICHES, :CONTRATS_PDF, :SUIVI, :PUBS);";
		
		$params = array(    
					":NOM"=>$this->NOM,
					":DESCRIPTION"=>$this->DESCRIPTION,
					":TYPE_PACK"=>$this->TYPE_PACK,
					":PRIX_BASE"=>$this->PRIX_BASE,
					":DUREE"=>$this->DUREE,
					":SOUMIS_REDUCTIONS_PARRAINAGE"=>$this->SOUMIS_REDUCTIONS_PARRAINAGE,
					":GAIN_PARRAINAGE_MAX"=>$this->GAIN_PARRAINAGE_MAX,
					":REDUCTION"=>$this->REDUCTION,
					":VISIBLE"=>$this->VISIBLE,
					":CV_VISIBILITE"=>$this->CV_VISIBILITE,
					":CV_ACCESSIBLE"=>$this->CV_ACCESSIBLE,
					":NB_FICHES_VISITABLES"=>$this->NB_FICHES_VISITABLES,
					":CV_VIDEO_ACCESSIBLE"=>$this->CV_VIDEO_ACCESSIBLE,
					":ALERTE_NON_DISPONIBILITE"=>$this->ALERTE_NON_DISPONIBILITE,
					":NB_DEPARTEMENTS_ALERTE"=>$this->NB_DEPARTEMENTS_ALERTE,
					":PARRAINAGE_ACTIVE"=>$this->PARRAINAGE_ACTIVE,
					":PREVISUALISATION_FICHES"=>$this->PREVISUALISATION_FICHES,
					":CONTRATS_PDF"=>$this->CONTRATS_PDF,
					":SUIVI"=>$this->SUIVI,
					":PUBS"=>$this->PUBS,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// --------------------------------------------------- UPDATE  -----------------------------------------------------
	
	public function UPDATE($oMSG){
		$this->ID_PACK = $oMSG->getData("ID_PACK");
		$this->NOM = $oMSG->getData("NOM");
		$this->DESCRIPTION = $oMSG->getData("DESCRIPTION");
		$this->TYPE_PACK = $oMSG->getData("TYPE_PACK");
		$this->PRIX_BASE = $oMSG->getData("PRIX_BASE");
		$this->DUREE = $oMSG->getData("DUREE");
		$this->SOUMIS_REDUCTIONS_PARRAINAGE = $oMSG->getData("SOUMIS_REDUCTIONS_PARRAINAGE");
		$this->GAIN_PARRAINAGE_MAX = $oMSG->getData("GAIN_PARRAINAGE_MAX");
		$this->REDUCTION = $oMSG->getData("REDUCTION");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->CV_VISIBILITE = $oMSG->getData("CV_VISIBILITE");
		$this->CV_ACCESSIBLE = $oMSG->getData("CV_ACCESSIBLE");
		$this->NB_FICHES_VISITABLES = $oMSG->getData("NB_FICHES_VISITABLES");
		$this->CV_VIDEO_ACCESSIBLE = $oMSG->getData("CV_VIDEO_ACCESSIBLE");
		$this->ALERTE_NON_DISPONIBILITE = $oMSG->getData("ALERTE_NON_DISPONIBILITE");
		$this->NB_DEPARTEMENTS_ALERTE = $oMSG->getData("NB_DEPARTEMENTS_ALERTE");
		$this->PARRAINAGE_ACTIVE = $oMSG->getData("PARRAINAGE_ACTIVE");
		$this->PREVISUALISATION_FICHES = $oMSG->getData("PREVISUALISATION_FICHES");
		$this->CONTRATS_PDF = $oMSG->getData("CONTRATS_PDF");
		$this->SUIVI = $oMSG->getData("SUIVI");
		$this->PUBS = $oMSG->getData("PUBS");
	
		$this->sql = "UPDATE pack SET NOM=:NOM, DESCRIPTION=:DESCRIPTION, TYPE_PACK=:TYPE_PACK, PRIX_BASE=:PRIX_BASE, DUREE=:DUREE, ".
		"SOUMIS_REDUCTIONS_PARRAINAGE=:SOUMIS_REDUCTIONS_PARRAINAGE, GAIN_PARRAINAGE_MAX=:GAIN_PARRAINAGE_MAX, REDUCTION=:REDUCTION, ".
		"VISIBLE=:VISIBLE, CV_VISIBILITE=:CV_VISIBILITE, CV_ACCESSIBLE=:CV_ACCESSIBLE, NB_FICHES_VISITABLES=:NB_FICHES_VISITABLES, ".
		"CV_VIDEO_ACCESSIBLE=:CV_VIDEO_ACCESSIBLE, ALERTE_NON_DISPONIBILITE=:ALERTE_NON_DISPONIBILITE, NB_DEPARTEMENTS_ALERTE=:NB_DEPARTEMENTS_ALERTE, ".
		"PARRAINAGE_ACTIVE=:PARRAINAGE_ACTIVE, PREVISUALISATION_FICHES=:PREVISUALISATION_FICHES, CONTRATS_PDF=:CONTRATS_PDF, SUIVI=:SUIVI, PUBS=:PUBS ".
		"WHERE ID_PACK=:ID_PACK;";
		
		$params = array(    
					":ID_PACK"=>$this->ID_PACK,
					":NOM"=>$this->NOM,
					":DESCRIPTION"=>$this->DESCRIPTION,
					":TYPE_PACK"=>$this->TYPE_PACK,
					":PRIX_BASE"=>$this->PRIX_BASE,
					":DUREE"=>$this->DUREE,
					":SOUMIS_REDUCTIONS_PARRAINAGE"=>$this->SOUMIS_REDUCTIONS_PARRAINAGE,
					":GAIN_PARRAINAGE_MAX"=>$this->GAIN_PARRAINAGE_MAX,
					":REDUCTION"=>$this->REDUCTION,
					":VISIBLE"=>$this->VISIBLE,
					":CV_VISIBILITE"=>$this->CV_VISIBILITE,
					":CV_ACCESSIBLE"=>$this->CV_ACCESSIBLE,
					":NB_FICHES_VISITABLES"=>$this->NB_FICHES_VISITABLES,
					":CV_VIDEO_ACCESSIBLE"=>$this->CV_VIDEO_ACCESSIBLE,
					":ALERTE_NON_DISPONIBILITE"=>$this->ALERTE_NON_DISPONIBILITE,
					":NB_DEPARTEMENTS_ALERTE"=>$this->NB_DEPARTEMENTS_ALERTE,
					":PARRAINAGE_ACTIVE"=>$this->PARRAINAGE_ACTIVE,
					":PREVISUALISATION_FICHES"=>$this->PREVISUALISATION_FICHES,
					":CONTRATS_PDF"=>$this->CONTRATS_PDF,
					":SUIVI"=>$this->SUIVI,
					":PUBS"=>$this->PUBS,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}