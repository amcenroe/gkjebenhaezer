<?php
/*******************************************************************************
 *
 *  filename    : PelayanPendukungEditor.php
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
$sPageTitle = gettext("Jadwal Pelayan Pendukung Peribadahan");

//Get the PelayanPendukungID out of the querystring
$iPelayanPendukungID = FilterInput($_GET["PelayanPendukungID"],'int');


$iTGL = FilterInput($_GET["TGL"]);
$iPKL = FilterInput($_GET["PKL"]);
$iKodeTI = FilterInput($_GET["KodeTI"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PelayanPendukungID= manually)
if (strlen($iPelayanPendukungID) > 0)
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

	$sPelayanPendukungID = FilterInput($_POST["PelayanPendukungID"]);
	$sTanggal = FilterInput($_POST["Tanggal"]);
	$sWaktu = FilterInput($_POST["Waktu"]);
	$sKodeTI = FilterInput($_POST["KodeTI"]);
	$sTempat = FilterInput($_POST["Tempat"]);
	$sKodeOrganis = FilterInput($_POST["KodeOrganis"]);
	$sKodeSongLeader = FilterInput($_POST["KodeSongLeader"]);
	$sKodePengajarSMBalita1 = FilterInput($_POST["KodePengajarSMBalita1"]);
	$sKodePengajarSMBalita2 = FilterInput($_POST["KodePengajarSMBalita2"]);
	$sKodePemusikSMBalita = FilterInput($_POST["KodePemusikSMBalita"]);
	$sKodePengajarSMKecil1 = FilterInput($_POST["KodePengajarSMKecil1"]);
	$sKodePengajarSMKecil2 = FilterInput($_POST["KodePengajarSMKecil2"]);
	$sKodePemusikSMKecil = FilterInput($_POST["KodePemusikSMKecil"]);
	$sKodePengajarSMBesar1 = FilterInput($_POST["KodePengajarSMBesar1"]);
	$sKodePengajarSMBesar2 = FilterInput($_POST["KodePengajarSMBesar2"]);
	$sKodePemusikSMBesar = FilterInput($_POST["KodePemusikSMBesar"]);
	$sKodePengajarPraRemaja1 = FilterInput($_POST["KodePengajarPraRemaja1"]);
	$sKodePengajarPraRemaja2 = FilterInput($_POST["KodePengajarPraRemaja2"]);
	$sKodePemusikPraRemaja = FilterInput($_POST["KodePemusikPraRemaja"]);
	$sKodePengajarRemaja1 = FilterInput($_POST["KodePengajarRemaja1"]);
	$sKodePengajarRemaja2 = FilterInput($_POST["KodePengajarRemaja2"]);
	$sKodePemusikRemaja = FilterInput($_POST["KodePemusikRemaja"]);
	$sKodePengajarGabungan1 = FilterInput($_POST["KodePengajarGabungan1"]);
	$sKodePengajarGabungan2 = FilterInput($_POST["KodePengajarGabungan2"]);
	$sKodePemusikGabungan = FilterInput($_POST["KodePemusikGabungan"]);	
	$sKodeSaranaIbadah = FilterInput($_POST["KodeSaranaIbadah"]);
	$sKodeKolektan = FilterInput($_POST["KodeKolektan"]);
	$sKodeMultimedia1 = FilterInput($_POST["KodeMultimedia1"]);
	$sKodeMultimedia2 = FilterInput($_POST["KodeMultimedia2"]);
	
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
		if (strlen($iPelayanPendukungID) < 1)
		{
			 	
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO JadwalPelayanPendukung ( 		
			Tanggal,
			Waktu,
			KodeTI,
			Tempat,
			KodeOrganis,
			KodeSongLeader,
			KodePengajarSMBalita1,
			KodePengajarSMBalita2,
			KodePemusikSMBalita,
			KodePengajarSMKecil1,
			KodePengajarSMKecil2,
			KodePemusikSMKecil,
			KodePengajarSMBesar1,
			KodePengajarSMBesar2,
			KodePemusikSMBesar,
			KodePengajarPraRemaja1,
			KodePengajarPraRemaja2,
			KodePemusikPraRemaja,
			KodePengajarRemaja1,
			KodePengajarRemaja2,
			KodePemusikRemaja,
			KodePengajarGabungan1,
			KodePengajarGabungan2,
			KodePemusikGabungan,	
			KodeSaranaIbadah,
			KodeKolektan,
			KodeMultimedia1,
			KodeMultimedia2,
			DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sTanggal . "',	
			'" . $sWaktu . "',	
			'" . $sKodeTI . "',	
			'" . $sTempat . "',	
			'" . $sKodeOrganis . "',	
			'" . $sKodeSongLeader . "',	
			'" . $sKodePengajarSMBalita1 . "',	
			'" . $sKodePengajarSMBalita2 . "',	
			'" . $sKodePemusikSMBalita . "',	
			'" . $sKodePengajarSMKecil1 . "',	
			'" . $sKodePengajarSMKecil2 . "',	
			'" . $sKodePemusikSMKecil . "',	
			'" . $sKodePengajarSMBesar1 . "',	
			'" . $sKodePengajarSMBesar2 . "',	
			'" . $sKodePemusikSMBesar . "',	
			'" . $sKodePengajarPraRemaja1 . "',	
			'" . $sKodePengajarPraRemaja2 . "',	
			'" . $sKodePemusikPraRemaja . "',	
			'" . $sKodePengajarRemaja1 . "',	
			'" . $sKodePengajarRemaja2 . "',	
			'" . $sKodePemusikRemaja . "',	
			'" . $sKodePengajarGabungan1 . "',	
			'" . $sKodePengajarGabungan2 . "',	
			'" . $sKodePemusikGabungan . "',		
			'" . $sKodeSaranaIbadah . "',	
			'" . $sKodeKolektan . "',	
			'" . $sKodeMultimedia1 . "',	
			'" . $sKodeMultimedia2 . "',			

			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			
		
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Jadwal Pendukung Peribadahan";


		// Existing Baptis (update)
		} else {
			 
	//update the Baptis table
			
			$sSQL = "UPDATE JadwalPelayanPendukung SET 
			Tanggal = '" . $sTanggal . "',
			Waktu = '" . $sWaktu . "',
			KodeTI = '" . $sKodeTI . "',
			Tempat = '" . $sTempat . "',
			KodeOrganis = '" . $sKodeOrganis . "',
			KodeSongLeader = '" . $sKodeSongLeader . "',
			KodePengajarSMBalita1 = '" . $sKodePengajarSMBalita1 . "',
			KodePengajarSMBalita2 = '" . $sKodePengajarSMBalita2 . "',
			KodePemusikSMBalita = '" . $sKodePemusikSMBalita . "',
			KodePengajarSMKecil1 = '" . $sKodePengajarSMKecil1 . "',
			KodePengajarSMKecil2 = '" . $sKodePengajarSMKecil2 . "',
			KodePemusikSMKecil = '" . $sKodePemusikSMKecil . "',
			KodePengajarSMBesar1 = '" . $sKodePengajarSMBesar1 . "',
			KodePengajarSMBesar2 = '" . $sKodePengajarSMBesar2 . "',
			KodePemusikSMBesar = '" . $sKodePemusikSMBesar . "',
			KodePengajarPraRemaja1 = '" . $sKodePengajarPraRemaja1 . "',
			KodePengajarPraRemaja2 = '" . $sKodePengajarPraRemaja2 . "',
			KodePemusikPraRemaja = '" . $sKodePemusikPraRemaja . "',
			KodePengajarRemaja1 = '" . $sKodePengajarRemaja1 . "',
			KodePengajarRemaja2 = '" . $sKodePengajarRemaja2 . "',
			KodePemusikRemaja = '" . $sKodePemusikRemaja . "',
			KodePengajarGabungan1 = '" . $sKodePengajarGabungan1 . "',
			KodePengajarGabungan2 = '" . $sKodePengajarGabungan2 . "',
			KodePemusikGabungan = '" . $sKodePemusikGabungan . "',
			KodeSaranaIbadah = '" . $sKodeSaranaIbadah . "',
			KodeKolektan = '" . $sKodeKolektan . "',
			KodeMultimedia1 = '" . $sKodeMultimedia1 . "',
			KodeMultimedia2 = '" . $sKodeMultimedia2 . "',
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE PelayanPendukungID = " . $iPelayanPendukungID;

		//	echo $sSQL;
	
			$sSQL2 = "";
			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Jadwal Pelayan Pendukung Ibadah";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		if($sSQL2 ==""){ echo "";}else{	RunQuery($sSQL2);}
		
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPelayanPendukungID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PelayanPendukungEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPelayanPendukungID);
		}
		else if (isset($_POST["SuratSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectListApp3.php?mode=PelayanPendukung&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PelayanPendukungEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPelayanPendukungID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM JadwalPelayanPendukung  WHERE PelayanPendukungID = " . $iPelayanPendukungID;
		$rsBaptis = RunQuery($sSQL);
		extract(mysql_fetch_array($rsBaptis));
		
		$sPelayanPendukungID = $PelayanPendukungID;
		$sTanggal = $Tanggal;
		$sWaktu = $Waktu;
		$sKodeTI = $KodeTI;
		$sTempat = $Tempat;
		$sKodeOrganis = $KodeOrganis;
		$sKodeSongLeader = $KodeSongLeader;
		$sKodePengajarSMBalita1 = $KodePengajarSMBalita1;
		$sKodePengajarSMBalita2 = $KodePengajarSMBalita2;
		$sKodePemusikSMBalita = $KodePemusikSMBalita;
		$sKodePengajarSMKecil1 = $KodePengajarSMKecil1;
		$sKodePengajarSMKecil2 = $KodePengajarSMKecil2;
		$sKodePemusikSMKecil = $KodePemusikSMKecil;
		$sKodePengajarSMBesar1 = $KodePengajarSMBesar1;
		$sKodePengajarSMBesar2 = $KodePengajarSMBesar2;
		$sKodePemusikSMBesar = $KodePemusikSMBesar;
		$sKodePengajarPraRemaja1 = $KodePengajarPraRemaja1;
		$sKodePengajarPraRemaja2 = $KodePengajarPraRemaja2;
		$sKodePemusikPraRemaja = $KodePemusikPraRemaja;
		$sKodePengajarRemaja1 = $KodePengajarRemaja1;
		$sKodePengajarRemaja2 = $KodePengajarRemaja2;
		$sKodePemusikRemaja = $KodePemusikRemaja;
		$sKodePengajarGabungan1 = $KodePengajarGabungan1;
		$sKodePengajarGabungan2 = $KodePengajarGabungan2;
		$sKodePemusikGabungan = $KodePemusikGabungan;
		$sKodeSaranaIbadah = $KodeSaranaIbadah;
		$sKodeKolektan = $KodeKolektan;
		$sKodeMultimedia1 = $KodeMultimedia1	;
		$sKodeMultimedia2 = $KodeMultimedia2	;
		
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
		
		//Date from source
		if (strlen($iTGL) AND strlen($iPKL) AND strlen($iKodeTI) ){
		echo $iTGL,$iPKL,$iKodeTI;
		$sTanggal=$iTGL;
		$sWaktu=$iPKL;
		$sKodeTI=$iKodeTI;
	}

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
							
//Get Pengajar SM for the drop-down
$sSQL = "Select  a.per_ID as KodePengajarSMBalita1, a.per_FirstName as NamaPengajarSMBalita1 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 202
order by per_Firstname";
$rsNamaPengajarSMBalita1 = RunQuery($sSQL);

//Get Pengajar SM for the drop-down
$sSQL = "Select  a.per_ID as KodePengajarSMBalita2, a.per_FirstName as NamaPengajarSMBalita2 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 202
order by per_Firstname";
$rsNamaPengajarSMBalita2 = RunQuery($sSQL);

//Get Pemusik SM for the drop-down
$sSQL = "Select  a.per_ID as KodePemusikSMBalita, a.per_FirstName as NamaPemusikSMBalita ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 205
order by per_Firstname";
$rsNamaPemusikSMBalita = RunQuery($sSQL);

//Get Pengajar SM for the drop-down
$sSQL = "Select  a.per_ID as KodePengajarSMKecil1, a.per_FirstName as NamaPengajarSMKecil1 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 202
order by per_Firstname";
$rsNamaPengajarSMKecil1 = RunQuery($sSQL);

//Get Pengajar SM for the drop-down
$sSQL = "Select  a.per_ID as KodePengajarSMKecil2, a.per_FirstName as NamaPengajarSMKecil2 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 202
order by per_Firstname";
$rsNamaPengajarSMKecil2 = RunQuery($sSQL);

//Get Pemusik SM for the drop-down
$sSQL = "Select  a.per_ID as KodePemusikSMKecil, a.per_FirstName as NamaPemusikSMKecil ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 205
order by per_Firstname";
$rsNamaPemusikSMKecil = RunQuery($sSQL);

//Get Pengajar SM for the drop-down
$sSQL = "Select  a.per_ID as KodePengajarSMBesar1, a.per_FirstName as NamaPengajarSMBesar1 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 202
order by per_Firstname";
$rsNamaPengajarSMBesar1 = RunQuery($sSQL);

//Get Pengajar SM for the drop-down
$sSQL = "Select  a.per_ID as KodePengajarSMBesar2, a.per_FirstName as NamaPengajarSMBesar2 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 202
order by per_Firstname";
$rsNamaPengajarSMBesar2 = RunQuery($sSQL);

//Get Pemusik SM for the drop-down
$sSQL = "Select  a.per_ID as KodePemusikSMBesar, a.per_FirstName as NamaPemusikSMBesar ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 205
order by per_Firstname";
$rsNamaPemusikSMBesar = RunQuery($sSQL);

//Get Pengajar PraRemaja for the drop-down
$sSQL = "Select  a.per_ID as KodePengajarPraRemaja1, a.per_FirstName as NamaPengajarPraRemaja1 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 208
order by per_Firstname";
$rsNamaPengajarPraRemaja1 = RunQuery($sSQL);


//Get Pengajar PraRemaja for the drop-down
$sSQL = "Select  a.per_ID as KodePengajarPraRemaja2, a.per_FirstName as NamaPengajarPraRemaja2 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 208
order by per_Firstname";
$rsNamaPengajarPraRemaja2 = RunQuery($sSQL);

//Get Pemusik SM for the drop-down
$sSQL = "Select  a.per_ID as KodePemusikPraRemaja, a.per_FirstName as NamaPemusikPraRemaja ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 205
order by per_Firstname";
$rsNamaPemusikPraRemaja = RunQuery($sSQL);

//Get Pengajar Remaja for the drop-down
$sSQL = "Select  a.per_ID as KodePengajarRemaja1, a.per_FirstName as NamaPengajarRemaja1 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 208
order by per_Firstname";
$rsNamaPengajarRemaja1 = RunQuery($sSQL);


//Get Pengajar Remaja for the drop-down
$sSQL = "Select  a.per_ID as KodePengajarRemaja2, a.per_FirstName as NamaPengajarRemaja2 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 208
order by per_Firstname";
$rsNamaPengajarRemaja2 = RunQuery($sSQL);

//Get Pemusik SM for the drop-down
$sSQL = "Select  a.per_ID as KodePemusikRemaja, a.per_FirstName as NamaPemusikRemaja ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 205
order by per_Firstname";
$rsNamaPemusikRemaja = RunQuery($sSQL);

//Get Pengajar Gabungan for the drop-down
$sSQL = "Select  a.per_ID as KodePengajarGabungan1, a.per_FirstName as NamaPengajarGabungan1 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 202
order by per_Firstname";
$rsNamaPengajarGabungan1 = RunQuery($sSQL);

//Get Pengajar Gabungan for the drop-down
$sSQL = "Select  a.per_ID as KodePengajarGabungan2, a.per_FirstName as NamaPengajarGabungan2 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 202
order by per_Firstname";
$rsNamaPengajarGabungan2 = RunQuery($sSQL);

//Get Pemusik SM for the drop-down
$sSQL = "Select  a.per_ID as KodePemusikGabungan, a.per_FirstName as NamaPemusikGabungan ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 205
order by per_Firstname";
$rsNamaPemusikGabungan = RunQuery($sSQL);

							
//Get Pengajar Sarana Ibadah for the drop-down
$sSQL = "Select  grp_ID as KodeSaranaIbadah, grp_Name as NamaSaranaIbadah, grp_Description as Kelompok from group_grp
				order by grp_Name";
$rsNamaSaranaIbadah = RunQuery($sSQL);

//Get Kolektan Ibadah for the drop-down
$sSQL = "Select  grp_ID as KodeKolektan, grp_Name as NamaKolektan ,grp_Description as Kelompok from group_grp
				order by grp_Name";
$rsNamaKolektan = RunQuery($sSQL);

//Get Multimedia for the drop-down
$sSQL = "Select  a.per_ID as KodeMultimedia1, a.per_FirstName as NamaMultimedia1 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 200
order by per_Firstname";
$rsNamaMultimedia1 = RunQuery($sSQL);

//Get Multimedia2 for the drop-down
$sSQL = "Select  a.per_ID as KodeMultimedia2, a.per_FirstName as NamaMultimedia2 ,a.per_WorkPhone as Kelompok 
					from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
					where a.per_id = b.per_id AND
					a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND vol_id = 200
order by per_Firstname";
$rsNamaMultimedia2 = RunQuery($sSQL);

//$rsNamaPengajarSMBalita2 = RunQuery($sSQL);
//$rsNamaPengajarSMKecil1 = RunQuery($sSQL);
//$rsNamaPengajarSMKecil2 = RunQuery($sSQL);
//$rsNamaPengajarSMBesar1 = RunQuery($sSQL);
//$rsNamaPengajarSMBesar2 = RunQuery($sSQL);
// Get Nama Pejabat

require "Include/Header.php";


$iTGL = FilterInput($_GET["TGL"]);
$iPKL = FilterInput($_GET["PKL"]);
$iKodeTI = FilterInput($_GET["KodeTI"],'int');

//if (strlen($iTGL) AND strlen($iPKL) AND strlen($iKodeTI) ){
//echo $iTGL,$iPKL,$iKodeTI; }
 



?>

<form method="post" action="PelayanPendukungEditor.php?PelayanPendukungID=<?php echo $iPelayanPendukungID; ?>" name="SuratEditor">

<table cellpadding="3" align="center" valign="top" border="0">

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="SuratSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"SuratSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iPelayanPendukungID) > 0) 
{ echo "SelectListApp3.php?mode=PelayanPendukung&amp;$refresh"; 
} else {echo "SelectListApp3.php?mode=PelayanPendukung&amp;$refresh"; 
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
		<td class="TextColumn" colspan="0"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalError ?></font></td>
	
		<td class="LabelColumn"><?php echo gettext("Pukul :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspan="3">
			<select name="Waktu" >
				<option value="0" <?php if ($sWaktu == "") { echo " selected"; }?> ><?php echo gettext("Tidak Diketahui"); ?></option>
				<option value="06.00 WIB"  <?php if ($sWaktu == "06.00 WIB") { echo " selected"; }?> ><?php echo gettext("06.00 WIB CM1"); ?></option>
				<option value="06.30 WIB"  <?php if ($sWaktu == "06.30 WIB") { echo " selected"; }?> ><?php echo gettext("06.30 WIB"); ?></option>
				<option value="07.00 WIB"  <?php if ($sWaktu == "07.00 WIB") { echo " selected"; }?> ><?php echo gettext("07.00 WIB CKRG"); ?></option>
				<option value="07.30 WIB"  <?php if ($sWaktu == "07.30 WIB") { echo " selected"; }?> ><?php echo gettext("07.30 WIB KRWG"); ?></option>
				<option value="08.00 WIB"  <?php if ($sWaktu == "08.00 WIB") { echo " selected"; }?> ><?php echo gettext("08.00 WIB"); ?></option>
				<option value="08.30 WIB"  <?php if ($sWaktu == "08.30 WIB") { echo " selected"; }?> ><?php echo gettext("08.30 WIB"); ?></option>
				<option value="09.00 WIB"  <?php if ($sWaktu == "09.00 WIB") { echo " selected"; }?> ><?php echo gettext("09.00 WIB CM2"); ?></option>
				<option value="09.30 WIB"  <?php if ($sWaktu == "09.30 WIB") { echo " selected"; }?> ><?php echo gettext("09.30 WIB"); ?></option>
				<option value="10.00 WIB"  <?php if ($sWaktu == "10.00 WIB") { echo " selected"; }?> ><?php echo gettext("10.00 WIB"); ?></option>
				<option value="10.30 WIB"  <?php if ($sWaktu == "10.30 WIB") { echo " selected"; }?> ><?php echo gettext("10.30 WIB"); ?></option>
				<option value="11.00 WIB"  <?php if ($sWaktu == "11.00 WIB") { echo " selected"; }?> ><?php echo gettext("11.00 WIB"); ?></option>
				<option value="11.30 WIB"  <?php if ($sWaktu == "11.30 WIB") { echo " selected"; }?> ><?php echo gettext("11.30 WIB"); ?></option>
				<option value="12.00 WIB"  <?php if ($sWaktu == "12.00 WIB") { echo " selected"; }?> ><?php echo gettext("12.00 WIB"); ?></option>
				<option value="12.30 WIB"  <?php if ($sWaktu == "12.30 WIB") { echo " selected"; }?> ><?php echo gettext("12.30 WIB"); ?></option>
				<option value="13.00 WIB"  <?php if ($sWaktu == "13.00 WIB") { echo " selected"; }?> ><?php echo gettext("13.00 WIB"); ?></option>
				<option value="13.30 WIB"  <?php if ($sWaktu == "13.30 WIB") { echo " selected"; }?> ><?php echo gettext("13.30 WIB"); ?></option>
				<option value="14.00 WIB"  <?php if ($sWaktu == "14.00 WIB") { echo " selected"; }?> ><?php echo gettext("14.00 WIB"); ?></option>
				<option value="14.30 WIB"  <?php if ($sWaktu == "14.30 WIB") { echo " selected"; }?> ><?php echo gettext("14.30 WIB"); ?></option>
				<option value="15.00 WIB"  <?php if ($sWaktu == "15.00 WIB") { echo " selected"; }?> ><?php echo gettext("15.00 WIB"); ?></option>
				<option value="15.30 WIB"  <?php if ($sWaktu == "15.30 WIB") { echo " selected"; }?> ><?php echo gettext("15.30 WIB"); ?></option>
				<option value="16.00 WIB"  <?php if ($sWaktu == "16.00 WIB") { echo " selected"; }?> ><?php echo gettext("16.00 WIB"); ?></option>
				<option value="16.30 WIB"  <?php if ($sWaktu == "16.30 WIB") { echo " selected"; }?> ><?php echo gettext("16.30 WIB"); ?></option>
				<option value="17.00 WIB"  <?php if ($sWaktu == "17.00 WIB") { echo " selected"; }?> ><?php echo gettext("17.00 WIB TMBN"); ?></option>
				<option value="17.30 WIB"  <?php if ($sWaktu == "17.30 WIB") { echo " selected"; }?> ><?php echo gettext("17.30 WIB"); ?></option>
				<option value="18.00 WIB"  <?php if ($sWaktu == "18.00 WIB") { echo " selected"; }?> ><?php echo gettext("18.00 WIB"); ?></option>
				<option value="18.30 WIB"  <?php if ($sWaktu == "18.30 WIB") { echo " selected"; }?> ><?php echo gettext("18.30 WIB"); ?></option>
				<option value="19.00 WIB"  <?php if ($sWaktu == "19.00 WIB") { echo " selected"; }?> ><?php echo gettext("19.00 WIB"); ?></option>
				<option value="19.30 WIB"  <?php if ($sWaktu == "19.30 WIB") { echo " selected"; }?> ><?php echo gettext("19.30 WIB"); ?></option>
				<option value="20.00 WIB"  <?php if ($sWaktu == "20.00 WIB") { echo " selected"; }?> ><?php echo gettext("20.00 WIB"); ?></option>
				<option value="20.30 WIB"  <?php if ($sWaktu == "20.30 WIB") { echo " selected"; }?> ><?php echo gettext("20.30 WIB"); ?></option>
				<option value="21.00 WIB"  <?php if ($sWaktu == "21.00 WIB") { echo " selected"; }?> ><?php echo gettext("21.00 WIB"); ?></option>
				<option value="21.30 WIB"  <?php if ($sWaktu == "21.30 WIB") { echo " selected"; }?> ><?php echo gettext("21.30 WIB"); ?></option>

				
			</select>
		</td>

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
	<td class="LabelColumn" <?php addToolTip(""); ?> colspans=1 ><b><?php echo gettext("Pelayan Pendukung Peribadahan:"); ?></b></td>
	</tr>
	<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Organis:"); ?></td>
				<td  class="TextColumn" >
					<select name="KodeOrganis">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaOrganis))
						{
							extract($aRow);

							echo "<option value=\"" . $KodeOrganis . "\"";
							if ($sKodeOrganis == $KodeOrganis) { echo " selected"; }
							echo ">" . $NamaOrganis . " - " . $Kelompok ;
						}
						?>

					</select>
				</td>

				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Song Leader:"); ?></td>
				<td  class="TextColumn" >
					<select name="KodeSongLeader">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaSongLeader))
						{
							extract($aRow);

							echo "<option value=\"" . $KodeSongLeader . "\"";
							if ($sKodeSongLeader == $KodeSongLeader) { echo " selected"; }
							echo ">" . $NamaSongLeader . " - " . $Kelompok ;
						}
						?>

					</select>
				</td>
	</tr>
	<tr>
	<td class="LabelColumn" <?php addToolTip(""); ?> colspans=1 ><b><?php echo gettext("Sekolah Minggu:"); ?></b></td>
	</tr>
	<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Kelas Balita:"); ?></td>
				<td  class="TextColumn" >
					<select name="KodePengajarSMBalita1">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPengajarSMBalita1))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePengajarSMBalita1 . "\"";
							if ($sKodePengajarSMBalita1 == $KodePengajarSMBalita1) { echo " selected"; }
							echo ">" . $NamaPengajarSMBalita1 . " - " . $Kelompok ;
						}
						?>
					</select>
	
					<select name="KodePengajarSMBalita2">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPengajarSMBalita2))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePengajarSMBalita2 . "\"";
							if ($sKodePengajarSMBalita2 == $KodePengajarSMBalita2) { echo " selected"; }
							echo ">" . $NamaPengajarSMBalita2 . " - " . $Kelompok ;
						}
						?>

					</select>
				</td>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Pemusik:"); ?></td>
				<td>
						<select name="KodePemusikSMBalita">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPemusikSMBalita))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePemusikSMBalita . "\"";
							if ($sKodePemusikSMBalita == $KodePemusikSMBalita) { echo " selected"; }
							echo ">" . $NamaPemusikSMBalita . " - " . $Kelompok ;
						}
						?>
					</select>
				</td>
	</tr>
	<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Kelas Kecil:"); ?></td>
				<td  class="TextColumn" >
					<select name="KodePengajarSMKecil1">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPengajarSMKecil1))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePengajarSMKecil1 . "\"";
							if ($sKodePengajarSMKecil1 == $KodePengajarSMKecil1) { echo " selected"; }
							echo ">" . $NamaPengajarSMKecil1 . " - " . $Kelompok ;
						}
						?>
					</select>
	
					<select name="KodePengajarSMKecil2">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPengajarSMKecil2))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePengajarSMKecil2 . "\"";
							if ($sKodePengajarSMKecil2 == $KodePengajarSMKecil2) { echo " selected"; }
							echo ">" . $NamaPengajarSMKecil2 . " - " . $Kelompok ;
						}
						?>

					</select>
				</td>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Pemusik:"); ?></td>
				<td>
						<select name="KodePemusikSMKecil">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPemusikSMKecil))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePemusikSMKecil . "\"";
							if ($sKodePemusikSMKecil == $KodePemusikSMKecil) { echo " selected"; }
							echo ">" . $NamaPemusikSMKecil . " - " . $Kelompok ;
						}
						?>
					</select>
				</td>
	</tr>
	<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Kelas Besar:"); ?></td>
				<td  class="TextColumn" >
					<select name="KodePengajarSMBesar1">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPengajarSMBesar1))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePengajarSMBesar1 . "\"";
							if ($sKodePengajarSMBesar1 == $KodePengajarSMBesar1) { echo " selected"; }
							echo ">" . $NamaPengajarSMBesar1 . " - " . $Kelompok ;
						}
						?>
					</select>
	
					<select name="KodePengajarSMBesar2">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPengajarSMBesar2))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePengajarSMBesar2 . "\"";
							if ($sKodePengajarSMBesar2 == $KodePengajarSMBesar2) { echo " selected"; }
							echo ">" . $NamaPengajarSMBesar2 . " - " . $Kelompok ;
						}
						?>

					</select>
				</td>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Pemusik:"); ?></td>
				<td>
						<select name="KodePemusikSMBesar">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPemusikSMBesar))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePemusikSMBesar . "\"";
							if ($sKodePemusikSMBesar == $KodePemusikSMBesar) { echo " selected"; }
							echo ">" . $NamaPemusikSMBesar . " - " . $Kelompok ;
						}
						?>
					</select>
				</td>
	</tr>	
	<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Kelas PraRemaja:"); ?></td>
				<td  class="TextColumn" >
					<select name="KodePengajarPraRemaja1">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPengajarPraRemaja1))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePengajarPraRemaja1 . "\"";
							if ($sKodePengajarPraRemaja1 == $KodePengajarPraRemaja1) { echo " selected"; }
							echo ">" . $NamaPengajarPraRemaja1 . " - " . $Kelompok ;
						}
						?>
					</select>
	

				</td>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Pemusik:"); ?></td>
				<td>
						<select name="KodePemusikPraRemaja">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPemusikPraRemaja))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePemusikPraRemaja . "\"";
							if ($sKodePemusikPraRemaja == $KodePemusikPraRemaja) { echo " selected"; }
							echo ">" . $NamaPemusikPraRemaja . " - " . $Kelompok ;
						}
						?>
					</select>
				</td>
	</tr>
	<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Kelas Remaja:"); ?></td>
				<td  class="TextColumn" >
					<select name="KodePengajarRemaja1">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPengajarRemaja1))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePengajarRemaja1 . "\"";
							if ($sKodePengajarRemaja1 == $KodePengajarRemaja1) { echo " selected"; }
							echo ">" . $NamaPengajarRemaja1 . " - " . $Kelompok ;
						}
						?>
					</select>
	

				</td>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Pemusik:"); ?></td>
				<td>
						<select name="KodePemusikRemaja">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPemusikRemaja))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePemusikRemaja . "\"";
							if ($sKodePemusikRemaja == $KodePemusikRemaja) { echo " selected"; }
							echo ">" . $NamaPemusikRemaja . " - " . $Kelompok ;
						}
						?>
					</select>
				</td>
	</tr>
	<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Kelas Gabungan:"); ?></td>
				<td  class="TextColumn" >
					<select name="KodePengajarGabungan1">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPengajarGabungan1))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePengajarGabungan1 . "\"";
							if ($sKodePengajarGabungan1 == $KodePengajarGabungan1) { echo " selected"; }
							echo ">" . $NamaPengajarGabungan1 . " - " . $Kelompok ;
						}
						?>
					</select>
	
					<select name="KodePengajarGabungan2">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPengajarGabungan2))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePengajarGabungan2 . "\"";
							if ($sKodePengajarGabungan2 == $KodePengajarGabungan2) { echo " selected"; }
							echo ">" . $NamaPengajarGabungan2 . " - " . $Kelompok ;
						}
						?>

					</select>
				</td>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Pemusik:"); ?></td>
				<td>
						<select name="KodePemusikGabungan">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPemusikGabungan))
						{
							extract($aRow);
							echo "<option value=\"" . $KodePemusikGabungan . "\"";
							if ($sKodePemusikGabungan == $KodePemusikGabungan) { echo " selected"; }
							echo ">" . $NamaPemusikGabungan . " - " . $Kelompok ;
						}
						?>
					</select>
				</td>
	</tr>
	<tr>
	<td class="LabelColumn" <?php addToolTip(""); ?> colspans=1 ><b><?php echo gettext("Pendukung Peribadahan lainnya:"); ?></b></td>
	</tr>

	<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Sarana Ibadah dan Usher:"); ?></td>
				<td  class="TextColumn" >
					<select name="KodeSaranaIbadah">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaSaranaIbadah))
						{
							extract($aRow);
							echo "<option value=\"" . $KodeSaranaIbadah . "\"";
							if ($sKodeSaranaIbadah == $KodeSaranaIbadah) { echo " selected"; }
							echo ">" . $NamaSaranaIbadah . " - " . $Kelompok ;
						}
						?>
					</select>
				</td>
	</tr>
	<tr>	
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Kolektan:"); ?></td>
				<td>
						<select name="KodeKolektan">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaKolektan))
						{
							extract($aRow);
							echo "<option value=\"" . $KodeKolektan . "\"";
							if ($sKodeKolektan == $KodeKolektan) { echo " selected"; }
							echo ">" . $NamaKolektan . " - " . $Kelompok ;
						}
						?>
					</select>
				</td>
	</tr>
		<tr>	
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Multimedia:"); ?></td>
				<td>
					<select name="KodeMultimedia1">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaMultimedia1))
						{
							extract($aRow);
							echo "<option value=\"" . $KodeMultimedia1 . "\"";
							if ($sKodeMultimedia1 == $KodeMultimedia1) { echo " selected"; }
							echo ">" . $NamaMultimedia1 . " - " . $Kelompok ;
						}
						?>
					</select>
					
						<select name="KodeMultimedia2">
						<option value="0" selected><?php echo gettext("Tidak Ada Pelayan"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaMultimedia2))
						{
							extract($aRow);
							echo "<option value=\"" . $KodeMultimedia2 . "\"";
							if ($sKodeMultimedia2 == $KodeMultimedia2) { echo " selected"; }
							echo ">" . $NamaMultimedia2 . " - " . $Kelompok ;
						}
						?>
					</select>
				</td>
	</tr>
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
		$logvar2 = "Pelayanan Firman Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPelayanPendukungID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
