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
			height: 257px;
			overflow: hidden;

		}
		#ticker li {
			height: 257px;
		}
		


            li { border: 0px solid #000099; margin: 0; padding: 0; }
            table { border: 0px solid #ccc; display: inline-table; margin: 0; padding: 0; }
			.boldtable, .boldtable TD, .boldtable TH
			{
			font-family:arial;
			font-size:14pt;
			color:navy;
			background-color:#FFFF99;
			height: 257px;
			}
          </style>
 
</head>
<BODY bgcolor="#FFFFFF">

<?
//Minggu Ini

// Get the list of custom person Laki2
$sSQL = "SELECT COUNT(per_ID) FROM person_per WHERE per_cls_ID < 3 AND per_Gender = 1 ";
$perintah = mysql_query($sSQL);
$laki = mysql_result($perintah,0);
// Get the list of custom person Perempuan
$sSQL = "SELECT COUNT(per_ID) FROM person_per WHERE per_cls_ID < 3 AND per_Gender = 2 ";
$perintah = mysql_query($sSQL);
$perempuan = mysql_result($perintah,0);


function rentang_usia ($klasumur, $umurawal, $umurakhir) {
		$sSQL = "SELECT CONCAT('<a href=PrintViewKlasUmur.php?GenderID=',per_Gender,'&amp;Klas=$klasumur&amp;Uawal=$umurawal&amp;Uakhir=$umurakhir&amp;lstID=1&amp;clsID=3 target=_blank >',IF(per_gender ='1', 'Laki laki', 'Perempuan'),'  ') as Jemaat , count(per_ID) as Jiwa
			FROM person_per
			WHERE per_gender <> 0 AND per_cls_ID < 3 AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL  $umurawal YEAR) <=  CURDATE()
			AND
			DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (  $umurakhir+1 ) YEAR) >=  CURDATE()
			group by per_gender";
		$perintah = mysql_query($sSQL);
		$i = 0;
		$total = 0;
		while ($hasilGD=mysql_fetch_array($perintah))
		{
		$i++;
		$total = ($total + $hasilGD[Jiwa]);
						extract($hasilGD);
						//Alternate the row color
		                  $sRowClass = AlternateRowStyle($sRowClass);
global $thestring;
$thestring[$klasumur]=$total;
}}



				//$tanggal='2013-01-06';
				$tanggal=$iTGL;

		echo "<div id=\"page\" >";				
		echo "<ul id=\"ticker\" style=\"display: table;\">";			
		
		rentang_usia(Balita,0,5); 
		rentang_usia(Anak,6,13); 
		rentang_usia(Remaja,14,17); 
		rentang_usia(Pemuda,18,21); 
		rentang_usia(Dewasa,22,54);
		rentang_usia(Lansia,55,200);

echo "<br>";		
echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Rentang Usia Jemaat<br>";
echo "<img src=\"graph_umurjemaat2.php?BLT=$thestring[Balita]&amp;ANK=$thestring[Anak]&amp;
			RMJ=$thestring[Remaja]&amp;PMD=$thestring[Pemuda]&amp;DWS=$thestring[Dewasa]&amp;
			LNS=$thestring[Lansia]&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";
		
echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Status Kewargaan<br>";
echo "<img src=\"graph_statuswarga.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Jenis Kelamin<br>";
echo "<img src=\"graph_jeniskelamin.php?lakilaki=$laki&amp;perempuan=$perempuan&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Jemaat Per-Kelompok<br>";
echo "<img src=\"graph_kelompok.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Golongan Darah<br>";
echo "<img src=\"graph_goldarah.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Status Perkawinan<br>";
echo "<img src=\"graph_statuskawin.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";		

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Bulan Kelahiran<br>";
echo "<img src=\"graph_ultah.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Umur Pernikahan<br>";
echo "<img src=\"graph_umurkawin.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Jenjang Pendidikan<br>";
echo "<img src=\"graph_statuspendidikan.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Informasi Pekerjaan<br>";
echo "<img src=\"graph_statuspekerjaan.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Jenjang Jabatan / Pangkat<br>";
echo "<img src=\"graph_statusjabatan.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Profesi / Keahlian<br>";
echo "<img src=\"graph_statusprofesi.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Minat<br>";
echo "<img src=\"graph_statusminat.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Minat Pelayanan<br>";
echo "<img src=\"graph_statusminatpelayanan.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";

echo "<li><table CLASS=\"boldtable\"><tr>";
echo "Hobi<br>";
echo "<img src=\"graph_statushobi.php?&amp;$refresh \" width=\"340\" ><br>" ;
echo "</tr></table></li>";






				
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