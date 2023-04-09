<?php
    session_start();
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }else{
        $user_id = "";
    }
    
    //お気に入り機能
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
            if(!empty($_POST['list_good'])){
                $_GET['name']="";
                $_GET['place']="";
                $_GET['price']="";
            }
        }
    
    //検索機能    
    $name_search="";
    $place_search="";
    $price_search="";
    if((!empty($_POST['search'])) || ($_GET['name'] != "" || $_GET['place'] != "" || $_GET['price'] != "")){
        
        if(!empty($_POST['search'])){
            $name_search = $_POST['name_search'];
            $place_search = $_POST['place_search'];
            $price_search = $_POST['price_search'];
        }elseif($_GET['name'] != "" || $_GET['place'] != "" || $_GET['price'] != ""){
            $name_search = $_GET['name'];
            $place_search = $_GET['place'];
            $price_search = $_GET['price'];
        }
        $dbh_search = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
        if((!empty($name_search) && $place_search != "0" && $price_search != "0") || (empty($name_search) && $place_search != "0" && $price_search != "0")){
            $sql_search = "SELECT
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
                                posts.delete_flag = '0' && posts.name like '%".$name_search."%' && posts.place = $place_search && posts.price = $price_search
                            ORDER BY 
                                posts.registered_time DESC";
            $stmt_search = $dbh_search->query($sql_search);
            //検索件数カウント
            $sql_counts = "SELECT
                                COUNT(*) as cnt
                            FROM 
                                posts
                            WHERE
                                delete_flag = '0' && name like '%".$name_search."%' && place = $place_search && price = $price_search";
            $counts = $dbh_search->query($sql_counts);
            $count = $counts->fetch();
        }elseif(!empty($name_search) && $place_search === "0" && $price_search === "0"){//店名のみ入力されている場合
            $sql_search = "SELECT
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
                                posts.delete_flag = '0' && posts.name like '%".$name_search."%'
                            ORDER BY 
                                posts.registered_time DESC";
            $stmt_search = $dbh_search->query($sql_search);
            $sql_counts = "SELECT
                                COUNT(*) as cnt
                            FROM 
                                posts
                            WHERE
                                delete_flag = '0' && name like '%".$name_search."%'";
            $counts = $dbh_search->query($sql_counts);
            $count = $counts->fetch();
        }elseif((!empty($name_search) && $place_search != "0" && $price_search === "0") || (empty($name_search) && $place_search != "0" && $price_search === "0")){//店名と場所のみ、選択入力されている場合か、場所のみ選択している場合
            $sql_search = "SELECT
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
                                posts.delete_flag = '0' && posts.name like '%".$name_search."%' && posts.place = $place_search
                            ORDER BY 
                                posts.registered_time DESC";
            $stmt_search = $dbh_search->query($sql_search);
            $sql_counts = "SELECT
                                COUNT(*) as cnt
                            FROM 
                                posts
                            WHERE
                                delete_flag = '0' && name like '%".$name_search."%' && place = $place_search";
            $counts = $dbh_search->query($sql_counts);
            $count = $counts->fetch();
        }elseif((!empty($name_search) && $place_search === "0" && $price_search != "0") || (empty($name_search) && $place_search === "0" && $price_search != "0")){//店名と価格帯のみ選択入力されている場合か、価格帯のみ選択している場合
            $sql_search = "SELECT
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
                                posts.delete_flag = '0' && posts.name like '%".$name_search."%' && posts.price = $price_search
                            ORDER BY 
                                posts.registered_time DESC";
            $stmt_search = $dbh_search->query($sql_search);
            $sql_counts = "SELECT
                                COUNT(*) as cnt
                            FROM 
                                posts
                            WHERE
                                delete_flag = '0' && name like '%".$name_search."%' && price = $price_search";
            $counts = $dbh_search->query($sql_counts);
            $count = $counts->fetch();
        }elseif(empty($name_search) && $place_search === "0" && $price_search === "0"){//全て選択されていない場合
            $search_er = "検索条件を入力または選択してください。";
        }
        
    }
}
    //デフォルト表示のsql
    mb_internal_encoding("utf8");
    $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
    //usersテーブルとpostsテーブルとuser_mediasテーブルとpost_mediasテーブルを結合
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
                posts.registered_time DESC";
        
    $stmt = $dbh->query($sql_post);
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>投稿一覧画面</title>
    </head>
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
                <h1 class="heading-lv1 text-center">投稿一覧</h1>
                <!--検索フォーム-->
                <form method = "POST" action = "post_list.php">
                    <input type="hidden" name="search" value="search">
                    <label>店名</label>
                    <input type = "text" name = "name_search" value=<?php //検索条件を入力して検索を行った時、検索条件をそのまま表示
                                                                        if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search'])){
                                                                            echo $_POST['name_search'];
                                                                        }elseif(!empty($_GET['name'])){
                                                                            echo $_GET['name'];
                                                                        }
                                                                    ?>>
                    <label>場所</label>
                    <select name="place_search">
                        <option value="0"></option>
                        <option value="1" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "1"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="1"){
                                                        echo "selected";
                                                    }
                                            ?>>千代田区</option>
                        <option value="2" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "2"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="2"){
                                                        echo "selected";
                                                    }
                                            ?>>中央区</option>
                        <option value="3" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "3"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="3"){
                                                        echo "selected";
                                                    }
                                            ?>>港区</option>
                        <option value="4" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "4"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="4"){
                                                        echo "selected";
                                                    }
                                            ?>>新宿区</option>
                        <option value="5" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "5"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="5"){
                                                        echo "selected";
                                                    }
                                            ?>>文京区</option>
                        <option value="6" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "6"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="6"){
                                                        echo "selected";
                                                    }
                                            ?>>台東区</option>
                        <option value="7" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "7"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="7"){
                                                        echo "selected";
                                                    }
                                            ?>>墨田区</option>
                        <option value="8" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "8"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="8"){
                                                        echo "selected";
                                                    }
                                            ?>>江東区</option>
                        <option value="9" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "9"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="9"){
                                                        echo "selected";
                                                    }
                                            ?>>品川区</option>
                        <option value="10" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "10"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="10"){
                                                        echo "selected";
                                                    }
                                            ?>>目黒区</option>
                        <option value="11" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "11"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="11"){
                                                        echo "selected";
                                                    }
                                            ?>>大田区</option>
                        <option value="12" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "12"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="12"){
                                                        echo "selected";
                                                    }
                                            ?>>世田谷区</option>
                        <option value="13" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "13"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="13"){
                                                        echo "selected";
                                                    }
                                            ?>>渋谷区</option>
                        <option value="14" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "14"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="14"){
                                                        echo "selected";
                                                    }
                                            ?>>中野区</option>
                        <option value="15" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "15"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="15"){
                                                        echo "selected";
                                                    }
                                            ?>>杉並区</option>
                        <option value="16" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "16"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="16"){
                                                        echo "selected";
                                                    }
                                            ?>>豊島区</option>
                        <option value="17" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "17"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="17"){
                                                        echo "selected";
                                                    }
                                            ?>>北区</option>
                        <option value="18" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "18"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="18"){
                                                        echo "selected";
                                                    }
                                            ?>>荒川区</option>
                        <option value="19" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "19"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="19"){
                                                        echo "selected";
                                                    }
                                            ?>>板橋区</option>
                        <option value="20" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "20"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="20"){
                                                        echo "selected";
                                                    }
                                            ?>>練馬区</option>
                        <option value="21" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "21"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="21"){
                                                        echo "selected";
                                                    }
                                            ?>>足立区</option>
                        <option value="22" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "23"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="22"){
                                                        echo "selected";
                                                    }
                                            ?>>葛飾区</option>
                        <option value="23" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['place_search'] === "24"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['place']) && $_GET['place'] ==="23"){
                                                        echo "selected";
                                                    }
                                            ?>>江戸川区</option>
                    </select>
                    <label>価格帯</label>
                    <select name = "price_search">
                        <option value="0"></option>
                        <option value = "1" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['price_search'] === "1"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['price']) && $_GET['price'] ==="1"){
                                                        echo "selected";
                                                    }
                                            ?>>0円〜500円</option>
                        <option value = "2" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['price_search'] === "2"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['price']) && $_GET['price'] ==="2"){
                                                        echo "selected";
                                                    }
                                            ?>>500円〜1000円</option>
                        <option value = "3" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['price_search'] === "3"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['price']) && $_GET['price'] ==="3"){
                                                        echo "selected";
                                                    }
                                            ?>>1000円〜1500円</option>
                        <option value = "4" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['price_search'] === "4"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['price']) && $_GET['price'] ==="4"){
                                                        echo "selected";
                                                    }
                                            ?>>1500円〜2000円</option>
                        <option value = "5" <?php 
                                                if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $_POST['price_search'] === "5"){
                                                            echo "selected";
                                                    }elseif(!empty($_GET['price']) && $_GET['price'] ==="5"){
                                                        echo "selected";
                                                    }
                                            ?>>2000円〜</option>
                    </select>
                    <input type="submit" value="検索">        
                </form>
                <?php if(!empty($search_er)):?>
                    <p class="text-danger"><?php echo $search_er;?></p>
                <?php endif; ?>
                
                <!-- 投稿一覧画面のデフォルト表示 -->  
                <?php if((empty($_POST['search']) && empty($_POST['search_good'])) || (!empty($search_er))):?>                      
                    <?php foreach($stmt as $row){?>
                        <ul>
                            <li><img class="profile_img" src="user_medias/<?php echo $row['user_medias_file_name']; ?>" alt="プロフィール写真" width="50" height="50"></li>
                            <li><?php echo $row['users_name'];?></li>
                            <li>店名：<?php echo $row['posts_name'];?></li>
                            <li>
                                場所：
                                <?php if($row['place'] === "1"){
                                            echo "千代田区";
                                        }elseif($row['place'] === "2"){
                                            echo "中央区";
                                        }elseif($row['place'] === "3"){
                                            echo "港区";
                                        }elseif($row['place'] === "4"){
                                            echo "新宿区";
                                        }elseif($row['place'] === "5"){
                                            echo "文京区";
                                        }elseif($row['place'] === "6"){
                                            echo "台東区";
                                        }elseif($row['place'] === "7"){
                                            echo "墨田区";
                                        }elseif($row['place'] === "8"){
                                            echo "江東区";
                                        }elseif($row['place'] === "9"){
                                            echo "品川区";
                                        }elseif($row['place'] === "10"){
                                            echo "目黒区";
                                        }elseif($row['place'] === "11"){
                                            echo "大田区";
                                        }elseif($row['place'] === "12"){
                                            echo "世田谷区";
                                        }elseif($row['place'] === "13"){
                                            echo "渋谷区";
                                        }elseif($row['place'] === "14"){
                                            echo "中野区";
                                        }elseif($row['place'] === "15"){
                                            echo "杉並区";
                                        }elseif($row['place'] === "16"){
                                            echo "豊島区";
                                        }elseif($row['place'] === "17"){
                                            echo "北区";
                                        }elseif($row['place'] === "18"){
                                            echo "荒川区";
                                        }elseif($row['place'] === "19"){
                                            echo "板橋区";
                                        }elseif($row['place'] === "20"){
                                            echo "練馬区";
                                        }elseif($row['place'] === "21"){
                                            echo "足立区";
                                        }elseif($row['place'] === "22"){
                                            echo "葛飾区";
                                        }elseif($row['place'] === "23"){
                                            echo "江戸川区";
                                        }?>
                            <li>
                                価格帯：
                                <?php if($row['price'] === "1"){
                                    echo "0円〜500円";
                                }elseif($row['price'] === "2"){
                                    echo "500円〜1000円";
                                }elseif($row['price'] === "3"){
                                    echo "1000円〜1500円";
                                }elseif($row['price'] === "4"){
                                    echo "1500円〜2000円";
                                }elseif($row['price'] === "5"){
                                    echo "2000円〜";
                                }?>
                            </li>
                            <li><?php echo $row['comment'];?></li>
                            <?php 
                                if(!empty($row['first_file_name'])){
                                    $first_file_name = $row['first_file_name'];
                                    echo "<li><img src='post_medias/$first_file_name' alt='投稿写真' width='80' height='80'></li>";
                                }
                            ?>
                            <?php 
                                if(!empty($row['second_file_name'])){
                                    $second_file_name = $row['second_file_name'];
                                    echo "<li><img src='post_medias/$second_file_name' alt='投稿写真' width='80' height='80'></li>";
                                }
                            ?>
                            <?php 
                                if(!empty($row['third_file_name'])){
                                    $third_file_name = $row['third_file_name'];
                                    echo "<li><img src='post_medias/$third_file_name' alt='投稿写真' width='80' height='80'></li>";
                                }
                            ?>
                            <?php 
                                if(!empty($row['fourth_file_name'])){
                                    $fourth_file_name = $row['fourth_file_name'];
                                    echo "<li><img src='post_medias/$fourth_file_name' alt='投稿写真' width='80' height='80'></li>";
                                }
                            ?>
                            <li><?php if($row['user_id'] === (string)$user_id){
                                            echo "<button><a href = 'update_post.php?post_id=$row[post_id]'>編集</a></button>";
                                        }else{
                                            echo "";
                                        }?>
                            </li>
                            <li><?php if($row['user_id'] === (string)$user_id){
                                            echo "<button><a href = 'delete_post.php?post_id=$row[post_id]'>削除</a></button>";
                                        }else{
                                            echo "";
                                        }?>
                            </li>
                            <li>
                                <form class = "good_count" action = "post_list.php" method ="POST">
                                    <input type = "hidden" name = "post_id" value = <?php  echo $row['post_id']; ?>>
                                    <input type = "hidden" name = "list_good" value="list_good">
                                    <?php
                                        if($user_id != ""){
                                            $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
                                            $sql_like_button = "SELECT * FROM post_likes WHERE user_id = $user_id AND post_id = $row[post_id]";
                                            $stmt_like = $dbh->query($sql_like_button);
                                            $result_like = $stmt_like->fetch(PDO::FETCH_ASSOC);
                                            if(empty($result_like)){
                                                echo "<input type = 'submit' name = 'button' class = 'good_btn' value = '行ってみたい'><span>$row[like_count]</span>";
                                            }else{
                                                echo "<input type = 'submit' name = 'button' class = 'good_btn' value = '行ってみたい解除'><span>$row[like_count]</span>";
                                            }
                                        }else{
                                            echo "<p>お気に入り件数:$row[like_count]</p>";
                                        }
                                    ?>
                                </form>
                            </li>
                        </ul>
                    <?php }?>


                <!--検索結果がない場合-->    
                <?php elseif($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $count['cnt'] === "0"):?>
                    <h1>該当の投稿がありません。</h1>
                
                <!--検索結果がある場合-->    <!--GETが原因-->
                <?php elseif(($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search']) && $count['cnt'] > 0) || (!empty($_GET['name']))):?>
                    <?php foreach($stmt_search as $row){?>
                        <ul>
                            <li><img class="profile_img" src="user_medias/<?php echo $row['user_medias_file_name']; ?>" alt="プロフィール写真" width="50" height="50"></li>
                            <li><?php echo $row['users_name'];?></li>
                            <li>店名：<?php echo $row['posts_name'];?></li>
                            <li>
                                場所：
                                <?php if($row['place'] === "1"){
                                            echo "千代田区";
                                        }elseif($row['place'] === "2"){
                                            echo "中央区";
                                        }elseif($row['place'] === "3"){
                                            echo "港区";
                                        }elseif($row['place'] === "4"){
                                            echo "新宿区";
                                        }elseif($row['place'] === "5"){
                                            echo "文京区";
                                        }elseif($row['place'] === "6"){
                                            echo "台東区";
                                        }elseif($row['place'] === "7"){
                                            echo "墨田区";
                                        }elseif($row['place'] === "8"){
                                            echo "江東区";
                                        }elseif($row['place'] === "9"){
                                            echo "品川区";
                                        }elseif($row['place'] === "10"){
                                            echo "目黒区";
                                        }elseif($row['place'] === "11"){
                                            echo "大田区";
                                        }elseif($row['place'] === "12"){
                                            echo "世田谷区";
                                        }elseif($row['place'] === "13"){
                                            echo "渋谷区";
                                        }elseif($row['place'] === "14"){
                                            echo "中野区";
                                        }elseif($row['place'] === "15"){
                                            echo "杉並区";
                                        }elseif($row['place'] === "16"){
                                            echo "豊島区";
                                        }elseif($row['place'] === "17"){
                                            echo "北区";
                                        }elseif($row['place'] === "18"){
                                            echo "荒川区";
                                        }elseif($row['place'] === "19"){
                                            echo "板橋区";
                                        }elseif($row['place'] === "20"){
                                            echo "練馬区";
                                        }elseif($row['place'] === "21"){
                                            echo "足立区";
                                        }elseif($row['place'] === "22"){
                                            echo "葛飾区";
                                        }elseif($row['place'] === "23"){
                                            echo "江戸川区";
                                        }?>
                            <li>
                                価格帯：
                                <?php if($row['price'] === "1"){
                                    echo "0円〜500円";
                                }elseif($row['price'] === "2"){
                                    echo "500円〜1000円";
                                }elseif($row['price'] === "3"){
                                    echo "1000円〜1500円";
                                }elseif($row['price'] === "4"){
                                    echo "1500円〜2000円";
                                }elseif($row['price'] === "5"){
                                    echo "2000円〜";
                                }?>
                            </li>
                            <li><?php echo $row['comment'];?></li>
                            <?php 
                                if(!empty($row['first_file_name'])){
                                    $first_file_name = $row['first_file_name'];
                                    echo "<li><img src='post_medias/$first_file_name' alt='投稿写真' width='80' height='80'></li>";
                                }
                            ?>
                            <?php 
                                if(!empty($row['second_file_name'])){
                                    $second_file_name = $row['second_file_name'];
                                    echo "<li><img src='post_medias/$second_file_name' alt='投稿写真' width='80' height='80'></li>";
                                }
                            ?>
                            <?php 
                                if(!empty($row['third_file_name'])){
                                    $third_file_name = $row['third_file_name'];
                                    echo "<li><img src='post_medias/$third_file_name' alt='投稿写真' width='80' height='80'></li>";
                                }
                            ?>
                            <?php 
                                if(!empty($row['fourth_file_name'])){
                                    $fourth_file_name = $row['fourth_file_name'];
                                    echo "<li><img src='post_medias/$fourth_file_name' alt='投稿写真' width='80' height='80'></li>";
                                }
                            ?>
                            <li><?php if($row['user_id'] === (string)$user_id){
                                            echo "<button><a href = 'update_post.php?post_id=$row[post_id]'>編集</a></button>";
                                        }else{
                                            echo "";
                                        }?>
                            </li>
                            <li><?php if($row['user_id'] === (string)$user_id){
                                            echo "<button><a href = 'delete_post.php?post_id=$row[post_id]'>削除</a></button>";
                                        }else{
                                            echo "";
                                        }?>
                            </li>
                            <li>
                                <form class = "good_count" action = "post_list.php?name=<?php echo $name_search;?>&place=<?php echo $place_search;?>&price=<?php echo $price_search;?>" method ="POST">
                                    <input type = "hidden" name = "post_id" value = <?php  echo $row['post_id']; ?>>
                                    <input type = "hidden" name = "search_good" value="search_good">
                                    <?php
                                        if($user_id != ""){
                                            $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
                                            $sql_like_button = "SELECT * FROM post_likes WHERE user_id = $user_id AND post_id = $row[post_id]";
                                            $stmt_like = $dbh->query($sql_like_button);
                                            $result_like = $stmt_like->fetch(PDO::FETCH_ASSOC);
                                            if(empty($result_like)){
                                                echo "<input type = 'submit' name = 'button' class = 'good_btn' value = '行ってみたい'><span>$row[like_count]</span>";
                                            }else{
                                                echo "<input type = 'submit' name = 'button' class = 'good_btn' value = '行ってみたい解除'><span>$row[like_count]</span>";
                                            }
                                        }else{
                                            echo "<p>お気に入り件数:$row[like_count]</p>";
                                        }
                                    ?>
                                </form>
                            </li>
                        </ul>
                    <?php }?>
                    

            <?php endif; ?>
            </div>
        </main>  
        <footer class="footer">
            <div>
                フッター
            </div>
        </footer>  
    </body>
</html>