<?PHP
 
//$firstname is the first name of target
//$lastname is the last name of target
//$email is the targets email address
//$meeting_date is straight from a DATETIME mysql field and assumes UTC.
//$meeting_name is the name of your meeting
//$meeting_duretion is the duration of your meeting in seconds (3600 = 1 hour)
 
FUNCTION sendIcalEmail($firstname,$lastname,$email,$meeting_date,$meeting_name,$meeting_duration) {
 
	$from_name = "My Name";
	$from_address = "e_pratama@yahoo.com";
	$subject = "Meeting Booking"; //Doubles as email subject and meeting subject in calendar
	$meeting_description = "Here is a brief description of my meeting\n\n";
	$meeting_location = "My Office"; //Where will your meeting take place
 
 
	//Convert MYSQL datetime and construct iCal start, end and issue dates
	$meetingstamp = STRTOTIME($meeting_date . " UTC");    
	$dtstart= GMDATE("Ymd\THis\Z",$meetingstamp);
	$dtend= GMDATE("Ymd\THis\Z",$meetingstamp+$meeting_duration);
	$todaystamp = GMDATE("Ymd\THis\Z");
 
	//Create unique identifier
	$cal_uid = DATE('Ymd').'T'.DATE('His')."-".RAND()."@mydomain.com";
 
	//Create Mime Boundry
	$mime_boundary = "----Meeting Booking----".MD5(TIME());
 
	//Create Email Headers
	$headers = "From: ".$from_name." <".$from_address.">\n";
	$headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
 
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
	$headers .= "Content-class: urn:content-classes:calendarmessage\n";
 
	//Create Email Body (HTML)
	$message .= "--$mime_boundary\n";
	$message .= "Content-Type: text/html; charset=UTF-8\n";
	$message .= "Content-Transfer-Encoding: 8bit\n\n";
 
	$message .= "<html>\n";
	$message .= "<body>\n";
	$message .= '<p>Dear '.$firstname.' '.$lastname.',</p>';
	$message .= '<p>Here is my HTML Email / Used for Meeting Description</p>';    
	$message .= "</body>\n";
	$message .= "</html>\n";
	$message .= "--$mime_boundary\n";
 
	//Create ICAL Content (Google rfc 2445 for details and examples of usage) 
	$ical =    'BEGIN:VCALENDAR
PRODID:-//Microsoft Corporation//Outlook 11.0 MIMEDIR//EN
VERSION:2.0
METHOD:PUBLISH
BEGIN:VEVENT
ORGANIZER:MAILTO:'.$from_address.'
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
 
	$message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST\n';
	$message .= "Content-Transfer-Encoding: 8bit\n\n";
	$message .= $ical;            
 
	//SEND MAIL
	$mail_sent = @smtp_mail( $email, $subject, $message, $headers );
 
	IF($mail_sent)     {
		RETURN TRUE;
	} ELSE {
		RETURN FALSE;
	}   
 
}
 
 
?>