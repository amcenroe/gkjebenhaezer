<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPengeluaranPerBidang.php
 *  last change : 2013-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
$filename="PrintViewPengeluaranKasKecil.php";
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

$iTGL = date("Y")."-1-1";
$hariini = strtotime(date("Y-m-d"));
//echo $hariini;
//$iTGL = date("Y-m-d", strtotime('last Sunday', $hariini));

//echo $iTGL;
$mingguterakhir = date("Y-m-d", strtotime('last Sunday', $hariini));
$minggukemaren = date("Y-m-d", strtotime('-1 week', strtotime($mingguterakhir)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', $hariini));
	$tanggal=$iTGL;
	$time = strtotime($tanggal);
	//echo $time;
	$monthago = date("Y-m-d", strtotime("-1 month", $time));
	$nextmonth = date("Y-m-d", strtotime("+1 month", $time));
			$yearago = date("Y-m-d", strtotime("-1 year", $time));
			$nextyear = date("Y-m-d", strtotime("+1 year", $time));
}
 

$Judul = "Informasi Rekapitulasi Pengeluaran Per Bidang Tahun Anggaran ".date(Y,strtotime($iTGL)); 
require "Include/Header-Report.php";


				
				echo "<table  cellpadding=\"0\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";
				echo "<td align=\"left\">";
				echo "<a href=\"PrintViewPengeluaranPerBidang2.php?TGL=".$yearago."\"  >";
				echo "<< Tahun sebelumnya </a></td>";
				echo "<td></td>";
				echo "<td align=\"center\" title=\"Klik Disini untuk mode Detail\"><a href=\"PrintViewPengeluaranPerBidang.php\"  ><font size=\"3\"><b>Rekapitulasi</b></font></a></td>";
				echo "<td align=\"right\">"; 
				echo "<a href=\"PrintViewPengeluaranPerBidang2.php?TGL=".$nextyear."\"  >";
				echo "Tahun Berikutnya >></a></td>";
				echo "</table>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=900>";

				
// Detail Laporan
				$ArrayNamaKomisi = array();
				$ArrayPengeluaranKomisi = array();
				$GraphLabel='';
// SUmary Per Komisi=========================================================================================
				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				//echo "<b>LAPORAN REKAPITULASI PER-BIDANG TAHUN ANGGARAN ".date(Y,strtotime($iTGL))."</b>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td rowspan=\"2\"><b>Nomor</b></td>";
				echo "<td rowspan=\"2\"><b>KODE</b></td>";
				echo "<td ><b>Nama Bidang</b></td>";
				echo "<td rowspan=\"2\"><b>Rencana <br> Anggaran (Rupiah)</b></td>";
			//	echo "<td ><b>Pengeluaran (Rupiah)</b></td>";
				echo "<td rowspan=\"2\"><b>Total Pengeluaran<br> (Rupiah) </b></td>";
				echo "<td rowspan=\"2\"><b>Prosentasi <br>( % )</b></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>Nama Komisi</td>";
				

				echo "</tr>";
				
				echo "<tr><td colspan=\"4\" bgcolor=\"aqua\" align=\"left\" ><font size=\"2\" ><b>KAS JEMAAT : </b></font></td></tr>";

			
			// Data Set
			
			//Kelompok2
	$sSQL0 = "select d.Kelompok as Kelompok, d.KetKelompok as KetKelompok FROM PengeluaranKasKecil	a
			LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
			LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
			LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
			WHERE d.BidangID > 0 ".$sSQLTanggal3." 
			GROUP BY d.Kelompok ORDER BY d.Kelompok";
			//echo $sSQL0;	
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
	echo "<td><font size=\"2\" >".integerToRoman($e)."</font></td>";
	echo "<td colspan=\"3\" bgcolor=\"yellow\" align=\"left\" ><font size=\"2\" ><b>".strtoupper($KetKelompok)."</b></font></td>";
	echo "<td  ></td>";
	}else	if ($Kelompok == 3){
	
	// Sub Total untuk kas jemaat
	
				echo "<tr><td colspan=\"3\" ><b>Sub Total Kas Jemaat</b></td>";
				
				$sSQL15 = "select SUM(Budget) as TotalBudget1 FROM MasterAnggaran 
				WHERE TahunAnggaran=".$iTGLnow;
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsBudget1 = RunQuery($sSQL15);
				//echo $sSQL15;
				while ($aRow = mysql_fetch_array($rsBudget1))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					echo "<td align=\"right\"><font size=\"2\" ><b>".currency(' ',$TotalBudget1,'.',',-')."</b></font></td>";
					}
					
				
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
				//echo $sSQL3;
				if (mysql_num_rows($rsPengeluaranPerBulan1)>0) {
				while ($aRow = mysql_fetch_array($rsPengeluaranPerBulan1))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					$GTotalSerapan1+=$TotalPerBulan1;
					//echo "<td align=\"right\"><font size=\"2\" ><b>".currency(' ',$TotalPerBulan1,'.',',-')."</b></font></td>";
					}
				}else{
					//	echo "<td align=\"right\"></td>";
					}
					
				}
					echo "<td align=\"right\"><font size=\"2\" ><b>".currency(' ',$GTotalSerapan1,'.',',-')."</b></font></font></td><td align=\"center\" ><b>".round(@($GTotalSerapan1/$TotalBudget1*100),2)."</b></td></tr>";
	
	// akhir data sub total khas jemaat
	
	
	echo "<tr bgcolor=\"fcfcfc\">";
	echo "<tr><td colspan=\"4\" bgcolor=\"aqua\" align=\"left\" ><font size=\"2\" ><b>Kas Lain Lain: </b></font></td></tr>";
	echo "<td><font size=\"2\" >".integerToRoman($e)."</font></td>";
	echo "<td colspan=\"3\" bgcolor=\"yellow\" align=\"left\" ><font size=\"2\" ><b>".strtoupper($KetKelompok)."</b></font></td>";
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
			//echo $sSQL;	
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
				echo "<td><font size=\"2\" >".integerToRoman($e)."</font></td>";
				echo "<td colspan=\"3\" bgcolor=\"yellow\" align=\"left\" ><b><font size=\"2\" >".strtoupper($NamaBidang)."</font></b></td>";
				echo "<td></td>";
				}
				//echo "<td align=\"right\"><b>".currency(' ',$SubTotal,'.',',-')."</b></td>";
				
				echo "</tr>";
				
				$sSQL1 = "select a.*, SUM(a.Jumlah) as TotalKomisi, b.*, c.*, d.*, e.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				LEFT JOIN MasterAnggaran e ON b.KomisiID = e.KomisiID AND e.TahunAnggaran ".$sSQLTanggal4."
				WHERE c.BidangID=".$Bidang." ".$sSQLTanggal3." 
				
				GROUP BY c.KomisiID ORDER BY c.KomisiID";
		//	echo "<br><br>".$sSQL1;	
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
				echo "<td><font size=\"2\" >".$KodeKomisi."</font></td>";
				echo "<td><font size=\"2\" >".$NamaKomisi."</font></td>";
				echo "<td align=\"right\"><font size=\"2\" >".currency(' ',$Budget,'.',',-')."</font></td>";
	//			"Balita\n%.1f%%"
		//		$GraphLabel=$GraphLabel.",\"".$KodeKomisi."\n%.1f%%\"";
				
				
				$Bul=0;
				$GTotalPerTahun=0;
				while ($Bul < 12 )
				{
				
		//		if ($KomisiID==''){$KomisiID=4;}
				$Bul++;
				//echo
				// ini
				$sSQL3 = "select a.*, SUM(a.Jumlah) as TotalPerBulan, b.*, c.*, d.*, e.Budget as AnggaranPerKomisi  FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				LEFT JOIN MasterAnggaran e ON b.KomisiID = e.KomisiID AND e.TahunAnggaran ".$sSQLTanggal4."
				WHERE c.BidangID = ".$Bidang." AND b.KomisiID = ".$KomisiID." AND MONTH(a.Tanggal) = ".$Bul." ".$sSQLTanggal3." 
				GROUP BY c.KomisiID ORDER BY c.KomisiID";
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsPengeluaranPerBulan = RunQuery($sSQL3);
		//		echo $sSQL3."<br><br>";
				if (mysql_num_rows($rsPengeluaranPerBulan)>0) {
				while ($aRow = mysql_fetch_array($rsPengeluaranPerBulan))
				{
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				$GTotalPerTahun=$GTotalPerTahun+$TotalPerBulan;
				//echo "<td align=\"right\"><font size=\"2\" >".currency(' ',$TotalPerBulan,'.',',-')."</font></td>";
				}
				}else
				{
				//echo "<td align=\"right\"></td>";
				}
				
				}
				
				echo "<td align=\"right\"><font size=\"2\" ><b>".currency(' ',$GTotalPerTahun,'.',',-')."</b></font></td>";
				echo "<td align=\"center\"><font size=\"2\" ><b>".round(@($GTotalPerTahun/$AnggaranPerKomisi*100),2)."</b></font></td>";
				echo "</tr>";
				
				
				array_push($ArrayPengeluaranKomisi, $GTotalPerTahun);
				array_push($ArrayNamaKomisi, $KodeKomisi);
			//	$GraphLabel=$GraphLabel.",".$KodeKomisi;
				$GraphLabel=$GraphLabel.",".$KodeKomisi." : \n%.1f%%";
				}

			}
	}
			// Budget
			
			

				echo "<tr><td colspan=\"3\" ><b>Total</b></td>";
				
				$sSQL5 = "select SUM(Budget) as TotalBudget FROM MasterAnggaran 
				WHERE TahunAnggaran ".$sSQLTanggal4;
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsBudget = RunQuery($sSQL5);
				//echo $sSQL5;
				while ($aRow = mysql_fetch_array($rsBudget))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					echo "<td align=\"right\"><font size=\"2\" ><b>".currency(' ',$TotalBudget,'.',',-')."</b></font></td>";
					}
					
				
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
				$sSQL4 = "select a.*, SUM(a.Jumlah) as TotalPerBulan, b.*, c.*, d.* FROM PengeluaranKasKecil	a
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
				//	echo "<td align=\"right\"><font size=\"2\" ><b>".currency(' ',$TotalPerBulan,'.',',-')."</b></font></td>";
					}
				}else{
				//		echo "<td align=\"right\"></td>";
					}
					
				}
					echo "<td align=\"right\"><font size=\"2\" ><b>".currency(' ',$GTotalSerapan,'.',',-')."</b></font></font></td><td><b>".round(@($GTotalSerapan/$TotalBudget*100),2)."</b></td></tr>";
					

			echo "</tbody></table>";
			echo "<br>";
			echo "<br>";

		$GraphLabel = substr($GraphLabel, 1);
		$urlPortion= '&data='.urlencode(serialize($ArrayPengeluaranKomisi)).
					  '&label='.urlencode(serialize($ArrayNamaKomisi));

					  
//Full URL:
//$fullUrl='http://localhost/?somevariable=somevalue'.$urlPortion;
//echo "graph_array.php?Kode=$GraphLabel.$urlPortion;$refresh";
$refresh = microtime() ;
		echo "<img src=\"graph_array.php?Judul=$Judul&Kode=$GraphLabel&$urlPortion;$refresh \" width=\"450\" ><br>" ;

			


				
?>
