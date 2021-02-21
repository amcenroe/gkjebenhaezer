<?php
/*******************************************************************************
 *
 *  filename    : PrintViewUltahWWW.php
 *  last change : 2003-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
//require "Include/Config.php";
//require "Include/Functions.php";
// require "Include/Header-Print.php";



require "Include/ConfigWeb.php";


function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function get_host($ip){
        $ptr= implode(".",array_reverse(explode(".",$ip))).".in-addr.arpa";
        $host = dns_get_record($ptr,DNS_PTR);
        if ($host == null) return $ip;
        else return $host[0]['target'];
}
			
$ipaddr = getRealIpAddr();
$hostygakses = get_host($ipaddr);
			
//Print_r ($_SESSION);
		$logvar1 = "Report";
		$logvar2 = "Ulang Tahun Jemaat (web) ";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "','" . $hostygakses . "','" . $ipaddr . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);


$iTahun = $_GET["Tahun"];
if ($iTahun == '' ) {
  $TGLSKR = date("Y-m-d");
  $THNSKR = date("Y");
} else {
  $TGLSKR = $iTahun . "-12-31";
  $THNSKR = $iTahun; 
}

// Get the Gol Darah ID from the querystring
//$iBulan = FilterInput($_GET["Bulan"]);
//$bln = $iBulan;

 if (( $bln == 1 )) {
	     $BULAN="Januari";
	    } elseif (( $bln == 2 )) {
	      $BULAN="Februari";
	    } elseif (( $bln == 3 )) {
	      $BULAN="Maret";
	    } elseif (( $bln == 4 )) {
	      $BULAN="April";
	    } elseif (( $bln == 5 )) {
	      $BULAN="Mei";
	    } elseif (( $bln == 6 )) {
	      $BULAN="Juni";
	    } elseif (( $bln == 7 )) {
	      $BULAN="Juli";
	    } elseif (( $bln == 8 )) {
	      $BULAN="Agustus";
	 	} elseif (( $bln == 9 )) {
	      $BULAN="September";
	    } elseif (( $bln == 10 )) {
	      $BULAN="Oktober";
	    } elseif (( $bln == 11 )) {
	      $BULAN="Nopember";
	    } elseif (( $bln == 12 )) {
	      $BULAN="Desember";
	    }

$Judul = "Laporan - Status Bulan Kelahiran Jemaat - Bulan: $BULAN "; 
// require "Include/Header-Report.php";


?>
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title><?php echo $Judul ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $sRootPath."/"; ?>Include/<?php echo $_SESSION['sStyle']; ?>">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo $sRootPath."/"; ?>Include/jscalendar/calendar-blue.css" title="cal-style">

<STYLE TYPE="text/css">
  		<!--
  		TD{font-family: Arial; font-size: 10pt;}
		--->
        P.breakhere {page-break-before: always}
</STYLE>
</head>




			<table border="0"  width="800" cellspacing=0 cellpadding=0 >
			
			Bagi Bapak/Ibu/Saudara yang berulang tahun pada bulan ini, Majelis dan segenap warga jemaat GKJ Bekasi Timur mengucapkan : <br><br>
		    <b> “Selamat ulang tahun, Panjang umur, sukses dan sehat selalu. Tuhan Yesus senantiasa memberkati.”<br>
			
			
			</b><br>
			<tr><b><font size="2">
			<td ALIGN=center><b><font size="2">No.</font></b></td>
			<td ALIGN=center><b><font size="2">Minggu ke-</font></b></td>
			<td ALIGN=center><b><font size="2">Nama Lengkap</font></b></td>
			<td ALIGN=center><b><font size="2">TanggalLahir</font></b></td>
			<td ALIGN=center><b><font size="2"> Kelompok </font></b></td>
			</font></b></tr>
			<?php
			 	$sRowClass = "RowColorA";
				$sSQL = "SELECT WEEK(CONCAT(YEAR(NOW()),'-',per_BirthMonth,'-',per_BirthDay),0) as 'Mingguke',
per_FirstName AS Nama,
CONCAT(
CASE DAYOFWEEK(CONCAT(YEAR(NOW()),'-',per_BirthMonth,'-',per_BirthDay))
WHEN '1' THEN 'Minggu'
WHEN '2' THEN 'Senin'
WHEN '3' THEN 'Selasa'
WHEN '4' THEN 'Rabu'
WHEN '5' THEN 'Kamis'
WHEN '6' THEN 'Jumat'
WHEN '7' THEN 'Sabtu'
ELSE ''
END , ', ',
per_BirthDay,' ',
lst_OptionName )
as 'HariTanggal',
CASE per_Workphone
WHEN ' BTRG' THEN 'Bekasi Timur Regensi'
WHEN ' CBTG' THEN 'Cibitung'
WHEN ' CKRG' THEN 'Cikarang'
WHEN ' JTMY' THEN 'Jatimulya'
WHEN ' KRWG' THEN 'Karawang'
WHEN ' MGHY' THEN 'Margahayu'
WHEN ' PRST' THEN 'Perumnas III'
WHEN ' TMBN' THEN 'Tambun'
WHEN ' WMJY' THEN 'Wismajaya'
ELSE ''
END as Kelompok
FROM person_per , list_lst
WHERE
 per_BirthMonth=month(now())
AND lst_ID=6 AND per_BirthMonth=lst_OptionID AND per_cls_ID<3
ORDER BY per_BirthMonth, per_BirthDay, per_WorkPhone";


				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{

					$i++;
					extract($hasilGD);
					//Alternate the row color
                    			$sRowClass = AlternateRowStyle($sRowClass);


				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td ALIGN=center><? echo $i ?></td>
				<td ALIGN=center><?=$hasilGD[Mingguke]?></td>
				<td><?=$hasilGD[Nama]?></td>
				<td><?=$hasilGD[HariTanggal]?></td>
				<td><?=$hasilGD[Kelompok]?></td>
				</tr>
				<?}?>
			</table>
			<hr>



<?php
require "Include/Footer-Short.php";
?>
