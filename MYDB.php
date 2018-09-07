<?php

function db_connect() {
  $db_user = "root";  // ユーザ名
  $db_pass = "M42Boq$8";  // パスワード
  $db_host = "localhost"; // ホスト名
  $db_name = "DB_test"; // データベース名
  $db_type = "mysql"; // データベースの種類

  $dsn = "$db_type:host=$db_host;dbname=$db_name;charset=utf8";

  try {
    $pdo = new PDO($dsn, $db_user, $db_pass); // データベースに接続
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // エラーモードの設定
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  // PDOクラスのエミュレーションを無効に
  } catch(PDOException $Exception) {  // エラー処理
    die('エラー：'.$Exception->getMessage());
  }
  return $pdo;
}

 ?>
