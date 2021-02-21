<?php
/*******************************************************************************
*
*  filename    : SelectListApp3.php
*  2008 Erwin Pratama for GKJ Bekasi WIl Timur ( http://www.gkjbekasi-wiltimur.net )
*  2009 Erwin Pratama for GKJ Bekasi Timur ( http://www.gkjbekasitimur.org )
*  2010 Erwin Pratama for GKPB Bali ( http://www.balichurchsynod.org/ )
*  2013 Erwin Pratama for GKJ Tanjung Priok ( http://www.gkjtp.com )
*
******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";
$Kategori = $_GET["Kategori"];
$iTenThousand = 10000;  // Constant used to offset negative choices in drop down lists

$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;

// Filter received user input as needed
if (strlen($_GET["Sort"]))
        $sSort = FilterInput($_GET["Sort"]);
else
        $sSort = "name";

if (isset($_GET["PrintView"]))
	$bPrintView = true;
if (strlen($_GET["Filter"]))
	$sFilter = FilterInput($_GET["Filter"]);
if (strlen($_GET["Letter"]))
	$sLetter = mb_strtoupper(FilterInput($_GET["Letter"]));


if (strlen($_GET["mode"]))
	$sMode = FilterInput($_GET["mode"]);
elseif (isset($_SESSION['SelectListMode']))
	$sMode = $_SESSION['SelectListMode'];

switch ($sMode)
	{
	case ('DaftarGereja'):
		$_SESSION['SelectListMode'] = $sMode;
		break;
	case ('KegiatanKaryawan'):
		$_SESSION['SelectListMode'] = $sMode;
		break;
	case ('ScrollRT'):
		$_SESSION['SelectListMode'] = $sMode;
		break;		
	case ('KegiatanGereja'):
		$_SESSION['SelectListMode'] = $sMode;
		break;		
	case ('PelayanPendukung'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('BacaanAlkitab'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('PersembahanBulanan'):
		$_SESSION['SelectListMode'] = $sMode;
		break;		
	case ('PersembahanPPPG'):
		$_SESSION['SelectListMode'] = $sMode;
		break;		
	case ('PengeluaranKasKecil'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('PengembalianKasKecil'):
		$_SESSION['SelectListMode'] = $sMode;
		break;			
	case ('PencairanCek'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('PengeluaranPPPG'):
		$_SESSION['SelectListMode'] = $sMode;
		break;
	case ('PengeluaranKlaimAsuransi'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('MasaBaktiMajelis'):
		$_SESSION['SelectListMode'] = $sMode;
		break;		
	default:
		$_SESSION['SelectListMode'] = 'person';
		break;
	}

// Save default search mode
$_SESSION['bSearchFamily'] = ($sMode != 'person');

if (isset($_GET["Number"]))
{
    $_SESSION['SearchLimit'] = FilterInput($_GET["Number"],'int');
    $uSQL =	" UPDATE user_usr SET usr_SearchLimit = " . $_SESSION['SearchLimit'] .
			" WHERE usr_per_ID = " . $_SESSION['iUserID'];
    $rsUser = RunQuery($uSQL);
}

// Set the page title
if ($sMode == 'DaftarGereja')
{
    $sPageTitle = gettext("Daftar Gereja GKJ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Gereja GKJ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 30;
}
elseif ($sMode == 'KegiatanKaryawan')
{
    $sPageTitle = gettext("Daftar Kegiatan Karyawan");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Kegiatan Karyawan";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 40;
}
elseif ($sMode == 'PelayanPendukung')
{
    $sPageTitle = gettext("Jadwal Pelayan Pendukung Ibadah");
	$logvar1 = "Listing";
	$logvar2 = "Jadwal Pelayan Pendukung Ibadah";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 41;
}
elseif ($sMode == 'BacaanAlkitab')
{
    $sPageTitle = gettext("Daftar Bacaan Alkitab Sepekan");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Bacaan Alkitab Sepekan";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 42;
}
elseif ($sMode == 'PersembahanBulanan')
{
    $sPageTitle = gettext("Daftar Persembahan Bulanan");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Persembahan Bulanan";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 43;
}

elseif ($sMode == 'PersembahanPPPG')
{
    $sPageTitle = gettext("Daftar Persembahan PPPG");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Persembahan PPPG";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 44;
}
elseif ($sMode == 'PengeluaranKasKecil')
{
    $sPageTitle = gettext("Daftar Pengeluaran Kas Kecil");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Pengeluaran Kas Kecil";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 45;
}

elseif ($sMode == 'PencairanCek')
{
    $sPageTitle = gettext("Daftar Pencairan Cek ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar PencairanCek";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 46;
}elseif ($sMode == 'PengeluaranPPPG')
{
    $sPageTitle = gettext("Daftar Pengeluaran PPPG");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Pengeluaran PPPG";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 47;
}
elseif ($sMode == 'PengeluaranKlaimAsuransi')
{
    $sPageTitle = gettext("Daftar Pengeluaran Klaim Kesehatan / Asuransi");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Pengeluaran Klaim Asuransi";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 48;
}
elseif ($sMode == 'MasaBaktiMajelis')
{
    $sPageTitle = gettext("Daftar Masa Bakti Majelis");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Masa Bakti Majelis";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 49;
}
elseif ($sMode == 'PengembalianKasKecil')
{
    $sPageTitle = gettext("Daftar Pengembalian Kas Kecil");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Pengembalian Kas Kecil";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 50;
}elseif ($sMode == 'KegiatanGereja')
{
    $sPageTitle = gettext("Daftar Kegiatan Gereja");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Kegiatan Gereja";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 51;
}elseif ($sMode == 'ScrollRT')
{
    $sPageTitle = gettext("Daftar Running Text Warta Gereja");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Running Text Warta Gereja";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 52;
}
else
{
    $sPageTitle = gettext("Daftar Keluarga");
	$logvar1 = "Listing";
	$logvar2 = "Family";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 3;
}

$iPerPage = $_SESSION['SearchLimit'];

if (!$PrintView)
		$sHeaderFile = "Include/Header.php";
else
		$sHeaderFile = "Include/Header-Short.php";

if ($iMode == 30)
{
// echo "listing DaftarGereja";
/**********************
**  Gereja Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select a.GerejaID as GerejaID, a.NamaGereja as NamaGereja, a.Alamat1 as Alamat1,a.Alamat2 as Alamat2,a.Alamat3 as Alamat3,
	   a.Telp as Telp,a.Fax as Fax,a.Email as Email, b.NamaKlasis as NamaKlasis from DaftarGerejaGKJ a 
	   left JOIN DaftarKlasisGKJ b ON a.KlasisID = b.KlasisID ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "NamaGereja":
                        $sSQL = $sSQL . " ORDER BY NamaGereja ASC";
                        break;
                case "NamaKlasis":
                        $sSQL = $sSQL . " ORDER BY NamaKlasis ASC";	
                        break;
				default:
                        $sSQL = $sSQL . " ORDER BY b.KlasisID, a.NamaGereja ASC";
                        break;
        }
//echo $sSQL;
        $rsGerejaCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsGerejaCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"DaftarGerejaEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Gereja") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="DaftarGereja">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="NamaGereja"><?php echo gettext("NamaGereja"); ?></option>
                        <option value="NamaKlasis"><?php echo gettext("NamaKlasis"); ?></option>

                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="DaftarGereja">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";

        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=DaftarGereja&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=DaftarGereja&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=DaftarGereja&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=DaftarGereja&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=DaftarGereja&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="DaftarGereja">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';
         } ?>
		<br>
		Catatan : 
        <BR>
        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
				<td><?php echo gettext("NamaGereja"); ?></td>
				<td><?php echo gettext("NamaKlasis"); ?></td>
                <td><?php echo gettext("Alamat"); ?></td>				
				<td><?php echo gettext("Telp"); ?></td>
				<td><?php echo gettext("Fax"); ?></td>	
				<td><?php echo gettext("Email"); ?></td>	
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>
        <tr>
                <td>&nbsp;</td>
        </tr>
        <?php
        //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$NamaKlasis = ""; 	
				$NamaGereja = ""; 	
				$Keterangan = ""; 	
				$Alamat1 = "";	
				$Alamat2 = "";	
				$Alamat3 = ""; 	
				$Telp = ""; 	
				$Fax = "";
				$Email = "";
		
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="DaftarGerejaEditor.php?GID=$refresh&GerejaID=<?php echo $GerejaID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
                <td><?php echo $NamaGereja ?></td>				
				<td><?php echo $NamaKlasis ?></td>
				<td><?php echo $Alamat1.",".$Alamat2.",".$Alamat3; ?></a>&nbsp;</td>
				<td><?php echo $Telp ?></td>
				<td><?php echo $Fax ?></td> 
				<td><?php echo $Email ?></td> 
					
					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=DaftarGereja&GerejaID=<?php echo $GerejaID ?>">X</a>&nbsp;</td>
						<?php } ?> 
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}
elseif ($iMode == 40)
{
// echo "listing DaftarKegiatanKaryawan";
/****************************
**  Listing Daftar Kegiatan Karyawan  **
*****************************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select a.*, b.per_FirstName as NamaKaryawan , c.* from Kegiatangkjbekti a
	   LEFT JOIN person_per b ON a.KaryawanID = b.per_ID
	   LEFT JOIN LokasiTI c ON a.KodeTI = c.KodeTI
	   
	   ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "KaryawanID":
                        $sSQL = $sSQL . " ORDER BY KaryawanID ASC,JamMulai DESC";
                        break;
                case "KodeTI":
                        $sSQL = $sSQL . " ORDER BY a.KodeTI ASC,JamMulai DESC";	
                        break;
				case "Kronologi":
                        $sSQL = $sSQL . " ORDER BY TanggalMulai DESC,JamMulai DESC";	
                        break;
				default:
                        $sSQL = $sSQL . " ORDER BY TanggalMulai DESC,JamMulai DESC";
                        break;
        }
//echo $sSQL;
        $rsGerejaCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsGerejaCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"KegiatanKaryawanEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Kegiatan Karyawan") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="KegiatanKaryawan">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="KaryawanID"><?php echo gettext("Nama Karyawan"); ?></option>
                        <option value="KodeTI"><?php echo gettext("Tempat Ibadah"); ?></option>
						<option value="Kronologi"><?php echo gettext("Kronologi"); ?></option>

                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="KegiatanKaryawan">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";
        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp3.php\" name=\"ListNumber\">";
                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=KegiatanKaryawan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=KegiatanKaryawan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=KegiatanKaryawan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=KegiatanKaryawan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=KegiatanKaryawan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="KegiatanKaryawan">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';
         } 
		 
		 ?>
		<br>
		Cetak Laporan Kegiatan Karyawan
		<? 
		$sSQL2 = "select a.*, b.per_FirstName as NamaKaryawan from Kegiatangkjbekti a
	   LEFT JOIN person_per b ON a.KaryawanID = b.per_ID
	   GROUP BY a.KaryawanID  ";
		 $rsKaryawan = RunQuery($sSQL2);
		 
		while ($bRow = mysql_fetch_array($rsKaryawan))
        {
		extract($bRow);
		echo "<a href=\"PrintViewKegiatanKaryawan.php?GID=$refresh&KaryawanID=".$KaryawanID . "&TGL=".date('Y-m-d')."\"  target=_blank >";
		echo $NamaKaryawan."</a>";
		echo " | ";
		}
		?>
		Catatan : 
        <BR>
        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
			
				<td><?php echo gettext("Tanggal"); ?></td>
                <td><?php echo gettext("Nama Karyawan"); ?></td>
				<td><?php echo gettext("Target"); ?></td>		
				<td><?php echo gettext("Aktual"); ?></td>					
				<td><?php echo gettext("Tgl Mulai<br>Jam Mulai"); ?></td>		
				<td><?php echo gettext("TglSelesai<br>Jam Selesai"); ?></td>
				<td><?php echo gettext("TempatIbadah"); ?></td>
				<td width="25%"><?php echo gettext("NamaKegiatan"); ?></td>
				<td><?php echo gettext("Hasil"); ?></td>
				<td><?php echo gettext("Keterangan"); ?></td>
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>
        <tr>
                <td>&nbsp;</td>
        </tr>
		<?php
		
		function otherDiffDate( $start, $end, $out_in_array=false){		
		//$end='2020-06-09 10:30:00';		
				
        $intervalo = date_diff(date_create($start), date_create($end));
        $out = $intervalo->format("Years:%Y,Months:%M,Days:%d,Hours:%H,Minutes:%i,Seconds:%s");
        if(!$out_in_array)
            return $out;
        $a_out = array();
        array_walk(explode(',',$out),
        function($val,$key) use(&$a_out){
            $v=explode(':',$val);
            $a_out[$v[0]] = $v[1];
        });
        return $a_out;
}
		
		?>
        <?php
        //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sKegiatanKaryawan_ID = ""; 	 	
			$sKaryawanID = ""; 
			$sTanggal = ""; 	 	
			$sPukul = ""; 	 	
			$sKodeTI = ""; 	 	
			$sNamaKegiatan  = ""; 	 	
			$sKeterangan = ""; 	 	
			$sTanggalMulai = ""; 	 	
			$sTanggalSelesai = ""; 	 	
			$sJamMulai = ""; 	 	
			$sJamSelesai = ""; 	 	
			$sHasil = ""; 	 	
			$sTargetHari = ""; 	 	
			$sTargetJam = ""; 	 	


                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="KegiatanKaryawanEditor.php?GID=$refresh&KegiatanKaryawan_ID=<?php echo $KegiatanKaryawan_ID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			 
                <td><a href="PrintViewKegiatanKaryawan.php?KaryawanID=<?php echo $KaryawanID . "&TGL=".$TanggalMulai."&GID=$refresh&\"  target=_blank >" . date2Ind($TanggalMulai,4); ?></a></td>				
				<td><a href="PrintViewKegiatanKaryawan.php?KaryawanID=<?php echo $KaryawanID . "&GID=$refresh&\"  target=_blank >" . $NamaKaryawan; ?></a></td>
				<td><?php 
				if(isset($TargetHari) && $TargetHari==NULL){echo "";}else{echo $TargetHari." hari ";}
				if(isset($TargetJam) && $TargetJam==NULL){echo "";}else{echo $TargetJam." jam ";}
				 ?></td>
				<td>
				<?php
				$JamMulaiPecah = explode(" ", $JamMulai);
				$JamMulaiPecah1 = $JamMulaiPecah[0];
				$JamMulaiPecah2 = str_replace('.', ':', $JamMulaiPecah1);
		
		//$start = new DateTime($TanggalMulai . " " . $JamMulaiPecah2);
		$start = $TanggalMulai . " " . $JamMulaiPecah2;
		//echo $start;
		
		$JamSelesaiPecah = explode(" ", $JamSelesai);
		$JamSelesaiPecah1 = $JamSelesaiPecah[0];
		$JamSelesaiPecah2 = str_replace('.', ':', $JamSelesaiPecah1);
		
		//$end = new DateTime($TanggalSelesai . " " . $JamSelesaiPecah2);
		$end = $TanggalSelesai . " " . $JamSelesaiPecah2;
		//echo $end;
		//echo "<br>";
		if($JamMulai==0 || $JamSelesai==0 ){echo " ";}else{
		        $intervalo = date_diff(date_create($start), date_create($end));
        $out = $intervalo->format("%d hari,<br> %h jam ,<br> %i menit");
		echo $out;
		}
				?>
				
				</td>
				<td><?php echo date2Ind($TanggalMulai,3); ?><br>
				<?php if($JamMulai==0){echo "<BLINK><font color=\"red\">00.00 WIB</font></BLINK>";}else{echo $JamMulai;} ?>

				</td>
				<td><?php echo date2Ind($TanggalSelesai,3) ?><br>
				<?php if($JamSelesai==0){echo "<BLINK><font color=\"red\">00.00 WIB</font></BLINK>";}else{echo $JamSelesai;} ?></td></td> 
				<td><?php echo $NamaTI ?></td> 	
				<td><?php echo $NamaKegiatan ?></td> 
				<td><?php echo $Hasil ?></td> 				
				<td><?php echo $Keterangan ?></td> 
					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=KegiatanKaryawan&KegiatanKaryawan_ID=<?php echo $KegiatanKaryawan_ID ?>">X</a>&nbsp;</td>
						<?php } ?> 
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}
elseif ($iMode == 41)
{
// echo "listing Daftar Pelayan Pendukung Peribadahan";
/****************************
**  Listing Daftar Kegiatan Karyawan  **
*****************************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select a.* ,
			b.per_FirstName as NamaOrganis, b.per_WorkPhone as KelNamaOrganis, 
			c.per_FirstName as NamaSongLeader , c.per_WorkPhone as KelNamaSongLeader , 
			d.per_FirstName as NamaPengajarSMBalita1 , d.per_WorkPhone as KelNamaPengajarSMBalita1 , 
			e.per_FirstName as NamaPengajarSMBalita2 , e.per_WorkPhone as KelNamaPengajarSMBalita2 , 
			f.per_FirstName as NamaPemusikSMBalita , f.per_WorkPhone as KelNamaPemusikSMBalita , 
			g.per_FirstName as NamaPengajarSMKecil1 , g.per_WorkPhone as KelNamaPengajarSMKecil1 , 
			h.per_FirstName as NamaPengajarSMKecil2 , h.per_WorkPhone as KelNamaPengajarSMKecil2 , 
			i.per_FirstName as NamaPemusikSMKecil , i.per_WorkPhone as KelNamaPemusikSMKecil , 
			j.per_FirstName as NamaPengajarSMBesar1 , j.per_WorkPhone as KelNamaPengajarSMBesar1 , 
			k.per_FirstName as NamaPengajarSMBesar2 , k.per_WorkPhone as KelNamaPengajarSMBesar2 , 
			l.per_FirstName as NamaPemusikSMBesar , l.per_WorkPhone as KelNamaPemusikSMBesar , 
			m.per_FirstName as NamaPengajarPraRemaja1 , m.per_WorkPhone as KelNamaPengajarPraRemaja1 , 
			n.per_FirstName as NamaPengajarPraRemaja2 , n.per_WorkPhone as KelNamaPengajarPraRemaja2 , 
			o.per_FirstName as NamaPemusikPraRemaja , o.per_WorkPhone as KelNamaPemusikPraRemaja , 
			p.per_FirstName as NamaPengajarRemaja1 , p.per_WorkPhone as KelNamaPengajarRemaja1 , 
			q.per_FirstName as NamaPengajarRemaja2 , q.per_WorkPhone as KelNamaPengajarRemaja2 , 
			r.per_FirstName as NamaPemusikRemaja , r.per_WorkPhone as KelNamaPemusikRemaja , 
			s.per_FirstName as NamaPengajarGabungan1 , s.per_WorkPhone as KelNamaPengajarGabungan1 , 
			t.per_FirstName as NamaPengajarGabungan2 , t.per_WorkPhone as KelNamaPengajarGabungan2 , 
			u.per_FirstName as NamaPemusikGabungan , u.per_WorkPhone as KelNamaPemusikGabungan , 
			v.per_FirstName as NamaMultimedia1 , v.per_WorkPhone as KelNamaMultimedia1 , 
			w.per_FirstName as NamaMultimedia2 , w.per_WorkPhone as KelNamaMultimedia2 , 
			
			x.NamaTI as NamaTI, 
			y.grp_Name as KelSaranaIbadah , y.grp_Description as KelompokSaranaIbadah, 
			z.grp_Name as KelKolektan , z.grp_Description as KelompokKolektan
			
			FROM JadwalPelayanPendukung a
	   		
			LEFT JOIN person_per b ON a.KodeOrganis = b.per_ID
			LEFT JOIN person_per c ON a.KodeSongLeader = c.per_ID
			LEFT JOIN person_per d ON a.KodePengajarSMBalita1 = d.per_ID
			LEFT JOIN person_per e ON a.KodePengajarSMBalita2 = e.per_ID
			LEFT JOIN person_per f ON a.KodePemusikSMBalita = f.per_ID
			LEFT JOIN person_per g ON a.KodePengajarSMKecil1 = g.per_ID
			LEFT JOIN person_per h ON a.KodePengajarSMKecil2 = h.per_ID
			LEFT JOIN person_per i ON a.KodePemusikSMKecil = i.per_ID
			LEFT JOIN person_per j ON a.KodePengajarSMBesar1 = j.per_ID
			LEFT JOIN person_per k ON a.KodePengajarSMBesar2 = k.per_ID
			LEFT JOIN person_per l ON a.KodePemusikSMBesar = l.per_ID
			LEFT JOIN person_per m ON a.KodePengajarPraRemaja1 = m.per_ID
			LEFT JOIN person_per n ON a.KodePengajarPraRemaja2 = n.per_ID
			LEFT JOIN person_per o ON a.KodePemusikPraRemaja = o.per_ID
			LEFT JOIN person_per p ON a.KodePengajarRemaja1 = p.per_ID
			LEFT JOIN person_per q ON a.KodePengajarRemaja2 = q.per_ID
			LEFT JOIN person_per r ON a.KodePemusikRemaja = r.per_ID
			LEFT JOIN person_per s ON a.KodePengajarGabungan1 = s.per_ID
			LEFT JOIN person_per t ON a.KodePengajarGabungan2 = t.per_ID
			LEFT JOIN person_per u ON a.KodePemusikGabungan = u.per_ID			
			LEFT JOIN person_per v ON a.KodeMultimedia1 = v.per_ID
			LEFT JOIN person_per w ON a.KodeMultimedia2 = w.per_ID
			
			LEFT JOIN LokasiTI x ON a.KodeTI = x.KodeTI
			LEFT JOIN group_grp y ON a.KodeSaranaIbadah = y.grp_ID 
			LEFT JOIN group_grp z ON a.KodeKolektan = z.grp_ID 
     
	   ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "KaryawanID":
                        $sSQL = $sSQL . " ORDER BY KaryawanID ASC,JamMulai DESC";
                        break;
                case "KodeTI":
                        $sSQL = $sSQL . " ORDER BY a.KodeTI ASC,JamMulai DESC";	
                        break;
				case "Kronologi":
                        $sSQL = $sSQL . " ORDER BY TanggalMulai DESC,JamMulai DESC";	
                        break;
				default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC,a.KodeTI ASC, a.Waktu ASC";
                        break;
        }
//echo $sSQL;
  //      $rsGerejaCount = RunQuery($sSQL);
  //      $Total = mysql_num_rows($rsGerejaCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PelayanPendukungEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Pelayan Pendukung") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="PelayanPendukung">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="KaryawanID"><?php echo gettext("Nama Karyawan"); ?></option>
                        <option value="KodeTI"><?php echo gettext("Tempat Ibadah"); ?></option>
						<option value="Kronologi"><?php echo gettext("Kronologi"); ?></option>

                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="PelayanPendukung">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";
        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp3.php\" name=\"ListNumber\">";
                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PelayanPendukung&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=PelayanPendukung&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PelayanPendukung&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PelayanPendukung&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PelayanPendukung&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="PelayanPendukung">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';
         } ?>
		<br>
		Catatan : 
        <BR>
        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
			
				<td><?php echo gettext("Tempat Ibadah<br>Tanggal / Waktu"); ?></td>
                <td><?php echo gettext("Majelis/<br>Organis/Song Leader"); ?></td>
				<td><?php echo gettext("Sekolah Minggu"); ?></td>		
				<td><?php echo gettext("Pemusik"); ?></td>					
				<td><?php echo gettext("Pendukung Lain"); ?></td>		
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>
        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sPelayanPendukungID = ""; 	 	
			$sKaryawanID = ""; 
			$sTanggal = ""; 	 	
			$sPukul = ""; 	 	
			$sKodeTI = ""; 	 	
			$sNamaKegiatan  = ""; 	 	
			$sKeterangan = ""; 	 	
			$sTanggalMulai = ""; 	 	
			$sTanggalSelesai = ""; 	 	
			$sJamMulai = ""; 	 	
			$sJamSelesai = ""; 	 	
			$sHasil = ""; 	 	
			$sTargetHari = ""; 	 	
			$sTargetJam = ""; 	 	


                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PelayanPendukungEditor.php?PelayanPendukungID=<?php echo $PelayanPendukungID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
                <td><?php echo $NamaTI."
				<br><a target=_blank href=\"PrintViewPelayanPendukung.php?TGL=".$Tanggal."&GID=$refresh&\">" .date2Ind($Tanggal,4)."</a><br>Pk.".$Waktu." 
				<br><br>DressCode: ".$DressCode."<br>Stola: ".$Stola; ?></td>	
				
				<td><?php 
						echo "<i><u><a target=_blank href=\"PrintViewJadwalMajelis.php?TGL=".$Tanggal."&GID=$refresh&\">Majelis: </a></u></i>
						  <br>Koord:".$Majelis1."
						  <br>Lit1 :".$Majelis2." 
						  <br>Lit1 :".$Majelis3." 
						  <br>Lek1 :".$Majelis4." 
						  <br>Lek2 :".$Majelis5." 
						  <br>P.SM :".$Majelis6." 
						  <br>P.Rem:".$Majelis7." 
						  <br>P.Pmd:".$Majelis8." 
						  <br>
						  ";		
						  echo "<i><u>Organis: </u></i><br>".$NamaOrganis."(".$KelNamaOrganis.")";
						  echo "<br>";
						  echo "<i><u>Song Leader: </u></i><br>".$NamaSongLeader."(".$KelNamaSongLeader.")";?></td>


				<td><?php echo "<i><u>K.Balita : </u></i><br>- " .$NamaPengajarSMBalita1."(".$KelNamaPengajarSMBalita1.")";
						  echo "<br>- " .$NamaPengajarSMBalita2."(".$KelNamaPengajarSMBalita2.")";
						  echo "<br>";
						echo "<i><u>K.Kecil : </u></i><br>- " .$NamaPengajarSMKecil1."(".$KelNamaPengajarSMKecil1.")";
						  echo "<br>- " .$NamaPengajarSMKecil2."(".$KelNamaPengajarSMKecil2.")";
						  echo "<br>";
						echo "<i><u>K.Besar : </u></i><br>- " .$NamaPengajarSMBesar1."(".$KelNamaPengajarSMBesar1.")";
						  echo "<br>- " .$NamaPengajarSMBesar2."(".$KelNamaPengajarSMBesar2.")";
						  echo "<br>";
						echo "<i><u>K.Gabungan : </u></i><br>- " .$NamaPengajarGabungan1."(".$KelNamaPengajarGabungan1.")";
						  echo "<br>- " .$NamaPengajarGabungan2."(".$KelPengajarGabungan2.")";
						  echo "<br>";  
						 echo "<i><u>Pra Remaja : </u></i><br>- " .$NamaPengajarPraRemaja1."(".$KelNamaPengajarPraRemaja1.")";
						 // echo " , " .$NamaPengajarPraRemaja2."(".$KelNamaPengajarPraRemaja2.")";
						  echo "<br>";
						echo "<i><u>Remaja : </u></i><br>- " .$NamaPengajarRemaja1."(".$KelNamaPengajarRemaja1.")";
						//  echo " , " .$NamaPengajarRemaja2."(".$KelNamaPengajarRemaja2.")";
						  echo "<br>"; 			 
				?></td> 	
				<td><?php echo "<i><u>K.Balita : </u></i><br>" .$NamaPemusikSMBalita."(".$KelNamaPemusikSMBalita.")";
						  echo "<br>";
						  echo "<i><u>K.Kecil : </u></i><br>" .$NamaPemusikSMKecil."(".$KelNamaPemusikSMKecil.")";
						  echo "<br>";
						  echo "<i><u>K.Besar : </u></i><br>" .$NamaPemusikSMBesar."(".$KelNamaPemusikSMBesar.")";
						  echo "<br>";
						  echo "<i><u>K.Gabungan : </u></i><br>" .$NamaPemusikGabungan."(".$KelNamaPemusikGabungan.")";
						  echo "<br>";
						 echo "<i><u>Pra Remaja : </u></i><br>" .$NamaPemusikPraRemaja."(".$KelNamaPemusikPraRemaja.")";
						  echo "<br>";
						echo "<i><u>Remaja : </u></i><br>" .$NamaPemusikRemaja."(".$KelNamaPemusikRemaja.")";
						  echo "<br>"; 			 
				?></td> 
				<td><?php echo "<i><u>Multimedia : </u></i><br>- " .$NamaMultimedia1."(".$KelNamaMultimedia1.")";
						  echo "<br>- " .$NamaMultimedia2."(".$KelNamaMultimedia2.")";
						  echo "<br>";
						  echo "<i><u>SaranaIbadah : </u></u></i><br>- " .$KelSaranaIbadah." - ".$KelompokSaranaIbadah;
						  echo "<br>";
						  echo "<i><u>Kolektan : </u></u></i><br>- " .$KelKolektan." - ".$KelompokKolektan;
				?></td> 
				<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=PelayanPendukung&PelayanPendukungID=<?php echo $PelayanPendukungID ?>">X</a>&nbsp;</td>
						<?php } ?> 
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}

elseif ($iMode == 42)
{
// echo "listing Daftar Pelayan Pendukung Peribadahan";
/****************************
**  Listing Bacaan Alkitab Sepekan  **
*****************************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select * FROM BacaanAlkitab	   ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
                case "BacaanI":
                        $sSQL = $sSQL . " ORDER BY BacaanI ASC";	
                        break;
				case "BacaanII":
                        $sSQL = $sSQL . " ORDER BY BacaanII ASC";	
                        break;
				case "Mazmur":
                        $sSQL = $sSQL . " ORDER BY Mazmur ASC";	
                        break;	
				case "Injil":
                        $sSQL = $sSQL . " ORDER BY Injil ASC";	
                        break;							
				default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
        }
//echo $sSQL;
  //      $rsGerejaCount = RunQuery($sSQL);
  //      $Total = mysql_num_rows($rsGerejaCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"BacaanAlkitabEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data BacaanAlkitab") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="BacaanAlkitab">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                        <option value="BacaanI"><?php echo gettext("BacaanI"); ?></option>
						<option value="BacaanII"><?php echo gettext("BacaanII"); ?></option>
						<option value="Mazmur"><?php echo gettext("Mazmur"); ?></option>
						<option value="Injil"><?php echo gettext("Injil"); ?></option>

                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="BacaanAlkitab">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";
        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp3.php\" name=\"ListNumber\">";
                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=BacaanAlkitab&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=BacaanAlkitab&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=BacaanAlkitab&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=BacaanAlkitab&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=BacaanAlkitab&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="BacaanAlkitab">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';
         } ?>
		<br>
		Catatan : 
        <BR>
        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
			
				<td><?php echo gettext("Hari dan Tanggal"); ?></td>
                <td><?php echo gettext("Bacaan I"); ?></td>
				<td><?php echo gettext("Mazmur"); ?></td>		
				<td><?php echo gettext("Bacaan II"); ?></td>					
				<td><?php echo gettext("Injil"); ?></td>		
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>
        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sBacaanAlkitabID = ""; 	 	
			$sTanggal = ""; 	 	
			$sBacaanI = ""; 
			$sBacaanII = ""; 			
			$sMazmur = ""; 	 	
			$sInjil  = ""; 	 	
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="BacaanAlkitabEditor.php?BacaanAlkitabID=<?php echo $BacaanAlkitabID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
                <td><?php echo "<a target=_blank href=\"PrintViewBacaanAlkitab.php?TGL=".$Tanggal."&GID=$refresh&\">" .date2Ind($Tanggal,1)."</a>"; 
				//echo date2Ind($Tanggal,1);
				?></td>	
				<td><?php echo $BacaanI;?></td>
				<td><?php echo $Mazmur;?></td>
				<td><?php echo $BacaanII;?></td>
				<td><?php echo $Injil;?></td>
				<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=BacaanAlkitab&BacaanAlkitabID=<?php echo $BacaanAlkitabID ?>">X</a>&nbsp;</td>
						<?php } ?> 
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}

elseif ($iMode == 43)
{
// echo "listing Daftar Persembahan Bulanan";
/****************************
**  Listing Persembahan Bulanan  **
*****************************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select a.*, b.* ,c.per_firstname as NamaPanjang FROM PersembahanBulanan	a
				LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
				LEFT JOIN person_per c ON a.NomorKartu = c.per_ID";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
                case "Kelompok":
                        $sSQL = $sSQL . " ORDER BY Kelompok ASC";	
                        break;
				case "Bulan1":
                        $sSQL = $sSQL . " ORDER BY Bulan1 ASC";	
                        break;
				case "NomorKartu":
                        $sSQL = $sSQL . " ORDER BY NomorKartu ASC";	
                        break;	
				case "KodeNama":
                        $sSQL = $sSQL . " ORDER BY KodeNama ASC";	
                        break;							
				default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, Kelompok ASC";
                        break;
        }
//echo $sSQL;
        $rsGerejaCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsGerejaCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PersembahanBulananEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data PersembahanBulanan") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="PersembahanBulanan">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                        <option value="Kelompok"><?php echo gettext("Kelompok"); ?></option>
						<option value="Bulan1"><?php echo gettext("Bulan1"); ?></option>
						<option value="NomorKartu"><?php echo gettext("NomorKartu"); ?></option>
						<option value="KodeNama"><?php echo gettext("KodeNama"); ?></option>

                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="PersembahanBulanan">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";
        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp3.php\" name=\"ListNumber\">";
                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PersembahanBulanan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=PersembahanBulanan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PersembahanBulanan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PersembahanBulanan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PersembahanBulanan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="PersembahanBulanan">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';
         } ?>
		<br>
		Catatan : 
        <BR>
        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
			
				<td><?php echo gettext("Hari dan Tanggal"); ?></td>
                <td><?php echo gettext("Tempat Ibadah"); ?></td>
				<td><?php echo gettext("Pukul"); ?></td>		
				<td><?php echo gettext("Kelompok"); ?></td>					
				<td><?php echo gettext("Nomor Kartu"); ?></td>		
				<td><?php echo gettext("Kode Nama"); ?></td>
				<td><?php echo gettext("Bulan"); ?></td>
				<td><?php echo gettext("Pers.Bulanan"); ?></td>
				<td><?php echo gettext("Pers.Syukur"); ?></td>
				<td><?php echo gettext("Pers.ULK"); ?></td>
				<td><?php echo gettext("Total Persembahan"); ?></td>
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>
        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sPersembahanBulananID = ""; 	 	
 	
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PersembahanBulananEditor.php?PersembahanBulananID=<?php echo $PersembahanBulananID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
                <td><?php echo "<a target=_blank href=\"PrintViewPersembahanBulanan.php?TGL=".$Tanggal."&GID=$refresh&\">" .date2Ind($Tanggal,1)."</a>"; 
				//echo date2Ind($Tanggal,1);
				?></td>	
				
    <td align="center"><?php echo "<a target=_blank href=\"PrintViewPersembahanBulanan.php?TGL=".$Tanggal."&KodeTI=".$KodeTI."&NamaTI=".$NamaTI."&GID=$refresh&\">".$NamaTI."</a>";?></td>
	
	<td align="center"><?php 
	if ($Pukul==0){echo "<blink>";echo "<font color=\"red\"> Salah!! </font></blink>";}else{
	echo "<a target=_blank href=\"PrintViewPersembahanBulanan.php?TGL=".$Tanggal."&KodeTI=".$KodeTI."&NamaTI=".$NamaTI."&Pukul=".$Pukul."&GID=$refresh&\">".$Pukul."</a>";
	}
	$NamaWarga = $NamaPanjang;	
	?>
	</td>
	
		<td align="center"><?php echo $Kelompok; ?></td>					
				<td align="center"><?php echo $NomorKartu; ?></td>		
<?php
				if ($KodeNama==""){
					echo "<td align=\"center\" >".KodeNamaWarga($NamaPanjang)."</td>";

				}else{
					echo "<td align=\"center\" >".$KodeNama."</td>";
				}
	
?>
				
				<td align="center"><?php echo $Bulan1;
				if (strlen($Bulan2>0)){echo " - ".$Bulan2;} ?></td>
				<td align="right" ><?php echo currency(' ',$Bulanan,'.',',00'); ?></td>
				<td align="right" ><?php echo currency(' ',$Syukur,'.',',00'); ?></td>
				<td align="right" ><?php echo currency(' ',$ULK,'.',',00'); ?></td>
				<td  align="right" ><b><?php 
				$Total=$Bulanan+$Syukur+$ULK;
				echo currency(' ',$Total,'.',',00'); ?></b></td>

				<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td align="center"><a href="SelectDeleteApp.php?mode=PersembahanBulanan&PersembahanBulananID=<?php echo $PersembahanBulananID ?>">X</a>&nbsp;</td>
						<?php } ?> 
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}

elseif ($iMode == 44)
{
// echo "listing Daftar Persembahan PPPG";
/****************************
**  Listing Persembahan PPPG  **
*****************************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select a.*, b.*, c.* FROM PersembahanPPPG	a
				LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
				LEFT JOIN JenisPPPG c ON a.Pos = c.KodeJenis";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
                case "Kelompok":
                        $sSQL = $sSQL . " ORDER BY Kelompok ASC";	
                        break;
				case "Bulan1":
                        $sSQL = $sSQL . " ORDER BY Bulan1 ASC";	
                        break;
				case "NomorKartu":
                        $sSQL = $sSQL . " ORDER BY NomorKartu ASC";	
                        break;	
				case "KodeNama":
                        $sSQL = $sSQL . " ORDER BY KodeNama ASC";	
                        break;							
				default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, Kelompok ASC";
                        break;
        }
//echo $sSQL;
        $rsGerejaCount = RunQuery($sSQL);
      $Total = mysql_num_rows($rsGerejaCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PersembahanPPPGEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data PersembahanPPPG") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="PersembahanPPPG">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                        <option value="Kelompok"><?php echo gettext("Kelompok"); ?></option>
						<option value="Bulan1"><?php echo gettext("Bulan1"); ?></option>
						<option value="NomorKartu"><?php echo gettext("NomorKartu"); ?></option>
						<option value="KodeNama"><?php echo gettext("KodeNama"); ?></option>

                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="PersembahanPPPG">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";
        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp3.php\" name=\"ListNumber\">";
                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PersembahanPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=PersembahanPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PersembahanPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PersembahanPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PersembahanPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="PersembahanPPPG">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';
         } ?>
		<br>
		Catatan : 
        <BR>
        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
			
				<td align="center"><?php echo gettext("Hari dan Tanggal"); ?></td>
                <td align="center"><?php echo gettext("Tempat Ibadah"); ?></td>
				<td align="center"><?php echo gettext("Pukul"); ?></td>	
				<td align="center"><?php echo gettext("Jenis Pers."); ?></td>	
				<td align="center"><?php echo gettext("Cetak"); ?></td>					
				<td align="center"><?php echo gettext("Kelompok"); ?></td>					
				<td align="center"><?php echo gettext("Nomor Kartu"); ?></td>		
				<td align="center"><?php echo gettext("Kode Nama"); ?></td>
				<td align="center"><?php echo gettext("Bulan"); ?></td>
				<td align="center"><?php echo gettext("Pers.PPPG"); ?></td>
				<td align="center"><?php echo gettext("Jml.Amplop"); ?></td>
				
				<td align="center"><?php echo gettext("Keterangan"); ?></td>
				<td align="center"><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>
        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sPersembahanPPPGID = ""; 	 	
 	
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PersembahanPPPGEditor.php?PersembahanPPPGID=<?php echo $PersembahanPPPGID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
                <td><?php echo "<a target=_blank href=\"PrintViewPersembahanPPPG.php?TGL=".$Tanggal."&GID=$refresh&\">" .date2Ind($Tanggal,1)."</a>"; 
				//echo date2Ind($Tanggal,1);
				?></td>	

    <td align="center"><?php 
	if ($KodeTI==0){echo "<blink>";echo "<font color=\"red\"> Salah!! </font></blink>";}else{	
	echo "<a target=_blank href=\"PrintViewPersembahanPPPG.php?TGL=".$Tanggal."&KodeTI=".$KodeTI."&NamaTI=".$NamaTI."&GID=$refresh&\">".$NamaTI."</a>";
	}?></td>
	
	</td>
	<td align="center"><?php 
	if ($Pukul==0){echo "<blink>";echo "<font color=\"red\"> Salah!! </font></blink>";}else{	
	echo "<a target=_blank href=\"PrintViewPersembahanPPPG.php?TGL=".$Tanggal."&KodeTI=".$KodeTI."&NamaTI=".$NamaTI."&Pukul=".$Pukul."&GID=$refresh&\">".$Pukul."</a>";
	}
	?></td>
				<td align="center"><?php echo $NamaJenis; ?></td>
				<td align="center"><?php 
				//if ($KodeJenis==2 OR $KodeJenis==4 OR $KodeJenis==17 ){echo "<a target=_blank href=\"PrintViewVoucher.php?WHO=JIF4&VCHID=".$PersembahanPPPGID."&GID=$refresh&\">Cetak</a>";}
				echo "<a target=_blank href=\"PrintViewVoucher.php?WHO=JIF4&VCHID=".$PersembahanPPPGID."&GID=$refresh&\">Cetak</a>";
				
				?>
				</td>
				<td align="center"><?php echo $Kelompok; ?></td>					
				<td align="center"><?php echo $NomorKartu; ?></td>		
				<td align="center"><?php echo $KodeNama; ?></td>
				<td align="center"><?php echo $Bulan1;
				if (strlen($Bulan2>0)){echo " - ".$Bulan2;} ?></td>
				<td align="right" ><?php echo currency(' ',$Bulanan,'.',',00'); ?></td>
				<td align="right" ><?php echo $JmlAmplop; ?></td>
				
				<td align="center"><?php echo $KetExternal; ?></td>

				<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td align="center"><a href="SelectDeleteApp.php?mode=PersembahanPPPG&PersembahanPPPGID=<?php echo $PersembahanPPPGID ?>">X</a>&nbsp;</td>
						<?php } ?> 
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}
elseif ($iMode == 45)
{
// echo "listing ";
/****************************
**  Listing Pengeluaran Kas Kecil  **
*****************************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select a.*, b.*, c.*, d.* FROM PengeluaranKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
                case "Komisi":
                        $sSQL = $sSQL . " ORDER BY c.KomisiID ASC";	
                        break;
				case "Bidang":
                        $sSQL = $sSQL . " ORDER BY c.BidangID ASC";	
                        break;
				default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, PengeluaranKasKecilID ASC";
                        break;
        }
//echo $sSQL;
        $rsGerejaCount = RunQuery($sSQL);
      $Total = mysql_num_rows($rsGerejaCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PengeluaranKasKecilEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data PengeluaranKasKecil") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="PengeluaranKasKecil">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                        <option value="Komisi"><?php echo gettext("Komisi"); ?></option>
						<option value="Bidang"><?php echo gettext("Bidang"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="PengeluaranKasKecil">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";
        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp3.php\" name=\"ListNumber\">";
                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengeluaranKasKecil&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=PengeluaranKasKecil&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengeluaranKasKecil&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengeluaranKasKecil&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengeluaranKasKecil&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="PengeluaranKasKecil">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';
         } ?>
		<br>
		Catatan : 
        <BR>
        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
			
				<td align="center"><?php echo gettext("Hari dan Tanggal"); ?></td>
				<td align="center"><?php echo gettext("No.Voucher"); ?></td>
				<td align="center"><?php echo gettext("RAB ID"); ?></td>
				<td align="center"><?php echo gettext("Deskripsi"); ?></td>				
				<td align="center"><?php echo gettext("Jumlah"); ?></td>					
				<td align="center"><?php echo gettext("Keterangan"); ?></td>
				<td align="center"><?php echo gettext("PosAnggaran"); ?></td>
                <td align="center"><?php echo gettext("Komisi"); ?></td>
				<td align="center"><?php echo gettext("Bidang"); ?></td>					
				<td align="center"><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>
        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sPengeluaranKasKecilID = ""; 	 	
 	
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PengeluaranKasKecilEditor.php?PengeluaranKasKecilID=<?php echo $PengeluaranKasKecilID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
            <td><?php echo "<a target=_blank href=\"PrintViewPengeluaranKasKecil.php?TGL=".$Tanggal."&GID=$refresh&\">" .date2Ind($Tanggal,1)."</a>"; ?></td>	
			<td><?php echo "<a target=_blank href=\"PrintViewVoucher.php?VCHID=".$PengeluaranKasKecilID."&GID=$refresh&\">" .$PengeluaranKasKecilID."</a>";?></td>	
			<td align="center"><?php echo $RabID; ?></td>	
			<td align="left"><?php echo $DeskripsiKas; ?></td>					
			<td align="right" ><?php echo currency(' ',$Jumlah,'.',',00'); ?></td>
			<td align="right" ><?php echo $Keterangan; ?></td>
			<td><?php 
			if ($PosAnggaranID==''){echo "<blink><font color=red>Pos Anggaran SALAH!!</font></blink>";}else{
			echo "<a target=_blank href=\"PrintViewPengeluaranKasKecil.php?POSID=".$PosAnggaranID."&TGL=".$Tanggal."&GID=$refresh&\">" .$NamaPosAnggaran."</a>";
			}
			?></td>
			<td><?php echo "<a target=_blank href=\"PrintViewPengeluaranKasKecil.php?KOMID=".$KomisiID."&TGL=".$Tanggal."&GID=$refresh&\">" .$KodeKomisi."</a>";?></td>	
			<td><?php echo "<a target=_blank href=\"PrintViewPengeluaranKasKecil.php?BIDID=".$BidangID."&TGL=".$Tanggal."&GID=$refresh&\">" .$KodeBidang."</a>";?></td>	

				<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td align="center"><a href="SelectDeleteApp.php?mode=PengeluaranKasKecil&PengeluaranKasKecilID=<?php echo $PengeluaranKasKecilID ?>">X</a>&nbsp;</td>
						<?php } ?> 
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }
 
        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}elseif ($iMode == 46)
{
// echo "listing ";
/****************************
**  Listing PencairanCek  **
*****************************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select * FROM PencairanCek
";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
                case "Jumlah":
                        $sSQL = $sSQL . " ORDER BY Jumlah ASC";	
                        break;
				default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, PencairanCekID ASC";
                        break;
        }
//echo $sSQL;
        $rsGerejaCount = RunQuery($sSQL);
      $Total = mysql_num_rows($rsGerejaCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PencairanCekEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data PencairanCek") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="PencairanCek">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                        <option value="Jumlah"><?php echo gettext("Jumlah"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="PencairanCek">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";
        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp3.php\" name=\"ListNumber\">";
                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PencairanCek&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=PencairanCek&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PencairanCek&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PencairanCek&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PencairanCek&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="PencairanCek">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';
         } ?>
		<br>
		Catatan : 
        <BR>
        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
			
				<td align="center"><?php echo gettext("Hari dan Tanggal"); ?></td>
				<td align="center"><?php echo gettext("Cetak Permohonan"); ?></td>
				<td align="center"><?php echo gettext("Nomor Cek"); ?></td>				
				<td align="center"><?php echo gettext("Jumlah"); ?></td>					
				<td align="center"><?php echo gettext("Keterangan"); ?></td>
				<td align="center"><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>
        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sPencairanCekID = ""; 	 	
 	
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PencairanCekEditor.php?PencairanCekID=<?php echo $PencairanCekID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
            <td align="center"><?php echo "<a target=_blank href=\"PrintViewPencairanCek.php?TGL=".$Tanggal."&GID=$refresh&\">" .date2Ind($Tanggal,1)."</a>"; ?></td>	
			<td align="center"><?php echo "<a target=_blank href=\"PrintViewVoucher.php?WHO=Cek&VCHID=".$PencairanCekID."&GID=$refresh&\">" .$PencairanCekID."</a>";?></td>	

			<td align="center"><?php echo $NomorCek; ?></td>					
			<td align="right" ><?php echo currency(' ',$Jumlah,'.',',00'); ?></td>
			<td align="center" ><?php echo $Keterangan; ?></td>
	
		<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td align="center"><a href="SelectDeleteApp.php?mode=PencairanCek&PencairanCekID=<?php echo $PencairanCekID ?>">X</a>&nbsp;</td>
						<?php } ?> 
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}
elseif ($iMode == 47)
{
// echo "listing Daftar Pengeluaran PPPG";
/****************************
**  Listing Pengeluaran PPPG  **
*****************************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select a.*, a.Keterangan as KetTambahan , b.*, c.* FROM PengeluaranPPPG	a
				LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI
				LEFT JOIN JenisPengeluaranPPPG c ON a.Pos = c.KodeJenis";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
                case "Pos":
                        $sSQL = $sSQL . " ORDER BY Pos ASC";	
                        break;
	
				default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, Pos ASC";
                        break;
        }
//echo $sSQL;
        $rsGerejaCount = RunQuery($sSQL);
      $Total = mysql_num_rows($rsGerejaCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PengeluaranPPPGEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data PengeluaranPPPG") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="PengeluaranPPPG">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
						<option value="Pos"><?php echo gettext("Pos"); ?></option>

                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="PengeluaranPPPG">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";
        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp3.php\" name=\"ListNumber\">";
                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengeluaranPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=PengeluaranPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengeluaranPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengeluaranPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengeluaranPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="PengeluaranPPPG">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';
         } ?>
		<br>
		Catatan : 
        <BR>
        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
		
				<td align="center"><?php echo gettext("Hari dan Tanggal"); ?></td>
                <td align="center"><?php echo gettext("Tempat Ibadah"); ?></td>
				<td align="center"><?php echo gettext("No Voucher"); ?></td>
				<td align="center"><?php echo gettext("Di Serahkan Kepada"); ?></td>				
				<td align="center"><?php echo gettext("Keperluan"); ?></td>					
				<td align="center"><?php echo gettext("Jumlah"); ?></td>		
				<td align="center"><?php echo gettext("Pos"); ?></td>
				<td align="center"><?php echo gettext("Keterangan"); ?></td>
				<td align="center"><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>
        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sPengeluaranPPPGID = ""; 	 	
 	
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PengeluaranPPPGEditor.php?PengeluaranPPPGID=<?php echo $PengeluaranPPPGID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
                <td><?php echo "<a target=_blank href=\"PrintViewPengeluaranPPPG.php?TGL=".$Tanggal."&GID=$refresh&\">" .date2Ind($Tanggal,1)."</a>"; 
				//echo date2Ind($Tanggal,1);
				?></td>	

    <td align="center"><?php 
	if ($KodeTI==0){echo "<blink>";echo "<font color=\"red\"> Salah!! </font></blink>";}else{	
	echo "<a target=_blank href=\"PrintViewPengeluaranPPPG.php?TGL=".$Tanggal."&KodeTI=".$KodeTI."&NamaTI=".$NamaTI."&GID=$refresh&\">".$NamaTI."</a>";
	}?></td>
	
	</td>
	<td align="center"><?php echo "<a target=_blank href=\"PrintViewVoucher.php?WHO=PPPG&VCHID=".$PengeluaranPPPGID."&GID=$refresh&\">" .$PengeluaranPPPGID."</a>";?></td>
	<td align="center"><?php echo $DiserahkanKepada; ?></td>
				<td align="center"><?php echo $Keperluan; ?></td>
				<td align="right" ><?php echo currency(' ',$Jumlah,'.',',00'); ?></td>
				<td align="center"><?php echo $Keterangan; ?></td>
				<td align="center"><?php echo $KetTambahan; ?></td>
				<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td align="center"><a href="SelectDeleteApp.php?mode=PengeluaranPPPG&PengeluaranPPPGID=<?php echo $PengeluaranPPPGID ?>">X</a>&nbsp;</td>
						<?php } ?> 
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}
elseif ($iMode == 48)
{
// echo "listing ";
/****************************
**  Listing Pengeluaran Klaim Asuransi  **
*****************************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select a.*, YEAR(a.Tanggal) as TahunKlaim,
		b.*,  a.PosAnggaranID as KaryawanID, 
		a.Keterangan as Rujukan
	   FROM PengeluaranKlaimAsuransi	a
				LEFT JOIN person_per b ON a.PosAnggaranID = b.per_ID";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
                case "Karyawan":
                        $sSQL = $sSQL . " ORDER BY a.PosAnggaranID ASC";	
                        break;
				default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, PengeluaranKlaimAsuransiID ASC";
                        break;
        }
//echo $sSQL;
        $rsGerejaCount = RunQuery($sSQL);
      $Total = mysql_num_rows($rsGerejaCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PengeluaranKlaimAsuransiEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data PengeluaranKlaimAsuransi") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="PengeluaranKlaimAsuransi">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                        <option value="Karyawan"><?php echo gettext("Karyawan"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="PengeluaranKlaimAsuransi">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";
        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp3.php\" name=\"ListNumber\">";
                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengeluaranKlaimAsuransi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=PengeluaranKlaimAsuransi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengeluaranKlaimAsuransi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengeluaranKlaimAsuransi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengeluaranKlaimAsuransi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="PengeluaranKlaimAsuransi">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';
         } ?>
		<br>
		Catatan : 
        <BR>
        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
			
				<td align="center"><?php echo gettext("Hari dan Tanggal"); ?></td>
				<td align="center"><?php echo gettext("No Kwitansi"); ?></td>
				<td align="center"><?php echo gettext("No.Klaim"); ?></td>
				<td align="center"><?php echo gettext("Nama Karyawan"); ?></td>
				<td align="center"><?php echo gettext("Nama Pasien"); ?></td>
				<td align="center"><?php echo gettext("Diagnosis/Tempat Rujukan"); ?></td>
			
				<td align="center"><?php echo gettext("Jumlah"); ?></td>
							
                <td align="center"><?php echo gettext("Plafon"); ?></td>
				<td align="center"><?php echo gettext("Total Klaim"); ?></td>
				<td align="center"><?php echo gettext("Sisa Plafon"); ?></td>	
				<td align="center"><?php echo gettext("Prosentase"); ?></td>					
				<td align="center"><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>
        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sPengeluaranKlaimAsuransiID = ""; 	 	
 	
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PengeluaranKlaimAsuransiEditor.php?PengeluaranKlaimAsuransiID=<?php echo $PengeluaranKlaimAsuransiID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
            <td align="center" ><?php echo "<a title=\"Cetak Laporan Klaim per Tahun\" target=_blank href=\"PrintViewKlaimAsuransi.php?TGL=".$Tanggal."&GID=$refresh&\">" .date2Ind($Tanggal,1)."</a>"; ?></td>
			<td align="left"><?php echo $NomorKwitansi; ?></td>			
			<td align="center" ><?php echo "<a title=\"Cetak Kwitansi\" target=_blank href=\"PrintViewVoucher.php?VCHID=".$PengeluaranKlaimAsuransiID."&WHO=SEHAT&GID=$refresh&\">K".$TahunKlaim."-" .$PengeluaranKlaimAsuransiID."</a>";?></td>	
			<td align="left"><?php echo "<a title=\"Cetak Laporan Klaim per Karyawan\" target=_blank href=\"PrintViewKlaimAsuransi.php?TGL=".$Tanggal."&KaryawanID=".$PosAnggaranID."&GID=$refresh&\" >" .$per_FirstName."</a>"; ?></td>
			<td align="left"><?php echo $Pasien; ?></td>				
			<td align="left"><?php echo $DeskripsiKas; ?><br><?php echo $Rujukan; ?></td>			
			<td align="right" ><?php echo currency(' ',$Jumlah,'.',',00'); ?></td>

<?			
		$sSQL1 = "
		select a.*, b.per_FirstName , c.*, a.Keterangan as Rujukan, c.Keterangan as Tanggungan, SUM(Jumlah) as TotalKlaim 
			FROM PengeluaranKlaimAsuransi a 
			LEFT JOIN person_per b ON a.PosAnggaranID = b.per_ID 
			LEFT JOIN MasterAnggaranKlaimAsuransi c ON a.PosAnggaranID = c.KaryawanID 
			WHERE c.TahunAnggaran = YEAR('".$Tanggal."')  AND a.PosAnggaranID = ".$PosAnggaranID." LIMIT 1  ";
		$rsKlaim = RunQuery($sSQL1);
		$aRow = mysql_fetch_array($rsKlaim);
		extract($aRow);
		
	?>
			<td align="right" ><?php echo currency(' ',$Budget,'.',',00'); ?></td>	
			<td align="right" ><?php echo currency(' ',$TotalKlaim,'.',',00'); ?></td>	
			<td align="right" ><?php echo currency(' ',($Budget-$TotalKlaim),'.',',00'); ?></td>			
			<td align="right" ><?php  
			$prosen=round(($TotalKlaim/$Budget*100),2); 
			if ($prosen > 100){echo "<BLINK><font color=\"red\">".$prosen."</font></BLINK>";}
			else{ echo $prosen;}
			
			?>%</td>
				<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td align="center"><a href="SelectDeleteApp.php?mode=PengeluaranKlaimAsuransi&PengeluaranKlaimAsuransiID=<?php echo $PengeluaranKlaimAsuransiID ?>">X</a>&nbsp;</td>
						<?php } ?> 
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}elseif ($iMode == 49)
{
// echo "listing ";
/****************************
**  Listing Masa Bakti Majelis  **
*****************************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select a.*, b.*, c.* , d.*, YEAR(a.TglAkhir) as TahunAkhir, YEAR(CURDATE()) as TahunIni
	   FROM MasaBaktiMajelis	a
			LEFT JOIN person_per b ON a.per_ID = b.per_ID
			LEFT JOIN volunteeropportunity_vol c ON a.vol_ID = c.vol_ID
			LEFT JOIN NotulaRapat d ON a.TglKeputusan = d.Tanggal
				";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Nama":
                        $sSQL = $sSQL . " ORDER BY b.per_FirstName ASC";
                        break;
                case "Kelompok":
                        $sSQL = $sSQL . " ORDER BY b.per_WorkPhone ASC";	 
                        break;
				case "Jabatan":
                        $sSQL = $sSQL . " ORDER BY a.vol_ID ASC";	
                        break;	
				case "TglKeputusan":
                        $sSQL = $sSQL . " ORDER BY a.TglKeputusan DESC, b.per_WorkPhone, b.per_FirstName";	
                        break;							
				case "TglPeneguhan":
                        $sSQL = $sSQL . " ORDER BY a.TglPeneguhan DESC, b.per_WorkPhone, b.per_FirstName";	
                        break;
				case "TglAkhir":
                        $sSQL = $sSQL . " ORDER BY a.TglAkhir DESC, b.per_WorkPhone, b.per_FirstName";	
                        break;							
				default:
                        $sSQL = $sSQL . " ORDER BY b.per_FirstName, a.TglAkhir DESC, b.per_WorkPhone";
                        break;
        }
//echo $sSQL;
        $rsGerejaCount = RunQuery($sSQL);
      $Total = mysql_num_rows($rsGerejaCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"MasaBaktiMajelisEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data MasaBaktiMajelis") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="MasaBaktiMajelis">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Nama"); ?></option>
                        <option value="Kelompok"><?php echo gettext("Kelompok"); ?></option>
						<option value="Jabatan"><?php echo gettext("Jabatan"); ?></option>
						<option value="TglKeputusan"><?php echo gettext("TglKeputusan"); ?></option>
						<option value="TglPeneguhan"><?php echo gettext("TglPeneguhan"); ?></option>
						<option value="TglAkhir"><?php echo gettext("TglAkhir"); ?></option>
						
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="MasaBaktiMajelis">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";
        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp3.php\" name=\"ListNumber\">";
                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=MasaBaktiMajelis&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=MasaBaktiMajelis&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=MasaBaktiMajelis&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=MasaBaktiMajelis&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=MasaBaktiMajelis&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="MasaBaktiMajelis">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';
         } ?>
		<br>
		Catatan : 
        <BR>
        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
				<td align="center"><?php echo gettext(" "); ?></td>
				<td align="center"><?php echo gettext("Nama"); ?></td>
				<td align="center"><?php echo gettext("Jabatan"); ?></td>
				<td align="center"><?php echo gettext("Kelompok"); ?></td>
				<td align="center"><?php echo gettext("<u>Tgl Keputusan</u><br>Diputuskan di"); ?></td>
				<td align="center"><?php echo gettext("<u>Tgl Peneguhan</u><br>Dilayani Oleh"); ?></td>
				<td align="center"><?php echo gettext("Tgl Akhir"); ?></td>
				<td align="center"><?php echo gettext("Kategori"); ?></td>
				<td align="center" colspan="2"><?php echo gettext("Keterangan"); ?></td>

				<td align="center"><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>
        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the surat recordset
		$i=0;
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sPengeluaranKlaimAsuransiID = ""; 	 	
 	
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="MasaBaktiMajelisEditor.php?MasaBaktiMajelisID=<?php echo $MasaBaktiMajelisID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			<td align="left"><?php  
			$TglSkrg=date("Y-m-d");
			if ($TglAkhir>$TglSkrg){$i++;echo $i;}
			else{echo "";};	
			?></td>
			
			<td align="left"><?php 
			$TglSkrg=date("Y-m-d");
			if ($TglAkhir>$TglSkrg){echo "<font color=blue><b>$per_FirstName</b><font>";}
			else{echo $per_FirstName;};	
			?></td>		
			<td align="left"><?php echo $vol_Name; ?></td>		
			<td align="center"><?php echo $per_WorkPhone; ?></td>	
			<td align="left"><?php echo date2Ind($TglKeputusan,2)."<br>".$NomorSurat; ?></td>
			<td align="left">
			<?php
			$TglSkrg=date("Y-m-d");
			$TglPendewasaan='2011-01-22';
			if ($TglPeneguhan>$TglPendewasaan){	
			echo "<a href=\"PrintViewMajelisBeritaAcara.php?TglPeneguhan=$TglPeneguhan&GID=$refresh&\" target=\"_blank\" title=\"Cetak Berita Acara\">".date2Ind($TglPeneguhan,2)."</a>";
			echo "<br>".$Pendeta."<br>";
			echo "<a href=\"PrintViewMajelisSK.php?MasaBaktiMajelisID=$MasaBaktiMajelisID&Mode=1&GID=$refresh&\" target=\"_blank\" title=\"Dengan Kop\">SK-m1</a>";
			echo " - ";
			echo "<a href=\"PrintViewMajelisSK.php?MasaBaktiMajelisID=$MasaBaktiMajelisID&Mode=3&GID=$refresh&\" target=\"_blank\" title=\"Tanpa Kop\">SK-m2</a>";
			}else{
			echo "<br>".date2Ind($TglPeneguhan,2)."<br>";
			echo "<br>".$Pendeta."<br>";
			};	
			
			?>
			</td>
			<td align="left"><?php 
			
		//	echo $TahunAkhir;
		//	echo $TahunIni;
			if ($TahunAkhir==$TahunIni AND $TglAkhir>$TglSkrg){
			echo "<font color=red><b>".date2Ind($TglAkhir,2)."</b></font>"; 
			}else{ echo date2Ind($TglAkhir,2); }
			?><br>
			<? if ($TglAkhir<$TglSkrg){
			echo "<a href=\"PrintViewMajelisSertifikat.php?MasaBaktiMajelisID=$MasaBaktiMajelisID&GID=$refresh&\" target=\"_blank\">CetakTrims"; 
			}
			?></a></td>			
			<td align="left"><?php echo $Kategorial; ?></td>
			<td align="left"><?php  
			$TglSkrg=date("Y-m-d");
			if ($TglAkhir>$TglSkrg){echo "Aktif";}else{echo "";};	
			echo " ".$KetTambahan; ?></td>

				<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td align="center"><a href="SelectDeleteApp.php?mode=MasaBaktiMajelis&MasaBaktiMajelisID=<?php echo $MasaBaktiMajelis ?>">X</a>&nbsp;</td>
						<?php } ?> 
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}
elseif ($iMode == 50)
{
// echo "listing ";
/****************************
**  Listing Pengembalian Kas Kecil  **
*****************************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select a.*, b.*, c.*, d.* FROM PengembalianKasKecil	a
				LEFT JOIN MasterPosAnggaran b ON a.PosAnggaranID = b.PosAnggaranID
				LEFT JOIN MasterKomisi c ON b.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
                case "Komisi":
                        $sSQL = $sSQL . " ORDER BY c.KomisiID ASC";	
                        break;
				case "Bidang":
                        $sSQL = $sSQL . " ORDER BY c.BidangID ASC";	
                        break;
				default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, PengembalianKasKecilID ASC";
                        break;
        }
//echo $sSQL;
        $rsGerejaCount = RunQuery($sSQL);
      $Total = mysql_num_rows($rsGerejaCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PengembalianKasKecilEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data PengembalianKasKecil") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="PengembalianKasKecil">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                        <option value="Komisi"><?php echo gettext("Komisi"); ?></option>
						<option value="Bidang"><?php echo gettext("Bidang"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="PengembalianKasKecil">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";
        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp3.php\" name=\"ListNumber\">";
                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengembalianKasKecil&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=PengembalianKasKecil&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengembalianKasKecil&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengembalianKasKecil&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=PengembalianKasKecil&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="PengembalianKasKecil">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';
         } ?>
		<br>
		Catatan : 
        <BR>
        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
			
				<td align="center"><?php echo gettext("Hari dan Tanggal"); ?></td>
				<td align="center"><?php echo gettext("No.Voucher"); ?></td>
				<td align="center"><?php echo gettext("Deskripsi"); ?></td>				
				<td align="center"><?php echo gettext("Jumlah"); ?></td>					
				<td align="center"><?php echo gettext("Keterangan"); ?></td>
				<td align="center"><?php echo gettext("PosAnggaran"); ?></td>
                <td align="center"><?php echo gettext("Komisi"); ?></td>
				<td align="center"><?php echo gettext("Bidang"); ?></td>					
				<td align="center"><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>
        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the surat recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
			$sPengembalianKasKecilID = ""; 	 	
 	
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PengembalianKasKecilEditor.php?PengembalianKasKecilID=<?php echo $PengembalianKasKecilID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
            <td><?php echo "<a target=_blank href=\"PrintViewPengembalianKasKecil.php?TGL=".$Tanggal."&GID=$refresh&\">" .date2Ind($Tanggal,1)."</a>"; ?></td>	
			<td><?php echo "<a target=_blank href=\"PrintViewVoucher.php?WHO=PengembalianKasKecil&VCHID=".$PengembalianKasKecilID."&GID=$refresh&\">" .$PengembalianKasKecilID."</a>";?></td>	

			<td align="left"><?php echo $DeskripsiKas; ?></td>					
			<td align="right" ><?php echo currency(' ',$Jumlah,'.',',00'); ?></td>
			<td align="right" ><?php echo $Keterangan; ?></td>
			<td><?php 
			if ($PosAnggaranID==''){echo "<blink><font color=red>Pos Anggaran SALAH!!</font></blink>";}else{
			echo "<a target=_blank href=\"PrintViewPengembalianKasKecil.php?POSID=".$PosAnggaranID."&TGL=".$Tanggal."&GID=$refresh&\">" .$NamaPosAnggaran."</a>";
			}
			?></td>
			<td><?php echo "<a target=_blank href=\"PrintViewPengembalianKasKecil.php?KOMID=".$KomisiID."&TGL=".$Tanggal."&GID=$refresh&\">" .$KodeKomisi."</a>";?></td>	
			<td><?php echo "<a target=_blank href=\"PrintViewPengembalianKasKecil.php?BIDID=".$BidangID."&TGL=".$Tanggal."&GID=$refresh&\">" .$KodeBidang."</a>";?></td>	

				<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td align="center"><a href="SelectDeleteApp.php?mode=PengembalianKasKecil&PengembalianKasKecilID=<?php echo $PengembalianKasKecilID ?>">X</a>&nbsp;</td>
						<?php } ?> 
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}elseif ($iMode == 51)
{
//echo "listing Kegiatan Gereja ";
/*****************************
**  Daftar Kegiatan Gereja **
******************************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.*, c.*, d.* FROM KegiatanGereja a
				LEFT JOIN MasterKomisi c ON a.KomisiID = c.KomisiID
				LEFT JOIN MasterBidang d ON c.BidangID = d.BidangID
		";

        //Apply the sort based on what was passed in
        switch($sSort)
        {

                case "Bidang":
                        $sSQL = $sSQL . " ORDER BY BidangID ASC";
                        break;		
                case "Gereja":
                        $sSQL = $sSQL . " ORDER BY GerejaID ASC";
                        break;
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal ASC";
                        break;
                case "Keterangan":
                        $sSQL = $sSQL . " ORDER BY Keterangan ASC";
                        break;						
				default:
                        $sSQL = $sSQL . " ORDER BY  a.Tanggal DESC ";
                        break;
        }

        $rsMailCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsMailCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsMail = RunQuery($sSQL);
		
        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"KegiatanGerejaEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Kegiatan Gereja") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="KegiatanGereja">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
						<option value="Bidang"><?php echo gettext("BidangID"); ?></option>
						<option value="Gereja"><?php echo gettext("GerejaID"); ?></option>
						<option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
						<option value="Tanggal"><?php echo gettext("Keterangan"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="KegiatanGereja">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php

        echo "</div>";
        echo "<BR>";

        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=KegiatanGereja&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=KegiatanGereja&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=KegiatanGereja&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=KegiatanGereja&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=KegiatanGereja&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="KegiatanGereja">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';

         } ?>
        <BR>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">
        <tr class="TableHeader">
				<td align="center"  colspan="3"  ><?php echo gettext("- "); ?></td>	
				<td align="center"  colspan="1"  ><?php echo gettext("Acuan Kegiatan"); ?></td>	
				<td align="center"  colspan="1"  ><?php echo gettext("Rencana Kegiatan"); ?></td>	
				<td align="center"  colspan="7" ><?php echo gettext("Pelaksanaan Kegiatan"); ?></td>
        <?php 
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
        </tr>
        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
				<td align="center"><?php echo gettext("Detail"); ?></td>
				<td align="center"><?php echo gettext("Cetak"); ?></td>
				<td align="center"></td>

				
				<td align="center"><?php echo gettext("Tempat"); ?><br>
									<?php echo gettext("Tanggal"); ?><br>
									<?php echo gettext("Pukul"); ?></td>
				
				<td align="center"><?php echo gettext("Bidang"); ?></td>
				<td align="center"><?php echo gettext("NamaKegiatan"); ?></td>
				
				<td align="center"><?php echo gettext("Tempat"); ?><br>
									<?php echo gettext("Tanggal"); ?><br>
									<?php echo gettext("Pukul"); ?></td>
				
				<td align="center"><?php echo gettext("PiC"); ?></td>
				<td align="center"><?php echo gettext("Jml Peserta"); ?></td>
				<td align="center"><?php echo gettext("Anggaran"); ?><br>
									<?php echo gettext("Realisasi Angg"); ?></td>
				
				<td><?php echo gettext("Hapus"); ?></td>				

        <?php 
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
        </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the Mail recordset
        while ($aRow = mysql_fetch_array($rsMail))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
                $KegiatanGerejaID  = "";
                $BidangID  = "";
                $Tanggal  = "";
                $Pukul  = "";
                $KodeTI  = "";
                $TempatKegiatan  = "";
                $NamaKegiatan  = "";
                $Keterangan  = "";
                $TanggalMulai  = "";
                $TanggalSelesai  = "";
                $JamMulai  = "";
                $JamSelesai  = "";
                $Hasil  = "";
                $Laporan  = "";
                $PiC  = "";
                $JmlPeserta  = ""; 
                $Anggaran  = "";
                $RealisasiAnggaran  = "";
  
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>"> 
                        <?php if ($_SESSION['bEditRecords']) { ?>
                        <td><a href="KegiatanGerejaEditor.php?KegiatanGerejaID=<?php echo $KegiatanGerejaID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
						<?php } ?>
						<td><a href="KegiatanGerejaView.php?KegiatanGerejaID=<?php echo $KegiatanGerejaID ?>"><?php echo "Detail" ?></a>&nbsp;</td>	
						<td><a href="PrintViewKegiatanGereja.php?AktaSidangID=<?php echo $AktaSidangID ?>&KegiatanGerejaID=<?php echo $KegiatanGerejaID."&GID=$refresh&"; ?>" class="help" title="Cetak Laporan Kegiatan berdasarkan <?php echo $NomorSurat ?>" target="_blank">
						<?php echo "Cetak" ?></a>&nbsp;</td>	
						
						<td></td>
						
						<td><?php echo $Tempat ?><br>
						<?php echo date2Ind($TglPlan,3) ?><br>
						<?php echo $Pukul ?>&nbsp;</td>
						
						<td>
						<a href="PrintViewKegiatanGereja.php?KegiatanGerejaID=<?php echo $KegiatanGerejaID ?>&BidangID=<?php echo $BidangID."&GID=$refresh&"; ?>" target="_blank">
						<?php 
						echo $NamaBidang 
						?></a></td>
						
						<td><?php 
						if ((($NamaGereja<1)&&($TempatKegiatan==''))&&($TanggalMulai=='0000-00-00')){
						echo "<font color=red >";	echo $NamaKegiatan ;echo "</font>";}else{
							if ($NotulaRapatID>0){
								echo "<a href=\"NotulaRapatView.php?NotulaRapatID=".$NotulaRapatID."\" target=\"_blank\">";
								echo $NamaKegiatan; 
							echo "</a>";}else{ echo $NamaKegiatan;  }
						} 
						?>&nbsp;</td>
						
						<td><?php echo $NamaGereja." ". $TempatKegiatan ?><br>
							<?php echo date2Ind($TanggalMulai,3) ?><br>
							<?php echo $JamMulai; ?>&nbsp;</td>

						
						<td><?php echo $PiC ?>&nbsp;</td>
						<td align="center"><?php echo $JmlPeserta ?>&nbsp;</td>
						<td align="center" ><?php echo $Anggaran ?><br>
							<?php echo $RealisasiAnggaran ?>&nbsp;</td>
									

					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp3.php?mode=KegiatanGereja&KegiatanGerejaID=<?php echo $KegiatanGerejaID ?>">X</a>&nbsp;</td>
						<?php } ?> 
							
						

                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
             //   $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}elseif ($iMode == 52)
{
//echo "listing Scroll Running Text ";
/*****************************
**  Daftar Scroll Running Text **
******************************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.* FROM ScrollRTWG a 
		";

        //Apply the sort based on what was passed in
        switch($sSort)
        {

                case "Keterangan":
                        $sSQL = $sSQL . " ORDER BY Keterangan ASC";
                        break;						
				default:
                        $sSQL = $sSQL . " ORDER BY  a.ScrollRTWGID DESC, a.Tanggal DESC ";
                        break;
        }

        $rsMailCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsMailCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsMail = RunQuery($sSQL);
		
        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"KegiatanGerejaEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Kegiatan Gereja") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp3.php" name="ScrollRT">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
						<option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
						<option value="Keterangan"><?php echo gettext("Keterangan"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="ScrollRT">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php

        echo "</div>";
        echo "<BR>";

        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectListApp3.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=ScrollRT&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=0&amp;mode=ScrollRT&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=ScrollRT&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=ScrollRT&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp3.php?Result_Set=$thisLinkResult&amp;mode=ScrollRT&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="ScrollRT">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';

         } ?>
        <BR>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">

        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
				<td align="center"><?php echo gettext("Detail"); ?></td>
									<td><?php echo gettext("Tanggal"); ?></td>
									<td><?php echo gettext("Scroll Text"); ?></td>
				
				<td><?php echo gettext("Hapus"); ?></td>				

        <?php 
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
        </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the Mail recordset
        while ($aRow = mysql_fetch_array($rsMail))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
                $ScrollRTWGID  = "";
                $Tanggal  = "";
                $Keterangan  = "";
  
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>"> 
                        <?php if ($_SESSION['bEditRecords']) { ?>
                        <td><a href="ScrollRTEditor.php?ScrollRTWGID=<?php echo $ScrollRTWGID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
						<?php } ?>
						<td><a href="ScrollRTView.php?ScrollRTWGID=<?php echo $ScrollRTWGID ?>"><?php echo "Detail" ?></a>&nbsp;</td>	
					
							<td><?php echo date2Ind($Tanggal,3) ?></td>
							<td><?php echo $Keterangan; ?>&nbsp;</td>

					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp3.php?mode=ScrollRT&ScrollRTWGID=<?php echo $ScrollRTWGID ?>">X</a>&nbsp;</td>
						<?php } ?> 
							
						

                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
             //   $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}
?>
