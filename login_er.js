window.onload = function(){
    if(param == 2){
        Swal.fire({
            title: 'ログインか新規登録を行ってください。',
            type : 'warning',
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