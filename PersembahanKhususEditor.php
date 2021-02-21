<?php
/*******************************************************************************
 *
 *  filename    : PersembahanAnakEditor.php
 *
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

//Get the Persembahan_ID out of the querystring
$iPersembahan_ID = FilterInput($_GET["Persembahan_ID"],'int');
$sKodeTI = FilterInput($_GET["KodeTI"],'int');
$Kategori = $_GET["Kategori"];
$iKategori = $_GET["Kategori"];
$sTanggal = $_GET["Tanggal"];
$sPukul = $_GET["Pukul"];

if ($sKodeTI==10){ $ExtTI = "SM.Jatimulya" ;} else { $ExtTI = ""; }
//Set the page title
$sPageTitle = gettext("Editor - Persembahan $Kategori $ExtTI GKJ Bekti ");

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?Persembahan_ID= manually)
if (strlen($iPersembahan_ID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsPersembahan = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPersembahan));

	if (mysql_num_rows($rsPersembahan) == 0)
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

if (isset($_POST["PersembahanSubmit"]) || isset($_POST["PersembahanSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	

			$sPersembahan_ID = FilterInput($_POST["Persembahan_ID"]); 
			$sTanggal = FilterInput($_POST["Tanggal"]); 
			$sPukul = FilterInput($_POST["Pukul"]); 
			$sKodeTI = FilterInput($_POST["KodeTI"]); 
			$sPengkotbah = FilterInput($_POST["Pengkotbah"]); 
			$sBacaan1 = FilterInput($_POST["Bacaan1"]); 
			$sBacaan2 = FilterInput($_POST["Bacaan2"]); 
			$sNas = FilterInput($_POST["Nas"]); 
			$sNyanyian1 = FilterInput($_POST["Nyanyian1"]); 
			$sNyanyian2 = FilterInput($_POST["Nyanyian2"]); 
			$sNyanyian3 = FilterInput($_POST["Nyanyian3"]); 
			$sNyanyian4 = FilterInput($_POST["Nyanyian4"]); 
			$sNyanyian5 = FilterInput($_POST["Nyanyian5"]); 
			$sNyanyian6 = FilterInput($_POST["Nyanyian6"]); 
			$sNyanyian7 = FilterInput($_POST["Nyanyian7"]); 
			$sNyanyian8 = FilterInput($_POST["Nyanyian8"]); 
			$sNyanyian9 = FilterInput($_POST["Nyanyian9"]); 
			$sNyanyian10 = FilterInput($_POST["Nyanyian10"]); 
			$sPersembahan = FilterInput($_POST["Persembahan"]); 
			$sPria = FilterInput($_POST["Pria"]); 
			$sWanita = FilterInput($_POST["Wanita"]); 
			$sMajelis1 = FilterInput($_POST["Majelis1"]); 
			$sMajelis2 = FilterInput($_POST["Majelis2"]); 
			$sDateEntered = FilterInput($_POST["DateEntered"]); 
			$sEnteredBy = FilterInput($_POST["EnteredBy"]); 
			$sDateLastEdited = FilterInput($_POST["DateLastEdited"]); 
			$sEditedBy = FilterInput($_POST["EditedBy"]); 	
	
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
		if (strlen($iPersembahan_ID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO Persembahan" . $Kategori . "gkjbekti ( 
				Persembahan_ID,
				Tanggal,
				Pukul,
				KodeTI,
				Pengkotbah,
				Bacaan1,
				Bacaan2,
				Nas,
				Nyanyian1,
				Nyanyian2,
				Nyanyian3,
				Nyanyian4,
				Nyanyian5,
				Nyanyian6,
				Nyanyian7,
				Nyanyian8,
				Nyanyian9,
				Nyanyian10,
				Persembahan,
				Pria,
				Wanita,
				Majelis1,
				Majelis2,
				DateEntered,				
				EnteredBy
			)
			VALUES ( 
			'" . $sPersembahan_ID . "',
			'" . $sTanggal . "',
			'" . $sPukul . "',
			'" . $sKodeTI . "',
			'" . $sPengkotbah . "',
			'" . $sBacaan1 . "',
			'" . $sBacaan2 . "',
			'" . $sNas . "',
			'" . $sNyanyian1 . "',
			'" . $sNyanyian2 . "',
			'" . $sNyanyian3 . "',
			'" . $sNyanyian4 . "',
			'" . $sNyanyian5 . "',
			'" . $sNyanyian6 . "',
			'" . $sNyanyian7 . "',
			'" . $sNyanyian8 . "',
			'" . $sNyanyian9 . "',
			'" . $sNyanyian10 . "',
			'" . $sPersembahan . "',
			'" . $sPria . "',
			'" . $sWanita . "',
			'" . $sMajelis1 . "',
			'" . $sMajelis2 . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New Persembahan " . $Kategori . "Data";


		// Existing Persembahan (update)
		} else {
	
			$sSQL = "UPDATE Persembahan" . $Kategori . "gkjbekti SET 
					Tanggal = '" . $sTanggal . "',
					Pukul = '" . $sPukul . "',
					KodeTI = '" . $sKodeTI . "',
					Pengkotbah = '" . $sPengkotbah . "',
					Bacaan1 = '" . $sBacaan1 . "',
					Bacaan2 = '" . $sBacaan2 . "',
					Nas = '" . $sNas . "',
					Nyanyian1 = '" . $sNyanyian1 . "',
					Nyanyian2 = '" . $sNyanyian2 . "',
					Nyanyian3 = '" . $sNyanyian3 . "',
					Nyanyian4 = '" . $sNyanyian4 . "',
					Nyanyian5 = '" . $sNyanyian5 . "',
					Nyanyian6 = '" . $sNyanyian6 . "',
					Nyanyian7 = '" . $sNyanyian7 . "',
					Nyanyian8 = '" . $sNyanyian8 . "',
					Nyanyian9 = '" . $sNyanyian9 . "',
					Nyanyian10 = '" . $sNyanyian10 . "',
					Persembahan = '" . $sPersembahan . "',
					Pria = '" . $sPria . "',
					Wanita = '" . $sWanita . "',
					Majelis1 = '" . $sMajelis1 . "',
					Majelis2 = '" . $sMajelis2 . "',
					DateLastEdited = '" . date("YmdHis") . "', 
					EditedBy = '" . $_SESSION['iUserID'] ;
					
			$sSQL .= "' WHERE Persembahan_ID = " . $iPersembahan_ID;

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Persembahan " . $Kategori . " Data";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PersembahanAnakEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPersembahan_ID);
		}
		else if (isset($_POST["PersembahanSubmit"]))
		{
			//Send to the view of this Persembahan
			Redirect("SelectList.php?mode=PersembahanAnak&Kategori=" .$Kategori."&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PersembahanAnakEditor.php?Kategori=" . $Kategori."&amp;$refresh");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPersembahan_ID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM Persembahan" . $Kategori . "gkjbekti  WHERE Persembahan_ID = " . $iPersembahan_ID;
		$rsPersembahan = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPersembahan));
		
		echo $sSQL;

			$sPersembahan_ID = $Persembahan_ID;
			$sTanggal = $Tanggal;
			$sPukul = $Pukul;
			$sKodeTI = $KodeTI;
			$sPengkotbah = $Pengkotbah;
			$sBacaan1 = $Bacaan1;
			$sBacaan2 = $Bacaan2;
			$sNas = $Nas;
			$sNyanyian1 = $Nyanyian1;
			$sNyanyian2 = $Nyanyian2;
			$sNyanyian3 = $Nyanyian3;
			$sNyanyian4 = $Nyanyian4;
			$sNyanyian5 = $Nyanyian5;
			$sNyanyian6 = $Nyanyian6;
			$sNyanyian7 = $Nyanyian7;
			$sNyanyian8 = $Nyanyian8;
			$sNyanyian9 = $Nyanyian9;
			$sNyanyian10 = $Nyanyian10;
			$sPersembahan = $Persembahan;
			$sPria = $Pria;
			$sWanita = $Wanita;
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

require "Include/Header.php";

?>

<form method="post" action="PersembahanAnakEditor.php?Persembahan_ID=<?php echo $iPersembahan_ID; ?>&Kategori=<?php echo $Kategori; ?>" name="PersembahanEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="PersembahanSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"PersembahanSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="PersembahanCancel" onclick="javascript:document.location='<?php if (strlen($iPersembahan_ID) > 0) 
{ echo "PersembahanAnakView.php?Persembahan_ID=" . $iPersembahan_ID . "&Kategori=" . $Kategori."&amp;$refresh"; 
} else {echo "SelectList.php?mode=PersembahanAnak&Kategori=" . $Kategori."&amp;$refresh"  ; 
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
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Data Penerimaan Persembahan $Kategori"); ?></b></td>
			</tr>
			<tr>
				<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal :"); ?></td>

				<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
				</td>
			
				<td class="LabelColumn"><?php echo gettext("Pukul :"); ?></td>
				<td class="TextColumnWithBottomBorder">
					<select name="Pukul">
						<option value="0"><?php echo gettext("Pilih"); ?></option>
						<option value="06.00 WIB" <?php if ($sPukul == "06.00 WIB") { echo "selected"; } ?>><?php echo gettext("06.00 WIB"); ?></option>
						<option value="07.00 WIB" <?php if ($sPukul == "07.00 WIB") { echo "selected"; } ?>><?php echo gettext("07.00 WIB"); ?></option>
						<option value="09.00 WIB" <?php if ($sPukul == "09.00 WIB") { echo "selected"; } ?>><?php echo gettext("09.00 WIB"); ?></option>
						<option value="16.00 WIB" <?php if ($sPukul == "16.00 WIB") { echo "selected"; } ?>><?php echo gettext("16.00 WIB"); ?></option>
						<option value="17.00 WIB" <?php if ($sPukul == "17.00 WIB") { echo "selected"; } ?>><?php echo gettext("17.00 WIB"); ?></option>
					</select>
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
			</tr>	
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Pengkotbah"); ?></b></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Pengkotbah"); ?></td>
				<td colspan="4" class="TextColumn"><input type="text" name="Pengkotbah" id="Pengkotbah" size="70" value="<?php echo htmlentities(stripslashes($sPengkotbah),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sPengkotbahError ?></font></td>
			</tr>
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Bacaan Alkitab"); ?></b></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Bacaan 1"); ?></td>
				<td class="TextColumn"><input type="text" name="Bacaan1" id="Bacaan1" value="<?php echo htmlentities(stripslashes($sBacaan1),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sBacaan1Error ?></font></td>
				<td class="LabelColumn"><?php echo gettext("Bacaan 2"); ?></td>
				<td class="TextColumn"><input type="text" name="Bacaan2" id="Bacaan2" value="<?php echo htmlentities(stripslashes($sBacaan2),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sBacaan2Error ?></font></td>
			
			</tr>			
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Nas / Tema"); ?></b></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nas / Tema"); ?></td>
				<td colspan="4" class="TextColumn"><input type="text" name="Nas" id="Nas" size="70" value="<?php echo htmlentities(stripslashes($sNas),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNasError ?></font></td>
			</tr>
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Nyanyian"); ?></b></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 1"); ?></td>
				<td class="TextColumn"><input type="text" name="Nyanyian1" id="Nyanyian1" value="<?php echo htmlentities(stripslashes($sNyanyian1),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian1Error ?></font></td>
				
				<td class="LabelColumn"><?php echo gettext("Nyanyian 6"); ?></td>
				<td class="TextColumn"><input type="text" name="Nyanyian6" id="Nyanyian6" value="<?php echo htmlentities(stripslashes($sNyanyian6),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian6Error ?></font></td>
				
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 2"); ?></td>
				<td class="TextColumn"><input type="text" name="Nyanyian2" id="Nyanyian1" value="<?php echo htmlentities(stripslashes($sNyanyian2),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian2Error ?></font></td>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 7"); ?></td>
				<td class="TextColumn"><input type="text" name="Nyanyian7" id="Nyanyian7" value="<?php echo htmlentities(stripslashes($sNyanyian7),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian7Error ?></font></td>

			</tr>
			<tr>
			<td class="LabelColumn"><?php echo gettext("Nyanyian 3"); ?></td>
				<td class="TextColumn"><input type="text" name="Nyanyian3" id="Nyanyian3" value="<?php echo htmlentities(stripslashes($sNyanyian3),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian3Error ?></font></td>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 8"); ?></td>
				<td class="TextColumn"><input type="text" name="Nyanyian8" id="Nyanyian1" value="<?php echo htmlentities(stripslashes($sNyanyian8),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian8Error ?></font></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 4"); ?></td>
				<td class="TextColumn"><input type="text" name="Nyanyian4" id="Nyanyian4" value="<?php echo htmlentities(stripslashes($sNyanyian4),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian4Error ?></font></td>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 9"); ?></td>
				<td class="TextColumn"><input type="text" name="Nyanyian9" id="Nyanyian9" value="<?php echo htmlentities(stripslashes($sNyanyian9),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian9Error ?></font></td>

			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 5"); ?></td>
				<td class="TextColumn"><input type="text" name="Nyanyian5" id="Nyanyian5" value="<?php echo htmlentities(stripslashes($sNyanyian5),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian5Error ?></font></td>
				<td class="LabelColumn"><?php echo gettext("Nyanyian 10"); ?></td>
				<td class="TextColumn"><input type="text" name="Nyanyian10" id="Nyanyian10" value="<?php echo htmlentities(stripslashes($sNyanyian10),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sNyanyian10Error ?></font></td>				
			</tr>
			<tr>

			</tr>
			<tr>


			</tr>
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Pelayanan Khusus"); ?></b></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Persembahan"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Persembahan" id="Persembahan" value="<?php echo htmlentities(stripslashes($sPersembahan),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sPersembahanError ?></font></td>
			</tr>			
		
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Jemaat yang Hadir"); ?></b></td>
			</tr>			
			<tr>
				<td class="LabelColumn"><?php echo gettext("Laki laki"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Pria" id="Pria" value="<?php echo htmlentities(stripslashes($sPria),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sPriaError ?></font></td>

				<td class="LabelColumn"><?php echo gettext("Perempuan"); ?></td>
				<td class="TextColumn"><input class="right"  type="text" name="Wanita" id="Wanita" value="<?php echo htmlentities(stripslashes($sWanita),ENT_NOQUOTES, "UTF-8"); ?>">
				<br><font color="red"><?php echo $sWanitaError ?></font></td>
			</tr>
				<tr>
			<td class="LabelColumn"><?php echo gettext("Total"); ?></td>
			<td  class="right" >
			<b><?php $TotalJemaat = $sPria + $sWanita;
			echo $TotalJemaat;
			?>
			</b>
			</td>
			</tr>		
			<tr>
				<td colspan="6" class="LabelColumnHL"><b><?php echo gettext("Majelis Pendamping"); ?></b></td>
			</tr>
			
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 1"); ?></td>
				<td class="TextColumn">
					<select name="Majelis1">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }								
							$NamaMajelis = $MjlType . "" . $per_FirstName;
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis1 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
				</td>			
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Majelis 2"); ?></td>
				<td class="TextColumn">
					<select name="Majelis2">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Majelis Names for the drop-down
						$sSQL = "select a.per_ID, per_FirstName , vol_id, vol_name as Jabatan 
						from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
						where a.per_id = b.per_id AND
						a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id AND  vol_id > 1 AND vol_id < 4 
						ORDER by  vol_id, per_firstname";
						$rsMajelis = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsMajelis))
						{
							extract($aRow);
							if ($vol_id == 2) { $MjlType = "Pnt." ; }else{ $MjlType = "Dkn." ; }								
							$NamaMajelis = $MjlType . "" . $per_FirstName;							
							echo "<option value=\"" . $NamaMajelis . "\"";
							if ($sMajelis2 == $NamaMajelis ) { echo " selected"; }
							echo ">$NamaMajelis" ;
						}
						?>

					</select>
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
		$logvar2 = "Persembahan Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
