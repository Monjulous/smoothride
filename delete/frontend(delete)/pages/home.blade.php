@include('frontend/layouts/header')
<!----------------- banner ----------------->
<section class="banner">
      <div class="banner_in" style="background-image:url(./assets/front/images/banner.png);">
         <div class="container">
         <h2>Complete Survey to get a free<br><b>Ultra pro uv mini snap!</b></h2>
      
      </div>
         <img class="imgb" src="/assets/front/images/bannerig.png" class="w-100">
      </div>
</section>


<!----------------- Auction ----------------->
<section class="auction">
<div class="container">
<h2>Auctions <a href="products_page.php">View more</a></h2>
<div class="owl-carousel auction_carousel owl.carousel.min owl-theme mt-3">
@foreach($auction as $k => $auctiond)
<div class="item">
<div class="set">
   <input type="hidden" value="{{count($auction)}}" id="auctionId"/>
   <input type="hidden" value="{{$auctiond['datetime_timestamp']}}" id="{{'auction'.$k}}" />
<div class="set_body">
<span class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
<ul class="dropdown-menu" aria-labelledby="dropdown06">
<div class="usr">
<div class="images">
<img src="images/user3.png" alt="img">
<img class="plus" src="/assets/front/images/union.png" alt="img">
</div>
<h5>User name <br>123 <span>Followers</span></h5>
</div>
<p><img src="/assets/front/images/frame_icon.png" alt="icon"> 5/5 (<span>24</span> Review)</p> 
<a class="dropdown-item" href="#"><img src="/assets/front/images/menuicon2.png" alt="icon"> Message user</a>
<a class="dropdown-item" href="#"><img src="/assets/front/images/menuicon4.png" alt="icon"> View other listing</a>
<a class="dropdown-item" href="#"><img src="/assets/front/images/singico2.png" alt="icon"> Report this post</a>
<a class="dropdown-item" href="#"><img src="/assets/front/images/singico3.png" alt="icon"> Share</a>
</ul>
</span>
<div class="imgs text-center">
<div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
@php
$images = explode("|",$auctiond['images']);
@endphp

@foreach($images as $image)
<div class="item">
<a href="single_product.php">
<img class="w-auto" src="{{'/image/'.$image}}" alt="pro">
</a>
</div>
@endforeach
</div>
</div>
<h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
<h5 class="text-start"><span class="sa1">{{$auctiond['edition']}}</span><span class="sa2 float-end">{{$auctiond['conditions']}}</span></h5>
</div>
<div class="set_footer">
<h4>{{$auctiond['name']}} <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
<div class="stp">
<div class="row">
<div class="col-7">
<p>Remaining time</p>
<h3 class="{{'slash'.$k}}"></h3>
</div>
<div class="col-5 text-end">
<p>Market Price</p>
<h2>${{$auctiond['startingPrice']}}</h2>
</div>
</div>
</div>
<div class="stp st2">
<div class="row">
<div class="col-6">
<p>Current bids</p>
<h3 class="prc">$ 0.99</h3>
</div>
<div class="col-6 text-end">
<p>Shipping</p>
<h2>$ {{$auctiond['shipping']}}</h2>
</div>
<div class="col-12 text-end">
<h2 class="meet">Meetup/Drop off Available</h2>
</div>
<div class="col-6">
<p>Minimal Increment</p>
<h3 class="prc">$ {{$auctiond['increment']}}</h3>
</div>
<div class="col-6 text-end">
<p>29 current bids</p>
<div class="imglist">
<img src="/assets/front/images/user1.jpg">
<img src="/assets/front/images/user2.jpg">
<img src="/assets/front/images/user1.jpg">
</div>
</div>
</div>
</div>
<form class="d-flex">
<input class="form-control" name="amount" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
<input class="form-control" name="id" value="{{$auctiond['id']}}"  type="hidden">
<button class="btn btn-outline-success" type="submit">Enter bid</button>
</form>
<a class="btnBuy blu" href="single_product.php">Buy Out - $5</a>
</div>
</div>
</div>
@endforeach

</section> 


<!----------------- sales ----------------->
<section class="auction sales">
<div class="container">
<h2>Sales <a href="products_page.php">View more</a></h2>
<div class="owl-carousel auction_carousel owl.carousel.min owl-theme">

<!-- item 1-->
@foreach($sell as $k => $selld)
<div class="item">
<div class="set">
<div class="set_body">
<span class="nav-item dropdown">
<input type="hidden" value="{{count($sell)}}" id="sellId"/>
<input type="hidden" value="{{$selld['datetime_timestamp']}}" id="{{'sell'.$k}}" />
<a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
<ul class="dropdown-menu" aria-labelledby="dropdown06">
<li><a class="dropdown-item" href="#">Action</a></li>
<li><a class="dropdown-item" href="#">Another action</a></li>
<li><a class="dropdown-item" href="#">Something else here</a></li>
</ul>
</span>
<div class="imgs text-center">
<div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">

@php
$images = explode("|",$selld['images']);
@endphp
@foreach($images as $image)
<div class="item">
<a href="single_product.php">
<img class="w-auto" src="{{'/image/'.$image}}" alt="pro">
</a>
</div>
@endforeach
</div>
</div>
<h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
<h5 class="text-start"><span class="sa1">{{$selld['edition']}}</span><span class="sa2 float-end">{{$selld['conditions']}}</span></h5>
</div>
<div class="set_footer">
<h4>{{$selld['name']}} <span>x1</span> <b><i class="bi bi-cart2"></i></b></h4>
<div class="stp">
<div class="row">
<div class="col-7">
<p>Remaining time</p>
<h3 class="{{'selling'.$k}}"></h3>
</div>
<div class="col-5 text-end">
<p>Market Price</p>
<h2>${{$selld['startingPrice']}}</h2>
</div>
</div>
</div>
<div class="stp st2">
<div class="row">
<div class="col-6">
<p>Sale Price</p>
<h3 class="prc">$ {{$selld['startingPrice']}}</h3>
</div>
<div class="col-6 text-end">
<p>Shipping</p>
<h2>$ {{$selld['shipping']}}</h2>
</div>
<div class="col-12 text-end">
<h2 class="meet">Meetup/Drop off Available</h2>
</div>
<div class="col-6">
</div>
<div class="col-6 text-end">
<p>29 Added to cart</p>
<div class="imglist">
<img src="/assets/front/images/user1.jpg">
<img src="/assets/front/images/user2.jpg">
<img src="/assets/front/images/user1.jpg">
</div>
</div>
</div>
</div>
<form class="d-flex">
<input class="form-control" type="search" placeholder="Enter offer eg. “$ 1.99”" aria-label="Search">
<button class="btn btn-outline-success" type="submit">Send Offer</button>
</form>
<a class="btnBuy" href="single_product.php">Buy Out - $5</a>
</div>
</div>
</div>
@endforeach
</div>
</div>
</section>



<!----------------- Following Users ----------------->
<section class="auction">
   <div class="container">
      <h2>Following Users <a href="products_page.php">View more</a></h2>
      <div class="owl-carousel auction_carousel owl.carousel.min owl-theme mt-3">

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy blu" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy blu" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

      </div>
   </div>
 
</section>



<!----------------- Listings You Might Like... ----------------->
<section class="auction sales">
   <div class="container">
      <h2>Listings You Might Like...<a href="products_page.php">View more</a></h2>
      <div class="owl-carousel auction_carousel owl.carousel.min owl-theme mt-3">

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                          <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex entergray">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex entergray">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>


<!----------------- Trending ----------------->
<section class="auction">
   <div class="container">
      <h2>Trending<a href="products_page.php">View more</a></h2>
      <div class="owl-carousel auction_carousel owl.carousel.min owl-theme mt-3">

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="/assets/front/images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>

         <div class="item">
            <div class="set">
               <div class="set_body">
                  <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><img src="images/Vector.png" alt="pro"></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                     </ul>
                  </span>
                  <div class="imgs text-center">
                     <div class="owl-carousel imagecarousel owl.carousel.min owl-theme mt-2">
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                        <div class="item">
                           <a href="single_product.php">
                           <img class="w-auto" src="/assets/front/images/pro1.jpg" alt="pro">
                           </a>
                        </div>
                     </div>
                  </div>
                  <h5 class="text-start mt-3">Edition <span class="float-end">Condition</span></h5>
                  <h5 class="text-start"><span class="sa1">Saviors of Kamigawa</span><span class="sa2 float-end">Near Mint</span></h5>
               </div>
               <div class="set_footer">
                  <h4>Sakashima the Imposter <span>x1</span> <b><img src="/assets/front/images/bd.jpg" alt="bd"></b></h4>
                  <div class="stp">
                     <div class="row">
                        <div class="col-7">
                           <p>Remaining time</p>
                           <h3>03h : 02m : 47s</h3>
                        </div>
                        <div class="col-5 text-end">
                           <p>Market Price</p>
                           <h2>$5.99</h2>
                        </div>
                     </div>
                  </div>
                  <div class="stp st2">
                     <div class="row">
                        <div class="col-6">
                           <p>Current bids</p>
                           <h3 class="prc">$ 0.99</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>Shipping</p>
                           <h2>$ 1.50</h2>
                        </div>
                        <div class="col-12 text-end">
                           <h2 class="meet">Meetup/Drop off Available</h2>
                        </div>
                        <div class="col-6">
                           <p>Minimal Increment</p>
                           <h3 class="prc">$ 1.50</h3>
                        </div>
                        <div class="col-6 text-end">
                           <p>29 current bids</p>
                           <div class="imglist">
                              <img src="/assets/front/images/user1.jpg">
                              <img src="/assets/front/images/user2.jpg">
                              <img src="/assets/front/images/user1.jpg">
                           </div>
                        </div>
                     </div>
                  </div>
                  <form class="d-flex">
                     <input class="form-control" type="search" placeholder="Enter minimium $ 1.99" aria-label="Search">
                     <button class="btn btn-outline-success" type="submit">Enter bid</button>
                  </form>
                  <a class="btnBuy" href="single_product.php">Buy Out - $5</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@include('frontend/layouts/footer')