<html>
	<head>
		<meta charset="utf-8">
		<title>메인 페이지</title>
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
	<?php

		session_start();
		if(!isset($_SESSION['user_nickname']) ) {
		
		echo "<script> alert('로그인이 필요합니다.');
		document.location.href='/login.php/'
		</script>";
		} else {
			$usernn = $_SESSION['user_nickname'];
			if($usernn=="admin")
				echo "<a href=\"/admin_page.php\">관리자 페이지로</a>";
	?>
	<div class="container" style="color:white;background-color:skyblue;width:70%;margin-top:5%;text-align:center">
	<p> Welcome! </p>
	<h1> <strong><?php echo $_SESSION['user_nickname']; ?>님, 환영합니다.</strong></h1>
	<a href="/changeuserinfo.php"> 개인정보 수정 </a><br>
	<a href="/logout.php"> 로그아웃 </a><br>
	<form action="/eval_list.php" method="GET">
		<input type="hidden" name = "username" value=<?php echo "\"$usernn\"";?>>
		<button type="submit" style="cursor:pointer;color:black">내가 작성한 평가 보러가기</button>
	</form>
	<br>
	<a href="/ranking.php"> 명예의 전당 </a><br>
	<a href="/signout.php"> 회원탈퇴 </a>
	</div>
			<div class="container" style="background-color:lightgray;width:70%;padding-top:10px;padding-bottom:10px;text-align:center">		
				
				<form action="/search_result.php" method="get">
					<div class="well">
						<div class="form-group">
						    
						    <label for="easysearchbar">간편검색</label>
						    <input type="hidden" name = "inputYear" value="">
						    <input type="hidden" name = "term" value="noneval">
						    <input type="hidden" name = "coursetype" value="noneval">
						    <input type="hidden" name = "dept" value="noneval">
						    <input type="hidden" name = "rating" value="0">
						   
						    <div class="row">
						    	<div class="col-md-3"> </div>
						    	<div class="col-md-6"> <input name="searchbar" type="text" class="form-control" id="easysearchbar" placeholder="강의명, 학수번호, 교수명으로 검색"> </div>
						    	<div class="col-md-3"> </div>
						    </div>
						   	
						 	<div class="row">
						   		<div class="col-md-5"> </div>
						 		<div class="col-md-2"><button style="margin-top:10px;" type="submit" class="btn btn-lg btn-primary btn-block">검색</button></div>
						 		<div class="col-md-5"> </div>
					  		</div>
					  

						</div>
					</div>
				</form>

				<form action="/search_result.php" method="get">
				<div class="well">
					<p style="margin-top:10px"> <strong>상세검색</strong> </p>

							<div class="row">
						    	<div class="col-md-3"> </div>
						    	<div class="col-md-6">
						    		<table style="width:100%">
						    		<tr>
						    		<td>
							    	<label for="years">년도 </label>
							    	<input name="inputYear" type="text" id="years" placeholder="년도" style="width:120px">
							    	</td>
							    	<td>
							    	<label for="terms">학기 </label>
									<select id="terms" name="term">
									  <option value="noneval"> - </option>
									  <option value="first">1학기</option>
									  <option value="second">2학기</option>
									  <option value="summer">여름학기</option>
									  <option value="winter">겨울학기</option>
									  <option value="ISS">국제하계대학</option>
									</select>
									</td>
									</tr>
									<tr>
									<td>
									<label for="terms">구분 </label>
									<select id="terms" name="coursetype">
									  <option value="noneval"> - </option>
									  <option value="major">전공</option>
									  <option value="non_major">비전공(교양)</option>
									</select>
									</td>
									<td>
									<label for="depts">학과</label>
									<select id="depts" name="dept">
									<option value="noneval"> - </option>
								    <?php
								    $dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234") or die('Could not connect: ' . pg_last_error());
								      $deptsquery = "select * from department";
									  $deptnames = pg_query($deptsquery) or die('Query failed: '.pg_last_error());
								      $deptsCount = pg_query("select count(*) from department") or die('Query failed: '.pg_last_error());
								      $deptsNum = pg_fetch_result($deptsCount,0,0);
								      for($i = 0; $i < $deptsNum; $i++) {
								      	$depts = pg_fetch_result($deptnames,$i,0);
									  echo "<option value=\"".$depts."\">".$depts."</option>";
									  }
									  pg_close($dbconn);
									 ?>
									</select>
									</td>
									</tr>
									</table>
									
										<div style="width:40%;margin-left:30%">
										<label for="ratings">평점</label>
										<input type="range" name="rating" id="ratings" min="0" max="5" value="0">
										</div>

							    	<input name="searchbar" type="text" class="form-control" id="hardsearchbar" placeholder="강의명, 학수번호, 교수명으로 검색">

						    	 </div>
						    	<div class="col-md-3"> </div>
						    </div>

							<div class="row">
						   		<div class="col-md-5"> </div>
						 		<div class="col-md-2"><button style="margin-top:10px;" type="submit" class="btn btn-lg btn-primary btn-block">검색</button></div>
						 		<div class="col-md-5"> </div>
					  		</div>

				</div>
				</form>
			</div>
			<div class="container" style="color:white;background-color:skyblue;width:70%;text-align:center">
				<p> DB 텀프로젝트 2012130888 김인호 </p>
			</div>
	
	<?php 
		}
	?>


	</body>
</html>
