<!-- 
this template is taken from the given link 
https://www.w3schools.com/bootstrap/tryit.asp?filename=trybs_temp_store&stacked=h
on June 15, 2022
-->

<!-- 
login page is taken from the given link just references

https://code.tutsplus.com/tutorials/create-a-php-login-form--cms-33261

 -->

<?php 
  session_start();
  include('DB.php');
  if (isset($_POST['login'])) {
    $client_id = $_POST['number'];

    $host = "";
    $database = "";
    $user = "";
    $pass = "";

    $conn = mysqli_connect($host, $user, $pass, $database) or die("Uable to connect with the DB " . mysqli_error($conn));


    $sql = "SELECT * FROM ClientsZ WHERE clientIDZ = '$client_id' ";
    $res = $conn->query($sql);

    if ($res->num_rows > 0 ) {
      while ($row = $res->fetch_assoc()) {
        if ($client_id == $row['clientIDZ']) {
          $_SESSION['client_id'] = $row['clientIDZ'];
          $_SESSION['islogin'] = true;
          $islogin =  true;
          header("Location: index.php");
          exit;
        }
      }
    }
    else{
      echo '<script> alert("Wrong Clients Id please try again\nThank You and Have a Nice Day"); </script>';
    }
    $conn->close();
   } 
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Resale Store</title>
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
  <style>
    #style{
      font-size: 20px;
    }

  </style>
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
      </div>
    </div>
    <div class="jumbotron">
      <div class="container text-center">
        <h1>LOGIN PAGE</h1>      
        <p>PUT THE CLIENT ID FOR THE EASY LOGIN</p>
      </div>
    </div>
  </nav>


  <main class="container-fluid text-center">
    <form method="post" accept="" id="style">
      <label>Client ID</label>
      <input type="number" size="100" name="number" value="number" placeholder="Put Client ID" required/>
      <button type="submit" name="login" value="login" class="btn-danger">Log In</button>
    </form>
    <br>
    <br>
    <p> REFER TO THE BELOW CLIENT IDs THANK YOU </p>
  </main>

  <?php 
    include('DB.php');
    $data = json_decode(file_get_contents('json_files/ClientsZ.json'));
    $alldata = [];
    for ($i=0; $i < sizeof($data); $i++) {   
      $alldata[] = $data[$i];
    }
    echo "<pre>";
    print_r( $alldata );
    echo "</pre>";
  ?>

</body>
</html>