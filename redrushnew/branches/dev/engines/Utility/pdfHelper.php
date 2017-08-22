<?php
	/*
		PDF Helper
		Author Babar
		21/12/2011
	*/
set_time_limit(0);
ob_start();
global $ENGINE_PATH;
require_once $ENGINE_PATH.'Utility/tcpdf/config/lang/eng.php';
require_once $ENGINE_PATH.'Utility/tcpdf/tcpdf.php';

class pdfHelper{
	function writePDF($author,$title,$fontsize=10,$content,$create="I",$path=NULL){
		if(!empty($author) && !empty($title) && !empty($fontsize) && !empty($content)){
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			// set document information
			$pdf->SetAuthor($author);
			$pdf->SetTitle($title);
			// set header and footer fonts
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			
			//set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			
			//set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			
			//set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			
			//set some language-dependent strings
			$pdf->setLanguageArray($l);
			
			// ---------------------------------------------------------
			
			// set default font subsetting mode
			$pdf->setFontSubsetting(true);
			
			// Set font
			// dejavusans is a UTF-8 Unicode font, if you only need to
			// print standard ASCII chars, you can use core fonts like
			// helvetica or times to reduce file size.
			$pdf->SetFont('dejavusans', '', $fontsize, '', true);
			
			// Add a page
			// This method has several options, check the source code documentation for more information.
			$pdf->AddPage();
			$pdf->writeHTML($content, true, false, true, false, '');
			ob_end_clean();
			return $pdf->Output($path.$title.".pdf",$create);
		}
		else{
			echo '<script type="text/javascript">alert("Failed to create PDF!");</script>';
			return false;
		}
		//return $execute;
	}
}
?>