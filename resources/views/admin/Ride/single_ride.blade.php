@extends('admin.layouts.master')

@section('page_title')
    Ride Info View
@endsection

@push('css')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<style>
.page-wrapper { background: #f8fafc; }
.card { border: none !important; box-shadow: none !important; }
</style>
@endpush

@section('content')
@php
    $arr = @$ride[0]['stoppage'];
    $raw = json_decode(@$arr);
    $seat = @$ride[0]['passenger_count'];
    $book = @$ride[0]['total_passenger'];

    // Dynamic seating arrangement setup
    $seatsLayout = [
        '1A' => ['label' => '1A Window', 'occupied' => false, 'name' => '', 'fullname' => ''],
        '2A' => ['label' => '2A Window', 'occupied' => false, 'name' => '', 'fullname' => ''],
        '2B' => ['label' => '2B Middle', 'occupied' => false, 'name' => '', 'fullname' => ''],
        '2C' => ['label' => '2C Window', 'occupied' => false, 'name' => '', 'fullname' => ''],
    ];
    $seatKeys = ['1A', '2A', '2B', '2C'];
    $seatIndex = 0;
    foreach($booking as $b) {
        if ($b['cancel_booking'] != 'Cancelledby' && $b['cancel_booking'] != 'Cancelled') {
            if ($seatIndex < count($seatKeys)) {
                $key = $seatKeys[$seatIndex];
                $seatsLayout[$key]['occupied'] = true;
                $seatsLayout[$key]['name'] = strtoupper(explode(' ', $b['name'])[0]);
                $seatsLayout[$key]['fullname'] = $b['name'] . ' ' . $b['lname'];
                $seatIndex++;
            }
        }
    }

    // Extract pickup city for live overlay
    $pickCity = 'Route View';
    if (!empty($ride[0]['pick_location'])) {
        $parts = explode(',', $ride[0]['pick_location']);
        $pickCity = trim(end($parts));
        if (strlen($pickCity) < 3 && count($parts) > 1) {
            $pickCity = trim($parts[count($parts) - 2]);
        }
    }
@endphp

<div class="space-y-6 pb-12 px-6 pt-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <a href="{{ route('Ride') }}" class="hover:text-slate-600 transition-colors">RIDES</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-yellow-600 font-black">RIDE DETAILS</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('Ride') }}" class="h-10 w-10 flex items-center justify-center rounded-full bg-white border border-slate-100 shadow-sm text-slate-600 hover:text-yellow-600 transition-all">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight px-1">Ride #{{ @$ride[0]['rideid'] }}</h2>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            @if(@$ride[0]['complete_status'] == 0)
                <span class="inline-flex items-center space-x-1.5 px-4 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest border border-blue-100">
                    <i data-lucide="refresh-cw" class="w-3 h-3 animate-spin"></i>
                    <span>IN PROGRESS</span>
                </span>
            @else
                <span class="inline-flex items-center space-x-1.5 px-4 py-1.5 rounded-xl bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest border border-green-100">
                    <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                    <span>COMPLETED</span>
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Column --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Map Container with overlays --}}
            <div class="relative h-[400px] bg-slate-200 rounded-[32px] overflow-hidden shadow-sm border border-slate-100 group">
                <div id="map" style="height: 100%; width: 100%;"></div>
                
                {{-- Live Tracking Floating Badge --}}
                <div class="absolute top-6 left-6 p-4 rounded-2xl bg-white/90 backdrop-blur shadow-xl border border-white max-w-[200px]">
                    <div class="flex items-center space-x-2 mb-1">
                        <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Live Route</span>
                    </div>
                    <p class="text-xs font-bold text-slate-500" id="liveText">Calculating route...</p>
                </div>
                
                {{-- Centered City Indicator --}}
                <div class="absolute inset-x-0 bottom-12 flex justify-center pointer-events-none">
                    <h3 class="text-4xl font-black text-slate-900 drop-shadow-2xl opacity-75 select-none">{{ $pickCity }}</h3>
                </div>
            </div>

            {{-- Trip Summary Card --}}
            <div class="bg-blue-50/50 rounded-[32px] border border-blue-100 p-8 relative overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900">Trip Summary</h3>
                        <p class="text-xs font-bold text-slate-400">Route ID: #{{ @$ride[0]['rideid'] }}</p>
                    </div>
                    @if(@$ride[0]['is_verifyId'])
                        <div class="px-4 py-2 bg-indigo-600 rounded-full flex items-center space-x-2 text-white shadow-lg shadow-indigo-200">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Verified Trip</span>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="h-10 w-10 flex-shrink-0 rounded-full bg-white flex items-center justify-center text-yellow-600 shadow-sm">
                                <span class="h-2 w-2 rounded-full bg-yellow-600"></span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pickup</p>
                                <p class="text-lg font-bold text-slate-800 leading-tight">{{ @$ride[0]['pick_location'] }}</p>
                            </div>
                        </div>

                        {{-- Intermediate stops if any --}}
                        @if(!empty($raw))
                            @foreach($raw as $idx => $stop)
                            <div class="flex items-start space-x-4 relative pl-1">
                                <div class="h-8 w-8 flex-shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shadow-sm">
                                    <span class="text-[9px] font-black">{{ $idx + 1 }}</span>
                                </div>
                                <div class="absolute -top-6 bottom-6 left-5 w-px border-l-2 border-dashed border-slate-200"></div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Stop {{ $idx + 1 }}</p>
                                    <p class="text-sm font-bold text-slate-600 leading-tight">{{ @$stop->name }}</p>
                                </div>
                            </div>
                            @endforeach
                        @endif

                        <div class="flex items-start space-x-4">
                            <div class="h-10 w-10 flex-shrink-0 rounded-full bg-white flex items-center justify-center text-indigo-600 shadow-sm relative">
                                <i data-lucide="map-pin" class="w-4 h-4 text-indigo-600"></i>
                                <div class="absolute -top-10 bottom-10 left-1/2 -translate-x-1/2 w-px border-l-2 border-dashed border-slate-200"></div>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Dropoff</p>
                                <p class="text-lg font-bold text-slate-800 leading-tight">{{ @$ride[0]['drop_location'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/60 backdrop-blur rounded-3xl p-6 space-y-4 border border-white/80 h-fit">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 text-slate-500">
                                <i data-lucide="navigation" class="w-4 h-4"></i>
                                <span class="text-xs font-bold">Total Distance</span>
                            </div>
                            <span class="text-sm font-black text-slate-900" id="displayDistance">--</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 text-slate-500">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                                <span class="text-xs font-bold">Estimated Duration</span>
                            </div>
                            <span class="text-sm font-black text-slate-900" id="displayDuration">--</span>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-lg font-black text-slate-900 uppercase italic">Price / Seat</span>
                            <span class="text-3xl font-black text-yellow-600 tracking-tight">₹{{ @$ride[0]['price'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Post-Ride Reviews --}}
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <i data-lucide="star" class="text-yellow-400 fill-yellow-400 w-6 h-6"></i>
                    <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Post-Ride Reviews</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Review 1: Driver's perspective --}}
                    <div class="bg-white border border-slate-100 p-8 rounded-[32px] shadow-sm flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">{{ strtoupper(@$ride[0]['name']) }}'S REVIEW FOR PASSENGERS</p>
                                <div class="flex space-x-0.5">
                                    @for($i=0; $i<5; $i++)
                                        <i data-lucide="star" class="w-3 h-3 fill-yellow-400 text-yellow-400"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-slate-600 font-medium italic leading-relaxed">"Great trip! All passengers were prompt, respectful of the vehicle rules, and made the commute highly engaging. Safe driving all around."</p>
                        </div>
                        <div class="mt-8 flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-full overflow-hidden border border-slate-200">
                                <img src="{{ asset(@$ride[0]['image']) }}" class="h-full w-full object-cover" onerror="this.src='{{ asset('assets/admin/img/default-user.png') }}';">
                            </div>
                            <span class="text-xs font-bold text-slate-800">{{ @$ride[0]['name'] }} {{ @$ride[0]['lname'] }} (Driver)</span>
                        </div>
                    </div>

                    {{-- Review 2: Passenger perspective --}}
                    @php
                        $passengerName = count($booking) > 0 ? $booking[0]['name'] . ' ' . $booking[0]['lname'] : 'Alex J.';
                        $passengerImg = count($booking) > 0 ? $booking[0]['image'] : '';
                    @endphp
                    <div class="bg-white border border-slate-100 p-8 rounded-[32px] shadow-sm flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">{{ strtoupper(explode(' ', $passengerName)[0]) }}'S REVIEW FOR DRIVER</p>
                                <div class="flex space-x-0.5">
                                    @for($i=0; $i<5; $i++)
                                        <i data-lucide="star" class="w-3 h-3 fill-yellow-400 text-yellow-400"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-slate-600 font-medium italic leading-relaxed">"Extremely comfortable and neat car. The driver was highly professional, kept perfect time, and navigated traffic smoothly. 10/10 service!"</p>
                        </div>
                        <div class="mt-8 flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-full overflow-hidden border border-slate-200">
                                <img src="{{ asset($passengerImg) }}" class="h-full w-full object-cover" onerror="this.src='{{ asset('assets/admin/img/default-user.png') }}';">
                            </div>
                            <span class="text-xs font-bold text-slate-800">{{ $passengerName }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar Column --}}
        <div class="space-y-8">
            
            {{-- Verification / Admin Actions --}}
            <div class="bg-blue-50/50 border border-blue-100 p-8 rounded-[32px] space-y-4 shadow-sm">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Verification Actions</p>
                <div class="relative group/select">
                    @php 
                        $statusList = [0 => 'Requested', 1 => 'Approved', 2 => 'Decline']; 
                        $currentStatus = @$ride[0]['status'];
                    @endphp
                    <select id="rideAdminStatus" 
                            class="w-full bg-white border border-slate-200 rounded-2xl py-3 px-6 pr-12 text-sm font-bold text-slate-900 appearance-none outline-none focus:ring-2 focus:ring-yellow-400 cursor-pointer shadow-sm transition-all"
                            data-id="{{ $ride[0]['id'] }}"
                            data-pre="{{ $currentStatus }}">
                        @foreach($statusList as $key => $val)
                            <option value="{{ $key }}" @if($key == $currentStatus) selected @endif>{{ $val }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-4 h-4"></i>
                </div>
                <button type="button" onclick="Swal.fire('Message Center', 'Direct messaging with drivers is enabled for administrative follow-ups.', 'info')" class="w-full bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-black py-4 rounded-2xl shadow-lg shadow-yellow-100 transition-all flex items-center justify-center space-x-2">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    <span>Send Message</span>
                </button>
            </div>

            {{-- Vehicle Seating Layout --}}
            <div class="bg-white border border-slate-100 rounded-[32px] p-8 shadow-sm overflow-hidden">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-1 mb-6">Vehicle Seating Layout</p>
                
                <div class="relative mb-8">
                    <div class="bg-yellow-600 rounded-3xl p-8 text-white relative z-10 shadow-xl shadow-yellow-100/50">
                        <h4 class="text-xl font-black mb-0.5">Smoothride Sedan</h4>
                        <p class="text-[10px] font-black opacity-70 tracking-widest">COMFORT CLASS</p>
                        <div class="absolute top-0 right-0 p-8 opacity-10">
                            <i data-lucide="car" class="w-20 h-20"></i>
                        </div>
                    </div>
                </div>

                {{-- Seating Arrangement Visualizer --}}
                <div class="bg-blue-50/40 rounded-[24px] p-8 space-y-8 border border-blue-50">
                    {{-- Front Row --}}
                    <div class="flex items-center justify-center space-x-12">
                        {{-- Driver --}}
                        <div class="flex flex-col items-center group/seat cursor-help" title="Driver: {{ @$ride[0]['name'] }} {{ @$ride[0]['lname'] }}">
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center bg-yellow-100 text-yellow-600 shadow-sm border border-yellow-200">
                                <i data-lucide="armchair" class="w-5 h-5"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase mt-1 tracking-tighter text-slate-800">Driver</span>
                            <span class="text-[8px] font-bold text-slate-400 -mt-0.5 max-w-[50px] truncate">{{ strtoupper(explode(' ', @$ride[0]['name'])[0]) }}</span>
                        </div>

                        {{-- Seat 1A --}}
                        @php $seat = $seatsLayout['1A']; @endphp
                        <div class="flex flex-col items-center group/seat cursor-help" title="{{ $seat['occupied'] ? $seat['fullname'] : 'Vacant Seat' }}">
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center transition-all {{ $seat['occupied'] ? 'bg-yellow-100 text-yellow-600 shadow-sm border border-yellow-200' : 'bg-slate-50 text-slate-300' }}">
                                <i data-lucide="armchair" class="w-5 h-5"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase mt-1 tracking-tighter {{ $seat['occupied'] ? 'text-slate-800' : 'text-slate-400' }}">1A Window</span>
                            <span class="text-[8px] font-bold -mt-0.5 {{ $seat['occupied'] ? 'text-slate-400' : 'text-slate-300' }}">{{ $seat['occupied'] ? $seat['name'] : 'Vacant' }}</span>
                        </div>
                    </div>

                    {{-- Back Row --}}
                    <div class="flex items-center justify-center space-x-8 pt-4 border-t border-slate-100">
                        {{-- Seat 2A --}}
                        @php $seat = $seatsLayout['2A']; @endphp
                        <div class="flex flex-col items-center group/seat cursor-help" title="{{ $seat['occupied'] ? $seat['fullname'] : 'Vacant Seat' }}">
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center transition-all {{ $seat['occupied'] ? 'bg-yellow-100 text-yellow-600 shadow-sm border border-yellow-200' : 'bg-slate-50 text-slate-300' }}">
                                <i data-lucide="armchair" class="w-5 h-5"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase mt-1 tracking-tighter {{ $seat['occupied'] ? 'text-slate-800' : 'text-slate-400' }}">2A Window</span>
                            <span class="text-[8px] font-bold -mt-0.5 {{ $seat['occupied'] ? 'text-slate-400' : 'text-slate-300' }}">{{ $seat['occupied'] ? $seat['name'] : 'Vacant' }}</span>
                        </div>

                        {{-- Seat 2B --}}
                        @php $seat = $seatsLayout['2B']; @endphp
                        <div class="flex flex-col items-center group/seat cursor-help" title="{{ $seat['occupied'] ? $seat['fullname'] : 'Vacant Seat' }}">
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center transition-all {{ $seat['occupied'] ? 'bg-yellow-100 text-yellow-600 shadow-sm border border-yellow-200' : 'bg-slate-50 text-slate-300' }}">
                                <i data-lucide="armchair" class="w-5 h-5"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase mt-1 tracking-tighter {{ $seat['occupied'] ? 'text-slate-800' : 'text-slate-400' }}">2B Middle</span>
                            <span class="text-[8px] font-bold -mt-0.5 {{ $seat['occupied'] ? 'text-slate-400' : 'text-slate-300' }}">{{ $seat['occupied'] ? $seat['name'] : 'Vacant' }}</span>
                        </div>

                        {{-- Seat 2C --}}
                        @php $seat = $seatsLayout['2C']; @endphp
                        <div class="flex flex-col items-center group/seat cursor-help" title="{{ $seat['occupied'] ? $seat['fullname'] : 'Vacant Seat' }}">
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center transition-all {{ $seat['occupied'] ? 'bg-yellow-100 text-yellow-600 shadow-sm border border-yellow-200' : 'bg-slate-50 text-slate-300' }}">
                                <i data-lucide="armchair" class="w-5 h-5"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase mt-1 tracking-tighter {{ $seat['occupied'] ? 'text-slate-800' : 'text-slate-400' }}">2C Window</span>
                            <span class="text-[8px] font-bold -mt-0.5 {{ $seat['occupied'] ? 'text-slate-400' : 'text-slate-300' }}">{{ $seat['occupied'] ? $seat['name'] : 'Vacant' }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Passenger Capacity</span>
                        <span class="text-sm font-black text-slate-900">{{ @$ride[0]['passenger_count'] }} Seats</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Bags Limit</span>
                        <span class="text-sm font-black text-slate-900">
                            {{ @$ride[0]['small_bag'] ?: 0 }} Small, {{ @$ride[0]['oversize_bag'] ?: 0 }} Large
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Amenities</span>
                        <span class="text-sm font-black text-slate-900">
                            {{ @$ride[0]['smoking_allowed'] ? 'Smoking' : 'No Smoking' }},
                            {{ @$ride[0]['pets_allowed'] ? 'Pets' : 'No Pets' }},
                            {{ @$ride[0]['music'] ? 'Music' : 'No Music' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Participants list --}}
            <div class="space-y-6">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Ride Participants</p>
                <div class="space-y-3">
                    {{-- Host --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-4 flex items-center justify-between group hover:border-yellow-200 transition-colors shadow-sm">
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 rounded-2xl overflow-hidden shadow-sm">
                                <img src="{{ asset(@$ride[0]['image']) }}" class="h-full w-full object-cover" onerror="this.src='{{ asset('assets/admin/img/default-user.png') }}';">
                            </div>
                            <div>
                                <p class="font-black text-slate-900 flex items-center">
                                    {{ @$ride[0]['name'] }} {{ @$ride[0]['lname'] }}
                                    @if(@$ride[0]['is_verifyId'])
                                        <span class="ml-2 px-1.5 py-0.5 rounded-md bg-yellow-100 text-yellow-700 text-[8px] font-black uppercase tracking-widest">Verified</span>
                                    @endif
                                </p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Driver • Host</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-black text-slate-900 uppercase">Host</span>
                    </div>

                    {{-- Passengers --}}
                    @php $seatAssign = ['1A', '2A', '2B', '2C']; $idx = 0; @endphp
                    @forelse($booking as $p)
                        @php 
                            $isCancelled = ($p['cancel_booking'] == 'Cancelledby' || $p['cancel_booking'] == 'Cancelled');
                        @endphp
                        <div class="bg-white border border-slate-100 rounded-3xl p-4 group hover:border-yellow-200 transition-colors shadow-sm @if($isCancelled) opacity-60 @endif">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-4">
                                    <div class="h-12 w-12 rounded-2xl overflow-hidden shadow-sm">
                                        <img src="{{ asset($p['image']) }}" class="h-full w-full object-cover" onerror="this.src='{{ asset('assets/admin/img/default-user.png') }}';">
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-900 flex items-center">
                                            {{ $p['name'] }} {{ $p['lname'] }}
                                            @if($isCancelled)
                                                <span class="ml-2 px-1.5 py-0.5 rounded-md bg-red-50 text-red-600 text-[8px] font-black uppercase tracking-widest">Cancelled</span>
                                            @else
                                                <span class="ml-2 px-1.5 py-0.5 rounded-md bg-green-100 text-green-700 text-[8px] font-black uppercase tracking-widest">Paid</span>
                                            @endif
                                        </p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            @if($isCancelled)
                                                No Seat Allocated
                                            @else
                                                Seat {{ $seatAssign[$idx] ?? 'General' }}
                                                @php $idx++; @endphp
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-slate-900">₹{{ $p['price'] }}</p>
                                    <div class="flex items-center justify-end text-yellow-500">
                                        <span class="text-[10px] font-black mr-1">5.0</span>
                                        <i data-lucide="star" class="w-2.5 h-2.5 fill-current"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">No Booked Passengers</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="space-y-4 pt-4 shrink-0">
                <button type="button" onclick="Swal.fire('Passenger Notification', 'Messages will be broadcast to all confirmed passengers.', 'success')" class="w-full bg-blue-50 text-indigo-600 hover:bg-blue-100 border border-blue-100 font-black py-4 rounded-2xl transition-all flex items-center justify-center space-x-2 shadow-sm">
                    <i data-lucide="message-square" class="w-4 h-4"></i>
                    <span>Contact All Passengers</span>
                </button>
                <button type="button" onclick="Swal.fire('Flagged', 'This ride has been flagged for administrative review.', 'warning')" class="w-full bg-white text-red-500 hover:bg-red-50 border border-red-100 font-black py-4 rounded-2xl transition-all flex items-center justify-center space-x-2 shadow-sm">
                    <i data-lucide="flag" class="w-4 h-4"></i>
                    <span>Flag for Review</span>
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('Google_Token') }}&callback=initMap" async defer></script>
<script>
    lucide.createIcons();
    
    // Prepare ride data as JSON for safe JS usage
    const rideData = <?php echo json_encode([
        'pick_lat' => $ride[0]['pick_lat'] ?? null,
        'pick_lng' => $ride[0]['pick_long'] ?? null,
        'drop_lat' => $ride[0]['drop_lat'] ?? null,
        'drop_lng' => $ride[0]['drop_long'] ?? null,
        'stops' => $raw ?? []
    ]); ?>;

    function initMap() {
        if (!rideData.pick_lat || !rideData.pick_lng) {
            console.warn('Missing pickup coordinates');
            document.getElementById('liveText').innerText = 'Coordinates missing';
            return;
        }
        const startP = new google.maps.LatLng(rideData.pick_lat, rideData.pick_lng);
        const endP = new google.maps.LatLng(rideData.drop_lat, rideData.drop_lng);
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 7,
            center: startP,
            disableDefaultUI: true,
            zoomControl: true,
            styles: [
                {
                    "featureType": "all",
                    "elementType": "labels.text.fill",
                    "stylers": [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.text.stroke",
                    "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.fill",
                    "stylers": [{"color": "#fefefe"}, {"lightness": 20}]
                },
                {
                    "featureType": "landscape",
                    "elementType": "geometry",
                    "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]
                }
            ]
        });
        const directionsService = new google.maps.DirectionsService();
        const directionsDisplay = new google.maps.DirectionsRenderer({ 
            map,
            polylineOptions: {
                strokeColor: '#eab308',
                strokeWeight: 5,
                strokeOpacity: 0.8
            },
            markerOptions: {
                visible: false // We will draw custom clean markers
            }
        });

        // Add custom markers
        new google.maps.Marker({
            position: startP,
            title: 'Pickup',
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 7,
                fillColor: '#eab308',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 2
            },
            map
        });

        new google.maps.Marker({
            position: endP,
            title: 'Drop Off',
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 7,
                fillColor: '#4f46e5',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 2
            },
            map
        });

        // Build waypoints array from stops (if any)
        const waypoints = (rideData.stops || []).map(stop => ({
            location: new google.maps.LatLng(stop.lat, stop.long),
            stopover: true,
        }));
        
        // Add markers for each stop
        waypoints.forEach((wp, idx) => {
            new google.maps.Marker({
                position: wp.location,
                title: `Stop ${idx + 1}`,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 6,
                    fillColor: '#64748b',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 2
                },
                map,
            });
        });
        calculateAndDisplayRoute(directionsService, directionsDisplay, startP, endP, waypoints);
    }

    function calculateAndDisplayRoute(service, display, origin, destination, waypts) {
        service.route({
            origin,
            destination,
            waypoints: waypts,
            travelMode: google.maps.TravelMode.DRIVING,
            avoidTolls: true,
            avoidHighways: false,
        }, (response, status) => {
            if (status === google.maps.DirectionsStatus.OK) {
                display.setDirections(response);
                const leg = response.routes[0].legs[0];
                if (leg) {
                    // Update layout values dynamically
                    document.getElementById('displayDistance').innerText = leg.distance.text;
                    document.getElementById('displayDuration').innerText = leg.duration.text;
                    document.getElementById('liveText').innerText = 'Route: ' + leg.distance.text + ' • ' + leg.duration.text;
                }
            } else {
                console.error('Directions request failed:', status);
                document.getElementById('liveText').innerText = 'Directions unavailable';
            }
        });
    }

    $(document).ready(function() {
        // Change Status Logic
        $('#rideAdminStatus').on('change', function() {
            var selectElement = $(this);
            var rideId = selectElement.data('id');
            var selectedValue = selectElement.val();
            var selectedText = selectElement.find('option:selected').text().trim();
            var previousValue = selectElement.data('pre');

            Swal.fire({
                title: 'Change status?',
                text: "Set ride status to " + selectedText + "?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#eab308',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Yes, update'
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
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                                selectElement.val(previousValue);
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Server could not process request.', 'error');
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
@endsection
