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

				$sSQL = "select count(a.p2g2r_grp_id) as Anggota
						from person2group2role_p2g2r a, group_grp b, person_per c
						where per_cls_id < 3 AND a.p2g2r_grp_id = b.grp_id AND a.p2g2r_per_id = c.per_ID
						group by b.grp_id order by b.grp_id ";
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

				$sSQL = "select b.grp_name
						from person2group2role_p2g2r a, group_grp b, person_per c
						where per_cls_id < 3 AND a.p2g2r_grp_id = b.grp_id AND a.p2g2r_per_id = c.per_ID
						group by b.grp_id order by b.grp_id ";
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
//	$graph->title->Set("Prosentasi Jumlah Warga");
//	$graph->title->SetFont(FF_VERDANA,FS_BOLD,11);
	$graph->title->SetColor("darkblue");
//	$graph->legend->Hide(); // jangan ada legend (penjelasan tiap warna di grafik oval)

	// setup data grafik dari array data yang sudah dipersiapkan sebelumnya
	$p1 = new PiePlot3D($dataArray);
	$p1->ExplodeSlice(2); // memberi jarak salah satu belahan oval dari bentuk oval keseluruhan
	$p1->SetSize(0.5); // untuk mengatur besarnya oval pada gambar keseluruhan
	/* Method SetLabels untuk mengatur agar text yang menjelaskan besaran pada tiap belahan
	   oval berformat seperti yang kita inginkan, pada contoh kali ini setiap option jawaban
	   akan diikuti oleh persentase hasil pilihan user pada bagian bawah text */
	$p1->SetLabels(($labelArray),1);



	// masukan setup grafik oval ke frame gambar keseluruhan
	$graph->Add($p1);
	$graph->Stroke(); // generate gambar


