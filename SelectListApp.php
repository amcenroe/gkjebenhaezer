<?php
/*******************************************************************************
*
*  filename    : SelectListApp.php
*  2008 Erwin Pratama for GKJ Bekasi WIl Timur ( http://www.gkjbekasi-wiltimur.net )
*  2009 Erwin Pratama for GKJ Bekasi Timur ( http://www.gkjbekasitimur.org )
*  2010 Erwin Pratama for GKPB Bali ( http://www.balichurchsynod.org/ )
*  2013 Erwin Pratama for GKJ Tanjung Priok ( http://www.gkjtp.com )
*
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
	case ('groupassign'):
		$_SESSION['SelectListMode'] = $sMode;
		break;
	case ('family'):
		$_SESSION['SelectListMode'] = $sMode;
		break;
	case ('mail'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('mailout'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('sukep'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('beritaacara'):
		$_SESSION['SelectListMode'] = $sMode;
		break;		
	case ('klassurat'):
		$_SESSION['SelectListMode'] = $sMode;
		break;		
	case ('masterbidang'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('masterkomisi'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('masterposangg'):
		$_SESSION['SelectListMode'] = $sMode;
		break;		
	case ('masterpengpppg'):
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

if (isset($_GET["PersonColumn3"])){
	$_SESSION['sPersonColumn3'] = FilterInput($_GET["PersonColumn3"]);
}

if (isset($_GET["PersonColumn5"])){
	$_SESSION['sPersonColumn5'] = FilterInput($_GET["PersonColumn5"]);
}


// Set the page title

if ($sMode == 'mail')
{
    $sPageTitle = gettext("Daftar Surat Masuk");
	$logvar1 = "Listing";
	$logvar2 = "Incoming Mail";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 4;
}
elseif ($sMode == 'pak')
{
    $sPageTitle = gettext("Daftar Nilai Pendidikan Agama Kristen");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Nilai PAK";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 5;
}
elseif ($sMode == 'aset')
{
    $sPageTitle = gettext("Daftar Aset Gereja");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Aset Gereja";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 6;
}
elseif ($sMode == 'Persembahan')
{
    $sPageTitle = gettext("Daftar Persembahan Gereja");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Persembahan Gereja";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 7;
}
elseif ($sMode == 'PersembahanAnak')
{
    $sPageTitle = gettext("Daftar Persembahan " . $Kategori . " Gereja");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Persembahan " . $Kategori . " Gereja";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 8;
}
elseif ($sMode == 'Sidhi')
{
    $sPageTitle = gettext("Daftar Permohonan Pelayanan Sidhi ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Permohonan Pelayanan Sidhi ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 10;
}
elseif ($sMode == 'BaptisAnak')
{
    $sPageTitle = gettext("Daftar Permohonan Pelayanan Baptis Anak ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Permohonan Pelayanan Baptis Anak ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 11;
}
elseif ($sMode == 'SuratKeluar')
{
    $sPageTitle = gettext("Daftar Surat Permohonan Pelayan Firman ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Surat Permohonan Pelayan Firman ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 12;
}
elseif ($sMode == 'Liturgi')
{
    $sPageTitle = gettext("Daftar dan Jadwal Liturgi");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Liturgi ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 13;
}
elseif ($sMode == 'JadwalPelayanFirman')
{
    $sPageTitle = gettext("Jadwal Pelayan Firman");
	$logvar1 = "Listing";
	$logvar2 = "Jadwal Pelayan Firman";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 14;
}
elseif ($sMode == 'DaftarPendeta')
{
    $sPageTitle = gettext("Daftar Pendeta GKJ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Pendeta GKJ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 15;
}
elseif ($sMode == 'DaftarGereja')
{
    $sPageTitle = gettext("Daftar Gereja GKJ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Gereja GKJ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 16;
}
elseif ($sMode == 'DaftarKlasis')
{
    $sPageTitle = gettext("Daftar Klasis GKJ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Klasis GKJ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 18;
}
elseif ($sMode == 'BaptisDewasa')
{
    $sPageTitle = gettext("Daftar Permohonan Pelayanan Baptis Dewasa ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Permohonan Pelayanan Baptis Dewasa ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 19;
}
elseif ($sMode == 'Pindah')
{
    $sPageTitle = gettext("Daftar Permohonan Pelayanan Pindah Warga (Perorangan) ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Permohonan Pelayanan Pindah Warga (Perorangan) ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 20;
}
elseif ($sMode == 'PindahK')
{
    $sPageTitle = gettext("Daftar Permohonan Pelayanan Pindah Warga (Keluarga) ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Permohonan Pelayanan Pindah Warga (Keluarga) ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 21;
}
elseif ($sMode == 'Titip')
{
    $sPageTitle = gettext("Daftar Permohonan Penitipan Perawatan Rohani (Perorangan) ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Permohonan Penitipan Perawatan Rohani (Perorangan) ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 22;
}
elseif ($sMode == 'Perjamuan')
{
    $sPageTitle = gettext("Daftar Pelayanan Perjamuan Kudus untuk Tamu ");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Pelayanan Perjamuan Kudus untuk Tamu ";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 23;
}
elseif ($sMode == 'Nikah')
{
    $sPageTitle = gettext("Daftar Permohonan Pemberkatan Pernikahan");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Permohonan Pemberkatan Pernikahan";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 24;
}
elseif ($sMode == 'Meninggal')
{
    $sPageTitle = gettext("Daftar Permohonan Pelayanan Pemakaman");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Permohonan Pelayanan Pemakaman";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 25;
}
elseif ($sMode == 'mailout')
{
    $sPageTitle = gettext("Daftar Surat Keluar (Manual)");
	$logvar1 = "Listing";
	$logvar2 = "Manual Outgoing Mail";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 26;
}
elseif ($sMode == 'sukep')
{
    $sPageTitle = gettext("Daftar Surat Keputusan ");
	$logvar1 = "Listing";
	$logvar2 = "Surat Keputusan";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 27;
}
elseif ($sMode == 'klassurat')
{
    $sPageTitle = gettext("Daftar Klasifikasi Surat ");
	$logvar1 = "Listing";
	$logvar2 = "Klasifikasi Surat";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 28;
}elseif ($sMode == 'beritaacara')
{
    $sPageTitle = gettext("Daftar Surat Berita Acara ");
	$logvar1 = "Listing";
	$logvar2 = "Berita Acara";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 29;
}elseif ($sMode == 'masterbidang')
{
    $sPageTitle = gettext("Daftar Bidang");
	$logvar1 = "Listing";
	$logvar2 = "Klasifikasi Bidang";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 30;
}elseif ($sMode == 'masterkomisi')
{
    $sPageTitle = gettext("Daftar Komisi");
	$logvar1 = "Listing";
	$logvar2 = "Klasifikasi Komisi";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 31;
}elseif ($sMode == 'masterposangg')
{
    $sPageTitle = gettext("Daftar Pos Anggaran");
	$logvar1 = "Listing";
	$logvar2 = "Klasifikasi Pos Anggaran";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 32;
}elseif ($sMode == 'PenyegaranJanjiNikah')
{
    $sPageTitle = gettext("Daftar Permohonan Pembaruan Penyegaran Janji Perkawinan");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Permohonan Pembaruan Janji Perkawinan";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 33;
}elseif ($sMode == 'masterpengpppg')
{
    $sPageTitle = gettext("Daftar Master Pengeluaran PPPG");
	$logvar1 = "Listing";
	$logvar2 = "Master Pengeluaran PPPG";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 34;
}else
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

if ($iMode == 4)
{
//echo "listing mail";
/**********************
**  Mail Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.*, b.* FROM SuratMasuk a
		LEFT JOIN KlasifikasiSurat b ON a.ket3 = b.KlasID";

        //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Dari":
                        $sSQL = $sSQL . " ORDER BY Dari DESC";
                        break;
                case "Institusi":
                        $sSQL = $sSQL . " ORDER BY Institusi, Dari";
                        break;
                default:
                        $sSQL = $sSQL . " ORDER BY MailID Desc";
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"MailEditor.php?GID=$refresh\">" . gettext("Tambahkan Data Surat Masuk Baru") . "</a></div><BR>"; }
        
		echo "<div align=\"center\"><a href=\"PrintViewSuratMasuk.php?GID=$refresh\" target=_blank >" . gettext("Cetak Daftar Surat Masuk") . "</a></div><BR>";
		echo "<div align=\"center\"><a href=\"PrintViewSuratMasuk.php?GID=$refresh&Lap=MPL\"  target=_blank >" . gettext("Cetak Daftar Surat Masuk untuk Sidang MPL") . "</a></div><BR>";
		?>
		
        <form method="get" action="SelectList.php" name="Mail">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Dari"><?php echo gettext("Pengirim"); ?></option>
                        <option value="Institusi"><?php echo gettext("Institusi"); ?></option>
						<option value="Hal"><?php echo gettext("Hal"); ?></option>
						<option value="TanggalSurat"><?php echo gettext("Tanggal Surat"); ?></option>
						<option value="Ket1"><?php echo gettext("Tanggal Terima"); ?></option>
						<option value="FollowUp"><?php echo gettext("FollowUp"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="mail">
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
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=mail&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=mail&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=mail&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=mail&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=mail&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="mail">
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
				<td><?php echo gettext("MailID"); ?></td>
				<td><?php echo gettext("TglSurat <br> TglTerima"); ?></td>

				<td><?php echo gettext("NomorSurat"); ?></td>
                <td><?php echo gettext("Pengirim"); ?></td>
                <td><?php echo gettext("Institusi"); ?></td>
                <td><?php echo gettext("Tujuan"); ?></td>
                <td><?php echo gettext("Hal"); ?></td>
                <td><?php echo gettext("Urgensi"); ?><br><?php echo gettext("TglNotifikasi"); ?></td>
				<td><?php echo gettext("FollowUp"); ?><br><?php echo gettext("Status"); ?></td>
				
				<td><?php echo gettext("Respon?<br>TglTarget"); ?></td>
				<td><?php echo gettext("Tgl FollowUp"); ?></td>
				
				<td><?php echo gettext("Kategori"); ?></td>
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
                $Tanggal = "";
                $Ket1 = "";
                $NomorSurat = "";
                $Dari = "";
                $Institusi = "";
                $Kepada = "";
                $Hal = "";
                $Urgensi = "";
				$FollowUp = "";
				$Ket3 = "";

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="MailEditor.php?MailID=<?php echo $MailID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="MailView.php?MailID=<?php echo $MailID."&GID=$refresh" ?>"><?php echo "View" ?></a>&nbsp;</td>
						<td><?php echo $Tanggal ?><br><font color="blue"><?php echo $Ket1 ?></font></td>
						
						
						
						<td><?php 
						    if (($Ket3 == '31' )||($Ket3 == '33' )){
								// Link to Person Info to track previous Church
								$ssSQL = "SELECT * FROM `person_per`
										WHERE `per_LastName` LIKE  '" . $NomorSurat . "' 
										ORDER BY per_fmr_ID LIMIT 1 " ;				
								$perintah = mysql_query($ssSQL);
								if (mysql_num_rows($perintah) )
									{
										while ($hasilGD=mysql_fetch_array($perintah))
											{
												extract($hasilGD);
												echo "<a href=\"PersonView.php?PersonID=" . $hasilGD[per_ID] . "\">" . $NomorSurat . "</a>"  ;								
											}
									}		
									else
									{
									echo "<a><img border=0 src=\"Images/exclaim.gif\"  width=15 height=15  > </a> ";
									echo "<font color=red >";	echo $NomorSurat ;echo "</font>";
									}
								}	
								else
								{ 
								
								echo $NomorSurat ; 
								}

						?>&nbsp;</td>

						<td><?php echo $Dari ?>&nbsp;</td>
						<td><?php echo $Institusi ?>&nbsp;</td>
						<td><?php echo $Kepada ?>&nbsp;</td>
						<td><?php echo $Hal ?>&nbsp;</td>
						<td><?php 
							switch ($Urgensi)
							{
							case 1:
								echo gettext("<font color=red ><b>Sangat Segera</b></font>");
								break;
							case 2:
								echo gettext("<b>Segera</b>");
								break;
							case 3:
								echo gettext("Biasa");
								break;
							}


$sSQL2 = "SELECT date as TglKirim  FROM `logger` WHERE `var1` LIKE '%Email%' AND per_ID =".$MailID." ORDER BY `logger`.`count`  DESC
		 LIMIT 1";

// echo $sSQL2; 

$rsEmail2 = RunQuery($sSQL2);

$num_rows = mysql_num_rows($rsEmail2);
if ($num_rows > 0 ) {
extract(mysql_fetch_array($rsEmail2));
echo "<br>TglNotifikasi:<br>";
echo "<font color=blue >";
echo date('d-M-Y H:i:s', strtotime ($TglKirim));
echo "</font>";}
//echo $TglKirim;
							
						?>&nbsp;</td>
						<td><?php echo $FollowUp ?><br><font color="green"><?php echo $Status ?></font></td>
						<td><?php 
							switch ($Respon)
							{
							case 1:
								echo gettext("Tidak");
								break;
							case 2:
								echo gettext("<b>Ya</b><br>$TglRespon");
								break;
							}


$exp_date = $TglRespon; 
$todays_date = date("Y-m-d"); 

$today = strtotime($todays_date); 
$expiration_date = strtotime($exp_date); 

if ($expiration_date > $today) 
{ $valid = "yes"; 
	echo "<br><blink><font color=red >Belum Direspon!</font></blink><br>";
} else 
{ $valid = "no"; }


							
						?>					
</td>
						<td><?php 
						if ( $TglDibalas == "0000-00-00" ){
						
$exp_date = $TglRespon; 
$todays_date = date("Y-m-d"); 

$today = strtotime($todays_date); 
$expiration_date = strtotime($exp_date); 

if ($expiration_date < $today AND $Respon == 2) 
{ $valid = "yes"; 
	echo "<br><blink><font color=red >Belum DiBalas!</font></blink><br>";
} else 
{ $valid = "no"; }
						
}else{echo $TglDibalas;} ?></td>
						<td><?php echo $Deskripsi;

						?>&nbsp;</td>
						

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



elseif ($iMode == 5)

{
// echo "listing pak";
/**********************
**  PAK Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT * FROM pakgkjbekti a
		LEFT JOIN person_per b ON a.per_ID =  b.per_ID  
		LEFT JOIN paktutor d ON a.TutorID=d.TutorID ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Kelas":
                        $sSQL = $sSQL . " ORDER BY Kelas DESC";
                        break;
                case "Semester":
                        $sSQL = $sSQL . " ORDER BY Semester, Kelas DESC";
                        break;
                default:
                        $sSQL = $sSQL . " ORDER BY TahunAjaran,Semester, Kelas Desc";
                        break;
        }

        $rsPakCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsPakCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PakEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data PAK") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectList.php" name="Pak">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Kelas"><?php echo gettext("Kelas"); ?></option>
                        <option value="Semester"><?php echo gettext("Semester"); ?></option>
						<option value="TahunAjaran"><?php echo gettext("TahunAjaran"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="pak">
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
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=pak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=0&amp;mode=pak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=pak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=pak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=pak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="pak">
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
		Catatan : Bobot Nilai : Nilai A	= nilai kerajinan , bobot = 30% | 	
		Nilai B	= nilai tugas , bobot = 30% |	
		Nilai C	= nilai test , bobot = 40%	

        <BR>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">

        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
				<td><?php echo gettext("PakID"); ?></td>
				<td><?php echo gettext("Cetak"); ?></td>
			
				<td><?php echo gettext("Nama"); ?></td>
				<td><?php echo gettext("AlamatSekolah"); ?></td>
                <td align="center"><?php echo gettext("TahunAjaran"); ?></td>
				<td align="center"><?php echo gettext("Semester"); ?></td>
                <td align="center"><?php echo gettext("Kelas"); ?></td>
                <td><?php echo gettext("TutorID"); ?></td>
				<td><?php echo gettext("TutorName"); ?></td>
                <td align="center"><?php echo gettext("Nilai1<br>(A)"); ?></td>
				<td align="center"><?php echo gettext("Nilai2<br>(B)"); ?></td>
				<td align="center"><?php echo gettext("Nilai3<br>(C)"); ?></td>
				<td align="center"><?php echo gettext("Nilai<br>Final"); ?></td>
				
				<td><?php echo gettext("Keterangan"); ?></td>
				<td align="center"><?php echo gettext("Tgl<br>Input"); ?></td>
				<td align="center"><?php echo gettext("Tgl<br>Edit"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$PakID = "";
				$per_ID = "";
				$Nama = "";
				$AlamatSekolah = "";
                $Kelas = "";
                $Semester = "";
                $TahunAjaran = "";
                $TutorID = "";
				$TutorName = "";
                $Nilai1 = "";
				$Nilai2 = "";
				$Nilai3 = "";
				$Nilai4 = "";
				$Keterangan = "";
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="PakEditor.php?PakID=<?php echo $PakID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="PakView.php?PakID=<?php echo $PakID ?>"><?php echo "View" ?></a>&nbsp;</td>
						<td><a target="_blank" href="PrintViewPak.php?PakID=<?php echo $PakID."&GID=$refresh&"; ?>"><?php echo "Cetak" ?></a>&nbsp;</td>

						<td><a href="PersonView.php?PersonID=<?php echo $per_ID."&GID=$refresh&"; ?>"><?php echo $per_FirstName ?></a><?php echo $Nama ?>&nbsp;</td>
						<td><?php echo $AlamatSekolah ?>&nbsp;</td>
						<td><?php echo "20" . $TahunAjaran . "/20" . ($TahunAjaran+1) . "" ?>&nbsp;</td>
						<td align="center"><?php echo $Semester ?>&nbsp;</td>
						<td align="center"><?php echo $Kelas ?>&nbsp;</td>
						<td><?php echo $TutorID ?>&nbsp;</td>
						<td><?php echo $TutorName ?>&nbsp;</td>
						<td align="center"><?php echo $Nilai1 ?>&nbsp;</td>
						<td align="center"><?php echo $Nilai2 ?>&nbsp;</td>
						<td align="center"><?php echo $Nilai3 ?>&nbsp;</td>

						<?php $NilaiAkhir=($Nilai1*0.3)+($Nilai2*0.3)+($Nilai3*0.4); ?>
						<td align="center" ><b><?php echo $NilaiAkhir; ?>&nbsp;</b></td>	

						<td><?php if ($per_ID == 0){echo "Non Warga";} else {echo $Keterangan;} ?>&nbsp;</td>
						<td align="center"><?php echo date2Ind($DateEntered,2) ?>&nbsp;</td>
						<td align="center"><?php echo date2Ind($DatelastEdited,2) ?>&nbsp;</td>

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


elseif ($iMode == 6)

{
// echo "listing aset";
/**********************
**  Aset Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT * FROM asetgkjbekti a
		    LEFT JOIN LokasiTI b ON a.Location=b.KodeTI 
			LEFT JOIN asetklasifikasi c ON a.AssetClass=c.ClassID 
			LEFT JOIN asetruangan d ON a.StorageCode=d.StorageCode
			LEFT JOIN asetstatus e ON a.Status=e.StatusCode 
		 ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Location":
                        $sSQL = $sSQL . " ORDER BY Location, AssetClass DESC";
                        break;
                case "Tahun":
                        $sSQL = $sSQL . " ORDER BY Tahun, AssettClass DESC";
                        break;
                default:
                        $sSQL = $sSQL . " ORDER BY AssetClass";
                        break;
        }

        $rsAsetCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsAsetCount);

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

	//	echo $sSQL;
        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"AsetEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Aset") . "</a></div>
		<div align=\"center\"><a href=\"PrintViewLapAset.php?ID=$refresh&\" target=\"_blank\" >" . gettext("Cetak Laporan Data Aset") . "</a></div>
		<BR>"; }
        ?>
		
        <form method="get" action="SelectList.php" name="aset">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="AssetClass"><?php echo gettext("Klasifikasi"); ?></option>
                        <option value="Tahun"><?php echo gettext("Tahun Pengadaan"); ?></option>
						<option value="Location"><?php echo gettext("Lokasi"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="aset">
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
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=aset&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=0&amp;mode=aset&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=aset&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=aset&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=aset&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="aset">
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
				<td ALIGN=CENTER  ><?php echo gettext("View"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Cetak"); ?></td>
				<td ALIGN=CENTER ><?php echo gettext("Klasifikasi"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Tahun"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Merk"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Type"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Spesifikasi"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Jumlah"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Unit"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Nilai"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Status"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Lokasi TI"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Tempat"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Rak"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Bin"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Keterangan"); ?></td> 				
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
            	$AssetID = ""; 
            	$AssetClass = ""; 
            	$Tahun = ""; 
            	$Merk = ""; 
            	$Type = ""; 
            	$Spesification = ""; 
            	$Quantity = ""; 
            	$UnitOfMasure = "";
            	$Value = ""; 
            	$Status = ""; 
            	$Description = ""; 
            	$Location = ""; 
            	$StorageCode = ""; 
            	$Rack = ""; 
            	$Bin = ""; 	

                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="AsetEditor.php?AssetID=<?php echo $AssetID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="AsetView.php?AssetID=<?php echo $AssetID ?>"><?php echo "View" ?></a>&nbsp;</td>
						<td><a target="_blank" href="PrintViewAset.php?AssetID=<?php echo $AssetID."&GID=$refresh&"; ?>"><?php echo "Cetak" ?></a>&nbsp;</td>


						<td><?php echo $majorclass . "-" . $minorclass ?>&nbsp;</td> 
						<td><?php echo $Tahun ?>&nbsp;</td> 
						<td><?php echo $Merk ?>&nbsp;</td> 
						<td><?php echo $Type ?>&nbsp;</td> 
						<td><?php echo $Spesification ?>&nbsp;</td> 
						<td  ALIGN=CENTER ><?php echo $Quantity ?>&nbsp;</td> 
						<td><?php echo $UnitOfMasure ?>&nbsp;</td> 
						<td><?php echo currency('Rp. ',$Value,'.',',00') ?>&nbsp;</td> 
						<td><?php echo $StatusName ?>&nbsp;</td> 
						<td><?php echo $NamaTI ?>&nbsp;</td> 
						<td><?php echo $StorageDesc ?>&nbsp;</td> 
						<td><?php echo $Rack ?>&nbsp;</td> 
						<td><?php echo $Bin ?>&nbsp;</td> 
						<td><?php echo $Description ?>&nbsp;</td> 
				

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

elseif ($iMode == 7)

{
// echo "listing persembahan";
/**********************
**  Persembahan Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT * FROM Persembahangkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "TempatIbadah":
                        $sSQL = $sSQL . " ORDER BY KodeTI, Tanggal DESC";
                        break;
                default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, a.KodeTI, Pukul";
                        break;
        }

        $rsPersembahanCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsPersembahanCount);

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

	//	echo $sSQL;
        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PersembahanEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Persembahan") . "</a></div>
		<div align=\"center\"><a href=\"PrintViewLapPersembahan.php?GID=$refresh&\" target=\"_blank\" >" . gettext("Cetak Laporan Data Persembahan") . "</a></div>
		<BR>"; }
        ?>
		
        <form method="get" action="SelectList.php" name="Persembahan">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="TempatIbadah"><?php echo gettext("TempatIbadah"); ?></option>
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Persembahan">
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
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=Persembahan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=0&amp;mode=Persembahan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=Persembahan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=Persembahan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=Persembahan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="Persembahan">
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
				<td ALIGN=CENTER  ><?php echo gettext("View"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Cetak"); ?></td>
				<td ALIGN=CENTER ><?php echo gettext("Tanggal"); ?></td> 				
				<td ALIGN=CENTER ><?php echo gettext("Tempat Ibadah"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Pukul"); ?></td> 	
				<td ALIGN=CENTER ><?php echo gettext("Nas"); ?></td> 					
				<td ALIGN=CENTER ><?php echo gettext("Pengkotbah"); ?></td>				
				<td ALIGN=CENTER ><?php echo gettext("Total Persembahan"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Total Jemaat"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Total Majelis"); ?></td> 					
			
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the persembahan recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$sPersembahan_ID = "";
				$sTanggal = "";
				$sPukul = "";
				$sKodeTI = "";


                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="PersembahanEditor.php?Persembahan_ID=<?php echo $Persembahan_ID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="PersembahanView.php?Persembahan_ID=<?php echo $Persembahan_ID."&GID=$refresh&"; ?>"><?php echo "View" ?></a>&nbsp;</td>
						<td><a target="_blank" href="PrintViewPersembahan.php?Persembahan_ID=<?php echo $Persembahan_ID."&GID=$refresh&"; ?>"><?php echo "Cetak" ?></a>&nbsp;</td>

						<td><a href="PrintViewInfoPersembahan.php?TGL=<?php echo $Tanggal."&GID=$refresh&"; ?>"><?php echo $Tanggal; ?></a>&nbsp;</td> 
						<td><?php echo $NamaTI ?>&nbsp;</td> 
						<td><?php echo $Pukul ?>&nbsp;</td> 
						<td><?php echo $Nas ?>&nbsp;</td>
						<td><?php echo $Pengkotbah ?>&nbsp;</td>
						<td ALIGN=RIGHT><?php 
						$TotalPersembahan = $KebDewasa + $KebAnak + $KebAnakJTMY + $KebRemaja + $Syukur + $Bulanan + $Khusus + $Marapas +$Marapen + $Unduh +$Pink + $LainLain;
							echo currency('Rp. ',$TotalPersembahan,'.',',00'); 
						 ?>&nbsp;</td>
						<td ALIGN=RIGHT><?php 
						$TotalJemaat = $Pria + $Wanita ;
						echo $TotalJemaat ?>&nbsp;</td>
						<td ALIGN=RIGHT><?php 
						if (trim($Majelis1)) { $Mjls1=1 ;}else{ $Mjls1=0 ; }
						if (trim($Majelis2)) { $Mjls2=1 ;}else{ $Mjls2=0 ; }
						if (trim($Majelis3)) { $Mjls3=1 ;}else{ $Mjls3=0 ; }
						if (trim($Majelis4)) { $Mjls4=1 ;}else{ $Mjls4=0 ; }
						if (trim($Majelis5)) { $Mjls5=1 ;}else{ $Mjls5=0 ; }
						if (trim($Majelis6)) { $Mjls6=1 ;}else{ $Mjls6=0 ; }
						if (trim($Majelis7)) { $Mjls7=1 ;}else{ $Mjls7=0 ; }
						if (trim($Majelis8)) { $Mjls8=1 ;}else{ $Mjls8=0 ; }
						if (trim($Majelis9)) { $Mjls9=1 ;}else{ $Mjls9=0 ; }
						if (trim($Majelis10)) { $Mjls10=1 ;}else{ $Mjls10=0 ; }
						if (trim($Majelis11)) { $Mjls11=1 ;}else{ $Mjls11=0 ; }
						if (trim($Majelis12)) { $Mjls12=1 ;}else{ $Mjls12=0 ; }
						if (trim($Majelis13)) { $Mjls13=1 ;}else{ $Mjls13=0 ; }
						if (trim($Majelis14)) { $Mjls14=1 ;}else{ $Mjls14=0 ; }
						$TotMjls = $Mjls1 + $Mjls2 + $Mjls3 + $Mjls4 + $Mjls5 + $Mjls6 + $Mjls7 
						+ $Mjls8 + $Mjls9 + $Mjls10 + $Mjls11 + $Mjls12 + $Mjls13 + $Mjls14 ;
						
						//
						//echo $TotalMajelis 
						echo $TotMjls;
						?>&nbsp;</td>
			

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


elseif ($iMode == 8)

{
// echo "listing persembahan Anak";
/**********************
**  Persembahan Anak Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
$Kategori = $_GET["Kategori"];		
        $sSQL = "SELECT * FROM Persembahan" . $Kategori . "gkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI ";
			
		//echo $sSQL;	

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "TempatIbadah":
                        $sSQL = $sSQL . " ORDER BY KodeTI, Tanggal DESC";
                        break;
                default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, a.KodeTI, Pukul";
                        break;
        }

        $rsPersembahanCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsPersembahanCount);

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

	//	echo $sSQL;
        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PersembahanAnakEditor.php?Kategori=" . $Kategori . "&GID=$refresh&\">" . gettext("Tambahkan Data Persembahan") . "</a></div>
		<div align=\"center\"><a href=\"PrintViewLapPersembahanAnak.php?Kategori=" . $Kategori . "&GID=$refresh&\" target=\"_blank\" >" . gettext("Cetak Laporan Data Persembahan") . "</a></div>
		<BR>"; }
        ?>
		
        <form method="get" action="SelectList.php" name="PersembahanAnak">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="TempatIbadah"><?php echo gettext("TempatIbadah"); ?></option>
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="PersembahanAnak">
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
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=PersembahanAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=0&amp;mode=PersembahanAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=PersembahanAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=PersembahanAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=PersembahanAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="PersembahanAnak">
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
				<td ALIGN=CENTER  ><?php echo gettext("View"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Cetak"); ?></td>
				<td ALIGN=CENTER ><?php echo gettext("Tanggal"); ?></td> 				
				<td ALIGN=CENTER ><?php echo gettext("Tempat Ibadah"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Pukul"); ?></td> 	
				<td ALIGN=CENTER ><?php echo gettext("Nas"); ?></td> 					
				<td ALIGN=CENTER ><?php echo gettext("Pengkotbah"); ?></td>				
				<td ALIGN=CENTER ><?php echo gettext("Total Persembahan"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Total Jemaat"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Total Majelis"); ?></td> 					
			
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the persembahan recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$sPersembahan_ID = "";
				$sTanggal = "";
				$sPukul = "";
				$sKodeTI = "";


                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="PersembahanAnakEditor.php?Persembahan_ID=<?php echo $Persembahan_ID . "&Kategori=$Kategori&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="PersembahanAnakView.php?Persembahan_ID=<?php echo $Persembahan_ID ?>&Kategori=<?php echo $Kategori."&GID=$refresh&"; ?> "><?php echo "View" ?></a>&nbsp;</td>
						<td><a target="_blank" href="PrintViewPersembahanAnak.php?Persembahan_ID=<?php echo $Persembahan_ID ?>&Kategori=<?php echo $Kategori."&GID=$refresh&"; ?> "><?php echo "Cetak" ?></a>&nbsp;</td>

						<td><?php echo $Tanggal ?>&nbsp;</td> 
						<td><?php echo $NamaTI ?>&nbsp;</td> 
						<td><?php echo $Pukul ?>&nbsp;</td> 
						<td><?php echo $Nas ?>&nbsp;</td>
						<td><?php echo $Pengkotbah ?>&nbsp;</td>
						<td ALIGN=RIGHT><?php echo currency('Rp. ',$Persembahan,'.',',00');  ?>&nbsp;</td>
						<td ALIGN=RIGHT><?php $TotalJemaat = $Pria + $Wanita ;	echo $TotalJemaat ?>&nbsp;</td>
						<td ALIGN=RIGHT><?php 
						if (trim($Majelis1)) { $Mjls1=1 ;}else{ $Mjls1=0 ; }
						if (trim($Majelis2)) { $Mjls2=1 ;}else{ $Mjls2=0 ; }
						$TotMjls = $Mjls1 + $Mjls2 ;
						
						//
						//echo $TotalMajelis 
						echo $TotMjls;
						?>&nbsp;</td>
			

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

elseif ($iMode == 10)
{

// echo "listing Sidhi";
/**********************
**  Sidhi Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "select a.* , z.SidhiID,
		a.per_id, 
		a.per_firstname as NamaPemohonSidhi , 
		a.per_WorkEmail as TempatLahir,
		CONCAT(a.per_BirthYear,'-',a.per_BirthMonth,'-',a.per_BirthDay) as TanggalLahir,
		a.per_Workemail as TempatLahir,
		c.per_firstname as NamaAyah, 
		d.per_firstname as NamaIbu,	
		z.KetuaMajelis as KetuaMajelis,
		z.SekretarisMajelis as SekretarisMajelis,
		z.NamaLengkap as NamaPemohonSidhiNW, 
		z.TempatLahir as TempatLahirNW,
		z.TanggalLahir 	as TanggalLahirNW,
		z.NamaAyah 	as NamaAyahNW,
		z.NamaIbu 	as NamaIbuNW,
		z.TanggalBaptis as TanggalBaptisNW,
		z.TempatBaptis 	as TempatBaptisNW,
		z.PendetaBaptis as PendetaBaptisNW,
		z.NoSuratTitipan as NoSuratTitipanNW, 
		z.PendetaSidhi as PendetaSidhiNW,
		z.TanggalRencanaSidhi as TanggalSidhiNW,
		z.TempatSidhi as TempatLayananSidhi,
		
		z.WaktuSidhi as WaktuSidhi,	
		f.NamaGereja as TempatSidhiNW,
		
		x.c1 as TanggalBaptis,
		x.c26 as TempatBaptis,
		x.c37 as DiBaptisOleh,
		x.c2 as TanggalSidhi,
		x.c27 as TempatSidhi,
		x.c38 as DiSidhiOleh,		
	
		a.per_gender as JK , a.per_fam_id
	
	
from sidhigkjbekti z 
left join person_per a ON z.per_id = a.per_id 
left join person_custom x ON a.per_id = x.per_id 
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
left join DaftarPendeta e ON z.PendetaSidhi = e.PendetaID
left join DaftarGerejaGKJ f ON z.TempatSidhi = f.GerejaID
 ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Nama":
                        $sSQL = $sSQL . " ORDER BY NamaPemohonSidhi DESC";
                        break;
                case "JenisKelamin":
                        $sSQL = $sSQL . " ORDER BY JK, NamaPemohonSidhi DESC";	
                        break;
				case "Kelompok":
                        $sSQL = $sSQL . " ORDER BY JK, a.per_Workphone, NamaPemohonSidhi DESC";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY z.TanggalRencanaSidhi Desc";
                        break;
        }

        $rsSidhiCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsSidhiCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"SidhiEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Permohonan Sidhi") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Sidhi">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Nama"><?php echo gettext("Nama"); ?></option>
                        <option value="JenisKelamin"><?php echo gettext("JenisKelamin"); ?></option>
						<option value="Kelompok"><?php echo gettext("Kelompok"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Sidhi">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Sidhi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=Sidhi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Sidhi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Sidhi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Sidhi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="Sidhi">
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
	
				<td colspan=3 align="center"><?php echo gettext("Cetak"); ?></td>
			
				<td><?php echo gettext("Nama"); ?></td>
				<td><?php echo gettext("Tempat/Tanggal lahir"); ?></td>
                <td><?php echo gettext("Nama Orang Tua"); ?></td>
				<td><?php echo gettext("Tempat/Tanggal Baptis"); ?></td>
                <td><?php echo gettext("Dibaptis Oleh"); ?></td>
                <td><?php echo gettext("Tempat/Tanggal Sidhi"); ?></td>
				<td><?php echo gettext("Waktu Sidhi"); ?></td>
				<td><?php echo gettext("Di layani Sidhi Oleh"); ?></td>
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$SidhiID = "";
				$per_ID = "";
				$NamaPemohonSidhi = "";
				$TempatLahir = "";
				$TanggalLahir = "";
				$TempatLahir = "";
				$NamaAyah = ""; 
				$NamaIbu = "";	
				$KetuaMajelis = "";	
				$SekretarisMajelis = "";	
				$NamaPemohonSidhiNW = ""; 
				$TempatLahirNW = "";
				$TanggalLahirNW = "";
				$NamaAyahNW = "";
				$NamaIbuNW = "";
				$TanggalBaptisNW = "";
				$TempatBaptisNW = "";
				$PendetaBaptisNW = "";
				$NoSuratTitipanNW = ""; 
				$TanggalBaptis = "";
				$TempatBaptis = "";
				$DiBaptisOleh = "";
				$TanggalSidhi = "";
				$WaktuSidhi = "";
				$TempatSidhi = "";
				$DiSidhiOleh = "";					
				$TanggalSidhiNW = "";
				$TempatSidhiNW = "";
				$PendetaSidhiNW = "";	 

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="SidhiEditor.php?SidhiID=<?php echo $SidhiID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
                        
			
				<?php if ($TempatLayananSidhi == 1){         
					echo "<td><a target=\"_blank\" href=\"PrintViewSidhi.php?SidhiID=".$SidhiID."&GID=$refresh&\">Cetak<br>Sertifikat</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewSidhiBeritaAcara.php?SidhiID=".$SidhiID."&TanggalSidhi=".$TanggalSidhi."&WaktuSidhi=".$WaktuSidhi."&GID=$refresh&\">Cetak<br>BeritaAcara</a>&nbsp;</td>";
					
					if ($per_ID == 0){
					 echo "<td><a target=\"_blank\" href=\"PrintViewSidhiSK.php?SidhiID=".$SidhiID."&GID=$refresh&\">Cetak<br>S.Keterangan</a>&nbsp;</td>";}
					 else {echo "<td></td>";}
					
					}else{
					echo "<td></td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewSidhiTitipan.php?SidhiID=".$SidhiID."&GID=$refresh&\">Cetak<br>Titipan</a>&nbsp;</td>";
					echo "<td></td>";
					}
					?>


					<td><a href="PersonView.php?PersonID=<?php echo $per_ID ?>"><?php echo $NamaPemohonSidhi ?></a><?php echo $NamaPemohonSidhiNW ?>&nbsp;</td>
					<td><?php if (strlen($per_ID) > 0){echo $TempatLahir . ",<br> " . date2Ind($TanggalLahir,3) ;}else{echo $TempatLahirNW . ",<br> " . date2Ind($TanggalLahirNW,3); } ?>&nbsp;</td>
					<td>(Ayah) <?php if (strlen($per_ID) > 0){echo $NamaAyah;}else{echo $NamaAyahNW;} ?> <br>
						(Ibu) <?php if (strlen($per_ID) > 0){echo $NamaIbu;}else{echo $NamaIbuNW;} ?>&nbsp;</td>
						
					<td><?php if (strlen($per_ID) > 0){echo $TempatBaptis;}else{echo $TempatBaptisNW;} ?><br>	
					<?php if (strlen($per_ID) > 0){echo date2Ind($TanggalBaptis,3);}else{echo date2Ind($TanggalBaptisNW,3);} ?>&nbsp;</td>
					
					<td><?php if (strlen($per_ID) > 0){echo $DiBaptisOleh;}else{echo $PendetaBaptisNW;}  ?>&nbsp;</td>
					
					<td><?php if (strlen($per_ID) > 0){echo $TempatSidhi;}else{echo $TempatSidhiNW;}  ?><br>
					<?php if (strlen($per_ID) > 0){echo date2Ind($TanggalSidhi,3);}else{echo date2Ind($TanggalSidhiNW,3);}  ?>&nbsp;</td>
					
					<td><?php 
					if ($WaktuSidhi == "11")
					{echo "06.00 WIB <br>Cut Meutia";}
					elseif ($WaktuSidhi == "12"){echo "09.00 WIB <br>Cut Meutia";}
					elseif ($WaktuSidhi == "21"){echo "07.00 WIB <br>Cikarang";}
					elseif ($WaktuSidhi == "31"){echo "07.30 WIB <br>Karawang";}
					elseif ($WaktuSidhi == "41"){echo "17.00 WIB <br>Tambun";}
					else{echo "";} ?>&nbsp;</td>
					<td><?php if (strlen($per_ID) > 0){echo $DiSidhiOleh;}else{echo $PendetaSidhiNW;}  ?>&nbsp;</td>
						<?php if ($_SESSION['bDeleteRecords']) { ?>
                        <td><a href="SelectDeleteApp.php?mode=Sidhi&AppPersonID=<?php echo $per_ID ?>&SidhiID=<?php echo $SidhiID ?>">X</a>&nbsp;</td>
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

elseif ($iMode == 11)
{

// echo "listing Baptis Anak";
/**********************
**  BaptisAnak Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
		$iTempatBaptis = FilterInput($_GET["TempatBaptis"],'int');
		if ($iTempatBaptis>1){$TITIP="AND z.TempatBaptis > 1";}else {$TITIP=""; };
		
        $sSQL = "select a.* , z.BaptisID,
		a.per_id, 
		a.per_firstname as NamaPemohonBaptis , 
		a.per_WorkEmail as TempatLahir,
		CONCAT(a.per_BirthYear,'-',a.per_BirthMonth,'-',a.per_BirthDay) as TanggalLahir,
		a.per_Workemail as TempatLahir,
		c.per_firstname as NamaAyah, 
		d.per_firstname as NamaIbu,	
		z.KetuaMajelis as KetuaMajelis,
		z.SekretarisMajelis as SekretarisMajelis,
		z.NamaLengkap as NamaPemohonBaptisNW, 
		z.TempatLahir as TempatLahirNW,
		z.TanggalLahir 	as TanggalLahirNW,
		z.NamaAyah 	as NamaAyahNW,
		z.NamaIbu 	as NamaIbuNW,
		z.TanggalBaptis as TanggalBaptisNW,

		
		z.PendetaBaptis as PendetaBaptisNW,
		z.NoSuratTitipan as NoSuratTitipanNW, 
		
		z.TempatBaptis as TempatLayananBaptis,
		z.WaktuBaptis as WaktuBaptis,
		f.NamaGereja as NamaGereja,
		
		x.c1 as TanggalBaptis,
		x.c26 as TempatBaptis,
		x.c37 as DiBaptisOleh,

	
		a.per_gender as JK , a.per_fam_id
	
	
from baptisanakgkjbekti z 
left join person_per a ON z.per_id = a.per_id 
left join person_custom x ON a.per_id = x.per_id 
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
left join DaftarPendeta e ON z.PendetaBaptis = e.PendetaID
left join DaftarGerejaGKJ f ON z.TempatBaptis = f.GerejaID



 ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Nama":
                        $sSQL = $sSQL . " ORDER BY NamaPemohonBaptis DESC";
                        break;
                case "JenisKelamin":
                        $sSQL = $sSQL . " ORDER BY JK, NamaPemohonBaptis DESC";	
                        break;
				case "Kelompok":
                        $sSQL = $sSQL . " ORDER BY JK, a.per_Workphone, NamaPemohonBaptis DESC";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY z.BaptisID Desc";
                        break;
        }

        $rsSidhiCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsSidhiCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"BaptisAnakEditor.php\">" . gettext("Tambahkan Data Permohonan Baptis") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="BaptisAnak">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Nama"><?php echo gettext("Nama"); ?></option>
                        <option value="JenisKelamin"><?php echo gettext("JenisKelamin"); ?></option>
						<option value="Kelompok"><?php echo gettext("Kelompok"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Baptis">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=BaptisAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=BaptisAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=BaptisAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=BaptisAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=BaptisAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="BaptisAnak">
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
	
				<td colspan="3" align="center"><?php echo gettext("Cetak"); ?></td>
			
				<td><?php echo gettext("Nama"); ?></td>
				<td><?php echo gettext("Tempat/Tanggal lahir"); ?></td>
                <td><?php echo gettext("Nama Orang Tua"); ?></td>	
                <td><?php echo gettext("Tanggal Baptis"); ?></td>
				<td><?php echo gettext("Waktu Baptis"); ?></td>
                <td><?php echo gettext("Tempat Baptis"); ?></td>
				<td><?php echo gettext("Dibaptis Oleh"); ?></td>
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$SidhiID = "";
				$per_ID = "";
				$NamaPemohonSidhi = "";
				$TempatLahir = "";
				$TanggalLahir = "";
				$TempatLahir = "";
				$NamaAyah = ""; 
				$NamaIbu = "";	
				$KetuaMajelis = "";	
				$SekretarisMajelis = "";	
				$NamaPemohonSidhiNW = ""; 
				$TempatLahirNW = "";
				$TanggalLahirNW = "";
				$NamaAyahNW = "";
				$NamaIbuNW = "";
				$TanggalBaptisNW = "";
				$WaktuBaptis = "";
				$TempatBaptisNW = "";
				$PendetaBaptisNW = "";
				$NoSuratTitipanNW = ""; 
				$TanggalBaptis = "";
				$TempatBaptis = "";
				$NamaGereja = "";
				$DiBaptisOleh = "";

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="BaptisAnakEditor.php?BaptisID=<?php echo $BaptisID . "&PersonID=".$per_ID."&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
               <?php if ($TempatLayananBaptis == 1){         
					echo "<td><a target=\"_blank\" href=\"PrintViewBaptisAnak.php?BaptisID=".$BaptisID."&GID=$refresh&\">Cetak<br>Sertifikat</a>&nbsp;</td>";
					if ($per_ID == 0){$TanggalRencanaBaptis=$TanggalBaptisNW;}else{$TanggalRencanaBaptis=$TanggalBaptis;}
					echo "<td><a target=\"_blank\" href=\"PrintViewBaptisAnakBeritaAcara.php?BaptisID=".$BaptisID."&TanggalBaptis=".$TanggalRencanaBaptis."&WaktuBaptis=".$WaktuBaptis."&GID=$refresh&\">Cetak<br>BeritaAcara</a>&nbsp;</td>";
					
					if ($per_ID == 0){
					 echo "<td><a target=\"_blank\" href=\"PrintViewBaptisAnakSK.php?BaptisID=".$BaptisID."&GID=$refresh&\">Cetak<br>S.Keterangan</a>&nbsp;</td>";}
					 else {echo "<td></td>";}
					 
					 
					
					}else{
					echo "<td><a target=\"_blank\" href=\"PrintViewBaptisAnakTitipan.php?BaptisID=".$BaptisID."&\">Cetak<br>Titipan</a>&nbsp;</td>";
					echo "<td></td><td></td>";
					}
					?>
					
					<td><a href="PersonView.php?PersonID=<?php echo $per_ID ?>"><?php echo $NamaPemohonBaptis ?></a><?php echo $NamaPemohonBaptisNW ?>&nbsp;</td>
					<td><?php if (strlen($per_ID) > 0){echo $TempatLahir . ", " . date2Ind($TanggalLahir,3) ;}else{echo $TempatLahirNW . ", " . date2Ind($TanggalLahirNW,3); } ?>&nbsp;</td>
					<td>(Ayah) <?php if (strlen($per_ID) > 0){echo $NamaAyah;}else{echo $NamaAyahNW;} ?>
					<br> (Ibu) <?php if (strlen($per_ID) > 0){echo $NamaIbu;}else{echo $NamaIbuNW;} ?>&nbsp;</td>
					<td><?php if (strlen($per_ID) > 0){echo date2Ind($TanggalBaptis,3);}else{echo date2Ind($TanggalBaptisNW,3);} ?>&nbsp;</td>
					<td><?php 
					if ($WaktuBaptis == "11")
					{echo "06.00 WIB <br>Cut Meutia";}
					elseif ($WaktuBaptis == "12"){echo "09.00 WIB <br>Cut Meutia";}
					elseif ($WaktuBaptis == "21"){echo "07.00 WIB <br>Cikarang";}
					elseif ($WaktuBaptis == "31"){echo "07.30 WIB <br>Karawang";}
					elseif ($WaktuBaptis == "41"){echo "17.00 WIB <br>Tambun";}
					else{echo "";} ?>&nbsp;</td>
					
			
					<td><?php if (strlen($per_ID) > 0){echo $NamaGereja;}else{echo $NamaGereja;} ?>&nbsp;</td>
					<td><?php if (strlen($per_ID) > 0){echo $DiBaptisOleh;}else{echo $PendetaBaptisNW;}  ?>&nbsp;</td>
						<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=Baptis&AppPersonID=<?php echo $per_ID ?>&BaptisID=<?php echo $BaptisID ?>">X</a>&nbsp;</td>
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


elseif ($iMode == 12)
{

// echo "listing Surat Permohonan Pelayan Firman";
/**********************
**  Sidhi Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "select a.*, b.* , c.* from suratkeluarPFgkjbekti a 
		left join LokasiTI b ON a.KodeTI = b.KodeTI
		left join DaftarPendeta c ON a.PelayanFirman = c.PendetaID
		
 ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "WaktuPF":
                        $sSQL = $sSQL . " ORDER BY a.TanggalPF , a.WaktuPF DESC";
                        break;
                case "TempatIbadah":
                        $sSQL = $sSQL . " ORDER BY a.KodeTI ASC";	
                        break;
				case "PelayanFirman":
                        $sSQL = $sSQL . " ORDER BY a.PelayanFirman ASC, a.PFnonInstitusi ASC";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY a.TanggalPF DESC";
                        break;
        }
//echo $sSQL;
        $rsSuratKeluarCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsSuratKeluarCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"SuratPFEditor.php\">" . gettext("Tambahkan Data Surat Permohonan Pelayan Firman") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="SuratKeluar">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="WaktuPF"><?php echo gettext("Waktu Pelayanan Firman"); ?></option>
                        <option value="TempatIbadah"><?php echo gettext("Tempat Ibadah"); ?></option>
						<option value="PelayanFirman"><?php echo gettext("Pelayan Firman"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="SuratKeluar">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=SuratKeluar&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=SuratKeluar&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=SuratKeluar&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=SuratKeluar&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=SuratKeluar&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="SuratKeluar">
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
	
				<td><?php echo gettext("Cetak"); ?></td>
				<td><?php echo gettext("Fax"); ?></td>
				<td><?php echo gettext("Email"); ?></td>
			
				<td><?php echo gettext("Pelayan Firman"); ?></td>
				<td><?php echo gettext("Tempat Ibadah"); ?></td>
                <td><?php echo gettext("Waktu"); ?></td>
				<td><?php echo gettext("Bahasa"); ?></td>
				<td><?php echo gettext("Tema"); ?></td>

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

				$PelayanFirman = "";
				$NamaPendeta = "";
				$PFnonInstitusi = "";
				$KodeTI = "";
				$NamaTI = ""; 
				$TempatPF = "";	
				$WaktuPF = "";	
				$BahasaPF = "";	
				$TemaPF = ""; 

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="BaptisAnakEditor.php?SuratKeluarID=<?php echo $SuratKeluarID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
                        
					<td><a target="_blank" href="PrintViewSuratKeluarPF.php?SuratKeluarID=<?php echo $SuratKeluarID."&GID=$refresh&"; ?>"><?php echo "Cetak" ?></a>&nbsp;</td>

					<td><?php if (($PelayanFirman) > 0){echo $NamaPendeta;}else{echo $PFnonInstitusi; }?>&nbsp;</td>
					<td><?php if (($KodeTI) > 0){echo $NamaTI;}else{echo $TempatPF; }?>&nbsp;</td>
					<td><?php echo $WaktuPF; ?>&nbsp;</td>
					<td><?php echo $BahasaPF; ?>&nbsp;</td>
					<td><?php echo $TemaPF; ?>&nbsp;</td>
					
					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=SuratKeluar&SuratKeluarID=<?php echo $SuratKeluarID ?>">X</a>&nbsp;</td>
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
elseif ($iMode == 13)
{

// echo "listing Liturgi";
/**********************
**  Liturgi Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "select * from LiturgiGKJBekti ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
                case "Warna":
                        $sSQL = $sSQL . " ORDER BY Warna ASC";	
                        break;
				case "Bahasa":
                        $sSQL = $sSQL . " ORDER BY Bahasa";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
        }
//echo $sSQL;
        $rsLiturgiCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsLiturgiCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"LiturgiEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Liturgi") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Liturgi">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                        <option value="Warna"><?php echo gettext("Warna"); ?></option>
						<option value="Bahasa"><?php echo gettext("Bahasa"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Liturgi">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Liturgi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=Liturgi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Liturgi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Liturgi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Liturgi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="Liturgi">
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
	
				<td><?php echo gettext("Cetak"); ?></td>
				<td><?php echo gettext("Email"); ?></td>
			
				<td><?php echo gettext("Tanggal"); ?></td>
				<td><?php echo gettext("Warna"); ?></td>
                <td><?php echo gettext("Keterangan"); ?></td>
				<td><?php echo gettext("Tema"); ?></td>				
				<td><?php echo gettext("Bahasa"); ?></td>
				<td><?php echo gettext("Bacaan"); ?></td>
				<td><?php echo gettext("AyatPenuntun"); ?></td>
				<td><?php echo gettext("Nyanyian"); ?></td>			
				
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

				$Tanggal = "";
				$Warna = "";
				$Bahasa = "";
				$Keterangan = "";
				$Tema = "";
				$Bacaan1 = "";
				$BacaanAntara = "";
				$Bacaan2 = "";
				$BacaanInjil = "";
				$AyatPenuntunHK = "";
				$AyatPenuntunBA = "";
				$AyatPenuntunLM = "";
				$AyatPenuntunP = "";
				$AyatPenuntunNP = "";
				$Nyanyian1 = "";
				$Nyanyian2 = "";
				$Nyanyian3 = "";
				$Nyanyian4 = "";
				$Nyanyian5 = "";
				$Nyanyian6 = "";
				$Nyanyian7 = "";
				$Nyanyian8 = "";
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				<?php 
				$TGL=$Tanggal;
				$MGG=getWeeks("$Tanggal","sunday");
				$TGLIND=date2Ind($Tanggal,3);
				
				//echo $TGL; ?>
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="LiturgiEditor.php?LiturgiID=<?php echo $LiturgiID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
                        
					<td><a target="_blank" href="PrintViewLiturgi.php?LiturgiID=<?php echo $LiturgiID."&GID=$refresh&"; ?>"><?php echo "Cetak" ?></a>&nbsp;<br>
					<a target="_blank" href="TataIbadahMinggu<?php echo $MGG ; ?>.php?TGL=<?php echo $TGL ?>&BHS=<?php echo $Bahasa."&GID=$refresh&"; ?>"><?php echo "Cetak Liturgi" ?></a>&nbsp; 
					</td>
					<td><a target="_blank" href="EmailLiturgi.php?LiturgiID=<?php echo $LiturgiID ?>"><?php echo "Email" ?></a>&nbsp;</td>

				<td>

				<a target="_blank" href="TataIbadahMinggu<?php echo $MGG ; ?>.php?TGL=<?php echo $TGL ?>&BHS=<?php echo $Bahasa; ?>">
				<?php echo $TGLIND . '
				<br>Minggu ke-'.getWeeks("$Tanggal","sunday"); ?>
				</a>
				</td>
				<td><?php echo $Warna ?></td>
                <td><?php echo $Keterangan ?></td>
				<td><?php echo $Tema ?></td>				
				<td><?php echo $Bahasa ?></td>
				<td><?php 
				echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $Bacaan1 . "&mode=print\" >".$Bacaan1."</a>";echo";";
				echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $BacaanAntara . "&mode=print\" >".$BacaanAntara."</a>";echo";";
				echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $Bacaan2 . "&mode=print\" >".$Bacaan2."</a>";echo";";
				echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $BacaanInjil . "&mode=print\" >".$BacaanInjil."</a>";echo";";
							?></td>

				
				<td><?php  
				if ($AyatPenuntunHK <> ""){
				 
				echo "(HK)";echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $AyatPenuntunHK . "&mode=print\" >".$AyatPenuntunHK."</a>";echo";";
				echo "(BA)";echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $AyatPenuntunBA . "&mode=print\" >".$AyatPenuntunBA."</a>";echo";";
				echo "(LM)";echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $AyatPenuntunLM . "&mode=print\" >".$AyatPenuntunLM."</a>";echo";";
				echo "(P)";echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $AyatPenuntunP . "&mode=print\" >".$AyatPenuntunP."</a>";echo";";
				echo "(NP)";echo "<a target=\"_blank\" href=\"http://alkitab.sabda.org/passage.php?passage=" . $AyatPenuntunNP . "&mode=print\" >".$AyatPenuntunNP."</a>";echo";";
				}else 
				{echo "<font color=\"red\" ><blink> Data TIDAK Lengkap </blink></font>";}
				?></td>
				<td><?php
				if ($Nyanyian1 <> ""){
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian1, 0, strpos($Nyanyian1, ':')) ."\">" .$Nyanyian1."</a>";echo";";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian2, 0, strpos($Nyanyian2, ':')) ."\">" .$Nyanyian2."</a>";echo";";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian3, 0, strpos($Nyanyian3, ':')) ."\">" .$Nyanyian3."</a>";echo";";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian4, 0, strpos($Nyanyian4, ':')) ."\">" .$Nyanyian4."</a>";echo";";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian5, 0, strpos($Nyanyian5, ':')) ."\">" .$Nyanyian5."</a>";echo";";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian6, 0, strpos($Nyanyian6, ':')) ."\">" .$Nyanyian6."</a>";echo";";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian7, 0, strpos($Nyanyian7, ':')) ."\">" .$Nyanyian7."</a>";echo";";
				echo "<a target=\"_blank\" href=\"http://kidung.co/" . $a=substr($Nyanyian8, 0, strpos($Nyanyian8, ':')) ."\">" .$Nyanyian8."</a>";echo";";
				
				}else 
				{echo "<font color=\"red\" ><blink> Data TIDAK Lengkap </blink></font>";}
				//			echo $Nyanyian1;echo";";echo $Nyanyian2;echo";";echo $Nyanyian3;echo";";echo $Nyanyian4;echo";";
				//			echo $Nyanyian5;echo";";echo $Nyanyian6;echo";";echo $Nyanyian7;echo";";echo $Nyanyian8;echo";";
				?></td>			
				

					
					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=Liturgi&LiturgiID=<?php echo $LiturgiID ?>">X</a>&nbsp;</td>
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
elseif ($iMode == 14)
{

// echo "listing PelayanFirman";
/**********************
**  Pelayan Firman Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "select distinct a.*, a.DateEntered as TglDibuat, a.DatelastEdited as TglDiedit, 
        a.EnteredBy as DibuatOleh, a.EditedBy as DieditOleh, 
        f.per_Firstname as NamaPembuat, g.per_Firstname as NamaPengedit, 
        b.* , c.* , d.*, e.*, a.Bahasa as Bahasa from JadwalPelayanFirman a
		left join DaftarPendeta b ON a.PelayanFirman = b.PendetaID
		left join LiturgiGKJBekti c ON a.TanggalPF = c.Tanggal
		left join LokasiTI d ON a.KodeTI = d.KodeTI
		left join DaftarGerejaGKJ e ON b.GerejaID = e.GerejaID	
		left join person_per f ON a.EnteredBy = f.per_ID	
		left join person_per g ON a.EditedBy = g.per_ID
		GROUP BY PelayanFirmanID 
		";
//WHERE a.Bahasa = c.Bahasa
         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY a.TanggalPF DESC, a.WaktuPF ASC, a.KodeTI ASC";
                        break;
                case "TempatIbadah":
                        $sSQL = $sSQL . " ORDER BY a.KodeTI ASC";	
                        break;
				case "Bahasa":
                        $sSQL = $sSQL . " ORDER BY Bahasa";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY a.TanggalPF DESC, a.KodeTI ASC, a.WaktuPF ASC";
                        break;
        }
//echo $sSQL;
        $rsLiturgiCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsLiturgiCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PelayanFirmanEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Jadwal Pelayan Firman") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="PelayanFirman">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                        <option value="TempatIbadah"><?php echo gettext("TempatIbadah"); ?></option>
						<option value="Bahasa"><?php echo gettext("Bahasa"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="PelayanFirman">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=JadwalPelayanFirman&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=JadwalPelayanFirman&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=JadwalPelayanFirman&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=JadwalPelayanFirman&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=JadwalPelayanFirman&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="PelayanFirman">
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


                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
			
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';

         } ?>
		<br>
		Catatan : <br>
		- C1=Cetak dgn Header, C2=Cetak dgn Header+TTD ,C3=Cetak tanpa Header, C4=Cetak tanpa Header+TTD<br>
		- Klik dibagian Bahasa untuk mencetak Ringkasan liturgi

        <BR>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">

        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
	
				<td colspan=4 align=center><?php echo gettext("Cetak Surat"); ?></td>
				<td><?php echo gettext("History "); ?></td>
				<td><?php echo gettext("Trk Kirim"); ?></td>
			
				<td><?php echo gettext("Tanggal"); ?></td>
                <td><?php echo gettext("TempatIbadah"); ?></td>				

				<td><?php echo gettext("PelayanFirman"); ?></td>	
			
				<td><?php echo gettext("Jenis Ibadah"); ?></td>						
				<td><?php echo gettext("PelayanPendukung"); ?></td>
				
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

				$TanggalPF = "";
				$WaktuPF = "";	
				$NamaTI = "";
				$TempatPF = "";				
				$Bahasa = "";
				$NamaPendeta = "";
				$NamaGereja = "";
				$PFnonInstitusi = "";
				

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PelayanFirmanEditor.php?PelayanFirmanID=<?php echo $PelayanFirmanID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
                        
				<td><a target="_blank" href="PrintViewPermohonanPF.php?PelayanFirmanID=<?php echo $PelayanFirmanID."&GID=$refresh&"; ?>&Mode=1" title="Cetak dgn Header tanpa TTD"><?php echo "C1" ?></a></td>
				<td><a target="_blank" href="PrintViewPermohonanPF.php?PelayanFirmanID=<?php echo $PelayanFirmanID."&GID=$refresh&"; ?>&Mode=2" title="Cetak dgn Header dgn TTD Lengkap"><?php echo "C2" ?></a><br>
					<a target="_blank" href="PrintViewPermohonanPF.php?PelayanFirmanID=<?php echo $PelayanFirmanID."&GID=$refresh&"; ?>&Mode=21" title="Cetak dgn Header dgn TTD Ketua"><?php echo "C2A" ?></a><br>
					<a target="_blank" href="PrintViewPermohonanPF.php?PelayanFirmanID=<?php echo $PelayanFirmanID."&GID=$refresh&";?>&Mode=22" title="Cetak dgn Header dgn TTD Sekr1"><?php echo "C2B" ?></a></td>
				<td><a target="_blank" href="PrintViewPermohonanPF.php?PelayanFirmanID=<?php echo $PelayanFirmanID."&GID=$refresh&"; ?>&Mode=3" title="Cetak tanpa Header tanpa TTD Lengkap"><?php echo "C3" ?></a></td>
				<td><a target="_blank" href="PrintViewPermohonanPF.php?PelayanFirmanID=<?php echo $PelayanFirmanID."&GID=$refresh&"; ?>&Mode=4" title="Cetak tanpa Header dgn TTD Lengkap"><?php echo "C4" ?></a><br>
					<a target="_blank" href="PrintViewPermohonanPF.php?PelayanFirmanID=<?php echo $PelayanFirmanID."&GID=$refresh&";?>&Mode=41" title="Cetak tanpa Header dgn TTD Ketua"><?php echo "C4A" ?></a><br>
					<a target="_blank" href="PrintViewPermohonanPF.php?PelayanFirmanID=<?php echo $PelayanFirmanID."&GID=$refresh&"; ?>&Mode=42" title="Cetak tanpa Header dgn TTD Sekr1"><?php echo "C4B" ?></a></td>
				
				<td>
				<?
				$TglDibuat = substr($TglDibuat, 0, -9);
				$TglDiedit = substr($TglDiedit, 0, -9);
				?>

				Dibuat : <font color=green ><?	echo date2Ind($TglDibuat,3);?>	</font><br>
				Oleh.. : <font color=green ><?	echo strtok($NamaPembuat, " ");?>	</font><br>
				DiEdit : <font color=green ><?	echo date2Ind($TglDiedit,3);?>	</font><br>
				Oleh.. : <font color=green ><?	echo strtok($NamaPengedit, " ");?>	</font><br>
				</td>


				<td>
				Fax.. : <font color=green ><?	echo date2Ind($TglFax,3);?>	</font><br>
				Surat : <font color=green ><?	echo date2Ind($TglSurat,3);?>	</font><br>
				Telp. : <font color=green ><?	echo date2Ind($TglTelp,3);?>	</font><br>
				
				
				<a href="SelectSendMail.php?mode=PelayanFirman&PelayanFirmanID=<?php echo $PelayanFirmanID ?>">
				<?php if ( $Email <> "" || $PFNIEmail <> "" || $EmailPendeta <> "" ){ echo "Email";} ?></a>&nbsp;
<?php
//Check Tanggal Kirim Email : 
$sSQL2 = "SELECT date as TglKirim  FROM `logger` WHERE `var1` LIKE '%Email%' AND per_ID =".$PelayanFirmanID." ORDER BY `logger`.`count`  DESC
		 LIMIT 1";

// echo $sSQL2; 

$rsEmail2 = RunQuery($sSQL2);

$num_rows = mysql_num_rows($rsEmail2);
if ($num_rows > 0 ) {
extract(mysql_fetch_array($rsEmail2));
//echo "<br>Dikirim Tgl:<br>";
echo "<font color=green >";
//echo date('d-M-Y H:i:s', strtotime ($TglKirim));
echo date2Ind($TglKirim,3);
echo "</font>";
//echo $TglKirim;
}else{

$exp_date = $TanggalPF; 
$todays_date = date("Y-m-d"); 

$today = strtotime($todays_date); 
$expiration_date = strtotime($exp_date); 

if ($expiration_date > $today) 
{ $valid = "yes"; 
	if ( $Email <> "" || $PFNIEmail <> "" || $EmailPendeta <> "" ){
		echo "<blink><font color=red >Belum Dikirim !</font></blink>";
	}
} else 
{ $valid = "no"; }

}
?>
				</td>

				<td><?php echo date2Ind($TanggalPF,2)."<br><br>"; ?>
				<a target="_blank" href="PrintViewTerimakasih.php?PelayanFirmanID=<?php echo $PelayanFirmanID ?>&LiturgiID=<?php echo $LiturgiID."&GID=$refresh&"; ?>">Cetak <br>Trimakasih</a>
				
				</td>
                <td><?php echo $NamaTI." ";echo $TempatPF."<br>";echo $WaktuPF."<br>" ?>
                <a target="_blank" href="PrintViewLiturgi.php?LiturgiID=<?php echo $LiturgiID."&GID=$refresh&"; ?>"><?php echo $Bahasa ?></a>&nbsp;</td>
				<td><?php 
				$hariini = strtotime(date("Ymd"));
				$minggukemaren = strtotime('last Sunday', $hariini);
				$minggudepan = strtotime('next Sunday', $hariini);

				if ($TanggalPF==date( 'Y-m-d', $minggudepan)){
				echo "<b><i>";
				echo $NamaPendeta; echo $PFnonInstitusi;
				echo "</i></b><font color=red ><blink> ***</blink></font>";				
				}else{
				echo $NamaPendeta; echo $PFnonInstitusi;
				}
				?><br><?php echo $NamaGereja;echo $PFNIAlamat ?></td> 
				<td><?php if ($Hal==" "){echo "Ibadah ";} else { echo "Ibadah ".$Hal;} ?></td> 
				<td>
<?php
//Check Pelayan Pendukung Peribadahan : 
$sSQL3 = "SELECT * FROM JadwalPelayanPendukung WHERE Tanggal ='".$TanggalPF."' AND Waktu ='".$WaktuPF."' AND KodeTI ='".$KodeTI."' LIMIT 1";
// echo $sSQL3; 

$rsPendukung = RunQuery($sSQL3);

$num_rows = mysql_num_rows($rsPendukung);
if ($num_rows > 0 ) {
extract(mysql_fetch_array($rsPendukung));
echo "<a target=\"_blank\" href=\"PrintViewPelayanPendukung.php?TGL=".$TanggalPF."&GID=$refresh&\">" ;
echo "<font color=green >Lihat Daftar";
echo "</font></a>";
//echo $TglKirim;
}else{
echo "<a target=\"_blank\" href=\"PelayanPendukungEditor.php?TGL=".$TanggalPF."&PKL=".$WaktuPF."&KodeTI=".$KodeTI."&GID=$refresh&  \">" ;
echo "<font color=red ><blink>Belum Dibuat!";
echo "</blink></font></a>";

}
?>				
				</td> 
				
					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=PelayanFirman&PelayanFirmanID=<?php echo $PelayanFirmanID ?>">X</a>&nbsp;</td>
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
elseif ($iMode == 15)
{

// echo "listing DaftarPendeta";
/**********************
**  Pendeta Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "SELECT a.PendetaID as PendetaID, a.Salutation as Salutation,
		a.NamaPendeta as NamaPendeta, 
		a.HP as HP, a.EmailPendeta as EmailPendeta,
        b.NamaGereja, c.NamaKlasis, 
		b.Alamat1 as Alamat1, b.Alamat2 as Alamat2, b.Alamat3 as Alamat3,
		b.Telp as Telp, b.Fax as Fax, b.Email as Email 
		FROM DaftarPendeta a
		LEFT JOIN DaftarGerejaGKJ b ON a.GerejaID = b.GerejaID
		LEFT JOIN DaftarKlasisGKJ c ON b.KlasisID = c.KlasisID";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "NamaPendeta":
                        $sSQL = $sSQL . " ORDER BY NamaPendeta ASC";
                        break;
                case "NamaGereja":
                        $sSQL = $sSQL . " ORDER BY NamaGereja ASC";	
                        break;
				case "Salutation":
                        $sSQL = $sSQL . " ORDER BY Salutation DESC, NamaGereja ASC, NamaPendeta ASC";
                        break;		
				case "Email":
                        $sSQL = $sSQL . " ORDER BY Email Desc";	
                        break;	
                default:
                        $sSQL = $sSQL . " ORDER BY NamaGereja, NamaPendeta ASC";
                        break;
        }
//echo $sSQL;
        $rsPendetaCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsPendetaCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"DaftarPendetaEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Pendeta") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="DaftarPendeta">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="NamaPendeta"><?php echo gettext("NamaPendeta"); ?></option>
                        <option value="NamaGereja"><?php echo gettext("NamaGereja"); ?></option>
						<option value="Salutation"><?php echo gettext("Salutation"); ?></option>
						<option value="Email"><?php echo gettext("Email"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="DaftarPendeta">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=DaftarPendeta&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=DaftarPendeta&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=DaftarPendeta&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=DaftarPendeta&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=DaftarPendeta&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="DaftarPendeta">
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
	

				<td><?php echo gettext("Nama"); ?></td>
                <td><?php echo gettext("Gereja"); ?></td>
                <td><?php echo gettext("Klasis"); ?></td>				
				<td><?php echo gettext("Alamat"); ?></td>
				<td><?php echo gettext("Telp"); ?></td>
				<td><?php echo gettext("Fax"); ?></td>	
				<td><?php echo gettext("Email"); ?></td>	
				<td><?php echo gettext("Email Pdt"); ?></td>
				<td><?php echo gettext("HP"); ?></td>					
	
				
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
				$Salutation = ""; 	
				$NamaPendeta = ""; 	
				$NamaGereja = ""; 	
				$Keterangan = ""; 	
				$Alamat1 = "";	
				$Alamat2 = "";	
				$Alamat3 = ""; 	
				$Telp = ""; 	
				$HP = "";	
				$Fax = "";
				$Email = "";
				$EmailPendeta = "";
			
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="DaftarPendetaEditor.php?PendetaID=<?php echo $PendetaID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
                <td><?php echo $NamaPendeta ?></td>				
				<td><?php echo $NamaGereja ?></td>
				<td><?php echo $NamaKlasis ?></td>
				<td><?php echo $Alamat1.",".$Alamat2.",".$Alamat3; ?></a>&nbsp;</td>
				<td><?php echo $Telp ?></td>
				<td><?php echo $Fax ?></td> 
				<td><?php echo $Email ?></td> 
				<td><?php echo $EmailPendeta ?></td> 
				<td><?php echo $HP ?></td> 
					
					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=DaftarPendeta&PendetaID=<?php echo $PendetaID ?>">X</a>&nbsp;</td>
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
elseif ($iMode == 16)
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
                    <td><a href="DaftarGerejaEditor.php?GerejaID=<?php echo $GerejaID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
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
elseif ($iMode == 18)
{

// echo "listing DaftarKlasis";
/**********************
**  Klasis Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select * from DaftarKlasisGKJ ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "KodeKlasis":
                        $sSQL = $sSQL . " ORDER BY KlasisID ASC";
                        break;
                case "NamaKlasis":
                        $sSQL = $sSQL . " ORDER BY NamaKlasis ASC";	
                        break;
				default:
                        $sSQL = $sSQL . " ORDER BY KlasisID ASC, NamaKlasis ASC";
                        break;
        }
		
//echo $sSQL;
        $rsKlasisCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsKlasisCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"DaftarKlasisEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Klasis") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="DaftarKlasis">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="KlasisID"><?php echo gettext("KlasisID"); ?></option>
                        <option value="NamaKlasis"><?php echo gettext("NamaKlasis"); ?></option>

                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="DaftarKlasis">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=DaftarKlasis&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=DaftarKlasis&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=DaftarKlasis&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=DaftarKlasis&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=DaftarKlasis&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="DaftarKlasis">
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
                    <td><a href="DaftarKlasisEditor.php?KlasisID=<?php echo $KlasisID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
				<td><?php echo $NamaKlasis ?></td>
				<td><?php echo $Alamat1.",".$Alamat2.",".$Alamat3; ?></a>&nbsp;</td>
				<td><?php echo $Telp ?></td>
				<td><?php echo $Fax ?></td> 
				<td><?php echo $Email ?></td> 
					
					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=DaftarKlasis&KlasisID=<?php echo $KlasisID ?>">X</a>&nbsp;</td>
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

elseif ($iMode == 19)
{

// echo "listing Baptis Dewasa";
/**********************
**  BaptisDewasa Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "select a.* , z.BaptisID,
		a.per_id, 
		a.per_firstname as NamaPemohonBaptis , 
		a.per_WorkEmail as TempatLahir,
		CONCAT(a.per_BirthYear,'-',a.per_BirthMonth,'-',a.per_BirthDay) as TanggalLahir,
		a.per_Workemail as TempatLahir,
		IF(a.per_fmr_id>2,c.per_firstname,x.c16) as NamaAyah,
		IF(a.per_fmr_id>2,d.per_firstname,x.c17) as NamaIbu,	
		z.KetuaMajelis as KetuaMajelis,
		z.SekretarisMajelis as SekretarisMajelis,
		z.NamaLengkap as NamaPemohonBaptisNW, 
		z.TempatLahir as TempatLahirNW,
		z.TanggalLahir 	as TanggalLahirNW,
		z.NamaAyah 	as NamaAyahNW,
		z.NamaIbu 	as NamaIbuNW,
		z.TanggalBaptis as TanggalBaptisNW,
		z.WaktuBaptis as WaktuBaptis,
		z.TempatBaptis 	as TempatBaptisNW,
		z.PendetaBaptis as PendetaBaptisNW,
		z.NoSuratTitipan as NoSuratTitipanNW, 
		f.NamaGereja as NamaGereja,
		
		x.c18 as TanggalBaptis,
		x.c28 as TempatBaptis,
		x.c39 as DiBaptisOleh,

	
		a.per_gender as JK , a.per_fam_id
	
	
from baptisdewasagkjbekti z 
left join person_per a ON z.per_id = a.per_id 
left join person_custom x ON a.per_id = x.per_id 
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
left join DaftarPendeta e ON z.PendetaBaptis = e.PendetaID
left join DaftarGerejaGKJ f ON z.TempatTitipBaptis = f.GerejaID


 ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Nama":
                        $sSQL = $sSQL . " ORDER BY NamaPemohonBaptis DESC";
                        break;
                case "JenisKelamin":
                        $sSQL = $sSQL . " ORDER BY JK, NamaPemohonBaptis DESC";	
                        break;
				case "Kelompok":
                        $sSQL = $sSQL . " ORDER BY JK, a.per_Workphone, NamaPemohonBaptis DESC";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY z.BaptisID Desc";
                        break;
        }

        $rsSidhiCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsSidhiCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"BaptisDewasaEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Permohonan Baptis") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="BaptisDewasa">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Nama"><?php echo gettext("Nama"); ?></option>
                        <option value="JenisKelamin"><?php echo gettext("JenisKelamin"); ?></option>
						<option value="Kelompok"><?php echo gettext("Kelompok"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Baptis">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=BaptisDewasa&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=BaptisDewasa&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=BaptisDewasa&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=BaptisDewasa&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=BaptisDewasa&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="BaptisDewasa">
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
	
				<td colspan="2" align="center"><?php echo gettext("Cetak"); ?></td>
			
				<td><?php echo gettext("Nama"); ?></td>
				<td><?php echo gettext("Tempat/Tanggal lahir"); ?></td>
                <td><?php echo gettext("NamaAyah"); ?></td>
				<td><?php echo gettext("NamaIbu"); ?></td>
                <td><?php echo gettext("Tanggal Baptis"); ?></td>
				<td><?php echo gettext("Waktu Baptis"); ?></td>
                <td><?php echo gettext("Tempat Baptis"); ?></td>
				<td><?php echo gettext("Dibaptis Oleh"); ?></td>
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$SidhiID = "";
				$per_ID = "";
				$NamaPemohonSidhi = "";
				$TempatLahir = "";
				$TanggalLahir = "";
				$TempatLahir = "";
				$NamaAyah = ""; 
				$NamaIbu = "";	
				$KetuaMajelis = "";	
				$SekretarisMajelis = "";	
				$NamaPemohonSidhiNW = ""; 
				$TempatLahirNW = "";
				$TanggalLahirNW = "";
				$NamaAyahNW = "";
				$NamaIbuNW = "";
				$TanggalBaptisNW = "";
				$WaktuBaptis = "";
				$TempatBaptisNW = "";
				$PendetaBaptisNW = "";
				$NoSuratTitipanNW = ""; 
				$TanggalBaptis = "";
				$TempatBaptis = "";
				$NamaGereja = "";
				$DiBaptisOleh = "";

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="BaptisDewasaEditor.php?BaptisID=<?php echo $BaptisID . "&PersonID=".$per_ID."&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
               <?php if ($TempatBaptisNW == 1){         
					echo "<td><a target=\"_blank\" href=\"PrintViewBaptisDewasa.php?BaptisID=".$BaptisID."&GID=$refresh&\">Cetak<br>Sertifikat</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewBaptisDewasaBeritaAcara.php?BaptisID=".$BaptisID."&TanggalBaptis=".$TanggalBaptis."&WaktuBaptis=".$WaktuBaptis."&GID=$refresh&\">Cetak<br>BeritaAcara</a>&nbsp;</td>";
					
					}else{
					echo "<td><a target=\"_blank\" href=\"PrintViewBaptisDewasaTitipan.php?BaptisID=".$BaptisID."&GID=$refresh&\">Cetak<br>Titipan</a>&nbsp;</td>";
					echo "<td></td>";
					}
					?>
					
					<td><a href="PersonView.php?PersonID=<?php echo $per_ID ?>"><?php echo $NamaPemohonBaptis ?></a><?php echo $NamaPemohonBaptisNW ?>&nbsp;</td>
					<td><?php if (strlen($per_ID) > 0){echo $TempatLahir . ", " . date2Ind($TanggalLahir,3) ;}else{echo $TempatLahirNW . ", " . date2Ind($TanggalLahirNW,3); } ?>&nbsp;</td>
					<td><?php if (strlen($per_ID) > 0){echo $NamaAyah;}else{echo $NamaAyahNW;} ?>&nbsp;</td>
					<td><?php if (strlen($per_ID) > 0){echo $NamaIbu;}else{echo $NamaIbuNW;} ?>&nbsp;</td>
					<td><?php if (strlen($per_ID) > 0){echo date2Ind($TanggalBaptis,3);}else{echo date2Ind($TanggalBaptisNW,3);} ?>&nbsp;</td>
					<td><?php 
					if ($WaktuBaptis == "11")
					{echo "06.00 WIB <br>Cut Meutia";}
					elseif ($WaktuBaptis == "12"){echo "09.00 WIB <br>Cut Meutia";}
					elseif ($WaktuBaptis == "21"){echo "07.00 WIB <br>Cikarang";}
					elseif ($WaktuBaptis == "31"){echo "07.30 WIB <br>Karawang";}
					elseif ($WaktuBaptis == "41"){echo "17.00 WIB <br>Tambun";}
					else{echo "";} ?>&nbsp;</td>
					
			
					<td><?php if (strlen($per_ID) > 0){echo $TempatBaptis."".$NamaGereja;}else{echo $TempatBaptisNW;} ?>&nbsp;</td>
					<td><?php if (strlen($per_ID) > 0){echo $DiBaptisOleh;}else{echo $PendetaBaptisNW;}  ?>&nbsp;</td>
						<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=BaptisDewasa&AppPersonID=<?php echo $per_ID ?>&BaptisID=<?php echo $BaptisID ?>">X</a>&nbsp;</td>
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

elseif ($iMode == 20)
{

// echo "listing Permohonan Pindah Perorangan(attestasi)";
/**********************
**  Permohonan Pindah Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.*, 
		
		CONCAT(b.per_id,b.per_fam_id,b.per_gender,b.per_fmr_id) as NomorInduk,
		CONCAT(b.per_BirthYear,'-',b.per_BirthMonth,'-',b.per_BirthDay) as TanggalLahir,
		b.per_firstname as Nama , 
		b.per_WorkEmail as TempatLahir,
		
		c.per_firstname as NamaAyah,
		d.per_firstname as NamaIbu,
		f.NamaGereja as NamaGereja,
		g.c48 as AlasanPindah
		
		FROM PermohonanPindahgkjbekti  a
		
		LEFT JOIN person_per b ON a.per_ID = b.per_ID 
        left join family_fam e ON b.per_fam_id = e.fam_id 
		left join person_per c ON (e.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
		left join person_per d ON (e.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
		LEFT JOIN DaftarGerejaGKJ f ON a.GerejaID = f.GerejaID
		LEFT JOIN person_custom g ON a.per_ID = g.per_ID  ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Nama":
                        $sSQL = $sSQL . " ORDER BY per_Firstname DESC";
                        break;
                case "Keluarga":
                        $sSQL = $sSQL . " ORDER BY per_fam_id DESC";	
                        break;
				case "Kelompok":
                        $sSQL = $sSQL . " ORDER BY b.per_Workphone, b.per_firstname";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY a.PindahID Desc";
                        break;
        }

        $rsPindahCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsPindahCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PindahPeroranganEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Permohonan Pindah / Attestasi") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Pindah">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Nama"><?php echo gettext("Nama"); ?></option>
                        <option value="Keluarga"><?php echo gettext("Keluarga"); ?></option>
						<option value="Kelompok"><?php echo gettext("Kelompok"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Pindah">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Pindah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=Pindah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Pindah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Pindah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Pindah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="Pindah">
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
	
				<td colspan="5" align="center"><?php echo gettext("Cetak"); ?></td>
			
				<td><?php echo gettext("Nama"); ?></td>
				<td><?php echo gettext("Tempat/Tanggal lahir"); ?></td>
                <td><?php echo gettext("Nama Orang Tua"); ?></td>
			    <td><?php echo gettext("Tanggal Permohonan"); ?></td>
				<td><?php echo gettext("Gereja Tujuan"); ?></td>
				<td><?php echo gettext("Alasan Pindah"); ?></td>
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$SidhiID = "";
				$per_ID = "";
				$Nama = "";
				$TempatLahir = "";
				$TanggalLahir = "";
				$NamaAyah = ""; 
				$NamaIbu = "";	
				$KetuaMajelis = "";	
				$SekretarisMajelis = "";	
				$Pendeta = "";
				$NamaGereja = "";

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PindahPeroranganEditor.php?PindahID=<?php echo $PindahID . "&PersonID=".$per_ID."&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
               <?php 
					echo "<td><a target=\"_blank\" href=\"PrintViewPermohonanPindahPerorangan.php?PindahID=".$PindahID."&Mode=1&GID=$refresh&\">C1</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewPermohonanPindahPerorangan.php?PindahID=".$PindahID."&Mode=2&GID=$refresh&\">C2</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewPermohonanPindahPerorangan.php?PindahID=".$PindahID."&Mode=3&GID=$refresh&\">C3</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewPermohonanPindahPerorangan.php?PindahID=".$PindahID."&Mode=4&GID=$refresh&\">C4</a>&nbsp;</td>";
		
					
					echo "<td><a target=\"_blank\" href=\"PrintViewKK.php?PindahID=".$PindahID."&PersonID=".$per_ID."&GID=$refresh&\">Cetak<br>DataKeluarga</a>&nbsp;</td>";
						?>
					
					<td><a href="PersonView.php?PersonID=<?php echo $per_ID ?>"><?php echo $Nama ?></a>&nbsp;</td>
					<td><?php echo $TempatLahir . ", " . date2Ind($TanggalLahir,3) ; ?>&nbsp;</td>
					<td><?php echo $NamaAyah ." / ". $NamaIbu; ?>&nbsp;</td>
					<td><?php echo date2Ind($TanggalPindah,3); ?>&nbsp;</td>
					<td><?php 
					 if ($GerejaID>0){ 
					echo $NamaGereja;
					}else{ echo $NamaGerejaNonGKJ;
					}
					?>&nbsp;</td>
					<td><?php echo $AlasanPindah; ?>&nbsp;</td>
			
						<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=BaptisDewasa&AppPersonID=<?php echo $per_ID ?>&BaptisID=<?php echo $BaptisID ?>">X</a>&nbsp;</td>
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
elseif ($iMode == 21)
{

// echo "listing Permohonan Pindah Keluarga(attestasi)";
/**********************
**  Permohonan Pindah Keluarga Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT 	a.PindahKID as PindahKID,	
		a.fam_ID as NomorInduk,
		e.fam_Name as Nama,
		e.fam_Address1 as Alamat,
		e.fam_WorkPhone as Kelompok,

		CONCAT(IFNULL(a.NamaGerejaNonGKJ,' '),IFNULL(f.NamaGereja,' ')) as NamaGereja,
                a.TanggalPindah as TanggalPindah,
		g.c48 as AlasanPindah
		
		FROM PermohonanPindahKgkjbekti  a
		
	        left join family_fam e ON e.fam_id = a.fam_id 
		left join person_per c ON (e.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
		LEFT JOIN DaftarGerejaGKJ f ON a.GerejaID = f.GerejaID
		LEFT JOIN person_custom g ON c.per_ID = g.per_ID  ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "NomorInduk":
                        $sSQL = $sSQL . " ORDER BY NomorInduk DESC";
                        break;
                case "Nama":
                        $sSQL = $sSQL . " ORDER BY Nama DESC";	
                        break;
				case "NamaGereja":
                        $sSQL = $sSQL . " ORDER BY NamaGereja";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY PindahKID DESC,TanggalPindah Desc";
                        break;
        }

        $rsPindahCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsPindahCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PindahKeluargaEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Permohonan Pindah / Attestasi (Keluarga)") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Pindah">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="NomorInduk"><?php echo gettext("NomorInduk"); ?></option>
                        <option value="Nama"><?php echo gettext("Nama"); ?></option>
						<option value="NamaGereja"><?php echo gettext("NamaGereja"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Pindah">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=PindahK&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=PindahK&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=PindahK&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=PindahK&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=PindahK&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="Pindah">
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
	
				<td colspan="5" align="center"><?php echo gettext("Cetak"); ?></td>
			
				<td><?php echo gettext("Nama Keluarga"); ?></td>
			    <td><?php echo gettext("Alamat"); ?></td>
				<td><?php echo gettext("Kelompok"); ?></td>
				<td><?php echo gettext("Gereja Tujuan"); ?></td>
				<td><?php echo gettext("Tanggal Pindah"); ?></td>
				<td><?php echo gettext("Alasan Pindah"); ?></td>
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.

				$Nama = "";
				$Alamat = "";
				$Kelompok = "";
				$NamaGereja = ""; 
				$TanggalPindah = "";	
				$AlasanPindah = "";	

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PindahKeluargaEditor.php?PindahKID=<?php echo $PindahKID . "&FamilyID=".$NomorInduk."&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
               <?php 
					echo "<td><a target=\"_blank\" href=\"PrintViewPermohonanPindahKeluarga.php?FamilyID=".$NomorInduk."&PindahKID=".$PindahKID."&Mode=1&GID=$refresh&\">C1</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewPermohonanPindahKeluarga.php?FamilyID=".$NomorInduk."&PindahKID=".$PindahKID."&Mode=2&GID=$refresh&\">C2</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewPermohonanPindahKeluarga.php?FamilyID=".$NomorInduk."&PindahKID=".$PindahKID."&Mode=3&GID=$refresh&\">C3</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewPermohonanPindahKeluarga.php?FamilyID=".$NomorInduk."&PindahKID=".$PindahKID."&Mode=4&GID=$refresh&\">C4</a>&nbsp;</td>";
		
					
					echo "<td><a target=\"_blank\" href=\"PrintViewKK.php?PindahKID=".$PindahKID."&FamilyID=".$NomorInduk."&GID=$refresh&\">Cetak<br>DataKeluarga</a>&nbsp;</td>";
						?>
					
					<td><a href="FamilyView.php?FamilyID=<?php echo $NomorInduk ?>"><?php echo $Nama ?></a>&nbsp;</td>
					<td><?php echo $Alamat; ?>&nbsp;</td>
					<td><?php echo $Kelompok; ?>&nbsp;</td>
					<td><?php echo $NamaGereja; ?>&nbsp;</td>
					<td><?php echo $TanggalPindah; ?>&nbsp;</td>
					<td><?php echo $AlasanPindah; ?>&nbsp;</td>
			
						<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=PindahK&PindahKID=<?php echo $PindahKID ?>&BaptisID=<?php echo $BaptisID ?>">X</a>&nbsp;</td>
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


elseif ($iMode == 22)
{

// echo "listing Permohonan Penitipan Perawatan Rohani Warga (Perorangan)";
/**********************
**  Permohonan Titipan Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.*, 
		
		CONCAT(b.per_id,b.per_fam_id,b.per_gender,b.per_fmr_id) as NomorInduk,
		CONCAT(b.per_BirthYear,'-',b.per_BirthMonth,'-',b.per_BirthDay) as TanggalLahir,
		b.per_firstname as Nama , 
		b.per_WorkEmail as TempatLahir,
		
		c.per_firstname as NamaAyah,
		d.per_firstname as NamaIbu,
		f.NamaGereja as NamaGereja,
		g.c45 as AlasanTitip
		
		FROM PermohonanTitipgkjbekti  a
		
		LEFT JOIN person_per b ON a.per_ID = b.per_ID 
        left join family_fam e ON b.per_fam_id = e.fam_id 
		left join person_per c ON (e.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
		left join person_per d ON (e.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
		LEFT JOIN DaftarGerejaGKJ f ON a.GerejaID = f.GerejaID
		LEFT JOIN person_custom g ON a.per_ID = g.per_ID  ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Nama":
                        $sSQL = $sSQL . " ORDER BY per_Firstname DESC";
                        break;
                case "Keluarga":
                        $sSQL = $sSQL . " ORDER BY per_fam_id DESC";	
                        break;
				case "Kelompok":
                        $sSQL = $sSQL . " ORDER BY b.per_Workphone, b.per_firstname";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY a.TitipID Desc";
                        break;
        }

        $rsTitipCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsTitipCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"TitipPeroranganEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Permohonan Penitipan Perawatan Rohani") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Titip">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Nama"><?php echo gettext("Nama"); ?></option>
                        <option value="Keluarga"><?php echo gettext("Keluarga"); ?></option>
						<option value="Kelompok"><?php echo gettext("Kelompok"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Titip">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Titip&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=Titip&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Titip&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Titip&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Titip&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="Titip">
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
	
				<td colspan="4" align="center"><?php echo gettext("Cetak"); ?></td>
			
				<td><?php echo gettext("Nama"); ?></td>
				<td><?php echo gettext("Tempat/Tanggal lahir"); ?></td>
                <td><?php echo gettext("Nama Orang Tua"); ?></td>
			    <td><?php echo gettext("Tanggal Permohonan"); ?></td>
				<td><?php echo gettext("Gereja Tujuan"); ?></td>
				<td><?php echo gettext("Alasan Titip"); ?></td>
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$TitipID = "";
				$per_ID = "";
				$Nama = "";
				$TempatLahir = "";
				$TanggalLahir = "";
				$NamaAyah = ""; 
				$NamaIbu = "";	
				$KetuaMajelis = "";	
				$SekretarisMajelis = "";	
				$Pendeta = "";
				$NamaGereja = "";

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="TitipPeroranganEditor.php?TitipID=<?php echo $TitipID . "&PersonID=".$per_ID."&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
               <?php 
					echo "<td><a target=\"_blank\" href=\"PrintViewPermohonanTitipPerorangan.php?TitipID=".$TitipID."&Mode=1&GID=$refresh&\">C1</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewPermohonanTitipPerorangan.php?TitipID=".$TitipID."&Mode=2&GID=$refresh&\">C2</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewPermohonanTitipPerorangan.php?TitipID=".$TitipID."&Mode=3&GID=$refresh&\">C3</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewPermohonanTitipPerorangan.php?TitipID=".$TitipID."&Mode=4&GID=$refresh&\">C4</a>&nbsp;</td>";
		
					
				//	echo "<td><a target=\"_blank\" href=\"PrintViewKK.php?TitipID=".$TitipID."&PersonID=".$per_ID."\">Cetak<br>DataKeluarga</a>&nbsp;</td>";
						?>
					
					<td><a href="PersonView.php?PersonID=<?php echo $per_ID ?>"><?php echo $Nama ?></a>&nbsp;</td>
					<td><?php echo $TempatLahir . ", " . date2Ind($TanggalLahir,3) ; ?>&nbsp;</td>
					<td><?php echo $NamaAyah ." / ". $NamaIbu; ?>&nbsp;</td>
					<td><?php echo date2Ind($TanggalTitip,3); ?>&nbsp;</td>
					<td><?php echo $NamaGereja; ?>&nbsp;</td>
					<td><?php echo $AlasanTitip; ?>&nbsp;</td>
			
						<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=BaptisDewasa&AppPersonID=<?php echo $per_ID ?>&BaptisID=<?php echo $BaptisID ?>">X</a>&nbsp;</td>
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

elseif ($iMode == 23)
{

// echo "listing Pelayanan Tamu Sakramen Perjamuan";
/**********************
**  Pelayanan Tamu Sakramen Perjamuan Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.*,a.Telp as TelpTamu,  b.*, c.* 

		FROM PerjamuanKudusTamugkjbekti  a
		LEFT JOIN LokasiTI b ON a.KodeTI = b.KodeTI 
		LEFT JOIN DaftarGerejaGKJ c ON a.GerejaID = c.GerejaID";


         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Nama":
                        $sSQL = $sSQL . " ORDER BY Nama DESC";
                        break;
                case "Gereja":
                        $sSQL = $sSQL . " ORDER BY NamaGereja DESC";	
                        break;
				case "TempatIbadah":
                        $sSQL = $sSQL . " ORDER BY KodeTI ";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY PerjamuanID Desc";
                        break;
        }

        $rsTitipCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsTitipCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PerjamuanTamuEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Pelayanan Perjamuan Kudus untuk Tamu") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Perjamuan">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Nama"><?php echo gettext("Nama"); ?></option>
                        <option value="Gereja"><?php echo gettext("Gereja"); ?></option>
						<option value="TempatIbadah"><?php echo gettext("TempatIbadah"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Perjamuan">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Perjamuan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=Perjamuan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Perjamuan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Perjamuan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Perjamuan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="Perjamuan">
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
	
				<td colspan="4" align="center"><?php echo gettext("Cetak"); ?></td>
				<td><?php echo gettext("Email"); ?></td>
				<td><?php echo gettext("Nama"); ?></td>
				<td><?php echo gettext("Alamat"); ?></td>
				<td><?php echo gettext("Telepon"); ?></td>
				<td><?php echo gettext("Tempat/Tanggal Perjamuan"); ?></td>
                <td><?php echo gettext("Asal Gereja"); ?></td>
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$TitipID = "";
				$per_ID = "";
				$Nama = "";
				$TempatLahir = "";
				$TanggalLahir = "";
				$NamaAyah = ""; 
				$NamaIbu = "";	
				$KetuaMajelis = "";	
				$SekretarisMajelis = "";	
				$Pendeta = "";
				$NamaGereja = "";

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PerjamuanTamuEditor.php?PerjamuanID=<?php echo $PerjamuanID . "&PersonID=".$per_ID."&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
               <?php 
					echo "<td><a target=\"_blank\" href=\"PrintViewPerjamuanTamu.php?PerjamuanID=".$PerjamuanID."&Mode=1&GID=$refresh&\">C1</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewPerjamuanTamu.php?PerjamuanID=".$PerjamuanID."&Mode=2&GID=$refresh&\">C2</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewPerjamuanTamu.php?PerjamuanID=".$PerjamuanID."&Mode=3&GID=$refresh&\">C3</a>&nbsp;</td>";
					echo "<td><a target=\"_blank\" href=\"PrintViewPerjamuanTamu.php?PerjamuanID=".$PerjamuanID."&Mode=4&GID=$refresh&\">C4</a>&nbsp;</td>";
		
					?>
									<td><a href="SelectSendMail.php?mode=PerjamuanTamu&PerjamuanID=<?php echo $PerjamuanID ?>">
				<?php if ( $Email <> "" || $PFNIEmail <> "" || $EmailPendeta <> "" ){ echo "Email";} ?></a>&nbsp;
				
				

<?php
				//SELECT *  FROM `logger` WHERE `var1` LIKE '%Email%' ORDER BY `logger`.`count`  DESC
				//Check Tanggal Kirim Email : 
$sSQL2 = "SELECT date as TglKirim  FROM `logger` WHERE `var1` LIKE '%Email%' AND per_ID =".$PerjamuanID." ORDER BY `logger`.`count`  DESC
		 LIMIT 1";

// echo $sSQL2; 

$rsEmail2 = RunQuery($sSQL2);

$num_rows = mysql_num_rows($rsEmail2);
if ($num_rows > 0 ) {
extract(mysql_fetch_array($rsEmail2));
echo "<br>Dikirim Tgl:<br>";
echo "<font color=green >";
echo date('d-M-Y H:i:s', strtotime ($TglKirim));
echo "</font>";
//echo $TglKirim;
}else{

$exp_date = $TanggalPF; 
$todays_date = date("Y-m-d"); 

$today = strtotime($todays_date); 
$expiration_date = strtotime($exp_date); 

if ($expiration_date > $today) 
{ $valid = "yes"; 
	if ( $Email <> "" || $PFNIEmail <> "" || $EmailPendeta <> "" ){
		echo "<br><blink><font color=red >Belum Dikirim !</font></blink><br>";
	}
} else 
{ $valid = "no"; }

}
?>
				</td>
				
					<td><?php echo $Nama ?>&nbsp;</td>
					<td><?php echo $Alamat ?>&nbsp;</td>
					<td><?php echo $TelpTamu ?>&nbsp;</td>
					<td><?php echo $NamaTI.", ". date2Ind($TanggalPerjamuan,3).", ".$JamPerjamuan ?>&nbsp;</td>
					<td><?php echo $NamaGereja."".$NamaGerejaNonGKJ ?>&nbsp;</td>
				
						<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=Perjamuan&AppPersonID=<?php echo $per_ID ?>&PerjamuanID=<?php echo $PerjamuanID ?>">X</a>&nbsp;</td>
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


elseif ($iMode == 24)
{

// echo "listing Permohonan Pelayanan Nikah";
/**********************
**  Pelayanan Nikah Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT NikahID,
per_ID_L, per_ID_P, 	
a.PendetaID, l.NamaPendeta as NamaPendeta, KetuaMajelis, SekretarisMajelis,  	
TanggalNikah, WaktuNikah, a.TempatNikah as TmpNkh,
IF(a.TempatNikah=0,a.NikahGerejaNonGKJ,m.NamaGereja) as TempatNikah,
IF(a.TempatNikah=0,a.PendetaNikahGerejaNonGKJ,l.NamaPendeta) as PelayanPernikahan
, m.NamaGereja, 

IF(per_ID_L>0,b.per_FirstName,NamaLengkapL) as NamaLengkapL,
IF(per_ID_L>0,b.per_WorkEmail,TempatLahirL) as TempatLahirL, 
IF(per_ID_L>0,b.per_WorkEmail,TempatLahirL) as TempatLahirL, 

IF(per_ID_L>0,CONCAT(b.per_BirthYear,'-',b.per_BirthMonth,'-',b.per_BirthDay),TanggalLahirL) as TanggalLahirL,
IF(per_ID_L>0,c.c26,TempatBaptisL) as TempatBaptisAnakL, 
IF(per_ID_L>0,c.c1,TanggalBaptisL) as TanggalBaptisAnakL,
IF(per_ID_L>0,c.c37,PendetaBaptisL) as PendetaBaptisL,
IF(per_ID_L>0,c.c27,TempatSidhiL) as TempatSidhiL, 
IF(per_ID_L>0,c.c2,TanggalSidhiL) as TanggalSidhiL,
IF(per_ID_L>0,c.c38,PendetaSidhiL) as PendetaSidhiL,
IF(per_ID_L>0,c.c28,TempatBaptisDL) as TempatBaptisDewasaL, 
IF(per_ID_L>0,c.c18,TanggalBaptisDL) as TanggalBaptisDewasaL,
IF(per_ID_L>0,c.c39,PendetaBaptisDL) as PendetaBaptiisDewasaL,

IF(per_ID_L>0,IF(c.c16 is NULL,g.per_FirstName,c.c16),a.NamaAyahL) as NamaAyahL, 
IF(per_ID_L>0,IF(c.c17 is NULL,h.per_FirstName,c.c17),a.NamaIbuL) as NamaIbuL, 

NoSuratTitipanL as NoSuratTitipanLNW, 
IF(per_ID_L=0,IF(a.WargaGerejaL>0,n.NamaGereja,WargaGerejaNonGKJL),b.per_WorkPhone) as KelompokL,
WargaGerejaL as WargaGerejaLNW, WargaGerejaNonGKJL as WargaGerejaNonGKJLNW, AlamatGerejaNonGKJL as AlamatGerejaNonGKJLNW, 

IF(per_ID_P>0,d.per_FirstName,NamaLengkapP) as NamaLengkapP,
IF(per_ID_P>0,d.per_WorkEmail,TempatLahirP) as TempatLahirP, 

IF(per_ID_P>0,CONCAT(d.per_BirthYear,'-',d.per_BirthMonth,'-',d.per_BirthDay),TanggalLahirP) as TanggalLahirP,
IF(per_ID_P>0,e.c26,TempatBaptisP) as TempatBaptisAnakP, 
IF(per_ID_P>0,e.c1,TanggalBaptisP) as TanggalBaptisAnakP,
IF(per_ID_P>0,e.c37,PendetaBaptisP) as PendetaBaptisP,
IF(per_ID_P>0,e.c27,TempatSidhiP) as TempatSidhiP, 
IF(per_ID_P>0,e.c2,TanggalSidhiP) as TanggalSidhiP,
IF(per_ID_P>0,e.c38,PendetaSidhiP) as PendetaSidhiP,
IF(per_ID_P>0,e.c28,TempatBaptisDP) as TempatBaptisDewasaP, 
IF(per_ID_P>0,e.c18,TanggalBaptisDP) as TanggalBaptisDewasaP,
IF(per_ID_P>0,e.c39,PendetaBaptisDp) as PendetaBaptisDewasaP,

IF(per_ID_P>0,IF(e.c16 is NULL,j.per_FirstName,e.c16),a.NamaAyahP) as NamaAyahP, 
IF(per_ID_P>0,IF(e.c17 is NULL,k.per_FirstName,e.c17),a.NamaIbuP) as NamaIbuP, 

NoSuratTitipanP as NoSuratTitipanPNW, 
IF(per_ID_P=0,IF(a.WargaGerejaP>0,o.NamaGereja,WargaGerejaNonGKJP),d.per_WorkPhone) as KelompokP,
WargaGerejaP as WargaGerejaPNW, WargaGerejaNonGKJP as WargaGerejaNonGKJPNW, AlamatGerejaNonGKJP as AlamatGerejaNonGKJPNW 


FROM PermohonanNikahgkjbekti a 
LEFT JOIN person_per b ON a.per_ID_L = b.per_ID 
LEFT JOIN person_custom c ON a.per_ID_L = c.per_ID 

LEFT JOIN person_per d ON a.per_ID_P = d.per_ID 
LEFT JOIN person_custom e ON a.per_ID_P = e.per_ID 

LEFT JOIN family_fam f ON b.per_fam_id = f.fam_id 
LEFT JOIN person_per g ON (f.fam_id = g.per_fam_id AND g.per_fmr_id = 1 AND g.per_gender = 1)
LEFT JOIN person_per h ON (f.fam_id = h.per_fam_id AND h.per_fmr_id = 2 AND h.per_gender = 2)

LEFT JOIN family_fam i ON d.per_fam_id = i.fam_id 
LEFT JOIN person_per j ON (i.fam_id = j.per_fam_id AND j.per_fmr_id = 1 AND j.per_gender = 1)
LEFT JOIN person_per k ON (i.fam_id = k.per_fam_id AND k.per_fmr_id = 2 AND k.per_gender = 2)

LEFT JOIN DaftarPendeta l ON a.PendetaID = l.PendetaID
LEFT JOIN DaftarGerejaGKJ m ON a.TempatNikah = m.GerejaID

LEFT JOIN DaftarGerejaGKJ n ON a.WargaGerejaL = n.GerejaID
LEFT JOIN DaftarGerejaGKJ o ON a.WargaGerejaP = o.GerejaID
";


         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "NamaL":
                        $sSQL = $sSQL . " ORDER BY NamaLengkapL ASC";
                        break;
                case "NamaP":
                        $sSQL = $sSQL . " ORDER BY NamaLengkapL ASC";	
                        break;
				case "TempatIbadah":
                        $sSQL = $sSQL . " ORDER BY TempatNikah ";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY TanggalNikah Desc, NikahID Desc";
                        break;
        }

        $rsTitipCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsTitipCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"NikahEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Pelayanan Pernikahan dan Pemberkatan Perkawinan") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Nikah">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="NamaL"><?php echo gettext("Nama Calon Laki2"); ?></option>
                        <option value="NamaP"><?php echo gettext("Nama Calon Perempuan"); ?></option>
						<option value="TempatIbadah"><?php echo gettext("TempatIbadah"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Nikah">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Nikah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=Nikah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Nikah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Nikah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Nikah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="Nikah">
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
	
				<td colspan="1" align="center"><?php echo gettext("Cetak"); ?></td>
			
				<td><?php echo gettext("Tempat/Tanggal Nikah"); ?></td>
				<td><?php echo gettext("Nama Pemohon"); ?></td>
				<td><?php echo gettext("Nama OrangTua"); ?></td>
				<td><?php echo gettext("Tempat/Tgl Lahir"); ?></td>
				<td><?php echo gettext("Tempat/Tgl BaptisAnak"); ?></td>
				<td><?php echo gettext("Tempat/Tgl Sidhi"); ?></td>
				<td><?php echo gettext("Tempat/Tgl BaptisDewasa"); ?></td>
                
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="NikahEditor.php?NikahID=<?php echo $NikahID . "&PersonID=".$per_ID."&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
				<?php 
					if ($TmpNkh<>1){echo "<td>SuratPengantar:<br>";
					echo "<a target=\"_blank\" href=\"PrintViewNikahTitipan.php?NikahID=".$NikahID."&Mode=1&GID=$refresh&\">C1</a>&nbsp;";
					echo "<a target=\"_blank\" href=\"PrintViewNikahTitipan.php?NikahID=".$NikahID."&Mode=2&GID=$refresh&\">C2</a>&nbsp;";
					echo "<a target=\"_blank\" href=\"PrintViewNikahTitipan.php?NikahID=".$NikahID."&Mode=3&GID=$refresh&\">C3</a>&nbsp;";
					echo "<a target=\"_blank\" href=\"PrintViewNikahTitipan.php?NikahID=".$NikahID."&Mode=4&GID=$refresh&\">C4</a>&nbsp;";
					echo "</td>";
					
					}else{echo "<td><a target=\"_blank\" href=\"PrintViewNikah.php?NikahID=".$NikahID."&Mode=4&GID=$refresh&\">Sertifikat</a>&nbsp;
					<br><a target=\"_blank\" href=\"PrintViewNikahBeritaAcara.php?NikahID=".$NikahID."&Mode=4&GID=$refresh&\">BeritaAcara</a>&nbsp;</td>";}
					?>
					
					<td><?php echo $TempatNikah."<br>".date2Ind($TanggalNikah,4)."<br> pk.".$WaktuNikah."<br>".$PelayanPernikahan ?>&nbsp;</td>
					<td>
					Calon Laki2 :<br> 
					<?php if($per_ID_L>0){
					echo "<b><a target=\"_blank\" href=\"PersonView.php?PersonID=".$per_ID_L."\">".$NamaLengkapL."</a></b><br> (".$KelompokL.")<br>";}else{
					echo "<b>".$NamaLengkapL."</b><br>(".$KelompokL.")<br>";}
					echo "<hr>";
					echo "Calon Perempuan: <br>";
					if($per_ID_P>0){
					echo "<b><a target=\"_blank\" href=\"PersonView.php?PersonID=".$per_ID_P."\">".$NamaLengkapP."</a></b><br> (".$KelompokP.")<br>";}else{
					echo "<b>".$NamaLengkapP."</b><br>(".$KelompokP.")<br>";}
					
					?></td>
					<td>
					<?php echo "(Ayah) ".$NamaAyahL."<br>(Ibu) ".$NamaIbuL."" ?><br><hr>
					<?php echo "(Ayah) ".$NamaAyahP."<br>(Ibu) ".$NamaIbuP."" ?><br>
					</td>
					<td>
					<?php echo $TempatLahirL."<br>".date2Ind($TanggalLahirL,3) ?><br><hr>
					<?php echo $TempatLahirP."<br>".date2Ind($TanggalLahirP,3) ?><br>
					</td>
					<td>
					<?php echo $TempatBaptisAnakL."<br>".date2Ind($TanggalBaptisAnakL,3)."<br>".$PendetaBaptisL ?><br><hr>
					<?php echo $TempatBaptisAnakP."<br>".date2Ind($TanggalBaptisAnakP,3)."<br>".$PendetaBaptisP ?>
					</td>
					<td>
					<?php echo $TempatSidhiL."<br>".date2Ind($TanggalSidhiL,3)."<br>".$PendetaSidhiL ?><br><hr>
					<?php echo $TempatSidhiP."<br>".date2Ind($TanggalSidhiP,3)."<br>".$PendetaSidhiP ?>
					</td>
					<td>
					<?php echo $TempatBaptisDewasaL."<br>".date2Ind($TanggalBaptisDewasaL,3)."<br>".$PendetaBaptisDewasaL ?><br><hr>
					<?php echo $TempatBaptisDewasaP."<br>".date2Ind($TanggalBaptisDewasaP,3)."<br>".$PendetaBaptisDewasaP ?>
					</td>
				
						<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=Nikah&AppPersonID=<?php echo $per_ID ?>&NikahID=<?php echo $NikahID ?>">X</a>&nbsp;</td>
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

elseif ($iMode == 25)
{

// echo "listing Permohonan Pelayanan Pemakaman";
/**********************
**  Pelayanan Pemakaman Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT		a.*, b.*, CONCAT(b.per_id,b.per_fam_id,b.per_gender,b.per_fmr_id) as NomorInduk,
		a.TanggalMeninggal as TanggalMeninggal,
		b.per_BirthMonth as BlnLahir, b.per_BirthDay as TglLahir, b.per_BirthYear as ThnLahir,
		e.fam_Name as NamaKeluarga, 
		fmr.lst_OptionName AS sFamRole,
		IF(a.per_ID>0,b.per_firstname,a.Nama) as NamaLengkap , 
		IF(a.per_ID=0,IF(a.GerejaID>0,f.NamaGereja,NamaGerejaNonGKJ),b.per_WorkPhone) as Kelompok,
		a.TempatPemakaman as TempatPemakaman,
                a.TanggalPemakaman as TanggalPemakaman,
				f.NamaGereja as NamaGereja
		
		FROM PermohonanPemakamangkjbekti  a
		
		LEFT JOIN person_per b ON a.per_ID = b.per_ID 
        left join family_fam e ON b.per_fam_id = e.fam_id 
		left join person_per c ON (e.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
		left join person_per d ON (e.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
		LEFT JOIN DaftarGerejaGKJ f ON a.TempatSemayam = f.GerejaID
		LEFT JOIN person_custom g ON a.per_ID = g.per_ID 
		LEFT JOIN list_lst fmr ON b.per_fmr_ID = fmr.lst_OptionID AND fmr.lst_ID = 2
";


         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Nama":
                        $sSQL = $sSQL . " ORDER BY NamaLengkapL ASC";
                        break;
				case "TempatIbadah":
                        $sSQL = $sSQL . " ORDER BY NamaGereja ";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY TanggalMeninggal Desc, MeninggalID Desc";
                        break;
        }

        $rsTitipCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsTitipCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"MeninggalEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Permohonan Pelayanan Pemakaman") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Meninggal">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Nama"><?php echo gettext("Nama"); ?></option>
						<option value="TempatIbadah"><?php echo gettext("TempatIbadah"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Meninggal">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Meninggal&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=Meninggal&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Meninggal&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Meninggal&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=Meninggal&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="Meninggal">
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
	
				<td colspan="1" align="center"><?php echo gettext("Cetak"); ?></td>
			
				<td><?php echo gettext("Tanggal Meninggal"); ?></td>
				<td><?php echo gettext("Nama Lengkap<br>Almarhum/Almarhumah"); ?></td>
				<td><?php echo gettext("Nama Keluarga"); ?></td>
				<td><?php echo gettext("Warga"); ?></td>
				<td><?php echo gettext("Tempat/Tgl Pemakaman"); ?></td>
				<td><?php echo gettext("Gereja yg Melayani"); ?></td>
               
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="MeninggalEditor.php?MeninggalID=<?php echo $MeninggalID . "&PersonID=".$per_ID."&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
				<?php 
					if ($TmpNkh<>1){echo "<td>SuratPengantar:<br>";
					echo "<a target=\"_blank\" href=\"PrintViewMeninggal.php?MeninggalID=".$MeninggalID."&Mode=1&GID=$refresh&\">C1</a>&nbsp;";
					echo "<a target=\"_blank\" href=\"PrintViewMeninggal.php?MeninggalID=".$MeninggalID."&Mode=2&GID=$refresh&\">C2</a>&nbsp;";
					echo "<a target=\"_blank\" href=\"PrintViewMeninggal.php?MeninggalID=".$MeninggalID."&Mode=3&GID=$refresh&\">C3</a>&nbsp;";
					echo "<a target=\"_blank\" href=\"PrintViewMeninggal.php?MeninggalID=".$MeninggalID."&Mode=4&GID=$refresh&\">C4</a>&nbsp;";
					echo "</td>";
					
					}else{echo "<td><a target=\"_blank\" href=\"PrintViewMeninggal.php?NikahID=".$MeninggalID."&Mode=4&GID=$refresh&\">Sertifikat</a>&nbsp;
					<br><a target=\"_blank\" href=\"PrintViewMeninggalBeritaAcara.php?NikahID=".$MeninggalID."&Mode=4&GID=$refresh&\">BeritaAcara</a>&nbsp;</td>";}
					?>
					
					<td><?php echo "".date2Ind($TanggalMeninggal,1); ?><br>Umur : <?php echo "". FormatAgeRip($BlnLahir,$TglLahir,$ThnLahir,$TanggalMeninggal) ?></td>
					<td>
					<?
					echo "<b><a target=\"_blank\" href=\"PersonView.php?PersonID=".$per_ID."\">".$NamaLengkap."</a></b>";
					?></td>
					<td>
					<?php 
					if ($sFamRole != "") { echo $sFamRole; } else { echo gettext("Anggota"); }; 
					echo " dari <br>Kelg. ".$NamaKeluarga; ?>
					</td>
					<td>
					<?php echo $Kelompok; ?>
					</td>
					<td>
					<?php echo date2Ind($TanggalPemakaman,1)." pk.".$WaktuPemakaman."<br><hr>".$TempatPemakaman; ?>
					</td>
					<td>
					<?php echo $NamaGereja ;?>	</td>
									
						<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=Meninggal&AppPersonID=<?php echo $per_ID ?>&MeninggalID=<?php echo $MeninggalID ?>">X</a>&nbsp;</td>
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

elseif ($iMode == 26)
{
//echo "listing mailout ";
/**********************
**  Mail Out Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.* ,c.* , IF(Dari=0,'SEKR',replace( `vol_Name` , 'Ketua', '' )) AS KodePengirim FROM SuratKeluar a
		LEFT JOIN volunteeropportunity_vol b ON a.Dari = b.vol_ID 
		LEFT JOIN KlasifikasiSurat c ON a.ket3 = c.KlasID";

        //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Dari":
                        $sSQL = $sSQL . " ORDER BY Dari DESC";
                        break;
                case "Institusi":
                        $sSQL = $sSQL . " ORDER BY Institusi, Dari";
                        break;
                default:
                        $sSQL = $sSQL . " ORDER BY Tanggal Desc";
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"MailOutEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Surat Keluar Baru") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Mail">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Dari"><?php echo gettext("Pengirim"); ?></option>
						<option value="Kepada"><?php echo gettext("Kepada"); ?></option>
                        <option value="Institusi"><?php echo gettext("Institusi"); ?></option>
						<option value="Hal"><?php echo gettext("Hal"); ?></option>
						<option value="TanggalSurat"><?php echo gettext("Tanggal Surat"); ?></option>
						<option value="Ket1"><?php echo gettext("Tanggal Terima"); ?></option>
						<option value="FollowUp"><?php echo gettext("FollowUp"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="mail">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=mailout&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=mailout&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=mailout&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=mailout&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=mailout&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="mail">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';
        //        if(isset($sLetter))
         //               echo '<input type="hidden" name="Letter" value="' . $sLetter . '">';

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
				<td><?php echo gettext("MailID"); ?></td>
				<td><?php echo gettext("Tanggal Kirim"); ?></td>
				<td><?php echo gettext("NomorSurat"); ?></td>
                <td><?php echo gettext("Pengirim"); ?></td>
                <td><?php echo gettext("Tujuan"); ?></td>
                <td><?php echo gettext("Institusi"); ?></td>
                <td><?php echo gettext("Hal"); ?></td>
                <td><?php echo gettext("Urgensi"); ?></td>
				<td><?php echo gettext("Sifat"); ?></td>
				<td><?php echo gettext("Kategori"); ?></td>
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
                $Tanggal = "";
                $Ket1 = "";
                $NomorSurat = "";
                $Dari = "";
                $Institusi = "";
                $Kepada = "";
                $Hal = "";
                $Urgensi = "";
				$FollowUp = "";
				$Ket3 = "";

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="MailOutEditor.php?MailID=<?php echo $MailID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="MailOutView.php?MailID=<?php echo $MailID ?>"><?php echo "View" ?></a>&nbsp;</td>
						<td><?php echo $Tanggal ?>&nbsp;</td>
						
						<td><?php 
						$time  = strtotime($Tanggal);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat =  $MailID."e/MG-".$KodePengirim."/".$sChurchCode."/".dec2roman($month)."/".$year;
						
						echo $NomorSurat ;?></td>
						
						<td><?php echo $KodePengirim ?>&nbsp;</td>
						<td><?php echo $Kepada ?>&nbsp;</td>
						<td><?php echo $Institusi ?>&nbsp;</td>
						<td><?php echo $Hal ?>&nbsp;</td>
						<td><?php 
							switch ($Urgensi)
							{
							case 1:
								echo gettext("<font color=\"red\"><b>Sangat Segera</b></font>");
								break;
							case 2:
								echo gettext("<b>Segera</b>");
								break;
							case 3:
								echo gettext("Biasa");
								break;
							}			
						?>&nbsp;</td>
						<td><?php echo $Via ?>&nbsp;</td>
						<td><?php echo $Deskripsi ;	
						?>&nbsp;</td>
						

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


elseif ($iMode == 27)
{
//echo "listing sukep ";
/*****************************
**  Daftar Surat Keputusan  **
******************************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.* FROM SuratKeputusan a";

        //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
                case "Hal":
                        $sSQL = $sSQL . " ORDER BY Hal, Dari";
                        break;
                case "TanggalExp":
                        $sSQL = $sSQL . " ORDER BY TanggalExp DESC";
                        break;						
                default:
                        $sSQL = $sSQL . " ORDER BY Tanggal Desc, SKID Desc";
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"SuKepEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Surat Keputusan Baru") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Mail">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
						<option value="Hal"><?php echo gettext("Hal"); ?></option>
                        <option value="TanggalExp"><?php echo gettext("TanggalExp"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="sukep">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=sukep&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=sukep&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=sukep&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=sukep&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=sukep&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="mail">
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
				<td><?php echo gettext("SK ID"); ?></td>
				<td><?php echo gettext("Tanggal"); ?></td>
				<td><?php echo gettext("Tanggal Expired"); ?></td>				
				<td><?php echo gettext("NomorSurat"); ?></td>
                <td><?php echo gettext("Hal"); ?></td>
                <td><?php echo gettext("Urgensi"); ?></td>

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
                $Tanggal = "";
                $Ket1 = "";
                $NomorSurat = "";
                $Dari = "";
                $Institusi = "";
                $Kepada = "";
                $Hal = "";
                $Urgensi = "";
				$FollowUp = "";
				$Ket3 = "";

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="SuKepEditor.php?SKID=<?php echo $SKID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="SuKepView.php?SKID=<?php echo $SKID ?>"><?php echo "View" ?></a>&nbsp;</td>
						<td><?php echo date2ind($Tanggal,2) ?>&nbsp;</td>
						<td><?php echo date2ind($TanggalExp,2) ?>&nbsp;</td>						
						<td><?php 
						$time  = strtotime($Tanggal);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						
						$NomorSurat2 =  $SKID."/MG/SK/".$NomorSurat."/".$sChurchCode."/".dec2roman($month)."/".$year;
						if ($SKID == 0){ echo $NomorSurat; }else{	echo $NomorSurat2 ;}?></td>
						
						<td><?php echo $Hal ?>&nbsp;</td>
						<td><?php 
							switch ($Urgensi)
							{
							case 1:
								echo gettext("<font color=\"red\"><b>Sangat Segera</b></font>");
								break;
							case 2:
								echo gettext("<b>Segera</b>");
								break;
							case 3:
								echo gettext("Biasa");
								break;
							}			
						?>&nbsp;</td>
						
						

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

elseif ($iMode == 28)
{
//echo "listing Klas Surat ";
/*****************************
**  Daftar Lasifikasi Surat **
******************************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.* FROM KlasifikasiSurat a";

        //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Keterangan":
                        $sSQL = $sSQL . " ORDER BY Keterangan ASC";
                        break;
                case "Deskripsi":
                        $sSQL = $sSQL . " ORDER BY Deskripsi ASC";
                        break;
                case "Aktif":
                        $sSQL = $sSQL . " ORDER BY Enable , KlasID ASC";
                        break;						
                default:
                        $sSQL = $sSQL . " ORDER BY KlasID ASC";
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"KlasifikasiSuratEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Klasifikasi Surat Baru") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="klassurat">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Aktif"><?php echo gettext("Aktif"); ?></option>
						<option value="Deskripsi"><?php echo gettext("Deskripsi"); ?></option>
                        <option value="Keterangan"><?php echo gettext("Keterangan"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="klassurat">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=klassurat&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=klassurat&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=klassurat&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=klassurat&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=klassurat&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="mail">
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
				<td align="center"><?php echo gettext("Aktif/Tidak Aktif?"); ?></td>	
				<td align="center"><?php echo gettext("Klas ID"); ?></td>
				<td><?php echo gettext("Deskripsi"); ?></td>
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
        //Loop through the Mail recordset
        while ($aRow = mysql_fetch_array($rsMail))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
                $KlasID = "";
                $Deskripsi = "";
                $Keterangan = "";
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="KlasifikasiSuratEditor.php?KlasID=<?php echo $KlasID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
						<td align="center"><?php 
							switch ($Enable)
							{
							case 0:
								echo gettext("Tidak Aktif");
								break;
							case 1:
								echo gettext("<font color=\"red\"><b>Aktif</b></font>");
								break;
							}			
						?>&nbsp;</td>
						<td align="center"><?php echo $KlasID ?>&nbsp;</td>
						<td><?php echo $Deskripsi ?>&nbsp;</td>		
						<td><?php echo $Keterangan ?>&nbsp;</td>							

					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=KlasSurat&KlasID=<?php echo $KlasID ?>">X</a>&nbsp;</td>
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


elseif ($iMode == 29)
{
//echo "listing beritaacara ";
/*****************************
**  Daftar Surat Berita Acara  **
******************************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.* FROM BeritaAcara a";

        //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC";
                        break;
                case "Hal":
                        $sSQL = $sSQL . " ORDER BY Hal, Dari";
                        break;
                case "TanggalExp":
                        $sSQL = $sSQL . " ORDER BY TanggalExp DESC";
                        break;						
                default:
                        $sSQL = $sSQL . " ORDER BY Tanggal Desc, BAID Desc";
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"BeritaAcaraEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Berita Acara Baru") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Mail">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
						<option value="Hal"><?php echo gettext("Hal"); ?></option>
                        <option value="TanggalExp"><?php echo gettext("TanggalExp"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="beritaacara">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=beritaacara&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=beritaacara&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=beritaacara&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=beritaacara&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=beritaacara&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="mail">
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
				<td><?php echo gettext("BA ID"); ?></td>
				<td><?php echo gettext("Tanggal"); ?></td>
				<td><?php echo gettext("Tanggal Expired"); ?></td>				
				<td><?php echo gettext("NomorSurat"); ?></td>
                <td><?php echo gettext("Hal"); ?></td>
                <td><?php echo gettext("Urgensi"); ?></td>

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
                $Tanggal = "";
                $Ket1 = "";
                $NomorSurat = "";
                $Dari = "";
                $Institusi = "";
                $Kepada = "";
                $Hal = "";
                $Urgensi = "";
				$FollowUp = "";
				$Ket3 = "";

				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="BeritaAcaraEditor.php?BAID=<?php echo $BAID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="BeritaAcaraView.php?BAID=<?php echo $BAID ?>"><?php echo "View" ?></a>&nbsp;</td>
						<td><?php echo date2ind($Tanggal,2) ?>&nbsp;</td>
						<td><?php echo date2ind($TanggalExp,2) ?>&nbsp;</td>						
						<td><?php 
						$time  = strtotime($Tanggal);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						
						$NomorSurat2 =  $BAID."/MG/BA/".$NomorSurat."/".$sChurchCode."/".dec2roman($month)."/".$year;
						if ($BAID == 0){ echo $NomorSurat; }else{	echo $NomorSurat2 ;}?></td>
						
						<td><?php echo $Hal ?>&nbsp;</td>
						<td><?php 
							switch ($Urgensi)
							{
							case 1:
								echo gettext("<font color=\"red\"><b>Sangat Segera</b></font>");
								break;
							case 2:
								echo gettext("<b>Segera</b>");
								break;
							case 3:
								echo gettext("Biasa");
								break;
							}			
						?>&nbsp;</td>
						
						

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
elseif ($iMode == 30)
{
//echo "listing Master Bidang ";
/*****************************
**  Daftar Bidang **
******************************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.* FROM MasterBidang a";

        //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Keterangan":
                        $sSQL = $sSQL . " ORDER BY Keterangan ASC";
                        break;
                case "Deskripsi":
                        $sSQL = $sSQL . " ORDER BY Deskripsi ASC";
                        break;
                case "Aktif":
                        $sSQL = $sSQL . " ORDER BY Enable , BidangID ASC";
                        break;						
                default:
                        $sSQL = $sSQL . " ORDER BY BidangID ASC";
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"MasterBidangEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Master Bidang Baru") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="masterbidang">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Aktif"><?php echo gettext("Aktif"); ?></option>
						<option value="Deskripsi"><?php echo gettext("Deskripsi"); ?></option>
                        <option value="Keterangan"><?php echo gettext("Keterangan"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="masterbidang">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=masterbidang&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=masterbidang&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=masterbidang&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=masterbidang&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=masterbidang&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="mail">
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
				<td align="center"><?php echo gettext("Aktif/Tidak Aktif?"); ?></td>	
				<td align="center"><?php echo gettext("Kode Bidang"); ?></td>
				<td><?php echo gettext("Nama Bidang"); ?></td>
				<td><?php echo gettext("Keterangan"); ?></td>	
				<td><?php echo gettext("Kelompok"); ?></td>
				<td><?php echo gettext("KetKelompok"); ?></td>
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
                $BidangID = "";
                $Deskripsi = "";
                $Keterangan = "";
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="MasterBidangEditor.php?BidangID=<?php echo $BidangID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
						<td align="center"><?php 
							switch ($Enable)
							{
							case 0:
								echo gettext("Tidak Aktif");
								break;
							case 1:
								echo gettext("<font color=\"red\"><b>Aktif</b></font>");
								break;
							}			
						?>&nbsp;</td>
						<td align="center"><?php echo $KodeBidang ?>&nbsp;</td>
						<td><?php echo $NamaBidang ?>&nbsp;</td>		
						<td><?php echo $Keterangan ?>&nbsp;</td>	
						<td><?php echo $Kelompok ?>&nbsp;</td>
						<td><?php echo $KetKelompok ?>&nbsp;</td>						

					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=MasterBidang&BidangID=<?php echo $BidangID ?>">X</a>&nbsp;</td>
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



}elseif ($iMode == 31)
{
//echo "listing Master Komisi ";
/*****************************
**  Daftar Komisi **
******************************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.*, a.Keterangan as KetKomisi, b.* FROM MasterKomisi a
		LEFT JOIN MasterBidang b ON a.BidangID=b.BidangID
		";

        //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Bidang":
                        $sSQL = $sSQL . " ORDER BY BidangID ASC";
                        break;
                case "Komisi":
                        $sSQL = $sSQL . " ORDER BY KomisiID ASC";
                        break;
                case "Aktif":
                        $sSQL = $sSQL . " ORDER BY Enable , KomisiID ASC";
                        break;						
                default:
                        $sSQL = $sSQL . " ORDER BY  b.BidangID, a.KomisiID ";
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"MasterKomisiEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Master Komisi Baru") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="masterkomisi">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Aktif"><?php echo gettext("Aktif"); ?></option>
						<option value="Deskripsi"><?php echo gettext("Deskripsi"); ?></option>
                        <option value="Keterangan"><?php echo gettext("Keterangan"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="masterkomisi">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=masterkomisi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=masterkomisi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=masterkomisi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=masterkomisi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=masterkomisi&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="mail">
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
				<td align="center"><?php echo gettext("Aktif/Tidak Aktif?"); ?></td>	
				<td align="center"><?php echo gettext("Kode Komisi"); ?></td>
				<td><?php echo gettext("Nama Komisi"); ?></td>
				<td><?php echo gettext("BidangID"); ?></td>	
				<td><?php echo gettext("KodeBidang"); ?></td>
				<td><?php echo gettext("Nama Bidang"); ?></td>
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
        //Loop through the Mail recordset
        while ($aRow = mysql_fetch_array($rsMail))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
                $KomisiID = "";
                $NamaKomisi = "";
                $Keterangan = "";
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="MasterKomisiEditor.php?KomisiID=<?php echo $KomisiID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
						<td align="center"><?php 
							switch ($Enable)
							{
							case 0:
								echo gettext("Tidak Aktif");
								break;
							case 1:
								echo gettext("<font color=\"red\"><b>Aktif</b></font>");
								break;
							}			
						?>&nbsp;</td>
						<td align="center"><?php echo $KodeKomisi ?>&nbsp;</td>
						<td><?php echo $NamaKomisi ?>&nbsp;</td>		
						<td><?php echo $BidangID ?>&nbsp;</td>	
						<td><?php echo $KodeBidang ?>&nbsp;</td>	
						<td><?php echo $NamaBidang ?>&nbsp;</td>
						<td><?php echo $KetKomisi ?>&nbsp;</td>						

					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=MasterKomisi&KomisiID=<?php echo $KomisiID ?>">X</a>&nbsp;</td>
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



}elseif ($iMode == 32)
{
//echo "listing Master Pos Anggaran ";
/*****************************
**  Daftar Master Pos Anggaran **
******************************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.*, b.*, c.* FROM MasterPosAnggaran a
		LEFT JOIN MasterKomisi b ON a.KomisiID=b.KomisiID
		LEFT JOIN MasterBidang c ON b.BidangID=c.BidangID
		";

        //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Bidang":
                        $sSQL = $sSQL . " ORDER BY BidangID ASC";
                        break;
                case "Komisi":
                        $sSQL = $sSQL . " ORDER BY KomisiID ASC";
                        break;
                case "Aktif":
                        $sSQL = $sSQL . " ORDER BY Enable , KomisiID ASC";
				        break;						
                case "MataAnggaran":
                        $sSQL = $sSQL . " ORDER BY  c.BidangID, b.KomisiID, a.PosAnggaranID ";
				        break;						
                
				default:
                        $sSQL = $sSQL . " ORDER BY  c.BidangID, b.KomisiID, a.PosAnggaranID ";
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"MasterPosAnggaranEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Master Pos Anggaran Baru") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="masterposangg">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Aktif"><?php echo gettext("Aktif"); ?></option>
						<option value="Bidang"><?php echo gettext("Bidang"); ?></option>
                        <option value="Komisi"><?php echo gettext("Komisi"); ?></option>
						<option value="MataAnggaran"><?php echo gettext("MataAnggaran"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="masterposangg">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=masterposangg&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=masterposangg&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=masterposangg&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=masterposangg&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=masterposangg&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="masterposangg">
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
				<td align="center"><?php echo gettext("Aktif/Tidak Aktif?"); ?></td>	
				<td align="center"><?php echo gettext("KodePosAngg"); ?></td>
				<td><?php echo gettext("Nama Pos Anggaran"); ?></td>
				<td align="center"><?php echo gettext("Bidang"); ?></td>	
				<td align="center"><?php echo gettext("Komisi"); ?></td>
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
        //Loop through the Mail recordset
        while ($aRow = mysql_fetch_array($rsMail))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
                $KomisiID = "";
                $NamaKomisi = "";
                $Keterangan = "";
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>"> 
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="MasterPosAnggaranEditor.php?PosAnggaranID=<?php echo $PosAnggaranID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
						<td align="center"><?php 
							switch ($Enable)
							{
							case 0:
								echo gettext("Tidak Aktif");
								break;
							case 1:
								echo gettext("<font color=\"red\"><b>Aktif</b></font>");
								break;
							}			
						?>&nbsp;</td>
						<td><?php echo $PosAnggaranID ?>&nbsp;</td>
						<td><?php echo $NamaPosAnggaran ?>&nbsp;</td>
						<td align="center"><?php echo $BidangID."-".$KodeBidang."-".$NamaBidang; ?>&nbsp;</td>
						<td align="center"><?php echo $KomisiID."-".$KodeKomisi."-".$NamaKomisi; ?>&nbsp;</td>

						<td><?php echo $Keterangan ?>&nbsp;</td>						

					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=MasterPosAnggaran&PosAnggaranID=<?php echo $PosAnggaranID ?>">X</a>&nbsp;</td>
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



}elseif ($iMode == 33)
{

// echo "listing Permohonan Pelayanan Pembaruan Penyegaran Janji Perkawinan";
/**********************
**  Pelayanan Pembaruan Penyegaran Janji Perkawinan Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL

$sSQL = "SELECT NikahID,
per_ID_L, per_ID_P, 	
a.PendetaID, l.NamaPendeta as PelayanPernikahan, KetuaMajelis, SekretarisMajelis,  	
TanggalNikah, WaktuNikah, a.TempatNikah as TmpNkh,
m.NamaGereja as NamaGereja,
m.Alamat1 as Alamat1Gereja,
b.fam_WorkPhone as Kelompok, b.fam_WeddingDate as TanggalMenikah, a.fam_ID as FamilyID,

g.per_FirstName as NamaLengkapL,g.per_WorkEmail as TempatLahirL,
CONCAT(g.per_BirthYear,'-',g.per_BirthMonth,'-',g.per_BirthDay) as TanggalLahirL,
i.c26 as TempatBaptisAnakL, 
i.c1 as TanggalBaptisAnakL,
i.c37 as PendetaBaptisL,
i.c27 as TempatSidhiL, 
i.c2 as TanggalSidhiL,
i.c38 as PendetaSidhiL,
i.c28 as TempatBaptisDewasaL, 
i.c18 as TanggalBaptisDewasaL,
i.c39 as PendetaBaptisDewasaL,
i.c16 as NamaAyahL, 
i.c17 as NamaIbuL, 

h.per_FirstName as NamaLengkapP,h.per_WorkEmail as TempatLahirP,
CONCAT(h.per_BirthYear,'-',h.per_BirthMonth,'-',h.per_BirthDay) as TanggalLahirP,
j.c26 as TempatBaptisAnakP,
j.c1 as TanggalBaptisAnakP,
j.c37 as PendetaBaptisP,
j.c27 as TempatSidhiP,
j.c2 as TanggalSidhiP,
j.c38 as PendetaSidhiP,
j.c28 as TempatBaptisDewasaP,
j.c18 as TanggalBaptisDewasaP,
j.c39 as PendetaBaptisDewasaP,
j.c16 as NamaAyahP, 
j.c17 as NamaIbuP

FROM PermohonanPenyegaranJanjiNikah a 

LEFT JOIN family_fam b ON a.fam_id = b.fam_id 
LEFT JOIN person_per g ON (a.fam_id = g.per_fam_id AND g.per_fmr_id = 1 AND g.per_gender = 1)
LEFT JOIN person_custom i ON g.per_id = i.per_id
LEFT JOIN person_per h ON (a.fam_id = h.per_fam_id AND h.per_fmr_id = 2 AND h.per_gender = 2)
LEFT JOIN person_custom j ON h.per_id = j.per_id
LEFT JOIN DaftarPendeta l ON a.PendetaID = l.PendetaID
LEFT JOIN DaftarGerejaGKJ m ON a.TempatNikah = m.GerejaID
LEFT JOIN DaftarGerejaGKJ n ON a.WargaGerejaL = n.GerejaID
LEFT JOIN DaftarGerejaGKJ o ON a.WargaGerejaP = o.GerejaID


";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "NamaL":
                        $sSQL = $sSQL . " ORDER BY NamaLengkapL ASC";
                        break;
                case "NamaP":
                        $sSQL = $sSQL . " ORDER BY NamaLengkapL ASC";	
                        break;
				case "TempatIbadah":
                        $sSQL = $sSQL . " ORDER BY TempatNikah ";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY TanggalNikah Desc, NikahID Desc";
                        break;
        }

        $rsTitipCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsTitipCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PenyegaranJanjiNikahEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Pelayanan Pembaruan dan Penyegaran Janji Perkawinan") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Nikah">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="NamaL"><?php echo gettext("Nama Suami"); ?></option>
                        <option value="NamaP"><?php echo gettext("Nama Istri"); ?></option>
						<option value="TempatIbadah"><?php echo gettext("TempatIbadah"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Nikah">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=PenyegaranJanjiNikah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=PenyegaranJanjiNikah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=PenyegaranJanjiNikah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=PenyegaranJanjiNikah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=PenyegaranJanjiNikah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="Nikah">
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
	
				<td colspan="1" align="center"><?php echo gettext("Cetak"); ?></td>
			
				<td><?php echo gettext("Tempat/Tanggal"); ?></td>
				<td><?php echo gettext("Nama Keluarga Pemohon"); ?></td>
				<td><?php echo gettext("Nama OrangTua"); ?></td>
				<td><?php echo gettext("Tempat/Tgl Lahir"); ?></td>
				<td><?php echo gettext("Tempat/Tgl BaptisAnak"); ?></td>
				<td><?php echo gettext("Tempat/Tgl Sidhi"); ?></td>
				<td><?php echo gettext("Tempat/Tgl BaptisDewasa"); ?></td>
                
				<td><?php echo gettext("Hapus"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="PenyegaranJanjiNikahEditor.php?NikahID=<?php echo $NikahID . "&PersonID=".$per_ID."&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
				<?php 
				echo "<td><a target=\"_blank\" href=\"PrintViewPenyegaranJanjiNikah.php?NikahID=".$NikahID."&Mode=4&GID=$refresh&\">Sertifikat</a>&nbsp;
					<br><a target=\"_blank\" href=\"PrintViewPenyegaranJanjiNikahBeritaAcara.php?NikahID=".$NikahID."&Tanggal=".$TanggalNikah."&Waktu=".$WaktuNikah."&Tempat=".$TmpNkh."&Mode=4&GID=$refresh&\">BeritaAcara</a>&nbsp;</td>";
					?>
					
					<td><?php echo $TempatNikah."<br>".date2Ind($TanggalNikah,4)."<br> pk.".$WaktuNikah."<br>".$PelayanPernikahan."
					<br><hr>Tanggal Menikah :<br>".date2Ind($TanggalMenikah,4)."<br>Kelompok : ".$Kelompok; ?>&nbsp;</td>
					<td>
					Suami :<br> 
					<?php 
					echo "<b><a target=\"_blank\" href=\"FamilyView.php?FamilyID=".$FamilyID."\">".$NamaLengkapL."</a></b><br>";
					echo "<hr>";
					echo "Istri : <br>";
					echo "<b><a target=\"_blank\" href=\"FamilyView.php?FamilyID=".$FamilyID."\">".$NamaLengkapP."</a></b><br>";
					
					?></td>
					<td>
					<?php echo "(Ayah) ".$NamaAyahL."<br>(Ibu) ".$NamaIbuL."" ?><br><hr>
					<?php echo "(Ayah) ".$NamaAyahP."<br>(Ibu) ".$NamaIbuP."" ?><br>
					</td>
					<td>
					<?php echo $TempatLahirL."<br>".date2Ind($TanggalLahirL,3) ?><br><hr>
					<?php echo $TempatLahirP."<br>".date2Ind($TanggalLahirP,3) ?><br>
					</td>
					<td>
					<?php echo $TempatBaptisAnakL."<br>".date2Ind($TanggalBaptisAnakL,3)."<br>".$PendetaBaptisL ?><br><hr>
					<?php echo $TempatBaptisAnakP."<br>".date2Ind($TanggalBaptisAnakP,3)."<br>".$PendetaBaptisP ?>
					</td>
					<td>
					<?php echo $TempatSidhiL."<br>".date2Ind($TanggalSidhiL,3)."<br>".$PendetaSidhiL ?><br><hr>
					<?php echo $TempatSidhiP."<br>".date2Ind($TanggalSidhiP,3)."<br>".$PendetaSidhiP ?>
					</td>
					<td>
					<?php echo $TempatBaptisDewasaL."<br>".date2Ind($TanggalBaptisDewasaL,3)."<br>".$PendetaBaptisDewasaL ?><br><hr>
					<?php echo $TempatBaptisDewasaP."<br>".date2Ind($TanggalBaptisDewasaP,3)."<br>".$PendetaBaptisDewasaP ?>
					</td>
				
						<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=PenyegaranJanjiNikah&AppPersonID=<?php echo $per_ID ?>&NikahID=<?php echo $NikahID ?>">X</a>&nbsp;</td>
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



}elseif ($iMode == 34)
{

// echo "listing Master Pengeluaran PPPG";
/**********************
**  listing Master Pengeluaran PPPG **
**********************/
		require "$sHeaderFile";
        // Base SQL

$sSQL = "SELECT * FROM JenisPengeluaranPPPG ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "KodeJenis":
                        $sSQL = $sSQL . " ORDER BY KodeJenis ASC";
                        break;
                case "NamaJenis":
                        $sSQL = $sSQL . " ORDER BY NamaJenis ASC";	
                        break;
				case "Keterangan":
                        $sSQL = $sSQL . " ORDER BY Keterangan ";	
                        break;		
                default:
                        $sSQL = $sSQL . " ORDER BY KodeJenis ASC";
                        break;
        }

        $rsTitipCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsTitipCount);

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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"JenisPengeluaranPPPGEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Jenis Pengeluaran PPPG") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="Nikah">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="KodeJenis"><?php echo gettext("Kode Jenis"); ?></option>
                        <option value="NamaJenis"><?php echo gettext("Nama Jenis"); ?></option>
						<option value="Keterangan"><?php echo gettext("Keterangan"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Nikah">
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
                        echo "<A HREF=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=JenisPengeluaranPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=0&amp;mode=JenisPengeluaranPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=JenisPengeluaranPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=JenisPengeluaranPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp.php?Result_Set=$thisLinkResult&amp;mode=JenisPengeluaranPPPG&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="Nikah">
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
			
				<td ALIGN=CENTER><?php echo gettext("Kode Jenis"); ?></td>
				<td><?php echo gettext("Nama Jenis"); ?></td>
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
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="JenisPengeluaranPPPGEditor.php?KodeJenis=<?php echo $KodeJenis."&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
					
					<td ALIGN=CENTER ><?php echo $KodeJenis ?></td>
					<td><?php echo $NamaJenis ?></td>
					<td><?php echo $Keterangan ?></td>
				
						<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=JenisPengeluaranPPPG&KodeJenis=<?php echo $KodeJenis ?>">X</a>&nbsp;</td>
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
?>
