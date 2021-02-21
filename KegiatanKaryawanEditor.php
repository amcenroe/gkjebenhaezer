<?php
/*******************************************************************************
 *
 *  filename    : KegiatanKaryawanEditor.php
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
$sPageTitle = gettext("Editor - Kegiatan Karyawan GKJ Bekti");

//Get the KegiatanKaryawan_ID out of the querystring
$iKegiatanKaryawan_ID = FilterInput($_GET["KegiatanKaryawan_ID"],'int');
$ReffTanggal = $_GET["ReffTanggal"] ;
$ReffKodeTI = $_GET["ReffKodeTI"] ;
$ReffPukul = $_GET["ReffPukul"] ;

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?KegiatanKaryawan_ID= manually)
if (strlen($iKegiatanKaryawan_ID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsKegiatan = RunQuery($sSQL);
	extract(mysql_fetch_array($rsKegiatan));

	if (mysql_num_rows($rsKegiatan) == 0)
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

if (isset($_POST["KegiatanSubmit"]) || isset($_POST["KegiatanSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	

			$sKegiatanKaryawan_ID = FilterInput($_POST["KegiatanKaryawan_ID"]); 
			$sKaryawanID = FilterInput($_POST["KaryawanID"]); 
			$sTanggal = FilterInput($_POST["Tanggal"]); 
			$sPukul = FilterInput($_POST["Pukul"]); 
			$sKodeTI = FilterInput($_POST["KodeTI"]); 
			$sNamaKegiatan = FilterInput($_POST["NamaKegiatan"]); 
			$sKeterangan = FilterInput($_POST["Keterangan"]); 
			$sTanggalMulai = FilterInput($_POST["TanggalMulai"]); 
			$sTanggalSelesai = FilterInput($_POST["TanggalSelesai"]); 
			$sJamMulai = FilterInput($_POST["JamMulai"]); 
			$sJamSelesai = FilterInput($_POST["JamSelesai"]); 
			$sHasil = FilterInput($_POST["Hasil"]); 
			$sTargetHari = FilterInput($_POST["TargetHari"]); 
			$sTargetJam = FilterInput($_POST["TargetJam"]); 
			$sDateEntered = FilterInput($_POST["DateEntered"]); 
			$sEnteredBy = FilterInput($_POST["EnteredBy"]); 
			$sDateLastEdited = FilterInput($_POST["DateLastEdited"]); 
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
		if (strlen($iKegiatanKaryawan_ID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";
			$sSQL = "INSERT INTO Kegiatangkjbekti ( 
				KaryawanID,
				Tanggal,
				Pukul,
				KodeTI,
				NamaKegiatan,
				Keterangan,
				TanggalMulai,
				TanggalSelesai,
				JamMulai,
				JamSelesai,
				Hasil,
				TargetHari,
				TargetJam,
				DateEntered,				
				EnteredBy
			)
			VALUES ( 
			'" . $sKaryawanID . "',
			'" . $sTanggal . "',
			'" . $sPukul . "',
			'" . $sKodeTI . "',
			'" . $sNamaKegiatan . "',
			'" . $sKeterangan . "',
			'" . $sTanggalMulai . "',
			'" . $sTanggalSelesai . "',
			'" . $sJamMulai . "',
			'" . $sJamSelesai . "',
			'" . $sHasil . "',
			'" . $sTargetHari . "',
			'" . $sTargetJam . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New Kegiatan Data";

			
			

		// Existing Kegiatan (update)
		} else {
	
			$sSQL = "UPDATE Kegiatangkjbekti SET 
					KaryawanID = '" . $sKaryawanID . "',
					Tanggal = '" . $sTanggal . "',
					Pukul = '" . $sPukul . "',
					KodeTI = '" . $sKodeTI . "',
					NamaKegiatan = '" . $sNamaKegiatan . "',
					Keterangan = '" . $sKeterangan . "',
					TanggalMulai = '" . $sTanggalMulai . "',
					TanggalSelesai = '" . $sTanggalSelesai . "',
					JamMulai = '" . $sJamMulai . "',
					JamSelesai = '" . $sJamSelesai . "',
					Hasil = '" . $sHasil . "',
					TargetHari = '" . $sTargetHari . "',
					TargetJam = '" . $sTargetJam . "',
					DateLastEdited = '" . date("YmdHis") . "', 
					EditedBy = '" . $_SESSION['iUserID'] ;
					
			$sSQL .= "' WHERE KegiatanKaryawan_ID = " . $iKegiatanKaryawan_ID;

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Kegiatan Karyawan";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iKegiatanKaryawan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. KegiatanKaryawanEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iKegiatanKaryawan_ID);
		}
		else if (isset($_POST["KegiatanSubmit"]))
		{
			//Send to the view of this Kegiatan
			Redirect("SelectListApp3.php?mode=KegiatanKaryawan&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("KegiatanKaryawanEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iKegiatanKaryawan_ID) > 0)
	{
		//Editing....
		//Get all the data on this record

		
		// Get Data Kegiatan
		$sSQL = "SELECT * FROM Kegiatangkjbekti  WHERE KegiatanKaryawan_ID = " . $iKegiatanKaryawan_ID;
		$rsKegiatan = RunQuery($sSQL);
		extract(mysql_fetch_array($rsKegiatan));
		
			$sKegiatanKaryawan_ID = $KegiatanKaryawan_ID;
			$sKaryawanID = $KaryawanID;
			$sTanggal = $Tanggal;
			$sPukul = $Pukul;
			$sKodeTI = $KodeTI;
			$sNamaKegiatan = $NamaKegiatan;
			$sKeterangan = $Keterangan;
			$sTanggalMulai = $TanggalMulai;
			$sTanggalSelesai = $TanggalSelesai;
			$sJamMulai = $JamMulai;
			$sJamSelesai = $JamSelesai;
			$sHasil = $Hasil;
			$sTargetHari = $TargetHari;
			$sTargetJam = $TargetJam;
			$sDateEntered = $DateEntered;
			$sEnteredBy = $EnteredBy;
			$sDateLastEdited = $DateLastEdited;
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

<form method="post" action="KegiatanKaryawanEditor.php?KegiatanKaryawan_ID=<?php echo $iKegiatanKaryawan_ID; ?>" name="KegiatanEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="KegiatanSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"KegiatanSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="KegiatanCancel" onclick="javascript:document.location='<?php if (strlen($iKegiatanKaryawan_ID) > 0) 
{ //echo "KegiatanKaryawanView.php?KegiatanKaryawan_ID=" . $iKegiatanKaryawan_ID; 
echo "SelectListApp3.php?mode=KegiatanKaryawan&amp;$refresh";
} else {echo "SelectListApp3.php?mode=KegiatanKaryawan&amp;$refresh"; 
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
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Data Jadwal dan Laporan Kegiatan Karyawan"); ?></b></td>
			</tr>
			<tr>
			
			<td class="LabelColumn"><?php echo gettext("Nama Karyawan :"); ?></td>
			<td class="TextColumnWithBottomBorder" colspans="2" >
					<select name="KaryawanID" >
						
						<?php
						//Check karyawan Gereja Apa Bukan
						// $_SESSION['iUserID']
						 $sSQL0 = "SELECT *
									FROM person2volunteeropp_p2vo
									WHERE p2vo_vol_ID = 207 AND p2vo_per_ID = " . $_SESSION['iUserID'] ;
						$rsStatusKaryawan  = RunQuery($sSQL0);
						if(is_resource($rsStatusKaryawan) && mysql_num_rows($rsStatusKaryawan) > 0 ){
						$sSQL = "SELECT p2vo_per_ID as KaryawanID, b.per_FirstName as NamaKaryawan 
									FROM person2volunteeropp_p2vo a
									LEFT JOIN person_per b ON a.p2vo_per_ID = b.per_ID
									WHERE p2vo_vol_ID =207 AND p2vo_per_ID = " . $_SESSION['iUserID'];
									}else{
						$sSQL = "SELECT p2vo_per_ID as KaryawanID, b.per_FirstName as NamaKaryawan 
									FROM person2volunteeropp_p2vo a
									LEFT JOIN person_per b ON a.p2vo_per_ID = b.per_ID
									WHERE p2vo_vol_ID =207";
						echo "<option value=\"0\" selected>Tidak Diketahui</option>";			
									}
						//echo $sSQL0	;		
						  
						//Get Karyawan Names for the drop-down

							$rsNamaKaryawan = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsNamaKaryawan))
						{
							extract($aRow);

							echo "<option value=\"" . $KaryawanID . "\"";
							if ($sKaryawanID == $KaryawanID) { echo " selected"; }
							echo ">" . $KaryawanID." - ".$NamaKaryawan;
						}
						?>

					</select>
			</td>
			<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Tempat Ibadah:"); ?></td>
				<td class="TextColumn">
					<select name="KodeTI">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Church Names for the drop-down
						$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
						$rsLokasiTI = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsLokasiTI))
						{
							extract($aRow);

							echo "<option value=\"" . $KodeTI . "\"";
							if ($sKodeTI == $KodeTI) { echo " selected"; }
							echo ">" . $NamaTI ;
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal :"); ?></td>
				<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
				</td>
			
			
			</tr>

			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Detail Kegiatan"); ?></b></td>
			</tr>			
			
			<tr>
							<td class="LabelColumn"><?php echo gettext("Nama Kegiatan:"); ?></td>
				<td colspan="4" class="TextColumn"><input    type="text" name="NamaKegiatan" id="NamaKegiatan" size="70" value="<?php echo htmlentities(stripslashes($sNamaKegiatan),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNamaKegiatanError ?></font></td>			
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Target (Harian)"); ?></td>
				<td class="TextColumn"><input   type="text" name="TargetHari" id="TargetHari" value="<?php echo htmlentities(stripslashes($sTargetHari),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sTargetHariError ?></font></td>

				<td class="LabelColumn"><?php echo gettext("Target (Jam)"); ?></td>
				<td class="TextColumn"><input    type="text" name="TargetJam" id="TargetHari" value="<?php echo htmlentities(stripslashes($sTargetJam),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sTargetJamError ?></font></td>
			</tr>			
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Detail Penyelesaian Kegiatan"); ?></b></td>
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Mulai :"); ?></td>
				<td class="TextColumn"><input type="text" name="TanggalMulai" value="<?php echo $sTanggalMulai; ?>" maxlength="10" id="sel2" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel2', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
				</td>

				<td class="LabelColumn"><?php echo gettext("Jam Mulai"); ?></td>
				<td class="TextColumnWithBottomBorder">
					<select name="JamMulai">
						<option value="0"><?php echo gettext("Pilih"); ?></option>
						<option value="00.00 WIB" <?php if ($sJamMulai == "00.00 WIB") { echo "selected"; } ?>><?php echo gettext("00.00 WIB"); ?></option>
						<option value="00.30 WIB" <?php if ($sJamMulai == "00.30 WIB") { echo "selected"; } ?>><?php echo gettext("00.30 WIB"); ?></option>
						<option value="01.00 WIB" <?php if ($sJamMulai == "01.00 WIB") { echo "selected"; } ?>><?php echo gettext("01.00 WIB"); ?></option>
						<option value="01.30 WIB" <?php if ($sJamMulai == "01.30 WIB") { echo "selected"; } ?>><?php echo gettext("01.30 WIB"); ?></option>
						<option value="02.00 WIB" <?php if ($sJamMulai == "02.00 WIB") { echo "selected"; } ?>><?php echo gettext("02.00 WIB"); ?></option>
						<option value="02.30 WIB" <?php if ($sJamMulai == "02.30 WIB") { echo "selected"; } ?>><?php echo gettext("02.30 WIB"); ?></option>
						<option value="03.00 WIB" <?php if ($sJamMulai == "03.00 WIB") { echo "selected"; } ?>><?php echo gettext("03.00 WIB"); ?></option>
						<option value="03.30 WIB" <?php if ($sJamMulai == "03.30 WIB") { echo "selected"; } ?>><?php echo gettext("03.30 WIB"); ?></option>
						<option value="04.00 WIB" <?php if ($sJamMulai == "04.00 WIB") { echo "selected"; } ?>><?php echo gettext("04.00 WIB"); ?></option>
						<option value="04.30 WIB" <?php if ($sJamMulai == "04.30 WIB") { echo "selected"; } ?>><?php echo gettext("04.30 WIB"); ?></option>
						<option value="05.00 WIB" <?php if ($sJamMulai == "05.00 WIB") { echo "selected"; } ?>><?php echo gettext("05.00 WIB"); ?></option>
						<option value="05.30 WIB" <?php if ($sJamMulai == "05.30 WIB") { echo "selected"; } ?>><?php echo gettext("05.30 WIB"); ?></option>
						<option value="06.00 WIB" <?php if ($sJamMulai == "06.00 WIB") { echo "selected"; } ?>><?php echo gettext("06.00 WIB"); ?></option>
						<option value="06.30 WIB" <?php if ($sJamMulai == "06.30 WIB") { echo "selected"; } ?>><?php echo gettext("06.30 WIB"); ?></option>
						<option value="07.00 WIB" <?php if ($sJamMulai == "07.00 WIB") { echo "selected"; } ?>><?php echo gettext("07.00 WIB"); ?></option>
						<option value="07.30 WIB" <?php if ($sJamMulai == "07.30 WIB") { echo "selected"; } ?>><?php echo gettext("07.30 WIB"); ?></option>
						<option value="08.00 WIB" <?php if ($sJamMulai == "08.00 WIB") { echo "selected"; } ?>><?php echo gettext("08.00 WIB"); ?></option>
						<option value="08.30 WIB" <?php if ($sJamMulai == "08.30 WIB") { echo "selected"; } ?>><?php echo gettext("08.30 WIB"); ?></option>
						<option value="09.00 WIB" <?php if ($sJamMulai == "09.00 WIB") { echo "selected"; } ?>><?php echo gettext("09.00 WIB"); ?></option>
						<option value="09.30 WIB" <?php if ($sJamMulai == "09.30 WIB") { echo "selected"; } ?>><?php echo gettext("09.30 WIB"); ?></option>
						<option value="10.00 WIB" <?php if ($sJamMulai == "10.00 WIB") { echo "selected"; } ?>><?php echo gettext("10.00 WIB"); ?></option>
						<option value="10.30 WIB" <?php if ($sJamMulai == "10.30 WIB") { echo "selected"; } ?>><?php echo gettext("10.30 WIB"); ?></option>
						<option value="11.00 WIB" <?php if ($sJamMulai == "11.00 WIB") { echo "selected"; } ?>><?php echo gettext("11.00 WIB"); ?></option>
						<option value="11.30 WIB" <?php if ($sJamMulai == "11.30 WIB") { echo "selected"; } ?>><?php echo gettext("11.30 WIB"); ?></option>
						<option value="12.00 WIB" <?php if ($sJamMulai == "12.00 WIB") { echo "selected"; } ?>><?php echo gettext("12.00 WIB"); ?></option>
						<option value="12.30 WIB" <?php if ($sJamMulai == "12.30 WIB") { echo "selected"; } ?>><?php echo gettext("12.30 WIB"); ?></option>
						<option value="13.00 WIB" <?php if ($sJamMulai == "13.00 WIB") { echo "selected"; } ?>><?php echo gettext("13.00 WIB"); ?></option>
						<option value="13.30 WIB" <?php if ($sJamMulai == "13.30 WIB") { echo "selected"; } ?>><?php echo gettext("13.30 WIB"); ?></option>
						<option value="14.00 WIB" <?php if ($sJamMulai == "14.00 WIB") { echo "selected"; } ?>><?php echo gettext("14.00 WIB"); ?></option>
						<option value="14.30 WIB" <?php if ($sJamMulai == "14.30 WIB") { echo "selected"; } ?>><?php echo gettext("14.30 WIB"); ?></option>
						<option value="15.00 WIB" <?php if ($sJamMulai == "15.00 WIB") { echo "selected"; } ?>><?php echo gettext("15.00 WIB"); ?></option>
						<option value="15.30 WIB" <?php if ($sJamMulai == "15.30 WIB") { echo "selected"; } ?>><?php echo gettext("15.30 WIB"); ?></option>
						<option value="16.00 WIB" <?php if ($sJamMulai == "16.00 WIB") { echo "selected"; } ?>><?php echo gettext("16.00 WIB"); ?></option>
						<option value="16.30 WIB" <?php if ($sJamMulai == "16.30 WIB") { echo "selected"; } ?>><?php echo gettext("16.30 WIB"); ?></option>
						<option value="17.00 WIB" <?php if ($sJamMulai == "17.00 WIB") { echo "selected"; } ?>><?php echo gettext("17.00 WIB"); ?></option>
						<option value="17.30 WIB" <?php if ($sJamMulai == "17.30 WIB") { echo "selected"; } ?>><?php echo gettext("17.30 WIB"); ?></option>
						<option value="18.00 WIB" <?php if ($sJamMulai == "18.00 WIB") { echo "selected"; } ?>><?php echo gettext("18.00 WIB"); ?></option>
						<option value="18.30 WIB" <?php if ($sJamMulai == "18.30 WIB") { echo "selected"; } ?>><?php echo gettext("18.30 WIB"); ?></option>
						<option value="19.00 WIB" <?php if ($sJamMulai == "19.00 WIB") { echo "selected"; } ?>><?php echo gettext("19.00 WIB"); ?></option>
						<option value="19.30 WIB" <?php if ($sJamMulai == "19.30 WIB") { echo "selected"; } ?>><?php echo gettext("19.30 WIB"); ?></option>
						<option value="20.00 WIB" <?php if ($sJamMulai == "20.00 WIB") { echo "selected"; } ?>><?php echo gettext("20.00 WIB"); ?></option>
						<option value="20.30 WIB" <?php if ($sJamMulai == "20.30 WIB") { echo "selected"; } ?>><?php echo gettext("20.30 WIB"); ?></option>
						<option value="21.00 WIB" <?php if ($sJamMulai == "21.00 WIB") { echo "selected"; } ?>><?php echo gettext("21.00 WIB"); ?></option>
						<option value="21.30 WIB" <?php if ($sJamMulai == "21.30 WIB") { echo "selected"; } ?>><?php echo gettext("21.30 WIB"); ?></option>
						<option value="22.00 WIB" <?php if ($sJamMulai == "22.00 WIB") { echo "selected"; } ?>><?php echo gettext("22.00 WIB"); ?></option>
						<option value="22.30 WIB" <?php if ($sJamMulai == "22.30 WIB") { echo "selected"; } ?>><?php echo gettext("22.30 WIB"); ?></option>
						<option value="23.00 WIB" <?php if ($sJamMulai == "23.00 WIB") { echo "selected"; } ?>><?php echo gettext("23.00 WIB"); ?></option>
						<option value="23.30 WIB" <?php if ($sJamMulai == "23.30 WIB") { echo "selected"; } ?>><?php echo gettext("23.30 WIB"); ?></option>
					</select>
				</td>			</tr>	
		
			<tr>
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Selesai :"); ?></td>
				<td class="TextColumn"><input type="text" name="TanggalSelesai" value="<?php echo $sTanggalSelesai; ?>" maxlength="10" id="sel3" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel3', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
				</td>

				<td class="LabelColumn"><?php echo gettext("Jam Selesai"); ?></td>
				<td class="TextColumnWithBottomBorder">
					<select name="JamSelesai">
						<option value="0"><?php echo gettext("Pilih"); ?></option>
						<option value="00.00 WIB" <?php if ($sJamSelesai == "00.00 WIB") { echo "selected"; } ?>><?php echo gettext("00.00 WIB"); ?></option>
						<option value="00.30 WIB" <?php if ($sJamSelesai == "00.30 WIB") { echo "selected"; } ?>><?php echo gettext("00.30 WIB"); ?></option>
						<option value="01.00 WIB" <?php if ($sJamSelesai == "01.00 WIB") { echo "selected"; } ?>><?php echo gettext("01.00 WIB"); ?></option>
						<option value="01.30 WIB" <?php if ($sJamSelesai == "01.30 WIB") { echo "selected"; } ?>><?php echo gettext("01.30 WIB"); ?></option>
						<option value="02.00 WIB" <?php if ($sJamSelesai == "02.00 WIB") { echo "selected"; } ?>><?php echo gettext("02.00 WIB"); ?></option>
						<option value="02.30 WIB" <?php if ($sJamSelesai == "02.30 WIB") { echo "selected"; } ?>><?php echo gettext("02.30 WIB"); ?></option>
						<option value="03.00 WIB" <?php if ($sJamSelesai == "03.00 WIB") { echo "selected"; } ?>><?php echo gettext("03.00 WIB"); ?></option>
						<option value="03.30 WIB" <?php if ($sJamSelesai == "03.30 WIB") { echo "selected"; } ?>><?php echo gettext("03.30 WIB"); ?></option>
						<option value="04.00 WIB" <?php if ($sJamSelesai == "04.00 WIB") { echo "selected"; } ?>><?php echo gettext("04.00 WIB"); ?></option>
						<option value="04.30 WIB" <?php if ($sJamSelesai == "04.30 WIB") { echo "selected"; } ?>><?php echo gettext("04.30 WIB"); ?></option>
						<option value="05.00 WIB" <?php if ($sJamSelesai == "05.00 WIB") { echo "selected"; } ?>><?php echo gettext("05.00 WIB"); ?></option>
						<option value="05.30 WIB" <?php if ($sJamSelesai == "05.30 WIB") { echo "selected"; } ?>><?php echo gettext("05.30 WIB"); ?></option>
						<option value="06.00 WIB" <?php if ($sJamSelesai == "06.00 WIB") { echo "selected"; } ?>><?php echo gettext("06.00 WIB"); ?></option>
						<option value="06.30 WIB" <?php if ($sJamSelesai == "06.30 WIB") { echo "selected"; } ?>><?php echo gettext("06.30 WIB"); ?></option>
						<option value="07.00 WIB" <?php if ($sJamSelesai == "07.00 WIB") { echo "selected"; } ?>><?php echo gettext("07.00 WIB"); ?></option>
						<option value="07.30 WIB" <?php if ($sJamSelesai == "07.30 WIB") { echo "selected"; } ?>><?php echo gettext("07.30 WIB"); ?></option>
						<option value="08.00 WIB" <?php if ($sJamSelesai == "08.00 WIB") { echo "selected"; } ?>><?php echo gettext("08.00 WIB"); ?></option>
						<option value="08.30 WIB" <?php if ($sJamSelesai == "08.30 WIB") { echo "selected"; } ?>><?php echo gettext("08.30 WIB"); ?></option>
						<option value="09.00 WIB" <?php if ($sJamSelesai == "09.00 WIB") { echo "selected"; } ?>><?php echo gettext("09.00 WIB"); ?></option>
						<option value="09.30 WIB" <?php if ($sJamSelesai == "09.30 WIB") { echo "selected"; } ?>><?php echo gettext("09.30 WIB"); ?></option>
						<option value="10.00 WIB" <?php if ($sJamSelesai == "10.00 WIB") { echo "selected"; } ?>><?php echo gettext("10.00 WIB"); ?></option>
						<option value="10.30 WIB" <?php if ($sJamSelesai == "10.30 WIB") { echo "selected"; } ?>><?php echo gettext("10.30 WIB"); ?></option>
						<option value="11.00 WIB" <?php if ($sJamSelesai == "11.00 WIB") { echo "selected"; } ?>><?php echo gettext("11.00 WIB"); ?></option>
						<option value="11.30 WIB" <?php if ($sJamSelesai == "11.30 WIB") { echo "selected"; } ?>><?php echo gettext("11.30 WIB"); ?></option>
						<option value="12.00 WIB" <?php if ($sJamSelesai == "12.00 WIB") { echo "selected"; } ?>><?php echo gettext("12.00 WIB"); ?></option>
						<option value="12.30 WIB" <?php if ($sJamSelesai == "12.30 WIB") { echo "selected"; } ?>><?php echo gettext("12.30 WIB"); ?></option>
						<option value="13.00 WIB" <?php if ($sJamSelesai == "13.00 WIB") { echo "selected"; } ?>><?php echo gettext("13.00 WIB"); ?></option>
						<option value="13.30 WIB" <?php if ($sJamSelesai == "13.30 WIB") { echo "selected"; } ?>><?php echo gettext("13.30 WIB"); ?></option>
						<option value="14.00 WIB" <?php if ($sJamSelesai == "14.00 WIB") { echo "selected"; } ?>><?php echo gettext("14.00 WIB"); ?></option>
						<option value="14.30 WIB" <?php if ($sJamSelesai == "14.30 WIB") { echo "selected"; } ?>><?php echo gettext("14.30 WIB"); ?></option>
						<option value="15.00 WIB" <?php if ($sJamSelesai == "15.00 WIB") { echo "selected"; } ?>><?php echo gettext("15.00 WIB"); ?></option>
						<option value="15.30 WIB" <?php if ($sJamSelesai == "15.30 WIB") { echo "selected"; } ?>><?php echo gettext("15.30 WIB"); ?></option>
						<option value="16.00 WIB" <?php if ($sJamSelesai == "16.00 WIB") { echo "selected"; } ?>><?php echo gettext("16.00 WIB"); ?></option>
						<option value="16.30 WIB" <?php if ($sJamSelesai == "16.30 WIB") { echo "selected"; } ?>><?php echo gettext("16.30 WIB"); ?></option>
						<option value="17.00 WIB" <?php if ($sJamSelesai == "17.00 WIB") { echo "selected"; } ?>><?php echo gettext("17.00 WIB"); ?></option>
						<option value="17.30 WIB" <?php if ($sJamSelesai == "17.30 WIB") { echo "selected"; } ?>><?php echo gettext("17.30 WIB"); ?></option>
						<option value="18.00 WIB" <?php if ($sJamSelesai == "18.00 WIB") { echo "selected"; } ?>><?php echo gettext("18.00 WIB"); ?></option>
						<option value="18.30 WIB" <?php if ($sJamSelesai == "18.30 WIB") { echo "selected"; } ?>><?php echo gettext("18.30 WIB"); ?></option>
						<option value="19.00 WIB" <?php if ($sJamSelesai == "19.00 WIB") { echo "selected"; } ?>><?php echo gettext("19.00 WIB"); ?></option>
						<option value="19.30 WIB" <?php if ($sJamSelesai == "19.30 WIB") { echo "selected"; } ?>><?php echo gettext("19.30 WIB"); ?></option>
						<option value="20.00 WIB" <?php if ($sJamSelesai == "20.00 WIB") { echo "selected"; } ?>><?php echo gettext("20.00 WIB"); ?></option>
						<option value="20.30 WIB" <?php if ($sJamSelesai == "20.30 WIB") { echo "selected"; } ?>><?php echo gettext("20.30 WIB"); ?></option>
						<option value="21.00 WIB" <?php if ($sJamSelesai == "21.00 WIB") { echo "selected"; } ?>><?php echo gettext("21.00 WIB"); ?></option>
						<option value="21.30 WIB" <?php if ($sJamSelesai == "21.30 WIB") { echo "selected"; } ?>><?php echo gettext("21.30 WIB"); ?></option>
						<option value="22.00 WIB" <?php if ($sJamSelesai == "22.00 WIB") { echo "selected"; } ?>><?php echo gettext("22.00 WIB"); ?></option>
						<option value="22.30 WIB" <?php if ($sJamSelesai == "22.30 WIB") { echo "selected"; } ?>><?php echo gettext("22.30 WIB"); ?></option>
						<option value="23.00 WIB" <?php if ($sJamSelesai == "23.00 WIB") { echo "selected"; } ?>><?php echo gettext("23.00 WIB"); ?></option>
						<option value="23.30 WIB" <?php if ($sJamSelesai == "23.30 WIB") { echo "selected"; } ?>><?php echo gettext("23.30 WIB"); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Hasil Akhir"); ?></b></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Hasil"); ?></td>
				<td colspan="4" class="TextColumn"><input   type="text" name="Hasil" id="Hasil" size="70" value="<?php echo htmlentities(stripslashes($sHasil),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sHasilError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Keterangan Tambahan"); ?></td>
				<td colspan="4" class="TextColumn"><input    type="text" name="Keterangan" id="Keterangan" size="70" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sKeteranganError ?></font></td>
			</tr>				
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext(" "); ?></b></td>
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
		$logvar2 = "Kegiatan Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iKegiatanKaryawan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
