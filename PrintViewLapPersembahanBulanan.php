<?php
/*******************************************************************************
 *
 *  filename    : PrintViewLapPersembahanBulanan.php
 *  last change : 2013-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
$filename="PrintViewLapPersembahanBulanan.php";
require "Include/Config.php";
require "Include/Functions.php";

$Kode2Komisi=array();
$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;

// require "Include/Header-Print.php";
// Get the Gol Darah ID from the querystring
$iJenisLap = FilterInput($_GET["JenisLap"]);
$iBulan = FilterInput($_GET["Bulan"]);
$iTGL = FilterInput($_GET["TGL"]);
$iPOSID = FilterInput($_GET["POSID"]);
$iBIDID = FilterInput($_GET["BIDID"]);
$iKOMID = FilterInput($_GET["KOMID"]);
$iKodeTI = FilterInput($_GET["KodeTI"]);
$iNamaTI = FilterInput($_GET["NamaTI"]);
$iPukul = FilterInput($_GET["Pukul"]);
$bln = $iBulan;

if (strlen($iTGL>0))
{
$iTGLnow=date(Y);
$sSQLTanggal =  " WHERE MONTH(a.Tanggal) = ".date(n,strtotime($iTGL))." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;
$sSQLTanggal2 =  " AND MONTH(a.Tanggal) = ".date(n,strtotime($iTGL))." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;
$sSQLTanggal3 =  " AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;
$sSQLTanggal4 =  "  = ".date(Y,strtotime($iTGL)) ;
$sSQLTanggal5 =  "  YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;

$minggukemaren = date("Y-m-d", strtotime('last Sunday', strtotime($iTGL)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', strtotime($iTGL)));
	$tanggal=$iTGL;
	$time = strtotime($tanggal);
	$monthago = date("Y-m-d", strtotime("-1 month", $time));
	$nextmonth = date("Y-m-d", strtotime("+1 month", $time));
			$yearago = date("Y-m-d", strtotime("-1 year", $time));
			$nextyear = date("Y-m-d", strtotime("+1 year", $time));	
}else
{
$iTGLnow=date(Y);
$sSQLTanggal =  " WHERE MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow)) ;
$sSQLTanggal2 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
$sSQLTanggal3 =  " AND YEAR(a.Tanggal) = ".$iTGLnow  ;
$sSQLTanggal4 =  "  = ".$iTGLnow  ;
$sSQLTanggal5 =  "  YEAR(a.Tanggal) = ".$iTGLnow  ;

$iTGL = date("Y-m-d");
$hariini = strtotime(date("Y-m-d"));
//echo $hariini;
//$iTGL = date("Y-m-d", strtotime('last Sunday', $hariini));

//echo $iTGL;
$mingguterakhir = date("Y-m-d", strtotime('last Sunday', $hariini));
$minggukemaren = date("Y-m-d", strtotime('-1 week', strtotime($mingguterakhir)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', $hariini));
	$tanggal=$iTGL;
	$time = strtotime($tanggal);
//	echo $time;
	$monthago = date("Y-m-d", strtotime("-1 month", $time));
	$nextmonth = date("Y-m-d", strtotime("+1 month", $time));
			$yearago = date("Y-m-d", strtotime("-1 year", $time));
			$nextyear = date("Y-m-d", strtotime("+1 year", $time));
}
 

$Judul = "Informasi Laporan Persembahan Kartu Bulanan  ".date(Y,strtotime($iTGL)); 
require "Include/Header-Report.php";

$sSQL0="SELECT MONTH(MAX(Tanggal)) as Pembagi FROM PersembahanBulanan a WHERE ".$sSQLTanggal5;
//echo $sSQL0;
$rsPembagi = RunQuery($sSQL0);
extract(mysql_fetch_array($rsPembagi));
//echo $Pembagi;
				
				echo "<table  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";
				echo "<td align=\"left\">";
				echo "<a href=\"PrintViewLapPersembahanBulanan.php?TGL=".$yearago."&amp;GID=$refresh\"  >";
				echo "<< Tahun sebelumnya </a></td>";
				echo "<td></td>";
				//echo "<td align=\"center\"><font size=\"3\"><b>LAPORAN REKAPITULASI PER-BIDANG TAHUN ANGGARAN ".date(Y,strtotime($iTGL))."</b></font></td>";
				echo "<td align=\"right\">"; 
				echo "<a href=\"PrintViewLapPersembahanBulanan.php?TGL=".$nextyear."&amp;GID=$refresh\"  >";
				echo "Tahun Berikutnya >></a></td>";
				echo "</table>";
				echo "<table  border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";

				
// Detail Laporan

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<b></b>";
				echo "<table   border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=1100>";
				echo "<tr><td bgcolor=\"#FF0000\" colspan=\"16\" align=\"center\" ><b>
				<a href=\"graph_LapPersembahan.php\" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
				<font color=#FFFFFF>Pemasukan (Rupiah)</font></a></b></td><tr>";
				echo "<tbody  align=\"center\" >";
				echo "<td>Urut</td><td >Kode</td><td >Keterangan</td>";

				$bul=0;
				while ($bul < 12 )
				{
				$bul++;
				$bulan = date(Y,strtotime($iTGL))."-".$bul."-".date(d,strtotime($iTGL));
				//echo $bulan;
				echo "<td width=\"100\" >".date2Ind($bulan,6)."</td>";
				}
				echo "<td><b>.....Total.....</b></td>";
				echo "<td><b> Rata-rata </b></td>";
				echo "</tr>";
			
			// Data Rincian
				
			//Data Perbulan				
				$tbul1 = 0;

			// Pers Bulanan perKelompok
			
	$sSQL1 = "SELECT DISTINCT(TRIM(Kelompok)) as NamaKelompok FROM PersembahanBulanan ORDER BY Kelompok" ;
	$rsPersBul = RunQuery($sSQL1);
	$a = 0;
    //Loop through the surat recordset
    while ($aRow = mysql_fetch_array($rsPersBul))
    {
	$a++;
    extract($aRow);
    //Alternate the row style
    $sRowClass = AlternateRowStyle($sRowClass); 
	
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				if ($NamaKelompok == '0'){$NamaKelompok = 'NN';}else{$NamaKelompok = $NamaKelompok;}
				echo "<td colspan=\"2\" ><font size=\"1\" >$NamaKelompok</font></td>";
		
				$tbul1 = 0;
				$GTotalPersBulanan=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Bulanan 
						$sSQL = "SELECT Kelompok, IFNULL(SUM(Bulanan)+SUM(Syukur)+SUM(ULK),0) as PersBulanan FROM PersembahanBulanan a
							WHERE Kelompok = '$NamaKelompok' AND MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));
					
					// Total
					$GTotalPersBulanan=$GTotalPersBulanan+$PersBulanan;
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=BendMPHBulanan&DetailCek=Bulanan&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".currency(' ',$PersBulanan,'.',',-')."</a></font></td>";
				}
				
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalPersBulanan,'.',',-')."</b></font></font></td>";
				if ($Pembagi>0){
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',ROUND(($GTotalPersBulanan/$Pembagi),0),'.',',-')."</b></font></font></td></tr>";
				}
				echo "</tr>";	
		
		
		
		}		


			
			echo "<tr>";
			echo "<td align=\"right\" colspan=\"3\"><font size=\"1\" >Total Persembahan</font></td>";
			$t1=0;
			$totpertahun=0;
			while ($t1 < 12 )
				{
				$t1++;
				$sSQL = "SELECT IFNULL(SUM(Bulanan)+SUM(Syukur)+SUM(ULK),0) as totperbulan FROM PersembahanBulanan a
							WHERE MONTH(a.Tanggal) = ".$t1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));
							
				$totpertahun=$totpertahun+$totperbulan;
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$totperbulan,'.',',-')."</b></font></td>";
				
				}
				echo "<td align=\"right\"><b><font size=\"1\" >".currency(' ',$totpertahun,'.',',00')."</b></font></td>";
				if ($Pembagi>0){
				echo "<td align=\"right\"><b><font size=\"1\" >".currency(' ',ROUND(($totpertahun/$Pembagi),0),'.',',00')."</b></font></td>";
				}
			echo "</tr>";	

						echo "<tr>";
			echo "<td align=\"right\" colspan=\"3\"><font size=\"1\" >Rata2 Pers.perBulan</font></td>";
			$t1=0;
			$totpertahun=0;
			while ($t1 < 12 )
				{
				$t1++;
				$sSQL = "SELECT IFNULL(SUM(Bulanan)+SUM(Syukur)+SUM(ULK),0) as totperbulan FROM PersembahanBulanan a
							WHERE MONTH(a.Tanggal) = ".$t1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));
							
				$totpertahun=$totpertahun+$totperbulan;
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',ROUND(($totperbulan/9),0),'.',',-')."</b></font></td>";
				
				}
				echo "<td align=\"right\"><b><font size=\"1\" >".currency(' ',ROUND(($totpertahun/9),0),'.',',00')."</b></font></td>";

			echo "</tr>";	
echo "</table>";
// Detail Laporan Jumlah Kartu

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<b></b>";
				echo "<table   border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=1100>";
				echo "<tr><td bgcolor=\"#FF0000\" colspan=\"16\" align=\"center\" ><b>
				<a href=\"graph_LapPersembahan.php\" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
				<font color=#FFFFFF>Pemasukan (Jumlah Kartu)</font></a></b></td><tr>";
				echo "<tbody  align=\"center\" >";
				echo "<td>Urut</td><td >Kode</td><td >Keterangan</td>";

				$bul=0;
				while ($bul < 12 )
				{
				$bul++;
				$bulan = date(Y,strtotime($iTGL))."-".$bul."-".date(d,strtotime($iTGL));
				//echo $bulan;
				echo "<td width=\"100\" >".date2Ind($bulan,6)."</td>";
				}
				echo "<td><b>.....Total.....</b></td>";
				echo "<td><b> Rata-rata </b></td>";
				echo "</tr>";
			
			// Data Rincian
				
			//Data Perbulan				
				$tbul1 = 0;

			// Pers Bulanan perKelompok
			
	$sSQL1 = "SELECT DISTINCT(TRIM(Kelompok)) as NamaKelompok FROM PersembahanBulanan ORDER BY Kelompok" ;
	$rsPersBul = RunQuery($sSQL1);
	$a = 0;
    //Loop through the surat recordset
    while ($aRow = mysql_fetch_array($rsPersBul))
    {
	$a++;
    extract($aRow);
    //Alternate the row style
    $sRowClass = AlternateRowStyle($sRowClass); 
	
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				if ($NamaKelompok == '0'){$NamaKelompok = 'NN';}else{$NamaKelompok = $NamaKelompok;}
				echo "<td colspan=\"2\" ><font size=\"1\" >$NamaKelompok</font></td>";
		
				$tbul1 = 0;
				$GTotalPersBulanan=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Bulanan 
						$sSQL = "SELECT Kelompok, IFNULL(COUNT(Kelompok),0) as PersBulanan FROM PersembahanBulanan a
							WHERE Kelompok = '$NamaKelompok' AND MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));
					
					// Total
					$GTotalPersBulanan=$GTotalPersBulanan+$PersBulanan;
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=BendMPHBulanan&DetailCek=Bulanan&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".$PersBulanan."</a></font></td>";
				}
				
				echo "<td align=\"right\"><font size=\"1\" ><b>".$GTotalPersBulanan."</b></font></font></td>";
				if ($Pembagi>0){
				echo "<td align=\"right\"><font size=\"1\" ><b>".ROUND(($GTotalPersBulanan/$Pembagi),2)."</b></font></font></td></tr>";
				}
				echo "</tr>";	
		
		
		
		}		


			
			echo "<tr>";
			echo "<td align=\"right\" colspan=\"3\"><font size=\"1\" >Total Kartu</font></td>";
			$t1=0;
			$totpertahun=0;
			while ($t1 < 12 )
				{
				$t1++;
				$sSQL = "SELECT IFNULL(COUNT(Kelompok),0) as totperbulan FROM PersembahanBulanan a
							WHERE MONTH(a.Tanggal) = ".$t1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));
							
				$totpertahun=$totpertahun+$totperbulan;
				echo "<td align=\"right\"><font size=\"1\" ><b>".$totperbulan."</b></font></td>";
				
				}
				echo "<td align=\"right\"><b><font size=\"1\" >".$totpertahun."</b></font></td>";
				if ($Pembagi>0){
				echo "<td align=\"right\"><b><font size=\"1\" >".ROUND(($totpertahun/$Pembagi),2)."</b></font></td>";
				}
			echo "</tr>";	
			
			echo "<tr>";
			
			echo "<td align=\"right\" colspan=\"3\"><font size=\"1\" >Rata2 kartu perbulan</font></td>";
			$t1=0;
			$totpertahun=0;
			while ($t1 < 12 )
				{
				$t1++;
				$sSQL = "SELECT IFNULL(COUNT(Kelompok),0) as totperbulan FROM PersembahanBulanan a
							WHERE MONTH(a.Tanggal) = ".$t1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));
							
				$totpertahun=$totpertahun+$totperbulan;
				echo "<td align=\"right\"><font size=\"1\" ><b>".round(($totperbulan/9),2)."</b></font></td>";
				
				}
				if ($Pembagi>0){
				echo "<td align=\"right\"><b><font size=\"1\" >".ROUND(($totpertahun/9),2)."</b></font></td>";
				}
			echo "</tr>";				
			

		//$TotalPemasukan=$GTotalSerapan2+$SaldoThnKemaren;
		//$TotalPemasukan=$GTotalSerapan2;
			echo "</tr>";			
			
			echo "</tbody>";
echo "</tr></td></table>";			





			

				
?>
