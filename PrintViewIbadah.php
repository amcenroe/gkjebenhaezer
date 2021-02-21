<?php
/*******************************************************************************
 *
 *  filename    : PrintViewIbadah.php
 *  last change : 2011-01-29
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2003 Phillip Hullquist, Deane Barker, Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2011 Erwin Pratama for GKJ Bekasi Timur (www.gljbekasitimur.org)
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";

// Get the Gol Darah ID from the querystring
$iStatus = FilterInput($_GET["Status"]);
$iTahun = $_GET["Tahun"];
if ($iTahun == ""){$iTahun =date("Y");}
$Judul = "Laporan - Daftar Peribadahan"; 
require "Include/Header-Report.php";

if (strlen($iTGL>0))
{
$iTGL = FilterInput($_GET["TGL"]);
$minggukemaren = date("Y-m-d", strtotime('last Sunday', strtotime($iTGL)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', strtotime($iTGL)));
}else
{
$hariini = strtotime(date("Y-m-d"));
$iTGL = date("Y-m-d", strtotime('last Sunday', $hariini));
$mingguterakhir = date("Y-m-d", strtotime('last Sunday', $hariini));
$minggukemaren = date("Y-m-d", strtotime('-1 week', strtotime($mingguterakhir)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', $hariini));
//echo date("Y-m-d", $hariini);
//echo "<br>";
//echo $mingguterakhir;
//echo "<br>";
//echo $minggukemaren;
//echo "<br>";
//echo $minggudepan;

}

?>


<style type="text/css" media="screen"><!--
.gallerycontainer{
position: relative;
/*Add a height attribute and set to largest image's height to prevent overlaying*/
}

.thumbnail img{
border: 1px solid white;
margin: 0 5px 5px 0;
}

.thumbnail:hover{
background-color: transparent;
}

.thumbnail:hover img{
border: 1px solid blue;
}

.thumbnail span{ /*CSS for enlarged image*/
position: absolute;
background-color: lightyellow;
padding: 5px;
left: -1000px;
border: 1px dashed gray;
visibility: hidden;
color: black;
text-decoration: none;
}

.thumbnail span img{ /*CSS for enlarged image*/
border-width: 0;
padding: 2px;
}

.thumbnail:hover span{ /*CSS for enlarged image*/
visibility: visible;
top: 0;
left: 50px; /*position where enlarged image should offset horizontally */
z-index: 50;
}


--></style>

			<table border="0"  width="900" cellspacing="1" cellpadding="1" >
			<tr>
			<td colspans=4 ALIGN=left><a href="PrintViewIbadah.php?Tahun=<?=$iTahun-1?>"> << </a></td>
			<td></td><td></td><td></td><td></td><td></td>
			<td colspans=4 ALIGN=right><a href="PrintViewIbadah.php?Tahun=<?=$iTahun+1?>"> >> </a></td>
			</tr>
			<tr>
			<td ALIGN=center><b>No</b></td>
			<td ALIGN=center><b>Tanggal</b></td>
			<td ALIGN=center><b>Tempat Ibadah </b></td>
			<td ALIGN=center><b>Waktu </b></td>
			<td ALIGN=center><b>Pengkotbah</b></td>
			<td ALIGN=center><b>Jemaat</b></td>
			<td ALIGN=center><b>Total Persembahan</b></td>


			</tr>
			<?php
	$stat = "KebDewasa+KebAnak+KebAnakJTMY+KebRemaja+Syukur+Bulanan+Khusus+
			SyukurBaptis+KhususPerjamuan+Marapas+Marapen+Unduh+Maranatal+Pink+ 
			LainLain+LainLainAmplop";

	$sSQL = "SELECT Persembahan_ID, a.Tanggal, a.KodeTI , b.NamaTI as TempatIbadah, a.Pukul , Pengkotbah,  
				($stat) as total , (Pria+Wanita) as Jemaat 
				FROM Persembahangkjbekti a 
				LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
				WHERE a.KodeTI > 0 AND YEAR(Tanggal) =$iTahun 
				ORDER BY a.Tanggal, a.KodeTI, Pukul
					";
				 	
	//echo $sSQL;		
			$perintah = mysql_query($sSQL);
				$i = 0;
					while ($hasilGD=mysql_fetch_array($perintah))
						{
						$i++;
						extract($hasilGD);
						//Alternate the row color
					   $sRowClass = AlternateRowStyle($sRowClass);

				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td><a href="PersembahanView.php?Persembahan_ID=<?=$hasilGD[Persembahan_ID]?>" target=_blank> <? echo $i ?></a></td>
				<td><?=date2Ind($hasilGD[Tanggal],1)?></td>
				<td><?=$hasilGD[TempatIbadah]?></td>
				<td> <?=$hasilGD[Pukul]?> </td>
				<td> <?=$hasilGD[Pengkotbah]?> </td>

				<?
				if ($hasilGD[Jemaat]==0){$fontmerah="FONT COLOR=RED";}else{$fontmerah="FONT COLOR=BLACK";}
				?>
				<td align=right><FONT <?=$fontmerah ?> > <?=currency(' ',$hasilGD[Jemaat],'.',',00')?></FONT></td>				
				<?
				if ($hasilGD[total]==0){$fontmerah="FONT COLOR=RED";}else{$fontmerah="FONT COLOR=BLACK";}
				?>
				<td align=right><FONT <?=$fontmerah ?> > <?=currency(' ',$hasilGD[total],'.',',00')?></FONT></td>



				<td></td>
				</tr>
				<?}?>
			</table>


</div>
