<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPersembahan.php
 *
 *  2012 Erwin Pratama for GKJ Bekasi Timur
 *  Sistem Informasi GKJ Bekasi Timur is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

// Get the person ID from the querystring
$iPersembahan_ID = FilterInput($_GET["Persembahan_ID"],'int');

// Get this Persembahan  Data

$sSQL = "SELECT * FROM Persembahangkjbekti  a
		LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
		WHERE Persembahan_ID = " . $iPersembahan_ID;

$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));



// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}



function Semester()
{
          switch ($Semester)
          {
          case 1:
            $Semester="Ganjil";
            break;
          case 2:
            $Semester="Genap";
            break;
          }
		 return $Semester;
		 echo $Semester;
}


	  	list($year , $month, $day ) = preg_split('[/.-]', $Tahun);
		//echo "Month: $month; Day: $day; Year: $year<br />\n"

		
// Set the page title and include HTML header
$sPageTitle = gettext("Persembahan $Tanggal-$NamaTI-$Pukul ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or Print Persembahan ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center>
<table border="0"  width="650" cellspacing=0 cellpadding=0>
<tr><td valign=top align=center>

<table border="0"  width="650" cellspacing=0 cellpadding=0>
  <tr><!-- Row 2 -->
     <td valign=top align=left>
     <img border="0" src="gkj_logo.jpg" width="80" >
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo "$sChurchFullName" ;?></font></b><BR>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font></b>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	  <?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font></b><br>
	 <b style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="3"><?php echo "" ;?></font></b>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <b style="font-family: Arial; color: rgb(0, 0, 102);">
	  <hr style="width: 100%; height: 2px;">
  
	 <b><font size="2">Lembar Persembahan Kebaktian Umum</font></b><br>
  <hr>
        </td><!-- Col 3 -->
  </tr>
</table>

<table border="0"  width="700" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u></u></b></font>
<table border="0" width="100%" cellspacing="0" cellpadding="4">
<tr>

<td width="100%" valign="top" align="left">


	  <?php
	  	list($year , $month, $day ) = preg_split('[/.-]', $Tahun);
		//echo "Month: $month; Day: $day; Year: $year<br />\n"
		?>


 
<tr>
    <td align="center">
			<table cellpadding="0" valign="top" >
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Data Penerimaan Persembahan"); ?></b></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip("Format: YYYY-MM-DD<br>"); ?>><?php echo gettext("Tanggal :"); ?></td>
				<td class="TinyTextColumn"><?php echo $Tanggal ?></td>
			
				<td class="TinyLabelColumn"><?php echo gettext("Pukul :"); ?></td>
				<td class="TinyTextColumn"><?php echo $Pukul ?></td>
				
			</tr> 
			<tr>
				<td class="TinyLabelColumn" <?php addToolTip(""); ?>><?php echo gettext("Tempat Ibadah:"); ?></td>
				<td class="TinyTextColumn">	<?php echo $NamaTI ; ?> </td>
				<td class="TinyLabelColumn"><?php echo gettext("Liturgi:"); ?></td>
				<td class="TinyTextColumn">
					<?php if ($Liturgi==0) {echo gettext("Liturgi Biasa"); }else{echo gettext("Liturgi Khusus"); } ?></option>
				</td>			
			</tr>	
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Pengkotbah"); ?></b></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Pengkotbah"); ?></td>
				<td colspan="4" class="TinyTextColumn"><?php echo $Pengkotbah ?></td>
			</tr>
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Bacaan Alkitab"); ?></b></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Bacaan 1"); ?></td>
				<td class="TinyTextColumn"><?php echo $Bacaan1 ?></td>

				<td class="TinyLabelColumn"><?php echo gettext("Bacaan 3"); ?></td>
				<td class="TinyTextColumn"><?php echo $Bacaan3 ?></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Bacaan 2"); ?></td>
				<td class="TinyTextColumn"><?php echo $Bacaan2 ?></td>

				<td class="TinyLabelColumn"><?php echo gettext("Bacaan 4"); ?></td>
				<td class="TinyTextColumn"><?php echo $Bacaan4 ?></td>
			</tr>
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Nas / Tema"); ?></b></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Nas / Tema"); ?></td>
				<td colspan="4" class="TinyTextColumn"><?php echo $Nas ?></td>
			</tr>
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Nyanyian"); ?></b></td>
			</tr>
			
			<tr>
				<td class="TinyTextColumn">1. <?php echo $Nyanyian1 ?></font></td>
				<td class="TinyTextColumn">5. <?php echo $Nyanyian5 ?></td>
				<td class="TinyTextColumn">9. <?php echo $Nyanyian9 ?></td>
				<td class="TinyTextColumn">13.<?php echo $Nyanyian13 ?></td>			
			</tr>
			<tr>
				<td class="TinyTextColumn">2. <?php echo $Nyanyian2 ?></td>
				<td class="TinyTextColumn">6. <?php echo $Nyanyian6 ?></td>				
				<td class="TinyTextColumn">10. <?php echo $Nyanyian10 ?></td>
				<td class="TinyTextColumn">14. <?php echo $Nyanyian14 ?></td>					
				

			</tr>
			<tr>
				<td class="TinyTextColumn">3. <?php echo $Nyanyian3 ?></td>
				<td class="TinyTextColumn">7. <?php echo $Nyanyian7 ?></td>	
				<td class="TinyTextColumn">11. <?php echo $Nyanyian11 ?></td>				


			</tr>
			<tr>
				<td class="TinyTextColumn">4. <?php echo $Nyanyian4 ?></font></td>				
				<td class="TinyTextColumn">8. <?php echo $Nyanyian8 ?></td>
				<td class="TinyTextColumn">12. <?php echo $Nyanyian12 ?></td>

			
			</tr>			
			<tr>


			</tr>
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Pelayanan Khusus"); ?></b></td>
			</tr>
			<tr>
			
				<td class="TinyLabelColumn"><?php echo gettext("Baptis Dewasa"); ?></td>
				<td class="TinyTextColumnRight"><?php echo $BaptisDewasa ?></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Baptis Anak"); ?></td>
				<td class="TinyTextColumnRight"><?php echo $BaptisAnak ?></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Sidi"); ?></td>
				<td class="TinyTextColumnRight"><?php echo $Sidi ?></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Pengakuan Dosa"); ?></td>
				<td class="TinyTextColumnRight"><?php echo $PengakuanDosa ?></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Penerimaan Warga"); ?></td>
				<td class="TinyTextColumnRight"><?php echo $PenerimaanWarga ?></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Pemberkatan Perkawinan"); ?></td>
				<td colspan="3" class="TinyTextColumn"><i><?php echo $Pemberkatan1 . "</i> dengan <i>" . $Pemberkatan2 ;?></i></td>

			</tr>				
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Persembahan"); ?></b></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Kebaktian Dewasa"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$KebDewasa,'.',',00');  ?></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Kebaktian Anak"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$KebAnak,'.',',00');  ?></td>
				
				<td>
			
				</td>
				
				<td>
				</td>
			</tr>				
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Kebaktian Anak JTMY"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$KebAnakJTMY,'.',',00');  ?></td>
				
				<td>
			
				</td>
				
				<td>
				</td>
			</tr>	
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Kebaktian Remaja"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$KebRemaja,'.',',00');  ?></td>
			
				<td>
		
				</td>
				
				<td>
				</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Syukur"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Syukur,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $SyukurAmplop ?> amplop</font></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Bulanan"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Bulanan,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $BulananAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Khusus"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Khusus,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $KhususAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Masa Raya Paskah"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Marapas,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $MarapasAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Masa Raya Pentakosta"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Marapen,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $MarapenAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Masa Raya Unduh2"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Unduh,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $UnduhAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Amplop Pink"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$Pink,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $PinkAmplop ?> amplop</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Lain-Lain"); ?></td>
				<td class="TinyTextColumnRight"><?php echo currency('Rp. ',$LainLain,'.',',00');  ?></td>
				<td class="TinyTextColumnRight"><?php echo $LainLainAmplop ?> amplop</td>
			</tr>	
			<tr>
			<td class="TinyLabelColumn"><?php echo gettext("Total Persembahan"); ?></td>
			<td class="TinyTextColumnRight" >
			<b>
			<?php $TotalPersembahan = $KebDewasa + $KebAnak + $KebAnakJTMY + $KebRemaja + $Syukur + $Bulanan + $Khusus + $Marapas +$sMarapen + $Unduh + $Pink + $LainLain;
			echo currency('Rp. ',$TotalPersembahan,'.',',00'); 
			?>
			</b>
			</td>
			</tr>
			
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Jemaat yang Hadir"); ?></b></td>
			</tr>			
			<tr>
				<td class="TinyLabelColumn"></td>
				<td class="TinyTextColumn"><b>Laki Laki : </b><?php echo $Pria ?></td>
				<td class="TinyTextColumn"><b>Wanita : </b><?php echo $Wanita ?></td>
				<td class="TinyTextColumn"><b>Total : </b><?php $TotalJemaat = $Pria + $Wanita; echo $TotalJemaat ; ?> jemaat </td>
			</tr>
	
			<tr>
				<td colspan="6" class="TinyLabelColumnHL"><b><?php echo gettext("Majelis Yang Hadir"); ?></b></td>
			</tr>
			
			<tr>
				<td class="TinyTextColumn">1. <?php echo $Majelis1 ?> </td>	
				<td class="TinyTextColumn">5. <?php echo $Majelis5 ?> </td>
				<td class="TinyTextColumn">9. <?php echo $Majelis9 ?> </td>
				<td class="TinyTextColumn">13. <?php echo $Majelis13 ?> </td>	
	
			</tr>
			<tr>
				<td class="TinyTextColumn">2. <?php echo $Majelis2 ?> </td>	
				<td class="TinyTextColumn">6. <?php echo $Majelis6 ?> </td>
				<td class="TinyTextColumn">10. <?php echo $Majelis10 ?> </td>
				<td class="TinyTextColumn">14. <?php echo $Majelis14 ?> </td>
			</tr>
			<tr>
				<td class="TinyTextColumn">3. <?php echo $Majelis3 ?> </td>	
				<td class="TinyTextColumn">7. <?php echo $Majelis7 ?> </td>			
				<td class="TinyTextColumn">11. <?php echo $Majelis11 ?> </td>					
				<td class="TinyLabelColumn"></td>	
			</tr>			
			<tr>
				<td class="TinyTextColumn">4. <?php echo $Majelis4 ?> </td>	
				<td class="TinyTextColumn">8. <?php echo $Majelis8 ?> </td>
				<td class="TinyTextColumn">12. <?php echo $Majelis12 ?> </td>				
				<td class="TinyLabelColumn"></td>				
			</tr>

	
	
	</table>
  
    </td>
	  
	  
  <?php  
 
   echo " </td>";
 echo "</tr>";
   ?>
  </table>


  </td><!-- Col 1 -->
  </tr>
 
</td></tr>
 
</table>
</td></tr>
</table>
