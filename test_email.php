<?php 
//define the receiver of the email
$to = "e_pratama@yahoo.com" ;

//define the subject of the email
$subject = "Test Email dari gkjbekasitimur.org" ;
//define the message to be sent. Each line should be separated with \n
$message = "Selamat Siang Bp/Ibu/Sdr/i\n\n
Email ini adalah notifikasi Test";
			
			
//define the headers we want passed. Note that they are separated with \r\n
$headers = "From: klasisjbt@gkj.or.id\r\nReply-To: klasisjbt@gkj.or.id\r\n";

//send the email
$mail_sent = @mail( $to, $subject, $message, $headers );

return $mail_sent ? "Email Anda sudah terkirim" : "Mail failed";	
	
?>