<?php
    session_start();

    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }

    
    try{
        mb_internal_encoding("utf8");
        $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root",
                        array(
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,//SQL実行失敗の時、例外をスロー
                            PDO::ATTR_EMULATE_PREPARES => false,
                            )   
                        );
        $sql = "SELECT * FROM users WHERE user_id = $user_id";
        $stmt = $dbh->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    }
                    catch(PDOException $e){//DB接続エラーが発生した時$db_errorを定義
                        $db_error = "エラーが発生したためアカウント登録できません。";
                    }
        $sql2 = "SELECT * FROM user_medias WHERE user_id = $user_id";
        $stmt2 = $dbh->query($sql2);
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                    
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>プロフィール画面</title>
    </head>
    <body>
    <h1 class="heading-lv1 text-center">Profile</h1>
    <figure class="profile-image">
            <img src="user_medias/<?php echo $result2['file_name']; ?>" alt="プロフィール写真" width="300" height="300">
        </figure>
        <h2 class="heading-lv2 text-center"><?php echo $result['name'];?></h2>

        <h3 class="heading-lv3 text-center">好きなジャンル</h3>
        <p class="text text-center"><?php echo $result['favorite_genre'];?></p>

        <h3 class="heading-lv3 text-center">好きなメニュー</h3>
        <p class="text text-center"><?php echo $result['favorite_menu'];?></p>

        <h3 class="heading-lv3 text-center">自己紹介</h3>
        <p class="text text-center"><?php echo $result['about_me'];?></p>

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
                <option value="toppage.php">ログアウト</option>
            </select>
        </form>

                

    </body>                