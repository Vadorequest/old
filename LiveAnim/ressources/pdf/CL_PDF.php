<?php
require('fpdf.php');

class CL_PDF extends FPDF
{
	var $B;
	var $I;
	var $U;
	var $HREF;
	
	// En-tête
	function Header()
	{
		// Logos
		$this->Image("images/logo.png",10,6,60, 0, '', 'http://liveanim.com');
		$this->Image("images/logo.png",155,6,60, 0, '', 'http://liveanim.com');
		// Police Arial gras 15
		$this->SetFont('Arial','B',15);
		$this->Ln(8);// Saut de ligne
		$this->Cell(60);// Décalage à droite
		// Titre entouré d'un cadre (bordure) et en couleur.
		$this->SetFillColor(235,40,82);// Couleur rose.
		$this->Cell(75,12,' Contrat sur LiveAnim.com :',0,1,'L',true);
		// Saut de ligne
		$this->Ln(15);
	}

	// Pied de page
	function Footer()
	{
		// Positionnement à 1,5 cm du bas
		$this->SetY(-15);
		// Police Arial italique 8
		$this->SetFont('Arial','I',8);
		// Numéro de page
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function PDF($orientation='P', $unit='mm', $size='A4')
{
		// Appel au constructeur parent
		$this->FPDF($orientation,$unit,$size);
		// Initialisation
		$this->B = 0;
		$this->I = 0;
		$this->U = 0;
		$this->HREF = '';
	}

	function WriteHTML($html)
	{
		// Parseur HTML
		$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				// Texte
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,$e);
			}
			else
			{
				// Balise
				if($e[0]=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					// Extraction des attributs
					$a2 = explode(' ',$e);
					$tag = strtoupper(array_shift($a2));
					$attr = array();
					foreach($a2 as $v)
					{
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
							$attr[strtoupper($a3[1])] = $a3[2];
					}
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}

	function OpenTag($tag, $attr)
	{
		// Balise ouvrante
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF = $attr['HREF'];
		if($tag=='BR')
			$this->Ln(5);
	}

	function CloseTag($tag)
	{
		// Balise fermante
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF = '';
	}

	function SetStyle($tag, $enable)
	{
		// Modifie le style et sélectionne la police correspondante
		$this->$tag += ($enable ? 1 : -1);
		$style = '';
		foreach(array('B', 'I', 'U') as $s)
		{
			if($this->$s>0)
				$style .= $s;
		}
		$this->SetFont('',$style);
	}

	function PutLink($URL, $txt)
	{
		// Place un hyperlien
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}
	
	// ------------------------------------------------------ TABLEAUX: ---------------------------------------
	
	// Chargement des données
	function LoadData($file)
	{
		// Lecture des lignes du fichier
		$lines = file($file);
		$data = array();
		foreach($lines as $line)
			$data[] = explode(';',trim($line));
		return $data;
	}

	// Tableau simple
	function BasicTable($header, $data)
	{
		// En-tête
		foreach($header as $col)
			$this->Cell(40,7,$col,1);
		$this->Ln();
		// Données
		foreach($data as $row)
		{
			foreach($row as $col)
				$this->Cell(40,6,$col,1);
			$this->Ln();
		}
	}

	// Tableau amélioré
	function ImprovedTable($header, $data)
	{
		// Largeurs des colonnes
		$w = array(40, 35, 45, 40);
		// En-tête
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C');
		$this->Ln();
		// Données
		foreach($data as $row)
		{
			$this->Cell($w[0],6,$row[0],'LR');
			$this->Cell($w[1],6,$row[1],'LR');
			$this->Cell($w[2],6,number_format($row[2],0,',',' '),'LR',0,'R');
			$this->Cell($w[3],6,number_format($row[3],0,',',' '),'LR',0,'R');
			$this->Ln();
		}
		// Trait de terminaison
		$this->Cell(array_sum($w),0,'','T');
	}

	// Tableau coloré
	function FancyTable($header, $data)
	{
		// Couleurs, épaisseur du trait et police grasse
		$this->SetFillColor(235,40,82);
		$this->SetTextColor(255);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		// En-tête
		$w = array(40, 65, 65);
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
			$this->Ln();
			// Restauration des couleurs et de la police
			$this->SetFillColor(224,235,255);
			$this->SetTextColor(0);
			$this->SetFont('');
			// Données
			$fill = false;
			foreach($data as $row)
			{
				
				$this->SetFont('','B',15);
				$this->Cell($w[0],10,$row[0],'LR',0,'C',$fill);
				$this->SetFont('','',15);
				$this->Cell($w[1],10,$row[1],'LR',0,'C',$fill);
				$this->Cell($w[2],10,$row[2],'LR',0,'C',$fill);
				
				$this->Ln(10);
				$fill = !$fill;
			}
			// Trait de terminaison
			$this->Cell(array_sum($w),0,'','T');
		}
}
				
?>