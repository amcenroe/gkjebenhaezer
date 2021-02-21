<?php
/*******************************************************************************
 *
 *  filename    : PrintViewAset.php
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
$iAssetID = FilterInput($_GET["AssetID"],'int');

// Get this ASET Data

       $sSQL = "SELECT * FROM asetgkjbekti a
		    LEFT JOIN LokasiTI b ON a.Location=b.KodeTI 
			LEFT JOIN asetklasifikasi c ON a.AssetClass=c.ClassID 
			LEFT JOIN asetruangan d ON a.StorageCode=d.StorageCode
			LEFT JOIN asetstatus e ON a.Status=e.StatusCode 
			 
			
		WHERE AssetID = " . $iAssetID;	

$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));



// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}



	  	list($year , $month, $day ) = preg_split('[/.-]', $Tahun);
		//echo "Month: $month; Day: $day; Year: $year<br />\n"

		
// Set the page title and include HTML header
$sPageTitle = gettext("Aset-NomorReg:$AssetClass-$AssetID-$year-$Location ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or Print Aset";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iAssetID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center>
<table border="0"  width="605" cellspacing=0 cellpadding=0>
<tr><td valign=top align=center>

<table border="0"  width="650" cellspacing=0 cellpadding=0>
  <tr><!-- Row 2 -->
     <td valign=top align=left>
     <img border="0" src="gkj_logo.jpg" width="80" >
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo "$sChurchFullName" ;?></font></b><BR>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font></b>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	  <?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font></b><br>
	 <b style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="3"><?php echo "" ;?></font></b>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <b style="font-family: Arial; color: rgb(0, 0, 102);">
	  <hr style="width: 100%; height: 2px;">
  
	 <b><font size="2">Lembar Aset Nomor Reg: <? echo $AssetClass . " / " . $AssetID . " / " . $year . " / " . $Location ?> </font></b><br>
  <hr>
        </td><!-- Col 3 -->
  </tr>
</table>

<table border="0"  width="650" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u></u></b></font>
     <table border="0"  width="100%">

    <?php

   echo "<tr><td valign=top><u>Foto Aset:</u><br>"; 
   
     // Display photo or upload from file
    $photoFile = "Images/Aset/thumbnails/Aset" . $iAssetID . ".jpg";
        if (file_exists($photoFile))
        {
            echo '<a target="_blank" href="Images/Aset/Aset' . $iAssetID . '.jpg">';
            echo '<img border="0" src="'.$photoFile.'"  Images/NoMail.gif" width="128" height="126"   ></a>';
            if ($bOkToEdit) {
                echo '';
                }
        } else {
            // Some old / M$ browsers can't handle PNG's correctly.
            if ($bDefectiveBrowser)
                echo '<img border="0" src="Images/NoMail.gif" width="64" height="63"  ><br><br><br>';
            else
                echo '<img border="0" src="Images/NoMail.png" width="64" height="63"  ><br><br><br>';

            if ($bOkToEdit) {
                if (isset($PhotoError))
                    echo '<span style="color: red;">' . $PhotoError . '</span><br>';

                echo '';
            }
        }
 
   
   
   
   echo "</td><td><u> Detail Aset : </u>";
   

   ?>
      <table cellspacing="0" cellpadding="0" border="0" >

      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Nomor Registrasi"); ?></td>
      <td class="TinyTextColumn"><?php echo $AssetID; ?></td>
      </tr>
	  <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Klasifikasi"); ?></td>
      <td class="TinyTextColumn" colspan="3" ><?php echo $AssetClass . " - <b>Kategori: </b>" . $majorclass . " - " . $minorclass; ?></td>
      </tr>

      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tanggal Pengadaan"); ?></td>
	  <?php
	  	//list($year , $month, $day ) = preg_split('[/.-]', $Tahun);
		//echo "Month: $month; Day: $day; Year: $year<br />\n"
		?>
      <td class="TinyTextColumn"><?php echo date2Ind($Tahun,2);//echo $day . "/" . $month . "/" . $year; ?></td>
      </tr>	  
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Merk"); ?></td>
      <td class="TinyTextColumn"><?php echo $Merk; ?></td>

      <td class="TinyLabelColumn"><?php echo gettext("Type / Part Number"); ?></td>
      <td class="TinyTextColumn"><?php echo $Type; ?></td>
      </tr>	  
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Detail Spesifikasi"); ?></td>
      <td class="TinyTextColumn"><?php echo $Spesification; ?></td>
      </tr>	
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Jumlah"); ?></td>
      <td class="TinyTextColumn"><?php echo $Quantity ; ?></td>
      <td class="TinyTextColumn"><?php echo $UnitOfMasure; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Nilai"); ?></td>
      <td class="TinyTextColumn"><?php echo currency('Rp. ',$Value,'.',',00'); ?></td>
      </tr>	  
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Status"); ?></td>
      <td class="TinyTextColumn"><?php echo $StatusName; ?></td>
      </tr>
       <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tempat Ibadah"); ?></td>
      <td class="TinyTextColumn"><?php echo $NamaTI; ?></td>

      <td class="TinyLabelColumn"><?php echo gettext("Tempat/Lokasi"); ?></td>
      <td class="TinyTextColumn"><?php echo $StorageDesc; ?></td>
      </tr>
       <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Kode Rak"); ?></td>
      <td class="TinyTextColumn"><?php echo $Rack; ?></td>

      <td class="TinyLabelColumn"><?php echo gettext("Kode Bin"); ?></td>
      <td class="TinyTextColumn"><?php echo $Bin ; ?></td>
      </tr>

	  <tr>
      <td class="TinyLabelColumn" > <?php echo gettext("Keterangan "); ?></td>
      <td class="TinyTextColumn" colspan="3" ><?php echo $Description; ?></td>
      </tr>

        </td>

	  
      </table>
	  
	  
  <?php  
 
   echo " </td>";
 echo "</tr>";
   ?>
  </table>


  </td><!-- Col 1 -->
  </tr>
 
</td></tr>
 
</table><hr>
</td></tr>
</table>
