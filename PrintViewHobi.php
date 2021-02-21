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


?>

<table border="0"  width="750" cellspacing=0 cellpadding=0>

  <tr><!-- Row 2 -->
     <td valign=top align=left>
     <img border="0" src="gkj_logo.jpg" width="120" >
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b><font size="5">GEREJA KRISTEN JAWA BEKASI </font></b><BR>
	 <b><font size="5">Wilayah Timur </font></b><br>
	 <b><font size="2">Detail Status Hobi Jemaat</font></b><br>
	 <hr>
	 <b>
	 </b><br>


	 </td><!-- Col 2 -->

     <td valign=top align=right >

     </td><!-- Col 3 -->
  </tr>

</table>

			<table border="0"  width="700" cellspacing=0 cellpadding=0 >
			<u><b>Status Hobi</b></u><br>
			<tr>
			<td> </td><td>*</td><td>Status Hobi</td><td>Nama</td><td>Telepon Rumah</td><td>Tahun Kelahiran</td><td>Kelompok</td>
			</tr>
			<?php
				$sSQL = "select c.lst_OptionName as Hobi, a.per_FirstName as Nama ,
				         per_HomePhone as TelpRumah, per_BirthYear as TahunLahir ,
				         per_WorkPhone as Kelompok
				         from person_per a , person_custom b , list_lst c
							WHERE a.per_ID = b.per_ID AND b.c14 = c.lst_OptionID AND c.lst_ID = 22
							AND c.lst_OptionID = '$iStatus'
							ORDER BY a.per_WorkPhone";
				$perintah = mysql_query($sSQL);
				while ($hasilGD=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td> </td><td>*</td><td><?=$hasilGD[Hobi]?></td><td><?=$hasilGD[Nama]?></td><td><?=$hasilGD[TelpRumah]?></td><td><?=$hasilGD[TahunLahir]?></td><td><?=$hasilGD[Kelompok]?></td>
				</tr>
				<?}?>
			</table>



<?php
require "Include/Footer-Short.php";
?>
