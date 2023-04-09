<?php 
    session_start();

    if(!empty($_SESSION['user_id_log'])){
        unset($_SESSION['user_id_log']);
    }elseif(!empty($_SESSION['user_id_sign'])){
        unset($_SESSION['user_id_sign']);
    }else{
        $user_id = "";
    }

    $err = "";
    $mail_err = "";
    $pas_err = "";
       
        try{
            if ($_SERVER['REQUEST_METHOD'] === 'POST'){
                    
                    $mail = $_POST['mail'];
                    $pas = $_POST['password'];       
        
                    mb_internal_encoding("utf8");
                    $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root",
                        array(
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,//SQL実行失敗の時、例外をスロー
                            PDO::ATTR_EMULATE_PREPARES => false,
                            )   
                        );
                    $sql = "SELECT * from users WHERE mail = ?";//POSTされたメールアドレスのユーザー情報を取得
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindParam(1,$mail);//SELECT文の「？」にPOSTされたメールアドレスをバインド
                    $stmt->execute();//bindParam実行
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
                    if($mail === "" && $pas ===""){
                        $mail_err = "メールアドレスを入力して下さい。";
                        $pas_err = "パスワードを入力して下さい。";
                    }elseif($mail === ""){
                        $mail_err = "メールアドレスを入力して下さい。";
                    }elseif($pas === ""){
                        $pas_err = "パスワードを入力して下さい。";
                    }elseif($result === false){//データベースからユーザーの情報を取得できなかった時（メールアドレス不一致）
                        $err = "エラーが発生したためログイン情報を取得できません。";
                        $mail = "";
                        $pas = "";
                    }elseif(!password_verify($pas, $result['password']) && $mail === $result['mail']){//メールアドレスは一致してるけど、パスワードが一致しなかった時
                        $err = "エラーが発生したためログイン情報を取得できません。";
                        $mail = "";
                        $pas = "";
                    }elseif(password_verify($pas, $result['password']) && $mail === $result['mail']){//メールアドレスとパスワードが一致した時
                        $_SESSION['user_id_log'] = $result['user_id'];
                        header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");
                    }
                }
            }
                    catch(PDOException $e){//データベース接続エラー
                        $db_err = "データベースエラーが発生したためログイン情報を取得できません。";
                     }

?>

<!DOCTYPE html>
<html lang ="ja">
    <head>
        <meta charset = "UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>ログイン画面</title>
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
                <h2>ログイン画面</h2>
                    
                <p class = "text-danger"><?php if(isset($db_err)){
                                                    echo $db_err;
                                            }elseif(isset($err)){
                                                    echo $err;}?></p>
                <form name = "form" action = "login.php" method = "POST">
                    <ul class = "ul2">
                        <li>
                            <label class = "form_name">メールアドレス</label>
                            <input type = "text" name = "mail" maxlength = "100"value = <?php if(isset($mail)){
                                                                                                    echo $mail;
                                                                                                }?>>
                            <p class = "text-danger"><?php echo $mail_err;?></p>
                        </li>    
                        <li>
                            <label class = "form_name">パスワード</label>
                            <input type = "password" name = "password" maxlength = "10" value = <?php if(isset($pas)){
                                                                                                            echo $pas;
                                                                                                        }?>>
                            <p class = "text-danger"><?php echo $pas_err;?></p>
                        </li>
                        <li><input type = "submit" class = "submit2" value="ログイン"></li>
                        <li><a href="signup.php">アカウント新規登録</a></li>
                    </ul>
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