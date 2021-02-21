<?php
/*******************************************************************************
 *
 *  filename    : MasaBaktiMajelisEditor.php
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
$sPageTitle = gettext("Editor - Masa Bakti Majelis");

//Get the MasaBaktiMajelisID out of the querystring
$iMasaBaktiMajelisID = FilterInput($_GET["MasaBaktiMajelisID"],'int');


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
// Clean error handling: (such as somebody typing an incorrect URL ?MasaBaktiMajelisID= manually)
if (strlen($iMasaBaktiMajelisID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsMasaBaktiMajelis = RunQuery($sSQL);
	extract(mysql_fetch_array($rsMasaBaktiMajelis));

	if (mysql_num_rows($rsMasaBaktiMajelis) == 0)
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

if (isset($_POST["MasaBaktiMajelisSubmit"]) || isset($_POST["MasaBaktiMajelisSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	

	$sMasaBaktiMajelisID = FilterInput($_POST["MasaBaktiMajelisID"]);
	$sper_ID = FilterInput($_POST["per_ID"]);
	$svol_ID = FilterInput($_POST["vol_ID"]);
	$sTglKeputusan = FilterInput($_POST["TglKeputusan"]);
	$sTglPeneguhan = FilterInput($_POST["TglPeneguhan"]);
	$sTglAkhir = FilterInput($_POST["TglAkhir"]);
	$sKategorial = FilterInput($_POST["Kategorial"]);
	$sKetTambahan = FilterInput($_POST["KetTambahan"]);
	
	$sPendeta = FilterInput($_POST["Pendeta"]);
	$sKetua = FilterInput($_POST["Ketua"]);
	$sSekretaris = FilterInput($_POST["Sekretaris"]);
	
	$sDateLastEdited = FilterInput($_POST["DateLastEdited"]);
	$sDateEntered = FilterInput($_POST["DateEntered"]);
	$sEnteredBy = FilterInput($_POST["EnteredBy"]);
	$sEditedBy = FilterInput($_POST["EditedBy"]);
	

	//Initialize the error flag
	$bErrorFlag = false;

	// Validate MasaBaktiMajelis Date if one was entered
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
		// New MasaBaktiMajelis (add)
			
		if (strlen($iMasaBaktiMajelisID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

	
		//	$sSQL = "INSERT INTO MasaBaktiMajelis ( MasaBaktiMajelisID, Tanggal, Pukul, KodeTI, NomorSurat, IsiNotula, Keterangan, Peserta, DateEntered, EnteredBy) 
		//	         VALUES ('" . $sMasaBaktiMajelisID . "','" . $sTanggal . "','" . $sPukul . "','" . $sKodeTI . "','" . $sNomorSurat . "','" . $sIsiNotula . "','" . $sKeterangan . "','" . $sPeserta . "','" . date("YmdHis") . "'," . $_SESSION['iUserID'] . ")";


	
		$sSQL = "INSERT INTO MasaBaktiMajelis ( 
					per_ID,
					vol_ID,
					TglKeputusan,
					TglPeneguhan,
					TglAkhir,
					Kategorial,
					KetTambahan,
					Pendeta,
					Ketua,
					Sekretaris,
					DateEntered,
					EnteredBy )
				VALUES ('" . 
					$sper_ID . "','" . 
					$svol_ID . "','" . 
					$sTglKeputusan . "','" . 
					$sTglPeneguhan . "','" . 
					$sTglAkhir . "','" . 
					$sKategorial . "','" . 
					$sKetTambahan . "','" . 
					$sPendeta . "','" . 
					$sKetua . "','" . 
					$sSekretaris . "','" . 
					date("YmdHis") . "'," . 
					$_SESSION['iUserID'] . ")";


					 $bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New MasaBaktiMajelis";

		// Existing MasaBaktiMajelis (update)
		} else {

			$sSQL = "UPDATE MasaBaktiMajelis SET 
			per_ID = '" . $sper_ID . "',
			vol_ID = '" . $svol_ID . "',
			TglKeputusan = '" . $sTglKeputusan  . "',
			TglPeneguhan = '" . $sTglPeneguhan  . "',
			TglAkhir = '" . $sTglAkhir  . "',
			Kategorial = '" . $sKategorial  . "',
			KetTambahan = '" . $sKetTambahan  . "',
			Pendeta = '" . $sPendeta  . "',
			Ketua = '" . $sKetua  . "',
			Sekretaris = '" . $sSekretaris  . "',
			DateLastEdited = '" . date("YmdHis") . "', 
			EditedBy = '" . $_SESSION['iUserID'] ;
			
			$sSQL .= "' WHERE MasaBaktiMajelisID = " . $iMasaBaktiMajelisID;
//echo $sSQL;
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update MasaBaktiMajelis";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMasaBaktiMajelisID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Update the custom MasaBaktiMajelis fields.

		// Check for redirection to another page after saving information: (ie. MasaBaktiMajelisEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iMasaBaktiMajelisID);
		}
		else if (isset($_POST["MasaBaktiMajelisSubmit"]))
		{
			Redirect("SelectListApp3.php?mode=MasaBaktiMajelis&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("MasaBaktiMajelisEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iMasaBaktiMajelisID) > 0)
	{
		//Editing....
		//Get all the data on this record

//		$sSQL = "SELECT * FROM MasaBaktiMajelis  WHERE MasaBaktiMajelisID = " . $iMasaBaktiMajelisID;
       $sSQL = "select a.*, b.*, c.* , d.*
	   FROM MasaBaktiMajelis	a
			LEFT JOIN person_per b ON a.per_ID = b.per_ID
			LEFT JOIN volunteeropportunity_vol c ON a.vol_ID = c.vol_ID
			LEFT JOIN NotulaRapat d ON a.TglKeputusan = d.Tanggal
			WHERE MasaBaktiMajelisID = " . $iMasaBaktiMajelisID;
		$rsMasaBaktiMajelis = RunQuery($sSQL);
		extract(mysql_fetch_array($rsMasaBaktiMajelis));
	
	$sMasaBaktiMajelisID = $MasaBaktiMajelisID;
	$sper_ID = $per_ID;
	$svol_ID = $vol_ID;
	$sTglKeputusan = $TglKeputusan;
	$sTglPeneguhan = $TglPeneguhan;
	$sTglAkhir = $TglAkhir;
	$sKategorial = $Kategorial;
	$sKetTambahan = $KetTambahan;
	$sKeterangan = $Keterangan;
	$sPendeta = $Pendeta;
	$sKetua = $Ketua;
	$sSekretaris = $Sekretaris;		
	$sDateLastEdited = $DateLastEdited;
	$sDateEntered = $DateEntered;
	$sEnteredBy = $EnteredBy;
	$sEditedBy = $EditedBy;
	
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

//Get Person Names for the drop-down
//$sSQL = "SELECT *, IF(per_Gender='1','Bp','Ibu') as Gender FROM person_per WHERE per_cls_id = 1 ORDER BY per_workphone, per_firstname";
$sSQL = "select per_cls_id, per_ID ,per_FirstName, per_WorkPhone ,
per_membershipdate as TglDaftar,
IF (per_Gender='1','Bp','Ibu') as Gender,
IF (c15='2','Menikah','-') as Status,
IF(c2='0000-00-00 00:00:00','-',c2) as TglSidhi,
IF(c27 is NULL,'-',c27) as TmpSidhi, IF(c18='0000-00-00 00:00:00','-',c18) as TglBaptisDewasa,
IF(c28 is NULL,'-',c28) as TmpBaptisDewasa
from person_per natural join person_custom
where per_cls_id=1 AND (( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
AND c27 is not NULL )
OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2) 
order by per_workphone, per_FirstName";

$rsNamaMajelis = RunQuery($sSQL);

//Get Jabatan Gereja for the drop-down
$sSQL = "SELECT * FROM volunteeropportunity_vol WHERE vol_ID between 1 AND 3";
$rsNamaJabatan = RunQuery($sSQL);

//Get Keputusan Gereja for the drop-down
$sSQL = "SELECT * FROM NotulaRapat ORDER BY Tanggal ASC, NomorSurat ASC";
$rsKeputusan = RunQuery($sSQL);

require "Include/Header.php";

?>

<form method="post" action="MasaBaktiMajelisEditor.php?MasaBaktiMajelisID=<?php echo $iMasaBaktiMajelisID; ?>" name="MasaBaktiMajelisEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="MasaBaktiMajelisSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"MasaBaktiMajelisSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="MasaBaktiMajelisCancel" onclick="javascript:document.location='<?php if (strlen($iMasaBaktiMajelisID) > 0) 
{ echo "SelectListApp3.php?mode=MasaBaktiMajelis&amp;$refresh"; 
} else {echo "SelectListApp3.php?mode=MasaBaktiMajelis&amp;$refresh"; 
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
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Nama Majelis:"); ?></td>
				<td colspan="0" class="TextColumn">
					<select name="per_ID">
						<?php
						while ($aRow = mysql_fetch_array($rsNamaMajelis))
						{
							extract($aRow);

							echo "<option value=\"" . $per_ID . "\"";
							if ($sper_ID == $per_ID) { echo " selected"; }
							echo ">" . $per_WorkPhone . " - " .$per_FirstName . " ( ". $Gender . " )" ;
						}
						?>
					</select>
				</td>
   	</tr>

	<tr>      	

				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Jabatan Gereja:"); ?></td>
				<td colspan="0" class="TextColumn">
					<select name="vol_ID">
						<?php
						while ($aRow = mysql_fetch_array($rsNamaJabatan))
						{
							extract($aRow);

							echo "<option value=\"" . $vol_ID . "\"";
							if ($svol_ID == $vol_ID) { echo " selected"; }
							echo ">" . $vol_Name ;
						}
						?>
					</select>
				</td>
				
		<td class="LabelColumn"><?php echo gettext("Jabatan Khusus/Kategorial:"); ?></td>
		<td class="TextColumn" colspan="4"><input type="text" size=40 name="Kategorial" id="Kategorial" value="<?php echo htmlentities(stripslashes($sKategorial),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKategorialError ?></font></td>

				
				
  	</tr>

	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Keputusan:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglKeputusan" value="<?php echo $sTglKeputusan; ?>" maxlength="10" id="sel1" size="11">&nbsp;
		<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> 
		<span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTglKeputusanError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Diputuskan di :"); ?></td>
		<td class="TextColumn"><input type="text"  size=40 value="<?php echo $NomorSurat." - " .$sKeterangan; ?>"  disabled>&nbsp;

		
	</tr>
	
	
	
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Peneguhan:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglPeneguhan" value="<?php echo $sTglPeneguhan; ?>" maxlength="10" id="sel2" size="11">&nbsp;
		<input type="image" onclick="return showCalendar('sel2', 'y-mm-dd');" src="Images/calendar.gif"> 
		<span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTglPeneguhanError ?></font></td>
       	
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Akhir Jabatan:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglAkhir" value="<?php echo $sTglAkhir; ?>" maxlength="10" id="sel3" size="11">&nbsp;
		<input type="image" onclick="return showCalendar('sel3', 'y-mm-dd');" src="Images/calendar.gif"> 
		<span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTglAkhirError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan:"); ?></td>
		<td class="TextColumn" colspan="4"><input type="text" size=100 name="KetTambahan" id="KetTambahan" value="<?php echo htmlentities(stripslashes($sKetTambahan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKetTambahanError ?></font></td>

	</tr>	
	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Pendeta:"); ?></td>
		<td class="TextColumn"><input type="text" size="50" name="Pendeta" id="Pendeta" 
		value="<?php 
		if (strlen($iMasaBaktiMajelisID) > 0)
		{ echo htmlentities(stripslashes($sPendeta),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		jabatanpengurus(1);
		}
		 ?>"><br><font color="red"><?php echo $sKetuaError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Ketua Majelis:"); ?></td>
		<td class="TextColumn"><input type="text" size="50" name="Ketua" id="Ketua" 
		value="<?php 
		if (strlen($iMasaBaktiMajelisID) > 0)
		{ echo htmlentities(stripslashes($sKetua),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		jabatanpengurus(61);
		}
		 ?>"><br><font color="red"><?php echo $sKetuaError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Sekretaris Majelis:"); ?></td>
		<td class="TextColumn"><input type="text" size="50" name="Sekretaris" id="Sekretaris" 
		value="<?php 
		if (strlen($iMasaBaktiMajelisID) > 0)
		{ echo htmlentities(stripslashes($sSekretaris),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		jabatanpengurus(65);
		}
		 ?>"><br><font color="red"><?php echo $sSekretarisError ?></font></td>
	</tr>	
	

	</form>

</table>

<?php
		$logvar1 = "Edit";
		$logvar2 = "MasaBaktiMajelis Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMasaBaktiMajelisID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
