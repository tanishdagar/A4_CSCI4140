<?php

$host = "";
$database = "";
$user = "";
$pass = "";

$conn = mysqli_connect($host, $user, $pass, $database) or die("Uable to connect with the DB " . mysqli_error($conn));

$client_ID = $_POST['clientID'];

date_default_timezone_set('Halifax');
$date = date('Y-m-d H-i-s');

$sql = "INSERT INTO POsY (poNoY, datePOY, statusY, clientIDY) VALUES (NULL, '$date','pending','$client_ID')";

$insert_id_posNO = 0;

if ($conn->query($sql) === TRUE) {
	$insert_id_posNO += mysqli_insert_id($conn);
}

$data_checkout = $_POST['datacheckout'];

$user_selected = $_POST['user_selected'];


for ($i=0; $i < sizeof($data_checkout); $i++) { 
	$count = 0;
	$tprice = 0;
	for ($j=0; $j < sizeof($user_selected); $j++) { 
		if ($data_checkout[$i]['partNoY'] === $user_selected[$j]['dataIDY'] && $client_ID === $user_selected[$j]['clientIDY']) {
          $count++;
        }
	}
	$tprice += $data_checkout[$i]['currentPriceY'];
	$data_id = $data_checkout[$i]['partNoY'];

	$sql_second = "INSERT INTO LinesY(lineNoY, poNoY, partNoY, priceOrderedY, qtyY) VALUES (NULL,'$insert_id_posNO','$data_id','$tprice','$count')";

	$conn->query($sql_second);
}


$clientdata = $_POST['clientdata'];
$clientMoneyhas = $_POST['clientMoneyhas'];

$checkout_Total_price = $_POST['checkout_Total_price'];

$clientMoneyOwned = $_POST['clientMoneyOwned'];

if ($clientdata == 1) {
	if ($checkout_Total_price > $clientMoneyhas) {
		$clientMoneyOwned = (($checkout_Total_price - (($checkout_Total_price*10)/100)) - $clientMoneyhas) ;
		$updateSql = "UPDATE ClientsY SET dollarsOnOrderY = 0.00, moneyOwedY = '$clientMoneyOwned', DealsY = 2 WHERE clientIDY = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else if ($checkout_Total_price == $clientMoneyhas) {
		$updateSql = "UPDATE ClientsY SET dollarsOnOrderY = 0.00, moneyOwedY = 0.00, DealsY = 2 WHERE clientIDY = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else{
		$clientMoneyhas = $clientMoneyhas - $checkout_Total_price;
		$updateSql = "UPDATE ClientsY SET dollarsOnOrderY = '$clientMoneyhas', moneyOwedY = 0.00, DealsY = 2 WHERE clientIDY = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
}
else{
	if ($checkout_Total_price > $clientMoneyhas) {
		$clientMoneyOwned = $checkout_Total_price - $clientMoneyhas;
		$updateSql = "UPDATE ClientsY SET dollarsOnOrderY = 0.00, moneyOwedY = '$clientMoneyOwned', DealsY = 0 WHERE clientIDY = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else if ($checkout_Total_price == $clientMoneyhas) {
		$updateSql = "UPDATE ClientsY SET dollarsOnOrderY = 0.00, moneyOwedY = 0.00, DealsY = 0 WHERE clientIDY = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else{
		$clientMoneyhas = $clientMoneyhas - $checkout_Total_price;
		$updateSql = "UPDATE ClientsY SET dollarsOnOrderY = '$clientMoneyhas', moneyOwedY = 0.00, DealsY = 0 WHERE clientIDY = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
}

$deleteSql = "DELETE FROM userSelectedY WHERE clientIDY = '$client_ID' ";

if ($conn->query($deleteSql) === TRUE) {
}



$conn->close();

?>