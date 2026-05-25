<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Ride;
use App\Models\Apply_ride;
use App\Models\User;
use App\Models\Faq;


class RideController extends Controller {
    function __construct() {

    }

    public function rideList( Request $request ) {
        $data['rides'] = Ride::join('users', 'users.id', '=', 'add_rides.userid')
            ->select(
                'users.name',
                'users.lname',
                'add_rides.total_passenger',
                'users.email',
                'add_rides.id',
                'add_rides.rideid',
                'add_rides.pick_location',
                'add_rides.drop_location',
                'add_rides.stoppage',
                'add_rides.date',
                'add_rides.time',
                'add_rides.passenger_count',
                'add_rides.small_bag',
                'add_rides.hand_bag',
                'add_rides.regular_bag',
                'add_rides.oversize_bag',
                'add_rides.price',
                'add_rides.complete_status',
                'add_rides.admin_status',
                'add_rides.created_at',
                'add_rides.instruction'
            )
            ->orderBy('add_rides.id', 'desc')
            ->get()
            ->toArray();

        $data['rr'] = Ride::get()->toArray();
        return view( 'admin.Ride.index', $data );
    }
    
    public function index(Request $request)
    {
        $data['rides'] = Ride::join('users', 'users.id', '=', 'add_rides.userid')
            ->select(
                'users.name',
                'users.lname',
                'add_rides.total_passenger',
                'users.email',
                'add_rides.id',
                'add_rides.rideid',
                'add_rides.pick_location',
                'add_rides.drop_location',
                'add_rides.stoppage',
                'add_rides.date',
                'add_rides.time',
                'add_rides.passenger_count',
                'add_rides.small_bag',
                'add_rides.hand_bag',
                'add_rides.regular_bag',
                'add_rides.oversize_bag',
                'add_rides.price',
                'add_rides.complete_status',
                'add_rides.admin_status',
                'add_rides.created_at',
                'add_rides.instruction'
            )
            ->orderBy('add_rides.id', 'desc')
            ->get()
            ->toArray();

        $data['status'] = '';
        return view('admin.Ride.index', $data);
    }
    public function update_ride_admin_status(Request $request)
{
    // 1. Find the record
    $ride = \App\Models\Ride::find($request->id); 
    
    if ($ride) {
        // 2. Update the status
        $ride->admin_status = $request->status;
        $ride->save();

        // 3. RETURN THIS EXACT JSON
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Ride not found.'
    ], 404);
}

    public function Booking() {

        $data[ 'booking_ride' ] = Apply_ride::select( 'add_rides.id',
        'add_rides.pick_location',
        'add_rides.drop_location',
        'add_rides.drop_lat',
        'add_rides.drop_long',
        'add_rides.pick_lat',
        'add_rides.pick_long',
        'add_rides.date',
        'add_rides.time',
        'add_rides.passenger_count',
        'add_rides.total_passenger',
        'add_rides.stoppage',
        'add_rides.small_bag',
        'add_rides.hand_bag',
        'add_rides.regular_bag',
        'add_rides.oversize_bag',
        'add_rides.price',
        'add_rides.instruction',
        'add_rides.status' )
        ->join('add_rides', 'add_rides.id', '=', 'apply_ride.ride_id')
            ->orderBy('add_rides.id', 'DESC')
            ->groupBy('add_rides.id')
            ->get()
            ->toArray();

        return view( 'admin.Ride.booking', $data );
    }

    public function cancel_booking() {
        return view( 'admin.Ride.cancelbooking' );

    }

    public function viewapplyedusers( $id ) {

        $data[ 'applyride' ] = Apply_ride::select( '*' )->where( 'ride_id', $id )->get()->toArray();

        return view( 'admin.Ride.applyride', $data );

    }

    public function single_ride( $id ) {

        // echo $id;

        $data[ 'ride' ] = Ride::select( 'users.name',
        'add_rides.id',
        'users.lname',
        'users.mobile',
        'users.email',
        'users.is_verifyEmail',
        'users.is_verifyId',
        'users.is_verifyNumber',
        'users.image',
        'add_rides.userid',
        'add_rides.vehicle_id',
        'add_rides.rideid',
        'add_rides.pick_location',
        'add_rides.drop_location',
        'add_rides.drop_lat',
        'add_rides.drop_long',
        'add_rides.pick_lat',
        'add_rides.pick_long',
        'add_rides.date',
        'add_rides.time',
        'add_rides.passenger_count',
        'add_rides.total_passenger',
        'add_rides.stoppage',
        'add_rides.small_bag',
        'add_rides.hand_bag',
        'add_rides.regular_bag',
        'add_rides.oversize_bag',
        'add_rides.price',
        'add_rides.instruction',
        'add_rides.smoking_allowed',
        'add_rides.pets_allowed',
        'add_rides.backsheet',
        'add_rides.music',
        'add_rides.verified_profile',
        'add_rides.ac_allow',
        'add_rides.food_allow',
        'add_rides.status',
        'add_rides.delete_status',
        'add_rides.instant_booking',
        'add_rides.complete_status',
        'add_rides.created_at' )
        ->join('users', 'users.id', '=', 'add_rides.userid')
            ->where('add_rides.id', $id)
            ->get()
            ->toArray();
        // foreach ( $data[ 'ride' ] as $key => $value ) {
        $data[ 'booking' ] = Apply_ride::select( 'users.name',
        'users.lname',
        'users.image',
        'add_rides.pick_location',
        'apply_ride.cancel_booking',
        'apply_ride.price',
        'apply_ride.confirm_book',
        'apply_ride.instant_status',
        'add_rides.drop_location' )
        ->join( 'add_rides', 'add_rides.id', '=', 'apply_ride.ride_id' )
        ->join( 'users', 'users.id', '=', 'apply_ride.passenger_id' )
        ->where( 'apply_ride.ride_id', $id )
        ->get()
        ->toArray();

        // }

        // echo '<pre>';
        // print_r( $data );

        return view( 'admin.Ride.single_ride', $data );
    }

    public function faq() {
        $data[ 'faq' ] = Faq::select( '*' )->orderBy( 'id', 'DESC' )->get()->toArray();
        return view( 'admin.Ride.faq', $data );
    }

    public function addfaq() {
        return view( 'admin.Ride.add_faq' );
    }

    public function submit_faq( Request $request ) {

        $Faq_arr = array( 'questions'=>$request->que,
        'answers'=>$request->ans,
        'created_at'=>date( 'Y-m-d H:i:s' ) );

        $res = Faq::create( $Faq_arr );
        if ( $res ) {
            return redirect()->route( 'faq' )->with( 'success', 'FAQ Added Successfully.' );

        } else {
            return redirect()->route( 'faq' )->with( 'success', 'Something else wrong please try again ' );

        }

    }

    public function editfaq(Request $request){
        $id=$request->id;
        try {
            $faq = Faq::findOrFail($id);
    
            $faq->update([
                'questions'   => $request->input('questions'),
                'answers'     => $request->input('answers'),
            ]);
    
            return redirect()->route('faq')->with('success', 'FAQ updated successfully.');
    
        } catch (\Exception $e) {
            return redirect()->route('faq')->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function delete_Faq( Request $request ) {

        $res = Faq::where( 'id', $request->id )->delete();
        if ( $res ) {
            return back()->with( 'success', 'Deleted Successfully' );

        } else {
            return back()->with( 'success', 'Something else wrong please try again ' );
        }
    }

}
