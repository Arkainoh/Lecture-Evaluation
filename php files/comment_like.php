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
		$likes = $_POST['commentlikes'];
	
		$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
		or die('Could not connect: ' . pg_last_error());

		$getuseridquery = "SELECT userid FROM user_account where nickname='".$usernn."';";
		$getuserid = pg_query($getuseridquery) or die('Query failed: '.pg_last_error());		
		$temp = pg_fetch_assoc($getuserid,0);
		$userid = "$temp[userid]";
		$likequery = "UPDATE evaluation SET likes=$likes WHERE userid='$userid' and courseid='$courseid' and sectionid = '$sectionid' and year=$year and semester='$semester';";
		pg_query($likequery) or die('Query failed: '.pg_last_error());

		pg_close($dbconn);
	}
?>