@extends('admin.layouts.master')

@section('page_title')
   Offers
@endsection

@push('css')
    <style>
        .table tr td {
            vertical-align: middle;
        }
    </style>

<style>
    .d-none {
        display: none;
    }
</style>

<style>
    .tab-content {
      border: 1px solid #dee2e6;
      border-top: none;
      padding: 20px;
    }
    .image-preview {
      position: relative;
      display: inline-block;
      max-width: 100%;
      margin-top: 15px;
    }
    .image-preview img {
      max-width: 300px;
      border: 1px solid #ccc;
      padding: 5px;
    }
    .remove-image {
      position: absolute;
      top: 5px;
      right: 5px;
      background: red;
      color: white;
      border: none;
      border-radius: 50%;
      width: 25px;
      height: 25px;
      text-align: center;
      cursor: pointer;
    }
    .card-box {
      border: 1px solid #dee2e6;
      border-radius: 5px;
      padding: 20px;
      margin-bottom: 20px;
      background-color: #f9f9f9;
    }
    .section-title {
      font-weight: 600;
      font-size: 1.2rem;
      margin-bottom: 15px;
    }
    
  </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header">

        

        <div class="card breadcrumb-card">
            <div class="row justify-content-between align-content-between" style="height: 100%;">
                <div class="col-md-6">
                    <h3 class="page-title">Services</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active-breadcrumb">
                            <a href="javascript:void(0)">Services</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div><!-- /card finish -->
    </div><!-- /Page Header -->



    <div class="row">
    
        <div class="col-md-12 ">

        
            <div class="card">

            <div class="card-body">

             @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            <form method="POST" action="{{ route('offer.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Title --}}
                <div class="row">
                    

                {{-- Image Upload --}}
                    <div class="mb-3 col">
                        <label for="offer_image" class="form-label">Offer Image</label>
                        <input type="file" class="form-control" id="offer_image" name="offer_image" accept="image/*" onchange="previewImage(event)" required>
                        
                        {{-- Preview Container --}}
                        <div id="image-preview-container" class="mt-3" style="position: relative; display: none;">
                            <img id="image-preview" src="#" alt="Preview" style="max-height: 200px; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                            <button type="button" onclick="removeImage()" style="position: absolute; top: 0; right: 0; background: #dc3545; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; font-size: 14px;">&times;</button>
                        </div>
                    </div>

                     {{-- Type --}}
                    <div class="mb-3 col">
                        <label for="type" class="form-label">Offer Type <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select form-control" required>
                            <option value="">Select Type</option>
                            <option value="coupon" {{ old('type') == 'coupon' ? 'selected' : '' }}>Coupon</option>
                            <option value="informative" {{ old('type') == 'informative' ? 'selected' : '' }}>Informative</option>
                        </select>
                    </div>
                </div>
               

               

                
                <div class="row">

                {{-- Coupon Code --}}
                <div class="mb-3 col">
                    <label for="coupon_code" class="form-label">Coupon Code</label>
                    <input type="text" id="coupon_code" name="coupon_code" class="form-control" value="{{ old('coupon_code') }}" placeholder="E.g. SAVE20" maxlength="8">
                </div>

                {{-- Discount --}}
                <div class="mb-3 col">
                    <label for="discount" class="form-label">Discount</label>
                    <div class="input-group">
                        <input type="number" id="discount" name="discount" class="form-control" value="{{ old('discount') }}" step="0.01" min="0">
                        <select name="discount_type" class="form-select ">
                            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>%</option>
                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        </select>
                    </div>
                </div>

                </div>
                

                <div class="row">

                 {{-- Start Date --}}
                <div class="mb-3 col">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="datetime-local" id="start_date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                </div>

                {{-- End Date --}}
                <div class="mb-3 col">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="datetime-local" id="end_date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                </div>


                </div>
               
                {{-- Is Active --}}
                <div class="form-check form-switch mb-4">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-dark">Save Offer</button>
            </form>
        </div>

        </div>
    </div>
    </div>


    <script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('image-preview-container');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        const input = document.getElementById('offer_image');
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('image-preview-container');

        input.value = '';
        preview.src = '#';
        container.style.display = 'none';
    }
</script>


@endsection