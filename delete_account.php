<?php
    session_start();
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
        $param_json = 1;
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
        $param_json = 1;
    }else{
        $param_json = 2;
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        
        $dbh = new PDO('mysql:dbname=heroku_f42c30f1b2af6d1;host=us-cdbr-east-06.cleardb.net;charset=utf8','bc9c8df67ff0e5','10b87118');
        $sql = "UPDATE users SET delete_flag = 1 WHERE user_id = $user_id ";
        $stmt = $dbh->query($sql);
        
        $sql2 = "UPDATE posts SET delete_flag = 1 WHERE user_id = $user_id ";
        $stmt2 = $dbh->query($sql2);

        $_SESSION['user_delete'] = "delete";

        header("Location:https://cafe23.herokuapp.com/index.php");
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>アカウント削除画面</title>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
        <script>
            const param = '<?=$param_json?>';
        </script>
        <script src="login_er.js"></script>
    </head>
    <body>
         <div class="container">
            <header class="header">
                <div class="header__inner">
                    <h1 class="header__title header-title">
                        <a href="index.php">Cafe23</a>
                    </h1>
                    <nav class="header__nav nav" id="js-nav">
                        <ul class="nav__items nav-items">
                            <li class="nav-items__item"><a href ="index.php">トップページ</a></li>
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
                        <script src="header.js"></script>
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
