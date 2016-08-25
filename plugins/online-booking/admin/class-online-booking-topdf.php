<?php
// include autoloader
require_once 'libs/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

/*
 * ob_generate_pdf
 * 
 *
 * @param $text string (html)
 * @param $name string
 * @return pdf
*/
function ob_generate_pdf($text,$name){
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$text = '';
	$dompdf->loadHtml($text);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$output = $dompdf->output();
	file_put_contents('pdf/'.$name.'.pdf', $output);
    
	// Output the generated PDF to Browser
	//$dompdf->stream();

}




