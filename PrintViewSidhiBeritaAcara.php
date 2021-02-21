<?php
/*******************************************************************************
 *
 *  filename    : PrintViewSidhiBeritaAcara.php
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
$iSidhiID = FilterInput($_GET["SidhiID"],'int');
$iTanggalRencanaSidhi = FilterInput($_GET["TanggalSidhi"],'date');
$iWaktuSidhi = FilterInput($_GET["WaktuSidhi"],'int');

// Get this Baptis Anak Data
// $sSQL = "SELECT * FROM sidhigkjbekti WHERE SidhiID = " . $iSidhiID;
$sSQL = "select a.* , z.SidhiID, 
		a.per_BirthDay as TglLahir, a.per_BirthMonth as BlnLahir,  a.per_BirthYear as ThnLahir, 
		a.per_id, 
		CONCAT(a.per_id,a.per_fam_id,a.per_gender,a.per_fmr_id) as NomorInduk,
		a.per_firstname as NamaPemohonBaptis , 
		a.per_WorkEmail as TempatLahir,
		CONCAT(a.per_BirthYear,'-',a.per_BirthMonth,'-',a.per_BirthDay) as TanggalLahir,
		a.per_Workemail as TempatLahir,
		c.per_firstname as NamaAyah, 
		d.per_firstname as NamaIbu,	
		
		z.KetuaMajelis as KetuaMajelis,
		z.SekretarisMajelis as SekretarisMajelis,

		z.NamaLengkap as NamaPemohonBaptisNW, 
		z.TempatLahir as TempatLahirNW,
		z.TanggalLahir 	as TanggalLahirNW,
		z.NamaAyah 	as NamaAyahNW,
		z.NamaIbu 	as NamaIbuNW,
		z.TanggalRencanaSidhi as TanggalRencanaSidhiNW,
		z.WaktuSidhi as WaktuSidhi,
		z.TempatBaptis 	as TempatBaptisNW,
		z.PendetaBaptis as PendetaBaptisNW,
		z.PendetaSidhi as PendetaSidhi,

		z.NoSuratTitipan as NoSuratTitipanNW, 
		x.c1 as TanggalRencanaSidhi,
		x.c26 as TempatBaptis,
		x.c37 as PendetaBaptis,
		
		a.per_gender as JK , a.per_fam_id
	
	
from sidhigkjbekti z 
left join person_per a ON z.per_id = a.per_id 
left join person_custom x ON a.per_id = x.per_id 
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
left join DaftarPendeta e ON z.PendetaBaptis = e.PendetaID

		 WHERE d.per_cls_id < 3 AND z.TanggalRencanaSidhi ='".$iTanggalRencanaSidhi."' AND z.WaktuSidhi =".$iWaktuSidhi;

//		 echo $sSQL;
$rsPerson = RunQuery($sSQL);
//extract(mysql_fetch_array($rsPerson));





// Set the page title and include HTML header
$sPageTitle = gettext("Berita Acara Pelayanan Pengakuan Percaya Tanggal.$iTanggalRencanaSidhi - $iWaktuSidhi");
$iTableSpacerWidth = 10;
//require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "Print Surat Berita Acara Sidhi " . $NamaPemohonBaptis;
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iSidhiID . "','" . $logvar1 . "','" . $logvar2 . "')";
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
	 <?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	  <?php echo "Telp:". $sChurchPhone  . "- Fax:". $sChurchFax   . " - Email:". $sChurchEmail;?></font><br><br>
	 <b><font FACE="Arial" size="5">Berita Acara</font> <font FACE="Arial" size="4"><br>Pelayanan Pengakuan Percaya<br></font></b>
	</td><!-- Col 3 -->
	</tr>

	<tr><td valign=top align=center >
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
		 <?php $TGL=$iTanggalRencanaSidhi; ?>
	Berita Acara Nomor: <?php echo $TGL."/BASD/".$sChurchCode."/".strftime( "%Y", strtotime($TGL));?></font>
     </td></tr>
	 <tr></tr>
	<tr><td align=center colspan="2"> <br><?php echo date2Ind($iTanggalRencanaSidhi,1); ?> di <?php 
					if ($iWaktuSidhi == "11")
					{echo "06.00 WIB <br>Cut Meutia";}
					elseif ($iWaktuSidhi == "12"){echo "TI Cut Meutia ,Pukul 09.00 WIB ";}
					elseif ($iWaktuSidhi == "21"){echo "TI Cikarang ,Pukul 07.00 WIB ";}
					elseif ($iWaktuSidhi == "31"){echo "TI Karawang ,Pukul 07.30 WIB ";}
					elseif ($iWaktuSidhi == "41"){echo "TI Tambun ,Pukul 17.00 WIB ";}
					else{echo "";} ?>	
	</td><td></td></tr>
	 
</table>
<br>
<table border="0"  width="300" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
 

        <table cellpadding="2" align="center" cellspacing="0" width="520">

        <tr class="TableHeader" align="center" >
				<td><b><u><?php echo gettext("No"); ?></u></b></td>
				<td><b><u><?php echo gettext("Nama"); ?></u></b></td>
                <td><b><u><?php echo gettext("Nama Orang Tua"); ?></u></b></td>
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
					<td><font size="2" style="font-family: Arial; color: rgb(0, 0, 0);"><?php echo $NamaPemohonBaptis ?><?php echo $NamaPemohonBaptisNW ?>&nbsp;</font></td>
					<td><font size="2" style="font-family: Arial; color: rgb(0, 0, 0);"><?php if (strlen($per_ID) > 0){echo $NamaAyah;}else{echo $NamaAyahNW;} ?>
					/<?php if (strlen($per_ID) > 0){echo $NamaIbu;}else{echo $NamaIbuNW;} ?>&nbsp;</font></td>
                </tr>
                <?php
        }

        //Close the table
        echo "</table>";
 ?>
   </tr>
  
  <table  border="0" width="520">
  <tr><td align=center colspan="2"> <?php echo $sChurchCity;?>, <?php echo date2Ind($iTanggalRencanaSidhi,2); ?></td><td></td></tr>
   <tr><td align=center  colspan="2"><b>  Majelis <?php echo $sChurchFullName ;?></b></td><td></td></tr>
<br>
<br>
 
 <tr>
  <td valign=bottom align=center width="50%" style="height:80px" ><u><?php if ($per_ID == 0){echo $PendetaSidhi;} else {echo $PendetaSidhi;} ?></u>
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
