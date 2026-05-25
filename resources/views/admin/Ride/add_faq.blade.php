@extends('admin.layouts.master')

@section('page_title')
FAQ
@endsection

@push('css')
	<style>
		.table tr td{
			vertical-align: middle;
		}
	</style>
@endpush

@section('content')
	 <!-- Page Header -->
	 <div class="page-header">
		<div class="card breadcrumb-card">
			<div class="row justify-content-between align-content-between" style="height: 100%;">
				<div class="col-md-6">
					<h3 class="page-title">FAQ</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="javascript:void(0)">FAQ</a>
						</li>
					</ul>
				</div>
			</div>
		</div><!-- /card finish -->	
	</div><!-- /Page Header -->


	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
				  <form action="{{route('submit_faq')}}" method="post">
				  	@csrf
				  	 <div class="form-group">
				  	 	<label><h5>Question</h5></label>
				  	 	<input     name="que" value="" class="form-control" placeholder="Question ?" required />
				  	 </div>
				  	  <div class="form-group">	
				  	   <label><h5>Answer</h5></label>
				  	   <textarea class="form-control" name="ans" placeholder="write here " required></textarea>	
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


@endpush