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

// Get the Gol Darah ID from the querystring
$iStatus = FilterInput($_GET["Status"]);

$Judul = "Laporan - Warga Yang Belum Ada Satmbuk"; 
require "Include/Header-Report.php";


?>

	<table border="0"  width="700" cellspacing=0 cellpadding=0 >

	<tr>
	<td ALIGN=center><b><font size="2">No.</font></b></td>
	<td ALIGN=center><b><font size="2">Per ID</b></td>
	<td ALIGN=center><b><font size="2">Stambuk</b></td>
	<td ALIGN=center><b><font size="2">Nama Lengkap</b></td>
	<td ALIGN=center><b><font size="2">Nama Split</b></td>
	<td ALIGN=center><b><font size="2">Tanggal</b></td>
	<td ALIGN=center><b><font size="2">Bulan</b></td>
	<td ALIGN=center><b><font size="2">Tahun</b></td>
	<td ALIGN=center><b><font size="2">Kelompok</b></td>
	</tr>
			<?php
				$sSQL = "select per_id,per_lastname as stambuk,per_firstname as nama , 
					per_birthday as TGL,per_birthmonth as BLN ,per_birthyear as THN , per_workphone as Kelompok
						from person_per 
						where 
						per_lastname = '' ";
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
				<td ALIGN=center><?=$hasilGD[per_id]?></td>
				<td ALIGN=left><?=$hasilGD[stambuk] ?></td>
				<td ALIGN=center><?=$hasilGD[nama]; $Cari=explode(" ",$hasilGD[nama],3)?></td>
				<td ALIGN=center><?=$Cari[0]?> - <?=$Cari[1]?> - <?=$Cari[2]?></td>
				<td ALIGN=center><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
				<td ALIGN=center><?=$hasilGD[Kelompok]; $kel=trim($hasilGD[Kelompok]) ?></td>
				</tr>

				<?php
				// Cari Data di Stambuk
					$stSQL = "select nama,stambuk,tgllahir,blnlahir,thnlahir,kelompok 
						from stambuk
						where 
						nama like '%$Cari[0]%' AND kelompok like '%$kel%'  ";
				
				// Cari Data di Stambuk
				//	$stSQL = "select nama,stambuk,tgllahir,blnlahir,thnlahir,kelompok 
				//		from stambuk
				//		where 
				//		nama like '$Cari[0]%' AND kelompok like '%$kel%'  ";
				//
				//	$stSQL = "select nama,stambuk,tgllahir,blnlahir,thnlahir,kelompok 
				//		from stambuk
				//		where 
				//		nama like '$Cari[0]%'";

				//echo $stSQL ;

				$perintahst = mysql_query($stSQL);
					while ($hasilST=mysql_fetch_array($perintahst))
					{
					extract($hasilST);
					?>
					<tr class="<?php echo $sRowClass; ?>">
					<td ALIGN=center><? echo $i ?></td>
					<td ALIGN=center></td>
					<td ALIGN=left><?=$hasilST[stambuk]?></td>
					<td ALIGN=left><?=$hasilST[nama]?></td>
					<td ALIGN=center></td>
					<td ALIGN=left><?=$hasilST[tgllahir]?></td>
					<td ALIGN=left><?=$hasilST[blnlahir]?></td>
					<td ALIGN=left><?=$hasilST[thnlahir]?></td>
					<td ALIGN=left><?=$hasilST[kelompok]?></td>
					</tr>
					<?}?>				

				<?}?>
			</table>



<?php
require "Include/Footer-Short.php";
?>
