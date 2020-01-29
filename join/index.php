<?php
require_once('../connection.php');

session_start();



if (!empty($_POST)) {
    if ($_POST['name'] == '') {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] == '') {
        $error['email'] = 'blank';
    }
    if (strlen($_POST['password']) < 4) {
        $error['password'] = 'length';
    }
    if ($_POST['password'] == '') {
        $error['password'] = 'blank';
    } 
    $fileName = $_FILES['image']['name'];
    if (!empty($fileName)) {
        $ext = substr($fileName, -4);
        if ($ext != '.jpg' && $ext != '.gif' && $ext != 'jpeg' && $ext != 'heic' && $ext != '.png') {
            $error['image'] = 'type';
        }
    }

    //重複アカウントチェック
    if (empty($error)) {
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=? ');
        $member->execute(array($_POST['email']));
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['email'] = 'duplicate';
        }

        //google API

        if (isset($_POST['recaptchaResponse']) && !empty($_POST['recaptchaResponse'])) {
            $secret = '6Ldbi9MUAAAAAF8eXoLDESHRe2x6C5Rtnym7LOTU';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['recaptchaResponse']);
            $reCAPTCHA = json_decode($verifyResponse);
            if ($reCAPTCHA->success) {
                // たぶん人間
            } else {
                echo "あなたはbotと判断されました。";
                  return;
            }
        }

        if (empty($error)) {
            //画像をアップロードする
            $image = date('YmdHis') . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/'.$image);
    
            $_SESSION['join'] = $_POST;
            $_SESSION['join']['image'] = $image;
            header('Location: check.php');
            exit();
        }
    }


}


if ($_REQUEST['action'] == 'rewrite') {
    $_POST = $_SESSION['join'];
    $error['rewrite'] = true;
}




?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../style.css">
    <title>Guchito</title>
    <script src="https://www.google.com/recaptcha/api.js?render=6Ldbi9MUAAAAAACTJamCtskPFR5b0OUXeqfgbL8l"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6Ldbi9MUAAAAAACTJamCtskPFR5b0OUXeqfgbL8l', {action: 'homepage'}).then(function(token) {
        var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
            });
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
<body>

    <header>
        <div class="inner flex">
            <div class="logo">
                <a href="../home.html">Guchito</a>
            </div>

            <div id="nav-open">
            <span></span>
            </div>


            <div id="nav-content">
                <div class="menu hamburger-top"></div>
                <ul class="flex category">
                    <li class="category-title"><a class="nav-btn" href="../home.html">ホーム</a></li>
                    <li class="category-title"><a class="nav-btn" href="../login.php">ログイン</a></li>
                    <li class="category-title"><a class="nav-btn" href="index.php">メンバー登録</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="main">
            <div id="lead">
                <h1>登録フォーム</h1>
                <p>次のフォームに必要事項をご記入ください</p>
            </div>
            <form action="index.php" method="post" enctype="multipart/form-data">
                <table>
                    <tr>
                        <th>
                            <p>
                                ニックネーム：<span class="required">必須</span>
                            </p>
                        </th>
                    </tr>
                    
                    <tr>
                        <td>
                            <input type="text" name="name" maxlength="35" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES) ?>">
                            <?php if($error['name'] == 'blank'): ?>
                            <p class="error">*ニックネームを入力してください</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>メールアドレス：<span class="required">必須</span></th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="email" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES) ?>">
                            <?php if($error['email'] == 'blank'): ?>
                            <p class="error">*メールアドレスを入力してください</p>
                            <?php endif; ?>
                            <?php if ($error['email'] == 'duplicate'): ?>
                            <p class="error">*指定されたメールアドレスは既に登録されています</p>
                            <?php endif ;?>
                        </td>
                    </tr>
                    <tr>
                        <th>パスワード：<span class="required">必須</span></th>
                    </tr>
                    <tr>
                            
                        <td>
                            <input type="password" name="password" maxlength="20" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES) ?>">
                            <?php if($error['password'] == 'blank'): ?>
                            <p class="error">*パスワードを入力してください</p>
                            <?php endif; ?>
                            <?php if ($error['password'] == 'length'):?>
                            <p class="error">*パスワードは4文字以上で入力してください</p>
                            <?php endif;?>
                        </td>
                    </tr>
                    <tr>
                        <th>アイコン：<span class="required">必須</span></th>
                    </tr>
                    <tr>
                        <td>
                        <p class="file_image">
                            <input type="file" name="image" size="35">
                        </p>
                        <?php if($error['image'] == 'type'): ?>
                            <p class="error">*写真などは「.gif」または「.jpg」の画像を指定してください</p>
                            <?php endif; ?>
                            <?php if (!empty($error)): ?>
                            <p class="error">*恐れ入りますが、画像を改めて指定してください</p>
                            <?php endif;?>
                        </td>
                    </tr>
                    <tr>
                        <div class="g-recaptcha">
                            <input type="hidden" name="recaptchaResponse" id="recaptchaResponse" />
                        </div>
                    </tr>
                </table>
                <div><input class="btn confirm" type="submit" value="入力内容を確認する"></div>
            </form>
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


