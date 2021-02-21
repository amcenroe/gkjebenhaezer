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
 *
 *  ChurchInfo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

 require_once('Header-function.php');

// Turn ON output buffering
ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
	<?php Header_head_metatag(); ?>
	<STYLE TYPE="text/css">
	     P.breakhere {page-break-before: always}

		 @page { size 8.5in 11in; margin: 2cm }
		 div.page { page-break-after: always }

	</STYLE>

</head>

<body>

