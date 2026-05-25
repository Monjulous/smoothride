@extends('admin.layouts.master')

@section('page_title')
   Services
@endsection

@push('css')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="//cdn.summernote.com/4.14.1/standard/summernote.css" rel="stylesheet">
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

/* Summernote overrides */
.note-editor.note-frame { border: none !important; border-radius: 0 !important; }
.note-toolbar { background: #fcfcfd !important; border-bottom: 1px solid #f1f5f9 !important; padding: 10px 20px !important; }
.note-statusbar { background: #fcfcfd !important; border-top: 1px solid #f1f5f9 !important; }
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
                <span class="text-yellow-600 font-black uppercase">Services</span>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">Services CMS</h2>
        </div>
        <button type="button" data-toggle="modal" data-target="#addServiceModal"
            class="bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-black py-4 px-10 rounded-2xl shadow-lg shadow-yellow-100 transition-all uppercase tracking-wider flex items-center space-x-3 active:scale-[0.98]">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span>Add New Service</span>
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-slate-100 rounded-[40px] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="table">
                <thead>
                    <tr class="bg-[#f1f6fa]">
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-20">SR NO.</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">TITLE</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">DESCRIPTION</th>
                        <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if($service)
                    @foreach($service as $key => $val)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-6 text-sm font-bold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-black text-slate-900">{{ $val->title }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="message-container">
                                <span class="short-text">
                                    <p class="text-xs font-bold text-slate-500 leading-relaxed max-w-md line-clamp-2">
                                        {!! \Illuminate\Support\Str::limit(strip_tags($val->description), 120) !!}
                                    </p>
                                    @if(strlen($val->description) > 100)
                                        <span class="see-more text-[10px] font-black text-blue-500 cursor-pointer uppercase tracking-widest">See more</span>
                                    @endif
                                </span>
                                <span class="full-text d-none">
                                    <p class="text-xs font-bold text-slate-500 leading-relaxed max-w-md">{{ strip_tags($val->description) }}</p>
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
                                    onclick="setDeleteId(`{{ $val->id }}`)"
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
                                            <i data-lucide="car" class="w-5 h-5 text-slate-900"></i>
                                        </div>
                                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Edit Service</h3>
                                    </div>
                                    <button type="button" class="close h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('service.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $val->id }}">

                                        <div class="space-y-2 mb-6">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Title</label>
                                            <input type="text" name="title" value="{{ $val->title }}"
                                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-6 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-yellow-400/10 transition-all hover:bg-white" required>
                                        </div>

                                        <div class="space-y-2 mb-6">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Description</label>
                                            <div class="bg-white border border-slate-100 rounded-[24px] overflow-hidden">
                                                <textarea class="summernote-edit" name="description">{{ $val->description }}</textarea>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-end space-x-3 pt-4">
                                            <button type="button" data-dismiss="modal" class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-800 transition-colors">Cancel</button>
                                            <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center space-x-2 shadow-xl shadow-slate-200 active:scale-95">
                                                <i data-lucide="check-circle-2" class="w-4 h-4 text-yellow-400"></i>
                                                <span>Save Changes</span>
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
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="flex items-center space-x-4">
                    <div class="h-10 w-10 bg-yellow-400 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-100">
                        <i data-lucide="plus" class="w-5 h-5 text-slate-900"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Add New Service</h3>
                </div>
                <button type="button" class="close h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all" data-dismiss="modal" aria-label="Close">
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

                <form action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-2 mb-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-6 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-yellow-400/10 transition-all hover:bg-white"
                            placeholder="Enter service title" required>
                    </div>

                    <div class="space-y-2 mb-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Description</label>
                        <div class="bg-white border border-slate-100 rounded-[24px] overflow-hidden">
                            <textarea class="summernote-add" name="description">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button type="button" data-dismiss="modal" class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-800 transition-colors">Cancel</button>
                        <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center space-x-2 shadow-xl shadow-slate-200 active:scale-95">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-yellow-400"></i>
                            <span>Add Service</span>
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
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Delete Service?</h3>
                    <p class="text-xs font-bold text-slate-500">Are you sure you want to delete this service? This action cannot be undone.</p>
                </div>
                <form action="{{ route('service_delete') }}" method="post" class="w-full pt-4">
                    @csrf
                    <input type="hidden" name="id" id="deleteServiceId" value="">
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

    function setDeleteId(id) {
        document.getElementById('deleteServiceId').value = id;
    }

    $(function() {
        $('#table').DataTable({
            ordering: true,
            order: [],
            paging: false,
            lengthChange: false,
            searching: false,
            info: false
        });
    });

    // See more / See less
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.message-container').forEach(container => {
            const shortText = container.querySelector('.short-text');
            const fullText = container.querySelector('.full-text');
            const seeMore = container.querySelector('.see-more');
            const seeLess = container.querySelector('.see-less');
            if (seeMore) {
                seeMore.addEventListener('click', () => {
                    shortText.classList.add('d-none');
                    fullText.classList.remove('d-none');
                });
            }
            if (seeLess) {
                seeLess.addEventListener('click', () => {
                    fullText.classList.add('d-none');
                    shortText.classList.remove('d-none');
                });
            }
        });
    });
</script>
<script src="//cdn.summernote.com/4.14.1/standard/summernote.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote-add').summernote({ height: 200, toolbar: [['style',['bold','italic','underline']],['para',['ul','ol']],['view',['codeview']]] });
        $('.summernote-edit').summernote({ height: 200, toolbar: [['style',['bold','italic','underline']],['para',['ul','ol']],['view',['codeview']]] });
    });
</script>
@endpush
