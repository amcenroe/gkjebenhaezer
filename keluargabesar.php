<?php
$refresh = microtime() ;

$img = "Images/Person/thumbnails/";
$path = "Images/Person/thumbnails/";
$dir_handle = @opendir($path) or die("Unable to open $path");
echo "Directory Listing of $path<br/>";
while($file = readdir($dir_handle)) {
if(is_dir($file)) {
continue;
}

else if($file != '.' && $file != '..' && $file != 'dummy' && $file != 'index.html' ) {
echo "<img src=\"img.php?fl=$file&amp;$refresh\" width=30 height=40 alt=\"G\">";

}
}
