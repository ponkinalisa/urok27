<?php 
include('includes/functions.php');
session_start();
if (isset($_SESSION['path']) and isset($_SESSION['type'])){
    $path = $_SESSION['path'];
    $type = $_SESSION['type'];
    switch ($type) {
        case 'image/gif':
            $img = imageCreateFromGif($path);
            if ($_SESSION['blur'] == 1){
                blur($img);
            }
            if ($_SESSION['wb'] == 1){
                bw($img);
            }
            if ($_SESSION['invers'] == 1){
                negate($img);
            }
            $img = rotate($img, (int)$_SESSION['rotation']);
            $img = scale($img);
            header('Content-Type: image/gif');
            imagepgif($img);
            imagedestroy($img);
            break;
        case 'image/jpg':
        case 'image/jpeg':
            $img = imageCreateFromJpeg($path);
            if ($_SESSION['blur'] == 1){
                blur($img);
            }
            if ($_SESSION['wb'] == 1){
                bw($img);
            }
            if ($_SESSION['invers'] == 1){
                negate($img);
            }
            $img = rotate($img, (int)$_SESSION['rotation']);
            $img = scale($img);
            header('Content-Type: image/jpeg');
            imagejpeg($img);
            imagedestroy($img);
            break;
        case 'image/png':
            $img = imageCreateFromPng($path);
            if ($_SESSION['blur'] == 1){
                blur($img);
            }
            if ($_SESSION['wb'] == 1){
                bw($img);
            }
            if ($_SESSION['invers'] == 1){
                negate($img);
            }
            $img = rotate($img, (int)$_SESSION['rotation']);
            $img = scale($img);
            header('Content-Type: image/png');
            imagepng($img);
            imagedestroy($img);
            break;
        }
}
?>