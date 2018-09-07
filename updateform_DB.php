<?php
session_start();
 ?>

 <!DOCTYPE html>
 <html lang="ja">
 <head>
   <meta charset="utf-8">
   <title>DBの更新用フォーム</title>
 </head>

 <body>
 <hr>
 更新画面
 <hr>
 <!-- "list_DB.php"に戻る -->
[ <a href="list_DB.php"> 戻る </a> ]
 <br>

 <?php
 // "MYDB.php"を読み込む
 require_once("MYDB.php");
 $pdo = db_connect();   // DBに接続

// 更新対象
 if ( isset($_GET['id']) && $_GET['id'] > 0) {  // 「isset」で、変数が入っていて、NULLでないことを確認
   $id = $_GET['id'];
   $_SESSION['id'] = $id;
 } else { // 変数に正しく入っていない時
   exit('パラメータが不正です。');   // 「exit」で、メッセージを出力後、閉じる
 }

 try {
  $sql = "SELECT * FROM member WHERE id = :id"; // 検索文 id検索を指定
  $stmh = $pdo->prepare($sql);  // 「prepare」:	値部分にパラメータを付けて実行待ち
  $stmh->bindValue(':id', $id, PDO::PARAM_INT); // 「PARAM_INT」: 数値型
  $stmh->execute(); // 実行
  $count = $stmh->rowCount(); // 「rowCount」: PDOStatement オブジェクトによって実行された 直近の DELETE, INSERT, UPDATE 文によって作用した行数を返す。
} catch(PDOException $Exception) {  // エラー処理
  print "エラー：".$Exception->getMessage();
}

if($count < 1){
  print "更新データがありません。<br>"; // 検索結果が0件の時
} else {
  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // 検索結果が1件以上の場合は、検索結果を表示 $rowに連想配列が格納される

  ?>

  <form name="form1" method="post" action="list_DB.php">
    <!-- 「htmlspecialchars」で安全性を高める ※ HTMLの役割ではなく、ただの文字として表示する。HTMLエンティティ化 -->
    番号：<?=htmlspecialchars($row['id'], ENT_QUOTES)?><br> <!-- 「ENT_QUOTES」：シングルクォートとダブルクォート両方を置き換える -->
    氏：<input type="text" name="last_name" value="<?=htmlspecialchars($row['last_name'], ENT_QUOTES)?>"><br>
    名：<input type="text" name="first_name" value="<?=htmlspecialchars($row['first_name'], ENT_QUOTES)?>"><br>
    年齢：<input type="text" name="age" value="<?=htmlspecialchars($row['age'], ENT_QUOTES)?>"><br>

    <input type="hidden" name="action" value="update">
    <input type="submit" value="更新">
  </form>

  <?php
}
   ?>

 </body>

</html>
