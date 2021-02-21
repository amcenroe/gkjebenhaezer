<?php
/*******************************************************************************
 *
 *  filename    : PersembahanBulananEditor.php
 *  last change : 2003-03-29
 *  website     : http://www.infocentral.org
 *  copyright   : Copyright 2003 Chris Gebhardt (http://www.openserve.org)
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 * 2012 Erwin Pratama for GKJ Bekasi Timur
 *
 *  function    : Editor for donation funds
 *
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

require "Include/Config.php";
require "Include/Functions.php";

// Security: user must be administrator to use this page
if (!$_SESSION['bAdmin'])
{
	Redirect("Menu.php");
	exit;
}

$sAction = $_GET["Action"];
$sPersembahan_ID = FilterInput($_GET["Persembahan_ID"],'int');

$sDeleteError = "";

if ($sAction = 'delete' && strlen($sFund) > 0)
{
	$sSQL = "DELETE FROM PersembahanBulanangkjbekti WHERE Persembahan_ID = '" . $sPersembahan . "'";
	RunQuery($sSQL);
}

$sPageTitle = gettext("Editor Persembahan Bulanan");

require "Include/Header.php";

// Does the user want to save changes to text fields?
if (isset($_POST["SaveChanges"]))
{
	$sSQL = "SELECT * FROM PersembahanBulanangkjbekti";
	$rsFunds = RunQuery($sSQL);
	$numRows = mysql_num_rows($rsFunds);

//	Tanggal
//Pukul
//KodeTI
//Kelompok
//NomorKartu
//Bulan1
//Bulan2
//Bulanan
//Syukur
//ULK 

//EnteredBy
//EditedBy
//DateEntered
//DateLastEdited
	
	for ($iFieldID = 1; $iFieldID <= $numRows; $iFieldID++ )
	{
		$aNameFields[$iFieldID] = FilterInput($_POST[$iFieldID . "name"]);

		if ( strlen($aNameFields[$iFieldID]) == 0 )
		{
			$aNameErrors[$iFieldID] = true;
			$bErrorFlag = true;
		}
		else
		{
			$aNameErrors[$iFieldID] = false;
		}

		$aDescFields[$iFieldID] = FilterInput($_POST[$iFieldID . "desc"]);
		$aActiveFields[$iFieldID] = $_POST[$iFieldID . "active"];

		$aRow = mysql_fetch_array($rsFunds);
		$aIDFields[$iFieldID] = $aRow[0];
	}

	// If no errors, then update.
	if (!$bErrorFlag)
	{
		for( $iFieldID=1; $iFieldID <= $numRows; $iFieldID++ )
		{
			if ($aActiveFields[$iFieldID] == 1)
				$temp = 'true';
			else
				$temp = 'false';

			$sSQL = "UPDATE donationfund_fun
					SET `fun_Name` = '" . $aNameFields[$iFieldID] . "',
						`fun_Description` = '" . $aDescFields[$iFieldID] . "',
						`fun_Active` = '" . $temp . "' " .
					"WHERE `fun_ID` = '" . $aIDFields[$iFieldID] . "';";

			RunQuery($sSQL);
		}
	}
}

else
{
	// Check if we're adding a fund
	if (isset($_POST["AddField"]))
	{
		$newTanggal = FilterInput($_POST["newTanggal"]);
		$newPukul = FilterInput($_POST["newPukul"]);
		$newKodeTI = FilterInput($_POST["newKodeTI"]);
		$newKelompok = FilterInput($_POST["newKelompok"]);
		$newNomorKartu = FilterInput($_POST["newNomorKartu"]);
		$newBulan1 = FilterInput($_POST["newBulan1"]);
		$newBulan2 = FilterInput($_POST["newBulan2"]);
		$newBulanan = FilterInput($_POST["newBulanan"]);
		$newSyukur = FilterInput($_POST["newSyukur"]);
		$newULK  = FilterInput($_POST["newULK"]);
		$newEnteredBy = FilterInput($_POST["newEnteredBy"]);
		$newDateEntered = FilterInput($_POST["newDateEntered"]);

		
		if (strlen($newFieldName) == 0)
		{
			$bNewNameError = true;
		}
		else
		{
			// Insert into the Persembahan table
			$sSQL = "INSERT INTO `PersembahanBulanangkjbekti` (				
					`Persembahan_ID` ,
					`Tanggal` ,
					`Pukul` ,
					`KodeTI` ,
					`Kelompok` ,
					`NomorKartu` ,
					`Bulan1` ,
					`Bulan2` ,
					`Bulanan` ,
					`Syukur` ,
					`ULK ` ,
					`EnteredBy` ,
					`EditedBy` )
					VALUES (
					0, 
					'" . $newPersembahan_ID . "' ,
					'" . $newTanggal . "' ,
					'" . $newPukul . "' ,
					'" . $newKodeTI . "' ,
					'" . $newKelompok . "' ,
					'" . $newNomorKartu . "' ,
					'" . $newBulan1 . "' ,
					'" . $newBulan2 . "' ,
					'" . $newBulanan . "' ,
					'" . $newSyukur . "' ,
					'" . $newULK  . "' ,
					'" . $newEnteredBy . "' ,
					'" . $newEditedBy . "' );";					

			RunQuery($sSQL);

			$bNewNameError = false;
		}
	}

	// Get data for the form as it now exists..
	$sSQL = "SELECT * FROM PersembahanBulanangkjbekti";

	$rsFunds = RunQuery($sSQL);
	$numRows = mysql_num_rows($rsFunds);

	// Create arrays of the fundss.
	for ($row = 1; $row <= $numRows; $row++)
	{
		$aRow = mysql_fetch_array($rsFunds, MYSQL_BOTH);
		extract($aRow);

		$aIDFields[$row] = $fun_ID;
		$aNameFields[$row] = $fun_Name;
		$aDescFields[$row] = $fun_Description;
		$aActiveFields[$row] = ($fun_Active == 'true');
		
//		Tanggal
//Pukul
//KodeTI
//Kelompok
//NomorKartu
//Bulan1
//Bulan2
//Bulanan
//Syukur
//ULK 

//EnteredBy
//EditedBy
//DateEntered
//DateLastEdited
	

//		Tanggal
//Pukul
//KodeTI
//Kelompok
//NomorKartu
//Bulan1
//Bulan2
//Bulanan
//Syukur
//ULK 

//EnteredBy
//EditedBy
//DateEntered
//DateLastEdited
	
	}
}

// Construct the form
?>

<script language="javascript">

function confirmDeleteFund( Fund ) {
var answer = confirm (<?php echo '"' . gettext("Apakah Anda yakin menghapus data ini?") . '"'; ?>)
if ( answer )
	window.location="PersembahanBulananEditor.php?Fund=" + Fund + "&Action=delete"
}
</script>

<form method="post" action="PersembahanBulananEditor.php" name="FundsEditor">

<table cellpadding="3" width="75%" align="center">

<?php
if ($numRows == 0)
{
?>
	<center><h2><?php echo gettext("Tidak ada Data yang ditambahkan"); ?></h2>
	<input type="button" class="icButton" <?php echo 'value="' . gettext("Keluar") . '"'; ?> Name="Exit" onclick="javascript:document.location='Menu.php';">
	</center>
<?php
}
else
{
?>
	<tr><td colspan="5">
		<center><b><?php echo gettext("Perhatian: Klik 'Simpan' sebelum menghapus atau menambah data baru!"); ?></b></center>
	</td></tr>

	<tr><td colspan="5" align="center"><span class="LargeText" style="color: red;">
		<?php
		if ( $bErrorFlag ) echo gettext("Invalid fields or selections. Changes not saved! Please correct and try again!");
		if (strlen($sDeleteError) > 0) echo $sDeleteError;
		?>
	</span></tr></td>

		<tr>
			<td colspan="5" align="center">
			<input type="submit" class="icButton" <?php echo 'value="' . gettext("Simpan") . '"'; ?> Name="SaveChanges">
			&nbsp;
			<input type="button" class="icButton" <?php echo 'value="' . gettext("Keluar") . '"'; ?> Name="Exit" onclick="javascript:document.location='Menu.php';">
			</td>
		</tr>

		<tr>
			<th></th>
			<th></th>
			<th><?php echo gettext("Tanggal"); ?></th>
			<th><?php echo gettext("Pukul"); ?></th>
			<th><?php echo gettext("KodeTI"); ?></th>
			<th><?php echo gettext("Kelompok"); ?></th>
			<th><?php echo gettext("NomorKartu"); ?></th>
			<th><?php echo gettext("Bulan1"); ?></th>
			<th><?php echo gettext("Bulan2"); ?></th>
			<th><?php echo gettext("Bulanan"); ?></th>
			<th><?php echo gettext("Syukur"); ?></th>
			<th><?php echo gettext("ULK "); ?></th>
		</tr>

	<?php

	for ($row=1; $row <= $numRows; $row++)
	{
		?>
		<tr>
			<td class="LabelColumn"><h2><b><?php echo $row ?></b></h2></td>

			<td class="TextColumn" width="5%">
				<input type="button" class="icButton" value="<?php echo gettext("X"); ?>" Name="delete" onclick="confirmDeleteFund(<?php echo "'" . $aIDFields[$row] . "'"; ?>);" >
			</td>

			
			
			<td class="TextColumn"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
			</td>
			
			<td class="TextColumn" align="center">
				<input type="text" name="<?php echo $row . "name"; ?>" value="<?php echo htmlentities(stripslashes($aNameFields[$row]),ENT_NOQUOTES, "UTF-8"); ?>" size="20" maxlength="30">
				<?php
				if ( $aNameErrors[$row] )
					echo "<span style=\"color: red;\"><BR>" . gettext("You must enter a name.") . " </span>";
				?>
			</td>

			<td class="TextColumn">
				<input type="text" Name="<?php echo $row . "desc" ?>" value="<?php echo htmlentities(stripslashes($aDescFields[$row]),ENT_NOQUOTES, "UTF-8"); ?>" size="40" maxlength="100">
			</td>
			<td class="TextColumn" align="center" nowrap>
				<input type="radio" Name="<?php echo $row . "active" ?>" value="1" <?php if ($aActiveFields[$row]) echo " checked" ?>><?php echo gettext("Yes"); ?>
				<input type="radio" Name="<?php echo $row . "active" ?>" value="0" <?php if (!$aActiveFields[$row]) echo " checked" ?>><?php echo gettext("No"); ?>
			</td>

		</tr>
	<?php } ?>

		<tr>
			<td colspan="5">
			<table width="100%">
				<tr>
					<td width="30%"></td>
					<td width="40%" align="center" valign="bottom">
						<input type="submit" class="icButton" <?php echo 'value="' . gettext("Save Changes") . '"'; ?> Name="SaveChanges">
						&nbsp;
						<input type="button" class="icButton" <?php echo 'value="' . gettext("Exit") . '"'; ?> Name="Exit" onclick="javascript:document.location='Menu.php';">
					</td>
					<td width="30%"></td>
				</tr>
			</table>
			</td>
			<td>
		</tr>
<?php } ?>
		<tr><td colspan="5"><hr></td></tr>
		<tr>
			<td colspan="5">
			<table width="100%">
				<tr>
					<td valign="top">
						<div><?php echo gettext("Tanggal:"); ?></div>
						<input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText">
						<?php if ( $bNewTanggalError ) echo "<div><span style=\"color: red;\"><BR>" . gettext("Tanggal Harus diisi") . "</span></div>"; ?>
						&nbsp;
						</td>

				
				<td valign="top">
				<div><?php echo gettext("Tempat Ibadah:"); ?></div>
					<select name="KodeTI">
						<option value="" selected><?php echo gettext("Tidak Diketahui"); ?></option>
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
					<?php if ( $bNewKodeTIError ) echo "<div><span style=\"color: red;\"><BR>" . gettext("Kode TI Harus diisi") . "</span></div>"; ?>
						&nbsp;
				</td>	

				<td valign="top">
					<div><?php echo gettext("Pukul:"); ?></div>
						<select name="Pukul">
						<option value=""><?php echo gettext("Pilih"); ?></option>
						<option value="06.00 WIB" <?php if ($sPukul == "06.00 WIB") { echo "selected"; } ?>><?php echo gettext("06.00 WIB"); ?></option>
						<option value="07.00 WIB" <?php if ($sPukul == "07.00 WIB") { echo "selected"; } ?>><?php echo gettext("07.00 WIB"); ?></option>
						<option value="09.00 WIB" <?php if ($sPukul == "09.00 WIB") { echo "selected"; } ?>><?php echo gettext("09.00 WIB"); ?></option>
						<option value="16.00 WIB" <?php if ($sPukul == "16.00 WIB") { echo "selected"; } ?>><?php echo gettext("16.00 WIB"); ?></option>
						<option value="17.00 WIB" <?php if ($sPukul == "17.00 WIB") { echo "selected"; } ?>><?php echo gettext("17.00 WIB"); ?></option>
					</select>
					<?php if ( $bNewPukulError ) echo "<div><span style=\"color: red;\"><BR>" . gettext("Kelompok Harus diisi") . "</span></div>"; ?>
						&nbsp;
				</td>	
				<td valign="top">
						<div><?php echo gettext("Kelompok"); ?></div>
					<select name="Kelompok">
						<option value="" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						//Get Church Names for the drop-down
						$sSQL = "SELECT * FROM kelompok ORDER BY Kode";
						$rsKelompok = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsKelompok))
						{
							extract($aRow);

							echo "<option value=\"" . $Kode . "\"";
							if ($sKelompok == $Kode) { echo " selected"; }
							echo ">" . $Kode ;
						}
						?>
						<?php if ( $bNewKelompokError ) echo "<div><span style=\"color: red;\"><BR>" . gettext("Kelompok Harus diisi") . "</span></div>"; ?>
						&nbsp;
					</select>
				</td>	
				<td valign="top">
						<div><?php echo gettext("Nomor Kartu:"); ?></div>
						<input type="text" name="NomorKartu" size="10" maxlength="10">
						<?php if ( $bNewNomorKartuError ) echo "<div><span style=\"color: red;\"><BR>" . gettext("Nomor Kartu Harus diisi") . "</span></div>"; ?>
						&nbsp;
				</td>
				<td valign="top">
						<div><?php echo gettext("Bulan1"); ?></div>
					<select name="Bulan1">
						<?php
						//Get Church Names for the drop-down
						$sSQL = "SELECT * FROM bulan ORDER BY kode";
						$rsBulan1 = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsBulan1))
						{
							extract($aRow);

							echo "<option value=\"" . $kode . "\"";
							if ($sBulan1 == $kode) { echo " selected"; }
							echo ">" . $nama_bulan ;
						}
						?>
						<?php if ( $bNewBulan1Error ) echo "<div><span style=\"color: red;\"><BR>" . gettext("Bulan Harus diisi") . "</span></div>"; ?>
						&nbsp;
					</select>
				</td>	
				<td valign="top">
						<div><?php echo gettext("Bulan2"); ?></div>
					<select name="Bulan2">
						<?php
						//Get Church Names for the drop-down
						$sSQL = "SELECT * FROM bulan ORDER BY kode";
						$rsBulan2 = RunQuery($sSQL);
						while ($aRow = mysql_fetch_array($rsBulan2))
						{
							extract($aRow);

							echo "<option value=\"" . $kode . "\"";
							if ($sBulan2 == $kode) { echo " selected"; }
							echo ">" . $nama_bulan ;
						}
						?>
						<?php if ( $bNewBulan2Error ) echo "<div><span style=\"color: red;\"><BR>" . gettext("Bulan Harus diisi") . "</span></div>"; ?>
						&nbsp;
					</select>
					<td valign="top">
						<div><?php echo gettext("Bulanan"); ?></div>
						<input type="text" name="Bulanan" size="10" maxlength="15">
					</td>					
					<td valign="top">
						<div><?php echo gettext("Syukur"); ?></div>
						<input type="text" name="Syukur" size="10" maxlength="15">
					</td>					
					<td valign="top">
						<div><?php echo gettext("ULK"); ?></div>
						<input type="text" name="ULK" size="10" maxlength="15">
					</td>					
				
				</td>
					<td>
						<input type="submit" class="icButton" <?php echo 'value="' . gettext("Tambah Data") . '"'; ?> Name="AddField">
					</td>
					<td width="15%"></td>
				</tr>
			</table>
			</td>
		</tr>

	</table>
	</form>

<?php require "Include/Footer.php"; ?>
