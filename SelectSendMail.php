<?php
/*******************************************************************************
 *
 *  filename    : SelectSendMail
 *  last change : 2012-01-07
 *  copyright   : 
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2012 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";

// Security: User must have Delete records permission
// Otherwise, re-direct them to the main menu.
if (!$_SESSION['bDeleteRecords'])
{
	Redirect("Menu.php");
	exit;
}
// ambil variable dari luar
if (!empty($_GET["MailID"])) $iMailID = FilterInput($_GET["MailID"],'int');
if (!empty($_GET["PelayanFirmanID"])) $iPelayanFirmanID = FilterInput($_GET["PelayanFirmanID"],'int');
if (!empty($_GET["PerjamuanID"])) $iPerjamuanID = FilterInput($_GET["PerjamuanID"],'int');

if (!empty($_GET["mode"])) $sMode = $_GET["mode"];


// Redirect kalo cancel
if ($_GET["CancelMail"]){
	Redirect("MailView.php?MailID=$iMailID");
	exit;
}
if ($_GET["CancelSuratMasuk"]){
	Redirect("MailView.php?MailID=$iMailID");
	exit;
}
if ($_GET["CancelPelayanFirman"]){
	Redirect("SelectListApp.php?mode=JadwalPelayanFirman");
	exit;
}
if ($_GET["CancelPerjamuanTamu"]){
	Redirect("SelectListApp.php?mode=Perjamuan");
	exit;
}


//Konfirmasi Kirim Email
if($sMode == 'SuratMasuk')
	{$sPageTitle = gettext("Konfirmasi Kirim Surat Elektronik");
	$logvar1 = "Email";
	$logvar2 = "Incoming Mail Notification Confirmation";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}	
elseif($sMode == 'PelayanFirman')
	{$sPageTitle = gettext("Konfirmasi Kirim Email ke Pelayan Firman");
	$logvar1 = "Email";
	$logvar2 = "Konfirmasi Kirim Email ke Pelayan Firman";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPelayanFirmanID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}
elseif($sMode == 'PerjamuanTamu')
	{$sPageTitle = gettext("Konfirmasi Kirim Email Perjamuan Tamu ke Gereja Asal");
	$logvar1 = "Email";
	$logvar2 = "Konfirmasi Kirim Email Berita Perjamuan Tamu ke Gereja Asal";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iPelayanFirmanID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
	}



// Function kirim Email Surat Masuk
	
function EmailSuratMasuk($iMailID)
{

// Read values from config table into local variables
// **************************************************
$sSQL = "SELECT cfg_name, IFNULL(cfg_value, cfg_default) AS value FROM config_cfg WHERE cfg_value LIKE '%gkj%'";
$rsConfig = mysql_query($sSQL);			// Can't use RunQuery -- not defined yet
if ($rsConfig) {
    while (list($cfg_name, $value) = mysql_fetch_row($rsConfig)) {
        $$cfg_name = $value;
    }
}	

// Get this mail's data
$sSQL = "SELECT a.* ,
b.vol_name as Jabatan1, d.p2vo_per_id as perIDJab1, f.per_email as EmailJab1, f.per_Firstname as Pejabat1,
c.vol_name as Jabatan2, e.p2vo_per_id as perIDJab2, g.per_email as EmailJab2, g.per_Firstname as Pejabat2
FROM SuratMasuk a
LEFT JOIN volunteeropportunity_vol b ON a.Bidang1 = b.vol_ID
LEFT JOIN volunteeropportunity_vol c ON a.Bidang2 = c.vol_ID

LEFT JOIN person2volunteeropp_p2vo d ON a.Bidang1 = d.p2vo_vol_id
LEFT JOIN person2volunteeropp_p2vo e ON a.Bidang2 = e.p2vo_vol_id

LEFT JOIN person_per f ON d.p2vo_per_id = f.per_ID
LEFT JOIN person_per g ON e.p2vo_per_id = g.per_ID

WHERE MailID = " . $iMailID;
		 
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));


//Pesan
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));


//Sending email

    switch ($Urgensi)
          {
          case 1:
            $Urgensi="Sangat Segera";
            break;
          case 2:
            $Urgensi="Segera";
            break;
		  case 3:
            $Urgensi="Biasa";
            break;
          }
		  
	switch ($Ket3)
          {
          case 11:
            $Ket3="Informasi Umum";
            break;
          case 12:
            $Ket3="Surat Edaran";
            break;
		  case 13:
            $Ket3="Undangan";
            break;
		  case 14:
            $Ket3="Laporan Kegiatan";
            break;
			
		  case 21:
            $Ket3="Permohonan Umum";
            break;
		  case 22:
            $Ket3="Permohonan Bantuan";
            break;
		  case 23:
            $Ket3="Permohonan Pelayanan Firman";
            break;
		  case 24:
            $Ket3="Permohonan Peminjaman Asset Gereja";
            break;
		  case 25:
            $Ket3="Permohonan Pelayanan Gerejawi (Baptis/Sidi/Nikah/dll)";
            break;

		  case 31:
            $Ket3="Surat Pindah/Atestasi";
            break;
		  case 32:
            $Ket3="Surat Pemberitahuan Sakramen";
            break;
		  case 33:
            $Ket3="Surat Penitipan Rohani";
            break;
		}
		

//define the receiver of the email
//$to = emailpengurus(1). ",".emailpengurus(61). ",".emailpengurus(65) ;

$emailpendeta1 = emailpengurus(1,1);
$emailpendeta2 = emailpengurus(1,2);
$emailketua = emailpengurus(61);
$emailsekre1 = emailpengurus(65); 

//$to="e_pratama@yahoo.com";
$to=array();	
array_push($to, $emailketua);
//array_push($to, $EmailInstitusi);	

$cc=array();
array_push($cc, $emailpendeta1);
array_push($cc, $emailpendeta2);
//array_push($cc, $emailketua);
array_push($cc, $emailsekre1);
array_push($cc, $EmailJab1);
array_push($cc, $EmailJab2);



//define the subject of the email
//$subject = "[GKJ Bekti](Notifikasi SuratMasuk)No Reg: $MailID tanggal $Ket1 , Hal:$Hal " ;
$subject = "[$sChurchInitial](Notifikasi SuratMasuk)No Reg: $MailID tanggal $Ket1 , Hal:$Hal " ;
//define the message to be sent. Each line should be separated with \n
//$sChurchName = GKJ Bekasi Timur
$message = "Selamat Siang Bp/Ibu/Sdr/i\n\n<br><br>
Email ini adalah notifikasi dari Sekretariat $sChurchName berkenaan dengan surat masuk berikut ini :\n <br>
 -	Tanggal Surat: $Tanggal <br>
 -	Tanggal Diterima : $Ket1 <br>
 -	Urgensi: $Urgensi  <br>
 -	Dari : $Dari - $Institusi <br>
 -	Kepada : $Kepada <br>
 -	Nomor Surat : $NomorSurat <br>
 -	Hal : $Hal <br>
 -	Lampiran : $Lampiran <br>
 -	TypeLampiran : $TypeLampiran <br>
 -	Deskripsi Singkat isi Surat : $Ket2 <br>
 -	Kategori : $Ket3\n <br>
	<br>
Distribusi Email :  <br>
 -	Pendeta ( ".emailpengurus(1,1).", ".emailpengurus(1,2)." ), <br> 
 -	Ketua Majelis ( ".emailpengurus(61,1)." ), <br>
 -	Sekretaris ( ".emailpengurus(65,1)." ),  <br>
 -	$Jabatan1 ( $Pejabat1 / $EmailJab1 ),  <br>
 -	$Jabatan2 ( $Pejabat2 / $EmailJab2) <br>
	<br>
Atas perhatiannya kami ucapkan terima kasih.\n\n <br>
Salam <br>
\n\n
<br>
Catatan: Email ini dikirim langsung dari system secara otomatis, 
bagi bapak/ibu/sdr/i yang membutuhkan berkas asli surat bisa menghubungi sekretariat	";
			
//$sChurchEmail = sekretariat@gkjbekasitimur.org	
//define the headers we want passed. Note that they are separated with \r\n
$headers = "From: $sChurchEmail\r\nReply-To: $sChurchEmail\r\n";

$headers .= "Cc: ".$EmailJab1. "\r\n";
$headers .= "Cc: ".$EmailJab2. "\r\n";

// Logger
	$logvar1 = "Email";
	$logvar2 = "Email Notifikasi Surat Masuk Sent to ".emailpengurus(1,1). ",".emailpengurus(1,2). ",".emailpengurus(61,1). ",".emailpengurus(65,1). ",".$EmailJab1. ",". $EmailJab2;
	
	//$logvar2 = $message ;
	
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iMailID . "','" . $Dari."-".$Institusi."','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

//send the email
//$mail_sent = @mail( $to, $subject, $message, $headers );
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
//return $mail_sent ? "Email Anda sudah terkirim" : "Mail failed";		

//send the email v2

	require_once('library-email/function.php');
//smtp_mail($to, $subject, $message, $from_name, $from, $cc, $bcc, $debug=false) 
$mail_sent = @smtp_mail($to, $subject, $message, ''	, ''	, $cc, 0, 0, false);

//echo $cc;
return $mail_sent ? "Email Anda sudah terkirim" : "Mail failed";	   

		
}

	
	
	
// Function kirim Email Permohonan Pelayan Firman
	
function EmailPelayanFirman($iPelayanFirmanID)
{
// Read values from config table into local variables
// **************************************************
$sSQL = "SELECT cfg_name, IFNULL(cfg_value, cfg_default) AS value FROM config_cfg WHERE cfg_value LIKE '%gkj%'";
$rsConfig = mysql_query($sSQL);			// Can't use RunQuery -- not defined yet
if ($rsConfig) {
    while (list($cfg_name, $value) = mysql_fetch_row($rsConfig)) {
        $$cfg_name = $value;
    }
}

$sSQL = "SELECT a . * , b . * , c . * , d . *,e.*, a.Bahasa as Bahasa
FROM JadwalPelayanFirman a
LEFT JOIN DaftarPendeta b ON a.PelayanFirman = b.PendetaID
LEFT JOIN LiturgiGKJBekti c ON a.TanggalPF = c.Tanggal AND a.Bahasa = c.Bahasa
LEFT JOIN LokasiTI d ON a.KodeTI = d.KodeTI
LEFT JOIN DaftarGerejaGKJ e ON b.GerejaID = e.GerejaID 
		 WHERE PelayanFirmanID = " . $iPelayanFirmanID;

		 
			$perintah = mysql_query($sSQL);
				$i = 0;
				$kelpk = " ";
					while ($hasilGD=mysql_fetch_array($perintah))
						{
						$i++;
						extract($hasilGD);
						$EmailInstitusi=$hasilGD[Email];
						$EmailPendeta=$hasilGD[EmailPendeta];
						$NamaPendeta=$hasilGD[NamaPendeta];
						$NamaGereja=$hasilGD[NamaGereja];

				}
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
				

//Pesan
$message = "<html><body>";
$message .= "";

//$message .= "	<table border=\"0\"  width=\"600\" cellspacing=0 cellpadding=0>";
//$message .= "	<tr><td><font size=2><b><u><br></u></b></font>"; 
//$sChurchCity = Bekasi
$message .= "	<table border=\"0\"  width=\"80%\">";

$message .= "<tr><td valign=top>Nomor Surat:</td><td><font size=\"2\">  " . $PelayanFirmanID . "e/MG/".$sChurchCode."/".dec2roman(date (m)). "/".date('Y')."</font></td>";
  
$message .= "<td>$sChurchCity, </td><td>".tanggalsekarang()." </td></tr>";
$message .= "<tr><td valign=top>Hal:</td><td><font size=\"2\"> Permohonan Pelayanan Firman <br>".$Hal."</font></td></tr>";
$message .= "<tr><td><font color=#FFFFFF>.</font></td></tr>";
$message .= "<tr><td valign=top colspan=2 >Kepada YTH</td><td><font size=\"2\"></font></td></tr>";	
 if ($PelayanFirman<>"0"){ 
$message .= "<tr><td valign=top colspan=2 ><font size=\"2\"><b> Majelis " . $NamaGereja . "</b></td><td><font size=\"2\"></font></td></tr>";
$message .= "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $NamaGereja . "</font></td><td><font size=\"2\"></font></td></tr>";		   
$message .= "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat1 . "</font></td><td><font size=\"1\"></font></td></tr>";	
   if ($Alamat2<>""){
$message .= "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat2 . "</font></td><td><font size=\"1\"></font></td></tr>";}else{};	
   if ($Alamat3<>""){
$message .= "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat3 . "</font></td><td><font size=\"1\"></font></td></tr>";}else{};
$message .= "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> Telp " . $Telp . " ,Fax " . $Fax . "</font></td><td><font size=\"1\"></font></td></tr>";	

   }
else {
$message .= "<tr><td valign=top colspan=2 ><font size=\"2\"><b> " . $Salutation . " " . $PFnonInstitusi . "</b></td><td><font size=\"2\"></font></td></tr>";
$message .= "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF>.</font> Ditempat</font></td><td><font size=\"2\"></font></td></tr>";		   
   }
   
     
$message .= "<tr><td><font color=#FFFFFF>.</font></td></tr>";
$message .= "<br>";
  
$message .= "  </table>";
$message .= "  <br>";

$message .= "  <table border=\"0\"  width=\"100%\">";
//$sChurchFullName = Gereja Kristen Jawa Bekasi Timur   
$message .= "<tr><td valign=top colspan=\"4\"><font size=\"2\"><i>Salam Sejahtera dalam kasih Tuhan Yesus Kristus,</i></font></td>"; 
$message .= "<tr><td valign=top colspan=\"4\"><font size=\"2\"></font></td>"; 
  if ($PelayanFirman<>"0"){ 
$message .= "<tr><td valign=top colspan=\"4\"><font size=\"2\"><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"80\" height=\"1\" >  
  Majelis $sChurchFullName dengan ini mohon kesediaan <b>Majelis " . $NamaGereja . "</b>
  berkenan mengijinkan <b>" . $Salutation . " " .$NamaPendeta . " </b> untuk melayani <b>Pemberitaan Firman ".$Hal."</b> di $sChurchFullName yang dilaksanakan :</font></td>";
	}else{
$message .= "<tr><td valign=top colspan=\"4\"><font size=\"2\"><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"10\" height=\"1\" >  
  Majelis $sChurchFullName dengan ini mohon kesediaan <b>" . $Salutation . " " .$PFnonInstitusi . " </b>
  untuk melayani  Pemberitaan Firman di $sChurchFullName yang dilaksanakan :</font></td>"; 
  }
    $batas=";";
  if(strlen($Bacaan1)>0){$Bacaan1=$Bacaan1.";";}else{echo ""; } ;
  if(strlen($Bacaan2)>0){$Bacaan2=$Bacaan2.";";}else{echo ""; } ;
  if(strlen($BacaanAntara)>0){$BacaanAntara=$BacaanAntara.";";}else{echo ""; } ;
  if(strlen($BacaanInjil)>0){$BacaanInjil=$BacaanInjil.";";}else{echo ""; } ;
$message .= "<tr><td valign=top ><font size=\"2\"> Hari, tgl.  </td><td>:</td><td align=left><b> " .date2Ind($TanggalPF,1)." </b></td></font>";	

if ( $WaktuKedua != NULL ){
   $WaktuPF2=  " dan " . $WaktuKedua ;
} 
  if ($Bahasa=="AltSore"){$Bahasa="Indonesia";}else{$Bahasa=$Bahasa;}; 
$message .= "<tr><td valign=top><font size=\"2\">  Waktu </td><td>:</td><td align=left><b> " . $WaktuPF . "" .$WaktuPF2. " </b></td></font>";


$message .= "<tr><td valign=top><font size=\"2\">  Tempat </td><td>:</td><td align=left><b> TI. " . $NamaTI . " </b></td></font></td>";
$message .= "<tr><td valign=top><font size=\"2\">  Alamat </td><td>:</td><td align=left><b> " . $AlamatTI1 . "," .$AlamatTI2 . " </b></td></font>";	
$message .= "<tr><td valign=top><font size=\"2\">  Bahasa </td><td>:</td><td align=left><b> " . $Bahasa . " </b></td></font>";	
$message .= "<tr><td valign=top><font size=\"2\">  Tema </td><td>:</td><td align=left><b><i> \"" . $Tema . "\" </i></b></td></font>";	

$message .= "<tr><td valign=top><font size=\"2\">  Bacaan </td><td>:</td><td align=left><b> " . $Bacaan1 . " " . $BacaanAntara . " " . $Bacaan2 . " " .$BacaanInjil . " </b></td></font>";	  
$message .= "<tr><td></td></tr>";
$message .= "<tr><td></td></tr>";
$message .= "<tr><td valign=top colspan=\"4\"><font size=\"2\"> Demikian permohonan ini kami sampaikan, atas kesediaan dan pelayanannya kami ucapkan terima kasih. Tuhan memberkati.</font></td>";

$message .= "   </table><table  border=\"0\" width=\"100%\">";
$message .= "  <tr><td align=center colspan=\"2\"> $sChurchCity,  " .tanggalsekarang()."</td><td></td></tr>";
$message .= "   <tr><td align=center  colspan=\"2\">  Teriring Salam dan Doa Kami, </td><td></td></tr>";
$message .= "   <tr><td align=center  colspan=\"2\"><b>  Majelis $sChurchFullName </b></td><td></td></tr>";

$message .= "<br><br>";

if (($iMode==2)||($iMode==4)){	
$message .= "<tr>";
$message .= "<td valign=bottom align=center ><img border=\"0\" src=\"ttd_ketua.jpg\"></td><td valign=bottom align=center ><img border=\"0\" src=\"ttd_sekre1.jpg\"></td>";
$message .= "</tr>";
	}else{
$message .= "<tr>";
$message .= "<td></td><td></td>"; 
$message .= "</tr>";
	}	

$message .= " <tr>";
$message .= "  <td valign=bottom align=center width=\"50%\" ";
if (($iMode==2)||($iMode==4)){	 
$message .= "style=\"height:1px\""; }else{ 
$message .= "style=\"height:80px\""; }

$message .= "  ><u>" .jabatanpengurus(61). "</u></td><td valign=bottom align=center ><u>" .jabatanpengurus(65). "</u></td>";
$message .= "  </tr>  ";
$message .= "   <tr>
  <td valign=bottom align=center width=\"50%\">Ketua Majelis</td><td align=center >Sekretaris</td>
  </tr>  ";
 
 		if ($PelayanFirman == 0){
		$NamaPendeta = $PFnonInstitusi; 
		$NamaGereja = $PFNIAlamat ;
		$EmailPendeta = $PFNIEmail;}
 
 $message .= " <tr><td valign=bottom align=center colspan=\"2\" style=\"height:50px\">";
 $message .= "  <u>" .jabatanpengurus(1). "</u></td><td></td></tr>
  <tr><td align=center colspan=\"2\">Pendeta Jemaat</td><td></td></tr>
  </table>
  </table>  
  </td>
  </tr>
<br>
</td></tr>
<br>
Tembusan : <br>
- Arsip <br>
- ".$NamaPendeta."/ " .$EmailPendeta."<br>
- ".$NamaGereja."/ " .$EmailInstitusi."<br>
- ".jabatanpengurus(1)."/ " .emailpengurus(1,1)."<br>
- ".jabatanpengurus(61)."/ " .emailpengurus(61,1)."<br>
- ".jabatanpengurus(65)."/ " .emailpengurus(65,1)."<br>
</table></body></html>";

//Kirim Email
//define the receiver of the email
		if ($PelayanFirman == 0){
		$NamaPendeta = $PFnonInstitusi; 
		$NamaGereja = $PFNIAlamat ;
		$EmailPendeta = $PFNIEmail;}

//if ($EmailInstitusi == ''){$EmailInstitusi = '';}else{$EmailInstitusi = ', $EmailInstitusi';}

$to=array();	
array_push($to, $EmailPendeta);
array_push($to, $EmailInstitusi);	
//$to = "$EmailPendeta $EmailInstitusi";

//test
//$to = "" .emailpengurus(65)."";

//define the subject of the email
//$subject = "[GKJ Bekti]Permohonan Pelayanan Firman" ;
$subject = "[$sChurchInitial]Permohonan Pelayanan Firman" ;
//define the message to be sent. Each line should be separated with \n
//define the headers we want passed. Note that they are separated with \r\n
$headers = "From: $sChurchEmail\r\nReply-To: $sChurchEmail\r\n";

$headers .= "Cc: ".emailpengurus(1,1) . "\r\n";
$headers .= "Cc: ".emailpengurus(1,2) . "\r\n";
$headers .= "Cc: ".emailpengurus(61,1) . "\r\n";
$headers .= "Cc: ".emailpengurus(65,1) . "\r\n";
//$headers .= "Cc: e.pratama@gmail.com \r\n";

$emailpendeta1 = emailpengurus(1,1);
$emailpendeta2 = emailpengurus(1,2);
$emailketua = emailpengurus(61,1);
$emailsekre1 = emailpengurus(65,1); 

$cc=array();
array_push($cc, $emailpendeta1);
array_push($cc, $emailpendeta2);
array_push($cc, $emailketua);
array_push($cc, $emailsekre1);


// Always set content-type when sending HTML email
$headers .= "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";




// Logger
	$logvar1 = "Email";
	$logvar2 = "Email Sent to ".$EmailInstitusi. "/ ". $EmailPendeta;
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPelayanFirmanID . "','" . $NamaGereja . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);


//send the calendar		
//define the receiver of the email
$from_name = "Sekretariat $sChurchName" ;
$from_address = "$sChurchEmail";
$to_name = $NamaPendeta;
$to_address = $to;

$TglMulai = date("Ymd", strtotime($TanggalPF));
$JamMulai = str_replace('.', '', substr($WaktuPF, 0, 5));
$startTime = $TglMulai."".$JamMulai;

$Durasi=3;
if ( $WaktuKedua != NULL ){ $Durasi=6;}; 
  if ($Bahasa=="AltSore"){$Bahasa="Indonesia";}else{$Bahasa=$Bahasa;}; 
$JamAkhir = date('Hi', strtotime($JamMulai)+(60*60*$Durasi));
$endTime = $TglMulai."".$JamAkhir;
$subject = "Pelayanan Firman di $sChurchName, " . $WaktuPF . "" .$WaktuPF2. " di "  . $NamaTI;
$description = "Deskripsi Kegiatan Pelayanan Firman di $sChurchName<br>";
$description .= "Waktu : " . $WaktuPF . "" .$WaktuPF2. "<br>";
$description .= "Tempat : TI. " . $NamaTI . "<br> ";
$description .= "Alamat : " . $AlamatTI1 . "," .$AlamatTI2 . "<br> ";	
$description .= "Bahasa : " . $Bahasa . "<br> ";	
$description .= "Tema : " . $Tema . "<br> ";	
$description .= "Bacaan : " . $Bacaan1 . ", " . $BacaanAntara . ", " . $Bacaan2 . ", " .$BacaanInjil . "<br> ";	  

$location = $NamaTI ;


//send the calender to customer

//sendIcalEvent($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description , $location);


$ical = 'BEGIN:VCALENDAR' . "\r\n" .
'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
'VERSION:2.0' . "\r\n" .
'METHOD:REQUEST' . "\r\n" .
'BEGIN:VTIMEZONE' . "\r\n" .
'TZID:Asia/Jakarta' . "\r\n" .
'BEGIN:STANDARD' . "\r\n" .
'DTSTART:20091101T020000' . "\r\n" .
'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
'TZOFFSETFROM:+0700' . "\r\n" .
'TZOFFSETTO:+0700' . "\r\n" .
'TZNAME:JMT' . "\r\n" .
'END:STANDARD' . "\r\n" .
'BEGIN:DAYLIGHT' . "\r\n" .
'DTSTART:20090301T020000' . "\r\n" .
'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
'TZOFFSETFROM:+0700' . "\r\n" .
'TZOFFSETTO:+0700' . "\r\n" .
'TZNAME:JMT' . "\r\n" .
'END:DAYLIGHT' . "\r\n" .
'END:VTIMEZONE' . "\r\n" .	
'BEGIN:VEVENT' . "\r\n" .
'ORGANIZER;CN="'.$from_name.'":MAILTO:'.$from_address. "\r\n" .
'ATTENDEE;CN="'.$to_name.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$to_address. "\r\n" .
'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
'UID:'.date("Ymd\TGis", strtotime($startTime)).rand()."@".$domain."\r\n" .
'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
'DTSTART;TZID="Asia/Jakarta":'.date("Ymd\THis", strtotime($startTime)). "\r\n" .
'DTEND;TZID="Asia/Jakarta":'.date("Ymd\THis", strtotime($endTime)). "\r\n" .
'TRANSP:OPAQUE'. "\r\n" .
'SEQUENCE:1'. "\r\n" .
'SUMMARY:' . $subject . "\r\n" .
'LOCATION:' . $location . "\r\n" .
'CLASS:PUBLIC'. "\r\n" .
'PRIORITY:5'. "\r\n" .
'BEGIN:VALARM' . "\r\n" .
'TRIGGER:-P2D' . "\r\n" .
'ACTION:DISPLAY' . "\r\n" .
'DESCRIPTION:'  . $description .  "\r\n" .
'END:VALARM' . "\r\n" .
'END:VEVENT'. "\r\n" .
'END:VCALENDAR'. "\r\n";

require_once('library-email/function.php');

$subject = "Pelayanan Firman " . $NamaPendeta . ", " . $WaktuPF . "" .$WaktuPF2. " di "  . $NamaTI;
//$to_name = 'e_pratama@yahoo.com';

$mail_sent = @smtp_mail($to_name, $subject, $message, $from_name	, $to_name , 0 , 0, $ical, false);
//return $mail_sent2 ? "Email Anda sudah terkirim" : "Mail failed";	

//send the calender to internal calendar
$from_address = "$sChurchEmail";
$to_address = "$sChurchEmail";

//sendIcalEvent($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description , $location);
		
//send the email
//$mail_sent = @mail( $to, $subject, $message, $headers );
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
//return $mail_sent ? "Email Anda sudah terkirim" : "Mail failed";		

//send the email v2

//$to = 'e_pratama@yahoo.com';
//smtp_mail($to, $subject, $message, $from_name, $from, $cc, $bcc, $debug=false) 
$mail_sent = @smtp_mail($to, $subject, $message, ''	, ''	, 0, $cc, 0, false);

//echo $cc;
return $mail_sent ? "Email Anda sudah terkirim" : "Mail failed";	   
    /*
      jika menggunakan fungsi mail biasa : mail($to, $subject, $message);
      dapat juga menggunakan fungsi smtp secara dasar : smtp_mail($to, $subject, $message);
      jangan lupa melakukan konfigurasi pada file function.php
    */

		
}


// Function kirim Email
	
function EmailPerjamuanTamu($iPerjamuanID)
{

// Read values from config table into local variables
// **************************************************
$sSQL = "SELECT cfg_name, IFNULL(cfg_value, cfg_default) AS value FROM config_cfg WHERE cfg_value LIKE '%gkj%'";
$rsConfig = mysql_query($sSQL);			// Can't use RunQuery -- not defined yet
if ($rsConfig) {
    while (list($cfg_name, $value) = mysql_fetch_row($rsConfig)) {
        $$cfg_name = $value;
    }
}

$sSQL = "SELECT a.*,a.Telp as TelpTamu,  b.*, c.* 

		FROM PerjamuanKudusTamugkjbekti  a
		LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI 
		LEFT JOIN DaftarGerejaGKJ c ON a.GerejaID = c.GerejaID
		 WHERE PerjamuanID = " . $iPerjamuanID;

		 
			$perintah = mysql_query($sSQL);
				$i = 0;
				$kelpk = " ";
					while ($hasilGD=mysql_fetch_array($perintah))
						{
						$i++;
						extract($hasilGD);
						$EmailInstitusi=$hasilGD[Email];
						$EmailPendeta=$hasilGD[EmailPendeta];
						$NamaPendeta=$hasilGD[NamaPendeta];
						$NamaGereja=$hasilGD[NamaGereja];

				}

//Pesan
						$time  = strtotime($TanggalPerjamuan);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat =  $PerjamuanID."e/SKPT/".$sChurchCode."/".dec2roman($month)."/".$year;
						
$message = "<html><body>";
$message .= "";
$message .= "
<p>Nomor Surat:&nbsp;&nbsp; &nbsp;".$NomorSurat." &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
$sChurchCity,&nbsp;  ". tanggalsekarang() ."<br />
Hal:&nbsp;&nbsp; &nbsp;Perjamuan Kudus Tamu<br /><br />
Kepada YTH&nbsp;&nbsp; &nbsp;<br />Majelis " .$NamaGereja. "&nbsp;&nbsp; &nbsp;<br />
" .$NamaGereja. "&nbsp;&nbsp; &nbsp;<br />
" .$Alamat1 . "&nbsp;&nbsp; &nbsp;<br />
" .$Alamat2 . "&nbsp;&nbsp; &nbsp;<br />
" .$Alamat3 . "&nbsp;&nbsp; &nbsp;<br />
Telp " . $Telp . ",Fax " . $Fax . "&nbsp;&nbsp; &nbsp;<br />Email :" . $Email . "<br /><br />
&nbsp; Salam Sejahtera dalam kasih Tuhan Yesus Kristus,<br />
Majelis $sChurchFullName dengan ini memberitahukan bahwa Saudara yang namanya tersebut di bawah ini 
yang menurut pernyataannya adalah anggota Warga Jemaat " .$NamaGereja. "<br />
&nbsp;&nbsp; &nbsp;Nama&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;: " .$Nama. "&nbsp;&nbsp; &nbsp;<br />
&nbsp;&nbsp; &nbsp;Alamat &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; : " .$Alamat. "&nbsp;&nbsp; &nbsp;<br />
&nbsp;&nbsp; &nbsp;Telp &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; : " .$Telp. " &nbsp;&nbsp; &nbsp;<br />
Telah mengikuti Perjamuan Kudus sebagai Tamu yang kami selenggarakan pada :<br />
&nbsp;&nbsp; &nbsp;Hari, Tanggal : " .date2Ind($TanggalPerjamuan,1)." &nbsp;&nbsp; &nbsp;<br />
&nbsp;&nbsp; &nbsp;Tempat &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; : $sChurchName, ".$NamaTI." &nbsp;&nbsp; &nbsp;<br />
&nbsp;&nbsp; &nbsp;Waktu &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; : " .$JamPerjamuan. "&nbsp;&nbsp; &nbsp;<br />
Demikian pemberitahuan ini kami sampaikan, atas perhatiannya kami ucapkan terima kasih. Kiranya Tuhan Yesus Kristus senantiasa memberkati pelayanan kita.<br /><br /><br />
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 
$sChurchCity,      &nbsp;&nbsp; &nbsp;<br />&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;
Teriring Salam dan Doa Kami, &nbsp;&nbsp; &nbsp;<br />&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;
Majelis $sChurchFullName &nbsp;&nbsp; &nbsp;</p>
<p><br />&nbsp;&nbsp; &nbsp;<br />
".jabatanpengurus(61)."&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;
".jabatanpengurus(65)."<br />
Ketua Majelis&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;
Sekretaris</p>
<p><br />&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;
".jabatanpengurus(1)."&nbsp;&nbsp; &nbsp;<br />&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;
Pendeta Jemaat</p>


</body></html>";

//Kirim Email
//define the receiver of the email
		
$to = "" .$EmailPendeta.", ".$EmailInstitusi."";
//test
//$to = "" .emailpengurus(65)."";

//define the subject of the email
//$subject = "[GKJ Bekti]Pelayanan Perjamuan atas diri " .$Nama. " pada " .date2Ind($TanggalPerjamuan,1);
$subject = "[$sChurchInitial]Pelayanan Perjamuan atas diri " .$Nama. " pada " .date2Ind($TanggalPerjamuan,1);
//define the message to be sent. Each line should be separated with \n
//define the headers we want passed. Note that they are separated with \r\n
$headers = "From: $sChurchEmail\r\nReply-To: $sChurchEmail\r\n";

$headers .= "Cc: ".emailpengurus(1,1) . "\r\n";
$headers .= "Cc: ".emailpengurus(1,2) . "\r\n";
$headers .= "Cc: ".emailpengurus(61,1) . "\r\n";
$headers .= "Cc: ".emailpengurus(65,1) . "\r\n";
//$headers .= "Cc: e.pratama@gmail.com \r\n";

// Always set content-type when sending HTML email
$headers .= "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";






// Logger
	$logvar1 = "Email";
	$logvar2 = "[PerjamuanTamu]Email Sent to ".$EmailInstitusi. "/". $EmailPendeta;
	
	//$logvar2 = $message ;
	
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPerjamuanID . "','" . $NamaGereja . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

//send the email
$mail_sent = @mail( $to, $subject, $message, $headers );
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
return $mail_sent ? "Email Anda sudah terkirim" : "Mail failed";		

		
}


function DeleteMail($iMailID)
{
	
	// Delete the Mail record
	$sSQL = "DELETE FROM SuratMasuk WHERE MailID = " . $iMailID;
	RunQuery($sSQL);

	// Delete the doc files, if they exist
	$photoThumbnail = "Images/Mail/thumbnails/" . $iMailID . ".jpg";
	if (file_exists($photoThumbnail))
		unlink ($photoThumbnail);
	$photoFile = "Images/Mail/" . $iMailID . ".jpg";
	if (file_exists($photoFile))
		unlink ($photoFile);

	$logvar1 = "Delete";
	$logvar2 = "Incoming Mail Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iMailID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

}


//Do we have sending email confirmation?
if (isset($_GET["Confirmed"]))
{
	if ($sMode == 'person')
	{
		// Delete Person
		// Make sure this person is not a user
		$sSQL = "SELECT '' FROM user_usr WHERE usr_per_ID = " . $iPersonID;
		$rsUser = RunQuery($sSQL);
		$bIsUser = (mysql_num_rows($rsUser) > 0);

		if (!$bIsUser)
		{
			DeletePerson($iPersonID);

			// Redirect back to the list
			Redirect("SelectList.php?mode=person");
		}
	}
	elseif ($sMode == 'SuratMasuk')
	{
		// Kirim Email ke Distribusi Internal
			EmailSuratMasuk($iMailID);

			// Redirect back to the list
			Redirect("MailView.php?MailID=".$iMailID);
	
	}
	elseif ($sMode == 'PelayanFirman')
	{
		// Kirim Email ke Pelayan Firman
			EmailPelayanFirman($iPelayanFirmanID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=JadwalPelayanFirman");
	
	}
	elseif ($sMode == 'PerjamuanTamu')
	{
		// Kirim Email ke Pelayan Firman
			EmailPerjamuanTamu($iPerjamuanID);

			// Redirect back to the list
			Redirect("SelectListApp.php?mode=Perjamuan");
	
	}
	
	else
	{
		// Delete Family
		// Delete all associated Notes associated with this Family record
		$sSQL = "DELETE FROM note_nte WHERE nte_fam_ID = " . $iFamilyID;
		RunQuery($sSQL);

		// Delete Family pledges
		$sSQL = "DELETE FROM pledge_plg WHERE plg_PledgeOrPayment = 'Pledge' AND plg_FamID = " . $iFamilyID;
		RunQuery($sSQL);

		// Remove family property data
		$sSQL = "SELECT pro_ID FROM property_pro WHERE pro_Class='f'";
		$rsProps = RunQuery($sSQL);

		while($aRow = mysql_fetch_row($rsProps)) {
			$sSQL = "DELETE FROM record2property_r2p WHERE r2p_pro_ID = " . $aRow[0] . " AND r2p_record_ID = " . $iFamilyID;
			RunQuery($sSQL);
		}

		if (isset($_GET["Members"]))
		{
			// Delete all persons that were in this family

			$sSQL = "SELECT per_ID FROM person_per WHERE per_fam_ID = " . $iFamilyID;
			$rsPersons = RunQuery($sSQL);
			while($aRow = mysql_fetch_row($rsPersons))
			{
				DeletePerson($aRow[0]);
			}
		}
		else
		{
			// Reset previous members' family ID to 0 (undefined)
			$sSQL = "UPDATE person_per SET per_fam_ID = 0 WHERE per_fam_ID = " . $iFamilyID;
			RunQuery($sSQL);
		}

		// Delete the specified Family record
		// Backup First
		$sSQL = "INSERT INTO family_pindah SELECT * FROM family_fam WHERE fam_ID = " . $iFamilyID;
		RunQuery($sSQL);

		$sSQL = "DELETE FROM family_fam WHERE fam_ID = " . $iFamilyID;
		RunQuery($sSQL);

		// Remove custom field data
		$sSQL = "DELETE FROM family_custom WHERE fam_ID = " . $iFamilyID;
		RunQuery($sSQL);

		// Delete the photo files, if they exist
		$photoThumbnail = "Images/Family/thumbnails/" . $iFamilyID . ".jpg";
		if (file_exists($photoThumbnail))
			unlink ($photoThumbnail);
		$photoFile = "Images/Family/" . $iFamilyID . ".jpg";
		if (file_exists($photoFile))
			unlink ($photoFile);

	$logvar1 = "Delete";
	$logvar2 = "Family Deleted";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, fam_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $iFamilyID . "','" . $logvar1 . "','" . $logvar2 . "')";	//Execute the SQL
		RunQuery($sSQL);

		// Redirect back to the family listing
		Redirect("SelectList.php?mode=family");
	}
}



if($sMode == 'person')
{
	// Get the data on this person
	$sSQL = "SELECT per_FirstName, per_LastName FROM person_per WHERE per_ID = " . $iPersonID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

	// See if this person is a user
	$sSQL = "SELECT '' FROM user_usr WHERE usr_per_ID = " . $iPersonID;
	$rsUser = RunQuery($sSQL);
	$bIsUser = (mysql_num_rows($rsUser) > 0);
}

elseif($sMode == 'SuratMasuk')
{
	// Get the data on this person
	$sSQL = "SELECT a.* ,
b.vol_name as Jabatan1, d.p2vo_per_id as perIDJab1, f.per_email as EmailJab1, f.per_Firstname as Pejabat1,
c.vol_name as Jabatan2, e.p2vo_per_id as perIDJab2, g.per_email as EmailJab2, g.per_Firstname as Pejabat2
FROM SuratMasuk a
LEFT JOIN volunteeropportunity_vol b ON a.Bidang1 = b.vol_ID
LEFT JOIN volunteeropportunity_vol c ON a.Bidang2 = c.vol_ID

LEFT JOIN person2volunteeropp_p2vo d ON a.Bidang1 = d.p2vo_vol_id
LEFT JOIN person2volunteeropp_p2vo e ON a.Bidang2 = e.p2vo_vol_id

LEFT JOIN person_per f ON d.p2vo_per_id = f.per_ID
LEFT JOIN person_per g ON e.p2vo_per_id = g.per_ID

WHERE MailID = " . $iMailID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}
elseif($sMode == 'PelayanFirman')
{
	// Ambil Data Daftar pelayan Firman 
$sSQL = "SELECT a . * , b . * , c . * , d . *, e.* , a.Bahasa as Bahasa
FROM JadwalPelayanFirman a
LEFT JOIN DaftarPendeta b ON a.PelayanFirman = b.PendetaID
LEFT JOIN LiturgiGKJBekti c ON a.TanggalPF = c.Tanggal AND a.Bahasa = c.Bahasa
LEFT JOIN LokasiTI d ON a.KodeTI = d.KodeTI
LEFT JOIN DaftarGerejaGKJ e ON b.GerejaID = e.GerejaID
		 WHERE PelayanFirmanID = " . $iPelayanFirmanID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}

elseif($sMode == 'PerjamuanTamu')
{
	// Ambil Data Daftar Perjamuan Tamu

	
$sSQL = "SELECT a.*,a.Telp as TelpTamu,  b.*, c.* 

		FROM PerjamuanKudusTamugkjbekti  a
		LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI 
		LEFT JOIN DaftarGerejaGKJ c ON a.GerejaID = c.GerejaID
		 WHERE PerjamuanID = " . $iPerjamuanID;
	$rsPerson = RunQuery($sSQL);
	extract(mysql_fetch_array($rsPerson));

}


else
{
	//Get the family record in question
	$sSQL = "SELECT * FROM family_fam WHERE fam_ID = " . $iFamilyID;
	$rsFamily = RunQuery($sSQL);
	extract(mysql_fetch_array($rsFamily));
}

require "Include/Header.php";

//Tampilkan Konfirmasi di Layar

if($sMode == 'person')
{

	if ($bIsUser) {
		echo "<p class=\"LargeText\">" . gettext("Maaf ,Jemaat ini berstatus sebagai USER, silahkan hapus USER dahulu melalui Administrator.") . "<br><br>";
		echo "<a href=\"PersonView.php?PersonID=" . $iPersonID . "\">" . gettext("KEMBALI ke Data Jemaat")."</a></p>";
	}
	else
	{
		echo "<p>" . gettext("Silahkan dikonfirmasi Penghapusan dari:") . "</p>";
		echo "<p class=\"ShadedBox\">" . $per_FirstName . " " . $per_LastName . "</p>";
		echo "<BR>";
		echo "<p>" . gettext("Silahkan Buat Surat Pengantar terlebih dahuluu, sebelum data ini akan dihapus:") . "</p>";
		echo "<p><h3><a target=\"_blank\" href=\"PrintViewMove.php?PersonID=" . $iPersonID . "  \">" . gettext("Buat Surat Pindah") . "</a></h2></p>";
		echo "<p><h3><a target=\"_blank\" href=\"PrintViewRip.php?PersonID=" . $iPersonID . "  \">" . gettext("Buat Surat Meninggal") . "</a></h2></p>";
		echo "<BR>";
		echo "<p><h2><blink><font color=red >" . gettext("Perhatian! Setelah dihapus tidak bisa direcovery") . "</font></blink></h2></p>";
		echo "<p><h3><a href=\"SelectDelete.php?mode=person&PersonID=" . $iPersonID . "&Confirmed=Yes\">" . gettext("YA, Silahkan HAPUS data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"PersonView.php?PersonID=" . $iPersonID . "\">" . gettext("TIDAK, Batalkan penghapusan data") . "</a></h2></p>";
	}
}
elseif($sMode == 'SuratMasuk')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Pengiriman Surat Elektronil (Email) Data Surat Masuk dari:") . "</p>";
		echo "<p class=\"ShadedBox\">Nomor Surat : " . $NomorSurat . ". Dari : " . $Dari . " - " . $Institusi . "</p>";
		echo "<BR>";
// Get this mail's data
$sSQL = "SELECT a.* ,
b.vol_name as Jabatan1, d.p2vo_per_id as perIDJab1, f.per_email as EmailJab1, f.per_Firstname as Pejabat1,
c.vol_name as Jabatan2, e.p2vo_per_id as perIDJab2, g.per_email as EmailJab2, g.per_Firstname as Pejabat2
FROM SuratMasuk a
LEFT JOIN volunteeropportunity_vol b ON a.Bidang1 = b.vol_ID
LEFT JOIN volunteeropportunity_vol c ON a.Bidang2 = c.vol_ID

LEFT JOIN person2volunteeropp_p2vo d ON a.Bidang1 = d.p2vo_vol_id
LEFT JOIN person2volunteeropp_p2vo e ON a.Bidang2 = e.p2vo_vol_id

LEFT JOIN person_per f ON d.p2vo_per_id = f.per_ID
LEFT JOIN person_per g ON e.p2vo_per_id = g.per_ID

WHERE MailID = " . $iMailID;
		 
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

$emailpendeta1 = emailpengurus(1,1);
$emailpendeta2 = emailpengurus(1,2);
$emailketua = emailpengurus(61,1);
$emailsekre1 = emailpengurus(65,1); 

//$to="e_pratama@yahoo.com";
$to=array();	
array_push($to, $emailketua);
//array_push($to, $EmailInstitusi);	

$cc=array();
array_push($cc, $emailpendeta1);
array_push($cc, $emailpendeta2);
//array_push($cc, $emailketua);
array_push($cc, $emailsekre1);
array_push($cc, $EmailJab1);
array_push($cc, $EmailJab2);

echo "
 -	Tanggal Surat: $Tanggal <br>
 -	Tanggal Diterima : $Ket1 <br>
 -	Urgensi: $Urgensi  <br>
 -	Dari : $Dari - $Institusi <br>
 -	Kepada : $Kepada <br>
 -	Nomor Surat : $NomorSurat <br>
 -	Hal : $Hal <br>
 -	Lampiran : $Lampiran <br>
 -	TypeLampiran : $TypeLampiran <br>
 -	Deskripsi Singkat isi Surat : $Ket2 <br>
 -	Kategori : $Ket3\n <br>
	<br>

Distribusi Email :  <br>
 -	Pendeta ( ".emailpengurus(1,1)." , ".emailpengurus(1,2)."  ), <br> 
 -	Ketua Majelis ( ".emailpengurus(61,1)." ), <br>
 -	Sekretaris ( ".emailpengurus(65,1)." ),  <br>
 -	$Jabatan1 ( $Pejabat1 / $EmailJab1 ),  <br>
 -	$Jabatan2 ( $Pejabat2 / $EmailJab2) <br>
	<br>";


		echo "<p><h3><a href=\"SelectSendMail.php?mode=SuratMasuk&MailID=" . $iMailID . "&Confirmed=Yes\">" . gettext("YA, Silahkan KIRIM data ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"MailView.php?MailID=" . $iMailID . "\">" . gettext("TIDAK, Batalkan pengiriman EMAIL") . "</a></h2></p>";

}
elseif($sMode == 'PelayanFirman')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Pengiriman Surat Elektronik (Email) berikut ini:") . "</p>";
		if ($PelayanFirman == 0){
		$NamaPendeta = $PFnonInstitusi; 
		$NamaGereja = $PFNIAlamat ;
		$Email = $PFNIEmail;}
		echo "<p class=\"ShadedBox\">Nama Pendeta : " . $NamaPendeta . ". Institusi : " . $NamaGereja . "</p>";echo "<BR>";
		echo "<p class=\"ShadedBox\">Email Tujuan : " . $Email . "," . $EmailPendeta . "</p>";echo "<BR>";
		
//		echo "<p>" . gettext("Email Preview:") . "</p>";
		

//$homepage = file_get_contents("http://www.gkjbekasitimur.org/datawarga/PrintViewPermohonanPF.php?PelayanFirmanID=".$iPelayanFirmanID."&Mode=1");
//echo "<p>".$homepage."</p>";


		
		echo "<p><h3><a href=\"SelectSendMail.php?mode=PelayanFirman&PelayanFirmanID=" . $iPelayanFirmanID . "&Confirmed=Yes\">" . gettext("YA, Silahkan KIRIM email ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=JadwalPelayanFirman\">" . gettext("TIDAK, Batalkan pengiriman EMAIL") . "</a></h2></p>";

}

elseif($sMode == 'PerjamuanTamu')
{
		echo "<p>" . gettext("Silahkan dikonfirmasi Pengiriman Surat Elektronik (Email) berikut ini:") . "</p>";
		echo "<p class=\"ShadedBox\">Kepada : Majelis " . $NamaGereja . "</p>";echo "<BR>";
		echo "<p class=\"ShadedBox\">Email Tujuan : " . $Email . "</p>";echo "<BR>";
		
		echo "<p><h3><a href=\"SelectSendMail.php?mode=PerjamuanTamu&PerjamuanID=" . $iPerjamuanID . "&Confirmed=Yes\">" . gettext("YA, Silahkan KIRIM email ini") . "</a>" .  "</h3></p>";
		echo "<p><h2><a href=\"SelectListApp.php?mode=Perjamuan\">" . gettext("TIDAK, Batalkan pengiriman EMAIL") . "</a></h2></p>";

}


else
{
	// Delete Family Confirmation
	// See if this family has any donations
	$sSQL = "SELECT plg_plgID FROM pledge_plg WHERE plg_PledgeOrPayment = 'Payment' AND plg_FamID = " . $iFamilyID;
	$rsDonations = RunQuery($sSQL);
	$bIsDonor = (mysql_num_rows($rsDonations) > 0);
	if ($bIsDonor && !$_SESSION['bFinance']) {
		// Donations from Family. Current user not authorized for Finance
		echo "<p class=\"LargeText\">" . gettext("Sorry, there are records of donations from this family. This family may not be deleted.") . "<br><br>";
		echo "<a href=\"FamilyView.php?FamilyID=" . $iFamilyID . "\">" . gettext("Return to Family View") . "</a></p>";

	} elseif ($bIsDonor && $_SESSION['bFinance']) {
		// Donations from Family. Current user authorized for Finance.
		// Select another family to move donations to.
		echo "<p class=\"LargeText\">" . gettext("WARNING: This family has records of donations and may NOT be deleted until these donations are associated with another family.") . "</p>";
		echo "<form name=SelectFamily method=get action=SelectDelete.php>";
		echo "<div class=\"ShadedBox\">";
		echo "<div class=\"LightShadedBox\"><strong>" . gettext("Family Name:") . " $fam_Name</strong></div>";
		echo "<p>" . gettext("Please select another family with whom to associate these donations:");
		echo "<br><b>".gettext("WARNING: This action can not be undone and may have legal implications!")."</b></p>";
		echo "<input name=FamilyID value=$iFamilyID type=hidden>";
		echo "<select name=DonationFamilyID><option value=0 selected>". gettext("Unassigned") ."</option>";

		//Get Families for the drop-down
		$sSQL = "SELECT fam_ID, fam_Name, fam_Address1, fam_City, fam_State FROM family_fam ORDER BY fam_Name";
		$rsFamilies = RunQuery($sSQL);
		// Build Criteria for Head of Household
		if (!$sDirRoleHead)
			$sDirRoleHead = "1";
		$head_criteria = " per_fmr_ID = " . $sDirRoleHead;
		// If more than one role assigned to Head of Household, add OR
		$head_criteria = str_replace(",", " OR per_fmr_ID = ", $head_criteria);
		// Add Spouse to criteria
		if (intval($sDirRoleSpouse) > 0)
			$head_criteria .= " OR per_fmr_ID = $sDirRoleSpouse";
		// Build array of Head of Households and Spouses with fam_ID as the key
		$sSQL = "SELECT per_FirstName, per_fam_ID FROM person_per WHERE per_fam_ID > 0 AND (" . $head_criteria . ") ORDER BY per_fam_ID";
		$rs_head = RunQuery($sSQL);
		$aHead = "";
		while (list ($head_firstname, $head_famid) = mysql_fetch_row($rs_head)){
			if ($head_firstname && $aHead[$head_famid])
				$aHead[$head_famid] .= " & " . $head_firstname;
			elseif ($head_firstname)
				$aHead[$head_famid] = $head_firstname;
		}
		while ($aRow = mysql_fetch_array($rsFamilies)){
			extract($aRow);
			echo "<option value=\"" . $fam_ID . "\"";
			if ($fam_ID == $iFamilyID) { echo " selected"; }
			echo ">" . $fam_Name;
			if ($aHead[$fam_ID])
				echo ", " . $aHead[$fam_ID];
			if ($fam_ID == $iFamilyID)
				echo " -- " . gettext("CURRENT FAMILY WITH DONATIONS");
			else
				echo " " . FormatAddressLine($fam_Address1, $fam_City, $fam_State);
		}
		echo "</select><br><br>";
		echo "<input type=submit name=CancelFamily value=\"Cancel and Return to Family View\"> &nbsp; &nbsp; ";
		echo "<input type=submit name=MoveDonations value=\"Move Donations to Selected Family\">";
		echo "</div></form>";

		// Show payments connected with family
		// -----------------------------------
		echo "<br><br>";
		//Get the pledges for this family
		$sSQL = "SELECT plg_plgID, plg_FYID, plg_date, plg_amount, plg_schedule, plg_method,
		         plg_comment, plg_DateLastEdited, plg_PledgeOrPayment, a.per_FirstName AS EnteredFirstName, a.Per_LastName AS EnteredLastName, b.fun_Name AS fundName
				 FROM pledge_plg
				 LEFT JOIN person_per a ON plg_EditedBy = a.per_ID
				 LEFT JOIN donationfund_fun b ON plg_fundID = b.fun_ID
				 WHERE plg_famID = " . $iFamilyID . " ORDER BY pledge_plg.plg_date";
		$rsPledges = RunQuery($sSQL);
		?>
		<table cellpadding="5" cellspacing="0" width="100%">
			<tr class="TableHeader">
			<td><?php echo gettext("Type"); ?></td>
			<td><?php echo gettext("Fund"); ?></td>
			<td><?php echo gettext("Fiscal Year"); ?></td>
			<td><?php echo gettext("Date"); ?></td>
			<td><?php echo gettext("Amount"); ?></td>
			<td><?php echo gettext("Schedule"); ?></td>
			<td><?php echo gettext("Method"); ?></td>
			<td><?php echo gettext("Comment"); ?></td>
			<td><?php echo gettext("Date Updated"); ?></td>
			<td><?php echo gettext("Updated By"); ?></td>
		</tr>
		<?php
		$tog = 0;
		//Loop through all pledges
		while ($aRow =mysql_fetch_array($rsPledges)){
			$tog = (! $tog);
			$plg_FYID = "";
			$plg_date = "";
			$plg_amount = "";
			$plg_schedule = "";
			$plg_method = "";
			$plg_comment = "";
			$plg_plgID = 0;
			$plg_DateLastEdited  = "";
			$plg_EditedBy = "";
			extract($aRow);

			//Alternate the row style
			if ($tog)
				$sRowClass = "RowColorA";
			else
				$sRowClass = "RowColorB";

			if ($plg_PledgeOrPayment == 'Payment') {
				if ($tog)
					$sRowClass = "PaymentRowColorA";
				else
					$sRowClass = "PaymentRowColorB";
			}
			?>
			<tr class="<?php echo $sRowClass ?>">
				<td><?php echo $plg_PledgeOrPayment ?>&nbsp;</td>
				<td><?php echo $fundName ?>&nbsp;</td>
				<td><?php echo MakeFYString ($plg_FYID) ?>&nbsp;</td>
				<td><?php echo $plg_date ?>&nbsp;</td>
				<td><?php echo $plg_amount ?>&nbsp;</td>
				<td><?php echo $plg_schedule ?>&nbsp;</td>
				<td><?php echo $plg_method; ?>&nbsp;</td>
				<td><?php echo $plg_comment; ?>&nbsp;</td>
				<td><?php echo $plg_DateLastEdited; ?>&nbsp;</td>
				<td><?php echo $EnteredFirstName . " " . $EnteredLastName; ?>&nbsp;</td>
			</tr>
			<?php
		}
		echo "</table>";


	} else {
		// No Donations from family.  Normal delete confirmation
		echo $DonationMessage;
		echo "<p>" . gettext("Silahkan Konfirmasi Penghapusan Data Keluarga ini:") . "</p>";
		echo "<p>" . gettext("Catatan: Hal ini akan menghapus semua catatan keluarga.") . "</p>";
		echo "<div class=\"ShadedBox\">";
		echo "<div class=\"LightShadedBox\"><strong>" . gettext("Nama Keluarga:") . "</strong></div>";
		echo "&nbsp;" . $fam_Name;
		echo "</div>";
		echo "<p class=\"MediumText\"><a href=\"SelectDelete.php?Confirmed=Yes&FamilyID=" . $iFamilyID . "\">" . gettext("HAPUS Data Keluarga saja") . "</a>" . gettext(" (Perhatian! Setelah dihapus tidak bisa direcovery)") . "</p>";
		echo "<div class=\"ShadedBox\">";
		echo "<div class=\"LightShadedBox\"><strong>" . gettext("Anggota Keluarga:") . "</strong></div>";
		//List Family Members
		$sSQL = "SELECT * FROM person_per WHERE per_fam_ID = " . $iFamilyID;
		$rsPerson = RunQuery($sSQL);
		while($aRow = mysql_fetch_array($rsPerson)) {
			extract($aRow);
			echo "&nbsp;" . $per_FirstName . " " . $per_LastName . "<br>";
			RunQuery($sSQL);
		}
		echo "</div>";
		echo "<p class=\"MediumText\"><a href=\"SelectDelete.php?Confirmed=Yes&Members=Yes&FamilyID=" . $iFamilyID . "\">" . gettext("HAPUS Data Keluarga DAN Anggota Keluarga") . "</a>" . gettext(" (Perhatian! Setelah dihapus tidak bisa direcovery)") . "</p>";
		echo "<br><p class=\"LargeText\"><a href=\"FamilyView.php?FamilyID=".$iFamilyID."\">" . gettext("TIDAK, Batalkan peng-hapusan!</a>") . "</p>";
	}
}


require "Include/Footer.php";
?>
