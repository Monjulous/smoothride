<!---------- footer ------------>
<footer class="main-footer">
   <div class="container">
      <div class="row">
         <div class="col-lg-2 footer1">
            <a href="">
            <img class="wow zoomIn" data-wow-delay=".2s" src="/assets/front/images/logo_footer.png" alt="logo" style="visibility: visible; animation-delay: 0.2s; animation-name: zoomIn;"></a>
         </div>
         <div class="col-sm-12 col-lg-6 menu">
            <div class="row">
               <div class="col-sm-4">
                  <h3>About Us</h3>
                  <ul>
                     <li><a href="#">Careers</a></li>
                     <li><a href="#">About Us</a></li>
                     <li><a href="#">Our Story</a></li>
                     <li><a href="#">Services</a></li>
                     <li><a href="#">Our Blog</a></li>
                  </ul>
               </div>
               <div class="col-sm-4">
                  <h3>Social Media</h3>
                  <ul>
                     <li><a href="#">facebook</a></li>
                     <li><a href="#">Twitter</a></li>
                     <li><a href="#">Instagram</a></li>
                     <li><a href="#">Linkedin</a></li>
                     <li><a href="#">Google+</a></li>
                  </ul>
               </div>
               <div class="col-sm-4">
                  <h3>Account & Shipping Info</h3>
                  <ul>
                     <li><a href="#">Your Account</a></li>
                     <li><a href="#">Shipping Rates & Policies</a></li>
                     <li><a href="#">Refunds & Replacements</a></li>
                     <li><a href="#">Delivery Info</a></li>
                     <li><a href="#">Affiliate Program</a></li>
                  </ul>
               </div>
            </div>
         </div>
         <div class=" col-lg-4 contfooter">
            <h3>Subscription</h3>
            <p>Subscribe to our Newsletter to receive early discount offers, latest news, sales and promo information.</p>
            <form> 
               <input class="w-100" type="text" name="firstname" placeholder="Enter Your E-Mail address">
               <button type="submit" value="">Subscribe</button>
            </form>
            <img src="/assets/front/images/Rectangle.png" alt="icon">
         </div>
      </div>
      <p class="lastp">@Copyright 2022 Le Toan</p>
   </div>
</footer>
<!-- Bootstrap JS -->
<script src="jquery-3.6.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/assets/front/js/bootstrap.bundle.min.js" ></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js" integrity="sha512-bztGAvCE/3+a1Oh0gUro7BHukf6v7zpzrAb3ReWAVrt+bVNNphcl2tDTKCBr5zk7iEDmQ2Bv401fX3jeVXGIcA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.js" integrity="sha512-4WpSQe8XU6Djt8IPJMGD9Xx9KuYsVCEeitZfMhPi8xdYlVA5hzRitm0Nt1g2AZFS136s29Nq4E4NVvouVAVrBw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<!-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js" ></script> -->
<script src="/assets/front/js/fancybox.min.js" ></script>
<script src="/assets/front/js/wow.js" ></script>
<script src="/assets/front/js/owl.carousel.min.js" ></script>
<script src="/assets/front/js/js_gijgo.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>




<script>
$(".arod").click(function(){
  $(".shipping_info").toggle();
});



var APP_URL = {!! json_encode(url('/')) !!};
   $( "form" ).on( "submit", function( event ) {
   event.preventDefault();
   
   console.log('hiiii for this', $( this ).serialize() );
   $.ajax({
      url: APP_URL+"/addBid",
      type:"POST",
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data:$( this ).serialize(),
      dataType: 'json',
      // {
      //   "_token": "{{ csrf_token() }}",
      //   name:name,
      //   email:email,
      //   mobile:mobile,
      //   message:message,
      // },
      success:function(response){
      //   $('#successMsg').show();
      //   console.log(response);
      },
      error: function(response) {
      //   $('#nameErrorMsg').text(response.responseJSON.errors.name);
      //   $('#emailErrorMsg').text(response.responseJSON.errors.email);
      //   $('#mobileErrorMsg').text(response.responseJSON.errors.mobile);
      //   $('#messageErrorMsg').text(response.responseJSON.errors.message);
      },
      });
   });



$( document ).ready(function() {
   var numi=$('#auctionId').val();
   var numi2=$('#sellId').val();
   var timers=[];
   var timerSell=[];
   if(numi && numi!==''){
      var num = Number(numi);
      for (let index = 0; index < num; index++) {
         timers.push($('#auction'+index).val());
      }

function myTimer(){
  var initTime = new Date();
  for (let index2 = 0; index2 < timers.length; index2++) {
   date_future = new Date(Number(timers[index2])*1000);
   date_now = new Date();
   diffMs = Math.floor((date_future - (date_now))/1000);
   diffMins = Math.floor(diffMs/60);
   diffHrs = Math.floor(diffMins/60);
   diffDays = Math.floor(diffHrs/24);
   diffHrs = diffHrs-(diffDays*24);
   diffMins = diffMins-(diffDays*24*60)-(diffHrs*60);
   diffMs = diffMs-(diffDays*24*60*60)-(diffHrs*60*60)-(diffMins*60);
   if(diffDays && diffDays>0)
   {
      $('.slash'+index2).html(diffDays+'d : '+diffHrs+'h : '+diffMins+'m : '+diffMs+'s');
   }else {
      $('.slash'+index2).html(diffHrs+'h : '+diffMins+'m : '+diffMs+'s');
   }
   }
   }
   var myVar = setInterval(myTimer, 1000);
   }

   if(numi2 && numi2!=='')
   {
      var num = Number(numi2);
      for (let indexr = 0; indexr < Number(numi2); indexr++) {
         timerSell.push($('#sell'+indexr).val());
      }
      function myTimer2(){
      var initTime = new Date();
      for (let indexsell = 0; indexsell < timerSell.length; indexsell++) {
         date_future = new Date(Number(timerSell[indexsell])*1000);
         date_now = new Date();
         diffMs = Math.floor((date_future - (date_now))/1000);
         diffMins = Math.floor(diffMs/60);
         diffHrs = Math.floor(diffMins/60);
         diffDays = Math.floor(diffHrs/24);
         diffHrs = diffHrs-(diffDays*24);
         diffMins = diffMins-(diffDays*24*60)-(diffHrs*60);
         diffMs = diffMs-(diffDays*24*60*60)-(diffHrs*60*60)-(diffMins*60);
         if(diffDays && diffDays>0)
         {
            $('.selling'+indexsell).html(diffDays+'d : '+diffHrs+'h : '+diffMins+'m : '+diffMs+'s');
         }else {
            $('.selling'+indexsell).html(diffHrs+'h : '+diffMins+'m : '+diffMs+'s');
         }
         }
         }
   var myVar = setInterval(myTimer2, 1000);
   }
});
   
   /************happy_carousel************/
 
   
     $('.client_carousel').owlCarousel({
                  //loop: true,  
                 autoplay: false,
                 autoplayTimeout: 5000,
                 margin:0, 
                 responsiveClass: true,
                       responsive: {
                         0: { items: 1, nav: true },
                       }
                     });



         $('.auction_carousel').owlCarousel({
        //loop: true,
       autoplay: false,
       autoplayTimeout: 5000,
       margin:40,
       responsiveClass: true,
             responsive: {
               0: { items: 1, nav: true },
               576: { items: 2, nav: true },
               768: { items: 2, nav: true },
               992: { items: 3, nav: true },
               1200: { items: 4, nav: true },
               1600: { items: 4, nav: true, margin: 40}
             }  
           });
   

  $('.imagecarousel').owlCarousel({
                  //loop: true,  
                 autoplay: false,
                 autoplayTimeout: 5000,
                 margin:0, 
                 responsiveClass: true,
                       responsive: {
                         0: { items: 1, nav: true },
                       }
                     });


   
   // When the user scrolls the page, execute myFunction
   window.onscroll = function() {myFunction()};
   
   // Get the header
   var header = document.getElementById("myHeader");
   
   // Get the offset position of the navbar
   var sticky = header.offsetTop;
   
   // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
   function myFunction() {
   if (window.pageYOffset > sticky) {
   header.classList.add("sticky");
   } else {
   header.classList.remove("sticky");
   }
   }
         
</script>

<script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
       
                reader.onload = function (e) {
                    $('.blah')
                        .attr('src', e.target.result);
                };
       
                reader.readAsDataURL(input.files[0]);
            }
        }
     </script>







</body>
</html>