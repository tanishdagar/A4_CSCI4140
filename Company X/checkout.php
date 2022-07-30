<?php

$host = "";
$database = "";
$user = "";
$pass = "";

$conn = mysqli_connect($host, $user, $pass, $database) or die("Uable to connect with the DB " . mysqli_error($conn));

$client_ID = $_POST['clientID'];

date_default_timezone_set('Halifax');
$date = date('Y-m-d H-i-s');

$sql = "INSERT INTO POs133 (poNo133, datePO133, status133, clientID133) VALUES (NULL, '$date','pending','$client_ID')";

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
		if ($data_checkout[$i]['partNo133'] === $user_selected[$j]['Dataid'] && $client_ID === $user_selected[$j]['clientID133']) {
          $count++;
        }
	}
	$tprice += $data_checkout[$i]['currentPrice133'];
	$data_id = $data_checkout[$i]['partNo133'];

	$sql_second = "INSERT INTO Lines133(lineNo133, poNo133, partNo133, priceOrdered133, qty133) VALUES (NULL,'$insert_id_posNO','$data_id','$tprice','$count')";

	$conn->query($sql_second);
}


$clientdata = $_POST['clientdata'];
$clientMoneyhas = $_POST['clientMoneyhas'];

$checkout_Total_price = $_POST['checkout_Total_price'];

$clientMoneyOwned = $_POST['clientMoneyOwned'];

if ($clientdata == 1) {
	if ($checkout_Total_price > $clientMoneyhas) {
		$clientMoneyOwned = (($checkout_Total_price - (($checkout_Total_price*10)/100)) - $clientMoneyhas) ;
		$updateSql = "UPDATE Clients133 SET dollarsOnOrder133 = 0.00, moneyOwed133 = '$clientMoneyOwned', Deals133 = 2 WHERE clientID133 = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else if ($checkout_Total_price == $clientMoneyhas) {
		$updateSql = "UPDATE Clients133 SET dollarsOnOrder133 = 0.00, moneyOwed133 = 0.00, Deals133 = 2 WHERE clientID133 = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else{
		$clientMoneyhas = $clientMoneyhas - $checkout_Total_price;
		$updateSql = "UPDATE Clients133 SET dollarsOnOrder133 = '$clientMoneyhas', moneyOwed133 = 0.00, Deals133 = 2 WHERE clientID133 = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
}
else{
	if ($checkout_Total_price > $clientMoneyhas) {
		$clientMoneyOwned = $checkout_Total_price - $clientMoneyhas;
		$updateSql = "UPDATE Clients133 SET dollarsOnOrder133 = 0.00, moneyOwed133 = '$clientMoneyOwned', Deals133 = 0 WHERE clientID133 = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else if ($checkout_Total_price == $clientMoneyhas) {
		$updateSql = "UPDATE Clients133 SET dollarsOnOrder133 = 0.00, moneyOwed133 = 0.00, Deals133 = 0 WHERE clientID133 = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else{
		$clientMoneyhas = $clientMoneyhas - $checkout_Total_price;
		$updateSql = "UPDATE Clients133 SET dollarsOnOrder133 = '$clientMoneyhas', moneyOwed133 = 0.00, Deals133 = 0 WHERE clientID133 = $client_ID";
		if ($conn->query($updateSql) === TRUE) {
		}
	}
}

$deleteSql = "DELETE FROM userSelected133 WHERE clientID133 = '$client_ID' ";

if ($conn->query($deleteSql) === TRUE) {
}



$conn->close();

?>