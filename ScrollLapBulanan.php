<?php
/*******************************************************************************
*
* filename : LapBulanan.php
* last change : 2003-01-29
*
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2011 Erwin Pratama for GKJ Bekasi Timur (www.gljbekasitimur.org)
******************************************************************************/

$refresh = microtime() ;
// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";

//Print_r ($_SESSION);
		$logvar1 = "Report";
		$logvar2 = "Executive Summary";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $logvar1 . "','" . $logvar2 . "')";
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


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>Laporan Bulanan - Executive Summary</title>


</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >

<?php

function rentang_usia ($klasumur, $umurawal, $umurakhir) {

echo "	<table border=\"0\" width=\"280\" cellspacing=0 cellpadding=0 >";
echo "	<tr>";
echo "	<td><b> > </b></td>";
echo "	<td><font size=2><b>Jemaat " . $klasumur . " (" . $umurawal ."-" . $umurakhir . "Th)</b></font></td> ";
echo "	<td ALIGN=right> </td>";
echo "	</tr>";
		$sSQL = "SELECT CONCAT('<a href=PrintViewKlasUmur.php?GenderID=',per_Gender,'&amp;Klas=$klasumur&amp;Uawal=$umurawal&amp;Uakhir=$umurakhir&amp;lstID=1&amp;clsID=3 target=_blank >',IF(per_gender ='1', 'Laki laki', 'Perempuan'),'  ') as Jemaat , count(per_ID) as Jiwa
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
echo "	<td><font size=2>" . $i . "</font></td>";
echo "	<td><font size=2>" . $hasilGD[Jemaat] . "</a></font></td>";
echo "	<td ALIGN=right><font size=2>" . $hasilGD[Jiwa] . " jiwa</font></td></tr>";
		}
echo "	<tr>";
echo "	<td></td>";
echo "	<td><font size=2>Sub Total</font></td>";
echo "	<td ALIGN=right><font size=2>" . $total . " jiwa</font></td>";
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
echo "	<td><b> > </b></td>";
echo "	<td><a href=\"PrintViewKelompok.php?Status=$kelompok \" target=\"_blank\">";
echo "	<font size=2><b>Kelompok  " . $kelompok . "</b></font></a></td> ";
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
echo "	<td><a href=\"PrintViewKelompok.php?Status=$kelompok&Hub=$i \" target=\"_blank\"><font size=2>" . $i . "</font></a></td>";
echo "	<td><font size=2>" . $hasilGD[Status] . "</font></td>";
echo "	<td ALIGN=right><font size=2>" . $hasilGD[Jiwa] . " jiwa</font></td></tr>";
		}
echo "	<tr>";
echo "	<td></td>";
echo "	<td><font size=2>Sub Total</font></td>";
echo "	<td ALIGN=right><font size=2><b>" . $total . " jiwa</b></font></td>";
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

$sSQL = "SELECT count(per_ID) FROM person_per WHERE per_cls_ID = 1";
$perintah = mysql_query($sSQL);
$jemaataktif = mysql_result($perintah,0);

$sSQL = "SELECT count(per_ID) FROM person_per WHERE per_cls_ID = 2";
$perintah = mysql_query($sSQL);
$jemaattitipan = mysql_result($perintah,0);

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
$sSQL = "SELECT COUNT(fam_ID) FROM family_fam where fam_Email = 'Aktif' OR fam_Email = 'Titipan'";
$perintah = mysql_query($sSQL);
$keluarga = mysql_result($perintah,0);

$sSQL = "SELECT COUNT(fam_ID) FROM family_fam where fam_Email = 'Aktif' ";
$perintah = mysql_query($sSQL);
$keluargaaktif = mysql_result($perintah,0);

$sSQL = "SELECT COUNT(fam_ID) FROM family_fam where fam_Email = 'Titipan' ";
$perintah = mysql_query($sSQL);
$keluargatitipan = mysql_result($perintah,0);




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
{ return "0"; } else { return $kematiantahunini; }
}

// Get the list of Pindah Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom where (c9 is not null AND 
( c9 !='Pindah Agama' AND c9 !='PindahAgama' AND c9 !='pindah agama' AND c9 !='pindahagama' AND c9 !='Pindahagama' AND c9 !='Pindah agama')
) AND YEAR(c10) = $THNSKR";
$perintah = mysql_query($sSQL);
$sumpindah = mysql_result($perintah,0);

// Get the list of Pindah Tahun ini per bulan
function pindah($bulan,$THNSKR) {
$sSQL = "select count(*) from person_per natural join person_custom where (c9 is not null AND 
( c9 !='Pindah Agama' AND c9 !='PindahAgama' AND c9 !='pindah agama' AND c9 !='pindahagama' AND c9 !='Pindahagama' AND c9 !='Pindah agama')
) AND YEAR(c10) = $THNSKR
 AND MONTH(c10) = $bulan ";
$perintah = mysql_query($sSQL);
$pindahtahunini = mysql_result($perintah,0); 
if ( $pindahtahunini == "0" ) 
{ return "0"; } else { return $pindahtahunini; }
}


// Get the list of Pindah Agama Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom where (c9 is not null AND 
( c9 ='Pindah Agama' OR c9 ='PindahAgama' OR c9 ='pindah agama' OR c9 ='pindahagama' OR c9 ='Pindahagama' OR c9 ='Pindah agama') 
) AND YEAR(c10) = $THNSKR";
$perintah = mysql_query($sSQL);
$sumpindahagm = mysql_result($perintah,0);

// Get the list of Pindah Agama Tahun ini per bulan
function pindahagm($bulan,$THNSKR) {
$sSQL = "select count(*) from person_per natural join person_custom where (c9 is not null AND 
 ( c9 ='Pindah Agama' OR c9 ='PindahAgama' OR c9 ='pindah agama' OR c9 ='pindahagama' OR c9 ='Pindahagama' OR c9 ='Pindah agama') 
  ) AND YEAR(c10) = $THNSKR
 AND MONTH(c10) = $bulan ";
$perintah = mysql_query($sSQL);
$pindahagmtahunini = mysql_result($perintah,0); 
if ( $pindahagmtahunini == "0" ) 
{ return "0"; } else { return $pindahagmtahunini; }
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
{ return "0"; } else { return $atestasitahunini; }
}

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
{ return "0"; } else { return $baptisanak; }
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
{ return "0"; } else { return $sidhitahunini; }
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
{ return "0"; } else { return $baptisdewasa; }
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
$sSQL = "select count(*) from person_per natural join person_custom
where c15 = 2 AND per_cls_id = '1'";
$perintah = mysql_query($sSQL);
$sumjemaatdewasamarried = mysql_result($perintah,0); 

// Get the list of Jemaat Dewasa Janda/Duda Total Tahun ini
$sSQL = "select count(per_ID)
from person_per natural join person_custom
where per_cls_id = 1 AND c15 = 3 AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
AND c27 is not NULL )
OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2)";
//echo $sSQL;
//echo $perintah;
$perintah = mysql_query($sSQL);
$sumjemaatJanda = mysql_result($perintah,0); 

// Get the list of Jemaat Dewasa Nodata Total Tahun ini
$sSQL = "select count(per_ID)
from person_per natural join person_custom
where per_cls_id = 1 AND ( c15 is NULL OR c15 = 0 )AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
AND c27 is not NULL )
OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2)";
//echo $sSQL;
//echo $perintah;
$perintah = mysql_query($sSQL);
$sumjemaatND = mysql_result($perintah,0); 

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

// Get the list of Jemaat Dewasa Janda/Duda Total Tahun ini
$sSQL = "select count(per_ID)
from person_per natural join person_custom
where per_cls_id = 2 AND c15 = 3 AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
AND c27 is not NULL )
OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2)";
//echo $sSQL;
//echo $perintah;
$perintah = mysql_query($sSQL);
$sumjemaattitipJanda = mysql_result($perintah,0); 

// Get the list of Jemaat Dewasa Nodata Total Tahun ini
$sSQL = "select count(per_ID)
from person_per natural join person_custom
where per_cls_id = 2 AND ( c15 is NULL OR c15 = 0 )AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
AND c27 is not NULL )
OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2)";
//echo $sSQL;
//echo $perintah;
$perintah = mysql_query($sSQL);
$sumjemaattitipND = mysql_result($perintah,0); 


function titipan($bulan,$THNSKR) {
$sSQL = "select count(*) from person_per natural join person_custom where ( per_cls_id = '2' 
AND MONTH(per_MembershipDate) = $bulan AND 
YEAR(per_membershipdate) = $THNSKR ) AND 
(( (c2 is not NULL AND c2<>'0000-00-00 00:00:00') 
AND c27 is not NULL ) OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2 )";
//echo $sSQL;
$perintah = mysql_query($sSQL);
$jemaatdewasatitip = mysql_result($perintah,0); 
if ( $jemaatdewasatitip == "0" ) 
{ return "0"; } else { return $jemaatdewasatitip; }
}


// Get the list of Jemaat Titipan Dewasa Belum Menikah Tahun ini
$sSQL = "select count(*) from person_per natural join person_custom
where per_cls_id = '2' AND c15 = 1 AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00') AND c27 is not NULL ) 
OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL)) ";
$perintah = mysql_query($sSQL);
$sumjemaatdewasasingletitip = mysql_result($perintah,0); 
// Get the list of Jemaat Titipan Dewasa Sudah Menikah Tahun ini
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
{ return "0"; } else { return $kelahiran; }
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
  <tbody>
    <tr>
      <td style="width: 125px;"><img style="width: 90px; height: 90px;" src="gkj_logo.jpg" border="0"></td>
      <td style="width: 630px;"><b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo $sChurchName;?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font>
	<br style="font-family: Arial; color: rgb(0, 0, 102);"><b style="font-family: Arial; color: rgb(0, 0, 102);">
	<hr style="width: 100%; height: 2px;">
	<font size="2"><big style="font-family: Arial;">Laporan (Executive Sumary) - Tahun <? echo $THNSKR ?></big><br></font></b>   
	<b style="font-family: Arial;">dilaporkan bulan : <?php echo date("F, Y"); ?></b></td>
    </tr>
	<tr>
	<td align="left">
	<?
				echo "<a href=\"LapBulanan.php?Tahun=".($iTahun-1)."\"  ><p class=\"help\" title=\"Data Sebelumnya Tahun: ".($iTahun-1)." \">";
				echo "<<</a>"
	?>
	</td>
	<td></td>
	<td align="right">
		<?
				echo "<a href=\"LapBulanan.php?Tahun=".($iTahun+1)."\"  ><p class=\"help\" title=\"Data Berikutnya Tahun: ".($iTahun+1)." \">";
				echo ">></a>"
	?>
	</td>
	</tr>
  </tbody>
</table>

<table 
 style="font-family: Arial; width: 750; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="2" cellspacing="2">
 
 <tr>
  <td colspan="15" style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
          <b>Informasi Mutasi Warga </b><br></td>
 </tr>
<tr class="1">
<td><font size=2><ALIGN=center> No </font></td> 
<td><font size=2><ALIGN=left><b> Faktor Pertambahan </b></font></td>
<td><font size=2><ALIGN=center> Jan </font></td>
<td><font size=2><ALIGN=center> Feb </font></td>
<td><font size=2><ALIGN=center> Mar </font></td>
<td><font size=2><ALIGN=center> Apr </font></td>
<td><font size=2><ALIGN=center> Mei </font></td>
<td><font size=2><ALIGN=center> Jun </font></td>
<td><font size=2><ALIGN=center> Jul </font></td>
<td><font size=2><ALIGN=center> Agt </font></td>
<td><font size=2><ALIGN=center> Sep </font></td>
<td><font size=2><ALIGN=center> Okt </font></td>
<td><font size=2><ALIGN=center> Nop </font></td>
<td><font size=2><ALIGN=center> Des </font></td>
<td ALIGN="RIGHT"><font size=2> Total </font></td>
</tr>
<tr>
<td><font size=2><ALIGN=center> 1 </font></td>
<td><font size=2><ALIGN=left> <a href=QueryView.php?QueryID=93 target=_blank>Kelahiran</a> </font></td>
<?php
$i=1;
$datatemp=0;
while($i<=12)
  {
  $datatemp=kelahiran($i,$THNSKR); 
  echo "<td><font size=2> <ALIGN=center>";  echo $datatemp;  echo "</font></td>";
  $DataLahir = $DataLahir .",". $datatemp;
  $SumDataLahir = $SumDataLahir+$datatemp;
  $i++;
  }
?>
<td ALIGN="RIGHT"><font size=2><?php echo $SumDataLahir; ?>  </font></td>

</tr>
<tr class="2">
<td><font size=2> 2 </font></td>
<td><font size=2> <a href=QueryView.php?QueryID=97 target=_blank>Baptisan Anak(*)</a> </font></td>
<?php
$i=1;
$datatemp=0;
while($i<=12)
  {
  $datatemp=baptisanak($i,$THNSKR); 
  echo "<td><font size=2> <ALIGN=center>";  echo $datatemp;  echo "</font></td>";
  $DataBAnak = $DataBAnak .",". $datatemp;
  $SumDataBAnak = $SumDataBAnak+$datatemp;
  $i++;
  }
?>
<td ALIGN="RIGHT"><font size=2><?php echo $SumDataBAnak ?>   </font></td>
</tr>
<tr class="3">
<td><font size=2> 3 </font></td>
<td><font size=2> <a href=QueryView.php?QueryID=99 target=_blank>Baptisan Dewasa</a> </font></td>
<?php
$i=1;
$datatemp=0;
while($i<=12)
  {
  $datatemp=baptisdewasa($i,$THNSKR); 
  echo "<td><font size=2> <ALIGN=center>";  echo $datatemp;  echo "</font></td>";
  $DataBDewasa = $DataBDewasa .",". $datatemp;
  $SumDataBDewasa = $SumDataBDewasa+$datatemp;
  $i++;
  }
?>
<td ALIGN="RIGHT"><font size=2><?php echo $SumDataBDewasa ?>   </font></td>
</tr>
<tr>
<td><font size=2> 4 </font></td>
<td><font size=2> <a href=QueryView.php?QueryID=98 target=_blank>Sidi(*)</a> </font></td>
<?php
$i=1;
$datatemp=0;
while($i<=12)
  {
  $datatemp=sidhi($i,$THNSKR); 
  echo "<td><font size=2> <ALIGN=center>";  echo $datatemp;  echo "</font></td>";
  $DataSidhi = $DataSidhi .",". $datatemp;
  $SumDataSidhi = $SumDataSidhi+$datatemp;
  $i++;
  }
?>
<td ALIGN="RIGHT"><font size=2><?php echo $SumDataSidhi ?>  </font></td>
</tr>

<tr>
<td><font size=2> 5 </font></td>
<td><font size=2> <a href=QueryView.php?QueryID=71 target=_blank>Pindahan/Atestasi</a> </font></td>
<?php
$i=1;
$datatemp=0;
while($i<=12)
  {
  $datatemp=atestasi($i,$THNSKR); 
  echo "<td><font size=2> <ALIGN=center>";  echo $datatemp;  echo "</font></td>";
  $DataAtestasi = $DataAtestasi .",". $datatemp;
  $SumAtestasi = $SumAtestasi+$datatemp;
  $i++;
  }
?>
<td ALIGN="RIGHT"><font size=2><?php echo $SumAtestasi ; ?>   </font></td>
</tr>

<tr>
<td><font size=2> 6 </font></td>
<td><font size=2> <a href=QueryView.php?QueryID=95 target=_blank>Titipan</a> </font></td>
<?php
$i=1;
$datatemp=0;
while($i<=12)
  {
  $datatemp=titipan($i,$THNSKR); 
  echo "<td><font size=2> <ALIGN=center>";  echo $datatemp;  echo "</font></td>";
  $DataTitipan = $DataTitipan .",". $datatemp;
  $SumTitipan = $SumTitipan+$datatemp;
  $i++;
  }
?>
<td ALIGN="RIGHT"><font size=2><?php echo $SumTitipan ?>  </font></td>
</tr>
<tr>
<td colspan="15" ALIGN="CENTER" >
<font size=1><ALIGN=right>Keterangan : (*) = tidak menambah jumlah warga jemaat, hanya menambah jumlah warga baptisan anak dan warga dewasa.
</font>

<?php 
echo "
<img src=\"//chart.googleapis.com/chart?chxr=0,0,25&chxs=0,000000,13.5,0.5,lt,000000&chxt=y&chbh=a&chs=650x200&cht=bvs
&chco=FF0000,0FF00A,990066,000FF0,FF9900,F0F00F
&chds=0,20,0,20,0,20,0,20,0,20,0,20
&chd=t:".substr($DataLahir, 1)."
|".substr($DataBAnak, 1)."
|".substr($DataBDewasa, 1)."
|".substr($DataSidhi, 1)."
|".substr($DataAtestasi, 1)."
|".substr($DataTitipan, 1)."
&chdl=Kelahiran
|Baptisan+Anak
|Baptisan+Dewasa
|Sidi
|Pindahan/Atestasi
|Titipan
&chdlp=l&chg=0,-1&chma=|7,5
&chtt=Faktor+Pertambahan
&chts=000000,13.5\" 

width=\"450\" height=\"200\" alt=\"Faktor Pertambahan\" />";

echo "<img src=\"//chart.googleapis.com/chart?chxs=0,000000,10&chxt=x&chs=300x200&cht=p3
&chco=FF0000,0FF00A,990066,000FF0,FF9900,F0F00F
&chd=t:".$SumDataLahir."
,".$SumDataBAnak."
,".$SumDataBDewasa."
,".$SumDataSidhi."
,".$SumAtestasi."
,".$SumTitipan."
&chdlp=b
&chl=Kelahiran
|BaptisanAnak
|BaptisanDewasa
|Sidi
|Pindahan/Atestasi
|Titipan
&chma=50,10,50,50|10&
chtt=Prosentase Pertambahan\" width=\"300\" height=\"200\" alt=\"Prosentase Pertambahan\" />";

?>

</td></tr>
</table>

<table 
 style="font-family: Arial; width: 750; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="2" cellspacing="2">
<tr class="1">
<td><font size=2><ALIGN=center> No </font></td> 
<td><font size=2><ALIGN=left> <b>Faktor Pengurangan</b> </font></td>
<td><font size=2><ALIGN=center> Jan </font></td>
<td><font size=2><ALIGN=center> Feb </font></td>
<td><font size=2><ALIGN=center> Mar </font></td>
<td><font size=2><ALIGN=center> Apr </font></td>
<td><font size=2><ALIGN=center> Mei </font></td>
<td><font size=2><ALIGN=center> Jun </font></td>
<td><font size=2><ALIGN=center> Jul </font></td>
<td><font size=2><ALIGN=center> Agt </font></td>
<td><font size=2><ALIGN=center> Sep </font></td>
<td><font size=2><ALIGN=center> Okt </font></td>
<td><font size=2><ALIGN=center> Nop </font></td>
<td><font size=2><ALIGN=center> Des </font></td>
<td ALIGN="RIGHT"><font size=2> Total </font></td>
</tr>
<tr>
<td><font size=2><ALIGN=center> 1 </font></td>
<td><font size=2><ALIGN=left> <a href=QueryView.php?QueryID=91 target=_blank>Kematian</a> </font></td>
<?php
$i=1;
$datatemp=0;
while($i<=12)
  {
  $datatemp=kematian($i,$THNSKR); 
  echo "<td><font size=2> <ALIGN=center>";  echo $datatemp;  echo "</font></td>";
  $DataKematian = $DataKematian .",". $datatemp;
  $SumKematian = $SumKematian+$datatemp;
  $i++;
  }
?>
<td ALIGN="RIGHT"><font size=2><?php echo $SumKematian ?>  </font></td>
</tr>
<tr class="2">
<td><font size=2> 2 </font></td>
<td><font size=2> <a href=QueryView.php?QueryID=73 target=_blank>Pindah</a> </font></td>
<?php
$i=1;
$datatemp=0;
while($i<=12)
  {
  $datatemp=pindah($i,$THNSKR); 
  echo "<td><font size=2> <ALIGN=center>";  echo $datatemp;  echo "</font></td>";
  $DataPindah = $DataPindah .",". $datatemp;
  $SumPindah = $SumPindah+$datatemp;
  $i++;
  }
?>
<td ALIGN="RIGHT"><font size=2><?php echo $SumPindah ?>   </font></td>
</tr>
<tr class="3">
<td><font size=2> 3 </font></td>
<td><font size=2> <a href=QueryView.php?QueryID= target=_blank>Pindah Agama</a> </font></td>
<?php
$i=1;
$datatemp=0;
while($i<=12)
  {
  $datatemp=pindahagm($i,$THNSKR); 
  echo "<td><font size=2> <ALIGN=center>";  echo $datatemp;  echo "</font></td>";
  $DataPAgama = $DataPAgama .",". $datatemp;
  $SumPAgama = $SumPAgama+$datatemp;
  $i++;
  }
?>
<td ALIGN="RIGHT"><font size=2><?php echo $SumPAgama ?>   </font></td>
</tr>
<tr>
<td colspan="15" ALIGN="CENTER" >
<?php echo"
<img src=\"//chart.googleapis.com/chart?chxr=0,0,25&chxs=0,000000,13.5,0.5,lt,000000&chxt=y&chbh=a&chs=650x200&cht=bvs
&chco=FF0000,000FF0,F0F00F
&chds=0,20,0,20,0,20
&chd=t:".substr($DataKematian, 1)."
|".substr($DataPindah, 1)."
|".substr($DataPAgama, 1)."
&chdl=Kematian
|Pindah
|Pindah+Agama
&chdlp=l&chg=0,-1&chma=|7,5
&chtt=Faktor+Pengurangan
&chts=000000,13.5\" 

width=\"450\" height=\"200\" alt=\"Faktor Pengurangan\" />";


echo "<img src=\"//chart.googleapis.com/chart?chxs=0,000000,10&chxt=x&chs=300x200&cht=p3
&chco=FF0000,000FF0,F0F00F
&chd=t:".$SumKematian."
,".$SumPindah."
,".$SumPAgama."
&chdlp=b
&chl=Kematian
|Pindah
|Pindah+Agama
&chma=10,10,50,50|10&
chtt=Prosentase Pengurangan\" width=\"300\" height=\"200\" alt=\"Prosentase Pengurangan\" />";

?>

</td></tr>
</table>

<table style="width: 750;  text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2" valign=top>
 
    <tr>
    <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
    	<b>Informasi Jumlah Jemaat</b> <br>(Status: WARGA & TITIPAN)<br>
		<table width="80%"border=0 style="font-size: 14px; font-family: Arial; text-align: left; color: rgb(0, 0, 102);" valign=top>
		<tr><td align="left">Jumlah Total Jemaat</td><td>:</td><td align="right"><?php echo $semua ?></td><td>jiwa</td></tr>
		<tr><td align="left"><font size=2>-- Jemaat Aktif</font></td><td>:</td><td align="right"><?php echo $jemaataktif ?></td><td>jiwa</td></tr>
		<tr><td align="left"><font size=2>-- Jemaat Titipan</font></td><td>:</td><td align="right"><?php echo $jemaattitipan ?></td><td>jiwa</td></tr>
		<tr><td></td><td></td><td></td><td></td></tr>
		<tr><td align="left">Jumlah Total Keluarga</td><td>:</td><td align="right"><?php echo $keluarga ?></td><td>jiwa</td></tr>
		<tr><td align="left"><font size=2>-- Keluarga Aktif</font></td><td>:</td><td align="right"><?php echo $keluargaaktif ?></td><td>jiwa</td></tr>
		<tr><td align="left"><font size=2>-- Keluarga Titipan</font></td><td>:</td><td align="right"><?php echo $keluargatitipan ?></td><td>jiwa</td></tr>
		</table>
		<br>
		<table border=0 width=280 cellspacing=0 cellpadding=0 >

		<tr><td><font size=2> Rincian </font></td></tr>
		<tr><td> .</td></tr>
		<tr><td><font size=2>  1.<a href=QueryView.php?QueryID=68 target=_blank><b>Jemaat Dewasa Total</b><br>(Sidhi/BaptisDewasa/Menikah)</a></font></td>
		<td><ALIGN=right>
		<font size=2><?php echo $sumjemaatdewasa ?> </font></td><td><font size=2> jiwa</font></td></tr>
		<tr><td><font size=2>  ....a.<a href=QueryView.php?QueryID=168 target=_blank>Belum Menikah </a></font></td><td><ALIGN=right>
		<font size=2><?php echo $sumjemaatdewasasingle ?> </font></td><td><font size=2> jiwa</font></td></tr>
		<tr><td><font size=2>  ....b.<a href=QueryView.php?QueryID=169 target=_blank>Sudah Menikah </a></font></td><td><ALIGN=right>
		<font size=2><?php echo $sumjemaatdewasamarried ?> </font></td><td><font size=2> jiwa </font></td></tr>
		<tr><td><font size=2>  ....c.<a href=QueryView.php?QueryID=68 target=_blank>Janda / Duda</a></font></td><td><ALIGN=right>
		<font size=2><?php echo $sumjemaatJanda ?> </font></td><td><font size=2> jiwa</font></td></tr>
		<tr><td><font size=2>  ....d.<a href=QueryView.php?QueryID=68 target=_blank>Tidak Ada Data</a></font></td><td><ALIGN=right>
		<font size=2><?php echo $sumjemaatND ?> </font></td><td><font size=2> jiwa</font></td></tr>		

		<tr><td><font size=2><b>  2.<a href=QueryView.php?QueryID= target=_blank>Jemaat Dewasa Titipan Total</a></b><br>(Sidhi/BaptisDewasa/Menikah)</font></td><td><ALIGN=right>
		<font size=2><?php echo $sumjemaatdewasatitip ?> </font></td><td><font size=2> jiwa</font></td></tr>
		<tr><td><font size=2>  ....a.<a href=QueryView.php?QueryID= target=_blank>Belum Menikah</a></font></td><td><ALIGN=right>
		<font size=2><?php echo $sumjemaatdewasasingletitip ?> </font></td><td><font size=2> jiwa</font></td></tr>
		<tr><td><font size=2>  ....b.<a href=QueryView.php?QueryID= target=_blank>Sudah Menikah</a></font></td><td><ALIGN=right>
		<font size=2><?php echo $sumjemaatdewasamarriedtitip ?> </font></td><td><font size=2> jiwa</font></td></tr>
		<tr><td><font size=2>  ....c.<a href=QueryView.php?QueryID= target=_blank>Janda / Duda</a></font></td><td><ALIGN=right>
		<font size=2><?php echo $sumjemaattitipJanda ?> </font></td><td><font size=2> jiwa</font></td></tr>
		<tr><td><font size=2>  ....d.<a href=QueryView.php?QueryID= target=_blank>Tidak Ada Data</a></font></td><td><ALIGN=right>
		<font size=2><?php echo $sumjemaattitipND ?> </font></td><td><font size=2> jiwa</font></td></tr>				

		<tr><td><font size=2><b>  3.<a href=QueryView.php?QueryID=67 target=_blank>Jemaat Anak Total</a></b><br></font></td><td><ALIGN=right>
		<font size=2><?php echo $sumjemaatanak ?> </font></td><td><font size=2> jiwa</font></td></tr>


</table>
		
 <br>
		
	</td>

    <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
          <b>Informasi Mutasi Warga </b><br>
 
 
<table border=0 width=280 cellspacing=0 cellpadding=0 >

<tr><td><font size=2><b> . </b></font></td></tr>
<tr><td><font size=2><b> Bertambah </b></font></td></tr>
<tr><td><font size=2><b> . </b></font></td></tr>
<tr><td><font size=2> A. Penambahan Jemaat Dewasa </font></td></tr>
<tr><td><font size=2>  1.<a href=QueryView.php?QueryID=71 target=_blank>Attestasi Masuk</a></font></td><td><ALIGN=right>
<font size=2><?php echo $sumattestasi ?> </font></td><td><font size=2> jiwa</font></td></tr>
<tr><td><font size=2>  ---- dari <?php echo $sumattestasikeluarga ?> keluarga</font></td></tr>

<tr><td><font size=2>  2.<a href=QueryView.php?QueryID=95 target=_blank>Titipan dari Gereja lain</a></font></td><td><ALIGN=right> 
<font size=2><?php echo $sumjemaatdewasatitiptahunini ?></font></td><td><font size=2> jiwa</font></td></tr>

<tr><td><font size=2>  3.<a href=QueryView.php?QueryID=99 target=_blank>Baptis Dewasa </a></font></td><td><ALIGN=right>
<font size=2><?php echo $sumbaptisdewasa ?> </font></td><td><font size=2> jiwa</font></td></tr>

<tr><td><font size=2>  4.<a href=QueryView.php?QueryID=98 target=_blank>Sidhi </a></font></td><td><ALIGN=right>
<font size=2><?php echo $sumsidhi ?> </font></td><td><font size=2> jiwa</font></td></tr>

<tr><td><font size=2><b> . </b></font></td></tr>
<tr><td><font size=2> B. Penambahan Jemaat Anak </font></td></tr>
<tr><td><font size=2>  1.<a href=QueryView.php?QueryID=93 target=_blank>Kelahiran Tahun ini </a></font></td><td><ALIGN=right>
<font size=2><?php echo $sumjemaatlahir ?> </font></td><td><font size=2> jiwa</font></td></tr>

<tr><td><font size=2>  ---- dari <?php echo $sumjemaatlahirklg ?> keluarga </font></td><td><ALIGN=right>
<font size=2> </font></td><td><font size=2> </font></td></tr>

<tr><td><font size=2>  2.<a href=QueryView.php?QueryID=97 target=_blank>Baptis Anak </a></font></td><td><ALIGN=right>
<font size=2><?php echo $sumbaptisanak ?> </font></td><td><font size=2> jiwa</font></td></tr>

<tr><td><font size=2>  ---- dari <?php echo $sumbaptisanakklg ?> keluarga </font></td><td><ALIGN=right>
<font size=2> </font></td><td><font size=2> </font></td></tr>
<tr><td><font size=2><b> . </b></font></td></tr>
<tr><td><font size=2><b> Berkurang </b></font></td></tr>
<tr><td><font size=2> 1.<a href=QueryView.php?QueryID=73 target=_blank> Jemaat Pindah  </a></font></td><td><ALIGN=right>
<font size=2><?php echo $sumpindah ?> </font></td><td><font size=2> jiwa</font></td></tr>
<tr><td><font size=2> 2.<a href=QueryView.php?QueryID= target=_blank> Meninggal</a></font></td><td><ALIGN=right>
<font size=2><?php echo $summeninggal ?> </font></td><td><font size=2> jiwa</font></td></tr>
<tr><td><font size=2> 3.<a href=QueryView.php?QueryID= target=_blank> Jemaat Pindah Agama </a></font></td><td><ALIGN=right>
<font size=2><?php echo $sumpindahagm ?> </font></td><td><font size=2> jiwa</font></td></tr>
</table>

 </td>
    </tr>
	
	
	<tr>
	<td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
          <b>Informasi Struktur Organisasi </b><br>
		  
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">

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
				<td><font size=2><a href=QueryView.php?QueryID=85 target=_blank><?=$hasilMajelis[Jabatan]?></a></font></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</font></td>
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
				<td><font size=2><a href=QueryView.php?QueryID=86 target=_blank>Badan Pembantu Majelis</a></font></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</font></td>
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
				<td><font size=2><a href=QueryView.php?QueryID=87 target=_blank>Pengurus Kelompok</a></font></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</font></td>
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
				<td><font size=2><a href=QueryView.php?QueryID=176 target=_blank><?=$hasilMajelis[Jabatan]?></a></font></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</font></td>
				</tr>
				<?}?>		
		</table>


	</td>

	<td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
          <b>Informasi Pelayan Ibadah </b><br>
		  
	<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
	  
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
				<td><font size=2><a href=QueryView.php?QueryID=178 target=_blank><?=$hasilMajelis[Jabatan]?></a></font></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</font></td>
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
				<td><font size=2><a href=QueryView.php?QueryID=179 target=_blank><?=$hasilMajelis[Jabatan]?></a></font></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</font></td>
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
				<td><font size=2><a href=QueryView.php?QueryID=177 target=_blank><?=$hasilMajelis[Jabatan]?></a></font></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</font></td>
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
				<td><font size=2><a href=QueryView.php?QueryID=175 target=_blank><?=$hasilMajelis[Jabatan]?></a></font></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</font></td>
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
				<td><font size=2><a href=QueryView.php?QueryID=180 target=_blank><?=$hasilMajelis[Jabatan]?></a></font></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</font></td>
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
				<td><font size=2><a href=QueryView.php?QueryID=181 target=_blank><?=$hasilMajelis[Jabatan]?></a></font></td>
				<td ALIGN=right><font size=2><?=$hasilMajelis[Jumlah]?>  jiwa</font></td>
				</tr>
				<?}?>
				
		</table>
		
	</td>
	</tr>


</table>
<p style="page-break-before: always">.</p>
<table style="width: 750;  text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2" valign=top>	
	
	
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
		//require "gtest.php";
			echo "<img src=\"graph_umurjemaat2.php?BLT=$thestring[Balita]&amp;ANK=$thestring[Anak]&amp;
			RMJ=$thestring[Remaja]&amp;PMD=$thestring[Pemuda]&amp;DWS=$thestring[Dewasa]&amp;
			LNS=$thestring[Lansia]&amp;$refresh \" width=\"360\" ><br>" ;

		?>
		<font size="1"><i>* Catatan: Data berikut adalah Jemat yang berstatus "WARGA" dan "TITIPAN"</i></font>

		<br>
	
		
		</td>

    </tr>


<tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Status Kewargaan</b><br>
      	<?php
      	echo "<img src=\"graph_statuswarga.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">

		<?php
		$sSQL = "select  CONCAT('<a href=PrintViewStatusWarga.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as StatusKewargaan, count(a.per_ID) as Jumlah
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
				<td><font size=2><?=$hasilStWarga[StatusKewargaan]?></font></td>
				<td ALIGN=right><font size=2><?=$hasilStWarga[Jumlah]?>  jiwa</font></td>
				</tr>
				<?}?>
		</table>
		</td>


      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Informasi Berdasarkan Jenis Kelamin </b><br>
      <?php
	  	  	echo "<img src=\"graph_jeniskelamin.php?lakilaki=$laki&amp;perempuan=$perempuan&amp;$refresh \" width=\"360\" ><br>" ;
	  ?>
	  <table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
	  <?php
  		$sSQL = "select CONCAT('<a href=PrintViewGender.php?GenderID=',per_Gender,' target=_blank >',per_Gender,'  ') as Gender ,
			IF(per_Gender=1,\"Laki Laki</a>\",\"Perempuan</a>\") as JenisKelamin, count(per_Gender) as Jumlah from person_per
			WHERE per_cls_ID < 3 group by JenisKelamin
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
  				<td><font size=2><?=$hasil[Gender]?> <?=$hasil[JenisKelamin]?></font></td>
  				<td ALIGN=right><font size=2><?=$hasil[Jumlah]?>  jiwa</font></td>
  				<td><font size=2>jiwa</font></td>
  				</tr>
				<?}?>
	  </table>

      </td>
</tr>


</table>
<p style="page-break-before: always">.</p>
<table style="width: 750;  text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2" valign=top>	

 <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Anggota Jemaat per Kelompok</b><br>
				<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
				<?php
					//$sSQL = "select per_workphone as Kelompok from person_per group by per_workphone";
					$sSQL = "select trim(per_workphone) as Kelompok from person_per group by trim(per_workphone)";
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


</table>
<p style="page-break-before: always">.</p>
<table style="width: 750;  text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2" valign=top>	


  <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Golongan Darah </b><br>
      <?php
      		echo "<img src=\"graph_goldarah.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
				<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
			<?php
//				$sSQL = "select CONCAT('<a href=PrintViewGolDarah.php?GolDarah=',c.lst_OptionName,' target=_blank>',c.lst_OptionName,'</a>') as GolDarah, count(a.per_ID) as Jumlah
//						from person_per a , person_custom b , list_lst c
//						WHERE a.per_ID = b.per_ID AND b.c6 = c.lst_OptionID AND c.lst_ID = 20
//						GROUP BY c.lst_OptionName";
$sSQL = "select CONCAT('<a href=PrintViewReport.php?clsID=3','&amp;','lstID=20&amp;','lstOptionID=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as GolDarah, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c6 = c.lst_OptionID AND c.lst_ID = 20 AND per_cls_id < 3
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilGD=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><?=$hasilGD[GolDarah]?></font></td>
				<td ALIGN=right><font size=2><?=$hasilGD[Jumlah]?>  jiwa</font></td>

				</tr>
				<?}?>
		</table>
      </td>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Status Perkawinan</b><br>
		<?php
				echo "<img src=\"graph_statuskawin.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
				<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
		<?php
//				$sSQL = "select CONCAT('<a href=PrintViewStatusKawin.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>')  as Status, count(a.per_ID) as Jumlah
//						from person_per a , person_custom b , list_lst c
//						WHERE a.per_ID = b.per_ID AND b.c15 = c.lst_OptionID AND c.lst_ID = 23 AND per_cls_ID < 3
//						GROUP BY c.lst_OptionName ORDER by lst_OptionID";

$sSQL = "select CONCAT('<a href=PrintViewReport.php?clsID=3','&amp;','lstID=23&amp;','lstOptionID=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Status, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c15 = c.lst_OptionID AND c.lst_ID = 23 AND per_cls_id < 3
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilKawin=mysql_fetch_array($perintah)){
				$i++;
				//Alternate the row color
				$sRowClass = AlternateRowStyle($sRowClass);
				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td>*</td>
				<td><font size=2><?=$hasilKawin[Status]?></font></td>
				<td ALIGN=right><font size=2><?=$hasilKawin[Jumlah]?>  jiwa</font></td>

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
 				<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">

 			<?php
 				$sSQL = "select CONCAT('<a href=PrintViewUltah.php?Bulan=',per_BirthMonth,' target=_blank>',nama_bulan,'</a>') as Bulan,
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
 				<td><font size=2><?=$hasilGD[Bulan]?></font></td>
 				<td ALIGN=right><font size=2><?=$hasilGD[Jumlah]?>  jiwa</font></td>

 				</tr>
 				<?}?>
 		</table>
       </td>

       <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
       <br><b>Umur Perkawinan</b><br>
 		<?php
 				echo "<img src=\"graph_umurkawin.php?&amp;$refresh \" width=\"360\" ><br>" ;
 		?>
 				<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
 		<?php
// 				$sSQL = "select CONCAT('<a href=PrintViewStatusKawin.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>')  as Status, count(a.per_ID) as Jumlah
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
				<a href=QueryView.php?QueryID=101 target=_blank>
				<font size=2><?=$hasilKawin[UmurNikah]?></font>
				</a></td>
 				<td ALIGN=right><font size=2><?=$hasilKawin[Jumlah]?>  keluarga</font></td>

 				</tr>
 				<?}?>
 				</table>
	
       </td>
     </tr>

	 
	 
</table>
<p style="page-break-before: always">.</p>
<table style="width: 750;  text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2" valign=top>	


 <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);">
      <b>Jenjang Pendidikan</b><br>
		<?php
		echo "<img src=\"graph_statuspendidikan.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
						<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
			<?php
//				$sSQL = "select CONCAT('<a href=PrintViewPendidikan.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Pendidikan, count(a.per_ID) as Jumlah
//						from person_per a , person_custom b , list_lst c
//						WHERE a.per_ID = b.per_ID AND b.c4 = c.lst_OptionID AND c.lst_ID = 18
//						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
$sSQL = "select CONCAT('<a href=PrintViewReport.php?clsID=3','&amp;','lstID=18&amp;','lstOptionID=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Pendidikan, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c4 = c.lst_OptionID AND c.lst_ID = 18 AND per_cls_id < 3
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
				<td><font size=2><?=$hasilDidik[Pendidikan]?></font></td>
				<td ALIGN=right><font size=2><?=$hasilDidik[Jumlah]?>  jiwa</font></td>

				</tr>
			<?}?>
		</table>
      </td>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
	 	<br><b>Informasi Pekerjaan</b><br>
		<?php
		echo "<img src=\"graph_statuspekerjaan.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
	 	<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
			<?php
//				$sSQL = "select CONCAT('<a href=PrintViewPekerjaan.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Pekerjaan, count(a.per_ID) as Jumlah
//						from person_per a , person_custom b , list_lst c
//						WHERE a.per_ID = b.per_ID AND b.c3 = c.lst_OptionID AND c.lst_ID = 17
//						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
$sSQL = "select CONCAT('<a href=PrintViewReport.php?clsID=3','&amp;','lstID=17&amp;','lstOptionID=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Pekerjaan, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c3 = c.lst_OptionID AND c.lst_ID = 17 AND per_cls_id < 3
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
				<td><font size=2><?=$hasilKerja[Pekerjaan]?></font></td>
				<td ALIGN=right><font size=2><?=$hasilKerja[Jumlah]?>  jiwa</font></td>

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
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
			<?php
//				$sSQL = "select CONCAT('<a href=PrintViewPangkat.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Jabatan, count(a.per_ID) as Jumlah
//						from person_per a , person_custom b , list_lst c
//						WHERE a.per_ID = b.per_ID AND b.c5 = c.lst_OptionID AND c.lst_ID = 19
//						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
$sSQL = "select CONCAT('<a href=PrintViewReport.php?clsID=3','&amp;','lstID=19&amp;','lstOptionID=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Jabatan, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c5 = c.lst_OptionID AND c.lst_ID = 19 AND per_cls_id < 3
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
				<td><font size=2><?=$hasilJabatan[Jabatan]?></font></td>
				<td ALIGN=right><font size=2><?=$hasilJabatan[Jumlah]?>  jiwa</font></td>

				</tr>
			<?}?>
		</table>
      </td>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Profesi / Keahlian</b><br>
		<?php
				echo "<img src=\"graph_statusprofesi.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
			<?php
//				$sSQL = "select CONCAT('<a href=PrintViewProfesi.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Profesi, count(a.per_ID) as Jumlah
//						from person_per a , person_custom b , list_lst c
//						WHERE a.per_ID = b.per_ID AND b.c19 = c.lst_OptionID AND c.lst_ID = 24
//						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
$sSQL = "select CONCAT('<a href=PrintViewReport.php?clsID=3','&amp;','lstID=24&amp;','lstOptionID=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Profesi, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c19 = c.lst_OptionID AND c.lst_ID = 24 AND per_cls_id < 3
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
				<td><font size=2><?=$hasilProfesi[Profesi]?></font></td>
				<td ALIGN=right><font size=2><?=$hasilProfesi[Jumlah]?>  jiwa</font></td>

				</tr>
			<?}?>
		</table>

      </td>
    </tr>

	
	
</table>
<p style="page-break-before: always">.</p>
<table style="width: 750;  text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2" valign=top>	



 <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Minat</b><br>
		<?php
			echo "<img src=\"graph_statusminat.php?&amp;$refresh \"  width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
			<?php
//			$sSQL = "select CONCAT('<a href=PrintViewMinat.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Minat, count(a.per_ID) as Jumlah
//				from person_per a , person_custom b , list_lst c
//				WHERE a.per_ID = b.per_ID AND b.c20 = c.lst_OptionID AND c.lst_ID = 25
//				GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
			$sSQL = "select CONCAT('<a href=PrintViewReport.php?clsID=3','&amp;','lstID=25&amp;','lstOptionID=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Minat, count(a.per_ID) as Jumlah
				from person_per a , person_custom b , list_lst c
				WHERE a.per_ID = b.per_ID AND b.c20 = c.lst_OptionID AND c.lst_ID = 25 AND per_cls_id < 3
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
				<td><font size=2><?=$hasilMinat[Minat]?></font></td>
				<td ALIGN=right><font size=2><?=$hasilMinat[Jumlah]?>  jiwa</font></td>

				</tr>
			<?}?>

		</table>

      </td>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Minat Pelayanan</b><br>
      <?php
		echo "<img src=\"graph_statusminatpelayanan.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
		<?php
//		$sSQL = "select CONCAT('<a href=PrintViewMinatPelayanan.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Minat, count(a.per_ID) as Jumlah
//						from person_per a , person_custom b , list_lst c
//						WHERE a.per_ID = b.per_ID AND b.c35 = c.lst_OptionID AND c.lst_ID = 26
//						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
		$sSQL = "select CONCAT('<a href=PrintViewReport.php?clsID=3','&amp;','lstID=26&amp;','lstOptionID=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Minat, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c35 = c.lst_OptionID AND c.lst_ID = 26 AND per_cls_id < 3
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
				<td><font size=2><?=$hasilMinat[Minat]?></font></td>
				<td ALIGN=right><font size=2><?=$hasilMinat[Jumlah]?>  jiwa</font></td>
				</tr>
				<?}?>
				</table>
      </td>

    </tr>

</table>
<p style="page-break-before: always">.</p>
<table style="width: 750;  text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2" valign=top>	

    <tr>
      <br><td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
		<b>Hobi</b><br>
		<?php
				echo "<img src=\"graph_statushobi.php?&amp;$refresh \"  width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0">
			<?php
//				$sSQL = "select CONCAT('<a href=PrintViewHobi.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Hobi, count(a.per_ID) as Jumlah
//					from person_per a , person_custom b , list_lst c
//					WHERE a.per_ID = b.per_ID AND b.c14 = c.lst_OptionID AND c.lst_ID = 22
//					GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";

				$sSQL = "select CONCAT('<a href=PrintViewReport.php?clsID=3','&amp;','lstID=22&amp;','lstOptionID=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Hobi, count(a.per_ID) as Jumlah
					from person_per a , person_custom b , list_lst c
					WHERE a.per_ID = b.per_ID AND b.c14 = c.lst_OptionID AND c.lst_ID = 22 AND per_cls_id < 3
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
				<td><font size=2><?=$hasilHobi[Hobi]?></font></td>
				<td ALIGN=right><font size=2><?=$hasilHobi[Jumlah]?>  jiwa</font></td>

				</tr>
			<?}?>
		</table>
      </td>


      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
</td>
 </tr>



</table>
<br>
<center>
    <hr>
		  catatan: Data yang tidak lengkap ditandai dengan "TIDAK ADA DATA",
		<br>dalam presentasi grafis tidak dimasukkan dalam perhitungan
		<br> <?// echo $TGLSKR; ?> 
		<br> <?// echo $THNSKR; ?>
    </center>

</body>



<?php

//require "Include/Footer-Short.php";
?>
