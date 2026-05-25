@extends('admin.layouts.master')

@section('page_title')
    {{ __('user.index.title') }}
@endsection

@push('css')
    <style>
        .table tr td {
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
					<h3 class="page-title">{{ $title }}</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active-breadcrumb">
							<a href="{{ route('users.index') }}">{{ __('user.index.title') }}</a>
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
                                <th class=""> Name</th>
                                <!-- <th class="">Last Name</th> -->
                                <th class="">Mobile Number</th>
                                <th class="">Email</th>
                                <th class="">Gender</th>
                                <!-- <th class="">Bio</th> -->
                                <!-- <th class="">Date of birth</th> -->
                                <th class="">Image</th>
                                <!-- <th class="">User Status</th> -->
                                <th class="">View</th>
                                 <th class="">Chat</th>

                                <!-- <th class="">is_verifyNumber</th> -->
                                <!-- <th class="">is_verifyEmail</th> -->

                                
                            </tr>
                        </thead>
           
                        <tbody>
                                @foreach($users as $value)
                                <tr>
                                    <td>{{ @$value['name']}} {{ @$value['lname']}}</td>
                                    <!-- <td></td> -->
                                    <td>{{ @$value['mobile']}}</td>
                                    <td>{{ $value['email'] ?   $value['email']  : '--'}}</td>

                                    <td>{{ $value['gender'] ? $value['gender'] : '--'}}</td>
                                    <!-- <td>{{ @$value['bio']}}</td> -->
                                    <!-- <td>{{ @$value['dob']}}</td> -->
                                    <td> <?php if($value['image']!=='')
                                                    {
                                                        ?>
                                                     <img src="{{ asset(str_replace('public/', '', $value['image'])) }}" class="w-100" alt="No IMG" onerror="this.onerror=null;this.src='{{ asset('assets/admin/img/default-user.png') }}';">
                                                   <?php
                                                    }else
                                                    {?>
                                                     <img width="40" src="{{ asset('assets/admin/img/default-user.png') }}" class="w-100" alt="No IMG">

                                                     <?php
                                                    } ?></td>
                                    <!-- <td><button type="button" class="btn <?php echo $value['status']==0 ? 'btn-primary' : 'btn-secondary';   ?>" onclick="user_status(<?php echo $value['id']; ?>,<?php echo $value['status']; ?>)" ><?php echo $value['status']==0 ? 'Active' : 'Block';   ?></button></td> -->
                                    <!-- <td><button class="btn btn-primary"><?php echo $value['is_verifyId']==1 ? 'Verifyed' : 'Verify' ?></button></td> -->
                                        <!-- <td><button class="btn btn-primary"><?php echo $value['is_verifyNumber']==1 ? 'Verifyed' : 'Verify' ?></button></td> -->
                                        <!-- <td><button class="btn btn-primary"><?php echo $value['is_verifyEmail']==1 ? 'Verifyed' : 'Verify' ?></button></td> -->
                                        <td><a href="{{route('user_info',$value['id'])}}"> <i class="fa fa-eye"> </i></a></td>
                                       <td><a class="btn btn-primary" href="{{route('chat',$value['id'])}}"> <i class="fa fa-commenting"> </i></a></td>

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
  
    <script type="text/javascript">
    $(document).ready(function () {
        $('#table').dataTable();
    });
</script>
<script>
     $.ajaxSetup({
     headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
    });   

    function user_status(id,val)
    {
        $.ajax({

            url:"{{ route('admin.user_status')}}",
            type:"post",
            data:{'id':id,'status':val},
            success:function(res)
            {     
                  if(res==1)
                  {
                  toastr.success('User status update successfully');
                  }
                  else if(res==0)
                  {
                    toastr.error('something else wrong please try again ..'); 
                  }
                  else
                  {
                     toastr.error('something else wrong please try again ..');  
                  }

            },
            error:function(res)
            {
                console.log(res);
            }
        })
      

    }
</script>

    


 
@endpush
