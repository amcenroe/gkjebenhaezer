<?php
/*******************************************************************************
 *
 *  filename    : PelayanFirmanEditor.php
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
$sPageTitle = gettext("Jadwal Pelayan Firman");

//Get the PelayanFirmanID out of the querystring
$iPelayanFirmanID = FilterInput($_GET["PelayanFirmanID"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PelayanFirmanID= manually)
if (strlen($iPelayanFirmanID) > 0)
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

	$sPelayanFirmanID = FilterInput($_POST["PelayanFirmanID"]);
	$sSalutation = FilterInput($_POST["Salutation"]);
	$sBahasa = FilterInput($_POST["Bahasa"]);
	$sHal = FilterInput($_POST["Hal"]);
	$sSalutationPdt = FilterInput($_POST["SalutationPdt"]);
	$sPelayanFirman = FilterInput($_POST["PelayanFirman"]);
	$sGereja = FilterInput($_POST["Gereja"]);
	$sPFnonInstitusi = FilterInput($_POST["PFnonInstitusi"]);
	//$sPFNIAlamat = FilterInput($_POST["PFNIAlamat"]);
	$sPFNIAlamat = $_POST["PFNIAlamat"];
	$sPFNIAlamat2 = $_POST["PFNIAlamat2"];
	$sPFNITelp = FilterInput($_POST["PFNITelp"]);
	$sPFNIFax = FilterInput($_POST["PFNIFax"]);
	$sPFNIEmail = FilterInput($_POST["PFNIEmail"]);
	$sTanggalPF = FilterInput($_POST["TanggalPF"]);
	$sKodeTI = FilterInput($_POST["KodeTI"]);
	$sTempatPF = FilterInput($_POST["TempatPF"]);
	$sWaktuPF = FilterInput($_POST["WaktuPF"]);
	$sWaktuPF2 = FilterInput($_POST["WaktuPF2"]);
	$sKodeOrganis = FilterInput($_POST["KodeOrganis"]);
	$sKodeSongLeader = FilterInput($_POST["KodeSongLeader"]);
	
	$sTglFax = FilterInput($_POST["$TglFax"]);
	$sStatusFax = FilterInput($_POST["$StatusFax"]);
	$sTglSurat = FilterInput($_POST["$TglSurat"]);
	$sResiSurat = FilterInput($_POST["$ResiSurat"]);
	$sStatusSurat = FilterInput($_POST["$StatusSurat"]);
	$sTglTelp = FilterInput($_POST["$TglTelp"]);
	$sPenerimaTelp = FilterInput($_POST["$PenerimaTelp"]);
	$sStatusTelp = FilterInput($_POST["$StatusTelp"]);
	$sTglEmail = FilterInput($_POST["$TglEmail"]);
	
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
		if (strlen($iPelayanFirmanID) < 1)
		{
			 	
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO JadwalPelayanFirman ( 		
							
			Salutation,		
			Bahasa,
			Hal,
			PelayanFirman,
			Gereja,
			PFnonInstitusi,	
			PFNIAlamat,
			PFNIAlamat2,
			PFNITelp,
			PFNIFax,
			PFNIEmail,
			TanggalPF,
			KodeTI,			
			TempatPF,						
			WaktuPF,
			KodeOrganis,
			KodeSongLeader,
			TglFax,
			StatusFax,
			TglSurat,
			ResiSurat,
			StatusSurat,
			TglTelp,
			PenerimaTelp,
			StatusTelp,
			TglEmail,
			DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sSalutation . "',	
			'" . $sBahasa . "',	
			'" . $sHal . "',	
			'" . $sPelayanFirman . "',
			'" . $sGereja . "',
			'" . $sPFnonInstitusi . "',
			'" . $sPFNIAlamat . "',
			'" . $sPFNIAlamat2 . "',
			'" . $sPFNITelp . "',
			'" . $sPFNIFax . "',
			'" . $sPFNIEmail . "',
			'" . $sTanggalPF . "',
			'" . $sKodeTI . "',	
			'" . $sTempatPF . "',
			'" . $sWaktuPF . "',
			'" . $sKodeOrganis . "',
			'" . $sKodeSongLeader . "',
			'" . $sTglFax . "',
			'" . $sStatusFax . "',
			'" . $sTglSurat . "',
			'" . $sResiSurat . "',
			'" . $sStatusSurat . "',
			'" . $sTglTelp . "',
			'" . $sPenerimaTelp . "',
			'" . $sStatusTelp . "',
			'" . $sTglEmail . "',		
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			
		// Creating Data Persembahan

			$sSQL2 = "INSERT INTO Persembahangkjbekti ( 
				Tanggal,
				Pukul,
				KodeTI,
				Liturgi,
				Pengkotbah,
				DateEntered,				
				EnteredBy
			)
			VALUES ( 
			'" . $sTanggalPF . "',
			'" . $sWaktuPF . "',
			'" . $sKodeTI . "',
			'" . $sLiturgi . "',
			'" . $sPelayanFirman . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";		
				
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Surat Keluar Data";


		// Existing Baptis (update)
		} else {
			 
	//update the Baptis table
			
			$sSQL = "UPDATE JadwalPelayanFirman SET 

			Salutation = '" . $sSalutation . "',
			Bahasa = '" . $sBahasa . "',
			Hal = '" . $sHal . "',
			PelayanFirman = '" . $sPelayanFirman . "',
			Gereja = '" . $sGereja . "',
			PFnonInstitusi = '" . $sPFnonInstitusi . "',
			PFNIAlamat = '" . $sPFNIAlamat . "',
			PFNIAlamat2 = '" . $sPFNIAlamat2 . "',
			PFNITelp = '" . $sPFNITelp . "',
			PFNIFax = '" . $sPFNIFax . "',
			PFNIEmail = '" . $sPFNIEmail . "',
			TanggalPF = '" . $sTanggalPF . "',
			KodeTI = '" . $sKodeTI . "',
			TempatPF = '" . $sTempatPF . "',						
			WaktuPF = '" . $sWaktuPF . "',									
			KodeOrganis = '" . $sKodeOrganis . "',
			KodeSongLeader = '" . $sKodeSongLeader . "',
			TglFax = '" . $sTglFax  . "' ,
			StatusFax = '" . $sStatusFax  . "' ,
			TglSurat = '" . $sTglSurat  . "' ,
			ResiSurat = '" . $sResiSurat  . "' ,
			StatusSurat = '" . $sStatusSurat  . "' ,
			TglTelp = '" . $sTglTelp  . "' ,
			PenerimaTelp = '" . $sPenerimaTelp  . "' ,
			StatusTelp = '" . $sStatusTelp  . "' ,
			TglEmail = '" . $sTglEmail  . "' ,			
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE PelayanFirmanID = " . $iPelayanFirmanID;

		//	echo $sSQL;
	
			$sSQL2 = "";
			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Jadwal Pelayan Firman";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		if($sSQL2 ==""){ echo "";}else{	RunQuery($sSQL2);}
		
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPelayanFirmanID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PelayanFirmanEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPelayanFirmanID);
		}
		else if (isset($_POST["SuratSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectListApp.php?mode=JadwalPelayanFirman&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PelayanFirmanEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPelayanFirmanID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM JadwalPelayanFirman  WHERE PelayanFirmanID = " . $iPelayanFirmanID;
		$rsBaptis = RunQuery($sSQL);
		extract(mysql_fetch_array($rsBaptis));
		
		$sPelayanFirmanID = $PelayanFirmanID;
		$sBahasa = $Bahasa;
		$sHal = $Hal;
		$sSalutation = $Salutation;
		$sPelayanFirman = $PelayanFirman;
		$sGereja = $Gereja;
		$sPFnonInstitusi = $PFnonInstitusi;
		$sPFNIAlamat = $PFNIAlamat;
		$sPFNIAlamat2 = $PFNIAlamat2;
		$sPFNITelp = $PFNITelp;
		$sPFNIFax = $PFNIFax;
		$sPFNIEmail = $PFNIEmail;
		$sTanggalPF = $TanggalPF;
		$sKodeTI = $KodeTI;
		$sTempatPF = $TempatPF;						
		$sWaktuPF = $WaktuPF;	
		$sKodeOrganis = $KodeOrganis;
		$sKodeSongLeader = $KodeSongLeader;
	$sTglFax = $TglFax;
	$sStatusFax = $StatusFax;
	$sTglSurat = $TglSurat;
	$sResiSurat = $ResiSurat;
	$sStatusSurat = $StatusSurat;
	$sTglTelp = $TglTelp;
	$sPenerimaTelp = $PenerimaTelp;
	$sStatusTelp = $StatusTelp;
	$sTglEmail = $TglEmail;		
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today

	}
}


//Get Pendeta Names for the drop-down
$sSQL = "SELECT a.*, b.*, Salutation as SalutationPdt FROM DaftarPendeta a 
LEFT JOIN DaftarGerejaGKJ b ON a.GerejaID = b.GerejaID
ORDER BY PendetaID";
$rsNamaPendeta = RunQuery($sSQL);

//Get Lokasi TI Names for the drop-down
$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
$rsNamaTempatIbadah = RunQuery($sSQL);
// Get Nama Pejabat

//Get Organis for the drop-down
$sSQL = "Select  a.per_ID as KodeOrganis, a.per_FirstName as NamaOrganis ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 203
order by per_Firstname";
$rsNamaOrganis = RunQuery($sSQL);
//echo $rsNamaOrganis;

//Get Song leader for the drop-down
$sSQL = "Select  a.per_ID as KodeSongLeader, a.per_FirstName as NamaSongLeader ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 204
order by per_Firstname"; 
$rsNamaSongLeader = RunQuery($sSQL);
//echo $rsNamaSongleader;

// Get Nama Pejabat


require "Include/Header.php";

?>

<form method="post" action="PelayanFirmanEditor.php?PelayanFirmanID=<?php echo $iPelayanFirmanID; ?>" name="SuratEditor">

<table cellpadding="3" align="center" valign="top" border="0">

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="SuratSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"SuratSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iPelayanFirmanID) > 0) 
{ echo "SelectListApp.php?mode=JadwalPelayanFirman&amp;$refresh"; 
} else {echo "SelectListApp.php?mode=JadwalPelayanFirman&amp;$refresh"; 
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
		<td class="TextColumn" colspan="0"><input type="text" name="TanggalPF" value="<?php echo $sTanggalPF; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalPFError ?></font></td>
	
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
				<option value="17.00 WIB"  <?php if ($sWaktuPF == "17.00 WIB") { echo " selected"; }?> ><?php echo gettext("17.00 WIB CM3"); ?></option>
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
		<td class="LabelColumn"><?php echo gettext("Bahasa / Liturgi:"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="Bahasa" >
						<option value="Indonesia" <?php if ($sBahasa == "Indonesia") { echo " selected"; }?> ><?php echo gettext("Indonesia"); ?></option>
						<option value="Jawa" <?php if ($sBahasa == "Jawa") { echo " selected"; }?> ><?php echo gettext("Jawa"); ?></option>
						<option value="AltSore" <?php if ($sBahasa == "AltSore") { echo " selected"; }?> ><?php echo gettext("AltSore"); ?></option>
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
		<td class="LabelColumn"><?php echo gettext("Pelayan Firman (Pendeta GKJ) :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspan="3">
					<select name="PelayanFirman" >
						<option value="0" selected><?php echo gettext("Bukan Pendeta GKJ"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPendeta))
						{
							extract($aRow);

							echo "<option value=\"" . $PendetaID . "\"";
							if ($sPelayanFirman == $PendetaID) { echo " selected"; }
							echo ">" . $SalutationPdt ."" . $NamaPendeta . " - " . $NamaGereja;
						}
						?>

					</select>
		</td>
			</tr><tr>	
		<td class="LabelColumn"><?php echo gettext("Pelayan Firman(Jika bukan Pendeta atau Pendeta Non GKJ):"); ?></td>
		<td class="TextColumn" colspan="4">
		<select name="Salutation" >
						<option value="Bp." <?php if ($sSalutation == "Bp.") { echo " selected"; }?> ><?php echo gettext("Bp."); ?></option>
						<option value="Ibu." <?php if ($sSalutation == "Ibu.") { echo " selected"; }?> ><?php echo gettext("Ibu."); ?></option>
						<option value="Sdr." <?php if ($sSalutation == "Sdr.") { echo " selected"; }?> ><?php echo gettext("Sdr."); ?></option>
						<option value="Sdri." <?php if ($sSalutation == "Sdri.") { echo " selected"; }?> ><?php echo gettext("Sdri."); ?></option>
						
					</select>
		
		<input type="text" size=50 name="PFnonInstitusi" id="PFnonInstitusi" value="<?php echo htmlentities(stripslashes($sPFnonInstitusi),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPFnonInstitusiError ?></font>

		</td>

	</tr>	
	<tr>	
		<td class="LabelColumn" ><?php echo gettext("Nama Gereja/Institusi :(NonPendetaGKJ)"); ?></td>
		<td class="TextColumn" colspan="4"><input type="text" size=50 name="PFNIAlamat" id="PFNIAlamat" value="<?php echo htmlentities(stripslashes($sPFNIAlamat),ENT_NOQUOTES, "UTF-8"); ?>"></font>
		 Sinode : 		<select name="Gereja" >
						<option value="Lain" <?php if ($sGereja == "Lain") { echo " selected"; }?> ><?php echo gettext("Gereja Lainnya"); ?></option>
						<option value="GKI" <?php if ($sGereja == "GKI") { echo " selected"; }?> ><?php echo gettext("GKI"); ?></option>
						<option value="GPIB" <?php if ($sGereja == "GPIB") { echo " selected"; }?> ><?php echo gettext("GPIB"); ?></option>
						<option value="GSRI" <?php if ($sGereja == "GSRI") { echo " selected"; }?> ><?php echo gettext("GSRI"); ?></option>
						<option value="HKBP" <?php if ($sGereja == "HKBP") { echo " selected"; }?> ><?php echo gettext("HKBP"); ?></option>
						<option value="GKP" <?php if ($sGereja == "GKP") { echo " selected"; }?> ><?php echo gettext("GKP"); ?></option>
						<option value="GMII" <?php if ($sGereja == "GMII") { echo " selected"; }?> ><?php echo gettext("GMII"); ?></option>
						<option value="GSJA" <?php if ($sGereja == "GSJA") { echo " selected"; }?> ><?php echo gettext("GSJA"); ?></option>
					
					</select>
		<br><font color="red"><?php echo $sPFNIAlamatPFError ?>			
		</td>
	</tr>
	<tr>	
		<td class="LabelColumn" ></td>
		<td class="LabelColumn" ><i><?php echo gettext("Jika Penetua/Diaken $sChurchInitial isikan : $sChurchName"); ?></i></td>
	</tr>
	<tr>	
		<td class="LabelColumn" ><?php echo gettext("Alamat:(NonPendetaGKJ)"); ?></td>
		<td class="TextColumn" colspan="4"><input type="text" size=50 name="PFNIAlamat2" id="PFNIAlamat2" value="<?php echo htmlentities(stripslashes($sPFNIAlamat2),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPFNIAlamat2PFError ?></font></td>
	</tr>
	<tr>	
		<td class="LabelColumn" ><?php echo gettext("Telp:(NonPendetaGKJ)"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=30 name="PFNITelp" id="PFNITelp" value="<?php echo htmlentities(stripslashes($sPFNITelp),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPFNITelpPFError ?></font></td>
		<td class="LabelColumn" ><?php echo gettext("Fax:(NonPendetaGKJ)"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=30 name="PFNIFax" id="PFNIFax" value="<?php echo htmlentities(stripslashes($sPFNIFax),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPFNIFaxPFError ?></font></td>
	</tr>

		<tr>	
		<td class="LabelColumn"><?php echo gettext("Email:(NonPendetaGKJ)"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=50 name="PFNIEmail" id="PFNIEmail" value="<?php echo htmlentities(stripslashes($sPFNIEmail),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPFNIEmailPFError ?></font></td>
	</tr>
	<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Tempat Ibadah:"); ?></td>
				<td colspan="0" class="TextColumn">
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
       	
		<td class="LabelColumn"><?php echo gettext("Tempat Ibadah lainnya:"); ?></td>
		<td class="TextColumn" colspan="4"><input type="text" size=50 name="TempatPF" id="TempatPF" value="<?php echo htmlentities(stripslashes($sTempatPF),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatPFError ?></font></td>

	</tr>
			<tr>
				<td colspan="4" align="center"><h3><?php echo gettext("Tracking Korespondensi"); ?></h3></td>
			</tr>
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal FAX:"); ?></td>
		<td class="TextColumn" colspan=3><input type="text" name="TglFax" value="<?php echo $sTglFax; ?>" maxlength="10" id="sel31" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel31', 'y-mm-dd');" src="Images/calendar.gif"> <font color="red"><?php echo $sTglFaxError ?></font>
		<?php echo gettext("Status FAX:"); ?><input type="text" name="StatusFax" id="StatusFax"  value="<?php echo htmlentities(stripslashes($sStatusFax),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sStatusFaxError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Surat:"); ?></td>
		<td class="TextColumn" colspan=4><input type="text" name="TglSurat" value="<?php echo $sTglSurat; ?>" maxlength="10" id="sel32" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel32', 'y-mm-dd');" src="Images/calendar.gif"> <font color="red"><?php echo $sTglSuratError ?></font>
		<?php echo gettext("Nomor Resi:"); ?><input type="text" name="ResiSurat" id="ResiSurat"  value="<?php echo htmlentities(stripslashes($sResiSurat),ENT_NOQUOTES, "UTF-8"); ?>">
		<?php echo gettext("Pengirim Surat:"); ?><input type="text" name="StatusSurat" id="StatusSurat"  value="<?php echo htmlentities(stripslashes($sStatusSurat),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sStatusSuratError ?></font></td>
	
	</tr>
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Telp:"); ?></td>
		<td class="TextColumn" colspan=4 ><input type="text" name="TglTelp" value="<?php echo $sTglTelp; ?>" maxlength="10" id="sel33" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel33', 'y-mm-dd');" src="Images/calendar.gif"> <font color="red"><?php echo $sTglTelpError ?></font>
		<?php echo gettext("Penerima Telp :"); ?>
		<input type="text" name="PenerimaTelp" id="PenerimaTelp"  value="<?php echo htmlentities(stripslashes($sPenerimaTelp),ENT_NOQUOTES, "UTF-8"); ?>">
		<?php echo gettext("Status Telp :"); ?>
		<input type="text" name="StatusTelp" id="StatusTelp" value="<?php echo htmlentities(stripslashes($sStatusTelp),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sStatusTelpError ?></font></td>
	
	</tr>
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Email:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglEmail" value="<?php echo $sTglEmail; ?>" maxlength="10" id="sel34" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel34', 'y-mm-dd');" src="Images/calendar.gif"> <font color="red"><?php echo $sTglEmailError ?></font></td>
	
	</tr>
	
	</tr>



	
	</table>
</td>

	</form>

</table>

<?php
		$logvar1 = "Edit";
		$logvar2 = "Pelayanan Firman Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPelayanFirmanID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
