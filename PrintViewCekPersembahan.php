<?php
/*******************************************************************************
 *
 *  filename    : PrintViewCekPersembahan.php
 *  last change : 2003-01-29
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
require "Include/Header-Print.php";
// Get the Gol Darah ID from the querystring
$iKelompok = FilterInput($_GET["Kelompok"]);
$iTGL = FilterInput($_GET["TGL"]);
$iGRP = FilterInput($_GET["GRP"]);
$iBul = FilterInput($_GET["Bul"]);
$iPos = FilterInput($_GET["Pos"]);
$iKomisiID = FilterInput($_GET["KomisiID"]);

if ($iKelompok == '' ){ $FilterKelompok = ""; }else{ $FilterKelompok = " fam_WorkPhone ='$iKelompok' AND ";} 
$iCek = FilterInput($_GET["Cek"]);
$iDetailCek = FilterInput($_GET["DetailCek"]);
	$tanggal=$iTGL;
	$time = strtotime($tanggal);
	$monthago = date("Y-m-d", strtotime("-1 month", $time));
	$nextmonth = date("Y-m-d", strtotime("+1 month", $time));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>Laporan - Detail Transaksi</title>

<STYLE TYPE="text/css">
  		<!--
  		TD{font-family: Arial; font-size: 10pt;}
		--->
        P.breakhere {page-break-before: always}
</STYLE>
</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >

<DIV align=center >



<table
 style="width: 750; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="width: 125px;"><img style="width: 90px; height: 90px;" src="gkj_logo.jpg" border="0"></td>
      <td style="width: 630px;"><b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo $sChurchName;?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);"><b style="font-family: Arial; color: rgb(0, 0, 102);">
	<hr style="width: 100%; height: 2px;">
	<font size="2"><big style="font-family: Arial;">Daftar Transaksi <?php echo $iKelompok ?></big><br></font></b>   
	<b style="font-family: Arial;">Bulan : <? echo date2Ind($tanggal,5); ?></b></td>
    </tr>
  </tbody>
</table>
<br>


<?php 
if ($iCek=='PPPG') {
$sRowClass = "RowColorA";

echo "<table border=\"0\"  width=\"1100\" cellspacing=0 cellpadding=0 >";
echo "	<u><b> </b></u><br>";
echo "	<tr><b><font size=\"1\">";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>No </center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>ID </center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Tanggal</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Kode TI</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pukul</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Kelompok</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>NomorKartu</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>KodeNama</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Bulan</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Jumlah</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pos</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Keterangan</center></font></b></td>";
echo "	</font></b></tr>";

$sSQLTanggal3 =  " AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;

 if ($iDetailCek=='MPH'){

 	$iTGLawalanggaran = date("Y-1-1");
	$iTGLawal = date(date2Ind($iTGL,11)."-1");
	
	$sSQLTanggalBulanIni =  "  Tanggal >= '".date($iTGLawalanggaran)."' AND Tanggal < '".date($iTGLawal)."'  ";

				$sSQL = "SELECT a.*, b.*, c.* FROM PersembahanPPPG a
				LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis 
				LEFT JOIN LokasiTI c ON a.KodeTI = c.KodeTI 
				WHERE a.Pos=".$iPos." AND ".$sSQLTanggalBulanIni." 
				ORDER BY Tanggal, a.KodeTI, Pukul 
				";
		
		
}else {
				$sSQL = "SELECT a.*, b.* ,c.* FROM PersembahanPPPG a
				LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis 
				LEFT JOIN LokasiTI c ON a.KodeTI = c.KodeTI 
				WHERE a.Pos=".$iPos." AND MONTH(a.Tanggal) = ".$iBul." ".$sSQLTanggal3."
				ORDER BY Tanggal, a.KodeTI, Pukul 
				";
}


				// echo $tanggal;
			//	 echo$time;
			//	 echo date(n,strtotime('-1 month', $time));
			//	echo $sSQL;

				$perintah = mysql_query($sSQL);
				$i = 0;
				$TotBulanan=0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{
				$TotBulanan=$TotBulanan+$hasilGD[Bulanan];
					$i++;
					extract($hasilGD);
					//Alternate the row color
                    $sRowClass = AlternateRowStyle($sRowClass);
			
				echo "<tr class=".$sRowClass.">";
				echo "<td align=\"center\" ><font size=\"1\" >".$i."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[PersembahanPPPGID]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".date2Ind($hasilGD[Tanggal],4)."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NamaTI]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Pukul]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Kelompok]."</font></td>";	
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NomorKartu]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[KodeNama]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Bulan1]."-".$hasilGD[Bulan2]."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[Bulanan],'.',',00')."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NamaJenis]."</font></td>";		
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[KetExternal]."</font></td>";					
				echo "</tr>";
				}
				echo "<tr class=".$sRowClass.">";
				echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><td></td><td> Total : </td>";
				echo "<td align=\"right\" ><font size=\"2\" ><b>".currency(' ',$TotBulanan,'.',',00')."</b></font></td>";
				echo "</tr>";
				
			echo "</table>";				
}elseif ($iCek=='PengeluaranPPPG') {
$sRowClass = "RowColorA";

echo "<table border=\"0\"  width=\"1100\" cellspacing=0 cellpadding=0 >";
echo "	<u><b> </b></u><br>";
echo "	<tr><b><font size=\"1\">";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>No </center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Tanggal</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Kode TI</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>DiserahkanKepada</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Keperluan</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Keterangan</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Jumlah</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Ket Tambahan</center></font></b></td>";
echo "	</font></b></tr>";


	 if ($iDetailCek=='MPH'){

 	$iTGLawalanggaran = date("Y-1-1");
	$iTGLawal = date(date2Ind($iTGL,11)."-1");
	$sSQLTanggalBulanIni =  "  Tanggal >= '".date($iTGLawalanggaran)."' AND Tanggal < '".date($iTGLawal)."'  ";

				$sSQL = "SELECT a.*, a.Keterangan as KetTambahan, b.*, c.* FROM PengeluaranPPPG a
				LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis 
				LEFT JOIN LokasiTI c ON a.KodeTI = c.KodeTI 
				WHERE a.Pos=".$iPos." AND ".$sSQLTanggalBulanIni."
				ORDER BY Tanggal, a.KodeTI, Pukul 
				";

}else{
$sSQLTanggal3 =  " AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;

				$sSQL = "SELECT a.*, a.Keterangan as KetTambahan, b.*, c.* FROM PengeluaranPPPG a
				LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis 
				LEFT JOIN LokasiTI c ON a.KodeTI = c.KodeTI 
				WHERE a.Pos=".$iPos." AND MONTH(a.Tanggal) = ".$iBul." ".$sSQLTanggal3."
				ORDER BY Tanggal, a.KodeTI, Pukul 
				";

}
	
				//echo $sSQL;

				$perintah = mysql_query($sSQL);
				$i = 0;
				$TotBulanan=0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{
				$TotBulanan=$TotBulanan+$hasilGD[Jumlah];
					$i++;
					extract($hasilGD);
					//Alternate the row color
                    $sRowClass = AlternateRowStyle($sRowClass);
		
				echo "<tr class=".$sRowClass.">";
				echo "<td align=\"center\" ><font size=\"1\" >".$i."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".date2Ind($hasilGD[Tanggal],4)."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[KodeTI]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[DiserahkanKepada]."</font></td>";	
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Keperluan]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Keterangan]."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[Jumlah],'.',',00')."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[KetTambahan]."</font></td>";		
				echo "</tr>";
				}
				echo "<tr class=".$sRowClass.">";
				echo "<td></td><td></td><td></td><td></td><td></td><td>Total : </td>";
				echo "<td align=\"right\" ><font size=\"2\" ><b>".currency(' ',$TotBulanan,'.',',00')."</b></font></td>";
				echo "</tr>";
				
			echo "</table>";				
}elseif ($iCek=='BendMPH') {
$sRowClass = "RowColorA";

echo "<table border=\"0\"  width=\"1100\" cellspacing=0 cellpadding=0 >";
echo "	<u><b> </b></u><br>";
echo "	<tr><b><font size=\"1\">";

echo "	<td align=\"center\" ><b><font size=\"1\" ><center>
<a title=\"List All By Transaksi\"  href=\"PrintViewCekPersembahan.php?Bul=".$iBul."&Cek=BendMPH&DetailCek=".$iDetailCek."&TGL=".$iTGL." \" STYLE=\"TEXT-DECORATION: NONE\">
No </a></center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>No Transaksi</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>
<a title=\"List By Tanggal\"  href=\"PrintViewCekPersembahan.php?GRP=Tanggal&Bul=".$iBul."&Cek=BendMPH&DetailCek=".$iDetailCek."&TGL=".$iTGL." \" STYLE=\"TEXT-DECORATION: NONE\">
Tanggal</a></center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Nama TI</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>
<a title=\"List By TI \"  href=\"PrintViewCekPersembahan.php?GRP=Pukul&Bul=".$iBul."&Cek=BendMPH&DetailCek=".$iDetailCek."&TGL=".$iTGL." \" STYLE=\"TEXT-DECORATION: NONE\">
Pukul</a></center></font></b></td>";
if ($iDetailCek=='Kantong') {
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>KebDewasa</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>KebAnak</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>KebAnakJTMY</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>KebPraRemaja</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>KebRemaja</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>KebPemuda</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Total Kantong</center></font></b></td>";
}elseif ($iDetailCek=='Khusus') {
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Khusus</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Syukur Baptis</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Perjamuan Kudus</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Masa Paskah</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Masa Pentakosta</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Unduh Unduh</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Masa Natal</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Total</center></font></b></td>";
}

echo "	</font></b></tr>";


$tanggal=$iTGL;
$time = strtotime($tanggal);
if ($iBul>0) {
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".$iBul." AND YEAR(Tanggal) = ".date(Y,$time)  ;
}else{
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".date(n,strtotime('-1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('-1 month', $time))  ;
}


if ($iGRP=='Tanggal'){
 $sSQL = "SELECT concat(count(Persembahan_ID),' data') as Persembahan_ID, Tanggal, 
   SUM(KebDewasa) as KebDewasa, SUM(KebAnak) as KebAnak, SUM(KebAnakJTMY) as KebAnakJTMY, 
   SUM(KebPraRemaja) as KebPraRemaja,  SUM(KebRemaja) as KebRemaja,  SUM(KebPemuda) as KebPemuda, 
   SUM(KebDewasa+ KebAnak+ KebAnakJTMY+KebPraRemaja+KebRemaja+KebPemuda) AS PersKantong ,  
   SUM(Khusus) as Khusus, SUM(SyukurBaptis) as SyukurBaptis, SUM(KhususPerjamuan) as KhususPerjamuan, SUM(Marapas) as Marapas, SUM(Marapen) as Marapen, SUM(Unduh) as Unduh, SUM(Maranatal) as Maranatal, 
	SUM(Khusus+SyukurBaptis+KhususPerjamuan+Marapas+Marapen+Unduh+Maranatal) as PersKhusus
FROM Persembahangkjbekti a
LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
WHERE ".$sSQLTanggalBulanKemaren. " GROUP BY Tanggal ORDER BY Tanggal  " ;
}else if ($iGRP=='Pukul'){
 $sSQL = "SELECT concat(count(Persembahan_ID),' data') as Persembahan_ID,  Pukul, b.NamaTI , 
   SUM(KebDewasa) as KebDewasa, SUM(KebAnak) as KebAnak, SUM(KebAnakJTMY) as KebAnakJTMY, 
   SUM(KebPraRemaja) as KebPraRemaja,  SUM(KebRemaja) as KebRemaja,  SUM(KebPemuda) as KebPemuda, 
   SUM(KebDewasa+ KebAnak+ KebAnakJTMY+KebRemaja) AS PersKantong ,  
   SUM(Khusus) as Khusus, SUM(SyukurBaptis) as SyukurBaptis, SUM(KhususPerjamuan) as KhususPerjamuan, SUM(Marapas) as Marapas, SUM(Marapen) as Marapen, SUM(Unduh) as Unduh, SUM(Maranatal) as Maranatal, 
	SUM(Khusus+SyukurBaptis+KhususPerjamuan+Marapas+Marapen+Unduh+Maranatal) as PersKhusus
FROM Persembahangkjbekti a
LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
WHERE ".$sSQLTanggalBulanKemaren. " GROUP BY Pukul ORDER BY a.KodeTI, Pukul  " ;
}else{
// Data Persembahan Kantong Mingguan
 $sSQL = "SELECT Persembahan_ID, Tanggal, Pukul, b.NamaTI , 
   KebDewasa, KebAnak, KebPraRemaja, KebRemaja, KebPemuda, 
   (KebDewasa+ KebAnak+ KebPraRemaja+ KebRemaja+ KebPemuda) AS PersKantong ,  
   Khusus, SyukurBaptis, KhususPerjamuan, Marapas, Marapen, Unduh, Maranatal, 
	(Khusus+SyukurBaptis+KhususPerjamuan+Marapas+Marapen+Unduh+Maranatal) as PersKhusus
FROM Persembahangkjbekti a
LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
WHERE ".$sSQLTanggalBulanKemaren. " ORDER BY Tanggal, a.KodeTI, Pukul 
" ;

// Data Persembahan Kantong Khusus 
// $sSQL1 = "SELECT SUM(Persembahan) as PersembahanKhusus FROM PersembahanKhususgkjbekti a
//WHERE ".$sSQLTanggalBulanKemaren. " ORDER BY Tanggal, a.KodeTI, Pukul 
//" ;
							
}

		$rsPerTI = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPerTI));
//echo $sSQL;

				$perintah = mysql_query($sSQL);
				$i = 0;
				$TotBulanan=0;
				$TotKhusus=0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{
				$TotBulanan=$TotBulanan+$hasilGD[PersKantong];
				$TotKhusus=$TotKhusus+$hasilGD[PersKhusus];
					$i++;
					extract($hasilGD);
					//Alternate the row color
                    $sRowClass = AlternateRowStyle($sRowClass);
				
				echo "<tr class=".$sRowClass.">";
				echo "<td align=\"center\" ><font size=\"1\" >".$i."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Persembahan_ID]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".date2Ind($hasilGD[Tanggal],4)."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NamaTI]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Pukul]."</font></td>";	
				if ($iDetailCek=='Kantong') {
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[KebDewasa],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[KebAnak],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[KebAnakJTMY],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[KebPraRemaja],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[KebRemaja],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[KebPemuda],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[PersKantong],'.',',00')."</font></td>";
				}else if ($iDetailCek=='Khusus') {
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[Khusus],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[SyukurBaptis],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[KhususPerjamuan],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[Marapas],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[Marapen],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[Unduh],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[Maranatal],'.',',00')."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[PersKhusus],'.',',00')."</font></td>";
				}
				
				echo "</tr>";
				}
				//Persembahan Anak JTMY
				if ($iDetailCek=='Kantong') {
				
					if ($iGRP=='Tanggal'){
						$sSQL = "SELECT concat(count(Persembahan_ID),' data') as Persembahan_ID, Tanggal, Persembahan as KebAnakJTMY, Persembahan as PersKantong 
								FROM PersembahanAnakgkjbekti a
								LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
								WHERE a.KodeTI=10 AND ".$sSQLTanggalBulanKemaren. " GROUP BY Tanggal ORDER BY Tanggal  " ;
						}else if ($iGRP=='Pukul'){
						$sSQL = "SELECT concat(count(Persembahan_ID),' data') as Persembahan_ID,  Pukul, b.NamaTI , Persembahan as KebAnakJTMY, Persembahan as PersKantong 
								FROM PersembahanAnakgkjbekti a
								LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
								WHERE a.KodeTI=10 AND ".$sSQLTanggalBulanKemaren. " GROUP BY Pukul ORDER BY a.KodeTI, Pukul  " ;
						}else{
						// Data Persembahan Mingguan
						$sSQL = "SELECT Persembahan_ID, Tanggal, Pukul, b.NamaTI ,Persembahan as  KebAnakJTMY, Persembahan as PersKantong
								FROM PersembahanAnakgkjbekti a
									LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
								WHERE a.KodeTI=10 AND ".$sSQLTanggalBulanKemaren. " ORDER BY Tanggal, a.KodeTI, Pukul 
								" ;
						}	
						$rsPerTI = RunQuery($sSQL);
						extract(mysql_fetch_array($rsPerTI));
						$perintah = mysql_query($sSQL);
								while ($hasilGD=mysql_fetch_array($perintah))
								{
								$i++;
								extract($hasilGD);
								//Alternate the row color
								$sRowClass = AlternateRowStyle($sRowClass);
								$TotBulanan=$TotBulanan+$hasilGD[PersKantong];
								echo "<tr class=".$sRowClass.">";
								echo "<td align=\"center\" ><font size=\"1\" >".$i."</font></td>";
								echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Persembahan_ID]."</font></td>";
								echo "<td align=\"center\" ><font size=\"1\" >".date2Ind($hasilGD[Tanggal],4)."</font></td>";
								echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NamaTI]."</font></td>";
								echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Pukul]."</font></td>";	
								echo "<td align=\"right\" ><font size=\"1\" ></font></td>";
								echo "<td align=\"right\" ><font size=\"1\" ></font></td>";
								echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[KebAnakJTMY],'.',',00')."</font></td>";
								echo "<td align=\"right\" ><font size=\"1\" ></font></td>";
								echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[PersKantong],'.',',00')."</font></td>";
								echo "</tr>";
								}

				
				}
				echo "<tr class=".$sRowClass.">";
				if ($iDetailCek=='Kantong') {
				echo "<td colspan=\"8\" align=\"center\" >Total Persembahan Kantong : </td>";
				$TotBulananKantong=$TotBulanan;
				echo "<td align=\"right\" ><font size=\"2\" ><b>".currency(' ',$TotBulanan,'.',',00')."</b></font></td>";
				}else if ($iDetailCek=='Khusus') {
				echo "<td colspan=\"11\" align=\"center\" ><font size=\"2\" >Persembahan Khusus :<b>".currency(' ',$TotKhusus,'.',',00')."</b></font></td>";
				}
				echo "</tr>";
				
			echo "</table>";	

if ($iDetailCek=='Khusus') {
echo "<table border=\"0\"  width=\"1100\" cellspacing=0 cellpadding=0 >";
echo "	<u><b> </b></u><br>";
echo "	<tr><b><font size=\"1\">";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>No </center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Tanggal</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Nama TI</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pukul</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Acara</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Persembahan</center></font></b></td>";


echo "	</font></b></tr>";

// Data Persembahan Khusus 
$sSQL = "SELECT Tanggal, Pukul, b.NamaTI , Persembahan as PersembahanKhusus , Nas as Acara
		FROM PersembahanKhususgkjbekti a
		LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
		WHERE ".$sSQLTanggalBulanKemaren. " ORDER BY Tanggal, a.KodeTI, Pukul " ;
		$rsKhusus = RunQuery($sSQL);
		extract(mysql_fetch_array($rsKhusus));
	//	echo $sSQL;
				$perintah = mysql_query($sSQL);
				$i = 0;
				$TotBulanan=0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{
				$TotBulananK=$TotBulananK+$hasilGD[PersembahanKhusus];
					$i++;
					extract($hasilGD);
					//Alternate the row color
                    $sRowClass = AlternateRowStyle($sRowClass);
		
				echo "<tr class=".$sRowClass.">";
				echo "<td align=\"center\" ><font size=\"1\" >".$i."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".date2Ind($hasilGD[Tanggal],4)."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NamaTI]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Pukul]."</font></td>";	
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Acara]."</font></td>";	
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[PersembahanKhusus],'.',',00')."</font></td>";
				echo "</tr>";
				}
				echo "<tr class=".$sRowClass.">";
				echo "<td colspan=\"4\" align=\"center\" >Total Persembahan Khusus : </td>";
				echo "<td align=\"right\" ><font size=\"2\" ><b>".currency(' ',$TotBulananK,'.',',00')."</b></font></td>";
				echo "</tr>";
				echo "<tr><td colspan=\"5\" align=\"center\" ><hr></td></tr>";
				echo "<tr class=".$sRowClass.">";
				echo "<td colspan=\"4\" align=\"center\" ><b>Total Persembahan Khusus = Persembahan Bulanan Khusus + Persembahan Khusus = 
				".currency(' ',$TotKhusus,'.',',00')." + ".currency(' ',$TotBulananK,'.',',00')." = ".currency(' ',$TotKhusus+$TotBulananK,'.',',00')."</b></b></td>";
				
				echo "</tr>";
			echo "</table>";
	}		
}elseif ($iCek=='BendMPHSyukur') {
$sRowClass = "RowColorA";
echo "<table border=\"0\"  width=\"700\" cellspacing=0 cellpadding=0 >";
echo "	<u><b> </b></u><br>";
echo "	<tr><b><font size=\"1\">";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>
<a title=\"List All By Transaksi\"  href=\"PrintViewCekPersembahan.php?Bul=".$iBul."&Cek=BendMPHSyukur&DetailCek=Kantong&TGL=".$iTGL." \" STYLE=\"TEXT-DECORATION: NONE\">
No </a></center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>No Transaksi</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>
<a title=\"Group By Tanggal\"  href=\"PrintViewCekPersembahan.php?GRP=Tanggal&Bul=".$iBul."&Cek=BendMPHSyukur&DetailCek=Kantong&TGL=".$iTGL." \" STYLE=\"TEXT-DECORATION: NONE\">
Tanggal</a></center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Nama TI</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>
<a title=\"Group By Pukul\"  href=\"PrintViewCekPersembahan.php?GRP=Pukul&Bul=".$iBul."&Cek=BendMPHSyukur&DetailCek=Kantong&TGL=".$iTGL." \" STYLE=\"TEXT-DECORATION: NONE\">
Pukul</a></center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pers. Syukur</center></font></b></td>";
echo "	</font></b></tr>";
$tanggal=$iTGL;
$time = strtotime($tanggal);

if ($iBul>0) {
//$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".$iBul." AND YEAR(Tanggal) = ".date(Y,strtotime('-1 month', $time))  ;
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".$iBul." AND YEAR(Tanggal) = ".date(Y,$time)  ;;
}else{
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".date(n,strtotime('-1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('-1 month', $time))  ;
}

if ($iGRP=='Tanggal'){
 $sSQL = "SELECT concat(count(Persembahan_ID),' data') as Persembahan_ID, Tanggal, Pukul, b.NamaTI , 
   SUM(Syukur) AS PersSyukur  
FROM Persembahangkjbekti a
LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
WHERE ".$sSQLTanggalBulanKemaren. " GROUP BY Tanggal ORDER BY Tanggal, a.KodeTI, Pukul " ;
}else if ($iGRP=='Pukul'){
 $sSQL = "SELECT concat(count(Persembahan_ID),' data') as Persembahan_ID, Tanggal, Pukul, b.NamaTI , 
   SUM(Syukur) AS PersSyukur  
FROM Persembahangkjbekti a
LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
WHERE ".$sSQLTanggalBulanKemaren. " GROUP BY Pukul ORDER BY a.KodeTI, Pukul " ;
}else{
// Data Persembahan Mingguan
 $sSQL = "SELECT Persembahan_ID, Tanggal, Pukul, b.NamaTI , 
   Syukur AS PersSyukur  
FROM Persembahangkjbekti a
LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
WHERE ".$sSQLTanggalBulanKemaren. " ORDER BY Tanggal, a.KodeTI, Pukul " ;
}
		$rsPerTI = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPerTI));
//echo $sSQL;
				$perintah = mysql_query($sSQL);
				$i = 0;
				$TotBulanan=0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{
				$TotBulanan=$TotBulanan+$hasilGD[PersSyukur];
					$i++;
					extract($hasilGD);
					//Alternate the row color
                    $sRowClass = AlternateRowStyle($sRowClass);		
				echo "<tr class=".$sRowClass.">";
				echo "<td align=\"center\" ><font size=\"1\" >".$i."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Persembahan_ID]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".date2Ind($hasilGD[Tanggal],4)."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NamaTI]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Pukul]."</font></td>";	
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[PersSyukur],'.',',00')."</font></td>";		
				echo "</tr>";
				}
				echo "<tr class=".$sRowClass.">";
				echo "<td colspan=\"4\" align=\"center\" >Total Persembahan Syukur : </td>";
				$TotBulananKantong=$TotBulanan;
				echo "<td align=\"right\" ><font size=\"2\" ><b>".currency(' ',$TotBulanan,'.',',00')."</b></font></td>";
				echo "</tr>";			
			echo "</table>";		
}elseif ($iCek=='BendMPHBulanan') {
$sRowClass = "RowColorA";
echo "<table border=\"0\"  width=\"700\" cellspacing=0 cellpadding=0 >";
echo "	<u><b> </b></u><br>";
echo "	<tr><b><font size=\"1\">";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>
<a title=\"List By Tanggal\"  href=\"PrintViewCekPersembahan.php?Bul=".$iBul."&Cek=BendMPHBulanan&DetailCek=".$iDetailCek."&TGL=".$iTGL." \" STYLE=\"TEXT-DECORATION: NONE\">
No </center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>No Transaksi</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>
<a title=\"Group By Tanggal\"  href=\"PrintViewCekPersembahan.php?GRP=Tanggal&Bul=".$iBul."&Cek=BendMPHBulanan&DetailCek=".$iDetailCek."&TGL=".$iTGL." \" STYLE=\"TEXT-DECORATION: NONE\">
Tanggal</a></center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Nama TI</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>
<a title=\"Group By TI dan Pukul \"  href=\"PrintViewCekPersembahan.php?GRP=Pukul&Bul=".$iBul."&Cek=BendMPHBulanan&DetailCek=".$iDetailCek."&TGL=".$iTGL." \"   STYLE=\"TEXT-DECORATION: NONE\">
Pukul</a></center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>
<a title=\"Group By Kelompok\" href=\"PrintViewCekPersembahan.php?GRP=Kelompok&Bul=".$iBul."&Cek=BendMPHBulanan&DetailCek=".$iDetailCek."&TGL=".$iTGL." \"   STYLE=\"TEXT-DECORATION: NONE\">
Kelompok</a></center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Nomor Kartu</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Kode Nama</center></font></b></td>";
if ($iDetailCek=='Bulanan') {
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pers. Bulanan</center></font></b></td>";
}else if ($iDetailCek=='Pengembangan') {
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pers. Dana Pengembangan</center></font></b></td>";
}if ($iDetailCek=='ULK') {
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pers. ULK</center></font></b></td>";
}


echo "	</font></b></tr>";
$tanggal=$iTGL;
$time = strtotime($tanggal);

if ($iBul>0) {
//$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".$iBul." AND YEAR(Tanggal) = ".date(Y,strtotime('-1 month', $time))  ;
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".$iBul." AND YEAR(Tanggal) = ".date(Y,$time)  ;
}else{
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".date(n,strtotime('-1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('-1 month', $time))  ;
}



if ($iGRP=='Kelompok'){
$sSQL = "SELECT CONCAT(COUNT(PersembahanBulananID),' kartu') as PersembahanBulananID, 
		 Kelompok,
		SUM(Bulanan) as PersBulanan, SUM(Syukur) as PersDanaPengembangan, SUM(ULK) as PersULK 
		FROM PersembahanBulanan a
		LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
		WHERE ".$sSQLTanggalBulanKemaren. " GROUP BY Kelompok ORDER BY Kelompok " ;
}else if ($iGRP=='Pukul'){
$sSQL = "SELECT CONCAT(COUNT(PersembahanBulananID),' kartu') as PersembahanBulananID, 
		Tanggal, Pukul, b.NamaTI , 
		SUM(Bulanan) as PersBulanan, SUM(Syukur) as PersDanaPengembangan, SUM(ULK) as PersULK 
		FROM PersembahanBulanan a
		LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
		WHERE ".$sSQLTanggalBulanKemaren. " GROUP BY Pukul ORDER BY a.KodeTI, Pukul " ;
}else if ($iGRP=='Tanggal'){
$sSQL = "SELECT CONCAT(COUNT(PersembahanBulananID),' kartu') as PersembahanBulananID, 
		Tanggal, 
		SUM(Bulanan) as PersBulanan, SUM(Syukur) as PersDanaPengembangan, SUM(ULK) as PersULK 
		FROM PersembahanBulanan a
		LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
		WHERE ".$sSQLTanggalBulanKemaren. " GROUP BY Tanggal ORDER BY Tanggal  " ;
}
else {
// Data Persembahan Mingguan
$sSQL = "SELECT PersembahanBulananID, Tanggal, Pukul, b.NamaTI , Kelompok, NomorKartu, KodeNama, 
		Bulanan as PersBulanan, Syukur as PersDanaPengembangan, ULK as PersULK , per_firstname as NamaPanjang 
		FROM PersembahanBulanan a
		LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
		LEFT JOIN person_per c ON a.NomorKartu = c.per_ID
		WHERE ".$sSQLTanggalBulanKemaren. " ORDER BY Tanggal, a.KodeTI, Pukul, Kelompok, NomorKartu " ;
}		
		$rsBulanan = RunQuery($sSQL);
		extract(mysql_fetch_array($rsBulanan));
//echo $sSQL;
				$perintah = mysql_query($sSQL);
				$i = 0;
				$TotBulanan=0;
				$TotDP=0;
				$TotULK=0;
				
				while ($hasilGD=mysql_fetch_array($perintah))
				{
				$TotBulanan=$TotBulanan+$hasilGD[PersBulanan];
				$TotDP=$TotDP+$hasilGD[PersDanaPengembangan];
				$TotULK=$TotULK+$hasilGD[PersULK];
				
					$i++;
					extract($hasilGD);
					//Alternate the row color
                    $sRowClass = AlternateRowStyle($sRowClass);		
				echo "<tr class=".$sRowClass.">";
				echo "<td align=\"center\" ><font size=\"1\" >".$i."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[PersembahanBulananID]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".date2Ind($hasilGD[Tanggal],4)."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NamaTI]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Pukul]."</font></td>";	
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Kelompok]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NomorKartu]."</font></td>";
				if ($hasilGD[KodeNama]==""){
										//echo "<br>".KodeNamaWarga($NamaWarga);
					$NamaWarga = $hasilGD[NamaPanjang];	
					//echo "<td align=\"center\" ><font size=\"1\" >".KodeNamaWarga($hasilGD[$NamaWarga])."</font></td>";
					echo "<td align=\"center\" ><font size=\"1\" >".KodeNamaWarga($NamaWarga)."</font></td>";
					//echo "<td align=\"center\" ><font size=\"1\" >".$NamaWarga."</font></td>";

				}else{
					echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[KodeNama]."</font></td>";
				}
				
				if ($iDetailCek=='Bulanan') {
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[PersBulanan],'.',',00')."</font></td>";	
				}else if ($iDetailCek=='Pengembangan') {
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[PersDanaPengembangan],'.',',00')."</font></td>";	
				}if ($iDetailCek=='ULK') {
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[PersULK],'.',',00')."</font></td>";	
				}
				echo "</tr>";
				}
				echo "<tr class=".$sRowClass.">";
				echo "<td colspan=\"8\" align=\"center\" >Total Persembahan : </td>";
				if ($iDetailCek=='Bulanan') {
				echo "<td align=\"right\" ><font size=\"2\" >Bulanan: <b>".currency(' ',$TotBulanan,'.',',00')."</b></font></td>";
				}else if ($iDetailCek=='Pengembangan') {
				echo "<td align=\"right\" ><font size=\"2\" >Dana Peng: <b>".currency(' ',$TotDP,'.',',00')."</b></font></td>";
				}if ($iDetailCek=='ULK') {
				echo "<td align=\"right\" ><font size=\"2\" >ULK :<b>".currency(' ',$TotULK,'.',',00')."</b></font></td>";
				}
			echo "</tr>";	
			echo "<tr class=".$sRowClass."><td colspan=\"8\" align=\"center\" >Jumlah Kartu/Amplop : </td><td align=\"right\" ><font size=\"2\" ><b>".$i."</b></font></td></tr>";
			
			echo "</table>";		
}elseif ($iCek=='BendMPHLain') {
$sRowClass = "RowColorA";
echo "<table border=\"0\"  width=\"700\" cellspacing=0 cellpadding=0 >";
echo "	<u><b> </b></u><br>";
echo "	<tr><b><font size=\"1\">";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>No </center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Tanggal</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Nama TI</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pukul</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pers. Pink</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pers. Lain Lain</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Total </center></font></b></td>";
echo "	</font></b></tr>";
$tanggal=$iTGL;
$time = strtotime($tanggal);
if ($iBul>0) {
//$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".$iBul." AND YEAR(Tanggal) = ".date(Y,strtotime('-1 month', $time))  ;
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".$iBul." AND YEAR(Tanggal) = ".date(Y,$time)  ;
}else{
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".date(n,strtotime('-1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('-1 month', $time))  ;
}

// Data Persembahan Mingguan
 $sSQL = "SELECT Persembahan_ID,Tanggal, Pukul, b.NamaTI , 
   Pink, LainLain, (Pink+LainLain) AS TotalLainLain  
FROM Persembahangkjbekti a
LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
WHERE ".$sSQLTanggalBulanKemaren. " ORDER BY Tanggal, a.KodeTI, Pukul " ;
		$rsPerTI = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPerTI));
//echo $sSQL;
				$perintah = mysql_query($sSQL);
				$i = 0;
				$TotBulanan=0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{
				$TotBulanan=$TotBulanan+$hasilGD[TotalLainLain];
					$i++;
					extract($hasilGD);
					//Alternate the row color
                    $sRowClass = AlternateRowStyle($sRowClass);		
				echo "<tr class=".$sRowClass.">";
				echo "<td align=\"center\" ><font size=\"1\" ><a href=\"PersembahanView.php?Persembahan_ID=".$hasilGD[Persembahan_ID]."\" target=_blank>".$i."</a></font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".date2Ind($hasilGD[Tanggal],4)."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NamaTI]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Pukul]."</font></td>";	
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[Pink],'.',',00')."</font></td>";	
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[LainLain],'.',',00')."</font></td>";	
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[Total],'.',',00')."</font></td>";
				echo "</tr>";
				}
				echo "<tr class=".$sRowClass.">";
				echo "<td colspan=\"6\" align=\"center\" >Total Pink dan Lain Lain : </td>";
				$TotBulananKantong=$TotBulanan;
				echo "<td align=\"right\" ><font size=\"2\" ><b>".currency(' ',$TotBulanan,'.',',00')."</b></font></td>";
				echo "</tr>";	
			echo "</table>";	
//Kontribusi
echo "<table border=\"0\"  width=\"700\" cellspacing=0 cellpadding=0 >";
echo "	<u><b> </b></u><br>";
echo "	<tr><b><font size=\"1\">";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>No </center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Tanggal</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Nama TI</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pukul</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center></center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center></center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pers. Kontribusi</center></font></b></td>";
echo "	</font></b></tr>";
$sSQL = "SELECT Tanggal, Pukul, b.NamaTI , 
   Persembahan as PersKontribusi 
FROM PersembahanKontribusigkjbekti a
LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
WHERE ".$sSQLTanggalBulanKemaren. " ORDER BY Tanggal, a.KodeTI, Pukul " ;
		$rsPerTI = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPerTI));
//echo $sSQL;
				$perintah = mysql_query($sSQL);
				$i = 0;
				$TotBulanan=0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{
				$TotBulanan=$TotBulanan+$hasilGD[PersKontribusi];
					$i++;
					extract($hasilGD);
					//Alternate the row color
                    $sRowClass = AlternateRowStyle($sRowClass);		
				echo "<tr class=".$sRowClass.">";
				echo "<td align=\"center\" ><font size=\"1\" >".$i."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".date2Ind($hasilGD[Tanggal],4)."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NamaTI]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Pukul]."</font></td>";	
				echo "<td align=\"right\" ><font size=\"1\" ></font></td>";	
				echo "<td align=\"right\" ><font size=\"1\" ></font></td>";	
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[PersKontribusi],'.',',00')."</font></td>";
				echo "</tr>";
				}
				echo "<tr class=".$sRowClass.">";
				echo "<td colspan=\"6\" align=\"center\" >Total Pink dan Lain Lain : </td>";
				$TotBulananKantong=$TotBulanan;
				echo "<td align=\"right\" ><font size=\"2\" ><b>".currency(' ',$TotBulanan,'.',',00')."</b></font></td>";
				echo "</tr>";	
			echo "</table>";
			
}elseif ($iCek=='KasKecil') {
$sRowClass = "RowColorA";

echo "<table border=\"0\"  width=\"1100\" cellspacing=0 cellpadding=0 >";
echo "	<u><b> </b></u><br>";
echo "	<tr><b><font size=\"1\">";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>No </center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Tanggal</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>No Voucher</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Pos Anggaran</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Nama Bidang</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Nama Komisi</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Deskripsi Pengeluaran</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Keterangan</center></font></b></td>";
echo "	<td align=\"center\" ><b><font size=\"1\" ><center>Jumlah</center></font></b></td>";
echo "	</font></b></tr>";



$sSQLTanggal3 =  " AND YEAR(a.Tanggal) = ".date(Y,strtotime($iTGL)) ;
$sSQL = " SELECT a . * , b . * , c . * , d . *
FROM PengeluaranKasKecil a
LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
WHERE c.KomisiID=".$iKomisiID." AND MONTH(a.Tanggal) = ".$iBul." ".$sSQLTanggal3."
ORDER BY Tanggal, d.NamaBidang, c.NamaKomisi";



	
			//	echo $sSQL;

				$perintah = mysql_query($sSQL);
				$i = 0;
				$TotBulanan=0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{
				$TotBulanan=$TotBulanan+$hasilGD[Jumlah];
					$i++;
					extract($hasilGD);
					//Alternate the row color
                    $sRowClass = AlternateRowStyle($sRowClass);
		
				echo "<tr class=".$sRowClass.">";
				echo "<td align=\"center\" ><font size=\"1\" >".$i."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".date2Ind($hasilGD[Tanggal],4)."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[PengeluaranKasKecilID]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NamaPosAnggaran]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NamaBidang]."</font></td>";	
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[NamaKomisi]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[DeskripsiKas]."</font></td>";
				echo "<td align=\"center\" ><font size=\"1\" >".$hasilGD[Keterangan]."</font></td>";
				echo "<td align=\"right\" ><font size=\"1\" >".currency(' ',$hasilGD[Jumlah],'.',',00')."</font></td>";
	
				echo "</tr>";
				}
				echo "<tr class=".$sRowClass.">";
				echo "<td></td><td></td><td></td><td></td><td></td><td>Total : </td>";
				echo "<td align=\"right\" ><font size=\"2\" ><b>".currency(' ',$TotBulanan,'.',',00')."</b></font></td>";
				echo "</tr>";
				
			echo "</table>";				
}



?>

<?php
require "Include/Footer-Short.php";
?>
