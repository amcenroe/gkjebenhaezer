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
case "Hapus0":
  $ctid = $_POST['EN_ctid'];
  $sSQL = "DELETE FROM eventcountnames_evctnm_tab0 WHERE evctnm_countid='$ctid' LIMIT 1";
  RunQuery($sSQL);
  break; 

case "Hapus1":
  $ctid = $_POST['EN_ctid'];
  $sSQL = "DELETE FROM eventcountnames_evctnm_tab1 WHERE evctnm_countid='$ctid' LIMIT 1";
  RunQuery($sSQL);
  break; 

case "Hapus":
  $ctid = $_POST['EN_ctid'];
  $sSQL = "DELETE FROM eventcountnames_evctnm WHERE evctnm_countid='$ctid' LIMIT 1";
  RunQuery($sSQL);
  break;

case "Hapus2":
  $ctid = $_POST['EN_ctid'];
  $sSQL = "DELETE FROM eventcountnames_evctnm_tab2 WHERE evctnm_countid='$ctid' LIMIT 1";
  RunQuery($sSQL);
  break; 

case "Hapus3":
  $ctid = $_POST['EN_ctid'];
  $sSQL = "DELETE FROM eventcountnames_evctnm_tab3 WHERE evctnm_countid='$ctid' LIMIT 1";
  RunQuery($sSQL);
  break; 

case "Hapus4":
  $ctid = $_POST['EN_ctid'];
  $sSQL = "DELETE FROM eventcountnames_evctnm_tab4 WHERE evctnm_countid='$ctid' LIMIT 1";
  RunQuery($sSQL);
  break; 

case "Hapus5":
  $ctid = $_POST['EN_ctid'];
  $sSQL = "DELETE FROM eventcountnames_evctnm_tab5 WHERE evctnm_countid='$ctid' LIMIT 1";
  RunQuery($sSQL);
  break; 
  
case "Tambah0":
  $newCTName = $_POST["newCountName"];
  $theID=$_POST["EN_tyid"];
  $sSQL = "INSERT eventcountnames_evctnm_tab0 (evctnm_eventtypeid, evctnm_countname) VALUES ('$theID','$newCTName')";
  RunQuery($sSQL);
  break;

case "Tambah1":
  $newCTName = $_POST["newCountName"];
  $theID=$_POST["EN_tyid"];
  $sSQL = "INSERT eventcountnames_evctnm_tab1 (evctnm_eventtypeid, evctnm_countname) VALUES ('$theID','$newCTName')";
  RunQuery($sSQL);
  break;
  
case "Tambah":
  $newCTName = $_POST["newCountName"];
  $theID=$_POST["EN_tyid"];
  $sSQL = "INSERT eventcountnames_evctnm (evctnm_eventtypeid, evctnm_countname) VALUES ('$theID','$newCTName')";
  RunQuery($sSQL);
  break;

case "Tambah2":
  $newCTName = $_POST["newCountName"];
  $theID=$_POST["EN_tyid"];
  $sSQL = "INSERT eventcountnames_evctnm_tab2 (evctnm_eventtypeid, evctnm_countname) VALUES ('$theID','$newCTName')";
  RunQuery($sSQL);
  break;

case "Tambah3":
  $newCTName = $_POST["newCountName"];
  $theID=$_POST["EN_tyid"];
  $sSQL = "INSERT eventcountnames_evctnm_tab3 (evctnm_eventtypeid, evctnm_countname) VALUES ('$theID','$newCTName')";
  RunQuery($sSQL);
  break;

case "Tambah4":
  $newCTName = $_POST["newCountName"];
  $theID=$_POST["EN_tyid"];
  $sSQL = "INSERT eventcountnames_evctnm_tab4 (evctnm_eventtypeid, evctnm_countname) VALUES ('$theID','$newCTName')";
  RunQuery($sSQL);
  break;

case "Tambah5":
  $newCTName = $_POST["newCountName"];
  $theID=$_POST["EN_tyid"];
  $sSQL = "INSERT eventcountnames_evctnm_tab5 (evctnm_eventtypeid, evctnm_countname) VALUES ('$theID','$newCTName')";
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

// Get a list of the IBADAH counts currently associated with thisevent type
$cSQL0 = "SELECT evctnm_countid, evctnm_countname FROM eventcountnames_evctnm_tab0 WHERE evctnm_eventtypeid='$aTypeID' ORDER BY evctnm_countid";
$cOpps0 = RunQuery($cSQL0);
$numCounts0 = mysql_num_rows($cOpps0);
$nr0 = $numCounts0+2;
$cCountName0="";
if($numCounts0)
     {
     $cCountName0="";
     for($c = 1; $c <=$numCounts0; $c++){
        $cRow0 = mysql_fetch_array($cOpps0, MYSQL_BOTH);
        extract($cRow0);
        $cCountID0[$c] = $evctnm_countid;
        $cCountName0[$c] = $evctnm_countname;
     }
}

// Get a list of the nYANYIAN counts currently associated with thisevent type
$cSQL1 = "SELECT evctnm_countid, evctnm_countname FROM eventcountnames_evctnm_tab1 WHERE evctnm_eventtypeid='$aTypeID' ORDER BY evctnm_countid";
$cOpps1 = RunQuery($cSQL1);
$numCounts1 = mysql_num_rows($cOpps1);
$nr1 = $numCounts1+2;
$cCountName1="";
if($numCounts1)
     {
     $cCountName1="";
     for($c = 1; $c <=$numCounts1; $c++){
        $cRow1 = mysql_fetch_array($cOpps1, MYSQL_BOTH);
        extract($cRow1);
        $cCountID1[$c] = $evctnm_countid;
        $cCountName1[$c] = $evctnm_countname;
     }
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

// Get a list of the money counts currently associated with thisevent type
$cSQL2 = "SELECT evctnm_countid, evctnm_countname FROM eventcountnames_evctnm_tab2 WHERE evctnm_eventtypeid='$aTypeID' ORDER BY evctnm_countid";
$cOpps2 = RunQuery($cSQL2);
$numCounts2 = mysql_num_rows($cOpps2);
$nr2 = $numCounts2+2;
$cCountName2="";
if($numCounts2)
     {
     $cCountName2="";
     for($c = 1; $c <=$numCounts2; $c++){
        $cRow2 = mysql_fetch_array($cOpps2, MYSQL_BOTH);
        extract($cRow2);
        $cCountID2[$c] = $evctnm_countid;
        $cCountName2[$c] = $evctnm_countname;
     }
}

// Get a list of the Amplop counts currently associated with thisevent type
$cSQL3 = "SELECT evctnm_countid, evctnm_countname FROM eventcountnames_evctnm_tab3 WHERE evctnm_eventtypeid='$aTypeID' ORDER BY evctnm_countid";
$cOpps3 = RunQuery($cSQL3);
$numCounts3 = mysql_num_rows($cOpps3);
$nr3 = $numCounts3+2;
$cCountName3="";
if($numCounts3)
     {
     $cCountName3="";
     for($c = 1; $c <=$numCounts3; $c++){
        $cRow3 = mysql_fetch_array($cOpps3, MYSQL_BOTH);
        extract($cRow3);
        $cCountID3[$c] = $evctnm_countid;
        $cCountName3[$c] = $evctnm_countname;
     }
}

// Get a list of the Majelis counts currently associated with thisevent type
$cSQL4 = "SELECT evctnm_countid, evctnm_countname FROM eventcountnames_evctnm_tab4 WHERE evctnm_eventtypeid='$aTypeID' ORDER BY evctnm_countid";
$cOpps4 = RunQuery($cSQL4);
$numCounts4 = mysql_num_rows($cOpps4);
$nr4 = $numCounts4+2;
$cCountName4="";
if($numCounts4)
     {
     $cCountName4="";
     for($c = 1; $c <=$numCounts4; $c++){
        $cRow4 = mysql_fetch_array($cOpps4, MYSQL_BOTH);
        extract($cRow4);
        $cCountID4[$c] = $evctnm_countid;
        $cCountName4[$c] = $evctnm_countname;
     }
}

// Get a list of the Pelayanan khusus counts currently associated with thisevent type
$cSQL5 = "SELECT evctnm_countid, evctnm_countname FROM eventcountnames_evctnm_tab5 WHERE evctnm_eventtypeid='$aTypeID' ORDER BY evctnm_countid";
$cOpps5 = RunQuery($cSQL5);
$numCounts5 = mysql_num_rows($cOpps5);
$nr5 = $numCounts5+2;
$cCountName5="";
if($numCounts5)
     {
     $cCountName5="";
     for($c = 1; $c <=$numCounts5; $c++){
        $cRow5 = mysql_fetch_array($cOpps5, MYSQL_BOTH);
        extract($cRow5);
        $cCountID5[$c] = $evctnm_countid;
        $cCountName5[$c] = $evctnm_countname;
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

  	
    <tr>
      <td class="LabelColumn" align="center" width="15%" rowspan="<?php echo $nr0; ?>" colspan="1">
        <strong><?php echo gettext("Peribadahan"); ?></strong>
      </td>
    </tr>
    <?php
    for($c = 1; $c <=$numCounts0; $c++){
      ?>
      <tr>
        <td class="TextColumn" colspan="1" width="35%"><?php echo $cCountName0[$c]; ?></td>
        <td class="TextColumn" colspan="2" width="50%">
           <form name="DelEvCount" action="EditEventTypes.php" method="POST">
           <input type="hidden" name="EN_ctid" value="<?php echo $cCountID0[$c]; ?>">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Hapus0"); ?>" class="icButton")">
           </form>
        </td>
      </tr>
     <?php
     }
     ?>
	<td class="TextColumn" colspan="1" width="35%">
           <form name="AddEvCount" action="EditEventTypes.php" method="POST">
           <input type="text" name="newCountName" length="20" value="Detail baru">
        </td>
        <td class="TextColumn" colspan="2" width="50%">

           <input type="hidden" name="EN_ctid" value="new">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Tambah0"); ?>" class="icButton")">
           </form>
        </td>
      </tr>

  	<tr>
      <td class="LabelColumn" align="center" width="15%" rowspan="<?php echo $nr1; ?>" colspan="1">
        <strong><?php echo gettext("Nyanyian"); ?></strong>
      </td>
    </tr>
    <?php
    for($c = 1; $c <=$numCounts1; $c++){
      ?>
      <tr>
        <td class="TextColumn" colspan="1" width="35%"><?php echo $cCountName1[$c]; ?></td>
        <td class="TextColumn" colspan="2" width="50%">
           <form name="DelEvCount" action="EditEventTypes.php" method="POST">
           <input type="hidden" name="EN_ctid" value="<?php echo $cCountID1[$c]; ?>">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Hapus1"); ?>" class="icButton")">
           </form>
        </td>
      </tr>
     <?php
     }
     ?>

	<td class="TextColumn" colspan="1" width="35%">
           <form name="AddEvCount" action="EditEventTypes.php" method="POST">
           <input type="text" name="newCountName" length="20" value="Jenis Detail baru">
        </td>
        <td class="TextColumn" colspan="2" width="50%">

           <input type="hidden" name="EN_ctid" value="new">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Tambah1"); ?>" class="icButton")">
           </form>
        </td>
      </tr>
  
	  
  <tr><td colspan="4"></td></tr>
   <tr>
      <td class="LabelColumn" align="center" width="15%" rowspan="<?php echo $nr; ?>" colspan="1">
        <strong><?php echo gettext("Jumlah Kehadiran"); ?></strong>
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
           <input type="text" name="newCountName" length="20" value="Jenis Kehadiran Baru">
        </td>
        <td class="TextColumn" colspan="2" width="50%">

           <input type="hidden" name="EN_ctid" value="new">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Tambah"); ?>" class="icButton")">
           </form>
        </td>
	</tr>
  
  
  <tr><td colspan="4"></td></tr>	
    <tr>
      <td class="LabelColumn" align="center" width="15%" rowspan="<?php echo $nr5; ?>" colspan="1">
        <strong><?php echo gettext("Pelayanan Khusus"); ?></strong>
      </td>
    </tr>
    <?php
    for($c = 1; $c <=$numCounts5; $c++){
      ?>
      <tr>
        <td class="TextColumn" colspan="1" width="35%"><?php echo $cCountName5[$c]; ?></td>
        <td class="TextColumn" colspan="2" width="50%">
           <form name="DelEvCount" action="EditEventTypes.php" method="POST">
           <input type="hidden" name="EN_ctid" value="<?php echo $cCountID5[$c]; ?>">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Hapus5"); ?>" class="icButton")">
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
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Tambah5"); ?>" class="icButton")">
           </form>
        </td>
  </tr>
  
  
  <tr><td colspan="4"></td></tr>	
    <tr>
      <td class="LabelColumn" align="center" width="15%" rowspan="<?php echo $nr2; ?>" colspan="1">
        <strong><?php echo gettext("Jumlah Persembahan"); ?></strong>
      </td>
    </tr>
    <?php
    for($c = 1; $c <=$numCounts2; $c++){
      ?>
      <tr>
        <td class="TextColumn" colspan="1" width="35%"><?php echo $cCountName2[$c]; ?></td>
        <td class="TextColumn" colspan="2" width="50%">
           <form name="DelEvCount" action="EditEventTypes.php" method="POST">
           <input type="hidden" name="EN_ctid" value="<?php echo $cCountID2[$c]; ?>">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Hapus2"); ?>" class="icButton")">
           </form>
        </td>
      </tr>
     <?php
     }
     ?>
<tr>
	 <td class="TextColumn" colspan="1" width="35%">
           <form name="AddEvCount" action="EditEventTypes.php" method="POST">
           <input type="text" name="newCountName" length="20" value="Jenis Persembahan Baru">
        </td>
        <td class="TextColumn" colspan="2" width="50%">

           <input type="hidden" name="EN_ctid" value="new">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Tambah2"); ?>" class="icButton")">
           </form>
        </td>
  </tr>
 
  
  <tr><td colspan="4"></td></tr>	
    <tr>
      <td class="LabelColumn" align="center" width="15%" rowspan="<?php echo $nr3; ?>" colspan="1">
        <strong><?php echo gettext("Amplop Persembahan"); ?></strong>
      </td>
    </tr>
    <?php
    for($c = 1; $c <=$numCounts3; $c++){
      ?>
      <tr>
        <td class="TextColumn" colspan="1" width="35%"><?php echo $cCountName3[$c]; ?></td>
        <td class="TextColumn" colspan="2" width="50%">
           <form name="DelEvCount" action="EditEventTypes.php" method="POST">
           <input type="hidden" name="EN_ctid" value="<?php echo $cCountID3[$c]; ?>">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Hapus3"); ?>" class="icButton")">
           </form>
        </td>
      </tr>
     <?php
     }
     ?>
<tr>
	 <td class="TextColumn" colspan="1" width="35%">
           <form name="AddEvCount" action="EditEventTypes.php" method="POST">
           <input type="text" name="newCountName" length="20" value="Jenis Amplop Baru">
        </td>
        <td class="TextColumn" colspan="2" width="50%">

           <input type="hidden" name="EN_ctid" value="new">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Tambah3"); ?>" class="icButton")">
           </form>
        </td>
  </tr>
 

  <tr><td colspan="4"></td></tr>	
    <tr>
      <td class="LabelColumn" align="center" width="15%" rowspan="<?php echo $nr4; ?>" colspan="1">
        <strong><?php echo gettext("Majelis yang Bertugas"); ?></strong>
      </td>
    </tr>
    <?php
    for($c = 1; $c <=$numCounts4; $c++){
      ?>
      <tr>
        <td class="TextColumn" colspan="1" width="35%"><?php echo $cCountName4[$c]; ?></td>
        <td class="TextColumn" colspan="2" width="50%">
           <form name="DelEvCount" action="EditEventTypes.php" method="POST">
           <input type="hidden" name="EN_ctid" value="<?php echo $cCountID4[$c]; ?>">
           <input type="hidden" name="EN_tyid" value="<?php echo $aTypeID; ?>">
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Hapus4"); ?>" class="icButton")">
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
           <input type="submit" class="SmallText" name="Action" value="<?php echo gettext("Tambah4"); ?>" class="icButton")">
           </form>
        </td>
  </tr>
	  
	  
	  </table>
	  

<?php

require "Include/Footer.php";
?>
