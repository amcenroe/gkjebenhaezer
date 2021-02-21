<?php
/*******************************************************************************
 *
 *  filename    : PengeluaranKasKecilEditor.php
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
$refresh = microtime() ;
//Set the page title
$sPageTitle = gettext("Editor - PengeluaranKasKecil GKJ Bekti");

//Get the PengeluaranKasKecilID out of the querystring
$iPengeluaranKasKecilID = FilterInput($_GET["PengeluaranKasKecilID"],'int');
$ReffTanggal = $_GET["ReffTanggal"] ;
$ReffKodeTI = $_GET["ReffKodeTI"] ;
$ReffPukul = $_GET["ReffPukul"] ;

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PengeluaranKasKecilID= manually)
if (strlen($iPengeluaranKasKecilID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsPengeluaranKasKecil = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPengeluaranKasKecil));

	if (mysql_num_rows($rsPengeluaranKasKecil) == 0)
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

if (isset($_POST["PengeluaranKasKecilSubmit"]) || isset($_POST["PengeluaranKasKecilSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	
			$sPengeluaranKasKecilID = FilterInput($_POST["PengeluaranKasKecilID"]); 
			$sTanggal = FilterInput($_POST["Tanggal"]); 
			$sPosAnggaranID = FilterInput($_POST["PosAnggaranID"]); 
			$sRabID = FilterInput($_POST["RabID"]); 
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
		if (strlen($iPengeluaranKasKecilID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";
			$sSQL = "INSERT INTO PengeluaranKasKecil ( 
			Tanggal,
			PosAnggaranID,
			RabID,
			DeskripsiKas,
			Keterangan,
			Jumlah,
			DateEntered,
			EnteredBy
			)
			VALUES ( 
			'" . $sTanggal . "',
			'" . $sPosAnggaranID . "',
			'" . $sRabID . "',
			'" . $sDeskripsiKas . "',
			'" . $sKeterangan . "',
			'" . $sJumlah . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New PengeluaranKasKecil Data";

		// Existing PengeluaranKasKecil (update)
		} else {
	
			$sSQL = "UPDATE PengeluaranKasKecil SET 

					Tanggal = '" . $sTanggal . "',
					PosAnggaranID = '" . $sPosAnggaranID . "',
					RabID = '" . $sRabID . "',
					DeskripsiKas = '" . $sDeskripsiKas . "',
					Keterangan = '" . $sKeterangan . "',
					Jumlah = '" . $sJumlah . "',
					
					DateLastEdited = '" . date("YmdHis") . "', 
					EditedBy = '" . $_SESSION['iUserID'] ;
					
			$sSQL .= "' WHERE PengeluaranKasKecilID = " . $iPengeluaranKasKecilID;

		//	echo $sSQL;
			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update PengeluaranKasKecil ";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPengeluaranKasKecilID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PengeluaranKasKecilEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPengeluaranKasKecilID);
		}
		else if (isset($_POST["PengeluaranKasKecilSubmit"]))
		{
			//Send to the view of this PengeluaranKasKecil
			Redirect("SelectListApp3.php?mode=PengeluaranKasKecil&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PengeluaranKasKecilEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPengeluaranKasKecilID) > 0)
	{
		//Editing....
		//Get all the data on this record

		
		// Get Data PengeluaranKasKecil
		$sSQL = "SELECT * FROM PengeluaranKasKecil  WHERE PengeluaranKasKecilID = " . $iPengeluaranKasKecilID;
		$rsPengeluaranKasKecil = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPengeluaranKasKecil));

			$sPengeluaranKasKecilID = $PengeluaranKasKecilID;
			$sTanggal = $Tanggal;
			$sPosAnggaranID = $PosAnggaranID;
			$sRabID = $RabID;
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



require "Include/Header.php";

?>

<form method="post" action="PengeluaranKasKecilEditor.php?PengeluaranKasKecilID=<?php echo $iPengeluaranKasKecilID; ?>" name="PengeluaranKasKecilEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="PengeluaranKasKecilSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"PengeluaranKasKecilSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="PengeluaranKasKecilCancel" onclick="javascript:document.location='<?php if (strlen($iPengeluaranKasKecilID) > 0) 
{ //echo "PengeluaranKasKecilView.php?PengeluaranKasKecilID=" . $iPengeluaranKasKecilID; 
echo "SelectListApp3.php?mode=PengeluaranKasKecil&amp;$refresh";
} else {echo "SelectListApp3.php?mode=PengeluaranKasKecil&amp;$refresh"; 
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
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Pengeluaran:"); ?></td>
				<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
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
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Item dalam Recana Program dan Anggaran "); ?></td>
				<td class="TextColumn">
					<select name="RabID">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.RabID, a.KomisiID, a.Tahun , a.Program, a.Kegiatan, b.KodeKomisi, c.KodeBidang from ProgramDanAnggaran a 
						LEFT JOIN MasterKomisi b ON a.KomisiID=b.KomisiID
						LEFT JOIN MasterBidang c ON b.BidangID = c.BidangID 
						ORDER BY a.Tahun Desc, c.BidangID ASC , b.KomisiID ASC , a.RabID ASC
						";
						$rsRAB = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsRAB))
						{
							extract($aRow);
							echo "<option value=\"" . $RabID . "\"";
							if ($sRabID == $RabID ) { echo " selected"; }
							echo ">RAB $Tahun - $KodeBidang.$KodeKomisi | $Program | $Kegiatan" ;
						}
						?>
					</select>
				</td>	
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Deskripsi Pengeluaran :"); ?></td>
				<td colspan="4" class="TextColumn"><input    type="text" name="DeskripsiKas" id="DeskripsiKas" size="70" value="<?php echo htmlentities(stripslashes($sDeskripsiKas),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sDeskripsiKasError ?></font></td>			
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Jumlah Pengeluaran"); ?></td>
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
		$logvar2 = "PengeluaranKasKecil Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPengeluaranKasKecilID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
