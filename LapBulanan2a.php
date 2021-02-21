<html>
<head>

</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >
<?php
/*******************************************************************************
 *
 *  filename    : BulananReport.php
 *  last change : 2003-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *
 ******************************************************************************/

// Include the function library

require "Include/Config.php";
require "Include/Functions.php";



// Get the list of custom person
$sSQL = "SELECT count(per_ID) FROM person_per ";
$perintah = mysql_query($sSQL);
$semua = mysql_result($perintah,0);

// Get the list of custom person null
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE per_BirthYear is null" ;
$perintah = mysql_query($sSQL);
$nodata = mysql_result($perintah,0);

// Get the list of custom person Anak 0-7 th
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 0 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (7 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$anak = mysql_result($perintah,0);

// Get the list of custom person Anak 8-16 th
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 8 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (16 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$remaja = mysql_result($perintah,0);

// Get the list of custom person Anak 17-25 th
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 17 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (25 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$pemuda = mysql_result($perintah,0);

// Get the list of custom person Anak 26-50 th
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 26 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (50 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$dewasa = mysql_result($perintah,0);


// Get the list of custom person Anak 51-120 th
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 51 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (200 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$lansia = mysql_result($perintah,0);


// Get the list of custom person Laki2
$sSQL = "SELECT COUNT(per_ID) FROM person_per WHERE per_Gender = 1 ";
$perintah = mysql_query($sSQL);
$laki = mysql_result($perintah,0);

// Get the list of custom person Perempuan
$sSQL = "SELECT COUNT(per_ID) FROM person_per WHERE per_Gender = 2 ";
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
$sSQL = "SELECT COUNT(per_ID) FROM person_per WHERE per_MembershipDate like '$bulanini' ";
$perintah = mysql_query($sSQL);
$atestasi_masuk = mysql_result($perintah,0);

?>

<table border="0" bgcolor=#FFFFFF width="750" cellspacing=0 cellpadding=0 align=center>

  <tr><!-- Row 2 -->
     <td valign=top align=center>
     <img border="0" src="gkj_logo.jpg" width="120" >
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b><font size="5">GEREJA KRISTEN JAWA BEKASI </font></b><BR>
	 <b><font size="5">Wilayah Timur </font></b><br>
	 <b><font size="2">Laporan Bulanan (Executive Sumary)</font></b><br>
	 <hr>
	 <b>Bulan : <?php echo date("F, Y"); ?>

	 		</b><br>
		</b>

	 </td><!-- Col 2 -->

     <td valign=top align=right >

     </td><!-- Col 3 -->
  </tr>

</table>


<table border="0"  width="750" cellspacing=0 cellpadding=0 align=center>

  <tr>
     <td valign=top align=left>
    	<br>
		<u><b>Informasi Jumlah Jemaat </b></u><br>
		&nbsp* Jumlah Total Jemaat : <?php echo $semua ?> jiwa<br>
		&nbsp* Jumlah Total Keluarga : <?php echo $keluarga ?> keluarga<br>
		<br>
		<u><b>Informasi Jemaat Berdasarkan Rentang Usia </b></u><br>
		&nbsp* Jumlah Jemaat Anak: <?php echo $anak ?> jiwa<br>
		&nbsp* Jumlah Jemaat Remaja: <?php echo $remaja ?> jiwa<br>
		&nbsp* Jumlah Jemaat Pemuda: <?php echo $pemuda ?> jiwa<br>
		&nbsp* Jumlah Jemaat Dewasa: <?php echo $dewasa ?> jiwa<br>
		&nbsp* Jumlah Jemaat Lansia: <?php echo $lansia ?> jiwa<br>
		&nbsp* Jumlah Jemaat Data Tidak Lengkap : <?php echo  $nodata ?><br>
		<br>
		<u><b>Informasi Berdasarkan Jenis Kelamin </b></u><br>
		&nbsp* Jumlah Jemaat Laki Laki: <?php echo $laki ?><br>
		&nbsp* Jumlah Jemaat Perempuan: <?php echo $perempuan ?><br>
		<br>
		<u><b>Informasi Atestasi </b></u><br>
		&nbsp* Jumlah Jemaat Atestasi Masuk : <?php echo $atestasi_masuk ?><br>
		&nbsp* Jumlah Jemaat Atestasi Keluar : <br>
		<br>

			<table border="0"  width="200" cellspacing=0 cellpadding=0>
			<u><b>Anggota Jemaat per Kelompok </b></u><br>
			<?php
				$sSQL = "select b.grp_name as Kelompok, count(a.p2g2r_grp_id) as Anggota
						from person2group2role_p2g2r a, group_grp b, person_per c
						where a.p2g2r_grp_id = b.grp_id AND a.p2g2r_per_id = c.per_ID
						group by b.grp_id order by b.grp_id ";
				$perintah = mysql_query($sSQL);
				while ($hasil=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasil[Kelompok]?></td><td><?=$hasil[Anggota]?></td>
				</tr>
				<?}?>
			</table>
		<br>

			<table border="0"  width="200" cellspacing=0 cellpadding=0>
			<u><b>Golongan Darah </b></u><br>
			<?php
				$sSQL = "select CONCAT('<a href=PrintViewGolDarah.php?GolDarah=',c.lst_OptionName,' target=_blank>',c.lst_OptionName,'</a>') as GolDarah, count(a.per_ID) as Jumlah
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

		<br>
			<table border="0"  width="200" cellspacing=0 cellpadding=0>
			<u><b>Status Kewargaan</b></u><br>
			<?php
			$sSQL = "select  CONCAT('<a href=PrintViewStatusWarga.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as StatusKewargaan, count(a.per_ID) as Jumlah
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

		<br>
			<table border="0"  width="200" cellspacing=0 cellpadding=0>
			<u><b>Status Perkawinan</b></u><br>
			<?php
				$sSQL = "select CONCAT('<a href=PrintViewStatusKawin.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>')  as Status, count(a.per_ID) as Jumlah
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

		<br>
			<table border="0"  width="200" cellspacing=0 cellpadding=0>
			<u><b>Jenjang Pendidikan</b></u><br>
			<?php
				$sSQL = "select CONCAT('<a href=PrintViewPendidikan.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Pendidikan, count(a.per_ID) as Jumlah
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

		<br>
			<table border="0"  width="200" cellspacing=0 cellpadding=0>
			<u><b>Informasi Pekerjaan</b></u><br>
			<?php
				$sSQL = "select CONCAT('<a href=PrintViewPekerjaan.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Pekerjaan, count(a.per_ID) as Jumlah
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

		<br>
			<table border="0"  width="200" cellspacing=0 cellpadding=0>
			<u><b>Jenjang Jabatan / Pangkat</b></u><br>
			<?php
				$sSQL = "select CONCAT('<a href=PrintViewPangkat.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Jabatan, count(a.per_ID) as Jumlah
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

		<br>
			<table border="0"  width="200" cellspacing=0 cellpadding=0>
			<u><b>Profesi / Keahlian</b></u><br>
			<?php
				$sSQL = "select CONCAT('<a href=PrintViewProfesi.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Profesi, count(a.per_ID) as Jumlah
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

		<br>
			<table border="0"  width="200" cellspacing=0 cellpadding=0>
			<u><b>Hobi</b></u><br>
			<?php
				$sSQL = "select CONCAT('<a href=PrintViewHobi.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Hobi, count(a.per_ID) as Jumlah
					from person_per a , person_custom b , list_lst c
					WHERE a.per_ID = b.per_ID AND b.c23 = c.lst_OptionID AND c.lst_ID = 22
					GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
				$perintah = mysql_query($sSQL);
				while ($hasilHobi=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td>*</td><td><?=$hasilHobi[Hobi]?></td><td><?=$hasilHobi[Jumlah]?></td>
				</tr>
			<?}?>
			</table>

		<br>
			<table border="0"  width="200" cellspacing=0 cellpadding=0>
			<u><b>Minat </b></u><br>
			<?php
				$sSQL = "select CONCAT('<a href=PrintViewMinat.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Minat, count(a.per_ID) as Jumlah
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

		<br>
				<table border="0"  width="200" cellspacing=0 cellpadding=0>
				<u><b>Minat Pelayanan</b></u><br>
				<?php
				$sSQL = "select CONCAT('<a href=PrintViewMinatPelayanan.php?Status=',c.lst_OptionID,' target=_blank>',c.lst_OptionName,'</a>') as Minat, count(a.per_ID) as Jumlah
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



     <td valign=top align=right width=300 >

		<?php
		$refresh = microtime() ;
		echo "<img src=\"graph_umurjemaat2.php?ANK=$anak&amp;RMJ=$remaja&amp;PMD=$pemuda&amp;DWS=$dewasa&amp;LNS=$lansia&amp;$refresh \"><br>" ;
		?>

		<?php
		echo "<img src=\"graph_jeniskelamin.php?lakilaki=$laki&amp;perempuan=$perempuan&amp;$refresh \"><br>" ;
		?>

		<?php
		echo "<img src=\"graph_kelompok.php?&amp;$refresh \"><br>" ;
		?>
		<?php
		echo "<img src=\"graph_goldarah.php?&amp;$refresh \"><br>" ;
		echo "<img src=\"graph_statuswarga.php?&amp;$refresh \" width=\"360\" ><br>" ;
		echo "<img src=\"graph_statuskawin.php?&amp;$refresh \"><br>" ;
		echo "<img src=\"graph_statuspendidikan.php?&amp;$refresh \" width=\"360\" ><br>" ;
		echo "<img src=\"graph_statuspekerjaan.php?&amp;$refresh \" width=\"360\" ><br>" ;
		echo "<img src=\"graph_statusjabatan.php?&amp;$refresh \"><br>" ;
		echo "<img src=\"graph_statusprofesi.php?&amp;$refresh \"><br>" ;
		echo "<img src=\"graph_statushobi.php?&amp;$refresh \"><br>" ;
		echo "<img src=\"graph_statusminat.php?&amp;$refresh \"><br>" ;
		echo "<img src=\"graph_statusminatpelayanan.php?&amp;$refresh \"><br>" ;
		?>


	</td>

	</tr>

</table>
<?php

require "Include/Footer-Short.php";
?>