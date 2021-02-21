<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPengurusGKJBektiList.php
 *  last change : 2011-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2012 Erwin Pratama for GKJ Bekasi Timur (www.gljbekasitimur.org)
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";

// Get the Gol Darah ID from the querystring
$iStatus = FilterInput($_GET["Status"]);

$Judul = "Laporan - Struktur Majelis dan BPM $sChurchName "; 
require "Include/Header-Report.php";

function crop($str, $len) {
    if ( strlen($str) <= $len ) {
        return $str;
    }

    // find the longest possible match
    $pos = 0;
    foreach ( array('. ', '? ', '! ') as $punct ) {
        $npos = strpos($str, $punct);
        if ( $npos > $pos && $npos < $len ) {
            $pos = $npos;
        }
    }

    if ( !$pos ) {
        // substr $len-3, because the ellipsis adds 3 chars
        return substr($str, 0, $len-3) . ''; 
    }
    else {
        // $pos+1 to grab punctuation mark
        return substr($str, 0, $pos+1);
    }
	}
function jabatan($posisi,$warna) {

			$sSQL = "select a.per_ID as PersonID, a.per_FirstName AS 'Nama',vol_id, 
			fam_homephone as TelpRumah, c13 as TelpKantor, per_cellphone as Handphone, per_email as Email, vol_description as Jabatan, per_workphone as Kelompok
			from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
			where a.per_id = b.per_id AND
			a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id
			AND vol_id = " .$posisi . "
			ORDER by per_workphone, vol_id, per_firstname";
			
			
			$perintah = mysql_query($sSQL);
				$i = 0;
				$kelpk = " ";
					while ($hasilGD=mysql_fetch_array($perintah))
						{
						$i++;
						extract($hasilGD);
$iPersonID=$hasilGD[PersonID]; 

				echo "<td>" . $hasilGD[Jabatan] . "</td>" ;
				//		echo - $hasilGD[vol_id];
				
									$sSQL2 = "SELECT vol_id as jabid, vol_description as Jabatan
									from person_per , person2volunteeropp_p2vo, volunteeropportunity_vol
									where  per_id = " . $hasilGD[PersonID] . " AND per_id = p2vo_per_id AND p2vo_vol_id = vol_id limit 1
								";
						$perintah2 = mysql_query($sSQL2);
							while ($hasilGD2=mysql_fetch_array($perintah2))
							{
					//		echo $sSQL;

							extract($hasilGD2);
							$jab=$hasilGD2[jabid] ;
							
							echo "<td>";
							if ($jab==1)
								echo "Pdt. ".$hasilGD[Nama] ;
							elseif ($jab==2)
								echo "Pnt. ".$hasilGD[Nama] ;
							elseif ($jab==3)
								echo "Dkn. ".$hasilGD[Nama] ;								
							else
							echo $hasilGD[Nama] ;echo " ";	   
							}
				
				
				echo "</td><td>" . $hasilGD[Handphone] . "</td><td>" . $hasilGD[TelpRumah] . "</td>  ";


				}
				//$i = 0;
				if(0 == $i)
				{
			//	echo $posisi;
				$sSQL = "select * from volunteeropportunity_vol
							where  vol_id = " .$posisi ;
			
				$perintah = mysql_query($sSQL);


					while ($hasilGD=mysql_fetch_array($perintah))
						{

						extract($hasilGD);
				

		//		echo "<td>" . $hasilGD[vol_Name] . "" . $posisi ."</td>";echo "";

				}
				}


}

function pengurusklpk($posisi,$warna,$klpk) {

			$sSQL = "select a.per_ID as PersonID, a.per_FirstName AS 'Nama',vol_id, 
			fam_homephone as TelpRumah, c13 as TelpKantor, per_cellphone as Handphone, per_email as Email, vol_name as Jabatan, per_workphone as Kelompok
			from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
			where a.per_id = b.per_id AND
			a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id
			AND vol_id = " .$posisi . " AND per_workphone LIKE '%" . $klpk . "%' 
			ORDER by per_workphone, vol_id, per_firstname LIMIT 1";
			
			
			$perintah = mysql_query($sSQL);
				$i = 0;
				$kelpk = " ";
					while ($hasilGD=mysql_fetch_array($perintah))
						{
						$i++;
						extract($hasilGD);
$iPersonID=$hasilGD[PersonID]; 


				echo "<td> Ketua Kelompok ";
				echo "<b>";
				echo $klpk ;
				echo "</b></td><td>";

				echo $hasilGD[Nama] . "</td><td>" . $hasilGD[Handphone] . "</td> <td>" . $hasilGD[TelpRumah] . "</td> ";


				}


}


?>

		<table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 700px; height: 528px;">
			
				<tr style="text-align: left ; height: 20%; background-color: #ccffff" ;>
				   <tr><td><b>MPH - Majelis Pekerja Harian</b></td></tr>
					<tr><? jabatan(1,"ccffff"); ?></tr>
					
					<tr><? jabatan(61,"0099ff"); ?></tr>
					<tr><? jabatan(65,"ffcc99"); ?></tr>
					<tr><? jabatan(66,"ffcc99"); ?></tr>
					<tr><? jabatan(67,"ccff66"); ?></tr>
					<tr><? jabatan(69,"ff3399"); ?></tr>
					<tr><? jabatan(70,"ff3399"); ?></tr>
					<tr><? jabatan(71,"ff3399"); ?></tr>
					<tr><? jabatan(72,"ff3399") ?></tr>
					<tr><? jabatan(73,"ff3399"); ?></tr>
					<tr><? jabatan(74,"ff3399"); ?></tr>
					<tr><? jabatan(75,"ff3399"); ?></tr>
					 <tr><td><b>MPL - Majelis Pekerja Lengkap</b></td></tr>
					<tr><? jabatan(76,"ff3399"); ?></tr>
					<tr><? jabatan(77,"ffff66"); ?></tr>
					<tr><? jabatan(78,"ffff66"); ?></tr>
					<tr><? jabatan(79,"ffff66"); ?></tr>
					<tr><? jabatan(80,"ffff66"); ?></tr>
					<tr><? jabatan(81,"ffff66"); ?></tr>
					<tr><? jabatan(82,"ffff66"); ?></tr>
					<tr><? jabatan(83,"ffff66"); ?></tr>
					<tr><? jabatan(84,"ffff66"); ?></tr>
					<tr><? jabatan(85,"ffff66"); ?></tr>
					<tr><? jabatan(86,"ffff66"); ?></tr>
					<tr><? jabatan(87,"ffff66"); ?></tr>
					<tr><? jabatan(88,"ffff66"); ?></tr>
					<tr><? jabatan(89,"ffff66"); ?></tr>
					<tr><? jabatan(90,"ffff66"); ?></tr>
					<tr><? jabatan(91,"ffff66"); ?></tr>
					<tr><? jabatan(92,"ffff66"); ?></tr>	
					<tr><td><b><u>Koordinator Tempat Ibadah</u></b></td></tr>					
					<tr><? jabatan(93,"ffff66"); ?></tr>
					<tr><? jabatan(94,"ffff66"); ?></tr>
					<tr><? jabatan(95,"ffff66"); ?></tr>
					<tr><? jabatan(96,"ffff66"); ?></tr>
 					<tr><? jabatan(97,"ffff66"); ?></tr>
 					<tr><? jabatan(98,"ffff66"); ?></tr>
										
					
					
					<tr><td><b>BPM - Badan Pembantu Majelis<td></td></b></td></tr>
					<tr><td><u>Kategorial 1</u></td><td></td></tr>
					<tr><? jabatan(24,"99ff66"); ?></tr>
					<tr><? jabatan(28,"99ff66"); ?></tr>
					<tr><? jabatan(32,"99ff66"); ?></tr>
					<tr><td><u>Kategorial 2</u></td><td></td></tr>
					<tr><? jabatan(36,"99ff66"); ?></tr>
					<tr><? jabatan(40,"99ff66"); ?></tr>
					<tr><td><u>Bidang Pembinaan dan Peribadahan</u></td><td></td></tr>
					<tr><? jabatan(20,"99ff66"); ?></tr>
					<tr><? jabatan(16,"99ff66"); ?></tr>
					<tr><? jabatan(48,"99ff66"); ?></tr>
					<tr><td><u>Bidang Diakonia dan Pewartaan</u></td><td></td></tr>
					<tr><? jabatan(8,"99ff66"); ?></tr>
					<tr><? jabatan(12,"99ff66"); ?></tr>
					<tr><? jabatan(44,"99ff66"); ?></tr>
					<tr><td><u>Bidang Pengembangan</u></td><td></td></tr>
					<tr><? jabatan(52,"99ff66"); ?></tr>
					<tr><? jabatan(56,"99ff66"); ?></tr>
					
					

 					
					
					
					
					<tr><td><u>Pengurus Kelompok</u></td><td></td></tr>
					<tr><? pengurusklpk(4,"CCFF99",BTRG) ?></tr>
					<tr><? pengurusklpk(4,"CCFF99",CBTG) ?></tr>
					<tr><? pengurusklpk(4,"CCFF99",CKRG) ?></tr>
					<tr><? pengurusklpk(4,"CCFF99",JTMY) ?></tr>					
 					<tr><? pengurusklpk(4,"CCFF99",KRWG) ?></tr>
					<tr><? pengurusklpk(4,"CCFF99",MGHY) ?></tr>
					<tr><? pengurusklpk(4,"CCFF99",PRST) ?></tr>
					<tr><? pengurusklpk(4,"CCFF99",TMBN) ?></tr>	
					<tr><? pengurusklpk(4,"CCFF99",WMJY) ?></tr>
					<tr><td><br></td><td><u></u></td></tr>
					<tr><td><u>Keterangan</u></td><td></td></tr>
					<tr><td><font size="1">KOMA : Komisi Anak</font></td><td><font size="1">BTRG : Bekasi Timur Regensi </font></td></tr>
					<tr><td><font size="1">KOREM : Komisi Remaja</font></td><td><font size="1">CBTG : Cibitung </font></td></tr>
					<tr><td><font size="1">KOMPA : Komisi Pemuda</font></td><td><font size="1">CKRG : Cikarang </font></td></tr>
					<tr><td><font size="1">KWD : Komisi Warga Dewasa</font></td><td><font size="1">JTMY : Jatimulya </font></td></tr>
					<tr><td><font size="1">KAY : Komisi Adiyuswa</font></td><td><font size="1">KRWG : Karawang </font></td></tr>
					
					<tr><td><font size="1">KPWG : Komisi Pembinaan Warga Gereja</font></td><td><font size="1">MGHY : Margahayu </font></td></tr>
					<tr><td><font size="1">KPG : Komisi Peribadahan Gereja</font></td><td><font size="1">PRST : Perumnas III </font></td></tr>
					<tr><td><font size="1">KKG : Komisi Kesenian Gerejawi</font></td><td><font size="1">TMBN : Tambun </font></td></tr>
					<tr><td><font size="1">KKWG : Komisi Kesejaheraan Warga Gereja</font></td><td><font size="1">WMJY : Wismajaya </font></td></tr>
					<tr><td><font size="1">KPK : Komisi Pelayanan Kasih</font></td><td><font size="1"></font></td></tr>
					<tr><td><font size="1">KPD : Komisi Pelayanan Doa</font></td><td><font size="1"></font></td></tr>
					<tr><td><font size="1">KOLITBANG : Komisi Penelitian dan Pengembangan</font></td><td><font size="1"> </font></td></tr>
					<tr><td><font size="1">HUMAS : Komisi Hubungan Masyarakat</font></td><td><font size="1">Kabid : Kepala Bidang </font></td></font></tr>
					
				</tr>
			
		</table>



<?php
require "Include/Footer-Short.php";
?>