<?php
/*******************************************************************************
 *
 *  filename    : PersembahanAnakView.php
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

$Kategori = $_GET["Kategori"];
$iKategori = $_GET["Kategori"];

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
$iPersembahan_ID = FilterInput($_GET["Persembahan_ID"],'int');

$iRemoveVO = FilterInput($_GET["RemoveVO"],'int');

$dSQL= "SELECT Persembahan_ID FROM Persembahan" . $Kategori . "gkjbekti order by Persembahan_ID";
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
  if ($pid == $iPersembahan_ID)
  {
    $previous_id = $last_id;
    $capture_next = 1;
  }
  $last_id = $pid;
}

if (($previous_id > 0)) {
    $previous_link_text = "<a class=\"SmallText\" href=\"PersembahanAnakView.php?Persembahan_ID=$previous_id\"><img border=0 src=\"Images/Icons/ico_prev.png\"  width=\"30\" height=\"30\"  > " . gettext("Sebelumnya") . "</a>";
}

if (($next_id > 0)) {
    $next_link_text = "<a class=\"SmallText\" href=\"PersembahanAnakView.php?Persembahan_ID=$next_id\"><img border=0 src=\"Images/Icons/ico_next.png\"   width=\"30\" height=\"30\"   >" . gettext("Berikutnya") . "</a>";
}

// Get this Persembahan's data


		
 		$sSQL = "SELECT *, Pria as APria, Wanita as AWanita , Majelis1 as AMajelis1, Majelis2 as AMajelis2
		FROM Persembahan" . $Kategori . "gkjbekti  a
		LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
		WHERE Persembahan_ID = " . $iPersembahan_ID;
		
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));


// Set the page title and include HTML header

require "Include/Header.php";



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
if ($_SESSION['bDeleteRecords']) { echo "<a class=\"SmallText\" href=\"SelectDelete.php?mode=Persembahan" . $Kategori . "&Persembahan_ID=" . $Persembahan_ID . "\"><img border=0 src=\"Images/Icons/ico_del.png\"   width=\"30\" height=\"30\"   > " . gettext("Hapus") . "</a> | " ; }
?>
<a href="PrintViewPersembahanAnak.php?Persembahan_ID=<?php echo $Persembahan_ID; ?>" class="SmallText" target="_blank"><img border=0 src="Images/Icons/ico_print.png"  width=30 height=30  >  <?php echo gettext("Cetak"); ?> </a> |
<a href="PersembahanAnakEditor.php?Persembahan_ID=<?php echo $Persembahan_ID; ?>&Kategori=<?=$Kategori?>&ReffTanggal=<?php echo $Tanggal; ?>&ReffKodeTI=<?php echo $KodeTI; ?>&ReffPukul=<?php echo $Pukul; ?> 
<class="SmallText" ><img border=0 src="Images/Icons/ico_edit.png"  width=30 height=30  >  <?php echo gettext("Edit"); ?></a>
<?php

if ($next_link_text) {
  echo " | $next_link_text";
}
echo " | <a class=\"SmallText\" href=\"SelectList.php?mode=PersembahanAnak&Kategori=" . $Kategori . "\"><img border=0 src=\"Images/Icons/ico_list.png\"  width=\"30\" height=\"30\"  >" .
gettext("Daftar Persembahan " . $Kategori ) . "</a> ";

?>
</td>
</tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="4">
<tr>

<td width="100%" valign="top" align="left">


	  <?php
	  	list($year , $month, $day ) = preg_split('[/.-]', $Tahun);
		//echo "Month: $month; Day: $day; Year: $year<br />\n"
		?>


  <b><?php echo gettext("Informasi Detail Persembahan " . $Kategori . " :  ")  ; ?></b>
  <b><?php echo gettext(" $NamaTI / $Tanggal / $Pukul "); ?> </b><br>
  
<tr>
    <td align="center">
			<table cellpadding="0" valign="top" >
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Data Penerimaan Persembahan " . $Kategori); ?></b></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal :"); ?></td>
				<td class="TinyTextColumn"><?php echo $Tanggal ?></td>
			
				<td class="TinyLabelColumn"><?php echo gettext("Pukul :"); ?></td>
				<td class="TinyTextColumn"><?php echo $Pukul ?></td>
				
			</tr> 
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Tempat Ibadah:"); ?></td>
				<td class="TinyTextColumn">	<?php echo $NamaTI ; ?> </td>
			</tr>	
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Pengkotbah"); ?></b></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Pengkotbah"); ?></td>
				<td colspan="4" class="TinyTextColumn"><?php echo $Pengkotbah ?></td>
			</tr>
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Bacaan Alkitab"); ?></b></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Bacaan 1"); ?></td>
				<td class="TinyTextColumn"><?php echo $Bacaan1 ?></td>
				<td class="TinyLabelColumn"><?php echo gettext("Bacaan 2"); ?></td>
				<td class="TinyTextColumn"><?php echo $Bacaan2 ?></td>
			</tr>			
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Nas / Tema"); ?></b></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nas / Tema"); ?></td>
				<td colspan="4" class="TinyTextColumn"><?php echo $Nas ?></td>
			</tr>
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Nyanyian"); ?></b></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 1"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian1 ?></font></td>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 6"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian6 ?></td>

			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 2"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian2 ?></td>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 7"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian7 ?></td>

			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 3"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian3 ?></td>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 8"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian8 ?></td>

			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 4"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian4 ?></font></td>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 9"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian9 ?></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 5"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian5 ?></td>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 10"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian10 ?></td>
			</tr>
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Persembahan"); ?></b></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Persembahan "); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Persembahan,'.',',00');  ?></td>
				
				<td>
				<?
				$sSQL = "SELECT * FROM Persembahangkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul='" . $Pukul . "'";
				$rsPersembahan = RunQuery($sSQL);
		  
				//echo $sSQL;
								while ($hasilGD=mysql_fetch_array($rsPersembahan))
				{
				$i++;
									extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Keb.$Kategori] ;
						
				//		echo $Persembahan;
				//		echo "<br>";
				//		echo $lPersembahan;
						
				if ( $Persembahan == $lPersembahan ){	
				echo "<a href=\"PersembahanView.php?Persembahan_ID=" . $lPersembahan_ID . " \"> View Detail </a></td><td>";
				} else
				{
				echo "<a href=\"PersembahanView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Remaja \" TARGET=\"_BLANK\">
				<font color=\"red\">Ada kesalahan</font></td><td><font color=\"red\">Terinput:Rp." . $lPersembahan . " dilembar utama</td></font></a><td>";

				}
				}
				?> 

				</td>
				

			</tr>				
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Jemaat yang Hadir"); ?></b></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Laki laki"); ?></td>
				<td class="TinyTextColumn"><?php echo $APria ?></td>

				<td class="TinyLabelColumn"><?php echo gettext("Perempuan"); ?></td>
				<td class="TinyTextColumn"><?php echo $AWanita ?></td>
			</tr>
				<tr>
			<td class="TinyLabelColumn"><?php echo gettext("Total"); ?></td>
			<td  class="right" >
			<b><?php $TotalJemaat = $APria + $AWanita;
			echo $TotalJemaat ;
			?>
			</b> jemaat
			</td>
			</tr>		
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Majelis Pendamping :"); ?></b></td>
			</tr>
			
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("1."); ?></td>
				<td class="TinyTextColumn"> <?php echo $AMajelis1 ?> </td>			
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("2."); ?></td>
				<td class="TinyTextColumn"> <?php echo $AMajelis2 ?> </td>	
			</tr>
	
	</table>
  
    </td>

	  
     
<br><br><br>
</tr>
<table>
<tr>
<td>
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

 
