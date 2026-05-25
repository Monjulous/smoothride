<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use DataTables;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    function __construct()
	{
		
        
	}

    public function Vehiclelist(Request $request)
    {      
           $user_type=Auth::user()->user_type;
            if($user_type=='admin')
            {
              $data['vehicles']=Vehicle::join('users','users.id','=','vehicles.user_id')
                      ->select('users.id as user_id','users.name','users.lname','vehicles.plate_number','vehicles.vehicle_brand','vehicles.country','vehicles.vehicle_model','vehicles.vechicle_color','vehicles.vechicle_madeyear','vehicles.status','vehicles.created_at')
                      ->get()
                      ->toArray();

                      return view('admin.vehicle.index',$data);
             
             
            }
            else
            {
            Auth::logout();
            Session::flush();
            return redirect('/admin/login')->with('logout','Logout successfully');
            }
       
    }

   

     

  



}
