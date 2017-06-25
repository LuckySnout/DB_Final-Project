<!DOCTYPE html>
<html>
	
	<?php
		session_start();	
		if(!isset($_SESSION['username'])) {
			header("Location:Login.php");
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
		<div id='content'>
			<table>
				<tr>
					<th>Professor</th><th>Subject</th><th>Credits</th><th>Available</th><th>Major GPA</th><th>Non-major GPA</th><th>Start Year</th><th>Start Semester</th><th></th>
				</tr>
				<br>
				<?php
				$db = mysqli_connect("0.0.0.0","hwaneeee","","c9");
				$ProfName = $_GET['ProfName'];
				$title = $_GET['title'];
				$year = $_GET['year'];
				$semester = $_GET['semester'];
				$accept = $_GET['accept'];
				$score = $_GET['score'];
				
		
				$query = "select distinct professor_name, title, credits, max_number, (pro_sum / pro_count), (ama_sum / ama_count), 
				year, semester
				from (select section.sec_id, sum(score_pro) as pro_sum, count(score_pro) as pro_count,
					sum(score_ama) as ama_sum, count(score_ama) as ama_count
					from takes join post using(post_id) join section using(sec_id)
					where section.sec_id = takes.sec_id group by section.sec_id) as avg_score
					join (((course) join (professor) using (professor_id)) join takes using(course_id)) using(sec_id)
				where (((pro_sum / pro_count) >= $score or (ama_sum / ama_count) >= $score) or pro_sum is null or ama_sum is null)
				and $accept <= max_number";
				
				if($year != "nosel") { $query = $query ." and year like '$year'"; }
				if($semester != "nosel") { $query = $query ." and semester like '$semester'"; }
				if($ProfName != "") { $query = $query ." and professor_name like '$ProfName'"; }
				if($title != "") { $query = $query ." and title like '%$title%'"; }
				$query = $query .";";
				$result = mysqli_query($db,$query);
				if(count($result)) {
					//for($i=0; $i<$row_numbers; $i++) 
					while($row=mysqli_fetch_array($result))
					{
						$professor_name = $row[0];
						$title = $row[1];
						$credit = $row[2];
						$max_number = $row[3];
						$score_pro = $row[4];
						$score_ama = $row[5];
						$year_val = $row[6];
						$semester_val = $row[7];
						print "<tr><td>$professor_name</td><td>$title</td><td>$credit</td><td>$max_number
						</td><td>$score_pro</td><td>$score_ama</td><td>$year_val</td><td>$semester_val</td>";
						?>
						<form action = "Score.php" method = "get">
							<input type="hidden" name="profname" value="<?php echo $professor_name?>"/>
							<input type="hidden" name="title" value="<?php echo $title?>"/>
							<input type="hidden" name="score_pro" value="<?php echo $score_pro?>"/>
							<input type="hidden" name="score_ama" value="<?php echo $score_ama?>"/>
							<input type="hidden" name="year" value="<?php echo $year_val?>"/>
							<input type="hidden" name="semester" value="<?php echo $semester_val?>"/>
							<td>
							<button type="submit">Evaluation</button>
							</td>
						</form>
						<?php
						echo "</tr>";
					}
				}
				
				?>
			</table>
		</div>
	</body>
</html>


