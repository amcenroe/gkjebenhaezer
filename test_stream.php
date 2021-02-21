
<?php
function curl_get_file_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) return $contents;
            else return FALSE;
    }

//$homepage = file_get_contents("http://www.gkjbekasitimur.org/datawarga/PrintViewPermohonanPF.php?PelayanFirmanID=596&Mode=1");
//echo $homepage;

curl_get_file_contents("http://www.gkjbekasitimur.org/datawarga/PrintViewPermohonanPF.php?PelayanFirmanID=596&Mode=1");

?>