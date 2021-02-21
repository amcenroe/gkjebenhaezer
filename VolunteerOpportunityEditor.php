<?php
/*******************************************************************************
 *
 *  filename    : VolunteerOpportunityEditor.php
 *  last change : 2003-03-29
 *  website     : http://www.infocentral.org
 *  copyright   : Copyright 2005 Michael Wilt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  function    : Editor for donation funds
 *
 *  ChurchInfo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

require "Include/Config.php";
require "Include/Functions.php";

$sAction = $_GET["Action"];
$sOpp = FilterInput($_GET["Opp"],'int');

$sDeleteError = "";

if ($sAction = 'delete' && strlen($sOpp) > 0)
{
	$sSQL = "DELETE FROM volunteeropportunity_vol WHERE vol_ID = '" . $sOpp . "'";
	RunQuery($sSQL);
}

$sPageTitle = gettext("Editor Pelayanan Sukarela");

require "Include/Header.php";

// Does the user want to save changes to text fields?
if (isset($_POST["SaveChanges"]))
{
	$sSQL = "SELECT * FROM volunteeropportunity_vol";
	$rsOpps = RunQuery($sSQL);
	$numRows = mysql_num_rows($rsOpps);

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

		$aRow = mysql_fetch_array($rsOpps);
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

			$sSQL = "UPDATE volunteeropportunity_vol
					SET `vol_Name` = '" . $aNameFields[$iFieldID] . "',
						`vol_Description` = '" . $aDescFields[$iFieldID] . "',
						`vol_Active` = '" . $temp . "' " .
					"WHERE `vol_ID` = '" . $aIDFields[$iFieldID] . "';";

			RunQuery($sSQL);
		}
	}
}

else
{
	// Check if we're adding a fund
	if (isset($_POST["AddField"]))
	{
		$newFieldName = FilterInput($_POST["newFieldName"]);
		$newFieldDesc = FilterInput($_POST["newFieldDesc"]);

		if (strlen($newFieldName) == 0)
		{
			$bNewNameError = true;
		}
		else
		{
			// Insert into the funds table
			$sSQL = "INSERT INTO `volunteeropportunity_vol`
					(`vol_ID` , `vol_Name` , `vol_Description`)
					VALUES ('', '" . $newFieldName . "', '" . $newFieldDesc . "');";
			RunQuery($sSQL);

			$bNewNameError = false;
		}
	}

	// Get data for the form as it now exists..
	$sSQL = "SELECT * FROM volunteeropportunity_vol";

	$rsOpps = RunQuery($sSQL);
	$numRows = mysql_num_rows($rsOpps);

	// Create arrays of the fundss.
	for ($row = 1; $row <= $numRows; $row++)
	{
		$aRow = mysql_fetch_array($rsOpps, MYSQL_BOTH);
		extract($aRow);

		$aIDFields[$row] = $vol_ID;
		$aNameFields[$row] = $vol_Name;
		$aDescFields[$row] = $vol_Description;
		$aActiveFields[$row] = ($vol_Active == 'true');
	}
}

// Construct the form
?>

<script language="javascript">

function confirmDeleteOpp( Opp ) {
var answer = confirm (<?php echo '"' . gettext("Are you sure you want to delete this fund?") . '"'; ?>)
if ( answer )
	window.location="VolunteerOpportunityEditor.php?Opp=" + Opp + "&Action=delete"
}
</script>

<form method="post" action="VolunteerOpportunityEditor.php" name="OppsEditor">

<table cellpadding="3" width="75%" align="center">

<?php
if ($numRows == 0)
{
?>
	<center><h2><?php echo gettext("Tidak Ada kesempatan Pelayanan Sukarela yang ditambahkan"); ?></h2>
	<input type="button" class="icButton" <?php echo 'value="' . gettext("Keluar") . '"'; ?> Name="Exit" onclick="javascript:document.location='Menu.php';">
	</center>
<?php
}
else
{
?>
	<tr><td colspan="5">
		<center><b><?php echo gettext("Perhatian: Perubahan isian akan hilang jika belum di klik 'Simpan'!"); ?></b></center>
	</td></tr>

	<tr><td colspan="5" align="center"><span class="LargeText" style="color: red;">
		<?php
		if ( $bErrorFlag ) echo gettext("Isian atau Pilihan Salah. Perubahan tidak akan disimpan! Silahkan dibetulkan!");
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
			<th><?php echo gettext("Vol ID"); ?></th>
			<th><?php echo gettext("Nama"); ?></th>
			<th><?php echo gettext("Keterangan"); ?></th>
			<th><?php echo gettext("Aktif"); ?></th>
		</tr>

	<?php

	for ($row=1; $row <= $numRows; $row++)
	{
		?>
		<tr>
			<td class="LabelColumn"><h3><?php echo $row ?></h3></td>
			<td class="LabelColumn"><h3><b><?php echo htmlentities(stripslashes($aIDFields[$row]),ENT_NOQUOTES, "UTF-8"); ?></b></h3></td>
			<td class="TextColumn" width="5%">
				<input type="button" class="icButton" value="<?php echo gettext("Hapus"); ?>" Name="delete" onclick="confirmDeleteOpp(<?php echo "'" . $aIDFields[$row] . "'"; ?>);" >
			</td>
			
			<td class="TextColumn" align="center">
				<input type="text" name="<?php echo $row . "name"; ?>" value="<?php echo htmlentities(stripslashes($aNameFields[$row]),ENT_NOQUOTES, "UTF-8"); ?>" size="20" maxlength="30">
				<?php
				if ( $aNameErrors[$row] )
					echo "<span style=\"color: red;\"><BR>" . gettext("Anda harus memasukkan nama.") . " </span>";
				?>
			</td>

			<td class="TextColumn">
				<input type="text" Name="<?php echo $row . "desc" ?>" value="<?php echo htmlentities(stripslashes($aDescFields[$row]),ENT_NOQUOTES, "UTF-8"); ?>" size="40" maxlength="100">
			</td>
			<td class="TextColumn" align="center" nowrap>
				<input type="radio" Name="<?php echo $row . "active" ?>" value="1" <?php if ($aActiveFields[$row]) echo " checked" ?>><?php echo gettext("Ya"); ?>
				<input type="radio" Name="<?php echo $row . "active" ?>" value="0" <?php if (!$aActiveFields[$row]) echo " checked" ?>><?php echo gettext("Tidak"); ?>
			</td>

		</tr>
	<?php } ?>

		<tr>
			<td colspan="5">
			<table width="100%">
				<tr>
					<td width="30%"></td>
					<td width="40%" align="center" valign="bottom">
						<input type="submit" class="icButton" <?php echo 'value="' . gettext("Simpan") . '"'; ?> Name="SaveChanges">
						&nbsp;
						<input type="button" class="icButton" <?php echo 'value="' . gettext("Keluar") . '"'; ?> Name="Exit" onclick="javascript:document.location='Menu.php';">
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
					<td width="15%"></td>
					<td valign="top">
						<div><?php echo gettext("Nama Kegiatan:"); ?></div>
						<input type="text" name="newFieldName" size="30" maxlength="30">
						<?php if ( $bNewNameError ) echo "<div><span style=\"color: red;\"><BR>" . gettext("Anda Harus memasukkan nama kegiatan.") . "</span></div>"; ?>
						&nbsp;
					</td>
					<td valign="top">
						<div><?php echo gettext("Keterangan:"); ?></div>
						<input type="text" name="newFieldDesc" size="40" maxlength="100">
						&nbsp;
					</td>
					<td>
						<input type="submit" class="icButton" <?php echo 'value="' . gettext("Tambah Kegiatan Baru") . '"'; ?> Name="AddField">
					</td>
					<td width="15%"></td>
				</tr>
			</table>
			</td>
		</tr>

	</table>
	</form>

<?php require "Include/Footer.php"; ?>
