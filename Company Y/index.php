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
  <title>Sports Stop</title>
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
            $data = json_decode(file_get_contents('json_files/ClientsY.json'));
            for ($i=0; $i < sizeof($data); $i++) {  
              if ($_SESSION['client_id'] == $data[$i]->clientIDY) {
                $Client_Data = $data[$i];
              }
            }
            ?>
            <a onclick="Account()"><span class="glyphicon glyphicon-user"></span>
              <?php 
              echo $Client_Data->clientNameY;
              ?>
            </a>
          </li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout </a></li>
        </ul>
      </div>
    </div>
    <div class="jumbotron">
      <div class="container text-center">
        <h1>Sports Stop</h1>      
        <p>Online Sports Store</p>
      </div>
    </div>
  </nav>

  <div class="container">    
    <div class="row">
      <?php  
      $data = json_decode(file_get_contents('json_files/PartsY.json'));

      $arr_items_checkout = [];
      $arr_items_checkout_quantity = [];

      for ($i=0; $i < sizeof($data); $i++) {           
        ?>
        <div class="col-sm-4">
          <div class="backcolor">
            <div class="panel-heading">
              <?php  echo $data[$i]->partNameY; ?>    
            </div>
            <div class="panel-body">
              <img src="img/<?php  echo $data[$i]->productImageY; ?>" width="100%" height="450">
            </div>
            <div class="panel-footer">
              Price
              <?php  
              echo $data[$i]->currentPriceY; 
              ?>
            </div>

            <?php 
            $count = 0;
            $savetotal = 0; 
            $amount = json_decode(file_get_contents('json_files/userSelectedY.json'));

            for ($j=0; $j < sizeof($amount); $j++) { 
              if ($data[$i]->partNoY == $amount[$j]->dataIDY && $Client_Data->clientIDY == $amount[$j]->clientIDY) {
                $count++;
                $savetotal = $amount[$j]->totalpriceY;
                $arr_items_checkout[] = $data[$i];
                $arr_items_checkout_quantity[] = $amount[$j];
              }
            }

            ?>
            <button  onclick="add('<?php echo $data[$i]->partNoY;?>', '<?php echo $count;?>', '<?php echo $data[$i]->QoHY;?>', '<?php echo $data[$i]->currentPriceY;?>')" id="btn">

              Add to Cart

            </button>

            <p id="amount"> <?php echo $count; ?> </p>

            <button onclick="sub('<?php echo $data[$i]->partNoY;?>', '<?php echo $count;?>', '<?php echo $data[$i]->QoHY;?>', '<?php echo $data[$i]->currentPriceY;?>')" id="btn">

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

  $filtered = array_intersect_key($arr_items_checkout_quantity, array_unique(array_column($arr_items_checkout_quantity, 'dataIDY')));

  foreach ($filtered as $key => $value) {
    $checkoutprice += $value->totalpriceY;
  }

  ?>

  <div id="totalcheck">
    <p> 
      <?php 
        if ($Client_Data->DealsY == 1) {
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
    var client_data = <?php echo $Client_Data->DealsY; ?>;
    var client_money_has = <?php echo $Client_Data->dollarsOnOrderY; ?>;
    var client_money_owned = <?php echo $Client_Data->moneyOwedY; ?>;
    var client_ID = <?php echo $Client_Data->clientIDY; ?>;
    var checkoutTotalprice = <?php echo $checkoutprice ; ?>;
  </script>
  <script src="./script.js"></script>

</body>
</html>