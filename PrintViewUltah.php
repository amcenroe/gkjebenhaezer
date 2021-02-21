<?php
/*******************************************************************************
 *
 *  filename    : PrintViewUltah.php
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
$Urutan="grp_Description ASC,";
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

			

//echo date( 'Y-m-d', $hariini)."<br>";
//echo date( 'Y-m-d', $minggukemaren)."<br>";
//echo date( 'Y-m-d', $minggudepan);

$Judul = "Informasi Ulang Tahun Warga - ".date2Ind($iTGL,1); 
require "Include/Header-Report.php";


//Minggu Ini

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<b>Hari Ulang Tahun Warga selama Tahun ".date('Y')."</b>";
				echo "<table  cellpadding=\"2\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody  align=\"center\" ><tr>";
				echo "<td><b>No</b></td>";
				echo "<td><a href=\"PrintViewUltah.php?Urut=Nama\" style=\"text-decoration:none;\"><b>Nama Lengkap</b></a></td>";
				echo "<td><a href=\"PrintViewUltah.php?Urut=Tanggal\" style=\"text-decoration:none;\"><b>Tanggal Lahir</b></a></td>";
				echo "<td><a href=\"PrintViewUltah.php?Urut=Tanggal\" style=\"text-decoration:none;\"><b>Hari & Tgl Ulang Tahun</b></a></td>";
				echo "<td><a href=\"PrintViewUltah.php?Urut=Umur\" style=\"text-decoration:none;\"><b>Umur</b></a></td>";
				echo "<td><a href=\"PrintViewUltah.php?Urut=Kelompok\" style=\"text-decoration:none;\"><b>Kelompok</b></a></td>";
				
				echo "</tr>";
							
			$sSQL2 = "SELECT per_ID, CONCAT(YEAR(NOW()),'-',per_BirthMonth,'-',per_BirthDay) as 'Tanggal',
						date_format(now(), '%Y') - date_format(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay), '%Y') 
						- (date_format(now(), '00-%m-%d') < date_format(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay), '00-%m-%d')) as Umur,
						CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay) as 'TanggalLahir',
						per_BirthYear, per_BirthMonth, per_BirthDay,
									per_FirstName AS Nama,
									grp_Description as Kelompok
								FROM person_per a 
								LEFT JOIN group_grp b ON TRIM(a.per_Workphone) = b.grp_Name
								WHERE
								per_cls_ID<3 
								ORDER BY $Urutan per_BirthMonth, per_BirthDay, per_WorkPhone";
			
			
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
echo "<td align=\"center\">".date2Ind($TanggalLahir,2)."</a></td>";
echo "<td>";if ($Tanggal=='2013-0-0'){echo "-";}else{echo date2Ind($Tanggal,12);};echo "</td>";
echo "<td align=\"center\">".FormatAge($per_BirthMonth,$per_BirthDay,$per_BirthYear,0)."</a></td>";
echo "<td>".$Kelompok."</a></td>";

echo "</tr>";
}		
						
			echo "</tbody></table>";
			echo "<br>";

require "Include/Footer-Short.php";
?>
