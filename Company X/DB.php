<?php

	$host = "";
	$database = "";
	$user = "";
	$pass = "";

	$conn = mysqli_connect($host, $user, $pass, $database) or die("Uable to connect with the DB " . mysqli_error($conn));

	$arr = array("Parts133","Clients133","Lines133","POs133","userSelected133");

	for ($i=0; $i <= sizeof($arr); $i++) { 
		$sql = "select * from $arr[$i]";
		$res = mysqli_query($conn, $sql);

		$store_array = array();

		while ($row = mysqli_fetch_assoc($res)) {
			$store_array[] = $row;
		}

		$json = json_encode($store_array, JSON_PRETTY_PRINT);

		if (file_put_contents("json_files/$arr[$i].json", $json)) {
		}
	}

	mysqli_close($conn);

?>