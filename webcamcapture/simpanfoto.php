<?php

/* JPEGCam Test Script */
/* Receives JPEG webcam submission and saves to local file. */
/* Make sure your directory has permission to write files as your web server user! */

function FilterInput($sInput,$type = 'string',$size = 1)
{
	if (strlen($sInput) > 0)
	{
		switch($type) {
			case 'string':
				// or use htmlspecialchars( stripslashes( ))
				$sInput = strip_tags(trim($sInput));
				if (get_magic_quotes_gpc())
        			$sInput = stripslashes($sInput);
				$sInput = mysql_real_escape_string($sInput);
				return $sInput;
			case 'htmltext':
				$sInput = strip_tags(trim($sInput),'<a><b><i><u>');
				if (get_magic_quotes_gpc())
        			$sInput = stripslashes($sInput);
				$sInput = mysql_real_escape_string($sInput);
				return $sInput;
			case 'char':
				$sInput = substr(trim($sInput),0,$size);
				if (get_magic_quotes_gpc())
        			$sInput = stripslashes($sInput);
				$sInput = mysql_real_escape_string($sInput);
				return $sInput;
			case 'int':
				return (int) intval(trim($sInput));
			case 'float':
				return (float) floatval(trim($sInput));
			case 'date':
				// Attempts to take a date in any format and convert it to YYYY-MM-DD format
				return date("Y-m-d",strtotime($sInput));
		}
	}
	else
	{
		return "";
	}
}

// Get the person ID from the querystring
$iPersonID = FilterInput($_GET["PersonID"],'int');

$filename = 'tmpimg/'.date('YmdHis') . '.jpg';
$result = file_put_contents( $filename, file_get_contents('php://input') );
if (!$result) {
	print "ERROR: Failed to write data to $filename, check permissions\n";
	exit();
}

$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/' . $filename;
print "$url\n";
print "$iPersonID\n";

?>
