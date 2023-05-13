<?php
    session_start();
    
    if(!empty($_SESSION['user_id_log'])){
        unset($_SESSION['user_id_log']);
        $param_json=1;
        header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");
    }elseif(!empty($_SESSION['user_id_sign'])){
        unset($_SESSION['user_id_sign']);
        $param_json=1;
        header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");
    }else{
        $param_json = 2;
    }
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script>
    const param = '<?=$param_json?>';
</script>
<script src="login_er.js"></script>
