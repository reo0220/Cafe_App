<?php

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
                    <input name="userfile[]" type="file"/><br/>
                    <input name="userfile[]" type="file"/><br/>
                    <input name="userfile[]" type="file"/><br/>
                    <input name="userfile[]" type="file"/><br/>
                </li>
                <input type="submit" name="_method"  value="投稿" formaction="create_post.php">
                <input type="submit" name="_method" value="キャンセル" formaction="post_list.php">
            </ul>
        </form>
    </body>
</html>
