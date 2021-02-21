<?php

<?
$path = "";
$dir_handle = @opendir($path) or die("Unable to open $path");
echo "Directory Listing of $path<br/>";
while($file = readdir($dir_handle)) {
if(is_dir($file)) {
continue;
}

else if($file != '.' && $file != '..') {
echo "<img src=\"img.php?fl=$file&amp;type=1 \" width=30 height=40 alt=\"G\">";

}
}
