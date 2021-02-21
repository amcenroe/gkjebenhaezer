<?php
/*******************************************************************************
 *
 *  filename    : PrintViewCetakDPT.php
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
$iKel = FilterInput($_GET["Kel"]);

$Judul = "Laporan - Daftar Hadir - Daftar Pemilih Tetap (DPT)"; 
require "Include/Header-Report.php";

$ANDKelas = 'AND a2.lst_OptionName like ';
$GetKelas = FilterInput($_GET["Klas"]);

if ( $iKel == '' ) {
	$Kel = " ";
} else {
	$Kel = "( per_WorkPhone like '%$iKel%' )  AND ";
}


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

				echo "Tampilkan Data : ";
					
				$sSQL0 = "select DISTINCT(TRIM(per_workphone)) as Kelompok
						FROM person_per 
						ORDER BY  Kelompok";

				//echo $sSQL0 ;
				$perintah = mysql_query($sSQL0);
				$i = 0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{
					$i++;
					extract($hasilGD);
					
					echo "<a href=PrintViewCetakDPT.php?Kel=".$hasilGD[Kelompok]. "> ".$hasilGD[Kelompok]. " </a> | ";
				}
				echo "<a href=PrintViewCetakDPT.php> Semua</a> ";
?>

			<table border="1"  width="1200" cellspacing=0 cellpadding=0 >
			<u><b> </b></u><br>
			<tr><b><font size="2">
			
			<td align=CENTER ><b><font size="2">No. </font></b></td>
			<td align=CENTER ><b><font size="2">Klpk </font></b></td>
			<td align=CENTER ><b><font size="2">IDKlg</font></b></td>
			<td align=CENTER ><b><font size="2">ID </font></b></td>
			<td align=CENTER ><b><font size="2"> </font></b></td>
			<td align=CENTER ><b><font size="2">Nama Lengkap</font></b></td>
			<td align=CENTER ><b><font size="2">Alamat Lengkap</font></b></td>
			<td align=CENTER ><b><font size="2">Cetak Undangan per Keluarga</font></b></td>
			<td align=CENTER ><b><font size="2">Cetak Undangan per Individu</font></b></td>

			</font></b></tr>
			<?php
			 	$sRowClass = "RowColorA";

				$sSQL = "select TRIM(per_workphone) as Kelompok, per_ID as KodeID, per_fam_id as FamID, 
							CONCAT('<a href=FamilyView.php?FamilyID=',per_fam_ID,'>',per_fam_id,'</a>')  as KodeKelg, fam_Name as NamaKeluarga, 
							CONCAT('<a href=PersonView.php?PersonID=',per_ID,'>',per_id,'</a>') as Kode, per_FirstName AS NamaLengkap, 
							CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_Birthday) as 'TglLahir', 
							CASE per_fmr_id
								WHEN '1' THEN '<b><font color=\"blue\">KK</font></b>'
								WHEN '2' THEN 'Ist'
								WHEN '3' THEN 'Ank'
								WHEN '4' THEN 'Sdr'
								END AS HubKlg,
							IF (per_cls_ID='1','Warga','Titipan') as Kewargaan, 
							per_membershipdate as TglDaftar,
							IF (per_Gender='1','L','P') as JnsKelamin,
							IF (c15='2','Menikah','-') as Status,
							IF(c2='0000-00-00 00:00:00','-',c2) as TglSidhi,
							IF(c27 is NULL,'-',c27) as TmpSidhi, IF(c18='0000-00-00 00:00:00','-',c18) as TglBaptisDewasa,
							IF(c28 is NULL,'-',c28) as TmpBaptisDewasa, fam_Address1 as Alamat
						FROM person_per a natural join person_custom 
							LEFT JOIN family_fam b ON a.per_fam_ID = b.fam_ID
							LEFT JOIN list_lst a1 ON a1.lst_OptionID = a.per_fmr_ID AND a1.lst_ID = 2
							LEFT JOIN list_lst a2 ON a2.lst_OptionID = a.per_cls_ID AND a2.lst_ID = 1
						
						WHERE $Kel (per_cls_id < 3) AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
							AND c27 is not NULL )
							OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2)
						ORDER BY  Kelompok, per_fam_id, per_fmr_id, per_FirstName";

		
						
				//echo $sSQL ;
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
				<td align=CENTER ><font size="2"><a href="PrintViewUndanganDPT.php?perID=<?=$hasilGD[KodeID]?>&Mode=2" target="_BLANK" ><? echo $i ?></a></td>
				<td align=CENTER ><font size="2"><?=$hasilGD[Kelompok]?></td>
				<td align=CENTER ><font size="2"><?=$hasilGD[KodeKelg]?></td>
				<td align=CENTER ><font size="2"><?=$hasilGD[Kode]?></td>
				<td ALIGN=center>
				<?
				$fileFOT = "Images/Person/" . $hasilGD[KodeID] .".jpg" ;
				//echo file_exists("Images/Person/" . $hasilGD[KodeID] .".jpg");
				//echo $fileFOT;
								if(file_exists($fileFOT))
								{
								echo "<img border=\"0\" src=\"Images/Person/thumbnails/".$hasilGD[KodeID].".jpg\" width=\"25\" >" ;
								}else{ echo "<img border=\"0\" src=\"Images/NoData.gif\" width=\"30\" >" ;
								}
				?></td>
				
				<td><font size="2"><?
				if ($hasilGD[JnsKelamin]=='L' AND $hasilGD[Status]=='Menikah'){ 
					$Sapaan='. Bp.' ;
				} elseif ($hasilGD[JnsKelamin]=='P' AND $hasilGD[Status]=='Menikah'){ 
					$Sapaan='. Ibu.' ;
				} elseif ($hasilGD[JnsKelamin]=='L' AND $hasilGD[Status]!='Menikah'){ 
					$Sapaan='. Sdr.' ;
				} elseif ($hasilGD[JnsKelamin]=='P' AND $hasilGD[Status]!='Menikah'){ 
					$Sapaan='. Sdri.' ;
				}
				
				echo $Sapaan;
				?>
				
				<font size="2"><?=$hasilGD[NamaLengkap]?></td>
				<td><font size="1"><?=$hasilGD[Alamat]?></td>
				<td align=CENTER ><font size="2"><a href="PrintViewUndanganDPT2.php?famID=<?=$hasilGD[FamID]?>&Mode=2" target="_BLANK" ><? echo $FamID ?></a></td>
				<td align=CENTER ><font size="2"><a href="PrintViewUndanganDPT.php?perID=<?=$hasilGD[KodeID]?>&Mode=2" target="_BLANK" ><? echo $KodeID ?></a></td>
				</tr>
				<?}?>
			</table>



<?php
require "Include/Footer-Short.php";
?>
