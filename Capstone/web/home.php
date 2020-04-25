<?php
session_start();

if (!isset($_SESSION["I"])) {
	$_SESSION["er"]="You are NOT Authorized";
	header("Location:index.php");
}

require 'conn.php';
$sql_sel = "SELECT * FROM m WHERE T=:X";
$stmt = $conn->prepare($sql_sel);
$stmt->execute(array(
	':X' => $_SESSION["I"]
));

?>

<!DOCTYPE html>
<html>
<head>
	<title>Message-System</title>
</head>
<body>
	<div>
		<h1>Welcome, this is your page</h1>

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

		<a href="write.php">Send a Message</a>
		<a href="Logout.php">Logout</a>
		<h3>My Inbox</h3>
		<div>
			<ol>
<?php 	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$sql_sel_u = "SELECT * FROM u WHERE I=:X";
			$stmt_u = $conn->prepare($sql_sel_u);
			$stmt_u->execute(array(
				':X' => $row["F"]
			));
			$row_u = $stmt_u->fetch(PDO::FETCH_ASSOC);
		 ?>
				<li><a href="read.php?MID=<?=$row["I"]?>">Message from <?=$row_u["N"]?></a></li>
<?php
		} ?>
			</ol>
		</div>
	</div>
</body>
</html>