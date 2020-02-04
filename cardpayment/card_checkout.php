<!--
/**
 * Created by Robert.
 * Date: 08/12/19
 */
ini_set('date.timezone', 'America/Vancouver');
-->
<?php session_start();

/*
$mid = $_GET['mid'];
$price = $_GET['price'];
$orderid = $_GET['order'];
$firstname = $_GET['firstname'];
$lastname = $_GET['lastname'];
$postcode = $_GET['postcode'];
*/
$mid = $_SESSION['mid'];
$price = $_SESSION['price'];
$price = $price/100;
$orderid = $_SESSION['orderID'];
$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];
$address1 = $_SESSION['address1'];
$address2 = $_SESSION['address2'];
$city = $_SESSION['city'];
$state = $_SESSION['state'];
$postcode = $_SESSION['postcode'];
$orders = $_SESSION['orders'];
$pass = $_SESSION['pass'];


$_SESSION['mid'] = $mid;
$_SESSION['price'] = $price;
$_SESSION['orderID'] = $orderid;
$_SESSION['firstname'] = $firstname;
$_SESSION['lastname'] = $lastname;
$_SESSION['address1'] = $address1;
$_SESSION['address2'] = $address2;
$_SESSION['city'] = $city;
$_SESSION['state'] = $state;
$_SESSION['postcode'] = $postcode;
$_SESSION['orders'] = $orders;
$_SESSION['pass'] = $pass;


if ($mid == NULL){
  echo '<script type="text/javascript">
           window.location.replace("https://demo.alphapay.ca/test/card_error.php");
      </script>';
}
?>


<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<script src="bambora.js"></script>-->
	<link rel="stylesheet" href="bambora111.css">
</head>
<body>
  <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
  <script type="text/javascript"   src='https://libs.na.bambora.com/customcheckout/1/customcheckout.js'></script>
  <!--<script type="text/javascript"  src='bam_online.js'></script>-->

  <div class="container" style="height: 600px;
                                        width: 600px;
                                        position: fixed;
                                        top: 50%;
                                        left: 50%;
                                        margin-top: -300px;
                                        margin-left: -300px;">
<div style="margin-left:80px">
<img src="https://www.alphapay.com/api/icon1.png" height="30px" >
</div>
    <div style="font-size: 20px;
                font-weight: 300;margin-left:220px;margin-top:10px">
    Total price:
    <?php
    print ($price)." CAD";
     ?>
   </div>

    <form id="checkout-form" method="get" action="" style="height: 400px;
                                                            width: 400px; margin-top:30px;margin-left:80px">
      <div id="card-number" name="card-number"></div>
      <label for="card-number" id="card-number-error"></label>

      <div style="float:left; width:50%">
      <div id="card-cvv" name="card-cvv"></div>
      <label for="card-cvv" id="card-cvv-error"></label>
    </div>

      <div style="float:left; width:45%">
      <div id="card-expiry" name="card-expiry"></div>
      <label for="card-expiry" id="card-expiry-error"></label>
    </div>
      <div>
      <input id="pay-button" type="submit" class="btn disabled" value="Pay <?php print ($price)." CAD" ?>" disabled="true" style="position:fixed; margin-top:100px; margin-left:-380px; width:446px;height:35px">
    </div>
    <button class="btn" type="button" onclick="history.back();" style="position:fixed; margin-top:150px; margin-left:-380px; width:446px;height:35px">
      Cancel
    </button>
  </form>
  <div style="margin-top:-120px;margin-left:82px">
    <strong>Visa</strong> and <strong>Mastercard</strong> are accepted. <br>
    Powered by <strong>Alphapay</strong>.
  </div>
  </div>





<script>
  var customCheckout = customcheckout();

  var isCardNumberComplete = false;
  var isCVVComplete = false;
  var isExpiryComplete = false;
  var token_save = "";

  var customCheckoutController = {
    init: function() {
      console.log('checkout.init()');
      this.createInputs();
      this.addListeners();
    },
    createInputs: function() {
      console.log('checkout.createInputs()');
      var options = {};

      // Create and mount the inputs
      options.placeholder = 'Card number';
      customCheckout.create('card-number', options).mount('#card-number');

      options.placeholder = 'CVV';
      customCheckout.create('cvv', options).mount('#card-cvv');

      options.placeholder = 'MM / YY';
      customCheckout.create('expiry', options).mount('#card-expiry');
    },
    addListeners: function() {
      var self = this;

      // listen for submit button
      if (document.getElementById('checkout-form') !== null) {
        document
          .getElementById('checkout-form')
          .addEventListener('submit', self.onSubmit.bind(self));
      }

      customCheckout.on('brand', function(event) {
        console.log('brand: ' + JSON.stringify(event));

        var cardLogo = 'none';
        if (event.brand && event.brand !== 'unknown') {
          var filePath =
            'https://cdn.na.bambora.com/downloads/images/cards/' +
            event.brand +
            '.svg';
          cardLogo = 'url(' + filePath + ')';
        }
        document.getElementById('card-number').style.backgroundImage = cardLogo;
      });

      customCheckout.on('blur', function(event) {
        console.log('blur: ' + JSON.stringify(event));
      });

      customCheckout.on('focus', function(event) {
        console.log('focus: ' + JSON.stringify(event));
      });

      customCheckout.on('empty', function(event) {
        console.log('empty: ' + JSON.stringify(event));

        if (event.empty) {
          if (event.field === 'card-number') {
            isCardNumberComplete = false;
          } else if (event.field === 'cvv') {
            isCVVComplete = false;
          } else if (event.field === 'expiry') {
            isExpiryComplete = false;
          }
          self.setPayButton(false);
        }
      });

      customCheckout.on('complete', function(event) {
        console.log('complete: ' + JSON.stringify(event));

        if (event.field === 'card-number') {
          isCardNumberComplete = true;
          self.hideErrorForId('card-number');
        } else if (event.field === 'cvv') {
          isCVVComplete = true;
          self.hideErrorForId('card-cvv');
        } else if (event.field === 'expiry') {
          isExpiryComplete = true;
          self.hideErrorForId('card-expiry');
        }

        self.setPayButton(
          isCardNumberComplete && isCVVComplete && isExpiryComplete
        );
      });

      customCheckout.on('error', function(event) {
        console.log('error: ' + JSON.stringify(event));

        if (event.field === 'card-number') {
          isCardNumberComplete = false;
          self.showErrorForId('card-number', event.message);
        } else if (event.field === 'cvv') {
          isCVVComplete = false;
          self.showErrorForId('card-cvv', event.message);
        } else if (event.field === 'expiry') {
          isExpiryComplete = false;
          self.showErrorForId('card-expiry', event.message);
        }
        self.setPayButton(false);
      });
    },


    onSubmit: function(event) {
      var self = this;
      console.log('checkout.onSubmit()');

      event.preventDefault();
      self.setPayButton(false);
      self.toggleProcessingScreen();

      var callback = function(result) {
        console.log('token result : ' + JSON.stringify(result));

        if (result.error) {
//          self.processTokenError(result.error);
          alert("something wrong");
        } else {
          console.log('processTokenSuccess: ' + result.token);
//          this.setPayButton(true);
//            window.location.replace("cardpay_pay.php?t=" + result.token + "&mid=" + <?php Print($mid) ?> + "&price=" + <?php Print($price) ?> + "&orderID=" + "<?php Print($orderid) ?>" + "&firstname=" + "<?php Print($firstname) ?>" + "&lastname=" + "<?php Print($lastname) ?>" + "&postcode=" + "<?php Print($postcode) ?>");
        		window.location.replace("cardpay_pay.php?t=" + result.token);
        }
      };

      console.log('checkout.createToken()');
      customCheckout.createToken(callback);


      //window.open("cardpay_pay.php?t=" + t);
      //window.location.replace("cardpay_pay.php?t=" + );

    },
    hideErrorForId: function(id) {
      console.log('hideErrorForId: ' + id);

      var element = document.getElementById(id);

      if (element !== null) {
        var errorElement = document.getElementById(id + '-error');
        if (errorElement !== null) {
          errorElement.innerHTML = '';
        }

        var bootStrapParent = document.getElementById(id + '-bootstrap');
        if (bootStrapParent !== null) {
          bootStrapParent.className = 'form-group has-feedback has-success';
        }
      } else {
        console.log('showErrorForId: Could not find ' + id);
      }
    },
    showErrorForId: function(id, message) {
      console.log('showErrorForId: ' + id + ' ' + message);

      var element = document.getElementById(id);

      if (element !== null) {
        var errorElement = document.getElementById(id + '-error');
        if (errorElement !== null) {
          errorElement.innerHTML = message;
        }

        var bootStrapParent = document.getElementById(id + '-bootstrap');
        if (bootStrapParent !== null) {
          bootStrapParent.className = 'form-group has-feedback has-error ';
        }
      } else {
        console.log('showErrorForId: Could not find ' + id);
      }
    },

    setPayButton: function(enabled) {
      console.log('checkout.setPayButton() disabled: ' + !enabled);

      var payButton = document.getElementById('pay-button');
      if (enabled) {
        payButton.disabled = false;
        payButton.className = 'btn btn-primary';
      } else {
        payButton.disabled = true;
        payButton.className = 'btn btn-primary disabled';
      }
    },
    toggleProcessingScreen: function() {
      var processingScreen = document.getElementById('processing-screen');
      if (processingScreen) {
        processingScreen.classList.toggle('visible');
      }
    },
    showErrorFeedback: function(message) {
      var xMark = '\u2718';
      this.feedback = document.getElementById('feedback');
      this.feedback.innerHTML =   message;
      this.feedback.classList.add('error');
    },
    processTokenSuccess: function(token) {
      console.log('processTokenSuccess: ' + token);
      this.showSuccessFeedback(token);
      this.setPayButton(true);
      this.toggleProcessingScreen();

      //console.log('token_save: ' + token);
      // <?php
      //   $token_value = "<script>document.write(token_save)</script>"
      //   $_SESSION['token'] = $token_value;
      //  ?>

      // Use token to call payments api
      // this.makeTokenPayment(token);
    },
  };

  customCheckoutController.init();

</script>

</body>
</html>
