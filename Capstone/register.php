<?php

if (isset($_POST["Register"])&&$_POST["Register"]==="Register")
{
	if(isset($_POST["Username"])&&
		($_POST["Password_Type"]==="Regular"&&isset($_POST["Password"]))||
		($_POST["Password_Type"]==="Advised"&&isset($_POST["First"])&&isset($_POST["Second"])&&isset($_POST["Third"])))
	{
		if($_POST["Password_Type"]==="Regular"&&isset($_POST["Password"]))
		{
			$pass=sha1($_POST["Password"]);
		}
		elseif($_POST["Password_Type"]==="Advised"&&isset($_POST["First"])&&isset($_POST["Second"])&&isset($_POST["Third"]))
		{
			$pass=sha1($_POST["First"].$_POST["Second"].$_POST["Third"]);
		}

		require 'conn.php';

		$sql_sel = "SELECT * FROM u WHERE N=:X";
		$stmt = $conn->prepare($sql_sel);
		$stmt->execute(array(
			':X' => $_POST['Username'],
		));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if(isset($row))
		session_start();
		$_SESSION["er"]="name is already taken";
		header("Location:register.php");


		$sql_ins = "INSERT INTO u (N,P) VALUES (:X,:Y)";
		$stmt=$conn->prepare($sql_ins);
		$stmt->execute(array(
			':X' => $_POST['Username'],
			':Y' => $pass
		));

		session_start();
		$_SESSION["msg"]="You have been registered successfully, you can login now";
		header("Location:login.php");
	}
	else
	{
		session_start();
		$_SESSION["er"]="please fill all the fields";
		header("Location:register.php");
	}
}


elseif (isset($_GET)) {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
</head>
<body>
	<div>
		<h1>This Is Your Registration Page</h1>
		<a href="index.php">Back</a>

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
		<form method="post">
			<label>Username<input type="text" name="Username" placeholder="Username"></label>
			<p>to help you chose a secure and easy-to-remember password we you have two ways to select a password:</p>
			<label><input type="radio" name="Password_Type" id="Regular_Radio" value="Regular">Regular</label>
			<label><input type="radio" name="Password_Type" id="Advised_Radio" value="Advised">Advised</label>
			<div id="Regular_Div">
				<p>please select a password that contains at least 8 characters, include numbers,capital and small letter characters, and special characters</p>
				<label>Password<input type="Password" name="Password" placeholder=""></label>		
			</div>
			<div id="Advised_Div">
				<p>We have developed a new password methode, please fill in the following three spaces with a name, a number and any additional field of you choice. we advise you pick something that is meaningful to you but unknown by other people.</p>
				<input type="Password" name="First">-<input type="Password" name="Second">-<input type="Password" name="Third">
			</div>
			<input type="submit" name="Register" value="Register">
		</form>
	</div>
</body>
</html>
<?php } ?>