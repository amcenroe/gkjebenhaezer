<?php
/*******************************************************************************
 *
 *  filename    : PrintViewSuratKeluar.php
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
$sSQL = "SELECT a.* ,a.DateEntered as TglDibuat , IF(Dari=0,'SEKR',replace( `vol_Name` , 'Ketua', '' )) AS KodePengirim,
		IF(Dari=100,'PAN','') AS KodePanitia FROM SuratKeluar a
		LEFT JOIN volunteeropportunity_vol b ON a.Dari = b.vol_ID WHERE MailID = " . $iMailID;

$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));
$TglDibuat = substr($TglDibuat, 0, -9);
						//$time  = strtotime($Tanggal);
            $time  = strtotime($TglDibuat);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat =  $MailID."e/MG-".$KodePengirim."".$KodePanitia."/".$sChurchCode."/".dec2roman($month)."/".$year;

// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}

// Set the page title and include HTML header
$sPageTitle = gettext("Surat Keluar untuk $Kepada - $Institusi nomor surat $NomorSurat ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or Surat Keluar";
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
<?php  if (($iMode==1)||($iMode==2)||($iMode==21)||($iMode==22)){	 
     echo "<img border=\"0\" src=\"gkj_logo.jpg\" width=\"110\" >";
	 }else{
	 echo "<img border=\"0\" src=\"Images/Spacer.gif\" width=\"1\" height=\"90\" >"; 
	}	
?>	
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b style="font-family: Times; color: rgb(0, 0, 102);"><font size="4"><?php  if (($iMode==1)||($iMode==2)||($iMode==21)||($iMode==22)){echo "GEREJA KRISTEN JAWA";}else{echo"";} ;?></font></b><BR>
	 <b style="font-family: Times; color: rgb(0, 0, 102);"><font size="4"><?php if (($iMode==1)||($iMode==2)||($iMode==21)||($iMode==22)){echo strtoupper($sChurchGKJName) ;}else{echo"";}?></font></b><BR>
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php if (($iMode==1)||($iMode==2)||($iMode==21)||($iMode==22)){echo "(Anggota Persekutuan Gereja-Gereja di Indonesia)";}else{echo"";}?></font></b><br>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php if (($iMode==1)||($iMode==2)||($iMode==21)||($iMode==22)){echo $sChurchAddress."
	 <BR>".$sChurchCity.", ". $sChurchState.", Kode POS ". $sChurchZip."
	 <BR>Telp : ".$sChurchPhone." , Fax : ".$sChurchFax."
	 <BR>Email: ".$sChurchEmail." , Situs Web : ".$sChurchWebsite;}else{echo"";}?></font></b>
        </td><!-- Col 3 -->

  </tr>

</table>

<table border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u><? if (($iMode==1)||($iMode==2)||($iMode==21)||($iMode==22)){ echo "<hr style=\"border: 3px outset #595955;\">";}else{echo "<br>";}; ?></u></b></font>
     <table border="0"  width="100%">

    <?php
	
	if ($Ket3 == 16 || $Ket3 == 17) { 
	echo "<tr align=\"center\"><td valign=top><font FACE=\"Arial\" size=\"5\">Surat Keterangan</font></td></tr>"; 
	echo "<tr align=\"center\"><td valign=top><font size=\"3\"> " . $NomorSurat."</font></td>";
	} else {

   echo "<tr><td valign=top><font size=\"3\">Nomor: </td><td><font size=\"3\">" . $NomorSurat."</font></td>";
   echo "<td><font size=\"3\">".$sChurchCity.", </td><td><font size=\"3\">"; 
 //  echo tanggalsekarang() ; date ('Y' ); 
     echo date2Ind($TglDibuat,2) ;
   echo " </td></tr>";
   echo "<tr><td valign=top ><font size=\"3\">Hal&nbsp&nbsp&nbsp&nbsp; : &nbsp</td><td colspan=\"3\"><font size=\"3\"> ".$Hal."</font></td></tr>";
  if ($Lampiran>0){ 
    echo "<tr><td valign=top><font size=\"3\">Lampiran</td><td><font size=\"3\">: ".$Lampiran."</font></td></tr>";  
   }
   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
   echo "<tr><td valign=top colspan=2 ><font size=\"3\">Kepada YTH</td><td><font size=\"3\"></font></td></tr>";	

   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><b> " . $Kepada . " </b></td><td><font size=\"3\"></font></td></tr>";
   if ($Institusi==""){
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF></font> Ditempat</font></td><td><font size=\"3\"></font></td></tr>";	
   }else{
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF></font> ".$Institusi."</font></td><td><font size=\"3\"></font></td></tr>";	
   }
   if ($Alamat1<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF></font> ".$Alamat1."</font></td><td><font size=\"3\"></font></td></tr>";	
   } 
    if ($Alamat2<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF></font> ".$Alamat2."</font></td><td><font size=\"3\"></font></td></tr>";	
   } 
   if ($Email<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF></font> ".$Email."</font></td><td><font size=\"3\"></font></td></tr>";	
   }    
    if ($Telp==""){  
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF></font></font></td><td><font size=\"3\"></font></td></tr>";	   
    }else{
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF></font>Telp:".$Telp." /Fax:".$Fax."</font></td><td><font size=\"3\"></font></td></tr>";	
   } 

}
 //  echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
//	echo "<br>";
   ?>
  </table>


  <table border="0"  width="100%">
  <?php 
	if ($Ket3 <> 16) {  
  if ($Via=="Gerejawi"){ 
 echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"><i>Salam Sejahtera dalam kasih Tuhan Yesus Kristus,</i></font></td></tr>"; 
} else {
 echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"><i>Dengan Hormat,</i></font></td><tr>"; 
} 
}
// echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"></font></td>"; 

 echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"><img border=\"0\" src=\"Images/Spacer.gif\" width=\"80\" height=\"1\" >  
  ". $IsiSuratBalasan . " </font></td></tr>"; 
 

//echo "</table>";
 ?>
  

     <tr><td align=center  colspan="2"><font size="3">  Teriring Salam dan Doa Kami, </td><td></td></tr>
<?php
if ($KodePanitia=="PAN") {
 echo "<tr><td align=center  colspan=\"2\"><font size=\"3\"><b>".strtoupper($Panitia)." </b></td><td></td></tr>";
 echo "<tr><td valign=bottom align=center width=\"50%\" style=\"height:60px\" >";
 echo "<font size=\"3\"><u>".$KetuaPanitia."</u></td><td valign=bottom align=center ><font size=\"3\"><u>".$SekretarisPanitia."</u></td> </tr>";  
 echo "<tr> <td valign=bottom align=center width=\"50%\"><font size=\"3\">Ketua Panitia</td><td align=center ><font size=\"3\">Sekretaris Panitia</td></tr>" ; 	 
 echo "<tr><td align=center  colspan=\"2\"><font size=\"3\">  Mengetahui, </td><td></td></tr>";	 
 }
 ?>
 
 <tr><td align=center  colspan="2"><font size="3"><b>  MAJELIS <?php echo strtoupper($sChurchFullName);?> </b></td><td></td></tr>

<br>
<br>
 <?php  if (($iMode==2)||($iMode==4)){	
	echo "<tr>";
    echo "<td valign=bottom align=center ><img border=\"0\"  height=\"80\"  src=\"ttd_ketua.jpg\"></td>";
	echo "<td valign=bottom align=center ><img border=\"0\"  height=\"80\"  src=\"ttd_sekre1.jpg\"></td>";
	echo "</tr>";
	}else if (($iMode==21)||($iMode==41)){	
	echo "<tr>";
    echo "<td valign=bottom align=center ><img border=\"0\"  height=\"80\"  src=\"ttd_ketua.jpg\"></td><td ></td>";
	echo "</tr>";
	}else if (($iMode==22)||($iMode==42)){	
	echo "<tr>";
    echo "<td valign=bottom align=center ></td>";
	echo "<td valign=bottom align=center ><img border=\"0\"  height=\"80\"  src=\"ttd_sekre1.jpg\"></td>";
	echo "</tr>";
	}else{
	 echo "<tr>";
	 echo "<td></td><td></td>"; 
	 echo "</tr>";
	}	
?>	
 <tr>
  <td valign=bottom align=center width="50%" 
  <?php  if (($iMode==2)||($iMode==4)||($iMode==21)||($iMode==41)||($iMode==22)||($iMode==42)){	 
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
