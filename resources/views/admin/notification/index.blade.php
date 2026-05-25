@extends('admin.layouts.master')

@section('page_title')
Notifications
@endsection

@push('css')
<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap4.min.css" rel="stylesheet">
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
<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<div class="space-y-6 pb-12 px-6 pt-6 animate-in fade-in duration-700">
    <!-- Breadcrumb and Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <span>ENGAGEMENT</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-yellow-600 font-black">NOTIFICATIONS</span>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">Notifications</h2>
        </div>
        <div>
            <a href="{{ route('notifications.create') }}" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-black py-3 px-6 rounded-2xl shadow-lg shadow-yellow-100 transition-all active:scale-[0.98]">
                + Add Notification
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden flex flex-col min-h-[600px]">
        <!-- Table Content -->
        <div class="flex-1 overflow-x-auto">
            <table class="w-full border-collapse" id="notificationsTable">
                <thead>
                    <tr class="text-left border-b border-slate-50 bg-slate-50/20">
                        <th class="pl-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap w-20">#</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Title</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Message</th>
                        <th class="pr-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($notifications as $index => $notification)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="pl-8 py-6 text-sm font-bold text-slate-400">{{ $notifications->firstItem() + $index }}</td>
                        <td class="px-4 py-6">
                            <span class="font-black text-slate-900 text-sm whitespace-nowrap">{{ $notification->title }}</span>
                        </td>
                        <td class="px-4 py-6">
                            <span class="text-sm font-medium text-slate-500 max-w-md block overflow-hidden text-ellipsis">{{ $notification->message }}</span>
                        </td>
                        <td class="pr-8 py-6 text-right">
                            <div class="flex items-center justify-end space-x-3">
                                <!-- Send Notification -->
                                <button type="button" class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-colors" data-toggle="modal" data-target="#send{{$index }}">
                                    Send
                                </button>
                                
                                <!-- Edit Button -->
                                <button type="button" class="text-slate-400 hover:text-yellow-600 transition-colors" data-toggle="modal" data-target="#editModal{{ $notification->id }}">
                                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                                </button>

                                <!-- Delete Button -->
                                <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-slate-400 hover:text-red-500 transition-colors">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade text-left" id="editModal{{ $notification->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form method="POST" action="{{ route('notifications.update', $notification->id) }}" class="w-full">
                                        @csrf
                                        @method('POST')
                                        <div class="modal-content rounded-[32px] border-0 shadow-2xl">
                                            <div class="modal-header border-b border-slate-100 px-8 py-6 flex justify-between items-center">
                                                <h5 class="modal-title font-black text-xl text-slate-800">Edit Notification</h5>
                                                <button type="button" class="close text-slate-400 hover:text-slate-600" data-dismiss="modal" aria-label="Close">
                                                    <i data-lucide="x" class="w-6 h-6"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body p-8 space-y-6">
                                                <div>
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Title</label>
                                                    <input type="text" name="title" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-400 text-sm font-bold text-slate-800 transition-all" value="{{ $notification->title }}" required>
                                                </div>
                                                <div>
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Message</label>
                                                    <textarea name="message" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-400 text-sm font-medium text-slate-600 transition-all" rows="3" required>{{ $notification->message }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-t border-slate-100 px-8 py-6 flex justify-end space-x-4">
                                                <button type="button" class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-slate-100 transition-colors" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-black py-3 px-8 rounded-2xl shadow-lg shadow-yellow-100 transition-all">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Send Modal -->
                            <div class="modal fade text-left" id="send{{$index}}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form action="{{ route('notifications.send') }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="notify_id" value="{{ $notification->id }}">
                                        <div class="modal-content rounded-[32px] border-0 shadow-2xl">
                                            <div class="modal-header border-b border-slate-100 px-8 py-6 flex justify-between items-center">
                                                <h5 class="modal-title font-black text-xl text-slate-800">Send Notification</h5>
                                                <button type="button" class="close text-slate-400 hover:text-slate-600" data-dismiss="modal" aria-label="Close">
                                                    <i data-lucide="x" class="w-6 h-6"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body p-8 space-y-6">
                                                <div>
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Send Notification In:</label>
                                                    <div class="flex items-center space-x-6">
                                                        <label class="flex items-center space-x-2 cursor-pointer">
                                                            <input type="radio" name="send_in" value="app" class="form-radio text-yellow-400 focus:ring-yellow-400 w-4 h-4" required>
                                                            <span class="text-sm font-bold text-slate-700">App</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Select User</label>
                                                    <select name="users[]" class="form-control users-select" multiple>
                                                        <option value="all">All</option>
                                                        @foreach($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-t border-slate-100 px-8 py-6 flex justify-end space-x-4">
                                                <button type="button" class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-slate-100 transition-colors" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-black py-3 px-8 rounded-2xl shadow-lg shadow-green-100 transition-all">Send</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-20 text-center">
                            <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-slate-300 mb-4">
                                <i data-lucide="bell-off" class="w-10 h-10"></i>
                            </div>
                            <p class="font-black text-slate-400 uppercase tracking-widest">No notifications found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($notifications->hasPages())
        <div class="p-6 border-t border-slate-100">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    lucide.createIcons();
    $('.users-select').each(function () {
        if (!this.tomselect) {
            new TomSelect(this, {
                plugins: ['remove_button'],
                create: false,
                maxItems: null,
                persist: false
            });
        }
    });
    $('#notificationsTable').DataTable({
        ordering: true,
        order: [],
        paging: false,
        lengthChange: false,
        searching: true,
        info: false
    });
</script>
@endpush
