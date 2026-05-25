@extends('admin.layouts.master')

@section('page_title')
    Rides
@endsection

@push('css')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<style>
/* Override Bootstrap conflicts */
.page-wrapper { background: #f8fafc; }
.card { border: none !important; box-shadow: none !important; }
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
    <div class="space-y-1">
        <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            <span>RIDES</span>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-yellow-600 font-black">ALL RIDES</span>
        </div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">Rides</h2>
    </div>
    <!-- Filters -->
    <div class="flex flex-wrap items-center gap-2 px-1 mb-4">
        <button type="button" class="filter-status px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-200 border" data-status="">All</button>
        <button type="button" class="filter-status px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-200 border" data-status="Requested">Requested</button>
        <button type="button" class="filter-status px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-200 border" data-status="Approved">Approved</button>
        <button type="button" class="filter-status px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-200 border" data-status="Decline">Declined</button>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden flex flex-col min-h-[600px]">
        
        <!-- Table Controls -->
        <div class="p-8 flex items-center justify-between">
            <div class="flex items-center space-x-4 text-sm font-bold text-slate-600">
                <span>Show</span>
                <div class="relative">
                    <select id="customLength" class="appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 pr-10 outline-none focus:ring-2 focus:ring-yellow-400 text-slate-800 min-w-[70px]">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 w-4 h-4"></i>
                </div>
                <span>entries</span>
            </div>

            <div class="relative group w-80">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-yellow-500 transition-colors w-4 h-4"></i>
                <input type="text" id="customSearch" placeholder="Quick search..." class="w-full bg-slate-50 border border-slate-200 rounded-full py-3 pl-12 pr-4 text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-yellow-400 outline-none transition-all text-sm font-medium" />
            </div>
        </div>

        <!-- Table Content -->
        <div class="flex-1 overflow-x-auto">
            <table class="w-full border-collapse" id="ridesTable">
                <thead>
                    <tr class="text-left border-y border-slate-50 bg-slate-50/20">
                        <th class="pl-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">ID</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Rider Name</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap text-center">Seats Available</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Pickup</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Drop Location</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Booked</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Ride Status</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Approval</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Created At</th>
                        <th class="pr-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($rides as $value)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="pl-8 py-6 text-sm font-bold text-slate-400">{{ $value['rideid'] }}</td>
                        <td class="px-4 py-6">
                            <span class="font-black text-slate-900 text-sm whitespace-nowrap">{{ $value['name'] }} {{ $value['lname'] }}</span>
                        </td>
                        <td class="px-4 py-6 text-sm font-bold text-slate-900 text-center">{{ $value['passenger_count'] }}</td>
                        <td class="px-4 py-6 text-sm font-bold text-slate-500 whitespace-nowrap max-w-[150px] overflow-hidden text-ellipsis">{{ Str::limit($value['pick_location'],50) }}</td>
                        <td class="px-4 py-6 text-sm font-bold text-slate-500 whitespace-nowrap max-w-[150px] overflow-hidden text-ellipsis">{{ Str::limit($value['drop_location'],50) }}</td>
                        <td class="px-4 py-6 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <i data-lucide="circle" class="w-2 h-2 fill-indigo-500 text-indigo-500"></i>
                                <span class="text-sm font-black text-slate-900">{{ $value['total_passenger'] }}/{{ $value['passenger_count'] }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-6 whitespace-nowrap">
                            @if($value['complete_status'] == 0)
                                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest border border-blue-100">
                                    <i data-lucide="refresh-cw" class="w-2.5 h-2.5 animate-spin"></i>
                                    <span>IN PROGRESS</span>
                                </span>
                            @else
                                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-lg bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest border border-green-100">
                                    <i data-lucide="check-circle-2" class="w-2.5 h-2.5"></i>
                                    <span>COMPLETED</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-6 whitespace-nowrap">
                            <div class="relative inline-flex items-center group/select">
                                @php 
                                    $statusList = [0 => 'Requested', 1 => 'Approved', 2 => 'Decline']; 
                                @endphp
                                <select name="admin_status" 
                                        class="status-select appearance-none px-4 py-2 pr-10 rounded-xl text-[10px] font-black uppercase tracking-widest outline-none transition-all cursor-pointer border ring-offset-2 focus:ring-2
                                        {{ $value['admin_status'] == 1 ? 'bg-slate-50 text-slate-900 border-slate-200 focus:ring-green-400' : 
                                           ($value['admin_status'] == 0 ? 'bg-yellow-50 text-yellow-700 border-yellow-100 focus:ring-yellow-400' : 'bg-red-50 text-red-600 border-red-100 focus:ring-red-400') }}"
                                        data-id="{{ $value['id'] }}" 
                                        data-pre="{{ $value['admin_status'] }}">
                                    <option value="" disabled>Select</option>
                                    @foreach($statusList as $key => $val)
                                        <option value="{{ $key }}" @if($key == $value['admin_status']) selected @endif>
                                            {{ $val }}
                                        </option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none opacity-50 group-hover/select:opacity-100 transition-opacity w-3.5 h-3.5"></i>
                            </div>
                        </td>
                        <td class="px-4 py-6 text-sm font-bold text-slate-400 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span>{{ date('M d', strtotime($value['date'])) }}</span>
                                <span class="text-[10px] text-slate-500">{{ date('h:i A', strtotime($value['date'])) }}</span>
                            </div>
                        </td>
                        <td class="pr-8 py-6 text-right">
                            <a href="{{ route('single_ride', ['id' => $value['id']]) }}" class="text-slate-400 hover:text-yellow-600 transition-colors inline-block">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Empty State (hidden by default, JS controls visibility) -->
            <div id="ridesEmptyState" class="p-20 text-center space-y-4 hidden">
                <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-slate-300 mx-auto">
                    <i data-lucide="search" class="w-10 h-10"></i>
                </div>
                <p class="font-black text-slate-400 uppercase tracking-widest text-sm">No matching rides found</p>
            </div>
        </div>
        <!-- Pagination Matches Global Style -->
        <div class="p-8 flex items-center justify-between border-t border-slate-100">
           <div class="text-xs font-black text-slate-400 uppercase tracking-widest" id="customInfo">
             SHOWING 0 TO 0 OF 0 RESULTS
           </div>
           
           <div class="flex items-center space-x-3" id="customPagination">
              <!-- Rendered via JS -->
           </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    lucide.createIcons();

    $(document).ready(function() {
        var table = $('#ridesTable').DataTable({
            ordering: true,
            order: [],
            paging: true,
            pageLength: 10,
            lengthChange: false,
            searching: true,
            info: false
        });

        // 2. Custom Filter Logic for DataTables (looks inside the select dropdown)
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var targetStatus = $('.filter-status.active').data('status');
                if (!targetStatus || targetStatus === "") return true;

                var row = table.row(dataIndex).node();
                var selectedText = $(row).find('td:eq(7) select option:selected').text().trim();
                return selectedText === targetStatus;
            }
        );

        function updateButtonStyles() {
            $('.filter-status').each(function() {
                var $btn = $(this);
                var status = $btn.data('status');
                var isActive = $btn.hasClass('active');

                if (isActive) {
                    if (status === "") {
                        $btn.removeClass('text-slate-400 hover:text-slate-600 bg-transparent border-slate-200')
                            .addClass('bg-indigo-50 text-indigo-600 border-indigo-200');
                    } else if (status === "Requested") {
                        $btn.removeClass('text-slate-400 hover:text-slate-600 bg-transparent border-slate-200')
                            .addClass('bg-yellow-50 text-yellow-700 border-yellow-200');
                    } else if (status === "Approved") {
                        $btn.removeClass('text-slate-400 hover:text-slate-600 bg-transparent border-slate-200')
                            .addClass('bg-green-50 text-green-600 border-green-200');
                    } else if (status === "Decline") {
                        $btn.removeClass('text-slate-400 hover:text-slate-600 bg-transparent border-slate-200')
                            .addClass('bg-red-50 text-red-600 border-red-200');
                    }
                } else {
                    $btn.removeClass('bg-indigo-50 text-indigo-600 border-indigo-200 bg-yellow-50 text-yellow-700 border-yellow-200 bg-green-50 text-green-600 border-green-200 bg-red-50 text-red-600 border-red-200')
                        .addClass('text-slate-400 hover:text-slate-600 bg-transparent border-slate-200');
                }
            });
        }

        // Set "Requested" as default active status and draw
        $('.filter-status[data-status="Requested"]').addClass('active');
        updateButtonStyles();
        table.draw();

        // Tab Filter Click Handler
        $('.filter-status').on('click', function() {
            $('.filter-status').removeClass('active');
            $(this).addClass('active');
            updateButtonStyles();
            table.draw();
        });

        // Custom Search
        $('#customSearch').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Custom Length
        $('#customLength').on('change', function() {
            table.page.len($(this).val()).draw();
        });

        // Custom Pagination & Info logic
        table.on('draw', function() {
            var info = table.page.info();

            // Show/hide empty state
            if (info.recordsDisplay === 0) {
                $('#ridesEmptyState').removeClass('hidden');
            } else {
                $('#ridesEmptyState').addClass('hidden');
            }

            var showingFrom = info.recordsDisplay > 0 ? info.start + 1 : 0;
            $('#customInfo').html(
                'SHOWING ' + showingFrom + ' TO ' + info.end + ' OF ' + info.recordsDisplay + ' RESULTS'
            );

            var paginationHtml = '';
            var totalPages = info.pages;
            var currentPage = info.page + 1; // 1-indexed

            // Prev Button
            if (totalPages > 1) {
                var prevDisabled = currentPage === 1 ? 'opacity-50 cursor-not-allowed pointer-events-none' : 'hover:bg-slate-200 cursor-pointer';
                paginationHtml += '<button type="button" class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 transition-all shadow-sm font-bold paginate-btn ' + prevDisabled + '" data-action="prev"><i data-lucide="chevron-left" class="w-5 h-5"></i></button>';

                paginationHtml += '<div class="flex items-center space-x-2">';
                // Show pages: first, around current, last
                var pagesToShow = [];
                if (totalPages <= 5) {
                    for (var p = 1; p <= totalPages; p++) pagesToShow.push(p);
                } else {
                    pagesToShow = [1, 2, 3];
                    if (totalPages > 3) pagesToShow.push('...');
                    pagesToShow.push(totalPages);
                }

                for (var idx = 0; idx < pagesToShow.length; idx++) {
                    var pg = pagesToShow[idx];
                    if (pg === '...') {
                        paginationHtml += '<span class="text-slate-300 font-bold px-1">...</span>';
                    } else {
                        var btnClass = currentPage === pg
                            ? 'bg-yellow-400 text-slate-900 shadow-md shadow-yellow-200 ring-2 ring-yellow-400 ring-offset-2'
                            : 'text-slate-500 hover:bg-slate-50';
                        paginationHtml += '<button type="button" class="h-10 w-10 rounded-full text-sm font-black transition-all paginate-btn ' + btnClass + '" data-page="' + (pg - 1) + '">' + pg + '</button>';
                    }
                }
                paginationHtml += '</div>';

                // Next Button
                var nextDisabled = currentPage === totalPages ? 'opacity-50 cursor-not-allowed pointer-events-none' : 'hover:bg-slate-200 cursor-pointer';
                paginationHtml += '<button type="button" class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 transition-all shadow-sm font-bold paginate-btn ' + nextDisabled + '" data-action="next"><i data-lucide="chevron-right" class="w-5 h-5"></i></button>';
            }

            $('#customPagination').html(paginationHtml);
            lucide.createIcons(); // re-init icons after DOM update
        });

        // Trigger initial draw to set up pagination
        table.draw();

        // Handle pagination clicks
        $('#customPagination').on('click', '.paginate-btn', function() {
            if ($(this).attr('disabled')) return;
            var action = $(this).data('action');
            var page = $(this).data('page');
            
            if (action === 'prev') {
                table.page('previous').draw('page');
            } else if (action === 'next') {
                table.page('next').draw('page');
            } else if (page !== undefined) {
                table.page(page).draw('page');
            }
        });

        // Change Status Logic
        $(document).on('change', '.status-select', function() {
            var selectElement = $(this);
            var rideId = selectElement.data('id');
            var selectedValue = selectElement.val();
            var selectedText = selectElement.find('option:selected').text().trim();
            var previousValue = selectElement.data('pre');

            if (selectedValue === "") return;

            Swal.fire({
                title: 'Are you sure?',
                text: "Change status to " + selectedText + "?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                   $.ajax({
                        url: "/update-ride-admin-status", 
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: rideId,
                            status: selectedValue
                        },
                       success: function(response) {
                            if (response.success === true) {
                                Swal.fire({
                                    title: 'Updated!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                selectElement.data('pre', selectedValue);
                                
                                // Update Tailwind classes dynamically
                                selectElement.removeClass('bg-yellow-50 text-yellow-700 border-yellow-100 focus:ring-yellow-400 bg-green-50 text-slate-900 border-slate-200 focus:ring-green-400 bg-red-50 text-red-600 border-red-100 focus:ring-red-400 bg-slate-50');
                                
                                if(selectedValue == 1) { // Approve
                                    selectElement.addClass('bg-slate-50 text-slate-900 border-slate-200 focus:ring-green-400');
                                } else if(selectedValue == 0) { // Requested
                                    selectElement.addClass('bg-yellow-50 text-yellow-700 border-yellow-100 focus:ring-yellow-400');
                                } else { // Disapprove
                                    selectElement.addClass('bg-red-50 text-red-600 border-red-100 focus:ring-red-400');
                                }

                                table.draw(); 
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                                selectElement.val(previousValue);
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Server could not process the request.', 'error');
                            selectElement.val(previousValue);
                        }
                    });
                } else {
                    selectElement.val(previousValue);
                }
            });
        });
    });
</script>
@endpush