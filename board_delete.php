<?php
// *
// http://localhost/terashima/mypage_edit.php
// *
session_start();
$id = $_SESSION['id'];
$board_id = $_SESSION['board_id'];
$msg = '';

$dsn = 'mysql:dbname=t_db;host=localhost;charset=utf8';
$user = 'root';
$password = 'root';
$dbh = new PDO($dsn,$user,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // エラーレポート,例外を投げる
// ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
// $sql = 'SELECT * FROM board_tb WHERE board_id=? AND user_id=?';
$sql = 'SELECT board,date,path,gym,user_name
        FROM board_tb b LEFT JOIN user_tb u
        ON b.user_id = u.user_id
        WHERE board_id=? ';

$stmt = $dbh->prepare($sql); // 実行待ち
$data[] = $board_id;
$stmt->execute($data);

foreach ($stmt as $row) {
  $board = $row['board'];
  $date = $row['date'];
  $path = $row['path'];
  $gym = $row['gym'];
  $name = $row['user_name'];
}

if($_SERVER["REQUEST_METHOD"] === "POST") { // POSTが送信された時
  try{
    $sql = 'DELETE FROM board_tb WHERE board_id=? AND user_id=? ';
    $stmt2 = $dbh->prepare($sql); // パラメータを付けて実行待ち 
    $data2[] = $board_id;
    $data2[] = $id;

    $stmt2->execute($data2); // ?に変数nameを代入して実行
    $msg = "削除が完了しました。";
    header("Location: ./mypage.php?msg={$msg}");
    exit();

  }catch (Exception $e){
    echo 'ただいま障害により大変ご迷惑をおかけしております。<br>';
    exit($e -> getMessage());
  }
}
//
 
?>
 <!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
<html>
  <head>
    <meta charset="utf-8">
    <title>BolCo</title>
    <link rel="stylesheet" href="./style.css">
  </head>
  <body>
    <?php require_once('./header.html'); ?>
 <!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
    <main>
      <br>
    <form method="post" action="">
      
      <img src='./upfile/<?php echo $path ?>' class="detail"><br>
      <?php echo $name . '　' . substr($row['date'],2,14)?><br>
      <?php echo $gym?><br><br>

      <?php echo nl2br($row['board'], false)?><br><br>
      <input type="submit" value="削除する">
    </form>
    <!-- <a href="./mypage.php">マイページ</a><br> -->
    <br>
  </main>
  <?php require_once('./footer.html'); ?>
  </body>
</html>