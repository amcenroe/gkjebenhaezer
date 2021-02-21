<?php
/*******************************************************************************
 *
 *  filename    : PrintViewReport.php
 *  last change : 2009-09-09
 * 
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
$ilstID = FilterInput($_GET["lstID"]);
$ilstOptionID = FilterInput($_GET["lstOptionID"]);
$iclsID = FilterInput($_GET["clsID"]);

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
$Judul = "Laporan - $Judul "; 

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
	$sSQL = "select count(a.per_id) as Total, IF(per_gender ='1', 'Laki laki', 'Perempuan') as Gender
	    	FROM person_per a , person_custom b , list_lst c
		WHERE a.per_ID = b.per_ID AND b.$cField = c.lst_OptionID AND c.lst_ID = '$ilstID'
			AND c.lst_OptionID = '$ilstOptionID' AND per_cls_id < '$iclsID'		
		GROUP BY per_gender";
	$perintah = mysql_query($sSQL);
	$i = 0;
	$total=0;
	while ($hasilGD=mysql_fetch_array($perintah))
	{
	$i++;
		$total=$hasilGD[Total]+$total;
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
	<table border="0"  width="200" cellspacing=0 cellpadding=0 >
	<tr class="<?php echo $sRowClass; ?>">
	<td ALIGN=left></td>
	<td ALIGN=left><b>Total</b></td>				
	<td ALIGN=right><b><?=$total?></b></center></td>
	</tr>
	</table>

<br><br>
<table border="0"  width="200" cellspacing=0 cellpadding=0 >
<tr>
<td ALIGN=center><b><font size="2">No.</font></b></td>
<td ALIGN=center><b><font size="2">Status Kewargaan</font></b></td>
<td ALIGN=right><b><font size="2">Jumlah</font></b></td>
</tr>
<?php
	$sSQL = "select count(a.per_id) as Total, IF(per_cls_id ='1', 'Warga', 'Titipan') as StatusWarga
	    	FROM person_per a , person_custom b , list_lst c
		WHERE a.per_ID = b.per_ID AND b.$cField = c.lst_OptionID AND c.lst_ID = '$ilstID'
			AND c.lst_OptionID = '$ilstOptionID' AND per_cls_id < '$iclsID'		
		GROUP BY per_cls_id";
	$perintah = mysql_query($sSQL);
	$i = 0;
	$total=0;
	while ($hasilGD=mysql_fetch_array($perintah))
	{
	$i++;
	$total=$hasilGD[Total]+$total;
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
	<table border="0"  width="200" cellspacing=0 cellpadding=0 >
	<tr class="<?php echo $sRowClass; ?>">
	<td ALIGN=left></td>
	<td ALIGN=left><b>Total</b></td>				
	<td ALIGN=right><b><?=$total?></b></center></td>
	</tr>
	</table>
</td>

<td>

<table border="0"  width="200" cellspacing=0 cellpadding=0 >
<tr>
<td ALIGN=center><b><font size="2">No.</font></b></td>
<td ALIGN=center><b><font size="2">Kelompok</font></b></td>
<td ALIGN=right><b><font size="2">Jumlah</font></b></td>
</tr>
<?php
	//$sSQL = "select count(a.per_id) as Total, per_workphone as Kelompok
	//   	FROM person_per a , person_custom b , list_lst c
	//	WHERE a.per_ID = b.per_ID AND b.$cField = c.lst_OptionID AND c.lst_ID = '$ilstID'
	//		AND c.lst_OptionID = '$ilstOptionID' AND per_cls_id < '$iclsID'		
	//	GROUP BY per_workphone";
	$sSQL = "select count(a.per_id) as Total, trim(per_workphone) as Kelompok
	    	FROM person_per a 
			LEFT JOIN person_custom b ON a.per_ID = b.per_ID
			LEFT JOIN list_lst c ON b.$cField = c.lst_OptionID
		WHERE c.lst_ID = '$ilstID' AND c.lst_OptionID = '$ilstOptionID' AND per_cls_id < '$iclsID'		
		GROUP BY Kelompok";	
	//echo $sSQL;	
	$perintah = mysql_query($sSQL);
	$i = 0;
	$total=0;
	while ($hasilGD=mysql_fetch_array($perintah))
	{
	$i++;
	$total=$hasilGD[Total]+$total;
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
	<table border="0"  width="200" cellspacing=0 cellpadding=0 >
	<tr class="<?php echo $sRowClass; ?>">
	<td ALIGN=left></td>
	<td ALIGN=left><b>Total</b></td>				
	<td ALIGN=right><b><?=$total?></b></center></td>
	</tr>
	</table>
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
<td ALIGN=center><b><font size="2"><?php echo $tabJudul ?></font></b></td>
<td ALIGN=center><b><font size="2">Nama Lengkap </font></b></td>
<td ALIGN=center><b><font size="2">Gen.</font></b></td>
<td ALIGN=center><b><font size="2">Tgl Lahir</font></b></td>
<td ALIGN=center><b><font size="2">Telp Rumah </font></b></td>
<td ALIGN=center><b><font size="2">Handphone </font></b></td>
<td ALIGN=center><b><font size="2">Kelompok </font></b></td>
<td ALIGN=center><b><font size="2">Status </font></b></td>
</tr>
<?php
	$sSQL = "select IF(per_gender ='1', 'L', 'P') as Gender,
	 		per_fam_id as IDKelg , c.lst_OptionName as StatusData, a.per_FirstName as Nama ,
         		per_CellPhone as TelpHP, per_fam_id as IDKelg , 
	 		per_birthDay as TGL, per_birthMonth as BLN, per_birthYear as THN, 
         		TRIM(per_WorkPhone) as Kelompok, IF(per_cls_id ='1', 'Warga', 'Titipan') as Status ,
			d.fam_HomePhone as TelpRumah
         	FROM person_per a , person_custom b , list_lst c , family_fam d
		WHERE a.per_ID = b.per_ID AND a.per_fam_id = d.fam_id
			AND b.$cField = c.lst_OptionID AND c.lst_ID = '$ilstID'
			AND c.lst_OptionID = '$ilstOptionID' AND per_cls_id < '$iclsID'
		ORDER BY TRIM(a.per_WorkPhone) , a.per_fam_id, a.per_FirstName ";

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
	<td ALIGN=center><?=$hasilGD[StatusData]?></center></td>
	<td ALIGN=left><?=$hasilGD[Nama]?></td>
	<td ALIGN=center><?=$hasilGD[Gender]?></td>
	<td ALIGN=center><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
	<td ALIGN=center><?=$hasilGD[TelpRumah]?></td>
	<td ALIGN=center><?=$hasilGD[TelpHP]?></td>
	<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
	<td ALIGN=center><?=$hasilGD[Status]?></td>
	</tr>
	<?}?>
	</table>

<?php
require "Include/Footer-Short.php";
?>
