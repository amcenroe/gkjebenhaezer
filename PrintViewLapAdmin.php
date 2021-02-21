<?php
/*******************************************************************************
 *
 *  filename    : PrintViewLapAdmin.php
 *  last change : 2013-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
$filename="PrintViewLapAdmin.php";
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
$bln = $iBulan;

if (strlen($iTGL>0))
{
$iTGLnow=date(Y);
$sSQLTanggal =  " WHERE MONTH(a.Tanggal) = ".date(n,strtotime($iTGL))." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;
$sSQLTanggal2 =  " AND MONTH(a.Tanggal) = ".date(n,strtotime($iTGL))." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;
$sSQLTanggal3 =  " AND YEAR(a.Ket1) = ".date(Y,strtotime($iTGL)) ;

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
$sSQLTanggal3 =  " AND YEAR(a.Ket1) = ".$iTGLnow  ;

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
 

$Judul = "Informasi Laporan Rincian Administrasi Surat Menyurat Tahun  ".date(Y,strtotime($iTGL)); 
require "Include/Header-Report.php";



				
				echo "<table  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";
				echo "<td align=\"left\">";
				echo "<a href=\"PrintViewLapAdmin.php?TGL=".$yearago."\"  >";
				echo "<< Tahun sebelumnya </a></td>";
				echo "<td></td>";
				//echo "<td align=\"center\"><font size=\"3\"><b>LAPORAN REKAPITULASI PER-BIDANG TAHUN ANGGARAN ".date(Y,strtotime($iTGL))."</b></font></td>";
				echo "<td align=\"right\">"; 
				echo "<a href=\"PrintViewLapAdmin.php?TGL=".$nextyear."\"  >";
				echo "Tahun Berikutnya >></a></td>";
				echo "</table>";
				echo "<table  border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";

				
// Detail Laporan

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<b></b>";
				echo "<table   border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";
				echo "<tr><td bgcolor=\"#BBFFFF\" colspan=\"16\" align=\"center\" ><b>
				<a href=\"graph_LapAdmin.php\" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
				Surat Masuk</a></b></td><tr>";
				echo "<tbody  align=\"center\" >";
				echo "<td>Urut</td><td >Kode</td><td >Keterangan</td>";

				
				while ($bul < 12 )
				{
				$bul++;
				$bulan = date(Y,strtotime($iTGL))."-".$bul."-".date(d,strtotime($iTGL));
				//echo $bulan;
				echo "<td width=\"100\" >".date2Ind($bulan,6)."</td>";
				}
				echo "<td><b>Total</b></td>";
				echo "</tr>";

		//Data Perbulan				
				$tbul1 = 0;
				$GTotalPersKantong=0;
				$ArrayPersKantong = array(0);
				$ArrayPersSyukur = array(0);
				$ArrayPersBulanan = array(0);
				$ArrayPersDanaPengembangan = array(0);
				$ArrayPersULK = array(0);
				$ArrayPersKhusus = array(0);
				$ArrayPersLainLain = array(0);
				
				$ArraySuratMasuk = array(0);
	
				// Data Surat Masuk Per Kategori
				
				
				$sSQL = "SELECT a.ket3 as Kategori, b.* FROM SuratMasuk a 
				LEFT JOIN KlasifikasiSurat b ON a.ket3 = b.KlasID 
				GROUP BY a.Ket3 ORDER BY a.ket3  ASC";
				$rsKategori = RunQuery($sSQL);
				$i = 0;
				$e = 1;
				
				//Loop through the surat recordset
				$PosSubTotalJumlah=0;
				$ArrayPosSubTotalJumlah = array();
				while ($aRow = mysql_fetch_array($rsKategori))
				{
					extract($aRow);
					$i++;
					echo "<tr bgcolor=\"ffffff\">";
					echo "<td align=\"center\"><font size=\"1\" >".$i."</font></td>";
					echo "<td colspan=\"2\" ><font size=\"1\" >".$Deskripsi."</font></td>";
				
					$Bul=0;
					$TotSrtMasuk=0;
					while ($Bul < 12 )
						{
						$Bul++;
						$sSQL = "SELECT IFNULL(COUNT(MailID),0) as SrtMasuk FROM SuratMasuk a 
						LEFT JOIN KlasifikasiSurat b ON a.ket3 = b.KlasID 
						LEFT JOIN volunteeropportunity_vol c ON a.Bidang1 = c.vol_ID 
						LEFT JOIN volunteeropportunity_vol d ON a.Bidang2 = d.vol_ID 
						WHERE a.Ket3=".$Kategori." AND  MONTH(a.Ket1) = ".$Bul." ".$sSQLTanggal3;
						$rsJmlSurat = RunQuery($sSQL);
					//echo $sSQL;
						//Loop through the surat recordset
						$PosSubTotalJumlah=0;
						$ArrayPosSubTotalJumlah = array();
						while ($aRow = mysql_fetch_array($rsJmlSurat))
						{
						extract($aRow);
						$TotSrtMasuk=$TotSrtMasuk+$SrtMasuk;
						//$[$Deskripsi]=array(0);
						//array_push($Deskripsi, $SrtMasuk);
						if ($SrtMasuk>0){
						echo "<td align=\"center\"><font size=\"2\" >
						<a href=\"PrintViewSuratMasuk.php?Kat=".$Kategori."&TGL=".$iTGLnow."-".$Bul."-1 \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
						".$SrtMasuk."</a></font></font></td>";
						}else{
							echo "<td align=\"center\"><font size=\"1\" >
						<a href=\"PrintViewSuratMasuk.php?Kat=".$Kategori."&TGL=".$iTGLnow."-".$Bul."-1 \"  \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
						 </a></font></font></td>";					
						}
						}
						}
					echo "<td align=\"center\"><font size=\"2\" >".$TotSrtMasuk."</td></tr>";
				//print_r($ArraySuratMasuk);
				}

				
			
			//	print_r($ArrayPersKantong);echo "<br>";
			//	print_r($ArrayPersSyukur);echo "<br>";
			//	print_r($ArrayPersBulanan);echo "<br>";
			//	print_r($ArrayPersDanaPengembangan);echo "<br>";
			//	print_r($ArrayPersULK);echo "<br>";
			//	print_r($ArrayPersKhusus);echo "<br>";
			//	print_r($ArrayPersLainLain);echo "<br>";
			
			//print_r($ArraySuratMasuk);echo "<br>";
			
			 $_SESSION['ArrayPersKantong'] = $ArrayPersKantong;
			  $_SESSION['ArrayPersSyukur'] = $ArrayPersSyukur;
			   $_SESSION['ArrayPersBulanan'] = $ArrayPersBulanan;
			    $_SESSION['ArrayPersDanaPengembangan'] = $ArrayPersDanaPengembangan;
				 $_SESSION['ArrayPersULK'] = $ArrayPersULK;
				  $_SESSION['ArrayPersKhusus'] = $ArrayPersKhusus;
				   $_SESSION['ArrayPersLainLain'] = $ArrayPersLainLain;
			//print_r $_SESSION['ArrayPersKantong'];echo "<br>";
			
			echo "<tr>";
			echo "<td align=\"right\" colspan=\"3\"><font size=\"1\" >Total Surat</font></td>";
			$t1=0;
			$totperbulan=0;
			$totpertahun=0;
			while ($t1 < 12 )
				{
				$t1++;
						$sSQL = "SELECT IFNULL(COUNT(MailID),0) as SrtMasuk FROM SuratMasuk a 
								LEFT JOIN KlasifikasiSurat b ON a.ket3 = b.KlasID 
								LEFT JOIN volunteeropportunity_vol c ON a.Bidang1 = c.vol_ID 
								LEFT JOIN volunteeropportunity_vol d ON a.Bidang2 = d.vol_ID 
						WHERE MONTH(a.Ket1) = ".$t1." ".$sSQLTanggal3;
						$rsJmlSurat = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsJmlSurat))
							{
							extract($aRow);
							array_push($ArraySuratMasuk, $SrtMasuk);
							echo "<td align=\"center\"><font size=\"1\" ><b>".$SrtMasuk."</b></font></td>";
							}
				$totpertahun=$totpertahun+$SrtMasuk;
				}
				echo "<td align=\"center\"><b><font size=\"1\" >".$totpertahun."</b></font></td>";
			echo "</tr>";	
			
// Akumulasi
			echo "<tr>";
			echo "<td align=\"right\" colspan=\"3\"><font size=\"1\" >Akumulasi Surat Masuk</font></td>";
			$t1 = 0;
				$AkumulasiSrtMasuk=0;
				while ($t1 < 12 )
				{
				$t1++;
				$totperbulan=$ArraySuratMasuk[$t1];
				$AkumulasiSrtMasuk+=$totperbulan;
				echo "<td align=\"center\"><font size=\"1\" ><b>".$AkumulasiSrtMasuk."</b></font></td>";
		
		
				}
				
		echo "<td align=\"center\"><b><font size=\"1\" >".$AkumulasiSrtMasuk."</b></font></td>";
		echo "</tr>";			
			
			echo "</tbody>";


			echo "<br>";
			echo "<br>";


















			

				
?>
