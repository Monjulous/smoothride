@extends('admin.layouts.master')

@section('page_title')
Booking
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
					<h3 class="page-title">Bookings</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="javascript:void(0)">Booking</a>
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
								<th class="">Pick Location</th>
								<th class="">Drop Location</th>
								<th class="">Date</th>
								<th class="">Time</th>
								<th class="">Passenger count</th>
								<th class="">Booked sheet</th>
								<th class="">Small bag</th>
								<th class="">Hand bag</th>
								<th class="">Regular bag</th>
								<th class="">Oversize bag</th>
								<th class="">Price</th>
								<th class="">Instruction</th>
								<th class="">Action</th>
							</tr>
						</thead>

						<tbody>
							    @foreach($booking_ride as $value)

                                <tr>
                                	<td>{{@$value['pick_location']}}</td>
                                	<td>{{@$value['pick_location']}}</td>
                                	<td>{{@$value['date']}}</td>
                                	<td>{{@$value['time']}}</td>
                                	<td>{{@$value['passenger_count']}}</td>
                                	<td>{{@$value['total_passenger']}}</td>
                                	<td>{{@$value['small_bag']}}</td>
                                	<td>{{@$value['hand_bag']}}</td>
                                	<td>{{@$value['regular_bag']}}</td>
                                	<td>{{@$value['oversize_bag']}}</td>
                                	<td>{{@$value['price']}}</td>
                                	<td>{{@$value['instruction']}}</td>
                                	<td> <a href="{{ route('viewapplyedusers',$value['id'])}}">View Apply users</a></td>
                                </tr>
							    @endforeach
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