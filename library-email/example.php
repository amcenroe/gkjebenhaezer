<form action="" method="post">
  <button type="submit" name="submit">Click Me</button>
</form>

<?php





	require_once('function.php');
	if(isset($_POST['submit']))
	{

//status / url / recurid /

$domain = 'exchangecore.com';
$TanggalPF = '2016-03-25';
$WaktuPF = '09:00';
$TglMulai = date("Ymd", strtotime($TanggalPF));
$JamMulai = str_replace('.', '', substr($WaktuPF, 0, 5));
$startTime = $TglMulai."".$JamMulai;
$Durasi=3;
$JamAkhir = date('Hi', strtotime($JamMulai)+(60*60*$Durasi));
$endTime = $TglMulai."".$JamAkhir;

$to = array();
array_push($to, 'e_pratama@yahoo.com');
array_push($to, 'e.pratama@gmail.com');

$from_name = 'Erwin';
$from_address = 'sekretariat@gkjbekasitimur.org';
$to_name = 'ewing';
$to_address = $to;

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
$messageIcal .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST\n';
$messageIcal .= "Content-Transfer-Encoding: 8bit\n\n";
$messageIcal .= $ical;

//$cc = array();
//array_push($cc, 'e_pratama1@yahoo.com');
//array_push($cc, 'e.pratama2@gmail.com');

 //   $to       = 'e_pratama@yahoo.com; e_pratama@gmail.com';
    $subject  = 'Subject Pengiriman Email Uji Coba';
    $message  = '<p>Isi dari Email Testing</p>';
//  smtp_mail($to, $subject, $message, $from_name, $from, $cc, $bcc, $messageIcal, $debug=false) 
    smtp_mail($to, $subject, $message, ''       , ''    , $cc, 0, $messageIcal, true);
    
    /*
      jika menggunakan fungsi mail biasa : mail($to, $subject, $message);
      dapat juga menggunakan fungsi smtp secara dasar : smtp_mail($to, $subject, $message);
      jangan lupa melakukan konfigurasi pada file function.php
    */
	}
?>