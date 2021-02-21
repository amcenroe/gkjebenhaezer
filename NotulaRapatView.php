<?php
/*******************************************************************************
 *
 *  filename    : NotulaRapatView.php
 *  last change : 2003-04-14
 *  description : Displays all the information about Notula Rapat
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
 *              : 2013 Erwin Pratama for GKJ Klasis Jakarta Bagian Timur
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
content_css : "css/content.css",
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


// Get the Akta ID from the querystring
$iNotulaRapatID = FilterInput($_GET["NotulaRapatID"],'int');

$iRemoveVO = FilterInput($_GET["RemoveVO"],'int');

$dSQL= "SELECT NotulaRapatID FROM NotulaRapat order by NotulaRapatID";
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
  if ($pid == $iNotulaRapatID)
  {
    $previous_id = $last_id;
    $capture_next = 1;
  }
  $last_id = $pid;
}

if (($previous_id > 0)) {
    $previous_link_text = "<a class=\"SmallText\" href=\"NotulaRapatView.php?NotulaRapatID=$previous_id\"><img border=0 src=\"Images/Icons/ico_prev.png\"  width=\"30\" height=\"30\"  > " . gettext("Sebelumnya") . "</a>";
}

if (($next_id > 0)) {
    $next_link_text = "<a class=\"SmallText\" href=\"NotulaRapatView.php?NotulaRapatID=$next_id\"><img border=0 src=\"Images/Icons/ico_next.png\"   width=\"30\" height=\"30\"   >" . gettext("Berikutnya") . "</a>";
}


// Get this Notula Rapat's data
        $sSQL = "SELECT a.*, a.Keterangan as KetNotula, b.* FROM NotulaRapat a
		LEFT JOIN DaftarGerejaGKJ b ON a.GerejaID=b.GerejaID
		WHERE NotulaRapatID = " . $iNotulaRapatID;
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

// Set the page title and include HTML header

require "Include/Header.php";

gettext("Notula Rapat : $NotulaRapatID / $GerejaID / $NamaGereja / $Tanggal #");

$iTableSpacerWidth = 10;

$bOkToEdit = ($_SESSION['bEditRecords'] ||
        ($_SESSION['bEditSelf'] && $per_ID==$_SESSION['iUserID']) ||
        ($_SESSION['bEditSelf'] && $per_fam_ID==$_SESSION['iFamID'])
       );
if ($previous_link_text) {
  echo "$previous_link_text | ";
}

if ($_SESSION['bDeleteRecords']) { echo "<a class=\"SmallText\" href=\"SelectDelete.php?mode=NotulaRapat&NotulaRapatID=" . $NotulaRapatID . "\"><img border=0 src=\"Images/Icons/ico_del.png\"   width=\"30\" height=\"30\"   > " . gettext("Hapus") . "</a> | " ; }

?>
<a href="PrintViewNotulaRapat.php?NotulaRapatID=<?php echo $NotulaRapatID; ?>&Mode=1" class="SmallText" target="_blank"><img border=0 src="Images/Icons/ico_print.png"  width=30 height=30  >  <?php echo gettext("Cetak Notula"); ?> </a> |
<a href="NotulaRapatEditor.php?NotulaRapatID=<?php echo $NotulaRapatID; ?>" class="SmallText" ><img border=0 src="Images/Icons/ico_edit.png"  width=30 height=30  >  <?php echo gettext("Edit"); ?></a>
<?php

if ($next_link_text) {
  echo " | $next_link_text";
}
echo " | <a class=\"SmallText\" href=\"SelectListApp.php?mode=notularapat\"><img border=0 src=\"Images/Icons/ico_list.png\"  width=\"30\" height=\"30\"  >" .
gettext("Daftar Notula Rapat") . "</a> ";

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
        // Create the thumbnail used by NotulaRapatView

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
        imagejpeg($dstImage, "NotulaRapat/thumbnails/" . $iNotulaRapatID . ".jpg");
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
        imagejpeg($dstImage, "NotulaRapat/" . $iNotulaRapatID . ".jpg");
        imagedestroy($dstImage);
          imagedestroy($srcImage);




        //move_uploaded_file($_FILES['Photo']['tmp_name'], "NotulaRapat/" . $iNotulaRapatID . ".jpg");
      }
    } elseif (isset($_POST["DeletePhoto"]) && $_SESSION['bDeleteRecords']) {
      unlink("NotulaRapat/" . $iNotulaRapatID . ".jpg");
      unlink("NotulaRapat/thumbnails/" . $iNotulaRapatID . ".jpg");
    }

    // Display photo or upload from file
    $photoFile = "NotulaRapat/thumbnails/" . $iNotulaRapatID . ".jpg";
        if (file_exists($photoFile))
        {
            echo '<a target="_blank" href="NotulaRapat/' . $iNotulaRapatID . '.jpg">';
            echo '<img border="1" src="'.$photoFile.'"  Images/NoMail.gif" width="128" height="126"   ></a>';
            if ($bOkToEdit) {
                echo '
                    <form method="post"
                    action="NotulaRapatView.php?NotulaRapatID=' . $iNotulaRapatID . '">
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
                    action="NotulaRapatView.php?NotulaRapatID=' . $iNotulaRapatID . '"
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

  <b><?php echo gettext("Informasi Notula Rapat:  ")  ; ?></b>
  <b><?php echo gettext("$NotulaRapatID - $NomorSurat - $Tanggal - $NamaGereja - $KetNotula"); ?> </b>
  <div class="LightShadedBox">
  <table cellspacing="0" cellpadding="0" border="0" width="100%">
  <tr>
    <td align="center">
      <table cellspacing="1" cellpadding="0" border="0" >
	  <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Rapat "); ?></td>
      <td class="TinyTextColumn"><?php echo $NomorSurat ." . Keterangan : ". $KetNotula ; ?></td>
      </tr> 
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tempat / Tanggal / Pukul"); ?></td>
      <td class="TinyTextColumn"><?php echo $NamaGereja." / ".date2Ind($Tanggal,1)." / ".$Pukul; ; ?></td>
      </tr>	  
	  <tr>
	  	<td class="TinyLabelColumn"><?php echo gettext("Isi Notula:"); ?></td>
		<td class="TextColumn" colspan="3" >
		
		<textarea id="IsiNotula" name="IsiNotula" rows="15" cols="80" >
			<?php echo $IsiNotula;?>
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

 
