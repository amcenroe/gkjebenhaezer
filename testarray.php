<?php
// *  2008 Erwin Pratama for GKJ Bekasi WIl Timur


require "Include/Config.php";
require "Include/Functions.php";


	/* siapkan data yang akan membentuk grafik oval
	   pada grafik ada 4 data yang disiapkan */

		// Some data
		// $dataArray = array($_GET["BTRG"],$_GET["MGHY"]);

				$sSQL = "select b.grp_name as Kelompok, count(a.p2g2r_grp_id) as Anggota
						from person2group2role_p2g2r a, group_grp b, person_per c
						where a.p2g2r_grp_id = b.grp_id AND a.p2g2r_per_id = c.per_ID
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
					echo $string;
					}



?>
