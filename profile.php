<?php
    session_start();

    if(!empty($_SESSION['user_id_log'])){
        $user_id = $_SESSION['user_id_log'];
    }elseif(!empty($_SESSION['user_id_sign'])){
        $user_id = $_SESSION['user_id_sign'];
    }else{
        echo "ログインしてない";
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
                            <option value="logout.php">ログアウト</option>
                        </select>
                    </form>
                </div>
            </main>  
            <footer class="footer">
                <div>
                    フッター
                </div>
            </footer>  
        </body>                