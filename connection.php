<?php
$dbname = "mysql:dbname=guchito_server;host=mysql8027.xserver.jp;charset=utf8";
$username = "guchito_user";
$password = "hand0221";

try {
    $db = new PDO($dbname, $username, $password);
} catch (PDOException $e) {
    echo 'DB接続エラー: '. $e->getMessage();
}
?>