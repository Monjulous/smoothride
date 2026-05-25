@extends('admin.layouts.master')

@section('page_title')
   Rating & Reviews
@endsection

@push('css')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<style>
/* Override Bootstrap conflicts */
.page-wrapper { background: #f8fafc; }
.card { border: none !important; box-shadow: none !important; }
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
    <div class="space-y-1">
        <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            <span>ENGAGEMENT</span>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-yellow-600 font-black">RATINGS</span>
        </div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">Rating & Reviews</h2>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden flex flex-col min-h-[600px]">
        <!-- Table Content -->
        <div class="flex-1 overflow-x-auto">
            <table class="w-full border-collapse" id="table">
                <thead>
                    <tr class="text-left border-b border-slate-50 bg-slate-50/20">
                        <th class="pl-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Sr. No</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Receiver Name</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Rating</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Posted At</th>
                        <th class="pr-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap text-right">Reviews</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($rating as $key => $value)
                    <tr class="group hover:bg-slate-50/50 transition-colors"> 
                        <td class="pl-8 py-6 text-sm font-bold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-6">
                            <div class="flex items-center space-x-3">
                                <div class="h-8 w-8 rounded-full overflow-hidden border-2 border-white shadow-sm bg-slate-100 shrink-0">
                                    <?php $imagePath = str_replace('public/', '', @$value['image'] ?? ''); ?>
                                    @if(!empty($imagePath))
                                        <img src="{{ asset($imagePath) }}" alt="User Image" class="h-full w-full object-cover" onerror="this.onerror=null;this.src='{{ asset('assets/admin/img/default-user.png') }}';">
                                    @else
                                        <img src="{{ asset('assets/admin/img/default-user.png') }}" alt="Default User" class="h-full w-full object-cover">
                                    @endif
                                </div>
                                <span class="font-black text-slate-900 text-sm whitespace-nowrap">{{ @$value['name'] }} {{ @$value['lname'] }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-6">
                            <div class="flex items-center space-x-1 text-yellow-400">
                                @php
                                    $ratingValue = floatval(@$value['rating'] ?? 0);
                                @endphp
                                @for($star = 0; $star < 5; $star++)
                                    <i data-lucide="star" class="w-4 h-4 {{ $star < $ratingValue ? 'fill-current' : 'text-slate-200' }}"></i>
                                @endfor
                                <span class="text-[12px] font-black text-slate-400 ml-2">{{ number_format($ratingValue, 1) }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-6 text-sm font-bold text-slate-500 whitespace-nowrap">
                            {{ date('d, M y', strtotime($value['created_at'])) }}
                        </td>
                        <td class="pr-8 py-6 text-right">
                            @if($value['reviews'])   
                            <button type="button" class="text-yellow-600 hover:text-yellow-700 transition-colors inline-flex items-center justify-end space-x-2 w-full" data-toggle="modal" data-target="#exampleModal{{$key}}">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </button>

                            <!-- Modal inside loop (Preserved logic) -->
                            <div class="modal fade text-left" id="exampleModal{{$key}}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-[32px] border-0 shadow-2xl">
                                        <div class="modal-header border-b border-slate-100 px-8 py-6 flex justify-between items-center">
                                            <h5 class="modal-title font-black text-xl text-slate-800">Review</h5>
                                            <button type="button" class="close text-slate-400 hover:text-slate-600" data-dismiss="modal" aria-label="Close">
                                                <i data-lucide="x" class="w-6 h-6"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body p-8">
                                            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                                                <div class="flex items-center space-x-1 text-yellow-400 mb-4">
                                                    @for($star = 0; $star < 5; $star++)
                                                        <i data-lucide="star" class="w-4 h-4 {{ $star < $ratingValue ? 'fill-current' : 'text-slate-200' }}"></i>
                                                    @endfor
                                                </div>
                                                <p class="text-slate-600 font-medium leading-relaxed italic">"{{ @$value['reviews'] }}"</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                                <span class="text-slate-300 font-bold">--</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    lucide.createIcons();
    $(function() {
        $('#table').DataTable({
            ordering: true,
            order: [],
            paging: true,
            lengthChange: false,
            searching: true,
            info: false
        });
    });
</script>
@endpush