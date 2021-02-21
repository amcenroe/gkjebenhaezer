<?php
/*******************************************************************************
 *
 *  filename    : SelectDeleteApp
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2012 Erwin Pratama for GKJ Bekasi Timur
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
if (!empty($_GET["AppPersonID"])) $iAppPersonID = FilterInput($_GET["AppPersonID"],'int');
if (!empty($_GET["MailID"])) $iMailID = FilterInput($_GET["MailID"],'int');
if (!empty($_GET["PakID"])) $iPakID = FilterInput($_GET["PakID"],'int');
if (!empty($_GET["AssetID"])) $iAssetID = FilterInput($_GET["AssetID"],'int');
if (!empty($_GET["SidhiID"])) $iSidhiID = FilterInput($_GET["SidhiID"],'int');
if (!empty($_GET["BaptisID"])) $iBaptisID = FilterInput($_GET["BaptisID"],'int');
if (!empty($_GET["PindahID"])) $iPindahID = FilterInput($_GET["PindahID"],'int');
if (!empty($_GET["PindahKID"])) $iPindahKID = FilterInput($_GET["PindahKID"],'int');
if (!empty($_GET["Persembahan_ID"])) $iPersembahan_ID = FilterInput($_GET["Persembahan_ID"],'int');
if (!empty($_GET["PersembahanAnak_ID"])) $iPersembahanAnak_ID = FilterInput($_GET["PersembahanAnak_ID"],'int');
if (!empty($_GET["PersembahanRemaja_ID"])) $iPersembahanRemaja_ID = FilterInput($_GET["PersembahanRemaja_ID"],'int');
if (!empty($_GET["DonationFamilyID"])) $iDonationFamilyID = FilterInput($_GET["DonationFamilyID"],'int');
if (!empty($_GET["LiturgiID"])) $iLiturgiID = FilterInput($_GET["LiturgiID"],'int');
if (!empty($_GET["PelayanFirmanID"])) $iPelayanFirmanID = FilterInput($_GET["PelayanFirmanID"],'int');
if (!empty($_GET["MeninggalID"])) $iMeninggalID = FilterInput($_GET["MeninggalID"],'int');
if (!empty($_GET["KlasID"])) $iKlasID = FilterInput($_GET["KlasID"],'int');
if (!empty($_GET["KegiatanKaryawan_ID"])) $iKegiatanKaryawan_ID = FilterInput($_GET["KegiatanKaryawan_ID"],'int');
if (!empty($_GET["PengeluaranKasKecilID"])) $iPengeluaranKasKecilID = FilterInput($_GET["PengeluaranKasKecilID"],'int');
if (!empty($_GET["PencairanCekID"])) $iPencairanCekID = FilterInput($_GET["PencairanCekID"],'int');
if (!empty($_GET["PersembahanBulananID"])) $iPersembahanBulananID = FilterInput($_GET["PersembahanBulananID"],'int');
if (!empty($_GET["PersembahanPPPGID"])) $iPersembahanPPPGID = FilterInput($_GET["PersembahanPPPGID"],'int');
if (!empty($_GET["NikahID"])) $iNikahID = FilterInput($_GET["NikahID"],'int');
if (!empty($_GET["PelayanPendukungID"])) $iPelayanPendukungID = FilterInput($_GET["PelayanPendukungID"],'int');
if (!empty($_GET["MasterAnggaranID"])) $iMasterAnggaranID = FilterInput($_GET["MasterAnggaranID"],'int');
if (!empty($_GET["PosAnggaranID"])) $iPosAnggaranID = FilterInput($_GET["PosAnggaranID"],'int');
if (!empty($_GET["BidangID"])) $iBidangID = FilterInput($_GET["BidangID"],'int');
if (!empty($_GET["KomisiID"])) $iKomisiID = FilterInput($_GET["KomisiID"],'int');
if (!empty($_GET["NotulaRapatID"])) $iNotulaRapatID = FilterInput($_GET["NotulaRapatID"],'int');
if (!empty($_GET["NotulaRapatID"])) $iNotulaRapatID = FilterInput($_GET["NotulaRapatID"],'int');
if (!empty($_GET["KodeJenis"])) $iKodeJenis = FilterInput($_GET["KodeJenis"],'int');
if (!empty($_GET["MasaBaktiMajelisID"])) $iMasaBaktiMajelisID = FilterInput($_GET["MasaBaktiMajelisID"],'int');
if (!empty($_GET["RabID"])) $iRabID = FilterInput($_GET["RabID"],'int');

if (!empty($_GET["mode"])) $sMode = $_GET["mode"];

if ($_GET["CancelFamily"]){
	Redirect("FamilyView.php?FamilyID=$iFamilyID");
	exit;
}

if ($_GET["CancelMail"]){
	Redirect("MailView.php?MailID=$iMailID");
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

if ($_GET["CancelSidhi"]){
	Redirect("SelectListApp.php?mode=Sidhi");
	exit;
}
if ($_GET["CancelBaptis"]){
	Redirect("SelectListApp.php?mode=BaptisAnak");
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
if ($_GET["CancelPersembahanRemaja"]){
	Redirect("PersembahanAnakView.php?Persembahan_ID=$iPersembahan_ID&Kategori=Remaja");
	exit;
}
if ($_GET["CancelLiturgi"]){
	Redirect("SelectListApp.php?mode=Liturgi");
	exit;
}
if ($_GET["CancelPelayanFirman"]){
	Redirect("SelectListApp.php?mode=JadwalPelayanFirman");
	exit;
}
if ($_GET["CancelPindah"]){
	Redirect("SelectListApp.php?mode=Pindah");
	exit;
}
if ($_GET["CancelPindahK"]){
	Redirect("SelectListApp.php?mode=PindahK");
	exit;
}
if ($_GET["CancelMeninggal"]){
	Redirect("SelectListApp.php?mode=Meninggal");
	exit;
}	
if ($_GET["CancelKlasSurat"]){
	Redirect("SelectListApp.php?mode=klassurat");
	exit;	
}
if ($_GET["CancelKegiatanKaryawan"]){
	Redirect("SelectListApp3.php?mode=KegiatanKaryawan");
	exit;	
}
if ($_GET["CancelPengeluaranKasKecil"]){
	Redirect("SelectListApp3.php?mode=PengeluaranKasKecil");
	exit;	
}
if ($_GET["CancelPencairanCek"]){
	Redirect("SelectListApp3.php?mode=PencairanCek");
	exit;	
}
if ($_GET["CancelPersembahanBulanan"]){
	Redirect("SelectListApp3.php?mode=PersembahanBulanan");
	exit;	
}

if ($_GET["CancelPersembahanPPPG"]){
	Redirect("SelectListApp3.php?mode=PersembahanPPPG");
	exit;	
}

if ($_GET["CancelNikah"]){
	Redirect("SelectListApp.php?mode=Nikah");
	exit;	
}

if ($_GET["CancelPenyegaranJanjiNikah"]){
	Redirect("SelectListApp.php?mode=PenyegaranJanjiNikah");
	exit;	
}

if ($_GET["CancelPelayanPendukung"]){
	Redirect("SelectListApp3.php?mode=PelayanPendukung");
	exit;	
}

if ($_GET["CancelMasterPosAnggaranThn"]){
	Redirect("SelectListApp2.php?mode=masterposanggthn");
	exit;	
}

if ($_GET["CancelMasterPosAnggaran"]){
	Redirect("SelectListApp.php?mode=masterposangg");
	exit;	
}

if ($_GET["CancelMasterBidang"]){
	Redirect("SelectListApp.php?mode=masterbidang");
	exit;	
}

if ($_GET["CancelMasterKomisi"]){
	Redirect("SelectListApp.php?mode=masterkomisi");
	exit;	
}

if ($_GET["CancelNotulaRapat"]){
	Redirect("SelectListApp2.php?mode=notularapat");
	exit;	
}

if ($_GET["CancelJenisPengeluaranPPPG"]){
	Redirect("SelectListApp.php?mode=masterpengpppg");
	exit;	
}
if ($_GET["CancelProgramDanAnggaran"]){
	Redirect("SelectListApp2.php?mode=ProgramDanAnggaran");
	exit;	
}

if ($_GET["CancelMasaBaktiMajelis"]){
	Redirect("SelectListApp3.php?mode=MasaBaktiMajelis");
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
elseif($sMode == 'Sidhi')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Permohonan Sidhi");
	$logvar1 = "Delete";
	$logvar2 = "Sidhi Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iSidhiID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'Baptis')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Permohonan Baptis");
	$logvar1 = "Delete";
	$logvar2 = "Baptis Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iBaptisID . "','" . $logvar1 . "','" . $logvar2 . "')";
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
elseif($sMode == 'Liturgi')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Liturgi");
	$logvar1 = "Delete";
	$logvar2 = "Liturgi Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iLiturgiID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'PelayanFirman')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Jadwal Pelayan Firman");
	$logvar1 = "Delete";
	$logvar2 = "Pelayan Firman Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPelayanFirmanID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
elseif($sMode == 'Pindah')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Warga Pindah");
	$logvar1 = "Delete";
	$logvar2 = "Pindah Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPindahID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
elseif($sMode == 'PindahK')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Keluarga Pindah");
	$logvar1 = "Delete";
	$logvar2 = "Pindah Keluarga Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPindahKID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
elseif($sMode == 'Meninggal')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Warga yang Meninggal");
	$logvar1 = "Delete";
	$logvar2 = "Meninggal Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPindahKID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'KlasSurat')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Klasifikasi Surat");
	$logvar1 = "Delete";
	$logvar2 = "Klasifikasi Surat Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'KegiatanKaryawan')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Kegiatan Karyawan");
	$logvar1 = "Delete";
	$logvar2 = "Kegiatan Karyawan Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
elseif($sMode == 'PengeluaranKasKecil')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Pengeluaran Kas Kecil");
	$logvar1 = "Delete";
	$logvar2 = "PengeluaranKasKecil Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
elseif($sMode == 'PencairanCek')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Pencairan Cek");
	$logvar1 = "Delete";
	$logvar2 = "PencairanCek Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'PersembahanBulanan')
	{$sPageTitle = gettext("Konfirmasi HAPUS data PersembahanBulanan");
	$logvar1 = "Delete";
	$logvar2 = "PersembahanBulanan Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	

elseif($sMode == 'PersembahanPPPG')
	{$sPageTitle = gettext("Konfirmasi HAPUS data PersembahanPPPG");
	$logvar1 = "Delete";
	$logvar2 = "PersembahanPPPG Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
	
elseif($sMode == 'Nikah')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Permohonan Nikah");
	$logvar1 = "Delete";
	$logvar2 = "Permohonan Nikah Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
	
elseif($sMode == 'PenyegaranJanjiNikah')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Permohonan Penyegaran Janji Perkawinan");
	$logvar1 = "Delete";
	$logvar2 = "Permohonan Penyegaran Janji Perkawinan Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	

elseif($sMode == 'PelayanPendukung')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Pelayan Pendukung Peribadahan");
	$logvar1 = "Delete";
	$logvar2 = "Pelayan Pendukung Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
elseif($sMode == 'MasterPosAnggaranThn')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Master Nilai Anggaran Tahunan");
	$logvar1 = "Delete";
	$logvar2 = " Master Nilai Anggaran Tahunan Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'MasterPosAnggaran')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Master Pos Anggaran");
	$logvar1 = "Delete";
	$logvar2 = " Master Pos Anggaran Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'MasterBidang')
	{$sPageTitle = gettext("Konfirmasi HAPUS data MasterBidang");
	$logvar1 = "Delete";
	$logvar2 = " Master Bidang Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'MasterKomisi')
	{$sPageTitle = gettext("Konfirmasi HAPUS data MasterKomisi");
	$logvar1 = "Delete";
	$logvar2 = " Master Komisi Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";
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
elseif($sMode == 'NotulaRapat')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Notula Rapat");
	$logvar1 = "Delete";
	$logvar2 = "Notula Rapat Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iFamilyID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
elseif($sMode == 'JenisPengeluaranPPPG')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Jenis Pengeluaran PPPG");
	$logvar1 = "Delete";
	$logvar2 = "Jenis Pengeluaran PPPG Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iFamilyID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'MasaBaktiMajelis')
	{$sPageTitle = gettext("Konfirmasi HAPUS data MasaBaktiMajelis");
	$logvar1 = "Delete";
	$logvar2 = "MasaBaktiMajelis Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iMasaBaktiMajelisID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'ProgramDanAnggaran')
	{$sPageTitle = gettext("Konfirmasi HAPUS data Daftar Program Dan Anggaran");
	$logvar1 = "Delete";
	$logvar2 = "ProgramDanAnggaran Delete Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iMasaBaktiMajelisID . "','" . $logvar1 . "','" . $logvar2 . "')";
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
function DeleteLiturgi($iLiturgiID)
{
	// Delete the Liturgi record
	$sSQL = "DELETE FROM LiturgiGKJBekti WHERE LiturgiID = " . $iLiturgiID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Liturgi Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iLiturgiID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeletePelayanFirman($iPelayanFirmanID)
{
	// Delete the Pelayan Firman record
	$sSQL = "DELETE FROM JadwalPelayanFirman WHERE PelayanFirmanID = " . $iPelayanFirmanID;

	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "PelayanFirman Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPelayanFirmanID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeletePindah($iPindahID)
{
	// Delete the Pindah record
	$sSQL = "DELETE FROM PermohonanPindahgkjbekti WHERE PindahID = " . $iPindahID;

	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Warga Pindah Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPindahID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeletePindahK($iPindahKID)
{
	// Delete the Pindah record
	$sSQL = "DELETE FROM PermohonanPindahKgkjbekti WHERE PindahKID = " . $iPindahKID;

	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Warga Pindah Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPindahKID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeleteMeninggal($iMeninggalID)
{
	// Delete the Pindah record
	$sSQL = "DELETE FROM PermohonanPemakamangkjbekti WHERE MeninggalID = " . $iMeninggalID;

	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Pemakaman Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPindahKID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
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

function DeleteSidhi($iSidhiID)
{
if (!empty($_GET["AppPersonID"])) $iAppPersonID = FilterInput($_GET["AppPersonID"],'int');
	// Delete the Baptis record
	$sSQL = "DELETE FROM sidhigkjbekti WHERE SidhiID = " . $iSidhiID;
	RunQuery($sSQL);
	
	
			// update the main database
			$sSQL2 = "UPDATE person_custom  SET 
					c2 = NULL,					
					c27 = NULL,
					c38 = NULL" ;
	
			$sSQL2 .= " WHERE per_ID = " . $iAppPersonID;
			
		 if ($iAppPersonID > 0){RunQuery($sSQL2); }else{};		
	//		RunQuery($sSQL2);
//echo $iAppPersonID, $AppPersonID;

	$logvar1 = "Delete";
	$logvar2 = "Sidhi Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iSidhiID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}

function DeleteBaptis($iBaptisID)
{
if (!empty($_GET["AppPersonID"])) $iAppPersonID = FilterInput($_GET["AppPersonID"],'int');
	// Delete the Baptis record
	$sSQL = "DELETE FROM baptisanakgkjbekti WHERE BaptisID = " . $iBaptisID;
	RunQuery($sSQL);
	
	
			// update the main database
			$sSQL2 = "UPDATE person_custom  SET 
					c1 = NULL,					
					c26 = NULL,
					c37 = NULL" ;
	
			$sSQL2 .= " WHERE per_ID = " . $iAppPersonID;
	if ($iAppPersonID > 0){RunQuery($sSQL2); }else{};
	
//echo $iAppPersonID, $AppPersonID;

	$logvar1 = "Delete";
	$logvar2 = "Baptis Data Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iBaptisID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
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

function DeleteKlasSurat($iKlasID)
{
	// Delete the Klasifikasi Surat record
	$sSQL = "DELETE FROM KlasifikasiSurat WHERE KlasID = " . $iKlasID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Data Klasifikasi Surat Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}

function DeleteKegiatanKaryawan($iKegiatanKaryawan_ID)
{
	// Delete the Kegiatan Karyawan record
	$sSQL = "DELETE FROM  Kegiatangkjbekti WHERE KegiatanKaryawan_ID = " . $iKegiatanKaryawan_ID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Kegiatan Karyawan Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}

function DeletePengeluaranKasKecil($iPengeluaranKasKecilID)
{
	// Delete the Pengeluaran Kas Kecil record
	$sSQL = "DELETE FROM  PengeluaranKasKecil WHERE PengeluaranKasKecilID = " . $iPengeluaranKasKecilID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "PengeluaranKasKecil Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}


function DeletePencairanCek($iPencairanCekID)
{
	// Delete the PencairanCek record
	$sSQL = "DELETE FROM PencairanCek WHERE PencairanCekID = " . $iPencairanCekID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "PencairanCek Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}

function DeletePersembahanBulanan($iPersembahanBulananID)
{
	// Delete the Kegiatan Karyawan record
	$sSQL = "DELETE FROM  PersembahanBulanan WHERE PersembahanBulananID = " . $iPersembahanBulananID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "PersembahanBulanan Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}

function DeletePersembahanPPPG($iPersembahanPPPGID)
{
	// Delete the PersembahanPPPG  record
	$sSQL = "DELETE FROM  PersembahanPPPG WHERE PersembahanPPPGID = " . $iPersembahanPPPGID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "PersembahanPPPG Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}

function DeleteNikah($iNikahID)
{
	// Delete the PermohonanNikahgkjbekti  record
	$sSQL = "DELETE FROM  PermohonanNikahgkjbekti WHERE NikahID = " . $iNikahID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "PermohonanNikahgkjbekti Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}

function DeletePenyegaranJanjiNikah($iNikahID)
{
	// Delete the PenyegaranJanjiNikah  record
	$sSQL = "DELETE FROM  PermohonanPenyegaranJanjiNikah WHERE NikahID = " . $iNikahID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Permohonan PenyegaranJanjiNikah Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}

function DeletePelayanPendukung($iPelayanPendukungID)
{
	// Delete the PelayanPendukung  record
	$sSQL = "DELETE FROM  JadwalPelayanPendukung WHERE PelayanPendukungID = " . $iPelayanPendukungID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Jadwal PelayanPendukung Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}

function DeleteMasterPosAnggaranThn($iMasterAnggaranID)
{
	// Delete the Master Nilai Anggaran per Tahun  record
	$sSQL = "DELETE FROM  MasterAnggaran WHERE MasterAnggaranID = " . $iMasterAnggaranID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Master Anggaran Tahunan Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeleteMasterPosAnggaran($iPosAnggaranID)
{
	// Delete the Master Pos Anggaran record
	$sSQL = "DELETE FROM  MasterPosAnggaran WHERE PosAnggaranID = " . $iPosAnggaranID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Master Pos Anggaran Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeleteMasterBidang($iBidangID)
{
	// Delete the Master Bidang record
	$sSQL = "DELETE FROM  MasterBidang WHERE BidangID = " . $iBidangID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Master Bidang Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeleteMasterKomisi($iKomisiID)
{
	// Delete the Master Komisi record
	$sSQL = "DELETE FROM  MasterKomisi WHERE KomisiID = " . $iKomisiID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Master Komisi Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeleteNotulaRapat($iNotulaRapatID)
{
	// Delete the Notula Rapat record
	$sSQL = "DELETE FROM  NotulaRapat WHERE NotulaRapatID = " . $iNotulaRapatID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "Notula Rapat Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function JenisPengeluaranPPPG($iKodeJenis)
{
	// Delete the JenisPengeluaranPPPG record
	$sSQL = "DELETE FROM  JenisPengeluaranPPPG WHERE KodeJenis = " . $iKodeJenis;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "JenisPengeluaranPPPG Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iKlasID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeleteMasaBaktiMajelis($iMasaBaktiMajelisID)
{
	// Delete the MasaBaktiMajelis record
	$sSQL = "DELETE FROM  MasaBaktiMajelis WHERE MasaBaktiMajelisID = " . $iMasaBaktiMajelisID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "MasaBaktiMajelis Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iMasaBaktiMajelisID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}
function DeleteProgramDanAnggaran($iRabID)
{
	// Delete the ProgramDanAnggaran record
	$sSQL = "DELETE FROM  ProgramDanAnggaran WHERE RabID = " . $iRabID;
	RunQuery($sSQL);

	$logvar1 = "Delete";
	$logvar2 = "ProgramDanAnggaran Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iRabID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
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
	elseif ($sMode == 'Sidhi')
	{
		// Delete Sidhi Data
			DeleteSidhi($iSidhiID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=Sidhi");
	
	}	
	elseif ($sMode == 'Baptis')
	{
		// Delete Baptis Data
			DeleteBaptis($iBaptisID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=BaptisAnak");
	
	}	
	elseif ($sMode == 'Persembahan')
	{
		// Delete Persembahan Data
			DeletePersembahan($iPersembahan_ID);

			// Redirect back to the list
			Redirect("SelectList.php?mode=Persembahan");
	
	}
	elseif ($sMode == 'Liturgi')
	{
		// Delete Liturgi Data
			DeleteLiturgi($iLiturgiID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=Liturgi");
	
	}
	elseif ($sMode == 'PelayanFirman')
	{
		// Delete PelayanFirman Data
			DeletePelayanFirman($iPelayanFirmanID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=JadwalPelayanFirman");
	
	}
	elseif ($sMode == 'Pindah')
	{
		// Delete Warga Pindah Data
			DeletePindah($iPindahID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=Pindah");
	
	}
	elseif ($sMode == 'PindahK')
	{
		// Delete Keluarga Pindah Data
			DeletePindahK($iPindahKID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=PindahK");
	
	}	
	elseif ($sMode == 'Meninggal')
	{
		// Delete Data Meninggal
			DeleteMeninggal($iMeninggalID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=Meninggal");
	
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
	elseif ($sMode == 'KlasSurat')
	{
		// Delete Data Klasifikasi Surat
			DeleteKlasSurat($iKlasID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=klassurat");
	
	}
	elseif ($sMode == 'KegiatanKaryawan')
	{
		// Delete Data Kegiatan Karyawan
			DeleteKegiatanKaryawan($iKegiatanKaryawan_ID);

			// Redirect back to the list
			Redirect("SelectListApp3.php?mode=KegiatanKaryawan");
	
	}	
	elseif ($sMode == 'PengeluaranKasKecil')
	{
		// Delete Data PengeluaranKasKecil
			DeletePengeluaranKasKecil($iPengeluaranKasKecilID);

			// Redirect back to the list
			Redirect("SelectListApp3.php?mode=PengeluaranKasKecil");
	
	}

	elseif ($sMode == 'PencairanCek')
	{
		// Delete Data PencairanCek
			DeletePencairanCek($iPencairanCekID);

			// Redirect back to the list
			Redirect("SelectListApp3.php?mode=PencairanCek");
	
	}
	elseif ($sMode == 'PersembahanBulanan')
	{
		// Delete Data PersembahanBulanan
			DeletePersembahanBulanan($iPersembahanBulananID);

			// Redirect back to the list
			Redirect("SelectListApp3.php?mode=PersembahanBulanan");
	
	}
	elseif ($sMode == 'PelayanPendukung')
	{
		// Delete Data PelayanPendukung
			DeletePelayanPendukung($iPelayanPendukungID);

			// Redirect back to the list
			Redirect("SelectListApp3.php?mode=PelayanPendukung");
	
	}
	
	elseif ($sMode == 'PersembahanPPPG')
	{
		// Delete Data PersembahanPPPG
			DeletePersembahanPPPG($iPersembahanPPPGID);

			// Redirect back to the list
			Redirect("SelectListApp3.php?mode=PersembahanPPPG");
	
	}

	elseif ($sMode == 'MasterPosAnggaranThn')
	{
		// Delete Data Master Nilai Anggaran
			DeleteMasterPosAnggaranThn($iMasterAnggaranID);

			// Redirect back to the list
			Redirect("SelectListApp2.php?mode=masterposanggthn");
	
	}
	
	elseif ($sMode == 'MasterPosAnggaran')
	{
		// Delete Data Master Pos Anggaran
			DeleteMasterPosAnggaran($iPosAnggaranID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=masterposangg");
	
	}
	
	elseif ($sMode == 'MasterBidang')
	{
		// Delete Data Master Bidang
			DeleteMasterBidang($iBidangID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=masterbidang");
	
	}
	
	elseif ($sMode == 'MasterKomisi')
	{
		// Delete Data Master Komisi
			DeleteMasterBidang($iKomisiID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=masterkomisi");
	
	}
	
	elseif ($sMode == 'Nikah')
	{
		// Delete Data Nikah
			DeleteNikah($iNikahID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=Nikah");
	
	}	
	
	elseif ($sMode == 'PenyegaranJanjiNikah')
	{
		// Delete Data Penyegaran Janji Nikah
			DeletePenyegaranJanjiNikah($iNikahID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=PenyegaranJanjiNikah");
	
	}	
	elseif ($sMode == 'NotulaRapat')
	{
		// Delete Data NotulaRapat
			DeleteNotulaRapat($iNotulaRapatID);

			// Redirect back to the list
			Redirect("SelectListApp2.php?mode=notularapat");
	
	}	
	elseif ($sMode == 'JenisPengeluaranPPPG')
	{
		// Delete Data JenisPengeluaranPPPG
			JenisPengeluaranPPPG($iKodeJenis);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=masterpengpppg");
	
	}		
	
	elseif ($sMode == 'MasaBaktiMajelis')
	{
		// Delete Data MasaBaktiMajelis
			DeleteMasaBaktiMajelis($iMasaBaktiMajelisID);

			// Redirect back to the list
			Redirect("SelectListApp3.php?mode=MasaBaktiMajelis");
	
	}
	elseif ($sMode == 'ProgramDanAnggaran')
	{
		// Delete Data ProgramDanAnggaran
			DeleteProgramDanAnggaran($iRabID);

			// Redirect back to the list
			Redirect("SelectListApp2.php?mode=ProgramDanAnggaran");
	
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
elseif($sMode == 'Sidhi')
{
	// Get the data on this aset
	$sSQL = "SELECT * FROM sidhigkjbekti WHERE SidhiID = " . $iSidhiID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'Liturgi')
{
	// Get the data on this aset
	$sSQL = "SELECT * FROM LiturgiGKJBekti WHERE LiturgiID = " . $iLiturgiID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'PelayanFirman')
{
	// Get the data on this aset
	$sSQL = "SELECT * FROM JadwalPelayanFirman WHERE PelayanFirmanID = " . $iPelayanFirmanID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'Pindah')
{
	// Get the data on this aset
	$sSQL = "SELECT * FROM PermohonanPindahgkjbekti WHERE PindahID = " . $iPindahID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'PindahK')
{
	// Get the data on this aset
	$sSQL = "SELECT * FROM PermohonanPindahKgkjbekti WHERE PindahKID = " . $iPindahKID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'Meninggal')
{
	// Get the data on this aset
	$sSQL = "SELECT * FROM PermohonanPemakamangkjbekti WHERE MeninggalID = " . $iMeninggalID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'Baptis')
{
	// Get the data on this aset
	$sSQL = "SELECT * FROM baptisanakgkjbekti WHERE BaptisID = " . $iBaptisID;
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
elseif($sMode == 'KlasSurat')
{
	// Get the data on this Persembahan
	$sSQL = "SELECT * FROM KlasifikasiSurat  
		WHERE KlasID = " . $iKlasID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'KegiatanKaryawan')
{
	// Get the data on this Persembahan
	$sSQL = "select a.*, b.per_FirstName as NamaKaryawan from Kegiatangkjbekti a
	   LEFT JOIN person_per b ON a.KaryawanID = b.per_ID
		WHERE KegiatanKaryawan_ID = " . $iKegiatanKaryawan_ID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'PengeluaranKasKecil')
{
	// Get the data on this Persembahan
	$sSQL = "select a.*, b.*, c.*, d.* FROM PengeluaranKasKecil a 
		LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
		LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
		LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID 
		WHERE PengeluaranKasKecilID = " . $iPengeluaranKasKecilID;
	$rsPengeluaranKasKecil = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPengeluaranKasKecil));

}

elseif($sMode == 'PencairanCek')
{
	// Get the data on this PencairanCek
	$sSQL = "select * FROM PencairanCek 
		WHERE PencairanCekID = " . $iPencairanCekID;
	$rsPencairanCek = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPencairanCek));

}
elseif($sMode == 'PersembahanBulanan')
{
	// Get the data on this Persembahan
	$sSQL = "select a.*, b.* from PersembahanBulanan a
	   LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
		WHERE PersembahanBulananID = " . $iPersembahanBulananID;
	$rsPersembahanBulanan = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPersembahanBulanan));

}

elseif($sMode == 'PersembahanPPPG')
{
	// Get the data on this PersembahanPPPG
	$sSQL = "select a.*, b.* from PersembahanPPPG a
	   LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
		WHERE PersembahanPPPGID = " . $iPersembahanPPPGID;
	$rsPersembahanPPPG = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPersembahanPPPG));

}

elseif($sMode == 'Nikah')
{
	// Get the data on this Nikah
	$sSQL = "select a.*, b.* from PermohonanNikahgkjbekti a
	   LEFT JOIN LokasiTI b ON a.TempatNikah = b.KodeTI
		WHERE NikahID = " . $iNikahID;
	$rsNikah = RunQuery($sSQL);
	extract(mysql_fetch_array($rsNikah));

}

elseif($sMode == 'PenyegaranJanjiNikah')
{
	// Get the data on this Nikah
	$sSQL = "select a.*, b.*, c.* from PermohonanPenyegaranJanjiNikah a
	   LEFT JOIN LokasiTI b ON a.TempatNikah = b.KodeTI
	   LEFT JOIN family_fam c ON a.fam_ID = c.fam_ID
		WHERE NikahID = " . $iNikahID;
	$rsPenyegaranJanjiNikah = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPenyegaranJanjiNikah));

}

elseif($sMode == 'PelayanPendukung')
{
	// Get the data on this PelayanPendukung
	$sSQL = "select a.*, b.* from JadwalPelayanPendukung a
	   LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
		WHERE PelayanPendukungID = " . $iPelayanPendukungID;
		
	$rsPelayanPendukung = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPelayanPendukung));

}

elseif($sMode == 'MasterPosAnggaranThn')
{
	// Get the data on this MasterAnggaranThn
$sSQL = "SELECT a.*, b.*, c.* FROM MasterAnggaran a
		LEFT JOIN MasterKomisi b ON a.KomisiID=b.KomisiID
		LEFT JOIN MasterBidang c ON b.BidangID=c.BidangID
		WHERE MasterAnggaranID = " . $iMasterAnggaranID;
		
	$rsMasterPosAnggaranThn = RunQuery($sSQL);
	extract(mysql_fetch_array($rsMasterPosAnggaranThn));

}

elseif($sMode == 'MasterPosAnggaran')
{
	// Get the data on this MasterPosAnggaran
$sSQL = "SELECT a.*, b.*, c.* FROM MasterPosAnggaran a
		LEFT JOIN MasterKomisi b ON a.KomisiID=b.KomisiID
		LEFT JOIN MasterBidang c ON b.BidangID=c.BidangID
		WHERE PosAnggaranID = " . $iPosAnggaranID;
		
	$rsMasterPosAnggaran = RunQuery($sSQL);
	extract(mysql_fetch_array($rsMasterPosAnggaran));
}

elseif($sMode == 'MasterKomisi')
{
	// Get the data on this MasterKomisi
$sSQL = "SELECT a.*, c.* FROM MasterKomisi a
		LEFT JOIN MasterBidang c ON a.BidangID=c.BidangID
		WHERE KomisiID = " . $iKomisiID;
		
	$rsMasterKomisi = RunQuery($sSQL);
	extract(mysql_fetch_array($rsMasterKomisi));
}

elseif($sMode == 'MasterBidang')
{
	// Get the data on this MasterBidang
$sSQL = "SELECT a.*, b.* FROM MasterBidang a
		LEFT JOIN MasterOrganisasi b ON a.Kelompok=b.OrganisasiID
		WHERE BidangID = " . $iBidangID;
		
	$rsMasterBidang = RunQuery($sSQL);
	extract(mysql_fetch_array($rsMasterBidang));

}
elseif($sMode == 'NotulaRapat')
{
	// Get the data on this NotulaRapat
$sSQL = "SELECT a.*, b.* FROM NotulaRapat a
		LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
		WHERE NotulaRapatID = " . $iNotulaRapatID;
		
	$rsNotulaRapat = RunQuery($sSQL);
	extract(mysql_fetch_array($rsNotulaRapat));
}
elseif($sMode == 'JenisPengeluaranPPPG')
{
	// Get the data on this JenisPengeluaranPPPG
$sSQL = "SELECT * FROM JenisPengeluaranPPPG	WHERE KodeJenis = " . $iKodeJenis;
		
	$rsJenisPengeluaranPPPG = RunQuery($sSQL);
	extract(mysql_fetch_array($rsJenisPengeluaranPPPG));
}
elseif($sMode == 'MasaBaktiMajelis')
{
	// Get the data on this MasaBaktiMajelis
       $sSQL = "select a.*, b.*, c.* , d.*
	   FROM MasaBaktiMajelis	a
			LEFT JOIN person_per b ON a.per_ID = b.per_ID
			LEFT JOIN volunteeropportunity_vol c ON a.vol_ID = c.vol_ID
			LEFT JOIN NotulaRapat d ON a.TglKeputusan = d.Tanggal
			
		 WHERE MasaBaktiMajelisID = " . $iMasaBaktiMajelisID;
		
	$rsMasaBaktiMajelis = RunQuery($sSQL);
	extract(mysql_fetch_array($rsMasaBaktiMajelis));
}
elseif($sMode == 'ProgramDanAnggaran')
{
	// Get the data on this ProgramDanAnggaran
        $sSQL = "SELECT a.*, b.*, c.* FROM ProgramDanAnggaran a
		LEFT JOIN MasterKomisi b ON a.KomisiID=b.KomisiID
		LEFT JOIN MasterBidang c ON b.BidangID=c.BidangID

		 WHERE RabID = " . $iRabID;
		
	$rsProgramDanAnggaran = RunQuery($sSQL);
	extract(mysql_fetch_array($rsProgramDanAnggaran));
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
		echo "<p>" . gettext("Silahkan Buat Surat Pengantar terlebih dahuluu, sebelum data ini akan dihapus:") . "</p>";
		echo "<p><h3><a target=\"_blank\" href=\"PrintViewMove.php?PersonID=" . $iPersonID . "  \">" . gettext("Buat Surat Pindah") . "</a></h2></p>";
		echo "<p><h3><a target=\"_blank\" href=\"PrintViewRip.php?PersonID=" . $iPersonID . "  \">" . gettext("Buat Surat Meninggal") . "</a></h2></p>";
		echo "<BR>";
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
elseif($sMode == 'Sidhi')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Permohonan Sidhi") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Permohonan Sidhi, ID: " . $SidhiID . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?AppPersonID=" . $iAppPersonID . "&mode=Sidhi&SidhiID=" . $iSidhiID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?SidhiID=" . $iSidhiID . "\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'Baptis')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Permohonan Baptis") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Permohonan Baptis, ID: " . $BaptisID . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?AppPersonID=" . $iAppPersonID . "&mode=Baptis&BaptisID=" . $iBaptisID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?BaptisID=" . $iBaptisID . "\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'Liturgi')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Jadwal Liturgi") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Jadwal Liturgi, ID: " . $LiturgiID . ",Tanggal:" .$Tanggal.",Tema:".$Tema."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?AppPersonID=" . $iAppPersonID . "&mode=Liturgi&LiturgiID=" . $iLiturgiID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=Liturgi\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'PelayanFirman')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Jadwal PelayanFirman") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Jadwal Pelayan Firman, ID: " . $PelayanFirmanID . ",Tanggal:" .$TanggalPF.",kode PF :".$PelayanFirman."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=PelayanFirman&PelayanFirmanID=" . $iPelayanFirmanID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=JadwalPelayanFirman\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'Pindah')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Warga Pindah") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Warga Pindah, ID: " . $PindahID . ",Tanggal:" .$TanggalPindah.",kode PF :".$Nama."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=Pindah&PindahID=" . $iPindahID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=Pindah\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
}
elseif($sMode == 'PindahK')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Keluarga Pindah") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Keluarga Pindah, ID: " . $PindahKID . ",Tanggal:" .$TanggalPindah.",kode PF :".$Nama."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=PindahK&PindahKID=" . $iPindahKID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=PindahK\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
}
elseif($sMode == 'Meninggal')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Permohonan Pelayanan Warga Meninggal") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Warga yang meninggal, ID: " . $MeninggalID . ",Tanggal:" .$TanggalMeninggal.",kode Warga :".$per_ID."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=Meninggal&MeninggalID=" . $iMeninggalID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=Meninggal\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
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
elseif($sMode == 'KlasSurat')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Klasifikasi Surat: $KlasID - $Deskripsi - $Keterangan") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Data Klasifikasi Surat, ID: " . $KlasID . " , Deskripsi :  " . $Deskripsi . " , Keterangan :  " . $Keterangan . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=KlasSurat&KlasID=" . $iKlasID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=klassurat \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'KegiatanKaryawan')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Kegiatan Karyawan: $KegiatanKaryawan_ID - $NamaKaryawan - $Tanggal") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Kegiatan ID " . $KegiatanKaryawan_ID . " , Nama Karyawan :  " . $NamaKaryawan . " , Tanggal Kegiatan :  " . $Tanggal . " ,Kegiatan : ".$NamaKegiatan."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=KegiatanKaryawan&KegiatanKaryawan_ID=" . $iKegiatanKaryawan_ID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp3.php?mode=KegiatanKaryawan \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'PengeluaranKasKecil')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data PengeluaranKasKecil:  $PengeluaranKasKecilID - Bidang: $NamaBidang - Komisi : $NamaKomisi - Pos Anggaran : $NamaPosAnggaran ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Kegiatan ID " . $PengeluaranKasKecilID . " , Deskripsi :  " . $DeskripsiKas . " , Tanggal :  " . $Tanggal . " , Jumlah : Rp".$Jumlah .", Keterangan : ".$Keterangan ."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=PengeluaranKasKecil&PengeluaranKasKecilID=" . $iPengeluaranKasKecilID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp3.php?mode=PengeluaranKasKecil \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}

elseif($sMode == 'PencairanCek')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data PencairanCek:  $PencairanCekID ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail PencairanCek ID " . $PencairanCekID . " , Deskripsi :  " . $NomorCek . " , Tanggal :  " . $Tanggal . " , Jumlah : Rp".$Jumlah .", Keterangan : ".$Keterangan ."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=PencairanCek&PencairanCekID=" . $iPencairanCekID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp3.php?mode=PencairanCek \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}
elseif($sMode == 'PersembahanBulanan')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data PersembahanBulanan: $PersembahanBulananID - $Tanggal - $NamaTI - $Pukul ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Persembahan ID " . $PersembahanID . " , Kelompok :  " . $Kelompok . " , Nomor Kartu :  " . $NomorKartu . " ,Kode Nama : ".$KodeNama."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=PersembahanBulanan&PersembahanBulananID=" . $iPersembahanBulananID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp3.php?mode=PersembahanBulanan \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}

elseif($sMode == 'PersembahanPPPG')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data PersembahanPPPG: $PersembahanPPPGnID - $Tanggal - $NamaTI - $Pukul ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail PersembahanPPPG ID " . $PersembahanPPPGID . " , Kelompok :  " . $Kelompok . " , Nomor Kartu :  " . $NomorKartu . " ,Kode Nama : ".$KodeNama."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=PersembahanPPPG&PersembahanPPPGID=" . $iPersembahanPPPGID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp3.php?mode=PersembahanPPPG \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}

elseif($sMode == 'Nikah')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Permohonan Nikah : $NikahID - $TanggalNikah - $NamaTI - $WaktuNikah ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Nikah ID " . $NikahID . " , Kode Pemohon :  " . $per_ID_L."-".$NamaLengkapL . " dan " . $per_ID_P ."-".$NamaLengkapP . " </p>";
		echo "<p class=\"ShadedBox\">Tanggal Nikah :  " . date2Ind($TanggalNikah,4)." - Pukul ".$WaktuNikah . " di " . $NamaGereja ." </p>";

		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=Nikah&NikahID=" . $iNikahID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=Nikah \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}

elseif($sMode == 'PenyegaranJanjiNikah')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Permohonan Pelayanan Pembaruan dan Penyegaran Janji Perkawinan : $NikahID - $TanggalNikah - $NamaTI - $WaktuNikah ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Nikah ID " . $NikahID . " , Kode Keluarga :  " . $fam_ID . " Nama Keluarga : ".$fam_Name."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=PenyegaranJanjiNikah&NikahID=" . $iNikahID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=PenyegaranJanjiNikah \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}

elseif($sMode == 'PelayanPendukung')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Pelayanan Pendukung Peribadahan : $PelayanPendukungID - Tgl: ".date2Ind($Tanggal,1)." - Waktu: $Waktu - Tempat: $NamaTI  ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Pelayan PendukungID " . $PelayanPendukungID . " </p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=PelayanPendukung&PelayanPendukungID=" . $iPelayanPendukungID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp3.php?mode=PelayanPendukung \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";

}

elseif($sMode == 'MasterPosAnggaranThn')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Master Nilai Anggaran Tahunan - Tahun: $TahunAnggaran - Bidang: $NamaBidang - Komisi: $NamaKomisi - Budget: $Budget ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail MasterAnggaranID " . $MasterAnggaranID . " </p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=MasterPosAnggaranThn&MasterAnggaranID=" . $MasterAnggaranID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp2.php?mode=masterposanggthn \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
}

elseif($sMode == 'MasterPosAnggaran')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Master Pos Anggaran: $NamaPosAnggaran - Bidang: $NamaBidang - Komisi: $NamaKomisi - Keterangan: $Keterangan ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail PosAnggaranID " . $PosAnggaranID . " - " .$NamaPosAnggaran."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=MasterPosAnggaran&PosAnggaranID=" . $PosAnggaranID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=masterposangg \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
}

elseif($sMode == 'MasterKomisi')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Master Komisi: $KomisiID - Bidang: $NamaBidang - Komisi: $NamaKomisi - Keterangan: $Keterangan ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail KomisiID " . $KomisiID . " - " .$NamaKomisi."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=MasterKomisi&KomisiID=" . $KomisiID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=masterkomisi \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
}

elseif($sMode == 'MasterBidang')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Master Bidang: $BidangID - Bidang: $NamaBidang - Keterangan: $Keterangan ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail BidangID " . $BidangID . " - " .$NamaBidang."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=MasterBidang&BidangID=" . $BidangID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=masterbidang \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
}

elseif($sMode == 'NotulaRapat')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Notula Rapat : $NotulaRapatID - Tanggal: ".date2Ind($Tanggal,2)." - di: $NamaTI ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail NotulaRapat " . $NomorSurat . " - " .$Keterangan."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=NotulaRapat&NotulaRapatID=" . $NotulaRapatID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp2.php?mode=notularapat \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
}

elseif($sMode == 'JenisPengeluaranPPPG')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan Data Jenis Pengeluaran PPPG  : $KodeJenis - Jenis : $NamaJenis - Keterangan: $Keterangan ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail JenisPengeluaranPPPG " . $NamaJenis . " - " .$Keterangan."</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=JenisPengeluaranPPPG&KodeJenis=" . $KodeJenis . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=masterpengpppg \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
}
elseif($sMode == 'MasaBaktiMajelis')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Masa Bakti Majelis  : $iMasaBaktiMajelisID ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail MasaBaktiMajelis " . $per_FirstName . " - " .$vol_Name. " - " . $Kategorial."</p>";
		echo "<p class=\"ShadedBox\">Tanggal Keputusan " . $TglKeputusan . "</p>";
		echo "<p class=\"ShadedBox\">Tanggal Peneguhan " . $TglPeneguhan . "</p>";
		echo "<p class=\"ShadedBox\">Tanggal Akhir " . $TglAkhir . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=MasaBaktiMajelis&MasaBaktiMajelisID=" . $MasaBaktiMajelisID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp3.php?mode=MasaBaktiMajelis \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
}
elseif($sMode == 'ProgramDanAnggaran')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Program Dan Anggaran  : $iRabID ") . "</p>";
		echo "<p class=\"ShadedBox\">Detail Program Dan Anggaran : Tahun " . $Tahun . " - Komisi: " .$NamaKomisi. " - Bidang: " . $NamaBidang."</p>";
		echo "<p class=\"ShadedBox\">Program : " . $Program . "</p>";
		echo "<p class=\"ShadedBox\">Kegiatan : " . $Kegiatan . "</p>";
		echo "<p class=\"ShadedBox\">Jadwal : " . $Jadwal . "</p>";
		echo "<p class=\"ShadedBox\">Anggaran Kas Jemaat : " . $AggKasJemaat . "</p>";
		echo "<p class=\"ShadedBox\">Anggaran Lain Lain : " . $AggLainLain . "</p>";
		echo "<p class=\"ShadedBox\">Keterangan : " . $Keterangan . "</p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDeleteApp.php?mode=ProgramDanAnggaran&RabID=" . $RabID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp2.php?mode=ProgramDanAnggaran \">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
}
//require "Include/Footer.php";
?>
