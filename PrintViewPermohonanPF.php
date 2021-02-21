<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPermohonanPF.php
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
$iKopSurat = FilterInput($_GET["KopSurat"],'int');
$iMode = FilterInput($_GET["Mode"],'int');
$iTema = FilterInput($_GET["Tema"],'int');

$sSQL = "SELECT a.* , a.DateEntered as TglDibuat 
,b.*, b.Keterangan as KetPendeta , c . * , d . *,e.*, a.Bahasa as Bahasa
FROM JadwalPelayanFirman a
LEFT JOIN DaftarPendeta b ON a.PelayanFirman = b.PendetaID
LEFT JOIN LiturgiGKJBekti c ON a.TanggalPF = c.Tanggal AND a.Bahasa = c.Bahasa
LEFT JOIN LokasiTI d ON a.KodeTI = d.KodeTI
LEFT JOIN DaftarGerejaGKJ e ON b.GerejaID = e.GerejaID 
		 WHERE PelayanFirmanID = " . $iPelayanFirmanID;

//		 echo $sSQL; echo "<br>";
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));
$TglDibuat = substr($TglDibuat, 0, -9);

//Check Kalo Ada Jam yg kedua
if ($PFnonInstitusi<>'') {
$sSQL2 = "SELECT a.WaktuPF as WaktuKedua
FROM JadwalPelayanFirman a
LEFT JOIN DaftarPendeta b ON a.PelayanFirman = b.PendetaID
LEFT JOIN LiturgiGKJBekti c ON a.TanggalPF = c.Tanggal AND a.Bahasa = c.Bahasa
LEFT JOIN LokasiTI d ON a.KodeTI = d.KodeTI
LEFT JOIN DaftarGerejaGKJ e ON b.GerejaID = e.GerejaID 
		WHERE
TanggalPF = '" . $TanggalPF . "' AND a.KodeTI = '" . $KodeTI . "'  
AND PFnonInstitusi = '" . $PFnonInstitusi . "' 
AND a.WaktuPF <> '" . $WaktuPF . "' ";

}else{

$sSQL2 = "SELECT a.WaktuPF as WaktuKedua
FROM JadwalPelayanFirman a
LEFT JOIN DaftarPendeta b ON a.PelayanFirman = b.PendetaID
LEFT JOIN LiturgiGKJBekti c ON a.TanggalPF = c.Tanggal AND a.Bahasa = c.Bahasa
LEFT JOIN LokasiTI d ON a.KodeTI = d.KodeTI
LEFT JOIN DaftarGerejaGKJ e ON b.GerejaID = e.GerejaID 
		WHERE
TanggalPF = '" . $TanggalPF . "' AND a.KodeTI = '" . $KodeTI . "'  
AND PelayanFirman = '" . $PelayanFirman . "' 
AND a.WaktuPF <> '" . $WaktuPF . "' ";
}
// echo $sSQL2; 

$rsPerson2 = RunQuery($sSQL2);

$num_rows = mysql_num_rows($rsPerson2);
if ($num_rows > 0 ) {
extract(mysql_fetch_array($rsPerson2));
}

//echo $sSQL2;









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
	$logvar2 = "Cetak Surat Permohonan PF ".$TanggalPF." - " .$NamaPendeta."".$PFnonInstitusi. " - ".$NamaGereja;
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
						//$time  = strtotime($TanggalPF);
						//$TglDibuat = substr($TglDibuat, 0, -9);
						$time  = strtotime($TglDibuat);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
					
						$NomorSurat =  $PelayanFirmanID."e/MG/".$sChurchCode."/".dec2roman($month)."/".$year;
						
   echo "<tr><td valign=top width=\"80\" ><font size=\"2\">Nomor Surat</td><td colspan=\"2\"><font size=\"2\">: " . $NomorSurat . "</font></td>";
  echo "<td align=right width=\"170\"><font size=\"2\">".$sChurchCity.", "; 
  //echo tanggalsekarang() ; date ('Y' ); 
  echo date2Ind($TglDibuat,2) ;
  echo " </td></tr>";
  
   echo "<tr><td valign=top><font size=\"2\">Hal</td><td colspan=\"3\"><font size=\"2\">: Permohonan Pelayanan Firman ".$Hal."</font></td></tr>";
   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
   echo "<tr><td valign=top colspan=3 ><font size=\"2\">Kepada YTH</td><td><font size=\"2\"></font></td></tr>";	
 if (($PelayanFirman<>"0")&&($KetPendeta<>"PPK")){ 
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><b> Majelis " . $NamaGereja . "</b></td><td><font size=\"2\"></font></td></tr>";
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> " . $NamaGereja . "</font></td><td><font size=\"2\"></font></td></tr>";		   
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat1 . "</font></td><td><font size=\"1\"></font></td></tr>";	
   if ($Alamat2<>""){
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat2 . "</font></td><td><font size=\"1\"></font></td></tr>";}else{};	
   if ($Alamat3<>""){
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat3 . "</font></td><td><font size=\"1\"></font></td></tr>";}else{};
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> Telp " . $Telp . ",Fax " . $Fax . "</font></td><td><font size=\"1\"></font></td></tr>";	

   }
else  if (($PelayanFirman<>"0")&&($KetPendeta=="PPK")){ 
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><b> " . $Salutation . " " . $NamaPendeta . "</b></td><td><font size=\"2\"></font></td></tr>";
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> " . $NamaGereja . "</font></td><td><font size=\"2\"></font></td></tr>";		   
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat1 . "</font></td><td><font size=\"1\"></font></td></tr>";	
   if ($Alamat2<>""){
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat2 . "</font></td><td><font size=\"1\"></font></td></tr>";}else{};	
   if ($Alamat3<>""){
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat3 . "</font></td><td><font size=\"1\"></font></td></tr>";}else{};
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> Telp " . $Telp . ",Fax " . $Fax . "</font></td><td><font size=\"1\"></font></td></tr>";	

   }   
else {
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><b> " . $Salutation . " " . $PFnonInstitusi . "</b></td><td><font size=\"2\"></font></td></tr>";
   if ($PFNIAlamat==""){
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> Ditempat</font></td><td><font size=\"2\"></font></td></tr>";	
   }else{
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> ".$PFNIAlamat."</font></td><td><font size=\"2\"></font></td></tr>";	
   }
    if ($PFNIAlamat2==""){
   echo "";	
   }else{
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> ".$PFNIAlamat2."</font></td><td><font size=\"2\"></font></td></tr>";	
   }
   if ($PFNITelp==""){
   echo "";	
   }else{
   echo "<tr><td valign=top colspan=3><font size=\"2\"><font color=#FFFFFF></font> Telp: ".$PFNITelp."</font></td><td><font size=\"2\"></font></td></tr>";	
   }
   if ($PFNIFax==""){
   echo "";	
   }else{
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> Fax: ".$PFNIFax."</font></td><td><font size=\"2\"></font></td></tr>";	
   }   
    if ($PFNIEmail==""){  
   echo "";	   
    }else{
   echo "<tr><td valign=top colspan=3 ><font size=\"2\"><font color=#FFFFFF></font> Email: ".$PFNIEmail."</font></td><td><font size=\"2\"></font></td></tr>";	
   } 

  }
   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
	echo "<br>";
   ?>
  </table>

  <table border="0"  width="600">
  <?php 
  echo "<tr><td valign=top colspan=\"5\"><font size=\"2\"><i>Salam Sejahtera dalam kasih Tuhan Yesus Kristus,</i></font></td></tr>"; 

   //Pelayanan Firman GKJ
  if (($PelayanFirman<>"0")&&($KetPendeta<>"PPK")){ 
 echo "<tr><td valign=top colspan=\"5\"><font size=\"2\">  
  <p style=\"text-align:justify\">Majelis ".$sChurchFullName." dengan ini mohon kesediaan <b>Majelis " . $NamaGereja . "</b>
  berkenan mengijinkan <b>" . $Salutation . " " .$NamaPendeta . " </b> untuk melayani <b>Pemberitaan Firman ".$Hal."</b> di ".$sChurchFullName." yang dilaksanakan :</p></font></td></tr>";
	}else  if (($PelayanFirman=="0")&&($Gereja<>"Lain")){ 
 echo "<tr><td valign=top colspan=\"5\"><font size=\"2\">  
  <p style=\"text-align:justify\">Majelis ".$sChurchFullName." dengan ini mohon kesediaan <b>Majelis " . $PFNIAlamat . "</b>
  berkenan mengijinkan <b>" . $Salutation . " " .$PFnonInstitusi . " </b> untuk melayani <b>Pemberitaan Firman ".$Hal."</b> di ".$sChurchFullName." yang dilaksanakan :</p></font></td></tr>";
	}else if (($PelayanFirman<>"0")&&($KetPendeta=="PPK")){
 echo "<tr><td valign=top colspan=\"5\"><font size=\"2\">  
  <p style=\"text-align:justify\">Majelis ".$sChurchFullName." dengan ini mohon kesediaan <b>" . $Salutation . " " .$NamaPendeta . " </b>
  untuk melayani  Pemberitaan Firman ".$Hal." di ".$sChurchFullName." yang dilaksanakan pada:</p></font></td></tr>"; 
  }	else{
 echo "<tr><td valign=top colspan=\"5\"><font size=\"2\">  
  <p style=\"text-align:justify\">Majelis ".$sChurchFullName." dengan ini mohon kesediaan <b>" . $Salutation . " " .$PFnonInstitusi . " </b>
  untuk melayani  Pemberitaan Firman ".$Hal." di ".$sChurchFullName." yang dilaksanakan :</p></font></td></tr>"; 
  }
  
  echo "<tr><td width=\"30\" ></td><td valign=top width=\"50\" ><font size=\"2\"> Hari, tgl.  </td><td>:</td><td><font size=\"2\"><b> ";?>
  <?php echo date2Ind($TanggalPF,1);?><?php echo " </b></td></font></tr>";	
  echo "<tr><td><td valign=top><font size=\"2\">  Waktu </td><td>:</td><td><font size=\"2\"><b> " . $WaktuPF ;
  
if ( $WaktuKedua != NULL ){
    echo " dan " . $WaktuKedua ;
}  

  echo " </b></td></font></tr>";
  echo "<tr><td></td><td valign=top><font size=\"2\">  Tempat </td><td>:</td><td><font size=\"2\"><b> TI. " . $NamaTI . " </b></td></font></tr>";
  echo "<tr><td></td><td valign=top><font size=\"2\">  Alamat </td><td>:</td><td><font size=\"2\"><b> " . $AlamatTI1 . "," .$AlamatTI2 . " </b></td></font></tr>";	
  if ($Bahasa=="AltSore"){$Bahasa="Indonesia";}else{$Bahasa=$Bahasa;}; 
  echo "<tr><td></td><td valign=top><font size=\"2\">  Bahasa </td><td>:</td><td><font size=\"2\"><b> " . $Bahasa . " </b></td></font><td></tr>";	


  $batas=";";
  if(strlen($Bacaan1)>0){$Bacaan1=$Bacaan1.";";}else{echo ""; } ;
  if(strlen($Bacaan2)>0){$Bacaan2=$Bacaan2.";";}else{echo ""; } ;
  if(strlen($BacaanAntara)>0){$BacaanAntara=$BacaanAntara.";";}else{echo ""; } ;
  if(strlen($BacaanInjil)>0){$BacaanInjil=$BacaanInjil.";";}else{echo ""; } ;
  if ( $iTema != 1 ){
	echo "<tr><td></td><td valign=top><font size=\"2\">
	<a href=\"PrintViewPermohonanPF.php?PelayanFirmanID=$iPelayanFirmanID&Mode=$iMode&Tema=1\" style=\"color: #000000;text-decoration: none\">Tema</a>
	</td><td>:</td><td  colspan=\"2\"><b><font size=\"2\"><i> \"" . $Tema . "\" </i></b></td></font></tr>";	
	echo "<tr><td></td><td valign=top><font size=\"2\">  Bacaan </td><td>:</td><td colspan=\"2\"><font size=\"2\"><b> " . $Bacaan1 . " " . $BacaanAntara . " " . $Bacaan2 . " " .$BacaanInjil . " </b></td></font></tr>";	  
	}
  echo "<tr><td valign=top colspan=\"5\"><font size=\"2\"><p style=\"text-align:justify\"> Demikian permohonan ini kami sampaikan, atas kesediaan dan pelayanannya kami ucapkan terima kasih. Tuhan memberkati.</p></font></td></tr>";
  echo "<tr><td  valign=top colspan=\"5\"></tr>";
  
  

  
 ?>

   <table  border="0" width="600" align="top" >
   <tr><td align=center  valign=top colspan="3"><font size="2">  Teriring Salam dan Doa Kami, </td><td></td></tr>
   <tr><td align=center  colspan="3"><font size="2"><b>  MAJELIS <?php echo strtoupper($sChurchFullName);?> </b></td><td></td></tr>
<br>
 <?php  if (($iMode==2)||($iMode==4)){	
	echo "<tr>";
    echo "<td valign=bottom align=center ><img border=\"0\" height=\"80\"  src=\"ttd_ketua.jpg\"></td>";
	echo "<td></td>";
	echo "<td valign=bottom align=center ><img border=\"0\" height=\"80\" src=\"ttd_sekre1.jpg\"></td>";
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
 <tr>
  <td valign=bottom align=center width="33%" 
  <?php  if (($iMode==2)||($iMode==4)||($iMode==21)||($iMode==41)||($iMode==22)||($iMode==42)){	  
  echo "style=\"height:1px\""; }else{ 
  echo "style=\"height:140px\""; }
  ?>>
  <font size="2"><u><?php echo jabatanpengurus(61); ?></u><br>Ketua Majelis<br><br><br><br></td>
  <td width="34%" valign=bottom align=center ><font size="2"><u><?php echo jabatanpengurus(1); ?></u><br>Pendeta Jemaat</td>
  <td width="33%" valign=bottom align=center ><font size="2"><u><?php echo jabatanpengurus(65); ?></u><br>Sekretaris Majelis<br><br><br><br></td>
  </tr>  
   <tr><td align=left colspan=5 >
 <?php
 		if ($PelayanFirman == 0){
		$NamaPendeta = $PFnonInstitusi; 
		$NamaGereja = $PFNIAlamat ;
		$EmailPendeta = $PFNIEmail;}

echo "<br>";
echo "Tembusan : <br>";
echo "- ".$NamaPendeta."<br>";
echo "- ".$NamaGereja."<br>";
echo "- Arsip <br>";		
?>
</td></tr> 

  </table>
  
  </td></tr>

  </table>

</table>
