<?php
    session_start();
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        mb_internal_encoding("utf8");
        $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
        $sql = "UPDATE users SET delete_flag = 1 WHERE user_id = $user_id ";
        $stmt = $dbh->query($sql);
        
        $sql2 = "UPDATE posts SET delete_flag = 1 WHERE user_id = $user_id ";
        $stmt2 = $dbh->query($sql2);

        header("Location:http://localhost/cafe_app/Cafe_App/toppage.php",true,307);
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>アカウント削除画面</title>
    </head>
    <body>
        <h1 class="heading-lv1 text-center">アカウント削除</h1>
        <h2>本当にアカウントを削除しますか？</h2>
        <p>※アカウント削除を行なった場合、現在のアカウント情報と投稿内容が削除され、復元することができません。</p>
        <form method ="POST" action ="?">
            <input type="submit" name="_method"  value="削除" formaction="delete_account.php">
            <input type="submit" name="_method" value="キャンセル" formaction="profile.php">
        </form>
    </body>
</html>