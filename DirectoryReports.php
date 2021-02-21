<?php
/*******************************************************************************
 *
 *  filename    : DirectoryReports.php
 *  last change : 2003-09-03
 *  description : form to invoke directory report
 *
 *  http://www.infocentral.org/
 *  Copyright 2003 Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

// Check for Create Directory user permission.
if (!$bCreateDirectory) {
    Redirect("Menu.php");
    exit;
}

// Set the page title and include HTML header
$sPageTitle = gettext("Laporan Daftar Warga");
require "Include/Header.php";

?>

<form method="POST" action="Reports/DirectoryReport.php">

<?php

// Get classifications for the selects
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 1 ORDER BY lst_OptionSequence";
$rsClassifications = RunQuery($sSQL);

//Get Family Roles for the drop-down
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 2 ORDER BY lst_OptionSequence";
$rsFamilyRoles = RunQuery($sSQL);

// Get all the Groups
$sSQL = "SELECT * FROM group_grp ORDER BY grp_Name";
$rsGroups = RunQuery($sSQL);

// Get the list of custom person fields
$sSQL = "SELECT person_custom_master.* FROM person_custom_master ORDER BY custom_Order";
$rsCustomFields = RunQuery($sSQL);
$numCustomFields = mysql_num_rows($rsCustomFields);

$aDefaultClasses = explode(',', $sDirClassifications);
$aDirRoleHead = explode(",",$sDirRoleHead);
$aDirRoleSpouse = explode(",",$sDirRoleSpouse);
$aDirRoleChild = explode(",",$sDirRoleChild);

$rsConfig = mysql_query("SELECT cfg_name, IFNULL(cfg_value, cfg_default) AS value FROM config_cfg WHERE cfg_section='ChurchInfoReport'");
if ($rsConfig) {
    while (list($cfg_name, $cfg_value) = mysql_fetch_row($rsConfig)) {
        $$cfg_name = $cfg_value;
    }
}

// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
	extract ($aRow);
	$aSecurityType[$lst_OptionID] = $lst_OptionName;
}

?>

<table align="center">
<?php if ($_GET['cartdir'] == null)
{
?>
    <tr>
        <td class="LabelColumn"><?php echo gettext("Pilih Klasifikasi "); ?></td>
        <td class="TextColumn">
            <div class="SmallText"><?php echo gettext("Gunakan Ctrl untuk memilih banyak"); ?></div>
            <select name="sDirClassifications[]" size="5" multiple>
            <option value="0"> - </option>
            <?php
                while ($aRow = mysql_fetch_array($rsClassifications)) {
                    extract($aRow);
                    echo "<option value=\"" . $lst_OptionID . "\"";
                    if (in_array($lst_OptionID,$aDefaultClasses)) echo " dipilih";
                    echo ">" . $lst_OptionName . "</option>";
                }
            ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="LabelColumn"><?php echo gettext("Kelompok :"); ?></td>
        <td class="TextColumn">
            <div class="SmallText"><?php echo gettext("Gunakan Ctrl untuk memilih banyak"); ?></div>
            <select name="GroupID[]" size="5" multiple>
                <?php
                while ($aRow = mysql_fetch_array($rsGroups))
                {
                    extract($aRow);
                    echo "<option value=\"" . $grp_ID . "\">" . $grp_Name . "</option>";
                }
                ?>
            </select>
        </td>
    </tr>

<?php
}
?>
    <tr>
        <td class="LabelColumn"><?php echo gettext("Peran Sebagai Kepala Keluarga?"); ?></td>
        <td class="TextColumn">
            <div class="SmallText"><?php echo gettext("Gunakan Ctrl untuk memilih banyak"); ?></div>
            <select name="sDirRoleHead[]" size="5" multiple>
            <?php
                while ($aRow = mysql_fetch_array($rsFamilyRoles)) {
                    extract($aRow);
                    echo "<option value=\"" . $lst_OptionID . "\"";
                    if (in_array($lst_OptionID, $aDirRoleHead)) echo " selected";
                    echo ">" . $lst_OptionName . "</option>";
                }
            ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="LabelColumn"><?php echo gettext("Peran Sebagai Pasangan?"); ?></td>
        <td class="TextColumn">
            <div class="SmallText"><?php echo gettext("Gunakan Ctrl untuk memilih banyak"); ?></div>
            <select name="sDirRoleSpouse[]" size="5" multiple>
            <?php
                mysql_data_seek($rsFamilyRoles,0);
                while ($aRow = mysql_fetch_array($rsFamilyRoles)) {
                    extract($aRow);
                    echo "<option value=\"" . $lst_OptionID . "\"";
                    if (in_array($lst_OptionID, $aDirRoleSpouse)) echo " selected";
                    echo ">" . $lst_OptionName . "</option>";
                }
            ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="LabelColumn"><?php echo gettext("Peran Sebagai Anak?"); ?></td>
        <td class="TextColumn">
            <div class="SmallText"><?php echo gettext("Gunakan Ctrl untuk memilih banyak"); ?></div>
            <select name="sDirRoleChild[]" size="5" multiple>
            <?php
                mysql_data_seek($rsFamilyRoles,0);
                while ($aRow = mysql_fetch_array($rsFamilyRoles)) {
                    extract($aRow);
                    echo "<option value=\"" . $lst_OptionID . "\"";
                    if (in_array($lst_OptionID, $aDirRoleChild)) echo " selected";
                    echo ">" . $lst_OptionName . "</option>";
                }
            ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="LabelColumn"><?php echo gettext("Informasi yang akan ditampilkan:"); ?></td>
        <td class="TextColumn">
            <input type="checkbox" Name="bDirAddress" value="1" checked><?php echo gettext("Alamat");?><br>
            <input type="checkbox" Name="bDirWedding" value="1" 		><?php echo gettext("Tanggal Pernikahan");?><br>
            <input type="checkbox" Name="bDirPersonalWorkEmail" value="1" ><?php echo gettext("Tempat Lahir");?><br>
            <input type="checkbox" Name="bDirBirthday" value="1" checked		><?php echo gettext("Tanggal lahir");?><br>

            <input type="checkbox" Name="bDirFamilyPhone" value="1" checked	><?php echo gettext("Tel Rumah (Keluarga)");?><br>
            <input type="checkbox" Name="bDirFamilyWork" value="1" 		><?php echo gettext("Tel Kantor (Keluarga)");?><br>
            <input type="checkbox" Name="bDirFamilyCell" value="1" 		><?php echo gettext("Tel Handphone (Keluarga)");?><br>
            <input type="checkbox" Name="bDirFamilyEmail" value="1"	 	><?php echo gettext("Alamat Email (Keluarga)");?><br>

            <input type="checkbox" Name="bDirPersonalPhone" value="1" 	><?php echo gettext("Tel Rumah (Jemaat)");?><br>
            <input type="checkbox" Name="bDirPersonalWork" value="1" 	><?php echo gettext("Tel Kantor (Jemaat)");?><br>
            <input type="checkbox" Name="bDirPersonalCell" value="1" 	><?php echo gettext("Tel Handphone (Jemaat)");?><br>
            <input type="checkbox" Name="bDirPersonalEmail" value="1"	><?php echo gettext("Alamat Email (Jemaat)");?><br>
                        <input type="checkbox" Name="bDirPhoto" value="1" ><?php echo gettext("Photos");?><br>
         <?php
         if ($numCustomFields > 0) {
            while ( $rowCustomField = mysql_fetch_array($rsCustomFields, MYSQL_ASSOC) ){
					if (($aSecurityType[$rowCustomField['custom_FieldSec']] == 'bAll') or ($_SESSION[$aSecurityType[$rowCustomField['custom_FieldSec']]]))
					{ ?>
		            <input type="checkbox" Name="bCustom<?php echo $rowCustomField['custom_Order'];?>" value="1" ><?php echo $rowCustomField['custom_Name'];?><br>
         <?php
	            }
	         }
         }
         ?>

        </td>
    </tr>
	<tr>
	 <td class="LabelColumn"><?php echo gettext("Jumlah Kolom:"); ?></td>
 	 <td class="TextColumn">
		    <input type="radio" Name="NumCols" value=1>1 col<br>
		    <input type="radio" Name="NumCols" value=2 checked>2 cols<br>
		    <input type="radio" Name="NumCols" value=3>3 cols<br>
	</td>
	</tr>
	<tr>
	 <td class="LabelColumn"><?php echo gettext("Ukuran Kertas:"); ?></td>
 	 <td class="TextColumn">
		    <input type="radio" name="PageSize" value="letter" checked>Letter (8.5x11)<br>
		    <input type="radio" name="PageSize" value="legal">Legal (8.5x14)
	</td>
	</tr>
	<tr>
	 <td class="LabelColumn"><?php echo gettext("Ukuran Font:"); ?></td>
 	 <td class="TextColumn">
		<table>
		<tr>
		    <td><input type="radio" Name="FSize" value=6>6<br>
		    <input type="radio" Name="FSize" value=8>8<br>
		    <input type="radio" Name="FSize" value=10 checked>10<br></td>

		    <td><input type="radio" Name="FSize" value=12>12<br>
		    <input type="radio" Name="FSize" value=14>14<br>
		    <input type="radio" Name="FSize" value=16>16<br></td>
		</tr>
		</table>
	</td>
	</tr>
    <tr>
        <td class="LabelColumn"><?php echo gettext("Judul Halaman:"); ?></td>
        <td class="TextColumn">
            <table>
                <tr>
                    <td><?php echo gettext("Menggunakan Judul"); ?></td>
                    <td><input type="checkbox" Name="bDirUseTitlePage" value="1"></td>
                </tr>
                <tr>
                    <td><?php echo gettext("Nama Gereja"); ?></td>
                    <td><input type="text" Name="sChurchName" value="<?php echo $sChurchName;?>"></td>
                </tr>
                <tr>
                    <td><?php echo gettext("Alamat"); ?></td>
                    <td><input type="text" Name="sChurchAddress" value="<?php echo $sChurchAddress;?>"></td>
                </tr>
                <tr>
                    <td><?php echo gettext("Kota"); ?></td>
                    <td><input type="text" Name="sChurchCity" value="<?php echo $sChurchCity;?>"></td>
                </tr>
                <tr>
                    <td><?php echo gettext("Daerah"); ?></td>
                    <td><input type="text" Name="sChurchState" value="<?php echo $sChurchState;?>"></td>
                </tr>
                <tr>
                    <td><?php echo gettext("Kode Pos"); ?></td>
                    <td><input type="text" Name="sChurchZip" value="<?php echo $sChurchZip;?>"></td>
                </tr>
                <tr>
                    <td><?php echo gettext("Telephone"); ?></td>
                    <td><input type="text" Name="sChurchPhone" value="<?php echo $sChurchPhone;?>"></td>
                </tr>
                <tr>
                    <td><?php echo gettext("Catatan"); ?></td>
                    <td><textarea Name="sDirectoryDisclaimer" cols="35" rows="4"><?php echo "$sDirectoryDisclaimer1 $sDirectoryDisclaimer2";?></textarea></td>
                </tr>

            </table>
        </td>
    </tr>


</table>

<?php if ($_GET['cartdir'] != null) echo '<input type="hidden" name="cartdir" value="M">'; ?>


<p align="center">
<BR>
<input type="submit" class="icButton" name="Submit" <?php echo 'value="' . gettext("Buat Daftar Jemaat (PDF)") . '"'; ?>>
<input type="button" class="icButton" name="Cancel" <?php echo 'value="' . gettext("Batal") . '"'; ?> onclick="javascript:document.location='Menu.php';">
</p>
</form>

<?php
require "Include/Footer.php";
?>
