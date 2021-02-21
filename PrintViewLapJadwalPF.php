<?php
/*******************************************************************************
 *
 *  filename    : PrintViewLapJadwalPF.php
 *  last change : 2013-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
$filename="PrintViewLapJadwalPF.php";
require "Include/Config.php";
require "Include/Functions.php";
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
$iRekap = FilterInput($_GET["Rekap"]);
$bln = $iBulan;

if (strlen($iTGL>0))
{
$iTGLnow=date(Y);
$sSQLTanggal =  " WHERE MONTH(a.Tanggal) = ".date(n,strtotime($iTGL))." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;
$sSQLTanggal2 =  " AND MONTH(a.Tanggal) = ".date(n,strtotime($iTGL))." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;
$sSQLTanggal3 =  " AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;

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
 

$Judul = "Informasi Jadwal Pelayan Firman Tahun  ".date(Y,strtotime($iTGL)); 
require "Include/Header-Report.php";



				
				echo "<table  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";
				echo "<td align=\"left\">";
				echo "<a href=\"PrintViewLapJadwalPF.php?TGL=".$yearago."&Rekap=".$iRekap."\"  >";
				echo "<< Tahun sebelumnya </a></td>";
				echo "<td></td>";
				//echo "<td align=\"center\"><font size=\"3\"><b>LAPORAN REKAPITULASI PER-BIDANG TAHUN ANGGARAN ".date(Y,strtotime($iTGL))."</b></font></td>";
				echo "<td align=\"right\">"; 
				echo "<a href=\"PrintViewLapJadwalPF.php?TGL=".$nextyear."&Rekap=".$iRekap."\"  >";
				echo "Tahun Berikutnya >></a></td>";
				echo "</table>";
				echo "<table  border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";

				
// Detail Laporan

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<b></b>";
		// Data Pemasukan Tahun Sebelumnya	
			$ThnKemaren=date(Y,strtotime($iTGL));
			//Header
				echo "<tr>";
				echo "<td><font size=\"2\" >No</font></td>";
				echo "<td colspan=\"1\" align=center><font size=\"2\" >Tanggal</font></td>";
				echo "<td colspan=\"1\" align=center><font size=\"2\" >Warna</font></td>";
			$sSQL0 = "SELECT DISTINCT(a.KodeTI), b.NamaTI FROM JadwalPelayanFirman a
			LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI 
			WHERE YEAR(TanggalPF) = ".date(Y,strtotime($iTGL))." ORDER BY KodeTI ";

			$rsHeader = RunQuery($sSQL0);
			$rsHeaderWidth = RunQuery($sSQL0);



			while ($aRow = mysql_fetch_array($rsHeader))
			{
				extract($aRow);

				echo "<td colspan=\"1\" align=center ><font size=\"2\" >$NamaTI</font>";
			
				$sSQL1 = "SELECT DISTINCT(WaktuPF) FROM JadwalPelayanFirman a
				WHERE KodeTI=$KodeTI AND YEAR(TanggalPF) = ".date(Y,strtotime($iTGL))."
				ORDER BY WaktuPF ";
				echo "<table border=1 align=center width=100%><tr>";

					$rsHeader1 = RunQuery($sSQL1);
					$rsHeader1a = RunQuery($sSQL1);

					$num_cols=0;
					$num_cols = mysql_num_rows($rsHeader1a);
				//	echo $num_cols;
					$colswidth=floor(100/($num_cols));
					$colswidthB=$colswidth."%";
				//	echo $colswidth;echo $colswidthB;


					while ($aRow = mysql_fetch_array($rsHeader1))
					{
					extract($aRow);
					echo "<td colspan=\"1\" width=$colswidth%><font size=\"2\" >$WaktuPF</font></td>";

					}
				echo "</tr></table>";
				echo "</td>";
			}	
			echo "</tr>";
			

			// Data Rincian
			$sSQL = "SELECT DISTINCT(TanggalPF), b.Warna FROM JadwalPelayanFirman a
			LEFT JOIN LiturgiGKJBekti b ON a.TanggalPF = b.Tanggal 
			WHERE YEAR(TanggalPF) = ".date(Y,strtotime($iTGL))." ORDER BY TanggalPF " ;
			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
						
				$i++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				echo "<tr>";
				echo "<td><font size=\"2\" >".$i."</font></td>";

			if ($Warna == 'Putih'){ $Warna0 = ' bgcolor=#FFFFFF ';}
			elseif ($Warna == 'Merah'){ $Warna0 = ' bgcolor=#DC143C ';}
			elseif ($Warna == 'Ungu'){ $Warna0 = ' bgcolor=#663399 ';}
			elseif ($Warna == 'Hitam'){ $Warna0 = ' bgcolor=#000000 ';}
			elseif ($Warna == 'Hijau'){ $Warna0 = ' bgcolor=#228B22 ';}
			elseif ($Warna == 'Tidak Diketahui'){ $Warna0 = ' ';}



				echo "<td  colspan=\"1\" ><font size=\"2\" >".date2Ind($TanggalPF, 1)."</font></td>";
				echo "<td  $Warna0 colspan=\"1\" align=center ><font size=\"2\" >$Warna</font></td>";



				// Data Pemasukan Tahun Sebelumnya	
					$ThnKemaren=date(Y,strtotime($iTGL));
					//Header

					$sSQL10 = "SELECT DISTINCT(a.KodeTI), b.NamaTI FROM JadwalPelayanFirman a
					LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI 
					WHERE YEAR(TanggalPF) = ".date(Y,strtotime($iTGL)) ." ORDER BY KodeTI ";

					$rsHeader = RunQuery($sSQL10);
					while ($aRow = mysql_fetch_array($rsHeader))
					{
						extract($aRow);

						//echo "<td colspan=\"1\" align=center ><font size=\"2\" >$NamaTI</font>";
						echo "<td  colspan=\"1\" align=center ><font size=\"2\" ></font>";
					
						$sSQL11 = "SELECT DISTINCT(WaktuPF) FROM JadwalPelayanFirman a
						WHERE KodeTI=$KodeTI AND YEAR(TanggalPF) = ".date(Y,strtotime($iTGL))."
						ORDER BY WaktuPF ";
						echo "<table border=0 align=center width=100%><tr>";

							$rsHeader11 = RunQuery($sSQL11);
							$rsHeader11a = RunQuery($sSQL11);
							$num_cols=0;
							$num_cols = mysql_num_rows($rsHeader11a);
							//echo $num_cols;
							$colswidth=floor(100/($num_cols));
							//echo $colswidth;

							while ($aRow = mysql_fetch_array($rsHeader11))
							{
							extract($aRow);

								$sSQL12 = "SELECT a.PelayanFirman, a.PFNonInstitusi , b.NamaPendeta, b.KodeWarna FROM JadwalPelayanFirman a
								LEFT JOIN DaftarPendeta b ON a.PelayanFirman = b.PendetaID 
								WHERE TanggalPF = '$TanggalPF' AND KodeTI=$KodeTI AND WaktuPF='$WaktuPF' 
								AND KodeTI=$KodeTI ";

							//	echo $sSQL12;
							$rsPF = RunQuery($sSQL12);
							$rsPF2 = RunQuery($sSQL12);

							$num_rows = mysql_num_rows($rsPF2);

							if ($num_rows>0){

							while ($aRow = mysql_fetch_array($rsPF))
								{
								extract($aRow);

								if ($PelayanFirman > 0 ){ 
									echo "<td bgcolor=\"$KodeWarna\" colspan=\"1\" width=$colswidth% ><font size=\"2\" >$NamaPendeta</font></td>";
									}else if ($PFNonInstitusi<>''){
 									echo "<td colspan=\"1\" width=$colswidth% ><font size=\"2\" >$PFNonInstitusi</font></td>";
									}

								}
							}else{
								echo "<td colspan=\"1\" width=$colswidth%  align=center ><font size=\"2\" > - </font></td>";
							}
							}
						echo "</tr></table>";
						echo "</td>";
					}	
					echo "</tr>";
				

				echo "</tr>";
			}
			echo "<tr>";
			echo "</table>";

echo "<table border=0 align=center width=1000><tr>";
			$sSQL101a = "SELECT PelayanFirman
					FROM JadwalPelayanFirman a 
					WHERE YEAR(TanggalPF) = ".date(Y,strtotime($iTGL))." AND PelayanFirman > 0 ";

			$rsTotPelayanan = RunQuery($sSQL101a);
			$totdatapelayanan = 0;
			$totdatapelayanan = mysql_num_rows($rsTotPelayanan);
			//echo $totdatapelayanan ;

			$sSQL102a = "SELECT PFNonInstitusi 
				FROM JadwalPelayanFirman a 
				WHERE YEAR(TanggalPF) = ".date(Y,strtotime($iTGL))." AND PFNonInstitusi != '' ";

			$rsTotPelayananNI = RunQuery($sSQL102a);
			$totdatapelayananNI = 0;
			$totdatapelayananNI = mysql_num_rows($rsTotPelayananNI);
			//echo $totdatapelayananNI;



echo "<td valign=top>";
echo "<table border=1 valign=top width=100%>";
				echo "<tr>";
				echo "<td><font size=\"2\" >No</font></td>";
				echo "<td colspan=\"1\" ><font size=\"2\" >Nama Pelayan Firman</font></td>";
				echo "<td colspan=\"1\" ><font size=\"2\" align=\"right\">Jumlah</font></td>";
				//echo "<td colspan=\"1\" ><font size=\"2\" align=\"right\">% Group</font></td>";
				echo "<td colspan=\"1\" ><font size=\"2\" align=\"right\">% Total</font></td>";
				echo "</tr>";

			$sSQL101 = "SELECT TanggalPF, NamaPendeta as PelayanFirman, b.KodeWarna, count(*) as TotalPelayanan , COUNT(*) / T.total * 100 AS percent
					FROM JadwalPelayanFirman a 
					LEFT JOIN DaftarPendeta b ON a.PelayanFirman = b.PendetaID , 
					(SELECT COUNT(*) AS total FROM JadwalPelayanFirman WHERE YEAR(TanggalPF) = ".date(Y,strtotime($iTGL))." AND PelayanFirman > 0) AS T
					WHERE YEAR(TanggalPF) = ".date(Y,strtotime($iTGL))." AND PelayanFirman > 0
					GROUP BY NamaPendeta
					ORDER BY percent DESC";

			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL101);
			$i = 0;
			$TotPelayanan = 0;
			$TotPercent = 0;
			$TotPercentAll = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
				$i++;
				extract($aRow);
				//Alternate the row style

				$percenttot = $TotalPelayanan / ($totdatapelayananNI + $totdatapelayanan) *100 ;
				$sRowClass = AlternateRowStyle($sRowClass); 
				echo "<tr>";
				echo "<td bgcolor=\"$KodeWarna\" ><font size=\"2\" >".$i."</font></td>";
				echo "<td colspan=\"1\" ><font size=\"2\" >$PelayanFirman</font></td>";
				echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >$TotalPelayanan</font></td>";
				//echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >$percent</font></td>";
				echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >".round($percenttot,2)."</font></td>";
				echo "</tr>";
				$TotPelayanan = $TotPelayanan + $TotalPelayanan;
				$TotPercent = $TotPercent + $percent;
				$TotPercentAll = $TotPercentAll + $percenttot;

			}
				echo "<tr>";
				echo "<td><font size=\"2\" >".$i."</font></td>";
				echo "<td colspan=\"1\" ><font size=\"2\" >Total</font></td>";
				echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >$TotPelayanan</font></td>";
				//echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >$TotPercent</font></td>";
				echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >".round($TotPercentAll,2)."</font></td>";
				echo "</tr>";		
echo "</table>";					
echo "</td>";
echo "<td valign=top>";
echo "<table border=1 valign=top width=100%>";
				echo "<tr>";
				echo "<td><font size=\"2\" >No</font></td>";
				echo "<td colspan=\"1\" ><font size=\"2\" >Nama Pelayan Firman</font></td>";
				echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >Jumlah</font></td>";
				//echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >% Group</font></td>";
				echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >% Total</font></td>";
				echo "</tr>";

			$sSQL102 = "SELECT PFNonInstitusi as PelayanFirman, count(*) as TotalPelayanan , COUNT(*) / T.total * 100 AS percent
				FROM JadwalPelayanFirman a 
				LEFT JOIN DaftarPendeta b ON a.PelayanFirman = b.PendetaID , 
				(SELECT COUNT(*) AS total FROM JadwalPelayanFirman WHERE YEAR(TanggalPF) = ".date(Y,strtotime($iTGL))." AND PFNonInstitusi != '') AS T
				WHERE YEAR(TanggalPF) = ".date(Y,strtotime($iTGL))." AND PFNonInstitusi != ''
				GROUP BY PFNonInstitusi
				ORDER BY percent DESC";

			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL102);
			$i = $i;
			$TotPelayanan = 0;
			$TotPercent = 0;	
			$TotPercentAll = 0;					
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
				$i++;
				extract($aRow);
				//Alternate the row style
				$percenttot = $TotalPelayanan / ($totdatapelayananNI + $totdatapelayanan) *100 ;
				$sRowClass = AlternateRowStyle($sRowClass); 
				echo "<tr>";
				echo "<td><font size=\"2\" >".$i."</font></td>";
				echo "<td colspan=\"1\" ><font size=\"2\" >$PelayanFirman</font></td>";
				echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >$TotalPelayanan</font></td>";
				//echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >$percent</font></td>";
				echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >".round($percenttot,2)."</font></td>";
				echo "</tr>";
				$TotPelayanan = $TotPelayanan + $TotalPelayanan;
				$TotPercent = $TotPercent + $percent;	
				$TotPercentAll = $TotPercentAll + $percenttot;							
			}
				echo "<tr>";
				echo "<td><font size=\"2\" >".$i."</font></td>";
				echo "<td colspan=\"1\" ><font size=\"2\" >Total</font></td>";
				echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >$TotPelayanan</font></td>";
				//echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >$TotPercent</font></td>";
				echo "<td colspan=\"1\" align=\"right\"><font size=\"2\" >".round($TotPercentAll,2)."</font></td>";
				echo "</tr>";				
echo "</table>";
echo "</td>";
echo "</tr></table>";			
				
			
















			

				
?>
