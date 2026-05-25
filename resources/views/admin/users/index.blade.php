@extends('admin.layouts.master')

@section('page_title')
    All Customers
@endsection

@push('css')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<style>
/* Override Bootstrap conflicts */
.page-wrapper { background: #f8fafc; }
.card { border: none !important; box-shadow: none !important; }
#toast-container .toast-success {background: green !important;}
#toast-container .toast-error {background: red !important;}
/* Hide datatable elements if they conflict visually */
.dataTables_wrapper .dataTables_length, 
.dataTables_wrapper .dataTables_filter, 
.dataTables_wrapper .dataTables_info, 
.dataTables_wrapper .dataTables_paginate {
    display: none !important;
}
table.dataTable.no-footer { border-bottom: none !important; }
table.dataTable thead th, table.dataTable thead td { border-bottom: none !important; }
table.dataTable tbody tr { background-color: transparent !important; }
</style>
@endpush

@section('content')
<div class="space-y-6 pb-12 px-6 pt-6 animate-in fade-in duration-700">
    <!-- Breadcrumb and Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <span>CUSTOMERS</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-yellow-600 font-black">ALL CUSTOMERS</span>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">All Customers</h2>
        </div>
        <div>
            <button type="button" class="bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-black py-3 px-6 rounded-2xl shadow-lg shadow-yellow-100 transition-all active:scale-[0.98]" data-toggle="modal" data-target="#adduser">
                Add User
            </button>
        </div>
    </div>

    <!-- Error Toast Trigger -->
    <div class="row hidden">
        @if ($errors->any())
            <script>
                @foreach ($errors->all() as $error)
                    toastr.error(@json($error));
                @endforeach
            </script>
        @endif
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden flex flex-col min-h-[600px]">
        <!-- Table Content -->
        <div class="flex-1 overflow-x-auto">
            <table class="w-full" id="table">
                <thead>
                    <tr class="text-left border-y border-slate-50">
                        <th class="pl-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest w-20 whitespace-nowrap">Sr No</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Profile</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Mobile Number</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Email Address</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Dob</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Tag</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Gender</th>
                        <th class="pr-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($users as $value)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="pl-8 py-6 text-sm font-bold text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-6">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 md:h-12 md:w-12 rounded-full overflow-hidden border-2 border-white shadow-sm bg-slate-100 shrink-0">
                                    <?php $imagePath = str_replace('public/', '', $value['image']); ?>
                                    @if(!empty($imagePath))
                                        <img src="{{ asset($imagePath) }}" alt="User Image" class="h-full w-full object-cover" onerror="this.src='{{ asset('assets/admin/img/default-user.png') }}';">
                                    @else
                                        <img src="{{ asset('assets/admin/img/default-user.png') }}" alt="Default" class="h-full w-full object-cover">
                                    @endif
                                </div>
                                <span class="font-black text-slate-900 text-xs md:text-sm whitespace-nowrap">{{ @$value['name']}} {{ @$value['lname']}}</span>
                            </div>
                        </td>
                        <td class="px-4 py-6 text-xs md:text-sm font-bold text-slate-500 whitespace-nowrap">{{ @$value['mobile']}}</td>
                        <td class="px-4 py-6 text-xs md:text-sm font-bold text-slate-500 whitespace-nowrap">{{ $value['email'] ? $value['email'] : '--'}}</td>
                        <td class="px-4 py-6 text-xs md:text-sm font-bold text-slate-500 whitespace-nowrap">{{ @$value['dob'] ?? '14 May 1992' }}</td>
                        <td class="px-4 py-6 whitespace-nowrap">
                            @php
                                $tag = @$value['tag'] ?? 'PASSENGER';
                            @endphp
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black tracking-widest uppercase {{ $tag === 'DRIVER' ? 'bg-indigo-100 text-indigo-600' : ($tag === 'PASSENGER' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600') }}">
                                {{ $tag }}
                            </span>
                        </td>
                        <td class="px-4 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">{{ @$value['gender'] ?: 'FEMALE' }}</td>
                        <td class="pr-8 py-6 text-right">
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{route('user_info',$value['id'])}}" class="text-yellow-600 hover:text-yellow-700 transition-colors">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </a>
                                <a href="{{route('chat',$value['id'])}}" class="text-slate-400 hover:text-blue-600 transition-colors" title="Support Chat">
                                    <i data-lucide="message-square" class="w-5 h-5"></i>
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

<!-- Add User Modal (Preserved Backend functionality) -->
<div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-[32px] border-0 shadow-2xl">
      <div class="modal-header border-b border-slate-100 px-8 py-6">
        <h5 class="modal-title font-black text-xl text-slate-800" id="exampleModalLabel">Create New User</h5>
        <button type="button" class="close text-slate-400 hover:text-slate-600" data-dismiss="modal" aria-label="Close">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
      </div>
      <div class="modal-body p-8">
         <form action="{{ route('users.store') }}" method="post" id="createUserForm" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Name</label>
                    <input type="text" name="name" id="name" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-400 text-sm font-bold text-slate-800 transition-all" placeholder="Enter Name" required>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Last Name</label>
                    <input type="text" name="lname" id="lname" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-400 text-sm font-bold text-slate-800 transition-all" placeholder="Enter Last Name">
                </div>
            </div>

            <div>
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Contact Number</label>
                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-yellow-400 transition-all">
                     <span class="px-4 py-3 text-sm font-bold text-slate-500 bg-slate-100 border-r border-slate-200">+91</span>
                    <input type="text" name="mobile" id="mobile" class="w-full bg-transparent px-4 py-3 outline-none text-sm font-bold text-slate-800" placeholder="e.g 0000000000" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" required>
                </div>
             </div>
             
             <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-black py-3 px-8 rounded-2xl shadow-lg shadow-yellow-100 transition-all active:scale-[0.98]">
                    Create User
                </button>
             </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    lucide.createIcons();

    $(document).ready(function () {
        // Init datatables but we hid its elements in CSS. We keep it if backend relies on it for something.
        // Or we can just let it run.
        if ($.fn.DataTable) {
            $('#table').dataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
            });
        }

        $('#mobile').on('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });

        $('#createUserForm').on('submit', function (e) {
            var mobile = $('#mobile').val();
            if (!/^\d{10}$/.test(mobile)) {
                e.preventDefault();
                toastr.error('Contact number must be exactly 10 digits.');
            }
        });
    });

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });   

    function user_status(id,val) {
        $.ajax({
            url:"{{ route('admin.user_status')}}",
            type:"post",
            data:{'id':id,'status':val},
            success:function(res) {     
                if(res==1) toastr.success('User status update successfully');
                else toastr.error('something else wrong please try again ..'); 
            },
            error:function(res) { console.log(res); }
        })
    }
</script>
@endpush
