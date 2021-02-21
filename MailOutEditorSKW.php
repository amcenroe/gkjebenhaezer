<?php
/*******************************************************************************
 *
 *  filename    : MailOutEditor.php
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
$sPageTitle = gettext("Editor - Surat Keluar (Unstructured)");

//Get the MailID out of the querystring
$iMailID = FilterInput($_GET["MailID"],'int');

ob_start();
?>



<!-- TinyMCE -->
<script type="text/javascript" src="Include/tiny_mce/tiny_mce.js">
</script>
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
	$sAlamat1 = FilterInput($_POST["Alamat1"]);
	$sAlamat2 = FilterInput($_POST["Alamat2"]);
	$sEmail = FilterInput($_POST["Email"]);
	$sTelp = FilterInput($_POST["Telp"]);
	$sFax = FilterInput($_POST["Fax"]);	
	$sSifatSurat = FilterInput($_POST["SifatSurat"]);
	$sHal = FilterInput($_POST["Hal"]);
	$sLampiran = FilterInput($_POST["Lampiran"]);
	$sTypeLampiran = FilterInput($_POST["TypeLampiran"]);
	$sKeterangan = FilterInput($_POST["Keterangan"]);
	$sFollowUp = FilterInput($_POST["FollowUp"]);
	$sStatus = FilterInput($_POST["Status"]);
	$sKet1 = FilterInput($_POST["Ket1"]);
	$sKet2 = FilterInput($_POST["Ket2"]);
	$sKet3 = FilterInput($_POST["Ket3"]);
	$sIsiSuratBalasan = $_POST["IsiSuratBalasan"];
//	$sIsiSuratBalasan = FilterInput($_POST["IsiSuratBalasan"]);
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
			 $sKet1 = "e/MG-".$sDari."/".$sChurchCode."/";
			 $sKet2 =  dec2roman(date (m)). "/" .date('Y');
			 
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO SuratKeluar ( MailID, Tanggal, Urgensi, Via, Dari, Institusi, Kepada, Alamat1, Alamat2, Email, Telp, Fax, SifatSurat, Hal, Lampiran, TypeLampiran, Keterangan, FollowUp, Status, Ket1, Ket2, Ket3, IsiSuratBalasan, TglDibalas, DateEntered, EnteredBy) 
			         VALUES ('" . $sMailID . "','" . $sTanggal . "','" . $sUrgensi . "','" . $sVia . "','" . $sDari . "','" . $sInstitusi . "',
					 '" . $sKepada . "','" . $sAlamat1 . "','" . $sAlamat2 . "','" . $sEmail . "','" . $sTelp . "','" . $sFax . "','" . $sSifatSurat . "','" . $sHal . "','" . $sLampiran . "','" . $sTypeLampiran . "','" . $sKeterangan . "','" . $sFollowUp . "',
					 '" . $sStatus . "','" . $sKet1 . "','" . $sKet2 . "','" . $sKet3 . "',
					 '" . $sIsiSuratBalasan . "','" . $sTglDibalas . "','" . date("YmdHis") . "'," . $_SESSION['iUserID'] . ")";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New Incoming Mail";

		// Existing mail (update)
		} else {
		
		$sKet1 = "e/MG-".$sKet3."/".$sChurchCode."/";

			$sSQL = "UPDATE SuratKeluar SET Tanggal = '" . $sTanggal . "',Urgensi = '" . $sUrgensi  . "',Via = '" . $sVia  . "',Dari = '" . $sDari  . "',
			Institusi = '" . $sInstitusi  . "',Kepada = '" . $sKepada  . "',Alamat1 = '" . $sAlamat1  . "',Alamat2 = '" . $sAlamat2  . "',
			Email = '" . $sEmail  . "',Telp = '" . $sTelp  . "',Fax = '" . $sFax  . "',
			SifatSurat = '" . $sSifatSurat  . "',Hal = '" . $sHal  . "',
			Lampiran = '" . $sLampiran  . "',TypeLampiran = '" . $sTypeLampiran  . "',Keterangan = '" . $sKeterangan  . "',
			FollowUp = '" . $sFollowUp  . "',Status = '" . $sStatus  . "',Ket2 = '" . $sKet2  . "',
			Ket3 = '" . $sKet3 . "' ,IsiSuratBalasan = '" . $sIsiSuratBalasan . "' ,TglDibalas = '" . $sTglDibalas . "' ,
			DateLastEdited = '" . date("YmdHis") . "', EditedBy = '" . $_SESSION['iUserID'] ;
			
			$sSQL .= "' WHERE MailID = " . $iMailID;

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

		// Check for redirection to another page after saving information: (ie. MailOutEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iMailID);
		}
		else if (isset($_POST["MailSubmit"]))
		{
			Redirect("SelectListApp.php?mode=mailout&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("MailOutEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iMailID) > 0)
	{
		//Editing....
		//Get all the data on this record

$sSQL = "SELECT a.* , IF(Dari=0,'SEKR',replace( `vol_Name` , 'Ketua', '' )) AS KodePengirim FROM SuratKeluar a
		LEFT JOIN volunteeropportunity_vol b ON a.Dari = b.vol_ID WHERE MailID = " . $iMailID;
		$rsmail = RunQuery($sSQL);
		extract(mysql_fetch_array($rsmail));

	$sMailID = $MailID;
	$sTanggal = $Tanggal;
	$sUrgensi = $Urgensi;
	$sVia = $Via;
	$sDari = $Dari;
	$sInstitusi = $Institusi;
	$sKepada = $Kepada;
	$sAlamat1 = $Alamat1;
	$sAlamat2 = $Alamat2;
	$sEmail = $Email;
	$sTelp = $Telp;
	$sFax = $Fax;	
	$sSifatSurat = $SifatSurat;
	$sHal = $Hal;
	$sLampiran = $Lampiran;
	$sTypeLampiran = $TypeLampiran;
	$sKeterangan = $Keterangan;
	$sFollowUp = $FollowUp;
	$sStatus = $Status;
	$sKet1 = $Ket1;
	$sKet2 = $Ket2;
	$sKet3 = $Ket3;
	$sIsiSuratBalasan = $IsiSuratBalasan;
	$sTglDibalas = $TglDibalas;

	
							$time  = strtotime($Tanggal);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat =  $MailID."e/MG-".$KodePengirim."/".$sChurchCode."/".dec2roman($month)."/".$year;
	
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
		
	}
}



require "Include/Header.php";


//Get Pengirim Names for the drop-down
$sSQL = "SELECT vol_ID, replace( `vol_Name` , 'Ketua', '' ) AS KodePengirim, replace(`vol_Description`, 'Ketua', '') as NamaPengirim FROM `volunteeropportunity_vol` WHERE `vol_Description` LIKE '%Ketua%' AND Vol_id <60";
$rsNamaPengirim = RunQuery($sSQL);

?>

<form method="post" action="MailOutEditor.php?MailID=<?php echo $iMailID; ?>" name="MailEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="MailSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"MailSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="mailCancel" onclick="javascript:document.location='<?php if (strlen($iMailID) > 0) 
{ echo "MailOutView.php?MailID=" . $iMailID."&amp;$refresh"; 
} else {echo "SelectListApp.php?mode=mailout&amp;$refresh"; 
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
				<td colspan="2" align="center"><h3><?php echo gettext("Data Standar - Nomor Surat: "); echo $NomorSurat; ?></h3></td>
			</tr>


	
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Surat:"); ?></td>
		<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggal ?></font></td>
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
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kategori:"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="Ket3" id="idtiers" onchange="showtiers(this.value);" >
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				<option value="11" <?php if ($sKet3 == 11) { echo "selected"; } ?>><?php echo gettext("Informasi Umum"); ?></option>
				<option value="12" <?php if ($sKet3 == 12) { echo "selected"; } ?>><?php echo gettext("Surat Edaran"); ?></option>
				<option value="13" <?php if ($sKet3 == 13) { echo "selected"; } ?>><?php echo gettext("Undangan"); ?></option>
				<option value="14" <?php if ($sKet3 == 14) { echo "selected"; } ?>><?php echo gettext("Laporan Kegiatan"); ?></option>
				<option value="15" <?php if ($sKet3 == 15) { echo "selected"; } ?>><?php echo gettext("Ucapan Terima Kasih"); ?></option>
				<option value="16" <?php if ($sKet3 == 16) { echo "selected"; } ?>><?php echo gettext("Surat Keterangan"); ?></option>
				<option value="17" <?php if ($sKet3 == 17) { echo "selected"; } ?>><?php echo gettext("Surat Keterangan - Warga Jemaar"); ?></option>

				<option value="21" <?php if ($sKet3 == 21) { echo "selected"; } ?>><?php echo gettext("Permohonan Umum"); ?></option>
				<option value="22" <?php if ($sKet3 == 22) { echo "selected"; } ?>><?php echo gettext("Permohonan Bantuan"); ?></option>
				<option value="23" <?php if ($sKet3 == 23) { echo "selected"; } ?>><?php echo gettext("Permohonan Pelayanan Firman"); ?></option>
				<option value="24" <?php if ($sKet3 == 24) { echo "selected"; } ?>><?php echo gettext("Permohonan Peminjaman Asset Gereja"); ?></option>
				<option value="25" <?php if ($sKet3 == 25) { echo "selected"; } ?>><?php echo gettext("Permohonan Pelayanan Gerejawi (Baptis/Sidi/Nikah/Pemakaman/dll) "); ?></option>
			
				<option value="31" <?php if ($sKet3 == 31) { echo "selected"; } ?>><?php echo gettext("Surat Pindah/Atestasi"); ?></option>
				<option value="32" <?php if ($sKet3 == 32) { echo "selected"; } ?>><?php echo gettext("Surat Pemberitahuan Sakramen"); ?></option>
				<option value="33" <?php if ($sKet3 == 33) { echo "selected"; } ?>><?php echo gettext("Surat Penitipan Rohani"); ?></option>

			</select>

			</td>	
	
		
		<td class="LabelColumn"><?php echo gettext("Umum / Gerejawi"); ?></td>
		<td class="TextColumn">			
				<select name="Via">
				<option value="Gerejawi"><?php echo gettext("Gerejawi"); ?></option>
				<option value="Umum" <?php if ($sVia == "Umum") { echo "selected"; } ?>><?php echo gettext("Umum"); ?></option>
				</select>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Pengirim:"); ?></td>
		<td class="TextColumnWithBottomBorder" colspans="2" >
					<select name="Dari" >
						<option value="0" selected><?php echo gettext("Sekretariat"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPengirim))
						{
							extract($aRow);

							echo "<option value=\"" . $vol_ID . "\"";
							if ($sDari == $vol_ID) { echo " selected"; }
							echo ">" . $KodePengirim." - ".$NamaPengirim;
						}
						?>
					</select>

					</select>
		</td>	</tr>	

	<tr>
		<td class="LabelColumn"><?php echo gettext("Kepada :"); ?></td>
		<td class="TextColumn"><input type="text" name="Kepada" id="Kepada" value="<?php echo htmlentities(stripslashes($sKepada),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKepadaError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Institusi:"); ?></td>
		<td class="TextColumn"><input type="text" name="Institusi" id="Institusi" value="<?php echo htmlentities(stripslashes($sInstitusi),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sInstitusiError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Alamat 1:"); ?></td>
		<td class="TextColumn"><input type="text" name="Alamat1" id="Alamat1" value="<?php echo htmlentities(stripslashes($sAlamat1),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat1Error ?></font></td>
	
		<td class="LabelColumn"><?php echo gettext("Telp :"); ?></td>
		<td class="TextColumn"><input type="text" name="Telp" id="Telp" value="<?php echo htmlentities(stripslashes($sTelp),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTelpError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Alamat 2:"); ?></td>
		<td class="TextColumn"><input type="text" name="Alamat2" id="Alamat2" value="<?php echo htmlentities(stripslashes($sAlamat2),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat2Error ?></font></td>
	
		<td class="LabelColumn"><?php echo gettext("Fax :"); ?></td>
		<td class="TextColumn"><input type="text" name="Fax" id="Fax" value="<?php echo htmlentities(stripslashes($sFax),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sFaxError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Email :"); ?></td>
		<td class="TextColumn"><input type="text" name="Email" id="Email" value="<?php echo htmlentities(stripslashes($sEmail),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sEmailError ?></font></td>
	</tr>	

		

	<tr>
		<td class="LabelColumn"><?php echo gettext("Perihal:"); ?></td>
		<td class="TextColumn"><input type="text" name="Hal" id="Hal" size="50"  value="<?php echo htmlentities(stripslashes($sHal),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sHalError ?></font></td>
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
	
	
	
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Type Lampiran:"); ?></td>
		<td class="TextColumn"><input type="text" name="TypeLampiran" id="TypeLampiran" value="<?php echo htmlentities(stripslashes($sTypeLampiran),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTypeLampiranError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Di Follow Up oleh:"); ?></td>
		<td class="TextColumn"><input type="text" name="FollowUp" id="FollowUp" value="<?php echo htmlentities(stripslashes($sFollowUp),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sFollowUpError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Status :"); ?></td>
		<td class="TextColumn"><input type="text" name="Status" id="Status" size="50"   value="<?php echo htmlentities(stripslashes($sStatus),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sStatusError ?></font></td>
	</tr>




	<tr>
		<td class="LabelColumn"><?php echo gettext("Isi Surat:"); ?></td>
		<td class="TextColumn" colspan="3" >
		
		<textarea id="IsiSuratBalasan" name="IsiSuratBalasan" rows="15" cols="80" >
			<?php echo $sIsiSuratBalasan;?>
		</textarea>
</div>
<!-- Some integration calls -->
		
		</td>
	</tr>	


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
//require "Include/Footer.php";
?>
