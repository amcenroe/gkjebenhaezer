<?php
/*******************************************************************************
 *
 *  filename    : MailEditor.php
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
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
$sPageTitle = gettext("Editor - Surat Masuk");

//Get the MailID out of the querystring
$iMailID = FilterInput($_GET["MailID"],'int');
$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;

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
// Clean error handling: (such as somebody typing an incorrect URL ?MailID= manually)
if (strlen($iMailID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsmail = RunQuery($sSQL);
	extract(mysql_fetch_array($rsmail));

	if (mysql_num_rows($rsmail) == 0)
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

if (isset($_POST["MailSubmit"]) || isset($_POST["MailSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sMailID = FilterInput($_POST["MailID"]);
	$sTanggal = FilterInput($_POST["Tanggal"]);
	$sUrgensi = FilterInput($_POST["Urgensi"]);
	$sVia = FilterInput($_POST["Via"]);
	$sDari = FilterInput($_POST["Dari"]);
	$sInstitusi = FilterInput($_POST["Institusi"]);
	$sKepada = FilterInput($_POST["Kepada"]);
	$sNomorSurat = FilterInput($_POST["NomorSurat"]);
	$sHal = FilterInput($_POST["Hal"]);
	$sLampiran = FilterInput($_POST["Lampiran"]);
	$sTypeLampiran = FilterInput($_POST["TypeLampiran"]);
	$sKeterangan = FilterInput($_POST["Keterangan"]);
	$sFollowUp = FilterInput($_POST["FollowUp"]);
	$sBidang1 = FilterInput($_POST["Bidang1"]);
	$sBidang2 = FilterInput($_POST["Bidang2"]);	
	$sDisposisiTgl = FilterInput($_POST["DisposisiTgl"]);	
	$sDisposisiOleh = FilterInput($_POST["sDisposisiOleh"]);		
	$sStatus = FilterInput($_POST["Status"]);
	$sKet1 = FilterInput($_POST["Ket1"]);
	$sKet2 = FilterInput($_POST["Ket2"]);
	$sKet3 = FilterInput($_POST["Ket3"]);
	$sRespon = FilterInput($_POST["Respon"]);
	$sTglRespon = FilterInput($_POST["TglRespon"]);
	$sIsiSuratBalasan = $_POST["IsiSuratBalasan"];
	$sTglDibalas = FilterInput($_POST["TglDibalas"]);

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
		// New mail (add)
		if (strlen($iMailID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO SuratMasuk ( MailID, Tanggal, Urgensi, Via, Dari, Institusi, Kepada, NomorSurat, Hal, Lampiran, TypeLampiran, Keterangan, FollowUp, Bidang1, Bidang2, DisposisiTgl, DisposisiOleh, Status, Ket1, Ket2, Ket3, Respon, TglRespon, DateEntered, EnteredBy) 
			         VALUES ('" . $sMailID . "','" . $sTanggal . "','" . $sUrgensi . "','" . $sVia . "','" . $sDari . "','" . $sInstitusi . "','" . $sKepada . "','" . $sNomorSurat . "','" . $sHal . "','" . $sLampiran . "','" . $sTypeLampiran . "'
					 ,'" . $sKeterangan . "','" . $sFollowUp . "','" . $sBidang1 . "','" . $sBidang2 . "','" . $sDisposisiTgl . "','" . $sDisposisiOleh . "','" . $sStatus . "','" . $sKet1 . "','" . $sKet2 . "','" . $sKet3 . "','" . $sRespon . "','" . $sTglRespon . "','" . date("YmdHis") . "'," . $_SESSION['iUserID'] . ")";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New Incoming Mail";

		// Existing mail (update)
		} else {

			$sSQL = "UPDATE SuratMasuk SET Tanggal = '" . $sTanggal . "',Urgensi = '" . $sUrgensi  . "',Via = '" . $sVia  . "',Dari = '" . $sDari  . "',
			Institusi = '" . $sInstitusi  . "',Kepada = '" . $sKepada  . "',NomorSurat = '" . $sNomorSurat  . "',Hal = '" . $sHal  . "',
			Lampiran = '" . $sLampiran  . "',TypeLampiran = '" . $sTypeLampiran  . "',Keterangan = '" . $sKeterangan  . "',
			FollowUp = '" . $sFollowUp  . "',Bidang1 = '" . $sBidang1  . "',Bidang2 = '" . $sBidang2  . "',DisposisiTgl = '" . $sDisposisiTgl  . "',DisposisiOleh = '" . $sDisposisiOleh  . "',
			Status = '" . $sStatus  . "',Ket1 = '" . $sKet1  . "',Ket2 = '" . $sKet2  . "',
			Ket3 = '" . $sKet3 . "' ,Respon = '" . $sRespon . "' ,TglRespon = '" . $sTglRespon . "' ,IsiSuratBalasan = '" . $sIsiSuratBalasan . "' ,TglDibalas = '" . $sTglDibalas . "' ,
			DateLastEdited = '" . date("YmdHis") . "', EditedBy = '" . $_SESSION['iUserID'] ;
			
			$sSQL .= "' WHERE MailID = " . $iMailID;
//echo $sSQL;
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Incoming Mail";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Update the custom mail fields.

		// Check for redirection to another page after saving information: (ie. MailEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iMailID);
		}
		else if (isset($_POST["MailSubmit"]))
		{
			Redirect("SelectListApp.php?mode=mail&amp;GID=$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("MailEditor.php?&amp;GID=$refresh");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iMailID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM SuratMasuk  WHERE MailID = " . $iMailID;
		$rsmail = RunQuery($sSQL);
		extract(mysql_fetch_array($rsmail));

	$sMailID = $MailID;
	$sTanggal = $Tanggal;
	$sUrgensi = $Urgensi;
	$sVia = $Via;
	$sDari = $Dari;
	$sInstitusi = $Institusi;
	$sKepada = $Kepada;
	$sNomorSurat = $NomorSurat;
	$sHal = $Hal;
	$sLampiran = $Lampiran;
	$sTypeLampiran = $TypeLampiran;
	$sKeterangan = $Keterangan;
	$sFollowUp = $FollowUp;
	$sBidang1 = $Bidang1;
	$sBidang2 = $Bidang2;	
	$sDisposisiTgl = $DisposisiTgl;
	$sDisposisiOleh = $DisposisiOleh;
	$sStatus = $Status;
	$sKet1 = $Ket1;
	$sKet2 = $Ket2;
	$sKet3 = $Ket3;
	$sRespon = $Respon;
	$sTglRespon = $TglRespon;
	$sIsiSuratBalasan = $IsiSuratBalasan;
	$sTglDibalas = $TglDibalas;

	
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
	}
}


//Get Bidang 1 Names for the drop-down
$sSQL1 = "select vol_id as Bidang, a.per_FirstName AS Nama,
fam_homephone as TelpRumah, c13 as TelpKantor, per_cellphone as Handphone, per_email as Email, vol_name as Jabatan, per_workphone as Kelompok
from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
where a.per_id = b.per_id AND
a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id
AND ( vol_id > 60 AND vol_id < 200 )
ORDER by vol_id, per_workphone, per_firstname";
$rsBidang1 = RunQuery($sSQL1);

//Get Bidang 2 Names for the drop-down
$sSQL2 = "select vol_id as Bidang, a.per_FirstName AS Nama,
fam_homephone as TelpRumah, c13 as TelpKantor, per_cellphone as Handphone, per_email as Email, vol_name as Jabatan, per_workphone as Kelompok
from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
where a.per_id = b.per_id AND
a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id
AND ( vol_id > 60 AND vol_id < 200 )
ORDER by vol_id, per_workphone, per_firstname";
$rsBidang2 = RunQuery($sSQL2);

//Get Kategori Surat Names for the drop-down
$sSQL = "select * from KlasifikasiSurat order by KlasID";
$rsKlasSurat = RunQuery($sSQL);

require "Include/Header.php";

?>

<form method="post" action="MailEditor.php?MailID=<?php echo $iMailID; ?>" name="MailEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="MailSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"MailSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="mailCancel" onclick="javascript:document.location='<?php if (strlen($iMailID) > 0) 
{ echo "MailView.php?MailID=" . $iMailID."&amp;GID=$refresh";  
} else {echo "SelectListApp.php?mode=mail&amp;GID=$refresh"; 
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
				<td colspan="2" align="center"><h3><?php echo gettext("Data Standar"); ?></h3></td>
			</tr>


	
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Surat:"); ?></td>
		<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggal ?></font></td>

		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Diterima:"); ?></td>
		<td class="TextColumn"><input type="text" name="Ket1" value="<?php echo $sKet1; ?>" maxlength="10" id="sel2" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel2', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sKet1 ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Urgensi :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="Urgensi">
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				<option value="1" <?php if ($sUrgensi == 1) { echo "selected"; } ?>><?php echo gettext("Sangat Segera"); ?></option>
				<option value="2" <?php if ($sUrgensi == 2) { echo "selected"; } ?>><?php echo gettext("Segera"); ?></option>
				<option value="3" <?php if ($sUrgensi == 3) { echo "selected"; } ?>><?php echo gettext("Biasa"); ?></option>
			</select>
		</td>

		<td class="LabelColumn"><?php echo gettext("Diterima dengan :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="Via">
				<option value="0"><?php echo gettext("pilih"); ?></option>
				<option value="1" <?php if ($sVia == 1) { echo "selected"; } ?>><?php echo gettext("POS"); ?></option>
				<option value="2" <?php if ($sVia == 2) { echo "selected"; } ?>><?php echo gettext("Titipan Kilat"); ?></option>
				<option value="3" <?php if ($sVia == 3) { echo "selected"; } ?>><?php echo gettext("Kurir"); ?></option>
				<option value="4" <?php if ($sVia == 4) { echo "selected"; } ?>><?php echo gettext("Fax"); ?></option>
				<option value="5" <?php if ($sVia == 5) { echo "selected"; } ?>><?php echo gettext("Email"); ?></option>
			</select>
		</td>

	<tr>
		<td class="LabelColumn"><?php echo gettext("Pengirim:"); ?></td>
		<td class="TextColumn"><input type="text" name="Dari" id="Dari" size="50" value="<?php echo htmlentities(stripslashes($sDari),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sDariError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Institusi Pengirim:"); ?></td>
		<td class="TextColumn"><input type="text" name="Institusi" id="Institusi" size="50" value="<?php echo htmlentities(stripslashes($sInstitusi),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sInstitusiError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Tujuan Surat:"); ?></td>
		<td class="TextColumn"><input type="text" name="Kepada" id="Kepada" size="50" value="<?php echo htmlentities(stripslashes($sKepada),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKepadaError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nomor Surat:"); ?></td>
		<td class="TextColumn"><input type="text" name="NomorSurat" id="NomorSurat"  size="50"   value="<?php echo htmlentities(stripslashes($sNomorSurat),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNomorSuratError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Perihal:"); ?></td>
		<td class="TextColumn" colspan="3" ><input type="text" name="Hal" id="Hal" size="100"  value="<?php echo htmlentities(stripslashes($sHal),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sHalError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Jumlah Lampiran:"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="Lampiran">
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				<option value="1" <?php if ($sLampiran == 1) { echo "selected"; } ?>><?php echo gettext("1"); ?></option>
				<option value="2" <?php if ($sLampiran == 2) { echo "selected"; } ?>><?php echo gettext("2"); ?></option>
				<option value="3" <?php if ($sLampiran == 3) { echo "selected"; } ?>><?php echo gettext("3"); ?></option>
				<option value="4" <?php if ($sLampiran == 4) { echo "selected"; } ?>><?php echo gettext("4"); ?></option>
				<option value="5" <?php if ($sLampiran == 5) { echo "selected"; } ?>><?php echo gettext("5"); ?></option>
				<option value="6" <?php if ($sLampiran == 6) { echo "selected"; } ?>><?php echo gettext("6"); ?></option>
				<option value="7" <?php if ($sLampiran == 7) { echo "selected"; } ?>><?php echo gettext("7"); ?></option>
				<option value="8" <?php if ($sLampiran == 8) { echo "selected"; } ?>><?php echo gettext("8"); ?></option>
				<option value="9" <?php if ($sLampiran == 9) { echo "selected"; } ?>><?php echo gettext("9"); ?></option>
				<option value="10" <?php if ($sLampiran == 10) { echo "selected"; } ?>><?php echo gettext("10"); ?></option>
				<option value="11" <?php if ($sLampiran == 11) { echo "selected"; } ?>><?php echo gettext(">10"); ?></option>
				
			</select>
		</td>	
		<td class="LabelColumn"><?php echo gettext("Type Lampiran:"); ?></td>
		<td class="TextColumn"><input type="text" name="TypeLampiran" id="TypeLampiran" size="50" value="<?php echo htmlentities(stripslashes($sTypeLampiran),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTypeLampiranError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kategori:"); ?></td>
		<td class="TextColumnWithBottomBorder">
		
						<select name="Ket3" >
						<option value="0" selected><?php echo gettext("Pilih"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsKlasSurat))
						{
							extract($aRow);

							echo "<option value=\"" . $KlasID . "\"";
							if ($sKet3 == $KlasID ) { echo " selected"; }
							echo ">" . $Deskripsi ." - " . $Keterangan;
						}
						?>

					</select>
	
		</td>	
	
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Di Follow Up oleh:"); ?></td>
		<td class="TextColumn"><input type="text" name="FollowUp" id="FollowUp" value="<?php echo htmlentities(stripslashes($sFollowUp),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sFollowUpError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Kepala Bidang Terkait:"); ?></td>
		<td class="TextColumn">
		
						<select name="Bidang1" >
						<option value="0" selected><?php echo gettext("Tidak Ada"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsBidang1))
						{
							extract($aRow);

							echo "<option value=\"" . $Bidang . "\"";
							if ($sBidang1 == $Bidang ) { echo " selected"; }
							echo ">" . $Jabatan ." - " . $Nama . " - " . $Email;
						}
						?>

					</select>
		</td>
	</tr>	
	<tr>
		<td class="LabelColumn"></td>
		<td class="TextColumn"></td>
		<td class="LabelColumn"><?php echo gettext("Majelis Pendamping:"); ?></td>
		<td class="TextColumn">
		
						<select name="Bidang2" >
						<option value="0" selected><?php echo gettext("Tidak Ada"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsBidang2))
						{
							extract($aRow);

							echo "<option value=\"" . $Bidang . "\"";
							if ($sBidang2 == $Bidang) { echo " selected"; }
							echo ">" . $Jabatan ." - " . $Nama . " - " . $Email;
						}
						?>

					</select>
		</td>		
	<tr>	
		<td class="LabelColumn"><?php echo gettext("Apakah Perlu Surat Balasan:"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="Respon">
				<option value="1" <?php if ($sRespon == 1) { echo "selected"; } ?>><?php echo gettext("Tidak"); ?></option>
				<option value="2" <?php if ($sRespon == 2) { echo "selected"; } ?>><?php echo gettext("Ya"); ?></option>
			</select>
		</td>
	
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Target Tanggal Respon:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglRespon" value="<?php echo $sTglRespon; ?>" maxlength="10" id="sel12" size="12">&nbsp;<input type="image" onclick="return showCalendar('sel12', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTglRespon ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Disposisi:"); ?></td>
		<td class="TextColumn"><input type="text" name="DisposisiTgl" value="<?php echo $sDisposisiTgl; ?>" maxlength="10" id="sel13" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel13', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sDisposisiTgl ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Disposisi Oleh:"); ?></td>
		<td class="TextColumn" ><input type="text" name="DisposisiOleh" id="DisposisiOleh" size="50"   value="<?php echo htmlentities(stripslashes($sDisposisiOleh),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sDisposisiTglError ?></font></td>

	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Status / Disposisi :"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="Status" id="Status" size="130"   value="<?php echo htmlentities(stripslashes($sStatus),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sStatusError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Deskripsi Singkat Isi Surat:"); ?></td>
		<td class="TextColumn" colspan="3"  ><input type="text" name="Ket2" id="Ket2" size="130"  value="<?php echo htmlentities(stripslashes($sKet2),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKet2Error ?></font></td>
	</tr>

	<tr>
	<td class="LabelColumn"><?php echo gettext("Response Surat:"); ?></td>
	</tr>
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Dibalas:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglDibalas" value="<?php echo $sTglDibalas; ?>" maxlength="10" id="sel11" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel11', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTglDibalas ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Isi Surat Balasan:"); ?></td>
		<td class="TextColumn" colspan="3" >
		
		<textarea id="IsiSuratBalasan" name="IsiSuratBalasan" rows="15" cols="80" >
			<?php echo $sIsiSuratBalasan;?>
		</textarea>

		
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
		$logvar2 = "mail Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
