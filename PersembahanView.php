<?php
/*******************************************************************************
 *
 *  filename    : PersembahanView.php
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
$iPersembahan_ID = FilterInput($_GET["Persembahan_ID"],'int');

$iRemoveVO = FilterInput($_GET["RemoveVO"],'int');

$dSQL= "SELECT Persembahan_ID FROM Persembahangkjbekti order by Persembahan_ID";
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
    $previous_link_text = "<a class=\"SmallText\" href=\"PersembahanView.php?Persembahan_ID=$previous_id\"><img border=0 src=\"Images/Icons/ico_prev.png\"  width=\"30\" height=\"30\"  > " . gettext("Sebelumnya") . "</a>";
}

if (($next_id > 0)) {
    $next_link_text = "<a class=\"SmallText\" href=\"PersembahanView.php?Persembahan_ID=$next_id\"><img border=0 src=\"Images/Icons/ico_next.png\"   width=\"30\" height=\"30\"   >" . gettext("Berikutnya") . "</a>";
}

// Get this Persembahan's data


		
 		$sSQL = "SELECT a.*,b.*,
		c.per_FirstName as NamaPengInput, d.per_FirstName as NamaPengEdit, 
		a.EnteredBy as DiInput, a.EditedBy as DiEdit,
		a.DateEntered as TanggalInput, a.DateLastEdited as TanggalEdit 
		FROM Persembahangkjbekti  a
		LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
		LEFT JOIN person_per c ON a.EnteredBy = c.per_ID 
		LEFT JOIN person_per d ON a.EditedBy = d.per_ID 
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
if ($_SESSION['bDeleteRecords']) { echo "<a class=\"SmallText\" href=\"SelectDelete.php?mode=Persembahan&Persembahan_ID=" . $Persembahan_ID . "\"><img border=0 src=\"Images/Icons/ico_del.png\"   width=\"30\" height=\"30\"   > " . gettext("Hapus") . "</a> | " ; }
?>
<a href="PrintViewPersembahan.php?Persembahan_ID=<?php echo $Persembahan_ID; ?>" class="SmallText" target="_blank"><img border=0 src="Images/Icons/ico_print.png"  width=30 height=30  >  <?php echo gettext("Cetak"); ?> </a> |
<a href="PersembahanEditor.php?Persembahan_ID=<?php echo $Persembahan_ID; ?>&ReffTanggal=<?php echo $Tanggal; ?>&ReffKodeTI=<?php echo $KodeTI; ?>&ReffPukul=<?php echo $Pukul; ?> 
<class="SmallText" ><img border=0 src="Images/Icons/ico_edit.png"  width=30 height=30  >  <?php echo gettext("Edit"); ?></a>
<?php

if ($next_link_text) {
  echo " | $next_link_text";
}
echo " | <a class=\"SmallText\" href=\"SelectList.php?mode=Persembahan\"><img border=0 src=\"Images/Icons/ico_list.png\"  width=\"30\" height=\"30\"  >" .
gettext("Daftar Persembahan Umum") . "</a> ";

?>
</td>
</tr>
</table>
<table border="0" width="80%" cellspacing="0" cellpadding="4" align="center">
<tr>

<td width="100%" valign="top" align="left">


	  <?php
	  	//list($year, $month, $day, $hour, $min, $sec) = preg_split('/[: -]/', $post_timestamp);
		//list($year , $month, $day ) = split('[/.-]', $Tahun);
		list($year , $month, $day ) = preg_split('[/.-]', $Tahun);
		//echo "Month: $month; Day: $day; Year: $year<br />\n"
		?>


  <b><?php echo gettext("Informasi Detail Persembahan Umum :  ")  ; ?></b>
  <b><?php echo gettext(" $NamaTI / $Tanggal / $Pukul "); ?> </b><br>
  
<tr>
    <td align="center">
			<table cellpadding="0" valign="top" >
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Data Penerimaan Persembahan"); ?></b></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal :"); ?></td>
				<td class="TinyTextColumn"><?php echo date2Ind($Tanggal,2); ?></td>
			
				<td class="TinyLabelColumn"><?php echo gettext("Pukul :"); ?></td>
				<td class="TinyTextColumn"><?php echo $Pukul ?></td>
				
			</tr> 
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Tempat Ibadah:"); ?></td>
				<td class="TinyTextColumn">	<?php echo $NamaTI ; ?> </td>
				<td class="TinyLabelColumn"><?php echo gettext("Liturgi:"); ?></td>
				<td class="TinyTextColumn">
					<?php if ($Liturgi==0) {echo gettext("Liturgi Biasa"); }else{echo gettext("Liturgi Khusus"); } ?></option>
				</td>			
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

				<td class="TinyLabelColumn"><?php echo gettext("Bacaan 3"); ?></td>
				<td class="TinyTextColumn"><?php echo $Bacaan3 ?></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Bacaan 2"); ?></td>
				<td class="TinyTextColumn"><?php echo $Bacaan2 ?></td>

				<td class="TinyLabelColumn"><?php echo gettext("Bacaan 4"); ?></td>
				<td class="TinyTextColumn"><?php echo $Bacaan4 ?></td>
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
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 9"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian9 ?></td>

			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 2"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian2 ?></td>

				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 10"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian10 ?></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 3"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian3 ?></td>

				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 11"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian11 ?></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 4"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian4 ?></font></td>

				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 12"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian12 ?></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 5"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian5 ?></td>

				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 13"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian13 ?></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 6"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian6 ?></td>

				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 14"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian14 ?></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 7"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian7 ?></td>

				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 15"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian15 ?></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 8"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian8 ?></td>
				
				<td class="TinyLabelColumn"><?php echo gettext("Nyanyian 16"); ?></td>
				<td class="TinyTextColumn"><?php echo $Nyanyian16 ?></td>			
			</tr>
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Pelayanan Khusus"); ?></b></td>
			</tr>
			<tr>
			
				<td class="TinyLabelColumn"><?php echo gettext("Baptis Dewasa"); ?></td>
				<td class="TinyTextColumnRight"><?php echo $BaptisDewasa ?></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Baptis Anak"); ?></td>
				<td class="TinyTextColumnRight"><?php echo $BaptisAnak ?></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Sidi"); ?></td>
				<td class="TinyTextColumnRight"><?php echo $Sidi ?></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Pengakuan Dosa"); ?></td>
				<td class="TinyTextColumnRight"><?php echo $PengakuanDosa ?></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Penerimaan Warga"); ?></td>
				<td class="TinyTextColumnRight"><?php echo $PenerimaanWarga ?></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Pemberkatan Perkawinan"); ?></td>
				<td colspan="3" class="TinyTextColumn"><i><?php echo $Pemberkatan1 . "</i> dengan <i>" . $Pemberkatan2 ;?></i></td>

			</tr>				
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Persembahan"); ?></b></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Kebaktian Dewasa"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$KebDewasa,'.',',00');  ?></td>
			</tr>	
			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Kebaktian Pemuda"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$KebPemuda,'.',',00');  ?></td>
				<td>
				<?
				$sSQL = "SELECT Persembahan_ID,Persembahan FROM PersembahanPemudagkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul= '" . $Pukul . "'";
				$rsPersembahanPemuda = RunQuery($sSQL);
				$num_rows = mysql_num_rows($rsPersembahanPemuda);	
				while ($hasilGD=mysql_fetch_array($rsPersembahanPemuda))
				{
						extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				}
									
				if ( $num_rows == 0 ) 
					{
						echo "<a href=\"PersembahanAnakEditor.php?Kategori=Pemuda&Tanggal=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . " \">
						<font color=\"red\"><blink>Ada kesalahan</blink></font></td><td><font color=\"red\">Belum Ada Data</td></font></a><td>";
					}
				elseif ( $KebPemuda == $lPersembahan )
					{	
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Pemuda \" TARGET=\"_BLANK\"> Lihat Detail </a></td><td>";
					} 
				else
					{
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Pemuda \" TARGET=\"_BLANK\">
						<font color=\"red\"><blink>Ada kesalahan</blink></font></td><td><font color=\"red\">Terinput:Rp." . $lPersembahan . " dilembar detail</td></font></a><td>";
					}


				?> 
				</td>
				
			</tr>

			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Kebaktian Remaja"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$KebRemaja,'.',',00');  ?></td>
				<td>
				<?
				$sSQL = "SELECT Persembahan_ID,Persembahan FROM PersembahanRemajagkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul= '" . $Pukul . "'";
				$rsPersembahanRemaja = RunQuery($sSQL);
				$num_rows = mysql_num_rows($rsPersembahanRemaja);	
				while ($hasilGD=mysql_fetch_array($rsPersembahanRemaja))
				{
						extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				}
									
				if ( $num_rows == 0 ) 
					{
						echo "<a href=\"PersembahanAnakEditor.php?Kategori=Remaja&Tanggal=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . " \">
						<font color=\"red\"><blink>Ada kesalahan</blink></font></td><td><font color=\"red\">Belum Ada Data</td></font></a><td>";
					}
				elseif ( $KebRemaja == $lPersembahan )
					{	
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Remaja \" TARGET=\"_BLANK\"> Lihat Detail </a></td><td>";
					} 
				else
					{
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Remaja \" TARGET=\"_BLANK\">
						<font color=\"red\"><blink>Ada kesalahan</blink></font></td><td><font color=\"red\">Terinput:Rp." . $lPersembahan . " dilembar detail</td></font></a><td>";
					}


				?> 
				</td>
				
			</tr>
			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Kebaktian Pra Remaja"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$KebPraRemaja,'.',',00');  ?></td>
				<td>
				<?
				$sSQL = "SELECT Persembahan_ID,Persembahan FROM PersembahanPraRemajagkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul= '" . $Pukul . "'";
				$rsPersembahanPraRemaja = RunQuery($sSQL);
				$num_rows = mysql_num_rows($rsPersembahanPraRemaja);	
				while ($hasilGD=mysql_fetch_array($rsPersembahanPraRemaja))
				{
						extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				}
									
				if ( $num_rows == 0 ) 
					{
						echo "<a href=\"PersembahanAnakEditor.php?Kategori=PraRemaja&Tanggal=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . " \">
						<font color=\"red\"><blink>Ada kesalahan</blink></font></td><td><font color=\"red\">Belum Ada Data</td></font></a><td>";
					}
				elseif ( $KebPraRemaja == $lPersembahan )
					{	
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=PraRemaja \" TARGET=\"_BLANK\"> Lihat Detail </a></td><td>";
					} 
				else
					{
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=PraRemaja \" TARGET=\"_BLANK\">
						<font color=\"red\"><blink>Ada kesalahan</blink></font></td><td><font color=\"red\">Terinput:Rp." . $lPersembahan . " dilembar detail</td></font></a><td>";
					}


				?> 
				</td>
				
			</tr>			
			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Kebaktian Anak"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$KebAnak,'.',',00');  ?></td>
				
				<td>
				<?
				$sSQL = "SELECT Persembahan_ID, Persembahan FROM PersembahanAnakgkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul='" . $Pukul . "'";
				$rsPersembahanAnak = RunQuery($sSQL);
				$num_rows = mysql_num_rows($rsPersembahanAnak);	
				while ($hasilGD=mysql_fetch_array($rsPersembahanAnak))
				{
						extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				}
									
				if ( $num_rows == 0 ) 
					{
						echo "<a href=\"PersembahanAnakEditor.php?Kategori=Anak&Tanggal=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . " \">
						<font color=\"red\"><blink>Ada kesalahan</blink></font></td><td><font color=\"red\">Belum Ada Data</td></font></a><td>";
					}
				elseif ( $KebAnak == $lPersembahan )
					{	
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Anak \" TARGET=\"_BLANK\"> Lihat Detail </a></td><td>";
					} 
				else
					{
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Anak \" TARGET=\"_BLANK\">
						<font color=\"red\"><blink>Ada kesalahan<blink></font></td><td><font color=\"red\">Terinput:Rp." . $lPersembahan . " dilembar detail</td></font></a><td>";
					}

				?> 

				</td>
				

			</tr>				
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Kebaktian Anak JTMY"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$KebAnakJTMY,'.',',00');  ?></td>
				<td>
				<?

				$sSQL = "SELECT Persembahan_ID,Persembahan  FROM PersembahanAnakgkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='10' ";
				$rsPersembahanAnak = RunQuery($sSQL);			
				$num_rows = mysql_num_rows($rsPersembahanAnak);	
				while ($hasilGD=mysql_fetch_array($rsPersembahanAnak))
				{
						extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				}
									
				if ( $num_rows == 0 ) 
					{
						echo "<a href=\"PersembahanAnakEditor.php?Kategori=Anak&Tanggal=" . $Tanggal . "&KodeTI=10&Pukul=17.00 WIB \">
						<font color=\"red\"><blink>Ada kesalahan</blink></font></td><td><font color=\"red\">Belum Ada Data</td></font></a><td>";
					}
				elseif ( $KebAnakJTMY == $lPersembahan )
					{	
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Anak \" TARGET=\"_BLANK\"> Lihat Detail </a></td><td>";
					} 
				else
					{
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Anak \" TARGET=\"_BLANK\">
						<font color=\"red\"><blink>Ada kesalahan</blink></font></td><td><font color=\"red\">Terinput:Rp." . $lPersembahan . " dilembar detail</td></font></a><td>";
					}

				?> 

				</td>
			</tr>	

			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Syukur"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Syukur,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $SyukurAmplop ?> amplop</font></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Bulanan"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Bulanan,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $BulananAmplop ?> amplop</td>
				<td >
				<?
				
				//$sSQL = "SELECT * FROM PersembahanBulanan  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul='" . $Pukul . "'";
				$sSQL = "SELECT SUM(Bulanan) as SubTotalBulanan, SUM(Syukur) as SubTotalSyukur, SUM(ULK) as SubTotalULK  FROM PersembahanBulanan WHERE Tanggal = '".$Tanggal."' AND KodeTI=".$KodeTI." AND Pukul='".$Pukul."'" ;
				//echo $sSQL;
				$rsPersembahanBulanan = RunQuery($sSQL);		
				$num_rows = mysql_num_rows($rsPersembahanBulanan);	
				while ($hasilGD=mysql_fetch_array($rsPersembahanBulanan))
				{
						extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[SubTotalBulanan]+$hasilGD[SubTotalSyukur]+$hasilGD[SubTotalULK] ;
						//echo $lPersembahan;
						
				}
									
				if ( $num_rows == 0 ) 
					{
						echo "<a href=\"PersembahanBulananEditor.php?TGL=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . " \">
						<font color=\"red\"><blink>Ada kesalahan - Belum Ada Data</blink></font></td>";
					}
				elseif ( $Bulanan == $lPersembahan )
					{	
						echo "<a href=\"PrintViewPersembahanBulanan.php?TGL=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . "&NamaTI=".$NamaTI."  \" TARGET=\"_BLANK\"> Lihat Detail </a></td><td>";
					} 
				else
					{
						echo "<a href=\"PrintViewPersembahanBulanan.php?TGL=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . "&NamaTI=".$NamaTI."\"  TARGET=\"_BLANK\">

						<font color=\"red\">Ada kesalahan</font></a><font color=\"red\"><blink>!!! <br>Terinput:Rp." . $lPersembahan . " dilembar detail</blink></td></font><td>";
					}

				?> 				
				</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Khusus"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Khusus,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $KhususAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Syukur Baptis/Sidi/Perkawinan"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$SyukurBaptis,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $SyukurBaptisAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Khusus Perjamuan"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$KhususPerjamuan,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $KhususPerjamuanAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Masa Raya Paskah"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Marapas,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $MarapasAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Masa Raya Pentakosta"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Marapen,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $MarapenAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Masa Raya Unduh2"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Unduh,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $UnduhAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Amplop Pink"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Pink,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $PinkAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Lain-Lain"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$LainLain,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $LainLainAmplop ?> amplop</td>
			</tr>	
			<tr>
			<td class="TinyLabelColumn"><?php echo gettext("Total Persembahan"); ?></td>
			<td class="TinyTextColumnRight" >
			<b>
<?php $TotalPersembahan = $KebDewasa+$KebAnak+$KebAnakJTMY+$KebRemaja+$KebPraRemaja+$KebPemuda+$Syukur+$SyukurBaptis+$Bulanan+$Khusus+$KhususPerjamuan+$Marapas+$sMarapen+$Unduh+$Pink+$LainLain;
			echo currency('Rp. ',$TotalPersembahan,'.',',00'); 
			?>
			</b>
			</td>
			</tr>
			
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Jemaat yang Hadir"); ?></b></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Laki laki"); ?></td>
				<td class="TinyTextColumn"><?php echo $Pria ?></td>

				<td class="TinyLabelColumn"><?php echo gettext("Perempuan"); ?></td>
				<td class="TinyTextColumn"><?php echo $Wanita ?></td>
			</tr>
				<tr>
			<td class="TinyLabelColumn"><?php echo gettext("Total"); ?></td>
			<td  class="right" >
			<b><?php $TotalJemaat = $Pria + $Wanita;
			echo $TotalJemaat ;
			?>
			</b> jemaat
			</td>
			</tr>		
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Majelis Yang Hadir"); ?></b></td>
			</tr>
			
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("1."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis1 ?> </td>			
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("8."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis8 ?> </td>	
			</tr>
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("2."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis2 ?> </td>	
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("9."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis9 ?> </td>	
			</tr>
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("3."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis3 ?> </td>	
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("10."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis10 ?> </td>	
			</tr>			
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("4."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis4 ?> </td>	
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("11."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis11 ?> </td>				</tr>
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("5."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis5 ?> </td>	
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("12."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis12 ?> </td>	
			</tr>			
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("6."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis6 ?> </td>	
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("13."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis13 ?> </td>	
			</tr>
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("7."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis7 ?> </td>	
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("14."); ?></td>
				<td class="TinyTextColumn"> <?php echo $Majelis14 ?> </td>	
			</tr>	
	
	
	</table>
  
    </td>

	  
     
<br><br><br>
</tr>
<table>
<tr>
<td>
<p class="SmallText"><i>
  <?php echo gettext("Entered:"); ?> <?php echo FormatDate($TanggalInput,true); ?> <?php echo gettext("by"); ?> <?php echo $DiInput . " " . $NamaPengInput; ?>
<?php

  if (strlen($DateLastEdited) > 0)
  {
    ?>
      <br>
      <?php echo gettext("Last edited:") . ' ' . FormatDate($TanggalEdit,true) . ' ' . gettext("by") . ' ' . $DiEdit . " " . $NamaPengEdit ?>
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

 
