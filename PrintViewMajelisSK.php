<?php
/*******************************************************************************
 *
 *  filename    : PrintViewMajelisSK.php
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

//Get the MasaBaktiMajelisID out of the querystring
$iMasaBaktiMajelisID = FilterInput($_GET["MasaBaktiMajelisID"],'int');
$iMode = FilterInput($_GET["Mode"],'int');
$iKopSurat = FilterInput($_GET["KopSurat"],'int');

// Get this MasaBaktiMajelis 
       $sSQL = "select a.*, b.*, c.* , d.*
	   FROM MasaBaktiMajelis	a
			LEFT JOIN person_per b ON a.per_ID = b.per_ID
			LEFT JOIN volunteeropportunity_vol c ON a.vol_ID = c.vol_ID
			LEFT JOIN NotulaRapat d ON a.TglKeputusan = d.Tanggal
			
		 WHERE MasaBaktiMajelisID = " . $iMasaBaktiMajelisID;

$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

						$time  = strtotime($TglPeneguhan);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat1 =  $iMasaBaktiMajelisID."e/MG-SKM/".$sChurchCode."/".dec2roman($month)."/".$year;

// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}

// Set the page title and include HTML header
$sPageTitle = gettext("SK Peneguhan Majelis untuk $per_FirstName nomor SK $iMasaBaktiMajelisID ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or SK Majelis";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMasaBaktiMajelisID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center>
<table border="0"  width="605" cellspacing=0 cellpadding=0>
<tr><td valign=top align=center>

<table border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><!-- Row 2 -->
     <td valign=top align=left>
<?php  if (($iMode==1)||($iMode==2)||($iMode==21)||($iMode==22)){	 
     echo "<img border=\"0\" src=\"gkj_logo.jpg\" width=\"110\" >";
	 }else{
	 echo "<img border=\"0\" src=\"Images/Spacer.gif\" width=\"1\" height=\"90\" >"; 
	}	
?>	
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b style="font-family: Times; color: rgb(0, 0, 102);"><font size="4"><?php  if (($iMode==1)||($iMode==2)||($iMode==21)||($iMode==22)){echo "GEREJA KRISTEN JAWA";}else{echo"";} ;?></font></b><BR>
	 <b style="font-family: Times; color: rgb(0, 0, 102);"><font size="4"><?php if (($iMode==1)||($iMode==2)||($iMode==21)||($iMode==22)){echo strtoupper($sChurchGKJName) ;}else{echo"";}?></font></b><BR>
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php if (($iMode==1)||($iMode==2)||($iMode==21)||($iMode==22)){echo "(Anggota Persekutuan Gereja-Gereja di Indonesia)";}else{echo"";}?></font></b><br>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php if (($iMode==1)||($iMode==2)||($iMode==21)||($iMode==22)){echo $sChurchAddress."
	 <BR>".$sChurchCity.", ". $sChurchState.", Kode POS ". $sChurchZip."
	 <BR>Telp : ".$sChurchPhone." , Fax : ".$sChurchFax."
	 <BR>Email: ".$sChurchEmail." , Situs Web : ".$sChurchWebsite;}else{echo"";}?></font></b>
        </td><!-- Col 3 -->

  </tr>

</table>

<table border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u><? if (($iMode==1)||($iMode==2)||($iMode==21)||($iMode==22)){ echo "<hr style=\"border: 3px outset #595955;\">";}else{echo "<br>";}; ?></u></b></font>
     <table border="0"  width="100%">
  <table border="0"  width="100%">
    <?php
	echo "<tr><td align=center><font size=\"3\"><b><u>SURAT KEPUTUSAN</u></b></font></td></tr>";
	echo "<tr><td align=center><font size=\"3\">Nomor : " . $NomorSurat1. "</font></td></tr>";
	echo "<tr><td align=center><font size=\"3\"><br>			</font></td></tr>";
	
	echo "<tr><td align=center><font size=\"3\"><b>Tentang</b></font></td></tr>";
	echo "<tr><td align=center><font size=\"3\"><br>			</font></td></tr>";
	echo "<tr><td align=center><font size=\"3\"><b>PENEGUHAN PENATUA DAN DIAKEN</b></font></td></tr>";
	echo "<tr><td align=center><font size=\"3\"><b>PERIODE TAHUN ".date('Y',strtotime($TglPeneguhan))." - ".date('Y',strtotime($TglAkhir))."</b></font></td></tr>";
 	echo "<tr><td align=center><font size=\"3\"><b>".strtoupper($sChurchFullName)."</b></font></td></tr>";
	echo "<tr><td align=center><font size=\"3\"><br></font></td></tr>";
	echo "<tr><td align=center><font size=\"3\"><br></font></td></tr>";
	echo "<tr><td align=center><font size=\"3\"><b>MAJELIS ".strtoupper($sChurchFullName)."</b></font></td></tr>";
   ?>
   </table>
 
  <table border="0"  width="100%">
    <?php
	echo "<tr><td align=center><font size=\"3\"><br>		</font></td></tr>";
	echo "<tr><td align=left colspan=2 ><font size=\"3\"><b>Menimbang : </b></font></td></tr>";
	echo "<tr><td align=left valign=top ><font size=\"3\">1. </font></td>";
	echo "<td align=justify ><font size=\"3\">Bahwa $sChurchFullName saat ini telah dan sedang mengalami perkembangan yang cukup pesat baik jumlah warga maupun cakupan wilayah pelayanannya.	</font></td></tr>";

	echo "<tr><td align=left valign=top ><font size=\"3\">2. </font></td>";
	echo "<td align=justify ><font size=\"3\">Bahwa Sdr. <b>$per_FirstName</b> dianggap memenuhi syarat untuk diteguhkan ke dalam jabatan sebagai $vol_Name $Kategorial $sChurchFullName.</font></td></tr>";

	echo "<tr><td align=left valign=top ><font size=\"3\">3. </font></td>";
	echo "<td align=justify ><font size=\"3\">Bahwa peneguhan Sdr. <b>$per_FirstName</b> ke dalam jabatan sebagai  $vol_Name $Kategorial $sChurchFullName, perlu ditetapkan dengan Keputusan Majelis</font></td></tr>";

   ?>
   </table>

  <table border="0"  width="100%">
    <?php
	echo "<tr><td align=center><font size=\"3\"><br>			</font></td></tr>";
	echo "<tr><td align=left colspan=2 ><font size=\"3\"><b>Mengingat : </b></font></td></tr>";
	echo "<tr><td align=left valign=top ><font size=\"3\">1. </font></td>";
	echo "<td align=justify ><font size=\"3\">Tata Laksana Gereja-gereja Kristen Jawa, pasal 5, tentang Majelis Gereja sebagai penanggung jawab segala kegiatan Gereja, baik di bidang Pemberitaan Penyelamatan Allah, Pemeliharaan Iman, maupun Organisasi Gereja.</font></td></tr>";

	echo "<tr><td align=left valign=top ><font size=\"3\">2. </font></td>";
	echo "<td align=justify ><font size=\"3\">Tata Laksana Gereja-gereja Kristen Jawa, pasal 6, tentang Penatua dan Diaken, yang menjelaskan tugas Penatua sebagai pelaksana pemerintahan Gereja demi terlaksananya tugas panggilan Gereja.</font></td></tr>";

   ?>
   </table>
  
  <table border="0"  width="100%">
    <?php
	list($a, $NomorSidang) = explode('-', $NomorSurat);
	echo "<tr><td align=center><font size=\"3\"><br>			</font></td></tr>";
	echo "<tr><td align=left colspan=2 ><font size=\"3\"><b>Memperhatikan : </b></font></td></tr>";
	echo "<tr><td align=left valign=top ><font size=\"3\">1. </font></td>";
	echo "<td align=justify ><font size=\"3\">Keputusan Sidang ke-$NomorSidang Majelis Pekerja Lengkap (MPL) $sChurchFullName tanggal ".date2Ind($TglKeputusan,2).", tentang Peneguhan Penatua dan Diaken $sChurchFullName periode ".date('Y',strtotime($TglPeneguhan))." &#45 ".date('Y',strtotime($TglAkhir)).".</font></td></tr>";
   ?>
   </table>
    <table border="0"  width="100%">
    <?php
	echo "<tr><td align=center><font size=\"3\"><b><BR></b></font></td></tr>";
	?>
   </table> 
	<br>
   	<P CLASS="breakhere">
	<br>
     <table border="0"  width="100%">
    <?php
	echo "<tr><td align=center><font size=\"3\"><b>MEMUTUSKAN</b></font></td></tr>";
	?>
   </table>
   
     <table border="0"  width="100%">
    <?php
	list($a, $NomorSidang) = explode('-', $NomorSurat);
	echo "<tr><td align=left ><font size=\"3\"><b><br><br></b></font></td><td valign=top><b></b></td></tr>";
	echo "<tr><td align=left ><font size=\"3\"><b>Menetapkan</b></font></td><td valign=top><b>:</b></td></tr>";
	echo "<tr><td align=left valign=top ><font size=\"3\"><b>Pertama</b></font></td><td valign=top><b>:</b></td>";
	echo "<td align=justify ><font size=\"3\">Meneguhkan Sdr. <b>$per_FirstName</b> ke dalam jabatan sebagai $vol_Name $Kategorial $sChurchFullName.</font></td></tr>";
	
	echo "<tr><td align=left ><font size=\"3\"><b><br></b></font></td><td valign=top><b></b></td></tr>";
	echo "<tr><td align=left valign=top ><font size=\"3\"><b>Kedua</b></font></td><td valign=top><b>:</b></font></td>";
	echo "<td align=justify ><font size=\"3\">Sebagai anggota Majelis Pekerja Lengkap (MPL) $sChurchFullName ,Sdr. <b>$per_FirstName</b> berkewajiban dan telah menyatakan kesanggupannya untuk melaksanakan tugas-tugas pelayanannya berdasarkan Alkitab, Pokok-pokok Ajaran GKJ, serta Tata Gereja dan Tata Laksana GKJ</font></td></tr>";
 	
	echo "<tr><td align=left ><font size=\"3\"><b><br></b></font></td><td valign=top><b></b></td></tr>";
	echo "<tr><td align=left valign=top ><font size=\"3\"><b>Ketiga</b></font></td><td valign=top><b>:</b></font></td>";
	echo "<td align=justify ><font size=\"3\">Masa pelayanan Sdr. <b>$per_FirstName</b> adalah terhitung  sejak diteguhkannya, yaitu tanggal ".date2Ind($TglPeneguhan,2)." sampai dengan tanggal ".date2Ind($TglAkhir,2).", atau selama 3 (tiga) tahun penuh.</font></td></tr>";
 
 ?>
   </table>
  
  </table>


  <table border="0"  width="100%">
  

     <tr><td align=center  colspan="2"><font size="3">  Ditetapkan di <?php echo $sChurchCity; ?> </td><td></td></tr>
	 <tr><td align=center  colspan="2"><font size="3">  Pada tanggal : <?php echo date2Ind($TglPeneguhan,2); ?> </td><td></td></tr>
	 <tr><td align=center  colspan="2"><font size="3">  </td><td></td></tr>
	<tr><td align=center  colspan="2"><font size="3"><b>  MAJELIS <?php echo strtoupper($sChurchFullName);?> </b></td><td></td></tr>

<br>
<br>
 <?php  if (($iMode==2)||($iMode==4)){	
	echo "<tr>";
    echo "<td valign=bottom align=center ><img border=\"0\"  height=\"80\"  src=\"ttd_ketua.jpg\"></td>";
	echo "<td valign=bottom align=center ><img border=\"0\"  height=\"80\"  src=\"ttd_sekre1.jpg\"></td>";
	echo "</tr>";
	}else if (($iMode==21)||($iMode==41)){	
	echo "<tr>";
    echo "<td valign=bottom align=center ><img border=\"0\"  height=\"80\"  src=\"ttd_ketua.jpg\"></td><td ></td>";
	echo "</tr>";
	}else if (($iMode==22)||($iMode==42)){	
	echo "<tr>";
    echo "<td valign=bottom align=center ></td>";
	echo "<td valign=bottom align=center ><img border=\"0\"  height=\"80\"  src=\"ttd_sekre1.jpg\"></td>";
	echo "</tr>";
	}else{
	 echo "<tr>";
	 echo "<td></td><td></td>"; 
	 echo "</tr>";
	}	
?>	
 <tr>
  <td valign=bottom align=center width="50%" 
  <?php  if (($iMode==2)||($iMode==4)||($iMode==21)||($iMode==41)||($iMode==22)||($iMode==42)){	 
  echo "style=\"height:1px\""; }else{ 
  echo "style=\"height:80px\""; }
  ?>>
  <font size="3"><u><?php echo jabatanpengurus(61); ?></u></td><td valign=bottom align=center ><font size="3"><u><?php echo jabatanpengurus(65); ?></u></td>
  </tr>  
 <tr>
  <td valign=bottom align=center width="50%"><font size="3">Ketua Majelis</td><td align=center ><font size="3">Sekretaris</td>
  </tr>  
 

  <tr><td valign=bottom align=center colspan="2" style="height:50px"><font size="3">
  <u><?php echo jabatanpengurus(1); ?></u></td><td></td></tr>
  <tr><td align=center colspan="2"><font size="3">Pendeta Jemaat</td><td></td></tr>


  </table>
  <table border="0"  width="100%">
<?php  
echo "<tr><td align=left colspan=2 valign=top ><font size=\"1\"><i><br><br><br><br><br></i></font></td>";
 	echo "<tr><td align=left colspan=3 ><font size=\"1\"><i><u>Surat Keputusan ini dibuat rangkap dua, dan disampaikan kepada </u></i></font></td><td valign=top><b>:</b></td></tr>";
	echo "<tr><td align=left colspan=2 valign=top ><font size=\"1\"><i>1.	Yth. Sdr $per_FirstName </i></font></td>";
	echo "<tr><td align=left colspan=2 valign=top ><font size=\"1\"><i>2.	Yth. Majelis $sChurchFullName </i></font></td>";
?>
</table>  
 </table>  
  </td><!-- Col 1 -->
  </tr>
  
<br>


</td></tr>
</table>
</td></tr>
</table>
