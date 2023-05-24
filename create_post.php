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

    $comment = "";
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $name = $_POST['name'];
        if($name === ""){
            $er_name = "店名は必須項目です。";
        }
        
        if(empty($_FILES['file1']['name']) && empty($_FILES['file2']['name']) && empty($_FILES['file3']['name']) && empty($_FILES['file4']['name'])){
            $er_file = "投稿する写真を一枚以上選択してください";
        }

        if(empty($er_name) && empty($er_file)){
           
            $dbh = new PDO('mysql:dbname=heroku_f42c30f1b2af6d1;host=us-cdbr-east-06.cleardb.net;charset=utf8','bc9c8df67ff0e5','10b87118');

            $dbh -> exec("insert into posts(user_id,name,place,price,comment,delete_flag)
                    values('".$user_id."','".$_POST['name']."','".$_POST['place']."','".$_POST['price']."','".$_POST['comment']."','0');");//postsテーブルにインサート
            $post_id = $dbh->lastInsertId();//post_idを変数に代入        

            if(!empty($_FILES['file1']['name'])){//file1の登録処理
                $image1 = uniqid(mt_rand(), true);//ファイル名をユニーク化
                $image1 .= '.' . substr(strrchr($_FILES['file1']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
                $file1 = "post_medias/$image1";
                $sql1 = "INSERT INTO post_medias(first_file_name,post_id) VALUES (:file1,$post_id)";
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
                $image2 = uniqid(mt_rand(), true);
                $image2 .= '.' . substr(strrchr($_FILES['file2']['name'], '.'), 1);
                $file2 = "post_medias/$image2";
                if(empty($_FILES['file1']['name'])){
                    $sql2 = "INSERT INTO post_medias(second_file_name,post_id) VALUES (:file2,$post_id)";
                }else{
                    $sql2 = "UPDATE post_medias SET second_file_name = :file2 WHERE post_id = $post_id";
                }
                $stmt2 = $dbh->prepare($sql2);
                $stmt2->bindValue(':file2', $image2, PDO::PARAM_STR);
                move_uploaded_file($_FILES['file2']['tmp_name'], './post_medias/' . $image2);
                if (exif_imagetype($file2)) {
                        $message2 = '画像をアップロードしました';
                        $stmt2->execute();
                } else {
                        $message2 = '画像ファイルではありません';
                }
            }

            if(!empty($_FILES['file3']['name'])){
                $image3 = uniqid(mt_rand(), true);
                $image3 .= '.' . substr(strrchr($_FILES['file3']['name'], '.'), 1);
                $file3 = "post_medias/$image3";
                if(empty($_FILES['file1']['name']) && empty($_FILES['file2']['name'])){
                    $sql3 = "INSERT INTO post_medias(third_file_name,post_id) VALUES (:file3,$post_id)";
                }else{
                    $sql3 = "UPDATE post_medias SET third_file_name = :file3 WHERE post_id = $post_id";
                }
                $stmt3 = $dbh->prepare($sql3);
                $stmt3->bindValue(':file3', $image3, PDO::PARAM_STR);
                move_uploaded_file($_FILES['file3']['tmp_name'], './post_medias/' . $image3);
                if (exif_imagetype($file3)) {
                        $message3 = '画像をアップロードしました';
                        $stmt3->execute();
                } else {
                        $message3 = '画像ファイルではありません';
                }
            }

            if(!empty($_FILES['file4']['name'])){
                $image4 = uniqid(mt_rand(), true);
                $image4 .= '.' . substr(strrchr($_FILES['file4']['name'], '.'), 1);
                $file4 = "post_medias/$image4";
                if(empty($_FILES['file1']['name']) && empty($_FILES['file2']['name']) && empty($_FILES['file3']['name'])){
                    $sql4 = "INSERT INTO post_medias(fourth_file_name,post_id) VALUES (:file4,$post_id)";
                }else{
                    $sql4 = "UPDATE post_medias SET fourth_file_name = :file4 WHERE post_id = $post_id";
                }
                $stmt4 = $dbh->prepare($sql4);
                $stmt4->bindValue(':file4', $image4, PDO::PARAM_STR);
                move_uploaded_file($_FILES['file4']['tmp_name'], './post_medias/' . $image4);
                if (exif_imagetype($file4)) {
                        $message4 = '画像をアップロードしました';
                        $stmt4->execute();
                } else {
                        $message4 = '画像ファイルではありません';
                }
            }

                header("Location:http://localhost/cafe_app/Cafe_App/post_list.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>投稿作成画面</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
        <script>
            const param = '<?=$param_json?>';
        </script>
        <script src="create_post.js"></script>
        <script src="login_er.js"></script>
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
                <div class="box_con07">
                    <h1 class="heading-lv10 text-center">投稿作成</h1>
                    <form method="post" action = "?" enctype='multipart/form-data'>
                        <ul class="formTable">
                            <li>
                                <p class="title"><em>店名</em></p>
                                <div class="box_det">
                                    <input size="20" type="text" class="wide" name="name"  pattern=".*\S+.*" title="スペースを削除してください。" value = <?php 
                                                                                                                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])){
                                                                                                                                                                    echo $_POST['name'];
                                                                                                                                                            }
                                                                                                                                                        ?>>
                                    <?php if(!empty($er_name)):?>
                                        <p class="text-danger"><?php echo $er_name;?></p>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <li>
                                <p class="title"><em>場所</em></p>
                                <div class="box_det"><select name="place">
                                    <option value="1" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "1"){
                                                                echo "selected";
                                                            }
                                                        ?>>千代田区</option>
                                    <option value="2" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "2"){
                                                                echo "selected";
                                                            }
                                                        ?>>中央区</option>
                                    <option value="3" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "3"){
                                                                echo "selected";
                                                            }
                                                        ?>>港区</option>
                                    <option value="4" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "4"){
                                                                echo "selected";
                                                            }
                                                        ?>>新宿区</option>
                                    <option value="5" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "5"){
                                                                echo "selected";
                                                            }
                                                        ?>>文京区</option>
                                    <option value="6" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "6"){
                                                                echo "selected";
                                                            }
                                                        ?>>台東区</option>
                                    <option value="7" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "7"){
                                                                echo "selected";
                                                            }
                                                        ?>>墨田区</option>
                                    <option value="8" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "8"){
                                                                echo "selected";
                                                            }
                                                        ?>>江東区</option>
                                    <option value="9" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "9"){
                                                                echo "selected";
                                                            }
                                                        ?>>品川区</option>
                                    <option value="10" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "10"){
                                                                echo "selected";
                                                            }
                                                        ?>>目黒区</option>
                                    <option value="11" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "11"){
                                                                echo "selected";
                                                            }
                                                        ?>>大田区</option>
                                    <option value="12" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "12"){
                                                                echo "selected";
                                                            }
                                                        ?>>世田谷区</option>
                                    <option value="13" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "13"){
                                                                echo "selected";
                                                            }
                                                        ?>>渋谷区</option>
                                    <option value="14" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "14"){
                                                                echo "selected";
                                                            }
                                                        ?>>中野区</option>
                                    <option value="15" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "15"){
                                                                echo "selected";
                                                            }
                                                        ?>>杉並区</option>
                                    <option value="16" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "16"){
                                                                echo "selected";
                                                            }
                                                        ?>>豊島区</option>
                                    <option value="17" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "17"){
                                                                echo "selected";
                                                            }
                                                        ?>>北区</option>
                                    <option value="18" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "18"){
                                                                echo "selected";
                                                            }
                                                        ?>>荒川区</option>
                                    <option value="19" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "19"){
                                                                echo "selected";
                                                            }
                                                        ?>>板橋区</option>
                                    <option value="20" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "20"){
                                                                echo "selected";
                                                            }
                                                        ?>>練馬区</option>
                                    <option value="21" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "21"){
                                                                echo "selected";
                                                            }
                                                        ?>>足立区</option>
                                    <option value="22" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "22"){
                                                                echo "selected";
                                                            }
                                                        ?>>葛飾区</option>
                                    <option value="23" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['place'] === "23"){
                                                                echo "selected";
                                                            }
                                                        ?>>江戸川区</option>
                                </select></td>
                            </li>
                            <li>
                                <p class="title"><em>価格帯</em></p>
                                <div class="box_det"><select name="price">
                                    <option value = "1" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['price'] === "1"){
                                                                echo "selected";
                                                            }
                                                        ?>>0円〜500円</option>
                                    <option value = "2" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['price'] === "2"){
                                                                echo "selected";
                                                            }
                                                        ?>>500円〜1000円</option>
                                    <option value = "3" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['price'] === "3"){
                                                                echo "selected";
                                                            }
                                                        ?>>1000円〜1500円</option>
                                    <option value = "4" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['price'] === "4"){
                                                                echo "selected";
                                                            }
                                                        ?>>1500円〜2000円</option>
                                    <option value = "5" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['price'] === "5"){
                                                                echo "selected";
                                                            }
                                                        ?>>2000円〜</option>
                                </select></td>
                            </li>     
                            <li>
                                <p class="title"><em>コメント</em></p>
                                <?php 
                                    if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment'])){
                                        $comment = $_POST['comment'];
                                    }
                                ?>
                                <div class="box_det"><textarea name="comment" cols="10" rows="5"><?php echo $comment;?></textarea></div>
                            </li>
                            <li>
                                <p class="title"><em>投稿写真</em></p></br>
                                <div class="box_det1">
                                    <?php if(!empty($er_file)):?>
                                        <p class="text-danger"><?php echo $er_file;?></p>
                                    <?php endif; ?>
                                    <input name="file1" type="file" id="file1" accept='image/*' onchange="previewImage(this);"></br>
                                    <img id='preview' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='160' height='160'>
                                    <input type="button" id="deselect" value="選択解除" onclick="deselect1()"></br>
                                </div>
                            </li>
                            <li>
                                <p class="title2"><em></em></p></br>
                                <div class="box_det2">
                                    <input name="file2" type="file" id="file2" accept='image/*' onchange="previewImage2(this);"></br>
                                    <img id='preview2' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='160' height='160'>
                                    <input type="button" id="deselect2" value="選択解除" onclick="deselect2_1()"></br>
                                </div>
                            </li>
                            <li>
                                <p class="title2"><em></em></p></br>
                                <div class="box_det2">
                                    <input name="file3" type="file" id="file3" accept='image/*' onchange="previewImage3(this);"></br>
                                    <img id='preview3' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='160' height='160'>
                                    <input type="button" id="deselect3" value="選択解除" onclick="deselect3_1()"></br>
                                </div>
                            </li>
                            <li>
                                <p class="title2"><em></em></p></br>
                                <div class="box_det2">
                                    <input name="file4" type="file" id="file4" accept='image/*' onchange="previewImage4(this);"></br>
                                    <img id='preview4' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='160' height='160'>
                                    <input type="button" id="deselect4" value="選択解除" onclick="deselect4_1()"></br>
                                </div>
                            </li>
                        </ul>
                        <div class="button-panel">
                            <input type="submit" name="_method" class="button1" formaction="create_post.php" value="投稿"></input>
                            <input type="submit" name="_method" class="button1" value="キャンセル" formaction="profile.php"></input>
                        </div>
                    </form>
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
