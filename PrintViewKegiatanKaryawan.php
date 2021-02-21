<?php
/*******************************************************************************
 *
 *  filename    : PrintViewKegiatanKaryawan.php
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

if (strlen($iKodeTI>0)){$sSQLKodeTI =  " AND KodeTI = ".$iKodeTI ;$STRJudulTI="<br> Tempat Ibadah : ".$iNamaTI." ";}
if (strlen($iPukul>0)){$sSQLPukul =  " AND Pukul = '".$iPukul."'" ;$STRJudulPK="- Pukul : ".$iPukul." ";}
if (strlen($iKaryawanID>0)){$sSQLKaryawan =  " WHERE KaryawanID = '".$iKaryawanID."' " ;}
if (strlen($iTGL>0)){$sSQLTGL =  " AND MONTH(Tanggal) = MONTH('".$iTGL."') " ;}else{$iTGL=date('Y-m-d');}
 
$hariini = strtotime(date("Ymd"));
$minggukemaren = strtotime('last Sunday', $hariini);
$minggudepan = strtotime('next Sunday', $hariini);



			

//echo date( 'Y-m-d', $hariini)."<br>";
//echo date( 'Y-m-d', $minggukemaren)."<br>";
//echo date( 'Y-m-d', $minggudepan);

$Judul = "Informasi Kegiatan Karyawan Bulanan ";
require "Include/Header-Report.php";


//Minggu Ini

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				
				$time = strtotime($tanggal);
				$monthago = date("Y-m-d", strtotime("-1 month", $time));
				$nextmonth = date("Y-m-d", strtotime("+1 month", $time));
				//echo "<b>MINGGU INI</b>";
				echo "<table  cellpadding=\"0\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=900>";
				echo "<td align=\"left\">";
				echo "<a href=\"PrintViewKegiatanKaryawan.php?KaryawanID=".$iKaryawanID . "&TGL=".$monthago."\"  >";
				echo "<< Bulan sebelumnya </a></td>";
				echo "<td align=\"center\"><font size=\"3\"><b>".date2Ind($iTGL,5)."</b></font></td>";
				echo "<td align=\"right\">"; 
				echo "<a href=\"PrintViewKegiatanKaryawan.php?KaryawanID=".$iKaryawanID . "&TGL=".$nextmonth."\"  >";
				echo "Bulan Berikutnya >></a></td>";
				echo "</table>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=900>";


//	$sSQL1 = "SELECT Kelompok as NamaKelompok ,SUM(Bulanan) as SubTotalBulanan, SUM(Syukur) as SubTotalSyukur, SUM(ULK) as SubTotalULK 
//	FROM KegiatanKaryawan WHERE Tanggal = '".$iTGL."' ".$sSQLKodeTI." ".$sSQLPukul." GROUP BY Kelompok" ;
	$sSQL1 = "select a.KaryawanID, b.per_FirstName as NamaKaryawan from Kegiatangkjbekti a
		LEFT JOIN person_per b ON a.KaryawanID = b.per_ID
	   ".$sSQLKaryawan." 
	   GROUP BY KaryawanID ORDER BY NamaKaryawan ASC
	   ";
	//	echo $sSQL1;
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
		echo "<td bgcolor=\"#D5FEFE\"  >".$KaryawanID."</td>";
		echo "<td bgcolor=\"#D5FEFE\" align=\"left\" colspan=\"10\"><b>".$NamaKaryawan."</b></td>";	
		echo "</tr>";
	 	
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td rowspan=\"2\" ><b>No#</b></td>";
				echo "<td rowspan=\"2\" ><b>Tanggal</b></td>";
				echo "<td colspan=\"2\" ><b>Jam</b></td>";
				echo "<td colspan=\"3\" ><b>Durasi</b></td>";
				echo "<td rowspan=\"2\" ><b>Tempat Kegiatan</b></td>";
				echo "<td rowspan=\"2\" ><b>Nama Kegiatan</b></td>";
				echo "<td rowspan=\"2\" ><b>Keterangan</b></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td><b>Mulai</b></td><td><b>Selesai</b></td><td><b>hari<b></td><td><b>jam</b></td><td><b>menit</b></td>";
				//echo "<td><b>Bulanan</b></td><td><b>Syukur</b></td><td><b>ULK</b></td>";
				echo "</tr>";
		
							
	$sSQL2 = "select a.*, b.per_FirstName as NamaKaryawan , c.* from Kegiatangkjbekti a
	   LEFT JOIN person_per b ON a.KaryawanID = b.per_ID
	   LEFT JOIN LokasiTI c ON a.KodeTI = c.KodeTI
	   WHERE KaryawanID=".$KaryawanID." ".$sSQLTGL." 
	   ORDER BY TanggalMulai ASC, JamMulai ASC
	   ";
	   
		//	echo $sSQL2;
		$rsJadwal2 = RunQuery($sSQL2);
		$i = 0;
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal2))
		

		
        {

			$i++;
            extract($aRow);
            //Alternate the row style
            $sRowClass = AlternateRowStyle($sRowClass); 
			echo "<tr>";
			echo "<td>".$i."</td>";
			echo "<td >".date2Ind($TanggalMulai,4)."</td>";
			echo "<td><font size=\"1\">".$JamMulai. "</font></td>";
			echo "<td><font size=\"1\">".$JamSelesai. "</font></td>";
			echo "<td  align=\"centre\">";
						
				$JamMulaiPecah = explode(" ", $JamMulai);
				$JamMulaiPecah1 = $JamMulaiPecah[0];
				$JamMulaiPecah2 = str_replace('.', ':', $JamMulaiPecah1);
		
				//$start = new DateTime($TanggalMulai . " " . $JamMulaiPecah2);
				$start = $TanggalMulai . " " . $JamMulaiPecah2;
				//echo $start;
		
				$JamSelesaiPecah = explode(" ", $JamSelesai);
				$JamSelesaiPecah1 = $JamSelesaiPecah[0];
				$JamSelesaiPecah2 = str_replace('.', ':', $JamSelesaiPecah1);
		
				//$end = new DateTime($TanggalSelesai . " " . $JamSelesaiPecah2);
				$end = $TanggalSelesai . " " . $JamSelesaiPecah2;
				//echo $end;
				//echo "<br>";
				if($JamMulai==0 || $JamSelesai==0 ){echo " 0 </td><td align=\"centre\"> 0 </td><td  align=\"centre\" > 0 ";}else{
		        $intervalo = date_diff(date_create($start), date_create($end));
				$out = $intervalo->format("%d </td><td align=\"centre\"> %h </td><td  align=\"centre\" > %i ");
				echo $out;
				}
			
			echo "</td>";
			echo "<td align=\"center\">".$NamaTI."</td>";
			echo "<td align=\"left\">".$NamaKegiatan."</td>";
			echo "<td align=\"left\">".$Keterangan."</td>";
			echo "</tr>";
		}
	echo "<tr>";
	echo "<td colspan=\"8\"></td>";
	echo "</tr>";
	}

	echo "<tr>";
	echo "<td colspan=\"8\"></td>";
	echo "</tr>";

	echo "</tbody></table>";
	
				
?>
.				

	

<?php
require "Include/Footer-Short.php";
?>
