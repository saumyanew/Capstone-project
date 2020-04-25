<?php

?>

<!DOCTYPE html>
<html>
<head>
	<title>Aliloya Messaging Platform</title>
</head>
<body>
	<h1>Aliloya Messaging Application</h1>

<?php
	session_start();
	if(isset($_SESSION["er"]))
	{ ?>
		<p style="color: red"><?=$_SESSION["er"]?></p>
<?php	unset($_SESSION["er"]);
	}
	if(isset($_SESSION["msg"]))
	{ ?>
		<p style="color: green"><?=$_SESSION["msg"]?></p>
<?php	unset($_SESSION["msg"]);
	}
?>

	<a href="register.php">Register</a>
	<a href="login.php">Login</a>
</body>
</html>
