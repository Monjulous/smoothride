@extends('admin.layouts.master')

@section('page_title')
    User Details - {{ $meta['name'] }}
@endsection

@push('css')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"></script>
<style>
/* Override Bootstrap conflicts */
.page-wrapper { background: #f8fafc; }
.card { border: none !important; box-shadow: none !important; }
#toast-container .toast-success {background: green !important;}
#toast-container .toast-error {background: red !important;}
</style>
@endpush

@section('content')
<div class="space-y-6 pb-12 px-6 pt-6" id="customer-details-app">
    <!-- Breadcrumb and Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition-colors">DASHBOARD</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <a href="{{ route('users.index') }}" class="hover:text-slate-600 transition-colors">CUSTOMERS</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-yellow-600 font-black">CUSTOMER DETAILS</span>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">{{ $meta['name'] }} {{ $meta['lname'] }}</h2>
        </div>
        <div class="flex items-center space-x-3">
            @if(@$meta['is_verifyId'] == 2)
            <div class="flex items-center space-x-1.5 bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                <i data-lucide="shield-check" class="w-3 h-3"></i>
                <span>VERIFIED DRIVER</span>
            </div>
            @endif
            <span class="text-slate-400 text-sm font-bold">Member since {{ date('M Y', strtotime($meta['created_at'])) }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-2 bg-white rounded-[32px] border border-slate-100 shadow-sm p-8 flex flex-col md:flex-row gap-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50/50 rounded-full -mr-16 -mt-16"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <div class="h-40 w-40 rounded-[40px] overflow-hidden border-4 border-white shadow-xl bg-slate-100 mb-4">
                    @php
                        $avatarSrc = !empty($meta['image']) ? asset($meta['image']) : asset('assets/admin/img/default-user.png');
                    @endphp
                    <a href="{{ $avatarSrc }}" data-fancybox="photo">
                        <img src="{{ $avatarSrc }}" alt="Profile" class="h-full w-full object-cover">
                    </a>
                </div>
                @if(@$meta['is_verifyId'] == 2)
                <span class="bg-indigo-100 text-indigo-600 px-4 py-1 rounded-lg text-[10px] font-black tracking-widest uppercase">VERIFIED</span>
                @else
                <span class="bg-slate-100 text-slate-600 px-4 py-1 rounded-lg text-[10px] font-black tracking-widest uppercase">PENDING</span>
                @endif
            </div>

            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">EMAIL ADDRESS</p>
                    <p class="font-bold text-slate-700">{{ $meta['email'] }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">PHONE NUMBER</p>
                    <p class="font-bold text-slate-700">{{ $meta['mobile'] }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ACCOUNT STATUS</p>
                    <div class="flex items-center space-x-2">
                        @if($meta['status'] == 0)
                        <div class="h-2 w-2 rounded-full" style="background-color: #7c7c10"></div>
                        <span class="font-bold text-slate-700">Active</span>
                        @else
                        <div class="h-2 w-2 rounded-full bg-red-500"></div>
                        <span class="font-bold text-slate-700">Blocked</span>
                        @endif
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">VERIFICATION</p>
                    <span class="px-2 py-0.5 rounded text-[10px] font-black tracking-widest uppercase {{ @$meta['is_verifyId'] == 2 ? 'bg-emerald-100 text-emerald-600' : 'bg-orange-100 text-orange-600' }}">
                        {{ @$meta['is_verifyId'] == 2 ? 'APPROVED' : 'PENDING' }}
                    </span>
                </div>
                <div class="md:col-span-2 space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">POSTAL ADDRESS</p>
                    @if(count($Postal_address) > 0)
                        @foreach($Postal_address as $value)
                        <p class="font-bold text-slate-700 flex items-center space-x-2">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span>{{ $value['postal_address'] }}</span>
                        </p>
                        @endforeach
                    @else
                        <p class="text-slate-400 text-sm font-medium">No address provided.</p>
                    @endif
                </div>
                <div class="md:col-span-2 space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">BIOGRAPHY</p>
                    <p class="text-sm font-medium text-slate-500 leading-relaxed">
                        {!! @$meta['bio'] ?: 'No biography provided.' !!}
                    </p>
                </div>
                <div class="md:col-span-2 flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-slate-400">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        <span class="text-xs font-bold">Registered on {{ date('M d, Y', strtotime($meta['created_at'])) }}</span>
                    </div>
                    <a href="{{ route('chat', @$meta['id']) }}" class="flex items-center space-x-2 text-blue-600 hover:text-blue-700 font-bold text-sm transition-colors">
                        <i data-lucide="message-square" class="w-4 h-4"></i>
                        <span>Send Message</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Verification Actions Card -->
        <div class="bg-slate-50 rounded-[32px] border border-slate-100 shadow-sm p-8 space-y-6">
            <div class="space-y-2">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">VERIFICATION ACTIONS</p>
                <div class="relative">
                    <select id="offer_ride_status_select" class="w-full appearance-none bg-white border border-slate-200 rounded-2xl px-6 py-4 pr-12 outline-none focus:ring-2 focus:ring-yellow-400 text-slate-800 font-bold transition-all">
                        <option value="" disabled {{ !in_array($meta['offer_ride_status'], [2,3]) ? 'selected' : '' }}>-- Update Status --</option>
                        <option value="2" {{ $meta['offer_ride_status'] == 2 ? 'selected' : '' }}>Approve Member</option>
                        <option value="3" {{ $meta['offer_ride_status'] == 3 ? 'selected' : '' }}>Disapprove Member</option>
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 w-5 h-5"></i>
                </div>
            </div>

            <button onclick="user_status1(document.getElementById('offer_ride_status_select').value, {{ $meta['id'] }})" class="w-full bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-black py-4 rounded-2xl shadow-lg shadow-yellow-100 transition-all active:scale-[0.98] flex items-center justify-center space-x-3">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Update Member</span>
            </button>

            <div class="grid grid-cols-2 gap-4">
                <button onclick="user_status({{ $meta['id'] }}, {{ $meta['status'] == 0 ? 1 : 0 }})" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-black py-4 rounded-2xl transition-all flex items-center justify-center space-x-2">
                    <i data-lucide="ban" class="w-4.5 h-4.5 {{ $meta['status'] == 0 ? 'text-slate-600' : 'text-emerald-600' }}"></i>
                    <span>{{ $meta['status'] == 0 ? 'Block' : 'Activate' }}</span>
                </button>
                <button onclick="confirmDelete({{ $meta['id'] }})" class="bg-red-50 text-red-600 hover:bg-red-100 font-black py-4 rounded-2xl transition-all flex items-center justify-center space-x-2">
                    <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                    <span>Delete</span>
                </button>
            </div>
        </div>
    </div>

    @php
        $documentsList = [
            ['key' => 'id_proof', 'label' => 'ID Proof', 'status' => 'id_proff_status', 'exp' => 'id_proof_expdate', 'category' => 'PERSONAL'],
            ['key' => 'driving_licence', 'label' => 'Driving Licence', 'status' => 'driving_licence_status', 'exp' => 'driving_licence_expdate', 'category' => 'PERSONAL'],
            ['key' => 'vehicle_plate', 'label' => 'Vehicle Plate', 'status' => 'vehicle_plate_status', 'category' => 'VEHICLE'],
            ['key' => 'insurance', 'label' => 'Insurance', 'status' => 'insurance_status', 'exp' => 'insurance_expdate', 'category' => 'VEHICLE'],
            ['key' => 'vehicle_rc', 'label' => 'Vehicle RC', 'status' => 'vehicle_rc_status', 'exp' => 'vehicle_rc_expdate', 'category' => 'VEHICLE'],
            ['key' => 'fitness_certificate', 'label' => 'Vehicle Fitness', 'status' => 'fitness_certificate_status', 'exp' => 'fitness_certificate_expdate', 'category' => 'VEHICLE'],
            ['key' => 'tax_receipt', 'label' => 'Vehicle Tax Receipt','status'=>null,'exp' => null, 'category' => 'VEHICLE'],
            ['key' => 'registration_slip', 'label' => 'Vehicle Registration Slip','status'=>null, 'exp' => null, 'category' => 'VEHICLE'],
            ['key' => 'tourist_permit', 'label' => 'Vehicle Tourist Permit', 'status' => 'tourist_permit_status', 'exp' => 'tourist_permit_expdate', 'category' => 'VEHICLE'],
            ['key' => 'driving_licence_tr', 'label' => 'Driving Licence TR', 'status' => 'driving_licence_tr_status', 'exp' => 'driving_licence_tr_expdate', 'category' => 'PERSONAL'],
            ['key' => 'puc', 'label' => 'PUC Certificate', 'status' => 'puc_status', 'exp' => 'puc_expdate', 'category' => 'VEHICLE'],
        ];
        $userVerify = $verify_id[0] ?? [];
    @endphp

    <div class="space-y-6 mt-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <h3 class="text-2xl font-black text-slate-800 tracking-tight">Document Management</h3>
                <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                    {{ count($documentsList) }} TOTAL FILES
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="space-y-2 lg:col-span-1">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">CATEGORIES</p>
                <div class="bg-yellow-400 text-slate-900 shadow-md shadow-yellow-100 w-full flex items-center space-x-3 p-4 rounded-2xl font-black text-sm transition-all">
                    <i data-lucide="file-text" class="w-4.5 h-4.5"></i>
                    <span>All Documents</span>
                </div>
            </div>

            <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($documentsList as $doc)
                @php
                    $docStatus = 'NOT UPLOADED';
                    $docStatusColor = 'bg-slate-100 text-slate-400';
                    if(!empty($userVerify[$doc['key']])) {
                        if(isset($doc['status']) && isset($userVerify[$doc['status']])) {
                            if($userVerify[$doc['status']] == 2) {
                                $docStatus = 'VERIFIED';
                                $docStatusColor = 'bg-emerald-100 text-emerald-600';
                            } elseif($userVerify[$doc['status']] == 1) {
                                $docStatus = 'PENDING';
                                $docStatusColor = 'bg-orange-100 text-orange-600';
                            }
                        } else {
                            $docStatus = 'UPLOADED';
                            $docStatusColor = 'bg-blue-100 text-blue-600';
                        }
                    }
                @endphp
                <div class="bg-white border border-slate-100 rounded-[28px] p-6 shadow-sm hover:shadow-md transition-all flex flex-col items-center text-center space-y-4 group">
                    <div class="w-full flex justify-end">
                        <span class="px-2 py-1 rounded text-[8px] font-black tracking-widest uppercase {{ $docStatusColor }}">
                            {{ $docStatus }}
                        </span>
                    </div>
                    <div class="h-16 w-16 bg-blue-50/50 rounded-2xl flex items-center justify-center text-emerald-500 cursor-pointer" onclick="openDocModal('{{ $doc['key'] }}', '{{ addslashes($doc['label']) }}', '{{ $doc['exp'] ?? '' }}', '{{ isset($doc['exp']) && isset($userVerify[$doc['exp']]) ? $userVerify[$doc['exp']] : '' }}', '{{ !empty($userVerify[$doc['key']]) ? asset($userVerify[$doc['key']]) : '' }}', '{{ $docStatus }}', '{{ $doc['status'] ?? '' }}')">
                        @if(!empty($userVerify[$doc['key']]))
                            <img src="{{ asset($userVerify[$doc['key']]) }}" class="h-full w-full object-cover rounded-2xl" />
                        @else
                            <i data-lucide="file-text" class="w-8 h-8"></i>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <h4 class="font-black text-slate-800">{{ $doc['label'] }}</h4>
                        @if(isset($doc['exp']) && !empty($userVerify[$doc['exp']]))
                            <p class="text-[10px] font-bold text-slate-400">Expiry: {{ $userVerify[$doc['exp']] }}</p>
                        @endif
                        <p class="text-[10px] font-bold text-slate-400">{{ $doc['category'] }}</p>
                    </div>
                    <button onclick="openDocModal('{{ $doc['key'] }}', '{{ addslashes($doc['label']) }}', '{{ $doc['exp'] ?? '' }}', '{{ isset($doc['exp']) && isset($userVerify[$doc['exp']]) ? $userVerify[$doc['exp']] : '' }}', '{{ !empty($userVerify[$doc['key']]) ? asset($userVerify[$doc['key']]) : '' }}', '{{ $docStatus }}', '{{ $doc['status'] ?? '' }}')" class="w-full py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $docStatus === 'VERIFIED' ? 'bg-slate-50 text-slate-400' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' }}">
                        {{ $docStatus === 'VERIFIED' ? 'View Details' : 'Manage Doc' }}
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="space-y-6 pt-6 mt-6">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Overall Reviews</h3>
            <div class="flex items-center space-x-2">
                <div class="h-2 w-2 rounded-full" style="background-color: #7c7c10"></div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">42 RECENT REVIEWS</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Driver Rating -->
            <div class="bg-white border border-slate-100 rounded-[32px] p-8 flex items-center space-x-8 shadow-sm">
                <div class="flex flex-col items-center">
                    <div class="text-5xl font-black text-slate-800 mb-2">4.8</div>
                    <div class="flex text-yellow-400 mb-2">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest text-center">DRIVER RATING</div>
                </div>
                <div class="flex-1 space-y-4">
                    <div class="flex items-center space-x-4">
                        <i data-lucide="car" class="w-4 h-4 text-slate-400"></i>
                        <div class="flex-1 space-y-1">
                            <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase">
                                <span>Ride Performance</span>
                            </div>
                            <div class="space-y-2">
                                <div class="space-y-1">
                                    <div class="flex justify-between text-[8px] font-bold text-slate-400 mb-1">
                                        <span>Punctual</span>
                                    </div>
                                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full w-[95%]" style="background-color: #7c7c10"></div>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex justify-between text-[8px] font-bold text-slate-400 mb-1">
                                        <span>Driving</span>
                                    </div>
                                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full w-[90%]" style="background-color: #7c7c10"></div>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex justify-between text-[8px] font-bold text-slate-400 mb-1">
                                        <span>Cleanly</span>
                                    </div>
                                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full w-[85%]" style="background-color: #7c7c10"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Passenger Rating -->
            <div class="bg-white border border-slate-100 rounded-[32px] p-8 flex items-center space-x-8 shadow-sm">
                <div class="flex flex-col items-center">
                    <div class="text-5xl font-black text-indigo-600 mb-2">4.9</div>
                    <div class="flex text-indigo-400 mb-2">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest text-center">PASSENGER RATING</div>
                </div>
                <div class="flex-1 space-y-4">
                    <div class="flex items-center space-x-4">
                        <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                        <div class="flex-1 space-y-1">
                            <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase">
                                <span>Guest Behavior</span>
                            </div>
                            <div class="space-y-2">
                                <div class="space-y-1">
                                    <div class="flex justify-between text-[8px] font-bold text-slate-400 mb-1">
                                        <span>Respect</span>
                                    </div>
                                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500 w-[98%]"></div>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex justify-between text-[8px] font-bold text-slate-400 mb-1">
                                        <span>Timing</span>
                                    </div>
                                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500 w-[95%]"></div>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex justify-between text-[8px] font-bold text-slate-400 mb-1">
                                        <span>Comm.</span>
                                    </div>
                                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500 w-[92%]"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Feedback -->
        <div class="bg-white border border-slate-100 rounded-[32px] p-8 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">LATEST FEEDBACK</p>
            <div class="space-y-8 divide-y divide-slate-50">
                @php
                $reviews = [
                    ['name' => 'Marcus Thompson', 'tag' => 'PASSENGER', 'rating' => 5.0, 'comment' => 'Elena is a fantastic driver. Very punctual, the car was spotless, and the route taken was perfect. Highly recommend commuting with her!', 'date' => 'OCT 26, 2024'],
                    ['name' => 'Sarah Jenkins', 'tag' => 'DRIVER', 'rating' => 4.0, 'comment' => 'Great passenger, very respectful. Was ready on time at the pickup spot. Only 4 stars because of some minor phone call noise, but otherwise perfect.', 'date' => 'OCT 22, 2024'],
                    ['name' => 'David Chen', 'tag' => 'PASSENGER', 'rating' => 5.0, 'comment' => 'Super smooth ride. Elena is very professional and a safe driver. I\'ve been riding with her for a month now and never had an issue.', 'date' => 'OCT 19, 2024']
                ];
                @endphp
                @foreach($reviews as $review)
                <div class="pt-6 first:pt-0 pb-6 group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-400 shrink-0 overflow-hidden">
                                <img src="{{ asset('assets/admin/img/default-user.png') }}" alt="{{ $review['name'] }}" />
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-black text-slate-900">{{ $review['name'] }}</span>
                                    <span class="px-2 py-0.5 rounded text-[8px] font-black tracking-widest uppercase {{ $review['tag'] === 'PASSENGER' ? 'bg-purple-100 text-purple-600' : 'bg-indigo-100 text-indigo-600' }}">
                                        {{ $review['tag'] }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-1 text-yellow-400">
                                    @for($star = 0; $star < 5; $star++)
                                    <i data-lucide="star" class="w-2.5 h-2.5 {{ $star < $review['rating'] ? 'fill-current' : 'text-slate-200' }}"></i>
                                    @endfor
                                    <span class="text-[10px] font-black text-slate-400 ml-1">{{ number_format($review['rating'], 1) }}</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $review['date'] }}</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500 leading-relaxed pl-13" style="padding-left: 3.25rem;">
                        "{!! $review['comment'] !!}"
                    </p>
                </div>
                @endforeach
            </div>
            
            <button class="w-full mt-8 py-4 bg-slate-50 hover:bg-slate-100 text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl transition-all">
                SEE ALL REVIEWS
            </button>
        </div>
    </div>
</div>

<!-- Modal equivalent implemented with Bootstrap/Tailwind -->
<div class="modal fade" id="docViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-[40px] border-0 shadow-2xl overflow-hidden p-0">
            <div class="flex items-center justify-between px-8 py-6 border-b border-slate-100 bg-white">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-xl text-slate-800 tracking-tight" id="modalDocTitle">Document</h4>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">DOCUMENT MANAGEMENT</p>
                    </div>
                </div>
                <button type="button" class="p-3 hover:bg-slate-50 rounded-2xl text-slate-400 hover:text-slate-600 transition-all" data-dismiss="modal">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="flex flex-col lg:flex-row h-[70vh] bg-white">
                <!-- Preview Area -->
                <div class="bg-slate-50 p-8 flex items-center justify-center border-r border-slate-100 lg:w-1/2 h-full overflow-hidden relative">
                    <img id="modalDocPreview" src="" class="rounded-2xl shadow-xl max-h-[60vh] object-contain border border-slate-200 hidden" />
                    <div id="modalDocEmpty" class="flex flex-col items-center justify-center text-slate-400 space-y-4">
                        <i data-lucide="file-x-2" class="w-16 h-16 opacity-20"></i>
                        <p class="font-bold text-sm">No document uploaded yet</p>
                    </div>
                </div>
                <!-- Actions Area -->
                <div class="p-8 lg:w-1/2 space-y-8 overflow-y-auto">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">CURRENT STATUS</p>
                        <span id="modalDocStatusBadge" class="px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase bg-slate-100 text-slate-400">
                            UNKNOWN
                        </span>
                    </div>

                    <form id="editDocForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="edit_doc_key" name="doc_key">
                        <input type="hidden" id="edit_user_id" name="user_id" value="{{ $meta['id'] }}">
                        
                        <div class="space-y-4">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">UPLOAD / REPLACE DOCUMENT</p>
                            <input type="file" class="form-control rounded-2xl py-2 px-4 text-sm bg-white" name="document">
                        </div>

                        <div class="space-y-4 mt-4" id="expiry_field_wrapper" style="display:none;">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">EXPIRY DATE</p>
                            <input type="date" class="form-control rounded-2xl py-2 px-4 text-sm bg-white" name="expiry_date" id="edit_expiry_date">
                        </div>
                        
                        <div class="mt-6">
                            <button type="button" class="w-full py-4 rounded-2xl bg-blue-50 text-blue-600 font-black text-sm uppercase tracking-widest hover:bg-blue-100 transition-all shadow-sm flex justify-center items-center gap-2" onclick="saveDocument()">
                                <i data-lucide="upload" class="w-4 h-4"></i>
                                Save Uploaded Details
                            </button>
                        </div>
                    </form>

                    <hr class="border-slate-100 my-6">
                    
                    <div id="verificationActions" class="space-y-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">VERIFICATION ACTIONS</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button type="button" id="btnDeclineDoc" class="flex items-center justify-center space-x-3 bg-red-50 text-red-600 p-5 rounded-[24px] font-black text-[12px] uppercase tracking-widest hover:bg-red-100 transition-all">
                                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                                <span>Decline</span>
                            </button>
                            <button type="button" id="btnApproveDoc" class="flex items-center justify-center space-x-3 bg-emerald-500 text-white p-5 rounded-[24px] font-black text-[12px] uppercase tracking-widest hover:bg-emerald-600 shadow-lg shadow-emerald-100 transition-all">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                                <span>Approve</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script>
    // Initialize Lucide icons
    lucide.createIcons();
    
    // Existing fancybox and ajax setup
    $('[data-fancybox="gallery"], [data-fancybox="photo"]').fancybox({
        buttons: ["slideShow", "thumbs", "zoom", "fullScreen", "share", "close"],
        loop: false, protect: true
    });
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    let currentDocStatusCol = '';
    let currentUserId = {{ $userVerify['user_id'] ?? $meta['id'] }};
    let currentDocKey = '';

    function openDocModal(docKey, label, expKey, expValue, previewUrl, docStatus, statusCol) {
        $("#edit_doc_key").val(docKey);
        currentDocKey = docKey;
        currentDocStatusCol = statusCol;
        
        if(expKey) {
            $("#expiry_field_wrapper").show();
            $("#edit_expiry_date").val(expValue || '');
        } else {
            $("#expiry_field_wrapper").hide();
        }

        $("#modalDocTitle").text(label);
        
        if (previewUrl) {
            $("#modalDocPreview").attr('src', previewUrl).removeClass('hidden');
            $("#modalDocEmpty").addClass('hidden');
        } else {
            $("#modalDocPreview").addClass('hidden').attr('src', '');
            $("#modalDocEmpty").removeClass('hidden');
        }

        // Status Badge
        let badgeClass = 'bg-slate-100 text-slate-400';
        if (docStatus === 'VERIFIED') badgeClass = 'bg-emerald-100 text-emerald-600';
        else if (docStatus === 'PENDING') badgeClass = 'bg-orange-100 text-orange-600';
        else if (docStatus === 'UPLOADED') badgeClass = 'bg-blue-100 text-blue-600';
        
        $("#modalDocStatusBadge").removeClass().addClass('px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase ' + badgeClass).text(docStatus);

        // Action Buttons
        if (statusCol && docStatus !== 'NOT UPLOADED') {
            $("#verificationActions").show();
            $("#btnDeclineDoc").off('click').on('click', function() { update_Verify_doc(docKey, statusCol, currentUserId, 1); });
            $("#btnApproveDoc").off('click').on('click', function() { update_Verify_doc(docKey, statusCol, currentUserId, 2); });
        } else {
            $("#verificationActions").hide();
        }

        $("#docViewerModal").modal("show");
    }

    function saveDocument() {
        var formData = new FormData($("#editDocForm")[0]);
        $.ajax({
            url: "{{ route('admin.update-document') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                toastr.success("Document updated successfully!");
                setTimeout(() => location.reload(), 1500);
            },
            error: function(err) { toastr.error("Error updating document"); }
        });
    }

    function update_Verify_doc(key_col, status_col, uid, doc_status) {
        let actionText = doc_status === 2 ? 'approve' : 'decline';
        if (confirm(`Are you sure you want to ${actionText} this document?`)) {
            $.ajax({
                url: "{{ route('admin.update_Verify_doc') }}",
                type: "POST",
                data: { status_col: status_col, uid: uid, doc_status: doc_status, key_col: key_col, _token: '{{ csrf_token() }}' },
                success: function (res) {
                    if (res.status === 'success') {
                        toastr.success(res.message);
                        setTimeout(() => location.reload(), 1500); 
                    } else { toastr.error(res.message); }
                },
                error: function (xhr) { toastr.error('An error occurred while updating the document status.'); }
            });
        }
    }

    function user_status1(val, uid) {
        if (!val) return;
        $.ajax({
            url: "{{ route('admin.updateuserstatus') }}",
            type: "POST",
            data: { uid: uid, status: val, _token: "{{ csrf_token() }}" },
            success: function(res) {
               if (res.status === 'success') {
                    toastr.success(res.message);
                    setTimeout(() => location.reload(), 1500); 
                } else { toastr.error(res.message); }
            },
            error: function(res) { toastr.error('Server error, please try again.'); }
        });
    }

    function user_status(id, val) {
        $.ajax({
            url: "{{ route('admin.user_status')}}",
            type: "post",
            data: { 'id': id, 'status': val },
            success: function(res) {
                if (res == 1) {
                    toastr.success('User status update successfully');
                    setTimeout(() => location.reload(), 1500);
                } else toastr.error('Something went wrong, please try again.');
            },
            error: function(res) { toastr.error('Error connecting to server.'); }
        });
    }

    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: "{{ route('admin.delete_userr')}}",
                type: "post",
                data: { 'id': id },
                success: function(res) {
                    if (res == 1) {
                        toastr.success('Delete successfully');
                        window.location.href = "{{ route('users.index') }}";
                    } else toastr.error('Something went wrong, please try again.');
                },
                error: function(res) { toastr.error('Error connecting to server.'); }
            });
        }
    }
</script>
@endpush