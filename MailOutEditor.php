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
	$sPanitia = FilterInput($_POST["Panitia"]);
	$sKetuaPanitia = FilterInput($_POST["KetuaPanitia"]);
	$sSekretarisPanitia = FilterInput($_POST["SekretarisPanitia"]);	
	$sIsiSuratBalasan = $_POST["IsiSuratBalasan"];
	$sIsiLampiran = $_POST["IsiLampiran"];
//	$sIsiSuratBalasan = FilterInput($_POST["IsiSuratBalasan"]);
	$sTglDibalas = FilterInput($_POST["TglDibalas"]);
	
	$sTglFax = FilterInput($_POST["TglFax"]);
	$sStatusFax = FilterInput($_POST["StatusFax"]);
	$sTglSurat = FilterInput($_POST["TglSurat"]);
	$sResiSurat = FilterInput($_POST["ResiSurat"]);
	$sStatusSurat = FilterInput($_POST["StatusSurat"]);
	$sTglTelp = FilterInput($_POST["TglTelp"]);
	$sPenerimaTelp = FilterInput($_POST["PenerimaTelp"]);
	$sStatusTelp = FilterInput($_POST["StatusTelp"]);
	$sTglEmail = FilterInput($_POST["TglEmail"]);

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
		//	 $sKet2 =  dec2roman(date (m)). "/" .date('Y');
			 
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";
				
			if 	($sKet3 == 17) {

$sSQL = "select	a.per_BirthDay as TglLahir, a.per_BirthMonth as BlnLahir,  a.per_BirthYear as ThnLahir, 
		a.per_id, 
		CONCAT(a.per_id,a.per_fam_id,a.per_gender,a.per_fmr_id) as NomorInduk,
		a.per_firstname as Nama, 
		a.per_WorkEmail as TempatLahir,
		CONCAT(a.per_BirthYear,'-',a.per_BirthMonth,'-',a.per_BirthDay) as TanggalLahir,
		a.per_Workemail as TempatLahir,
	
	       x.c16 as NamaAyah,
           x.c17 as NamaIbu,
		x.c1 as TanggalBaptis,
		x.c26 as TempatBaptis,
		x.c37 as DiBaptisOleh,
		x.c2 as TanggalSidhi,
		x.c27 as TempatSidhi,
		x.c38 as DiSidhiOleh,	
			a.per_WorkPhone as Kelompok,
	
		a.per_gender as JK , a.per_fam_id,
               CONCAT(b.fam_Address1,' ',b.fam_City,' ',b.fam_Zip) as Alamat,
               CONCAT(a.per_CellPhone,'/',b.fam_HomePhone) as Telepon


	
	
from person_per a 
left join person_custom x ON a.per_id = x.per_id 
left join family_fam b ON a.per_fam_id = b.fam_id 



		 WHERE a.per_ID = ".$sKet2;

$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

$sHal = $sHal." a.n.".$Nama;			
			
$sIsiSuratBalasan = "
<p align=\"left\">Bersama surat ini kami, Majelis $sChurchName, menerangkan bahwa :</p>
<p>Nama&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; <strong>
".$Nama." </strong></p>
<p>Tempat, tgl. lahir&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : 
".$TempatLahir.", ".date2Ind($TanggalLahir,2)."</p>
<p>Tempat, tgl. Baptis&nbsp;&nbsp; :&nbsp; 
".$TempatBaptis.", ".date2Ind($TanggalBaptis,2)."</p>
<p>Tempat, Tgl. Sidi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; 
".$TempatSidhi.", ".date2Ind($TanggalSidhi,2)."</p>
<p>Orang Tua&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; <strong>
".$NamaAyah."</strong>;&nbsp;&nbsp;&nbsp; (Ayah)</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;<strong>
".$NamaIbu."&nbsp;</strong>;&nbsp;&nbsp;&nbsp;(Ibu)</p>
<p>Alamat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : 
".$Alamat."</p>
<p>Telepon&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : 
".$Telepon."</p>
<p>&nbsp;</p>
<p>adalah benar warga jemaat $sChurchFullName, Kelompok <strong>
".$Kelompok."</strong> yang tercatat dalam stambuk No. <strong>".$NomorInduk."
</strong>.</p>
<p>Demikian surat ini kami buat untuk dipergunakan sebagaimana mestinya</p> ";

}
			
				

			$sSQL = "INSERT INTO SuratKeluar ( MailID, Tanggal, Urgensi, Via, Dari, Institusi, Kepada, Alamat1, Alamat2, Email, Telp, Fax, SifatSurat, Hal, Lampiran, TypeLampiran, Keterangan, FollowUp, Status, Ket1, Ket2, Ket3, Panitia, KetuaPanitia, SekretarisPanitia, IsiSuratBalasan, IsiLampiran, TglDibalas, 
			         TglFax, StatusFax, TglSurat, ResiSurat, StatusSurat, TglTelp, PenerimaTelp, StatusTelp, TglEmail, DateEntered, EnteredBy) 
			         VALUES ('" . $sMailID . "','" . $sTanggal . "','" . $sUrgensi . "','" . $sVia . "','" . $sDari . "','" . $sInstitusi . "',
					 '" . $sKepada . "','" . $sAlamat1 . "','" . $sAlamat2 . "','" . $sEmail . "','" . $sTelp . "','" . $sFax . "','" . $sSifatSurat . "','" . $sHal . "','" . $sLampiran . "','" . $sTypeLampiran . "','" . $sKeterangan . "','" . $sFollowUp . "',
					 '" . $sStatus . "','" . $sKet1 . "','" . $sKet2 . "','" . $sKet3 . "','" . $sPanitia . "','" . $sKetuaPanitia . "','" . $sSekretarisPanitia . "',
					 '" . $sIsiSuratBalasan . "','" . $sIsiLampiran . "','" . $sTglDibalas . "',
					 '" . $sTglFax . "','" . $sStatusFax . "','" . $sTglSurat . "','" . $sResiSurat . "','" . $sStatusSurat . "','" . $sTglTelp . "','" . $sPenerimaTelp . "','" . $sStatusTelp . "','" . $sTglEmail . "',
					 '" . date("YmdHis") . "'," . $_SESSION['iUserID'] . ")";
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
			Ket3 = '" . $sKet3 . "' ,Panitia = '" . $sPanitia . "' ,KetuaPanitia = '" . $sKetuaPanitia . "' ,SekretarisPanitia = '" . $sSekretarisPanitia . "' ,
			IsiSuratBalasan = '" . $sIsiSuratBalasan . "' ,IsiLampiran = '" . $sIsiLampiran . "' ,TglDibalas = '" . $sTglDibalas . "' ,
			TglFax = '" . $sTglFax  . "' ,StatusFax = '" . $sStatusFax  . "' ,TglSurat = '" . $sTglSurat  . "' ,ResiSurat = '" . $sResiSurat  . "' ,StatusSurat = '" . $sStatusSurat  . "' ,
			TglTelp = '" . $sTglTelp  . "' ,PenerimaTelp = '" . $sPenerimaTelp  . "' ,StatusTelp = '" . $sStatusTelp  . "' ,TglEmail = '" . $sTglEmail  . "' ,
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
			Redirect("SelectListApp.php?mode=mailout&amp;GID=$refresh");

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
	$sPanitia = $Panitia;
	$sKetuaPanitia = $KetuaPanitia;
	$sSekretarisPanitia = $SekretarisPanitia;
	$sIsiSuratBalasan = $IsiSuratBalasan;
	$sIsiLampiran = $IsiLampiran;
	$sTglDibalas = $TglDibalas;
	
	$sTglFax = $TglFax;
	$sStatusFax = $StatusFax;
	$sTglSurat = $TglSurat;
	$sResiSurat = $ResiSurat;
	$sStatusSurat = $StatusSurat;
	$sTglTelp = $TglTelp;
	$sPenerimaTelp = $PenerimaTelp;
	$sStatusTelp = $StatusTelp;
	$sTglEmail = $TglEmail;

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

//Get Kategori Surat Names for the drop-down
$sSQL = "select * from KlasifikasiSurat order by KlasID";
$rsKlasSurat = RunQuery($sSQL);

?>

<form method="post" action="MailOutEditor.php?MailID=<?php echo $iMailID; ?>" name="MailEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="MailSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"MailSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="mailCancel" onclick="javascript:document.location='<?php if (strlen($iMailID) > 0) 
{ echo "MailOutView.php?MailID=" . $iMailID."&amp;GID=$refresh"; 
} else {echo "SelectListApp.php?mode=mailout&amp;GID=$refresh"; 
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
				<td></td>
				<td></td>
				<td colspan="2" align="center"><h3><?php echo gettext("Tracking Surat: "); ?></h3></td>
			</tr>


	
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Surat:"); ?></td>
		<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalError ?></font></td>
				<td></td>
				<td></td>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal FAX:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglFax" value="<?php echo $sTglFax; ?>" maxlength="10" id="sel31" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel31', 'y-mm-dd');" src="Images/calendar.gif"> <font color="red"><?php echo $sTglFaxError ?></font></td>

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
				<td></td>
				<td></td>
		<td class="LabelColumn" ><?php echo gettext("Status FAX:"); ?></td>
		<td class="TextColumn" ><input type="text" name="StatusFax" id="StatusFax"  value="<?php echo htmlentities(stripslashes($sStatusFax),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sStatusFaxError ?></font></td>
		
	</tr>
	
	<?php 
	
if 	($iKategori == 17) {

		echo " <td class=\"LabelColumn\">Kategori:</td>";
		echo " <td class=\"TextColumnWithBottomBorder\">";
		echo "Surat Keterangan - Warga Jemaat"; 
	//	echo " 	<select name=\"Ket3\" >";
	//	echo " 		<option value=\"17\" "; if ($sKet3 == 17) { echo "selected"; };echo ">";echo "Surat Keterangan - Warga Jemaat"; echo "</option>";
	//	echo " 	</select>";
		echo " 	</td>	";
		
		//Get Pengirim Names for the drop-down
		$sSQL = "SELECT * FROM `person_per` WHERE `per_cls_id` < 3 ORDER BY per_FirstName ASC";
		$rsNamaWarga = RunQuery($sSQL);


		echo "<tr><td class=\"LabelColumn\">Nama Warga</td><td class=\"TextColumnWithBottomBorder\"> 
					<select name=\"Ket2\" >";
						
						while ($aRow = mysql_fetch_array($rsNamaWarga))
						{
							extract($aRow);

							echo "<option value=\"" . $per_ID . "\"";
							if ($sKet2 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone;
						}
						
		echo"			</select>	</select></td>	</tr>";	
		?>
		
	<input type="hidden" name="Ket3" value="17" />
	<input type="hidden" name="Via" value="Umum" />
	<input type="hidden" name="Dari" value="0" />
	<input type="hidden" name="Institusi" value="0" />
	<input type="hidden" name="Kepada" value="Pihak yang berwenang" />
	<input type="hidden" name="Alamat1" value="" />
	<input type="hidden" name="Alamat2" value="" />
	<input type="hidden" name="Email" value="" />
	<input type="hidden" name="Telp" value="" />
	<input type="hidden" name="Fax" value="" />
	<input type="hidden" name="Hal" value="Surat Keterangan - Warga Jemaat " />
	<input type="hidden" name="Lampiran" value="0" />
	<input type="hidden" name="TypeLampiran" value="0" />
	<input type="hidden" name="Keterangan" value="0" />
	<input type="hidden" name="FollowUp" value="0" />
	<input type="hidden" name="Status" value="0" />
	<input type="hidden" name="Ket1" value="0" />

	




		<?
			}
			else
			{ 
?>
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
	
		
		<td class="LabelColumn"><?php echo gettext("Umum / Gerejawi"); ?></td>
		<td class="TextColumn">			
				<select name="Via">
				<option value="Gerejawi"><?php echo gettext("Gerejawi"); ?></option>
				<option value="Umum" <?php if ($sVia == "Umum") { echo "selected"; } ?>><?php echo gettext("Umum"); ?></option>
				</select>

		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Surat:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglSurat" value="<?php echo $sTglSurat; ?>" maxlength="10" id="sel32" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel32', 'y-mm-dd');" src="Images/calendar.gif"> <font color="red"><?php echo $sTglSuratError ?></font></td>
		
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Pengirim:"); ?></td>
		<td class="TextColumnWithBottomBorder" colspans="2" >
					<select name="Dari" >
						<option value="0" <? if ($sDari == 0) { echo " selected"; }; ?>><?php echo gettext("Sekretariat"); ?></option>
						<option value="100" <? if ($sDari == 100) { echo " selected"; }; ?>><?php echo gettext("Panitia (PPPG/Natal/Paskah dll)"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPengirim))
						{
							extract($aRow);

							echo "<option value=\"" . $vol_ID . "\"";
							if ($sDari == $vol_ID) { echo " selected"; }
							echo ">". $KodePengirim." - ".$NamaPengirim;
						}
						?>
					</select>

					</select>
		</td>

		<td></td>
		<td></td>
		<td class="LabelColumn" ><?php echo gettext("Nomor Resi:"); ?></td>
		<td class="TextColumn" ><input type="text" name="ResiSurat" id="ResiSurat"  value="<?php echo htmlentities(stripslashes($sResiSurat),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sResiSuratError ?></font></td>
		
		</tr>
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Panitia (NamaEvent):"); ?></td>
		<td class="TextColumn" ><input type="text" name="Panitia" id="Panitia"  value="<?php echo htmlentities(stripslashes($sPanitia),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPanitiaError ?></font></td>

		<td></td>
		<td></td>
		<td class="LabelColumn" ><?php echo gettext("Pengirim Surat:"); ?></td>
		<td class="TextColumn" ><input type="text" name="StatusSurat" id="StatusSurat"  value="<?php echo htmlentities(stripslashes($sStatusSurat),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sStatusSuratError ?></font></td>

	</tr>
	<tr>

		<td class="LabelColumn"><?php echo gettext("Ketua Panitia :"); ?></td>
		<td class="TextColumn"><input type="text" name="KetuaPanitia" id="KetuaPanitia"  value="<?php echo htmlentities(stripslashes($sKetuaPanitia),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKetuaPanitiaError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("SekretarisPanitia:"); ?></td>
		<td class="TextColumn"><input type="text" name="SekretarisPanitia" id="SekretarisPanitia" value="<?php echo htmlentities(stripslashes($sSekretarisPanitia),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sSekretarisPanitiaError ?></font></td>

		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Telp:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglTelp" value="<?php echo $sTglTelp; ?>" maxlength="10" id="sel33" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel33', 'y-mm-dd');" src="Images/calendar.gif"> <font color="red"><?php echo $sTglTelpError ?></font></td>
	
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kepada :"); ?></td>
		<td class="TextColumn"><input type="text" name="Kepada" id="Kepada" size="50" value="<?php echo htmlentities(stripslashes($sKepada),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKepadaError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Institusi:"); ?></td>
		<td class="TextColumn"><input type="text" name="Institusi" id="Institusi" value="<?php echo htmlentities(stripslashes($sInstitusi),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sInstitusiError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Penerima Telp :"); ?></td>
		<td class="TextColumn"><input type="text" name="PenerimaTelp" id="PenerimaTelp"  value="<?php echo htmlentities(stripslashes($sPenerimaTelp),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPenerimaTelpError ?></font></td>
		
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Alamat 1:"); ?></td>
		<td class="TextColumn"><input type="text" name="Alamat1" id="Alamat1" size="50" value="<?php echo htmlentities(stripslashes($sAlamat1),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat1Error ?></font></td>
	
		<td class="LabelColumn"><?php echo gettext("Telp :"); ?></td>
		<td class="TextColumn"><input type="text" name="Telp" id="Telp" value="<?php echo htmlentities(stripslashes($sTelp),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTelpError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Status Telp :"); ?></td>
		<td class="TextColumn"><input type="text" name="StatusTelp" id="StatusTelp" value="<?php echo htmlentities(stripslashes($sStatusTelp),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sStatusTelpError ?></font></td>
		
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Alamat 2:"); ?></td>
		<td class="TextColumn"><input type="text" name="Alamat2" id="Alamat2" size="50" value="<?php echo htmlentities(stripslashes($sAlamat2),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat2Error ?></font></td>
	
		<td class="LabelColumn"><?php echo gettext("Fax :"); ?></td>
		<td class="TextColumn"><input type="text" name="Fax" id="Fax" value="<?php echo htmlentities(stripslashes($sFax),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sFaxError ?></font></td>

		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Email:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglEmail" value="<?php echo $sTglEmail; ?>" maxlength="10" id="sel34" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel34', 'y-mm-dd');" src="Images/calendar.gif"> <font color="red"><?php echo $sTglEmailError ?></font></td>
	
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Email :"); ?></td>
		<td class="TextColumn"><input type="text" name="Email" id="Email" size="50" value="<?php echo htmlentities(stripslashes($sEmail),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sEmailError ?></font></td>
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
<?php } ?>
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
