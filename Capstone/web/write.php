<?php

session_start();

if (!isset($_SESSION["I"])) {
	$_SESSION["er"]="You are NOT Authorized";
	header("Location:index.php");
}

if(isset($_POST["Send"])&&$_POST["Send"]==="Send")
{
	if(isset($_POST["Content"])&&isset($_POST["To"]))
	{
		require 'crypto.php';
		$encrypted = openssl_encrypt($_POST["Content"], $ciphering, $key, $options, $iv); 
		
		require 'conn.php';
		$sql_sel = "SELECT * FROM u WHERE N=:X";
		$stmt = $conn->prepare($sql_sel);
		$stmt->execute(array(
			':X' => $_POST['To']
		));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if(empty($row))
		{
			$_SESSION["er"]="no such contact name";
			header("Location:write.php");
		}
		else
		{
			$sql_ins = "INSERT INTO m (T,F,C) VALUES (:X,:Y,:Z)";
			$stmt=$conn->prepare($sql_ins);
			$stmt->execute(array(
				':X' => $row["I"],
				':Y' => $_SESSION["I"],
				':Z' => $encrypted
			));
			$_SESSION["msg"]="message sent seccessfully";
			header("Location:write.php");
		}
	}
	else
	{
		$_SESSION["er"]="please fill all the fields";
		header("Location:write.php");
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Compose</title>
</head>
<body>
	<div>
		<h1>Compose a Message</h1>
		<a href="home.php">Back to Home</a>
		<p>please fill both fields before you click Send</p>

<?php
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

		<form method="post">
			<label>To: <input type="text" name="To"></label>
			<label>Content: <input type="text" name="Content"></label>
			<input type="submit" name="Send" value="Send">			
		</form>
	</div>
</body>
</html>