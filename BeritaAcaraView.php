<?php
/*******************************************************************************
 *
 *  filename    : BeritaAcaraView.php
 *  description : Displays all the information about a single BA
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


// Get the BA ID from the querystring
$iBAID = FilterInput($_GET["BAID"],'int');

$iRemoveVO = FilterInput($_GET["RemoveVO"],'int');

$dSQL= "SELECT BAID FROM BeritaAcara order by BAID";
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
  if ($pid == $iBAID)
  {
    $previous_id = $last_id;
    $capture_next = 1;
  }
  $last_id = $pid;
}

if (($previous_id > 0)) {
    $previous_link_text = "<a class=\"SmallText\" href=\"BeritaAcaraView.php?BAID=$previous_id\"><img border=0 src=\"Images/Icons/ico_prev.png\"  width=\"30\" height=\"30\"  > " . gettext("Sebelumnya") . "</a>";
}

if (($next_id > 0)) {
    $next_link_text = "<a class=\"SmallText\" href=\"BeritaAcaraView.php?BAID=$next_id\"><img border=0 src=\"Images/Icons/ico_next.png\"   width=\"30\" height=\"30\"   >" . gettext("Berikutnya") . "</a>";
}


// Get this BA's data
$sSQL = "SELECT a.* 
FROM BeritaAcara a
WHERE BAID = " . $iBAID;
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

// Set the page title and include HTML header

require "Include/Header.php";

						$time  = strtotime($Tanggal);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat2 =  $BAID."/MG/BA/".$NomorSurat."/".$sChurchCode."/".dec2roman($month)."/".$year;

gettext("Berita Acara : $NomorSurat2 ");

$iTableSpacerWidth = 10;

$bOkToEdit = ($_SESSION['bEditRecords'] ||
        ($_SESSION['bEditSelf'] && $per_ID==$_SESSION['iUserID']) ||
        ($_SESSION['bEditSelf'] && $per_fam_ID==$_SESSION['iFamID'])
       );
if ($previous_link_text) {
  echo "$previous_link_text | ";
}

if ($_SESSION['bDeleteRecords']) { echo "<a class=\"SmallText\" href=\"SelectDelete.php?mode=beritaacara&BAID=" . $BAID . "\"><img border=0 src=\"Images/Icons/ico_del.png\"   width=\"30\" height=\"30\"   > " . gettext("Hapus") . "</a> | " ; }

?>
<a href="BeritaAcaraEditor.php?BAID=<?php echo $BAID; ?>" class="SmallText" ><img border=0 src="Images/Icons/ico_edit.png"  width=30 height=30  >  <?php echo gettext("Edit"); ?></a>
<?php

if ($next_link_text) {
  echo " | $next_link_text";
}
echo " | <a class=\"SmallText\" href=\"SelectListApp.php?mode=beritaacara\"><img border=0 src=\"Images/Icons/ico_list.png\"  width=\"30\" height=\"30\"  >" .
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
        // Create the thumbnail used by BAView

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
        imagejpeg($dstImage, "Images/BA/thumbnails/" . $iBAID . ".jpg");
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
        imagejpeg($dstImage, "Images/BA/" . $iBAID . ".jpg");
        imagedestroy($dstImage);
          imagedestroy($srcImage);




        //move_uploaded_file($_FILES['Photo']['tmp_name'], "Images/BA/" . $iBAID . ".jpg");
      }
    } elseif (isset($_POST["DeletePhoto"]) && $_SESSION['bDeleteRecords']) {
      unlink("Images/BA/" . $iBAID . ".jpg");
      unlink("Images/BA/thumbnails/" . $iBAID . ".jpg");
    }

    // Display photo or upload from file
    $photoFile = "Images/BA/thumbnails/" . $iBAID . ".jpg";
        if (file_exists($photoFile))
        {
            echo '<a target="_blank" href="Images/BA/' . $iBAID . '.jpg">';
            echo '<img border="1" src="'.$photoFile.'"  Images/NoBA.gif" width="128" height="126"   ></a>';
            if ($bOkToEdit) {
                echo '
                    <form method="post"
                    action="BeritaAcaraView.php?BAID=' . $iBAID . '">
                    <br>
                    <input type="submit" class="icTinyButton"
                    value="' . gettext("Hapus Berkas") . '" name="DeletePhoto">
                    </form>';
                }
        } else {
            // Some old / M$ browsers can't handle PNG's correctly.
            if ($bDefectiveBrowser)
                echo '<img border="0" src="Images/NoBA.gif" width="64" height="63"  ><br><br><br>';
            else
                echo '<img border="0" src="Images/NoBA.png" width="64" height="63"  ><br><br><br>';

            if ($bOkToEdit) {
                if (isset($PhotoError))
                    echo '<span style="color: red;">' . $PhotoError . '</span><br>';

                echo '
                    <form method="post"
                    action="BeritaAcaraView.php?BAID=' . $iBAID . '"
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

  <b><?php echo gettext("Informasi Berita Acara :  ")  ; ?></b>
  <b><?php echo gettext("$BAID - $NomorSurat2"); ?> </b>
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
      <td class="TinyLabelColumn"><?php echo gettext("Nomor Surat"); ?></td>
      <td class="TinyTextColumn"><?php 
	  						if ($BAID == 0){ echo $NomorSurat; }else{	echo $NomorSurat2 ;}?></td>
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
      <td class="TinyLabelColumn"><?php echo gettext("Tembusan"); ?></td>
      <td class="TinyTextColumn"><?php echo "- ".$Tembusan1."<br>- ".$Tembusan2."<br>- ".$Tembusan3."<br>- ".$Tembusan4; ?></td>
      </tr>
	  <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Cetak Surat "); ?></td>
      <td class="TinyTextColumn">
	  <table>
		<tr>	<td> Cetak Berita Acara: </td>
	  			<td><a target="_blank" href="PrintViewBeritaAcara.php?Type=ISI&BAID=<?php echo $BAID ?>&Mode=1"><?php echo "Cetak dengan Kop Surat" ?></a> atau </td>
				<td><a target="_blank" href="PrintViewBeritaAcara.php?Type=ISI&BAID=<?php echo $BAID ?>&Mode=3"><?php echo "Cetak polos" ?></a></td>
		</tr>
	  </table>
	  </td>
      </tr>
	  <tr>
	  	<td class="TinyLabelColumn"><?php echo gettext("Isi Surat:"); ?></td>
		<td class="TextColumn" colspan="3" >
		
		<textarea id="IsiBeritaAcara" name="IsiBeritaAcara" rows="15" cols="80" >
			<?php echo $IsiBeritaAcara;?>
		</textarea>
		</td>
		</tr>
	  <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Cetak Lampiran "); ?></td>
      <td class="TinyTextColumn">
	  <table>
		<tr>	<td> Cetak Berita Acara: </td>
	  			<td><a target="_blank" href="PrintViewBeritaAcara.php?Type=LAMP&BAID=<?php echo $BAID ?>&Mode=1"><?php echo "Cetak dengan Kop Surat " ?> </a> atau </td>
	
				<td><a target="_blank" href="PrintViewBeritaAcara.php?Type=LAMP&BAID=<?php echo $BAID ?>&Mode=3"><?php echo "Cetak Polos" ?></a></td>

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

 
