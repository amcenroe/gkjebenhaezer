<?php
/*******************************************************************************
 *
 *  filename    : PengeluaranPPPGEditor.php
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
$sPageTitle = gettext("Editor Pengeluaran PPPG");

//Get the PengeluaranPPPGID out of the querystring
$iPengeluaranPPPGID = FilterInput($_GET["PengeluaranPPPGID"],'int');

$iTGL = FilterInput($_GET["TGL"]);
$iPKL = FilterInput($_GET["PKL"]);
$iKodeTI = FilterInput($_GET["KodeTI"],'int');
$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PengeluaranPPPGID= manually)
if (strlen($iPengeluaranPPPGID) > 0)
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

if (isset($_POST["PengeluaranSubmit"]) || isset($_POST["PengeluaranSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	

	$sPengeluaranPPPGID = FilterInput($_POST["PengeluaranPPPGID"]);
	$sTanggal = FilterInput($_POST["Tanggal"]);
	$sPukul = FilterInput($_POST["Pukul"]);
	$sKodeTI = FilterInput($_POST["KodeTI"]);
	$sDiserahkanKepada = FilterInput($_POST["DiserahkanKepada"]);
	$sKeperluan = FilterInput($_POST["Keperluan"]);
	$sKeterangan = FilterInput($_POST["Keterangan"]);
	$sJumlah = FilterInput($_POST["Jumlah"]);
	$sPos = FilterInput($_POST["Pos"]);

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
		if (strlen($iPengeluaranPPPGID) < 1)
		{
			 	
			if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

			$sSQL = "INSERT INTO PengeluaranPPPG ( 	

			Tanggal,
			Pukul,
			KodeTI,
			DiserahkanKepada,
			Keperluan,
			Keterangan,
			Jumlah,
			Pos,
			DateEntered,
			EnteredBy	)
			VALUES ( 

			'" . $sTanggal . "',	
			'" . $sPukul . "',	
			'" . $sKodeTI . "',	
			'" . $sDiserahkanKepada . "',	
			'" . $sKeperluan . "',	
			'" . $sKeterangan . "',	
			'" . $sJumlah . "',	
			'" . $sPos . "',	

			'" . date("YmdHis") . "',
			'" . $_SESSION['iUserID'] . "')";
		
			$bGetKeyBack = True;
			
		//	echo $sSQL;
			
			$logvar1 = "Edit";
			$logvar2 = "New Daftar Pengeluaran PPPG";


		// Existing Baptis (update)
		} else {
			 
	//update the Baptis table
			
			$sSQL = "UPDATE PengeluaranPPPG SET 
		
			Tanggal = '" . $sTanggal . "',
			Pukul = '" . $sPukul . "',
			KodeTI = '" . $sKodeTI . "',
			DiserahkanKepada = '" . $sDiserahkanKepada . "',
			Keperluan = '" . $sKeperluan . "',
			Keterangan = '" . $sKeterangan . "',
			Jumlah = '" . $sJumlah . "',
			Pos = '" . $sPos . "',
			DateLastEdited = '" . date("YmdHis") . "',
			EditedBy = '" . $_SESSION['iUserID'] ;
				
			$sSQL .= "' WHERE PengeluaranPPPGID = " . $iPengeluaranPPPGID;

		//	echo $sSQL;
	
			$sSQL2 = "";
			
			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Daftar Pengeluaran PPPG";
		}

		//Execute the SQL
		RunQuery($sSQL);
		
		if($sSQL2 ==""){ echo "";}else{	RunQuery($sSQL2);}
		
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPengeluaranPPPGID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

		// Check for redirection to another page after saving information: (ie. PengeluaranPPPGEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iPengeluaranPPPGID);
		}
		else if (isset($_POST["PengeluaranSubmit"]))
		{
			//Send to the view of this PAK
			Redirect("SelectListApp3.php?mode=PengeluaranPPPG&amp;GID=$refresh");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("PengeluaranPPPGEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iPengeluaranPPPGID) > 0)
	{
		//Editing....
		//Get all the data on this record

		$sSQL = "SELECT * FROM PengeluaranPPPG  WHERE PengeluaranPPPGID = " . $iPengeluaranPPPGID;
		$rsBaptis = RunQuery($sSQL);
		extract(mysql_fetch_array($rsBaptis));
		
		$sPengeluaranPPPGID = $PengeluaranPPPGID;
		$sTanggal = $Tanggal;
		$sPukul = $Pukul;
		$sKodeTI = $KodeTI;
		$sDiserahkanKepada = $DiserahkanKepada;
		$sKeperluan = $Keperluan;
		$sKeterangan = $Keterangan;
		$sJumlah = $Jumlah;
		$sPos = $Pos;
		
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


//Get Lokasi TI Names for the drop-down
$sSQL = "SELECT * FROM LokasiTI ORDER BY KodeTI";
$rsNamaTempatIbadah = RunQuery($sSQL);

//Get Jenis PPPG for the drop-down
$sSQL = "SELECT * FROM JenisPengeluaranPPPG ORDER BY KodeJenis";
$rsJenisPengeluaranPPPG = RunQuery($sSQL);


?>
      <style type="text/css">
         input.right{
         text-align:right;
         }
      </style> 
<form method="post" action="PengeluaranPPPGEditor.php?PengeluaranPPPGID=<?php echo $iPengeluaranPPPGID; ?>" name="PengeluaranPPPG">

<table cellpadding="3" align="center" valign="top" border="0">

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Simpan"); ?>" name="PengeluaranSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Simpan dan Tambah") . "\" name=\"PengeluaranSubmitAndAdd\">"; } ?>
			<input type="button" class="icButton" value="<?php echo gettext("Batal"); ?>" name="pakCancel" onclick="javascript:document.location='<?php if (strlen($iPengeluaranPPPGID) > 0) 
{ echo "SelectListApp3.php?mode=PengeluaranPPPG&amp;$refresh"; 
} else {echo "SelectListApp3.php?mode=PengeluaranPPPG&amp;$refresh"; 
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
		<td class="LabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal Pengeluaran:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" name="Tanggal" value="<?php echo $sTanggal; ?>" maxlength="10" id="sel0" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel0', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sTanggalError ?></font></td>
	
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
       	
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Jenis Pengeluaran PPPG:"); ?></td>
				<td colspan="0" class="TextColumn">
					<select name="Pos">
						<option value="0" selected><?php echo gettext("Tdk diketahui/lain-lain"); ?></option>
						<?php
						while ($aRow = mysql_fetch_array($rsJenisPengeluaranPPPG))
						{
							extract($aRow);

							echo "<option value=\"" . $KodeJenis . "\"";
							if ($sPos == $KodeJenis) { echo " selected"; }
							echo ">" . $NamaJenis ;
						}
						?>
					</select>
				</td>
				
	</tr>	
			<tr>
				<td colspan="4" align="center"><h3><?php echo gettext("Data Pengeluaran"); ?></h3></td>
			</tr>

	<tr>
		<td class="LabelColumn"><?php echo gettext("Diserahkan Kepada:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=50 name="DiserahkanKepada" id="DiserahkanKepada" value="<?php echo htmlentities(stripslashes($sDiserahkanKepada),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sDiserahkanKepadaError ?></font></td>
		</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Untuk keperluan:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=50 name="Keperluan" id="Keperluan" value="<?php echo htmlentities(stripslashes($sKeperluan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKeperluanError ?></font></td>
	</tr>
	<tr>	
		<td class="LabelColumn"><?php echo gettext("Jumlah Pengeluaran :"); ?></td>
		<td class="TextColumn" colspan="0">Rp. <input class="right" type="text" size=25 name="Jumlah" id="Jumlah" value="<?php echo htmlentities(stripslashes($sJumlah),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sJumlahError ?></font></td>
	</tr>
	<tr>
		<td class="LabelColumn"><?php echo gettext("Keterangan Tambahan:"); ?></td>
		<td class="TextColumn" colspan="0"><input type="text" size=50 name="Keterangan" id="Keterangan" value="<?php echo htmlentities(stripslashes($sKeterangan),ENT_NOQUOTES, "UTF-8"); ?>"><br><font color="red"><?php echo $sKeteranganError ?></font></td>
	</tr>

	</tr>
	</table>
</td>

	</form>

</table>

<?php
		$logvar1 = "Edit";
		$logvar2 = "Pengeluaran PPPG Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPengeluaranPPPGID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
require "Include/Footer.php";
?>