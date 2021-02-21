<?php
/*******************************************************************************
 *
 *  filename    : PakEditor.php
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
$sPageTitle = gettext("Editor - Pendidikan Agama Kristen");

//Get the PakID out of the querystring
$iPakID = FilterInput($_GET["PakID"],'int');

$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PakID= manually)
if (strlen($iPakID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rspak = RunQuery($sSQL);
	extract(mysql_fetch_array($rspak));

	if (mysql_num_rows($rspak) == 0)
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

if (isset($_POST["PakSubmit"]) || isset($_POST["PakSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sPakID = FilterInput($_POST["PakID"]);
	$sper_ID = FilterInput($_POST["per_ID"]);
	$sNama = FilterInput($_POST["Nama"]);
	$sNama1 = FilterInput($_POST["Nama1"]);
	$sAlamatSekolah = FilterInput($_POST["AlamatSekolah"]);
	$sNoTelp = FilterInput($_POST["NoTelp"]);
	$sKelas = FilterInput($_POST["Kelas"]);
	$sKetKelas = FilterInput($_POST["KetKelas"]);
	$sSemester = FilterInput($_POST["Semester"]);
	$sTahunAjaran = FilterInput($_POST["TahunAjaran"]);
	$sTutor = FilterInput($_POST["Tutor"]);
	$sTutorID = FilterInput($_POST["TutorID"]);
	$sNilai1 = FilterInput($_POST["Nilai1"]);
	$sNilai2 = FilterInput($_POST["Nilai2"]);
	$sNilai3 = FilterInput($_POST["Nilai3"]);
	$sNilai4 = FilterInput($_POST["Nilai4"]);
	$sTglTest1 = FilterInput($_POST["TglTest1"]);
	$sTglTest2 = FilterInput($_POST["TglTest2"]);
	$sTglTest3 = FilterInput($_POST["TglTest3"]);
	$sTglTest4 = FilterInput($_POST["TglTest4"]);	
	$sKeterangan = FilterInput($_POST["Keterangan"]);
	
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
		if (strlen($iPakID) < 1)
		{
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO pakgkjbekti ( 
			PakID,
			per_ID,
			Nama,
			Nama1,
			AlamatSekolah,
			NoTelp,
			Kelas,
			KetKelas,
			Semester,
			TahunAjaran,
			Tutor,
			TutorID,
			Nilai1,
			Nilai2,
			Nilai3,
			Nilai4,
			TglTest1,
			TglTest2,
			TglTest3,
			TglTest4,
			Keterangan,
			DateEntered, 
			EnteredBy )
			VALUES ( 
			'" . $sPakID . "',
			'" . $sper_ID . "',
			'" . $sNama . "',
			'" . $sNama1 . "',
			'" . $sAlamatSekolah . "',
			'" . $sNoTelp . "',
			'" . $sKelas . "',
			'" . $sKetKelas . "',
			'" . $sSemester . "',
			'" . $sTahunAjaran . "',
			'" . $sTutor . "',
			'" . $sTutorID . "',
			'" . $sNilai1 . "',
			'" . $sNilai2 . "',
			'" . $sNilai3 . "',
			'" . $sNilai4 . "',
			'" . $sTglTest1 . "',
			'" . $sTglTest2 . "',
			'" . $sTglTest3 . "',
			'" . $sTglTest4 . "',
			'" . $sKeterangan . "',
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
			$bGetKeyBack = True;
			
			$logvar1 = "Edit";
			$logvar2 = "New PAK Data";


		// Existing PAK (update)
		} else {
	
			$sSQL = "UPDATE pakgkjbekti SET 
			per_ID = '" . $sper_ID . "',
			Nama = '" . $sNama . "',
			Nama1 = '" . $sNama1 . "',
			AlamatSekolah = '" . $sAlamatSekolah . "',
			NoTelp = '" . $sNoTelp . "',
			Kelas = '" . $sKelas . "',
			KetKelas = '" . $sKetKelas . "',
			Semester = '" . $sSemester . "',
			TahunAjaran = '" . $sTahunAjaran . "',
			Tutor = '" . $sTutor . "',
			TutorID = '" . $sTutorID . "',
			Nilai1 = '" . $sNilai1 . "',
			Nilai2 = '" . $sNilai2 . "',
			Nilai3 = '" . $sNilai3 . "',
			Nilai4 = '" . $sNilai4 . "',
			TglTest1 = '" . $sTglTest1 . "',
			TglTest2 = '" . $sTglTest2 . "',
			TglTest3 = '" . $sTglTest3 . "',
			TglTest4 = '" . $sTglTest4 . "',
			Keterangan = '" . $sKeterangan . "',
			DateLastEdited = '" . date("YmdHis") . "', 
			EditedBy = '" . $_SESSION['iUserID'] ;
					
			$sSQL .= "' WHERE PakID = " . $iPakID;

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update PAK Data";
		}

		//Execute the SQL
		RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPakID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PakEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPakID);
		}
		else if (isset($_POST["PakSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectList.php?mode=pak&amp;GID=$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PakEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPakID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM pakgkjbekti  WHERE PakID = " . $iPakID;
		$rspak = RunQuery($sSQL);
		extract(mysql_fetch_array($rspak));

		$sPakID = $PakID;
		$sper_ID = $per_ID;
		$sNama = $Nama;
		$sNama1 = $Nama1;
		$sAlamatSekolah = $AlamatSekolah;
		$sNoTelp = $NoTelp;
		$sKelas = $Kelas;
		$sKetKelas = $KetKelas;
		$sSemester = $Semester;
		$sTahunAjaran = $TahunAjaran;
		$sTutor = $Tutor;
		$sTutorID = $TutorID;
		$sNilai1 = $Nilai1;
		$sNilai2 = $Nilai2;
		$sNilai3 = $Nilai3;
		$sNilai4 = $Nilai4;
		$sTglTest1 = $TglTest1;
		$sTglTest2 = $TglTest2;
		$sTglTest3 = $TglTest3;
		$sTglTest4 = $TglTest4;
		$sKeterangan = $Keterangan;
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
	}
}

//Get Student Names for the drop-down
$sSQL = "SELECT * FROM person_per a JOIN family_fam b ON a.per_fam_ID=b.fam_ID WHERE (per_cls_ID <3 AND per_fmr_ID >2 ) ORDER BY per_firstname";
$rsNamamurid = RunQuery($sSQL);

//Get Tutor Names for the drop-down
$sSQL = "SELECT * FROM paktutor ORDER BY TutorID";
$rsNamaTutor = RunQuery($sSQL);

require "Include/Header.php";

?>

<form method="post" action="PakEditor.php?PakID=<?php echo $iPakID; ?>" name="PakEditor">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="PakSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"PakSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iPakID) > 0) 
{ echo "PakView.php?PakID=" . $iPakID."&amp;GID=$refresh"; 
} else {echo "SelectList.php?mode=pak&amp;GID=$refresh"; 
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
				<td colspan="2" align="center"><h3><?php echo gettext("Data Standar"); ?></h3></td>
			</tr>
		
			<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Nama Murid:"); ?></td>
				<td class="TextColumn">
					<select name="per_ID" size="18">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<option value="0">-----------------------</option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamamurid))
						{
							extract($aRow);

							echo "<option value=\"" . $per_ID . "\"";
							if ($sper_ID == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName . "&nbsp; - " . $fam_Name . "&nbsp; - " . $fam_WorkPhone;
						}
						?>

					</select>
				</td>
			</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nama (Jika bukan warga):"); ?></td>
		<td class="TextColumn"><input type="text" name="Nama" id="Nama" value="<?php echo htmlentities(stripslashes($sNama),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNamaError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Alamat Sekolah:"); ?></td>
		<td class="TextColumn"><input type="text" name="AlamatSekolah" id="AlamatSekolah" value="<?php echo htmlentities(stripslashes($sAlamatSekolah),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sAlamatSekolahError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("No Telp:"); ?></td>
		<td class="TextColumn"><input type="text" name="NoTelp" id="NoTelp" value="<?php echo htmlentities(stripslashes($sNoTelp),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNoTelpError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kelas :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="Kelas">
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				<option value="1" <?php if ($sKelas == 1) { echo "selected"; } ?>><?php echo gettext("1 - SD"); ?></option>
				<option value="2" <?php if ($sKelas == 2) { echo "selected"; } ?>><?php echo gettext("2 - SD"); ?></option>
				<option value="3" <?php if ($sKelas == 3) { echo "selected"; } ?>><?php echo gettext("3 - SD"); ?></option>
				<option value="4" <?php if ($sKelas == 4) { echo "selected"; } ?>><?php echo gettext("4 - SD"); ?></option>
				<option value="5" <?php if ($sKelas == 5) { echo "selected"; } ?>><?php echo gettext("5 - SD"); ?></option>
				<option value="6" <?php if ($sKelas == 6) { echo "selected"; } ?>><?php echo gettext("6 - SD"); ?></option>
				<option value="7" <?php if ($sKelas == 7) { echo "selected"; } ?>><?php echo gettext("7 - SMP"); ?></option>
				<option value="8" <?php if ($sKelas == 8) { echo "selected"; } ?>><?php echo gettext("8 - SMP"); ?></option>
				<option value="9" <?php if ($sKelas == 9) { echo "selected"; } ?>><?php echo gettext("9 - SMP"); ?></option>
				<option value="10" <?php if ($sKelas == 10) { echo "selected"; } ?>><?php echo gettext("10 - SMA"); ?></option>
				<option value="11" <?php if ($sKelas == 11) { echo "selected"; } ?>><?php echo gettext("11 - SMA"); ?></option>
				<option value="12" <?php if ($sKelas == 12) { echo "selected"; } ?>><?php echo gettext("12 - SMA"); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan Kelas"); ?></td>
		<td class="TextColumn"><input type="text" name="KetKelas" id="KetKelas" value="<?php echo htmlentities(stripslashes($sKetKelas),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKetKelasError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Semester :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="Semester">
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				<option value="1" <?php if ($sSemester == 1) { echo "selected"; } ?>><?php echo gettext("Semester Ganjil"); ?></option>
				<option value="2" <?php if ($sSemester == 2) { echo "selected"; } ?>><?php echo gettext("Semester Genap"); ?></option>
				<option value="3" <?php if ($sSemester == 3) { echo "selected"; } ?>><?php echo gettext("Mid Semester Ganjil"); ?></option>
				<option value="4" <?php if ($sSemester == 4) { echo "selected"; } ?>><?php echo gettext("Mid Semester Genap"); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Tahun Ajaran :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="TahunAjaran">
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				<option value="10" <?php if ($sTahunAjaran == 10) { echo "selected"; } ?>><?php echo gettext("2010/2011"); ?></option>
				<option value="11" <?php if ($sTahunAjaran == 11) { echo "selected"; } ?>><?php echo gettext("2011/2012"); ?></option>
				<option value="12" <?php if ($sTahunAjaran == 12) { echo "selected"; } ?>><?php echo gettext("2012/2013"); ?></option>
				<option value="13" <?php if ($sTahunAjaran == 13) { echo "selected"; } ?>><?php echo gettext("2013/2014"); ?></option>
				<option value="14" <?php if ($sTahunAjaran == 14) { echo "selected"; } ?>><?php echo gettext("2014/2015"); ?></option>
				<option value="15" <?php if ($sTahunAjaran == 15) { echo "selected"; } ?>><?php echo gettext("2015/2016"); ?></option>
				<option value="16" <?php if ($sTahunAjaran == 16) { echo "selected"; } ?>><?php echo gettext("2016/2017"); ?></option>
				<option value="17" <?php if ($sTahunAjaran == 17) { echo "selected"; } ?>><?php echo gettext("2017/2018"); ?></option>
				<option value="18" <?php if ($sTahunAjaran == 18) { echo "selected"; } ?>><?php echo gettext("2018/2019"); ?></option>
				<option value="19" <?php if ($sTahunAjaran == 19) { echo "selected"; } ?>><?php echo gettext("2019/2020"); ?></option>
				<option value="20" <?php if ($sTahunAjaran == 20) { echo "selected"; } ?>><?php echo gettext("2020/2021"); ?></option>
				<option value="21" <?php if ($sTahunAjaran == 21) { echo "selected"; } ?>><?php echo gettext("2021/2022"); ?></option>
				<option value="22" <?php if ($sTahunAjaran == 22) { echo "selected"; } ?>><?php echo gettext("2022/2023"); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Tutor/Pengajar :"); ?></td>
		<td class="TextColumnWithBottomBorder">
					<select name="TutorID" >
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaTutor))
						{
							extract($aRow);

							echo "<option value=\"" . $TutorID . "\"";
							if ($sTutorID == $TutorID) { echo " selected"; }
							echo ">" . $TutorName;
						}
						?>

					</select>
		</td>
	</tr>
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Test1:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglTest1" value="<?php echo $sTglTest1; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTglTest1 ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nilai 1 (A):"); ?></td>
		<td class="TextColumn"><input type="text" name="Nilai1" id="Nilai1" value="<?php echo htmlentities(stripslashes($sNilai1),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNilai1Error ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Test2:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglTest2" value="<?php echo $sTglTest2; ?>" maxlength="10" id="sel2" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel2', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTglTest2 ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nilai 2 (B) :"); ?></td>
		<td class="TextColumn"><input type="text" name="Nilai2" id="Nilai2" value="<?php echo htmlentities(stripslashes($sNilai2),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNilai2Error ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Test3:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglTest3" value="<?php echo $sTglTest1; ?>" maxlength="10" id="sel3" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel3', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTglTest3 ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nilai 3 (C) :"); ?></td>
		<td class="TextColumn"><input type="text" name="Nilai3" id="Nilai3" value="<?php echo htmlentities(stripslashes($sNilai3),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNilai3Error ?></font></td>
	</tr>
<?php /*
**	<tr>
**		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Test4:"); ?></td>
		<td class="TextColumn"><input type="text" name="TglTest4" value="<?php echo $sTglTest1; ?>" maxlength="10" id="sel4" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel4', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTglTest4 ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nilai 4:"); ?></td>
		<td class="TextColumn"><input type="text" name="Nilai4" id="Nilai4" value="<?php echo htmlentities(stripslashes($sNilai4),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNilai4Error ?></font></td>
	</tr>
**/ ?> 
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan :"); ?></td>
		<td class="TextColumn"><input type="text" name="Keterangan" id="Keterangan" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKeteranganError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn">Catatan :</td>
		<td class="TextColumn"> Nilai A = Nilai Kerajinan , Bobot 30%</td>
	</tr>
	<tr>
		<td class="LabelColumn"></td>
		<td class="TextColumn"> Nilai B = Nilai Tugas , Bobot 30%</td>
	</tr>
	<tr>
		<td class="LabelColumn"></td>
		<td class="TextColumn"> Nilai C = Nilai Test  , Bobot 40%</td>
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
		$logvar2 = "PAK Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPakID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
