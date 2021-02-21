<?php
/*******************************************************************************
 *
 *  filename    : EventNames.php
 *  last change : 2005-09-10
 *  website     : http://www.terralabs.com
 *  copyright   : Copyright 2005 Todd Pillars
 *
 *  function    : List all Church Events
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *
 *  ChurchInfo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *
 *  Modified by Stephen Shaffer, Oct 2006
 *  feature changes - added recurring defaults and customizable attendance count
 *  fields
 *
 ******************************************************************************/
require "Include/Config.php";
require "Include/Functions.php";
if (!$_SESSION['bAdmin'])
{
    header ("Location: Menu.php");
}
$sPageTitle = gettext("Edit Type Kegiatan");
require "Include/Header.php";
?>
<script language="javascript">
function confirmDeleteOpp( Opp ) {
var answer = confirm (<?php echo '"' . gettext("Apakah anda akan menghapus kegiatan ini?") . '"'; ?>)
if ( answer )
        window.location="EventEditor.php?Opp=" + Opp + "&Action=delete"
}
</script>
<table width="100%" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td align="center"><input type="button" class="icButton" <?php echo 'value="' . gettext("Kembali ke Menu") . '"'; ?> Name="Exit" onclick="javascript:document.location='Menu.php';"></td>
  </tr>
</table>
<?php
//
//  process the ACTION button inputs from the form page
//
$editing='FALSE';
$tyid = $_POST["EN_tyid"];

switch ($_POST['Action']){

case "Hapus":
  $ctid = $_POST['EN_ctid'];
  $sSQL = "DELETE FROM eventcountnames_evctnm WHERE evctnm_countid='$ctid' LIMIT 1";
  RunQuery($sSQL);
  break;

case "Tambah":
  $newCTName = $_POST["newCountName"];
  $theID=$_POST["EN_tyid"];
  $sSQL = "INSERT eventcountnames_evctnm (evctnm_eventtypeid, evctnm_countname) VALUES ('$theID','$newCTName')";
  RunQuery($sSQL);
  break;

case "Simpan Nama":
  $editing='FALSE';
  $eName = $_POST["newEvtName"];
  $theID=$_POST["EN_tyid"];
  $sSQL = "UPDATE event_types SET type_name='$eName' WHERE type_id='$theID'";
  RunQuery($sSQL);
  $theID='';
  $_POST['Action']='';
  break;

case "Simpan Waktu":
  $editing='FALSE';
  $eTime = $_POST["newEvtStartTime"];
  $theID=$_POST["EN_tyid"];
  $sSQL = "UPDATE event_types SET type_defstarttime='$eTime' WHERE type_id='$theID'";
  RunQuery($sSQL);
  $theID='';
  $_POST['Action']='';
  break;
}

// Get data for the form as it now exists.
$sSQL = "SELECT * FROM event_types WHERE type_id='$tyid'";
$rsOpps = RunQuery($sSQL);
$aRow = mysql_fetch_array($rsOpps, MYSQL_BOTH);
extract($aRow);
$aTypeID = $type_id;
$aTypeName = $type_name;
$aDefStartTime = $type_defstarttime;
    $aStartTimeTokens = explode(":", $aDefStartTime);
    $aEventStartHour = $aStartTimeTokens[0];
    $aEventStartMins = $aStartTimeTokens[1];
$aDefRecurDOW = $type_defrecurDOW;
$aDefRecurDOM = $type_defrecurDOM;
$aDefRecurDOY = $type_defrecurDOY;
$aDefRecurType = $type_defrecurtype;
switch ($aDefRecurType){
    case "nihil":
       $recur="Nihil";
       break;
    case "mingguan":
       $recur="Mingguan, setiap hari ".$aDefRecurDOW;
       break;
    case "bulanan":
       $recur="Bulanan, setiap tanggal ".date('dS',mktime(0,0,0,1,$aDefRecurDOM,2000));
       break;
    case "tahunan":
       $recur="Tahunan, setiap tanggal  ".substr($aDefRecurDOY,5);
       break;
    default:
       $recur="Nihil";
}


// Get a list of the attendance counts currently associated with thisevent type
$cSQL = "SELECT evctnm_countid, evctnm_countname FROM eventcountnames_evctnm WHERE evctnm_eventtypeid='$aTypeID' ORDER BY evctnm_countid";
$cOpps = RunQuery($cSQL);
$numCounts = mysql_num_rows($cOpps);
$nr = $numCounts+2;
$cCountName="";
if($numCounts)
     {
     $cCountName="";
     for($c = 1; $c <=$numCounts; $c++){
        $cRow = mysql_fetch_array($cOpps, MYSQL_BOTH);
        extract($cRow);
        $cCountID[$c] = $evctnm_countid;
        $cCountName[$c] = $evctnm_countname;
     }
}


// Construct the form
?>
<table width="100%" align="center" cellpadding="1" cellspacing="0">
<form method="POST" action="EditEventTypes.php" name="EventTypeEditForm">
  <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
  <caption>
    <h3><?php echo gettext("Edit Type Kegiatan"); ?></h3>
  </caption>
  <tr>
     <td class="LabelColumn" width="15%">
        <strong><?php echo gettext("Type Kegiatan ").":".$aTypeID; ?></strong>
     </td>
     <td class="TextColumn" colspan="3" width="85%">
        <input type="text" name="newEvtName" value="<?php echo $aTypeName; ?>" size="30" maxlength="35"> <input type="submit" Class="SmallText" Name="Action" value="<?php echo gettext("Simpan Nama"); ?>" class="icButton">
              <script language="javascript">
                document.UpdateEventNames.newEvtName.focus()
              </script>
          </form>
     </td>
  </tr>
  <tr>
   <td class="LabelColumn" width="15%">
      <strong><?php echo gettext("Pola Kegiatan"); ?></strong>
   </td>
   <td class="TextColumn" width="35%">
      <?php echo $recur; ?>
   </td>
   <td class="LabelColumn" align="left" width="15%">
        <strong><?php echo gettext("Waktu Mulai"); ?></strong>
   </td>
   <td class="TextColumn" width="35%">
        <form method="POST" action="EditEventTypes.php" name="EventTypeEditForm">
        <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
        <input type="hidden" name="Action" value="Simpan Waktu">
        <select name="newEvtStartTime" size="1" onchange="javascript:this.form.submit()">
         <?php createTimeDropdown(4,23,30,$aEventStartHour,$aEventStartMins); ?>
        </select>
        &nbsp;<span class="SmallText"><?php echo gettext("[format: JJ:MM]"); ?></span>
        </form>
   </td>
   </tr>
	  
  <tr><td colspan="4"></td></tr>
   <tr>
      <td class="LabelColumn" align="center" width="15%" rowspan="<?php echo $nr; ?>" colspan="1">
        <strong><?php echo gettext("Kehadiran - Persembahan - Amplop"); ?></strong>
      </td>
    </tr>
    <?php
    for($c = 1; $c <=$numCounts; $c++){
      ?>
      <tr>
        <td class="TextColumn" colspan="1" width="35%"><?php echo $cCountName[$c]; ?></td>
        <td class="TextColumn" colspan="2" width="50%">
           <form name="DelEvCount" action="EditEventTypes.php" method="POST">
           <input type="hidden" name="EN_ctid" value="<?php echo $cCountID[$c]; ?>">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Hapus"); ?>" class="icButton")">
           </form>
        </td>
      </tr>
     <?php
     }
     ?>
	 
	 
    <tr>
        <td class="TextColumn" colspan="1" width="35%">
           <form name="AddEvCount" action="EditEventTypes.php" method="POST">
           <input type="text" name="newCountName" length="20" value="Kolom Baru">
        </td>
        <td class="TextColumn" colspan="2" width="50%">

           <input type="hidden" name="EN_ctid" value="new">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Tambah"); ?>" class="icButton")">
           </form>
        </td>
	</tr>
  
	  
	  
	  </table>
	  

<?php

require "Include/Footer.php";
?>
