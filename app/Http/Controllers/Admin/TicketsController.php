<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\User;
use App\Models\Ticket;
 use Illuminate\Support\Facades\Auth;

class TicketsController extends Controller
{
    function __construct()
	{

	}

	public function Tickets(Request $request)
	{
		 $user_type=Auth::user()->user_type;
		   // echo $id;
		   if($user_type=='admin')
		    {
         $data['tickets']=Ticket::join('users','users.id','=','tickets.user_id')
         ->select('users.name',
         	'users.lname',
         	'tickets.title',
         	'tickets.id',
         	'tickets.description',
         	'tickets.tickets_number',
         	'tickets.requestType',
         	'tickets.status',
         	'tickets.created_at')
         ->get()
         ->toArray();
        
		return view('admin.Tickets.index',$data);
	}
	 else
		  {
				  Auth::logout();
				  Session::flush();
				  return redirect('/admin/login')->with('logout','Logout successfully');
		  }
	}
	public function tiket_statusupdate($id)
	{
		 $user_type=Auth::user()->user_type;
		   // echo $id;
		   if($user_type=='admin')
		    {

		    	 echo "work in progress";
  //        $data['tickets']=Ticket::where('id','')
  //        ->select('users.name',
  //        	'users.lname',
  //        	'tickets.title',
  //        	'tickets.id',
  //        	'tickets.description',
  //        	'tickets.tickets_number',
  //        	'tickets.requestType',
  //        	'tickets.status',
  //        	'tickets.created_at')
  //        ->get()
  //        ->toArray();
        
		// return view('admin.Tickets.index',$data);
	}
	 else
		  {
				  Auth::logout();
				  Session::flush();
				  return redirect('/admin/login')->with('logout','Logout successfully');
		  }
	}

	public function ticket_status(Request $request)
	{
		$user_type=Auth::user()->user_type;
		   // echo $id;
		   if($user_type=='admin')
		    {
               // $status=$request->status;
               $id=$request->id;
               $updateARR=array('status'=>$request->status);
               $resp=Ticket::where('id',$id)->update($updateARR);
               if($resp)
               {
                    return redirect()->back()->with('success', 'Successfully Update');
 
               }
               else
               {
               	return redirect()->back()->with('success', 'Something else wrong please try again');

               }   
	   }
	   else
		{
		     Auth::logout();
		    Session::flush();
		    return redirect('/admin/login')->with('logout','Logout successfully');
		}
	}
	public function ticketchat($id)
	{


		$user_type=Auth::user()->user_type;
		   // echo $id;
		   if($user_type=='admin')
		    {
                  $data['tickets']=Ticket::select('user_id','tickets_number','requestType','status','title','id')->where('id',$id)->first()->toArray(); 
            	  return view('admin.Tickets.ticket_chat',$data);
	        }
	     else
		 {
		     Auth::logout();
		    Session::flush();
		    return redirect('/admin/login')->with('logout','Logout successfully');
		 }

	}

	

}
