<?php
    session_start();
    
    if(!empty($_SESSION['user_id_log'])){
        unset($_SESSION['user_id_log']);
        header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");
    }elseif(!empty($_SESSION['user_id_sign'])){
        unset($_SESSION['user_id_sign']);
        header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");
    }else{
        $param_json = "";
    }
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script>
            const param = '<?=$param_json?>';
            window.onload = function(){
                        if(param == ""){
                            Swal.fire({
                                title: 'ログインか新規登録を行ってください。',
                                type : 'warning',
                                bottons:true,
                                grow : 'fullscreen',
                                confirmButtonText:"ログインまたは新規登録",
                                allowOutsideClick:false
                            }).then((result) =>{//「ログイン」ボタンをクリックした時、ログイン画面へ遷移
                                if(result.value){
                                        window.location.href ="./login.php";
                                    }
                            });
                        }   
                    }
        </script>
