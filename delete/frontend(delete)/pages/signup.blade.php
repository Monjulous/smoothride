@include('frontend/layouts/header')
<section class="login">
   <div class="container">
      <div class="form-signin">
      @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
 
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
         <form action="{!! url('/addUser') !!}" method="POST">
            @csrf
            <h2>Register</h2>
            <div class="form-group">
               <label>Full Name</label>
               <input type="text" name="name" class="form-control" placeholder="Name" required="">
            </div>
            <div class="form-group">
               <label>Email Address</label>
               <input type="email" name="email" class="form-control" placeholder="Enter Email" required="">
            </div>
            <div class="form-group">
               <label>Phone Number</label>
               <input type="text" name="mobile" class="form-control" placeholder="Phone Number" required="">
            </div>
            <div class="form-group">
               <label>Password</label>
               <input type="password" name="password" class="form-control" placeholder=" Password" required="">
            </div>
            <div class="form-group">
               <label>Confirm Password</label>
               <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required="">
            </div>
            <div class="form-group">
               <button class="btn btn-lg btn-warning btn-block" type="submit">Submit</button>
            </div>Password
         </form>
         <p class="text-center mt-3">Don’t have an account ?   <a href="{{route('userlogin')}}">Login</a></p>
      </div>
   </div>
</section>
@include('frontend/layouts/footer')