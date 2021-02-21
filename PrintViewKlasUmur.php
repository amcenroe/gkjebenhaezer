<?php
/*******************************************************************************
 *
 *  filename    : PrintViewReport.php
 *  last change : 2009-09-09
 *  http://www.gkjbekasi-wiltimur.info/datawarga/PrintViewKlasUmur.php?GenderID=1?Klas=Balita?Uawal=0?Uakhir=5
 *  Copyright 2001-2003 Phillip Hullquist, Deane Barker, Chris Gebhardt 
 *  (M) 2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  http://www.gkjbekasi-wiltimur.info/datawarga/PrintViewReport.php?lstID=XX&lstOptionID=YY&clsID=ZZ
 *
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";


// Get the ID from the querystring

$GenderID=FilterInput($_GET["GenderID"]);
$Klas=FilterInput($_GET["Klas"]);
$umurawal=FilterInput($_GET["Uawal"]);
$umurakhir=FilterInput($_GET["Uakhir"]);
$ilstID = FilterInput($_GET["lstID"]);

//$ilstOptionID = FilterInput($_GET["lstOptionID"]);
$iclsID = FilterInput($_GET["clsID"]);

$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;

$sSQL = "SELECT custom_Name as iJudul, custom_Field as cField
	FROM person_custom_master
	WHERE custom_Special = '$ilstID'";
$perintah = mysql_query($sSQL);
	while ($hasilGD=mysql_fetch_array($perintah))
	{
extract($hasilGD);
	$Judul = $hasilGD[iJudul];
	$customField = $hasilGD[cField];
	}

//echo $ilstID;
//echo $Judul;
//echo $cField;

$tabJudul = $Judul;
//$Judul = "Laporan - $Judul "; 
$Judul = "Laporan - Jenjang Umur $Klas , Umur: $umurawal s/d $umurakhir tahun "; 

require "Include/Header-Report.php";
?>

<table border="0"  width="750" cellspacing=0 cellpadding=0 >
<b><u><font size="3">Ringkasan :</font></u></b>
<br><br>

<tr>
<td>

<table border="0"  width="200" cellspacing=0 cellpadding=0 >
<tr>
<td ALIGN=center><b><font size="2">No.</font></b></td>
<td ALIGN=center><b><font size="2">Jenis Kelamin</font></b></td>
<td ALIGN=right><b><font size="2">Jumlah</font></b></td>
</tr>
<?php
	$sSQL = "SELECT count(per_ID) as Total , IF(per_gender ='1', 'Laki laki', 'Perempuan') as Gender
			FROM person_per
			WHERE per_gender = $GenderID AND per_cls_ID < 3 AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL  $umurawal YEAR) <= CURDATE()
			AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (  $umurakhir+1 ) YEAR) >= CURDATE()
			group by per_gender";

	$perintah = mysql_query($sSQL);
	$i = 0;
	while ($hasilGD=mysql_fetch_array($perintah))
	{
	$i++;
		extract($hasilGD);
                $sRowClass = AlternateRowStyle($sRowClass);
?>
	<table border="0"  width="200" cellspacing=0 cellpadding=0 >
	<tr class="<?php echo $sRowClass; ?>">
	<td ALIGN=left><? echo $i ?>.</td>
	<td ALIGN=left><?=$hasilGD[Gender]?></td>				
	<td ALIGN=right><?=$hasilGD[Total]?></center></td>

	</tr>
	</table>
	<?}?>

<br><br>
<table border="0"  width="200" cellspacing=0 cellpadding=0 >
<tr>
<td ALIGN=center><b><font size="2">No.</font></b></td>
<td ALIGN=center><b><font size="2">Status Kewargaan</font></b></td>
<td ALIGN=right><b><font size="2">Jumlah</font></b></td>
</tr>
<?php
	$sSQL = "SELECT count(a.per_ID) as Total , IF(per_gender ='1', 'Laki laki', 'Perempuan') as Gender,
			 IF(per_cls_id ='1', 'Warga', 'Titipan') as StatusWarga
			FROM person_per  a , person_custom b , list_lst c
		WHERE a.per_ID = b.per_ID AND c.lst_OptionID = 1 AND c.lst_ID = '$ilstID' AND
			per_gender = $GenderID AND per_cls_ID < 3 AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL  $umurawal YEAR) <= CURDATE()
			AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (  $umurakhir+1 ) YEAR) >= CURDATE()
		group by per_cls_id";	


	$perintah = mysql_query($sSQL);
	$i = 0;
	while ($hasilGD=mysql_fetch_array($perintah))
	{
	$i++;
		extract($hasilGD);
                $sRowClass = AlternateRowStyle($sRowClass);
?>
	<table border="0"  width="200" cellspacing=0 cellpadding=0 >
	<tr class="<?php echo $sRowClass; ?>">
	<td ALIGN=left><? echo $i ?>.</td>
	<td ALIGN=left><?=$hasilGD[StatusWarga]?></td>				
	<td ALIGN=right><?=$hasilGD[Total]?></center></td>

	</tr>
	</table>
	<?}?>
</td>

<td>

<table border="0"  width="200" cellspacing=0 cellpadding=0 >
<tr>
<td ALIGN=center><b><font size="2">No.</font></b></td>
<td ALIGN=center><b><font size="2">Kelompok</font></b></td>
<td ALIGN=right><b><font size="2">Jumlah</font></b></td>
</tr>
<?php
	$sSQL = "select count(a.per_id) as Total, TRIM(per_workphone) as Kelompok
	    	FROM person_per a , person_custom b , list_lst c
		WHERE a.per_ID = b.per_ID AND c.lst_OptionID = 1 AND c.lst_ID = '$ilstID' AND
			per_gender = $GenderID AND per_cls_ID < 3 AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL  $umurawal YEAR) <= CURDATE()
			AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (  $umurakhir+1 ) YEAR) >= CURDATE()
		GROUP BY TRIM(per_workphone)";

	$perintah = mysql_query($sSQL);
	$i = 0;
	while ($hasilGD=mysql_fetch_array($perintah))
	{
	$i++;
		extract($hasilGD);
                $sRowClass = AlternateRowStyle($sRowClass);
?>
	<table border="0"  width="200" cellspacing=0 cellpadding=0 >
	<tr class="<?php echo $sRowClass; ?>">
	<td ALIGN=left><? echo $i ?>.</td>
	<td ALIGN=left><?=$hasilGD[Kelompok]?></td>				
	<td ALIGN=right><?=$hasilGD[Total]?></center></td>

	</tr>
	</table>
	<?}?>
</td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
</tr>
</table> 


<br><br>

<b><u><font size="3">Detail :</font></u></b>
<br><br>

<table border="0"  width="750" cellspacing=0 cellpadding=0 >
<tr>
<td ALIGN=center><b><font size="2">No.</font></b></td>
<td ALIGN=center><b><font size="2">ID.Kelg </font></b></td>
<td ALIGN=center><b><font size="2">Nama Lengkap </font></b></td>
<td ALIGN=center><b><font size="2">Gen.</font></b></td>
<td ALIGN=center><b><font size="2">Tgl Lahir</font></b></td>
<td ALIGN=center><b><font size="2">Telp Rumah </font></b></td>
<td ALIGN=center><b><font size="2">Handphone </font></b></td>
<td ALIGN=center><b><font size="2">Kelompok </font></b></td>
<td ALIGN=center><b><font size="2">Status </font></b></td>
<td ALIGN=center><b><font size="2">BA </font></b></td>
<td ALIGN=center><b><font size="2">SD </font></b></td>
<td ALIGN=center><b><font size="2">BD </font></b></td>
<td ALIGN=center><b><font size="2">M </font></b></td>
</tr>
<?php


	$sSQL = "SELECT IF(per_gender ='1', 'L', 'P') as Gender,
			per_fam_id as IDKelg , c.lst_OptionName as StatusData, a.per_FirstName as Nama ,
         		per_CellPhone as TelpHP, per_fam_id as IDKelg , 
	 		per_birthDay as TGL, per_birthMonth as BLN, per_birthYear as THN, 
         		TRIM(per_WorkPhone) as Kelompok, IF(per_cls_id ='1', 'Warga', 'Titipan') as Status ,
			d.fam_HomePhone as TelpRumah, 
			IF (b.c1 IS NULL or (b.c1 = '0000-00-00'), '-', 'V' ) as BA ,
			IF (b.c2 IS NULL or (b.c2 = '0000-00-00'), '-', 'V' ) as SD ,
			IF (b.c18 IS NULL or (b.c18 = '0000-00-00'), '-', 'V' ) as BD,
			IF (a.per_fmr_ID = '1' or (a.per_fmr_ID = '2'), 'M', '-' ) as Mn 
			
		FROM person_per a , person_custom b , list_lst c , family_fam d
			
		WHERE a.per_ID = b.per_ID AND a.per_fam_id = d.fam_id AND c.lst_OptionID = 1  AND c.lst_ID = '$ilstID' AND
			per_gender = $GenderID AND per_cls_ID < 3 AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL  $umurawal YEAR) <= CURDATE()
			AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (  $umurakhir+1 ) YEAR) >= CURDATE()
		ORDER BY  TRIM(a.per_WorkPhone) , a.per_fam_id, a.per_FirstName 	";

	$perintah = mysql_query($sSQL);
	$i = 0;
	while ($hasilGD=mysql_fetch_array($perintah))
	{
	$i++;
		extract($hasilGD);
                $sRowClass = AlternateRowStyle($sRowClass);
?>

	<tr class="<?php echo $sRowClass; ?>">
	<td ALIGN=center><? echo $i ?></td>
	<td ALIGN=center><?=$hasilGD[IDKelg]?></td>				
	<td ALIGN=left><?=$hasilGD[Nama]?></td>
	<td ALIGN=center><?=$hasilGD[Gender]?></td>
	<td ALIGN=center><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
	<td ALIGN=center><?=$hasilGD[TelpRumah]?></td>
	<td ALIGN=center><?=$hasilGD[TelpHP]?></td>
	<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
	<td ALIGN=center><?=$hasilGD[Status]?></td>
	<td ALIGN=center><?=$hasilGD[BA]?></td>
	<td ALIGN=center><?=$hasilGD[SD]?></td>
	<td ALIGN=center><?=$hasilGD[BD]?></td>
	<td ALIGN=center><?=$hasilGD[Mn]?></td>
	</tr>
	<?}?>
	</table>

<?php
require "Include/Footer-Short.php";
?>
