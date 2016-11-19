<?php
class VIEW_contrat{

	private $ID_CONTRAT;
	private $ID_ANNONCE;
	private $VISIBLE;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_CONTRAT = "";
	$this->ID_ANNONCE = "";
	$this->VISIBLE = "";
	}
	
	
	public function SELECT_COUNT_nb_contrat_by_ID_PERSONNE_et_ID_ANNONCE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->ID_ANNONCE = $oMSG->getData('ID_ANNONCE');
		$condition = $oMSG->getData('condition');
		if(empty($condition)){
			$condition = ";";
		}
	
		$this->sql = "SELECT COUNT(ID_CONTRAT) as nb_contrat FROM contrat NATURAL JOIN contrat_personne WHERE ID_PERSONNE=:ID_PERSONNE AND ID_ANNONCE=:ID_ANNONCE $condition;";
		
		$params = array(  
					':ID_PERSONNE' =>$this->ID_PERSONNE,		
					':ID_ANNONCE' =>$this->ID_ANNONCE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_nb_contrat_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
	
		$this->sql = "SELECT COUNT(contrat.ID_CONTRAT) as nb_contrat FROM contrat RIGHT OUTER JOIN contrat_personne ".
		"ON contrat_personne.ID_CONTRAT = contrat.ID_CONTRAT WHERE contrat_personne.ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(  
					':ID_PERSONNE' =>$this->ID_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_contrat_min_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');

		$this->sql = "SELECT contrat.ID_CONTRAT, contrat.ID_ANNONCE, DATE_CONTRAT, STATUT_CONTRAT, URL_CONTRAT_PDF, annonce.TITRE  FROM contrat_personne ".
		"LEFT OUTER JOIN contrat ON contrat_personne.ID_CONTRAT = contrat.ID_CONTRAT ".
		"LEFT OUTER JOIN annonce ON annonce.ID_ANNONCE = contrat.ID_ANNONCE ".
		"WHERE contrat_personne.ID_PERSONNE=:ID_PERSONNE ".
		"ORDER BY DATE_CONTRAT DESC ".
		"LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(  
					':ID_PERSONNE' =>$this->ID_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_contrats_courants_min_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
	
		$this->sql = "SELECT COUNT(contrat.ID_CONTRAT) as nb_contrat FROM contrat RIGHT OUTER JOIN contrat_personne ".
		"ON contrat_personne.ID_CONTRAT = contrat.ID_CONTRAT WHERE contrat_personne.ID_PERSONNE=:ID_PERSONNE AND ".
		"(STATUT_CONTRAT='En attente' OR STATUT_CONTRAT='Refusé');";
		
		$params = array(  
					':ID_PERSONNE' =>$this->ID_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_contrats_courants_min_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');

		$this->sql = "SELECT contrat.ID_CONTRAT, contrat.ID_ANNONCE, DATE_CONTRAT, STATUT_CONTRAT, URL_CONTRAT_PDF, annonce.TITRE  FROM contrat_personne ".
		"LEFT OUTER JOIN contrat ON contrat_personne.ID_CONTRAT = contrat.ID_CONTRAT ".
		"LEFT OUTER JOIN annonce ON annonce.ID_ANNONCE = contrat.ID_ANNONCE ".
		"WHERE contrat_personne.ID_PERSONNE=:ID_PERSONNE AND (STATUT_CONTRAT='En attente' OR STATUT_CONTRAT='Refusé') ".
		"ORDER BY DATE_CONTRAT DESC ".
		"LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(  
					':ID_PERSONNE' =>$this->ID_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_contrat_by_ID_CONTRAT($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');

		$this->sql = "SELECT contrat.ID_CONTRAT, contrat.ID_ANNONCE, contrat.DATE_CONTRAT, contrat.STATUT_CONTRAT, contrat.DATE_EVALUATION, ".
		"contrat.DESCRIPTION as DESCRIPTION_contrat, contrat.DATE_DEBUT as DATE_DEBUT_contrat, contrat.DATE_FIN as DATE_FIN_contrat, ".
		"contrat.PRIX as PRIX_contrat, contrat.DESTINATAIRE, annonce.TITRE, annonce.TYPE_ANNONCE, annonce.DATE_DEBUT as DATE_DEBUT_annonce, ".
		"annonce.DATE_FIN as DATE_FIN_annonce, annonce.BUDGET as PRIX_annonce, annonce.DESCRIPTION as DESCRIPTION_annonce ".
		"FROM contrat ".
		"LEFT OUTER JOIN annonce ON annonce.ID_ANNONCE = contrat.ID_ANNONCE ".
		"WHERE contrat.ID_CONTRAT=:ID_CONTRAT;";
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
	public function SELECT_departement_annonce_lors_annulation_contrat($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');

		$this->sql = "SELECT contrat.ID_CONTRAT, contrat.ID_ANNONCE, annonce.ID_DEPARTEMENT ".
		"FROM contrat LEFT OUTER JOIN annonce ON contrat.ID_ANNONCE = annonce.ID_ANNONCE ".
		"WHERE ID_CONTRAT=:ID_CONTRAT";
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
				
	public function SELECT_by_STATUT($oMSG){
		$this->STATUT_CONTRAT = $oMSG->getData('STATUT_CONTRAT');
		$debut_affichage = $oMSG->getData("debut_affichage");
		$nb_result_affiches = $oMSG->getData("nb_result_affiches");
		
		$this->sql = "SELECT DISTINCT contrat.ID_CONTRAT, contrat.ID_ANNONCE, contrat.DATE_CONTRAT, contrat.STATUT_CONTRAT, contrat.PRIX, contrat.GOLDLIVE, ".
		"annonce.TITRE ".
		"FROM contrat LEFT OUTER JOIN annonce ON contrat.ID_ANNONCE = annonce.ID_ANNONCE ".
		"WHERE STATUT_CONTRAT=:STATUT_CONTRAT ORDER BY DATE_CONTRAT DESC LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(  
					':STATUT_CONTRAT' =>$this->STATUT_CONTRAT,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
		
	public function SELECT_all($oMSG){
		$debut_affichage = $oMSG->getData("debut_affichage");
		$nb_result_affiches = $oMSG->getData("nb_result_affiches");
		
		$this->sql = "SELECT DISTINCT contrat.ID_CONTRAT, contrat.ID_ANNONCE, contrat.DATE_CONTRAT, contrat.STATUT_CONTRAT, contrat.PRIX, contrat.GOLDLIVE, ".
		"annonce.TITRE ".
		"FROM contrat LEFT OUTER JOIN annonce ON contrat.ID_ANNONCE = annonce.ID_ANNONCE ".
		"ORDER BY contrat.DATE_CONTRAT DESC, contrat.ID_ANNONCE LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(  
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
		
	public function SELECT_COUNT_prestations($oMSG){
		$this->STATUT_CONTRAT = $oMSG->getData('STATUT_CONTRAT');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$where = $oMSG->getData('where');
		
		$this->sql = "SELECT COUNT(contrat.ID_CONTRAT) AS nb_contrat ".
		"FROM contrat LEFT OUTER JOIN contrat_personne ON contrat_personne.ID_CONTRAT = contrat.ID_CONTRAT ".
		"WHERE STATUT_CONTRAT=:STATUT_CONTRAT AND contrat_personne.ID_PERSONNE=:ID_PERSONNE $where;";
		
		$params = array(  
					':STATUT_CONTRAT' => $this->STATUT_CONTRAT,
					':ID_PERSONNE' => $this->ID_PERSONNE,
					);

		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
		
	public function SELECT_prestations_min($oMSG){
		$this->STATUT_CONTRAT = $oMSG->getData('STATUT_CONTRAT');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$where = $oMSG->getData('where');
		$debut_affichage = $oMSG->getData("debut_affichage");
		$nb_result_affiches = $oMSG->getData("nb_result_affiches");
		
		$this->sql = "SELECT DISTINCT contrat.ID_CONTRAT, contrat.ID_ANNONCE, contrat.DATE_CONTRAT, contrat.DATE_FIN, contrat.PRIX, contrat.DESTINATAIRE, ".
		"annonce.TITRE, annonce.ADRESSE, annonce.CP, annonce.VILLE ".
		"FROM contrat LEFT OUTER JOIN annonce ON contrat.ID_ANNONCE = annonce.ID_ANNONCE ".
		"LEFT OUTER JOIN contrat_personne ON contrat_personne.ID_CONTRAT = contrat.ID_CONTRAT ".
		"WHERE STATUT_CONTRAT=:STATUT_CONTRAT AND contrat_personne.ID_PERSONNE=:ID_PERSONNE $where ".
		"ORDER BY contrat.DATE_CONTRAT DESC, contrat.ID_ANNONCE LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(  
					':STATUT_CONTRAT' => $this->STATUT_CONTRAT,
					':ID_PERSONNE' => $this->ID_PERSONNE,
					);

		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
		
	public function SELECT_MOY_evaluation_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->TYPE_EVALUATION = $oMSG->getData('TYPE_EVALUATION');
		
		$this->sql = "SELECT DISTINCT AVG(EVALUATION) as moy_evaluation ".
		"FROM contrat_personne LEFT OUTER JOIN contrat ON contrat_personne.ID_CONTRAT = contrat.ID_CONTRAT ".
		"LEFT OUTER JOIN evaluation ON evaluation.ID_CONTRAT = contrat.ID_CONTRAT ".
		"WHERE contrat_personne.ID_PERSONNE=:ID_PERSONNE AND evaluation.TYPE_EVALUATION=:TYPE_EVALUATION;";

		$params = array(  
					':ID_PERSONNE' => $this->ID_PERSONNE,
					':TYPE_EVALUATION' => $this->TYPE_EVALUATION,
					);

		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
		
	public function SELECT_SUM_gains_contrats_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$criteres = $oMSG->getData('criteres');
		
		$this->sql = "SELECT SUM(DISTINCT contrat.PRIX) as prix_total ".
		"FROM contrat_personne LEFT OUTER JOIN contrat ON contrat_personne.ID_CONTRAT = contrat.ID_CONTRAT ".
		"LEFT OUTER JOIN evaluation ON evaluation.ID_CONTRAT = contrat.ID_CONTRAT ".
		"WHERE contrat_personne.ID_PERSONNE=:ID_PERSONNE $criteres";

		$params = array(  
					':ID_PERSONNE' => $this->ID_PERSONNE,
					);

		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
}