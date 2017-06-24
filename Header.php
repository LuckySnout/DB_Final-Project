<!DOCTYPE html>
<html>
	<head>
		<style>
			#sidebar {
				width: 200px;
				padding: 20px;
				margin-bottom: 20px;
				float: left;
				border: 1px solid #bcbcbc;
				background-color: #dbf2fe;
			}
		</style>
	</head>
	<body>
		<div id="sidebar">
			<a style="font-size:25pt; font-style:bold"><?php echo $_SESSION['username']."(".$_SESSION['usertype'].")"?></a>
			<ul>
				<p><a href = 'Logout.php' style="font-size:15pt; font-style:bold">Logout</a></p>
				<p><a href = 'Mypage.php' style="font-size:15pt; font-style:bold">Mypage</a></p>
				<p><a href = 'DB_test.php' style="font-size:15pt; font-style:bold">Searching</a></p>
			</ul>
		</div>
	</body>
</html>