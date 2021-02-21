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
//$today = date("D j M Y");
$today = date("j M Y");

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>Laporan - Progress Process Updating Database</title>

<STYLE TYPE="text/css">
  		<!--
  		TD{font-family: Arial; font-size: 10pt;}
		--->
        P.breakhere {page-break-before: always}
</STYLE>
</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >

<DIV align=center >


<?php
// Read values from config table into local variables
// **************************************************
$sSQL = "SELECT cfg_name, IFNULL(cfg_value, cfg_default) AS value "
      . "FROM config_cfg WHERE cfg_section='ChurchInfoReport'";
$rsConfig = mysql_query($sSQL);			// Can't use RunQuery -- not defined yet
if ($rsConfig) {
    while (list($cfg_name, $value) = mysql_fetch_row($rsConfig)) {
        $$cfg_name = $value;
    }
}
?>


<table
 style="width: 750; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="width: 125px;"><img style="width: 90px; height: 90px;" src="gkj_logo.jpg" border="0"></td>
      <td style="width: 630px;"><b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo $sChurchName;?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);"><b style="font-family: Arial; color: rgb(0, 0, 102);">
	<hr style="width: 100%; height: 2px;">
	<font size="2"><big style="font-family: Arial;">Progress Process Updating Database</big><br></font></b>   
	<b style="font-family: Arial;">Bulan : <?php echo date("F, Y"); ?></b></td>
    </tr>
  </tbody>
</table>
<br>


<table border="0"  width="700" cellspacing=0 cellpadding=0 >
<tr><td>

			<table border="0"  width="300" cellspacing=0 cellpadding=0 >

			<tr><b><u>Data Awal Jemaat (Th 2006)<br>- Diinput secara Batch tanggal 4 Juni 2009</u><b>
						<td ALIGN=center><b>No</b></td>
						<td ALIGN=center><b>Kelompok</b></td>
						<td ALIGN=center><b>Jumlah Data</b></td>
			</tr>
			<?php
				$sSQL = "SELECT per_workphone as Kelompok, count(per_ID) as Jumlah
							FROM person_per
							WHERE date(per_DateEntered) = '2009-06-04'
							AND per_workphone <> ' '

							group by per_workphone";

				$perintah = mysql_query($sSQL);
				$num_rows = mysql_num_rows($perintah);


								$i = 0;
								$total = 0;
									while ($hasilGD=mysql_fetch_array($perintah))
										{
										$i++;
										$total = ($total + $hasilGD[Jumlah]);
										extract($hasilGD);
										//Alternate the row color
									   $sRowClass = AlternateRowStyle($sRowClass);

				?>

				<tr class="<?php echo $sRowClass; ?>">
				<td ALIGN=center><? echo $i ?></td>
				<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
				<td ALIGN=center><?=$hasilGD[Jumlah]?></td>
				</tr>

				<?}?>
							<tr>
							<td></td>
							<td ALIGN=center><font size=2><b>Total</b></td>
							<td ALIGN=center><font size=2><b><? echo $total  ?></b></td>
							</tr>


			</table>
			<br>
			<table border="0"  width="300" cellspacing=0 cellpadding=0 >

						<tr><b><u>Data Jemaat Yang Di Edit <br>- Setelah Tanggal 4 Juni 2009</u><b>
									<td ALIGN=center><b>No</b></td>
									<td ALIGN=center><b>Kelompok</b></td>
									<td ALIGN=center><b>Jumlah Data</b></td>
						</tr>
						<?php
							$sSQL = "SELECT per_workphone as Kelompok, count(per_ID) as Jumlah
										FROM person_per
										WHERE date(per_DateLastEdited) > '2009-06-04'
										AND per_workphone <> ' '

										group by per_workphone";

							$perintah = mysql_query($sSQL);
											$i = 0;
											$total = 0;
												while ($hasilGD=mysql_fetch_array($perintah))
													{
													$i++;
													$total = ($total + $hasilGD[Jumlah]);
													extract($hasilGD);
													//Alternate the row color
												   $sRowClass = AlternateRowStyle($sRowClass);

							?>

							<tr class="<?php echo $sRowClass; ?>">
							<td ALIGN=center><? echo $i ?></td>
							<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
							<td ALIGN=center><?=$hasilGD[Jumlah]?></td>
							</tr>

							<?}?>
							<tr>
							<td></td>
							<td ALIGN=center><font size=2><b>Total</b></td>
							<td ALIGN=center><font size=2><b><? echo $total  ?></b></td>
							</tr>
			</table>
			<br>

			<table border="0"  width="300" cellspacing=0 cellpadding=0 >
						<tr><b><u>Data Jemaat Yang BELUM Di Edit/Proses <br>- Setelah Tanggal 4 Juni 2009</u><b>
									<td ALIGN=center><b>No</b></td>
									<td ALIGN=center><b>Kelompok</b></td>
									<td ALIGN=center><b>Jumlah Data</b></td>
						</tr>
						<?php
							$sSQL = "SELECT per_workphone as Kelompok, count(per_ID) as Jumlah
										FROM person_per
										WHERE ( per_dateLastEdited is NULL OR per_dateLastEdited = 0000-00-00 )
										AND per_workphone <> ' '
										group by per_workphone";

							$perintah = mysql_query($sSQL);
											$i = 0;
											$total = 0;
												while ($hasilGD=mysql_fetch_array($perintah))
													{
													$i++;
													$total = ($total + $hasilGD[Jumlah]);
													extract($hasilGD);
													//Alternate the row color
												   $sRowClass = AlternateRowStyle($sRowClass);

							?>

							<tr class="<?php echo $sRowClass; ?>">
							<td ALIGN=center><? echo $i ?></td>
							<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
							<td ALIGN=center><?=$hasilGD[Jumlah]?></td>
							</tr>

							<?}?>
							<tr>
							<td></td>
							<td ALIGN=center><font size=2><b>Total</b></td>
							<td ALIGN=center><font size=2><b><? echo $total  ?></b></td>
							</tr>
							</table>

			<br>

						<table border="0"  width="300" cellspacing=0 cellpadding=0 >

									<tr><b><u>Data Jemaat Yang Di Tambah <br>- Setelah Tanggal 4 Juni 2009</u><b>
												<td ALIGN=center><b>No</b></td>
												<td ALIGN=center><b>Kelompok</b></td>
												<td ALIGN=center><b>Jumlah Data</b></td>
									</tr>
									<?php
										$sSQL = "SELECT per_workphone as Kelompok, count(per_ID) as Jumlah
													FROM person_per
													WHERE date(per_DateEntered) > '2009-06-04'
													AND per_workphone <> ' '
													group by per_workphone";

										$perintah = mysql_query($sSQL);
														$i = 0;
														$total = 0 ;
															while ($hasilGD=mysql_fetch_array($perintah))
																{
																$i++;
																$total = ($total + $hasilGD[Jumlah]);
																extract($hasilGD);
																//Alternate the row color
															   $sRowClass = AlternateRowStyle($sRowClass);

										?>

										<tr class="<?php echo $sRowClass; ?>">
										<td ALIGN=center><? echo $i ?></td>
										<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
										<td ALIGN=center><?=$hasilGD[Jumlah]?></td>
										</tr>


							<?}?>
							<tr>
							<td></td>
							<td ALIGN=center><font size=2><b>Total</b></td>
							<td ALIGN=center><font size=2><b><? echo $total  ?></b></td>
							</tr>

			</table>

			<br>
			<table border="0"  width="300" cellspacing=0 cellpadding=0 >

									<tr><b><u>Total Data Jemaat Yang Ada <br>di Database sampai dengan <? echo $today ?></u><b>
												<td ALIGN=center><b>No</b></td>
												<td ALIGN=center><b>Kelompok</b></td>
												<td ALIGN=center><b>Jumlah Data</b></td>
									</tr>
									<?php
										$sSQL = "SELECT per_workphone as Kelompok, count(per_ID) as Jumlah
													FROM person_per
													WHERE per_workphone <> ' '
													group by per_workphone";

										$perintah = mysql_query($sSQL);
														$i = 0;
														$total = 0;
															while ($hasilGD=mysql_fetch_array($perintah))
																{
																$i++;
																$total = ($total + $hasilGD[Jumlah]);
																extract($hasilGD);
																//Alternate the row color
															   $sRowClass = AlternateRowStyle($sRowClass);

										?>

										<tr class="<?php echo $sRowClass; ?>">
										<td ALIGN=center><? echo $i ?></td>
										<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
										<td ALIGN=center><?=$hasilGD[Jumlah]?></td>
										</tr>

										<?}?>
							<tr>
							<td></td>
							<td ALIGN=center><font size=2><b>Total</b></td>
							<td ALIGN=center><font size=2><b><? echo $total  ?></b></td>
							</tr>


			</table>


</td>

<td>

			<table border="0"  width="300" cellspacing=0 cellpadding=0 >

			<tr><b><u>Data Awal Keluarga <br>- Diinput secara Batch tanggal 4 Juni 2009</u><b>
						<td ALIGN=center><b>No</b></td>
						<td ALIGN=center><b>Kelompok</b></td>
						<td ALIGN=center><b>Jumlah Data</b></td>
			</tr>
			<?php
				$sSQL = "SELECT fam_workphone as Kelompok, count(fam_ID) as Jumlah
							FROM family_fam
							WHERE date(fam_DateEntered) = '2009-06-04'
							AND fam_workphone <> ' '

							group by fam_workphone";

				$perintah = mysql_query($sSQL);
				$num_rows = mysql_num_rows($perintah);


								$i = 0;
								$total = 0;
									while ($hasilGD=mysql_fetch_array($perintah))
										{
										$i++;
										$total = ($total + $hasilGD[Jumlah]);
										extract($hasilGD);
										//Alternate the row color
									   $sRowClass = AlternateRowStyle($sRowClass);

				?>

				<tr class="<?php echo $sRowClass; ?>">
				<td ALIGN=center><? echo $i ?></td>
				<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
				<td ALIGN=center><?=$hasilGD[Jumlah]?></td>
				</tr>

				<?}?>
							<tr>
							<td></td>
							<td ALIGN=center><font size=2><b>Total</b></td>
							<td ALIGN=center><font size=2><b><? echo $total  ?></b></td>
							</tr>

			</table>
			<br>
			<table border="0"  width="300" cellspacing=0 cellpadding=0 >

						<tr><b><u>Data Keluarga Yang Di Edit <br>- Setelah Tanggal 4 Juni 2009</u><b>
									<td ALIGN=center><b>No</b></td>
									<td ALIGN=center><b>Kelompok</b></td>
									<td ALIGN=center><b>Jumlah Data</b></td>
						</tr>
						<?php
							$sSQL = "SELECT fam_workphone as Kelompok, count(fam_ID) as Jumlah
										FROM family_fam
										WHERE date(fam_DateLastEdited) > '2009-06-04'
										AND fam_workphone <> ' '

										group by fam_workphone";

							$perintah = mysql_query($sSQL);
											$i = 0;
											$total = 0;
												while ($hasilGD=mysql_fetch_array($perintah))
													{
													$i++;
													$total = ($total + $hasilGD[Jumlah]);
													extract($hasilGD);
													//Alternate the row color
												   $sRowClass = AlternateRowStyle($sRowClass);

							?>

							<tr class="<?php echo $sRowClass; ?>">
							<td ALIGN=center><? echo $i ?></td>
							<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
							<td ALIGN=center><?=$hasilGD[Jumlah]?></td>
							</tr>

							<?}?>
							<tr>
							<td></td>
							<td ALIGN=center><font size=2><b>Total</b></td>
							<td ALIGN=center><font size=2><b><? echo $total  ?></b></td>
							</tr>

			</table>
			<br>

			<table border="0"  width="300" cellspacing=0 cellpadding=0 >

						<tr><b><u>Data Keluarga Yang BELUM DiEdit/Process <br>- Setelah Tanggal 4 Juni 2009</u><b>
									<td ALIGN=center><b>No</b></td>
									<td ALIGN=center><b>Kelompok</b></td>
									<td ALIGN=center><b>Jumlah Data</b></td>
						</tr>
						<?php
							$sSQL = "SELECT fam_workphone as Kelompok, count(fam_ID) as Jumlah
										FROM family_fam
										WHERE ( fam_dateLastEdited is NULL OR fam_dateLastEdited = 0000-00-00 )

										AND fam_workphone <> ' '

										group by fam_workphone";

							$perintah = mysql_query($sSQL);
											$i = 0;
											$total = 0;
												while ($hasilGD=mysql_fetch_array($perintah))
													{
													$i++;
													$total = ($total + $hasilGD[Jumlah]);
													extract($hasilGD);
													//Alternate the row color
												   $sRowClass = AlternateRowStyle($sRowClass);

							?>

							<tr class="<?php echo $sRowClass; ?>">
							<td ALIGN=center><? echo $i ?></td>
							<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
							<td ALIGN=center><?=$hasilGD[Jumlah]?></td>
							</tr>

							<?}?>
							<tr>
							<td></td>
							<td ALIGN=center><font size=2><b>Total</b></td>
							<td ALIGN=center><font size=2><b><? echo $total  ?></b></td>
							</tr>

			</table>
			<br>


						<table border="0"  width="300" cellspacing=0 cellpadding=0 >

									<tr><b><u>Data Keluarga Yang Di Tambah <br>- Setelah Tanggal 4 Juni 2009</u><b>
												<td ALIGN=center><b>No</b></td>
												<td ALIGN=center><b>Kelompok</b></td>
												<td ALIGN=center><b>Jumlah Data</b></td>
									</tr>
									<?php
										$sSQL = "SELECT fam_workphone as Kelompok, count(fam_ID) as Jumlah
													FROM family_fam
													WHERE date(fam_DateEntered) > '2009-06-04'
													AND fam_workphone <> ' '
													group by fam_workphone";

										$perintah = mysql_query($sSQL);
														$i = 0;
														$total = 0;
															while ($hasilGD=mysql_fetch_array($perintah))
																{
																$i++;
																$total = ($total + $hasilGD[Jumlah]);
																extract($hasilGD);
																//Alternate the row color
															   $sRowClass = AlternateRowStyle($sRowClass);

										?>

										<tr class="<?php echo $sRowClass; ?>">
										<td ALIGN=center><? echo $i ?></td>
										<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
										<td ALIGN=center><?=$hasilGD[Jumlah]?></td>
										</tr>

										<?}?>
							<tr>
							<td></td>
							<td ALIGN=center><font size=2><b>Total</b></td>
							<td ALIGN=center><font size=2><b><? echo $total  ?></b></td>
							</tr>

			</table>

			<br>
						<table border="0"  width="300" cellspacing=0 cellpadding=0 >

									<tr><b><u>Total Data Keluarga Yang Ada <br>di Database sampai dengan <? echo $today ?></u><b>
												<td ALIGN=center><b>No</b></td>
												<td ALIGN=center><b>Kelompok</b></td>
												<td ALIGN=center><b>Jumlah Data</b></td>
									</tr>
									<?php
										$sSQL = "SELECT fam_workphone as Kelompok, count(fam_ID) as Jumlah
													FROM family_fam
													WHERE fam_workphone <> ' '
													group by fam_workphone";

										$perintah = mysql_query($sSQL);
														$i = 0;
														$total = 0;
															while ($hasilGD=mysql_fetch_array($perintah))
																{
																$i++;
																$total = ($total + $hasilGD[Jumlah]);
																extract($hasilGD);
																//Alternate the row color
															   $sRowClass = AlternateRowStyle($sRowClass);

										?>

										<tr class="<?php echo $sRowClass; ?>">
										<td ALIGN=center><? echo $i ?></td>
										<td ALIGN=center><?=$hasilGD[Kelompok]?></td>
										<td ALIGN=center><?=$hasilGD[Jumlah]?></td>
										</tr>

										<?}?>
							<tr>
							<td></td>
							<td ALIGN=center><font size=2><b>Total</b></td>
							<td ALIGN=center><font size=2><b><? echo $total  ?></b></td>
							</tr>
			</table>

</td>
</tr>
</table>

<table border="0"  width="700" cellspacing=0 cellpadding=0 >
<tr><td>
			<br>
			<table border="0"  width="300" cellspacing=0 cellpadding=0 >

				<tr><b><u>Progress Edit/Input data Jemaat <br>di Database sampai dengan <? echo $today ?></u><b>
							<td ALIGN=center><b>No</b></td>
							<td ALIGN=center><b>Tanggal Proses</b></td>
							<td ALIGN=center><b>Hari</b></td>
							<td ALIGN=center><b>Jumlah Data</b></td>
				</tr>
				<?php
					$sSQL = "SELECT  DAYNAME(per_DateLastEdited) as Hari,
								DATE(per_DateLastEdited) as TanggalProses ,count(DATE(per_DateLastEdited)) as DataYangDiproses
								FROM person_per
								WHERE per_DateLastEdited <> '0000-00-00'
								GROUP BY TanggalProses ORDER BY TanggalProses";
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
							<td ALIGN=center><?=$hasilGD[TanggalProses]?></td>
							<td ALIGN=left><?=$hasilGD[Hari]?></td>
							<td ALIGN=center><?=$hasilGD[DataYangDiproses]?></td>
							</tr>

										<?}?>
				<?php
					$sSQL = "SELECT  count(per_DateLastEdited) as Total
								FROM person_per
								WHERE per_DateLastEdited <> '0000-00-00'";
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
							<td ALIGN=center></td>
							<td ALIGN=center><b>Total</b></td>
							<td ALIGN=left></td>
							<td ALIGN=center><b><?=$hasilGD[Total]?></b></td>
							</tr>

										<?}?>
			</table>

</td>
<td>
			<br>
			<table border="0"  width="300" cellspacing=0 cellpadding=0 >

				<tr><b><u>Progress Edit/Input data Keluarga <br>di Database sampai dengan <? echo $today ?></u><b>
							<td ALIGN=center><b>No</b></td>
							<td ALIGN=center><b>Tanggal Proses</b></td>
							<td ALIGN=center><b>Hari</b></td>
							<td ALIGN=center><b>Jumlah Data</b></td>
				</tr>
				<?php
					$sSQL = "SELECT  DAYNAME(fam_DateLastEdited) as Hari,
								DATE(fam_DateLastEdited) as TanggalProses ,count(DATE(fam_DateLastEdited)) as DataYangDiproses
								FROM family_fam
								WHERE date(fam_DateLastEdited) > '2009-06-04' AND
								fam_DateLastEdited <> '0000-00-00'
								GROUP BY TanggalProses ORDER BY TanggalProses";
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
							<td ALIGN=center><?=$hasilGD[TanggalProses]?></td>
							<td ALIGN=left><?=$hasilGD[Hari]?></td>
							<td ALIGN=center><?=$hasilGD[DataYangDiproses]?></td>
							</tr>
									<?}?>
				<?php
					$sSQL = "SELECT  count(fam_DateLastEdited) as Total
								FROM family_fam
								WHERE date(fam_DateLastEdited) > '2009-06-04' AND
								fam_DateLastEdited <> '0000-00-00'";
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
							<td ALIGN=center></td>
							<td ALIGN=center><b>Total</b></td>
							<td ALIGN=left></td>
							<td ALIGN=center><b><?=$hasilGD[Total]?></b></td>
							</tr>
									<?}?>

			</table>

</td>
</tr>
</table>

<?php
require "Include/Footer-Short.php";
?>
