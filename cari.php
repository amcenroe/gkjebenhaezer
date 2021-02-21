<?php
/*******************************************************************************
 *
 *  filename    : cari.php
 *  last change : 2012-10-31
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2010 Erwin Pratama for GKJ Bekasi Timur
  ******************************************************************************/
?>

<form action="cari.php" method="POST">
<center><input type="text" name="search"/><br/>
<input type="submit" value="Silahkan Telusuri Alkitab atau Kidung Nyanyian" name="submit">
</center></form>

<?php if (isset($_POST['submit'])) 
{

// meimisahkan kitab dan kidung

$string = $_POST['search'];
$words = explode(' ', $string);
//var_dump($words);

$to_keep = array_slice($words, 0, 2);
//var_dump($to_keep);

$final_string = implode(' ', $to_keep);
//echo $to_keep[0];

//echo $hasilGD[ID];echo " - ";

$str = strtoupper(substr($to_keep[0], 0, 2));
if ($str=="KJ"){
echo "Kidung Jemaat";
$a = '<iframe frameborder="0" align="middle" width="800" height="5000" src="http://kidung.co/'.$_POST['search'].'&printable=yes "></iframe>';
} else if  ($str=="NK"){
echo "Nyanyian Kidung Baru";
$a = '<iframe frameborder="0" align="middle" width="800" height="5000" src="http://kidung.co/'.$_POST['search'].'&printable=yes "></iframe>';
}else if  ($str=="PK"){
echo "Pelengkap Kidung Jemaat";
$a = '<iframe frameborder="0" align="middle" width="800" height="5000" src="http://kidung.co/'.$_POST['search'].'&printable=yes "></iframe>';
}else {
echo "Alkitab";
$a = '<iframe frameborder="0" align="middle" width="800" height="5000" src="http://alkitab.sabda.org/passage.php?passage='.$_POST['search'].'&mode=print"></iframe>';
} 





//echo htmlentities($a);
echo "<center>";
echo $a;
echo "</center>";
}
?>