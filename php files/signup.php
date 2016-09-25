<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
		<title>회원가입</title>
		<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<!--<link type="text/css" rel="stylesheet" href="불러올CSS파일명.css"/>-->
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>

<body>
	<?php

	session_start();
	if(isset($_SESSION['user_nickname']) ) {
		
	echo "<script>
		document.location.href='/mainpage.php/';
		</script>";
	} else {
$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
or die('Could not connect: ' . pg_last_error());
?>

<div class="container" style="color:white;background-color:skyblue;width:30%;margin-top:5%;text-align:center">
	<p> COSE371-03 Database Term Project </p>
	<h1> <strong>회원가입</strong></h1>
	</div>
<div class="container" style="background-color:lightgray;width:30%;padding-top:10px;padding-bottom:10px;text-align:center">		
				<form action="/signup_submit.php" method="post">
				  <div class="form-group">
				    <label for="InputEmail">사용할 이메일 아이디</label>
				    <input name="userid" type="email" class="form-control" id="InputEmail" placeholder="ID">
				  </div>
				  <div class="form-group">
				    <label for="InputNickname">닉네임</label>
				    <input name="usernn" type="text" class="form-control" id="InputNickname" placeholder="Nickname">
				  </div>
				  <div class="form-group">
				    <label for="InputStudentID">학번</label>
				    <input name="userstuid" type="text" class="form-control" id="InputStudentID" placeholder="Student ID">
				  </div>

				  <div class="form-group">
				    <label for="InputLastname">성</label>
				    <input name="userlname" type="text" class="form-control" id="InputLastname" placeholder="Last name">
				  </div>

				  <div class="form-group">
				    <label for="InputFirstname">이름</label>
				    <input name="userfname" type="text" class="form-control" id="InputFirstname" placeholder="First name">
				  </div>

				  <div class="form-group">
				    <label for="InputPassword1">비밀번호</label>
				    <input name="userpw" type="password" class="form-control" id="InputPassword1" placeholder="Password">
				  </div>
				  <div class="form-group">
				    <label for="InputPassword2">비밀번호 확인</label>
				    <input name="userpw2" type="password" class="form-control" id="InputPassword2" placeholder="Password를 한 번 더 입력하세요.">
				  </div>

				  <div class="form-group">
				    <label for="depts">소속학과</label>
				    <br>
					<select name="userdept" id="depts">
				    <?php
				      $deptsquery = "select * from department";
					  $deptnames = pg_query($deptsquery) or die('Query failed: '.pg_last_error());
				      $deptsCount = pg_query("select count(*) from department") or die('Query failed: '.pg_last_error());
				      $deptsNum = pg_fetch_result($deptsCount,0,0);
				      for($i = 0; $i < $deptsNum; $i++) {
				      	$depts = pg_fetch_result($deptnames,$i,0);
					  echo "<option value=\"".$depts."\">".$depts."</option>";
					  }
					 ?>
					</select>
				  </div>
				  <!--비밀번호 확인 질문-->
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
				  <button type="submit" class="btn btn-lg btn-primary btn-block" id="signupbutton">가입</button>
				</form>
				<a href="/login.php" style="margin-top: 10px" type="submit" class="btn btn-lg btn-primary btn-block">로그인 화면으로</a>
			</div>
			<div class="container" style="color:white;background-color:skyblue;width:30%;text-align:center">
				<p> DB 텀프로젝트 2012130888 김인호 </p>
			</div>

			<?php } pg_close($dbconn); ?>

	</body>
</html>

