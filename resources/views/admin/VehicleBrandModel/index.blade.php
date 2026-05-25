@extends('admin.layouts.master')

@section('page_title')
Vehicle Brand
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
	 	@if(session()->has('success'))
        <div class="alert alert-success" >
           <p style="color: green;"> {{ session()->get('success') }}</p>
        </div>
    @endif
	 <div class="page-header">
		<div class="card breadcrumb-card">
			<div class="row justify-content-between align-items-end" style="height: 100%;">
				<div class="col-md-8">
                    <div class="admin-ui-kicker">
                        <span>Vehicles</span>
                        <span class="sep">›</span>
                        <span class="current">Brand & model</span>
                    </div>
					<h3 class="page-title mb-0">Vehicle Brand </h3>
					<ul class="breadcrumb mb-0">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="#">Vehicle Brand</a>
						</li>
					</ul>
				</div>
				<div class="col-md-3">
					<!-- 	<div class="create-btn pull-right">
							<button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add Brand</button>
						</div> -->
					</div>
			
			</div>
		</div><!-- /card finish -->	
	</div><!-- /Page Header -->


	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
    <div class="table-responsive">
    <table class="table table-hover table-center mb-0" id="table">
        <thead>
            <tr>
                <th class="">Sr. No.</th>
                <th class="">Brand Name</th>
                <th class="text-center">Model Name</th>
                <th class="text-right">Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($brand as $key => $value)
            <tr>
                <td>{{ count($brand) - $key < 10 ? '0' . (count($brand) - $key) : count($brand) - $key }}</td>
                <td>
                    <span class="user-name-text">{{ ucwords(@$value['brand_name'])}}</span>
                </td>
                <td class="text-center" style="font-weight: 700; color: var(--aui-slate-900);">{{ ucwords(@$value['model_name']) }}</td>
                <td class="text-right" style="color: var(--aui-slate-400);">{{ date('d, M y', strtotime($value['created_at'])) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
			</div>
		</div>
	</div>
	<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Vehicle Brand</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="{{route('addcarbrand')}}">
      	@csrf
      <div class="modal-body">
       <label><h6>Vehicle Brand Name</h6></label>
        <input class="form-control" type="text" value="" name="brand_name" placeholder="Enter Model Name" required />
       <div>
        
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
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#table')) {
            $('#table').DataTable().destroy();
        }

        $('#table').DataTable({
            "paging": true,          // Enables pagination buttons
            "pageLength": 10,        // Forces 10 rows per page
            "searching": true,       // Enables the search bar
            "info": true,            // Enables "Showing 1 to 10 of 50"
            "order": [],             // Prevents re-sorting your 50 to 1 order
            "columnDefs": [
                { "orderable": false, "targets": 0 } // Stops Sr No from being clickable
            ],
            "language": {
                "paginate": {
                    "previous": "Previous",
                    "next": "Next"
                }
            }
        });
    });
</script>
@endpush