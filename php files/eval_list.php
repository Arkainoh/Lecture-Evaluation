<html>
  <head>
    <meta charset="utf-8">
    <title>수강평가 목록</title>
    <!--<link type="text/css" rel="stylesheet" href="불러올CSS파일명.css"/>-->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <style>
  td {
    text-align: center;
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

    $loginuser = $_SESSION['user_nickname'];

      if($loginuser=="admin")
        echo "<a href=\"/admin_page.php\">관리자 페이지로</a>";
      
		$usernn = $_GET['username'];

    $getuseridquery = "SELECT userid FROM user_account where nickname='".$usernn."';";
    $getuserid = pg_query($getuseridquery) or die('Query failed: '.pg_last_error());    
    $temp = pg_fetch_assoc($getuserid,0);
    $userid = "$temp[userid]";
?>

<div class="container" style="color:white;background-color:skyblue;width:70%;margin-top:5%;text-align:center">
  <p> Welcome! </p>
  <h1> <strong><?php echo $_SESSION['user_nickname']; ?>님, 환영합니다.</strong></h1>
  <form action="/eval_list.php" method="GET">
    <input type="hidden" name = "username" value=<?php echo "\"$loginuser\"";?>>
    <button type="submit" style="cursor:pointer;color:black">내가 작성한 평가 보러가기</button>
  </form>
  <a href="/mainpage.php"> 강의검색 </a><br>
  <a href="/ranking.php"> 명예의 전당 </a><br>
  <a href="/logout.php"> 로그아웃 </a>
</div>
<div class="container" style="background-color:lightgray;width:70%;padding-top:10px;padding-bottom:10px;text-align:center"> 
<h3 style="color:white"><strong><?php echo "$usernn";?>님의 수강평가 목록</strong></h3>

<?php

    // 추천 수 기준으로 정렬 dept_name 겹치는 문제 해결 위해 course.dept_name으로 명시
    $evalquery = "with T as (select courseid, sectionid, year, semester, title, type, course.dept_name, lastname, firstname, content, rating, likes from (teaches natural join instructor inner join course using (courseid)) natural join evaluation where userid='$userid' ) select * from T order by likes desc;";

    $evalresult = pg_query($evalquery) or die('Query failed: '.pg_last_error());
    echo "총 ".pg_num_rows($evalresult)."개";
      for($i = 0; $i < pg_num_rows($evalresult); $i ++) {
        $eval = pg_fetch_assoc($evalresult,$i);
?>
<div class="panel panel-primary">
    <div class="panel-heading post-title" style="cursor:pointer">
      <h3 class="panel-title"><?php echo "$eval[year]/$eval[semester]<br><strong>[$eval[courseid]-$eval[sectionid]] $eval[title]</strong><br>$eval[lastname] $eval[firstname] 교수님"; ?></h3>
    </div>
    <div id = <?php echo "\"post_content_$i\""; ?> class="panel-body post-content">
      <div class="well">
      <?php echo "[평점: $eval[rating]]"; ?><br>
        <?php echo "$eval[content]"; ?><br>
          <br>추천 수<p id=<?php echo "\"likes_$i\""; ?>><?php echo "$eval[likes]"; ?></p>

          <!-- 추천하기 구현을 위한 hidden input -->
            <input id=<?php echo "\"comment_usernn_$i\""; ?> type="hidden" value=<?php echo "\"$usernn\""; ?>>
            <input id=<?php echo "\"comment_courseid_$i\""; ?> type="hidden" value=<?php echo "\"$eval[courseid]\""; ?>>
          <input id=<?php echo "\"comment_sectionid_$i\""; ?> type="hidden" value=<?php echo "\"$eval[sectionid]\""; ?>>
          <input id=<?php echo "\"comment_semester_$i\""; ?> type="hidden" value=<?php echo "\"$eval[semester]\""; ?>>
          <input id=<?php echo "\"comment_year_$i\""; ?> type="hidden" value=<?php echo "\"$eval[year]\""; ?>>
          <input id=<?php echo "\"comment_likes_$i\""; ?> type="hidden" value=<?php echo "\"$eval[likes]\""; ?>>

            <a style="cursor:pointer" onclick="likeClicked(<?php echo "'$i'"; ?>)">추천하기</a>
             <?php
            //render current session's components
            if($loginuser == $usernn) {
            ?>
            <br>
            <button style="cursor:pointer;margin-bottom:5px" onclick="modifySlider(<?php echo "'$i'"; ?>)">수정</button> 
            <form action="/comment_delete.php" method="POST">
              <input name="commentusername" type="hidden" value=<?php echo "\"$usernn\""; ?>>
              <input name="commentcourseid" type="hidden" value=<?php echo "\"$eval[courseid]\""; ?>>
          <input name="commentsectionid" type="hidden" value=<?php echo "\"$eval[sectionid]\""; ?>>
          <input name="commentsemester" type="hidden" value=<?php echo "\"$eval[semester]\""; ?>>
          <input name="commentyear" type="hidden" value=<?php echo "\"$eval[year]\""; ?>>
              
              <button type="submit" style="cursor:pointer">삭제</button></form>
              <hr>
            
            <div id=<?php echo "\"modifyboard_$i\"";?> class="modifyboard">
              <h4><strong>수정하기</strong></h4><br>
              <form action="/comment_modify.php" method="POST">
              <input name="commentusername" type="hidden" value=<?php echo "\"$usernn\""; ?>>
              <input name="commentcourseid" type="hidden" value=<?php echo "\"$eval[courseid]\""; ?>>
          <input name="commentsectionid" type="hidden" value=<?php echo "\"$eval[sectionid]\""; ?>>
          <input name="commentsemester" type="hidden" value=<?php echo "\"$eval[semester]\""; ?>>
          <input name="commentyear" type="hidden" value=<?php echo "\"$eval[year]\""; ?>>
                <div class="row">
                <div class="col-md-5">
                
                </div>
            <div class="col-md-2">
              <label for="ratingsmodify">평점</label> 
              <input type="range" name="commentrating" id="ratingsmodify" min="0" max="5" value=<?php echo "\"$eval[rating]\""; ?>>
            </div>
            
            <div class="col-md-5"></div>
            </div>
            <br>
            <label for=<?php echo "\"modify_content_$i\""; ?>>내용</label>
                <input name="commentcontent" type="text" class="form-control" id=<?php echo "\"modify_content_$i\""; ?> placeholder="Comment" value=<?php echo "\"$eval[content]\"";?>> 
                <br>
            <div class="row">
                <div class="col-md-5">
                
                </div>
            <div class="col-md-2">
              <button type="submit" style="margin-top:10px" class="btn btn-lg btn-primary comment-submit" value=<?php echo "\"$i\""; ?>>수정</button>
            </div>
            <div class="col-md-5"></div>
            </div>
          </form>
        </div>
        <?php
            } // if
        ?>
      </div>
    </div>
</div>

<?php
} //for i
?>
</div>
<div class="container" style="color:white;background-color:skyblue;width:70%;text-align:center">
  <p> DB 텀프로젝트 2012130888 김인호 </p>
</div>


<?php 
} pg_close($dbconn);

?>
<script>
$(".modifyboard").hide();
function modifySlider(commentid) {
          $("#modifyboard_"+commentid).slideToggle();
        }
function likeClicked(commentid) {
          loginUser = <?php echo "\"$loginuser\";";?>
          cusername = $("#comment_usernn_"+commentid).val();
          ccourseid = $("#comment_courseid_"+commentid).val();
          csectionid = $("#comment_sectionid_"+commentid).val();
          csemester = $("#comment_semester_"+commentid).val();
          cyear = $("#comment_year_"+commentid).val();
          clikes = $("#comment_likes_"+commentid).val(); //한 페이지에서 여러번 클릭해서 추천하는 것을 방지
          newLike = parseInt(clikes)+1;
          if(cusername == loginUser) {
            alert('자신이 작성한 수강평가는 추천할 수 없습니다.');
          } else {
            $.ajax({
              method: "POST",
              url: "/comment_like.php",
              data: { commentusername: cusername, commentcourseid: ccourseid,commentsectionid:csectionid,commentyear:cyear,commentsemester:csemester, commentlikes: newLike },
              success: function(){//.comment-list 
               $("#likes_"+commentid).text(newLike);
              },
              error: function(){alert( "error!!" );}
            })
            }
        }
</script>

</body>
</html>