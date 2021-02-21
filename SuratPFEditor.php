<?php
/*******************************************************************************
 *
 *  filename    : SuratPFEditor.php
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
$sPageTitle = gettext("Surat Permohonan Pelayanan Firman");

//Get the SuratKeluarID out of the querystring
$iSuratKeluarID = FilterInput($_GET["SuratKeluarID"],'int');
$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;
// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?SuratKeluarID= manually)
if (strlen($iSuratKeluarID) > 0)
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
	$sSuratKeluarID = FilterInput($_POST["SuratKeluarID"]);
	$sPerihal = FilterInput($_POST["Perihal"]);
	$sTujuanSurat = FilterInput($_POST["TujuanSurat"]);
	$sSalutation = FilterInput($_POST["Salutation"]);
	$sPelayanFirman = FilterInput($_POST["PelayanFirman"]);
	$sPFnonInstitusi = FilterInput($_POST["PFnonInstitusi"]);
	$sTanggalPF = FilterInput($_POST["TanggalPF"]);
	$sKodeTI = FilterInput($_POST["KodeTI"]);
	$sTempatPF = FilterInput($_POST["TempatPF"]);
	$sWaktuPF = FilterInput($_POST["WaktuPF"]);
	$sTemaPF = FilterInput($_POST["TemaPF"]);
	$sBacaanPF = FilterInput($_POST["BacaanPF"]);
	$sBahasaPF = FilterInput($_POST["BahasaPF"]);
	$sTanggalKirimEmail = FilterInput($_POST["TanggalKirimEmail"]);
	$sTanggalKirimFax = FilterInput($_POST["TanggalKirimFax"]);
	$sTanggalKirimSurat = FilterInput($_POST["TanggalKirimSurat"]);
	$sTembusan1 = FilterInput($_POST["Tembusan1"]);
	$sTembusan2 = FilterInput($_POST["Tembusan2"]);
	$sTembusan3 = FilterInput($_POST["Tembusan3"]);
	$sPendetaJemaat = FilterInput($_POST["PendetaJemaat"]);
	$sKetuaMajelis = FilterInput($_POST["KetuaMajelis"]);
	$sSekretarisMajelis = FilterInput($_POST["SekretarisMajelis"]);
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
		if (strlen($iSuratKeluarID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO suratkeluarPFgkjbekti ( 		

			Perihal,			
			TujuanSurat,									
			Salutation,			
			PelayanFirman,		
			PFnonInstitusi,				
			TanggalPF,
			KodeTI,			
			TempatPF,						
			WaktuPF,									
			TemaPF,						
			BacaanPF,												
			BahasaPF,						
			TanggalKirimEmail,			
			TanggalKirimFax,									
			TanggalKirimSurat,						
			Tembusan1,			
			Tembusan2,			
			Tembusan3,
			PendetaJemaat,									
			KetuaMajelis,			
			SekretarisMajelis,   
			DateEntered,
			EnteredBy	)
			VALUES ( 


			'" . $sPerihal . "',
			'" . $sTujuanSurat . "',
			'" . $sSalutation . "',	
			'" . $sPelayanFirman . "',
			'" . $sPFnonInstitusi . "',
			'" . $sTanggalPF . "',
			'" . $sKodeTI . "',	
			'" . $sTempatPF . "',
			'" . $sWaktuPF . "',
			'" . $sTemaPF . "',	
			'" . $sBacaanPF . "',
			'" . $sBahasaPF . "',
			'" . $sTanggalKirimEmail . "',	
			'" . $sTanggalKirimFax . "',
			'" . $sTanggalKirimSurat . "',
			'" . $sTembusan1 . "',
			'" . $sTembusan2 . "',
			'" . $sTembusan3 . "',
			'" . $sPendetaJemaat . "',
			'" . $sKetuaMajelis . "',
			'" . $sSekretarisMajelis . "',
			
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Surat Keluar Data";


		// Existing Baptis (update)
		} else {
	//update the Baptis table
			$sSQL = "UPDATE suratkeluarPFgkjbekti SET 
			
			Perihal = '" . $sPerihal . "',
			TujuanSurat = '" . $sTujuanSurat . "',
			Salutation = '" . $sSalutation . "',
			PelayanFirman = '" . $sPelayanFirman . "',
			PFnonInstitusi = '" . $sPFnonInstitusi . "',
			TanggalPF = '" . $sTanggalPF . "',
			KodeTI = '" . $sKodeTI . "',
			TempatPF = '" . $sTempatPF . "',						
			WaktuPF = '" . $sWaktuPF . "',									
			TemaPF = '" . $sTemaPF . "',
			BacaanPF = '" . $sBacaanPF . "',
			BahasaPF = '" . $sBahasaPF . "',
			TanggalKirimEmail = '" . $sTanggalKirimEmail . "',
			TanggalKirimFax = '" . $sTanggalKirimFax . "',
			TanggalKirimSurat = '" . $sTanggalKirimSurat . "',
			Tembusan1 = '" . $sTembusan1 . "',
			Tembusan2 = '" . $sTembusan2 . "',
			Tembusan3 = '" . $sTembusan3 . "',
			PendetaJemaat = '" . $sPendetaJemaat . "',
			KetuaMajelis = '" . $sKetuaMajelis . "',
			SekretarisMajelis = '" . $sSekretarisMajelis . "',
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE SuratKeluarID = " . $iSuratKeluarID;

		//	echo $sSQL;
	

			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Data Surat Keluar";
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
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iSuratKeluarID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. SuratPFEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iSuratKeluarID);
		}
		else if (isset($_POST["SuratSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectListApp.php?mode=SuratKeluar&amp;GID=$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("SuratPFEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iSuratKeluarID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM suratkeluarPFgkjbekti  WHERE SuratKeluarID = " . $iSuratKeluarID;
		$rsBaptis = RunQuery($sSQL);
		extract(mysql_fetch_array($rsBaptis));
		
		$sSuratKeluarID = $SuratKeluarID;
		$sPerihal = $Perihal;
		$sTujuanSurat = $TujuanSurat;
		$sSalutation = $Salutation;
		$sPelayanFirman = $PelayanFirman;
		$sPFnonInstitusi = $PFnonInstitusi;
		$sTanggalPF = $TanggalPF;
		$sKodeTI = $KodeTI;
		$sTempatPF = $TempatPF;						
		$sWaktuPF = $WaktuPF;									
		$sTemaPF = $TemaPF;
		$sBacaanPF = $BacaanPF;
		$sBahasaPF = $BahasaPF;
		$sTanggalKirimEmail = $TanggalKirimEmail;
		$sTanggalKirimFax = $TanggalKirimFax;
		$sTanggalKirimSurat = $TanggalKirimSurat;
		$sTembusan1 = $Tembusan1;
		$sTembusan2 = $Tembusan2;
		$sTembusan3 = $Tembusan3;
		$sPendetaJemaat = $PendetaJemaat;
		$sKetuaMajelis = $KetuaMajelis;
		$sSekretarisMajelis = $SekretarisMajelis;		
		
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today

	}
}


//Get Pendeta Names for the drop-down
$sSQL = "SELECT * FROM DaftarPendeta ORDER BY PendetaID";
$rsNamaPendeta = RunQuery($sSQL);

//Get Lokasi TI Names for the drop-down
$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
$rsNamaTempatIbadah = RunQuery($sSQL);
// Get Nama Pejabat



require "Include/Header.php";

?>

<form method="post" action="SuratPFEditor.php?SuratKeluarID=<?php echo $iSuratKeluarID; ?>" name="SuratEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="SuratSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"SuratSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iSuratKeluarID) > 0) 
{ echo "SelectListApp.php?mode=SuratKeluar&amp;GID=$refresh"; 
} else {echo "SelectListApp.php?mode=SuratKeluar&amp;GID=$refresh"; 
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
		<td class="TextColumn"><input type="text" name="TanggalPF" value="<?php echo $sTanggalPF; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalPFError ?></font></td>
	</tr><tr>	
		<td class="LabelColumn"><?php echo gettext("Pukul :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="WaktuPF" >
						<option value="Tidak Diketahui" <?php if ($sWaktuPF == "") { echo " selected"; }?> ><?php echo gettext("Tidak Diketahui"); ?></option>
						<option value="06.00 WIB"  <?php if ($sWaktuPF == "06.00 WIB") { echo " selected"; }?> ><?php echo gettext("06.00 WIB"); ?></option>
						<option value="07.00 WIB"  <?php if ($sWaktuPF == "07.00 WIB") { echo " selected"; }?> ><?php echo gettext("07.00 WIB"); ?></option>
						<option value="07.30 WIB"  <?php if ($sWaktuPF == "07.30 WIB") { echo " selected"; }?> ><?php echo gettext("07.30 WIB"); ?></option>
						<option value="09.00 WIB"  <?php if ($sWaktuPF == "09.00 WIB") { echo " selected"; }?> ><?php echo gettext("09.00 WIB"); ?></option>
						<option value="17.00 WIB"  <?php if ($sWaktuPF == "17.00 WIB") { echo " selected"; }?> ><?php echo gettext("17.00 WIB"); ?></option>
					</select>
		</td>
	</tr>	
	<tr>	
		<td class="LabelColumn"><?php echo gettext("Bahasa :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="BahasaPF" >
						<option value="Indonesia" <?php if ($sBahasaPF == "Indonesia") { echo " selected"; }?> ><?php echo gettext("Indonesia"); ?></option>
						<option value="Jawa" <?php if ($sBahasaPF == "Jawa") { echo " selected"; }?> ><?php echo gettext("Jawa"); ?></option>
					</select>
		</td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Pelayan Firman (Pendeta) :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="PelayanFirman" >
						<option value="0" selected><?php echo gettext("Bukan Pendeta"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPendeta))
						{
							extract($aRow);

							echo "<option value=\"" . $PendetaID . "\"";
							if ($sPelayanFirman == $PendetaID) { echo " selected"; }
							echo ">" . $NamaPendeta;
						}
						?>

					</select>
		</td>
			</tr><tr>	
		<td class="LabelColumn"><?php echo gettext("Pelayan Firman(Jika bukan Pendeta):"); ?></td>
		<td class="TextColumn">
		<select name="Salutation" >
						<option value="Bp." <?php if ($sSalutation == "Bp.") { echo " selected"; }?> ><?php echo gettext("Bp."); ?></option>
						<option value="Ibu." <?php if ($sSalutation == "Ibu.") { echo " selected"; }?> ><?php echo gettext("Ibu."); ?></option>
						<option value="Sdr." <?php if ($sSalutation == "Sdr.") { echo " selected"; }?> ><?php echo gettext("Sdr."); ?></option>
						<option value="Sdri." <?php if ($sSalutation == "Sdri.") { echo " selected"; }?> ><?php echo gettext("Sdri."); ?></option>
						
					</select>
		
		<input type="text" size=50 name="PFnonInstitusi" id="PFnonInstitusi" value="<?php echo htmlentities(stripslashes($sPFnonInstitusi),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPFnonInstitusiError ?></font></td>

	</tr>	
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Tempat Ibadah:"); ?></td>
				<td colspan="3" class="TextColumn">
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
		<td class="TextColumn"><input type="text" size=50 name="TempatPF" id="TempatPF" value="<?php echo htmlentities(stripslashes($sTempatPF),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatPFError ?></font></td>

	</tr>	

	<tr><td>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Pendeta Jemaat:"); ?></td>
		<td class="TextColumn"><input type="text" name="PendetaJemaat" id="PendetaJemaat" 
		value="<?php 
		if (strlen($iSuratKeluarID) > 0)
		{ echo htmlentities(stripslashes($sPendetaJemaat),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		jabatanpengurus(1);
		}
		 ?>"><br><font color="red"><?php echo $sPendetaJemaatError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Ketua Majelis:"); ?></td>
		<td class="TextColumn"><input type="text" name="KetuaMajelis" id="KetuaMajelis" 
		value="<?php 
		if (strlen($iSuratKeluarID) > 0)
		{ echo htmlentities(stripslashes($sKetuaMajelis),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		jabatanpengurus(61);
		}
		 ?>"><br><font color="red"><?php echo $sKetuaMajelisError ?></font></td>
	</tr><tr>	
		<td class="LabelColumn"><?php echo gettext("Sekretaris Majelis:"); ?></td>
		<td class="TextColumn"><input type="text" name="SekretarisMajelis" id="SekretarisMajelis" 
		value="<?php 
		if (strlen($iSuratKeluarID) > 0)
		{ echo htmlentities(stripslashes($sSekretarisMajelis),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		jabatanpengurus(65);
		}
		 ?>"><br><font color="red"><?php echo $sSekretarisMajelisError ?></font></td>
		</tr>	
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
		$logvar2 = "Pendaftaran Baptis Anak Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iSuratKeluarID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
