<?php
session_start();

if(isset($_POST['widthBox'])
&& isset($_POST['heightBox'])
&& isset($_POST['leftBox'])
&& isset($_POST['topBox']))
{
    $_SESSION['crop'] = 1;
    $_SESSION['width'] = $_POST['widthBox'];
    $_SESSION['height'] = $_POST['heightBox'];
    $_SESSION['top'] = $_POST['topBox'];
    $_SESSION['left'] = $_POST['leftBox'];
    header('Location: preview.php');
}
else
{
    echo "Error!";
}
?>