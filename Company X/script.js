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
      var isDuplicate = unique.has(items.partNo133);

      unique.add(items.partNo133);

      if (!isDuplicate) {
        return true;
      }

      return false;
    })

    var returnItem = "";

    for (var i = 0; i < data_checkout.length; i++) {
      var count = 0;

      for (var j = 0; j < userselected.length; j++) {
        if (data_checkout[i].partNo133 === userselected[j].Dataid) {
          count++;
        }
      }
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
      ${count}  * ${data_checkout[i].currentPrice133} = ${count*data_checkout[i].currentPrice133}
      <br>
      <br>
      </p>
      </div>
      <br>
      `
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
  Name: ${data.clientName133}
  <br>
  <br>
  Address: ${data.clientCity133}
  <br>
  <br>
  Amount: ${data.dollarsOnOrder133}
  <br>
  <br>
  Status: ${data.clientStatus133}
  <br>
  <br>
  Money Owned: ${data.moneyOwed133}
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