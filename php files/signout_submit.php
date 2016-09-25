<?php

	session_start();
  
	if(!isset($_SESSION['user_nickname']) ) {
		echo "<script> alert('로그인이 필요합니다.');
		document.location.href='/login.php/'
		</script>";
	} else {
		
    $dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
    or die('Could not connect: ' . pg_last_error());

    $studentid = $_POST['userstuid'];
    $removequery = "DELETE FROM student where studentid='$studentid';";
    pg_query($removequery) or die('Query failed: '.pg_last_error());


	session_destroy();
    echo "<script> alert('회원탈퇴가 완료되었습니다.');
		document.location.href='/login.php/'
		</script>";
	} pg_close($dbconn); 
 ?>