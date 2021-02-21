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

$Judul = "Laporan - Detail Status Perkawinan Jemaat"; 
require "Include/Header-Report.php";

?>

<table border="0"  width="700" cellspacing=0 cellpadding=0 >

	<tr>
	<td> </td>
	<td>*</td>
	<td>No</td>
	<td>Status</td>
	<td>ID Kelg</td>
	<td>Nama</td>
	<td>Gender</td>
	<td>Telepon Rumah</td>
	<td>Th Lahir</td>
	<td>Kelompok</td>
			</tr>
			<?php
				$sSQL = "select c.lst_OptionName as StatusKawin, a.per_FirstName as Nama ,a.per_fam_id as IdKelg,
						 IF(a.per_gender ='1', 'L', 'P') as Gender,
				         per_HomePhone as TelpRumah, per_BirthYear as TahunLahir ,
				         per_WorkPhone as Kelompok
				         from person_per a , person_custom b , list_lst c
							WHERE a.per_ID = b.per_ID AND b.c15 = c.lst_OptionID AND c.lst_ID = 23
							AND c.lst_OptionID = '$iStatus' AND per_cls_ID < 3
							ORDER BY a.per_WorkPhone , a.per_fam_ID, a.per_fmr_id";
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
				<td> </td><td>*</td><td><? echo $i ?>
				<td><?=$hasilGD[StatusKawin]?></td><td><?=$hasilGD[IdKelg]?></td><td><?=$hasilGD[Nama]?></td>
				<td><?=$hasilGD[Gender]?></td><td><?=$hasilGD[TelpRumah]?></td><td><?=$hasilGD[TahunLahir]?></td><td><?=$hasilGD[Kelompok]?></td>
				</tr>
				<?}?>
			</table>



<?php
require "Include/Footer-Short.php";
?>
