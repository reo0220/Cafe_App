<?php
    session_start();
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }else{
        $user_id = "";
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
        $dbh=new PDO('mysql:dbname=heroku_f42c30f1b2af6d1;host=us-cdbr-east-06.cleardb.net;charset=utf8','bc9c8df67ff0e5','10b87118');

        $image = file_get_contents($_FILES['image']['tmp_name']);
        $binary_image = base64_encode($image);

        $sql_post = "UPDATE post_medias SET first_file_name = '$binary_image' WHERE media_id = 394";
        $stmt_post = $dbh->prepare($sql_post);
        $stmt_post->execute();

       
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
    <form action="test.php" method="POST" enctype="multipart/form-data" class="post_form">
        <div class="form_parts">
            <input type="file" name="image">
            <br>
            <input type="submit" value="テスト">
        </div>
        <?php
            $dbh1=new PDO('mysql:dbname=heroku_f42c30f1b2af6d1;host=us-cdbr-east-06.cleardb.net;charset=utf8','bc9c8df67ff0e5','10b87118');
             $sql = "SELECT * FROM post_medias WHERE media_id = 394";
             $stmt_like = $dbh1->query($sql);
             $result_like = $stmt_like->fetch(PDO::FETCH_ASSOC);
        ?>

        <img src="data:image/jpeg;base64,<?php echo $result_like['first_file_name'];?>" >
        <img src="data:image/png;base64,<?php echo $result_like['first_file_name'];?>" >
        <img src="data:image/jpg;base64,<?php echo $result_like['first_file_name'];?>" >
    </form>

    </body>
</html>