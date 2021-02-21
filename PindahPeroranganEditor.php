<?php
/*******************************************************************************
 *
 *  filename    : PindahPeroranganEditor.php
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
 *  Sistem Informasi GKJ Bekasi Timur is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/
ob_start();
//Include the function library
require "Include/Config.php";
require "Include/Functions.php";

//Set the page title
$sPageTitle = gettext("Permohonan Pindah Warga (Perorangan)");
$iPersonID = FilterInput($_GET["PersonID"],'int');
//Get the PindahID out of the querystring
$iPindahID = FilterInput($_GET["PindahID"],'int');

$TAHUN=(DATE("Y"));
$BULAN=dec2roman(date (m));
// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PindahID= manually)
if (strlen($iPindahID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsPindah = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPindah));

	if (mysql_num_rows($rsPindah) == 0)
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

if (isset($_POST["PindahSubmit"]) || isset($_POST["PindahSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sPindahID = FilterInput($_POST["PindahID"]);
	$sper_ID = FilterInput($_POST["per_ID"]);
	$sPendeta = FilterInput($_POST["Pendeta"]);
	$sKetuaMajelis = FilterInput($_POST["KetuaMajelis"]);
	$sSekretarisMajelis = FilterInput($_POST["SekretarisMajelis"]);
	$sTanggalPindah = FilterInput($_POST["TanggalPindah"]);
	$sKeterangan = FilterInput($_POST["Keterangan"]);
	
	$sAlamat1Baru = FilterInput($_POST["Alamat1Baru"]);
	$sAlamat2Baru = FilterInput($_POST["Alamat2Baru"]);
	$sAlamat3Baru = FilterInput($_POST["Alamat3Baru"]);
	$sTelpBaru = FilterInput($_POST["TelpBaru"]);
	
	$sGerejaID = FilterInput($_POST["GerejaID"]);
	$sNamaGerejaNonGKJ = FilterInput($_POST["NamaGerejaNonGKJ"]);
	$sAlamat1NonGKJ = FilterInput($_POST["Alamat1NonGKJ"]);
	$sAlamat2NonGKJ = FilterInput($_POST["Alamat2NonGKJ"]);
	$sAlamat3NonGKJ = FilterInput($_POST["Alamat3NonGKJ"]);
	$sTelpNonGKJ = FilterInput($_POST["TelpNonGKJ"]);
	$sFaxNonGKJ = FilterInput($_POST["FaxNonGKJ"]);
	$sEmailNonGKJ = FilterInput($_POST["EmailNonGKJ"]);
	
	$sPindahKe = FilterInput($_POST["PindahKe"]);
	$sReffSuratPindah = FilterInput($_POST["ReffSuratPindah"]);
	$sAlasanPindah = FilterInput($_POST["AlasanPindah"]);
	
	$sDateLastEdited = FilterInput($_POST["DateLastEdited"]);
	$sDateEntered = FilterInput($_POST["DateEntered"]);
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
		if (strlen($iPindahID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO PermohonanPindahgkjbekti ( 
			PindahID,
			per_ID,
			TanggalPindah,
			Pendeta,
			KetuaMajelis,
			SekretarisMajelis,
			Keterangan,
			Alamat1Baru,
			Alamat2Baru,
			Alamat3Baru,
			TelpBaru,
			GerejaID,
			NamaGerejaNonGKJ,
			Alamat1NonGKJ,
			Alamat2NonGKJ,
			Alamat3NonGKJ,
			TelpNonGKJ,
			FaxNonGKJ,
			EmailNonGKJ,
			DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sPindahID . "',
			'" . $sper_ID . "',
			'" . $sTanggalPindah . "',
			'" . $sPendeta . "',
			'" . $sKetuaMajelis . "',
			'" . $sSekretarisMajelis . "',
			'" . $sKeterangan . "',
			'" . $sAlamat1Baru . "',
			'" . $sAlamat2Baru . "',
			'" . $sAlamat3Baru . "',
			'" . $sTelpBaru . "',			
			'" . $sGerejaID . "',
			'" . $sNamaGerejaNonGKJ . "',
			'" . $sAlamat1NonGKJ . "',
			'" . $sAlamat2NonGKJ . "',
			'" . $sAlamat3NonGKJ . "',
			'" . $sTelpNonGKJ . "',
			'" . $sFaxNonGKJ . "',
			'" . $sEmailNonGKJ . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Warga Pindah Data";


		// Existing Pindah (update)
		} else {
	//update the Pindah table
			$sSQL = "UPDATE PermohonanPindahgkjbekti SET 
			
					per_ID = '" . $sper_ID . "',
					TanggalPindah = '" . $sTanggalPindah . "',
					Pendeta = '" . $sPendeta . "',
					KetuaMajelis = '" . $sKetuaMajelis . "',
					SekretarisMajelis = '" . $sSekretarisMajelis . "',
					Keterangan = '" . $sKeterangan . "',
					Alamat1Baru = '" . $sAlamat1Baru . "',
					Alamat2Baru = '" . $sAlamat2Baru . "',
					Alamat3Baru = '" . $sAlamat3Baru . "',
					TelpBaru = '" . $sTelpBaru . "',
					GerejaID = '" . $sGerejaID . "',
					NamaGerejaNonGKJ = '" . $sNamaGerejaNonGKJ . "',
					Alamat1NonGKJ = '" . $sAlamat1NonGKJ . "',
					Alamat2NonGKJ = '" . $sAlamat2NonGKJ . "',
					Alamat3NonGKJ = '" . $sAlamat3NonGKJ . "',
					TelpNonGKJ = '" . $sTelpNonGKJ . "',
					FaxNonGKJ = '" . $sFaxNonGKJ . "',
					EmailNonGKJ = '" . $sEmailNonGKJ . "',
					DateLastEdited = '" . date("YmdHis") . "',
					EditedBy = '" . $_SESSION['iUserID'] ;

			$sSQL .= "' WHERE PindahID = " . $iPindahID;

		//	echo $sSQL;
			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Warga Pindah Data";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		
// 34 	c10 	Tanggal Pindah ( Attestasi Keluar )
// 35 	c9 		PindahKe ( Attestasi Keluar )
// 36 	c40 	Reff Surat Pindah ( Attestasi Keluar )
// 37 	c48 	Alasan Kepindahan (Attestasi Keluar ) 	
		
//	".strftime( "%Y", strtotime($TGL))"	
					// update the main database
				$sSQLGKJ = "SELECT * FROM DaftarGerejaGKJ  WHERE GerejaID = " . $sGerejaID . " LIMIT 1";
				$rsGKJ = RunQuery($sSQLGKJ);
				extract(mysql_fetch_array($rsGKJ));
				$sNamaGereja = $NamaGereja;	
	
			//Ambil data utk reff surat
				$sSQL0 = "SELECT * FROM PermohonanPindahgkjbekti  WHERE per_ID = " . $sper_ID . " LIMIT 1";
				$rsNoReff = RunQuery($sSQL0);
				extract(mysql_fetch_array($rsNoReff));
				$sNomorReff = $PindahID;			
				

				// set klasifikasi pindah	
		$sSQL1 = "UPDATE person_per SET per_cls_id = '6' WHERE per_ID=" . $sper_ID;
		RunQuery($sSQL1);	

			// set nama gereja tujuan dan alasan
		$sSQL2 = "UPDATE person_custom  SET 
					c10 = '" . $sTanggalPindah . "',					
					c9 = '" . $sNamaGereja. "',
					c48 = '" . $sAlasanPindah ."',
					c40 = '" . $sNomorReff. "e/SKPP/".$sChurchCode."/".$BULAN."/".$TAHUN."' WHERE per_ID =" . $sper_ID;
					
		//echo $sSQL2;
		RunQuery($sSQL2);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPindahID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PindahPeroranganEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPindahID);
		}
		else if (isset($_POST["PindahSubmit"]))
		{
			//Send to the view of this Pindah
			Redirect("SelectListApp.php?mode=Pindah&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PindahPeroranganEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPindahID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT *,c.c48 as AlasanPindah  FROM PermohonanPindahgkjbekti  a
		LEFT JOIN person_per b ON a.per_ID = b.per_ID 
		LEFT JOIN person_custom c ON a.per_ID = c.per_ID 
		WHERE PindahID = " . $iPindahID;
		$rsPindah = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPindah));

		$sPindahID = $PindahID;
		$sper_ID = $per_ID;
		$sTanggalPindah = $TanggalPindah;
		$sPendeta = $Pendeta;
		$sKetuaMajelis = $KetuaMajelis;
		$sSekretarisMajelis = $SekretarisMajelis;
		$sKeterangan = $Keterangan;
		$sAlasanPindah = $AlasanPindah;		
		
		$sAlamat1Baru = $Alamat1Baru;	
		$sAlamat2Baru = $Alamat2Baru;	
		$sAlamat3Baru = $Alamat3Baru;	
		$sTelpBaru = $TelpBaru;
		
		$sGerejaID = $GerejaID;
		$sNamaGerejaNonGKJ = $NamaGerejaNonGKJ;	
		$sAlamat1NonGKJ = $Alamat1NonGKJ;	
		$sAlamat2NonGKJ = $Alamat2NonGKJ;	
		$sAlamat3NonGKJ = $Alamat3NonGKJ;	
		$sTelpNonGKJ = $TelpNonGKJ;
		$sFaxNonGKJ = $FaxNonGKJ;
		$sEmailNonGKJ = $EmailNonGKJ;
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today

	}
}

//Get Student Names for the drop-down
//$sSQL = "SELECT * FROM person_per a JOIN family_fam b ON a.per_fam_ID=b.fam_ID WHERE (per_cls_ID <3 AND per_fmr_ID >2 ) ORDER BY per_firstname";

	if (strlen($iPindahID) > 0)
	{
	$sSQL = "select * 
	from person_per a 
	where per_id = ".$per_ID." order by per_firstname 
	";
	}
	else
	{
	$sSQL = "select * 
	from person_per a 
	where per_cls_id = 1 order by per_firstname ";
}

$rsNamaPemohonPindah = RunQuery($sSQL);

//Get Pendeta Names for the drop-down
$sSQL = "SELECT * FROM DaftarPendeta a
LEFT JOIN DaftarGerejaGKJ b ON a.GerejaID=b.GerejaID
ORDER BY PendetaID";
$rsNamaPendeta = RunQuery($sSQL);

//Get Daftar GKJ Names for the drop-down
$sSQL = "SELECT * FROM DaftarGerejaGKJ a 
LEFT JOIN DaftarKlasisGKJ b ON a.KlasisID=b.KlasisID WHERE GerejaID > 1 
ORDER BY GerejaID, NamaGereja";
$rsNamaGereja = RunQuery($sSQL);

// Get Nama Pejabat



require "Include/Header.php";

?>

<form method="post" action="PindahPeroranganEditor.php?PindahID=<?php echo $iPindahID; ?>" name="PindahEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="PindahSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"PindahSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iPindahID) > 0) 
{ echo "SelectListApp.php?mode=Pindah&amp;$refresh"; 
} else {echo "SelectListApp.php?mode=Pindah&amp;$refresh"; 
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
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Permohonan Pindah:"); ?></td>
		<td class="TextColumn" colspan="3" ><input type="text" name="TanggalPindah" value="<?php echo $sTanggalPindah; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalPindahError ?></font></td>
	</tr>	
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Nama Pemohon Pindah <br> (Perorangan):"); ?></td>
				<td colspan="3" class="TextColumn">
					<select name="per_ID" size="10">
						<?php
						while ($aRow = mysql_fetch_array($rsNamaPemohonPindah))
						{
							extract($aRow);

							echo "<option value=\"" . $per_ID . "\"";
							if ($sper_ID == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName . "&nbsp; - " . $per_WorkPhone;
						}
						?>

					</select>
				</td>
			</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Alasan Kepindahan:"); ?></td>
		<td class="TextColumn" colspan="5" ><input type="text" size="100" name="AlasanPindah" id="AlasanPindah" value="<?php echo htmlentities(stripslashes($sAlasanPindah),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlasanPindahError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan:"); ?></td>
		<td class="TextColumn" colspan="5" ><input type="text" size="80" name="Keterangan" id="Keterangan" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKeteranganError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Alamat Tempat Tinggal Baru:"); ?></td>
		<td class="TextColumn" colspan="5" ><input type="text" size="60" name="Alamat1Baru" id="Alamat1Baru" value="<?php echo htmlentities(stripslashes($sAlamat1Baru),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat1BaruError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Jl/Kec/Kel/RT/RW"); ?></td>
		<td class="TextColumn" colspan="5" ><input type="text" size="60" name="Alamat2Baru" id="Alamat2Baru" value="<?php echo htmlentities(stripslashes($sAlamat2Baru),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat2BaruError ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kota/Kab/Kode POS"); ?></td>
		<td class="TextColumn" colspan="5" ><input type="text" size="60" name="Alamat3Baru" id="Alamat3Baru" value="<?php echo htmlentities(stripslashes($sAlamat3Baru),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat3BaruError ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Telp / HP:"); ?></td>
		<td class="TextColumn" colspan="5" ><input type="text" size="50" name="TelpBaru" id="TelpBaru" value="<?php echo htmlentities(stripslashes($sTelpBaru),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTelpBaruError ?></font></td>
	</tr>	
	
	<tr><td><tr>
		<td class="LabelColumn"><?php echo gettext("Pendeta:"); ?></td>
		<td class="TextColumn"><input type="text" name="Pendeta" id="Pendeta" 
		value="<?php 
		if (strlen($iPindahID) > 0)
		{ echo htmlentities(stripslashes($sPendeta),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		echo jabatanpengurus(1);
		}
		 ?>"><br><font color="red"><?php echo $sPendetaError ?></font></td>
	
		<td class="LabelColumn"><?php echo gettext("Ketua Majelis:"); ?></td>
		<td class="TextColumn"><input type="text" name="KetuaMajelis" id="KetuaMajelis" 
		value="<?php 
		if (strlen($iPindahID) > 0)
		{ echo htmlentities(stripslashes($sKetuaMajelis),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		echo jabatanpengurus(61);
		}
		 ?>"><br><font color="red"><?php echo $sKetuaMajelisError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Sekretaris Majelis:"); ?></td>
		<td class="TextColumn"><input type="text" name="SekretarisMajelis" id="SekretarisMajelis" 
		value="<?php 
		if (strlen($iPindahID) > 0)
		{ echo htmlentities(stripslashes($sSekretarisMajelis),ENT_NOQUOTES, "UTF-8"); 
		}else
		{
		echo jabatanpengurus(65);
		}
		 ?>"><br><font color="red"><?php echo $sSekretarisMajelisError ?></font></td>
		</tr>	
		</td></tr>
	</tr>
	<tr>
			<td colspan="6" align="center"><h3><?php echo gettext("Isikan Data Gereja Tujuan"); ?></h3></td>
	</tr>	
		
	<tr>
		<td class="LabelColumn" ><?php echo gettext("Pindah ke Gereja :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspan="3" >
					<select name="GerejaID" >
						<option value="0" selected><?php echo gettext("Non GKJ"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaGereja))
						{
							extract($aRow);

							echo "<option value=\"" . $GerejaID . "\"";
							if ($sGerejaID == $GerejaID) { echo " selected"; }
							echo ">" . $NamaGereja." - ".$NamaKlasis;
						}
						?>
					</select>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nama Gereja non GKJ:"); ?></td>
		<td class="TextColumn"><input type="text" name="NamaGerejaNonGKJ" id="NamaGerejaNonGKJ" value="<?php echo htmlentities(stripslashes($sNamaGerejaNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaGerejaNonGKJError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Telp:"); ?></td>
		<td class="TextColumn"><input type="text" name="TelpNonGKJ" id="TelpNonGKJ" value="<?php echo htmlentities(stripslashes($sTelpNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTelpNonGKJError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Alamat Gereja non GKJ:"); ?></td>
		<td class="TextColumn"><input type="text" name="Alamat1NonGKJ" id="Alamat1NonGKJ" value="<?php echo htmlentities(stripslashes($sAlamat1NonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat1NonGKJError ?></font></td>
	
		<td class="LabelColumn"><?php echo gettext("Fax:"); ?></td>
		<td class="TextColumn"><input type="text" name="FaxNonGKJ" id="FaxNonGKJ" value="<?php echo htmlentities(stripslashes($sFaxNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sFaxNonGKJError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kota/Kab:"); ?></td>
		<td class="TextColumn"><input type="text" name="Alamat2NonGKJ" id="Alamat2NonGKJ" value="<?php echo htmlentities(stripslashes($sAlamat2NonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat2NonGKJError ?></font></td>

		<td class="LabelColumn"><?php echo gettext("Email:"); ?></td>
		<td class="TextColumn"><input type="text" name="EmailNonGKJ" id="EmailNonGKJ" value="<?php echo htmlentities(stripslashes($sEmailNonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sEmailNonGKJError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kode POS:"); ?></td>
		<td class="TextColumn"><input type="text" name="Alamat3NonGKJ" id="Alamat3NonGKJ" value="<?php echo htmlentities(stripslashes($sAlamat3NonGKJ),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamat3NonGKJError ?></font></td>
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
		$logvar2 = "Pendaftaran Pindah Anak Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPindahID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
//require "Include/Footer.php";
?>
<? ob_flush(); ?>
