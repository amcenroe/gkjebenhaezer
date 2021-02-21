<?php
/*******************************************************************************
 *
 *  filename    : MailOutSend.php
 *  last change : 2003-04-14
 *  description : Displays all the information about a single mail
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
 *
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

// Get the Mail ID from the querystring
$iMailID = FilterInput($_GET["MailID"],'int');

$iRemoveVO = FilterInput($_GET["RemoveVO"],'int');

$dSQL= "SELECT MailID FROM SuratKeluar order by MailID";
$dResults = RunQuery($dSQL);

// Get this mail's data
$sSQL = "SELECT * FROM SuratKeluar WHERE MailID = " . $iMailID;
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

//Sending email

    switch ($Urgensi)
          {
          case 1:
            $Urgensi="Sangat Segera";
            break;
          case 2:
            $Urgensi="Segera";
            break;
		  case 3:
            $Urgensi="Biasa";
            break;
          }
		  
	switch ($Ket3)
          {
          case 11:
            $Ket3="Informasi Umum";
            break;
          case 12:
            $Ket3="Surat Edaran";
            break;
		  case 13:
            $Ket3="Undangan";
            break;
		  case 14:
            $Ket3="Laporan Kegiatan";
            break;
		case 15:
			$Ket3="Ucapan Terima Kasih";
			break;
		case 16:
			$Ket3="Surat Keterangan";
			break;
		case 17:
			$Ket3="Surat Keterangan - Warga Jemaat";
			break;					
		  case 21:
            $Ket3="Permohonan Umum";
            break;
		  case 22:
            $Ket3="Permohonan Bantuan";
            break;
		  case 23:
            $Ket3="Permohonan Pelayanan Firman";
            break;
		  case 24:
            $Ket3="Permohonan Peminjaman Asset Gereja";
            break;
		  case 25:
            $Ket3="Permohonan Pelayanan Gerejawi (Baptis/Sidi/Nikah/dll)";
            break;

		  case 31:
            $Ket3="Surat Pindah/Atestasi";
            break;
		  case 32:
            $Ket3="Surat Pemberitahuan Sakramen";
            break;
		  case 33:
            $Ket3="Surat Penitipan Rohani";
            break;
		}
		

//define the receiver of the email
//$to = 'johan.kristantara@gkj.or.id, rasimansuryaadi@yahoo.co.id , e_pratama@yahoo.com';
//define the subject of the email
$subject = "[$sChurchInitial]No Reg: $MailID tanggal $Ket1" ;
//define the message to be sent. Each line should be separated with \n
$message = "Selamat Siang Bp/Ibu/Sdr/i\n\n
Email ini adalah notifikasi dari Sekretariat $sChurchName berkenaan dengan surat masuk berikut ini :\n
	Tanggal Surat: $Tanggal
	Tanggal Diterima : $Ket1
	Urgensi: $Urgensi 
	Dari : $Dari - $Institusi
	Kepada : $Kepada
	Nomor Surat : $NomorSurat
	Hal : $Hal
	Lampiran : $Lampiran
	TypeLampiran : $TypeLampiran
	Deskripsi Singkat isi Surat : $Ket2
	Kategori : $Ket3\n
Atas perhatiannya kami ucapkan terima kasih.\n\n
Salam
\n\n
Catatan: Email ini dikirim langsung dari system secara otomatis, bagi bapak/ibu/sdr/i yang membutuhkan berkas asli surat bisa menghubungi sekretariat	";

			
			
			
			
			
			
//define the headers we want passed. Note that they are separated with \r\n
$headers = "From: $sChurchEmail\r\nReply-To: $sChurchEmail";
//send the email
$mail_sent = @mail( $to, $subject, $message, $headers );
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
echo $mail_sent ? "Email Anda sudah terkirim" : "Mail failed";






// Set the page title and include HTML header

require "Include/Header.php";

gettext("Kirim Email Pemberitahuan Surat Masuk : $MailID / $NomorSurat / $Dari / $Institusi #");

$iTableSpacerWidth = 10;

$bOkToEdit = ($_SESSION['bEditRecords'] ||
        ($_SESSION['bEditSelf'] && $per_ID==$_SESSION['iUserID']) ||
        ($_SESSION['bEditSelf'] && $per_fam_ID==$_SESSION['iFamID'])
       );
if ($previous_link_text) {
  echo "$previous_link_text | ";
}
if ($bOkToEdit) {
  echo "<a class=\"SmallText\" href=\"MailEditor.php?MailID=" . $MailID .
     "\"><img border=0 src=\"Images/Icons/ico_edit.png\"  width=\"30\" height=\"30\"   >" . gettext("Edit") . "</a> | ";
}

if ($_SESSION['bDeleteRecords']) { echo "<a class=\"SmallText\" href=\"SelectDelete.php?mode=mail&MailID=" . $MailID . "\"><img border=0 src=\"Images/Icons/ico_del.png\"   width=\"30\" height=\"30\"   > " . gettext("Hapus") . "</a> | " ; }
?>
<a href="PrintViewMail.php?MailID=<?php echo $MailID; ?>" class="SmallText" target="_blank"><img border=0 src="Images/Icons/ico_print.png"  width=30 height=30  >  <?php echo gettext("Cetak"); ?></a>
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
<td width="20%" valign="top" align="center">

  </div>
</td>

<td width="80%" valign="top" align="left">

  <b><?php echo gettext("Informasi Surat:  ")  ; ?></b>
  <b><?php echo gettext("$MailID - $Tanggal - $Dari - $Institusi"); ?> </b>
  <div class="LightShadedBox">
  <table cellspacing="0" cellpadding="0" border="0" width="100%">
  <tr>
    <td align="center">
      <table cellspacing="1" cellpadding="0" border="0" >

      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tanggal Surat"); ?></td>
      <td class="TinyTextColumn"><?php echo $Tanggal; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tanggal Diterima"); ?></td>
      <td class="TinyTextColumn"><?php echo $Ket1; ?></td>
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
      </tr>
      <tr>
        <td class="TinyLabelColumn"><?php echo gettext("Dikirim dengan :"); ?></td>
        <td class="TinyTextColumn">
        <?php
          switch ($Via)
          {
          case 1:
            echo gettext("POS");
            break;
          case 2:
            echo gettext("Titipan Kilat");
            break;
		  case 3:
            echo gettext("Kurir");
            break;
		  case 4:
            echo gettext("Fax");
            break;
		  case 5:
            echo gettext("Email");
            break;
		  case 6:
            echo gettext("LainLain");
            break;
          }
        ?>
        </td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Dari"); ?></td>
      <td class="TinyTextColumn"><?php echo $Dari; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Institusi"); ?></td>
      <td class="TinyTextColumn"><?php echo $Institusi; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tujuan"); ?></td>
      <td class="TinyTextColumn"><?php echo $Kepada; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Nomor Surat"); ?></td>
      <td class="TinyTextColumn"><?php echo $NomorSurat; ?></td>
      </tr>	  
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Hal"); ?></td>
      <td class="TinyTextColumn"><?php echo $Hal; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Lampiran"); ?></td>
      <td class="TinyTextColumn"><?php echo $Lampiran; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("TypeLampiran"); ?></td>
      <td class="TinyTextColumn"><?php echo $TypeLampiran; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Deskripsi Singkat isi Surat"); ?></td>
      <td class="TinyTextColumn"><?php echo $Ket2; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("FollowUp"); ?></td>
      <td class="TinyTextColumn"><?php echo $FollowUp; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Status"); ?></td>
      <td class="TinyTextColumn"><?php echo $Status; ?></td>
      </tr>
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
        </td>

	  
      </table>
    </td>
 
  </tr>
  </table>
  </div>
  <br>

 
