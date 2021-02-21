<?PHP
require "Include/Config.php";
require "Include/Functions.php";

		// Get Data ScrollRT
		$sSQL = "SELECT * FROM ScrollRTWG  ORDER BY ScrollRTWGID DESC LIMIT 1";
		$rsKegiatan = RunQuery($sSQL);
		extract(mysql_fetch_array($rsKegiatan));
		
			$sScrollRTWGID = $ScrollRTWGID;
			$sTanggal = $Tanggal;
			$sKeterangan = $Keterangan;
			$sDateEntered = $DateEntered;
			$sEnteredBy = $EnteredBy;
			$sDateLastEdited = $DateLastEdited;
			$sEditedBy = $EditedBy;
			
			
?>

<BODY bgcolor="#000c94">

<marquee direction="left" scrollamount="5" behavior="scroll" style="height: 40px; color: #ffffff; font-size: 30px; font-family: Arial; background-color: #000c94;">
<?echo $sKeterangan;?>
</marquee>

</BODY>