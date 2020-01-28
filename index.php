
<?php
session_start();
require('connection.php');


if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    //ログインしている
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();
}else {
    //ログインしていない
    header('Location: login.php');
    exit();
}

//投稿を記録する


if (!empty($_POST)) {
	if ($_POST['message'] != '' && $_REQUEST['reply_id'] != '') {
		$message = $db->prepare('INSERT INTO posts SET member_id=?, title=?, category=?, message=?,reply_id=?,created=NOW()');
		$message->execute(array(
            $member['id'],
            $_POST['title'],
            $_POST['category'],
			$_POST['message'],
			$_REQUEST['reply_id']
		));
		header('Location: index.php'); exit();
	}else {
		$message = $db->prepare('INSERT INTO posts SET member_id=?, title=?, category=?, message=?,reply_id=0,created=NOW()');
		$message->execute(array(
			$member['id'],
            $_POST['title'],
            $_POST['category'],
			$_POST['message']
		));
		header('Location: index.php'); exit();
	}
}

//投稿を取得する

$page = $_REQUEST['page'];
if ($page == '') {
    $page = 1;
}
$page = max($page, 1);

//最終ページを取得する
$counts = $db->query('SELECT COUNT(*)  AS cnt FROM posts');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 10);
$page = min($page, $maxPage);

$start = ($page - 1) * 10;

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?, 10');
$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();

//返信の場合
if (isset($_REQUEST['res'])) {
    $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m,posts p WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
    $response->execute(array($_REQUEST['res']));

    $table = $response->fetch();
    $message = '@' . $table['name'] . '  ' . $table['message']. '->  ';
}

//htmlspecialcharsのショートカット
function h ($value) {
    return htmlspecialchars($value, ENT_QUOTES);
}

//本文内のURLにリンクを設定します
function makeLink($value) {
    return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)",'<a href="\1\2">\1\2</a>' ,$value);
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
                    <li class="category-title"><a class="nav-btn" href="login.php">ログイン</a></li>
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
            <div id="content">
            </div>
            <?php foreach ($posts as $post):?>
        
            <div class="msg">
                <div class="msg-header">
                    <div class="icon">
                        <img src="member_picture/<?php echo h($post['picture']); ?>" width="40" height="40" alt="<?php echo h($post['name']); ?>" />
                    </div>
                    <div class="msg-header-content">
                        <p class="name"><span><?php echo h($post['name']); ?></span></p>
                        <p>[<a href="reply.php?res=<?php echo h($post['id']); ?>">返信</a>]</p>
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
                        <!-- <p>
                            <?//php if ($post['reply_id'] > 0):?>
                            <a href="view.php?id=<?php // echo h($post['reply_id']); ?>">[返信元]</a>
                            <?php //endif; ?>
                        </p> -->
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
            <?php endforeach; ?>
            
        </div>


        <ul class="paging">
            <?php if ($page > 1) { ?>
            <li><a class="btn" href="index.php?page=<?php print($page - 1); ?>">前のページへ</a></li>
            <?php } else { ?>
            <?php } ?>
            <?php if ($page < $maxPage) { ?>
            <li><a class="btn" href="index.php?page=<?php print($page + 1); ?>">次のページへ</a></li>
            <?php } else { ?>
            <?php } ?>
        </ul>
    </div>
    
    <!-- 投稿フォーム -->
    <footer id="footer-content">
        <form action="" method="post">
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
                    <textarea name="message" cols="30" rows="4" wrap="hard" maxlength="200" required><?php echo h($message); ?></textarea>
                    <input type="hidden" name="reply_id" value="<?php echo h($_REQUEST['res']); ?>">
                </div>
                <div class="submit">
                    <input class="submit_btn" type="submit" value="投稿する" >
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

        $("#footer-close").on("click", function () {
            $("footer-close").removeClass("active");
            $("#footer-content").removeClass("open").fadeOut(100);
        });
    });



    </script>
</body>
</html>



