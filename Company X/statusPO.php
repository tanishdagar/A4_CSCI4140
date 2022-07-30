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
  <title>E-store</title>
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
          <li><a href="index.php">Home</a></li>
          <li class="active"><a href="statusPO.php">Status of Purchase Order</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li>
            <?php 
            $Client_Data = [];
            $data = json_decode(file_get_contents('json_files/Clients133.json'));
            for ($i=0; $i < sizeof($data); $i++) {  
              if ($_SESSION['client_id'] == $data[$i]->clientID133) {
                $Client_Data = $data[$i];
              }
            }
            ?>
            <a onclick="Account()"><span class="glyphicon glyphicon-user"></span>
              <?php 
              echo $Client_Data->clientName133;
              ?>
            </a>
          </li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout </a></li>
        </ul>
      </div>
    </div>
    <div class="jumbotron">
      <div class="container text-center">
        <h1>Your Purchase Order Status</h1>
      </div>
    </div>
  </nav>

  <main class="container-fluid text-center">
    <p>
    <?php 
      $dataPO = json_decode(file_get_contents('json_files/POs133.json'));
      for ($i=0; $i < sizeof($dataPO); $i++) {  
        if ($_SESSION['client_id'] == $dataPO[$i]->clientID133) {
          echo "Purchase Order Number <b>" . $dataPO[$i]->poNo133 . "</b> is <b>" . $dataPO[$i]->status133 . "</b> On this Date: <b>" . $dataPO[$i]->datePO133 . "</b><br><br>";
        }
      }
    ?>
    </p>
    <form method="post" accept="" id="style">
      <label>Purchase Order Number</label>
      <input type="number" size="100" name="number" value="number" placeholder="Purchase Order Number" required/>
      <button type="Submit" name="Submit" value="Submit" class="btn-danger">Submit</button>
    </form>
    <br>
    <br>
  </main>

  <?php 
    include('DB.php');

    if (isset($_POST['Submit'])) {
      $pos = $_POST['number'];
      $line = json_decode(file_get_contents('json_files/Lines133.json'));
      $posArray = json_decode(file_get_contents('json_files/POs133.json'));
      $alldata = [];
      for ($i=0; $i < sizeof($line); $i++) {  
        if($pos === $line[$i]->poNo133){
          for ($j=0; $j < sizeof($posArray); $j++) { 
            if ($posArray[$j]->poNo133 === $pos && $posArray[$j]->clientID133 ===  $Client_Data->clientID133) {
              $alldata[] = $line[$i];
            }
          }
        } 
      }

      if (count($alldata) !== 0) {
        echo "<pre>";
        print_r( $alldata );
        echo "</pre>";
      }
      else{
        echo "Wrong Purchase Order Number";
      }

    } 
  ?>

  <!--  
    my javascript
  -->
  <script type="text/javascript">
    var duplicate_elements = <?php echo json_encode($arr_items_checkout); ?>;
    var userselected = <?php echo json_encode($arr_items_checkout_quantity); ?>;
    var data = <?php echo json_encode($Client_Data); ?>;
    var client_data = <?php echo $Client_Data->Deals133; ?>;
    var client_money_has = <?php echo $Client_Data->dollarsOnOrder133; ?>;
    var client_money_owned = <?php echo $Client_Data->moneyOwed133; ?>;
    var client_ID = <?php echo $Client_Data->clientID133; ?>;
    var checkoutTotalprice = <?php echo $checkoutprice ; ?>;
  </script>
  <script src="./script.js"></script>

</body>
</html>
