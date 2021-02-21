<?php
        require "Include/jpgraph-1.13/src/jpgraph.php";
		require "Include/jpgraph-1.13/src/jpgraph_pie.php";
		require "Include/jpgraph-1.13/src/jpgraph_pie3d.php";

$row=$_SESSION["GRAPHDATA1"];

$title = "97.253.57.229 STATUS GRAPH AS ON " .  strftime("%d-%b-%y",strtotime(implode("/",split("-",$date))));
$cnt=count($row['SUCCESS']);

for($i=0;$i<$cnt;$i++) {
$success=$row['SUCCESS'][$i];
  if ($success > 0) {
        $gData[] = intval($success);
  } else {
        $gData[]=0;
  }

}
// Callback formatting function for the X-scale to convert timestamps
// to hour and minutes.
function xScaleCallback($aVal) {
        return date('H:i',mktime(0,0,$aVal*300));



// Get the list of custom person Anak 0-7 th
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 0 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (7 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$anak = mysql_result($perintah,0);

// Get the list of custom person Anak 8-16 th
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 8 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (16 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$remaja = mysql_result($perintah,0);

// Get the list of custom person Anak 17-25 th
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 17 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (25 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$pemuda = mysql_result($perintah,0);

// Get the list of custom person Anak 26-50 th
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 26 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (50 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$dewasa = mysql_result($perintah,0);


// Get the list of custom person Anak 51-120 th
$sSQL = "SELECT count(per_ID)
FROM person_per WHERE
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL 51 YEAR) <= CURDATE()
AND
DATE_ADD(CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay),INTERVAL (200 + 1) YEAR) >= CURDATE()";
$perintah = mysql_query($sSQL);
$lansia = mysql_result($perintah,0);







		// Some data
		$data = array($anak,$remaja,$pemuda,$dewasa,$lansia);

		// Create the Pie Graph.
		$graph = new PieGraph(350,300);
		$graph->SetShadow();

		// Set A title for the plot
		$graph->title->Set("Persentasi Umur Jemaat");
		$graph->title->SetFont(FF_VERDANA,FS_BOLD,18);
		$graph->title->SetColor("darkblue");
		$graph->legend->Pos(0.1,0.2);

		// Create pie plot
		$p1 = new PiePlot3d($data);
		$p1->SetTheme("sand");
		$p1->SetCenter(0.4);
		$p1->SetAngle(30);
		$p1->value->SetFont(FF_ARIAL,FS_NORMAL,12);
		$p1->SetLegends(array("Anak","Remaja","Pemuda","Dewasa","Lansia"));

		$graph->Add($p1);
		$graph->Stroke();
?>