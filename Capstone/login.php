<?php

session_start();

if(isset($_SESSION["I"]))
{
	$_SESSION["msg"]="you are already logged in, if you want to login as a different user please logout first";
	header("Location:home.php");
}

if(isset($_POST["Login"])&&$_POST["Login"]==="Login")
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
		$sql_sel = "SELECT * FROM u WHERE N=:X AND P=:Y";
		$stmt = $conn->prepare($sql_sel);
		$stmt->execute(array(
			':X' => $_POST['Username'],
			':Y' => $pass
		));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if(!empty($row))
		{
			$_SESSION["I"]=$row["I"];
			header("Location:home.php");
		}
		else
		{
			$_SESSION["er"]="Incorrect information, Please try again";
			header("Location:login.php");			
		}
	}
	else
	{
		$_SESSION["er"]="please fill all the fields";
		header("Location:Login.php");
	}

}



elseif (isset($_GET)) { ?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>
	<div>
		<h1>Login</h1>
		<a href="index.php">Back</a>

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
			<p><label>Username<input type="text" name="Username" placeholder="Username"></label></p>
			<p style="display: inline-block;">Password Type</p>
			<label><input type="radio" name="Password_Type" id="Regular_Radio" value="Regular">Regular</label>
			<label><input type="radio" name="Password_Type" id="Advised_Radio" value="Advised">Advised</label>
			<div id="Regular_Div">
				<label>Password<input type="Password" name="Password"></label>		
			</div>
			<div id="Advised_Div">
				<input type="Password" name="First">-<input type="Password" name="Second">-<input type="Password" name="Third">
			</div>
			<input type="submit" name="Login" value="Login">
		</form>
	</div>
</body>
</html>
<?php } ?>