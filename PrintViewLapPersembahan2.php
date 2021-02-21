<?php
/*******************************************************************************
 *
 *  filename    : PrintViewLapPersembahan2.php
 *  last change : 2013-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
$filename="PrintViewLapPersembahan2.php";
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
 

$Judul = "Informasi Laporan Rincian Persembahan Kehadiran dan Jumlah Amplop Persembahan Tahun  ".date(Y,strtotime($iTGL)); 
require "Include/Header-Report.php";



				
				echo "<table  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";
				echo "<td align=\"left\">";
				echo "<a href=\"PrintViewLapPersembahan2.php?TGL=".$yearago."\"  >";
				echo "<< Tahun sebelumnya </a></td>";
				echo "<td></td>";
				//echo "<td align=\"center\"><font size=\"3\"><b>LAPORAN REKAPITULASI PER-BIDANG TAHUN ANGGARAN ".date(Y,strtotime($iTGL))."</b></font></td>";
				echo "<td align=\"right\">"; 
				echo "<a href=\"PrintViewLapPersembahan2.php?TGL=".$nextyear."\"  >";
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
				echo "<td colspan=\"2\" ><font size=\"1\" ><u>Pers.Kantong</u><br>
				Ibadah Dewasa<br>Sek.Minggu<br>Sek.Minggu JTMY<br>Ibadah Remaja<br>Pers.Khusus<br>_________<br>Total</font></td>";
				
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
					
					// Data Persembahan Mingguan
						$sSQL = "SELECT (SUM( KebDewasa ) + SUM( KebAnak ) + SUM( KebAnakJTMY ) + SUM( KebRemaja )) AS PersKantong,
							SUM( KebDewasa ) as PersKantongDewasa, SUM( KebAnak ) as PersKantongAnak, SUM( KebAnakJTMY ) as PersKantongJTMY, SUM( KebRemaja ) as PersKantongRemaja
							FROM Persembahangkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPerTI = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPerTI));
							
					// Data Persembahan Khusus 
						$sSQL = "SELECT SUM(Persembahan) as PersembahanKhusus FROM PersembahanKhususgkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsKhusus = RunQuery($sSQL);
							extract(mysql_fetch_array($rsKhusus));
					
					// Total
					$TotalPerBulanPersKantong=$PersKantong+$PersembahanKhusus;
					$GTotalPersKantong=$GTotalPersKantong+$TotalPerBulanPersKantong;
					
					
					array_push($ArrayPersKantong, $TotalPerBulanPersKantong);
				
					
					
					echo "<td align=\"right\"><font size=\"1\" ><br>
					".currency(' ',$PersKantongDewasa,'.',',-')."<br>
					".currency(' ',$PersKantongAnak,'.',',-')."<br>
					".currency(' ',$PersKantongJTMY,'.',',-')."<br>
					".currency(' ',$PersKantongRemaja,'.',',-')."<br>
					".currency(' ',$PersembahanKhusus,'.',',-')."<br>
					____________+<br>
					
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
					$sSQL = "SELECT  IFNULL((SUM(Khusus)+SUM(SyukurBaptis)+SUM(KhususPerjamuan)+SUM(Marapas)+SUM(Marapen)+SUM(Unduh)+SUM(Maranatal)),0) as PersKhusus
							FROM Persembahangkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPerTI = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPerTI));

					// Total
					$GTotalPersKhusus=$GTotalPersKhusus+$PersKhusus;
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
			
			// Data Lainnya ........................................................
			
			echo "<b></b>";
				
				echo "<tr><td bgcolor=\"#BBFFFF\" colspan=\"16\" align=\"center\" ><b>Data Lainnya (Jumlah Kartu/Amplop/Kehadiran)</b></td><tr>";
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
				echo "<td><b>Total</b></td>";
				echo "</tr>";

		// Data Lainnya

			// Amplop Bulanan  Kartu Merah
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Pers.Bulanan (KartuMerah)</font></td>";
		
				$tbul1 = 0;
				$TotJumlahKartu=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Bulanan 
						$sSQL = "SELECT IFNULL(COUNT(PersembahanBulananID),0) as JumlahKartu FROM PersembahanBulanan a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));

					// Total
					$TotJumlahKartu=$TotJumlahKartu+$JumlahKartu;
					
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=BendMPHBulanan&DetailCek=Bulanan&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".$JumlahKartu."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".$TotJumlahKartu."</b></font></font></td></tr>";
				echo "</tr>";
		
			// Amplop Bulanan PPPG Kartu Biru
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Pers.Iman-PPPG (Kartu Biru)</font></td>";
		
				$tbul1 = 0;
				$TotJumlahKartu=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Bulanan 
						$sSQL = "SELECT IFNULL(COUNT(PersembahanPPPGID),0) as JumlahKartu FROM PersembahanPPPG a
							WHERE Pos=1 AND MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));

					// Total
					$TotJumlahKartu=$TotJumlahKartu+$JumlahKartu;
					
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=KartuBulanan&DetailCek=ULK&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".$JumlahKartu."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".$TotJumlahKartu."</b></font></font></td></tr>";
				echo "</tr>";

			// Amplop Bulanan PPPG Kartu Hijau
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Pers.Iman-PPPG-F4 (Kartu Hijau)</font></td>";
		
				$tbul1 = 0;
				$TotJumlahKartu=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Bulanan 
						$sSQL = "SELECT IFNULL(COUNT(PersembahanPPPGID),0) as JumlahKartu FROM PersembahanPPPG a
							WHERE Pos=4 AND MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));

					// Total
					$TotJumlahKartu=$TotJumlahKartu+$JumlahKartu;
					
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=KartuBulanan&DetailCek=ULK&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".$JumlahKartu."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".$TotJumlahKartu."</b></font></font></td></tr>";
				echo "</tr>";	

			// Amplop Bulanan PPPG Amplop Biru
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Pers. Amplop.Biru</font></td>";
		
				$tbul1 = 0;
				$TotJumlahKartu=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Persembahan Bulanan 
						$sSQL = "SELECT IFNULL(SUM(JmlAmplop),0) as JumlahKartu FROM PersembahanPPPG a
							WHERE Pos=3 AND MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));

					// Total
					$TotJumlahKartu=$TotJumlahKartu+$JumlahKartu;
					//echo $sSQL;
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=KartuBulanan&DetailCek=ULK&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".$JumlahKartu."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".$TotJumlahKartu."</b></font></font></td></tr>";
				echo "</tr>";

			// Kehadiran Sebulan
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Kehadiran Jemaat_Dewasa</font></td>";
		
				$tbul1 = 0;
				$TotJmlJemaat=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Kehadiran
						$sSQL = "SELECT IFNULL((SUM(Pria)+SUM(Wanita)),0) as JmlJemaat FROM Persembahangkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));

					// Total
					$TotJmlJemaat=$TotJmlJemaat+$JmlJemaat;
					
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=KartuBulanan&DetailCek=ULK&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".$JmlJemaat."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".$TotJmlJemaat."</b></font></font></td></tr>";
				echo "</tr>";
				
			// Kehadiran Sebulan
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Kehadiran Jemaat_AnakSM</font></td>";
		
				$tbul1 = 0;
				$TotJmlJemaat=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Kehadiran
						$sSQL = "SELECT IFNULL((SUM(Pria)+SUM(Wanita)),0) as JmlJemaat FROM PersembahanAnakgkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));

					// Total
					$TotJmlJemaat=$TotJmlJemaat+$JmlJemaat;
					
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=KartuBulanan&DetailCek=ULK&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".$JmlJemaat."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".$TotJmlJemaat."</b></font></font></td></tr>";
				echo "</tr>";

			// Kehadiran Sebulan
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Kehadiran Jemaat_Remaja</font></td>";
		
				$tbul1 = 0;
				$TotJmlJemaat=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Kehadiran
						$sSQL = "SELECT IFNULL((SUM(Pria)+SUM(Wanita)),0) as JmlJemaat FROM PersembahanRemajagkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));

					// Total
					$TotJmlJemaat=$TotJmlJemaat+$JmlJemaat;
					
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=KartuBulanan&DetailCek=ULK&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".$JmlJemaat."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".$TotJmlJemaat."</b></font></font></td></tr>";
				echo "</tr>";
				
			// Kehadiran Sebulan
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Jml.Peribadahan.Dws (per Tanggal)</font></td>";
		
				$tbul1 = 0;
				$TotJmlJemaat=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Kehadiran
						$sSQL = "SELECT COUNT(DISTINCT(Tanggal)) as JmlJemaat FROM Persembahangkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));
//echo $sSQL;
					// Total
					$TotJmlJemaat=$TotJmlJemaat+$JmlJemaat;
					
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=KartuBulanan&DetailCek=ULK&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".$JmlJemaat."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".$TotJmlJemaat."</b></font></font></td></tr>";
				echo "</tr>";

			// Kehadiran Sebulan
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Jml.Peribadahan.Anak (per Tanggal)</font></td>";
		
				$tbul1 = 0;
				$TotJmlJemaat=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Kehadiran
						$sSQL = "SELECT COUNT(DISTINCT(Tanggal)) as JmlJemaat FROM PersembahanAnakgkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));
//echo $sSQL;
					// Total
					$TotJmlJemaat=$TotJmlJemaat+$JmlJemaat;
					
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=KartuBulanan&DetailCek=ULK&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".$JmlJemaat."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".$TotJmlJemaat."</b></font></font></td></tr>";
				echo "</tr>";
	
			// Kehadiran Sebulan
				echo "<tr>";
				echo "<td><font size=\"1\" >".$i."</font></td>";
				echo "<td colspan=\"2\" ><font size=\"1\" >Jml.Peribadahan.Remaja (per Tanggal)</font></td>";
		
				$tbul1 = 0;
				$TotJmlJemaat=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				//extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					
					// Data Kehadiran
						$sSQL = "SELECT COUNT(DISTINCT(Tanggal)) as JmlJemaat FROM PersembahanRemajagkjbekti a
							WHERE MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3;
							$rsPersembahan = RunQuery($sSQL);
							extract(mysql_fetch_array($rsPersembahan));
//echo $sSQL;
					// Total
					$TotJmlJemaat=$TotJmlJemaat+$JmlJemaat;
					
					echo "<td align=\"right\"><font size=\"1\" >
					<a href=\"PrintViewCekPersembahan.php?Bul=".$tbul1."&Cek=KartuBulanan&DetailCek=ULK&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					".$JmlJemaat."</a></font></td>";
				}
				echo "<td align=\"right\"><font size=\"1\" ><b>".$TotJmlJemaat."</b></font></font></td></tr>";
				echo "</tr>";	
				
			// Budget
		echo "</tbody></table>";
			echo "<br>";
			echo "<br>";


















			

				
?>
