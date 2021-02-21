<?php
/*******************************************************************************
 *
 *  filename    : PelayanIbadahEditor.php
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
$sPageTitle = gettext("Jadwal Pelayan Ibadah");

//Get the PelayanIbadahID out of the querystring
$iPelayanIbadahID = FilterInput($_GET["PelayanIbadahID"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PelayanIbadahID= manually)
if (strlen($iPelayanIbadahID) > 0)
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

if (isset($_POST["SuratSubmit"]) || isset($_POST["SuratSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	

	$sPelayanIbadahID = FilterInput($_POST["PelayanIbadahID"]);
	$sSalutation = FilterInput($_POST["Salutation"]);
	$sBahasa = FilterInput($_POST["Bahasa"]);
	$sHal = FilterInput($_POST["Hal"]);
	$sSalutationPdt = FilterInput($_POST["SalutationPdt"]);
	$sPelayanIbadah = FilterInput($_POST["PelayanIbadah"]);
	$sPFnonInstitusi = FilterInput($_POST["PFnonInstitusi"]);
	$sPFNIAlamat = FilterInput($_POST["PFNIAlamat"]);
	$sPFNIEmail = FilterInput($_POST["PFNIEmail"]);
	$sTanggalPF = FilterInput($_POST["TanggalPF"]);
	$sKodeTI = FilterInput($_POST["KodeTI"]);
	$sTempatPF = FilterInput($_POST["TempatPF"]);
	$sWaktuPF = FilterInput($_POST["WaktuPF"]);
	$sWaktuPF2 = FilterInput($_POST["WaktuPF2"]);
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
		if (strlen($iPelayanIbadahID) < 1)
		{
			 	
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO JadwalPelayanIbadah ( 		
							
			Salutation,		
			Bahasa,
			Hal,
			PelayanIbadah,		
			PFnonInstitusi,	
			PFNIAlamat,
			PFNIEmail,
			TanggalPF,
			KodeTI,			
			TempatPF,						
			WaktuPF,									
			DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sSalutation . "',	
			'" . $sBahasa . "',	
			'" . $sHal . "',	
			'" . $sPelayanIbadah . "',
			'" . $sPFnonInstitusi . "',
			'" . $sPFNIAlamat . "',
			'" . $sPFNIEmail . "',
			'" . $sTanggalPF . "',
			'" . $sKodeTI . "',	
			'" . $sTempatPF . "',
			'" . $sWaktuPF . "',
		
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Surat Keluar Data";


		// Existing Baptis (update)
		} else {
			 
	//update the Baptis table
			
			$sSQL = "UPDATE JadwalPelayanIbadah SET 

			Salutation = '" . $sSalutation . "',
			Bahasa = '" . $sBahasa . "',
			Hal = '" . $sHal . "',
			PelayanIbadah = '" . $sPelayanIbadah . "',
			PFnonInstitusi = '" . $sPFnonInstitusi . "',
			PFNIAlamat = '" . $sPFNIAlamat . "',
			PFNIEmail = '" . $sPFNIEmail . "',
			TanggalPF = '" . $sTanggalPF . "',
			KodeTI = '" . $sKodeTI . "',
			TempatPF = '" . $sTempatPF . "',						
			WaktuPF = '" . $sWaktuPF . "',									

			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE PelayanIbadahID = " . $iPelayanIbadahID;

		//	echo $sSQL;
	

			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Jadwal Pelayan Ibadah";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPelayanIbadahID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PelayanIbadahEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPelayanIbadahID);
		}
		else if (isset($_POST["SuratSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectListApp.php?mode=JadwalPelayanIbadah&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PelayanIbadahEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPelayanIbadahID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM JadwalPelayanIbadah  WHERE PelayanIbadahID = " . $iPelayanIbadahID;
		$rsBaptis = RunQuery($sSQL);
		extract(mysql_fetch_array($rsBaptis));
		
		$sPelayanIbadahID = $PelayanIbadahID;
		$sBahasa = $Bahasa;
		$sHal = $Hal;
		$sSalutation = $Salutation;
		$sPelayanIbadah = $PelayanIbadah;
		$sPFnonInstitusi = $PFnonInstitusi;
		$sPFNIAlamat = $PFNIAlamat;
		$sPFNIEmail = $PFNIEmail;
		$sTanggalPF = $TanggalPF;
		$sKodeTI = $KodeTI;
		$sTempatPF = $TempatPF;						
		$sWaktuPF = $WaktuPF;									
		
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today

	}
}


//Get Pendeta Names for the drop-down
$sSQL = "SELECT *,Salutation as SalutationPdt FROM DaftarPendeta ORDER BY PendetaID";
$rsNamaPendeta = RunQuery($sSQL);

//Get Lokasi TI Names for the drop-down
$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
$rsNamaTempatIbadah = RunQuery($sSQL);
// Get Nama Pejabat



require "Include/Header.php";

?>

<form method="post" action="PelayanIbadahEditor.php?PelayanIbadahID=<?php echo $iPelayanIbadahID; ?>" name="SuratEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="SuratSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"SuratSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iPelayanIbadahID) > 0) 
{ echo "SelectListApp.php?mode=JadwalPelayanIbadah&amp;$refresh"; 
} else {echo "SelectListApp.php?mode=JadwalPelayanIbadah&amp;$refresh"; 
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
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Pelayanan Firman:"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="TanggalPF" value="<?php echo $sTanggalPF; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalPFError ?></font></td>
	</tr>
	<tr>	
		<td class="LabelColumn"><?php echo gettext("Pukul :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspan="3">
			<select name="WaktuPF" >
				<option value="0" <?php if ($sWaktuPF == "") { echo " selected"; }?> ><?php echo gettext("Tidak Diketahui"); ?></option>
				<option value="06.00 WIB"  <?php if ($sWaktuPF == "06.00 WIB") { echo " selected"; }?> ><?php echo gettext("06.00 WIB CM1"); ?></option>
				<option value="06.30 WIB"  <?php if ($sWaktuPF == "06.30 WIB") { echo " selected"; }?> ><?php echo gettext("06.30 WIB"); ?></option>
				<option value="07.00 WIB"  <?php if ($sWaktuPF == "07.00 WIB") { echo " selected"; }?> ><?php echo gettext("07.00 WIB CKRG"); ?></option>
				<option value="07.30 WIB"  <?php if ($sWaktuPF == "07.30 WIB") { echo " selected"; }?> ><?php echo gettext("07.30 WIB KRWG"); ?></option>
				<option value="08.00 WIB"  <?php if ($sWaktuPF == "08.00 WIB") { echo " selected"; }?> ><?php echo gettext("08.00 WIB"); ?></option>
				<option value="08.30 WIB"  <?php if ($sWaktuPF == "08.30 WIB") { echo " selected"; }?> ><?php echo gettext("08.30 WIB"); ?></option>
				<option value="09.00 WIB"  <?php if ($sWaktuPF == "09.00 WIB") { echo " selected"; }?> ><?php echo gettext("09.00 WIB CM2"); ?></option>
				<option value="09.30 WIB"  <?php if ($sWaktuPF == "09.30 WIB") { echo " selected"; }?> ><?php echo gettext("09.30 WIB"); ?></option>
				<option value="10.00 WIB"  <?php if ($sWaktuPF == "10.00 WIB") { echo " selected"; }?> ><?php echo gettext("10.00 WIB"); ?></option>
				<option value="10.30 WIB"  <?php if ($sWaktuPF == "10.30 WIB") { echo " selected"; }?> ><?php echo gettext("10.30 WIB"); ?></option>
				<option value="11.00 WIB"  <?php if ($sWaktuPF == "11.00 WIB") { echo " selected"; }?> ><?php echo gettext("11.00 WIB"); ?></option>
				<option value="11.30 WIB"  <?php if ($sWaktuPF == "11.30 WIB") { echo " selected"; }?> ><?php echo gettext("11.30 WIB"); ?></option>
				<option value="12.00 WIB"  <?php if ($sWaktuPF == "12.00 WIB") { echo " selected"; }?> ><?php echo gettext("12.00 WIB"); ?></option>
				<option value="12.30 WIB"  <?php if ($sWaktuPF == "12.30 WIB") { echo " selected"; }?> ><?php echo gettext("12.30 WIB"); ?></option>
				<option value="13.00 WIB"  <?php if ($sWaktuPF == "13.00 WIB") { echo " selected"; }?> ><?php echo gettext("13.00 WIB"); ?></option>
				<option value="13.30 WIB"  <?php if ($sWaktuPF == "13.30 WIB") { echo " selected"; }?> ><?php echo gettext("13.30 WIB"); ?></option>
				<option value="14.00 WIB"  <?php if ($sWaktuPF == "14.00 WIB") { echo " selected"; }?> ><?php echo gettext("14.00 WIB"); ?></option>
				<option value="14.30 WIB"  <?php if ($sWaktuPF == "14.30 WIB") { echo " selected"; }?> ><?php echo gettext("14.30 WIB"); ?></option>
				<option value="15.00 WIB"  <?php if ($sWaktuPF == "15.00 WIB") { echo " selected"; }?> ><?php echo gettext("15.00 WIB"); ?></option>
				<option value="15.30 WIB"  <?php if ($sWaktuPF == "15.30 WIB") { echo " selected"; }?> ><?php echo gettext("15.30 WIB"); ?></option>
				<option value="16.00 WIB"  <?php if ($sWaktuPF == "16.00 WIB") { echo " selected"; }?> ><?php echo gettext("16.00 WIB"); ?></option>
				<option value="16.30 WIB"  <?php if ($sWaktuPF == "16.30 WIB") { echo " selected"; }?> ><?php echo gettext("16.30 WIB"); ?></option>
				<option value="17.00 WIB"  <?php if ($sWaktuPF == "17.00 WIB") { echo " selected"; }?> ><?php echo gettext("17.00 WIB TMBN"); ?></option>
				<option value="17.30 WIB"  <?php if ($sWaktuPF == "17.30 WIB") { echo " selected"; }?> ><?php echo gettext("17.30 WIB"); ?></option>
				<option value="18.00 WIB"  <?php if ($sWaktuPF == "18.00 WIB") { echo " selected"; }?> ><?php echo gettext("18.00 WIB"); ?></option>
				<option value="18.30 WIB"  <?php if ($sWaktuPF == "18.30 WIB") { echo " selected"; }?> ><?php echo gettext("18.30 WIB"); ?></option>
				<option value="19.00 WIB"  <?php if ($sWaktuPF == "19.00 WIB") { echo " selected"; }?> ><?php echo gettext("19.00 WIB"); ?></option>
				<option value="19.30 WIB"  <?php if ($sWaktuPF == "19.30 WIB") { echo " selected"; }?> ><?php echo gettext("19.30 WIB"); ?></option>
				<option value="20.00 WIB"  <?php if ($sWaktuPF == "20.00 WIB") { echo " selected"; }?> ><?php echo gettext("20.00 WIB"); ?></option>
				<option value="20.30 WIB"  <?php if ($sWaktuPF == "20.30 WIB") { echo " selected"; }?> ><?php echo gettext("20.30 WIB"); ?></option>
				<option value="21.00 WIB"  <?php if ($sWaktuPF == "21.00 WIB") { echo " selected"; }?> ><?php echo gettext("21.00 WIB"); ?></option>
				<option value="21.30 WIB"  <?php if ($sWaktuPF == "21.30 WIB") { echo " selected"; }?> ><?php echo gettext("21.30 WIB"); ?></option>

				
			</select>
		</td>

	</tr>
	<tr>	
		<td class="LabelColumn"><?php echo gettext("Bahasa :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="Bahasa" >
						<option value="Indonesia" <?php if ($sBahasa == "Indonesia") { echo " selected"; }?> ><?php echo gettext("Indonesia"); ?></option>
						<option value="Jawa" <?php if ($sBahasa == "Jawa") { echo " selected"; }?> ><?php echo gettext("Jawa"); ?></option>
					</select>
		</td>
		<td class="LabelColumn"><?php echo gettext("Ibadah :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="Hal" >
				<option value="" <?php if ($sHal == " ") { echo " selected"; }?> ><?php echo gettext("Ibadah "); ?></option>
				<option value="dan Sakramen Perjamuan" <?php if ($sHal == "dan Sakramen Perjamuan") { echo " selected"; }?> ><?php echo gettext("Ibadah dengan Sakramen Perjamuan"); ?></option>
				<option value="dan Sakramen Baptisan" <?php if ($sHal == "dan Sakramen Baptisan") { echo " selected"; }?> ><?php echo gettext("Ibadah dengan Sakramen Baptisan"); ?></option>
				<option value="dan Sakramen Sidhi" <?php if ($sHal == "dan Sakramen Sidhi") { echo " selected"; }?> ><?php echo gettext("Ibadah dengan Sakramen Sidhi"); ?></option>
				<option value="dan Sakramen Pernikahan" <?php if ($sHal == "dan Sakramen Pernikahan") { echo " selected"; }?> ><?php echo gettext("Ibadah dengan Sakramen Pernikahan"); ?></option>
				<option value="dan Ibadah Syukur" <?php if ($sHal == "dan Ibadah Syukur") { echo " selected"; }?> ><?php echo gettext("Ibadah Syukur"); ?></option>
				<option value="/ Nara Sumber PA/PD/Sarasehan" <?php if ($sHal == "/ Nara Sumber PA/PD/Sarasehan") { echo " selected"; }?> ><?php echo gettext("Nara Sumber PA/PD/Sarasehan"); ?></option>

		</select>
		</td>
	</tr>	
				<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Tempat Ibadah:"); ?></td>
				<td colspan="3" class="TextColumn" colspan="4">
					<select name="KodeTI">
						<option value="0" selected><?php echo gettext("Tempat Ibadah Lain"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaTempatIbadah))
						{
							extract($aRow);

							echo "<option value=\"" . $KodeTI . "\"";
							if ($sKodeTI == $KodeTI) { echo " selected"; }
							echo ">" . $NamaTI . "&nbsp; - " .  $AlamatTI1 . "" . $AlamatTI2 . "-" . $KotaTI ;
						}
						?>

					</select>
				</td>
			</tr>
	<tr>	
		<td class="LabelColumn"><?php echo gettext("Tempat Ibadah lainnya:"); ?></td>
		<td class="TextColumn" colspan="4"><input type="text" size=50 name="TempatPF" id="TempatPF" value="<?php echo htmlentities(stripslashes($sTempatPF),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatPFError ?></font></td>

	</tr>
	<tr>
	<td class="LabelColumn"><?php echo gettext("Pendukung Ibadah Dewasa:"); ?></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Organis :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspan="3">
					<select name="Organis" >
						<option value="0" selected><?php echo gettext("Bukan Warga"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsOrganis))
						{
							extract($aRow);

							echo "<option value=\"" . $Organis . "\"";
							if ($sOrganis == $Organis) { echo " selected"; }
							echo ">" . $Organis . " - " . $Kelompok;
						}
						?>

					</select>

		<input type="text" size=30 name="Organis" id="Organis" value="<?php echo htmlentities(stripslashes($sOrganis),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sOrganisError ?></font></td>
		</td>
	</tr>	

	<tr>
		<td class="LabelColumn"><?php echo gettext("Song Leader :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspan="3">
					<select name="SongLeader" >
						<option value="0" selected><?php echo gettext("Bukan Warga"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsSongLeader))
						{
							extract($aRow);

							echo "<option value=\"" . $SongLeader . "\"";
							if ($sSongLeader == $SongLeader) { echo " selected"; }
							echo ">" . $SongLeader . " - " . $Kelompok;
						}
						?>

					</select>

		<input type="text" size=30 name="SongLeader" id="SongLeader" value="<?php echo htmlentities(stripslashes($sSongLeader),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sSongLeaderError ?></font></td>
		</td>
	</tr>	

	<tr>
	<td class="LabelColumn"><?php echo gettext("Ibadah Sekolah Minggu:"); ?></td>
	</tr>	
	
	
	<tr>
	<td class="LabelColumn"><?php echo gettext("Pengiring Musik Sekolah Minggu:"); ?></td>
	</tr>
	
	<tr>
	<td class="LabelColumn"><?php echo gettext("Ibadah Pra Remaja:"); ?></td>
	</tr>
	
	<tr>
	<td class="LabelColumn"><?php echo gettext("Ibadah Remaja:"); ?></td>
	</tr>
	
	<tr>
	<td class="LabelColumn"><?php echo gettext("Pelayan Pendukung Peribadahan:"); ?></td>
	</tr>
	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Pelayan Firman (Pendeta) :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspan="3">
					<select name="PelayanIbadah" >
						<option value="0" selected><?php echo gettext("Bukan Pendeta"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPendeta))
						{
							extract($aRow);

							echo "<option value=\"" . $PendetaID . "\"";
							if ($sPelayanIbadah == $PendetaID) { echo " selected"; }
							echo ">" . $SalutationPdt ."" . $NamaPendeta . " - " . $NamaGereja;
						}
						?>

					</select>
		</td>
	</tr>
	<tr>	
		<td class="LabelColumn"><?php echo gettext("Pelayan Firman(Jika bukan Pendeta):"); ?></td>
		<td class="TextColumn" colspan="4">
		<select name="Salutation" >
						<option value="Bp." <?php if ($sSalutation == "Bp.") { echo " selected"; }?> ><?php echo gettext("Bp."); ?></option>
						<option value="Ibu." <?php if ($sSalutation == "Ibu.") { echo " selected"; }?> ><?php echo gettext("Ibu."); ?></option>
						<option value="Sdr." <?php if ($sSalutation == "Sdr.") { echo " selected"; }?> ><?php echo gettext("Sdr."); ?></option>
						<option value="Sdri." <?php if ($sSalutation == "Sdri.") { echo " selected"; }?> ><?php echo gettext("Sdri."); ?></option>
						
					</select>
		
		<input type="text" size=50 name="PFnonInstitusi" id="PFnonInstitusi" value="<?php echo htmlentities(stripslashes($sPFnonInstitusi),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPFnonInstitusiError ?></font></td>

	</tr>	
	<tr>	
		<td class="LabelColumn" ><?php echo gettext("Alamat:(NonPendeta)"); ?></td>
		<td class="TextColumn" colspan="4"><input type="text" size=50 name="PFNIAlamat" id="PFNIAlamat" value="<?php echo htmlentities(stripslashes($sPFNIAlamat),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPFNIAlamatPFError ?></font></td>
	</tr>
		<tr>	
		<td class="LabelColumn"><?php echo gettext("Email:(NonPendeta)"); ?></td>
		<td class="TextColumn" colspan="4"><input type="text" size=50 name="PFNIEmail" id="PFNIEmail" value="<?php echo htmlentities(stripslashes($sPFNIEmail),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPFNIEmailPFError ?></font></td>
	</tr>


	<tr><td>
		</td></tr>
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
		$logvar2 = "Pelayan Ibadah Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPelayanIbadahID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
