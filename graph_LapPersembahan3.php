<?php // content="text/plain; charset=utf-8"
//require_once ('jpgraph/jpgraph.php');
//re/quire_once ('jpgraph/jpgraph_bar.php');
//require_once ('jpgraph/jpgraph_line.php');
require "Include/Config.php";
require "Include/Functions.php";

	include ("Include/jpgraph-3.5.0b1/src/jpgraph.php");
	include ("Include/jpgraph-3.5.0b1/src/jpgraph_bar.php");
	include ("Include/jpgraph-3.5.0b1/src/jpgraph_line.php");
	
//bar1
//$data1y=array(115,130,135,130,110,130,130,150,130,130,150,120);
//bar2
//$data2y=array(180,200,220,190,170,195,190,210,200,205,195,150);

$ArrayPersBulanan 			= $_SESSION['ArrayPersBulanan'] 		;array_shift($ArrayPersBulanan)			;$data5y=$ArrayPersBulanan;
$ArrayPersDanaPengembangan	= $_SESSION['ArrayPersDanaPengembangan'];array_shift($ArrayPersDanaPengembangan);$data6y=$ArrayPersDanaPengembangan;
$ArrayPersULK 				= $_SESSION['ArrayPersULK']				;array_shift($ArrayPersULK)				;$data7y=$ArrayPersULK;
$ArrayAmplop				= $_SESSION['ArrayAmplop']				;array_shift($ArrayAmplop)				;$data10y=$ArrayAmplop;


//line1
//$data6y=array(50,58,60,58,53,58,57,60,58,58,57,50);
//foreach ($data10y as &$y) { $y -=10; }

// Create the graph. These two calls are always required
$graph = new Graph(1024,500,'auto');
$graph->SetScale("textlin");
$graph->SetY2Scale("lin",0,500);
$graph->SetY2OrderBack(false);

$graph->SetMargin(100,50,20,5);

//$theme_class = new UniversalTheme;
//$graph->SetTheme($theme_class);

//$graph->yaxis->SetTickPositions(array(0,50,100,150,200,250,300,350), array(25,75,125,175,275,325));
//$graph->y2axis->SetTickPositions(array(30,40,50,60,70,80,90));

$months = $gDateLocale->GetShortMonth();
$months = array_merge(array_slice($months,0,12), array_slice($months,0,3));
$graph->SetBox(false);

$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels(array('A','B','C','D'));
// Setup the y-axis to show currency values
$graph->yaxis->SetLabelFormatCallback('number_format');
$graph->yaxis->SetLabelFormat('Rp.%s');

//$graph->yaxis->HideLine(false);
//$graph->yaxis->HideTicks(false,false);
// Setup month as labels on the X-axis
$graph->xaxis->SetTickLabels($months);

// Create the bar plots
//$b1plot = new BarPlot($data1y);
//$b2plot = new BarPlot($data2y);


$b5plot = new BarPlot($data5y);
$b6plot = new BarPlot($data6y);
$b7plot = new BarPlot($data7y);

$lplot = new LinePlot($data10y);

// Create the grouped bar plot
$gbbplot = new AccBarPlot(array($b5plot,$b6plot,$b7plot));
//$gbplot = new GroupBarPlot(array($b1plot,$b2plot,$gbbplot));

// ...and add it to the graPH
$graph->Add($gbbplot);
$graph->AddY2($lplot);

//$b1plot->SetColor("#0000CD");
//$b1plot->SetFillColor("#0000CD");
//$b1plot->SetLegend("Cliants");

//$b2plot->SetColor("#B0C4DE");
//$b2plot->SetFillColor("#B0C4DE");
//$b2plot->SetLegend("Machines");

$b5plot->SetColor("brown");
$b5plot->SetFillColor("brown");
$b5plot->SetLegend("Bulanan");

$b6plot->SetColor("cyan");
$b6plot->SetFillColor("cyan");
$b6plot->SetLegend("Dana Pengembangan");

$b7plot->SetColor("magenta");
$b7plot->SetFillColor("magenta");
$b7plot->SetLegend("Unit Layanan Kasih");



$lplot->SetBarCenter();
$lplot->SetColor("blue");
$lplot->SetLegend("Jumlah Amplop");
$lplot->mark->SetType(MARK_X,'',1.0);
$lplot->mark->SetWeight(3);
$lplot->mark->SetWidth(8);
$lplot->mark->setColor("cyan");
$lplot->mark->setFillColor("cyan");

$graph->legend->SetFrameWeight(1);
$graph->legend->SetColumns(10);
$graph->legend->SetColor('#4E4E4E','#00A78A');

//$band = new PlotBand(VERTICAL,BAND_RDIAG,11,"max",'khaki4');
//$band->ShowFrame(true);
//$band->SetOrder(DEPTH_BACK);
//$graph->Add($band);

$graph->title->Set("Grafik Persembahan Amplop");

// Display the graph
$graph->Stroke();
?>
