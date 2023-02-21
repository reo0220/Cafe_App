<?php
    session_start();
    
    if(!empty($_SESSION['user_id_log'])){
        unset($_SESSION['user_id_log']);
        header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");
    }elseif(!empty($_SESSION['user_id_sign'])){
        unset($_SESSION['user_id_sign']);
        header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");
    }
?>