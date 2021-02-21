<?php
/*******************************************************************************
 *
 *  filename    : NotulaRapatEditor.php
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
$sPageTitle = gettext("Editor - Notula Rapat");

//Get the NotulaRapatID out of the querystring
$iNotulaRapatID = FilterInput($_GET["NotulaRapatID"],'int');


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
// Clean error handling: (such as somebody typing an incorrect URL ?NotulaRapatID= manually)
if (strlen($iNotulaRapatID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsNotulaRapat = RunQuery($sSQL);
	extract(mysql_fetch_array($rsNotulaRapat));

	if (mysql_num_rows($rsNotulaRapat) == 0)
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

if (isset($_POST["NotulaRapatSubmit"]) || isset($_POST["NotulaRapatSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	

	$sNotulaRapatID = FilterInput($_POST["NotulaRapatID"]);
	$sTanggal = FilterInput($_POST["Tanggal"]);
	$sPukul = FilterInput($_POST["Pukul"]);
	$sKodeTI = FilterInput($_POST["KodeTI"]);
	$sNomorSurat = FilterInput($_POST["NomorSurat"]);
	$sIsiNotula = $_POST["IsiNotula"];
	$sKeterangan = FilterInput($_POST["Keterangan"]);
	$sPeserta = FilterInput($_POST["Peserta"]);

	//Initialize the error flag
	$bErrorFlag = false;

	// Validate NotulaRapat Date if one was entered
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
		// New NotulaRapat (add)
		

		
		if (strlen($iNotulaRapatID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

	
			$sSQL = "INSERT INTO NotulaRapat ( NotulaRapatID, Tanggal, Pukul, KodeTI, NomorSurat, IsiNotula, Keterangan, Peserta, DateEntered, EnteredBy) 
			         VALUES ('" . $sNotulaRapatID . "','" . $sTanggal . "','" . $sPukul . "','" . $sKodeTI . "','" . $sNomorSurat . "','" . $sIsiNotula . "','" . $sKeterangan . "','" . $sPeserta . "','" . date("YmdHis") . "'," . $_SESSION['iUserID'] . ")";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New NotulaRapat";

		// Existing NotulaRapat (update)
		} else {

			$sSQL = "UPDATE NotulaRapat SET Tanggal = '" . $sTanggal . "',Pukul = '" . $sPukul . "',KodeTI = '" . $sKodeTI  . "',NomorSurat = '" . $sNomorSurat  . "',
			IsiNotula = '" . $sIsiNotula  . "',Keterangan = '" . $sKeterangan  . "',Peserta = '" . $sPeserta  . "',
			DateLastEdited = '" . date("YmdHis") . "', EditedBy = '" . $_SESSION['iUserID'] ;
			
			$sSQL .= "' WHERE NotulaRapatID = " . $iNotulaRapatID;
//echo $sSQL;
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update NotulaRapat";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iNotulaRapatID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Update the custom NotulaRapat fields.

		// Check for redirection to another page after saving information: (ie. NotulaRapatEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iNotulaRapatID);
		}
		else if (isset($_POST["NotulaRapatSubmit"]))
		{
			Redirect("SelectListApp2.php?mode=notularapat&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("NotulaRapatEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iNotulaRapatID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM NotulaRapat  WHERE NotulaRapatID = " . $iNotulaRapatID;
		$rsNotulaRapat = RunQuery($sSQL);
		extract(mysql_fetch_array($rsNotulaRapat));
	
				
	$sNotulaRapatID = $NotulaRapatID;
	$sTanggal = $Tanggal;
	$sPukul = $Pukul;
	$sKodeTI = $KodeTI;
	$sNomorSurat = $NomorSurat;
	$sIsiNotula = $IsiNotula;
	$sKeterangan = $Keterangan;
	$sPeserta = $Peserta;
	
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
	}
}


//Get Lokasi TI Names for the drop-down
$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
$rsNamaTempatIbadah = RunQuery($sSQL);


require "Include/Header.php";

?>

<form method="post" action="NotulaRapatEditor.php?NotulaRapatID=<?php echo $iNotulaRapatID; ?>" name="NotulaRapatEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="NotulaRapatSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"NotulaRapatSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="NotulaRapatCancel" onclick="javascript:document.location='<?php if (strlen($iNotulaRapatID) > 0) 
{ echo "SelectListApp2.php?mode=notularapat&amp;$refresh"; 
} else {echo "SelectListApp2.php?mode=notularapat&amp;$refresh"; 
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
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Rapat:"); ?></td>
		<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;
		<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> 
		<span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Pukul :"); ?></td>
		<td class="TextColumn" colspan="3"><input type="text" name="Pukul" id="Pukul" size="10" value="<?php echo htmlentities(stripslashes($sPukul),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sPukulError ?></font></td>
		
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
		<td class="TextColumn" colspan="4"><input type="text" size=40 name="TempatPF" id="TempatPF" value="<?php echo htmlentities(stripslashes($sTempatPF),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatPFError ?></font></td>
	</tr>
	<tr>	

		<td class="LabelColumn"><?php echo gettext("Keterangan Rapat :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="Keterangan">
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				<option value="MPH Reguler" <?php if ($sKeterangan == 'MPH Reguler') { echo "selected"; } ?>><?php echo gettext("MPH Reguler"); ?></option>
				<option value="MPH Istimewa" <?php if ($sKeterangan == 'MPH Istimewa') { echo "selected"; } ?>><?php echo gettext("MPH Istimewa"); ?></option>
				<option value="MPL Reguler" <?php if ($sKeterangan == 'MPL Reguler') { echo "selected"; } ?>><?php echo gettext("MPL Reguler"); ?></option>
				<option value="MPL Istimewa" <?php if ($sKeterangan == 'MPL Istimewa') { echo "selected"; } ?>><?php echo gettext("MPL Istimewa"); ?></option>
				<option value="MPH dan Bidang" <?php if ($sKeterangan == 'MPH dan Bidang') { echo "selected"; } ?>><?php echo gettext("MPH dan Bidang"); ?></option>
				<option value="MPH dan Bawas" <?php if ($sKeterangan == 'MPH dan Bawas') { echo "selected"; } ?>><?php echo gettext("MPH dan Bawas"); ?></option>
				<option value="Lainnya" <?php if ($sKeterangan == 'Lainnya') { echo "selected"; } ?>><?php echo gettext("Lainnya"); ?></option>

				</select>
		</td>

	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Judul Notula Rapat:"); ?></td>
		<td class="TextColumn" colspan="4"><input type="text" name="NomorSurat" id="NomorSurat" size="100" value="<?php echo htmlentities(stripslashes($sNomorSurat),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sNomorSuratError ?></font></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Isi Notula Rapat:"); ?></td>
		<td class="TextColumn" colspan="4" >
		
		<textarea id="IsiNotula" name="IsiNotula" rows="15" cols="80" >
			<?php echo $sIsiNotula;?>
		</textarea>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Jumlah Peserta:"); ?></td>
		<td class="TextColumn" colspan="4"><input type="text" name="Peserta" id="Peserta" size="100" value="<?php echo htmlentities(stripslashes($sPeserta),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sPesertaError ?></font></td>

	<tr>	

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
		$logvar2 = "NotulaRapat Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iNotulaRapatID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
