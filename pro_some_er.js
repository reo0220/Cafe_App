//プロフィール画像から遷移していない時
window.onload = function(){
    if(er_user == 1){
        Swal.fire({
            title: '表示したいアカウントのプロフィール画像をクリックしてください。',
            type : 'warning',
            bottons:true,
            grow : 'fullscreen',
            confirmButtonText:"アカウントを選択",
            allowOutsideClick:false
        }).then((result) =>{
            if(result.value){
                window.location.href ="./post_list.php";
            }
        });
    }
}