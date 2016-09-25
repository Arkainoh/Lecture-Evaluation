
<?php

	session_start();
	if(!isset($_SESSION['user_nickname']) ) {
		echo "<script> alert('로그인이 필요합니다.');
		document.location.href='/login.php/'
		</script>";
	} else {
		$usernn = $_SESSION['user_nickname'];
		if($usernn != 'admin') {
			echo "<script> alert('관리자만 접근할 수 있습니다.');
		document.location.href='/login.php/'
		</script>";
		} else {

			$courseid = $_POST['courseid'];
			$sectionid = $_POST['sectionid'];
			$year = $_POST['year'];
			$semester = $_POST['semester'];

			$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
   			or die('Could not connect: ' . pg_last_error());
   			$removequery = "DELETE FROM section WHERE courseid='$courseid' and sectionid='$sectionid' and year=$year and semester = '$semester';";
   			pg_query($removequery) or die('Query failed: '.pg_last_error());

		}
		echo "<script> alert('삭제 완료.');
		window.history.back();
		</script>";

	}

?>


