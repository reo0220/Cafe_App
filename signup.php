<?php
session_start();

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
        <div class = "container">
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
    </body>
</html>