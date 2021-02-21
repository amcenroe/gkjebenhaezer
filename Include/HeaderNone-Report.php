<?php
/*******************************************************************************
 *
 *  filename    : Include/Header-Short.php
 *  last change : 2003-05-29
 *  description : page header (simplified version with no menubar)
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2002 Phillip Hullquist, Deane Barker
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2010 Erwin Pratama for GKJ Bekasi Timur (www.gkjbekasitimur.org)
 *
 *  ChurchInfo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// require_once('Header-function.php');

// Turn ON output buffering
ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title><?php echo $Judul ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $sRootPath."/"; ?>Include/<?php echo $_SESSION['sStyle']; ?>">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo $sRootPath."/"; ?>Include/jscalendar/calendar-blue.css" title="cal-style">

<STYLE TYPE="text/css">
  		<!--
  		TD{font-family: Arial; font-size: 10pt;}
		--->
        P.breakhere {page-break-before: always}
</STYLE>
</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >
<DIV align=center >

<table
 style="width: 700; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF">
  <tbody>
  <tr>
    <td></td>
	</tr>
  </tbody>
</table>


