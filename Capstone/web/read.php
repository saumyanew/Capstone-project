<?php

session_start();

if (!isset($_SESSION["I"])) {
	$_SESSION["er"]="You are NOT Authorized";
	header("Location:index.php");
}
if(!isset($_GET["MID"]))
{
	$_SESSION["er"]="Please chose a message first";
	header("Location:home.php");	
}
if(isset($_GET["MID"]))
{
	require 'conn.php';
	$sql_sel_u = "SELECT * FROM u WHERE I=:X";
	$stmt = $conn->prepare($sql_sel_u);
	$stmt->execute(array(
		':X' => $_SESSION["I"]
	));
	$row_u = $stmt->fetch(PDO::FETCH_ASSOC);

	$sql_sel = "SELECT * FROM m WHERE I=:X";
	$stmt = $conn->prepare($sql_sel);
	$stmt->execute(array(
		':X' => $_GET["MID"]
	));
	if(!empty($row = $stmt->fetch(PDO::FETCH_ASSOC)))
	{
		if($row["T"]!==$_SESSION["I"])
		{
		$_SESSION["er"]="Please chose a message to read if you want";
		header("Location:home.php");	
		}
		elseif($row["T"]===$_SESSION["I"])
		{
			require 'crypto.php';
			$decrypted=openssl_decrypt ($row["C"], $ciphering, $key, $options, $iv); 

		///////////////////////
////		MESSAGES NEED TO BE DECRYPTED
		///////////////////////

?>

<!DOCTYPE html>
<html>
<head>
	<title>Message</title>
</head>
<body>
	<div>
		<a href="home.php">Back</a>

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

		<h5>from:  </h5><span><?=$row_u["N"]?></span>
		<h5>content:</h5>
		<p><?=$decrypted?></p>		
	</div>
</body>
</html>

<?php
		}
	}
}
?>