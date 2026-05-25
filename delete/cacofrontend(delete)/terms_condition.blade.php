@extends('layouts.master')

@section('content')


<!-- inner_banner -->
<section class="inner_banner">
  <div class="container">
    <h1>Terms & Conditions</h1>
  </div>
</section>


<!------------------ privacy_policy ---------------->
<section class="privacy_policy mt-5">
   <div class="container">
      <div class="row mb-4">
         <div class="col-sm-12 mb-4">
            {!! $terms->content !!}
         </div>
         
      </div>
   </div>
</section>
@endsection

