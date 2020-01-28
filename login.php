<?php
require('connection.php');

session_start();

if ($_COOKIE['email'] != '') {
    $_POST['email'] = $_COOKIE['email'];
    $_POST['password'] = $_COOKIE['password'];
    $_POST['save'] = 'on';
}

if (!empty($_POST)) {
    //ログイン処理
    if ($_POST['email'] != '' && $_POST['password'] != '') {
		$login = $db->prepare('SELECT * FROM members WHERE email=? AND
			password=?');
        $login->execute(array(
            $_POST['email'],
            sha1($_POST['password'])
        ));
        $member = $login->fetch();
        
        if ($member) {
            //ログイン成功
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();
            
            //ログイン情報を記録する
            if ($_POST['save'] == 'on') {
                setcookie('email', $_POST['email'], time()+60*60*24*14);
                setcookie('password', $_POST['password'], time()+60*60*24*14);
            }
            
            header('Location: index.php');
            exit();
        }else {
            $error['login'] = 'failed';
        }
    }else {
        $error['login'] = 'blank';
    }
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Guchito login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
<body>
    
    <header>
        <div class="inner flex">
            <div class="logo">
                <a href="home.html">Guchito</a>
            </div>

            <div id="nav-open">
            <span></span>
            </div>


            <div id="nav-content">
                <div class="menu hamburger-top"></div>
                <ul class="flex category">
                    <li class="category-title"><a class="nav-btn" href="home.html">ホーム</a></li>
                    <li class="category-title"><a class="nav-btn" href="login.php">ログイン</a></li>
                    <li class="category-title"><a class="nav-btn" href="join/index.php">メンバー登録</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="main">
            <div id="lead" class="lead">
                <h1>ログイン画面</h1>
                <p>メールアドレスとパスワードを<br class="sp_br" >記入してログインしてください</p>
            </div>
            <form action="" method="post">
                <table>
                    <tr>
                    <th>メールアドレス：</th>
                        <td><input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>"></td>
                        <?php if ($error['login'] == 'blank'): ?>
                        <p class="error">* メールアドレスとパスワードを記入してください。</p>
                        <?php endif ?>
                        <?php if ($error['login'] == 'failed') :?>
                        <p class="error">* ログインに失敗しました。正しく記入してください。</p>
                        <?php endif; ?>
                    </tr>
                    <tr>
                    <th>パスワード：</th>
                        <td><input type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>"></td>
                    </tr>
                </table>
                <div class="save">
                    <input id="save" type="checkbox" name="save" value="on">
                    <label for="save">　次回からは自動的にログインする</label>
                </div>
                <div class="login btn"><input type="submit" value="ログインする"></div>
            </form>
            <div class="join_btn">
                <p>・入会手続きがまだの方はこちらからどうぞ</p>
                <p><a href="join/" class="btn">&raquo;メンバー登録する</a></p>
            </div>
        </div>
    </div>
    <script>
    $(function(){
        
        $("#nav-open").on("click", function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $("#nav-content").removeClass("open").fadeOut(100);
        } else {
            $(this).addClass("active");
            $("#nav-content").fadeIn(100).addClass("open");
        }
        });

    });
    </script>
</body>
</html>

