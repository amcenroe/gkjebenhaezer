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
	include ("Include/jpgraph-3.5.0b1/src/jpgraph_line.php");
//	include ("Include/jpgraph-3.5.0b1/src/jpgraph_pie3d.php");
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/ConfigWeb.php";

//$TYP=$_GET["TYP"];
//$PID=$_GET["PID"];

$ResEnc=$_GET["res"];
$ResClearText=$_GET["rest"];

if($ResClearText==''){
    # --- DECRYPTION ---

$ciphertext_base64 = $ResEnc; 

    # key is specified using hexadecimal
    $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
    
    # show key size use either 16, 24 or 32 byte keys for AES-128, 192
    # and 256 respectively
    $key_size =  strlen($key);
    # create a random IV to use with CBC encoding
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);    

    $ciphertext_dec = base64_decode($ciphertext_base64);
    
    # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
    $iv_dec = substr($ciphertext_dec, 0, $iv_size);
    
    # retrieves the cipher text (everything except the $iv_size in the front)
    $ciphertext_dec = substr($ciphertext_dec, $iv_size);

    # may remove 00h valued characters from end of plain text
    $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
    
 //   echo  $plaintext_dec . "\n";

$RES = $plaintext_dec;
}else
{$RES=$ResClearText;}

//echo $RES;

//TYPTHBXXXYYYPID1234567890.0000
// TYP = Type
// THB = THB Thumbnail, HRS Highres  
// XXX = Size X
// YYY = Size Y
// PID = ID Image

//echo substr('abcdef', 1, 3);  // bcd
//echo substr('abcdef', 0, 4);  // abcd

$TYP = substr($RES, 0,3);
$THB = substr($RES, 3,3);
$XXX = substr($RES, 6,3);
$YYY = substr($RES, 9,3);
$PID = substr($RES, 12,6);

//test
//$TYP=SRT;
//$THB=HGH;
//$XXX=800;
//$YYY=999;
//$PID=100854;

switch ($TYP)
{
	case AST:
		//echo gettext("Aset");
		$bloc= 'Images/Aset/';
		$loc = $bloc.''.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/'.$PID.'.jpg';
	break;	
	case ATT:
		//echo gettext("Atestasi Keluar");
		$bloc= 'Images/AtestasiKeluar/';
		$loc = $bloc.''.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/'.$PID.'.jpg';
	break;
	case ATN:
		//echo gettext("Atestasi Masuk");
		$bloc= 'Images/AtestasiMasuk/';
		$loc = $bloc.'ATIN'.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/ATIN'.$PID.'.jpg';
	break;
	case SBA:
		//echo gettext("Surat Baptis Anak");
		$bloc= 'Images/BaptisAnak/';
		$loc = $bloc.'SBA'.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/SBA'.$PID.'.jpg';
	break;
	case SBD:
		//echo gettext("Surat Baptis Dewasa");
		$bloc= 'Images/BaptisDewasa/';
		$loc = $bloc.'SBD'.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/SBD'.$PID.'.jpg';
	break;
	case FAM:
		//echo gettext("Foto Keluarga");
		$bloc= 'Images/Family/';
		$loc = $bloc.''.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/'.$PID.'.jpg';
	break;	
	case KTP:
		//echo gettext("KTP");
		$bloc= 'Images/KartuTandaPenduduk/';
		$loc = $bloc.'KTP'.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/KTP'.$PID.'.jpg';
	break;	
	case SRT:
		//echo gettext("Surat Masuk");
		$bloc= 'Images/Mail/';
		$loc = $bloc.''.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/'.$PID.'.jpg';
	break;	
	case SNN:
		//echo gettext("Surat Nikah");
		$bloc= 'Images/Nikah/';
		$loc = $bloc.'SN'.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/SN'.$PID.'.jpg';
	break;	
	case PAK:
		//echo gettext("Pend Agama Kristen");
		$bloc= 'Images/Pak/';
		$loc = $bloc.''.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/'.$PID.'.jpg';
	break;
	case PER:
		//echo gettext("Foto Pribadi");
		$bloc= 'Images/Person/';
		$loc = $bloc.''.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/'.$PID.'.jpg';
	break;	
	case SKK:
		//echo gettext("Surat Keputusan");
		$bloc= 'Images/SK/';
		$loc = $bloc.''.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/'.$PID.'.jpg';
	break;			
	case SID:
		//echo gettext("Surat Sidhi");
		$bloc= 'Images/Sidhi/';
		$loc = $bloc.'SS'.$PID.'.jpg';
		$loct= $bloc.'/thumbnails/SS'.$PID.'.jpg';	
	break;		
}

if (!file_exists($loc)) {
    $loc = '1piksel.jpg';
    $loct = '1piksel.jpg';
}

IF ($THB=='THB'){
$lok = $loct;
}else{
$lok = $loc; 
}


// Some data
$ydata = array(0,0);
 
// Create the graph. These two calls are always required
$graph = new Graph($XXX,$YYY);

$MarginAtas=$YYY-20;

$graph->SetScale("textlin");
//$graph->SetScale("linlin",1,1,1,1);
$graph->SetMargin(5,5,$MarginAtas,2);

$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,2);
$graph->xaxis->SetColor('white','gray');

$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,2);
$graph->yaxis->SetColor('white','gray');

// Setup the grid and plotarea box
$graph->ygrid->SetLineStyle('dashed');
$graph->ygrid->setColor('darkgray');
$graph->SetBox(false);

$graph->img->SetImgFormat("jpeg");
 
$graph->SetBackgroundImage($lok,BGIMG_FILLFRAME);
$graph->SetBackgroundImageMix(100); 

// Create the linear plot
$lineplot=new LinePlot($ydata);
$lineplot->SetColor("blue");
 
// Add the plot to the graph
$graph->Add($lineplot);
 


// Display the graph
$graph->Stroke();

?>
