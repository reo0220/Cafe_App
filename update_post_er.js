window.onload = function(){
    if(er_update == 1){
        Swal.fire({
            title: '編集する投稿を選択してください。',
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