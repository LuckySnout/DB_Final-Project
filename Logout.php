<!DOCTYPE html>
<html>
	<?php
		session_start();
		session_destroy();
		header("Location:DB_test.php");
	?>
</html>