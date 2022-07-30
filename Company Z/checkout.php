<?php

$host = "";
$database = "";
$user = "";
$pass = "";

$conn = mysqli_connect($host, $user, $pass, $database) or die("Uable to connect with the DB " . mysqli_error($conn));

$client_ID = $_POST['clientID'];

date_default_timezone_set('Halifax');
$date = date('Y-m-d H-i-s');

$sql = "INSERT INTO POsZ (poNoZ, datePOZ, statusZ, clientIDZ) VALUES (NULL, '$date','pending','$client_ID')";

$insert_id_posNO = 0;

if ($conn->query($sql) === TRUE) {
	$insert_id_posNO += mysqli_insert_id($conn);
}

$data_checkout = $_POST['datacheckout'];

$user_selected = $_POST['user_selected'];

//storing to the new array so that we can add to the X and Y comapny
$arrayXvalues = array();
$arrayYvalues = array();

$insertInNewArrayX = 0;
$insertInNewArrayY = 0;

for ($i=0; $i < sizeof($data_checkout); $i++) { 
	$compX = new stdClass();
	$compY = new stdClass();

	$countX = 0;
	$countY = 0;
	$tprice = 0;
	for ($j=0; $j < sizeof($user_selected); $j++) { 
		if ($data_checkout[$i]['partNoY'] != NULL && $data_checkout[$i]['partNoY'] != "undefined") {
			if (($data_checkout[$i]['partNoY']."Y") === $user_selected[$j]['dataIDZ'] && $client_ID === $user_selected[$j]['clientIDZ']) {
				$countY++;
			}
		}
		if ($data_checkout[$i]['partNo133'] != NULL && $data_checkout[$i]['partNo133'] != "undefined") {
			if (($data_checkout[$i]['partNo133']."X") === $user_selected[$j]['dataIDZ'] && $client_ID === $user_selected[$j]['clientIDZ']) {
				$countX++;
			}
		}
	}
	if ($countX > 0) {
		$tprice += $data_checkout[$i]['currentPrice133'];
		$data_id = $data_checkout[$i]['partNo133'];
		$companyName = 'Comapny X';

		$compX->data_id = $data_id;
		$compX->tprice = $tprice;
		$compX->countX = $countX;

		$arrayXvalues[$insertInNewArrayX] = $compX;

		$sql_secondX = "INSERT INTO LinesZ(lineNoZ, poNoZ, partNoZ, priceOrderedZ, qtyZ, companyName) VALUES (NULL, '$insert_id_posNO', '$data_id','$tprice', '$countX', '$companyName')";

		$conn->query($sql_secondX);
		$insertInNewArrayX++;
	}
	else if ($countY > 0) {
		$tprice += $data_checkout[$i]['currentPriceY'];
		$data_id = $data_checkout[$i]['partNoY'];
		$companyName = 'Comapny Y';

		$compY->data_id = $data_id;
		$compY->tprice = $tprice;
		$compY->countY = $countY;

		$arrayYvalues[$insertInNewArrayY] = $compY;

		$sql_secondY = "INSERT INTO LinesZ(lineNoZ, poNoZ, partNoZ, priceOrderedZ, qtyZ, companyName) VALUES (NULL, '$insert_id_posNO', '$data_id','$tprice', '$countY', '$companyName')";
		$conn->query($sql_secondY);
		$insertInNewArrayY++;
	}
}


// adding the sql for the X company

if (count($arrayXvalues) != 0) {
	$sql = "INSERT INTO POs133 (poNo133, datePO133, status133, clientID133) VALUES (NULL, '$date','pending','5')";
	$insert_id_posNOXC = 0;

	if ($conn->query($sql) === TRUE) {
		$insert_id_posNOXC += mysqli_insert_id($conn);
	}

	for ($p=0; $p < sizeof($arrayXvalues); $p++) { 
		$id = $arrayXvalues[$p]->data_id;
		$price = $arrayXvalues[$p]->tprice;
		$cx = $arrayXvalues[$p]->countX;
		$sql_secondXC = "INSERT INTO Lines133(lineNo133, poNo133, partNo133, priceOrdered133, qty133) VALUES (NULL,'$insert_id_posNOXC','$id','$price','$cx')";

		$conn->query($sql_secondXC);
	}
}


// adding the sql for the Y company
if (count($arrayYvalues) != 0) {
	$sql = "INSERT INTO POsY (poNoY, datePOY, statusY, clientIDY) VALUES (NULL, '$date','pending','1114')";
	$insert_id_posNOYC = 0;

	if ($conn->query($sql) === TRUE) {
		$insert_id_posNOYC += mysqli_insert_id($conn);
	}

	for ($q=0; $q < sizeof($arrayYvalues); $q++) { 
		$id = $arrayYvalues[$q]->data_id;
		$price = $arrayYvalues[$q]->tprice;
		$cy = $arrayYvalues[$q]->countY;

		$sql_secondYC = "INSERT INTO LinesY(lineNoY, poNoY, partNoY, priceOrderedY, qtyY) VALUES (NULL,'$insert_id_posNOYC','$id','$price','$cy')";
		$conn->query($sql_secondYC);
	}
}

// update the client info 
$clientdata = $_POST['clientdata'];
$clientMoneyhas = $_POST['clientMoneyhas'];

$checkout_Total_price = $_POST['checkout_Total_price'];

$clientMoneyOwned = $_POST['clientMoneyOwned'];

if ($clientdata == 1) {
	if ($checkout_Total_price > $clientMoneyhas) {
		$clientMoneyOwned = (($checkout_Total_price - (($checkout_Total_price*10)/100)) - $clientMoneyhas) ;
		$updateSql = "UPDATE ClientsZ SET dollarsOnOrderZ=0.00, moneyOwedZ='$clientMoneyOwned', DealsZ=2 WHERE clientIDZ = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else if ($checkout_Total_price == $clientMoneyhas) {
		$updateSql = "UPDATE ClientsZ SET dollarsOnOrderZ=0.00, moneyOwedZ=0.00, DealsZ=2 WHERE clientIDZ = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else{
		$clientMoneyhas = $clientMoneyhas - $checkout_Total_price;
		$updateSql = "UPDATE ClientsZ SET dollarsOnOrderZ='$clientMoneyhas', moneyOwedZ=0.00, DealsZ=2 WHERE clientIDZ = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
}
else{
	if ($checkout_Total_price > $clientMoneyhas) {
		$clientMoneyOwned = $checkout_Total_price - $clientMoneyhas;
		$updateSql = "UPDATE ClientsZ SET dollarsOnOrderZ=0.00, moneyOwedZ='$clientMoneyOwned', DealsZ=0 WHERE clientIDZ = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else if ($checkout_Total_price == $clientMoneyhas) {
		$updateSql = "UPDATE ClientsZ SET dollarsOnOrderZ=0.00, moneyOwedZ=0.00, DealsZ=0 WHERE clientIDZ = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else{
		$clientMoneyhas = $clientMoneyhas - $checkout_Total_price;
		$updateSql = "UPDATE ClientsZ SET dollarsOnOrderZ='$clientMoneyhas', moneyOwedZ=0.00, DealsZ=0 WHERE clientIDZ = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
}

$deleteSqlY = "DELETE FROM userSelectedZ WHERE clientIDZ = '$client_ID' ";

if ($conn->query($deleteSqlY) === TRUE) {
}

$conn->close();

?>