<?php
/*******************************************************************************
 *
 *  filename    : PersembahanBulananEditor.php
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

//echo $refresh;

//Set the page title
$sPageTitle = gettext("Editor Persembahan Bulanan");

//Get the PersembahanBulananID out of the querystring
$iPersembahanBulananID = FilterInput($_GET["PersembahanBulananID"],'int');


$iTGL = FilterInput($_GET["TGL"]);
$iPKL = FilterInput($_GET["PKL"]);
$iKodeTI = FilterInput($_GET["KodeTI"],'int');
$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;
// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PersembahanBulananID= manually)
if (strlen($iPersembahanBulananID) > 0)
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

if (isset($_POST["PersembahanSubmit"]) || isset($_POST["PersembahanSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	

	$sPersembahanBulananID = FilterInput($_POST["PersembahanBulananID"]);
	$sTanggal = FilterInput($_POST["Tanggal"]);
	$sPukul = FilterInput($_POST["Pukul"]);
	$sKodeTI = FilterInput($_POST["KodeTI"]);
	$sKelompok = FilterInput($_POST["Kelompok"]);
	$sNomorKartu = FilterInput($_POST["NomorKartu"]);
	$sKodeNama = FilterInput($_POST["KodeNama"]);
	$sBulan1 = FilterInput($_POST["Bulan1"]);
	$sBulan2 = FilterInput($_POST["Bulan2"]);
	$sBulanan = FilterInput($_POST["Bulanan"]);
	$sSyukur = FilterInput($_POST["Syukur"]);
	$sULK = FilterInput($_POST["ULK"]);

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
		if (strlen($iPersembahanBulananID) < 1)
		{
			 	
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO PersembahanBulanan ( 	

			Tanggal,
			Pukul,
			KodeTI,
			Kelompok,
			NomorKartu,
			KodeNama,
			Bulan1,
			Bulan2,
			Bulanan,
			Syukur,
			ULK,
			
			DateEntered,
			EnteredBy	)
			VALUES ( 

			'" . $sTanggal . "',	
			'" . $sPukul . "',	
			'" . $sKodeTI . "',	
			'" . $sKelompok . "',	
			'" . $sNomorKartu . "',	
			'" . $sKodeNama . "',	
			'" . $sBulan1 . "',	
			'" . $sBulan2 . "',	
			'" . $sBulanan . "',	
			'" . $sSyukur . "',	
			'" . $sULK . "',	
			
			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
		
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Daftar Persembahan Bulanan";


		// Existing Baptis (update)
		} else {
			 
	//update the Baptis table
			
			$sSQL = "UPDATE PersembahanBulanan SET 
			
			Tanggal = '" . $sTanggal . "',
			Pukul = '" . $sPukul . "',
			KodeTI = '" . $sKodeTI . "',
			Kelompok = '" . $sKelompok . "',
			NomorKartu = '" . $sNomorKartu . "',
			KodeNama = '" . $sKodeNama . "',
			Bulan1 = '" . $sBulan1 . "',
			Bulan2 = '" . $sBulan2 . "',
			Bulanan = '" . $sBulanan . "',
			Syukur = '" . $sSyukur . "',
			ULK = '" . $sULK . "',
	
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE PersembahanBulananID = " . $iPersembahanBulananID;

		//	echo $sSQL;
	
			$sSQL2 = "";
			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Daftar Persembahan Bulanan";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		if($sSQL2 ==""){ echo "";}else{	RunQuery($sSQL2);}
		
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersembahanBulananID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PersembahanBulananEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPersembahanBulananID);
		}
		else if (isset($_POST["PersembahanSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectListApp3.php?mode=PersembahanBulanan&amp;GID=$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PersembahanBulananEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPersembahanBulananID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM PersembahanBulanan  WHERE PersembahanBulananID = " . $iPersembahanBulananID;
		$rsBaptis = RunQuery($sSQL);
		extract(mysql_fetch_array($rsBaptis));
		
		$sPersembahanBulananID = $PersembahanBulananID;
		$sTanggal = $Tanggal;
		$sPukul = $Pukul;
		$sKodeTI = $KodeTI;
		$sKelompok = $Kelompok;
		$sNomorKartu = $NomorKartu;
		$sKodeNama = $KodeNama;
		$sBulan1 = $Bulan1;
		$sBulan2 = $Bulan2;
		$sBulanan = $Bulanan;
		$sSyukur = $Syukur;
		$sULK = $ULK;
		
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
		
		//Date from source
		if (strlen($iTGL) AND strlen($iPKL) AND strlen($iKodeTI) ){
		echo $iTGL,$iPKL,$iKodeTI;
		$sTanggal=$iTGL;
		$sPukul=$iPKL;
		$sKodeTI=$iKodeTI;
	}

	}
}


require "Include/Header.php";


$iTGL = FilterInput($_GET["TGL"]);
$iPKL = FilterInput($_GET["PKL"]);
$iKodeTI = FilterInput($_GET["KodeTI"],'int');


//Get Kelompok for the drop-down
$sSQL = "Select  grp_ID as KodeKelompok, grp_Name as NamaKelompok ,grp_Description as Kelompok from group_grp
				order by grp_Name";
$rsNamaKelompok = RunQuery($sSQL);

//Get Lokasi TI Names for the drop-down
$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
$rsNamaTempatIbadah = RunQuery($sSQL);

//Get Bulan Names for the drop-down
$sSQL = "SELECT kode as KodeBulan, nama_bulan as NamaBulan FROM bulan ORDER BY Kode";
$rsNamaBulan1 = RunQuery($sSQL);

//Get Bulan Names for the drop-down
$sSQL = "SELECT kode as KodeBulan, nama_bulan as NamaBulan FROM bulan ORDER BY Kode";
$rsNamaBulan2 = RunQuery($sSQL);

?>
      <style type="text/css">
         input.right{
         text-align:right;
         }
      </style> 
<form method="post" action="PersembahanBulananEditor.php?PersembahanBulananID=<?php echo $iPersembahanBulananID; ?>" name="BacaanAkitabEditor">

<table cellpadding="3" align="center" valign="top" border="0">

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="PersembahanSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"PersembahanSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iPersembahanBulananID) > 0) 
{ echo "SelectListApp3.php?mode=PersembahanBulanan&amp;GID=$refresh"; 
} else {echo "SelectListApp3.php?mode=PersembahanBulanan&amp;GID=$refresh"; 
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
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Persembahan:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalError ?></font></td>
	
		<td class="LabelColumn"><?php echo gettext("Pukul :"); ?></td>
		<td class="TextColumnWithBottomBorder" colspan="3">
			<select name="Pukul" >
				<option value="0" <?php if ($sPukul == "") { echo " selected"; }?> ><?php echo gettext("Tidak Diketahui"); ?></option>
				<option value="06.00 WIB"  <?php if ($sPukul == "06.00 WIB") { echo " selected"; }?> ><?php echo gettext("06.00 WIB CM1"); ?></option>
				<option value="06.30 WIB"  <?php if ($sPukul == "06.30 WIB") { echo " selected"; }?> ><?php echo gettext("06.30 WIB"); ?></option>
				<option value="07.00 WIB"  <?php if ($sPukul == "07.00 WIB") { echo " selected"; }?> ><?php echo gettext("07.00 WIB CKRG"); ?></option>
				<option value="07.30 WIB"  <?php if ($sPukul == "07.30 WIB") { echo " selected"; }?> ><?php echo gettext("07.30 WIB KRWG"); ?></option>
				<option value="08.00 WIB"  <?php if ($sPukul == "08.00 WIB") { echo " selected"; }?> ><?php echo gettext("08.00 WIB"); ?></option>
				<option value="08.30 WIB"  <?php if ($sPukul == "08.30 WIB") { echo " selected"; }?> ><?php echo gettext("08.30 WIB"); ?></option>
				<option value="09.00 WIB"  <?php if ($sPukul == "09.00 WIB") { echo " selected"; }?> ><?php echo gettext("09.00 WIB CM2"); ?></option>
				<option value="09.30 WIB"  <?php if ($sPukul == "09.30 WIB") { echo " selected"; }?> ><?php echo gettext("09.30 WIB"); ?></option>
				<option value="10.00 WIB"  <?php if ($sPukul == "10.00 WIB") { echo " selected"; }?> ><?php echo gettext("10.00 WIB"); ?></option>
				<option value="10.30 WIB"  <?php if ($sPukul == "10.30 WIB") { echo " selected"; }?> ><?php echo gettext("10.30 WIB"); ?></option>
				<option value="11.00 WIB"  <?php if ($sPukul == "11.00 WIB") { echo " selected"; }?> ><?php echo gettext("11.00 WIB"); ?></option>
				<option value="11.30 WIB"  <?php if ($sPukul == "11.30 WIB") { echo " selected"; }?> ><?php echo gettext("11.30 WIB"); ?></option>
				<option value="12.00 WIB"  <?php if ($sPukul == "12.00 WIB") { echo " selected"; }?> ><?php echo gettext("12.00 WIB"); ?></option>
				<option value="12.30 WIB"  <?php if ($sPukul == "12.30 WIB") { echo " selected"; }?> ><?php echo gettext("12.30 WIB"); ?></option>
				<option value="13.00 WIB"  <?php if ($sPukul == "13.00 WIB") { echo " selected"; }?> ><?php echo gettext("13.00 WIB"); ?></option>
				<option value="13.30 WIB"  <?php if ($sPukul == "13.30 WIB") { echo " selected"; }?> ><?php echo gettext("13.30 WIB"); ?></option>
				<option value="14.00 WIB"  <?php if ($sPukul == "14.00 WIB") { echo " selected"; }?> ><?php echo gettext("14.00 WIB"); ?></option>
				<option value="14.30 WIB"  <?php if ($sPukul == "14.30 WIB") { echo " selected"; }?> ><?php echo gettext("14.30 WIB"); ?></option>
				<option value="15.00 WIB"  <?php if ($sPukul == "15.00 WIB") { echo " selected"; }?> ><?php echo gettext("15.00 WIB"); ?></option>
				<option value="15.30 WIB"  <?php if ($sPukul == "15.30 WIB") { echo " selected"; }?> ><?php echo gettext("15.30 WIB"); ?></option>
				<option value="16.00 WIB"  <?php if ($sPukul == "16.00 WIB") { echo " selected"; }?> ><?php echo gettext("16.00 WIB"); ?></option>
				<option value="16.30 WIB"  <?php if ($sPukul == "16.30 WIB") { echo " selected"; }?> ><?php echo gettext("16.30 WIB"); ?></option>
				<option value="17.00 WIB"  <?php if ($sPukul == "17.00 WIB") { echo " selected"; }?> ><?php echo gettext("17.00 WIB CM3"); ?></option>
				<option value="17.30 WIB"  <?php if ($sPukul == "17.30 WIB") { echo " selected"; }?> ><?php echo gettext("17.30 WIB"); ?></option>
				<option value="18.00 WIB"  <?php if ($sPukul == "18.00 WIB") { echo " selected"; }?> ><?php echo gettext("18.00 WIB"); ?></option>
				<option value="18.30 WIB"  <?php if ($sPukul == "18.30 WIB") { echo " selected"; }?> ><?php echo gettext("18.30 WIB"); ?></option>
				<option value="19.00 WIB"  <?php if ($sPukul == "19.00 WIB") { echo " selected"; }?> ><?php echo gettext("19.00 WIB"); ?></option>
				<option value="19.30 WIB"  <?php if ($sPukul == "19.30 WIB") { echo " selected"; }?> ><?php echo gettext("19.30 WIB"); ?></option>
				<option value="20.00 WIB"  <?php if ($sPukul == "20.00 WIB") { echo " selected"; }?> ><?php echo gettext("20.00 WIB"); ?></option>
				<option value="20.30 WIB"  <?php if ($sPukul == "20.30 WIB") { echo " selected"; }?> ><?php echo gettext("20.30 WIB"); ?></option>
				<option value="21.00 WIB"  <?php if ($sPukul == "21.00 WIB") { echo " selected"; }?> ><?php echo gettext("21.00 WIB"); ?></option>
				<option value="21.30 WIB"  <?php if ($sPukul == "21.30 WIB") { echo " selected"; }?> ><?php echo gettext("21.30 WIB"); ?></option>

				
			</select>
		</td>

	</tr>
	<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Tempat Ibadah:"); ?></td>
				<td colspan="0" class="TextColumn">
					<select name="KodeTI">
						<option value="0" selected><?php echo gettext("Tempat Ibadah Lain"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaTempatIbadah))
						{
							extract($aRow);

							echo "<option value=\"" . $KodeTI . "\"";
							if ($sKodeTI == $KodeTI) { echo " selected"; }
							echo ">" . $NamaTI . "&nbsp; - " .  $AlamatTI1 . "" . $AlamatTI2 . "-" . $KotaTI ;
						}
						?>
					</select>
				</td>
       	
		<td class="LabelColumn"><?php echo gettext("Tempat Ibadah lainnya:"); ?></td>
		<td class="TextColumn" colspan="4"><input type="text" size=50 name="TempatPF" id="TempatPF" value="<?php echo htmlentities(stripslashes($sTempatPF),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTempatPFError ?></font></td>
	</tr>
			<tr>
				<td colspan="4" align="center"><h3><?php echo gettext("Data Persembahan"); ?></h3></td>
			</tr>
	<tr>	
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Kelompok:"); ?></td>
				<td>
						<select name="Kelompok">
						<option value="0" selected><?php echo gettext("Tidak Ada Ket"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaKelompok))
						{
							extract($aRow);
							echo "<option value=\"" . $NamaKelompok . "\"";
							if ($sKelompok == $NamaKelompok) { echo " selected"; }
							echo ">" . $NamaKelompok . " - " . $Kelompok ;
						}
						?>
					</select>
				</td>
	</tr>
	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Nomor Kartu:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=10 name="NomorKartu" id="NomorKartu" value="<?php echo htmlentities(stripslashes($sNomorKartu),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sNomorKartuError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kode Nama:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=25 name="KodeNama" id="KodeNama" value="<?php echo htmlentities(stripslashes($sKodeNama),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKodeNamaError ?></font></td>
	</tr>
	<tr>	
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Persembahan Bulan:"); ?></td>
				<td>
						<select name="Bulan1">
						<?php
						while ($aRow = mysql_fetch_array($rsNamaBulan1))
						{
							extract($aRow);
							echo "<option value=\"" . $KodeBulan . "\"";
							if ($sBulan1 == $KodeBulan) { echo " selected"; }
							echo ">" . $KodeBulan . " - " . $NamaBulan ;
						}
						?>
					</select>
					-
						<select name="Bulan2">
						<?php
						while ($aRow = mysql_fetch_array($rsNamaBulan2))
						{
							extract($aRow);
							echo "<option value=\"" . $KodeBulan . "\"";
							if ($sBulan2 == $KodeBulan) { echo " selected"; }
							echo ">" . $KodeBulan . " - " . $NamaBulan ;
						}
						?>
					</select>
				</td>
	</tr>
	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Persembahan Bulanan:"); ?></td>
		<td class="TextColumn" colspan="0">Rp. <input class="right" type="text" size=25 name="Bulanan" id="Bulanan" value="<?php echo htmlentities(stripslashes($sBulanan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sBulananError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Persembahan Syukur:"); ?></td>
		<td class="TextColumn" colspan="0">Rp. <input class="right" type="text" size=25 name="Syukur" id="Syukur" value="<?php echo htmlentities(stripslashes($sSyukur),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sSyukurError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Persembahan ULK:"); ?></td>
		<td class="TextColumn" colspan="0">Rp. <input class="right" type="text" size=25 name="ULK" id="ULK" value="<?php echo htmlentities(stripslashes($sULK),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sULKError ?></font></td>
	</tr>	

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
		$logvar2 = "Pelayanan Firman Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersembahanBulananID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>
