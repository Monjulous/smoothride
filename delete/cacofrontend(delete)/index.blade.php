@extends('cacofrontend.layouts.master')

@section('content')
<!-- banner -->

<?php //echo "<pre>";print_r($homedata);
//echo $homedata->banner_title;
?>
<section class="banner" style="background-image: url(./images/bg.png);">
   <div class="container">
      <div class="row">
         <div class="col-md-6">
            <h1>{{ $homedetail->banner_title }}</h1>
            <p>{!! $homedetail->banner_desc !!}</p>
            <h6><a href="{{ $homedetail->url1 }}"><img src="{{ asset('assets/cacofront/images/Maskgroup.png')}}" alt="Maskgroup"></a><a href="{{ $homedetail->url2 }}"> <img class="ms-2" src="{{ asset('assets/cacofront/images/Maskgroup-1.png')}}" alt="Maskgroup"></a></h6>
         </div>
         <div class="col-md-6">
            <div class="bannerimg position-relative text-center">
               <a class="check" href="#"><img src="{{ asset('assets/cacofront/images/check.png')}}" alt="img"> Request for ride</a>
               <img src="{{ asset('assets/cacofront/images/cacoban.png')}}" alt="img">
               <a class="user_c" href="#"><img src="{{ asset('assets/cacofront/images/user_c.png')}}" alt="img"> Match with driver</a>
            </div>
         </div>
      </div>
   </div>
</section>


<!-- How it works -->
<section class="how_it_works">
   <div class="container">
      <h2 class="text-center mb-5">{{ $homedetail->title }}vvv</h2>
      <div class="row">
         <div class="col-sm-3">
            <div class="set mt-5 text-end">
               <h3>1</h3>
               <!-- <h6>Request a Ride</h6>
               <p>Choose your pickup and drop-off location, and the trip type that meets your needs</p> -->
               {!! $homedetail->desc1 !!}
            </div>
            <div class="set mt-5 text-end">
               <h3>3</h3>
               <!-- <h6>Enjoy Your Ride</h6>
               <p>Meet your driver with the help of our real time GPS services and enjoy your trip!</p> -->
               {!! $homedetail->desc3 !!}
            </div>
         </div>
         <div class="col-sm-6">
            <div class="works_center text-center" style="background-image: url(./images/mobbg.png);">
               <img src="{{ asset('assets/cacofront/images/cacoimage.png')}}" alt="mobile2">
            </div>
         </div>
         <div class="col-sm-3">
            <div class="set mt-5">
               <h3>2</h3>
               <!-- <h6>RMatch with a Driver</h6>
               <p>Caco will match you with the nearest available driver</p> -->
               {!! $homedetail->desc2 !!}
            </div>
            <div class="set mt-5">
               <h3>4</h3>
               <!-- <h6>Review</h6>
               <p>Give ratings to your driver</p> -->
               {!! $homedetail->desc4 !!}
            </div>
         </div>
      </div>
   </div>
</section>

@endsection

@push('js')
 
@endpush
<!--------------------  footer -------------------->
