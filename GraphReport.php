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
include ("Include/jpgraph-1.13/src/jpgraph_pie.php");
include ("Include/jpgraph-1.13/src/jpgraph_pie3d.php");
require "Include/ConfigWeb.php";

$ilstID = $_GET["lstID"];
$icField = $_GET["cField"];
$ilstOptionID = $_GET["lstOptionID"];
$iclsID = $_GET["clsID"];


// Some Numeric data
if ( $icField == "" ) {
	$sSQL = "select count(a.per_ID) as Jumlah
		from person_per a , person_custom b , list_lst c
		WHERE a.per_cls_ID = c.lst_OptionID AND c.lst_ID = '$ilstID' AND c.lst_OptionID >0	
		GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
} else {
	$sSQL = "select count(a.per_ID) as Jumlah
		from person_per a , person_custom b , list_lst c
		WHERE a.per_ID = b.per_ID AND b.$icField = c.lst_OptionID AND c.lst_ID = '$ilstID'  AND c.lst_OptionID >0
		GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
}
	$perintah = mysql_query($sSQL);
	$text = array();
	$string = '';
		while(list($val) = mysql_fetch_array($perintah))
			{
			$text[] = $val;
			};
		if (!empty($text))
			{
			$string = implode(",",$text);
			}
		 $dataArray = $text;
// Some Desc data
if ( $icField == "" ) {
	$sSQL = "select c.lst_OptionName
		from person_per a , person_custom b , list_lst c
		WHERE a.per_ID = b.per_ID AND c.lst_ID = '$ilstID' AND c.lst_OptionID >0
		GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
} else {
	$sSQL = "select c.lst_OptionName
		from person_per a , person_custom b , list_lst c
		WHERE a.per_ID = b.per_ID AND b.$icField = c.lst_OptionID AND c.lst_ID = '$ilstID' AND c.lst_OptionID >0
		GROUP BY c.lst_OptionName ORDER by lst_OptionSequence";
}
	$perintah = mysql_query($sSQL);
	$text = array();
	$string = '';
		while(list($val) = mysql_fetch_array($perintah))
			{
			$val = "$val \n%.1f%%";
			$text[] = $val;
			};
		if (!empty($text))
			{
			$string = implode(",",$text);
			}
		 $labelArray = $text;

// Graphing
	$graph = new PieGraph(350,250,"auto");
	$graph->SetShadow(); 
	$graph->legend->Pos(0.01,0.01);
	$graph->title->SetColor("darkblue");
// setup data 
	$p1 = new PiePlot3D($dataArray);
	$p1->ExplodeSlice(2); 
	$p1->SetSize(0.5); 

	$p1->SetLabels(($labelArray),1);
	$graph->Add($p1);
	$graph->Stroke(); 


