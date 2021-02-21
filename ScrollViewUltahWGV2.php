<?php
/*******************************************************************************
 *
 *  filename    : PrintViewUltahWG.php
 *  last change : 2003-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
// require "Include/Header-Print.php";
// Get the Gol Darah ID from the querystring
$iBulan = FilterInput($_GET["Bulan"]);
$iTGL = FilterInput($_GET["TGL"]);
$bln = $iBulan;

 
$hariini = strtotime(date("Ymd"));
$minggukemaren = strtotime('last Sunday', $hariini);
$minggudepan = strtotime('next Sunday', $hariini);


if ($iTGL==''){$iTGL=date( 'Y-m-d');}

			

//echo date( 'Y-m-d', $hariini)."<br>";
//echo date( 'Y-m-d', $minggukemaren)."<br>";
//echo date( 'Y-m-d', $minggudepan);

 if (( $bln == 1 )) {
	     $BULAN="Januari";
	    } elseif (( $bln == 2 )) {
	      $BULAN="Februari";
	    } elseif (( $bln == 3 )) {
	      $BULAN="Maret";
	    } elseif (( $bln == 4 )) {
	      $BULAN="April";
	    } elseif (( $bln == 5 )) {
	      $BULAN="Mei";
	    } elseif (( $bln == 6 )) {
	      $BULAN="Juni";
	    } elseif (( $bln == 7 )) {
	      $BULAN="Juli";
	    } elseif (( $bln == 8 )) {
	      $BULAN="Agustus";
	 	} elseif (( $bln == 9 )) {
	      $BULAN="September";
	    } elseif (( $bln == 10 )) {
	      $BULAN="Oktober";
	    } elseif (( $bln == 11 )) {
	      $BULAN="Nopember";
	    } elseif (( $bln == 12 )) {
	      $BULAN="Desember";
	    }

$Judul = "Informasi Ulang Tahun Warga - ".date2Ind($iTGL,1); 
?>

<!doctype html>
<head>
<meta charset="utf-8" />

<link href="resources/css/global.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

          <style type="text/css">
		#ticker {
			height: 100px;
			overflow: hidden;

		}
		#ticker li {
			height: 100px;
		}
		


            li { border: 0px solid #000099; margin: 0; padding: 0; }
            table { border: 0px solid #ccc; display: inline-table; margin: 0; padding: 0; }
			.boldtable, .boldtable TD, .boldtable TH
			{
			font-family:arial;
			font-size:24pt;
			color:navy;
			background-color:#FFFF99;
			height: 100px;
			}
          </style>
 
</head>
<BODY bgcolor="#FFFF99">

<?
//Minggu Ini

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<table  cellpadding=\"2\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody  align=\"center\" ><tr>";
				echo "<td colspan=\"4\"><font face=\"Arial\" size=\"3\"><b>Warga Jemaat yang ber-ulang tahun minggu ini</b></font></td>";

				echo "</tr>";
				echo "</tbody></table>";
			echo "<br>";
		echo "<div id=\"page\" >";				
		echo "<ul id=\"ticker\" style=\"display: table;\">";			
			//	       $sSQL2 = "SELECT * FROM BacaanAlkitab WHERE WEEK(Tanggal) = WEEK('".$iTGL."') ORDER BY Tanggal ASC";
			//		         $sSQL2 = "SELECT * FROM BacaanAlkitab WHERE WEEK(Tanggal) = WEEK(curdate())";
			
			$sSQL2 = "SELECT per_ID, CONCAT(YEAR(NOW()),'-',per_BirthMonth,'-',per_BirthDay) as 'Tanggal',
									per_FirstName AS Nama,
									per_Workphone as Kelompok
								FROM person_per a 
								LEFT JOIN group_grp b ON TRIM(a.per_Workphone) = TRIM(b.grp_Name)
								WHERE
										WEEK(CONCAT(YEAR(NOW()),'-',per_BirthMonth,'-',per_BirthDay),0) = WEEK('".$iTGL."')+1
										AND per_cls_ID<3 
								ORDER BY per_BirthMonth, per_BirthDay, per_WorkPhone";
			
			
	//echo $sSQL2;
	$a=0;
	$rsJadwal2 = RunQuery($sSQL2);
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal2))
        {
                extract($aRow);
                //Alternate the row style
				$a++;
                $sRowClass = AlternateRowStyle($sRowClass); 
echo "<li><table CLASS=\"boldtable\"><tr>";

$photoFile = "Images/Person/thumbnails/" . $per_ID . ".jpg";
        if (file_exists($photoFile))
        {
		echo "<td><img src=\"Images/Person/thumbnails/" . $per_ID . ".jpg\" height=\"90\" ></td>";
		} else {
		echo "<td><img src=\"Images/NoPhoto.gif\" height=\"90\"  width=\"73\"></td>";

		}

//echo "<td><a target=\"_blank\" href=\"PersonView.php?PersonID=" . $per_ID . "&mode=print\" style=\"text-decoration:none;\">".$Nama."</a></td>";

echo "<td width=\"800\"> ".date2Ind($Tanggal,12)."  [".trim($Kelompok)."] <br>".$Nama."</td>";

echo "</tr></table></li>";
}		
						
	echo "</ul></div>";
				
				
?>
<script>

	function tick(){
		$('#ticker li:first').slideUp( function () { $(this).appendTo($('#ticker')).slideDown(); });
	}
	setInterval(function(){ tick () }, 5000);


</script>

</body>
</html>