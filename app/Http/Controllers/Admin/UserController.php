<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Verify_id;
use App\Models\Postal_address;
use App\Models\Token;
use App\Models\Notifications;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Image; 
use Storage;
use Session;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    function __construct()
	{
		$this->middleware('auth');
		// $this->middleware('permission:user-list', ['only' => ['index','store']]);
		// $this->middleware('permission:user-create', ['only' => ['create','store']]);
		// $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
		// $this->middleware('permission:user-delete', ['only' => ['destroy']]);
		// $this->middleware('permission:profile-index', ['only' => ['profile','profile_update']]);
	}



  public function sendFCMViaJson($deviceToken, $title, $body, $image,$data = [])
    {
        $jsonKeyFile =config_path('notification.json');

        if (!file_exists($jsonKeyFile)) {
            return ['error' => 'Service account file not found.'];
        }



        // Load the service account details
        $json = json_decode(file_get_contents($jsonKeyFile), true);
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];

        $now = time();
        $claim = [
            'iss' => $json['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        // Encode header and claim
        $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $base64UrlPayload = rtrim(strtr(base64_encode(json_encode($claim)), '+/', '-_'), '=');
        $signatureInput = $base64UrlHeader . "." . $base64UrlPayload;

        // Sign JWT using service account private key
        openssl_sign($signatureInput, $signature, $json['private_key'], 'sha256');
        $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        $jwt = $signatureInput . "." . $base64UrlSignature;

        // Get access token using JWT
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://oauth2.googleapis.com/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]),
        ]);

        $response = curl_exec($ch);
        curl_close($ch);
        $tokenData = json_decode($response, true);
        if (!isset($tokenData['access_token'])) {
            return ['error' => 'Failed to get access token', 'response' => $tokenData];
        }

        $accessToken = $tokenData['access_token'];
        $projectId = $json['project_id'];

        // Prepare message
        $message = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'image'=>$image
                ],
                'data' => $data,
            ],
        ];

        // Send notification
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($message),
        ]);


        $result = curl_exec($ch);

        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ['status' => $httpStatus, 'response' => json_decode($result, true)];
    }


// ///////


	public function index(Request $request)
	{

		  $user_type=Auth::user()->user_type;
		   // echo $id;
		   if($user_type=='admin')
		    {
                $data['users']=User::select('*')->where('user_type','!=','admin')->orderby('id','desc')->get()->toArray();

                return view('admin.users.index',$data);
		    }
		  else
		  {
				  Auth::logout();
				  Session::flush();
				  return redirect('/admin/login')->with('logout','Logout successfully');
		  }
	}


    public function store(Request $request){

            $validated = $request->validate([
              'mobile' => 'required|unique:users',
              'name' => 'required',
              'lname' => 'required',
            ]);

          
            try{

                $data= new User();
                $data->name= $request->name;
                $data->lname= $request->lname;
                $data->mobile= $request->mobile;
                $data->save();
              return redirect()->back()->with('success','User Created Successfully');

            }
            catch (Exception $e){

              // return redirect()->back()->with('success', $e->getMessage());
              return response()->json(['error'=> $e->getMessage()]);
            }
  

    }
	public function user_status(Request $request)
	{
		$id=$request->id;
		$status=$request->status==1 ? 0 : 1;
      
         $userArr=array('status'=>$status);
	     	$res=User::where('id',$id)->update($userArr);
        if($res)
        {
           echo 1;
        }
        else
        {
           echo 0;
        }
	}

	public function user_info(Request $request)
	{
       $data['verify_id']=Verify_id::select('*')->where('user_id',$request->id)->get()->toArray();
       $data['Postal_address']=Postal_address::select('*')->where('userid',$request->id)->get()->toArray();
       $data['meta']=User::select('*')->where('id',$request->id)->first()->toArray();
      
       return view('admin.users.userinfo_single',$data);

	}
    public function PendingRequest(Request $request)
    {
      

        $users=User::where('offer_ride_status',1)->get();
      
        $title='Offer Ride Access Request ';
        return view('admin.users.indexpending_req', [
            'users' => $users,
            'title'=>$title

        ]);
    }

    public function doucmentVerfiyRequest(Request $request)
{
   

    $ids = Verify_id::where(function ($query) {
        $query->where('id_proff_status', 1)
                  ->orWhere('driving_licence_status', 1)
                  ->orWhere('vehicle_plate_status', 1)
                  ->orWhere('insurance_status', 1)
                  ->orWhere('vehicle_rc_status', 1)
                  ->orWhere('fitness_certificate_status', 1)
                  ->orWhere('tourist_permit_status', 1)
                  ->orWhere('driving_licence_tr_status', 1)
                  ->orWhere('puc_status', 1);
        })
        ->pluck('user_id');



    $users = User::whereIn('id', $ids)
        ->get();

    $title = 'Document Verification Request';

    return view('admin.users.indexpending_req', compact('users', 'title'));

}



	public function updateuserstatus(Request $request)
	{      
		    
     $receiver_id=(string) $request->uid;
      $title="Offer Rides Request";

      
     
        // $update=array('is_verifyId'=>$request->status);
        // $res= User::where('id',$request->uid)->update($update);
        //  $res1=Verify_id::select('id_proff_status','driving_licence_status','vehicle_plate_status','insurance_status')->where('user_id',$request->uid)->get();

         
        //  if(count($res1)>0)
        //  {
        //      $update_Arr=array('id_proff_status'=>$request->status,'driving_licence_status'=>$request->status,'vehicle_plate_status'=>$request->status,'insurance_status'=>$request->status);

        //      Verify_id::where('user_id',$request->uid)->update($update_Arr);
 
        //  }
        //   if($request->status==1)
        //   {
        //     $dsc="Your documents has been declined, Re-upload them";
        //   }
        //   elseif($request->status==2)
        //   {
        //      $dsc="Thank you for submited documents, your account is verified and you can start driving with us !";
        //   }

         $update = ['offer_ride_status' => $request->status];
          $res = User::where('id', $request->uid)->update($update);

              
            

           if ($res) {


          if ($request->status == 3) {
              $desc = "Your request to offer a ride in our app has been rejected by the admin. Please contact support for more details.";
          } elseif ($request->status == 2) {
              $desc = "Congratulations! Your request to offer a ride in our app has been approved. You can now start driving with us.";
          }

                $arrayNames = array('user_id' => $receiver_id, 'title' => $title, 'description' => $desc);
                  $getting_token_info = Token::select('*')->where('user_id', $receiver_id)->get()->toArray();
                  $user = User::select('*')->where('id', $receiver_id)->first();

                  if (!empty($getting_token_info) && count($getting_token_info) > 0) {
                    foreach ($getting_token_info as $key => $valuedata) {
                      $deviceToken = $valuedata['token'];
                      $data = (object) array('type' => 'home', 'chatId' =>'test');
                    
                      $response = $this->sendFCMViaJson(
                        $deviceToken,
                        $title,
                        $desc,
                        $user->image,
                        $data
                    );
                    }

                }
            
              return response()->json([
                  'status' => 'success',
                  'message' => ' status updated successfully.'
              ]);
          } else {

             return response()->json([
                  'status' => 'error',
                  'message' => 'Error Updating status.'
              ]);

          }
      

	}
	public function completeRequest(Request $request)
	{
		 $data['users']=User::select('*')->where('is_verifyId',2)->where('user_type','!=','admin',)->orderby('id','desc')->get()->toArray();
      return view('admin.users.indexcomplete_req',$data);
	}

  public function driver(Request $request){

     $Ids=Verify_id::pluck('user_id');
     $data['users']=User::select('*')->whereIn('id',$Ids)->where('user_type','!=','admin',)->orderby('id','desc')->get()->toArray();
      return view('admin.users.index',$data);
  }
	public function chat($id)
	{
        
       $user_type='';
        $user_type=Auth::user()->user_type;
           // echo $id;
           if($user_type=='admin')
            {
                $data['id']=$id;
         $userdata=User::select('*')->where('id',$id)->get()->toArray();
         $data['userdata']=$userdata[0];
         $data['mata']=User::select('image','name','lname')->first()->toArray();

        return view('admin.users.chat',$data);
            }
          else
          {
                  Auth::logout();
                  Session::flush();
                  return redirect('/admin/login')->with('logout','Login');
          }


	}
	public function chatimg(Request $request)
    {   
        if($files = $request->file('file')){  
            $name = time() . '_' . $files->getClientOriginalName();  
            
            // Move the file to the physical location on the disk
            $files->move(public_path('image/caco_media'), $name);  
            
            // SAVE THIS TO DATABASE: Just the path relative to the public folder
            return 'image/caco_media/' . $name;
        }
}
    public function sendnotification1($userid=null,$desc=null,$type1=null)
            {
                // echo 'chek';
                  $userid=$userid;
                  $receiver_iddata=User::select('name','lname')->where('id',$userid)->first();


                    $checkArr=User::select('ride_notification','messages_notification','news_deals_stuff_notification')->where('id',$userid)->first();

                    if($checkArr->ride_notification==0)
                    {
                    return false;
                    } 

                if($checkArr->messages_notification==0)
                {
                return false;
                }

                if($checkArr->news_deals_stuff_notification==0)
                {
                return false;
                }

              $desc=$receiver_iddata['name'].' '.$receiver_iddata['lname'].' '.$desc;
              $title='Support';
              // $gettokn_Arr=array('user_id'=>$request->userid);

                $arrayNames = array('user_id' =>$userid,'title'=>$title,'description'=>$desc);
               
                $getting_token_info=Token::select('*')->where('user_id',$userid)->get()->toArray();

                if(!empty($getting_token_info) && count($getting_token_info)>0)
                {
                $API_SERVER_KEY="AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
                foreach ($getting_token_info as $key => $valuedata) {
                $registrationIds=$valuedata['token'];
                 $data=(object)array('type'=>$type1,'id'=>13,'name'=>'Caco','image'=>'Caco');

                $msg = array
                (
                'body'  => $desc,
                'title' => $title,
                'icon'  => 'myicon',/*Default Icon*/
                'sound' => 'mySound'/*Default sound*/
                );
                $fields = array
                (
                'to'        => $registrationIds,
                'notification'  => $msg,
                'data'=>$data

                );
                $headers = array
                (
                'Authorization: key=' . $API_SERVER_KEY,
                'Content-Type: application/json'
                );
                
                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                $result[] = curl_exec($ch );
                curl_close( $ch );
                }
                if(!empty($result))
                {
                $arrayName = true;
                }
                else
                {
                $arrayName = false;
                }
                }
                else
                {
                $arrayName = false;
                }
                  $response=['status'=>'success','message'=>'test ','rss'=>$arrayName]; 
                return response()->json($response);


    }




	public function sendnotification(Request $request)
        {
             // echo 'chek';
              $userid=$request->uid;
              $desc=$request->sms;
              $senderdata=User::where('id',$request->adminId)->select('id', 'name', 'lname', 'image')->first();
               $checkArr=User::select('ride_notification','messages_notification','news_deals_stuff_notification')->where('id',$userid)->first();

                if($checkArr->ride_notification==0)
                {
                return false;
                } 

                if($checkArr->messages_notification==0)
                {
                return false;
                }

                if($checkArr->news_deals_stuff_notification==0)
                {
                return false;
                }

                  $title='Support';
              
                $arrayNames = array('user_id' =>$userid,'title'=>$title,'description'=>$desc);
                 $getting_token_info=Token::select('*')->where('user_id',$userid)->get()->toArray();

                 $response=[];
                if(!empty($getting_token_info) && count($getting_token_info)>0)
                {

                    $sender=(string) $senderdata->id;

                  foreach ($getting_token_info as $key => $valuedata) {
                    $deviceToken = $valuedata['token'];
                    $data = (object) array('type' => 'chat', 'chatId' => $sender, 'image' => $senderdata->image, 'name' => $title);
            
                    $image='';
                    if($request->type == 'image'){
                      $image=$desc;
                      $desc = 'You received an image'; // or whatever message you want
                     
                    }
                    $response = $this->sendFCMViaJson(
                      $deviceToken,
                      $title,
                      $desc,
                      $image,
                      $data
                  );
                  }
                }
                
                  $response=['status'=>'success','message'=>'test ','rss'=>$response,'type'=>$request->type]; 
                return response()->json($response);


    }

    // public function update_Verify_doc(Request $request)
    // {

    //    echo"<pre>";print_r($request->all());die;
    //   $no=$request->doc_status;
     
                 
    //   if($request->status_col=='id_proff_status')
    //   {
    //     $dd='Id_proof';
    //     $sss="Id_proof";
    //   }
    //    if($request->status_col=='driving_licence_status')
    //   {
    //     $dd='driving licence';
    //      $sss="driving_licence";
        
    //   } 
    //    if($request->status_col=='vehicle_plate_status')
    //   {
    //     $dd='Marticula';
    //     $sss="vehicle_plate";
       
    //   } 
    //    if($request->status_col=='insurance_status')
    //   {
    //     $dd='Insurance';
    //      $sss="insurance";
    //   }  


    //   $desc='';
    //   if($no==2)
    //   {
    //      $desc="Your ".$dd." document has been approved";
    //   }
    //   else
    //   { 
    //    $desc="Your ".$dd." document has been declined, Re-upload them";
    //   }

    //   if($no==1)
    //   {
    //     $updateArr=array($request->status_col=>$no,$sss=>Null);
    //   $res=Verify_id::where('user_id',$request->uid)->update($updateArr);  
    //   }
    //   elseif($no==2)
    //   {
    //     $updateArr=array($request->status_col=>$no);
    //      $res=Verify_id::where('user_id',$request->uid)->update($updateArr); 
    //   }
     

    //     $udata=Verify_id::select('*')->where('user_id',$request->uid)->first();
    //    if($udata->id_proff_status==2 && $udata->driving_licence_status==2 && $udata->vehicle_plate_status==2 && $udata->insurance_status==2)
    //    {
    //       $updateDD=array('is_verifyId'=>2);
    //       User::where('id',$request->uid)->update($updateDD);
    //       $this->sendnotification1($request->uid,'Thank you for submited documents, your account is verified and you can start driving with us !','Verifyid');

    //    }
    //    else
    //    {
    //        $updateDD=array('is_verifyId'=>1);
    //       User::where('id',$request->uid)->update($updateDD);
    //    }
                                            
    //   if($res)
    //   {
    //        $this->sendnotification1($request->uid, $desc,'Verifyid');

    //        return response()->json(['status'=>'success','message'=>'Docuement is Approved']);
    //       // echo 1;
    //   } 
    //   else
    //   {
    //        return response()->json(['status'=>'error','message'=>'Error Approving Docuement. Please try again after some time']);

    //       // echo 0;
    //   }

    // }

   
   

    public function update_Verify_doc(Request $request)
        {
            $docStatus = (int) $request->doc_status;
            $statusCol = $request->status_col; 
            $docCol = $request->key_col;   
            $userId = $request->uid;

            $docLabel = ucwords(str_replace('_', ' ', $docCol)); 

            $desc = $docStatus === 2
                ? "Your {$docLabel} document has been approved"
                : "Your {$docLabel} document has been declined, please re-upload it.";

          $updateData = [$statusCol => $docStatus];

          if ($docStatus === 1) {
              $updateData[$docCol] = null;
          }

          $res = Verify_id::where('user_id', $userId)->update($updateData);

          $userDoc = Verify_id::select('id_proff_status','driving_licence_status',
                      'vehicle_plate_status','insurance_status','vehicle_rc_status',
                      'fitness_certificate_status','tourist_permit_status','driving_licence_tr_status'
                      ,'puc_status')->where('user_id', $userId)->first();

          $allApproved = collect($userDoc->toArray())
              ->filter(function ($value, $key) {
                  return str_ends_with($key, '_status');
              })
              ->every(function ($status) {
                  return $status === 2;
              });

          User::where('id', $userId)->update([
              'is_verifyId' => $allApproved ? 2 : 1
          ]);
 
          $type='Verifyid';
          if ($res) {

                 $getting_token_info = Token::select('*')->where('user_id', $userId)->get()->toArray();
                  $user = User::select('*')->where('id', $userId)->first();

                  if (!empty($getting_token_info) && count($getting_token_info) > 0) {
                    foreach ($getting_token_info as $key => $valuedata) {
                      $deviceToken = $valuedata['token'];
                      $data = (object) array('type' => 'Verifyid', 'chatId' =>'test');
                      $title='Document Approved !';
                      $response = $this->sendFCMViaJson(
                        $deviceToken,
                        $title,
                        $desc,
                        $user->image,
                        $data
                    );
                    }

                }
            
              // $this->sendnotification($userId, $desc, type);  ///(dec)
              return response()->json([
                  'status' => 'success',
                  'message' => 'Document status updated successfully.'
              ]);
          } else {
              return response()->json([
                  'status' => 'error',
                  'message' => 'Failed to update document. Please try again later.'
              ]);
          }
      }

    public function update_date(Request $request)
    {

        $request->uid;
        $request->col;
        $request->date;

        $updateDate=array($request->col=>$request->date);
        $res=Verify_id::where('user_id',$request->uid)->update($updateDate);
        if($res)
        {
           echo 1;
        }
        else
        {
          echo 0;
        }
    }
    public function clear_all(Request $request)
    {
        $update_seen=array('seen'=>1);
        $res=Notifications::where('seen',0)->update($update_seen);
        if($res)
        {
            echo 1;

        }
        else
        {
            echo 0;
        }
    }
    public function delete_userr(Request $request)
    {
       $delis=$request->id;
       $res=User::where('id',$delis)->delete();
       if($res)
       {
        echo 1;

       }
       else
       {
        echo 0;
       }
    }

    public function chats()
    {

         $user_type='';
        $user_type=Auth::user()->user_type;
           // echo $id;
           if($user_type=='admin')
            {
                // $data['id']=$id;
          $data['chatlist']=User::select('*')->where('user_type','!=','admin')->get()->toArray();
         // $data['userdata']=$userdata[0];
         // $data['mata']=User::select('image','name','lname')->first()->toArray();

        return view('admin.users.chats',$data);
            }
          else
          {
                  Auth::logout();
                  Session::flush();
                  return redirect('/admin/login')->with('logout','Login');
          }


    }

       public function profile(Request $request){


        return view('admin.users.profile');

       }


       public function profile_update(Request $request, $id)
        {
            $user = User::findOrFail($id);

    // Validate the incoming data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        // 'password' => 'nullable|min:8|same:confirm-password',
    ]);

    // Update Name and Email
    $user->name = $request->name;
    $user->email = $request->email;

    // Only update password if the user actually typed one in
   

    $user->save();

    return back()->with('success', 'Profile updated successfully!');

          $rules = [
                  'password' 	=> 'required|string|min:6|same:confirm-password',
              ];

              $messages = [
                  'password.required'    	=> __('default.form.validation.password.required'),
                  'password.same'    		=> __('default.form.validation.password.same'),
              ];

              $this->validate($request, $rules, $messages);
          $input = $request->all();
          $input['password'] = Hash::make($input['password']);

          try {
            $user = User::whereId($id)->update([
              'password' => $input['password']
            ]);

            // Toastr::success(__('Password Updated Successfully!'));
              return redirect()->route('admin_profile')->with('success','Password Updated Successfully!');
          } catch (Exception $e) {
            // Toastr::success(__('Error Updating Password'));
              return redirect()->route('admin_profile')->with('error','Error Updating Password!');
          }	
        }


        public function update_document(Request $request)
        {

          $request->validate([
                'doc_key' => 'required|string',
                'user_id' => 'required|integer',
                'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
                'expiry_date' => 'nullable|date'
            ]);

           
           $user = Verify_id::firstOrCreate(['user_id' => $request->user_id]);
            
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $filename = time().'_'.$file->getClientOriginalName();

                $file->move(public_path('image/caco_media'), $filename);

                // Save relative path in DB
                $user->{$request->doc_key} ='image/caco_media/'. $filename;
            }


            if($request->expiry_date) {
                $expKey = $request->doc_key . "_expdate";
                $user->{$expKey} = $request->expiry_date;
            }

            $user->save();

            return response()->json(['status' => true, 'msg' => 'Updated successfully']);
        }
}
