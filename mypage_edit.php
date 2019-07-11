<?php
// *
// http://localhost/terashima/login/mypage_edit.php
// *
session_start();
$id = $_SESSION['id'];
$msg = '';

$dsn = 'mysql:dbname=t_db;host=localhost;charset=utf8';
$user = 'root';
$password = 'root';
$dbh = new PDO($dsn,$user,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // エラーレポート,例外を投げる

// 
if($_SERVER["REQUEST_METHOD"] !== "POST") { // POSTが送信されなかった時
  try{
    $sql = 'SELECT prof FROM user_tb WHERE user_id=? ';
    $stmt = $dbh->prepare($sql); // パラメータを付けて実行待ち
    $data[] = $id;
    $stmt->execute($data); // ?に変数nameを代入して実行

    foreach ($stmt as $row) {
        $prof = $row['prof'];
    }

  }catch (Exception $e){
    echo 'ただいま障害により大変ご迷惑をおかけしております。<br>';
    exit($e -> getMessage());
  }
}else{

// if($_SERVER["REQUEST_METHOD"] === "POST") { // POSTが送信された時
  try{
    $prof = $_POST['prof'];
    // 
    $sql = 'UPDATE user_tb SET prof=? WHERE user_id=?';
    $stmt = $dbh->prepare($sql); // パラメータを付けて実行待ち
    $data2[] = $prof;
    $data2[] = $id;
    $stmt->execute($data2); // ?に変数nameを代入して実行

    $msg = '編集が完了しました。';
    header("Location: ./mypage.php?msg={$msg}");
    exit();


  }catch (Exception $e){
    echo 'ただいま障害により大変ご迷惑をおかけしております。<br>';
    exit($e -> getMessage());
  }
}

?>
 <!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
 <html>
  <head>
    <meta charset="utf-8">
    <title>BolCo</title>
    <link rel="stylesheet" href="./style.css">
    <script src="./main.js" defer></script> <!-- 文字数カウントの為 -->
  </head>
  <body>
  <?php require_once('./header.html'); ?>
<!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
  <main>
    <p>プロフィール</p><br>
    <form method="post" action="">
      <textarea name="prof" class="textarea" cols="50" rows="10" maxlength="120" placeholder="プロフィールを入力してください"><?php echo $prof ?></textarea><br>
      <span class="string_num">0</span>/120文字<br>
      <input type="submit" value="送信">
    </form>

    <!-- <a href="mypage.php">マイページ</a><br> -->
  </main>
  <?php require_once('./footer.html'); ?>

   </body>
 </html>