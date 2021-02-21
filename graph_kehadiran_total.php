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


	include ("Include/jpgraph-1.13/src/jpgraph.php");
	include ("Include/jpgraph-1.13/src/jpgraph_line.php");
	include ("Include/jpgraph-1.13/src/jpgraph_bar.php");

require "Include/ConfigWeb.php";

$iTahun = $_GET["Tahun"];
$iKolom = $_GET["Kolom"];
$iJenis = $_GET["Jenis"];

//$Jenis=="Bar";




switch ($iKolom) 
{
 	case 1: $judul = "Jumlah  Persembahan Kantong - Jemaat Dewasa (Total)";
	$stat = "KebDewasa+KebAnak+KebAnakJTMY+KebRemaja+Syukur+Bulanan+Khusus+
			SyukurBaptis+KhususPerjamuan+Marapas+Marapen+Unduh+Maranatal+Pink+ 
			LainLain+LainLainAmplop";break;
	case 2: $judul = "Jumlah  Kehadiran Jemaat Dewasa (Total)";
			$stat = "1000000+Pria+Wanita";break;
	case 3: $judul = "Kehadiran Majelis (Total)";
			$stat = "1000000+(if(Majelis1<>'0',1,0)+if(Majelis2<>'0',1,0)+if(Majelis3<>'0',1,0)+if(Majelis4<>'0',1,0)+if(Majelis5<>'0',1,0)+if(Majelis6<>'0',1,0)+if(Majelis7<>'0',1,0)
					+if(Majelis8<>'0',1,0)+if(Majelis9<>'0',1,0)+if(Majelis10<>'0',1,0)+if(Majelis11<>'0',1,0)+if(Majelis12<>'0',1,0)+if(Majelis13<>'0',1,0)+if(Majelis14<>'0',1,0)) 
			";
			break;	
 }

//Inisialisasi data
$dataHadir = array();
$dataKantong = array(); 
$dataMajelis = array();
 
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


//Query untuk Cut Meutia Pagi

$stat1 = "KebDewasa+KebAnak+KebAnakJTMY+KebRemaja+Syukur+Bulanan+Khusus+
			SyukurBaptis+KhususPerjamuan+Marapas+Marapen+Unduh+Maranatal+Pink+ 
			LainLain+LainLainAmplop";
$stat2 = "Pria+Wanita";
$stat3 = "(if(Majelis1<>'0',1,0)+if(Majelis2<>'0',1,0)+if(Majelis3<>'0',1,0)+if(Majelis4<>'0',1,0)+if(Majelis5<>'0',1,0)+if(Majelis6<>'0',1,0)+if(Majelis7<>'0',1,0)
					+if(Majelis8<>'0',1,0)+if(Majelis9<>'0',1,0)+if(Majelis10<>'0',1,0)+if(Majelis11<>'0',1,0)+if(Majelis12<>'0',1,0)+if(Majelis13<>'0',1,0)+if(Majelis14<>'0',1,0)) 
			";
	
$query = "
SELECT Tanggal as tanggal, SUM($stat2) as hadir , 
SUM($stat1) as kantong, 
SUM($stat3) as majelis 
FROM Persembahangkjbekti WHERE YEAR(Tanggal) = $iTahun 
GROUP BY TANGGAL 
ORDER BY Tanggal DESC 
";

//echo $query;

$hasil = mysql_query($query);
while ($data = mysql_fetch_array($hasil))
{
    // menambahkan data hasil query ke array
    array_unshift($dataTanggal, $data['tanggal']);
	array_unshift($dataHadir, $data['hadir']);
	array_unshift($dataKantong, $data['kantong']);
    array_unshift($dataMajelis, $data['majelis']);
}

 
// membuat image dengan ukuran 400x200 px
$graph = new Graph(2048,600,"auto");    
$graph->SetScale("textlin");
$graph->SetY2Scale("lin");


if ($iJenis=="Bar"){


//menampilkan data Kehadiran
$bplot2 = new BarPlot($dataHadir);
$bplot2->SetFillColor("blue");
$bplot2->value->show();
$bplot2->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot2->value->SetAngle(45);

//menampilkan data Persembahan
$bplot3 = new BarPlot($dataKantong);
$bplot3->SetFillColor("red");
$bplot3->value->show();
$bplot3->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot3->value->SetAngle(45);



//menampilkan data Majelis
$bplot4 = new BarPlot($dataMajelis);
$bplot4->SetFillColor("green");
$bplot4->value->show();
$bplot4->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot4->value->SetAngle(45);


// mengelompokkan grafik batang berdasarkan tanggal
$gbplot = new GroupBarPlot(array($bplot2,$bplot3,$bplot4));


// Create the grouped bar plot
//$gbplot = new GroupBarPlot(array($ab1plot,$gbplot1));


$gbplot = new GroupBarPlot(array($bplot2,$bplot3,$bplot4));

$graph->Add($gbplot);
 
 }else{
 
//$y2data = array(354 ,200,265, 99,111,91 ,198,225, 293,251);
//$graph->SetY2Scale( "lin");

$bplot2 =new LinePlot($dataHadir);
$bplot2 ->SetColor("blue");
$bplot2->SetFillColor("#00ffff@0.6");
$bplot2->SetWeight(2);
$bplot2->mark->SetType(MARK_IMG_MBALL,'blue');
$bplot2->value->show();
$bplot2->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot2->value->SetAngle(45);
//$graph->Add( $bplot2); 
$graph->AddY2( $bplot2);  
 
 
$bplot3 =new LinePlot($dataKantong);
$bplot3 ->SetColor("red");
$bplot3->SetFillColor("#F5A9D0@0.6");
$bplot3->SetWeight(2);
$bplot3->mark->SetType(MARK_IMG_MBALL,'red');
$bplot3->value->show();
$bplot3->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot3->value->SetAngle(45);
$graph->Add( $bplot3); 
 
$bplot4 =new LinePlot($dataMajelis);
$bplot4 ->SetColor("green");
$bplot4->SetFillColor("#81F781@0.6");
$bplot4->SetWeight(2);
$bplot4->mark->SetType(MARK_IMG_MBALL,'green');
$bplot4->value->show();
$bplot4->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot4->value->SetAngle(45);
$graph->AddY2( $bplot4); 


 
 }
 
 // Membuat Tabel
 


// Format table header row


// .. and add it to the graph


// membuat legend untuk masing2 TI
// $bplot1->SetLegend("Bulak Kapal");
$bplot2->SetLegend("Kehadiran");
$bplot3->SetLegend("Persembahan");
$bplot4->SetLegend("Majelis");

$graph->legend->Pos(0.005,0.005,"right","top");

// mengatur margin image
$graph->img->SetMargin(80,110,20,40);
 
// menampilkan title grafik dan nama masing-masing sumbu
$graph->title->Set("Grafik $judul - Tahun $iTahun");
// format font title grafik
$graph->title->SetFont(FF_ARIAL,FS_BOLD,12);





$graph->xaxis->title->Set("Tanggal");
$graph->yaxis->title->Set("Jumlah Persembahan");
$graph->y2axis->title->Set("Jumlah Kehadiran");


// menampilkan Tanggalke sumbu x
$graph->xaxis->SetTickLabels($dataTanggal);
$graph->xaxis->SetLabelAngle(45);
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
 
// menampilkan efek shadow pada image
$graph->SetShadow();
 
// menampilkan image ke browser
$graph->Stroke();
?>