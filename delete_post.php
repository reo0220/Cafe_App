<?php
    session_start();
    
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }else{
        $param_json = "";
    }
    if(empty($_GET['post_id']) && !empty($user_id)){
        $er_delete_post = "del";
    }

    if(!empty($user_id) && !empty($_GET['post_id'])){
        $post_id = $_GET['post_id'];
        $motourl = $_SERVER['HTTP_REFERER'];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
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
                
                header("Location:$url");
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>投稿削除画面</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    </head>
    <script>
        //ログインまたはアカウント登録していない場合
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
    <script>
        //ログインはしているけど、「削除」ボタンから遷移していない時
        const del = '<?=$er_delete_post?>';
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
                <h1 class="heading-lv1 text-center">投稿削除</h1>
                <h2>本当に投稿を削除しますか？</h2>
                <p>※投稿削除を行なった場合、投稿内容が削除され、復元することができません。</p>
                <form method ="POST" action ="delete_post.php?post_id=<?php echo $post_id;?>">
                    <input type="hidden" name="post_id" value= <?php echo $post_id;?>>
                    <input type="hidden" name="url" value= <?php echo $motourl;?>>
                    <input type="submit" name="_method" value="削除">
                </form>
                <input value="キャンセル" onclick="history.back();" type="button">
            </div>
        </main>  
        <footer class="footer">
            <div>
                フッター
            </div>
        </footer>  
    </body>
</html>