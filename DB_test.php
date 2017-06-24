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
				width: 400px;
				padding: 20px;
				margin-bottom: 20px;
				margin-left: auto;
				margin-right: auto;
				border: 1px solid #bcbcbc;
			}
		</style>
		<title>
			<?php echo $username."(".$usertype.")" ?>
		</title>
	</head>
	<body>
		<?php include("Header.php"); ?>
		<div id="content">
			<form action = "Next.php" method = "get">
				<fieldset style = "text-align: center;">
					<legend><strong><?php echo $username ?>, Hello!</strong></legend>
					Professor Name <input type="text" name="ProfName"/>
					<br>
					Subject Name  <input type="text" name="title"/>
					<br>
					Start Year
					<select name = "year">
						<option value = "nosel">None</option>
						<option value = "2017">2017</option>
						<option value = "2016">2016</option>
						<option value = "2015">2015</option>
					</select>
					<br>
					Start Semester
					<select name = "semester">
						<option value = "nosel">None</option>
						<option value = "1">1</option>
						<option value = "2">2</option>
					</select>
					<br>
					Accepting Student
					<select name = "accept">
						<option value = 40>40</option>
						<option value = 50>50</option>
						<option value = 60>60</option>
						<option value = 70>70</option>
						<option value = 80>80</option>
					</select>
					<br>
					GPA <input type="range" name="score" min="0" max="5">
					<br>
					<input type="submit" name="Submit" value="Search">
				</fieldset>
			</form>
		</div>
	</body>
</html>

<script>
	function checkAll() {
		var boxes = document.getElementsByName('semester[]');
		for (var i=0; i<boxes.length; i++) { boxes[i].checked=true; }
	}
	function uncheckAll() {
		var boxes = document.getElementsByName('semester[]');
		for (var i=0; i<boxes.length; i++) { boxes[i].checked=false; }
	}
</script>