<?php
/*******************************************************************************
 *
 *  filename    : PrintViewAll.php
 *  last change : 2003-01-29
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2003 Phillip Hullquist, Deane Barker, Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";


$sSQL = "select per_ID from person_per ";
$perintah = mysql_query($sSQL);
while ($HasilWarga=mysql_fetch_array($perintah)){

$iPersonID=$HasilWarga[per_ID] ;

echo $iPersonID;

}
?>;