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

$Kode2Komisi=array();
				
				echo "<table  cellpadding=\"0\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";
				echo "<td align=\"left\">";
				echo "<a href=\"PrintViewPengeluaranPerBidang.php?TGL=".$yearago."\"  >";
				echo "<< Tahun sebelumnya </a></td>";
				echo "<td></td>";
				echo "<td align=\"center\" title=\"Klik Disini untuk mode Ringkas\"><a href=\"PrintViewPengeluaranPerBidang2.php\"  ><font size=\"3\"><b>Detail</b></font></a></td>";
				echo "<td align=\"right\">"; 
				echo "<a href=\"PrintViewPengeluaranPerBidang.php?TGL=".$nextyear."\"  >";
				echo "Tahun Berikutnya >></a></td>";
				echo "</table>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=900>";

				
// Detail Laporan

// SUmary Per Komisi=========================================================================================
				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				//echo "<p><b>LAPORAN REKAPITULASI PER-BIDANG TAHUN ANGGARAN ".date(Y,strtotime($iTGL))."</b></p>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=1000>";
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td rowspan=\"2\"><b>Nomor</b></td>";
				echo "<td rowspan=\"2\"><b>KODE</b></td>";
				echo "<td ><b>Nama Bidang</b></td>";
				echo "<td rowspan=\"2\"><b>Rencana <br> Anggaran (Rupiah)</b></td>";
				echo "<td colspan=\"12\"><b>Pengeluaran (Rupiah)</b></td>";
				echo "<td rowspan=\"2\"><b>Total <br> (Rupiah) </b></td>";
				echo "<td rowspan=\"2\"><b>Prosentasi <br>( % )</b></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>Nama Komisi</td>";
				
				$bul = 0;
				$GTotalPerBulan=0;
				while ($bul < 12 )
				{
				$bul++;
				$bulan = date(Y,strtotime($iTGL))."-".$bul."-".date(d,strtotime($iTGL));
				//echo $bulan;
				echo "<td width=\"100\" >".date2Ind($bulan,6)."</td>";
				}

				echo "</tr>";
				
				echo "<tr><td colspan=\"4\" bgcolor=\"aqua\" align=\"left\" ><p><font size=\"1\" ><b>KAS JEMAAT : </b></font></p></td></tr>";

			
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
	echo "<td><p><font size=\"1\" >".integerToRoman($e)."</font></p></td>";
	echo "<td colspan=\"3\" bgcolor=\"yellow\" align=\"left\" ><p><font size=\"1\" ><b>".strtoupper($KetKelompok)."</b></font></p></td>";
	echo "<td colspan=\"15\" ><p></p></td>";
	}else	if ($Kelompok == 3){
	
	// Sub Total untuk kas jemaat
	
				echo "<tr><td colspan=\"3\" ><b>Sub Total Kas Jemaat</b></td>";
				
				//$sSQL15 = "select SUM(Budget) as TotalBudget1 FROM MasterAnggaran 
				//WHERE TahunAnggaran=".$iTGLnow;
				$sSQL15 = "select SUM(AggKasJemaat) as TotalBudget1 FROM ProgramDanAnggaran a 
				WHERE Tahun".$sSQLTanggal4;
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsBudget1 = RunQuery($sSQL15);
				//echo $sSQL15;
				while ($aRow = mysql_fetch_array($rsBudget1))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					echo "<td align=\"right\"><p><font size=\"1\" ><b>".currency(' ',$TotalBudget1,'.',',-')."</b></font></p></td>";
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
					echo "<td align=\"right\"><p><font size=\"1\" ><b>".currency(' ',$TotalPerBulan1,'.',',-')."</b></font></p></td>";
					}
				}else{
						echo "<td align=\"right\"><p></p></td>";
					}
					
				}
					echo "<td align=\"right\"><p><font size=\"1\" ><b>".currency(' ',$GTotalSerapan1,'.',',-')."</b></font></font></p></td><td align=\"center\" ><b>".round(@($GTotalSerapan1/$TotalBudget1*100),2)."</b></td></tr>";
	
	// akhir data sub total khas jemaat
	
	
	echo "<tr bgcolor=\"fcfcfc\">";
	echo "<tr><td colspan=\"4\" bgcolor=\"aqua\" align=\"left\" ><p><font size=\"1\" ><b>Kas Lain Lain: </b></font></p></td></tr>";
	echo "<td><p><font size=\"1\" >".integerToRoman($e)."</font></p></td>";
	echo "<td colspan=\"3\" bgcolor=\"yellow\" align=\"left\" ><p><font size=\"1\" ><b>".strtoupper($KetKelompok)."</b></font></p></td>";
	echo "<td colspan=\"15\" ><p></p></td>";
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
				echo "<td><p><font size=\"1\" >".integerToRoman($e)."</font></p></td>";
				echo "<td colspan=\"3\" bgcolor=\"yellow\" align=\"left\" ><b><p><font size=\"1\" >".strtoupper($NamaBidang)."</font></p></b></td>";
				echo "<td><p></p></td>";
				}
				//echo "<td align=\"right\"><b><p>".currency(' ',$SubTotal,'.',',-')."</b></p></td>";
				
				echo "</tr>";
				
				$sSQL1 = "select a.*, SUM(a.Jumlah) as TotalKomisi, b.*, c.*, c.KomisiID as cKomisiID, d.*, e.*, e.AggKasJemaat as Budget FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				LEFT JOIN (SELECT Tahun,SUM(AggKasJemaat) as AggKasJemaat,KomisiID FROM ProgramDanAnggaran WHERE Tahun ".$sSQLTanggal4." GROUP BY KomisiID ) e ON b.KomisiID = e.KomisiID AND e.Tahun ".$sSQLTanggal4."

				WHERE c.BidangID=".$Bidang." ".$sSQLTanggal3." 
				
				GROUP BY c.KomisiID ORDER BY c.KomisiID";
				
			//echo $sSQL1;	
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
				echo "<td><p></p></td>";
				echo "<td><p><font size=\"1\" >".$KodeKomisi."</font></p></td>";
				echo "<td><p><font size=\"1\" >".$NamaKomisi."</font></p></td>";
				echo "<td align=\"right\"><p><font size=\"1\" >".currency(' ',$Budget,'.',',-')."</font></p></td>";
				
				
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
				$sSQL3 = "select a.*, SUM(a.Jumlah) as TotalPerBulan, b.*, c.*,  d.*, e.AggKasJemaat as AnggaranPerKomisi  FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				LEFT JOIN (SELECT Tahun,SUM(AggKasJemaat) as AggKasJemaat,KomisiID FROM ProgramDanAnggaran WHERE Tahun ".$sSQLTanggal4." GROUP BY KomisiID ) e ON b.KomisiID = e.KomisiID AND e.Tahun ".$sSQLTanggal4."
				WHERE c.BidangID=".$Bidang." AND b.KomisiID=".$cKomisiID." AND MONTH(a.Tanggal) = ".$Bul." ".$sSQLTanggal3." 
				GROUP BY c.KomisiID ORDER BY c.KomisiID";
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsPengeluaranPerBulan = RunQuery($sSQL3);
				//echo $sSQL3;
				if (mysql_num_rows($rsPengeluaranPerBulan)>0) {
				while ($aRow = mysql_fetch_array($rsPengeluaranPerBulan))
				{
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				$GTotalPerTahun=$GTotalPerTahun+$TotalPerBulan;
				echo "<td align=\"right\"><p><font size=\"1\" >".currency(' ',$TotalPerBulan,'.',',-')."</font></p></td>";
				//array_push($$KodeKomisi, $TotalPerBulan);
				$$KodeKomisi=$$KodeKomisi.",".$TotalPerBulan;
				}
				}else
				{
				echo "<td align=\"right\"><p></p></td>";
				//array_push($$KodeKomisi, 0);
				$$KodeKomisi=$$KodeKomisi.",0";
				}
				
				}
				
				echo "<td align=\"right\"><p><font size=\"1\" ><b>".currency(' ',$GTotalPerTahun,'.',',-')."</b></font></p></td>";
				echo "<td align=\"center\"><p><font size=\"1\" ><b>".round(@($GTotalPerTahun/$AnggaranPerKomisi*100),2)."</b></font></p></td>";
				echo "</tr>";
				
				}

			}
	}
			// Budget
			
			

				echo "<tr><td colspan=\"3\" ><b>Total</b></td>";
				
				
				$sSQL5 = "select SUM(AggKasJemaat) as TotalBudget FROM ProgramDanAnggaran a 
				WHERE Tahun".$sSQLTanggal4;	
				
			//	$sSQL5 = "select SUM(Budget) as TotalBudget FROM MasterAnggaran 
			//	WHERE TahunAnggaran ".$sSQLTanggal4;
				//$sSQLTanggal3 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
				$rsBudget = RunQuery($sSQL5);
				//echo $sSQL5;
				while ($aRow = mysql_fetch_array($rsBudget))
					{
					extract($aRow);
					//Alternate the row style
					$sRowClass = AlternateRowStyle($sRowClass); 
					echo "<td align=\"right\"><p><font size=\"1\" ><b>".currency(' ',$TotalBudget,'.',',-')."</b></font></p></td>";
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
					echo "<td align=\"right\"><p><font size=\"1\" ><b>".currency(' ',$TotalPerBulan,'.',',-')."</b></font></p></td>";
					}
				}else{
						echo "<td align=\"right\"><p></p></td>";
					}
					
				}
					echo "<td align=\"right\"><p><font size=\"1\" ><b>".currency(' ',$GTotalSerapan,'.',',-')."</b></font></font></p></td><td><b>".round(@($GTotalSerapan/$TotalBudget*100),2)."</b></td></tr>";
					

			echo "</tbody></table>";
			echo "<br>";
			echo "<br>";
			
	$JumlahData = count($Kode2Komisi)-1;		
//	print("<pre>".print_r($Kode2Komisi,true)."</pre>");
//	print("<pre>".print_r($BEND,true)."</pre>");
//	print("<pre>".print_r($SEK1,true)."</pre>");

	//Sending to graph
	//	$GraphLabel = substr($GraphLabel, 1);
	
//		$urlPortion= '&data='.urlencode(serialize($ArrayPengeluaranKomisi)).
//					  '&label='.urlencode(serialize($ArrayNamaKomisi));

//		$refresh = microtime() ;
//		echo "<img src=\"graph_array.php?JumlahData=$JumlahDataKomisi&
//		Judul=$Judul&Kode=$GraphLabel&$urlPortion;$refresh \" width=\"450\" ><br>" ;	

	$urlPortion='';
	$a = "datay";
	//$GradasiWarna=rainbow('0000FF','FFFFFF',$JumlahData);
//	$GradasiWarna = array('#ff0000', '#ff3300', '#ff6600', '#ff9900', '#ffcc00', '#ffff00', '#ccff00', '#99ff00', '#66ff00', '#33ff00', '#00ff00', '#00ff33', '#00ff66', '#00ff99', '#00ffcc', '#00ffff', '#00ccff', '#0099ff', '#0066ff', '#0033ff', '#0000ff', '#3300ff', '#6600ff', '#9900ff', '#cc00ff', '#ff00ff', '#ff00cc', '#ff0099', '#ff0066', '#ff0033'); 
	$GradasiWarna = array('#ff0000',            '#ff6600', '#ff9900', '#ffcc00', '#ffff00', '#ccff00', '#99ff00',                                             '#00ff66',            '#00ffcc', 			  '#00ccff', '#0099ff', '#0066ff', '#0033ff', '#0000ff', '#3300ff', '#6600ff', '#9900ff', '#cc00ff', '#ff00ff', '#ff00cc', '#ff0099', '#ff0066', '#ff0033'); 

//	print("<pre>".print_r($GradasiWarna,true)."</pre>");
	for ($i = 0; $i <= ($JumlahData); $i++) {

		$index = $i;
		${$a.$i} = $$Kode2Komisi[$index];
//		echo $a.$i;
		$NamaArray=$a.$i;
		$DataArray=$$Kode2Komisi[$index];
	//	echo $Kode2Komisi[$index];
	//$urlPortion .=	'&'.$NamaArray.'='.urlencode(serialize($DataArray));
	$urlPortion .=	'&'.$NamaArray.'='.$$Kode2Komisi[$index];
	$urlDGAPIPortion .=	'|'.substr($$Kode2Komisi[$index], 6);
	$Warna .=	','.substr($GradasiWarna[$index],1);
	
//	echo $Warna;
//	print("<pre>".print_r($DataArray,true)."</pre>");
	//	print_r ($Kode2Komisi[$index],true);
	//	print("<pre>".print_r(${$a.$i},true)."</pre>");
	
	}

//cho "$a1, $a2, $a3, $a4, $a5";
//Output is value, value, value, value, value

//echo $urlPortion;
		$refresh = microtime() ;
		//echo "<img src=\"graph_arraybar.php?JumlahData=$JumlahData&Judul=$Judul&Kode=$GraphLabel&$urlPortion;$refresh \" width=\"450\" ><br>" ;	
		
//		echo "<a href=\"graph_testarray.php?JumlahData=$JumlahData&Judul=$Judul&Kode=$GraphLabel&$urlPortion;$refresh \" >test</a><br>" ;

 
  
$urlDGAPIPortion=substr($urlDGAPIPortion, 1);
$DaftarKomisi=substr($DaftarKomisi, 1);
$Warna=substr($Warna, 1);
		echo "
<img src=\"//chart.googleapis.com/chart?chxr=0,0,".$GTotalSerapan."&chxs=0,000000,13.5,0.5,lt,000000&chxt=y,x
&chbh=a
&chs=850x350&cht=bvs
&chxl=1:|Jan|Feb|Mar|Apr|Mei|Jun|Jul|Agt|Sep|Okt|Nop|Des|
&chco=".$Warna."
&chds=a
&chd=t:".$urlDGAPIPortion."
&chdl=".$DaftarKomisi."
&chdls=0000CC,8
&chdlp=t
&chg=0,-1&chma=|7,5
&chtt=".$Judul."
&chts=0000CC,14

 alt=\"Faktor Pertambahan\" />";

?>
