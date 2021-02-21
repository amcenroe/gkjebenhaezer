<?php
/*******************************************************************************
 *
 *  filename    : PrintViewTerimakasih.php
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
$iPelayanFirmanID = FilterInput($_GET["PelayanFirmanID"],'int');
$iKopSurat = FilterInput($_GET["KopSurat"],'int');
$iMode = FilterInput($_GET["Mode"],'int');

$sSQL = "SELECT a . * , b . * , c . * , d . *,e.*, a.Bahasa as Bahasa
FROM JadwalPelayanFirman a
LEFT JOIN DaftarPendeta b ON a.PelayanFirman = b.PendetaID
LEFT JOIN LiturgiGKJBekti c ON a.TanggalPF = c.Tanggal AND a.Bahasa = c.Bahasa
LEFT JOIN LokasiTI d ON a.KodeTI = d.KodeTI
LEFT JOIN DaftarGerejaGKJ e ON b.GerejaID = e.GerejaID 
		 WHERE PelayanFirmanID = " . $iPelayanFirmanID;

	//	 echo $sSQL; echo "<br>";
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));


//Check Kalo Ada Jam yg kedua

$sSQL2 = "SELECT a.WaktuPF as WaktuKedua
FROM JadwalPelayanFirman a
LEFT JOIN DaftarPendeta b ON a.PelayanFirman = b.PendetaID
LEFT JOIN LiturgiGKJBekti c ON a.TanggalPF = c.Tanggal AND a.Bahasa = c.Bahasa
LEFT JOIN LokasiTI d ON a.KodeTI = d.KodeTI
LEFT JOIN DaftarGerejaGKJ e ON b.GerejaID = e.GerejaID 
		WHERE
TanggalPF = '" . $TanggalPF . "' AND a.KodeTI = '" . $KodeTI . "'  
AND PelayanFirman = '" . $PelayanFirman . "' 
AND a.WaktuPF <> '" . $WaktuPF . "' ";

// echo $sSQL2; 

$rsPerson2 = RunQuery($sSQL2);

$num_rows = mysql_num_rows($rsPerson2);
if ($num_rows > 0 ) {
extract(mysql_fetch_array($rsPerson2));
}

//echo $sSQL2;









// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}

// Set the page title and include HTML header
$sPageTitle = gettext("Surat Permohonan Pelayan Firman tgl $TanggalPF untuk $NamaPendeta -$NamaGereja ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "Cetak Surat Ucapan Terima Kasih PF ".$TanggalPF." - " .$NamaPendeta."".$PFnonInstitusi. " - ".$NamaGereja;
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPelayanFirmanID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center>
<table border="0"  width="605" cellspacing=0 cellpadding=0>
<tr><td valign=top align=center>



<table border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u><? if (($iMode==1)||($iMode==2)){ echo "<hr style=\"border: 3px outset #595955;\">";}else{echo "<br>";}; ?></u></b></font>
    <br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>

  <table border="0"  width="100%">
  <?php 
  
  if ($PelayanFirman<>"0"){ 
 echo "<tr><td align=center valign=top colspan=\"4\"><font FACE=\"Vivaldi\" size=\"6\">  
  <b>" . $Salutation . " " .$NamaPendeta . " </b></font></td>";
	}else{
 echo "<tr><td align=center valign=top colspan=\"4\"><font FACE=\"Vivaldi\" size=\"6\">  
  <b>" . $Salutation . " " .$PFnonInstitusi . " </b>
  </font></td>"; 
  }
  echo "<tr><td align=center valign=top colspan=\"4\"><font size=\"2\"> 
  <b>Yang telah melayani Pemberitaaan Firman ".$Hal." pada:</b>
  </font></td>"; 
  
  echo "<tr><td><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"30\" height=\"1\" ></td>
  <td valign=top ><font size=\"2\"> Hari, tgl.  </td><td>:</td><td><font size=\"2\"><b> ";?>
  <?php echo date2Ind($TanggalPF,1);?><?php echo " </b></td></font><td></td></td>";	
  echo "<tr><td><td valign=top><font size=\"2\">  Waktu </td><td>:</td><td><font size=\"2\"><b> " . $WaktuPF ;
  
if ( $WaktuKedua != NULL ){
    echo " dan " . $WaktuKedua ;
}  

  echo " </b></td></font><td></td></td>";
  echo "<tr><td></td><td valign=top><font size=\"2\">  Tempat </td><td>:</td><td><font size=\"2\"><b>".$sChurchName." - TI. " . $NamaTI . " </b></td></font><td></td></td>";
  echo "<tr><td></td><td valign=top><font size=\"2\">  Alamat </td><td>:</td><td><font size=\"2\"><b> " . $AlamatTI1 . "," .$AlamatTI2 . " </b></td></font><td></td></td>";	

  echo "<tr><td align=center valign=top colspan=\"4\"><font size=\"2\"> Demikian disampaikan, sekali lagi kami ucapkan terima kasih.<br>
Tuhan memberkati
</font></td></tr>";


 
 ?>
   <table  border="0" width="100%">
   <tr><td align=center  colspan="2"><font size="2"><b> Ttd. </b></td><td></td></tr>
   <tr><td align=center  colspan="2"><font size="2"><b>  MAJELIS <?php echo strtoupper($sChurchFullName);?> </b></td><td></td></tr>


  </table>
 
  </table>  
  </td><!-- Col 1 -->
  </tr>
  
<br>


</td></tr>
</table>
</td></tr>
</table>
