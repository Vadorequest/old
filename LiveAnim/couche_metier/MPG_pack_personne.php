<?php
class MPG_pack_personne{

	private $ID_PERSONNE;
	private $ID_PACK;
	private $DATE_ACHAT;
	private $DATE_DEBUT;
	private $DATE_FIN;
	private $REDUCTION;
	private $NB_FICHES_VISITABLES;
	private	$DATAS_PAYPAL;

	private $sql;

	public function __construct() {
		$this->sql = "";
		$this->ID_PERSONNE = "";
		$this->ID_PACK = "";
		$this->DATE_ACHAT = "";
		$this->DATE_DEBUT = "";
		$this->DATE_FIN = "";
		$this->REDUCTION = "";
		$this->NB_FICHES_VISITABLES = "";		
		$this->DATAS_PAYPAL = "";			
	}
	
	// -------------------------------------------------- SELECT -----------------------------------------------------------
	
	public function SELECT_COUNT_packs_achetes_by_ID_PACK($oMSG){		
		$this->ID_PACK = $oMSG->getData("ID_PACK");
		
		$this->sql = "SELECT COUNT(ID_PACK) as nb_pack FROM pack_personne WHERE ID_PACK=:ID_PACK;";
		
		$params = array(
				":ID_PACK"=>$this->ID_PACK,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// -------------------------------------------------- INSERT -----------------------------------------------------------
	
	public function INSERT($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->ID_PACK = $oMSG->getData("ID_PACK");
		$this->DATE_ACHAT = $oMSG->getData("DATE_ACHAT");
		$this->DATE_DEBUT = $oMSG->getData("DATE_DEBUT");
		$this->DATE_FIN = $oMSG->getData("DATE_FIN");
		$this->REDUCTION = $oMSG->getData("REDUCTION");
		$this->NB_FICHES_VISITABLES = $oMSG->getData("NB_FICHES_VISITABLES");
		$this->DATAS_PAYPAL = $oMSG->getData("DATAS_PAYPAL");
		
		$this->sql = "INSERT INTO pack_personne (ID_PERSONNE, ID_PACK, DATE_ACHAT, DATE_DEBUT, DATE_FIN, REDUCTION, NB_FICHES_VISITABLES, DATAS_PAYPAL) ".
		"VALUES (:ID_PERSONNE, :ID_PACK, :DATE_ACHAT, :DATE_DEBUT, :DATE_FIN, :REDUCTION, :NB_FICHES_VISITABLES, :DATAS_PAYPAL);";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
				":ID_PACK"=>$this->ID_PACK,
				":DATE_ACHAT"=>$this->DATE_ACHAT,
				":DATE_DEBUT"=>$this->DATE_DEBUT,
				":DATE_FIN"=>$this->DATE_FIN,
				":REDUCTION"=>$this->REDUCTION,
				":NB_FICHES_VISITABLES"=>$this->NB_FICHES_VISITABLES,
				":DATAS_PAYPAL"=>$this->DATAS_PAYPAL,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ------------------------------------------------- UPDATE ------------------------------------------------------
	
	public function UPDATE_DATE_FIN_by_IDs($oMSG){		
	$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	$this->ID_PACK = $oMSG->getData("ID_PACK");
	$this->DATE_ACHAT = $oMSG->getData("DATE_ACHAT");
	$this->DATE_FIN = $oMSG->getData("DATE_FIN");
	
	$this->sql = "UPDATE pack_personne SET DATE_FIN=:DATE_FIN WHERE ID_PACK=:ID_PACK AND ID_PERSONNE=:ID_PERSONNE AND DATE_ACHAT=:DATE_ACHAT;";
	
	$params = array(
			":ID_PERSONNE"=>$this->ID_PERSONNE,
			":ID_PACK"=>$this->ID_PACK,
			":DATE_ACHAT"=>$this->DATE_ACHAT,
			":DATE_FIN"=>$this->DATE_FIN,
				);
	
	$oMSG->setData(0, $this->sql);
	$oMSG->setData(1, $params);
	
	return $oMSG;
	}
	
	public function UPDATE_DATE_DEBUT_et_DATE_FIN_by_IDs($oMSG){		
	$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	$this->ID_PACK = $oMSG->getData("ID_PACK");
	$this->DATE_ACHAT = $oMSG->getData("DATE_ACHAT");
	$this->DATE_DEBUT = $oMSG->getData("DATE_DEBUT");
	$this->DATE_FIN = $oMSG->getData("DATE_FIN");
	
	$this->sql = "UPDATE pack_personne SET DATE_DEBUT=:DATE_DEBUT, DATE_FIN=:DATE_FIN WHERE ID_PACK=:ID_PACK AND ID_PERSONNE=:ID_PERSONNE AND DATE_ACHAT=:DATE_ACHAT;";
	
	$params = array(
			":ID_PERSONNE"=>$this->ID_PERSONNE,
			":ID_PACK"=>$this->ID_PACK,
			":DATE_ACHAT"=>$this->DATE_ACHAT,
			":DATE_DEBUT"=>$this->DATE_DEBUT,
			":DATE_FIN"=>$this->DATE_FIN,
				);
	
	$oMSG->setData(0, $this->sql);
	$oMSG->setData(1, $params);
	
	return $oMSG;
	}
	
	public function UPDATE_decremente_NB_FICHES_VISITABLES_by_IDs($oMSG){		
	$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	$this->ID_PACK = $oMSG->getData("ID_PACK");
	$this->DATE_ACHAT = $oMSG->getData("DATE_ACHAT");
	$this->NB_FICHES_VISITABLES = $oMSG->getData("NB_FICHES_VISITABLES");
	
	$this->sql = "UPDATE pack_personne SET NB_FICHES_VISITABLES=:NB_FICHES_VISITABLES-1 WHERE ID_PACK=:ID_PACK AND ID_PERSONNE=:ID_PERSONNE AND DATE_ACHAT=:DATE_ACHAT;";
	
	$params = array(
			":ID_PERSONNE"=>$this->ID_PERSONNE,
			":ID_PACK"=>$this->ID_PACK,
			":DATE_ACHAT"=>$this->DATE_ACHAT,
			":NB_FICHES_VISITABLES"=>$this->NB_FICHES_VISITABLES,
				);
	
	$oMSG->setData(0, $this->sql);
	$oMSG->setData(1, $params);
	
	return $oMSG;
	}
	
}