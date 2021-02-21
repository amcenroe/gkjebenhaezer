<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>This is header 1</title>
</head>

<body>
<p><!--webbot bot="HTMLMarkup" startspan -->

<SCRIPT language=JavaScript>
<!-- http://www.spacegun.co.uk -->
	var message = "function disabled";
	function rtclickcheck(keyp){ if (navigator.appName == "Netscape" && keyp.which == 3){ 	alert(message); return false; }
	if (navigator.appVersion.indexOf("MSIE") != -1 && event.button == 2) { 	alert(message); 	return false; } }
	document.onmousedown = rtclickcheck;
</SCRIPT>

<script>
	  var popup="Sorry, right-click is disabled.\n\nThis Site Copyright ©2000";
	   function noway(go) {
	   if  (document.all) { if (event.button == 2) { alert(popup); return false; } } if (document.layers)
	  { if (go.which == 3) { alert(popup); return false; } } } if (document.layers)
	  { document.captureEvents(Event.MOUSEDOWN); } document.onmousedown=noway; // -->
</script>

<script>
	  var debug = true;
	  function right(e) {
	  if (navigator.appName == 'Netscape' && (e.which == 3 || e.which
	  == 2)) return false; else if (navigator.appName == 'Microsoft Internet Explorer'
	  && (event.button == 2 || event.button == 3)) { alert('This Page is fully
	  protected!'); return false; } return true; } document.onmousedown=right; if (document.layers)
	  window.captureEvents(Event.MOUSEDOWN); window.onmousedown=right; //-->
</script>

<!--webbot bot="HTMLMarkup" endspan -->
</p>

<?php

$path = "./Images/Person/thumbnails/";
$dir_handle = @opendir($path) or die("Unable to open folder");

while (false !== ($file = readdir($dir_handle))) {

if($file == "index.php")
continue;
if($file == "index.html")
continue;
if($file == "dummy")
continue;
if($file == ".")
continue;
if($file == "..")
continue;

//echo "<img src='$path$file' width=30 height=40 alt='$file' >";
//echo "<img src=\"graph_kelompok.php?&amp;$refresh \" width=\"360\" ><br>" ;

if (file_exists($path . $file))
echo "<img src=\"img.php?fl=$file&amp;type=1 \" width=30 height=40 alt=\"gambar\">";

}
closedir($dir_handle);

$path = "./Images/Family/thumbnails/";
$dir_handle = @opendir($path) or die("Unable to open folder");

while (false !== ($file = readdir($dir_handle))) {

if($file == "index.php")
continue;
if($file == "index.html")
continue;
if($file == "dummy")
continue;
if($file == ".")
continue;
if($file == "..")
continue;

//echo "<img src='$path$file' width=60 height=40 alt='$file' >";
//echo '<img src=\"img.php?fl=$path$file&type=1\" alt=\"gambar\">';

if (file_exists($path . $file))
echo "<img src=\"img.php?fl=$file&amp;type=1 \" width=30 height=40 alt=\"gambar\">";

}
closedir($dir_handle);

?>

</body>

</html>