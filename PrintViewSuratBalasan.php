<?php
/*******************************************************************************
 *
 *  filename    : PrintViewSuratBalasan.php
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
$iMailID = FilterInput($_GET["MailID"],'int');
$iKopSurat = FilterInput($_GET["KopSurat"],'int');
$iMode = FilterInput($_GET["Mode"],'int');

// Get this mail's data
$sSQL = "SELECT * FROM SuratMasuk WHERE MailID = " . $iMailID;

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

// Set the page title and include HTML header
$sPageTitle = gettext("Surat Balasan untuk $Dari nomor surat $NomorSurat ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or Surat Balasan";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center>
<table border="0"  width="605" cellspacing=0 cellpadding=0>
<tr><td valign=top align=center>

<table border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><!-- Row 2 -->
     <td valign=top align=left>
<?php  if (($iMode==1)||($iMode==2)){	 
     echo "<img border=\"0\" src=\"gkj_logo.jpg\" width=\"110\" >";
	 }else{
	 echo "<img border=\"0\" src=\"\Images\Spacer.gif\" width=\"1\" height=\"90\" >"; 
	}	
?>	
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b style="font-family: Times; color: rgb(0, 0, 102);"><font size="4"><?php  if (($iMode==1)||($iMode==2)){echo "GEREJA KRISTEN JAWA";}else{echo"";} ;?></font></b><BR>
	 <b style="font-family: Times; color: rgb(0, 0, 102);"><font size="4"><?php if (($iMode==1)||($iMode==2)){echo strtoupper($sChurchGKJName) ;}else{echo"";}?></font></b><BR>
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php if (($iMode==1)||($iMode==2)){echo "(Anggota Persekutuan Gereja-Gereja di Indonesia)";}else{echo"";}?></font></b><br>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php if (($iMode==1)||($iMode==2)){echo $sChurchAddress."
	 <BR>".$sChurchCity.", ". $sChurchState.", Kode POS ". $sChurchZip."
	 <BR>Telp : ".$sChurchPhone." , Fax : ".$sChurchFax."
	 <BR>Email: ".$sChurchEmail." , Situs Web : ".$sChurchWebsite;}else{echo"";}?></font></b>
        </td><!-- Col 3 -->

  </tr>

</table>

<table border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u><? if (($iMode==1)||($iMode==2)){ echo "<hr style=\"border: 3px outset #595955;\">";}else{echo "<br>";}; ?></u></b></font>
     <table border="0"  width="100%">

    <?php

   echo "<tr><td valign=top><font size=\"3\">Nomor Surat:</td><td><font size=\"3\">  " . $MailID . "e/SB/MG/".$sChurchCode."/"; echo dec2roman(date (m)) ;echo "/"; echo date('Y');"</font></td>";
  
  echo "<td><font size=\"3\">".$sChurchCity.", </td><td><font size=\"3\">"; echo tanggalsekarang() ; date ('Y' ); echo " </td></tr>";
  
   echo "<tr><td valign=top><font size=\"3\">Hal:</td><td><font size=\"3\"> Surat Balasan hal <br>".$Hal."</font></td></tr>";
   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
   echo "<tr><td valign=top colspan=2 ><font size=\"3\">Kepada YTH</td><td><font size=\"3\"></font></td></tr>";	

   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><b> " . $Dari . " </b></td><td><font size=\"3\"></font></td></tr>";
   if ($Institusi==""){
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF>.</font> Ditempat</font></td><td><font size=\"3\"></font></td></tr>";	
   }else{
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF>.</font> ".$Institusi."</font></td><td><font size=\"3\"></font></td></tr>";	
   }
   
    if ($NomorSurat==""){  
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF></font></font></td><td><font size=\"3\"></font></td></tr>";	   
    }else{
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF>.</font>Respon surat no: ".$NomorSurat."</font></td><td><font size=\"3\"></font></td></tr>";	
   } 


   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
	echo "<br>";
   ?>
  </table>
  <br>

  <table border="0"  width="100%">
  <?php 
  echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"><i>Salam Sejahtera dalam kasih Tuhan Yesus Kristus,</i></font></td>"; 
   echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"></font></td>"; 

 echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"80\" height=\"1\" >  
  ". $IsiSuratBalasan . " </font></td>"; 
  echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"></font></td>"; 
 echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"80\" height=\"1\" >  
  Demikian pemberitahuan ini kami sampaikan, atas perhatiannya kami ucapkan terima kasih. Tuhan memberkati. </font></td>"; 

 ?>
   <table  border="0" width="100%">
  <tr><td align=center colspan="2"><font size="3"> <?php echo $sChurchCity;?>,  <?php echo tanggalsekarang();?></td><td></td></tr>
     <tr><td align=center  colspan="2"><font size="3">  Teriring Salam dan Doa Kami, </td><td></td></tr>
   <tr><td align=center  colspan="2"><font size="3"><b>  MAJELIS <?php echo strtoupper($sChurchFullName);?> </b></td><td></td></tr>

<br>
<br>
 <?php  if (($iMode==2)||($iMode==4)){	
	echo "<tr>";
    echo "<td valign=bottom align=center ><img border=\"0\"  height=\"80\"  src=\"ttd_ketua.jpg\"></td><td valign=bottom align=center ><img border=\"0\"  height=\"80\"  src=\"ttd_sekre1.jpg\"></td>";
	echo "</tr>";
	}else{
	 echo "<tr>";
	 echo "<td></td><td></td>"; 
	 echo "</tr>";
	}	
?>	
 <tr>
  <td valign=bottom align=center width="50%" 
  <?php  if (($iMode==2)||($iMode==4)){	 
  echo "style=\"height:1px\""; }else{ 
  echo "style=\"height:80px\""; }
  ?>>
  <font size="3"><u><?php echo jabatanpengurus(61); ?></u></td><td valign=bottom align=center ><font size="3"><u><?php echo jabatanpengurus(65); ?></u></td>
  </tr>  
 <tr>
  <td valign=bottom align=center width="50%"><font size="3">Ketua Majelis</td><td align=center ><font size="3">Sekretaris</td>
  </tr>  
 

  <tr><td valign=bottom align=center colspan="2" style="height:50px"><font size="3">
  <u><?php echo jabatanpengurus(1); ?></u></td><td></td></tr>
  <tr><td align=center colspan="2"><font size="3">Pendeta Jemaat</td><td></td></tr>


  </table>
 
  </table>  
  </td><!-- Col 1 -->
  </tr>
  
<br>


</td></tr>
</table>
</td></tr>
</table>
