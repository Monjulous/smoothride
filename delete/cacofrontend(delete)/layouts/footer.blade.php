<!-- download_app -->

<?php $details = DB::table('home')->select('*')->where('id', 1)->first();
//echo "<pre>";print_r($details);
?>
<section class="download_app">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ $details->banner_title }}</h1>
                {!! $details->banner_desc !!}
                <h6><a href="{{ $details->url1 }}"><img src="{{ asset('assets/cacofront/images/Maskgroup.png') }}"
                            alt="Maskgroup"></a><a href="{{ $details->url2 }}"> <img class="ms-2"
                            src="{{ asset('assets/cacofront/images/Maskgroup-1.png') }}" alt="Maskgroup"></a></h6>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('assets/cacofront/images/mob2.png') }}" alt="img">
            </div>
        </div>
    </div>
</section>
<!--------------------  footer -------------------->
<footer class="main-footer">
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
</footer>
<!--------------------  script -------------------->
<script src="{{ asset('assets/cacofront/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/cacofront/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/cacofront/js/fancybox.min.js') }}"></script>
<script src="{{ asset('assets/cacofront/js/owl.carousel.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyBBU9hD0_aNm36ZsG_DFJhJwM306BnkmHs" type="text/javascript">
</script>
<script>
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
