<?php 

    session_start();
    unset($_SESSION['user_login']);
    unset($_SESSION['user_username']);
    header('location: index.php');

?>