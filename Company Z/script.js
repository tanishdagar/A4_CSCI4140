// add to cart
function add(data, user_count, quantity, totalprice){
  $.ajax({
    type: "POST",
    url: 'addCart.php',
    data: {dataname: data, user_selected_item: user_count, quan: quantity, totalprice: totalprice, clientID: client_ID},
    success: function(res){
      if (res !== "") {
        alert(res);
      }
      window.location.reload();
    },
  })
}

// subtract from cart
function sub(data, user_count, quantity, totalprice){
  $.ajax({
    type: "POST",
    url: 'subCart.php',
    data: {dataname: data, user_selected_item: user_count, quan: quantity, totalprice: totalprice, clientID: client_ID},
    success: function(res){
      if (res !== "") {
        alert(res);
      }
      window.location.reload();
    },
  })
}

// making my own alert box using the give link
// https://www.delftstack.com/howto/javascript/javascript-customize-alert-box/
// on June 15, 2022
function checkoutcart(price){
  if (price == 0) {
    alert("Total Price of the Cart is 0");
    document.getElementById('Checkout').disabled = true;
    document.getElementById('Checkout').style.backgroundColor = "grey";
  }
  else{
    var unique = new Set();

    var data_checkout = duplicate_elements.filter(items => {

      if (items.partNoY != null && items.partNoY != "undefined") {
        var isDuplicate = unique.has(items.partNameY);
        unique.add(items.partNameY);
      }
      else if (items.partNo133 != null && items.partNo133 != "undefined") {
        var isDuplicate = unique.has(items.partName133);
        unique.add(items.partName133);
      }

      if (!isDuplicate) {
        return true;
      }

      return false;

    })

    var returnItem = "";
    for (var i = 0; i < data_checkout.length; i++) {
      var countX = 0;
      var countY = 0;

      for (var j = 0; j < userselected.length; j++) {
        if (data_checkout[i].partNoY != null && data_checkout[i].partNoY != "undefined") {
          var Yproduct = data_checkout[i].partNoY.concat("Y");
          if (Yproduct === userselected[j].dataIDZ) {
            countY++;
          }
        }
        if (data_checkout[i].partNo133 != null && data_checkout[i].partNo133 != "undefined") {
          var Xproduct = data_checkout[i].partNo133.concat("X"); 
          if (Xproduct === userselected[j].dataIDZ) {
            countX++;
          }
        }
      }

      if (countX > 0) {
        returnItem += 
        `
        <div id="stylecart">
        <img src="img/${data_checkout[i].productImage133}" width="200px" height="200px">
        <p> 
        ID - ${data_checkout[i].partNo133}
        <br>
        <br>
        Name - ${data_checkout[i].partName133}
        <br>
        <br>
        Description - ${data_checkout[i].partDescription133}
        <br>
        <br>
        ${countX}  * ${data_checkout[i].currentPrice133} = ${countX * data_checkout[i].currentPrice133}
        <br>
        <br>
        </p>
        </div>
        <br>
        `
      }
      else if (countY > 0) {
        returnItem += 
        `
        <div id="stylecart">
        <img src="img/${data_checkout[i].productImageY}" width="200px" height="200px">
        <p> 
        ID - ${data_checkout[i].partNoY}
        <br>
        <br>
        Name - ${data_checkout[i].partNameY}
        <br>
        <br>
        Description - ${data_checkout[i].partDescriptionY}
        <br>
        <br>
        ${countY}  * ${data_checkout[i].currentPriceY} = ${countY * data_checkout[i].currentPriceY}
        <br>
        <br>
        </p>
        </div>
        <br>
        `
      }
    }
    
    Swal.fire({
      title: "Do you want to Checkout or Make Any changes?",
      html: returnItem, 
      showCancelButton: true,
      confirmButtonText: `checkout`,
      customClass: 'swal-box-size',
    }).then((res) => {
      if (res.isConfirmed) {
        Swal.fire('checkout!', '', 'success')
        window.setTimeout(function(){
         $.ajax({
          type: "POST",
          url: 'checkout.php',
          data: 
          {
            clientID: client_ID,
            datacheckout: data_checkout, 
            user_selected: userselected, 
            clientdata: client_data,
            clientMoneyhas: client_money_has,
            clientMoneyOwned: client_money_owned,
            checkout_Total_price: checkoutTotalprice,
          },
          success: function(res){
            window.location.href = "statusPO.php";
          },
        })

       }, 2000);

      }
    });

  }
}

function deals(){
  var input = document.getElementById("imput").value;

  // regex take from gven link 
  // https://stackabuse.com/validate-email-addresses-with-regular-expressions-in-javascript/
  let regex = new RegExp("([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\"\(\[\]!#-[^-~ \t]|(\\[\t -~]))+\")@([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\[[\t -Z^-~]*])");

  if (regex.test(input)) {
    if (client_data >= 1) {
      Swal.fire({
        title: "Already got 10% off on your purchase",
        icon: 'warning',
        showCancelButton: false,
        customClass: 'deals-swal-box-size',
      });
      document.getElementById('disable').disabled = true;
      document.getElementById('disable').style.backgroundColor = "grey";
    }
    else{
      $.ajax({
        type: "POST",
        url: 'updateDeals.php',
        data: {dataname: client_ID},
        success: function(res){
          Swal.fire({
            title: "Got 10% off on your purchase",
            icon: 'success',
            showCancelButton: false,
            customClass: 'deals-swal-box-size',
          }).then((res) => {
            if (res.isConfirmed) {
              window.setTimeout(function(){
               window.location.reload();
             }, 20);

            }
          });
        },
      });
    }
  }
  else{
    alert("Put Valid Email :) :)");
  }
}


function Account(){
  var returnItem = 
  `
  <div id="stylecart">
  <p> 
  Name: ${data.clientNameZ}
  <br>
  <br>
  Address: ${data.clientCityZ}
  <br>
  <br>
  Amount: ${data.dollarsOnOrderZ}
  <br>
  <br>
  Status: ${data.clientStatusZ}
  <br>
  <br>
  Money Owned: ${data.moneyOwedZ}
  <br>
  <br>
  </p>
  </div>
  `;
  Swal.fire({
    title: "Account Details",
    html: returnItem,
    showCloseButton: true,
    showCancelButton: false,
    confirmButtonText: `Add Amount`,
    focusConfirm: false,
    customClass: 'Account-swal-box-size',
  });
}