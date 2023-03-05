<?php
session_start();
if(!empty($_SESSION['user_id_log'])){
    $user_id = $_SESSION['user_id_log'];
}elseif(!empty($_SESSION['user_id_sign'])){
    $user_id = $_SESSION['user_id_sign'];
}



mb_internal_encoding("utf8");
$dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");

//usersテーブルとpostsテーブルとuser_mediasテーブルとpost_mediasテーブルを結合
$sql = "SELECT
            posts.post_id,
            posts.user_id,
            posts.name AS posts_name,
            posts.place,
            posts.price,
            posts.comment,
            posts.like_count,
            post_medias.file_name AS post_medias_file_name,
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
     
     //$sql.=
$stmt = $dbh->query($sql);
?>



<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>投稿一覧画面</title>
    </head>
    <body>
        <h1 class="heading-lv1 text-center">投稿一覧</h1>

        <?php foreach($stmt as $row){?>
                
            <ul>
                <li><img src="user_medias/<?php echo $row['user_medias_file_name']; ?>" alt="プロフィール写真" width="50" height="50"></li>
                <li><?php echo $row['users_name'];?></li>
                <li><?php echo $row['posts_name'];?></li>
                <li>
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
                <li><img src="post_medias/<?php echo $row['post_medias_file_name']; ?>" alt="投稿写真" width="80" height="80"></li>
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
            </ul>
               
                

            <?php }?>
        
    </body>
</html>