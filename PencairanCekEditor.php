<?php
/*******************************************************************************
 *
 *  filename    : PencairanCekEditor.php
 *
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
$sPageTitle = gettext("Editor - PencairanCek GKJ Bekti");

//Get the PencairanCekID out of the querystring
$iPencairanCekID = FilterInput($_GET["PencairanCekID"],'int');
$ReffTanggal = $_GET["ReffTanggal"] ;
$ReffKodeTI = $_GET["ReffKodeTI"] ;
$ReffPukul = $_GET["ReffPukul"] ;

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PencairanCekID= manually)
if (strlen($iPencairanCekID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsPencairanCek = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPencairanCek));

	if (mysql_num_rows($rsPencairanCek) == 0)
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

if (isset($_POST["PencairanCekSubmit"]) || isset($_POST["PencairanCekSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	

			$sPencairanCekID = FilterInput($_POST["PencairanCekID"]); 
			$sTanggal = FilterInput($_POST["Tanggal"]); 
			$sNomorCek = FilterInput($_POST["NomorCek"]); 
			$sKeterangan = FilterInput($_POST["Keterangan"]); 
			$sJumlah = FilterInput($_POST["Jumlah"]);
			$sDetail1 = FilterInput($_POST["Detail1"]);
			$sDetail2 = FilterInput($_POST["Detail2"]);
			$sDetail3 = FilterInput($_POST["Detail3"]);
			$sDetail4 = FilterInput($_POST["Detail4"]);
			$sDetail5 = FilterInput($_POST["Detail5"]);
			$sDetail6 = FilterInput($_POST["Detail6"]);
			$sDetail7 = FilterInput($_POST["Detail7"]);
			$sDetail8 = FilterInput($_POST["Detail8"]);
			$sDetail9 = FilterInput($_POST["Detail9"]);
			$sDetail10 = FilterInput($_POST["Detail10"]);
			$sKet1 = FilterInput($_POST["Ket1"]);
			$sKet2 = FilterInput($_POST["Ket2"]);
			$sKet3 = FilterInput($_POST["Ket3"]);
			$sKet4 = FilterInput($_POST["Ket4"]);
			$sKet5 = FilterInput($_POST["Ket5"]);
			$sKet6 = FilterInput($_POST["Ket6"]);
			$sKet7 = FilterInput($_POST["Ket7"]);
			$sKet8 = FilterInput($_POST["Ket8"]);
			$sKet9 = FilterInput($_POST["Ket9"]);
			$sKet10 = FilterInput($_POST["Ket10"]);
			$sNilai1 = FilterInput($_POST["Nilai1"]);
			$sNilai2 = FilterInput($_POST["Nilai2"]);
			$sNilai3 = FilterInput($_POST["Nilai3"]);
			$sNilai4 = FilterInput($_POST["Nilai4"]);
			$sNilai5 = FilterInput($_POST["Nilai5"]);
			$sNilai6 = FilterInput($_POST["Nilai6"]);
			$sNilai7 = FilterInput($_POST["Nilai7"]);
			$sNilai8 = FilterInput($_POST["Nilai8"]);
			$sNilai9 = FilterInput($_POST["Nilai9"]);
			$sNilai10 = FilterInput($_POST["Nilai10"]);
			$sDateLastEdited = FilterInput($_POST["DateLastEdited"]); 
			$sDateEntered = FilterInput($_POST["DateEntered"]); 
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
								. gettext("Not a valid Date") . "</span>";
			$bErrorFlag = true;
		} else {
			$dTanggal = $dateString;
		}
	}

	//If no errors, then let's update...
	//			PencairanCekID 	Tanggal 	NomorCek 	Keterangan 	Jumlah 

		// New Data (add)
		if (strlen($iPencairanCekID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";
			$sJumlah = $sNilai1+$sNilai2+$sNilai3+$sNilai4+$sNilai5+$sNilai6+$sNilai7+$sNilai8+$sNilai9+$sNilai10;	
				
			$sSQL = "INSERT INTO PencairanCek ( 
			Tanggal,
			NomorCek,
			Keterangan,
			Jumlah,
			Detail1,
			Detail2,
			Detail3,
			Detail4,
			Detail5,
			Detail6,
			Detail7,
			Detail8,
			Detail9,
			Detail10,
			Ket1,
			Ket2,
			Ket3,
			Ket4,
			Ket5,
			Ket6,
			Ket7,
			Ket8,
			Ket9,
			Ket10,
			Nilai1,
			Nilai2,
			Nilai3,
			Nilai4,
			Nilai5,
			Nilai6,
			Nilai7,
			Nilai8,
			Nilai9,
			Nilai10,
			DateEntered,
			EnteredBy
			)
			VALUES ( 
			'" . $sTanggal . "',
			'" . $sNomorCek . "',
			'" . $sKeterangan . "',
			'" . $sJumlah . "',
			'" . $sDetail1 . "',
			'" . $sDetail2 . "',
			'" . $sDetail3 . "',
			'" . $sDetail4 . "',
			'" . $sDetail5 . "',
			'" . $sDetail6 . "',
			'" . $sDetail7 . "',
			'" . $sDetail8 . "',
			'" . $sDetail9 . "',
			'" . $sDetail10 . "',
			'" . $sKet1 . "',
			'" . $sKet2 . "',
			'" . $sKet3 . "',
			'" . $sKet4 . "',
			'" . $sKet5 . "',
			'" . $sKet6 . "',
			'" . $sKet7 . "',
			'" . $sKet8 . "',
			'" . $sKet9 . "',
			'" . $sKet10 . "',
			'" . $sNilai1 . "',
			'" . $sNilai2 . "',
			'" . $sNilai3 . "',
			'" . $sNilai4 . "',
			'" . $sNilai5 . "',
			'" . $sNilai6 . "',
			'" . $sNilai7 . "',
			'" . $sNilai8 . "',
			'" . $sNilai9 . "',
			'" . $sNilai10 . "',			
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New PencairanCek Data";

		// Existing PencairanCek (update)
		} else {
			$sJumlah = $sNilai1+$sNilai2+$sNilai3+$sNilai4+$sNilai5+$sNilai6+$sNilai7+$sNilai8+$sNilai9+$sNilai10;	
			$sSQL = "UPDATE PencairanCek SET 

					Tanggal = '" . $sTanggal . "',
					NomorCek = '" . $sNomorCek . "',
					Keterangan = '" . $sKeterangan . "',
					Jumlah = '" . $sJumlah . "',
					Detail1 = '" . $sDetail1 . "',
					Detail2 = '" . $sDetail2 . "',
					Detail3 = '" . $sDetail3 . "', 
					Detail4 = '" . $sDetail4 . "',
					Detail5 = '" . $sDetail5 . "',
					Detail6 = '" . $sDetail6 . "',
					Detail7 = '" . $sDetail7 . "',
					Detail8 = '" . $sDetail8 . "',
					Detail9 = '" . $sDetail9 . "',
					Detail10 = '" . $sDetail10 . "',
					Ket1 = '" . $sKet1 . "',
					Ket2 = '" . $sKet2 . "',
					Ket3 = '" . $sKet3 . "',
					Ket4 = '" . $sKet4 . "',
					Ket5 = '" . $sKet5 . "',
					Ket6 = '" . $sKet6 . "',
					Ket7 = '" . $sKet7 . "',
					Ket8 = '" . $sKet8 . "',
					Ket9 = '" . $sKet9 . "',
					Ket10 = '" . $sKet10 . "',
					Nilai1 = '" . $sNilai1 . "',
					Nilai2 = '" . $sNilai2 . "',
					Nilai3 = '" . $sNilai3 . "',
					Nilai4 = '" . $sNilai4 . "',
					Nilai5 = '" . $sNilai5 . "',
					Nilai6 = '" . $sNilai6 . "',
					Nilai7 = '" . $sNilai7 . "',
					Nilai8 = '" . $sNilai8 . "',
					Nilai9 = '" . $sNilai9 . "',
					Nilai10 = '" . $sNilai10 . "',
					DateLastEdited = '" . date("YmdHis") . "', 
					EditedBy = '" . $_SESSION['iUserID'] ;
					
			$sSQL .= "' WHERE PencairanCekID = " . $iPencairanCekID;

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update PencairanCek ";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPencairanCekID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PencairanCekEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPencairanCekID);
		}
		else if (isset($_POST["PencairanCekSubmit"]))
		{
			//Send to the view of this PencairanCek
			Redirect("SelectListApp3.php?mode=PencairanCek&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PencairanCekEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPencairanCekID) > 0)
	{
		//Editing....
		//Get all the data on this record

		
		// Get Data PencairanCek
		$sSQL = "SELECT * FROM PencairanCek  WHERE PencairanCekID = " . $iPencairanCekID;
		$rsPencairanCek = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPencairanCek));

			$sPencairanCekID = $PencairanCekID;
			$sTanggal = $Tanggal;
			$sNomorCek = $NomorCek;
			$sKeterangan = $Keterangan;
			$sJumlah = $Jumlah;
			$sDetail1 = $Detail1;
			$sDetail2 = $Detail2;
			$sDetail3 = $Detail3;
			$sDetail4 = $Detail4;
			$sDetail5 = $Detail5;
			$sDetail6 = $Detail6;
			$sDetail7 = $Detail7;
			$sDetail8 = $Detail8;
			$sDetail9 = $Detail9;
			$sDetail10 = $Detail10;
			$sKet1 = $Ket1;
			$sKet2 = $Ket2;
			$sKet3 = $Ket3;
			$sKet4 = $Ket4;
			$sKet5 = $Ket5;
			$sKet6 = $Ket6;
			$sKet7 = $Ket7;
			$sKet8 = $Ket8;
			$sKet9 = $Ket9;
			$sKet10 = $Ket10;
			$sNilai1 = $Nilai1;
			$sNilai2 = $Nilai2;
			$sNilai3 = $Nilai3;
			$sNilai4 = $Nilai4;
			$sNilai5 = $Nilai5;
			$sNilai6 = $Nilai6;
			$sNilai7 = $Nilai7;
			$sNilai8 = $Nilai8;
			$sNilai9 = $Nilai9;
			$sNilai10 = $Nilai10;

			$sDateLastEdited = $DateLastEdited;
			$sDateEntered = $DateEntered;
			$sEnteredBy = $EnteredBy;
			$sEditedBy = $EditedBy;


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

<script type="text/javascript"><!--
function updatesum() {
document.PencairanCekEditor.Nilai.value = (document.PencairanCekEditor.Nilai1.value -0) + (document.PencairanCekEditor.Nilai2.value -0) + (document.PencairanCekEditor.Nilai3.value -0) + (document.PencairanCekEditor.Nilai4.value -0) + (document.PencairanCekEditor.Nilai5.value -0) + (document.PencairanCekEditor.Nilai6.value -0) + (document.PencairanCekEditor.Nilai7.value -0) + (document.PencairanCekEditor.Nilai8.value -0) + (document.PencairanCekEditor.Nilai9.value -0) + (document.PencairanCekEditor.Nilai10.value -0) ;
}
//--></script>


<form method="post" action="PencairanCekEditor.php?PencairanCekID=<?php echo $iPencairanCekID; ?>" name="PencairanCekEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="PencairanCekSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"PencairanCekSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="PencairanCekCancel" onclick="javascript:document.location='<?php if (strlen($iPencairanCekID) > 0) 
{ //echo "PencairanCekView.php?PencairanCekID=" . $iPencairanCekID; 
echo "SelectListApp3.php?mode=PencairanCek&amp;$refresh";
} else {echo "SelectListApp3.php?mode=PencairanCek&amp;$refresh"; 
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
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Pencairan:"); ?></td>
				<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
				</td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nomor Cek :"); ?></td>
				<td colspan="4" class="TextColumn"><input    type="text" name="NomorCek" id="NomorCek" size="70" value="<?php echo htmlentities(stripslashes($sNomorCek),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNomorCekError ?></font></td>			
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Jumlah yang Dicairkan"); ?></td>
				<td colspan="4" class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Nilai" readonly style="border:0px;" id="Nilai" value="<?php echo htmlentities(stripslashes($sJumlah),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sJumlahError ?></font></td>
			</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Detail 1"); ?></td>
		<td class="TextColumn"><input type="text" name="Detail1" id="Detail1" size="40" value="<?php echo htmlentities(stripslashes($sDetail1),ENT_NOQUOTES, "UTF-8"); ?>">
		<td class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Nilai1" onChange="updatesum()" id="Nilai1"  value="<?php echo htmlentities(stripslashes($sNilai1),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sNilai1Error ?></font></td>
		<td class="TextColumn"><input type="text" name="Ket1" id="Ket1" size="40" value="<?php echo htmlentities(stripslashes($sKet1),ENT_NOQUOTES, "UTF-8"); ?>">
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Detail 2"); ?></td>
		<td class="TextColumn"><input type="text" name="Detail2" id="Detail2" size="40" value="<?php echo htmlentities(stripslashes($sDetail2),ENT_NOQUOTES, "UTF-8"); ?>">
		<td class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Nilai2" onChange="updatesum()"  id="Nilai2"  value="<?php echo htmlentities(stripslashes($sNilai2),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sNilai2Error ?></font></td>
		<td class="TextColumn"><input type="text" name="Ket2" id="Ket2" size="40" value="<?php echo htmlentities(stripslashes($sKet2),ENT_NOQUOTES, "UTF-8"); ?>">
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Detail 3"); ?></td>
		<td class="TextColumn"><input type="text" name="Detail3" id="Detail3" size="40" value="<?php echo htmlentities(stripslashes($sDetail3),ENT_NOQUOTES, "UTF-8"); ?>">
		<td class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Nilai3" onChange="updatesum()"  id="Nilai3"  value="<?php echo htmlentities(stripslashes($sNilai3),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sNilai3Error ?></font></td>
		<td class="TextColumn"><input type="text" name="Ket3" id="Ket3" size="40" value="<?php echo htmlentities(stripslashes($sKet3),ENT_NOQUOTES, "UTF-8"); ?>">
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Detail 4"); ?></td>
		<td class="TextColumn"><input type="text" name="Detail4" id="Detail4" size="40" value="<?php echo htmlentities(stripslashes($sDetail4),ENT_NOQUOTES, "UTF-8"); ?>">
		<td class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Nilai4" onChange="updatesum()"  id="Nilai4"  value="<?php echo htmlentities(stripslashes($sNilai4),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sNilai4Error ?></font></td>
		<td class="TextColumn"><input type="text" name="Ket4" id="Ket4" size="40" value="<?php echo htmlentities(stripslashes($sKet4),ENT_NOQUOTES, "UTF-8"); ?>">
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Detail 5"); ?></td>
		<td class="TextColumn"><input type="text" name="Detail5" id="Detail5" size="40" value="<?php echo htmlentities(stripslashes($sDetail5),ENT_NOQUOTES, "UTF-8"); ?>">
		<td class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Nilai5" onChange="updatesum()"  id="Nilai5"  value="<?php echo htmlentities(stripslashes($sNilai5),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sNilai5Error ?></font></td>
		<td class="TextColumn"><input type="text" name="Ket5" id="Ket5" size="40" value="<?php echo htmlentities(stripslashes($sKet5),ENT_NOQUOTES, "UTF-8"); ?>">
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Detail 6"); ?></td>
		<td class="TextColumn"><input type="text" name="Detail6" id="Detail6" size="40" value="<?php echo htmlentities(stripslashes($sDetail6),ENT_NOQUOTES, "UTF-8"); ?>">
		<td class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Nilai6" onChange="updatesum()"  id="Nilai6"  value="<?php echo htmlentities(stripslashes($sNilai6),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sNilai6Error ?></font></td>
		<td class="TextColumn"><input type="text" name="Ket6" id="Ket6" size="40" value="<?php echo htmlentities(stripslashes($sKet6),ENT_NOQUOTES, "UTF-8"); ?>">
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Detail 7"); ?></td>
		<td class="TextColumn"><input type="text" name="Detail7" id="Detail7" size="40" value="<?php echo htmlentities(stripslashes($sDetail7),ENT_NOQUOTES, "UTF-8"); ?>">
		<td class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Nilai7" onChange="updatesum()"  id="Nilai7"  value="<?php echo htmlentities(stripslashes($sNilai7),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sNilai7Error ?></font></td>
		<td class="TextColumn"><input type="text" name="Ket7" id="Ket7" size="40" value="<?php echo htmlentities(stripslashes($sKet7),ENT_NOQUOTES, "UTF-8"); ?>">
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Detail 8"); ?></td>
		<td class="TextColumn"><input type="text" name="Detail8" id="Detail8" size="40" value="<?php echo htmlentities(stripslashes($sDetail8),ENT_NOQUOTES, "UTF-8"); ?>">
		<td class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Nilai8" onChange="updatesum()"  id="Nilai8"  value="<?php echo htmlentities(stripslashes($sNilai8),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sNilai8Error ?></font></td>
		<td class="TextColumn"><input type="text" name="Ket8" id="Ket8" size="40" value="<?php echo htmlentities(stripslashes($sKet8),ENT_NOQUOTES, "UTF-8"); ?>">
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Detail 9"); ?></td>
		<td class="TextColumn"><input type="text" name="Detail9" id="Detail9" size="40" value="<?php echo htmlentities(stripslashes($sDetail9),ENT_NOQUOTES, "UTF-8"); ?>">
		<td class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Nilai9" onChange="updatesum()"  id="Nilai9"  value="<?php echo htmlentities(stripslashes($sNilai9),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sNilai9Error ?></font></td>
		<td class="TextColumn"><input type="text" name="Ket9" id="Ket9" size="40" value="<?php echo htmlentities(stripslashes($sKet9),ENT_NOQUOTES, "UTF-8"); ?>">
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Detail 10"); ?></td>
		<td class="TextColumn"><input type="text" name="Detail10" id="Detail10" size="40" value="<?php echo htmlentities(stripslashes($sDetail10),ENT_NOQUOTES, "UTF-8"); ?>">
		<td class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Nilai10" onChange="updatesum()"  id="Nilai10"  value="<?php echo htmlentities(stripslashes($sNilai10),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sNilai10Error ?></font></td>
		<td class="TextColumn"><input type="text" name="Ket10" id="Ket10" size="40" value="<?php echo htmlentities(stripslashes($sKet10),ENT_NOQUOTES, "UTF-8"); ?>">
	</tr>		
			<tr>
				<td class="LabelColumn"><?php echo gettext("Keterangan Tambahan"); ?></td>
				<td colspan="4" class="TextColumn"><input   type="text" name="Keterangan" id="Keterangan" size="70" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sKeteranganError ?></font></td>
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
		$logvar2 = "PencairanCek Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPencairanCekID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
