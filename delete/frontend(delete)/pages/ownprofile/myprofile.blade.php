@include('frontend/layouts/header')
<section class="dashboard_profile">
   <div class="container">


<div class="dashboard_pro">
   <img class="w-100" src="/assets/front/images/dpro_bg.jpg">
</div>


<div class="row_pro">
<div class="row">
   <div class="col-md-6 dsbuser">
      <div class="images">
                     <img class="w-100" src="/assets/front/images/user1.jpg" alt="img">
                  </div>
                  <h4>Gustavo Torp</h4>
                  <h6>5/5<span>(24 Review)</span></h6>
                  <ul>
                     <li><b>122</b>Followers</li>
                     <li><b>28</b>Following</li>
                     <li><b>50</b>Point</li>
                  </ul>
                  <a class="editbtn" href="#"> <img src="/assets/front/images/user_edit.png" alt="img"> Edit profile</a>
                   <span class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdown06">
                       <li><a class="dropdown-item" href="#"><img src="/assets/front/images/iconC1.png" alt="icon"> Report</a></li>
                        <li><a class="dropdown-item" href="#"><img src="/assets/front/images/iconC2.png" alt="icon"> Share</a></li>
                        <li><a class="dropdown-item" href="#"><img src="/assets/front/images/iconC3.png" alt="icon"> Copy URL</a></li>
                     </ul>
                  </span>
   </div>
   <div class="col-md-6">
      <div class="grp">
      <h3>Transaction history</h3>
      <ul>
         <li><i style="color:#ffbd00;" class="bi bi-circle-fill"></i> Spent</li>
         <li><i class="bi bi-circle-fill"></i> Earned</li>
      </ul>
                  <img class="w-100" src="/assets/front/images/mapopro.jpg" alt="img">
               </div>
   </div>


   <div class="col-12">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
               <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">Dashboard
               </button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false"> Orders
               </button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="tab3-tab" data-bs-toggle="tab" data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false">Bookmarked</button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="tab4-tab" data-bs-toggle="tab" data-bs-target="#tab4" type="button" role="tab" aria-controls="tab4" aria-selected="false">Followed</button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="tab5-tab" data-bs-toggle="tab" data-bs-target="#tab5" type="button" role="tab" aria-controls="tab5" aria-selected="false">Draft</button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="tab6-tab" data-bs-toggle="tab" data-bs-target="#tab6" type="button" role="tab" aria-controls="tab6" aria-selected="false">My cards (coming soon)</button>
            </li>
         </ul>

         <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
               <div class="row">
                  <div class="col-lg-7">
                      <div class="row">
                  <div class="col-sm-6 mb-3">
                     <div class="setnew">
                        <h5>Selling <span class="float-end">01 <i class="bi bi-exclamation-triangle-fill"></i></span></h5>
                        <div class="row selling">
                           <div class="col-6">
                              <p>Inactive listing</p>
                              <h4>01</h4>
                           </div>
                           <div class="col-6">
                              <p>Drafts</p>
                              <h4>01</h4>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col-6">
                              <p>Inactive listing</p>
                              <h4>01</h4>
                           </div>
                           <div class="col-6">
                              <p>Drafts</p>
                              <h4>01</h4>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-6 mb-3">
                     <div class="setnew">
                        <h5>Buying <span class="float-end">01 <i class="bi bi-exclamation-triangle-fill"></i></span></h5>
                        <div class="row selling">
                           <div class="col-6">
                              <p>Bookmarked</p>
                              <h4>01</h4>
                           </div>
                           <div class="col-6">
                              <p>Cart</p>
                              <h4>01</h4>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col-6">
                              <p>Purchased/Won</p>
                              <h4>01</h4>
                           </div>
                           <div class="col-6">
                              <p>Ended</p>
                              <h4>01</h4>
                           </div>
                        </div>
                     </div>
                  </div>

 <div class="col-sm-6 mb-3">
                     <div class="setnew text-center">
                        <h5>Active Listings Available</h5>
                         <img src="/assets/front/images/chart2.jpg" alt="img">
                        <h3>3/5</h3>
                        <p class="pcp">This listing is limited to your account, upgrade to get more access.</p>
                        <a href="#" class="btn">upgrade</a>
                     </div>
               </div>

               <div class="col-sm-6 text-center">
                     <div class="setnew mb-3 spt">
                        <h5>Earn This Month</h5>
                        <div class="sein">
                        <h3>$120.77</h3>
                        <h6>3 Listings</h6>
                     </div>
                     </div>

                     <div class="setnew spt">
                        <h5>Spent This Month</h5>
                        <div class="sein">
                        <h3>$120.77</h3>
                        <h6>3 Listings</h6>
                     </div>
                     </div>
               </div>
                  </div>
               </div>
                  <div class="col-lg-5 tablist2">




<div class="set4 pt-0">

<ul class="nav nav-tabs mt-0" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
               <button class="nav-link active" id="tabb1-tab" data-bs-toggle="tab" data-bs-target="#tabb1" type="button" role="tab" aria-controls="tabb1" aria-selected="true">Mission Board</button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="tabb2-tab" data-bs-toggle="tab" data-bs-target="#tabb2" type="button" role="tab" aria-controls="tabb2" aria-selected="false">Daily</button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="tabb3-tab" data-bs-toggle="tab" data-bs-target="#tabb3" type="button" role="tab" aria-controls="tabb3" aria-selected="false">View More Mission</button>
            </li>
        
         </ul>

<div class="tab-content" id="myTabContent2">
 <div class="tab-pane fade show active" id="tabb1" role="tabpanel" aria-labelledby="tabb1-tab">
   <div class="list">
      <div class="imgs">
         <img src="/assets/front/images/list1.png" alt="icon">
      </div>
      <h5>Complete 1 transaction (0/1)</h5>
      <p>10 <img src="/assets/front/images/list1.png" alt="icon"></p>
   </div>
     <div class="list">
      <div class="imgs">
         <img src="/assets/front/images/list1.png" alt="icon">
      </div>
      <h5>Complete 1 transaction (0/1)</h5>
      <p>10 <img src="/assets/front/images/list1.png" alt="icon"></p>
   </div>
     <div class="list">
      <div class="imgs">
         <img src="/assets/front/images/list_comp.png" alt="icon">
      </div>
      <h5>Complete 1 transaction (0/1)</h5>
      <p class="btn">Collect 10 <img src="/assets/front/images/list1white.png" alt="icon"></p>
   </div>

      <div class="list">
      <div class="imgs">
         <img src="/assets/front/images/list1.png" alt="icon">
      </div>
      <h5>Complete 1 transaction (0/1)</h5>
      <p>10 <img src="/assets/front/images/list1.png" alt="icon"></p>
   </div>

      <div class="list">
      <div class="imgs">
         <img src="/assets/front/images/list_er.png" alt="icon">
      </div>
      <h5 class="mt-2">Select additional Missions</h5>
   </div>
 </div>

  <div class="tab-pane fade" id="tabb2" role="tabpanel" aria-labelledby="tabb2-tab">
 </div>

  <div class="tab-pane fade" id="tabb2" role="tabpanel" aria-labelledby="tabb2-tab">
 </div>
</div>
</div>

                  </div>
               </div>

              
            </div>
            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>
            <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>

            <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>

            <div class="tab-pane fade" id="tab5" role="tabpanel" aria-labelledby="tab5-tab">
               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>

            <div class="tab-pane fade" id="tab6" role="tabpanel" aria-labelledby="tab6-tab">
               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>
         </div>
   </div>


</div>
</div>


   </div>
</section>
@include('frontend/layouts/footer')