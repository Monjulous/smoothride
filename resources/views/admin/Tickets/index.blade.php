@extends('admin.layouts.master')
@section('page_title')
@endsection
@section('content')
	<!-- Page Header -->

		<div class="card breadcrumb-card">
			<div class="row justify-content-between align-content-between" style="height: 100%;">
				<div class="col-md-6">
					<h3 class="page-title">Tickets</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="#">Tickets</a>
						</li>
					</ul>
				</div>
			
			</div>
		</div><!-- /card finish -->	
	</div><!-- /Page Header -->
@if (session()->has('success'))
    <h5 style="color: green;">{{ session('success') }}</h5>
@endif
	<div class="row">
		<div class="col-md-12">
			<div class="card">

				<div class="card-body">
					<table class="table table-report" id="role_table">
						<thead>
							<tr>
								<th>Title</th>
								<th>Summary</th>
								<th>User Name</th>
								<th>Ticket Number</th>
								<th>Request Type</th>
								<th>Created At</th>
								<th>Status</th>
								<th>Action</th>

								
							</tr>
						</thead>

						<tbody>
						  @foreach($tickets as $value)
                            <tr>
                            	<td>{{@$value['title']}}</td>
                            	<td>{{@$value['description']}}</td>
                            	<td>{{@$value['name']}} {{@$value['lname']}}</td>
                            	<td>{{@$value['tickets_number']}}</td>
                            	<td>{{@$value['requestType']}}</td>
                            	<td>{{@$value['created_at']}}</td>
                            	<td><?php  echo $value['status']==0 ? 'Active' : 'Close'; ?></td>
                            	 <!-- <a href="{{ route('tiket_statusupdate',$value['id'])}}"> -->
                            	<td><a href="{{ route('ticketchat',$value['id'])}}"><i class="fa fa-comment" ></i></a>  <i class="fa fa-edit" onclick="update_status(<?php echo $value['id'] ?>,<?php echo  $value['status'];  ?>)" data-toggle="modal" data-target="#exampleModalCenter"></i></td>
                            </tr>
 
						  @endforeach
							
						</tbody>
						
					</table>
				</div>
			</div>




		</div>

	</div>
	<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Update Ticket </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form id="ticket_status" method="post" action="{{route('ticket_status')}}">
       	@csrf
       	<input type="hidden" name="id" value="" class="tid">
       	<input type="hidden" name="status" value="" class="tstatus">
       	<div class="row form-group">
       		<select class="form-control" name="status" required>
       			<option value="" selected disabled>Select </option>
       			<option value="0">Active</option>
       			<option value="1">Cancel</option>
       			<option value="2">Resolve</option>
       		</select>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
             </form>

    </div>
  </div>
</div>
@endsection


@push('scripts')
<script>
	$(document).ready( function () {
		$('#role_table').DataTable();
	} );
</script>
<script>
	 
	 function update_status(id,status)
	 {
	    
 
		$('.tid').val(id);
		$('.tstatus').val(status);
	 }
</script>
@endpush




