## 制作物
PHP、MySQLを使用した会員管理システム


## 開発環境
CentOS7 PHP7.2 MySQL5.7 Apache2.4


## 事前準備
MySQLにて、データベース「DB_test」および、テーブル「memory」を作成
<br>※MySQLのユーザ名、パスワード、ホスト名、DB名、は「MYDB.php」の情報から作成をお願いします。


###### テーブル作成文：<br>
CREATE TABLE member (id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT, last_name VARCHAR(50), first_name VARCHAR(50), age TINYINT UNSIGNED, PRIMARY KEY (id) );


## 使用方法
「form_DB.html」「list_DB.php」「MYDB.php」「updateform_DB.php」


上記4ファイルをApacheのDocumentRootに配置し、「list_DB.php」にブラウザでアクセス ※chrome推奨
