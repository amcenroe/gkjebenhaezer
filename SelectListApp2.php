<?php
/*******************************************************************************
*
*  filename    : SelectListApp2.php
*  2008 Erwin Pratama for GKJ Bekasi WIl Timur
*  2013 Erwin Pratama for GKJ Bekasi Timur
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
	case ('DaftarTempatIbadah'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('masterposanggthn'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('notularapat'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	case ('ProgramDanAnggaran'):
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
}elseif ($sMode == 'DaftarTempatIbadah')
{
    $sPageTitle = gettext("Daftar Tempat Ibadah");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Gereja Tempat Ibadah";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 31;
}elseif ($sMode == 'masterposanggthn')
{
    $sPageTitle = gettext("Daftar Master Nilai Anggaran per Tahun ");
	$logvar1 = "Listing";
	$logvar2 = "Klasifikasi Pos Anggaran";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 32;
}elseif ($sMode == 'notularapat')
{
    $sPageTitle = gettext("Daftar Notula Rapat");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Notula Rapat";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 34;
}elseif ($sMode == 'ProgramDanAnggaran')
{
    $sPageTitle = gettext("Daftar Program dan Anggaran");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Program Dan Anggaran";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 35;	
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"DaftarGerejaEditor.php\">" . gettext("Tambahkan Data Gereja") . "</a></div><BR>"; }
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
                    <td><a href="DaftarGerejaEditor.php?GerejaID=<?php echo $GerejaID . "\">" . gettext ("Edit"); ?></a></td>
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
elseif ($iMode == 31)
{
// echo "listing DaftarTempatIbadah";
/****************************
**  Listing Tempat ibadah  **
*****************************/
		require "$sHeaderFile";
        // Base SQL
       $sSQL = "select * from LokasiTI";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "NamaTI":
                        $sSQL = $sSQL . " ORDER BY NamaTI ASC";
                        break;
                case "KotaTI":
                        $sSQL = $sSQL . " ORDER BY KotaTI ASC";	
                        break;
				default:
                        $sSQL = $sSQL . " ORDER BY NamaTI ASC";
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"DaftarTempatIbadahEditor.php\">" . gettext("Tambahkan Data Tempat Ibadah") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp2.php" name="DaftarTempatIbadah">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="NamaTI"><?php echo gettext("Nama Tempat Ibadah"); ?></option>
                        <option value="KotaTI"><?php echo gettext("Kota Tempat Ibadah"); ?></option>

                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="DaftarTempatIbadah">
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
                echo "<form method=\"get\" action=\"SelectListApp2.php\" name=\"ListNumber\">";
                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=DaftarTempatIbadah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp2.php?Result_Set=0&amp;mode=DaftarTempatIbadah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=DaftarTempatIbadah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=DaftarTempatIbadah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=DaftarTempatIbadah&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="DaftarTempatIbadah">
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
			
				<td><?php echo gettext("NamaTI"); ?></td>
                <td><?php echo gettext("AlamatTI"); ?></td>		
				<td><?php echo gettext("KotaTI"); ?></td>		
				<td><?php echo gettext("KodePOSTI"); ?></td>	
				<td><?php echo gettext("Telepon"); ?></td>
				<td><?php echo gettext("Fax"); ?></td>	
				<td><?php echo gettext("latitude"); ?></td>
				<td><?php echo gettext("longitude"); ?></td>
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
				$KodeTI = ""; 	 	
				$NamaTI = ""; 		
				$AlamatTI1 = ""; 	 	
				$AlamatTI2 = ""; 	 	
				$KotaTI = ""; 	 	
				$KodePOSTI = ""; 	 	
				$Telepon  = ""; 		
				$Fax = ""; 	 
				$latitude = ""; 	 	
				$longitude = ""; 	

                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>
				
                <tr class="<?php echo $sRowClass ?>">
                    <?php if ($_SESSION['bEditRecords']) { ?>
                    <td><a href="DaftarTempatIbadahEditor.php?KodeTI=<?php echo $KodeTI . "\">" . gettext ("Edit"); ?></a></td>
                    <?php } ?>
			
                <td><?php echo $NamaTI ?></td>				
				<td><?php echo $AlamatTI1.",".$AlamatTI2; ?></td>
				<td><?php echo $KotaTI; ?></a>&nbsp;</td>
				<td><?php echo $KodePOSTI ?></td>
				<td><?php echo $Telepon ?></td> 
				<td><?php echo $FAX ?></td> 
				<td><?php echo $latitude ?></td> 	
				<td><?php echo $longitude ?></td> 
					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=DaftarTempatIbadah&KodeTI=<?php echo $KodeTI ?>">X</a>&nbsp;</td>
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
}elseif ($iMode == 32)
{
//echo "listing Master Nilai Pos Anggaran ";
/*****************************
**  Daftar Master Nilai Anggaran **
******************************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.*, b.*, c.* FROM MasterAnggaran a
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
                case "TahunAnggaran":
                        $sSQL = $sSQL . " ORDER BY  a.TahunAnggaran, c.BidangID, b.KomisiID ";
				        break;						
                
				default:
                        $sSQL = $sSQL . " ORDER BY  a.TahunAnggaran DESC, c.BidangID, b.KomisiID ";
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"MasterAnggaranEditor.php\">" . gettext("Tambahkan Data Master Nilai Anggaran Baru") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="masterposanggthn">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Aktif"><?php echo gettext("Aktif"); ?></option>
						<option value="Bidang"><?php echo gettext("Bidang"); ?></option>
                        <option value="Komisi"><?php echo gettext("Komisi"); ?></option>
						<option value="TahunAnggaran"><?php echo gettext("TahunAnggaran"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="masterposanggthn">
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
                echo "<form method=\"get\" action=\"SelectListApp2.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=masterposanggthn&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp2.php?Result_Set=0&amp;mode=masterposanggthn&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=masterposanggthn&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=masterposanggthn&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=masterposanggthn&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="masterposanggthn">
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
				<td align="center"><?php echo gettext("Tahun Anggaran"); ?></td>					
				<td align="center"><?php echo gettext("Bidang"); ?></td>	
				<td align="center"><?php echo gettext("Komisi"); ?></td>
				<td align="center"><?php echo gettext("Budget"); ?></td>
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
                                <td><a href="MasterAnggaranEditor.php?MasterAnggaranID=<?php echo $MasterAnggaranID . "\">" . gettext ("Edit"); ?></a></td>
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
						<td align="center"><?php echo $TahunAnggaran; ?>&nbsp;</td>
						<td align="left"><?php echo $BidangID."-".$KodeBidang."-".$NamaBidang; ?>&nbsp;</td>
						<td align="left"><?php echo $KomisiID."-".$KodeKomisi."-".$NamaKomisi; ?>&nbsp;</td>
						<td align="right"><?php echo currency(' ',$Budget,'.',',-'); ?>&nbsp;</td>
						<td><?php echo $Keterangan ?>&nbsp;</td>						

					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=MasterPosAnggaranThn&MasterAnggaranID=<?php echo $MasterAnggaranID ?>">X</a>&nbsp;</td>
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



}elseif ($iMode == 34)
{
//echo "listing Notula Rapat ";
/*****************************
**  Daftar Notula Rapat **
******************************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.*,a.Keterangan as KetRapat, b.* FROM NotulaRapat a
		LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI

		";

        //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Gereja":
                        $sSQL = $sSQL . " ORDER BY KodeTI ASC";
                        break;
                case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal ASC";
                        break;
                case "Keterangan":
                        $sSQL = $sSQL . " ORDER BY Keterangan ASC";
                        break;						
				default:
                        $sSQL = $sSQL . " ORDER BY  Tanggal DESC ";
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"NotulaRapatEditor.php\">" . gettext("Tambahkan Data Notula Rapat Baru") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp.php" name="notularapat">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Gereja"><?php echo gettext("KodeTI"); ?></option>
						<option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
						<option value="Tanggal"><?php echo gettext("Keterangan"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="notularapat">
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
                echo "<form method=\"get\" action=\"SelectListApp2.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=notularapat&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp2.php?Result_Set=0&amp;mode=notularapat&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=notularapat&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=notularapat&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=notularapat&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="notularapat">
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
				<td align="center"><?php echo gettext("Cetak"); ?></td>	
				<td align="center"><?php echo gettext("Email"); ?></td>	
				<td align="center"><?php echo gettext("Judul Dokumen"); ?></td>	
				<td align="center"><?php echo gettext("Tanggal Rapat"); ?></td>	
				<td align="center"><?php echo gettext("Pukul"); ?></td>					
				<td align="center"><?php echo gettext("Tempat"); ?></td>	
				<td align="center"><?php echo gettext("Keterangan"); ?></td>
				<td align="center"><?php echo gettext("Jml Peserta"); ?></td>				
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

                $NotulaRapatID = "";
                $Tanggal = "";
				$Pukul = "";
                $KodeTI = "";
                $NomorSurat = "";
                $IsiNotula = "";
                $Keterangan = "";
				$Peserta = "";
  
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>"> 
                        <?php if ($_SESSION['bEditRecords']) { ?>
                        <td><a href="NotulaRapatEditor.php?NotulaRapatID=<?php echo $NotulaRapatID . "\">" . gettext ("Edit"); ?></a></td>
						<?php } ?>
						<td><a href="NotulaRapatView.php?NotulaRapatID=<?php echo $NotulaRapatID ?>"><?php echo "Detail" ?></a>&nbsp;</td>		
						<td><a href="PrintViewNotulaRapat.php?NotulaRapatID=<?php echo $NotulaRapatID; ?>&Mode=1" class="SmallText" target="_blank"><?php echo "Cetak" ?></a>&nbsp;</td>	
						<td><a href="AktaSidang/aktasidang<?php echo $Tanggal; ?>.doc" ><?php echo "Email" ?></a>&nbsp;</td>	
						<td><?php echo $NomorSurat ?>&nbsp;</td>
						<td><?php echo date2Ind($Tanggal,1) ?>&nbsp;</td>
						<td><?php echo $Pukul; ?>&nbsp;</td>
						<td><?php echo $NamaTI ?>&nbsp;</td>
						<td><?php echo $KetRapat ?>&nbsp;</td>
						<td><?php echo $Peserta ?>&nbsp;</td>
										

					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=NotulaRapat&NotulaRapatID=<?php echo $NotulaRapatID ?>">X</a>&nbsp;</td>
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

}elseif ($iMode == 35)
{
//echo "listing Program dan Anggaran ";
/*********************************
**  Daftar Program dan Anggaran **
******************************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.*, b.*, c.* FROM ProgramDanAnggaran a
		LEFT JOIN MasterKomisi b ON a.KomisiID=b.KomisiID
		LEFT JOIN MasterBidang c ON b.BidangID=c.BidangID
		";

        //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Bidang":
                        $sSQL = $sSQL . " ORDER BY b.BidangID ASC, Tahun DESC";
                        break;
                case "Komisi":
                        $sSQL = $sSQL . " ORDER BY a.KomisiID ASC, b.BidangID ASC, Tahun DESC";
                        break;
                case "Tahun":
                        $sSQL = $sSQL . " ORDER BY Tahun DESC, b.BidangID ASC, a.KomisiID ASC ";
                        break;	
                case "Program":
                        $sSQL = $sSQL . " ORDER BY Program ASC, b.BidangID ASC, a.KomisiID ASC, Tahun DESC";
                        break;						
				default:
                        $sSQL = $sSQL . " ORDER BY  Tahun DESC, b.BidangID ASC, a.KomisiID ASC ";
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
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"ProgramDanAnggaranEditor.php\">" . gettext("Tambahkan Data Program Dan Anggaran Baru") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectListApp2.php" name="ProgramDanAnggaran">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Bidang"><?php echo gettext("Bidang"); ?></option>
						<option value="Komisi"><?php echo gettext("Komisi"); ?></option>
						<option value="Tahun"><?php echo gettext("Tahun"); ?></option>
						<option value="Program"><?php echo gettext("Program"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="ProgramDanAnggaran">
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
                echo "<form method=\"get\" action=\"SelectListApp2.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=ProgramDanAnggaran&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
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
                        echo "&nbsp;&nbsp;<a href=\"SelectListApp2.php?Result_Set=0&amp;mode=ProgramDanAnggaran&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=ProgramDanAnggaran&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=ProgramDanAnggaran&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectListApp2.php?Result_Set=$thisLinkResult&amp;mode=ProgramDanAnggaran&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="ProgramDanAnggaran">
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
				<td align="center"><?php echo gettext("Tahun RAB"); ?></td>	
				<td align="center"><?php echo gettext("Bidang/Komisi"); ?></td>	
				<td align="center"><?php echo gettext("Program"); ?></td>	
				<td align="center"><?php echo gettext("Kegiatan"); ?></td>					
				<td align="center"><?php echo gettext("Tolok Ukur"); ?></td>
				<td align="center"><?php echo gettext("Jadwal"); ?></td>
				<td align="center"><?php echo gettext("Anggaran KasJemaat"); ?></td>	
				<td align="center"><?php echo gettext("Anggaran LainLain"); ?></td>
				<td align="center"><?php echo gettext("Keterangan"); ?></td>
							
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

                $RabID = "";
                $Tanggal = "";
				$Pukul = "";
                $KodeTI = "";
                $NomorSurat = "";
                $IsiNotula = "";
                $Keterangan = "";
				$Peserta = "";
  
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>"> 
                        <?php if ($_SESSION['bEditRecords']) { ?>
                        <td><a href="ProgramDanAnggaranEditor.php?RabID=<?php echo $RabID . "\">" . gettext ("Edit"); ?></a></td>
						<?php } ?>
						<td><a href="PrintViewProgramDanAnggaran.php?RabID=<?php echo $RabID ?>"><?php echo "Detail" ?></a>&nbsp;</td>		
						<td><?php echo $Tahun; ?>&nbsp;</td>
						<td><?php echo $KodeBidang ?><br><?php echo $KodeKomisi ?></td>
						<td><?php echo $Program; ?>&nbsp;</td>
						<td><?php echo $Kegiatan ?>&nbsp;</td>
						<td><?php echo $TolokUkur ?>&nbsp;</td>
						<td><?php echo $Jadwal ?>&nbsp;</td>
						<td><?php echo currency('Rp. ',$AggKasJemaat,'.',',00') ?>&nbsp;</td>
						<td><?php echo currency('Rp. ',$AggLainLain,'.',',00') ?>&nbsp;</td>
						<td><?php echo $Keterangan ?>&nbsp;</td>
										

					<?php if ($_SESSION['bDeleteRecords']) { ?> 
                        <td><a href="SelectDeleteApp.php?mode=ProgramDanAnggaran&RabID=<?php echo $RabID ?>">X</a>&nbsp;</td>
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
