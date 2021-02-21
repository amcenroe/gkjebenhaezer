<?php
/*******************************************************************************
*
* filename : BulananReport.php
* last change : 2003-01-29
*
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2011 Erwin Pratama for GKJ Bekasi Timur (www.gljbekasitimur.org)
******************************************************************************/


$refresh = microtime() ;
// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";
//Set the page title
$sPageTitle = gettext("Agenda Kegiatan");
require "Include/Header.php";
//Print_r ($_SESSION);
		$logvar1 = "Report";
		$logvar2 = "Agenda Kegiatan";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">

</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >


<table 
 style="font-family: Arial; width: 750; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="2" cellspacing="2">
 
 <tr>
  <td colspan="15" style="font-family: Arial; width: 380px; text-align: left; color: rgb(0, 0, 102);" valign=top align=center>
<iframe src="https://www.google.com/calendar/embed?mode=AGENDA&amp;height=500&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=gkjbekasitimur.org_32gsa16k7fjijguk6pdslri304%40group.calendar.google.com&amp;color=%23125A12&amp;src=sekretariat%40gkjbekasitimur.org&amp;color=%23182C57&amp;src=gkjbekasitimur.org_aihd1u4rsc445b488dd8lg3k9c%40group.calendar.google.com&amp;color=%232F6309&amp;src=in.indonesian%23holiday%40group.v.calendar.google.com&amp;color=%23711616&amp;src=komisi.remaja%40gkjbekasitimur.org&amp;color=%23875509&amp;ctz=Asia%2FJakarta" style=" border-width:0 " width="850" height="500" frameborder="0" scrolling="no"></iframe>
</table>

</body>



<?php

//require "Include/Footer-Short.php";
?>
