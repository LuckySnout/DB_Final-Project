<!DOCTYPE html>
<html>
	<?php
		session_start();
		session_destroy();
		header("Location:db_test.php");
	?>
</html>