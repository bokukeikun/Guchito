
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
            <div class="menu">
                <ul class="flex">
                    <li><a href="login.php">ログイン</a></li>
                    <li><a href="join/index.php">メンバー登録</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container">

        <div class="main">
            <h1><span>Guchito</span>へようこそ！</h1>
            <h2>グチをたくさんつぶやこう！</h2>
            <a class="btn" href="login.php">Guchitoを始める</a>
            <a class="btn" href="join/index.php">Guchitoに登録する</a>
        </div>
    
        <div class="explain">
            <div class="QA">

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
                    <h2 class="addComent">sss<span class="plus">+</span></h2>
                    <p class="text-content"></p>
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
        $('.feature').click(function(){
            if ($('.feature').hasClass('open')) {
                $(this).removeClass('open');
                $(this).find(".text-content").slideUp();
                $(this).find('.plus').text('+');
            }else {
                $(this).addClass('open');
                $(this).find(".text-content").slideDown();
                $(this).find('.plus').text('-');
            }
        });
    </script>
</body>
</html>