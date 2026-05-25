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
								<th class="">Passenger count</th>
								<th class="">Small bag</th>
								<th class="">Hand bag</th>
								<th class="">Regular bag</th>
								<th class="">Oversize bag</th>
								<th class="">Price</th>
							</tr>
						</thead>

						<tbody>
							    @foreach($applyride as $value)

                                <tr>
                                	<td>{{@$value['passenger_count']}}</td>
                                	<td>{{@$value['total_passenger']}}</td>
                                	<td>{{@$value['small_bag']}}</td>
                                	<td>{{@$value['hand_bag']}}</td>
                                	<td>{{@$value['regular_bag']}}</td>
                                	<td>{{@$value['oversize_bag']}}</td>
                                	<td>{{@$value['price']}}</td>
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