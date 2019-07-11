<!-- *
アクセスURL:http://localhost/terashima/board_seach.php
* -->
<?php
  session_start();
  $id = $_SESSION['id'];

  // define('MAX','3'); // 1ページの投稿の表示数
  // $stmt_num = count($stmt); // トータルデータ数
  // $max_page = ceil($stmt_num / MAX); // トータルページ数※ceilは小数点を切り捨てる関数

  // if(!isset($_GET['page_id'])){ // $_GET['page_id'] はURLに渡された現在のページ数
  //   $now = 1; // 設定されてない場合は1ページ目にする
  // }else{
  //   $now = $_GET['page_id'];
  // }
  // $start_no = ($now - 1) * MAX; // 配列の何番目から取得すればよいか

  // // array_sliceは、配列の何番目($start_no)から何番目(MAX)まで切り取る関数
  // $disp_data = array_slice($stmt, $start_no, MAX, true);

  // foreach($disp_data as $val){ // データ表示
  //   echo $val['book_kind']. '　'.$val['book_name']. '<br />';
  // }

  // for($i = 1; $i <= $max_page; $i++){ // 最大ページ数分リンクを作成
  //   if ($i == $now) { // 現在表示中のページ数の場合はリンクを貼らない
  //       echo $now. '　'; 
  //   } else {
  //       echo '<a href=\'/terashima/pageboard/board_seach.php?page_id='. $i. '\')>'. $i. '</a>'. '　';
  //   }
  // }



  // データベースに接続するための設定
  $dsn = 'mysql:host=localhost;dbname=t_db;charset=utf8';
  $user = 'root';
  $password = 'root';
  $dbh = new PDO($dsn,$user,$password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // エラーレポート,例外を投げる

// ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
if($_SERVER["REQUEST_METHOD"] === "POST") { // POSTが送信された時
  try{
    $seach_gym = $_POST['seach_gym'];

    $sql = "SELECT *
            FROM board_tb b LEFT JOIN user_tb u
            ON b.user_id = u.user_id
            WHERE gym
            LIKE '%$seach_gym%'
            ORDER BY date DESC";

    $stmt = $dbh->prepare($sql); // 実行待ち
    $stmt->execute();
  }catch (Exception $e){
    echo 'ただいま障害により大変ご迷惑をおかけしております。<br>';
    exit($e -> getMessage());
  }
}else{ // POSTが送信されなかった時
  try{
    $sql = 'SELECT *
            FROM board_tb b LEFT JOIN user_tb u
            ON b.user_id = u.user_id
            ORDER BY date DESC';

    $stmt = $dbh->prepare($sql); // 実行待ち
    $stmt->execute();
  }catch (Exception $e){
    echo 'ただいま障害により大変ご迷惑をおかけしております。<br>';
    exit($e -> getMessage());
  }
}

// ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊

?>
<html>
  <head>
    <meta charset="utf-8">
    <title>BolCo</title>
    <link rel="stylesheet" href="./style.css">
  </head>
  <body>
    <?php require_once('./header.html'); ?>
    <main>
<!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
    <p>みんなの投稿一覧</p>
    <div class="post">
      <?php
          foreach($stmt as $row): // $stmtから1行取ってくる
            echo '<form method="get" action="" class="new">';

            echo '<a href="./board_detail.php?board_id=' . $row['board_id'] . '">'; // board_idのURL
              echo '<input type="hidden" name="board_id" value="'. $row['board_id'].'">'; // board_idを飛ばすための処理
              echo '<img src=./upfile/' . $row['path'] . ' class="pic">'; 
              echo '<div class="name">' . $row['user_name'] . '　' . substr($row['date'],2,14) . '</div>';
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
          echo '<br>お探しの店舗はありませんでした<br><br>';
        }
    
      ?>
    </div>
  </main>
  <?php require_once('./footer.html'); ?>
  </body>
</html>