<?php
/*******************************************************************************
 *
 *  filename    : PrintViewKelompok.php
 *  last change : 2003-01-29
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2003 Phillip Hullquist, Deane Barker, Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2010 Erwin Pratama for GKJ Bekasi Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";
// Get the Gol Darah ID from the querystring
$iStatus = FilterInput($_GET["Status"]);
$iHub = FilterInput($_GET["Hub"]);

$Judul = "Laporan - Detail Daftar Anggota Jemaat per Kelompok"; 
require "Include/Header-Report.php";

$ANDKelas = 'AND a2.lst_OptionName like ';
$GetKelas = FilterInput($_GET["Klas"]);


if ( $iHub == '' ) {
	$Hubungan = " ";
} else {
	$Hubungan = "AND per_fmr_ID = '$iHub'  ";
}


if ( $GetKelas == '' ) {
	$Kelas = " ";
} else {
	$Kelas = "$ANDKelas '$GetKelas'  ";
}

//$Kelas = "$ANDKelas '$GetKelas'  ";


//echo $Kelas
?>
Tampilkan Status Kewargaan :
<a href=PrintViewKelompok.php?Status=<?=$iStatus?> >Semua</a> |
<a href=PrintViewKelompok.php?Status=<?=$iStatus?>&Klas=Warga&Hub=<? if ( $iHub <> '' ) { echo 1; }; ?> >Warga</a> |
<a href=PrintViewKelompok.php?Status=<?=$iStatus?>&Klas=Titipan&Hub=<? if ( $iHub <> '' ) { echo 2; }; ?>  >Titipan</a> |
<a href=PrintViewKelompok.php?Status=<?=$iStatus?>&Klas=Calon&Hub=<? if ( $iHub <> '' ) { echo 3; }; ?>  Warga >Calon Warga</a> |
<a href=PrintViewKelompok.php?Status=<?=$iStatus?>&Klas=Meninggal&Hub=<? if ( $iHub <> '' ) { echo 4; }; ?>  >Meninggal</a> |
<a href=PrintViewKelompok.php?Status=<?=$iStatus?>&Klas=Pindah&Hub=<? if ( $iHub <> '' ) { echo 5; }; ?>  >Pindah</a> |

			<table border="0"  width="1100" cellspacing=0 cellpadding=0 >
			<u><b> </b></u><br>
			<tr><b><font size="2">
			
			<td><b><font size="2">No. </font></b></td>
			<td><b><font size="2"> </font></b></td>
			<td><b><font size="2">ID </font></b></td>
			<td><b><font size="2">ID Klg</font></b></td>
			<td><b><font size="2">Hub</font></b></td>
			<td><b><font size="2">Nama Lengkap</font></b></td>
			<td><b><font size="2">Gen</font></b></td>
			<td><b><font size="2">Tgl Lahir</font></b></td>
			<td><b><font size="2"> </font></b></td>
			<td><b><font size="2">Alamat Lengkap</font></b></td>
			<td><b><font size="2">Telp Rumah</font></b></td>
			<td><b><font size="2"> </font></b></td>
			<td><b><font size="2">Handphone</font></b></td>
			<td><b><font size="2">Status</font></b></td>
			</font></b></tr>
			<?php
			 	$sRowClass = "RowColorA";
				$sSQL = "SELECT IF(per_gender ='1', 'L', 'P') as Gender,
							CASE per_fmr_id
								WHEN '1' THEN '<b><font color=\"blue\">KK</font></b>'
								WHEN '2' THEN 'Ist'
								WHEN '3' THEN 'Ank'
								WHEN '4' THEN 'Sdr'
								END AS HubKlg,
							 CONCAT('<a href=PersonView.php?PersonID=',per_ID,'>',per_ID,'</a>') as ID,
							 CONCAT('<a href=FamilyView.php?FamilyID=',per_fam_ID,'>',per_fam_ID,'</a>') as IDKelg,
							 per_FirstName as Nama, fam_HomePhone as TelpRumah, per_Cellphone as Handphone, a1.lst_OptionName as Hub,
							fam_Name as NmKelg, fam_Address1 as Alamat, a2.lst_OptionName as Kewargaan,
							per_birthDay as TGL, 
							CASE per_birthMonth 
								WHEN '1' THEN 'Jan'
								WHEN '2' THEN 'Feb'
								WHEN '3' THEN 'Mar'
								WHEN '4' THEN 'Apr'
								WHEN '5' THEN 'Mei'
								WHEN '6' THEN 'Jun'
								WHEN '7' THEN 'Jul'
								WHEN '8' THEN 'Agt'
								WHEN '9' THEN 'Sep'
								WHEN '10' THEN 'Okt'
								WHEN '11' THEN 'Nov'
								WHEN '12' THEN 'Des'
								END as BLN, 
							
							per_birthYear as THN
						FROM person_per
						LEFT JOIN family_fam ON person_per.per_fam_ID = family_fam.fam_ID
						LEFT JOIN list_lst as a1 ON a1.lst_OptionID = person_per.per_fmr_ID AND a1.lst_ID = 2
						LEFT JOIN list_lst as a2 ON a2.lst_OptionID = person_per.per_cls_ID AND a2.lst_ID = 1
						WHERE per_WorkPhone like '%$iStatus%'
							$Kelas $Hubungan 
						ORDER BY family_fam.fam_Name, person_per.per_fmr_ID, person_per.per_BirthYear";


				//$sSQL = "select c.lst_OptionName as StatusKewargaan, a.per_FirstName as Nama ,
				//         per_HomePhone as TelpRumah, per_BirthYear as TahunLahir ,
				//         per_WorkPhone as Kelompok from person_per a , list_lst c
				//			WHERE a.per_cls_ID = c.lst_OptionID AND c.lst_ID = 1
				//			AND c.lst_OptionID = '$iStatus'
				//			ORDER BY a.per_WorkPhone";
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
				<td><font size="1"><? echo $i ?></td><td></td>
				<td><font size="1"><?=$hasilGD[ID]?></td>
				<td><font size="1"><?=$hasilGD[IDKelg]?></td>
				<td><font size="1"><?=$hasilGD[HubKlg]?></td>
				<td><font size="1"><?=$hasilGD[Nama]?></td>
				<td ALIGN=center><font size="1"><?=$hasilGD[Gender]?></td>
				<td ALIGN=center><font size="1"><?=$hasilGD[TGL]?>-<?=$hasilGD[BLN]?>-<?=$hasilGD[THN]?></td>
				<td>.</td>
				<td><font size="1"><?=$hasilGD[Alamat]?></td>
				<td><font size="1"><?=$hasilGD[TelpRumah]?></td>
				<td><font size="1"> . </td>
				<td><font size="1"><?=$hasilGD[Handphone]?></td>
				<td ALIGN=center><font size="1"><?=$hasilGD[Kewargaan]?></td>
				</tr>
				<?}?>
			</table>



<?php
require "Include/Footer-Short.php";
?>
