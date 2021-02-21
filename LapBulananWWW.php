

<?php
/*******************************************************************************
*
* filename : BulananReportWWW.php
* last change : 2003-01-29
*
*  2008 Erwin Pratama for GKJ Bekasi WIl Timur
*  2010 Erwin Pratama for GKJ Bekasi Timur 
******************************************************************************/
$refresh = microtime() ;
// Database connection constants
$sSERVERNAME = 'mysql.lazeon.com';
$sUSER = 'gkjbekas_dbudb';
$sPASSWORD = 'p4ssdbudb;';
$sDATABASE = 'gkjbekas_db';

// Establish the database connection
$cnInfoCentral = mysql_connect($sSERVERNAME,$sUSER,$sPASSWORD)
        or die ('Cannot connect to the MySQL server because: ' . mysql_error());

mysql_select_db($sDATABASE)
        or die ('Cannot select the MySQL database because: ' . mysql_error());

$sql = "SHOW TABLES FROM $sDATABASE";
$tablecheck = mysql_num_rows( mysql_query($sql) );

// Include the function library
//require "Include/Config.php";
//require "Include/Functions.php";



// Runs an SQL query.  Returns the result resource.
// By default stop on error, unless a second (optional) argument is passed as false.
function RunQuery($sSQL, $bStopOnError = true)
{
    global $cnInfoCentral;
    global $debug;

    if ($result = mysql_query($sSQL, $cnInfoCentral))
        return $result;
    elseif ($bStopOnError)
    {
        if ($debug)
            die(gettext("Query TIDAK BISA dijalankan.") . "<p>$sSQL<p>" . mysql_error());
        else
            die("Database ERROR atau Data SALAH");
    }
    else
        return FALSE;
}





function rentang_usia ($klasumur, $umurawal, $umurakhir) {

echo "	<table border=\"0\" width=\"280\" cellspacing=0 cellpadding=0 >";
echo "	<tr>";
echo "	<td><b> > </td>";
echo "	<td><font size=2><b>Jemaat " . $klasumur . " (" . $umurawal ."-" . $umurakhir . "Th)</td> ";
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
echo "	<td><a href=\"PrintViewKelompok.php?Status=$kelompok \" target=\"_blank\">";
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
<td><font size=2><ALIGN=left> <a href=QueryView.php?QueryID=93 target=_blank>Kelahiran</a> </td>
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
<td><font size=2> <a href=QueryView.php?QueryID=97 target=_blank>Baptisan Anak(*)</a> </td>
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
<td><font size=2> <a href=QueryView.php?QueryID=99 target=_blank>Baptisan Dewasa</a> </td>
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
<td><font size=2> <a href=QueryView.php?QueryID=98 target=_blank>Sidi(*)</a> </td>
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
<td><font size=2> <a href=QueryView.php?QueryID=71 target=_blank>Pindahan/Atestasi</a> </td>
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
<td><font size=2> <a href=QueryView.php?QueryID=95 target=_blank>Titipan</a> </td>
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
<td><font size=2><ALIGN=left> <a href=QueryView.php?QueryID=91 target=_blank>Kematian</a> </td>
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
<td><font size=2> <a href=QueryView.php?QueryID=73 target=_blank>Pindah</a> </td>
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
<td><font size=2> <a href=QueryView.php?QueryID= target=_blank>Pindah Agama</a> </td>
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
    	<b>Informasi Jumlah Jemaat </b><br>
		&nbsp* Jumlah Total Jemaat : <?php echo $semua ?> jiwa<br>
		&nbsp* Jumlah Total Keluarga : <?php echo $keluarga ?> keluarga<br>
		<br>
	</td>

 <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
 <b>Informasi Atestasi </b>

 <br>

 </td>
    </tr>
    <tr>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top >
		<br><b>Informasi Jemaat Berdasarkan Rentang Usia </b><br>
 		<?php
			echo "<img src=\"graph_umurjemaat2.php?ANK=$anak&amp;RMJ=$remaja&amp;PMD=$pemuda&amp;DWS=$dewasa&amp;LNS=$lansia&amp;$refresh \" width=\"360\" ><br>" ;
		?>

 		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
 		<tr>
 		<td style="width: 210px" >&nbsp* Jumlah Jemaat Anak</td><td><?php echo $anak ?></td><td>jiwa</td>
		</tr>
		<tr>
		<td>&nbsp* Jemaat Remaja </td><td><?php echo $remaja ?></td><td>jiwa</td>
		</tr>
		<tr>
		<td>&nbsp* Jemaat Pemuda </td><td><?php echo $pemuda ?></td><td>jiwa</td>
		</tr>
		<tr>
		<td>&nbsp* Jemaat Dewasa </td><td><?php echo $dewasa ?></td><td>jiwa</td>
		</tr>
		<tr>
		<td>&nbsp* Jemaat Lansia </td><td><?php echo $lansia ?></td><td>jiwa</td>
		</tr>
		<tr>
		<td>&nbsp* Data Tidak Lengkap  </td><td><?php echo  $nodata ?></td><td>jiwa<td>
		</tr>
		</tbody></table>


		<br></td>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Informasi Berdasarkan Jenis Kelamin </b><br>
      <?php
	  	  	echo "<img src=\"graph_jeniskelamin.php?lakilaki=$laki&amp;perempuan=$perempuan&amp;$refresh \" width=\"360\" ><br>" ;
	  ?>
	  <table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
	  <?php
	  						$sSQL = "select per_Gender as Gender ,IF(per_Gender=1,\"Laki Laki</a>\",\"Perempuan</a>\") as JenisKelamin, count(per_Gender) as Jumlah from person_per group by JenisKelamin";
	  						$perintah = mysql_query($sSQL);
	  						while ($hasil=mysql_fetch_array($perintah)){
	  						?>
	  						<tr>
	  						<td>*</td><td><?=$hasil[Gender]?> <?=$hasil[JenisKelamin]?></td><td><?=$hasil[Jumlah]?></td><td>jiwa</td>
	  						</tr>
						<?}?>
	  </table>

      </td>

    </tr>

 <P CLASS="breakhere">


 <tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Anggota Jemaat per Kelompok</b><br>
		<?php
		echo "<img src=\"graph_kelompok.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
		<?php
						$sSQL = "select
								b.grp_name  as Kelompok, count(a.p2g2r_grp_id) as Anggota
								from person2group2role_p2g2r a, group_grp b, person_per c
								where a.p2g2r_grp_id = b.grp_id AND a.p2g2r_per_id = c.per_ID
								group by b.grp_id order by b.grp_name ";
						$perintah = mysql_query($sSQL);
						while ($hasil=mysql_fetch_array($perintah)){
						?>
						<tr>
						<td><?=$hasil[prn]?></td><td><?=$hasil[Kelompok]?></td><td><?=$hasil[Anggota]?></td><td>jiwa</td>
						</tr>
						<?}?>
		</table>
		</td>




      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Status Kewargaan</b><br>
      	<?php
      	echo "<img src=\"graph_statuswarga.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>
		<table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
		<?php
					$sSQL = "select  c.lst_OptionName as StatusKewargaan, count(a.per_ID) as Jumlah
							from person_per a , list_lst c
							WHERE a.per_cls_ID = c.lst_OptionID AND c.lst_ID = 1
							GROUP BY c.lst_OptionName ORDER by lst_OptionID";
						$perintah = mysql_query($sSQL);
						while ($hasilStWarga=mysql_fetch_array($perintah)){
						?>
						<tr>
						<td>*</td><td><?=$hasilStWarga[StatusKewargaan]?></td><td><?=$hasilStWarga[Jumlah]?></td>
						</tr>
				<?}?>
		</table>
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
				$sSQL = "select c.lst_OptionName as GolDarah, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c6 = c.lst_OptionID AND c.lst_ID = 20
						GROUP BY c.lst_OptionName";
				$perintah = mysql_query($sSQL);
				while ($hasilGD=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilGD[GolDarah]?></td><td><?=$hasilGD[Jumlah]?></td>
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
				$sSQL = "select c.lst_OptionName as Status, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c6 = c.lst_OptionID AND c.lst_ID = 23
						GROUP BY c.lst_OptionName ORDER by lst_OptionID";
				$perintah = mysql_query($sSQL);
				while ($hasilKawin=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilKawin[Status]?></td><td><?=$hasilKawin[Jumlah]?></td>
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
				$sSQL = "select c.lst_OptionName as Pendidikan, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c4 = c.lst_OptionID AND c.lst_ID = 18
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilDidik=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilDidik[Pendidikan]?></td><td><?=$hasilDidik[Jumlah]?></td>
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
				$sSQL = "select c.lst_OptionName  as Pekerjaan, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c3 = c.lst_OptionID AND c.lst_ID = 17
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilKerja=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilKerja[Pekerjaan]?></td><td><?=$hasilKerja[Jumlah]?></td>
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
				$sSQL = "select c.lst_OptionName as Jabatan, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c5 = c.lst_OptionID AND c.lst_ID = 19
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilJabatan=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilJabatan[Jabatan]?></td><td><?=$hasilJabatan[Jumlah]?></td>
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
				$sSQL = "select c.lst_OptionName as Profesi, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c19 = c.lst_OptionID AND c.lst_ID = 24
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilProfesi=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilProfesi[Profesi]?></td><td><?=$hasilProfesi[Jumlah]?></td>
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
				$sSQL = "select c.lst_OptionName as Minat, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c20 = c.lst_OptionID AND c.lst_ID = 25
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilMinat=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilMinat[Minat]?></td><td><?=$hasilMinat[Jumlah]?></td>
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
		$sSQL = "select c.lst_OptionName as Minat, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c35 = c.lst_OptionID AND c.lst_ID = 26
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilMinat=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilMinat[Minat]?></td><td><?=$hasilMinat[Jumlah]?></td>
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
				$sSQL = "select c.lst_OptionName as Hobi, count(a.per_ID) as Jumlah
					from person_per a , person_custom b , list_lst c
					WHERE a.per_ID = b.per_ID AND b.c14 = c.lst_OptionID AND c.lst_ID = 22
					GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilHobi=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilHobi[Hobi]?></td><td><?=$hasilHobi[Jumlah]?></td>
				</tr>
			<?}?>
		</table>


      </td>


      <br><td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <b><br>
      </b></td>
    </tr>
  </tbody>
</table>
<br>


</body>



<?php

require "Include/Footer-Short.php";
?>