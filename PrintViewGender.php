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
$iStatus = FilterInput($_GET["GenderID"]);

$Judul = "Laporan - Detail Status Jenis Kelamin Jemaat"; 
require "Include/Header-Report.php";

?>


			<table border="0" width="700" cellspacing=0 cellpadding=0 >

			<tr>
			<td><b><font size="2">*</td>
			<td><b><font size="2">No</td>
			<td><b><font size="2">Gen</td>
			<td><b><font size="2">ID Klg</td>
			<td><b><font size="2">Hub</font></b></td>
			<td><b><font size="2">Nama</td>
			<td><b><font size="2">Tgl Lahir</td>
			<td><b><font size="2">Tel Rumah</td>
			<td><b><font size="2">Handphone</font></b></td>
			<td><b><font size="2">Kelompok</td>
			</tr>
			<?php
				$sSQL = "select IF(per_gender ='1', 'L', 'P') as Gender , per_fam_id as IdKelg,
						CASE per_fmr_id
								WHEN '1' THEN 'KK'
								WHEN '2' THEN 'Ist'
								WHEN '3' THEN 'Ank'
								WHEN '4' THEN 'Sdr'
								END AS HubKlg,
						per_FirstName as Nama ,
				         per_HomePhone as TelpRumah,per_Cellphone as Handphone,
				         per_WorkPhone as Kelompok,
				         per_birthDay as TGL, per_birthMonth as BLN, per_birthYear as THN
				         from person_per
							WHERE per_Gender = '$iStatus' AND per_cls_ID < 3
							ORDER BY per_WorkPhone, per_fam_id , per_fmr_id, per_BirthYear";
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
				<td>*</td><td><? echo $i ?></td>
				<td ALIGN=center><?=$hasilGD[Gender]?></td>
				<td><?=$hasilGD[IdKelg]?></td>
				<td><?=$hasilGD[HubKlg]?></td>
				<td><?=$hasilGD[Nama]?></td>
				<td><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
				<td><?=$hasilGD[TelpRumah]?></td>
				<td><?=$hasilGD[Handphone]?></td>
				<td><?=$hasilGD[Kelompok]?></td>
				</tr>
				<?}?>
			</table>
			<hr>
			Daftar ini hanya untuk warga dengan status "WARGA" dan "TITIPAN"



<?php
require "Include/Footer-Short.php";
?>
