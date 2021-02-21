<?php
/*******************************************************************************
 *
 *  filename    : PrintViewMail.php
 *
 *  2012 Erwin Pratama for GKJ Bekasi Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
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

// Get this mail
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
$sPageTitle = gettext("Cetak Surat Disposisi : Nomor Reg : $MailID , $NomorSurat ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or Print Mail Disposisi";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center>
<table border="0"  width="805" cellspacing=0 cellpadding=0>
<tr><td valign=top align=center>

<table border="0"  width="800" cellspacing=0 cellpadding=0>
  <tr><!-- Row 2 -->
     <td valign=top align=left>
     <img border="0" src="gkj_logo.jpg" width="100" >
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo $sChurchName;?></font></b><BR>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font></b>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font></b>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);"><b style="font-family: Arial; color: rgb(0, 0, 102);">
	 <hr style="width: 100%; height: 2px;">
	 <b><font size="2">Lembar Disposisi - Rekomendasi</font></b><br>
  <hr>
        <?php echo "Nomor Reg: " . $MailID . " " ?>
		<?php echo " -- " ?>
		<?php echo "Urgensi : ";
          switch ($Urgensi)
          {
          case 1:
            echo gettext("Sangat Segera");
            break;
          case 2:
            echo gettext("Segera");
            break;
		  case 3:
            echo gettext("Biasa");
            break;
          } 
		?>
		<?php echo " -- " ?>
      <?php echo " Kategori : ";
          switch ($Ket3)
          {
          case 11:
            echo gettext("Informasi Umum");
            break;
          case 12:
            echo gettext("Surat Edaran");
            break;
		  case 13:
            echo gettext("Undangan");
            break;
		  case 14:
            echo gettext("Laporan Kegiatan");
            break;
			
		  case 21:
            echo gettext("Permohonan Umum");
            break;
		  case 22:
            echo gettext("Permohonan Bantuan");
            break;
		  case 23:
            echo gettext("Permohonan Pelayanan Firman");
            break;
		  case 24:
            echo gettext("Permohonan Peminjaman Asset Gereja");
            break;
		  case 25:
            echo gettext("Permohonan Pelayanan Gerejawi (Baptis/Sidi/Nikah/dll)");
            break;

		  case 31:
            echo gettext("Surat Pindah/Atestasi");
            break;
		  case 32:
            echo gettext("Surat Pemberitahuan Sakramen");
            break;
		  case 33:
            echo gettext("Surat Penitipan Rohani");
            break;
			
			
          }
        ?>
     </td><!-- Col 3 -->
  </tr>
</table>

<table border="0"  width="800" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u>Detail Surat</u></b></font><br>
     <table border="0"  width="100%">

    <?php
   echo "<tr><td valign=top>Nomor Surat:</td><td><font size=\"2\">  " . $NomorSurat . "</font></td></tr>";	
   echo "<tr><td valign=top>Tanggal Surat:</td><td><font size=\"2\">  " . $Tanggal . " - Diterima Tanggal : " . $Ket1 . "</font></td></tr>";
   echo "<tr><td valign=top>Pengirim:</td><td><font size=\"2\"> " . $Dari . " - Institusi : " . $Institusi . "</font></td></tr>";
   echo "<tr><td valign=top>Hal:</td><td><font size=\"2\"> " . $Hal . "</font></td></tr>";
   echo "<tr><td valign=top>Tujuan:</td><td><font size=\"2\"> " . $Kepada . "</font></td></tr>";
   echo "<tr><td valign=top>Deskrisi :</td><td><font size=\"2\"> " . $Ket2 . "</font></td></tr>";   
 ?>
  </table>
  </td><!-- Col 1 -->
  </tr>

</table>
<hr>

<table HEIGHT="275" border="1" width=800 cellspacing="0" cellpadding="0">
<tr>
 <td width="50%" valign="top" align="left">
  <table cellspacing="1" cellpadding="4">

  </table>
 </td>
</tr>
</table>
<br>


</td></tr>
</table>
</td></tr>
</table>
