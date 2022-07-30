<?php 
	$host = "";
	$database = "";
	$user = "";
	$pass = "";

	$conn = mysqli_connect($host, $user, $pass, $database) or die("Uable to connect with the DB " . mysqli_error($conn));

	$data = $_POST['dataname'];

	$updateSql = "UPDATE ClientsY SET DealsY = 1 WHERE clientIDY = $data";

	if ($conn->query($updateSql) === TRUE) {
	}

	$conn->close();
?>