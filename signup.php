<?php
session_start();

if(!empty($_SESSION['user_id_log'])){
    unset($_SESSION['user_id_log']);
}elseif(!empty($_SESSION['user_id_sign'])){
    unset($_SESSION['user_id_sign']);
}else{
    $user_id = "";
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];
    if($name === ""){
        $error1 = "ニックネームが未入力です。";
    }
    
    $mail = $_POST['mail'];
    if($mail === ""){
        $error2 = "メールアドレスが未入力です。";
    }elseif(!empty($mail)){//メールアドレスがデータベースに存在するかチェック
        mb_internal_encoding("utf8");
        $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
        $sql = "SELECT * FROM users WHERE mail = '$mail' ";
        $stmt = $dbh->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
            $error4 = "既に入力したメールアドレスが存在します。";
        }
    }
    
    $password = $_POST['password'];
    if($password === ""){
        $error3 = "パスワードが未入力です。";
    }
   
    if(!isset($error1) && !isset($error2) && !isset($error3) && !isset($error4)){
            try{
                mb_internal_encoding("utf8");
                $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root",//データベース接続
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,//SQL実行失敗の時、例外をスロー
                        PDO::ATTR_EMULATE_PREPARES => false,
                        )   
                    );
                $dbh -> exec("insert into users(name,mail,password,delete_flag)
                values('".$_POST['name']."','".$_POST['mail']."','".password_hash($_POST['password'],PASSWORD_DEFAULT)."','0');");
                $_SESSION['user_id_sign'] = $dbh->lastInsertId();//セッションに、登録した「user_id」を代入
                }
                catch(PDOException $e){//DB接続エラーが発生した時$db_errorを定義
                    $db_error = "エラーが発生したためアカウント登録できません。";
                }
                header("Location:http://localhost/cafe_app/Cafe_App/profile.php");
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>アカウント新規登録画面</title>
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
                            <li class="nav-items__item"></li>
                            <li class="nav-items__item"><a href ="toppage.php">トップページ</a></li>
                            <li class="nav-items__item"><a href="post_list.php">投稿一覧</a></li>
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
                <h1>アカウント新規登録</h1>
                <form class = "form" method='POST' action='signup.php'>
                    <label>ニックネーム</labal>    
                    <input type ="text" name = "name"><br>
                    <?php if(!empty($error1)):?>
                        <p class="text-danger"><?php echo $error1 ?></p><!--$error1が空じゃないときエラーメッセージ表示-->
                    <?php endif; ?>
                
                    <label>メールアドレス</labal>    
                    <input type ="text" name = "mail"><br>
                    <p class='text-danger'>
                    <?php if(!empty($error2)){
                        echo $error2;
                    }elseif(!empty($error4)){
                        echo $error4;
                    }?></p>
                        
                    <label>パスワード</labal>    
                    <input type ="password" name = "password"><br>
                    <?php if(!empty($error3)):?>
                        <p class="text-danger"><?php echo $error3 ?></p>
                    <?php endif; ?>
                    
                    <input type = "submit" value = "登録">
                </form>       
            </div>
        </main>  
        <footer class="footer">
            <div>
                フッター
            </div>
        </footer> 
    </body>
</html>