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
	<body>
		<?php include("Header.php"); ?>
		<div>
			<?php
				$db = mysqli_connect("0.0.0.0","hwaneeee","","c9")
				or die ('could not connext : '. mysqli_error());
				$type = $_GET['addtype'];
				if ($type == 0) {
					$dept_name = $_GET['dept_name'];
					$building = $_GET['building'];
					$query = "select dept_name from department;";
					$result = mysqli_query($db,$query) or die('Query failed: '. mysqli_error());
					$row_numbers = mysqli_num_rows($result);
					$already_exist = 0;
					if($row_numbers) {
						while($row = mysqli_fetch_assoc($result)) {
							$dn = $row['dept_name'];
							if ($dn == $dept_name) { $already_exist = 1; }
						}
					}
					
					if(!$already_exist) {
						$query = "insert into department values ('$dept_name', '$building');";
						$result = mysqli_query($db,$query) or die('Query failed: '. mysqli_error());
						echo "<script>alert('ADD SUCCESS!');document.location.href='Mypage.php';</script>";
					}
					else if($already_exist) {
						echo "<script>alert('ALREADY EXIST!');document.location.href='Mypage.php';</script>";
					}
				}
				else if ($type == 1) {
					$usersname = $_GET['usersname'];
					$deptname = $_GET['deptname'];
					if ($usersname == null) { echo "<script>alert('FAILED!');document.location.href='Mypage.php';</script>"; }
					$query = "select count(*) from student;";
					$result = mysqli_query($db,$query) or die('Query failed: '. mysqli_error($db));
					$row = mysqli_fetch_array($result);
					$student_number = $row[0] + 1;
					$query = "insert into student values ('$student_number', '$usersname', '$deptname');";
					$result = mysqli_query($db,$query) or die('Query failed: '. mysqli_error());
					$query = "insert into users values ('$usersname','student','$student_number',NULL);";
					$result = mysqli_query($db,$query) or die('Query failed: '. mysqli_error());
					echo "<script>alert('ADD SUCCESS!');document.location.href='Mypage.php';</script>";
				}
				else if ($type == 2) {
					$profname = $_GET['profname'];
					$deptname = $_GET['deptname'];
					if ($profname == null) { echo "<script>alert('FAILED!');document.location.href='Mypage.php';</script>"; }
					$query = "select count(*) from professor;";
					$result = mysqli_query($db,$query) or die('Query failed: '. mysqli_error());
					$row = mysqli_fetch_array($result);
					$student_number = $row[0] + 1;
					$query = "insert into professor values ('$student_number', '$profname', '$deptname');";
					$result = mysqli_query($db,$query) or die('Query failed: '. mysqli_error());
					$query = "insert into users values ('$profname','professor',NULL,'$student_number');";
					$result = mysqli_query($db,$query) or die('Query failed: '. mysqli_error());
					echo "<script>alert('ADD SUCCESS!');document.location.href='Mypage.php';</script>";
				}
				else if ($type == 3) {
					$deptname = $_GET['deptname'];
					$profid = $_GET['professor_id'];
					$title = $_GET['title'];
					$max_number = $_GET['max_number'];
					$credit = $_GET['credit'];
					$coursetype = $_GET['coursetype'];
					if ($title == null) { echo "<script>alert('FAILED!');document.location.href='Mypage.php';</script>"; }
					if ($max_number == null) { echo "<script>alert('FAILED!');document.location.href='Mypage.php';</script>"; }
					$query = "select count(*) from course;";
					$result = mysqli_query($db,$query) or die('Query failed: '. mysqli_error());
					$row = mysqli_fetch_array($result);
					$student_number = $row[0] + 1;
					$query = "insert into course values ('$next_number', '$title', '$deptname', '$credit', '$profid', $max_number, '$coursetype');";
					$result = mysqli_query($db, $query) or die('Query failed: '. mysqli_error());
					echo "<script>alert('ADD SUCCESS!');document.location.href='Mypage.php';</script>";
				}

			?>
		</div>
	</body>
</html>
