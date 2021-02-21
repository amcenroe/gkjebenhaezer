<?php
/*******************************************************************************
 *
 *  filename    : ReportList.php
 *  last change : 2003-03-20
 *  website     : http://www.infocentral.org
 *  copyright   : Copyright 2003 Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

require 'Include/Config.php';
require 'Include/Functions.php';

//Set the page title
$sPageTitle = gettext('Menu Laporan');

$today = getdate();
$year = $today['year'];

require 'Include/Header.php';

		$logvar1 = "Report";
		$logvar2 = "Menu List";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);


?>

<p>
<a class="MediumText" href="LapBulanan.php?PersonID=5" target="_blank"><?php
    echo gettext('Laporan Kewargaan Bulanan'); ?></a>
<br>

<?php
    echo gettext('Laporan Jumlah Detail Aktifitas Kewargaan.'); ?>
</p>


<p>
<a class="MediumText" href="GroupReports.php"><?php
    echo gettext('Laporan Per-Kelompok'); ?></a>
<br>
<?php
    echo gettext('Laporan Detail Anggota Kelompok (Format PDF).'); ?>
</p>


<?php if ($bCreateDirectory) { ?>
	<p>
	<a class="MediumText" href="DirectoryReports.php"><?php echo gettext('Laporan Daftar Jemaat'); ?></a>
	<br>
	<?php echo gettext('Daftar Jemaat di Kelompokkan berdasarkan ABJAD nama Keluarga'); ?>
	</p>
<?php } ?>


<p>
<a class="MediumText" href="PrintViewProgresDB.php" target="_blank"><?php echo gettext('Laporan Progress Updating Database'); ?></a>
<br>
<?php echo gettext('Laporan Progress Pengerjaan Update Database dari Data Awal.'); ?>
</p>


<p>
<a class="MediumText" href="PrintViewDocStatus.php" target="_blank"><?php
    echo gettext('Laporan Detail Status Kelengkapan Dokumen Warga'); ?></a>
<br>
Laporan Per Kelompok :

		<table table style="text-align: left; width: 50%;" border="0" cellpadding="0" cellspacing="0"><tbody>
			<tr>			
			<td>*</td>
			<td><font size=2><b><i>Kelompok</i></b></td>
			<td><font size=2><b><i>Data Foto</i></b></td>								
			<td><font size=2><b><i>Cetak Detail</i></b></td>
			<td><font size=2><b><i>Keluarga</i></b></td>			
			<td><font size=2><b><i>Keterangan</i></b></td>
			</tr>
			
		<?php
		$sSQL = "select  
			CONCAT('<a href=PrintViewDocStatus.php?Kelompok=',grp_Name,' target=_blank>',grp_Name,'</a>') Kelompok ,
			CONCAT('<a href=PrintViewDocStatus2.php?Kelompok=',grp_Name,' target=_blank>',grp_Name,'</a>') Foto ,
			CONCAT('<a href=PrintViewAll.php?Kelompok=',grp_Name,' target=_blank>',grp_Name,'</a>') CetakDetail ,
			CONCAT('<a href=PrintViewKelompokKalender.php?Kelompok=',grp_Name,' target=_blank>',grp_Name,'</a>') Keluarga ,
			grp_Description as Keterangan
			from group_grp ORDER by grp_Name";
			$perintah = mysql_query($sSQL);
			$i = 0;
			while ($hasilStWarga=mysql_fetch_array($perintah)){
			$i++;
			//Alternate the row color
			$sRowClass = AlternateRowStyle($sRowClass);
			?>
			<tr class="<?php echo $sRowClass; ?>">
			<td>*</td>
			<td><font size=2><?=$hasilStWarga[Kelompok]?></td>
			<td><font size=2><?=$hasilStWarga[Foto]?></td>								
			<td><font size=2><?=$hasilStWarga[CetakDetail]?></td>
			<td><font size=2><?=$hasilStWarga[Keluarga]?></td>
			<td><font size=2><?=$hasilStWarga[Keterangan]?></td>			
			</td>
			</tr>
			<?}?>
		</table>



<?php
require 'Include/Footer.php';
?>
