<?php
/*******************************************************************************
 *
 *  filename    : graph_arraybar.php
 *  description : make graphical data
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2010 Erwin Pratama for GKJ Bekasi Timur
 *
 ******************************************************************************/
	include ("Include/jpgraph-3.5.0b1/src/jpgraph.php");
	include ("Include/jpgraph-3.5.0b1/src/jpgraph_bar.php");

//		$dataArray=unserialize($_GET['data']);
//		$labelArray=unserialize($_GET['label']);
		$JumlahData = $_GET["JumlahData"];
		$iJudul = $_GET["Judul"];
 
	$a = "datay";
	for ($i = 0; $i <= ($JumlahData); $i++) {//

		$index = $i;
		$NamaArray=$a.$i;
		$DataArray = substr($_GET[$NamaArray],6);
		$$NamaArray = array($DataArray);
	}

//$data1y=array(12,8,19,3,10,5);
//$data2y=array(8,2,11,7,14,4);
 $datay1=array(substr($_GET[$datay1],6););
 $datay2=array(substr($_GET[$datay2],6););
 
 
// Create the graph. These two calls are always required
$graph = new Graph(310,200);    
$graph->SetScale("textlin");
 
$graph->SetShadow();
$graph->img->SetMargin(40,30,20,40);
 
// Create the bar plots
$b1plot = new BarPlot($datay1);
//$b1plot->SetFillColor("orange");
$b2plot = new BarPlot($datay2);
//$b2plot->SetFillColor("blue");

	$a = "datay";
	$b = "bplot";
	$DataPlotArray="";
	for ($i = 0; $i <= ($JumlahData); $i++) {

		$index = $i;
		$NamaArray=$a.$i;
		$NamaPlot=$b.$i;
		$$NamaPlot=  new BarPlot($NamaArray);
		$DataPlotArray = $DataPlotArray.",".$NamaPlot;	
	} 
	$DataPlotArray = substr($DataPlotArray, 1);
	
// Create the grouped bar plot
$gbplot = new AccBarPlot(array($b1plot, $b2plot));
 
// ...and add it to the graPH
$graph->Add($gbplot);
 
$graph->title->Set("Accumulated bar plots");
$graph->xaxis->title->Set("X-title");
$graph->yaxis->title->Set("Y-title");
 
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
 
// Display the graph
$graph->Stroke();
?>