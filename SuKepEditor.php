<?php
/*******************************************************************************
 *
 *  filename    : SuKepEditor.php
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
$sPageTitle = gettext("Editor - Surat Keputusan");

//Get the SKID out of the querystring
$iSKID = FilterInput($_GET["SKID"],'int');
$iKategori = FilterInput($_GET["Kategori"],'int');
$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;
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
// Clean error handling: (such as somebody typing an incorrect URL ?SKID= manually)
if (strlen($iSKID) > 0)
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

	$sSKID = FilterInput($_POST["SKID"]);
	$sTanggal = FilterInput($_POST["Tanggal"]);
	$sTanggalExp = FilterInput($_POST["TanggalExp"]);	
	$sUrgensi = FilterInput($_POST["Urgensi"]);
	$sNomorSurat = FilterInput($_POST["NomorSurat"]);
	$sNomorSuratReff = FilterInput($_POST["NomorSuratReff"]);
	$sSifatSurat = FilterInput($_POST["SifatSurat"]);
	$sHal = FilterInput($_POST["Hal"]);
	$sTembusan1 = FilterInput($_POST["Tembusan1"]);
	$sTembusan2 = FilterInput($_POST["Tembusan2"]);
	$sTembusan3 = FilterInput($_POST["Tembusan3"]);
	$sTembusan4 = FilterInput($_POST["Tembusan4"]);
	$sLampiran = FilterInput($_POST["Lampiran"]);
	$sTypeLampiran = FilterInput($_POST["TypeLampiran"]);
	$sIsiSuratKeputusan = $_POST["IsiSuratKeputusan"];
	$sIsiLampiran = $_POST["IsiLampiran"];
	$sPendeta = $_POST["Pendeta"];
	$sKetua = $_POST["Ketua"];
	$sSekretaris = $_POST["Sekretaris"];
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
		// New SK (add)
		if (strlen($iSKID) < 1)
		{
			 $sKet1 = "e/MG-".$sDari."/".$sChurchCode."/";
		//	 $sKet2 =  dec2roman(date (m)). "/" .date('Y');
			 
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";
				
 $sPendeta = jabatanpengurus(1);
 $sKetua = jabatanpengurus(61);
 $sSekretaris = jabatanpengurus(65);
				
			$sSQL = "INSERT INTO SuratKeputusan ( SKID, Tanggal,  TanggalExp,Urgensi, NomorSurat,NomorSuratReff, SifatSurat, Hal, Tembusan1, Tembusan2, Tembusan3, Tembusan4,
			Lampiran, TypeLampiran, IsiSuratKeputusan, IsiLampiran, Pendeta, Ketua, Sekretaris, DateEntered, EnteredBy) 
			         VALUES ('" . $sSKID . "','" . $sTanggal . "','" . $sTanggalExp . "','" . $sUrgensi . "','" . $sNomorSurat . "','" . $sNomorSuratReff . "','" . $sSifatSurat . "','" . $sHal . "',
					 '" . $sTembusan1 . "','" . $sTembusan2 . "','" . $sTembusan3 . "','" . $sTembusan4 . "','" . $sLampiran . "','" . $sTypeLampiran . "',
					 '" . $sIsiSuratKeputusan . "','" . $sIsiLampiran . "','" . $sPendeta . "','" . $sKetua . "','" . $sSekretaris . "','" . date("YmdHis") . "'," . $_SESSION['iUserID'] . ")";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "Buat Surat Keputusan Baru";

		// Existing mail (update)
		} else {
		
			$sSQL = "UPDATE SuratKeputusan SET Tanggal = '" . $sTanggal . "',TanggalExp = '" . $sTanggalExp . "',Urgensi = '" . $sUrgensi  . "',NomorSurat = '" . $sNomorSurat  . "',NomorSuratReff = '" . $sNomorSuratReff  . "',
			SifatSurat = '" . $sSifatSurat  . "',Hal = '" . $sHal  . "',Tembusan1 = '" . $sTembusan1  . "',Tembusan2 = '" . $sTembusan2  . "',Tembusan3 = '" . $sTembusan3  . "',Tembusan4 = '" . $sTembusan4  . "',
			Lampiran = '" . $sLampiran  . "',TypeLampiran = '" . $sTypeLampiran  . "',
			IsiSuratKeputusan = '" . $sIsiSuratKeputusan . "' ,IsiLampiran = '" . $sIsiLampiran . "' ,
			DateLastEdited = '" . date("YmdHis") . "', EditedBy = '" . $_SESSION['iUserID'] ;
			
			$sSQL .= "' WHERE SKID = " . $iSKID;

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Surat Keputusan";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iSKID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Update the custom mail fields.

		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iSKID);
		}
		else if (isset($_POST["MailSubmit"]))
		{
			Redirect("SelectListApp.php?mode=sukep&amp;GID=$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("SuKepEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iSKID) > 0)
	{
		//Editing....
		//Get all the data on this record

$sSQL = "SELECT a.* FROM SuratKeputusan a
		WHERE SKID = " . $iSKID;
		$rsmail = RunQuery($sSQL);
		extract(mysql_fetch_array($rsmail));

	$sSKID = $SKID;
	$sTanggal = $Tanggal;
	$sTanggalExp = $TanggalExp;	
	$sUrgensi = $Urgensi;
	$sNomorSurat = $NomorSurat;
	$sNomorSuratReff = $NomorSuratReff;
	$sSifatSurat = $SifatSurat;
	$sHal = $Hal;
	$sTembusan1 = $Tembusan1;
	$sTembusan2 = $Tembusan2;
	$sTembusan3 = $Tembusan3;
	$sTembusan4 = $Tembusan4;
	$sLampiran = $Lampiran;
	$sTypeLampiran = $TypeLampiran;
	$sIsiSuratKeputusan = $IsiSuratKeputusan;
	$sIsiLampiran = $IsiLampiran;

	
						$time  = strtotime($Tanggal);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat2 =  $SKID."/MG/SK/".$NomorSurat."/".$sChurchCode."/".dec2roman($month)."/".$year;
	
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
		
	}
}



require "Include/Header.php";


?>

<form method="post" action="SuKepEditor.php?SKID=<?php echo $iSKID; ?>" name="SuKepEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="MailSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"MailSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="mailCancel" onclick="javascript:document.location='<?php if (strlen($iSKID) > 0) 
{ echo "SuKepView.php?SKID=" . $iSKID."&amp;GID=$refresh"; 
} else {echo "SelectListApp.php?mode=sukep&amp;GID=$refresh"; 
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
				<td colspan="2" align="center"><h3><?php 
				
						$time  = strtotime($Tanggal);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat2 =  $SKID."/MG/SK/".$NomorSurat."/".$sChurchCode."/".dec2roman($month)."/".$year;
				
				echo gettext("Data Standar - Nomor Surat Keputusan: "); echo $NomorSurat2; ?></h3></td>
			</tr>


	
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Surat:"); ?></td>
		<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalError ?></font></td>

		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Expired Surat:"); ?></td>
		<td class="TextColumn"><input type="text" name="TanggalExp" value="<?php echo $sTanggalExp; ?>" maxlength="10" id="sel2" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel2', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalExpError ?></font></td>
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
		<td class="LabelColumn"><?php echo gettext("Nomor Surat:"); ?></td>
		<td colspan="4"  class="TextColumn">/MG/SK/<input type="text" name="NomorSurat" id="NomorSurat" size="50"  value="<?php echo htmlentities(stripslashes($sNomorSurat),ENT_NOQUOTES, "UTF-8"); ?>">
		/<?php echo  $sChurchCode; ?>/ <?php if ($Tanggal == ""){ echo "BLN/TAHUN";} else { echo dec2roman($month)."/".$year ;}?> <br><font color="red"><?php echo $sNomorSuratError ?></font></td>
	</tr>	
		<tr>
		<td class="LabelColumn"><?php echo gettext("Nomor Surat Reff/Asli:"); ?></td>
		<td colspan="4"  class="TextColumn"><input type="text" name="NomorSuratReff" id="NomorSuratReff" size="50"  value="<?php echo htmlentities(stripslashes($sNomorSuratReff),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sNomorSuratReffError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Perihal:"); ?></td>
		<td colspan="4"  class="TextColumn"><input type="text" name="Hal" id="Hal" size="50"  value="<?php echo htmlentities(stripslashes($sHal),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sHalError ?></font></td>
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
		<td class="TextColumn"><input type="text" name="TypeLampiran" id="TypeLampiran" value="<?php echo htmlentities(stripslashes($sTypeLampiran),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTypeLampiranError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Tembusan 1:"); ?></td>
		<td   class="TextColumn"><input type="text" name="Tembusan1" id="Tembusan1" size="50"  value="<?php echo htmlentities(stripslashes($sTembusan1),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTembusan1Error ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Tembusan 2:"); ?></td>
		<td colspan="4"  class="TextColumn"><input type="text" name="Tembusan2" id="Tembusan2" size="50"  value="<?php echo htmlentities(stripslashes($sTembusan2),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTembusan2Error ?></font></td>
	</tr>
		<tr>
		<td class="LabelColumn"><?php echo gettext("Tembusan 3:"); ?></td>
		<td   class="TextColumn"><input type="text" name="Tembusan3" id="Tembusan3" size="50"  value="<?php echo htmlentities(stripslashes($sTembusan3),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTembusan3Error ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Tembusan 4:"); ?></td>
		<td colspan="4"  class="TextColumn"><input type="text" name="Tembusan4" id="Tembusan4" size="50"  value="<?php echo htmlentities(stripslashes($sTembusan4),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTembusan4Error ?></font></td>
	</tr>
	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Isi Surat Keputusan:"); ?></td>
		<td class="TextColumn" colspan="3" >
		
		<textarea id="IsiSuratKeputusan" name="IsiSuratKeputusan" rows="15" cols="80" >
			<?php echo $sIsiSuratKeputusan;?>
		</textarea>

		</td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Isi Lampiran (text):"); ?></td>
		<td class="TextColumn" colspan="3" >
		
		<textarea id="IsiLampiran" name="IsiLampiran" rows="15" cols="80" >
			<?php echo $sIsiLampiran;?>
		</textarea>

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
		$logvar2 = "Surat Keputusan Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iSKID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
//require "Include/Footer.php";
?>
