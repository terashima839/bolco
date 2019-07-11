<?php

$yes = (isset($_POST['yes']) === true) ? $_POST['yes']: '';
$no = (isset($_POST['no']) === true) ? $_POST['no']: '';
session_start();

if($_SERVER["REQUEST_METHOD"] === "POST") { // POSTが送信された時
  if($_POST['yes'] === 'はい'){
    session_start();

    $_SESSION = array();
    session_destroy(); // セッションを破棄

    // session_unset();
    header('Location: ./login.php');
    exit();
  }elseif($_POST['no'] === 'いいえ'){
    session_start();
    
    header('Location: ./mypage.php');
    exit();
  }
}

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
    <main>
    <br>
    ログアウトしてよろしいですか？<br>
  
    <form method="post" action="">
      <input type="submit" name="yes" value="はい">
      <input type="submit" name="no" value="いいえ">
    </form>
    <br>
  
    <!-- <a href="mypage.php">マイページ</a><br> -->
  </main>
  <?php require_once('./footer.html'); ?>
  </body>
</html>
