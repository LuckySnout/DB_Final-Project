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
		<?php include("Header.php"); ?>

		<div id="content">
				<?php
					if ($usertype == "student") {
				?>
				<table style="table-layout: fixed">
				<tr>
					<th>Subject</th><th>Year-Semester</th><th>Grade</th><th>Evaluation</th>
				</tr>
				<?php  
				
						$db = mysqli_connect("0.0.0.0","hwaneeee","","c9");

						$query = "SELECT title, semester, year, grade, post_string, score_pro, score_ama, professor_name
									FROM (student join takes using(student_id)) join post using(post_id) join course using(course_id)
									join professor using(professor_id)
									WHERE student.student_name like '$username'"; 
						
						$result = mysqli_query($db,$query);
						if(count($result)) 
						{
							while($row = mysqli_fetch_assoc($result))
							{
								$title = $row['title'];
								$semester = $row['semester'];
								$year = $row['year'];
								$grade = $row['grade'];
								$post_string = $row['post_string'];
								$score_pro = $row['score_pro'];
								$score_ama = $row['score_ama'];
								$professor_name = $row['professor_name'];
	
								print "<tr><td>$title</td><td>$year"."-"."$semester</td><td>$grade</td>"?>
								<td style="word-break; break-all">
								<?php echo "$post_string</td><td>";
								?>
								<form action = "Score.php" method = "get">
									<input type="hidden" name="profname" value="<?php echo $professor_name?>"/>
									<input type="hidden" name="title" value="<?php echo $title?>"/>
									<input type="hidden" name="score_pro" value="<?php echo $score_pro?>"/>
									<input type="hidden" name="score_ama" value="<?php echo $score_ama?>"/>
									<input type="hidden" name="year" value="<?php echo $year?>"/>
									<input type="hidden" name="semester" value="<?php echo $semester?>"/>
			
									<button type="submit">Evaluation</button>
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
				if ($usertype == "professor") {
			?>
			<table>
				<tr>
					<th>Subject</th><th>Evaluation</th>
				</tr>
			<?php
					$db = mysqli_connect("0.0.0.0","hwaneeee","","c9");
					$query = "SELECT distinct title, semester, year, score_pro, score_ama
								FROM (professor join course using(professor_id)) join takes using(course_id) join post using(post_id) join student using(student_id)
								WHERE professor.professor_name like '$username';"; 
					$result = mysqli_query($db,$query) or die ('Query failed: '. mysqli_error());
					$row_numbers = mysqli_num_rows($result);
					if($row_numbers) {
						//for($i=0; $i<$row_numbers; $i++) 
						while($row=mysqli_fetch_assoc($result))
						{
							$title = $row[title];
							$semester = $row[semester];
							$year = $row[year];
							$score_pro = $row[score_pro];
							$score_ama = $row[score_ama];

							print "<tr><td>$title</td>";
							?>
							<form action = "Score.php" method = "get">
								<input type="hidden" name="profname" value="<?php echo $username?>"/>
								<input type="hidden" name="title" value="<?php echo $title?>"/>
								<input type="hidden" name="score_pro" value="<?php echo $score_pro?>"/>
								<input type="hidden" name="score_ama" value="<?php echo $score_ama?>"/>
								<input type="hidden" name="year" value="<?php echo $year?>"/>
								<input type="hidden" name="semester" value="<?php echo $semester?>"/>
								<td>
								<button type="submit">Evaluation</button>
								</td></tr>
							</form>
							<?php
						}
					}					
				}
			?>
			</table>
			<?php
				if ($usertype == "administer") {
			?>
				<div style = "width:300px";>
					<form action = "Administer.php" method = "get">
						<fieldset style = "text-align: left;">
							<legend><strong>Add Department</strong></legend>
							Department Name : <input type="text" name="dept_name"/> <br>
							Department Building : <input type="text" name="building"/> <br>
							<input type="hidden" name="addtype" value=0>
							<button type="submit">Addition</button>
						</fieldset>
					</form>
				</div>
				<br>
				<div style = "width:300px";>
					<form action = "Administer.php" method = "get">
						<fieldset style = "text-align: left;">
							<legend><strong>Add Students</strong></legend>
							Name : <input type="text" name="usersname"/> <br>
							Department :
							<select name="deptname">
								<?php
									$db = mysqli_connect("0.0.0.0","hwaneeee","","c9");
									$query = "SELECT dept_name from department;"; 
									$result = mysqli_query($db,$query) or die ('Query failed: '. mysqli_error());
									$row_numbers = mysqli_num_rows($result);
									if($row_numbers) {
										//for ($i=0; $i<$row_numbers; $i++) 
										while($row=mysqli_fetch_assoc($result))
										{
											$dept = $row[dept_name];
											echo "<option value='$dept'>$dept</option>";
										}
									}
								?>
							</select>
							<br>
							<input type="hidden" name="addtype" value=1>
							<button type="submit">Add</button>
						</fieldset>
					</form>
				</div>
				<div style = "width:300px";>
					<form action = "Administer.php" method = "get">
						<fieldset style = "text-align: left;">
							<legend><strong>Add Professor</strong></legend>
							Name : <input type="text" name="profname"/> <br>
							Department :
							<select name="deptname">
								<?php
									$db = mysqli_connect("0.0.0.0","hwaneeee","","c9");
									$query = "SELECT dept_name from department;"; 
									$result = mysqli_query($db,$query) or die ('Query failed: '. mysqli_error());
									$row_numbers = mysqli_num_rows($result);
									if($row_numbers) {
										//for ($i=0; $i<$row_numbers; $i++) 
										while($row=mysqli_fetch_assoc($result))
										{
											$dept = $row[dept_name];
											echo "<option value='$dept'>$dept</option>";
										}
									}
								?>
							</select>
							<br>
							<input type="hidden" name="addtype" value=2>
							<button type="submit">Add</button>
						</fieldset>
					</form>
				</div>
				<br>
				<div style = "width:300px";>
					<form action = "Administer.php" method = "get">
						<fieldset style = "text-align: left;">
							<legend><strong>Add Subject</strong></legend>
							Department :
							<select name="deptname">
								<?php
									$db = mysqli_connect("0.0.0.0","hwaneeee","","c9");
									$query = "SELECT dept_name from department;"; 
									$result = mysqli_query($db,$query) or die ('Query failed: '. mysqli_error());
									$row_numbers = mysqli_num_rows($result);
									if($row_numbers) {
										//for ($i=0; $i<$row_numbers; $i++) 
										while($row=mysqli_fetch_assoc($result))
										{
											$dept = $row[dept_name];
											echo "<option value='$dept'>$dept</option>";
										}
									}
								?>
							</select>
							<br>
							Professor Name : 
							<select name="professor_id">
								<?php
									$db = mysqli_connect("0.0.0.0","hwaneeee","","c9");
									$query = "SELECT professor_id, professor_name from professor order by professor_name;"; 
									$result = mysqli_query($db,$query) or die ('Query failed: '. mysqli_error());
									$row_numbers = mysqli_num_rows($result);
									if($row_numbers) {
										//for ($i=0; $i<$row_numbers; $i++) 
										while($row=mysqli_fetch_assoc($result))
										{
											$prof_id = $row[professor_id];
											$prof_name = $row[professor_name];
											echo "<option value='$prof_id'>$prof_name</option>";
										}
									}
								?>
							</select>
							<br>
							Subject : <input type="text" name="title"/> <br>
							Grade : 
							<select name="credit">
								<option value='1'>1</option>
								<option value='2'>2</option>
								<option value='3'>3</option>
							</select>
							<br>
							Available : <input type="range" name="max_number" min=40 max=100> <br>
							Type :
							<select name="coursetype">
								<option value='major'>major</option>
								<option value='liberal arts'>liberal arts</option>
							</select>
							<br>
	
							<input type="hidden" name="addtype" value=3>
							<button type="submit">Add</button>
						</fieldset>
					</form>
				</div>
			<?php
				}
			?>
		</div>
	</body>
</html>
