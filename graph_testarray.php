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

		$dataArray=$_GET['datay1'];
//		$labelArray=unserialize($_GET['label']);
		$JumlahData = $_GET[JumlahData];
		$iJudul = $_GET["Judul"];
	echo $JumlahData;	
setlocale (LC_ALL, 'et_EE.ISO-8859-1');
 
	$a = "datay";
	for ($i = 0; $i <= ($JumlahData); $i++) {//

		$index = $i;
		$NamaArray=$a.$i;
//		echo $Kode2Komisi[$index];
		echo "Nama Array :".$NamaArray;
		$DataArray = substr($_GET[$NamaArray],6);
		echo "Data Array :".$DataArray;
//		print("<pre>".print_r($$NamaArray,true)."</pre>");
		$$NamaArray = array($DataArray);
//		print_r($$NamaArray,true);
		print("<pre>".print_r($$NamaArray,true)."</pre>");
	}


//$datay2=unserialize($_GET['datay2']);
//$data1y=array(12,8,19,3,10,5);
//$data2y=array(8,2,11,7,14,4);
 
//print_r($dataArray,true);

	$a = "datay";
	$b = "bplot";
	$DataPlotArray="";
	for ($i = 0; $i <= ($JumlahData); $i++) {

		$index = $i;
		$NamaArray=$a.$i;
		$NamaPlot=$b.$i;
		//$$NamaPlot=  new BarPlot($NamaArray);
		$DataPlotArray = $DataPlotArray.",".$NamaPlot;	
	} 
	$DataPlotArray = substr($DataPlotArray, 1);
	echo $DataPlotArray;
	

?>