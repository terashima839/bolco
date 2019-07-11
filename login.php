<?php
// *
// http://localhost/terashima/login.php
// *
session_start();
$id = $_SESSION['id']; //ログインした場合
if(strlen($id) > 0){
  header('Location: ./mypage.php');
  exit();
}

  $name = (isset($_POST['name']) === true) ? $_POST['name']: '';
  $pass = (isset($_POST['pass']) === true) ? $_POST['pass']: '';

  // エラー変数の宣言（空を入れる）：最初に変数を定義しておかないとエラーになる
  $arr_err_msg = '';
  // *********************************************************
  // 登録がある場合のみ処理を行う
if(isset($_POST['send']) === true){
  if($name === ''){
    $arr_err_msg = 'ユーザー名を入力してください。';
  }elseif($pass === ''){
    $arr_err_msg = 'パスワードを入力してください。';
  }else{
    try{
      $dsn = 'mysql:dbname=t_db;host=localhost;charset=utf8';
      $user = 'root';
      $password = 'root';
      
      $dbh = new PDO($dsn,$user,$password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // エラーレポート,例外を投げる
      // プリペアドステートメントを作成
      // $sql = 'SELECT user_name FROM user_tb WHERE user_name=? AND user_pass=?';
      $sql = 'SELECT * FROM user_tb WHERE user_name=? AND user_pass=?';
      $stmt = $dbh->prepare($sql); // パラメータを付けて実行待ち
      $data[] = $name;
      $data[] = $pass;
      $stmt->execute($data); // ?に変数nameとpassを代入して実行

      session_start();
      foreach($stmt as $row){
        $_SESSION['id'] = $row['user_id'];
      }

      if(count($row) > 0){
        header('Location: mypage.php'); 
        exit();
      }else{
        $arr_err_msg = 'ユーザー名かパスワードが間違っています。';
      }
    }catch (Exception $e){
      echo 'ただいま障害により大変ご迷惑をおかけしております。';
      exit();
    }
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
    <div class="error"><?php echo $arr_err_msg; ?></div>
    <form  method="post" action="">
      <input type="text" name="name" id="name" value="<?php echo $name; ?>" size="40" placeholder='ユーザー名を入力してください'/><br><br>

      <input type="password" name="pass" value="<?php echo $name; ?>" size="40" placeholder='パスワードを入力してください'/><br><br>
      <input type="submit" name="send" value="ログイン"><br>
    </form>
  </main>
  <?php require_once('./footer.html'); ?>
  </body>
</html>