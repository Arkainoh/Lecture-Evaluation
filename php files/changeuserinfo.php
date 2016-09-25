<html>
	<head>
		<meta charset="utf-8">
		<title>개인정보 수정</title>
		<!--<link type="text/css" rel="stylesheet" href="불러올CSS파일명.css"/>-->
		<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<style>
	label {
		margin-top:10px;
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
			$usernn = $_SESSION['user_nickname'];
			if($usernn=="admin")
				echo "<a href=\"/admin_page.php\">관리자 페이지로</a>";

			$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
		    or die('Could not connect: ' . pg_last_error());
		    $usernn = $_SESSION['user_nickname'];
		    $getstuidquery = "SELECT studentid FROM user_account where nickname='".$usernn."';";
		    $getstuid = pg_query($getstuidquery) or die('Query failed: '.pg_last_error());    
		    $temp = pg_fetch_assoc($getstuid,0);
		    $stuid = "$temp[studentid]";
?>

<div class="container" style="color:white;background-color:skyblue;width:70%;margin-top:5%;text-align:center">
	<p> Welcome! </p>
	<h1> <strong><?php echo $_SESSION['user_nickname']; ?>님, 환영합니다.</strong></h1><a href="/logout.php"> 로그아웃 </a><br>
	<form action="/eval_list.php" method="GET">
		<input type="hidden" name = "username" value=<?php echo "\"$usernn\"";?>>
		<button type="submit" style="cursor:pointer;color:black">내가 작성한 평가 보러가기</button>
	</form>
	<a href="/mainpage.php"> 강의검색 </a><br>
	<a href="/ranking.php"> 명예의 전당 </a><br>
	<a href="/signout.php"> 회원탈퇴 </a>
	</div>
			<div class="container" style="background-color:lightgray;width:70%;padding-top:10px;padding-bottom:10px;text-align:center">		
				<h3> 개인정보 수정 <h3>
				<form action="/changeuserinfo_submit.php" method="POST">
					<div class="well">
						
						<label for="nickname">새로운 닉네임</label>
				    	<input name="usernickname" type="text" class="form-control" id="nickname" placeholder="닉네임" value=<?php echo $usernn; ?>>
				    	<label for="password">현재 비밀번호</label>
				    	<input name="userpw" type="password" class="form-control" id="password" placeholder="Password">
				    	<label for="password1">새로운 비밀번호</label>
				    	<input name="userpw1" type="password" class="form-control" id="password1" placeholder="Password">
				    	<label for="password2">비밀번호 확인</label>
				    	<input name="userpw2" type="password" class="form-control" id="password2" placeholder="Password를 한 번 더 입력하세요.">
				    	<button type="submit" style="margin-top:10px" class="btn btn-lg btn-primary">확인</button>
						
					</div>
				</form>

			</div>
			<div class="container" style="color:white;background-color:skyblue;width:70%;text-align:center">
				<p> DB 텀프로젝트 2012130888 김인호 </p>
			</div>
			</body>
			</html>
			<?php
		} pg_close($dbconn);
			?>