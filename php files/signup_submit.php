<?php

session_start();
session_destroy();

$userid = $_POST['userid'];
$usernn = $_POST['usernn'];
$userstuid = $_POST['userstuid'];
$userlname = $_POST['userlname'];
$userfname = $_POST['userfname'];
$userpw = $_POST['userpw'];
$userpw2 = $_POST['userpw2'];
$userdept = $_POST['userdept'];
$userquestion = $_POST['userquestion'];
$useranswer = $_POST['useranswer'];

$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
or die('Could not connect: ' . pg_last_error());

$idquery = "SELECT * from user_account where userid='".$userid."';";
$idresult = pg_query($idquery) or die('Query failed: '.pg_last_error());

$nnquery = "SELECT * from user_account where nickname='".$usernn."';";
$nnresult = pg_query($nnquery) or die('Query failed: '.pg_last_error());

$stuquery = "SELECT * from student where studentid='".$userstuid."';";
$sturesult = pg_query($stuquery) or die('Query failed: '.pg_last_error());

if($userid == "" ||	$usernn == "" || $userstuid == "" || $userlname == "" || $userfname == "" || $userpw == ""|| $userpw2 == "" || $userdept == "" || $userquestion == "" || $useranswer == "" || $useranswer == "") {
	//비어있는 input이 있는지 확인
	echo "<script>
	alert('입력되지 않은 항목이 있습니다.');
	window.history.back();
	</script>";
}


else if(pg_num_rows($idresult) != 0) {
	echo "<script>
		alert('이미 존재하는 아이디입니다.');
		window.history.back();
		</script>";
}
else if(pg_num_rows($nnresult) != 0) {
	echo "<script>
		alert('이미 존재하는 닉네임입니다.');
		window.history.back();
		</script>";
}
else if(pg_num_rows($sturesult) != 0) {
	echo "<script>
		alert('이미 존재하는 학번입니다. 학생 한 명당 하나의 계정만 생성할 수 있습니다.');
		window.history.back();
		</script>";
}
else if($userpw != $userpw2) {
	echo "<script>
	alert('비밀번호를 확인하세요.');
	window.history.back();
	</script>";
} else {

$insertstudent = "INSERT INTO student VALUES('".$userstuid."','".$userfname."','".$userlname."','".$userdept."')";
$insertaccount = "INSERT INTO user_account VALUES('".$userid."','".$usernn."','".$userstuid."','".$userpw."',".$userquestion.",'".$useranswer."')";

pg_query($insertstudent) or die('Query failed: '.pg_last_error());
pg_query($insertaccount) or die('Query failed: '.pg_last_error());

	echo "<script>
	alert('가입이 완료되었습니다.');
	document.location.href='/login.php/';
	</script>";
}

pg_close($dbconn);
?>