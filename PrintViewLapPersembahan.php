<?php
/*******************************************************************************
 *
 *  filename    : PrintViewLapPersembahan.php
 *  last change : 2013-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
$filename="PrintViewLapPersembahan.php";
require "Include/Config.php";
require "Include/Functions.php";

$Kode2Komisi=array();


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
 

$Judul = "Informasi Laporan Rincian Persembahan dan Pengeluaran Tahun  ".date(Y,strtotime($iTGL)); 
require "Include/Header-Report.php";



				
				echo "<table  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";
				echo "<td align=\"left\">";
				echo "<a href=\"PrintViewLapPersembahan.php?TGL=".$yearago."\"  >";
				echo "<< Tahun sebelumnya </a></td>";
				echo "<td></td>";
				//echo "<td align=\"center\"><font size=\"3\"><b>LAPORAN REKAPITULASI PER-BIDANG TAHUN ANGGARAN ".date(Y,strtotime($iTGL))."</b></font></td>";
				echo "<td align=\"right\">"; 
				echo "<a href=\"PrintViewLapPersembahan.php?TGL=".$nextyear."\"  >";
				echo "Tahun Berikutnya >></a></td>";
				echo "</table>";
				echo "<table  border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";

				
// Detail Laporan

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<b></b>";
				echo "<table   border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";
				echo "<tr><td bgcolor=\"#BBFFFF\" colspan=\"16\" align=\"center\" ><b>
				<a href=\"graph_LapPersembahan.php\" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
				Pemasukan</a></b></td><tr>";
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
			// Data Pemasukan Tahun Sebelumnya	
			$ThnKemaren=date(Y,strtotime($iTGL));
			
			$sSQL0 = "SELECT SUM(Bulanan) as PemasukanThnKemaren FROM PersembahanPPPG
			WHERE YEAR(Tanggal) < ".$ThnKemaren ;
			//echo $sSQL0;
			$rsPemasukanThnKemaren = RunQuery($sSQL0);
			extract(mysql_fetch_array($rsPemasukanThnKemaren));
			
			$sSQL0 = "SELECT SUM(Jumlah) as PengeluaranThnKemaren FROM PengeluaranPPPG
			WHERE YEAR(Tanggal) < ".$ThnKemaren ;
			//echo $sSQL0;
			$rsPengeluaranThnKemaren = RunQuery($sSQL0);
			extract(mysql_fetch_array($rsPengeluaranThnKemaren));		
			
			$SaldoThnKemaren=$PemasukanThnKemaren-$PengeluaranThnKemaren;
		//	echo "<tr><td>-</td><td colspan=\"2\" ><b><font size=\"1\" >Saldo Tahun ".($ThnKemaren-1)."</font></b></td><td colspan=\"12\" >
		//	<td><b><font size=\"1\" >".currency(' ',$SaldoThnKemaren,'.',',-')."</font></b></td></tr>";
			
			// Data Rincian
			// Pers Kantong
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Persembahan Kantong</font></td>";
				
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
				
				
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Kantong Mingguan
						$sSQL = "SELECT (SUM(KebDewasa)+SUM(KebAnak)+SUM(KebAnakJTMY)+SUM(KebPraRemaja)+SUM(KebRemaja)+SUM(KebPemuda)) AS PersKantong
							FROM Persembahangkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPerTI = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPerTI));
							
					// Data Persembahan Kantong Khusus 
					//	$sSQL = "SELECT SUM(Persembahan) as PersembahanKhusus FROM PersembahanKhususgkjbekti a
					//		WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
					//		$rsKhusus = RunQuery($sSQL);
					//		extract(mysql_fetch_array($rsKhusus));
					
					// Total

					$TotalPerBulanPersKantong=$PersKantong+$PersembahanKhusus;
					$GTotalPersKantong=$GTotalPersKantong+$TotalPerBulanPersKantong;
					
					
					array_push($ArrayPersKantong, $TotalPerBulanPersKantong);
				
					
					
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=BendMPH&DetailCek=Kantong&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".currency(' ',$PersKantong+$PersembahanKhusus,'.',',-')."</a></font></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalPersKantong,'.',',-')."</b></font></font></td></tr>";
				echo "</tr>";
				
			// Pers Syukur
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Persembahan Syukur</font></td>";
		
				$tbul1 = 0;
				$GTotalPersSyukur=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Syukur 
						$sSQL = "SELECT IFNULL(SUM(Syukur),0) as PersSyukur FROM Persembahangkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsSyukur = RunQuery($sSQL);
							extract(mysql_fetch_array($rsSyukur));

					// Total
					array_push($ArrayPersSyukur, $PersSyukur);
					$GTotalPersSyukur=$GTotalPersSyukur+$PersSyukur;
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=BendMPHSyukur&DetailCek=Kantong&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".currency(' ',$PersSyukur,'.',',-')."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalPersSyukur,'.',',-')."</b></font></font></td></tr>";
				echo "</tr>";	

			// Pers Bulanan
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Persembahan Bulanan</font></td>";
		
				$tbul1 = 0;
				$GTotalPersBulanan=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Bulanan 
						$sSQL = "SELECT IFNULL(SUM(Bulanan),0) as PersBulanan, IFNULL(SUM(Syukur),0) as PersDanaPengembangan, IFNULL(SUM(ULK),0) as PersULK FROM PersembahanBulanan a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));
					
					// Total
					$GTotalPersBulanan=$GTotalPersBulanan+$PersBulanan;
					array_push($ArrayPersBulanan, $PersBulanan);
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=BendMPHBulanan&DetailCek=Bulanan&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".currency(' ',$PersBulanan,'.',',-')."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalPersBulanan,'.',',-')."</b></font></font></td></tr>";
				echo "</tr>";			

			// Pers DANA Pengembangan
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Dana Pengembangan</font></td>";
		
				$tbul1 = 0;
				$GTotalPersDP=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Bulanan 
						$sSQL = "SELECT IFNULL(SUM(Bulanan),0) as PersBulanan, IFNULL(SUM(Syukur),0) as PersDanaPengembangan, IFNULL(SUM(ULK),0) as PersULK FROM PersembahanBulanan a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));

					// Total
					$GTotalPersDP=$GTotalPersDP+$PersDanaPengembangan;
					array_push($ArrayPersDanaPengembangan, $PersDanaPengembangan);
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=BendMPHBulanan&DetailCek=Pengembangan&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".currency(' ',$PersDanaPengembangan,'.',',-')."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalPersDP,'.',',-')."</b></font></font></td></tr>";
				echo "</tr>";	

			// Pers ULK
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Unit Layanan Kasih (ULK)</font></td>";
		
				$tbul1 = 0;
				$GTotalPersULK=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Bulanan 
						$sSQL = "SELECT IFNULL(SUM(Bulanan),0) as PersBulanan, IFNULL(SUM(Syukur),0) as PersDanaPengembangan, IFNULL(SUM(ULK),0) as PersULK FROM PersembahanBulanan a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));

					// Total
					$GTotalPersULK=$GTotalPersULK+$PersULK;
					array_push($ArrayPersULK, $PersULK);
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=BendMPHBulanan&DetailCek=ULK&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".currency(' ',$PersULK,'.',',-')."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalPersULK,'.',',-')."</b></font></font></td></tr>";
				echo "</tr>";

		
			// Pers Khusus
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Persembahan Khusus</font></td>";
		
				$tbul1 = 0;
				$GTotalPersKhusus=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Bulanan 
					$sSQL = "SELECT  IFNULL((SUM(Khusus)+SUM(SyukurBaptis)+SUM(KhususPerjamuan)+SUM(Marapas)+SUM(Marapen)+SUM(Unduh)+SUM(Maranatal)),0) as PersKhusus1
							FROM Persembahangkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPerTI = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPerTI));

				// Data Persembahan Kantong Khusus 
						$sSQL1 = "SELECT SUM(Persembahan) as PersembahanKantongKhusus FROM PersembahanKhususgkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsKhusus = RunQuery($sSQL1);
							extract(mysql_fetch_array($rsKhusus));
					
					
					// Total
					$GTotalPersKhusus=$GTotalPersKhusus+$PersKhusus1+$PersembahanKantongKhusus;
					$PersKhusus=$PersKhusus1+$PersembahanKantongKhusus;
					array_push($ArrayPersKhusus, $PersKhusus);
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=BendMPH&DetailCek=Khusus&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".currency(' ',$PersKhusus,'.',',-')."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalPersKhusus,'.',',-')."</b></font></font></td></tr>";
				echo "</tr>";			

			// Pers Lain Lain 
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Persembahan Lain Lain</font></td>";
		
				$tbul1 = 0;
				$GTotalPersLainLain=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Lain Lain 
					$sSQL = "SELECT SUM(Pink)+SUM(LainLain) as PersLainLain 
							FROM Persembahangkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPerTI = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPerTI));

					// Data Persembahan Kontribusi 
					$sSQL = "SELECT IFNULL(SUM(Persembahan),0) as PersKontribusi FROM PersembahanKontribusigkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsKontribusi = RunQuery($sSQL);
							extract(mysql_fetch_array($rsKontribusi));
							
					// Total
					$GTotalPersLainLain=$GTotalPersLainLain+($PersLainLain+$PersKontribusi);
					array_push($ArrayPersLainLain, ($PersLainLain+$PersKontribusi));
					
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=BendMPHLain&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".currency(' ',$PersLainLain+$PersKontribusi,'.',',-')."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalPersLainLain,'.',',-')."</b></font></font></td></tr>";
				echo "</tr>";	
				
				
			//	print_r($ArrayPersKantong);echo "<br>";
			//	print_r($ArrayPersSyukur);echo "<br>";
			//	print_r($ArrayPersBulanan);echo "<br>";
			//	print_r($ArrayPersDanaPengembangan);echo "<br>";
			//	print_r($ArrayPersULK);echo "<br>";
			//	print_r($ArrayPersKhusus);echo "<br>";
			//	print_r($ArrayPersLainLain);echo "<br>";
			
			 $_SESSION['ArrayPersKantong'] = $ArrayPersKantong;
			  $_SESSION['ArrayPersSyukur'] = $ArrayPersSyukur;
			   $_SESSION['ArrayPersBulanan'] = $ArrayPersBulanan;
			    $_SESSION['ArrayPersDanaPengembangan'] = $ArrayPersDanaPengembangan;
				 $_SESSION['ArrayPersULK'] = $ArrayPersULK;
				  $_SESSION['ArrayPersKhusus'] = $ArrayPersKhusus;
				   $_SESSION['ArrayPersLainLain'] = $ArrayPersLainLain;
			//print_r $_SESSION['ArrayPersKantong'];echo "<br>";
			
			echo "<tr>";
			echo "<td align=\"right\" colspan=\"3\"><font size=\"1\" >Total Persembahan</font></td>";
			$t1=0;
			$totpertahun=0;
			while ($t1 < 12 )
				{
				$t1++;
				//$key_data = $Array_Name[$t1];
				$totperbulan=$ArrayPersKantong[$t1]+$ArrayPersSyukur[$t1]+$ArrayPersBulanan[$t1]+$ArrayPersDanaPengembangan[$t1]+$ArrayPersULK[$t1]+$ArrayPersKhusus[$t1]+$ArrayPersLainLain[$t1];
				$totpertahun=$totpertahun+$totperbulan;
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$totperbulan,'.',',-')."</b></font></td>";
				
				}
				echo "<td align=\"right\"><b><font size=\"1\" >".currency(' ',$totpertahun,'.',',00')."</b></font></td>";
		//$TotalPemasukan=$GTotalSerapan2+$SaldoThnKemaren;
		//$TotalPemasukan=$GTotalSerapan2;
			echo "</tr>";	
			
// Akumulasi
			echo "<tr>";
			echo "<td align=\"right\" colspan=\"3\"><font size=\"1\" >Akumulasi Persembahan</font></td>";
			
			$t1 = 0;
			
				$AkumulasiPersembahan=0;
				while ($t1 < 12 )
				{
				$t1++;
				$totperbulan=$ArrayPersKantong[$t1]+$ArrayPersSyukur[$t1]+$ArrayPersBulanan[$t1]+$ArrayPersDanaPengembangan[$t1]+$ArrayPersULK[$t1]+$ArrayPersKhusus[$t1]+$ArrayPersLainLain[$t1];
				$AkumulasiPersembahan+=$totperbulan;
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$AkumulasiPersembahan,'.',',-')."</b></font></td>";
		
		
				}
				
		echo "<td align=\"right\"><b><font size=\"1\" >".currency(' ',$AkumulasiPersembahan,'.',',00')."</b></font></td>";
		//$TotalPemasukan=$GTotalSerapan2+$SaldoThnKemaren;
		//$TotalPemasukan=$GTotalSerapan2;
			echo "</tr>";			
			
			echo "</tbody>";
			
			// Pengeluaran ........................................................
			
			echo "<b></b>";
				
				echo "<tr><td bgcolor=\"#BBFFFF\" colspan=\"16\" align=\"center\" ><b>Pengeluaran</b></td><tr>";
				echo "<tbody  align=\"center\" >";
				echo "<td>Urut</td><td >Kode</td><td > Keterangan</td>";
				
				$bul=0;
				
				while ($bul < 12 )
				{
				$bul++;
				$bulan = date(Y,strtotime($iTGL))."-".$bul."-".date(d,strtotime($iTGL));
				//echo $bulan;
				echo "<td width=\"100\" >".date2Ind($bulan,6)."</td>";
				}
				echo "<td><b>Total</b></td><td>%</td>";
				echo "</tr>";

		// Data Pengeluaran


		
				echo "<tr><td colspan=\"3\" bgcolor=\"aqua\" align=\"left\" ><font size=\"1\" ><b>Kas Jemaat : </b></font></td></tr>";

			
			// Data Set
			
			//Kelompok2
	$sSQL0 = "select d.Kelompok as Kelompok, d.KetKelompok as KetKelompok FROM PengeluaranKasKecil	a
			LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
			LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
			LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
			WHERE d.BidangID > 0 ".$sSQLTanggal3." 
			GROUP BY d.Kelompok ORDER BY d.Kelompok";
			
			
			//echo "<br>A.Kelompok :".$Kelompok."<br>";
			//echo $sSQL0;echo "<br>===================<br>";
			
			$rsPengeluaran0 = RunQuery($sSQL0);
			$e = 0;
			//Loop through the surat recordset

		while ($aRow = mysql_fetch_array($rsPengeluaran0))
	{
				$e++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
	if ($Kelompok == 1){
	echo "<tr bgcolor=\"fcfcfc\">";
	echo "<td><font size=\"1\" >".integerToRoman($e)."</font></td>";
	echo "<td colspan=\"2\" bgcolor=\"yellow\" align=\"left\" ><font size=\"1\" >".$KetKelompok."</font></td>";
	echo "<td colspan=\"12\" ></td>";
	}else	if ($Kelompok == 3){
	
	// Sub Total untuk kas jemaat
	
				echo "<tr><td colspan=\"3\" ><font size=\"1\" ><b>Sub Total Kas Jemaat</b></font></td>";
				
				$tbul1 = 0;
				$GTotalSerapan1=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				
				$bulan1 = date(Y,strtotime($iTGL))."-".$tbul1."-".date(d,strtotime($iTGL));
				//echo $bulan;
				//echo "<td width=\"100\" >".date2Ind($bulan,6)."</td>";
				//echo
				// ini
				$sSQL14 = "select a.*, SUM(a.Jumlah) as TotalPerBulan1, b.*, c.*, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				WHERE d.Kelompok < 3 AND MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3."
				";
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsPengeluaranPerBulan1 = RunQuery($sSQL14);
			
				//echo "<br>A.Kelompok :".$Kelompok."=Bulan===:".$tbul1."===<br>";
				//echo $sSQL14;echo "<br>===================<br>";

				if (mysql_num_rows($rsPengeluaranPerBulan1)>0) {
				while ($aRow = mysql_fetch_array($rsPengeluaranPerBulan1))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					$GTotalSerapan1+=$TotalPerBulan1;
					echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$TotalPerBulan1,'.',',-')."</b></font></td>";
					}
				}else{
						echo "<td align=\"right\"><font size=\"1\" ><b></b></font></td>";
					}
					
				}
					echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalSerapan1,'.',',-')."</b></font></font></td><td align=\"center\" ><b><font size=\"1\" >".round(@($GTotalSerapan1/$TotalBudget1*100),2)."</font></b></td></tr>";
	
	// akhir data sub total khas jemaat
	
	
	echo "<tr bgcolor=\"fcfcfc\">";
	echo "<tr><td colspan=\"3\" bgcolor=\"aqua\" align=\"left\" ><font size=\"1\" ><b>Kas Lain Lain: </b></font></td></tr>";
	echo "<td><font size=\"1\" >".integerToRoman($e)."</font></td>";
	echo "<td colspan=\"2\" bgcolor=\"yellow\" align=\"left\" ><font size=\"1\" >".$KetKelompok."</font></td>";
	echo "<td colspan=\"15\" ></td>";
	$Kelompok=3;
	}
			
			
			// Bidang
			$sSQL = "select a.*, SUM(a.Jumlah) as SubTotal , b.*, c.*,c.BidangID as Bidang, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				WHERE d.Kelompok = ".$Kelompok." ".$sSQLTanggal3." 
				GROUP BY c.BidangID ORDER BY c.BidangID";
			//echo $sSQL."<br><br>";	
			//echo "<br>- BidangID: ".$Bidang.".".$NamaBidang."<br>";
			//	echo "<br>...B.Kelompok :".$Kelompok."=Bidang :".$Bidang."<br>";
			//echo $sSQL;echo "<br>===================<br>";
			$rsPengeluaran = RunQuery($sSQL);
			$i = 0;
			$e = 1;
			//Loop through the surat recordset
			$PosSubTotalJumlah=0;
			$ArrayPosSubTotalJumlah = array();
			while ($aRow = mysql_fetch_array($rsPengeluaran))
			{
						
				$i++;
				$e++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				$PosSubTotalJumlah+=$SubTotal;
				
				if ($Kelompok == 2){
				echo "<tr bgcolor=\"ffffff\">";
				echo "<td><font size=\"1\" >".integerToRoman($e)."</font></td>";
				echo "<td colspan=\"2\" bgcolor=\"yellow\" align=\"left\" ><font size=\"1\" >".$NamaBidang."</font></td>";
				echo "<td></td>";
				}
				//echo "<td align=\"right\"><b>".currency(' ',$SubTotal,'.',',-')."</b></td>";
				
				echo "</tr>";
				
				$sSQL1 = "select a.*, IFNULL(SUM(a.Jumlah),0) as TotalKomisi, b.*, c.*, c.KomisiID as cKomisiID, d.*, e.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				LEFT JOIN MasterAnggaran e ON b.KomisiID = e.KomisiID  AND e.TahunAnggaran ".$sSQLTanggal4."
				WHERE c.BidangID=".$Bidang." ".$sSQLTanggal3."
				
				GROUP BY c.KomisiID ORDER BY c.KomisiID";

			//echo $sSQL."<br>";
			//echo "<br>--- KomisiID: ".$KomisiID."<br>";
			//	echo "<br>......C.Kelompok :".$Kelompok."=Bidang :".$Bidang."=Komisi : ".$cKomisiID."<br>";
			
			// Pengeluaran Komisi	
				$rsPengeluaranKomisi = RunQuery($sSQL1);
				$ii = 0;
				//Loop through the surat recordset
				//$PosSubTotalJumlah=0;
				$TotalPenyerapanAnggaran=0;
				while ($aRow = mysql_fetch_array($rsPengeluaranKomisi))
				{
				$ii++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr bgcolor=\"ffffff\">";
				echo "<td></td>";
			
				echo "<td colspan=\"2\" ><font size=\"1\" >".$NamaKomisi."</font></td>";
				
				$$KodeKomisi=array();
				$DaftarKomisi=$DaftarKomisi."|".$KodeKomisi;
				array_push($Kode2Komisi, $KodeKomisi);
				$Bul=0;
				$GTotalPerTahun=0;
				while ($Bul < 12 )
				{
				$Bul++;
				//echo
				// ini
				$sSQL3 = "select a.*, IFNULL(SUM(a.Jumlah),0) as TotalPerBulan, b.*, c.*, d.*, e.Budget as AnggaranPerKomisi  FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				LEFT JOIN MasterAnggaran e ON b.KomisiID = e.KomisiID  AND e.TahunAnggaran ".$sSQLTanggal4."
				WHERE c.BidangID=".$Bidang." AND b.KomisiID=".$cKomisiID." AND MONTH(a.Tanggal) = ".$Bul." ".$sSQLTanggal3."
				GROUP BY c.KomisiID ORDER BY c.KomisiID";
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsPengeluaranPerBulan = RunQuery($sSQL3);
				
				//echo "<br>--- KomisiID: ".$KomisiID."<br>".$sSQL3."<br><br>";
								
				if (mysql_num_rows($rsPengeluaranPerBulan)>0) {
				while ($aRow = mysql_fetch_array($rsPengeluaranPerBulan))
				{
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				$GTotalPerTahun=$GTotalPerTahun+$TotalPerBulan;
				echo "<td align=\"right\"><font size=\"1\" >
				<a href=\"PrintViewCekPersembahan.php?Cek=KasKecil&TGL=".$iTGL."&KomisiID=".$KomisiID."&Bul=".$Bul."\" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
				".currency(' ',$TotalPerBulan,'.',',-')."</a></font></td>";
				$$KodeKomisi=$$KodeKomisi.",".$TotalPerBulan;
				}
				}else
				{
				echo "<td align=\"right\">.</td>";
				$$KodeKomisi=$$KodeKomisi.",0";
				}
				
				}
				
				echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalPerTahun,'.',',-')."</b></font></td>";
				echo "<td align=\"center\"><font size=\"1\" ><b>".round(@($GTotalPerTahun/$AnggaranPerKomisi*100),2)."</b></font></td>";
				echo "</tr>";
				
				}

			}
	}
			// Budget
			
			

				echo "<tr><td colspan=\"3\" ><font size=\"1\" ><b>Total Pengeluaran</b></font></td>";
				
			
				
				$tbul = 0;
				$GTotalSerapan=0;
				//$ArrayTotalPerBulan=0;
				$ArrayTotalPerBulan = array(0);
				while ($tbul < 12 )
				{
				$tbul++;
				
				$bulan = date(Y,strtotime($iTGL))."-".$tbul."-".date(d,strtotime($iTGL));
				//echo $bulan;
				//echo "<td width=\"100\" >".date2Ind($bulan,6)."</td>";
				//echo
				// ini
				$sSQL4 = "select a.*, IFNULL(SUM(a.Jumlah),0) as TotalPerBulan, b.*, c.*, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				WHERE MONTH(a.Tanggal) = ".$tbul." ".$sSQLTanggal3."
				";
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsPengeluaranPerBulan = RunQuery($sSQL4);
				//echo $sSQL3;
				if (mysql_num_rows($rsPengeluaranPerBulan)>0) {
				while ($aRow = mysql_fetch_array($rsPengeluaranPerBulan))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					$GTotalSerapan+=$TotalPerBulan;
					echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$TotalPerBulan,'.',',-')."</b></font></td>";
					}
				}else{
						echo "<td align=\"right\"><font size=\"1\" ><b></b></td>";
					}
					array_push($ArrayTotalPerBulan, $TotalPerBulan);
					
					
				}
					echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalSerapan,'.',',-')."</b></font></font></td><td><b><font size=\"1\" >".round(@($GTotalSerapan/$TotalBudget*100),2)."</font></b></td></tr>";
					
$_SESSION['ArrayTotalPerBulan'] = $ArrayTotalPerBulan;
// total					
		$TotalPengeluaran=$GTotalSerapan2;
			echo "</tr>";
			
			echo "<tr><td colspan=\"3\" ><font size=\"1\" ><b>Akumulasi Pengeluaran</b></font></td>";
					$tbul = 0;
				$GTotalSerapan=0;
				while ($tbul < 12 )
				{
				$tbul++;
				
				$bulan = date(Y,strtotime($iTGL))."-".$tbul."-".date(d,strtotime($iTGL));
				//echo $bulan;
				//echo "<td width=\"100\" >".date2Ind($bulan,6)."</td>";
				//echo
				// ini
				$sSQL4 = "select a.*, IFNULL(SUM(a.Jumlah),0) as TotalPerBulan, b.*, c.*, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				WHERE MONTH(a.Tanggal) = ".$tbul." ".$sSQLTanggal3."
				";
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsPengeluaranPerBulan = RunQuery($sSQL4);
				//echo $sSQL3;
				if (mysql_num_rows($rsPengeluaranPerBulan)>0) {
				while ($aRow = mysql_fetch_array($rsPengeluaranPerBulan))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					$GTotalSerapan=$GTotalSerapan+$TotalPerBulan;
					echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalSerapan,'.',',-')."</b></font></td>";
					}
				}else{
						echo "<td align=\"right\"><font size=\"1\" ><b></b></td>";
					}
					
				}
					echo "<td align=\"right\"><font size=\"1\" ><b>".currency(' ',$GTotalSerapan,'.',',-')."</b></font></font></td><td><b><font size=\"1\" >".round(@($GTotalSerapan/$TotalBudget*100),2)."</font></b></td></tr>";
			
			
			
			
				
			echo "<tr><td bgcolor=\"#BBFFFF\" colspan=\"14\" align=\"center\" ><b>Saldo = ( Total Persembahan - Total Pengeluaran ) = ".currency(' ',$AkumulasiPersembahan,'.',',00')." - ".currency(' ',$GTotalSerapan,'.',',00')." </b></td>
			<td align=\"right\" bgcolor=\"#BBFFFF\" colspan=\"2\"><b><font size=\"2\" >".currency(' ',$AkumulasiPersembahan-$GTotalSerapan,'.',',00')."</b></font></td><tr>";
			echo "</tbody></table>";
			echo "<br>";
			echo "<br>";


$refresh = microtime() ;
		echo "<img src=\"graph_LapPersembahan.php?Judul=$Judul&Kode=$GraphLabel&$urlPortion;$refresh \" width=\"900\" ><br>" ;





	$JumlahData = count($Kode2Komisi)-1;		

	$urlPortion='';
	$a = "datay";
	$GradasiWarna = array('#ff0000',            '#ff6600', '#ff9900', '#ffcc00', '#ffff00', '#ccff00', '#99ff00',                                             '#00ff66',            '#00ffcc', 			  '#00ccff', '#0099ff', '#0066ff', '#0033ff', '#0000ff', '#3300ff', '#6600ff', '#9900ff', '#cc00ff', '#ff00ff', '#ff00cc', '#ff0099', '#ff0066', '#ff0033'); 

	for ($i = 0; $i <= ($JumlahData); $i++) {

		$index = $i;
		${$a.$i} = $$Kode2Komisi[$index];

		$NamaArray=$a.$i;
		$DataArray=$$Kode2Komisi[$index];

	$urlPortion .=	'&'.$NamaArray.'='.$$Kode2Komisi[$index];
	$urlDGAPIPortion .=	'|'.substr($$Kode2Komisi[$index], 6);
	$Warna .=	','.substr($GradasiWarna[$index],1);
	}
		$refresh = microtime() ;
  
$urlDGAPIPortion=substr($urlDGAPIPortion, 1);
$DaftarKomisi=substr($DaftarKomisi, 1);
$Warna=substr($Warna, 1);
		echo "
<img src=\"//chart.googleapis.com/chart?chxr=0,0,".$TotalBudget."
&chxs=0N*sz2*,0000FF
&chxt=y,x,r
&chma=40,80,20,20
&chbh=a,5,15
&chs=850x350
&cht=bvs
&chxl=1:|Jan|Feb|Mar|Apr|Mei|Jun|Jul|Agt|Sep|Okt|Nop|Des|
2:|min|rata2|max
&chco=".$Warna."
&chds=a
&chd=t:".$urlDGAPIPortion."
&chdl=".$DaftarKomisi."
&chdls=0000CC,8
&chdlp=t
&chg=0,-1
&chtt=".$Judul."
&chts=0000CC,14

 alt=\"".$Judul."\" />";









			

				
?>
