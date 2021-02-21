<?php
/*******************************************************************************
 *
 *  filename    : PrintViewKlaimAsuransi.php
 *  last change : 2003-01-29
 *  Copyright 
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
// require "Include/Header-Print.php";
// Get the Gol Darah ID from the querystring
$iBulan = FilterInput($_GET["Bulan"]);
$iTGL = FilterInput($_GET["TGL"]);
$iKodeTI = FilterInput($_GET["KodeTI"]);
$iNamaTI = FilterInput($_GET["NamaTI"]);
$iPukul = FilterInput($_GET["Pukul"]);
$iKaryawanID = FilterInput($_GET["KaryawanID"]);
$bln = $iBulan;
$today = date("Y-m-d");
if (strlen($iKodeTI>0)){$sSQLKodeTI =  " AND KodeTI = ".$iKodeTI ;$STRJudulTI="<br> Tempat Ibadah : ".$iNamaTI." ";}
if (strlen($iPukul>0)){$sSQLPukul =  " AND Pukul = '".$iPukul."'" ;$STRJudulPK="- Pukul : ".$iPukul." ";}
if (strlen($iKaryawanID>0)){$sSQLKaryawan =  " WHERE a.KaryawanID = '".$iKaryawanID."' " ;}else{$sSQLKaryawan =  " WHERE a.KaryawanID > 0 " ;}
if (strlen($iTGL>0)){$sSQLTGL =  $iTGL  ;}else{$sSQLTGL =  $today ;}
 
$hariini = strtotime(date("Ymd"));
$minggukemaren = strtotime('last Sunday', $hariini);
$minggudepan = strtotime('next Sunday', $hariini);



			

//echo date( 'Y-m-d', $hariini)."<br>";
//echo date( 'Y-m-d', $minggukemaren)."<br>";
//echo date( 'Y-m-d', $minggudepan);

$Judul = "Informasi Klaim Kesehatan Karyawan Tahunan ";
require "Include/Header-Report.php";


//Minggu Ini

				//$tanggal='2013-01-06';
				$tanggal=$sSQLTGL;
				
				$time = strtotime($tanggal);
				$yearago = date("Y-m-d", strtotime("-1 year", $time));
				$nextyear = date("Y-m-d", strtotime("+1 year", $time));
				//echo "<b>MINGGU INI</b>";
				echo "<table  cellpadding=\"0\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=900>";
				echo "<td align=\"left\">";
				echo "<a href=\"PrintViewKlaimAsuransi.php?KaryawanID=".$iKaryawanID . "&TGL=".$yearago."\"  >";
				echo "<< Tahun sebelumnya </a></td>";
				echo "<td align=\"center\"><font size=\"2\"><i>dilaporkan tanggal <b>".date2Ind($tanggal,2)."</b></i></font></td>";
				echo "<td align=\"right\">"; 
				echo "<a href=\"PrintViewKlaimAsuransi.php?KaryawanID=".$iKaryawanID . "&TGL=".$nextyear."\"  >";
				echo "Tahun Berikutnya >></a></td>";
				echo "</table>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=900>";


//	$sSQL1 = "SELECT Kelompok as NamaKelompok ,SUM(Bulanan) as SubTotalBulanan, SUM(Syukur) as SubTotalSyukur, SUM(ULK) as SubTotalULK 
//	FROM KlaimAsuransi WHERE Tanggal = '".$iTGL."' ".$sSQLKodeTI." ".$sSQLPukul." GROUP BY Kelompok" ;
//	$sSQL1 = "select a.PosAnggaranID , b.per_FirstName as NamaKaryawan ,c.* 
//				from PengeluaranKlaimAsuransi a 
//				LEFT JOIN person_per b ON a.PosAnggaranID = b.per_ID 
//				LEFT JOIN MasterAnggaranKlaimAsuransi c ON a.PosAnggaranID = c.KaryawanID 
//				".$sSQLKaryawan." AND (c.TahunAnggaran = YEAR('".$sSQLTGL."')  AND a.PosAnggaranID =c.KaryawanID ) 
//				GROUP BY a.PosAnggaranID ORDER BY NamaKaryawan ASC";

	$sSQL1 = "select a.*, b.per_Firstname as NamaKaryawan from MasterAnggaranKlaimAsuransi a 
		LEFT JOIN person_per b ON a.KaryawanID = b.per_ID 
		".$sSQLKaryawan." AND a.TahunAnggaran = YEAR('".$sSQLTGL."')  GROUP BY a.KaryawanID ORDER BY NamaKaryawan ASC
	   ";
//echo $sSQL1;echo "<br><br>";
	$rsJadwal1 = RunQuery($sSQL1);
	$a = 0;
	$GTBulanan=0;
	$GTSyukur=0;
	$GTULK=0;
    //Loop through the surat recordset
    while ($aRow = mysql_fetch_array($rsJadwal1))
    {
	$a++;
 
    extract($aRow);
    //Alternate the row style
    $sRowClass = AlternateRowStyle($sRowClass); 
	$GTBulanan+=$SubTotalBulanan;
	$GTSyukur+=$SubTotalSyukur;
	$GTULK+=$SubTotalULK;
		echo "<tr>";
		echo "<td bgcolor=\"#D5FEFE\" align=\"center\"  >".$KaryawanID."</td>";
		echo "<td bgcolor=\"#D5FEFE\" align=\"left\" colspan=\"3\"><b>".$NamaKaryawan."</b></td>";	
		echo "<td bgcolor=\"#D5FEFE\" align=\"right\" >Tahun : <b>".$TahunAnggaran."</b></td>";	
		echo "<td bgcolor=\"#D5FEFE\" align=\"right\" >Plafon : <b>".currency(' ',$Budget,'.',',00')."</b></td>";	
		
		echo "</tr>";
	 	
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td ><b>No#</b></td>";
				echo "<td ><b>Tanggal</b></td>";
				echo "<td  ><b>Pasien</b></td>";
				echo "<td  ><b>Rujukan</b></td>";
				echo "<td  ><b>Keluhan</b></td>";
				echo "<td  ><b>Jumlah</b></td>";
				echo "</tr>";
$sSQL2 = "select * from PengeluaranKlaimAsuransi 
WHERE PosAnggaranID = ".$KaryawanID." 
AND YEAR(Tanggal) = YEAR('".$sSQLTGL."') 
ORDER BY Tanggal ASC";
	   
//			echo $sSQL2;
		$rsJadwal2 = RunQuery($sSQL2);
		$i = 0;
		$JumlahTot=0;
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal2))
		

		
        {

			$i++;
			
            extract($aRow);
            //Alternate the row style
            $sRowClass = AlternateRowStyle($sRowClass); 
			echo "<tr>";
			echo "<td>".$i."</td>";
			echo "<td >".date2Ind($Tanggal,2)."</td>";
			echo "<td align=\"left\"><font size=\"2\">".$Pasien. "</font></td>";
			echo "<td align=\"left\"><font size=\"2\">".$Keterangan. "</font></td>";
			echo "<td align=\"left\"><font size=\"2\">".$DeskripsiKas. "</font></td>";
			echo "<td align=\"right\"><font size=\"2\">".currency(' ',$Jumlah,'.',',00'). "</font></td>";
			
			echo "</tr>";
			$JumlahTot=$JumlahTot+$Jumlah;
		}
			echo "<tr>";
			echo "<td></td>";			echo "<td></td>";			echo "<td></td>";			echo "<td></td>";
			echo "<td align=\"left\"><font size=\"2\"><b>Jumlah Total</b></font></td>";
			echo "<td align=\"right\"><font size=\"2\"><b>".currency(' ',$JumlahTot,'.',',00'). "</b></font></td>";
			echo "</tr>";	
			echo "<tr>";
			echo "<td></td>";			echo "<td></td>";			echo "<td></td>";			echo "<td></td>";
			$prosen=round(($JumlahTot/$Budget*100),2); 
			echo "<td align=\"left\"><font size=\"2\"><b>Prosentase</b></font></td>";
			echo "<td align=\"right\"><font size=\"2\"><b>".$prosen." %</b></font></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td></td>";			echo "<td></td>";			echo "<td></td>";			echo "<td></td>";
			$prosen=round(($JumlahTot/$Budget*100),2); 
			echo "<td align=\"left\"><font size=\"2\"><b>Sisa Plafon</b></font></td>";
			echo "<td align=\"right\"><font size=\"2\"><b>".currency(' ',$Budget-$JumlahTot,'.',',00'). "</b></font></td>";
			echo "</tr>";
	echo "<tr>";
//	echo "<td colspan=\"8\">.</td>";
	echo "</tr>";
	}

	echo "<tr>";
//	echo "<td colspan=\"8\"></td>";
	echo "</tr>";

	echo "</tbody></table>";
	
				
?>
.				

	

<?php
require "Include/Footer-Short.php";
?>
