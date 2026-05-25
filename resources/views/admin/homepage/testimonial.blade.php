@extends('admin.layouts.master')

@section('page_title')
   Testimonials
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
.dataTables_wrapper .dataTables_paginate { display: none !important; }
table.dataTable.no-footer { border-bottom: none !important; }
table.dataTable thead th, table.dataTable thead td { border-bottom: none !important; }
table.dataTable tbody tr { background-color: transparent !important; }

/* Modal Styling */
.modal-content {
    border-radius: 40px !important;
    border: 1px solid #f1f5f9 !important;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important;
    overflow: hidden;
}
.modal-header {
    border-bottom: 1px solid #f8fafc !important;
    background: white !important;
    padding: 2rem !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
}
.modal-body { padding: 2.5rem !important; }
.modal-footer { border-top: 1px solid #f1f5f9 !important; background: #f8fafc !important; padding: 1.5rem 2rem !important; }
</style>
@endpush

@section('content')
<div class="space-y-8 pb-20 px-6 pt-6 min-h-screen">

    @if (Session::has('success'))
        <div class="fixed top-24 right-8 z-[200] px-6 py-4 rounded-2xl shadow-2xl flex items-center space-x-3 border bg-slate-900 border-green-500/20 text-white">
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
                <span>CMS</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-yellow-600 font-black uppercase">Testimonial</span>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">Testimonial CMS</h2>
        </div>
        <button type="button" data-toggle="modal" data-target="#addTestimonialModal"
            class="bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-black py-4 px-10 rounded-2xl shadow-lg shadow-yellow-100 transition-all uppercase tracking-wider flex items-center space-x-3 active:scale-[0.98]">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span>Add New Testimonial</span>
        </button>
    </div>

    <!-- Table Card -->
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
            <div class="relative max-w-sm w-full">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400 pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" id="searchInput" placeholder="Filter records..."
                    class="w-full bg-[#ecf2f7] border-none rounded-full py-3.5 pl-14 pr-6 text-xs font-bold text-slate-900 placeholder:text-slate-400 outline-none focus:ring-4 focus:ring-yellow-400/20 transition-all">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full" id="table">
                <thead>
                    <tr class="bg-[#f1f6fa]">
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-16">SR NO.</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">IMAGE</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">NAME</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">LOCATION</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">REVIEW</th>
                        <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if($testimonial)
                    @foreach($testimonial as $key => $val)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-6 text-sm font-bold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="px-8 py-6">
                            <div class="h-10 w-10 rounded-full overflow-hidden border border-slate-100 shadow-sm bg-slate-50 shrink-0">
                                @if($val->photo)
                                    <img src="{{ asset($val->photo) }}" alt="{{ $val->name }}" class="h-full w-full object-cover" onerror="this.src='{{ asset('assets/admin/img/default-user.png') }}';">
                                @else
                                    <div class="h-full w-full flex items-center justify-center">
                                        <img src="{{ asset('assets/admin/img/default-user.png') }}" alt="Default" class="h-full w-full object-cover">
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-black text-slate-900">{{ $val->name }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-2 text-slate-500">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-yellow-500 shrink-0"></i>
                                <span class="text-xs font-bold">{{ $val->location ?: '—' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="message-container">
                                <span class="short-text">
                                    <p class="text-xs font-bold text-slate-500 leading-relaxed max-w-sm line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit($val->message, 100) }}
                                    </p>
                                    @if(strlen($val->message) > 100)
                                        <span class="see-more text-[10px] font-black text-blue-500 cursor-pointer uppercase tracking-widest">See more</span>
                                    @endif
                                </span>
                                <span class="full-text d-none">
                                    <p class="text-xs font-bold text-slate-500 leading-relaxed max-w-sm">{{ $val->message }}</p>
                                    <span class="see-less text-[10px] font-black text-blue-500 cursor-pointer uppercase tracking-widest">See less</span>
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center space-x-4">
                                <button data-toggle="modal" data-target="#edit{{ $key }}"
                                    class="p-1 text-slate-400 hover:text-slate-900 transition-colors">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <button data-toggle="modal" data-target="#deleteModal"
                                    onclick="setDeleteId(`{{ $val->id }}`, `{{ addslashes($val->name) }}`)"
                                    class="p-1 text-red-400 hover:text-red-600 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="edit{{ $key }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="flex items-center space-x-4">
                                        <div class="h-10 w-10 bg-yellow-400 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-100">
                                            <i data-lucide="message-square" class="w-5 h-5 text-slate-900"></i>
                                        </div>
                                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Edit Testimonial</h3>
                                    </div>
                                    <button type="button" class="close h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all" data-dismiss="modal">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('testimonial.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $val->id }}">
                                        <input type="hidden" name="old_photo" value="{{ $val->photo }}">

                                        <div class="grid grid-cols-2 gap-6 mb-6">
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Name</label>
                                                <input type="text" name="name" value="{{ $val->name }}" required
                                                    class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-yellow-400/10 transition-all">
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Location</label>
                                                <input type="text" name="location" value="{{ $val->location }}"
                                                    class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-yellow-400/10 transition-all"
                                                    placeholder="e.g. New York, NY">
                                            </div>
                                        </div>

                                        <div class="space-y-2 mb-6">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Review Message</label>
                                            <textarea name="message" rows="4" required
                                                class="w-full bg-slate-50 border border-slate-100 rounded-3xl py-5 px-6 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-yellow-400/10 transition-all resize-none">{{ $val->message }}</textarea>
                                        </div>

                                        <div class="space-y-2 mb-6">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Photo</label>
                                            <div class="flex items-center space-x-4">
                                                @if($val->photo)
                                                    <div class="h-16 w-16 rounded-2xl overflow-hidden border border-slate-100 shadow-sm shrink-0">
                                                        <img src="{{ asset($val->photo) }}" class="h-full w-full object-cover" alt="{{ $val->name }}" onerror="this.src='{{ asset('assets/admin/img/default-user.png') }}';">
                                                    </div>
                                                @endif
                                                <div class="flex-1">
                                                    <label class="cursor-pointer">
                                                        <input type="file" name="photo" accept="image/*" class="hidden">
                                                        <div class="bg-slate-50 border border-dashed border-slate-200 hover:border-yellow-400 hover:bg-yellow-50/30 rounded-2xl py-4 px-6 transition-all flex items-center justify-center space-x-2">
                                                            <i data-lucide="upload" class="w-4 h-4 text-yellow-500"></i>
                                                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $val->photo ? 'Change Photo' : 'Upload Photo' }}</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-50">
                                            <button type="button" data-dismiss="modal" class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-800 transition-colors">Cancel</button>
                                            <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center space-x-2 shadow-xl shadow-slate-200 active:scale-95">
                                                <i data-lucide="check-circle-2" class="w-4 h-4 text-yellow-400"></i>
                                                <span>Save Testimonial</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="p-8 border-t border-slate-50 flex items-center justify-between text-xs font-bold text-slate-500" id="paginationBar">
            <span id="paginationInfo"></span>
            <div class="flex items-center space-x-2" id="paginationButtons"></div>
        </div>
    </div>
</div>

<!-- Add Testimonial Modal -->
<div class="modal fade" id="addTestimonialModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="flex items-center space-x-4">
                    <div class="h-10 w-10 bg-yellow-400 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-100">
                        <i data-lucide="plus" class="w-5 h-5 text-slate-900"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Add New Testimonial</h3>
                </div>
                <button type="button" class="close h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-2xl">
                        <ul class="text-xs font-bold text-red-500 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('testimonial.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-yellow-400/10 transition-all"
                                placeholder="Enter full name">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Location</label>
                            <input type="text" name="location" value="{{ old('location') }}"
                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-yellow-400/10 transition-all"
                                placeholder="e.g. New York, NY">
                        </div>
                    </div>

                    <div class="space-y-2 mb-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Review Message</label>
                        <textarea name="message" rows="4" required
                            class="w-full bg-slate-50 border border-slate-100 rounded-3xl py-5 px-6 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-yellow-400/10 transition-all resize-none"
                            placeholder="Enter review message...">{{ old('message') }}</textarea>
                    </div>

                    <div class="space-y-2 mb-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Photo</label>
                        <label class="cursor-pointer block">
                            <input type="file" name="photo" id="addPhotoInput" accept="image/*" class="hidden">
                            <div class="bg-slate-50 border border-dashed border-slate-200 hover:border-yellow-400 hover:bg-yellow-50/30 rounded-2xl py-5 px-6 transition-all flex items-center justify-center space-x-3">
                                <div class="h-10 w-10 bg-yellow-400/10 rounded-xl flex items-center justify-center">
                                    <i data-lucide="upload" class="w-5 h-5 text-yellow-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Upload Photo</p>
                                    <p class="text-[10px] font-bold text-slate-400">JPG, PNG, GIF up to 5MB</p>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-50">
                        <button type="button" data-dismiss="modal" class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-800 transition-colors">Cancel</button>
                        <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center space-x-2 shadow-xl shadow-slate-200 active:scale-95">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-yellow-400"></i>
                            <span>Add Testimonial</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 32px !important; padding: 2rem;">
            <div class="flex flex-col items-center text-center space-y-4">
                <div class="h-16 w-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center">
                    <i data-lucide="alert-circle" class="w-8 h-8"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Delete Testimonial?</h3>
                    <p class="text-xs font-bold text-slate-500">Are you sure you want to delete <span id="deleteNameLabel" class="text-red-500"></span>'s review?</p>
                </div>
                <form action="{{ route('testimonial_delete') }}" method="post" class="w-full pt-4">
                    @csrf
                    <input type="hidden" name="id" id="deleteTestimonialId" value="">
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

    function setDeleteId(id, name) {
        document.getElementById('deleteTestimonialId').value = id;
        document.getElementById('deleteNameLabel').textContent = '"' + name + '"';
    }

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

        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });

        $('#perPageSelect').on('change', function () {
            table.page.len(parseInt(this.value)).draw();
        });

        table.on('draw', function () {
            var info = table.page.info();
            var total = info.recordsDisplay;
            var start = total === 0 ? 0 : info.start + 1;
            $('#paginationInfo').text('Showing ' + start + ' to ' + info.end + ' of ' + total + ' entries');
            buildPagination(table, info);
            lucide.createIcons();
        }).draw();

        function buildPagination(table, info) {
            var container = $('#paginationButtons');
            container.empty();
            var totalPages = info.pages;
            var currentPage = info.page;

            var prevBtn = $('<button class="h-8 w-8 rounded-full flex items-center justify-center border border-slate-100 hover:bg-slate-50 text-slate-400 transition-all"></button>');
            prevBtn.html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>');
            if (currentPage === 0) prevBtn.prop('disabled', true).addClass('opacity-40 cursor-not-allowed');
            else prevBtn.on('click', function () { table.page('previous').draw('page'); });
            container.append(prevBtn);

            var startPage = Math.max(0, currentPage - 2);
            var endPage = Math.min(totalPages - 1, startPage + 4);
            for (var i = startPage; i <= endPage; i++) {
                var pageBtn = $('<button class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-black transition-all"></button>').text(i + 1);
                if (i === currentPage) pageBtn.addClass('bg-yellow-400 text-slate-900 shadow-sm');
                else pageBtn.addClass('border border-slate-100 hover:bg-slate-50 text-slate-500');
                (function(p) { pageBtn.on('click', function () { table.page(p).draw('page'); }); })(i);
                container.append(pageBtn);
            }

            var nextBtn = $('<button class="h-8 w-8 rounded-full flex items-center justify-center border border-slate-100 hover:bg-slate-50 text-slate-400 transition-all"></button>');
            nextBtn.html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>');
            if (currentPage >= totalPages - 1) nextBtn.prop('disabled', true).addClass('opacity-40 cursor-not-allowed');
            else nextBtn.on('click', function () { table.page('next').draw('page'); });
            container.append(nextBtn);
        }
    });

    // See more / See less
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.message-container').forEach(function(container) {
            var shortText = container.querySelector('.short-text');
            var fullText = container.querySelector('.full-text');
            var seeMore = container.querySelector('.see-more');
            var seeLess = container.querySelector('.see-less');
            if (seeMore) seeMore.addEventListener('click', function() { shortText.classList.add('d-none'); fullText.classList.remove('d-none'); });
            if (seeLess) seeLess.addEventListener('click', function() { fullText.classList.add('d-none'); shortText.classList.remove('d-none'); });
        });
    });
</script>
@endpush
