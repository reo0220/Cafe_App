<?php
    session_start();
    $user_id = $_SESSION['user_id'];

    mb_internal_encoding("utf8");
    $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
    $sql = "SELECT * FROM users WHERE user_id = $user_id ";//パラメータに渡された[user_id]のidの情報を取り出す
    $stmt = $dbh->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);//カラム名で添字付けた配列を返す
    
    
    $counts = $dbh->query("SELECT COUNT(*) as cnt FROM user_medias WHERE user_id = $user_id");
    $count = $counts->fetch();//ログインしているアカウントのuser_idが登録されているかのチェック

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $name_check = $_POST['name'];
        if($name_check === ""){
            $error_name =  "ニックネームは必須項目です。";
        }

        $mail_check = $_POST['mail'];
        if($mail_check === ""){
            $error_mail =  "メールアドレスは必須項目です。";
        }
        
        $pas_check = $_POST['password'];
        if($pas_check === ""){
            $error_pas =  "現在のパスワード又は変更後のパスワードを入力して下さい。";
        }

        if(!empty($name_check) && !empty($mail_check) && !empty($pas_check)){

            $dsn = "mysql:host=localhost; dbname=cafe_app; charset=utf8";
            $username = "root";
            $db_password = "root";
            
            try {
                $dbh = new PDO($dsn, $username, $db_password);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            
                $image = uniqid(mt_rand(), true);//ファイル名をユニーク化
                $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
                $file = "user_medias/$image";
                if(empty($count['cnt'])){
                    $sql = "INSERT INTO user_medias(file_name,user_id) VALUES (:image,$user_id)";
                }else{
                    $sql = "UPDATE user_medias SET file_name = :image WHERE user_id = $user_id ";
                }
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(':image', $image, PDO::PARAM_STR);
                if (!empty($_FILES['image']['name'])) {//ファイルが選択されていれば$imageにファイル名を代入
                    move_uploaded_file($_FILES['image']['tmp_name'], './user_medias/' . $image);//user_mediasディレクトリにファイル保存
                    if (exif_imagetype($file)) {//画像ファイルかのチェック
                        $message = '画像をアップロードしました';
                        $stmt->execute();
                    } else {
                        $message = '画像ファイルではありません';
                    }
                }

                $name = $_POST['name'];
                $mail = $_POST['mail'];
                $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
                $favorite_genre = $_POST['favorite_genre'];
                $favorite_menu = $_POST['favorite_menu'];
                $about_me = $_POST['about_me'];
                
                $sql2 = "UPDATE users SET name = '$name',mail = '$mail',password  = '$password',favorite_genre ='$favorite_genre',favorite_menu = '$favorite_menu',about_me = '$about_me' WHERE user_id = '$user_id'";
                $dbh -> exec($sql2);

                header("Location:http://localhost/cafe_app/Cafe_App/profile.php");
        }
    }
?>    


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>アカウント編集画面</title>
    </head>
    <body>
    <h1 class="heading-lv1 text-center">アカウント編集</h1>
    <form method = "POST" action = "edit_account.php" enctype="multipart/form-data">
        <ul>
            <li>
                <label>プロフィール画像</label>
                <input type="file" name="image" accept='image/*' onchange="previewImage(this);">
                <img id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" style="max-width:200px;">
                <script>
                    function previewImage(obj){
                        var fileReader = new FileReader();
                        fileReader.onload = (function() {
                            document.getElementById('preview').src = fileReader.result;
                        });
                        fileReader.readAsDataURL(obj.files[0]);
                    }
                </script>
            </li>
            <li>
                <label>ニックネーム</label>
                <input type = "text" name = "name" value = <?php echo $result['name'];?>>
                <?php if(!empty($error_name)):?>
                    <p class="text-danger"><?php echo $error_name;?></p>
                <?php endif; ?>
            </li>
            <li>
                <label>メールアドレス</label>
                <input type = "text" name = "mail" value = <?php echo $result['mail'];?>>
                <?php if(!empty($error_mail)):?>
                    <p class="text-danger"><?php echo $error_mail;?></p>
                <?php endif; ?>
            </li>
            <li>
                <label>パスワード</label>
                <input type = "password" name = "password" value = "">
                <?php if(!empty($error_pas)):?>
                    <p class="text-danger"><?php echo $error_pas;?></p>
                <?php endif; ?>
            </li>
            <li>
                <label>好きなジャンル</label>
                <input type = "text" name = "favorite_genre" value = <?php echo $result['favorite_genre'];?>>
            </li>
            <li>
                <label>好きなメニュー</label>
                <input type = "text" name = "favorite_menu" value = <?php echo $result['favorite_menu'];?>>
            </li>
            <li>
                <label>自己紹介文</label>
                <textarea name="about_me" name = "about_me" rows="5" cols="33"><?php echo $result['about_me'];?></textarea>
            </li>
            <li><input type = "submit" class = "submit" value="保存"></li>
        </ul>
    </form>
</body>
</html>