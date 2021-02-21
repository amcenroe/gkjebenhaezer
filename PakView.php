<?php
/*******************************************************************************
 *
 *  filename    : PakView.php
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
$iPakID = FilterInput($_GET["PakID"],'int');

$iRemoveVO = FilterInput($_GET["RemoveVO"],'int');

$dSQL= "SELECT PakID FROM pakgkjbekti order by PakID";
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
  if ($pid == $iPakID)
  {
    $previous_id = $last_id;
    $capture_next = 1;
  }
  $last_id = $pid;
}

if (($previous_id > 0)) {
    $previous_link_text = "<a class=\"SmallText\" href=\"PakView.php?PakID=$previous_id\"><img border=0 src=\"Images/Icons/ico_prev.png\"  width=\"30\" height=\"30\"  > " . gettext("Sebelumnya") . "</a>";
}

if (($next_id > 0)) {
    $next_link_text = "<a class=\"SmallText\" href=\"PakView.php?PakID=$next_id\"><img border=0 src=\"Images/Icons/ico_next.png\"   width=\"30\" height=\"30\"   >" . gettext("Berikutnya") . "</a>";
}

// Get this PAK's data

$sSQL = "SELECT * FROM pakgkjbekti a 
         LEFT JOIN person_per b ON a.per_ID=b.per_ID 
		 LEFT JOIN family_fam c ON b.per_fam_ID=c.fam_ID
		 LEFT JOIN paktutor d ON a.TutorID=d.TutorID
		WHERE PakID = " . $iPakID;
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

// Set the page title and include HTML header

require "Include/Header.php";



gettext("Surat Masuk : $PakID / $per_FirstName / $fam_WorkPhone ");

$iTableSpacerWidth = 10;

$bOkToEdit = ($_SESSION['bEditRecords'] ||
        ($_SESSION['bEditSelf'] && $per_ID==$_SESSION['iUserID']) ||
        ($_SESSION['bEditSelf'] && $per_fam_ID==$_SESSION['iFamID'])
       );
if ($previous_link_text) {
  echo "$previous_link_text | ";
}

if ($_SESSION['bDeleteRecords']) { echo "<a class=\"SmallText\" href=\"SelectDelete.php?mode=pak&PakID=" . $PakID . "\"><img border=0 src=\"Images/Icons/ico_del.png\"   width=\"30\" height=\"30\"   > " . gettext("Hapus") . "</a> | " ; }

?>
<a href="PrintViewPak.php?PakID=<?php echo $PakID; ?>" class="SmallText" target="_blank"><img border=0 src="Images/Icons/ico_print.png"  width=30 height=30  >  <?php echo gettext("Cetak"); ?> </a> |
<a href="PakEditor.php?PakID=<?php echo $PakID; ?>" class="SmallText" ><img border=0 src="Images/Icons/ico_edit.png"  width=30 height=30  >  <?php echo gettext("Edit"); ?></a>
<?php

if ($next_link_text) {
  echo " | $next_link_text";
}
echo " | <a class=\"SmallText\" href=\"SelectList.php?mode=pak\"><img border=0 src=\"Images/Icons/ico_list.png\"  width=\"30\" height=\"30\"  >" .
gettext("Daftar Nilai PAK") . "</a> ";

?>

<br><br>
<table border="0" width="100%" cellspacing="0" cellpadding="4">
<tr>
<td width="20%" valign="top" align="center">


  <?php
  
echo "<div class=\"LightShadedBox\"> ";
    // Display photo or upload from file
    $photoFile = "Images/Person/thumbnails/" . $per_ID . ".jpg";
        if (file_exists($photoFile))
        {
            echo '<a target="_blank" href="Images/Person/' . $per_ID . '.jpg">';
            echo '<img border="1" src="'.$photoFile.'"  width="128" height="150"  ></a>';
         
        } else {
            // Some old / M$ browsers can't handle PNG's correctly.
            if ($bDefectiveBrowser)
                echo '<img border="0" src="Images/NoPhoto.gif" width="128" height="150"  ><br><br><br>';
            else
                echo '<img border="0" src="Images/NoPhoto.png" width="128" height="150"  ><br><br><br>';

          
        }




    echo "<br>";
	    echo "<br>";

    // Upload Berkas surat halaman 1
    if ( isset($_POST["UploadPhoto"]) && ($_SESSION['bAddRecords'] || $bOkToEdit) ) {
      if ($_FILES['Photo']['name'] == "") {
        $PhotoError = gettext("Tidak Ada Photo yang di upload.");
      } elseif ($_FILES['Photo']['type'] != "image/pjpeg" && $_FILES['Photo']['type'] != "image/jpeg") {
        $PhotoError = gettext("Hanya Format JPEG yang bisa di upload.");
      } else {
        // Create the thumbnail used by PakView

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
        imagejpeg($dstImage, "Images/Pak/thumbnails/" . $iPakID . ".jpg");
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
        imagejpeg($dstImage, "Images/Pak/" . $iPakID . ".jpg");
        imagedestroy($dstImage);
          imagedestroy($srcImage);




        //move_uploaded_file($_FILES['Photo']['tmp_name'], "Images/Pak/" . $iPakID . ".jpg");
      }
    } elseif (isset($_POST["DeletePhoto"]) && $_SESSION['bDeleteRecords']) {
      unlink("Images/Pak/" . $iPakID . ".jpg");
      unlink("Images/Pak/thumbnails/" . $iPakID . ".jpg");
    }

    // Display photo or upload from file
    $photoFile = "Images/Pak/thumbnails/" . $iPakID . ".jpg";
        if (file_exists($photoFile))
        {
            echo '<a target="_blank" href="Images/Pak/' . $iPakID . '.jpg">';
            echo '<img border="1" src="'.$photoFile.'"  Images/NoMail.gif" width="128" height="126"   ></a>';
            if ($bOkToEdit) {
                echo '
                    <form method="post"
                    action="PakView.php?PakID=' . $iPakID . '">
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
                    action="PakView.php?PakID=' . $iPakID . '"
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

  <b><?php echo gettext("Informasi Nilai PAK:  ")  ; ?></b>
  <b><?php echo gettext("$per_ID - $per_FirstName$Nama - $fam_Name - $fam_WorkPhone"); ?> </b>
  <div class="LightShadedBox">
  <table cellspacing="0" cellpadding="0" border="0" width="100%">
  
<tr>
    <td align="center">
      <table cellspacing="1" cellpadding="0" border="0" >

      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Nomor Induk"); ?></td>
      <td class="TinyTextColumn"><?php if ($per_ID == 0){echo "NW" . $PakID;} else {echo $per_ID;} ?> </td>
      </tr>
	  <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Nama Siswa"); ?></td>
      <td class="TinyTextColumn"><?php if ($per_ID == 0){echo $Nama;} else {echo $per_FirstName;} ?></td>
      </tr>

      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Nama OrangTua"); ?></td>
      <td class="TinyTextColumn"><?php echo $fam_Name; ?></td>
      </tr>	  
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Kelompok"); ?></td>
      <td class="TinyTextColumn"><?php echo $fam_WorkPhone; ?></td>
      </tr>	  
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Nomor Telepon"); ?></td>
      <td class="TinyTextColumn"><?php echo $NomorTelepon; ?></td>
      </tr>	  
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Alamat Sekolah"); ?></td>
      <td class="TinyTextColumn"><?php echo $AlamatSekolah; ?></td>
      </tr>	
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tahun Ajaran"); ?></td>
      <td class="TinyTextColumn"><?php echo gettext("20$TahunAjaran/20");echo $TahunAjaran+1 ; ?></td>
      </tr>	  
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Kelas"); ?></td>
      <td class="TinyTextColumn"><?php echo $Kelas; ?></td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Keterangan Kelas"); ?></td>
      <td class="TinyTextColumn"><?php echo $KetKelas; ?></td>
      </tr>	  
      <tr>
        <td class="TinyLabelColumn"><?php echo gettext("Semester:"); ?></td>
        <td class="TinyTextColumn">
        <?php
          switch ($Semester)
          {
          case 1:
            echo gettext("Semester Ganjil");
            break;
          case 2:
            echo gettext("Semester Genap");
            break;
		  case 3:
            echo gettext("Mid Semester Ganjil");
            break;
		  case 4:
            echo gettext("Mid Semester Genap");
            break;
          }
        ?>
        </td>
      </tr>
      <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tutor"); ?></td>
      <td class="TinyTextColumn"><?php echo $TutorName; ?>
	  </td>
      </tr>
       <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tanggal Penilaian 1 "); ?></td>
      <td class="TinyTextColumn"><?php echo $TglTest1; ?></td>

      <td class="TinyLabelColumn"><?php echo gettext("Nilai 1 (A)"); ?></td>
      <td class="TinyTextColumn"><?php echo $Nilai1; ?></td>
      </tr>
       <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tanggal Penilaian 2 "); ?></td>
      <td class="TinyTextColumn"><?php echo $TglTest2; ?></td>

      <td class="TinyLabelColumn"><?php echo gettext("Nilai 2 (B)"); ?></td>
      <td class="TinyTextColumn"><?php echo $Nilai2; ?></td>
      </tr>
       <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tanggal Penilaian 3 "); ?></td>
      <td class="TinyTextColumn"><?php echo $TglTest3; ?></td>

      <td class="TinyLabelColumn"><?php echo gettext("Nilai 3 (C)"); ?></td>
      <td class="TinyTextColumn"><?php echo $Nilai3; ?></td>
      </tr>
       <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Tanggal Penilaian Akhir "); ?></td>
	  <td class="TinyTextColumn"><?php echo $TglTest4; ?></td>
	  
	  

      <td class="TinyLabelColumn"><?php echo gettext("Nilai Akhir"); ?></td>
	  <?php $NilaiAkhir=($Nilai1*0.3)+($Nilai2*0.3)+($Nilai3*0.4); ?>
      <td class="TinyTextColumn"><?php echo $NilaiAkhir; ?></td>
      </tr>

	  <tr>
      <td class="TinyLabelColumn"><?php echo gettext("Keterangan "); ?></td>
      <td class="TinyTextColumn"><?php echo $Keterangan; ?></td>
      </tr>

        </td>

	  
      </table>
    </td>
 
  </tr>  
  
  </table>
  </div>
  <br>

 
