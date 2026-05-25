<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verify_id;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use DataTables;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class Verify_idController extends Controller
{
    function __construct()
    {
        
        
    }

    public function verify_id(Request $request)
    {      
           $user_type=Auth::user()->user_type;
            if($user_type=='admin')
            {
                // join('users','users.id','=','verify_id.user_id')
              $data['verify_id']=Verify_id::select('*')
                      ->get()
                      ->toArray();

                      return view('admin.verifyID.index',$data);
            }
            else
            {
            Auth::logout();
            Session::flush();
            return redirect('/admin/login')->with('logout','Logout successfully');
            }
       
    }

   

     

  



}
