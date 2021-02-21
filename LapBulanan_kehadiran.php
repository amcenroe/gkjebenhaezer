<?php
/*******************************************************************************
*
* filename : LapBulanan_kehadiran.php
* last change : 2003-01-29
*
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
  *  2012 Erwin Pratama for GKJ Bekasi Timur
******************************************************************************/


$refresh = microtime() ;
// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";

$iTahun = $_GET["Tahun"];
$iJenis = $_GET["Jenis"];

if ($iTahun == ""){$iTahun =date("Y");}

//Print_r ($_SESSION);
		$logvar1 = "Report";
		$logvar2 = "Executive Summary Kehadiran";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

			
function minavgmax ($iKolom, $tahun) {
switch ($iKolom) 
{
     case 1: $judul = "Kehadiran Jemaat LakiLaki";$stat = "Pria";break;
    case 2: $judul = "Kehadiran Jemaat Perempuan";$stat = "Wanita";break;
    case 3: $judul = "Kehadiran Jemaat Dewasa Total";$stat = "Pria+Wanita";break;
    case 4: $judul = "Kehadiran Jemaat Pemuda";$stat = "Pria";break;
    case 5: $judul = "Kehadiran Jemaat Remaja";$stat = "Pria";break;
    case 6: $judul = "Kehadiran Jemaat Anak";$stat = "Pria";break;
    case 7: $judul = "Kehadiran Jemaat Baptis Dewasa";$stat = "Pria";break;
    case 8: $judul = "Kehadiran Jemaat Baptis Anak";$stat = "Pria";break;
    case 9: $judul = "Kehadiran Jemaat SIDI";$stat = "Pria";break;
    case 10: $judul = "Kehadiran Jemaat Pengakuan Dosa";$stat = "Pria";break;
    case 11: $judul = "Kehadiran Jemaat Penerimaan Warga";$stat = "Pria";break;
    case 12: $judul = "Kehadiran Majelis";
			$stat = "(if(Majelis1<>'0',1,0)+if(Majelis2<>'0',1,0)+if(Majelis3<>'0',1,0)+if(Majelis4<>'0',1,0)+if(Majelis5<>'0',1,0)+if(Majelis6<>'0',1,0)+if(Majelis7<>'0',1,0)
					+if(Majelis8<>'0',1,0)+if(Majelis9<>'0',1,0)+if(Majelis10<>'0',1,0)+if(Majelis11<>'0',1,0)+if(Majelis12<>'0',1,0)+if(Majelis13<>'0',1,0)+if(Majelis14<>'0',1,0)) 
			";
			break;
    case 13: $judul = "Kehadiran Jemaat Kb.Anak JTMY";$stat = "Pria";break;
    case 14: $judul = "Persembahan Jemaat Dewasa";$stat = "KebDewasa";break;
    case 15: $judul = "Persembahan Jemaat Pemuda";$stat = "stat";break;
    case 16: $judul = "Persembahan Jemaat Remaja";$stat = "KebRemaja";break;
    case 17: $judul = "Persembahan Jemaat Anak";$stat = "KebAnak";break;
    case 18: $judul = "Persembahan Jemaat Syukur";$stat = "Syukur";break;
    case 19: $judul = "Persembahan Jemaat Bulanan";$stat = "Bulanan";break;
    case 20: $judul = "Persembahan Jemaat Khusus";$stat = "Khusus";break;
    case 21: $judul = "Persembahan Jemaat Perpuluhan";$stat = "stat";break;
    case 22: $judul = "Persembahan Jemaat PPPG";$stat = "stat";break;
    case 23: $judul = "Persembahan Jemaat Bencana";$stat = "stat";break;
    case 24: $judul = "Persembahan Jemaat LainLain";$stat = "LainLain";break;
    case 25: $judul = "Persembahan Jemaat Anak JTMY";$stat = "KebAnakJTMY";break;
    case 26: $judul = "Jumlah  Amplop Syukur";$stat = "stat";break;
    case 27: $judul = "Jumlah  Amplop Bulanan";$stat = "stat";break;
    case 28: $judul = "Jumlah  Amplop Perpuluhan";$stat = "stat";break;
    case 29: $judul = "Jumlah  Amplop PPPG";$stat = "stat";break;
    case 30: $judul = "Jumlah  Amplop Pink";$stat = "PinkAmplop";break;
    case 31: $judul = "Jumlah  Amplop LainLain";$stat = "LainLainAmplop";break;
	
	case 40: $judul = "Jumlah  Persembahan Total";
	$stat = "KebDewasa+KebAnak+KebAnakJTMY+KebRemaja+Syukur+Bulanan+Khusus+
			SyukurBaptis+KhususPerjamuan+Marapas+Marapen+Unduh+Maranatal+Pink+ 
			LainLain+LainLainAmplop";break;
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
echo "	<td><font size=2><b>Waktu </b></td> ";
echo "	<td><font size=2><b>Jumlah Data </b></td> ";
echo "	<td><font size=2><b>Nilai Terendah </b></td> ";
echo "	<td><font size=2><b>Nilai Rata Rata </b></td> ";
echo "	<td><font size=2><b>Nilai Tertinggi </b></td> ";

echo "	<td ALIGN=right> </td>";
echo "	</tr>";
		

		$sSQL = "SELECT a.KodeTI , b.NamaTI as TempatIbadah, a.Pukul , 
				count(Persembahan_ID) as JmlData, 
				SUM($stat) as total,
				MIN(NULLIF($stat, 0)) as Terendah,
				MAX($stat) as Tertinggi, 
				ROUND(AVG(NULLIF($stat, 0)),2) as RataRata
				FROM Persembahangkjbekti a 
				LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
				WHERE a.KodeTI > 0 AND YEAR(Tanggal) =$tahun AND a.Pukul > 0 
				GROUP BY Pukul
				ORDER BY a.KodeTI, Pukul
					";
				 	
		
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
echo "	<td><font size=2>" . $hasilGD[Pukul] . "</td>";
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
  <title>Laporan Persembahan dan Kehadiran - Executive Summary</title><br>
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
	<font size="2"><big style="font-family: Arial;">Laporan Persembahan dan kehadiran (Executive Sumary)</big><br></font></b>   
	<b style="font-family: Arial;">dilaporkan bulan : <?php echo date("F, Y"); ?></b></td>

	
    </tr>
  </tbody>
</table>


<table style="width: 1024;  text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2" valign=top>
  <tbody>
    <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
	  	<br><font size="1"><b> Grafik Data yang tersedia : 
		<a href="LapBulanan_kehadiran.php?Tahun=<?=$iTahun?>" >Dewasa</a> - 
		<a href="LapBulanan_kehadiran2.php?Tahun=<?=$iTahun?>&Kategori=Anak" >Anak</a> - 
		<a href="LapBulanan_kehadiran2.php?Tahun=<?=$iTahun?>&Kategori=Remaja" >Remaja</a> -
		<a href="LapBulanan_kehadiran2.php?Tahun=<?=$iTahun?>&Kategori=PraRemaja" >PraRemaja</a> -
		<a href="LapBulanan_kehadiran2.php?Tahun=<?=$iTahun?>&Kategori=Pemuda" >Pemuda</a> -
		<a href="LapBulanan_hadir4.php?Tahun=<?=$iTahun?>" >Persembahan</a> -
		<a href="LapBulanan_hadir5.php?Tahun=<?=$iTahun?>" >Amplop</a>
		<br>
		Grafik Tahun : 
		<a href="LapBulanan_kehadiran.php?Tahun=<?=$iTahun-1?>" ><?=$iTahun-1?></a> - 
		<a href="LapBulanan_kehadiran.php?Tahun=<?=$iTahun+1?>" ><?=$iTahun+1?></a>
		<br>
		Tabulasi Data Tahun : 
		<a href="ListEvents.php?Tahun=<?=$iTahun-1?>" ><?=$iTahun-1?></a> - 
		<a href="ListEvents.php?Tahun=<?=$iTahun+1?>" ><?=$iTahun+1?></a>
		<br>
		Jenis Graphic : 
		<a href="LapBulanan_kehadiran.php?Tahun=<?=$iTahun?>&Jenis=Bar" >Batang</a> - 
		<a href="LapBulanan_kehadiran.php?Tahun=<?=$iTahun?>&Jenis=Line" >Garis</a>
		</font>
		</font>
    	<br>
		<br>
		<? minavgmax(12,$iTahun);   ?>
		<b><a href="ListEvents.php?Tahun=<?=$iTahun?>" target="_blank">
		Grafik Kehadiran Majelis Tahun : <?=$iTahun?> </a>
		</b><br>
		<a href="graph_kehadiran_baru.php?Tahun=<?=$iTahun?>&Kolom=12&Jenis=<?=$iJenis?>" width="2048" target="_blank">
 		<?php	echo "<img src=\"graph_kehadiran_baru.php?Tahun=".$iTahun."&Kolom=12&amp;&Jenis=".$iJenis."&amp;".$refresh." \" width=\"1024\" ><br>" ;	?>
		</a><font size="1"><i>* Catatan: Untuk Memperbesar Gambar,Silahkan Klik Grafik tersebut</i></font>
		<br><br>
<div style="page-break-before: always">.</div>
		
		<? minavgmax(3,$iTahun);   ?>
		<b><a href="ListEvents.php?Tahun=<?=$iTahun?>" target="_blank">
		Grafik Kehadiran Jemaat Dewasa Tahun : <?=$iTahun?> </a>
		</b><br>
		<a href="graph_kehadiran_baru.php?Tahun=<?=$iTahun?>&Kolom=3&Jenis=<?=$iJenis?>" width="2048" target="_blank">
 		<?php	echo "<img src=\"graph_kehadiran_baru.php?Tahun=".$iTahun."&Kolom=3&amp;&Jenis=".$iJenis."&amp;".$refresh." \" width=\"1024\" ><br>" ;	?>
		</a><font size="1"><i>* Catatan: Untuk Memperbesar Gambar,Silahkan Klik Grafik tersebut</i></font>
		<br><br>
<div style="page-break-before: always">.</div>		
		<? minavgmax(14,$iTahun);   ?>
		<b><a href="ListEvents.php?Tahun=<?=$iTahun?>" target="_blank">
		Grafik Persembahan Kantong Jemaat Dewasa Tahun : <?=$iTahun?> </a>
		</b><br>
		<a href="graph_kehadiran_baru.php?Tahun=<?=$iTahun?>&Kolom=14&Jenis=<?=$iJenis?>" width="2048" target="_blank">
 		<?php	echo "<img src=\"graph_kehadiran_baru.php?Tahun=".$iTahun."&Kolom=14&amp;&Jenis=".$iJenis."&amp;".$refresh." \" width=\"1024\" ><br>" ;	?>
		</a><font size="1"><i>* Catatan: Untuk Memperbesar Gambar,Silahkan Klik Grafik tersebut</i></font>
		<br><br>
<div style="page-break-before: always">.</div>
		<? minavgmax(40,$iTahun);   ?>
		<b><a href="ListEvents.php?Tahun=<?=$iTahun?>" target="_blank">
		Grafik Jumlah Persembahan Total (Kantong+Amplop) Tahun : <?=$iTahun?> </a>
		</b><br>
		<a href="graph_kehadiran_baru.php?Tahun=<?=$iTahun?>&Kolom=40&Jenis=<?=$iJenis?>" width="2048" target="_blank">
 		<?php	echo "<img src=\"graph_kehadiran_baru.php?Tahun=".$iTahun."&Kolom=40&amp;&Jenis=".$iJenis."&amp;".$refresh." \" width=\"1024\" ><br>" ;	?>
		</a><font size="1"><i>* Catatan: Untuk Memperbesar Gambar,Silahkan Klik Grafik tersebut</i></font>
		<br><br>		
	
 </td>
    </tr>
	</tbody>
	</table>
	
<?php
require "Include/Footer-Short.php";
?>
