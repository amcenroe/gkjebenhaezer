<?php
//    Header("Content-type: image/jpeg");
//    $exp=GMDate("D, d M Y H:i:s",time()+999);
//    Header("Expires: $exp GMT");
$image_path = "Images/Person/thumbnails/";
    $file=$image_path . $_GET["img"];
    if (file_exists($file)){
echo $file;
//  $info=getimagesize($file);
//    $width=$info[0];
//    $height=$info[1];

//  if ($info[2]==1){
//        $img=@imagecreatefromgif($file);
//    } else if ($info[2]==2){
//        $img=@imagecreatefromjpeg($file);
//    } else if ($info[2]==3){
//        $img=@imagecreatefrompng($file);
//    } else {
//        $width=640;
//        $height=480;
//        $file = 'noimage.png';
//        $img=@imagecreatefrompng($file);
//    }

$img=@imagecreatefromjpeg($file);
echo $img;
    ImageJpeg($img);
    imagedestroy($img);

?>