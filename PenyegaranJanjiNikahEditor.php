<?php
/*******************************************************************************
 *
 *  filename    : PenyegaranJanjiNikahEditor.php
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
$sPageTitle = gettext("Pendaftaran Penyegaran Janji Nikah");

//Get the NikahID out of the querystring
$iNikahID = FilterInput($_GET["NikahID"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?NikahID= manually)
if (strlen($iNikahID) > 0)
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

if (isset($_POST["NikahSubmit"]) || isset($_POST["NikahSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sNikahID = FilterInput($_POST["NikahID"]);
	$sfam_ID = FilterInput($_POST["fam_ID"]);
	$sper_ID_L = FilterInput($_POST["per_ID_L"]);
	$sper_ID_P = FilterInput($_POST["per_ID_P"]);
 
	$sPendetaID = FilterInput($_POST["PendetaID"]);
	$sKetuaMajelis = FilterInput($_POST["KetuaMajelis"]);
	$sSekretarisMajelis = FilterInput($_POST["SekretarisMajelis"]);
	$sTanggalNikah = FilterInput($_POST["TanggalNikah"]);
	$sWaktuNikah = FilterInput($_POST["WaktuNikah"]);
	$sTempatNikah = FilterInput($_POST["TempatNikah"]);
	$sTanggalCetak = FilterInput($_POST["TanggalCetak"]);
 
	$sNamaLengkapL = FilterInput($_POST["NamaLengkapL"]);
	$sTempatLahirL = FilterInput($_POST["TempatLahirL"]);
	$sTanggalLahirL = FilterInput($_POST["TanggalLahirL"]);
	$sNamaAyahL = FilterInput($_POST["NamaAyahL"]);
	$sNamaIbuL = FilterInput($_POST["NamaIbuL"]);
	$sTanggalBaptisL = FilterInput($_POST["TanggalBaptisL"]);
	$sTempatBaptisL = FilterInput($_POST["TempatBaptisL"]);
	$sPendetaBaptisL = FilterInput($_POST["PendetaBaptisL"]);
	$sTanggalSidhiL = FilterInput($_POST["TanggalSidhiL"]);
	$sTempatSidhiL = FilterInput($_POST["TempatSidhiL"]);
	$sPendetaSidhiL = FilterInput($_POST["PendetaSidhiL"]);
	$sTanggalBaptisDL = FilterInput($_POST["TanggalBaptisDL"]);
	$sTempatBaptisDL = FilterInput($_POST["TempatBaptisDL"]);
	$sPendetaBaptisDL = FilterInput($_POST["PendetaBaptisDL"]);	
	$sNoSuratTitipanL = FilterInput($_POST["NoSuratTitipanL"]);
	$sWargaGerejaL = FilterInput($_POST["WargaGerejaL"]);
	$sWargaGerejaNonGKJL = FilterInput($_POST["WargaGerejaNonGKJL"]);
	$sAlamatGerejaNonGKJL = FilterInput($_POST["AlamatGerejaNonGKJL"]);
 
	$sNamaLengkapP = FilterInput($_POST["NamaLengkapP"]);
	$sTempatLahirP = FilterInput($_POST["TempatLahirP"]);
	$sTanggalLahirP = FilterInput($_POST["TanggalLahirP"]);
	$sNamaAyahP = FilterInput($_POST["NamaAyahP"]);
	$sNamaIbuP = FilterInput($_POST["NamaIbuP"]);
	$sTanggalBaptisP = FilterInput($_POST["TanggalBaptisP"]);
	$sTempatBaptisP = FilterInput($_POST["TempatBaptisP"]);
	$sPendetaBaptisP = FilterInput($_POST["PendetaBaptisP"]);
	$sTanggalSidhiP = FilterInput($_POST["TanggalSidhiP"]);
	$sTempatSidhiP = FilterInput($_POST["TempatSidhiP"]);
	$sPendetaSidhiP = FilterInput($_POST["PendetaSidhiP"]);
	$sTanggalBaptisDP = FilterInput($_POST["TanggalBaptisDP"]);
	$sTempatBaptisDP = FilterInput($_POST["TempatBaptisDP"]);
	$sPendetaBaptisDP = FilterInput($_POST["PendetaBaptisDP"]);
	$sNoSuratTitipanP = FilterInput($_POST["NoSuratTitipanP"]);
	$sWargaGerejaP = FilterInput($_POST["WargaGerejaP"]);
	$sWargaGerejaNonGKJP = FilterInput($_POST["WargaGerejaNonGKJP"]);
	$sAlamatGerejaNonGKJP = FilterInput($_POST["AlamatGerejaNonGKJP"]);
	
	$sNikahGerejaNonGKJ = FilterInput($_POST["NikahGerejaNonGKJ"]);
	$sAlamatNikahGerejaNonGKJ = FilterInput($_POST["AlamatNikahGerejaNonGKJ"]);
	$sPendetaNikahGerejaNonGKJ = FilterInput($_POST["PendetaNikahGerejaNonGKJ"]);

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
		if (strlen($iNikahID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO PermohonanPenyegaranJanjiNikah ( 
			
			fam_ID, 
			per_ID_L,
			per_ID_P,
 
			PendetaID,
			KetuaMajelis,
			SekretarisMajelis,
			TanggalNikah,
			WaktuNikah,
			TempatNikah,
			TanggalCetak,
 
			NamaLengkapL,
			TempatLahirL,
			TanggalLahirL,
			NamaAyahL,
			NamaIbuL,
			TanggalBaptisL,
			TempatBaptisL,
			PendetaBaptisL,
			TanggalSidhiL,
			TempatSidhiL,
			PendetaSidhiL,
			TanggalBaptisDL,
			TempatBaptisDL,
			PendetaBaptisDL,
			NoSuratTitipanL,
			WargaGerejaL,
			WargaGerejaNonGKJL,
			AlamatGerejaNonGKJL,
 
			NamaLengkapP,
			TempatLahirP,
			TanggalLahirP,
			NamaAyahP,
			NamaIbuP,
			TanggalBaptisP,
			TempatBaptisP,
			PendetaBaptisP,
			TanggalSidhiP,
			TempatSidhiP,
			PendetaSidhiP,
			TanggalBaptisDP,
			TempatBaptisDP,
			PendetaBaptisDP,
			NoSuratTitipanP,
			WargaGerejaP,
			WargaGerejaNonGKJP,
			AlamatGerejaNonGKJP,
			
			NikahGerejaNonGKJ,
			AlamatNikahGerejaNonGKJ,
			PendetaNikahGerejaNonGKJ,

  
			DateEntered,
			EnteredBy )
			VALUES ( 
			'" . $sfam_ID . "',
			'" . $sper_ID_L . "',
			'" . $sper_ID_P . "',
 
			'" . $sPendetaID . "',
			'" . $sKetuaMajelis . "',
			'" . $sSekretarisMajelis . "',
			'" . $sTanggalNikah . "',
			'" . $sWaktuNikah . "',
			'" . $sTempatNikah . "',
			'" . $sTanggalCetak . "',
 
			'" . $sNamaLengkapL . "',
			'" . $sTempatLahirL . "',
			'" . $sTanggalLahirL . "',
			'" . $sNamaAyahL . "',
			'" . $sNamaIbuL . "',
			'" . $sTanggalBaptisL . "',
			'" . $sTempatBaptisL . "',
			'" . $sPendetaBaptisL . "',
			'" . $sTanggalSidhiL . "',
			'" . $sTempatSidhiL . "',
			'" . $sPendetaSidhiL . "',
			'" . $sTanggalBaptisDL . "',
			'" . $sTempatBaptisDL . "',
			'" . $sPendetaBaptisDL . "',
			'" . $sNoSuratTitipanL . "',
			'" . $sWargaGerejaL . "',
			'" . $sWargaGerejaNonGKJL . "',
			'" . $sAlamatGerejaNonGKJL . "',
 
			'" . $sNamaLengkapP . "',
			'" . $sTempatLahirP . "',
			'" . $sTanggalLahirP . "',
			'" . $sNamaAyahP . "',
			'" . $sNamaIbuP . "',
			'" . $sTanggalBaptisP . "',
			'" . $sTempatBaptisP . "',
			'" . $sPendetaBaptisP . "',
			'" . $sTanggalSidhiP . "',
			'" . $sTempatSidhiP . "',
			'" . $sPendetaSidhiP . "',
			'" . $sTanggalBaptisDP . "',
			'" . $sTempatBaptisDP . "',
			'" . $sPendetaBaptisDP . "',
			'" . $sNoSuratTitipanP . "',
			'" . $sWargaGerejaP . "',
			'" . $sWargaGerejaNonGKJP . "',
			'" . $sAlamatGerejaNonGKJP . "',
			
			'" . $sNikahGerejaNonGKJ . "',
			'" . $sAlamatNikahGerejaNonGKJ . "',
			'" . $sPendetaNikahGerejaNonGKJ . "',
			
  
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New Sidhi Data";


		// Existing Sidhi (update)
		} else {
	//update the sidhi table
			$sSQL = "UPDATE PermohonanPenyegaranJanjiNikah SET 
					
					fam_ID = '" . $sfam_ID . "',
					per_ID_L = '" . $sper_ID_L . "',
					per_ID_P = '" . $sper_ID_P . "',
 
					PendetaID = '" . $sPendetaID . "',
					KetuaMajelis = '" . $sKetuaMajelis . "',
					SekretarisMajelis = '" . $sSekretarisMajelis . "',
					TanggalNikah = '" . $sTanggalNikah . "',
					WaktuNikah = '" . $sWaktuNikah . "',
					TempatNikah = '" . $sTempatNikah . "',
					TanggalCetak = '" . $sTanggalCetak . "',
 
					NamaLengkapL = '" . $sNamaLengkapL . "',
					TempatLahirL = '" . $sTempatLahirL . "',
					TanggalLahirL = '" . $sTanggalLahirL . "',
					NamaAyahL = '" . $sNamaAyahL . "',
					NamaIbuL = '" . $sNamaIbuL . "',
					TanggalBaptisL = '" . $sTanggalBaptisL . "',
					TempatBaptisL = '" . $sTempatBaptisL . "',
					PendetaBaptisL = '" . $sPendetaBaptisL . "',
					TanggalSidhiL = '" . $sTanggalSidhiL . "',
					TempatSidhiL = '" . $sTempatSidhiL . "',
					PendetaSidhiL = '" . $sPendetaSidhiL . "',
					TanggalBaptisDL = '" . $sTanggalBaptisDL . "',
					TempatBaptisDL = '" . $sTempatBaptisDL . "',
					PendetaBaptisDL = '" . $sPendetaBaptisDL . "',
					NoSuratTitipanL = '" . $sNoSuratTitipanL . "',
					WargaGerejaL = '" . $sWargaGerejaL . "',
					WargaGerejaNonGKJL = '" . $sWargaGerejaNonGKJL . "',
					AlamatGerejaNonGKJL = '" . $sAlamatGerejaNonGKJL . "',
 
					NamaLengkapP = '" . $sNamaLengkapP . "',
					TempatLahirP = '" . $sTempatLahirP . "',
					TanggalLahirP = '" . $sTanggalLahirP . "',
					NamaAyahP = '" . $sNamaAyahP . "',
					NamaIbuP = '" . $sNamaIbuP . "',
					TanggalBaptisP = '" . $sTanggalBaptisP . "',
					TempatBaptisP = '" . $sTempatBaptisP . "',
					PendetaBaptisP = '" . $sPendetaBaptisP . "',
					TanggalSidhiP = '" . $sTanggalSidhiP . "',
					TempatSidhiP = '" . $sTempatSidhiP . "',
					PendetaSidhiP = '" . $sPendetaSidhiP . "',
					TanggalBaptisDP = '" . $sTanggalBaptisDP . "',
					TempatBaptisDP = '" . $sTempatBaptisDP . "',
					PendetaBaptisDP = '" . $sPendetaBaptisDP . "',
					NoSuratTitipanP = '" . $sNoSuratTitipanP . "',
					WargaGerejaP = '" . $sWargaGerejaP . "',
					WargaGerejaNonGKJP = '" . $sWargaGerejaNonGKJP . "',
					AlamatGerejaNonGKJP = '" . $sAlamatGerejaNonGKJP . "',
					
					NikahGerejaNonGKJ = '" . $sNikahGerejaNonGKJ . "',
					AlamatNikahGerejaNonGKJ = '" . $sAlamatNikahGerejaNonGKJ . "',
					PendetaNikahGerejaNonGKJ = '" . $sPendetaNikahGerejaNonGKJ . "',
  
					DateLastEdited = '" . date("YmdHis") . "',
					EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE NikahID = " . $iNikahID;
			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Penyegaran Janji Nikah Data";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		
					// update the main database
//				$sSQLGKJ = "SELECT * FROM DaftarGerejaGKJ  WHERE GerejaID = " . $sTempatSidhi . " LIMIT 1";
//				$rsGKJ = RunQuery($sSQLGKJ);
//				extract(mysql_fetch_array($rsGKJ));
//				$sNamaGereja = $NamaGereja;		
					
//				$sSQL2 = "UPDATE person_custom  SET 
//					c2 = '" . $sTanggalRencanaSidhi . "',					
//					c27 = '" . $sNamaGereja. "',
//					c38 = '" . $sPendetaSidhi  ;
//								$sSQL2 .= "' WHERE per_ID = " . $sper_ID;
//		RunQuery($sSQL2);
		
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iNikahID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PenyegaranJanjiNikahEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iNikahID);
		}
		else if (isset($_POST["NikahSubmit"]))
		{
			//Send to the view of this Nikah
			Redirect("SelectListApp.php?mode=PenyegaranJanjiNikah&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PenyegaranJanjiNikahEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iNikahID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM PermohonanPenyegaranJanjiNikah  WHERE NikahID = " . $iNikahID;
		$rssidhi = RunQuery($sSQL);
		extract(mysql_fetch_array($rssidhi));

	$sNikahID = $NikahID;
	$sfam_ID = $fam_ID;
	$sper_ID_L = $per_ID_L;
	$sper_ID_P = $per_ID_P;
 
	$sPendetaID = $PendetaID;
	$sKetuaMajelis = $KetuaMajelis;
	$sSekretarisMajelis = $SekretarisMajelis;
	$sTanggalNikah = $TanggalNikah;
	$sWaktuNikah = $WaktuNikah;
	$sTempatNikah = $TempatNikah;
	$sTanggalCetak = $TanggalCetak;
 
	$sNamaLengkapL = $NamaLengkapL;
	$sTempatLahirL = $TempatLahirL;
	$sTanggalLahirL = $TanggalLahirL;
	$sNamaAyahL = $NamaAyahL;
	$sNamaIbuL = $NamaIbuL;
	$sTanggalBaptisL = $TanggalBaptisL;
	$sTempatBaptisL = $TempatBaptisL;
	$sPendetaBaptisL = $PendetaBaptisL;
	$sTanggalSidhiL = $TanggalSidhiL;
	$sTempatSidhiL = $TempatSidhiL;
	$sPendetaSidhiL = $PendetaSidhiL;
	$sTanggalBaptisDL = $TanggalBaptisDL;
	$sTempatBaptisDL = $TempatBaptisDL;
	$sPendetaBaptisDL = $PendetaBaptisDL;
	$sNoSuratTitipanL = $NoSuratTitipanL;
	$sWargaGerejaL = $WargaGerejaL;
	$sWargaGerejaNonGKJL = $WargaGerejaNonGKJL;
	$sAlamatGerejaNonGKJL = $AlamatGerejaNonGKJL;
 
	$sNamaLengkapP = $NamaLengkapP;
	$sTempatLahirP = $TempatLahirP;
	$sTanggalLahirP = $TanggalLahirP;
	$sNamaAyahP = $NamaAyahP;
	$sNamaIbuP = $NamaIbuP;
	$sTanggalBaptisP = $TanggalBaptisP;
	$sTempatBaptisP = $TempatBaptisP;
	$sPendetaBaptisP = $PendetaBaptisP;
	$sTanggalSidhiP = $TanggalSidhiP;
	$sTempatSidhiP = $TempatSidhiP;
	$sPendetaSidhiP = $PendetaSidhiP;
	$sTanggalBaptisDP = $TanggalBaptisDP;
	$sTempatBaptisDP = $TempatBaptisDP;
	$sPendetaBaptisDP = $PendetaBaptisDP;
	$sNoSuratTitipanP = $NoSuratTitipanP;
	$sWargaGerejaP = $WargaGerejaP;
	$sWargaGerejaNonGKJP = $WargaGerejaNonGKJP;
	$sAlamatGerejaNonGKJP = $AlamatGerejaNonGKJP;
	
	$sNikahGerejaNonGKJ = $NikahGerejaNonGKJ;
	$sAlamatNikahGerejaNonGKJ = $AlamatNikahGerejaNonGKJ;
	$sPendetaNikahGerejaNonGKJ = $PendetaNikahGerejaNonGKJ;
		
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

// Nama Keluarga Pemohon Penyegaran Perkawinan


	if (strlen($iNikahID) > 0)
	{
	$sSQL = "SELECT a.*, b.fam_WorkPhone as Kelompok,
	g.per_FirstName as NamaAyah,
	h.per_FirstName as NamaIbu 

	FROM PermohonanPenyegaranJanjiNikah a
	
	LEFT JOIN family_fam b ON a.fam_id = b.fam_id

	LEFT JOIN person_per g ON (b.fam_id = g.per_fam_id AND g.per_fmr_id = 1 AND g.per_gender = 1)	
	LEFT JOIN person_custom i ON g.per_id = i.per_id

	LEFT JOIN person_per h ON (b.fam_id = h.per_fam_id AND h.per_fmr_id = 2 AND h.per_gender = 2)
	LEFT JOIN person_custom j ON h.per_id = j.per_id
	
	WHERE a.NikahID = $iNikahID 

	";
	}
	else
	{
$sSQL = "SELECT a.*, a.fam_WorkPhone as Kelompok,
	g.per_FirstName as NamaAyah,
	h.per_FirstName as NamaIbu 
	from family_fam a
	LEFT JOIN person_per g ON (a.fam_id = g.per_fam_id AND g.per_fmr_id = 1 AND g.per_gender = 1)	
	LEFT JOIN person_custom i ON g.per_id = i.per_id

	LEFT JOIN person_per h ON (a.fam_id = h.per_fam_id AND h.per_fmr_id = 2 AND h.per_gender = 2)
	LEFT JOIN person_custom j ON h.per_id = j.per_id
	
	WHERE ((g.per_fmr_id = 1 AND g.per_cls_id != 6) AND (h.per_fmr_id = 2 AND h.per_cls_id != 6))
ORDER by fam_WorkPhone, fam_Name


";
}

$rsNamaPemohonPenyegaranJanjiNikah = RunQuery($sSQL);




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
ORDER BY GerejaID ASC, NamaGereja ASC";
$rsNamaGereja2 = RunQuery($sSQL);

//Get Daftar GKJ Names for the drop-down non GKJ Bekti
$sSQL = "SELECT *, a.GerejaID as GerejaIDL FROM DaftarGerejaGKJ a 
LEFT JOIN DaftarKlasisGKJ b ON a.KlasisID=b.KlasisID
WHERE a.GerejaID > 1 
ORDER BY GerejaID ASC, NamaGereja ASC";
$rsNamaGereja2L = RunQuery($sSQL);
//Get Daftar GKJ Names for the drop-down non GKJ Bekti
$sSQL = "SELECT *, a.GerejaID as GerejaIDP FROM DaftarGerejaGKJ a 
LEFT JOIN DaftarKlasisGKJ b ON a.KlasisID=b.KlasisID
WHERE a.GerejaID > 1 
ORDER BY GerejaID ASC, NamaGereja ASC";
$rsNamaGereja2P = RunQuery($sSQL);

// Get Nama Pejabat

 

require "Include/Header.php";

?>

<form method="post" action="PenyegaranJanjiNikahEditor.php?NikahID=<?php echo $iNikahID; ?>" name="PenyegaranJanjiNikahEditor">

<table cellpadding="2" align="center" valign="top" border="0" width=90%>

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="NikahSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"NikahSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iNikahID) > 0) 
{ echo "SelectListApp.php?mode=PenyegaranJanjiNikah&amp;$refresh"; 
} else {echo "SelectListApp.php?mode=PenyegaranJanjiNikah&amp;$refresh"; 
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
				<td colspan="8" align="center"><h3><?php echo gettext("Data Standar"); ?></h3></td>
			</tr>
	<tr>		
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Permohonan:"); ?></td>
		<td class="TextColumn"><input type="text" name="TanggalNikah" value="<?php echo $sTanggalNikah; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalNikahError ?></font></td>
	
	
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Dilayani di :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="TempatNikah" >
						<option value="0" selected><?php echo gettext("Non GKJ"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaGereja))
						{
							extract($aRow);

							echo "<option value=\"" . $GerejaID . "\"";
							if ($sTempatNikah == $GerejaID) { echo " selected"; }
							echo ">" . $NamaGereja." - ".$NamaKlasis;
						}
						?>
					</select>
		</td>
	</tr>	
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Pukul:"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="WaktuNikah" >
				<option value="0" <?php if ($sWaktuNikah == "") { echo " selected"; }?> ><?php echo gettext("Tidak Diketahui"); ?></option>
				<option value="06.00 WIB"  <?php if ($sWaktuNikah == "06.00 WIB") { echo " selected"; }?> ><?php echo gettext("06.00 WIB"); ?></option>
				<option value="06.30 WIB"  <?php if ($sWaktuNikah == "06.30 WIB") { echo " selected"; }?> ><?php echo gettext("06.30 WIB"); ?></option>
				<option value="07.00 WIB"  <?php if ($sWaktuNikah == "07.00 WIB") { echo " selected"; }?> ><?php echo gettext("07.00 WIB"); ?></option>
				<option value="07.30 WIB"  <?php if ($sWaktuNikah == "07.30 WIB") { echo " selected"; }?> ><?php echo gettext("07.30 WIB"); ?></option>
				<option value="08.00 WIB"  <?php if ($sWaktuNikah == "08.00 WIB") { echo " selected"; }?> ><?php echo gettext("08.00 WIB"); ?></option>
				<option value="08.30 WIB"  <?php if ($sWaktuNikah == "08.30 WIB") { echo " selected"; }?> ><?php echo gettext("08.30 WIB"); ?></option>
				<option value="09.00 WIB"  <?php if ($sWaktuNikah == "09.00 WIB") { echo " selected"; }?> ><?php echo gettext("09.00 WIB"); ?></option>
				<option value="09.30 WIB"  <?php if ($sWaktuNikah == "09.30 WIB") { echo " selected"; }?> ><?php echo gettext("09.30 WIB"); ?></option>
				<option value="10.00 WIB"  <?php if ($sWaktuNikah == "10.00 WIB") { echo " selected"; }?> ><?php echo gettext("10.00 WIB"); ?></option>
				<option value="10.30 WIB"  <?php if ($sWaktuNikah == "10.30 WIB") { echo " selected"; }?> ><?php echo gettext("10.30 WIB"); ?></option>
				<option value="11.00 WIB"  <?php if ($sWaktuNikah == "11.00 WIB") { echo " selected"; }?> ><?php echo gettext("11.00 WIB"); ?></option>
				<option value="11.30 WIB"  <?php if ($sWaktuNikah == "11.30 WIB") { echo " selected"; }?> ><?php echo gettext("11.30 WIB"); ?></option>
				<option value="12.00 WIB"  <?php if ($sWaktuNikah == "12.00 WIB") { echo " selected"; }?> ><?php echo gettext("12.00 WIB"); ?></option>
				<option value="12.30 WIB"  <?php if ($sWaktuNikah == "12.30 WIB") { echo " selected"; }?> ><?php echo gettext("12.30 WIB"); ?></option>
				<option value="13.00 WIB"  <?php if ($sWaktuNikah == "13.00 WIB") { echo " selected"; }?> ><?php echo gettext("13.00 WIB"); ?></option>
				<option value="13.30 WIB"  <?php if ($sWaktuNikah == "13.30 WIB") { echo " selected"; }?> ><?php echo gettext("13.30 WIB"); ?></option>
				<option value="14.00 WIB"  <?php if ($sWaktuNikah == "14.00 WIB") { echo " selected"; }?> ><?php echo gettext("14.00 WIB"); ?></option>
				<option value="14.30 WIB"  <?php if ($sWaktuNikah == "14.30 WIB") { echo " selected"; }?> ><?php echo gettext("14.30 WIB"); ?></option>
				<option value="15.00 WIB"  <?php if ($sWaktuNikah == "15.00 WIB") { echo " selected"; }?> ><?php echo gettext("15.00 WIB"); ?></option>
				<option value="15.30 WIB"  <?php if ($sWaktuNikah == "15.30 WIB") { echo " selected"; }?> ><?php echo gettext("15.30 WIB"); ?></option>
				<option value="16.00 WIB"  <?php if ($sWaktuNikah == "16.00 WIB") { echo " selected"; }?> ><?php echo gettext("16.00 WIB"); ?></option>
				<option value="16.30 WIB"  <?php if ($sWaktuNikah == "16.30 WIB") { echo " selected"; }?> ><?php echo gettext("16.30 WIB"); ?></option>
				<option value="17.00 WIB"  <?php if ($sWaktuNikah == "17.00 WIB") { echo " selected"; }?> ><?php echo gettext("17.00 WIB"); ?></option>
				<option value="17.30 WIB"  <?php if ($sWaktuNikah == "17.30 WIB") { echo " selected"; }?> ><?php echo gettext("17.30 WIB"); ?></option>
				<option value="18.00 WIB"  <?php if ($sWaktuNikah == "18.00 WIB") { echo " selected"; }?> ><?php echo gettext("18.00 WIB"); ?></option>
				<option value="18.30 WIB"  <?php if ($sWaktuNikah == "18.30 WIB") { echo " selected"; }?> ><?php echo gettext("18.30 WIB"); ?></option>
				<option value="19.00 WIB"  <?php if ($sWaktuNikah == "19.00 WIB") { echo " selected"; }?> ><?php echo gettext("19.00 WIB"); ?></option>
			</select>
		</td>


	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Dilayani Oleh :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspans="2" >
					<select name="PendetaID" >
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPendeta))
						{
							extract($aRow);

							echo "<option value=\"" . $PendetaID . "\"";
							if ($sPendetaID == $PendetaID) { echo " selected"; }
							echo ">" . $NamaPendeta." - ".$NamaGereja;
						}
						?>

					</select>
		</td>
		<td class="LabelColumn"><?php echo gettext("Dilayani oleh (non GKJ):"); ?></td>
		<td class="TextColumn"><input type="text" name="PendetaNikahGerejaNonGKJ" id="PendetaNikahGerejaNonGKJ" value="<?php echo htmlentities(stripslashes($sPendetaNikahGerejaNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPendetaNikahGerejaNonGKJError ?></font></td>

	</tr>	
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Nama Keluarga Pemohon :"); ?></td>
				<td class="TextColumn" colspan="3">
					<select name="fam_ID" size="5" >

						<?php
						while ($aRow = mysql_fetch_array($rsNamaPemohonPenyegaranJanjiNikah))
						{
							extract($aRow);

							echo "<option value=\"" . $fam_ID . "\"";
							if ($sfam_ID == $fam_ID) { echo " selected"; }
							echo ">Keluarga Bp." . $NamaAyah . " dan Ibu." . $NamaIbu . "&nbsp; - Kelp." . $Kelompok;
						}
						?>

					</select>
				</td>
				
			</tr>

	<tr><td><tr>
		<td class="LabelColumn"><?php echo gettext("Ketua Majelis:"); ?></td>
		<td class="TextColumn" ><input type="text" name="KetuaMajelis" id="KetuaMajelis" 
		value="<?php 
		if (strlen($iNikahID) > 0)
		{ echo htmlentities(stripslashes($sKetuaMajelis),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		echo jabatanpengurus(61);
		}
		 ?>"><br><font color="red"><?php echo $sKetuaMajelisError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Sekretaris Majelis:"); ?></td>
		<td class="TextColumn"><input type="text" name="SekretarisMajelis" id="SekretarisMajelis" 
		value="<?php 
		if (strlen($iNikahID) > 0)
		{ echo htmlentities(stripslashes($sSekretarisMajelis),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		echo jabatanpengurus(65);
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
		$logvar2 = "Pendaftaran Penyegaran Janji Nikah Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iNikahID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
