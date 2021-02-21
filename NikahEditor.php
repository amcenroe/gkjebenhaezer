<?php
/*******************************************************************************
 *
 *  filename    : NikahEditor.php
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
$sPageTitle = gettext("Pendaftaran Nikah");

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

	$sBPNTempat = FilterInput($_POST["BPNTempat"]);
	$sBPNTempatNonGKJ = FilterInput($_POST["BPNTempatNonGKJ"]);
	$sBPNMulai = FilterInput($_POST["BPNMulai"]);
	$sBPNSelesai = FilterInput($_POST["BPNSelesai"]);

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

			$sSQL = "INSERT INTO PermohonanNikahgkjbekti ( 
			
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

			BPNTempat,
			BPNTempatNonGKJ,
			BPNMulai,
			BPNSelesai,
  
			DateEntered,
			EnteredBy )
			VALUES ( 
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

			'" . $sBPNTempat . "',
			'" . $sBPNTempatNonGKJ . "',
			'" . $sBPNMulai . "',
			'" . $sBPNSelesai . "',
  
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New Sidhi Data";


		// Existing Sidhi (update)
		} else {
	//update the sidhi table
			$sSQL = "UPDATE PermohonanNikahgkjbekti SET 
			
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
  
					BPNTempat = '" . $sBPNTempat . "',
					BPNTempatNonGKJ = '" . $sBPNTempatNonGKJ . "',
					BPNMulai = '" . $sBPNMulai . "',
					BPNSelesai = '" . $sBPNSelesai . "',

					DateLastEdited = '" . date("YmdHis") . "',
					EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE NikahID = " . $iNikahID;
			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Nikah Data";
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

		// Check for redirection to another page after saving information: (ie. NikahEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iNikahID);
		}
		else if (isset($_POST["NikahSubmit"]))
		{
			//Send to the view of this Nikah
			Redirect("SelectListApp.php?mode=Nikah");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("NikahEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iNikahID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM PermohonanNikahgkjbekti  WHERE NikahID = " . $iNikahID;
		$rssidhi = RunQuery($sSQL);
		extract(mysql_fetch_array($rssidhi));

	$sNikahID = $NikahID;
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
		
	$sBPNTempat = $BPNTempat;
	$sBPNTempatNonGKJ = $BPNTempatNonGKJ;
	$sBPNMulai = $BPNMulai;
	$sBPNSelesai = $BPNSelesai;

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

// Nama Pemohon Nikah Laki2

	if (strlen($iNikahID) > 0)
	{
	$sSQL = "select a.per_id as per_ID_L, a.per_FirstName AS NamaLengkap,a.per_Gender as JK,
	IF(c16 is NULL,c.per_FirstName,c16) as NamaAyah, 
	IF(c17 is NULL,d.per_FirstName,c17) as NamaIbu, 

a.per_WorkPhone as Kelompok
from person_per a natural join person_custom e
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
where a.per_Gender=1 
";
	}
	else
	{
$sSQL = "select a.per_id as per_ID_L, a.per_FirstName AS NamaLengkap,a.per_Gender as JK,
IF(c.per_firstname is NULL,c16,c.per_firstname) as NamaAyah,
IF(d.per_firstname is NULL,c17,d.per_firstname) as NamaIbu,
a.per_WorkPhone as Kelompok
from person_per a natural join person_custom
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)

where a.per_Gender=1 AND c15 = 1 AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
AND c27 is not NULL )
OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL))
order by a.per_FirstName";
}

$rsNamaPemohonNikahL = RunQuery($sSQL);

	if (strlen($iNikahID) > 0)
	{
	$sSQL = "select a.per_id as per_ID_P, a.per_FirstName AS NamaLengkap,a.per_Gender as JK,
	IF(c16 is NULL,c.per_FirstName,c16) as NamaAyah, 
	IF(c17 is NULL,d.per_FirstName,c17) as NamaIbu, 
a.per_WorkPhone as Kelompok
from person_per a natural join person_custom
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
where a.per_Gender=2
";
	}
	else
	{
$sSQL = "select a.per_id as per_ID_P, a.per_FirstName AS NamaLengkap,a.per_Gender as JK,
IF(c.per_firstname is NULL,c16,c.per_firstname) as NamaAyah,
IF(d.per_firstname is NULL,c17,d.per_firstname) as NamaIbu,
a.per_WorkPhone as Kelompok
from person_per a natural join person_custom
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)

where a.per_Gender=2 AND c15 = 1 AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
AND c27 is not NULL )
OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL))
order by a.per_FirstName";
}

$rsNamaPemohonNikahP = RunQuery($sSQL);


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

//Get Daftar GKJ Names for the drop-down
$sSQL = "SELECT * FROM DaftarGerejaGKJ a 
LEFT JOIN DaftarKlasisGKJ b ON a.KlasisID=b.KlasisID
ORDER BY GerejaID, NamaGereja";
$rsNamaGerejaBPN = RunQuery($sSQL);

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

<form method="post" action="NikahEditor.php?NikahID=<?php echo $iNikahID; ?>" name="NikahEditor">

<table cellpadding="2" align="center" valign="top" border="0" width=90%>

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="NikahSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"NikahSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iNikahID) > 0) 
{ echo "SelectListApp.php?mode=Nikah"; 
} else {echo "SelectListApp.php?mode=Nikah"; 
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
	
		<td class="LabelColumn" ></td>
		<td class="TextColumn"><?php echo gettext("Isikan data berikut, jika dilayani di Gereja Non GKJ:"); ?></td>
	
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
		<td class="LabelColumn"><?php echo gettext("Dilayani di (non GKJ):"); ?></td>
		<td class="TextColumn"><input type="text" name="NikahGerejaNonGKJ" id="NikahGerejaNonGKJ" value="<?php echo htmlentities(stripslashes($sNikahGerejaNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNikahGerejaNonGKJError ?></font></td>
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
		<td class="LabelColumn"><?php echo gettext("Alamat Gereja (non GKJ):"); ?></td>
		<td class="TextColumn"><input type="text" name="AlamatNikahGerejaNonGKJ" id="AlamatNikahGerejaNonGKJ" value="<?php echo htmlentities(stripslashes($sAlamatNikahGerejaNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamatNikahGerejaNonGKJError ?></font></td>


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
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Nama Pemohon (Laki2):"); ?></td>
				<td class="TextColumn">
					<select name="per_ID_L" size="5" style="width: 400px;">
						<option value="0" selected><?php echo gettext("Tidak Diketahui / Bukan Warga "); ?></option>
						<option value="0">-----------------------</option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPemohonNikahL))
						{
							extract($aRow);

							echo "<option value=\"" . $per_ID_L . "\"";
							if($JK == 1 ){$hub = "putra ";}else{$hub = "putri ";}
							if ($sper_ID_L == $per_ID_L) { echo " selected"; }
							echo ">" . $NamaLengkap . "&nbsp; - " .  $hub . "" . $NamaAyah . "/" . $NamaIbu . "&nbsp; - " . $Kelompok;
						}
						?>

					</select>
				</td>
				
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Nama Pemohon (Perempuan):"); ?></td>
				<td colspan="3" class="TextColumn">
					<select name="per_ID_P" size="5" style="width: 400px;">
						<option value="0" selected><?php echo gettext("Tidak Diketahui / Bukan Warga "); ?></option>
						<option value="0">-----------------------</option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPemohonNikahP))
						{
							extract($aRow);

							echo "<option value=\"" . $per_ID_P . "\"";
							if($JK == 1 ){$hub = "putra ";}else{$hub = "putri ";}
							if ($sper_ID_P == $per_ID_P) { echo " selected"; }
							echo ">" . $NamaLengkap . "&nbsp; - " .  $hub . "" . $NamaAyah . "/" . $NamaIbu . "&nbsp; - " . $Kelompok;
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

	<tr>
		<td colspan="8" align="center"><h3><?php echo gettext("Data Bina Pra Nikah / Katekisasi Pra Nikah"); ?></h3></td>
	</tr>
	<tr>		
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Mulai:"); ?></td>
		<td class="TextColumn"><input type="text" name="BPNMulai" value="<?php echo $sBPNMulai; ?>" maxlength="10" id="sel90" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel90', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sBPNMulaiError ?></font></td>
	
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Selesai:"); ?></td>
		<td class="TextColumn"><input type="text" name="BPNSelesai" value="<?php echo $sBPNSelesai; ?>" maxlength="10" id="sel91" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel91', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sBPNSelesaiError ?></font></td>
	
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Dilayani di :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="BPNTempat" >
						<option value="0" selected><?php echo gettext("Non GKJ"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaGerejaBPN))
						{
							extract($aRow);

							echo "<option value=\"" . $GerejaID . "\"";
							if ($sBPNTempat == $GerejaID) { echo " selected"; }
							echo ">" . $NamaGereja." - ".$NamaKlasis;
						}
						?>
					</select>
		</td>
		<td class="LabelColumn"><?php echo gettext("Dilayani di (non GKJ):"); ?></td>
		<td class="TextColumn"><input type="text" name="BPNTempatNonGKJ" id="BPNTempatNonGKJ" value="<?php echo htmlentities(stripslashes($sBPNTempatNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sBPNTempatNonGKJError ?></font></td>
	</tr>	
	
	

			<tr>
				<td colspan="8" align="center"><h3><?php echo gettext("Isikan Data dibawah jika bukan Warga jemaat"); ?></h3></td>
			</tr>	
				
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nama Pemohon Laki-laki:"); ?></td>
		<td class="TextColumn"><input type="text" name="NamaLengkapL" id="NamaLengkapL" value="<?php echo htmlentities(stripslashes($sNamaLengkapL),
		ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaLengkapLError ?></font></td>
		
		<td class="LabelColumn"><?php echo gettext("Nama Pemohon Perempuan:"); ?></td>
		<td class="TextColumn"><input type="text" name="NamaLengkapP" id="NamaLengkapP" value="<?php echo htmlentities(stripslashes($sNamaLengkapP),
		ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaLengkapPError ?></font></td>
	
		</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Warga dari Gereja :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="WargaGerejaL" >
						<option value="0" selected><?php echo gettext("Bukan Warga GKJ"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaGereja2L))
						{
							extract($aRow);

							echo "<option value=\"" . $GerejaIDL . "\"";
							if ($sWargaGerejaL == $GerejaIDL) { echo " selected"; }
							echo ">" . $NamaGereja." - ".$NamaKlasis;
						}
						?>
					</select>
		</td>
		
		<td class="LabelColumn"><?php echo gettext("Warga dari Gereja :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="WargaGerejaP" >
						<option value="0" selected><?php echo gettext("Bukan Warga GKJ"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaGereja2P))
						{
							extract($aRow);

							echo "<option value=\"" . $GerejaIDP . "\"";
							if ($sWargaGerejaP == $GerejaIDP) { echo " selected"; }
							echo ">" . $NamaGereja." - ".$NamaKlasis;
						}
						?>
					</select>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Warga Gereja non GKJ:"); ?></td>
		<td class="TextColumn"><input type="text" name="WargaGerejaNonGKJL" id="WargaGerejaNonGKJL" value="<?php echo htmlentities(stripslashes($sWargaGerejaNonGKJL),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sWargaGerejaNonGKJLError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Warga Gereja non GKJ:"); ?></td>
		<td class="TextColumn"><input type="text" name="WargaGerejaNonGKJP" id="WargaGerejaNonGKJP" value="<?php echo htmlentities(stripslashes($sWargaGerejaNonGKJP),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sWargaGerejaNonGKJPError ?></font></td>


	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Alamat Gereja non GKJ:"); ?></td>
		<td class="TextColumn"><input type="text" name="AlamatGerejaNonGKJL" id="AlamatGerejaNonGKJL" value="<?php echo htmlentities(stripslashes($sAlamatGerejaNonGKJL),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamatGerejaNonGKJLError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Alamat Gereja non GKJ:"); ?></td>
		<td class="TextColumn"><input type="text" name="AlamatGerejaNonGKJP" id="AlamatGerejaNonGKJP" value="<?php echo htmlentities(stripslashes($sAlamatGerejaNonGKJP),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamatGerejaNonGKJPError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Tempat/<br>Tanggal lahir"); ?></td>
		<td class="TextColumn"><input type="text" name="TempatLahirL" id="TempatLahirL" value="<?php echo htmlentities(stripslashes($sTempatLahirL),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatLahirLError ?></font>
		<input type="text" name="TanggalLahirL" value="<?php echo $sTanggalLahirL; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalLahirLError ?></font>
		</td>
		<td class="LabelColumn"><?php echo gettext("Tempat/<br>Tanggal lahir"); ?></td>
		<td class="TextColumn"><input type="text" name="TempatLahirP" id="TempatLahirP" value="<?php echo htmlentities(stripslashes($sTempatLahirP),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatLahirPError ?></font>
		<input type="text" name="TanggalLahirP" value="<?php echo $sTanggalLahirP; ?>" maxlength="10" id="sel11" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel11', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalLahirPError ?></font>
		</td>
	</tr>

	<tr>
		<td class="LabelColumn"><?php echo gettext("Nama Ayah<br>Nama Ibu:"); ?></td>
		<td class="TextColumn"><input type="text" name="NamaAyahL" id="NamaAyahL" value="<?php echo htmlentities(stripslashes($sNamaAyahL),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaAyahLError ?></font>
		<input type="text" name="NamaIbuL" id="NamaIbuL" value="<?php echo htmlentities(stripslashes($sNamaIbuL),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaIbuLError ?></font></td>
	
		<td class="LabelColumn"><?php echo gettext("Nama Ayah<br>Nama Ibu:"); ?></td>
		<td class="TextColumn"><input type="text" name="NamaAyahP" id="NamaAyahP" value="<?php echo htmlentities(stripslashes($sNamaAyahP),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaAyahPError ?></font>
		<input type="text" name="NamaIbuP" id="NamaIbuP" value="<?php echo htmlentities(stripslashes($sNamaIbuP),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaIbuPError ?></font></td>
	
	
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Tempat :<br>Tanggal Baptis:<br>Dilayani Oleh:"); ?></td>
		<td class="TextColumn"><input type="text" name="TempatBaptisL" id="TempatBaptisL" value="<?php echo htmlentities(stripslashes($sTempatBaptisL),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatBaptisLError ?></font>
		<input type="text" name="TanggalBaptisL" value="<?php echo $sTanggalBaptisL; ?>" maxlength="10" id="sel2" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel2', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalBaptisLError ?></font>
		<input type="text" name="PendetaBaptisL" id="PendetaBaptisL" value="<?php echo htmlentities(stripslashes($sPendetaBaptisL),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPendetaBaptisLError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Tempat :<br>Tanggal Baptis:<br>Dilayani Oleh:"); ?></td>
		<td class="TextColumn"><input type="text" name="TempatBaptisP" id="TempatBaptisP" value="<?php echo htmlentities(stripslashes($sTempatBaptisP),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatBaptisPError ?></font>
		<input type="text" name="TanggalBaptisP" value="<?php echo $sTanggalBaptisP; ?>" maxlength="10" id="sel12" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel12', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalBaptisPError ?></font>
		<input type="text" name="PendetaBaptisP" id="PendetaBaptisP" value="<?php echo htmlentities(stripslashes($sPendetaBaptisP),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPendetaBaptisPError ?></font></td>

	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Tempat :<br>Tanggal Sidhi:<br>Dilayani Oleh:"); ?></td>
		<td class="TextColumn"><input type="text" name="TempatSidhiL" id="TempatSidhiL" value="<?php echo htmlentities(stripslashes($sTempatSidhiL),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatSidhiLError ?></font>
		<input type="text" name="TanggalSidhiL" value="<?php echo $sTanggalSidhiL; ?>" maxlength="10" id="sel3" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel3', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalSidhiLError ?></font>
		<input type="text" name="PendetaSidhiL" id="PendetaSidhiL" value="<?php echo htmlentities(stripslashes($sPendetaSidhiL),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPendetaSidhiLError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Tempat :<br>Tanggal Sidhi:<br>Dilayani Oleh:"); ?></td>
		<td class="TextColumn"><input type="text" name="TempatSidhiP" id="TempatSidhiP" value="<?php echo htmlentities(stripslashes($sTempatSidhiP),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatSidhiPError ?></font>
		<input type="text" name="TanggalSidhiP" value="<?php echo $sTanggalSidhiP; ?>" maxlength="10" id="sel13" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel13', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalSidhiPError ?></font>
		<input type="text" name="PendetaSidhiP" id="PendetaSidhiP" value="<?php echo htmlentities(stripslashes($sPendetaSidhiP),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPendetaSidhiPError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Tempat :<br>Tanggal Baptis Dewasa:<br>Dilayani Oleh:"); ?></td>
		<td class="TextColumn"><input type="text" name="TempatBaptisDL" id="TempatBaptisDL" value="<?php echo htmlentities(stripslashes($sTempatBaptisDL),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatBaptisDLError ?></font>
		<input type="text" name="TanggalBaptisDL" value="<?php echo $sTanggalBaptisDL; ?>" maxlength="10" id="sel2" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel2', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalBaptisDLError ?></font>
		<input type="text" name="PendetaBaptisDL" id="PendetaBaptisDL" value="<?php echo htmlentities(stripslashes($sPendetaBaptisDL),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPendetaBaptisDLError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Tempat :<br>Tanggal Baptis Dewasa:<br>Dilayani Oleh:"); ?></td>
		<td class="TextColumn"><input type="text" name="TempatBaptisDP" id="TempatBaptisDP" value="<?php echo htmlentities(stripslashes($sTempatBaptisDP),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatBaptisDPError ?></font>
		<input type="text" name="TanggalBaptisDP" value="<?php echo $sTanggalBaptisDP; ?>" maxlength="10" id="sel12" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel12', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalBaptisDPError ?></font>
		<input type="text" name="PendetaBaptisDP" id="PendetaBaptisDP" value="<?php echo htmlentities(stripslashes($sPendetaBaptisDP),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPendetaBaptisDPError ?></font></td>

	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nomor Reff Surat:"); ?></td>
		<td class="TextColumn"><input type="text" name="NoSuratTitipanL" id="NoSuratTitipanL" value="<?php echo htmlentities(stripslashes($sNoSuratTitipanL),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNoSuratTitipanLError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Nomor Reff Surat:"); ?></td>
		<td class="TextColumn"><input type="text" name="NoSuratTitipanP" id="NoSuratTitipanP" value="<?php echo htmlentities(stripslashes($sNoSuratTitipanP),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNoSuratTitipanPError ?></font></td>

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
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iNikahID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
