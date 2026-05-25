@extends('admin.layouts.master')

@section('page_title')
   Vechile
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
			<div class="row justify-content-between align-items-end" style="height: 100%;">
				<div class="col-md-8">
                    <div class="admin-ui-kicker">
                        <span>Vehicles</span>
                        <span class="sep">›</span>
                        <span class="current">Fleet</span>
                    </div>
					<h3 class="page-title mb-0">Vechile's</h3>
					<ul class="breadcrumb mb-0">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="#">Vechicle</a>
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
                    <div class="table-responsive">
					<table class="table table-hover table-center mb-0" id="table">
						<thead>
							<tr>
								<th class="">Sr No.</th>
								<th class="">Name of Owner</th>
								<th class="">Country</th>
								<th class="">Brand</th>
								<th class="">Model</th>
								<th class="">Color</th>
								<th class="">Plate Number</th>
								<th class="">Model Year</th>
								<th class="">Created At</th>
								<th class="text-right">Action</th>
							</tr>
						</thead>

						<tbody>
							   @foreach($vehicles as $value)
                                <tr>
                                	<td>{{ $loop->iteration < 10 ? '0' . $loop->iteration : $loop->iteration }}</td>
                                	<td>
                                        <span class="user-name-text">{{@$value['name']}} {{@$value['lname']}}</span>
                                    </td>
                                	<td style="font-weight: 700; color: var(--aui-slate-500); white-space: nowrap;">{{@$value['country']}}</td>
                                	<td style="white-space: nowrap;">
                                        <span style="padding: 0.25rem 0.75rem; border-radius: 0.5rem; background-color: #eff6ff; color: #2563eb; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;">
                                            {{@$value['vehicle_brand']}}
                                        </span>
                                    </td>
                                	<td style="font-weight: 700; color: var(--aui-slate-500); white-space: nowrap;">{{@$value['vehicle_model']}}</td>
                                	<td style="white-space: nowrap;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <div style="height: 12px; width: 12px; border-radius: 50%; border: 1px solid #e2e8f0; background-color: {{ @$value['vechicle_color'] == 'Midnight Black' ? '#0f172a' : (@$value['vechicle_color'] == 'Pearl White' ? '#ffffff' : (@$value['vechicle_color'] == 'Deep Crimson' ? '#dc2626' : (@$value['vechicle_color'] == 'Space Grey' ? '#64748b' : '#3b82f6'))) }};"></div>
                                            <span style="font-weight: 700; color: var(--aui-slate-500);">{{@$value['vechicle_color']}}</span>
                                        </div>
                                    </td>
                                	<td style="font-weight: 700; color: var(--aui-slate-700); font-family: monospace; letter-spacing: 0.05em; white-space: nowrap;">{{@$value['plate_number']}}</td>
                                	<td style="font-weight: 900; color: var(--aui-slate-900); white-space: nowrap;">{{@$value['vechicle_madeyear']}}</td>
                                	<td style="font-weight: 700; color: var(--aui-slate-400); white-space: nowrap;"><?php echo date('d, M y',strtotime($value['created_at'])); ?></td>
                                    <td class="text-right">
                                        <div class="action-icons-wrapper">
                                            <a href="{{ route('user_info', @$value['user_id']) }}" class="action-icon-eye">
                                                <i class="fa fa-eye fa-lg"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
							   @endforeach
						</tbody>
					</table>
                    </div>
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




<script type="text/javascript">
	function changeCmspageStatus(_this, id) {
		var status = $(_this).prop('checked') == true ? 1 : 0;
		let _token = $('meta[name="csrf-token"]').attr('content');

		$.ajax({
			url: `{{route('cmspages.status_update')}}`,
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