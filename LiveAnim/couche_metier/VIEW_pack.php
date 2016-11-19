<?php
class VIEW_pack{

	private $ID_PERSONNE;
	private $ID_PACK;

	private $sql;

	public function __construct() {
		$this->sql = "";
		$this->ID_PERSONNE = "";
		$this->ID_PACK = "";		
	}
	
	public function SELECT_ALL_by_DATE_ACHAT_et_ID_PERSONNE($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$limit = $oMSG->getData("limit");
		
		$this->sql = "SELECT pack.ID_PACK as ID_PACK, NOM, TYPE_PACK, PRIX_BASE, DUREE, CV_VISIBILITE, CV_ACCESSIBLE, pack_personne.NB_FICHES_VISITABLES ".
		"as NB_FICHES_VISITABLES, CV_VIDEO_ACCESSIBLE, ALERTE_NON_DISPONIBILITE, NB_DEPARTEMENTS_ALERTE, PARRAINAGE_ACTIVE, PREVISUALISATION_FICHES, CONTRATS_PDF, ".
		"SUIVI, PUBS, ID_PERSONNE, DATE_ACHAT, DATE_DEBUT, DATE_FIN ".
		"FROM pack LEFT OUTER JOIN pack_personne ON pack.ID_PACK=pack_personne.ID_PACK WHERE ID_PERSONNE=:ID_PERSONNE ".
		"AND DATE_DEBUT < NOW() AND DATE_FIN > NOW() ORDER BY DATE_ACHAT DESC $limit;";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_dernier_achat_by_DATE_ACHAT_et_ID_PERSONNE($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$limit = $oMSG->getData("limit");
		
		$this->sql = "SELECT pack.ID_PACK as ID_PACK, NOM, TYPE_PACK, PRIX_BASE, DUREE, CV_VISIBILITE, CV_ACCESSIBLE, pack_personne.NB_FICHES_VISITABLES ".
		"as NB_FICHES_VISITABLES, CV_VIDEO_ACCESSIBLE, ALERTE_NON_DISPONIBILITE, NB_DEPARTEMENTS_ALERTE, PARRAINAGE_ACTIVE, PREVISUALISATION_FICHES, CONTRATS_PDF, ".
		"SUIVI, PUBS, ID_PERSONNE, DATE_ACHAT, DATE_DEBUT, DATE_FIN ".
		"FROM pack LEFT OUTER JOIN pack_personne ON pack.ID_PACK=pack_personne.ID_PACK WHERE ID_PERSONNE=:ID_PERSONNE ORDER BY DATE_ACHAT DESC $limit;";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_PACK_by_ID_PERSONNE($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		
		$this->sql = "SELECT COUNT(pack_personne.ID_PACK)  AS nb_pack FROM pack_personne WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_ALL_by_ID_PERSONNE_et_by_LIMIT($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$nb_result_affiches = $oMSG->getData("nb_result_affiches");
		$debut_affichage = $oMSG->getData("debut_affichage");
		
		$this->sql = "SELECT pack.ID_PACK as ID_PACK, NOM, TYPE_PACK, PRIX_BASE, DUREE, pack_personne.NB_FICHES_VISITABLES as NB_FICHES_VISITABLES, ".
		"ID_PERSONNE, DATE_ACHAT, DATE_DEBUT, DATE_FIN, pack_personne.REDUCTION AS REDUCTION ".
		"FROM pack LEFT OUTER JOIN pack_personne ON pack.ID_PACK=pack_personne.ID_PACK WHERE ID_PERSONNE=:ID_PERSONNE ORDER BY DATE_ACHAT DESC LIMIT $debut_affichage, $nb_result_affiches";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_pack_by_ID_PERSONNE_et_DATE_ACHAT($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->DATE_ACHAT = $oMSG->getData("DATE_ACHAT");
		
		$this->sql = "SELECT COUNT(pack_personne.ID_PACK) AS nb_pack, pack_personne.ID_PACK AS ID_PACK, ID_PERSONNE, DATE_ACHAT, DATE_DEBUT, DATE_FIN, DUREE ".
		"FROM pack LEFT OUTER JOIN pack_personne ON pack.ID_PACK=pack_personne.ID_PACK ".
		"WHERE ID_PERSONNE=:ID_PERSONNE AND DATE_ACHAT=:DATE_ACHAT;";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
				":DATE_ACHAT"=>$this->DATE_ACHAT,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_SUM_couts_packs_by_ID_PERSONNE($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		
		$this->sql = "SELECT SUM(pack.PRIX_BASE) AS prix_total ".
		"FROM pack LEFT OUTER JOIN pack_personne ON pack.ID_PACK=pack_personne.ID_PACK ".
		"WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}