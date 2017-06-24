<!DOCTYPE html>
<html>
	<?php
		session_start();	
		if(!isset($_SESSION['username'])) {
			header("Location:login.php");
			exit;
		}
		$username = $_SESSION['username'];
		$usertype = $_SESSION['usertype'];
	?>
	<head> 
		<style>
			#content {
				width: 800px;
				padding: 20px;
				margin-bottom: 20px;
				margin-left: auto;
				margin-right: auto;
				border: 0px solid #bcbcbc;
			}
			table {
				width:100%;
				margin:15px 0;
				border:0;
			}
			th {
				background-color:#93DAFF;
				color:#000000
			}
			table, th, td {
				font-size:0.95em;
				text-align:center;
				padding:4px;
				border-collapse:collapse;
			}
			th, td {
				border: 1px solid #c1e9fe;
				border-width:1px 0 1px 0
			}
			tr {
				border: 1px solid #c1e9fe;
			}
			tr:nth-child(odd){
				background-color:#dbf2fe;
			}
			tr:nth-child(even){
				background-color:#fdfdfd;
			}
		</style>

		<title>
			<?php echo $username."(".$usertype.")" ?>
		</title>
	</head>
	<body>
		<?php include("header.php"); ?>

		<div id="content">
				<?php
					if ($usertype == "학생") {
				?>
				<table style="table-layout: fixed">
				<tr>
					<th>과목명</th><th>연도-학기</th><th>학점</th><th>평가 내용</th>
				</tr>
				<?php
						$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=ghkstkd1")
						or die ('could not connext : '. pg_last_error());
						$query = "SELECT title, semester, year, grade, post_string, score_pro, score_ama, professor_name
									FROM (student join takes using(student_id)) join post using(post_id) join course using(course_id)
									join professor using(professor_id)
									WHERE student.student_name like '$username';"; 
						$result = pg_query($query) or die ('Query failed: '. pg_last_error());
						$row_numbers = pg_num_rows($result);
						if($row_numbers) {
							for($i=0; $i<$row_numbers; $i++) {
								$title = pg_fetch_result($result, $i, 0);
								$semester = pg_fetch_result($result, $i, 1);
								$year = pg_fetch_result($result, $i, 2);
								$grade = pg_fetch_result($result, $i, 3);
								$post_string = pg_fetch_result($result, $i, 4);
								$score_pro = pg_fetch_result($result, $i, 5);
								$score_ama = pg_fetch_result($result, $i, 6);
								$professor_name = pg_fetch_result($result, $i, 7);
	
								print "<tr><td>$title</td><td>$year"."-"."$semester</td><td>$grade</td>"?>
								<td style="word-break; break-all">
								<?php echo "$post_string</td><td>";
								?>
								<form action = "score.php" method = "get">
									<input type="hidden" name="profname" value="<?php echo $professor_name?>"/>
									<input type="hidden" name="title" value="<?php echo $title?>"/>
									<input type="hidden" name="score_pro" value="<?php echo $score_pro?>"/>
									<input type="hidden" name="score_ama" value="<?php echo $score_ama?>"/>
									<input type="hidden" name="year" value="<?php echo $year?>"/>
									<input type="hidden" name="semester" value="<?php echo $semester?>"/>
			
									<button type="submit">평가</button>
								</td></tr>
								</form>
	
								<?php
							}
						}
						?>
						</table>
						<?php
					}
					?>
			<?php
				if ($usertype == "교수") {
			?>
			<table>
				<tr>
					<th>과목명</th><th>평가보기</th>
				</tr>
			<?php
					$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=ghkstkd1")
					or die ('could not connext : '. pg_last_error());
					$query = "SELECT distinct title, semester, year, score_pro, score_ama
								FROM (professor join course using(professor_id)) join takes using(course_id) join post using(post_id) join student using(student_id)
								WHERE professor.professor_name like '$username';"; 
					$result = pg_query($query) or die ('Query failed: '. pg_last_error());
					$row_numbers = pg_num_rows($result);
					if($row_numbers) {
						for($i=0; $i<$row_numbers; $i++) {
							$title = pg_fetch_result($result, $i, 0);
							$semester = pg_fetch_result($result, $i, 1);
							$year = pg_fetch_result($result, $i, 2);
							$score_pro = pg_fetch_result($result, $i, 3);
							$score_ama = pg_fetch_result($result, $i, 4);

							print "<tr><td>$title</td>";
							?>
							<form action = "score.php" method = "get">
								<input type="hidden" name="profname" value="<?php echo $username?>"/>
								<input type="hidden" name="title" value="<?php echo $title?>"/>
								<input type="hidden" name="score_pro" value="<?php echo $score_pro?>"/>
								<input type="hidden" name="score_ama" value="<?php echo $score_ama?>"/>
								<input type="hidden" name="year" value="<?php echo $year?>"/>
								<input type="hidden" name="semester" value="<?php echo $semester?>"/>
								<td>
								<button type="submit">평가보기</button>
								</td></tr>
							</form>
							<?php
						}
					}					
				}
			?>
			</table>
			<?php
				if ($usertype == "관리") {
			?>
				<div style = "width:300px";>
					<form action = "administer_ok.php" method = "get">
						<fieldset style = "text-align: left;">
							<legend><strong>학과 추가</strong></legend>
							학과이름 : <input type="text" name="dept_name"/> <br>
							학과건물 : <input type="text" name="building"/> <br>
							<input type="hidden" name="addtype" value=0>
							<button type="submit">추가</button>
						</fieldset>
					</form>
				</div>
				<br>
				<div style = "width:300px";>
					<form action = "administer_ok.php" method = "get">
						<fieldset style = "text-align: left;">
							<legend><strong>학생 추가</strong></legend>
							학생이름 : <input type="text" name="usersname"/> <br>
							학과이름 :
							<select name="deptname">
								<?php
									$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=ghkstkd1")
									or die ('could not connext : '. pg_last_error());
									$query = "SELECT dept_name from department;"; 
									$result = pg_query($query) or die ('Query failed: '. pg_last_error());
									$row_numbers = pg_num_rows($result);
									if($row_numbers) {
										for ($i=0; $i<$row_numbers; $i++) {
											$dept = pg_fetch_result($result, $i, 0);
											echo "<option value='$dept'>$dept</option>";
										}
									}
								?>
							</select>
							<br>
							<input type="hidden" name="addtype" value=1>
							<button type="submit">추가</button>
						</fieldset>
					</form>
				</div>
				<div style = "width:300px";>
					<form action = "administer_ok.php" method = "get">
						<fieldset style = "text-align: left;">
							<legend><strong>교수 추가</strong></legend>
							교수이름 : <input type="text" name="profname"/> <br>
							학과이름 :
							<select name="deptname">
								<?php
									$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=ghkstkd1")
									or die ('could not connext : '. pg_last_error());
									$query = "SELECT dept_name from department;"; 
									$result = pg_query($query) or die ('Query failed: '. pg_last_error());
									$row_numbers = pg_num_rows($result);
									if($row_numbers) {
										for ($i=0; $i<$row_numbers; $i++) {
											$dept = pg_fetch_result($result, $i, 0);
											echo "<option value='$dept'>$dept</option>";
										}
									}
								?>
							</select>
							<br>
							<input type="hidden" name="addtype" value=2>
							<button type="submit">추가</button>
						</fieldset>
					</form>
				</div>
				<br>
				<div style = "width:300px";>
					<form action = "administer_ok.php" method = "get">
						<fieldset style = "text-align: left;">
							<legend><strong>과목 추가</strong></legend>
							학과이름 :
							<select name="deptname">
								<?php
									$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=ghkstkd1")
									or die ('could not connext : '. pg_last_error());
									$query = "SELECT dept_name from department;"; 
									$result = pg_query($query) or die ('Query failed: '. pg_last_error());
									$row_numbers = pg_num_rows($result);
									if($row_numbers) {
										for ($i=0; $i<$row_numbers; $i++) {
											$dept = pg_fetch_result($result, $i, 0);
											echo "<option value='$dept'>$dept</option>";
										}
									}
								?>
							</select>
							<br>
							교수이름 : 
							<select name="professor_id">
								<?php
									$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=ghkstkd1")
									or die ('could not connext : '. pg_last_error());
									$query = "SELECT professor_id, professor_name from professor order by professor_name;"; 
									$result = pg_query($query) or die ('Query failed: '. pg_last_error());
									$row_numbers = pg_num_rows($result);
									
									if($row_numbers) {	
										for ($i=0; $i<$row_numbers; $i++) {
											$prof_id = pg_fetch_result($result, $i, 0);
											$prof_name = pg_fetch_result($result, $i, 1);
											echo "<option value='$prof_id'>$prof_name</option>";
										}
									}
								?>
							</select>
							<br>
							과목이름 : <input type="text" name="title"/> <br>
							학점 : 
							<select name="credit">
								<option value='1'>1</option>
								<option value='2'>2</option>
								<option value='3'>3</option>
							</select>
							<br>
							최대수용인원 : <input type="range" name="max_number" min=40 max=100> <br>
							유형 :
							<select name="coursetype">
								<option value='전공'>전공</option>
								<option value='교양'>교양</option>
							</select>
							<br>
	
							<input type="hidden" name="addtype" value=3>
							<button type="submit">추가</button>
						</fieldset>
					</form>
				</div>
			<?php
				}
			?>
		</div>
	</body>
</html>
