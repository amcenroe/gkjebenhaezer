<?php
/*******************************************************************************
 *
 *  filename    : FormulirUmumEditor.php
 *
*  2008 Erwin Pratama for GKJ Bekasi WIl Timur ( http://www.gkjbekasi-wiltimur.net )
*  2009 Erwin Pratama for GKJ Bekasi Timur ( http://www.gkjbekasitimur.org )
*  2010 Erwin Pratama for GKPB Bali ( http://www.balichurchsynod.org/ )
*  2013 Erwin Pratama for GKJ Tanjung Priok ( http://www.gkjtp.com )
*
 *  Sistem Informasi GKJ Bekasi Timur is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";

//Get the FormID out of the querystring
$iFormID = FilterInput($_GET["FormID"],'int');
$sKodeTI = FilterInput($_GET["KodeTI"],'int');
$Kategori = $_GET["Kategori"];
$iKategori = $_GET["Kategori"];
$sTanggal = $_GET["Tanggal"];
$sPukul = $_GET["Pukul"];

if ($sKodeTI==10){ $ExtTI = "SM.Jatimulya" ;} else { $ExtTI = ""; }
//Set the page title
$sPageTitle = gettext("Editor - FormulirUmum $Kategori $ExtTI GKJ Bekti ");

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?FormID= manually)
if (strlen($iFormID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsFormulirUmum = RunQuery($sSQL);
	extract(mysql_fetch_array($rsFormulirUmum));

	if (mysql_num_rows($rsFormulirUmum) == 0)
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

if (isset($_POST["FormulirUmumSubmit"]) || isset($_POST["FormulirUmumSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	
			$sFormID = FilterInput($_POST["FormID"]); 
			$sTanggal = FilterInput($_POST["Tanggal"]); 
			$sPukul = FilterInput($_POST["Pukul"]); 
			$sKodeTI = FilterInput($_POST["KodeTI"]); 
			$sper_ID = FilterInput($_POST["per_ID"]); 
			$sTanggalKembali = FilterInput($_POST["TanggalKembali"]); 
			$sTanggalDikembalikan = FilterInput($_POST["TanggalDikembalikan"]); 
			$sKeperluan = FilterInput($_POST["Keperluan"]); 
			$sKomisiID = FilterInput($_POST["KomisiID"]); 
			$sBidangLain = FilterInput($_POST["BidangLain"]); 
			$sAssetID = FilterInput($_POST["AssetID"]); 
			$sJumlah = FilterInput($_POST["Jumlah"]); 
			$sUnit = FilterInput($_POST["Unit"]); 
			$sKeterangan = FilterInput($_POST["Keterangan"]); 
			$sMajelis1 = FilterInput($_POST["Majelis1"]); 
			$sMajelis2 = FilterInput($_POST["Majelis2"]); 
			$sEnteredBy = FilterInput($_POST["EnteredBy"]); 
			$sEditedBy = FilterInput($_POST["EditedBy"]); 
			$sDateEntered = FilterInput($_POST["DateEntered"]); 
			$sDateLastEdited = FilterInput($_POST["DateLastEdited"]); 
  

	//Initialize the error flag
	$bErrorFlag = false;

	// Validate Mail Date if one was entered
	if (strlen($dTanggal) > 0)
	{
		$dateString = parseAndValidateDate($dTanggal, $locale = "US", $pasfut = "past");
		if ( $dateString === FALSE ) {
			$sTanggalError = "<span style=\"color: red; \">"
								. gettext("Not a valid Date") . "</span>";
			$bErrorFlag = true;
		} else {
			$dTanggal = $dateString;
		}
	}

	//If no errors, then let's update...
		// New Data (add)
		if (strlen($iFormID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO FormulirUmum" . $Kategori . "gkjbekti ( 
			Tanggal,
			Pukul,
			KodeTI,
			per_ID,
			TanggalKembali,
			TanggalDikembalikan,
			Keperluan,
			KomisiID,
			BidangLain,
			AssetID,
			Jumlah,
			Unit,
			Keterangan,
			Majelis1,
			Majelis2,
			DateEntered,
			EnteredBy
			)
			VALUES ( 
			'" . $sTanggal . "',
			'" . $sPukul . "',
			'" . $sKodeTI . "',
			'" . $sper_ID . "',
			'" . $sTanggalKembali . "',
			'" . $sTanggalDikembalikan . "',
			'" . $sKeperluan . "',
			'" . $sKomisiID . "',
			'" . $sBidangLain . "',
			'" . $sAssetID . "',
			'" . $sJumlah . "',
			'" . $sUnit . "',
			'" . $sKeterangan . "',
			'" . $sMajelis1 . "',
			'" . $sMajelis2 . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New FormulirUmum " . $Kategori . "Data";


		// Existing FormulirUmum (update)
		} else {
	
			$sSQL = "UPDATE FormulirUmum" . $Kategori . "gkjbekti SET 

			Tanggal = '" . $sTanggal . "',
			Pukul = '" . $sPukul . "',
			KodeTI = '" . $sKodeTI . "',
			per_ID = '" . $sper_ID . "',
			TanggalKembali = '" . $sTanggalKembali . "',
			TanggalDikembalikan = '" . $sTanggalDikembalikan . "',
			Keperluan = '" . $sKeperluan . "',
			KomisiID = '" . $sKomisiID . "',
			BidangLain = '" . $sBidangLain . "',
			AssetID = '" . $sAssetID . "',
			Jumlah = '" . $sJumlah . "',
			Unit = '" . $sUnit . "',
			Keterangan = '" . $sKeterangan . "',
			Majelis1 = '" . $sMajelis1 . "',
			Majelis2 = '" . $sMajelis2 . "',
			DateLastEdited = '" . date("YmdHis") . "', 
			EditedBy = '" . $_SESSION['iUserID'] ;
					
			$sSQL .= "' WHERE FormID = " . $iFormID;

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update FormulirUmum " . $Kategori . " Data";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iFormID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. FormulirUmumEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iFormID);
		}
		else if (isset($_POST["FormulirUmumSubmit"]))
		{
			//Send to the view of this FormulirUmum
			Redirect("SelectList.php?mode=FormulirUmum&Kategori=" .$Kategori."&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("FormulirUmumEditor.php?Kategori=" . $Kategori."&amp;$refresh");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iFormID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM FormulirUmum" . $Kategori . "gkjbekti  WHERE FormID = " . $iFormID;
		$rsFormulirUmum = RunQuery($sSQL);
		extract(mysql_fetch_array($rsFormulirUmum));
		
	//	echo $sSQL;

			$sFormID = $FormID;
			$sTanggal = $Tanggal;
			$sPukul = $Pukul;
			$sKodeTI = $KodeTI;
			$sper_ID = $per_ID;
			$sTanggalKembali = $TanggalKembali;
			$sTanggalDikembalikan = $TanggalDikembalikan;
			$sKeperluan = $Keperluan;
			$sKomisiID = $KomisiID;
			$sBidangLain = $BidangLain;
			$sAssetID = $AssetID;
			$sJumlah = $Jumlah;
			$sUnit = $Unit;
			$sKeterangan = $Keterangan;
			$sMajelis1 = $Majelis1;
			$sMajelis2 = $Majelis2;
			$sDateEntered = $DateEntered;
			$sEnteredBy = $EnteredBy;
			$sDateLastEdited = $DateLastEdited;
			$sEditedBy = $EditedBy;
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
	}
}

//Get Names for the drop-down
$sSQL1 = "SELECT * FROM person_per 
where per_cls_id < 3
ORDER BY per_FirstName";
$rsNamaWarga = RunQuery($sSQL1);

$sSQL2 = "SELECT * FROM asetgkjbekti a
LEFT JOIN LokasiTI b ON a.Location=b.KodeTI 
LEFT JOIN asetklasifikasi c ON a.AssetClass=c.ClassID 
LEFT JOIN asetruangan d ON a.StorageCode=d.StorageCode
LEFT JOIN asetstatus e ON a.Status=e.StatusCode 
order by ClassID, Merk, Type, KodeTI";
$rsNamaAset = RunQuery($sSQL2);

$sSQL3 = "select * from MasterKomisi a 
LEFT JOIN MasterBidang b ON a.BidangID = b.BidangID 
WHERE KomisiID < 20 
ORDER BY a.BidangID, a.KomisiID";
$rsKomisi = RunQuery($sSQL3);
		 
require "Include/Header.php";

?>

<form method="post" action="FormulirUmumEditor.php?FormID=<?php echo $iFormID; ?>&Kategori=<?php echo $Kategori; ?>" name="FormulirUmumEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan")?>" name="FormulirUmumSubmit" 
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"FormulirUmumSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal")?>" name="FormulirUmumCancel" onclick="javascript:document.location='<?php if (strlen($iFormID) > 0) 
{ echo "FormulirUmumView.php?FormID=" . $iFormID . "&Kategori=" . $Kategori."&amp;$refresh"; 
} else {echo "SelectList.php?mode=FormulirUmum&Kategori=" . $Kategori."&amp;$refresh"  ; 
} ?>';">

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
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Pinjam:"); ?></td>
				<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
				</td>
			
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Rencana Pengembalian:"); ?></td>
				<td class="TextColumn"><input type="text" name="TanggalKembali" value="<?php echo $sTanggalKembali; ?>" maxlength="10" id="sel2" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel2', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
				</td>
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Tempat Ibadah:"); ?></td>
				<td class="TextColumn">
					<select name="KodeTI">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Church Names for the drop-down
						$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
						$rsLokasiTI = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsLokasiTI))
						{
							extract($aRow);

							echo "<option value=\"" . $KodeTI . "\"";
							if ($sKodeTI == $KodeTI) { echo " selected"; }
							echo ">" . $NamaTI ;
						}
						?>
					</select>
				</td>
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Dikembalikan:"); ?></td>
				<td class="TextColumn"><input type="text" name="TanggalDikembalikan" value="<?php echo $sTanggalDikembalikan; ?>" maxlength="10" id="sel3" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel3', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
				</td>				
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Nama Peminjam:"); ?></td>
				<td colspan="3" class="TextColumn">
					<select name="per_ID" size="1">
						<option value="0" selected><?php echo gettext("Tidak Diketahui / Bukan Warga "); ?></option>
						<option value="0">-----------------------</option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaWarga))
						{
							extract($aRow);

							echo "<option value=\"" . $per_ID . "\"";
							if ($sper_ID == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName . " - " . $per_WorkPhone;
						}
						?>

					</select>
				</td>
			</tr>	
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Aset yang Dipinjam:"); ?></td>
				<td colspan="3" class="TextColumn">
					<select name="AssetID" size="1">
						<?php
						while ($aRow = mysql_fetch_array($rsNamaAset))
						{
							extract($aRow);
							echo "<option value=\"" . $AssetID . "\"";
							if ($sAssetID == $AssetID) { echo " selected"; }
							echo ">".$majorclass." - ".$minorclass." - ".$Merk." - ".$Type." - ".$Specification." - ".$NamaTI." - ".$StorageDesc;
						}
						?>

					</select>
				</td>
			</tr>				
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Bidang/Komisi:"); ?></td>
				<td colspan="3" class="TextColumn">
					<select name="KomisiID" size="1">
						<?php
						while ($aRow = mysql_fetch_array($rsKomisi))
						{
							extract($aRow);
							echo "<option value=\"" . $KomisiID . "\"";
							if ($sKomisiID == $KomisiID ) { echo " selected"; }
							echo ">$NamaBidang - $NamaKomisi" ;
						}
						?>

					</select>
				</td>
			</tr>		
			<tr>
				<td class="LabelColumn"><?php echo gettext("Kelompok/Bidang Lain:"); ?></td>
				<td class="TextColumn" colspan=3 ><input size="50" type="text" name="BidangLain" id="BidangLain" value="<?php echo htmlentities(stripslashes($sBidangLain),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sBidangLainError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Keperluan:"); ?></td>
				<td class="TextColumn" colspan=3 ><input size="80" type="text" name="Keperluan" id="Keperluan" value="<?php echo htmlentities(stripslashes($sKeperluan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKeperluanError ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Lokasi Penggunaan:"); ?></td>
				<td class="TextColumn" colspan=3 ><input size="80" type="text" name="Keterangan" id="Keterangan" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKeteranganError ?></font></td>
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
		$logvar2 = "FormulirUmum Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iFormID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
