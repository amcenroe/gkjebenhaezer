<?php
/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */

    // get the HTML
    ob_start();
    include(dirname(__FILE__).'/PrintViewMajelis.php');
    $content = ob_get_clean();

    // convert in PDF
    require_once(dirname(__FILE__).'/Include/html2pdf.class.php');
    try
    {
	

	
	
        $html2pdf = new HTML2PDF('P', 'A4', 'en');
//      $html2pdf->setModeDebug();
//        $html2pdf->setDefaultFont('Arial');

//$dummy2 = AdjustHTML($content);

        $html2pdf->writeHTML($content);
		//output to file
        $html2pdf->Output('exemple00.pdf');
		//output to browser
		//$html2pdf->Output();
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
