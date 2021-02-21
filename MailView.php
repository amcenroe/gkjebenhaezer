<?php
/*******************************************************************************
 *
 *  filename    : MailView.php
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
// General options
mode : "textareas",
theme : "simple",
plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",
// Theme options
theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
theme_advanced_statusbar_location : "bottom",
theme_advanced_resizing : true,
// Example content CSS (should be your site CSS)
//content_css : "css/content.css",
// Drop lists for link/image/media/template dialogs
template_external_list_url : "lists/template_list.js",
external_link_list_url : "lists/link_list.js",
external_image_list_url : "lists/image_list.js",
media_external_list_url : "lists/media_list.js",
// Replace values for the template plugin
template_replace_values : {
username : "Some User",
staffid : "991234"
}
});
</script>
<!-- /TinyMCE -->



<?php


// Fungsi untuk bikin resize image
function miniw($image_file, $destination, $width, $quality){
# $image_file = sumber gambar
# $destination = hasil thumbnail (path + file)
# $width = lebar thumbnail (pixel)
# $quality = kualitas JPG thumbnail

$thumbw = $width; //lebar=100; tinggi akan diproposionalkan

$src_img = imagecreatefromjpeg($image_file);
$size[0] = ImageSX($src_img); // lebar
$size[1] = ImageSY($src_img); // tinggi

$thumbh = ($thumbw/$size[0])*$size[1]; //height
$scale = min($thumbw/$size[0], $thumbh/$size[1]);
$width = (int)($size[0]*$scale);
$height = (int)($size[1]*$scale);
$deltaw = (int)(($thumbw - $width)/2);
$deltah = (int)(($thumbh - $height)/2);

if($thumbw < $size[0]){
$dst_img = imagecreatetruecolor($thumbw, $thumbh);
imagecopyresampled($dst_img, $src_img, 0,0, 0,0, $thumbw, $thumbh,
$size[0], $size[1]);
touch($destination);
imagejpeg($dst_img, $destination, $quality); // Ini hasil akhirnya.
imagedestroy($dst_img);
} else {
$dst_img = imagecreatetruecolor($size[0], $size[1]);
imagecopyresampled($dst_img, $src_img, 0,0, 0,0, $size[0], $size[1],
$size[0], $size[1]);
touch($destination);
imagejpeg($dst_img, $destination, $quality); // Ini hasil akhirnya.
imagedestroy($dst_img);
}

imagedestroy($src_img);
}


// Get the Mail ID from the querystring
$iMailID = FilterInput($_GET["MailID"],'int');

$iRemoveVO = FilterInput($_GET["RemoveVO"],'int');

$dSQL= "SELECT MailID FROM SuratMasuk order by MailID";
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
$sSQL = "SELECT a.* ,
b.vol_name as Jabatan1, d.p2vo_per_id as perIDJab1, f.per_email as EmailJab1, f.per_Firstname as Pejabat1,
c.vol_name as Jabatan2, e.p2vo_per_id as perIDJab2, g.per_email as EmailJab2, g.per_Firstname as Pejabat2
FROM SuratMasuk a
LEFT JOIN volunteeropportunity_vol b ON a.Bidang1 = b.vol_ID
LEFT JOIN volunteeropportunity_vol c ON a.Bidang2 = c.vol_ID

LEFT JOIN person2volunteeropp_p2vo d ON a.Bidang1 = d.p2vo_vol_id
LEFT JOIN person2volunteeropp_p2vo e ON a.Bidang2 = e.p2vo_vol_id

LEFT JOIN person_per f ON d.p2vo_per_id = f.per_ID
LEFT JOIN person_per g ON e.p2vo_per_id = g.per_ID

WHERE MailID = " . $iMailID;
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

// Set the page title and include HTML header

require "Include/Header.php";



gettext("Surat Masuk : $MailID / $NomorSurat / $Dari / $Institusi #");

$iTableSpacerWidth = 10;

$bOkToEdit = ($_SESSION['bEditRecords'] ||
        ($_SESSION['bEditSelf'] && $per_ID==$_SESSION['iUserID']) ||
        ($_SESSION['bEditSelf'] && $per_fam_ID==$_SESSION['iFamID'])
       );
if ($previous_link_text) {
  echo "$previous_link_text | ";
}

if ($_SESSION['bDeleteRecords']) { echo "<a class=\"SmallText\" href=\"SelectDelete.php?mode=mail&MailID=" . $MailID . "\"><img border=0 src=\"Images/Icons/ico_del.png\"   width=\"30\" height=\"30\"   > " . gettext("Hapus") . "</a> | " ; }

?>
<a href="PrintViewMail.php?MailID=<?php echo $MailID; ?>" class="SmallText" target="_blank"><img border=0 src="Images/Icons/ico_print.png"  width=30 height=30  >  <?php echo gettext("Cetak Disposisi"); ?> </a> |
<a href="SelectSendMail.php?MailID=<?php echo $MailID; ?>&mode=SuratMasuk" class="SmallText" ><img border=0 src="Images/Icons/ico_mail.png"  width=30 height=30  >  <?php echo gettext("Email"); ?> </a> |
<a href="MailEditor.php?MailID=<?php echo $MailID; ?>" class="SmallText" ><img border=0 src="Images/Icons/ico_edit.png"  width=30 height=30  >  <?php echo gettext("Edit"); ?></a>
<?php

if ($next_link_text) {
  echo " | $next_link_text";
}
echo " | <a class=\"SmallText\" href=\"SelectListApp.php?mode=mail\"><img border=0 src=\"Images/Icons/ico_list.png\"  width=\"30\" height=\"30\"  >" .
gettext("Daftar Surat") . "</a> ";

?>

<br><br>
<table border="0" width="100%" cellspacing="0" cellpadding="4">
<tr>
<td width="20%" valign="top" align="center">


  <?php
  
echo "<div class=\"LightShadedBox\"> ";



    echo "<br>";

    // Upload Berkas surat halaman 1
    if ( isset($_POST["UploadPhoto"]) && ($_SESSION['bAddRecords'] || $bOkToEdit) ) {
      if ($_FILES['Photo']['name'] == "") {
        $PhotoError = gettext("Tidak Ada Photo yang di upload.");
      } elseif ($_FILES['Photo']['type'] != "image/pjpeg" && $_FILES['Photo']['type'] != "image/jpeg") {
        $PhotoError = gettext("Hanya Format JPEG yang bisa di upload.");
      } else {
        // Create the thumbnail used by MailView

            chmod ($_FILES['Photo']['tmp_name'], 0777);

        $srcImage=imagecreatefromjpeg($_FILES['Photo']['tmp_name']);
        $src_w=imageSX($srcImage);
          $src_h=imageSY($srcImage);

        // Calculate thumbnail's height and width (a "maxpect" algorithm)
        $dst_max_w = 150;
        $dst_max_h = 300;
        if ($src_w > $dst_max_w) {
          $thumb_w=$dst_max_w;
          $thumb_h=$src_h*($dst_max_w/$src_w);
          if ($thumb_h > $dst_max_h) {
            $thumb_h = $dst_max_h;
            $thumb_w = $src_w*($dst_max_h/$src_h);
          }
        }
        elseif ($src_h > $dst_max_h) {
          $thumb_h=$dst_max_h;
          $thumb_w=$src_w*($dst_max_h/$src_h);
          if ($thumb_w > $dst_max_w) {
            $thumb_w = $dst_max_w;
            $thumb_h = $src_h*($dst_max_w/$src_w);
          }
        }
        else {
          if ($src_w > $src_h) {
            $thumb_w = $dst_max_w;
            $thumb_h = $src_h*($dst_max_w/$src_w);
          } elseif ($src_w < $src_h) {
            $thumb_h = $dst_max_h;
            $thumb_w = $src_w*($dst_max_h/$src_h);
          } else {
            if ($dst_max_w >= $dst_max_h) {
              $thumb_w=$dst_max_h;
              $thumb_h=$dst_max_h;
            } else {
              $thumb_w=$dst_max_w;
              $thumb_h=$dst_max_w;
            }
          }
        }
        $dstImage=ImageCreateTrueColor($thumb_w,$thumb_h);
            imagecopyresampled($dstImage,$srcImage,0,0,0,0,$thumb_w,$thumb_h,$src_w,$src_h);
        imagejpeg($dstImage, "Images/Mail/thumbnails/" . $iMailID . ".jpg");
//        imagedestroy($dstImage);
//         imagedestroy($srcImage);

        // Calculate Resize Doc height and width (a "maxpect" algorithm)
        $dst_max_w = 800;
        $dst_max_h = 1100;
        if ($src_w > $dst_max_w) {
          $thumb_w=$dst_max_w;
          $thumb_h=$src_h*($dst_max_w/$src_w);
          if ($thumb_h > $dst_max_h) {
            $thumb_h = $dst_max_h;
            $thumb_w = $src_w*($dst_max_h/$src_h);
          }
        }
        elseif ($src_h > $dst_max_h) {
          $thumb_h=$dst_max_h;
          $thumb_w=$src_w*($dst_max_h/$src_h);
          if ($thumb_w > $dst_max_w) {
            $thumb_w = $dst_max_w;
            $thumb_h = $src_h*($dst_max_w/$src_w);
          }
        }
        else {
          if ($src_w > $src_h) {
            $thumb_w = $dst_max_w;
            $thumb_h = $src_h*($dst_max_w/$src_w);
          } elseif ($src_w < $src_h) {
            $thumb_h = $dst_max_h;
            $thumb_w = $src_w*($dst_max_h/$src_h);
          } else {
            if ($dst_max_w >= $dst_max_h) {
              $thumb_w=$dst_max_h;
              $thumb_h=$dst_max_h;
            } else {
              $thumb_w=$dst_max_w;
              $thumb_h=$dst_max_w;
            }
          }
        }
        $dstImage=ImageCreateTrueColor($thumb_w,$thumb_h);
            imagecopyresampled($dstImage,$srcImage,0,0,0,0,$thumb_w,$thumb_h,$src_w,$src_h);
        imagejpeg($dstImage, "Images/Mail/" . $iMailID . ".jpg");
        imagedestroy($dstImage);
          imagedestroy($srcImage);




        //move_uploaded_file($_FILES['Photo']['tmp_name'], "Images/Mail/" . $iMailID . ".jpg");
      }
    } elseif (isset($_POST["DeletePhoto"]) && $_SESSION['bDeleteRecords']) {
      unlink("Images/Mail/" . $iMailID . ".jpg");
      unlink("Images/Mail/thumbnails/" . $iMailID . ".jpg");
    }

    // Display photo or upload from file
    $photoFile = "Images/Mail/thumbnails/" . $iMailID . ".jpg";


        if (file_exists($photoFile))
        {
            echo '<a target="_blank" href="Images/Mail/' . $iMailID . '.jpg">';
            echo '<img border="1" src="'.$photoFile.'"  Images/NoMail.gif" width="128" height="126"   ></a>';
            if ($bOkToEdit) {
                echo '
                    <form method="post"
                    action="MailView.php?MailID=' . $iMailID . '">
                    <br>
                    <input type="submit" class="icTinyButton"
                    value="' . gettext("Hapus Berkas") . '" name="DeletePhoto">
                    </form>';
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

                echo '
                    <form method="post"
                    action="MailView.php?MailID=' . $iMailID . '"
                    enctype="multipart/form-data">
                    <input class="icTinyButton" type="file" name="Photo" size="1">
                    <input type="submit" class="icTinyButton"
                    value="' . gettext("Upload Berkas") . '" name="UploadPhoto">
                    </form>';
            }
        }

    // Display photo or upload from file

    $photoFile1 = 'streamimg.php?rest=SRTTHB126128'.$iMailID;
    $photoFile2 = 'streamimg.php?res='.enkrip(SRT,HRS,800,999,$iMailID);

//    echo $photoFile1;
    
        if (file_exists($photoFile))
        {
            echo '<a target="_blank" href="'.$photoFile2.'">';
            echo '<img border="1" src="'.$photoFile1.'"  Images/NoMail.gif" width="128" height="126"   ></a>';
            if ($bOkToEdit) {
                echo '
                    <form method="post"
                    action="MailView.php?MailID=' . $iMailID . '">
                    <br>
                    <input type="submit" class="icTinyButton"
                    value="' . gettext("Hapus Berkas") . '" name="DeletePhoto">
                    </form>';
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

                echo '
                    <form method="post"
                    action="MailView.php?MailID=' . $iMailID . '"
                    enctype="multipart/form-data">
                    <input class="icTinyButton" type="file" name="Photo" size="1">
                    <input type="submit" class="icTinyButton"
                    value="' . gettext("Upload Berkas") . '" name="UploadPhoto">
                    </form>';
            }
        }

    echo "<br>";

  

        ?>
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
      <td class="TinyTextColumn"><?php echo date2Ind($Tanggal,1) ; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tanggal Diterima"); ?></td>
      <td class="TinyTextColumn"><?php echo date2Ind($Ket1,1) ; ?></td>
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
      <td class="TinyLabelColumn"><?php echo gettext("Bidang Terkait"); ?></td>
      <td class="TinyTextColumn"><?php echo $Jabatan1." (".$Pejabat1.") , ".$Jabatan2." (".$Pejabat2.")"; ?></td>
      </tr>	  
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Status"); ?></td>
      <td class="TinyTextColumn"><?php echo $Status; ?></td>
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
      <td class="TinyLabelColumn"><?php echo gettext("Surat Jawaban"); ?></td>
      <td class="TinyTextColumn">
	  <table>
		<tr>	<td> Cetak Surat Balasan : </td>
	  			<td><a target="_blank" href="PrintViewSuratBalasan.php?MailID=<?php echo $MailID ?>&Mode=1"><?php echo "C1" ?></a></td>
				<td><a target="_blank" href="PrintViewSuratBalasan.php?MailID=<?php echo $MailID ?>&Mode=2"><?php echo "C2" ?></a></td>
				<td><a target="_blank" href="PrintViewSuratBalasan.php?MailID=<?php echo $MailID ?>&Mode=3"><?php echo "C3" ?></a></td>
				<td><a target="_blank" href="PrintViewSuratBalasan.php?MailID=<?php echo $MailID ?>&Mode=4"><?php echo "C4" ?></a></td>
		</tr>
	  </table>
	  
	  </td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Dibalas Tanggal"); ?></td>
      <td class="TinyTextColumn"><?php echo date2Ind($TglDibalas,1); ?></td>
      </tr>

	  <tr>
	  	<td class="TinyLabelColumn"><?php echo gettext("Isi Surat:"); ?></td>
		<td class="TextColumn" colspan="3" >
		
		<textarea id="IsiSuratBalasan" name="IsiSuratBalasan" rows="15" cols="80" >
			<?php echo $IsiSuratBalasan;?>
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

 
