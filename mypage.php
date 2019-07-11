
<?php
// ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊

session_start();
$id = $_SESSION['id']; //ログインした場合
$msg = (isset($_GET['msg']) === true) ? $_GET['msg']: ''; 

$user_id = (isset($_GET['user_id']) === true) ? $_GET['user_id']: ''; // boradからの場合

// 
$dsn = 'mysql:dbname=t_db;host=localhost;charset=utf8';
$user = 'root';
$password = 'root';

$dbh = new PDO($dsn,$user,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // エラーレポート,例外を投げる
// ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊

// プリペアドステートメントを作成
$sql = 'SELECT * FROM user_tb WHERE user_id=? '; // user_nameを連結する必要あり
$stmt = $dbh->prepare($sql); // 実行待ち
if($user_id===''){
  $data[] = $id; //sessionあり
}else{
  $data[] = $user_id; //sessionなし
}
$stmt->execute($data); // ?に変数nameを代入して実行
foreach ($stmt as $row) {
  $name = $row['user_name'];
  $prof = $row['prof'];
}

// プリペアドステートメントを作成
$sql = "SELECT * FROM board_tb WHERE user_id=? ORDER BY date DESC "; // 投稿内容
$stmt2 = $dbh->prepare($sql); // 実行待ち
if($user_id===''){
  $board[] = $id; //sessionあり
}else{
  $board[] = $user_id; //sessionなし
}
$stmt2->execute($board);


$_SESSION['name'] = $name;

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
    <?php echo $msg ?>

    <h2><?php echo $name ?>さんのマイページ</h2>
    <p>プロフィール</p>
    <div class="prof">
      <?php echo nl2br($prof) ?><br>
      <?php if($user_id===''){ ?> <!-- ログインの場合 -->

      <a href="mypage_edit.php" class="edit">編集</a>
    </div>
<!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
  <!-- <div class="board"> -->
    <p>投稿</p>
    <div class="board_icon">
      <a href="./board.php">
      <img src="./image/icon1.jpg"><br>投稿</a><br>
    </div>
    <div class="board_icon">
      <a href="./board_seach.php">
      <img src="./image/icon2.jpg"><br>みんなの投稿一覧</a><br>
    </div>
  <!-- </div> -->
      <?php } ?> <!-- ログインの場合 -->
    <p><?php echo $name ?>さんの投稿一覧</p>
    <?php
    foreach($stmt2 as $row): // $stmtから1行取ってくる
      echo '<form method="get" action="" class="new">';
        echo '<a href="./board_detail.php?board_id=' . $row['board_id'] . '">'; // board_idのURL
          echo '<input type="hidden" name="board_id" value="'.$row['board_id'].'">'; // board_idを飛ばすための処理
          echo '<img src=./upfile/' . $row['path'] . ' class="pic">'; 
          echo '<div class="name">' . $name . '　' . substr($row['date'],2,14) . '</div>';
          echo '<div class="gym">' . $row['gym'] . '</div>';
          
          $text = $row['board'];
          $limit = 37; // 表示制限

          if(mb_strlen($text) > $limit) {
            $title = mb_substr($text,0,$limit);
            echo $title. '･･･' ; // 字数を超えた場合、変換する
          } else {
            echo $text; // 字数を超えなかった場合、そのまま
          }
        echo '</a>';
      echo '</form>';
    endforeach;

    if(isset($row['board_id']) !== true){ //投稿の確認
      echo '投稿はまだありません';
    }
    ?>
    <br><br>
<!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
    <?php if($user_id===''){ ?> <!-- ログインの場合 -->
    <a href="logout.php">ログアウト</a><br><br>
    <?php } ?>

    
  </main>
  <?php require_once('./footer.html'); ?>
  </body>
</html>