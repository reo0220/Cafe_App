<?php
    session_start();
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }else{
        $param_json = "";
    }

    if(empty($_GET['post_id']) && !empty($user_id)){
        $er_update_post = "";
    }


    if(!empty($user_id) && !empty($_GET['post_id'])){
        $motourl = $_SERVER['HTTP_REFERER'];
        $post_id = $_GET['post_id'];
        $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
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

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $url = $_POST['url'];
            
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
                mb_internal_encoding("utf8");
                $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
                $sql_post_edit = "UPDATE posts SET name = '$_POST[name]',place='$_POST[place]',price='$_POST[price]',comment='$_POST[comment]' WHERE post_id = $post_id_edit";
                $stmt_post_edit = $dbh->query($sql_post_edit);

                if(!empty($_FILES['file1']['name'])){//file1の登録処理
                    $image1 = uniqid(mt_rand(), true);//ファイル名をユニーク化
                    $image1 .= '.' . substr(strrchr($_FILES['file1']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
                    $file1 = "post_medias/$image1";
                    $sql1 = "UPDATE post_medias SET first_file_name = :file1 WHERE post_id = $post_id_edit";
                    $stmt1 = $dbh->prepare($sql1);
                    $stmt1->bindValue(':file1', $image1, PDO::PARAM_STR);
                    move_uploaded_file($_FILES['file1']['tmp_name'], './post_medias/' . $image1);//post_mediasディレクトリにファイル保存
                    if (exif_imagetype($file1)) {//画像ファイルかのチェック
                            $message1 = '画像をアップロードしました';
                            $stmt1->execute();
                    } else {
                            $message1 = '画像ファイルではありません';
                    }
                }elseif($file1_input === "0"){
                    $sql1_file_delete = "UPDATE post_medias SET first_file_name = '' WHERE post_id = $post_id_edit";
                    $dbh->query($sql1_file_delete);
                }
                
                if(!empty($_FILES['file2']['name'])){//file2が選択されているとき、登録処理を行う
                    $image2 = uniqid(mt_rand(), true);//ファイル名をユニーク化
                    $image2 .= '.' . substr(strrchr($_FILES['file2']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
                    $file2 = "post_medias/$image2";
                    $sql2 = "UPDATE post_medias SET second_file_name = :file2 WHERE post_id = $post_id_edit";
                    $stmt2 = $dbh->prepare($sql2);
                    $stmt2->bindValue(':file2', $image2, PDO::PARAM_STR);
                    move_uploaded_file($_FILES['file2']['tmp_name'], './post_medias/' . $image2);//post_mediasディレクトリにファイル保存
                    if (exif_imagetype($file2)) {//画像ファイルかのチェック
                            $message2 = '画像をアップロードしました';
                            $stmt2->execute();
                    } else {
                            $message2 = '画像ファイルではありません';
                    }
                }elseif($file2_input === "0"){
                    $sql2_file_delete = "UPDATE post_medias SET second_file_name = '' WHERE post_id = $post_id_edit";
                    $dbh->query($sql2_file_delete);
                }
                
                if(!empty($_FILES['file3']['name'])){
                    $image3 = uniqid(mt_rand(), true);//ファイル名をユニーク化
                    $image3 .= '.' . substr(strrchr($_FILES['file3']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
                    $file3 = "post_medias/$image3";
                    $sql3 = "UPDATE post_medias SET third_file_name = :file3 WHERE post_id = $post_id_edit";
                    $stmt3 = $dbh->prepare($sql3);
                    $stmt3->bindValue(':file3', $image3, PDO::PARAM_STR);
                    move_uploaded_file($_FILES['file3']['tmp_name'], './post_medias/' . $image3);//post_mediasディレクトリにファイル保存
                    if (exif_imagetype($file3)) {//画像ファイルかのチェック
                            $message3 = '画像をアップロードしました';
                            $stmt3->execute();
                    } else {
                            $message3 = '画像ファイルではありません';
                    }
                }elseif($file3_input === "0"){
                    $sql3_file_delete = "UPDATE post_medias SET third_file_name = '' WHERE post_id = $post_id_edit";
                    $dbh->query($sql3_file_delete);
                }
                
                if(!empty($_FILES['file4']['name'])){
                    $image4 = uniqid(mt_rand(), true);//ファイル名をユニーク化
                    $image4 .= '.' . substr(strrchr($_FILES['file4']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
                    $file4 = "post_medias/$image4";
                    $sql4 = "UPDATE post_medias SET fourth_file_name = :file4 WHERE post_id = $post_id_edit";
                    $stmt4 = $dbh->prepare($sql4);
                    $stmt4->bindValue(':file4', $image4, PDO::PARAM_STR);
                    move_uploaded_file($_FILES['file4']['tmp_name'], './post_medias/' . $image4);//post_mediasディレクトリにファイル保存
                    if (exif_imagetype($file4)) {//画像ファイルかのチェック
                            $message4 = '画像をアップロードしました';
                            $stmt4->execute();
                    } else {
                            $message4 = '画像ファイルではありません';
                    }
                }elseif($file4_input === "0"){
                    $sql4_file_delete = "UPDATE post_medias SET fourth_file_name = '' WHERE post_id = $post_id_edit";
                    $dbh->query($sql4_file_delete);
                }
                //エラーが起きた時、編集画面が前画面になってしまう(更新はできてる)
                header("Location:http://localhost/cafe_app/Cafe_App/post_list.php");
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>投稿編集</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    </head>
    <script>
        //ログインまたはアカウント登録していない場合
        const param = '<?=$param_json?>';
        window.onload = function(){
            if(param == ""){
                Swal.fire({
                    title: 'ログインか新規登録を行ってください。',
                    type : 'warning',
                    bottons:true,
                    grow : 'fullscreen',
                    confirmButtonText:"ログインまたは新規登録",
                    allowOutsideClick:false
                }).then((result) =>{//「ログインまたは新規登録」ボタンをクリックした時、ログイン画面へ遷移
                    if(result.value){
                        window.location.href ="./login.php";
                    }
                });
            }
        }
    </script>
    <script>
        //ログインはしているけど、「編集」ボタンから遷移していない時
        const er_update = '<?=$er_update_post?>';
        window.onload = function(){
            if(er_update == ""){
                Swal.fire({
                    title: '編集する投稿を選択してください。',
                    type : 'warning',
                    bottons:true,
                    grow : 'fullscreen',
                    confirmButtonText:"投稿を選択",
                    allowOutsideClick:false
                }).then((result) =>{
                    if(result.value){
                        window.location.href ="./profile.php";
                    }
                });
            }
        }
    </script>
    </script>
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
                <h1 class="heading-lv1 text-center">投稿編集</h1>
                <form method="POST" action= <?php echo "update_post.php?post_id=$post_id";?> enctype='multipart/form-data'>
                    <ul>
                        <li>
                            <label>店名</label>
                            <input type = "text" name = "name" value=<?php echo $result_post['posts_name'];?>>
                            <?php if(!empty($error_name)):?>
                                <p class="text-danger"><?php echo $error_name;?></p>
                            <?php endif; ?>
                        </li>
                        <li>
                            <label>場所</label>
                            <select name="place">
                                <option value="1" <?php 
                                                        if($result_post['place'] === "1"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>千代田区</option>
                                <option value="2" <?php 
                                                        if($result_post['place'] === "2"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>中央区</option>
                                <option value="3" <?php 
                                                        if($result_post['place'] === "3"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>港区</option>
                                <option value="4" <?php 
                                                        if($result_post['place'] === "4"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>新宿区</option>
                                <option value="5" <?php 
                                                        if($result_post['place'] === "5"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>文京区</option>
                                <option value="6" <?php 
                                                        if($result_post['place'] === "6"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>台東区</option>
                                <option value="7" <?php 
                                                        if($result_post['place'] === "7"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>墨田区</option>
                                <option value="8" <?php 
                                                        if($result_post['place'] === "8"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>江東区</option>
                                <option value="9" <?php 
                                                        if($result_post['place'] === "9"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>品川区</option>
                                <option value="10" <?php 
                                                        if($result_post['place'] === "10"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>目黒区</option>
                                <option value="11" <?php 
                                                        if($result_post['place'] === "11"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>大田区</option>
                                <option value="12" <?php 
                                                        if($result_post['place'] === "12"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>世田谷区</option>
                                <option value="13"<?php 
                                                        if($result_post['place'] === "13"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>渋谷区</option>
                                <option value="14" <?php 
                                                        if($result_post['place'] === "14"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>中野区</option>
                                <option value="15" <?php 
                                                        if($result_post['place'] === "15"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>杉並区</option>
                                <option value="16" <?php 
                                                        if($result_post['place'] === "16"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>豊島区</option>
                                <option value="17" <?php 
                                                        if($result_post['place'] === "17"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>北区</option>
                                <option value="18" <?php 
                                                        if($result_post['place'] === "18"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>荒川区</option>
                                <option value="19" <?php 
                                                        if($result_post['place'] === "19"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>板橋区</option>
                                <option value="20" <?php 
                                                        if($result_post['place'] === "20"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>練馬区</option>
                                <option value="21" <?php 
                                                        if($result_post['place'] === "21"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>足立区</option>
                                <option value="22" <?php 
                                                        if($result_post['place'] === "22"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>葛飾区</option>
                                <option value="23" <?php 
                                                        if($result_post['place'] === "23"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>江戸川区</option>
                            </select>
                        </li>
                        <li>
                            <label>価格帯</label>
                            <select name = "price">
                                <option value = "1" <?php 
                                                        if($result_post['price'] === "1"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>0円〜500円</option>
                                <option value = "2" <?php 
                                                        if($result_post['price'] === "2"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>500円〜1000円</option>
                                <option value = "3" <?php 
                                                        if($result_post['price'] === "3"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>1000円〜1500円</option>
                                <option value = "4" <?php 
                                                        if($result_post['price'] === "4"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>1500円〜2000円</option>
                                <option value = "5" <?php 
                                                        if($result_post['price'] === "5"){
                                                            echo "selected";
                                                        }else{
                                                            echo "";
                                                        }
                                                    ?>>2000円〜</option>
                            </select>
                        </li>
                        <li>
                            <label>コメント</label>
                            <textarea name="comment" rows="5" cols="33"><?php echo $result_post['comment'];?></textarea>
                        </li>
                        <li><label>投稿写真</label></li>
                        <?php if(!empty($error_file1)):?>
                                <p class="text-danger"><?php echo $error_file1;?></p>
                        <?php endif; ?>
                        <li>
                            <!--ファイル１の処理-->
                            <?php 
                                if(!empty($result_post['first_file_name'])){
                                    $first_file_name = $result_post['first_file_name'];
                                    echo "<img src='post_medias/$first_file_name' alt='投稿写真' width='80' height='80' id='image'>";
                                }
                            ?>    
                            <img id='preview' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='80' height='80'></br>
                            <input type="file" name="file1" id="file1" accept='image/*' onchange="previewImage(this);" onclick="deselect1_0()"/>
                            <input type="button" id="deselect1" value="選択解除" onclick="deselect1_1()">
                            <input type="hidden" name="deselect1" id="deselect1_2">
                            <script>
                                function previewImage(obj){
                                    var fileReader = new FileReader();
                                    fileReader.onload = (function() {
                                        document.getElementById('preview').src = fileReader.result;
                                    });
                                    fileReader.readAsDataURL(obj.files[0]);
                                    //imageの非表示処理
                                    const image = document.getElementById("image");
                                    image.style.display ="none";
                                }
                                function deselect1_0(){
                                    setTimeout(() => {
                                    document.getElementById("preview").style.visibility = "visible";
                                    },250);
                                }
                                function deselect1_1(){
                                    document.getElementById("image").style.display ="none";
                                    document.getElementById("preview").style.visibility = "hidden";
                                    document.getElementById("file1").value = "";
                                    //ファイルが選択されていないとき、０をvalueに渡す
                                    document.getElementById("deselect1_2").value = "0";

                                } 
                                const fileInput = document.getElementById("file1");
                                //ファイルが選択されているとき、１をvalueに渡す
                                const handleFileSelect = () => {
                                    const files = fileInput.files;
                                    if(files.length === 1){
                                        document.getElementById("deselect1_2").value = "1";
                                    }
                                }
                                fileInput.addEventListener('change', handleFileSelect);
                            </script>
                        </li>
                        <li>
                            <!--ファイル2の処理-->  
                            <?php 
                                if(!empty($result_post['second_file_name'])){
                                    $second_file_name = $result_post['second_file_name'];
                                    echo "<img src='post_medias/$second_file_name' alt='投稿写真' width='80' height='80' id='image2'>";
                                }
                            ?>
                            <img id='preview2' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='80' height='80'></br>
                            <input type="file" name="file2" id="file2" accept='image/*' onchange="previewImage2(this);" onclick="deselect2_0()"/>
                            <input type="button" id="deselect2" value="選択解除" onclick="deselect2_1()">
                            <input type="hidden" name="deselect2" id="deselect2_2">
                            <script>
                                function previewImage2(obj){
                                    var fileReader2 = new FileReader();
                                    fileReader2.onload = (function() {
                                        document.getElementById('preview2').src = fileReader2.result;
                                    });
                                    fileReader2.readAsDataURL(obj.files[0]);
                                    //imageの非表示処理
                                    const image2 = document.getElementById("image2");
                                    image2.style.display ="none";
                                }
                                function deselect2_0(){
                                    setTimeout(() => {
                                    document.getElementById("preview2").style.visibility = "visible";
                                    },250);
                                }
                                function deselect2_1(){
                                    document.getElementById("image2").style.display ="none";
                                    document.getElementById("preview2").style.visibility = "hidden";
                                    document.getElementById("file2").value = "";
                                    document.getElementById("deselect2_2").value = "0";
                                } 
                                const fileInput2 = document.getElementById("file2");
                                const handleFileSelect2 = () => {
                                    const files2 = fileInput2.files;
                                    if(files2.length === 1){
                                        document.getElementById("deselect2_2").value = "1";
                                    }
                                }
                                fileInput2.addEventListener('change', handleFileSelect2);
                            </script>
                        </li>
                        <li>
                            <!--ファイル3の処理-->
                            <?php 
                                if(!empty($result_post['third_file_name'])){
                                    $third_file_name = $result_post['third_file_name'];
                                    echo "<img src='post_medias/$third_file_name' alt='投稿写真' width='80' height='80' id='image3'>";
                                }
                            ?>
                            <img id='preview3' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='80' height='80'></br>
                            <input name="file3" type="file" id="file3"  accept='image/*' onchange="previewImage3(this);" onclick="deselect3_0()"/>
                            <input type="button" id="deselect3" value="選択解除" onclick="deselect3_1()">
                            <input type="hidden" name="deselect3" id="deselect3_2">
                            <script>
                                function previewImage3(obj){
                                    var fileReader3 = new FileReader();
                                    fileReader3.onload = (function() {
                                        document.getElementById('preview3').src = fileReader3.result;
                                    });
                                    fileReader3.readAsDataURL(obj.files[0]);
                                    //imageの非表示処理
                                    const image3 = document.getElementById("image3");
                                    image3.style.display ="none";
                                }
                                function deselect3_0(){
                                    setTimeout(() => {
                                    document.getElementById("preview3").style.visibility = "visible";
                                    },250);
                                }
                                function deselect3_1(){
                                    document.getElementById("image3").style.display ="none";
                                    document.getElementById("preview3").style.visibility = "hidden";
                                    document.getElementById("file3").value = "";
                                    document.getElementById("deselect3_2").value = "0";
                                }
                                const fileInput3 = document.getElementById("file3");
                                const handleFileSelect3 = () => {
                                    const files3 = fileInput3.files;
                                    if(files3.length === 1){
                                        document.getElementById("deselect3_2").value = "1";
                                    }
                                }
                                fileInput3.addEventListener('change', handleFileSelect3);
                            </script>
                        </li>
                        <li>
                            <!--ファイル4の処理-->
                            <?php 
                                if(!empty($result_post['fourth_file_name'])){
                                    $fourth_file_name = $result_post['fourth_file_name'];
                                    echo "<img src='post_medias/$fourth_file_name' alt='投稿写真' width='80' height='80' id='image4'>";
                                }
                            ?>
                            <img id='preview4' src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' width='80' height='80'></br>
                            <input name="file4" type="file" id="file4" accept='image/*' onchange="previewImage4(this);" onclick="deselect4_0()"/>
                            <input type="button" id="deselect4" value="選択解除" onclick="deselect4_1()">
                            <input type="hidden" name="deselect4" id="deselect4_2">
                            <script>
                                function previewImage4(obj){
                                    var fileReader4 = new FileReader();
                                    fileReader4.onload = (function() {
                                        document.getElementById('preview4').src = fileReader4.result;
                                    });
                                    fileReader4.readAsDataURL(obj.files[0]);
                                    //imageの非表示処理
                                    const image4 = document.getElementById("image4");
                                    image4.style.display ="none";
                                }
                                function deselect4_0(){
                                    setTimeout(() => {
                                    document.getElementById("preview4").style.visibility = "visible";
                                    },250);
                                }
                                function deselect4_1(){
                                    document.getElementById("image4").style.display ="none";
                                    document.getElementById("preview4").style.visibility = "hidden";
                                    document.getElementById("file4").value = "";
                                    document.getElementById("deselect4_2").value = "0";
                                }
                                const fileInput4 = document.getElementById("file4");
                                const handleFileSelect4 = () => {
                                    const files4 = fileInput4.files;
                                    if(files4.length === 1){
                                        document.getElementById("deselect4_2").value = "1";
                                    }
                                }
                                fileInput4.addEventListener('change', handleFileSelect4);
                            </script>
                        </li>
                    </ul>
                    <input type="hidden" name="url" value= <?php echo $motourl;?>>
                    <input type="hidden" name="post_id" value= <?php echo $result_post['post_id'];?>>
                    <input type="submit" value="編集">
                </form>
                <input value="キャンセル" onclick="history.back();" type="button">
            </div>
        </main>  
        <footer class="footer">
            <div>
                フッター
            </div>
        </footer>  
    </body>
</html>