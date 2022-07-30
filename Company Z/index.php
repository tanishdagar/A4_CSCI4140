<!-- 
this template is taken from the given link 
https://www.w3schools.com/bootstrap/tryit.asp?filename=trybs_temp_store&stacked=h
on June 15, 2022
-->

<?php 
session_start();
if (!isset($_SESSION['islogin']) || isset($_SESSION['islogin']) !== true ) {
	header('Location: login.php');
	exit;
}
include 'DB.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Resale Stop</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- 
		these are with the bootstrap
	-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	<!-- 

		using jquery from the given link 
		https://cdnjs.com/libraries/jquery
		on June 15, 2022
	-->

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


	<!-- 

		making my own alert box using the give link
		https://www.delftstack.com/howto/javascript/javascript-customize-alert-box/
		on June 15, 2022

	-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8/dist/sweetalert2.all.min.js"></script>

	<!-- 
		connect with the css
	-->
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>                        
				</button>
				<a class="navbar-brand" href="index.php">Logo</a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li class="active"><a href="index.php">Home</a></li>
					<li><a href="statusPO.php">Status of Purchase Order</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<?php 
						$Client_Data = [];
						$data = json_decode(file_get_contents('json_files/ClientsZ.json'));
						for ($i=0; $i < sizeof($data); $i++) {  
							if ($_SESSION['client_id'] == $data[$i]->clientIDZ) {
								$Client_Data = $data[$i];
							}
						}
						?>
						<a onclick="Account()"><span class="glyphicon glyphicon-user"></span>
							<?php 
							echo $Client_Data->clientNameZ;
							?>
						</a>
					</li>
					<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout </a></li>
				</ul>
			</div>
		</div>
		<div class="jumbotron">
			<div class="container text-center">
				<h1>Resale Stop</h1>      
				<p>Resale Sports Store</p>
			</div>
		</div>
	</nav>

	<div class="container">    
		<div class="row">

			<?php  
			$dataX = json_decode(file_get_contents('json_files/Parts133.json'));
			$data = json_decode(file_get_contents('json_files/PartsY.json'));

			$arr_items_checkout = [];
			$arr_items_checkout_quantity = [];


			$merge_array = array_merge($dataX,$data);

			// new array 
			$new_array = array();
			$check_elements = true;

			for ($i=0; $i < sizeof($merge_array); $i++) { 
				if ($i < sizeof($dataX)) {
					$new_array["{$i}X"]= $merge_array[$i];
				}
				else{
					for ($n=0; $n < sizeof($new_array); $n++) { 

						if (strcmp($new_array["{$n}X"]->partName133, $merge_array[$i]->partNameY) == 0) {
							if ($new_array["{$n}X"]->currentPrice133 >= $merge_array[$i]->currentPriceY) {
								unset($new_array["{$n}X"]); 
							}

							$check_elements = false;
							break;
						}                   
					}
					if ($check_elements = true) {
						if (strcmp($new_array["{$n}X"]->partName133, $merge_array[$i]->partNameY) != 0) {
							$new_array["{$n}Y"] = $merge_array[$i];
						}               
					}
					$check_elements = true;
				}
			}

			// sort the array with respect to key but not as string, sorting with respect to the numbers
			ksort($new_array,SORT_NUMERIC);
			
			for ($i=0; $i < sizeof($new_array); $i++) {
				if (key($new_array) === "{$i}X") {
					?>
					<div class="col-sm-4">
						<div class="backcolor">
							<div class="panel-heading">
								<?php  echo $new_array["{$i}X"]->partName133; ?>    
							</div>
							<div class="panel-body">
								<img src="img/<?php  echo $new_array["{$i}X"]->productImage133; ?>" width="100%" height="450">
							</div>
							<div class="panel-footer">
								Price
								<?php  
								echo $new_array["{$i}X"]->currentPrice133; 
								?>
							</div>

							<?php 
							$count = 0;
							$savetotal = 0; 
							$amount = json_decode(file_get_contents('json_files/userSelectedZ.json'));

							for ($j=0; $j < sizeof($amount); $j++) { 
								if (($new_array["{$i}X"]->partNo133 . "X") == $amount[$j]->dataIDZ && $Client_Data->clientIDZ == $amount[$j]->clientIDZ) {
									$count++;
									$savetotal = $amount[$j]->totalpriceZ;
									$arr_items_checkout[] = $new_array["{$i}X"];
									$arr_items_checkout_quantity[] = $amount[$j];
								}
							}


							?>
							<button  onclick="add('<?php echo ($new_array["{$i}X"]->partNo133."X");?>', '<?php echo $count;?>', '<?php echo $new_array["{$i}X"]->QoH133;?>', '<?php echo $new_array["{$i}X"]->currentPrice133;?>')" id="btn">

								Add to Cart

							</button>

							<p id="amount"> <?php echo $count; ?> </p>

							<button onclick="sub('<?php echo ($new_array["{$i}X"]->partNo133."X");?>', '<?php echo $count;?>', '<?php echo $new_array["{$i}X"]->QoH133;?>', '<?php echo $new_array["{$i}X"]->currentPrice133;?>')" id="btn">

								Remove From Cart

							</button>

							<br>

							<p id="totalamount"> <?php echo "The total Product Price is " . $savetotal; ?> </p>
						</div>

						<br>
						<br>
					</div>
					<?php 
				}
				else if (key($new_array) === "{$i}Y") {
					?>
					<div class="col-sm-4">
						<div class="backcolor">
							<div class="panel-heading">
								<?php  echo $new_array["{$i}Y"]->partNameY; ?>    
							</div>
							<div class="panel-body">
								<img src="img/<?php  echo $new_array["{$i}Y"]->productImageY; ?>" width="100%" height="450">
							</div>
							<div class="panel-footer">
								Price
								<?php  
								echo $new_array["{$i}Y"]->currentPriceY; 
								?>
							</div>

							<?php 
							$countY = 0;
							$savetotal = 0; 
							$amount = json_decode(file_get_contents('json_files/userSelectedZ.json'));

							for ($j=0; $j < sizeof($amount); $j++) { 
								if (($new_array["{$i}Y"]->partNoY . "Y") == $amount[$j]->dataIDZ && $Client_Data->clientIDZ == $amount[$j]->clientIDZ) {
									$countY++;
									$savetotal = $amount[$j]->totalpriceZ;
									$arr_items_checkout[] = $new_array["{$i}Y"];
									$arr_items_checkout_quantity[] = $amount[$j];
								}
							}


							?>
							<button  onclick="add('<?php echo ($new_array["{$i}Y"]->partNoY."Y");?>', '<?php echo $countY;?>', '<?php echo $new_array["{$i}Y"]->QoHY;?>', '<?php echo $new_array["{$i}Y"]->currentPriceY;?>')" id="btn">

								Add to Cart

							</button>

							<p id="amount"> <?php echo $countY; ?> </p>

							<button onclick="sub('<?php echo ($new_array["{$i}Y"]->partNoY."Y");?>', '<?php echo $countY;?>', '<?php echo $new_array["{$i}Y"]->QoHY;?>', '<?php echo $new_array["{$i}Y"]->currentPriceY;?>')" id="btn">

								Remove From Cart

							</button>

							<br>

							<p id="totalamount"> <?php echo "The total Product Price is " . $savetotal; ?> </p>
						</div>

						<br>
						<br>
					</div>
					<?php
				}
				next($new_array);
			}
			?>
		</div>
	</div>

	<br>
	<br>

		<!-- 
			checkout button to process
		-->
		<?php 
		$checkoutprice = 0;

		// taking from the given link 
		// https://itecnote.com/tecnote/php-remove-duplicates-from-an-array-based-on-object-property/
		// https://www.php.net/manual/en/function.rsort.php
		// on june 24, 2022

		rsort($arr_items_checkout_quantity);

		$filtered = array_intersect_key($arr_items_checkout_quantity, array_unique(array_column($arr_items_checkout_quantity, 'dataIDZ')));

		foreach ($filtered as $key => $value) {
			$checkoutprice += $value->totalpriceZ;
		}

		?>

		<div id="totalcheck">
			<p> 
				<?php 
				if ($Client_Data->DealsZ == 1) {
					$discount = ($checkoutprice*10)/100;
					echo "The total Price of your Cart is  " . $checkoutprice . " * 10%  => " . ($checkoutprice-$discount);
				}
				else{
					echo "The total Price of your Cart is  " . $checkoutprice;
				}
				?> 
			</p>

			<button type="button" id="Checkout" onclick="checkoutcart(<?php echo $checkoutprice; ?>)">
				Checkout your Cart
			</button>
		</div>

		<!-- 
			footer 
		-->
		<footer class="container-fluid text-center">
			<p>Online Store Copyright</p>  
			Get 10% off:
			<input type="email" size="50" placeholder="Email Address" id="imput">
			<button onclick="deals()" id="disable">Get Deal</button>
		</footer>

		<!--  
			my javascript
		-->
		<script type="text/javascript">
			var duplicate_elements = <?php echo json_encode($arr_items_checkout); ?>;
			var userselected = <?php echo json_encode($arr_items_checkout_quantity); ?>;
			var data = <?php echo json_encode($Client_Data); ?>;
			var client_data = <?php echo $Client_Data->DealsZ; ?>;
			var client_money_has = <?php echo $Client_Data->dollarsOnOrderZ; ?>;
			var client_money_owned = <?php echo $Client_Data->moneyOwedZ; ?>;
			var client_ID = <?php echo $Client_Data->clientIDZ; ?>;
			var checkoutTotalprice = <?php echo $checkoutprice ; ?>;
		</script>
		<script src="./script.js"></script>

	</body>
	</html>