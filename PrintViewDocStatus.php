<?php
/*******************************************************************************
 *
 *  filename    : PrintView.php
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

// Get the Kelompok ID from the querystring
$iKelompok = FilterInput($_GET["Kelompok"]);

$Judul = "Laporan - Detail Status Kelengkapan Dokumen Jemaat"; 
require "Include/Header-Report.php";

?>



			<table border="1"  width="750" cellspacing=0 cellpadding=0 >

			<tr>

			<td ALIGN=center><b>No</b></td>
			<td ALIGN=center><b>ID</b></td>
			<td ALIGN=center><b>ID.Klg</b></td>
			<td ALIGN=center><b>Nama Lengkap</b></td>
			<td ALIGN=center><b>Status</b></td>
			<td ALIGN=center><b>Kelompok</b></td>
			<td ALIGN=center><b>Hub</b></td>
			<td ALIGN=center><b>Gender</b></td>
			<td ALIGN=center><b>Foto</b></td>
			<td ALIGN=center><b>F.Klg</b></td>
			<td ALIGN=center><b>S.Nikah</b></td>
			<td ALIGN=center><b>SB.Ank</b></td>
			<td ALIGN=center><b>S.Sid</b></td>
			<td ALIGN=center><b>SB.Dws</b></td>
			</tr>
			<?php
//				echo $iKelompok;

				if($iKelompok == "")
				{
				$sSQL = "select per_ID as ID, per_FirstName as Nama ,
				         CASE per_cls_id
						 	WHEN '1' THEN 'Warga'
						 	WHEN '2' THEN 'Titipan'
						 	WHEN '3' THEN 'Tamu'
						 	WHEN '5' THEN 'Calon'
							WHEN '6' THEN 'Pindah'
							WHEN '7' THEN 'Meninggal'
							WHEN '8' THEN 'NonWarga'
							WHEN '9' THEN 'TdkAktif'
						END AS Status,				
				         per_WorkPhone as Kelompok, per_fam_ID as ID_Kelg,
				         CASE per_fmr_id
						 	WHEN '1' THEN 'KK'
						 	WHEN '2' THEN 'Ist'
						 	WHEN '3' THEN 'Ank'
						 	WHEN '4' THEN 'Sdr'
						END AS St_Kelg,
						CASE per_Gender
						 	WHEN '1' THEN 'L'
						 	WHEN '2' THEN 'P'
						END AS Gender
						
				         from person_per
						ORDER BY per_WorkPhone, per_fam_ID, per_fmr_ID,per_birthyear, per_FirstName";
				}else
				{
				$sSQL = "select per_ID as ID, per_FirstName as Nama ,
				         CASE per_cls_id
						 	WHEN '1' THEN 'Warga'
						 	WHEN '2' THEN 'Titipan'
						 	WHEN '3' THEN 'Tamu'
						 	WHEN '5' THEN 'Calon'
							WHEN '6' THEN 'Pindah'
							WHEN '7' THEN 'Meninggal'
							WHEN '8' THEN 'NonWarga'
							WHEN '9' THEN 'TdkAktif'
						END AS Status,					
						per_WorkPhone as Kelompok, per_fam_ID as ID_Kelg,
						CASE per_fmr_id
							WHEN '1' THEN 'KK'
							WHEN '2' THEN 'Ist'
							WHEN '3' THEN 'Ank'
							WHEN '4' THEN 'Sdr'
						END AS St_Kelg,
						CASE per_Gender
						 	WHEN '1' THEN 'L'
						 	WHEN '2' THEN 'P'
						END AS Gender
						from person_per
						WHERE per_WorkPhone like '%" . $iKelompok . "'
						ORDER BY per_WorkPhone, per_fam_ID, per_fmr_ID,per_birthyear, per_FirstName";
				}


//				$sSQL = "select per_ID as ID, per_FirstName as Nama ,
//				         per_WorkPhone as Kelompok, per_fam_ID as ID_Kelg, per_fmr_ID as St_Kelg
//				         from person_per
//							ORDER BY per_WorkPhone, per_fam_ID, per_fmr_ID, per_FirstName";
//				echo $sSQL;

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

				<td><? echo $i ?></td>
				<td><?=$hasilGD[ID]?></td>
				<td> <center><?=$hasilGD[ID_Kelg]?></center></td>
				<td><?=$hasilGD[Nama]?></td>
				<td><?=$hasilGD[Status]?></td>
				<td><center><?=$hasilGD[Kelompok]?></center></td>
				<td><center><?=$hasilGD[St_Kelg]?></center></td>
				<td><center><?=$hasilGD[Gender]?></center></td>

				<td><b><center><?
				$fileFOT = "Images/Person/" . $hasilGD[ID] .".jpg" ;
								if(file_exists($fileFOT))
								{
								echo "@" ;
								}else{ echo "" ;
								}
				?></b></center></td>

				<td><b><center><?
				$fileFAM = "Images/Family/" . $hasilGD[ID_Kelg] .".jpg" ;
								if(file_exists($fileFAM))
								{
								echo "@" ;
								}else{ echo " " ;
								}
				?></b></center></td>

				<td><b><center><? if($hasilGD[St_Kelg]=="KK" OR $hasilGD[St_Kelg]=="Ist"){
				$fileSNK = "Images/Nikah/SN" . $hasilGD[ID_Kelg] .".jpg" ;
								if(file_exists($fileSNK))
								{
								echo "@" ;
								}else{ echo " " ;
								}
				}
				?></b></center></td>

				<td><b><center><?
				$fileSBA = "Images/BaptisAnak/SBA" . $hasilGD[ID] .".jpg" ;
								if(file_exists($fileSBA))
								{
								echo "@" ;
								}else{ echo " " ;
								}
				?></b></center></td>

				<td><b><center><?
				$fileSDI = "Images/Sidhi/SS" . $hasilGD[ID] .".jpg" ;
								if(file_exists($fileSDI))
								{
								echo "@" ;
								}else{ echo " " ;
								}
				?></b></center></td>

				<td><b><center><?
				$fileSBD = "Images/BaptisDewasa/SBD" . $hasilGD[ID] .".jpg" ;
								if(file_exists($fileSBD))
								{
								echo "@" ;
								}else{ echo " " ;
								}
				?></b></center></td>



				</tr>
				<?}?>
			</table>
			Keterangan: <br>
			Hub : Hub Status Keluarga (KK:Kepala Keluarga, Ist:Istri/Pasangan, Ank:Anak, Sdr:Saudara)<br>
			Foto: Pas Foto Pribadi - F.Klg: Foto Keluarga<br>
			S.Nikah: SuratNikah - SB.Ank: Surat BaptisAnak - S.Sid: SuratSidhi - SB.Dws: Surat BaptisDewasa


</div>

<?php
require "Include/Footer-Short.php";
?>
