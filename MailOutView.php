<?php
/*******************************************************************************
 *
 *  filename    : MailOutView.php
 *  last change : 2003-04-14
 *  description : Displays all the information about a single mail
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
 *
 *  Sistem Informasi GKJ Bekasi is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

ob_start();
?>

<!-- TinyMCE -->
<script type="text/javascript" src="Include/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        readonly : true
});
</script>

<!-- /TinyMCE -->



<?php


// Get the Mail ID from the querystring
$iMailID = FilterInput($_GET["MailID"],'int');

$iRemoveVO = FilterInput($_GET["RemoveVO"],'int');

$dSQL= "SELECT MailID FROM SuratKeluar order by MailID";
$dResults = RunQuery($dSQL);

$last_id = 0;
$next_id = 0;
$capture_next = 0;
while($myrow = mysql_fetch_row($dResults))
{
  $pid = $myrow[0];
  if ($capture_next == 1)
  {
      $next_id = $pid;
    break;
  }
  if ($pid == $iMailID)
  {
    $previous_id = $last_id;
    $capture_next = 1;
  }
  $last_id = $pid;
}

if (($previous_id > 0)) {
    $previous_link_text = "<a class=\"SmallText\" href=\"MailView.php?MailID=$previous_id\"><img border=0 src=\"Images/Icons/ico_prev.png\"  width=\"30\" height=\"30\"  > " . gettext("Sebelumnya") . "</a>";
}

if (($next_id > 0)) {
    $next_link_text = "<a class=\"SmallText\" href=\"MailView.php?MailID=$next_id\"><img border=0 src=\"Images/Icons/ico_next.png\"   width=\"30\" height=\"30\"   >" . gettext("Berikutnya") . "</a>";
}

// Get this mail's data
$sSQL = "SELECT a.* , IF(Dari=0,'SEKR',replace( `vol_Name` , 'Ketua', '' )) AS KodePengirim FROM SuratKeluar a
		LEFT JOIN volunteeropportunity_vol b ON a.Dari = b.vol_ID WHERE MailID = " . $iMailID;
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

// Set the page title and include HTML header

require "Include/Header.php";

						$time  = strtotime($Tanggal);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat =  $MailID."e/MG-".$KodePengirim."/".$sChurchCode."/".dec2roman($month)."/".$year;

gettext("Surat Keluar : $MailID / $NomorSurat / $Dari / $Kepada / $Institusi #");

$iTableSpacerWidth = 10;

$bOkToEdit = ($_SESSION['bEditRecords'] ||
        ($_SESSION['bEditSelf'] && $per_ID==$_SESSION['iUserID']) ||
        ($_SESSION['bEditSelf'] && $per_fam_ID==$_SESSION['iFamID'])
       );
if ($previous_link_text) {
  echo "$previous_link_text | ";
}

if ($_SESSION['bDeleteRecords']) { echo "<a class=\"SmallText\" href=\"SelectDelete.php?mode=mailout&MailID=" . $MailID . "\"><img border=0 src=\"Images/Icons/ico_del.png\"   width=\"30\" height=\"30\"   > " . gettext("Hapus") . "</a> | " ; }


if ($Email <> "") {

?> 
<a href="MailOutSend.php?MailID=<?php echo $MailID; ?>" class="SmallText"><img border=0 src="Images/Icons/ico_mail.png"  width=30 height=30  >  <?php echo gettext("Email"); ?> </a> |

<?}?>

<a href="MailOutEditor.php?MailID=<?php echo $MailID; ?>" class="SmallText"><img border=0 src="Images/Icons/ico_edit.png"  width=30 height=30  >  <?php echo gettext("Edit"); ?></a>
<?php

if ($next_link_text) {
  echo " | $next_link_text";
}
echo " | <a class=\"SmallText\" href=\"SelectListApp.php?mode=mailout\"><img border=0 src=\"Images/Icons/ico_list.png\"  width=\"30\" height=\"30\"  >" .
gettext("Daftar Surat") . "</a> ";

?>

<br><br>
<table border="0" width="100%" cellspacing="0" cellpadding="4">
<tr>

<td width="80%" valign="top" align="left">

  <b><?php echo gettext("Informasi Surat Keluar :  ")  ; ?></b>

  <div class="LightShadedBox">
  <table border="0" cellspacing="0" cellpadding="0" border="0" width="100%">
  <tr>
    <td align="center">
      <table cellspacing="1" cellpadding="0" border="0" >

      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tanggal Surat"); ?></td>
      <td class="TinyTextColumn"><?php echo date2Ind($Tanggal,2); ?></td>

      </tr>
      <tr>
        <td class="TinyLabelColumn"><?php echo gettext("Urgensi:"); ?></td>
        <td class="TinyTextColumn">
        <?php
		
		
		
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
        </td>

        <td class="TinyLabelColumn"><?php echo gettext("Sifat :"); ?></td>
        <td class="TinyTextColumn">
        <?php
		  echo $Via; 
        //  switch ($Via)
        //  {
        //  case 1:
        //    echo gettext("POS");
        //    break;
        //  case 2:
         //   echo gettext("Titipan Kilat");
        //    break;
		//  case 3:
        //    echo gettext("Kurir");
        //    break;
		//  case 4:
        //    echo gettext("Fax");
        //    break;
		//  case 5:
        //    echo gettext("Email");
        //    break;
		//  case 6:
        //    echo gettext("LainLain");
        //    break;
        //  }
        ?>
        </td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Pengirim "); ?></td>
      <td class="TinyTextColumn"><?php echo $KodePengirim ." - " .$NamaPengirim; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Kepada "); ?></td>
      <td class="TinyTextColumn"><?php echo $Kepada; ?></td>

      <td class="TinyLabelColumn"><?php echo gettext("Institusi"); ?></td>
      <td class="TinyTextColumn"><?php echo $Institusi; ?></td>
      </tr>

      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Nomor Surat"); ?></td>
      <td class="TinyTextColumn"><?php 
	  $time  = strtotime($Tanggal);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat =  $MailID."e/MG-".$KodePengirim."/".$sChurchCode."/".dec2roman($month)."/".$year;
	  
	  echo $NomorSurat; ?></td>

      <td class="TinyLabelColumn"><?php echo gettext("Hal"); ?></td>
      <td class="TinyTextColumn"><?php echo $Hal; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Lampiran"); ?></td>
      <td class="TinyTextColumn"><?php echo $Lampiran; ?></td>

      <td class="TinyLabelColumn"><?php echo gettext("TypeLampiran"); ?></td>
      <td class="TinyTextColumn"><?php echo $TypeLampiran; ?></td>
      </tr>
 	  <tr>
        <td class="TinyLabelColumn"><?php echo gettext("Kategori :"); ?></td>
        <td class="TinyTextColumn">
        <?php
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
		  case 15:
            echo gettext("Ucapan Terima Kasih");
            break;
		  case 16:
            echo gettext("Surat Keterangan");
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
		</tr>
		 <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Cetak Surat "); ?></td>
      <td class="TinyTextColumn">
	  <table>
		<tr>	<td> Cetak Surat : </td>
	  	<td><a target="_blank" href="PrintViewSuratKeluar.php?MailID=<?php echo $MailID ?>&Mode=1" title="Cetak dgn Header tanpa TTD"><?php echo "C1 " ?></a></td>
		<td><a target="_blank" href="PrintViewSuratKeluar.php?MailID=<?php echo $MailID ?>&Mode=2" title="Cetak dgn Header dgn TTD Lengkap"><?php echo "C2 " ?></a></td>
		<td><a target="_blank" href="PrintViewSuratKeluar.php?MailID=<?php echo $MailID ?>&Mode=21" title="Cetak dgn Header dgn TTD Ketua"><?php echo "C2A " ?></a></td>
		<td><a target="_blank" href="PrintViewSuratKeluar.php?MailID=<?php echo $MailID ?>&Mode=22" title="Cetak dgn Header dgn TTD Sekr1"><?php echo "C2B " ?></a></td>
		<td><a target="_blank" href="PrintViewSuratKeluar.php?MailID=<?php echo $MailID ?>&Mode=3" title="Cetak tanpa Header tanpa TTD Lengkap"><?php echo "C3 " ?></a></td>
		<td><a target="_blank" href="PrintViewSuratKeluar.php?MailID=<?php echo $MailID ?>&Mode=4" title="Cetak tanpa Header dgn TTD Lengkap"><?php echo "C4 " ?></a></td>
		<td><a target="_blank" href="PrintViewSuratKeluar.php?MailID=<?php echo $MailID ?>&Mode=41" title="Cetak tanpa Header dgn TTD Ketua"><?php echo "C4A " ?></a></td>
		<td><a target="_blank" href="PrintViewSuratKeluar.php?MailID=<?php echo $MailID ?>&Mode=42" title="Cetak tanpa Header dgn TTD Sekr1"><?php echo "C4B" ?></a></td>
		</tr>
	  </table>
	  
	  </td>
      </tr>

	  <tr>
	  	<td class="TinyLabelColumn"><?php echo gettext("Isi Surat:"); ?></td>
		<td class="TextColumn" colspan="2" >
		
		<textarea id="IsiSuratBalasan" name="IsiSuratBalasan" rows="15" cols="80" >
			<?php echo $IsiSuratBalasan;?>
		</textarea>
		
		</td>
		<td>
		<table align="top">
		<tr>
		<td  colspan="2" align="center" ><b><u><?php echo gettext("Tracking Korespondensi"); ?></u></b></td>
		</tr>		
		<tr>
		<td class="TinyLabelColumn" ><?php echo gettext("Tanggal Fax"); ?></td>
		<td class="TinyTextColumn"><?php echo date2Ind($TglFax,2); ?></td>	
		</tr>
		<tr>
		<td class="TinyLabelColumn" ><?php echo gettext("Status Fax"); ?></td>
		<td class="TinyTextColumn"><?php echo $StatusFax; ?></td>	
		</tr>		
		<tr>
		<td class="TinyLabelColumn" ><?php echo gettext("Tgl Kirim Surat"); ?></td>
		<td class="TinyTextColumn"><?php echo date2Ind($TglSurat,2); ?></td>	
		</tr>
		<tr>
		<td class="TinyLabelColumn" ><?php echo gettext("Nomor Resi"); ?></td>
		<td class="TinyTextColumn"><?php echo $ResiSurat; ?></td>	
		</tr>
		<tr>
		<td class="TinyLabelColumn" ><?php echo gettext("Dikirim dengan"); ?></td>
		<td class="TinyTextColumn"><?php echo $StatusSurat; ?></td>	
		</tr>
		<tr>
		<td class="TinyLabelColumn" ><?php echo gettext("Konfirmasi Telp"); ?></td>
		<td class="TinyTextColumn"><?php echo date2Ind($TglTelp,2); ?></td>	
		</tr>
		<tr>
		<td class="TinyLabelColumn" ><?php echo gettext("Penerima Telp"); ?></td>
		<td class="TinyTextColumn"><?php echo $PenerimaTelp; ?></td>	
		</tr>
		<tr>
		<td class="TinyLabelColumn" ><?php echo gettext("Status Telp"); ?></td>
		<td class="TinyTextColumn"><?php echo $StatusTelp; ?></td>	
		</tr>
		<tr>
		<td class="TinyLabelColumn" ><?php echo gettext("Tanggal Email"); ?></td>
		<td class="TinyTextColumn"><?php echo date2Ind($TglEmail,2); ?></td>	
		</tr>


		
		</table>
		</td>
	</tr>
	
	<tr>
      <td class="TinyLabelColumn"><?php echo gettext("Cetak Lampiran "); ?></td>
      <td class="TinyTextColumn">
	  <table>
		<tr>	<td> </td>
	  	<td><a target="_blank" href="PrintViewSuratKeluarLampiran.php?MailID=<?php echo $MailID ?>&Lamp=1&Mode=1" title="Cetak dgn Header"><?php echo "C1 : Cetak dengan Header" ?></a></td>
		<td><a target="_blank" href="PrintViewSuratKeluarLampiran.php?MailID=<?php echo $MailID ?>&Lamp=1&Mode=3" title="Cetak tanpa Header"><?php echo "C3 : Cetak tanpa Header " ?></a></td>
		</tr>
	  </table>
	  
	  </td>
      </tr>
		<tr>
	  	<td class="TinyLabelColumn"><?php echo gettext("Isi Lampiran:"); ?></td>
		<td class="TextColumn" colspan="3" >
		
		<textarea id="IsiLampiran" name="IsiLampiran" rows="15" cols="80" >
			<?php echo $IsiLampiran;?>
		</textarea>
		</td>
	</tr>
        </td>

	  
      </table>
    </td>
 
  </tr>
  </table>
  </div>
  <br>

 
