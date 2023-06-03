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

    if(empty($_GET['post_id']) && !empty($user_id)){
        $er_update_post = 1;
    }

    if(!empty($user_id) && !empty($_GET['post_id'])){
        $er_update_post = 2;
        $post_id = $_GET['post_id'];
        $dbh = new PDO('mysql:dbname=heroku_f42c30f1b2af6d1;host=us-cdbr-east-06.cleardb.net;charset=utf8','bc9c8df67ff0e5','10b87118');
        $sql_post = "SELECT 
                        posts.post_id,
                        posts.user_id,
                        posts.name AS posts_name,
                        posts.place, posts.price,
                        posts.comment,
                        post_medias.first_file_name,
                        post_medias.second_file_name,
                        post_medias.third_file_name,
                        post_medias.fourth_file_name
                    FROM 
                        posts 
                    INNER JOIN 
                        post_medias ON posts.post_id = post_medias.post_id 
                    WHERE 
                        posts.post_id = $post_id";
        $stmt_post = $dbh->query($sql_post);
        $result_post = $stmt_post->fetch(PDO::FETCH_ASSOC);

        if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['post_id'])){
            
            $name = $_POST['name'];
            if($name === ""){
                $error_name = "店名が未入力です。";
            }
            
            $file1_input = $_POST['deselect1'];
            $file2_input = $_POST['deselect2'];
            $file3_input = $_POST['deselect3'];
            $file4_input = $_POST['deselect4'];
            
            //画像が空の状態
            if((!empty($result_post['first_file_name']) && $file1_input === "0") || (empty($result_post['first_file_name']))){
                $er_file1 = "NULL1";
            }
            if((!empty($result_post['second_file_name']) && $file2_input === "0") || (empty($result_post['second_file_name']))){
                $er_file2 = "NULL2";
            }
            if((!empty($result_post['third_file_name']) && $file3_input === "0") || (empty($result_post['third_file_name']))){
                $er_file3 = "NULL3";
            }
            if((!empty($result_post['fourth_file_name']) && $file4_input === "0") || (empty($result_post['fourth_file_name']))){
                $er_file4 = "NULL4";
            }
            if(!empty($er_file1) && !empty($er_file2) && !empty($er_file3) && !empty($er_file4) ){
                $error_file1 = "投稿する写真を一枚以上選択してください";
            }

            if((!empty($_POST['name'])) && (empty($er_file1) || empty($er_file2) || empty($er_file3) || empty($er_file4))){
                $post_id_edit = $_POST['post_id'];
                $dbh = new PDO('mysql:dbname=heroku_f42c30f1b2af6d1;host=us-cdbr-east-06.cleardb.net;charset=utf8','bc9c8df67ff0e5','10b87118');
                $sql_post_edit = "UPDATE posts SET name = '$_POST[name]',place='$_POST[place]',price='$_POST[price]',comment='$_POST[comment]' WHERE post_id = $post_id_edit";
                $stmt_post_edit = $dbh->query($sql_post_edit);

                if(!empty($_FILES['file1']['name'])){//file1の登録処理
                    $image1 = file_get_contents($_FILES['file1']['tmp_name']);
                    $binary_image1 = base64_encode($image1);
                    $sql_media1 = "UPDATE post_medias SET first_file_name = '$binary_image1' WHERE post_id = $post_id_edit";
                    $stmt_media1 = $dbh->prepare($sql_media1);
                    $stmt_media1->execute();
                }elseif($file1_input === "0"){
                    $sql1_file_delete = "UPDATE post_medias SET first_file_name = '' WHERE post_id = $post_id_edit";
                    $dbh->query($sql1_file_delete);
                }
                
                if(!empty($_FILES['file2']['name'])){//file2が選択されているとき、登録処理を行う
                    $image2 = file_get_contents($_FILES['file2']['tmp_name']);
                    $binary_image2 = base64_encode($image2);
                    $sql_media2 = "UPDATE post_medias SET second_file_name = '$binary_image2' WHERE post_id = $post_id_edit";
                    $stmt_media2 = $dbh->prepare($sql_media2);
                    $stmt_media2->execute();
                }elseif($file2_input === "0"){
                    $sql2_file_delete = "UPDATE post_medias SET second_file_name = '' WHERE post_id = $post_id_edit";
                    $dbh->query($sql2_file_delete);
                }
                
                if(!empty($_FILES['file3']['name'])){
                    $image3 = file_get_contents($_FILES['file3']['tmp_name']);
                    $binary_image3 = base64_encode($image3);
                    $sql_media3 = "UPDATE post_medias SET third_file_name = '$binary_image3' WHERE post_id = $post_id_edit";
                    $stmt_media3 = $dbh->prepare($sql_media3);
                    $stmt_media3->execute();
                }elseif($file3_input === "0"){
                    $sql3_file_delete = "UPDATE post_medias SET third_file_name = '' WHERE post_id = $post_id_edit";
                    $dbh->query($sql3_file_delete);
                }
                
                if(!empty($_FILES['file4']['name'])){
                    $image4 = file_get_contents($_FILES['file4']['tmp_name']);
                    $binary_image4 = base64_encode($image4);
                    $sql_media4 = "UPDATE post_medias SET fourth_file_name = '$binary_image4' WHERE post_id = $post_id_edit";
                    $stmt_media4 = $dbh->prepare($sql_media4);
                    $stmt_media4->execute();
                }elseif($file4_input === "0"){
                    $sql4_file_delete = "UPDATE post_medias SET fourth_file_name = '' WHERE post_id = $post_id_edit";
                    $dbh->query($sql4_file_delete);
                }
                header("Location:https://cafe23.herokuapp.com/profile.php");
            }
        }
    }
?>

<!--エラー表示-->
<?php if(!empty($_SESSION['user_id_log']) || !empty($_SESSION['user_id_sign'])):?>
    <script>
        const er_update = '<?=$er_update_post?>';
    </script>
    <script src="update_post_er.js"></script>
<?php elseif(empty($_SESSION['user_id_log']) || empty($_SESSION['user_id_sign'])):?>
    <script>
        const param = '<?=$param_json?>';
    </script>
    <script src="login_er.js"></script>
<?php endif;?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>投稿編集</title>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
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
                <div class="box_con07">
                    <h1 class="heading-lv10 text-center">投稿編集</h1>
                    <form method="post" action = "create_post.php" enctype='multipart/form-data'>
                        <ul class="formTable">
                            <li>
                                <p class="title"><em>店名</em></p>
                                <div class="box_det">
                                    <input size="20" type="text" class="wide" name="name" value = <?php 
                                                                                                        if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])){
                                                                                                                echo $_POST['name'];
                                                                                                        }else{
                                                                                                                echo $result_post['posts_name'];
                                                                                                        }
                                                                                                    ?>>
                                    <?php if(!empty($error_name)):?>
                                        <p class="text-danger"><?php echo $error_name;?></p>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <li>
                                <p class="title"><em>場所</em></p>
                                <div class="box_det"><select name="place">
                                    <option value="1" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "1"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "1"){
                                                                echo "selected";
                                                            }
                                                        ?>>千代田区</option>
                                    <option value="2" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "2"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "2"){
                                                                echo "selected";
                                                            }
                                                        ?>>中央区</option>
                                    <option value="3" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "3"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "3"){
                                                                echo "selected";
                                                            }
                                                        ?>>港区</option>
                                    <option value="4" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "4"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "4"){
                                                                echo "selected";
                                                            }
                                                        ?>>新宿区</option>
                                    <option value="5" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "5"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "5"){
                                                                echo "selected";
                                                            }
                                                        ?>>文京区</option>
                                    <option value="6" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "6"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "6"){
                                                                echo "selected";
                                                            }
                                                        ?>>台東区</option>
                                    <option value="7" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "7"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "7"){
                                                                echo "selected";
                                                            }
                                                        ?>>墨田区</option>
                                    <option value="8" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "8"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "8"){
                                                                echo "selected";
                                                            }
                                                        ?>>江東区</option>
                                    <option value="9" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "9"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "9"){
                                                                echo "selected";
                                                            }
                                                        ?>>品川区</option>
                                    <option value="10" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "10"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "10"){
                                                                echo "selected";
                                                            }
                                                        ?>>目黒区</option>
                                    <option value="11" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "11"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "11"){
                                                                echo "selected";
                                                            }
                                                        ?>>大田区</option>
                                    <option value="12" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "12"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "12"){
                                                                echo "selected";
                                                            }
                                                        ?>>世田谷区</option>
                                    <option value="13" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "13"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "13"){
                                                                echo "selected";
                                                            }
                                                        ?>>渋谷区</option>
                                    <option value="14" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "14"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "14"){
                                                                echo "selected";
                                                            }
                                                        ?>>中野区</option>
                                    <option value="15" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "15"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "15"){
                                                                echo "selected";
                                                            }
                                                        ?>>杉並区</option>
                                    <option value="16" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "16"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "16"){
                                                                echo "selected";
                                                            }
                                                        ?>>豊島区</option>
                                    <option value="17" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "17"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "17"){
                                                                echo "selected";
                                                            }
                                                        ?>>北区</option>
                                    <option value="18" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "18"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "18"){
                                                                echo "selected";
                                                            }
                                                        ?>>荒川区</option>
                                    <option value="19" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "19"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "19"){
                                                                echo "selected";
                                                            }
                                                        ?>>板橋区</option>
                                    <option value="20" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "20"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "20"){
                                                                echo "selected";
                                                            }
                                                        ?>>練馬区</option>
                                    <option value="21" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "21"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "21"){
                                                                echo "selected";
                                                            }
                                                        ?>>足立区</option>
                                    <option value="22" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "22"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "22"){
                                                                echo "selected";
                                                            }
                                                        ?>>葛飾区</option>
                                    <option value="23" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place']) && $_POST['place'] === "23"){
                                                                echo "selected";
                                                            }elseif($result_post['place'] === "23"){
                                                                echo "selected";
                                                            }
                                                        ?>>江戸川区</option>
                                </select></td>
                            </li>
                            <li>
                                <p class="title"><em>価格帯</em></p>
                                <div class="box_det"><select name="price">
                                    <option value = "1" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['price']) && $_POST['price'] === "1"){
                                                                echo "selected";
                                                            }elseif($result_post['price'] === "1"){
                                                                echo "selected";
                                                            }
                                                        ?>>0円〜500円</option>
                                    <option value = "2" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['price']) && $_POST['price'] === "2"){
                                                                echo "selected";
                                                            }elseif($result_post['price'] === "2"){
                                                                echo "selected";
                                                            }
                                                        ?>>500円〜1000円</option>
                                    <option value = "3" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['price']) && $_POST['price'] === "3"){
                                                                echo "selected";
                                                            }elseif($result_post['price'] === "3"){
                                                                echo "selected";
                                                            }
                                                        ?>>1000円〜1500円</option>
                                    <option value = "4" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['price']) && $_POST['price'] === "4"){
                                                                echo "selected";
                                                            }elseif($result_post['price'] === "4"){
                                                                echo "selected";
                                                            }
                                                        ?>>1500円〜2000円</option>
                                    <option value = "5" <?php
                                                            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['price']) && $_POST['price'] === "5"){
                                                                echo "selected";
                                                            }elseif($result_post['price'] === "5"){
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
                                    }else{
                                        $comment = $result_post['comment'];
                                    }
                                ?>
                                <div class="box_det"><textarea name="comment" cols="10" rows="5"><?php echo $comment;?></textarea></div>
                            </li>
                            <li>
                                <p class="title"><em>投稿写真</em></p></br>
                                <div class="box_det10">
                                    <?php if(!empty($error_file1)):?>
                                        <p class="text-danger"><?php echo $error_file1;?></p>
                                    <?php endif; ?>
                                    <!--ファイル１の処理-->
                                    <input type="file" name="file1" id="file1" accept='image/*' onchange="previewImage(this);" onclick="deselect1_0()"/>
                                    <?php 
                                            $first_file_name = $result_post['first_file_name'];
                                            echo "<img src='data:image/jpeg;base64,$first_file_name' title='投稿写真' width='160' height='160' id='image'>";
                                    ?>    
                                    <img id='preview' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='160' height='160'></br>
                                    <input type="button" id="deselect1" value="選択解除" onclick="deselect1_1()">
                                    <input type="hidden" name="deselect1" id="deselect1_2">
                                </div>
                            </li>
                            <li>
                                <p class="title2"><em></em></p></br>
                                <div class="box_det2">
                                    <!--ファイル2の処理-->  
                                    <input type="file" name="file2" id="file2" accept='image/*' onchange="previewImage2(this);" onclick="deselect2_0()"/>
                                    <?php 
                                            $second_file_name = $result_post['second_file_name'];
                                            echo "<img src='data:image/jpeg;base64,$second_file_name' title='投稿写真' width='160' height='160' id='image2'>";
                                    ?>
                                    <img id='preview2' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='160' height='160'></br>
                                    <input type="button" id="deselect2" value="選択解除" onclick="deselect2_1()">
                                    <input type="hidden" name="deselect2" id="deselect2_2">
                                </div>
                            </li>
                            <li>
                                <p class="title2"><em></em></p></br>
                                <div class="box_det2">
                                    <!--ファイル3の処理-->
                                    <input name="file3" type="file" id="file3"  accept='image/*' onchange="previewImage3(this);" onclick="deselect3_0()"/>
                                    <?php 
                                            $third_file_name = $result_post['third_file_name'];
                                            echo "<img src='data:image/jpeg;base64,$third_file_name' title='投稿写真' width='160' height='160' id='image3'>";
                                    ?>
                                    <img id='preview3' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='160' height='160'></br>
                                    <input type="button" id="deselect3" value="選択解除" onclick="deselect3_1()">
                                    <input type="hidden" name="deselect3" id="deselect3_2">
                                </div>
                            </li>
                            <li>
                                <p class="title2"><em></em></p></br>
                                <div class="box_det2">
                                    <!--ファイル4の処理-->
                                    <input name="file4" type="file" id="file4" accept='image/*' onchange="previewImage4(this);" onclick="deselect4_0()"/>
                                    <?php 
                                            $fourth_file_name = $result_post['fourth_file_name'];
                                            echo "<img src='data:image/jpeg;base64,$fourth_file_name' title='投稿写真' width='160' height='160' id='image4'>";
                                    ?>
                                    <img id='preview4' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='160' height='160'></br>
                                    <input type="button" id="deselect4" value="選択解除" onclick="deselect4_1()">
                                    <input type="hidden" name="deselect4" id="deselect4_2">
                                    <script src="update_post.js"></script>
                                </div>
                            </li>
                        </ul>
                        <input type="hidden" name="post_id" value= <?php echo $result_post['post_id'];?>>
                        <div class="button-panel">
                            <input type="submit" class="button1"name="_method" value="編集" formaction=<?php echo "update_post.php?post_id=$post_id";?>>
                            <input type="submit" class="button1" name="_method" value="キャンセル" formaction="profile.php">
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