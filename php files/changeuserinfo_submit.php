<?php

	session_start();
  
	if(!isset($_SESSION['user_nickname']) ) {
		echo "<script> alert('로그인이 필요합니다.');
		document.location.href='/login.php/'
		</script>";
	} else {
		
    $dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
    or die('Could not connect: ' . pg_last_error());
/*
    $studentid = $_POST['userstuid'];
    $removequery = "DELETE FROM student where studentid='$studentid';";
    pg_query($removequery) or die('Query failed: '.pg_last_error());
*/
    $usernn = $_SESSION['user_nickname'];
    $newnn = $_POST['usernickname'];
    $userpw = $_POST['userpw'];
    $userpw1 = $_POST['userpw1'];
    $userpw2 = $_POST['userpw2'];

    $findquery = "SELECT * FROM user_account WHERE nickname='$usernn' and userpw='$userpw';";
    $foundresult = pg_query($findquery) or die('Query failed: '.pg_last_error());

    $findquery2 = "SELECT * FROM user_account WHERE nickname='$newnn';";
    $foundresult2 = pg_query($findquery2) or die('Query failed: '.pg_last_error());

    if(pg_num_rows($foundresult) == 0) {
    	echo "<script> alert('비밀번호가 올바르지 않습니다.');
		window.history.back();
		</script>";
    } else if ($userpw1 != $userpw2) {
    	echo "<script> alert('새로운 비밀번호를 정확하게 입력하세요.');
		window.history.back();
		</script>";
    
    } else {

    	if ($usernn != $newnn && pg_num_rows($foundresult2) != 0) {

		echo "<script> alert('이미 존재하는 닉네임입니다.');
		window.history.back();
		</script>";
		
		} else {


    	if ($userpw1 != "" && $userpw2 != "") {
    		$updatequery = "UPDATE user_account SET userpw='$userpw2' WHERE nickname='$usernn';";
    		pg_query($updatequery) or die('Query failed: '.pg_last_error());

    	}
    	if ($usernn != $newnn) {
    		$updatequery2 = "UPDATE user_account SET nickname='$newnn' WHERE nickname='$usernn';";
    		pg_query($updatequery2) or die('Query failed: '.pg_last_error());
    	}
    session_destroy();
    session_start();
    $_SESSION['user_nickname'] = $newnn;
    echo "<script> alert('개인정보 수정이 완료되었습니다.');
		document.location.href='/mainpage.php/'
		</script>";

    	}
	}

	} pg_close($dbconn); 
 ?>