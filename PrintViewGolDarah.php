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
$iGolDarah = FilterInput($_GET["GolDarah"]);

$Judul = "Laporan - Detail Golongan Darah Jemaat"; 
require "Include/Header-Report.php";


?>

			<table border="0"  width="700" cellspacing=0 cellpadding=0 >

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
						<td ALIGN=center><b>Gol</b></td>
			</tr>
			<?php
				$sSQL = "select IF(per_gender ='1', 'L', 'P') as Gender,
						c.lst_OptionName as GolonganDarah, a.per_FirstName as Nama ,
						per_fam_ID as ID_Kelg,
						CASE per_fmr_id
						 	WHEN '1' THEN 'KK'
						 	WHEN '2' THEN 'Ist'
						 	WHEN '3' THEN 'Ank'
						 	WHEN '4' THEN 'Sdr'
						END AS St_Kelg,
				         per_HomePhone as TelpRumah, per_Cellphone as Handphone,
				         per_BirthDay as TGL, per_BirthMonth as BLN, per_BirthYear as THN ,
				         per_WorkPhone as Kelompok from person_per a , person_custom b , list_lst c
							WHERE a.per_ID = b.per_ID AND b.c6 = c.lst_OptionID AND c.lst_ID = 20
							AND c.lst_OptionName = '$iGolDarah'
							ORDER BY per_WorkPhone";

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
				<td><?=$hasilGD[GolonganDarah]?></td>
				</tr>





				<?}?>
			</table>



<?php
require "Include/Footer-Short.php";
?>
