<?php
    session_start();
    
    if(!empty($_SESSION['user_id_log'])){
        unset($_SESSION['user_id_log']);
        $param_json=1;
        header("Location:https://cafe23-169d1a25357a.herokuapp.com//index.php");
    }elseif(!empty($_SESSION['user_id_sign'])){
        unset($_SESSION['user_id_sign']);
        $param_json=1;
        header("Location:https://cafe23-169d1a25357a.herokuapp.com//index.php");
    }else{
        $param_json = 2;
    }
?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script>
    const param = '<?=$param_json?>';
</script>
<script src="login_er.js"></script>
