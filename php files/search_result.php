<html>
	<head>
		<meta charset="utf-8">
		<title>검색 결과</title>
		<!--<link type="text/css" rel="stylesheet" href="불러올CSS파일명.css"/>-->
		<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	</head>
	
	<body>

<?php

	session_start();

	if(!isset($_SESSION['user_nickname']) ) {
		
	echo "<script> alert('로그인이 필요합니다.');
		document.location.href='/logout.php/'
		</script>";

	} else {

	$usernn = $_SESSION['user_nickname'];
			if($usernn=="admin")
				echo "<a href=\"/admin_page.php\">관리자 페이지로</a>";
	
	$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
	or die('Could not connect: ' . pg_last_error());
	
	$useryear = $_GET['inputYear'];
	$userterm = $_GET['term'];
	$usercoursetype = $_GET['coursetype'];
	$userdept = $_GET['dept'];
	$userrating = $_GET['rating'];
	$userinput = $_GET['searchbar'];

	//condition strings
	$s_year = "";
	$s_term = "";
	$s_ctype = "";
	$s_dept = "";
	$s_rating = "";
	$s_input = "";

	if($useryear != "")	$s_year = "and (year = ". $useryear .")";

	if($userterm != "noneval") $s_term = "and (semester = '".$userterm."')";

	if($usercoursetype != "noneval") $s_ctype = "and (type = '".$usercoursetype."')";

	if($userdept != "noneval") $s_dept = "and (dept_name = '".$userdept."')"; 

	$s_rating = "where (avg>=".$userrating.")";

	if($userinput != "") {
		$s_input = "and (courseid like '%".$userinput."%' or title like '%".$userinput."%' or (lastname = substring('".$userinput."' from 1 for 1) and firstname = substring('".$userinput."' from '..$')) or (lastname = substring('".$userinput."' from 1 for 2) and firstname = substring('".$userinput."' from '..$')) or (lastname = substring('".$userinput."' from 1 for 1) and firstname = substring('".$userinput."' from '...$')) or (lastname = substring('".$userinput."' from 1 for 2) and firstname = substring('".$userinput."' from '...$')) or firstname=substring('".$userinput."' from '..$') or firstname=substring('".$userinput."' from '...$'))";
	}

	
		
		//아직 수강평가가 하나도 작성되지 않은 강의도 고려해주기 위해서 union 사용하였고 0, 0 사용
		//dept_name이 여러 relation에서 다른 의미로서 나타나는 문제를 해결해주기 위해 course.dept_name이라고 명시해주었음
		$myquery1 = "with T as ((select courseid, sectionid, year, semester, title, type, course.dept_name, lastname, firstname, avg(rating), count(rating), credits from (teaches natural join instructor inner join course using (courseid)) natural join evaluation group by (course.dept_name, courseid, sectionid, year, semester, title, type, lastname, firstname, credits)) union (select courseid, sectionid, year, semester, title, type, course.dept_name, lastname, firstname, 0, 0, credits from (instructor natural join teaches inner join course using(courseid)) natural left outer join evaluation where rating is null group by (course.dept_name, courseid, sectionid, year, semester, title, type, lastname, firstname, credits))) select * from T";

	$myquery1 = "".$myquery1." ".$s_rating;
	$myquery1 = "".$myquery1." ".$s_dept;
	$myquery1 = "".$myquery1." ".$s_ctype;
	$myquery1 = "".$myquery1." ".$s_term;
	$myquery1 = "".$myquery1." ".$s_year;
	$myquery1 = "".$myquery1." ".$s_input;

	$myquery1 = "".$myquery1." order by avg desc, count desc;";
	
	$result1 = pg_query($myquery1) or die('Query failed: '.pg_last_error());

	if(pg_num_rows($result1)==0) {

		echo "<script>alert('mainpage.php');
		document.location.href='/logout.php/';</script>;";
	}
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
<h3 style="color:white"><strong>강의 목록</strong></h3>
<div class="well">

<?php 
	for($i = 0; $i < pg_num_rows($result1); $i ++) {
		$tuple = pg_fetch_assoc($result1,$i);

?>

<div class="panel panel-primary">
	  <div class="panel-heading post-title" style="cursor:pointer" onclick="mySlider(<?php echo $i; ?>)">
	    <h3 class="panel-title"><?php echo "$tuple[year]/$tuple[semester]<br><strong>[$tuple[courseid]-$tuple[sectionid]] $tuple[title]</strong><br>$tuple[lastname] $tuple[firstname] 교수님"; ?></h3>
	  </div>
	  <div id = <?php echo "\"post_content_$i\""; ?> class="panel-body post-content">
	    <div class="well">
		    <?php echo "$tuple[year]/$tuple[semester]"; ?>
		    <br>
		    <h3><?php echo "[$tuple[courseid]-$tuple[sectionid]] $tuple[title]"; ?></h3>
		    <?php
		    if($tuple['dept_name']!="") echo "$tuple[dept_name] ";
		    if($tuple['type'] == "non_major") echo "교양";
		    if($tuple['type'] == "major") echo "전공";
		    echo " $tuple[credits]학점";
		    ?>
		    <br>
		    <?php echo "$tuple[lastname] $tuple[firstname] 교수님<br>"; ?>
		    <?php
			    $timeclassquery = "SELECT * FROM sec_time_class where courseid='$tuple[courseid]' and sectionid='$tuple[sectionid]' and semester='$tuple[semester]' and year=$tuple[year] order by period;";
				$timeclassresult = pg_query($timeclassquery) or die('Query failed: '.pg_last_error());
				for($k = 0; $k < pg_num_rows($timeclassresult); $k++) {
					$timeclassinfo = pg_fetch_assoc($timeclassresult,$k);
					echo "$timeclassinfo[day] $timeclassinfo[period]교시 / $timeclassinfo[building] $timeclassinfo[room_number]호<br>";
				}
		    ?>
		</div>
		    <br>
		    <?php echo "평균 평점: $tuple[avg]";?>
		    <br>
		    <?php echo "작성된 수강평가 수: $tuple[count]개"; ?>
		    <input type="hidden" id=<?php echo "\"comment_num_$i\"";?> value=<?php echo "\"$tuple[count]\"";?>>
		    <br><br>
		    <button class="btn btn-lg btn-primary btn-block" style="cursor:pointer" onclick="commentSlider(<?php echo $i; ?>)">수강평가</button>
		
		    <hr>
		    <!-- 이부분에 메모장 내용 추가!-->
		   
		    <div class="form-group comment-write" id=<?php echo "\"comment_write_$i\""; ?> >
		    	<ul class="list-group comment-list" id=<?php echo "\"comments_$i\""; ?> >
					
			      <?php 
			      	$evalquery = "select nickname, rating, likes, content from user_account natural join evaluation where courseid='$tuple[courseid]' and sectionid='$tuple[sectionid]' and year=$tuple[year] and semester='$tuple[semester]' order by likes desc;";
			     	$evalresult = pg_query($evalquery) or die('Query failed: '.pg_last_error());

			      	for($j = 0; $j < pg_num_rows($evalresult); $j ++) {
			     		$eval = pg_fetch_assoc($evalresult,$j);

			      ?>
			      <li class="list-group-item">
			      <form action="/eval_list.php" method="GET"> <!--닉네임을 클릭하면 작성자의 수강평가 목록을 불러온다-->
			      	<input type="hidden" name="username" value=<?php echo "$eval[nickname]"; ?>>
			      	작성자: <button type="submit" style="cursor:pointer"><?php echo "$eval[nickname]"; ?></button>
			      </form>
			      <?php echo "[평점: $eval[rating]]"; ?><br>
			      <?php echo "$eval[content]"; ?><br>
			      <br>추천 수<p id=<?php echo "\"likes_$i"; echo "_$j\""; ?>><?php echo "$eval[likes]"; ?></p>

			      <!-- 추천하기 구현을 위한 hidden input -->
			      <input id=<?php echo "\"comment_usernn_$i"; echo "_$j\""; ?> type="hidden" value=<?php echo "\"$eval[nickname]\""; ?>>
			      <input id=<?php echo "\"comment_courseid_$i"; echo "_$j\""; ?> type="hidden" value=<?php echo "\"$tuple[courseid]\""; ?>>
				  <input id=<?php echo "\"comment_sectionid_$i"; echo "_$j\""; ?> type="hidden" value=<?php echo "\"$tuple[sectionid]\""; ?>>
				  <input id=<?php echo "\"comment_semester_$i"; echo "_$j\""; ?> type="hidden" value=<?php echo "\"$tuple[semester]\""; ?>>
				  <input id=<?php echo "\"comment_year_$i"; echo "_$j\""; ?> type="hidden" value=<?php echo "\"$tuple[year]\""; ?>>
				  <input id=<?php echo "\"comment_likes_$i"; echo "_$j\""; ?> type="hidden" value=<?php echo "\"$eval[likes]\""; ?>>

			      <a style="cursor:pointer" onclick="likeClicked(<?php echo "'$i"; echo "_$j'"; ?>)">추천하기</a>
			      
			      <?php
			      //render current session's components
			      if($eval['nickname'] == $usernn) {
			      ?>
			      <br>
			      <button style="cursor:pointer;margin-bottom:5px" onclick="modifySlider(<?php echo "'$i"; echo "_$j'"; ?>)">수정</button> 
			      <form action="/comment_delete.php" method="POST">
			      	<input name="commentusername" type="hidden" value=<?php echo "\"$usernn\""; ?>>
		        	<input name="commentcourseid" type="hidden" value=<?php echo "\"$tuple[courseid]\""; ?>>
					<input name="commentsectionid" type="hidden" value=<?php echo "\"$tuple[sectionid]\""; ?>>
					<input name="commentsemester" type="hidden" value=<?php echo "\"$tuple[semester]\""; ?>>
					<input name="commentyear" type="hidden" value=<?php echo "\"$tuple[year]\""; ?>>
			      	
			      	<button type="submit" style="cursor:pointer">삭제</button></form>
			      	<hr>
			      
			      <div id=<?php echo "\"modifyboard_$i"; echo "_$j\"";?> class="modifyboard">
			      	<h4><strong>수정하기</strong></h4><br>
		        	<form action="/comment_modify.php" method="POST">
		        	<input name="commentusername" type="hidden" value=<?php echo "\"$usernn\""; ?>>
		        	<input name="commentcourseid" type="hidden" value=<?php echo "\"$tuple[courseid]\""; ?>>
					<input name="commentsectionid" type="hidden" value=<?php echo "\"$tuple[sectionid]\""; ?>>
					<input name="commentsemester" type="hidden" value=<?php echo "\"$tuple[semester]\""; ?>>
					<input name="commentyear" type="hidden" value=<?php echo "\"$tuple[year]\""; ?>>
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
			      }
			      ?>
			      </li>
			      <?php 

			      	} //for j
			      ?>
			    </ul>
			    <hr>
		        
		          <!--<div class="col-md-1"></div>-->
		          	
		            <!--name="commentcontent"-->
		            <h4><strong>수강평가 남기기</strong></h4><br>
		        	<form action="/comment_write.php" method="POST">
		        	<input name="commentusername" type="hidden" value=<?php echo "\"$usernn\""; ?>>
		        	<input name="commentcourseid" type="hidden" value=<?php echo "\"$tuple[courseid]\""; ?>>
					<input name="commentsectionid" type="hidden" value=<?php echo "\"$tuple[sectionid]\""; ?>>
					<input name="commentsemester" type="hidden" value=<?php echo "\"$tuple[semester]\""; ?>>
					<input name="commentyear" type="hidden" value=<?php echo "\"$tuple[year]\""; ?>>
			        	<div class="row">
			        	<div class="col-md-5">
			        	
			        	</div>
						<div class="col-md-2">
							<label for="ratings">평점</label>	
							<input type="range" name="commentrating" id="ratings" min="0" max="5" value="0">
						</div>
						
						<div class="col-md-5"></div>
						</div>
						<br>
						<label for=<?php echo "\"comment_content_$i\""; ?>>내용</label>
			        	<input name="commentcontent" type="text" class="form-control inputcontent" id=<?php echo "\"comment_content_$i\""; ?> placeholder="Comment" value=""> 
			        	<br>
						<div class="row">
			        	<div class="col-md-5">
			        	
			        	</div>
						<div class="col-md-2">
							<button type="submit" style="margin-top:10px" class="btn btn-lg btn-primary comment-submit" value=<?php echo "\"$i\""; ?>>확인</button>
						</div>
						<div class="col-md-5"></div>
						</div>
					</form>
		        <!--<input name="commentpostid" type="hidden" value="<%=x.id%>">-->
		    </div>
		    
			</div>
	</div>
<?php

	} //for i
?>
</div>
</div>
	<div class="container" style="color:white;background-color:skyblue;width:70%;text-align:center">
		<p> DB 텀프로젝트 2012130888 김인호 </p>
	</div>
		
		<script>
			$(".post-content").hide();
			$(".comment-write").hide();
			$(".modifyboard").hide();
			$(".inputcontent").val('');
			function mySlider(postid) {
	      		$("#post_content_"+postid).slideToggle();
	   		}

	   		function commentSlider(postid) {
		      $("#comment_write_"+postid).slideToggle();
		    }
		    function modifySlider(commentid) {
		      $("#modifyboard_"+commentid).slideToggle();
		    }

		    function likeClicked(commentid) {
		      loginUser = <?php echo "\"$usernn\";";?>
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
<?php
		pg_close($dbconn);
	} //else
?>