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
    if(empty($_GET['post_id']) && !empty($user_id)){
        $er_delete_post = "del";
    }

    if(!empty($user_id) && !empty($_GET['post_id'])){
        $post_id = $_GET['post_id'];
        $er_delete_post = "del1";

        if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['post_id'])){
            $post_id_delete = $_POST['post_id'];
            $url = $_POST['url'];

            try{
                mb_internal_encoding("utf8");
                $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root",
                        array(
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_EMULATE_PREPARES => false,
                            )   
                        );
                        $sql = "UPDATE posts SET delete_flag = 1 WHERE post_id= $post_id_delete";
                        $stmt = $dbh->query($sql);
                }
                catch(PDOException $e){
                    $db_error = "エラーが発生したためアカウント削除できません。";
                }
                
                header("Location:http://localhost/cafe_app/Cafe_App/profile.php");
        }
    }
?>

<!--エラー表示-->
<?php if(!empty($_SESSION['user_id_log']) || !empty($_SESSION['user_id_sign'])):?>
    <script>
        const del = '<?=$er_delete_post?>';
    </script>
    <script src="delete_post_er.js"></script>
<?php elseif(empty($_SESSION['user_id_log']) || empty($_SESSION['user_id_sign'])):?>
    <script>
        const param = '<?=$param_json?>';
    </script>
    <script src="login_er.js"></script>
<?php endif;?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>投稿削除画面</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    </head>
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
                        <script src="header.js"></script>
                    </button>
                </div>
            </header>
        </div>
        <main class = "main0">
            <div class="main2">
                <div class="del_post">
                    <h1 class="del_post_h1">投稿削除</h1></br>
                    <h2 class="del_post_h2">本当に投稿を削除しますか？</h2></br>
                    <p class="del_post_p">※投稿削除を行なった場合、投稿内容が削除され、復元することができません。</p>
                    <form method ="POST" action ="?">
                        <input type="hidden" name="post_id" value= <?php echo $post_id;?>>
                        <div class="button-panel">
                            <input type="submit" class="button2" name="_method" value="削除" formaction=<?php echo "delete_post.php?post_id=$post_id";?>>
                            <input type="submit" class="button1" name="_method" value="キャンセル" formaction="profile.php">
                        <div>
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