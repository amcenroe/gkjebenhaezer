<?php
/*******************************************************************************
 *
 *  filename    : PrintView.php
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
require "Include/Header-Print.php";

$iStatus = FilterInput($_GET["Status"]);

// test the table functions
error_reporting(E_ALL);
include('class.ezpdf.php');



$pdf =& new Cezpdf('a4','landscape');
$pdf->selectFont('Include/ezpdf/fonts/Helvetica');



$image="gkj_logo.jpg";
$pdf->ezImage($image,20,20,'none','left');
$pdf->ezText('Testing');
			$pdf->stream();



?>


