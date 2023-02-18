<?php 
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
                        $_SESSION['mail'] = $result['mail'];
                        header("Location:http://localhost/cafe_app/Cafe_App/toppage.php",true,307);
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
        <h2>ログイン画面</h2>
            <main>
                <p class = "login_err"><?php if(isset($db_err)){
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
                            <p class = "login_err2"><?php echo $mail_err;?></p>
                        </li>    
                        <li>
                            <label class = "form_name">パスワード</label>
                            <input type = "password" name = "password" maxlength = "10" value = <?php if(isset($pas)){
                                                                                                            echo $pas;
                                                                                                            }?>>
                            <p class = "login_err2"><?php echo $pas_err;?></p>
                        </li>
                        <li><input type = "submit" class = "submit2" value="ログイン"></li>
                    </ul>
                </form>    
            </main>
    </body>
</html>