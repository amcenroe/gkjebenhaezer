<?php
/*******************************************************************************
 *
 *  filename    : LiturgiEditor.php
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
$sPageTitle = gettext("Liturgi  $sChurchName");

//Get the LiturgiID out of the querystring
$iLiturgiID = FilterInput($_GET["LiturgiID"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?LiturgiID= manually)
if (strlen($iLiturgiID) > 0)
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

if (isset($_POST["LiturgiSubmit"]) || isset($_POST["LiturgiSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sLiturgiID = FilterInput($_POST["LiturgiID"]);
	$sTanggal = FilterInput($_POST["Tanggal"]);
	$sWarna = FilterInput($_POST["Warna"]);
	$sBahasa = FilterInput($_POST["Bahasa"]);
	$sKeterangan = FilterInput($_POST["Keterangan"]);
	$sTema = FilterInput($_POST["Tema"]);
	$sBacaan1 = FilterInput($_POST["Bacaan1"]);
	$sBacaanAntara = FilterInput($_POST["BacaanAntara"]);
	$sBacaan2 = FilterInput($_POST["Bacaan2"]);
	$sBacaanInjil = FilterInput($_POST["BacaanInjil"]);
	$sAyatPenuntunHK = FilterInput($_POST["AyatPenuntunHK"]);
	$sAyatPenuntunBA = FilterInput($_POST["AyatPenuntunBA"]);
	$sAyatPenuntunLM = FilterInput($_POST["AyatPenuntunLM"]);
	$sAyatPenuntunP = FilterInput($_POST["AyatPenuntunP"]);
	$sAyatPenuntunNP = FilterInput($_POST["AyatPenuntunNP"]);
	$sNyanyian1 = FilterInput($_POST["Nyanyian1"]);
	$sNyanyian2 = FilterInput($_POST["Nyanyian2"]);
	$sNyanyian3 = FilterInput($_POST["Nyanyian3"]);
	$sNyanyian4 = FilterInput($_POST["Nyanyian4"]);
	$sNyanyian5 = FilterInput($_POST["Nyanyian5"]);
	$sNyanyian6 = FilterInput($_POST["Nyanyian6"]);
	$sNyanyian7 = FilterInput($_POST["Nyanyian7"]);
	$sNyanyian8 = FilterInput($_POST["Nyanyian8"]);

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
		if (strlen($iLiturgiID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO LiturgiGKJBekti ( 		

			Tanggal,
			Warna,
			Bahasa,
			Keterangan,
			Tema,
			Bacaan1,
			BacaanAntara,
			Bacaan2,
			BacaanInjil,
			AyatPenuntunHK,
			AyatPenuntunBA,
			AyatPenuntunLM,
			AyatPenuntunP,
			AyatPenuntunNP,
			Nyanyian1,
			Nyanyian2,
			Nyanyian3,
			Nyanyian4,
			Nyanyian5,
			Nyanyian6,
			Nyanyian7,
			Nyanyian8,			
			
			DateEntered,
			EnteredBy	)
			VALUES ( 

			'" . $sTanggal . "',
			'" . $sWarna . "',
			'" . $sBahasa . "',
			'" . $sKeterangan . "',
			'" . $sTema . "',
			'" . $sBacaan1 . "',
			'" . $sBacaanAntara . "',
			'" . $sBacaan2 . "',
			'" . $sBacaanInjil . "',
			'" . $sAyatPenuntunHK . "',
			'" . $sAyatPenuntunBA . "',
			'" . $sAyatPenuntunLM . "',
			'" . $sAyatPenuntunP . "',
			'" . $sAyatPenuntunNP . "',
			'" . $sNyanyian1 . "',
			'" . $sNyanyian2 . "',
			'" . $sNyanyian3 . "',
			'" . $sNyanyian4 . "',
			'" . $sNyanyian5 . "',
			'" . $sNyanyian6 . "',
			'" . $sNyanyian7 . "',
			'" . $sNyanyian8 . "',
			
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Liturgi Data";


		// Existing Baptis (update)
		} else {
	//update the Baptis table
			$sSQL = "UPDATE LiturgiGKJBekti SET 
	
			Tanggal = '" . $sTanggal . "',
			Warna = '" . $sWarna . "',
			Bahasa = '" . $sBahasa . "',
			Keterangan = '" . $sKeterangan . "',
			Tema = '" . $sTema . "',
			Bacaan1 = '" . $sBacaan1 . "',
			BacaanAntara = '" . $sBacaanAntara . "',
			Bacaan2 = '" . $sBacaan2 . "',
			BacaanInjil = '" . $sBacaanInjil . "',
			AyatPenuntunHK = '" . $sAyatPenuntunHK . "',
			AyatPenuntunBA = '" . $sAyatPenuntunBA . "',
			AyatPenuntunLM = '" . $sAyatPenuntunLM . "',
			AyatPenuntunP = '" . $sAyatPenuntunP . "',
			AyatPenuntunNP = '" . $sAyatPenuntunNP . "',
			Nyanyian1 = '" . $sNyanyian1 . "',
			Nyanyian2 = '" . $sNyanyian2 . "',
			Nyanyian3 = '" . $sNyanyian3 . "',
			Nyanyian4 = '" . $sNyanyian4 . "',
			Nyanyian5 = '" . $sNyanyian5 . "',
			Nyanyian6 = '" . $sNyanyian6 . "',
			Nyanyian7 = '" . $sNyanyian7 . "',
			Nyanyian8 = '" . $sNyanyian8 . "',
			
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE LiturgiID = " . $iLiturgiID;

		//	echo $sSQL;
	

			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Data Liturgi";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		
					// update the main database
		//		$sSQL2 = "UPDATE person_custom  SET 
		//			c1 = '" . $sTanggalBaptis . "',					
		//			c26 = 'Gereja Kristen Jawa Bekasi Timur',
		//			c37 = '" . $sPendetaPF  ;
		//						$sSQL2 .= "' WHERE per_ID = " . $sper_ID;
		
	//	echo $sSQL2;
		
	//	RunQuery($sSQL2);
	
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iLiturgiID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. LiturgiEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iLiturgiID);
		}
		else if (isset($_POST["LiturgiSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectListApp.php?mode=Liturgi&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("LiturgiEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iLiturgiID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM LiturgiGKJBekti  WHERE LiturgiID = " . $iLiturgiID;
		$rsBaptis = RunQuery($sSQL);
		extract(mysql_fetch_array($rsBaptis));
		
		$sLiturgiID = $LiturgiID;
		
		$sTanggal = $Tanggal;
		$sWarna = $Warna;
		$sBahasa = $Bahasa;
		$sKeterangan = $Keterangan;
		$sTema = $Tema;
		$sBacaan1 = $Bacaan1;
		$sBacaanAntara = $BacaanAntara;
		$sBacaan2 = $Bacaan2;
		$sBacaanInjil = $BacaanInjil;
		$sAyatPenuntunHK = $AyatPenuntunHK;
		$sAyatPenuntunBA = $AyatPenuntunBA;
		$sAyatPenuntunLM = $AyatPenuntunLM;
		$sAyatPenuntunP = $AyatPenuntunP;
		$sAyatPenuntunNP = $AyatPenuntunNP;
		$sNyanyian1 = $Nyanyian1;
		$sNyanyian2 = $Nyanyian2;
		$sNyanyian3 = $Nyanyian3;
		$sNyanyian4 = $Nyanyian4;
		$sNyanyian5 = $Nyanyian5;
		$sNyanyian6 = $Nyanyian6;
		$sNyanyian7 = $Nyanyian7;
		$sNyanyian8 = $Nyanyian8;		
		

	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today

	}
}


//Get Pendeta Names for the drop-down
//$sSQL = "SELECT * FROM DaftarPendeta ORDER BY PendetaID";
//$rsNamaPendeta = RunQuery($sSQL);

//Get Lokasi TI Names for the drop-down
//$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
//$rsNamaTempatIbadah = RunQuery($sSQL);
// Get Nama Pejabat

//Get Lokasi TI Names for the drop-down
$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
$rsNamaTempatIbadah = RunQuery($sSQL);


require "Include/Header.php";

?>

<form method="post" action="LiturgiEditor.php?LiturgiID=<?php echo $iLiturgiID; ?>" name="SuratEditor">

<table cellpadding="3" align="center" valign="top" >


			
	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="LiturgiSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"LiturgiSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iLiturgiID) > 0) 
{ echo "SelectListApp.php?mode=Liturgi&amp;$refresh"; 
} else {echo "SelectListApp.php?mode=Liturgi&amp;$refresh"; 
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
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal:"); ?></td>
		<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalError ?></font></td>
	</tr>
	<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Tempat Ibadah:"); ?></td>
				<td colspan="0" class="TextColumn">
					<select name="KodeTI">
						<option value="0" selected><?php echo gettext("- Liturgi Umum -"); ?></option>
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
		<td class="LabelColumn"><?php echo gettext("Warna :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="Warna" >
						<option value="Tidak Diketahui" <?php if ($sWaktu == "") { echo " selected"; }?> ><?php echo gettext("Tidak Diketahui"); ?></option>
						<option value="Merah"  <?php if ($sWarna == "Merah") { echo " selected"; }?> ><?php echo gettext("Merah"); ?></option>
						<option value="Putih"  <?php if ($sWarna == "Putih") { echo " selected"; }?> ><?php echo gettext("Putih"); ?></option>
						<option value="Ungu"  <?php if ($sWarna == "Ungu") { echo " selected"; }?> ><?php echo gettext("Ungu"); ?></option>
						<option value="Hijau"  <?php if ($sWarna == "Hijau") { echo " selected"; }?> ><?php echo gettext("Hijau"); ?></option>
						<option value="Hitam"  <?php if ($sWarna == "Hitam") { echo " selected"; }?> ><?php echo gettext("Hitam"); ?></option>
					</select>
		</td>
	
		<td class="LabelColumn"><?php echo gettext("Bahasa / Liturgi:"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="Bahasa" >
						<option value="Indonesia" <?php if ($sBahasa == "Indonesia") { echo " selected"; }?> ><?php echo gettext("Indonesia"); ?></option>
						<option value="Jawa" <?php if ($sBahasa == "Jawa") { echo " selected"; }?> ><?php echo gettext("Jawa"); ?></option>
						<option value="AltSore" <?php if ($sBahasa == "AltSore") { echo " selected"; }?> ><?php echo gettext("AltSore"); ?></option>
					</select>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan"); ?></td>
		<td class="TextColumn"><input type="text" name="Keterangan" id="Keterangan" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKeteranganError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Tema"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="Tema" size=80 id="Tema" value="<?php echo htmlentities(stripslashes($sTema),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sTemaError ?></font></td>
	</tr>			
	<tr>
		<td class="LabelColumn"><?php echo gettext("Bacaan1"); ?></td>
		<td class="TextColumn"><input type="text" name="Bacaan1" id="Bacaan1" value="<?php echo htmlentities(stripslashes($sBacaan1),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sBacaan1Error ?></font></td>

		<td class="LabelColumn"><?php echo gettext("BacaanAntara"); ?></td>
		<td class="TextColumn"><input type="text" name="BacaanAntara" id="BacaanAntara" value="<?php echo htmlentities(stripslashes($sBacaanAntara),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sBacaanAntaraError ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Bacaan2"); ?></td>
		<td class="TextColumn"><input type="text" name="Bacaan2" id="Bacaan2" value="<?php echo htmlentities(stripslashes($sBacaan2),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sBacaan2Error ?></font></td>

		<td class="LabelColumn"><?php echo gettext("BacaanInjil"); ?></td>
		<td class="TextColumn"><input type="text" name="BacaanInjil" id="BacaanInjil" value="<?php echo htmlentities(stripslashes($sBacaanInjil),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sBacaanInjilError ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Hukum Kasih"); ?></td>
		<td class="TextColumn"><input type="text" name="AyatPenuntunHK" id="AyatPenuntunHK" value="<?php echo htmlentities(stripslashes($sAyatPenuntunHK),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sAyatPenuntunHKError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Berita Anugerah"); ?></td>
		<td class="TextColumn"><input type="text" name="AyatPenuntunBA" id="AyatPenuntunBA" value="<?php echo htmlentities(stripslashes($sAyatPenuntunBA),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sAyatPenuntunBAError ?></font></td>
	</tr>			
	<tr>
		<td class="LabelColumn"><?php echo gettext("Litani Mazmur"); ?></td>
		<td class="TextColumn"><input type="text" name="AyatPenuntunLM" id="AyatPenuntunLM" value="<?php echo htmlentities(stripslashes($sAyatPenuntunLM),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sAyatPenuntunLMError ?></font></td>
	</tr>			
	<tr>
		<td class="LabelColumn"><?php echo gettext("Persembahan"); ?></td>
		<td class="TextColumn"><input type="text" name="AyatPenuntunP" id="AyatPenuntunP" value="<?php echo htmlentities(stripslashes($sAyatPenuntunP),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sAyatPenuntunPError ?></font></td>
	</tr>			
	<tr>
		<td class="LabelColumn"><?php echo gettext("Pengutusan"); ?></td>
		<td class="TextColumn"><input type="text" name="AyatPenuntunNP" id="AyatPenuntunNP" value="<?php echo htmlentities(stripslashes($sAyatPenuntunNP),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sAyatPenuntunNPError ?></font></td>
	</tr>			
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nyanyian1 (Pembukaan)"); ?></td>
		<td class="TextColumn"><input type="text" name="Nyanyian1" id="Nyanyian1" value="<?php echo htmlentities(stripslashes($sNyanyian1),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $Nyanyian1Error ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Nyanyian2 (Pujian)"); ?></td>
		<td class="TextColumn"><input type="text" name="Nyanyian2" id="Nyanyian2" value="<?php echo htmlentities(stripslashes($sNyanyian2),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $Nyanyian2Error ?></font></td>
	</tr>				
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nyanyian3 (Penyesalan)"); ?></td>
		<td class="TextColumn"><input type="text" name="Nyanyian3" id="Nyanyian3" value="<?php echo htmlentities(stripslashes($sNyanyian3),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $Nyanyian3Error ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Nyanyian4 (Sukacita)"); ?></td>
		<td class="TextColumn"><input type="text" name="Nyanyian4" id="Nyanyian4" value="<?php echo htmlentities(stripslashes($sNyanyian4),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $Nyanyian4Error ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nyanyian5 (Ucapan Syukur)"); ?></td>
		<td class="TextColumn"><input type="text" name="Nyanyian5" id="Nyanyian5" value="<?php echo htmlentities(stripslashes($sNyanyian5),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $Nyanyian5Error ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Nyanyian6 (Pengutusan)"); ?></td>
		<td class="TextColumn"><input type="text" name="Nyanyian6" id="Nyanyian6" value="<?php echo htmlentities(stripslashes($sNyanyian6),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $Nyanyian6Error ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nyanyian7 (Penutup)"); ?></td>
		<td class="TextColumn"><input type="text" name="Nyanyian7" id="Nyanyian7" value="<?php echo htmlentities(stripslashes($sNyanyian7),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $Nyanyian7Error ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Nyanyian8"); ?></td>
		<td class="TextColumn"><input type="text" name="Nyanyian8" id="Nyanyian8" value="<?php echo htmlentities(stripslashes($sNyanyian8),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $Nyanyian8Error ?></font></td>
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
		$logvar2 = "Liturgi Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iLiturgiID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
