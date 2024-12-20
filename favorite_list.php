<?php
    session_start();
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
        $param_json = 1;
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
        $param_json = 1;
    }else{
        $param_json = 2;
    }

    if(!empty($user_id)){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!empty($_POST['post_id'])){
                if($_POST['button'] === "行ってみたい" || $_POST['button'] === "行ってみたい解除"){
                    $post_id_good = $_POST['post_id'];
                    $dbh = new PDO('mysql:host=lmag6s0zwmcswp5w.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;dbname=aebifro9tkb2dfum','yao2hpzrt6mfkfn1','ck3eakt3fp2npl6a');
                    $sql_like_button = "SELECT * FROM post_likes WHERE user_id = $user_id AND post_id = $post_id_good";
                    $stmt_like = $dbh->query($sql_like_button);
                    $result_like = $stmt_like->fetch(PDO::FETCH_ASSOC);
                    
                    if($_POST['button'] === "行ってみたい" && empty($result_like)){
                        $dbh = new PDO('mysql:host=lmag6s0zwmcswp5w.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;dbname=aebifro9tkb2dfum','yao2hpzrt6mfkfn1','ck3eakt3fp2npl6a');
                        $sql_like_count = "UPDATE posts SET like_count = like_count + 1 WHERE post_id = $post_id_good";
                        $sql_post_like = "INSERT INTO post_likes(user_id,post_id) VALUES($user_id,$post_id_good)";
                        $stmt = $dbh->query($sql_like_count);
                        $stmt = $dbh->query($sql_post_like);
                    }elseif($_POST['button'] === "行ってみたい解除" && !empty($result_like)){
                        $dbh = new PDO('mysql:host=lmag6s0zwmcswp5w.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;dbname=aebifro9tkb2dfum','yao2hpzrt6mfkfn1','ck3eakt3fp2npl6a');
                        $sql_like_count = "UPDATE posts SET like_count = like_count - 1 WHERE post_id = $post_id_good";
                        $sql_post_like = "DELETE from post_likes WHERE user_id = $user_id AND post_id = $post_id_good";
                        $stmt = $dbh->query($sql_like_count);
                        $stmt = $dbh->query($sql_post_like);
                    }
                }
            }
        }
        

        try{
            $dbh = new PDO('mysql:host=lmag6s0zwmcswp5w.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;dbname=aebifro9tkb2dfum','yao2hpzrt6mfkfn1','ck3eakt3fp2npl6a',
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
                        DISTINCT 
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
                            user_medias.file_name AS user_medias_file_name,
                            post_likes.registered_time
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
                            post_likes.registered_time DESC";
            
            $stmt_post = $dbh->query($sql_post);

            $sql_counts = "SELECT
                                COUNT(*) as cnt
                            FROM 
                                post_likes
                            WHERE
                             user_id = $user_id";
            $counts = $dbh->query($sql_counts);
            $count = $counts->fetch();
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>行ってみたいリスト</title>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
        <link href="//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
        <script>
            const param = '<?=$param_json?>';
        </script>
        <script src="login_er.js"></script>
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
                        <script src="header.js"></script>
                    </button>
                </div>
            </header>
        </div>
        <main class = "main1">
            <div class="main2">
               <h1 class="heading-lv10 text-center">Profile</h1>
                <figure class="profile-image">
                    <a href="data:image/jpeg;base64,<?php echo $result_user_media['file_name']; ?>" rel='lightbox'><img src="data:image/jpeg;base64,<?php echo $result_user_media['file_name']; ?>" width="300" height="300"></a>
                </figure>
                <h2 class="heading-lv2 text-center"><?php echo $result_user['name'];?></h2>

                <h3 class="heading-lv3 text-center">【好きなジャンル】</h3>
                <p class="text text-center"><?php echo $result_user['favorite_genre'];?></p>

                <h3 class="heading-lv3 text-center">【好きなメニュー】</h3>
                <p class="text text-center"><?php echo $result_user['favorite_menu'];?></p>
                <h3 class="heading-lv3 text-center">【自己紹介】</h3>
                <p class="text text-center"><?php echo $result_user['about_me'];?></p>
                
                <script src="account_pull.js"></script>
                <div class="cp_ipselect cp_sl01">
                    <form name="pullForm">
                        <select name="pullMenu" onChange="screenChange()">
                            <option>アカウント操作</option>
                            <option value= "edit_account.php">アカウント編集</option>
                            <option value="delete_account.php">アカウント削除</option>
                            <option value="logout.php">ログアウト</option>
                        </select>
                    </form>
                </div>

                <div class="top_post1">
                    <div class="pro_link">
                        <h1 class="heading-lv1 text-center3">行ってみたいリスト</h1>
                        <a class="pro_link_a" href="profile.php"><h2 class="pro_link_h2">自分の投稿一覧へ</h2></a>
                    </div>
                    <?php 
                        if($count['cnt'] === 0){
                            echo "<div class='zero1'>まだ行ってみたいリストに追加された投稿がありません。</div>";
                        }
                    ?>
                    <?php foreach($stmt_post as $row){?>
                        <div class="post">
                            <div class="post_img">
                                <div class="post_img1">
                                    <?php if(!empty($row['first_file_name'])):?>
                                        <a href="data:image/jpeg;base64,<?php echo $row['first_file_name'];?>" rel="lightbox"><img src="data:image/jpeg;base64,<?php echo $row['first_file_name'];?>" width="250" height="250"></a>
                                    <?php elseif(!empty($row['second_file_name'])):?>
                                        <a href="data:image/jpeg;base64,<?php echo $row['second_file_name'];?>" rel="lightbox"><img src="data:image/jpeg;base64,<?php echo $row['second_file_name'];?>" width="250" height="250"></a>
                                    <?php elseif(!empty($row['third_file_name'])):?>
                                        <a href="data:image/jpeg;base64,<?php echo $row['third_file_name'];?>" rel="lightbox"><img src="data:image/jpeg;base64,<?php echo $row['third_file_name'];?>" width="250" height="250"></a>
                                    <?php elseif(!empty($row['fourth_file_name'])):?>
                                        <a href="data:image/jpeg;base64,<?php echo $row['fourth_file_name'];?>" rel="lightbox"><img src="data:image/jpeg;base64,<?php echo $row['fourth_file_name'];?>" width="250" height="250"></a>
                                    <?php endif;?>
                                </div>
                                <div class="post_img2_pro">
                                    <?php if(!empty($row['first_file_name']) && !empty($row['second_file_name'])):?>
                                        <a href="data:image/jpeg;base64,<?php echo $row['second_file_name'];?>" rel="lightbox"><img src="data:image/jpeg;base64,<?php echo $row['second_file_name'];?>" class='post_img_img' width="80" height="80"></a>
                                    <?php endif;?> 
                                    <?php if((!empty($row['first_file_name']) && !empty($row['third_file_name'])) || (empty($row['first_file_name']) && !empty($row['second_file_name']) && !empty($row['third_file_name']))):?>
                                        <a href="data:image/jpeg;base64,<?php echo $row['third_file_name'];?>" rel="lightbox"><img src="data:image/jpeg;base64,<?php echo $row['third_file_name'];?>" class='post_img_img' width="80" height="80"></a>
                                    <?php endif;?>
                                    <?php if((!empty($row['first_file_name']) && !empty($row['fourth_file_name'])) || (empty($row['first_file_name']) && !empty($row['second_file_name']) && !empty($row['third_file_name']) && !empty($row['fourth_file_name'])) || (empty($row['first_file_name']) && empty($row['second_file_name']) && !empty($row['third_file_name']) && !empty($row['fourth_file_name'])) || (empty($row['first_file_name']) && !empty($row['second_file_name']) && empty($row['third_file_name']) && !empty($row['fourth_file_name']))):?>
                                        <a href="data:image/jpeg;base64,<?php echo $row['fourth_file_name'];?>" rel="lightbox"><img src="data:image/jpeg;base64,<?php echo $row['fourth_file_name'];?>" class='post_img_img' width="80" height="80"></a>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="post_content">
                                <table class="top_table">
                                    <tr>
                                        <th>
                                            <a href="profile_someone.php?user_id=<?php echo $row['user_id'];?>">
                                                <img src="data:image/jpeg;base64,<?php echo $row['user_medias_file_name']; ?>" class="profile_img" alt="プロフィール写真" width="100" height="100">
                                            </a>
                                        </th>
                                        <th>
                                            <?php echo $row['users_name'];?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b>店名：<?php echo $row['posts_name'];?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b>場所：<?php if($row['place'] === 1){
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
                                                            }?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b>店名：<?php if($row['price'] === 1){
                                                        echo "0円〜500円";
                                                        }elseif($row['price'] === 2){
                                                            echo "500円〜1000円";
                                                        }elseif($row['price'] === 3){
                                                            echo "1000円〜1500円";
                                                        }elseif($row['price'] === 4){
                                                            echo "1500円〜2000円";
                                                        }elseif($row['price'] === 5){
                                                            echo "2000円〜";
                                                        }
                                                    ?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="width:300px;">
                                            <?php echo $row['comment'];?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <form class = "good_count" action = "favorite_list.php" method ="POST">
                                                <input type = "hidden" name = "post_id" value = <?php  echo $row['post_id']; ?>>
                                                <?php
                                                    if($user_id != ""){
                                                        if($user_id != $row['user_id']){
                                                            $dbh = new PDO('mysql:host=lmag6s0zwmcswp5w.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;dbname=aebifro9tkb2dfum','yao2hpzrt6mfkfn1','ck3eakt3fp2npl6a');
                                                            $sql_like_button = "SELECT * FROM post_likes WHERE user_id = $user_id AND post_id = $row[post_id]";
                                                            $stmt_like = $dbh->query($sql_like_button);
                                                            $result_like = $stmt_like->fetch(PDO::FETCH_ASSOC);
                                                            if(empty($result_like)){
                                                                echo "<input type = 'submit' class = 'good_btn' value = '行ってみたい（$row[like_count]）'>";
                                                                echo "<input type='hidden' name='button' value='行ってみたい'>";
                                                            }else{
                                                                echo "<input type = 'submit' name = 'button' class = 'good_btn' value = '行ってみたい解除（$row[like_count]）'>";
                                                                echo "<input type='hidden' name='button' value='行ってみたい解除'>";
                                                            }
                                                        }else{
                                                            echo "<p>お気に入り件数:$row[like_count]</p>";
                                                        }
                                                    }else{
                                                        echo "<p>お気に入り件数:$row[like_count]</p>";
                                                    }
                                                ?>
                                            </form>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </main>  
        <footer class="footer">
            <div>
                <p>&copy; ReoKodama. 2024.</p>
            </div>
        </footer>  
    </body>
</html>                