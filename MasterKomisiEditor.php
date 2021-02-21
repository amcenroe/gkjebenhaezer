<?php
/*******************************************************************************
 *
 *  filename    : MasterKomisiEditor.php
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur ( http://www.gkjbekasi-wiltimur.net )
 *  2009 Erwin Pratama for GKJ Bekasi Timur ( http://www.gkjbekasitimur.org )
 *  2010 Erwin Pratama for GKPB Bali ( http://www.balichurchsynod.org/ )
 *  2013 Erwin Pratama for GKJ Tanjung Priok ( http://www.gkjtp.com )
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
$sPageTitle = gettext("Daftar Master Komisi Editor");

//Get the KomisiID out of the querystring
$iKomisiID = FilterInput($_GET["KomisiID"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?KomisiID= manually)
if (strlen($iKomisiID) > 0)
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

if (isset($_POST["MasterKomisiSubmit"]) || isset($_POST["MasterKomisiSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sKomisiID = FilterInput($_POST["KomisiID"]);
	$sBidangID = FilterInput($_POST["BidangID"]);
	$sEnable = FilterInput($_POST["Enable"]);
	$sKodeKomisi = FilterInput($_POST["KodeKomisi"]);
	$sNamaKomisi = FilterInput($_POST["NamaKomisi"]);
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
		if (strlen($iKomisiID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

				
			$sSQL = "INSERT INTO MasterKomisi ( 
			KomisiID,
			BidangID,
			Enable,
			KodeKomisi,
			NamaKomisi,
			Keterangan,
			DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sKomisiID . "',		
			'" . $sBidangID . "',				
			'" . $sEnable . "',
			'" . $sKodeKomisi . "',
			'" . $sNamaKomisi . "',			
			'" . $sKeterangan . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Master Komisi Data";


		// Existing Baptis (update)
		} else {
	//update the Baptis table
			$sSQL = "UPDATE MasterKomisi SET 

	KomisiID = '" . $sKomisiID . "',
	BidangID = '" . $sBidangID . "',	
	Enable = '" . $sEnable . "',
	KodeKomisi = '" . $sKodeKomisi . "',
	NamaKomisi = '" . $sNamaKomisi . "',
	Keterangan = '" . $sKeterangan . "',
	
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE KomisiID = " . $iKomisiID;

		//	echo $sSQL;
	

			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Data Master Komisi";
		}

		//Execute the SQL
		RunQuery($sSQL);
		

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iKomisiID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. MasterKomisiEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iKomisiID);
		}
		else if (isset($_POST["MasterKomisiSubmit"]))
		{
			//Send to the view of this PendetaData
			Redirect("SelectListApp.php?mode=masterkomisi&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("MasterKomisiEditor.php&amp;$refresh");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iKomisiID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM MasterKomisi  WHERE KomisiID = " . $iKomisiID;
		$rsGereja = RunQuery($sSQL);
		extract(mysql_fetch_array($rsGereja));
	
	$sKomisiID = $KomisiID;
	$sBidangID = $BidangID;	
	$sEnable = $Enable;
	$sKodeKomisi = $KodeKomisi;
	$sNamaKomisi = $NamaKomisi;
	$sKeterangan = $Keterangan;
	$sDateLastEdited = $DateLastEdited;
	$sDateEntered  = $DateEntered;
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

//Get Aset Class Names for the drop-down
$sSQL = "SELECT * FROM MasterBidang ORDER BY BidangID";
$rsBidang = RunQuery($sSQL);

require "Include/Header.php";

?>

<form method="post" action="MasterKomisiEditor.php?KomisiID=<?php echo $iKomisiID; ?>" name="MasterKomisiEditor">

<table cellpadding="3" align="center" valign="top" >


			
	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="MasterKomisiSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"MasterKomisiSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iKomisiID) > 0) 
{ echo "SelectListApp.php?mode=masterkomisi&amp;$refresh"; 
} else {echo "SelectListApp.php?mode=masterkomisi&amp;$refresh"; 
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
		<td class="LabelColumn"><?php echo gettext("Komisi ID"); ?></td>
		<td class="TextColumn"><input type="text" name="KomisiID" size=80  id="KomisiID" value="<?php echo htmlentities(stripslashes($sKomisiID),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sKomisiIDError ?></font></td>
	</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Bidang:"); ?></td>
				<td class="TextColumn">
					<select name="BidangID">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsBidang))
						{
							extract($aRow);

							echo "<option value=\"" . $BidangID . "\"";
							if ($sBidangID == $BidangID) { echo " selected"; }
							echo ">" . $KodeBidang . "&nbsp; - " . $NamaBidang ;
						}
						?>

					</select>
				</td>
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
		<td class="LabelColumn" ><?php echo gettext("Kode Komisi"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="KodeKomisi" size=80 id="KodeKomisi" value="<?php echo htmlentities(stripslashes($sKodeKomisi),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sKodeKomisiError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Nama Komisi"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="NamaKomisi" size=80 id="NamaKomisi" value="<?php echo htmlentities(stripslashes($sNamaKomisi),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sNamaKomisiError ?></font></td>
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
		$logvar2 = "Daftar Master Komisi Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iKomisiID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
?>
