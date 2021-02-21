<?php
/*******************************************************************************
 *
 *  filename    : PrintViewKegiatanKlasis.php
 *  last change : 2003-01-29
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2003 Phillip Hullquist, Deane Barker, Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2010 Erwin Pratama for GKJ Bekasi Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";
// Get the Gol Darah ID from the querystring
$iStatus = FilterInput($_GET["Status"]);
$iBidangID = FilterInput($_GET["BidangID"]);
$iAktaSidangID = FilterInput($_GET["AktaSidangID"]);
$iHub = FilterInput($_GET["Hub"]);
$iLap = FilterInput($_GET["Lap"]);
$iUrut = FilterInput($_GET["Urut"]);
$iTGL = FilterInput($_GET["TGL"]);
$iKat = FilterInput($_GET["Kat"]); 


$ANDKelas = 'AND a2.lst_OptionName like ';
$GetKelas = FilterInput($_GET["Klas"]);

$today = time();
$twoMonthsAgo = strtotime("-45 days", $today);
$TwoMonth=date('Y-m-d', $twoMonthsAgo);
if ( $iLap == 'MPL' ) {
	$Laporan = "  WHERE ket1 > '$TwoMonth'  ";
} else if ( $iLap == 'Bulanan' ) {
	$iTGL = date("Y-m-d");
	$Laporan = " WHERE ket1 > 0";
} else {
	$Laporan = " WHERE ket1 > 0";
}

// Urutan
if ( $iUrut == 'TglSurat' ) {
	$Urutan = "  ORDER BY Tanggal DESC ";
} else if ( $iUrut == 'TglTerima' ) {
	$Urutan = "  ORDER BY ket1 DESC";
} else  if ( $iUrut == 'Kategori' ) {
	$Urutan = "  ORDER BY ket3 ASC, ket1 DESC";
} else  if ( $iUrut == 'Pengirim' ) {
	$Urutan = "  ORDER BY Dari ASC, ket1 DESC";
} else  if ( $iUrut == 'Institusi' ) {
	$Urutan = "  ORDER BY Institusi ASC, ket1 DESC ";
} else  if ( $iUrut == 'Kepada' ) {
	$Urutan = "  ORDER BY Kepada, ket1 DESC ";
} else   if ( $iUrut == 'Urgensi' ) {
	$Urutan = "  ORDER BY Urgensi, ket1 DESC ";
} else {
	$Urutan = " ORDER BY ket1 DESC";
}

if ( $iHub == 'MPL' ) {
	$Hubungan = "";
} else {
	$Hubungan = "";
}


if ( $GetKelas == '' ) {
	$Kelas = " ";
} else {
	$Kelas = "$ANDKelas '$GetKelas'  ";
}

if (strlen($iKat>0))
{
$Kategori=" AND Ket3=$iKat";
}

if (strlen($iTGL>0))
{
$iTGLY = date(Y,strtotime($iTGL));
$iTGLM = date(m,strtotime($iTGL));

$minggukemaren = date("Y-m-d", strtotime('last Sunday', strtotime($iTGL)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', strtotime($iTGL)));

	$tanggal=$iTGL;
	$time = strtotime($tanggal);
	$monthago = date("Y-m-d", strtotime("-1 month", $time));
	$nextmonth = date("Y-m-d", strtotime("+1 month", $time));
	$iTGLawal = date(date2Ind($iTGL,11)."-1");
	$iTGLawalanggaran = date("Y-1-1");
//base on tanggal surat
//$sSQLTanggal =  " AND MONTH(Tanggal) = ".$iTGLM." AND YEAR(Tanggal) = ".$iTGLY ;
//base on tanggal terima
$sSQLTanggal =  " AND MONTH(ket1) = ".$iTGLM." AND YEAR(ket1) = ".$iTGLY ; 
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".date(n,strtotime('-1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('-1 month', $time))  ;
$sSQLTanggalBulanBesok =  "  MONTH(Tanggal) = ".date(n,strtotime('+1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('+1 month', $time))  ;
$sSQLTanggalBulanIni =  "  Tanggal >= '".date($iTGLawalanggaran)."' AND Tanggal < '".date($iTGLawal)."'  ";
//echo $sSQLTanggalBulanIni;
}else
{
$hariini = strtotime(date("Y-m-d"));
//$iTGL = date("Y-m-d");
$iTGLY = date(Y,strtotime($iTGL));
$iTGLM = date(m,strtotime($iTGL));

$mingguterakhir = date("Y-m-d", strtotime('last Sunday', $hariini));
$minggukemaren = date("Y-m-d", strtotime('-1 week', strtotime($mingguterakhir)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', $hariini));

	$tanggal=$iTGL;
	$time = strtotime($tanggal);
	$monthago = date("Y-m-d", strtotime("-1 month", $time));
	$nextmonth = date("Y-m-d", strtotime("+1 month", $time));
		$iTGLawal = date(date2Ind($iTGL,11)."-1");
	$iTGLawalanggaran = date("Y-1-1");

//$sSQLTanggal =  " AND MONTH(Tanggal) = ".$iTGLM." AND YEAR(Tanggal) = ".$iTGLY ;
$sSQLTanggal =  " " ;
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".date(n,strtotime('-1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('-1 month', $time))  ;
$sSQLTanggalBulanBesok =  "  MONTH(Tanggal) = ".date(n,strtotime('+1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('+1 month', $time))  ;
$sSQLTanggalBulanIni =  "  Tanggal >= '".date($iTGLawalanggaran)."' AND Tanggal < '".date($iTGLawal)."'  ";
//echo $sSQLTanggalBulanIni;
}
if ( $iAktaSidangID < 1 ) {

				$sSQL0 = "SELECT MAX(a.AktaSidangID), d.NomorSurat as NamaAkta FROM KegiatanKlasis a
						LEFT JOIN AktaSidang d ON a.AktaSidangID=d.AktaSidangID
						LIMIT 1
						";

} else {

				 $sSQL0 = "SELECT d.AktaSidangID, d.NomorSurat as NamaAkta FROM KegiatanKlasis a
						LEFT JOIN AktaSidang d ON a.AktaSidangID=d.AktaSidangID
						WHERE a.AktaSidangID=".$iAktaSidangID." 
						LIMIT 1
						";
		}				
				$perintah = mysql_query($sSQL0);
				$num_rows = mysql_num_rows($perintah);
				$hasilGD0=mysql_fetch_array($perintah);
				extract($hasilGD0);
				$AktaSidangID=$hasilGD0[AktaSidangID];
				
$Judul = "Laporan ".$iLap." - Detail Daftar Kegiatan Klasis <br>Berdasarkan ".$hasilGD0[NamaAkta];
require "Include/Header-Report.php";

//$Kelas = "$ANDKelas '$GetKelas'  ";


//echo $Kelas
?>

			<table border="1"  width="1100" cellspacing=0 cellpadding=0 >
			<u><b> </b></u>
	<?php
			
	if (strlen($iTGL>0))
	{
				echo "<tr><td>";
				echo "<a href=\"PrintViewKegiatanKlasis.php?Urut=".$iUrut."&TGL=".$monthago."\"  >";
				echo "<< </a></td></td>";
				echo "<td colspan=\"6\" align=\"center\" > bulan ".date2Ind($iTGL,5)." ada <b> $num_rows (".Terbilang($num_rows).")</b> Kegiatan Klasis</td>";
				echo "<td align=\"right\">";
				echo "<a href=\"PrintViewKegiatanKlasis.php?Urut=".$iUrut."&TGL=".$nextmonth."\"  >";
				echo ">> </a></td></tr>";
	}
	?> 


		</tr>		
		<tr><b><font size="2">
			
	<td ALIGN=center rowspan="2" ><b><font size="2">
	<a href="PrintViewKegiatanKlasis.php"  STYLE="TEXT-DECORATION: NONE" class="help" title="Detail">No. </a></font></b></td>
	<td ALIGN=center rowspan="2" ><b><font size="2">Nama Kegiatan</font></b></td>
	<td ALIGN=center rowspan="2" ><b><font size="2">Reff Akta</font></b></td>
	<td ALIGN=center colspan="2" ><b><font size="2">Rencana Kegiatan</font></b></td>
	<td ALIGN=center colspan="2" ><b><font size="2">Pelaksanaan</font></b></td>
	<td ALIGN=center colspan="2" ><b><font size="2">Laporan</font></b></td>
	</font></b></tr>
	
	<tr><b><font size="2">
			
	<td ALIGN=center  width="100" ><b><font size="2">Tempat</font></b></td>
	<td ALIGN=center  width="100" ><b><font size="2">Waktu</font></b></td>
	<td ALIGN=center  width="100" ><b><font size="2">Tempat</font></b></td>
	<td ALIGN=center  width="100" ><b><font size="2">Waktu</font></b></td>
	<td ALIGN=center><b><font size="2">Jml Peserta</font></b></td>
	<td ALIGN=center width="200" ><b><font size="2">Keterangan</font></b></td>

	</font></b></tr>
	
	<?php
		$sRowClass = "RowColorA";
if ( $iBidangID < 1 ) {		    
		$sSQL1 = "SELECT a.AktaSidangID, c.NamaBidang as NamaBidang, a.BidangID as BidangID
				FROM KegiatanKlasis a
				LEFT JOIN MasterBidang c ON a.BidangID = c.BidangID
				GROUP BY a.BidangID
				";
	}else{
		$sSQL1 = "SELECT a.AktaSidangID, c.NamaBidang as NamaBidang, a.BidangID as BidangID
				FROM KegiatanKlasis a
				LEFT JOIN MasterBidang c ON a.BidangID = c.BidangID
				WHERE a.BidangID=".$iBidangID." LIMIT 1
				";
		
	}
			//echo $sSQL1;	
			$perintah1 = mysql_query($sSQL1);
			$num_rows1 = mysql_num_rows($perintah1);
			$ii = 0;
			while ($hasilGD=mysql_fetch_array($perintah1))
			{		
				$ii++;
				extract($hasilGD);
					//Alternate the row color
                    $sRowClass = AlternateRowStyle($sRowClass);
				?>

				<tr class="<?php echo $sRowClass; ?>">
				<td align="center"><font size="2"><b><?php echo "<a class=\"SmallText\" href=\"PrintViewKegiatanKlasis.php?BidangID=$hasilGD[BidangID]&AktaSidangID=$hasilGD[AktaSidangID]\" STYLE=\"TEXT-DECORATION: NONE\" class=\"help\" title=\"Lihat Kegiatan khusus Bidang : $hasilGD[NamaBidang]\" >".dec2roman($ii)."</a>"; ?></b></td>
				<td align="left" colspan="8"><b><font size="2"><?php echo "<a class=\"SmallText\" href=\"PrintViewKegiatanKlasis.php?BidangID=$hasilGD[BidangID]&AktaSidangID=$hasilGD[AktaSidangID]\" STYLE=\"TEXT-DECORATION: NONE\" class=\"help\" title=\"Lihat Kegiatan khusus Bidang : $hasilGD[NamaBidang]\" >".$NamaBidang."</a>"; ?></b></td>
				</tr>
				
				<?
				 $sRowClass = "RowColorA";
				        $sSQL = "SELECT a.*, a.Tanggal as TglPlan, b.*, c.*, d.* FROM KegiatanKlasis a
						LEFT JOIN DaftarGerejaGKJ b ON a.GerejaID=b.GerejaID
						LEFT JOIN MasterBidang c ON a.BidangID=c.BidangID
						LEFT JOIN AktaSidang d ON a.AktaSidangID=d.AktaSidangID
						LEFT JOIN NotulaRapat e ON a.NotulaRapatID=e.NotulaRapatID
						WHERE a.BidangID=".$hasilGD[BidangID];
				//echo $sSQL;		
				$perintah = mysql_query($sSQL);
				$num_rows = mysql_num_rows($perintah);
				$i = 0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{

					$i++;
					extract($hasilGD);

                    $sRowClass = AlternateRowStyle($sRowClass);


				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td align="right"><font size="1"> .<?php echo $i ?></td>
				
				<td><font size="1"><? 
						if ((($NamaGereja<1)&&($TempatKegiatan==''))&&($TanggalMulai=='0000-00-00')){
						echo "<font color=red >";	echo $NamaKegiatan ;echo "</font>";}else{
							if ($NotulaRapatID>0){
								echo "<a href=\"NotulaRapatView.php?NotulaRapatID=".$NotulaRapatID."\" target=\"_blank\" STYLE=\"TEXT-DECORATION: NONE\" class=\"help\" title=\"Lihat Notula Rapat : $NamaKegiatan\" >";
								echo $NamaKegiatan; 
							echo "</a>";}else{ echo $NamaKegiatan;  }
						} 

				
				
				
				?></td>
				<td  ALIGN=center ><font size="1"><?=$hasilGD[Artikel]?><br>
	
				<td  ALIGN=center><font size="1"><?=$hasilGD[Tempat]?></font></td>
				<td  ALIGN=center><font size="1">
				<? if ($TglPlan<>''){ echo "<u>".date2Ind($TglPlan,4)."</u>";}?><br>
				<? if ($Pukul<>''){ echo "Pk.".$Pukul. " WIB"; }?> </font></td>
				
				<td  ALIGN=center><font size="1"><?=$hasilGD[NamaGereja]?><?=$hasilGD[TempatKegiatan]?></font></td>
				<td  ALIGN=center><font size="1">

				<? if ($TanggalMulai<>''){ echo "<u>".date2Ind($TanggalMulai,4)."</u>";}?>
				<? if ($TanggalSelesai<>''){ echo " - <u>".date2Ind($TanggalSelesai,4)."</u>";}?>
				<br>
				<? if ($JamMulai<>''){ echo "Pk.".$JamMulai;}?>
				<? if ($JamSelesai<>''){ echo " - ".$JamSelesai." WIB";}?>
				</td>
				<td  ALIGN=center ><font size="1"><?=$hasilGD[JmlPeserta]?></td>
				<td  ALIGN=center width="100" ><font size="1"><?=$hasilGD[Laporan]?></td>
				</tr>
				
				<?}?>
				<?}?>
				
				
			</table>



<?php
require "Include/Footer-Short.php";
?>
