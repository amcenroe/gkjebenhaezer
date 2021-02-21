<?php
/*******************************************************************************
 *
 *  filename    : BaptisAnakEditor.php
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
$sPageTitle = gettext("Pendaftaran Baptis");
$iPersonID = FilterInput($_GET["PersonID"],'int');
//Get the BaptisID out of the querystring
$iBaptisID = FilterInput($_GET["BaptisID"],'int');
$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;
// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?BaptisID= manually)
if (strlen($iBaptisID) > 0)
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

if (isset($_POST["BaptisSubmit"]) || isset($_POST["BaptisSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sBaptisID = FilterInput($_POST["BaptisID"]);
	$sper_ID = FilterInput($_POST["per_ID"]);
	$sPendetaBaptis = FilterInput($_POST["PendetaBaptis"]);
	$sKetuaMajelis = FilterInput($_POST["KetuaMajelis"]);
	$sSekretarisMajelis = FilterInput($_POST["SekretarisMajelis"]);
	$sTanggalBaptis = FilterInput($_POST["TanggalBaptis"]);
	$sWaktuBaptis = FilterInput($_POST["WaktuBaptis"]);
	$sTanggalCetak = FilterInput($_POST["TanggalCetak"]);
	$sNamaGereja = FilterInput($_POST["NamaGereja"]);
	$sTempatBaptis = FilterInput($_POST["TempatBaptis"]);
	$sTempatBaptis2 = FilterInput($_POST["TempatBaptis2"]);
	$sTempatTitipBaptis = FilterInput($_POST["TempatTitipBaptis"]);
	$sNamaLengkap = FilterInput($_POST["NamaLengkap"]);
	$sTempatLahir = FilterInput($_POST["TempatLahir"]);
	$sTanggalLahir = FilterInput($_POST["TanggalLahir"]);
	$sNamaAyah = FilterInput($_POST["NamaAyah"]);
	$sNamaIbu = FilterInput($_POST["NamaIbu"]);
	$sNoSuratTitipan = FilterInput($_POST["NoSuratTitipan"]);
	
	$sWargaGereja = FilterInput($_POST["WargaGereja"]);
	$sWargaGerejaNonGKJ = FilterInput($_POST["WargaGerejaNonGKJ"]);
	$sAlamatGerejaNonGKJ = FilterInput($_POST["AlamatGerejaNonGKJ"]);	
	
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
		if (strlen($iBaptisID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO baptisanakgkjbekti ( 
			BaptisID,
			per_ID,
			PendetaBaptis,
			KetuaMajelis,
			SekretarisMajelis,
			TanggalBaptis,
			WaktuBaptis,
			TempatBaptis,
			TempatBaptis2,
			TempatTitipBaptis,			
			TanggalCetak,
			NamaLengkap,
			TempatLahir,
			TanggalLahir,
			NamaAyah,
			NamaIbu,
			NoSuratTitipan,
			WargaGereja,
			WargaGerejaNonGKJ,
			AlamatGerejaNonGKJ,			
			DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sBaptisID . "',
			'" . $sper_ID . "',
			'" . $sPendetaBaptis . "',
			'" . $sKetuaMajelis . "',
			'" . $sSekretarisMajelis . "',
			'" . $sTanggalBaptis . "',
			'" . $sWaktuBaptis . "',
			'" . $sTempatBaptis . "',
			'" . $sTempatBaptis2 . "',
			'" . $sTempatTitipBaptis . "',			
			'" . $sTanggalCetak . "',
			'" . $sNamaLengkap . "',
			'" . $sTempatLahir . "',
			'" . $sTanggalLahir . "',
			'" . $sNamaAyah . "',
			'" . $sNamaIbu . "',
			'" . $sNoSuratTitipan . "',
			'" . $sWargaGereja . "',
			'" . $sWargaGerejaNonGKJ . "',
			'" . $sAlamatGerejaNonGKJ . "',			
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Baptis Data";


		// Existing Baptis (update)
		} else {
	//update the Baptis table
			$sSQL = "UPDATE baptisanakgkjbekti SET 
			
					per_ID = '" . $sper_ID . "',
					PendetaBaptis = '" . $sPendetaBaptis . "',
					KetuaMajelis = '" . $sKetuaMajelis . "',
					SekretarisMajelis = '" . $sSekretarisMajelis . "',
					TanggalBaptis = '" . $sTanggalBaptis . "',
					WaktuBaptis = '" . $sWaktuBaptis . "',
					TempatBaptis = '" . $sTempatBaptis . "',
					TempatBaptis2 = '" . $sTempatBaptis2 . "',
					TempatTitipBaptis = '" . $sTempatTitipBaptis . "',						
					TanggalCetak = '" . $sTanggalCetak . "',
					NamaLengkap = '" . $sNamaLengkap . "',
					TempatLahir = '" . $sTempatLahir . "',
					TanggalLahir = '" . $sTanggalLahir . "',
					NamaAyah = '" . $sNamaAyah . "',
					NamaIbu = '" . $sNamaIbu . "',
					NoSuratTitipan = '" . $sNoSuratTitipan . "',
					WargaGereja = '" . $sWargaGereja . "',
					WargaGerejaNonGKJ = '" . $sWargaGerejaNonGKJ . "',
					AlamatGerejaNonGKJ = '" . $sAlamatGerejaNonGKJ . "',
					DateLastEdited = '" . date("YmdHis") . "',
					EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE BaptisID = " . $iBaptisID;

		//	echo $sSQL;
	

			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Baptis Anak Data";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		
					// update the main database
				$sSQLGKJ = "SELECT * FROM DaftarGerejaGKJ  WHERE GerejaID = " . $sTempatBaptis . " LIMIT 1";
				$rsGKJ = RunQuery($sSQLGKJ);
				extract(mysql_fetch_array($rsGKJ));
				$sNamaGereja = $NamaGereja;	
				
				$sSQL2 = "UPDATE person_custom  SET 
					c1 = '" . $sTanggalBaptis . "',					

					c26 = '" . $sNamaGereja. "',
					c37 = '" . $sPendetaBaptis  ;
								$sSQL2 .= "' WHERE per_ID = " . $sper_ID;
				//Update Status Kewargaan 
				$sSQL3 = "UPDATE person_per  SET 
					per_cls_ID = '1' WHERE per_ID = " . $sper_ID;
		
	//	echo $sSQL2;
	//					c26 = 'Gereja Kristen Jawa Bekasi Timur',
		
		RunQuery($sSQL2);
		RunQuery($sSQL3);
		
		

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iBaptisID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. BaptisAnakEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iBaptisID);
		}
		else if (isset($_POST["BaptisSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectListApp.php?mode=BaptisAnak&amp;GID=$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("BaptisAnakEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iBaptisID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM baptisanakgkjbekti  WHERE BaptisID = " . $iBaptisID;
		$rsBaptis = RunQuery($sSQL);
		extract(mysql_fetch_array($rsBaptis));

		$sBaptisID = $BaptisID;
		$sper_ID = $per_ID;
		$sPendetaBaptis = $PendetaBaptis;
		$sKetuaMajelis = $KetuaMajelis;
		$sSekretarisMajelis = $SekretarisMajelis;
		$sTanggalBaptis = $TanggalBaptis;
		$sWaktuBaptis = $WaktuBaptis;
		$sTempatBaptis = $TempatBaptis;	
		$sTempatBaptis2 = $TempatBaptis2;
		$sTempatTitipBaptis = $TempatTitipBaptis;				
		$sTanggalCetak = $TanggalCetak;
		$sNamaLengkap = $NamaLengkap;
		$sTempatLahir = $TempatLahir;
		$sTanggalLahir = $TanggalLahir;
		$sNamaAyah = $NamaAyah;
		$sNamaIbu = $NamaIbu;
		$sNoSuratTitipan = $NoSuratTitipan;
		$sWargaGereja = $WargaGereja;
		$sWargaGerejaNonGKJ = $WargaGerejaNonGKJ;
		$sAlamatGerejaNonGKJ = $sAlamatGerejaNonGKJ;
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

	if (strlen($iBaptisID) > 0)
	{
	$sSQL = "select a.* , a.per_id, a.per_firstname as NamaPemohonBaptis , a.per_gender as JK , a.per_fam_id ,
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
$sSQL = "select a.* , a.per_id, a.per_firstname as NamaPemohonBaptis , a.per_gender as JK , a.per_fam_id ,
 c.per_firstname as NamaAyah, d.per_firstname as NamaIbu,
x.c1 as TglBaptis, x.c26 as TempatBaptis, x.c37 as DiBaptisOleh
from person_per a
left join person_custom x ON a.per_id = x.per_id 
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
where a.per_fmr_id = 3 and a.per_cls_id <6
and x.c26 is NULL 
order by a.per_firstname ";
}

$rsNamaPemohonBaptis = RunQuery($sSQL);

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

<form method="post" action="BaptisAnakEditor.php?BaptisID=<?php echo $iBaptisID; ?>" name="BaptisEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="BaptisSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"BaptisSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iBaptisID) > 0) 
{ echo "SelectListApp.php?mode=BaptisAnak&amp;GID=$refresh"; 
} else {echo "SelectListApp.php?mode=BaptisAnak&amp;GID=$refresh"; 
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
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Permohonan:"); ?></td>
		<td class="TextColumn"><input type="text" name="TanggalBaptis" value="<?php echo $sTanggalBaptis; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalBaptisError ?></font></td>
			<td class="LabelColumn"><?php echo gettext("Pukul :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="WaktuBaptis" >
				<option value="0"  <?php if ($sWaktuBaptis == "0") { echo " selected"; }?> ><?php echo gettext("TidakDiketahui"); ?></option>
				<option value="11"  <?php if ($sWaktuBaptis == "11") { echo " selected"; }?> ><?php echo gettext("06.00 WIB Cut Meutia"); ?></option>
				<option value="12"  <?php if ($sWaktuBaptis == "12") { echo " selected"; }?> ><?php echo gettext("09.00 WIB Cut Meutia"); ?></option>
				<option value="21"  <?php if ($sWaktuBaptis == "21") { echo " selected"; }?> ><?php echo gettext("07.00 WIB Cikarang"); ?></option>
				<option value="31"  <?php if ($sWaktuBaptis == "31") { echo " selected"; }?> ><?php echo gettext("07.30 WIB Karawang"); ?></option>
				<option value="41"  <?php if ($sWaktuBaptis == "41") { echo " selected"; }?> ><?php echo gettext("17.00 WIB Tambun"); ?></option>
			</select>
		</td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Dilayani Oleh :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="PendetaBaptis" >
						<option value="" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPendeta))
						{
							extract($aRow);

							echo "<option value=\"" . $NamaPendeta . "\"";
							if ($sPendetaBaptis == $NamaPendeta) { echo " selected"; }
							echo ">" . $NamaPendeta." - ".$NamaGereja;
						}
						?>

					</select>
		</td>
	</tr>

	<tr>
		<td class="LabelColumn"><?php echo gettext("Dilayani di :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="TempatBaptis" >
						
						<?php
						while ($aRow = mysql_fetch_array($rsNamaGereja))
						{
							extract($aRow);

							echo "<option value=\"" . $GerejaID . "\"";
							if ($sTempatBaptis == $GerejaID) { echo " selected"; }
							echo ">" . $NamaGereja." - ".$NamaKlasis;
						}
						?>
					</select>
		</td>
	</tr>		
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Nama Pemohon Baptis:"); ?></td>
				<td colspan="3" class="TextColumn">
					<select name="per_ID" size="10">
						<option value="0" selected><?php echo gettext("Tidak Diketahui / Bukan Warga "); ?></option>
						<option value="0">-----------------------</option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPemohonBaptis))
						{
							extract($aRow);

							echo "<option value=\"" . $per_ID . "\"";
							if($JK == 1 ){$hub = "putra ";}else{$hub = "putri ";}
							if ($sper_ID == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName . "&nbsp; - " .  $hub . "" . $NamaAyah . "/" . $NamaIbu . "&nbsp; - " . $per_WorkPhone;
						}
						?>

					</select>
				</td>
			</tr>

	<tr><td><tr>
		<td class="LabelColumn"><?php echo gettext("Ketua Majelis:"); ?></td>
		<td class="TextColumn"><input type="text" name="KetuaMajelis" id="KetuaMajelis" 
		value="<?php 
		if (strlen($iBaptisID) > 0)
		{ echo htmlentities(stripslashes($sKetuaMajelis),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		echo jabatanpengurus(61);
		}
		 ?>"><br><font color="red"><?php echo $sKetuaMajelisError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Sekretaris Majelis:"); ?></td>
		<td class="TextColumn"><input type="text" name="SekretarisMajelis" id="SekretarisMajelis" 
		value="<?php 
		if (strlen($iBaptisID) > 0)
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
			<td colspan="4" align="center"><h3><?php echo gettext("Isikan Data dibawah jika Bukan Warga Jemaat"); ?></h3></td>
	</tr>	
		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nama Pemohon(Jika bukan warga):"); ?></td>
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
		<td class="LabelColumn"><?php echo gettext("Warga Gereja non GKJ:"); ?></td>
		<td class="TextColumn"><input type="text" name="WargaGerejaNonGKJ" id="WargaGerejaNonGKJ" value="<?php echo htmlentities(stripslashes($sWargaGerejaNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sWargaGerejaNonGKJError ?></font></td>
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
		<td class="LabelColumn"><?php echo gettext("Nomor Reff Surat:"); ?></td>
		<td class="TextColumn"><input type="text" name="NoSuratTitipan" id="NoSuratTitipan" value="<?php echo htmlentities(stripslashes($sNoSuratTitipan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNoSuratTitipanError ?></font></td>
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
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iBaptisID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
//require "Include/Footer.php";
?>
