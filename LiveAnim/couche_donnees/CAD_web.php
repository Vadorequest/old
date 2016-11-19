<?php
error_reporting(0);// On cache les erreurs sinon en cas d'impossibilité de connexion on va afficher les logs de la bdd.
class CAD{
	
	private $user;
	private $pass;
	private $dsn;
	private $dbo;// DataBaseObject --> Objet de connexion à la BDD.
	

	
	/* *
	* Constructeur du CAD
	* Initialise les valeurs de connexion à la BDD
	*
	*/
	function __construct(){
		
		# Initialisation des variables:
		$this->sql = "";
		
		
		#Definition des variables de connexion:
		$this->user = "liveanimbdd1";
		$this->pass = "LiveAnimBDD";
		$this->dsn = "mysql:host=mysql51-41.perso;dbname=liveanimbdd1";
		try{
			$this->dbo = new PDO($this->dsn, $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); 
		}catch (PDOExeption $e){
			die("Erreur ! :".$e->getMessage());
		}
	}

        
        /* ******************************* ActionRows ***********************************
	* Méthode à utiliser pour les requêtes INSERT, UPDATE, DELETE
	* Reçoit la requête SQL en position 0 et le tableau de paramètres en position 1
	* Renvoit le résultat dans $msg->data[1], retourne FALSE si la requête à échouée
        * Les données renvoyées correspondent au nombre de lignes modifiées.
        * Les données renvoyées doivent être traitées.
	*/
	public function ActionRows($msg, $last_ID = false, $nb_ligne = false){
	
		$sql = $msg->getData(0);
		$params = $msg->getData(1);
                
		$statement = $this->dbo->prepare($sql);

		$statement->execute($params);
		
		if($last_ID == true){
			# On fait une requête pour récupérer le dernier ID modifié. On le place dans $msg->data[1];
                        $msg->setData(1, $this->dbo->lastInsertId());
		}
		
		if($nb_ligne == true){
			# On met $resultat dans $msg->data[2] qui correspond au nombre de lignes modifiées (= 0 si aucune modification) ou à une erreur (= FALSE si erreur).
			$msg->setData(2, $resultat);
		}
                
                
		$this->dbo = NULL;
		return $msg;
		
	
	}
	
	
	
	/* ******************************* GetRows ***********************************
	* Méthode à utiliser pour les requêtes SELECT, EXPLAIN, SHOW et DESC
	* Reçoit la requête SQL en position 0 et le tableau de paramètres en position 1
	* Renvoit le résultat dans $msg->data[1], retourne FALSE si la requête à échouée
        * Les données renvoyées devront être traitées. (Fetch(), Fetchall()) 
	*/	
	public function GetRows($msg){
		$sql = $msg->getData(0);
		$params = $msg->getData(1);
		
		$statement = $this->dbo->prepare($sql);
		$statement->execute($params);

                
		$msg->setData(1, $statement);
	
		$this->dbo = NULL; # On ferme la connexion.
		return $msg;
	}
	
	
	
}


?>