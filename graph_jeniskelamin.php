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
		$dataArray = array($_GET["lakilaki"],$_GET["perempuan"]);

	// tentukan lebar dan tinggi gambar keseluruhan
	$graph = new PieGraph(350,250,"auto");
	$graph->SetShadow(); // memberi efek bayangan pada frame gambar

	// Set A title for the plot

		$graph->legend->Pos(0.1,0.2);

	// judul yang tertera pada bagian paling atas gambar adalah isi pertanyaan polling
//	$graph->title->Set("Prosentasi Jenis Kelamin");
//	$graph->title->SetFont(FF_VERDANA,FS_BOLD,11);
	$graph->title->SetColor("darkblue");
	$graph->legend->Hide(); // jangan ada legend (penjelasan tiap warna di grafik oval)

	// setup data grafik dari array data yang sudah dipersiapkan sebelumnya
	$p1 = new PiePlot3D($dataArray);
	$p1->ExplodeSlice(1); // memberi jarak salah satu belahan oval dari bentuk oval keseluruhan
//	$p1->SetSize(0.35); // untuk mengatur besarnya oval pada gambar keseluruhan
	$p1->SetSize(0.4); // untuk mengatur besarnya oval pada gambar keseluruhan

	/* Method SetLabels untuk mengatur agar text yang menjelaskan besaran pada tiap belahan
	   oval berformat seperti yang kita inginkan, pada contoh kali ini setiap option jawaban
	   akan diikuti oleh persentase hasil pilihan user pada bagian bawah text */
	$p1->SetLabels(array("Laki Laki\n%.1f%%","Perempuan\n%.1f%%"),1);

	// masukan setup grafik oval ke frame gambar keseluruhan
	$graph->Add($p1);
	$graph->Stroke(); // generate gambar


