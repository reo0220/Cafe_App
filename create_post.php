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
            $id = $dbh->lastInsertId();
            $image2 = uniqid(mt_rand(), true);
            $image2 .= '.' . substr(strrchr($_FILES['file2']['name'], '.'), 1);
            $file2 = "post_medias/$image2";
            $sql2 = "UPDATE post_medias SET second_file_name = :file2 WHERE media_id = $id";
            $stmt2 = $dbh->prepare($sql2);
            $stmt2->bindValue(':file2', $image2, PDO::PARAM_STR);
            move_uploaded_file($_FILES['file2']['tmp_name'], './post_medias/' . $image2);
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
            $image3 = uniqid(mt_rand(), true);
            $image3 .= '.' . substr(strrchr($_FILES['file3']['name'], '.'), 1);
            $file3 = "post_medias/$image3";
            $sql3 = "UPDATE post_medias SET third_file_name = :file3 WHERE media_id = $id";
            $stmt3 = $dbh->prepare($sql3);
            $stmt3->bindValue(':file3', $image3, PDO::PARAM_STR);
            move_uploaded_file($_FILES['file3']['tmp_name'], './post_medias/' . $image3);
            if (exif_imagetype($file3)) {
                    $message3 = '画像をアップロードしました';
                    $stmt3->execute();
            } else {
                    $message3 = '画像ファイルではありません';
            }
        }else{
            header("Location:http://localhost/cafe_app/Cafe_App/toppage.php");
        }

        if(!empty($_FILES['file4']['name'])){
            $image4 = uniqid(mt_rand(), true);
            $image4 .= '.' . substr(strrchr($_FILES['file4']['name'], '.'), 1);
            $file4 = "post_medias/$image4";
            $sql4 = "UPDATE post_medias SET fourth_file_name = :file4 WHERE media_id = $id";
            $stmt4 = $dbh->prepare($sql4);
            $stmt4->bindValue(':file4', $image4, PDO::PARAM_STR);
            move_uploaded_file($_FILES['file4']['tmp_name'], './post_medias/' . $image4);
            if (exif_imagetype($file4)) {
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
        <main class = "main0">
            <div class="main2">
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
                            <label>投稿写真</label></br>
                            <input name="file1" type="file" id="file1" accept='image/*' onchange="previewImage(this);"></br>
                            <img id='preview' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='80' height='80'></br>
                            <input type="button" id="deselect" value="選択解除" onclick="deselect1()"></br>
                        </li>
                        <li>
                            <input name="file2" type="file" id="file2" accept='image/*' onchange="previewImage2(this);"></br>
                            <img id='preview2' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='80' height='80'></br>
                            <input type="button" id="deselect2" value="選択解除" onclick="deselect2_1()"></br>
                        </li>
                        <li>
                            <input name="file3" type="file" id="file3" accept='image/*' onchange="previewImage3(this);"></br>
                            <img id='preview3' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='80' height='80'></br>
                            <input type="button" id="deselect3" value="選択解除" onclick="deselect3_1()"></br>
                        </li>
                        <li>
                            <input name="file4" type="file" id="file4" accept='image/*' onchange="previewImage4(this);"></br>
                            <img id='preview4' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='80' height='80'></br>
                            <input type="button" id="deselect4" value="選択解除" onclick="deselect4_1()"></br>
                        </li>

                            <script>
                                //最初は非表示
                                document.getElementById("deselect").style.visibility = "hidden";
                                document.getElementById("deselect2").style.visibility = "hidden";
                                document.getElementById("deselect3").style.visibility = "hidden";
                                document.getElementById("deselect4").style.visibility = "hidden";
                                document.getElementById("file2").style.visibility = "hidden";
                                document.getElementById("file3").style.visibility = "hidden";
                                document.getElementById("file4").style.visibility = "hidden";
                                
                                //プレビューの表示
                                function previewImage(obj){
                                    var fileReader = new FileReader();
                                    fileReader.onload = (function() {
                                        document.getElementById("preview").style.visibility = "visible";
                                        document.getElementById('preview').src = fileReader.result;
                                    });
                                    fileReader.readAsDataURL(obj.files[0]);
                                }
                                function previewImage2(obj){
                                    var fileReader2 = new FileReader();
                                    fileReader2.onload = (function() {
                                        document.getElementById("preview2").style.visibility = "visible";
                                        document.getElementById('preview2').src = fileReader2.result;
                                    });
                                    fileReader2.readAsDataURL(obj.files[0]);
                                }
                                function previewImage3(obj){
                                    var fileReader3 = new FileReader();
                                    fileReader3.onload = (function() {
                                        document.getElementById("preview3").style.visibility = "visible";
                                        document.getElementById('preview3').src = fileReader3.result;
                                    });
                                    fileReader3.readAsDataURL(obj.files[0]);
                                }
                                function previewImage4(obj){
                                    var fileReader4 = new FileReader();
                                    fileReader4.onload = (function() {
                                        document.getElementById("preview4").style.visibility = "visible";
                                        document.getElementById('preview4').src = fileReader4.result;
                                    });
                                    fileReader4.readAsDataURL(obj.files[0]);
                                }

                                //選択解除をクリックした時
                                function deselect1(){
                                    document.getElementById("preview").style.visibility = "hidden";
                                    document.getElementById("file1").value = "";
                                    document.getElementById("file2").style.visibility = "hidden";
                                    document.getElementById("deselect").style.visibility = "hidden";
                                }
                                function deselect2_1(){
                                    document.getElementById("preview2").style.visibility = "hidden";
                                    document.getElementById("file2").value = "";
                                    document.getElementById("file3").style.visibility = "hidden";
                                    document.getElementById("deselect2").style.visibility = "hidden";
                                }
                                function deselect3_1(){
                                    document.getElementById("preview3").style.visibility = "hidden";
                                    document.getElementById("file3").value = "";
                                    document.getElementById("file4").style.visibility = "hidden";
                                    document.getElementById("deselect3").style.visibility = "hidden";
                                }
                                function deselect4_1(){
                                    document.getElementById("preview4").style.visibility = "hidden";
                                    document.getElementById("file4").value = "";
                                    document.getElementById("deselect4").style.visibility = "hidden";
                                }
                                
                                //ファイルを選択した時に表示
                                const fileInput = document.getElementById("file1");
                                const handleFileSelect = () => {
                                    const files = fileInput.files;
                                    if(files.length === 1){
                                        document.getElementById("file2").style.visibility = "visible";
                                        document.getElementById("deselect").style.visibility = "visible";
                                    }
                                }
                                fileInput.addEventListener('change', handleFileSelect);
                                
                                const fileInput2 = document.getElementById("file2");
                                const handleFileSelect2 = () => {
                                    const files2 = fileInput2.files;
                                    if(files2.length === 1){
                                        document.getElementById("file3").style.visibility = "visible";
                                        document.getElementById("deselect2").style.visibility = "visible";
                                    }
                                }
                                fileInput2.addEventListener('change', handleFileSelect2);

                                const fileInput3 = document.getElementById("file3");
                                const handleFileSelect3 = () => {
                                    const files3 = fileInput3.files;
                                    if(files3.length === 1){
                                        document.getElementById("file4").style.visibility = "visible";
                                        document.getElementById("deselect3").style.visibility = "visible";
                                    }
                                }
                                fileInput3.addEventListener('change', handleFileSelect3);

                                const fileInput4 = document.getElementById("file4");
                                const handleFileSelect4 = () => {
                                    const files4 = fileInput4.files;
                                    if(files4.length === 1){
                                        document.getElementById("deselect4").style.visibility = "visible";
                                    }
                                }
                                fileInput4.addEventListener('change', handleFileSelect4);
                            </script>
                        <li>
                            <?php if(!empty($error_file1)):?>
                                <p class="text-danger"><?php echo $error_file1;?></p>
                            <?php endif; ?>
                        </li>                   
                        <li><input type="submit" name="_method"  value="投稿" formaction="create_post.php"></li>
                        <li><input type="submit" name="_method" value="キャンセル" formaction="post_list.php"></li>
                    </ul>
                </form>
            </div>
        </main>  
        <footer class="footer">
            <div>
                フッター
            </div>
        </footer>  
    </body>
</html>
