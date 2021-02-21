

<?
// Sanitizes user input as a security measure
// Optionally, a filtering type and size may be specified.  By default, strip any tags from a string.
// Note that a database connection must already be established for the mysql_real_escape_string function to work.
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

//Copy Foto Disini
if (isset($_POST["SaveChanges"]))
{
echo "upload gambar";
}
?>
<script language="JavaScript" type="text/javascript">
  <!--
	function closeself() {
		opener.location.reload(true);
		self.close();
	}
  // -->
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Swalayan Foto Jemaat</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Joseph Huckaby">
	<!-- Date: 2008-03-15 -->
</head>
<body>
	<table align="center" style="margin: 0px auto;"><tr><td valign=top>
	<h3>Swalayan Foto Pribadi</h3>
	<h6>Silahkan Klik Ambil Gambar untuk mengambil gambar</h6>
	
	<!-- First, include the JPEGCam JavaScript Library -->
	<script type="text/javascript" src="webcam.js"></script>
	
	<!-- Configure a few settings -->
	<script language="JavaScript">
		webcam.set_api_url( 'simpanfoto.php' );
		webcam.set_quality( 100 ); // JPEG quality (1 - 100)
		webcam.set_shutter_sound( true ); // play shutter click sound
	</script>

	
	<!-- Next, write the movie to the page at 265x350 -->
	<script language="JavaScript">
		document.write( webcam.get_html(265, 350) );
	</script>
	
	<!-- Some buttons for controlling things -->
	<br/><form>
		<!-- 	<input type=button value="Configure..." onClick="webcam.configure()">  -->
		&nbsp;&nbsp;
		<input type=button value="Ambil Gambar" onClick="take_snapshot()">
	</form>
	
	<!-- Code to handle the server response (see simpanfoto.php) -->
	<script language="JavaScript">
		webcam.set_hook( 'onComplete', 'my_completion_handler' );
		
		function take_snapshot() {
			// take snapshot and upload to server
			document.getElementById('upload_results').innerHTML = '<h1>Upload foto...</h1>';
			webcam.snap();
		}
		
		function my_completion_handler(msg) {
			// extract URL out of PHP output
			if (msg.match(/(http\:\/\/\S+)/)) {
				var image_url = RegExp.$1;
				// show JPEG image in page
				document.getElementById('upload_results').innerHTML = 
					'<h1>Hasil Gambar</h1>' + 
				//	'<h3>JPEG URL: ' + image_url + '</h3>' + 
					'<a href="../PrintViewCari.php?PersonID=<?php echo $iPersonID; ?>">' +
					'<img src="' + image_url + '">' +
					'<br>PersonID : <?php echo $iPersonID; ?></a>' + 
					'<br>PersonID : ' + image_url + '</a>' +
				'<br><form><input type="submit" class="icButton" value="Simpan" Name="SaveChanges">' + 
				'          <input type="button" class="icButton" value="Keluar" Name="Exit" onclick="javascript:closeself();"></form>' ;
				// reset camera for another shot
				webcam.reset();
				
				
			}
			else alert("PHP Error: " + msg);
		}
	</script>
	<!-- Some buttons for controlling things -->

	
	</td><td width=50>&nbsp;</td><td valign=top>
		<div id="upload_results" style="background-color:#eee;"></div>
		<br/>
		

<?
    if ( isset($_POST["UploadGambar"]) && ($_SESSION['bAddRecords'] || $bOkToEdit) ) {
	
	echo "testing";
 
 }
?>
	</td></tr></table>
</body>
</html>
