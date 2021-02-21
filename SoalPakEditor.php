<?php
/*******************************************************************************
 *
 *  filename    : SoalPakEditor.php
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
$sPageTitle = gettext("Editor - Soal Pendidikan Agama Kristen");

//Get the SoalPAKID out of the querystring
$iSoalPAKID = FilterInput($_GET["SoalPAKID"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?SoalPAKID= manually)
if (strlen($iSoalPAKID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rspak = RunQuery($sSQL);
	extract(mysql_fetch_array($rspak));

	if (mysql_num_rows($rspak) == 0)
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

if (isset($_POST["SoalPakSubmit"]) || isset($_POST["SoalPakSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
				$sSoalPAKID = FilterInput($_POST["SoalPAKID"]);
				$sEnable = FilterInput($_POST["Enable"]);
				$sKelasID = FilterInput($_POST["KelasID"]);
				$sSemesterID = FilterInput($_POST["SemesterID"]);
				$sSoal = FilterInput($_POST["Soal"]);
				$sOpsiSoal = FilterInput($_POST["OpsiSoal"]);
				$sBobotSoal = FilterInput($_POST["BobotSoal"]);
				$sKeterangan = FilterInput($_POST["Keterangan"]);

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
		if (strlen($iSoalPAKID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO MasterSoalPak ( 
			SoalPAKID,
			Enable,
			KelasID,
			SemesterID,
			Soal,
			OpsiSoal,
			BobotSoal,
			Keterangan,
			DateEntered, 
			EnteredBy )
			VALUES ( 
			'" . $sSoalPAKID . "',
			'" . $sEnable . "',
			'" . $sKelasID . "',
			'" . $sSemesterID . "',
			'" . $sSoal . "',
			'" . $sOpsiSoal . "',
			'" . $sBobotSoal . "',
			'" . $sKeterangan . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New Soal PAK Data";


		// Existing PAK (update)
		} else {
	
			$sSQL = "UPDATE MasterSoalPak SET 
			
			SoalPAKID = '" . $sSoalPAKID . "',
			Enable = '" . $sEnable . "',
			KelasID = '" . $sKelasID . "',
			SemesterID = '" . $sSemesterID . "',
			Soal = '" . $sSoal . "',
			OpsiSoal = '" . $sOpsiSoal . "',
			BobotSoal = '" . $sBobotSoal . "',
			Keterangan = '" . $sKeterangan . "',
			
			DateLastEdited = '" . date("YmdHis") . "', 
			EditedBy = '" . $_SESSION['iUserID'] ;
					
			$sSQL .= "' WHERE SoalPAKID = " . $iSoalPAKID;

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Soal PAK Data";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iSoalPAKID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. SoalPakEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iSoalPAKID);
		}
		else if (isset($_POST["SoalPakSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectList.php?mode=soalpak&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("SoalPakEditor.php&amp;$refresh");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iSoalPAKID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM MasterSoalPak  WHERE SoalPAKID = " . $iSoalPAKID;
		$rspak = RunQuery($sSQL);
		extract(mysql_fetch_array($rspak));
		
		$sSoalPAKID = $SoalPAKID;
		$sEnable = $Enable;
		$sKelasID = $KelasID;
		$sSemesterID = $SemesterID;
		$sSoal = $Soal;
		$sOpsiSoal = $OpsiSoal;
		$sBobotSoal = $BobotSoal;
		$sKeterangan = $Keterangan;

	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
	}
}

//Get Student Names for the drop-down
$sSQL = "SELECT * FROM person_per a JOIN family_fam b ON a.per_fam_ID=b.fam_ID WHERE (per_cls_ID <3 AND per_fmr_ID >2 ) ORDER BY per_firstname";
$rsNamamurid = RunQuery($sSQL);

//Get Tutor Names for the drop-down
$sSQL = "SELECT * FROM paktutor ORDER BY TutorID";
$rsNamaTutor = RunQuery($sSQL);

require "Include/Header.php";

?>

<form method="post" action="SoalPakEditor.php?SoalPAKID=<?php echo $iSoalPAKID; ?>" name="SoalPakEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="SoalPakSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"SoalPakSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iSoalPAKID) > 0) 
{ echo "SelectList.php?mode=soalpak&amp;$refresh"; 
} else {echo "SelectList.php?mode=soalpak&amp;$refresh"; 
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
				<td colspan="2" align="center"><h3><?php echo gettext("Data Standar"); ?></h3></td>
			</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kelas :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="KelasID">
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				<option value="1" <?php if ($sKelasID == 1) { echo "selected"; } ?>><?php echo gettext("1 - SD"); ?></option>
				<option value="2" <?php if ($sKelasID == 2) { echo "selected"; } ?>><?php echo gettext("2 - SD"); ?></option>
				<option value="3" <?php if ($sKelasID == 3) { echo "selected"; } ?>><?php echo gettext("3 - SD"); ?></option>
				<option value="4" <?php if ($sKelasID == 4) { echo "selected"; } ?>><?php echo gettext("4 - SD"); ?></option>
				<option value="5" <?php if ($sKelasID == 5) { echo "selected"; } ?>><?php echo gettext("5 - SD"); ?></option>
				<option value="6" <?php if ($sKelasID == 6) { echo "selected"; } ?>><?php echo gettext("6 - SD"); ?></option>
				<option value="7" <?php if ($sKelasID == 7) { echo "selected"; } ?>><?php echo gettext("7 - SMP"); ?></option>
				<option value="8" <?php if ($sKelasID == 8) { echo "selected"; } ?>><?php echo gettext("8 - SMP"); ?></option>
				<option value="9" <?php if ($sKelasID == 9) { echo "selected"; } ?>><?php echo gettext("9 - SMP"); ?></option>
				<option value="10" <?php if ($sKelasID == 10) { echo "selected"; } ?>><?php echo gettext("10 - SMA"); ?></option>
				<option value="11" <?php if ($sKelasID == 11) { echo "selected"; } ?>><?php echo gettext("11 - SMA"); ?></option>
				<option value="12" <?php if ($sKelasID == 12) { echo "selected"; } ?>><?php echo gettext("12 - SMA"); ?></option>
			</select>
		</td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Semester :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="SemesterID">
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				<option value="1" <?php if ($sSemesterID == 1) { echo "selected"; } ?>><?php echo gettext("Ulangan Semester Ganjil"); ?></option>
				<option value="2" <?php if ($sSemesterID == 2) { echo "selected"; } ?>><?php echo gettext("Ulangan Semester Genap"); ?></option>
				<option value="3" <?php if ($sSemesterID == 3) { echo "selected"; } ?>><?php echo gettext("Ulangan Mid Semester Ganjil"); ?></option>
				<option value="4" <?php if ($sSemesterID == 4) { echo "selected"; } ?>><?php echo gettext("Ulangan Mid Semester Genap"); ?></option>
				<option value="5" <?php if ($sSemesterID == 5) { echo "selected"; } ?>><?php echo gettext("Ulangan Umum Kenaikan Kelas"); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Type Soal :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="OpsiSoal">
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				<option value="1" <?php if ($sOpsiSoal == 1) { echo "selected"; } ?>><?php echo gettext("Pilihan BENAR - SALAH"); ?></option>
				<option value="2" <?php if ($sOpsiSoal == 2) { echo "selected"; } ?>><?php echo gettext("Pilihan Ganda ABCD dengan 1 jawaban BENAR"); ?></option>
				<option value="3" <?php if ($sOpsiSoal == 3) { echo "selected"; } ?>><?php echo gettext("Pilihan Ganda ABCD dengan 2 jawaban BENAR"); ?></option>
				<option value="4" <?php if ($sOpsiSoal == 4) { echo "selected"; } ?>><?php echo gettext("Uraian - Essay"); ?></option>
			</select>
		</td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Bobot Soal :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="BobotSoal">
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				<option value="1" <?php if ($sBobotSoal == 1) { echo "selected"; } ?>><?php echo gettext("Mudah"); ?></option>
				<option value="2" <?php if ($sBobotSoal == 2) { echo "selected"; } ?>><?php echo gettext("Agak Mudah"); ?></option>
				<option value="3" <?php if ($sBobotSoal == 3) { echo "selected"; } ?>><?php echo gettext("Rata rata"); ?></option>
				<option value="4" <?php if ($sBobotSoal == 4) { echo "selected"; } ?>><?php echo gettext("Agak Sukar"); ?></option>
				<option value="5" <?php if ($sBobotSoal == 5) { echo "selected"; } ?>><?php echo gettext("Sukar Sekali"); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Soal:"); ?></td>
		<td class="TextColumn"><input type="text" name="Soal" id="Soal" value="<?php echo htmlentities(stripslashes($sSoal),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sSoalError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan :"); ?></td>
		<td class="TextColumn"><input type="text" name="Keterangan" id="Keterangan" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKeteranganError ?></font></td>
	</tr>	
	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Jawaban1"); ?></td>
		<td class="TextColumn"><input type="text" name="Jawaban1" id="Jawaban1" value="<?php echo htmlentities(stripslashes($sJawaban1),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sJawaban1Error ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Jawaban2"); ?></td>
		<td class="TextColumn"><input type="text" name="Jawaban2" id="Jawaban2" value="<?php echo htmlentities(stripslashes($sJawaban2),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sJawaban2Error ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Jawaban3"); ?></td>
		<td class="TextColumn"><input type="text" name="Jawaban3" id="Jawaban3" value="<?php echo htmlentities(stripslashes($sJawaban3),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sJawaban3Error ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Jawaban4"); ?></td>
		<td class="TextColumn"><input type="text" name="Jawaban4" id="Jawaban4" value="<?php echo htmlentities(stripslashes($sJawaban4),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sJawaban4Error ?></font></td>
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
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iSoalPAKID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
