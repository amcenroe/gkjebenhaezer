<?php
/*******************************************************************************
 *
 *  filename    : PrintViewMajelisBeritaAcara.php
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

// Get the person ID from the querystring
$iMasaBaktiMajelisID = FilterInput($_GET["MasaBaktiMajelisID"],'int');
$iTglPeneguhan = FilterInput($_GET["TglPeneguhan"],'date');

// Get this Baptis Anak Data
// $sSQL = "SELECT * FROM baptisanakgkjbekti WHERE MasaBaktiMajelisID = " . $iMasaBaktiMajelisID;
// Get this MasaBaktiMajelis 
       $sSQL = "select a.*, b.*, c.* , d.*
	   FROM MasaBaktiMajelis	a
			LEFT JOIN person_per b ON a.per_ID = b.per_ID
			LEFT JOIN volunteeropportunity_vol c ON a.vol_ID = c.vol_ID
			LEFT JOIN NotulaRapat d ON a.TglKeputusan = d.Tanggal
			
		 WHERE TglPeneguhan = '".$iTglPeneguhan."'";


	//	 echo $sSQL;
$rsPerson = RunQuery($sSQL);
//extract(mysql_fetch_array($rsPerson));





// Set the page title and include HTML header
$sPageTitle = gettext("Berita Acara Peneguhan Majelis Tanggal.$iTglPeneguhan");
$iTableSpacerWidth = 10;
//require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "Print Berita Acara Peneguhan Tgl" . $iTglPeneguhan;
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iTglPeneguhan . "','" . $logvar1 . "','" . $logvar2 . "')";
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
	  <?php echo "Telp:".$sChurchPhone . "- Fax:".$sChurchFax." - Email:".$sChurchEmail;?></font><br>
	 <b><font FACE="Arial" size="5">Berita Acara Peneguhan Majelis</font> </b>
	</td><!-- Col 3 -->
	</tr>

	<tr><td valign=top align=center >
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
		 <?php $TGL=$iTglPeneguhan; ?>
	Berita Acara Nomor: <?php echo $TGL."/BAPM/".$sChurchCode."/".strftime( "%Y", strtotime($TGL));?></font>
     </td></tr>
	 <tr></tr>
	<tr><td align=center colspan="2"> <br><?php echo date2Ind($iTglPeneguhan,1); ?></td><td></td></tr>
 	<tr><td></td></tr>
	 
</table>
<br>
<table border="0"  width="300" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
 

        <table cellpadding="2" align="center" cellspacing="0" width="520">

        <tr class="TableHeader" align="center" >
				<td><b><u><?php echo gettext("No"); ?></u></b></td>
				<td><b><u><?php echo gettext("Nama"); ?></u></b></td>
                <td><b><u><?php echo gettext("Jabatan"); ?></u></b></td>
				<td><b><u><?php echo gettext("Tanda Tangan"); ?></u></b></td>
	   </tr>
        <?php
        //Loop through the family recordset
		$i = 0;
		
		
		
        while ($aRow = mysql_fetch_array($rsPerson))
        {
				$i++;				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
					<td><font size="2" style="font-family: Arial; color: rgb(0, 0, 0);"><?php  echo $i ; ?></font></td>
					<td><font size="2" style="font-family: Arial; color: rgb(0, 0, 0);"><?php echo $per_FirstName ?></font></td>
					<td><font size="2" style="font-family: Arial; color: rgb(0, 0, 0);"><?php echo $vol_Name."<br>".$Kategorial ?></font></td>
					<td><font size="2" style="font-family: Arial; color: rgb(0, 0, 0);"><?php echo "......................" ?></font></td>
                </tr>
                <?php
        }

        //Close the table
        echo "</table>";
 ?>
   </tr>
  
  <table  border="0" width="520">
  <tr><td align=center colspan="2"><?php echo $sChurchCity ;?>,  <?php echo date2Ind($TglPeneguhan,2);?></td><td></td></tr>
   <tr><td align=center  colspan="2"><b>  Majelis <?php echo $sChurchFullName ;?> </b></td><td></td></tr>
<br>
<br>
 
 <tr>
  <td valign=bottom align=center width="50%" style="height:80px" ><u><?php if ($per_ID == 0){echo $PendetaBaptisNW;} else {echo $PendetaBaptis;} ?></u>
  </td><td valign=bottom align=center ><u>...........................</u></td>
  </tr>  
 <tr>
  <td valign=bottom align=center width="50%">Pendeta Yang Melayani</td><td align=center >Majelis</td>
  </tr>  
 

   <tr><td align=center colspan="2" style="height:10px"><font FACE="Monotype Corsiva" size="1"><br>
  Catatan : 
  </font></u></td><td></td></tr>
  </table>
</td></tr>
</table>
</td></tr>

</table>
