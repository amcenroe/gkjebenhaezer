<?php
/*******************************************************************************
 *
 *  filename    : PrintViewLapAset.php
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

$Judul = "Laporan - Daftar Detail Aset"; 
require "Include/Header-Report.php";

?>



			<table border="1"  width="1200" cellspacing=0 cellpadding=0 >

			<tr><b>
				<td ALIGN=center><b><?php echo gettext(" No "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Nomor Reg "); ?></b></td> 
				<td ALIGN=center><b>
				<a href="PrintViewLapAset.php?Sort=ClassID">
				<?php echo gettext(" Klasifikasi "); ?></a></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Sub Klas "); ?></b></td> 
				<td ALIGN=center><b>
				<a href="PrintViewLapAset.php?Sort=Tahun">
				<?php echo gettext(" Tahun "); ?></b></a></td> 
				<td ALIGN=center><b><?php echo gettext(" Merk "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Type "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Spesifikasi "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Jumlah "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Unit "); ?></b></td> 
				<td ALIGN=center><b>
				<a href="PrintViewLapAset.php?Sort=Value">
				<?php echo gettext(" Nilai"); ?></a></b></td> 
				<td ALIGN=center><b>
				<a href="PrintViewLapAset.php?Sort=Status">
				<?php echo gettext(" Status "); ?></a></b></td> 
				<td ALIGN=center><b>
				<a href="PrintViewLapAset.php?Sort=Location">
				<?php echo gettext(" Lokasi TI "); ?></a></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Tempat "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Rak "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Bin "); ?></b></td> 
				<td ALIGN=center><b><?php echo gettext(" Keterangan "); ?></b></td> 		
			</b>
			
			</tr>
			<?php
//				echo $iSort;

				if($iSort == "")
				{
               $sSQL = "SELECT * FROM asetgkjbekti a
						LEFT JOIN LokasiTI b ON a.Location=b.KodeTI 
						LEFT JOIN asetklasifikasi c ON a.AssetClass=c.ClassID 
						LEFT JOIN asetruangan d ON a.StorageCode=d.StorageCode
						LEFT JOIN asetstatus e ON a.Status=e.StatusCode
						ORDER BY AssetClass , majorclass, minorclass
						";
				}else
				{
				       $sSQL = "SELECT * FROM asetgkjbekti a
							LEFT JOIN LokasiTI b ON a.Location=b.KodeTI 
							LEFT JOIN asetklasifikasi c ON a.AssetClass=c.ClassID 
							LEFT JOIN asetruangan d ON a.StorageCode=d.StorageCode
							LEFT JOIN asetstatus e ON a.Status=e.StatusCode 
							ORDER BY " . $iSort ;
			
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
							
				<tr class="<?php echo $sRowClass; ?>"><font size="1" style="font-family: Arial; ">
				<td ALIGN=center><font size="1" style="font-family: Arial; "><? echo $i ?></font></td>
				
				<?php
						list($year , $month, $day ) = preg_split('[/.-]', $Tahun);
				?>
					    <td ALIGN=center><font size="1" style="font-family: Arial; "><?php echo gettext("$AssetClass/$AssetID/$year/$Location"); ?></font></td> 
				
				
				
						<td><font size="1" style="font-family: Arial; "><?=$hasilGD[majorclass] ?></font></td>
						<td><font size="1" style="font-family: Arial; "><?=$hasilGD[minorclass] ?></font></td> 
						<td ALIGN=center><font size="1" style="font-family: Arial; "><?=$year ?>&nbsp;</font></td> 
						<td><font size="1" style="font-family: Arial; "><?=$hasilGD[Merk] ?>&nbsp;</font></td> 
						<td><font size="1" style="font-family: Arial; "><?=$hasilGD[Type] ?>&nbsp;</font></td> 
						<td><font size="1" style="font-family: Arial; "><?=$hasilGD[Spesification] ?>&nbsp;</font></td> 
						<td ALIGN=center><font size="1" style="font-family: Arial; "><?=$hasilGD[Quantity] ?>&nbsp;</font></td> 
						<td ALIGN=center><font size="1" style="font-family: Arial; "><?=$hasilGD[UnitOfMasure] ?>&nbsp;</font></td> 
						<td ALIGN=right><font size="1" style="font-family: Arial; "><? echo currency('Rp. ', $hasilGD[Value],'.',',00'); ?>&nbsp;</font></td> 
						<td><font size="1" style="font-family: Arial; "><?=$hasilGD[StatusName] ?>&nbsp;</font></td> 
						<td><font size="1" style="font-family: Arial; "><?=$hasilGD[NamaTI] ?>&nbsp;</font></td> 
						<td><font size="1" style="font-family: Arial; "><?=$hasilGD[StorageDesc] ?>&nbsp;</font></td> 
						<td ALIGN=center><font size="1" style="font-family: Arial; "><?=$hasilGD[Rack] ?>&nbsp;</font></td> 
						<td ALIGN=center><font size="1" style="font-family: Arial; "><?=$hasilGD[Bin] ?>&nbsp;</font></td> 
						<td><font size="1" style="font-family: Arial; "><?=$hasilGD[Description] ?>&nbsp;</font></td> 
				
				<td><b><center><?
				$fileFOT = "Images/Aset/Aset" . $hasilGD[AssetID] .".jpg" ;
								if(file_exists($fileFOT))
								{
								echo "<img border=\"0\" src=\"Images/Aset/thumbnails/Aset$hasilGD[AssetID].jpg\" width=\"20\" >" ;
								}else{ echo "<img border=\"0\" src=\"Images/NoData.gif\" width=\"25\" >" ;
								}
				?></b></center></td>

				</font>
				</tr>
				<?}?>
			</table>
	
</div>

<?php
require "Include/Footer-Short.php";
?>
