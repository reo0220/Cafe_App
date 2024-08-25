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
        
                    $dbh = new PDO('mysql:dbname=heroku_a8ae41c85a24286;host=us-cluster-east-01.k8s.cleardb.net;charset=utf8','b5d8de2f7148b2','1bf59141',
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
                    }elseif($result['delete_flag'] === 1){
                        $err = "削除されたアカウントのためログインできません。";
                        $mail = "";
                        $pas = "";
                    }elseif(password_verify($pas, $result['password']) && $mail === $result['mail']){//メールアドレスとパスワードが一致した時
                        $_SESSION['user_id_log'] = $result['user_id'];
                        header("Location:https://cafe23-169d1a25357a.herokuapp.com/profile.com");
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
        <link href="//use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
        <title>ログイン画面</title>
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
                            <li class="nav-items__item"></li>
                            <li class="nav-items__item"><a href ="index.php">トップページ</a></li>
                            <li class="nav-items__item"><a href="post_list.php">投稿一覧</a></li>
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
                <h1 class="heading-lv1 text-center">ログイン画面</h1>
                <div class="form-wrapper">
                    <h2 class="log">ログイン</h2>
                    <p class = "text-danger"><?php if(isset($db_err)){
                                                                        echo $db_err;
                                                                }elseif(isset($err)){
                                                                        echo $err;}?></p>
                    <form name = "form" action = "login.php" method = "POST">
                        <div class="form-item">
                        <label for="mail"></label>
                        <input type="text" name="mail" placeholder="メールアドレス" value = <?php 
                                                                                                if(isset($mail)){
                                                                                                    echo $mail;
                                                                                                }
                                                                                            ?>>
                        </input>
                        <p class = "text-danger"><?php echo $mail_err;?></p>
                        </div>
                        <div class="form-item">
                        <label for="password"></label>
                        <input type="password" id="textPassword" name="password" placeholder="パスワード" value = <?php 
                                                                                                    if(isset($pas)){
                                                                                                        echo $pas;
                                                                                                    }
                                                                                                ?>>
                        </input>
                        <span id="buttonEye" class="fa fa-eye" onclick="pushHideButton()"></span>
                        <p class = "text-danger"><?php echo $pas_err;?></p>
                        </div>
                        <div class="button-panel">
                            <input type="submit" class="button" value="ログイン"></input>
                        </div>
                    </form>
                    <div class="form-footer">
                        <p><a href="signup.php">アカウント新規登録</a></p>
                    </div>
                </div>
            </div>
            <script src="pas_eye.js"></script>
        </main>  
        <footer class="footer">
            <div>
                <p>&copy; ReoKodama. 2023.</p>
            </div>
        </footer>  
    </body>
</html>