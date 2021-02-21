<?php
/*******************************************************************************
 *
 *  filename    : TataIbadahMinggu5.php
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
 *  Sistem Informasi GKJ Bekasi Timur is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

	if (!isset($_SESSION['iUserID']))
	{
	require "Include/ConfigWeb.php";

	}else
	{
//Include the function library
require "Include/Config.php";
require "Include/Functions.php";
}

// Get the person ID from the querystring
$iTGL = FilterInput($_GET["TGL"],'date');
$iBHS = FilterInput($_GET["BHS"],'string');



// Get this LiturgiData
$sSQL = "select * from LiturgiGKJBekti 
		 WHERE Tanggal = '" . $iTGL . "' AND Bahasa  = '" .$iBHS ."'" ;

$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

//Set the page title
				$TGL=$Tanggal;
				$MGG=getWeeks("$Tanggal","sunday");
				$TGLIND=date2Ind($Tanggal,2);
				 
				
$sPageTitle = gettext("Tata Ibadah Minggu Kelima $TGLIND");

?>
<table  border="0"  align=center width="600" cellspacing=0 cellpadding=0>
<tr><td valign=top  width="100%" > 
<title><? echo $sPageTitle; ?></title>


<div align="center" style="text-align:center;">
	<b><span style="font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	TATA IBADAH GKJ BEKASI TIMUR</span></b></div>
<div align="center" style="margin-bottom:2.0pt;text-align:center;">
	<b><span style="font-size:11.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	MINGGU KELIMA, <?php echo $Keterangan; ?>, <?php echo date2Ind($Tanggal,2); ?></span></b></div>
<div align="center" style="text-align:center;">
	<b><span style="font-size:11.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	NUANSA IBADAH : <i>Syukur </i></span></b><b><i><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	atas kerukunan dan kasih di tengah persekutuan jemaat Tuhan</span></i></b></div>
<div align="center" style="text-align:center;">
	<b></b></div><br>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
background:#D9D9D9">
	<div style="margin-bottom:3.0pt;background:#D9D9D9;
border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
		PRA-IBADAH</span></b></div>
</div>
<div style="margin-left:17.85pt;text-indent:-17.85pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	1. Saat Teduh dan Doa Persiapan Ibadah </span></div>
<div>
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	2. Pembacaan Intisari Warta Gereja  <i>(oleh Pewarta) (jemaat duduk)</i></span></div>
<div>
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	3. Penyalaan Lilin Ibadah <i>(oleh Pewarta)</i> </span></div>
<div style="margin-left:17.85pt;text-align:justify;text-indent:
-17.85pt;">
	<span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">4. Lonceng dibunyikan, <b><i>jemaat berdiri,</i></b> dan menyanyikan <b>
Nyanyian Pembukaan&ndash;</b></span><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;">
<?php echo $Nyanyian1; ?></span></b></div>
<div style="margin-left:9.7pt;text-align:justify;text-indent:
-9.7pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	5.  Prosesi (Pemimpin Ibadah dan anggota Majelis masuk ke ruang ibadah) </span></div>
<div>
	</div><br>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
background:#D9D9D9">
	<div style="background:#D9D9D9;border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
I.  LITURGI PEMBUKA</span></b></div>
</div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
margin-left:17.85pt;margin-right:0cm">
	<div style="margin-top:1.0pt;margin-right:0cm;margin-bottom:1.0pt;
margin-left:17.85pt;text-indent:-17.85pt;
border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
		1. PENGAKUAN DAN SALAM  </span></b><i><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">(oleh Pemimpin Ibadah<b>, </b>jemaat tetap berdiri)</span></i></div>
</div>
<div style="margin-left:42.55pt;text-indent:-14.75pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;">
	a.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Pengakuan</span></b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <i>
(umat memandang ke arah Pemimpin Ibadah)</i></span></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:63.8pt;text-align:justify;text-indent:-21.25pt;">
	<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">P :</span></b><span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Di dalam <b>syukur atas persekutuan jemaat yang senantiasa Tuhan pelihara dan bangun dalam kerukunan dan kasih,</b> 
marilah kita awali dan khususkan ibadah ini dengan pengakuan bahwa TUHAN, Sang Kepala Gereja, adalah sumber keselamatan kita. <b>Amin.</b></span></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:3.0pt;
margin-left:63.8pt;text-align:justify;text-indent:-21.25pt;">
	<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
U :</span></b><span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <i>
(Menyanyikan </i><b>KJ 478a<i> &ndash; &ldquo;Amin, amin, amin&rdquo;)</i></b></span></div>
<div style="margin-left:42.55pt;text-indent:-14.75pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;">
	b.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">Salam</span></b>
<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <i>
(umat memandang ke arah Pemimpin Ibadah)</i></span></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:63.8pt;text-align:justify;text-indent:-21.25pt;">
	<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
P :</span></b><span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Kasih karunia dan damai sejahtera dari Allah, Bapa kita, dan dari Yesus Kristus, Putera Tunggal-Nya, kiranya menyertai Saudara-saudara.</span></div>
<div style="margin-left:63.8pt;text-indent:-21.25pt;">
	<b><span style="font-size:
10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
U :</span></b><span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <i>
Dan menyertai Saudara juga.</i></span></div>
<div style="margin-left:36.0pt">
	<b></b></div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
margin-left:18.15pt;margin-right:0cm">
	<div style="margin-bottom:1.0pt;border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
2. NYANYIAN PUJIAN <i>(jemaat duduk)</i></span></b></div>
</div>
<div style="margin-left:42.5pt;text-indent:-14.45pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;">
	a.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Ajakan Beribadah</span></b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">  </span></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:3.0pt;
margin-left:56.75pt;text-align:justify;text-indent:-14.2pt;">
	<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
L :</span></b><span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Ibu, Bapak, dan Saudara sekalian, sungguh kita bersyukur atas karya Tuhan di tengah-tengah persekutuan kita di GKJ Bekasi Timur ini. 
Syukur karena kita terus dimampukan untuk memelihara iman dan mewartakan kasih. Kiranya seluruh ibadah ini menjadi wujud ungkapan syukur kita kepada Tuhan, 
Pemimpin Keluarga.</span></div>
<div style="margin-left:42.5pt;text-indent:-14.45pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;;">
	b.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b>
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	Nyanyian Pujian&ndash;</span></b><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;">
	<?php echo $Nyanyian2; ?></span></b></div>
<div style="margin-left:42.55pt;">
	<b></b></div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
margin-left:17.85pt;margin-right:0cm">
	<div style="margin-top:0cm;margin-right:0cm;margin-bottom:1.0pt;
margin-left:17.85pt;text-indent:-17.85pt;
border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
		3. PENGAKUAN DOSA DAN PENGAMPUNAN </span></b></div>
</div>
<div style="margin-left:42.5pt;text-indent:-14.45pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;">
	a.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Pembacaan Hukum Kasih&ndash;</span></b><b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;">
<?php echo $AyatPenuntunHK; ?></span></b><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> </span></b><i>
<span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
(oleh Liturgos)</span></i></div>
<div style="margin-left:42.5pt;text-indent:-14.45pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;;">
	b.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b>
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	Nyanyian Penyesalan/Pengakuan Dosa&ndash;</span></b><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;">
	<?php echo $Nyanyian3; ?></span></b></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:42.55pt;">
	<i><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;">
(dinyanyikan berbalasan, bait 1 bersama, bait 2 wanita, bait 3 pria, bait 4 bersama)</span></i></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;;">
	c.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b>
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	Berita Pengampunan Dosa</span></b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <i>
	(oleh Pemimpin Ibadah)</i> </span></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:56.75pt;text-align:justify;text-indent:-14.2pt;">
	<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
P :</span></b><span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Sebagai pelayan Tuhan, perkenankan saya menyampaikan bahwa bagi setiap orang yang dengan rendah dan tulus hati mengakui dosanya, 
Allah yang penuh kasih dan rahmat mengaruniakan pengampunan dosa, sebagaimana nyata melalui firman-Nya dalam <b><?php echo $AyatPenuntunBA; ?></b>, <b><i>
&ldquo; ... Ayat Penuntun Berita Anugerah ... &rdquo;
</i></b> &ndash; di dalam nama Bapa, Anak, dan Roh Kudus. <b>Amin.</b></span></div>
<div style="margin-left:2.0cm;text-align:justify;text-indent:
-14.15pt;">
	<b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	U :</span></b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <i>
	Syukur kepada Allah.</i></span></div>
<div style="margin-left:18.0pt">
	<b></b></div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
margin-left:17.85pt;margin-right:0cm">
	<div style="margin-bottom:1.0pt;border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
4. LITANI MAZMUR </span></b></div>
</div>
<div style="margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;;">
	a.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	Litani Mazmur&ndash;</span></b><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;">
	<?php echo $AyatPenuntunLM; ?></span></b><b><span style="font-size:
10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> </span></b><i><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
(oleh Liturgos dan Umat)</span></i></div>
<div style="margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;;">
	b.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	Nyanyian Sukacita&ndash; </span></b><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;">
	<?php echo $Nyanyian4; ?></span></b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <b><i>
	(jemaat berdiri) </i></b></span></div>
<div style="margin-left:1.0cm;">
	<b></b></div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
background:#D9D9D9">
	<div style="margin-top:0cm;margin-right:0cm;margin-bottom:1.0pt;
margin-left:17.85pt;text-indent:-17.85pt;background:#D9D9D9;
border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
		II. LITURGI SABDA</span></b></div>
</div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
margin-left:17.85pt;margin-right:0cm">
	<div style="margin-top:2.0pt;margin-right:0cm;margin-bottom:0cm;
margin-left:17.85pt;margin-bottom:.0001pt;text-indent:-17.85pt;
border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
		1. PEMBACAAN SABDA </span></b></div>
</div>
<div style="margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;;">
	a.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	Doa mohon pimpinan dan hikmat Roh Kudus </span></b><i><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	(oleh Pemimpin Ibadah)</span></i></div>
<div style="margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;;">
	b.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	Bacaan I&ndash;</span></b><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	<?php echo $Bacaan1; ?></span></b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <i>
	(oleh Lektor)</i></span></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:
10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;;">
c.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Bacaan Injil&ndash;</span></b><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
<?php echo $BacaanInjil; ?></span></b><span style="font-size:
10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <i>
(oleh Pemimpin Ibadah)</i></span></div>
<div style="margin-left:52.5pt;text-align:justify;text-indent:
-9.95pt;">
	<span style="font-size:10.0pt;font-family:Wingdings;">&sect;</span><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	Diakhiri dengan ucapan: &rdquo;<i>Berbahagialah setiap orang yang mendengarkan Firman Tuhan dan memeliharanya. Haleluya/Hosiana/Maranata!&rdquo;)</i></span></div>
<div style="margin-left:52.5pt;text-align:justify;text-indent:
-9.95pt;">
	<span style="font-size:10.0pt;font-family:Wingdings;">&sect;</span><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	Umat menyambut dengan <b>NKB 225 &ndash; <i>&ldquo;Haleluya! Amin!&rdquo;</i></b></span></div>
<div style="margin-left:53.3pt;text-align:justify;">
	</div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
margin-left:18.15pt;margin-right:0cm">
	<div style="margin-top:0cm;margin-right:0cm;margin-bottom:1.0pt;
margin-left:10.2pt;text-indent:-10.2pt;border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;">
2.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span></b><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
KHOTBAH&ndash;</span></b><b><i><span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;">
&quot;<?php echo $Tema; ?>&quot;</span></i></b></div>
</div>
<div style="margin-left:1.0cm;">
	<b></b></div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
margin-left:18.15pt;margin-right:0cm">
	<div style="margin-top:0cm;margin-right:0cm;margin-bottom:1.0pt;
margin-left:10.2pt;text-indent:-10.2pt;border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;">
3.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span></b><b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
RESPONS ATAS KHOTBAH</span></b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">   <i>
(suasana hening, tanpa musik/nyanyian)</i> </span></div>
</div>
<div style="margin-left:46.2pt;text-indent:-17.85pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;">
	a.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Saat Teduh/Hening</span></b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <i>
(hening tanpa iringan musik/nyanyian)</i></span></div>
<div style="margin-left:46.2pt;text-indent:-17.85pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;">
	b.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Doa Syafaat</span></b></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:42.55pt;">
	<span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Ditutup dengan Doa Bapa Kami, dan diakhiri dengan <b>KJ. 475 &ndash; <i>&ldquo;Kar&rsquo;na Engkaulah&rdquo;</i></b></span></div>
<div style="margin-left:46.2pt;text-indent:-17.85pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;">
	c.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Paduan Suara</span></b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
/Vocal Group </span></div>
<div style="margin-left:46.2pt;text-indent:-17.85pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;Arial Narrow&quot;;Arial Narrow&quot;">
	d.<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Pengakuan Iman Rasuli</span></b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> 
dengan <b>KJ 280 &ndash; <i>&ldquo;Aku Percaya&rdquo;</i></b> <i>(dipimpin Liturgos, <b>jemaat berdiri)</b></i></span></div>
<div>
	<b></b></div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
background:#D9D9D9">
	<div style="margin-top:0cm;margin-right:0cm;margin-bottom:3.0pt;
margin-left:17.85pt;text-indent:-17.85pt;background:#D9D9D9;border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
III. LITURGI PENGUCAPAN SYUKUR</span></b></div>
</div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
margin-left:17.85pt;margin-right:0cm">
	<div style="margin-top:2.0pt;margin-right:0cm;margin-bottom:1.0pt;
margin-left:17.85pt;text-indent:-17.85pt;border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
1. PENGUCAPAN SYUKUR <i>(jemaat duduk)</i></span></b></div>
</div>
<div style="margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	a. <b>Ajakan Bersyukur&ndash;</b></span><b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;">
<?php echo $AyatPenuntunP; ?></span></b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <i>
(oleh Liturgos</i><i>) </i></span></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	b. <b>Penghimpunan Persembahan</b></span></div>
<div style="margin-left:53.2pt;text-indent:-10.65pt;">
	<span style="font-size:10.0pt;font-family:Symbol;">&middot;<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span><b>
	<span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Nyanyian Pengucapan Syukur&ndash;</span></b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"></span><b>
<span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;">
 <?php echo $Nyanyian5; ?>/span></b></div>
<div style="margin-left:53.2pt;text-align:justify;text-indent:
-10.65pt;">
	<span style="font-size:10.0pt;font-family:Symbol;">&middot;<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span>
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	Nyanyian dinyanyikan bait pertama terlebih dulu, sementara umat mempersiapkan persembahan.</span></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:3.0pt;
margin-left:53.3pt;text-align:justify;text-indent:-10.75pt;">
	<span style="font-size:10.0pt;font-family:Symbol;">&middot;<span style="font:7.0pt &quot;Times New Roman&quot;"> </span></span>
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	Menjelang akhir bait pertama, pelayan penghimpun persembahan dan anggota Majelis berjajar di depan mimbar. 
	Anggota Majelis dan Pemimpin Ibadah menyampaikan persembahan terlebih dahulu, kemudian diikuti oleh segenap umat. <i>
	(jemaat duduk dan/atau maju)</i></span></div>
<div style="margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	c. <b>Doa Pengucapan Syukur </b><i>(oleh Liturgos, <b>jemaat berdiri)</b></i></span></div>
<div style="margin-left:18.0pt;text-indent:-18.0pt;">
	<b></b></div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
background:#D9D9D9">
	<div style="margin-top:0cm;margin-right:0cm;margin-bottom:3.0pt;
margin-left:17.85pt;text-indent:-17.85pt;background:#D9D9D9;
border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
		IV. LITURGI PENUTUP</span></b></div>
</div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
margin-left:17.85pt;margin-right:0cm">
	<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:17.85pt;text-indent:-17.85pt;border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
1. PENGUTUSAN </span></b></div>
</div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	a. <b>Nas Pengutusan</b> <i>(oleh Liturgos)</i></span></div>
<div style="margin-left:63.8pt;text-indent:-21.25pt;">
	<b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	L :</span></b><span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	Terimalah pengutusan atas kita : <i>(membaca </i></span><b><i><span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;"><?php echo $AyatPenuntunNP; ?>span></i></b><i><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">) ... </span></i>
<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Demikianlah Sabda Allah.</span></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:63.8pt;text-indent:-21.25pt;">
	<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
U :</span></b><span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <i>
Terpujilah Tuhan!</i></span></div>
<div style="margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	b. <b>Nyanyian Pengutusan&ndash;</b></span><b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;">
<?php echo $Nyanyian6; ?></span></b><span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">  </span></div>
<div style="margin-left:54.0pt;text-indent:-18.0pt;">
	<b></b></div>
<div style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 0cm 1.0pt 0cm;
margin-left:18.0pt;margin-right:0cm">
	<div style="margin-left:18.0pt;text-indent:-18.0pt;border:none;padding:0cm;">
		<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
2. BERKAT</span></b></div>
</div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	a. <b>Penyampaian Berkat Tuhan</b>  <i>(oleh Pemimpin Ibadah)</i></span></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:63.8pt;text-align:justify;text-indent:-21.25pt;">
	<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
P :</span></b><span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
Dengan kerendahan hati, mari kita memohon pimpinan dan pemeliharaan Tuhan Yesus Kristus, <b>Sang Kepala Gereja</b>. 
Terimalah berkatnya : ... <i>(berkat disampaikan)</i></span></div>
<div style="margin-top:0cm;margin-right:0cm;margin-bottom:2.0pt;
margin-left:63.8pt;text-indent:-21.25pt;">
	<b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">U :</span></b><span style="font-size:10.0pt;
font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;"> <i>
(menyanyikan </i><b>KJ 478c<i> &ndash; &ldquo;Amin, amin, amin&rdquo;)</i></b></span></div>
<div style="margin-left:42.55pt;text-indent:-14.2pt;">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	b. <b>Nyanyian Penutup&ndash;</b></span><b><span style="font-size:10.0pt;font-family:
&quot;Arial Narrow&quot;,&quot;sans-serif&quot;"><?php echo $Nyanyian7; ?></span></b></div>
<div style="margin-left:21.3pt">
	</div>
<div style="margin-left:21.3pt;text-align:justify">
	<span style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;">
	(<i>Sementara jemaat menyanyikan Nyanyian Penutup, Pemimpin Ibadah turun dari mimbar utama/besar, <b>mematikan Lilin Ibadah, </b>
	kemudian menyerahkan Alkitab kepada Liturgos. Setelah itu, Pemimpin Ibadah dan anggota Majelis bersalam-salaman 
	dengan warga jemaat di depan pintu gedung gereja</i>)</span></div>


</td></tr>	
</table>