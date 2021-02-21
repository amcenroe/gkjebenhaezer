<?php
/*******************************************************************************
 *
 *  filename    : PrintViewKartuPersembahan.php
 *  last change : 2011-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2011 Erwin Pratama for GKJ Bekasi Timur (www.gljbekasitimur.org)
 ******************************************************************************/
$refresh = microtime() ;
 
// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
require "Include/BarcodeQR.php";

//$refresh = microtime() ;
$alamaturl= $sChurchWebsite;
//require "Include/Header-Print.php";

// Get the Gol Darah ID from the querystring
$iStatus = FilterInput($_GET["Status"]);
$iJenisKartu = FilterInput($_GET["JenisKartu"]);
//$personID = FilterInput($_GET["PersonID"],'int');


$iA1 = FilterInput($_GET["A1"]);
$iA2 = FilterInput($_GET["A2"]);
$iA3 = FilterInput($_GET["A3"]);
$iA4 = FilterInput($_GET["A4"]);

$iB1 = FilterInput($_GET["B1"]);
$iB2 = FilterInput($_GET["B2"]);
$iB3 = FilterInput($_GET["B3"]);
$iB4 = FilterInput($_GET["B4"]);

$iC1 = FilterInput($_GET["C1"]);
$iC2 = FilterInput($_GET["C2"]);
$iC3 = FilterInput($_GET["C3"]);
$iC4 = FilterInput($_GET["C4"]);

$Judul = "PrintViewKartuPersembahan $sChurchName"; 
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

function stiker($kolom,$nilai) {

$iJenisKartu = FilterInput($_GET["JenisKartu"]);
//$NilaiKolom = substr("abcdef", 0, -1);  // returns "abcde"
//$rest = substr("abcdef", 2, -1);  // returns "cde"
//$rest = substr("abcdef", 4, -4);  // returns false
//$rest = substr("abcdef", -3, -1); // returns "de"

$length_of_string = strlen($nilai); 
//echo $nilai;
//echo $length_of_string;

if ( $length_of_string<6 ) {

				echo "	<table border=\"0\"  height=100px width=200px >";
				echo "	<tr>";
				echo "		<td width=\"80px\" style=\"font-family: Arial; font-size: 18pt; font-weight:bold; text-align: left; color: rgb(0, 0, 102);\" 
				valign=top>";
				echo "		" ;
				echo "		<br>" ;
				echo "		<br>" ;
				echo "		</td>";
				echo "		<td width=\"120px\">";
				echo "		</td>";
				echo "	</tr>";
				echo "	</table>";				

}
else 
{
//$personID = FilterInput($_GET["PersonID"],'int');
$personID = substr($nilai, 2, 4);  


				$sSQL = "select per_ID as ID, per_FirstName as Nama ,
				         CASE per_cls_id
						 	WHEN '1' THEN 'Warga'
						 	WHEN '2' THEN 'Titipan'
						 	WHEN '3' THEN 'Tamu'
						 	WHEN '5' THEN 'Calon'
							WHEN '6' THEN 'Pindah'
							WHEN '7' THEN 'Meninggal'
							WHEN '8' THEN 'NonWarga'
							WHEN '9' THEN 'TdkAktif'
						END AS Status,				
				         per_WorkPhone as Kelompok, per_fam_ID as ID_Kelg,
				         CASE per_fmr_id
						 	WHEN '1' THEN 'KK'
						 	WHEN '2' THEN 'Ist'
						 	WHEN '3' THEN 'Ank'
						 	WHEN '4' THEN 'Sdr'
						END AS St_Kelg,
						CASE per_Gender
						 	WHEN '1' THEN 'L'
						 	WHEN '2' THEN 'P'
						END AS Gender
						
				         from person_per
						 WHERE  per_cls_ID < 3  AND per_ID = '".$personID."'
						ORDER BY per_WorkPhone, per_fam_ID, per_fmr_ID,per_birthyear, per_FirstName";
						
				$perintah = mysql_query($sSQL);
				$i = 0;
								while ($hasilGD=mysql_fetch_array($perintah))
								{
								$i++;
													extract($hasilGD);
													//Alternate the row color
								                    $sRowClass = AlternateRowStyle($sRowClass);
								$NamaWarga = $hasilGD[Nama];	
								$KelompokWarga = $hasilGD[Kelompok];
						
}



				
				


				echo "	<table border=\"0\"  >";
				echo "	<tr>";
				echo "		<td width=\"80px\" style=\"font-family: Arial; font-size: 20pt; font-weight:bold; text-align: left; color: rgb(0, 0, 102);\" 
				valign=top>";
				echo "		".$personID ;
				echo "		<br>".$KelompokWarga  ;
				echo "<br>".KodeNamaWarga($NamaWarga);
				//echo "		<br>".$KodeNama."".$KodeNama2 ;
				echo "		</td>";
				echo "		<td >";
			//	echo $iJenisKartu;
		if ($iJenisKartu == "PPPG"){
					echo "<img src=\"1piksel.jpg&amp;$refresh \" height=\"1\" width=\"180\"  border=\"0\" >";
		}else
			{
				echo "<img src=\"1piksel.jpg&amp;$refresh \" height=\"1\" width=\"220\" >";
			}	
				echo "		</td>";
								echo "		<td>";
				
				echo "		</td>";
	
				echo "	</tr>";
				echo "<tr>";
					echo "<td>";
					echo "</td>";
					echo "<td>";
					echo "</td>";
					echo "<td>";
			//s		echo $iJenisKartu;
		if ($iJenisKartu == PPPG){
					 echo "<img src=\"1piksel.jpg&amp;$refresh \" height=\"20\" width=\"1\" border=\"0\" >";	
		}else
		{
					 echo "<img src=\"1piksel.jpg&amp;$refresh \" height=\"50\" width=\"1\" >";
		}
	 
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td>";
					echo "</td>";
					echo "<td>";
					echo "</td>";
					echo "<td style=\"font-family: Arial; font-size: 6pt; text-align: center; color: rgb(0, 0, 102);\">";
				
 $width = $height = 80; 
 $alamatlengkapurl = "PersonView.php?PersonID=$personID&KLPK=$KelompokWarga&INIT=$KodeNama$KodeNama2&KARTU=$iJenisKartu";
 //$url = urlencode("http://www.gkjbekasitimur.org/datawarga/PersonView.php?PersonID=$personID&KLPK=$KelompokWarga&INIT=$KodeNama$KodeNama2&KARTU=$iJenisKartu"); 
  $url = urlencode(".$alamatlengkapurl."); 
 $error = "L"; // handle up to 30% data loss, or "L" (7%), "M" (15%), "Q" (25%) 
 $border = 0; 

 echo "<img src=\"http://chart.googleapis.com/chart?". "chs={$width}x{$height}&cht=qr&chld=$error|$border&chl=$url\" />"; 
 echo "<br>".$iJenisKartu;
					echo "</td>";
				echo "</tr>";
				echo "	</table>";
}

}


?>


		<table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 750px">
		<?php //echo $iA1; 
	//	echo $iJenisKartu;
		
		if ($iJenisKartu == "PPPG"){
		$MarginAtas = 115;
		$MarginKiri = 170;
		$Warna = "0099ff"	;

		}else
		{

		$MarginAtas = 80;
		$MarginKiri = 160;	
		$Warna = "ff0000";	
		}
		
		
		?>
			<tbody>
				<tr>
				<td height="<?php echo $MarginAtas;?>px" ></td>
				</tr>
				<tr style="text-align: left ; vertical-align:top; " >
					
					<td width="<?php echo $MarginKiri;?>">
					</td>	
					<td width="5"   style="background-color: #<?php echo $Warna;?>" ; > 
					</td>					
					<td> <? stiker(B1,"$iB1", $alamaturl); ?>
					</td>
					<td width="5"   style="background-color: #<?php echo $Warna;?>" ; > 
					</td>

				</tr>



			</tbody>
		</table>



<?php
//require "Include/Footer-Short.php";
?>