<?php
/*******************************************************************************
 *
 *  filename    : MeninggalEditor.php
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
$sPageTitle = gettext("Pencatatan Warga yg Meninggal");

//Get the MeninggalID out of the querystring
$iMeninggalID = FilterInput($_GET["MeninggalID"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?MeninggalID= manually)
if (strlen($iMeninggalID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsMeninggal = RunQuery($sSQL);
	extract(mysql_fetch_array($rsMeninggal));

	if (mysql_num_rows($rsMeninggal) == 0)
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

if (isset($_POST["MeninggalSubmit"]) || isset($_POST["MeninggalSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sMeninggalID = FilterInput($_POST["MeninggalID"]);
	$sper_ID = FilterInput($_POST["per_ID"]);
	$sPendeta = FilterInput($_POST["Pendeta"]);
	$sKetuaMajelis = FilterInput($_POST["KetuaMajelis"]);
	$sSekretarisMajelis = FilterInput($_POST["SekretarisMajelis"]);
	
	


		
		
	$sTanggalMeninggal = FilterInput($_POST["TanggalMeninggal"]);
	$sTempatSemayam = FilterInput($_POST["TempatSemayam"]);
	$sTempatPemakaman = FilterInput($_POST["TempatPemakaman"]);
	$sTanggalPemakaman = FilterInput($_POST["TanggalPemakaman"]);
	$sWaktuPemakaman = FilterInput($_POST["WaktuPemakaman"]);
	$sNama = FilterInput($_POST["Nama"]);
	$sWaktuMeninggal = FilterInput($_POST["WaktuMeninggal"]);
	$sTempatMeninggal = FilterInput($_POST["TempatMeninggal"]);		
	$sGerejaID = FilterInput($_POST["GerejaID"]);
	$sNamaGerejaNonGKJ = FilterInput($_POST["NamaGerejaNonGKJ"]);
	$sAlamat1NonGKJ = FilterInput($_POST["Alamat1NonGKJ"]);
	$sAlamat2NonGKJ = FilterInput($_POST["Alamat2NonGKJ"]);
	$sAlamat3NonGKJ = FilterInput($_POST["Alamat3NonGKJ"]);
	$sTelpNonGKJ = FilterInput($_POST["TelpNonGKJ"]);
	$sFaxNonGKJ = FilterInput($_POST["FaxNonGKJ"]);
	$sEmailNonGKJ = FilterInput($_POST["EmailNonGKJ"]);
	$sDateLastEdited = FilterInput($_POST["DateLastEdited"]);
	$sDateEntered = FilterInput($_POST["DateEntered"]);
	$sEnteredBy = FilterInput($_POST["EnteredBy"]);
	$sEditedBy = FilterInput($_POST["EditedBy"]);
	
	$sWargaGereja = FilterInput($_POST["WargaGereja"]);
	$sWargaGerejaNonGKJ = FilterInput($_POST["WargaGerejaNonGKJ"]);
	$sAlamatGerejaNonGKJ = FilterInput($_POST["AlamatGerejaNonGKJ"]);
	
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
		if (strlen($iMeninggalID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO PermohonanPemakamangkjbekti ( 
			per_ID,
			TanggalMeninggal,
			Pendeta,
			KetuaMajelis,
			SekretarisMajelis,
			TempatSemayam,
			TempatPemakaman,
			TanggalPemakaman,
			WaktuPemakaman,	
			Nama,
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
			'" . $sTanggalMeninggal . "',
			'" . $sPendeta . "',
			'" . $sKetuaMajelis . "',
			'" . $sSekretarisMajelis . "',
			'" . $sTempatSemayam . "',
			'" . $sTempatPemakaman . "',
			'" . $sTanggalPemakaman . "',
			'" . $sWaktuPemakaman . "',
			'" . $sNama . "',
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
			
			$logvar1 = "Edit";
			$logvar2 = "New Meninggal Data";


		// Existing Meninggal (update)
		} else {
	//update the Meninggal table
			$sSQL = "UPDATE PermohonanPemakamangkjbekti SET 
			per_ID = '" . $sper_ID . "',
			TanggalMeninggal = '" . $sTanggalMeninggal . "',
			Pendeta = '" . $sPendeta . "',
			KetuaMajelis = '" . $sKetuaMajelis . "',
			SekretarisMajelis = '" . $sSekretarisMajelis . "',
			TempatSemayam = '" . $sTempatSemayam . "',
			TempatPemakaman = '" . $sTempatPemakaman . "',
			TanggalPemakaman = '" . $sTanggalPemakaman . "',
			WaktuPemakaman = '" . $sWaktuPemakaman . "',
			Nama = '" . $sNama . "',
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
				
			$sSQL .= "' WHERE MeninggalID = " . $iMeninggalID;


	

			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Meninggal Data";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		
					// update the main database
			//	$sSQLGKJ = "SELECT * FROM DaftarGerejaGKJ  WHERE GerejaID = " . $sTempatMeninggal . " LIMIT 1";
			//	$rsGKJ = RunQuery($sSQLGKJ);
			//	extract(mysql_fetch_array($rsGKJ));
			//	$sNamaGereja = $NamaGereja;		
					
				$sSQL2 = "UPDATE person_custom  SET 
					c41 = '" . $sTanggalMeninggal . "',					
					c42 = '" . $sTempatPemakaman  ;
								$sSQL2 .= "' WHERE per_ID = " . $sper_ID;
		RunQuery($sSQL2);
		
		

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMeninggalID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. MeninggalEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iMeninggalID);
		}
		else if (isset($_POST["MeninggalSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectListApp.php?mode=Meninggal&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("MeninggalEditor.php&amp;$refresh");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iMeninggalID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM PermohonanPemakamangkjbekti  WHERE MeninggalID = " . $iMeninggalID;
		$rsMeninggal = RunQuery($sSQL);
		extract(mysql_fetch_array($rsMeninggal));

			
		$sMeninggalID = $MeninggalID;
		$sper_ID = $per_ID;
		$sTanggalMeninggal = $TanggalMeninggal;
		$sPendeta = $Pendeta;
		$sKetuaMajelis = $KetuaMajelis;
		$sSekretarisMajelis = $SekretarisMajelis;
		$sTempatSemayam = $TempatSemayam;
		$sTempatPemakaman = $TempatPemakaman;
		$sTanggalPemakaman = $TanggalPemakaman;
		$sWaktuPemakaman = $WaktuPemakaman;
		$sNama = $Nama;
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

//Get Student Names for the drop-down
//$sSQL = "SELECT * FROM person_per a JOIN family_fam b ON a.per_fam_ID=b.fam_ID WHERE (per_cls_ID <3 AND per_fmr_ID >2 ) ORDER BY per_firstname";

	if (strlen($iMeninggalID) > 0)
	{
	$sSQL = "select a.* , a.per_id, a.per_firstname as NamaMeninggal , a.per_gender as JK , a.per_fam_id ,
 c.per_firstname as NamaAyah, d.per_firstname as NamaIbu,
x.c1 as TglBaptis, x.c26 as TempatBaptis, x.c37 as DiBaptisOleh
from person_per a
left join person_custom x ON a.per_id = x.per_id 
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
order by a.per_firstname ";
	}
	else
	{
$sSQL = "select a.* , a.per_id, a.per_firstname as NamaMeninggal , a.per_gender as JK , a.per_fam_id ,
 c.per_firstname as NamaAyah, d.per_firstname as NamaIbu,
x.c1 as TglBaptis, x.c26 as TempatBaptis, x.c37 as DiBaptisOleh
from person_per a
left join person_custom x ON a.per_id = x.per_id 
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)

order by a.per_firstname ";
}

$rsNamaMeninggal = RunQuery($sSQL);

//Get Pendeta Names for the drop-down
$sSQL = "SELECT * FROM DaftarPendeta a
LEFT JOIN DaftarGerejaGKJ b ON a.GerejaID=b.GerejaID
ORDER BY PendetaID";
$rsNamaPendeta = RunQuery($sSQL);

//Get Daftar GKJ Names for the drop-down
$sSQL = "SELECT * FROM DaftarGerejaGKJ a 
LEFT JOIN DaftarKlasisGKJ b ON a.KlasisID=b.KlasisID
ORDER BY GerejaID, NamaGereja";
$rsNamaGereja = RunQuery($sSQL);

//Get Daftar GKJ Names for the drop-down non GKJ Bekti
$sSQL = "SELECT * FROM DaftarGerejaGKJ a 
LEFT JOIN DaftarKlasisGKJ b ON a.KlasisID=b.KlasisID
WHERE a.GerejaID > 1 
ORDER BY GerejaID, NamaGereja";
$rsNamaGereja2 = RunQuery($sSQL);

// Get Nama Pejabat



require "Include/Header.php";

?>

<form method="post" action="MeninggalEditor.php?MeninggalID=<?php echo $iMeninggalID; ?>" name="MeninggalEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="MeninggalSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"MeninggalSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iMeninggalID) > 0) 
{ echo "SelectListApp.php?mode=Meninggal&amp;$refresh"; 
} else {echo "SelectListApp.php?mode=Meninggal&amp;$refresh"; 
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
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Meninggal:"); ?></td>
		<td class="TextColumn"><input type="text" name="TanggalMeninggal" value="<?php echo $sTanggalMeninggal; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalMeninggalError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Pemakaman:"); ?></td>
		<td class="TextColumn"><input type="text" name="TanggalPemakaman" value="<?php echo $sTanggalPemakaman; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalPemakamanError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Waktu Pemakaman:"); ?></td>
		<td class="TextColumn"><input type="text" name="WaktuPemakaman" id="WaktuPemakaman" value="<?php echo htmlentities(stripslashes($sWaktuPemakaman),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sWaktuPemakamanError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Dimakamkan di:"); ?></td>
		<td class="TextColumn"><input type="text" name="TempatPemakaman" id="TempatPemakaman" value="<?php echo htmlentities(stripslashes($sTempatPemakaman),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatPemakamanError ?></font></td>
	</tr>

	<tr>
		<td class="LabelColumn"><?php echo gettext("Dilayani Oleh :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspans="2" >
					<select name="Pendeta" >
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPendeta))
						{
							extract($aRow);

							echo "<option value=\"" . $NamaPendeta . "\"";
							if ($sPendeta == $NamaPendeta) { echo " selected"; }
							echo ">" . $NamaPendeta." - ".$NamaGereja;
						}
						?>

					</select>
		</td>
		<tr>
		<td class="LabelColumn"><?php echo gettext("Gereja yg melayani :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="TempatSemayam" >
						<option value="0" selected><?php echo gettext("Tidak Diketahui / Non GKJ "); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaGereja))
						{
							extract($aRow);

							echo "<option value=\"" . $GerejaID . "\"";
							if ($sTempatSemayam == $GerejaID) { echo " selected"; }
							echo ">" . $NamaGereja." - ".$NamaKlasis;
						}
						?>
					</select>
		</td>
	</tr>	
		
		
	</tr>	
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Nama Warga yg Meninggal:"); ?></td>
				<td colspan="3" class="TextColumn">
					<select name="per_ID" size="10">
						<option value="0" selected><?php echo gettext("Tidak Diketahui / Bukan Warga "); ?></option>
						<option value="0">-----------------------</option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaMeninggal))
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

	<tr><td><tr>
		<td class="LabelColumn"><?php //echo gettext("Ketua Majelis:"); ?></td>
		<td class="TextColumn"><input type="hidden" type="text" name="KetuaMajelis" id="KetuaMajelis" 
		value="<?php 
		if (strlen($iMeninggalID) > 0)
		{ echo htmlentities(stripslashes($sKetuaMajelis),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		echo jabatanpengurus(61);
		}
		 ?>"><br><font color="red"><?php echo $sKetuaMajelisError ?></font></td>

		<td class="LabelColumn"><?php //echo gettext("Sekretaris Majelis:"); ?></td>
		<td class="TextColumn"><input type="hidden" type="text" name="SekretarisMajelis" id="SekretarisMajelis" 
		value="<?php 
		if (strlen($iMeninggalID) > 0)
		{ echo htmlentities(stripslashes($sSekretarisMajelis),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		echo jabatanpengurus(65);
		}
		 ?>"><br><font color="red"><?php echo $sSekretarisMajelisError ?></font></td>
		</tr>	
		</td></tr>
	</tr>

<?php /*

			<tr>
				<td colspan="4" align="center"><h3><?php echo gettext("Isikan Data dibawah jika bukan Warga jemaat"); ?></h3></td>
			</tr>	
				
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nama yg Meninggal(Jika bukan warga):"); ?></td>
		<td class="TextColumn"><input type="text" name="NamaLengkap" id="NamaLengkap" value="<?php echo htmlentities(stripslashes($sNamaLengkap),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaLengkapError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Warga dari Gereja :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="WargaGereja" >
						<option value="0" selected><?php echo gettext("Bukan Warga GKJ"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaGereja2))
						{
							extract($aRow);

							echo "<option value=\"" . $GerejaID . "\"";
							if ($sWargaGereja == $GerejaID) { echo " selected"; }
							echo ">" . $NamaGereja." - ".$NamaKlasis;
						}
						?>
					</select>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nama Warga Gereja non GKJ:"); ?></td>
		<td class="TextColumn"><input type="text" name="Nama" id="Nama" value="<?php echo htmlentities(stripslashes($sWargaGerejaNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sWargaGerejaNonGKJError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Alamat Gereja non GKJ:"); ?></td>
		<td class="TextColumn"><input type="text" name="WargaGerejaNonGKJ" id="AlamatGerejaNonGKJ" value="<?php echo htmlentities(stripslashes($sWargaGerejaNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sWargaGerejaNonGKJError ?></font></td>
	</tr>	
	
	
	
	<tr><td><tr>
		<td class="LabelColumn"><?php echo gettext("Tempat lahir"); ?></td>
		<td class="TextColumn"><input type="text" name="TempatLahir" id="TempatLahir" value="<?php echo htmlentities(stripslashes($sTempatLahir),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatLahirError ?></font></td>
		
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal lahir:"); ?></td>
		<td class="TextColumn"><input type="text" name="TanggalLahir" value="<?php echo $sTanggalLahir; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalLahirError ?></font></td>
		</tr></td>
		</tr>

	<tr><td><tr>
		<td class="LabelColumn"><?php echo gettext("Nama Ayah:"); ?></td>
		<td class="TextColumn"><input type="text" name="NamaAyah" id="NamaAyah" value="<?php echo htmlentities(stripslashes($sNamaAyah),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaAyahError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Nama Ibu:"); ?></td>
		<td class="TextColumn"><input type="text" name="NamaIbu" id="NamaIbu" value="<?php echo htmlentities(stripslashes($sNamaIbu),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaIbuError ?></font></td>
		</tr></td>
	</tr>	
	<tr><td><tr>
		<td class="LabelColumn"><?php echo gettext("TempatBaptis:"); ?></td>
		<td class="TextColumn"><input type="text" name="TempatBaptis" id="TempatBaptis" value="<?php echo htmlentities(stripslashes($sTempatBaptis),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatBaptisError ?></font></td>
		
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Baptis:"); ?></td>
		<td class="TextColumn"><input type="text" name="TanggalBaptis" value="<?php echo $sTanggalBaptis; ?>" maxlength="10" id="sel2" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel2', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalBaptisError ?></font></td>
	</tr></td>
	</tr>	

	<tr>
		<td class="LabelColumn"><?php echo gettext("DiBaptis oleh:"); ?></td>
		<td class="TextColumn"><input type="text" name="PendetaBaptis" id="PendetaBaptis" value="<?php echo htmlentities(stripslashes($sPendetaBaptis),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPendetaBaptisError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nomor Reff Surat:"); ?></td>
		<td class="TextColumn"><input type="text" name="NoSuratTitipan" id="NoSuratTitipan" value="<?php echo htmlentities(stripslashes($sNoSuratTitipan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNoSuratTitipanError ?></font></td>
	</tr>	
	



	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Test4:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglTest4" value="<?php echo $sTglTest1; ?>" maxlength="10" id="sel4" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel4', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTglTest4 ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nilai 4:"); ?></td>
		<td class="TextColumn"><input type="text" name="Nilai4" id="Nilai4" value="<?php echo htmlentities(stripslashes($sNilai4),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNilai4Error ?></font></td>
	</tr>
**/ ?> 

	
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
		$logvar2 = "Pendaftaran Meninggal Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMeninggalID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
// require "Include/Footer.php";
?>
