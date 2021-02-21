<?php
// Include the function library
require "Include/Config.php";
require "Include/Functions.php";


	
function EmailPelayanFirman($iPelayanFirmanID)
{


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

// echo $sSQL2; 

$rsPerson2 = RunQuery($sSQL2);

$num_rows = mysql_num_rows($rsPerson2);
if ($num_rows > 0 ) {
extract(mysql_fetch_array($rsPerson2));
}

//echo $sSQL2;
				
//Kirim Email
//define the receiver of the email
		if ($PelayanFirman == 0){
		$NamaPendeta = $PFnonInstitusi; 
		$NamaGereja = $PFNIAlamat ;
		$EmailPendeta = $PFNIEmail;}
		
//$to = "" .$EmailPendeta.", ".$EmailInstitusi."";

//send the calendar		
//define the receiver of the email
$from_name = "Sekretariat GKJ Bekasi Timur" ;
$from_address = "sekretariat@gkjbekasitimur.org";
$to_name = $NamaPendeta;
$to_address = "e_pratama@yahoo.com";

$TglMulai = date("Ymd", strtotime($TanggalPF));
$JamMulai = str_replace('.', '', substr($WaktuPF, 0, 5));
$startTime = $TglMulai."".$JamMulai;

$Durasi=3;
if ( $WaktuKedua != NULL ){ $Durasi=6;}; 

$JamAkhir = date('Hi', strtotime($JamMulai)+(60*60*$Durasi));
$endTime = $TglMulai."".$JamAkhir;
$subject = "Pelayanan Firman di GKJ Bekasi Timur, " . $WaktuPF . "" .$WaktuPF2. " di "  . $NamaTI;
$description = "Deskripsi Kegiatan Pelayanan Firman di GKJ Bekasi Timur<br>";
$description .= "Waktu : " . $WaktuPF . "" .$WaktuPF2. "<br>";
$description .= "Tempat : TI. " . $NamaTI . "<br> ";
$description .= "Alamat : " . $AlamatTI1 . "," .$AlamatTI2 . "<br> ";	
$description .= "Bahasa : " . $Bahasa . "<br> ";	
$description .= "Tema : " . $Tema . "<br> ";	
$description .= "Bacaan : " . $Bacaan1 . ", " . $BacaanAntara . ", " . $Bacaan2 . ", " .$BacaanInjil . "<br> ";	  

$location = $NamaTI ;


//send the calender to customer
sendIcalEvent($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description , $location);
//send the calender to internal calendar
$from_address = "calendar@gkjbekasitimur.org";
$to_address = "sekretariat@gkjbekasitimur.org";
$subject = "Pelayanan Firman " . $NamaPendeta . ", " . $WaktuPF . "" .$WaktuPF2. " di "  . $NamaTI;
sendIcalEvent($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description , $location);
		
		
//send the email
//$mail_sent = @mail( $to, $subject, $message, $headers );
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
//return $mail_sent ? "Email Anda sudah terkirim" : "Mail failed";		
		
}

$iPelayanFirmanID=658;
EmailPelayanFirman($iPelayanFirmanID);

//define the receiver of the email
$from_name = "Sekretariat GKJ Bekasi Timur" ;
$from_address = "event@gkjbekasitimur.org";
$to_name = "Nama Penerima Surat";
$to_address = "e_pratama@yahoo.com, sekretariat@gkjbekasitimur.org";
$TglMulai = date("Ymd", strtotime($TanggalPF));
$JamMulai = str_replace('.', '', substr($WaktuMulai, 0, 5));
//$JamMulai = date("His", substr($WaktuMulai, 0, 5));
$startTime = $TglMulai."".$JamMulai;
//$startTime = "201301300900";
//$endTime = "20130130130000";
$subject = "Test Kirim dari gkjbekasitimur.org" ;
$description = "Deskripsi Kalender" ;
$location ="TI Cut Meutia";

//send the email
//function sendIcalEvent($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description, $location)
//sendIcalEvent($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description , $location);
	
?>