<?php
/*******************************************************************************
 *
 *  filename    : ScrollViewInfoPersembahan.php
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

$iTGL = FilterInput($_GET["TGL"]);

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



// Get the person ID from the querystring
$iPersembahan_ID = FilterInput($_GET["Persembahan_ID"],'int');

// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}
	  	list($year , $month, $day ) = preg_split('[/.-]', $Tahun);
		//echo "Month: $month; Day: $day; Year: $year<br />\n"

// Set the page title and include HTML header
$sPageTitle = "Informasi Kebaktian ".date2Ind($iTGL,1);
$iTableSpacerWidth = 1;


	$logvar1 = "Print";
	$logvar2 = "View or Print Informasi Persembahan Mingguan";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersembahan_ID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<!doctype html>
<head>
<meta charset="utf-8" />

<link href="resources/css/global.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

          <style type="text/css">

		


            li { border: 0px solid #000099; margin: 0; padding: 0; }
            table { border: 0px solid #ccc; display: inline-table; margin: 0; padding: 0; }
			.boldtable, .boldtable TD, .boldtable TH
			{
			font-family:arial;
			font-size:20pt;
			color:navy;
			background-color:white;
			}
			
			table{
    table-layout: fixed;
}
td{
    word-wrap:break-word;
				font-family:arial;
			font-size:12pt;
			color:navy;
			background-color:white;
}
          </style>
 
</head>
<body>

			<table border="1px" cellspacing="0" cellpadding="2" valign="top" >
			<tr>
				<td align="center" rowspan="2" ><b><?php echo gettext(" NO "); ?></b></td>
				<td align="center" rowspan="2" ><b><?php echo gettext(" URAIAN "); ?></b></td>
				<td align="center" rowspan="2" width="65" ><b><?php echo gettext(" WAKTU "); ?></b></td>
				<td align="center" rowspan="2" ><b><?php echo gettext(" JUMLAH<br>JEMAAT<br>HADIR "); ?></b></td>
				<td align="center" rowspan="2" ><b><?php echo gettext(" PELAYAN KEBAKTIAN "); ?></b></td>
				<td align="center" rowspan="2" ><b><?php echo gettext(" KANTONG "); ?></b></td>
				<td align="center" colspan="7" ><b><?php echo gettext(" PERSEMBAHAN "); ?></b></td>
			</tr> 
			<tr>
				<td align="center" ><b><?php echo gettext(" SYUKUR "); ?></b></td>
				<td align="center" ><b><?php echo gettext(" KHUSUS "); ?></b></td>
				<td align="center" ><b><?php echo gettext(" BULANAN "); ?></b></td>
				<td align="center" ><b><?php echo gettext(" PENGEMBANGAN "); ?></b></td>
				<td align="center" ><b><?php echo gettext(" ULK "); ?></b></td>
				<td align="center" ><b><?php echo gettext(" Lain-lain "); ?></b></td>
				<td align="center" ><b><?php echo gettext(" TOTAL "); ?></b></td>
			</tr> 
			</table>
</tr>	
<div id="page" >				
<ul id="ticker" style="display: table";>

<?



		
		
        $sSQL = "SELECT a.* , b.NamaTI as NamaTI   FROM Persembahangkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
			WHERE a.Tanggal='".$iTGL."' AND a.KodeTI > 0 
			GROUP BY b.KodeTI ";
$rsTI = RunQuery($sSQL);
$i=0;
 $GTPersembahanKantong=0;
 $GTPersembahanSyukur=0;
 $GTPersembahanPerjamuan=0;
 $GTPersembahanBulanan=0;
 $GTPersembahanPengembangan=0;
 $GTPersembahanULK=0;
 $GTPersembahanLainLain=0;
 $GTKehadiran=0;
 
 
 
while ($aRow = mysql_fetch_array($rsTI))
{


		
$i++;
 extract ($aRow);

 echo "<li><table><tr><td align=\left\" ><b>".integerToRoman($i)."</b></td><td colspan=\"12\" ><b> ".$NamaTI."</b></td></li></tr>";
 

    // Ibadah Dewasa 
        $sSQL = "SELECT a.* , b.NamaTI as NamaTI, c.PelayanFirman,  IF(c.PelayanFirman>0, d.NamaPendeta , c.PFnonInstitusi) as Pengkotbah  FROM Persembahangkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
			LEFT JOIN JadwalPelayanFirman c ON a.Tanggal = c.TanggalPF 
			LEFT JOIN DaftarPendeta d ON c.PelayanFirman = d.PendetaID 
			WHERE a.Tanggal='".$iTGL."' AND (a.KodeTI =".$KodeTI."  AND a.Pukul = c.WaktuPF) 
			ORDER BY a.Pukul 
			";
		$rsPerTI = RunQuery($sSQL);
		$a=0;
		//echo $sSQL;
		while ($aRow = mysql_fetch_array($rsPerTI))
		{
			$a++;
			extract ($aRow);
			// Data Persembahan Bulanan
			$sSQL = "SELECT SUM(Bulanan) as PersBulanan, SUM(Syukur) as PersSyukur, SUM(ULK) as PersULK FROM PersembahanBulanan 
					WHERE KodeTI=".$KodeTI." AND Tanggal = '".$Tanggal."' AND Pukul='".$Pukul."' ";

					$rsBulanan = RunQuery($sSQL);
					extract(mysql_fetch_array($rsBulanan));
			 
			//echo $sSQL;		
			
	// Ibadah Dewasa		
			
		$TotalJemaat = $Pria + $Wanita ;	
		 echo "<li><table><tr><td align=\"right\"  width=\"10\">";if ($a==1){echo "A.";}else{echo "__";};
		 echo "<a href=\"PersembahanView.php?Persembahan_ID=".$Persembahan_ID."\" target=\"_blank\"  STYLE=\"TEXT-DECORATION: NONE\" >".$a." </a> </td>";
		 echo "<td  width=\"50\">Dewasa</td>
		 <td align=\"center\" width=\"80\"> ".$Pukul." </td>
		 <td  align=\"center\" width=\"40\"> ".$TotalJemaat."</td>
		 <td width=\"100\"> ".$Pengkotbah."</td>
		 <td align=\"right\" width=\"150\"> ".currency(' ',$KebDewasa,'.',',00')." </td>
		 <td align=\"right\" width=\"150\" > ".currency(' ',$Syukur,'.',',00')." </td>
		 <td align=\"right\" width=\"150\" > ".currency(' ',$KhususPerjamuan+$SyukurBaptis,'.',',00')." </td>
		 <td align=\"right\" width=\"150\" > ".currency(' ',$PersBulanan,'.',',00')." </td>
		 <td align=\"right\" width=\"150\" > ".currency(' ',$PersSyukur,'.',',00')." </td>
		 <td align=\"right\" width=\"150\" > ".currency(' ',$PersULK,'.',',00')." </td>";
		 $PersLainLain = $Khusus+$Marapas+$Marapen+$Unduh+$Maranatal+$Pink+$LainLain;
		 echo " <td align=\"right\" width=\"150\" > ".currency(' ',$PersLainLain,'.',',00')." </td>";
		 
		 $TotalPersembahanDewasa=$KebDewasa+$Syukur+$KhususPerjamuan+$SyukurBaptis+$PersBulanan+$PersSyukur+$PersULK+$PersLainLain;
		 echo "<td align=\"right\" ><b> ".currency(' ',$TotalPersembahanDewasa,'.',',00')." </b></td>
		 <td></td></table></li></tr>";
		 
		$GTKehadiran= $GTKehadiran+$TotalJemaat;
		$GTPersembahanKantong= $GTPersembahanKantong+$KebDewasa;
		$GTPersembahanSyukur=$GTPersembahanSyukur+$Syukur;
		$GTPersembahanPerjamuan=$GTPersembahanPerjamuan+$KhususPerjamuan+$SyukurBaptis;
		$GTPersembahanBulanan=$GTPersembahanBulanan+$PersBulanan;
		$GTPersembahanPengembangan=$GTPersembahanPengembangan+$PersSyukur;
		$GTPersembahanULK=$GTPersembahanULK+$PersULK;
		$GTPersembahanLainLain=$GTPersembahanLainLain+$PersLainLain;
		}	
 // Ibadah Anak 
        $sSQLAnak = "SELECT a.Persembahan_ID as PersembahanAnak_ID, a.Pukul as PukulAnak, a.Pengkotbah as PengkotbahAnak,a.Pria as AnakPria, a.Wanita as AnakWanita, a.Persembahan as PersembahanAnak , b.NamaTI as NamaTI  FROM PersembahanAnakgkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
			WHERE a.Tanggal='".$iTGL."' AND a.KodeTI =".$KodeTI." 
			ORDER BY a.Pukul 
			";
		$rsPerTIAnak = RunQuery($sSQLAnak);
		$b=0;
		//echo $sSQL;
		while ($aRowAnak = mysql_fetch_array($rsPerTIAnak))
		{
		$b++;
		extract ($aRowAnak);
		$TotalJemaatAnak = $AnakPria + $AnakWanita ;	
		 echo "<li><table><tr><td align=\"right\" >";if ($b==1){echo "B.";};
		 echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=".$PersembahanAnak_ID."&Kategori=Anak\" target=\"_blank\"  STYLE=\"TEXT-DECORATION: NONE\" >".$b." </a> </td>";		 
		 echo "<td>Anak</td>
		 <td align=\"center\" > ".$PukulAnak." </td>
		 <td  align=\"center\" > ".$TotalJemaatAnak."</td>
		 <td> ".$PengkotbahAnak."</td>
		 <td align=\"right\" > ".currency(' ',$PersembahanAnak,'.',',00')." </td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		  <td align=\"right\" ></td>
		   <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ><b> ".currency(' ',$PersembahanAnak,'.',',00')." </b></td>
		 <td></td></li></tr>";
		 
		 $GTKehadiran= $GTKehadiran+$TotalJemaatAnak;
		 $GTPersembahanKantong= $GTPersembahanKantong+$PersembahanAnak;
		}
if ($KodeTI==1){		
  // Ibadah Anak JTMY
        $sSQLAnak = "SELECT a.Persembahan_ID as PersembahanAnakJTMY, a.Pukul as PukulAnak, a.Pengkotbah as PengkotbahAnak,a.Pria as AnakPria, a.Wanita as AnakWanita, a.Persembahan as PersembahanAnak , b.NamaTI as NamaTI  FROM PersembahanAnakgkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
			WHERE a.Tanggal='".$iTGL."' AND a.KodeTI =10  
			ORDER BY a.Pukul 
			";
		$rsPerTIAnak = RunQuery($sSQLAnak);
		//$b=0;
		//echo $sSQL;
		while ($aRowAnak = mysql_fetch_array($rsPerTIAnak))
		{
		$b++;
		extract ($aRowAnak);
		$TotalJemaatAnak = $AnakPria + $AnakWanita ;	
		 echo "<li><table><tr><td align=\"right\" >";if ($b==1){echo "B.";};
		 echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=".$PersembahanAnakJTMY_ID."&Kategori=Anak\" target=\"_blank\"  STYLE=\"TEXT-DECORATION: NONE\" >".$b." </a> </td>";		 
		 echo "<td>Anak JTMY</td>
		 <td align=\"center\" > ".$PukulAnak." </td>
		 <td  align=\"center\" > ".$TotalJemaatAnak."</td>
		 <td> ".$PengkotbahAnak."</td>
		 <td align=\"right\" > ".currency(' ',$PersembahanAnak,'.',',00')." </td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		  <td align=\"right\" ></td>
		   <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ><b> ".currency(' ',$PersembahanAnak,'.',',00')." </b></td>
		 <td></td></li></tr>";
		 
		 $GTKehadiran= $GTKehadiran+$TotalJemaatAnak;
		 $GTPersembahanKantong= $GTPersembahanKantong+$PersembahanAnak;
		}	
 }
 // Ibadah Remaja 
        $sSQLRemaja = "SELECT a.Persembahan_ID as PersembahanRemaja_ID, a.Pukul as PukulRemaja, a.Pengkotbah as PengkotbahRemaja,a.Pria as RemajaPria, a.Wanita as RemajaWanita, a.Persembahan as PersembahanRemaja , b.NamaTI as NamaTI  FROM PersembahanRemajagkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
			WHERE a.Tanggal='".$iTGL."' AND a.KodeTI =".$KodeTI." 
			ORDER BY a.Pukul 
			";
		$rsPerTIRemaja = RunQuery($sSQLRemaja);
		$c=0;
		//echo $sSQL;
		while ($aRowRemaja = mysql_fetch_array($rsPerTIRemaja))
		{
		$c++;
		extract ($aRowRemaja);
		$TotalJemaatRemaja = $RemajaPria + $RemajaWanita ;	
		 echo "<li><table><tr><td align=\"right\" >";if ($c==1){echo "C.";};
		 echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=".$PersembahanRemaja_ID."&Kategori=Anak\" target=\"_blank\"  STYLE=\"TEXT-DECORATION: NONE\" >".$c." </a> </td>";		 
		 echo "<td>Remaja</td>
		 <td align=\"center\" > ".$PukulRemaja." </td>
		 <td  align=\"center\" > ".$TotalJemaatRemaja."</td>
		 <td> ".$PengkotbahRemaja."</td>
		 <td align=\"right\" > ".currency(' ',$PersembahanRemaja,'.',',00')." </td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		  <td align=\"right\" ></td>
		   <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ><b> ".currency(' ',$PersembahanRemaja,'.',',00')." </b></td>
		 <td></td></li></tr>";
		
		$GTKehadiran= $GTKehadiran+$TotalJemaatRemaja;
		 $GTPersembahanKantong= $GTPersembahanKantong+$PersembahanRemaja;
		}	
	
// Ibadah PraRemaja 
        $sSQLPraRemaja = "SELECT a.Persembahan_ID as PersembahanPraRemaja_ID, a.Pukul as PukulPraRemaja, a.Pengkotbah as PengkotbahPraRemaja,a.Pria as PraRemajaPria, a.Wanita as PraRemajaWanita, a.Persembahan as PersembahanPraRemaja , b.NamaTI as NamaTI  FROM PersembahanPraRemajagkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
			WHERE a.Tanggal='".$iTGL."' AND a.KodeTI =".$KodeTI." 
			ORDER BY a.Pukul 
			";
		$rsPerTIPraRemaja = RunQuery($sSQLPraRemaja);
		$d=0;
		//echo $sSQL;
		while ($aRowPraRemaja = mysql_fetch_array($rsPerTIPraRemaja))
		{
		$d++;
		extract ($aRowPraRemaja);
		$TotalJemaatPraRemaja = $PraRemajaPria + $PraRemajaWanita ;	
		 echo "<li><table><tr><td align=\"right\" >";if ($d==1){echo "D.";};
		 echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=".$PersembahanPraRemaja_ID."&Kategori=Anak\" target=\"_blank\"  STYLE=\"TEXT-DECORATION: NONE\" >".$d." </a> </td>";		 
		 echo "<td>PraRemaja</td>
		 <td align=\"center\" > ".$PukulPraRemaja." </td>
		 <td  align=\"center\" > ".$TotalJemaatPraRemaja."</td>
		 <td> ".$PengkotbahPraRemaja."</td>
		 <td align=\"right\" > ".currency(' ',$PersembahanPraRemaja,'.',',00')." </td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		  <td align=\"right\" ></td>
		   <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ><b> ".currency(' ',$PersembahanPraRemaja,'.',',00')." </b></td>
		 <td></td></li></tr>";
		
		$GTKehadiran= $GTKehadiran+$TotalJemaatPraRemaja;
		 $GTPersembahanKantong= $GTPersembahanKantong+$PersembahanPraRemaja;
		}	
		
// Ibadah Pemuda 
        $sSQLPemuda = "SELECT a.Persembahan_ID as PersembahanPemuda_ID, a.Pukul as PukulPemuda, a.Pengkotbah as PengkotbahPemuda,a.Pria as PemudaPria, a.Wanita as PemudaWanita, a.Persembahan as PersembahanPemuda , b.NamaTI as NamaTI  FROM PersembahanPemudagkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
			WHERE a.Tanggal='".$iTGL."' AND a.KodeTI =".$KodeTI." 
			ORDER BY a.Pukul 
			";
		$rsPerTIPemuda = RunQuery($sSQLPemuda);
		$d=0;
		//echo $sSQL;
		while ($aRowPemuda = mysql_fetch_array($rsPerTIPemuda))
		{
		$d++;
		extract ($aRowPemuda);
		$TotalJemaatPemuda = $PemudaPria + $PemudaWanita ;	
		 echo "<li><table><tr><td align=\"right\" >";if ($d==1){echo "D.";};
		 echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=".$PersembahanPemuda_ID."&Kategori=Anak\" target=\"_blank\"  STYLE=\"TEXT-DECORATION: NONE\" >".$d." </a> </td>";		 
		 echo "<td>Pemuda</td>
		 <td align=\"center\" > ".$PukulPemuda." </td>
		 <td  align=\"center\" > ".$TotalJemaatPemuda."</td>
		 <td> ".$PengkotbahPemuda."</td>
		 <td align=\"right\" > ".currency(' ',$PersembahanPemuda,'.',',00')." </td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		  <td align=\"right\" ></td>
		   <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ><b> ".currency(' ',$PersembahanPemuda,'.',',00')." </b></td>
		 <td></td></li></tr>";
		
		$GTKehadiran= $GTKehadiran+$TotalJemaatPemuda;
		 $GTPersembahanKantong= $GTPersembahanKantong+$PersembahanPemuda;
		}	
 }

//Ibadah Selain Minggu

if (date2Ind($iTGL,7)=="Minggu"){
// Ibadah Khusus  
        $sSQLKhusus = "SELECT a.Nas as IbadahKhusus , a.Pukul as PukulKhusus, a.Pengkotbah as PengkotbahKhusus,a.Pria as KhususPria, a.Wanita as KhususWanita, a.Persembahan as PersembahanKhusus , b.NamaTI as NamaTIKhusus  FROM PersembahanKhususgkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
			WHERE a.Tanggal<='".$iTGL."' AND a.Tanggal>SUBDATE('".$iTGL."', INTERVAL 1 week)
			ORDER BY a.Tanggal 
			";
		$rsPerTIKhusus = RunQuery($sSQLKhusus);
		$num_rows = mysql_num_rows($rsPerTIKhusus);
		
		if ($num_rows >0) {
		$d=0;
		//echo $sSQLKhusus;
		echo "<li><table><tr><td align=\left\" ><b>".integerToRoman($i+1)."</b></td><td colspan=\"12\" ><b> Persembahan Khusus </b></td></li></tr>";
		}
		
		$TotPersembahanKhusus=0;
		while ($aRowKhusus = mysql_fetch_array($rsPerTIKhusus))
		{
		$d++;
		extract ($aRowKhusus);
		$TotalJemaatKhusus = $KhususPria + $KhususWanita ;
		$TotPersembahanKhusus=$TotPersembahanKhusus+$PersembahanKhusus;	
	
		 echo "<li><table><tr><td align=\"right\" >". $d." </td>";
		 echo "<td colspan=\"2\" >".$IbadahKhusus."</td>
		 
		 <td  align=\"center\" > ".$TotalJemaatKhusus."</td>
		 <td> ".$PengkotbahKhusus."</td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" > </td>
		 <td align=\"right\" > ".currency(' ',$PersembahanKhusus,'.',',00')." </td>
		 <td align=\"right\" ></td>
		  <td align=\"right\" ></td>
		   <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ><b> ".currency(' ',$PersembahanKhusus,'.',',00')." </b></td>
		 <td></td></li></tr>";
		
		$GTKehadiran= $GTKehadiran+$TotalJemaatKhusus;
		 $GTPersembahanKantong= $GTPersembahanKantong;
		}	

// Kontribusi Persembahan
        $sSQLKontribusi = "SELECT a.Nas as DeskripsiKontribusi , a.Pukul as PukulKontribusi, a.Pengkotbah as PengkotbahKontribusi,a.Pria as KontribusiPria, a.Wanita as KontribusiWanita, a.Persembahan as PersembahanKontribusi , b.NamaTI as NamaTIKontribusi  FROM PersembahanKontribusigkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
			WHERE a.Tanggal<='".$iTGL."' AND a.Tanggal>SUBDATE('".$iTGL."', INTERVAL 1 week)
			ORDER BY a.Tanggal 
			";
		$rsPerTIKontribusi = RunQuery($sSQLKontribusi);
		$num_rows = mysql_num_rows($rsPerTIKontribusi);
		
		if ($num_rows >0) {
		$d=0;
		//echo $sSQLKhusus;
		echo "<li><table><tr><td align=\left\" ><b>".integerToRoman($i+1)."</b></td><td colspan=\"12\" ><b> Persembahan Kontribusi </b></td></li></tr>";
		}
		//$d=0;
		//echo $sSQLKontribusi;
		//echo "<li><table><tr><td align=\left\" ><b>".integerToRoman($i+1)."</b></td><td colspan=\"12\" ><b> Persembahan Kontribusi </b></td></li></tr>";
			 
		while ($aRowKontribusi = mysql_fetch_array($rsPerTIKontribusi))
		{
		$d++;
		extract ($aRowKontribusi);
		$TotalJemaatKontribusi = $KontribusiPria + $KontribusiWanita ;
			
	
		 echo "<li><table><tr><td align=\"right\" >". $d." </td>";
		 echo "<td colspan=\"4\" >".$DeskripsiKontribusi."</td>
		 
		 <td align=\"right\" > </td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		  <td align=\"right\" ></td>
		   <td align=\"right\" ></td>
		 <td align=\"right\" > ".currency(' ',$PersembahanKontribusi,'.',',00')."  </td>
		 <td align=\"right\" ><b> ".currency(' ',$PersembahanKontribusi,'.',',00')." </b></td>
		 <td></td></li></tr>";
		
		$GTKehadiran= $GTKehadiran+$TotalJemaatKhusus;
		$GTPersembahanLainLain= $GTPersembahanLainLain+$PersembahanKontribusi;
		}
		
// Pengembalian Kas Komisi
        $sSQLPengembalian = "SELECT a.Jumlah as JumlahPengembalian , a.DeskripsiKas as DeskripsiPengembalian  FROM PengembalianKasKecil a
			WHERE a.Tanggal<='".$iTGL."' AND a.Tanggal>SUBDATE('".$iTGL."', INTERVAL 1 week)
			ORDER BY a.Tanggal 
			";
		$rsPerTIPengembalian = RunQuery($sSQLPengembalian);
		$d=0;
		$num_rows = mysql_num_rows($rsPerTIPengembalian);
		
		if ($num_rows >0) {
		//echo $sSQLPengembalian;
		echo "<li><table><tr><td align=\left\" ><b>".integerToRoman($i+1)."</b></td><td colspan=\"12\" ><b> Pengembalian Kas</b></td></li></tr>";
			 
		while ($aRowPengembalian = mysql_fetch_array($rsPerTIPengembalian))
		{
		$d++;
		extract ($aRowPengembalian);
		 echo "<li><table><tr><td align=\"right\" >". $d." </td>";
		 echo "<td colspan=\"3\" >".$DeskripsiPengembalian."</td>
		 
		 <td  align=\"right\" > </td>
		 
		 <td align=\"right\" > </td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" ></td>
		 <td align=\"right\" > ".currency(' ',$JumlahPengembalian,'.',',00')."  </td>
		 <td align=\"right\" ><b> ".currency(' ',$JumlahPengembalian,'.',',00')." </b></td>
		 <td></td></li></tr>";

		$GTPersembahanLainLain= $GTPersembahanLainLain+$JumlahPengembalian;
		}
		}
		
	}	
// Total		
		echo "<li><table><tr><td colspan=\"13\"><hr></td></li></tr>";
		 echo "<li><table><tr><td align=\"right\" ></td>";
		 echo "<td><b>Total</b></td><td>  </td><td  align=\"right\" ><b> ".$GTKehadiran."</b></td><td> </td>
		 <td align=\"right\" ><b> ".currency(' ',$GTPersembahanKantong,'.',',00')." </b></td>
		 <td align=\"right\" ><b> ".currency(' ',$GTPersembahanSyukur,'.',',00')." </b></td>
		 <td align=\"right\" ><b> ".currency(' ',($GTPersembahanPerjamuan+$TotPersembahanKhusus),'.',',00')." </b></td>
		 <td align=\"right\" ><b> ".currency(' ',$GTPersembahanBulanan,'.',',00')." </b></td>
		  <td align=\"right\" ><b> ".currency(' ',$GTPersembahanPengembangan,'.',',00')." </b></td>
		   <td align=\"right\" ><b> ".currency(' ',$GTPersembahanULK,'.',',00')." </b></td>
		 <td align=\"right\" ><b> ".currency(' ',$GTPersembahanLainLain,'.',',00')." </b></td>";
		 
		 $TotalGT=$GTPersembahanKantong+$GTPersembahanSyukur+$GTPersembahanPerjamuan+$TotPersembahanKhusus+$GTPersembahanBulanan+$GTPersembahanPengembangan+$GTPersembahanULK+$GTPersembahanLainLain;
		 echo "<td align=\"right\" ><b> ".currency(' ',$TotalGT,'.',',00')." </b></td>
		 <td></td></li></tr>";


?>

  

	  
	  
  <?php  
 echo "</ul></div>";
   ?>
   

<script>

	function tick(){
		$('#ticker li:first').slideUp( function () { $(this).appendTo($('#ticker')).slideDown(); });
	}
	setInterval(function(){ tick () }, 5000);


</script>

</body>
</html>