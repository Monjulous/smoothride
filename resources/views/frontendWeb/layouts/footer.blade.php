<!-- download_app -->

<?php $details = DB::table('home')->select('*')->where('id', 1)->first();
//echo "<pre>";print_r($details);
?>


@php
    $setting = \App\Models\Setting::find(1);
@endphp
<!--------------------  footer -------------------->
<!-- <footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-lg-4 d-grid">

                    <img src="{{ asset('assets/admin/img/white-logo.png') }}" alt="logo"><br>
                    {!! $details->footer_text !!}</p>
                </div>
                <div class="col-lg-4">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="{{ route('homeindex') }}">Home</a></li>
                        <li><a href="{{ route('privacy_policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('termscondition') }}">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h3>Contact US</h3>
                    {!! $details->contact_text !!}</p>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</footer> -->

<footer class="footer text-center text-md-start">
  <div class="container">
    <div class="row text-center text-md-start align-items-center  foot-inner" >

    <div class="col-md-3 text-md-start text-center  ">
      <div class="footer-logo"><img src="{{ asset($setting->website_logo_light) }}" alt="" class="w-50 h-50"></div>
      <div class="text-muted foot-para">Book safe, fast, and affordable rides across your city.</div>
      <span class="footer-icons">
      <a href="{{ $setting->facebook }}" target="_blank"><i class="bi bi-facebook"></i></a>
        <a href="{{ $setting->instagram }}" target="_blank"><i class="bi bi-instagram"></i></a>
        <a href="{{ $setting->twitter }}" target="_blank"><i class="bi bi-x"></i></a>
        <a href="{{ $setting->linkedin }}" target="_blank"><i class="bi bi-linkedin"></i></a>
      </span>
        
      </div>
      <!-- Contact -->
     

          <div class="col-md-7 mb-4 mb-md-0  text-center foot-center">
            <label for=""><b>Head Office</b></label>
            <div class="text-muted foot-para" ><p style="font-size: 14px;">{{ $setting->address }}</p></div>

         
            <label for=""><b>Branch Office</b></label>
            <div class="text-muted foot-para" ><p style="font-size: 14px;">{{ $setting->address2 }}</p></div>
      </div>

      <!-- Social Icons -->


      <div class="col-md-2 mb-4 mb-md-0 d-grid align-items-center justify-content-center justify-content-md-start foot-right">

<div class="contact-item d-flex align-items-center mb-3">
  <div class="icon-cir me-2">
    <i class="bi bi-send-fill"></i>
  </div>
  <div class="footer-mail">
    <a href="mailto:{{ $setting->email }}">{{ $setting->email }}</a>
  </div>
</div>

<div class="contact-item d-flex align-items-center mb-3">
  <div class="icon-cir me-2">
    <i class="bi bi-telephone-fill"></i>
  </div>
  <div class="footer-mail">
    <a href="tel:{{ $setting->phone }}">{{ $setting->phone }}</a>
  </div>
</div>

<div class="contact-item d-flex align-items-center">
  <div class="icon-cir me-2">
    <i class="bi bi-whatsapp"></i>
  </div>
  <div class="footer-mail">
    <a href="https://wa.me/{{ preg_replace('/\D/', '', $setting->whatsapp) }}" target="_blank" rel="noopener noreferrer">
      {{ $setting->whatsapp }}
    </a>
  </div>
</div>

</div>
      
    </div>

    <!-- Navigation Links -->
    <div class="footer-nav text-center ">
      <a href="{{ route('homeindex') }}" class="active">Home</a>
      <a href="{{ route('about') }}">About us</a>
      <!-- <a href="#">How it Works</a>
      <a href="#">Features</a> -->
      <a href="/#testimonial">Testimonial</a>
    </div>

    <!-- Bottom Links -->
    <div class="footer-bottom text-center ">
      <div class="row">
        <div class="col-md-6 text-md-start foot-copy">
          Copyright @ 2025 smoothright, All rights reserved.
        </div>
        <div class="col-md-6 text-md-end foot-link">
          <a href="{{ route('services') }}"> Service</a> |
          <a href="{{ route('termscondition') }}">Terms & Conditions</a> |
          <a href="{{ route('privacy_policy') }}">Privacy Policy</a>
        </div>
      </div>
    </div>
  </div>
</footer>


<!--------------------  script -------------------->
<script src="{{ asset('assets/cacofront/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/cacofront/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/cacofront/js/fancybox.min.js') }}"></script>
<script src="{{ asset('assets/cacofront/js/owl.carousel.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyBBU9hD0_aNm36ZsG_DFJhJwM306BnkmHs" type="text/javascript">
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script> -->
<script>

// $(document).ready(function() {
//     $("#news-slider").owlCarousel({
//         items : 3,
//         itemsDesktop:[1199,3],
//         itemsDesktopSmall:[980,2],
//         itemsMobile : [600,1],
//         navigation:true,
//         navigationText:["",""],
//         pagination:true,
//         autoPlay:true
//     });
// });

$(document).ready(function () {
  $(".found, .home-ban").owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    navText: [
      '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
      '<i class="fa fa-chevron-right" aria-hidden="true"></i>'
    ],
    autoplayTimeout: 3000,
    responsive: {
      0: { items: 1 },
      300:{items: 1},
      600:{items: 2},
      1000: { items: 3 }
    }
  });
});
$(document).ready(function () {
  $(".hom-ban").owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    navText: [
      '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
      '<i class="fa fa-chevron-right" aria-hidden="true"></i>'
    ],
    autoplay: true,              
    autoplayTimeout: 3000,      
    autoplayHoverPause: true,    
    responsive: {
      0: { items: 1 },
      300: { items: 1 },
      600: { items: 1 },
      1000: { items: 1 }
    }
  });
});



    var locations = [
        <?php
        $contact = DB::table('home')->select('id', 'contact_address', 'latitude', 'longitude')->get();
        $i = 1;
        foreach ($contact as $key => $value) {
            echo '["' . $value->contact_address . '",' . $value->latitude . ',' . $value->longitude . '],';
            $i++;
        }
        
        ?>
    ];

    console.log(locations);

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 6,
        // zoomControl: true,
        center: new google.maps.LatLng(19.7761, -70.4469),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|752577'
            //     icon: {
            //      path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
            //     strokeColor: "green",
            //     scale: 3
            // },

        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }
</script>

</body>

</html>
