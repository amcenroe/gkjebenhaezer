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

// Get the Profesi ID from the querystring
$iStatus = FilterInput($_GET["Status"]);

$Judul = "Laporan - Status Profesi"; 
require "Include/Header-Report.php";

?>
	<table border="0"  width="700" cellspacing=0 cellpadding=0 >

	<tr>
	<td ALIGN=center><b><font size="2">No.</font></b></td>
	<td ALIGN=center><b><font size="2">Status Profesi</b></td>
	<td ALIGN=center><b><font size="2">Nama</b></td>
	<td ALIGN=center><b><font size="2">Telepon Rumah</b></td>
	<td ALIGN=center><b><font size="2">Tahun Kelahiran</b></td>
	<td ALIGN=center><b><font size="2">Kelompok</b></td>
	</tr>
			<?php
				$sSQL = "select c.lst_OptionName as StatusKerja, a.per_FirstName as Nama ,
				         per_HomePhone as TelpRumah, per_BirthYear as TahunLahir ,
					 per_birthDay as TGL, per_birthMonth as BLN, per_birthYear as THN, 
				         per_WorkPhone as Kelompok
				         from person_per a , person_custom b , list_lst c
							WHERE a.per_ID = b.per_ID AND b.c19 = c.lst_OptionID AND c.lst_ID = 24
							AND c.lst_OptionID = '$iStatus'
							ORDER BY a.per_WorkPhone";
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
				<td ALIGN=center><? echo $i ?></td>
				<td ALIGN=center><?=$hasilGD[StatusKerja]?></td>
				<td ALIGN=left><?=$hasilGD[Nama]?></td>
				<td ALIGN=center><?=$hasilGD[TelpRumah]?></td>
				<td ALIGN=center><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
				<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
				</tr>
				<?}?>
			</table>



<?php
require "Include/Footer-Short.php";
?>
