@extends('frontendWeb.layouts.master')

@section('content')


<!-- inner_banner -->
<section class="inner_banner">
  <div class="container">
    <h1>Services</h1>
  </div>
</section>
<style>
      .service-box {
            transition: 0.3s ease-in-out;
            padding: 30px;
            border: 1px solid #eee;
            border-radius: 10px;
            background-color: #64530f08;
            /* text-align: center; */
            height: 100%;
        }
        .service-box:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .service-icon {
            font-size: 40px;
            color: #0d6efd;
            margin-bottom: 20px;
        }
</style>

<!------------------ privacy_policy ---------------->
<section class="privacy_policy mt-5">
   <div class="container">
      <div class="row mb-4">
        <h2 class="text-center">{{ $service->title }}</h2>
         <div class="col-sm-12 mb-4 mt-2" style="text-align: justify;">
            {!! $service->sub_title !!}
         </div>
        
         <div class="row">
             @if ($service_list)
                @foreach ($service_list as $val)
                
                <div class="col-md-6 mb-4" style="text-align: left;" >
                <div class="service-box h-100">
                    <div class="service-icon">
                        <!-- <i class="bi bi-tools"></i>  -->
                    </div>
                    <h5 class="fw-semibold text-center">{{ $val->title }}</h5>
                    <p class="text-muted">{!!  $val->description !!}</p>
                    <!-- <a href="#" class="btn btn-outline-primary btn-sm mt-2">Learn More</a> -->
                </div>
               </div>
                @endforeach
             
             @endif
         </div>
      </div>
   </div>
</section>
@endsection

