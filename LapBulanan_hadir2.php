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
			

function minavgmax ($iKolom, $tahun) {
switch ($iKolom) 
{
    case 1: $judul = "Kehadiran Jemaat LakiLaki";break;
    case 2: $judul = "Kehadiran Jemaat Perempuan";break;
    case 3: $judul = "Kehadiran Jemaat Dewasa Total";break;
    case 4: $judul = "Kehadiran Jemaat Pemuda";break;
    case 5: $judul = "Kehadiran Jemaat Remaja";break;
    case 6: $judul = "Kehadiran Jemaat Anak";break;
    case 7: $judul = "Kehadiran Jemaat Baptis Dewasa";break;
    case 8: $judul = "Kehadiran Jemaat Baptis Anak";break;
    case 9: $judul = "Kehadiran Jemaat SIDI";break;
    case 10: $judul = "Kehadiran Jemaat Pengakuan Dosa";break;
    case 11: $judul = "Kehadiran Jemaat Penerimaan Warga";break;
    case 12: $judul = "Kehadiran Majelis";break;
    case 13: $judul = "Kehadiran Jemaat Kb.Anak JTMY";break;
    case 14: $judul = "Persembahan Jemaat Dewasa";break;
    case 15: $judul = "Persembahan Jemaat Pemuda";break;
    case 16: $judul = "Persembahan Jemaat Remaja";break;
    case 17: $judul = "Persembahan Jemaat Anak";break;
    case 18: $judul = "Persembahan Jemaat Syukur";break;
    case 19: $judul = "Persembahan Jemaat Bulanan";break;
    case 20: $judul = "Persembahan Jemaat Khusus";break;
    case 21: $judul = "Persembahan Jemaat Perpuluhan";break;
    case 22: $judul = "Persembahan Jemaat PPPG";break;
    case 23: $judul = "Persembahan Jemaat Bencana";break;
    case 24: $judul = "Persembahan Jemaat LainLain";break;
    case 25: $judul = "Persembahan Jemaat Anak JTMY";break;
    case 26: $judul = "Jumlah  Amplop Syukur";break;
    case 27: $judul = "Jumlah  Amplop Bulanan";break;
    case 28: $judul = "Jumlah  Amplop Perpuluhan";break;
    case 29: $judul = "Jumlah  Amplop PPPG";break;
    case 30: $judul = "Jumlah  Amplop Bencana";break;
    case 31: $judul = "Jumlah  Amplop LainLain";break;
 }
echo "	<table border=\"0\" width=\"600\" cellspacing=5 cellpadding=0 >";
echo "	<tr>";
echo "	<td><font size=3><b><u>Ringkasan Data " . $judul . " </u></td> ";
echo "	</tr>";
echo "	</table>";

echo "	<table border=\"0\" width=\"800\" cellspacing=5 cellpadding=0 >";
echo "	<tr>";
echo "	<td><font size=2><b>No </b></td> ";
echo "	<td><font size=2><b>Tempat Ibadah </b></td> ";
echo "	<td><font size=2><b>Kegiatan </b></td> ";
echo "	<td><font size=2><b>Jumlah Data </b></td> ";
echo "	<td><font size=2><b>Nilai Terendah </b></td> ";
echo "	<td><font size=2><b>Nilai Rata Rata </b></td> ";
echo "	<td><font size=2><b>Nilai Tertinggi </b></td> ";

echo "	<td ALIGN=right> </td>";
echo "	</tr>";
		$sSQL = "SELECT DISTINCT(t1.event_desc) as TempatIbadah, event_title as Kegiatan,
					count(event_id) as JmlData,
					SUM(t3.evtcnt_countcount) as total,
					Min(t3.evtcnt_countcount) as Terendah,
					Round(AVG(t3.evtcnt_countcount),2) as RataRata,
					Max(t3.evtcnt_countcount) as Tertinggi
					FROM eventcounts_evtcnt AS t3, events_event AS t1, event_types AS t2
					WHERE event_id = t3.evtcnt_eventid
					AND t1.event_type = t2.type_id
					AND t1.event_type =1
					AND YEAR( t1.event_start ) =$tahun
					AND evtcnt_countid =$iKolom
					AND t3.evtcnt_countcount >0
					GROUP BY t1.event_desc";
		$perintah = mysql_query($sSQL);
		$i = 0;
		$total = 0;
		while ($hasilGD=mysql_fetch_array($perintah))
		{
		$i++;
		$total = ($total + $hasilGD[Jiwa]);
						extract($hasilGD);
						//Alternate the row color
		                  $sRowClass = AlternateRowStyle($sRowClass);

echo "	<tr class=" . $sRowClass . ">" ;
echo "	<td><font size=2>" . $i . "</td>";
echo "	<td><font size=2>" . $hasilGD[TempatIbadah] . "</td>";
echo "	<td><font size=2>" . $hasilGD[Kegiatan] . "</td>";
echo "	<td ALIGN=center><font size=2>" . number_format($hasilGD[JmlData],0,",",".") . "</td>";
echo "	<td ALIGN=center><font size=2>" . number_format($hasilGD[Terendah],0,",",".") . "</td>";
echo "	<td ALIGN=center><font size=2>" . number_format($hasilGD[RataRata],2,",",".") . "</td>";
echo "	<td ALIGN=center><font size=2>" . number_format($hasilGD[Tertinggi],0,",",".") . "</td>";

		}

echo "	</table>";

}			

			
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

		<? minavgmax(6,$iTahun);   ?>
		<b><a href="ListEvents.php?Tahun=<?=$iTahun?>" target="_blank">
		Grafik Kehadiran Anak Sekolah Minggu Tahun : <?=$iTahun?> </a>
		</b><br>
		<a href="graph_kehadiran.php?Tahun=<?=$iTahun?>&Kolom=6" width="2048" target="_blank">
 		<?php	echo "<img src=\"graph_kehadiran.php?Tahun=".$iTahun."&Kolom=6&amp;".$refresh." \" width=\"1024\" ><br>" ;	?>
		</a><font size="1"><i>* Catatan: Untuk Memperbesar Gambar,Silahkan Klik Grafik tersebut</i></font>
		<br>
		<br>
		<? minavgmax(17,$iTahun);   ?>
		<b><a href="ListEvents.php?Tahun=<?=$iTahun?>" target="_blank">
		Grafik Persembahan Anak Sekolah Minggu Tahun : <?=$iTahun?> </a>
		</b><br>
		<a href="graph_kehadiran.php?Tahun=<?=$iTahun?>&Kolom=17" width="2048" target="_blank">
 		<?php	echo "<img src=\"graph_kehadiran.php?Tahun=".$iTahun."&Kolom=17&amp;".$refresh." \" width=\"1024\" ><br>" ;	?>
		</a><font size="1"><i>* Catatan: Untuk Memperbesar Gambar,Silahkan Klik Grafik tersebut</i></font>

		<br>
		<br>
		<? minavgmax(13,$iTahun);   ?>
		<b><a href="ListEvents.php?Tahun=<?=$iTahun?>" target="_blank">
		Grafik Kehadiran Anak Sekolah Minggu JTMY Tahun : <?=$iTahun?> </a>
		</b><br>
		<a href="graph_kehadiran.php?Tahun=<?=$iTahun?>&Kolom=13" width="2048" target="_blank">
 		<?php	echo "<img src=\"graph_kehadiran.php?Tahun=".$iTahun."&Kolom=13&amp;".$refresh." \" width=\"1024\" ><br>" ;	?>
		</a><font size="1"><i>* Catatan: Untuk Memperbesar Gambar,Silahkan Klik Grafik tersebut</i></font>
		<br>
		<br>
		<? minavgmax(25,$iTahun);   ?>
		<b><a href="ListEvents.php?Tahun=<?=$iTahun?>" target="_blank">
		Grafik Persembahan Anak Sekolah Minggu JTMY Tahun : <?=$iTahun?> </a>
		</b><br>
		<a href="graph_kehadiran.php?Tahun=<?=$iTahun?>&Kolom=25" width="2048" target="_blank">
 		<?php	echo "<img src=\"graph_kehadiran.php?Tahun=".$iTahun."&Kolom=25&amp;".$refresh." \" width=\"1024\" ><br>" ;	?>
		</a><font size="1"><i>* Catatan: Untuk Memperbesar Gambar,Silahkan Klik Grafik tersebut</i></font>
		<br>

		
 </td>
    </tr>
	</tbody>
	</table>
	
<?php
require "Include/Footer-Short.php";
?>
