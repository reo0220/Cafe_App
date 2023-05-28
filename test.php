<?php
    session_start();
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }else{
        $param_json = 2;
    }

    try{
        $db=new PDO('mysql:dbname=heroku_f42c30f1b2af6d1;host=us-cdbr-east-06.cleardb.net;charset=utf8','bc9c8df67ff0e5','10b87118');

        $sql_post = "SELECT
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
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>トップページ</title>
        <link href="//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
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
                            <li class="nav-items__item"><a href ="index.php">トップページ</a></li>
                            <li class="nav-items__item"><a href="post_list.php">投稿一覧</a></li>
                            <?php
                                if($user_id != ""){
                                    echo "<li class='nav-items__item'><a href='create_post.php'>投稿作成</a></li>";
                                }
                            ?>
                            <?php 
                                if($user_id != ""){
                                    echo  "<li class='nav-items__item'><a href='profile.php'>プロフィール</a></li>";
                                }else{
                                    echo  "<li class='nav-items__item'><a href='login.php'>ログインまたは新規登録</a></li>";
                                }
                            ?>
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
        <main class = "main1">
            <div class="main3">
                <div class="back_img">
                    <div class="back_img_text">
                        <h1 class="back_img_text1">東京23区のカフェを投稿・検索できるアプリ</br>
                            <?php
                                if(!empty($_SESSION['user_id_sign']) || !empty($_SESSION['user_id_log'])){
                                    echo "";
                                }else{
                                    echo "<a class='top_a' href='signup.php'>アカウント登録はこちら</a>";
                                }
                            ?>
                        </h1>
                    </div>
                </div>
                <div class="top_post">
                    <h2 class="heading-lv1 text-center1">人気投稿</h2>
                    <?php foreach($stmt as $row){?>
                    <div class="post">
                        <?php echo $row['users_name'];?>
                    </div>
                    <?php }?>
                </div>
            </div>
        </main>  
        <footer class="footer">
            <div>
                <p>&copy; ReoKodama. 2023.</p>
            </div>
        </footer>  
    </body>
</html>