<?php
/*******************************************************************************
 *
 *  filename    : MasterAnggaranEditor.php
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
$sPageTitle = gettext("Daftar Master Nilai Anggaran Editor");

//Get the MasterAnggaranID out of the querystring
$iMasterAnggaranID = FilterInput($_GET["MasterAnggaranID"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?MasterAnggaranID= manually)
if (strlen($iMasterAnggaranID) > 0)
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

if (isset($_POST["MasterAnggaranSubmit"]) || isset($_POST["MasterAnggaranSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sMasterAnggaranID = FilterInput($_POST["MasterAnggaranID"]);
	$sKomisiID = FilterInput($_POST["KomisiID"]);
	$sTahunAnggaran = FilterInput($_POST["TahunAnggaran"]);
	$sEnable = FilterInput($_POST["Enable"]);
	$sBudget = FilterInput($_POST["Budget"]);
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
		if (strlen($iMasterAnggaranID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

				
			$sSQL = "INSERT INTO MasterAnggaran ( 
			KomisiID,
			TahunAnggaran,
			Enable,
			Budget,
			Keterangan,
			DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sKomisiID . "',
			'" . $sTahunAnggaran . "',			
			'" . $sEnable . "',
			'" . $sBudget . "',			
			'" . $sKeterangan . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Master PosAnggaran Data";


		// Existing Baptis (update)
		} else {
	//update the Baptis table
			$sSQL = "UPDATE MasterAnggaran SET 

			KomisiID = '" . $sKomisiID . "',
			TahunAnggaran = '" . $sTahunAnggaran . "',			
			Enable = '" . $sEnable . "',
			Budget = '" . $sBudget . "',
			Keterangan = '" . $sKeterangan . "',
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE MasterAnggaranID = " . $iMasterAnggaranID;

			//echo $sSQL;
	

			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Data Master PosAnggaran";
		}

		//Execute the SQL
		RunQuery($sSQL);
		

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMasterAnggaranID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. MasterAnggaranEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iMasterAnggaranID);
		}
		else if (isset($_POST["MasterAnggaranSubmit"]))
		{
			//Send to the view of this PendetaData
			Redirect("SelectListApp2.php?mode=masterposanggthn&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("MasterAnggaranEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iMasterAnggaranID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM MasterAnggaran  WHERE MasterAnggaranID = " . $iMasterAnggaranID;
		$rsGereja = RunQuery($sSQL);
		extract(mysql_fetch_array($rsGereja));
	
	$sMasterAnggaranID = $MasterAnggaranID;
	$sKomisiID = $KomisiID;	
	$sTahunAnggaran = $TahunAnggaran;
	$sEnable = $Enable;
	$sBudget = $Budget;
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
        $sSQL = "SELECT a.*, a.Keterangan as KetKomisi, b.* FROM MasterKomisi a
		LEFT JOIN MasterBidang b ON a.BidangID=b.BidangID
		";
$rsKomisi = RunQuery($sSQL);

require "Include/Header.php";

?>

<form method="post" action="MasterAnggaranEditor.php?MasterAnggaranID=<?php echo $iMasterAnggaranID; ?>" name="MasterAnggaranEditor">

<table cellpadding="3" align="center" valign="top" >


			
	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="MasterAnggaranSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"MasterAnggaranSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iMasterAnggaranID) > 0) 
{ echo "SelectListApp2.php?mode=masterposanggthn&amp;$refresh"; 
} else {echo "SelectListApp2.php?mode=masterposanggthn&amp;$refresh"; 
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
		<td class="LabelColumn" ><?php echo gettext("TahunAnggaran"); ?></td>
		<td class="TextColumn" colspan="3">
			<select name="TahunAnggaran">
				<option value="2013" <?php if ($sTahunAnggaran == 2013) { echo "selected"; } ?>><?php echo gettext("2013"); ?></option>
				<option value="2014" <?php if ($sTahunAnggaran == 2014) { echo "selected"; } ?>><?php echo gettext("2014"); ?></option>
				<option value="2015" <?php if ($sTahunAnggaran == 2015) { echo "selected"; } ?>><?php echo gettext("2015"); ?></option>
				<option value="2016" <?php if ($sTahunAnggaran == 2016) { echo "selected"; } ?>><?php echo gettext("2016"); ?></option>
				<option value="2017" <?php if ($sTahunAnggaran == 2017) { echo "selected"; } ?>><?php echo gettext("2017"); ?></option>
				<option value="2018" <?php if ($sTahunAnggaran == 2018) { echo "selected"; } ?>><?php echo gettext("2018"); ?></option>
				<option value="2019" <?php if ($sTahunAnggaran == 2019) { echo "selected"; } ?>><?php echo gettext("2019"); ?></option>
				<option value="2020" <?php if ($sTahunAnggaran == 2020) { echo "selected"; } ?>><?php echo gettext("2020"); ?></option>
				<option value="2021" <?php if ($sTahunAnggaran == 2021) { echo "selected"; } ?>><?php echo gettext("2021"); ?></option>
				<option value="2022" <?php if ($sTahunAnggaran == 2022) { echo "selected"; } ?>><?php echo gettext("2022"); ?></option>
				<option value="2023" <?php if ($sTahunAnggaran == 2023) { echo "selected"; } ?>><?php echo gettext("2023"); ?></option>
				<option value="2024" <?php if ($sTahunAnggaran == 2024) { echo "selected"; } ?>><?php echo gettext("2024"); ?></option>
				<option value="2025" <?php if ($sTahunAnggaran == 2025) { echo "selected"; } ?>><?php echo gettext("2025"); ?></option>
			</select>
		</td>
	</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Komisi/Bidang:"); ?></td>
				<td class="TextColumn">
					<select name="KomisiID">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsKomisi))
						{
							extract($aRow);

							echo "<option value=\"" . $KomisiID . "\"";
							if ($sKomisiID == $KomisiID) { echo " selected"; }
							echo ">" . $KodeKomisi . " - " . $NamaKomisi  . "/" . $KodeBidang . " - " . $NamaBidang;
						}
						?>

					</select>
				</td>
			</tr>	

	<tr>
		<td class="LabelColumn" ><?php echo gettext("Budget"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="Budget" size=80 id="Budget" value="<?php echo htmlentities(stripslashes($sBudget),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sBudgetError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan"); ?></td>
		<td class="TextColumn"><input type="text" name="Keterangan" size=80  id="Keterangan" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sKeteranganError ?></font></td>
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
		$logvar2 = "Daftar Master PosAnggaran Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMasterAnggaranID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
?>
