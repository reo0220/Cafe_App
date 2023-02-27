<?php
session_start();
if(!empty($_SESSION['user_id_log'])){
    $user_id = $_SESSION['user_id_log'];
}elseif(!empty($_SESSION['user_id_sign'])){
    $user_id = $_SESSION['user_id_sign'];
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];
    if($name === ""){
        $error_name = "店名が未入力です。";
    }
    
    $file_er = $_FILES['file1']['name'];
    if($file_er === ""){
        $error_file1 = "投稿する写真を選択してください";
    }

    if(!isset($error_name) && !isset($error_file1)){
        mb_internal_encoding("utf8");
        $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");

        $dbh -> exec("insert into posts(user_id,name,place,price,comment,delete_flag)
                values('".$user_id."','".$_POST['name']."','".$_POST['place']."','".$_POST['price']."','".$_POST['comment']."','0');");//postsテーブルにインサート
        $post_id = $dbh->lastInsertId();//post_idを変数に代入        

        if(!empty($_FILES['file1']['name'])){//file1の登録処理
            $image1 = uniqid(mt_rand(), true);//ファイル名をユニーク化
            $image1 .= '.' . substr(strrchr($_FILES['file1']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
            $file1 = "post_medias/$image1";
            $sql1 = "INSERT INTO post_medias(file_name,post_id) VALUES (:file1,$post_id)";
            $stmt1 = $dbh->prepare($sql1);
            $stmt1->bindValue(':file1', $image1, PDO::PARAM_STR);
            move_uploaded_file($_FILES['file1']['tmp_name'], './post_medias/' . $image1);//post_mediasディレクトリにファイル保存
            if (exif_imagetype($file1)) {//画像ファイルかのチェック
                    $message1 = '画像をアップロードしました';
                    $stmt1->execute();
            } else {
                    $message1 = '画像ファイルではありません';
            }
        }

        if(!empty($_FILES['file2']['name'])){//file2が選択されているとき、登録処理を行う
            $image2 = uniqid(mt_rand(), true);//ファイル名をユニーク化
            $image2 .= '.' . substr(strrchr($_FILES['file2']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
            $file2 = "post_medias/$image2";
            $sql2 = "INSERT INTO post_medias(file_name,post_id) VALUES (:file2,$post_id)";
            $stmt2 = $dbh->prepare($sql1);
            $stmt2->bindValue(':file1', $image2, PDO::PARAM_STR);
            move_uploaded_file($_FILES['file2']['tmp_name'], './post_medias/' . $image2);//post_mediasディレクトリにファイル保存
            if (exif_imagetype($file2)) {//画像ファイルかのチェック
                    $message2 = '画像をアップロードしました';
                    $stmt2->execute();
            } else {
                    $message2 = '画像ファイルではありません';
            }
        }else{
            header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");//file2が選択されていない時、トップページに遷移する
        }

        if(!empty($_FILES['file3']['name'])){
            $image3 = uniqid(mt_rand(), true);//ファイル名をユニーク化
            $image3 .= '.' . substr(strrchr($_FILES['file3']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
            $file3 = "post_medias/$image3";
            $sql3 = "INSERT INTO post_medias(file_name,post_id) VALUES (:file3,$post_id)";
            $stmt3 = $dbh->prepare($sql3);
            $stmt3->bindValue(':file3', $image3, PDO::PARAM_STR);
            move_uploaded_file($_FILES['file3']['tmp_name'], './post_medias/' . $image3);//post_mediasディレクトリにファイル保存
            if (exif_imagetype($file3)) {//画像ファイルかのチェック
                    $message3 = '画像をアップロードしました';
                    $stmt3->execute();
            } else {
                    $message3 = '画像ファイルではありません';
            }
        }else{
            header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");
        }

        if(!empty($_FILES['file4']['name'])){
            $image4 = uniqid(mt_rand(), true);//ファイル名をユニーク化
            $image4 .= '.' . substr(strrchr($_FILES['file4']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
            $file4 = "post_medias/$image4";
            $sql4 = "INSERT INTO post_medias(file_name,post_id) VALUES (:file4,$post_id)";
            $stmt4 = $dbh->prepare($sql4);
            $stmt4->bindValue(':file4', $image4, PDO::PARAM_STR);
            move_uploaded_file($_FILES['file4']['tmp_name'], './post_medias/' . $image4);//post_mediasディレクトリにファイル保存
            if (exif_imagetype($file4)) {//画像ファイルかのチェック
                    $message4 = '画像をアップロードしました';
                    $stmt4->execute();
            } else {
                    $message4 = '画像ファイルではありません';
            }
        }else{
            header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");
        }

        if(!empty($_FILES['file1']['name']) && !empty($_FILES['file2']['name']) && !empty($_FILES['file3']['name']) && !empty($_FILES['file4']['name'])){
            header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>投稿作成画面</title>
    </head>
    <body>
        <h1 class="heading-lv1 text-center">投稿作成</h1>

        <form method = "POST" action = "?" enctype='multipart/form-data'>
            <ul>
                <li>
                    <label>店名</label>
                    <input type = "text" name = "name">
                    <?php if(!empty($error_name)):?>
                        <p class="text-danger"><?php echo $error_name;?></p>
                    <?php endif; ?>
                </li>
                <li>
                    <label>場所</label>
                    <select name="place">
                        <option value="1">千代田区</option>
                        <option value="2">中央区</option>
                        <option value="3">港区</option>
                        <option value="4">新宿区</option>
                        <option value="5">文京区</option>
                        <option value="6">台東区</option>
                        <option value="7">墨田区</option>
                        <option value="8">江東区</option>
                        <option value="9">品川区</option>
                        <option value="10">目黒区</option>
                        <option value="11">大田区</option>
                        <option value="12">世田谷区</option>
                        <option value="13">渋谷区</option>
                        <option value="14">中野区</option>
                        <option value="15">杉並区</option>
                        <option value="16">豊島区</option>
                        <option value="17">北区</option>
                        <option value="18">荒川区</option>
                        <option value="19">板橋区</option>
                        <option value="20">練馬区</option>
                        <option value="21">足立区</option>
                        <option value="22">葛飾区</option>
                        <option value="23">江戸川区</option>
                    </select>
                </li>
                <li>
                    <label>価格帯</label>
                    <select name = "price">
                        <option value = "1">0円〜500円</option>
                        <option value = "2">500円〜1000円</option>
                        <option value = "3">1000円〜1500円</option>
                        <option value = "4">1500円〜2000円</option>
                        <option value = "5">2000円〜</option>
                    </select>
                </li>
                <li>
                    <label>コメント</label>
                    <textarea name="comment" rows="5" cols="33"></textarea>
                </li>
                <li>
                    <label>投稿写真</label>
                    <input name="file1" type="file"/><br/>
                    <?php if(!empty($error_file1)):?>
                        <p class="text-danger"><?php echo $error_file1;?></p>
                    <?php endif; ?>
                    <input name="file2" type="file"/><br/>
                    <input name="file3" type="file"/><br/>
                    <input name="file4" type="file"/><br/>
                </li>
                <input type="submit" name="_method"  value="投稿" formaction="create_post.php">
                <input type="submit" name="_method" value="キャンセル" formaction="post_list.php">
            </ul>
        </form>
    </body>
</html>
