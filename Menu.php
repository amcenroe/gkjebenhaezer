<?php
/*******************************************************************************
*
*  filename    : Menu.php
*  description : menu that appears after login, shows login attempts
*
*  http://www.churchdb.org/
*  Copyright 2001-2002 Phillip Hullquist, Deane Barker, Michael Wilt
*
*  Additional Contributors:
*  2006 Ed Davis
*  2008 Erwin Pratama for GKJ Bekasi WIl Timur ( http://www.gkjbekasi-wiltimur.net )
*  2009 Erwin Pratama for GKJ Bekasi Timur ( http://www.gkjbekasitimur.org )
*  2010 Erwin Pratama for GKPB Bali ( http://www.balichurchsynod.org/ )
*  2013 Erwin Pratama for GKJ Tanjung Priok ( http://www.gkjtp.com )
*
*  Copyright Contributors
*  ChurchInfo is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  This file best viewed in a text editor with tabs stops set to 4 characters
*
******************************************************************************/

// Include the function library
require 'Include/Config.php';
require 'Include/Functions.php';
$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;
$date = getdate();

// Set the page title
$sPageTitle = gettext('Selamat Datang di Sistem Informasi '.$sChurchName);

require 'Include/Header2.php';


echo '<p>'.gettext('Syallom ').' '.$_SESSION['UserFirstName'].'</p>';

if ($_SESSION['iLoginCount'] == 0) {

    echo '<p>'.gettext('Ini adalah login Anda untuk yang pertama kalinya ').'.</p>';

} else {

    $dLL = $_SESSION['dLastLogin'];
    $sSQL = "SELECT DAYNAME('$dLL') as dn, MONTHNAME('$dLL') as mn, DAYOFMONTH('$dLL') as dm, "
    .       "YEAR('$dLL') as y, HOUR('$dLL') as h, DATE_FORMAT('$dLL', ':%i') as m";
	
	//echo $sSQL;
    extract(mysql_fetch_array(RunQuery($sSQL)));
	
//	echo $_SERVER['REQUEST_TIME']; 
    echo '<p>'.gettext('Selamat Datang kembali ').'.';

    echo ' '.gettext('Login terakhir anda pada ').' '.gettext("$dn").', '.gettext("$mn")
    .   " $dm, $y " . gettext('jam') . " $h$m.</p>\n";

    echo '<p>'.gettext('Ada ').' '.$_SESSION['iFailedLogins'].' '
    .   gettext('kali percobaan login yang gagal sejak login terakhir').'.</p>';
    echo '<hr>';
    echo '<br>';
	

				$sSQL = "SELECT count(MasaBaktiMajelisID) as JumlahData FROM `MasaBaktiMajelis` WHERE ( DATE(TglAkhir) > DATE(NOW()) ) AND ( YEAR(TglAkhir) = 2014 )";
				$perintah = mysql_query($sSQL);
				while ($hasilGD=mysql_fetch_array($perintah)){
				
				if ($hasilGD[JumlahData]>0){ 
				echo "<blink><font size=\"2\" color=\"red\"> 
							
				PERHATIAN!!! 
				<a href=\"SelectListApp3.php?Sort=TglAkhir&Filter=&mode=MasaBaktiMajelis&amp;GID=".randint()."\" >
				Ada <b>" . $hasilGD[JumlahData] ." Majelis yang akan berakhir Masa Pelayanannya di Tahun ini </b> 
				</a></font></b></blink>";
						}
					}
		echo '<br>';	
	
				$sSQL = "	SELECT count(Persembahan_ID) as JumlahData
					FROM Persembahangkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
			LEFT JOIN JadwalPelayanFirman c ON a.Tanggal = c.TanggalPF 
			LEFT JOIN DaftarPendeta d ON c.PelayanFirman = d.PendetaID 
			WHERE KetPersembahan = 0 AND ( a.KodeTI = c.KodeTI AND a.Pukul = c.WaktuPF) 	AND 
				(a.KebDewasa+a.KebAnak+a.KebAnakJTMY+a.KebRemaja+a.Syukur+a.SyukurBaptis+a.Bulanan+a.Khusus+a.KhususPerjamuan+a.Marapas+ 
				a.Marapen+a.Maranatal+a.Unduh+a.Pink+a.LainLain) = 0 
				AND YEAR(a.TAnggal) = YEAR(CURDATE()) AND WEEKOFYEAR(Tanggal) < WEEKOFYEAR(NOW())
				ORDER BY Tanggal DESC ";
				$perintah = mysql_query($sSQL);
				while ($hasilGD=mysql_fetch_array($perintah)){
				
				if ($hasilGD[JumlahData]>0){ 
				echo "<blink><font size=\"2\" color=\"red\"> 
							
				PERHATIAN!!! 
				<a href=\"SelectList.php?mode=Persembahan&amp;GID=".randint()."\" >
				Ada <b>" . $hasilGD[JumlahData] ." DATA PERSEMBAHAN </b>yang belum diinput 
				</a></font></b></blink>";
						}
					}
		echo '<br>';		
				$sSQL = "SELECT count(LiturgiID) as JumlahData, Tanggal, WEEKOFYEAR(NOW()),WEEKOFYEAR(Tanggal) From LiturgiGKJBekti
				WHERE 
				( AyatPenuntunHK = '' AND Nyanyian1 = '' )  AND 
				( Tanggal >= DATE(NOW()) AND WEEKOFYEAR(Tanggal) >= WEEKOFYEAR(NOW()) )
				ORDER BY Tanggal DESC ";
				$perintah = mysql_query($sSQL);
				while ($hasilGD=mysql_fetch_array($perintah)){
				
				if ($hasilGD[JumlahData]>0){ 
				echo "<blink><font size=\"2\" color=\"red\"> 
								
				PERHATIAN!!! 
				<a href=\"SelectListApp.php?mode=Liturgi&amp;GID=".randint()."\" >
				Ada <b>" . $hasilGD[JumlahData] ." DATA LITURGI  </b>yang belum lengkap diinput
				</a></font></b></blink>";
					}
				}
		echo '<br>';
				$sSQL = "SELECT count(PelayanFirmanID) as JumlahData, TanggalPF, WEEKOFYEAR(NOW()),WEEKOFYEAR(TanggalPF), WEEKOFYEAR(NOW())+2 as MingguBerikut From JadwalPelayanFirman
				WHERE (WEEKOFYEAR(TanggalPF)= WEEKOFYEAR(NOW())+2) AND 
				( TanggalPF >= DATE(NOW()) AND WEEKOFYEAR(TanggalPF) >= WEEKOFYEAR(NOW()) ) ";
				$perintah = mysql_query($sSQL);
				while ($hasilGD=mysql_fetch_array($perintah)){
				
				//echo $hasilGD[JumlahData];
				if ($hasilGD[JumlahData]<1){ 
				echo "<blink><font size=\"2\" color=\"red\"> 
								
				PERHATIAN!!! 
				<a href=\"SelectListApp.php?mode=Liturgi&amp;GID=".randint()."\" >
				Untuk <b>Minggu ke-" . $hasilGD[MingguBerikut] ." </b>belum dibuat <b>JADWAL PELAYAN FIRMAN</b> 
				</a></font></blink>";
					}
				}
		echo '<br>';
				$sSQL = "SELECT count(PelayanFirmanID) as JumlahData, TanggalPF, WEEKOFYEAR(NOW()),WEEKOFYEAR(TanggalPF), WEEKOFYEAR(NOW())+1 as MingguBerikut From JadwalPelayanFirman
				WHERE (WEEKOFYEAR(TanggalPF)= WEEKOFYEAR(NOW())+1) AND 
				( TanggalPF >= DATE(NOW()) AND WEEKOFYEAR(TanggalPF) >= WEEKOFYEAR(NOW()) ) ";
				$perintah = mysql_query($sSQL);
				while ($hasilGD=mysql_fetch_array($perintah)){
				
				//echo $hasilGD[JumlahData];
				if ($hasilGD[JumlahData]<1){ 
				echo "<blink><font size=\"2\" color=\"red\"> 
								
				PERHATIAN!!! 
				<a href=\"SelectListApp.php?mode=Liturgi&amp;GID=".randint()."\" >
				Untuk <b>Minggu DEPAN</b> belum dibuat <b>JADWAL PELAYAN FIRMAN</b>  <br>
				</a></font></blink>";
					}
				}				

				$sSQL = "SELECT count(per_ID) as JumlahData
				FROM person_per
				WHERE per_WorkPhone is null or per_workphone =' '";
				$perintah = mysql_query($sSQL);
				while ($hasilGD=mysql_fetch_array($perintah)){
				
				//echo $hasilGD[JumlahData];
				if ($hasilGD[JumlahData]>1){ 
				echo "<blink><font size=\"2\" color=\"red\"> 
				PERHATIAN!!! 
				<a href=\"QueryView.php?QueryID=59\" >
				Ada <b>" . $hasilGD[JumlahData] ." Warga</b> yang <b>TIDAK</b> terdaftar dalam kelompok  <br>
				</a></font></blink>";
					}
				}	
	
	
				$sSQL = "SELECT count(per_ID) as JumlahData
				 FROM `person_per`where ( per_cls_ID = 1 OR per_cls_ID = 2 ) AND (per_BirthDay = 00 or per_BirthMonth = 00 or per_BirthYear = 0000)";
				$perintah = mysql_query($sSQL);
				while ($hasilGD=mysql_fetch_array($perintah)){
				
				//echo $hasilGD[JumlahData];
				if ($hasilGD[JumlahData]>1){ 
				echo "<blink><font size=\"2\" color=\"red\"> 
				PERHATIAN!!! 
				<a href=\"QueryView.php?QueryID=57\" >
				Ada <b>" . $hasilGD[JumlahData] ." Warga</b> yang <b>TIDAK</b> ada Data Tanggal lahir  <br>
				</a></font></blink>";
					}
				}	
				
				$sSQL = "select count(per_ID) as JumlahData
				from person_per where per_fmr_id = 0 or per_fmr_id is NULL	";
				$perintah = mysql_query($sSQL);
				while ($hasilGD=mysql_fetch_array($perintah)){
				
				//echo $hasilGD[JumlahData];
				if ($hasilGD[JumlahData]>1){ 
				echo "<blink><font size=\"2\" color=\"red\"> 
				PERHATIAN!!! 
				<a href=\"QueryView.php?QueryID=62\" >
				Ada <b>" . $hasilGD[JumlahData] ." Warga</b> yang <b>TIDAK</b> jelas status/hubungan dalam keluarga  <br>
				</a></font></blink>";
					}
				}	
	echo "<br>";
	
	$today = date("Ymd");  
//	echo '<br>'.$today;
	echo '<br>Hari ini :' .date2Ind($today,1);
	$weekNum = date("W") - date("W",strtotime(date("Y-m-01"))) + 1;
//	echo '<br>Minggu ke:'.$weekNum ;
	
	
    echo '<br><b>Akses Cepat Laporan:</b>';
    echo '<br>';
    echo "|<a href=\"LapBulanan.php?Tahun=".$y."&amp;GID=".randint()."\" target=\"blank\" > Executive Summary </a>";
	//echo "|<a href=\"LapBulanan_hadir.php?Tahun=".$y."\" target=\"blank\" > Kehadiran dan Persembahan </a>";
	echo "|<a href=\"LapBulanan_kehadiran.php?Tahun=".$y."&amp;GID=".randint()."\" target=\"blank\" > Kehadiran & Persembahan (Grafik)</a>";
	echo "|<a href=\"PrintViewLapPersembahan2.php?&amp;GID=".randint()."\" target=\"blank\" > Kehadiran & Persembahan (Tabulasi)</a>";
	echo "|<a href=\"LapBulanan_att.php?Tahun=".$y."&amp;GID=".randint()."\" target=\"blank\" > Mutasi Jemaat </a>";
    echo "|<a href=\"PrintViewProgresDB.php?&amp;GID=".randint()."\" target=\"blank\" > Progress Update Data </a>";
    echo "|<a href=\"PrintViewDocStatus.php?&amp;GID=".randint()."\" target=\"blank\" > Kelengkapan Dokumen </a>";
    echo "|<a href=\"PrintViewUltah.php?Bulan=" ?><?=$date['mon'] ?><? echo "&amp;GID=".randint()." \" target=\"blank\" > Ultah Jemaat Bulan ini </a> |";
	echo '<br>';
	echo "|<a href=\"PrintViewMajelis.php?&amp;GID=".randint()."\" target=\"blank\" > Daftar Majelis </a>";
	echo "|<a href=\"PrintViewBPM.php?&amp;GID=".randint()."\" target=\"blank\" > Daftar Badan Pembantu Majelis </a>";
	echo "|<a href=\"PrintViewPKelompok.php?&amp;GID=".randint()."\" target=\"blank\" > Daftar Pengurus Kelompok </a>";
	echo "|<a href=\"PrintViewPengurusGKJBekti.php?&amp;GID=".randint()."\" target=\"blank\" > Struktur Organisasi ".$sChurchName." </a>";
	echo '<br>';
	echo "|<a href=\"PrintViewKelompokKalender.php?&amp;GID=".randint()."\" target=\"blank\" > Daftar Keluarga per Kelompok (status Warga dan Titipan) </a>";
	echo '<br>';
	echo '<br><b>Laporan Keuangan:</b>';
	echo '<br>';
	
	echo "|<a href=\"PrintViewInfoPersembahan.php?&amp;GID=".randint()."\" target=\"blank\" > Rekapitulasi Persembahan Mingguan </a>";
	echo "|<a href=\"PrintViewPersembahanBulanan.php?&amp;GID=".randint()."\" target=\"blank\" > Rekapitulasi Persembahan Amplop Bulanan </a>";
	echo "|<a href=\"PrintViewPengeluaranKasKecil.php?BIDID=2&amp;GID=".randint()."\" target=\"blank\" > Rekapitulasi Kas Kecil </a>";
	echo '<br>';
	
	echo "|<a href=\"PrintViewLapPersembahan.php?&amp;GID=".randint()."\" target=\"blank\" > Laporan Rincian Persembahan vs Pengeluaran </a>";
	echo "|<a href=\"PrintViewPengeluaranPerBidang.php?&amp;GID=".randint()."\" target=\"blank\" > Monitoring Status Anggaran </a>";
	echo "|<a href=\"PrintViewLapBulananBendahara.php?&amp;GID=".randint()."\" target=\"blank\" > Laporan Bulanan Bendahara </a><blink>(Dalam Pengembangan)</blink> ";
	echo "|<a href=\"PrintViewInfoKeuangan.php?&amp;GID=".randint()."\" target=\"blank\" > Informasi Keuangan utk Warta </a><blink>(Dalam Pengembangan)</blink> ";
	echo '<br>';	
	echo "|<a href=\"PrintViewPersembahanPPPG.php?&amp;GID=".randint()."\" target=\"blank\" > Rekapitulasi dan Detail Persembahan PPPG </a>";	
	echo "|<a href=\"PrintViewLapPPPG.php?&amp;GID=".randint()."\" target=\"blank\" > Laporan dan Rincian Persembahan PPPG </a>";	
	echo "|<a href=\"PrintViewLapPPPGRekap.php?Rekap=YES&amp;GID=".randint()."\" target=\"blank\" > Laporan Rekap Persembahan Pengeluaran PPPG (utk Warta)</a>";	

	echo '<br>';


?>
			<table border="0"  width="400" cellspacing=0 cellpadding=0 >
			<u><b>User Yang Login Terakhir :</b></u><br>
			<tr>
			<td> </td><td> </td><td>Nama Lengkap</td><td>Login Terakhir</td><td>Total Login</td></tr>
			<?php
				$sSQL = "select per_FirstName as nama, usr_LastLogin as lastlogin, usr_LoginCount as logincount
				from person_per, user_usr
				where per_id = usr_per_ID
				order by usr_LastLogin desc";
				$perintah = mysql_query($sSQL);
				while ($hasilGD=mysql_fetch_array($perintah)){
				?>
				<tr>
				<td> </td><td>*</td><td><?=$hasilGD[nama]?></td><td><?=$hasilGD[lastlogin]?></td><td align=right ><?=$hasilGD[logincount]?></td>
				</tr>
				<?}?>
			</table>


<?php







    echo '<br>';
    echo 'Cetak Formulir Ke-Jemaatan';
    echo '<br>';
    echo "<a href=\"form/formulir_daftar1.pdf\" target=\"blank\" >* Formulir Daftar  . </a>";
	echo '<br>';
    echo '<br>';
    echo 'Slide TV Scroller (Resolusi 1024x768piksel) dan (Resolusi 1366x768piksel)';
    echo '<br>';
    echo '<br>';
    echo "<a href=\"http://www.gkjbekasitimur.org/datawarga/tvinfo.php?&amp;GID=".randint()."\" ><IMG SRC=\"Images/tvinfo.jpg\" ALT=\"tvinfo\" > v1 </a>";
	echo "<a href=\"http://www.gkjbekasitimur.org/datawarga/tvinfo2.php?&amp;GID=".randint()."\" ><IMG SRC=\"Images/tvinfo.jpg\" ALT=\"tvinfo\" > v2 </a>";
	echo '<br>';	
    echo '<br>';
    echo 'Slide Laporan Keuangan';
    echo '<br>';
    echo "<a href=\"http://www.gkjbekasitimur.org/datawarga/slide_infokeuangan.html?&amp;GID=".randint()."\" ><IMG SRC=\"Images/persembahan.jpg\" ALT=\"KIOSK\" ></a>";
	echo '<br>';
    echo '<br>';
    echo 'Anjungan Informasi Mandiri';
    echo '<br>';
    echo "<a href=\"http://www.gkjbekasitimur.org/datawarga/pencarian.php?&amp;GID=".randint()."\" ><IMG SRC=\"Images/kiosk.jpg\" ALT=\"KIOSK\" ></a>";
}


require 'Include/Footer.php';
?>
