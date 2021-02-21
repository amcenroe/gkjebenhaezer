<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPersembahanPPPG.php
 *  last change : 2003-01-29
 *
 *  Copyright 
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
$filename="PrintViewPersembahanPPPG.php";
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
//echo date("Y-m-d", $hariini);
//echo "<br>";
//echo $mingguterakhir;
//echo "<br>";
//echo $minggukemaren;
//echo "<br>";
//echo $minggudepan;

}



if (strlen($iKodeTI>0)){$sSQLKodeTI =  " AND a.KodeTI = ".$iKodeTI ;$STRJudulTI="<br> Tempat Ibadah : ".$iNamaTI." ";}
if (strlen($iPukul>0)){$sSQLPukul =  " AND a.Pukul = '".$iPukul."'" ;$STRJudulPK="- Pukul : ".$iPukul." ";}
 

$Judul = "Informasi Rekapitulasi Persembahan PPPG <br>Hari: ".date2Ind($iTGL,1)." ".$STRJudulTI."".$STRJudulPK; 
require "Include/Header-Report.php";

?>
	<table  cellpadding=0 border=0 cellpadding=0 cellspacing=0 width=700>
	<tr>
	<td align="left">
	<?php
	$sSQL0 = "SELECT Tanggal FROM PersembahanPPPG a
			LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis
			WHERE Tanggal < '".$iTGL."' $sSQLKodeTI $sSQLPukul ORDER BY Tanggal Desc LIMIT 1 ";
			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL0);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
				$i++;
				extract($aRow);
				echo "<a href=\"".$filename."?TGL=".$Tanggal."\"  ><p class=\"help\" title=\"Data Sebelumnya Tgl: ".date2Ind($Tanggal,2)." \">";
				echo "<< </a>";
			}
				
	?>
	</td>
	<td><i style="font-family: Arial;"></i></td>
	<td align="right">
	<?php
	$sSQL0 = "SELECT Tanggal FROM PersembahanPPPG a
			LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis
			WHERE Tanggal > '".$iTGL."' $sSQLKodeTI $sSQLPukul ORDER BY Tanggal Asc LIMIT 1 ";
			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL0);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
				$i++;
				extract($aRow);
				echo "<a href=\"".$filename."?TGL=".$Tanggal."\"  ><p class=\"help\" title=\"Data Berikutnya Tgl: ".date2Ind($Tanggal,2)." \">";
				echo ">> </a>";
			}
				
	?>
	</td>
	</tr>
	</table>
<?php
			
			

// SUmary
				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				echo "<b>LAPORAN RINGKASAN :</b>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=700>";
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td colspan=\"4\" ><b>Nomor</b></td>";
				echo "<td colspan=\"2\" ><b>Persembahan (Rupiah)</b></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td><b>Urut</b></td><td><b>Kode</b></td><td><b>Keterangan</b><td><b>Jml Amplop/Kartu</b></td>";
				echo "<td><b>Sub Total</b></td><td><b>Total</b></td>";
				
				echo "</tr>";

			$sSQL = "SELECT a.*,b.*, SUM(a.JmlAmplop) as JmlAmplop, COUNT(a.Bulanan) as AmplopBulanan, SUM(a.Bulanan) as Bulanan FROM PersembahanPPPG a
			LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis
			WHERE Tanggal = '".$iTGL."' $sSQLKodeTI $sSQLPukul GROUP BY a.Pos ";
			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
						
				$i++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				$PosSubTotalBulanan+=$Bulanan;
				echo "<tr>";
				echo "<td>".$i."</td>";
				echo "<td>".$Pos."</td>";
				echo "<td><a href=\"PrintViewPersembahanKartuPPPG.php?TGL=".$iTGL."&Pos=".$Pos."\" target=\"_blank\"  STYLE=\"TEXT-DECORATION: NONE\" >".$Keterangan." </a></td>";
				echo "<td>";if (strlen($JmlAmplop>0)){echo $JmlAmplop;}else{echo $AmplopBulanan;};echo "</td>";
				echo "<td align=\"right\">".currency(' ',$Bulanan,'.',',00')."</td>";
				echo "</tr>";
			}
			echo "<tr>";
			echo "<td align=\"right\" colspan=\"5\">Total Persembahan</td>";
			echo "<td align=\"right\"><b>".currency(' ',$PosSubTotalBulanan,'.',',00')."</b></td>";
			echo "</tr>";
			echo "</tbody></table>";
			echo "<br>";
			echo "<br>";
			

//Detail			

//Minggu Ini

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				
				echo "<b>LAPORAN DETAIL :</b>";
				echo "<table  cellpadding=\"0\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=700>";
				echo "<tbody  align=\"center\" >";
				echo "<tr>";
				echo "<td colspan=\"4\" ><b>Nomor</b></td>";
				echo "<td rowspan=\"2\" ><b>Bulan</b></td>";
				echo "<td colspan=\"3\" ><b>Persembahan (Rupiah)</b></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td><b>Urut</b></td><td><b>Kelompok</b></td><td><b>Kartu</b><td><b>Kode Nama</b></td>";
				echo "<td><b>Detail</b></td><td><b>Sub Total</b></td><td><b>Total</b></td>";
				
				echo "</tr>";

	$sSQL1 = "SELECT a.Tanggal, a.Pukul ,SUM(a.Bulanan) as SubTotalBulanan, b.NamaTI	FROM PersembahanPPPG a
	LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI 
	WHERE Tanggal = '".$iTGL."' $sSQLKodeTI $sSQLPukul GROUP BY Pukul ORDER BY  a.KodeTI, a.Pukul" ;
	//echo $sSQL1;
	$rsJadwal1 = RunQuery($sSQL1);
	$a = 0;
	$GTBulanan=0;
	$GTSyukur=0;
	$GTULK=0;
    //Loop through the surat recordset
    while ($aRow = mysql_fetch_array($rsJadwal1))
    {
	$a++;

    extract($aRow);
    //Alternate the row style
    $sRowClass = AlternateRowStyle($sRowClass); 
	$GTBulanan+=$SubTotalBulanan;
		echo "<tr>";
		echo "<td bgcolor=\"#48D1CC\" colspan=\"9\" >Tempat Ibadah: <b>".$NamaTI."</b> , Pukul: <b>".$Pukul." </td>";
		echo "</tr>";
							
		$sSQL2 = "SELECT a.Pos, b.* FROM PersembahanPPPG a
		LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis 
		WHERE Tanggal = '".$iTGL."' AND Pukul = '".$Pukul."' GROUP BY Pos ";
		//echo $sSQL2;
		$rsJadwal2 = RunQuery($sSQL2);
		$i = 0;
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal2))
        {
		    extract($aRow);
			//Alternate the row style
			$sRowClass = AlternateRowStyle($sRowClass); 
			
			echo "<tr>";
			echo "<td bgcolor=\"#D5FEFE\" >Kode: <b>".$Pos."</b></td>";
			echo "<td align=\"left\" bgcolor=\"#D5FEFE\" colspan=\"7\" >Jenis Persembahan: <b>".$Keterangan."</b></td>";
		
			echo "</tr>";
		
			$sSQL3 = "SELECT a.*,b.* FROM PersembahanPPPG a
			LEFT JOIN JenisPPPG b ON a.Pos = b.KodeJenis
			WHERE Tanggal = '".$iTGL."' AND Pukul = '".$Pukul."' AND Pos = '".$Pos."' ORDER BY a.Kelompok ";
			//echo $sSQL3;
			$rsJadwal3 = RunQuery($sSQL3);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal3))
			{
						
				$i++;
				extract($aRow);
				//Alternate the row style
				$sRowClass = AlternateRowStyle($sRowClass); 
				$PosSubTotalBulanan+=$Bulanan;
				echo "<tr>";
				echo "<td>".$i."</td>";
				if (strlen($JmlAmplop>0)){$Kelompok='Amplop Biru Biasa';};
				echo "<td>".$Kelompok."</td>";
				echo "<td>".$NomorKartu."</td>";
				echo "<td>".$KodeNama;if (strlen($JmlAmplop>0)){echo "Jumlah Amplop : ".$JmlAmplop;};"</td>";
				echo "<td>".$Bulan1;if (strlen($Bulan2>0)){echo " - ".$Bulan2;};echo "</td>";
				echo "<td align=\"right\">".currency(' ',$Bulanan,'.',',00')."</td>";
				echo "</tr>";
			}
			echo "<tr>";
			echo "<td align=\"right\" colspan=\"5\">Sub Total ".$Keterangan."</td>";
			echo "<td align=\"right\"></td>";
			echo "<td align=\"right\"><b>".currency(' ',$PosSubTotalBulanan,'.',',00')."</b></td>";
			echo "</tr>";
			
		}
	echo "<tr>";
	echo "<td align=\"right\" colspan=\"5\">Total Persembahan per Tempat Ibadah</td>";
	echo "<td align=\"right\" colspan=\"2\"></td>";
	echo "<td align=\"right\"><b>".currency(' ',$SubTotalBulanan,'.',',00')."</b></td>";
	echo "</tr>";
	}

	echo "<tr>";
	echo "<td colspan=\"5\"><b>TOTAL PERSEMBAHAN per ".date2Ind($Tanggal,1)."</b></td>";
	echo "<td colspan=\"3\" align=\"right\"><b>Rp. ".currency(' ',$GTBulanan,'.',',00')."</b></td>";
	echo "</tr>";

	echo "</tbody></table>";
	
				
?>
.				

	

<?php
require "Include/Footer-Short.php";
?>
