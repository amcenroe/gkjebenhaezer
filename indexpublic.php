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
//require "Include/Config.php";
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
		$logvar2 = "Detail Executive Summary public (web) ";
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


//Print_r ($_SESSION);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>Laporan Bulanan - Executive Summary</title>

  <STYLE TYPE="text/css">
        P.breakhere {page-break-before: always}
</STYLE>

</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >

<br>
<center>
Maaf Informasi yang anda harapkan tidak tersedia dalam versi ini. <br>
Jika Anda membutuhkan data yang dimaksud, silahkan menghubungi admin atau sekretariat Gereja.<br>
Terima Kasih<br><br>
<A HREF="javascript:javascript:history.go(-1)">Tampilan Sebelumnya</A>
<br>
<br>
<hr>
detected ip :<?php echo $ipaddr ;?><br>
detected host : <?php echo $hostygakses ;?>
 </center>

</body>


