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
	<body>
		<?php include("Header.php"); ?>
		<div>
			<?php
				$db = mysqli_connect("0.0.0.0","hwaneeee","","c9");
				$new_string = $_GET['post_string'];
				$new_score = $_GET['score'];
				$score_type = $_GET['score_type'];
				$post_id = $_GET['post_id'];
				$title = $_GET['title'];
				$query = "select course_id from course where title like '$title';";
				$result = mysqli_query($db,$query);
				$row = mysqli_fetch_assoc($result);
				$course = $row[0];
				if($new_string != null) {
					$query = "update post set post_string = '$new_string' where post_id = '$post_id';";
					$result = mysqli_query($db,$query);
					$query = "update post set score_ama = $new_score where post_id = '$post_id';";					
					if ($score_type == 1) { $query = "update post set score_pro = $new_score where post_id = '$post_id';"; }
					$result = mysqli_query($db,$query);
				}
				header ("Location:DB_test.php");
			?>
		</div>
	</body>
</html>
