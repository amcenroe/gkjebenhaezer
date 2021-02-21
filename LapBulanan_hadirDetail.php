<?php
/*******************************************************************************
*
* filename : LapBulanan_kehadiranDetail.php
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
echo "	<td><font size=2><b>Tanggal </b></td> ";
echo "	<td><font size=2><b>Tempat Ibadah </b></td> ";
echo "	<td><font size=2><b>Waktu </b></td> ";
echo "	<td><font size=2><b>Pengkotbah</b></td> ";
echo "	<td><font size=2><b>Total Persembahan</b></td> ";


echo "	<td ALIGN=right> </td>";
echo "	</tr>";
		

		$sSQL = "SELECT a.Tanggal, a.KodeTI , b.NamaTI as TempatIbadah, a.Pukul , Pengkotbah,  
				SUM($stat) as total 
				FROM Persembahangkjbekti a 
				LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
				WHERE a.KodeTI > 0 AND YEAR(Tanggal) =$tahun
				ORDER BY a.Tanggal, a.KodeTI, Pukul
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
echo "	<td><font size=2>" . $hasilGD[Tanggal] . "</td>";
echo "	<td><font size=2>" . $hasilGD[TempatIbadah] . "</td>";
echo "	<td><font size=2>" . $hasilGD[Pukul] . "</td>";
echo "	<td><font size=2>" . $hasilGD[Pengkotbah] . "</td>";
echo "	<td><font size=2>" . $hasilGD[total] . "</td>";

		}
		

echo "	</table>";



	
<?php
require "Include/Footer-Short.php";
?>
