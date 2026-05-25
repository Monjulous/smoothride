@extends('admin.layouts.master')

@section('page_title')
   Offers
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

/* Existing modal/form CSS preserved */
.tab-content { border: 1px solid #dee2e6; border-top: none; padding: 20px; }
.image-preview { position: relative; display: inline-block; max-width: 100%; margin-top: 15px; }
.image-preview img { max-width: 300px; border: 1px solid #ccc; padding: 5px; }
.remove-image { position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; text-align: center; cursor: pointer; }
.card-box { border: 1px solid #dee2e6; border-radius: 5px; padding: 20px; margin-bottom: 20px; background-color: #f9f9f9; }
.section-title { font-weight: 600; font-size: 1.2rem; margin-bottom: 15px; }
</style>
@endpush

@section('content')
<div class="space-y-6 pb-12 px-6 pt-6 animate-in fade-in duration-700">
    <!-- Breadcrumb and Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <span>MARKETING</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-yellow-600 font-black">OFFERS</span>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">Offers</h2>
        </div>
        <div>
            <a href="{{ route('offer.create') }}" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-black py-3 px-6 rounded-2xl shadow-lg shadow-yellow-100 transition-all active:scale-[0.98]">
                + Add New Offer
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden flex flex-col min-h-[600px]">
        <!-- Table Content -->
        <div class="flex-1 overflow-x-auto">
            <table class="w-full border-collapse" id="offersTable">
                <thead>
                    <tr class="text-left border-b border-slate-50 bg-slate-50/20">
                        <th class="pl-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap w-20">#</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Image</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Type</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Coupon Code</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Discount</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Start</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">End</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap text-center">Status</th>
                        <th class="pr-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($offers as $key => $offer)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="pl-8 py-6 text-sm font-bold text-slate-400">{{ $key + 1 }}</td>
                        <td class="px-4 py-6">
                            <div class="h-12 w-20 rounded-xl overflow-hidden border border-slate-100 shadow-sm bg-slate-50 flex items-center justify-center">
                                @if($offer->image)
                                    <img src="{{ asset($offer->image) }}" alt="Offer Image" class="h-full w-full object-cover" onerror="this.src='{{ asset('assets/admin/img/default.jpg') }}';">
                                @else
                                    <img src="{{ asset('assets/admin/img/default.jpg') }}" alt="Default" class="h-full w-full object-cover">
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-6 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black tracking-widest uppercase bg-blue-50 text-blue-600 border border-blue-100">
                                {{ ucfirst($offer->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-6 text-sm font-bold text-slate-900 whitespace-nowrap">{{ $offer->coupon_code ?? '-' }}</td>
                        <td class="px-4 py-6 whitespace-nowrap">
                            @if($offer->discount)
                                <div class="inline-flex items-center space-x-1 px-3 py-1 bg-green-50 text-green-700 rounded-lg border border-green-100">
                                    <span class="font-black text-sm">{{ $offer->discount }}</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $offer->discount_type == 'percentage' ? '%' : '₹' }}</span>
                                </div>
                            @else
                                <span class="text-slate-400 font-bold">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-6 text-sm font-bold text-slate-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($offer->start_date)->format('d M, Y') }}</td>
                        <td class="px-4 py-6 text-sm font-bold text-slate-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($offer->end_date)->format('d M, Y') }}</td>
                        <td class="px-4 py-6 text-center whitespace-nowrap">
                            <form method="GET" action="{{ route('offer.status_update') }}" class="m-0">
                                <input type="hidden" name="id" value="{{ $offer->id }}">
                                <button class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm border {{ $offer->is_active ? 'bg-green-500 hover:bg-green-600 text-white border-green-600' : 'bg-slate-100 hover:bg-slate-200 text-slate-500 border-slate-200' }}">
                                    {{ $offer->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="pr-8 py-6 text-right">
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('offer.edit', $offer->id) }}" class="text-slate-400 hover:text-yellow-600 transition-colors">
                                   <i data-lucide="edit-3" class="w-5 h-5"></i>
                                </a>
                                <form action="{{ route('offer.destroy') }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $offer->id }}">
                                    <button class="text-slate-400 hover:text-red-500 transition-colors">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-20 text-center">
                            <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-slate-300 mb-4">
                                <i data-lucide="tag" class="w-10 h-10"></i>
                            </div>
                            <p class="font-black text-slate-400 uppercase tracking-widest">No offers found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals Preserved from Backend -->
<div class="modal fade" id="addreview" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
                    <button type="submit" class="btn btn-primary">Add Service</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('service_delete')}}" method="post">
                @csrf
                <input type="hidden" name="id" class="iddelete" value="" />
                <div class="modal-body">
                    Are Your Sure You Want to Delete !
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    lucide.createIcons();
    $(function() {
        $('#offersTable').DataTable({
            ordering: true,
            order: [],
            paging: true,
            lengthChange: false,
            searching: true,
            info: false
        });
    });

    function delete_review(id) {
        $('.iddelete').val(id);
    }
</script>
<script src="//cdn.summernote.com/4.14.1/standard/summernote.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote();
    });

    const bannerImage = document.getElementById('bannerImage');
    if (bannerImage) {
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const removeBtn = document.getElementById('removeImage');

        bannerImage.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.setAttribute('src', e.target.result);
                    imagePreview.style.display = 'inline-block';
                };
                reader.readAsDataURL(file);
            }
        });

        removeBtn.addEventListener('click', function () {
            bannerImage.value = '';
            previewImg.src = '#';
            imagePreview.style.display = 'none';
        });
    }

    $(document).ready(function () {
        $('.card-image-input').on('change', function () {
            const input = this;
            const previewContainer = $(this).siblings('.image-preview');
            const img = previewContainer.find('img')[0];

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $(img).attr('src', e.target.result);
                    previewContainer.show();
                };
                reader.readAsDataURL(input.files[0]);
            }
        });

        $('.remove-image').on('click', function () {
            const preview = $(this).closest('.image-preview');
            const input = preview.siblings('.card-image-input');
            input.val('');
            preview.hide().find('img').attr('src', '#');
        });
    });

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
@endpush
