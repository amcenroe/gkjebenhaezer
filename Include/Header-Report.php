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
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
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
      <td style="width: 125px;"><a href="Menu.php" ><img align=right style="width: 90px; height: 95px;" src="gkj_logo.jpg" border="0"></a></td>
      <td  colspan="2" style="width: 600px;"><b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo $sChurchName;?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font>
	<br style="font-family: Arial; color: rgb(0, 0, 102);"><b style="font-family: Arial; color: rgb(0, 0, 102);">
	<hr style="width: 100%; height: 2px;">
	<font size="2"><big style="font-family: Arial;"><?php echo $Judul ?></big></font></b>   
	</td>
    <td></td>
	</tr>
  </tbody>
</table>


