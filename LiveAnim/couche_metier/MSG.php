<?php
/**
* Cette classe permet de transmettre un flot d'informations très importantes.
* A utiliser pour transmettre la plupart des informations.
*/
class MSG{
	
	private $statut; # Booléen de validation des diverses opérations.
	private $message_erreur; # Message d'erreur à l'intention de l'utilisateur.
	private $data; # Tableau contenant toutes les données.
	
	function __construct(){
		$this->statut = false;
		$this->message_erreur = NULL;
		$this->data = array();
	
	}
	
	# Accesseurs:
	public function getStatut(){
		return $this->statut;
	}
	
	public function getMessage_erreur(){
		return $this->message_erreur;
	}
	
	public function getData($data){
		return $this->data[$data];
	}
	
	public function getDatas(){
		return $this->data;
	}


	# Mutateur:
	public function setStatut($statut){
		$this->statut = $statut;
	}
	
	public function setMessage_erreur($message){
		$this->message_erreur = $message;
	}
	
	public function setData($key, $value){
		$this->data[$key] = $value;
	}
	
	public function setDatas($data){
		$this->data = $data;
	}
	
}




?>