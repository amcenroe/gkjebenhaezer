<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPenyegaranJanjiNikahBeritaAcara.php
 *
 *  2012 Erwin Pratama for GKJ Bekasi Timur
 *  Sistem Informasi GKJ Bekasi Timur is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

// Get the person ID from the querystring
$iNikahID = FilterInput($_GET["NikahID"],'int');
$iTanggal = FilterInput($_GET["Tanggal"],'date');
$iWaktu = FilterInput($_GET["Waktu"],'int');
$iTempat = FilterInput($_GET["Tempat"],'int');
//$iTanggalRencanaPenyegaranJanjiNikah = FilterInput($_GET["TanggalPenyegaranJanjiNikah"],'date');
//$iWaktuPenyegaranJanjiNikah = FilterInput($_GET["WaktuPenyegaranJanjiNikah"],'int');

// Get this Baptis Anak Data
// $sSQL = "SELECT * FROM PenyegaranJanjiNikahgkjbekti WHERE PenyegaranJanjiNikahID = " . $iPenyegaranJanjiNikahID;

$sSQL = "SELECT NikahID,
per_ID_L, per_ID_P, 	
a.PendetaID, l.NamaPendeta as PelayanPernikahan, KetuaMajelis, SekretarisMajelis,  	
TanggalNikah, WaktuNikah, a.TempatNikah as TmpNkh,
m.NamaGereja as NamaGereja,
m.Alamat1 as Alamat1Gereja,
b.fam_WorkPhone as Kelompok, b.fam_WeddingDate as TanggalMenikah, a.fam_ID as FamilyID,

g.per_FirstName as NamaLengkapL,g.per_WorkEmail as TempatLahirL,
CONCAT(g.per_BirthYear,'-',g.per_BirthMonth,'-',g.per_BirthDay) as TanggalLahirL,
i.c26 as TempatBaptisAnakL, 
i.c1 as TanggalBaptisAnakL,
i.c37 as PendetaBaptisL,
i.c27 as TempatSidhiL, 
i.c2 as TanggalSidhiL,
i.c38 as PendetaSidhiL,
i.c28 as TempatBaptisDewasaL, 
i.c18 as TanggalBaptisDewasaL,
i.c39 as PendetaBaptisDewasaL,
i.c16 as NamaAyahL, 
i.c17 as NamaIbuL, 

h.per_FirstName as NamaLengkapP,h.per_WorkEmail as TempatLahirP,
CONCAT(h.per_BirthYear,'-',h.per_BirthMonth,'-',h.per_BirthDay) as TanggalLahirP,
j.c26 as TempatBaptisAnakP,
j.c1 as TanggalBaptisAnakP,
j.c37 as PendetaBaptisP,
j.c27 as TempatSidhiP,
j.c2 as TanggalSidhiP,
j.c38 as PendetaSidhiP,
j.c28 as TempatBaptisDewasaP,
j.c18 as TanggalBaptisDewasaP,
j.c39 as PendetaBaptisDewasaP,
j.c16 as NamaAyahP, 
j.c17 as NamaIbuP


FROM PermohonanPenyegaranJanjiNikah a 

LEFT JOIN family_fam b ON a.fam_id = b.fam_id 
LEFT JOIN person_per g ON (a.fam_id = g.per_fam_id AND g.per_fmr_id = 1 AND g.per_gender = 1)
LEFT JOIN person_custom i ON g.per_id = i.per_id

LEFT JOIN person_per h ON (a.fam_id = h.per_fam_id AND h.per_fmr_id = 2 AND h.per_gender = 2)
LEFT JOIN person_custom j ON h.per_id = j.per_id

LEFT JOIN DaftarPendeta l ON a.PendetaID = l.PendetaID
LEFT JOIN DaftarGerejaGKJ m ON a.TempatNikah = m.GerejaID

LEFT JOIN DaftarGerejaGKJ n ON a.WargaGerejaL = n.GerejaID
LEFT JOIN DaftarGerejaGKJ o ON a.WargaGerejaP = o.GerejaID

WHERE a.TanggalNikah ='".$iTanggal."' AND a.WaktuNikah =".$iWaktu." AND a.TempatNikah = ".$iTempat;


//		 echo $sSQL;
$rsPerson = RunQuery($sSQL);
$rsPerson2 = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson2));





// Set the page title and include HTML header
$sPageTitle = gettext("Berita Acara Pelayanan Pembaruan dan Penyegaran Janji Nikah No.$NikahID - $NamaLengkapL dan $NamaLengkapP");
$iTableSpacerWidth = 10;
//require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "Print Surat Berita Acara Pelayanan Pembaruan dan Penyegaran Janji Nikah " . $NamaLengkapL."dan".$NamaLengkapP;
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPenyegaranJanjiNikahID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table  border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center style="background-image: url('/datawarga/Images/blangkobaptis.jpg');background-position: center top;background-repeat:no-repeat">
<table  border="0"  width="505" cellspacing=0 cellpadding=0 >
<tr><td valign=top align=center>

<table  border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><td valign=bottom align=center width="100%" style="height:150px" > </tr>
  <tr><!-- Row 2 -->
     <td valign=top align=center >
	 <b><font FACE="Bernard MT Condensed" size="6"><?php echo $sChurchFullName ;?></b><BR>
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 ( Anggota Persekutuan Gereja Gereja di Indonesia )<BR>
	 Kompleks <?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	  <?php echo "Telp:".$sChurchPhone. " - Fax:". $sChurchFax   . " - Email:".$sChurchEmail;?></font><br><br>
	 <b><font FACE="Arial" size="5">Berita Acara</font> <font FACE="Arial" size="4"><br>Pelayanan Pembaruan dan Penyegaran Janji Perkawinan</font></b>
	</td><!-- Col 3 -->
	</tr>

	<tr><td valign=top align=center >
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
		 <?php $TGL=$iTanggal; ?>
	Berita Acara Nomor:  <?php echo $iTanggal."/BAPJP/".$sChurchCode."/".strftime( "%Y", strtotime($TGL));?></font>
     </td></tr>
	 <tr></tr>
	<tr><td align=center colspan="2"> <br><?php echo "".date2Ind($TanggalNikah,1)." di " .$NamaGereja ; ?>	
	</td><td></td></tr>
	 
</table>
<br>
<table border="0"  width="300" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
 

        <table cellpadding="2" align="center" cellspacing="0" width="520">

        <tr class="TableHeader" align="center" >
				<td><b><u><font size="2" style="font-family: Arial"><?php echo gettext("No"); ?></font></u></b></td>
				<td><b><u><font size="2" style="font-family: Arial"><?php echo gettext("Nama"); ?></font></u></b></td>

	   </tr>
        <?php
        //Loop through the family recordset
		$i = 0;
		
		
		
        while ($aRow = mysql_fetch_array($rsPerson))
        {
				$i++;				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
					<td><font size="2" style="font-family: Arial; color: rgb(0, 0, 0);"><?php  echo $i ; ?></font></td>
					<td><font size="2" style="font-family: Arial; color: rgb(0, 0, 0);"><?php echo "<b>Bp. ".$NamaLengkapL."</b>
					dan  <b>Ibu.".$NamaLengkapP."</b> ,Kelompok : ".$Kelompok."" ;?><br></font></td>                </tr>
                <?php
        }

        //Close the table
        echo "</table>";
 ?>

   </tr>

  <table  border="0" width="520">
  <tr><td align=center colspan="2"> <?php echo "$sChurchCity" ;?>,  <?php echo date2Ind($TanggalPenyegaranJanjiNikah,2); ?></td><td></td></tr>
   <tr><td align=center  colspan="2"><b>  Majelis <?php echo "$sChurchFullName" ;?></b></td><td></td></tr>
<br>
<br>
 
 <tr>
  <td valign=bottom align=center width="50%" style="height:80px" ><u><?php echo $PelayanPernikahan;?></u>
  </td><td valign=bottom align=center ><u>...........................</u></td>
  </tr>  
 <tr>
  <td valign=bottom align=center width="50%">Pendeta Yang Melayani</td><td align=center >Majelis</td>
  </tr>  
 

   <tr><td align=center colspan="2" style="height:10px"><font FACE="Monotype Corsiva" size="1"><br>
  Catatan : 
  </font></u></td><td></td></tr>
  </table>
</td></tr>
</table>
</td></tr>

</table>
