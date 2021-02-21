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
// require "Include/Header-Print.php";
// Get the Gol Darah ID from the querystring
$iBulan = FilterInput($_GET["Bulan"]);
$bln = $iBulan;

 if (( $bln == 1 )) {
	     $BULAN="Januari";
	    } elseif (( $bln == 2 )) {
	      $BULAN="Februari";
	    } elseif (( $bln == 3 )) {
	      $BULAN="Maret";
	    } elseif (( $bln == 4 )) {
	      $BULAN="April";
	    } elseif (( $bln == 5 )) {
	      $BULAN="Mei";
	    } elseif (( $bln == 6 )) {
	      $BULAN="Juni";
	    } elseif (( $bln == 7 )) {
	      $BULAN="Juli";
	    } elseif (( $bln == 8 )) {
	      $BULAN="Agustus";
	 	} elseif (( $bln == 9 )) {
	      $BULAN="September";
	    } elseif (( $bln == 10 )) {
	      $BULAN="Oktober";
	    } elseif (( $bln == 11 )) {
	      $BULAN="Nopember";
	    } elseif (( $bln == 12 )) {
	      $BULAN="Desember";
	    }

$Judul = "Laporan - Status Bulan Kelahiran Jemaat - Bulan: $BULAN "; 
require "Include/Header-Report.php";

?>


			<table border="0"  width="1100" cellspacing=0 cellpadding=0 >
			<u><b> </b></u><br>
			<tr><b><font size="2">
			<td ALIGN=center><b><font size="2">No.</font></b></td>
			<td ALIGN=center><b><font size="2">IDKlg</font></b></td>
			<td ALIGN=center><b><font size="2">Hub</font></b></td>
			<td ALIGN=center><b><font size="2">Nama Lengkap</font></b></td>
			<td ALIGN=center><b><font size="2">Gen.</font></b></td>
			<td ALIGN=center><b><font size="2">TanggalLahir</font></b></td>
			<td ALIGN=center><b><font size="2">Alamat Lengkap</font></b></td>
			<td ALIGN=center><b><font size="2">Telepon Rumah</font></b></td>
			<td ALIGN=center><b><font size="2"> Status </font></b></td>
			<td ALIGN=center><b><font size="2"> Kelp </font></b></td>
			</font></b></tr>
			<?php
			 	$sRowClass = "RowColorA";
				$sSQL = "SELECT IF(per_gender ='1', 'L', 'P') as Gender,
							CASE per_fmr_id
								WHEN '1' THEN 'KK'
								WHEN '2' THEN 'Ist'
								WHEN '3' THEN 'Ank'
								WHEN '4' THEN 'Sdr'
								END AS HubKlg,
				per_fam_id as IDKelg , per_FirstName as Nama, fam_HomePhone as TelpRumah, per_Cellphone as Handphone, a1.lst_OptionName as Hub,
				fam_Name as NmKelg, fam_Address1 as Alamat, a2.lst_OptionName as Kewargaan,
							per_birthDay as TGL, per_birthMonth as BLN, per_birthYear as THN, per_WorkPhone as Kelp
						FROM person_per
						LEFT JOIN family_fam ON person_per.per_fam_ID = family_fam.fam_ID
						LEFT JOIN list_lst as a1 ON a1.lst_OptionID = person_per.per_fmr_ID AND a1.lst_ID = 2
						LEFT JOIN list_lst as a2 ON a2.lst_OptionID = person_per.per_cls_ID AND a2.lst_ID = 1
						WHERE per_BirthMonth = '$iBulan' AND per_cls_ID < 3
						ORDER BY person_per.per_BirthDay, person_per.per_BirthYear ";


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
				<td><?=$hasilGD[IDKelg]?></td>
				<td><?=$hasilGD[HubKlg]?>.</td>
				<td><?=$hasilGD[Nama]?></td>
				<td ALIGN=center><?=$hasilGD[Gender]?></td>
				<td ALIGN=center><?=$hasilGD[TGL]?>/<?=$hasilGD[BLN]?>/<?=$hasilGD[THN]?></td>
				<td><?=$hasilGD[Alamat]?></td>
				<td><?=$hasilGD[TelpRumah]?></td>
				<td><?=$hasilGD[Kewargaan]?>.</td>
				<td><?=$hasilGD[Kelp]?></td>
				</tr>
				<?}?>
			</table>
			<hr>
			Daftar ini hanya untuk warga dengan status "WARGA" dan "TITIPAN"



<?php
require "Include/Footer-Short.php";
?>
