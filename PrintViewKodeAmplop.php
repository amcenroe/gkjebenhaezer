<?php
/*******************************************************************************
 *
 *  filename    : PrintViewKodeAmplop.php
 *  last change : 2003-01-29
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2003 Phillip Hullquist, Deane Barker, Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2010 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";


// Get the Kelompok ID from the querystring
$iKelompok = FilterInput($_GET["Kelompok"]);

$Judul = "Laporan - Detail Kode Persembahan Jemaat $iKelompok "; 
require "Include/Header-Report.php";

?>
 <style type='text/css'>
 a img { display:none; }
 a:hover img { display:block; }
 </style> 


			<table border="1"  width="750" cellspacing=0 cellpadding=0 >

			<tr>

			<td ALIGN=center><b>No</b></td>
			<td ALIGN=center><b>ID</b></td>
			<td ALIGN=center><b>ID.Klg</b></td>
			<td ALIGN=center><b>Kode Persembahan</b></td>
			<td ALIGN=center><b>Nama Lengkap</b></td>
			<td ALIGN=center><b>Status</b></td>
			<td ALIGN=center><b>Kelompok</b></td>
			<td ALIGN=center><b>Hub</b></td>
			<td ALIGN=center><b>Gender</b></td>
			</tr>
			<?php
//				echo $iKelompok;

				if($iKelompok == "")
				{
				$sSQL = "select per_ID as ID, per_FirstName as Nama ,
				         CASE per_cls_id
						 	WHEN '1' THEN 'Warga'
						 	WHEN '2' THEN 'Titipan'
						 	WHEN '3' THEN 'Tamu'
						 	WHEN '5' THEN 'Calon'
							WHEN '6' THEN 'Pindah'
							WHEN '7' THEN 'Meninggal'
							WHEN '8' THEN 'NonWarga'
							WHEN '9' THEN 'TdkAktif'
						END AS Status,				
				         per_WorkPhone as Kelompok, per_fam_ID as ID_Kelg,
				         CASE per_fmr_id
						 	WHEN '1' THEN 'KK'
						 	WHEN '2' THEN 'Ist'
						 	WHEN '3' THEN 'Ank'
						 	WHEN '4' THEN 'Sdr'
						END AS St_Kelg,
						CASE per_Gender
						 	WHEN '1' THEN 'L'
						 	WHEN '2' THEN 'P'
						END AS Gender
						
				         from person_per
						 WHERE  per_cls_ID < 3  
						ORDER BY TRIM(per_WorkPhone), per_fam_ID, per_fmr_ID,per_birthyear, per_FirstName";
				}else
				{
				$sSQL = "select per_ID as ID, per_FirstName as Nama ,
				         CASE per_cls_id
						 	WHEN '1' THEN 'Warga'
						 	WHEN '2' THEN 'Titipan'
						 	WHEN '3' THEN 'Tamu'
						 	WHEN '5' THEN 'Calon'
							WHEN '6' THEN 'Pindah'
							WHEN '7' THEN 'Meninggal'
							WHEN '8' THEN 'NonWarga'
							WHEN '9' THEN 'TdkAktif'
						END AS Status,					
						per_WorkPhone as Kelompok, per_fam_ID as ID_Kelg,
						CASE per_fmr_id
							WHEN '1' THEN 'KK'
							WHEN '2' THEN 'Ist'
							WHEN '3' THEN 'Ank'
							WHEN '4' THEN 'Sdr'
						END AS St_Kelg,
						CASE per_Gender
						 	WHEN '1' THEN 'L'
						 	WHEN '2' THEN 'P'
						END AS Gender
						from person_per
						WHERE per_cls_ID < 3 AND per_WorkPhone like '%" . $iKelompok . "'
						ORDER BY TRIM(per_WorkPhone), per_fam_ID, per_fmr_ID,per_birthyear, per_FirstName";
				}


//				$sSQL = "select per_ID as ID, per_FirstName as Nama ,
//				         per_WorkPhone as Kelompok, per_fam_ID as ID_Kelg, per_fmr_ID as St_Kelg
//				         from person_per
//							ORDER BY per_WorkPhone, per_fam_ID, per_fmr_ID, per_FirstName";
//				echo $sSQL;

				$perintah = mysql_query($sSQL);
				$i = 0;
								while ($hasilGD=mysql_fetch_array($perintah))
								{
								$i++;
													extract($hasilGD);
													//Alternate the row color
								                    $sRowClass = AlternateRowStyle($sRowClass);




				?>
				<tr class="<?php echo $sRowClass; ?>">

				<td><? echo $i ?></td>
				<td><a href="PersonView.php?PersonID=<?=$hasilGD[ID]?>" target=_blank ><?=$hasilGD[ID]?></a></td>
				<td> <center><?=$hasilGD[ID_Kelg]?></center></td>
				<td>
<?php

echo $hasilGD[Kelompok] ;echo " - ";

$string = $hasilGD[Nama];
$words = explode(' ', $string);
//var_dump($words);

$to_keep = array_slice($words, 0, 3);
//var_dump($to_keep);

$final_string = implode(' ', $to_keep);
//echo "1:" ;
//echo $to_keep[0];echo "<br>";
//echo $to_keep[1];echo "<br>";
//echo $to_keep[2];echo "<br>";
//echo $to_keep[3];echo "<br>";

//Nomor ID Jemaat
echo $hasilGD[ID];echo " - ";

//Kode Nama
if ($to_keep[0]=="I"){
echo $str = strtoupper(substr($to_keep[1], 0, 2));
echo $str = strtoupper(substr($to_keep[2], 0, 2));

}else{
		echo $str = strtoupper(substr($to_keep[0], 0, 2));

		if ($to_keep[1]<>""){
			echo $str = strtoupper(substr($to_keep[1], 0, 2));
			} else {
			echo strtoupper(substr($to_keep[0], -2));
			}
	}



?>
				</td>
				
				<td><a href="PersonView.php?PersonID=<?=$hasilGD[ID]?>"><?=$hasilGD[Nama]?><img src="Images/Person/<?=$hasilGD[ID]?>.jpg" height=120 /></a></td>
				
				<td><?=$hasilGD[Status]?></td>
				<td><center><?=$hasilGD[Kelompok]?></center></td>
				<td><center><?=$hasilGD[St_Kelg]?></center></td>
				<td><center><?=$hasilGD[Gender]?></center></td>

		

				</tr>
				<?}?>
			</table>
			Keterangan: <br>
			Hub : Hub Status Keluarga (KK:Kepala Keluarga, Ist:Istri/Pasangan, Ank:Anak, Sdr:Saudara)<br>
			Foto: Pas Foto Pribadi - F.Klg: Foto Keluarga<br>
			S.Nikah: SuratNikah - SB.Ank: Surat BaptisAnak - S.Sid: SuratSidhi - SB.Dws: Surat BaptisDewasa


</div>

<?php
require "Include/Footer-Short.php";
?>
