<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPersembahanBulanan.php
 *  last change : 2003-01-29
 *
 *  Copyright 
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
$filename="PrintViewPersembahanBulanan.php";
require "Include/Config.php";
require "Include/Functions.php";
// require "Include/Header-Print.php";
// Get the Gol Darah ID from the querystring
$iBulan = FilterInput($_GET["Bulan"]);
$iTGL = FilterInput($_GET["TGL"]);
$iKodeTI = FilterInput($_GET["KodeTI"]);
$iNamaTI = FilterInput($_GET["NamaTI"]);
$iPukul = FilterInput($_GET["Pukul"]);
$iScroll = FilterInput($_GET["Scroll"]);

$bln = $iBulan;

if (strlen($iTGL>0))
{
$iTGL = FilterInput($_GET["TGL"]);
$minggukemaren = date("Y-m-d", strtotime('last Sunday', strtotime($iTGL)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', strtotime($iTGL)));
}else
{
$hariini = strtotime(date("Y-m-d"));
$iTGL = date("Y-m-d", strtotime('last Sunday', $hariini));
$mingguterakhir = date("Y-m-d", strtotime('last Sunday', $hariini));
$minggukemaren = date("Y-m-d", strtotime('-1 week', strtotime($mingguterakhir)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', $hariini));
//echo date("Y-m-d", $hariini);
//echo "<br>";
//echo $mingguterakhir;
//echo "<br>";
//echo $minggukemaren;
//echo "<br>";
//echo $minggudepan;

}

if (strlen($iKodeTI>0)){$sSQLKodeTI =  " AND KodeTI = ".$iKodeTI ;$STRJudulTI="<br> Tempat Ibadah : ".$iNamaTI." ";};
if (strlen($iPukul>0)){$sSQLPukul =  " AND Pukul = '".$iPukul."'" ;$STRJudulPK="- Pukul : ".$iPukul." ";};



$Judul = "Informasi Rekapitulasi Persembahan Bulanan <br>Hari: ".date2Ind($iTGL,1)." ".$STRJudulTI."".$STRJudulPK; 
require "Include/Header-Report.php";


?>
<script language="javascript">
function pageScroll() {
    	window.scrollBy(0,10); // horizontal and vertical scroll increments
    	scrolldelay = setTimeout('pageScroll()',500); // scrolls every 100 milliseconds
}
<?
if ($iScroll==1){
echo "window.onload = pageScroll;";
}
?>
</script>

	<table  cellpadding=0 border=0 cellpadding=0 cellspacing=0 width=700>
	<tr>
	<td align="left">
	<?php
	$sSQL0 = "SELECT Tanggal FROM PersembahanBulanan
			WHERE Tanggal < '".$iTGL."'  ORDER BY Tanggal Desc LIMIT 1 ";
			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL0);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
				$i++;
				extract($aRow);
				echo "<a href=\"".$filename."?TGL=".$Tanggal."\"  ><p class=\"help\" title=\"Data Sebelumnya Tgl: ".date2Ind($Tanggal,2)." \">";
				echo "<<  </a>";
			}
				
	?>
	</td>
	<td><i style="font-family: Arial;"></i></td>
	<td align="right">
	<?php
	$sSQL0 = "SELECT Tanggal FROM PersembahanBulanan
			WHERE Tanggal > '".$iTGL."'  ORDER BY Tanggal Asc LIMIT 1 ";
			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL0);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
				$i++;
				extract($aRow);
				echo "<a href=\"".$filename."?TGL=".$Tanggal."\"  ><p class=\"help\" title=\"Data Berikutnya Tgl: ".date2Ind($Tanggal,2)." \">";
				echo ">>  </a>";
			}
				
	?>
	</td>
	</tr>
	</table>
<?php

//Minggu Ini

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				//echo " <b>MINGGU INI</b> ";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=650 bgcolor=\"#FFFFFF\">";
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td colspan=\"3\" ><b>Nomor</b></td>";
				echo "<td rowspan=\"2\" ><b>Bulan</b></td>";
				echo "<td colspan=\"3\" ><b>Persembahan</b></td>";
				echo "<td rowspan=\"2\" ><b>Jumlah Rupiah</b></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td><b>Urut</b></td><td><b>Kelompok</b></td><td><b>Kartu</b></td>";
				echo "<td><b>Bulanan</b></td><td><b>Syukur</b></td><td><b>ULK</b></td>";
				echo "</tr>";
	
	$sSQL1 = "SELECT Kelompok as NamaKelompok ,SUM(Bulanan) as SubTotalBulanan, SUM(Syukur) as SubTotalSyukur, SUM(ULK) as SubTotalULK 
	FROM PersembahanBulanan WHERE Tanggal = '".$iTGL."' ".$sSQLKodeTI." ".$sSQLPukul." ".$sSQLKelompok." GROUP BY Kelompok" ;

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
							
		$sSQL2 = "SELECT a.*, b.per_FirstName as NamaWarga FROM PersembahanBulanan a
		LEFT JOIN person_per b ON a.NomorKartu = b.per_ID
		WHERE Tanggal = '".$iTGL."' AND Kelompok = '".$NamaKelompok."'  ".$sSQLKodeTI." ".$sSQLPukul."      ";
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


			//KodeNamaWarga
			$string = $NamaWarga;
			//echo $NamaWarga;
			$words = explode(' ', $string);
			//var_dump($words);

			$to_keep = array_slice($words, 0, 3);
			//var_dump($to_keep);

			$final_string = implode(' ', $to_keep);

			//Kode Nama
			if ($to_keep[0]=="I"){
			$KodeNama = strtoupper(substr($to_keep[1], 0, 2));
			$KodeNama2 =   strtoupper(substr($to_keep[2], 0, 2));

			}else{
					$KodeNama = strtoupper(substr($to_keep[0], 0, 2));

					if ($to_keep[1]<>""){
						$KodeNama2 = strtoupper(substr($to_keep[1], 0, 2));
						} else {
						$KodeNama2 =   strtoupper(substr($to_keep[0], -2));
						}
				}





			echo "<tr>";
			echo "<td> ".$i." </td>";
			echo "<td> ".$Kelompok." </td>";
		//	echo "<td> ".$NomorKartu;if (isset($KodeNama)){echo $KodeNama $KodeNama2;}else{echo $KodeNama $KodeNama2;};echo " </td>";
			echo "<td> ".$NomorKartu." ".$KodeNama."".$KodeNama2."</td>";
			echo "<td> ".$Bulan1;if (strlen($Bulan2>0)){echo " - ".$Bulan2;};echo " </td>";
			echo "<td align=\"right\"> ".currency(' ',$Bulanan,'.',',00')." </td>";
			echo "<td align=\"right\"> ".currency(' ',$Syukur,'.',',00')." </td>";
			echo "<td align=\"right\"> ".currency(' ',$ULK,'.',',00')." </td>";
			$Total=$Bulanan+$Syukur+$ULK;
			echo "<td align=\"right\"> ".currency(' ',$Total,'.',',00')." </td>";	
			echo "</tr>";
		}
	echo "<tr>";
	echo "<td colspan=\"4\"> Jumlah </td>";
	echo "<td align=\"right\"><b> ".currency(' ',$SubTotalBulanan,'.',',00')."</b> </td>";
	echo "<td align=\"right\"><b> ".currency(' ',$SubTotalSyukur,'.',',00')."</b> </td>";
	echo "<td align=\"right\"><b> ".currency(' ',$SubTotalULK,'.',',00')."</b> </td>";
	$SubTotal=$SubTotalBulanan+$SubTotalSyukur+$SubTotalULK;
	echo "<td align=\"right\"><b> ".currency(' ',$SubTotal,'.',',00')."</b> </td>";	
	echo "</tr>";
	}

	echo "<tr>";
	echo "<td colspan=\"4\"><b> TOTAL PERSEMBAHAN </b></td>";
	echo "<td align=\"right\"><b> ".currency(' ',$GTBulanan,'.',',00')."</b> </td>";
	echo "<td align=\"right\"><b> ".currency(' ',$GTSyukur,'.',',00')."</b> </td>";
	echo "<td align=\"right\"><b> ".currency(' ',$GTULK,'.',',00')."</b> </td>";
	$GrandTotal=$GTBulanan+$GTSyukur+$GTULK;
	echo "<td align=\"right\"><b> ".currency(' ',$GrandTotal,'.',',00')."</b> </td>";	
	echo "</tr>";

	echo "</tbody></table>";
	
				
?>
.				

	

<?php
require "Include/Footer-Short.php";
?>
