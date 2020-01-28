<?php
session_start();
require('connection.php');

if (isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];

    $messages = $db->prepare('SELECT * FROM posts WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();

    if ($message['member_id'] == $_SESSION['id']) {
      //削除する
      $del = $db->prepare('DELETE FROM posts WHERE id=?');
      $del->execute(array($id));
    }
}

header('Location: index.php'); exit();

?>