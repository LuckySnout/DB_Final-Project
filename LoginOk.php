<?php
if(!isset($_POST['Username'])) exit;
		$username = $_POST['Username'];
		$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=ghkstkd1")
		or die ('could not connext : '. pg_last_error());
		$query = "select user_name, user_type from users";
		$result = pg_query($query) or die ('Query failed: '. pg_last_error());
		$all_data = pg_fetch_all($result);
		$check = 0;
		$type_check = 0;
		for($i=0; $i < count($all_data); $i++) {
			$data = pg_fetch_result($result, $i, 0);
			if ($data == $username) {
				$check++;
				$usertype = pg_fetch_result($result, $i, 1);
			}
		}
		if ($check == 0) {
			echo "<script>alert('ID DOES NOT EXIST! TRY IT AGAIN!');document.location.href='Login.html';</script>";
			exit;
		}
		session_start();
		$_SESSION['username'] = $username;
		$_SESSION['usertype'] = $usertype;
		header ("Location:db_test.php");
?>