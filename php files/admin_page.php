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
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>관리자 페이지</title>
		<!--<link type="text/css" rel="stylesheet" href="불러올CSS파일명.css"/>-->
		<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<style>
	td {
		text-align: center;
	}

	</style>

	</head>
	
	<body>
	<div class="container">
<a href="/mainpage.php">메인 페이지로</a><br>

<form action="addsection.php" method="POST">
<h3> 강의 추가 </h3>
<input type="text" name="title" placeholder="강의명">
<input type="text" name="courseid" placeholder="학수번호">
<input type="text" name="sectionid" placeholder="분반">
<input type="text" name="year" placeholder="개설연도">
<input type="text" name="semester" placeholder="개설학기">
<br>

<input type="text" name="type" placeholder="major/non_major">
<input type="text" name="coursedept" placeholder="전공 학과">
<input type="text" name="credits" placeholder="학점">

<br>
<input type="text" name="instructorid" placeholder="교수 ID">
<input type="text" name="lastname" placeholder="교수 성">
<input type="text" name="firstname" placeholder="교수 이름">
<input type="text" name="instdept" placeholder="교수 소속학과">
<br>
<input type="submit" value="추가">
</form>
<h3> 강의 목록 </h3>
<?php
	$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
    or die('Could not connect: ' . pg_last_error());

    $getquery = "SELECT * FROM course natural join section natural join teaches;";
    $getcourseinfo = pg_query($getquery) or die('Query failed: '.pg_last_error());    
    
	for($i = 0; $i < pg_num_rows($getcourseinfo); $i++) {
		$tuple = pg_fetch_assoc($getcourseinfo,$i);
		
	?>
	<div class="well">
		
		<form action="removesection.php" method="POST"> <!-- 삭제 -->
		<?php echo "[$tuple[courseid]-$tuple[sectionid]] $tuple[title] $tuple[year]-$tuple[semester] 교수 ID: $tuple[instructorid]"; ?>
		<?php
			    $timeclassquery = "SELECT * FROM sec_time_class where courseid='$tuple[courseid]' and sectionid='$tuple[sectionid]' and semester='$tuple[semester]' and year=$tuple[year] order by period;";
				$timeclassresult = pg_query($timeclassquery) or die('Query failed: '.pg_last_error());
				for($k = 0; $k < pg_num_rows($timeclassresult); $k++) {
					$timeclassinfo = pg_fetch_assoc($timeclassresult,$k);
					echo " / $timeclassinfo[day] $timeclassinfo[period]교시 $timeclassinfo[building] $timeclassinfo[room_number]호";
				}
		?>
			<input type="hidden" name="courseid" value=<?php echo $tuple['courseid']; ?>>
			<input type="hidden" name="sectionid" value=<?php echo $tuple['sectionid']; ?>>
			<input type="hidden" name="year" value=<?php echo $tuple['year'];?>>
			<input type="hidden" name="semester" value=<?php echo $tuple['semester'];?>>
			<input type="submit" value="삭제">
		</form>

	<form action="modifysection.php" method="POST"> <!-- 수정 -->
		<input type="text" name="newcourseid" value=<?php echo $tuple['courseid']; ?> placeholder="학수번호">
		<input type="text" name="newsectionid" value=<?php echo $tuple['sectionid']; ?> placeholder="분반">
		<input type="text" name="newyear" value=<?php echo $tuple['year']; ?> placeholder="개설연도">
		<input type="text" name="newsemester" value=<?php echo $tuple['semester']; ?> placeholder="개설학기">
		<input type="text" name="newtitle" value=<?php echo $tuple['title']; ?> placeholder="강의명">
		<input type="text" name="newinstructorid" value=<?php echo $tuple['instructorid']; ?> placeholder="교수 ID">

		<input type="hidden" name="courseid" value=<?php echo $tuple['courseid']; ?>>
		<input type="hidden" name="sectionid" value=<?php echo $tuple['sectionid']; ?>>
		<input type="hidden" name="year" value=<?php echo $tuple['year']; ?>>
		<input type="hidden" name="semester" value=<?php echo $tuple['semester']; ?>>
		<input type="hidden" name="title" value=<?php echo $tuple['title']; ?>>
		<input type="hidden" name="instructorid" value=<?php echo $tuple['instructorid']; ?>>
		<input type="submit" value="수정">
	</form>

	</div>
<?php
	}//for i

?>

<form action="addtimeclassinfo.php" method="POST">
<h3> 강의 시간, 장소 정보 추가 </h3>
<input type="text" name="courseid" placeholder="학수번호">
<input type="text" name="sectionid" placeholder="분반">
<input type="text" name="year" placeholder="개설연도">
<input type="text" name="semester" placeholder="개설학기">
<br>
<input type="text" name="day" placeholder="요일">
<input type="text" name="period" placeholder="교시">
<input type="text" name="building" placeholder="건물">
<input type="text" name="room_number" placeholder="강의실">
<br>
<input type="submit" value="추가">
</form>

<form action="removetimeclassinfo.php" method="POST">
<h3> 강의 시간, 장소 정보 삭제 </h3>
<input type="text" name="courseid" placeholder="학수번호">
<input type="text" name="sectionid" placeholder="분반">
<input type="text" name="year" placeholder="개설연도">
<input type="text" name="semester" placeholder="개설학기">
<br>
<input type="text" name="day" placeholder="요일">
<input type="text" name="period" placeholder="교시">
<input type="text" name="building" placeholder="건물">
<input type="text" name="room_number" placeholder="강의실">
<br>
<input type="submit" value="삭제">
</form>
</div>
</body>
</html>

<?php
	}


	}

?>


