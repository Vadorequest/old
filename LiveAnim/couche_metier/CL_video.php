<?php
class CL_video{

	private $url;
	private $url_hebergeur;
	private $url_decoupee;
	private $delimiter;
	private $tag_video;
	private $regex;
	private $youtube;
	private $youtu;
	private $youtubewatch;
	
	public function __construct($delimiter = "/", $regex = ""){
		$this->url = "";
		$this->url_hebergeur = "";
		$this->url_decoupee = "";
		$this->delimiter = $delimiter;
		$this->tag_video = "";
		$this->regex = $regex;
		$this->youtube = "http://www.youtube.com/v/";
		$this->youtu = "http://youtu.be/";
		$this->youtubewatch = "http://www.youtube.com/watch?v=";
	}

	public function fx_recuperer_tag($url){
		$this->url = $url;
		
		$this->url_decoupee = explode($this->delimiter, $this->url);
		
		# On compte le nombre d'éléments dans le tableau.
		$nb_elements = count($this->url_decoupee);
	
		# Vérification youtube
		if(substr_count($this->url, $this->youtube) > 0){
			return $this->url;
			
		}else if(substr_count($this->url, $this->youtu)  > 0){
			# On retourne la chaine de base + le dernier élément du tableau.
			return $this->youtube.$this->url_decoupee[$nb_elements-1];
			
		}else if(substr_count($this->url, $this->youtubewatch)  > 0){
			# On retourne la chaine mais en modifiant le début.
			return str_replace("http://www.youtube.com/watch?v=", "http://www.youtube.com/v/", $this->url);
		
		}else{
			//return $this->youtube.$this->url_decoupee[$nb_elements-1];
		}
	}

}
?>