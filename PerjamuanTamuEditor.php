<?php
/*******************************************************************************
 *
 *  filename    : PerjamuanTamuEditor.php
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
$sPageTitle = gettext("Surat Keterangan Perjamuan Kudus untuk Warga Tamu");
$iPersonID = FilterInput($_GET["PersonID"],'int');
//Get the PerjamuanID out of the querystring
$iPerjamuanID = FilterInput($_GET["PerjamuanID"],'int');

$TAHUN=(DATE("Y"));
$BULAN=dec2roman(date (m));
// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PerjamuanID= manually)
if (strlen($iPerjamuanID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsPerjamuan = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerjamuan));

	if (mysql_num_rows($rsPerjamuan) == 0)
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

if (isset($_POST["PerjamuanSubmit"]) || isset($_POST["PerjamuanSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sPerjamuanID = FilterInput($_POST["PerjamuanID"]);
	$sper_ID = FilterInput($_POST["per_ID"]);
	$sPendeta = FilterInput($_POST["Pendeta"]);
	$sKetuaMajelis = FilterInput($_POST["KetuaMajelis"]);
	$sSekretarisMajelis = FilterInput($_POST["SekretarisMajelis"]);
	$sTanggalPerjamuan = FilterInput($_POST["TanggalPerjamuan"]);
	$sKeterangan = FilterInput($_POST["Keterangan"]);
	
	$sNama = FilterInput($_POST["Nama"]);
	$sAlamat = FilterInput($_POST["Alamat"]);
	$sTelp = FilterInput($_POST["Telp"]);
	$sKodeTI = FilterInput($_POST["KodeTI"]);
	$sJamPerjamuan = FilterInput($_POST["JamPerjamuan"]);

	
	$sGerejaID = FilterInput($_POST["GerejaID"]);
	$sNamaGerejaNonGKJ = FilterInput($_POST["NamaGerejaNonGKJ"]);
	$sAlamat1NonGKJ = FilterInput($_POST["Alamat1NonGKJ"]);
	$sAlamat2NonGKJ = FilterInput($_POST["Alamat2NonGKJ"]);
	$sAlamat3NonGKJ = FilterInput($_POST["Alamat3NonGKJ"]);
	$sTelpNonGKJ = FilterInput($_POST["TelpNonGKJ"]);
	$sFaxNonGKJ = FilterInput($_POST["FaxNonGKJ"]);
	$sEmailNonGKJ = FilterInput($_POST["EmailNonGKJ"]);
	
	$sPerjamuanKe = FilterInput($_POST["PerjamuanKe"]);
	$sReffSuratPerjamuan = FilterInput($_POST["ReffSuratPerjamuan"]);
	$sAlasanPerjamuan = FilterInput($_POST["AlasanPerjamuan"]);
	
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
								. gettext("Not a valid Friend Date") . "</span>";
			$bErrorFlag = true;
		} else {
			$dTanggal = $dateString;
		}
	}
	//If no errors, then let's update...
		// New Data (add)
		if (strlen($iPerjamuanID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

				
				
				
			$sSQL = "INSERT INTO PerjamuanKudusTamugkjbekti ( 
			per_ID,
			TanggalPerjamuan,
			Pendeta,
			KetuaMajelis,
			SekretarisMajelis,
			Keterangan,
			Nama,
			Alamat,
			KodeTI,
			JamPerjamuan,
			Telp,
			GerejaID,
			NamaGerejaNonGKJ,
			Alamat1NonGKJ,
			Alamat2NonGKJ,
			Alamat3NonGKJ,
			TelpNonGKJ,
			FaxNonGKJ,
			EmailNonGKJ,
			DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sper_ID . "',
			'" . $sTanggalPerjamuan . "',
			'" . $sPendeta . "',
			'" . $sKetuaMajelis . "',
			'" . $sSekretarisMajelis . "',
			'" . $sKeterangan . "',
			'" . $sNama . "',
			'" . $sAlamat . "',
			'" . $sKodeTI . "',
			'" . $sJamPerjamuan . "',	
			'" . $sTelp . "',			
			'" . $sGerejaID . "',
			'" . $sNamaGerejaNonGKJ . "',
			'" . $sAlamat1NonGKJ . "',
			'" . $sAlamat2NonGKJ . "',
			'" . $sAlamat3NonGKJ . "',
			'" . $sTelpNonGKJ . "',
			'" . $sFaxNonGKJ . "',
			'" . $sEmailNonGKJ . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Warga Perjamuan Data";


		// Existing Perjamuan (update)
		} else {
	//update the Perjamuan table
	
	
			
			
			
			$sSQL = "UPDATE PerjamuanKudusTamugkjbekti SET 
			
					per_ID = '" . $sper_ID . "',
					TanggalPerjamuan = '" . $sTanggalPerjamuan . "',
					Pendeta = '" . $sPendeta . "',
					KetuaMajelis = '" . $sKetuaMajelis . "',
					SekretarisMajelis = '" . $sSekretarisMajelis . "',
					Keterangan = '" . $sKeterangan . "',
					Nama = '" . $sNama . "',
					Alamat = '" . $sAlamat . "',
					KodeTI = '" . $sKodeTI . "',
					JamPerjamuan = '" . $sJamPerjamuan. "',
					Telp = '" . $sTelp . "',
					GerejaID = '" . $sGerejaID . "',
					NamaGerejaNonGKJ = '" . $sNamaGerejaNonGKJ . "',
					Alamat1NonGKJ = '" . $sAlamat1NonGKJ . "',
					Alamat2NonGKJ = '" . $sAlamat2NonGKJ . "',
					Alamat3NonGKJ = '" . $sAlamat3NonGKJ . "',
					TelpNonGKJ = '" . $sTelpNonGKJ . "',
					FaxNonGKJ = '" . $sFaxNonGKJ . "',
					EmailNonGKJ = '" . $sEmailNonGKJ . "',
					DateLastEdited = '" . date("YmdHis") . "',
					EditedBy = '" . $_SESSION['iUserID'] ;
	

	
			$sSQL .= "' WHERE PerjamuanID = " . $iPerjamuanID;

		//	echo $sSQL;
			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Warga Perjamuan Data";
		}

		//Execute the SQL
		RunQuery($sSQL);


//c43 	PerjamuanKe
//c44 	Tanggal Penitipan/Perawatan Rohani
//c45 	Alasan Penitipan/Perawatan Rohani		
//c46 surat reff		
		
//	".strftime( "%Y", strtotime($TGL))"	
					// update the main database
	//			$sSQLGKJ = "SELECT * FROM DaftarGerejaGKJ  WHERE GerejaID = " . $sGerejaID . " LIMIT 1";
	//			$rsGKJ = RunQuery($sSQLGKJ);
	//			extract(mysql_fetch_array($rsGKJ));
	//			$sNamaGereja = $NamaGereja;	
	
			//Ambil data utk reff surat
	//			$sSQL0 = "SELECT * FROM PermohonanPerjamuanKudusTamugkjbekti  WHERE per_ID = " . $sper_ID . " LIMIT 1";
	//			$rsNoReff = RunQuery($sSQL0);
	//			extract(mysql_fetch_array($rsNoReff));
	//			$sNomorReff = $PerjamuanID;			
				

				// set klasifikasi Perjamuan	
	//	$sSQL1 = "UPDATE person_per SET per_cls_id = '2' WHERE per_ID=" . $sper_ID;
	//	RunQuery($sSQL1);	
		
		
		
			// set nama gereja tujuan dan alasan
			// SKPR = Surat Keterangan Perawatan Rohani
	//	$sSQL2 = "UPDATE person_custom  SET 
	//				c44 = '" . $sTanggalPerjamuan . "',					
	//				c43 = '" . $sNamaGereja. "',
	//				c45 = '" . $sAlasanPerjamuan ."',
	//				c46 = '" . $sNomorReff. "e/SKPR/".$sChurchCode."/".$BULAN."/".$TAHUN."' WHERE per_ID =" . $sper_ID;
					
		//echo $sSQL2;
	//	RunQuery($sSQL2);
		
		

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPerjamuanID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PerjamuanTamuEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPerjamuanID);
		}
		else if (isset($_POST["PerjamuanSubmit"]))
		{
			//Send to the view of this Perjamuan
			Redirect("SelectListApp.php?mode=Perjamuan&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PerjamuanTamuEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPerjamuanID) > 0)
	{
		//Editing....
		//Get all the data on this record

//c43 	PerjamuanKe
//c44 	Tanggal Penitipan/Perawatan Rohani
//c45 	Alasan Penitipan/Perawatan Rohani		
//c46 surat reff

		$sSQL = "SELECT *  FROM PerjamuanKudusTamugkjbekti  a
		WHERE PerjamuanID = " . $iPerjamuanID;
		$rsPerjamuan = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPerjamuan));

		$sPerjamuanID = $PerjamuanID;
		$sper_ID = $per_ID;
		$sTanggalPerjamuan = $TanggalPerjamuan;
		$sPendeta = $Pendeta;
		$sKetuaMajelis = $KetuaMajelis;
		$sSekretarisMajelis = $SekretarisMajelis;
		$sKeterangan = $Keterangan;
		$sNama = $Nama;		
		
		$sAlamat = $Alamat;	
		$sKodeTI = $KodeTI;	
		$sJamPerjamuan = $JamPerjamuan;	
		$sTelp = $Telp;
		
		$sGerejaID = $GerejaID;
		$sNamaGerejaNonGKJ = $NamaGerejaNonGKJ;	
		$sAlamat1NonGKJ = $Alamat1NonGKJ;	
		$sAlamat2NonGKJ = $Alamat2NonGKJ;	
		$sAlamat3NonGKJ = $Alamat3NonGKJ;	
		$sTelpNonGKJ = $TelpNonGKJ;
		$sFaxNonGKJ = $FaxNonGKJ;
		$sEmailNonGKJ = $EmailNonGKJ;
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today

	}
}

//Get Pendeta Names for the drop-down
$sSQL = "SELECT * FROM DaftarPendeta a
LEFT JOIN DaftarGerejaGKJ b ON a.GerejaID=b.GerejaID
ORDER BY PendetaID";
$rsNamaPendeta = RunQuery($sSQL);

//Get Daftar GKJ Names for the drop-down
$sSQL = "SELECT * FROM DaftarGerejaGKJ a 
LEFT JOIN DaftarKlasisGKJ b ON a.KlasisID=b.KlasisID WHERE GerejaID > 1 
ORDER BY GerejaID, NamaGereja";
$rsNamaGereja = RunQuery($sSQL);

//Get Lokasi TI Names for the drop-down
$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
$rsNamaTempatIbadah = RunQuery($sSQL);

// Get Nama Pejabat

require "Include/Header.php";

?>

<form method="post" action="PerjamuanTamuEditor.php?PerjamuanID=<?php echo $iPerjamuanID; ?>" name="PerjamuanEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="PerjamuanSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"PerjamuanSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iPerjamuanID) > 0) 
{ echo "SelectListApp.php?mode=Perjamuan&amp;$refresh"; 
} else {echo "SelectListApp.php?mode=Perjamuan&amp;$refresh"; 
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
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Perjamuan:"); ?></td>
		<td class="TextColumn" colspan="3" ><input type="text" name="TanggalPerjamuan" value="<?php echo $sTanggalPerjamuan; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalPerjamuanError ?></font></td>
			<td class="LabelColumn"><?php echo gettext("Pukul :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspan="3">
			<select name="JamPerjamuan" >
				<option value="0" <?php if ($sJamPerjamuan == "") { echo " selected"; }?> ><?php echo gettext("Tidak Diketahui"); ?></option>
				<option value="06.00 WIB"  <?php if ($sJamPerjamuan == "06.00 WIB") { echo " selected"; }?> ><?php echo gettext("06.00 WIB CM1"); ?></option>
				<option value="06.30 WIB"  <?php if ($sJamPerjamuan == "06.30 WIB") { echo " selected"; }?> ><?php echo gettext("06.30 WIB"); ?></option>
				<option value="07.00 WIB"  <?php if ($sJamPerjamuan == "07.00 WIB") { echo " selected"; }?> ><?php echo gettext("07.00 WIB CKRG"); ?></option>
				<option value="07.30 WIB"  <?php if ($sJamPerjamuan == "07.30 WIB") { echo " selected"; }?> ><?php echo gettext("07.30 WIB KRWG"); ?></option>
				<option value="08.00 WIB"  <?php if ($sJamPerjamuan == "08.00 WIB") { echo " selected"; }?> ><?php echo gettext("08.00 WIB"); ?></option>
				<option value="08.30 WIB"  <?php if ($sJamPerjamuan == "08.30 WIB") { echo " selected"; }?> ><?php echo gettext("08.30 WIB"); ?></option>
				<option value="09.00 WIB"  <?php if ($sJamPerjamuan == "09.00 WIB") { echo " selected"; }?> ><?php echo gettext("09.00 WIB CM2"); ?></option>
				<option value="09.30 WIB"  <?php if ($sJamPerjamuan == "09.30 WIB") { echo " selected"; }?> ><?php echo gettext("09.30 WIB"); ?></option>
				<option value="10.00 WIB"  <?php if ($sJamPerjamuan == "10.00 WIB") { echo " selected"; }?> ><?php echo gettext("10.00 WIB"); ?></option>
				<option value="10.30 WIB"  <?php if ($sJamPerjamuan == "10.30 WIB") { echo " selected"; }?> ><?php echo gettext("10.30 WIB"); ?></option>
				<option value="11.00 WIB"  <?php if ($sJamPerjamuan == "11.00 WIB") { echo " selected"; }?> ><?php echo gettext("11.00 WIB"); ?></option>
				<option value="11.30 WIB"  <?php if ($sJamPerjamuan == "11.30 WIB") { echo " selected"; }?> ><?php echo gettext("11.30 WIB"); ?></option>
				<option value="12.00 WIB"  <?php if ($sJamPerjamuan == "12.00 WIB") { echo " selected"; }?> ><?php echo gettext("12.00 WIB"); ?></option>
				<option value="12.30 WIB"  <?php if ($sJamPerjamuan == "12.30 WIB") { echo " selected"; }?> ><?php echo gettext("12.30 WIB"); ?></option>
				<option value="13.00 WIB"  <?php if ($sJamPerjamuan == "13.00 WIB") { echo " selected"; }?> ><?php echo gettext("13.00 WIB"); ?></option>
				<option value="13.30 WIB"  <?php if ($sJamPerjamuan == "13.30 WIB") { echo " selected"; }?> ><?php echo gettext("13.30 WIB"); ?></option>
				<option value="14.00 WIB"  <?php if ($sJamPerjamuan == "14.00 WIB") { echo " selected"; }?> ><?php echo gettext("14.00 WIB"); ?></option>
				<option value="14.30 WIB"  <?php if ($sJamPerjamuan == "14.30 WIB") { echo " selected"; }?> ><?php echo gettext("14.30 WIB"); ?></option>
				<option value="15.00 WIB"  <?php if ($sJamPerjamuan == "15.00 WIB") { echo " selected"; }?> ><?php echo gettext("15.00 WIB"); ?></option>
				<option value="15.30 WIB"  <?php if ($sJamPerjamuan == "15.30 WIB") { echo " selected"; }?> ><?php echo gettext("15.30 WIB"); ?></option>
				<option value="16.00 WIB"  <?php if ($sJamPerjamuan == "16.00 WIB") { echo " selected"; }?> ><?php echo gettext("16.00 WIB"); ?></option>
				<option value="16.30 WIB"  <?php if ($sJamPerjamuan == "16.30 WIB") { echo " selected"; }?> ><?php echo gettext("16.30 WIB"); ?></option>
				<option value="17.00 WIB"  <?php if ($sJamPerjamuan == "17.00 WIB") { echo " selected"; }?> ><?php echo gettext("17.00 WIB TMBN"); ?></option>
				<option value="17.30 WIB"  <?php if ($sJamPerjamuan == "17.30 WIB") { echo " selected"; }?> ><?php echo gettext("17.30 WIB"); ?></option>
				<option value="18.00 WIB"  <?php if ($sJamPerjamuan == "18.00 WIB") { echo " selected"; }?> ><?php echo gettext("18.00 WIB"); ?></option>
				<option value="18.30 WIB"  <?php if ($sJamPerjamuan == "18.30 WIB") { echo " selected"; }?> ><?php echo gettext("18.30 WIB"); ?></option>
				<option value="19.00 WIB"  <?php if ($sJamPerjamuan == "19.00 WIB") { echo " selected"; }?> ><?php echo gettext("19.00 WIB"); ?></option>
				<option value="19.30 WIB"  <?php if ($sJamPerjamuan == "19.30 WIB") { echo " selected"; }?> ><?php echo gettext("19.30 WIB"); ?></option>
				<option value="20.00 WIB"  <?php if ($sJamPerjamuan == "20.00 WIB") { echo " selected"; }?> ><?php echo gettext("20.00 WIB"); ?></option>
				<option value="20.30 WIB"  <?php if ($sJamPerjamuan == "20.30 WIB") { echo " selected"; }?> ><?php echo gettext("20.30 WIB"); ?></option>
				<option value="21.00 WIB"  <?php if ($sJamPerjamuan == "21.00 WIB") { echo " selected"; }?> ><?php echo gettext("21.00 WIB"); ?></option>
				<option value="21.30 WIB"  <?php if ($sJamPerjamuan == "21.30 WIB") { echo " selected"; }?> ><?php echo gettext("21.30 WIB"); ?></option>

				
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

	<tr><td><tr>
		<td class="LabelColumn"><?php echo gettext("Pendeta:"); ?></td>
		<td class="TextColumn"><input type="text" name="Pendeta" id="Pendeta" 
		value="<?php 
		if (strlen($iPerjamuanID) > 0)
		{ echo htmlentities(stripslashes($sPendeta),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		echo jabatanpengurus(1);
		}
		 ?>"><br><font color="red"><?php echo $sPendetaError ?></font></td>
	
		<td class="LabelColumn"><?php echo gettext("Ketua Majelis:"); ?></td>
		<td class="TextColumn"><input type="text" name="KetuaMajelis" id="KetuaMajelis" 
		value="<?php 
		if (strlen($iPerjamuanID) > 0)
		{ echo htmlentities(stripslashes($sKetuaMajelis),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		echo jabatanpengurus(61);
		}
		 ?>"><br><font color="red"><?php echo $sKetuaMajelisError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Sekretaris Majelis:"); ?></td>
		<td class="TextColumn"><input type="text" name="SekretarisMajelis" id="SekretarisMajelis" 
		value="<?php 
		if (strlen($iPerjamuanID) > 0)
		{ echo htmlentities(stripslashes($sSekretarisMajelis),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		echo jabatanpengurus(65);
		}
		 ?>"><br><font color="red"><?php echo $sSekretarisMajelisError ?></font></td>
		</tr>	
		</td></tr>
	</tr>
	<tr>
			<td colspan="6" align="center"><h3><?php echo gettext("Isikan Data Warga Tamu"); ?></h3></td>
	</tr>	
	<tr>
		<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Nama Tamu:"); ?></td>
		<td class="TextColumn" colspan="5" ><input type="text" size="100" name="Nama" id="Nama" value="<?php echo htmlentities(stripslashes($sNama),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Alamat :"); ?></td>
		<td class="TextColumn" colspan="5" ><input type="text" size="100" name="Alamat" id="Alamat" value="<?php echo htmlentities(stripslashes($sAlamat),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamatError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Telp"); ?></td>
		<td class="TextColumn" colspan="5" ><input type="text" size="60" name="Telp" id="Telp" value="<?php echo htmlentities(stripslashes($sTelp),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTelpError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan:"); ?></td>
		<td class="TextColumn" colspan="5" ><input type="text" size="80" name="Keterangan" id="Keterangan" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKeteranganError ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Warga Gereja :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspan="3" >
					<select name="GerejaID" >
						<option value="0" selected><?php echo gettext("Non GKJ"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaGereja))
						{
							extract($aRow);

							echo "<option value=\"" . $GerejaID . "\"";
							if ($sGerejaID == $GerejaID) { echo " selected"; }
							echo ">" . $NamaGereja." - ".$NamaKlasis;
						}
						?>
					</select>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nama Gereja non GKJ:"); ?></td>
		<td class="TextColumn"><input type="text" name="NamaGerejaNonGKJ" id="NamaGerejaNonGKJ" value="<?php echo htmlentities(stripslashes($sNamaGerejaNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaGerejaNonGKJError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Telp:"); ?></td>
		<td class="TextColumn"><input type="text" name="TelpNonGKJ" id="TelpNonGKJ" value="<?php echo htmlentities(stripslashes($sTelpNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTelpNonGKJError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Alamat Gereja non GKJ:"); ?></td>
		<td class="TextColumn"><input type="text" name="Alamat1NonGKJ" id="Alamat1NonGKJ" value="<?php echo htmlentities(stripslashes($sAlamat1NonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat1NonGKJError ?></font></td>
	
		<td class="LabelColumn"><?php echo gettext("Fax:"); ?></td>
		<td class="TextColumn"><input type="text" name="FaxNonGKJ" id="FaxNonGKJ" value="<?php echo htmlentities(stripslashes($sFaxNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sFaxNonGKJError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kota/Kab:"); ?></td>
		<td class="TextColumn"><input type="text" name="Alamat2NonGKJ" id="Alamat2NonGKJ" value="<?php echo htmlentities(stripslashes($sAlamat2NonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat2NonGKJError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Email:"); ?></td>
		<td class="TextColumn"><input type="text" name="EmailNonGKJ" id="EmailNonGKJ" value="<?php echo htmlentities(stripslashes($sEmailNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sEmailNonGKJError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kode POS:"); ?></td>
		<td class="TextColumn"><input type="text" name="Alamat3NonGKJ" id="Alamat3NonGKJ" value="<?php echo htmlentities(stripslashes($sAlamat3NonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat3NonGKJError ?></font></td>
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
		$logvar2 = "Pendaftaran Perjamuan Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPerjamuanID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
//require "Include/Footer.php";
?>
