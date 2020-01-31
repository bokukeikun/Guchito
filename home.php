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
    <title>Guchito</title>
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
            <h1><span>Guchito</span><br class="sp_br" >へようこそ！</h1>
            <h2>グチをたくさんつぶやこう！</h2>
            <a class="btn" href="login.php">Guchitoを始める</a>
            <a class="btn" href="join/index.php">Guchitoに登録する</a>
        </div>
    
        <div class="explain main">
            <div id="lead">
                <h1>Q&A</h1>
                <p></p>
                <div class="feature">
                    <h2 class="addComent"><span>Guchito</span>ってな〜に？<span class="plus">+</span></h2>
                    <p class="text-content">Guchitoとは、社会に溜まっている不満を好き放題に言えるチャットサービスだよ！<br>
                        特定の誰かを誹謗中傷する様なことは、やめてね！</p>
                </div>

                <div class="feature">
                    <h2 class="addComent">お金はかかるの？<span class="plus">+</span></h2>
                    <p class="text-content">完全に無料だから安心してね！<br>
                        お金を取ろうとする人がいても騙されないでね！</p>
                </div>

                <div class="feature">
                    <h2 class="addComent">誰が作ったの？？<span class="plus">+</span></h2>
                    <p class="text-content">ただの大学生だよ！！</p>
                </div>

<!-- 
                <div class="feature">
                    <h2 class="addComent"><span class="plus">+</span></h2>
                    <p class="text-content"></p>
                </div>
                
                <div class="feature">
                    <h2 class="addComent"><span class="plus">+</span></h2>
                    <p class="text-content"></p>
                </div> -->


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
        
        $('.feature').click(function(){
            if ($(this).hasClass('open')) {
                $(this).removeClass('open');
                $(this).find(".text-content").slideUp();
                $(this).find('.plus').text('+');
            }else {
                $(this).addClass('open');
                $(this).find(".text-content").slideDown();
                $(this).find('.plus').text('-');
            }
        });
    });
    </script>
</body>
</html>