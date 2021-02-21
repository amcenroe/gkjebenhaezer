<?php
/*******************************************************************************
 *
 *  filename    : SidhiEditor.php
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
$sPageTitle = gettext("Pendaftaran Sidhi");

//Get the SidhiID out of the querystring
$iSidhiID = FilterInput($_GET["SidhiID"],'int');
$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;
// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?SidhiID= manually)
if (strlen($iSidhiID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rssidhi = RunQuery($sSQL);
	extract(mysql_fetch_array($rssidhi));

	if (mysql_num_rows($rssidhi) == 0)
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

if (isset($_POST["SidhiSubmit"]) || isset($_POST["SidhiSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sSidhiID = FilterInput($_POST["SidhiID"]);
	$sper_ID = FilterInput($_POST["per_ID"]);
	$sPendetaSidhi = FilterInput($_POST["PendetaSidhi"]);
	$sKetuaMajelis = FilterInput($_POST["KetuaMajelis"]);
	$sSekretarisMajelis = FilterInput($_POST["SekretarisMajelis"]);
	$sTanggalRencanaSidhi = FilterInput($_POST["TanggalRencanaSidhi"]);
	$sWaktuSidhi = FilterInput($_POST["WaktuSidhi"]);
	$sTempatSidhi = FilterInput($_POST["TempatSidhi"]);	
	$sTempatRetreat = FilterInput($_POST["TempatRetreat"]);		
	$sTanggalCetak = FilterInput($_POST["TanggalCetak"]);
	$sNamaLengkap = FilterInput($_POST["NamaLengkap"]);
	$sTempatLahir = FilterInput($_POST["TempatLahir"]);
	$sTanggalLahir = FilterInput($_POST["TanggalLahir"]);
	$sNamaAyah = FilterInput($_POST["NamaAyah"]);
	$sNamaIbu = FilterInput($_POST["NamaIbu"]);
	$sTanggalBaptis = FilterInput($_POST["TanggalBaptis"]);
	$sTempatBaptis = FilterInput($_POST["TempatBaptis"]);
	$sPendetaBaptis = FilterInput($_POST["PendetaBaptis"]);
	$sNoSuratTitipan = FilterInput($_POST["NoSuratTitipan"]);
	$sDateLastEdited = FilterInput($_POST["DateLastEdited"]);
	$sDateEntered = FilterInput($_POST["DateEntered"]);
	$sEnteredBy = FilterInput($_POST["EnteredBy"]);
	$sEditedBy = FilterInput($_POST["EditedBy"]);
	$sPembimbing = FilterInput($_POST["Pembimbing"]);
	$sTglMulaiKatekisasi = FilterInput($_POST["TglMulaiKatekisasi"]);
	$sTglSelesaiKatekisasi = FilterInput($_POST["TglSelesaiKatekisasi"]);
	
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
		if (strlen($iSidhiID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO sidhigkjbekti ( 
			SidhiID,
			per_ID,
			PendetaSidhi,
			KetuaMajelis,
			SekretarisMajelis,
			TanggalRencanaSidhi,
			WaktuSidhi,
			TempatSidhi,
			TempatRetreat,
			TglMulaiKatekisasi,
			TglSelesaiKatekisasi,
			Pembimbing,
			TanggalCetak,
			NamaLengkap,
			TempatLahir,
			TanggalLahir,
			NamaAyah,
			NamaIbu,
			TanggalBaptis,
			TempatBaptis,
			PendetaBaptis,
			NoSuratTitipan,
			WargaGereja,
			WargaGerejaNonGKJ,
			AlamatGerejaNonGKJ,
			DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sSidhiID . "',
			'" . $sper_ID . "',
			'" . $sPendetaSidhi . "',
			'" . $sKetuaMajelis . "',
			'" . $sSekretarisMajelis . "',
			'" . $sTanggalRencanaSidhi . "',
			'" . $sWaktuSidhi . "',
			'" . $sTempatSidhi . "',	
			'" . $sTempatRetreat . "',
			'" . $sTglMulaiKatekisasi . "',
			'" . $sTglSelesaiKatekisasi . "',
			'" . $sPembimbing . "',
			'" . $sTanggalCetak . "',
			'" . $sNamaLengkap . "',
			'" . $sTempatLahir . "',
			'" . $sTanggalLahir . "',
			'" . $sNamaAyah . "',
			'" . $sNamaIbu . "',
			'" . $sTanggalBaptis . "',
			'" . $sTempatBaptis . "',
			'" . $sPendetaBaptis . "',
			'" . $sNoSuratTitipan . "',
			'" . $sWargaGereja . "',
			'" . $sWargaGerejaNonGKJ . "',
			'" . $sAlamatGerejaNonGKJ . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New Sidhi Data";


		// Existing Sidhi (update)
		} else {
	//update the sidhi table
			$sSQL = "UPDATE sidhigkjbekti SET 
			
					per_ID = '" . $sper_ID . "',
					PendetaSidhi = '" . $sPendetaSidhi . "',
					KetuaMajelis = '" . $sKetuaMajelis . "',
					SekretarisMajelis = '" . $sSekretarisMajelis . "',
					TanggalRencanaSidhi = '" . $sTanggalRencanaSidhi . "',
					WaktuSidhi = '" . $sWaktuSidhi . "',
					TempatSidhi = '" . $sTempatSidhi . "',	
					TempatRetreat = '" . $sTempatRetreat . "',	
					TglMulaiKatekisasi = '" . $sTglMulaiKatekisasi . "',	
					TglSelesaiKatekisasi = '" . $sTglSelesaiKatekisasi . "',	
					Pembimbing = '" . $sPembimbing . "',	
					TanggalCetak = '" . $sTanggalCetak . "',
					NamaLengkap = '" . $sNamaLengkap . "',
					TempatLahir = '" . $sTempatLahir . "',
					TanggalLahir = '" . $sTanggalLahir . "',
					NamaAyah = '" . $sNamaAyah . "',
					NamaIbu = '" . $sNamaIbu . "',
					TanggalBaptis = '" . $sTanggalBaptis . "',
					TempatBaptis = '" . $sTempatBaptis . "',
					PendetaBaptis = '" . $sPendetaBaptis . "',
					NoSuratTitipan = '" . $sNoSuratTitipan . "',
					WargaGereja = '" . $sWargaGereja . "',
					WargaGerejaNonGKJ = '" . $sWargaGerejaNonGKJ . "',
					AlamatGerejaNonGKJ = '" . $sAlamatGerejaNonGKJ . "',
					DateLastEdited = '" . date("YmdHis") . "',
					EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE SidhiID = " . $iSidhiID;


	

			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Sidhi Data";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		
					// update the main database
				$sSQLGKJ = "SELECT * FROM DaftarGerejaGKJ  WHERE GerejaID = " . $sTempatSidhi . " LIMIT 1";
				$rsGKJ = RunQuery($sSQLGKJ);
				extract(mysql_fetch_array($rsGKJ));
				$sNamaGereja = $NamaGereja;		
					
				$sSQL2 = "UPDATE person_custom  SET 
					c2 = '" . $sTanggalRencanaSidhi . "',					
					c27 = '" . $sNamaGereja. "',
					c38 = '" . $sPendetaSidhi  ;
								$sSQL2 .= "' WHERE per_ID = " . $sper_ID;
		RunQuery($sSQL2);
		
		

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iSidhiID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. SidhiEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iSidhiID);
		}
		else if (isset($_POST["SidhiSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectListApp.php?mode=Sidhi&amp;GID=$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("SidhiEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iSidhiID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM sidhigkjbekti  WHERE SidhiID = " . $iSidhiID;
		$rssidhi = RunQuery($sSQL);
		extract(mysql_fetch_array($rssidhi));

		$sSidhiID = $SidhiID;
		$sper_ID = $per_ID;
		$sPendetaSidhi = $PendetaSidhi;
		$sKetuaMajelis = $KetuaMajelis;
		$sSekretarisMajelis = $SekretarisMajelis;
		$sTanggalRencanaSidhi = $TanggalRencanaSidhi;
		$sWaktuSidhi = $WaktuSidhi;
		$sTempatSidhi = $TempatSidhi;	
		$sTempatRetreat = $TempatRetreat;	
		$sTglMulaiKatekisasi = $TglMulaiKatekisasi;
		$sTglSelesaiKatekisasi = $TglSelesaiKatekisasi;
		$sPembimbing = $Pembimbing;
		$sTanggalCetak = $TanggalCetak;
		$sNamaLengkap = $NamaLengkap;
		$sTempatLahir = $TempatLahir;
		$sTanggalLahir = $TanggalLahir;
		$sNamaAyah = $NamaAyah;
		$sNamaIbu = $NamaIbu;
		$sTanggalBaptis = $TanggalBaptis;
		$sTempatBaptis = $TempatBaptis;
		$sPendetaBaptis = $PendetaBaptis;
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

	if (strlen($iSidhiID) > 0)
	{
	$sSQL = "select a.* , a.per_id, a.per_firstname as NamaPemohonSidhi , a.per_gender as JK , a.per_fam_id ,
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
$sSQL = "select a.* , a.per_id, a.per_firstname as NamaPemohonSidhi , a.per_gender as JK , a.per_fam_id ,
 c.per_firstname as NamaAyah, d.per_firstname as NamaIbu,
x.c1 as TglBaptis, x.c26 as TempatBaptis, x.c37 as DiBaptisOleh
from person_per a
left join person_custom x ON a.per_id = x.per_id 
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
where a.per_fmr_id = 3 and a.per_cls_id <3 
and x.c26 <> '' and x.c27 is NULL 
order by a.per_firstname ";
}

$rsNamaPemohonSidhi = RunQuery($sSQL);

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

//Get Daftar GKJ Names for the drop-down non GKJ Bekti - tempat retreat
$sSQL = "SELECT * FROM DaftarGerejaGKJ a 
LEFT JOIN DaftarKlasisGKJ b ON a.KlasisID=b.KlasisID
ORDER BY GerejaID, NamaGereja";
$rsNamaGereja3 = RunQuery($sSQL);

// Get Nama Pejabat



require "Include/Header.php";

?>

<form method="post" action="SidhiEditor.php?SidhiID=<?php echo $iSidhiID; ?>" name="SidhiEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="SidhiSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"SidhiSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iSidhiID) > 0) 
{ echo "SelectListApp.php?mode=Sidhi&amp;GID=$refresh"; 
} else {echo "SelectListApp.php?mode=Sidhi&amp;GID=$refresh"; 
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
		<td class="TextColumn"><input type="text" name="TanggalRencanaSidhi" value="<?php echo $sTanggalRencanaSidhi; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalRencanaSidhiError ?></font></td>
	
		<td class="TextColumnWithBottomBorder">
			<select name="WaktuSidhi" >
				<option value="0"  <?php if ($sWaktuSidhi == "0") { echo " selected"; }?> ><?php echo gettext("TidakDiketahui"); ?></option>
				<option value="11"  <?php if ($sWaktuSidhi == "11") { echo " selected"; }?> ><?php echo gettext("06.00 WIB Cut Meutia"); ?></option>
				<option value="12"  <?php if ($sWaktuSidhi == "12") { echo " selected"; }?> ><?php echo gettext("09.00 WIB Cut Meutia"); ?></option>
				<option value="21"  <?php if ($sWaktuSidhi == "21") { echo " selected"; }?> ><?php echo gettext("07.00 WIB Cikarang"); ?></option>
				<option value="31"  <?php if ($sWaktuSidhi == "31") { echo " selected"; }?> ><?php echo gettext("07.30 WIB Karawang"); ?></option>
				<option value="41"  <?php if ($sWaktuSidhi == "41") { echo " selected"; }?> ><?php echo gettext("17.00 WIB Tambun"); ?></option>
			</select>
		</td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Dilayani Oleh :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspans="2" >
					<select name="PendetaSidhi" >
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPendeta))
						{
							extract($aRow);

							echo "<option value=\"" . $NamaPendeta . "\"";
							if ($sPendetaSidhi == $NamaPendeta) { echo " selected"; }
							echo ">" . $NamaPendeta." - ".$NamaGereja;
						}
						?>

					</select>
		</td>
		<tr>
		<td class="LabelColumn"><?php echo gettext("Dilayani di :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="TempatSidhi" >
						
						<?php
						while ($aRow = mysql_fetch_array($rsNamaGereja))
						{
							extract($aRow);

							echo "<option value=\"" . $GerejaID . "\"";
							if ($sTempatSidhi == $GerejaID) { echo " selected"; }
							echo ">" . $NamaGereja." - ".$NamaKlasis;
						}
						?>
					</select>
		</td>
	</tr>	

		<tr>
		<td class="LabelColumn"><?php echo gettext("Tempat Katekisasi/Retreat Katekumen:"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="TempatRetreat" >
						
						<?php
						while ($aRow = mysql_fetch_array($rsNamaGereja3))
						{
							extract($aRow);

							echo "<option value=\"" . $GerejaID . "\"";
							if ($sTempatRetreat == $GerejaID) { echo " selected"; }
							echo ">" . $NamaGereja." - ".$NamaKlasis;
						}
						?>
					</select>
		</td>
	</tr>	
	<tr>		
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Mulai Katekisasi:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglMulaiKatekisasi" value="<?php echo $sTglMulaiKatekisasi; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTglMulaiKatekisasiError ?></font></td>

		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Mulai Katekisasi:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglSelesaiKatekisasi" value="<?php echo $sTglSelesaiKatekisasi; ?>" maxlength="10" id="sel2" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel2', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTglSelesaiKatekisasiError ?></font></td>

	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nama Pembimbing Katekisasi:"); ?></td>
		<td class="TextColumn"><input type="text" name="Pembimbing" id="Pembimbing" value="<?php echo htmlentities(stripslashes($sPembimbing),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPembimbingError ?></font></td>
	</tr>
	</tr>	
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Nama Pemohon Sidhi:"); ?></td>
				<td colspan="3" class="TextColumn">
					<select name="per_ID" size="10">
						<option value="0" selected><?php echo gettext("Tidak Diketahui / Bukan Warga "); ?></option>
						<option value="0">-----------------------</option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPemohonSidhi))
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
		<td class="LabelColumn"><?php // echo gettext("Ketua Majelis:"); ?></td>
		<td class="TextColumn"><input type="hidden" type="text" name="KetuaMajelis" id="KetuaMajelis" 
		value="<?php 
		if (strlen($iSidhiID) > 0)
		{ echo htmlentities(stripslashes($sKetuaMajelis),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		echo jabatanpengurus(61);
		}
		 ?>"><br><font color="red"><?php echo $sKetuaMajelisError ?></font></td>

		<td class="LabelColumn"><?php //echo gettext("Sekretaris Majelis:"); ?></td>
		<td class="TextColumn"><input type="hidden" type="text" name="SekretarisMajelis" id="SekretarisMajelis" 
		value="<?php 
		if (strlen($iSidhiID) > 0)
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
				<td colspan="4" align="center"><h3><?php echo gettext("Isikan Data dibawah jika bukan Warga jemaat"); ?></h3></td>
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
	


<?php /*
**	<tr>
**		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Test4:"); ?></td>
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
		$logvar2 = "Pendaftaran Sidhi Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iSidhiID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
