<?php
/*******************************************************************************
 *
 *  filename    : PBUlananEditor.php
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
$sPageTitle = gettext("Editor - Persembahan Bulanan");

//Get the BulananID out of the querystring
$iBulananID = FilterInput($_GET["BulananID"],'int');
$iKategori = FilterInput($_GET["Kategori"],'int');

ob_start();
?>

<script language="javascript">  
function fncCreateElement(){  
  
var mySpan = document.getElementById('mySpan');  
  
var myElement1 = document.createElement('input');  
myElement1.setAttribute('type',"text");  
myElement1.setAttribute('name',"txtSiteName[]");  
mySpan.appendChild(myElement1);  

myElement2 = document.createElement('input');  
myElement2.setAttribute('type',"text");  
myElement2.setAttribute('name',"txtSiteName2[]");  
mySpan.appendChild(myElement2);  
 
myElement3 = document.createElement('input');  
myElement3.setAttribute('type',"text");  
myElement3.setAttribute('name',"txtSiteName3[]");  
mySpan.appendChild(myElement3);  

myElement4 = document.createElement('<br>');  
mySpan.appendChild(myElement4);  

}  
</script>  



<?php


// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?BulananID= manually)
if (strlen($iBulananID) > 0)
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




if (isset($_POST["BulananSubmit"]) || isset($_POST["BulananSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	

	$sBulananID = FilterInput($_POST["BulananID"]);
	$sTanggal = FilterInput($_POST["Tanggal"]);
	$sTanggalExp = FilterInput($_POST["TanggalExp"]);	
	$sUrgensi = FilterInput($_POST["Urgensi"]);
	$sNomorSurat = FilterInput($_POST["NomorSurat"]);
	$sSifatSurat = FilterInput($_POST["SifatSurat"]);
	$sHal = FilterInput($_POST["Hal"]);
	$sTembusan1 = FilterInput($_POST["Tembusan1"]);
	$sTembusan2 = FilterInput($_POST["Tembusan2"]);
	$sTembusan3 = FilterInput($_POST["Tembusan3"]);
	$sTembusan4 = FilterInput($_POST["Tembusan4"]);
	$sLampiran = FilterInput($_POST["Lampiran"]);
	$sTypeLampiran = FilterInput($_POST["TypeLampiran"]);
	$sIsiPersembahanBulanan = $_POST["IsiPersembahanBulanan"];
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
		if (strlen($iBulananID) < 1)
		{
			 $sKet1 = "e/MG-".$sDari."/".$sChurchCode."/";
		//	 $sKet2 =  dec2roman(date (m)). "/" .date('Y');
			 
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";
				
 $sPendeta = jabatanpengurus(1);
 $sKetua = jabatanpengurus(61);
 $sSekretaris = jabatanpengurus(65);
				
			$sSQL = "INSERT INTO PersembahanBulanan ( BulananID, Tanggal,  TanggalExp,Urgensi, NomorSurat, SifatSurat, Hal, Tembusan1, Tembusan2, Tembusan3, Tembusan4,
			Lampiran, TypeLampiran, IsiPersembahanBulanan, IsiLampiran, Pendeta, Ketua, Sekretaris, DateEntered, EnteredBy) 
			         VALUES ('" . $sBulananID . "','" . $sTanggal . "','" . $sTanggalExp . "','" . $sUrgensi . "','" . $sNomorSurat . "','" . $sSifatSurat . "','" . $sHal . "',
					 '" . $sTembusan1 . "','" . $sTembusan2 . "','" . $sTembusan3 . "','" . $sTembusan4 . "','" . $sLampiran . "','" . $sTypeLampiran . "',
					 '" . $sIsiPersembahanBulanan . "','" . $sIsiLampiran . "','" . $sPendeta . "','" . $sKetua . "','" . $sSekretaris . "','" . date("YmdHis") . "'," . $_SESSION['iUserID'] . ")";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "Buat Persembahan Bulanan Baru";

		// Existing mail (update)
		} else {
		
			$sSQL = "UPDATE PersembahanBulanan SET Tanggal = '" . $sTanggal . "',TanggalExp = '" . $sTanggalExp . "',Urgensi = '" . $sUrgensi  . "',NomorSurat = '" . $sNomorSurat  . "',
			SifatSurat = '" . $sSifatSurat  . "',Hal = '" . $sHal  . "',Tembusan1 = '" . $sTembusan1  . "',Tembusan2 = '" . $sTembusan2  . "',Tembusan3 = '" . $sTembusan3  . "',Tembusan4 = '" . $sTembusan4  . "',
			Lampiran = '" . $sLampiran  . "',TypeLampiran = '" . $sTypeLampiran  . "',
			IsiPersembahanBulanan = '" . $sIsiPersembahanBulanan . "' ,IsiLampiran = '" . $sIsiLampiran . "' ,
			DateLastEdited = '" . date("YmdHis") . "', EditedBy = '" . $_SESSION['iUserID'] ;
			
			$sSQL .= "' WHERE BulananID = " . $iBulananID;

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Persembahan Bulanan";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iBulananID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Update the custom mail fields.

		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iBulananID);
		}
		else if (isset($_POST["BulananSubmit"]))
		{
			Redirect("SelectListApp.php?mode=pbulanan&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PBulananEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iBulananID) > 0)
	{
		//Editing....
		//Get all the data on this record

$sSQL = "SELECT a.* FROM PersembahanBulanan a
		WHERE BulananID = " . $iBulananID;
		$rsmail = RunQuery($sSQL);
		extract(mysql_fetch_array($rsmail));

	$sBulananID = $BulananID;
	$sTanggal = $Tanggal;
	$sTanggalExp = $TanggalExp;	
	$sUrgensi = $Urgensi;
	$sNomorSurat = $NomorSurat;
	$sSifatSurat = $SifatSurat;
	$sHal = $Hal;
	$sTembusan1 = $Tembusan1;
	$sTembusan2 = $Tembusan2;
	$sTembusan3 = $Tembusan3;
	$sTembusan4 = $Tembusan4;
	$sLampiran = $Lampiran;
	$sTypeLampiran = $TypeLampiran;
	$sIsiPersembahanBulanan = $IsiPersembahanBulanan;
	$sIsiLampiran = $IsiLampiran;

	
						$time  = strtotime($Tanggal);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat2 =  $BulananID."/MG/SK/".$NomorSurat."/".$sChurchCode."/".dec2roman($month)."/".$year;
	
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

<form method="post" action="PBulananEditor.php?BulananID=<?php echo $iBulananID; ?>" name="PBulananEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="BulananSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"BulananSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="mailCancel" onclick="javascript:document.location='<?php if (strlen($iBulananID) > 0) 
{ echo "PBulananView.php?BulananID=" . $iBulananID."&amp;$refresh"; 
} else {echo "SelectListApp.php?mode=pbulanan&amp;$refresh"; 
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
						$NomorSurat2 =  $BulananID."/MG/SK/".$NomorSurat."/".$sChurchCode."/".dec2roman($month)."/".$year;
				
				echo gettext("Data Standar - Nomor Persembahan Bulanan: "); echo $NomorSurat2; ?></h3></td>
			</tr>


	
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Persembahan:"); ?></td>
		<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalError ?></font></td>

	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kelompok :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="Urgensi">
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				<option value="1" <?php if ($sUrgensi == 1) { echo "selected"; } ?>><?php echo gettext("BTRG"); ?></option>
				<option value="2" <?php if ($sUrgensi == 2) { echo "selected"; } ?>><?php echo gettext("JTMY"); ?></option>
				<option value="3" <?php if ($sUrgensi == 3) { echo "selected"; } ?>><?php echo gettext("Biasa"); ?></option>
			</select>
		</td>	
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kode:"); ?></td>
		<td colspan="4"  class="TextColumn"><input type="text" name="Kode" id="Kode" value="<?php echo htmlentities(stripslashes($sKode),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKodeError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Jumlah Persembahan:"); ?></td>
		<td class="TextColumn"><input type="text" name="Persembahan" id="Persembahan" value="<?php echo htmlentities(stripslashes($sPersembahan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sPersembahanError ?></font></td>
	</tr>

	</form>
	
	<form action="php_multiple_textbox4.php" method="post" name="form1">  
		<input type="text" name="txtSiteName[]">  
		<input type="text" name="txtSiteName2[]">  
		<input type="text" name="txtSiteName3[]">  
		<input name="btnButton" type="button" value="+" onClick="JavaScript:fncCreateElement();"><br>  
		<span id="mySpan"></span>  
		<input name="btnSubmit" type="submit" value="Submit">  
	</form>

</table>

<?php
		$logvar1 = "Edit";
		$logvar2 = "Persembahan Bulanan Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iBulananID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
//require "Include/Footer.php";
?>
