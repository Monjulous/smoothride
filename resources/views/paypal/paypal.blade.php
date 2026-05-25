<!DOCTYPE html>
<html>
<head>
        
    
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>PayPal</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://www.paypal.com/sdk/js?client-id=AUg8qnvz9rq7mnEdrUgJH8VCZTSVXyIDQQTTkxMLZ1-bfJp3ik_HLlBGrnf6ibeYKJ_i3sZ5Ug2-nwEn&currency=USD&disable-funding=credit,card"></script>
        <style type="text/css">
            .paypal-button.paypal-button-number-1.paypal-button-layout-vertical.paypal-button-shape-rect.paypal-button-number-multiple.paypal-button-env-sandbox.paypal-button-color-black.paypal-button-text-color-white.paypal-logo-color-white {
               display: none;}
               .paypal_button {
    max-width: 500px;
    margin: 30px auto;
    border: 1px solid #ccc;
    padding: 12px;
    border-radius: 5px;
}
</style>
</head>
<body>
     <input type="hidden" name="amount" id="amount" value="<?php echo $data['amount']; ?>" />
     <input type="hidden" name="passenger_id" id="passenger_id"  value="<?php echo $data['passenger_id']; ?>" />
     <input type="hidden" name="driver_id" id="driver_id" value="<?php echo $data['driver_id']; ?>" />
     <input type="hidden" name="ride_id" id="ride_id" value="<?php echo $data['ride_id']; ?>" />

     <div class="paypal_button ">
 
    <div class="summry">
        <img src="https://caco.do/assets/cacofront/images/logo.png" style="height: 34px;
    width: auto;"/>
      <h6>Location -<span class="price" style="color:black"><i class="fa fa-shopping-cart"></i> <?php echo  @$data['pick_location']; ?> To <?php echo  @$data['drop_location']; ?></span></h6>
      <p><a class="linkr" href="javascript:void(0)" style="text-decoration:none">Price -</a> <span class="price">RD$<?php echo $data['amount']; ?></span></p>
      <hr class="hr">
      <p>Total <span class="price" style="color:black"><b>RD$<?php echo $data['amount']; ?></b></span></p>
  
  </div>

 
  <div id="paypal-button-container"></div>
            </div>
    <script> 

            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
            });
           var amount=document.getElementById('amount').value;
           var passenger_id=document.getElementById('passenger_id').value;
           var driver_id=document.getElementById('driver_id').value;
           var ride_id=document.getElementById('ride_id').value;
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: amount

                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    $.ajax({
                            url:"save_payment_into",
                            type:"post",
                            data:{'amount':amount,'passenger_id':passenger_id,'ride_id':ride_id,'driver_id':driver_id},
                            success:function(res)
                            {
                              if(res)
                              {
                                window.ReactNativeWebView.postMessage("yes") 
                                //   console.log(details);
                                //  alert('Transaction completed by ' + details.payer.name.given_name);

                              }
                              else
                              {
                                window.ReactNativeWebView.postMessage("no") 
                                //  alert('Something else wrong please try again');
                              }
                            },
                            error:function(res){
                              console.log('res',res);
                            }
                    })
                    
                    // You can handle further processing here, like sending confirmation emails, etc.
                });
            }
        }).render('#paypal-button-container');
    </script>

























</body>
</html>
