@extends('admin.layouts.master')
@section('page_title')
FAQ
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

/* Modal Custom Styles */
.modal-content {
    border-radius: 40px !important;
    border: 1px solid #f1f5f9;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
}
.modal-header {
    border-bottom: 1px solid #f8fafc;
    background: white;
    padding: 2rem;
}
.modal-body {
    padding: 2.5rem;
}
.modal-footer {
    border-top: 1px solid #f1f5f9;
    background: #f8fafc;
    padding: 2rem;
}
</style>
@endpush

@section('content')
<div class="space-y-8 animate-in fade-in duration-500 min-h-screen pb-20 px-6 pt-6">
    
    @if (Session::has('success'))
        <div class="fixed top-24 right-8 z-[200] px-6 py-4 rounded-2xl shadow-2xl flex items-center space-x-3 border bg-slate-900 border-green-500/20 text-white transition-all">
            <div class="p-2 rounded-xl bg-green-500/10">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400"></i>
            </div>
            <span class="text-sm font-black tracking-wide">{{ Session::get('success') }}</span>
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <span>FAQ</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-yellow-600 font-black uppercase">FAQ</span>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">FAQ</h2>
        </div>

        <button type="button" data-toggle="modal" data-target="#addFaqModal" class="bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-black py-4 px-10 rounded-2xl shadow-lg shadow-yellow-100 transition-all uppercase tracking-wider flex items-center space-x-3 group active:scale-[0.98]">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span>Add New FAQ</span>
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-100 rounded-[40px] shadow-sm overflow-hidden">
        
        <!-- Controls Row -->
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <span class="text-xs font-bold text-slate-500">Show</span>
                <select id="perPageSelect" class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs font-bold text-slate-900 outline-none focus:ring-2 focus:ring-yellow-400 transition-all">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span class="text-xs font-bold text-slate-500">entries</span>
            </div>

            <div class="relative max-w-md w-full">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400 pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input 
                    type="text"
                    id="searchInput"
                    placeholder="Quick search..."
                    class="w-full bg-[#ecf2f7] border-none rounded-full py-3.5 pl-14 pr-6 text-xs font-bold text-slate-900 placeholder:text-slate-400 outline-none focus:ring-4 focus:ring-yellow-400/20 transition-all"
                />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="table">
                <thead>
                    <tr class="bg-[#f1f6fa]">
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">SR NO.</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">QUESTION</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">ANSWER</th>
                        <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($faq as $key=>$value)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-6 text-sm font-bold text-slate-900">{{ $loop->iteration }}</td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-black text-slate-900 leading-tight block">{{@$value['questions']}}</span>
                            <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest">GENERAL</span>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-xs font-bold text-slate-500 leading-relaxed max-w-md line-clamp-2 faqanswers">
                                {{@$value['answers']}}
                            </p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center space-x-4">
                                <button data-toggle="modal" data-target="#edit{{ $key }}" class="p-1 text-slate-400 hover:text-slate-900 transition-colors">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <button data-toggle="modal" data-target="#exampleModal" onclick="delete_Faq(`<?php echo $value['id']; ?>`)" class="p-1 text-red-400 hover:text-red-600 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="edit{{ $key }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="h-10 w-10 bg-yellow-400 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-100">
                                            <i data-lucide="help-circle" class="w-5 h-5 text-slate-900"></i>
                                        </div>
                                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Edit FAQ</h3>
                                    </div>
                                    <button type="button" class="close h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all shadow-inner" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body space-y-6">
                                    <form action="{{ route('editfaq') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{@$value['id']}}">
                                        
                                        <div class="space-y-2 mb-6">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Question</label>
                                            <input type="text" name="questions" value="{{@$value['questions']}}" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-6 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-yellow-400/10 transition-all hover:bg-white" required>
                                        </div>

                                        <div class="space-y-2 mb-6">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Answer</label>
                                            <div class="relative">
                                                <i data-lucide="message-square" class="w-4 h-4 absolute left-6 top-6 text-slate-300"></i>
                                                <textarea name="answers" rows="5" class="w-full bg-slate-50 border border-slate-100 rounded-[32px] py-6 pl-14 pr-8 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-yellow-400/10 transition-all resize-none hover:bg-white" required>{{@$value['answers']}}</textarea>
                                            </div>
                                        </div>
                                
                                        <div class="flex items-center justify-end space-x-3 pt-4">
                                            <button type="button" data-dismiss="modal" class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-800 transition-colors">Cancel</button>
                                            <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center justify-center space-x-2 shadow-xl shadow-slate-200 active:scale-95">
                                                <i data-lucide="check-circle-2" class="w-4 h-4 text-yellow-400"></i>
                                                <span>Save FAQ</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach 
                </tbody>
            </table>
        </div>
        
        <!-- Pagination footer -->
        <div class="p-8 border-t border-slate-50 flex items-center justify-between text-xs font-bold text-slate-500" id="paginationBar">
            <span id="paginationInfo"></span>
            <div class="flex items-center space-x-2" id="paginationButtons"></div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addFaqModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-10 w-10 bg-yellow-400 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-100">
                        <i data-lucide="help-circle" class="w-5 h-5 text-slate-900"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Add FAQ</h3>
                </div>
                <button type="button" class="close h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all shadow-inner" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body space-y-6">
                <form action="{{route('submit_faq')}}" method="post">
                    @csrf
                    
                    <div class="space-y-2 mb-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Question</label>
                        <input type="text" name="que" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-6 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-yellow-400/10 transition-all hover:bg-white" placeholder="Enter question" required>
                    </div>

                    <div class="space-y-2 mb-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Answer</label>
                        <div class="relative">
                            <i data-lucide="message-square" class="w-4 h-4 absolute left-6 top-6 text-slate-300"></i>
                            <textarea name="ans" rows="5" class="w-full bg-slate-50 border border-slate-100 rounded-[32px] py-6 pl-14 pr-8 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-yellow-400/10 transition-all resize-none hover:bg-white" placeholder="Enter the answer..." required></textarea>
                        </div>
                    </div>
            
                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button type="button" data-dismiss="modal" class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-800 transition-colors">Cancel</button>
                        <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center justify-center space-x-2 shadow-xl shadow-slate-200 active:scale-95">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-yellow-400"></i>
                            <span>Save FAQ</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 32px !important; padding: 2rem;">
            <div class="flex flex-col items-center text-center space-y-4">
                <div class="h-16 w-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center">
                    <i data-lucide="alert-circle" class="w-8 h-8"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Delete FAQ?</h3>
                    <p class="text-xs font-bold text-slate-500">
                        Are you sure you want to delete this FAQ? This action cannot be undone.
                    </p>
                </div>
                <form action="{{route('delete_Faq')}}" method="post" class="w-full pt-4">
                    @csrf
                    <input type="hidden" name="id" class="iddelete" value="" />
                    <div class="flex items-center space-x-3 w-full">
                        <button type="button" data-dismiss="modal" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-slate-200 transition-colors">
                            Go Back
                        </button>
                        <button type="submit" class="flex-1 py-4 bg-red-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 shadow-lg shadow-red-100 transition-all active:scale-95">
                            Delete Permanently
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
    function delete_Faq(id) {
        $('.iddelete').val(id);
    }
    $(function() {
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
            var total = info.recordsDisplay;
            var start = total === 0 ? 0 : info.start + 1;
            $('#paginationInfo').text(
                'Showing ' + start + ' to ' + info.end + ' of ' + total + ' entries'
            );
            buildPagination(table, info);
            lucide.createIcons();
        }).draw();

        function buildPagination(table, info) {
            var container = $('#paginationButtons');
            container.empty();
            var totalPages = info.pages;
            var currentPage = info.page;

            // Prev
            var prevBtn = $('<button class="h-8 w-8 rounded-full flex items-center justify-center border border-slate-100 hover:bg-slate-50 text-slate-400 transition-all"></button>');
            prevBtn.html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>');
            if (currentPage === 0) prevBtn.prop('disabled', true).addClass('opacity-40 cursor-not-allowed');
            else prevBtn.on('click', function () { table.page('previous').draw('page'); });
            container.append(prevBtn);

            // Page buttons
            var startPage = Math.max(0, currentPage - 2);
            var endPage = Math.min(totalPages - 1, startPage + 4);
            for (var i = startPage; i <= endPage; i++) {
                var pageBtn = $('<button class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-black transition-all"></button>').text(i + 1);
                if (i === currentPage) pageBtn.addClass('bg-yellow-400 text-slate-900 shadow-sm');
                else pageBtn.addClass('border border-slate-100 hover:bg-slate-50 text-slate-500');
                (function(p) { pageBtn.on('click', function () { table.page(p).draw('page'); }); })(i);
                container.append(pageBtn);
            }

            // Next
            var nextBtn = $('<button class="h-8 w-8 rounded-full flex items-center justify-center border border-slate-100 hover:bg-slate-50 text-slate-400 transition-all"></button>');
            nextBtn.html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>');
            if (currentPage >= totalPages - 1) nextBtn.prop('disabled', true).addClass('opacity-40 cursor-not-allowed');
            else nextBtn.on('click', function () { table.page('next').draw('page'); });
            container.append(nextBtn);
        }
    });
</script>
@endpush