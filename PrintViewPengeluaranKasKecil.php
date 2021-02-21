<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPengeluaranKasKecil.php
 *  last change : 2003-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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
$iTGL = FilterInput($_GET["TGL"]);
$minggukemaren = date("Y-m-d", strtotime('last Sunday', strtotime($iTGL)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', strtotime($iTGL)));
	$tanggal=$iTGL;
	$time = strtotime($tanggal);
	$monthago = date("Y-m-d", strtotime("-1 month", $time));
	$nextmonth = date("Y-m-d", strtotime("+1 month", $time));
}else
{
$hariini = strtotime(date("Y-m-d"));
$iTGL = date("Y-m-d", strtotime('last Sunday', $hariini));
$mingguterakhir = date("Y-m-d", strtotime('last Sunday', $hariini));
$minggukemaren = date("Y-m-d", strtotime('-1 week', strtotime($mingguterakhir)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', $hariini));
	$tanggal=$iTGL;
	$time = strtotime($tanggal);
	$monthago = date("Y-m-d", strtotime("-1 month", $time));
	$nextmonth = date("Y-m-d", strtotime("+1 month", $time));
//echo date("Y-m-d", $hariini);
//echo "<br>";
//echo $mingguterakhir;
//echo "<br>";
//echo $minggukemaren;
//echo "<br>";
//echo $minggudepan;

}
 

if (strlen($iTGL>0))
{
$sSQLTanggal =  " WHERE MONTH(a.Tanggal) = ".date(n,strtotime($iTGL))." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;
$sSQLTanggal2 =  " AND MONTH(a.Tanggal) = ".date(n,strtotime($iTGL))." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;
}else
{
$iTGLnow=date(n);
$sSQLTanggal =  " WHERE MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow)) ;
$sSQLTanggal2 =  " AND MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow))  ;
}

$sSQL = "select min(Tanggal) as AwalPeriode ,max(Tanggal) as AkhirPeriode from PengeluaranKasKecil a 
		".$sSQLTanggal." LIMIT 1";
//echo $sSQL;		
$rsPeriode = RunQuery($sSQL);
extract(mysql_fetch_array($rsPeriode));

$Judul = "Informasi Rekapitulasi Laporan Kas Kecil <br>Periode: ".date2Ind($AwalPeriode,1)." s/d ".date2Ind($AkhirPeriode,1); 
require "Include/Header-Report.php";


				
				echo "<table  cellpadding=\"0\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=750>";
				echo "<td align=\"left\">";
				echo "<a href=\"PrintViewPengeluaranKasKecil.php?POSID=$iPOSID&BIDID=$iBIDID&KOMID=$iKOMID&TGL=".$monthago."\"  >";
				echo "<< Bulan sebelumnya </a></td>";
				echo "<td align=\"center\"><font size=\"3\"><b>".date2Ind($iTGL,5)."</b></font></td>";
				echo "<td align=\"right\">"; 
				echo "<a href=\"PrintViewPengeluaranKasKecil.php?POSID=$iPOSID&BIDID=$iBIDID&KOMID=$iKOMID&TGL=".$nextmonth."\"  >";
				echo "Bulan Berikutnya >></a></td>";
				echo "</table>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=900>";

				
// Detail Laporan

if (strlen($iBIDID>0))
{
// SUmary per Bidang =========================================================================================
				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<p><b>LAPORAN REKAPITULASI PENGELUARAN KAS KECIL Per-BIDANG:</b></p>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=750>";
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td rowspan=\"2\"><b>Nomor</b></td>";
				echo "<td rowspan=\"2\"><b>KODE</b></td>";
				echo "<td ><b>Nama Bidang</b></td>";
				echo "<td rowspan=\"2\"><b>Pemasukan (Rupiah)</b></td>";
				echo "<td colspan=\"2\"><b>Pengeluaran (Rupiah)</b></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>Detail Komisi</td>";
				echo "<td>Per Komisi</td>";
				echo "<td>Per Bidang</td>";
				echo "</tr>";

			//Perhitungan Saldo Bulan Lalu
			//Pemasukan Sisa Saldo Bulan Lalu
			
			$tanggal=$iTGL;
			$TahunYYYY=date(Y,strtotime($tanggal)); 
			//echo $TahunYYYY;
			$BulanMM=date('m',strtotime($tanggal));
			//echo $BulanMM;
			//$first_day_this_month = date('m-01-Y');
			$HariPertama=$TahunYYYY."-".$BulanMM."-01";
			$firstday= date($HariPertama);
			//echo $firstday;

			$sSQLTanggalNM =  " WHERE a.Tanggal < '".date($firstday)."'" ;

			//Pemasukan Pencairan Cek Bulan Lalu
			$sSQL10 = "select SUM(a.Jumlah) as SubTotalPencairanCekBL FROM PencairanCek	a ".$sSQLTanggalNM;
			//echo $sSQL10;
				$rsPencairanCek = RunQuery($sSQL10);
				while ($aRow = mysql_fetch_array($rsPencairanCek))
				{
				extract($aRow);
				}
			//Pengeluaran Kas Kecil Bulan Lalu	
			$sSQL11 = "select SUM(a.Jumlah) as SaldoBulanLalu FROM PengeluaranKasKecil a ".$sSQLTanggalNM;
			//echo $sSQL11;
				$rsSaldoBulanLalu = RunQuery($sSQL11);
				while ($aRow = mysql_fetch_array($rsSaldoBulanLalu))
				{
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr>";
				echo "<td><p></p></td>";
				echo "<td><p> BEND </p></td>";
				echo "<td><p> Saldo Bulan lalu </p></td>";
				$SaldoBL=$SubTotalPencairanCekBL-$SaldoBulanLalu;
				echo "<td align=\"right\"><p>".currency(' ',$SaldoBL ,'.',',00')."</p></td>";
				echo "<td><p></p></td>";
				echo "<td><p></p></td>";
				echo "</tr>";
				}
			
				//Pemasukan Pencairan Cek Bulan berjalan
				
				$sSQL12 = "select SUM(a.Jumlah) as SubTotalPencairanCekBI FROM PencairanCek	a
				".$sSQLTanggal;
			
				//echo $sSQL10;
				
				$rsPencairanCek = RunQuery($sSQL12);
				$iii = 0;
				//$PosSubTotalJumlah=0;
				while ($aRow = mysql_fetch_array($rsPencairanCek))
				{
				$iii++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr>";
				echo "<td><p></p></td>";
				echo "<td><p> BEND </p></td>";
				echo "<td><p> Pencairan Cek </p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$SubTotalPencairanCekBI,'.',',00')."</p></td>";
				echo "<td><p></p></td>";
				echo "<td><p></p></td>";
				echo "</tr>";
				
				}			
				
				

			
			$sSQL = "select a.*, SUM(a.Jumlah) as SubTotal , b.*, c.*,c.BidangID as Bidang, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				".$sSQLTanggal." 
				GROUP BY c.BidangID ORDER BY c.BidangID";
				
			//$sSQL = "SELECT a.*,b.*, SUM(a.JmlAmplop) as JmlAmplop, COUNT(a.Bulanan) as AmplopBulanan, SUM(a.Bulanan) as Bulanan FROM PengeluaranKasKecil a
			//LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis
			//WHERE Tanggal = '".$iTGL."' GROUP BY a.Pos ";
		//	echo $sSQL;  
			$rsPengeluaran = RunQuery($sSQL);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalJumlah=0;
			while ($aRow = mysql_fetch_array($rsPengeluaran))
			{
						
				$i++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//Jika Ada Pengembalian Anggaran
			//		$sSQL11a = "select a.*, SUM(a.Jumlah) as TotalKembaliBidang, b.*, c.*, d.* FROM PengembalianKasKecil	a
			//		LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
			//		LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
			//		LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
			//		WHERE c.BidangID=".$Bidang." ".$sSQLTanggal2."
			//		GROUP BY c.BidangID ";
			//		$rsPengembalianBidang = RunQuery($sSQL11a);
			//		while ($aRow = mysql_fetch_array($rsPengembalianBidang))
			//		{
			//		extract($aRow);
			//		$SubTotal=$SubTotal-$TotalKembaliBidang;
			//		}
			//		
			//	$PosSubTotalJumlah+=$SubTotal;
			//	echo "<tr bgcolor=\"cccccc\">";
			//	echo "<td><p>".$i."</p></td>";
			//	echo "<td><p>".$KodeBidang."</p></td>";
			//	echo "<td><p>".$NamaBidang."</p></td>";
			//	echo "<td><p></p></td>";
			//	echo "<td><p></p></td>";
			//	echo "<td align=\"right\"><p>".currency(' ',$SubTotal,'.',',00')."</p></td>";
			//	
			//	echo "</tr>";
				
				$sSQL1 = "select a.*, SUM(a.Jumlah) as SubTotalKomisi , b.*, c.*, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				WHERE c.BidangID=".$Bidang." ".$sSQLTanggal2."
				GROUP BY b.KomisiID ORDER BY b.KomisiID";
				
			//	echo $sSQL1;
				$rsPengeluaranKomisi = RunQuery($sSQL1);
				$ii = 0;
				//Loop through the surat recordset
				//$PosSubTotalJumlah=0;
				while ($aRow = mysql_fetch_array($rsPengeluaranKomisi))
				{
				$ii++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr>";
				echo "<td><p></p></td>";
				echo "<td><p>".$KodeKomisi."</p></td>";
				echo "<td><p>".$NamaKomisi."</p></td>";
				echo "<td align=\"right\"><p>";
					//Jika ada Pengembalian Kas
				
			//		$sSQL1a = "select a.*, SUM(a.Jumlah) as TotalKembaliKomisi, b.*, c.*, d.* FROM PengembalianKasKecil	a
			//		LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
			//		LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
			//		LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
			//		WHERE c.BidangID=".$Bidang." ".$sSQLTanggal2."
			//		GROUP BY c.KomisiID ORDER BY c.KomisiID";
			//		$rsPengembalianKomisi = RunQuery($sSQL1a);
			//		while ($aRow = mysql_fetch_array($rsPengembalianKomisi))
			//		{
			//		extract($aRow);
			//			if 	($TotalKembaliKomisi>0){
			//			echo currency(' ',$TotalKembaliKomisi,'.',',00');
			//			}
			//		}
				echo "</p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$SubTotalKomisi,'.',',00')."</p></td>";
				echo "<td><p></p></td>";
				echo "</tr>";
				
				}
				
			}
			echo "<tr>";
			echo "<td><p></p></td>";
			echo "<td align=\"center\" colspan=\"2\"><b><p>Total </p></b></td>";
			
			$TotalPemasukan=$SaldoBL+$SubTotalPencairanCekBI+$TotalKembaliKomisi;
			$TotalPengeluaran=$PosSubTotalJumlah;
			echo "<td align=\"right\"><b><p>".currency(' ',$TotalPemasukan,'.',',00')."</b></p></td>";
			echo "<td align=\"right\"><b><p></b></p></td>";
			echo "<td align=\"right\"><b><p>".currency(' ',$PosSubTotalJumlah,'.',',00')."</b></p></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td><p></p></td>";
			echo "<td align=\"center\" colspan=\"4\"><b><p>Saldo Akhir ( Pemasukan - Pengeluaran ) </p></b></td>";
			echo "<td align=\"right\"><b><p>".currency(' ',$TotalPemasukan-$TotalPengeluaran,'.',',00')."</b></p></td>";
			echo "</tr>";
			echo "</tbody></table>";
			echo "<br>";
			echo "<br>";

}else if (strlen($iPOSID>0))
{ 
// SUmary Pos Anggaran=========================================================================================
				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<p><b>LAPORAN REKAPITULASI PENGELUARAN KAS KECIL Per-Pos Anggaran:</b></p>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=750>";
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td rowspan=\"2\"><b>Nomor</b></td>";
				echo "<td rowspan=\"2\"><b>KODE</b></td>";
				echo "<td ><b>Nama Bidang</b></td>";
				echo "<td rowspan=\"2\"><b>Pemasukan (Rupiah)</b></td>";
				echo "<td colspan=\"2\"><b>Pengeluaran (Rupiah)</b></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>Detail Pos Anggaran</td>";
				echo "<td>Per PosAnggaran</td>";
				echo "<td>Per Bidang</td>";
				echo "</tr>";
				
							//Perhitungan Saldo Bulan Lalu
			//Pemasukan Sisa Saldo Bulan Lalu
			
			$tanggal=$iTGL;
			$TahunYYYY=date(Y,strtotime($tanggal)); 
			//echo $TahunYYYY;
			$BulanMM=date('m',strtotime($tanggal));
			//echo $BulanMM;
			//$first_day_this_month = date('m-01-Y');
			$HariPertama=$TahunYYYY."-".$BulanMM."-01";
			$firstday= date($HariPertama);
			//echo $firstday;

			$sSQLTanggalNM =  " WHERE a.Tanggal < '".date($firstday)."'" ;

			//Pemasukan Pencairan Cek Bulan Lalu
			$sSQL10 = "select SUM(a.Jumlah) as SubTotalPencairanCekBL FROM PencairanCek	a ".$sSQLTanggalNM;
			//echo $sSQL10;
				$rsPencairanCek = RunQuery($sSQL10);
				while ($aRow = mysql_fetch_array($rsPencairanCek))
				{
				extract($aRow);
				}
			//Pengeluaran Kas Kecil Bulan Lalu	
			$sSQL11 = "select SUM(a.Jumlah) as SaldoBulanLalu FROM PengeluaranKasKecil a ".$sSQLTanggalNM;
			//echo $sSQL11;
				$rsSaldoBulanLalu = RunQuery($sSQL11);
				while ($aRow = mysql_fetch_array($rsSaldoBulanLalu))
				{
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr>";
				echo "<td><p></p></td>";
				echo "<td><p> BEND </p></td>";
				echo "<td><p> Saldo Bulan lalu </p></td>";
				$SaldoBL=$SubTotalPencairanCekBL-$SaldoBulanLalu;
				echo "<td align=\"right\"><p>".currency(' ',$SaldoBL ,'.',',00')."</p></td>";
				echo "<td><p></p></td>";
				echo "<td><p></p></td>";
				echo "</tr>";
				}
			
				//Pemasukan Pencairan Cek Bulan berjalan
				
				$sSQL12 = "select SUM(a.Jumlah) as SubTotalPencairanCekBI FROM PencairanCek	a
				".$sSQLTanggal;
			
				//echo $sSQL10;
				
				$rsPencairanCek = RunQuery($sSQL12);
				$iii = 0;
				//$PosSubTotalJumlah=0;
				while ($aRow = mysql_fetch_array($rsPencairanCek))
				{
				$iii++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr>";
				echo "<td><p></p></td>";
				echo "<td><p> BEND </p></td>";
				echo "<td><p> Pencairan Cek </p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$SubTotalPencairanCekBI,'.',',00')."</p></td>";
				echo "<td><p></p></td>";
				echo "<td><p></p></td>";
				echo "</tr>";
				
				}	

			
			$sSQL = "select a.*, SUM(a.Jumlah) as SubTotal , b.*, c.*,c.BidangID as Bidang, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				".$sSQLTanggal." 
				GROUP BY c.BidangID ORDER BY c.BidangID";
				
			//$sSQL = "SELECT a.*,b.*, SUM(a.JmlAmplop) as JmlAmplop, COUNT(a.Bulanan) as AmplopBulanan, SUM(a.Bulanan) as Bulanan FROM PengeluaranKasKecil a
			//LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis
			//WHERE Tanggal = '".$iTGL."' GROUP BY a.Pos ";
			//echo $sSQL;
			$rsPengeluaran = RunQuery($sSQL);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalJumlah=0;
			$KembalianSubTotalJumlah=0;
			while ($aRow = mysql_fetch_array($rsPengeluaran))
			{
						
				$i++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				
					//Jika Ada Pengembalian Anggaran
			//		$sSQL11a = "select a.*, SUM(a.Jumlah) as TotalKembaliBidang, b.*, c.*, d.* FROM PengembalianKasKecil	a
			//		LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
			//		LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
			//		LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
			//		WHERE c.BidangID=".$Bidang." ".$sSQLTanggal2."
			//		GROUP BY c.BidangID ";
			//		$rsPengembalianBidang = RunQuery($sSQL11a);
			//		while ($aRow = mysql_fetch_array($rsPengembalianBidang))
			//		{
			//		extract($aRow);
			//		$SubTotal=$SubTotal-$TotalKembaliBidang;
			//		$KembalianSubTotalJumlah+=$TotalKembaliBidang;
			//		}
					
				$PosSubTotalJumlah+=$SubTotal;
			
				echo "<tr bgcolor=\"cccccc\">";
				echo "<td><p>".$i."</p></td>";
				echo "<td><p>".$KodeBidang."</p></td>";
				echo "<td ><p>".$NamaBidang."</p></td>";
				echo "<td><p></p></td>";
				echo "<td><p></p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$SubTotal,'.',',00')."</p></td>";
				
				echo "</tr>";
				
				$sSQL1 = "select a.*, SUM(a.Jumlah) as TotalPosAnggaran, b.*, c.*, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				WHERE c.BidangID=".$Bidang." ".$sSQLTanggal2."
				GROUP BY a.PosAnggaranID ORDER BY a.PosAnggaranID";
				
				
				$rsPengeluaranKomisi = RunQuery($sSQL1);
				$ii = 0;
				//Loop through the surat recordset
				//$PosSubTotalJumlah=0;
				while ($aRow = mysql_fetch_array($rsPengeluaranKomisi))
				{
				$ii++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr>";
				echo "<td><p></p></td>";
				echo "<td><p>".$PosAnggaranID."</p></td>";
				echo "<td><p>".$NamaPosAnggaran."</p></td>";
				echo "<td><p></p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$TotalPosAnggaran,'.',',00')."</p></td>";
				echo "<td><p></p></td>";
				echo "</tr>";
				
				}
				// Jika Ada Pengembalian Anggaran
			//		$sSQL1a = "select a.*, SUM(a.Jumlah) as TotalKembaliKomisi, b.*, c.*, d.* FROM PengembalianKasKecil	a
			//		LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
			//		LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
			//		LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
			//		WHERE c.BidangID=".$Bidang." ".$sSQLTanggal2."
			//		GROUP BY c.KomisiID ORDER BY c.KomisiID";
			//		$rsPengembalianKomisi = RunQuery($sSQL1a);
			//		while ($aRow = mysql_fetch_array($rsPengembalianKomisi))
			//		{
			//		extract($aRow);
			//		echo "<tr>";
			//		echo "<td><p></p></td>";
			//		echo "<td><p></p></td>";
			//		echo "<td><p>".$NamaKomisi."</p></td>";
			//		echo "<td align=\"right\"><p>".currency(' ',$TotalKembaliKomisi,'.',',00')."</p></td>";
			//		echo "<td><p></p></td>";
			//		echo "<td><p></p></td>";
			//		echo "</tr>";
			//		}
					
					
				
			}
			//echo "<tr>";
			//echo "<td align=\"center\" colspan=\"3\"><b><p>Total  </p></b></td>";
			//echo "<td align=\"right\"><b><p>".currency(' ',$PosSubTotalJumlah,'.',',00')."</b></p></td>";
			//echo "</tr>";
			//echo "</tbody></table>";
			//echo "<br>";
			//echo "<br>";
			
			
			echo "<tr>";
			echo "<td><p></p></td>";
			echo "<td align=\"center\" colspan=\"2\"><b><p>Total </p></b></td>";
			
			$TotalPemasukan=$SaldoBL+$SubTotalPencairanCekBI+$KembalianSubTotalJumlah;
			$TotalPengeluaran=$PosSubTotalJumlah;
			echo "<td align=\"right\"><b><p>".currency(' ',$TotalPemasukan,'.',',00')."</b></p></td>";
			echo "<td align=\"right\"><b><p></b></p></td>";
			echo "<td align=\"right\"><b><p>".currency(' ',$PosSubTotalJumlah,'.',',00')."</b></p></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td><p></p></td>";
			echo "<td align=\"center\" colspan=\"4\"><b><p>Saldo Akhir ( Pemasukan - Pengeluaran ) </p></b></td>";
			echo "<td align=\"right\"><b><p>".currency(' ',$TotalPemasukan-$TotalPengeluaran,'.',',00')."</b></p></td>";
			echo "</tr>";
			echo "</tbody></table>";
			echo "<br>";
			echo "<br>";
			

}else if (strlen($iKOMID>0))
{
// SUmary Per Komisi=========================================================================================
				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<p><b>LAPORAN REKAPITULASI PENGELUARAN KAS KECIL Per-Komisi:</b></p>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=700>";
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td rowspan=\"2\"><b>Nomor</b></td>";
				echo "<td rowspan=\"2\"><b>KODE</b></td>";
				echo "<td ><b>Nama Bidang</b></td>";
				echo "<td rowspan=\"2\"><b>Pemasukan (Rupiah)</b></td>";
				echo "<td colspan=\"2\"><b>Pengeluaran (Rupiah)</b></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>Nama Komisi</td>";
				echo "<td>Per Komisi</td>";
				echo "<td>SubTotal Komisi</td>";
				echo "</tr>";

										//Perhitungan Saldo Bulan Lalu
			//Pemasukan Sisa Saldo Bulan Lalu
			
			$tanggal=$iTGL;
			$TahunYYYY=date(Y,strtotime($tanggal)); 
			//echo $TahunYYYY;
			$BulanMM=date('m',strtotime($tanggal));
			//echo $BulanMM;
			//$first_day_this_month = date('m-01-Y');
			$HariPertama=$TahunYYYY."-".$BulanMM."-01";
			$firstday= date($HariPertama);
			//echo $firstday;

			$sSQLTanggalNM =  " WHERE a.Tanggal < '".date($firstday)."'" ;

			//Pemasukan Pencairan Cek Bulan Lalu
			$sSQL10 = "select SUM(a.Jumlah) as SubTotalPencairanCekBL FROM PencairanCek	a ".$sSQLTanggalNM;
			//echo $sSQL10;
				$rsPencairanCek = RunQuery($sSQL10);
				while ($aRow = mysql_fetch_array($rsPencairanCek))
				{
				extract($aRow);
				}
			//Pengeluaran Kas Kecil Bulan Lalu	
			$sSQL11 = "select SUM(a.Jumlah) as SaldoBulanLalu FROM PengeluaranKasKecil a ".$sSQLTanggalNM;
			//echo $sSQL11;
				$rsSaldoBulanLalu = RunQuery($sSQL11);
				while ($aRow = mysql_fetch_array($rsSaldoBulanLalu))
				{
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr>";
				echo "<td><p></p></td>";
				echo "<td><p> BEND </p></td>";
				echo "<td><p> Saldo Bulan lalu </p></td>";
				$SaldoBL=$SubTotalPencairanCekBL-$SaldoBulanLalu;
				echo "<td align=\"right\"><p>".currency(' ',$SaldoBL ,'.',',00')."</p></td>";
				echo "<td><p></p></td>";
				echo "<td><p></p></td>";
				echo "</tr>";
				}
			
				//Pemasukan Pencairan Cek Bulan berjalan
				
				$sSQL12 = "select SUM(a.Jumlah) as SubTotalPencairanCekBI FROM PencairanCek	a
				".$sSQLTanggal;
			
				//echo $sSQL10;
				
				$rsPencairanCek = RunQuery($sSQL12);
				$iii = 0;
				//$PosSubTotalJumlah=0;
				while ($aRow = mysql_fetch_array($rsPencairanCek))
				{
				$iii++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr>";
				echo "<td><p></p></td>";
				echo "<td><p> BEND </p></td>";
				echo "<td><p> Pencairan Cek </p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$SubTotalPencairanCekBI,'.',',00')."</p></td>";
				echo "<td><p></p></td>";
				echo "<td><p></p></td>";
				echo "</tr>";
				
				}				
			
			
			$sSQL = "select a.*, SUM(a.Jumlah) as SubTotal , b.*, c.*,c.BidangID as Bidang, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				".$sSQLTanggal." 
				GROUP BY c.BidangID ORDER BY c.BidangID";
				
			//$sSQL = "SELECT a.*,b.*, SUM(a.JmlAmplop) as JmlAmplop, COUNT(a.Bulanan) as AmplopBulanan, SUM(a.Bulanan) as Bulanan FROM PengeluaranKasKecil a
			//LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis
			//WHERE Tanggal = '".$iTGL."' GROUP BY a.Pos ";
			//echo $sSQL;
			$rsPengeluaran = RunQuery($sSQL);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalJumlah=0;
			while ($aRow = mysql_fetch_array($rsPengeluaran))
			{
						
				$i++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				
				//Jika Ada Pengembalian Anggaran
			//		$sSQL11a = "select a.*, SUM(a.Jumlah) as TotalKembaliBidang, b.*, c.*, d.* FROM PengembalianKasKecil	a
			//		LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
			//		LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
			//		LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
			//		WHERE c.BidangID=".$Bidang." ".$sSQLTanggal2."
			//		GROUP BY c.BidangID ";
			//		$rsPengembalianBidang = RunQuery($sSQL11a);
			//		while ($aRow = mysql_fetch_array($rsPengembalianBidang))
			//		{
			//		extract($aRow);
			//		$SubTotal=$SubTotal-$TotalKembaliBidang;
			//		}
				
			//	$PosSubTotalJumlah+=$SubTotal;
			//	echo "<tr bgcolor=\"cccccc\">";
			//	echo "<td><p>".$i."</p></td>";
			//	echo "<td><p>".$KodeBidang."</p></td>";
			//	echo "<td ><p>".$NamaBidang."</p></td>";
			//	echo "<td><p></p></td>";
			//	echo "<td><p></p></td>";
			//	//Jika Ada Pengembalian di Komisi

			//	echo "<td align=\"right\"><p>".currency(' ',$SubTotal,'.',',00')."</p></td>";
			//	
			// echo "</tr>";
				
				$sSQL1 = "select a.*, SUM(a.Jumlah) as TotalKomisi, b.*, c.*, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				WHERE c.BidangID=".$Bidang." ".$sSQLTanggal2."
				GROUP BY c.KomisiID ORDER BY c.KomisiID";
	
		//		echo $sSQL1;
				
				$rsPengeluaranKomisi = RunQuery($sSQL1);
				$ii = 0;
				//Loop through the surat recordset
				//$PosSubTotalJumlah=0;
				while ($aRow = mysql_fetch_array($rsPengeluaranKomisi))
				{
				$ii++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr bgcolor=\"ccffcc\">";
				echo "<td><p></p></td>";
				echo "<td><p>".$KomisiID."</p></td>";
				echo "<td><p>".$NamaKomisi."</p></td>";
				echo "<td align=\"right\"><p>";
					//Jika ada Pengembalian Kas
				
			//		$sSQL1a = "select a.*, SUM(a.Jumlah) as TotalKembaliKomisi, b.*, c.*, d.* FROM PengembalianKasKecil	a
				//	LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
			//		LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
			//		LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
			//		WHERE c.BidangID=".$Bidang." ".$sSQLTanggal2."
			//		GROUP BY c.KomisiID ORDER BY c.KomisiID";
			//		$rsPengembalianKomisi = RunQuery($sSQL1a);
			//		while ($aRow = mysql_fetch_array($rsPengembalianKomisi))
			//		{
			//		extract($aRow);
			//			if 	($TotalKembaliKomisi>0){
			//			echo currency(' ',$TotalKembaliKomisi,'.',',00');
			//			}
			//		}
				echo "</p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$TotalKomisi,'.',',00')."</p></td>";
				echo "<td><p></p></td>";
				echo "</tr>";
				
				}
				
			}
			echo "<tr>";
			echo "<td><p></p></td>";
			echo "<td align=\"center\" colspan=\"2\"><b><p>Total </p></b></td>";
			
			$TotalPemasukan=$SaldoBL+$SubTotalPencairanCekBI+$TotalKembaliKomisi;
			$TotalPengeluaran=$PosSubTotalJumlah;
			echo "<td align=\"right\"><b><p>".currency(' ',$TotalPemasukan,'.',',00')."</b></p></td>";
			echo "<td align=\"right\"><b><p></b></p></td>";
			echo "<td align=\"right\"><b><p>".currency(' ',$PosSubTotalJumlah,'.',',00')."</b></p></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td><p></p></td>";
			echo "<td align=\"center\" colspan=\"4\"><b><p>Saldo Akhir ( Pemasukan - Pengeluaran ) </p></b></td>";
			echo "<td align=\"right\"><b><p>".currency(' ',$TotalPemasukan-$TotalPengeluaran,'.',',00')."</b></p></td>";
			echo "</tr>";
			echo "</tbody></table>";
			echo "<br>";
			echo "<br>";

}
else 
{
// Detail =========================================================================================
				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<p><b>LAPORAN REKAPITULASI PENGELUARAN KAS KECIL DETAIL Per-Komisi:</b></p>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=750>";
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td rowspan=\"2\"><b>Nomor</b></td>";
				echo "<td rowspan=\"2\"><b>KODE</b></td>";
				echo "<td colspan=\"3\"><b>Bidang/Komisi/Detail</b></td>";
				echo "<td colspan=\"2\"><b>Pemasukan (Rupiah)</b></td>";
				echo "<td colspan=\"3\"><b>Pengeluaran (Rupiah)</b></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>Tanggal</td>";
				echo "<td>Voucher</td>";
				echo "<td>Deskripsi</td>";
				echo "<td>Nilai</td>";
				echo "<td>SubTotal Pemasukan</td>";
				echo "<td>Nilai</td>";
				echo "<td>SubTotal Komisi</td>";
				echo "<td>SubTotal Bidang</td>";
				echo "</tr>";
				
				
				
			//Perhitungan Saldo Bulan Lalu
			//Pemasukan Sisa Saldo Bulan Lalu
			
			$tanggal=$iTGL;
			$TahunYYYY=date(Y,strtotime($tanggal)); 
			//echo $TahunYYYY;
			$BulanMM=date('m',strtotime($tanggal));
			//echo $BulanMM;
			//$first_day_this_month = date('m-01-Y');
			$HariPertama=$TahunYYYY."-".$BulanMM."-01";
			$firstday= date($HariPertama);
			//echo $firstday;

			$sSQLTanggalNM =  " WHERE a.Tanggal < '".date($firstday)."'" ;

			//Pemasukan Pencairan Cek Bulan Lalu
			$sSQL10 = "select SUM(a.Jumlah) as SubTotalPencairanCekBL FROM PencairanCek	a ".$sSQLTanggalNM;
			//echo $sSQL10;
				$rsPencairanCek = RunQuery($sSQL10);
				while ($aRow = mysql_fetch_array($rsPencairanCek))
				{
				extract($aRow);
				}
			//Pengeluaran Kas Kecil Bulan Lalu	
			$sSQL11 = "select SUM(a.Jumlah) as SaldoBulanLalu FROM PengeluaranKasKecil a ".$sSQLTanggalNM;
			//echo $sSQL11;
				$rsSaldoBulanLalu = RunQuery($sSQL11);
				while ($aRow = mysql_fetch_array($rsSaldoBulanLalu))
				{
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr>";
				echo "<td><p></p></td>";
				echo "<td><p> BEND </p></td>";
				echo "<td><p> - </p></td>";
				echo "<td><p> - </p></td>";
				echo "<td><p> Saldo Bulan lalu </p></td>";
				echo "<td><p> - </p></td>";
				$SaldoBL=$SubTotalPencairanCekBL-$SaldoBulanLalu;
				echo "<td align=\"right\"><p>".currency(' ',$SaldoBL ,'.',',00')."</p></td>";
				echo "<td><p></p></td>";
				echo "<td><p></p></td>";
				echo "</tr>";
				}
			
				//Pemasukan Pencairan Cek Bulan berjalan
				//Sub Total
				$sSQL11 = "select SUM(a.Jumlah) as SubTotalPencairanCekBI FROM PencairanCek	a
				".$sSQLTanggal;
			
				//echo $sSQL10;
				
				$rsPencairanCek = RunQuery($sSQL11);
				$iii = 0;
				//$PosSubTotalJumlah=0;
				while ($aRow = mysql_fetch_array($rsPencairanCek))
				{
				$iii++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr>";
				echo "<td><p></p></td>";
				echo "<td><p> BEND </p></td>";
				echo "<td colspan=\"3\" ><p> PENCAIRAN CEK</p></td>";
				echo "<td><p></p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$SubTotalPencairanCekBI,'.',',00')."</p></td>";
				echo "<td><p></p></td>";echo "<td><p></p></td>";
				echo "<td><p></p></td>";echo "<td><p></p></td>";
				echo "</tr>";
				
				}
				//detail
				$sSQL12 = "select a.* as SubTotalPencairanCekBI FROM PencairanCek	a
				".$sSQLTanggal;
			
				//echo $sSQL10;
				
				$rsPencairanCek = RunQuery($sSQL12);
				$iii = 0;
				//$PosSubTotalJumlah=0;
				while ($aRow = mysql_fetch_array($rsPencairanCek))
				{
				$iii++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr>";
				echo "<td><p></p></td>";
				echo "<td><p> ".$iii." </p></td>";
				echo "<td><p>".$Tanggal."</p></td>";
				echo "<td><p>".$NomorCek."</p></td>";
				echo "<td><p>".$Keterangan."</p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$Jumlah,'.',',00')."</p></td>";
				echo "<td><p></p></td>";echo "<td><p></p></td>";
				echo "<td><p></p></td>";echo "<td><p></p></td>";
				echo "</tr>";
				
				}				
			
			$sSQL = "select a.*, SUM(a.Jumlah) as SubTotal , b.*, c.*,c.BidangID as Bidang, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				".$sSQLTanggal." 
				GROUP BY c.BidangID ORDER BY c.BidangID";
				

			$rsPengeluaran = RunQuery($sSQL);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalJumlah=0;
			while ($aRow = mysql_fetch_array($rsPengeluaran))
			{
						
				$i++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				$PosSubTotalJumlah+=$SubTotal;
				echo "<tr bgcolor=\"cccccc\">";
				echo "<td><p>".$i."</p></td>";
				echo "<td><p>".$KodeBidang."</p></td>";
				echo "<td colspan=\"3\"><p>".$NamaBidang."</p></td>";
				echo "<td><p></p></td>";echo "<td><p></p></td>";echo "<td><p></p></td>";echo "<td><p></p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$SubTotal,'.',',00')."</p></td>";
				
				echo "</tr>";
				
				$sSQL1 = "select a.*, SUM(a.Jumlah) as TotalKomisi, b.*, c.*, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				WHERE c.BidangID=".$Bidang." ".$sSQLTanggal2."
				GROUP BY c.KomisiID ORDER BY c.KomisiID";
				$rsPengeluaranKomisi = RunQuery($sSQL1);
				
	//			echo $sSQL1;
				
				
				$ii = 0;
				//Loop through the surat recordset
				//$PosSubTotalJumlah=0;
				while ($aRow = mysql_fetch_array($rsPengeluaranKomisi))
				{
				$ii++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr bgcolor=\"ccffcc\">";
				echo "<td><p></p></td>";
				echo "<td><p>".$KomisiID."</p></td>";
				echo "<td colspan=\"3\"><p>".$NamaKomisi."</p></td>";
				echo "<td><p></p></td>";echo "<td><p></p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$TotalKembaliKomisi,'.',',00')."</p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$TotalKomisi,'.',',00')."</p></td>";
				echo "<td><p></p></td>";
				echo "</tr>";
				
				$sSQL2 = "select a.*, b.*, c.*, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				WHERE c.KomisiID=".$KomisiID." ".$sSQLTanggal2."
				ORDER BY c.KomisiID ,a.Tanggal ASC";
				
				
				$rsPengeluaranKas = RunQuery($sSQL2);
				$iii = 0;
				//Loop through the surat recordset
				//$PosSubTotalJumlah=0;
				while ($aRow = mysql_fetch_array($rsPengeluaranKas))
				{
				$iii++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				//$PosSubTotalJumlah+=$SubTotal;
				echo "<tr>";
				echo "<td><p></p></td>";
				echo "<td><p></p></td>";
				echo "<td><p>".$Tanggal."</p></td>";
				echo "<td><p>".$PengeluaranKasKecilID."</p></td>";
				echo "<td align=\"left\"><p>".$DeskripsiKas."</p></td>";
				echo "<td><p></p></td>";echo "<td><p></p></td>";
				echo "<td align=\"right\"><p>".currency(' ',$Jumlah,'.',',00')."</p></td>";
				echo "<td><p></p></td>";
				echo "</tr>";
				
				}
				
				}
				
			}
			echo "<tr>";
			echo "<td><p></p></td>";
			echo "<td align=\"center\" colspan=\"4\"><b><p>Total </p></b></td>";
			
			$TotalPemasukan=$SaldoBL+$SubTotalPencairanCekBI;
			$TotalPengeluaran=$PosSubTotalJumlah;
			echo "<td><p></p></td>";
			echo "<td align=\"right\"><b><p>".currency(' ',$TotalPemasukan,'.',',00')."</b></p></td>";
			echo "<td align=\"right\"><b><p></b></p></td>";echo "<td><p></p></td>";
			echo "<td align=\"right\"><b><p>".currency(' ',$PosSubTotalJumlah,'.',',00')."</b></p></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td><p></p></td>";
			echo "<td align=\"center\" colspan=\"5\"><b><p>Saldo Akhir ( Pemasukan - Pengeluaran ) </p></b></td>";
			echo "<td align=\"center\"  colspan=\"4\"><b><p>".currency(' ',$TotalPemasukan-$TotalPengeluaran,'.',',00')."</b></p></td>";
			echo "</tr>";
			echo "</tbody></table>";
			echo "<br>";
			echo "<br>";

}				

				
?>
.				

	

<?php
require "Include/Footer-Short.php";
?>
