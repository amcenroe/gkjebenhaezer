<?php
/*******************************************************************************
 *
 *  filename    : PrintViewUltahNikahWG.php
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
require "Include/Header-Report.php";


//Bulan Ini

	if (strlen($iTGL>0))
	{
				echo "<tr><td>";
				echo "<a href=\"PrintViewUltahNikahWG.php?TGL=".$monthago."\"  >";
				echo "<< </a></td>";
				echo "<td colspan=\"6\" align=\"center\" > . <b> BULAN INI </b> . </td>";
				echo "<td align=\"right\">";
				echo "<a href=\"PrintViewUltahNikahWG.php?TGL=".$nextmonth."\"  >";
				echo ">> </a></td></tr>";
	}

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				
				echo "<table  cellpadding=\"2\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody  align=\"center\" ><tr>";
				echo "<td><b>No</b></td>";
				echo "<td><b>Nama Lengkap</b></td>";
				echo "<td><b>Tgl. Nikah</b></td>";
				echo "<td><b>Kelompok</b></td>";
				echo "</tr>";
							
			//	       $sSQL2 = "SELECT * FROM BacaanAlkitab WHERE WEEK(Tanggal) = WEEK('".$iTGL."') ORDER BY Tanggal ASC";
			//		         $sSQL2 = "SELECT * FROM BacaanAlkitab WHERE WEEK(Tanggal) = WEEK(curdate())";
			
	//		$sSQL2 = "SELECT per_ID, CONCAT(YEAR(NOW()),'-',per_BirthMonth,'-',per_BirthDay) as 'Tanggal',
	//								per_FirstName AS Nama,
	//								Deskripsi as Kelompok
	//							FROM person_per a 
	//							LEFT JOIN kelompok b ON TRIM(a.per_Workphone) = b.Kode
	//							WHERE
	//									WEEK(CONCAT(YEAR(NOW()),'-',per_BirthMonth,'-',per_BirthDay),0) = WEEK('".$iTGL."')
	//									AND per_cls_ID<3 
	//							ORDER BY per_BirthMonth, per_BirthDay, per_WorkPhone";
			

				$sSQL2 = "SELECT fam_Workphone as Kelompok,  
				 
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
echo "<tr>";
echo "<td>".$a."</td>";
//echo "<td><a target=\"_blank\" href=\"PersonView.php?PersonID=" . $per_ID . "&mode=print\" style=\"text-decoration:none;\">".$Nama."</a></td>";
echo "<td align=\"left\">".$Nama."</a></td>";
echo "<td>".date2Ind($Tanggal,12)."</td>";
echo "<td>".$Kelompok."</a></td>";

echo "</tr>";
}		
						
			echo "</tbody></table>";
	
				
				
?>
.				

	

<?php
require "Include/Footer-Short.php";
?>
