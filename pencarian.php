<?php
/*******************************************************************************
 *
 *  filename    : pencarian.php
 *
*  2008 Erwin Pratama for GKJ Bekasi WIl Timur ( http://www.gkjbekasi-wiltimur.net )
*  2011 Erwin Pratama for GKJ Bekasi Timur ( http://www.gkjbekasitimur.org )
 *
 ******************************************************************************/
 ?>
<html>
<head>
<title>Anjungan Informasi Mandiri - GKJ Bekasi Timur</title>
<meta name="author" content="">
<style type="text/css" media="screen"><!--
body 
    {
    color: white;
    background-color: #ccccff;
    margin: 0px
    }

#horizon        
    {
    color: white;
    background-color: transparent;
    text-align: center;
    position: absolute;
    top: 10%;
    left: 0px;
    width: 100%;
    height: 1px;
    overflow: visible;
    visibility: visible;
    display: block
    }

#content    
    {
    font-family: Verdana, Geneva, Arial, sans-serif;
    background-color: transparent;
    margin-left: -250px;
    position: absolute;
    top: -35px;
    left: 50%;
    width: 500px;
    height: 15px;
    visibility: visible
    }

.bodytext 
    {
    font-size: 12px
    }

.headline 
    {
    font-weight: bold;
    font-size: 18px;
	font-color: black;
    }

#footer 
    {
    font-size: 11px;
    font-family: Verdana, Geneva, Arial, sans-serif;
    text-align: center;
    position: absolute;
    bottom: 0px;
    left: 0px;
    width: 100%;
    height: 20px;
    visibility: visible;
    display: block
    }

a:link, a:visited 
    {
    color: #06f;
    text-decoration: none
    }

a:hover 
    {
    color: red;
    text-decoration: none
    }

p.blue {color:rgb(0,0,255);}

tr {
color: black;
background-color: #ccccff;
}
tr:hover {
color: blue;
background-color: yellow;
font-weight: bold;
}


.gallerycontainer{
position: relative;
/*Add a height attribute and set to largest image's height to prevent overlaying*/
}

.thumbnail img{
border: 1px solid white;
margin: 0 5px 5px 0;
}

.thumbnail:hover{
background-color: transparent;
}

.thumbnail:hover img{
border: 1px solid blue;
}

.thumbnail span{ /*CSS for enlarged image*/
position: absolute;
background-color: lightyellow;
padding: 5px;
left: -1000px;
border: 1px dashed gray;
visibility: hidden;
color: black;
text-decoration: none;
}

.thumbnail span img{ /*CSS for enlarged image*/
border-width: 0;
padding: 2px;
}

.thumbnail:hover span{ /*CSS for enlarged image*/
visibility: visible;
top: 0;
left: 50px; /*position where enlarged image should offset horizontally */
z-index: 50;
}


--></style>

</head>
<!-- © http://www.gkjbekasitimur.org/ -->
<body>

<div id="horizon">
    <div id="content">
        <div class="bodytext">
        <span class="headline"><p class="blue">GKJ Bekasi Timur</p></span><br>
        </div>

<script type="text/javascript" src="Include/keyboard.js" charset="UTF-8"></script>
<style type='text/css'>
    #mask{ 
        position:absolute; /* important */
        top:0px; /* start from top */
        left:0px; /* start from left */
        height:100%; /* cover the whole page */
        width:100%;  /* cover the whole page */
        display:none; /* don't show it '*/          
        
        /* styling bellow */
        background-color: gray; 
    }
    
    .modal_window{
        position:absolute; /* important so we can position it on center later */
        display:none; /* don't show it */
        
        /* styling bellow */
        color:white;
    }
    
    /* style a specific modal window  */
    #modal_window{
        padding:50px;
        border:1px solid gray;
        background: #246493;
        color:black;
    }
</style>

<script type='text/javascript' src='Include/jquery.js'></script>                   
<script type='text/javascript'>

    $(document).ready(function(){
    
    //get the height and width of the page
    var window_width = $(window).width();
    var window_height = $(window).height();
    
    //vertical and horizontal centering of modal window(s)
    /*we will use each function so if we have more then 1 
    modal window we center them all*/
    $('.modal_window').each(function(){
        
        //get the height and width of the modal
        var modal_height = $(this).outerHeight();
        var modal_width = $(this).outerWidth();
        
        //calculate top and left offset needed for centering
        var top = (window_height-modal_height)/2;
        var left = (window_width-modal_width)/2;
        
        //apply new top and left css values 
        $(this).css({'top' : top , 'left' : left});
        
    });

        
        $('.activate_modal').click(function(){
                
              //get the id of the modal window stored in the name of the activating element       
              var modal_id = $(this).attr('name');
              
              //use the function to show it
              show_modal(modal_id);
              
        });
        
        $('.close_modal').click(function(){
            
            //use the function to close it
            close_modal();
            
        });
        
    });
    
    //THE FUNCTIONS
    
    function close_modal(){
        
        //hide the mask
        $('#mask').fadeOut(500);
        
        //hide modal window(s)
        $('.modal_window').fadeOut(500);
        
    }
    function show_modal(modal_id){
    
        //set display to block and opacity to 0 so we can use fadeTo
        $('#mask').css({ 'display' : 'block', opacity : 0});
        
        //fade in the mask to opacity 0.8 
        $('#mask').fadeTo(500,0.8);
         
         //show the modal window
        $('#'+modal_id).fadeIn(500);
        
    }
</script>

<link rel="stylesheet" type="text/css" href="Include/keyboard.css">

<form name="form" action="pencarian.php" method="get">
<P ID=#keyboardInputMaster.keyboardInputSize3> 
 <input type="text" name="q" class="keyboardInput" size="50" />
</P>
</form>

<?php
$refresh = microtime() ;
// Database connection constants
$sSERVERNAME = 'localhost';
$sUSER = 'gkjbekas_dbudb';
$sPASSWORD = 'p4ssdbudb;';
$sDATABASE = 'gkjbekas_db';

function crop($str, $len) {
    if ( strlen($str) <= $len ) {
        return $str;
    }
    // find the longest possible match
    $pos = 0;
    foreach ( array('. ', '? ', '! ') as $punct ) {
        $npos = strpos($str, $punct);
        if ( $npos > $pos && $npos < $len ) {
            $pos = $npos;
        }
    }

    if ( !$pos ) {
        // substr $len-3, because the ellipsis adds 3 chars
        return substr($str, 0, $len-3) . ''; 
    }
    else {
        // $pos+1 to grab punctuation mark
        return substr($str, 0, $pos+1);
    }
}
function cropx($str, $len) {
    if ( strlen($str) <= $len ) {
        return $str;
    }

    // find the longest possible match
    $pos = 0;
    foreach ( array('. ', '? ', '! ') as $punct ) {
        $npos = strpos($str, $punct);
        if ( $npos > $pos && $npos < $len ) {
            $pos = $npos;
        }
    }

    if ( !$pos ) {
        // substr $len-3, because the ellipsis adds 3 chars
        return substr($str, 0, $len-3) . '...'; 
    }
    else {
        // $pos+1 to grab punctuation mark
        return substr($str, 0, $pos+1);
    }
}

  // Get the search variable from URL

  $var = @$_GET['q'] ;
  $var = preg_replace("/[^A-Za-z0-9 ]/","",$var);

//  $var = mysql_real_escape_string($var);
  $trimmed = trim($var) ;//trim whitespace from the stored variable
  $str3 = strlen($var);

 // rows to return
$limit=100; 

// check for an empty string and display a message.
if ($trimmed == "" || $str3 < 3 ) 
  {
  echo "<p><font color=\"blue\">Silahkan masukkan data yang akan ditelusuri...</font></p>";
  exit;
  }

// check for a search parameter
if (!isset($var))
  {
  echo "<p><font color=\"red\">Maaf Anda belum memasukkan kata kunci!</font></p>";
  exit;
  }

//connect to your database ** EDIT REQUIRED HERE **
mysql_connect($sSERVERNAME,$sUSER,$sPASSWORD); //(host, username, password)

//specify database ** EDIT REQUIRED HERE **
mysql_select_db($sDATABASE) or die("Unable to select database"); //select which database we're using

// Build SQL Query  
$query = "select 
     CASE
        WHEN per_cls_ID = 1 THEN 'Warga'
		WHEN per_cls_ID = 2 THEN 'Titipan'
		WHEN per_cls_ID = 3 THEN 'Tamu'
		WHEN per_cls_ID = 5 THEN 'Calon'
		WHEN per_cls_ID = 6 THEN 'Pindah'
		WHEN per_cls_ID = 7 THEN 'Meninggal'
		WHEN per_cls_ID = 8 THEN 'Bukan'
		WHEN per_cls_ID = 9 THEN 'InAktif'
		WHEN per_cls_ID is NULL THEN 'NoData'
   END as Status, per_ID, per_WorkPhone, per_FirstName
   from person_per where per_FirstName like \"%$trimmed%\"  
 order by per_FirstName"; // EDIT HERE and specify your table and field names for the SQL query

 //$query = "select * from person_per where per_FirstName like \"%$trimmed%\"  
//  order by per_FirstName"; // EDIT HERE and specify your table and field names for the SQL query  
  


 $numresults=mysql_query($query);
 $numrows=mysql_num_rows($numresults);

// If we have no results, offer a google search as an alternative

if ($numrows == 0)
  {
  echo "<h4>Hasil pencarian:</h4>";
  echo "<p><font color=\"red\">Maaf, pencarian: &quot;" . $trimmed . "&quot; tidak ditemukan</font></p>";
}
// next determine if s has been passed to script, if not use 0
  if (empty($s)) {
  $s=0;
  }

// get results
  $query .= " limit $s,$limit";
  $result = mysql_query($query) or die("Couldn't execute query");

// display what the person searched for
echo "<p><font color=\"blue\">Ditemukan: " .$numrows . " data, dengan kata kunci </font><font color=\"red\">&quot;" . $var . "&quot;</font></p>";

// begin to show results set
echo "	<table border=\"0\" width=\"500\" cellspacing=0 cellpadding=0 >";
echo "	<tr>";
echo "	<td><font size=2><b> No </b></td> ";
echo "	<td><font size=2><b> Foto </b></td> ";
echo "	<td><font size=2><b> No.ID </b></td> ";
echo "	<td><font size=2><b> Kelompok </b></td> ";
echo "	<td><font size=2><b> Nama Lengkap </b></td> ";
echo "	<td ALIGN=right><font size=2><b> Status </b> </td>";
echo "	</tr>";

//echo "Hasil <br>";
//echo "No.NoID - Kelompok - Nama Lengkap  <br>";
$count = 1 + $s ;

// now you can display the results returned
  while ($row= mysql_fetch_array($result)) {
 // $title = $row["per_ID"];
 // $title1 = $row["per_FirstName"];
 // $title2 = $row["per_WorkPhone"];
 // $title3 = $row["per_DateEntered"];
  
  $iPersonID = $row["per_ID"];
  
//  $title1a = cropx($title1,15);
//  $title3a = crop($title3,13);
// print hasil  
//  echo "$count. $title - $title2 - $title3a - $title1  <br>";
//  echo "<p><a href='http://www.gkjbekasitimur.org/datawarga/PrintViewCari.php?PersonID=".$title."'>".$title." - ".$title2." - ".$title1."" ; 
echo "	<tr>" ;
echo "	<td><font size=2>" . $count . "</td>";
echo "	<td><font size=2><div class='gallerycontainer'>" ;
 
	 		// Display photo or upload from file
	 		$photoFile = "Images/Person/thumbnails/" . $iPersonID . ".jpg";
	         if (file_exists($photoFile))
	         {
	         //    echo '<a target="_blank" href="" >';
	         //    echo '<img border="1" src="'.$photoFile.'" width="20" ></a>';
				 
				 
			//	 <a class="thumbnail" href="#thumb"><img src="media/tree_thumb.jpg" width="100px" height="66px" border="0" />
			//	 <span><img src="media/tree.jpg" /><br />Simply beautiful.</span></a>
				 
			//   echo '<a class="thumbnail" href="#thumb" target="_blank" >';
			   echo '<a class="thumbnail" href="http://www.gkjbekasitimur.org/datawarga/PrintViewCari.php?PersonID='. $row[per_ID] .'" >';
	           echo '<img border="1" src="'.$photoFile.'" width="20" ><span>
			   <img border="1" src="'.$photoFile.'" width="200" > 
			   ' . $row[per_FirstName] . ' </span></a>';
				 
				 
	             if ($bOkToEdit) {
	                 echo '
	                     <form method="post"
	                     action="PersonView.php?PersonID=' . $iPersonID . '">
	                     <br>
	                     <input type="submit" class="icTinyButton"
	                     value="' . gettext("Delete Photo") . '" name="DeletePhoto">
	                     </form>';
	                 }
	         } else {
	             // Some old / M$ browsers can't handle PNG's correctly.
	             if ($bDefectiveBrowser)
	                echo '<img border="0" src="Images/NoPhoto.gif" width="20" >';
	             else
			       echo '<img border="0" src="Images/NoPhoto.png" width="20" >';

	             if ($bOkToEdit) {
	                 if (isset($PhotoError))
	                     echo '<span style="color: red;">' . $PhotoError . '</span><br>';

	                 echo '
	                     <form method="post"
	                     action="PersonView.php?PersonID=' . $iPersonID . '"
	                     enctype="multipart/form-data">
	                     <input class="icTinyButton" type="file" name="Photo">
	                     <input type="submit" class="icTinyButton"
	                     value="' . gettext("Upload Photo") . '" name="UploadPhoto">
	                     </form>';
	             }
	         }
echo "</div></td>";
echo "	<td><a href='http://www.gkjbekasitimur.org/datawarga/PrintViewCari.php?PersonID=". $row[per_ID] ."'><font size=2>" . $row[per_ID] . "</td>";
echo "	<td><font size=2>" . $row[per_WorkPhone] . "</td>";
echo "	<td><font size=2>" . $row[per_FirstName] . "</td>";
//echo "	<td><font size=2>" . $row[per_cls_ID] . "</td>";
echo "	<td ALIGN=right><font size=2>" . $row[Status] . "</td>";
  echo "</a>	</tr>" ;

  
// jquery
 // echo "<p><a class='activate_modal' name='modal_window' 
 // href='http://www.gkjbekasitimur.org/datawarga/PrintViewCari.php?PersonID=".$title."'>".$title." - ".$title2." - ".$title1."" ; 

 // echo "<br></a></p>";

  
  
//  echo "$count.)&nbsp;$idperson &nbsp;$title" ;
  $count++ ;
  }

  echo "	</table>";
 //     echo "  </div> </div>
//<div id='footer'>
//    <a href='http://www.gkjbekasitimur.org'>www.gkjbekasitimur.org</a></div> ";
	
//  echo "<div id='mask' class='close_modal'></div>";
//  echo "<div id='modal_window' class='modal_window'>data sedang diproses, silahkan tunggu .....</div>"; 
  

$currPage = (($s/$limit) + 1);

//break before paging
  echo "<br />";

  // next we need to do the links to other results
  if ($s>=1) { // bypass PREV link if s is 0
  $prevs=($s-$limit);
  print "&nbsp;<a href=\"$PHP_SELF?s=$prevs&q=$var\">&lt;&lt; 
  Prev 10</a>&nbsp&nbsp;";
  }

// calculate number of pages needing links
  $pages=intval($numrows/$limit);

// $pages now contains int of pages needed unless there is a remainder from division

  if ($numrows%$limit) {
  // has remainder so add one page
  $pages++;
  }

// check to see if last page
  if (!((($s+$limit)/$limit)==$pages) && $pages!=1) {

  // not last page so give NEXT link
  $news=$s+$limit;

  echo "&nbsp;<a href=\"$PHP_SELF?s=$news&q=$var\">Next 10 &gt;&gt;</a>";
  }

$a = $s + ($limit) ;
  if ($a > $numrows) { $a = $numrows ; }
  $b = $s + 1 ;
//  echo "<p>Hasil pencarian $b sampai $a dari $numrows</p>";
  
?>

</body>
</html>