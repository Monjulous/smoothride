@extends('admin.layouts.master')
@section('page_title')
    {{__('dashboard.title')}}
@endsection
@push('css')
    <style>
    .kpi-card { background:#fff; border-radius:24px; padding:20px 22px; border:1px solid #e2e8f0; box-shadow:0 8px 22px rgba(15,23,42,.05); }
    .kpi-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; }
    .kpi-icon { width:34px; height:34px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700; }
    .kpi-icon.yellow { background:#fef9c3; color:#ca8a04; }
    .kpi-icon.blue { background:#dbeafe; color:#2563eb; }
    .kpi-icon.red { background:#ffe4e6; color:#e11d48; }
    .kpi-change { border-radius:999px; padding:4px 10px; font-size:11px; font-weight:700; }
    .kpi-change.green { background:#dcfce7; color:#15803d; }
    .kpi-change.pink { background:#ffe4e6; color:#be123c; }
    .kpi-card .kpi-number { font-size:42px; font-weight:900; line-height:1; color:#0f172a; }
    .kpi-card .kpi-label { font-size:11px; color:#64748b; letter-spacing:.12em; text-transform:uppercase; font-weight:700; margin-top:6px; }
    .kpi-track { height:6px; background:#eef2f7; border-radius:999px; margin-top:10px; overflow:hidden; }
    .kpi-fill { height:100%; border-radius:999px; }
    .panel { background:#fff; border:1px solid #e2e8f0; border-radius:24px; padding:20px; box-shadow:0 8px 22px rgba(15,23,42,.05); }
    .bookings-table thead th { font-size:10px; letter-spacing:.09em; text-transform:uppercase; color:#94a3b8; background:#fff !important; border-bottom:1px solid #eef2f7 !important; }
    .bookings-table td { font-size:13px; font-weight:600; color:#334155; }
    .status-pill { border-radius:999px; padding:4px 10px; font-size:11px; font-weight:700; display:inline-block; }
    .status-pill.cancelled { background:#fee2e2; color:#b91c1c; }
    </style>
@endpush

@section('content')   

	<div class="page-header">
		<div class="card breadcrumb-card">
			<div class="row justify-content-between align-items-end" style="height: 100%;">
				<div class="col-md-8">
                    <div class="admin-ui-kicker">
                        <span>Dashboard</span>
                        <span class="sep">›</span>
                        <span class="current">Overview</span>
                    </div>
					<h3 class="page-title mb-0">Overview</h3>
					<ul class="breadcrumb mb-0">
						<li class="breadcrumb-item active-breadcrumb">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
					</ul>
				</div>
				<div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <a href="javascript:void(0)" class="font-weight-bold" style="color:#2563eb;font-size:0.9375rem;">Export Records</a>
				</div>
			</div>
		</div><!-- /card finish -->	
	</div><!-- /Page Header -->
    <div class="row">
        <div class="col-md-4">
            <div class="kpi-card">
                <div class="kpi-head">
                    <div class="kpi-icon yellow">~</div>
                    <span class="kpi-change green">~ +12%</span>
                </div>
                <div class="kpi-number">{{ @$user['route_count'] }}</div>
                <div class="kpi-label">Ongoing Rides</div>
                <div class="kpi-track"><div class="kpi-fill" style="width:40%;background:#facc15;"></div></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card">
                <div class="kpi-head">
                    <div class="kpi-icon blue">o</div>
                    <span class="kpi-change green">~ +8%</span>
                </div>
                <div class="kpi-number">{{ @$user['Apply_ride'] }}</div>
                <div class="kpi-label">Completed Today</div>
                <div class="kpi-track"><div class="kpi-fill" style="width:70%;background:#4f46e5;"></div></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card">
                <div class="kpi-head">
                    <div class="kpi-icon red">x</div>
                    <span class="kpi-change pink">~ -4%</span>
                </div>
                <div class="kpi-number">{{ @$user['user_count'] }}</div>
                <div class="kpi-label">Cancelled Rides</div>
                <div class="kpi-track"><div class="kpi-fill" style="width:35%;background:#f43f5e;"></div></div>
            </div>
        </div>
    </div>
    <div class="row mt-4 mb-5">
        <div class="col-md-12">
            <div class="panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 font-weight-bold">Recent Bookings</h5>
                    <a href="javascript:void(0)" class="font-weight-bold" style="color:#1d4ed8;">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table bookings-table mb-0">
                        <thead>
                            <tr>
                                <th>Booking No.</th>
                                <th>Type</th>
                                <th>Distance</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="text-right">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#CC8942</td>
                                <td>#INSTANT</td>
                                <td>2.4 km</td>
                                <td>Cash</td>
                                <td><span class="status-pill cancelled">Canceled</span></td>
                                <td class="text-right">$12.50</td>
                            </tr>
                            <tr>
                                <td>#CC8939</td>
                                <td>#SCHEDULED</td>
                                <td>5.1 km</td>
                                <td>Wallet</td>
                                <td><span class="status-pill cancelled" style="background:#dcfce7;color:#166534;">Completed</span></td>
                                <td class="text-right">$24.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

      
@endsection




@push('scripts')

@endpush