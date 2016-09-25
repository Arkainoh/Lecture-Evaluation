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

			//course
			$courseid = $_POST['courseid'];
			$sectionid = $_POST['sectionid'];
			$year = $_POST['year'];
			$semester = $_POST['semester'];
			$day = $_POST['day'];
			$period = $_POST['period'];
			$building = $_POST['building'];
			$room_number = $_POST['room_number'];

			$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
   			or die('Could not connect: ' . pg_last_error());

	   			$removetimeclassinfo = "DELETE FROM sec_time_class WHERE courseid = '$courseid' and sectionid = '$sectionid' and semester = '$semester' and year = $year and day = '$day'and period = $period and building = '$building' and room_number = '$room_number';";
	   			pg_query($removetimeclassinfo) or die("<script> alert('<ALERT> 삭제 실패.');window.history.back();</script>");
 				
		}

		echo "<script> alert('삭제 완료.');
		window.history.back();
		</script>";

	} pg_close($dbconn);

?>