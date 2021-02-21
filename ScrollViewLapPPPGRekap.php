<?php
/*******************************************************************************
 *
 *  filename    : PrintViewLapPPPG.php
 *  last change : 2013-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
$filename="PrintViewLapPPPG.php";
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
 

$Judul = "Informasi Laporan Rincian PPPG Tahun  ".date(Y,strtotime($iTGL)); 

?>
<!doctype html>
<head>
<meta charset="utf-8" />
     <style type="text/css">
	
            table { border: 0px solid #ccc; display: inline-table; margin: 0; padding: 0; }
			.boldtable, .boldtable TD, .boldtable TH
			{
			font-family:arial;
			font-size:20pt;
			color:navy;
			background-color:#E6E6FA;
			height: 67px;
			}
          </style>
 
</head>
<?

// Detail Laporan

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<b></b>";
			if($iRekap!=YES){		
				echo "<table   border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";
				echo "<tr><td bgcolor=\"#BBFFFF\" colspan=\"16\" align=\"center\" ><b>Penerimaan</b></td><tr>";
			}else{
				echo "<table   border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=380>";
				echo "<tr><td bgcolor=\"#BBFFFF\" colspan=\"5\" align=\"center\" ><b>Penerimaan PPPG </b></td><tr>";
			}
				echo "<tbody  align=\"center\" >";
				echo "<td width=10></td><td  width=180>Keterangan</td>";

			if($iRekap!=YES){	
				while ($bul < 12 )
				{
				$bul++;
				$bulan = date(Y,strtotime($iTGL))."-".$bul."-".date(d,strtotime($iTGL));
				//echo $bulan;
				echo "<td width=\"100\" >".date2Ind($bulan,6)."</td>";
				}
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
		if($iRekap!=YES){		
			echo "<tr><td></td><td ><b><font size=\"4\" >Saldo Tahun ".($ThnKemaren-1)."</font></b></td><td colspan=\"13\" ></td>
			<td><b><font size=\"4\" >".currency(' ',$SaldoThnKemaren,'.',',-')."</font></b></td></tr>";
		}else{
			echo "<tr><td></td><td ><b><font size=\"4\" >Saldo Tahun ".($ThnKemaren-1)."</font></b></td>
			<td align=\"right\" ><b><font size=\"4\" >".currency(' ',$SaldoThnKemaren,'.',',-')."</font></b></td></tr>";

		}		
			
			// Data Rincian
			$sSQL = "SELECT a.*,b.* FROM PersembahanPPPG a
			LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis
			WHERE YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL))." 
			 GROUP BY a.Pos ";
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
				$PosSubTotalBulanan+=$TotalPerBulan1;
				echo "<tr>";
				echo "<td><font size=\"4\" >".$i."</font></td>";
				echo "<td align=\"left\"><font size=\"4\" >".$Keterangan."</font></td>";
				
			//Data Perbulan				
				$tbul1 = 0;
				$GTotalSerapan1=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				
				$bulan1 = date(Y,strtotime($iTGL))."-".$tbul1."-".date(d,strtotime($iTGL));
				$sSQL14 = "SELECT SUM(a.Bulanan) as TotalPerBulan1 FROM PersembahanPPPG a
				LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis 
				WHERE a.Pos=".$Pos." AND MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3."
				";
				
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsPPPGPerBulan1 = RunQuery($sSQL14);
				//echo $sSQL14;

				while ($aRow = mysql_fetch_array($rsPPPGPerBulan1))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					$GTotalSerapan1+=$TotalPerBulan1;
								if($iRekap!=YES){	
					echo "<td align=\"right\"><font size=\"4\" >
					<a href=\"PrintViewCekPersembahan.php?Cek=PPPG&TGL=".$iTGL."&Pos=".$Pos."&Bul=".$tbul1."\" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
					<b>".currency(' ',$TotalPerBulan1,'.',',-')."</b></a></font></td>";
					}
					}

			
				}
				echo "<td align=\"right\"><font size=\"4\" ><b>".currency(' ',$GTotalSerapan1,'.',',-')."</b></font></font></td></tr>";
				echo "</tr>";
			}
			echo "<tr>";
			echo "<td align=\"right\" colspan=\"2\"><font size=\"2\" >Total Persembahan</font></td>";
			
			$tbul2 = 0;
				$GTotalSerapan2=0;
				while ($tbul2 < 12 )
				{
				$tbul2++;
				
				$bulan1 = date(Y,strtotime($iTGL))."-".$tbul2."-".date(d,strtotime($iTGL));
				$sSQL15 = "SELECT SUM(a.Bulanan) as TotalPerBulan2 FROM PersembahanPPPG a
				LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis 
				WHERE MONTH(a.Tanggal) = ".$tbul2." ".$sSQLTanggal3."
				";
				
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsPPPGPerBulan2 = RunQuery($sSQL15);
				//echo $sSQL14;

				while ($aRow = mysql_fetch_array($rsPPPGPerBulan2))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					$GTotalSerapan2+=$TotalPerBulan2;
							if($iRekap!=YES){	
								echo "<td align=\"right\"><font size=\"4\" ><b>".currency(' ',$TotalPerBulan2,'.',',-')."</b></font></td>";
							}
					}
		
				}
				
		echo "<td align=\"right\"><b><font size=\"4\" >".currency(' ',$GTotalSerapan2,'.',',00')."</b></font></td>";
		$TotalPemasukan=$GTotalSerapan2+$SaldoThnKemaren;
		//$TotalPemasukan=$GTotalSerapan2;
			echo "</tr>";
			
// Akumulasi
			echo "<tr>";
			echo "<td align=\"right\" colspan=\"2\"><font size=\"2\" >Akumulasi Persembahan</font></td>";
			
			$tbul2 = 0;
				$GTotalSerapan2=0;
				while ($tbul2 < 12 )
				{
				$tbul2++;
				
				$bulan1 = date(Y,strtotime($iTGL))."-".$tbul2."-".date(d,strtotime($iTGL));
				$sSQL15 = "SELECT SUM(a.Bulanan) as TotalPerBulan2 FROM PersembahanPPPG a
				LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis 
				WHERE MONTH(a.Tanggal) = ".$tbul2." ".$sSQLTanggal3."
				";
				
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsPPPGPerBulan2 = RunQuery($sSQL15);
				//echo $sSQL14;

				while ($aRow = mysql_fetch_array($rsPPPGPerBulan2))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					$GTotalSerapan2+=$TotalPerBulan2;
								if($iRekap!=YES){	
								echo "<td align=\"right\"><font size=\"4\" ><b>".currency(' ',$GTotalSerapan2,'.',',-')."</b></font></td>";
								}
					}
		
				}
				
		echo "<td align=\"right\"><b><font size=\"4\" >".currency(' ',$TotalPemasukan,'.',',00')."</b></font></td>";
		$TotalPemasukan=$GTotalSerapan2+$SaldoThnKemaren;
		//$TotalPemasukan=$GTotalSerapan2;
			echo "</tr>";			
			
			echo "</tbody>";
			
			// Pengeluaran
			
			echo "<b></b>";
				

			if($iRekap!=YES){	
				echo "<tr><td bgcolor=\"#BBFFFF\" colspan=\"16\" align=\"center\" ><b>Pengeluaran</b></td><tr>";
			}else{
				echo "<tr><td bgcolor=\"#BBFFFF\" colspan=\"5\" align=\"center\" ><b>Pengeluaran</b></td><tr>";
			}
			
				echo "<tbody  align=\"center\" >";
				echo "<td width=10></td><td width=50> Keterangan</td>";
				
				$bul=0;
			if($iRekap!=YES){		
				while ($bul < 12 )
				{
				$bul++;
				$bulan = date(Y,strtotime($iTGL))."-".$bul."-".date(d,strtotime($iTGL));
				//echo $bulan;
				echo "<td width=\"100\" >".date2Ind($bulan,6)."</td>";
				}
			}
				echo "<td width=40><b>Total</b></td>";
				echo "</tr>";

			$sSQL = "SELECT a.*,b.* FROM PengeluaranPPPG a
			LEFT JOIN JenisPengeluaranPPPG b ON a.Pos = b.KodeJenis
			WHERE YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL))." 
			GROUP BY a.Pos ";
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
				$PosSubTotalBulanan+=$TotalPerBulan1;
				echo "<tr>";
				echo "<td><font size=\"4\" >".$i."</font></td>";
				echo "<td align=\"left\"><font size=\"4\" >".$Keterangan."</font></td>";

				
			//Data Perbulan				
				$tbul1 = 0;
				$GTotalSerapan1=0;
				while ($tbul1 < 12 )
				{
				$tbul1++;
				
				$bulan1 = date(Y,strtotime($iTGL))."-".$tbul1."-".date(d,strtotime($iTGL));
				$sSQL14 = "SELECT SUM(a.Jumlah) as TotalPerBulan1 FROM PengeluaranPPPG a
				LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis 
				WHERE a.Pos=".$Pos." AND MONTH(a.Tanggal) = ".$tbul1." ".$sSQLTanggal3."
				";
				
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsPPPGPerBulan1 = RunQuery($sSQL14);
				//echo $sSQL14;

				while ($aRow = mysql_fetch_array($rsPPPGPerBulan1))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					$GTotalSerapan1+=$TotalPerBulan1;
						if($iRekap!=YES){		
						echo "<td align=\"right\"><font size=\"4\" >
						<a href=\"PrintViewCekPersembahan.php?Cek=PengeluaranPPPG&TGL=".$iTGL."&Pos=".$Pos."&Bul=".$tbul1."\" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
						<b>".currency(' ',$TotalPerBulan1,'.',',-')."</b></a></font></td>";
						}
					}

			
				}
				echo "<td align=\"right\"><font size=\"4\" ><b>".currency(' ',$GTotalSerapan1,'.',',-')."</b></font></font></td></tr>";
				echo "</tr>";
			}
			echo "<tr>";
			echo "<td align=\"right\" colspan=\"2\"><font size=\"2\" >Total Pengeluaran</font></td>";
			
			$tbul2 = 0;
				$GTotalSerapan2=0;
				while ($tbul2 < 12 )
				{
				$tbul2++;
				
				$bulan1 = date(Y,strtotime($iTGL))."-".$tbul2."-".date(d,strtotime($iTGL));
				$sSQL15 = "SELECT SUM(a.Jumlah) as TotalPerBulan2 FROM PengeluaranPPPG a
				LEFT JOIN JenisPengeluaranPPPG b ON a.Pos = b.KodeJenis 
				WHERE MONTH(a.Tanggal) = ".$tbul2." ".$sSQLTanggal3."
				";
				
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsPPPGPerBulan2 = RunQuery($sSQL15);
				//echo $sSQL14;

				while ($aRow = mysql_fetch_array($rsPPPGPerBulan2))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					$GTotalSerapan2+=$TotalPerBulan2;
								if($iRekap!=YES){	
					echo "<td align=\"right\"><font size=\"4\" ><b>".currency(' ',$TotalPerBulan2,'.',',-')."</b></font></td>";
					}
					}
		
				}
		echo "<td align=\"right\"><b><font size=\"4\" >".currency(' ',$GTotalSerapan2,'.',',00')."</b></font></td>";

if($iRekap!=YES){	
// Akumulasi 
			echo "<tr>";
			echo "<td align=\"right\" colspan=\"3\"><font size=\"2\" >Akumulasi Pengeluaran</font></td>";
			
			$tbul2 = 0;
				$GTotalSerapan2=0;
				while ($tbul2 < 12 )
				{
				$tbul2++;
				
				$bulan1 = date(Y,strtotime($iTGL))."-".$tbul2."-".date(d,strtotime($iTGL));
				$sSQL15 = "SELECT SUM(a.Jumlah) as TotalPerBulan2 FROM PengeluaranPPPG a
				LEFT JOIN JenisPengeluaranPPPG b ON a.Pos = b.KodeJenis 
				WHERE MONTH(a.Tanggal) = ".$tbul2." ".$sSQLTanggal3."
				";
				
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsPPPGPerBulan2 = RunQuery($sSQL15);
				//echo $sSQL14;

				while ($aRow = mysql_fetch_array($rsPPPGPerBulan2))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					$GTotalSerapan2+=$TotalPerBulan2;
								if($iRekap!=YES){	
					echo "<td align=\"right\"><font size=\"4\" ><b>".currency(' ',$GTotalSerapan2,'.',',-')."</b></font></td>";
					}
					}
		
				}
		echo "<td align=\"right\"><b><font size=\"4\" >".currency(' ',$GTotalSerapan2,'.',',00')."</b></font></td>";		
		
		$TotalPengeluaran=$GTotalSerapan2;
			echo "</tr>";
		
}			
		if($iRekap!=YES){		
			echo "<tr><td bgcolor=\"#BBFFFF\" colspan=\"15\" align=\"center\" ><b>Saldo</b></td>";
		}else{
			echo "<tr><td bgcolor=\"#BBFFFF\" colspan=\"2\" align=\"center\" ><b>Saldo</b></td>";
		}

	
		
			echo "<td align=\"right\" bgcolor=\"#BBFFFF\"><b><font size=\"4\" >".currency(' ',$TotalPemasukan-$GTotalSerapan2,'.',',00')."</b></font></td><tr>";
			echo "</tbody></table>";
			echo "<br>";
			echo "<br>";


















			

				
?>
