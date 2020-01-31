<?php
session_start();
require('connection.php');

if (empty($_REQUEST['id'])) {
    header('Location: index.php');
    exit();
}

//投稿を取得する
$posts = $db->prepare('SELECT m.name, m.picture, p.*FROM members m, posts p WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
$posts->execute(array($_REQUEST['id']));

//htmlspecialcharsのショートカット
function h ($value) {
    return htmlspecialchars($value, ENT_QUOTES);
}

//本文内のURLにリンクを設定します
function makeLink($value) {
    return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)",'<a href="\1\2">\1\2</a>' ,$value);
}
?>

<p>&laquo; <a href="index.php">一覧に戻る</a></p>

<?php
if ($post = $posts->fetch()) :
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
                <a class="textshadow" href="home.html">Guchito</a>
            </div>
            
            <div id="submit-open">
            <button>ぐちる</button>
            </div>

            <div id="nav-open">
            <span></span>
            </div>


            <div id="nav-content">
                <div class="menu hamburger-top"></div>
                <ul class="flex category">
                    <!-- <li class="category-title"><a class="nav-btn" href="home.html">ホーム</a></li> -->
                    <li class="category-title"><a class="nav-btn" href="home.html">ホーム</a></li>
                    <li class="category-title"><a class="nav-btn" href="join/index.php">メンバー登録</a></li>
                    <li class="category-title"><a class="nav-btn" href="logout.php">ログアウト</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="main">

            <div class="main-header">
                <h2>ルールを守って、<br class="sp_br" >たまってるストレスを発散させよう！！</h2>
            </div>
            <div class="msg res-msg">
                            <div class="msg-header">
                                <div class="icon">
                                    <img src="member_picture/<?php echo h($post['picture']); ?>" width="40" height="40" alt="<?php echo h($post['name']); ?>" />
                                </div>
                                <div class="msg-header-content">
                                    <p class="name"><span><?php echo h($post['name']); ?></span></p>
                                    <p></p>
                                        <p>
                                            <?php  if ($_SESSION['id'] == $post['member_id']): ?>
                                            [ <a href="delete.php?id=<?php echo h($post['id']); ?>">削除</a> ]
                                            <?php endif; ?>
                                        </p>
                                    <p class="created">
                                        <!-- <a class="day" href="view.php?id=<?php // echo h($post['id']); ?>"> -->
                                            <?php echo h($post['created']); ?>
                                        <!-- </a> -->
                                    </p>
                                    <p>
                                        <?php if ($post['reply_id'] > 0):?>
                                        <a href="view.php?id=<?php echo h($post['reply_id']); ?>">[返信元]</a>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="msg-content">
                                <div class="flex">
                                    <h4>タイトル：<?php echo (h($post['title']))?></h4>
                                    <h4>カテゴリー：<?php echo (h($post['category']))?></h4>
                                </div>
                                <div class="message">
                                    <h4>ぐち内容</h4>
                                    <p><?php echo nl2br(makeLink(h($post['message'])));?></p>
                                </div>
                            </div>
                        </div>
            
            <?php else: ?>
                <p>その投稿は削除されたか、URLが間違っています。</p>
            <?php endif; ?>
        </div>
    </div>

    <div id="reply" class="btn">
            <button>返信内容を書く</button>
    </div>
    <div class="back btn">
        <p><a href="index.php">一覧に戻る</a></p>
    </div>


    <footer id="footer-content">
        <form action="index.php" method="post">
            <div class="footer_flex">
                <div id="footer-close">
                <span></span>
                </div>
                <div class="item">
                    <p><?php echo h($member['name']); ?>さん</p>
                </div>
                <div class="type">
                    <p>ぐちのタイトル：　</p>
                    <input type="text" height="20px" name="title" maxlength="10">
                    <p>カテゴリー：　</p>
                    <select name="category" id="">
                        <option value="会社">会社</option>
                        <option value="恋愛">恋愛</option>
                        <option value="学校">学校</option>
                        <option value="その他">その他</option>
                    </select>
                </div>
                <div class="comment">
                    <p>ぐち内容：　</p>
                    <textarea name="message" cols="50" rows="4" wrap="hard" maxlength="400" required><?php echo h($message)."\n"."\n"."->"; ?></textarea>
                    <input type="hidden" name="reply_id" value="<?php echo h($_REQUEST['res']); ?>">
                </div>
                <div class="submit">
                    <input class="submit_btn" type="submit" value="ぐちる" >
                </div>
            </div>
        </form>
    </footer>

    <script>
    $(function(){
        $("input"). keydown(function(e) {
            if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
                return false;
            } else {
                return true;
            }
        });

        $("#nav-open").on("click", function () {
          if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $("#nav-content").removeClass("open").fadeOut(100);
          } else {
            $(this).addClass("active");
            $("#nav-content").fadeIn(100).addClass("open");
          }
        });
    
        $("#submit-open").on("click", function () {
            $("#footer-close").addClass("active");
            $("#footer-content").fadeIn(100).addClass("open");
        });

        $("#reply").on("click", function () {
            $("#footer-close").addClass("active");
            $("#footer-content").fadeIn(100).addClass("open");
        });

        $("#footer-close").on("click", function () {
            $("footer-close").removeClass("active");
            $("#footer-content").removeClass("open").fadeOut(100);
        });
    });



    </script>
</body>
</html>

