<HTML>
   <HEAD>
      <TITLE>Keluarga Besar GKJ Bekasi Timur</TITLE>



   </HEAD>

   <body bgcolor="#FFFFFF" ondragstart="return false" onselectstart="return false">
      <script language="JavaScript">
	  var popup="Sorry, right-click is disabled.\n\nThis Site Copyright �2000";
	   function noway(go) { if
	  (document.all) { if (event.button == 2) { alert(popup); return false; } } if (document.layers)
	  { if (go.which == 3) { alert(popup); return false; } } } if (document.layers)
	  { document.captureEvents(Event.MOUSEDOWN); } document.onmousedown=noway; // -->
	  </script>

	  <script language="JavaScript1.1">
	  var debug = true;
	  function right(e) { if (navigator.appName == 'Netscape' && (e.which == 3 || e.which
	  == 2)) return false; else if (navigator.appName == 'Microsoft Internet Explorer'
	  && (event.button == 2 || event.button == 3)) { alert('This Page is fully
	  protected!'); return false; } return true; } document.onmousedown=right; if (document.layers)
	  window.captureEvents(Event.MOUSEDOWN); window.onmousedown=right; //--></script>


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

echo "<img src='$path$file' width=30 height=40 alt='$file' >";
//echo "<img src=\"img.php?fl=$path$file&type=1\" alt=\"gambar\">";

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

echo "<img src='$path$file' width=60 height=40 alt='$file' >";
//echo '<img src=\"img.php?fl=$path$file&type=1\" alt=\"gambar\">';

}
closedir($dir_handle);

?>


   </BODY>

</HTML>