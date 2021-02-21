<?php
/*******************************************************************************
 *
 *  filename    : Stiker103Editor.php
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
 *  ChurchInfo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";

//Set the page title
$sPageTitle = gettext("Editor - Cetak Stiker 103");

//Get the StikerID out of the querystring
$iStikerID = FilterInput($_GET["StikerID"],'int');
$iKategori = FilterInput($_GET["Kategori"],'int');

ob_start();
?>


<?php


// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?StikerID= manually)
if (strlen($iStikerID) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsStiker = RunQuery($sSQL);
	extract(mysql_fetch_array($rsStiker));

	if (mysql_num_rows($rsStiker) == 0)
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




if (isset($_POST["StikerSubmit"]) || isset($_POST["StikerSubmitAndAdd"]))
{
	//Get all the variables from the request object and assign them locally	
	$sStikerID = FilterInput($_POST["StikerID"]);
	$sWargaA1= FilterInput($_POST["WargaA1"]);
	$sWargaA2= FilterInput($_POST["WargaA2"]);
	$sWargaA3= FilterInput($_POST["WargaA3"]);
	$sWargaA4= FilterInput($_POST["WargaA4"]);
	
	$sWargaB1= FilterInput($_POST["WargaB1"]);
	$sWargaB2= FilterInput($_POST["WargaB2"]);
	$sWargaB3= FilterInput($_POST["WargaB3"]);
	$sWargaB4= FilterInput($_POST["WargaB4"]);
	
	$sWargaC1= FilterInput($_POST["WargaC1"]);
	$sWargaC2= FilterInput($_POST["WargaC2"]);
	$sWargaC3= FilterInput($_POST["WargaC3"]);
	$sWargaC4= FilterInput($_POST["WargaC4"]);
	

	//Initialize the error flag
	$bErrorFlag = false;

	// Validate Stiker Date if one was entered
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
		// New Stiker (add)
		if (strlen($iStikerID) < 1)
		{
	//		 $sKet1 = "e/MG-".$sDari."/".$sChurchCode."/";
			 $sKet2 =  dec2roman(date (m)). "/" .date('Y');
			 
	//		if (!$_SESSION['bFinance'] || strlen($sEnvelope) < 1)
				$sEnvelope = "NULL";

	//		$sSQL = "INSERT INTO SuratKeluar ( StikerID, Tanggal, Urgensi, Via, Dari, Institusi, Kepada, Alamat1, Alamat2, EStiker, Telp, Fax, SifatSurat, Hal, Lampiran, TypeLampiran, Keterangan, FollowUp, Status, Ket1, Ket2, Ket3, IsiSuratBalasan, TglDibalas, DateEntered, EnteredBy) 
	//		         VALUES ('" . $sStikerID . "','" . $sTanggal . "','" . $sUrgensi . "','" . $sVia . "','" . $sDari . "','" . $sInstitusi . "',
//					 '" . $sKepada . "','" . $sAlamat1 . "','" . $sAlamat2 . "','" . $sEStiker . "','" . $sTelp . "','" . $sFax . "','" . $sSifatSurat . "','" . $sHal . "','" . $sLampiran . "','" . $sTypeLampiran . "','" . $sKeterangan . "','" . $sFollowUp . "',
	//				 '" . $sStatus . "','" . $sKet1 . "','" . $sKet2 . "','" . $sKet3 . "',
	//				 '" . $sIsiSuratBalasan . "','" . $sTglDibalas . "','" . date("YmdHis") . "'," . $_SESSION['iUserID'] . ")";
			$bGetKeyBack = True;
			
			$logvar1 = "Add";
			$logvar2 = "New Print Stiker";

		// Existing Stiker (update)
		} else {
		

			$bGetKeyBack = false;

			$logvar1 = "Edit";
			$logvar2 = "Update Incoming Stiker";
		}

		//Execute the SQL
	//	RunQuery($sSQL);

		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iStikerID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
		//	RunQuery($sSQL);

		// Update the custom Stiker fields.

		// Check for redirection to another page after saving information: (ie. StikerOutEditor.php?previousPage=prev.php?a=1;b=2;c=3)
		if ($previousPage != "") {
			$previousPage = str_replace(";","&",$previousPage) ;
			Redirect($previousPage . $iStikerID);
		}
		else if (isset($_POST["StikerSubmit"]))
		{
			//Redirect("SelectListApp.php?mode=Stikerout");
			Redirect("PrintViewStiker103.php?mode=Stiker&A1=A1$sWargaA1&B1=B1$sWargaB1&C1=C1$sWargaC1&A2=A2$sWargaA2&B2=B2$sWargaB2&C2=C2$sWargaC2&A3=A3$sWargaA3&B3=B3$sWargaB3&C3=C3$sWargaC3&A4=A4$sWargaA4&B4=B4$sWargaB4&C4=C4$sWargaC4");

		}
		else
		{
			//Reload to editor to add another record
			Redirect("StikerOutEditor.php");
		}

	}
else {

	//FirstPass
	//Are we editing or adding?
	if (strlen($iStikerID) > 0)
	{
		//Editing....
		//Get all the data on this record

	
	}
	else
	{
		//Adding....
		//Set defaults
		$dTanggal = date("Y-m-d"); // Default friend date is today
		//Redirect("PrintViewStiker103.php?mode=Stiker&A1=$sWargaA1");
		
	}
}



require "Include/Header.php";


//Get Pengirim Names for the drop-down
$sSQL = "SELECT vol_ID, replace( `vol_Name` , 'Ketua', '' ) AS KodePengirim, replace(`vol_Description`, 'Ketua', '') as NamaPengirim FROM `volunteeropportunity_vol` WHERE `vol_Description` LIKE '%Ketua%' AND Vol_id <60";
$rsNamaPengirim = RunQuery($sSQL);



?>

<form method="post" action="Stiker103Editor.php?StikerID=<?php echo $iStikerID; ?>" name="StikerEditor">

		<table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 750px">
			<tbody>
				<tr>
			<td></td>	
			<td colspan="5" align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Print Preview"); ?>" name="StikerSubmit"> <br>
	        <font color="red"><blink>PERHATIAN! </blink></font> Cetakan ini menggunakan kertas Stiker Standard Tom&Jerry no.103 <br> sebelum mencetak pastikan top margin ,left dan right margin di set ke nilai 0, <br>
			Margin dapat di set di menu browser lewat menu: File - Page Setup - Margin/Header&Footer
		   </td>
				</tr>
				<tr>
				<td height="25px" ></td>
				</tr>
				<tr style="text-align: left ; vertical-align:top;  background-color: #ccffff" ;>
					<td width="5" > 
					</td>
					<td width="200"> 
					
					<?
					//Get Nama Warga for the drop-down
					$sSQL = "select per_ID , per_FirstName, per_WorkPhone 
					from person_per natural join person_custom
					where ( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
					AND c27 is not NULL )
					OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2
					order by per_workphone, per_FirstName ";					
					$rsNamaWargaA1 = RunQuery($sSQL);

					echo "Nama (Kolom A1)<br>
					<select name=\"WargaA1\" >";	
						echo "<option value=\"0\">Pilih Nama Warga</option>	";				
						while ($aRow = mysql_fetch_array($rsNamaWargaA1))
						{
							extract($aRow);
							echo "<option value=\"" . $per_ID . "\"";
							if ($sWargaA1 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone."-".$per_ID;
						}					
					echo"</select>	</select>";	
					?>
					</td>		
					<td width="25" > 
					</td>
					<td width="200">
					<?
					//Get Nama Warga for the drop-down
					$sSQL = "select per_ID , per_FirstName, per_WorkPhone 
					from person_per natural join person_custom
					where ( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
					AND c27 is not NULL )
					OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2
					order by per_workphone, per_FirstName ";					
					$rsNamaWargaB1 = RunQuery($sSQL);

					echo "Nama (Kolom B1)<br>
					<select name=\"WargaB1\" >";
						echo "<option value=\"0\">Pilih Nama Warga</option>	";						
						while ($aRow = mysql_fetch_array($rsNamaWargaB1))
						{
							extract($aRow);
							echo "<option value=\"" . $per_ID . "\"";
							if ($sWargaB1 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone."-".$per_ID;
						}					
					echo"</select>	</select>";	
					?>					</td>
					<td width="25"> 
					</td>
					<td width="200"> 
					<?
					//Get Nama Warga for the drop-down
					$sSQL = "select per_ID , per_FirstName, per_WorkPhone 
					from person_per natural join person_custom
					where ( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
					AND c27 is not NULL )
					OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2
					order by per_workphone, per_FirstName ";					
					$rsNamaWargaC1 = RunQuery($sSQL);

					echo "Nama (Kolom C1)<br>
					<select name=\"WargaC1\" >";	
						echo "<option value=\"0\">Pilih Nama Warga</option>	";							
						while ($aRow = mysql_fetch_array($rsNamaWargaC1))
						{
							extract($aRow);
							echo "<option value=\"" . $per_ID . "\"";
							if ($sWargaC1 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone."-".$per_ID;
						}					
					echo"</select>	</select>";	
					?>
					</td>
				</tr>

				<tr>
				<td height="40px" ></td>
				</tr>
				
				<tr style="text-align: left ; vertical-align:top;  background-color: #ccffff" ;>
					<td width="5" > 
					</td>
					<td width="200"> 
										<?
					//Get Nama Warga for the drop-down
					$sSQL = "select per_ID , per_FirstName, per_WorkPhone 
					from person_per natural join person_custom
					where ( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
					AND c27 is not NULL )
					OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2
					order by per_workphone, per_FirstName ";					
					$rsNamaWargaA2 = RunQuery($sSQL);

					echo "Nama (Kolom A2)<br>
					<select name=\"WargaA2\" >";
						echo "<option value=\"0\">Pilih Nama Warga</option>	";							
						while ($aRow = mysql_fetch_array($rsNamaWargaA2))
						{
							extract($aRow);
							echo "<option value=\"" . $per_ID . "\"";
							if ($sWargaA2 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone."-".$per_ID;
						}					
					echo"</select>	</select>";	
					?>
					</td>		
					<td width="25" > 
					</td>
					<td width="200"> 
										<?
					//Get Nama Warga for the drop-down
					$sSQL = "select per_ID , per_FirstName, per_WorkPhone 
					from person_per natural join person_custom
					where ( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
					AND c27 is not NULL )
					OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2
					order by per_workphone, per_FirstName ";					
					$rsNamaWargaB2 = RunQuery($sSQL);

					echo "Nama (Kolom B2)<br>
					<select name=\"WargaB2\" >";
						echo "<option value=\"0\">Pilih Nama Warga</option>	";							
						while ($aRow = mysql_fetch_array($rsNamaWargaB2))
						{
							extract($aRow);
							echo "<option value=\"" . $per_ID . "\"";
							if ($sWargaB2 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone."-".$per_ID;
						}					
					echo"</select>	</select>";	
					?>
					</td>
					<td width="25"> 
					</td>
					<td width="200"> 
										<?
					//Get Nama Warga for the drop-down
					$sSQL = "select per_ID , per_FirstName, per_WorkPhone 
					from person_per natural join person_custom
					where ( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
					AND c27 is not NULL )
					OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2
					order by per_workphone, per_FirstName ";					
					$rsNamaWargaC2 = RunQuery($sSQL);

					echo "Nama (Kolom C2)<br>
					<select name=\"WargaC2\" >";
						echo "<option value=\"0\">Pilih Nama Warga</option>	";							
						while ($aRow = mysql_fetch_array($rsNamaWargaC2))
						{
							extract($aRow);
							echo "<option value=\"" . $per_ID . "\"";
							if ($sWargaC2 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone."-".$per_ID;
						}					
					echo"</select>	</select>";	
					?>
					</td>
				</tr>
				
				<tr>
				<td height="40px" ></td>
				</tr>
				
				<tr style="text-align: left ; vertical-align:top;  background-color: #ccffff" ;>
									<td width="5" > 
					</td>
					<td width="200"> 
										<?
					//Get Nama Warga for the drop-down
					$sSQL = "select per_ID , per_FirstName, per_WorkPhone 
					from person_per natural join person_custom
					where ( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
					AND c27 is not NULL )
					OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2
					order by per_workphone, per_FirstName ";					
					$rsNamaWargaA3 = RunQuery($sSQL);

					echo "Nama (Kolom A3)<br>
					<select name=\"WargaA3\" >";
						echo "<option value=\"0\">Pilih Nama Warga</option>	";							
						while ($aRow = mysql_fetch_array($rsNamaWargaA3))
						{
							extract($aRow);
							echo "<option value=\"" . $per_ID . "\"";
							if ($sWargaA3 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone."-".$per_ID;
						}					
					echo"</select>	</select>";	
					?>
					</td>		
					<td width="25" > 
					</td>
					<td width="200"> 
										<?
					//Get Nama Warga for the drop-down
					$sSQL = "select per_ID , per_FirstName, per_WorkPhone 
					from person_per natural join person_custom
					where ( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
					AND c27 is not NULL )
					OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2
					order by per_workphone, per_FirstName ";					
					$rsNamaWargaB3 = RunQuery($sSQL);

					echo "Nama (Kolom B3)<br>
					<select name=\"WargaB3\" >";	
						echo "<option value=\"0\">Pilih Nama Warga</option>	";							
						while ($aRow = mysql_fetch_array($rsNamaWargaB3))
						{
							extract($aRow);
							echo "<option value=\"" . $per_ID . "\"";
							if ($sWargaB3 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone."-".$per_ID;
						}					
					echo"</select>	</select>";	
					?>
					</td>
					<td width="25"> 
					</td>
					<td width="200"> 
										<?
					//Get Nama Warga for the drop-down
					$sSQL = "select per_ID , per_FirstName, per_WorkPhone 
					from person_per natural join person_custom
					where ( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
					AND c27 is not NULL )
					OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2
					order by per_workphone, per_FirstName ";					
					$rsNamaWargaC3 = RunQuery($sSQL);

					echo "Nama (Kolom C3)<br>
					<select name=\"WargaC3\" >";
						echo "<option value=\"0\">Pilih Nama Warga</option>	";							
						while ($aRow = mysql_fetch_array($rsNamaWargaC3))
						{
							extract($aRow);
							echo "<option value=\"" . $per_ID . "\"";
							if ($sWargaC3 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone."-".$per_ID;
						}					
					echo"</select>	</select>";	
					?>
					</td>
				</tr>
				
				<tr>
				<td height="40px" ></td>
				</tr>	
				
				<tr style="text-align: left ; vertical-align:top;  background-color: #ccffff" ;>
									<td width="5" > 
					</td>
					<td width="200"> 
										<?
					//Get Nama Warga for the drop-down
					$sSQL = "select per_ID , per_FirstName, per_WorkPhone 
					from person_per natural join person_custom
					where ( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
					AND c27 is not NULL )
					OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2
					order by per_workphone, per_FirstName ";					
					$rsNamaWargaA4 = RunQuery($sSQL);

					echo "Nama (Kolom A4)<br>
					<select name=\"WargaA4\" >";
						echo "<option value=\"0\">Pilih Nama Warga</option>	";							
						while ($aRow = mysql_fetch_array($rsNamaWargaA4))
						{
							extract($aRow);
							echo "<option value=\"" . $per_ID . "\"";
							if ($sWargaA4 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone."-".$per_ID;
						}					
					echo"</select>	</select>";	
					?>
					</td>		
					<td width="25" > 
					</td>
					<td width="200"> 
					<?
					//Get Nama Warga for the drop-down
					$sSQL = "select per_ID , per_FirstName, per_WorkPhone 
					from person_per natural join person_custom
					where ( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
					AND c27 is not NULL )
					OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2
					order by per_workphone, per_FirstName ";					
					$rsNamaWargaB4 = RunQuery($sSQL);

					echo "Nama (Kolom B4)<br>
					<select name=\"WargaB4\" >";	
						echo "<option value=\"0\">Pilih Nama Warga</option>	";							
						while ($aRow = mysql_fetch_array($rsNamaWargaB4))
						{
							extract($aRow);
							echo "<option value=\"" . $per_ID . "\"";
							if ($sWargaB4 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone."-".$per_ID;
						}					
					echo"</select>	</select>";	
					?>
					</td>
					<td width="25"> 
					</td>
					<td width="200"> 
					<?
					//Get Nama Warga for the drop-down
					$sSQL = "select per_ID , per_FirstName, per_WorkPhone 
					from person_per natural join person_custom
					where ( (c2 is not NULL AND c2<>'0000-00-00 00:00:00')
					AND c27 is not NULL )
					OR ( (c18 is not NULL AND c18<>'0000-00-00 00:00:00') AND c28 is not NULL) OR c15 = 2
					order by per_workphone, per_FirstName ";					
					$rsNamaWargaC4 = RunQuery($sSQL);

					echo "Nama (Kolom C4)<br>
					<select name=\"WargaC4\" >";	
						echo "<option value=\"0\">Pilih Nama Warga</option>	";							
						while ($aRow = mysql_fetch_array($rsNamaWargaC4))
						{
							extract($aRow);
							echo "<option value=\"" . $per_ID . "\"";
							if ($sWargaC4 == $per_ID) { echo " selected"; }
							echo ">" . $per_FirstName." - ".$per_WorkPhone."-".$per_ID;
						}					
					echo"</select>	</select>";	
					?>
					</td>
				</tr>

			</tbody>
		</table>
</form>

<?php
		$logvar1 = "Edit";
		$logvar2 = "Stiker Editor";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iStikerID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);
//require "Include/Footer.php";
?>
