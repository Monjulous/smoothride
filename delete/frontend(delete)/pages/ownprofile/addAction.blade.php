@include('frontend/layouts/header')
<section class="addcard create_auc">
   <div class="container">
      <h3>Listing Type</h3>
      <!-- <form> -->
      <!-- @csrf -->
         <ul class="nav nav-tabs onoff" id="myTab" role="tablist">
            <li role="presentation">
               <span class="nav-link active" id="Auction-tab" data-bs-toggle="tab" data-bs-target="#Auction-tab-pane" role="tab" aria-controls="Auction-tab-pane" aria-selected="true">Auction</span>
            </li>
            <li role="presentation">
               <span class="nav-link" id="Sell-tab" data-bs-toggle="tab" data-bs-target="#Sell-tab-pane" role="tab" aria-controls="Sell-tab-pane" aria-selected="false">Sell</span>
            </li>
         </ul>
         <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="Auction-tab-pane" role="tabpanel" aria-labelledby="Auction-tab" tabindex="0">
            @if(Session::has('success'))
            <div class="alert alert-success">
                  {{ Session::get('success') }}
                  @php
                     Session::forget('success');
                  @endphp
            </div>
            @endif

            @if(Session::has('error'))
            <div class="alert alert-danger">
                  {{ Session::get('error') }}
                  @php
                     Session::forget('error');
                  @endphp
            </div>
            @endif

            <form action="{!! url('/Auction') !!}" method="POST"  enctype="multipart/form-data"> 
                @csrf  
                <div class="row">
                  <div class="col-sm-12">
                     <div class="imagefile">
                        <span class="up"><i class="bi bi-camera-fill"></i>
                        <input type='file' onchange="readURL(this);" name="images[]" multiple/>
                        </span>
                        <span class="img">
                        <img class="blah" src="/assets/front/images/upld.jpg" alt="" />
                        </span>
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <h5>Enter Card Details</h5>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder="Name of Card" >
                        @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="text" name="edition" value="{{old('edition')}}" class="form-control" placeholder="Edition">
                        @if ($errors->has('edition'))
                        <span class="text-danger">{{ $errors->first('edition') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="text" name="rarity" value="{{old('rarity')}}" class="form-control" placeholder="Rarity">
                        @if ($errors->has('rarity'))
                        <span class="text-danger">{{ $errors->first('rarity') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="text" name="conditions" value="{{old('conditions')}}" class="form-control" placeholder="Condition">
                        @if ($errors->has('conditions'))
                        <span class="text-danger">{{ $errors->first('conditions') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="text" name="language" value="{{old('language')}}" class="form-control" placeholder="language">
                        @if ($errors->has('language'))
                        <span class="text-danger">{{ $errors->first('language') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="number" name="quantity" value="{{old('quantity')}}" class="form-control" placeholder="Quantity">
                        @if ($errors->has('quantity'))
                        <span class="text-danger">{{ $errors->first('quantity') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <h5>Listing Details</h5>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="number" name="price" value="{{old('price')}}" class="form-control" placeholder="Starting Price">
                        @if ($errors->has('price'))
                        <span class="text-danger">{{ $errors->first('price') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="number" name="increment" value="{{old('increment')}}" class="form-control" placeholder="Munimum Increment">
                        @if ($errors->has('increment'))
                        <span class="text-danger">{{ $errors->first('increment') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="datetime-local" value="{{old('datetime')}}" name="datetime" class="form-control" placeholder="End Date and Time">
                        @if ($errors->has('datetime'))
                        <span class="text-danger">{{ $errors->first('datetime') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <h6><label class="checkcontainer"><input type="checkbox" name="buyOut" checked="checked"><span class="radiobtn"></span></label> Buy Out Option</h6>
                     <h6><label class="checkcontainer"><input type="checkbox" name="bestOffer" checked="checked"><span class="radiobtn"></span></label> Accept Best Offer</h6>
                     <h6><label class="checkcontainer"><input type="checkbox" name="startDate" checked="checked"><span class="radiobtn"></span></label> Schedule Start Date</h6>
                  </div>
                  <div class="col-sm-12">
                     <h5>Shipping Details</h5>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="number" name="shipping" value="{{old('shipping')}}" class="form-control" placeholder="Shipping">
                        @if ($errors->has('shipping'))
                        <span class="text-danger">{{ $errors->first('shipping') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="number" name="cost" value="{{old('cost')}}" class="form-control" placeholder="Cost">
                        @if ($errors->has('cost'))
                        <span class="text-danger">{{ $errors->first('cost') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <h6><label class="checkcontainer"><input type="checkbox" name="meet" checked="checked"><span class="radiobtn"></span></label>Look Meet - Up / Drop off</h6>
                     <h6><label class="checkcontainer"><input type="checkbox" name="international" checked="checked"><span class="radiobtn"></span></label> Accept International (comming soon)</h6>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <textarea class="form-control" name="information" value="{{old('information')}}" placeholder="Additional Information"></textarea>
                        @if ($errors->has('information'))
                        <span class="text-danger">{{ $errors->first('information') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <h5>Additional Information</h5>
                     <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                  </div>
                  <div class="col-sm-12">
                     <button type="submit" class="btn">Piblish Item</button>
                  </div>
               </div>
                </form>
            </div>
            <div class="tab-pane fade" id="Sell-tab-pane" role="tabpanel" aria-labelledby="Sell-tab" tabindex="0">
            <form action="{!! url('/Sell') !!}" method="POST"  enctype="multipart/form-data"> 
            @csrf  
                <div class="row">
                  <div class="col-sm-12">
                     <div class="imagefile">
                        <span class="up"><i class="bi bi-camera-fill"></i>
                        <input type='file' onchange="readURL(this);" name="images[]" multiple/>
                        </span>
                        <span class="img">
                        <img class="blah" src="/assets/front/images/upld.jpg" alt="" />
                        </span>
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <h5>Enter Card Details</h5>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder="Name of Card" >
                        @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="text" name="edition" value="{{old('edition')}}" class="form-control" placeholder="Edition">
                        @if ($errors->has('edition'))
                        <span class="text-danger">{{ $errors->first('edition') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="text" name="rarity" value="{{old('rarity')}}" class="form-control" placeholder="Rarity">
                        @if ($errors->has('rarity'))
                        <span class="text-danger">{{ $errors->first('rarity') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="text" name="conditions" value="{{old('conditions')}}" class="form-control" placeholder="Condition">
                        @if ($errors->has('conditions'))
                        <span class="text-danger">{{ $errors->first('conditions') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="text" name="language" value="{{old('language')}}" class="form-control" placeholder="language">
                        @if ($errors->has('language'))
                        <span class="text-danger">{{ $errors->first('language') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="number" name="quantity" value="{{old('quantity')}}" class="form-control" placeholder="Quantity">
                        @if ($errors->has('quantity'))
                        <span class="text-danger">{{ $errors->first('quantity') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <h5>Listing Details</h5>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="number" name="price" value="{{old('price')}}" class="form-control" placeholder="Price">
                        @if ($errors->has('price'))
                        <span class="text-danger">{{ $errors->first('price') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="datetime-local" value="{{old('datetime')}}" name="datetime" class="form-control" placeholder="End Date and Time">
                        @if ($errors->has('datetime'))
                        <span class="text-danger">{{ $errors->first('datetime') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <h6><label class="checkcontainer"><input type="checkbox" name="buyOut" checked="checked"><span class="radiobtn"></span></label> Buy Out Option</h6>
                     <h6><label class="checkcontainer"><input type="checkbox" name="bestOffer" checked="checked"><span class="radiobtn"></span></label> Accept Best Offer</h6>
                     <h6><label class="checkcontainer"><input type="checkbox" name="startDate" checked="checked"><span class="radiobtn"></span></label> Schedule Start Date</h6>
                  </div>
                  <div class="col-sm-12">
                     <h5>Shipping Details</h5>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="number" name="shipping" value="{{old('shipping')}}" class="form-control" placeholder="Shipping">
                        @if ($errors->has('shipping'))
                        <span class="text-danger">{{ $errors->first('shipping') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <input type="number" name="cost" value="{{old('cost')}}" class="form-control" placeholder="Cost">
                        @if ($errors->has('cost'))
                        <span class="text-danger">{{ $errors->first('cost') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <h6><label class="checkcontainer"><input type="checkbox" name="meet" checked="checked"><span class="radiobtn"></span></label>Look Meet - Up / Drop off</h6>
                     <h6><label class="checkcontainer"><input type="checkbox" name="international" checked="checked"><span class="radiobtn"></span></label> Accept International (comming soon)</h6>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <textarea class="form-control" name="information" value="{{old('information')}}" placeholder="Additional Information"></textarea>
                        @if ($errors->has('information'))
                        <span class="text-danger">{{ $errors->first('information') }}</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <h5>Additional Information</h5>
                     <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                  </div>
                  <div class="col-sm-12">
                     <button type="submit" class="btn">Piblish Item</button>
                  </div>
               </div>
            </form>
            </div>
         </div>
      <!-- </form> -->
   </div>
</section>
@include('frontend/layouts/footer')