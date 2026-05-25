@include('frontend/layouts/header')
<section class="login">
   <div class="container">
      <div class="form-signin">
    @if ($message = Session::get('errors'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
 
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
         <form action="{!! url('/logins') !!}" method="POST">
            @csrf
            <h2>Login</h2>
            <div class="form-group">
               <input type="email" name="email" class="form-control" placeholder="User Name" required="">
            </div>
            <div class="form-group">
               <input type="password" name="password" class="form-control" placeholder="Password" required="">
            </div>
            <div class="form-group text-end">
               <a href="#" data-toggle="modal" data-target="#exampleModalCenter">Forgot Password ?</a>
            </div>
            <div class="form-group">
               <button class="btn btn-lg btn-warning btn-block" type="submit">Login</button>
            </div>
            <h6>or login with</h6>
         </form>
         <div class="row social text-center mt-3">
            <div class="col-6 wow zoomIn" data-wow-delay=".2s">
               <a class="google" href="#"><img src="/assets/front/images/g_icon.png" alt="icon"><span> Google+</span></a>
            </div>
            <div class="col-6 wow zoomIn" data-wow-delay=".1s">
               <a href="#"><img src="/assets/front/images/f_icon.png" alt="icon"><span> Login With Facebook</span></a>
            </div>
         </div>

         <p class="text-center mt-3">Don’t have an account ? <a href="{{route('signup')}}">Sign Up</a></p>
      </div>
   </div>
</section>
@include('frontend/layouts/footer')