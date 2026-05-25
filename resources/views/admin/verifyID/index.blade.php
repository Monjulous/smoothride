@extends('admin.layouts.master')

@section('page_title')
    {{__('currency.index.title')}}
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
					<h3 class="page-title">VerifyId</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="{{ route('currencies.index') }}">VerifyId</a>
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
								<th class="">Name</th>
								<th class="">Email</th>
								<th class="">Phone No</th>
								<th class="">Type</th>
								<th class="">Image</th>
								<!-- <th class="">is_verifyId</th>
								<th class="">is_verifyNumber</th>
								<th class="">is_verifyEmail</th> -->
								<th class="">Created At</th>

							
							</tr>
						</thead>
                            	 @foreach($verify_id as $value)
                                       
                                       <tr>
                                       	<td>{{@$value['first_name']}} {{@$value['last_name']}}</td>
                                       	<td>{{@$value['email']}}</td>
                                       	<td>{{@$value['phone_no']}}</td>
                                       	<td>{{@$value['type']}}</td>
                                       	<td>{{@$value['image']}}</td>
                                        <!-- <td><button class="btn btn-primary"><?php echo $value['is_verifyId']==1 ? 'Verifyed' : 'Verify' ?></button></td>
                                       	<td><button class="btn btn-primary"><?php echo $value['is_verifyNumber']==1 ? 'Verifyed' : 'Verify' ?></button></td>
                                       	<td><button class="btn btn-primary"><?php echo $value['is_verifyEmail']==1 ? 'Verifyed' : 'Verify' ?></button></td>  -->
                                       	<td>{{@$value['created_at']}}</td>
                                       </tr>
                            	 @endforeach

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
		$('#table').DataTable({
			processing	: true,
			responsive 	: false,
			serverSide	: true,
			order:       [[0, 'desc' ]],
			ajax 		: '{{ route('currencies.index') }}',
			columns			: [
					{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
					{ data: 'name', name: 'name' },
					{ data: 'code', name: 'code' },
					{ data: 'symbol', name: 'symbol' },
					{ data: 'status', name: 'status' },						        

					@if(Gate::check('currency-edit') || Gate::check('currency-delete'))
						{ data: 'action', name: 'action', orderable: false, searchable: false}
					@endif 
				],
		});
	});
</script>

<script type="text/javascript">
	$("body").on("click",".remove-currency",function(){
		var current_object = $(this);
		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this data!",
			type: "error",
			showCancelButton: true,
			dangerMode: true,
			cancelButtonClass: '#DD6B55',
			confirmButtonColor: '#dc3545',
			confirmButtonText: 'Delete!',
		},function (result) {
			if (result) {
				var action = current_object.attr('data-action');
				var token = jQuery('meta[name="csrf-token"]').attr('content');
				var id = current_object.attr('data-id');

				$('body').html("<form class='form-inline remove-form' method='POST' action='"+action+"'></form>");
				$('body').find('.remove-form').append('<input name="_method" type="hidden" value="post">');
				$('body').find('.remove-form').append('<input name="_token" type="hidden" value="'+token+'">');
				$('body').find('.remove-form').append('<input name="id" type="hidden" value="'+id+'">');
				$('body').find('.remove-form').submit();
			}
		});
	});
</script>

<script type="text/javascript">
	function changeCurrencieStatus(_this, id) {
		var status = $(_this).prop('checked') == true ? 1 : 0;
		let _token = $('meta[name="csrf-token"]').attr('content');

		$.ajax({
			url: `{{route('currencies.status_update')}}`,
			type: 'get',
			data: {
				_token: _token,
				id: id,
				status: status 
			},
			success: function (result) {
				if(status == 1){
                    	toastr.success(result.message);
                	}else{
                    	toastr.error(result.message);
                	} 
			}
		});
	}
</script>
@endpush