<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Bootstrap CSS -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="shortcut icon" href="/assets/front/images/fabicon.png">
      <link rel="stylesheet" href="/assets/front/css/bootstrap.min.css">
      <link rel="stylesheet" href="/assets/front/css/style.css">
      <link rel="stylesheet" href="/assets/front/css/fancybox.min.css">
      <link rel="stylesheet" href="/assets/front/css/animate.css">
      <link rel="stylesheet" href="/assets/front/css/owl.carousel.min.css">
      <link rel="stylesheet" href="/assets/front/css/wow.min.css">
      <link rel="stylesheet" href="/assets/front/css/gijgo.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
      <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
      <title>Smooth Ride Cab Service</title>
   </head>
   <style>
   @font-face {
   font-family: 'Proxima Nova Rg';
   src: url('fonts/ProximaNova-Regular.woff2') format('woff2'),
   url('fonts/ProximaNova-Regular.woff') format('woff');
   font-weight: normal;
   font-style: normal;
   font-display: swap;
   }
   </style>
   <body>


      <!----------- main_header ---------->
      <header class="main_header main_header2" id="myHeader">
         <div class="top_header">
            <div class="container">
               <div class="row">
                  <div class="col-md-2 ">
                     <a class="navbar-brand pt-2" href="{{url('/')}}"><img src="/assets/front/images/logo.png"></a>
                  </div>
                  <div class="col-md-10 ">
                     <div class="marker_div text-end">
                        <!-- <nav class="navbar navbar-dark navbar-expand-md p-0">
                           <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                           <i class="bi bi-list"></i>
                           </button>
                           <div class="navbar-collapse collapse" id="navbarsExampleDefault">
                              <ul class="navbar-nav me-auto mb-2 mb-md-0">
                                 <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                                 <li class="nav-item"><a class="nav-link" href="#">Service</a></li>
                                 <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                                 <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                                 <li class="nav-item"><a class="nav-link" href="#">Testimonials</a></li>
                              </ul>
                           </div>
                        </nav> --> 
                        <form class="d-flex">
                           <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
                           <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
                        </form>
                        @if(Session::get('eastKey') && Session::get('eastKey') !==null)         
                        <ul class="nav_p">
                           <li><a class="Sell" href="#">Sell on TCG</span></a></li>
                           <li>
                              <select>
                                 <option>En</option>
                                 <option>Ar</option>
                              </select>
                           </li>
                           <li><img src="/assets/front/images/message.png"></li>
                           <li><img src="/assets/front/images/akar-icons_bell.png"></li>
                           <li><img src="/assets/front/images/Line.png"> <span class="num">03</span></li>
                           <li class="dropdown">
                              <span class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/avatar.png"></span>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                 <h6>Current TC pts: <span>20 <img src="/assets/front/images/list1.png" alt="bd"></span></h6>
                                 <a class="dropdown-item" href="{{route('myprofile')}}"><img src="/assets/front/images/menuicon1.png" alt="icon"> Profile</a>
                                 <a class="dropdown-item" href="#"><img src="/assets/front/images/menuicon2.png" alt="icon"> My order</a>
                                 <a class="dropdown-item" href="#"><img src="/assets/front/images/menuicon3.png" alt="icon"> My Mission</a>
                                 <a class="dropdown-item" href="{{route('addAction')}}"><img src="/assets/front/images/menuicon4.png" alt="icon"> Add Cards</a>
                                 <a class="dropdown-item" href="#"><img src="/assets/front/images/menuicon4.png" alt="icon"> View other listing</a>
                                 <a class="dropdown-item" href="#"><img src="/assets/front/images/menuicon5.png" alt="icon"> Followed</a>
                                 <a class="dropdown-item" href="#"><img src="/assets/front/images/menuicon6.png" alt="icon"> Bookmark</a>
                                 <a class="dropdown-item" href="#"><img src="/assets/front/images/menuicon7.png" alt="icon"> My card <span>(Coming soon)</span></a>
                                 <a class="dropdown-item" href="#"><img src="/assets/front/images/menuicon8.png" alt="icon"> Account Setting</a> 
                                 <a class="dropdown-item" href="{{route('userlogout')}}"><img src="/assets/front/images/menuicon9.png" alt="icon"> Logout</a>
                              </ul>
                           </li>
                        </ul>         
                        @else
                        <ul class="nav_p">
                           <li><a class="Sell" href="#">Sell on TCG</span></a></li>
                           <li>
                              <select>
                                    <option>En</option>
                                    <option>Ar</option>
                              </select>
                           </li>
                           <li><img src="/assets/front/images/message.png"></li>
                           <li><img src="/assets/front/images/akar-icons_bell.png"></li>
                           <li><img src="/assets/front/images/Line.png"> <span class="num">03</span></li>
                           <li><a class="lgin" href="{{route('userlogin')}}">login</a></li>
                        </ul>       
                        @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </header>
      