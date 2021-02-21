<?php
/*******************************************************************************
 *
 *  filename    : PrintViewMajelis.php
 *  last change : 2011-01-29
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2003 Phillip Hullquist, Deane Barker, Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2011 Erwin Pratama for GKJ Bekasi Timur (www.gljbekasitimur.org)
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

$Judul = "Laporan - Daftar Majelis jemaat"; 
require "Include/Header-Report.php";

?>


<style type="text/css" media="screen"><!--
.gallerycontainer{
position: relative;
/*Add a height attribute and set to largest image's height to prevent overlaying*/
}

.thumbnail img{
border: 1px solid white;
margin: 0 5px 5px 0;
}

.thumbnail:hover{
background-color: transparent;
}

.thumbnail:hover img{
border: 1px solid blue;
}

.thumbnail span{ /*CSS for enlarged image*/
position: absolute;
background-color: lightyellow;
padding: 5px;
left: -1000px;
border: 1px dashed gray;
visibility: hidden;
color: black;
text-decoration: none;
}

.thumbnail span img{ /*CSS for enlarged image*/
border-width: 0;
padding: 2px;
}

.thumbnail:hover span{ /*CSS for enlarged image*/
visibility: visible;
top: 0;
left: 50px; /*position where enlarged image should offset horizontally */
z-index: 50;
}


--></style>
			<table border="0"  width="900" cellspacing="1" cellpadding="1" >

			<tr>
			<td ALIGN=center><b>No</b></td>
			<td ALIGN=center><b>Foto</b></td>
			<td ALIGN=center><b>Nama Lengkap </b></td>
			<td ALIGN=center><b>TelpRumah / Handphone </b></td>
			<td ALIGN=center><b>Alamat Rumah	</b></td>
			<td ALIGN=center><b>Email	</b></td>
			<td ALIGN=center><b>Jabatan</b></td>
			<td ALIGN=center><b>Kelompok</b></td>
			</tr>
			<?php
				
			$sSQL = "select a.per_ID as PersonID, CONCAT('<a href=PersonView.php?PersonID=',a.per_ID,'>',a.per_FirstName,'</a>') AS Nama,
			fam_homephone as TelpRumah, fam_Address1 as AlamatRumah, fam_City as Kota, 
			c13 as TelpKantor, per_cellphone as Handphone, per_email as Email, vol_name as Jabatan, TRIM(per_workphone) as Kelompok
			from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
			where a.per_id = b.per_id AND
			a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id < 4
			ORDER by vol_id, TRIM(per_workphone), per_firstname";
			
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
<?php 

$iPersonID=$hasilGD[PersonID]; 

echo "	<td>" ;
//echo "	<td><div class='gallerycontainer'>" ;
//echo "	<td><font size=2><div class='gallerycontainer'>" ;
 
	 		// Display photo or upload from file
	 		$photoFile = "Images/Person/thumbnails/" . $iPersonID . ".jpg";
	         if (file_exists($photoFile))
	         {
			// 		 echo '<a class="thumbnail" href="http://www.gkjbekasitimur.org/datawarga/PrintViewCari.php?PersonID='. $row[per_ID] .'" >';
			   echo '<a >';
	       //   echo '<img border="1" src="'.$photoFile.'" width="20" ><span><img border="1" src="'.$photoFile.'" width="200" > ' . $row[per_FirstName] . ' </span></a>';	
	          echo '<img border="1" src="'.$photoFile.'" width="20" >' . $row[per_FirstName] . ' </a>';				  

	         } else {
	             // Some old / M$ browsers can't handle PNG's correctly.
	             if ($bDefectiveBrowser)
	                echo '<img border="0" src="Images/NoPhoto.gif" width="20" >';
	             else
			       echo '<img border="0" src="Images/NoPhoto.png" width="20" >';

	         }
//echo "</div></td>";
echo "<br></td>";
?> 
				<td><?=$hasilGD[Nama]?></td>
				<td> <?=$hasilGD[TelpRumah]?> <br> <?=$hasilGD[Handphone]?> </td>
				<td> <?=$hasilGD[AlamatRumah]?> </td>
				<td> <?=$hasilGD[Email]?> </td>
				<td><?=$hasilGD[Jabatan]?></td>
				<td><?=$hasilGD[Kelompok]?></td>

				<td></td>
				</tr>
				<?}?>
			</table>


</div>
