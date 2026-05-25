@extends('frontendWeb.layouts.master')

@section('content')


<!-- inner_banner -->
<section class="inner_banner">
  <div class="container">
    <h1>About Us</h1>
  </div>
</section>


<!------------------ privacy_policy ---------------->
<section class="privacy_policy mt-5">
   <div class="container">
      <div class="row mb-4">
        <h2 class="text-center">{{ $about->title }}</h2>
         <div class="col-sm-12 mb-4 mt-2" style="text-align: justify;">
            {!! $about->sub_title !!}
         </div>
        
      </div>
   </div>
</section>
@endsection

