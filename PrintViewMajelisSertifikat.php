<?php
/*******************************************************************************
 *
 *  filename    : PrintViewMajelisSertifikat.php
 *
 *  2012 Erwin Pratama for GKJ Bekasi Timur
 *  Sistem Informasi GKJ Bekasi Timur is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

//Get the MasaBaktiMajelisID out of the querystring
$iMasaBaktiMajelisID = FilterInput($_GET["MasaBaktiMajelisID"],'int');

// Get this MasaBaktiMajelis 
       $sSQL = "select a.*, b.*, c.* , d.*
	   FROM MasaBaktiMajelis	a
			LEFT JOIN person_per b ON a.per_ID = b.per_ID
			LEFT JOIN volunteeropportunity_vol c ON a.vol_ID = c.vol_ID
			LEFT JOIN NotulaRapat d ON a.TglKeputusan = d.Tanggal
			
		 WHERE MasaBaktiMajelisID = " . $iMasaBaktiMajelisID;

$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

						$time  = strtotime($TglAkhir);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat =  $iMasaBaktiMajelisID."e/MG-PPM/".$sChurchCode."/".dec2roman($month)."/".$year;


// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}


// Set the page title and include HTML header
$sPageTitle = gettext("Sertifikat Majelis No.$MasaBaktiMajelisID - $per_FirstName ");
$iTableSpacerWidth = 10;
//require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "Print Sertifikat Majelis " . $MasaBaktiMajelisID."-".$per_FirstName ;
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iBaptisID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table  border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center style="background-image: url('/datawarga/Images/blangkobaptis.jpg');background-position: center top;background-repeat:no-repeat">
<table  border="0"  width="505" cellspacing=0 cellpadding=0 >
<tr><td valign=top align=center>

<table  border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><td valign=bottom align=center width="100%" style="height:150px" > </tr>
  <tr><!-- Row 2 -->
     <td valign=top align=center >
	 <b><font FACE="Bernard MT Condensed" size="6"><?php echo $sChurchFullName ;?></b><BR>
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 ( Anggota Persekutuan Gereja Gereja di Indonesia )<BR>
	 <?php echo $sChurchAddress.",".$sChurchCity.",".$sChurchState.",KodePos:".$sChurchZip;?></font>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	  <?php echo "Telp:".$sChurchPhone . "- Fax:".$sChurchFax." - Email:".$sChurchEmail;?></font><br><br>
	 <b><u><font FACE="Old English Text MT" size="6">Piagam Penghargaan</font></u></b>
	</td><!-- Col 3 -->
	</tr>
	<tr><td valign=top align=center >
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">

	Nomor: <?php echo $NomorSurat;?></font>
     </td></tr>
</table>

<table border="0"  width="500" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u></u></b></font><br>
  <table border="0"  width="100%">
  <tr><td align=justify colspan="4" style="height:10px"><font FACE="Monotype Corsiva" size="3"><br>
	Majelis dan Segenap Warga Jemaat <?php echo "$sChurchFullName"; ?> Mengucapkan Penghargaan dan Terima Kasih yang Sebesar-besarnya atas Pelayanan :
  </font></u></td><td></td></tr>
  

  <?php 
  echo "<tr><td><td valign=center><font size=\"2\"><font FACE=\"Monotype Corsiva\" size=\"3\"> </font></td>
 <td></td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  </font></b></td></font><td></td></td></tr>";
  
 echo "<tr><td><td valign=center><font size=\"2\"><font FACE=\"Monotype Corsiva\" size=\"3\">  Bapak/Ibu </font></td>
 <td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  $per_FirstName</font></b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\"><font FACE=\"Monotype Corsiva\" size=\"3\">  Sebagai </font></td>
 <td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  $vol_Name $Kategorial</font></b></td></font><td></td></td></tr>"; 
 echo "<tr><td><td valign=center><font size=\"2\"><font FACE=\"Monotype Corsiva\" size=\"3\">  Periode </font></td>
 <td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ".date2Ind($TglPeneguhan,2)." - ".date2Ind($TglAkhir,2)."</font></b></td></font><td></td></td></tr>";
 
 
 ?>
   <tr><td align=justify colspan="4" style="height:10px"><font FACE="Monotype Corsiva" size="3"><br>
Kiranya Tuhan Yesus Kristus Sang Raja Gereja memberkati pelayanan Bapak/Ibu dan keluarga, serta pelayanan selanjutnya didalam jemaat Tuhan. 
  </font></u></td><td></td></tr>

  </table>  
  </td><!-- Col 1 -->
  </tr>
  
  <table  border="0" width="100%">
  <tr><td align=center colspan="2"> <?php echo $sChurchCity; ?>,  <?php echo date2Ind($TglAkhir,2); ?></td><td></td></tr>
   <tr><td align=center  colspan="2"><b>  Majelis <?php echo $sChurchFullName; ?> </b></td><td></td></tr>
<br>
<br>
 
 <tr>
  <td valign=bottom align=center width="50%" style="height:80px" ><u><?php echo jabatanpengurus(61); ?></u></td>
  <td valign=bottom align=center ><u><?php echo jabatanpengurus(65); ?></u></td>
  </tr>  
 <tr>
  <td valign=bottom align=center width="50%">Ketua Majelis</td><td align=center >Sekretaris</td>
  </tr>  
 

  <tr><td valign=bottom align=center colspan="2" style="height:70px">
  <u><?php echo jabatanpengurus(1); ?></u></td><td></td></tr>
  <tr><td align=center colspan="2">Pendeta Yang Melayani</td><td></td></tr>

  <tr><td align=center colspan="2" style="height:10px"><font FACE="Monotype Corsiva" size="1"><br>

  </font></u></td><td></td></tr>
  </table>
</td></tr>
</table>
</td></tr>

</table>
