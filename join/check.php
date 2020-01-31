<?php
session_start();
require('../connection.php');

if (!isset($_SESSION['join'])) {
    header('Location: index.php');
    exit();
}

if (!empty($_POST)) {
    $statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
    echo $ret = $statement->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image']
    ));
    unset($_SESSION['join']);
    
    header('Location: thanks.php');
    exit();
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
<body>

    <header>
        <div class="inner flex">
            <div class="logo">
                <a class="textshadow" href="../home.html">Guchito</a>
            </div>

            <div id="nav-open">
            <span></span>
            </div>


            <div id="nav-content">
                <div class="menu hamburger-top"></div>
                <ul class="flex category">
                    <li class="category-title"><a class="nav-btn" href="home.html">ホーム</a></li>
                    <li class="category-title"><a class="nav-btn" href="../login.php">ログイン</a></li>
                    <li class="category-title"><a class="nav-btn" href="index.php">メンバー登録</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="main">
            <div id="lead">
                <h1>登録内容</h1>
                <p>登録内容を確認してね</p>
            </div>
            <form action="check.php" method="post" class="check">
                <input type="hidden" name="action" value="submit">
                <table>
                    <tr>
                        <th>ニックネーム：</th>
                        <td>
                        <?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>ログインID：</th>
                        <td>
                        <?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>パスワード：</th>
                        <td>
                        『表示されません』
                        </td>
                    </tr>
                    <tr>
                        <th>写真など：</th>
                        <td>
                        <img src="../member_picture/<?php echo htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES) ?>" width="100" height="100" alt="" />
                        </td>
                    </tr>
                </table>
            
                <div>
                    <a class="rewrite btn" href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a><input class="btn" type="submit" value="登録する">
                </div>
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