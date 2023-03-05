<?php
    session_start();
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }

    $post_id = $_GET['post_id'];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        try{
            mb_internal_encoding("utf8");
            $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root",
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        )   
                    );
                    $sql = "UPDATE posts SET delete_flag = 1 WHERE post_id= $post_id";
                    $stmt = $dbh->query($sql);
            }
            catch(PDOException $e){
                $db_error = "エラーが発生したためアカウント削除できません。";
                }
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>投稿削除画面</title>
    </head>
    <body>
        <h1 class="heading-lv1 text-center">投稿削除</h1>
        <h2>本当に投稿を削除しますか？</h2>
        <p>※投稿削除を行なった場合、投稿内容が削除され、復元することができません。</p>
        <form method ="POST" action ="?">
            <input type="submit" name="_method"  value="削除" formaction="delete_post.php">
            <input type="submit" name="_method" value="キャンセル" formaction="post_list.php">
        </form>
    </body>
</html>