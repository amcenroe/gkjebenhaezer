<?
require "Include/Config.php";
require "Include/Functions.php";
echo "Check System Date and Time";
echo "<br><b>=========================</b>";
echo "<br>";
echo "<br>";

$sSQL = "SELECT NOW( ) as WaktuDB, CURDATE( ) as TanggalDB, CURTIME( ) as PukulDB ";

$rsWaktu = RunQuery($sSQL);
extract(mysql_fetch_array($rsWaktu));
echo "Current DATABASE Date Time : ";
echo "<b>".$WaktuDB."</b>";
echo "<br>";
echo "SQL = SELECT NOW( ) as WaktuDB";
//echo $TanggalDB;
//echo $PukulDB;

echo "<br>";
echo "<br>";
echo "Current SERVER Date Time : ";
$today = date("Y-m-d G:i:s T Y");      
echo "<b>".$today."</b>";
echo "<br>";
echo "PHP = date(\"Y-m-d G:i:s T Y\")";					
					
?>
<br>
<br>
Current CLIENT Date Time : 
<script type="text/javascript">
<!--
	var currentDate = new Date()
	var day = currentDate.getDate()
	var month = currentDate.getMonth() + 1
	var year = currentDate.getFullYear()
	document.write("<b>" + year + "-" + month + "-" + day + "</b>")
//-->
</script>

<script type="text/javascript">
<!--
	var currentTime = new Date()
	var hours = currentTime.getHours()
	var minutes = currentTime.getMinutes()
	var seconds = currentTime.getSeconds()

	if (minutes < 10)
	minutes = "0" + minutes

	document.write("<b>" + hours + ":" + minutes + ":" + seconds +"</b>")
//-->
</script>
<br>
<?
echo "JAVASCRIPT = var currentDate = new Date() ; var currentTime = new Date()"
?>