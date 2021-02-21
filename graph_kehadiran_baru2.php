<?php
/*******************************************************************************
 *
 *  filename    : graph_kehadiran.php
 *  description : make graphical data
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
  *  2012 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/
	include ("Include/jpgraph-3.5.0b1/src/jpgraph.php");
	include ("Include/jpgraph-3.5.0b1/src/jpgraph_line.php");
	include ("Include/jpgraph-3.5.0b1/src/jpgraph_bar.php");

//	include ("Include/jpgraph-1.13/src/jpgraph.php");
//	include ("Include/jpgraph-1.13/src/jpgraph_line.php");
//	include ("Include/jpgraph-1.13/src/jpgraph_bar.php");

DEFINE("TTF_DIR","Include/fonts/");
 
require "Include/ConfigWeb.php";

$iTahun = $_GET["Tahun"];
$iKolom = $_GET["Kolom"];
$iJenis = $_GET["Jenis"];
$iKategori = $_GET["Kategori"];

//$Jenis=="Bar";

if ($iKategori == ""){$iiKategori = "PersembahanAnakgkjbekti";}else{$iiKategori = "Persembahan".$iKategori."gkjbekti";}

switch ($iKolom) 
{
    case 11: $judul = "Kehadiran Jemaat $iKategori LakiLaki";$stat = "Pria";break;
    case 12: $judul = "Kehadiran Jemaat $iKategori Perempuan";$stat = "Wanita";break;
    case 13: $judul = "Kehadiran Jemaat $iKategori  Total";$stat = "Pria+Wanita";break;
	
	
    case 51: $judul = "Kehadiran Majelis";
			$stat = "(if(Majelis1<>'0',1,0)+if(Majelis2<>'0',1,0)+if(Majelis3<>'0',1,0)+if(Majelis4<>'0',1,0)+if(Majelis5<>'0',1,0)+if(Majelis6<>'0',1,0)+if(Majelis7<>'0',1,0)
					+if(Majelis8<>'0',1,0)+if(Majelis9<>'0',1,0)+if(Majelis10<>'0',1,0)+if(Majelis11<>'0',1,0)+if(Majelis12<>'0',1,0)+if(Majelis13<>'0',1,0)+if(Majelis14<>'0',1,0)) 
			";
			break;
			
    case 14: $judul = "Persembahan Jemaat $iKategori";$stat = "Persembahan";break;
 }

// inisialisasi array untuk jumlah pria, wanita dan negara
$dataPria = array();
$dataWanita = array();
$dataNegara = array();

$dataTanggal = array();
//$dataBulakKapal = array();
$dataCMP = array();
$dataCMS = array();
$dataCMR = array();
$dataCMK = array();
$dataPrsbBulakKapal = array();
$dataCikarang = array();
$dataTambun = array();
$dataKarawang = array();





/*
//Query untuk Bulak Kapal
$query = "
SELECT event_id, event_title, event_desc, event_start,DATE_FORMAT( event_start, '%d%b' ) AS tanggal, t3.evtcnt_countname, t3.evtcnt_countcount as hadir
FROM eventcounts_evtcnt AS t3, events_event AS t1, event_types AS t2
WHERE event_id = t3.evtcnt_eventid
AND t1.event_type = t2.type_id
AND t1.event_type =1
AND YEAR( t1.event_start ) =$iTahun
AND t1.event_desc = 'Bulak Kapal'
AND evtcnt_countid =$iKolom
ORDER BY event_start DESC
";
$hasil = mysql_query($query);
while ($data = mysql_fetch_array($hasil))
{
    // menambahkan data hasil query ke array
    array_unshift($dataTanggal, $data['tanggal']);
    array_unshift($dataBulakKapal, $data['hadir']);
}
*/

//Query untuk Cut Meutia Pagi

$sSQL="SELECT DISTINCT(Tanggal) as ListTanggal FROM $iiKategori WHERE YEAR(Tanggal)=$iTahun AND KodeTI > 0 ORDER BY Tanggal DESC";
$hasiltanggal =  mysql_query($sSQL);
while ($TanggalIbadah = mysql_fetch_array($hasiltanggal))
{
	extract($TanggalIbadah);
	$query = "
		SELECT $ListTanggal as tanggal, 
		IFNULL($stat,0) as hadir 
		FROM $iiKategori 
		WHERE Tanggal='$ListTanggal' AND Pukul = '06.00 WIB'
		AND KodeTI = 1 
		ORDER BY Tanggal DESC
			";

//	echo $query;
		$hasil = mysql_query($query);
		if (mysql_num_rows($hasil)>0) {
			while ($data = mysql_fetch_array($hasil))
				{
				extract($data);
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $tanggal);
				array_unshift($dataCMP, $hadir);
				//echo $data['hadir'];
				}
		}else
		{
		$hadir=0;
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $ListTanggal);
				array_unshift($dataCMP, $hadir);
		}
//echo "CMP:".$ListTanggal.":".$hadir." <br>";		
}

//Query untuk Cut Meutia Siang

$sSQL="SELECT DISTINCT(Tanggal) as ListTanggal FROM $iiKategori WHERE YEAR(Tanggal)=$iTahun AND KodeTI > 0 ORDER BY Tanggal DESC";
$hasiltanggal =  mysql_query($sSQL);
while ($TanggalIbadah = mysql_fetch_array($hasiltanggal))
{
	extract($TanggalIbadah);
	$query = "
		SELECT $ListTanggal as tanggal, 
		IFNULL($stat,0) as hadir 
		FROM $iiKategori 
		WHERE Tanggal='$ListTanggal' AND Pukul = '09.00 WIB'
		AND KodeTI = 1 
		ORDER BY Tanggal DESC
			";

//	echo $query;
		$hasil = mysql_query($query);
		if (mysql_num_rows($hasil)>0) {
			while ($data = mysql_fetch_array($hasil))
				{
				extract($data);
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $tanggal);
				array_unshift($dataCMS, $hadir);
				//echo $data['hadir'];
				}
		}else
		{
		$hadir=0;
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $ListTanggal);
				array_unshift($dataCMS, $hadir);
		}
//echo "CMS:".$ListTanggal.":".$hadir." <br>";		
}


//Query untuk Cut Meutia Sore

$sSQL="SELECT DISTINCT(Tanggal) as ListTanggal FROM $iiKategori WHERE YEAR(Tanggal)=$iTahun AND KodeTI > 0 ORDER BY Tanggal DESC";
$hasiltanggal =  mysql_query($sSQL);
while ($TanggalIbadah = mysql_fetch_array($hasiltanggal))
{
	extract($TanggalIbadah);
	$query = "
		SELECT $ListTanggal as tanggal, 
		IFNULL($stat,0) as hadir 
		FROM $iiKategori 
		WHERE Tanggal='$ListTanggal' AND Pukul = '17.00 WIB'
		AND KodeTI = 1 
		ORDER BY Tanggal DESC
			";

//	echo $query;
		$hasil = mysql_query($query);
		if (mysql_num_rows($hasil)>0) {
			while ($data = mysql_fetch_array($hasil))
				{
				extract($data);
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $tanggal);
				array_unshift($dataCMR, $hadir);
				//echo $data['hadir'];
				}
		}else
		{
		$hadir=0;
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $ListTanggal);
				array_unshift($dataCMR, $hadir);
		}
//echo "CMR:".$ListTanggal.":".$hadir." <br>";		
}

//Query untuk Cut Meutia Khusus

$sSQL="SELECT DISTINCT(Tanggal) as ListTanggal FROM $iiKategori WHERE YEAR(Tanggal)=$iTahun AND KodeTI > 0 ORDER BY Tanggal DESC";
$hasiltanggal =  mysql_query($sSQL);
if ($stat=='Pria+Wanita'){$stat='(SUM(Pria)+SUM(Wanita))';}
while ($TanggalIbadah = mysql_fetch_array($hasiltanggal))

{
	extract($TanggalIbadah);
	$query = "
		SELECT $ListTanggal as tanggal, 
		 IFNULL($stat,0) as hadir 
		FROM $iiKategori 
		WHERE Tanggal='$ListTanggal' AND ( Pukul != '06.00 WIB' AND Pukul != '09.00 WIB' )
		AND KodeTI = 1 
		ORDER BY Tanggal DESC LIMIT 1
			";

//	echo $query;
		$hasil = mysql_query($query);
			while ($data = mysql_fetch_array($hasil))
				{
				extract($data);
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $tanggal);
				array_unshift($dataCMK, $hadir);
				//echo $data['hadir']; 
				}

//echo "CMK:". $ListTanggal.":".$hadir." <br>";		
}

// Query Cikarang

$sSQL="SELECT DISTINCT(Tanggal) as ListTanggal FROM $iiKategori WHERE YEAR(Tanggal)=$iTahun AND KodeTI > 0 ORDER BY Tanggal DESC";
$hasiltanggal =  mysql_query($sSQL);
while ($TanggalIbadah = mysql_fetch_array($hasiltanggal))
{
	extract($TanggalIbadah);
	$query = "
		SELECT $ListTanggal as tanggal, 
		IFNULL($stat,0) as hadir 
		FROM $iiKategori 
		WHERE Tanggal='$ListTanggal' AND Pukul = '07.00 WIB'
		AND KodeTI = 3 
		ORDER BY Tanggal DESC
			";

//	echo $query;
		$hasil = mysql_query($query);
		if (mysql_num_rows($hasil)>0) {
			while ($data = mysql_fetch_array($hasil))
				{
				extract($data);
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $tanggal);
				array_unshift($dataCikarang, $hadir);
				//echo $data['hadir'];
				}
		}else
		{
		$hadir=0;
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $ListTanggal);
				array_unshift($dataCikarang, $hadir);
		}
//echo "CKR:".$ListTanggal.":".$hadir." <br>";		
}

// Query Tambun
$sSQL="SELECT DISTINCT(Tanggal) as ListTanggal FROM $iiKategori WHERE YEAR(Tanggal)=$iTahun AND KodeTI > 0 ORDER BY Tanggal DESC";
$hasiltanggal =  mysql_query($sSQL);
while ($TanggalIbadah = mysql_fetch_array($hasiltanggal))
{
	extract($TanggalIbadah);
	$query = "
		SELECT $ListTanggal as tanggal, 
		IFNULL($stat,0) as hadir 
		FROM $iiKategori 
		WHERE Tanggal='$ListTanggal' AND Pukul = '17.00 WIB'
		AND KodeTI = 2 
		ORDER BY Tanggal DESC
			";

//	echo $query;
		$hasil = mysql_query($query);
		if (mysql_num_rows($hasil)>0) {
			while ($data = mysql_fetch_array($hasil))
				{
				extract($data);
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $tanggal);
				array_unshift($dataTambun, $hadir);
				//echo $data['hadir'];
				}
		}else
		{
		$hadir=0;
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $ListTanggal);
				array_unshift($dataTambun, $hadir);
		}
//echo "TMB:".$ListTanggal.":".$hadir." <br>";		
}

// Query Karawang
$sSQL="SELECT DISTINCT(Tanggal) as ListTanggal FROM $iiKategori WHERE YEAR(Tanggal)=$iTahun AND KodeTI > 0 ORDER BY Tanggal DESC";
$hasiltanggal =  mysql_query($sSQL);
while ($TanggalIbadah = mysql_fetch_array($hasiltanggal))
{
	extract($TanggalIbadah);
	$query = "
		SELECT $ListTanggal as tanggal, 
		IFNULL($stat,0) as hadir 
		FROM $iiKategori 
		WHERE Tanggal='$ListTanggal' AND Pukul = '07.30 WIB'
		AND KodeTI = 4 
		ORDER BY Tanggal DESC
			";

//	echo $query;
		$hasil = mysql_query($query);
		if (mysql_num_rows($hasil)>0) {
			while ($data = mysql_fetch_array($hasil))
				{
				extract($data);
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $tanggal);
				array_unshift($dataKarawang, $hadir);
				//echo $data['hadir'];
				}
		}else
		{
		$hadir=0;
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  $ListTanggal);
				array_unshift($dataKarawang, $hadir);
		}
//echo "KRW:".$ListTanggal.":".$hadir." <br>";		

}

//Query untuk Tanggal 

$sSQL="SELECT DISTINCT(Tanggal) as ListTanggal FROM $iiKategori WHERE YEAR(Tanggal)=$iTahun AND KodeTI > 0 ORDER BY Tanggal DESC";
$hasiltanggal =  mysql_query($sSQL);
while ($TanggalIbadah = mysql_fetch_array($hasiltanggal))
{
				extract($TanggalIbadah);
				// menambahkan data hasil query ke array
				array_unshift($dataTanggal,  date2Ind($ListTanggal,5));

}


 
// membuat image dengan ukuran 400x200 px
$graph = new Graph(2048,400,"auto");    
$graph->SetScale("textlin");

if ($iJenis=="Bar"){

/* 
//menampilkan data Bulak Kapal
$bplot1 = new BarPlot($dataBulakKapal);
//$bplot1 = new BarPlot($dataBulakKapal);
$bplot1->SetFillColor("orange");
$bplot1->value->show();
$bplot1->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot1->value->SetAngle(45);
*/

//menampilkan data Cikarang
$bplot2 = new BarPlot($dataCikarang);
$bplot2->SetFillColor("blue");
$bplot2->value->show();
$bplot2->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot2->value->SetAngle(45);

//menampilkan data Tambun
$bplot3 = new BarPlot($dataTambun);
$bplot3->SetFillColor("red");
$bplot3->value->show();
$bplot3->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot3->value->SetAngle(45);

//menampilkan data Karawang
$bplot4 = new BarPlot($dataKarawang);
$bplot4->SetFillColor("green");
$bplot4->value->show();
$bplot4->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot4->value->SetAngle(45);

//menampilkan data Cut Meutia Pagi
$bplot5 = new BarPlot($dataCMP);
//$bplot5 = new BarPlot($dataCMP);
$bplot5->SetFillColor("cyan");
$bplot5->value->show();
$bplot5->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot5->value->SetAngle(45);

//menampilkan data Cut Meutia Siang
$bplot6 = new BarPlot($dataCMS);
//$bplot6 = new BarPlot($dataCMS);
$bplot6->SetFillColor("blue");
$bplot6->value->show();
$bplot6->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot6->value->SetAngle(45);

//menampilkan data Cut Meutia Sore
$bplot7 = new BarPlot($dataCMR);
//$bplot7 = new BarPlot($dataCMR);
$bplot7->SetFillColor("blue");
$bplot7->value->show();
$bplot7->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot7->value->SetAngle(45);

//menampilkan data Cut Meutia Khusus
$bplot8 = new BarPlot($dataCMK);
//$bplot8 = new BarPlot($dataCMK);
$bplot8->SetFillColor("wheat");
$bplot8->value->show();
$bplot8->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot8->value->SetAngle(45);

// mengelompokkan grafik batang berdasarkan tanggal
$gbplot = new GroupBarPlot(array($bplot1,$bplot2,$bplot3,$bplot4,$bplot5,$bplot6,$bplot7,$bplot8));

//$ab1plot = new AccBarPlot(array($bplot2,$bplot3,$bplot4,$bplot5,$bplot6));
//$gbplot1 = new GroupBarPlot(array($bplot2,$bplot3,$bplot4,$bplot5,$bplot6));


// Create the grouped bar plot
//$gbplot = new GroupBarPlot(array($ab1plot,$gbplot1));


$gbplot = new GroupBarPlot(array($bplot2,$bplot3,$bplot4,$bplot5,$bplot6,$bplot7,$bplot8));


$graph->Add($gbplot);
 
 }else{
 
 // Create the linear plot
$bplot5 =new LinePlot($dataCMP);
$bplot5 ->SetColor("cyan");
$bplot5->SetFillColor("turquoise@0.6");
$bplot5->SetWeight(2);
$bplot5->mark->SetType(MARK_IMG_MBALL,'yellow');
$bplot5->value->show();
$bplot5->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot5->value->SetAngle(45);
$graph->Add( $bplot5); 

 // Create the linear plot
$bplot6 =new LinePlot($dataCMS);
$bplot6 ->SetColor("blue");
$bplot6->SetFillColor("yellow@0.6");
$bplot6->SetWeight(2);
$bplot6->mark->SetType(MARK_IMG_MBALL,'lightblue');
$bplot6->value->show();
$bplot6->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot6->value->SetAngle(45);
$graph->Add( $bplot6);  

 // Create the linear plot
$bplot7 =new LinePlot($dataCMR);
$bplot7 ->SetColor("blue");
$bplot7->SetFillColor("orange@0.6");
$bplot7->SetWeight(2);
$bplot7->mark->SetType(MARK_IMG_MBALL,'purple');
$bplot7->value->show();
$bplot7->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot7->value->SetAngle(45);
$graph->Add( $bplot7);  

 // Create the linear plot
$bplot8 =new LinePlot($dataCMK);
$bplot8 ->SetColor("blue");
$bplot8->SetFillColor("yellow@0.6");
$bplot8->SetWeight(2);
$bplot8->mark->SetType(MARK_IMG_MBALL,'brown');
$bplot8->value->show();
$bplot8->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot8->value->SetAngle(45);
$graph->Add( $bplot8);  


 
/* 
 // Create the linear plot
$bplot1 =new LinePlot($dataBulakKapal);
$bplot1 ->SetColor("orange");
$bplot1->SetFillColor("yellow@0.6");
$bplot1->SetWeight(2);
$bplot1->mark->SetType(MARK_IMG_MBALL,'yellow');
$bplot1->value->show();
$bplot1->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot1->value->SetAngle(45);
$graph->Add( $bplot1); 
*/
$bplot2 =new LinePlot($dataCikarang);
$bplot2 ->SetColor("blue");
$bplot2->SetFillColor("#00ffff@0.6");
$bplot2->SetWeight(2);
$bplot2->mark->SetType(MARK_IMG_MBALL,'blue');
$bplot2->value->show();
$bplot2->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot2->value->SetAngle(45);
$graph->Add( $bplot2); 
 
$bplot3 =new LinePlot($dataTambun);
$bplot3 ->SetColor("red");
$bplot3->SetFillColor("#F5A9D0@0.6");
$bplot3->SetWeight(2);
$bplot3->mark->SetType(MARK_IMG_MBALL,'red');
$bplot3->value->show();
$bplot3->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot3->value->SetAngle(45);
$graph->Add( $bplot3); 
 
$bplot4 =new LinePlot($dataKarawang);
$bplot4 ->SetColor("green");
$bplot4->SetFillColor("#81F781@0.6");
$bplot4->SetWeight(2);
$bplot4->mark->SetType(MARK_IMG_MBALL,'green');
$bplot4->value->show();
$bplot4->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot4->value->SetAngle(45);
$graph->Add( $bplot4); 


 
 }
 

// membuat legend untuk masing2 TI
// $bplot1->SetLegend("Bulak Kapal");
$bplot2->SetLegend("Cikarang");
$bplot3->SetLegend("Tambun");
$bplot4->SetLegend("Karawang");
$bplot5->SetLegend("Cut Meutia Pagi");
$bplot6->SetLegend("Cut Meutia Siang");
$bplot7->SetLegend("Cut Meutia Sore");
$bplot8->SetLegend("Kebaktian Khusus");
$graph->legend->Pos(0.005,0.005,"right","top");

// mengatur margin image
$graph->img->SetMargin(80,110,20,40);
 
// menampilkan title grafik dan nama masing-masing sumbu
$graph->title->Set("Grafik $judul - Tahun $iTahun");
// format font title grafik
$graph->title->SetFont(FF_ARIAL,FS_BOLD,12);

$graph->xaxis->title->Set("Tanggal");
$graph->yaxis->title->Set("Jumlah");
 
// menampilkan Tanggalke sumbu x
$graph->xaxis->SetTickLabels($dataTanggal);
$graph->xaxis->SetLabelAngle(45);
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
 
// menampilkan efek shadow pada image
$graph->SetShadow();
 
// menampilkan image ke browser
$graph->Stroke();
?>