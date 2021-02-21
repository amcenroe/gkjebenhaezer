<?php
/*******************************************************************************
 *
 *  filename    : BackupDatabase2.php
 *  description : Creates a backup file of the database.
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

$backupFile = $sDATABASE . date("Y-m-d-H-i-s") . '.gz';
//$command = "mysqldump --opt -h $sSERVERNAME -u $sUSER -p $sPASSWORD $sDATABASE | gzip > $backupFile";
$command = 'mysqldump --opt -h ' . $sSERVERNAME . ' -u ' . $sUSER . ' -p\'' . $sPASSWORD . '\' ' . $sDATABASE . ' | gzip > ' . $backupFile;
system($command);
echo $command;

//$command = "mysqldump --opt -h $sSERVERNAME -u $sUSER -p $sPASSWORD $sDATABASE | gzip > $backupFile";
//echo $command;
//system($command);
//include 'closedb.php';
?> 