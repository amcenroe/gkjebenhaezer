<?php
/*******************************************************************************
 *
 *  filename    : PrintViewSuratMasuk.php
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

$Judul = "Laporan ".$iLap." - Detail Daftar Surat Masuk , Bulan : ".date2Ind($iTGL,5);
require "Include/Header-Report.php";

//$Kelas = "$ANDKelas '$GetKelas'  ";


//echo $Kelas
?>

			<table border="0"  width="1100" cellspacing=0 cellpadding=0 >
			<u><b> </b></u>
	<?php
				 $sRowClass = "RowColorA";
				$sSQL = "SELECT a.*, b.* ,c.vol_Name as Jabatan1, d.vol_Name as Jabatan2 FROM SuratMasuk a
				LEFT JOIN KlasifikasiSurat b ON a.ket3 = b.KlasID 
				LEFT JOIN volunteeropportunity_vol c ON a.Bidang1 = c.vol_ID 
				LEFT JOIN volunteeropportunity_vol d ON a.Bidang2 = d.vol_ID 
				".$Laporan." ".$Kategori." ".$sSQLTanggal." ".$Urutan;

//echo $sSQL;
				//$sSQL = "select c.lst_OptionName as StatusKewargaan, a.per_FirstName as Nama ,
				//         per_HomePhone as TelpRumah, per_BirthYear as TahunLahir ,
				//         per_WorkPhone as Kelompok from person_per a , list_lst c
				//			WHERE a.per_cls_ID = c.lst_OptionID AND c.lst_ID = 1
				//			AND c.lst_OptionID = '$iStatus'
				//			ORDER BY a.per_WorkPhone";
				$perintah = mysql_query($sSQL);
				
				$num_rows = mysql_num_rows($perintah);

				
	if (strlen($iTGL>0))
	{
				echo "<tr><td>";
				echo "<a href=\"PrintViewSuratMasuk.php?Urut=".$iUrut."&TGL=".$monthago."\"  >";
				echo "<< </a></td></td>";
				echo "<td colspan=\"6\" align=\"center\" > bulan ".date2Ind($iTGL,5)." ada <b> $num_rows (".Terbilang($num_rows).")</b> surat masuk</td>";
				echo "<td align=\"right\">";
				echo "<a href=\"PrintViewSuratMasuk.php?Urut=".$iUrut."&TGL=".$nextmonth."\"  >";
				echo ">> </a></td></tr>";
	}
	?> 


		</tr>		
	
	<tr><b><font size="2">
			
	<td ALIGN=center><b><font size="2">
	<a href="PrintViewSuratMasuk.php?<?echo"&Lap=".$iLap."&TGL=".$iTGL;?>"  STYLE="TEXT-DECORATION: NONE" class="help" title="Detail">No. </a></font></b></td>
	<td ALIGN=center><b><font size="2"></font></b></td>
	<td ALIGN=center><b><font size="2">
	<u><a href="PrintViewSuratMasuk.php?Urut=TglSurat<?echo"&Lap=".$iLap."&TGL=".$iTGL;?>"  STYLE="TEXT-DECORATION: NONE" class="help" title="Diurutkan berdasarkan Tanggal Surat">Tgl.Surat</a></u> 
	<br><a href="PrintViewSuratMasuk.php?Urut=TglTerima<?echo"&Lap=".$iLap."&TGL=".$iTGL;?>"  STYLE="TEXT-DECORATION: NONE" class="help" title="Diurutkan berdasarkan Tanggal TERIMA Surat"> Tgl.Diterima</a>
	<br> Nomor Surat</font></b></td>
	<td ALIGN=center><b><font size="2">
	<u><a href="PrintViewSuratMasuk.php?Urut=Pengirim<?echo"&Lap=".$iLap."&TGL=".$iTGL;?>"  STYLE="TEXT-DECORATION: NONE" class="help" title="Diurutkan berdasarkan PENGIRIM">Pengirim</a></u> 
	<br><a href="PrintViewSuratMasuk.php?Urut=Institusi<?echo"&Lap=".$iLap."&TGL=".$iTGL;?>"  STYLE="TEXT-DECORATION: NONE" class="help" title="Diurutkan berdasarkan INSTITUSI PENGIRIM">Institusi</a></font></b></td>
	<td ALIGN=center><b><font size="2">
	<u><a href="PrintViewSuratMasuk.php?Urut=Kepada<?echo"&Lap=".$iLap."&TGL=".$iTGL;?>"  STYLE="TEXT-DECORATION: NONE" class="help" title="Diurutkan berdasarkan TUJUAN">	Tujuan</a></u>
	<br>Bidang Terkait</font></b></td>
	<td ALIGN=center><b><font size="2"><u>Hal</u><br>Deskripsi</font></b></td>
	<td ALIGN=center width="15%" ><b><font size="2"><u>Follow Up</u><br>Status</font></b></td>
	<td ALIGN=center width="10%" ><b><font size="2">
	<u><a href="PrintViewSuratMasuk.php?Urut=Kategori<?echo"&Lap=".$iLap."&TGL=".$iTGL;?>"  STYLE="TEXT-DECORATION: NONE" class="help" title="Diurutkan berdasarkan KATEGORI">Kategori</a></u>
	<br><a href="PrintViewSuratMasuk.php?Urut=Urgensi<?echo"&Lap=".$iLap."&TGL=".$iTGL;?>"  STYLE="TEXT-DECORATION: NONE" class="help" title="Diurutkan berdasarkan URGENSI">Urgensi</a></font></b></td>
	<td ALIGN=center><b><font size="2"></font></b></td>
	</font></b></tr>
			<?php

				$i = 0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{

					$i++;
					extract($hasilGD);
					//Alternate the row color
                    $sRowClass = AlternateRowStyle($sRowClass);


				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td><font size="1"><?php echo "<a class=\"SmallText\" href=\"MailView.php?MailID=$hasilGD[MailID]\">$i</a>"; ?></td><td></td>
				
				<td><font size="1"><u><? echo date2Ind($hasilGD[Tanggal],3); ?></u><br><u><? echo date2Ind($hasilGD[Ket1],3); ?>
				</u><br><?=$hasilGD[NomorSurat]?><br></td>
				
				
				
				<td><font size="1"><u><?=$hasilGD[Dari]?></u><br><?=$hasilGD[Institusi]?><br></td>
				<td ><font size="1"><u><?=$hasilGD[Kepada]?></u><br>- <?=$hasilGD[Jabatan1]?><br>- <?=$hasilGD[Jabatan2]?><br></td>
				<td><font size="1"><u><?=$hasilGD[Hal]?></u><br><?=$hasilGD[Ket2]?><br><br></td>

				<td><font size="1"><u><?=$hasilGD[FollowUp]?></u><br><?=$hasilGD[Status]?><br></td>

				<td ALIGN=center><font size="1"><font size="1"><u><?=$hasilGD[Deskripsi]?></u><br>
				<?php 
							switch ($hasilGD[Urgensi])
							{
							case 1:
								echo gettext("<font color=red ><b>Sangat Segera</b></font>");
								break;
							case 2:
								echo gettext("<b>Segera</b>");
								break;
							case 3:
								echo gettext("Biasa");
								break;
							}
				?></td>
				</tr>
				<?}?>
			</table>



<?php
require "Include/Footer-Short.php";
?>
