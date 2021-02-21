<?php
/*******************************************************************************
 *
 *  filename    : PrintViewJadwalMajelis.php
 *  last change : 2003-01-29
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2003 Phillip Hullquist, Deane Barker, Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2013 Erwin Pratama for GKJ Bekasi Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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


//if ($iTGL==''){$iTGL=date( 'Y-m-d', $minggudepan);}

if ($iTGL==''){$iTGL=date( 'Y-m-d');}	
$hariini = strtotime($iTGL);		
$bulankemaren = date('Y-m-d',strtotime('last month', $hariini));
$bulandepan = date('Y-m-d',strtotime('next month', $hariini));
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

$Judul = "Informasi Jadwal Pelayan Peribadahan - ".date2Ind($iTGL,5); 
require "Include/Header-Report.php";

?>
	<table  cellpadding=0 border=0 cellpadding=0 cellspacing=0 width=700>
	<tr>
	<td align="left">
	<?php
	
	$sSQL0 = "SELECT Tanggal FROM JadwalPelayanPendukung
			WHERE MONTH(Tanggal) = MONTH('".$bulankemaren."') AND YEAR(Tanggal) = YEAR('".$bulankemaren."')  ORDER BY Tanggal Desc LIMIT 1 ";
	//		echo $sSQL0;
			$rsJadwal = RunQuery($sSQL0);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
				$i++;
				extract($aRow);
				echo "<a href=\"".$filename."?TGL=".$Tanggal."\"  ><p class=\"help\" title=\"Data Sebelumnya Tgl: ".date2Ind($Tanggal,2)." \">";
				echo "<<  </a>";
			}
				
	?>
	</td>
	<td><i style="font-family: Arial;"></i></td>
	<td align="right">
	<?php
	$sSQL0 = "SELECT Tanggal FROM JadwalPelayanPendukung
			WHERE MONTH(Tanggal) = MONTH('".$bulandepan."') AND YEAR(Tanggal) = YEAR('".$bulandepan."')  ORDER BY Tanggal Desc LIMIT 1 ";
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
				echo ">>  </a>";
			}
				
	?>
	</td>
	</tr>
	</table>
<?
function sapaan ($perid){

if ($perid!=''){

									$sSQL2 = "SELECT vol_id as jabid, per_gender as Gender, per_fmr_id as PerFmrId, vol_name as Jabatan
									from person_per , person2volunteeropp_p2vo, volunteeropportunity_vol
									where per_id = " . $perid . " AND per_id = p2vo_per_id AND p2vo_vol_id = vol_id limit 1
								";
						$perintah2 = mysql_query($sSQL2);
						

					//	echo $sSQL2;
							while ($hasilGD2=mysql_fetch_array($perintah2))
							{
							

							extract($hasilGD2);
							$jab=$hasilGD2[jabid] ;
							$panggilan=$hasilGD2[PerFmrId] ;
							$gender=$hasilGD2[Gender] ;
							
							//return $jab;
							//return $gender;
							if ($jab==1)
								return "Pdt.";
							elseif ($jab==2)
								return "Pnt.";
							elseif ($jab==3)
								return "Dkn.";								
							elseif (($gender==1 )AND( $panggilan==1))
								return "Bp.";
							elseif ($gender==2 AND $panggilan==2)
								return "Ibu.";
							elseif ($gender==1 AND $panggilan==3)
								return "Sdr.";	
							elseif ($gender==2 AND $panggilan==3)
								return "Sdri.";
							elseif ($gender==1 )
								return "Sdr.";	
							elseif ($gender==2 )
								return "Sdri.";	
							echo "";		   
							}
							
				}
}


function kebaktianpendukung ($tanggal) {

       $sSQL = "select a.* ,
			v.per_FirstName as NamaMultimedia1 , v.per_WorkPhone as KelNamaMultimedia1 , 
			w.per_FirstName as NamaMultimedia2 , w.per_WorkPhone as KelNamaMultimedia2 , 
			
			x.NamaTI as NamaTI, 
			y.grp_Name as KelSaranaIbadah , y.grp_Description as KelompokSaranaIbadah, 
			z.grp_Name as KelKolektan , z.grp_Description as KelompokKolektan
			
			FROM JadwalPelayanPendukung a		
		
			LEFT JOIN person_per v ON a.KodeMultimedia1 = v.per_ID
			LEFT JOIN person_per w ON a.KodeMultimedia2 = w.per_ID
			
			LEFT JOIN LokasiTI x ON a.KodeTI = x.KodeTI
			LEFT JOIN group_grp y ON a.KodeSaranaIbadah = y.grp_ID 
			LEFT JOIN group_grp z ON a.KodeKolektan = z.grp_ID 
			
			WHERE a.Tanggal='$tanggal' 
			
			ORDER BY Tanggal DESC, KodeTI ASC, Waktu ASC
	   ";
	   
	   echo "<br>";
//echo $sSQL;

$rsJadwal = RunQuery($sSQL);
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sJadwalPelayanPendukung_ID = ""; 	 	

                extract($aRow);
	
                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
 				if (strlen($KelompokSaranaIbadah)==0){
				}else{
				$saranaibadah=1;
				$varSaranaIbadah .= "<td> ". $Waktu."</td>";
				$varSaranaIbadah .= "<td> ".$KelompokSaranaIbadah."</td></tr>";
				$rowspan=$saranaibadah+$rowspan;
				}
        }
				if ($rowspan>0){
				echo "<tr><td rowspan=\"".$rowspan."\" > Sarana Ibadah & Usher<br> (Penyambut Umat)</td>";
				if ($saranaibadah>0) {echo $varSaranaIbadah;}
				}
}


function kebaktianpendukungkolektan ($tanggal) {

       $sSQL = "select a.* ,
			v.per_FirstName as NamaMultimedia1 , v.per_WorkPhone as KelNamaMultimedia1 , 
			w.per_FirstName as NamaMultimedia2 , w.per_WorkPhone as KelNamaMultimedia2 , 
			
			x.NamaTI as NamaTI, 
			y.grp_Name as KelSaranaIbadah , y.grp_Description as KelompokSaranaIbadah, 
			z.grp_Name as KelKolektan , z.grp_Description as KelompokKolektan
			
			FROM JadwalPelayanPendukung a		
		
			LEFT JOIN person_per v ON a.KodeMultimedia1 = v.per_ID
			LEFT JOIN person_per w ON a.KodeMultimedia2 = w.per_ID
			
			LEFT JOIN LokasiTI x ON a.KodeTI = x.KodeTI
			LEFT JOIN group_grp y ON a.KodeSaranaIbadah = y.grp_ID 
			LEFT JOIN group_grp z ON a.KodeKolektan = z.grp_ID 
			
			WHERE a.Tanggal='$tanggal' 
			
			ORDER BY Tanggal DESC, KodeTI ASC, Waktu ASC
	   ";
	   
	   echo "<br>";
//echo $sSQL;

$rsJadwal = RunQuery($sSQL);
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sJadwalPelayanPendukung_ID = ""; 	 	

                extract($aRow);
	
                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
 				if (strlen($KelompokKolektan)==0){
				}else{
				$kolektan=1;
				$varSaranaIbadah .= "<td> ". $Waktu."</td>";
				$varSaranaIbadah .= "<td> ".$KelompokKolektan."</td></tr>";
				$rowspan=$kolektan+$rowspan;
				}
        }
				if ($rowspan>0){
				echo "<tr><td rowspan=\"".$rowspan."\" > Kolektan</td>";
				if ($kolektan>0) {echo $varSaranaIbadah;}
				}
}


function kebaktianpendukungmultimedia ($tanggal) {

       $sSQL = "select a.* ,
			v.per_FirstName as NamaMultimedia1 , v.per_WorkPhone as KelNamaMultimedia1 , 
			w.per_FirstName as NamaMultimedia2 , w.per_WorkPhone as KelNamaMultimedia2 , 
			
			x.NamaTI as NamaTI, 
			y.grp_Name as KelSaranaIbadah , y.grp_Description as KelompokSaranaIbadah, 
			z.grp_Name as KelKolektan , z.grp_Description as KelompokKolektan
			
			FROM JadwalPelayanPendukung a		
		
			LEFT JOIN person_per v ON a.KodeMultimedia1 = v.per_ID
			LEFT JOIN person_per w ON a.KodeMultimedia2 = w.per_ID
			
			LEFT JOIN LokasiTI x ON a.KodeTI = x.KodeTI
			LEFT JOIN group_grp y ON a.KodeSaranaIbadah = y.grp_ID 
			LEFT JOIN group_grp z ON a.KodeKolektan = z.grp_ID 
			
			WHERE a.Tanggal='$tanggal' 
			
			ORDER BY Tanggal DESC, KodeTI ASC, Waktu ASC
	   ";
	   
	   echo "<br>";
//echo $sSQL;

$rsJadwal = RunQuery($sSQL);
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sJadwalPelayanPendukung_ID = ""; 	 	

                extract($aRow);
	
                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
 				if (strlen($KelompokKolektan)==0){
				}else{
				$multimedia=1;
				$varSaranaIbadah .= "<td> ". $Waktu."</td>";
				$varSaranaIbadah .= "<td> ".sapaan($KodeMultimedia1)."".shorten_string($NamaMultimedia1,2)." , "
												.sapaan($KodeMultimedia2)."".shorten_string($NamaMultimedia2,2)." </td></tr>";
				$rowspan=$multimedia+$rowspan;
				}
        }
				if ($rowspan>0){
				echo "<tr><td rowspan=\"".$rowspan."\" > Multimedia</td>";
				if ($multimedia>0) {echo $varSaranaIbadah;}
				}
}

				function filter_callback($val) {
				$val = trim($val);
				return $val != '';
				}

?>

<?
//Data Awal
				//$tanggal='2013-01-06';
				$tanggal=$iTGL;

// Jadwal Majelis				
				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				//echo "<b>".strtoupper(date2Ind($tanggal,1))."</b>";
				echo "<table  border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody  align=\"center\" ><tr>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Tanggal</b></font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Jam</b></font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Tempat Ibadah</b></font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Koordinator</b></font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Liturgos 1</b></font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Liturgos 2</b></font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Lektor 1</b></font></td>";
				//echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Lektor 2</b></font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Pend.SM</b></font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Pend.Remaja</b></font></td>";
				//echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Pend.Pemuda</b></font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Busana</b></font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Stola</b></font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Organis</b></font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"><b>Song Leader</b></font></td>";
				echo "</tr>";
				
	echo "<tr><br></tr>";		


		//$sSQL1 = "SELECT distinct(Tanggal) as tglsebulan, count(Tanggal) as jmltgl FROM `JadwalPelayanPendukung` WHERE MONTH(Tanggal) = MONTH('$tanggal')";
		$sSQL1 = "SELECT Tanggal as tglsebulan , count(Tanggal) as jmltgl FROM `JadwalPelayanPendukung` WHERE MONTH(Tanggal) = MONTH('$tanggal') GROUP BY Tanggal";
	//	echo $sSQL1;
		$rsJadwal1 = RunQuery($sSQL1);
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal1))
        {
			extract($aRow);
                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);
				
				       $sSQL2 = "select a.*, b.* , 
					   c.per_firstname as NamaOrganis, d.per_firstname as NamaSongLeader
					   FROM JadwalPelayanPendukung a
						LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
						LEFT JOIN person_per c ON a.KodeOrganis = c.per_ID
						LEFT JOIN person_per d ON a.KodeSongLeader = d.per_ID
							WHERE a.Tanggal='$tglsebulan'	ORDER BY a.KodeTI ASC ,Waktu ASC  ";

//	echo $sSQL2;
	$rsJadwal2 = RunQuery($sSQL2);
         //Loop through the surat recordset
		 $a=0; 
	 $ArrayNamaMajelis = array();
        while ($aRow = mysql_fetch_array($rsJadwal2))
        {
                extract($aRow);
                //Alternate the row style
				$a++;
                $sRowClass = AlternateRowStyle($sRowClass);
				
				array_push($ArrayNamaMajelis, $Majelis1);
				array_push($ArrayNamaMajelis, $Majelis2);
				array_push($ArrayNamaMajelis, $Majelis3);
				array_push($ArrayNamaMajelis, $Majelis4);
				array_push($ArrayNamaMajelis, $Majelis5);
				array_push($ArrayNamaMajelis, $Majelis6);
				array_push($ArrayNamaMajelis, $Majelis7);
				array_push($ArrayNamaMajelis, $Majelis8);
				

				echo "<tr>";
				if($a==1){
				echo "<td rowspan=\"".$jmltgl."\" ><font size=\"1\" style=\"font-family: Arial; \"> ".date2Ind($Tanggal,1)." </font></td>";
				}
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ".$Waktu." </font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ".$NamaTI." </font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ".$Majelis1." </font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ".$Majelis2." </font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ".$Majelis3." </font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ".$Majelis4." </font></td>";
				//echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ".$Majelis5." </font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ".$Majelis6." </font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ".$Majelis7." </font></td>";
				//echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ".$Majelis8." </font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ".$DressCode." </font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ".$Stola." </font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ". sapaan($KodeOrganis)."".$NamaOrganis." </font></td>";
				echo "<td><font size=\"1\" style=\"font-family: Arial; \"> ". sapaan($KodeSongLeader)."".$NamaSongLeader." </font></td>";
				echo "</font></tr>";
		}
		// print_r ($ArrayNamaMajelis);
		
		//$arr = array_diff($arr, array('remove_me', 'remove_me_also'));
		
			$remove = array('0','',0);
			$ArrayNamaMajelis = array_diff($ArrayNamaMajelis, $remove); 
		
		//	print_r ($ArrayNamaMajelis);
		//	echo "<br>";echo "<br>";echo "<br>";echo "<br>";
		
		$ArrayNamaMajelis=array_unique( array_diff_assoc( $ArrayNamaMajelis, array_unique( $ArrayNamaMajelis ) ) );

//if ($hasilarray!='Array ( )1'){
		if (!empty($ArrayNamaMajelis)){
		echo "<blink>Duplikasi Data:</blink>".date2Ind($tglsebulan,2)." >>";
		//print_r (array_unique( array_diff_assoc( $ArrayNamaMajelis, array_unique( $ArrayNamaMajelis ) ) ));
		//echo "<br>";	
		print_r ($ArrayNamaMajelis);
		echo "<br>";echo "<br>";
		}
	//	}
		//get_duplicates( $ArrayNamaMajelis );
		
		}
				echo "</tbody></table>";
	
				
				
				
?>
.				

	

<?php
require "Include/Footer-Short.php";
?>
