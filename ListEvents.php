<?php
/*******************************************************************************
*
*  filename    : ListEvents.php
*  website     : http://www.churchdb.org
*  function    : List all Church Events
*
*  copyright   : Copyright 2005 Todd Pillars
*
*
*  Additional Contributors:
*  2007 Ed Davis
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
*
*  Copyright Contributors
*
*
*  ChurchInfo is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  This file best viewed in a text editor with tabs stops set to 4 characters.
*  Please configure your editor to use soft tabs (4 spaces for a tab) instead
*  of hard tab characters.
*
******************************************************************************/

require 'Include/Config.php';
require 'Include/Functions.php';
$eType="Semua";
$eTypeTI="Semua";
$ThisYear=date("Y");

 $currMonth = date("m");
 $allMonths = array($currMonth);


if ($_POST['WhichType']){
  $eType = $_POST['WhichType'];
} else {
  $eType ="Semua";
}

if($eType!="Semua"){
  $sSQL = "SELECT * FROM event_types WHERE type_id=$eType";
  $rsOpps = RunQuery($sSQL);
  $aRow = mysql_fetch_array($rsOpps, MYSQL_BOTH);
  extract($aRow);
  $sPageTitle = "Daftar Kegiatan = ".$type_name;
} else {
  $sPageTitle = gettext("Daftar semua Kegiatan Gereja");
}

//TI Selector
if ($_POST['WhichTypeTI']){
  $eTypeTI = $_POST['WhichTypeTI'];
} else {
  $eTypeTI ="Semua";
}
if($eTypeTI!="Semua"){
  $sSQLTI = "SELECT DISTINCT event_desc FROM events_event WHERE event_desc='$eTypeTI'";
  $rsOppsTI = RunQuery($sSQLTI);
  $aRowTI = mysql_fetch_array($rsOppsTI, MYSQL_BOTH);
  extract($aRowTI);
  $sPageTitle = "Daftar Kegiatan = ".$type_nameTI;
} else {
  $sPageTitle = gettext("Daftar semua Kegiatan Gereja");
}

// retrieve the year selector

if($_POST['WhichYear'])
{
    $EventYear=$_POST['WhichYear'];
} else {
    $EventYear=date("Y");
}

// retrieve the year selector

if($_POST['WhichMonth'])
{
    $EventMonth=$_POST['WhichMonth'];
} else {
    $EventMonth=date("m");
}
if($EventMonth!="Semua"){
 $allMonths = array($EventMonth);
} else {
  $allMonths = array("1","2","3","4","5","6","7","8","9","10","11","12");
}


///////////////////////
require 'Include/Header.php';

if ($_POST['Action']== "Delete" && !empty($_POST['EID']))
{
    $sSQL = "DELETE FROM events_event WHERE event_id = ".$_POST['EID']." LIMIT 1";
    RunQuery($sSQL);

    $sSQL = "DELETE FROM eventcounts_evtcnt WHERE evtcnt_eventid = ".$_POST['EID'];
    RunQuery($sSQL);

}
elseif ($_POST['Action']== "Activate" && !empty($_POST['EID']))
{
    $sSQL = "UPDATE events_event SET inactive = 0 WHERE event_id = ".$_POST['EID']." LIMIT 1";
    RunQuery($sSQL);
}

/// top of main form
//
$sSQL = "SELECT DISTINCT event_types.* FROM event_types RIGHT JOIN events_event ON event_types.type_id=events_event.event_type ORDER BY type_id ";
$rsOpps = RunQuery($sSQL);
$numRows = mysql_num_rows($rsOpps);

/// TI Selector
//
$sSQLTI = "SELECT DISTINCT event_desc FROM events_event ORDER BY event_desc ";
$rsOppsTI = RunQuery($sSQLTI);
$numRowsTI = mysql_num_rows($rsOppsTI);

?>
<table cellpadding="1" align="center" cellspacing="0" width="2500">
<tr>
<td align="left" width="50%"><strong><?php echo gettext("Pilih Type Kegiatan dan tempat Ibadah") ?></strong><br>
    <form name="EventTypeSelector" method="POST" action="ListEvents.php">
       Jenis Kegiatan : <select name="WhichType" onchange="javascript:this.form.submit()">
        <option value="Semua">Semua</option>
        <?php
        for ($r = 1; $r <= $numRows; $r++)
        {
          $aRow = mysql_fetch_array($rsOpps, MYSQL_BOTH);
          extract($aRow);
//          foreach($aRow as $t)echo "$t\n\r";
          ?>
          <option value="<?php echo $type_id ?>" <?php if($type_id==$eType) echo "selected" ?>><?php echo $type_name; ?></option>
          <?php
         }
         ?>
         </select>
		 
       <form name="EventTypeSelectorTI" method="POST" action="ListEvents.php">
       Tempat Ibadah : <select name="WhichTypeTI" onchange="javascript:this.form.submit()">
        <option value="Semua">Semua</option>
        <?php
        for ($r = 1; $r <= $numRowsTI; $r++)
        {
          $aRowTI = mysql_fetch_array($rsOppsTI, MYSQL_BOTH);
          extract($aRowTI);
//          foreach($aRowTI as $t)echo "$t\n\r";
          ?>
          <option value="<?php echo $event_desc ?>" <?php if($event_desc==$eTypeTI) echo "selected" ?>><?php echo $event_desc; ?></option>
          <?php
         }
         ?>
         </select>
		 
<?php
// month selector
if($eType=="Semua"){
  $sSQL = "SELECT DISTINCT MONTH(events_event.event_start) FROM events_event WHERE MONTH(events_event.event_start) ";
} else {
  $sSQL = "SELECT DISTINCT MONTH(events_event.event_start) FROM events_event WHERE events_event.event_type = '$eType' AND MONTH(events_event.event_start) ";
}
$rsOpps = RunQuery($sSQL);
$aRow = mysql_fetch_array($rsOpps, MYSQL_BOTH);
@extract($aRow); // @ needed to suppress error messages when no church events
$rsOpps = RunQuery($sSQL);
$numRows = mysql_num_rows($rsOpps);
for($r=1; $r<=$numRows; $r++){
    $aRow = mysql_fetch_array($rsOpps, MYSQL_BOTH);
    extract($aRow);
    $Yr[$r]=$aRow[0];
}
?>

       Bulan: <select name="WhichMonth" onchange="javascript:this.form.submit()" >
	   <option value="<?php echo "Semua" ?>" <?php if("Semua"==$EventMonth) echo "selected" ?>><?php echo "Semua"; ?></option>
        <?php
        for ($r = 1; $r <= $numRows; $r++)
        {
          ?>
  
          <option value="<?php echo $Yr[$r] ?>" <?php if($Yr[$r]==$EventMonth) echo "selected" ?>><?php echo $Yr[$r]; ?></option>
          <?php
         }
         ?>
         </select>

		 
<?php
// year selector
if($eType=="Semua"){
  $sSQL = "SELECT DISTINCT YEAR(events_event.event_start) FROM events_event WHERE YEAR(events_event.event_start) ";
} else {
  $sSQL = "SELECT DISTINCT YEAR(events_event.event_start) FROM events_event WHERE events_event.event_type = '$eType' AND YEAR(events_event.event_start) ";
}
$rsOpps = RunQuery($sSQL);
$aRow = mysql_fetch_array($rsOpps, MYSQL_BOTH);
@extract($aRow); // @ needed to suppress error messages when no church events
$rsOpps = RunQuery($sSQL);
$numRows = mysql_num_rows($rsOpps);
for($r=1; $r<=$numRows; $r++){
    $aRow = mysql_fetch_array($rsOpps, MYSQL_BOTH);
    extract($aRow);
    $Yr[$r]=$aRow[0];
}
?>

       Tahun :<select name="WhichYear" onchange="javascript:this.form.submit()" >
        <?php
        for ($r = 1; $r <= $numRows; $r++)
        {
          ?>
          <option value="<?php echo $Yr[$r] ?>" <?php if($Yr[$r]==$EventYear) echo "selected" ?>><?php echo $Yr[$r]; ?></option>
          <?php
         }
         ?>
         </select>
    </form>
</td>
</tr>
<tr>
<td><a href="LapBulanan_hadir.php?Tahun=<?=$EventYear?> " target="_blank"><b>Grafik Kehadiran dan Persembahan - Tahun <?=$EventYear?></b></a>
</tr>
</table>
<?php

// Get data for the form as it now exists..
// for this year
$currYear = date("Y");
//$currMonth = date("m");
//$allMonths = array("1","2","3","4","5","6","7","8","9","10","11","12");
//$allMonths = array("5");
if ($eType=="Semua") {
  $eTypeSQL=" ";
} else {
  $eTypeSQL = " AND t1.event_type=$eType";

}

// TI Selecktor
if ($eTypeTI=="Semua") {
  $eTypeSQLTI=" ";
  $eTypeSQLTI2=" ";
} else {
  $eTypeSQLTI = " AND t1.event_desc='$eTypeTI'";
  $eTypeSQLTI2 = " AND events_event.event_desc='$eTypeTI'";
}
//Mulai Listing

foreach ($allMonths as $mKey => $mVal) {
        unset($cCountSum);
        $sSQL = "SELECT * FROM events_event as t1, event_types as t2 ";
        if (isset($previousMonth))
        {
                // $sSQL .= " WHERE previous month stuff";
        }
        elseif (isset($nextMonth))
        {
                 // $sSQL .= " WHERE next month stuff";
        }
        elseif (isset($showAll))
        {
                $sSQL .="";
        }
        else
        {
            //$sSQL .= " WHERE (TO_DAYS(event_start_date) - TO_DAYS(now()) < 30)";
            //$sSQL .= " WHERE t1.event_type = t2.type_id".$eTypeSQL." AND MONTH(t1.event_start) = ".$mVal." AND YEAR(t1.event_start)=$EventYear";
			$sSQL .= " WHERE t1.event_type = t2.type_id".$eTypeSQL." AND MONTH(t1.event_start) = ".$mVal." AND YEAR(t1.event_start)=$EventYear".$eTypeSQLTI;
	//		echo $sSQL ;
	//	//	echo $mVal;
        }
		
        $sSQL .= " ORDER BY t1.event_start ";
// echo $sSQL;
        $rsOpps = RunQuery($sSQL);
        $numRows = mysql_num_rows($rsOpps);
        $aAvgRows = $numRows;
        // Create arrays of the fundss.
        for ($row = 1; $row <= $numRows; $row++)
        {
                $aRow = mysql_fetch_array($rsOpps, MYSQL_BOTH);
                extract($aRow);

                $aEventID[$row] = $event_id;
                $aEventType[$row] = $event_typename;
                $aEventName[$row] = htmlentities(stripslashes($event_name),ENT_NOQUOTES, "UTF-8");
                $aEventTitle[$row] = htmlentities(stripslashes($event_title),ENT_NOQUOTES, "UTF-8");
                $aEventDesc[$row] = htmlentities(stripslashes($event_desc),ENT_NOQUOTES, "UTF-8");
                $aEventText[$row] = htmlentities(stripslashes($event_text),ENT_NOQUOTES, "UTF-8");
                $aEventStartDateTime[$row] = $event_start;
                $aEventEndDateTime[$row] = $event_end;
                $aEventStatus[$row] = $inactive;
                // get the list of attend-counts that exists in event_attend for this
                $attendSQL="SELECT * FROM event_attend WHERE event_id=$event_id";
                $attOpps = RunQuery($attendSQL);
                if($attOpps)
                  $attNumRows[$row] = mysql_num_rows($attOpps);
                else
                  $attNumRows[$row]=0;

        }

// Construct the form
?>
<table cellpadding="4" align="left" cellspacing="0" width="2100">
  <h3><?php echo gettext(" ".($numRows == 1 ? " ".$numRows." kegiatan":"Di Tempat Ibadah ".$eTypeTI." Ada ".$numRows." ")." kegiatan pada bulan ".date("F", mktime(0, 0, 0, $mVal, 1, $currYear))." ".$EventYear); ?></h3>
<?php
if ($numRows > 0)
{
?>

	
         <tr class="TableHeader">
		   <td width="100"></td>
           <td width="100"><strong><?php echo gettext("Type Kegiatan"); ?></strong></td>
           <td width="200"><strong><?php echo gettext("Judul kegiatan"); ?><br></strong>
           <strong><?php echo gettext("Keterangan"); ?></strong></td>
		   <td width="150" align="center"><strong><?php echo gettext("Tanggal - Jam"); ?>
		   <br><img src="1piksel.jpg" width="60" height="1" alt=" " /> 
		   </strong></td>
				<td width="100%" align="center"><strong>
				<?php echo gettext("Data Statistik"); ?></strong>

				
				
				
			<table cellpadding="4" align="center" cellspacing="0" width="100%">
				<tr class="TableHeader">
				<td align="center"> Jumlah Kehadiran </td>
				<td align="center"> Jumlah Persembahan </td>
				<td align="center"> Jumlah Amplop </td>
				</tr>
			</table>	
			<table cellpadding="4" align="center" cellspacing="0" width="100%">
				<tr>
				<?php
$headerstat = "1" ;
	//			if($eType="Semua"){
	//				$headerstat = "1" ;
	//				} else {
	//				$headerstat = $eType ;
	//				}
					
				// HEADER RETRIEVE THE list of counts associated with the current event
				//
				// $cvSQL= "SELECT * FROM eventcounts_evtcnt WHERE evtcnt_eventid='$aEventID[$row]' ORDER BY evtcnt_countid ASC LIMIT 1";
		$cvSQL1= "SELECT evctnm_countname FROM eventcountnames_evctnm WHERE evctnm_eventtypeid ='$headerstat' ORDER BY evctnm_countid";
      
	 //echo $cvSQL;
                $cvOpps1 = RunQuery($cvSQL1);
                $aNumCounts1 = mysql_num_rows($cvOpps1);
//        echo "numcounts = {$aNumCounts}\n\l";
                if($aNumCounts1) {

                for($c = 0; $c <$aNumCounts1; $c++){
                  $cRow1 = mysql_fetch_array($cvOpps1, MYSQL_BOTH);
                  extract($cRow1);
    //              $cCountID[$c] = $evtcnt_countid;
      //            $cCountName[$c] = $evtcnt_countname;
        //          $cCount[$c]= $evtcnt_countcount;
          //        $cCountNotes = $evtcnt_notes;
//                  $cCountSum[$c]+= $evtcnt_countcount;
                  ?>
                  <td align="center" width="30" >
                  <span class="SmallText">
                    <strong><?php echo $evctnm_countname; ?>
					<br><img src="1piksel.jpg" width="60" height="1" alt=" " />  
					</strong> 
                    </td> 
                  <?php
                }
                } else {
                  ?>
                  <td align="center">
                    <span class="SmallText">
                    <strong><?php echo gettext("Tidak ada data kehadiran"); ?></strong>
                    </span>
                  </td>
                  <?php
//                  $aAvgRows -=1;
                }
           ?>
           </tr>
   



			</table>
	
				</td>
           <td width="5%" align="center"><strong><?php echo gettext("Aktif"); ?></strong></td>
           <td colspan="3" width="15%" align="center"><strong><?php echo gettext("Action"); ?></strong></td>
        </tr>
         <?php
         //Set the initial row color
         $sRowClass = "RowColorA";

         for ($row=1; $row <= $numRows; $row++)
         {

         //Alternate the row color
         $sRowClass = AlternateRowStyle($sRowClass);

         //Display the row
         ?>
         <tr class="<?php echo $sRowClass; ?>">
		            <td align="center"><span class="SmallText">
             <form name="EditEvent" action="EventEditor.php" method="POST">
               <input type="hidden" name="EID" value="<?php echo $aEventID[$row]; ?>">
               <input class="SmallText" type="submit" name="Action" <?php echo 'value="' . gettext("Edit") . '"'; ?> class="icButton">
             </form></span>
           </td>
		   
           <td><span class="SmallText"><?php echo $aEventType[$row]; ?></span></td>
           <td><span class="SmallText"><?php echo $aEventTitle[$row]; ?><br><strong>
           <?php echo ($aEventDesc[$row] == '' ? "&nbsp;":$aEventDesc[$row]); ?></strong>
             <?php echo ($aEventText[$row] != '' ? "&nbsp;&nbsp;&nbsp;<a href=\"javascript:popUp('GetText.php?EID=".$aEventID[$row]."')\"><strong>Sermon Text</strong></a>":""); ?></span>
           </td>
		    <td><span class="SmallText"><?php echo FormatDate($aEventStartDateTime[$row],1); ?></span></td>
           <td>
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
           <?php
// RETRIEVE THE list of counts associated with the current event
//
                $cvSQL= "SELECT * FROM eventcounts_evtcnt WHERE evtcnt_eventid='$aEventID[$row]' ORDER BY evtcnt_countid ASC";
 //     echo $cvSQL;
                $cvOpps = RunQuery($cvSQL);
                $aNumCounts = mysql_num_rows($cvOpps);
//        echo "numcounts = {$aNumCounts}\n\l";
                if($aNumCounts) {

                for($c = 0; $c <$aNumCounts; $c++){
                  $cRow = mysql_fetch_array($cvOpps, MYSQL_BOTH);
                  extract($cRow);
                  $cCountID[$c] = $evtcnt_countid;
                  $cCountName[$c] = $evtcnt_countname;
                  $cCount[$c]= $evtcnt_countcount;
                  $cCountNotes = $evtcnt_notes;
//                  $cCountSum[$c]+= $evtcnt_countcount;
                  ?>
                  <td align="center" width="30">
                  <span class="SmallText">
                    <strong><?php // echo $evtcnt_countname; ?> </strong> 
					<?php echo number_format($evtcnt_countcount,0,",","."); ?>
                    </span>
					<br><img src="1piksel.jpg" width="60" height="1" alt=" " /> 
                  </td>
                  <?php
                }
                } else {
                  ?>
                  <td align="center">
                    <span class="SmallText">
                    <strong><?php echo gettext("Tidak ada data kehadiran"); ?></strong>
                    </span>
                  </td>
                  <?php
//                  $aAvgRows -=1;
                }
           ?>
           </tr>
           </table>
           </td>
          

           <td class="SmallText" align="center"><?php echo ($aEventStatus[$row] != 0 ? "No":"Yes"); ?></span></td>

          <td><span class="SmallText">
          <form name="EditAttendees" action="EditEventAttendees.php" method="POST">
          <input type="hidden" name="EID" value="<?php echo $aEventID[$row]; ?>">
          <input type="hidden" name="EName" value="<?php echo $aEventTitle[$row]; ?>">
          <input type="hidden" name="EDesc" value="<?php echo $aEventDesc[$row]; ?>">
          <input type="hidden" name="EDate" value="<?php echo FormatDate($aEventStartDateTime[$row],1); ?>">
 
             </form></span>
           </td>

           <td align="center"><span class="SmallText">
             <form name="EditEvent" action="EventEditor.php" method="POST">
               <input type="hidden" name="EID" value="<?php echo $aEventID[$row]; ?>">
               <input class="SmallText" type="submit" name="Action" <?php echo 'value="' . gettext("Edit") . '"'; ?> class="icButton">
             </form></span>
           </td>
           <td><span class="SmallText">
             <form name="DeleteEvent" action="ListEvents.php" method="POST">
               <input type="hidden" name="EID" value="<?php echo $aEventID[$row]; ?>">
               <input class="SmallText" type="submit" name="Action" value="<?php echo gettext("Delete"); ?>" class="icButton" onClick="return confirm('Deleting an event will also delete all attendance counts for that event.  Are you sure you want to DELETE Event ID: <?php echo  $aEventID[$row]; ?>')">
             </form></span>
          </td>

         </tr>
<?php
         } // end of for loop for # rows for this month

// calculate averages if this is a single type list
if ($eType != "Semua" && $aNumCounts >0){
//    $avgSQL="SELECT evtcnt_countid, evtcnt_countname, AVG(evtcnt_countcount) from eventcounts_evtcnt, events_event WHERE eventcounts_evtcnt.evtcnt_eventid=events_event.event_id AND events_event.event_type='$eType' ".$eTypeSQLTI2." AND MONTH(events_event.event_start)='$mVal' GROUP BY eventcounts_evtcnt.evtcnt_countid ASC ";
$avgSQL="SELECT evtcnt_countid, evtcnt_countname, SUM(evtcnt_countcount) from eventcounts_evtcnt, events_event WHERE eventcounts_evtcnt.evtcnt_eventid=events_event.event_id AND events_event.event_type='$eType' ".$eTypeSQLTI2." AND MONTH(events_event.event_start)='$mVal' GROUP BY eventcounts_evtcnt.evtcnt_countid ASC ";

	
//	echo $avgSQL;
    $avgOpps = RunQuery($avgSQL);
    $aAvgRows = mysql_num_rows($avgOpps);

    ?>
    <tr>
	<td> </td>
    <td class="LabelColumn" colspan="2"><?php echo gettext(" Rata-rata Bulanan "); ?></td>
	<td> </td>
    <td>
       <table width="100%" cellpadding="0" cellspacing="0" border="0">
       <tr>
	 
      <?php
      // calculate and report averages
      for($c = 0; $c <$aAvgRows; $c++){
        $avgRow = mysql_fetch_array($avgOpps, MYSQL_BOTH);
        extract($avgRow);
        $avgName = $avgRow['evtcnt_countname'];
        $avgAvg = $avgRow[2];
        ?>
        <td align="center">
          <span class="SmallText">
<?php       
//	   Rata2<br> // echo $avgName;
?>          
		  <br><strong>
			<?php echo number_format($avgAvg/$numRows,2,",","."); ?>
		  </strong>
		  <br><img src="1piksel.jpg" width="60" height="1" alt=" " /> 
		  </span>
        </td> 
        <?php
      }
      ?>
      </tr>
	  	
      </table>
      </td>

      </tr>
<?php }
?>
         <tr><td class="TextColumn" colspan="6">&nbsp;</td></tr>
<?php
    }
?>
      </table>
<?php
} // end for-each month loop
?>
             <table width="100%">
                <tr class="<?php echo $sRowClass; ?>">
                 <td align="center" valign="bottom">
                   <input type="button" Name="Action" <?php echo 'value="' . gettext("Tambah kegiatan Baru") . '"'; ?> class="icButton" onclick="javascript:document.location='EventNames.php';">
                 </td>
               </tr>
             </table>
<?php
require 'Include/Footer.php';
?>
