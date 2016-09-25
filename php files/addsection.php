
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
			$title = $_POST['title'];
			$coursedept = $_POST['coursedept'];
			$credits = $_POST['credits'];
			$type = $_POST['type'];
			if($coursedept=="") $coursedept="null";
			else $coursedept = "'".$coursedept."'";

			//section
			$sectionid = $_POST['sectionid'];
			$year = $_POST['year'];
			$semester = $_POST['semester'];

			//instructor
			$instructorid = $_POST['instructorid'];
			$lastname = $_POST['lastname'];
			$firstname = $_POST['firstname'];
			$instdept = $_POST['instdept'];
			if($instdept=="") $instdept="null";
			else $instdept = "'".$instdept."'";

			$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
   			or die('Could not connect: ' . pg_last_error());
   			
   			//쿼리 순서   			
   			//1. course
   			//2. section
   			//3. instructor
   			//4. teaches

   			$checkcourse = "SELECT * FROM course WHERE courseid='$courseid'";
   			$courseresult = pg_query($checkcourse) or die('Query failed: '.pg_last_error());
   			if(pg_num_rows($courseresult)==0){
	   			$addcourse = "INSERT INTO course VALUES('$courseid', '$title',$coursedept, $credits, '$type');";
	   			pg_query($addcourse) or die("<script> alert('<ALERT-course> 이미 존재하는 course이거나, 존재하지 않는 학과입니다.');window.history.back();</script>");
   			} else { //이미 존재하는 courseid는 업데이트하지 않고 기존의 것을 그대로 사용한다.
   				echo "<script> alert('이미 존재하는 courseid입니다. course는 새로 추가되지 않았습니다.');
				</script>";
   			}

   			$checksection = "SELECT * FROM section WHERE courseid='$courseid' and sectionid='$sectionid' and semester='$semester' and year=$year";
   			$sectionresult = pg_query($checksection) or die('Query failed: '.pg_last_error());
   			if(pg_num_rows($sectionresult)==0){
	   			$addsection = "INSERT INTO section VALUES('$courseid', '$sectionid','$semester', $year);";
	   			pg_query($addsection) or die("<script> alert('<ALERT-section> 존재하지 않는 course입니다.');window.history.back();</script>");
	   		} else {
	   			echo "<script> alert('이미 존재하는 section입니다. section은 새로 추가되지 않았습니다.');
				</script>";

	   		}

	   		$checkinst = "SELECT * FROM instructor WHERE instructorid='$instructorid';";
   			$instresult = pg_query($checkinst) or die('Query failed: '.pg_last_error());
   			if(pg_num_rows($instresult)==0){
	   			$addinstructor = "INSERT INTO instructor VALUES('$instructorid', '$firstname','$lastname', $instdept);";
	   			pg_query($addinstructor) or die("<script> alert('<ALERT-instructor> 이미 존재하는 교수 ID이거나 존재하지 않는 학과입니다.');window.history.back();</script>");
   			} else {
   				echo "<script> alert('이미 존재하는 instructor입니다. instructor는 새로 추가되지 않았습니다.');
				</script>";
   			}

   			$checkteaches = "SELECT * FROM teaches WHERE instructorid='$instructorid' and courseid='$courseid' and semester='$semester' and year=$year;";
   			$teachesresult = pg_query($checkteaches) or die('Query failed: '.pg_last_error());
   			if(pg_num_rows($teachesresult)==0){
	   			$addteaches = "INSERT INTO teaches VALUES('$instructorid', '$courseid','$sectionid', '$semester', $year);";
	   			pg_query($addteaches) or die("<script> alert('<ALERT-teaches> 존재하지 않는 ID이거나 존재하지 않는 section입니다.');window.history.back();</script>");
   			} else {
   				echo "<script> alert('이미 존재하는 teaches입니다. teaches는 새로 추가되지 않았습니다.');
				</script>";
   			}
		}

		echo "<script> alert('추가 완료.');
		window.history.back();
		</script>";

	} pg_close($dbconn);

?>