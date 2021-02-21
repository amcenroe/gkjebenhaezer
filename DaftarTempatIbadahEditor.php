<?php
/*******************************************************************************
 *
 *  filename    : DaftarTempatIbadahEditor.php
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
 *  Sistem Informasi GKJ Bekasi Timur is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";

//Set the page title
$sPageTitle = gettext("Daftar Gereja dalam Sinode GKJ");

//Get the KodeTI out of the querystring
$iKodeTI = FilterInput($_GET["KodeTI"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?KodeTI= manually)
if (strlen($iKodeTI) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsGereja = RunQuery($sSQL);
	extract(mysql_fetch_array($rsGereja));

	if (mysql_num_rows($rsGereja) == 0)
	{
		Redirect("Menu.php");
		exit;
	}

	if ( !(
	       $_SESSION['bEditRecords'] ||
	       ($_SESSION['bEditSelf'] && $_SESSION['iUserID']) ||
	       ($_SESSION['bEditSelf'] && $per_fam_ID==$_SESSION['iFamID'])
		  )
	   )
	{
		Redirect("Menu.php");
		exit;
	}
}
elseif (!$_SESSION['bAddRecords'])
{
	Redirect("Menu.php");
	exit;
}
// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
	extract ($aRow);
	$aSecurityType[$lst_OptionID] = $lst_OptionName;
}

if (isset($_POST["GerejaSubmit"]) || isset($_POST["GerejaSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
				$sKodeTI = FilterInput($_POST["KodeTI"]);
				$sNamaTI = FilterInput($_POST["NamaTI"]);
				$sAlamatTI1 = FilterInput($_POST["AlamatTI1"]);
				$sAlamatTI2 = FilterInput($_POST["AlamatTI2"]);
				$sKotaTI = FilterInput($_POST["KotaTI"]);
				$sKodePOSTI = FilterInput($_POST["KodePOSTI"]);
				$sTelepon  = FilterInput($_POST["Telepon"]);
				$sFax = FilterInput($_POST["Fax"]);
				$slatitude = FilterInput($_POST["latitude"]);
				$slongitude = FilterInput($_POST["longitude"]);
	$sDateLastEdited = FilterInput($_POST["DateLastEdited"]);
	$sDateEntered  = FilterInput($_POST["DateEntered"]);
	$sEnteredBy = FilterInput($_POST["EnteredBy"]);
	$sEditedBy = FilterInput($_POST["EditedBy"]);
	
	//Initialize the error flag
	$bErrorFlag = false;

	// Validate Mail Date if one was entered
	if (strlen($dTanggal) > 0)
	{
		$dateString = parseAndValidateDate($dTanggal, $locale = "US", $pasfut = "past");
		if ( $dateString === FALSE ) {
			$sTanggalError = "<span style=\"color: red; \">"
								. gettext("Not a valid Friend Date") . "</span>";
			$bErrorFlag = true;
		} else {
			$dTanggal = $dateString;
		}
	}

	//If no errors, then let's update...
		// New Data (add)
		if (strlen($iKodeTI) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO LokasiTI ( 		
				NamaTI,
				AlamatTI1,
				AlamatTI2,
				KotaTI,
				KodePOSTI,
				Telepon,
				Fax,
				latitude,
				longitude,
				DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sNamaTI . "',
			'" . $sAlamatTI1 . "',
			'" . $sAlamatTI2 . "',
			'" . $sKotaTI . "',
			'" . $sKodePOSTI . "',
			'" . $sTelepon . "',
			'" . $sFax . "',
			'" . $slatitude . "',
			'" . $slongitude . "',	
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Lokasi Tempat Ibadah Data";


		// Existing Baptis (update)
		} else {
	//update the Baptis table
			$sSQL = "UPDATE LokasiTI SET 
		NamaTI = '" . $sNamaTI . "',
		AlamatTI1 = '" . $sAlamatTI1 . "',
		AlamatTI2 = '" . $sAlamatTI2 . "',
		KotaTI = '" . $sKotaTI . "',
		KodePOSTI = '" . $sKodePOSTI . "',
		Telepon = '" . $sTelepon . "',
		Fax = '" . $sFax  . "',
		latitude = '" . $slatitude . "',
		longitude = '" . $slongitude . "',
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE KodeTI = " . $iKodeTI;

		//	echo $sSQL;
	

			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Data Gereja";
		}

		//Execute the SQL
		RunQuery($sSQL);
		

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iKodeTI . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. DaftarTempatIbadahEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iKodeTI);
		}
		else if (isset($_POST["GerejaSubmit"]))
		{
			//Send to the view of this PendetaData
			Redirect("SelectListApp2.php?mode=DaftarTempatIbadah&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("DaftarTempatIbadahEditor.php&amp;$refresh");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iKodeTI) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM LokasiTI  WHERE KodeTI = " . $iKodeTI;
		$rsGereja = RunQuery($sSQL);
		extract(mysql_fetch_array($rsGereja));
				$sKodeTI = $KodeTI; 	 	
				$sNamaTI = $NamaTI; 		
				$sAlamatTI1 = $AlamatTI1; 	 	
				$sAlamatTI2 = $AlamatTI2; 	 	
				$sKotaTI = $KotaTI; 	 	
				$sKodePOSTI = $KodePOSTI; 	 	
				$sTelepon  = $Telepon; 		
				$sFax = $Fax; 	 
				$slatitude = $latitude; 	 	
				$slongitude = $longitude; 		
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today

	}
}


require "Include/Header.php";

?>

<form method="post" action="DaftarTempatIbadahEditor.php?KodeTI=<?php echo $iKodeTI; ?>" name="DaftarTempatIbadahEditor">

<table cellpadding="3" align="center" valign="top" >


			
	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="GerejaSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"GerejaSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iKodeTI) > 0) 
{ echo "SelectListApp2.php?mode=DaftarTempatIbadah&amp;$refresh"; 
} else {echo "SelectListApp2.php?mode=DaftarTempatIbadah&amp;$refresh"; 
} ?>';">
		</td>
	</tr>

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"4\""; ?> align="center">
		<?php if ( $bErrorFlag ) echo "<span class=\"LargeText\" style=\"color: red;\">" . gettext("Ada keSALAHan pengisian atau pilihan. Perubahan tidak akan disimpan! Silahkan diKOREKSI dan dicoba lagi!") . "</span>"; ?>
		</td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" valign="top" border="0" >
			<tr>
				<td colspan="4" align="center"><h3><?php echo gettext("Data Standar"); ?></h3></td>
			</tr>

	<tr>
		<td class="LabelColumn"><?php echo gettext("Nama Tempat Ibadah"); ?></td>
		<td class="TextColumn"><input type="text" name="NamaTI" size=80  id="NamaTI" value="<?php echo htmlentities(stripslashes($sNamaTI),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sNamaTIError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Alamat1"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="AlamatTI1" size=80 id="AlamatTI1" value="<?php echo htmlentities(stripslashes($sAlamatTI1),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sAlamatTI1Error ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Alamat2"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="AlamatTI2" size=80 id="AlamatTI2" value="<?php echo htmlentities(stripslashes($sAlamatTI2),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sAlamatTI2Error ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Kota"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="KotaTI" size=80 id="KotaTI" value="<?php echo htmlentities(stripslashes($sKotaTI),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sKotaTIError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn" ><?php echo gettext("KodePOSTI"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="KodePOSTI" size=80 id="KodePOSTI" value="<?php echo htmlentities(stripslashes($sKodePOSTI),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sKodePOSTIError ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Telepon"); ?></td>
		<td class="TextColumn"><input type="text" name="Telepon" id="Telepon" value="<?php echo htmlentities(stripslashes($sTelepon),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sTeleponError ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Fax"); ?></td>
		<td class="TextColumn"><input type="text" name="Fax" id="Fax" value="<?php echo htmlentities(stripslashes($sFax),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sFaxError ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("latitude"); ?></td>
		<td class="TextColumn"><input type="text" name="latitude" size=80  id="latitude" value="<?php echo htmlentities(stripslashes($slatitude),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $slatitudeError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("longitude"); ?></td>
		<td class="TextColumn"><input type="text" name="longitude" size=80  id="longitude" value="<?php echo htmlentities(stripslashes($slongitude),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $slongitudeError ?></font></td>
	</tr>		
	</table>
</td>


	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr>

	</tr>

	</form>

</table>

<?php
		$logvar1 = "Edit";
		$logvar2 = "Daftar Tempat Ibadah Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iKodeTI . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
?>
