<?php
$name = isset($_POST['name']) === true? $_POST['name']: '';
$pass = isset($_POST['pass']) === true? $_POST['pass']: '';
$pass_re = isset($_POST['pass_re']) === true? $_POST['pass_re']: '';


if(isset($_POST['send']) === true){
  if($name === ''){ 
    $err_msg['name'] = 'ユーザー名が入力されていません。';
  }

  if($pass === '' || $pass_re === ''){
    $err_msg['pass'] = 'パスワードを入力してください。';
  }elseif($pass !== $pass_re ){
    $err_msg['pass_re'] = 'パスワードが一致していません。';
  }

  if(isset($err_msg) !== true){
    try{
      $dsn = 'mysql:dbname=t_db;host=localhost;charset=utf8';
      $user = 'root';
      $password = 'root';
      $dbh = new PDO($dsn,$user,$password);

      $sql = "INSERT INTO user_tb(user_name,user_pass) VALUES (?,?)";
      $stmt=$dbh->prepare($sql);
      $data[] = $name;
      $data[] = $pass;

      $stmt->execute($data);

      $dbh=null;

      header("location:user_add_done.php");
      exit();
    }catch(Exception $e){
      echo 'ただいま障害により大変ご迷惑をおかけしております。';
      exit();
    }
  }
}

?>

<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>BolCo</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
  </head>
  <body>
    <?php require_once('./header.html'); ?>
    <main>
      <br>
      <!-- <form method="post" action=""> -->
      <form method="post" action="">

        <!-- ユーザー名： -->
          <input type="text" name="name"size="60" placeholder='ユーザー名を入力してください' value="<?php if(!empty($_POST['name'])) echo $_POST['name'] ?>"><br>
          <div class="error"><?php if(!empty($err_msg['name'])) echo $err_msg['name']; ?></div><br>
        
        <!-- パスワード： -->
        <input type="password" name="pass" size="60" placeholder='半角英数字でパスワードを入力してください' value="<?php if(!empty($_POST['pass'])) echo $_POST['pass'] ?>"><br>
        <div class="error">  <?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?></div><br>

        <!-- パスワード： -->
        <input type="password" name="pass_re" size="60" placeholder='半角英数字でパスワードを再入力してください' value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re'] ?>"><br>
        <div class="error">  <?php if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re']; ?></div><br>

        <input type="submit" value="登録確認" name="send"><br>
      </form>
    </main>
    <?php require_once('./footer.html'); ?>
  </body>
</html>

