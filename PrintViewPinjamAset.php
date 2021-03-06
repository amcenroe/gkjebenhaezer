<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPinjamAset.php
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
$iFormID = FilterInput($_GET["FormID"],'int');
$iPelayanFirmanID = FilterInput($_GET["PelayanFirmanID"],'int');
$iKopSurat = FilterInput($_GET["KopSurat"],'int');
$iMode = FilterInput($_GET["Mode"],'int');
$iWHO = FilterInput($_GET["WHO"]);
$iVCHID = FilterInput($_GET["VCHID"],'int');

 

// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}






?>
<table
 style="width: 650; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="width: 70px;"><img style="width: 60px; " src="gkj_logo.jpg" border="0"></td>
      <td style="width: 630px;"><b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo "$sChurchFullName"; ?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="1" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "$sChurchAddress"; ?></font>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="1" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "Telp:". $sChurchPhone . "- Fax: " .$sChurchFax." - Email:". $sChurchEmail;?></font>
	<br style="font-family: Arial; color: rgb(0, 0, 102);"><b style="font-family: Arial; color: rgb(0, 0, 102);">
	<hr style="width: 100%; height: 2px;">
	<font size="2"><big style="font-family: Arial;">
	<?php
	if ($iWHO=='JIF4'){
	echo "Tanda Terima Persembahan";
	}else if ($iWHO=='Cek'){
	echo "Permohonan Pencairan Cek";
	}else{
	echo "Kuitansi Pembayaran";} 
	?>
	</big><br></font></b>   
    </tr>
 </tbody>
<table
 style="width: 700; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="0" cellspacing="0">
 
 <?php 

 if ($iWHO=='SEHAT'){
 
	 
	$sSQL = "select a.*, b.*, c.*, 
		a.Keterangan as Rujukan, c.Keterangan as Tanggungan
	   FROM PengeluaranKlaimAsuransi	a
				LEFT JOIN person_per b ON a.PosAnggaranID = b.per_ID
				LEFT JOIN MasterAnggaranKlaimAsuransi c ON a.PosAnggaranID = c.KaryawanID 
		WHERE PengeluaranKlaimAsuransiID = " . $iVCHID;

				
// Set the page title and include HTML header
$sPageTitle = gettext("Cetak Kuitansi Klaim Asuransi no $iVCHID tanggal $Tanggal");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	//	 echo $sSQL; echo "<br>";
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));
 
 echo "<tr><td align=left valign=middle width=130px><font size=\"2\">Nomor Kuitansi </font></td>     <td>:</td><td  colspan=\"2\"><font size=\"2\"><i><b> ".$PengeluaranKlaimAsuransiID."/K-".$PosAnggaranID."/SEKR2/".$Tanggal."</b></i></font></td></tr>";
 echo "<tr><td></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Sudah diterima dari </font></td><td>:</td><td  colspan=\"2\"><font size=\"3\"><b> Sekretariat - $sChurchName <b></font></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Banyaknya uang </font></td>     <td>:</td><td colspan=\"2\">## <font size=\"3\"><b>".currency('Rp.',$Jumlah,'.',',00')."</b></font> ##</td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Terbilang </font></td>          <td>:</td><td colspan=\"2\">## <font FACE=\"LUCIDA HANDWRITING\" size=\"3\">".Terbilang($Jumlah)." rupiah </font> ##</td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Untuk Pembayaran </font></td>   <td>:</td><td colspan=\"2\"><i><font size=\"2\">Klaim Kesehatan utk Sdr/i.<b>".$Pasien."</b></i> </font></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Keterangan </font></td>   <td>:</td><td colspan=\"2\"><i>NamaKaryawan: <i><font size=\"2\">".$per_FirstName."</i></font></td></tr>";echo "<tr><td>";
 echo "<tr><td align=left valign=middle ><font size=\"2\"> </font></td>   <td></td><td colspan=\"2\"><i>Tgl.Perawatan : ".date2Ind($Tanggal,2).", di ".$Rujukan.", No.Kwitansi: ".$NomorKwitansi."</i></font></td></tr>";echo "<tr><td>";

 echo" </td>";
 echo "<td></td><td align=center><font size=\"2\"><br>Tgl: ................, ............................</font></td><td align=center><br>Tgl: ................, ............................</td></tr>";
 echo "<tr><td><font size=\"1\">*".$iVCHID."*" . date("YmdHis") ."*</font></td><td></td><td align=center><font size=\"2\">Yang Menyerahkan</font></td><td align=center>Yang Menerima</td></tr>";
 echo "<tr><td>";
 $width = $height = 80; 
 $url = urlencode("http://$sChurchWebsite/datawarga/PrintViewVoucher.php?VCHID=$iVCHID&iWHO=SEHAT"); 
 $error = "M"; // handle up to 30% data loss, or "L" (7%), "M" (15%), "Q" (25%) 
 $border = 0; 
 echo "<img src=\"http://chart.googleapis.com/chart?". "chs={$width}x{$height}&cht=qr&chld=$error|$border&chl=$url\" />"; 
 echo"</td>";
 echo "<td></td><td align=center><font size=\"2\"> </font></td><td></td></tr>";
 echo "<tr><td colspan=\"2\" align=\"left\"><font size=\"1\">*".$EnteredBy."*".$DateEntered."*<br>*".$EditedBy."*".$DateLastEdited."*<font></td><td align=center><font size=\"2\">(................................)</font></td><td align=center>( ".$per_FirstName." )</td></tr>";
 echo "<tr><td colspan=\"4\"><hr></td></tr>";
 

}else if ($iWHO=='PPPG'){
 
 //$sSQL = "select a.*, b.*, c.*, d.* FROM PengeluaranKasKecil	a
//		LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
//		LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
//		LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
//		 WHERE PengeluaranKasKecilID = " . $iVCHID;
		 
	$sSQL = "select a.*, a.Keterangan as KetTambahan , b.*, c.* FROM PengeluaranPPPG	a
		LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
		LEFT JOIN JenisPengeluaranPPPG c ON a.Pos = c.KodeJenis
		WHERE PengeluaranPPPGID = " . $iVCHID;

				
// Set the page title and include HTML header
$sPageTitle = gettext("Cetak Kuitansi no $iVCHID tanggal $Tanggal");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	//	 echo $sSQL; echo "<br>";
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));
 
 echo "<tr><td align=left valign=middle width=130px><font size=\"2\">Nomor Kuitansi </font></td>     <td>:</td><td  colspan=\"2\"><font size=\"2\"><i><b> ".$PengeluaranPPPGID."/POS-".$KodeJenis."/PPPG/".$Tanggal."</b></i></font></td></tr>";
 echo "<tr><td></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Sudah diterima dari </font></td><td>:</td><td  colspan=\"2\"><font size=\"3\"><b> PPPG - $sChurchName <b></font></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Banyaknya uang </font></td>     <td>:</td><td colspan=\"2\">## <font size=\"3\"><b>".currency('Rp.',$Jumlah,'.',',00')."</b></font> ##</td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Terbilang </font></td>          <td>:</td><td colspan=\"2\">## <font FACE=\"LUCIDA HANDWRITING\" size=\"3\">".Terbilang($Jumlah)." rupiah </font> ##</td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Untuk Pembayaran </font></td>   <td>:</td><td colspan=\"2\"><i><font size=\"2\"><b>".$Keperluan."</b></i></font></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Keterangan </font></td>   <td>:</td><td colspan=\"2\"><i><font size=\"2\">".$KetTambahan."</i></font></td></tr>";echo "<tr><td>";
  echo" </td>";
 echo "<td></td><td align=center><font size=\"2\"><br>Tgl: ................, ............................</font></td><td align=center><br>Tgl: ................, ............................</td></tr>";
 echo "<tr><td><font size=\"1\">*".$iVCHID."*" . date("YmdHis") ."*</font></td><td></td><td align=center><font size=\"2\">Yang Menyerahkan</font></td><td align=center>Yang Menerima</td></tr>";
 echo "<tr><td>";
 $width = $height = 80; 
 $url = urlencode("http://$sChurchWebsite/datawarga/PrintViewVoucher.php?VCHID=$iVCHID&iWHO=PPPG"); 
 $error = "M"; // handle up to 30% data loss, or "L" (7%), "M" (15%), "Q" (25%) 
 $border = 0; 
 echo "<img src=\"http://chart.googleapis.com/chart?". "chs={$width}x{$height}&cht=qr&chld=$error|$border&chl=$url\" />"; 
 echo"</td>";
 echo "<td></td><td align=center><font size=\"2\"> </font></td><td></td></tr>";
 echo "<tr><td colspan=\"2\" align=\"left\"><font size=\"1\">*".$EnteredBy."*".$DateEntered."*<br>*".$EditedBy."*".$DateLastEdited."*<font></td><td align=center><font size=\"2\">(................................)</font></td><td align=center>( ".$DiserahkanKepada." )</td></tr>";
 echo "<tr><td colspan=\"4\"><hr></td></tr>";
 

}else if ($iWHO=='JIF4'){
 
		$sSQL = "SELECT a.*, b.* FROM PersembahanPPPG a
			LEFT JOIN person_per b ON a.NomorKartu = b.per_ID
		WHERE PersembahanPPPGID = " . $iVCHID;

	
// Set the page title and include HTML header
$sPageTitle = gettext("Cetak Tanda Terima Persembahan no $iVCHID tanggal $Tanggal");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	//	 echo $sSQL; echo "<br>";
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));
 
 echo "<tr><td align=left valign=middle width=130px><font size=\"2\">Nomor Kuitansi </font></td>     <td>:</td><td  colspan=\"2\"><font size=\"2\"><i><b> ".$PersembahanPPPGID."/PJIF4/PPPG/".$Tanggal."</b></i></font></td></tr>";
 echo "<tr><td></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Sudah diterima dari </font></td><td>:</td><td  colspan=\"2\"><font size=\"3\">Bp/Ibu/Sdr/i.<b>  ".$per_FirstName." </b>(".$Kelompok.")</font></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Banyaknya uang </font></td>     <td>:</td><td colspan=\"2\">## <font size=\"3\"><b>".currency('Rp.',$Bulanan,'.',',00')."</b></font> ##</td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Terbilang </font></td>          <td>:</td><td colspan=\"2\">## <font FACE=\"LUCIDA HANDWRITING\" size=\"3\">".Terbilang($Bulanan)." rupiah </font> ##</td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Untuk  </font></td>   <td>:</td><td colspan=\"2\"><i><font size=\"3\"><b>Persembahan Janji Iman F4</b></i></font></td></tr>";
 ;echo "<tr><td>";
  echo" </td>";
 echo "<td></td><td align=center><font size=\"2\"><br>Tgl: ................, ............................</font></td><td align=center><br>$sChurchCity , ".date2Ind($Tanggal,2)."</td></tr>";
 echo "<tr><td><font size=\"1\">*".$iVCHID."*" . date("YmdHis") ."*</font></td><td></td><td align=center><font size=\"2\">Yang Menyerahkan</font></td><td align=center>Yang Menerima</td></tr>";
 echo "<tr><td>";
 $width = $height = 80; 
 $url = urlencode("http://$sChurchWebsite/datawarga/PrintViewVoucher.php?VCHID=$iVCHID&WHO=JIF4"); 
 $error = "M"; // handle up to 30% data loss, or "L" (7%), "M" (15%), "Q" (25%) 
 $border = 0; 
 echo "<img src=\"http://chart.googleapis.com/chart?". "chs={$width}x{$height}&cht=qr&chld=$error|$border&chl=$url\" />"; 
 echo"</td>";
 echo "<td></td><td align=center><font size=\"2\"> </font></td><td></td></tr>";
 echo "<tr><td colspan=\"2\" align=\"left\"><font size=\"1\">*".$EnteredBy."*".$DateEntered."*<br>*".$EditedBy."*".$DateLastEdited."*<font></td><td align=center><font size=\"2\"><u>(".$per_FirstName.")</u></font><br>".$Kelompok."-".$NomorKartu."-".$KodeNama."</td><td align=center><u>( ".jabatanpengurus(67)." )</u><br>Bendahara</td></tr>";
 echo "<tr><td colspan=\"4\"><hr></td></tr>";
 

}else if ($iWHO=='Cek'){
 
		$sSQL = "SELECT a.* FROM PencairanCek a
				WHERE PencairanCekID = " . $iVCHID;

	
// Set the page title and include HTML header
$sPageTitle = gettext("Cetak Permohonan Pencairan Cek no $iVCHID tanggal $Tanggal");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	//	 echo $sSQL; echo "<br>";
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));
 
 echo "<tr><td align=left valign=middle width=130px><font size=\"2\">Nomor Permohonan </font></td>     <td>:</td><td  colspan=\"2\"><font size=\"2\"><i><b> ".$PencairanCekID."/Cek/".$Tanggal."</b></i></font></td></tr>";
 echo "<tr><td></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Pencairan Cek No:</font></td><td>:</td><td  colspan=\"2\"><font size=\"3\"><b>  ".$NomorCek." </b></font></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Banyaknya uang </font></td>     <td>:</td><td colspan=\"2\">## <font size=\"3\"><b>".currency('Rp.',$Jumlah,'.',',00')."</b></font> ##</td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Terbilang </font></td>          <td>:</td><td colspan=\"2\">## <font FACE=\"LUCIDA HANDWRITING\" size=\"3\">".Terbilang($Jumlah)." rupiah </font> ##</td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Keterangan  </font></td>   <td>:</td><td colspan=\"2\"><i><font size=\"3\"><b>".$Keterangan."</b></i></font></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Detail   </font></td>   <td>:</td></tr>";
 
 if (strlen($Detail1)>0&&strlen($Nilai1)>0){
 echo "<tr><td align=right valign=middle ><font size=\"2\">-</font></td><td>:</td><td> ".$Detail1." </td><td>".currency('Rp.',$Nilai1,'.',',00')."</td></tr>";}
 if (strlen($Detail2)>0&&strlen($Nilai2)>0){
 echo "<tr><td align=right valign=middle ><font size=\"2\">-</font></td><td>:</td><td> ".$Detail2." </td><td>".currency('Rp.',$Nilai2,'.',',00')."</td></tr>";}
 if (strlen($Detail3)>0&&strlen($Nilai3)>0){
 echo "<tr><td align=right valign=middle ><font size=\"2\">-</font></td><td>:</td><td> ".$Detail3." </td><td>".currency('Rp.',$Nilai3,'.',',00')."</td></tr>";}
 if (strlen($Detail4)>0&&strlen($Nilai4)>0) {
 echo "<tr><td align=right valign=middle ><font size=\"2\">-</font></td><td>:</td><td> ".$Detail4." </td><td>".currency('Rp.',$Nilai4,'.',',00')."</td></tr>";}
 if (strlen($Detail5)>0&&strlen($Nilai5)>0){
 echo "<tr><td align=right valign=middle ><font size=\"2\">-</font></td><td>:</td><td> ".$Detail5." </td><td>".currency('Rp.',$Nilai5,'.',',00')."</td></tr>";}
 if (strlen($Detail6)>0&&strlen($Nilai6)>0){
 echo "<tr><td align=right valign=middle ><font size=\"2\">-</font></td><td>:</td><td> ".$Detail6." </td><td>".currency('Rp.',$Nilai6,'.',',00')."</td></tr>";}
 if (strlen($Detail7)>0&&strlen($Nilai7)>0){
 echo "<tr><td align=right valign=middle ><font size=\"2\">-</font></td><td>:</td><td> ".$Detail7." </td><td>".currency('Rp.',$Nilai7,'.',',00')."</td></tr>";}
 if (strlen($Detail8)>0&&strlen($Nilai1)>0){
 echo "<tr><td align=right valign=middle ><font size=\"2\">-</font></td><td>:</td><td> ".$Detail8." </td><td>".currency('Rp.',$Nilai8,'.',',00')."</td></tr>";}
 if (strlen($Detail9)>0&&strlen($Nilai9)>0){
 echo "<tr><td align=right valign=middle ><font size=\"2\">-</font></td><td>:</td><td> ".$Detail9." </td><td>".currency('Rp.',$Nilai9,'.',',00')."</td></tr>";}
 if(strlen($Detail10)>0&&strlen($Nilai10)>0){
 echo "<tr><td align=right valign=middle ><font size=\"2\">-</font></td><td>:</td><td> ".$Detail10." </td><td>".currency('Rp.',$Nilai10,'.',',00')."</td></tr>";}
 
  echo "<tr><td align=center >$sChurchCity , ".date2Ind($Tanggal,2)."</td><td></td><td align=center><font size=\"2\">Tgl: ................, ............................</font></td><td align=center>Tgl: ................, ............................</td></tr>";
 echo "<tr><td align=center  >Pemohon</td><td></td><td align=center><font size=\"2\">Pengesahan I</font></td><td align=center>Pengesahan II</td></tr>";
 echo "<tr><td height=\"50\">";
 
 echo"</td></tr>";
 echo "<tr><td align=center  >(.................................)</td><td></td><td align=center><u>( ".jabatanpengurus(61)." )</u><br>Ketua</td><td align=center><u>( ".jabatanpengurus(67)." )</u><br>Bendahara</td></tr>";
  echo "<tr><td colspan=\"4\"><hr></td></tr>";
 

}else{


$sSQL = "select a.*, b.*, c.*, d.* FROM PengeluaranKasKecil	a
		LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
		LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
		LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
		 WHERE PengeluaranKasKecilID = " . $iVCHID;
		 
// Set the page title and include HTML header
$sPageTitle = gettext("Cetak Kuitansi no $iVCHID tanggal $Tanggal");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	//	 echo $sSQL; echo "<br>";
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));
 
 
 
 
 echo "<tr><td align=left valign=middle width=130px><font size=\"2\">Nomor Kuitansi </font></td>     <td>:</td><td  colspan=\"2\"><font size=\"2\"><i><b> ".$PengeluaranKasKecilID."/POS-".$PosAnggaranID."/KOM-".$KomisiID."/BID-".$BidangID."/".$Tanggal."</b></i></font></td></tr>";
 echo "<tr><td></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Sudah diterima dari </font></td><td>:</td><td  colspan=\"2\"><font size=\"3\"><b> $sChurchName <b></font></td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Banyaknya uang </font></td>     <td>:</td><td colspan=\"2\">## <font size=\"3\"><b>".currency('Rp.',$Jumlah,'.',',00')."</b></font> ##</td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Terbilang </font></td>          <td>:</td><td colspan=\"2\">## <font FACE=\"LUCIDA HANDWRITING\" size=\"3\">".Terbilang($Jumlah)." rupiah </font> ##</td></tr>";
 echo "<tr><td align=left valign=middle ><font size=\"2\">Untuk Pembayaran </font></td>   <td>:</td><td colspan=\"2\"><i><font size=\"2\"><b>".$DeskripsiKas."</b></i></font></td></tr>";
 echo "<tr><td>";
  echo" </td>";
 echo "<td></td><td align=center><font size=\"2\"><br>Tgl: ................, ............................</font></td><td align=center><br>Tgl: ................, ............................</td></tr>";
 echo "<tr><td><font size=\"1\">*".$iVCHID."*" . date("YmdHis") ."*</font></td><td></td><td align=center><font size=\"2\">Yang Menyerahkan</font></td><td align=center>Yang Menerima</td></tr>";
 echo "<tr><td>";
 $width = $height = 70; 
 $url = urlencode("http://$sChurchWebsite/datawarga/PrintViewVoucher.php?VCHID=$iVCHID"); 
 $error = "L"; // handle up to 30% data loss, or "L" (7%), "M" (15%), "Q" (25%) 
 $border = 0; 
 echo "<img src=\"http://chart.googleapis.com/chart?". "chs={$width}x{$height}&cht=qr&chld=$error|$border&chl=$url\" />"; 
 echo"</td>";
 echo "<td></td><td align=center><font size=\"2\"> </font></td><td></td></tr>";
 echo "<tr><td colspan=\"2\" align=\"left\"><font size=\"1\">*".$EnteredBy."*".$DateEntered."*<br>*".$EditedBy."*".$DateLastEdited."*<font></td><td align=center><font size=\"2\">(................................)</font></td><td align=center>(................................)</td></tr>";
 echo "<tr><td colspan=\"4\"><hr></td></tr>";


}

// Log
	$logvar1 = "Print";
	$logvar2 = "Cetak Kuitansi No ".$iVCHID." - " .$Tanggal;
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $PengeluaranKasKecilID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
		
 ?>

</table>  
 
