<?php

ini_set("gd.jpeg_ignore_warning", 1);

function compressImage($source, $destination, $quality)
{
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            $image = imagecreatefromjpeg($source);
    }

    imagejpeg($image, $destination, $quality);

    return $destination;
}
$uploadPath = "uploads/";
$allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'tif');