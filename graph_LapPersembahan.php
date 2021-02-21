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

$ArrayPersKantong 			= $_SESSION['ArrayPersKantong']			;array_shift($ArrayPersKantong)			;$data3y=$ArrayPersKantong;
$ArrayPersSyukur 			= $_SESSION['ArrayPersSyukur']			;array_shift($ArrayPersSyukur)			;$data4y=$ArrayPersSyukur;
$ArrayPersBulanan 			= $_SESSION['ArrayPersBulanan'] 		;array_shift($ArrayPersBulanan)			;$data5y=$ArrayPersBulanan;
$ArrayPersDanaPengembangan	= $_SESSION['ArrayPersDanaPengembangan'];array_shift($ArrayPersDanaPengembangan);$data6y=$ArrayPersDanaPengembangan;
$ArrayPersULK 				= $_SESSION['ArrayPersULK']				;array_shift($ArrayPersULK)				;$data7y=$ArrayPersULK;
$ArrayPersKhusus 			= $_SESSION['ArrayPersKhusus']			;array_shift($ArrayPersKhusus)			;$data8y=$ArrayPersKhusus;
$ArrayPersLainLain 			= $_SESSION['ArrayPersLainLain']		;array_shift($ArrayPersLainLain)		;$data9y=$ArrayPersLainLain;

$ArrayTotalPerBulan 		= $_SESSION['ArrayTotalPerBulan']		;array_shift($ArrayTotalPerBulan)		;$data10y=$ArrayTotalPerBulan;


//line1
//$data6y=array(50,58,60,58,53,58,57,60,58,58,57,50);
//foreach ($data10y as &$y) { $y -=10; }

// Create the graph. These two calls are always required
$graph = new Graph(1024,500,'auto');
$graph->SetScale("textlin");
$graph->SetY2Scale("lin",0,120000000);
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

$b3plot = new BarPlot($data3y);
$b4plot = new BarPlot($data4y);
$b5plot = new BarPlot($data5y);
$b6plot = new BarPlot($data6y);
$b7plot = new BarPlot($data7y);
$b8plot = new BarPlot($data8y);
$b9plot = new BarPlot($data9y);

$lplot = new LinePlot($data10y);

// Create the grouped bar plot
$gbbplot = new AccBarPlot(array($b3plot,$b4plot,$b5plot,$b6plot,$b7plot,$b8plot,$b9plot));
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

$b3plot->SetColor("blue");
$b3plot->SetFillColor("blue");
$b3plot->SetLegend("Kantong");

$b4plot->SetColor("lime");
$b4plot->SetFillColor("lime");
$b4plot->SetLegend("Syukur");

$b5plot->SetColor("brown");
$b5plot->SetFillColor("brown");
$b5plot->SetLegend("Bulanan");

$b6plot->SetColor("cyan");
$b6plot->SetFillColor("cyan");
$b6plot->SetLegend("Dana Pengembangan");

$b7plot->SetColor("magenta");
$b7plot->SetFillColor("magenta");
$b7plot->SetLegend("ULK");

$b8plot->SetColor("gold");
$b8plot->SetFillColor("gold");
$b8plot->SetLegend("Khusus");

$b9plot->SetColor("lightblue");
$b9plot->SetFillColor("lightblue");
$b9plot->SetLegend("LainLain");



$lplot->SetBarCenter();
$lplot->SetColor("red");
$lplot->SetLegend("Pengeluaran");
$lplot->mark->SetType(MARK_X,'',1.0);
$lplot->mark->SetWeight(3);
$lplot->mark->SetWidth(8);
$lplot->mark->setColor("red");
$lplot->mark->setFillColor("red");

$graph->legend->SetFrameWeight(1);
$graph->legend->SetColumns(10);
$graph->legend->SetColor('#4E4E4E','#00A78A');

//$band = new PlotBand(VERTICAL,BAND_RDIAG,11,"max",'khaki4');
//$band->ShowFrame(true);
//$band->SetOrder(DEPTH_BACK);
//$graph->Add($band);

$graph->title->Set("Grafik Persembahan dan Pengeluaran");

// Display the graph
$graph->Stroke();
?>
