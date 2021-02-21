<?php
/*******************************************************************************
*
* filename : deg2dec.php
* last change : 2014-01-29
*
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2011 Erwin Pratama for GKJ Bekasi Timur (www.gljbekasitimur.org)
******************************************************************************/


$refresh = microtime() ;
// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";



function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

function deg2dec($location){
list($lat, $long) = explode(',', $location);
#latitude
#grep degress
$latdeg = get_string_between($lat, "-", "°");
#grep minutes
$latmin = get_string_between($lat, "°", "'");
#grep second
$latsec = get_string_between($lat, "'", '"');

$declat= $latdeg+((($latmin*60)+($latsec))/3600);
echo "latitude : ".$declat;

#longitude
#grep degress
$longdeg = get_string_between($long, "+", "°");
#grep minutes
$longmin = get_string_between($long, "°", "'");
#grep second
$longsec = get_string_between($long, "'", '"');

$declong= $longdeg+((($longmin*60)+($longsec))/3600);
echo "longitude : ". $declong;

}


$parsed = get_string_between($fullstring, "-", "°");



echo $parsed; // (result = dog)

$fullstring = "-6°15'18.12\", +107°1'4.26\"";
list($lat, $long) = explode(',', $fullstring);

echo "<br>";
echo $lat;
echo "<br>";
echo $long;

$konversi = deg2dec($fullstring);
echo $declat;
echo "<br>";
echo $konversi;

?>



