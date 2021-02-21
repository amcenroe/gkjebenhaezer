<?php
/*******************************************************************************
*
* filename : JadwalPelayanan2web.php
* last change : 2003-01-29
*
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
******************************************************************************/


// Include the function library
//require "Include/Config.php";
require "Include/ConfigWeb.php";

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function get_host($ip){
        $ptr= implode(".",array_reverse(explode(".",$ip))).".in-addr.arpa";
        $host = dns_get_record($ptr,DNS_PTR);
        if ($host == null) return $ip;
        else return $host[0]['target'];
}
			
$ipaddr = getRealIpAddr();
$hostygakses = get_host($ipaddr);
			
//Print_r ($_SESSION);
		$logvar1 = "Report";
		$logvar2 = "Jadwal Pelayanan (web) ";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "','" . $hostygakses . "','" . $ipaddr . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);


$iTahun = $_GET["Tahun"];
if ($iTahun == '' ) {
  $TGLSKR = date("Y-m-d");
  $THNSKR = date("Y");
} else {
  $TGLSKR = $iTahun . "-12-31";
  $THNSKR = $iTahun; 
}


 

//Print_r ($_SESSION);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>Jadwal Pelayanan</title>
	<link rel="stylesheet" type="text/css" href="Include/Style.css">
  <STYLE TYPE="text/css">
        P.breakhere {page-break-before: always}
</STYLE>

</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >


<?php

// echo "listing Liturgi";
/**********************
**  Liturgi Listing  **
**********************/
	//	require "$sHeaderFile";
        // Base SQL
        $sSQL = "select * from LiturgiGKJBekti where Tema <> '' AND Bacaan1 <> '' AND Nyanyian1 <> '' ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
                case "Warna":
                        $sSQL = $sSQL . " ORDER BY Warna ASC";	
                        break;
				case "Bahasa":
                        $sSQL = $sSQL . " ORDER BY Bahasa";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
        }
//echo $sSQL;
        $rsLiturgiCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsLiturgiCount);

  
        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

   ?>
		<br>
		<b><i>Tema dan Liturgi</i></b>

        <BR>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">

        <tr class="TableHeader">

				<td><?php echo gettext("Tanggal"); ?></td>
                <td><?php echo gettext("Keterangan"); ?></td>
				<td><?php echo gettext("Tema dan Pelayan Firman"); ?></td>				
				<td><?php echo gettext("Bacaan"); ?></td>
				<td><?php echo gettext("AyatPenuntun"); ?></td>
				<td><?php echo gettext("Nyanyian"); ?></td>			
				

        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.

				$Tanggal = "";
				$Warna = "";
				$Bahasa = "";
				$Keterangan = "";
				$Tema = "";
				$Bacaan1 = "";
				$BacaanAntara = "";
				$Bacaan2 = "";
				$BacaanInjil = "";
				$AyatPenuntunHK = "";
				$AyatPenuntunBA = "";
				$AyatPenuntunLM = "";
				$AyatPenuntunP = "";
				$AyatPenuntunNP = "";
				$Nyanyian1 = "";
				$Nyanyian2 = "";
				$Nyanyian3 = "";
				$Nyanyian4 = "";
				$Nyanyian5 = "";
				$Nyanyian6 = "";
				$Nyanyian7 = "";
				$Nyanyian8 = "";
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        
	
				<td><b><?php echo date2Ind($Tanggal,1) ?><br>
				<?php 
				//$weekNum = date("W",strtotime(date($Tanggal))) - date("W",strtotime(date("Y-m-01"))) ;
				//$weeNum = getWeeks($Tanggal,"sunday");
				echo '<br>Minggu ke-'.getWeeks("$Tanggal","sunday");
				?>
						<?php 
				$TGL=$Tanggal;
				$MGG=getWeeks("$Tanggal","sunday");
				$TGLIND=date2Ind($Tanggal,3);
				
				//echo $TGL; ?>
				
				</b></td>
				<td>
				Warna : <?php echo $Warna ?><br>
				<br>Ket : <?php echo $Keterangan ?><br>
				<br>Bahasa : <?php echo $Bahasa ?>
				<br><a target="_blank" href="TataIbadahMinggu<?php echo $MGG ; ?>.php?TGL=<?php echo $TGL ?>&BHS=<?php echo $Bahasa; ?>">
				Cetak Liturgi</a>
				<br>
				<?php
					//$weekNum = date("W") - date("W",strtotime(date("Y-m-01"))) + 1;
				//	$weekNum = date("W",strtotime(date($Tanggal)));
				//	echo '<br>Minggu ke:'.$weekNum ;
				?>
		
				
				</td>
				<td><b><i>
				
				<?
				
						$sSQL = "SELECT id FROM `jos2_content` WHERE `catid` =35 AND `publish_up` LIKE '".$Tanggal."%' LIMIT 1";
//echo $sSQL;
						$db1  = mysql_connect($sSERVERNAME2,$sUSER2,$sPASSWORD2);  
						$sel1 = mysql_select_db($sDATABASE2); 


							//$res1 = mysql_query($sSQL, $db1);
							$result2 = mysql_query($sSQL, $db1);
							$numrows = mysql_num_rows($result2);
								mysql_close;  	
								
								$db1  = mysql_connect($sSERVERNAME,$sUSER,$sPASSWORD);  
								$sel1 = mysql_select_db($sDATABASE);
								
							
							while($row = mysql_fetch_array($result2)){	
							echo "<a target=\"_blank\" href=\"http://www2.gkjbekasitimur.org/index.php/features-mainmenu-47/renungan-minggu/".$row[id]."\"";
							echo $row[id];echo ">";
						//	echo ">Klik disini utk Artikel Renungan Minggu</a>";
						//	echo $row[id];
							
							}

			
					?>	
						
				
				
				<font size=2><?php echo $Tema ?></font></a></i></b>
				<br>
				Pelayan Firman:
				<br>
				<?php
				echo "<table>";
	  // Pelayan Firman
	$sSQL = "SELECT * FROM JadwalPelayanFirman  a
left join DaftarPendeta b ON a.PelayanFirman = b.PendetaID 
left join LokasiTI c ON a.KodeTI = c.KodeTI
WHERE TanggalPF = '" . $Tanggal . "' AND Bahasa = '" . $Bahasa . "'";
$rsPelayanFirman = RunQuery($sSQL);
//extract(mysql_fetch_array($rsPelayanFirman));
   while ($aRow = mysql_fetch_array($rsPelayanFirman))
						{
							extract($aRow);
			echo "<tr><td><td valign=top style=\"width:70px\"><font size=\"2\">  " . $NamaTI . "<br>" . $WaktuPF . " </td><td>:</td><td><b>";
 if ($PelayanFirman<>"0"){ echo $NamaPendeta ;}else{ echo $PFnonInstitusi ;}
			echo "</b><br><i> " . $NamaGereja . "</i> </td></font><td></td></td>";  		
			
						}
					echo "</table>" ;
   
 			
?>				
				
				
				
				</td>				
				<td><?php 
				echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $Bacaan1 . "&mode=print\" > ".$Bacaan1."</a>";echo";";
				echo "<br><a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $BacaanAntara . "&mode=print\" > ".$BacaanAntara."</a>";echo";";
				echo "<br><a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $Bacaan2 . "&mode=print\" > ".$Bacaan2."</a>";echo";";
				echo "<br><a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $BacaanInjil . "&mode=print\" > ".$BacaanInjil."</a>";echo";";
							?></td>

				
				<td><?php  
				echo "(HK)";echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $AyatPenuntunHK . "&mode=print\" > ".$AyatPenuntunHK."</a>";echo";";
				echo "<br>(BA)";echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $AyatPenuntunBA . "&mode=print\" > ".$AyatPenuntunBA."</a>";echo";";
				echo "<br>(LM)";echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $AyatPenuntunLM . "&mode=print\" > ".$AyatPenuntunLM."</a>";echo";";
				echo "<br>(P)";echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $AyatPenuntunP . "&mode=print\" > ".$AyatPenuntunP."</a>";echo";";
				echo "<br>(NP)";echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $AyatPenuntunNP . "&mode=print\" > ".$AyatPenuntunNP."</a>";echo";";
				
				?></td>
				<td><?php
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian1, 0, strpos($Nyanyian1, ':')) ."\">" .$Nyanyian1."</a>";echo"<br>";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian2, 0, strpos($Nyanyian2, ':')) ."\">" .$Nyanyian2."</a>";echo"<br>";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian3, 0, strpos($Nyanyian3, ':')) ."\">" .$Nyanyian3."</a>";echo"<br>";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian4, 0, strpos($Nyanyian4, ':')) ."\">" .$Nyanyian4."</a>";echo"<br>";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian5, 0, strpos($Nyanyian5, ':')) ."\">" .$Nyanyian5."</a>";echo"<br>";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian6, 0, strpos($Nyanyian6, ':')) ."\">" .$Nyanyian6."</a>";echo"<br>";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian7, 0, strpos($Nyanyian7, ':')) ."\">" .$Nyanyian7."</a>";echo"<br>";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian8, 0, strpos($Nyanyian8, ':')) ."\">" .$Nyanyian8."</a>";echo"<br>";

					//		echo $Nyanyian1;echo"<br>";echo $Nyanyian2;echo"<br>";echo $Nyanyian3;echo"<br>";echo $Nyanyian4;echo"<br>";
					//		echo $Nyanyian5;echo"<br>";echo $Nyanyian6;echo"<br>";echo $Nyanyian7;echo"<br>";echo $Nyanyian8;echo"<br>";
				?></td>	
	

	

                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;










require "Include/Footer-Short.php";
?>
