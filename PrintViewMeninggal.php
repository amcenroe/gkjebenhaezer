<?php
/*******************************************************************************
 *
 *  filename    : PrintViewMeninggal.php
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
$iMeninggalID = FilterInput($_GET["MeninggalID"],'int');
$iKopSurat = FilterInput($_GET["KopSurat"],'int');
$iMode = FilterInput($_GET["Mode"],'int');

// Get this Meninggal's data
$sSQL = " SELECT		a.*, b.*, f.*, CONCAT(b.per_id,b.per_fam_id,b.per_gender,b.per_fmr_id) as NomorInduk,
		a.TanggalMeninggal as TanggalMeninggal,b.per_WorkEmail as TempatLahir,
		b.per_BirthMonth as BlnLahir, b.per_BirthDay as TglLahir, b.per_BirthYear as ThnLahir,
		e.fam_Name as NamaKeluarga, e.fam_Address1 as AlamatKeluarga, 
		fmr.lst_OptionName AS sFamRole,
		IF(a.per_ID>0,b.per_firstname,a.Nama) as NamaLengkap , 
		IF(a.per_ID=0,IF(a.GerejaID>0,f.NamaGereja,NamaGerejaNonGKJ),b.per_WorkPhone) as Kelompok,
		a.TempatPemakaman as TempatPemakaman,
                a.TanggalPemakaman as TanggalPemakaman,
				f.NamaGereja as NamaGereja
		
		FROM PermohonanPemakamangkjbekti  a
		
		LEFT JOIN person_per b ON a.per_ID = b.per_ID 
        left join family_fam e ON b.per_fam_id = e.fam_id 
		left join person_per c ON (e.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
		left join person_per d ON (e.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
		LEFT JOIN DaftarGerejaGKJ f ON a.TempatSemayam = f.GerejaID
		LEFT JOIN person_custom g ON a.per_ID = g.per_ID 
		LEFT JOIN list_lst fmr ON b.per_fmr_ID = fmr.lst_OptionID AND fmr.lst_ID = 2
		WHERE MeninggalID = " . $iMeninggalID;

$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

						$time  = strtotime($Tanggal);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat =  $MeninggalID."e/MG-".$KodePengirim."/".$sChurchCode."/".dec2roman($month)."/".$year;

// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}

// Set the page title and include HTML header
$sPageTitle = gettext("Surat Keluar untuk $Kepada - $Institusi nomor surat $NomorSurat ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or Surat Keluar";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMeninggalID . "','" . $logvar1 . "','" . $logvar2 . "')";
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
<?php  if (($iMode==1)||($iMode==2)){	 
     echo "<img border=\"0\" src=\"gkj_logo.jpg\" width=\"110\" >";
	 }else{
	 echo "<img border=\"0\" src=\"\Images\Spacer.gif\" width=\"1\" height=\"90\" >"; 
	}	
?>	
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b style="font-family: Times; color: rgb(0, 0, 102);"><font size="4"><?php  if (($iMode==1)||($iMode==2)){echo "GEREJA KRISTEN JAWA";}else{echo"";} ;?></font></b><BR>
	 <b style="font-family: Times; color: rgb(0, 0, 102);"><font size="4"><?php if (($iMode==1)||($iMode==2)){echo strtoupper($sChurchGKJName) ;}else{echo"";}?></font></b><BR>
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php if (($iMode==1)||($iMode==2)){echo "(Anggota Persekutuan Gereja-Gereja di Indonesia)";}else{echo"";}?></font></b><br>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php if (($iMode==1)||($iMode==2)){echo $sChurchAddress."
	 <BR>".$sChurchCity." ". $sChurchState." ". $sChurchZip."
	 <BR>Telp : ".$sChurchPhone." , Fax : ".$sChurchFax."
	 <BR>Email: ".$sChurchEmail." , Situs Web : ".$sChurchWebsite;}else{echo"";}?></font></b>
        </td><!-- Col 3 -->

  </tr>

</table>

<table border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u><? if (($iMode==1)||($iMode==2)){ echo "<hr style=\"border: 3px outset #595955;\">";}else{echo "<br>";}; ?></u></b></font>
     <table border="0"  width="100%">

    <?php
	

   echo "<tr><td valign=top><font size=\"3\">Nomor Surat:</td><td><font size=\"3\">  " . $NomorSurat."</font></td>";
   echo "<td><font size=\"3\">".$sChurchCity.", </td><td><font size=\"3\">"; echo tanggalsekarang() ; date ('Y' ); echo " </td></tr>";
   echo "<tr><td valign=top><font size=\"3\">Hal:</td><td><font size=\"3\">".$Hal."</font></td></tr>";
   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
   echo "<tr><td valign=top colspan=2 ><font size=\"3\">Kepada YTH</td><td><font size=\"3\"></font></td></tr>";	

   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><b>  Majelis ".$NamaGereja ." </b></td><td><font size=\"3\"></font></td></tr>";
   if ($Alamat1==""){
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF>.</font> Ditempat</font></td><td><font size=\"3\"></font></td></tr>";	
   }else{
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF>.</font> ".$Alamat1."</font></td><td><font size=\"3\"></font></td></tr>";	
   }
    if ($Alamat2<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF>.</font> ".$Alamat2."</font></td><td><font size=\"3\"></font></td></tr>";	
   } 
       if ($Alamat3<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF>.</font> ".$Alamat3."</font></td><td><font size=\"3\"></font></td></tr>";	
   } 
   if ($Email<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF>.</font> ".$Email."</font></td><td><font size=\"3\"></font></td></tr>";	
   }    
    if ($Telp==""){  
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF></font></font></td><td><font size=\"3\"></font></td></tr>";	   
    }else{
   echo "<tr><td valign=top colspan=2 ><font size=\"3\"><font color=#FFFFFF>.</font>Telp:".$Telp." /Fax:".$Fax."</font></td><td><font size=\"3\"></font></td></tr>";	
   } 


   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
	echo "<br>";
   ?>
  </table>
  <br>

  <table border="0"  width="100%">
  <?php 
 echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"><i>Salam Sejahtera dalam kasih Tuhan Yesus Kristus,</i></font></td>"; 


 echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"></font></td>"; 

					if ($sFamRole != "") { $HubKeluarga = $sFamRole. " dari Kelg. ".$NamaKeluarga; } else { $HubKeluarga =""; }; 
					$TanggalLahir = $ThnLahir."-".$BlnLahir."-".$TglLahir;
 			
$sIsiSuratBalasan = "
<p>Majelis " .$sChurchFullName. " dengan ini mohon kesediaan Majelis ".$NamaGereja ." untuk melayani ibadah Pelepasan dan Pemakaman bagi warga kami :
<br /><br />&nbsp;&nbsp; 
Nama &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; 
(Alm) ".$NamaLengkap." <br />&nbsp;&nbsp; 
Alamat &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; 
".$AlamatKeluarga." <br />&nbsp;&nbsp;
Tempat,tanggal lahir&nbsp;&nbsp;&nbsp; :&nbsp; 
".$TempatLahir." , ".date2Ind($TanggalLahir,2)."<br />&nbsp;&nbsp; 
Usia&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; 
". FormatAgeRip($BlnLahir,$TglLahir,$ThnLahir,$TanggalMeninggal)."<br />&nbsp;&nbsp; 
Meninggal hari/tanggal : 
".date2Ind($TanggalMeninggal,1)."</p>
<p>Rencana Pemakaman&nbsp;&nbsp; &nbsp;: <br />&nbsp;&nbsp;&nbsp; 
Di semayamkan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : 
".$NamaGereja ." <br />&nbsp;&nbsp; &nbsp;
Pukul  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : 
".$WaktuPemakaman."<br />&nbsp;&nbsp;&nbsp; 
Tempat Pemakaman &nbsp; : 
".$TempatPemakaman."<br />&nbsp;&nbsp; &nbsp;<br />
Demikian permohonan ini kami sampaikan, atas kesediaan dan kerja samanya,&nbsp; kami ucapkan terima kasih.<br /><br /></p>
";

 
 
 
 echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"80\" height=\"1\" >  
  ". $sIsiSuratBalasan . " </font></td>"; 
  echo "<tr><td valign=top colspan=\"4\"><font size=\"3\"></font></td>"; 


 ?>
   <table  border="0" width="100%">
  <tr><td align=center colspan="2"> <?php echo $sChurchCity;?>,  <?php echo tanggalsekarang();?></td><td></td></tr>
     <tr><td align=center  colspan="2">  Teriring Salam dan Doa Kami, </td><td></td></tr>
   <tr><td align=center  colspan="2"><b>  MAJELIS  <?php echo strtoupper($sChurchFullName);?> </b></td><td></td></tr>

<br>
<br>
 <?php  if (($iMode==2)||($iMode==4)){	
	echo "<tr>";
    echo "<td valign=bottom align=center ><img border=\"0\" src=\"ttd_ketua.jpg\"></td><td valign=bottom align=center ><img border=\"0\" src=\"ttd_sekre1.jpg\"></td>";
	echo "</tr>";
	}else{
	 echo "<tr>";
	 echo "<td></td><td></td>"; 
	 echo "</tr>";
	}	
?>	
 <tr>
  <td valign=bottom align=center width="50%" 
  <?php  if (($iMode==2)||($iMode==4)){	 
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
 
  </table>  
  </td><!-- Col 1 -->
  </tr>
  
<br>


</td></tr>
</table>
</td></tr>
</table>
