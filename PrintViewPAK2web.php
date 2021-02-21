<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPAK2web.php
 *  last change : 2011-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2011 Erwin Pratama for GKJ Bekasi Timur (www.gljbekasitimur.org)
 ******************************************************************************/

// Include the function library
//require "Include/Config.php";
require "Include/ConfigWeb.php";
//require "Include/Functions.php";
//require "Include/Header-Print.php";

// Get the Gol Darah ID from the querystring
$iStatus = FilterInput($_GET["Status"]);

$Judul = "Laporan - Daftar Pengajar PAK "; 
//require "Include/Header-Report.php";

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

?>

	<link rel="stylesheet" type="text/css" href="Include/Style.css">
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
			<table border="0"  width="600" cellspacing=0 cellpadding=0 align="center">

			<tr>
			<td ALIGN=center><b>No</b></td>
			<td ALIGN=center><b>Foto</b></td>
			<td ALIGN=center><b>Nama Lengkap </b></td>
			<td ALIGN=center><b>Jabatan</b></td>
			<td ALIGN=center><b>Kelompok</b></td>
			</tr>
			<?php

	
			
				
			$sSQL = "select a.per_ID as PersonID, a.per_FirstName AS 'Nama',
			fam_homephone as TelpRumah, c13 as TelpKantor, per_cellphone as Handphone, per_email as Email, vol_name as Jabatan, per_workphone as Kelompok
			from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
			where a.per_id = b.per_id AND
			a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id
			AND vol_id = 201 

			ORDER by vol_id, per_workphone, per_firstname";
			
			$perintah = mysql_query($sSQL);
				$i = 0;
				$komisi = " " ;
//				$komisi2 =  "Komisi Kesejahteraan Warga Gereja";
					while ($hasilGD=mysql_fetch_array($perintah))
						{
						$i++;

//						echo $komisi;
						


						
						extract($hasilGD);
						//Alternate the row color
					   $sRowClass = AlternateRowStyle($sRowClass);
// $komisi == crop($hasilGD[Jabatan],6);
   
					   
				if ( $komisi == crop($hasilGD[Jabatan],6) ) 
								{						
								
					echo "";
					} else {
									
					echo "<tr><td><hr></td><td><hr></td><td><hr></td><td><hr></td><td><hr></td><td><hr></td><td><hr></td><td><hr></td></tr>";
					}	   
					  					$komisi = crop($hasilGD[Jabatan],6); 
	
				?>
				
				<tr class="<?php echo $sRowClass; ?>">
				<td><? echo $i ?></td>
<?php 								

$iPersonID=$hasilGD[PersonID]; 

echo "	<td><font size=2><div class='gallerycontainer'>" ;
 
	 		// Display photo or upload from file
	 		$photoFile = "Images/Person/thumbnails/" . $iPersonID . ".jpg";
	         if (file_exists($photoFile))
	         {
			   echo '<a class="thumbnail"  >';
	           echo '<img border="1" src="'.$photoFile.'" width="20" ><span>
			   <img border="1" src="'.$photoFile.'" width="200" > 
			   ' . $row[per_FirstName] . ' </span></a>';		 
	             if ($bOkToEdit) {
	                 echo '
	                     <form method="post"
	                     action="indexpublic.php?PersonID=' . $iPersonID . '">
	                     <br>
	                     <input type="submit" class="icTinyButton"
	                     value="' . gettext("Delete Photo") . '" name="DeletePhoto">
	                     </form>';
	                 }
	         } else {
	             // Some old / M$ browsers can't handle PNG's correctly.
	             if ($bDefectiveBrowser)
	                echo '<img border="0" src="Images/NoPhoto.gif" width="20" >';
	             else
			       echo '<img border="0" src="Images/NoPhoto.png" width="20" >';

	             if ($bOkToEdit) {
	                 if (isset($PhotoError))
	                     echo '<span style="color: red;">' . $PhotoError . '</span><br>';

	                 echo '';
	             }
	         }
echo "</div></td>";
?> 
				<td><?=$hasilGD[Nama]?></center></td>
				<td><?=$hasilGD[Jabatan]?></td>
				<td><?=$hasilGD[Kelompok]?></td>

				<td></td>
				</tr>
				<?}?>
				
<?php	
echo "<tr><td><hr></td><td><hr></td><td><hr></td><td><hr></td><td><hr></td><td><hr></td><td><hr></td><td><hr></td></tr>";
//		echo "<tr><td></td><td></td><td><p><font size=1>	";
 //       echo "<b>KKWG : </b>Komisi Kesejahteraan Warga Gereja <br>";
//		echo "<b>KPK  : </b>Komisi Pelayanan Kasih <br>";
 //       echo "<b>KPG  : </b>Komisi Peribadahan Gereja <br>";
//        echo "<b>KPWG : </b>Komisi Pembinaan Warga Gereja <br>";
//        echo "<b>KA   : </b>Komisi Anak <br>";
//        echo "<b>Korem: </b>Komisi Remaja <br>";
//        echo "<b>Kompa: </b>Komisi Pemuda <br>";
//        echo "<b>KWD  : </b>Komisi Warga Dewasa <br>";
//        echo "<b>KAY  : </b>Komisi Adyuswo <br>";
//        echo "<b>KPD  : </b>Komisi Pelayanan Doa <br>";
//        echo "<b>KKG  : </b>Komisi Kesenian Gerejawi <br>";
//        echo "<b>Kolitbang:</b>Komisi Penelitian dan Pengembangan <br>";
//        echo "<b>Humas: </b>Komisi Komunikasi dan Informasi <br>";
		
		?>
		</p></font></td></tr>
			</table>



<?php
//Print_r ($_SESSION);
		$logvar1 = "Report";
		$logvar2 = "Daftar Pengajar PAK (web) ";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "','" . $hostygakses . "','" . $ipaddr . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
//require "Include/Footer-Short.php";
?>
