
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

			$newcourseid = $_POST['newcourseid'];
			$newsectionid = $_POST['newsectionid'];
			$newyear = $_POST['newyear'];
			$newsemester = $_POST['newsemester'];
			$newtitle = $_POST['newtitle'];
			$newinstructorid = $_POST['newinstructorid'];

			$courseid = $_POST['courseid'];
			$sectionid = $_POST['sectionid'];
			$year = $_POST['year'];
			$semester = $_POST['semester'];
			$title = $_POST['title'];
			$instructorid = $_POST['instructorid'];

			$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
   			or die('Could not connect: ' . pg_last_error());
   			
   			//쿼리 순서   			
   			//1. teaches에서 instructor ID 수정
   			//2. section에서 courseid를 제외하고 다 수정
   			//3. course에서 title과 courseid 수정

   			$teachesupdate = "UPDATE teaches SET instructorid='$newinstructorid' WHERE instructorid = '$instructorid' and courseid='$courseid' and sectionid='$sectionid' and year = $year and semester = '$semester';";
   			pg_query($teachesupdate) or die("<script> alert('존재하지 않는 교수 ID입니다.');window.history.back();</script>");

   			$sectionupdate = "UPDATE section SET sectionid='$newsectionid', year=$newyear, semester='$newsemester' WHERE courseid='$courseid' and sectionid='$sectionid' and year=$year and semester = '$semester';";
   			pg_query($sectionupdate) or die("<script> alert('중복된 강의입니다.');window.history.back();</script>");

   			$courseupdate = "UPDATE course SET courseid='$newcourseid', title='$newtitle' WHERE courseid='$courseid' and title='$title';";
   			pg_query($courseupdate) or die("<script> alert('중복된 학수번호입니다.');window.history.back();</script>");
		}

		echo "<script> alert('수정 완료.');
		window.history.back();
		</script>";

	} pg_close($dbconn);

?>
