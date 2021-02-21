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

require "Include/ConfigWeb.php";
 
// membuat array inisial untuk jumlah penduduk dan tahunnya
$dataJum = array();
$dataTh = array();
 
 
// query SQL untuk mencari jumlah totol penduduk untuk setiap tahun pada negara A
$query = "SELECT tahun, jmlpria + jmlwanita as jum FROM sensus WHERE negara = 'A'";
$hasil = mysql_query($query);
while ($data = mysql_fetch_array($hasil))
{
    // hasil data query ditambahkan ke dalam array jumlah pendudukan dan tahun
    array_unshift($dataJum, $data['jum']);
    array_unshift($dataTh, $data['tahun']);
}
 
// membuat grafik dengan size 300x200 px
$graph = new Graph(300,200,"auto");    
$graph->SetScale("textlin");
 
// menampilkan data jumlah penduduk ke dalam plot garis
$lineplot=new LinePlot($dataJum);
$graph->Add($lineplot);
 
// mengatur margin plot
$graph->img->SetMargin(40,20,20,40);
 
// menampilkan title dari grafik
$graph->title->Set("Grafik Jumlah Penduduk Negara A");
 
// menampilkan label pada sumbu x grafik
$graph->xaxis->title->Set("Tahun");
 
// menampilkan label pada sumbu y grafik
$graph->yaxis->title->Set("Jumlah");
 
// menampilkan titik data pada sumbu x (tahun)
$graph->xaxis->SetTickLabels($dataTh);
 
// mengatur jenis font pada title grafik
$graph->title->SetFont(FF_FONT1,FS_BOLD);
 
// memberi warna biru pada plot garis
$lineplot->SetColor("blue");
 
// memberikan efek shadow pada image
$graph->SetShadow();
 
// tampilkan grafik ke browser
$graph->Stroke();
?>