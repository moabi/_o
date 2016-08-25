<?php
require('libs/fpdf181/fpdf.php');

class PDF extends FPDF {
	// En-tête
	function Header()
	{
	    // Logo
	    $this->Image('img/onlyoo-logo.png',10,6,30);
	    // Police Arial gras 15
	    $this->SetFont('Arial','B',15);
	    // Décalage à droite
	    $this->Cell(80);
	    // Titre
	    $this->Cell(30,10,'Onlyoo',1,0,'C');
	    // Saut de ligne
	    $this->Ln(20);
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
}

// Instanciation de la classe dérivée
$pdf = new PDF();
$pdf->SetTitle('Onlyoo',true);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica','',12);
//SET CONTENT
$pdf->MultiCell(0, 10, '<h1>Objectively fabricate</h1><p> highly efficient supply chains through compelling infomediaries. Proactively revolutionize premier collaboration and idea-sharing for client-centered scenarios. Seamlessly supply customer directed expertise after transparent applications.</p>', 0, 'L', false);

for($i=1;$i<=40;$i++){
	$pdf->Cell(0,10,'Impression de la ligne numéro '.$i,0,1);
}
    
    
    
$pdf->Output('I','Onlyoo',true);
