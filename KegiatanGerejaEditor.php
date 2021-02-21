<?php
/*******************************************************************************
 *
 *  filename    : KegiatanGerejaEditor.php
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
 *  copyright   : 2013 Erwin Pratama for GKJ Klasis Jakarta Bagian Timur
 *  ChurchInfo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";

//Set the page title
$sPageTitle = gettext("Editor - Kegiatan Gereja");

//Get the KegiatanGerejaID out of the querystring
$iKegiatanGerejaID = FilterInput($_GET["KegiatanGerejaID"],'int');


ob_start();
?>

<!-- TinyMCE -->
<script type="text/javascript" src="Include/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
// General options
mode : "textareas",
theme : "advanced",
plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",
// Theme options
theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
//theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
//theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
theme_advanced_statusbar_location : "bottom",
theme_advanced_resizing : true,
// Example content CSS (should be your site CSS)
content_css : "css/content.css",
// Drop lists for link/image/media/template dialogs
template_external_list_url : "lists/template_list.js",
external_link_list_url : "lists/link_list.js",
external_image_list_url : "lists/image_list.js",
media_external_list_url : "lists/media_list.js",
// Replace values for the template plugin
template_replace_values : {
username : "Some User",
staffid : "991234"
}
});
</script>
<!-- /TinyMCE -->



<?php



// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?KegiatanGerejaID= manually)
if (strlen($iKegiatanGerejaID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsKegiatanGereja = RunQuery($sSQL);
	extract(mysql_fetch_array($rsKegiatanGereja));

	if (mysql_num_rows($rsKegiatanGereja) == 0)
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

if (isset($_POST["KegiatanGerejaSubmit"]) || isset($_POST["KegiatanGerejaSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	
	$sKegiatanGerejaID = FilterInput($_POST["KegiatanGerejaID"]);
	$sArtikel = FilterInput($_POST["Artikel"]);
	$sKomisiID = FilterInput($_POST["KomisiID"]);
	$sBidangID = FilterInput($_POST["BidangID"]);
	$sTempat = FilterInput($_POST["Tempat"]);
	$sTanggal = FilterInput($_POST["Tanggal"]);
	$sPukul = FilterInput($_POST["Pukul"]);
	$sGerejaID = FilterInput($_POST["GerejaID"]);
	$sTempatKegiatan = FilterInput($_POST["TempatKegiatan"]);
	$sNamaKegiatan = FilterInput($_POST["NamaKegiatan"]);
	$sKeterangan = FilterInput($_POST["Keterangan"]);
	$sTanggalMulai = FilterInput($_POST["TanggalMulai"]);
	$sTanggalSelesai = FilterInput($_POST["TanggalSelesai"]);
	$sJamMulai = FilterInput($_POST["JamMulai"]);
	$sJamSelesai = FilterInput($_POST["JamSelesai"]);
	$sHasil = FilterInput($_POST["Hasil"]);
	$sLaporan = FilterInput($_POST["Laporan"]);
	$sNotulaRapatID = FilterInput($_POST["NotulaRapatID"]);
	$sPiC = FilterInput($_POST["PiC"]);
	$sJmlPeserta = FilterInput($_POST["JmlPeserta"]);
	$sAnggaran = FilterInput($_POST["Anggaran"]);
	$sRealisasiAnggaran = FilterInput($_POST["RealisasiAnggaran"]);


	//Initialize the error flag
	$bErrorFlag = false;

	// Validate KegiatanGereja Date if one was entered
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
		// New KegiatanGereja (add)
		

		
		if (strlen($iKegiatanGerejaID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";
			$sSQL = "INSERT INTO KegiatanGereja ( 				
			KegiatanGerejaID ,
			AktaSidangID ,
			Artikel ,
			KomisiID ,
			BidangID ,
			Tempat ,
			Tanggal ,
			Pukul ,
			GerejaID ,
			TempatKegiatan ,
			NamaKegiatan ,
			Keterangan ,
			TanggalMulai ,
			TanggalSelesai ,
			JamMulai ,
			JamSelesai ,
			Hasil ,
			Laporan ,
			NotulaRapatID ,
			PiC ,
			JmlPeserta ,
			Anggaran ,
			RealisasiAnggaran ,
			DateEntered, 
			EnteredBy )
			
			VALUES (
			'" . $sKegiatanGerejaID . "',
			'" . $sAktaSidangID . "',
			'" . $sArtikel . "',
			'" . $sKomisiID . "',
			'" . $sBidangID . "',
			'" . $sTempat . "',
			'" . $sTanggal . "',
			'" . $sPukul . "',
			'" . $sGerejaID . "',
			'" . $sTempatKegiatan . "',
			'" . $sNamaKegiatan . "',
			'" . $sKeterangan . "',
			'" . $sTanggalMulai . "',
			'" . $sTanggalSelesai . "',
			'" . $sJamMulai . "',
			'" . $sJamSelesai . "',
			'" . $sHasil . "',
			'" . $sLaporan . "',
			'" . $sNotulaRapatID . "',
			'" . $sPiC . "',
			'" . $sJmlPeserta . "',
			'" . $sAnggaran . "',
			'" . $sRealisasiAnggaran . "',
			'" . date("YmdHis") . "',
			" . $_SESSION['iUserID'] . "
			)"	;
					 
//			$sSQL = "INSERT INTO KegiatanGereja ( KegiatanGerejaID, Tanggal, TanggalSelesai, GerejaID, NomorSurat, IsiAkta, Keterangan, DateEntered, EnteredBy) 
//			         VALUES ('" . $sKegiatanGerejaID . "','" . $sTanggal . "','" . $sTanggalSelesai . "','" . $sGerejaID . "','" . $sNomorSurat . "','" . $sIsiAkta . "','" . $sKeterangan . "','" . date("YmdHis") . "'," . $_SESSION['iUserID'] . ")";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New Incoming KegiatanGereja";

		// Existing KegiatanGereja (update)
		} else {
			
			$sSQL = "UPDATE KegiatanGereja SET 
			AktaSidangID = '" . $sAktaSidangID . "',
			Artikel = '" . $sArtikel . "',
			KomisiID = '" . $sKomisiID . "',
			BidangID = '" . $sBidangID . "',
			Tempat = '" . $sTempat . "',
			Tanggal = '" . $sTanggal . "',
			Pukul = '" . $sPukul . "',
			GerejaID = '" . $sGerejaID . "',
			TempatKegiatan = '" . $sTempatKegiatan . "',
			NamaKegiatan = '" . $sNamaKegiatan . "',
			Keterangan = '" . $sKeterangan . "',
			TanggalMulai = '" . $sTanggalMulai . "',
			TanggalSelesai = '" . $sTanggalSelesai . "',
			JamMulai = '" . $sJamMulai . "',
			JamSelesai = '" . $sJamSelesai . "',
			Hasil = '" . $sHasil . "',
			Laporan = '" . $sLaporan . "',
			NotulaRapatID = '" . $sNotulaRapatID . "',
			PiC = '" . $sPiC . "',
			JmlPeserta = '" . $sJmlPeserta . "',
			Anggaran = '" . $sAnggaran . "',
			RealisasiAnggaran = '" . $sRealisasiAnggaran . "',
			DateLastEdited = '" . date("YmdHis") . "', 
			EditedBy = '" . $_SESSION['iUserID'] ;

//			$sSQL = "UPDATE KegiatanGereja SET Tanggal = '" . $sTanggal . "',TanggalSelesai = '" . $sTanggalSelesai . "',GerejaID = '" . $sGerejaID  . "',NomorSurat = '" . $sNomorSurat  . "',
//			IsiAkta = '" . $sIsiAkta  . "',Keterangan = '" . $sKeterangan  . "',
//			DateLastEdited = '" . date("YmdHis") . "', EditedBy = '" . $_SESSION['iUserID'] ;
			
			$sSQL .= "' WHERE KegiatanGerejaID = " . $iKegiatanGerejaID;
//echo $sSQL;
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Incoming KegiatanGereja";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iKegiatanGerejaID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Update the custom KegiatanGereja fields.

		// Check for redirection to another page after saving information: (ie. KegiatanGerejaEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iKegiatanGerejaID);
		}
		else if (isset($_POST["KegiatanGerejaSubmit"]))
		{
			Redirect("SelectListApp.php?mode=KegiatanGereja&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("KegiatanGerejaEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iKegiatanGerejaID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM KegiatanGereja  WHERE KegiatanGerejaID = " . $iKegiatanGerejaID;
		$rsKegiatanGereja = RunQuery($sSQL);
		extract(mysql_fetch_array($rsKegiatanGereja));

	$sKegiatanGerejaID = $KegiatanGerejaID;
	$sAktaSidangID = $AktaSidangID;
	$sArtikel = $Artikel;
	$sKomisiID = $KomisiID;
	$sBidangID = $BidangID;
	$sTempat = $Tempat;
	$sTanggal = $Tanggal;
	$sPukul = $Pukul;
	$sGerejaID = $GerejaID;
	$sTempatKegiatan = $TempatKegiatan;
	$sNamaKegiatan = $NamaKegiatan;
	$sKeterangan = $Keterangan;
	$sTanggalMulai = $TanggalMulai;
	$sTanggalSelesai = $TanggalSelesai;
	$sJamMulai = $JamMulai;
	$sJamSelesai = $JamSelesai;
	$sHasil = $Hasil;
	$sLaporan = $Laporan;
	$sNotulaRapatID = $NotulaRapatID;
	$sPiC = $PiC;
	$sJmlPeserta = $JmlPeserta;
	$sAnggaran = $Anggaran;
	$sRealisasiAnggaran = $RealisasiAnggaran;
	
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
	}
}

//Get Gereja Names for the drop-down
$sSQL = "SELECT a.* ,b.NamaKlasis FROM DaftarGerejaGKJ a
LEFT JOIN DaftarKlasisGKJ b ON a.KlasisID = b.KlasisID 
WHERE a.KlasisID = 14 
ORDER BY a.NamaGereja";
$rsNamaGereja = RunQuery($sSQL);

//Get Gereja Names for the drop-down
$sSQL1 = "SELECT * FROM MasterBidang 
WHERE BidangID < 10 
ORDER BY BidangID";
$rsNamaBidang = RunQuery($sSQL1);

//Get Akta Sidang for the drop-down
$sSQL1 = "SELECT * FROM AktaSidang 
ORDER BY Tanggal DESC";
$rsNamaAkta = RunQuery($sSQL1);

//Get Notula Rapat for the drop-down
$sSQL1 = "SELECT * FROM NotulaRapat a
LEFT JOIN DaftarGerejaGKJ b ON a.GerejaID=b.GerejaID
ORDER BY Tanggal DESC";
$rsNotulaRapat = RunQuery($sSQL1);

require "Include/Header.php";

?>

<form method="post" action="KegiatanGerejaEditor.php?KegiatanGerejaID=<?php echo $iKegiatanGerejaID; ?>" name="KegiatanGerejaEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="KegiatanGerejaSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"KegiatanGerejaSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="KegiatanGerejaCancel" onclick="javascript:document.location='<?php if (strlen($iKegiatanGerejaID) > 0) 
{ echo "SelectListApp.php?mode=KegiatanGereja&amp;$refresh";
} else {echo "SelectListApp.php?mode=KegiatanGereja&amp;$refresh"; 
} ?>';">
		</td>
	</tr>

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> class="SmallText" align="center">
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
				<td class="LabelColumn"><?php echo gettext("Bidang Penyelenggara:"); ?></td>
				<td class="TextColumnWithBottomBorder">
					<select name="BidangID" >
						<option value="" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaBidang))
						{
							extract($aRow);

							echo "<option value=\"" . $BidangID . "\"";
							if ($sBidangID == $BidangID) { echo " selected"; }
							echo ">" . $NamaBidang;
						}
						?>
				</td>
				
				<td class="LabelColumn"><?php echo gettext("Sub Bidang:"); ?></td>
				<td class="TextColumn" ><input type="text" name="KomisiID" id="KomisiID" size="30" value="<?php echo htmlentities(stripslashes($sKomisiID),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sKomisiIDError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Reff Akta Sidang:"); ?></td>
				<td class="TextColumnWithBottomBorder">
					<select name="AktaSidangID" >
						<option value="" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaAkta))
						{
							extract($aRow);

							echo "<option value=\"" . $AktaSidangID . "\"";
							if ($sAktaSidangID == $AktaSidangID) { echo " selected"; }
							echo ">" . $NomorSurat . "," .date2Ind($Tanggal,2) ;
						}
						?>
				</td>
				
				<td class="LabelColumn"><?php echo gettext("Artikel No:"); ?></td>
				<td class="TextColumn" ><input type="text" name="Artikel" id="Artikel" size="15" value="<?php echo htmlentities(stripslashes($sArtikel),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sArtikelError ?></font></td>
				
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Tempat (Plan):"); ?></td>
				<td class="TextColumn" ><input type="text" name="Tempat" id="Tempat" size="15" value="<?php echo htmlentities(stripslashes($sTempat),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sTempatError ?></font></td>
				
				<td class="LabelColumn"><?php echo gettext("Tanggal/Pukul (Plan):"); ?></td>
				<td class="TextColumn" >		
				<input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel20" size="11">&nbsp;
				<input type="image" onclick="return showCalendar('sel20', 'y-mm-dd');" src="Images/calendar.gif"> 
				<span class="SmallText"> - </span><font color="red"><?php echo $sTanggalError ?></font>				
				<input type="text" name="Pukul" id="Pukul" size="15" value="<?php echo htmlentities(stripslashes($sPukul),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sPukulError ?></font></td>
			<tr>
				<td align="center" colspan="4" ><?php echo gettext("Realisasi Pelaksanaan Kegiatan:"); ?></td>		
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Mulai:"); ?></td>
				<td class="TextColumn"><input type="text" name="TanggalMulai" value="<?php echo $sTanggalMulai; ?>" maxlength="10" id="sel1" size="11">&nbsp;
				<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> 
				<span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalMulaiError ?></font></td>
	
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Selesai:"); ?></td>
				<td class="TextColumn"><input type="text" name="TanggalSelesai" value="<?php echo $sTanggalSelesai; ?>" maxlength="10" id="sel2" size="11">&nbsp;
				<input type="image" onclick="return showCalendar('sel2', 'y-mm-dd');" src="Images/calendar.gif"> 
				<span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalSelesaiError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Jam Mulai:"); ?></td>
				<td class="TextColumn" ><input type="text" name="JamMulai" id="JamMulai" size="15" value="<?php echo htmlentities(stripslashes($sJamMulai),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sJamMulaiError ?></font></td>
				
				<td class="LabelColumn"><?php echo gettext("Jam Selesai:"); ?></td>
				<td class="TextColumn" ><input type="text" name="JamSelesai" id="JamSelesai" size="15" value="<?php echo htmlentities(stripslashes($sJamSelesai),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sJamSelesaiError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Tempat Kegiatan:"); ?></td>
				<td class="TextColumnWithBottomBorder">
					<select name="GerejaID" >
						<option value="" selected><?php echo gettext("Ditempat Lain"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaGereja))
						{
							extract($aRow);

							echo "<option value=\"" . $GerejaID . "\"";
							if ($sGerejaID == $GerejaID) { echo " selected"; }
							echo ">" . $NamaGereja ." - " .$NamaKlasis;
						}
						?>
				</td>
				
				<td class="LabelColumn"><?php echo gettext("Tempat (jika diluar Gereja):"); ?></td>
				<td class="TextColumn" ><input type="text" name="TempatKegiatan" id="TempatKegiatan" size="30" value="<?php echo htmlentities(stripslashes($sTempatKegiatan),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sTempatKegiatanError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nama Kegiatan:"); ?></td>
				<td class="TextColumn" colspan="4"><input type="text" name="NamaKegiatan" id="NamaKegiatan" size="100" value="<?php echo htmlentities(stripslashes($sNamaKegiatan),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNamaKegiatanError ?></font></td>
			<tr>
 			<tr>
				<td class="LabelColumn"><?php echo gettext("Keterangan :"); ?></td>
				<td class="TextColumn" colspan="4"><input type="text" name="Keterangan" id="Keterangan" size="100" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sKeteranganError ?></font></td>
			<tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("PiC :"); ?></td>
				<td class="TextColumn" ><input type="text" name="PiC" id="PiC" size="40" value="<?php echo htmlentities(stripslashes($sPiC),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sPiCError ?></font></td>
			
				<td class="LabelColumn"><?php echo gettext("Jml Peserta :"); ?></td>
				<td class="TextColumn" ><input type="text" name="JmlPeserta" id="JmlPeserta" size="20" value="<?php echo htmlentities(stripslashes($sJmlPeserta),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sJmlPesertaError ?></font></td>
			<tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Anggaran :"); ?></td>
				<td class="TextColumn" >Rp.<input type="text" name="Anggaran" id="Anggaran" size="25" value="<?php echo htmlentities(stripslashes($sAnggaran),ENT_NOQUOTES, "UTF-8"); ?>">,-
				<br><font color="red"><?php echo $sAnggaranError ?></font></td>
			
				<td class="LabelColumn"><?php echo gettext("Realisasi Anggaran :"); ?></td>
				<td class="TextColumn" >Rp.<input type="text" name="RealisasiAnggaran" id="RealisasiAnggaran" size="25" value="<?php echo htmlentities(stripslashes($sRealisasiAnggaran),ENT_NOQUOTES, "UTF-8"); ?>">,-
				<br><font color="red"><?php echo $sRealisasiAnggaranError ?></font></td>
			<tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Notula Rapat :"); ?></td>
				<td class="TextColumnWithBottomBorder" colspan="3" >
					<select name="NotulaRapatID" >
						<option value="" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNotulaRapat))
						{
							extract($aRow);

							echo "<option value=\"" . $NotulaRapatID . "\"";
							if ($sNotulaRapatID == $NotulaRapatID) { echo " selected"; }
							echo ">" . $NomorSurat .". Di :". $NamaGereja .". Tgl :".date2Ind($Tanggal,2);
						}
						?>
				</td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Laporan :"); ?></td>
				<td class="TextColumn" colspan="4" >
				<textarea id="Laporan" name="Laporan" rows="15" cols="80" >
				<?php echo $sLaporan;?>
				</textarea>
				</td>
			</tr>	
			<tr>
				<td class="LabelColumn"><?php echo gettext("Tolok Ukur :"); ?></td>
				<td class="TextColumn" colspan="4"><input type="text" name="Hasil" id="Hasil" size="100" value="<?php echo htmlentities(stripslashes($sHasil),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sHasilError ?></font></td>
			
			<tr>			

	</form>

</table>

<?php
		$logvar1 = "Edit";
		$logvar2 = "KegiatanGereja Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iKegiatanGerejaID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
