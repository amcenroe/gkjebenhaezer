<?php
/*******************************************************************************
*
* filename : BulananReport.php
* last change : 2003-01-29
*
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
******************************************************************************/


$refresh = microtime() ;
// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";

$iTahun = $_GET["Tahun"];

//Print_r ($_SESSION);
		$logvar1 = "Report";
		$logvar2 = "Executive Summary 2";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>Laporan Bulanan - Executive Summary</title><br>
  <title>Kehadiran dan Persembahan</title>

  <STYLE TYPE="text/css">
        P.breakhere {page-break-before: always}
</STYLE>

</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >

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
	<font size="2"><big style="font-family: Arial;">Laporan Bulanan (Executive Sumary)</big><br></font></b>   
	<b style="font-family: Arial;">Bulan : <?php echo date("F, Y"); ?></b></td>

	
    </tr>
  </tbody>
</table>


<table style="width: 1024;  text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2" valign=top>
  <tbody>
    <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
	  	<br><font size="1"><b> Grafik Data yang tersedia : 
		<a href="LapBulanan_hadir.php?Tahun=<?=$iTahun?>" >Dewasa</a> - 
		<a href="LapBulanan_hadir2.php?Tahun=<?=$iTahun?>" >Anak</a> - 
		<a href="LapBulanan_hadir3.php?Tahun=<?=$iTahun?>" >Remaja</a> -
		<a href="LapBulanan_hadir4.php?Tahun=<?=$iTahun?>" >Persembahan</a> -
		<a href="LapBulanan_hadir5.php?Tahun=<?=$iTahun?>" >Amplop</a>
		<br>
		Grafik Tahun : 
		<a href="LapBulanan_hadir.php?Tahun=<?=$iTahun-1?>" ><?=$iTahun-1?></a> - 
		<a href="LapBulanan_hadir.php?Tahun=<?=$iTahun+1?>" ><?=$iTahun+1?></a>
		<br>
		Tabulasi Data Tahun : 
		<a href="ListEvents.php?Tahun=<?=$iTahun-1?>" ><?=$iTahun-1?></a> - 
		<a href="ListEvents.php?Tahun=<?=$iTahun+1?>" ><?=$iTahun+1?></a>
		</font>
		</font>
    	<br>
		<br>
		<b><a href="ListEvents.php?Tahun=<?=$iTahun?>" target="_blank">
		Grafik Kehadiran Majelis Tahun : <?=$iTahun?> </a>
		</b><br>
		<a href="graph_kehadiran.php?Tahun=<?=$iTahun?>&Kolom=12" width="2048" target="_blank">
 		<?php	echo "<img src=\"graph_kehadiran.php?Tahun=".$iTahun."&Kolom=12\" width=\"1024\" ><br>" ;	?>
		</a><font size="1"><i>* Catatan: Untuk Memperbesar Gambar,Silahkan Klik Grafik tersebut</i></font>
		<br>
		
		<b><a href="ListEvents.php?Tahun=<?=$iTahun?>" target="_blank">
		Grafik Kehadiran Jemaat Dewasa Tahun : <?=$iTahun?> </a>
		</b><br>
		<a href="graph_kehadiran.php?Tahun=<?=$iTahun?>&Kolom=3" width="2048" target="_blank">
 		<?php	echo "<img src=\"graph_kehadiran.php?Tahun=".$iTahun."&Kolom=12\" width=\"1024\" ><br>" ;	?>
		</a><font size="1"><i>* Catatan: Untuk Memperbesar Gambar,Silahkan Klik Grafik tersebut</i></font>
		<br>
		
		<b><a href="ListEvents.php?Tahun=<?=$iTahun?>" target="_blank">
		Grafik Persembahan Kantong Jemaat Dewasa Tahun : <?=$iTahun?> </a>
		</b><br>
		<a href="graph_kehadiran.php?Tahun=<?=$iTahun?>&Kolom=3" width="2048" target="_blank">
 		<?php	echo "<img src=\"graph_kehadiran.php?Tahun=".$iTahun."&Kolom=14\" width=\"1024\" ><br>" ;	?>
		</a><font size="1"><i>* Catatan: Untuk Memperbesar Gambar,Silahkan Klik Grafik tersebut</i></font>
		<br>
	
 </td>
    </tr>
	</tbody>
	</table>
	
<?php
require "Include/Footer-Short.php";
?>
