<?php
/*
* @Author: Ambroise Dhenain
* @Date: 15 septembre 2011
* @Description: Classe d'upload qui permet d'uploader n'importe quel type de fichier en précisant toutes ses caractéristiques.
* @Exemple d'utilisation: 

// On crée l'objet, on lui passe ses paramètres, ils ne seront pas forcément tous modifiés.
$oCL_upload = new CL_upload($_FILES['fichier_uploade'], "images/uploads/membres", array("png", "gif", "jpg", "jpeg"), 0777, array("image/jpeg", "image/jpeg", "image/png", "image/gif", "image/pjpg", "image/pjpeg"), 200, 200, 30000);
	$new_filename = $ID_PERSONNE."_".date("Y-m-d_H-i-s");
	$ext = explode('.', $_FILES['fichier_uploade']['name']);
	$extension = $ext[count($ext)-1];
	
	// On upload le fichier: $verif_mime = true, $verif_largeur = false, $verif_longueur = false, $verif_size = true (valeurs de base de la fonction).
	$tab_message = $oCL_upload->fx_upload($_FILES['fichier_uploade']['name'], $new_filename);
	
	if($tab_message['reussite'] == true){
		$URL_PHOTO_PRINCIPALE =  $oCL_page->getPage('accueil', 'absolu').$tab_message['resultat'];
	}else{
		$_SESSION['modification_fiche_membre']['message'].= $tab_message['resultat'];
		$URL_PHOTO_PRINCIPALE = "";
		$echec_upload = true;
		# On empèche pas la modification de la fiche.
	}
	
* 
*/

class CL_upload {
    
	private $file;
    private $path;
    private $extensions = array();
    private $chmod = 0777;
	private $mime = array();
	private $hauteur = 200;
	private $largeur = 200;
	private $size = 2097152;
    /* La taille correspond au nombre d'OCTETS maximums du fichier. Ici 2Mo soit le maximal autorisé par php de base. 
	* (Modifiable ! --> http://forum.ovh.com/archive/index.php/t-7622.html  (Serveur Dédié nécessaire !) ) */
	
	private $errorsMessage = "";# Contient tous les messages d'erreurs.
	
    private $messages = array(
                            "success"   => "<span class='alert'>Téléchargement effectué avec succès.</span><br />",
                            "extension" => "<span class='alert'>Extension non autorisé.</span><br />",
                            "echec_upload"    => "<span class='alert'>Une erreur est survenue.</span><br />",
							"nom"		=> "<span class='alert'>Le nom du fichier est invalide.</span><br />",
							"mime"		=> "<span class='alert'>Le type mime du fichier est invalide.</span><br />",
							"largeur"	=> "<span class='alert'>La largeur du fichier est trop grande.</span><br />",
							"longueur"	=> "<span class='alert'>La longueur du fichier est trop grande.</span><br />",
							"size"		=> "<span class='alert'>La taille (Ko) du fichier est trop grande.</span><br />",
                        );    
    
    /**
    * Constructeur
    * @param string $file : Fichier à uploader
    * @param string $path : Chemin du fichier à uploader
    * @param array $extensions : Extensions autorisé pour l'upload
    * @param int $chmod : Droits du fichier uploadé
    * @param array $mime : Les types mimes autorisés
    * @param int $hauteur : Hauteur maximale du fichier (pixels)
	* @param int $largeur : Largeur maximale du fichier (pixels)
	* @param int $size : Taille maximale du fichier (octets)
    */
    public function __construct($file = null, $path = "images/uploads/", $extensions = array("jpg", "jpeg", "png", "gif"), $chmod = 0777, $mime = array("image/jpeg", "image/jpeg", "image/png", "image/gif", "image/pjpg", "image/pjpeg"), $hauteur = 200, $largeur = 200, $size = 2097152) {
        $this->file = $file;
        $this->path = $path;
        $this->extensions = $extensions;
        $this->chmod = $chmod;
		$this->mime = $mime;
		$this->hauteur = $hauteur;
		$this->largeur = $largeur;
		$this->size = $size;
		$this->errorsMessage = "";
    }    
    
    /**
    * Retourne le fichier uploadé
    * @return string
    */
    public function getFile() {
        return $this->file;
    }

    /**
    * Retourne le chemin d'upload
    * @return string
    */
    public function getPath() {
        return $this->path;
    }

    /**
    * Retourne les extensions autorisé sous forme de tableau
    * @return array
    */
    public function getExtensions() {
        return $this->extensions;
    }

    /**
    * Retourne le chmod du répertoire d'upload
    * @return int
    */
    public function getChmod() {
        return $this->chmod;
    }

    /**
    * Retourne la valeur d'une clé de l'attribut $messages
    * @return string
    */
    public function getMessage($name) {
        $keys = array_keys($this->messages);
        if(in_array($name, $keys)){
            return $this->messages[$name];
		}
        else
            return "<strong>" . $name . "</strong> n'est pas pris en charge";
    }

    /**
    * Définit le fichier à uploader
    * @param string $file : Fichier à uploader
    * @return void
    */
    public function setFile($file) {
        $this->file = $file;
    }
    
    /**
    * Définit le chemin de l'upload
    * @param string $path : Chemin du fichier à uploader
    * @return void
    */
    public function setPath($path) {
        $this->path = $path;
    }
    
    /**
    * Définit les extensions autorisé lors de l'upload
    * @param array $extensions : Extensions autorisé pour l'upload
    * @return void
    */
    public function setExtensions($extensions = array()){
        $this->extensions = $extensions;
    }

    /**
    * Définit le chmod du répertoire d'upload
    * @param int $chmod : Chmod du répertoire
    * @return void
    */
    public function setChmod($chmod) {
        $this->chmod = $chmod;
    }
    
    /**
    * Définit un message personnalisé sous forme de tableau associatif
    * @param array $message : Message personnalisé (array("success" => "New message"))
    * @return void
    */
    public function setMessage($message = array()){
        foreach($message as $key => $value){
            $this->messages[$key] = $value;
        }
    }
	
	/**
    * Retourne la hauteur du fichier
    * @return int
    */
    public function getHauteur() {
        return $this->Hauteur;
    }

    /**
    * Définit la hauteur maximale du fichier (en pixels).
	* @param int $hauteur : La hauteur du fichier, en pixels.
    * @return void
    */
    public function setHauteur($hauteur = 200) {
        $this->hauteur = $hauteur;
    } 
	
	/**
    * Retourne la largeur du fichier
    * @return int
    */
    public function getLargeur() {
        return $this->largeur;
    }

    /**
    * Définit la largeur maximale du fichier (en pixels).
	* @param int $largeur : La largeur du fichier, en pixels.
    * @return void
    */
    public function setLargeur($largeur = 200) {
        $this->largeur = $largeur;
    } 
    
	 /**
    * Retourne le type mime du fichier
    * @return string
    */
    public function getMime() {
        return $this->mime;
    }

    /**
    * Définit les types mimes autorisés
	* @param array $mime : Tableau contenant tous les types mime autorisés.
    * @return void
    */
    public function setMime($mime = array()) {
        $this->mime = $mime;
    } 
	
	/**
    * Retourne la taille en octets du fichier
    * @return string
    */
    public function getSize() {
        return $this->size;
    }

    /**
    * Définit la taille maximale (en octet) du fichier
	* @param int $size : La taille en octets. 2Mo si non précisé.
    * @return void
    */
    public function setSize($size = 2097152) {
        $this->size = $size;
    }
	
    /**
    * Upload le fichier
	* @param string $filename : Le nom du fichier.
	* @param string $new_filename : Le nouveau nom du fichier si différent de "0".
	* @param bool $verif_mime : Indique si le type mime doit être vérifiée. Vrai par défaut.
	* @param bool $time_auto : Indique si la fonction time est appliquée automatiquement en cas de fichier déjà existant.
	* @param bool $verif_largeur : Indique si la largeur doit être vérifiée. Faux par défaut.
	* @param bool $verif_longueur : Indique si la longueur doit être vérifiée. Faux par défaut.
	* @param bool $verif_size : Indique si la taille doit être vérifiée. Vrai par défaut.
    * @return string or array
    */
    public function fx_upload($filename, $new_filename = "0", $verif_mime = true, $time_auto = true, $verif_largeur = false, $verif_longueur = false, $verif_size = true){
		$error = 0;# Un type INT permet d'incrémenter à chaque erreur sans ce soucier de la valeur précédente. On peut donc connaître le nombre d'erreurs totales.
        $this->errorsMessage = "";# On réinitialise les messages d'erreurs entre deux appels de fx_upload.
        
		# On récupère l'extension du fichier en découpant le nom à chaque "." et en sélectionnant la dernière partie.
        $ext = explode('.', $filename);
        $extension = $ext[count($ext)-1];

		# On récupère les tailles du fichier. /!\ On regarde par rapport à l'emplacement temporaire du fichier.
		$tailles = getimagesize($this->file['tmp_name']);
		
		if($verif_mime){
			# On récupère le type MIME du fichier.
			$mime = $tailles['mime'];

			if($mime == NULL){
				# On tente alors de récupérer le type du fichier. (Notamment les PDF qui n'ont pas de MIME mais un type.
				$mime = $this->file['type'];
			}
		}
		
		if($verif_size){
			# On récupère la taille en BYTES ~ octets du fichier.
			$size = $this->file['size'];
		}
		
		# Si on a fournit un nouveau nom alors on modifie l'ancien.
		if($new_filename != "0"){
			$filename = $new_filename.".".$extension; # On oublie pas de rajouter l'extension à la fin.
		}else{
			$filename.=".".$extension; # On rajoute l'extention.
		}
		
		
		// ------------------------------------------ On commence les vérifications après avoir récupéré toutes les données.
		
		# On vérifie le nom du fichier.
        if($filename == ''){
			$error++; # Le fichier ne possède pas de nom.
			$this->errorsMessage.= $this->getMessage('nom');
		}
	
		# On vérifie l'extension du fichier.
		if(!in_array(strtolower($extension), $this->extensions)) {
			$this->errorsMessage.= $this->getMessage('extension');
			$error++;
		}

		if($verif_mime){
			# On vérifie le type mime du fichier.
			if(!in_array($mime, $this->mime)) {
				$this->errorsMessage.= $this->getMessage('mime');
				$error++;
			}
		}
		
		# Si on a décidé de vérifier la largeur.
		if($verif_largeur){
			if($tailles[0] > $this->largeur){
				# La largeur de l'image est supérieur à celle que l'on désire.
				$this->errorsMessage.= $this->getMessage('largeur');
				$error++;
			}
		}
	
		# Si on a décidé de vérifier la longueur.
		if($verif_longueur){
			if($tailles[1] > $this->longueur){
				# La longueur de l'image est supérieur à celle que l'on désire.
				$this->errorsMessage.= $this->getMessage('longueur');
				$error++;
			}
		}
	
		if($verif_size){
			if($size > $this->size){
				# Si la taille du fichier est supérieure à la taille que l'on désire.
				$this->errorsMessage.= $this->getMessage('size');
				$error++;
			}
		}

		# S'il n'y a aucune erreur
        if($error == 0) {
			# On regarde si le chemin choisi est un dossier.
            if(!is_dir($this->path)) {
				# Si ce n'est pas un dossier alors on le crée et on lui attribue le chmod. (Chmod = droits d'accès au niveau du serveur pour les dossiers/fichiers).
                mkdir($this->path);
                chmod($this->path, $this->chmod);
            }
            
			# Si on a laissé le time_auto à true alors on modifie le nom du fichier afin de ne pas écraser l'existant. (S'il existe).
			if($time_auto){
				# Si le fichier qu'on veut mettre dans le dossier existe déjà on lui attribue une valeur devant son nom.
				if(file_exists($this->path .'/'. $filename)){
					$filename = time() . $filename;
				}
			}
            
			# On tente d'uploader le fichier.
            $upload = move_uploaded_file($this->file['tmp_name'], $this->path .'/'. $filename);
                if($upload){
					$chemin = $this->path .'/'. $filename;
					
                    return array("reussite" => true, "resultat" => $this->path .'/'. $filename);# On retourne true pour dire que ça a fonctionné.
				}
                else{
                    return array("reussite" => false, "resultat" => $this->getMessage('echec_upload'));
				}
        }else {
			# S'il y a une/des erreur(s) alors on retourne le message d'erreur et un booleen false
            return array("reussite" => false, "resultat" => $this->errorsMessage);
        }
    }
    
    /**
    * Permet d'avoir un aperçu rapide de l'objet instancié.
    * @return string
    */
    public function getUpload() {
        echo '<pre>';
        print_r($this);
        echo '</pre>';
    }
	
	/**
    * Permet d'avoir un aperçu rapide de l'objet instancié et de toutes ses méthodes.
    * @return string
    */
	public function __toString(){
		return '<pre>' . print_r($this, true) . 'Méthodes: ' . print_r(get_class_methods(__CLASS__), true) . '<pre>';
	}

}
?>