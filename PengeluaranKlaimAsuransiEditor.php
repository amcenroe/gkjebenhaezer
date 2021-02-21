<?php
/*******************************************************************************
 *
 *  filename    : PengeluaranKlaimAsuransiEditor.php
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
$sPageTitle = gettext("Editor - PengeluaranKlaimAsuransi");

//Get the PengeluaranKlaimAsuransiID out of the querystring
$iPengeluaranKlaimAsuransiID = FilterInput($_GET["PengeluaranKlaimAsuransiID"],'int');
$ReffTanggal = $_GET["ReffTanggal"] ;
$ReffKodeTI = $_GET["ReffKodeTI"] ;
$ReffPukul = $_GET["ReffPukul"] ;

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PengeluaranKlaimAsuransiID= manually)
if (strlen($iPengeluaranKlaimAsuransiID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsPengeluaranKlaimAsuransi = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPengeluaranKlaimAsuransi));

	if (mysql_num_rows($rsPengeluaranKlaimAsuransi) == 0)
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

if (isset($_POST["PengeluaranKlaimAsuransiSubmit"]) || isset($_POST["PengeluaranKlaimAsuransiSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	
			$sPengeluaranKlaimAsuransiID = FilterInput($_POST["PengeluaranKlaimAsuransiID"]); 
			$sTanggal = FilterInput($_POST["Tanggal"]); 
			$sPosAnggaranID = FilterInput($_POST["PosAnggaranID"]); 
			$sPasien = FilterInput($_POST["Pasien"]); 
			$sNomorKwitansi = FilterInput($_POST["NomorKwitansi"]); 
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
		if (strlen($iPengeluaranKlaimAsuransiID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";
			$sSQL = "INSERT INTO PengeluaranKlaimAsuransi ( 
			Tanggal,
			PosAnggaranID,
			Pasien,
			NomorKwitansi,
			DeskripsiKas,
			Keterangan,
			Jumlah,
			DateEntered,
			EnteredBy
			)
			VALUES ( 
			'" . $sTanggal . "',
			'" . $sPosAnggaranID . "',
			'" . $sPasien . "',
			'" . $sNomorKwitansi . "',
			'" . $sDeskripsiKas . "',
			'" . $sKeterangan . "',
			'" . $sJumlah . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New PengeluaranKlaimAsuransi Data";

		// Existing PengeluaranKlaimAsuransi (update)
		} else {
	
			$sSQL = "UPDATE PengeluaranKlaimAsuransi SET 

					Tanggal = '" . $sTanggal . "',
					PosAnggaranID = '" . $sPosAnggaranID . "',
					Pasien = '" . $sPasien . "',
					NomorKwitansi = '" . $sNomorKwitansi . "',
					DeskripsiKas = '" . $sDeskripsiKas . "',
					Keterangan = '" . $sKeterangan . "',
					Jumlah = '" . $sJumlah . "',
					
					DateLastEdited = '" . date("YmdHis") . "', 
					EditedBy = '" . $_SESSION['iUserID'] ;
					
			$sSQL .= "' WHERE PengeluaranKlaimAsuransiID = " . $iPengeluaranKlaimAsuransiID;

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update PengeluaranKlaimAsuransi ";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPengeluaranKlaimAsuransiID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PengeluaranKlaimAsuransiEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPengeluaranKlaimAsuransiID);
		}
		else if (isset($_POST["PengeluaranKlaimAsuransiSubmit"]))
		{
			//Send to the view of this PengeluaranKlaimAsuransi
			Redirect("SelectListApp3.php?mode=PengeluaranKlaimAsuransi&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PengeluaranKlaimAsuransiEditor.php&amp;$refresh");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPengeluaranKlaimAsuransiID) > 0)
	{
		//Editing....
		//Get all the data on this record

		
		// Get Data PengeluaranKlaimAsuransi
		$sSQL = "SELECT * FROM PengeluaranKlaimAsuransi  WHERE PengeluaranKlaimAsuransiID = " . $iPengeluaranKlaimAsuransiID;
		$rsPengeluaranKlaimAsuransi = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPengeluaranKlaimAsuransi));

			$sPengeluaranKlaimAsuransiID = $PengeluaranKlaimAsuransiID;
			$sTanggal = $Tanggal;
			$sPosAnggaranID = $PosAnggaranID;
			$sPasien = $Pasien;
			$sNomorKwitansi = $NomorKwitansi;
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

<form method="post" action="PengeluaranKlaimAsuransiEditor.php?PengeluaranKlaimAsuransiID=<?php echo $iPengeluaranKlaimAsuransiID; ?>" name="PengeluaranKlaimAsuransiEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="PengeluaranKlaimAsuransiSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"PengeluaranKlaimAsuransiSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="PengeluaranKlaimAsuransiCancel" onclick="javascript:document.location='<?php if (strlen($iPengeluaranKlaimAsuransiID) > 0) 
{ //echo "PengeluaranKlaimAsuransiView.php?PengeluaranKlaimAsuransiID=" . $iPengeluaranKlaimAsuransiID; 
echo "SelectListApp3.php?mode=PengeluaranKlaimAsuransi&amp;$refresh";
} else {echo "SelectListApp3.php?mode=PengeluaranKlaimAsuransi&amp;$refresh"; 
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
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Klaim"); ?></td>
				<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
				</td>
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Nama Karyawan "); ?></td>
				<td class="TextColumn">
					<select name="PosAnggaranID" >
						
						<?php
						//Check karyawan Gereja Apa Bukan
						// $_SESSION['iUserID']
				//		 $sSQL0 = "SELECT *
				//					FROM person2volunteeropp_p2vo
				//					WHERE p2vo_vol_ID = 207 AND p2vo_per_ID = " . $_SESSION['iUserID'] ;
				//		$rsStatusKaryawan  = RunQuery($sSQL0);
				//		if(is_resource($rsStatusKaryawan) && mysql_num_rows($rsStatusKaryawan) > 0 ){
				//		$sSQL = "SELECT p2vo_per_ID as PosAnggaranID, b.per_FirstName as NamaKaryawan 
				//					FROM person2volunteeropp_p2vo a
				//					LEFT JOIN person_per b ON a.p2vo_per_ID = b.per_ID
				//					WHERE p2vo_vol_ID =207 AND p2vo_per_ID = " . $_SESSION['iUserID'];
				//					}else{
				// 1 = Pendeta
				// 210 = Vikaris
				// 207 = Karyawan Gereja
					
						$sSQL = "SELECT p2vo_per_ID as PosAnggaranID, b.per_FirstName as NamaKaryawan 
									FROM person2volunteeropp_p2vo a
									LEFT JOIN person_per b ON a.p2vo_per_ID = b.per_ID
									WHERE p2vo_per_ID > 1000 AND (p2vo_vol_ID =207 OR p2vo_vol_ID =210 OR p2vo_vol_ID =1)";
						echo "<option value=\"0\" selected>Tidak Diketahui</option>";			
				//					}
						//echo $sSQL0	;		
						  
						//Get Karyawan Names for the drop-down

							$rsNamaKaryawan = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsNamaKaryawan))
						{
							extract($aRow);

							echo "<option value=\"" . $PosAnggaranID . "\"";
							if ($sPosAnggaranID == $PosAnggaranID) { echo " selected"; }
							echo ">" . $PosAnggaranID." - ".$NamaKaryawan;
						}
						?>

					</select>
				</td>	
			</tr> 
			<tr>
				<td class="LabelColumn"><?php echo gettext("Pasien :"); ?></td>
				<td colspan="4" class="TextColumn"><input    type="text" name="Pasien" id="Pasien" size="70" value="<?php echo htmlentities(stripslashes($sPasien),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sPasienError ?></font></td>			
			</tr>	
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nomor Kwitansi :"); ?></td>
				<td colspan="4" class="TextColumn"><input    type="text" name="NomorKwitansi" id="NomorKwitansi" size="70" value="<?php echo htmlentities(stripslashes($sNomorKwitansi),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNomorKwitansiError ?></font></td>			
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Deskripsi Klaim/Diagnosis Dokter :"); ?></td>
				<td colspan="4" class="TextColumn"><input    type="text" name="DeskripsiKas" id="DeskripsiKas" size="70" value="<?php echo htmlentities(stripslashes($sDeskripsiKas),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sDeskripsiKasError ?></font></td>			
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Jumlah Pengeluaran"); ?></td>
				<td colspan="4" class="TextColumn">Rp. <input  class="right "align="right" type="text" name="Jumlah" id="Jumlah"  value="<?php echo htmlentities(stripslashes($sJumlah),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sJumlahError ?></font></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Rumah Sakit/Klinik Rujukan"); ?></td>
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
		$logvar2 = "PengeluaranKlaimAsuransi Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPengeluaranKlaimAsuransiID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
