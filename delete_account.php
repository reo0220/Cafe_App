<?php
    session_start();
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }else{
        $param_json = "";
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        mb_internal_encoding("utf8");
        $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
        $sql = "UPDATE users SET delete_flag = 1 WHERE user_id = $user_id ";
        $stmt = $dbh->query($sql);
        
        $sql2 = "UPDATE posts SET delete_flag = 1 WHERE user_id = $user_id ";
        $stmt2 = $dbh->query($sql2);

        $_SESSION['user_delete'] = "delete";

        header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>アカウント削除画面</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    </head>
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
                            }).then((result) =>{//「ログインまたは新規登録」ボタンをクリックした時、ログイン画面へ遷移
                                if(result.value){
                                        window.location.href ="./login.php";
                                    }
                            });
                    }
                }
    </script>
    <body>
         <div class="container">
            <header class="header">
                <div class="header__inner">
                    <h1 class="header__title header-title">
                        <a href="toppage.php">Cafe23</a>
                    </h1>
                    <nav class="header__nav nav" id="js-nav">
                        <ul class="nav__items nav-items">
                            <li class="nav-items__item"><a href ="toppage.php">トップページ</a></li>
                            <li class="nav-items__item"><a href="post_list.php">投稿一覧</a></li>
                            <li class="nav-items__item"><a href="create_post.php">投稿作成</a></li>
                            <?php 
                                if(isset($user_id)){
                                    echo  "<li class='nav-items__item'><a href='profile.php'>プロフィール</a></li>";
                                }else{
                                    echo  "<li class='nav-items__item'><a href='login.php'>ログインまたは新規登録</a></li>";
                                }
                            ?>
                        </ul>
                    </nav>
                    <button class="header__hamburger hamburger" id="js-hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                        <script>
                            const ham = document.querySelector('#js-hamburger');
                            const nav = document.querySelector('#js-nav');

                            ham.addEventListener('click', function () {
                                ham.classList.toggle('active');
                                nav.classList.toggle('active');
                            });
                        </script>
                    </button>
                </div>
            </header>
        </div>
        <main class = "main0">
            <div class="main2">
                <div class="del_post">
                    <h1 class="del_post_h1">アカウント削除</h1>
                    <h2 class="del_post_h2">本当にアカウントを削除しますか？</h2>
                    <p class="del_post_p">※アカウント削除を行なった場合、現在のアカウント情報と投稿内容が削除され、復元することができません。</p>
                    <form method ="POST" action ="?">
                        <div class="button-panel">
                            <input type="submit" class="button2" name="_method"  value="削除" formaction="delete_account.php">
                            <input type="submit" class="button1" name="_method" value="キャンセル" formaction="profile.php">
                        </div>
                    </form>
                </div>
            </div>
        </main>  
        <footer class="footer">
            <div>
                <p>&copy; ReoKodama. 2023.</p>
            </div>
        </footer>  
    </body>
</html>
