@extends('admin.layouts.master')

@section('page_title')
Vehicle Model
@endsection

@push('css')
    <style>
        .table tr td{
            vertical-align: middle;
        }
        /* Style for the pagination alignment */
        .pagination-container {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
@endpush

@section('content')
     <div class="page-header">
        <div class="card breadcrumb-card">
            <div class="row justify-content-between align-content-between" style="height: 100%;">
                <div class="col-md-6">
                    @if(session()->has('success'))
                        <div class="alert alert-success" >
                           <p style="color: green;"> {{ session()->get('success') }}</p>
                        </div>
                    @endif
                    <h3 class="page-title">Vehicle Model</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active-breadcrumb"><a href="#">Vehicle Model</a></li>
                    </ul>
                </div>
            </div>
        </div> 
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between">
                        <div>
                            Show 
                            <select onchange="window.location.href='?per_page='+this.value">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select> 
                            entries
                        </div>
                    </div>

                    <table class="table table-hover table-center mb-0" id="table">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Vehicle Brand Name</th>
                                <th>Vehicle Model Name</th>
                                <th>Created At</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($model as $value)
                                <tr>
                                    <td>{{ ($model->currentPage() - 1) * $model->perPage() + $loop->iteration }}</td>
                                    <td>{{ $value->brand_name }}</td>
                                    <td>{{ $value->model_name }}</td>
                                    <td>{{ date('d, M Y', strtotime($value->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="pagination-container">
                        <div>
                            Showing {{ $model->firstItem() }} to {{ $model->lastItem() }} of {{ $model->total() }} entries
                        </div>
                        <div>
                            {{ $model->appends(request()->input())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Vehicle Model</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="{{route('addcarmoel')}}">
        @csrf
      <div class="modal-body">
        <label><h6>Vehicle Brand</h6></label>
       <select class="form-control" name="vehiclebrand" required>
       @foreach($brand as $value)
         <option value="{{ $value->id }}">{{ $value->brand_name }}</option>
       @endforeach
       </select>
       <label class="mt-2"><h6>Vehicle Model Name</h6></label>
        <input class="form-control" type="text" name="model_name" placeholder="Enter Model Name" required />
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
    $(function() {
        // Disable DataTables native paging because Laravel is handling it now
        $('#table').DataTable({
            "paging": false,
            "info": false,
            "searching": true 
        });
    });
</script>
@endpush