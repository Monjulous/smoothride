@extends('admin.layouts.master')

@section('page_title')
    Edit Privacy Policy
@endsection

@push('css')
    <style>
        .table tr td {
            vertical-align: middle;
        }
    </style>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<style>
/* Override Bootstrap conflicts */
.page-wrapper { background: #f8fafc; }
.card { border: none !important; box-shadow: none !important; }

/* Custom Overrides for this CMS Page */
.tab-content {
    background: transparent;
    border: none;
    padding: 24px 0;
}
.nav-tabs {
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    gap: 8px;
    padding: 0 4px;
    background: #f8fafc;
    border-radius: 16px;
    overflow-x: auto;
}
.nav-tabs .nav-link {
    border: none !important;
    color: #64748b;
    font-weight: 900;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 12px 20px;
    border-radius: 12px;
    transition: all 0.2s;
    background: transparent;
}
.nav-tabs .nav-link:hover {
    color: #0f172a;
    background: #f1f5f9;
}
.nav-tabs .nav-item.show .nav-link, 
.nav-tabs .nav-link.active {
    color: #eab308 !important;
    background: #fefce8 !important;
    box-shadow: inset 0 0 0 1px #fef08a;
}
.card-box, .form-section, .bg-white.p-4 {
    background: white;
    border: 1px solid #f1f5f9;
    border-radius: 32px;
    padding: 32px !important;
    margin-bottom: 24px;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}
.section-title, h4, h5 {
    font-size: 1.25rem !important;
    font-weight: 900 !important;
    color: #1e293b !important;
    margin-bottom: 24px !important;
    letter-spacing: -0.025em;
}
.form-group label {
    font-size: 10px;
    font-weight: 900;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 8px;
    display: block;
}
.form-control {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 12px 16px;
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
    transition: all 0.2s;
}
.form-control:focus {
    background: #ffffff;
    border-color: #facc15;
    box-shadow: 0 0 0 4px rgba(250, 204, 21, 0.1);
}
.btn-primary, .btn-success, .btn-dark {
    background: #facc15 !important;
    color: #0f172a !important;
    border: none !important;
    border-radius: 16px !important;
    padding: 12px 32px !important;
    font-size: 12px !important;
    font-weight: 900 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.1em !important;
    box-shadow: 0 4px 14px 0 rgba(250, 204, 21, 0.39) !important;
    transition: all 0.2s !important;
}
.btn-primary:hover, .btn-success:hover, .btn-dark:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(250, 204, 21, 0.4) !important;
}
.image-preview img, .img-preview img {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}
.remove-image, .remove-btn {
    background: #ef4444 !important;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
    z-index: 10;
}
</style>
@endpush

@section('content')
    <div class="space-y-6 pb-12 px-6 pt-6 animate-in fade-in duration-700">
    <div class="space-y-1">
        <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            <span>CMS</span>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-yellow-600 font-black uppercase">Homepage</span>
        </div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">Homepage CMS</h2>
    </div>

    @if ($errors->any())
        <div class="fixed top-24 right-8 z-[200] px-6 py-4 rounded-2xl shadow-2xl flex items-center space-x-3 border bg-slate-900 border-red-500/20 text-white transition-all">
            <div class="p-2 rounded-xl bg-red-500/10">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-400"></i>
            </div>
            <div class="text-sm font-black tracking-wide">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-12">
             <!-- Nav Tabs -->
                <ul class="nav nav-tabs" id="cmsTabs" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" id="banner-tab" data-toggle="tab" href="#banner" role="tab">Banner Section</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="info-tab" data-toggle="tab" href="#infotab" role="tab">Info Section</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="works-tab" data-toggle="tab" href="#works" role="tab">How it works</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="choose-tab" data-toggle="tab" href="#choose" role="tab">Why Choose us</a>
                    </li>

                    <li class="nav-item">
                    <a class="nav-link" id="testimonial-tab" data-toggle="tab" href="#testimonial" role="tab">Testimonial</a>
                    </li>

                    <li class="nav-item">
                    <a class="nav-link" id="action-tab" data-toggle="tab" href="#action" role="tab">Call to Action</a>
                    </li>

                   

                    <li class="nav-item">
                    <a class="nav-link" id="about-tab" data-toggle="tab" href="#about" role="tab">About Us</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="service-tab" data-toggle="tab" href="#service" role="tab">Service </a>
                    </li>

                    <li class="nav-item">
                    <a class="nav-link" id="setting-tab" data-toggle="tab" href="#setting" role="tab">Settings</a>
                    </li>
                  
                </ul>

               


                  <!-- Tab Content -->
             <div class="tab-content" id="cmsTabsContent">
                  <div class="tab-pane fade show active" id="banner" role="tabpanel">
                    <h4>Banner Section</h4>
                        <!-- Form -->
                        <form action="{{route('store_banner')}}" method="POST" enctype="multipart/form-data" class="bg-white p-4" >
                            @csrf
                             <input type="hidden" name="id" value="{{ $banner->id }}">
                            <!-- Title -->
                            <div class="form-group">
                            <label for="bannerTitle">Title</label>
                            <input type="text" class="form-control" id="bannerTitle" name="title" value="{{ $banner->title }}" required>
                            </div>

                            <!-- Sub-title -->
                            <div class="form-group">
                            <label for="bannerSubtitle">Sub-title</label>
                            <input type="text" class="form-control" id="bannerSubtitle" name="sub_title"  value="{{ $banner->sub_title }}">
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                            <label for="bannerDescription">Description</label>
                            <textarea class="form-control" id="bannerDescription" name="desc" rows="4" value="{{ $banner->desc }}">{{ $banner->desc }}</textarea>
                            </div>

                            <!-- Image Upload -->
                            <div class="form-group">
                           
                            <label for="bannerImage">Banner Image</label>
                            <input type="file" class="form-control-file" id="bannerImage" name="image[]" accept="image/*" multiple>
                            <!-- <small class="form-text text-muted">Recommended size: 1200x400px</small> -->
                             
                             @if($banner->image)
                                        @php
                                        $image =explode(',',$banner->image);
                                        @endphp

                                        @foreach ($image as $key=>$img)

                                              <div id="imagePreview" class="image-preview" >
                                                        <button type="button" class="remove-image" id="removeImage">&times;</button>
                                                    

                                                        <img id="previewImg" src="{{ asset($img) }}" alt="Image Preview">
                                                    
                                                        <input type="hidden" name="old_card_image[]" value="{{ $img }}">

                                                    </div>
                                                                
                                        @endforeach
                                @endif
                            
                            <!-- Image Preview -->
                          
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn btn-primary">Save Banner</button>
                        </form>
                      <!-- End Form -->
                    </div>
                    <div class="tab-pane fade" id="infotab" role="tabpanel">
                    <h4>Info</h4>
                      <!-- Info Form -->

                      <form action="{{ route('store_info_section') }}" method="POST" class="bg-white p-4">
                        @csrf
                        <input type="hidden" name="id" value="{{ $info->id }}">

                            <!-- Main Section Title -->
                            <div class="form-group">
                            <label for="mainTitle">Title</label>
                            <input type="text" class="form-control" id="mainTitle" name="title" value="{{ $info->title }}" placeholder="e.g., Our Features" required>
                            </div>

                             <?php
                                $title=explode(',',$info->card_title);
                                $desc=explode(',',$info->card_desc);
                                $image=explode(',',$info->card_img);

                             ?>
                            <!-- Card Row 1 -->
                            <div class="row">
                            <div class="col-md-6">
                                <div class="card-box">
                                <div class="section-title">Card 1</div>
                                <div class="form-group">
                                    <label for="cardTitle1">Card Title</label>
                                    <input type="text" class="form-control" id="cardTitle1" name="card_title[]" value="{{ @$title[0] }}" >
                                </div>
                                <div class="form-group">
                                    <label for="cardDesc1">Card Description</label>
                                    <textarea class="form-control" id="cardDesc1" name="card_desc[]" rows="3" value="{{ @$desc[0] }}" >{{ @$desc[0] }}</textarea>
                                </div>
                                <div class="form-group">
                                        <label>Card Image</label>
                                        <input type="file" class="form-control-file card-image-input" name="card_image[]" accept="image/*">
                                        <div class="image-preview">
                                        <button type="button" class="remove-image">&times;</button>
                                        <img src="{{ url('/'.@$image[0]) }}" alt="Preview">
                                        <input type="hidden" name="old_card_image[0]" value="{{ @$image[0] }}">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card-box">
                                <div class="section-title">Card 2</div>
                                <div class="form-group">
                                    <label for="cardTitle2">Card Title</label>
                                    <input type="text" class="form-control" id="cardTitle2" name="card_title[]"  value="{{ @$title[1] }}">
                                </div>
                                <div class="form-group">
                                    <label for="cardDesc2">Card Description</label>
                                    <textarea class="form-control" id="cardDesc2" name="card_desc[]" rows="3"  value="{{ @$desc[1] }}">{{ @$desc[1] }}</textarea>
                                </div>
                                <div class="form-group">
                                        <label>Card Image</label>
                                        <input type="file" class="form-control-file card-image-input" name="card_image[]" accept="image/*">
                                        <div class="image-preview">
                                        <button type="button" class="remove-image">&times;</button>
                                        <img src="{{ url('/'.@$image[1]) }}" alt="Preview">
                                        <input type="hidden" name="old_card_image[1]" value="{{ @$image[1] }}">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>

                            <!-- Card Row 2 -->
                            <div class="row">
                            <div class="col-md-6">
                                <div class="card-box">
                                <div class="section-title">Card 3</div>
                                <div class="form-group">
                                    <label for="cardTitle3">Card Title</label>
                                    <input type="text" class="form-control" id="cardTitle3" name="card_title[]"  value="{{ @$title[2] }}">
                                </div>
                                <div class="form-group">
                                    <label for="cardDesc3">Card Description</label>
                                    <textarea class="form-control" id="cardDesc3" name="card_desc[]" rows="3"  value="{{ @$desc[2] }}">{{ @$desc[2] }}</textarea>
                                </div>
                                <div class="form-group">
                                        <label>Card Image</label>
                                        <input type="file" class="form-control-file card-image-input" name="card_image[]" accept="image/*">
                                        <div class="image-preview">
                                        <button type="button" class="remove-image">&times;</button>
                                        <img src="{{ url('/'.@$image[2]) }}" alt="Preview">
                                        <input type="hidden" name="old_card_image[2]" value="{{ @$image[2] }}">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="col-md-6">
                                <div class="card-box">
                                <div class="section-title">Card 4</div>
                                <div class="form-group">
                                    <label for="cardTitle4">Card Title</label>
                                    <input type="text" class="form-control" id="cardTitle4" name="card_title[]"  value="{{ @$title[3] }}">
                                </div>
                                <div class="form-group">
                                    <label for="cardDesc4">Card Description</label>
                                    <textarea class="form-control" id="cardDesc4" name="card_desc[]" rows="3"  value="{{ @$desc[3] }}">{{ @$desc[3] }}</textarea>
                                </div>
                                <div class="form-group">
                                        <label>Card Image</label>
                                        <input type="file" class="form-control-file card-image-input" name="card_image[]" accept="image/*">
                                        <div class="image-preview">
                                        <button type="button" class="remove-image">&times;</button>
                                        <img src="{{ url('/'.@$image[3]) }}" alt="Preview">
                                        <input type="hidden" name="old_card_image[3]" value="{{ @$image[3] }}">

                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn btn-success">Save Section</button>
                        </form>
                      <!-- ENd INfo Form -->
                    </div>
                    <div class="tab-pane fade" id="works" role="tabpanel">
                    <h4>How it works</h4> 
                       <!-- Section start -->
                       <form action="{{ route('store_howitwork_section') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4">
                             <!-- Main Section Title -->
                                @csrf
                                <input type="hidden" name="id" value="{{ $howitwork->id }}" >
                             <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                <label for="mainTitle">Title</label>
                                <input type="text" class="form-control" id="mainTitle" name="title" placeholder="e.g., Our Features" value="{{ $howitwork->title }}" required>
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                <label for="mainTitle">Sub Title</label>
                                <input type="text" class="form-control" id="mainTitle" name="sub_title" placeholder="e.g., Our Features" value="{{ $howitwork->sub_title }}" required>
                                </div>
                                </div>
                                        
                                </div>
                                <?php
                                $title=explode(',',$howitwork->card_title);
                                $desc=explode(',',$howitwork->card_desc);
                                $image=explode(',',$howitwork->card_img);
                                ?>

                                <div class="row">
                                <!-- Loop for 4 cards -->
                                <!-- Card 1 -->
                                <div class="col-md-6">
                                    <div class="card-box">
                                    <div class="section-title">Card 1</div>
                                    <div class="form-group">
                                        <label>Card Title</label>
                                        <input type="text" class="form-control" name="card_title[]" value="{{ @$title[0] }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Description</label>
                                        <textarea class="form-control" name="card_desc[]" rows="3"  value="{{ @$desc[0] }}" required>{{ @$desc[0] }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Image</label>
                                        <input type="file" class="form-control-file card-image-input" name="card_image[]" accept="image/*">
                                        <div class="image-preview">
                                        <button type="button" class="remove-image">&times;</button>
                                        <img src="{{ url('/'.@$image[0]) }}" alt="Preview">
                                        <input type="hidden" name="old_card_image[0]" value="{{ @$image[0] }}">

                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <!-- Card 2 -->
                                <div class="col-md-6">
                                    <div class="card-box">
                                    <div class="section-title">Card 2</div>
                                    <div class="form-group">
                                        <label>Card Title</label>
                                        <input type="text" class="form-control" name="card_title[]"  value="{{ @$title[1] }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Description</label>
                                        <textarea class="form-control" name="card_desc[]" rows="3" required value="{{ @$desc[1] }}">{{ @$desc[1] }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Image</label>
                                        <input type="file" class="form-control-file card-image-input" name="card_image[]" accept="image/*">
                                        <div class="image-preview">
                                        <button type="button" class="remove-image">&times;</button>
                                        <img src="{{ url('/'.@$image[1]) }}" alt="Preview">
                                        <input type="hidden" name="old_card_image[1]" value="{{ @$image[1] }}">
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <!-- Card 3 -->
                                <div class="col-md-6">
                                    <div class="card-box">
                                    <div class="section-title">Card 3</div>
                                    <div class="form-group">
                                        <label>Card Title</label>
                                        <input type="text" class="form-control" name="card_title[]"  value="{{ @$title[2] }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Description</label>
                                        <textarea class="form-control" name="card_desc[]" rows="3" required value="{{ @$desc[2] }}">{{ @$desc[2] }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Image</label>
                                        <input type="file" class="form-control-file card-image-input" name="card_image[]" accept="image/*">
                                        <div class="image-preview">
                                        <button type="button" class="remove-image">&times;</button>
                                        <img src="{{ url('/'.@$image[2]) }}" alt="Preview">
                                        <input type="hidden" name="old_card_image[2]" value="{{ @$image[2] }}">


                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <!-- Card 4 -->
                                <div class="col-md-6">
                                    <div class="card-box">
                                    <div class="section-title">Card 4</div>
                                    <div class="form-group">
                                        <label>Card Title</label>
                                        <input type="text" class="form-control" name="card_title[]" value="{{ @$title[3] }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Description</label>
                                        <textarea class="form-control" name="card_desc[]" rows="3" required value="{{ @$desc[3] }}">{{ @$desc[3] }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Image</label>
                                        <input type="file" class="form-control-file card-image-input" name="card_image[]" accept="image/*">
                                        <div class="image-preview" >
                                        <button type="button" class="remove-image">&times;</button>
                                        <img src="{{ url('/'.@$image[3]) }}" alt="Preview">
                                        <input type="hidden" name="old_card_image[3]" value="{{ @$image[3] }}">

                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>

                                <button type="submit" class="btn btn-success">Save Section</button>
                            </form>

                        <!-- Section end -->
                    </div>
                    <div class="tab-pane fade" id="choose" role="tabpanel">
                    <h4>Why Choose Us</h4>
                      <!-- Section start -->
                      <form action="{{ route('store_whychooseus_section') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4">
                             <!-- Main Section Title -->
                             @csrf
                             <input type="hidden" name="id" value="{{ $whychooseus->id }}" >

                             <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                <label for="mainTitle">Title</label>
                                <input type="text" class="form-control" id="mainTitle" name="title" placeholder="e.g., Our Features"  value="{{ $whychooseus->title }}" required>
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                <label for="mainTitle">Sub Title</label>
                                <input type="text" class="form-control" id="mainTitle" name="sub_title" placeholder="e.g., Our Features" value="{{ $whychooseus->sub_title }}" required>
                                </div>
                                </div>
                                    
                                </div>

                                <?php
                                $title=explode(',',$whychooseus->card_title);
                                $desc=explode(',',$whychooseus->card_desc);
                                $image=explode(',',$whychooseus->card_img);
                                ?>
                                <div class="row">
                                <!-- Loop for 4 cards -->
                                <!-- Card 1 -->
                                <div class="col-md-6">
                                    <div class="card-box">
                                    <div class="section-title">Card 1</div>
                                    <div class="form-group">
                                        <label>Card Title</label>
                                        <input type="text" class="form-control" name="card_title[]"  value="{{ @$title[0] }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Description</label>
                                        <textarea class="form-control" name="card_desc[]" rows="3" required value="{{ @$desc[0] }}"> {{ @$desc[0] }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Image</label>
                                        <input type="file" class="form-control-file card-image-input" name="card_image[]" accept="image/*">
                                        <div class="image-preview">
                                        <button type="button" class="remove-image">&times;</button>
                                        <img src="{{ url('/'.@$image[0]) }}" alt="Preview">
                                        <input type="hidden" name="old_card_image[0]" value="{{ @$image[0] }}">

                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <!-- Card 2 -->
                                <div class="col-md-6">
                                    <div class="card-box">
                                    <div class="section-title">Card 2</div>
                                    <div class="form-group">
                                        <label>Card Title</label>
                                        <input type="text" class="form-control" name="card_title[]" required value="{{ @$title[1] }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Card Description</label>
                                        <textarea class="form-control" name="card_desc[]" rows="3" required value="{{ @$desc[1] }}">{{ @$desc[1] }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Image</label>
                                        <input type="file" class="form-control-file card-image-input" name="card_image[]" accept="image/*">
                                        <div class="image-preview" >
                                        <button type="button" class="remove-image">&times;</button>
                                        <img src="{{ url('/'.@$image[1]) }}" alt="Preview">
                                        <input type="hidden" name="old_card_image[1]" value="{{ @$image[1] }}">

                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <!-- Card 3 -->
                                <div class="col-md-6">
                                    <div class="card-box">
                                    <div class="section-title">Card 3</div>
                                    <div class="form-group">
                                        <label>Card Title</label>
                                        <input type="text" class="form-control" name="card_title[]" value="{{ @$title[2] }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Description</label>
                                        <textarea class="form-control" name="card_desc[]" rows="3" required value="{{ @$title[2] }}">{{ @$desc[2] }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Image</label>
                                        <input type="file" class="form-control-file card-image-input" name="card_image[]" accept="image/*">
                                        <div class="image-preview" >
                                        <button type="button" class="remove-image">&times;</button>
                                        <img src="{{ url('/'.@$image[2]) }}" alt="Preview">
                                        <input type="hidden" name="old_card_image[2]" value="{{ @$image[2] }}">


                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <!-- Card 4 -->
                                <div class="col-md-6">
                                    <div class="card-box">
                                    <div class="section-title">Card 4</div>
                                    <div class="form-group">
                                        <label>Card Title</label>
                                        <input type="text" class="form-control" name="card_title[]" value="{{ @$title[3] }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Description</label>
                                        <textarea class="form-control" name="card_desc[]" rows="3" required value="{{ @$desc[3] }}">{{ @$desc[3] }} </textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Image</label>
                                        <input type="file" class="form-control-file card-image-input" name="card_image[]" accept="image/*">
                                        <div class="image-preview" >
                                        <button type="button" class="remove-image">&times;</button>
                                        <img src="{{ url('/'.@$image[3]) }}" alt="Preview">
                                        <input type="hidden" name="old_card_image[3]" value="{{ @$image[3] }}">


                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>

                                <button type="submit" class="btn btn-success">Save Section</button>
                            </form>

                        <!-- Section end -->
                    </div>

                    <div class="tab-pane fade" id="testimonial" role="tabpanel">
                        <div class="d-flex justify-content-between mb-3">
                        <h4>Testimonial Title & Sub Title</h4>
                        <button type="button" class="btn btn-dark float-right" data-toggle="modal" data-target="#addreview">Add Testimonial</button>
            
                        </div>

                        <!-- Modal -->

                        <div class="modal fade" id="addreview" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add New Testimonial</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                           
                            <div class="modal-body">
                           
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('testimonial.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" 
                                        class="form-control @error('name') is-invalid @enderror" 
                                        value="{{ old('name') }}" required>
                                
                                </div>

                                <div class="mb-3">
                                    <label for="designation" class="form-label">Location</label>
                                    <input type="text" name="location" id="location" 
                                        class="form-control @error('location') is-invalid @enderror" 
                                        value="{{ old('location') }}">
                                
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea name="message" id="message" rows="4" required 
                                            class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                                    
                                </div>

                                <div class="mb-3">
                                    <label for="photo" class="form-label">Photo</label>
                                    <input type="file" name="photo" id="bannerImage" 
                                        class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                                
                                </div>

                                <button type="submit" class="btn btn-primary">Add Testimonial</button>
                            </form>
                            </div>
                            
                        </div>
                        </div>
                    </div>
                        <!-- End Modal -->
                    
                      <!-- Section start -->
                      <form action="{{ route('store_testimonial_section') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4">
                             <!-- Main Section Title -->
                                @csrf
                                <input type="hidden" name="id" value="{{ $testimonial->id }}">
                             <div class="row">
                                <div class="col-md-12">
                                <div class="form-group">
                                <label for="mainTitle">Title</label>
                                <input type="text" class="form-control" id="mainTitle" name="title" placeholder="e.g., Our Features" value="{{ $testimonial->title }}" required>
                                </div>
                                </div>
                                <div class="col-md-12">
                                <div class="form-group">
                                <label for="mainTitle">Sub Title</label>
                                <input type="text" class="form-control" id="mainTitle" name="sub_title" placeholder="e.g., Our Features" value="{{ $testimonial->sub_title }}" required>
                                </div>
                                </div>
                                        
                                </div>

                                

                                <button type="submit" class="btn btn-success">Save Section</button>
                            </form>

                        <!-- Section end -->
                    </div>
                    <div class="tab-pane fade" id="action" role="tabpanel">
                    <h4>Call To Action</h4>
                     <!-- Section start -->
                     <form action="{{ route('store_calltoaction_section') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4">
                        @csrf
                             <!-- Main Section Title -->
                                    <input type="hidden" name="id" value="{{ $calltoaction->id }}">
                             <div class="row">
                                <div class="col-md-12">
                                <div class="form-group">
                                <label for="mainTitle">Title</label>
                                <input type="text" class="form-control" id="mainTitle" name="title" placeholder="e.g., Our Features" value="{{ $calltoaction->title }}" required>
                                </div>
                                </div>
                                <div class="col-md-12">
                                <div class="form-group">
                                <label for="mainTitle">Sub Title</label>
                                <input type="text" class="form-control" id="mainTitle" name="sub_title" placeholder="e.g., Our Features" value="{{ $calltoaction->sub_title }}" required>
                                </div>
                                </div>
                                
                                        
                                </div>

                                

                                <button type="submit" class="btn btn-success">Save Section</button>
                            </form>

                        <!-- Section end -->
                    </div>


                    <div class="tab-pane fade" id="setting" role="tabpanel">
                    <h4>Website Setting</h4>
                     <!-- Section start -->
                     <form action="{{ route('update_setting') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4">
                                
                     @csrf
                            <!-- Website Info Section -->
                             <input type="hidden" name="id" value="{{ $setting->id }}">
                            <div class="form-section">
                            <h5 class="mb-3">Website Info</h5>
                            <div class="form-group">
                                <label for="websiteTitle">Website Title</label>
                                <input type="text" class="form-control" id="websiteTitle" name="website_title" placeholder="Enter website title"  value="{{ $setting->website_title }}"/>
                            </div>
                            </div>
                            
                            <!-- Logos & Favicon Section -->
                            <div class="form-section">
                            <h5 class="mb-3">Logos & Favicon</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                <label for="websiteLogoDark">Logo Dark</label>
                                <input type="file" class="form-control-file" id="websiteLogoDark" name="website_logo_dark" accept="image/*" />
                                <div class="img-preview" id="preview-websiteLogoDark">
                                     
                                    <div class="">
                                        <img src="{{ asset($setting->website_logo_dark) }}" alt="" class="w-100 h-100">
                                        <input type="hidden"  name="old_website_logo_dark" value="{{ $setting->website_logo_dark }}">

                                     </div>
                                </div>
                                
                                </div>
                                <div class="form-group col-md-6">
                                <label for="websiteLogoLight">Logo Light</label>

                                <input type="file" class="form-control-file" id="websiteLogoLight" name="website_logo_light" accept="image/*" />
                                <div class="img-preview" id="preview-websiteLogoLight">
                                <div class="img-preview">
                                        <img src="{{ asset($setting->website_logo_light) }}" alt="">
                                <input type="hidden"  name="old_website_logo_light" value="{{ $setting->website_logo_light}}">

                                        
                                     </div>
                                </div>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="websiteLogoSmall">Logo Small</label>

                                <input type="file" class="form-control-file" id="websiteLogoSmall" name="website_logo_small" accept="image/*" />
                                <div class="img-preview" id="preview-websiteLogoSmall">
                                <div class="img-preview">
                                        <img src="{{ asset($setting->website_logo_small) }}" alt="">
                                        <input type="hidden"  name="old_website_logo_small" value="{{ $setting->website_logo_small }}">

                                     </div>
                                </div>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="websiteFavicon">Favicon</label>

                                <input type="file" class="form-control-file" id="websiteFavicon" name="website_favicon" accept="image/*" />
                                <div class="img-preview" id="preview-websiteFavicon">
                                <div class="img-preview">
                                        <img src="{{ asset($setting->website_favicon) }}" alt="">

                                         <input type="hidden"  name="old_website_favicon" value="{{ $setting->website_favicon }}">

                                     </div>
                                
                                 </div>
                                </div>
                            </div>
                            </div>
                            
                            <!-- SEO Section -->
                            <div class="form-section">
                            <h5 class="mb-3">SEO & Meta Data</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                <label for="metaTitle">Meta Title</label>
                                <input type="text" class="form-control" id="metaTitle" name="meta_title" placeholder="Enter meta title"  value="{{ $setting->meta_title }}"/>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="metaDescription">Meta Description</label>
                                <textarea class="form-control" id="metaDescription" name="meta_description" rows="2" placeholder="Enter meta description" value="{{ $setting->meta_description }}"></textarea>
                                </div>
                            </div>
                            </div>
                            
                            <!-- Contact Info Section -->
                            <div class="form-section">
                            <h5 class="mb-3">Contact Information</h5>
                            <div class="form-row">
                                  <div class="form-group col-md-6">
                                <label for="address">Head Office</label>
                                <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter address" value="{{ $setting->address }}">{{ $setting->address }}</textarea>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="address">Branch Office</label>
                                <textarea class="form-control" id="address" name="address2" rows="2" placeholder="Enter address" value="{{ $setting->address2 }}">{{ $setting->address2 }}</textarea>
                                </div>
                                <div class="form-group col-md-4">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" value="{{ $setting->phone }}" />
                                </div>
                                <div class="form-group col-md-4">
                                <label for="phone">Whatsapp</label>
                                <input type="text" class="form-control" id="phone" name="whatsapp" placeholder="Enter  number" value="{{ $setting->whatsapp }}" />
                                </div>
                                <div class="form-group col-md-4">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address"  value="{{ $setting->email }}"/>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="address"> Business Hours</label>
                                <textarea class="form-control" id="address" name="business_hours" rows="2" placeholder="Enter Monday to Saturday: 9:00 AM – 9:00 PM Sunday: 10:00 AM – 6:00 PM" value="{{ $setting->business_hours }}">{{ $setting->business_hours }}</textarea>
                                </div>
                               
                            </div>
                           
                            </div>
                            
                            <!-- Social Media Section -->
                            <div class="form-section">
                            <h5 class="mb-3">Social Media Links</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                <label for="facebook">Facebook URL</label>
                                <input type="url" class="form-control" id="facebook" name="facebook" placeholder="https://facebook.com/yourpage"  value="{{ $setting->facebook }}"/>
                                </div>

                                <div class="form-group col-md-6">
                                <label for="instagram">Instagram URL</label>
                                <input type="url" class="form-control" id="instagram" name="instagram" placeholder="https://instagram.com/yourpage"  value="{{ $setting->instagram }}"/>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="twitter">Twitter URL</label>
                                <input type="url" class="form-control" id="twitter" name="twitter" placeholder="https://twitter.com/yourhandle"  value="{{ $setting->twitter }}"/>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="linkedin">LinkedIn URL</label>
                                <input type="url" class="form-control" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/yourprofile" value="{{ $setting->linkedin }}" />
                                </div>
                            </div>
                            </div>

                            <div class="form-section">
                            <h5 class="mb-3">App Download  inks</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                <label for="playstore">PlayStore URL</label>
                                <input type="url" class="form-control"  name="playstore" placeholder="Enter PlayStore Link here..."  value="{{ $setting->playstore }}"/>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="appstore">App Store URL</label>
                                <input type="url" class="form-control"  name="appstore" placeholder="Enter AppStore Link here..."  value="{{ $setting->appstore }}"/>
                                </div>
                               
                            </div>
                            </div>
                            
                            <!-- Google API Key Section -->
                            <div class="form-section">
                            <h5 class="mb-3">Google API Key</h5>
                            <div class="form-group">
                                <input type="text" class="form-control" id="googleApiKey" name="google_key" placeholder="Enter Google API Key" value="{{ $setting->google_key }}" />
                            </div>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-lg ">Save Settings</button>
                           
                        </form>

                        <!-- Section end -->
                    </div>

                    <div class="tab-pane fade" id="about" role="tabpanel">
                        <h4>About us</h4>
                        

                      
                      <!-- Section start -->
                      <form action="{{ route('store_about_us') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4">
                             <!-- Main Section Title -->
                                @csrf
                                <input type="hidden" name="id" value="{{ @$about->id }}">
                             <div class="row">
                                <div class="col-md-12">
                                <div class="form-group">
                                <label for="mainTitle">Title</label>
                                <input type="text" class="form-control" id="mainTitle" name="title" placeholder="" value="{{ @$about->title }}" required>
                               
                            </div>
                                </div>
                                <div class="col-md-12">
                                <div class="form-group">
                                <label for="mainTitle">Sub Title</label>
				  	         	<textarea class="ckeditor form-control" rows="5" name="sub_title">{{ @$about->sub_title }}</textarea>	

                              
                            </div>
                                </div>
                                        
                                </div>

                                

                                <button type="submit" class="btn btn-success">Save Section</button>
                            </form>

                        <!-- Section end -->
                    </div>

                    <div class="tab-pane fade" id="service" role="tabpanel">
                        <h4> Service page</h4>
                        

                      
                      <!-- Section start -->
                      <form action="{{ route('store_service_heading') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4">
                             <!-- Main Section Title -->
                                @csrf
                                <input type="hidden" name="id" value="{{ @$service->id }}">
                             <div class="row">
                                <div class="col-md-12">
                                <div class="form-group">
                                <label for="mainTitle">Title</label>
                                <input type="text" class="form-control" id="mainTitle" name="title" placeholder="" value="{{ @$service->title }}" required>
                               
                            </div>
                                </div>
                                <div class="col-md-12">
                                <div class="form-group">
                                <label for="mainTitle">Sub Title</label>
				  	         	<textarea class="ckeditor form-control" rows="5" name="sub_title">{{ @$service->sub_title }}</textarea>	

                              
                            </div>
                                </div>
                                        
                                </div>

                                

                                <button type="submit" class="btn btn-success">Save Section</button>
                            </form>

                        <!-- Section end -->
                    </div>

                </div>
            </div>
        
      

        <div class="col-md-12 d-none">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('update_home', $homedata->id) }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label>
                                <h5>Banner Title</h5>
                            </label>
                            <input type="text" name="banner_title" class="form-control"
                                value="{{ $homedata->banner_title }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <h5>Description</h5>
                            </label>
                            <textarea class="summernote form-control" rows="5" name="banner_desc">{{ $homedata->banner_desc }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <h5>Url1</h5>
                                    </label>
                                    <input type="text" name="url1" class="form-control" value="{{ $homedata->url1 }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <h5>Url2</h5>
                                    </label>
                                    <input type="text" name="url2" class="form-control" value="{{ $homedata->url2 }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <h5>Title</h5>
                            </label>
                            <input type="text" name="title" class="form-control" value="{{ $homedata->title }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <h5>Description1</h5>
                            </label>
                            <textarea class="summernote form-control" rows="5" name="desc1">{{ $homedata->desc1 }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                <h5>Description2</h5>
                            </label>
                            <textarea class="summernote form-control" rows="5" name="desc2">{{ $homedata->desc2 }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                <h5>Description3</h5>
                            </label>
                            <textarea class="summernote form-control" rows="5" name="desc3">{{ $homedata->desc3 }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                <h5>Description4</h5>
                            </label>
                            <textarea class="summernote form-control" rows="5" name="desc4">{{ $homedata->desc4 }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                <h5>Footer Content</h5>
                            </label>
                            <textarea class="summernote form-control" rows="5" name="footer_text">{{ $homedata->footer_text }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                <h5>Contact Content</h5>
                            </label>
                            <textarea class="summernote form-control" rows="5" name="contact_text">{{ $homedata->contact_text }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                <h5>Contact Address</h5>
                            </label>
                            <textarea class="summernote form-control" rows="5" name="contact_address">{{ $homedata->contact_address }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                <h5>Contact Phone</h5>
                            </label>
                            <input type="text" name="contact_phone" class="form-control"
                                value="{{ $homedata->contact_phone }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <h5>Contact Email</h5>
                            </label>
                            <input type="text" name="contact_email" class="form-control"
                                value="{{ $homedata->contact_email }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <h5>Contact Fax</h5>
                            </label>
                            <input type="text" name="contact_fax" class="form-control"
                                value="{{ $homedata->contact_fax }}">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#table').DataTable();
        });
    </script>

    <!-- <script src="//cdn.summernote.com/4.14.1/standard/summernote.js"></script> -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote();
        });
    </script>
 <script>
    // Single Image
        const bannerImage = document.getElementById('bannerImage');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const removeBtn = document.getElementById('removeImage');

        bannerImage.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.setAttribute('src', e.target.result);
                imagePreview.style.display = 'inline-block';
            };
            reader.readAsDataURL(file);
            }
        });

        removeBtn.addEventListener('click', function () {
            bannerImage.value = '';
            previewImg.src = '#';
            imagePreview.style.display = 'none';
        });


        //  
   $(document).ready(function () {
    $('.card-image-input').on('change', function () {
      const input = this;
      const previewContainer = $(this).siblings('.image-preview');
      const img = previewContainer.find('img')[0];

      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          $(img).attr('src', e.target.result);
          previewContainer.show();
        };
        reader.readAsDataURL(input.files[0]);
      }
    });

    $('.remove-image').on('click', function () {
      const preview = $(this).closest('.image-preview');
      const input = preview.siblings('.card-image-input');

      input.val(''); // clear file input
      preview.hide().find('img').attr('src', '#');
    });
  });



  document.addEventListener("DOMContentLoaded", function () {
    const fileInputs = ["websiteLogoDark", "websiteLogoLight", "websiteLogoSmall", "websiteFavicon"];

    fileInputs.forEach((inputId) => {
      const input = document.getElementById(inputId);
      const previewContainer = document.getElementById(`preview-${inputId}`);

      input.addEventListener("change", function () {
        // Clear existing preview
        previewContainer.innerHTML = "";

        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
            // Create image element
            const img = document.createElement("img");
            img.src = e.target.result;
            img.alt = inputId + " preview";

            // Create container div
            const imgWrapper = document.createElement("div");
            imgWrapper.classList.add("img-preview");

            // Create remove button
            const removeBtn = document.createElement("button");
            removeBtn.type = "button";
            removeBtn.classList.add("remove-btn");
            removeBtn.innerHTML = "&times;";
            removeBtn.title = "Remove image";

            removeBtn.addEventListener("click", () => {
              input.value = ""; // Clear file input
              previewContainer.innerHTML = "";
            });

            imgWrapper.appendChild(img);
            imgWrapper.appendChild(removeBtn);

            previewContainer.appendChild(imgWrapper);
          };
          reader.readAsDataURL(file);
        }
      });
    });

    // document.getElementById("settingsForm").addEventListener("submit", function (e) {
    //   e.preventDefault();
    //   // Your form submission logic here
    //   alert("Settings saved!");
    // });
  });
</script>
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>

@endpush
