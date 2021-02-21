<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPengurusGKJBekti.php
 *  last change : 2011-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2011 Erwin Pratama for GKJ Bekasi Timur (www.gljbekasitimur.org)
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";

// Get the Gol Darah ID from the querystring
$iStatus = FilterInput($_GET["Status"]);

$Judul = "Laporan - Struktur Majelis dan BPM ".$sChurchName; 
require "Include/Header-Report.php";

function crop($str, $len) {
    if ( strlen($str) <= $len ) {
        return $str;
    }

    // find the longest possible match
    $pos = 0;
    foreach ( array('. ', '? ', '! ') as $punct ) {
        $npos = strpos($str, $punct);
        if ( $npos > $pos && $npos < $len ) {
            $pos = $npos;
        }
    }

    if ( !$pos ) {
        // substr $len-3, because the ellipsis adds 3 chars
        return substr($str, 0, $len-3) . ''; 
    }
    else {
        // $pos+1 to grab punctuation mark
        return substr($str, 0, $pos+1);
    }
	}
function jabatan($posisi,$warna) {

			$sSQL = "select a.per_ID as PersonID, CONCAT('<a href=PrintViewCari.php?PersonID=',a.per_ID,'>',a.per_FirstName,'</a>') AS 'Nama',vol_id, 
			fam_homephone as TelpRumah, c13 as TelpKantor, per_cellphone as Handphone, per_email as Email, vol_name as Jabatan, per_workphone as Kelompok
			from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
			where a.per_id = b.per_id AND
			a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id
			AND vol_id = " .$posisi . "
			ORDER by per_workphone, vol_id, per_firstname";
			 
			
			$perintah = mysql_query($sSQL);
				$i = 0;
				$kelpk = " ";
					while ($hasilGD=mysql_fetch_array($perintah))
						{
						$i++;
						extract($hasilGD);
$iPersonID=$hasilGD[PersonID]; 

				echo "	<table align=\"center\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100px; height: 150px\">";
				echo "	<tbody>	<tr> <td valign=top style=\"text-align: center ; height: 20%; background-color: #". $warna . " ;\">";
				echo " <span style=\"font-size: 10px;\">";
				echo $hasilGD[Jabatan];
		//
				
				echo "	</span></td></tr>";
				echo "	<tr><td valign=top style=\"text-align: center; width: 100%; height: 60%;\">";
		//eNLARGE iMAGE
		//			echo "	<tr><td valign=top style=\"text-align: center; width: 100%; height: 60%;\"><div class='gallerycontainer'>";			
		// Display photo or upload from file
		$refresh = microtime() ;
	 		$photoFile = "Images/Person/thumbnails/" . $iPersonID . ".jpg";
	         if (file_exists($photoFile))
	         {
			 //enable enlarge image
			 //   echo '<a class="thumbnail" href="http://www.gkjbekasitimur.org/datawarga/PrintViewCari.php?PersonID='. $row[per_ID] .'" >';
			     echo '<a href="http://www.gkjbekasitimur.org/datawarga/PrintViewCari.php?PersonID='. $row[per_ID] .'" >';
				 
	        
				echo '<img border="1" src="'.$photoFile.'?'.$refresh.'" width="65" height="80">
			   ' . $row[per_FirstName] . ' </a>';		 
	        //emable enlarge image
			//echo '<img border="1" src="'.$photoFile.'" width="20" ><span>
			//   <img border="1" src="'.$photoFile.'" width="200" > 
			 //  ' . $row[per_FirstName] . ' </span></a>';			 
	             if ($bOkToEdit) {
	                 echo '
	                     <form method="post"
	                     action="PrintViewCari.php?PersonID=' . $iPersonID . '">
	                     <br>
	                     <input type="submit" class="icTinyButton"
	                     value="' . gettext("Delete Photo") . '" name="DeletePhoto">
	                     </form>';
	                 }
	         } else {
	             // Some old / M$ browsers can't handle PNG's correctly.
	             if ($bDefectiveBrowser)
	                echo '<img border="0" src="Images/NoPhoto.gif" width="50" >';
	             else
			       echo '<img border="0" src="Images/NoPhoto.png" width="50" >';

	             if ($bOkToEdit) {
	                 if (isset($PhotoError))
	                     echo '<span style="color: red;">' . $PhotoError . '</span><br>';

	                 echo '';
	             }
	         }
			 
			 
				
				echo "	</div></td></tr><tr><td valign=top style=\"text-align: center; height: 20%;background-color: #". $warna . " ;\">";
				echo " <span style=\"font-size: 10px;\">";
				
										$sSQL2 = "SELECT vol_id as jabid, vol_name as Jabatan
									from person_per , person2volunteeropp_p2vo, volunteeropportunity_vol
									where vol_id < 4 AND per_id = " . $hasilGD[PersonID] . " AND per_id = p2vo_per_id AND p2vo_vol_id = vol_id 
								";
						$perintah2 = mysql_query($sSQL2);
							while ($hasilGD2=mysql_fetch_array($perintah2))
							{
							//echo $sSQL;

							extract($hasilGD2);
							$jab=$hasilGD2[jabid] ;
							
							if ($jab==1)
								echo "Pdt.";
							elseif ($jab==2)
								echo "Pnt.";
							elseif ($jab==3)
								echo "Dkn.";								
							else
							echo "";		   
							}
				
				
				echo $hasilGD[Nama];
				echo "	</span></td></tr>";
				echo "	</td></tr></tbody></table>";

				}
				if(0 == $i)
				{
		//		echo $posisi;
				$sSQL = "select * from volunteeropportunity_vol
							where  vol_id = " .$posisi ;
			
				$perintah = mysql_query($sSQL);


					while ($hasilGD=mysql_fetch_array($perintah))
						{

						extract($hasilGD);
				
				echo "	<table align=\"center\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100px; height: 150px\">";
				echo "	<tbody>	<tr> <td valign=top style=\"text-align: center ; height: 20%; background-color: #". $warna . " ;\">";
				echo " <span style=\"font-size: 10px;\">";
				echo $hasilGD[vol_Name];
		//		echo - $hasilGD[vol_id];
		//		echo $posisi ;
		//		echo $sSQL ;
				echo "	</span></td></tr>";
				echo "	<tr><td valign=top style=\"text-align: center; width: 100%; height: 60%;\"><div class='gallerycontainer'>";
				echo "	</div></td></tr><tr><td valign=top style=\"text-align: center; height: 20%;background-color: #". $warna . " ;\">";
				echo " <span style=\"font-size: 10px;\">";
		//		echo $hasilGD[Nama];
				echo "	</span></td></tr>";
				echo "	</td></tr></tbody></table>";
				}
				}


}

function pengurusklpk($posisi,$warna,$klpk) {

			$sSQL = "select a.per_ID as PersonID, CONCAT('<a href=PrintViewCari.php?PersonID=',a.per_ID,'>',a.per_FirstName,'</a>') AS 'Nama',vol_id, 
			fam_homephone as TelpRumah, c13 as TelpKantor, per_cellphone as Handphone, per_email as Email, vol_name as Jabatan, per_workphone as Kelompok
			from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
			where a.per_id = b.per_id AND
			a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id
			AND vol_id = " .$posisi . " AND per_workphone LIKE '%" . $klpk . "%' 
			ORDER by per_workphone, vol_id, per_firstname LIMIT 1";
			
			
			$perintah = mysql_query($sSQL);
				$i = 0;
				$kelpk = " ";
					while ($hasilGD=mysql_fetch_array($perintah))
						{
						$i++;
						extract($hasilGD);
$iPersonID=$hasilGD[PersonID]; 

				echo "	<table align=\"center\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100px; height: 150px\">";
				echo "	<tbody>	<tr> <td valign=top style=\"text-align: center ; height: 20%; background-color: #". $warna . " ;\">";
				echo " <span style=\"font-size: 10px;\">";

				echo "Ketua Kelompok ";
				echo "<b>";
				echo $klpk ;
				echo "</b>";

				
				echo "	</span></td></tr>";
				echo "	<tr><td valign=top style=\"text-align: center; width: 100%; height: 60%;\"><div class='gallerycontainer'>";
				
		// Display photo or upload from file
	 		$photoFile = "Images/Person/thumbnails/" . $iPersonID . ".jpg";
	         if (file_exists($photoFile))
	         {
			 //enable enlarge image
			 //   echo '<a class="thumbnail" href="http://www.gkjbekasitimur.org/datawarga/PrintViewCari.php?PersonID='. $row[per_ID] .'" >';
			     echo '<a href="http://www.gkjbekasitimur.org/datawarga/PrintViewCari.php?PersonID='. $row[per_ID] .'" >';
				 
	        
				echo '<img border="1" src="'.$photoFile.'"  width="65" height="80">
			   ' . $row[per_FirstName] . ' </a>';		 
	        //emable enlarge image
			//echo '<img border="1" src="'.$photoFile.'" width="20" ><span>
			//   <img border="1" src="'.$photoFile.'" width="200" > 
			 //  ' . $row[per_FirstName] . ' </span></a>';		 
	             if ($bOkToEdit) {
	                 echo '
	                     <form method="post"
	                     action="PrintViewCari.php?PersonID=' . $iPersonID . '">
	                     <br>
	                     <input type="submit" class="icTinyButton"
	                     value="' . gettext("Delete Photo") . '" name="DeletePhoto">
	                     </form>';
	                 }
	         } else {
	             // Some old / M$ browsers can't handle PNG's correctly.
	             if ($bDefectiveBrowser)
	                echo '<img border="0" src="Images/NoPhoto.gif" width="50" >';
	             else
			       echo '<img border="0" src="Images/NoPhoto.png" width="50" >';

	             if ($bOkToEdit) {
	                 if (isset($PhotoError))
	                     echo '<span style="color: red;">' . $PhotoError . '</span><br>';

	                 echo '';
	             }
	         }
				
				echo "	</div></td></tr><tr><td valign=top style=\"text-align: center; height: 20%;background-color: #". $warna . " ;\">";
				echo " <span style=\"font-family: arial,helvetica,sans-serif;font-size: 10px;\">";
				echo $hasilGD[Nama];
				echo "	</span></td></tr>";
				echo "	</td></tr></tbody></table>";

				}


}


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

		<table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 850px; height: 528px;">
			<tbody>
				<tr style="text-align: left ; height: 20%; background-color: #ccffff" ;>
					<td>
						</td>
					<td>
						</td>
					<td>
						</td>
					<td valign=top colspan="5">
						<b>MPH - Majelis Pekerja Harian </b></td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>

				</tr>
				<tr style="text-align: center ; height: 20%; background-color: #ccffff" ;>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td valign=top >
					</td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						<? jabatan(61,"0099ff"); ?></td>
					<td>
						</td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						<? jabatan(1,"ccffff"); ?> </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
				</tr>
				<tr style="text-align: center ; height: 20%; background-color: #ccffff" ;>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
					     </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
				</tr>
				<tr style="text-align: center ; height: 20%; background-color: #ccffff" ;>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td valign=top >
						<? jabatan(65,"ffcc99"); ?></td>
					<td>
						 </td>
					<td>
						<? jabatan(66,"ffcc99"); ?></td>
					<td>
						 </td>
					<td>
						<? jabatan(67,"ccff66"); ?></td>
					<td>
						</td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
				</tr>
				<tr style="text-align: center ; height: 20%; background-color: #ccffff" ;>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
				</tr>
				<tr style="text-align: center ; height: 20%; background-color: #ccffff" ;>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						<? jabatan(69,"ff3399"); ?></td>
					<td>
						 </td>
					<td>
						<? jabatan(70,"ff3399"); ?></td>
					<td>
						 </td>
					<td>
						<? jabatan(71,"ff3399"); ?></td>
					<td>
						 </td>
					<td>
						<? jabatan(72,"ff3399") ?></td>
					<td>
						 </td>
					<td>
						<? jabatan(73,"ff3399"); ?></td>
					<td>
						 </td>
					<td>
						<? jabatan(74,"ff3399"); ?></td>
					<td>
						 </td>
					<td>
						<? jabatan(75,"ff3399"); ?></td>
					<td>
						 </td>
					<td>
						<? jabatan(76,"ff3399"); ?></td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
				</tr>
				<tr style="text-align: center ; height: 20%; background-color: #99ccff" ;>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td valign=top colspan="5" style="text-align: left ;">
						<b>MPL - Majelis Pekerja Lengkap </b></td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
				</tr>
				<tr>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ; >
						<? jabatan(77,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(80,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(82,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(85,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(88,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td valign=top style="text-align: center ; background-color: #99ccff" ;>
					Pengurus Kelompok <br>dan<br>Penanggung Jawab<br>Tempat Ibadah</td>
					<td valign=top style="text-align: center ; background-color: #99ccff" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #99ccff" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top colspan="3" style="text-align: center ; background-color: #ffff99" ;>
						<b>Pengurus Kelompok</b>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
					<b>Koordinator TI</b></td>
					<td valign=top style="background-color: #99ccff" ;>
						</td>
					<td valign=top style="background-color: #99ccff" ;>
							</td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(78,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(90,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(83,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(86,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(92,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",BTRG) ?></td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",CBTG) ?></td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(93,"ffff66"); ?>
						</td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
					</td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(79,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(81,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(84,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(91,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(89,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",CKRG) ?></td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",JTMY) ?></td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(94,"ffff66"); ?>
						</td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 <? jabatan(98,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 <? jabatan(87,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 <? jabatan(99,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",KRWG) ?></td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",MGHY) ?></td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(95,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td valign=top colspan="5" style="background-color: #ffcccc" ;>
						<b>BPM - Badan Pembantu Majelis</b></td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top >
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td>
						 </td>
					<td>
						 </td>
					<td valign=top >
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(24,"99ff66"); ?></td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(36,"99ff66"); ?></td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(20,"99ff66"); ?></td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(8,"99ff66"); ?></td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(52,"99ff66"); ?></td>
					<td valign=top >
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",PRST) ?></td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",TMBN) ?></td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(96,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(28,"99ff66"); ?></td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(40,"99ff66"); ?></td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(16,"99ff66"); ?></td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(12,"99ff66"); ?></td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(56,"99ff66"); ?></td>
					<td>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						</td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",WMJY) ?></td>
					<td valign=top style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						<? jabatan(97,"ffff66"); ?></td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
					<td valign=top style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>					
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
				</tr>
				<tr>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(32,"99ff66"); ?></td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(48,"99ff66"); ?></td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						<? jabatan(44,"99ff66"); ?></td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td valign=top style="background-color: #ffcccc" ;>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
				</tr>
				<tr>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
				</tr>
			</tbody>
		</table>



<?php
require "Include/Footer-Short.php";
?>