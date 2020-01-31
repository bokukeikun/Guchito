

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
                <h1 class="completed">ユーザー登録が完了しました。</h1>
                <p><a class="btn" href="../">ログインする</a></p>
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