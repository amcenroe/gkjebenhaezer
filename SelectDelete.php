<?php
/*******************************************************************************
 *
 *  filename    : SelectDelete
 *  last change : 2003-01-07
 *  website     : http://www.infocentral.org
 *  copyright   : Copyright 2001-2003 Deane Barker, Lewis Franklin
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur ( http://www.gkjbekasi-wiltimur.net )
 *  2009 Erwin Pratama for GKJ Bekasi Timur ( http://www.gkjbekasitimur.org )
 *  2010 Erwin Pratama for GKPB Bali ( http://www.balichurchsynod.org/ )
 *  2013 Erwin Pratama for GKJ Tanjung Priok ( http://www.gkjtp.com )
 *
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";

// Security: User must have Delete records permission
// Otherwise, re-direct them to the main menu.
if (!$_SESSION['bDeleteRecords'])
{
	Redirect("Menu.php");
	exit;
}

if (!empty($_GET["FamilyID"])) $iFamilyID = FilterInput($_GET["FamilyID"],'int');
if (!empty($_GET["PersonID"])) $iPersonID = FilterInput($_GET["PersonID"],'int');
if (!empty($_GET["MailID"])) $iMailID = FilterInput($_GET["MailID"],'int');
if (!empty($_GET["SKID"])) $iSKID = FilterInput($_GET["SKID"],'int');
if (!empty($_GET["PakID"])) $iPakID = FilterInput($_GET["PakID"],'int');
if (!empty($_GET["AssetID"])) $iAssetID = FilterInput($_GET["AssetID"],'int');
if (!empty($_GET["Persembahan_ID"])) $iPersembahan_ID = FilterInput($_GET["Persembahan_ID"],'int');
if (!empty($_GET["PersembahanAnak_ID"])) $iPersembahanAnak_ID = FilterInput($_GET["PersembahanAnak_ID"],'int');
if (!empty($_GET["PersembahanPraRemaja_ID"])) $iPersembahanPraRemaja_ID = FilterInput($_GET["PersembahanPraRemaja_ID"],'int');
if (!empty($_GET["PersembahanRemaja_ID"])) $iPersembahanRemaja_ID = FilterInput($_GET["PersembahanRemaja_ID"],'int');
if (!empty($_GET["PersembahanPemuda_ID"])) $iPersembahanPemuda_ID = FilterInput($_GET["PersembahanPemuda_ID"],'int');
if (!empty($_GET["PersembahanKontribusi_ID"])) $iPersembahanRemaja_ID = FilterInput($_GET["PersembahanKontribusi_ID"],'int');
if (!empty($_GET["DonationFamilyID"])) $iDonationFamilyID = FilterInput($_GET["DonationFamilyID"],'int');
if (!empty($_GET["mode"])) $sMode = $_GET["mode"];

if ($_GET["CancelFamily"]){
	Redirect("FamilyView.php?FamilyID=$iFamilyID");
	exit;
}

if ($_GET["CancelMail"]){
	Redirect("MailView.php?MailID=$iMailID");
	exit;
}

if ($_GET["CancelMailOut"]){
	Redirect("MailOutView.php?MailID=$iMailID");
	exit;
}

if ($_GET["CancelSuKep"]){
	Redirect("SuKepView.php?SKID=$iSKID");
	exit;
}
if ($_GET["CancelPak"]){
	Redirect("PakView.php?PakID=$iPakID");
	exit;
}

if ($_GET["CancelAsset"]){
	Redirect("AsetView.php?AssetID=$iAssetID");
	exit;
}

if ($_GET["CancelPersembahan"]){
	Redirect("PersembahanView.php?Persembahan_ID=$iPersembahan_ID");
	exit;
}
if ($_GET["CancelPersembahanAnak"]){
	Redirect("PersembahanAnakView.php?Persembahan_ID=$iPersembahan_ID&Kategori=Anak");
	exit;
}
if ($_GET["CancelPersembahanPraRemaja"]){
	Redirect("PersembahanAnakView.php?Persembahan_ID=$iPersembahan_ID&Kategori=PraRemaja");
	exit;
}
if ($_GET["CancelPersembahanRemaja"]){
	Redirect("PersembahanAnakView.php?Persembahan_ID=$iPersembahan_ID&Kategori=Remaja");
	exit;
}
if ($_GET["CancelPersembahanPemuda"]){
	Redirect("PersembahanAnakView.php?Persembahan_ID=$iPersembahan_ID&Kategori=Pemuda");
	exit;
}
if ($_GET["CancelPersembahanKhusus"]){
	Redirect("PersembahanAnakView.php?Persembahan_ID=$iPersembahan_ID&Kategori=Khusus");
	exit;
}
if ($_GET["CancelPersembahanKontribusi"]){
	Redirect("PersembahanAnakView.php?Persembahan_ID=$iPersembahan_ID&Kategori=Kontribusi");
	exit;
}


// Move Donations from 1 family to another
if ($_SESSION['bFinance'] && $_GET["MoveDonations"] && $iFamilyID && $iDonationFamilyID && $iFamilyID != $iDonationFamilyID) {
	$today = date("Y-m-d");
	$sSQL = "UPDATE pledge_plg SET plg_FamID='$iDonationFamilyID',
		plg_DateLastEdited ='$today', plg_EditedBy='".$_SESSION["iUserID"]
		."' WHERE plg_FamID='$iFamilyID'";
	RunQuery($sSQL);
	$DonationMessage = "<p><b><font color=red>".gettext("All donations from this family have been moved to another family.") . "</font></b></p>";
}



//Set the Page Title



if($sMode == 'person')
	{ $sPageTitle = gettext("Konfirmasi HAPUS data jemaat");
	$logvar1 = "Delete";
	$logvar2 = "Person Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iFamilyID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
elseif($sMode == 'mail')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Surat ");
	$logvar1 = "Delete";
	$logvar2 = "Incoming Mail Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'mailout')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Surat Keluar ");
	$logvar1 = "Delete";
	$logvar2 = "Outgoing Mail Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}		
elseif($sMode == 'sukep')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Surat Keputusan");
	$logvar1 = "Delete";
	$logvar2 = "Surat Keputusan Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'pak')
	{$sPageTitle = gettext("Konfirmasi HAPUS data PAK");
	$logvar1 = "Delete";
	$logvar2 = "PAK Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPakID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'aset')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Aset");
	$logvar1 = "Delete";
	$logvar2 = "Aset Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iAssetID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
elseif($sMode == 'Persembahan')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Persembahan");
	$logvar1 = "Delete";
	$logvar2 = "Persembahan Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'PersembahanAnak')
	{$sPageTitle = gettext("Konfirmasi HAPUS data PersembahanAnak");
	$logvar1 = "Delete";
	$logvar2 = "PersembahanAnak Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'PersembahanRemaja')
	{$sPageTitle = gettext("Konfirmasi HAPUS data PersembahanRemaja");
	$logvar1 = "Delete";
	$logvar2 = "PersembahanRemaja Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'PersembahanKhusus')
	{$sPageTitle = gettext("Konfirmasi HAPUS data PersembahanKhusus");
	$logvar1 = "Delete";
	$logvar2 = "PersembahanKhusus Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'PersembahanKontribusi')
	{$sPageTitle = gettext("Konfirmasi HAPUS data PersembahanKontribusi");
	$logvar1 = "Delete";
	$logvar2 = "PersembahanKontribusi Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
elseif($sMode == 'family')
	{$sPageTitle = gettext("Konfirmasi HAPUS data keluarga");
	$logvar1 = "Delete";
	$logvar2 = "Family Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iFamilyID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
else
	{$sPageTitle = gettext("Konfirmasi HAPUS data Surat Masuk");
	$logvar1 = "Delete";
	$logvar2 = "Incoming Mail Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
	
function DeletePerson($iPersonID)
{
	// Remove person from all groups they belonged to
	$sSQL = "SELECT p2g2r_grp_ID FROM person2group2role_p2g2r WHERE p2g2r_per_ID = " . $iPersonID;
	$rsAssignedGroups = RunQuery($sSQL);
	while ($aRow = mysql_fetch_array($rsAssignedGroups))
	{
		extract($aRow);
		RemoveFromGroup($iPersonID, $p2g2r_grp_ID);
	}

	// Remove custom field data
	$sSQL = "DELETE FROM person_custom WHERE per_ID = " . $iPersonID;
	RunQuery($sSQL);

	// Remove note data
	$sSQL = "DELETE FROM note_nte WHERE nte_per_ID = " . $iPersonID;
	RunQuery($sSQL);

	// Delete the Person record
	// Backup First
//	$sSQL = "INSERT INTO person_pindah SELECT * FROM person_per WHERE per_ID = " . $iPersonID;
//	RunQuery($sSQL);
	// Backup First Custom Records
//	$sSQL = "INSERT INTO person_custom_pindah SELECT * FROM person_custom WHERE per_ID = " . $iPersonID;
//	RunQuery($sSQL);

	$sSQL = "DELETE FROM person_per WHERE per_ID = " . $iPersonID;
	RunQuery($sSQL);

	// Remove person property data
	$sSQL = "SELECT pro_ID FROM property_pro WHERE pro_Class='p'";
	$rsProps = RunQuery($sSQL);

	while($aRow = mysql_fetch_row($rsProps)) {
		$sSQL = "DELETE FROM record2property_r2p WHERE r2p_pro_ID = " . $aRow[0] . " AND r2p_record_ID = " . $iPersonID;
		RunQuery($sSQL);
	}

	// Delete any User record
	// $sSQL = "DELETE FROM user_usr WHERE usr_per_ID = " . $iPersonID;
	// RunQuery($sSQL);

	// Make sure person was not in the cart
	RemoveFromPeopleCart($iPersonID);

	// Delete the photo files, if they exist
	$photoThumbnail = "Images/Person/thumbnails/" . $iPersonID . ".jpg";
	if (file_exists($photoThumbnail))
		unlink ($photoThumbnail);
	$photoFile = "Images/Person/" . $iPersonID . ".jpg";
	if (file_exists($photoFile))
		unlink ($photoFile);

	// Delete the BaptisAnak files, if they exist
	$photoBaptisAnakThumbnail = "Images/BaptisAnak/thumbnails/SBA" . $iPersonID . ".jpg";
	if (file_exists($photoBaptisAnakThumbnail))
		unlink ($photoBaptisAnakThumbnail);
	$photoBaptisAnakFile = "Images/BaptisAnak/SBA" . $iPersonID . ".jpg";
		if (file_exists($photoBaptisAnakFile))
		unlink ($photoBaptisAnakFile);

	// Delete the BaptisDewasa files, if they exist
	$photoBaptisDewasaThumbnail = "Images/BaptisDewasa/thumbnails/SBD" . $iPersonID . ".jpg";
	if (file_exists($photoBaptisDewasaThumbnail))
		unlink ($photoBaptisDewasaThumbnail);
	$photoBaptisDewasaFile = "Images/BaptisDewasa/SBD" . $iPersonID . ".jpg";
	if (file_exists($photoBaptisDewasaFile))
		unlink ($photoBaptisDewasaFile);

	// Delete the Sidhi files, if they exist
	$photoSidhiThumbnail = "Images/Sidhi/thumbnails/SS" . $iPersonID . ".jpg";
	if (file_exists($photoSidhiThumbnail))
		unlink ($photoSidhiThumbnail);
	$photoSidhiFile = "Images/Sidhi/SS" . $iPersonID . ".jpg";
	if (file_exists($photoSidhiFile))
		unlink ($photoSidhiFile);

	// Delete the Atestasi files, if they exist
	$photoATINThumbnail = "Images/AtestasiMasuk/thumbnails/ATIN" . $iPersonID . ".jpg";
	if (file_exists($photoATINThumbnail))
		unlink ($photoATINThumbnail);
	$photoATINFile = "Images/AtestasiMasuk/ATIN" . $iPersonID . ".jpg";
	if (file_exists($photoATINFile))
		unlink ($photoATINFile);


	$logvar1 = "Delete";
	$logvar2 = "Person Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iFamilyID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeleteMail($iMailID)
{
	// Delete the Mail record
	$sSQL = "DELETE FROM SuratMasuk WHERE MailID = " . $iMailID;
	RunQuery($sSQL);

	// Delete the doc files, if they exist
	$photoThumbnail = "Images/Mail/thumbnails/" . $iMailID . ".jpg";
	if (file_exists($photoThumbnail))
		unlink ($photoThumbnail);
	$photoFile = "Images/Mail/" . $iMailID . ".jpg";
	if (file_exists($photoFile))
		unlink ($photoFile);

	$logvar1 = "Delete";
	$logvar2 = "Incoming Mail Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeleteMailOut($iMailID)
{
	// Delete the MailOut record
	$sSQL = "DELETE FROM SuratKeluar WHERE MailID = " . $iMailID;
	RunQuery($sSQL);

	// Delete the doc files, if they exist
//	$photoThumbnail = "Images/MailOut/thumbnails/" . $iMailID . ".jpg";
//	if (file_exists($photoThumbnail))
//		unlink ($photoThumbnail);
//	$photoFile = "Images/Mail/" . $iMailID . ".jpg";
//	if (file_exists($photoFile))
//		unlink ($photoFile);

	$logvar1 = "Delete";
	$logvar2 = "Outgoing Mail Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeleteSuKep($iSKID)
{
	// Delete the Mail record
	$sSQL = "DELETE FROM SuratKeputusan WHERE SKID = " . $iSKID;
	RunQuery($sSQL);

	// Delete the doc files, if they exist
	$photoThumbnail = "Images/SK/thumbnails/" . $iSKID . ".jpg";
	if (file_exists($photoThumbnail))
		unlink ($photoThumbnail);
	$photoFile = "Images/SK/" . $iSKID . ".jpg";
	if (file_exists($photoFile))
		unlink ($photoFile);

	$logvar1 = "Delete";
	$logvar2 = "Surat Keputusan Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeletePak($iPakID)
{
	// Delete the Mail record
	$sSQL = "DELETE FROM pakgkjbekti WHERE PakID = " . $iPakID;
	RunQuery($sSQL);

	// Delete the doc files, if they exist
	$photoThumbnail = "Images/Pak/thumbnails/Pak" . $iPakID . ".jpg";
	if (file_exists($photoThumbnail))
		unlink ($photoThumbnail);
	$photoFile = "Images/Pak/Pak" . $iPakID . ".jpg";
	if (file_exists($photoFile))
		unlink ($photoFile);

	$logvar1 = "Delete";
	$logvar2 = "PAK Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPakID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeleteAset($iAssetID)
{
	// Delete the Mail record
	$sSQL = "DELETE FROM asetgkjbekti WHERE AssetID = " . $iAssetID;
	RunQuery($sSQL);

	// Delete the doc files, if they exist
	$photoThumbnail = "Images/Aset/thumbnails/Aset" . $iAssetID . ".jpg";
	if (file_exists($photoThumbnail))
		unlink ($photoThumbnail);
	$photoFile = "Images/Aset/Aset" . $iAssetID . ".jpg";
	if (file_exists($photoFile))
		unlink ($photoFile);

	$logvar1 = "Delete";
	$logvar2 = "Asset Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPakID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}

function DeletePersembahan($iPersembahan_ID)
{
	// Delete the Persembahan record
	$sSQL = "DELETE FROM Persembahangkjbekti WHERE Persembahan_ID = " . $iPersembahan_ID;
	RunQuery($sSQL);

	// Delete the doc files, if they exist
	$photoThumbnail = "Images/Persembahan/thumbnails/Prsb" . $iPersembahan_ID . ".jpg";
	if (file_exists($photoThumbnail))
		unlink ($photoThumbnail);
	$photoFile = "Images/Persembahan/Prsb" . $iPersembahan_ID . ".jpg";
	if (file_exists($photoFile))
		unlink ($photoFile);

	$logvar1 = "Delete";
	$logvar2 = "Persembahan Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}

function DeletePersembahanAnak($iPersembahan_ID)
{
	// Delete the Persembahan record
	$sSQL = "DELETE FROM PersembahanAnakgkjbekti WHERE Persembahan_ID = " . $iPersembahan_ID;
	RunQuery($sSQL);

	// Delete the doc files, if they exist
	$photoThumbnail = "Images/Persembahan/thumbnails/Prsb" . $iPersembahan_ID . ".jpg";
	if (file_exists($photoThumbnail))
		unlink ($photoThumbnail);
	$photoFile = "Images/Persembahan/Prsb" . $iPersembahan_ID . ".jpg";
	if (file_exists($photoFile))
		unlink ($photoFile);

	$logvar1 = "Delete";
	$logvar2 = "Persembahan Anak Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeletePersembahanRemaja($iPersembahan_ID)
{
	// Delete the Persembahan record
	$sSQL = "DELETE FROM PersembahanRemajagkjbekti WHERE Persembahan_ID = " . $iPersembahan_ID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Persembahan Remaja Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeletePersembahanKontribusi($iPersembahan_ID)
{
	// Delete the Persembahan record
	$sSQL = "DELETE FROM PersembahanKontribusigkjbekti WHERE Persembahan_ID = " . $iPersembahan_ID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Persembahan Kontribusi Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
//Do we have deletion confirmation?
if (isset($_GET["Confirmed"]))
{
	if ($sMode == 'person')
	{
		// Delete Person
		// Make sure this person is not a user
		$sSQL = "SELECT '' FROM user_usr WHERE usr_per_ID = " . $iPersonID;
		$rsUser = RunQuery($sSQL);
		$bIsUser = (mysql_num_rows($rsUser) > 0);

		if (!$bIsUser)
		{
			DeletePerson($iPersonID);

			// Redirect back to the list
			Redirect("SelectList.php?mode=person");
		}
	}
	elseif ($sMode == 'mail')
	{
		// Delete Incoming Mail
			DeleteMail($iMailID);

			// Redirect back to the list
			Redirect("SelectList.php?mode=mail");
	
	}
	elseif ($sMode == 'mailout')
	{
		// Delete Outgoing Mail
			DeleteMailOut($iMailID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=mailout");
	
	}	
	elseif ($sMode == 'sukep')
	{
		// Delete Incoming Mail
			DeleteSuKep($iSKID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=sukep");
	
	}
	elseif ($sMode == 'pak')
	{
		// Delete PAK Data
			DeletePak($iPakID);

			// Redirect back to the list
			Redirect("SelectList.php?mode=pak");
	
	}	
	elseif ($sMode == 'aset')
	{
		// Delete Asset Data
			DeleteAset($iAssetID);

			// Redirect back to the list
			Redirect("SelectList.php?mode=aset");
	
	}	
	elseif ($sMode == 'Persembahan')
	{
		// Delete Persembahan Data
			DeletePersembahan($iPersembahan_ID);

			// Redirect back to the list
			Redirect("SelectList.php?mode=Persembahan");
	
	}
	elseif ($sMode == 'PersembahanAnak')
	{
		// Delete Persembahan Data
			DeletePersembahanAnak($iPersembahan_ID);

			// Redirect back to the list
			Redirect("SelectList.php?mode=PersembahanAnak&Kategori=Anak");
	
	}	
	elseif ($sMode == 'PersembahanRemaja')
	{
		// Delete Persembahan Data
			DeletePersembahanRemaja($iPersembahan_ID);

			// Redirect back to the list
			Redirect("SelectList.php?mode=PersembahanAnak&Kategori=Remaja");
	
	}	
	elseif ($sMode == 'PersembahanKontribusi')
	{
		// Delete Persembahan Data
			DeletePersembahanKontribusi($iPersembahan_ID);

			// Redirect back to the list
			Redirect("SelectList.php?mode=PersembahanAnak&Kategori=Kontribusi");
	
	}	
	else
	{
		// Delete Family
		// Delete all associated Notes associated with this Family record
		$sSQL = "DELETE FROM note_nte WHERE nte_fam_ID = " . $iFamilyID;
		RunQuery($sSQL);

		// Delete Family pledges
		$sSQL = "DELETE FROM pledge_plg WHERE plg_PledgeOrPayment = 'Pledge' AND plg_FamID = " . $iFamilyID;
		RunQuery($sSQL);

		// Remove family property data
		$sSQL = "SELECT pro_ID FROM property_pro WHERE pro_Class='f'";
		$rsProps = RunQuery($sSQL);

		while($aRow = mysql_fetch_row($rsProps)) {
			$sSQL = "DELETE FROM record2property_r2p WHERE r2p_pro_ID = " . $aRow[0] . " AND r2p_record_ID = " . $iFamilyID;
			RunQuery($sSQL);
		}

		if (isset($_GET["Members"]))
		{
			// Delete all persons that were in this family

			$sSQL = "SELECT per_ID FROM person_per WHERE per_fam_ID = " . $iFamilyID;
			$rsPersons = RunQuery($sSQL);
			while($aRow = mysql_fetch_row($rsPersons))
			{
				DeletePerson($aRow[0]);
			}
		}
		else
		{
			// Reset previous members' family ID to 0 (undefined)
			$sSQL = "UPDATE person_per SET per_fam_ID = 0 WHERE per_fam_ID = " . $iFamilyID;
			RunQuery($sSQL);
		}

		// Delete the specified Family record
		// Backup First
		$sSQL = "INSERT INTO family_pindah SELECT * FROM family_fam WHERE fam_ID = " . $iFamilyID;
		RunQuery($sSQL);

		$sSQL = "DELETE FROM family_fam WHERE fam_ID = " . $iFamilyID;
		RunQuery($sSQL);

		// Remove custom field data
		$sSQL = "DELETE FROM family_custom WHERE fam_ID = " . $iFamilyID;
		RunQuery($sSQL);

		// Delete the photo files, if they exist
		$photoThumbnail = "Images/Family/thumbnails/" . $iFamilyID . ".jpg";
		if (file_exists($photoThumbnail))
			unlink ($photoThumbnail);
		$photoFile = "Images/Family/" . $iFamilyID . ".jpg";
		if (file_exists($photoFile))
			unlink ($photoFile);

	$logvar1 = "Delete";
	$logvar2 = "Family Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iFamilyID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

		// Redirect back to the family listing
		Redirect("SelectList.php?mode=family");
	}
}

if($sMode == 'person')
{
	// Get the data on this person
	$sSQL = "SELECT per_FirstName, per_LastName FROM person_per WHERE per_ID = " . $iPersonID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

	// See if this person is a user
	$sSQL = "SELECT '' FROM user_usr WHERE usr_per_ID = " . $iPersonID;
	$rsUser = RunQuery($sSQL);
	$bIsUser = (mysql_num_rows($rsUser) > 0);
}
elseif($sMode == 'mail')
{
	// Get the data on this person
	$sSQL = "SELECT NomorSurat, Dari , Institusi FROM SuratMasuk WHERE MailID = " . $iMailID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'mailout')
{
	// Get the data on this person
	$sSQL = "SELECT * FROM SuratKeluar WHERE MailID = " . $iMailID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'sukep')
{
	// Get the data on this sukep
	$sSQL = "SELECT * FROM SuratKeputusan WHERE SKID = " . $iSKID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'pak')
{
	// Get the data on this pak
	$sSQL = "SELECT * FROM pakgkjbekti WHERE PakID = " . $iPakID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'aset')
{
	// Get the data on this aset
	$sSQL = "SELECT * FROM asetgkjbekti WHERE AssetID = " . $iAssetID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'Persembahan')
{
	// Get the data on this Persembahan
	//$sSQL = "SELECT * FROM Persembahangkjbekti LEFT JOIN WHERE Persembahan_ID = " . $iPersembahan_ID;
	$sSQL = "SELECT * FROM Persembahangkjbekti  a
		LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
		WHERE Persembahan_ID = " . $iPersembahan_ID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'PersembahanAnak')
{
	// Get the data on this Persembahan
	$sSQL = "SELECT * FROM PersembahanAnakgkjbekti  a
		LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
		WHERE Persembahan_ID = " . $iPersembahan_ID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'PersembahanRemaja')
{
	// Get the data on this Persembahan
	$sSQL = "SELECT * FROM PersembahanRemajagkjbekti  a
		LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
		WHERE Persembahan_ID = " . $iPersembahan_ID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'PersembahanKontribusi')
{
	// Get the data on this Persembahan
	$sSQL = "SELECT * FROM PersembahanKontribusigkjbekti  a
		LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
		WHERE Persembahan_ID = " . $iPersembahan_ID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
else
{
	//Get the family record in question
	$sSQL = "SELECT * FROM family_fam WHERE fam_ID = " . $iFamilyID;
	$rsFamily = RunQuery($sSQL);
	extract(mysql_fetch_array($rsFamily));
}

require "Include/Header.php";


if($sMode == 'person')
{

	if ($bIsUser) {
		echo "<p class=\"LargeText\">" . gettext("Maaf ,Jemaat ini berstatus sebagai USER, silahkan hapus USER dahulu melalui Administrator.") . "<br><br>";
		echo "<a href=\"PersonView.php?PersonID=" . $iPersonID . "\">" . gettext("KEMBALI ke Data Jemaat")."</a></p>";
	}
	else
	{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan dari:") . "</p>";
		echo "<p class=\"ShadedBox\">" . $per_FirstName . " " . $per_LastName . "</p>";
		echo "<BR>";
	//	echo "<p>" . gettext("Silahkan Buat Surat Pengantar terlebih dahuluu, sebelum data ini akan dihapus:") . "</p>";
	//	echo "<p><h3><a target=\"_blank\" href=\"PrintViewMove.php?PersonID=" . $iPersonID . "  \">" . gettext("Buat Surat Pindah") . "</a></h2></p>";
	//	echo "<p><h3><a target=\"_blank\" href=\"PrintViewRip.php?PersonID=" . $iPersonID . "  \">" . gettext("Buat Surat Meninggal") . "</a></h2></p>";
	//	echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDelete.php?mode=person&PersonID=" . $iPersonID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"PersonView.php?PersonID=" . $iPersonID . "\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
	}
}
elseif($sMode == 'mail')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Surat Masuk dari:") . "</p>";
		echo "<p class=\"ShadedBox\">Nomor Surat : " . $NomorSurat . ". Dari : " . $Dari . " - " . $Institusi . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDelete.php?mode=mail&MailID=" . $iMailID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"MailView.php?MailID=" . $iMailID . "\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'mailout')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Surat Keluar berikut ini:") . "</p>";
		echo "<p class=\"ShadedBox\">Nomor Surat : " . $MailID . ". Kepada : " . $Kepada . " - " . $Institusi . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDelete.php?mode=mailout&MailID=" . $iMailID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"MailOutView.php?MailID=" . $iMailID . "\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'sukep')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Surat Keputusan:") . "</p>";
								$time  = strtotime($Tanggal);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat2 =  $SKID."/MG/SK/".$NomorSurat."/".$sChurchCode."/".dec2roman($month)."/".$year;
		echo "<p class=\"ShadedBox\">Nomor Surat : " . $NomorSurat2 . ". Hal : " . $Hal . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDelete.php?mode=sukep&SKID=" . $iSKID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SuKepView.php?SKID=" . $iSKID . "\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'pak')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Pendidikan Agama Kristen:") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data : " . $PakID . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDelete.php?mode=pak&PakID=" . $iPakID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"PakView.php?PakID=" . $iPakID . "\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'aset')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Asset") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Aset ID: " . $AssetID . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDelete.php?mode=aset&AssetID=" . $iAssetID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"AsetView.php?AssetID=" . $iAssetID . "\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'Persembahan')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Persembahan") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Persembahan ID: " . $Persembahan_ID . " , Tanggal :  " . $Tanggal . " , Nama TI :  " . $NamaTI . " , Pukul :  " . $Pukul . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDelete.php?mode=Persembahan&Persembahan_ID=" . $iPersembahan_ID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"PersembahanView.php?Persembahan_ID=" . $iPersembahan_ID . "\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'PersembahanAnak')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Persembahan Anak") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Persembahan ID: " . $Persembahan_ID . " , Tanggal :  " . $Tanggal . " , Nama TI :  " . $NamaTI . " , Pukul :  " . $Pukul . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDelete.php?mode=PersembahanAnak&Persembahan_ID=" . $iPersembahan_ID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"PersembahanAnakView.php?Persembahan_ID=" . $iPersembahan_ID . "&Kategori=Anak \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'PersembahanRemaja')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Persembahan Remaja") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Persembahan ID: " . $Persembahan_ID . " , Tanggal :  " . $Tanggal . " , Nama TI :  " . $NamaTI . " , Pukul :  " . $Pukul . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDelete.php?mode=PersembahanRemaja&Persembahan_ID=" . $iPersembahan_ID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"PersembahanAnakView.php?Persembahan_ID=" . $iPersembahan_ID . "&Kategori=Remaja \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'PersembahanKontribusi')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Persembahan Kontribusi") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Persembahan ID: " . $Persembahan_ID . " , Tanggal :  " . $Tanggal . " , Nama TI :  " . $NamaTI . " , Pukul :  " . $Pukul . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDelete.php?mode=PersembahanKontribusi&Persembahan_ID=" . $iPersembahan_ID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"PersembahanAnakView.php?Persembahan_ID=" . $iPersembahan_ID . "&Kategori=Kontribusi \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
else
{
	// Delete Family Confirmation
	// See if this family has any donations
	$sSQL = "SELECT plg_plgID FROM pledge_plg WHERE plg_PledgeOrPayment = 'Payment' AND plg_FamID = " . $iFamilyID;
	$rsDonations = RunQuery($sSQL);
	$bIsDonor = (mysql_num_rows($rsDonations) > 0);
	if ($bIsDonor && !$_SESSION['bFinance']) {
		// Donations from Family. Current user not authorized for Finance
		echo "<p class=\"LargeText\">" . gettext("Sorry, there are records of donations from this family. This family may not be deleted.") . "<br><br>";
		echo "<a href=\"FamilyView.php?FamilyID=" . $iFamilyID . "\">" . gettext("Return to Family View") . "</a></p>";

	} elseif ($bIsDonor && $_SESSION['bFinance']) {
		// Donations from Family. Current user authorized for Finance.
		// Select another family to move donations to.
		echo "<p class=\"LargeText\">" . gettext("WARNING: This family has records of donations and may NOT be deleted until these donations are associated with another family.") . "</p>";
		echo "<form name=SelectFamily method=get action=SelectDelete.php>";
		echo "<div class=\"ShadedBox\">";
		echo "<div class=\"LightShadedBox\"><strong>" . gettext("Family Name:") . " $fam_Name</strong></div>";
		echo "<p>" . gettext("Please select another family with whom to associate these donations:");
		echo "<br><b>".gettext("WARNING: This action can not be undone and may have legal implications!")."</b></p>";
		echo "<input name=FamilyID value=$iFamilyID type=hidden>";
		echo "<select name=DonationFamilyID><option value=0 selected>". gettext("Unassigned") ."</option>";

		//Get Families for the drop-down
		$sSQL = "SELECT fam_ID, fam_Name, fam_Address1, fam_City, fam_State FROM family_fam ORDER BY fam_Name";
		$rsFamilies = RunQuery($sSQL);
		// Build Criteria for Head of Household
		if (!$sDirRoleHead)
			$sDirRoleHead = "1";
		$head_criteria = " per_fmr_ID = " . $sDirRoleHead;
		// If more than one role assigned to Head of Household, add OR
		$head_criteria = str_replace(",", " OR per_fmr_ID = ", $head_criteria);
		// Add Spouse to criteria
		if (intval($sDirRoleSpouse) > 0)
			$head_criteria .= " OR per_fmr_ID = $sDirRoleSpouse";
		// Build array of Head of Households and Spouses with fam_ID as the key
		$sSQL = "SELECT per_FirstName, per_fam_ID FROM person_per WHERE per_fam_ID > 0 AND (" . $head_criteria . ") ORDER BY per_fam_ID";
		$rs_head = RunQuery($sSQL);
		$aHead = "";
		while (list ($head_firstname, $head_famid) = mysql_fetch_row($rs_head)){
			if ($head_firstname && $aHead[$head_famid])
				$aHead[$head_famid] .= " & " . $head_firstname;
			elseif ($head_firstname)
				$aHead[$head_famid] = $head_firstname;
		}
		while ($aRow = mysql_fetch_array($rsFamilies)){
			extract($aRow);
			echo "<option value=\"" . $fam_ID . "\"";
			if ($fam_ID == $iFamilyID) { echo " selected"; }
			echo ">" . $fam_Name;
			if ($aHead[$fam_ID])
				echo ", " . $aHead[$fam_ID];
			if ($fam_ID == $iFamilyID)
				echo " -- " . gettext("CURRENT FAMILY WITH DONATIONS");
			else
				echo " " . FormatAddressLine($fam_Address1, $fam_City, $fam_State);
		}
		echo "</select><br><br>";
		echo "<input type=submit name=CancelFamily value=\"Cancel and Return to Family View\"> &nbsp; &nbsp; ";
		echo "<input type=submit name=MoveDonations value=\"Move Donations to Selected Family\">";
		echo "</div></form>";

		// Show payments connected with family
		// -----------------------------------
		echo "<br><br>";
		//Get the pledges for this family
		$sSQL = "SELECT plg_plgID, plg_FYID, plg_date, plg_amount, plg_schedule, plg_method,
		         plg_comment, plg_DateLastEdited, plg_PledgeOrPayment, a.per_FirstName AS EnteredFirstName, a.Per_LastName AS EnteredLastName, b.fun_Name AS fundName
				 FROM pledge_plg
				 LEFT JOIN person_per a ON plg_EditedBy = a.per_ID
				 LEFT JOIN donationfund_fun b ON plg_fundID = b.fun_ID
				 WHERE plg_famID = " . $iFamilyID . " ORDER BY pledge_plg.plg_date";
		$rsPledges = RunQuery($sSQL);
		?>
		<table cellpadding="5" cellspacing="0" width="100%">
			<tr class="TableHeader">
			<td><?php echo gettext("Type"); ?></td>
			<td><?php echo gettext("Fund"); ?></td>
			<td><?php echo gettext("Fiscal Year"); ?></td>
			<td><?php echo gettext("Date"); ?></td>
			<td><?php echo gettext("Amount"); ?></td>
			<td><?php echo gettext("Schedule"); ?></td>
			<td><?php echo gettext("Method"); ?></td>
			<td><?php echo gettext("Comment"); ?></td>
			<td><?php echo gettext("Date Updated"); ?></td>
			<td><?php echo gettext("Updated By"); ?></td>
		</tr>
		<?php
		$tog = 0;
		//Loop through all pledges
		while ($aRow =mysql_fetch_array($rsPledges)){
			$tog = (! $tog);
			$plg_FYID = "";
			$plg_date = "";
			$plg_amount = "";
			$plg_schedule = "";
			$plg_method = "";
			$plg_comment = "";
			$plg_plgID = 0;
			$plg_DateLastEdited  = "";
			$plg_EditedBy = "";
			extract($aRow);

			//Alternate the row style
			if ($tog)
				$sRowClass = "RowColorA";
			else
				$sRowClass = "RowColorB";

			if ($plg_PledgeOrPayment == 'Payment') {
				if ($tog)
					$sRowClass = "PaymentRowColorA";
				else
					$sRowClass = "PaymentRowColorB";
			}
			?>
			<tr class="<?php echo $sRowClass ?>">
				<td><?php echo $plg_PledgeOrPayment ?>&nbsp;</td>
				<td><?php echo $fundName ?>&nbsp;</td>
				<td><?php echo MakeFYString ($plg_FYID) ?>&nbsp;</td>
				<td><?php echo $plg_date ?>&nbsp;</td>
				<td><?php echo $plg_amount ?>&nbsp;</td>
				<td><?php echo $plg_schedule ?>&nbsp;</td>
				<td><?php echo $plg_method; ?>&nbsp;</td>
				<td><?php echo $plg_comment; ?>&nbsp;</td>
				<td><?php echo $plg_DateLastEdited; ?>&nbsp;</td>
				<td><?php echo $EnteredFirstName . " " . $EnteredLastName; ?>&nbsp;</td>
			</tr>
			<?php
		}
		echo "</table>";


	} else {
		// No Donations from family.  Normal delete confirmation
		echo $DonationMessage;
		echo "<p>" . gettext("Silahkan Konfirmasi Penghapusan Data Keluarga ini:") . "</p>";
		echo "<p>" . gettext("Catatan: Hal ini akan menghapus semua catatan keluarga.") . "</p>";
		echo "<div class=\"ShadedBox\">";
		echo "<div class=\"LightShadedBox\"><strong>" . gettext("Nama Keluarga:") . "</strong></div>";
		echo "&nbsp;" . $fam_Name;
		echo "</div>";
		echo "<p class=\"MediumText\"><a href=\"SelectDelete.php?Confirmed=Yes&FamilyID=" . $iFamilyID . "\">" . gettext("HAPUS Data Keluarga saja") . "</a>" . gettext(" (Perhatian! Setelah dihapus tidak bisa direcovery)") . "</p>";
		echo "<div class=\"ShadedBox\">";
		echo "<div class=\"LightShadedBox\"><strong>" . gettext("Anggota Keluarga:") . "</strong></div>";
		//List Family Members
		$sSQL = "SELECT * FROM person_per WHERE per_fam_ID = " . $iFamilyID;
		$rsPerson = RunQuery($sSQL);
		while($aRow = mysql_fetch_array($rsPerson)) {
			extract($aRow);
			echo "&nbsp;" . $per_FirstName . " " . $per_LastName . "<br>";
			RunQuery($sSQL);
		}
		echo "</div>";
		echo "<p class=\"MediumText\"><a href=\"SelectDelete.php?Confirmed=Yes&Members=Yes&FamilyID=" . $iFamilyID . "\">" . gettext("HAPUS Data Keluarga DAN Anggota Keluarga") . "</a>" . gettext(" (Perhatian! Setelah dihapus tidak bisa direcovery)") . "</p>";
		echo "<br><p class=\"LargeText\"><a href=\"FamilyView.php?FamilyID=".$iFamilyID."\">" . gettext("TIDAK, Batalkan peng-hapusan!</a>") . "</p>";
	}
}


require "Include/Footer.php";
?>
