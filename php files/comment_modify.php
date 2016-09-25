<?php
	session_start();

	if(!isset($_SESSION['user_nickname']) ) {
		
	echo "<script> alert('로그인이 필요합니다.');
		document.location.href='/logout.php/'
		</script>";

	} else {
		$usernn = $_POST['commentusername'];
		$courseid = $_POST['commentcourseid'];
		$sectionid = $_POST['commentsectionid'];
		$year = $_POST['commentyear'];
		$semester = $_POST['commentsemester'];
		$rating = $_POST['commentrating'];
		$content =$_POST['commentcontent'];
		
		$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
		or die('Could not connect: ' . pg_last_error());

		$getuseridquery = "SELECT userid FROM user_account where nickname='".$usernn."';";
		$getuserid = pg_query($getuseridquery) or die('Query failed: '.pg_last_error());		
		$temp = pg_fetch_assoc($getuserid,0);
		$userid = "$temp[userid]";

		if ($content=="") {
			echo "<script>
			alert('내용이 비었습니다.');
			window.history.back();
			</script>";
		} else {
			$updatequery = "UPDATE evaluation SET rating=$rating , content='$content' WHERE userid='$userid' and courseid='$courseid' and sectionid = '$sectionid' and year=$year and semester='$semester';";
			pg_query($updatequery) or die('Query failed: '.pg_last_error());
			echo "<script>
			alert('수정이 완료되었습니다.');
			window.history.back();
			</script>";
		}

		pg_close($dbconn);
	}
?>