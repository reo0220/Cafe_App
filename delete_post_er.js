//ログインはしているけど、「削除」ボタンから遷移していない時
window.onload = function(){
    if(del == "del"){
        Swal.fire({
            title: '削除する投稿を選択してください。',
            type : 'warning',
            bottons:true,
            grow : 'fullscreen',
            confirmButtonText:"投稿を選択",
            allowOutsideClick:false
        }).then((result) =>{
            if(result.value){
                window.location.href ="./profile.php";
            }
        });
    }
}