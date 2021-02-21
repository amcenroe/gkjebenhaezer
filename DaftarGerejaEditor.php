<?php
/*******************************************************************************
 *
 *  filename    : DaftarGerejaEditor.php
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

//Get the GerejaID out of the querystring
$iGerejaID = FilterInput($_GET["GerejaID"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?GerejaID= manually)
if (strlen($iGerejaID) > 0)
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
	$sGerejaID = FilterInput($_POST["GerejaID"]);
	$sKlasisID = FilterInput($_POST["KlasisID"]);
	$sNamaGereja = FilterInput($_POST["NamaGereja"]);
	$sKeterangan = FilterInput($_POST["Keterangan"]);
	$sAlamat1 = FilterInput($_POST["Alamat1"]);
	$sAlamat2 = FilterInput($_POST["Alamat2"]);
	$sAlamat3 = FilterInput($_POST["Alamat3"]);
	$sTelp = FilterInput($_POST["Telp"]);
	$sFax = FilterInput($_POST["Fax"]);
	$sEmail = FilterInput($_POST["Email"]);

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
		if (strlen($iGerejaID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO DaftarGerejaGKJ ( 		
			KlasisID,
			NamaGereja,
			Keterangan,
			Alamat1,
			Alamat2,
			Alamat3,
			Telp,
			Fax,
			Email,
			
			DateEntered,
			EnteredBy	)
			VALUES ( 
						
			'" . $sKlasisID . "',
			'" . $sNamaGereja . "',
			'" . $sKeterangan . "',
			'" . $sAlamat1 . "',
			'" . $sAlamat2 . "',
			'" . $sAlamat3 . "',
			'" . $sTelp . "',
			'" . $sFax . "',
			'" . $sEmail . "',
			
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Gereja Data";


		// Existing Baptis (update)
		} else {
	//update the Baptis table
			$sSQL = "UPDATE DaftarGerejaGKJ SET 

	KlasisID = '" . $sKlasisID . "',
	NamaGereja = '" . $sNamaGereja . "',
	Keterangan = '" . $sKeterangan . "',
	Alamat1 = '" . $sAlamat1 . "',
	Alamat2 = '" . $sAlamat2 . "',
	Alamat3 = '" . $sAlamat3 . "',
	Telp = '" . $sTelp . "',
	Fax = '" . $sFax . "',
	Email = '" . $sEmail . "',
	
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE GerejaID = " . $iGerejaID;

		//	echo $sSQL;
	

			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Data Gereja";
		}

		//Execute the SQL
		RunQuery($sSQL);
		

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iGerejaID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. DaftarGerejaEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iGerejaID);
		}
		else if (isset($_POST["GerejaSubmit"]))
		{
			//Send to the view of this PendetaData
			Redirect("SelectListApp.php?mode=DaftarGereja");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("DaftarGerejaEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iGerejaID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM DaftarGerejaGKJ  WHERE GerejaID = " . $iGerejaID;
		$rsGereja = RunQuery($sSQL);
		extract(mysql_fetch_array($rsGereja));
		
		$sGerejaID = $GerejaID;
		$sKlasisID = $KlasisID;
		$sNamaGereja = $NamaGereja;
		$sKeterangan = $Keterangan;
		$sAlamat1 = $Alamat1;
		$sAlamat2 = $Alamat2;
		$sAlamat3 = $Alamat3;
		$sTelp = $Telp;
		$sFax = $Fax;
		$sEmail = $Email;
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today

	}
}


//Get Pendeta Names for the drop-down
$sSQL = "SELECT * FROM DaftarKlasisGKJ ORDER BY KlasisID";
$rsNamaKlasis = RunQuery($sSQL);


require "Include/Header.php";

?>

<form method="post" action="DaftarGerejaEditor.php?GerejaID=<?php echo $iGerejaID; ?>" name="DaftarGerejaEditor">

<table cellpadding="3" align="center" valign="top" >


			
	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="GerejaSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"GerejaSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iGerejaID) > 0) 
{ echo "SelectListApp.php?mode=DaftarGereja"; 
} else {echo "SelectListApp.php?mode=DaftarGereja"; 
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
		<td class="LabelColumn"><?php echo gettext("KlasisID :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="KlasisID" >
						<option value="" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaKlasis))
						{
							extract($aRow);

							echo "<option value=\"" . $KlasisID . "\"";
							if ($sKlasisID == $KlasisID) { echo " selected"; }
							echo ">" . $NamaKlasis;
						}
						?>
		</td>
	<tr>
		<td class="LabelColumn"><?php echo gettext("NamaGereja"); ?></td>
		<td class="TextColumn"><input type="text" name="NamaGereja" size=80  id="NamaGereja" value="<?php echo htmlentities(stripslashes($sNamaGereja),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sNamaGerejaError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Alamat1"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="Alamat1" size=80 id="Alamat1" value="<?php echo htmlentities(stripslashes($sAlamat1),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sAlamat1Error ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Alamat2"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="Alamat2" size=80 id="Alamat2" value="<?php echo htmlentities(stripslashes($sAlamat2),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sAlamat2Error ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Alamat3"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="Alamat3" size=80 id="Alamat3" value="<?php echo htmlentities(stripslashes($sAlamat3),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sAlamat3Error ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Telp"); ?></td>
		<td class="TextColumn"><input type="text" name="Telp" id="Telp" value="<?php echo htmlentities(stripslashes($sTelp),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sTelpError ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Fax"); ?></td>
		<td class="TextColumn"><input type="text" name="Fax" id="Fax" value="<?php echo htmlentities(stripslashes($sFax),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sFaxError ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Email"); ?></td>
		<td class="TextColumn"><input type="text" name="Email" size=80  id="Email" value="<?php echo htmlentities(stripslashes($sEmail),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sEmailError ?></font></td>
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
		$logvar2 = "Daftar Gereja Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iGerejaID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
?>
