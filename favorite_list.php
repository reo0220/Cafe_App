<?php
    session_start();
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }else{
        $param_json = "";
    }

    if(!empty($user_id)){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!empty($_POST['post_id'])){
                if($_POST['button'] === "行ってみたい" || $_POST['button'] === "行ってみたい解除"){
                    $post_id_good = $_POST['post_id'];
                    $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
                    $sql_like_button = "SELECT * FROM post_likes WHERE user_id = $user_id AND post_id = $post_id_good";
                    $stmt_like = $dbh->query($sql_like_button);
                    $result_like = $stmt_like->fetch(PDO::FETCH_ASSOC);
                    
                    if($_POST['button'] === "行ってみたい" && empty($result_like)){
                        $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
                        $sql_like_count = "UPDATE posts SET like_count = like_count + 1 WHERE post_id = $post_id_good";
                        $sql_post_like = "INSERT INTO post_likes(user_id,post_id) VALUES($user_id,$post_id_good)";
                        $stmt = $dbh->query($sql_like_count);
                        $stmt = $dbh->query($sql_post_like);
                    }elseif($_POST['button'] === "行ってみたい解除" && !empty($result_like)){
                        $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
                        $sql_like_count = "UPDATE posts SET like_count = like_count - 1 WHERE post_id = $post_id_good";
                        $sql_post_like = "DELETE from post_likes WHERE user_id = $user_id AND post_id = $post_id_good";
                        $stmt = $dbh->query($sql_like_count);
                        $stmt = $dbh->query($sql_post_like);
                    }
                }
            }
        }
        

        try{
            mb_internal_encoding("utf8");
            $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root",
                            array(
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,//SQL実行失敗の時、例外をスロー
                                PDO::ATTR_EMULATE_PREPARES => false,
                                )   
                            );
            $sql_user = "SELECT * FROM users WHERE user_id = $user_id";
            $stmt_user = $dbh->query($sql_user);
            $result_user = $stmt_user->fetch(PDO::FETCH_ASSOC);
                        }
                        catch(PDOException $e){//DB接続エラーが発生した時$db_errorを定義
                            $db_error = "エラーが発生したためアカウント登録できません。";
                        }
            $sql_user_media = "SELECT * FROM user_medias WHERE user_id = $user_id";
            $stmt_user_media = $dbh->query($sql_user_media);
            $result_user_media = $stmt_user_media->fetch(PDO::FETCH_ASSOC);

            $sql_post = "SELECT
                            post_likes.user_id,
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
                            post_likes
                        INNER JOIN
                            posts ON post_likes.post_id=posts.post_id                       
                        INNER JOIN
                            post_medias ON post_likes.post_id = post_medias.post_id
                        LEFT JOIN 
                            users ON posts.user_id = users.user_id
                        LEFT JOIN
                            user_medias ON users.user_id = user_medias.user_id
                        WHERE
                            posts.delete_flag = '0' AND post_likes.user_id = $user_id
                        ORDER BY 
                            posts.registered_time DESC";
            
            $stmt_post = $dbh->query($sql_post);
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>行ってみたいリスト</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
    </head>
    <script>
            const param = '<?=$param_json?>';
            window.onload = function(){
                if(param == ""){
                    Swal.fire({
                        title: 'ログインか新規登録を行ってください。',
                        type : 'warning',
                        bottons:true,
                        grow : 'fullscreen',
                        confirmButtonText:"ログインまたは新規登録",
                        allowOutsideClick:false
                    }).then((result) =>{//「ログイン」ボタンをクリックした時、ログイン画面へ遷移
                        if(result.value){
                            window.location.href ="./login.php";
                        }
                    });
                }   
            }
    </script>
    <body>
        <div class="container">
            <header class="header">
                <div class="header__inner">
                    <h1 class="header__title header-title">
                        <a href="toppage.php">Cafe23</a>
                    </h1>
                    <nav class="header__nav nav" id="js-nav">
                        <ul class="nav__items nav-items">
                            <li class="nav-items__item"><a href ="toppage.php">トップページ</a></li>
                            <li class="nav-items__item"><a href="post_list.php">投稿一覧</a></li>
                            <li class="nav-items__item"><a href="create_post.php">投稿作成</a></li>
                            <?php 
                                if(isset($user_id)){
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
        <main class = "main1">
            <div class="main2">
                <h1 class="heading-lv1 text-center">Profile</h1>
                <figure class="profile-image">
                    <a href="user_medias/<?php echo $result_user_media['file_name']; ?>" rel='lightbox'><img src="user_medias/<?php echo $result_user_media['file_name']; ?>" width="300" height="300"></a>
                </figure>
                <h2 class="heading-lv2 text-center"><?php echo $result_user['name'];?></h2>
                
                <h3 class="heading-lv3 text-center">好きなジャンル</h3>
                <p class="text text-center"><?php echo $result_user['favorite_genre'];?></p>

                <h3 class="heading-lv3 text-center">好きなメニュー</h3>
                <p class="text text-center"><?php echo $result_user['favorite_menu'];?></p>

                <h3 class="heading-lv3 text-center">自己紹介</h3>
                <p class="text text-center"><?php echo $result_user['about_me'];?></p>

                <script>
                    function screenChange(){
                            pullSellect = document.pullForm.pullMenu.selectedIndex ;
                            location.href = document.pullForm.pullMenu.options[pullSellect].value ;
                                        }
                </script>
                <form name="pullForm">
                    <select name="pullMenu" onChange="screenChange()">
                        <option></option>
                        <option value= "edit_account.php">アカウント編集</option>
                        <option value="delete_account.php">アカウント削除</option>
                        <option value="logout.php">ログアウト</option>
                    </select>
                </form>
                <h1 class="heading-lv1 text-center">行ってみたいリスト</h1>
                    <?php foreach($stmt_post as $row){?>
                        <ul>
                            <li><img class="profile_img" src="user_medias/<?php echo $row['user_medias_file_name']; ?>" alt="プロフィール写真" width="50" height="50"></li>
                            <li><?php echo $row['users_name'];?></li>
                            <li><?php echo $row['posts_name'];?></li>
                            <li>
                                <?php if($row['place'] === 1){
                                            echo "千代田区";
                                        }elseif($row['place'] === 2){
                                            echo "中央区";
                                        }elseif($row['place'] === 3){
                                            echo "港区";
                                        }elseif($row['place'] === 4){
                                            echo "新宿区";
                                        }elseif($row['place'] === 5){
                                            echo "文京区";
                                        }elseif($row['place'] === 6){
                                            echo "台東区";
                                        }elseif($row['place'] === 7){
                                            echo "墨田区";
                                        }elseif($row['place'] === 8){
                                            echo "江東区";
                                        }elseif($row['place'] === 9){
                                            echo "品川区";
                                        }elseif($row['place'] === 10){
                                            echo "目黒区";
                                        }elseif($row['place'] === 11){
                                            echo "大田区";
                                        }elseif($row['place'] === 12){
                                            echo "世田谷区";
                                        }elseif($row['place'] === 13){
                                            echo "渋谷区";
                                        }elseif($row['place'] === 14){
                                            echo "中野区";
                                        }elseif($row['place'] === 15){
                                            echo "杉並区";
                                        }elseif($row['place'] === 16){
                                            echo "豊島区";
                                        }elseif($row['place'] === 17){
                                            echo "北区";
                                        }elseif($row['place'] === 18){
                                            echo "荒川区";
                                        }elseif($row['place'] === 19){
                                            echo "板橋区";
                                        }elseif($row['place'] === 20){
                                            echo "練馬区";
                                        }elseif($row['place'] === 21){
                                            echo "足立区";
                                        }elseif($row['place'] === 22){
                                            echo "葛飾区";
                                        }elseif($row['place'] === 23){
                                            echo "江戸川区";
                                        }?>
                            <li>
                                <?php if($row['price'] === 1){
                                    echo "0円〜500円";
                                }elseif($row['price'] === 2){
                                    echo "500円〜1000円";
                                }elseif($row['price'] === 3){
                                    echo "1000円〜1500円";
                                }elseif($row['price'] === 4){
                                    echo "1500円〜2000円";
                                }elseif($row['price'] === 5){
                                    echo "2000円〜";
                                }?>
                            </li>
                            <li><?php echo $row['comment'];?></li>
                            <?php 
                                if(!empty($row['first_file_name'])){
                                    $first_file_name = $row['first_file_name'];
                                    echo "<li><a href='post_medias/$first_file_name' rel='lightbox'><img src='post_medias/$first_file_name' width='80' height='80'></a></li>";
                                }
                            ?>
                            <?php 
                                if(!empty($row['second_file_name'])){
                                    $second_file_name = $row['second_file_name'];
                                    echo "<li><a href='post_medias/$second_file_name' rel='lightbox'><img src='post_medias/$second_file_name' width='80' height='80'></a></li>";
                                }
                            ?>
                            <?php 
                                if(!empty($row['third_file_name'])){
                                    $third_file_name = $row['third_file_name'];
                                    echo "<li><a href='post_medias/$third_file_name' rel='lightbox'><img src='post_medias/$third_file_name' width='80' height='80'></a></li>";
                                }
                            ?>
                            <?php 
                                if(!empty($row['fourth_file_name'])){
                                    $fourth_file_name = $row['fourth_file_name'];
                                    echo "<li><a href='post_medias/$fourth_file_name' rel='lightbox'><img src='post_medias/$fourth_file_name' width='80' height='80'></a></li>";
                                }
                            ?>
                            <li><?php if($row['user_id'] === $user_id){
                                            echo "<button><a href = 'update_post.php?post_id=$row[post_id]'>編集</a></button>";
                                        }else{
                                            echo "";
                                        }?>
                            </li>
                            <li><?php if($row['user_id'] === $user_id){
                                            echo "<button><a href = 'delete_post.php?post_id=$row[post_id]'>削除</a></button>";
                                        }else{
                                            echo "";
                                        }?>
                            </li>
                            <li>
                                <form class = "good_count" action = "favorite_list.php" method ="POST">
                                    <input type = "hidden" name = "post_id" value = <?php  echo $row['post_id']; ?>>
                                    <input type = "hidden" name = "list_good" value="search_good">
                                    <?php
                                        $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
                                        $sql_like_button = "SELECT * FROM post_likes WHERE user_id = $user_id AND post_id = $row[post_id]";
                                        $stmt_like = $dbh->query($sql_like_button);
                                        $result_like = $stmt_like->fetch(PDO::FETCH_ASSOC);
                                        if(empty($result_like)){
                                            echo "<input type = 'submit' name = 'button' class = 'good_btn' value = '行ってみたい'><span>$row[like_count]</span>";
                                        }else{
                                            echo "<input type = 'submit' name = 'button' class = 'good_btn' value = '行ってみたい解除'><span>$row[like_count]</span>";
                                        }
                                    ?>
                                </form>
                            </li>
                        </ul>
                <?php }?>
            </div>
        </main>
        <footer class="footer">
            <div>
                フッター
            </div>
        </footer>  
    </body>
</html>