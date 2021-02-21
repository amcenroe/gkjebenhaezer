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
		$logvar2 = "Executive Summary public (web) ";
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


<?php

function rentang_usia ($klasumur, $umurawal, $umurakhir) {

echo "	<table border=\"0\" width=\"280\" cellspacing=0 cellpadding=0 >";
echo "	<tr>";
echo "	<td><b> > </td>";
echo "	<td><font size=2><b>Jemaat " . $klasumur . " (" . $umurawal ."-" . $umurakhir . "Th)</td> ";
echo "	<td ALIGN=right> </td>";
echo "	</tr>";
		$sSQL = "SELECT CONCAT('<a href=../indexpublic.php?GenderID=',per_Gender,'&amp;Klas=$klasumur&amp;Uawal=$umurawal&amp;Uakhir=$umurakhir&amp;lstID=1&amp;clsID=3   >',IF(per_gender ='1', 'Laki laki', 'Perempuan'),'  ') as Jemaat , count(per_ID) as Jiwa
			FROM person_per
			WHERE per_gender <> 0 AND per_cls_ID < 3 AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL  $umurawal YEAR) <=  CURDATE()
			AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (  $umurakhir+1 ) YEAR) >=  CURDATE()
			group by per_gender";
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
echo "	<td><font size=2>" . $hasilGD[Jemaat] . "</td>";
echo "	<td ALIGN=right><font size=2>" . $hasilGD[Jiwa] . " jiwa</td>";
		}
echo "	<tr>";
echo "	<td></td>";
echo "	<td><font size=2>Sub Total</td>";
echo "	<td ALIGN=right><font size=2><b>" . $total . " jiwa</b></td>";
echo "	</tr>";
echo "	</table>";

global $thestring;
$thestring[$klasumur]=$total;
}

function statuskelompok ($kelompok) {

if ( $kelompok == "" ) {
	echo "";
} else {

echo "	<table border=\"0\" width=\"280\" cellspacing=0 cellpadding=0 >";
echo "	<tr>";
echo "	<td><b>></td>";
echo "	<td><a href=\"../indexpublic.php?Status=$kelompok \"  >";
echo "	<font size=2><b>Kelompok  " . $kelompok . "</td></a> ";
echo "	<td ALIGN=right> </td>";
echo "	</tr>";
	$sSQL = "select lst_OptionName as Status, count(per_id) as Jiwa
		from person_per, list_lst
		where per_cls_ID < 3 AND lst_id = 2 AND lst_optionID = per_fmr_id AND  per_workphone like '%$kelompok' group by per_fmr_id
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
echo "	<td><font size=2>" . $hasilGD[Status] . "</td>";
echo "	<td ALIGN=right><font size=2>" . $hasilGD[Jiwa] . " jiwa</td>";
		}
echo "	<tr>";
echo "	<td></td>";
echo "	<td><font size=2>Sub Total</td>";
echo "	<td ALIGN=right><font size=2><b>" . $total . " jiwa</b></td>";
echo "	</tr>";
echo "	</table>";

global $thestringkelompok;
$thestringkelompok[$kelompok]=$total;

}
}

// Get the list of custom person
$sSQL = "SELECT count(per_ID) FROM person_per WHERE per_cls_ID < 3";
$perintah = mysql_query($sSQL);
$semua = mysql_result($perintah,0);
// Get the list of custom person null
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE per_BirthYear is null AND per_cls_ID < 3" ;
$perintah = mysql_query($sSQL);
$nodata = mysql_result($perintah,0);



// Get the list of custom person Laki2
$sSQL = "SELECT COUNT(per_ID) FROM person_per WHERE per_cls_ID < 3 AND per_Gender = 1 ";
$perintah = mysql_query($sSQL);
$laki = mysql_result($perintah,0);
// Get the list of custom person Perempuan
$sSQL = "SELECT COUNT(per_ID) FROM person_per WHERE per_cls_ID < 3 AND per_Gender = 2 ";
$perintah = mysql_query($sSQL);
$perempuan = mysql_result($perintah,0);
// Get the list of custom Keluarga
$sSQL = "SELECT COUNT(fam_ID) FROM family_fam ";
$perintah = mysql_query($sSQL);
$keluarga = mysql_result($perintah,0);
// Get the list of this month birtday
$sSQL = "SELECT per_ID as AddToCart, CONCAT(per_FirstName,' ',per_MiddleName,' ',per_LastName) AS Nama FROM person_per WHERE per_fmr_ID = 1 AND per_Gender = 1";
//$sSQL = "SELECT per_BirthDay as Tanggal, CONCAT(per_FirstName,' ',per_LastName) AS Nama FROM person_per WHERE per_cls_ID=1 AND per_BirthMonth=1 ORDER BY per_BirthDay";
$rsUltah = RunQuery($sSQL);
$my_t=getdate(date("U"));
if ($my_t[mon] < 10 )
{
$my_t[mon] = "0$my_t[mon]" ;
}
else
$my_t[mon] = "$my_t[mon]" ;
$bulanini = "$my_t[year]-$my_t[mon]%";
// Get the list of Atestasi Masuk
$sSQL = "SELECT COUNT(per_ID) FROM person_per WHERE per_cls_ID < 3 AND per_MembershipDate like '$bulanini' ";
$perintah = mysql_query($sSQL);
$atestasi_masuk = mysql_result($perintah,0);

// Get the list of Meninggal Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom where per_cls_id = 7 AND YEAR(c41) = $THNSKR";
$perintah = mysql_query($sSQL);
$summeninggal = mysql_result($perintah,0);
// Get the list of Meninggal Tahun ini per bulan
function kematian($bulan,$THNSKR) {
$sSQL = "select count(*) from person_per natural join person_custom where per_cls_id = 7 AND YEAR(c41) = $THNSKR
 AND MONTH(c41) = $bulan ";
$perintah = mysql_query($sSQL);
$kematiantahunini = mysql_result($perintah,0); 
if ( $kematiantahunini == "0" ) 
{ echo "-"; } else { echo $kematiantahunini; }
}

// Get the list of Pindah Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom where c9 is not null AND YEAR(c10) = $THNSKR";
$perintah = mysql_query($sSQL);
$sumpindah = mysql_result($perintah,0);
// Get the list of Pindah Tahun ini per bulan
function pindah($bulan,$THNSKR) {
$sSQL = "select count(*) from person_per natural join person_custom where c9 is not null AND YEAR(c10) = $THNSKR
 AND MONTH(c10) = $bulan ";
$perintah = mysql_query($sSQL);
$pindahtahunini = mysql_result($perintah,0); 
if ( $pindahtahunini == "0" ) 
{ echo "-"; } else { echo $pindahtahunini; }
}

// Get the list of Attestasi Masuk Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom where c8 is not null AND per_cls_id = 1 AND
YEAR(per_membershipdate) =  $THNSKR";
$perintah = mysql_query($sSQL);
$sumattestasi = mysql_result($perintah,0);

// Get the list of Attestasi Masuk dari Keluarga Tahun ini
$sSQL = "select count(distinct per_fam_id) from person_per natural join person_custom where c8 is not null AND per_cls_id = 1 AND
YEAR(per_membershipdate) = $THNSKR";
$perintah = mysql_query($sSQL);
$sumattestasikeluarga = mysql_result($perintah,0);

function atestasi($bulan,$THNSKR) {
$sSQL = "select count(*) from person_per natural join person_custom where c8 is not null AND per_cls_id = 1 AND
YEAR(per_membershipdate) = $THNSKR AND MONTH(per_membershipdate) = $bulan ";
$perintah = mysql_query($sSQL);
$atestasitahunini = mysql_result($perintah,0); 
if ( $atestasitahunini == "0" ) 
{ echo "-"; } else { echo $atestasitahunini; }
}

// Get the list of Titipan Masuk Tahun ini
//$sSQL = "select count(*) from person_per natural join person_custom where c8 is not null AND per_cls_id = 2 AND
//YEAR(per_membershipdate) = $THNSKR";
//$sSQL = "select count(*) from person_per natural join person_custom where ( per_cls_id = '2' 
//AND MONTH(per_MembershipDate) = $bulan AND 
//YEAR(per_membershipdate) = $THNSKR ) AND 
//(( (c2 is not NULL AND c2<>'0000-00-00 00:00:00') 
//AND c27 is not NULL ) OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2 )";
//$perintah = mysql_query($sSQL);
//$sumtitipan = mysql_result($perintah,0);

// Get the list of Baptis Anak Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom where YEAR(c1) = $THNSKR";
$perintah = mysql_query($sSQL);
$sumbaptisanak = mysql_result($perintah,0); 

// Get the list of Baptis Anak Tahun ini perbulan
function baptisanak($bulan,$THNSKR) {
$sSQL = "select count(*) from person_per natural join person_custom where YEAR(c1) = $THNSKR 
AND MONTH(c1) = $bulan ";
$perintah = mysql_query($sSQL);
$baptisanak = mysql_result($perintah,0); 
if ( $baptisanak == "0" ) 
{ echo "-"; } else { echo $baptisanak; }
}

// Get the list of Baptis Anak dari Keluarga Tahun ini
$sSQL = "select count(distinct fam_id) from person_per natural join person_custom, family_fam 
where per_fam_id = fam_ID AND 
YEAR(c1) = $THNSKR";
$perintah = mysql_query($sSQL);
$sumbaptisanakklg = mysql_result($perintah,0); 

// Get the list of Sidhi Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom where YEAR(c2) = $THNSKR";
$perintah = mysql_query($sSQL);
$sumsidhi = mysql_result($perintah,0); 

// Get the list of Sidhi Tahun ini perbulan
function sidhi($bulan,$THNSKR) {
$sSQL = "select count(*) from person_per natural join person_custom where YEAR(c2) = $THNSKR
AND MONTH(c2) = $bulan ";
$perintah = mysql_query($sSQL);
$sidhitahunini = mysql_result($perintah,0); 
if ( $sidhitahunini == "0" ) 
{ echo "-"; } else { echo $sidhitahunini; }
}

// Get the list of Baptis Dewasa Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom where YEAR(c18) = $THNSKR";
$perintah = mysql_query($sSQL);
$sumbaptisdewasa = mysql_result($perintah,0); 

// Get the list of Baptis Dewasa Tahun ini perbulan
function baptisdewasa($bulan,$THNSKR) {
$sSQL = "select count(*) from person_per natural join person_custom where YEAR(c18) = $THNSKR 
AND MONTH(c18) = $bulan ";
$perintah = mysql_query($sSQL);
$baptisdewasa = mysql_result($perintah,0); 
if ( $baptisdewasa == "0" ) 
{ echo "-"; } else { echo $baptisdewasa; }
}




// Get the list of Jemaat Dewasa Total Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom where per_cls_id = '1' AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00') 
AND c27 is not NULL ) OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2 )";
$perintah = mysql_query($sSQL);
$sumjemaatdewasa = mysql_result($perintah,0); 

// Get the list of Jemaat Dewasa Belum Menikah Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom
where per_cls_id = '1' AND c15 = 1 AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00') AND c27 is not NULL ) 
OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL)) ";
$perintah = mysql_query($sSQL);
$sumjemaatdewasasingle = mysql_result($perintah,0); 
// Get the list of Jemaat Dewasa Sudah Menikah Tahun ini
// $sSQL = "select count(*) from person_per natural join person_custom
//where c15 = 2 AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00') AND c27 is not NULL ) 
//OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL)) ";
$sSQL = "select count(*) from person_per natural join person_custom
where c15 = 2 AND per_cls_id = '1'";
$perintah = mysql_query($sSQL);
$sumjemaatdewasamarried = mysql_result($perintah,0); 

// Get the list of Jemaat Titipan Dewasa Total Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom where per_cls_id = '2' AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00') 
AND c27 is not NULL ) OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2 )";
$perintah = mysql_query($sSQL);
$sumjemaatdewasatitip = mysql_result($perintah,0); 

// Get the list of Jemaat Titipan Dewasa Total Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom where ( per_cls_id = '2' AND 
YEAR(per_membershipdate) = $THNSKR ) AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00') 
AND c27 is not NULL ) OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2 )";
$perintah = mysql_query($sSQL);
$sumjemaatdewasatitiptahunini = mysql_result($perintah,0); 

function titipan($bulan,$THNSKR) {
$sSQL = "select count(*) from person_per natural join person_custom where ( per_cls_id = '2' 
AND MONTH(per_MembershipDate) = $bulan AND 
YEAR(per_membershipdate) = $THNSKR ) AND 
(( (c2 is not NULL AND c2<>'0000-00-00 00:00:00') 
AND c27 is not NULL ) OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2 )";
$perintah = mysql_query($sSQL);
$jemaatdewasatitip = mysql_result($perintah,0); 
if ( $jemaatdewasatitip == "0" ) 
{ echo "-"; } else { echo $jemaatdewasatitip; }
}


// Get the list of Jemaat Titipan Dewasa Belum Menikah Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom
where per_cls_id = '2' AND c15 = 1 AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00') AND c27 is not NULL ) 
OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL)) ";
$perintah = mysql_query($sSQL);
$sumjemaatdewasasingletitip = mysql_result($perintah,0); 
// Get the list of Jemaat Titipan Dewasa Sudah Menikah Tahun ini
// $sSQL = "select count(*) from person_per natural join person_custom
//where c15 = 2 AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00') AND c27 is not NULL ) 
//OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL)) ";
$sSQL = "select count(*) from person_per natural join person_custom
where c15 = 2 AND per_cls_id = '2'";
$perintah = mysql_query($sSQL);
$sumjemaatdewasamarriedtitip = mysql_result($perintah,0); 

// Get the list of Jemaat Anak Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom
where  c15='1' AND ( per_cls_id = '1' OR per_cls_id = '2') AND ( ( c2 ='0000-00-00 00:00:00' OR c2 is NULL ) 
AND ( c18 ='0000-00-00 00:00:00' OR c18 is NULL ) AND c28 is NULL AND c27 is NULL )";
$perintah = mysql_query($sSQL);
$sumjemaatanak = mysql_result($perintah,0); 

// Get the list of Kelahiran Jemaat Anak Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom, family_fam
where person_per.per_fam_id = family_fam.fam_id AND per_BirthYear = $THNSKR
order by per_workphone, per_FirstName";
$perintah = mysql_query($sSQL);
$sumjemaatlahir = mysql_result($perintah,0); 
function kelahiran($bulan,$THNSKR) {
$sSQL = "select count(*) from person_per natural join person_custom, family_fam
where person_per.per_fam_id = family_fam.fam_id AND per_BirthYear = $THNSKR AND per_BirthMonth = $bulan 
order by per_workphone, per_FirstName";
$perintah = mysql_query($sSQL);
$kelahiran = mysql_result($perintah,0); 
if ( $kelahiran == "0" ) 
{ echo "-"; } else { echo $kelahiran; }
}
// Get the list of Kelahiran Jemaat Anak dr KeluargaTahun ini
$sSQL = "select count(distinct fam_id) from person_per natural join person_custom, family_fam
where person_per.per_fam_id = family_fam.fam_id AND per_BirthYear = $THNSKR
order by per_workphone, per_FirstName";
$perintah = mysql_query($sSQL);
$sumjemaatlahirklg = mysql_result($perintah,0); 

?>

<table
 style="width: 750; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="2" cellspacing="2">

</table>

<table 
 style="font-family: Arial; width: 750; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="2" cellspacing="2">
 
 <tr>
  <td colspan="15" style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
          <b>Informasi Mutasi Warga </b><br></td>
 </tr>
<tr class="1">
<td><font size=2><ALIGN=center> No </td> 
<td><font size=2><ALIGN=left><b> Faktor Pertambahan </b></td>
<td><font size=2><ALIGN=center> Jan </td>
<td><font size=2><ALIGN=center> Feb </td>
<td><font size=2><ALIGN=center> Mar </td>
<td><font size=2><ALIGN=center> Apr </td>
<td><font size=2><ALIGN=center> Mei </td>
<td><font size=2><ALIGN=center> Jun </td>
<td><font size=2><ALIGN=center> Jul </td>
<td><font size=2><ALIGN=center> Agt </td>
<td><font size=2><ALIGN=center> Sep </td>
<td><font size=2><ALIGN=center> Okt </td>
<td><font size=2><ALIGN=center> Nop </td>
<td><font size=2><ALIGN=center> Des </td>
<td><font size=2><ALIGN=center> Total </td>
</tr>
<tr>
<td><font size=2><ALIGN=center> 1 </td>
<td><font size=2><ALIGN=left> Kelahiran </td>
<td><font size=2><ALIGN=center> <? kelahiran(1,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kelahiran(2,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kelahiran(3,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kelahiran(4,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kelahiran(5,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kelahiran(6,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kelahiran(7,$THNSKR); ?> </td>
<td><font size=2><ALIGN=center> <? kelahiran(8,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kelahiran(9,$THNSKR); ?> </td>
<td><font size=2><ALIGN=center> <? kelahiran(10,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kelahiran(11,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kelahiran(12,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center><?php echo $sumjemaatlahir ?>  </td>
</tr>
<tr class="2">
<td><font size=2> 2 </td>
<td><font size=2> Baptisan Anak(*) </td>
<td><font size=2><ALIGN=center> <? baptisanak(1,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisanak(2,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisanak(3,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisanak(4,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisanak(5,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisanak(6,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisanak(7,$THNSKR); ?> </td>
<td><font size=2><ALIGN=center> <? baptisanak(8,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisanak(9,$THNSKR); ?> </td>
<td><font size=2><ALIGN=center> <? baptisanak(10,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisanak(11,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisanak(12,$THNSKR); ?>  </td>
<td><font size=2><?php echo $sumbaptisanak ?>   </td>
</tr>
<tr class="3">
<td><font size=2> 3 </td>
<td><font size=2> Baptisan Dewasa </td>
<td><font size=2><ALIGN=center> <? baptisdewasa(1,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisdewasa(2,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisdewasa(3,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisdewasa(4,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisdewasa(5,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisdewasa(6,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisdewasa(7,$THNSKR); ?> </td>
<td><font size=2><ALIGN=center> <? baptisdewasa(8,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisdewasa(9,$THNSKR); ?> </td>
<td><font size=2><ALIGN=center> <? baptisdewasa(10,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisdewasa(11,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? baptisdewasa(12,$THNSKR); ?>  </td>
<td><font size=2><?php echo $sumbaptisdewasa ?>   </td>
</tr>
<tr>
<td><font size=2> 4 </td>
<td><font size=2> Sidi(*) </td>
<td><font size=2> <ALIGN=center> <? sidhi(1,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? sidhi(2,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? sidhi(3,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? sidhi(4,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? sidhi(5,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? sidhi(6,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? sidhi(7,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? sidhi(8,$THNSKR); ?> </td>
<td><font size=2> <ALIGN=center> <? sidhi(9,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? sidhi(10,$THNSKR); ?> </td>
<td><font size=2> <ALIGN=center> <? sidhi(11,$THNSKR); ?> </td>
<td><font size=2> <ALIGN=center> <? sidhi(12,$THNSKR); ?>  </td>
<td><font size=2><?php echo $sumsidhi ?>  </td>
</tr>
<tr>
<td><font size=2> 5 </td>
<td><font size=2> Pindahan/Atestasi </td>
<td><font size=2> <ALIGN=center> <? atestasi(1,$THNSKR); ?>   </td>
<td><font size=2> <ALIGN=center> <? atestasi(2,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? atestasi(3,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? atestasi(4,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? atestasi(5,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? atestasi(6,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? atestasi(7,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? atestasi(8,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? atestasi(9,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? atestasi(10,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? atestasi(11,$THNSKR); ?>  </td>
<td><font size=2> <ALIGN=center> <? atestasi(12,$THNSKR); ?>  </td>
<td><font size=2><?php echo $sumattestasi ?>   </td>
</tr>
<tr>
<td><font size=2> 6 </td>
<td><font size=2> Titipan </td>
<td><font size=2> <? titipan(1,$THNSKR); ?>  </td>
<td><font size=2> <? titipan(2,$THNSKR); ?>  </td>
<td><font size=2> <? titipan(3,$THNSKR); ?>  </td>
<td><font size=2> <? titipan(4,$THNSKR); ?>  </td>
<td><font size=2> <? titipan(5,$THNSKR); ?>  </td>
<td><font size=2> <? titipan(6,$THNSKR); ?>  </td>
<td><font size=2> <? titipan(7,$THNSKR); ?>  </td>
<td><font size=2> <? titipan(8,$THNSKR); ?>  </td>
<td><font size=2> <? titipan(9,$THNSKR); ?>  </td>
<td><font size=2> <? titipan(10,$THNSKR); ?>  </td>
<td><font size=2> <? titipan(11,$THNSKR); ?>  </td>
<td><font size=2> <? titipan(12,$THNSKR); ?>  </td>
<td><font size=2><?php echo $sumjemaatdewasatitiptahunini ?>  </td>
</tr>
<tr>
<td colspan="15">
<font size=1><ALIGN=right>Keterangan : (*) = tidak menambah jumlah warga jemaat, hanya menambah jumlah warga baptisan anak dan warga dewasa.
</td></tr>
</table>

<table 
 style="font-family: Arial; width: 750; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="2" cellspacing="2">
<tr class="1">
<td><font size=2><ALIGN=center> No </td> 
<td><font size=2><ALIGN=left> <b>Faktor Pengurangan</b> </td>
<td><font size=2><ALIGN=center> Jan </td>
<td><font size=2><ALIGN=center> Feb </td>
<td><font size=2><ALIGN=center> Mar </td>
<td><font size=2><ALIGN=center> Apr </td>
<td><font size=2><ALIGN=center> Mei </td>
<td><font size=2><ALIGN=center> Jun </td>
<td><font size=2><ALIGN=center> Jul </td>
<td><font size=2><ALIGN=center> Agt </td>
<td><font size=2><ALIGN=center> Sep </td>
<td><font size=2><ALIGN=center> Okt </td>
<td><font size=2><ALIGN=center> Nop </td>
<td><font size=2><ALIGN=center> Des </td>
<td><font size=2><ALIGN=center> Total </td>
</tr>
<tr>
<td><font size=2><ALIGN=center> 1 </td>
<td><font size=2><ALIGN=left> Kematian </td>
<td><font size=2><ALIGN=center> <? kematian(1,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kematian(2,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kematian(3,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kematian(4,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kematian(5,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kematian(6,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kematian(7,$THNSKR); ?> </td>
<td><font size=2><ALIGN=center> <? kematian(8,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kematian(9,$THNSKR); ?> </td>
<td><font size=2><ALIGN=center> <? kematian(10,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kematian(11,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? kematian(12,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center><?php echo $summeninggal ?>  </td>
</tr>
<tr class="2">
<td><font size=2> 2 </td>
<td><font size=2> Pindah </td>
<td><font size=2><ALIGN=center> <? pindah(1,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? pindah(2,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? pindah(3,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? pindah(4,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? pindah(5,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? pindah(6,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? pindah(7,$THNSKR); ?> </td>
<td><font size=2><ALIGN=center> <? pindah(8,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? pindah(9,$THNSKR); ?> </td>
<td><font size=2><ALIGN=center> <? pindah(10,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? pindah(11,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? pindah(12,$THNSKR); ?>  </td>
<td><font size=2><?php echo $sumpindah ?>   </td>
</tr>
<tr class="3">
<td><font size=2> 3 </td>
<td><font size=2> Pindah Agama </td>
<td><font size=2><ALIGN=center> <? //pindahagm(1,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? //pindahagm(2,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? //pindahagm(3,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? //pindahagm(4,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? //pindahagm(5,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? //pindahagm(6,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? //pindahagm(7,$THNSKR); ?> </td>
<td><font size=2><ALIGN=center> <? //pindahagm(8,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? //pindahagm(9,$THNSKR); ?> </td>
<td><font size=2><ALIGN=center> <? //pindahagm(10,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? //pindahagm(11,$THNSKR); ?>  </td>
<td><font size=2><ALIGN=center> <? //pindahagm(12,$THNSKR); ?>  </td>
<td><font size=2><?php echo $sumpindahagm ?>   </td>
</tr>
</table>


<table style="width: 750;  text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2" valign=top>
  <tbody>
    <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
    	<b>Informasi Jumlah Jemaat</b> <br>(Status: WARGA & TITIPAN)<br>
		&nbsp* Jumlah Total Jemaat : <?php echo $semua ?> jiwa<br>
		&nbsp* Jumlah Total Keluarga : <?php echo $keluarga ?> keluarga<br>
		<br>
	</td>

    <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
          <b>Informasi Mutasi Warga </b><br>
 
 
<table border=0 width=280 cellspacing=0 cellpadding=0 >

<tr><td><font size=2><b> . </b></font></td></tr>
<tr><td><font size=2><b> Bertambah </b></font></td></tr>
<tr><td><font size=2><b> . </b></font></td></tr>
<tr><td><font size=2> A. Penambahan Jemaat Dewasa </td></tr>
<tr><td><font size=2>  1.<a href=../indexpublic.php?QueryID=71  >Attestasi Masuk</td><td><ALIGN=right>
<font size=2><?php echo $sumattestasi ?> </td><td><font size=2> jiwa</td></font></tr>
<tr><td><font size=2>  ---- dari <?php echo $sumattestasikeluarga ?> keluarga</td><td><ALIGN=right>
<tr><td><font size=2>  2.<a href=../indexpublic.php?QueryID=95  >Titipan dari Gereja lain</td><td><ALIGN=right> 
<font size=2><?php echo $sumjemaatdewasatitiptahunini ?></td><td><font size=2> jiwa</td></font></tr>
<tr><td><font size=2>  3.<a href=../indexpublic.php?QueryID=99  >Baptis Dewasa </td><td><ALIGN=right>
<font size=2><?php echo $sumbaptisdewasa ?> </td><td><font size=2> jiwa</td></font></tr>
<tr><td><font size=2>  4.<a href=../indexpublic.php?QueryID=98  >Sidhi </td><td><ALIGN=right>
<font size=2><?php echo $sumsidhi ?> </td><td><font size=2> jiwa</td></font></tr>
<tr><td><font size=2><b> . </b></font></td></tr>
<tr><td><font size=2> B. Penambahan Jemaat Anak </td></tr>
<tr><td><font size=2>  1.<a href=../indexpublic.php?QueryID=93  >Kelahiran Tahun ini </td><td><ALIGN=right>
<font size=2><?php echo $sumjemaatlahir ?> </td><td><font size=2> jiwa</td></font></tr>
<tr><td><font size=2>  ---- dari <?php echo $sumjemaatlahirklg ?> keluarga </td><td><ALIGN=right>
<font size=2> </td><td><font size=2> </td></font></tr>
<tr><td><font size=2>  2.<a href=../indexpublic.php?QueryID=97  >Baptis Anak </td><td><ALIGN=right>
<font size=2><?php echo $sumbaptisanak ?> </td><td><font size=2> jiwa</td></font></tr>
<tr><td><font size=2>  ---- dari <?php echo $sumbaptisanakklg ?> keluarga </td><td><ALIGN=right>
<font size=2> </td><td><font size=2> </td></font></tr>
<tr><td><font size=2><b> . </b></font></td></tr>
<tr><td><font size=2><b> Berkurang </b></td></font></tr>
<tr><td><font size=2> 1.<a href=../indexpublic.php?QueryID=73  > Jemaat Pindah  </a></td><td><ALIGN=right>
<font size=2><?php echo $sumpindah ?> </td><td><font size=2> jiwa</td></font></tr>
<tr><td><font size=2> 2.<a href=../indexpublic.php?QueryID=  > Meninggal</td><td><ALIGN=right>
<font size=2><?php echo $summeninggal ?> </td><td><font size=2> jiwa</td></font></tr>

</table>
		
 <br>

 </td>

    </tr>
	
	
		<tr>
	<td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
          <b>Informasi Struktur Organisasi </b><br>
		  
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>

		<?php
		$sSQL = "Select  vol_name as Jabatan, count(vol_name) AS Jumlah
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id < 4
					Group by vol_name ORDER by vol_id 
					";
		$perintah = mysql_query($sSQL);
			$i = 0;
			while ($hasilMajelis=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><a href=../indexpublic.php?QueryID= ><?=$hasilMajelis[Jabatan]?></a></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</td>
				</tr>
				<?}?>
			<?	
					$sSQL = "Select  vol_name as Jabatan, count(vol_name) AS Jumlah
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id > 7 AND vol_id < 61   
					";
		$perintah = mysql_query($sSQL);
			$i = 0;
			while ($hasilMajelis=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><a href=../indexpublic.php?QueryID  >Badan Pembantu Majelis</a></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</td>
				</tr>
				<?}?>
		<?	
					$sSQL = "Select  vol_name as Jabatan, count(vol_name) AS Jumlah
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id > 3 AND vol_id < 8
					";
		$perintah = mysql_query($sSQL);
			$i = 0;
			while ($hasilMajelis=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><a href=../indexpublic.php?QueryID >Pengurus Kelompok</a></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</td>
				</tr>
				<?}?>
				<?	
					$sSQL = "Select  vol_name as Jabatan, count(vol_name) AS Jumlah
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 201
					";
		$perintah = mysql_query($sSQL);
			$i = 0;
			while ($hasilMajelis=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><a href=../indexpublic.php?QueryID ><?=$hasilMajelis[Jabatan]?></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</td>
				</tr>
				<?}?>		
		</table>


	</td>
	
	<td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
          <b>Informasi Pelayan Ibadah </b><br>
		  
	<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
	  
		  	<?	//Organis
					$sSQL = "Select  vol_name as Jabatan, count(vol_name) AS Jumlah
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 203
					";
		$perintah = mysql_query($sSQL);
			$i = 0;
			while ($hasilMajelis=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><a href=../indexpublic.php?QueryID ><?=$hasilMajelis[Jabatan]?></a></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</td>
				</tr>
				<?}?>		
				
			<?	 // Song Leader
					$sSQL = "Select  vol_name as Jabatan, count(vol_name) AS Jumlah
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 204
					";
		$perintah = mysql_query($sSQL);
			$i = 0;
			while ($hasilMajelis=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><a href=../indexpublic.php?QueryID ><?=$hasilMajelis[Jabatan]?></a></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</td>
				</tr>
				<?}?>	
				
			<?	 // Guru Sekolah Minggu
					$sSQL = "Select  vol_name as Jabatan, count(vol_name) AS Jumlah
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 202
					";
		$perintah = mysql_query($sSQL);
			$i = 0;
			while ($hasilMajelis=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><a href=../indexpublic.php?QueryID ><?=$hasilMajelis[Jabatan]?></a></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</td>
				</tr>
				<?}?>	
				
	
			<?	 // Pokja Multimedia
					$sSQL = "Select  vol_name as Jabatan, count(vol_name) AS Jumlah
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 200
					";
		$perintah = mysql_query($sSQL);
			$i = 0;
			while ($hasilMajelis=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><a href=../indexpublic.php?QueryID ><?=$hasilMajelis[Jabatan]?></a></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</td>
				</tr>
				<?}?>	

			<?	 // Tim Musik
					$sSQL = "Select  vol_name as Jabatan, count(vol_name) AS Jumlah
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 205
					";
		$perintah = mysql_query($sSQL);
			$i = 0;
			while ($hasilMajelis=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><a href=../indexpublic.php?QueryID ><?=$hasilMajelis[Jabatan]?></a></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</td>
				</tr>
				<?}?>	
				
			<?	 // Tim MC
					$sSQL = "Select  vol_name as Jabatan, count(vol_name) AS Jumlah
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 206
					";
		$perintah = mysql_query($sSQL);
			$i = 0;
			while ($hasilMajelis=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><a href=../indexpublic.php?QueryID ><?=$hasilMajelis[Jabatan]?></a></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</td>
				</tr>
				<?}?>
				
		</table>
		
	</td>
	</tr>

	
	
    <tr>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top >
		<br><b>Informasi Jemaat Berdasarkan Rentang Usia </b><br>

		<? rentang_usia(Balita,0,5);   ?>
		<? rentang_usia(Anak,6,13);    ?>
		<? rentang_usia(Remaja,14,17); ?>
		<? rentang_usia(Pemuda,18,21); ?>
		<? rentang_usia(Dewasa,22,54); ?>
		<? rentang_usia(Lansia,55,200);?>

		<br></td>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top >
		<br><b>Grafis : </b><br>
 		<?php
			echo "<img src=\"graph_umurjemaat2.php?BLT=$thestring[Balita]&amp;ANK=$thestring[Anak]&amp;
			RMJ=$thestring[Remaja]&amp;PMD=$thestring[Pemuda]&amp;DWS=$thestring[Dewasa]&amp;
			LNS=$thestring[Lansia]&amp;$refresh \" width=\"360\" ><br>" ;
		?>
		<font size="1"><i>* Catatan: Data berikut adalah Jemat yang berstatus "WARGA" dan "TITIPAN"</i></font>

		<br></td>

    </tr>

 <P CLASS="breakhere">
<tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Status Kewargaan</b><br>
      	<?php
      	echo "<img src=\"graph_statuswarga.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>

		<?php
					$sSQL = "select  CONCAT('<a href=../indexpublic.php?Status=',c.lst_OptionID,'  >',c.lst_OptionName,'</a>') as StatusKewargaan, count(a.per_ID) as Jumlah
							from person_per a , list_lst c
							WHERE a.per_cls_ID = c.lst_OptionID AND c.lst_ID = 1
							GROUP BY c.lst_OptionName ORDER by lst_OptionID";
						$perintah = mysql_query($sSQL);
						$i = 0;
						while ($hasilStWarga=mysql_fetch_array($perintah)){
						$i++;
						//Alternate the row color
						$sRowClass = AlternateRowStyle($sRowClass);
						?>
						<tr class="<?php echo $sRowClass; ?>">
						<td>*</td>
						<td><font size=2><?=$hasilStWarga[StatusKewargaan]?></td>
						<td ALIGN=right><font size=2><?=$hasilStWarga[Jumlah]?>  jiwa</td>
						</tr>
				<?}?>
		</table>
		</td>


      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Informasi Berdasarkan Jenis Kelamin </b><br>
      <?php
	  	  	echo "<img src=\"graph_jeniskelamin.php?lakilaki=$laki&amp;perempuan=$perempuan&amp;$refresh \" width=\"360\" ><br>" ;
	  ?>
	  <table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
	  <?php
	  		$sSQL = "select CONCAT('<a href=../indexpublic.php?GenderID=',per_Gender,'   >',per_Gender,'  ') as Gender ,
	  				IF(per_Gender=1,\"Laki Laki</a>\",\"Perempuan</a>\") as JenisKelamin, count(per_Gender) as Jumlah from person_per
	  				WHERE per_cls_ID < 3
	  				group by JenisKelamin
	  				";
	  				$perintah = mysql_query($sSQL);
	  				$i = 0;
	  				while ($hasil=mysql_fetch_array($perintah)){
	  				$i++;
					//Alternate the row color
					$sRowClass = AlternateRowStyle($sRowClass);
	  				?>
	  				<tr class="<?php echo $sRowClass; ?>">
	  				<td>*</td>
	  				<td><font size=2><?=$hasil[Gender]?> <?=$hasil[JenisKelamin]?></td>
	  				<td ALIGN=right><font size=2><?=$hasil[Jumlah]?>  jiwa</td>
	  				<td><font size=2>jiwa</td>
	  				</tr>
					<?}?>
	  </table>

      </td>
</tr>

 <P CLASS="breakhere">
 <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Anggota Jemaat per Kelompok</b><br>
				<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
				<?php
					$sSQL = "select per_workphone as Kelompok from person_per group by per_workphone";
					$perintah = mysql_query($sSQL);
					while ($hasil=mysql_fetch_array($perintah)){
					?>
					<tr>
					<td><?statuskelompok($hasil[Kelompok])?></td>
					</tr>
					<?}?>
				</table>
		</td>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Grafis :</b><br>
				<?php
				echo "<img src=\"graph_kelompok.php?&amp;$refresh \" width=\"360\" ><br>" ;
				?>
				<font size="1"><i>* Catatan: Data berikut adalah Jemat yang berstatus "WARGA" dan "TITIPAN"</i></font>

		</td>

    </tr>



  <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Golongan Darah </b><br>
      <?php
      		echo "<img src=\"graph_goldarah.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
				<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
			<?php
				$sSQL = "select CONCAT('<a href=../indexpublic.php?GolDarah=',c.lst_OptionName,'  >',c.lst_OptionName,'</a>') as GolDarah, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c6 = c.lst_OptionID AND c.lst_ID = 20
						GROUP BY c.lst_OptionName";
				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilGD=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><?=$hasilGD[GolDarah]?></td>
				<td ALIGN=right><font size=2><?=$hasilGD[Jumlah]?>  jiwa</td>

				</tr>
				<?}?>
		</table>
      </td>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Status Perkawinan</b><br>
		<?php
				echo "<img src=\"graph_statuskawin.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
				<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
		<?php
				$sSQL = "select CONCAT('<a href=../indexpublic.php?Status=',c.lst_OptionID,'  >',c.lst_OptionName,'</a>')  as Status, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c15 = c.lst_OptionID AND c.lst_ID = 23 AND per_cls_ID < 3
						GROUP BY c.lst_OptionName ORDER by lst_OptionID";
				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilKawin=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><?=$hasilKawin[Status]?></td>
				<td ALIGN=right><font size=2><?=$hasilKawin[Jumlah]?>  jiwa</td>

				</tr>
				<?}?>
				</table>

      </td>
    </tr>

   <tr>
       <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
       <br><b>Bulan Kelahiran</b><br>
       <?php
       		echo "<img src=\"graph_ultah.php?&amp;$refresh \" width=\"360\" ><br>" ;
 		?>
 				<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>

 			<?php
 				$sSQL = "select CONCAT('<a href=../indexpublic.php?Bulan=',per_BirthMonth,'  >',nama_bulan,'</a>') as Bulan,
 				nama_bulan as NmBulan, 	count(per_ID) as Jumlah
 				from person_per, bulan
 				WHERE person_per.per_BirthMonth = bulan.kode AND per_cls_ID < 3
 				GROUP BY per_BirthMonth";
 				$perintah = mysql_query($sSQL);
 				$i = 0;
 				while ($hasilGD=mysql_fetch_array($perintah)){
 				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
 				?>
 				<tr class="<?php echo $sRowClass; ?>">
 				<td>*</td>
 				<td><font size=2><?=$hasilGD[Bulan]?></td>
 				<td ALIGN=right><font size=2><?=$hasilGD[Jumlah]?>  jiwa</td>

 				</tr>
 				<?}?>
 		</table>
       </td>

       <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
       <br><b>Umur Perkawinan</b><br>
 		<?php
 				echo "<img src=\"graph_umurkawin.php?&amp;$refresh \" width=\"360\" ><br>" ;
 		?>
 				<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
 		<?php
// 				$sSQL = "select CONCAT('<a href=../indexpublic.php?Status=',c.lst_OptionID,'  >',c.lst_OptionName,'</a>')  as Status, count(a.per_ID) as Jumlah
// 						from person_per a , person_custom b , list_lst c
 //						WHERE a.per_ID = b.per_ID AND b.c15 = c.lst_OptionID AND c.lst_ID = 23 AND per_cls_ID < 3
 //						GROUP BY c.lst_OptionName ORDER by lst_OptionID";
$sSQL = "SELECT
    CASE
        WHEN age BETWEEN 0 and 4 THEN 'a.  0 -  4 tahun'
        WHEN age BETWEEN 5 and 9 THEN 'b.  5 -  9 tahun'
        WHEN age BETWEEN 10 and 19 THEN 'c. 10 - 19 tahun'
        WHEN age BETWEEN 20 and 29 THEN 'd. 20 - 29 tahun'
        WHEN age BETWEEN 30 and 39 THEN 'e. 30 - 39 tahun'
        WHEN age BETWEEN 40 and 49 THEN 'f. 40 - 49 tahun'
        WHEN age BETWEEN 50 and 59 THEN 'g. 50 - 59 tahun'
        WHEN age >= 60 THEN 'h. diatas 60 tahun'
        WHEN age IS NULL THEN 'NoData'
    END as UmurNikah, count(*) as Jumlah
    FROM (SELECT TIMESTAMPDIFF(YEAR, fam_weddingdate, CURDATE() ) AS age FROM family_fam) as derived
    GROUP BY UmurNikah";
 				$perintah = mysql_query($sSQL);
 				$i = 0;
 				while ($hasilKawin=mysql_fetch_array($perintah)){
 				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
 				?>
 				<tr class="<?php echo $sRowClass; ?>">
				
 				<td> </td>
 				<td>
				<a href=../indexpublic.php?QueryID=101  >
				<font size=2><?=$hasilKawin[UmurNikah]?></td>
				</a>
 				<td ALIGN=right><font size=2><?=$hasilKawin[Jumlah]?>  keluarga</td>

 				</tr>
 				<?}?>
 				</table>
	
       </td>
     </tr>

 <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);">
      <br><b>Jenjang Pendidikan</b><br>
		<?php
		echo "<img src=\"graph_statuspendidikan.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
						<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
			<?php
				$sSQL = "select CONCAT('<a href=../indexpublic.php?Status=',c.lst_OptionID,'  >',c.lst_OptionName,'</a>') as Pendidikan, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c4 = c.lst_OptionID AND c.lst_ID = 18
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilDidik=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><?=$hasilDidik[Pendidikan]?></td>
				<td ALIGN=right><font size=2><?=$hasilDidik[Jumlah]?>  jiwa</td>

				</tr>
			<?}?>
		</table>
      </td>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
	 	<br><b>Informasi Pekerjaan</b><br>
		<?php
		echo "<img src=\"graph_statuspekerjaan.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
	 	<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
			<?php
				$sSQL = "select CONCAT('<a href=../indexpublic.php?Status=',c.lst_OptionID,'  >',c.lst_OptionName,'</a>') as Pekerjaan, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c3 = c.lst_OptionID AND c.lst_ID = 17
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilKerja=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><?=$hasilKerja[Pekerjaan]?></td>
				<td ALIGN=right><font size=2><?=$hasilKerja[Jumlah]?>  jiwa</td>

				</tr>
			<?}?>
		</table>

	 	</td>
	</tr>

    <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Jenjang Jabatan / Pangkat</b><br>
		<?php
				echo "<img src=\"graph_statusjabatan.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
			<?php
				$sSQL = "select CONCAT('<a href=../indexpublic.php?Status=',c.lst_OptionID,'  >',c.lst_OptionName,'</a>') as Jabatan, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c5 = c.lst_OptionID AND c.lst_ID = 19
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilJabatan=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><?=$hasilJabatan[Jabatan]?></td>
				<td ALIGN=right><font size=2><?=$hasilJabatan[Jumlah]?>  jiwa</td>

				</tr>
			<?}?>
		</table>
      </td>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Profesi / Keahlian</b><br>
		<?php
				echo "<img src=\"graph_statusprofesi.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
			<?php
				$sSQL = "select CONCAT('<a href=../indexpublic.php?Status=',c.lst_OptionID,'  >',c.lst_OptionName,'</a>') as Profesi, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c19 = c.lst_OptionID AND c.lst_ID = 24
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilProfesi=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><?=$hasilProfesi[Profesi]?></td>
				<td ALIGN=right><font size=2><?=$hasilProfesi[Jumlah]?>  jiwa</td>

				</tr>
			<?}?>
		</table>

      </td>
    </tr>

 <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Minat</b><br>
		<?php
			echo "<img src=\"graph_statusminat.php?&amp;$refresh \"  width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
			<?php
				$sSQL = "select CONCAT('<a href=../indexpublic.php?Status=',c.lst_OptionID,'  >',c.lst_OptionName,'</a>') as Minat, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c20 = c.lst_OptionID AND c.lst_ID = 25
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilMinat=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><?=$hasilMinat[Minat]?></td>
				<td ALIGN=right><font size=2><?=$hasilMinat[Jumlah]?>  jiwa</td>

				</tr>
			<?}?>

		</table>

      </td>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Minat Pelayanan</b><br>
      <?php
		echo "<img src=\"graph_statusminatpelayanan.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
		<?php
		$sSQL = "select CONCAT('<a href=../indexpublic.php?Status=',c.lst_OptionID,'  >',c.lst_OptionName,'</a>') as Minat, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c35 = c.lst_OptionID AND c.lst_ID = 26
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilMinat=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><?=$hasilMinat[Minat]?></td>
				<td ALIGN=right><font size=2><?=$hasilMinat[Jumlah]?>  jiwa</td>
				</tr>
				<?}?>
				</table>
      </td>

    </tr>

    <tr>
      <br><td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
		<b>Hobi</b><br>
		<?php
				echo "<img src=\"graph_statushobi.php?&amp;$refresh \"  width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
			<?php
				$sSQL = "select CONCAT('<a href=../indexpublic.php?Status=',c.lst_OptionID,'  >',c.lst_OptionName,'</a>') as Hobi, count(a.per_ID) as Jumlah
					from person_per a , person_custom b , list_lst c
					WHERE a.per_ID = b.per_ID AND b.c14 = c.lst_OptionID AND c.lst_ID = 22
					GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilHobi=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><?=$hasilHobi[Hobi]?></td>
				<td ALIGN=right><font size=2><?=$hasilHobi[Jumlah]?>  jiwa</td>

				</tr>
			<?}?>
		</table>
      </td>


      <br><td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
</td>
    </tr>

  </tbody>
</table>
<br>
<center>
    <hr>
		  catatan: Data yang tidak lengkap ditandai dengan "TIDAK ADA DATA",
		<br>dalam presentasi grafis tidak dimasukkan dalam perhitungan
    </center>

</body>



<?php

require "Include/Footer-Short.php";
?>
