<?php
require ("Include/Config.php");
require ("Include/Functions.php");
require ("Include/Header.php");
require ("Include/ReportFunctions.php");

$iGroupID = FilterInput($_GET["GroupID"],'int');

// Read values from config table into local variables
// **************************************************
// *  2008 Erwin Pratama for GKJ Bekasi WIl Timur

$sSQL = "SELECT cfg_name, IFNULL(cfg_value, cfg_default) AS value FROM config_cfg WHERE cfg_section='ChurchInfoReport'";
$rsConfig = mysql_query($sSQL);			// Can't use RunQuery -- not defined yet
if ($rsConfig) {
	while (list($cfg_name, $cfg_value) = mysql_fetch_row($rsConfig)) {
		$$cfg_name = $cfg_value;
	}
}

if ($nChurchLatitude == 0 || $nChurchLongitude == 0) {

	require ("Include/GeoCoder.php");
	$myAddressLatLon = new AddressLatLon;

	// Try to look up the church address to center the map.
	$myAddressLatLon->SetAddress ($sChurchAddress, $sChurchCity, $sChurchState, $sChurchZip);
	$ret = $myAddressLatLon->Lookup ();
	if ($ret == 0) {
		$nChurchLatitude = $myAddressLatLon->GetLat ();
		$nChurchLongitude = $myAddressLatLon->GetLon ();

		$sSQL = "UPDATE config_cfg SET cfg_value='" . $nChurchLatitude . "' WHERE cfg_name=\"nChurchLatitude\"";
		RunQuery ($sSQL);
		$sSQL = "UPDATE config_cfg SET cfg_value='" . $nChurchLongitude . "' WHERE cfg_name=\"nChurchLongitude\"";
		RunQuery ($sSQL);
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
   
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?php echo $sGoogleMapKey; ?>&sensor=false"></script>
  </head>
  <body>
    <div id="map" style="width: 1024px; height: 550px"></div>


    <script type="text/javascript">
    //<![CDATA[


	var myOptions = {
//	   center: new google.maps.LatLng(<?php echo $nChurchLatitude . ", " . $nChurchLongitude; ?>),
       center: new google.maps.LatLng(-6.2018348, 106.8838982 ),
       zoom: 12,
       mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map"), myOptions);   
//
//	var churchMark = new google.maps.Marker({
//                                                position: new google.maps.LatLng(<?php echo $nChurchLatitude . ", " . $nChurchLongitude; ?>), 
//                                                map: map});
//	 
//	var churchInfoWin = new google.maps.InfoWindow({content: "<?php echo $sChurchName . "<p>" . $sChurchAddress . "<p>" . $sChurchCity . ", " . $sChurchState . "  " . $sChurchZip;?>"});
//	google.maps.event.addListener(churchMark, "click", function() {
 //                                                 churchInfoWin.open(map,churchMark);
 //                                  });



	
<?php
//Lokasi Tempat Ibadah
	$sSQL = "SELECT KodeTI, NamaTI, AlamatTI1, AlamatTI2 ,KotaTI, latitude, longitude FROM LokasiTI";
	//$sSQL = "SELECT KodeJemaat, NamaJemaat, alamat1, alamat2, notelp, nofax, email, KotaKab, jemaat_latitude, jemaat_longitude FROM jemaat";
	$rsJemaat = RunQuery ($sSQL);
	while ($aJemaat = mysql_fetch_array($rsJemaat)) {
		extract ($aJemaat);
		if ($longitude != 0 && $latitude != 0) {
	?>

	 <?php 
 				//$TIDescription = MakeSalutationUtility ($KodeTI);
				$TIDescriptionTitle = "TI. " . $NamaTI ;
				$TIDescription = " " . $NamaTI ;
 				$TIDescription .= "<p>" . $AlamatTI1 . "<p>" . $AlamatTI2 . ", " . $KotaTI ;
 	?>	
			
	var image = 'Images/Icons/gereja.png';
	var TIMark<?php echo $KodeTI;?> = new google.maps.Marker({
        position: new google.maps.LatLng(<?php echo $latitude . ", " . $longitude; ?>),map: map, icon: image,
		title: "<?php echo $TIDescriptionTitle; ?>"
		});
		
	var jemaat<?php echo $KodeTI; ?>InfoWin = new google.maps.InfoWindow({content: "<?php echo $TIDescription; ?>"}); 
	google.maps.event.addListener(TIMark<?php echo $KodeTI; ?>, "click", 
	function() { jemaat<?php echo $KodeTI; ?>InfoWin.open(map,TIMark<?php echo $KodeTI;?>); });

	
			
			
		<?php
		}

	}
		?>
		
	
	
	
	
<?php
	$appendToQuery = "";
	if ($iGroupID > 0) {
		// If mapping only members of  a group build a condition to add to the query used below

		//Get all the members of this group
		$sSQL = "SELECT per_fam_ID FROM person_per, person2group2role_p2g2r WHERE per_ID = p2g2r_per_ID AND p2g2r_grp_ID = " . $iGroupID;
		$rsGroupMembers = RunQuery($sSQL);
		$appendToQuery = " WHERE fam_ID IN (";
		while ($aPerFam = mysql_fetch_array($rsGroupMembers)) {
			extract ($aPerFam);
			$appendToQuery .= $per_fam_ID . ",";
		}
		$appendToQuery = substr($appendToQuery, 0, strlen ($appendToQuery)-1);
		$appendToQuery .= ")";
		
		
	} elseif ($iGroupID > -1) {
        // group zero means map the cart
		$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID IN (" . ConvertCartToString($_SESSION['aPeopleCart']) . ")";
		$rsGroupMembers = RunQuery($sSQL);
		$appendToQuery = " WHERE fam_ID IN (";
		while ($aPerFam = mysql_fetch_array($rsGroupMembers)) {
			extract ($aPerFam);
			$appendToQuery .= $per_fam_ID . ",";
		}
		$appendToQuery = substr($appendToQuery, 0, strlen ($appendToQuery)-1);
		$appendToQuery .= ")";
    }

	$sSQL = "SELECT fam_WorkPhone, fam_ID, fam_Name, fam_latitude, fam_longitude, fam_Address1, fam_City, fam_State, fam_Zip FROM family_fam";
	$sSQL .= $appendToQuery;
	$rsFams = RunQuery ($sSQL);
	while ($aFam = mysql_fetch_array($rsFams)) {
		extract ($aFam);
		if ($fam_longitude != 0 && $fam_latitude != 0) {
		
		if ( $fam_WorkPhone == "BTRG" ) { 
			//$WarnaPointer = "http://www.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png"; 
			$WarnaPointer = "Images/Icons/fam_red.png"; 
		} elseif ( $fam_WorkPhone == "CBTG" ) { 
			//$WarnaPointer = "http://www.google.com/intl/en_us/mapfiles/ms/micons/green-dot.png"; 
			$WarnaPointer = "Images/Icons/fam_blu.png"; 			
		} elseif ( $fam_WorkPhone == "CKRG" ) { 
			//$WarnaPointer = "http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png"; 
			$WarnaPointer = "Images/Icons/fam_lblu.png"; 			
		} elseif ( $fam_WorkPhone == "JTMY" ) { 
			//$WarnaPointer = "http://www.google.com/intl/en_us/mapfiles/ms/micons/yellow-dot.png"; 
			$WarnaPointer = "Images/Icons/fam_gre.png"; 
		} elseif ( $fam_WorkPhone == "MGHY" ) { 
			//$WarnaPointer = "http://www.google.com/intl/en_us/mapfiles/ms/micons/purple-dot.png"; 
			$WarnaPointer = "Images/Icons/fam_dgre.png"; 
		} elseif ( $fam_WorkPhone == "KRWG" ) { 
			//$WarnaPointer = "http://www.google.com/intl/en_us/mapfiles/ms/micons/ltblue-dot.png"; 
			$WarnaPointer = "Images/Icons/fam_grey.png"; 
		} elseif ( $fam_WorkPhone == "PRST" ) { 
			//$WarnaPointer = "http://www.google.com/intl/en_us/mapfiles/ms/micons/orange-dot.png"; 
			$WarnaPointer = "Images/Icons/fam_ora.png"; 
		} elseif ( $fam_WorkPhone == "TMBN" ) { 
			//$WarnaPointer = "http://www.google.com/intl/en_us/mapfiles/ms/micons/pink-dot.png"; 
			$WarnaPointer = "Images/Icons/fam_yel.png"; 
		} elseif ( $fam_WorkPhone == "WMJY" ) { 
			//$WarnaPointer = "http://www.google.com/intl/en_us/mapfiles/ms/micons/blue.png"; 
			$WarnaPointer = "Images/Icons/fam_pur.png"; 
		} else { 
			//$WarnaPointer = "http://www.gkjbekasitimur.org/datawarga/Images/Icons/gereja.png"; 
			$WarnaPointer = "Images/Icons/fam_bla.png"; 
			}

		
		
		
		
?>


		
		// KelpIcon.iconSize = new GSize(32, 32);
		//	KelpIcon.image = "http://www.google.com/intl/en_us/mapfiles/ms/micons/blue.png"; 


			
		<?php 
 				//$famDescription = MakeSalutationUtility ($fam_ID);
				$famDescriptionTitle = "Kelg. ". $fam_Name ." (".$fam_WorkPhone.")";
				$famDescription = "Kelg. ". $fam_Name;
 				$famDescription .= "<p>" . $fam_Address1 . "<p>" . $fam_City . ", " . $fam_State . "  " . $fam_Zip. "<p>" . $fam_WorkPhone;
 			?>	
			
		var image = "<?php echo $WarnaPointer; ?>";
		var famMark<?php echo $fam_ID;?> = new google.maps.Marker({
        position: new google.maps.LatLng(<?php echo $fam_latitude . ", " . $fam_longitude; ?>),map: map, icon: image,
		
		title: "<?php echo $famDescriptionTitle; ?>"
		});
 					
	var jemaat<?php echo $fam_ID; ?>InfoWin = new google.maps.InfoWindow({content: "<?php echo $famDescription; ?>"}); 
	google.maps.event.addListener(famMark<?php echo $fam_ID; ?>, "click", 
	function() { jemaat<?php echo $fam_ID; ?>InfoWin.open(map,famMark<?php echo $fam_ID;?>); });

			
			
			
			
			
			
			
			
			
			
			
<?php
		}

	}
?>

    //]]>
    </script>

  </body>
</html>
