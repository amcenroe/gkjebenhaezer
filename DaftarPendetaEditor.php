<?php
/*******************************************************************************
 *
 *  filename    : DaftarPendetaEditor.php
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
 *  Sistem Informasi GKJ Bekasi Timur is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";

//Set the page title
$sPageTitle = gettext("Daftar Pendeta ");

//Get the PendetaID out of the querystring
$iPendetaID = FilterInput($_GET["PendetaID"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PendetaID= manually)
if (strlen($iPendetaID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsBaptis = RunQuery($sSQL);
	extract(mysql_fetch_array($rsBaptis));

	if (mysql_num_rows($rsBaptis) == 0)
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

if (isset($_POST["PendetaSubmit"]) || isset($_POST["PendetaSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sPendetaID = FilterInput($_POST["PendetaID"]);
	$sSalutation = FilterInput($_POST["Salutation"]);
	$sNamaPendeta = FilterInput($_POST["NamaPendeta"]);
	$sGerejaID = FilterInput($_POST["GerejaID"]);
	$sKeterangan = FilterInput($_POST["Keterangan"]);
	$sHP = FilterInput($_POST["HP"]);
	$sEmailPendeta = FilterInput($_POST["EmailPendeta"]);

	$sDateLastEdited = FilterInput($_POST["DateLastEdited"]);
	$sDateEntered  = FilterInput($_POST["DateEntered"]);
	$sEnteredBy = FilterInput($_POST["EnteredBy"]);
	$sEditedBy = FilterInput($_POST["EditedBy"]);
	
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
		// New Data (add)
		if (strlen($iPendetaID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO DaftarPendeta ( 		

			Salutation,
			NamaPendeta,
			GerejaID,
			Keterangan,
			HP,
			EmailPendeta,			
			
			DateEntered,
			EnteredBy	)
			VALUES ( 
						
			'" . $sSalutation . "',
			'" . $sNamaPendeta . "',
			'" . $sGerejaID . "',
			'" . $sKeterangan . "',
			'" . $sHP . "',
			'" . $sEmailPendeta . "',
			
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Pendeta Data";


		// Existing Baptis (update)
		} else {
	//update the Baptis table
			$sSQL = "UPDATE DaftarPendeta SET 
	GerejaID = '" . $sGerejaID . "',			
	Salutation = '" . $sSalutation . "',
	NamaPendeta = '" . $sNamaPendeta . "',
	Keterangan = '" . $sKeterangan . "',
	HP = '" . $sHP . "',
	EmailPendeta = '" . $sEmailPendeta . "',
	
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE PendetaID = " . $iPendetaID;

		//	echo $sSQL;
	

			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Data Pendeta";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
	
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPendetaID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. DaftarPendetaEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPendetaID);
		}
		else if (isset($_POST["PendetaSubmit"]))
		{
			//Send to the view of this PendetaData
			Redirect("SelectListApp.php?mode=DaftarPendeta");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("DaftarPendetaEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPendetaID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT a.GerejaID, a.Salutation as Salutation,
		a.NamaPendeta as NamaPendeta, 
		a.HP as HP, a.EmailPendeta as EmailPendeta,
        b.NamaGereja, c.NamaKlasis, 
		b.Alamat1 as Alamat1, b.Alamat2 as Alamat2, b.Alamat3 as Alamat3,
		b.Telp as Telp, b.Fax as Fax, b.Email as Email 
		FROM DaftarPendeta a
		LEFT JOIN DaftarGerejaGKJ b ON a.GerejaID = b.GerejaID
		LEFT JOIN DaftarKlasisGKJ c ON b.KlasisID = c.KlasisID
		WHERE PendetaID =" . $iPendetaID;
		
		$rsPendeta = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPendeta));
		
		$sGerejaID = $GerejaID;
		$sSalutation = $Salutation;
		$sNamaPendeta = $NamaPendeta;
		$sHP = $HP;
		$sEmailPendeta = $EmailPendeta;
		$sKeterangan = $Keterangan;
		

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

ORDER BY b.KlasisID, a.GerejaID";
$rsNamaGereja = RunQuery($sSQL);

//Get Lokasi TI Names for the drop-down
//$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
//$rsNamaTempatIbadah = RunQuery($sSQL);
// Get Nama Pejabat



require "Include/Header.php";

?>

<form method="post" action="DaftarPendetaEditor.php?PendetaID=<?php echo $iPendetaID; ?>" name="DaftarPendetaEditor">

<table cellpadding="3" align="center" valign="top" >


			
	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="PendetaSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"PendetaSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iPendetaID) > 0) 
{ echo "SelectListApp.php?mode=DaftarPendeta"; 
} else {echo "SelectListApp.php?mode=DaftarPendeta"; 
} ?>';">
		</td>
	</tr>

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"4\""; ?> align="center">
		<?php if ( $bErrorFlag ) echo "<span class=\"LargeText\" style=\"color: red;\">" . gettext("Ada keSALAHan pengisian atau pilihan. Perubahan tidak akan disimpan! Silahkan diKOREKSI dan dicoba lagi!") . "</span>"; ?>
		</td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" valign="top" border="0" >
			<tr>
				<td colspan="4" align="center"><h3><?php echo gettext("Data Standar"); ?></h3></td>
			</tr>
	<tr>	
		<td class="LabelColumn"><?php echo gettext("Salutation :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="Salutation" >
						<option value="Bp."  <?php if ($sSalutation == "Bp.") { echo " selected"; }?> ><?php echo gettext("Bp."); ?></option>
						<option value="Ibu."  <?php if ($sSalutation == "Ibu.") { echo " selected"; }?> ><?php echo gettext("Ibu."); ?></option>
					</select>
		</td>
	<tr>
		<td class="LabelColumn"><?php echo gettext("NamaPendeta"); ?></td>
		<td class="TextColumn"><input type="text" name="NamaPendeta"  size=80 id="NamaPendeta" value="<?php echo htmlentities(stripslashes($sNamaPendeta),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sNamaPendetaError ?></font></td>
	</tr>
	<tr>	
		<td class="LabelColumn"><?php echo gettext("Nama Gereja :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="GerejaID" >
						<option value="" selected><?php echo gettext("Tidak Diketahui"); ?></option>
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
	<tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("HP"); ?></td>
		<td class="TextColumn"><input type="text" name="HP" id="HP" value="<?php echo htmlentities(stripslashes($sHP),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sHPError ?></font></td>
	</tr>			
	<tr>
		<td class="LabelColumn"><?php echo gettext("EmailPendeta"); ?></td>
		<td class="TextColumn"><input type="text" name="EmailPendeta" size=80 id="EmailPendeta" value="<?php echo htmlentities(stripslashes($sEmailPendeta),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sEmailPendetaError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan"); ?></td>
		<td class="TextColumn"><input type="text" name="Keterangan" size=80  id="Keterangan" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>"><br>
		<font color="red"><?php echo $sKeteranganError ?></font></td>
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
		$logvar2 = "Daftar Pendeta Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPendetaID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
