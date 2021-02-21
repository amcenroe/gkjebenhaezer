<?php
/*******************************************************************************
 *
 *  filename    : graph.php
 *  description : make graphical data
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *
 ******************************************************************************/
	include ("Include/jpgraph-3.5.0b1/src/jpgraph.php");
	include ("Include/jpgraph-3.5.0b1/src/jpgraph_pie.php");
	include ("Include/jpgraph-3.5.0b1/src/jpgraph_pie3d.php");

//	include ("Include/jpgraph-1.13/src/jpgraph.php");
//	include ("Include/jpgraph-1.13/src/jpgraph_pie.php");
//	include ("Include/jpgraph-1.13/src/jpgraph_pie3d.php");

require "Include/ConfigWeb.php";

//require "Include/Config.php";
//require "Include/Functions.php";


	/* siapkan data yang akan membentuk grafik oval
	   pada grafik ada data yang disiapkan */

		// Some data

				$sSQL = "	SELECT
     COUNT(*)
    FROM (SELECT TIMESTAMPDIFF(YEAR, fam_weddingdate, CURDATE()) AS age FROM family_fam) as derived
    GROUP BY 
    CASE
        WHEN age BETWEEN 0 and 4 THEN 'a.0-4'
        WHEN age BETWEEN 5 and 9 THEN 'b.5-9'
        WHEN age BETWEEN 10 and 19 THEN 'c.10-19'
        WHEN age BETWEEN 20 and 29 THEN 'd.20-29'
        WHEN age BETWEEN 30 and 39 THEN 'e.30-39'
        WHEN age BETWEEN 40 and 49 THEN 'f.40-49'
        WHEN age BETWEEN 50 and 59 THEN 'g.50-59'
        WHEN age >= 60 THEN 'h.>60'
        WHEN age IS NULL THEN 'NoData'
    END ";
				$perintah = mysql_query($sSQL);
//				while ($hasil=mysql_fetch_array($perintah)){
					$text = array();
					$string = '';
					while(list($val) = mysql_fetch_array($perintah))
					{
					$text[] = $val;
					};

					if (!empty($text))
					{
					$string = implode(",",$text);
	//				echo $text;
					}
	//	 $dataArray = array($string);
		 $dataArray = $text;
	//	 array_push($dataArray,$string);
	//	 print_r($dataArray);

		// Some data

				$sSQL = "SELECT
    CASE
        WHEN age BETWEEN 0 and 4 THEN 'a.0-4'
        WHEN age BETWEEN 5 and 9 THEN 'b.5-9'
        WHEN age BETWEEN 10 and 19 THEN 'c.10-19'
        WHEN age BETWEEN 20 and 29 THEN 'd.20-29'
        WHEN age BETWEEN 30 and 39 THEN 'e.30-39'
        WHEN age BETWEEN 40 and 49 THEN 'f.40-49'
        WHEN age BETWEEN 50 and 59 THEN 'g.50-59'
        WHEN age >= 60 THEN 'h.>60'
        WHEN age IS NULL THEN 'NoData'
    END as age_range
    FROM (SELECT TIMESTAMPDIFF(YEAR, fam_weddingdate, CURDATE()) AS age FROM family_fam) as derived
    GROUP BY age_range";
				$perintah = mysql_query($sSQL);
//				while ($hasil=mysql_fetch_array($perintah)){
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
	//				echo $text;
					}
	//	 $dataArray = array($string);
		 $labelArray = $text;
	//	 array_push($dataArray,$string);
	//	 print_r($labelArray);





	// tentukan lebar dan tinggi gambar keseluruhan
	$graph = new PieGraph(350,250,"auto");
	$graph->SetShadow(); // memberi efek bayangan pada frame gambar

	// Set A title for the plot

		$graph->legend->Pos(0.01,0.01);

	// judul yang tertera pada bagian paling atas gambar adalah isi pertanyaan polling
//	$graph->title->Set("Prosentasi Status Perkawinan");
//	$graph->title->SetFont(FF_VERDANA,FS_BOLD,11);
	$graph->title->SetColor("darkblue");
//	$graph->legend->Hide(); // jangan ada legend (penjelasan tiap warna di grafik oval)

	// setup data grafik dari array data yang sudah dipersiapkan sebelumnya
	$p1 = new PiePlot3D($dataArray);
	$p1->ExplodeSlice(2); // memberi jarak salah satu belahan oval dari bentuk oval keseluruhan
	$p1->SetSize(0.45); // untuk mengatur besarnya oval pada gambar keseluruhan
//	$p1->SetSize(0.35); // untuk mengatur besarnya oval pada gambar keseluruhan

	/* Method SetLabels untuk mengatur agar text yang menjelaskan besaran pada tiap belahan
	   oval berformat seperti yang kita inginkan, pada contoh kali ini setiap option jawaban
	   akan diikuti oleh persentase hasil pilihan user pada bagian bawah text */
	$p1->SetLabels(($labelArray),1);



	// masukan setup grafik oval ke frame gambar keseluruhan
	$graph->Add($p1);
	$graph->Stroke(); // generate gambar


