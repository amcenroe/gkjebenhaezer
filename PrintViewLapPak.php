<?php
/*******************************************************************************
 *
 *  filename    : PrintViewLapPak.php
 *  last change : 2003-01-29
 *
 *  2010 Erwin Pratama for GKJ Bekasi Timur
 *  Sistem Informasi GKJ Bekasi is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

// Get the Kelompok ID from the querystring
$iSort = FilterInput($_GET["Sort"]);

$Judul = "Laporan - Daftar Detail Pendidikan Agama Kristen"; 
require "Include/Header-Report.php";

?>



			<table border="1"  width="1000" cellspacing=0 cellpadding=0 >

			<tr><b>
				<td ALIGN=center><b><?php echo gettext(" No "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Tahun Ajaran "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Kelas "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Semester "); ?></b></td>
				<td ALIGN=center><b><?php echo gettext(" Nama Peserta PAK "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Nama Sekolah "); ?></b></td> 	
				<td ALIGN=center><b><?php echo gettext(" Nilai A "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Nilai B "); ?></b></td>
				<td ALIGN=center><b><?php echo gettext(" Nilai C "); ?></b></td>	
				<td ALIGN=center><b><?php echo gettext(" Nilai Rata2 "); ?></b></td>				

			</b>
			
			</tr>
			<?php
//				echo $iSort;

				if($iSort == "")
				{
				       $sSQL = "SELECT a.*, b.* FROM pakgkjbekti a
						LEFT JOIN person_per b ON a.per_ID = b.per_ID
						ORDER BY a.TahunAjaran DESC, a.Kelas , a.Semester , b.per_FirstName  " ;

				}else
				{
				       $sSQL = "SELECT a.*, b.* FROM pakgkjbekti a
						LEFT JOIN person_per b ON a.per_ID = b.per_ID
						ORDER BY a.TahunAjaran DESC, a.Kelas , a.Semester , b.per_FirstName  " ;
				}


				$perintah = mysql_query($sSQL);
				$i = 0;
								while ($hasilGD=mysql_fetch_array($perintah))
								{
								$i++;
													extract($hasilGD);
													//Alternate the row color
								                    $sRowClass = AlternateRowStyle($sRowClass);


				?>
							
				<tr class="<?php echo $sRowClass; ?>"><font size="2" style="font-family: Arial; ">
				<td ALIGN=center><font size="2" style="font-family: Arial; ">
				<a href="PakView.php?PakID=<?=$hasilGD[PakID] ?>">
				<? echo $i ?></a></font></td>
				<td ALIGN=center><font size="2" style="font-family: Arial; "><?=2000+$hasilGD[TahunAjaran] ?></font></td> 
				<td ALIGN=center><font size="2" style="font-family: Arial; "><?=$hasilGD[Kelas] ?></font></td> 
				<td ALIGN=center><font size="2" style="font-family: Arial; "><?=$hasilGD[Semester] ?></font></td> 
				<td><font size="2" style="font-family: Arial; ">
				<?php  if ($hasilGD[per_ID]>0 ) { echo $hasilGD[per_FirstName];}else{ echo $hasilGD[Nama];} ?></font></td> 
						<td><font size="2" style="font-family: Arial; "><?=$hasilGD[AlamatSekolah] ?></font></td> 
				<td ALIGN=center><font size="2" style="font-family: Arial; "><?=$hasilGD[Nilai1] ?></font></td> 
				<td ALIGN=center><font size="2" style="font-family: Arial; "><?=$hasilGD[Nilai2] ?></font></td> 
				<td ALIGN=center><font size="2" style="font-family: Arial; "><?=$hasilGD[Nilai3] ?></font></td> 
				<td ALIGN=center><font size="2" style="font-family: Arial; "><?=ROUND(($hasilGD[Nilai1]+$hasilGD[Nilai2]+$hasilGD[Nilai3])/3,2) ?></font></td> 				
				</tr>
				<?}?>
			</table>
	
</div>

<?php
require "Include/Footer-Short.php";
?>
