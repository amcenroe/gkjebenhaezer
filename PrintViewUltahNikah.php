<?php
/*******************************************************************************
 *
 *  filename    : PrintViewUltahNikah.php
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
$iUrut = FilterInput($_GET["Urut"]);
$iTGL = FilterInput($_GET["TGL"]);
$bln = $iBulan;

if ($iUrut=="Kelompok"){
$Urutan="Deskripsi ASC,";
} else if ($iUrut=="Nama"){
$Urutan="Nama ASC,";
} else if ($iUrut=="Umur"){
$Urutan="Umur ASC,";
} else if ($iUrut=="Tanggal"){
$Urutan="";
} else $Urutan="";

 
$hariini = strtotime(date("Ymd"));
$minggukemaren = strtotime('last Sunday', $hariini);
$minggudepan = strtotime('next Sunday', $hariini);


if ($iTGL==''){$iTGL=date( 'Y-m-d');}


$Judul = "Informasi Ulang Tahun Perkawinan - ".date2Ind($iTGL,1); 
require "Include/Header-Report.php";


//Minggu Ini

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<b>Hari Ulang Tahun Pernikahan Warga selama Tahun ".date('Y')."</b>";
				echo "<table  cellpadding=\"2\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody  align=\"center\" ><tr>";
				echo "<td><b>No</b></td>";
				echo "<td><a href=\"PrintViewUltahNikah.php?Urut=Nama\" style=\"text-decoration:none;\"><b>Nama Keluarga ( Suami - Istri )</b></a></td>";
				echo "<td><a href=\"PrintViewUltahNikah.php?Urut=Tanggal\" style=\"text-decoration:none;\"><b>Tgl Pernikahan</b></a></td>";
				echo "<td><a href=\"PrintViewUltahNikah.php?Urut=Tanggal\" style=\"text-decoration:none;\"><b>Hari & Tgl Ultah Nikah</b></a></td>";
				echo "<td><a href=\"PrintViewUltahNikah.php?Urut=Umur\" style=\"text-decoration:none;\"><b>Umur Pernikahan</b></a></td>";
				echo "<td><a href=\"PrintViewUltahNikah.php?Urut=Kelompok\" style=\"text-decoration:none;\"><b>Kelompok</b></a></td>";
				echo "</tr>";
							
				$sSQL2 = "SELECT fam_Workphone as Kelompok,  fam_WeddingDate as TanggalNikah, 
				date_format(now(), '%Y') - date_format(fam_WeddingDate, '%Y') - (date_format(now(), '00-%m-%d') < date_format(fam_WeddingDate, '00-%m-%d')) as Umur,		
				DATE_FORMAT(fam_WeddingDate, '%m') as BlnNikah, DATE_FORMAT(fam_WeddingDate, '%d') as TglNikah, DATE_FORMAT(fam_WeddingDate, '%Y') as ThnNikah,
				CONCAT(date_format(date(now()),'%Y'),'-',DATE_FORMAT(fam_WeddingDate, '%m'),'-',DATE_FORMAT(fam_WeddingDate, '%d')) as Tanggal,
CONCAT(
(SELECT per_firstname from person_per where person_per.per_fmr_id = '1' AND person_per.per_fam_id = a.fam_id AND per_cls_ID<3),' - ',
(SELECT per_firstname from person_per where person_per.per_fmr_id = '2' AND person_per.per_fam_id = a.fam_id AND per_cls_ID<3)) as 'Nama'

FROM family_fam a
LEFT JOIN family_custom b ON a.fam_ID = b.fam_ID  
LEFT JOIN group_grp c ON TRIM(a.fam_Workphone) = TRIM(c.grp_Name)
WHERE  
(
(SELECT per_firstname from person_per where person_per.per_fmr_id = '1' AND person_per.per_fam_id = a.fam_id AND per_cls_ID<3) <> ''
AND
(SELECT per_firstname from person_per where person_per.per_fmr_id = '2' AND person_per.per_fam_id = a.fam_id AND per_cls_ID<3) <> ''
)

ORDER BY $Urutan DATE_FORMAT(fam_WeddingDate, '%m'), DATE_FORMAT(fam_WeddingDate, '%d'),
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
echo "<td>".date2Ind($TanggalNikah,2)."</td>";
echo "<td>".date2Ind($Tanggal,12)."</td>";
echo "<td align=\"center\">".FormatAge($BlnNikah,$TglNikah,$ThnNikah,0)."</a></td>";
echo "<td>".$Kelompok."</a></td>";

echo "</tr>";
}		
						
			echo "</tbody></table>";
	
				
				
?>
.				

	

<?php
require "Include/Footer-Short.php";
?>
