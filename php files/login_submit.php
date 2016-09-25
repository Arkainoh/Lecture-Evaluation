<?php

	session_start();
	session_destroy();

	$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234") or die ('Could not connect: ' . pg_last_error());
	
	$inputID = $_POST['userID'];
	$inputPW = $_POST['userPW'];

	$idquery = "select userID from user_account where userID='".$inputID."';";
	$idresult = pg_query($idquery) or die('Query failed: '.pg_last_error());
	
	if(pg_num_rows($idresult) == 0)
		echo "<script> alert('존재하지 않는 아이디입니다.'); document.location.href='/login.php/' </script>";
	else{
		$haha = pg_fetch_result($idresult,0,0);
		$pwquery = "select userPW from user_account where userID='".$haha."';";
		$pwresult = pg_query($pwquery) or die('Query failed: '.pg_last_error());
		$realPW = pg_fetch_result($pwresult,0,0);

		if($realPW == $inputPW){
			//로그인 성공
			$nnquery = "select nickname from user_account where userID='".$haha."';";
			$nnresult = pg_query($nnquery) or die('Query failed: '.pg_last_error());
			$usernn = pg_fetch_result($nnresult,0,0);
			session_start();
			$_SESSION['user_nickname'] = $usernn;

			echo "<script> document.location.href='/mainpage.php/'; </script>";
		}
		else{
			echo "<script> alert('비밀번호를 다시 확인하세요.'); document.location.href='/login.php/' </script>";
		}
	}

?>