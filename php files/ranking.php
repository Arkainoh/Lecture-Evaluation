<html>
  <head>
    <meta charset="utf-8">
    <title>명예의 전당</title>
    <!--<link type="text/css" rel="stylesheet" href="불러올CSS파일명.css"/>-->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <style>
  
  table,td,th {
    text-align:center;
  }
  
  </style>

  </head>
  
  <body>
<?php

	session_start();
  
	if(!isset($_SESSION['user_nickname']) ) {
		echo "<script> alert('로그인이 필요합니다.');
		document.location.href='/login.php/'
		</script>";
	} else {
		
    $dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
    or die('Could not connect: ' . pg_last_error());
    $usernn = $_SESSION['user_nickname'];
    $getuseridquery = "SELECT userid FROM user_account where nickname='".$usernn."';";
    $getuserid = pg_query($getuseridquery) or die('Query failed: '.pg_last_error());    
    $temp = pg_fetch_assoc($getuserid,0);
    $userid = "$temp[userid]";
?>

<div class="container" style="color:white;background-color:skyblue;width:70%;margin-top:5%;text-align:center">
  <p> Welcome! </p>
  <h1> <strong><?php echo $_SESSION['user_nickname']; ?>님, 환영합니다.</strong></h1>
  <a href="/changeuserinfo.php"> 개인정보 수정 </a><br>
  <a href="/logout.php"> 로그아웃 </a><br>
  <form action="/eval_list.php" method="GET">
    <input type="hidden" name = "username" value=<?php echo "\"$usernn\"";?>>
    <button type="submit" style="cursor:pointer;color:black">내가 작성한 평가 보러가기</button>
  </form>
  <a href="/mainpage.php"> 강의검색 </a><br>
  <a href="/ranking.php"> 명예의 전당 </a><br>
  <a href="/signout.php"> 회원탈퇴 </a>
</div>
<div class="container" style="background-color:lightgray;width:70%;padding-top:10px;padding-bottom:10px;text-align:center"> 
<h3 style="color:white"><strong>명예의 전당</strong></h3>
<?php

    // 추천 수 기준으로 정렬 dept_name 겹치는 문제 해결 위해 course.dept_name으로 명시
    $rankingquery = "select nickname, sum(likes), count(*) from evaluation natural join user_account group by userid, nickname";

    $s_sum = "order by sum desc;";
    $s_count = "order by count desc;";

    $likeresult = pg_query($rankingquery." ".$s_sum) or die('Query failed: '.pg_last_error());
    $total_num_likes = pg_num_rows($likeresult);
    
    $countresult = pg_query($rankingquery." ".$s_count) or die('Query failed: '.pg_last_error());
    $total_num_counts = pg_num_rows($countresult);


    if($total_num_likes > 10) $total_num_likes = 10; //최대 10개까지만 보여준다.
    if($total_num_counts >10) $total_num_counts = 10;
    ?>

<div class="well">
<h4><strong>누적 추천 수 랭킹</strong></h4>
<table class="table table-striped">
<tr>
<th>순위</th><th>닉네임</th><th>추천 수</th>
</tr>
<?php
    for($i = 0; $i < $total_num_likes; $i ++) {
        $tuple_like = pg_fetch_assoc($likeresult,$i);
?>
<tr>
<td><?php echo $i+1;?></td><td>
  <form action="/eval_list.php" method="GET"> <!--닉네임을 클릭하면 작성자의 수강평가 목록을 불러온다-->
    <input type="hidden" name="username" value=<?php echo "$tuple_like[nickname]"; ?>>
    <button type="submit" style="cursor:pointer"><?php echo "$tuple_like[nickname]"; ?></button>
  </form>
</td><td><?php echo $tuple_like['sum'];?></td>
</tr>    
<?php
      } //for i
?>

</table>
</div>

<div class="well">
<h4><strong>수강평가 작성 수 랭킹</strong></h4>
<table class="table table-striped">
<tr>
<th>순위</th><th>닉네임</th><th>수강평가 수</th>
</tr>
<?php
    for($i = 0; $i < $total_num_counts; $i ++) {
        $tuple_count = pg_fetch_assoc($countresult,$i);
?>
<tr>
<td><?php echo $i+1;?></td><td>
  <form action="/eval_list.php" method="GET"> <!--닉네임을 클릭하면 작성자의 수강평가 목록을 불러온다-->
    <input type="hidden" name="username" value=<?php echo "$tuple_count[nickname]"; ?>>
    <button type="submit" style="cursor:pointer"><?php echo "$tuple_count[nickname]"; ?></button>
  </form>
</td><td><?php echo $tuple_count['count']?></td>
</tr>
<?php
      } //for i
?>
</table>
</div>



</div>
<div class="container" style="color:white;background-color:skyblue;width:70%;text-align:center">
  <p> DB 텀프로젝트 2012130888 김인호 </p>
</div>

<?php 
} pg_close($dbconn);
?>
</body>
</html>