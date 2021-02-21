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

	/* siapkan data yang akan membentuk grafik oval
	   pada grafik ada 4 data yang disiapkan */


		// Some data
		$dataArray=unserialize($_GET['data']);
		$labelArray=unserialize($_GET['label']);
		$iKode = $_GET["Kode"];
		$iJudul = $_GET["Judul"];

		$arrayLabel = explode(',', $iKode);
		
	// tentukan lebar dan tinggi gambar keseluruhan
	$graph = new PieGraph(450,550,"auto");
	$graph->SetShadow(); // memberi efek bayangan pada frame gambar


	// Set A title for the plot

//	$graph->legend->Pos(0.1,0.2);

	// judul yang tertera pada bagian paling atas gambar adalah isi pertanyaan polling
	$graph->title->Set($iJudul);
//	$graph->title->SetFont(FF_VERDANA,FS_BOLD,11);
	$graph->title->SetColor("darkblue");
//	$graph->legend->Hide(); // jangan ada legend (penjelasan tiap warna di grafik oval)


	// setup data grafik dari array data yang sudah dipersiapkan sebelumnya
	$p1 = new PiePlot3D($dataArray);
	$p1->SetAngle(80);
	$p1->SetCenter(0.5,0.4);
	$p1->ExplodeSlice(0); // memberi jarak salah satu belahan oval dari bentuk oval keseluruhan
	$p1->SetSize(0.3); // untuk mengatur besarnya oval pada gambar keseluruhan
	/* Method SetLabels untuk mengatur agar text yang menjelaskan besaran pada tiap belahan
	   oval berformat seperti yang kita inginkan, pada contoh kali ini setiap option jawaban
	   akan diikuti oleh persentase hasil pilihan user pada bagian bawah text */
	//$p1->SetLabels(array("{$data->answer1}\n%.1f%%","{$data->answer2}\n%.1f%%","{$data->answer3}\n%.1f%%","{$data->answer4}\n%.1f%%"),1);
	
//$p1->SetLabels(array("SEK1","SEK2","PROC","MDIA","KOMA","KOREM","KOMPA","KWD","KAY","KPWG","KPG","KKG","KKWG","KPK","KPD","LITBANG","HUMAS","KASJ","DANP","ULK"),1);
//$p1->SetLabels(array(SEK1,SEK2,PROC,MDIA,KOMA,KOREM,KOMPA,KWD,KAY,KPWG,KPG,KKG,KKWG,KPK,KPD,LITBANG,HUMAS,KASJ,DANP,ULK),1);

//	$p1->SetLabels($labelArray,1);
$p1->SetLegends($arrayLabel,1);
	$p1->SetLabels($arrayLabel,1);
	
	// masukan setup grafik oval ke frame gambar keseluruhan
	$graph->Add($p1);
	$graph->Stroke(); // generate gambar


