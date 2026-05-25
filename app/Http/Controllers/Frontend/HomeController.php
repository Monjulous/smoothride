<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Card;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Session;


use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\PrivacyPolicy;
use App\Models\Contact;
use App\Models\HomePage;
use App\Models\Testimonial;
use App\Models\service;

use App\Models\CMSdata;
class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('web');
    }

    public function pagename(Request $request)
    {
        $val=$request->val;
        // die();
        if($val=='tnc')
        {
        return view('apppage.tnc');
        }
        elseif($val=='privacy')
        {
          return view('apppage.privacy_policy');

        }
    }

    // public function userlogin()
    // {
    //     return view('frontend.pages.login');
    // }

    // public function signup(){
    //     return view('frontend.pages.signup');
    // }
    
    // public function addUser(Request $request){
    //     $input = $request->all();
    //     $validate=$request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users|max:255',
    //         'mobile' => 'required|min:10',
    //         'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
    //         'password_confirmation' => 'min:6'
    //     ]);
    //     $input['password'] = bcrypt($input['password']);
    //     $input['user_type'] = '2';
    //     $input['status'] = 1;
    //     User::create($input);
    //     return back()->with('success','Successfully registered a new user!');
    // }

    // public function logins(Request $request){
    //     $input = $request->all();
    //     $validate=$request->validate([
    //         'email' => 'required|email|max:255',
    //         'password' => 'min:6',
    //     ]);
    //     $fire= array('email' => $input['email']);
    //     $result = User::where($fire)->first();
    //     if(isset($result) && !empty($result)){
    //         $user = $result->toArray();
    //         if(Hash::check($input['password'], $user['password'])) {
    //             session()->put('eastKey', $user['id']);
    //             session()->put('eastname', $user['name']);
    //             session()->put('eastemail', $user['email']);
    //             return redirect('/myprofile');
    //         } else {
    //             return back()->with('errors','Your Password mismatch');
    //         }
    //     }else{
    //         return back()->with('errors','Invalid User');
    //     }
    // }




	public function homeindex(){ 
           
        $data['homedetail'] = HomePage::where('id',1)->first();

        $data[ 'banner' ] = CMSdata::where( 'section', 'banner' )->first();
        $data[ 'info' ] = CMSdata::where( 'section', 'info' )->first();
        $data[ 'howitwork' ] = CMSdata::where( 'section', 'howitwork' )->first();
        $data[ 'whychooseus' ] = CMSdata::where( 'section', 'whychooseus' )->first();
        $data[ 'testimonial' ] = CMSdata::where( 'section', 'testimonial' )->first();
        $data[ 'calltoaction' ] = CMSdata::where( 'section', 'calltoaction' )->first();
        $data[ 'testimonial_data' ] = Testimonial::get();

        return view('frontendWeb.index',$data);
    
        }


        public function about(){ 
           
            $data[ 'about' ] = CMSdata::where( 'section', 'about' )->first();
           
            return view('frontendWeb.about',$data);
        
            }

            public function services(){ 
           
                $data[ 'service' ] = CMSdata::where( 'section', 'service' )->first();
                $data[ 'service_list' ] = service::get();
               
                return view('frontendWeb.services',$data);
            
                }
    
        public function termscondition(){
    
        $data['terms'] = PrivacyPolicy::where('id',2)->first();
        return view('frontendWeb.terms_condition',$data);
        
        }
    
        public function privacy_policy(){
    
        $data['privacy'] = PrivacyPolicy::where('id',1)->first();
        return view('frontendWeb.privacy_policy',$data);
        
        }
    
    
        public function contact_us(){
    
        $data['contact_data'] = HomePage::where('id',1)->first();
        return view('frontendWeb.contact',$data);
        
        }
    
    
        public function store_contact(Request $request){
    
        $contact = new Contact;
    
        $contact->name = $request->input('name');
        $contact->email = $request->input('email');
        $contact->subject = $request->input('subject');
        $contact->message = $request->input('message');
    
        $contact->save();
        return redirect()->back()->with('success','Message Sent Successfully!! We will contact you shortly');
        }
    
}
