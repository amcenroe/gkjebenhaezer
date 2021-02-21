<?php
/*******************************************************************************
 *
 *  filename    : KlasifikasiSuratEditor.php
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
$sPageTitle = gettext("Daftar Master Klasifikasi Surat");

//Get the KlasID out of the querystring
$iKlasID = FilterInput($_GET["KlasID"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?KlasID= manually)
if (strlen($iKlasID) > 0)
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

if (isset($_POST["KlasSuratSubmit"]) || isset($_POST["KlasSuratSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sKlasID = FilterInput($_POST["KlasID"]);
	$sEnable = FilterInput($_POST["Enable"]);
	$sDeskripsi = FilterInput($_POST["Deskripsi"]);
	$sKeterangan = FilterInput($_POST["Keterangan"]);
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
		if (strlen($iKlasID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

				
			$sSQL = "INSERT INTO KlasifikasiSurat ( 
			KlasID,
			Enable,
			Deskripsi,
			Keterangan,
			DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sKlasID . "',			
			'" . $sEnable . "',
			'" . $sDeskripsi . "',
			'" . $sKeterangan . "',
			
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Klasifikasi Surat Data";


		// Existing Baptis (update)
		} else {
	//update the Baptis table
			$sSQL = "UPDATE KlasifikasiSurat SET 
			
	KlasID = '" . $sKlasID . "',
	Enable = '" . $sEnable . "',
	Deskripsi = '" . $sDeskripsi . "',
	Keterangan = '" . $sKeterangan . "',
	
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE KlasID = " . $iKlasID;

		//	echo $sSQL;
	

			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Data Klasifikasi Surat";
		}

		//Execute the SQL
		RunQuery($sSQL);
		

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. KlasifikasiSuratEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iKlasID);
		}
		else if (isset($_POST["KlasSuratSubmit"]))
		{
			//Send to the view of this PendetaData
			Redirect("SelectListApp.php?mode=klassurat&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("KlasifikasiSuratEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iKlasID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM KlasifikasiSurat  WHERE KlasID = " . $iKlasID;
		$rsGereja = RunQuery($sSQL);
		extract(mysql_fetch_array($rsGereja));
		
		$sKlasID = $KlasID;
		$sEnable = $Enable;
		$sDeskripsi = $Deskripsi;
		$sKeterangan = $Keterangan;
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

<form method="post" action="KlasifikasiSuratEditor.php?KlasID=<?php echo $iKlasID; ?>" name="KlasifikasiSuratEditor">

<table cellpadding="3" align="center" valign="top" >


			
	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="KlasSuratSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"KlasSuratSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iKlasID) > 0) 
{ echo "SelectListApp.php?mode=klassurat&amp;$refresh"; 
} else {echo "SelectListApp.php?mode=klassurat&amp;$refresh"; 
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
		<td class="LabelColumn"><?php echo gettext("Kode Klasifikasi"); ?></td>
		<td class="TextColumn"><input type="text" name="KlasID" size=80  id="KlasID" value="<?php echo htmlentities(stripslashes($sKlasID),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sNamaGerejaError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Aktif ?"); ?></td>
		<td class="TextColumn" colspan="3">
			<select name="Enable">
				<option value="0"><?php echo gettext("Tidak Aktif"); ?></option>
				<option value="1" <?php if ($sEnable == 1) { echo "selected"; } ?>><?php echo gettext("Aktif"); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Deskripsi"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="Deskripsi" size=80 id="Deskripsi" value="<?php echo htmlentities(stripslashes($sDeskripsi),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sDeskripsiError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan"); ?></td>
		<td class="TextColumn"><input type="text" name="Keterangan" size=80  id="Keterangan" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sKeteranganError ?></font></td>
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
		$logvar2 = "Daftar Klasifikasi Surat Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
?>
