<?php
/*******************************************************************************
 *
 *  filename    : PrintViewLapBulananBendahara.php
 *
 *  2012 Erwin Pratama for GKJ Bekasi Timur
 *  Sistem Informasi GKJ Bekasi Timur is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

$iTGL = FilterInput($_GET["TGL"]);

if (strlen($iTGL>0))
{
$iTGL = FilterInput($_GET["TGL"]);
$iTGLY = date(Y,$iTGL);
$iTGLM = date(m,$iTGL);

$minggukemaren = date("Y-m-d", strtotime('last Sunday', strtotime($iTGL)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', strtotime($iTGL)));

	$tanggal=$iTGL;
	$time = strtotime($tanggal);
	$monthago = date("Y-m-d", strtotime("-1 month", $time));
	$nextmonth = date("Y-m-d", strtotime("+1 month", $time));
	$iTGLawal = date(date2Ind($iTGL,11)."-1");
	$iTGLawalanggaran = date("Y-1-1");

$sSQLTanggal =  " WHERE MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow)) ;
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".date(n,strtotime('-1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('-1 month', $time))  ;
$sSQLTanggalBulanBesok =  "  MONTH(Tanggal) = ".date(n,strtotime('+1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('+1 month', $time))  ;
$sSQLTanggalBulanIni =  "  Tanggal >= '".date($iTGLawalanggaran)."' AND Tanggal < '".date($iTGLawal)."'  ";
//echo $sSQLTanggalBulanIni;
}else
{
$hariini = strtotime(date("Y-m-d"));
$iTGL = date("Y-m-d");

$mingguterakhir = date("Y-m-d", strtotime('last Sunday', $hariini));
$minggukemaren = date("Y-m-d", strtotime('-1 week', strtotime($mingguterakhir)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', $hariini));

	$tanggal=$iTGL;
	$time = strtotime($tanggal);
	$monthago = date("Y-m-d", strtotime("-1 month", $time));
	$nextmonth = date("Y-m-d", strtotime("+1 month", $time));
		$iTGLawal = date(date2Ind($iTGL,11)."-1");
	$iTGLawalanggaran = date("Y-1-1");

	
$sSQLTanggal =  " WHERE MONTH(a.Tanggal) = ".$iTGLnow." AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGLnow)) ;
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".date(n,strtotime('-1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('-1 month', $time))  ;
$sSQLTanggalBulanBesok =  "  MONTH(Tanggal) = ".date(n,strtotime('+1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('+1 month', $time))  ;
$sSQLTanggalBulanIni =  "  Tanggal >= '".date($iTGLawalanggaran)."' AND Tanggal < '".date($iTGLawal)."'  ";
//echo $sSQLTanggalBulanIni;


}



// Get the person ID from the querystring
$iPersembahan_ID = FilterInput($_GET["Persembahan_ID"],'int');

// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}
	  	list($year , $month, $day ) = preg_split('[/.-]', $Tahun);
		//echo "Month: $month; Day: $day; Year: $year<br />\n"

// Set the page title and include HTML header
$sPageTitle = "Laporan Bulanan Bendahara ".date2Ind($iTGL,1);
$iTableSpacerWidth = 1;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or Print Laporan Bulanan Bendahara ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center>

<!-- Awal Tabel Header -->		
<table border="0"  width="650" cellspacing=0 cellpadding=0>
  <tr><!-- Row 2 -->
  		<td align=left  valign=bottom >
		<?php
				echo "<a href=\"PrintViewLapBulananBendahara.php?TGL=".$monthago."\"  >";
				echo "<< </a></td>";
		?>
		</td>
     <td valign=top align=center width="80">
     <img border="0" src="gkj_logo.jpg" width="80" >
     </td><!-- Col 1 -->

     <td valign=top align=center width="450">
     <b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo "$sChurchFullName" ;?></font></b><BR>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font></b>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	  <?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font></b><br>
	 <b style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="3"><?php echo "" ;?></font></b>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <b style="font-family: Arial; color: rgb(0, 0, 102);">
	  <hr style="width: 100%; height: 2px;">
  
	 <b><font size="2">Laporan Bulanan Bendahara ,bulan <? echo date2Ind($monthago,5); ?></font></b><br><font size="1"><i>dilaporkan: <? echo date2Ind($iTGL,2); ?></i></font>
        </td><!-- Col 3 -->
		<td align=right valign=bottom >
		<?php
				echo "<a href=\"PrintViewLapBulananBendahara.php?TGL=".$nextmonth."\"  >";
				echo " >></a></td>";
		?>
		</td>
  </tr>
</table>
<!-- Akhir Tabel Header -->			
<!-- Awal Tabel Data -->
<?php

// Data Persembahan Mingguan
 $sSQL = "SELECT 
(SUM( KebDewasa ) + SUM( KebAnak ) + SUM( KebRemaja )) AS PersKantong
, SUM(Syukur) as PersSyukur 
, (SUM(Khusus)+SUM(SyukurBaptis)+SUM(KhususPerjamuan)+SUM(Marapas)+SUM(Marapen)+SUM(Unduh)+SUM(Maranatal)) as PersKhusus
, SUM(Pink)+SUM(LainLain) as PersLainLain 
FROM Persembahangkjbekti
WHERE ".$sSQLTanggalBulanKemaren;
		$rsPerTI = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPerTI));

 $sSQL = "SELECT SUM(Persembahan) AS PersKantongAnakJTMY
FROM PersembahanAnakgkjbekti
WHERE KodeTI=10 AND ".$sSQLTanggalBulanKemaren;
		$rsPerTI = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPerTI));
		
//echo $sSQL;

// Data Persembahan Amplop Bulanan
$sSQL = "SELECT SUM(Bulanan) as PersBulanan, SUM(Syukur) as PersDanaPengembangan, SUM(ULK) as PersULK FROM PersembahanBulanan 
		WHERE ".$sSQLTanggalBulanKemaren;
		$rsBulanan = RunQuery($sSQL);
		extract(mysql_fetch_array($rsBulanan));

// Data Persembahan Khusus 
$sSQL = "SELECT SUM(Persembahan) as PersembahanKhusus FROM PersembahanKhususgkjbekti 
		WHERE ".$sSQLTanggalBulanKemaren;
		$rsKhusus = RunQuery($sSQL);
		extract(mysql_fetch_array($rsKhusus));

// Data Persembahan Kontribusi 
$sSQL = "SELECT SUM(Persembahan) as PersKontribusi FROM PersembahanKontribusigkjbekti 
		WHERE ".$sSQLTanggalBulanKemaren;
		$rsKontribusi = RunQuery($sSQL);
		extract(mysql_fetch_array($rsKontribusi));
		
// Data Pengeluaran
$sSQL = "SELECT SUM(Jumlah) as PengeluaranBulanan FROM PengeluaranKasKecil 
		WHERE ".$sSQLTanggalBulanKemaren;
		$rsPengeluaranBulanan = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPengeluaranBulanan));
		
$PersKantong=$PersKantong+$PersKantongAnakJTMY;	

$TotalPers=($PersKantong+$PersembahanKhusus)+$PersSyukur+$PersBulanan+$PersDanaPengembangan+$PersULK+$PersKhusus+($PersLainLain+$PersKontribusi);			

		?>
<table border="1" width="650"  bgcolor="#FFFFFF">

<tr><td>1</td><td colspan=4 ><b>Saldo Bank per <? echo date2Ind(date("Y-m-t", strtotime($monthago) ),2) ; ?> yang terdiri dari :</td><td></td><td></td></tr>
<tr><td></td><td>-</td><td colspan=2 >Kas Jemaat Bank</td><td>Rp.</td><td></td></tr>
<tr><td></td><td>-</td><td colspan=2 >Dana Pengembangan</td><td>Rp.</td><td></td><td></td></tr>
<tr><td></td><td>-</td><td colspan=2 >ULK (Unit Layanan Kasih)</td><td>Rp.</td><td></td><td></td></tr>
<tr><td></td><td>-</td><td colspan=2 >Kas Tunai</td><td>Rp.</td><td></td><td></td></tr>
<tr><td></td><td colspan=3 >Saldo Bank + Tunai</td><td>Rp.</td><td></td><td></td></tr>
<tr></tr>
<tr><td>2</td><td colspan=3 ><b>Persembahan  yang  belum masuk bank <? echo  date2Ind(date("Y-m-t", strtotime($monthago) ),5) ; ?> </td><td>Rp.</td><td></td></tr>
<tr></tr>
<tr><td>3</td><td colspan=4 ><b>Rincian Pemasukan dan Pengeluaran Bulan ini :</td><td></td><td></td></tr>
<tr><td></td><td>-</td><td colspan=2 >Pemasukan</td><td>Rp.</td><td><? echo currency(' ',$TotalPers,'.',',00')?></td><td></td></tr>
<tr><td></td><td>-</td><td colspan=2 >Pengeluaran</td><td>Rp.</td><td><? echo currency(' ',$PengeluaranBulanan,'.',',00')?></td><td></td></tr>
<tr><td></td><td>-</td><td colspan=2 >Selisih</td><td>Rp.</td><td><? echo currency(' ',$TotalPers-$PengeluaranBulanan,'.',',00')?>.</td><td></td></tr>	
<tr></tr>
<tr><td>4</td><td colspan=4 ><b>Rincian Pemasukan Bulan ini :</td><td></td><td></td></tr>
<?
//Pers Kantong
//$TotalPers=($PersKantong+$PersembahanKhusus)+$PersSyukur+$PersBulanan+$PersDanaPengembangan+$PersULK+$PersKhusus+($PersLainLain+$PersKontribusi);					
echo "<tr><td></td><td>-</td><td colspan=2 >Persembahan Kantong</td><td>Rp.</td>
<td align=\"right\" title=\"Pers.Kantong : ".$PersKantong."  &#xA; \">
<a href=\"PrintViewCekPersembahan.php?Cek=BendMPH&DetailCek=Kantong&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
".currency(' ',$PersKantong,'.',',00')."</a></td>
<td align=\"center\" >".round(@($PersKantong/$TotalPers*100),2)."%</td></tr>";

//Pers Syukur
echo "<tr><td></td><td>-</td><td colspan=2 >Persembahan Syukur</td><td>Rp.</td>
<td  align=\"right\" title=\"Pers.Syukur : ".$PersSyukur." &#xA; \">
<a href=\"PrintViewCekPersembahan.php?Cek=BendMPHSyukur&DetailCek=Kantong&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
".currency(' ',$PersSyukur,'.',',00')."</a></td>
<td align=\"center\" >".round(@($PersSyukur/$TotalPers*100),2)."%</td></tr>";

//Pers Bulanan
echo "<tr><td></td><td>-</td><td colspan=2 >Persembahan Bulanan</td><td>Rp.</td>
<td align=\"right\" title=\"Pers.Bulanan : ".$PersBulanan." &#xA; \">
<a href=\"PrintViewCekPersembahan.php?Cek=BendMPHBulanan&DetailCek=Bulanan&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
".currency(' ',$PersBulanan,'.',',00')."</a></td>
<td align=\"center\" >".round(@($PersBulanan/$TotalPers*100),2)."%</td></tr>";

//Pers Dana Pengembangan
echo "<tr><td></td><td>-</td><td colspan=2 >Dana Pengembangan</td><td>Rp.</td>
<td  align=\"right\" title=\"Dana Pengembangan : ".$PersDanaPengembangan." &#xA; \">
<a href=\"PrintViewCekPersembahan.php?Cek=BendMPHBulanan&DetailCek=Pengembangan&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
".currency(' ',$PersDanaPengembangan,'.',',00')."</a></td>
<td align=\"center\" >".round(@($PersDanaPengembangan/$TotalPers*100),2)."%</td></tr>";

//Pers ULK
echo "<tr><td></td><td>-</td><td colspan=2 >Unit Layanan Kasih (ULK)</td><td>Rp.</td>
<td  align=\"right\" title=\"ULK : ".$PersULK." &#xA; \">
<a href=\"PrintViewCekPersembahan.php?Cek=BendMPHBulanan&DetailCek=ULK&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
".currency(' ',$PersULK,'.',',00')."</a></td>
<td align=\"center\" >".round(@($PersULK/$TotalPers*100),2)."%</td></tr>";

//Pers Khusus
echo "<tr><td></td><td>-</td><td colspan=2 >Persembahan Khusus (Perjamuan kudus, Baptis)</td><td>Rp.</td>
<td align=\"right\" title=\"Pers.Khusus : ".$PersKhusus."+".$PersembahanKhusus." &#xA; \">
<a href=\"PrintViewCekPersembahan.php?Cek=BendMPH&DetailCek=Khusus&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
".currency(' ',$PersKhusus+$PersembahanKhusus,'.',',00')."</a></td>
<td align=\"center\" >".round(@(($PersKhusus+$PersembahanKhusus)/($TotalPers*100)),2)."%</td></tr>";

//Pers Lain Lain
echo "<tr><td></td><td>-</td><td colspan=2 >Persembahan Lain Lain (Lain Lain, Kontribusi )</td><td>Rp.</td>
<td  align=\"right\" title=\"Pers.Lain Lain : ".$PersLainLain." + Pers.Kontribusi : ".$PersKontribusi." &#xA; \">
<a href=\"PrintViewCekPersembahan.php?Cek=BendMPHLain&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
".currency(' ',$PersLainLain+$PersKontribusi,'.',',00')."</a></td>
<td align=\"center\" >".round(@(($PersLainLain+$PersKontribusi)/$TotalPers*100),2)."%</td></tr>";

echo "<tr><td></td><td>-</td><td colspan=2 ><b>Total Pemasukan</b></td><td>Rp.</td><td align=\"right\" >".currency(' ',$TotalPers,'.',',00')."</td></tr>";
echo "<tr><td></td><td>-</td><td colspan=2 ><b>Total Pengeluaran</b></td><td>Rp.</td><td align=\"right\" >".currency(' ',$PengeluaranBulanan,'.',',00')."</td></tr>";
echo "<tr><td></td><td>-</td><td colspan=2 ><b>Saldo Bulan Ini</b></td><td>Rp.</td><td align=\"right\" >".currency(' ',$TotalPers-$PengeluaranBulanan,'.',',00')."</td></tr>";

?>
<tr></tr>
<tr><td>5</td><td colspan=4 ><b>Rincian Pemasukan Bulan Januari s/d <? echo  date2Ind(date("Y-m-t", strtotime($monthago) ),5) ; ?> berdasarkan :</td><td></td><td></td></tr>

<?

// Data SAMPAI DENGAN bulan terkhir

// Data Persembahan Mingguan
 $sSQL = "SELECT 
(SUM( KebDewasa ) + SUM( KebAnak ) + SUM( KebAnakJTMY ) + SUM( KebRemaja )) AS PersKantong
, SUM(Syukur) as PersSyukur 
, (SUM(Khusus)+SUM(SyukurBaptis)+SUM(KhususPerjamuan)+SUM(Marapas)+SUM(Marapen)+SUM(Unduh)+SUM(Maranatal)) as PersKhusus
, SUM(Pink)+SUM(LainLain) as PersLainLain 
FROM Persembahangkjbekti
WHERE ".$sSQLTanggalBulanIni;
		$rsPerTI = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPerTI));

// Data Persembahan Amplop Bulanan
$sSQL = "SELECT SUM(Bulanan) as PersBulanan, SUM(Syukur) as PersDanaPengembangan, SUM(ULK) as PersULK FROM PersembahanBulanan 
		WHERE ".$sSQLTanggalBulanIni;
		$rsBulanan = RunQuery($sSQL);
	//	echo $sSQL;
		extract(mysql_fetch_array($rsBulanan));

// Data Persembahan Khusus 
$sSQL = "SELECT SUM(Persembahan) as PersembahanKhusus FROM PersembahanKhususgkjbekti 
		WHERE ".$sSQLTanggalBulanIni;
		$rsKhusus = RunQuery($sSQL);
		extract(mysql_fetch_array($rsKhusus));

// Data Persembahan Kontribusi 
$sSQL = "SELECT SUM(Persembahan) as PersKontribusi FROM PersembahanKontribusigkjbekti 
		WHERE ".$sSQLTanggalBulanIni;
		$rsKontribusi = RunQuery($sSQL);
		extract(mysql_fetch_array($rsKontribusi));
		
// Data Pengeluaran
$sSQL = "SELECT SUM(Jumlah) as PengeluaranBulanan FROM PengeluaranKasKecil 
		WHERE ".$sSQLTanggalBulanIni;
		$rsPengeluaranBulanan = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPengeluaranBulanan));		


	
$TotalPers=($PersKantong+$PersembahanKhusus)+$PersSyukur+$PersBulanan+$PersDanaPengembangan+$PersULK+$PersKhusus+($PersLainLain+$PersKontribusi);					
echo "<tr><td></td><td>-</td><td colspan=2 >Persembahan Kantong</td><td>Rp.</td><td align=\"right\" >".currency(' ',$PersKantong+$PersembahanKhusus,'.',',00')."</td>
<td align=\"center\" >".round(@($PersKantong/$TotalPers*100),2)."%</td></tr>";
echo "<tr><td></td><td>-</td><td colspan=2 >Persembahan Syukur</td><td>Rp.</td><td align=\"right\" >".currency(' ',$PersSyukur,'.',',00')."</td>
<td align=\"center\" >".round(@($PersSyukur/$TotalPers*100),2)."%</td></tr>";
echo "<tr><td></td><td>-</td><td colspan=2 >Persembahan Bulanan</td><td>Rp.</td><td align=\"right\" >".currency(' ',$PersBulanan,'.',',00')."</td>
<td align=\"center\" >".round(@($PersBulanan/$TotalPers*100),2)."%</td></tr>";
echo "<tr><td></td><td>-</td><td colspan=2 >Dana Pengembangan</td><td>Rp.</td><td align=\"right\" >".currency(' ',$PersDanaPengembangan,'.',',00')."</td>
<td align=\"center\" >".round(@($PersDanaPengembangan/$TotalPers*100),2)."%</td></tr>";
echo "<tr><td></td><td>-</td><td colspan=2 >Unit Layanan Kasih (ULK)</td><td>Rp.</td><td align=\"right\" >".currency(' ',$PersULK,'.',',00')."</td>
<td align=\"center\" >".round(@($PersULK/$TotalPers*100),2)."%</td></tr>";
echo "<tr><td></td><td>-</td><td colspan=2 >Khusus (Perjamuan kudus, Baptis)</td><td>Rp.</td><td align=\"right\" >".currency(' ',$PersKhusus,'.',',00')."</td>
<td align=\"center\" >".round(@($PersKhusus/$TotalPers*100),2)."%</td></tr>";
echo "<tr><td></td><td>-</td><td colspan=2 >Lain-lain (A. pink, Kontr TI TMBN dll)</td><td>Rp.</td><td align=\"right\" >".currency(' ',$PersLainLain+$PersKontribusi,'.',',00')."</td>
<td align=\"center\" >".round(@(($PersLainLain+$PersKontribusi)/$TotalPers*100),2)."%</td></tr>";

echo "<tr><td></td><td>-</td><td colspan=2 ><b>Total Pemasukan</b></td><td>Rp.</td><td align=\"right\" >".currency(' ',$TotalPers,'.',',00')."</td></tr>";
echo "<tr><td></td><td>-</td><td colspan=2 ><b>Total Pengeluaran</b></td><td>Rp.</td><td align=\"right\" >".currency(' ',$PengeluaranBulanan,'.',',00')."</td></tr>";
echo "<tr><td></td><td>-</td><td colspan=2 ><b>Saldo Bulan Ini</b></td><td>Rp.</td><td align=\"right\" >".currency(' ',$TotalPers-$PengeluaranBulanan,'.',',00')."</td></tr>";

?>
<tr></tr>
<tr><td>6</td><td colspan=4 ><b>Saldo PPPG s/d <? echo  date2Ind(date("Y-m-t", strtotime($monthago) ),5) ; ?> :</td><td></td><td></td></tr>
<?php
				echo "<tr>";
				echo "<td ></td>";
				echo "<td > - </td>";
				echo "<td >Keterangan</td>";
				echo "<td >Jumlah Kartu</td>";
				echo "<td ></td>";
				echo "<td >Jumlah</td>";
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
			
			echo "<tr><td></td><td>-</td><td colspan=2 ><b>Saldo PPPG Tahun ".($ThnKemaren-1)." (A)</b></td><td>Rp.</td><td align=\"right\" >".currency(' ',$SaldoThnKemaren,'.',',00')."</td></tr>";


			$sSQL = "SELECT a.*,b.*, SUM(a.JmlAmplop) as JmlAmplop, COUNT(a.Bulanan) as AmplopBulanan, SUM(a.Bulanan) as Bulanan FROM PersembahanPPPG a
			LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis
			WHERE ".$sSQLTanggalBulanIni." GROUP BY a.Pos ";
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
				$PosSubTotalBulanan+=$Bulanan;
				echo "<tr>";
				echo "<td></td>";
				echo "<td>- </td>";
				echo "<td align=\"left\">".$Keterangan."</td>";
				echo "<td align=\"center\">";if (strlen($JmlAmplop>0)){echo $JmlAmplop;}else{echo $AmplopBulanan;};echo "</td>";
				echo "<td align=\"left\">Rp.</td><td align=\"right\">
				<a href=\"PrintViewCekPersembahan.php?Cek=PPPG&DetailCek=MPH&Pos=".$Pos."&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
				".currency(' ',$Bulanan,'.',',00')."</a></td>";
				echo "</tr>";
			}
			echo "<tr><td></td><td>-</td><td colspan=2 ><b>Pemasukan PPPG s/d ".date2Ind(date("Y-m-t", strtotime($monthago) ),5)." (B)</b></td>
			<td>Rp.</td><td align=\"right\" >".currency(' ',$PosSubTotalBulanan,'.',',00')."</td></tr>";
			echo "<tr><td></td><td>-</td><td colspan=2 ><b>Total Pemasukan PPPG ( A + B )</b></td>
			<td>Rp.</td><td align=\"right\" ><b>".currency(' ',$PosSubTotalBulanan+$SaldoThnKemaren,'.',',00')."</b></td></tr>";

			$sSQL = "SELECT a.Pos, b.Keterangan as Keterangan2 , SUM(a.Jumlah) as TotalPerBulan2 FROM PengeluaranPPPG a
				LEFT JOIN JenisPengeluaranPPPG b ON a.Pos = b.KodeJenis 
				WHERE ".$sSQLTanggalBulanIni." GROUP BY a.Pos 
				";
			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan2=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
						
				$i++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				$PosSubTotalBulanan2+=$TotalPerBulan2;
				echo "<tr>";
				echo "<td></td>";
				echo "<td>- </td>";
				echo "<td align=\"left\">".$Keterangan2."</td>";
				echo "<td align=\"center\">";if (strlen($JmlAmplop>0)){echo $JmlAmplop;}else{echo $AmplopBulanan;};echo "</td>";
				echo "<td align=\"left\">Rp.</td>";
				echo "<td align=\"right\">
				<a href=\"PrintViewCekPersembahan.php?Cek=PengeluaranPPPG&DetailCek=MPH&Pos=".$Pos."&TGL=".$iTGL." \" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
				".currency(' ',$TotalPerBulan2,'.',',00')."</a></td>";
				echo "</tr>";
			}
			echo "<tr><td></td><td>-</td><td colspan=2 ><b>Total Pengeluaran PPPG (C)</b></td><td>Rp.</td>
			<td align=\"right\" >".currency(' ',$PosSubTotalBulanan2,'.',',00')."</td></tr>";
			echo "<tr><td></td><td>-</td><td colspan=2 ><b>Saldo PPPG (( A + B ) - C )</b></td><td>Rp.</td>
			<td align=\"right\" ><b>".currency(' ',($PosSubTotalBulanan+$SaldoThnKemaren)-$PosSubTotalBulanan2,'.',',00')."</b></td></tr>";
				
				
?>
</table>


