<html>
	<head>
		<meta charset="utf-8">
		<title>비밀번호 찾기</title>
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
	if(isset($_SESSION['user_nickname']) ) {
			
		echo "<script>
			document.location.href='/mainpage.php/'
			</script>";

	} else {
		$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
or die('Could not connect: ' . pg_last_error());
	?>

	<div class="container" style="color:white;background-color:skyblue;width:30%;margin-top:5%;text-align:center">
	<p> COSE371-03 Database Term Project </p>
	<h1> <strong>비밀번호 찾기</strong></h1>
	</div>
	<div class="container" style="background-color:lightgray;width:30%;padding-top:10px;padding-bottom:10px;text-align:center">
	
	<form action="/findpw_submit.php" method="post">
		<div class="form-group">
		    <label for="InputEmail">이메일 아이디</label>
		    <input name="userid" type="email" class="form-control" id="InputEmail" placeholder="ID">
		</div>
		<div class="form-group">
		    <label for="InputStudentID">학번</label>
		    <input name="userstuid" type="text" class="form-control" id="InputStudentID" placeholder="Student ID">
		</div>
		<div class="form-group">
	    <label for="dropdownList">비밀번호 확인용 질문</label>
	    <br>
	    	<select name="userquestion" id="dropdownList">
			    <?php
			      $questionquery = "select * from question";
				  $result = pg_query($questionquery) or die('Query failed: '.pg_last_error());
			      $qCount = pg_query("select count(*) from question") or die('Query failed: '.pg_last_error());
			      $boundary = pg_fetch_result($qCount,0,0);
			      for($i = 0; $i < $boundary; $i++) {
			      $questionIDs = pg_fetch_result($result,$i,0);
			      $questions = pg_fetch_result($result,$i,1);
				  echo "<option value=\"".$questionIDs."\">".$questions."</option>";
				  }
				 ?>
			</select>
	    </div>

				  <div class="form-group">
				    <input name="useranswer" type="text" class="form-control" id="InputAnswer" placeholder="Answer">
				  </div>
				  <button type="submit" class="btn btn-lg btn-primary btn-block" id="signupbutton">확인</button>
	</form>
	<a href="/login.php" style="margin-top: 10px" type="submit" class="btn btn-lg btn-primary btn-block">로그인 화면으로</a>
	</div>
	<div class="container" style="color:white;background-color:skyblue;width:30%;text-align:center">
				<p> DB 텀프로젝트 2012130888 김인호 </p>
	</div>

	<?php
	} pg_close($dbconn);
	?>
	</body>
</html>