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
$sql = 'SELECT board,date,path,gym,user_name
        FROM board_tb b LEFT JOIN user_tb u
        ON b.user_id = u.user_id
        WHERE board_id=?';

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

// ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊

if($_SERVER["REQUEST_METHOD"] === "POST") { // POSTが送信された時
  try{
    $board = $_POST['board'];
    $gym = (strlen($_POST['gym']) > 0) ? $_POST['gym']: '不明';
    $tmp_image = (isset($_FILES['path']) === true) ? $_FILES['path']: ''; // ファイルがあれば

    if($tmp_image['error'] === 0 && $tmp_image['size'] >= 0){ // ファイルがあったら(エラー値が0かつ、サイズが0以上)
      $path = 'up_' . time() . '.jpeg';
      if(is_uploaded_file($tmp_image['tmp_name']) === true){ // ファイル名があった場合
        $image_info = getimagesize($tmp_image['tmp_name']);
        $image_mime = $image_info['mime'];

        if($tmp_image['size'] > 1234567){ // 1MBくらい
            echo 'アップロードできる画像のサイズは1MBまでです';
        }elseif(preg_match('/^image\/jpeg$/', $image_mime) === 0){
            echo 'アップロードできる画像の形式はJPEGだけです';
        }else{
          move_uploaded_file($tmp_image['tmp_name'], './upfile/' . $path ) === true;
        }
        // 
        $sql = 'UPDATE board_tb SET path=?,gym=?,board=? WHERE board_id=? AND user_id=? '; // 画像更新あり
        $stmt2 = $dbh->prepare($sql); // パラメータを付けて実行待ち
        $data2[] = $path;
        $data2[] = $gym;
        $data2[] = $board;
        $data2[] = $board_id;
        $data2[] = $id;

        $stmt2->execute($data2); // ?に変数nameを代入して実行
        $msg = '編集が完了しました。';
        header("Location: ./mypage.php?msg={$msg}");
        exit();  
      }
    }else{ // ファイルがなかったら
      $sql = 'UPDATE board_tb SET gym=?,board=? WHERE board_id=? AND user_id=? '; // 画像更新なし
      $stmt3 = $dbh->prepare($sql); // パラメータを付けて実行待ち    
      $data3[] = $gym;
      $data3[] = $board;
      $data3[] = $board_id;
      $data3[] = $id;

      $stmt3->execute($data3); // ?に変数nameを代入して実行
      $msg = '編集が完了しました。';
      header("Location: ./mypage.php?msg={$msg}");
      exit();
    }
  }catch (Exception $e){
    echo 'ただいま障害により大変ご迷惑をおかけしております2。<br>';
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
    <main>
      <br>
    <!-- 投稿修正 -->
    <form method="post" action="" enctype="multipart/form-data">

      <img src='./upfile/<?php echo $path ?>' class="detail" ><br>
      <?php echo $name . '　' . substr($row['date'],2,14)?><br>
      <?php echo $gym?><br><br>

      <input type="file" name="path"><br>
      <br>
      <input type="text" name="gym" value="<?php echo $gym ?>" size="52" placeholder='店舗名を入力してください' value="$gym"><br>

      <textarea class="textarea" name="board" cols="50" rows="10" maxlength="120" placeholder='コメントを入力してください'><?php echo $board ?></textarea><br>
      <span class="string_num">0</span>/120文字<br>

      <input type="submit" value="修正">
     </form>
    <br>

    <!-- <a href="./mypage.php">マイページ</a><br> -->
  </main>
  <?php require_once('./footer.html'); ?>
   </body>
 </html>