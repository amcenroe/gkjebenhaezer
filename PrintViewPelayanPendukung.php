<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPelayanPendukung.php
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


if ($iTGL==''){$iTGL=date( 'Y-m-d', $minggudepan);}

			

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

$Judul = "Informasi Jadwal Pelayan Peribadahan - ".date2Ind($iTGL,1); 
require "Include/Header-Report.php";

//echo "<table width=600px ellpadding=\"3\"  align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >";
//				echo "<tr>";
//				echo "<td align=\"left\" ><b>
//				jadwal sebelumnya<<</b></td>";
//				echo "<td><b> </b></td>";
//				echo "<td align=\"right\" ><b>>>jadwal berikutnya</b></td>";
//echo "</tr></table>";
?>
	<table  cellpadding=0 border=0 cellpadding=0 cellspacing=0 width=700>
	<tr>
	<td align="left">
	<?php
	$sSQL0 = "SELECT TanggalPF FROM JadwalPelayanFirman
			WHERE TanggalPF < '".$iTGL."'  ORDER BY TanggalPF Desc LIMIT 1 ";
			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL0);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
				$i++;
				extract($aRow);
				echo "<a href=\"".$filename."?TGL=".$TanggalPF."\"  ><p class=\"help\" title=\"Data Sebelumnya Tgl: ".date2Ind($TanggalPF,2)." \">";
				echo "<<  </a>";
			}
				
	?>
	</td>
	<td><i style="font-family: Arial;"></i></td>
	<td align="right">
	<?php
	$sSQL0 = "SELECT TanggalPF FROM JadwalPelayanFirman
			WHERE TanggalPF > '".$iTGL."'  ORDER BY TanggalPF Asc LIMIT 1 ";
			//echo $sSQL;
			$rsJadwal = RunQuery($sSQL0);
			$i = 0;
			//Loop through the surat recordset
			$PosSubTotalBulanan=0;
			while ($aRow = mysql_fetch_array($rsJadwal))
			{
				$i++;
				extract($aRow);
				echo "<a href=\"".$filename."?TGL=".$TanggalPF."\"  ><p class=\"help\" title=\"Data Berikutnya Tgl: ".date2Ind($TanggalPF,2)." \">";
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

function ibadahumum ($tanggal,$jam,$tempatibadah,$bahasa) {

       $sSQL = "SELECT a.*, b.*, c.*, f.*, g.*,	
	   h.per_firstname as NamaOrganis, i.per_firstname as NamaSongLeader
		FROM JadwalPelayanFirman a
				LEFT JOIN DaftarPendeta b ON a.PelayanFirman = b.PendetaID
				LEFT JOIN DaftarGerejaGKJ c ON b.GerejaID = c.GerejaID
				
			
				LEFT JOIN LokasiTI f ON a.KodeTI = f.KodeTI
				
				LEFT JOIN JadwalPelayanPendukung g ON (a.TanggalPF=g.Tanggal AND a.WaktuPF=g.Waktu AND a.KodeTI=g.KodeTI)
				LEFT JOIN person_per h ON g.KodeOrganis = h.per_ID
				LEFT JOIN person_per i ON g.KodeSongLeader = i.per_ID
				
				
				WHERE a.TanggalPF = '$tanggal' AND a.WaktuPF = '$jam' AND a.KodeTI='$tempatibadah' AND a.Bahasa='$bahasa' 
				ORDER BY a.TanggalPF DESC, a.KodeTI ASC, a.WaktuPF ASC LIMIT 1
	   ";
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
				if ((strlen($WaktuPF)==0) AND (strlen($NamaPendeta)==0)) {
				//echo strlen($NamaPengajarSMBalita1);
				//echo strlen($NamaPengajarSMBalita2);
				}else{
				$umum=1;

				$varDua = "<td> ". $NamaPendeta.$PFnonInstitusi." <i>(". $NamaGereja.$PFNIAlamat.")</i></td>";
				$varDua .= "<td> ". sapaan($KodeOrganis) . "" . shorten_string($NamaOrganis,2)."</td>";
				$varDua .= "<td> ". sapaan($KodeSongLeader) . "". shorten_string($NamaSongLeader,2)."</td></tr>";
				}

				$rowspan=$umum+$kecil+$besar+$remaja+$gabungan;
				if ($rowspan>0){
				echo "<tr><td rowspan=\"".$rowspan."\" >".$jam."</td><td rowspan=\"".$rowspan."\" >". $NamaTI."</td>";
				if ($umum>0) {echo $varDua;}
				}
?>
				

                <?php
                //Store the first letter of the family name to allow for the control break
               // $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        //echo "</table>";	
}

function kebaktiananakremaja ($tanggal,$jam,$tempatibadah) {

       $sSQL = "select a.* ,
			b.per_FirstName as NamaOrganis, b.per_WorkPhone as KelNamaOrganis, 
			c.per_FirstName as NamaSongLeader , c.per_WorkPhone as KelNamaSongLeader , 
			d.per_FirstName as NamaPengajarSMBalita1 , d.per_WorkPhone as KelNamaPengajarSMBalita1 , 
			e.per_FirstName as NamaPengajarSMBalita2 , e.per_WorkPhone as KelNamaPengajarSMBalita2 , 
			f.per_FirstName as NamaPemusikSMBalita , f.per_WorkPhone as KelNamaPemusikSMBalita , 
			g.per_FirstName as NamaPengajarSMKecil1 , g.per_WorkPhone as KelNamaPengajarSMKecil1 , 
			h.per_FirstName as NamaPengajarSMKecil2 , h.per_WorkPhone as KelNamaPengajarSMKecil2 , 
			i.per_FirstName as NamaPemusikSMKecil , i.per_WorkPhone as KelNamaPemusikSMKecil , 
			j.per_FirstName as NamaPengajarSMBesar1 , j.per_WorkPhone as KelNamaPengajarSMBesar1 , 
			k.per_FirstName as NamaPengajarSMBesar2 , k.per_WorkPhone as KelNamaPengajarSMBesar2 , 
			l.per_FirstName as NamaPemusikSMBesar , l.per_WorkPhone as KelNamaPemusikSMBesar , 
			m.per_FirstName as NamaPengajarPraRemaja1 , m.per_WorkPhone as KelNamaPengajarPraRemaja1 , 
			n.per_FirstName as NamaPengajarPraRemaja2 , n.per_WorkPhone as KelNamaPengajarPraRemaja2 , 
			o.per_FirstName as NamaPemusikPraRemaja , o.per_WorkPhone as KelNamaPemusikPraRemaja , 
			p.per_FirstName as NamaPengajarRemaja1 , p.per_WorkPhone as KelNamaPengajarRemaja1 , 
			q.per_FirstName as NamaPengajarRemaja2 , q.per_WorkPhone as KelNamaPengajarRemaja2 , 
			r.per_FirstName as NamaPemusikRemaja , r.per_WorkPhone as KelNamaPemusikRemaja , 
			s.per_FirstName as NamaPengajarGabungan1 , s.per_WorkPhone as KelNamaPengajarGabungan1 , 
			t.per_FirstName as NamaPengajarGabungan2 , t.per_WorkPhone as KelNamaPengajarGabungan2 , 
			u.per_FirstName as NamaPemusikGabungan , u.per_WorkPhone as KelNamaPemusikGabungan , 
			v.per_FirstName as NamaMultimedia1 , v.per_WorkPhone as KelNamaMultimedia1 , 
			w.per_FirstName as NamaMultimedia2 , w.per_WorkPhone as KelNamaMultimedia2 , 
			
			x.NamaTI as NamaTI, 
			y.grp_Name as KelSaranaIbadah , y.grp_Description as KelompokSaranaIbadah, 
			z.grp_Name as KelKolektan , z.grp_Description as KelompokKolektan
			
			FROM JadwalPelayanPendukung a
	   		
			LEFT JOIN person_per b ON a.KodeOrganis = b.per_ID
			LEFT JOIN person_per c ON a.KodeSongLeader = c.per_ID
			LEFT JOIN person_per d ON a.KodePengajarSMBalita1 = d.per_ID
			LEFT JOIN person_per e ON a.KodePengajarSMBalita2 = e.per_ID
			LEFT JOIN person_per f ON a.KodePemusikSMBalita = f.per_ID
			LEFT JOIN person_per g ON a.KodePengajarSMKecil1 = g.per_ID
			LEFT JOIN person_per h ON a.KodePengajarSMKecil2 = h.per_ID
			LEFT JOIN person_per i ON a.KodePemusikSMKecil = i.per_ID
			LEFT JOIN person_per j ON a.KodePengajarSMBesar1 = j.per_ID
			LEFT JOIN person_per k ON a.KodePengajarSMBesar2 = k.per_ID
			LEFT JOIN person_per l ON a.KodePemusikSMBesar = l.per_ID
			LEFT JOIN person_per m ON a.KodePengajarPraRemaja1 = m.per_ID
			LEFT JOIN person_per n ON a.KodePengajarPraRemaja2 = n.per_ID
			LEFT JOIN person_per o ON a.KodePemusikPraRemaja = o.per_ID
			LEFT JOIN person_per p ON a.KodePengajarRemaja1 = p.per_ID
			LEFT JOIN person_per q ON a.KodePengajarRemaja2 = q.per_ID
			LEFT JOIN person_per r ON a.KodePemusikRemaja = r.per_ID
			LEFT JOIN person_per s ON a.KodePengajarGabungan1 = s.per_ID
			LEFT JOIN person_per t ON a.KodePengajarGabungan2 = t.per_ID
			LEFT JOIN person_per u ON a.KodePemusikGabungan = u.per_ID			
			LEFT JOIN person_per v ON a.KodeMultimedia1 = v.per_ID
			LEFT JOIN person_per w ON a.KodeMultimedia2 = w.per_ID
			
			LEFT JOIN LokasiTI x ON a.KodeTI = x.KodeTI
			LEFT JOIN group_grp y ON a.KodeSaranaIbadah = y.grp_ID 
			LEFT JOIN group_grp z ON a.KodeKolektan = z.grp_ID 
			
			WHERE a.Tanggal='$tanggal' AND a.Waktu='$jam' AND a.KodeTI='$tempatibadah' 
			
			ORDER BY Tanggal DESC LIMIT 1
	   ";
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
                ?>


			
				<?php 
				if ((strlen($NamaPengajarSMBalita1)==0) AND (strlen($NamaPengajarSMBalita1)==0) AND (strlen($NamaPemusikSMBalita)==0)){
				//echo strlen($NamaPengajarSMBalita1);
				//echo strlen($NamaPengajarSMBalita2);
				}else{
				$balita=1;
				$varBalita = "<td>Balita</td>";
				$varBalita .= "<td> ". sapaan($KodePengajarSMBalita1)."".shorten_string($NamaPengajarSMBalita1,2).","
										. sapaan($KodePengajarSMBalita2)."". shorten_string($NamaPengajarSMBalita2,2)."</td>";
				$varBalita .= "<td> ". sapaan($KodePemusikSMBalita) ."". shorten_string($NamaPemusikSMBalita,2)."</td></tr>";
				}

				if ((strlen($NamaPengajarSMKecil1)==0) AND (strlen($NamaPengajarSMKecil1)==0) AND (strlen($NamaPemusikSMKecil)==0)){
				//echo strlen($NamaPengajarSMKecil1);
				//echo strlen($NamaPengajarSMKecil2);
				}else{
				$kecil=1;
				$varKecil = "<td>Kecil</td>";
				$varKecil .= "<td> ". sapaan($KodePengajarSMKecil1)."". shorten_string($NamaPengajarSMKecil1,2).","
									   . sapaan($KodePengajarSMKecil2)."". shorten_string($NamaPengajarSMKecil2,2)."</td>";
				$varKecil .= "<td> ". sapaan($KodePemusikSMKecil)."".shorten_string($NamaPemusikSMKecil,2)."</td></tr>";
				}
				
				if ((strlen($NamaPengajarSMBesar1)==0) AND (strlen($NamaPengajarSMBesar1)==0) AND (strlen($NamaPemusikSMBesar)==0)){
				//echo strlen($NamaPengajarSMBesar1);
				//echo strlen($NamaPengajarSMBesar2);
				}else{
				$besar=1;
				$varBesar = "<td>Besar</td>";
				$varBesar .= "<td> ". sapaan($KodePengajarSMBesar1)."". shorten_string($NamaPengajarSMBesar1,2).","
										. sapaan($KodePengajarSMBesar2)."". shorten_string($NamaPengajarSMBesar2,2)."</td>";
				$varBesar .= "<td> ". sapaan($KodePemusikSMBesar)."". shorten_string($NamaPemusikSMBesar,2)."</td></tr>";
				}

				if ((strlen($NamaPengajarRemaja1)==0) AND (strlen($NamaPengajarRemaja1)==0) AND (strlen($NamaPemusikRemaja)==0)){
				//echo strlen($NamaPengajarRemaja1);
				//echo strlen($NamaPengajarRemaja2);
				}else{
				$remaja=1;
				$varRemaja = "<td>Besar</td>";
				$varRemaja .= "<td> ". sapaan($KodePengajarRemaja1)."". shorten_string($NamaPengajarRemaja1,2).","
										. sapaan($KodePengajarRemaja2)."". shorten_string($NamaPengajarRemaja2,2)."</td>";
				$varRemaja .= "<td> ". sapaan($KodePemusikRemaja)."". shorten_string($NamaPemusikRemaja,2)."</td></tr>";
				}
				
				if ((strlen($NamaPengajarGabungan1)==0) AND (strlen($NamaPengajarGabungan1)==0) AND (strlen($NamaPemusikGabungan)==0)){
				//echo strlen($NamaPengajarGabungan1);
				//echo strlen($NamaPengajarGabungan2);
				}else{
				$gabungan=1;
				$varGabungan = "<td>Gabungan</td>";
				$varGabungan .= "<td> ". sapaan($KodePengajarGabungan1)."". shorten_string($NamaPengajarGabungan1,2).","
										  . sapaan($KodePengajarGabungan2)."". shorten_string($NamaPengajarGabungan2,2)."</td>";
				$varGabungan .= "<td> ".sapaan($KodePemusikGabungan)."". shorten_string($NamaPemusikGabungan,2)."</td></tr>";
				}
				$rowspan=$balita+$kecil+$besar+$remaja+$gabungan;
				if ($rowspan>0){
				echo "<tr><td rowspan=\"".$rowspan."\" >".$jam."</td><td rowspan=\"".$rowspan."\" >". $NamaTI."</td>";
				if ($balita>0) {echo $varBalita;}
				if ($kecil>0){echo $varKecil;}
				if ($besar>0){echo $varBesar;}
				if ($remaja>0){echo $varRemaja;}
				if ($gabungan>0){echo $varGabungan;}
				}
?>
				

                <?php
                //Store the first letter of the family name to allow for the control break
               // $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        //echo "</table>";	
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

?>

<?
//Data Awal



				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				//echo "<b>".strtoupper(date2Ind($tanggal,1))."</b>";
				echo "<table  cellpadding=\"3\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody  align=\"center\" ><tr>";
				echo "<td><b>Jam</b></td>";
				echo "<td><b>Tempat Ibadah</b></td>";
				echo "<td><b>Pemimpin Ibadah</b></td>";
				echo "<td><b>Organis</b></td>";
				echo "<td><b>Song Leader</b></td>";
				echo "</tr>";
				
						
				       $sSQL2 = "select a.* FROM JadwalPelayanFirman a
							WHERE a.TanggalPF='$tanggal' ORDER BY KodeTI ASC ,WaktuPF ASC  ";
//	echo $sSQL2;
	$rsJadwal2 = RunQuery($sSQL2);
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal2))
        {
                extract($aRow);
                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass); 
				ibadahumum ($TanggalPF,$WaktuPF,$KodeTI,$Bahasa);
		}
				echo "</tbody></table><br>";

// Jadwal Sekolah Minggu dan Remaja serta Gabungan				

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				//echo "<b>".strtoupper(date2Ind($tanggal,1))."</b>";
				echo "<table  cellpadding=\"3\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody  align=\"center\" ><tr>";
				echo "<td><b>Jam</b></td>";
				echo "<td><b>Tempat Kebaktian</b></td>";
				echo "<td><b>Kelas</b></td>";
				echo "<td><b>Pengajar</b></td>";
				echo "<td><b>Musik</b></td>";
				echo "</tr>";
				
						
				       $sSQL2 = "select a.* FROM JadwalPelayanPendukung a
							WHERE a.Tanggal='$tanggal'	ORDER BY KodeTI ASC ,Waktu ASC  ";
//	echo $sSQL2;
	$rsJadwal2 = RunQuery($sSQL2);
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal2))
        {
                extract($aRow);
                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);
				kebaktiananakremaja ($Tanggal,$Waktu,$KodeTI);
		}
				echo "</tbody></table>";

// Jadwal Majelis				

				//$tanggal='2013-01-06';
				$tanggal=$iTGL;
				//echo "<b>".strtoupper(date2Ind($tanggal,1))."</b>";
				echo "<table  cellpadding=\"3\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody  align=\"center\" ><tr>";
				echo "<td><b>Jam</b></td>";
				echo "<td><b>Tempat Ibadah</b></td>";
				echo "<td><b>Koordinator</b></td>";
				echo "<td><b>Liturgos 1</b></td>";
				echo "<td><b>Liturgos 2</b></td>";
				echo "<td><b>Lektor 1</b></td>";
				//echo "<td><b>Lektor 2</b></td>";
				echo "<td><b>Pend.SM</b></td>";
				echo "<td><b>Pend.Remaja</b></td>";
				//echo "<td><b>Pend.Pemuda</b></td>";
				echo "</tr>";
				
	echo "<tr><br></tr>";					
				       $sSQL2 = "select a.*, b.* FROM JadwalPelayanPendukung a
					   LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
							WHERE a.Tanggal='$tanggal'	ORDER BY a.KodeTI ASC ,Waktu ASC  ";
//	echo $sSQL2;
	$rsJadwal2 = RunQuery($sSQL2);
         //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsJadwal2))
        {
                extract($aRow);
                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);
				echo "<tr>";
				echo "<td>".$Waktu."</td><td>".$NamaTI."</td>";
				//echo "<td>".$Majelis1."</td><td>".$Majelis2."</td><td>".$Majelis3."</td><td>".$Majelis4."</td><td>".$Majelis5."</td><td>".$Majelis6."</td><td>".$Majelis7."</td><td>".$Majelis8."</td>   ";
				echo "<td>".$Majelis1."</td><td>".$Majelis2."</td><td>".$Majelis3."</td><td>".$Majelis4."</td><td>".$Majelis6."</td><td>".$Majelis7."</td>   ";
				echo "</tr>";
		}
				echo "</tbody></table>";
				
// Pendukung Lainnya		
				$tanggal=$iTGL;
				echo "<table cellpadding=\"3\"  align=\"center\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				echo "<tbody align=\"center\" ><tr>";
				echo "<td><b>Pelayanan</b></td>";
				echo "<td><b>Jam</b></td>";
				echo "<td><b>".strtoupper(date2Ind($tanggal,1))."</b></td>";
				echo "</tr>";
				kebaktianpendukung ($tanggal);
				kebaktianpendukungkolektan ($tanggal);
				kebaktianpendukungmultimedia ($tanggal);
				echo "</tbody></table>";		

				
				
				
				
?>
.				

	

<?php
require "Include/Footer-Short.php";
?>
