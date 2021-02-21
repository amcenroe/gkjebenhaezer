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
	$sSQL = "select count(a.per_id) as Total, per_workphone as Kelompok
	    	FROM person_per a , person_custom b , list_lst c
		WHERE a.per_ID = b.per_ID AND c.lst_OptionID = 1 AND c.lst_ID = '$ilstID' AND
			per_gender = $GenderID AND per_cls_ID < 3 AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL  $umurawal YEAR) <= CURDATE()
			AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (  $umurakhir+1 ) YEAR) >= CURDATE()
		GROUP BY per_workphone";

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

<table border="0"  width="1000" cellspacing=0 cellpadding=0 >
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
<?php
$header_c1="Tanggal Baptis Anak";
$header_c2="Tanggal Sidi";
$header_c3="Pekerjaan";
$header_c4="Pendidikan Terakhir";
$header_c5="Jabatan/Pangkat";
$header_c6="Golongan Darah";
$header_c29="Pekerjaan (Lainnya)";
$header_c8="Tamu/Pindah/Titipan dr Gereja(attestasi)";
$header_c9="Pindah Ke ( Attestasi Keluar )";
$header_c10="Tanggal Pindah ( Attestasi Keluar )";
$header_c11="Tempat Bekerja/Usaha";
$header_c12="Alamat Kantor/Usaha";
$header_c13="Telp.Kantor/Usaha";
$header_c14="Hobi";
$header_c15="Status Perkawinan";
$header_c16="Nama Ayah (Jika Bukan Warga)";
$header_c17="Nama Ibu (Jika Bukan Warga)";
$header_c18="Tanggal Baptis Dewasa";
$header_c19="Profesi/Keahlian";
$header_c20="Minat";
$header_c31="SubBidang Usaha";
$header_c30="Bid.Usaha Utama";
$header_c23="Hobi Lainya";
$header_c25="Pendidikan (Jurusan)";
$header_c26="Tempat Baptis Anak";
$header_c27="Tempat Sidi";
$header_c28="Tempat Baptis Dewasa";
$header_c32="Jabatan/Pangkat (Lainnya)";
$header_c33="Profesi/Keahlian (Lainnya)";
$header_c34="Minat (Lainnya)";
$header_c35="Minat Bidang Pelayanan";
$header_c36="Minat Bidang Pelayanan (Lainnya)";
$header_c37="DiBaptis Oleh (Anak)";
$header_c38="DiLayani Oleh";
$header_c39="DiBaptis Oleh (Dewasa)";
$header_c40="Reff Surat Pindah ( Attestasi Keluar )";
$header_c42="Tempat Semayam";
$header_c41="Tanggal Meninggal";
$header_c43="Dititipkan/Perawatan Rohani ke";
$header_c44="Tanggal Penitipan/Perawatan Rohani";
$header_c45="Alasan Penitipan/Perawatan Rohani";
$header_c46="Alamat baru (titipan)";
$header_c47="Kota - KodePOS (titipan)";
$header_c48="Alasan Kepindahan (Attestasi Keluar )";

//if($C1==1){echo "<td ALIGN=center><b><font size=\"2\">$headerc1</font></b></td>";}

$C1=FilterInput($_GET["C1"]);if($C1==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c1</font></b></td>";}
$C2=FilterInput($_GET["C2"]);if($C2==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c2</font></b></td>";}
$C3=FilterInput($_GET["C3"]);if($C3==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c3</font></b></td>";}
$C4=FilterInput($_GET["C4"]);if($C4==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c4</font></b></td>";}
$C5=FilterInput($_GET["C5"]);if($C5==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c5</font></b></td>";}
$C6=FilterInput($_GET["C6"]);if($C6==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c6</font></b></td>";}
$C7=FilterInput($_GET["C7"]);if($C7==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c7</font></b></td>";}
$C8=FilterInput($_GET["C8"]);if($C8==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c8</font></b></td>";}
$C9=FilterInput($_GET["C9"]);if($C9==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c9</font></b></td>";}
$C10=FilterInput($_GET["C10"]);if($C10==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c10</font></b></td>";}

$C11=FilterInput($_GET["C11"]);if($C11==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c11</font></b></td>";}
$C12=FilterInput($_GET["C12"]);if($C12==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c12</font></b></td>";}
$C13=FilterInput($_GET["C13"]);if($C13==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c13</font></b></td>";}
$C14=FilterInput($_GET["C14"]);if($C14==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c14</font></b></td>";}
$C15=FilterInput($_GET["C15"]);if($C15==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c15</font></b></td>";}
$C16=FilterInput($_GET["C16"]);if($C16==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c16</font></b></td>";}
$C17=FilterInput($_GET["C17"]);if($C17==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c17</font></b></td>";}
$C18=FilterInput($_GET["C18"]);if($C18==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c18</font></b></td>";}
$C19=FilterInput($_GET["C19"]);if($C19==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c19</font></b></td>";}
$C20=FilterInput($_GET["C20"]);if($C20==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c20</font></b></td>";}

$C21=FilterInput($_GET["C21"]);if($C21==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c21</font></b></td>";}
$C22=FilterInput($_GET["C22"]);if($C22==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c22</font></b></td>";}
$C23=FilterInput($_GET["C23"]);if($C23==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c23</font></b></td>";}
$C24=FilterInput($_GET["C24"]);if($C24==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c24</font></b></td>";}
$C25=FilterInput($_GET["C25"]);if($C25==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c25</font></b></td>";}
$C26=FilterInput($_GET["C26"]);if($C26==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c26</font></b></td>";}
$C27=FilterInput($_GET["C27"]);if($C27==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c27</font></b></td>";}
$C28=FilterInput($_GET["C28"]);if($C28==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c28</font></b></td>";}
$C29=FilterInput($_GET["C29"]);if($C29==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c29</font></b></td>";}
$C30=FilterInput($_GET["C30"]);if($C30==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c30</font></b></td>";}

$C31=FilterInput($_GET["C31"]);if($C31==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c31</font></b></td>";}
$C32=FilterInput($_GET["C32"]);if($C32==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c32</font></b></td>";}
$C33=FilterInput($_GET["C33"]);if($C33==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c33</font></b></td>";}
$C34=FilterInput($_GET["C34"]);if($C34==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c34</font></b></td>";}
$C35=FilterInput($_GET["C35"]);if($C35==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c35</font></b></td>";}
$C36=FilterInput($_GET["C36"]);if($C36==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c36</font></b></td>";}
$C37=FilterInput($_GET["C37"]);if($C37==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c37</font></b></td>";}
$C38=FilterInput($_GET["C38"]);if($C38==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c38</font></b></td>";}
$C39=FilterInput($_GET["C39"]);if($C39==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c39</font></b></td>";}
$C40=FilterInput($_GET["C40"]);if($C40==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c40</font></b></td>";}

$C41=FilterInput($_GET["C41"]);if($C41==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c41</font></b></td>";}
$C42=FilterInput($_GET["C42"]);if($C42==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c42</font></b></td>";}
$C43=FilterInput($_GET["C43"]);if($C43==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c43</font></b></td>";}
$C44=FilterInput($_GET["C44"]);if($C44==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c44</font></b></td>";}
$C45=FilterInput($_GET["C45"]);if($C45==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c45</font></b></td>";}
$C46=FilterInput($_GET["C46"]);if($C46==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c46</font></b></td>";}
$C47=FilterInput($_GET["C47"]);if($C47==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c47</font></b></td>";}
$C48=FilterInput($_GET["C48"]);if($C48==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c48</font></b></td>";}
$C49=FilterInput($_GET["C49"]);if($C49==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c49</font></b></td>";}
$C50=FilterInput($_GET["C50"]);if($C50==1){echo "<td ALIGN=center><b><font size=\"2\">$header_c50</font></b></td>";}
?>


</tr>
<?php


	$sSQL = "SELECT IF(per_gender ='1', 'L', 'P') as Gender,b.*,
			per_fam_id as IDKelg , c.lst_OptionName as StatusData, a.per_FirstName as Nama ,
         		per_CellPhone as TelpHP, per_fam_id as IDKelg , 
	 		per_birthDay as TGL, per_birthMonth as BLN, per_birthYear as THN, 
         		per_WorkPhone as Kelompok, IF(per_cls_id ='1', 'Warga', 'Titipan') as Status ,
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
		ORDER BY  a.per_WorkPhone , a.per_fam_id, a.per_FirstName 	";

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
	<?php

if($C1==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c1]</font></td>";}
if($C2==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c2]</font></td>";}

if($C3==1&&$hasilGD[c3]>0){

	$sSQL1="SELECT lst_OptionName FROM list_lst WHERE lst_ID = 17 AND lst_OptionID = ".$hasilGD[c3];
	//echo $sSQL1;
	$perintah1 = mysql_query($sSQL1);
	$ii = 0;
	while ($hasilGD1=mysql_fetch_array($perintah1))
	{
	$ii++;
		extract($hasilGD1);
		echo "<td ALIGN=center><font size=\"2\">$hasilGD1[lst_OptionName]</font></td>";
	}	
	}
if($C4==1&&$hasilGD[c4]>0){

	$sSQL1="SELECT lst_OptionName FROM list_lst WHERE lst_ID = 18 AND lst_OptionID = ".$hasilGD[c4];
	//echo $sSQL1;
	$perintah1 = mysql_query($sSQL1);
	$ii = 0;
	while ($hasilGD1=mysql_fetch_array($perintah1))
	{
	$ii++;
		extract($hasilGD1);
		echo "<td ALIGN=center><font size=\"2\">$hasilGD1[lst_OptionName]</font></td>";
	}	
	}
if($C5==1&&$hasilGD[c5]>0){

	$sSQL1="SELECT lst_OptionName FROM list_lst WHERE lst_ID = 19 AND lst_OptionID = ".$hasilGD[c5];
	//echo $sSQL1;
	$perintah1 = mysql_query($sSQL1);
	$ii = 0;
	while ($hasilGD1=mysql_fetch_array($perintah1))
	{
	$ii++;
		extract($hasilGD1);
		echo "<td ALIGN=center><font size=\"2\">$hasilGD1[lst_OptionName]</font></td>";
	}	
	}
if($C6==1&&$hasilGD[c6]>0){

	$sSQL1="SELECT lst_OptionName FROM list_lst WHERE lst_ID = 20 AND lst_OptionID = ".$hasilGD[c6];
	//echo $sSQL1;
	$perintah1 = mysql_query($sSQL1);
	$ii = 0;
	while ($hasilGD1=mysql_fetch_array($perintah1))
	{
	$ii++;
		extract($hasilGD1);
		echo "<td ALIGN=center><font size=\"2\">$hasilGD1[lst_OptionName]</font></td>";
	}	
	}
if($C7==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c7]</font></td>";}
if($C8==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c8]</font></td>";}
if($C9==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c9]</font></td>";}
if($C10==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c10]</font></td>";}

if($C11==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c11]</font></td>";}
if($C12==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c12]</font></td>";}
if($C13==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c13]</font></td>";}
if($C14==1&&$hasilGD[c14]>0){

	$sSQL1="SELECT lst_OptionName FROM list_lst WHERE lst_ID = 22 AND lst_OptionID = ".$hasilGD[c14];
	//echo $sSQL1;
	$perintah1 = mysql_query($sSQL1);
	$ii = 0;
	while ($hasilGD1=mysql_fetch_array($perintah1))
	{
	$ii++;
		extract($hasilGD1);
		echo "<td ALIGN=center><font size=\"2\">$hasilGD1[lst_OptionName]</font></td>";
	}	
	}
if($C15==1&&$hasilGD[c15]>0){

	$sSQL1="SELECT lst_OptionName FROM list_lst WHERE lst_ID = 23 AND lst_OptionID = ".$hasilGD[c15];
	//echo $sSQL1;
	$perintah1 = mysql_query($sSQL1);
	$ii = 0;
	while ($hasilGD1=mysql_fetch_array($perintah1))
	{
	$ii++;
		extract($hasilGD1);
		echo "<td ALIGN=center><font size=\"2\">$hasilGD1[lst_OptionName]</font></td>";
	}	
	}
if($C16==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c16]</font></td>";}
if($C17==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c17]</font></td>";}
if($C18==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c18]</font></td>";}
if($C19==1&&$hasilGD[c19]>0){

	$sSQL1="SELECT lst_OptionName FROM list_lst WHERE lst_ID = 24 AND lst_OptionID = ".$hasilGD[c19];
	//echo $sSQL1;
	$perintah1 = mysql_query($sSQL1);
	$ii = 0;
	while ($hasilGD1=mysql_fetch_array($perintah1))
	{
	$ii++;
		extract($hasilGD1);
		echo "<td ALIGN=center><font size=\"2\">$hasilGD1[lst_OptionName]</font></td>";
	}	
	}
if($C20==1&&$hasilGD[c20]>0){

	$sSQL1="SELECT lst_OptionName FROM list_lst WHERE lst_ID = 20 AND lst_OptionID = ".$hasilGD[c20];
	//echo $sSQL1;
	$perintah1 = mysql_query($sSQL1);
	$ii = 0;
	while ($hasilGD1=mysql_fetch_array($perintah1))
	{
	$ii++;
		extract($hasilGD1);
		echo "<td ALIGN=center><font size=\"2\">$hasilGD1[lst_OptionName]</font></td>";
	}	
	}

if($C21==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c21]</font></td>";}
if($C22==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c22]</font></td>";}
if($C23==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c23]</font></td>";}
if($C24==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c24]</font></td>";}
if($C25==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c25]</font></td>";}
if($C26==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c26]</font></td>";}
if($C27==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c27]</font></td>";}
if($C28==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c28]</font></td>";}
if($C29==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c29]</font></td>";}
if($C30==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c30]</font></td>";}

if($C31==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c31]</font></td>";}
if($C32==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c32]</font></td>";}
if($C33==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c33]</font></td>";}
if($C34==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c34]</font></td>";}
if($C35==1&&$hasilGD[c35]>0){

	$sSQL1="SELECT lst_OptionName FROM list_lst WHERE lst_ID = 26 AND lst_OptionID = ".$hasilGD[c35];
	//echo $sSQL1;
	$perintah1 = mysql_query($sSQL1);
	$ii = 0;
	while ($hasilGD1=mysql_fetch_array($perintah1))
	{
	$ii++;
		extract($hasilGD1);
		echo "<td ALIGN=center><font size=\"2\">$hasilGD1[lst_OptionName]</font></td>";
	}	
	}
if($C36==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c36]</font></td>";}
if($C37==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c37]</font></td>";}
if($C38==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c38]</font></td>";}
if($C39==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c39]</font></td>";}
if($C40==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c40]</font></td>";}

if($C41==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c41]</font></td>";}
if($C42==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c42]</font></td>";}
if($C43==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c43]</font></td>";}
if($C44==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c44]</font></td>";}
if($C45==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c45]</font></td>";}
if($C46==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c46]</font></td>";}
if($C47==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c47]</font></td>";}
if($C48==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c48]</font></td>";}
if($C49==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c49]</font></td>";}
if($C50==1){echo "<td ALIGN=center><font size=\"2\">$hasilGD[c50]</font></td>";}
	?>
	</tr>
	<?}?>
	</table>

<?php
require "Include/Footer-Short.php";
?>
