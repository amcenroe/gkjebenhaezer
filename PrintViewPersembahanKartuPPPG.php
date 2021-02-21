<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPersembahanKartuPPPG.php
 *  last change : 2003-01-29
 *
 *  Copyright 
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
$filename="PrintViewPersembahanKartuPPPG.php";
require "Include/Config.php";
require "Include/Functions.php";
// require "Include/Header-Print.php";
// Get the Gol Darah ID from the querystring
$iBulan = FilterInput($_GET["Bulan"]);

$Tahunan = FilterInput($_GET["Tahunan"]);
$iTGL = FilterInput($_GET["TGL"]);
$iPos = FilterInput($_GET["Pos"]);
$iKodeTI = FilterInput($_GET["KodeTI"]);
$iNamaTI = FilterInput($_GET["NamaTI"]);
$iPukul = FilterInput($_GET["Pukul"]);
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

if (strlen($iKodeTI>0)){$sSQLKodeTI =  " AND KodeTI = ".$iKodeTI ;$STRJudulTI="<br> Tempat Ibadah : ".$iNamaTI." ";}
if (strlen($iPukul>0)){$sSQLPukul =  " AND Pukul = '".$iPukul."'" ;$STRJudulPK="- Pukul : ".$iPukul." ";}
			


$Judul = "Informasi Rekapitulasi Persembahan PPPG <br>Hari: ".date2Ind($iTGL,1)." ".$STRJudulTI."".$STRJudulPK; 

$iHeader = FilterInput($_GET["Header"]);
if (strlen($iHeader==""))
{
require "Include/Header-Report.php";
}else if (strlen($iHeader=="0"))
{
require "Include/HeaderNone-Report.php";
}

?>
	<table  cellpadding=0 border=0 cellpadding=0 cellspacing=0 width=700>
	<tr>
	<td align="left">
	<?php
	$sSQL0 = "SELECT Tanggal, NamaJenis FROM PersembahanPPPG a
			LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis
			WHERE Tanggal < '".$iTGL."' AND Pos=".$iPos."  ORDER BY Tanggal Desc LIMIT 1 ";
			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL0);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
				$i++;
				extract($aRow);
				echo "<a href=\"".$filename."?Pos=".$iPos."&TGL=".$Tanggal."\"  ><p class=\"help\" title=\"Data Sebelumnya Tgl: ".date2Ind($Tanggal,2)." \">";
				echo "<<  </a>";
			}
				
	?>
	</td>
	<td><i style="font-family: Arial;"></i></td>
	<td align="right">
	<?php
	$sSQL0 = "SELECT Tanggal, NamaJenis FROM PersembahanPPPG a
			LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis
			WHERE Tanggal > '".$iTGL."'  AND Pos=".$iPos." ORDER BY Tanggal Asc LIMIT 1 ";
			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL0);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
				$i++;
				extract($aRow);
				echo "<a href=\"".$filename."?Pos=".$iPos."&TGL=".$Tanggal."\"  ><p class=\"help\" title=\"Data Berikutnya Tgl: ".date2Ind($Tanggal,2)." \">";
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
if ($iPos==3){
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=650 bgcolor=\"#FFFFFF\">";
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td colspan=\"4\" ><b> </b></td>";
				//echo "<td rowspan=\"2\" ><b>Bulan</b></td>";
				echo "<td colspan=\"2\" ><b>Persembahan PPPG</b></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td><b>Urut</b></td><td><b>Tempat Ibadah</b></td><td><b>Pukul</b><td><b>Jumlah Amplop</b></td>";
				echo "<td colspan=\"2\" ><b>".$NamaJenis."</b></td>";
				echo "</tr>";
}else{				
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=650 bgcolor=\"#FFFFFF\">";
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td colspan=\"4\" ><b>Nomor</b></td>";
				echo "<td rowspan=\"2\" ><b>";
				
				if ($Tahunan=='Y'){
				echo "<a href=\"".$filename."?Pos=".$iPos."&TGL=".$iTGL."\"  ><p class=\"help\" title=\"Lihat Data Bulanan\">";
				echo "Bulan</a>";
				}else{
				//$Tahunan='Y';
				echo "<a href=\"".$filename."?Pos=".$iPos."&TGL=".$iTGL."&Tahunan=Y\"  ><p class=\"help\" title=\"Lihat Data Tahunan\">";
				echo "Bulan</a>";				
				}
				
				
				echo "</b></td>";
				echo "<td colspan=\"2\" ><b>Persembahan PPPG</b></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td><b>Urut</b></td><td><b>Kelompok</b></td><td><b>No.Kartu</b><td><b>Kode</b></td>";
				echo "<td colspan=\"2\" ><b>".$NamaJenis."</b></td>";
				echo "</tr>";
}

if ($iPos==3){
	$sSQL1 = "SELECT KodeTI, Pukul ,SUM(Bulanan) as SubTotalBulanan
	FROM PersembahanPPPG WHERE Tanggal = '".$iTGL."' AND Pos=".$iPos." GROUP BY Pukul ORDER BY KodeTI,Pukul" ;
}else{
	if ($Tahunan=='Y'){
	$sSQL1 = "SELECT Kelompok as NamaKelompok ,SUM(Bulanan) as SubTotalBulanan
	FROM PersembahanPPPG WHERE YEAR(Tanggal) = YEAR('".$iTGL."') ".$sSQLKodeTI." ".$sSQLPukul." AND Pos=".$iPos." GROUP BY Kelompok" ;
	}else{
	$sSQL1 = "SELECT Kelompok as NamaKelompok ,SUM(Bulanan) as SubTotalBulanan
	FROM PersembahanPPPG WHERE Tanggal = '".$iTGL."' ".$sSQLKodeTI." ".$sSQLPukul." AND Pos=".$iPos." GROUP BY Kelompok" ;
	
	}

}
	//	echo $sSQL1;
	$rsJadwal1 = RunQuery($sSQL1);
	$a = 0;
	$GTBulanan=0;
	$GTKartu=0;
    //Loop through the surat recordset
    while ($aRow = mysql_fetch_array($rsJadwal1))
    {
	$a++;

    extract($aRow);
    //Alternate the row style
    $sRowClass = AlternateRowStyle($sRowClass); 
	$GTBulanan+=$SubTotalBulanan;
	if ($iPos==3){
		$sSQL2 = "SELECT a.* ,b.NamaTI as NamaTI FROM PersembahanPPPG a
		LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
		WHERE Tanggal = '".$iTGL."' AND Pukul = '".$Pukul."' AND Pos=".$iPos."  ORDER BY KodeTI,Pukul   ";
	}else{

		if ($Tahunan=='Y'){
		$sSQL2 = "SELECT * FROM PersembahanPPPG WHERE YEAR(Tanggal) = YEAR('".$iTGL."') AND Kelompok = '".$NamaKelompok."'  ".$sSQLKodeTI." ".$sSQLPukul."  AND Pos=".$iPos."     ";
		}else{
		$sSQL2 = "SELECT * FROM PersembahanPPPG WHERE Tanggal = '".$iTGL."' AND Kelompok = '".$NamaKelompok."'  ".$sSQLKodeTI." ".$sSQLPukul."  AND Pos=".$iPos."     ";
		
		}
	}
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
			if ($iPos==3){
			echo "<tr>";
			echo "<td> ".$i." </td>";
			echo "<td> ".$NamaTI." </td>";
			echo "<td> ".$Pukul." </td>";
			echo "<td> ".$JmlAmplop." </td>";
			//echo "<td> ".$Bulan1;if (strlen($Bulan2>0)){echo " - ".$Bulan2;};echo " </td>";
			echo "<td align=\"right\"> ".currency(' ',$Bulanan,'.',',00')." </td><td></td>";
			echo "</tr>";
			$GTKartu+=$JmlAmplop;
			}else{
			echo "<tr>";
			echo "<td> ".$i." </td>";
			echo "<td> ".$Kelompok." </td>";
			echo "<td> ";if ($NomorKartu!=''){echo $NomorKartu;}else{ echo '-';};echo " </td>";
			echo "<td> ";if ($KodeNama!=''){echo $KodeNama;}else{ echo '-';};echo " </td>";
			echo "<td> ".$Bulan1;if (strlen($Bulan2>0)){echo " - ".$Bulan2;};echo " </td>";
				if ($iPos==2){
				echo "<td align=\"right\"> ".currency(' ',$Bulanan,'.',',00')." </td><td align=\"left\" > ".date2Ind($Tanggal,1)."</td>";
				}else{
				echo "<td align=\"right\"> ".currency(' ',$Bulanan,'.',',00')." </td><td></td>";
				}
			echo "</tr>";
			}
		}
		if ($iPos!=3){
			echo "<tr>";
			echo "<td colspan=\"5\"> Jumlah ( ".$i." Kartu/Amplop )</td>";
			echo "<td></td><td align=\"right\"><b> ".currency(' ',$SubTotalBulanan,'.',',00')."</b> </td>";
			echo "</tr>";$GTKartu+=$i;
			}
	}
if ($iPos==3){
	echo "<tr>";
	echo "<td colspan=\"3\"><b> TOTAL  <b></td><td><b> ".$GTKartu." </b></td>";
	echo "<td align=\"right\"><b> ".currency(' ',$GTBulanan,'.',',00')."</b> </td>";
	echo "</tr>";
}else{
	echo "<tr>";
	echo "<td colspan=\"5\"><b> TOTAL PERSEMBAHAN ( ".$GTKartu." Kartu/Amplop ) </b></td>";
	echo "<td></td><td align=\"right\"><b> ".currency(' ',$GTBulanan,'.',',00')."</b> </td>";
	echo "</tr>";
}
	echo "</tbody></table>";
	
				
?>
.				

	

<?php
require "Include/Footer-Short.php";
?>
