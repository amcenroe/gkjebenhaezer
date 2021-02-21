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
require "Include/Header-Report.php";


//Minggu Ini

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<b>MINGGU INI</b>";
				echo "<table  cellpadding=\"2\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody  align=\"center\" ><tr>";
				echo "<td><b>No</b></td>";
				echo "<td><b>Nama Lengkap</b></td>";
				echo "<td><b>Tgl Lahir</b></td>";
				echo "<td><b>Kelompok</b></td>";
				echo "</tr>";
							
			//	       $sSQL2 = "SELECT * FROM BacaanAlkitab WHERE WEEK(Tanggal) = WEEK('".$iTGL."') ORDER BY Tanggal ASC";
			//		         $sSQL2 = "SELECT * FROM BacaanAlkitab WHERE WEEK(Tanggal) = WEEK(curdate())";
			
			$sSQL2 = "SELECT per_ID, CONCAT(YEAR(NOW()),'-',per_BirthMonth,'-',per_BirthDay) as 'Tanggal',
									per_FirstName AS Nama,
									per_Workphone as Kelompok
								FROM person_per a 
								LEFT JOIN group_grp b ON TRIM(a.per_Workphone) = TRIM(b.grp_Name)
								WHERE
										WEEK(CONCAT(YEAR(NOW()),'-',per_BirthMonth,'-',per_BirthDay),0) = WEEK('".$iTGL."')
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
echo "<tr>";
echo "<td>".$a."</td>";
//echo "<td><a target=\"_blank\" href=\"PersonView.php?PersonID=" . $per_ID . "&mode=print\" style=\"text-decoration:none;\">".$Nama."</a></td>";
echo "<td>".$Nama."</a></td>";
echo "<td>".date2Ind($Tanggal,12)."</td>";
echo "<td>".$Kelompok."</a></td>";

echo "</tr>";
}		
						
			echo "</tbody></table>";
			echo "<br>";

//Minggu Depan

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<b>MINGGU DEPAN</b>";
				echo "<table  cellpadding=\"2\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody  align=\"center\" ><tr>";
				echo "<td><b>No</b></td>";
				echo "<td><b>Nama Lengkap</b></td>";
				echo "<td><b>Tgl Lahir</b></td>";
				echo "<td><b>Kelompok</b></td>";
				echo "</tr>";
							
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

			// Jika Bulan Desember 	
			
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
				echo "<tr>";
				echo "<td>".$a."</td>";
				//echo "<td><a target=\"_blank\" href=\"PersonView.php?PersonID=" . $per_ID . "&mode=print\" style=\"text-decoration:none;\">".$Nama."</a></td>";
				echo "<td>".$Nama."</a></td>";
				echo "<td>".date2Ind($Tanggal,12)."</td>";
				echo "<td>".$Kelompok."</a></td>";
				echo "</tr>";
		}

			// Jika Bulan Desember
			$iBulan=date( 'm');
			$iTahunDepan=date ('Y')+1;
			$iTGL= $iTahunDepan.'-1-1';
		//	echo $iTGL;
			if ($iBulan==12){
			
						$sSQL3 = "SELECT per_ID, CONCAT(YEAR(NOW()),'-',per_BirthMonth,'-',per_BirthDay) as 'Tanggal',
									per_FirstName AS Nama,
									per_Workphone as Kelompok
								FROM person_per a 
								LEFT JOIN group_grp b ON TRIM(a.per_Workphone) = TRIM(b.grp_Name)
								WHERE
										WEEK(CONCAT(YEAR(NOW()),'-',per_BirthMonth,'-',per_BirthDay),0) = WEEK('".$iTGL."')
										AND per_cls_ID<3 
								ORDER BY per_BirthMonth, per_BirthDay, per_WorkPhone";
					//	echo $sSQL3;	
				$rsJadwal3 = RunQuery($sSQL3);
					//Loop through the surat recordset
					while ($aRow = mysql_fetch_array($rsJadwal3))
					{
						extract($aRow);
						//Alternate the row style
						$a++;
						$sRowClass = AlternateRowStyle($sRowClass); 
						echo "<tr>";
						echo "<td>".$a."</td>";
						//echo "<td><a target=\"_blank\" href=\"PersonView.php?PersonID=" . $per_ID . "&mode=print\" style=\"text-decoration:none;\">".$Nama."</a></td>";
						echo "<td>".$Nama."</a></td>";
						echo "<td>".date2Ind($Tanggal,12)."</td>";
						echo "<td>".$Kelompok."</a></td>";
						echo "</tr>";
					}
		
						};
			echo "</tbody></table>";
	

				
					
				
				
?>
.				

	

<?php
require "Include/Footer-Short.php";
?>
