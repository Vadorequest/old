<?php 
	class CL_cryptage{
	
	public function Cryptage($MDP, $Clef){
		
		# On rajoute un grain de sable et une clé
		$Clef = "¤".$Clef."V4@";
		
		$LClef = strlen($Clef);
		$LMDP = strlen($MDP);
							
		if ($LClef < $LMDP){
					
			$Clef = str_pad($Clef, $LMDP, $Clef, STR_PAD_RIGHT);
		
		}
					
		elseif ($LClef > $LMDP){

			$diff = $LClef - $LMDP;
			$_Clef = substr($Clef, 0, -$diff);

		}
	    
		return $MDP ^ $Clef; // La fonction envoie le texte crypté
				
	}
	
}
?>