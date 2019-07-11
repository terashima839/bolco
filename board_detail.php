<?php
// *
// http://localhost/terashima/login/mypage_edit.php
// *
session_start();
$id = (isset($_SESSION['id']) === true) ? $_SESSION['id']: '';
$comment = (isset($_POST['comment']) === true) ? $_POST['comment']: '';
$board_id = $_GET['board_id'];

$_SESSION['board_id'] = $board_id;
$msg = '';

$dsn = 'mysql:dbname=t_db;host=localhost;charset=utf8';
$user = 'root';
$password = 'root';
$dbh = new PDO($dsn,$user,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // エラーレポート,例外を投げる
// ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
// 投稿詳細表示
$sql = 'SELECT * 
        FROM board_tb b LEFT JOIN user_tb u
        ON b.user_id = u.user_id
        WHERE board_id=?';

$stmt = $dbh->prepare($sql); // 実行待ち
$board[] = $board_id;
$stmt->execute($board);

// ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
// コメントの登録
if($comment !== ''){ // コメントがあった場合
  // $sql = 'INSERT INTO board_tb(user_id, board, date, path,gym) VALUES (:user_id,:board,now(),:path,:gym)';
  try{
    $sql2 = 'INSERT INTO comment_tb(board_id, user_id, comment_date, comment) 
             VALUES (:board_id,:user_id,now(),:comment)';
    $stmt2 = $dbh->prepare($sql2); // パラメータを付けて実行待ち
    $stmt2->bindParam(':board_id', $board_id, PDO::PARAM_STR);
    $stmt2->bindParam(':user_id', $id, PDO::PARAM_INT);
    $stmt2->bindParam(':comment', $comment, PDO::PARAM_STR);
  
    $stmt2->execute(); // 実行
  
    $msg = '投稿が完了しました。';
    // header("Location: ./mypage.php?msg={$msg}");
    // exit();

  }catch (Exception $e){
    echo 'ただいま障害により大変ご迷惑をおかけしております。<br>';
    exit($e -> getMessage());
  }
}
// ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
// コメントの表示
// $sql3 = 'SELECT *
$sql3 = 'SELECT user_name,comment_date,comment
         FROM comment_tb c LEFT JOIN user_tb u
         ON c.user_id = u.user_id
         WHERE board_id=?
         ORDER BY comment_date DESC';

$stmt3 = $dbh->prepare($sql3); // 実行待ち

// $board_id = array(); // 配列にする
$board_com[] = $board_id;
$stmt3->execute($board_com);

// ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊


//
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
  <?php
    foreach($stmt as $row): // $stmtから1行取ってくる
      echo '<img src=./upfile/' . $row['path'] . ' class="detail" ><br>';
      
      echo '<a href="./mypage.php?user_id=' . $row['user_id'] . '">'; // mypageへのリンク
      echo '<input type="hidden" name="user_id" value="'. $row['user_id'].'">'; // user_idを飛ばすための処理

      echo '<div class="board">';
      echo '<div class="name">'.$row['user_name'] . '　' . substr($row['date'],2,14) . '</div></a>'; // mypageへのリンク

      echo $row['gym'] . '<br>';
      echo nl2br($row['board'], false); // nl2brで改行を修正
      echo '</div>';
    endforeach;
    
  ?>
  <br>
  <?php
    if($_SESSION['id'] !== null){ // SESSION_idがあれば表示
  ?>
    <form action="board_edit.php">
      <input type="submit" value="編集">
    </form>
    <form action="board_delete.php">
      <input type="submit" value="削除">
    </form>
  
    <?php echo $msg ?><br>
    <br>コメントする<br>
    <form action="" method="POST"> 
      <textarea class="comment" name="comment" cols="50" rows="8" maxlength="60" placeholder="コメントを入力してください"></textarea><br>
      <span class="string_num">0</span>/60文字<br>
      <input type="submit" value="コメントする">
    </form>
  <?php } ?>

  <br>

  <?php
    foreach($stmt3 as $row): // コメントの表示
      echo '<div class="user_comment">';
        echo '<div class="">'.$row['user_name'] . '　' . substr($row['comment_date'],2,14) . '</div></a>';
        echo nl2br($row['comment'], false) . '<br>'; // nl2brで改行を修正
      echo '</div>';
    endforeach;
    if(isset($row['comment']) !== true){ //投稿の確認
      echo 'コメントはまだありません';
    }

  ?>
  <br>
  <br>
  </main>
  <?php require_once('./footer.html'); ?>
   </body>
 </html>