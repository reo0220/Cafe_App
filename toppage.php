<?php 
    session_start();
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        unset($_SESSION['user_id_log']);
        unset($_SESSION['user_id_sign']);
    }
    
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>トップページ</title>
    </head>
    <body>
        <h1 class="heading-lv1 text-center">トップページ</h1>
        
        <a href ="profile.php">プロフィール画面</a>
        <a href ="create_post.php">投稿作成画面</a>
        <a href ="post_list.php">投稿一覧画面</a>

    </body>
</html>