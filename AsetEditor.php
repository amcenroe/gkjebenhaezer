<?php
/*******************************************************************************
 *
 *  filename    : AsetEditor.php
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
$sPageTitle = gettext("Editor - Aset GKJ Bekti");

//Get the AssetID out of the querystring
$iAssetID = FilterInput($_GET["AssetID"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?AssetID= manually)
if (strlen($iAssetID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsaset = RunQuery($sSQL);
	extract(mysql_fetch_array($rsaset));

	if (mysql_num_rows($rsaset) == 0)
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

if (isset($_POST["AsetSubmit"]) || isset($_POST["AsetSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	

	$sAssetID = FilterInput($_POST["AssetID"]);
	$sAssetClass = FilterInput($_POST["AssetClass"]);
	$sTahun = FilterInput($_POST["Tahun"]);
	$sMerk = FilterInput($_POST["Merk"]);
	$sType = FilterInput($_POST["Type"]);
	$sSpesification = FilterInput($_POST["Spesification"]);
	$sQuantity = FilterInput($_POST["Quantity"]);
	$sUnitOfMasure = FilterInput($_POST["UnitOfMasure"]); 
	$sValue = FilterInput($_POST["Value"]); 
	$sStatus = FilterInput($_POST["Status"]); 
	$sDescription = FilterInput($_POST["Description"]); 
	$sLocation = FilterInput($_POST["Location"]); 
	$sStorageCode = FilterInput($_POST["StorageCode"]); 
	$sRack = FilterInput($_POST["Rack"]); 
	$sBin = FilterInput($_POST["Bin"]); 
	$sEnteredBy = FilterInput($_POST["EnteredBy"]); 
	$sEditedBy = FilterInput($_POST["EditedBy"]); 
	$sDateEntered = FilterInput($_POST["DateEntered"]); 
	$sDateLastEdited = FilterInput($_POST["DateLastEdited"]); 
	
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
		if (strlen($iAssetID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO asetgkjbekti ( 
					AssetID, 
					AssetClass, 
					Tahun,
					Merk, 
					Type, 
					Spesification, 
					Quantity, 
					UnitOfMasure, 
					Value, 
					Status, 
					Description, 
					Location, 
					StorageCode, 
					Rack, 
					Bin, 
					DateEntered,
					EnteredBy
			)
			VALUES ( 
			'" . $sAssetID . "',
			'" . $sAssetClass . "',
			'" . $sTahun . "',
			'" . $sMerk . "',
			'" . $sType . "',
			'" . $sSpesification . "',
			'" . $sQuantity . "',
			'" . $sUnitOfMasure . "',
			'" . $sValue . "',
			'" . $sStatus . "',
			'" . $sDescription . "',
			'" . $sLocation . "',
			'" . $sStorageCode . "',
			'" . $sRack . "',
			'" . $sBin . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New Aset Data";


		// Existing Aset (update)
		} else {
	
			$sSQL = "UPDATE asetgkjbekti SET 
	
			AssetClass = '" . $sAssetClass . "',
			Tahun = '" . $sTahun . "',
			Merk = '" . $sMerk . "',
			Type = '" . $sType . "',
			Spesification = '" . $sSpesification . "',
			Quantity = '" . $sQuantity . "',
			UnitOfMasure = '" . $sUnitOfMasure . "',
			Value = '" . $sValue . "',
			Status = '" . $sStatus . "',
			Description = '" . $sDescription . "',
			Location = '" . $sLocation . "',
			StorageCode = '" . $sStorageCode . "',
			Rack = '" . $sRack . "',
			Bin = '" . $sBin . "',
			DateLastEdited = '" . date("YmdHis") . "', 
			EditedBy = '" . $_SESSION['iUserID'] ;
					
			$sSQL .= "' WHERE AssetID = " . $iAssetID;

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Aset Data";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iAssetID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. AsetEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iAssetID);
		}
		else if (isset($_POST["AsetSubmit"]))
		{
			//Send to the view of this Aset
			Redirect("SelectList.php?mode=aset&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("AsetEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iAssetID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM asetgkjbekti  WHERE AssetID = " . $iAssetID;
		$rsaset = RunQuery($sSQL);
		extract(mysql_fetch_array($rsaset));

		$sAssetID = $AssetID;
		$sAssetClass = $AssetClass;
		$sTahun = $Tahun;
		$sMerk = $Merk;
		$sType = $Type;
		$sSpesification = $Spesification;
		$sQuantity = $Quantity;
		$sUnitOfMasure = $UnitOfMasure;
		$sValue = $Value;
		$sStatus = $Status;
		$sDescription = $Description;
		$sLocation = $Location;
		$sStorageCode = $StorageCode;
		$sRack = $Rack;
		$sBin = $Bin;
		$sDateEntered = $DateEntered;
		$sEnteredBy = $EnteredBy;
		$sDateLastEdited = $DateLastEdited;
		$sEditedBy = $EditedBy;
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
	}
}

//Get Aset Class Names for the drop-down
$sSQL = "SELECT * FROM asetklasifikasi ORDER BY majorclass, minorclass";
$rsKlasAset = RunQuery($sSQL);

//Get Church Names for the drop-down
$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
$rsLokasiTI = RunQuery($sSQL);

//Get Ruangan Names for the drop-down
$sSQL = "SELECT * FROM asetruangan ORDER BY StorageCode";
$rsStorageCode = RunQuery($sSQL);

//Get Ruangan Names for the drop-down
$sSQL = "SELECT * FROM asetstatus ORDER BY StatusCode";
$rsStatusCode = RunQuery($sSQL);

require "Include/Header.php";

?>

<form method="post" action="AsetEditor.php?AssetID=<?php echo $iAssetID; ?>" name="AsetEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="AsetSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"AsetSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="asetCancel" onclick="javascript:document.location='<?php if (strlen($iAssetID) > 0) 
{ echo "AsetView.php?AssetID=" . $iAssetID."&amp;$refresh"; 
} else {echo "SelectList.php?mode=aset&amp;$refresh"; 
} ?>';">
		</td>
	</tr>

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
		<?php if ( $bErrorFlag ) echo "<span class=\"LargeText\" style=\"color: red;\">" . gettext("Ada keSALAHan pengisian atau pilihan. Perubahan tidak akan disimpan! Silahkan diKOREKSI dan dicoba lagi!") . "</span>"; ?>
		</td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" valign="top" >
			<tr>
				<td colspan="2" align="center"><h3><?php echo gettext("Data Aset Standar"); ?></h3></td>
			</tr>
		
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Klasifikasi:"); ?></td>
				<td class="TextColumn">
					<select name="AssetClass">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsKlasAset))
						{
							extract($aRow);

							echo "<option value=\"" . $classID . "\"";
							if ($sAssetClass == $classID) { echo " selected"; }
							echo ">" . $minorclass . "&nbsp; - " . $majorclass ;
						}
						?>

					</select>
				</td>
			</tr>
	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Merk:"); ?></td>
		<td class="TextColumn"><input type="text" name="Merk" id="Merk" value="<?php echo htmlentities(stripslashes($sMerk),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sMerkError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Type / Nomor Produk:"); ?></td>
		<td class="TextColumn"><input type="text" name="Type" id="Type" value="<?php echo htmlentities(stripslashes($sType),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTypeError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Spesifikasi:"); ?></td>
		<td class="TextColumn"><input type="text" name="Spesification" id="Spesification" value="<?php echo htmlentities(stripslashes($sSpesification),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sSpesificationError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Jumlah:"); ?></td>
		<td class="TextColumn"><input type="text" name="Quantity" id="Quantity" value="<?php echo htmlentities(stripslashes($sQuantity),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sQuantityError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Unit/Satuan:"); ?></td>
		<td class="TextColumn"><input type="text" name="UnitOfMasure" id="UnitOfMasure" value="<?php echo htmlentities(stripslashes($sUnitOfMasure),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sUnitOfMasureError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nilai (Rp):"); ?></td>
		<td class="TextColumn"><input type="text" name="Value" id="Value" value="<?php echo htmlentities(stripslashes($sValue),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sValueError ?></font></td>
	</tr>	

	<tr>
			<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Status Aset:"); ?></td>
				<td class="TextColumn">
					<select name="Status">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsStatusCode))
						{
							extract($aRow);

							echo "<option value=\"" . $StatusCode . "\"";
							if ($sStatus == $StatusCode) { echo " selected"; }
							echo ">" . $StatusName ;
						}
						?>

					</select>
				</td>
			</tr>

	<tr>
	<td></td><td></td>
	</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Tempat Ibadah:"); ?></td>
				<td class="TextColumn">
					<select name="Location">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsLokasiTI))
						{
							extract($aRow);

							echo "<option value=\"" . $KodeTI . "\"";
							if ($sLocation == $KodeTI) { echo " selected"; }
							echo ">" . $NamaTI ;
						}
						?>

					</select>
				</td>

				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Letak / Kode Gudang:"); ?></td>
				<td class="TextColumn">
					<select name="StorageCode">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsStorageCode))
						{
							extract($aRow);

							echo "<option value=\"" . $StorageCode . "\"";
							if ($sStorageCode == $StorageCode) { echo " selected"; }
							echo ">" . $StorageDesc ;
						}
						?>

					</select>
				</td>
			</tr>				
	<tr>
		<td class="LabelColumn"><?php echo gettext("Rak :"); ?></td>
		<td class="TextColumn"><input type="text" name="Rack" id="Rack" value="<?php echo htmlentities(stripslashes($sRack),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sRackError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Bin :"); ?></td>
		<td class="TextColumn"><input type="text" name="Bin" id="Bin" value="<?php echo htmlentities(stripslashes($sBin),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sBinError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Perolehan:"); ?></td>
		<td class="TextColumn"><input type="text" name="Tahun" value="<?php echo $sTahun; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
		</td><td><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTahun ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan Tambahan:"); ?></td>
		<td class="TextColumn"><input type="text" name="Description" id="Description" value="<?php echo htmlentities(stripslashes($sDescription),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sDescriptionError ?></font></td>
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
		$logvar2 = "PAK Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iAssetID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
