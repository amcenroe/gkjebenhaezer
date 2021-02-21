<?php
/*******************************************************************************
 *
 *  filename    : ScrollViewUltahNikahWG.php
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

if (strlen($iTGL>0))
{
$iTGLY = date(Y,strtotime($iTGL));
$iTGLM = date(m,strtotime($iTGL));

$minggukemaren = date("Y-m-d", strtotime('last Sunday', strtotime($iTGL)));
$minggudepan = date("Y-m-d", strtotime('next Sunday', strtotime($iTGL)));

	$tanggal=$iTGL;
	$time = strtotime($tanggal);
	$monthago = date("Y-m-d", strtotime("-1 month", $time));
	$nextmonth = date("Y-m-d", strtotime("+1 month", $time));
	$iTGLawal = date(date2Ind($iTGL,11)."-1");
	$iTGLawalanggaran = date("Y-1-1");
//base on tanggal surat
//$sSQLTanggal =  " AND MONTH(Tanggal) = ".$iTGLM." AND YEAR(Tanggal) = ".$iTGLY ;
//base on tanggal terima
$sSQLTanggal =  " AND MONTH(ket1) = ".$iTGLM." AND YEAR(ket1) = ".$iTGLY ; 
$sSQLTanggalBulanKemaren =  "  MONTH(Tanggal) = ".date(n,strtotime('-1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('-1 month', $time))  ;
$sSQLTanggalBulanBesok =  "  MONTH(Tanggal) = ".date(n,strtotime('+1 month', $time))." AND YEAR(Tanggal) = ".date(Y,strtotime('+1 month', $time))  ;
$sSQLTanggalBulanIni =  "  Tanggal >= '".date($iTGLawalanggaran)."' AND Tanggal < '".date($iTGLawal)."'  ";
//echo $sSQLTanggalBulanIni;
}else

$Judul = "Informasi Ulang Tahun Perkawinan - ".date2Ind($iTGL,1); 
?>

<!doctype html>
<head>
<meta charset="utf-8" />

<link href="resources/css/global.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

          <style type="text/css">
		#ticker {
			height: 260px;
			overflow: hidden;

		}
		#ticker li {
			height: 260px;
		}
		


            li { border: 0px solid #000099; margin: 0; padding: 0; }
            table { border: 0px solid #ccc; display: inline-table; margin: 0; padding: 0; }
			.boldtable, .boldtable TD, .boldtable TH
			{
			font-family:arial;
			font-size:14pt;
			color:navy;
			background-color:#FFFF99;
			height: 80px;
			}

          </style>
 
</head>
<BODY bgcolor="#FFFF99">

<?

//Bulan Ini
				echo "<table  cellpadding=\"2\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<td colspan=\"4\"><font face=\"Arial\" size=\"3\"><b>Keluarga yang ber-ulang tahun Perkawinan</b></font></td>";
				echo "</table>";
			
echo "<div id=\"page\" >";				
		echo "<ul id=\"ticker\" style=\"display: table;\">";		

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;

				$sSQL2 = "SELECT a.fam_ID, fam_Workphone as Kelompok,  
				 
				CONCAT(date_format(date(now()),'%Y'),'-',DATE_FORMAT(fam_WeddingDate, '%m'),'-',DATE_FORMAT(fam_WeddingDate, '%d')) as Tanggal,
CONCAT(
(SELECT per_firstname from person_per where person_per.per_fmr_id = '1' AND person_per.per_fam_id = a.fam_id AND per_cls_ID<3),' - ',
(SELECT per_firstname from person_per where person_per.per_fmr_id = '2' AND person_per.per_fam_id = a.fam_id AND per_cls_ID<3)) as 'Nama'

FROM family_fam a
LEFT JOIN family_custom b ON a.fam_ID = b.fam_ID  

LEFT JOIN group_grp c ON TRIM(a.fam_Workphone) = TRIM(c.grp_Name)
WHERE  MONTH(fam_Weddingdate) = MONTH('".$iTGL."')
AND 
(
(SELECT per_firstname from person_per where person_per.per_fmr_id = '1' AND person_per.per_fam_id = a.fam_id AND per_cls_ID<3) <> ''
AND
(SELECT per_firstname from person_per where person_per.per_fmr_id = '2' AND person_per.per_fam_id = a.fam_id AND per_cls_ID<3) <> ''
)

ORDER BY DATE_FORMAT(fam_WeddingDate, '%m'), DATE_FORMAT(fam_WeddingDate, '%d'),
DATE_FORMAT(fam_WeddingDate, '%Y')";

			
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
echo "<td align=\"center\"><br>";


$photoFile = "Images/Family/thumbnails/" . $fam_ID . ".jpg";
        if (file_exists($photoFile))
        {
		echo "<img src=\"Images/Family/thumbnails/" . $fam_ID . ".jpg\" height=\"160\"   width=\"160\">";
		} else {
		echo "<img src=\"Images/NoFamPhoto.gif\" height=\"160\"  width=\"160\">";

		}
		
echo "<br>".$Nama."</a>";
echo "<br>".date2Ind($Tanggal,12)."";
echo "<br> Kelompok : ".$Kelompok."</a></td>";

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
