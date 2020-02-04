<!--
/**
 * Created by Robert.
 * Date: 08/12/19
 */
ini_set('date.timezone', 'America/Vancouver');
-->

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<script src="bambora.js"></script>-->
	<link rel="stylesheet" href="payment.css">
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
                font-weight: 300;margin-left:160px;margin-top:100px">
    Sorry, there is something wrong here.
   </div>

    <form id="checkout-form" method="get" action="" style="height: 400px;
                                                            width: 400px; margin-top:30px;margin-left:80px">

    <button class="btn" type="button" onclick="history.back();" style="position:fixed; margin-top:150px; margin-left:0px; width:446px;height:35px">
      Go back
    </button>
  </form>
  <div style="margin-top:-120px;margin-left:82px">
    Powered by <strong>Alphapay</strong>.
  </div>
  </div>

  </body>
  </html>
