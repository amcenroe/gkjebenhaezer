<?php
/*******************************************************************************
*
* filename : LapBulanan_att.php
* last change : 2003-01-29
*
*  2008 Erwin Pratama for GKJ Bekasi WIl Timur
******************************************************************************/


$refresh = microtime() ;
// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";

//Print_r ($_SESSION);
		$logvar1 = "Report";
		$logvar2 = "Executive Summary Mutasi";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

			
$iTahun = $_GET["Tahun"];

if ($iTahun == '') {
    $TahunIni=date("Y");;

} else {
    $TahunIni = $_GET["Tahun"];;
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>Laporan Bulanan - Executive Summary 3</title>

  <STYLE TYPE="text/css">
        P.breakhere {page-break-before: always}
</STYLE>

</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >

<?php

function meninggal ($bulan, $tahun, $total) {
if ( $total == "Ya" ) {
	$sSQL = "select count(*) as Jumlah
					from person_per natural join person_custom
					where per_cls_id = 7
					and YEAR(c41) = $tahun
					";
} else {
	$sSQL = "select 
							         CASE count(*)
						 		WHEN '0' THEN '-'
								ELSE count(*)
							END AS Jumlah
					from person_per natural join person_custom
					where per_cls_id = 7
					and MONTH(c41) = $bulan
					and YEAR(c41) = $tahun
					";
	}
		$perintah = mysql_query($sSQL);
		$i = 0;
		$total = 0;
		while ($hasilGD=mysql_fetch_array($perintah))
		{
		$i++;
		$total = ($total + $hasilGD[Jumlah]);
						extract($hasilGD);
						//Alternate the row color
		                  $sRowClass = AlternateRowStyle($sRowClass);

echo $hasilGD[Jumlah] ;
}
}

function lahir ($klasid, $bulan, $tahun, $total) {
if ( $total == "Ya" ) {
	$sSQL = "select  count(*) as Jumlah
					from person_per natural join person_custom
					where (per_cls_id = 5 OR per_cls_id = $klasid)
					and per_BirthYear = $tahun
					
					";
} else {
	$sSQL = "select 
							         CASE count(*)
						 		WHEN '0' THEN '-'
								ELSE count(*)
							END AS Jumlah
					from person_per natural join person_custom
					where (per_cls_id = 5 OR per_cls_id = $klasid)
					and per_BirthMonth = $bulan
					and per_BirthYear = $tahun
					";
	}
		$perintah = mysql_query($sSQL);
		$i = 0;
		$totaldata = 0;
		while ($hasilGD=mysql_fetch_array($perintah))
		{
		$i++;
		$totaldata = ($totaldata + $hasilGD[Jumlah]);
						extract($hasilGD);
						//Alternate the row color
		                  $sRowClass = AlternateRowStyle($sRowClass);
echo "<td ALIGN=right><font size=2>";
echo $hasilGD[Jumlah] ;
echo "</td>";
}
}

function attestasi ($klasid, $kolom, $bulan, $tahun, $total) {
if ( $total == "Ya" ) {
	$sSQL = "select  count(*) as Jumlah 
					from person_per natural join person_custom
					where per_cls_id = $klasid
					and YEAR($kolom) = $tahun
					and c8 is not null
					";
} else {
	$sSQL = "select 
						         CASE count(*)
						 		WHEN '0' THEN '-'
								ELSE count(*)
							END AS Jumlah
					from person_per natural join person_custom
					where per_cls_id = $klasid
					and MONTH($kolom) = $bulan
					and YEAR($kolom) = $tahun
					and c8 is not null
					";
			//	echo $sSQL;	
	}

		$perintah = mysql_query($sSQL);
		$i = 0;
		$total = 0;
		while ($hasilGD=mysql_fetch_array($perintah))
		{
		$i++;
		$total = ($total + $hasilGD[Jumlah]);
						extract($hasilGD);
						//Alternate the row color
		                  $sRowClass = AlternateRowStyle($sRowClass);
echo "<td ALIGN=right><font size=2>";
echo $hasilGD[Jumlah] ;
echo "</td>";
}
}

function mutasi ($klasid, $kolom, $bulan, $tahun, $total) {
if ( $total == "Ya" ) {
	$sSQL = "select count(*) as Jumlah
					from person_per natural join person_custom
					where per_cls_id = $klasid
					and YEAR($kolom) = $tahun
					";
} else {
	$sSQL = "select 
							         CASE count(*)
						 		WHEN '0' THEN '-'
								ELSE count(*)
							END AS Jumlah
					from person_per natural join person_custom
					where per_cls_id = $klasid
					and MONTH($kolom) = $bulan
					and YEAR($kolom) = $tahun
					";
	}
		$perintah = mysql_query($sSQL);
		$i = 0;
		$total = 0;
		while ($hasilGD=mysql_fetch_array($perintah))
		{
		$i++;
		$total = ($total + $hasilGD[Jumlah]);
						extract($hasilGD);
						//Alternate the row color
		                  $sRowClass = AlternateRowStyle($sRowClass);
echo "<td ALIGN=right><font size=2>";
echo $hasilGD[Jumlah] ;
echo "</td>";
}
}



//get last edited
$sSQL = "SELECT per_datelastedited FROM `person_per` order by per_datelastedited desc limit 1";
$perintah = mysql_query($sSQL);
$editterakhir = mysql_result($perintah,0);

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
	<?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);"><b style="font-family: Arial; color: rgb(0, 0, 102);">
	<hr style="width: 100%; height: 2px;">
	<font size="2"><big style="font-family: Arial;">Laporan Bulanan (Executive Sumary)</big><br></font></b>   
	<b style="font-family: Arial;">Bulan : <?php echo date("F, Y"); ?> , Diupdate Terakhir : <?php echo $editterakhir ?></b></td>
    </tr>
  </tbody>
</table>


<table style="width: 750;  text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2" valign=top>
  <tbody>

<tr>

 <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
 <b>Informasi Mutasi </b>
 <br> Jemaat Bertambah
<table table style="text-align: left; width: 100%;" border="0" cellpadding="5" cellspacing="0">
<tr>
<td ALIGN=center ><font size=2><b>Thn:<? echo $TahunIni ?></b></td>
<td ALIGN=center><font size=2><b>Jan</b></td>
<td ALIGN=center><font size=2><b>Feb</b></td>
<td ALIGN=center><font size=2><b>Mar</b></td>
<td ALIGN=center><font size=2><b>Apr</b></td>
<td ALIGN=center><font size=2><b>Mei</b></td>
<td ALIGN=center><font size=2><b>Jun</b></td>
<td ALIGN=center><font size=2><b>Jul</b></td>
<td ALIGN=center><font size=2><b>Agt</b></td>
<td ALIGN=center><font size=2><b>Sep</b></td>
<td ALIGN=center><font size=2><b>Okt</b></td>
<td ALIGN=center><font size=2><b>Nop</b></td>
<td ALIGN=center><font size=2><b>Des</b></td>
<td ALIGN=center><font size=2><b>Total</b></td>
</tr>

<tr class="1" BGCOLOR="#E0ECF8">
<td ALIGN=left><font size=2> Kelahiran:</td>
<?php
		$i = 0;
		while ( $i < 12 )
		{
		$i++;
		lahir(1, $i,$TahunIni,Tidak);
		}
		lahir (1, 0,$TahunIni,Ya)
?>
</tr>

<tr class="2">
<td ALIGN=left><font size=2> BaptisAnak:</td>
<?php
		$i = 0;
		while ( $i < 12 )
		{
		$i++;
		mutasi (1, c1, $i,$TahunIni,Tidak);
		}
		mutasi (1, c1, 0,$TahunIni,Ya);
?>
</tr>


<tr class="1" BGCOLOR="#E0ECF8">
<td ALIGN=left><font size=2> BaptisDewasa:</td>
<?php
		$i = 0;
		while ( $i < 12 )
		{
		$i++;
		mutasi (1, c18, $i,$TahunIni,Tidak);
		}
		mutasi (1, c18, 0,$TahunIni,Ya);
?>
</tr>
<tr class="4">
<td ALIGN=left><font size=2> AttestasiMasuk:</td>
<?php
		$i = 0;
		while ( $i < 12 )
		{
		$i++;
		attestasi (1, per_MembershipDate, $i,$TahunIni,Tidak);
		}
		attestasi (1, per_MembershipDate, 0,$TahunIni,Ya);
?>
</tr>
<tr class="1" BGCOLOR="#E0ECF8">
<td ALIGN=left><font size=2> Titipan:</td>
<?php
		$i = 0;
		while ( $i < 12 )
		{
		$i++;
		mutasi(2, per_MembershipDate, $i,$TahunIni,Tidak);
		}
		mutasi (2, per_MembershipDate, 0,$TahunIni,Ya)
?>
</tr>

</table> 
 <br>
Jemaat Berkurang
 <table table style="text-align: left; width: 100%;" border="0" cellpadding="5" cellspacing="0">
<tr>
<td ALIGN=center><font size=2><b>Thn:<? echo $TahunIni ?></b></td>
<td ALIGN=center><font size=2><b>Jan</b></td>
<td ALIGN=center><font size=2><b>Feb</b></td>
<td ALIGN=center><font size=2><b>Mar</b></td>
<td ALIGN=center><font size=2><b>Apr</b></td>
<td ALIGN=center><font size=2><b>Mei</b></td>
<td ALIGN=center><font size=2><b>Jun</b></td>
<td ALIGN=center><font size=2><b>Jul</b></td>
<td ALIGN=center><font size=2><b>Agt</b></td>
<td ALIGN=center><font size=2><b>Sep</b></td>
<td ALIGN=center><font size=2><b>Okt</b></td>
<td ALIGN=center><font size=2><b>Nop</b></td>
<td ALIGN=center><font size=2><b>Des</b></td>
<td ALIGN=center><font size=2><b>Total</b></td>
</tr>
<tr class="1" BGCOLOR="#E0ECF8">
<td ALIGN=left><font size=2>Pindah :</td>
<?php
		$i = 0;
		while ( $i < 12 )
		{
		$i++;
		mutasi (6, c10, $i,$TahunIni,Tidak);
		}
		mutasi (6, c10, 0,$TahunIni,Ya);
?>
</tr>
<tr class="2">
<td ALIGN=left><font size=2>Meninggal :</td>
<?php
		$i = 0;
		while ( $i < 12 )
		{
		$i++;
		mutasi (7, c41, $i,$TahunIni,Tidak);
		}
		mutasi (7, c41, 0,$TahunIni,Ya);
?>
</tr>    

</tr>


<tr>
 
  </tbody>
</table>
<br>
 <b>Informasi Detail Mutasi </b>
 <br>++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  <br> Kelahiran
			<table border="0"  width="750" cellspacing=0 cellpadding=0 >

			<tr>
			<td ALIGN=center><font size=2><b>No</b></td>
			<td ALIGN=center><font size=2><b>ID</b></td>
			<td ALIGN=center><font size=2><b>Hub</b></td>
			<td ALIGN=center><font size=2><b>Gen</b></td>
			<td ALIGN=center><font size=2><b>Nama Lengkap</b></td>
			<td ALIGN=center><font size=2><b>ID.Klg</b></td>
			<td ALIGN=center><font size=2><b>Nama Kep Keluarga</b></td>
			<td ALIGN=center><font size=2><b>Tgl Lahir</b></td>
			<td ALIGN=center><font size=2><b>Klpk</b></td>
			<td ALIGN=center><font size=2><b>Status</b></td>
			</tr>
			<?php
						
				$sSQL = "select IF(per_gender ='1', 'L', 'P') as Gender,
				        CONCAT('<a href=PersonView.php?PersonID=',per_ID,'>',per_ID,'</a>') AS ID,
						c.lst_OptionName as StatusKewargaan, a.per_FirstName as Nama ,
						CONCAT('<a href=FamilyView.php?FamilyID=',per_fam_ID,'>',per_fam_ID,'</a>') AS ID_Kelg,
						b.fam_Name as NM_Kelg, 
				         per_HomePhone as TelpRumah, per_BirthYear as TahunLahir ,
				         CASE per_fmr_id
						 						 	WHEN '1' THEN 'KK'
						 						 	WHEN '2' THEN 'Ist'
						 						 	WHEN '3' THEN 'Ank'
						 						 	WHEN '4' THEN 'Sdr'
						END AS St_Kelg,per_birthDay as TGL, per_birthMonth as BLN, per_birthYear as THN,
				         per_WorkPhone as Kelompok ,per_Cellphone as Handphone
				         FROM person_per a , list_lst c, family_fam b
							WHERE a.per_fam_ID = b.fam_ID AND 
							a.per_cls_ID = c.lst_OptionID AND c.lst_ID = '1'
							AND per_BirthYear = '$TahunIni'
							ORDER BY a.per_WorkPhone, per_fam_ID, per_fmr_id, a.per_firstname";
//							echo $sSQL ;
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
				<td><font size=2><? echo $i ?></td>
				<td><font size=2><center><?=$hasilGD[ID]?></center></td>
				<td><font size=2><center><?=$hasilGD[St_Kelg]?></td>
				<td><font size=2><center><?=$hasilGD[Gender]?></center></td>
				<td><font size=2><?=$hasilGD[Nama]?></td>
				<td><font size=2><center><?=$hasilGD[ID_Kelg]?></center></td>
				<td><font size=2><?=$hasilGD[NM_Kelg]?></td>
				<td><font size=2><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
				<td><font size=2><?=$hasilGD[Kelompok]?></td>
				<td><font size=2><?=$hasilGD[StatusKewargaan]?></td>
				</tr>
				<?}?>
			</table>

  <br> Baptis Anak
			<table border="0"  width="750" cellspacing=0 cellpadding=0 >

			<tr>
			<td ALIGN=center><font size=2><b>No</b></td>
			<td ALIGN=center><font size=2><b>ID</b></td>
			<td ALIGN=center><font size=2><b>Hub</b></td>
			<td ALIGN=center><font size=2><b>Gen</b></td>
			<td ALIGN=center><font size=2><b>Nama Lengkap</b></td>
			<td ALIGN=center><font size=2><b>ID.Klg</b></td>
			<td ALIGN=center><font size=2><b>Nama Kep Keluarga</b></td>
			<td ALIGN=center><font size=2><b>Tgl Baptis</b></td>
			<td ALIGN=center><font size=2><b>Klpk</b></td>
			<td ALIGN=center><font size=2><b>Status</b></td>
			</tr>
			<?php

//	$sSQL = "select count(*) as Jumlah
//					from person_per natural join person_custom
//					where per_cls_id = $klasid
//					and MONTH($kolom) = $bulan
//					and YEAR($kolom) = $tahun
//					";
//	}

			
				$sSQL = "select IF(per_gender ='1', 'L', 'P') as Gender,
				        CONCAT('<a href=PersonView.php?PersonID=',a.per_ID,'>',a.per_ID,'</a>') AS ID,
						c.lst_OptionName as StatusKewargaan, a.per_FirstName as Nama ,
						CONCAT('<a href=FamilyView.php?FamilyID=',per_fam_ID,'>',per_fam_ID,'</a>') AS ID_Kelg,
						b.fam_Name as NM_Kelg, 
				        per_BirthYear as TahunLahir ,
				         CASE per_fmr_id
						 						 	WHEN '1' THEN 'KK'
						 						 	WHEN '2' THEN 'Ist'
						 						 	WHEN '3' THEN 'Ank'
						 						 	WHEN '4' THEN 'Sdr'
						END AS St_Kelg,
						DAY(d.c1) as TGL, MONTH(d.c1) as BLN, YEAR(d.c1) as THN,
				         per_WorkPhone as Kelompok ,per_Cellphone as Handphone
				         FROM person_per a ,person_custom d , list_lst c, family_fam b
							WHERE a.per_fam_ID = b.fam_ID AND a.per_id = d.per_id AND 
							a.per_cls_ID = c.lst_OptionID AND c.lst_ID = '1'
							and YEAR(d.c1) = '$TahunIni'
							ORDER BY a.per_WorkPhone, per_fam_ID, per_fmr_id, a.per_firstname";
//							echo $sSQL ;
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
				<td><font size=2><? echo $i ?></td>
				<td><font size=2><center><?=$hasilGD[ID]?></center></td>
				<td><font size=2><center><?=$hasilGD[St_Kelg]?></td>
				<td><font size=2><center><?=$hasilGD[Gender]?></center></td>
				<td><font size=2><?=$hasilGD[Nama]?></td>
				<td><font size=2><center><?=$hasilGD[ID_Kelg]?></center></td>
				<td><font size=2><?=$hasilGD[NM_Kelg]?></td>
				<td><font size=2><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
				<td><font size=2><?=$hasilGD[Kelompok]?></td>
				<td><font size=2><?=$hasilGD[StatusKewargaan]?></td>
				</tr>
				<?}?>
			</table>

 <br> Baptis Dewasa
			<table border="0"  width="750" cellspacing=0 cellpadding=0 >

			<tr>
			<td ALIGN=center><font size=2><b>No</b></td>
			<td ALIGN=center><font size=2><b>ID</b></td>
			<td ALIGN=center><font size=2><b>Hub</b></td>
			<td ALIGN=center><font size=2><b>Gen</b></td>
			<td ALIGN=center><font size=2><b>Nama Lengkap</b></td>
			<td ALIGN=center><font size=2><b>ID.Klg</b></td>
			<td ALIGN=center><font size=2><b>Nama Kep Keluarga</b></td>
			<td ALIGN=center><font size=2><b>Tgl Baptis</b></td>
			<td ALIGN=center><font size=2><b>Klpk</b></td>
			<td ALIGN=center><font size=2><b>Status</b></td>
			</tr>
			<?php

//	$sSQL = "select count(*) as Jumlah
//					from person_per natural join person_custom
//					where per_cls_id = $klasid
//					and MONTH($kolom) = $bulan
//					and YEAR($kolom) = $tahun
//					";
//	}

			
				$sSQL = "select IF(per_gender ='1', 'L', 'P') as Gender,
				        CONCAT('<a href=PersonView.php?PersonID=',a.per_ID,'>',a.per_ID,'</a>') AS ID,
						c.lst_OptionName as StatusKewargaan, a.per_FirstName as Nama ,
						CONCAT('<a href=FamilyView.php?FamilyID=',per_fam_ID,'>',per_fam_ID,'</a>') AS ID_Kelg,
						b.fam_Name as NM_Kelg, 
				        per_BirthYear as TahunLahir ,
				         CASE per_fmr_id
						 						 	WHEN '1' THEN 'KK'
						 						 	WHEN '2' THEN 'Ist'
						 						 	WHEN '3' THEN 'Ank'
						 						 	WHEN '4' THEN 'Sdr'
						END AS St_Kelg,
						DAY(d.c18) as TGL, MONTH(d.c18) as BLN, YEAR(d.c18) as THN,
				         per_WorkPhone as Kelompok ,per_Cellphone as Handphone
				         FROM person_per a ,person_custom d , list_lst c, family_fam b
							WHERE a.per_fam_ID = b.fam_ID AND a.per_id = d.per_id AND 
							a.per_cls_ID = c.lst_OptionID AND c.lst_ID = '1'
							and YEAR(d.c18) = '$TahunIni'
							ORDER BY a.per_WorkPhone, per_fam_ID, per_fmr_id, a.per_firstname";
//							echo $sSQL ;
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
				<td><font size=2><? echo $i ?></td>
				<td><font size=2><center><?=$hasilGD[ID]?></center></td>
				<td><font size=2><center><?=$hasilGD[St_Kelg]?></td>
				<td><font size=2><center><?=$hasilGD[Gender]?></center></td>
				<td><font size=2><?=$hasilGD[Nama]?></td>
				<td><font size=2><center><?=$hasilGD[ID_Kelg]?></center></td>
				<td><font size=2><?=$hasilGD[NM_Kelg]?></td>
				<td><font size=2><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
				<td><font size=2><?=$hasilGD[Kelompok]?></td>
				<td><font size=2><?=$hasilGD[StatusKewargaan]?></td>
				</tr>
				<?}?>
			</table>

<br> Attestasi Masuk
			<table border="0"  width="750" cellspacing=0 cellpadding=0 >

			<tr>
			<td ALIGN=center><font size=2><b>No</b></td>
			<td ALIGN=center><font size=2><b>ID</b></td>
			<td ALIGN=center><font size=2><b>Hub</b></td>
			<td ALIGN=center><font size=2><b>Gen</b></td>
			<td ALIGN=center><font size=2><b>Nama Lengkap</b></td>
			<td ALIGN=center><font size=2><b>ID.Klg</b></td>
			<td ALIGN=center><font size=2><b>Nama Kep Keluarga</b></td>
			<td ALIGN=center><font size=2><b>Tgl Daftar</b></td>
			<td ALIGN=center><font size=2><b>Pindahan Dari</b></td>
			<td ALIGN=center><font size=2><b>Klpk</b></td>

			</tr>
			<?php

//	$sSQL = "select count(*) as Jumlah
//					from person_per natural join person_custom
//					where per_cls_id = $klasid
//					and MONTH($kolom) = $bulan
//					and YEAR($kolom) = $tahun
//					";
//	}

			
				$sSQL = "select IF(per_gender ='1', 'L', 'P') as Gender,
				        CONCAT('<a href=PersonView.php?PersonID=',a.per_ID,'>',a.per_ID,'</a>') AS ID,
						c.lst_OptionName as StatusKewargaan, a.per_FirstName as Nama ,
						CONCAT('<a href=FamilyView.php?FamilyID=',per_fam_ID,'>',per_fam_ID,'</a>') AS ID_Kelg,
						b.fam_Name as NM_Kelg, 
				        per_BirthYear as TahunLahir ,
				         CASE per_fmr_id
						 						 	WHEN '1' THEN 'KK'
						 						 	WHEN '2' THEN 'Ist'
						 						 	WHEN '3' THEN 'Ank'
						 						 	WHEN '4' THEN 'Sdr'
						END AS St_Kelg,
						DAY(per_MembershipDate) as TGL, MONTH(per_MembershipDate) as BLN, YEAR(per_MembershipDate) as THN,
				         per_WorkPhone as Kelompok ,per_Cellphone as Handphone, d.c8 as DARI
				         FROM person_per a ,person_custom d , list_lst c, family_fam b
							WHERE a.per_fam_ID = b.fam_ID AND a.per_id = d.per_id AND c.lst_OptionName = 'Warga' AND 
							a.per_cls_ID = c.lst_OptionID AND c.lst_ID = '1'
							and YEAR(per_MembershipDate) = '$TahunIni' AND d.c8 is not NULL
							ORDER BY a.per_WorkPhone, per_fam_ID, per_fmr_id, a.per_firstname";
//							echo $sSQL ;
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
				<td><font size=2><? echo $i ?></td>
				<td><font size=2><center><?=$hasilGD[ID]?></center></td>
				<td><font size=2><center><?=$hasilGD[St_Kelg]?></td>
				<td><font size=2><center><?=$hasilGD[Gender]?></center></td>
				<td><font size=2><?=$hasilGD[Nama]?></td>
				<td><font size=2><center><?=$hasilGD[ID_Kelg]?></center></td>
				<td><font size=2><?=$hasilGD[NM_Kelg]?></td>
				<td><font size=2><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
				<td><font size=2><?=$hasilGD[DARI]?></td>
				<td><font size=2><?=$hasilGD[Kelompok]?></td>

				</tr>
				<?}?>
			</table>
			
<br> Jemaat Titipan
			<table border="0"  width="750" cellspacing=0 cellpadding=0 >

			<tr>
			<td ALIGN=center><font size=2><b>No</b></td>
			<td ALIGN=center><font size=2><b>ID</b></td>
			<td ALIGN=center><font size=2><b>Hub</b></td>
			<td ALIGN=center><font size=2><b>Gen</b></td>
			<td ALIGN=center><font size=2><b>Nama Lengkap</b></td>
			<td ALIGN=center><font size=2><b>Tgl Daftar</b></td>
			<td ALIGN=center><font size=2><b>Titipan Dari</b></td>
			<td ALIGN=center><font size=2><b>Klpk</b></td>

			</tr>
			<?php

//	$sSQL = "select count(*) as Jumlah
//					from person_per natural join person_custom
//					where per_cls_id = $klasid
//					and MONTH($kolom) = $bulan
//					and YEAR($kolom) = $tahun
//					";
//	}


			
				$sSQL = "select IF(per_gender ='1', 'L', 'P') as Gender,
					CONCAT('<a href=PersonView.php?PersonID=',a.per_ID,'>',a.per_ID,'</a>') AS ID,
					a.per_FirstName as Nama ,per_BirthYear as TahunLahir ,
					CASE per_fmr_id
						WHEN '1' THEN 'KK'
						WHEN '2' THEN 'Ist'
						WHEN '3' THEN 'Ank'
						WHEN '4' THEN 'Sdr'
						END AS St_Kelg,
					DAY(per_membershipdate) as TGL, MONTH(per_membershipdate) as BLN, YEAR(per_membershipdate) as THN,
					per_WorkPhone as Kelompok ,per_Cellphone as Handphone, d.c8 as DARI
					FROM person_per a ,person_custom d 
					WHERE a.per_id = d.per_id 
						and YEAR(per_membershipdate) = '$TahunIni' and a.per_cls_ID = '2' 
					ORDER BY a.per_WorkPhone, per_fam_ID, per_fmr_id, a.per_firstname";

		//					echo $sSQL ;
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
				<td><font size=2><? echo $i ?></td>
				<td><font size=2><center><?=$hasilGD[ID]?></center></td>
				<td><font size=2><center><?=$hasilGD[St_Kelg]?></td>
				<td><font size=2><center><?=$hasilGD[Gender]?></center></td>
				<td><font size=2><?=$hasilGD[Nama]?></td>
				<td><font size=2><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
				<td><font size=2><?=$hasilGD[DARI]?></td>
				<td><font size=2><?=$hasilGD[Kelompok]?></td>

				</tr>
				<?}?>
			</table>
			
			
			
 <br>-----------------------------------------------------------------------------
 <br> Meninggal
			<table border="0"  width="750" cellspacing=0 cellpadding=0 >

			<tr>
			<td ALIGN=center><font size=2><b>No</b></td>
			<td ALIGN=center><font size=2><b>ID</b></td>
			<td ALIGN=center><font size=2><b>Hub</b></td>
			<td ALIGN=center><font size=2><b>Gen</b></td>
			<td ALIGN=center><font size=2><b>Nama Lengkap</b></td>
			<td ALIGN=center><font size=2><b>ID.Klg</b></td>
			<td ALIGN=center><font size=2><b>Nama Kep Keluarga</b></td>
			<td ALIGN=center><font size=2><b>Tgl Meninggal</b></td>
			<td ALIGN=center><font size=2><b>Klpk</b></td>
			<td ALIGN=center><font size=2><b>Status</b></td>
			</tr>
			<?php

//	$sSQL = "select count(*) as Jumlah
//					from person_per natural join person_custom
//					where per_cls_id = $klasid
//					and MONTH($kolom) = $bulan
//					and YEAR($kolom) = $tahun
//					";
//	}

			
				$sSQL = "select IF(per_gender ='1', 'L', 'P') as Gender,
				        CONCAT('<a href=PersonView.php?PersonID=',a.per_ID,'>',a.per_ID,'</a>') AS ID,
						c.lst_OptionName as StatusKewargaan, a.per_FirstName as Nama ,
						CONCAT('<a href=FamilyView.php?FamilyID=',per_fam_ID,'>',per_fam_ID,'</a>') AS ID_Kelg,
						b.fam_Name as NM_Kelg, 
				        per_BirthYear as TahunLahir ,
				         CASE per_fmr_id
						 						 	WHEN '1' THEN 'KK'
						 						 	WHEN '2' THEN 'Ist'
						 						 	WHEN '3' THEN 'Ank'
						 						 	WHEN '4' THEN 'Sdr'
						END AS St_Kelg,
						DAY(d.c41) as TGL, MONTH(d.c41) as BLN, YEAR(d.c41) as THN,
				         per_WorkPhone as Kelompok ,per_Cellphone as Handphone
				         FROM person_per a ,person_custom d , list_lst c, family_fam b
							WHERE a.per_fam_ID = b.fam_ID AND a.per_id = d.per_id AND 
							a.per_cls_ID = c.lst_OptionID AND c.lst_ID = '1'
							and YEAR(d.c41) = '$TahunIni' and d.c41 is not null 
							ORDER BY a.per_WorkPhone, per_fam_ID, per_fmr_id, a.per_firstname";
//							echo $sSQL ;
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
				<td><font size=2><? echo $i ?></td>
				<td><font size=2><center><?=$hasilGD[ID]?></center></td>
				<td><font size=2><center><?=$hasilGD[St_Kelg]?></td>
				<td><font size=2><center><?=$hasilGD[Gender]?></center></td>
				<td><font size=2><?=$hasilGD[Nama]?></td>
				<td><font size=2><center><?=$hasilGD[ID_Kelg]?></center></td>
				<td><font size=2><?=$hasilGD[NM_Kelg]?></td>
				<td><font size=2><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
				<td><font size=2><?=$hasilGD[Kelompok]?></td>
				<td><font size=2><?=$hasilGD[StatusKewargaan]?></td>
				</tr>
				<?}?>
			</table>

 <br> Attestasi Keluar/Pindah
			<table border="0"  width="750" cellspacing=0 cellpadding=0 >

			<tr>
			<td ALIGN=center><font size=2><b>No</b></td>
			<td ALIGN=center><font size=2><b>ID</b></td>
			<td ALIGN=center><font size=2><b>Hub</b></td>
			<td ALIGN=center><font size=2><b>Gen</b></td>
			<td ALIGN=center><font size=2><b>Nama Lengkap</b></td>
			<td ALIGN=center><font size=2><b>ID.Klg</b></td>
			<td ALIGN=center><font size=2><b>Nama Kep Keluarga</b></td>
			<td ALIGN=center><font size=2><b>Tgl Pindah</b></td>
			<td ALIGN=center><font size=2><b>Klpk</b></td>
			<td ALIGN=center><font size=2><b>Status</b></td>
			</tr>
			<?php

//	$sSQL = "select count(*) as Jumlah
//					from person_per natural join person_custom
//					where per_cls_id = $klasid
//					and MONTH($kolom) = $bulan
//					and YEAR($kolom) = $tahun
//					";
//	}

			
				$sSQL = "select IF(per_gender ='1', 'L', 'P') as Gender,
				        CONCAT('<a href=PersonView.php?PersonID=',a.per_ID,'>',a.per_ID,'</a>') AS ID,
						c.lst_OptionName as StatusKewargaan, a.per_FirstName as Nama ,
						CONCAT('<a href=FamilyView.php?FamilyID=',per_fam_ID,'>',per_fam_ID,'</a>') AS ID_Kelg,
						b.fam_Name as NM_Kelg, 
				        per_BirthYear as TahunLahir ,
				         CASE per_fmr_id
						 						 	WHEN '1' THEN 'KK'
						 						 	WHEN '2' THEN 'Ist'
						 						 	WHEN '3' THEN 'Ank'
						 						 	WHEN '4' THEN 'Sdr'
						END AS St_Kelg,
						DAY(d.c10) as TGL, MONTH(d.c10) as BLN, YEAR(d.c10) as THN,
				         per_WorkPhone as Kelompok ,per_Cellphone as Handphone
				         FROM person_per a ,person_custom d , list_lst c, family_fam b
							WHERE a.per_fam_ID = b.fam_ID AND a.per_id = d.per_id AND 
							a.per_cls_ID = c.lst_OptionID AND c.lst_ID = '1'
							and YEAR(d.c10) = '$TahunIni' and d.c10 is not null 
							ORDER BY a.per_WorkPhone, per_fam_ID, per_fmr_id, a.per_firstname";
//							echo $sSQL ;
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
				<td><font size=2><? echo $i ?></td>
				<td><font size=2><center><?=$hasilGD[ID]?></center></td>
				<td><font size=2><center><?=$hasilGD[St_Kelg]?></td>
				<td><font size=2><center><?=$hasilGD[Gender]?></center></td>
				<td><font size=2><?=$hasilGD[Nama]?></td>
				<td><font size=2><center><?=$hasilGD[ID_Kelg]?></center></td>
				<td><font size=2><?=$hasilGD[NM_Kelg]?></td>
				<td><font size=2><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
				<td><font size=2><?=$hasilGD[Kelompok]?></td>
				<td><font size=2><?=$hasilGD[StatusKewargaan]?></td>
				</tr>
				<?}?>
			</table>

<br>
<center>
    <hr>
		<a href="LapBulanan_att.php?Tahun=<?=$TahunIni-1?>" ><< Data Tahun Sebelumnya (<?=$TahunIni-1?>)</a> - 
		<a href="LapBulanan_att.php?Tahun=<?=$TahunIni+1?>" >Data Tahun Berikutnya (<?=$TahunIni+1?>) >></a>
    </center>

</body>



<?php

require "Include/Footer-Short.php";
?>
