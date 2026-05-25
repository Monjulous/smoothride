<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use DataTables;
use App\Models\Rating_reviews;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class RatingReviewsController extends Controller
{
    function __construct()
	{
		
	}

	public function ratingReviews(Request $request)
	{
		 	$user_type=Auth::user()->user_type;
            if($user_type=='admin')
            {
              $data['rating']=Rating_reviews::join('users','users.id','=','rating_reviews.receiver_id')
                            // ->join('users','users.id','=','rating_reviews.sender_id')
                      ->select('users.name','users.lname','rating_reviews.rating','rating_reviews.reviews','rating_reviews.created_at')
                      ->get()
                      ->toArray();
                     return view('admin.Rating_reviews.index',$data); 
            }
            else
            {

            Auth::logout();
            Session::flush();
            return redirect('/admin/login')->with('logout','Logout successfully');
            }
	}

	
}
