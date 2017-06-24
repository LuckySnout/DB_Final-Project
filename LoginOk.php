<!DOCTYPE html>
<html>
	<?php
		include("Connect.php");
		session_start();

		if(!isset($_POST['Username'])) exit;
		
		$IDname = $_POST['Username'];
		$query = "SELECT * FROM users WHERE user_name = '$IDname'";
		$result = mysqli_query($db,$query);

		//$all_data = mysqli_fetch_all($result);
		//printf("%d\n",count($all_data));
		//printf("%s\n",$IDname);
		
		//printf("%d\n",!$row);
		
		$check = 0;
		while($row = mysqli_fetch_assoc($result))
		{
			printf("Wow\n");
			printf("%d",$check);
			if($row['user_name']==$IDname)
			{
				$check++;
				$IDname = $row['user_name'];
				$usertype = $row['user_type'];
			}
		}
		if ($check == 0) {
			echo "<script>alert('ID DOES NOT EXIST! TRY IT AGAIN!');document.location.href='Login.php';</script>";
			exit;
		}
		session_start();
		$_SESSION['username'] = $IDname;
		$_SESSION['usertype'] = $usertype;
		header ("Location:DB_test.php");
	?>
</html>
