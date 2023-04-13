<?php
    session_start();
    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }else{
        $param_json = "";
    }

    if(!empty($user_id)){
        mb_internal_encoding("utf8");
        $dbh = new PDO("mysql:dbname=cafe_app;host=localhost;","root","root");
        $sql = "SELECT * FROM users WHERE user_id = $user_id ";//パラメータに渡された[user_id]のidの情報を取り出す
        $stmt = $dbh->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);//カラム名で添字付けた配列を返す

        $sql2 = "SELECT * FROM user_medias WHERE user_id = $user_id";
        $stmt2 = $dbh->query($sql2);
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        
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

            if(empty($error_name) && empty($error_mail) && empty($error_pas)){

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
    }
?>    


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>アカウント編集画面</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    </head>
    <script>
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
                            }).then((result) =>{//「ログイン」ボタンをクリックした時、ログイン画面へ遷移
                                if(result.value){
                                        window.location.href ="./login.php";
                                    }
                            });
                    }
                }
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
                <h1 class="heading-lv1 text-center">アカウント編集</h1>
                <form method = "POST" action = "?" enctype="multipart/form-data">
                    <ul>
                        <li>
                            <?php if(!empty($result2['file_name'])):?>
                                <figure class="profile-image">
                                    <img src='user_medias/<?php echo $result2['file_name'];?>' alt='投稿写真' width='300' height='300' id='image'>
                                </figure>
                            <?php endif;?>
                            
                            <figure class="profile-image" id="preview2" >
                                <img id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="300" height="300">
                            </figure>
                            <input type="file" name="image" id="file1" accept='image/*' onchange="previewImage(this);" onclick="deselect1_0()"/>
                            <input type="button" id="deselect1" value="選択解除" onclick="deselect1_1()">
                            <input type="hidden" name="deselect1" id="deselect1_2">
                            <script>
                                document.getElementById("preview2").style.visibility = "hidden";
                                function previewImage(obj){
                                    var fileReader = new FileReader();
                                    fileReader.onload = (function() {
                                        document.getElementById("preview2").style.visibility = "visible";
                                        document.getElementById("preview").style.visibility = "visible";

                                        document.getElementById('preview').src = fileReader.result;
                                    });
                                    fileReader.readAsDataURL(obj.files[0]);
                                    //imageの非表示処理
                                    const image = document.getElementById("image");
                                    image.style.display ="none";
                                }
                                function deselect1_0(){
                                    setTimeout(() => {
                                        document.getElementById("preview2").style.visibility = "visible";
                                    document.getElementById("preview").style.visibility = "visible";
                                    },250);
                                }
                                function deselect1_1(){
                                    document.getElementById("image").style.display ="none";
                                    document.getElementById("preview").style.visibility = "hidden";
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
                            <label>プロフィール画像</label>
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
                        <li><input type="submit" name="_method"  value="保存" formaction="edit_account.php"></li>
                        <li><input type="submit" name="_method" value="キャンセル" formaction="profile.php"></li>
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