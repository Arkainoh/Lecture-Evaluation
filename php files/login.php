<html>
	<head>
		<meta charset="utf-8">
		<title>로그인 페이지</title>
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

	?>
	<div class="container" style="color:white;background-color:skyblue;width:30%;margin-top:5%;text-align:center">
	<p> COSE371-03 Database Term Project </p>
	<h1> <strong>강의평가 시스템</strong></h1>
	</div>
			<div class="container" style="background-color:lightgray;width:30%;padding-top:10px;padding-bottom:10px;text-align:center">		
				<form action="/login_submit.php" method="post">
				  <div class="form-group">
				    <label for="exampleInputEmail1">이메일 아이디</label>
				    <input name="userID" type="email" class="form-control" id="exampleInputEmail1" placeholder="ID">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputPassword1">비밀번호</label>
				    <input name="userPW" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
				  </div>

				  <button type="submit" class="btn btn-lg btn-primary btn-block">로그인</button>
				  
				</form>
				<p style="margin-top:10px"> 비밀번호를 잊어버리셨나요? </p>
				<a href="/findpw.php" type="submit" class="btn btn-lg btn-primary btn-block">비밀번호 찾기</a>
				<p style="margin-top:10px"> 회원이 아니신가요? </p>
				<a href="/signup.php" type="submit" class="btn btn-lg btn-primary btn-block">회원가입</a>
			</div>
			<div class="container" style="color:white;background-color:skyblue;width:30%;text-align:center">
				<p> DB 텀프로젝트 2012130888 김인호 </p>
			</div>
	<?php
	}
	?>

	</body>
</html>
