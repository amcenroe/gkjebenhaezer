<?php
/*******************************************************************************
 *
 *  filename    : PrintView.php
 *  last change : 2003-01-29
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2003 Phillip Hullquist, Deane Barker, Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";

$iStatus = FilterInput($_GET["Status"]);

// test the table functions
error_reporting(E_ALL);
include('class.ezpdf.php');



$pdf =& new Cezpdf('a4','landscape');
$pdf->selectFont('Include/ezpdf/fonts/Helvetica');



$pdf->ezText('Testing');

?>






			<?php
				$data = array();
				$query = "SELECT per_FirstName as 'Nama Lengkap', fam_HomePhone as 'Telp Rumah', per_Cellphone as Handphone, a1.lst_OptionName as 'Status Keluarga',
							fam_Address1 as 'Alamat Lengkap', a2.lst_OptionName as 'Status Warga'
						FROM person_per
						LEFT JOIN family_fam ON person_per.per_fam_ID = family_fam.fam_ID
						LEFT JOIN list_lst as a1 ON a1.lst_OptionID = person_per.per_fmr_ID AND a1.lst_ID = 2
						LEFT JOIN list_lst as a2 ON a2.lst_OptionID = person_per.per_cls_ID AND a2.lst_ID = 1
						WHERE per_WorkPhone like '%$iStatus%'
						ORDER BY family_fam.fam_Name, person_per.per_fmr_ID, person_per.per_BirthYear";

					$data = array();
					$result = mysql_query($query);
					while($data[] = mysql_fetch_array($result, MYSQL_ASSOC)) {}

					 // make the table

					 $JudulTabel = "Data Warga kelompok $iStatus " ;
					 $pdf->ezTable($data,'',  $JudulTabel);
					 $image="gkj_logo.jpg";
					 $pdf->ezImage($image,20,20,'none','left');

					if (isset($d) && $d){
					 $pdfcode = $pdf->output(1);
					 $pdfcode = str_replace("\n","\n<br>",htmlspecialchars($pdfcode));
					 echo '<html><body>';
					 echo trim($pdfcode);
					 echo '</body></html>';
					} else {


					$pdf->stream();
					}



?>


