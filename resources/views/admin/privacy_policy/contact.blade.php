@extends('admin.layouts.master')
@section('page_title')
Contact
@endsection
@push('css')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<style>
.page-wrapper { background: #f8fafc; }
.card { border: none !important; box-shadow: none !important; }
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate { display: none !important; }
.dataTables_wrapper .dataTables_filter { display: none !important; }
table.dataTable.no-footer { border-bottom: none !important; }
table.dataTable thead th, table.dataTable thead td { border-bottom: none !important; }
table.dataTable tbody tr { background-color: transparent !important; }
</style>
@endpush

@section('content')
<div class="space-y-6 pb-20 px-6 pt-6 min-h-screen">

    @if (Session::has('success'))
        <div class="fixed top-24 right-8 z-[200] px-6 py-4 rounded-2xl shadow-2xl flex items-center space-x-3 border bg-slate-900 border-green-500/20 text-white">
            <div class="p-2 rounded-xl bg-green-500/10">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400"></i>
            </div>
            <span class="text-sm font-black tracking-wide">{{ Session::get('success') }}</span>
        </div>
    @endif

    <!-- Header -->
    <div class="space-y-1">
        <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            <span>CMS</span>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-yellow-600 font-black uppercase">Contact</span>
        </div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">Contact</h2>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden flex flex-col min-h-[600px]">

        <!-- Table Controls -->
        <div class="p-8 flex items-center justify-between">
            <div class="flex items-center space-x-4 text-sm font-bold text-slate-600">
                <span>Show</span>
                <div class="relative">
                    <select id="perPageSelect" class="appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 pr-10 outline-none focus:ring-2 focus:ring-yellow-400 text-slate-800 min-w-[70px]">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400"></i>
                </div>
                <span>entries</span>
            </div>

            <div class="relative group w-80">
                <i data-lucide="search" class="w-[18px] h-[18px] absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-yellow-500 transition-colors"></i>
                <input type="text" id="searchInput" placeholder="Quick search..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-full py-3 pl-12 pr-4 text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-yellow-400 outline-none transition-all text-sm font-medium">
            </div>
        </div>

        <!-- Table Content -->
        <div class="flex-1 overflow-x-auto">
            <table class="w-full border-collapse" id="table">
                <thead class="bg-[#e2ecf5]/50">
                    <tr class="text-left">
                        <th class="pl-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap w-20">Sr No.</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Name</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Email</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Subject</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Message</th>
                        <th class="pr-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php $i = 1; ?>
                    @foreach($contact as $value)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="pl-8 py-6 text-sm font-black text-slate-400">{{ sprintf('%02d', $i) }}</td>
                        <td class="px-4 py-6">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 md:h-12 md:w-12 rounded-full overflow-hidden border-2 border-white shadow-sm bg-slate-100 shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(@$value->name) }}&background=f1f5f9&color=64748b&rounded=true&bold=true" 
                                         alt="{{ @$value->name }}"
                                         class="h-full w-full object-cover">
                                </div>
                                <span class="font-black text-slate-700 text-xs md:text-sm whitespace-nowrap">{{ @$value->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-6 text-xs md:text-sm font-bold text-slate-500 whitespace-nowrap">
                            {{ @$value->email }}
                        </td>
                        <td class="px-4 py-6 text-xs md:text-sm font-bold text-slate-500 whitespace-nowrap">
                            {{ @$value->subject }}
                        </td>
                        <td class="px-4 py-6">
                            <p class="text-xs md:text-sm font-medium text-slate-500 line-clamp-1 max-w-lg">
                                "{!! strip_tags(@$value->message) !!}"
                            </p>
                        </td>
                        <td class="pr-8 py-6 text-right">
                            <a href="{{ route('delete_contact', $value->id) }}"
                               onclick="return confirm('Are you sure you want to delete this contact inquiry?')"
                               class="text-slate-400 hover:text-red-500 transition-colors inline-block">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </a>
                        </td>
                    </tr>
                    <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
            @if(count($contact) === 0)
            <div class="p-20 text-center space-y-4">
                <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-slate-300">
                    <i data-lucide="mail" class="w-10 h-10"></i>
                </div>
                <p class="font-black text-slate-400 uppercase tracking-widest">No messages found</p>
            </div>
            @endif
        </div>

        <!-- Pagination footer -->
        <div class="p-8 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between">
            <span id="paginationInfo" class="text-[10px] font-black text-slate-400 uppercase tracking-widest"></span>
            <div class="flex items-center space-x-3" id="paginationButtons"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    lucide.createIcons();

    $(function () {
        var table = $('#table').DataTable({
            ordering: true,
            order: [],
            paging: true,
            pageLength: 10,
            lengthChange: false,
            searching: true,
            info: true,
            dom: 'tip'
        });

        // Wire custom search
        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });

        // Wire custom per-page
        $('#perPageSelect').on('change', function () {
            table.page.len(parseInt(this.value)).draw();
        });

        // Update info text after each draw
        table.on('draw', function () {
            var info = table.page.info();
            $('#paginationInfo').text(
                'Showing ' + (info.start + 1) + ' to ' + info.end + ' of ' + info.recordsDisplay + ' entries'
            );
            buildPagination(table, info);
        }).draw();

        function buildPagination(table, info) {
            var container = $('#paginationButtons');
            container.empty();
            var totalPages = info.pages;
            var currentPage = info.page;

            // Prev
            var prevBtn = $('<button class="h-10 w-10 flex items-center justify-center rounded-full bg-white text-slate-400 border border-slate-200 hover:bg-slate-50 transition-all shadow-sm"></button>');
            prevBtn.html('<i data-lucide="chevron-left" class="w-4 h-4"></i>');
            if (currentPage === 0) prevBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
            else prevBtn.on('click', function () { table.page('previous').draw('page'); });
            container.append(prevBtn);

            // Inner wrapper for numbers
            var numbersWrapper = $('<div class="flex items-center space-x-2"></div>');
            
            // Page buttons (show up to 3)
            var startPage = Math.max(0, currentPage - 1);
            var endPage = Math.min(totalPages - 1, startPage + 2);
            if (endPage - startPage < 2) {
                startPage = Math.max(0, endPage - 2);
            }
            
            for (var i = startPage; i <= endPage; i++) {
                var pageBtn = $('<button class="h-10 w-10 rounded-full text-xs font-black transition-all"></button>').text(i + 1);
                if (i === currentPage) pageBtn.addClass('bg-yellow-400 text-slate-900 shadow-md shadow-yellow-200/50');
                else pageBtn.addClass('text-slate-500 hover:bg-white border border-transparent hover:border-slate-200');
                (function(p) { pageBtn.on('click', function () { table.page(p).draw('page'); }); })(i);
                numbersWrapper.append(pageBtn);
            }
            container.append(numbersWrapper);

            // Next
            var nextBtn = $('<button class="h-10 w-10 flex items-center justify-center rounded-full bg-white text-slate-400 border border-slate-200 hover:bg-slate-50 transition-all shadow-sm"></button>');
            nextBtn.html('<i data-lucide="chevron-right" class="w-4 h-4"></i>');
            if (currentPage >= totalPages - 1) nextBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
            else nextBtn.on('click', function () { table.page('next').draw('page'); });
            container.append(nextBtn);
            
            lucide.createIcons();
        }
    });
</script>
@endpush