@extends('admin.layouts.master')

@section('page_title')
Cancel Booking's
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
					<h3 class="page-title">Cancel Booking</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="javascript:void(0)">Cancel Booking's</a>
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
					<table class="table table-hover table-center mb-0" id="table">
						<thead>
							<tr>
								<th class="">Driver Name</th>
								<th class="">User Name</th>
								<th class="">Pickup  Location</th>
								<th class="">Drop Location</th>
								<th class="">Date</th>
								<th class="">Time</th>
								<th class="">Passenger count</th>
								<!-- <th class="">Small bag</th> -->
								<!-- <th class="">Hand bag</th> -->
								<!-- <th class="">Regular bag</th> -->
								<!-- <th class="">Oversze bag</th> -->
								<th class="">Price</th>
								<!-- <th class="">Instruction</th> -->
							</tr>
						</thead>

						<tbody>
							        
						</tbody>
					</table>
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