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
				$type = $_GET['postid'];
				$query = "update post set score_pro = null where post_id = '$type';";
				$result = mysqli_query($db,$query) or die('Query failed: '. mysqli_error());
				$query = "update post set score_ama = null where post_id = '$type';";
				$result = mysqli_query($db,$query) or die('Query failed: '. mysqli_error());
				$query = "update post set post_string = null where post_id = '$type';";
				$result = mysqli_query($db,$query) or die('Query failed: '. mysqli_error());
				echo "<script>alert('DELETE SUCCESS!');document.location.href='DB_test.php';</script>";
			?>
		</div>
	</body>
</html>
