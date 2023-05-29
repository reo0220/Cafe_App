<?php
    session_start();
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }else{
        $user_id = "";
    }

    try{
        $dbh=new PDO('mysql:dbname=heroku_f42c30f1b2af6d1;host=us-cdbr-east-06.cleardb.net;charset=utf8','bc9c8df67ff0e5','10b87118');

        $sql_post = "SELECT
                    DISTINCT 
                        posts.post_id,
                        posts.user_id,
                        posts.name AS posts_name,
                        posts.place,
                        posts.price,
                        posts.comment,
                        posts.like_count,
                        post_medias.first_file_name,
                        post_medias.second_file_name,
                        post_medias.third_file_name,
                        post_medias.fourth_file_name,
                        users.name AS users_name,
                        user_medias.file_name AS user_medias_file_name
                    FROM 
                        posts
                    INNER JOIN
                        post_medias ON posts.post_id = post_medias.post_id
                    LEFT JOIN 
                        users ON posts.user_id = users.user_id
                    LEFT JOIN
                        user_medias ON users.user_id = user_medias.user_id
                    WHERE
                        posts.delete_flag = '0'
                    ORDER BY 
                        posts.like_count DESC
                    LIMIT
                        5";
            
            $stmt = $dbh->query($sql_post);

    }catch(PDOException $e){
        print('DB接続エラー:'.$e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>トップページ</title>
    </head>
    <body>
        <?php 
            foreach($stmt as $row){
                echo $row['users_name'];
            }
        ?>
    </body>
</html>