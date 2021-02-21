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
// Get the list of custom person
$sSQL = "SELECT count(per_ID) FROM person_per WHERE per_cls_ID < 3";
$perintah = mysql_query($sSQL);
$semua = mysql_result($perintah,0);
// Get the list of custom person null
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE per_BirthYear is null AND per_cls_ID < 3" ;
$perintah = mysql_query($sSQL);
$nodata = mysql_result($perintah,0);
// Get the list of custom person Anak 0-7 th
$sSQL = "SELECT count(per_ID)
FROM person_per
WHERE per_cls_ID < 3 AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 0 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (7 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$anak = mysql_result($perintah,0);
// Get the list of custom person Anak 8-16 th
$sSQL = "SELECT count(per_ID)
FROM person_per
WHERE per_cls_ID < 3 AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 8 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (16 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$remaja = mysql_result($perintah,0);
// Get the list of custom person Anak 17-25 th
$sSQL = "SELECT count(per_ID)
FROM person_per
WHERE per_cls_ID < 3 AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 17 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (25 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$pemuda = mysql_result($perintah,0);
// Get the list of custom person Anak 26-50 th
$sSQL = "SELECT count(per_ID)
FROM person_per
WHERE per_cls_ID < 3 AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 26 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (50 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$dewasa = mysql_result($perintah,0);
// Get the list of custom person Anak 51-120 th
$sSQL = "SELECT count(per_ID)
FROM person_per
WHERE per_cls_ID < 3 AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 51 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (200 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$lansia = mysql_result($perintah,0);
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
      <td style="width: 125px;"><img
 style="width: 109px; height: 109px;" src="gkj_logo.jpg" border="0"></td>
      <td style="width: 630px;"><b
 style="font-family: Arial; color: rgb(0, 0, 102);"><font size="5">GEREJA KRISTEN JAWA BEKASI </font></b><br
 style="font-family: Arial; color: rgb(0, 0, 102);">
      <b style="font-family: Arial; color: rgb(0, 0, 102);"><font
 size="5">Wilayah Timur </font></b><br style="font-family: Arial;">
      <b><font size="2"><big style="font-family: Arial;">Laporan Bulanan (Executive Sumary)</big><br>
      </font></b>
      <hr style="width: 100%; height: 2px;"><b
 style="font-family: Arial;">Bulan : <?php echo date("F, Y"); ?></b></td>
    </tr>
  </tbody>
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
 		<td>&nbsp* Jemaat Anak (0~7th) </td><td><?php echo $anak ?></td><td>jiwa</td>
		</tr>
		<tr>
		<td>&nbsp* Jemaat Remaja (8~16th)</td><td><?php echo $remaja ?></td><td>jiwa</td>
		</tr>
		<tr>
		<td>&nbsp* Jemaat Pemuda (17~25th)</td><td><?php echo $pemuda ?></td><td>jiwa</td>
		</tr>
		<tr>
		<td>&nbsp* Jemaat Dewasa (26~50th)</td><td><?php echo $dewasa ?></td><td>jiwa</td>
		</tr>
		<tr>
		<td>&nbsp* Jemaat Lansia (51~)</td><td><?php echo $lansia ?></td><td>jiwa</td>
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
	  						$sSQL = "select CONCAT('<a href=PrintViewGender.php?GenderID=',per_Gender,' target=_blank >',per_Gender,'  ') as Gender ,
	  								IF(per_Gender=1,\"Laki Laki</a>\",\"Perempuan</a>\") as JenisKelamin, count(per_Gender) as Jumlah from person_per
	  								WHERE per_cls_ID < 3
	  								group by JenisKelamin
	  								";
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
								CONCAT('
								<a href=PrintViewKelompokPDF.php?Status=' ,b.grp_name,' target=_blank><img border=\"0\" src=\"Images/pdf_button.png\" ></a>')  as prn,
								CONCAT('
								<a href=PrintViewKelompok.php?Status=' ,b.grp_name,' target=_blank>',b.grp_name,'</a>')  as Kelompok, count(a.p2g2r_grp_id) as Anggota
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
					$sSQL = "select  CONCAT('<a href=PrintViewStatusWarga.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as StatusKewargaan, count(a.per_ID) as Jumlah
							from person_per a , list_lst c
							WHERE a.per_cls_ID = c.lst_OptionID AND c.lst_ID = 1
							GROUP BY c.lst_OptionName ORDER by lst_OptionID";
						$perintah = mysql_query($sSQL);
						while ($hasilStWarga=mysql_fetch_array($perintah)){
						?>
						<tr>
						<td>*</td><td><?=$hasilStWarga[StatusKewargaan]?></td><td><?=$hasilStWarga[Jumlah]?></td><td>jiwa</td>
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
				$sSQL = "select CONCAT('<a href=PrintViewGolDarah.php?GolDarah=',c.lst_OptionName,' target=_blank>',c.lst_OptionName,'</a>') as GolDarah, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c6 = c.lst_OptionID AND c.lst_ID = 20
						GROUP BY c.lst_OptionName";
				$perintah = mysql_query($sSQL);
				while ($hasilGD=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilGD[GolDarah]?></td><td><?=$hasilGD[Jumlah]?></td><td>jiwa</td>
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
				$sSQL = "select CONCAT('<a href=PrintViewStatusKawin.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>')  as Status, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c15 = c.lst_OptionID AND c.lst_ID = 23 AND per_cls_ID < 3
						GROUP BY c.lst_OptionName ORDER by lst_OptionID";
				$perintah = mysql_query($sSQL);
				while ($hasilKawin=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilKawin[Status]?></td><td><?=$hasilKawin[Jumlah]?></td><td>jiwa</td>
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
 				$sSQL = "select CONCAT('<a href=PrintViewUltah.php?Bulan=',per_BirthMonth,' target=_blank>',nama_bulan,'</a>') as Bulan,
 				nama_bulan as NmBulan, 	count(per_ID) as Jumlah
 				from person_per, bulan
 				WHERE person_per.per_BirthMonth = bulan.kode AND per_cls_ID < 3
 				GROUP BY per_BirthMonth";
 				$perintah = mysql_query($sSQL);
 				while ($hasilGD=mysql_fetch_array($perintah)){
 				?>
 				<tr>
 				<td>*</td><td><?=$hasilGD[Bulan]?></td><td><?=$hasilGD[Jumlah]?></td><td>jiwa</td>
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
 				$sSQL = "select CONCAT('<a href=PrintViewStatusKawin.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>')  as Status, count(a.per_ID) as Jumlah
 						from person_per a , person_custom b , list_lst c
 						WHERE a.per_ID = b.per_ID AND b.c15 = c.lst_OptionID AND c.lst_ID = 23 AND per_cls_ID < 3
 						GROUP BY c.lst_OptionName ORDER by lst_OptionID";
 				$perintah = mysql_query($sSQL);
 				while ($hasilKawin=mysql_fetch_array($perintah)){
 				?>
 				<tr>
 				<td>*</td><td><?=$hasilKawin[Status]?></td><td><?=$hasilKawin[Jumlah]?></td><td>jiwa</td>
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
				$sSQL = "select CONCAT('<a href=PrintViewPendidikan.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Pendidikan, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c4 = c.lst_OptionID AND c.lst_ID = 18
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilDidik=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilDidik[Pendidikan]?></td><td><?=$hasilDidik[Jumlah]?></td><td>jiwa</td>
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
				$sSQL = "select CONCAT('<a href=PrintViewPekerjaan.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Pekerjaan, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c3 = c.lst_OptionID AND c.lst_ID = 17
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilKerja=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilKerja[Pekerjaan]?></td><td><?=$hasilKerja[Jumlah]?></td><td>jiwa</td>
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
				$sSQL = "select CONCAT('<a href=PrintViewPangkat.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Jabatan, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c5 = c.lst_OptionID AND c.lst_ID = 19
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilJabatan=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilJabatan[Jabatan]?></td><td><?=$hasilJabatan[Jumlah]?></td><td>jiwa</td>
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
				$sSQL = "select CONCAT('<a href=PrintViewProfesi.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Profesi, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c19 = c.lst_OptionID AND c.lst_ID = 24
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilProfesi=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilProfesi[Profesi]?></td><td><?=$hasilProfesi[Jumlah]?></td><td>jiwa</td>
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
				$sSQL = "select CONCAT('<a href=PrintViewMinat.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Minat, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c20 = c.lst_OptionID AND c.lst_ID = 25
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilMinat=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilMinat[Minat]?></td><td><?=$hasilMinat[Jumlah]?></td><td>jiwa</td>
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
		$sSQL = "select CONCAT('<a href=PrintViewMinatPelayanan.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Minat, count(a.per_ID) as Jumlah
						from person_per a , person_custom b , list_lst c
						WHERE a.per_ID = b.per_ID AND b.c35 = c.lst_OptionID AND c.lst_ID = 26
						GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilMinat=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilMinat[Minat]?></td><td><?=$hasilMinat[Jumlah]?></td><td>jiwa</td>
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
				$sSQL = "select CONCAT('<a href=PrintViewHobi.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Hobi, count(a.per_ID) as Jumlah
					from person_per a , person_custom b , list_lst c
					WHERE a.per_ID = b.per_ID AND b.c14 = c.lst_OptionID AND c.lst_ID = 22
					GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilHobi=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilHobi[Hobi]?></td><td><?=$hasilHobi[Jumlah]?></td><td>jiwa</td>
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