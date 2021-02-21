<?php
/*******************************************************************************
 *
 *  filename    : PrintViewUndanganDPT.php
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
$iPelayanFirmanID = FilterInput($_GET["PelayanFirmanID"],'int');
$iperID = FilterInput($_GET["perID"],'int');
$iKopSurat = FilterInput($_GET["KopSurat"],'int');
$iMode = FilterInput($_GET["Mode"],'int');
$iTema = FilterInput($_GET["Tema"],'int');

		 				$sSQL = "select TRIM(per_workphone) as Kelompok, 
							CONCAT('<a href=FamilyView.php?FamilyID=',per_fam_ID,'>',per_fam_id,'</a>')  as KodeKelg, fam_Name as NamaKeluarga, 
							CONCAT('<a href=PersonView.php?PersonID=',per_ID,'>',per_id,'</a>') as Kode, per_FirstName AS NamaLengkap, 
							CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_Birthday) as 'TglLahir', 
							CASE per_fmr_id
								WHEN '1' THEN '<b><font color=\"blue\">KK</font></b>'
								WHEN '2' THEN 'Ist'
								WHEN '3' THEN 'Ank'
								WHEN '4' THEN 'Sdr'
								END AS HubKlg,
							IF (per_cls_ID='1','Warga','Titipan') as Kewargaan, 
							per_membershipdate as TglDaftar,
							IF (per_Gender='1','L','P') as JnsKelamin,
							IF (c15='2','Menikah','-') as Status,
							IF(c2='0000-00-00 00:00:00','-',c2) as TglSidhi,
							IF(c27 is NULL,'-',c27) as TmpSidhi, IF(c18='0000-00-00 00:00:00','-',c18) as TglBaptisDewasa,
							IF(c28 is NULL,'-',c28) as TmpBaptisDewasa, fam_Address1 as Alamat
						FROM person_per a natural join person_custom 
							LEFT JOIN family_fam b ON a.per_fam_ID = b.fam_ID
							LEFT JOIN list_lst a1 ON a1.lst_OptionID = a.per_fmr_ID AND a1.lst_ID = 2
							LEFT JOIN list_lst a2 ON a2.lst_OptionID = a.per_cls_ID AND a2.lst_ID = 1
						
						WHERE per_ID = $iperID";
				
//		 echo $sSQL; echo "<br>";
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));




// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}

// Set the page title and include HTML header
$sPageTitle = gettext("Surat Permohonan Pelayan Firman tgl $TanggalPF untuk $NamaPendeta -$NamaGereja ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "Cetak Surat Undangan DPT ".$iperID ;
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPelayanFirmanID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
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
	 <BR>".$sChurchCity.", ". $sChurchState.", Kode POS ". $sChurchZip."
	 <BR>Telp : ".$sChurchPhone." , Fax : ".$sChurchFax."
	 <BR>Email: ".$sChurchEmail." , Situs Web : ".$sChurchWebsite;}else{echo"";}?></font></b>
        </td><!-- Col 3 -->

  </tr>
  <td colspan="2"><font size=2><b><u><? if (($iMode==1)||($iMode==2)){ echo "<hr style=\"border: 3px outset #595955;\">";}else{echo "<br>";}; ?></u></b></font>
  </td>
  <tr>
  </tr>
</table>

     <table border="0"  width="600">

    <?php
						$time  = strtotime(date("Y-m-d"));
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
					
						$NomorSurat =  "UND".$iperID."e/J/DPT/MG/".$sChurchCode."/".dec2roman($month)."/".$year;
						
   echo "<tr><td valign=top width=\"80\" ><font size=\"2\">Nomor Surat</td><td colspan=\"2\"><font size=\"2\">: " . $NomorSurat . "</font></td>";
  echo "<td align=right width=\"170\"><font size=\"2\">".$sChurchCity.", "; echo tanggalsekarang() ; date ('Y' ); echo " </td></tr>";
  
   echo "<tr><td valign=top><font size=\"2\">Hal</td><td colspan=\"3\"><font size=\"2\">: Undangan</font></td></tr>";
   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
   echo "<tr><td valign=top colspan=3 ><font size=\"2\">Kepada YTH</td><td><font size=\"2\"></font></td></tr>";	

				if ($JnsKelamin=='L' AND $Status=='Menikah'){ 
					$Sapaan='Bp. ' ;
				} elseif ($JnsKelamin=='P' AND $Status=='Menikah'){ 
					$Sapaan='Ibu. ' ;
				} elseif ($JnsKelamin=='L' AND $Status!='Menikah'){ 
					$Sapaan='Sdr. ' ;
				} elseif ($JnsKelamin=='P' AND $Status!='Menikah'){ 
					$Sapaan='Sdri. ' ;
				}
	

				
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><b> " . $Sapaan . " " . $NamaLengkap . "</b></td><td><font size=\"2\"></font></td></tr>";
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> ".$Alamat."</font></td><td><font size=\"2\"></font></td></tr>";	
	echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> Kelompok ".$Kelompok."</font></td><td><font size=\"2\"></font></td></tr>";	
   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
	echo "<br>";
   ?>
  </table>

  <table border="0"  width="600">
  <?php 
  echo "<tr><td valign=top colspan=\"5\"><font size=\"2\"><i>Salam Sejahtera dalam kasih Tuhan Yesus Kristus,</i></font></td></tr>"; 

 echo "<tr><td valign=top colspan=\"5\"><font size=\"2\">  
  <p style=\"text-align:justify\">Majelis $sChurchName, mengundang Bapak/Ibu/Sdr/i warga jemaat untuk hadir dalam 
  <b>Ibadah Persiapan Perjamuan Kudus dan Permohonan Persetujuan/Pemilihan Jemaat atas Sdri. Ribka Evelina Pratiwi sebagai Calon Pendeta </b>
  yang akan dilaksanakan pada :</p></font></td></tr>"; 
 
 $TanggalPF="2014-08-09";
 $WaktuPF="Pukul 18.00 WIB " ;
 $NamaTI="- Kelompok masing-masing (untuk jemaat dewasa)";
 $NamaTI2="- Gd Induk Cut Meutia (untuk jemaat Remaja Pemuda & Dewasa Muda)";
 
  echo "<tr><td width=\"30\" ></td><td valign=top width=\"50\" ><font size=\"2\"> Hari/Tanggal </td><td>:</td><td><font size=\"2\"><b> ";?>
  <?php echo date2Ind($TanggalPF,1);?><?php echo " </b></td></font></tr>";	
  echo "<tr><td><td valign=top><font size=\"2\">  Waktu </td><td>:</td><td><font size=\"2\"><b> " . $WaktuPF ;

  echo "<tr><td></td><td valign=top><font size=\"2\">  Tempat </td><td>:</td><td><font size=\"2\"><b> " . $NamaTI . " </b></td></font></tr>";
  echo "<tr><td></td><td valign=top><font size=\"2\">        </td><td>:</td><td><font size=\"2\"><b> " . $NamaTI2 . " </b></td></font></tr>";

  echo "<tr><td valign=top colspan=\"5\"><font size=\"2\"><p style=\"text-align:justify\"> Demikian undangan ini kami sampaikan, mengingat pentingnya acara ini maka Bapak/Ibu/Sdr/i 
  dimohon hadir tepat pada waktunya. Atas perhatiannya, kami ucapkan terima kasih</p></font></td></tr>";
  echo "<tr><td  valign=top colspan=\"5\"></tr>";
  
  

  
 ?>

   <table  border="0" width="600" align="top" >
   <tr><td align=center  valign=top colspan="3"><font size="2">  Teriring Salam dan Doa Kami, </td><td></td></tr>
   <tr><td align=center  colspan="3"><font size="2"><b>  MAJELIS <?php echo strtoupper($sChurchFullName);?> </b></td><td></td></tr>
<br>
 <?php  if (($iMode==2)||($iMode==4)){	
	echo "<tr>";
    echo "<td valign=bottom align=center width=190><img border=\"0\" height=\"70\"  src=\"ttd_ketua.jpg\">";
	echo "<font size=\"2\"><br><u> ".jabatanpengurus(61)."</u><br>Ketua Majelis<br><br><br><br><br><br><br><br></td>";
	echo "<td valign=bottom align=center width=190><br><br><br><br><img border=\"0\" width=\"190\" src=\"ttd_pdt1.jpg\">";
	echo "<font size=\"2\"><br><u> ".jabatanpengurus(1)."</u><br>Pendeta Jemaat</td>";
	echo "<td valign=bottom align=center ><img border=\"0\" height=\"90\" src=\"ttd_sekre1.jpg\">";
	echo "<font size=\"2\"><br><u> ".jabatanpengurus(65)."</u><br>Sekretaris Majelis<br><br><br><br><br><br><br><br></td>";
	echo "</tr>";
	}else if (($iMode==21)||($iMode==41)){	
	echo "<tr>";
    echo "<td valign=bottom align=center ><img border=\"0\"  height=\"80\"  src=\"ttd_ketua.jpg\"></td><td ></td>";
	echo "</tr>";
	}else if (($iMode==22)||($iMode==42)){	
	echo "<tr>";
    echo "<td valign=bottom align=center ></td>";
	echo "<td></td>";
	echo "<td valign=bottom align=center ><img border=\"0\"  height=\"80\"  src=\"ttd_sekre1.jpg\"></td>";
	echo "</tr>";
	}else{
	 echo "<tr>";
	 echo "<td></td><td></td>"; 
	 echo "</tr>";
	}	

?>	

  </table>
  
  </td></tr>
  </table>

</table>
