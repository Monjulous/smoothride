@extends('frontendWeb.layouts.master')

@section('content')

@php
$setting = \App\Models\Setting::find(1);
@endphp


<?php $details = DB::table('home')->select('*')->where('id', 1)->first();
//echo "<pre>";print_r($details);
?>
<section class="home-banner">
    <div class="container hero-section">
        <div class="row ">
            <div class="col-md-6 hero-left" data-aos="fade-right">

                <h6><i>
                        {{ $banner->sub_title }}</i>
                </h6>
                <h2>{{ $banner->title }}</h2>
                <p style=" text-align: justify; font-size: 18px;">{{ $banner->desc}}</p>
                <div class="d-flex align-items-center mt-3">
                    <!-- <div class="stats d-flex align-items-center">
                    <img src="" alt="users">
                    <span class="ms-2">5,000+ Daily Rides</span>
                </div> -->
                </div>
                <div class="stores mt-3">
                    <h4> Available On</h4>
                    <a href="{{ $setting->playstore }}"><img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play"></a>
                    <a href="{{ $setting->appstore }}"><img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="App Store"></a>
                </div>
            </div>
            <div class="col-md-6 hero-right text-md-end text-center aos-init aos-animate" data-aos="fade-left">

                <div class="owl-carousel owl-theme hom-ban mt-4">
                    @if($banner->image)
                            @php
                               $image =explode(',',$banner->image);
                            @endphp

                            @foreach ($image as $key=>$img)

                                 <div class="item">
                                    <img class="img" src="{{ asset(@$img)}}" alt="App Screenshot">
                                </div>
                            
                            @endforeach
                    @endif
                   
                  
                </div>





            </div>
        </div>
    </div>
    </div>

</section>


<section class="trust">
    <div class="container trusted-section" data-aos="fade-up">
        <h6>{{ $info->title }}</h6>
        <div class="row g-3">
            <?php
            $title = explode(',', $info->card_title);
            $desc = explode(',', $info->card_desc);
            $image = explode(',', $info->card_img);
            // echo"<pre>";print_r($image);die;
            ?>

            @if($desc)

            @foreach($desc as $key=>$val)
            <div class="col-lg-4 col-sm-6 stat-box">
                <!-- <h4>{{$val}}</h4> -->
                <img src="{{ asset(@$image[$key])}}" alt="" style="width: auto;">
                <p>{{ $desc[$key]}}</p>

            </div>
            @endforeach
            @endif

        </div>
    </div>
</section>


<!-- How It Works Section -->
<section class="works ">
    <div class="container how-it-works " data-aos="fade-up">
        <h6> <i>{{ $howitwork->title }} </i></h6>
        <h2>{{ $howitwork->sub_title }}</h2>
        <?php
        $title = explode(',', $howitwork->card_title);
        $desc = explode(',', $howitwork->card_desc);
        $image = explode(',', $howitwork->card_img);
        ?>
        <div class="row mt-5 g-4">

            @if($title)

            @foreach($title as $key=>$val)

            <div class="col-md-6 how-step mb-3" data-aos="fade-up">
                <img src="{{ asset(@$image[$key])}}" alt="Login Step">
                <h3>{{ $val }}</h3>
                <p>{{ @$desc[$key]}}</p>
            </div>
            @endforeach
            @endif




        </div>
    </div>
</section>


<!-- Why Choose -->
<!-- Features Section -->
<section class="feature-app home-banner">
    <div class="container  py-5 " data-aos="fade-up">
        <div class="text-center mb-4">
            <h6 class="text  mb-1"> <i>{{ $whychooseus->title }} </i></h6>
            <h2>{{ $whychooseus->sub_title }}</h2>
        </div>
        <div class="row text-center mt-5">
            <?php
            $title = explode(',', $whychooseus->card_title);
            $desc = explode(',', $whychooseus->card_desc);
            $image = explode(',', $whychooseus->card_img);
            ?>

            @if($title)

            @foreach($title as $key=>$val)
            <div class="col-md-3 mb-4">
                <div class="mb-5">
                    <img src="{{ asset(@$image[$key])}}" alt="Filters" width="50">
                </div>
                <h5>{{ $val }}</h5>
                <p>{{ @$desc[$key]}}</p>
            </div>
            @endforeach
            @endif


        </div>
    </div>
</section>




<!-- Testimonials Section -->
<section class="testimonial " id="testimonial">
    <div class="container py-5 text-white" data-aos="fade-up">
        <div class="text-start mb-5">
            <h6><i> {{ $testimonial->title}} </i></h6>
            <h2>{{ $testimonial->sub_title}}</h2>
        </div>

        <div class="owl-carousel owl-theme found mt-4">
            @if($testimonial_data)

            @foreach($testimonial_data as $key=>$val)
            <div class="item">

                <p>{{ $val->message}}</p>
                <div class="test-pic text-center d-flex justify-content-center gap-2">
                    <div class="test-pic-left">
                        <img src="{{ asset($val->photo)}}">
                    </div>
                    <div class="test-pic-right">
                        <h4>{{ $val->name}} </h4>
                        <p class="location">{{ $val->location}}</p>
                    </div>


                </div>
            </div>

            @endforeach
            @endif



        </div>

    </div>



</section>





<!-- Download CTA Section -->
<section class="smooth-ride">
    <div class="container smooth-inner py-5" style="background-color: #fcd34d; border-radius: 25px;" data-aos="fade-up">
        <div class="text-center">
            <h3>{{ $calltoaction->title }}</h3>
            <p class="mb-3">{{ $calltoaction->sub_title }}</p>
            <p class="dark-avail">Available now on Android and iOS.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ $setting->playstore }}"><img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" height="50" alt="Google Play"></a>
                <a href="{{ $setting->appstore }}"><img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" height="50" alt="App Store"></a>
            </div>
        </div>
    </div>
</section>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>
@endsection

@push('js')


@endpush