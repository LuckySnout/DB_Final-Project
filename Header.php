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
				<p><a href = 'Logout.php' style="font-size:15pt; font-style:bold">로그아웃</a></p>
				<p><a href = 'MyPage.php' style="font-size:15pt; font-style:bold">마이페이지</a></p>
				<p><a href = 'DB_test.php' style="font-size:15pt; font-style:bold">검색</a></p>
			</ul>
		</div>
	</body>
</html>