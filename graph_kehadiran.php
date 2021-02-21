<?php
/*******************************************************************************
 *
 *  filename    : graph.php
 *  description : make graphical data
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *
 ******************************************************************************/


	include ("Include/jpgraph-1.13/src/jpgraph.php");
	include ("Include/jpgraph-1.13/src/jpgraph_line.php");
	include ("Include/jpgraph-1.13/src/jpgraph_bar.php");

require "Include/ConfigWeb.php";

$iTahun = $_GET["Tahun"];
$iKolom = $_GET["Kolom"];
$iJenis = $_GET["Jenis"];

//$Jenis=="Bar";

// Kolom data
// Kolom 1 	 	LakiLaki  	 
// Kolom 2 	 	Perempuan 	 
// Kolom 3 	 	Dewasa Total 	 
// Kolom 4 	 	Pemuda 	 
// Kolom 5 	 	Remaja 	 
// Kolom 6 	 	Anak 	 
// Kolom 7 	 	Baptis Dewasa 	 
// Kolom 8 	 	Baptis Anak 	 
// Kolom 9 	 	SIDI 	 
// Kolom 10 	Pengakuan Dosa 	 
// Kolom 11 	Penerimaan Warga 	 
// Kolom 12 	Majelis 	 
// Kolom 13 	Kb.Anak JTMY 	 
// Kolom 14 	Prsb Dewasa 	 
// Kolom 15 	Prsb Pemuda 	 
// Kolom 16 	Prsb Remaja 	 
// Kolom 17 	Prsb Anak 	 
// Kolom 18 	Prsb Syukur 	 
// Kolom 19 	Prsb Bulanan 	 
// Kolom 20 	Prsb Khusus 	 
// Kolom 21 	Prsb Perpuluhan 	 
// Kolom 22 	Prsb PPPG 	 
// Kolom 23 	Prsb Bencana 	 
// Kolom 24 	Prsb LainLain 	 
// Kolom 25 	Anak JTMY 	 
// Kolom 26 	Amplop Syukur 	 
// Kolom 27 	Amplop Bulanan 	 
// Kolom 28 	Amplop Perpuluhan 	 
// Kolom 29 	Amplop PPPG 	 
// Kolom 30 	Amplop Bencana 	 
// Kolom 31 	Amplop LainLain

switch ($iKolom) 
{
    case 1: $judul = "Kehadiran Jemaat LakiLaki";break;
    case 2: $judul = "Kehadiran Jemaat Perempuan";break;
    case 3: $judul = "Kehadiran Jemaat Dewasa Total";break;
    case 4: $judul = "Kehadiran Jemaat Pemuda";break;
    case 5: $judul = "Kehadiran Jemaat Remaja";break;
    case 6: $judul = "Kehadiran Jemaat Anak";break;
    case 7: $judul = "Kehadiran Jemaat Baptis Dewasa";break;
    case 8: $judul = "Kehadiran Jemaat Baptis Anak";break;
    case 9: $judul = "Kehadiran Jemaat SIDI";break;
    case 10: $judul = "Kehadiran Jemaat Pengakuan Dosa";break;
    case 11: $judul = "Kehadiran Jemaat Penerimaan Warga";break;
    case 12: $judul = "Kehadiran Majelis";break;
    case 13: $judul = "Kehadiran Jemaat Kb.Anak JTMY";break;
    case 14: $judul = "Persembahan Jemaat Dewasa";break;
    case 15: $judul = "Persembahan Jemaat Pemuda";break;
    case 16: $judul = "Persembahan Jemaat Remaja";break;
    case 17: $judul = "Persembahan Jemaat Anak";break;
    case 18: $judul = "Persembahan Jemaat Syukur";break;
    case 19: $judul = "Persembahan Jemaat Bulanan";break;
    case 20: $judul = "Persembahan Jemaat Khusus";break;
    case 21: $judul = "Persembahan Jemaat Perpuluhan";break;
    case 22: $judul = "Persembahan Jemaat PPPG";break;
    case 23: $judul = "Persembahan Jemaat Bencana";break;
    case 24: $judul = "Persembahan Jemaat LainLain";break;
    case 25: $judul = "Persembahan Jemaat Anak JTMY";break;
    case 26: $judul = "Jumlah  Amplop Syukur";break;
    case 27: $judul = "Jumlah  Amplop Bulanan";break;
    case 28: $judul = "Jumlah  Amplop Perpuluhan";break;
    case 29: $judul = "Jumlah  Amplop PPPG";break;
    case 30: $judul = "Jumlah  Amplop Bencana";break;
    case 31: $judul = "Jumlah  Amplop LainLain";break;
 }

// inisialisasi array untuk jumlah pria, wanita dan negara
$dataPria = array();
$dataWanita = array();
$dataNegara = array();

$dataTanggal = array();
//$dataBulakKapal = array();
$dataCMP = array();
$dataCMS = array();
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
$query = "
SELECT event_id, event_title, event_desc, event_start,DATE_FORMAT( event_start, '%d%b' ) AS tanggal, t3.evtcnt_countname, t3.evtcnt_countcount as hadir
FROM eventcounts_evtcnt AS t3, events_event AS t1, event_types AS t2
WHERE event_id = t3.evtcnt_eventid
AND t1.event_type = t2.type_id
AND t1.event_type =1
AND YEAR( t1.event_start ) =$iTahun
AND t1.event_desc = 'Cut Meutia Pagi'
AND evtcnt_countid =$iKolom
ORDER BY event_start DESC
";
$hasil = mysql_query($query);
while ($data = mysql_fetch_array($hasil))
{
    // menambahkan data hasil query ke array
    array_unshift($dataTanggal, $data['tanggal']);
    array_unshift($dataCMP, $data['hadir']);
}

//Query untuk Cut Meutia Siang
$query = "
SELECT event_id, event_title, event_desc, event_start,DATE_FORMAT( event_start, '%d%b' ) AS tanggal, t3.evtcnt_countname, t3.evtcnt_countcount as hadir
FROM eventcounts_evtcnt AS t3, events_event AS t1, event_types AS t2
WHERE event_id = t3.evtcnt_eventid
AND t1.event_type = t2.type_id
AND t1.event_type =1
AND YEAR( t1.event_start ) =$iTahun
AND t1.event_desc = 'Cut Meutia Siang'
AND evtcnt_countid =$iKolom
ORDER BY event_start DESC
";
$hasil = mysql_query($query);
while ($data = mysql_fetch_array($hasil))
{
    // menambahkan data hasil query ke array
    array_unshift($dataTanggal, $data['tanggal']);
    array_unshift($dataCMS, $data['hadir']);
}


// Query Cikarang
$query = "
SELECT event_id, event_title, event_desc, event_start,DATE_FORMAT( event_start, '%d%b' ) AS tanggal, t3.evtcnt_countname, t3.evtcnt_countcount as hadir
FROM eventcounts_evtcnt AS t3, events_event AS t1, event_types AS t2
WHERE event_id = t3.evtcnt_eventid
AND t1.event_type = t2.type_id
AND t1.event_type =1
AND YEAR( t1.event_start ) =$iTahun
AND t1.event_desc = 'Cikarang'
AND evtcnt_countid =$iKolom
ORDER BY event_start DESC
";
$hasil = mysql_query($query);
while ($data = mysql_fetch_array($hasil))
{
    // menambahkan data hasil query ke array
    array_unshift($dataTanggal, $data['tanggal']);
    array_unshift($dataCikarang, $data['hadir']);	
}

// Query Tambun
$query = "
SELECT event_id, event_title, event_desc, event_start,DATE_FORMAT( event_start, '%d%b' ) AS tanggal, t3.evtcnt_countname, t3.evtcnt_countcount as hadir
FROM eventcounts_evtcnt AS t3, events_event AS t1, event_types AS t2
WHERE event_id = t3.evtcnt_eventid
AND t1.event_type = t2.type_id
AND t1.event_type =1
AND YEAR( t1.event_start ) =$iTahun
AND t1.event_desc = 'Tambun'
AND evtcnt_countid =$iKolom
ORDER BY event_start DESC
";
$hasil = mysql_query($query);
while ($data = mysql_fetch_array($hasil))
{
    // menambahkan data hasil query ke array
    array_unshift($dataTanggal, $data['tanggal']);
    array_unshift($dataTambun, $data['hadir']);	
}

// Query Karawang
$query = "
SELECT event_id, event_title, event_desc, event_start,DATE_FORMAT( event_start, '%d%b' ) AS tanggal, t3.evtcnt_countname, t3.evtcnt_countcount as hadir
FROM eventcounts_evtcnt AS t3, events_event AS t1, event_types AS t2
WHERE event_id = t3.evtcnt_eventid
AND t1.event_type = t2.type_id
AND t1.event_type =1
AND YEAR( t1.event_start ) =$iTahun
AND t1.event_desc = 'Karawang'
AND evtcnt_countid =$iKolom
ORDER BY event_start DESC
";
$hasil = mysql_query($query);
while ($data = mysql_fetch_array($hasil))
{
    // menambahkan data hasil query ke array
    array_unshift($dataTanggal, $data['tanggal']);
    array_unshift($dataKarawang, $data['hadir']);	
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

// mengelompokkan grafik batang berdasarkan tanggal
$gbplot = new GroupBarPlot(array($bplot1,$bplot2,$bplot3,$bplot4,$bplot5,$bplot6));
//$ab1plot = new AccBarPlot(array($bplot2,$bplot3,$bplot4,$bplot5,$bplot6));
//$gbplot1 = new GroupBarPlot(array($bplot2,$bplot3,$bplot4,$bplot5,$bplot6));


// Create the grouped bar plot
//$gbplot = new GroupBarPlot(array($ab1plot,$gbplot1));


$gbplot = new GroupBarPlot(array($bplot2,$bplot3,$bplot4,$bplot5,$bplot6));

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
$bplot6->mark->SetType(MARK_IMG_MBALL,'purple');
$bplot6->value->show();
$bplot6->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot6->value->SetAngle(45);
$graph->Add( $bplot6);  
 
 
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
$bplot5->SetLegend("Cut Mutia Pagi");
$bplot6->SetLegend("Cut Mutia Siang");
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