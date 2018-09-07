<?php
session_start();  // セッション開始
 ?>

<!DOCTYPE html>
<html lang="ja">
 <head>
   <meta charset="utf-8">
   <title>DB内の検索、挿入、更新、削除機能</title>
 </head>

 <body>
   <hr>
   §会員名簿一覧§
   <hr>
   [ <a href="form_DB.html">新規登録</a> ] <!-- 新規登録へのリンク -->
   <br>
   <form name="form1" method="post" action="list_DB.php"> <!-- 検索フォームをここに表示 -->
     名前：<input type="text" name="search_key"><input type="submit" value="検索する">
   </form>

   <?php
   require_once("MYDB.php"); // ここで"MYDB.php"を呼び出し
   $pdo = db_connect(); // ここで接続

   // 以下、削除処理
   if (isset($_GET['action']) && $_GET['action'] == 'delete' && $_GET['id'] > 0) {  // $_GET['action']がdeleteの時、削除する
     try {
       $pdo->beginTransaction();  // トランザクション開始
       $id = $_GET['id']; // id取得
       $sql = "DELETE FROM member WHERE id=:id";
       $stmh = $pdo->prepare($sql); // 「prepare」:	値部分にパラメータを付けて実行待ち
       $stmh->bindValue(':id',$id, PDO::PARAM_INT); // 値をバインド
       $stmh->execute();  // 実行
       $pdo->commit();  // 確定
       print "データを".$stmh->rowCount()."件、削除しました。<br>";
     } catch(PDOException $Exception) { // エラーが起きた場合
       $pdo->rollBack();  // ロールバック
       print "エラー：".$Exception->getMessage();
     }
   }

   // 以下、挿入処理
   if (isset($_POST['action']) && $_POST['action'] == 'insert') {   // $_POST['action']がinsertの時、挿入処理をする
     try {
       $pdo->beginTransaction();  // トランザクション開始
       $sql = "INSERT INTO member (last_name, first_name, age) VALUES ( :last_name, :first_name, :age)";
       $stmh = $pdo->prepare($sql);   // 「prepare」:	値部分にパラメータを付けて実行待ち
       $stmh->bindValue(':last_name', $_POST['last_name'], PDO::PARAM_STR); // バインド
       $stmh->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
       $stmh->bindValue(':age', $_POST['age'], PDO::PARAM_INT);
       $stmh->execute();  // 実行
       $pdo->commit();  // 確定
       print "データを".$stmh->rowCount()."件、挿入しました。<br>";
     } catch(PDOException $Exception) { // エラーが起きた場合
       $pdo->rollBack();  // ロールバック
       print "エラー：".$Exception->getMessage();
     }
   }

   // 以下、更新処理
   if (isset($_POST['action']) && $_POST['action'] == 'update') {   // $_POST['action']がupdateの時、更新処理をする
     $id = $_SESSION['id']; // セッション変数からidを受け取る

     try {
       $pdo->beginTransaction();  // トランザクション開始
       $sql = "UPDATE member SET last_name = :last_name, first_name = :first_name, age = :age WHERE id = :id";
       $stmh = $pdo->prepare($sql);   // 「prepare」:	値部分にパラメータを付けて実行待ち
       $stmh->bindValue(':last_name', $_POST['last_name'], PDO::PARAM_STR); // バインド
       $stmh->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
       $stmh->bindValue(':age', $_POST['age'], PDO::PARAM_INT);
       $stmh->bindValue(':id', $id, PDO::PARAM_INT);
       $stmh->execute();  // 実行
       $pdo->commit();  // 確定
       print "データを".$stmh->rowCount()."件、更新しました。<br>";
     } catch(PDOException $Exception) {   // エラーが起きた場合
       $pdo->rollBack();  // ロールバック
     }
    // 使用したセッション変数を削除する。
    unset($_SESSION['id']);  // unsetで、$_SESSION['id']を破棄
   }

   // 検索および、現在の全データを表示する
   try {
     if (isset($_POST['search_key']) && $_POST['search_key']!="") {   // 検索に情報が入っていた場合
       $search_key = '%'.$_POST['search_key'].'%';  // 中間一致検索
       $sql = "SELECT * FROM member WHERE last_name like :last_name OR first_name like :first_name";  // 苗字か名前どちらか一致したものを抽出
       $stmh = $pdo->prepare($sql);   // 「prepare」:	値部分にパラメータを付けて実行待ち
       $stmh->bindValue(':last_name', $search_key, PDO::PARAM_STR); // バインド
       $stmh->bindValue(':first_name', $search_key,PDO::PARAM_STR);
       $stmh->execute();  // 実行
     } else {   // 検索に情報が無かったら
       $sql = "SELECT * FROM member";
       $stmh = $pdo->query($sql);
     }
     $count = $stmh->rowCount();
     print "検索結果は".$count."件です。<br>";
   } catch(PDOException $Exception) {
     print "エラー：".$Exception->getMessage();
   }

   // 挿入処理
   if ($count < 1){   // 検索結果が0件の時
     print "検索結果はありません。<br>";
   } else {

   ?>

<table border="1">  <!-- テーブルの作成 -->
  <tbody>
    <tr><th> 番号 </th><th> 氏 </th><th> 名 </th><th> 年齢 </th><th>&nbsp;</th><th>&nbsp;</th></tr> <!-- 見出し設定 -->

    <?php
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {   // 検索結果を表示 $rowに連想配列が格納される
      ?>
    <tr>
      <!-- 「htmlspecialchars」で安全性を高める ※ HTMLの役割ではなく、ただの文字として表示する。HTMLエンティティ化 -->
      <td><?=htmlspecialchars($row['id'], ENT_QUOTES)?></td>  <!-- 「ENT_QUOTES」：シングルクォートとダブルクォート両方を置き換える -->
      <td><?=htmlspecialchars($row['last_name'], ENT_QUOTES)?></td>
      <td><?=htmlspecialchars($row['first_name'], ENT_QUOTES)?></td>
      <td><?=htmlspecialchars($row['age'], ENT_QUOTES)?></td>
      <!-- 更新用のリンクを追加 -->
      <td><a href=updateform_DB.php?id=<?=htmlspecialchars($row['id'], ENT_QUOTES)?>> 更新 </a></td>
      <td><a href=list_DB.php?action=delete&id=<?=htmlspecialchars($row['id'], ENT_QUOTES)?>> 削除 </a></td>
    </tr>

    <?php
  }
     ?>
   </tbody></table>
   <br>
    ◆<a href="../index.php">INDEXページへ。</a>
   <?php
 }
    ?>

 </body>

</html>
