<?php
/*******************************************************************************
 *
 *  filename    : BacaanAlkitabEditor.php
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
$sPageTitle = gettext("Daftar Bacaan Alkitab");

//Get the BacaanAlkitabID out of the querystring
$iBacaanAlkitabID = FilterInput($_GET["BacaanAlkitabID"],'int');


$iTGL = FilterInput($_GET["TGL"]);
$iPKL = FilterInput($_GET["PKL"]);
$iKodeTI = FilterInput($_GET["KodeTI"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?BacaanAlkitabID= manually)
if (strlen($iBacaanAlkitabID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsBaptis = RunQuery($sSQL);
	extract(mysql_fetch_array($rsBaptis));

	if (mysql_num_rows($rsBaptis) == 0)
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

if (isset($_POST["BacaanSubmit"]) || isset($_POST["BacaanSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	

	$sBacaanAlkitabID = FilterInput($_POST["BacaanAlkitabID"]);
	$sTanggal = FilterInput($_POST["Tanggal"]);
	$sWaktu = FilterInput($_POST["Waktu"]);
	$sBacaanI = FilterInput($_POST["BacaanI"]);
	$sMazmur = FilterInput($_POST["Mazmur"]);
	$sBacaanII = FilterInput($_POST["BacaanII"]);
	$sInjil  = FilterInput($_POST["Injil"]);

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
		if (strlen($iBacaanAlkitabID) < 1)
		{
			 	
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO BacaanAlkitab ( 		
			Tanggal,
			BacaanI,
			Mazmur,
			BacaanII,
			Injil,
			DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sTanggal . "',	
			'" . $sBacaanI . "',	
			'" . $sMazmur . "',	
			'" . $sBacaanII . "',	
			'" . $sInjil . "',	

			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
		
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Daftar Bacaan Alkitab";


		// Existing Baptis (update)
		} else {
			 
	//update the Baptis table
			
			$sSQL = "UPDATE BacaanAlkitab SET 
			Tanggal = '" . $sTanggal . "',
			BacaanI = '" . $sBacaanI . "',
			Mazmur = '" . $sMazmur . "',
			BacaanII = '" . $sBacaanII . "',
			Injil = '" . $sInjil . "',
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE BacaanAlkitabID = " . $iBacaanAlkitabID;

		//	echo $sSQL;
	
			$sSQL2 = "";
			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Daftar Bacaan Alkitab";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		if($sSQL2 ==""){ echo "";}else{	RunQuery($sSQL2);}
		
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iBacaanAlkitabID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. BacaanAlkitabEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iBacaanAlkitabID);
		}
		else if (isset($_POST["BacaanSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectListApp3.php?mode=BacaanAlkitab&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("BacaanAlkitabEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iBacaanAlkitabID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM BacaanAlkitab  WHERE BacaanAlkitabID = " . $iBacaanAlkitabID;
		$rsBaptis = RunQuery($sSQL);
		extract(mysql_fetch_array($rsBaptis));
		
		$sBacaanAlkitabID = $BacaanAlkitabID;
		$sTanggal = $Tanggal;
		$sBacaanI = $BacaanI;
		$sMazmur = $Mazmur;
		$sBacaanII = $BacaanII;
		$sInjil = $Injil;
		
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
		
		//Date from source
		if (strlen($iTGL) AND strlen($iPKL) AND strlen($iKodeTI) ){
		echo $iTGL,$iPKL,$iKodeTI;
		$sTanggal=$iTGL;
		$sWaktu=$iPKL;
		$sKodeTI=$iKodeTI;
	}

	}
}


require "Include/Header.php";


$iTGL = FilterInput($_GET["TGL"]);
$iPKL = FilterInput($_GET["PKL"]);
$iKodeTI = FilterInput($_GET["KodeTI"],'int');

?>

<form method="post" action="BacaanAlkitabEditor.php?BacaanAlkitabID=<?php echo $iBacaanAlkitabID; ?>" name="BacaanAkitabEditor">

<table cellpadding="3" align="center" valign="top" border="0">

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="BacaanSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"BacaanSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iBacaanAlkitabID) > 0) 
{ echo "SelectListApp3.php?mode=BacaanAlkitab&amp;$refresh"; 
} else {echo "SelectListApp3.php?mode=BacaanAlkitab&amp;$refresh"; 
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
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Bacaan:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Bacaan I:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=50 name="BacaanI" id="BacaanI" value="<?php echo htmlentities(stripslashes($sBacaanI),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sBacaanIError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Mazmur:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=50 name="Mazmur" id="Mazmur" value="<?php echo htmlentities(stripslashes($sMazmur),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sMazmurError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Bacaan II:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=50 name="BacaanII" id="BacaanII" value="<?php echo htmlentities(stripslashes($sBacaanII),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sBacaanIIError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Injil:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=50 name="Injil" id="Injil" value="<?php echo htmlentities(stripslashes($sInjil),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sInjilError ?></font></td>
	</tr>	
	

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
		$logvar2 = "Pelayanan Firman Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iBacaanAlkitabID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
