@extends('layouts.master')

@section('content')
<style>
    .contact-container {
      padding: 50px 15px;
    }
    .left-box {
      background-color: #fff;
      border-radius: 8px;
      padding: 30px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }
    .form-box {
      background-color: #fff;
      border-radius: 8px;
      padding: 30px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }
    .section-title {
      font-weight: 600;
      margin-bottom: 20px;
    }
</style>

<!-- inner_banner -->
<section class="inner_banner">
   <div class="container">
      <h1>Contact Us</h1>
      <p class="text-light">Have a question? Need help with your booking? We're here to assist you 24/7.</p>
   </div>
</section>

@php
  $setting=DB::table('settings')->get()->first();
@endphp
<!------------------ contact---------------->
<section class="contact">
   <div class="container contact-container">
      <div class="row">

      <div class="col-md-6">
      <div class="left-box">
        <h4 class="section-title">Head Office</h4>
        <p>{{ $setting->address }}</p>

        <h4 class="section-title">Branch Office</h4>
        <p>{{ $setting->address2 }}</p>

        <h4 class="section-title">Customer Support</h4>
        <p>
          📞 Phone: {{ $setting->phone }}<br>
          💬 WhatsApp:{{ $setting->whatsapp }}<br>
          ✉️ Email: <a href="mailto:{{ $setting->email }}">{{ $setting->email }}</a>
        </p>
      </div>
    </div>


    <div class="col-md-6">
            @if (Session::has('success'))
            <div class=" alert-success">{{ Session::get('success') }} 
            </div>
            @endif
            <h3>Speak with us</h3>
            <form action="{{ route('store_contact') }}" method="post" class="row">
               @csrf
               <div class="form-group col-12 mb-3">
                  <span class="into d-block">
                  <input type="text" id="fname" name="name" placeholder="Enter name.." class="w-100" sss="" required>
                  <i class="fas fa-user"></i>
                  </span>
               </div>
               <div class="form-group col-12 mb-3">
                  <span class="into d-block">
                  <input type="text" id="email" name="email" placeholder="Enter email.." class="w-100" required>
                  <i class="fas fa-envelope"></i>
                  </span>
               </div>
               <div class="form-group col-12 mb-3">
                  <span class="into d-block">
                  <input type="text" id="subject" name="subject" placeholder="Subject.." class="w-100" required>
                  <i class="fas fa-user-tie"></i>
                  </span>
               </div>
               <div class="form-group col-12 mb-3">
                  <textarea id="message" class="w-100" name="message" placeholder="Enter your message here..." style="height:180px" required></textarea>
               </div>
               <div class="form-group col-12">
                 <button type="submit" class="btn btn-primary" style="background: #ffde59;">Send a Message</button>
               </div>
            </form>
         </div>









      <!-- ---------------------- -->
        
         
      </div>
   </div>
</section>


@endsection