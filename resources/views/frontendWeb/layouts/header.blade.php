
@php
    $setting = \App\Models\Setting::find(1);
@endphp
<!doctype html>
<html lang="en">

<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-LF5FTD4H08"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-LF5FTD4H08');
    </script>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="shortcut icon" href="{{ asset($setting->website_favicon) }}">
    <link rel="stylesheet" href="{{ asset('assets/cacofront/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/cacofront/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/cacofront/css/fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/cacofront/css/owl.carousel.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css"
        rel="stylesheet" type="text/css" />
    <title> {{ $setting->website_title }}</title>
    <!-- Google tag (gtag.js) -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <style>

@import url('https://fonts.googleapis.com/css2?family=Cookie&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap');

body{
   font-family: "Montserrat", sans-serif !important;
}

        .section-dark {
      background-color: #1a1a1a;
      color: #fff;
    }

    .highlight {
      color: #fbbf24;
      font-weight: bold;
    }

    .feature-box i {
      font-size: 24px;
      margin-bottom: 10px;
    }

   .testimonial {
    background-color: #303030;
    padding: 40px 20px 229px;
}

    .app-download {
      background-color: #fbbf24;
      padding: 40px 20px;
      text-align: center;
      font-weight: bold;
      border-radius: 20px 20px 0 0;
    }

    .app-download p {
      font-size: 1.25rem;
      margin-bottom: 0.5rem;
    }

    .app-download small {
      font-weight: normal;
      display: block;
      margin-top: 0.5rem;
      color: #333;
    }

    .app-download a {
      margin: 0 10px;
    }

    .step-img {
      height: 250px;
      object-fit: contain;
    }
    .hom-ban img.img {
    height: 569px;
    width: 100%;
    object-fit: contain;
}
.hom-ban .owl-nav {
    display: none;
}

    .trust {
      background-color: #303030;
      color: #fff;
      padding: 60px 20px;
    }

   .trusted-section h6 {
    text-align: center;
    color: white;
    font-size: 48px;
    margin-bottom: 48px;
    font-weight: 400;
}
.hom-ban .owl-dots {
    position: absolute;
    right: 45%;
    bottom: 0px;
}

    .trusted-section .stat-box {
    text-align: center;
    border-right: 1px solid white;
}

.trusted-section .stat-box:last-child {
    border-right: none;
}

  .trusted-section .stat-box h4 {
    color: white;
    font-size: 48px;
}

   .trusted-section .stat-box p {
    color: white;
    font-size: 20px;
}
    .how-it-works {
      padding: 60px 20px;
      text-align: center;
    }

.how-it-works h6 {
    font-size: 24px;
    font-weight: 600;
    color: #303030;
}
.works .how-it-works h2 {
    font-size: 48px;
    font-weight: 500;
    color: black;
}


    .how-it-works h2 span {
      color: #fbbf24;
      font-weight: bold;
    }
    .works .how-it-works h3 {
    font-size: 28px;
    font-weight: 600;
    color: #0D0D0D;
}

    .how-step img {
      max-width: 100%;
      height: auto;
      margin-bottom: 15px;
    }

    .how-step p {
      margin-bottom: 0;
    }

   .testimonial #news-slider{
    margin-top: 80px;
        }
        .testimonial  .post-slide{
            background: #fff;
            margin: 20px 15px 20px;
            border-radius: 15px;
            padding-top: 1px;
            box-shadow: 0px 14px 22px -9px #bbcbd8;
        }
        .testimonial  .post-slide .post-img{
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            margin: -12px 15px 8px 15px;
            margin-left: -10px;
        }
        .testimonial  .post-slide .post-img img{
            width: 100%;
            height: auto;
            transform: scale(1,1);
            transition:transform 0.2s linear;
        }
        .testimonial .post-slide:hover .post-img img{
            transform: scale(1.1,1.1);
        }
        .testimonial .post-slide .over-layer{
            width:100%;
            height:100%;
            position: absolute;
            top:0;
            left:0;
            opacity:0;
            background: linear-gradient(-45deg, rgba(6,190,244,0.75) 0%, rgba(45,112,253,0.6) 100%);
            transition:all 0.50s linear;
        }
        .testimonial .post-slide:hover .over-layer{
            opacity:1;
            text-decoration:none;
        }
        .testimonial .post-slide .over-layer i{
            position: relative;
            top:45%;
            text-align:center;
            display: block;
            color:#fff;
            font-size:25px;
        }
        .testimonial .post-slide .post-content{
            background:#fff;
            padding: 2px 20px 40px;
            border-radius: 15px;
        }
        .testimonial .post-slide .post-title a{
            font-size:15px;
            font-weight:bold;
            color:#333;
            display: inline-block;
            text-transform:uppercase;
            transition: all 0.3s ease 0s;
        }
        .testimonial .post-slide .post-title a:hover{
            text-decoration: none;
            color:#3498db;
        }
        .testimonial .post-slide .post-description{
            line-height:24px;
            color:#808080;
            margin-bottom:25px;
        }
        .testimonial .post-slide .post-date{
            color:#a9a9a9;
            font-size: 14px;
        }
        .testimonial .post-slide .post-date i{
            font-size:20px;
            margin-right:8px;
            color: #CFDACE;
        }
        .testimonial .post-slide .read-more{
            padding: 7px 20px;
            float: right;
            font-size: 12px;
            background: #2196F3;
            color: #ffffff;
            box-shadow: 0px 10px 20px -10px #1376c5;
            border-radius: 25px;
            text-transform: uppercase;
        }
        .testimonial .post-slide .read-more:hover{
            background: #3498db;
            text-decoration:none;
            color:#fff;
        }
        .testimonial  .owl-controls .owl-buttons{
            text-align:center;
            margin-top:20px;
        }
        .testimonial .owl-controls .owl-buttons .owl-prev{
            background: #fff;
            position: absolute;
            top:-13%;
            left:15px;
            padding: 0 18px 0 15px;
            border-radius: 50px;
            box-shadow: 3px 14px 25px -10px #92b4d0;
            transition: background 0.5s ease 0s;
        }
        .testimonial .owl-controls .owl-buttons .owl-next{
            background: #fff;
            position: absolute;
            top:-13%;
            right: 15px;
            padding: 0 15px 0 18px;
            border-radius: 50px;
            box-shadow: -3px 14px 25px -10px #92b4d0;
            transition: background 0.5s ease 0s;
        }
        .testimonial  .owl-controls .owl-buttons .owl-prev:after,
        .owl-controls .owl-buttons .owl-next:after{
            content:"\f104";
            font-family: FontAwesome;
            color: #333;
            font-size:30px;
        }
        .testimonial  .owl-controls .owl-buttons .owl-next:after{
            content:"\f105";
        }
        

        /* 24 june css start */

        section.home-banner {
          background-color: #F9F9FB;
          padding: 40px 20px 0;
}

.home-banner h6 {
    font-size: 24px;
    font-weight: 600;
    color: #303030;
    margin-bottom: 28px;
}

.home-banner .hero-section h2 {
    font-size: 46px;
    font-weight: 600;
    color: #0D0D0D;
    margin-bottom: 28px;
}
.home-banner .hero-section p {
    font-size: 20px;
    font-weight: 500;
    color: #808080;
}
.home-banner .hero-section h4 {
    font-size: 24px;
    font-weight: 500;
    color: #0D0D0D;
}
.home-banner .hero-section .stores img {
    width: 200px;
    height: 62px;
}
section.feature-app {
    background-color: #F9F9FB;
}
.feature-app h2 {
    font-size: 48px;
    font-weight: 500;
    color: black;
}
.feature-app img {
    background-color: #FFDE59;
    padding: 7px;
    border-radius: 8px;
}

.feature-app h5 {
    font-size: 24px;
    font-weight: 500;
    color: #18181B;
}
.feature-app p {
    color: #52525B;
    max-width: 270px;
    margin: auto;
}
.testimonial h6 {
    font-size: 24px;
    font-weight: 600;
    color: #FFFFFFCC;
}
.testimonial h2 {
    font-size: 48px;
    font-weight: 500;
    color: white;
}
.testimonial .item p {
    padding: 40px 20px;
    border-radius: 20px;
    border: 1px solid white;
    min-height: 180px;
}
.testimonial .test-pic img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    margin: auto;
}
.testimonial p.location {
    padding: 0;
    border: none;
    min-height: 100%;
}
.testimonial .owl-nav.disabled {
    display: block;
}
.testimonial button.owl-prev {
    position: absolute;
    top: -35%;
    right: 10%;
    background-color: white !important;
    color: black !important;
    width: 50px;
    height: 50px;
    border-radius: 50% !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}
.testimonial button.owl-next {
    position: absolute;
    top: -35%;
    right: 5%;
    background-color: white !important;
    color: black !important;
    width: 50px;
    height: 50px;
    border-radius: 50% !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}
.smooth-ride .smooth-inner {
    margin-top: -6%;
    margin-bottom: 50px;
}
.smooth-ride h3 {
    font-size: 48px;
    font-weight: 500;
    color: black;
}
.smooth-ride p {
    font-size: 22px;
    color: #303030;
}
.smooth-ride p.dark-avail {
    font-size: 24px;
    font-weight: 500;
    color: #0D0D0D;
}
/* 24 june css end */


/* footer css start */

.footer {
    background-color: #F9F9FB;
    padding: 40px 20px;
}



   .footer .footer-icons a {
      display: inline-block;
      margin: 0 8px;
      font-size: 1.3rem;
      color: #000;
    }

  .footer  .footer-nav a {
    margin: 0 10px;
    color: #616161;
    text-decoration: none;
    font-weight: 500;
}

   .footer .footer-nav a.active {
      font-weight: 600;
      color:black;
    }

.footer  .footer-bottom {
    border-top: 1px solid #D6D6D6;
    padding-top: 20px;
    font-size: 14px;
}

   .footer .icon-cir i.bi.bi-send-fill {
    margin: 0 8px 0 0;
    /* font-size: 20px; */
    color: black;
}


    .footer .footer-mail a {
    text-decoration: none;
    color: #0D0D0D;
    font-weight: 500;
    /* font-size: 24px; */
}

.footer .foot-inner {
    padding: 40px 20px 0 !important;
    border-bottom: 1px solid #D6D6D6;
}
.footer .foot-para {
    color: #808080;
    margin-bottom: 24px;
}
.footer .footer-nav {
    padding: 50px 0;
}


.footer .foot-center {
    border-right: 1px solid #D6D6D6;
    border-left: 1px solid #D6D6D6;
    padding: 40px 20px;
}
.footer .foot-copy {
    font-size: 18px;
    color: #0D0D0D;
}
.footer .foot-link a {
    text-decoration: none;
    font-size: 18px;
    color: #0D0D0D;
}
.footer .foot-link {
    display: flex;
    justify-content: end;
    gap: 33px;
    flex-wrap: wrap;
}
.footer-icons i {
    padding: 8px 10px;
    border: 2px solid black;
    border-radius: 50%;
    /* font-size: 24px; */
}
.footer .footer-icons {
    display: flex;
}
.collapse ul.navbar-nav {
    column-gap: 33px;
}

.footer .foot-right{

    padding: 0 0 0 32px;
    margin: -4% 0 0 0;

}
    /* footer css end */



@media only screen and (max-width:1280px) {
          .testimonial .post-slide .post-content{
                padding: 0px 15px 25px 15px;
            }
}
           
@media (max-width:1199px){
            .home-banner .hero-section h2 {
                    font-size: 28px;
    }
    .footer .foot-link {
        gap: 15px;
    }
    .home-banner .hero-section p {
        font-size: 16px !important;
    
    }
    .footer .footer-mail a {

        font-size: 18px;
    }
    .trusted-section h6 {
        font-size: 38px;
    }
    .works .how-it-works h2 {
        font-size: 38px;

    }
    .feature-app h2 {
        font-size: 38px;
    }
    .testimonial h2 {
        font-size: 38px;

    }
    .smooth-ride h3 {
        font-size: 38px;
    }
}

@media (max-width:991px){
        .home-banner h6 {
            font-size: 20px;
        }
        .home-banner .stores {
    padding-bottom: 25px;
 }
        .home-banner .hero-section h2 {
          font-size: 32px;
        
        }
        .home-banner .hero-section p {
            font-size: 16px;
        }

        .trusted-section h6 {
            font-size: 38px;
        }
        .trusted-section .stat-box h4 {
            font-size: 30px;
        }
        .trusted-section .stat-box:last-child {
        border-right: 1px solid;
    }
    .trusted-section .stat-box p {
        font-size: 16px;
    }
    .works .how-it-works h2 {
        font-size: 38px;
    }
    .feature-app h2 {
        font-size: 38px;

    }
    .testimonial button.owl-prev {
        position: relative;
        top: 0;
        right: 0;
        background-color: white !important;
        color: black !important;
        width: 50px;
        height: 50px;
        border-radius: 50% !important;
        display: inline !important;
    }
    .testimonial button.owl-next {
        position: relative;
        top: 0;
        right: 0;
        background-color: white !important;
        color: black !important;
        width: 50px;
        height: 50px;
        border-radius: 50% !important;
        display: inline !important;
    }
    .testimonial .owl-dots {
        display:none;
    }
    .smooth-ride h3 {
        font-size: 38px;

    }
    .smooth-ride p {
        font-size: 18px;
    }
    .testimonial {

        padding: 40px 20px 100px;
    }
    .footer .footer-mail a {
        font-size: 13px;
    }
    .footer .foot-para {

        font-size: 14px;
    }
    .home-banner .hero-section .stores img {
        width: 153px;
        height: 62px;
    }

}

@media (max-width:767px){
            .home-banner .hero-section h2 {
        font-size: 28px;
    }
    .trusted-section h6 {
        font-size: 28px;
    }
    .works .how-it-works h2 {
        font-size: 28px;
    }
    .feature-app h2 {
        font-size: 28px;
    }
    .testimonial h2 {
        font-size: 28px;

    }
    .footer .foot-link {
        justify-content: center;
        gap: 9px;

    }
    .footer .footer-icons {
        display: flex;
        justify-content: center;
        padding-bottom: 21px;
    }
    .footer .foot-center {
    border-right: none;
    border-left: none;
    padding: 20px 0 0;
    border-top: 1px solid;
    border-bottom: 1px solid;
    margin-bottom: 42px !important;
    margin-top: 20px;
}
    .footer .footer-mail a {
        font-size: 20px;
    }
    .footer .foot-copy {
        font-size: 16px;
        color: #0D0D0D;
        padding-bottom:10px;
    }
    .footer .foot-link a {
        font-size: 16px;
    }
    .home-banner .hero-section .stores img {
        width: 135px;
        height: 62px;
    }
}
@media (max-width: 575px){

            .trusted-section .stat-box {
  
    border-bottom: 1px solid white;
    border-right:none;
 }
 .trusted-section .stat-box:last-child {
    border-right: none;
 }
 .trusted-section h6 {
    font-size: 22px;
 } 
 .works .how-it-works h2 {
    font-size: 22px;
 }
 .works .how-it-works h3 {
    font-size: 22px;
 }
 .feature-app h2 {
    font-size: 22px;
 }
 .feature-app h5 {
    font-size: 20px;
 }
 .testimonial h2 {
    font-size: 22px;
 }
 .smooth-ride h3 {
    font-size: 22px;
 }
}
@media (max-width:480px){
          .home-banner .hero-section h2 {
    font-size: 22px;
 }
 .smooth-ride p.dark-avail {
    font-size: 18px;
 }
}
    </style>
</head>

<body>
    </div>



<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('homeindex') }}"><img src="{{ asset($setting->website_logo_dark) }}" alt="logo" width="300"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('homeindex') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">About</a>
                </li>
                
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('services') }}"> Services</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('termscondition') }}">Terms & Conditions</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link"href="{{ route('contact_us') }}">Contact Us</a>
              </li>

                
            </ul>
        </div>
    </div>
</nav>
<!-- Navbar End -->
    <!-------------- header ---------->
    <header class="main-header d-none" id="header">
        <div class="container">
            <nav class="navbar navbar-expand-sm navbar-dark p-0" aria-label="Third navbar example">
                <div class="col-md-4">
                    

                    <a class="navbar-brand gap-3" href="{{ route('homeindex') }}"><img src="{{ asset($setting->website_logo_dark) }}" alt="logo" width="200"></a>
                </div>
                <div class="col-md-8 text-end">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse d-inline-block" id="navbarsExample03">
                        <ul class="navbar-nav me-auto mb-sm-0 ">
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('homeindex') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('about') }}">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('termscondition') }}">Terms & Conditions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contact_us') }}">Contact Us</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('services') }}"> Services</a>
                            </li>
                        </ul>
                    </div>
                    <!--  <a href="#" class="btn">Help center</a> -->
                </div>
        </div>
        </nav>
        </div>
    </header>
