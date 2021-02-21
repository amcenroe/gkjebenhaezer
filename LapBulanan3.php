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
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";

//Print_r ($_SESSION);
$Judul = "Laporan - Executive Summary "; 
require "Include/Header-Report.php";

?>

<?php

function list_data ($listID) {

$iclsID = 3;
$sSQL = "SELECT custom_Name as iJudul, custom_Field as cField
	FROM person_custom_master
	WHERE custom_Special = '$listID'";
$perintah = mysql_query($sSQL);
	while ($hasilGD=mysql_fetch_array($perintah))
	{
extract($hasilGD);
	$Judul = $hasilGD[iJudul];
	$customField = $hasilGD[cField];
	}

echo "<img src=\"GraphReport.php?lstID=$listID&amp;cField=$cField&amp;$refresh \"  width=\"360\" ><br>" ;
echo "	<table table style=\"text-align: left; width: 80%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody> ";

if ( $customField == "" ) {

	$sSQL = "select CONCAT('<a href=PrintViewStatusWarga.php?lstID=',$listID,'&amp;','Status=',c.lst_OptionID,' 	target=_blank>',c.lst_OptionName,'</a>') as ListData, count(a.per_ID) as Jumlah
		FROM person_per a , list_lst c
		WHERE a.per_cls_ID = c.lst_OptionID AND c.lst_ID = '$listID'	
		GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";

} else {
	$sSQL = "select CONCAT('<a href=PrintViewReport.php?clsID=$iclsID','&amp;','lstID=',$listID,'&amp;','lstOptionID=',c.lst_OptionID,' 	target=_blank>',c.lst_OptionName,'</a>') as ListData, count(a.per_ID) as Jumlah
		FROM person_per a , person_custom b , list_lst c
		WHERE a.per_ID = b.per_ID AND b.$cField = c.lst_OptionID AND c.lst_ID = '$listID' AND per_cls_id < '$iclsID'	
		GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
}
	$perintah = mysql_query($sSQL);
	$i = 0;
	while ($hasilListData=mysql_fetch_array($perintah)){
	$i++;
	$sRowClass = AlternateRowStyle($sRowClass);

echo "	<tr class=" . $sRowClass . "> ";
echo "	<td>*</td> ";
echo "	<td><font size=2>" . $hasilListData[ListData] . "</td> ";
echo "	<td ALIGN=right><font size=2>" . $hasilListData[Jumlah] . " jiwa</td> ";
echo "	</tr> ";
}
echo "	</table>";
}



function rentang_usia ($klasumur, $umurawal, $umurakhir) {

echo "	<table border=\"0\" width=\"280\" cellspacing=0 cellpadding=0 >";
echo "	<tr>";
echo "	<td><b> > </td>";
echo "	<td><font size=2><b>Jemaat " . $klasumur . " (" . $umurawal ."-" . $umurakhir . "Th)</td> ";
echo "	<td ALIGN=right> </td>";
echo "	</tr>";
	$sSQL = "SELECT CONCAT('<a href=PrintViewKlasUmur.php?GenderID=',per_Gender,'?Klas=$klasumur?Uawal=$umurawal?Uakhir=$umurakhir target=_blank >',IF(per_gender ='1', 'Laki laki', 'Perempuan'),'  ') as Jemaat , count(per_ID) as Jiwa
	FROM person_per
	WHERE per_gender <> 0 AND per_cls_ID < 3 AND
		DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL  $umurawal YEAR) <= CURDATE()
		AND DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (  $umurakhir+1 ) YEAR) >= CURDATE()
	group by per_gender";
	$perintah = mysql_query($sSQL);
	$i = 0;
	$total = 0;
	while ($hasilGD=mysql_fetch_array($perintah))
	{
	$i++;
	$total = ($total + $hasilGD[Jiwa]);
	extract($hasilGD);
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
$sSQL = "SELECT COUNT(fam_ID) FROM family_fam WHERE fam_WorkPhone != 'Pindah' ";
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
 <b>Informasi Atestasi </b>

 <br>

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
	list_data(1); 
	?>
	</td>


      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Informasi Berdasarkan Jenis Kelamin </b><br>
      <?php
	  	  	echo "<img src=\"graph_jeniskelamin.php?lakilaki=$laki&amp;perempuan=$perempuan&amp;$refresh \" width=\"360\" ><br>" ;
	  ?>
	  <table table style="text-align: left; width: 80%;" border="0" cellpadding="0" cellspacing="0"><tbody>
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
	list_data(20); 
	?>
      </td>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Status Perkawinan</b><br>
	<?php
	list_data(23); 	
	?>
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
 				<td><font size=2><?=$hasilGD[Bulan]?></td>
 				<td ALIGN=right><font size=2><?=$hasilGD[Jumlah]?>  jiwa</td>

 				</tr>
 				<?}?>
 		</table>
       </td>

       <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
       <br><b>Status Perkawinan</b><br>
	<?php
	list_data(23);
	?>
       </td>
</tr>
<tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <b>Jenjang Pendidikan</b><br>
	<?php
	list_data(18);   
	?>
      </td>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
 	<br><b>Informasi Pekerjaan</b><br>
	<?php
	list_data(17);   
	?>
 	</td>
</tr>
<tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Jenjang Jabatan / Pangkat</b><br>
	<?php
	list_data(19);   
	?>
      </td>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Profesi / Keahlian</b><br>
	<?php
	list_data(24);   
	?>
      </td>
</tr>
<tr>
      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Minat</b><br>
	<?php
	list_data(25);   
	?>
      </td>

      <td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
      <br><b>Minat Pelayanan</b><br>
      <?php
	list_data(26);   
	?>
      </td>
</tr>

<tr>
      <br><td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
	<b>Hobi</b><br>
	<?php
	list_data(22);   
	?>
	</td>
<br>
	<td style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top>
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
