<?php
/*******************************************************************************
 *
 *  filename    : ScrollViewPersembahanBulanan.php
 *  last change : 2003-01-29
 *
 *  Copyright 
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
$filename="PrintViewPersembahanBulanan.php";
require "Include/Config.php";
require "Include/Functions.php";
// require "Include/Header-Print.php";
// Get the Gol Darah ID from the querystring
$iBulan = FilterInput($_GET["Bulan"]);
$iTGL = FilterInput($_GET["TGL"]);
$iKodeTI = FilterInput($_GET["KodeTI"]);
$iNamaTI = FilterInput($_GET["NamaTI"]);
$iPukul = FilterInput($_GET["Pukul"]);

$bln = $iBulan;

if (strlen($iTGL>0))
{
$iTGL = FilterInput($_GET["TGL"]);
$minggukemaren = date("Y-m-d", strtotime('last Sunday', strtotime($iTGL)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', strtotime($iTGL)));
}else
{
$hariini = strtotime(date("Y-m-d"));
$iTGL = date("Y-m-d", strtotime('last Sunday', $hariini));
$mingguterakhir = date("Y-m-d", strtotime('last Sunday', $hariini));
$minggukemaren = date("Y-m-d", strtotime('-1 week', strtotime($mingguterakhir)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', $hariini));
}

if (strlen($iKodeTI>0)){$sSQLKodeTI =  " AND KodeTI = ".$iKodeTI ;$STRJudulTI="<br> Tempat Ibadah : ".$iNamaTI." ";};
if (strlen($iPukul>0)){$sSQLPukul =  " AND Pukul = '".$iPukul."'" ;$STRJudulPK="- Pukul : ".$iPukul." ";};



$Judul = "Informasi Rekapitulasi Persembahan Bulanan <br>Hari: ".date2Ind($iTGL,1)." ".$STRJudulTI."".$STRJudulPK; 


?>

<!doctype html>
<head>
<meta charset="utf-8" />

<link href="resources/css/global.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

          <style type="text/css">
		#ticker {
			height: 80x;
			overflow: hidden;

		}
		#ticker li {
			height: 80x;
		}
		


            li { border: 0px solid #000099; margin: 0; padding: 0; }
            table { border: 0px solid #ccc; display: inline-table; margin: 0; padding: 0; }
			.boldtable, .boldtable TD, .boldtable TH
			{
			font-family:arial;
			font-size:45pt;
			color:navy;
			background-color:#E6E6FA;
			height: 40px;
			}
          </style>
 
</head>
<BODY bgcolor="#E6E6FA">
<style>


</style>
<?php
//Header
				echo "<table  cellpadding=\"0\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=1366 bgcolor=\"#FFFFFF\">";
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td colspan=\"8\" ><font face=\"Arial\"><u><b>Persembahan PPPG ( Kartu Biru ) , ".date2Ind($iTGL,1)."</b></u></font></td>";
				echo "</tr>";
				echo "<tr>";
				
				echo "<td width=1><b>.</b></td><td width=120><font face=\"Arial\"><b>Kelompok</b></font></td>";
				echo "<td width=100><font face=\"Arial\"><b>Nomor Kartu</b></font></td>";
				echo "<td width=160><font face=\"Arial\"><b>Kode </b</font></td>";
				echo "<td width=60><font face=\"Arial\"><b> Bulan </b></font></td>";
				
				
				echo "<td width=160><font face=\"Arial\"><b> </b></font></td>";
				echo "<td width=160><font face=\"Arial\"><b>Persembahan (Rp)</b></font></td>";
				echo "</tr>";
				echo "</tbody></table>";
				
				
//Minggu Ini

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				//echo " <b>MINGGU INI</b> ";
		echo "<div id=\"page\" >";				
		echo "<ul id=\"ticker\" style=\"display: table;\">";		

	

		$sSQL2 = "SELECT a.*, b.per_FirstName as NamaWarga FROM PersembahanPPPG a 
		LEFT JOIN person_per b ON a.NomorKartu = b.per_ID
		WHERE Tanggal = '".$iTGL."'  ".$sSQLKodeTI." ".$sSQLPukul."      ";
		//	echo $sSQL2;
		$rsJadwal2 = RunQuery($sSQL2);
		$i = 0;
         //Loop through the surat recordset
		 
        while ($aRow = mysql_fetch_array($rsJadwal2))
        {
			$i++;
            extract($aRow);
            //Alternate the row style
            $sRowClass = AlternateRowStyle($sRowClass); 
			
		//Kode Nama
	//	if (isset($KodeNama)){$KodeNama=$KodeNama;}else{
			$string = $NamaWarga;
			$words = explode(' ', $string);
			//var_dump($words);

			$to_keep = array_slice($words, 0, 3);
			//var_dump($to_keep);

			$final_string = implode(' ', $to_keep);

			//Kode Nama
			if ($to_keep[0]=="I"){
			$KodeNama = strtoupper(substr($to_keep[1], 0, 2));
			$KodeNama2 =   strtoupper(substr($to_keep[2], 0, 2));

			}else{
					$KodeNama = strtoupper(substr($to_keep[0], 0, 2));

					if ($to_keep[1]<>""){
						$KodeNama2 = strtoupper(substr($to_keep[1], 0, 2));
						} else {
						$KodeNama2 =   strtoupper(substr($to_keep[0], -2));
						}
				}		
	//		}
			
			echo "<li><table CLASS=\"boldtable\"><tr>";
		//	echo "<td> ".$i." </td>";
			echo "<font face=\"Arial\">";
		//	echo "<td width=\"90\">  ".date2Ind($Tanggal,0)."  </td>";
			if ($Kelompok=="0"){ $Kelompok = "NN"; $NomorKartu = "NN"; $KodeNama = "NN";}
			echo "<td width=\"200\"> ".$Kelompok." </td>";
			echo "<td width=\"340\"> ".$NomorKartu." </td>";
			echo "<td width=\"340\"> ".$KodeNama."".$KodeNama2." </td>";
			echo "<td width=\"20\" align=\"center\"> ".$Bulan1;if (strlen($Bulan2>0)){echo "-".$Bulan2;};echo " </td>";
			echo "<td width=\"155\" align=\"right\">   </td>";
			echo "<td width=\"155\" align=\"right\">   </td>";
			$Total=$Bulanan+$Syukur+$ULK;
			echo "<td width=\"160\" align=\"right\"> ".currency(' ',$Total,'.',',00')." </td>";	
			echo "</font>";
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
