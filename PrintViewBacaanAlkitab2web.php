<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPelayanPendukung2web.php
 *  last change : 2003-01-29
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2003 Phillip Hullquist, Deane Barker, Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
//require "Include/Config.php";
require "Include/ConfigWeb.php";
//require "Include/Functions.php";
// require "Include/Header-Print.php";
// Get the Gol Darah ID from the querystring
$iBulan = FilterInput($_GET["Bulan"]);
$iTGL = FilterInput($_GET["TGL"]);
$bln = $iBulan;

 
$hariini = strtotime(date("Ymd"));
$minggukemaren = strtotime('last Sunday', $hariini);
$minggudepan = strtotime('next Sunday', $hariini);


if ($iTGL==''){$iTGL=date( 'Y-m-d');}

			

//echo date( 'Y-m-d', $hariini)."<br>";
//echo date( 'Y-m-d', $minggukemaren)."<br>";
//echo date( 'Y-m-d', $minggudepan);

 if (( $bln == 1 )) {
	     $BULAN="Januari";
	    } elseif (( $bln == 2 )) {
	      $BULAN="Februari";
	    } elseif (( $bln == 3 )) {
	      $BULAN="Maret";
	    } elseif (( $bln == 4 )) {
	      $BULAN="April";
	    } elseif (( $bln == 5 )) {
	      $BULAN="Mei";
	    } elseif (( $bln == 6 )) {
	      $BULAN="Juni";
	    } elseif (( $bln == 7 )) {
	      $BULAN="Juli";
	    } elseif (( $bln == 8 )) {
	      $BULAN="Agustus";
	 	} elseif (( $bln == 9 )) {
	      $BULAN="September";
	    } elseif (( $bln == 10 )) {
	      $BULAN="Oktober";
	    } elseif (( $bln == 11 )) {
	      $BULAN="Nopember";
	    } elseif (( $bln == 12 )) {
	      $BULAN="Desember";
	    }

$Judul = "Informasi Daftar Bacaan Alkitab - ".date2Ind($iTGL,1); 
//require "Include/Header-Report.php";
?>
<link rel="stylesheet" type="text/css" href="Include/Style.css">
<?

//Minggu Ini

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<p><b>MINGGU INI</b></p>";
				echo "<table  cellpadding=\"2\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody  align=\"center\" ><tr>";
				echo "<td><p><b>Hari & Tanggal</b></p></td>";
				echo "<td><p><b>Bacaan I</b></p></td>";
				echo "<td><p><b>Mazmur</b></p></td>";
				echo "<td><p><b>Bacaan II</b></p></td>";
				echo "<td><p><b>Injil</b></p></td>";
				echo "</tr>";
							
				       $sSQL2 = "SELECT * FROM BacaanAlkitab WHERE WEEK(Tanggal) = WEEK('".$iTGL."') ORDER BY Tanggal ASC";
			//		         $sSQL2 = "SELECT * FROM BacaanAlkitab WHERE WEEK(Tanggal) = WEEK(curdate())";
//	echo $sSQL2;
	$rsJadwal2 = RunQuery($sSQL2);
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal2))
        {
                extract($aRow);
                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass); 
echo "<tr>";
echo "<td><p>".date2Ind($Tanggal,1)."</p></td>";
echo "<td><p><a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $BacaanI . "&mode=print\" style=\"text-decoration:none;\">".$BacaanI."</a></p></td>";
echo "<td><p><a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $Mazmur . "&mode=print\" style=\"text-decoration:none;\">".$Mazmur."</a></p></td>";
echo "<td><p><a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $BacaanII . "&mode=print\" style=\"text-decoration:none;\">".$BacaanII."</a></p></td>";
echo "<td><p><a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $Injil . "&mode=print\" style=\"text-decoration:none;\">".$Injil."</a></p></td>";
echo "</tr>";
}		
						
			echo "</tbody></table>";

//Minggu Depan

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<br><p><b>MINGGU DEPAN</b></p>";
				echo "<table  cellpadding=\"2\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody  align=\"center\" ><tr>";
				echo "<td><p><b>Hari & Tanggal</b></p></td>";
				echo "<td><p><b>Bacaan I</b></p></td>";
				echo "<td><p><b>Mazmur</b></p></td>";
				echo "<td><p><b>Bacaan II</b></p></td>";
				echo "<td><p><b>Injil</b></p></td>";
				echo "</tr>";
							
				  //     $sSQL2 = "SELECT * FROM BacaanAlkitab WHERE WEEK(Tanggal) = WEEK(curdate())+1";
					   $sSQL2 = "SELECT * FROM BacaanAlkitab WHERE WEEK(Tanggal) = WEEK('".$iTGL."')+1 ORDER BY Tanggal ASC";
//	echo $sSQL2;
	$rsJadwal2 = RunQuery($sSQL2);
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal2))
        {
                extract($aRow);
                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass); 
echo "<tr>";
echo "<td><p>".date2Ind($Tanggal,1)."</p></td>";
echo "<td><p><a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $BacaanI . "&mode=print\" style=\"text-decoration:none;\">".$BacaanI."</a></p></td>";
echo "<td><p><a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $Mazmur . "&mode=print\" style=\"text-decoration:none;\">".$Mazmur."</a></p></td>";
echo "<td><p><a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $BacaanII . "&mode=print\" style=\"text-decoration:none;\">".$BacaanII."</a></p></td>";
echo "<td><p><a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $Injil . "&mode=print\" style=\"text-decoration:none;\">".$Injil."</a></p></td>";
echo "</tr>";
		}		
						
			echo "</tbody></table>";
	

				
	
//require "Include/Footer-Short.php";
?>
