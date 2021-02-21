<?php
/*******************************************************************************
 *
 *  filename    : PersembahanEditor.php
 *
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
$sPageTitle = gettext("Editor - Persembahan GKJ Bekti");

//Get the Persembahan_ID out of the querystring
$iPersembahan_ID = FilterInput($_GET["Persembahan_ID"],'int');
$ReffTanggal = $_GET["ReffTanggal"] ;
$ReffKodeTI = $_GET["ReffKodeTI"] ;
$ReffPukul = $_GET["ReffPukul"] ;
$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?Persembahan_ID= manually)
if (strlen($iPersembahan_ID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsPersembahan = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPersembahan));

	if (mysql_num_rows($rsPersembahan) == 0)
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

if (isset($_POST["PersembahanSubmit"]) || isset($_POST["PersembahanSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	

			$sPersembahan_ID = FilterInput($_POST["Persembahan_ID"]); 
			$sTanggal = FilterInput($_POST["Tanggal"]); 
			$sPukul = FilterInput($_POST["Pukul"]); 
			$sKodeTI = FilterInput($_POST["KodeTI"]); 
			$sLiturgi = FilterInput($_POST["Liturgi"]); 
			$sPengkotbah = FilterInput($_POST["Pengkotbah"]); 
			$sBacaan1 = FilterInput($_POST["Bacaan1"]); 
			$sBacaan2 = FilterInput($_POST["Bacaan2"]); 
			$sBacaan3 = FilterInput($_POST["Bacaan3"]); 
			$sBacaan4 = FilterInput($_POST["Bacaan4"]); 
			$sNas = FilterInput($_POST["Nas"]); 
			$sNyanyian1 = FilterInput($_POST["Nyanyian1"]); 
			$sNyanyian2 = FilterInput($_POST["Nyanyian2"]); 
			$sNyanyian3 = FilterInput($_POST["Nyanyian3"]); 
			$sNyanyian4 = FilterInput($_POST["Nyanyian4"]); 
			$sNyanyian5 = FilterInput($_POST["Nyanyian5"]); 
			$sNyanyian6 = FilterInput($_POST["Nyanyian6"]); 
			$sNyanyian7 = FilterInput($_POST["Nyanyian7"]); 
			$sNyanyian8 = FilterInput($_POST["Nyanyian8"]); 
			$sNyanyian9 = FilterInput($_POST["Nyanyian9"]); 
			$sNyanyian10 = FilterInput($_POST["Nyanyian10"]); 
			$sNyanyian11 = FilterInput($_POST["Nyanyian11"]); 
			$sNyanyian12 = FilterInput($_POST["Nyanyian12"]); 
			$sNyanyian13 = FilterInput($_POST["Nyanyian13"]); 
			$sNyanyian14 = FilterInput($_POST["Nyanyian14"]); 
			$sNyanyian15 = FilterInput($_POST["Nyanyian15"]); 
			$sNyanyian16 = FilterInput($_POST["Nyanyian16"]); 
			$sBaptisDewasa = FilterInput($_POST["BaptisDewasa"]); 
			$sBaptisAnak = FilterInput($_POST["BaptisAnak"]); 
			$sSidi = FilterInput($_POST["Sidi"]); 
			$sPengakuanDosa = FilterInput($_POST["PengakuanDosa"]); 
			$sPenerimaanWarga = FilterInput($_POST["PenerimaanWarga"]); 
			$sPemberkatan1 = FilterInput($_POST["Pemberkatan1"]); 
			$sPemberkatan2 = FilterInput($_POST["Pemberkatan2"]); 
			$sKebDewasa = FilterInput($_POST["KebDewasa"]); 
			$sKebAnak = FilterInput($_POST["KebAnak"]); 
			$sKebAnakJTMY = FilterInput($_POST["KebAnakJTMY"]); 
			$sKebRemaja = FilterInput($_POST["KebRemaja"]); 
			$sKebPraRemaja = FilterInput($_POST["KebPraRemaja"]); 
			$sKebPemuda = FilterInput($_POST["KebPemuda"]); 
			$sSyukur = FilterInput($_POST["Syukur"]); 
			$sSyukurAmplop = FilterInput($_POST["SyukurAmplop"]); 
			$sBulanan = FilterInput($_POST["Bulanan"]); 
			$sBulananAmplop = FilterInput($_POST["BulananAmplop"]); 
			$sKhusus = FilterInput($_POST["Khusus"]); 
			$sKhususAmplop = FilterInput($_POST["KhususAmplop"]);
			$sSyukurBaptis = FilterInput($_POST["SyukurBaptis"]); 
			$sSyukurBaptisAmplop = FilterInput($_POST["SyukurBaptisAmplop"]); 
			$sKhususPerjamuan = FilterInput($_POST["KhususPerjamuan"]); 
			$sKhususPerjamuanAmplop = FilterInput($_POST["KhususPerjamuanAmplop"]);			
			$sMarapas = FilterInput($_POST["Marapas"]); 
			$sMarapasAmplop = FilterInput($_POST["MarapasAmplop"]); 
			$sMarapen = FilterInput($_POST["Marapen"]); 
			$sMarapenAmplop = FilterInput($_POST["MarapenAmplop"]); 
			$sMaranatal = FilterInput($_POST["Maranatal"]); 
			$sMaranatalAmplop = FilterInput($_POST["MaranatalAmplop"]); 
			$sUnduh = FilterInput($_POST["Unduh"]); 
			$sUnduhAmplop = FilterInput($_POST["UnduhAmplop"]); 
			$sPink = FilterInput($_POST["Pink"]); 
			$sPinkAmplop = FilterInput($_POST["PinkAmplop"]); 
			$sLainLain = FilterInput($_POST["LainLain"]); 
			$sLainLainAmplop = FilterInput($_POST["LainLainAmplop"]); 
			$sPria = FilterInput($_POST["Pria"]); 
			$sWanita = FilterInput($_POST["Wanita"]); 
			$sMajelis1 = FilterInput($_POST["Majelis1"]); 
			$sMajelis2 = FilterInput($_POST["Majelis2"]); 
			$sMajelis3 = FilterInput($_POST["Majelis3"]); 
			$sMajelis4 = FilterInput($_POST["Majelis4"]); 
			$sMajelis5 = FilterInput($_POST["Majelis5"]); 
			$sMajelis6 = FilterInput($_POST["Majelis6"]); 
			$sMajelis7 = FilterInput($_POST["Majelis7"]); 
			$sMajelis8 = FilterInput($_POST["Majelis8"]); 
			$sMajelis9 = FilterInput($_POST["Majelis9"]); 
			$sMajelis10 = FilterInput($_POST["Majelis10"]); 
			$sMajelis11 = FilterInput($_POST["Majelis11"]); 
			$sMajelis12 = FilterInput($_POST["Majelis12"]); 
			$sMajelis13 = FilterInput($_POST["Majelis13"]); 
			$sMajelis14 = FilterInput($_POST["Majelis14"]); 
			$sKetPersembahan = FilterInput($_POST["KetPersembahan"]); 
			$sDateEntered = FilterInput($_POST["DateEntered"]); 
			$sEnteredBy = FilterInput($_POST["EnteredBy"]); 
			$sDateLastEdited = FilterInput($_POST["DateLastEdited"]); 
			$sEditedBy = FilterInput($_POST["EditedBy"]); 	
	
	//Initialize the error flag
	$bErrorFlag = false;

	// Validate Mail Date if one was entered
	if (strlen($dTanggal) > 0)
	{
		$dateString = parseAndValidateDate($dTanggal, $locale = "US", $pasfut = "past");
		if ( $dateString === FALSE ) {
			$sTanggalError = "<span style=\"color: red; \">"
								. gettext("Not a valid Date") . "</span>";
			$bErrorFlag = true;
		} else {
			$dTanggal = $dateString;
		}
	}

	//If no errors, then let's update...
		// New Data (add)
		if (strlen($iPersembahan_ID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

				
				
				
				
				
				
			$sSQL = "INSERT INTO Persembahangkjbekti ( 
				Persembahan_ID,
				Tanggal,
				Pukul,
				KodeTI,
				Liturgi,
				Pengkotbah,
				Bacaan1,
				Bacaan2,
				Bacaan3,
				Bacaan4,
				Nas,
				Nyanyian1,
				Nyanyian2,
				Nyanyian3,
				Nyanyian4,
				Nyanyian5,
				Nyanyian6,
				Nyanyian7,
				Nyanyian8,
				Nyanyian9,
				Nyanyian10,
				Nyanyian11,
				Nyanyian12,
				Nyanyian13,
				Nyanyian14,
				Nyanyian15,
				Nyanyian16,
				BaptisDewasa,
				BaptisAnak,
				Sidi,
				PengakuanDosa,
				PenerimaanWarga,
				Pemberkatan1,
				Pemberkatan2,
				KebDewasa,
				KebAnak,
				KebAnakJTMY,
				KebRemaja,
				KebPraRemaja,
				KebPemuda,
				Syukur,
				SyukurAmplop,
				Bulanan,
				BulananAmplop,
				Khusus,
				KhususAmplop,
				SyukurBaptis,
				SyukurBaptisAmplop,
				KhususPerjamuan,
				KhususPerjamuanAmplop,
				Marapas,
				MarapasAmplop,
				Marapen,
				MarapenAmplop,
				Maranatal,
				MaranatalAmplop,
				Unduh,
				UnduhAmplop,
				Pink,
				PinkAmplop,
				LainLain,
				LainLainAmplop,
				Pria,
				Wanita,
				Majelis1,
				Majelis2,
				Majelis3,
				Majelis4,
				Majelis5,
				Majelis6,
				Majelis7,
				Majelis8,
				Majelis9,
				Majelis10,
				Majelis11,
				Majelis12,
				Majelis13,
				Majelis14,
				KetPersembahan,
				DateEntered,				
				EnteredBy
			)
			VALUES ( 
			'" . $sPersembahan_ID . "',
			'" . $sTanggal . "',
			'" . $sPukul . "',
			'" . $sKodeTI . "',
			'" . $sLiturgi . "',
			'" . $sPengkotbah . "',
			'" . $sBacaan1 . "',
			'" . $sBacaan2 . "',
			'" . $sBacaan3 . "',
			'" . $sBacaan4 . "',
			'" . $sNas . "',
			'" . $sNyanyian1 . "',
			'" . $sNyanyian2 . "',
			'" . $sNyanyian3 . "',
			'" . $sNyanyian4 . "',
			'" . $sNyanyian5 . "',
			'" . $sNyanyian6 . "',
			'" . $sNyanyian7 . "',
			'" . $sNyanyian8 . "',
			'" . $sNyanyian9 . "',
			'" . $sNyanyian10 . "',
			'" . $sNyanyian11 . "',
			'" . $sNyanyian12 . "',
			'" . $sNyanyian13 . "',
			'" . $sNyanyian14 . "',
			'" . $sNyanyian15 . "',
			'" . $sNyanyian16 . "',
			'" . $sBaptisDewasa . "',
			'" . $sBaptisAnak . "',
			'" . $sSidi . "',
			'" . $sPengakuanDosa . "',
			'" . $sPenerimaanWarga . "',
			'" . $sPemberkatan1 . "',
			'" . $sPemberkatan2 . "',
			'" . $sKebDewasa . "',
			'" . $sKebAnak . "',
			'" . $sKebAnakJTMY . "',
			'" . $sKebRemaja . "',
			'" . $sKebPraRemaja . "',
			'" . $sKebPemuda . "',
			'" . $sSyukur . "',
			'" . $sSyukurAmplop . "',
			'" . $sBulanan . "',
			'" . $sBulananAmplop . "',
			'" . $sKhusus . "',
			'" . $sKhususAmplop . "',
			'" . $sSyukurBaptis . "',
			'" . $sSyukurBaptisAmplop . "',
			'" . $sKhususPerjamuan . "',
			'" . $sKhususPerjamuanAmplop . "',
			'" . $sMarapas . "',
			'" . $sMarapasAmplop . "',
			'" . $sMarapen . "',
			'" . $sMarapenAmplop . "',
			'" . $sMaranatal . "',
			'" . $sMaranatalAmplop . "',
			'" . $sUnduh . "',
			'" . $sUnduhAmplop . "',
			'" . $sPink . "',
			'" . $sPinkAmplop . "',
			'" . $sLainLain . "',
			'" . $sLainLainAmplop . "',
			'" . $sPria . "',
			'" . $sWanita . "',
			'" . $sMajelis1 . "',
			'" . $sMajelis2 . "',
			'" . $sMajelis3 . "',
			'" . $sMajelis4 . "',
			'" . $sMajelis5 . "',
			'" . $sMajelis6 . "',
			'" . $sMajelis7 . "',
			'" . $sMajelis8 . "',
			'" . $sMajelis9 . "',
			'" . $sMajelis10 . "',
			'" . $sMajelis11 . "',
			'" . $sMajelis12 . "',
			'" . $sMajelis13 . "',
			'" . $sMajelis14 . "',
			'" . $sKetPersembahan . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New Persembahan Data";

			
			

		// Existing Persembahan (update)
		} else {
	
			$sSQL = "UPDATE Persembahangkjbekti SET 
					Tanggal = '" . $sTanggal . "',
					Pukul = '" . $sPukul . "',
					KodeTI = '" . $sKodeTI . "',
					Liturgi = '" . $sLiturgi . "',
					Pengkotbah = '" . $sPengkotbah . "',
					Bacaan1 = '" . $sBacaan1 . "',
					Bacaan2 = '" . $sBacaan2 . "',
					Bacaan3 = '" . $sBacaan3 . "',
					Bacaan4 = '" . $sBacaan4 . "',
					Nas = '" . $sNas . "',
					Nyanyian1 = '" . $sNyanyian1 . "',
					Nyanyian2 = '" . $sNyanyian2 . "',
					Nyanyian3 = '" . $sNyanyian3 . "',
					Nyanyian4 = '" . $sNyanyian4 . "',
					Nyanyian5 = '" . $sNyanyian5 . "',
					Nyanyian6 = '" . $sNyanyian6 . "',
					Nyanyian7 = '" . $sNyanyian7 . "',
					Nyanyian8 = '" . $sNyanyian8 . "',
					Nyanyian9 = '" . $sNyanyian9 . "',
					Nyanyian10 = '" . $sNyanyian10 . "',
					Nyanyian11 = '" . $sNyanyian11 . "',
					Nyanyian12 = '" . $sNyanyian12 . "',
					Nyanyian13 = '" . $sNyanyian13 . "',
					Nyanyian14 = '" . $sNyanyian14 . "',
					Nyanyian15 = '" . $sNyanyian15 . "',
					Nyanyian16 = '" . $sNyanyian16 . "',
					BaptisDewasa = '" . $sBaptisDewasa . "',
					BaptisAnak = '" . $sBaptisAnak . "',
					Sidi = '" . $sSidi . "',
					PengakuanDosa = '" . $sPengakuanDosa . "',
					PenerimaanWarga = '" . $sPenerimaanWarga . "',
					Pemberkatan1 = '" . $sPemberkatan1 . "',
					Pemberkatan2 = '" . $sPemberkatan2 . "',
					KebDewasa = '" . $sKebDewasa . "',
					KebAnak = '" . $sKebAnak . "',
					KebAnakJTMY = '" . $sKebAnakJTMY . "',
					KebRemaja = '" . $sKebRemaja . "',
					KebPraRemaja = '" . $sKebPraRemaja . "',
					KebPemuda = '" . $sKebPemuda . "',
					Syukur = '" . $sSyukur . "',
					SyukurAmplop = '" . $sSyukurAmplop . "',
					Bulanan = '" . $sBulanan . "',
					BulananAmplop = '" . $sBulananAmplop . "',
					Khusus = '" . $sKhusus . "',
					KhususAmplop = '" . $sKhususAmplop . "',
					SyukurBaptis = '" . $sSyukurBaptis . "',
					SyukurBaptisAmplop = '" . $sSyukurBaptisAmplop . "',
					KhususPerjamuan = '" . $sKhususPerjamuan . "',
					KhususPerjamuanAmplop = '" . $sKhususPerjamuanAmplop . "',
					Marapas = '" . $sMarapas . "',
					MarapasAmplop = '" . $sMarapasAmplop . "',
					Marapen = '" . $sMarapen . "',
					MarapenAmplop = '" . $sMarapenAmplop . "',
					Maranatal = '" . $sMaranatal . "',
					MaranatalAmplop = '" . $sMaranatalAmplop . "',
					Unduh = '" . $sUnduh . "',
					UnduhAmplop = '" . $sUnduhAmplop . "',
					Pink = '" . $sPink . "',
					PinkAmplop = '" . $sPinkAmplop . "',
					LainLain = '" . $sLainLain . "',
					LainLainAmplop = '" . $sLainLainAmplop . "',
					Pria = '" . $sPria . "',
					Wanita = '" . $sWanita . "',
					Majelis1 = '" . $sMajelis1 . "',
					Majelis2 = '" . $sMajelis2 . "',
					Majelis3 = '" . $sMajelis3 . "',
					Majelis4 = '" . $sMajelis4 . "',
					Majelis5 = '" . $sMajelis5 . "',
					Majelis6 = '" . $sMajelis6 . "',
					Majelis7 = '" . $sMajelis7 . "',
					Majelis8 = '" . $sMajelis8 . "',
					Majelis9 = '" . $sMajelis9 . "',
					Majelis10 = '" . $sMajelis10 . "',
					Majelis11 = '" . $sMajelis11 . "',
					Majelis12 = '" . $sMajelis12 . "',
					Majelis13 = '" . $sMajelis13 . "',
					Majelis14 = '" . $sMajelis14 . "',
					KetPersembahan = '" . $sKetPersembahan . "',
					DateLastEdited = '" . date("YmdHis") . "', 
					EditedBy = '" . $_SESSION['iUserID'] ;
					
			$sSQL .= "' WHERE Persembahan_ID = " . $iPersembahan_ID;

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Persembahan Data";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PersembahanEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPersembahan_ID);
		}
		else if (isset($_POST["PersembahanSubmit"]))
		{
			//Send to the view of this Persembahan
			Redirect("SelectList.php?mode=Persembahan&amp;GID=$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PersembahanEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPersembahan_ID) > 0)
	{
		//Editing....
		//Get all the data on this record

		
		// Get Data persembahan
		$sSQL = "SELECT * FROM Persembahangkjbekti  WHERE Persembahan_ID = " . $iPersembahan_ID;
		$rsPersembahan = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPersembahan));
		
			$sPersembahan_ID = $Persembahan_ID;
			$sTanggal = $Tanggal;
			$sPukul = $Pukul;
			$sKodeTI = $KodeTI;
			$sLiturgi = $Liturgi;
//			$sPengkotbah = $Pengkotbah;
//			$sBacaan1 = $Bacaan1;
//			$sBacaan2 = $Bacaan2;
//			$sBacaan3 = $Bacaan3;
//			$sBacaan4 = $Bacaan4;
//			$sNas = $Nas;
//			$sNyanyian1 = $Nyanyian1;
//			$sNyanyian2 = $Nyanyian2;
//			$sNyanyian3 = $Nyanyian3;
//			$sNyanyian4 = $Nyanyian4;
//			$sNyanyian5 = $Nyanyian5;
//			$sNyanyian6 = $Nyanyian6;
//			$sNyanyian7 = $Nyanyian7;
//			$sNyanyian8 = $Nyanyian8;
//			$sNyanyian9 = $Nyanyian9;
//			$sNyanyian10 = $Nyanyian10;
//			$sNyanyian11 = $Nyanyian11;
//			$sNyanyian12 = $Nyanyian12;
//			$sNyanyian13 = $Nyanyian13;
//			$sNyanyian14 = $Nyanyian14;
			$sBaptisDewasa = $BaptisDewasa;
			$sBaptisAnak = $BaptisAnak;
			$sSidi = $Sidi;
			$sPengakuanDosa = $PengakuanDosa;
			$sPenerimaanWarga = $PenerimaanWarga;
			$sPemberkatan1 = $Pemberkatan1;
			$sPemberkatan2 = $Pemberkatan2;
			$sKebDewasa = $KebDewasa;
			$sKebAnak = $KebAnak;
			$sKebAnakJTMY = $KebAnakJTMY;
			$sKebRemaja = $KebRemaja;
			$sKebPraRemaja = $KebPraRemaja;
			$sKebPemuda = $KebPemuda;
			$sSyukur = $Syukur;
			$sSyukurAmplop = $SyukurAmplop;
			$sBulanan = $Bulanan;
			$sBulananAmplop = $BulananAmplop;
			$sKhusus = $Khusus;
			$sKhususAmplop = $KhususAmplop;
			$sSyukurBaptis = $SyukurBaptis;
			$sSyukurBaptisAmplop = $SyukurBaptisAmplop;
			$sKhususPerjamuan = $KhususPerjamuan;
			$sKhususPerjamuanAmplop = $KhususPerjamuanAmplop;
			$sMarapas = $Marapas;
			$sMarapasAmplop = $MarapasAmplop;
			$sMarapen = $Marapen;
			$sMarapenAmplop = $MarapenAmplop;
			$sMaranatal = $Maranatal;
			$sMaranatalAmplop = $MaranatalAmplop;
			$sUnduh = $Unduh;
			$sUnduhAmplop = $UnduhAmplop;
			$sPink = $Pink;
			$sPinkAmplop = $PinkAmplop;
			$sLainLain = $LainLain;
			$sLainLainAmplop = $LainLainAmplop;
			$sPria = $Pria;
			$sWanita = $Wanita;
			$sMajelis1 = $Majelis1;
			$sMajelis2 = $Majelis2;
			$sMajelis3 = $Majelis3;
			$sMajelis4 = $Majelis4;
			$sMajelis5 = $Majelis5;
			$sMajelis6 = $Majelis6;
			$sMajelis7 = $Majelis7;
			$sMajelis8 = $Majelis8;
			$sMajelis9 = $Majelis9;
			$sMajelis10 = $Majelis10;
			$sMajelis11 = $Majelis11;
			$sMajelis12 = $Majelis12;
			$sMajelis13 = $Majelis13;
			$sMajelis14 = $Majelis14;
			$sKetPersembahan = $KetPersembahan;
			$sDateEntered = $DateEntered;
			$sEnteredBy = $EnteredBy;
			$sDateLastEdited = $DateLastEdited;
			$sEditedBy = $EditedBy;
			
			
					//Get data Liturgi
					
		$sSQL = "SELECT count(*) as DualBahasa FROM LiturgiGKJBekti  WHERE Tanggal = '" . $sTanggal ."' ";
		$rsLiturgiBhs = RunQuery($sSQL);
		extract(mysql_fetch_array($rsLiturgiBhs));
		
		if($DualBahasa>1){
		 if($Pukul=="06.00 WIB"){$Bahasa="Jawa";}else{$Bahasa="Indonesia";};
		 $sSQL = "SELECT * FROM LiturgiGKJBekti  WHERE Tanggal = '" . $sTanggal ."' AND Bahasa = '". $Bahasa ."' ";
		 }
		 else 
		 {
		 $sSQL = "SELECT * FROM LiturgiGKJBekti  WHERE Tanggal = '" . $sTanggal ."' ";
		 }
		 
		
		
	echo $sSQL;
		$rsLiturgi = RunQuery($sSQL);
		extract(mysql_fetch_array($rsLiturgi));
		
		$sLiturgiID = $LiturgiID;
		
		$sTanggal = $Tanggal;
		$sWarna = $Warna;
		$sBahasa = $Bahasa;
		$sKeterangan = $Keterangan;
		$sNas = $Tema;
		$sBacaan1 = $Bacaan1;
		$sBacaanAntara = $BacaanAntara;
		$sBacaan2 = $Bacaan2;
		$sBacaan4 = $BacaanInjil;
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
		
		//Get data PelayanFirman
	
//		$sSQL = "SELECT a.* ,IF(c.PelayanFirman>0, d.NamaPendeta , c.PFnonInstitusi) as Pengkotbah	
//		FROM JadwalPelayanFirman  a 
//		LEFT JOIN JadwalPelayanFirman c ON a.TanggalPF = c.TanggalPF 
//		LEFT JOIN DaftarPendeta d ON c.PelayanFirman = d.PendetaID 
//		WHERE a.TanggalPF = '" . $sTanggal . "' AND a.WaktuPF = '" . $sPukul ."'" ;
			
			
$sSQL = "SELECT a.* , b.NamaTI as NamaTI, a.PelayanFirman, IF(a.PelayanFirman>0, d.NamaPendeta , a.PFnonInstitusi) as Pengkotbah FROM JadwalPelayanFirman a 
LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
LEFT JOIN DaftarPendeta d ON a.PelayanFirman = d.PendetaID 
WHERE ( a.TanggalPF = '" . $sTanggal . "' AND a.WaktuPF = '" . $sPukul ."') ";	

	$rsPF = RunQuery($sSQL);
		//		echo $sSQL;
		extract(mysql_fetch_array($rsPF));
		
		$sPengkotbah = $Pengkotbah;
		$sPelayanFirmanID = $PelayanFirmanID;
		$sBahasa = $Bahasa;
		$sHal = $Hal;
		$sSalutation = $Salutation;
		$sPelayanFirman = $PelayanFirman;
		$sPFnonInstitusi = $PFnonInstitusi;
		$sPFNIAlamat = $PFNIAlamat;
		$sPFNIEmail = $PFNIEmail;
		$sTanggalPF = $TanggalPF;
		$sKodeTI = $KodeTI;
		$sTempatPF = $TempatPF;						
		$sWaktuPF = $WaktuPF;				
			
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
	}
}

//Get Church Names for the drop-down
//$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
//$rsLokasiTI = RunQuery($sSQL);


require "Include/Header.php";

?>

<script type="text/javascript"><!--
function updatesum() {
document.PersembahanEditor.Nilai.value = (document.PersembahanEditor.Nilai1.value -0) + (document.PersembahanEditor.Nilai2.value -0) + (document.PersembahanEditor.Nilai3.value -0) + (document.PersembahanEditor.Nilai4.value -0) + (document.PersembahanEditor.Nilai5.value -0) + (document.PersembahanEditor.Nilai6.value -0) + (document.PersembahanEditor.Nilai7.value -0) + (document.PersembahanEditor.Nilai8.value -0) + (document.PersembahanEditor.Nilai9.value -0) + (document.PersembahanEditor.Nilai10.value -0) ;
}
//--></script>

<form method="post" action="PersembahanEditor.php?Persembahan_ID=<?php echo $iPersembahan_ID; ?>" name="PersembahanEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="PersembahanSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"PersembahanSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="PersembahanCancel" onclick="javascript:document.location='<?php if (strlen($iPersembahan_ID) > 0) 
{ echo "PersembahanView.php?Persembahan_ID=" . $iPersembahan_ID; 
} else {echo "SelectList.php?mode=Persembahan&amp;GID=$refresh"; 
} ?>';">
		</td>
	</tr>

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
		<?php if ( $bErrorFlag ) echo "<span class=\"LargeText\" style=\"color: red;\">" . gettext("Ada keSALAHan pengisian atau pilihan. Perubahan tidak akan disimpan! Silahkan diKOREKSI dan dicoba lagi!") . "</span>"; ?>
		</td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" valign="top" >
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Data Penerimaan Persembahan"); ?></b></td>
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal :"); ?></td>
				<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
				</td>
			
				<td class="LabelColumn"><?php echo gettext("Pukul :"); ?></td>
				<td class="TextColumnWithBottomBorder">
					<select name="Pukul">
						<option value="0"><?php echo gettext("Pilih"); ?></option>
						<option value="06.00 WIB" <?php if ($sPukul == "06.00 WIB") { echo "selected"; } ?>><?php echo gettext("06.00 WIB"); ?></option>
						<option value="07.00 WIB" <?php if ($sPukul == "07.00 WIB") { echo "selected"; } ?>><?php echo gettext("07.00 WIB"); ?></option>
						<option value="07.30 WIB" <?php if ($sPukul == "07.30 WIB") { echo "selected"; } ?>><?php echo gettext("07.30 WIB"); ?></option>
						<option value="08.00 WIB" <?php if ($sPukul == "08.00 WIB") { echo "selected"; } ?>><?php echo gettext("08.00 WIB"); ?></option>
						<option value="08.30 WIB" <?php if ($sPukul == "08.30 WIB") { echo "selected"; } ?>><?php echo gettext("08.30 WIB"); ?></option>
						<option value="09.00 WIB" <?php if ($sPukul == "09.00 WIB") { echo "selected"; } ?>><?php echo gettext("09.00 WIB"); ?></option>
						<option value="09.30 WIB" <?php if ($sPukul == "09.30 WIB") { echo "selected"; } ?>><?php echo gettext("09.30 WIB"); ?></option>
						<option value="10.00 WIB" <?php if ($sPukul == "10.00 WIB") { echo "selected"; } ?>><?php echo gettext("10.00 WIB"); ?></option>
						<option value="10.30 WIB" <?php if ($sPukul == "10.30 WIB") { echo "selected"; } ?>><?php echo gettext("10.30 WIB"); ?></option>
						<option value="11.00 WIB" <?php if ($sPukul == "11.00 WIB") { echo "selected"; } ?>><?php echo gettext("11.00 WIB"); ?></option>
						<option value="11.30 WIB" <?php if ($sPukul == "11.30 WIB") { echo "selected"; } ?>><?php echo gettext("11.30 WIB"); ?></option>
						<option value="12.00 WIB" <?php if ($sPukul == "12.00 WIB") { echo "selected"; } ?>><?php echo gettext("12.00 WIB"); ?></option>
						<option value="12.30 WIB" <?php if ($sPukul == "12.30 WIB") { echo "selected"; } ?>><?php echo gettext("12.30 WIB"); ?></option>
						<option value="13.00 WIB" <?php if ($sPukul == "13.00 WIB") { echo "selected"; } ?>><?php echo gettext("13.00 WIB"); ?></option>
						<option value="13.30 WIB" <?php if ($sPukul == "13.30 WIB") { echo "selected"; } ?>><?php echo gettext("13.30 WIB"); ?></option>
						<option value="14.00 WIB" <?php if ($sPukul == "14.00 WIB") { echo "selected"; } ?>><?php echo gettext("14.00 WIB"); ?></option>
						<option value="14.30 WIB" <?php if ($sPukul == "14.30 WIB") { echo "selected"; } ?>><?php echo gettext("14.30 WIB"); ?></option>
						<option value="15.00 WIB" <?php if ($sPukul == "15.00 WIB") { echo "selected"; } ?>><?php echo gettext("15.00 WIB"); ?></option>
						<option value="15.30 WIB" <?php if ($sPukul == "15.30 WIB") { echo "selected"; } ?>><?php echo gettext("15.30 WIB"); ?></option>
						<option value="16.00 WIB" <?php if ($sPukul == "16.00 WIB") { echo "selected"; } ?>><?php echo gettext("16.00 WIB"); ?></option>
						<option value="16.30 WIB" <?php if ($sPukul == "16.30 WIB") { echo "selected"; } ?>><?php echo gettext("16.30 WIB"); ?></option>
						<option value="17.00 WIB" <?php if ($sPukul == "17.00 WIB") { echo "selected"; } ?>><?php echo gettext("17.00 WIB"); ?></option>
						<option value="17.30 WIB" <?php if ($sPukul == "17.30 WIB") { echo "selected"; } ?>><?php echo gettext("17.30 WIB"); ?></option>
						<option value="18.00 WIB" <?php if ($sPukul == "18.00 WIB") { echo "selected"; } ?>><?php echo gettext("18.00 WIB"); ?></option>
						<option value="18.30 WIB" <?php if ($sPukul == "18.30 WIB") { echo "selected"; } ?>><?php echo gettext("18.30 WIB"); ?></option>
						<option value="19.00 WIB" <?php if ($sPukul == "19.00 WIB") { echo "selected"; } ?>><?php echo gettext("19.00 WIB"); ?></option>
						<option value="19.30 WIB" <?php if ($sPukul == "19.30 WIB") { echo "selected"; } ?>><?php echo gettext("19.30 WIB"); ?></option>
						<option value="20.00 WIB" <?php if ($sPukul == "20.00 WIB") { echo "selected"; } ?>><?php echo gettext("20.00 WIB"); ?></option>
						<option value="20.30 WIB" <?php if ($sPukul == "20.30 WIB") { echo "selected"; } ?>><?php echo gettext("20.30 WIB"); ?></option>
						<option value="21.00 WIB" <?php if ($sPukul == "21.00 WIB") { echo "selected"; } ?>><?php echo gettext("21.00 WIB"); ?></option>
						<option value="21.30 WIB" <?php if ($sPukul == "21.30 WIB") { echo "selected"; } ?>><?php echo gettext("21.30 WIB"); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Tempat Ibadah:"); ?></td>
				<td class="TextColumn">
					<select name="KodeTI">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Church Names for the drop-down
						$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
						$rsLokasiTI = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsLokasiTI))
						{
							extract($aRow);

							echo "<option value=\"" . $KodeTI . "\"";
							if ($sKodeTI == $KodeTI) { echo " selected"; }
							echo ">" . $NamaTI ;
						}
						?>
					</select>
				</td>
				<td class="LabelColumn"><?php echo gettext("Bahasa:"); ?></td>
				<td class="TextColumnWithBottomBorder">
					<select name="Liturgi">
						<option value="0"><?php echo gettext("Liturgi Biasa"); ?></option>
						<option value="1" <?php if ($sLiturgi == 1) { echo "selected"; } ?>><?php echo gettext("Liturgi Khusus"); ?></option>
					</select>
				</td>			
			</tr>	
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Pengkotbah"); ?></b></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Pengkotbah"); ?></td>
				<td colspan="4" class="TextColumn"><input    type="text" name="Pengkotbah" id="Pengkotbah" size="70" value="<?php echo htmlentities(stripslashes($sPengkotbah),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sPengkotbahError ?></font></td>
			</tr>
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Bacaan Alkitab"); ?></b></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Bacaan 1"); ?></td>
				<td class="TextColumn"><input    type="text" name="Bacaan1" id="Bacaan1" value="<?php echo htmlentities(stripslashes($sBacaan1),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sBacaan1Error ?></font></td>

				<td class="LabelColumn"><?php echo gettext("Bacaan 3"); ?></td>
				<td class="TextColumn"><input    type="text" name="Bacaan3" id="Bacaan3" value="<?php echo htmlentities(stripslashes($sBacaan3),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sBacaan3Error ?></font></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Bacaan 2"); ?></td>
				<td class="TextColumn"><input    type="text" name="Bacaan2" id="Bacaan2" value="<?php echo htmlentities(stripslashes($sBacaan2),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sBacaan2Error ?></font></td>

				<td class="LabelColumn"><?php echo gettext("Bacaan 4"); ?></td>
				<td class="TextColumn"><input    type="text" name="Bacaan4" id="Bacaan4" value="<?php echo htmlentities(stripslashes($sBacaan4),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sBacaan4Error ?></font></td>
			</tr>
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Nas / Tema"); ?></b></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nas / Tema"); ?></td>
				<td colspan="4" class="TextColumn"><input   type="text" name="Nas" id="Nas" size="70" value="<?php echo htmlentities(stripslashes($sNas),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNasError ?></font></td>
			</tr>
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Nyanyian"); ?></b></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 1"); ?></td>
				<td class="TextColumn"><input   type="text" name="Nyanyian1" id="Nyanyian1" value="<?php echo htmlentities(stripslashes($sNyanyian1),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian1Error ?></font></td>

				<td class="LabelColumn"><?php echo gettext("Nyanyian 9"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian9" id="Nyanyian9" value="<?php echo htmlentities(stripslashes($sNyanyian9),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian9Error ?></font></td>

			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 2"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian2" id="Nyanyian2" value="<?php echo htmlentities(stripslashes($sNyanyian2),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian2Error ?></font></td>

				<td class="LabelColumn"><?php echo gettext("Nyanyian 10"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian10" id="Nyanyian10" value="<?php echo htmlentities(stripslashes($sNyanyian10),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian10Error ?></font></td>

			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 3"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian3" id="Nyanyian3" value="<?php echo htmlentities(stripslashes($sNyanyian3),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian3Error ?></font></td>

				<td class="LabelColumn"><?php echo gettext("Nyanyian 11"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian11" id="Nyanyian11" value="<?php echo htmlentities(stripslashes($sNyanyian11),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian11Error ?></font></td>

			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 4"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian4" id="Nyanyian4" value="<?php echo htmlentities(stripslashes($sNyanyian4),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian4Error ?></font></td>

				<td class="LabelColumn"><?php echo gettext("Nyanyian 12"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian12" id="Nyanyian12" value="<?php echo htmlentities(stripslashes($sNyanyian12),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian12Error ?></font></td>

			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 5"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian5" id="Nyanyian5" value="<?php echo htmlentities(stripslashes($sNyanyian5),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian5Error ?></font></td>

				<td class="LabelColumn"><?php echo gettext("Nyanyian 13"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian13" id="Nyanyian13" value="<?php echo htmlentities(stripslashes($sNyanyian13),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian13Error ?></font></td>

			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 6"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian6" id="Nyanyian6" value="<?php echo htmlentities(stripslashes($sNyanyian6),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian6Error ?></font></td>

				<td class="LabelColumn"><?php echo gettext("Nyanyian 14"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian14" id="Nyanyian14" value="<?php echo htmlentities(stripslashes($sNyanyian14),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian14Error ?></font></td>

			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 7"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian7" id="Nyanyian7" value="<?php echo htmlentities(stripslashes($sNyanyian7),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian7Error ?></font></td>

				<td class="LabelColumn"><?php echo gettext("Nyanyian 15"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian15" id="Nyanyian15" value="<?php echo htmlentities(stripslashes($sNyanyian15),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian15Error ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 8"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian8" id="Nyanyian8" value="<?php echo htmlentities(stripslashes($sNyanyian8),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian8Error ?></font></td>
				
				<td class="LabelColumn"><?php echo gettext("Nyanyian 16"); ?></td>
				<td class="TextColumn"><input    type="text" name="Nyanyian16" id="Nyanyian16" value="<?php echo htmlentities(stripslashes($sNyanyian16),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian16Error ?></font></td>				
			</tr>			
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Pelayanan Khusus"); ?></b></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Baptis Dewasa"); ?></td>
				<td class="TextColumn"><input class="right" type="text" name="BaptisDewasa" id="BaptisDewasa" size="10"  value="<?php echo htmlentities(stripslashes($sBaptisDewasa),ENT_NOQUOTES, "UTF-8"); ?>">
				Orang<br><font color="red"><?php echo $sBaptisDewasaError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Baptis Anak"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="BaptisAnak" id="BaptisAnak" size="10"  value="<?php echo htmlentities(stripslashes($sBaptisAnak),ENT_NOQUOTES, "UTF-8"); ?>">
				Orang<br><font color="red"><?php echo $sBaptisDewasaError ?></font></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Sidi"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Sidi" id="Sidi" size="10"  value="<?php echo htmlentities(stripslashes($sSidi),ENT_NOQUOTES, "UTF-8"); ?>">
				Orang<br><font color="red"><?php echo $sSidiError ?></font></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Pengakuan Dosa"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="PengakuanDosa" id="PengakuanDosa" size="10"  value="<?php echo htmlentities(stripslashes($sPengakuanDosa),ENT_NOQUOTES, "UTF-8"); ?>">
				Orang<br><font color="red"><?php echo $sPengakuanDosaError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Penerimaan Warga"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="PenerimaanWarga" id="PenerimaanWarga" size="10" value="<?php echo htmlentities(stripslashes($sPenerimaanWarga),ENT_NOQUOTES, "UTF-8"); ?>">
				Orang<br><font color="red"><?php echo $sPenerimaanWargaError ?></font></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Pemberkatan Perkawinan"); ?></td>
				<td class="TextColumn"><input type="text" name="Pemberkatan1" id="Pemberkatan1" value="<?php echo htmlentities(stripslashes($sPemberkatan1),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sPemberkatan1Error ?></font></td>

				<td class="LabelColumn"><?php echo gettext("dengan"); ?></td>
				<td class="TextColumn"><input type="text" name="Pemberkatan2" id="Pemberkatan2" value="<?php echo htmlentities(stripslashes($sPemberkatan2),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sPemberkatan2Error ?></font></td>
			</tr>				
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Persembahan"); ?></b></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Kebaktian Dewasa"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="KebDewasa" id="KebDewasa" value="<?php echo htmlentities(stripslashes($sKebDewasa),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sKebDewasaError ?></font></td>
				<td class="LabelColumn"><?php echo gettext("KetPersembahan:"); ?></td>
				<td class="TextColumnWithBottomBorder">
					<select name="KetPersembahan">
						<option value="0" <?php if ($sKetPersembahan == 0) { echo "selected"; } ?>><?php echo gettext("Dengan Persembahan"); ?></option>
						<option value="1" <?php if ($sKetPersembahan == 1) { echo "selected"; } ?>><?php echo gettext("Tanpa Persembahan"); ?></option>
					</select>
				</td>	
			</tr>	
			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Kebaktian Pemuda"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="KebPemuda" id="KebPemuda" value="<?php echo htmlentities(stripslashes($sKebPemuda),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sKebPemudaError ?></font></td>
			
				<td>
				<?
				$sSQL = "SELECT * FROM PersembahanPemudagkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul='" . $Pukul . "'";
				$rsPersembahanPemuda = RunQuery($sSQL);
		
								while ($hasilGD=mysql_fetch_array($rsPersembahanPemuda))
				{
				$i++;
									extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				if ( $KebRemaja == $lPersembahan ){	
				echo "<a href=\"PersembahanAnakEditor.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Pemuda \"> View Detail </a></td><td>";
				} else
				{
				echo "<a href=\"PersembahanAnakEditor.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Pemuda \">
				<font color=\"red\"> View Detail</td><td> - Error ( " . $lPersembahan . " ) </td></font></a><td>";

				}
				}
				?> 


				</td>
				
				<td>
				</td>
			</tr>
			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Kebaktian Remaja"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="KebRemaja" id="KebRemaja" value="<?php echo htmlentities(stripslashes($sKebRemaja),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sKebRemajaError ?></font></td>
			
				<td>
				<?
				$sSQL = "SELECT * FROM PersembahanRemajagkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul='" . $Pukul . "'";
				$rsPersembahanRemaja = RunQuery($sSQL);
		
								while ($hasilGD=mysql_fetch_array($rsPersembahanRemaja))
				{
				$i++;
									extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				if ( $KebRemaja == $lPersembahan ){	
				echo "<a href=\"PersembahanAnakEditor.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Remaja \"> View Detail </a></td><td>";
				} else
				{
				echo "<a href=\"PersembahanAnakEditor.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Remaja \">
				<font color=\"red\"> View Detail</td><td> - Error ( " . $lPersembahan . " ) </td></font></a><td>";

				}
				}
				?> 


				</td>
				
				<td>
				</td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Kebaktian Pra Remaja"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="KebPraRemaja" id="KebPraRemaja" value="<?php echo htmlentities(stripslashes($sKebPraRemaja),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sKebPraRemajaError ?></font></td>
			
				<td>
				<?
				$sSQL = "SELECT * FROM PersembahanPraRemajagkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul='" . $Pukul . "'";
				$rsPersembahanPraRemaja = RunQuery($sSQL);
		
								while ($hasilGD=mysql_fetch_array($rsPersembahanPraRemaja))
				{
				$i++;
									extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				if ( $KebRemaja == $lPersembahan ){	
				echo "<a href=\"PersembahanAnakEditor.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=PraRemaja \"> View Detail </a></td><td>";
				} else
				{
				echo "<a href=\"PersembahanAnakEditor.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=PraRemaja \">
				<font color=\"red\"> View Detail</td><td> - Error ( " . $lPersembahan . " ) </td></font></a><td>";

				}
				}
				?> 


				</td>
				
				<td>
				</td>
			</tr>
			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Kebaktian Anak"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="KebAnak" id="KebAnak" value="<?php echo htmlentities(stripslashes($sKebAnak),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sKebAnakError ?></font></td>
				
				<td>
				<?
				$sSQL = "SELECT * FROM PersembahanAnakgkjbekti  WHERE Tanggal='" . $ReffTanggal . "' AND KodeTI='" . $ReffKodeTI . "' AND Pukul='" . $ReffPukul . "'";
				$rsPersembahanAnak = RunQuery($sSQL);
		 
								while ($hasilGD=mysql_fetch_array($rsPersembahanAnak))
				{
				$i++;
									extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				if ( $KebAnak == $lPersembahan ){	
				echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Anak \"> View Detail </a></td><td>";
				} else
				{
				echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Anak \">
				<font color=\"red\"> View Detail</td><td> - Error ( " . $lPersembahan . " ) </td></font></a><td>";

				}
				}
				?> <?php //echo $sSQL; ?> ;
				</td>
			</tr>				
			<tr>
				<td class="LabelColumn"><?php echo gettext("Kebaktian Anak JTMY"); ?></td>
				<td class="TextColumn"><input class="right" type="text" name="KebAnakJTMY" id="KebAnakJTMY" value="<?php echo htmlentities(stripslashes($sKebAnakJTMY),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sKebAnakJTMYError ?></font></td>
				
				<td>
				
				</td>
				
				<td>
				</td>
			</tr>	

			<tr>
				<td class="LabelColumn"><?php echo gettext("Syukur"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Syukur" id="Syukur" value="<?php echo htmlentities(stripslashes($sSyukur),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sSyukurError ?></font></td>

				<td class="LabelColumn"><?php echo gettext(" : "); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="SyukurAmplop" id="SyukurAmplop" value="<?php echo htmlentities(stripslashes($sSyukurAmplop),ENT_NOQUOTES, "UTF-8"); ?>">
				Amplop<br><font color="red"><?php echo $sSyukurAmplopError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Bulanan"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Bulanan" id="Bulanan" value="<?php echo htmlentities(stripslashes($sBulanan),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sBulananError ?></font></td>

				<td class="LabelColumn"><?php echo gettext(" : "); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="BulananAmplop" id="BulananAmplop" value="<?php echo htmlentities(stripslashes($sBulananAmplop),ENT_NOQUOTES, "UTF-8"); ?>">
				Amplop<br><font color="red"><?php echo $sBulananAmplopError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Khusus"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Khusus" id="Khusus" value="<?php echo htmlentities(stripslashes($sKhusus),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sKhususError ?></font></td>

				<td class="LabelColumn"><?php echo gettext(" : "); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="KhususAmplop" id="KhususAmplop" value="<?php echo htmlentities(stripslashes($sKhususAmplop),ENT_NOQUOTES, "UTF-8"); ?>">
				Amplop<br><font color="red"><?php echo $sKhususAmplopError ?></font></td>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Syukur Baptis/Sidi/Perkawinan"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="SyukurBaptis" id="SyukurBaptis" value="<?php echo htmlentities(stripslashes($sSyukurBaptis),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sSyukurBaptisError ?></font></td>

				<td class="LabelColumn"><?php echo gettext(" : "); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="SyukurBaptisAmplop" id="SyukurBaptisAmplop" value="<?php echo htmlentities(stripslashes($sSyukurBaptisAmplop),ENT_NOQUOTES, "UTF-8"); ?>">
				Amplop<br><font color="red"><?php echo $sSyukurBaptisAmplopError ?></font></td>
			</tr>
			
			</tr>
				<tr>
				<td class="LabelColumn"><?php echo gettext("Khusus Perjamuan Kudus"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="KhususPerjamuan" id="KhususPerjamuan" value="<?php echo htmlentities(stripslashes($sKhususPerjamuan),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sKhususPerjamuanError ?></font></td>

				<td class="LabelColumn"><?php echo gettext(" : "); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="KhususPerjamuanAmplop" id="KhususPerjamuanAmplop" value="<?php echo htmlentities(stripslashes($sKhususPerjamuanAmplop),ENT_NOQUOTES, "UTF-8"); ?>">
				Amplop<br><font color="red"><?php echo $sKhususPerjamuanAmplopError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Masa Raya Paskah"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Marapas" id="Marapas" value="<?php echo htmlentities(stripslashes($sMarapas),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sMarapasError ?></font></td>

				<td class="LabelColumn"><?php echo gettext(" : "); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="MarapasAmplop" id="MarapasAmplop" value="<?php echo htmlentities(stripslashes($sMarapasAmplop),ENT_NOQUOTES, "UTF-8"); ?>">
				Amplop<br><font color="red"><?php echo $sMarapasAmplopError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Masa Raya Pentakosta"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Marapen" id="Marapen" value="<?php echo htmlentities(stripslashes($sMarapen),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sMarapenError ?></font></td>

				<td class="LabelColumn"><?php echo gettext(" : "); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="MarapenAmplop" id="MarapenAmplop" value="<?php echo htmlentities(stripslashes($sMarapenAmplop),ENT_NOQUOTES, "UTF-8"); ?>">
				Amplop<br><font color="red"><?php echo $sMarapenAmplopError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Masa Raya Natal"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Maranatal" id="Maranatal" value="<?php echo htmlentities(stripslashes($sMaranatal),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sMaranatalError ?></font></td>

				<td class="LabelColumn"><?php echo gettext(" : "); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="MaranatalAmplop" id="MaranatalAmplop" value="<?php echo htmlentities(stripslashes($sMaranatalAmplop),ENT_NOQUOTES, "UTF-8"); ?>">
				Amplop<br><font color="red"><?php echo $sMaranatalAmplopError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Masa Raya Unduh2"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Unduh" id="Unduh" value="<?php echo htmlentities(stripslashes($sUnduh),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sUnduhError ?></font></td>

				<td class="LabelColumn"><?php echo gettext(" : "); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="UnduhAmplop" id="UnduhAmplop" value="<?php echo htmlentities(stripslashes($sUnduhAmplop),ENT_NOQUOTES, "UTF-8"); ?>">
				Amplop<br><font color="red"><?php echo $sUnduhAmplopError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Amplop Pink"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Pink" id="Pink" value="<?php echo htmlentities(stripslashes($sPink),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sPinkError ?></font></td>

				<td class="LabelColumn"><?php echo gettext(" : "); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="PinkAmplop" id="PinkAmplop" value="<?php echo htmlentities(stripslashes($sPinkAmplop),ENT_NOQUOTES, "UTF-8"); ?>">
				Amplop<br><font color="red"><?php echo $sPinkAmplopError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Lain-Lain"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="LainLain" id="LainLain" value="<?php echo htmlentities(stripslashes($sLainLain),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sLainLainError ?></font></td>

				<td class="LabelColumn"><?php echo gettext(" : "); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="LainLainAmplop" id="LainLainAmplop" value="<?php echo htmlentities(stripslashes($sLainLainAmplop),ENT_NOQUOTES, "UTF-8"); ?>">
				Amplop<br><font color="red"><?php echo $sLainLainAmplopError ?></font></td>
			</tr>	
			<tr>
			<td class="LabelColumn"><?php echo gettext("Total Persembahan"); ?></td>
			<td class="TextColumn" >
			<b>
			<?php 
			
			$TotalPersembahan = $sKebDewasa + $sKebAnak + $sKebAnakJTMY + $sKebRemaja + $sKebPraRemaja + $sKebPemuda + 
			$sSyukur + $sSyukurBaptis + $sBulanan + $sKhusus + $sKhususPerjamuan + $sMarapas +
			$sMarapen + $sMaranatal + $sUnduh +$sPink + $sLainLain;
			echo currency('Rp. ',$TotalPersembahan,'.',',00'); 
			?>
			</b>
			</td>
			</tr>
			
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Jemaat yang Hadir"); ?></b></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Laki laki"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Pria" id="Pria" value="<?php echo htmlentities(stripslashes($sPria),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sPriaError ?></font></td>

				<td class="LabelColumn"><?php echo gettext("Perempuan"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Wanita" id="Wanita" value="<?php echo htmlentities(stripslashes($sWanita),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sWanitaError ?></font></td>
			</tr>
				<tr>
			<td class="LabelColumn"><?php echo gettext("Total"); ?></td>
			<td  class="right" >
			<b><?php $TotalJemaat = $sPria + $sWanita;
			echo $TotalJemaat;
			?>
			</b>
			</td>
			</tr>		
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Majelis Yang Hadir"); ?></b></td>
			</tr>
			
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 1"); ?></td>
				<td class="TextColumn">
					<select name="Majelis1">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }								
							$NamaMajelis = $MjlType . "" . $per_FirstName;
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis1 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>			
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 8"); ?></td>
				<td class="TextColumn">
					<select name="Majelis8">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }								
							$NamaMajelis = $MjlType . "" . $per_FirstName;							
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis8 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>

			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 2"); ?></td>
				<td class="TextColumn">
					<select name="Majelis2">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }								
							$NamaMajelis = $MjlType . "" . $per_FirstName;
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis2 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 9"); ?></td>
				<td class="TextColumn">
					<select name="Majelis9">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }								
							$NamaMajelis = $MjlType . "" . $per_FirstName;
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis9 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 3"); ?></td>
				<td class="TextColumn">
					<select name="Majelis3">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }								
							$NamaMajelis = $MjlType . "" . $per_FirstName;
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis3 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 10"); ?></td>
				<td class="TextColumn">
					<select name="Majelis10">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }								
							$NamaMajelis = $MjlType . "" . $per_FirstName;
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis10 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>
			</tr>			
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 4"); ?></td>
				<td class="TextColumn">
					<select name="Majelis4">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }								
							$NamaMajelis = $MjlType . "" . $per_FirstName;
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis4 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 11"); ?></td>
				<td class="TextColumn">
					<select name="Majelis11">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }	
							$NamaMajelis = $MjlType . "" . $per_FirstName;							
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis11 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 5"); ?></td>
				<td class="TextColumn">
					<select name="Majelis5">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }	
							$NamaMajelis = $MjlType . "" . $per_FirstName;							
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis5 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 12"); ?></td>
				<td class="TextColumn">
					<select name="Majelis12">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }
							$NamaMajelis = $MjlType . "" . $per_FirstName;							
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis12 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>
			</tr>			
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 6"); ?></td>
				<td class="TextColumn">
					<select name="Majelis6">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }	
							$NamaMajelis = $MjlType . "" . $per_FirstName;							
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis6 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 13"); ?></td>
				<td class="TextColumn">
					<select name="Majelis13">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }	
							$NamaMajelis = $MjlType . "" . $per_FirstName;							
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis13 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 7"); ?></td>
				<td class="TextColumn">
					<select name="Majelis7">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }	
							$NamaMajelis = $MjlType . "" . $per_FirstName;							
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis7 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 14"); ?></td>
				<td class="TextColumn">
					<select name="Majelis14">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }	
							$NamaMajelis = $MjlType . "" . $per_FirstName;							
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis14 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>
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
		$logvar2 = "Persembahan Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
