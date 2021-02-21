<?php
/*******************************************************************************
 *
 *  filename    : PrintViewSticker103.php
 *  last change : 2011-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2011 Erwin Pratama for GKJ Bekasi Timur (www.gljbekasitimur.org)
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
require "Include/BarcodeQR.php";

//require "Include/Header-Print.php";

// Get the Gol Darah ID from the querystring
$iStatus = FilterInput($_GET["Status"]);

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

$Judul = "Laporan - Struktur Majelis dan BPM GKJ Bekasi Timur "; 
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
						 WHERE  (per_cls_ID < 5 OR per_cls_ID > 8 ) AND per_ID = '".$personID."'
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


$string = $NamaWarga;
$words = explode(' ', $string);
//var_dump($words);

$to_keep = array_slice($words, 0, 3);
//var_dump($to_keep);

$final_string = implode(' ', $to_keep);

//Kode Nama
if ($to_keep[0]=="I"){
$KodeNama = strtoupper(substr($to_keep[1], 0, 2));
$KodeNama2 =   strtoupper(substr($to_keep[2], 0, 2));

}else{
		$KodeNama = strtoupper(substr($to_keep[0], 0, 2));

		if ($to_keep[1]<>""){
			$KodeNama2 = strtoupper(substr($to_keep[1], 0, 2));
			} else {
			$KodeNama2 =   strtoupper(substr($to_keep[0], -2));
			}
	}






				echo "	<table border=\"0\"  height=100px width=200px >";
				echo "	<tr>";
				echo "		<td width=\"80px\" style=\"font-family: Arial; font-size: 18pt; font-weight:bold; text-align: left; color: rgb(0, 0, 102);\" 
				valign=top>";
				echo "		".$personID ;
				echo "		<br>".$KelompokWarga  ;
				echo "		<br>".$KodeNama."".$KodeNama2 ;
				echo "		</td>";
				echo "		<td width=\"120px\">";
				
 $width = $height = 100; 
 $url = urlencode("http://www.gkjbekasitimur.org/datawarga/PersonView.php?PersonID=$personID&KLPK=$KelompokWarga&INIT=$KodeNama$KodeNama2"); 
 $error = "M"; // handle up to 30% data loss, or "L" (7%), "M" (15%), "Q" (25%) 
 $border = 1; 
 echo "<img src=\"http://chart.googleapis.com/chart?". "chs={$width}x{$height}&cht=qr&chld=$error|$border&chl=$url\" />"; 
 
				echo "		</td>";
				echo "	</tr>";
				echo "	</table>";
}

}


?>


		<table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 750px">
		<?php //echo $iA1; ?>
			<tbody>
				<tr>
				<td height="25px" ></td>
				</tr>
				<tr style="text-align: left ; vertical-align:top;  background-color: #ccffff" ;>
					<td width="5" > 
					</td>
					<td width="200"> <? stiker(A1,"$iA1"); ?>
					</td>		
					<td width="25" > 
					</td>
					<td width="200"> <? stiker(B1,"$iB1"); ?>
					</td>
					<td width="25"> 
					</td>
					<td width="200"> <? stiker(C1,"$iC1"); ?>
					</td>
				</tr>

				<tr>
				<td height="40px" ></td>
				</tr>
				
				<tr style="text-align: left ; vertical-align:top;  background-color: #ccffff" ;>
					<td width="5" > 
					</td>
					<td width="200"> <? stiker(A2,"$iA2"); ?>
					</td>		
					<td width="25" > 
					</td>
					<td width="200"> <? stiker(B2,"$iB2"); ?>
					</td>
					<td width="25"> 
					</td>
					<td width="200"> <? stiker(C2,"$iC2"); ?>
					</td>
				</tr>
				
				<tr>
				<td height="40px" ></td>
				</tr>
				
				<tr style="text-align: left ; vertical-align:top;  background-color: #ccffff" ;>
									<td width="5" > 
					</td>
					<td width="200"> <? stiker(A3,"$iA3"); ?>
					</td>		
					<td width="25" > 
					</td>
					<td width="200"> <? stiker(B3,"$iB3"); ?>
					</td>
					<td width="25"> 
					</td>
					<td width="200"> <? stiker(C3,"$iC3"); ?>
					</td>
				</tr>
				
				<tr>
				<td height="40px" ></td>
				</tr>	
				
				<tr style="text-align: left ; vertical-align:top;  background-color: #ccffff" ;>
									<td width="5" > 
					</td>
					<td width="200"> <? stiker(A4,"$iA4"); ?>
					</td>		
					<td width="25" > 
					</td>
					<td width="200"> <? stiker(B4,"$iB4"); ?>
					</td>
					<td width="25"> 
					</td>
					<td width="200"> <? stiker(C4,"$iC4"); ?>
					</td>
				</tr>

			</tbody>
		</table>



<?php
//require "Include/Footer-Short.php";
?>