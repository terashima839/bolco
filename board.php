<?php
// *
// http://localhost/terashima/board.php
// *
session_start();
$id = $_SESSION['id'];
$name = $_SESSION['name'];
$msg = '';

$arr_err_msg = [
  'image' => null,
  'board' => null
];

$tmp_image = (isset($_FILES['image']) === true) ? $_FILES['image']: '';
$board = (isset($_POST['board']) === true) ? $_POST['board']: '';
$gym = (isset($_POST['gym']) === true) ? $_POST['gym']: '';

$dsn = 'mysql:dbname=t_db;host=localhost;charset=utf8';
$user = 'root';
$password = 'root';
$dbh = new PDO($dsn,$user,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // エラーレポート,例外を投げる

// ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
if($_SERVER["REQUEST_METHOD"] === "POST") { // POSTが送信された時

  if($tmp_image['error'] !== 0 || $tmp_image['size'] === 0){ // エラー値が0ではなく、サイズが0だったら
    $arr_err_msg['image'] = 'ファイルを添付してください。';
  }
  if($board === '') $arr_err_msg['board'] = 'コメントを入力入してください。';  
  
  if(isset($arr_err_msg['image']) === true || isset($arr_err_msg['board']) === true){
      $msg = '投稿できませんでした。';
  }else{

    $gym = (strlen($_POST['gym']) > 0) ? $_POST['gym']: '不明';

    try{ // ファイルチェック
      $path = 'up_' . time() . '.jpg';
      if(is_uploaded_file($tmp_image['tmp_name']) === true){
          $image_info = getimagesize($tmp_image['tmp_name']);
          $image_mime = $image_info['mime'];

          if($tmp_image['size'] > 1234567){ // 1MBくらい
              echo 'アップロードできる画像のサイズは1MBまでです';
          }elseif(preg_match('/^image\/jpeg$/', $image_mime) === 0){
              echo 'アップロードできる画像の形式はJPEGだけです';
          }else{
            move_uploaded_file($tmp_image['tmp_name'], './upfile/' . $path ) === true;
          }
      }

      $sql = 'INSERT INTO board_tb(user_id, board, date, path,gym) VALUES (:user_id,:board,now(),:path,:gym)';
      $stmt = $dbh->prepare($sql); // パラメータを付けて実行待ち
      $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':board', $board, PDO::PARAM_STR);
      $stmt->bindParam(':path', $path, PDO::PARAM_STR);
      $stmt->bindParam(':gym', $gym, PDO::PARAM_STR);

      $stmt->execute(); // 実行

      $msg = '投稿が完了しました。';
      header("Location: ./mypage.php?msg={$msg}");
      exit();

    }catch (Exception $e){
      echo 'ただいま障害により大変ご迷惑をおかけしております。<br>';
      exit($e -> getMessage());
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
    <script src="./main.js" defer></script> <!-- 文字数カウントの為 -->
  </head>
  <body>
  <?php require_once('./header.html'); ?>
<!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
  <main>
    <br>
    <?php echo $msg ?>
    <form method="post" action="" enctype="multipart/form-data">

      <div class="error"><?php echo $arr_err_msg['image'] ?></div> <!-- エラー文 -->
      <input type="file" name="image"><br>
      <input type="text" name="gym" value="<?php echo $gym ?>" size="52" placeholder='店舗名を入力してください' value="$gym"><br>
      
      <div class="error"><?php echo $arr_err_msg['board'] ?></div> <!-- エラー文 -->
      <textarea name="board" class="textarea" cols="50" rows="10" maxlength="120" placeholder='コメントを入力してください'><?php echo $board ?></textarea><br>
      <span class="string_num">0</span>/120文字<br>

      <input type="submit" value="投稿">
    </form>

    <!-- <a href="./mypage.php">マイページ</a><br> -->
  </main>
  <?php require_once('./footer.html'); ?>
  </body>
</html>