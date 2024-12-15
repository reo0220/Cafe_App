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
        $dbh = new PDO('mysql:host=lmag6s0zwmcswp5w.cbetxkdyhwsb.us-;dbname=aebifro9tkb2dfum','yao2hpzrt6mfkfn1', 'ck3eakt3fp2npl6a');
        $sql = "SELECT * FROM users WHERE mail = '$mail' ";
        $stmt = $dbh->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($result) && $result['delete_flag'] === "0"){
            $error4 = "既に入力したメールアドレスが存在します。";
        }
    }
    
    $password = $_POST['password'];
    if($password === ""){
        $error3 = "パスワードが未入力です。";
    }
   
    if(!isset($error1) && !isset($error2) && !isset($error3) && !isset($error4)){
            try{
                $dbh = new PDO('mysql:host=lmag6s0zwmcswp5w.cbetxkdyhwsb.us-;dbname=aebifro9tkb2dfum','yao2hpzrt6mfkfn1', 'ck3eakt3fp2npl6a',//データベース接続
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,//SQL実行失敗の時、例外をスロー
                        PDO::ATTR_EMULATE_PREPARES => false,
                        )   
                    );
                $dbh -> exec("insert into users(name,mail,password,delete_flag)
                values('".$_POST['name']."','".$_POST['mail']."','".password_hash($_POST['password'],PASSWORD_DEFAULT)."','0');");
                $_SESSION['user_id_sign'] = $dbh->lastInsertId();//セッションに、登録した「user_id」を代入
                $user_id_media = $_SESSION['user_id_sign'];
                
                }
                catch(PDOException $e){//DB接続エラーが発生した時$db_errorを定義
                    $db_error = "エラーが発生したためアカウント登録できません。";
                }
                
                $dbh1 = new PDO('mysql:host=lmag6s0zwmcswp5w.cbetxkdyhwsb.us-;dbname=aebifro9tkb2dfum','yao2hpzrt6mfkfn1', 'ck3eakt3fp2npl6a');
                $sql_df = "SELECT * FROM user_medias WHERE media_id = 94";
                $stmt_df = $dbh1->query($sql_df);
                $result_df = $stmt_df->fetch(PDO::FETCH_ASSOC);
                $media_df = $result_df['file_name'];
                
                $sql_media = "INSERT INTO user_medias(file_name,user_id) VALUES ('$media_df','$user_id_media')";
                $stmt1 = $dbh1->prepare($sql_media);
                $stmt1->execute();
                header("Location:https://cafe23-169d1a25357a.herokuapp.com//profile.php");
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <link href="//use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
        <title>アカウント新規登録画面</title>
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
                <h1 class="heading-lv1 text-center">アカウント新規登録画面</h1>
                <div class="form-wrapper">
                    <h2 class="log">アカウント新規登録</h2>
                    <form class = "form" method='POST' action='signup.php'>
                        <div class="form-item">
                            <label for="name"></label>
                            <input type ="text" name = "name" placeholder="ニックネーム" pattern=".*\S+.*" title="スペースを削除してください。" value=<?php 
                                                                                                                                                    if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])){
                                                                                                                                                        echo $_POST['name'];
                                                                                                                                                    }
                                                                                                                                                ?>>
                            </input>
                            <?php if(!empty($error1)):?>
                                <p class="text-danger"><?php echo $error1 ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-item">
                            <label for="mail"></label> 
                            <input type ="text" name = "mail" placeholder="メールアドレス" pattern=".*\S+.*" title="スペースを削除してください。" value=<?php 
                                                                                                                                                        if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mail'])){
                                                                                                                                                            echo $_POST['mail'];
                                                                                                                                                        }
                                                                                                                                                    ?>>
                            </input>
                            <?php if(!empty($error2)):?>
                                <p class='text-danger'><?php echo $error2;?></p>
                            <?php elseif(!empty($error4)):?>
                                <p class='text-danger'><?php echo $error4;?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-item">
                            <label for="password"></label> 
                            <input type ="password" id="textPassword" name = "password" placeholder="パスワード" pattern=".*\S+.*" title="スペースを削除してください。" value=<?php 
                                                                                                                                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password'])){
                                                                                                                                                                                echo $_POST['password'];
                                                                                                                                                                            }
                                                                                                                                                                        ?>>
                            </input>
                            <span id="buttonEye" class="fa fa-eye" onclick="pushHideButton()"></span>
                            <?php if(!empty($error3)):?>
                                <p class="text-danger"><?php echo $error3 ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="button-panel">
                            <input type="submit" class="button" value="登録"></input>
                        </div>
                    </form>
                    <div class="form-footer">
                        <p><a href="login.php">ログイン</a></p>
                    </div>
                </div>
            </div>
            <script src="pas_eye.js"></script>
        </main>  
        <footer class="footer">
            <div>
                <p>&copy; ReoKodama. 2024.</p>
            </div>
        </footer> 
    </body>
</html>