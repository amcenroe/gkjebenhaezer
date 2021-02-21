<?php
/*******************************************************************************
 *
 *  filename    : AsetView.php
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
$iAssetID = FilterInput($_GET["AssetID"],'int');

$iRemoveVO = FilterInput($_GET["RemoveVO"],'int');

$dSQL= "SELECT AssetID FROM asetgkjbekti order by AssetID";
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
  if ($pid == $iAssetID)
  {
    $previous_id = $last_id;
    $capture_next = 1;
  }
  $last_id = $pid;
}

if (($previous_id > 0)) {
    $previous_link_text = "<a class=\"SmallText\" href=\"AsetView.php?AssetID=$previous_id\"><img border=0 src=\"Images/Icons/ico_prev.png\"  width=\"30\" height=\"30\"  > " . gettext("Sebelumnya") . "</a>";
}

if (($next_id > 0)) {
    $next_link_text = "<a class=\"SmallText\" href=\"AsetView.php?AssetID=$next_id\"><img border=0 src=\"Images/Icons/ico_next.png\"   width=\"30\" height=\"30\"   >" . gettext("Berikutnya") . "</a>";
}

// Get this ASET's data


		
       $sSQL = "SELECT * FROM asetgkjbekti a
		    LEFT JOIN LokasiTI b ON a.Location=b.KodeTI 
			LEFT JOIN asetklasifikasi c ON a.AssetClass=c.ClassID 
			LEFT JOIN asetruangan d ON a.StorageCode=d.StorageCode
			LEFT JOIN asetstatus e ON a.Status=e.StatusCode 
			 
			
		WHERE AssetID = " . $iAssetID;	
		
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

// Set the page title and include HTML header

require "Include/Header.php";



//echo gettext("Aset : $AssetID-$AssetClass-$majorclass-$minorclass-$NamaTI ");
?>
<table border="0" width="100%" cellspacing="0" cellpadding="4">
<tr>
<td width="20%" valign="top" align="center">
<?php 
$iTableSpacerWidth = 10;

$bOkToEdit = ($_SESSION['bEditRecords'] ||
        ($_SESSION['bEditSelf'] && $per_ID==$_SESSION['iUserID']) ||
        ($_SESSION['bEditSelf'] && $per_fam_ID==$_SESSION['iFamID'])
       );
if ($previous_link_text) {
  echo "$previous_link_text | ";
}
if ($_SESSION['bDeleteRecords']) { echo "<a class=\"SmallText\" href=\"SelectDelete.php?mode=aset&AssetID=" . $AssetID . "\"><img border=0 src=\"Images/Icons/ico_del.png\"   width=\"30\" height=\"30\"   > " . gettext("Hapus") . "</a> | " ; }
?>
<a href="PrintViewAset.php?AssetID=<?php echo $AssetID; ?>" class="SmallText" target="_blank"><img border=0 src="Images/Icons/ico_print.png"  width=30 height=30  >  <?php echo gettext("Cetak"); ?> </a> |
<a href="AsetEditor.php?AssetID=<?php echo $AssetID; ?>" class="SmallText" ><img border=0 src="Images/Icons/ico_edit.png"  width=30 height=30  >  <?php echo gettext("Edit"); ?></a>
<?php

if ($next_link_text) {
  echo " | $next_link_text";
}
echo " | <a class=\"SmallText\" href=\"SelectList.php?mode=aset\"><img border=0 src=\"Images/Icons/ico_list.png\"  width=\"30\" height=\"30\"  >" .
gettext("Daftar Aset") . "</a> ";

?>
</td>
</tr>
</table>

<br><br>
<table border="0" width="100%" cellspacing="0" cellpadding="4">
<tr>

<td width="100%" valign="top" align="left">


	  <?php
	  	list($year , $month, $day ) = preg_split('[/.-]', $Tahun);
		//echo "Month: $month; Day: $day; Year: $year<br />\n"
		?>


  <b><?php echo gettext("Informasi Detail Aset:  ")  ; ?></b>
  <b><?php echo gettext("$AssetClass/$AssetID/$year/$Location"); ?> </b><br>
  <i><?php echo gettext("Keterangan : Klasifikasi / Asset ID / Tahun / Kode Lokasi "); ?> </i>
  <div class="LightShadedBox">
  <table cellspacing="0" cellpadding="0" border="0" width="100%">
  
<tr>
<td valign="top" align="center">
    <b>
	<br>
	<?php echo gettext("$AssetClass/$AssetID/$year/$Location"); ?> </b>
	<br><hr>

  <?php
  

    // Upload Foto Aset
    if ( isset($_POST["UploadPhoto"]) && ($_SESSION['bAddRecords'] || $bOkToEdit) ) {
      if ($_FILES['Photo']['name'] == "") {
        $PhotoError = gettext("Tidak Ada Photo yang di upload.");
      } elseif ($_FILES['Photo']['type'] != "image/pjpeg" && $_FILES['Photo']['type'] != "image/jpeg") {
        $PhotoError = gettext("Hanya Format JPEG yang bisa di upload.");
      } else {
        // Create the thumbnail used by AsetView

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
        imagejpeg($dstImage, "Images/Aset/thumbnails/Aset" . $iAssetID . ".jpg");
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
        imagejpeg($dstImage, "Images/Aset/Aset" . $iAssetID . ".jpg");
        imagedestroy($dstImage);
          imagedestroy($srcImage);




        //move_uploaded_file($_FILES['Photo']['tmp_name'], "Images/Pak/" . $iAssetID . ".jpg");
      }
    } elseif (isset($_POST["DeletePhoto"]) && $_SESSION['bDeleteRecords']) {
      unlink("Images/Aset/Aset" . $iAssetID . ".jpg");
      unlink("Images/Aset/thumbnails/Aset" . $iAssetID . ".jpg");
    }

    // Display photo or upload from file
    $photoFile = "Images/Aset/thumbnails/Aset" . $iAssetID . ".jpg";
        if (file_exists($photoFile))
        {
            echo '<a target="_blank" href="Images/Aset/Aset' . $iAssetID . '.jpg">';
            echo '<img border="0" src="'.$photoFile.'"  Images/NoMail.gif" width="128"   ></a>';
            if ($bOkToEdit) {
                echo '
                    <form method="post"
                    action="AsetView.php?AssetID=' . $iAssetID . '">
                    <br>
                    <input type="submit" class="icTinyButton"
                    value="' . gettext("Hapus Foto") . '" name="DeletePhoto">
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
                    action="AsetView.php?AssetID=' . $iAssetID . '"
                    enctype="multipart/form-data">
                    <input class="icTinyButton" type="file" name="Photo" size="1">
                    <input type="submit" class="icTinyButton"
                    value="' . gettext("Upload Foto Aset") . '" name="UploadPhoto">
                    </form>';
            }
        }

    echo "<br>";

  

        ?>

</td>



    <td align="center">
      <table cellspacing="1" cellpadding="0" border="0" >

      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Nomor Registrasi Aset"); ?></td>
      <td class="TinyTextColumn"><?php echo $AssetID; ?></td>
      </tr>
	  <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Klasifikasi Aset"); ?></td>
      <td class="TinyTextColumn"><?php echo $AssetClass ; ?></td>
	  <td class="TinyTextColumn"><?php echo $majorclass ; ?></td>
	  <td class="TinyTextColumn"><?php echo $minorclass ; ?></td>
      </tr>

      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tanggal Pengadaan"); ?></td>
	  <?php
	  //	list($year , $month, $day ) = preg_split('[/.-]', $Tahun);
		//echo "Month: $month; Day: $day; Year: $year<br />\n"
		?>
      <td class="TinyTextColumn"><?php echo date2Ind($Tahun,2);//echo $day . "-" . $month . "-" . $year; ?></td>
      </tr>	  
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Merk"); ?></td>
      <td class="TinyTextColumn"><?php echo $Merk; ?></td>

      <td class="TinyLabelColumn"><?php echo gettext("Type / Part Number"); ?></td>
      <td class="TinyTextColumn"><?php echo $Type; ?></td>
      </tr>	  
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Detail Spesifikasi"); ?></td>
      <td class="TinyTextColumn"><?php echo $Spesification; ?></td>
      </tr>	
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Jumlah"); ?></td>
      <td class="TinyTextColumn"><?php echo $Quantity ; ?></td>
      <td class="TinyTextColumn"><?php echo $UnitOfMasure; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Nilai"); ?></td>
      <td class="TinyTextColumn"><?php  echo currency('Rp. ',$Value,'.',',00'); ?></td>
      </tr>	  
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Status"); ?></td>
      <td class="TinyTextColumn"><?php echo $StatusName; ?></td>
      </tr>
       <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tempat Ibadah"); ?></td>
      <td class="TinyTextColumn"><?php echo $NamaTI; ?></td>

      <td class="TinyLabelColumn"><?php echo gettext("Tempat/Lokasi"); ?></td>
      <td class="TinyTextColumn"><?php echo $StorageDesc; ?></td>
      </tr>
       <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Kode Rak"); ?></td>
      <td class="TinyTextColumn"><?php echo $Rack; ?></td>

      <td class="TinyLabelColumn"><?php echo gettext("Kode Bin"); ?></td>
      <td class="TinyTextColumn"><?php echo $Bin ; ?></td>
      </tr>

	  <tr>
      <td class="TinyLabelColumn" > <?php echo gettext("Keterangan "); ?></td>
      <td class="TinyTextColumn" ><?php echo $Description; ?></td>
      </tr>

        </td>

	  
      </table>
<br><br><br>
<p class="SmallText"><i>
  <?php echo gettext("Entered:"); ?> <?php echo FormatDate($DateEntered,true); ?> <?php echo gettext("by"); ?> <?php echo $EnteredBy . " " . $EnteredLastName; ?>
<?php

  if (strlen($DateLastEdited) > 0)
  {
    ?>
      <br>
      <?php echo gettext("Last edited:") . ' ' . FormatDate($DateLastEdited,true) . ' ' . gettext("by") . ' ' . $EditedBy . " " . $EditedLastName ?>
    </i></p>
    <?php
  }
  ?>

</p> 
 </td>
 
  </tr>  
  
  </table>
  
  </div>
  <br>

 
