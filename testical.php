<?PHP
	INCLUDE ("Include/iCal.php");
?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iCal Test</title>
</head>
 
<body>
<?PHP

	$firstname = "Erwin";
	$lastname = "Pratama";
	$to = "e_pratama@yahoo.com";
	$meeting_date = "2016-03-06 18:40:00"; //mysql format
	$meeting_name = "Nama meeting";
	$meeting_duration = 3600;
 
	$from_name = "Sekretariat GKJ Bekasi Timur";
	$from = "sekretariat@gkjbekasitimur.com";
	$subject = "Meeting Booking"; //Doubles as email subject and meeting subject in calendar
	$meeting_description = "Here is a brief description of my meeting\n\n";
	$meeting_location = "My Office"; //Where will your meeting take place
 
 
	//Convert MYSQL datetime and construct iCal start, end and issue dates
	$meetingstamp = STRTOTIME($meeting_date . " UTC");    
	$dtstart= GMDATE("Ymd\THis\Z",$meetingstamp);
	$dtend= GMDATE("Ymd\THis\Z",$meetingstamp+$meeting_duration);
	$todaystamp = GMDATE("Ymd\THis\Z");
 
	//Create unique identifier
	$cal_uid = DATE('Ymd').'T'.DATE('His')."-".RAND()."@yahoo.com";


	$ical =    'BEGIN:VCALENDAR
	PRODID:-//Microsoft Corporation//Outlook 11.0 MIMEDIR//EN
	VERSION:2.0
	METHOD:PUBLISH
	BEGIN:VEVENT
	ORGANIZER:MAILTO:'.$from.'
	DTSTART:'.$dtstart.'
	DTEND:'.$dtend.'
	LOCATION:'.$meeting_location.'
	TRANSP:OPAQUE
	SEQUENCE:0
	UID:'.$cal_uid.'
	DTSTAMP:'.$todaystamp.'
	DESCRIPTION:'.$meeting_description.'
	SUMMARY:'.$subject.'
	PRIORITY:5
	CLASS:PUBLIC
	END:VEVENT
	END:VCALENDAR'; 


require_once('library-email/function.php');
//            smtp_mail($to, $subject, $message, $from_name, $from, $cc, $bcc, $ical, $debug=false) 
$mail_sent = @smtp_mail($to, $subject, $message, $from_name,  $from, $cc, 0, $ical, true);



	//display result
	IF($mail_sent) {
		ECHO "Email sent successfully.";

		echo "<br>"; echo $to;
		echo "<br>"; echo $subject;
		echo "<br>"; echo $ical;	
		echo "<br>"; echo $mail_sent;	

	} ELSE {
		ECHO "A problem occurred sending email";
	}   
 
?>
</body>
</html>