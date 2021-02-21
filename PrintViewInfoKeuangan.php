<?php
/*******************************************************************************
 *
 *  filename    : PrintViewInfoKeuangan.php
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
				echo "<a href=\"PrintViewInfoKeuangan.php?TGL=".$monthago."\"  >";
				echo "<< </a></td>";
		?>
		</td>
     <td valign=top align=center width="80">
     <img border="0" src="gkj_logo.jpg" width="80" >
     </td><!-- Col 1 -->

     <td valign=top align=center width="450">
     <b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo "$sChurchName" ;?></font></b><BR>
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
  
	 <b><font size="2">Informasi Keuangan ,bulan <? echo date2Ind($monthago,5); ?></font></b><br><font size="1"><i>dilaporkan: <? echo date2Ind($iTGL,2); ?></i></font>
        </td><!-- Col 3 -->
		<td align=right valign=bottom >
		<?php
				echo "<a href=\"PrintViewInfoKeuangan.php?TGL=".$nextmonth."\"  >";
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
(SUM( KebDewasa ) + SUM( KebAnak ) + SUM( KebAnakJTMY ) + SUM( KebRemaja )) AS PersKantong
,SUM( KebDewasa ) as KantongDewasa ,SUM( KebAnak ) as KantongAnak ,SUM( KebAnakJTMY ) as KantongAnakJTMY ,SUM( KebRemaja ) as KantongRemaja
, SUM(Syukur) as PersSyukur 
, (SUM(Khusus)+SUM(SyukurBaptis)+SUM(KhususPerjamuan)+SUM(Marapas)+SUM(Marapen)+SUM(Unduh)+SUM(Maranatal)) as PersKhusus
, SUM(Khusus) as DPersKhusus, SUM(SyukurBaptis) as DPersSyukurBaptis, SUM(KhususPerjamuan) as DPersKhususPerjamuan
, SUM(Marapas) as DPersMarapas, SUM(Marapen) as DPersMarapen, SUM(Unduh) as DPersUnduhUnduh, SUM(Maranatal) as DPersMaranatal
, SUM(Pink)+SUM(LainLain) as PersLainLain 
FROM Persembahangkjbekti
WHERE DAYNAME(Tanggal) = 'Sunday' AND ".$sSQLTanggalBulanKemaren;

//echo $sSQL;

		$rsPerTI = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPerTI));
//echo $sSQL;
// Data Persembahan Bukan Hari Minggu
 $sSQL = "SELECT 
(SUM( KebDewasa ) + SUM( KebAnak ) + SUM( KebAnakJTMY ) + SUM( KebRemaja )) AS PersKantong2
,SUM( KebDewasa ) as KantongDewasa2 ,SUM( KebAnak ) as KantongAnak2 ,SUM( KebAnakJTMY ) as KantongAnakJTMY2 ,SUM( KebRemaja ) as KantongRemaja2
, SUM(Syukur) as PersSyukur2
, (SUM(Khusus)+SUM(SyukurBaptis)+SUM(KhususPerjamuan)+SUM(Marapas)+SUM(Marapen)+SUM(Unduh)+SUM(Maranatal)) as PersKhusus2
, SUM(Khusus) as DPersKhusus2, SUM(SyukurBaptis) as DPersSyukurBaptis2, SUM(KhususPerjamuan) as DPersKhususPerjamuan2
, SUM(Marapas) as DPersMarapas2, SUM(Marapen) as DPersMarapen2, SUM(Unduh) as DPersUnduhUnduh2, SUM(Maranatal) as DPersMaranatal2
, SUM(Pink)+SUM(LainLain) as PersLainLain2
FROM Persembahangkjbekti
WHERE DAYNAME(Tanggal) != 'Sunday' AND ".$sSQLTanggalBulanKemaren;
		$rsPerTI = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPerTI));
		
// Data Persembahan Amplop Bulanan
$sSQL = "SELECT SUM(Bulanan) as PersBulananTot, SUM(Syukur) as PersDanaPengembanganTot, SUM(ULK) as PersULKTot FROM PersembahanBulanan 
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
		

$TotalPers=($PersKantong+$PersembahanKhusus)+$PersSyukur+$PersBulanan+$PersDanaPengembangan+$PersULK+$PersKhusus+($PersLainLain+$PersKontribusi);			

		?>
<table border="1" width="700"  bgcolor="#FFFFFF">
<?
echo "<tr><td  rowspan=\"2\" align=\"center\" ><b>No</b></td><td colspan=\"2\"  rowspan=\"2\"  align=\"center\" ><b>URAIAN</b></td>
<td colspan=\"4\"  align=\"center\"  ><b>Per-".date2Ind($monthago,5)."</b></td>
<td rowspan=\"2\" colspan=\"2\" align=\"center\" ><b>TOTAL</b></td><td rowspan=\"2\" align=\"center\"  ><b>KETERANGAN</b></td></tr>";
echo "<tr><td align=\"center\" colspan=\"2\"><b>RINCIAN</b></td><td align=\"center\" colspan=\"2\"><b>SUB TOTAL</b></td></tr>";
$KasTunaiAwal=12345678;$TabMandiriAwal=12345678; $SetoranDalamPerjalanan=12345678;
$TotalSaldoAwal=$KasTunaiAwal+$TabMandiriAwal+$SetoranDalamPerjalanan;
echo "<tr><td><b> I </b></td><td colspan=\"2\" bgcolor=\"lime\" align=\"left\" ><font size=\"1\" ><b>KAS JEMAAT </b></font></td></tr>";
echo "<tr><td></td><td colspan=\"2\" bgcolor=\"aqua\" align=\"left\" ><font size=\"1\" ><b>A. SALDO AWAL ( A.1 + A.2 + A.3 )</b></font></td>
	<td></td><td></td><td></td><td></td><td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$TotalSaldoAwal,'.',',-')."</font></td><td rowspan=\"4\" align=\"left\" ><font size=\"1\" >Informasi diambil dari rekening koran</font></td></tr>";
	echo "<tr bgcolor=\"ffffff\"><td><font size=\"1\" ></font></td><td align=\"center\"><font size=\"1\" >1</font></td><td align=\"left\" ><font size=\"1\" >Kas Tunai</font></td>
	<td></td><td></td><td></td><td></td><td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$KasTunaiAwal,'.',',-')."</font></td><td></td>";
	echo "<tr bgcolor=\"ffffff\"><td><font size=\"1\" ></font></td><td align=\"center\"><font size=\"1\" >2</font></td><td align=\"left\" ><font size=\"1\" >Tabungan Mandiri</font></td>
	<td></td><td></td><td></td><td></td><td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$TabMandiriAwal,'.',',-')."</font></td><td></td>";
	echo "<tr bgcolor=\"ffffff\"><td><font size=\"1\" ></font></td><td align=\"center\"><font size=\"1\" >3</font></td><td align=\"left\" ><font size=\"1\" >Setoran Dalam Perjalanan</font></td>
	<td></td><td></td><td></td><td></td><td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$SetoranDalamPerjalananAwal,'.',',-')."</font></td><td></td>";
$PersIbadahMinggu=$KantongDewasa+$PersSyukur+$KantongAnak+$KantongAnakJTMY+$KantongRemaja+$PersLainLain;
$PersIbadahHariBesar=$KantongDewasa2+$PersSyukur2+$KantongAnak2+$KantongAnakJTMY2+$KantongRemaja2+$PersLainLain2;
	$PersIbadahTotal=$PersIbadahMinggu+$PersIbadahHariBesar;

//$PersBulananTotal=
//$PersIstimewaTotal=
//$LainLainBukanPersTotal=

$Pemasukan=$PersIbadahTotal+$PersBulananTotal+$PersIstimewaTotal+$LainLainBukanPersTotal;
echo "<tr><td></td><td colspan=\"2\" bgcolor=\"aqua\" align=\"left\" ><font size=\"1\" ><b>B. PEMASUKAN ( 1+2+3+4 )</b></font></td><td></td><td></td><td></td><td></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$JumlahTot,'.',',-')."</font></td></tr>";
	
	echo "<tr bgcolor=\"fcfcfc\"><td></td><td  bgcolor=\"yellow\" ><font size=\"1\" ><b>1</b></font></td>
	<td colspan=\"1\" bgcolor=\"yellow\" align=\"left\" ><font size=\"1\" ><b>Persembahan Ibadah (1a+1b) </b> </font></td><td></td><td></td><td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$PersIbadahTotal,'.',',-')."</font></td>";
		
	echo "<tr bgcolor=\"fcfcfc\">"; 
	echo "<td><font size=\"1\" >".$e."</font></td>";
	echo "<td colspan=\"2\" bgcolor=\"ccffcc\" align=\"left\" ><font size=\"1\" >a. Persembahan Ibadah Minggu</font></td>";

		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" >".$e."</font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Kantong</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',$KantongDewasa,'.',',-')."</font></td>"; 
		echo "<td></td>";
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" >".$e."</font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Syukur</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',$PersSyukur,'.',',-')."</font></td>"; 
		echo "<td></td>";		
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" >".$e."</font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Kebaktian Anak</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',$KantongAnak+$KantongAnakJTMY,'.',',-')."</font></td>"; 
		echo "<td></td>";	
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" >".$e."</font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Kebaktian Pra Remaja + Remaja</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',$KantongRemaja,'.',',-')."</font></td>"; 
		echo "<td></td>";
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" >".$e."</font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Lain Lain</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',$PersLainLain,'.',',-')."</font></td>"; 
		echo "<td></td>";		
	echo "<tr bgcolor=\"fcfcfc\">";
	echo "<td><font size=\"1\" >".$e."</font></td>";
	echo "<td colspan=\"2\" bgcolor=\"ccffcc\" align=\"left\" ><font size=\"1\" >b. Persembahan Ibadah Hari Besar</font></td>";
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" >".$e."</font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Kantong</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',$KantongDewasa2,'.',',-')."</font></td>"; 
		echo "<td></td>";
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" >".$e."</font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Syukur</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',$PersSyukur2,'.',',-')."</font></td>"; 
		echo "<td></td>";		
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" >".$e."</font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Kebaktian Anak</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',$KantongAnak2+$KantongAnakJTMY2,'.',',-')."</font></td>"; 
		echo "<td></td>";	
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" >".$e."</font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Kebaktian Pra Remaja + Remaja</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',$KantongRemaja2,'.',',-')."</font></td>"; 
		echo "<td></td>";
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" >".$e."</font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Lain Lain</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',$PersLainLain2,'.',',-')."</font></td>"; 
		echo "<td></td>";		
	echo "<tr bgcolor=\"fcfcfc\">";
	echo "<td ></td><td  bgcolor=\"yellow\" ><font size=\"1\" ><b>2</b></font></td>";
	echo "<td  bgcolor=\"yellow\" align=\"left\" ><font size=\"1\" ><b>Persembahan Bulanan</b></font></td><td align=\"right\" ><td></td><td align=\"right\"><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$PersBulananTot,'.',',-')."</font></td>";
// Persembahan Bulanan
$sSQL = "SELECT Kelompok, b.grp_Description as Deskripsi, SUM(Bulanan) as PersBulanan, SUM(Syukur) as PersDanaPengembangan, SUM(ULK) as PersULK FROM PersembahanBulanan a
		LEFT JOIN group_grp b ON a.Kelompok = b.grp_Name
		WHERE ".$sSQLTanggalBulanKemaren." GROUP By Kelompok ORDER BY Kelompok";
		$rsBulanan = RunQuery($sSQL);
		$e = 0;
		//Loop through the recordset
		while ($aRow = mysql_fetch_array($rsBulanan))
			{
				$e++;
				extract($aRow);
				echo "<tr bgcolor=\"ffffff\">";
				echo "<td><font size=\"1\" ></font></td>";
				echo "<td  bgcolor=\"white\" align=\"left\" > - </td><td><font size=\"1\" > ".$Kelompok." - ".$Deskripsi."</font></td>
				<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',$PersBulanan,'.',',-')."</font></td>"; 
				
			}
	echo "<tr bgcolor=\"fcfcfc\">";
	echo "<td ></td><td  bgcolor=\"yellow\" ><font size=\"1\" ><b>3</b></font></td>";
	echo "<td  bgcolor=\"yellow\" align=\"left\" ><font size=\"1\" ><b>Persembahan Istimewa</b></font></td><td align=\"right\" ><td></td><td align=\"right\"><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$PersIstimewaTot,'.',',-')."</font></td>";
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" ></font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Khusus</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',($DPersKhusus+$DPersKhusus2),'.',',-')."</font></td>"; 
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" ></font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Syukur Baptis/Sidi</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',($DPersSyukurBaptis+$DPersSyukurBaptis2),'.',',-')."</font></td>"; 
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" ></font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Perjamuan Kudus</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',($DPersKhususPerjamuan+$DPersKhususPerjamuan2),'.',',-')."</font></td>"; 
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" ></font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Masa Paskah</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',($DPersMarapas+$DPersMarapas2),'.',',-')."</font></td>"; 
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" ></font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Masa Pentakosta</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',($DPersMarapen+$DPersMarapen2),'.',',-')."</font></td>"; 
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" ></font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Unduh Unduh</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',($DPersUnduhUnduh+$DPersUnduhUnduh2),'.',',-')."</font></td>"; 
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" ></font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Persembahan Masa Natal</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',($DPersMaranatal+$DPersMaranatal2),'.',',-')."</font></td>"; 
	echo "<tr bgcolor=\"fcfcfc\">";
	echo "<td ></td><td  bgcolor=\"yellow\" ><font size=\"1\" ><b>4</b></font></td>";
	echo "<td  bgcolor=\"yellow\" align=\"left\" ><font size=\"1\" ><b>Lain Lain Bukan Persembahan</b></font></td><td align=\"right\" ><td></td><td align=\"right\"><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$lainLainBukanPers,'.',',-')."</font></td>";
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" ></font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Sisa Anggaran</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',($SisaAnggaran),'.',',-')."</font></td>"; 
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" ></font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Bunga Bank bersih</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',($BungaBank),'.',',-')."</font></td>"; 
		echo "<tr bgcolor=\"ffffff\">";
		echo "<td><font size=\"1\" ></font></td>";
		echo "<td colspan=\"2\" bgcolor=\"white\" align=\"left\" ><font size=\"1\" >- Lain Lain</font></td>
		<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >".currency(' ',($DLainLainBukanPers),'.',',-')."</font></td>"; 
		echo "<tr bgcolor=\"ffffff\">";


	
// Data Pengeluaran
$sSQL = "select SUM(a.Jumlah) as JumlahTot, d.Kelompok as Kelompok, d.KetKelompok as KetKelompok FROM PengeluaranKasKecil	a
			LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
			LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
			LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
			WHERE d.Kelompok < 3 AND ".$sSQLTanggalBulanKemaren;
		$rsPengeluaranBulanan = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPengeluaranBulanan));	
		
			echo "<tr><td></td><td colspan=\"2\" bgcolor=\"aqua\" align=\"left\" ><font size=\"1\" ><b>C. PENGELUARAN </b></font></td><td></td><td></td>
			<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$JumlahTot,'.',',-')."</font></td>
			</tr>";
			// Data Set
			
			//Kelompok2
	$sSQL0 = "select SUM(a.Jumlah) as Jumlah, d.Kelompok as Kelompok, d.KetKelompok as KetKelompok FROM PengeluaranKasKecil	a
			LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
			LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
			LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
			WHERE d.BidangID > 0 AND ".$sSQLTanggalBulanKemaren." 
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
		echo "<td><font size=\"1\" >".$e."</font></td>";
		echo "<td colspan=\"2\" bgcolor=\"yellow\" align=\"left\" ><font size=\"1\" >".$KetKelompok."</font></td><td align=\"right\" ><font size=\"1\" >Rp.</font></td><td></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$Jumlah,'.',',-')."</font></td>";
	} 
				// Bidang
			$sSQL = "select a.*, SUM(a.Jumlah) as SubTotal , b.*, c.*,c.BidangID as Bidang, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				WHERE d.Kelompok = ".$Kelompok." AND ".$sSQLTanggalBulanKemaren." 
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
				echo "<td><font size=\"1\" >".$e."</font></td>";
				echo "<td colspan=\"2\" bgcolor=\"yellow\" align=\"left\" ><font size=\"1\" >".$NamaBidang."</font></td>
				<td align=\"right\" ><font size=\"1\" >Rp.</font></td><td></td><td align=\"right\"><font size=\"1\" >".currency(' ',$SubTotal,'.',',-')."</font></td>"; 
				echo "<td></td>";
				}
				//echo "<td align=\"right\"><b>".currency(' ',$SubTotal,'.',',-')."</b></td>";
				
				echo "</tr>";
				
				$sSQL1 = "select a.*, SUM(a.Jumlah) as TotalKomisi, b.*, c.*, d.*, e.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				LEFT JOIN MasterAnggaran e ON b.KomisiID = e.KomisiID 
				WHERE c.BidangID=".$Bidang." AND ".$sSQLTanggalBulanKemaren." 
				
				GROUP BY c.KomisiID ORDER BY c.KomisiID";
				
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
				
				
	
				//echo
				// ini
				$sSQL3 = "select a.*, SUM(a.Jumlah) as TotalPerBulan, b.*, c.*, d.*, e.Budget as AnggaranPerKomisi  FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
				LEFT JOIN MasterAnggaran e ON b.KomisiID = e.KomisiID 
				WHERE c.BidangID=".$Bidang." AND b.KomisiID=".$KomisiID." AND ".$sSQLTanggalBulanKemaren." 
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
				echo "<td align=\"right\"><font size=\"1\" >Rp.</font></td><td align=\"right\"><font size=\"1\" >
				<a href=\"PrintViewCekPersembahan.php?Cek=KasKecil&TGL=".$iTGL."&KomisiID=".$KomisiID."&Bul=".$Bul."\" target=\"_blank\"   STYLE=\"TEXT-DECORATION: NONE\">
				".currency(' ',$TotalPerBulan,'.',',-')."</a></font></td>";
				}
				}else
				{
				echo "<td align=\"right\">.</td>";
				}
				
			
				
								
				}

			}
	}
echo "<tr><td></td><td colspan=\"2\" bgcolor=\"aqua\" align=\"left\" ><font size=\"1\" ><b>D. SALDO AKHIR ( D.1 + D.2 + D.3 )</b></font></td>
	<td></td><td></td><td></td><td></td><td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$TotalSaldoAkhir,'.',',-')."</font></td></tr>";
	echo "<tr bgcolor=\"ffffff\"><td><font size=\"1\" ></font></td><td align=\"center\"><font size=\"1\" >1</font></td><td align=\"left\" ><font size=\"1\" >Kas Tunai</font></td>
	<td></td><td></td><td></td><td></td><td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$KasTunaiAkhir,'.',',-')."</font></td><td></td>";
	echo "<tr bgcolor=\"ffffff\"><td><font size=\"1\" ></font></td><td align=\"center\"><font size=\"1\" >2</font></td><td align=\"left\" ><font size=\"1\" >Tabungan Mandiri</font></td>
	<td></td><td></td><td></td><td></td><td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$TabMandiriAkhir,'.',',-')."</font></td><td></td>";
	echo "<tr bgcolor=\"ffffff\"><td><font size=\"1\" ></font></td><td align=\"center\"><font size=\"1\" >3</font></td><td align=\"left\" ><font size=\"1\" >Setoran Dalam Perjalanan</font></td>
	<td></td><td></td><td></td><td></td><td align=\"right\" ><font size=\"1\" >Rp.</font></td><td align=\"right\" ><font size=\"1\" >".currency(' ',$SetoranDalamPerjalananAkhir,'.',',-')."</font></td><td></td>";


	
?>	

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


