@extends('admin.layouts.master')

@section('page_title')
Edit Terms & Conditions
@endsection

@push('css')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="//cdn.summernote.com/4.14.1/standard/summernote.css" rel="stylesheet">
<style>
/* Override Bootstrap conflicts */
.page-wrapper { background: #f8fafc; }
.card { border: none !important; box-shadow: none !important; }

/* Summernote overrides */
.note-editor.note-frame {
    border: none !important;
    border-radius: 0 !important;
}
.note-toolbar {
    background: #fcfcfd !important;
    border-bottom: 1px solid #f1f5f9 !important;
    padding: 12px 24px !important;
}
.note-statusbar {
    background: #fcfcfd !important;
    border-top: 1px solid #f1f5f9 !important;
}
</style>
@endpush

@section('content')
<div class="space-y-6 pb-12 px-6 pt-6 animate-in fade-in duration-700">
    
    @if (Session::has('success'))
        <div class="fixed top-24 right-8 z-[200] px-6 py-4 rounded-2xl shadow-2xl flex items-center space-x-3 border bg-slate-900 border-green-500/20 text-white transition-all">
            <div class="p-2 rounded-xl bg-green-500/10">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400"></i>
            </div>
            <span class="text-sm font-black tracking-wide">{{ Session::get('success') }}</span>
        </div>
    @endif

    <!-- Header Area -->
    <div class="space-y-1">
        <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            <span>CMS</span>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-yellow-600 font-black uppercase">Terms & Conditions</span>
        </div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">Terms & Conditions</h2>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- Editor Container 1 -->
        <form action="{{route('update_terms',$terms->id)}}" method="post" class="space-y-4">
            @csrf
            <div class="flex items-center justify-between pl-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Driver Terms</label>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-white/50 px-4 py-1.5 rounded-full border border-slate-100">
                    Last Updated: {{ $terms->updated_at ? $terms->updated_at->format('d M Y') : 'Unknown' }}
                </div>
            </div>
            
            <div class="bg-white border border-slate-100 rounded-[32px] overflow-hidden shadow-sm focus-within:ring-4 focus-within:ring-yellow-400/20 transition-all flex flex-col min-h-[550px]">
                <textarea class="summernote" name="content">{{ $terms->content }}</textarea>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center justify-center space-x-2 shadow-xl shadow-slate-200 active:scale-95">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-yellow-400"></i>
                    <span>Save Driver Terms</span>
                </button>
            </div>
        </form>

        <!-- Editor Container 2 -->
        <form action="{{route('update_terms',$terms1->id)}}" method="post" class="space-y-4">
            @csrf
            <div class="flex items-center justify-between pl-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Customer Terms</label>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-white/50 px-4 py-1.5 rounded-full border border-slate-100">
                    Last Updated: {{ $terms1->updated_at ? $terms1->updated_at->format('d M Y') : 'Unknown' }}
                </div>
            </div>
            
            <div class="bg-white border border-slate-100 rounded-[32px] overflow-hidden shadow-sm focus-within:ring-4 focus-within:ring-yellow-400/20 transition-all flex flex-col min-h-[550px]">
                <textarea class="summernote" name="content">{{ $terms1->content }}</textarea>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center justify-center space-x-2 shadow-xl shadow-slate-200 active:scale-95">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-yellow-400"></i>
                    <span>Save Customer Terms</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    lucide.createIcons();
</script>
<script src="//cdn.summernote.com/4.14.1/standard/summernote.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 450,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush