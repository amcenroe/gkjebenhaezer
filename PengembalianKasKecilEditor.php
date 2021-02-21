<?php
/*******************************************************************************
 *
 *  filename    : PengembalianKasKecilEditor.php
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
$sPageTitle = gettext("Editor - PengembalianKasKecil GKJ Bekti");

//Get the PengembalianKasKecilID out of the querystring
$iPengembalianKasKecilID = FilterInput($_GET["PengembalianKasKecilID"],'int');
$ReffTanggal = $_GET["ReffTanggal"] ;
$ReffKodeTI = $_GET["ReffKodeTI"] ;
$ReffPukul = $_GET["ReffPukul"] ;

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PengembalianKasKecilID= manually)
if (strlen($iPengembalianKasKecilID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsPengembalianKasKecil = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPengembalianKasKecil));

	if (mysql_num_rows($rsPengembalianKasKecil) == 0)
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

if (isset($_POST["PengembalianKasKecilSubmit"]) || isset($_POST["PengembalianKasKecilSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	
			$sPengembalianKasKecilID = FilterInput($_POST["PengembalianKasKecilID"]); 
			$sTanggal = FilterInput($_POST["Tanggal"]); 
			$sPosAnggaranID = FilterInput($_POST["PosAnggaranID"]); 
			$sper_ID = FilterInput($_POST["per_ID"]); 
			$sDeskripsiKas = FilterInput($_POST["DeskripsiKas"]); 
			$sKeterangan = FilterInput($_POST["Keterangan"]); 
			$sJumlah = FilterInput($_POST["Jumlah"]); 
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
		// New Data (add)
		if (strlen($iPengembalianKasKecilID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";
			$sSQL = "INSERT INTO PengembalianKasKecil ( 
			Tanggal,
			PosAnggaranID,
			per_ID,
			DeskripsiKas,
			Keterangan,
			Jumlah,
			DateEntered,
			EnteredBy
			)
			VALUES ( 
			'" . $sTanggal . "',
			'" . $sPosAnggaranID . "',
			'" . $sper_ID . "',
			'" . $sDeskripsiKas . "',
			'" . $sKeterangan . "',
			'" . $sJumlah . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New PengembalianKasKecil Data";

		// Existing PengembalianKasKecil (update)
		} else {
	
			$sSQL = "UPDATE PengembalianKasKecil SET 

					Tanggal = '" . $sTanggal . "',
					PosAnggaranID = '" . $sPosAnggaranID . "',
					per_ID = '" . $sper_ID . "',
					DeskripsiKas = '" . $sDeskripsiKas . "',
					Keterangan = '" . $sKeterangan . "',
					Jumlah = '" . $sJumlah . "',
					
					DateLastEdited = '" . date("YmdHis") . "', 
					EditedBy = '" . $_SESSION['iUserID'] ;
					
			$sSQL .= "' WHERE PengembalianKasKecilID = " . $iPengembalianKasKecilID;

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update PengembalianKasKecil ";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPengembalianKasKecilID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PengembalianKasKecilEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPengembalianKasKecilID);
		}
		else if (isset($_POST["PengembalianKasKecilSubmit"]))
		{
			//Send to the view of this PengembalianKasKecil
			Redirect("SelectListApp3.php?mode=PengembalianKasKecil&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PengembalianKasKecilEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPengembalianKasKecilID) > 0)
	{
		//Editing....
		//Get all the data on this record

		
		// Get Data PengembalianKasKecil
		$sSQL = "SELECT * FROM PengembalianKasKecil  WHERE PengembalianKasKecilID = " . $iPengembalianKasKecilID;
		$rsPengembalianKasKecil = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPengembalianKasKecil));

			$sPengembalianKasKecilID = $PengembalianKasKecilID;
			$sTanggal = $Tanggal;
			$sPosAnggaranID = $PosAnggaranID;
			$sper_ID = $per_ID;
			$sDeskripsiKas = $DeskripsiKas;
			$sKeterangan = $Keterangan;
			$sJumlah = $Jumlah;
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

//Get Names for the drop-down
$sSQL1 = "SELECT * FROM person_per 
where per_cls_id < 3
ORDER BY per_FirstName";
$rsNamaWarga = RunQuery($sSQL1);

require "Include/Header.php";

?>

<form method="post" action="PengembalianKasKecilEditor.php?PengembalianKasKecilID=<?php echo $iPengembalianKasKecilID; ?>" name="PengembalianKasKecilEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="PengembalianKasKecilSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"PengembalianKasKecilSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="PengembalianKasKecilCancel" onclick="javascript:document.location='<?php if (strlen($iPengembalianKasKecilID) > 0) 
{ //echo "PengembalianKasKecilView.php?PengembalianKasKecilID=" . $iPengembalianKasKecilID; 
echo "SelectListApp3.php?mode=PengembalianKasKecil&amp;$refresh";
} else {echo "SelectListApp3.php?mode=PengembalianKasKecil&amp;$refresh"; 
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
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Pengembalian:"); ?></td>
				<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
				</td>
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Dikembalikan Oleh:"); ?></td>
				<td colspan="3" class="TextColumn">
					<select name="per_ID" size="1">
						<option value="0" selected><?php echo gettext("Tidak Diketahui / Bukan Warga "); ?></option>
						<option value="0">-----------------------</option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaWarga))
						{
							extract($aRow);

							echo "<option value=\"" . $per_ID . "\"";
							if ($sper_ID == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName . " - " . $per_WorkPhone;
						}
						?>

					</select>
				</td>
			</tr>				
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Bidang - Komisi - Pos Anggaran "); ?></td>
				<td class="TextColumn">
					<select name="PosAnggaranID">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.PosAnggaranID, a.NamaPosAnggaran , b.NamaKomisi, c.KodeBidang from MasterPosAnggaran a 
						LEFT JOIN MasterKomisi b ON a.KomisiID=b.KomisiID
						LEFT JOIN MasterBidang c ON b.BidangID = c.BidangID 
						ORDER BY c.BidangID, b.KomisiID, a.PosAnggaranID
						";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							echo "<option value=\"" . $PosAnggaranID . "\"";
							if ($sPosAnggaranID == $PosAnggaranID ) { echo " selected"; }
							echo ">$KodeBidang - $NamaKomisi - $NamaPosAnggaran" ;
						}
						?>

					</select>
				</td>	
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Deskripsi Pengembalian :"); ?></td>
				<td colspan="4" class="TextColumn"><input    type="text" name="DeskripsiKas" id="DeskripsiKas" size="70" value="<?php echo htmlentities(stripslashes($sDeskripsiKas),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sDeskripsiKasError ?></font></td>			
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Jumlah Pengembalian"); ?></td>
				<td colspan="4" class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Jumlah" id="Jumlah"  value="<?php echo htmlentities(stripslashes($sJumlah),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sJumlahError ?></font></td>
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
		$logvar2 = "PengembalianKasKecil Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPengembalianKasKecilID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
