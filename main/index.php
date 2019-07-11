<!-- *
アクセスURL:http://localhost/terashima/main/index.php
* -->

<?php
  $dsn = 'mysql:dbname=t_db;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $dbh = new PDO($dsn,$user,$password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // エラーレポート,例外を投げる

  $sql = 'SELECT *
          FROM board_tb b LEFT JOIN user_tb u
          ON b.user_id = u.user_id
          ORDER BY date DESC';

  $stmt = $dbh->prepare($sql); // 実行待ち
  $stmt->execute();
?>
<!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>BolCo</title>
  <link rel="stylesheet" type="text/css" href="../style.css">

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
  <script type="text/javascript"> // スライドの設定
  $(function(){
    var setImg = '#photo';
    var fadeSpeed = 2500;
    var switchDelay = 3500;

    $(setImg).children('img').css({opacity:'0'});
    $(setImg + ' img:first').stop().animate({opacity:'1',zIndex:'20'},fadeSpeed);

    setInterval(function(){
        $(setImg + ' :first-child').animate({opacity:'0'},fadeSpeed).next('img').animate({opacity:'1'},fadeSpeed).end().appendTo(setImg);
    },switchDelay);
  });
  </script>

</head>
<body>
  <?php require_once('../main/header.html'); ?>
<!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
  <main>
    <div class="example">
      <div id="photo">
        <img src="./photo/p1.jpg"  height="700" alt="">
        <img src="./photo/p2.jpg"  height="700" alt="">
        <img src="./photo/p3.jpg"  height="700" alt="">
      </div>
      <span class="circle"></span>
      <div class="v1">これは</div><br>
      <div class="v2">あなただけの</div><br>
      <div class="v3">ボルダリング記録</div><br>
    </div>
<!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
    <div class="section1">
      <div class="title">BolCoへようこそ！</div>
      あなたが挑戦した壁を記録していこう。あなただけのボルダリング日記。
    </div>
<!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
    <div class="section2">
      <img src="../main/image/sports.jpeg" width="8%" height="8%" alt="" class="pic">
      <div class="column">
         登る！！<br>
         今日もあの壁に挑戦<br>
      </div>
<!--  -->
      <img src="../main/image/camera.jpeg" width="8%" height="8%" alt="" class="pic">
      <div class="column">
        撮る！！<br>
        挑戦した壁を写真に残そう<br>
      </div>
<!--  -->
      <img src="../main/image/memo.jpeg" width="8%" height="8%" alt="" class="pic">
      <div class="column">
        メモ！！<br>
        成果を記録して日記完成<br>
      </div>
    </div>
<!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
    <div class="section3">
      <div class="title">新着投稿一覧</div>
<!--  -->
      <?php
        $i = 0;
        foreach($stmt as $row): // $stmtから1行取ってくる
          if($i >= 3){
            break;
          }
          echo '<form method="get" action="" class="new">';

            echo '<a href="../board/board_detail.php?board_id=' . $row['board_id'] . '">'; // board_idのURL
              echo '<input type="hidden" name="board_id" value="'. $row['board_id'].'">'; // board_idを飛ばすための処理
              echo '<img src=../board/upfile/' . $row['path'] . ' class="pic">';
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
            $i++;
          echo '</form>';
        endforeach;
      ?>
    <!-- </div> -->
<!--  -->
        <div class="more"><a href="../board/board_seach.php">もっと見る</a></div>
      <!-- </div> -->
    </div>
<!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
    <div class="section4">
      <div class="title">ジム情報</div>
<!--  -->
      <div class="post">
        <div class="new">
          <img src="../main/image/gym.jpeg" width="100%" height="100%" alt="" class="pic">
          <div class="user_name">B-pump</div>
        </div>
        <div class="new">
          <img src="../main/image/gym.jpeg" width="100%" height="100%" alt="" class="pic">
          <div class="user_name">B-pump</div>
        </div>
        <div class="new">
          <img src="../main/image/gym.jpeg" width="100%" height="100%" alt="" class="pic">
          <div class="user_name">B-pump</div>
        </div>

        <div class="more"><a href="#">もっと見る</a></div>
      </div>
<!--  -->
    </div>
  </main>
  <!-- ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ -->
  <?php require_once('../main/footer.html'); ?>
</body>
</html>
