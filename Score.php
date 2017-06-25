<!DOCTYPE html>
<html>
	<head>
		<?php
			session_start();	
			if(!isset($_SESSION['username'])) {
				header("Location:Login.php");
				exit;
			}
			$username = $_SESSION['username'];
			$usertype = $_SESSION['usertype'];
		?>
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
		<div id = 'content'>
			<table>
				<tr>
					<td><?php echo $_GET['profname']."by Professor ".$_GET['title']; ?></td>
				</tr>
			</table>
			<table>
				<tr>
					<th width="100">Name</th><th width="30">Grade</th><th>Evaluation</th><th></th>
				</tr>
				<?php
					$profname = $_GET['profname'];
					$title = $_GET['title'];
					$year = $_GET['year'];
					$semester = $_GET['semester'];
					$db = mysqli_connect("0.0.0.0","hwaneeee","","c9");
					$query = "select student_name, post_string, score_pro, score_ama, post_id
							from (takes join course using(course_id)) join student using(student_id) join professor using(professor_id)
							join post using(post_id)
							where professor_name like '$profname' and title like '$title' and year like '$year'
							and semester like '$semester' and not (score_pro is null and score_ama is null);";
					$result = mysqli_query($db,$query);
					$row_numbers = mysqli_num_rows($result);
					if ($row_numbers) {
						while($row = mysqli_fetch_assoc($result)) {
							$std_name = $row['student_name'];
							$post_str = $row['post_string'];
							$score1 = $row['score_pro'];
							$score2 = $row['score_ama'];
							$pid = $row['post_id'];
							if ($score1 == null) { $score1 = 0; }
							if ($score2 == null) { $score2 = 0; }
							echo "<tr><td>$std_name</td><td>$score1"."("."$score2".")"."</td><td>$post_str</td><td>";
							if ($usertype == "administer") {
								?>
								<form action = "Score_Delete.php" method = "get">
									<input type="hidden" name="postid" value="<?php echo $pid; ?>"/>
									<button type="submit">Delete</button>
								</form>
								<?php
							}
								
							echo "</td></tr>";
						}
					}
				?>
			</table>
			<?php
				$query = "select student.dept_name, course.dept_name, post_id, title
						from (takes join course using(course_id)) join student using(student_id) join professor using(professor_id)
						where student_name like '$username' and professor_name like '$profname'
						and title like '$title' and year like '$year' and semester like '$semester';";
				$result = mysqli_query($db,$query);
				$row_numbers = mysqli_num_rows($result);
				$post_id = null;
				$my_scorepro = null;
				$my_scoreama = null;
				$my_post = null;
				$row = mysqli_fetch_assoc($result);
				if($row_numbers) {
					$student_dept = $row['student.dept_name'];
					$course_dept = $row['course.dept_name'];
					$post_id = $row['post_id'];
					$score_type = 0;
					if ($student_dept == $course_dept) { $score_type = 1; }
					if ($post_id != null) {
						$sub_query = "select post_string, score_pro, score_ama from post where post_id like '$post_id';";
						$sub_result = mysqli_query($db,$sub_query);
						$sub_row = mysqli_fetch_assoc($sub_result);
						$my_scorepro = $sub_row['score_pro'];
						$my_scoreama = $sub_row['score_ama'];
						$my_post = $sub_row['post_string'];
					}
					if ($usertype == "student") {
					?>
					<br>
					<table>
						<tr>
							<td style="font-size:15pt; font-weight:bold"><?php echo "$my_scorepro $my_scoreama" ?></td>
							<td style="background-color: #fdfdfd; height:50px"><?php echo $my_post?></td>
							<td></td>
						</tr>
						<tr>
						<form action = "Score_Ok.php" method = "get">
							<td>
							<a style="font-size:15pt; font-weight:bold">Grade</a>
							<select name="score" style="font-size:15pt">
								<option value=5>5</option>
								<option value=4>4</option>
								<option value=3>3</option>
								<option value=2>2</option>
								<option value=1>1</option>
								<option value=0>0</option>
							</select>
							</td>
							<td style="background-color: #dbf2fe">
								<textarea rows="5" cols = "30" name = "post_string" style="text-align: left; border:1px; word-break; break-all;"></textarea>
							</td>
							<input type="hidden" name="profname" value="<?php echo $post_id ?>"/>
							<input type="hidden" name="title" value="<?php echo $title ?>"/>
							<input type="hidden" name="year" value="<?php echo $year ?>"/>
							<input type="hidden" name="semester" value="<?php echo $semester ?>"/>
							<input type="hidden" name="post_id" value="<?php echo $post_id ?>"/>
							<input type="hidden" name="score_type" value="<?php echo $score_type ?>"/>

							<td>
							<button type="submit" style="height:50px; font-size:15pt; color:#ff0000; font-weight:bold; border-color: #ff0000">Evaluation</button>
							</td>
						</form>
						</tr>
					<?php
					}
				}
				else {
					if ($usertype == "student") {
						printf ("Cannot Evaluate\n");
					}
				}
			?>
		</div>
	</body>
</html>
