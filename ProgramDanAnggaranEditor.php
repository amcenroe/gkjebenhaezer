<?php
/*******************************************************************************
 *
 *  filename    : ProgramDanAnggaranEditor.php
 *  copyright   : 2014 Erwin Pratama for GKJ Bekasi Timur
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
$sPageTitle = gettext("Editor Program Dan Anggaran");

//Get the RabID out of the querystring
$iRabID = FilterInput($_GET["RabID"],'int');

$iTGL = FilterInput($_GET["TGL"]);
$iPKL = FilterInput($_GET["PKL"]);
$iKodeTI = FilterInput($_GET["KodeTI"],'int');

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?RabID= manually)
if (strlen($iRabID) > 0)
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

if (isset($_POST["RabSubmit"]) || isset($_POST["RabSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	

	$sRabID = FilterInput($_POST["RabID"]);
	$sTahun = FilterInput($_POST["Tahun"]);
	$sKomisiID = FilterInput($_POST["KomisiID"]);
	$sProgram = FilterInput($_POST["Program"]);
	$sKegiatan = FilterInput($_POST["Kegiatan"]);
	$sTolokUkur = FilterInput($_POST["TolokUkur"]);
	$sJadwal = FilterInput($_POST["Jadwal"]);
	$sAggKasJemaat = FilterInput($_POST["AggKasJemaat"]);
	$sAggLainLain = FilterInput($_POST["AggLainLain"]);
	$sKeterangan = FilterInput($_POST["Keterangan"]);

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
		if (strlen($iRabID) < 1)
		{
			 	
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO ProgramDanAnggaran ( 	
			Tahun,
			KomisiID,
			Program,
			Kegiatan,
			TolokUkur,
			Jadwal,
			AggKasJemaat,
			AggLainLain,
			Keterangan,
			DateEntered,
			EnteredBy	)
			VALUES ( 
			'" . $sTahun . "',	
			'" . $sKomisiID . "',	
			'" . $sProgram . "',	
			'" . $sKegiatan . "',	
			'" . $sTolokUkur . "',	
			'" . $sJadwal . "',	
			'" . $sAggKasJemaat . "',	
			'" . $sAggLainLain . "',	
			'" . $sKeterangan . "',	

			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
		
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Daftar Program Dan Anggaran";


		// Existing Baptis (update)
		} else {
			 
	//update the Baptis table
			
			$sSQL = "UPDATE ProgramDanAnggaran SET 

			Tahun = '" . $sTahun . "',
			Program = '" . $sProgram . "',
			Kegiatan = '" . $sKegiatan . "',
			TolokUkur = '" . $sTolokUkur . "',
			Jadwal = '" . $sJadwal . "',
			AggKasJemaat = '" . $sAggKasJemaat . "',
			AggLainLain = '" . $sAggLainLain . "',
			Keterangan = '" . $sKeterangan . "',

			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE RabID = " . $iRabID;

		//	echo $sSQL;
	
			$sSQL2 = "";
			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Daftar Program Dan Anggaran";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		if($sSQL2 ==""){ echo "";}else{	RunQuery($sSQL2);}
		
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iRabID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. ProgramDanAnggaranEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iRabID);
		}
		else if (isset($_POST["RabSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectListApp2.php?mode=ProgramDanAnggaran&amp;$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("ProgramDanAnggaranEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iRabID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM ProgramDanAnggaran  WHERE RabID = " . $iRabID;
		$rsBaptis = RunQuery($sSQL);
		extract(mysql_fetch_array($rsBaptis));

		$sTahun = $Tahun;
		$sKomisiID = $KomisiID;
		$sProgram = $Program;
		$sKegiatan = $Kegiatan;
		$sTolokUkur = $TolokUkur;
		$sJadwal = $Jadwal;
		$sAggKasJemaat = $AggKasJemaat;
		$sAggLainLain = $AggLainLain;
		$sKeterangan = $Keterangan;
		
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


//Get Komisi Names for the drop-down
$sSQL = "SELECT * FROM MasterKomisi a
		LEFT JOIN MasterBidang b ON a.BidangID=b.BidangID";
$rsNamaKomisi = RunQuery($sSQL);

?>
      <style type="text/css">
         input.right{
         text-align:right;
         }
      </style> 
<form method="post" action="ProgramDanAnggaranEditor.php?RabID=<?php echo $iRabID; ?>" name="ProgramDanAnggaran">

<table cellpadding="3" align="center" valign="top" border="0">

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="RabSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"RabSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iRabID) > 0) 
{ echo "SelectListApp2.php?mode=ProgramDanAnggaran&amp;$refresh"; 
} else {echo "SelectListApp2.php?mode=ProgramDanAnggaran&amp;$refresh"; 
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
		<td class="LabelColumn"><?php echo gettext("Tahun Anggaran :"); ?></td>
		<td class="TextColumnWithBottomBorder">
			<select name="Tahun">
				<option value="0"><?php echo gettext("Pilih"); ?></option>
				
				<option value="2010" <?php if ($sTahun == '2010') { echo "selected"; } ?>><?php echo gettext("2010"); ?></option>
				<option value="2011" <?php if ($sTahun == '2011') { echo "selected"; } ?>><?php echo gettext("2011"); ?></option>				
				<option value="2012" <?php if ($sTahun == '2012') { echo "selected"; } ?>><?php echo gettext("2012"); ?></option>				
				<option value="2013" <?php if ($sTahun == '2013') { echo "selected"; } ?>><?php echo gettext("2013"); ?></option>
				<option value="2014" <?php if ($sTahun == '2014') { echo "selected"; } ?>><?php echo gettext("2014"); ?></option>
				<option value="2015" <?php if ($sTahun == '2015') { echo "selected"; } ?>><?php echo gettext("2015"); ?></option>
				<option value="2016" <?php if ($sTahun == '2016') { echo "selected"; } ?>><?php echo gettext("2016"); ?></option>
				<option value="2017" <?php if ($sTahun == '2017') { echo "selected"; } ?>><?php echo gettext("2017"); ?></option>
				<option value="2018" <?php if ($sTahun == '2018') { echo "selected"; } ?>><?php echo gettext("2018"); ?></option>
				<option value="2019" <?php if ($sTahun == '2019') { echo "selected"; } ?>><?php echo gettext("2019"); ?></option>
				<option value="2020" <?php if ($sTahun == '2020') { echo "selected"; } ?>><?php echo gettext("2020"); ?></option>		
			
			</select>
		</td>
	</tr>
	
	<tr>
				<td class="LabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Komisi:"); ?></td>
				<td colspan="0" class="TextColumn">
					<select name="KomisiID">
						<option value="0" selected><?php echo gettext("Tidak Diketahui"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsNamaKomisi))
						{
							extract($aRow);

							echo "<option value=\"" . $KomisiID . "\"";
							if ($sKomisiID == $KomisiID) { echo " selected"; }
							echo ">" . $NamaKomisi . " (" . $KodeKomisi . ") - Bid." .  $NamaBidang . " (" . $KodeBidang . ")" ;
						}
						?>
					</select>
				</td> 	
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Program:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=100 name="Program" id="Program" value="<?php echo htmlentities(stripslashes($sProgram),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sProgramError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Kegiatan:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=100 name="Kegiatan" id="Kegiatan" value="<?php echo htmlentities(stripslashes($sKegiatan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKegiatanError ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Tolok Ukur:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=100 name="TolokUkur" id="TolokUkur" value="<?php echo htmlentities(stripslashes($sTolokUkur),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sTolokUkurError ?></font></td>
	</tr>	
	<tr>
		<td class="LabelColumn"><?php echo gettext("Jadwal Pelaksanaan:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=100 name="Jadwal" id="Jadwal" value="<?php echo htmlentities(stripslashes($sJadwal),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sJadwalError ?></font></td>
	</tr>	
	<tr>	
		<td class="LabelColumn"><?php echo gettext("Anggaran dari Kas Jemaat :"); ?></td>
		<td class="TextColumn" colspan="0">Rp. <input class="right" type="text" size=25 name="AggKasJemaat" id="AggKasJemaat" value="<?php echo htmlentities(stripslashes($sAggKasJemaat),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sAggKasJemaatError ?></font></td>
	</tr>	
	<tr>	
		<td class="LabelColumn"><?php echo gettext("Anggaran Lain Lain :"); ?></td>
		<td class="TextColumn" colspan="0">Rp. <input class="right" type="text" size=25 name="AggLainLain" id="AggLainLain" value="<?php echo htmlentities(stripslashes($sAggLainLain),ENT_NOQUOTES, "UTF-8"); ?>">
		<br><font color="red"><?php echo $sAggLainLainError ?></font></td>
	</tr>		
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan :"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=100 name="Keterangan" id="Keterangan" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKeteranganError ?></font></td>
	</tr>	
	



	</tr>
	</table>
</td>

	</form>

</table>

<?php
		$logvar1 = "Edit";
		$logvar2 = "Program Dan Anggaran Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iRabID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>