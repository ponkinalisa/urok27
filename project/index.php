<?php 
session_start();
if (!isset($_SESSION['id']) or !isset($_SESSION['login'])){
    header('Location: main/php/login.php');
}else{
    header('Location: main/php/start_screen.php');
}
?>