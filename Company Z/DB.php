<?php

	$host = "";
	$database = "";
	$user = "";
	$pass = "";

	$conn = mysqli_connect($host, $user, $pass, $database) or die("Uable to connect with the DB " . mysqli_error($conn));

	$arr = array("ClientsZ","LinesZ","POsZ","userSelectedZ");

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

//-----------------------------------------------------------------------------------//


	$arrY = array("PartsY","ClientsY","LinesY","POsY","userSelectedY");

	for ($i=0; $i <= sizeof($arrY); $i++) { 
		$sqlY = "select * from $arrY[$i]";
		$resY = mysqli_query($conn, $sqlY);

		$store_arrayY = array();

		while ($rowY = mysqli_fetch_assoc($resY)) {
			$store_arrayY[] = $rowY;
		}

		$jsonY = json_encode($store_arrayY, JSON_PRETTY_PRINT);

		if (file_put_contents("json_files/$arrY[$i].json", $jsonY)) {
		}
	}

//-----------------------------------------------------------------------------------//

	$arrX = array("Parts133","Clients133","Lines133","POs133","userSelected");

	for ($i=0; $i <= sizeof($arrX); $i++) { 
		$sqlX = "select * from $arrX[$i]";
		$resX = mysqli_query($conn, $sqlX);

		$store_arrayX = array();

		while ($rowX = mysqli_fetch_assoc($resX)) {
			$store_arrayX[] = $rowX;
		}

		$jsonX = json_encode($store_arrayX, JSON_PRETTY_PRINT);

		if (file_put_contents("json_files/$arrX[$i].json", $jsonX)) {
		}
	}

	mysqli_close($conn);

?>