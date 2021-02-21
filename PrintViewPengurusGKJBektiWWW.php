<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPengurusGKJBekti.php
 *  last change : 2011-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2011 Erwin Pratama for GKJ Bekasi Timur (www.gljbekasitimur.org)
 ******************************************************************************/
// Database connection constants

		
// Include the function library
require "Include/ConfigWeb.php";
//require "Include/Config.php";
//require "Include/Functions.php";
//require "Include/Header-Print.php";

// Get the Gol Darah ID from the querystring
//$iStatus = FilterInput($_GET["Status"]);

$Judul = "Laporan - Struktur Majelis dan BPM GKJ Bekasi Timur "; 
//require "Include/Header-Report.php";


function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function get_host($ip){
        $ptr= implode(".",array_reverse(explode(".",$ip))).".in-addr.arpa";
        $host = dns_get_record($ptr,DNS_PTR);
        if ($host == null) return $ip;
        else return $host[0]['target'];
}
			
$ipaddr = getRealIpAddr();
$hostygakses = get_host($ipaddr);
			
//Print_r ($_SESSION);
		$logvar1 = "Report";
		$logvar2 = "Struktur Majelis dan BPM GKJ Bekasi Timur (web) ";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "','" . $hostygakses . "','" . $ipaddr . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);



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

			$sSQL = "select a.per_ID as PersonID, CONCAT('<a>',a.per_FirstName,'</a>') AS 'Nama',vol_id, 
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
				echo "	<tbody>	<tr> <td style=\"text-align: center ; height: 20%; background-color: #". $warna . " ;\">";
				echo " <span style=\"font-family: arial,helvetica,sans-serif;font-size: 10px;\">";
				echo $hasilGD[Jabatan];
		//		echo - $hasilGD[vol_id];
		//		echo - $posisi;
				echo "	</span></td></tr>";
				echo "	<tr><td style=\"text-align: center; width: 100%; height: 60%;\"><div class='gallerycontainer'>";
				
		// Display photo or upload from file
	 		$photoFile = "Images/Person/thumbnails/" . $iPersonID . ".jpg";
	         if (file_exists($photoFile))
	         {
		
	           echo '<img border="1" src="'.$photoFile.'" width="50" ><span>
	
			   ' . $row[per_FirstName] . ' </span>';		 

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
			 
			 

				echo "	</div></td></tr><tr><td style=\"text-align: center; height: 20%;background-color: #". $warna . " ;\">";
				echo " <span style=\"font-family: arial,helvetica,sans-serif;font-size: 10px;\">";
				
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
			//	echo $posisi;
				$sSQL = "select * from volunteeropportunity_vol
							where  vol_id = " .$posisi ;
			
				$perintah = mysql_query($sSQL);


					while ($hasilGD=mysql_fetch_array($perintah))
						{

						extract($hasilGD);
				
				echo "	<table align=\"center\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100px; height: 150px\">";
				echo "	<tbody>	<tr> <td style=\"text-align: center ; height: 20%; background-color: #". $warna . " ;\">";
				echo " <span style=\"font-family: arial,helvetica,sans-serif;font-size: 10px;\">";
				
		//		echo test;
				
				echo $hasilGD[vol_Name];
		//		echo - $hasilGD[vol_id];
		//		echo $posisi ;
		//		echo $sSQL ;
				echo "	</span></td></tr>";
				echo "	<tr><td style=\"text-align: center; width: 100%; height: 60%;\"><div class='gallerycontainer'>";
				echo "	</div></td></tr><tr><td style=\"text-align: center; height: 20%;background-color: #". $warna . " ;\">";
				echo " <span style=\"font-family: arial,helvetica,sans-serif;font-size: 10px;\">";
		//		echo $hasilGD[Nama];
				echo "	</span></td></tr>";
				echo "	</td></tr></tbody></table>";
				}
				}


}

function pengurusklpk($posisi,$warna,$klpk) {

			$sSQL = "select a.per_ID as PersonID, CONCAT('<a>',a.per_FirstName,'</a>') AS 'Nama',vol_id, 
			fam_homephone as TelpRumah, c13 as TelpKantor, per_cellphone as Handphone, per_email as Email, vol_name as Jabatan, per_workphone as Kelompok
			from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
			where a.per_id = b.per_id AND
			a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id
			AND vol_id = " .$posisi . " AND per_workphone LIKE '%" . $klpk . "%' 
			ORDER by per_workphone, vol_id, per_firstname LIMIT 1";
			
			
			$perintah = mysql_query($sSQL);
				$i = 0;
				$kelpk = " ";
			//	echo $sSQL;
					while ($hasilGD=mysql_fetch_array($perintah))
						{
						$i++;
						extract($hasilGD);
$iPersonID=$hasilGD[PersonID]; 

				echo "	<table align=\"center\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100px; height: 150px\">";
				echo "	<tbody>	<tr> <td style=\"text-align: center ; height: 20%; background-color: #". $warna . " ;\">";
				echo " <span style=\"font-family: arial,helvetica,sans-serif;font-size: 10px;\">";

				echo "Ketua Kelompok ";
				echo "<b>";
				echo $klpk ;
				echo "</b>";

				
				echo "	</span></td></tr>";
				echo "	<tr><td style=\"text-align: center; width: 100%; height: 60%;\"><div class='gallerycontainer'>";
				
		// Display photo or upload from file
	 		$photoFile = "Images/Person/thumbnails/" . $iPersonID . ".jpg";
	         if (file_exists($photoFile))
	         {
		
	           echo '<img border="1" src="'.$photoFile.'" width="50" ><span>

			   ' . $row[per_FirstName] . ' </span>';		 

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
				
				echo "	</div></td></tr><tr><td style=\"text-align: center; height: 20%;background-color: #". $warna . " ;\">";
				echo " <span style=\"font-family: arial,helvetica,sans-serif;font-size: 10px;\">";
				echo $hasilGD[Nama];
				echo "	</span></td></tr>";
				echo "	</td></tr></tbody></table>";

				}


}


?>





<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title><?php echo $Judul ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $sRootPath."/"; ?>Include/<?php echo $_SESSION['sStyle']; ?>">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo $sRootPath."/"; ?>Include/jscalendar/calendar-blue.css" title="cal-style">

<STYLE TYPE="text/css">
</STYLE>
</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >
<DIV align=center >

<table
 style="width: 750; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="2" cellspacing="2">
  <tbody>

  </tbody>
</table>
		<table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 850px; height: 528px;">
			<tbody>
				<tr style="text-align: left ; height: 20%; background-color: #ccffff" ;>
					<td>
						</td>
					<td>
						</td>
					<td>
						</td>
					<td colspan="5">
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
					<td >
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
					<td >
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
					<td colspan="5" style="text-align: left ;">
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
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ; >
						<? jabatan(77,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(80,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(82,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(85,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(88,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td style="text-align: center ; background-color: #99ccff" ;>
					Pengurus Kelompok <br>dan<br>Penanggung Jawab<br>Tempat Ibadah</td>
					<td style="text-align: center ; background-color: #99ccff" ;>
						 </td>
					<td style="text-align: center ; background-color: #99ccff" ;>
						 </td>
					<td style="text-align: center ; background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td colspan="3" style="text-align: center ; background-color: #ffff99" ;>
						<b>Pengurus Kelompok</b>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
					<b>Koordinator TI</b></td>
					<td style="background-color: #99ccff" ;>
						</td>
					<td style="background-color: #99ccff" ;>
							</td>
					<td style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(78,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(90,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(83,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(86,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(92,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",BTRG) ?></td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",CBTG) ?></td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(93,"ffff66"); ?>
						</td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
					</td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(79,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(81,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(84,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(91,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(89,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",CKRG) ?></td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",JTMY) ?></td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(94,"ffff66"); ?>
						</td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 <? jabatan(98,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 <? jabatan(87,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",KRWG) ?></td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",MGHY) ?></td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(95,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td colspan="5" style="background-color: #ffcccc" ;>
						<b>BPM - Badan Pembantu Majelis</b></td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td >
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td>
						 </td>
					<td>
						 </td>
					<td >
						 </td>
					<td style="background-color: #ffcccc" ;>
						<? jabatan(24,"99ff66"); ?></td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						<? jabatan(36,"99ff66"); ?></td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						<? jabatan(20,"99ff66"); ?></td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						<? jabatan(8,"99ff66"); ?></td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						<? jabatan(52,"99ff66"); ?></td>
					<td >
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",PRST) ?></td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",TMBN) ?></td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(96,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td style="background-color: #ffcccc" ;>
						<? jabatan(28,"99ff66"); ?></td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						<? jabatan(40,"99ff66"); ?></td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						<? jabatan(16,"99ff66"); ?></td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						<? jabatan(12,"99ff66"); ?></td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						<? jabatan(56,"99ff66"); ?></td>
					<td>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						</td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						<? pengurusklpk(4,"CCFF99",WMJY) ?></td>
					<td style="text-align: center ; background-color: #ffff99" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						<? jabatan(97,"ffff66"); ?></td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
					<td style="background-color: #99ccff" ;>
						 </td>
				</tr>
				<tr>
					<td>
						 </td>
					<td>
						 </td>
					<td>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
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
					<td style="background-color: #ffcccc" ;>
						<? jabatan(32,"99ff66"); ?></td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						<? jabatan(48,"99ff66"); ?></td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
						<? jabatan(44,"99ff66"); ?></td>
					<td style="background-color: #ffcccc" ;>
						 </td>
					<td style="background-color: #ffcccc" ;>
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