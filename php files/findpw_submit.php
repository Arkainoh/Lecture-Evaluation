<?php

session_start();
session_destroy();

$userid = $_POST['userid'];
$userstuid = $_POST['userstuid'];
$userquestion = $_POST['userquestion'];
$useranswer = $_POST['useranswer'];

$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
or die('Could not connect: ' . pg_last_error());

$searchquery = "SELECT * FROM user_account WHERE userid='$userid' and studentid='$userstuid' and questionid='$userquestion' and answer='$useranswer';";
$result = pg_query($searchquery) or die('Query failed: '.pg_last_error());

if(pg_num_rows($result) == 0) {
	echo "<script>
		alert('잘못된 정보입니다.');
		window.history.back();
		</script>";
} else {
	$tuple = pg_fetch_assoc($result,0);

	echo "<script>
		alert('비밀번호는 $tuple[userpw]입니다.');
		document.location.href='/login.php/';</script>";
}

pg_close($dbconn);
?>