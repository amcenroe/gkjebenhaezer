<?php
/*******************************************************************************
 *
 *  filename    : PrintViewLiturgi.php
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
$iLiturgiID = FilterInput($_GET["LiturgiID"],'int');

// Get this Liturgi Data

$sSQL = "SELECT * FROM LiturgiGKJBekti  WHERE LiturgiID = " . $iLiturgiID;

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
$sPageTitle = gettext("Ringkasan Liturgi Tanggal $Tanggal ,Bahasa $Bahasa ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or Print Ringkasan Liturgi";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iLiturgiID . "','" . $logvar1 . "','" . $logvar2 . "')";
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
     <img border="0" src="gkj_logo.jpg" width="100" >
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo "$sChurchFullName" ;?></font></b><BR>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font></b>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	  <?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font></b><br>
	 <b style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="3"><?php echo "Ringkasan Liturgi" ;?></font></b><BR>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <b style="font-family: Arial; color: rgb(0, 0, 102);">
	  <hr style="width: 100%; height: 2px;">
	 <b><font size="2"><?php echo "Tanggal: " . $Tanggal . " ,Bahasa: " . $Bahasa  ;?></font></b><br>
  <hr>
        </td><!-- Col 3 -->
  </tr>
</table>

<table border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u></u></b></font><br>
   <table border="0"  width="100%">
  <?php 

  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"> Ringkasan Liturgi :</font></td>";
  
  echo "<tr><td><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"30\" height=\"1\" ></td>
  <td valign=top ><font size=\"2\"> Tanggal </td><td>:</td><td><b> ";?>
  <?php echo $Tanggal ?><?php echo " </b></td></font><td></td></td>";	
   echo "<tr><td><td valign=top><font size=\"2\">  Bahasa</td><td>:</td><td><b> ";?>
  <?php echo $Bahasa ?><?php echo " </b></td></font><td></td></td>";	
  echo "<tr><td><td valign=top><font size=\"2\">  Warna </td><td>:</td><td><b> " . $Warna . " </b></td></font><td></td></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Keterangan </td><td>:</td><td><b> " . $Keterangan . " </b></td></font><td></td></td>";  
  echo "<tr><td><td valign=top><font size=\"2\">  Tema </td><td>:</td><td><b> " . $Tema . " </b></td></font><td></td></td>";
  
  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"> Bacaan :</font></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Bacaan 1 </td><td>:</td><td><b> " . $Bacaan1 . " </b></td></font><td></td></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Bacaan Antara </td><td>:</td><td><b> " . $BacaanAntara . " </b></td></font><td></td></td>"; 
  echo "<tr><td><td valign=top><font size=\"2\">  Bacaan 2 </td><td>:</td><td><b> " . $Bacaan2 . " </b></td></font><td></td></td>";   
  echo "<tr><td><td valign=top><font size=\"2\">  Bacaan Injil </td><td>:</td><td><b> " . $BacaanInjil . " </b></td></font><td></td></td>";
  
  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"> Ayat Penuntun :</font></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Hukum Kasih </td><td>:</td><td><b> " . $AyatPenuntunHK . " </b></td></font><td></td></td>";   
  echo "<tr><td><td valign=top><font size=\"2\">  Berita Anugerah </td><td>:</td><td><b> " . $AyatPenuntunBA . " </b></td></font><td></td></td>";   
  echo "<tr><td><td valign=top><font size=\"2\">  Litani Mazmur </td><td>:</td><td><b> " . $AyatPenuntunLM . " </b></td></font><td></td></td>";   
  echo "<tr><td><td valign=top><font size=\"2\">  Persembahan </td><td>:</td><td><b> " . $AyatPenuntunP . " </b></td></font><td></td></td>";   
  echo "<tr><td><td valign=top><font size=\"2\">  Nats Pengutusan </td><td>:</td><td><b> " . $AyatPenuntunNP . " </b></td></font><td></td></td>";   
  
  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"> Nyanyian :</font></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Nyanyian 1 </td><td>:</td><td><b> " . $Nyanyian1 . " </b></td></font><td></td></td>";  
    echo "<td><td valign=top><font size=\"2\">  Nyanyian 5 </td><td>:</td><td><b> " . $Nyanyian5 . " </b></td></font><td></td></td>";  
  echo "<tr><td><td valign=top><font size=\"2\">  Nyanyian 2 </td><td>:</td><td><b> " . $Nyanyian2 . " </b></td></font><td></td></td>"; 
    echo "<td><td valign=top><font size=\"2\">  Nyanyian 6 </td><td>:</td><td><b> " . $Nyanyian6 . " </b></td></font><td></td></td>";    
  echo "<tr><td><td valign=top><font size=\"2\">  Nyanyian 3 </td><td>:</td><td><b> " . $Nyanyian3 . " </b></td></font><td></td></td>";  
    echo "<td><td valign=top><font size=\"2\">  Nyanyian 7 </td><td>:</td><td><b> " . $Nyanyian7 . " </b></td></font><td></td></td>";  
  echo "<tr><td><td valign=top><font size=\"2\">  Nyanyian 4 </td><td>:</td><td><b> " . $Nyanyian4 . " </b></td></font><td></td></td>";  
    echo "<td><td valign=top><font size=\"2\">  Nyanyian 8 </td><td>:</td><td><b> " . $Nyanyian8 . " </b></td></font><td></td></td>";  
 
   echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"> Pelayan Firman :</font></td>";
   // Pelayan Firman
	$sSQL = "SELECT * FROM JadwalPelayanFirman  a
left join DaftarPendeta b ON a.PelayanFirman = b.PendetaID 
left join LokasiTI c ON a.KodeTI = c.KodeTI
WHERE TanggalPF = '" . $Tanggal . "' AND Bahasa = '" . $Bahasa . "'";
$rsPelayanFirman = RunQuery($sSQL);
//extract(mysql_fetch_array($rsPelayanFirman));
   while ($aRow = mysql_fetch_array($rsPelayanFirman))
						{
							extract($aRow);
			echo "<tr><td><td valign=top><font size=\"2\">  " . $NamaTI . " " . $WaktuPF . " </td><td>:</td><td><b>";
 if ($PelayanFirman<>"0"){ echo $NamaPendeta ;}else{ echo $PFnonInstitusi ;}
			echo "</b><br><i> " . $NamaGereja . "</i> </td></font><td></td></td>";  		
			
						}
					
   
   
  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"> Majelis Yang Bertugas :</font></td>";

 
 ?>
  </table>  
  </td><!-- Col 1 -->
  </tr>
  
   
<br>


</td></tr>
</table>
</td></tr>
</table>
