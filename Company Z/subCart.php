<?php

// acutal quantity of the product in 
$user_count = $_POST['user_selected_item'];

if ($user_count >= 1) {
	$host = "";
	$database = "";
	$user = "";
	$pass = "";

	$conn = mysqli_connect($host, $user, $pass, $database) or die("Uable to connect with the DB " . mysqli_error($conn));

	//product id
	$data = $_POST['dataname'];

	//total price in the cart
	$totalprice = $_POST['totalprice'] * ($user_count);

	$client_ID = $_POST['clientID'];

	$sql = "DELETE FROM userSelectedZ WHERE dataIDZ = '$data' AND totalpriceZ = '$totalprice' AND clientIDZ = '$client_ID' ";

	if ($conn->query($sql) === TRUE) {
	}

	// acutal quantity of the product in 
	$item_acutal_quantty = $_POST['quan'];

	$totalQuantity = $item_acutal_quantty + 1;

	$checkWhichCompany = preg_replace('/\d/', '', $data);

	if ($checkWhichCompany === "Y") {
		$getProductID = (int)(preg_replace('/[a-zA-Z]/', '', $data));
		$updateSql = "UPDATE PartsY SET QoHY = '$totalQuantity' WHERE PartsY . partNoY = $getProductID";

		if ($conn->query($updateSql) === TRUE) {
		}
	}
	else{
		$getProductID = (int)(preg_replace('/[a-zA-Z]/', '', $data));
		$updateSql = "UPDATE Parts133 SET QoH133 = '$totalQuantity' WHERE Parts133 . partNo133 = $getProductID";

		if ($conn->query($updateSql) === TRUE) {
		}
	}

	$conn->close();
}
else{
	echo "Cart is Empty :)";
}

?>