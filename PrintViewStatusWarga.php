<?php
/*******************************************************************************
 *
 *  filename    : PrintView.php
 *  last change : 2003-01-29
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2003 Phillip Hullquist, Deane Barker, Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
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

$Judul = "Laporan - Detail Status Kewargaan Jemaat"; 
require "Include/Header-Report.php";

?>
			<table border="0"  width="900" cellspacing=0 cellpadding=0 >

			<tr>
			<td ALIGN=center><b>No</b></td>
			<td ALIGN=center><b>ID.Klg</b></td>
			<td ALIGN=center><b>Hub</b></td>
			<td ALIGN=center><b>Gen</b></td>
			<td ALIGN=center><b>Nama Lengkap</b></td>
			<td ALIGN=center><b>Telp Rumah</b></td>
			<td ALIGN=center><b>Handphone</b></td>
			<td ALIGN=center><b>Tgl Lahir</b></td>
			<td ALIGN=center><b>Klpk</b></td>
			<td ALIGN=center><b>Status</b></td>
			</tr>
			<?php
				$sSQL = "select IF(per_gender ='1', 'L', 'P') as Gender,
						per_ID as ID, c.lst_OptionName as StatusKewargaan, a.per_FirstName as Nama ,
						per_fam_ID as ID_Kelg,
				         per_HomePhone as TelpRumah, per_BirthYear as TahunLahir ,
				         CASE per_fmr_id
						 						 	WHEN '1' THEN 'KK'
						 						 	WHEN '2' THEN 'Ist'
						 						 	WHEN '3' THEN 'Ank'
						 						 	WHEN '4' THEN 'Sdr'
						END AS St_Kelg,per_birthDay as TGL, per_birthMonth as BLN, per_birthYear as THN,
				         TRIM(per_WorkPhone) as Kelompok ,per_Cellphone as Handphone
				         FROM person_per a , list_lst c
							WHERE a.per_cls_ID = c.lst_OptionID AND c.lst_ID = 1
							AND c.lst_OptionID = '$iStatus'
							ORDER BY TRIM(a.per_WorkPhone), per_fam_ID, per_fmr_id, a.per_firstname";
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
				<td><center><?=$hasilGD[ID_Kelg]?></center></td>
				<td><center><?=$hasilGD[St_Kelg]?></td>
				<td><center><?=$hasilGD[Gender]?></center></td>
				<td><?=$hasilGD[Nama]?></td>
				<td><?=$hasilGD[TelpRumah]?></td>
				<td><?=$hasilGD[Handphone]?></td>
				<td><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
				<td><?=$hasilGD[Kelompok]?></td>
				<td><?=$hasilGD[StatusKewargaan]?></td>
				</tr>
				<?}?>
			</table>



<?php
require "Include/Footer-Short.php";
?>
