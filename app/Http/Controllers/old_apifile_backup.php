<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Vehicle;
use App\Models\Verify_id;
use App\Models\User;
use App\Models\Ride;
use App\Models\Apply_ride;
use App\Models\RecentHistory;
use App\Models\Color;
use App\Models\Car_brand;
use App\Models\Car_model;
use App\Models\Postal_address;
use App\Models\Rating_reviews;
use App\Models\Imported_brand;
use App\Models\Stop;
use App\Models\Faq;
use App\Models\Token;
use App\Models\Notifications;
use App\Models\Alert_notification;
Use Exception;
// use Kreait\Firebase\Factory;
// use Kreait\Firebase\ServiceAccount;
// use Kreait\Firebase\Database;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

use Session;





class apicontroller extends Controller
{
    //

 public function __construct()
    {
        date_default_timezone_set("America/Santo_Domingo");
        // date_default_timezone_set('Asia/Kolkata');
       


        // Load Firebase service account credentials
       

    }
    // public function test(Request $request)
    // {
    //     $info = array(
    //         'name' => "Alex"
    //     );
    //     Mail::send(['text' => 'mail'], $info, function ($message)
    //     {
    //         $message->to('sunil.digittrix@gmail.com', 'Sunil')
    //             ->subject('Basic test eMail from W3schools.');
    //         $message->from('superconnectbiz@gmail.com', 'superconnectbiz@gmail.com');
    //     });
    //     echo "Successfully sent the email";
    // }
        

        // *************************************************************************************************************************************************************

         public function commonErrorCodes($code)
         {
            $commonErrorCodes = array(
            '00000' => 'Success',
            '01004' => 'String data, right-truncated',
            '21000' => 'Cardinality violation',
            '22001' => 'String data, right-truncated',
            '22002' => 'Null value in a field not allowed',
            '22003' => 'Numeric value out of range',
            '22007' => 'Invalid datetime format',
            '22008' => 'Datetime field overflow',
            '22012' => 'Division by zero',
            '23000' => 'Integrity constraint violation',
            '23001' => 'Restrict violation - parent key not found',
            '23002' => 'Restrict violation - child records exist',
            '23003' => 'Restrict violation - interdependency',
            '23004' => 'Integrity constraint violation - parent key not found',
            '23005' => 'Integrity constraint violation - child key violation',
            '23006' => 'Integrity constraint violation - duplicate key',
            '42000' => 'Syntax error or access violation',
            '42S02' => 'Base table or view not found',
            '42S22' => 'Column not found',
            'HY000' => 'General error',
            'HY001' => 'Memory allocation error',
            'HY008' => 'Operation canceled',
            'HY009' => 'Invalid use of null pointer',
            'HY010' => 'Function sequence error',
            'HYC00' => 'Optional feature not implemented',
            'HYT00' => 'Timeout expired',
            '08001' => 'Unable to connect to data source',
            '08004' => 'Server rejected the connection',
            '08007' => 'Connection failure during transaction',
            '080S01' => 'Communication link failure',
            '08003' => 'Connection not open',
            '08006' => 'Connection failure',
            '08001' => 'SQLClient unable to establish SQL connection',
            );
            
            $value='Unkownerror';
            foreach($commonErrorCodes as $key=>$error_code)
            {
              if($code==$key)
              {
                  $value=$error_code;
                
              } 
             
            }
            return $value;
         }
        // *************************************************************************************************************************************************************

    public function loginotp(Request $request)
    {
       $data=$request->all();

        // $mobile= $data['mobile'];
        $where=array('mobile'=>$data['mobile'],'status'=>0);

        $checkforuserexist=DB::table('users')->where($where)->count();
      if($checkforuserexist>0)
        {
          if($data['mobile']==919780980916 || $data['mobile']==919917827522)
          {
            $otp=4567;
          }
          else
          {
            $otp=rand(1000,9999);
          }
            $account_sid = 'AC393748e4f5bf292b72bb09897ed9d04b';
            // $account_sid = 'AC71d1304217b87f087f357b87ac8832a6';
            // $auth_token = '510c814e103f4cbfd2c3b5f6450aaa35';
            $auth_token = '7612f0fb2dc5c14fbe6d2661165e4062';
            $url = "https://api.twilio.com/2010-04-01/Accounts/$account_sid/SMS/Messages";
            $to = "+".$data['mobile'];
            $from = "+12232176267"; // twilio trial verified number
            // $from = "+12232176267"; // twilio trial verified number
            $body = "Your OTP".' '.$otp;
            $data1 = array (
            'From' => $from,
            'To' => $to,
            'Body' => $body,
            );
            $post = http_build_query($data1);
            $x = curl_init($url);
            curl_setopt($x, CURLOPT_POST, true);
            curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($x, CURLOPT_USERPWD, "$account_sid:$auth_token");
            curl_setopt($x, CURLOPT_POSTFIELDS, $post);
            $y = curl_exec($x);
            curl_close($x);

            $lang_mess=$request->lang=='en' ? 'Send otp to your mobile number' : 'Enviar otp a su número de móvil';

            $response=['status'=>'success','message'=>$lang_mess,'otp'=>$otp];
            return response()->json($response);
        }
        else
            {  

            $where=array('mobile'=>$data['mobile'],'status'=>1);
            $checkforuserexist=DB::table('users')->where($where)->count();
            if($checkforuserexist>0)
            {

            $lang_mess=$request->lang=='en' ? 'Your account has been blocked' : 'Tu cuenta ha sido bloqueada';
            $response=['status'=>'failure','message'=>$lang_mess,'otp'=>''];
            }else
            {

            $lang_mess=$request->lang=='en' ? 'Mobile Number Not Exist' : 'Número de móvil no existe';
            $response=['status'=>'failure','message'=>$lang_mess,'otp'=>''];
            }

        }
         return response()->json($response);
    }
    public function otpsend(Request $request) //register
    {
        $data=$request->all();
        $mobile= $data['mobile'];
        $checkforuserexist=DB::table('users')->where('mobile',$mobile)->count();
        if($checkforuserexist>0)
        {
            $lang_mess=$request->lang=='en' ? 'User already exist' : 'El usuario ya existe';

            $response=['status'=>'failure','message'=>$lang_mess];
            return response()->json($response);
        }
        else{
            $otp=rand(1000,9999);
            $account_sid = 'AC393748e4f5bf292b72bb09897ed9d04b';
            $auth_token = '7612f0fb2dc5c14fbe6d2661165e4062';
            $url = "https://api.twilio.com/2010-04-01/Accounts/$account_sid/SMS/Messages";
            $to = "+".$data['mobile'];
            $from = "+12232176267"; // twilio trial verified number
            // $from = "+12232176267"; // twilio trial verified number
            $body = "Your Caco login OTP".' '.$otp;
            $data1 = array (
            'From' => $from,
            'To' => $to,
            'Body' => $body,
            );
            $post = http_build_query($data1);
            $x = curl_init($url);
            curl_setopt($x, CURLOPT_POST, true);
            curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($x, CURLOPT_USERPWD, "$account_sid:$auth_token");
            curl_setopt($x, CURLOPT_POSTFIELDS, $post);
            $y = curl_exec($x);
            curl_close($x);
        // $info = array(
        //     'name' => @$checkforuserexist->name,
        //     'otp'=>$otp
        // );
         //Mail::send(['text' => 'mail'], $info, function ($message) use($email)
        //     Mail::send('emails.otp',compact('info'), function ($message) use ($email) {
        //     $message->to($email)
        //     ->subject('OTP Send');
        //     $message->from('onam@gmail.com', 'onam@gmail.com');
        // });
            $response=['status'=>'success','otp'=>$otp];
            return response()->json($response);
        }
        
    }

    public function otpsendforforgotpassword(Request $request)
    {
        $data=$request->all();
        $email= $data['email'];
        $checkforuserexist=DB::table('users')->where('email',$email)->first();
        if(isset($checkforuserexist))
        {
            $otp=rand(1000,9999);
        $info = array(
            'name' => @$checkforuserexist->name,
            'otp'=>$otp
        );
        Mail::send('emails.otp',compact('info'), function ($message) use($email) {
            $message->to($email)
                ->subject('OTP Send');
            $message->from('Caco@gmail.com', 'Caco@gmail.com');
        });
            $response=['status'=>'success','otp'=>$otp];
            return response()->json($response);
        }
        else{
             $lang_mess=$request->lang=='en' ? 'user not exist' : 'El usuario no existe';

            $response=['status'=>'failure','message'=>$lang_mess];
            return response()->json($response);
        }
    }

    public function register(Request $request)
    {
        // $otp=rand(1000,9999);
        $data=$request->all();
        // check if user already registered in db
         $cachk=array('mobile'=>$data['mobile']);
         // $cachk1=array('email'=>$data['email']);
        $checkuserexistindb=DB::table('users')->where($cachk)->first();
        // $checkuserexistindb1=DB::table('users')->where($cachk1)->first();
        if(isset($checkuserexistindb) )
        {

              $lang_mess=$request->lang=='en' ? 'User Already Exist' : 'El usuario ya existe';

            $response=['status'=>'failure','message'=>$lang_mess];
            return response()->json($response);
        }
        else
        {   
            $no=rand(1234,5677);
            $checkforinserted=DB::table('users')->insertGetId([
                'name'=>$data['fname'],
                'lname'=>$data['lname'],
                'gender'=>'',
                'bio'=>'',
                'dob'=>'',
                'mobile'=>$data['mobile'],
                'image'=>'public/image/userimage/1748281240793284.jpeg',
                'remember_token'=>''
            ]);
            
            if($checkforinserted)
            {
                Session::put('user',$checkforinserted);
                $data['id']=$checkforinserted;
               
                $noti_arr=array('user_id'=>$data['id'],'messages'=>'New user registered'.$data['fname'].' '.$data['lname']);
                Notifications::create($noti_arr);
             $lang_mess=$request->lang=='en' ? 'User Register Successfully' : 'Registro de usuario con éxito';

                $response=['status'=>'success','message'=>$lang_mess,'data'=>$data];
            }
            else{

                 $lang_mess=$request->lang=='en' ? 'Error in Registration' : 'Error en el Registro';

                $response=['status'=>'failure','message'=>$lang_mess,'data'=>[]];
            }
                return response()->json($response);
        }

    }

    public function login(Request $request)
    {
        $data=$request->all();
         $where=array('mobile'=>$data['mobile'],'status'=>0);
         
         
        $checkforemailexist=DB::table('users')->where($where)->first();
        if(!empty($checkforemailexist))
        {
            // $checkforpassword =  $checkforemailexist->otp;
            // $userotp = $data['otp'];
            // if($checkforemailexist)
            // {
                $token=array('user_id'=>$checkforemailexist->id,'number'=>$data['mobile'],
                      'token'=>$data['deviceTocken'],
                      'd_type'=>$data['deviceType'],
                      'created_at'=>date('Y-m-d H:i:s'));
                Token::create($token);
                $detail = Session::put('user', $checkforemailexist);
                 $lang_mess=$request->lang=='en' ? 'Login Successfully' : 'Iniciar sesión con éxito';

                $response=['status'=>'success','message'=>$lang_mess,'data'=>$checkforemailexist];
               return response()->json($response);
            // }
            // else{
            //    $response=['status'=>'failure','message'=>'Credentials are incorrect'];
            //    return response()->json($response);
            // }
        }
        else{
             $lang_mess=$request->lang=='en' ? 'Credentials are incorrect' : 'Las credenciales son incorrectas';

            $response=['status'=>'failure','message'=>$lang_mess];
               return response()->json($response);
        }
    }

    public function forgotpassword(Request $request)
    {
        $data=$request->all();
        $email=$data['email'];
        $password=$data['password'];
        $checkforemailexist=DB::table('users')->where('email',$email)->first();
        if($checkforemailexist)
        {
            DB::table('users')->where('id',$checkforemailexist->id)->update([
                'password'=>Hash::make($data['password']),
            ]);

        $lang_mess=$request->lang=='en' ? 'Updated Successfully' : 'Actualizado con éxito';

            $response=['status'=>'success','message'=>$lang_mess];
               return response()->json($response);
        }
        else{

           $lang_mess=$request->lang=='en' ? 'Email not found' : 'El correo electrónico no encontrado';
            $response=['status'=>'failure','message'=>$lang_mess];
               return response()->json($response);
        }
    }

       public function distance($lat1, $long1, $lat2, $long2) {
            // $token='AIzaSyCjjdzpr0bET9HsEMsWfEPmA54tuxdiF2E';
            // $token='AIzaSyD3Bmj00bwemXp7TX5RjzWLBs7xt7xUuPI';
            $token='AIzaSyD3Bmj00bwemXp7TX5RjzWLBs7xt7xUuPI';
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".$token."&origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($ch);
                curl_close($ch);
                $response_a = json_decode($response, true);
                // print_r($response_a);
                // die;
                 $dist = @$response_a['rows'][0]['elements'][0]['distance']['value'];
                 $km = $dist/1000;
                 $aaa = $km/1.609344;
                $time = @$response_a['rows'][0]['elements'][0]['duration']['text'];
                return array('distance' => round((float)$km,2), 'time' => $time,'km'=>'KM');
            }

    public function getprofile(Request $request)
    {
        // http://172.105.172.161/caco/public/image/userimage/1748281240793284.jpeg
        // $Verify_idata=[];
        $data=$request->all();
        $userid=$data['userid'];
     // fetching vehicle list
        $distance=[];
        $rating='';
        $datamv=Vehicle::select('*')->where('user_id',$userid)->get()->toArray();
        $total_count_ride=Ride::where('userid',$userid)->where('complete_status',1)->count();
        $ride_latlong=Ride::select('pick_lat','pick_long','drop_lat','drop_long','complete_status')->where('complete_status',1)->where('userid',$userid)->get()->toArray();
        $Postal_address=Postal_address::select('*')->where('userid',$userid)->get()->toArray();
        if(isset($userid))
        {

        $Verify_idata=Verify_id::select('*')->where('user_id',$userid)->first();
        $rating=Rating_reviews::where('receiver_id',$userid)->avg('rating');

        }
        // $Verify_idata['']==
        if(empty($Verify_idata))
        {
            $Verify_idata=[];
        }
        else
        {
           // if(empty($Verify_idata['insurance'])) 
           // {

         $Verify_idata=array('insurance'=>$Verify_idata['insurance']==Null ? '' :  $Verify_idata['insurance'],
            'id_proof'=>$Verify_idata['id_proof']==Null ? '' :  $Verify_idata['id_proof'],
            'vehicle_plate'=>$Verify_idata['vehicle_plate']==Null ? '' :  $Verify_idata['vehicle_plate'],
            'driving_licence'=>$Verify_idata['driving_licence']==Null ? '' :  $Verify_idata['driving_licence'],
            'image'=>$Verify_idata['image']==NULL ? '' :  $Verify_idata['image'],
            'type'=>$Verify_idata['type']==NULL ? '' :  $Verify_idata['type'],
            'otp'=>$Verify_idata['otp']==NULL ? '' :  $Verify_idata['otp'],
            'id_proof_expdate'=>$Verify_idata['id_proof_expdate']==NULL ? '' :  $Verify_idata['id_proof_expdate'],
            'driving_licence_expdate'=>$Verify_idata['driving_licence_expdate']==NULL ? '' :  $Verify_idata['driving_licence_expdate'],
            'vehicle_plate_expdate'=>"",
            // 'vehicle_plate_expdate'=>$Verify_idata['vehicle_plate_expdate']==NULL ? '' :  $Verify_idata['vehicle_plate_expdate'],
            'insurance_expdate'=>$Verify_idata['insurance_expdate']==NULL ? '' :  $Verify_idata['insurance_expdate'],
            'id_proof_renewdate'=>$Verify_idata['id_proof_renewdate']==NULL ? '' :  $Verify_idata['id_proof_renewdate'],
            'driving_licence_renewdate'=>$Verify_idata['driving_licence_renewdate']==NULL ? '' :  $Verify_idata['driving_licence_renewdate'],
            'vehicle_plate_renewdate'=>$Verify_idata['vehicle_plate_renewdate']==NULL ? '' :  $Verify_idata['vehicle_plate_renewdate'],
            'insurance_renewdate'=>$Verify_idata['insurance_renewdate']==NULL ? '' :  $Verify_idata['insurance_renewdate']);
        }
          $thetmain=[];
         if(count($ride_latlong)>0)
         {
           foreach ($ride_latlong as $key => $value) { 
           $thetmain[]=$this->distance($value['pick_lat'],$value['pick_long'],$value['drop_lat'],$value['drop_long']);
              }
         } 
        
         $sum = 0;
         if(!empty($thetmain))
         {
            foreach($thetmain as $key => $value) {
            $sum += round($value['distance']);
          } 
         }
       
           foreach($datamv as $key=>$value)
           {

              if($value['vehicle_type']=='Hatchback' )
              {
              $data[$key]['vehicle_type_img']='public/image/userimage/Vector (2).png';

              }
              if($value['vehicle_type']=='Sedan')
              {
              $data[$key]['vehicle_type_img']='public/image/userimage/Vector.png';

              }
              if($value['vehicle_type']=='Convertible')
              {
              $data[$key]['vehicle_type_img']='public/image/userimage/Vector (3).png';

              }
              if($value['vehicle_type']=='Estate')
              {
              $data[$key]['vehicle_type_img']='public/image/userimage/Vector (4).png';

              }
              if($value['vehicle_type']=='SUV')
              {
              $data[$key]['vehicle_type_img']='public/image/userimage/Vector (5).png';

              }
              if($value['vehicle_type']=='Station Wagon')
              {
              $data[$key]['vehicle_type_img']='public/image/userimage/Vector (6).png';

              }
           }
    // end
           $userdata=[];
        $userdata=User::select('ride_notification','messages_notification','news_deals_stuff_notification','img_status','id','name','lname','mobile','gender','bio','dob','email','is_verifyId','is_verifyNumber','image','profile_status','is_verifyEmail','offer_notifications_status','created_at')->where('id',$userid)->first()->toArray();
        

        $userdata['total_count_ride']=$total_count_ride;
        $userdata['total_driven_km']=$sum;

         $userdata['rating_avg']=round($rating);
       // $verified_status=Verify_id::where('user_id',$userid)->pluck('is_verifyId')->first();

              if($userdata['is_verifyId']==1)
              {  
                 $userdata['is_verifyId']=1;
                 $userdata['is_verifyId_message']='Your request is already taken please wait for admin approval';
              }
              elseif($userdata['is_verifyId']==2){
                 $userdata['is_verifyId']=2;
                 $userdata['is_verifyId_message']='';
              }
              else
              {
                 $userdata['is_verifyId']=0;
                 $userdata['is_verifyId_message']='';

              } 
        
                               
        if($userdata)
        {
            if(isset($userdata['image']) && empty($userdata['image']))
            {
                $userdata['image']='public/image/userimage/1748281240793284.jpeg';
            }

            $response=['status'=>'success','data'=>$userdata,'vehicleslist'=>$datamv,'postal_address'=>$Postal_address,'Verify_idata'=>@$Verify_idata];
               return response()->json($response);
        }
        else{
            $response=['status'=>'failure','message'=>'Data not found','vehicleslist'=>[],'postal_address'=>[],'Verify_idata'=>[]];
               return response()->json($response);
        }
    }

    public function updateprofile(Request $request)
    {
        $lastimage='';
        $data=$request->all();
        $email=@$data['email'];
        // $phone=@$data['phone'];
        $userid=$data['userid'];
        $newpassword=@$data['newpassword'];
        $oldpassword=@$data['oldpassword'];
        $firstname=@$data['fname'];
        $lastname=@$data['lname'];
        $gender=@$data['gender'];
        $dob=@$data['dob'];
        $userimage=@$data['userimage'];
        $mobile=@$data['mobile'];
        $bio=@$data['bio'];
        $userdetails=DB::table('users')->where('id',$userid)->first();

        if(isset($newpassword) && isset($oldpassword))
        {
            if (!(Hash::check($oldpassword, $userdetails->password))) 
            {
                //when user enter wrong password


            $lang_mess=$request->lang=='en' ? 'Password you entered is incorrect' : 'La contraseña que ingresaste es incorrecta';

                $response=['status'=>'failure','message'=>$lang_mess];
                return response()->json($response);
            }
            else
            {
                $updatedpassword=Hash::make($newpassword);
            }
        }    
        // else
        // {
        //     $updatedpassword=DB::table('users')->where('id',$userid)->pluck('password')->first();
        // }
        if(isset($userimage) && !empty($userimage))
        {
            $name_gen=hexdec(uniqid());
            $img_ext=strtolower($userimage->getClientOriginalExtension());
            $img_name=$name_gen.'.'.$img_ext;
            $up_location='public/image/userimage/';
            // return $up_location;
            $lastimage=$up_location.$img_name;
            // return $lastimage;
            $userimage->move($up_location,$img_name);
            $updateArr['img_status']=1;
        }
        else
        {
            $lastimage=DB::table('users')->where('id',$userid)->pluck('image')->first();
        }
        $userdetails=DB::table('users')->where('id',$userid)->first();
        if(!isset($firstname))
        {
            $firstname=$userdetails->name;
        }
        if(!isset($lastname))
        {
            $lastname=$userdetails->lname;
        }
        if(!isset($gender))
        {
            $gender=$userdetails->gender;
        }
        if(!isset($bio))
        {
            $bio=$userdetails->bio;
        }
        if(!isset($dob))
        {
            $dob=$userdetails->dob;
        }
        if(!isset($mobile))
        {
            $mobile=$userdetails->mobile;
        }
            // update password
            $updateArr['name']=@$firstname;
            $updateArr['lname']=@$lastname;
            $updateArr['gender']=@$gender;
            $updateArr['bio']=@$bio;
            $updateArr['dob']=@$dob;
            $updateArr['image']=@$lastimage;
            $updateArr['mobile']=@$mobile;
        if(!empty($lastimage))
        {
            $updateArr['profile_status']=1;
        }
            $userupdate = DB::table('users')->where('id', $userid)
           ->update($updateArr);
                $lang_mess=$request->lang=='en' ? 'Profile Updated Successfully' : 'Perfil actualizado con éxito';

                $response=['status'=>'success','message'=>$lang_mess,'img'=>$lastimage];
                return response()->json($response);
          
           
        }

        public function changepassword(Request $request)
        {
            $userid=$data['userid'];
            $newpassword=@$data['newpassword'];
            $oldpassword=@$data['oldpassword'];
            if(isset($newpassword) && isset($oldpassword))
            {
                if (!(Hash::check($oldpassword, $userdetails->password))) 
                {
                    //when user enter wrong password

                    $lang_mess=$request->lang=='en' ? 'Password you entered is incorrect' : 'La contraseña que ingresaste es incorrecta';

                    $response=['status'=>'failure','message'=>$lang_mess];
                    return response()->json($response);
                }
                else
                {
                    $updatedpassword=Hash::make($newpassword);
                }
                $userupdate = DB::table('users')->where('id', $userid)
           ->update([
             'password'=>$updatedpassword,

            ]);
            }  
            if($userupdate)
           {

          $lang_mess=$request->lang=='en' ? 'Password changed Successfully' : 'Contraseña cambiada con éxito';

                $response=['status'=>'success','message'=>$lang_mess];
                return response()->json($response);
           }
           else
           {

          $lang_mess=$request->lang=='en' ? 'Error in password update' : 'Error en la actualización de contraseña';

                $response=['status'=>'failure','message'=>$lang_mess];
                return response()->json($response);
           }  
        }

    public function addride(Request $request)
    { 
     //add ride from this new also this check expiredate function check user document has expire or not 
  
      $this->check_expiredate();
        $data=$request->all();
        
        $userid=@$data['userid'];

        if(isset($userid)){

            $vehicle_id=@$data['vehicle_id'];
            $pick_location=@$data['pick_location'];
            $drop_location=@$data['drop_location'];
            $drop_lat=@$data['drop_lat'];
            $drop_long=@$data['drop_long'];
            $pick_lat=@$data['pick_lat'];
            $pick_long=@$data['pick_long'];
            $date=@$data['date'];
            $time=@$data['time'];
            $passenger_count=@$data['passenger_count'];
            $stoppage=$data['stoppage'];
            // $stoppage=json_decode($stoppage)

            


            $small_bag=@$data['small_bag'];
            $hand_bag=@$data['hand_bag'];
            $regular_bag=@$data['regular_bag'];
            $oversize_bag=@$data['oversize_bag'];
            $price=@$data['price'];
            $instruction=@$data['instruction'];
            $instant_booking=@$data['instant_booking'];
            // $music=$data['music'];
                

            $smoking_allowed=$data['smoking_allowed'];
            $pets_allowed=$data['pets_allowed'];
            $ac_allow=@$data['ac_allow'];
            $food_allow=@$data['food_allow'];
            // $verified_profile=$data['verified_profile'];
            $data=array(
                'userid'=>@$userid,
                'pick_location'=>@$pick_location,
                'drop_location'=>@$drop_location,
                'drop_lat'=>@$drop_lat,
                'drop_long'=>@$drop_long,
                'pick_lat'=>@$pick_lat,
                'pick_long'=>@$pick_long,
                'vehicle_id'=>@$vehicle_id,
                'pending_small_bag'=>@$small_bag,            
                'pending_hand_bag'=>@$hand_bag,              
                'pending_regular_bag'=>@$regular_bag,          
                'pending_oversize_bag'=>@$oversize_bag,
                'date'=>@$date,
                'time'=>@$time,
                'rideid'=>rand(10000,50000),
                'passenger_count'=>@$passenger_count,
                'stoppage'=>@$stoppage,
                'small_bag'=>@$small_bag,
                'hand_bag'=>@$hand_bag,
                'regular_bag'=>@$regular_bag,
                'oversize_bag'=>@$oversize_bag,
                'price'=>@$price,
                'instruction'=>@$instruction,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            );


            if($instant_booking=='true')
            {
            $data['instant_booking']=1;

            }
            else
            {
            $data['instant_booking']=0;

            } 

            if($smoking_allowed=='true')
            {
            $data['smoking_allowed']=1;

            }
            else
            {
            $data['smoking_allowed']=0;

            } 

            if($pets_allowed=='true')
            {
            $data['pets_allowed']=1;

            }
            else
            {
            $data['pets_allowed']=0;

            }
            if($food_allow=='true')
            {
            $data['food_allow']=1;

            }
            else
            {
            $data['food_allow']=0;

            }

            if($ac_allow=='true')
            {
            $data['ac_allow']=1;

            }
            else
            {
            $data['ac_allow']=0;
            }

              
            
               
               
        
                $checkforinserted=DB::table('add_rides')->insertGetId($data);
                if($checkforinserted){

                $stoppAdd=array('date'=>$date,'addride_id'=>$checkforinserted,'stop_location'=>$pick_location,'stop_lat'=>$pick_lat,'stop_long'=>$pick_long,'created_at'=>date('Y-m-d H:i:s'));
                Stop::create($stoppAdd);

                if(!empty($stoppage) && $stoppage!==null)
                {
                $stoppage=json_decode($stoppage);
                $count=count($stoppage);
                // if(!empty($stoppage))

                foreach($stoppage as $value)
                {

                if(!empty($value->lat))
                {
                $stopArr=array('date'=>$date,'addride_id'=>@$checkforinserted,'stop_location'=>@$value->name,'stop_lat'=>@$value->lat,'stop_long'=>@$value->long,'created_at'=>date('Y-m-d H:i:s'));
                Stop::create($stopArr);
                }  

                }
                }
                $stoppAdd1=array('date'=>$date,'addride_id'=>$checkforinserted,'stop_location'=>$drop_location,'stop_lat'=>$drop_lat,'stop_long'=>$drop_long,'created_at'=>date('Y-m-d H:i:s'));

                Stop::create($stoppAdd1);

                $notinfo=User::select('name as fname','lname')->where('id',$userid)->first();
                $noti_arr=array('user_id'=>$userid,'messages'=>$notinfo->fname.' '.$notinfo->lname.' Create New Ride');
                Notifications::create($noti_arr);
                $alertchack=array('pickup_location'=>@$pick_location,'drop_location'=>@$drop_location,'date'=>@$date);
                $alertdata=Alert_notification::where($alertchack)->first();
                if($alertdata)
                {

                if($userid!=$alertdata->user_id)
                {

                $this->send_notification1($userid,$alertdata->user_id,'You have new ride available on your searched route !','New ride',$alertdata);
                } 

                }
                // else
                // {

                // }
             $lang_mess=$request->lang=='en' ? 'Ride added Successfully' : 'Viaje agregado con éxito';

                $response=['status'=>'success','message'=>$lang_mess];
            }else{
                $lang_mess=$request->lang=='en' ? 'Ride not Added plase try again' : 'Viaje no agregado, inténtelo de nuevo';
                $response=['status'=>'failure','message'=>$lang_mess];
            }
            return response()->json($response);
        }
    }

    public function getallride(Request $request)
    {
         // $this->auto_complete_ride();
        $today=date('Y-m-d h:i a');
        $today1=strtotime($today);
        $data=$request->all();
        $status=@$data['status'];
        $userid=$data['userid'];
        $milesss=[];
        $curr_date=strtotime(date('Y-m-d H:i a'));
        if($status=='booked'){
          //all booking user and drivers 
                $data=Apply_ride::join('add_rides','add_rides.id','=','apply_ride.ride_id')
                ->join('users','users.id','=','apply_ride.driver_id')
                ->join('vehicles','vehicles.id','=','add_rides.vehicle_id')
                ->join('verify_id','verify_id.user_id','=','users.id','left')
                // ->join('rating_reviews','rating_reviews.sender_id','=','apply_ride.driver_id')
                ->select('users.is_verifyId','add_rides.id',
                    'vehicles.plate_number',
                    'vehicles.vehicle_brand',
                    'vehicles.country',
                    'vehicles.vehicle_model',
                    'vehicles.vehicle_type',
                    'vehicles.vechicle_color',
                    'vehicles.vechicle_madeyear',
                    'vehicles.vehicle_img',
                    'users.id as driver_id',
                    'add_rides.created_at',
                    'add_rides.id',
                    'users.name',
                    'users.lname',
                    'users.mobile',
                    'users.image',
                    'add_rides.pick_location',
                    'add_rides.complete_status',
                    'add_rides.drop_location',
                    'add_rides.pick_lat',
                    'add_rides.pick_long',
                    'add_rides.drop_lat',
                    'add_rides.drop_long',
                    'add_rides.date',
                    'add_rides.time',
                    'add_rides.passenger_count',
                    'add_rides.price',
                    'add_rides.instruction',
                    'add_rides.total_passenger as bookedsheet',
                    'add_rides.smoking_allowed',
                    'add_rides.pets_allowed',
                    'add_rides.ac_allow',
                    'add_rides.food_allow',
                    'add_rides.instant_booking',
                    'apply_ride.instant_status',
                    'apply_ride.cancel_booking',
                    'apply_ride.confirm_book',
                    'add_rides.stoppage',
                    'apply_ride.passenger_count as usersheetcount',
                    'apply_ride.price as updated_price','add_rides.small_bag','add_rides.hand_bag','add_rides.regular_bag','add_rides.oversize_bag','add_rides.pending_small_bag','add_rides.pending_hand_bag','add_rides.pending_regular_bag','add_rides.pending_oversize_bag')
                ->where('apply_ride.passenger_id',$userid)
                ->where('add_rides.delete_status','=','active') 
                 //the instant_status 1 mean it mean users can directly booking    
                // ->groupBy('apply_ride.ride_id')
                ->orderBy('add_rides.date', 'DESC')
                ->get()
                ->toArray();

                 if($data)
                 {    
                     foreach($data as $key=>$value)
                     {
                      
                       $round=Rating_reviews::where('receiver_id',$value['driver_id'])->avg('rating');
                       $data[$key]['rating_avg']=round(@$round);
                       $data[$key]['booked_users']=Apply_ride::select('apply_ride.id',
                       
                        'apply_ride.passenger_id',
                        'users.name','users.lname',
                        'users.mobile',
                        'users.gender',
                        'users.bio',
                        'users.dob',
                        'users.email',
                        'users.image',
                        'apply_ride.passenger_count as usersheetcount',
                        'apply_ride.instant_status',
                        'apply_ride.small_bag',
                        'apply_ride.hand_bag',
                        'apply_ride.regular_bag',
                        'apply_ride.oversize_bag',
                        'apply_ride.confirm_book')
                          ->join('users','users.id','=','apply_ride.passenger_id')
                          ->where('cancel_booking','=','true')
                          ->where('apply_ride.ride_id',$value['id'])->get()->toArray();
                        foreach($data[$key]['booked_users'] as $i=>$vald)
                        {
                             $rating_avg=Rating_reviews::where('receiver_id',$vald['passenger_id'])->avg('rating');
                             $data[$key]['booked_users'][$i]['rating_avg']=round(@$rating_avg);
  
                        }
                          

                    
                         $milesss=$this->distance($value['pick_lat'],$value['pick_long'],$value['drop_lat'],$value['drop_long']);
                         $data[$key]['distance']=(string)$milesss['distance'].'KM'; 
                         $data[$key]['hours']=$milesss['time'];


                            // $milesss1=$this->distance($curr_lat,$currlong,$latitude,$longitude);
                            //            $newData[$key]['estimate_distance']=(string)$milesss1['distance'].'KM'; 
                            //            $newData[$key]['estimate_hours']=$milesss1['time']; 
                            $thredate=date('h:i',strtotime($value['date'].' '.$value['time']));
                            $timess=strtotime($thredate.'+'.$milesss['time']); 
                            $newfff=date('h:i A',$timess);
                            $data[$key]['estimate_hours']=$newfff;



                         if($value['is_verifyId']==null || $value['is_verifyId']=='')
                         {
                            $data[$key]['is_verifyId']=0;
                         }
                      
                         $exdate=strtotime($value['date'].' '.$value['time']);
                         // strtotime($value['date'].' '.$value['time']);
                         if($today1>$exdate)
                         {
                            $data[$key]['expiredate']=true;
                         }
                         else
                         {
                           $data[$key]['expiredate']=false; 
                         }

                         // if($value['image']=='' || $value['image']==null )
                         // {
                         //     $data[$key]['image']='public/image/userimage/1748281240793284.jpeg';
                            
                         // }

                         if(empty($value['stoppage']) || $value['stoppage']==null )
                         {
                             $data[$key]['stoppage']=[];

                         }

                         if($value['instant_status']==0)
                         {
                             $data[$key]['instant_status']='Hold on ! While your driver accept your request';
                              $data[$key]['confirm_book']=0;
                            
                         }

                         if($value['cancel_booking']=='Cancelled')//from driver side canceled
                         {
                            $data[$key]['instant_status']='Request has been canceled by driver';
                             $data[$key]['confirm_book']=0;
                         } 

                          if($value['cancel_booking']=='Cancelledby' && $value['confirm_book']==1) //cancel by user
                         {
                            // $data[$key]['cancel_booking']='Booked';
                          $data[$key]['instant_status']='Booking has been canceled';
                          $data[$key]['confirm_book']=0;

                         } 

                        if($value['cancel_booking']=='Cancelledby')
                         {
                            $data[$key]['instant_status']='Booking has been canceled';
                         }

                         if($value['instant_status']==1 && $value['cancel_booking']=='true')
                         {
                            $data[$key]['instant_status']='Booked';
                         }
                          if($value['instruction']==null)
                         {
                            $data[$key]['instruction']='';
                         }


                          // $data[$key]['rating_reviews']=Apply_ride::join('users','users.id','=','apply_ride.driver_id')
                          // ->join('rating_reviews','rating_reviews.receiver_id','=','apply_ride.driver_id')
                          // ->select('users.name','users.lname','rating_reviews.rating','rating_reviews.reviews')
                          // ->where('apply_ride.passenger_id',$userid)
                          // ->where('apply_ride.ride_id',$value['id'])->get()->first();

                        $ddf=Rating_reviews::select('users.name','users.lname','rating_reviews.rating','rating_reviews.reviews')
                          ->join('apply_ride','apply_ride.ride_id','=','rating_reviews.ride_id')
                          ->join('users','users.id','=','apply_ride.driver_id')
                          ->where('apply_ride.passenger_id',$userid)
                          ->where('apply_ride.ride_id',$value['id'])->get()->first();
                          if($ddf)
                          {
                            $data[$key]['rating_reviews']=$ddf;
                          }
                          else
                          {
                            $data[$key]['rating_reviews']=[];
                          }



                     $data[$key]['my_rating_review_status']=Rating_reviews::where('sender_id',$userid)->where('ride_id',$value['id'])->count();
                     }
                 $lang_mess=$request->lang=='en' ? 'data found' : 'datos encontrados';

                $response=['status'=>'success','message'=>$lang_mess,'data'=>$data];  

                 }
                 else
                 {
                $lang_mess=$request->lang=='en' ? 'There Is No Ride' : 'no hay paseo';

               $response=['status'=>'success','message'=>$lang_mess,'data'=>[]];  

                 }
             }
        elseif($status=='offerd')
        {
                $data=Ride::join('users','users.id','=','add_rides.userid')
                ->join('verify_id','verify_id.user_id','=','users.id','left')
                ->join('vehicles','vehicles.id','=','add_rides.vehicle_id')

                // ->join('rating_reviews','rating_reviews.sender_id','=','add_rides.userid')
                ->select('users.profile_status','users.is_verifyId','users.id as driver_id','add_rides.created_at','add_rides.id','users.name','users.lname','users.mobile','users.image','add_rides.pick_location','add_rides.drop_location','add_rides.pick_lat','add_rides.pick_long','add_rides.drop_lat','add_rides.drop_long','add_rides.date','add_rides.time','add_rides.passenger_count','add_rides.price','add_rides.instruction','add_rides.total_passenger as bookedsheet', 'add_rides.smoking_allowed',
                'add_rides.pets_allowed',
               // 'rating_reviews.review_status',
                'add_rides.ac_allow',
                'add_rides.food_allow',
                'add_rides.stoppage',
                'add_rides.complete_status',
                'vehicles.plate_number',
                    'vehicles.vehicle_brand',
                    'vehicles.country',
                    'vehicles.vehicle_model',
                    'vehicles.vehicle_type',
                    'vehicles.vechicle_color',
                    'vehicles.vechicle_madeyear',
                    'vehicles.vehicle_img',
                'add_rides.instant_booking','add_rides.small_bag','add_rides.hand_bag','add_rides.regular_bag','add_rides.oversize_bag','add_rides.pending_small_bag','add_rides.pending_hand_bag','add_rides.pending_regular_bag','add_rides.pending_oversize_bag')
                ->where('add_rides.userid',$userid)
                ->where('add_rides.delete_status','=','active') 
                ->orderBy('add_rides.date', 'DESC')
                ->get()
                ->toArray();

               if($data)
                 {
                       
                        // $count=0;
                        foreach($data as $key=>$value)
                        {
                        $exdate=strtotime($value['date'].' '.$value['time']);
                        // strtotime($value['date'].' '.$value['time']);
                        if($today1>$exdate)
                        {

                        $data[$key]['expiredate']=true;
                        }

                        else
                        {
                        $data[$key]['expiredate']=false; 
                        }
                            $count=Apply_ride::where('ride_id',$value['id'])->where('instant_status',1)->count();
                            if($count>0)
                            {
                            $data[$key]['booking_count']=1;
                            }
                            else
                            {

                            $data[$key]['booking_count']=0;
                            }

                        // $exdate=strtotime($value['date']);
                        // if($curr_date<$exdate)
                        // {
                        // $data[$key]['expiredate']=true; 
                        // }
                        // else
                        // {
                        // $data[$key]['expiredate']=false;
                        // }   

                        $milesss=$this->distance($value['pick_lat'],$value['pick_long'],$value['drop_lat'],$value['drop_long']);
                        $data[$key]['distance']=(string)$milesss['distance'].'KM'; 
                        $data[$key]['hours']=$milesss['time'];
                        // $milesss1=$this->distance($curr_lat,$currlong,$latitude,$longitude);
                            //            $newData[$key]['estimate_distance']=(string)$milesss1['distance'].'KM'; 
                            //            $newData[$key]['estimate_hours']=$milesss1['time']; 
                            $thredate=date('H:i',strtotime($value['date'].' '.$value['time']));
                            $timess=strtotime($thredate.'+'.$milesss['time']); 
                            $newfff=date('H:i A',$timess);
                            $data[$key]['estimate_hours']=$newfff;

                            



                        if(empty($value['stoppage']) || $value['stoppage']==null )
                        {
                        $data[$key]['stoppage']=[];

                        }
                        if($value['is_verifyId']==null || $value['is_verifyId']=='')
                        {
                        $data[$key]['is_verifyId']=0;
                        }
                        $booked_users=Apply_ride::select(
                        'apply_ride.small_bag',
                        'apply_ride.hand_bag',
                        'apply_ride.regular_bag',
                        'apply_ride.oversize_bag','apply_ride.id','apply_ride.ride_id',
                            'apply_ride.driver_id','apply_ride.id','apply_ride.passenger_id','users.name','users.lname','users.mobile','users.gender','users.bio','users.dob','users.email','users.image','apply_ride.passenger_count as usersheetcount','apply_ride.instant_status','apply_ride.confirm_book')
                        ->join('users','users.id','=','apply_ride.passenger_id')
                        // ->join('rating_reviews','rating_reviews.ride_id','=','apply_ride.ride_id','left')
                        // ->where('rating_reviews.receiver_id','!=',$userid)
                        ->where('apply_ride.cancel_booking','=','true')
                        ->where('apply_ride.ride_id',$value['id'])->get()->toArray();
                         
                     

                        foreach ($booked_users as $eky=>$value123) {
                                   
                                     $dfgg=Rating_reviews::select('rating','reviews')->where('ride_id',$value123['ride_id'])->where('receiver_id',$value123['passenger_id'])->first();
                                     $ratingavg=Rating_reviews::where('receiver_id',$value123['passenger_id'])->avg('rating');

                               $booked_users[$eky]['rating_avg']=round(@$ratingavg);

                                    if(!empty($dfgg))
                                    {
                                      $booked_users[$eky]['rating']=@$dfgg->rating;
                                    $booked_users[$eky]['reviews']=@$dfgg->reviews;  
                                    }
                                    else
                                    {
                                    $booked_users[$eky]['reviews']=false;
                                    $booked_users[$eky]['rating']=false;

                                    }
                                    
                               // foreach ($value123 as $key23 => $inner_value) {
                               //     if ($inner_value === null ) {
                               //     $value123[$key23] =false;
                               //    }
                                 
                               //  }
                      }


                        $data[$key]['booked_users']=$booked_users;
                     
                        }

                        
                      
                    $lang_mess=$request->lang=='en' ? 'data found' : 'datos encontrados';

                    $response=['status'=>'success','message'=> $lang_mess,'data'=>$data,'approval'=>'Expired approval'];  

                 }
                 else
                 {
                     $lang_mess=$request->lang=='en' ? 'There Is No Ride' : 'no hay paseo';

                    $response=['status'=>'success','message'=>$lang_mess,'data'=>[],'approval'=>''];  

                 }
        } 
        else
        {

            $lang_mess=$request->lang=='en' ? 'There Is No Ride' : 'no hay paseo';

             $response=['status'=>'failure','message'=>$lang_mess,'data'=>[],'approval'=>''];

        }

            return response()->json($response);
       
    }
    

    public function auto_complete_ride()
        {
             date_default_timezone_set("America/Santo_Domingo");
        $today=date('Y-m-d h:i a');
        $data=Ride::select('id','userid','date','time','pick_lat','pick_long','drop_lat','drop_long','complete_status')->where('complete_status',0)->get()->toArray();
             
       // $esti=0;
        foreach($data as $key=>$value)
         {
                        $milesss=$this->distance($value['pick_lat'],$value['pick_long'],$value['drop_lat'],$value['drop_long']);
                        $data[$key]['distance']=(string)$milesss['distance'].'KM'; 
                        $data[$key]['hours']=$milesss['time'];
                        $thredate=date('H:i',strtotime($value['date'].' '.$value['time']));
                        $timess=strtotime($thredate.'+'.$milesss['time']); 
                        $newfff=date('h:i A',$timess);
                        $data[$key]['estimate_hours']=$newfff;
         }
        $today1=strtotime($today); 
       foreach($data as $key=>$value)
       {
      
                              
                           
                                      $passenger_Arr=0;
                                      $esti=strtotime($value['date'].''.$value['estimate_hours']);
                                      // $esti=strtotime('+2 hours',$esti);
                                      // $esti=strtotime($esti);
                                    
                                    if($today1>$esti)
                                    {  
                                       
                                        $update=array('complete_status'=>1);
                                        Ride::where('id',$value['id'])->update($update);
                                        // if($res) 
                                        // {

                                        $passenger_Arr=Apply_ride::select('id','passenger_id','driver_id','ride_id')->where('ride_id',$value['id'])->where('instant_status',1)->where('cancel_booking','true')->get()->toArray();
                                        if(count($passenger_Arr)>0)
                                        {    
                                        // echo 'yes1';

                                        foreach($passenger_Arr as $val)
                                        {
                                        // echo 'yes2';
                                        $desc="Your ride has been completed, it's time to give a review to driver";
                                        $this->send_notification_auto_complete_ride('booked',$val['passenger_id'],$val['driver_id'],$value['id'],$desc);
                                       
                                        }
                                         $desc="Your ride has been completed, please give a review your passenger";
                                        $this->send_notification_auto_complete_ride('Ride',$value['userid'],$value['userid'],$value['id'],$desc);
                                        }
                                        else
                                        {
                                           $desc="Your ride has been completed";
                                         $this->send_notification_auto_complete_ride('Ride',$value['userid'],$value['userid'],$value['id'],$desc);
                                        }
                                        

                                  }



                                   
        }
                              
       }
      
   
       
   public function send_notification_auto_complete_ride($type,$receiver_id,$userid,$ride_id,$desc)
        {
             date_default_timezone_set("America/Santo_Domingo");
         // $userid=$request->userid;
              // $receiver_id=86;
              // $title=$request->title;
    // echo  $receiver_id.'--'.$userid.'----'.$ride_id;
    //         exit();
              
              // $type='Ride';
              $senderdata=User::select('id','name','lname','image')->where('id',$userid)->first();
              $receiver_iddata=User::select('name','lname','image')->where('id',$receiver_id)->first();

                $checkArr=User::select('ride_notification','messages_notification','news_deals_stuff_notification')->where('id',$receiver_id)->first();

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
                 $title='Hi '.' '.@$receiver_iddata->name .' '. @$receiver_iddata->lname;
              // $gettokn_Arr=array('user_id'=>$request->userid);

                $arrayNames = array('user_id' =>$receiver_id,'title'=>$title,'description'=>$desc);
                // $result2=$this->init2()->insert('notifications_history',$arrayNames);
                // $userData=$this->init2()->device_token_fetch($userid,$utype);
              $getting_token_info=Token::select('*')->where('user_id',$receiver_id)->get()->toArray();
                 $notdata=$this->get_single_ridedata($ride_id,$receiver_id);
                if(!empty($getting_token_info) && count($getting_token_info)>0)
                {
                $API_SERVER_KEY="AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";

                    
                foreach ($getting_token_info as $key => $valuedata) {
                $registrationIds=$valuedata['token'];

                $data=(object)array('type'=>$type,'id'=>$senderdata->id,'image'=>$senderdata->image,'name'=>$senderdata->name,'lname'=>$senderdata->lname,'ridedata'=>$notdata);
                $msg = array
                (
                'body'  => $desc,
                // 'payload'=>'ttest',
                'title' => $title.$key,
                'icon'  => 'myicon',
                'sound' => 'mySound'
                );
                $fields = array
                (
                'to' => $registrationIds,
                'notification'=> $msg,
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
               
             
     }
}

    public function verify_id(Request $request)
     {

       

        $getU=User::select('name','lname')->where('id',$request->userid)->first();
        $data['first_name']=$getU->name;  
        $data['last_name']=$getU->lname;  
        $data['type']=@$request->type;  
         
        if($files=$request->file('image')){  
        $name=$files->getClientOriginalName();  
        $files->move('public/image/caco_media/',$name);  
        $data['image']='public/image/caco_media/'.$name;  

                 // $notinfo=User::select('fname','lname')->where('id',$userid)->first();

         $noti_arr=array('user_id'=>$request->userid,'messages'=>$getU->name.' '.$getU->lname.' submitted new documents to verify');
                Notifications::create($noti_arr);
        } 

         if($files=$request->file('id_proof')){  
        $name1=$files->getClientOriginalName();  
        $files->move('public/image/caco_media/',$name1);  
        $data['id_proof']='public/image/caco_media/'.$name1;  
         $noti_arr=array('user_id'=>$request->userid,'messages'=>$getU->name.' '.$getU->lname.' submitted new documents to verify');
          Notifications::create($noti_arr);
        } 

         if($files=$request->file('driving_licence')){  
        $name2=$files->getClientOriginalName();  
        $files->move('public/image/caco_media/',$name2);  
        $data['driving_licence']='public/image/caco_media/'.$name2;  
         $noti_arr=array('user_id'=>$request->userid,'messages'=>$getU->name.' '.$getU->lname.' submitted new documents to verify');
         Notifications::create($noti_arr);
         $data['driving_licence_status']=1;

        } 

         if($files=$request->file('vehicle_plate')){  
        $name3=$files->getClientOriginalName();  
        $files->move('public/image/caco_media/',$name3);  
        $data['vehicle_plate']='public/image/caco_media/'.$name3; 
         $noti_arr=array('user_id'=>$request->userid,'messages'=>$getU->name.' '.$getU->lname.' submitted new documents to verify');
                Notifications::create($noti_arr); 

                        $data['vehicle_plate_status']=1;

        }  
          if($files=$request->file('insurance')){  
        $name4=$files->getClientOriginalName();  
        $files->move('public/image/caco_media/',$name4);  
        $data['insurance']='public/image/caco_media/'.$name4;  
         $noti_arr=array('user_id'=>$request->userid,'messages'=>$getU->name.' '.$getU->lname.' submitted new documents to verify');
                Notifications::create($noti_arr); 
        }

        if(!empty($request->id_proof_expdate))
        {
            $data['id_proof_expdate']=date('Y-m-d',strtotime($request->id_proof_expdate));
            $data['id_proff_status']=1;

        }

        if(!empty($request->driving_licence_expdate))
        {
        $data['driving_licence_expdate']=date('Y-m-d',strtotime($request->driving_licence_expdate));
        $data['driving_licence_status']=1;

        } 

        if(!empty($request->vehicle_plate_expdate))
        {
        $data['vehicle_plate_expdate']=date('Y-m-d',strtotime($request->vehicle_plate_expdate));

        } 


        if(!empty($request->insurance_expdate))
        {
         $data['insurance_expdate']=date('Y-m-d',strtotime($request->insurance_expdate));
         $data['insurance_status']=1;
        }





         $count=Verify_id::where('user_id',$request->userid)->count();
        if(!empty($request->userid))
        {
         if($count>0)
         {      
              $data['updated_at']=date('Y-m-d H:i:s'); 
              $res=Verify_id::where('user_id',$request->userid)->update($data);

              if($res)
              {     $idArr=array('is_verifyId'=>1);
                    User::where('id',$request->userid)->update($idArr);
                    
                   $this->send_email_documents_verifyed($request->userid);
                    $lang_mess=$request->lang=='en' ? 'Please Wait for Admin Approval' : 'Espere la aprobación del administrador';

                   $response=['status'=>'success','message'=>$lang_mess];

              }
              else
              {

             $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo';

              $response=['status'=>'failure','message'=>$lang_mess];

              }
         }  
         else
         {   
            $data['user_id']=$request->userid; 
            $data['created_at']=date('Y-m-d H:i:s');

            $idArr=array('is_verifyId'=>1);
            User::where('id',$request->userid)->update($idArr); 
            $res=Verify_id::create($data);

            if($res)
              {
                $lang_mess=$request->lang=='en' ? 'Please Wait for Admin Approval ' : 'Espere la aprobación del administrador';

                  $response=['status'=>'success','message'=>$lang_mess];

              }
              else
              {

                $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again ' : 'Algo más está mal, inténtalo de nuevo.';

              $response=['status'=>'failure','message'=>$lang_mess];

              }
         }
     }
     else
     {

        $lang_mess=$request->lang=='en' ? 'User id must ' : 'La identificación del usuario debe';

              $response=['status'=>'failure','message'=>$lang_mess];

     }
         return response()->json($response); 

     }

     // **********************************************************************
     public function verify_details(Request $request)
     {
        
      
        if(!empty($request->userid))
        {       
               
                if(!empty($request->email) && !empty($request->userid))
                {

                     $detARR=array('email'=>$request->email,'is_verifyEmail'=>1);

               
                $res=User::where('id',$request->userid)->update($detARR);
                if($res)
                {

                 $lang_mess=$request->lang=='en' ? 'Email verifyed Successfully ' : 'Correo electrónico verificado con éxito';

                $response=['status'=>'success', 'message'=>$lang_mess];       
                }
                else
                {
                $lang_mess=$request->lang=='en' ? 'user not match ' : 'el usuario no coincide';

                $response=['status'=>'failure','message'=>$lang_mess]; 
                }

                }elseif(!empty($request->phone_no) && !empty($request->userid))
                {   

                $numArr=array('is_verifyNumber'=>1,'mobile'=>$request->phone_no);

                $res= User::where('id',$request->userid)->update($numArr);

                // Verify_id::where('user_id',$request->userid)->update($otparr);
                if($res)
                {
                
                $lang_mess=$request->lang=='en' ? 'Number verifyed Successfully' : 'Número verificado con éxito';

                $response=['status'=>'success', 'message'=>$lang_mess];      
                }
                else
                {
             $lang_mess=$request->lang=='en' ? 'user not match' : 'el usuario no coincide';

                $response=['status'=>'failure','message'=>$lang_mess];      
                }
                } 

         }
        else
        {
                         $lang_mess=$request->lang=='en' ? 'user not match' : 'el usuario no coincide';

                $response=['status'=>'failure', 'message'=>$lang_mess, 'otp'=>''];      
        }

                return response()->json($response); 
     }

     public function saveapi(Request $request)
     {
            if(!empty($request->userid))
            {
            $data['email']=@$request->email;
            $data['phone_no']=@$request->phone_no;
            $res=Verify_id::where('user_id',$request->userid)->update($data);
            if($res)
            {
             $lang_mess=$request->lang=='en' ? 'Successfully update' : 'Actualizar con éxito';

            $response=['status'=>'success','message'=>$lang_mess];      

            }
            else
            {
            $lang_mess=$request->lang=='en' ? 'user not match' : 'el usuario no coincide';

            $response=['status'=>'failure','message'=>$lang_mess];      

            }
            }
            else
            {

              $lang_mess=$request->lang=='en' ? 'user not match' : 'el usuario no coincide';

            $response=['status'=>'failure','message'=>$lang_mess];      
            }
            return response()->json($response);   
    }
    public function pages(Request $request)
    {     

       $pagename=$request->val;
       if($pagename=='tnc')
       {    
        // $url=asset('/').'page?val=tnc';
        $url="https://caco.do/termscondition";
                 $lang_mess=$request->lang=='en' ? 'tnc web url' : 'url web de tnc';

        $response=['status'=>'success','message'=>$lang_mess,'url'=>$url];      
       }
       elseif($pagename=='privacy')
       {
         // $url=asset('/').'page?val=privacy';
         $url="https://caco.do/privacy_policy";
         $lang_mess=$request->lang=='en' ? 'tnc web url' : 'url web de tnc';

         $response=['status'=>'success','message'=> $lang_mess,'url'=>$url];      
       }
       return response()->json($response);   
    }

    public function addvehicle(Request $request)
    {
        
       
        if(!empty($request->userid) && !empty($request->country))
        {
            $data=array('plate_number'=>@$request->plate_number,
            'vehicle_model'=>@$request->vehicle_model,
            'vehicle_brand'=>@$request->vehicle_brand,
            'vechicle_color'=>@$request->vechicle_color,
            'vehicle_type'=>@$request->vehicle_type,
            'vechicle_madeyear'=>@$request->vechicle_madeyear,
            'user_id'=>$request->userid,
            'country'=>$request->country,
            'created_at'=>date('Y-m-d H:i:s'));
            $userimage=$request->file('vehicle_img');
            if($request->file('vehicle_img'))
            {
                 // $vehicle_img=$request->file('vehicle_img');
            $name_gen=hexdec(uniqid());
            $img_ext=strtolower($request->file('vehicle_img')->getClientOriginalExtension());
            $img_name=$name_gen.'.'.$img_ext;
            $up_location='public/image/userimage/';
            // return $up_location;
            $lastimage=$up_location.$img_name;
            // return $lastimage;
            $userimage->move($up_location,$img_name);
            $data['vehicle_img']=$lastimage;
            }
            else
            {
                 $data['vehicle_img']='public/image/userimage/noimg.png';
 
            }
         $notinfo=User::select('name as fname','lname')->where('id',$request->userid)->first();
            if(empty($request->vehicle_id))
            {
                $res=Vehicle::create($data);
                $noti_arr=array('user_id'=>$request->userid,'messages'=>$notinfo->fname.' '. $notinfo->lname.'Add New Vechicle');
                Notifications::create($noti_arr); 
                 // $message='Vechicle Added Successfully';
                 $lang_mess=$request->lang=='en' ? 'Vechicle Added Successfully' : 'Vehiculo Agregado Exitosamente';


            }
            else
            {

                $noti_arr=array('user_id'=>$request->userid,'messages'=> $notinfo->fname.' '.$notinfo->lname.'Update Vechicle');
                Notifications::create($noti_arr);
                $res=Vehicle::where('id',$request->vehicle_id)->update($data);
                // $message='Vechicle update Successfully';
                $lang_mess=$request->lang=='en' ? 'Vechicle update Successfully' : 'Actualización del vehículo con éxito';

            }
            if($res)
            {
            $response=['status'=>'success','message'=>$lang_mess];      
            }
            else
            {
                 $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                $response=['status'=>'failure','message'=>$lang_mess];      
            }
        }
        else
        {

            $lang_mess=$request->lang=='en' ? 'user id and country must' : 'ID de usuario y país deben';

            $response=['status'=>'failure','message'=>$lang_mess];      
        }
        return response()->json($response);
     }
     public function updateVehicle(Request $request)
     {
        if(!empty($request->vid) && !empty($request->country))
          {    
        $data=array('plate_number'=>@$request->plate_number,
        'vehicle_model'=>@$request->vehicle_model,
        'vehicle_brand'=>@$request->vehicle_brand,
        'vechicle_color'=>@$request->vechicle_color,
        'vehicle_type'=>@$request->vehicle_type,
        'vechicle_madeyear'=>@$request->vechicle_madeyear,
        'country'=>@$request->country,
        'updated_at'=>date('Y-m-d H:i:s'));
        $res=Vehicle::where('id',$request->vid)->update($data);
        if($res)
        {
       $lang_mess=$request->lang=='en' ? 'Vechicle Update Successfully' : 'Actualización del vehículo con éxito';

        $response=['status'=>'success','message'=>$lang_mess];      
        }
        else
        {

       $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again ' : 'Algo más está mal, inténtalo de nuevo.';

        $response=['status'=>'failure','message'=>$lang_mess];      
        }
        }
        else
        {
         $lang_mess=$request->lang=='en' ? 'user id and country must' : 'ID de usuario y país deben';

        $response=['status'=>'failure','message'=>$lang_mess];      
        }
        return response()->json($response);
     }


     public function sentverifyotp(Request $request)
     {
        // $request->email;
        // $request->phone_no;
              $otp=rand(1000,9999);
                $otparr=array('otp'=>$otp);
              if(!empty($request->email))
              {
               $info = array(
                'EMail' => @$request->email,
                'otp'=>$otp
                );
                $message='';
                $email=$request->email;
                Mail::send('emails.otp',compact('info'), function ($message) use ($email) {
                $message->to($email)
                ->subject('OTP Send');
                $message->from('onam@gmail.com', 'onam@gmail.com');
                });
                 
                // $res=Verify_id::where('email',$request->email)->update($otparr);
                // if($res)
                // {
         $lang_mess=$request->lang=='en' ? 'Successfully send email' : 'Algo más está mal, inténtalo de nuevo.';

                  $response=['status'=>'success','message'=>$lang_mess,'otp'=>$otp];           
                // }
                // else
                // {
                //   $response=['status'=>'failure','message'=>'wrong email ','otp'=>''];      

                // }
            }
            elseif(!empty($request->phone_no))
            {
                               // $res=Verify_id::where('phone_no',$request->phone_no)->update($otparr);;
                                // if($res)
                                // {
                         $lang_mess=$request->lang=='en' ? 'Successfully send otp to number' : 'Enviar con éxito otp al número';

                                $response=['status'=>'success','message'=>$lang_mess,'otp'=>$otp];           
                                // }
                                // else
                                // {
                                // $response=['status'=>'failure','message'=>'wrong Number ','otp'=>''];      

                                // }


            }
            else
            {

            $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

              $response=['status'=>'failure','message'=>$lang_mess,'otp'=>''];           
 
            }

                   return response()->json($response);
 
     }
     

        // function haversineGreatCircleDistance($lat1, $lon1, $lat2, $lon2,$unit='K')
        // {
        // // convert from degrees to radians
        // $theta = $lon1 - $lon2;
        // $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        // $dist = acos($dist);
        // $dist = rad2deg($dist);
        // $miles = $dist * 60 * 1.1515;
        // $unit = strtoupper($unit);

       
        // return round($miles * 1.609344);
       

        // }
        /**************extra Function***************/
  public function newserach(Request $request)
  {
        $lat=$request->pickup_lat;
        $lon=$request->pickup_long;
        $latitude=$request->drop_lat;
        $longitude=$request->drop_long;
        $dte=$request->date;
        $nearby=DB::table("add_rides");
        $nearby=$nearby->whereDate('date',$dte);
        $nearby=$nearby->get()->toArray();
        $nearby=json_decode(json_encode($nearby), true);


//   $nearby=DB::select(\DB::raw('
//     select add_rides(
//         point(:pick_long, :pick_lat),
//         point(:drop_long, :drop_lat)
//     ) * 0.00621371192
// '), [
//     'lonA' => $lon,
//     'latA' => $lat,
//     'lonB' => $longitude,
//     'latB' => $latitude,
// ]);
            


//    echo "<pre>";
//    print_r($nearby);




  }



  public function yoyoyodistance($lat1, $lon1, $lat2, $lon2, $unit='k') {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

       $round=round($miles * 1.609344);
       echo "<pre>";
       print_r($round);
       die();
    
    }
  }




   //   public function recent_history(Request $request)  //serach ride
   //   {   
       
   //      $curr_lat=$request->curr_lat;
   //      $currlong=$request->curr_long;
   //         $passeng_count1=$request->passenger_count;
   //       $useridsd=$request->userid;
   //       if(!empty($request->userid))
   //       {
   //           $dte= date('Y-m-d',strtotime($request->date));
   //           $copyd=array('user_id'=>$request->userid,
   //                       'pickup_location'=>$request->pickup_location,
   //                       'drop_location'=>$request->drop_location);

   //           RecentHistory::where($copyd)->delete();
   //           $countsss=RecentHistory::where($copyd)->count();
   //           // if($countsss<1)
   //           // {
   //             $data=array('user_id'=>$request->userid,
   //                      'pickup_location'=>$request->pickup_location,
   //                      'drop_location'=>$request->drop_location,
   //                      'pickup_lat'=>$request->pickup_lat,
   //                      'pickup_long'=>$request->pickup_long,
   //                      'drop_lat'=>$request->drop_lat,
   //                      'drop_long'=>$request->drop_long,
   //                      'passenger_count'=>$request->passenger_count,
   //                      'date'=>$request->date,
   //                      'created_at'=>date('Y-m-d H:i:s')); 
   //            $resp=RecentHistory::create($data);
          
   // // serach ride data 
   //      $lat=$request->pickup_lat;
   //      $lon=$request->pickup_long;

   //      $latitude=$request->drop_lat;
   //      $longitude=$request->drop_long;
   //      $nearby=DB::table("add_rides");
   //      $nearby=$nearby->select('add_rides.id as ride_id','add_rides.userid as driver_id','users.name','users.lname','users.mobile','users.image','add_rides.pick_location','add_rides.drop_location','add_rides.pick_lat', 'add_rides.pick_long','add_rides.drop_lat','add_rides.drop_long','add_rides.date','add_rides.time','add_rides.passenger_count','add_rides.price','users.is_verifyId','users.avg_rating', 'add_rides.instruction','vehicles.plate_number','vehicles.vehicle_brand','vehicles.country','vehicles.vehicle_model','vehicles.vehicle_type','vehicles.vechicle_color','vehicles.vechicle_madeyear','vehicles.vehicle_img','add_rides.total_passenger as bookedsheet','add_rides.stoppage','add_rides.small_bag','add_rides.hand_bag','add_rides.regular_bag','add_rides.smoking_allowed','add_rides.pets_allowed','add_rides.verified_profile','add_rides.instant_booking','add_rides.food_allow','add_rides.ac_allow','add_rides.oversize_bag',DB::raw("6371 * acos(cos(radians(" . $lat . "))* cos(radians(pick_lat)) * cos(radians(pick_long) - radians(" . $lon . ")) + sin(radians(" .$lat. ")) * sin(radians(pick_lat))) AS distance"));
   //              $nearby=$nearby->join('users','users.id','=','add_rides.userid');
   //              $nearby=$nearby->join('vehicles','vehicles.id','=','add_rides.vehicle_id');
   //              $nearby=$nearby->having('distance', '<=', 20);
   //              $nearby=$nearby->whereDate('add_rides.date', '=',$dte);
   //              $nearby=$nearby->where('add_rides.complete_status',0);
   //              $nearby=$nearby->where('add_rides.delete_status','active');
   //              $nearby=$nearby->orderBy('distance', 'asc');
   //              $nearby=$nearby->get()->toArray();
   //              $nearby = json_decode(json_encode($nearby), true);
                

   //              if($nearby)
   //              {
   //              $passeng_count=$request->passenger_count;
   //              $milesss=[];
   //              $newData=[];
   //              $timess=[];  
   //              $desto=[];
   //                $newarrarry=[];
   //              foreach ($nearby as $key=>$value) {
                    
   //                     $desto=$this->distance($latitude,$longitude,$value['drop_lat'],$value['drop_long']); 
                      
   //                     if($desto['distance']<=20)
   //                     { 
   //                           $newArr=[];
   //                           $newarr=array('ride_id'=>$value['ride_id'],
   //                              'mobile'=>$value['mobile'],
   //                              'driver_id'=>$value['driver_id'],
   //                              'name'=>$value['name'],
   //                              'lname'=>$value['lname'],
   //                              'pick_location'=>$value['pick_location'],
   //                              'drop_location'=>$value['drop_location'],
   //                              'pick_lat'=>$value['pick_lat'],
   //                              'pick_long'=>$value['pick_long'],
   //                              'drop_long'=>$value['drop_long'],
   //                              'date'=>$value['date'],
   //                                  'time' =>$value['time'],
   //                                  'passenger_count'=>$value['passenger_count'],
   //                                  'price'=>$value['price'],
   //                                  'is_verifyId'=>$value['is_verifyId'],
   //                                  'instruction'=>$value['instruction'],
   //                                  'plate_number'=>$value['plate_number'],
   //                                  'vehicle_brand'=>$value['vehicle_brand'],
   //                                  'country'=>$value['country'],
   //                                  'vehicle_model'=>$value['vehicle_model'],
   //                                  'vehicle_type'=>$value['vehicle_type'],
   //                                  'vechicle_color'=>$value['vechicle_color'],
   //                                  'vechicle_madeyear'=>$value['vechicle_madeyear'],
   //                                  'vehicle_img'=>$value['vehicle_img'],
   //                                  'bookedsheet'=>$value['bookedsheet'],
   //                                  'stoppage'=>$value['stoppage'],
   //                                  'small_bag'=>$value['small_bag'],
   //                                  'hand_bag'=>$value['hand_bag'],
   //                                  'regular_bag'=>$value['regular_bag'],
   //                                  'smoking_allowed'=>$value['smoking_allowed'],
   //                                  'pets_allowed'=>$value['pets_allowed'],
   //                                  'verified_profile'=>$value['verified_profile'],
   //                                  'instant_booking'=>$value['instant_booking'],
   //                                  'food_allow'=>$value['food_allow'],
   //                                  'ac_allow'=>$value['ac_allow'],
   //                                  'oversize_bag'=>$value['oversize_bag'],
   //                                  'image'=>$value['image']);
                       
   //                            // $newData[$key]['ride_id']=$value['ride_id'];
   //                       // $newData[$key]['distancedfsdfssf']=$desto['distance'];
   //                              // $newData[$key]['driver_id']=$value['driver_id'];
   //                              // $newData[$key]['name']=$value['name'];
   //                              // $newData[$key]['lname']=$value['lname'];
   //                              // $newData[$key]['mobile']=$value['mobile'];
   //                              // $newData[$key]['pick_location']=$value['pick_location'];
   //                              // $newData[$key]['drop_location']=$value['drop_location'];
   //                              // $newData[$key]['pick_lat']=$value['pick_lat'];
   //                              // $newData[$key]['pick_long']=$value['pick_long'];
   //                              // $newData[$key]['drop_lat']=$value['drop_lat'];
   //                              // $newData[$key]['drop_long']=$value['drop_long'];
   //                              // $newData[$key]['date']=$value['date'];
   //                              // $newData[$key]['time']=$value['time'];
   //                              // $newData[$key]['passenger_count']=$value['passenger_count'];
   //                              // $newData[$key]['price']=$value['price'];
   //                              // $newData[$key]['is_verifyId']=$value['is_verifyId'];
   //                              // $newData[$key]['instruction']=$value['instruction'];
   //                              // $newData[$key]['plate_number']=$value['plate_number'];
   //                              // $newData[$key]['vehicle_brand']=$value['vehicle_brand'];
   //                              // $newData[$key]['country']=$value['country'];
   //                              // $newData[$key]['vehicle_model']=$value['vehicle_model'];
   //                              // $newData[$key]['vehicle_type']=$value['vehicle_type'];
   //                              // $newData[$key]['vechicle_color']=$value['vechicle_color'];
   //                              // $newData[$key]['vechicle_madeyear']=$value['vechicle_madeyear'];
   //                              // $newData[$key]['vehicle_img']=$value['vehicle_img'];
   //                              // $newData[$key]['bookedsheet']=$value['bookedsheet'];
   //                              // $newData[$key]['stoppage']=$value['stoppage'];
   //                              // $newData[$key]['small_bag']=$value['small_bag'];
   //                              // $newData[$key]['hand_bag']=$value['hand_bag'];
   //                              // $newData[$key]['regular_bag']=$value['regular_bag'];
   //                              // $newData[$key]['smoking_allowed']=$value['smoking_allowed']; 
   //                              // $newData[$key]['pets_allowed']=$value['pets_allowed'];
   //                              // $newData[$key]['verified_profile']=$value['verified_profile'];
   //                              // $newData[$key]['instant_booking']=$value['instant_booking'];
   //                              // $newData[$key]['food_allow']=$value['food_allow'];
   //                              // $newData[$key]['ac_allow']=$value['ac_allow'];
   //                              // $newData[$key]['oversize_bag']=$value['oversize_bag'];
   //                              // $newData[$key]['image']=$value['image'];

   //                              $srchArrrr=array('passenger_id'=>$useridsd,'ride_id'=>$value['ride_id'],'cancel_booking'=>'true');
   //                              $rating=Rating_reviews::where('receiver_id',$value['driver_id'])->avg('rating');
   //                              //$newData[$key]['avg_rating']=round($rating,1);
   //                              $newarr['avg_rating']=round($rating,1);
   //                                  if($newarr['avg_rating']==null)
   //                                  {
   //                                  $newarr['avg_rating']=0;
   //                                  }
   //                                  $count=Apply_ride::where($srchArrrr)->count();
   //                                  if($count>0)
   //                                  {
   //                                  $newarr['booking_status']=1;   
   //                                  }
   //                                  else
   //                                  {   
   //                                  $newarr['booking_status']=0;   
   //                                  }
   //                                  $newarr['updated_price']=$value['price']*$passeng_count1; 
   //                                  $milesss=$this->distance($lat,$lon,$latitude,$longitude);
   //                                  $newarr['distance']=(string)$milesss['distance'].'KM'; 
   //                                  $newarr['passenger_count']=(int)$value['passenger_count']; 
   //                                  $newarr['hours']=$milesss['time']; 

   //                                  if(!empty($lat) && !empty($lon))
   //                                  {

   //                                  $milesss1=$this->distance($lat,$lon,$latitude,$longitude);
   //                                  $newarr['estimate_distance']=(string)$milesss1['distance'].'KM'; 
   //                                  $newarr['estimate_hours']=$milesss1['time']; 
   //                                  $thredate=date('H:i',strtotime($value['date'].' '.$value['time']));
   //                                  $timess=strtotime($thredate.'+'.$milesss1['time']); 
   //                                  $newfff=date('h:i A',$timess);
   //                                  $newarr['estimate_hours']=$newfff;

   //                                 } 
   //                              else
   //                                {
   //                                 $newarr['estimate_distance']=''; 
   //                                 $newarr['estimate_hours']=''; 
   //                                 $newarr['estimate_hours']='';
   //                                }
   //                                  if(empty($value['image']) || $value['image']='') 
   //                                  {

   //                                     $newarr['image']='public/image/userimage/1748281240793284.jpeg';    

   //                                  }
   //                                        $newarrarry[]=$newarr;
   //                     }

                                
   //              }

   //                if(!empty($newarrarry))
   //                {
   //                  $lang_mess=$request->lang=='en' ? 'Fetch all rides' : 'Obtener todos los viajes';

   //                $response=['status'=>'success','message'=>$lang_mess,'data'=>$newarrarry];  
   //                }
   //                else
   //                {
   //                   $datads=$this->stoppage_search($latitude,$longitude,$lat,$lon,$dte,$useridsd,$passeng_count1);
   //                  if(!empty($datads))
   //                  {
   //                  $lang_mess=$request->lang=='en' ? 'Fetch all rides' : 'Obtener todos los viajes';
   //                  $response=['status'=>'success','message'=>$lang_mess,'data'=>$datads]; 
   //                  }
   //                  else
   //                  {
   //                  $lang_mess=$request->lang=='en' ? 'Rides Not available' : 'Paseos No disponible';
   //                  $response=['status'=>'success','message'=>$lang_mess,'data'=>[]];  
   //                  } 
   //                }
       
                         
   //              }

   //              else
   //              {

   //                  $datads=$this->stoppage_search($latitude,$longitude,$lat,$lon,$dte,$useridsd,$passeng_count1);
   //                  if(!empty($datads))
   //                  {
   //                  $lang_mess=$request->lang=='en' ? 'Fetch all rides' : 'Obtener todos los viajes';
   //                  $response=['status'=>'success','message'=>$lang_mess,'data'=>$datads]; 
   //                  }
   //                  else
   //                  {
   //                  $lang_mess=$request->lang=='en' ? 'Rides Not available' : 'Paseos No disponible';
   //                  $response=['status'=>'success','message'=>$lang_mess,'data'=>[]];  
   //                  }        
   //              }
   //      } 
   //       else
   //       {
   //         $lang_mess=$request->lang=='en' ? 'User id must' : 'La identificación del usuario debe';

   //         $response=['status'=>'failure','message'=>$lang_mess];           
   //       }

       

   //       return response()->json($response);

   //   }
     public function recent_history(Request $request)
        {
            
            $dte= date('Y-m-d',strtotime($request->date));
             $copyd=array('user_id'=>$request->userid,
                           'date'=>$request->date,
                          'pickup_location'=>$request->pickup_location,
                         'drop_location'=>$request->drop_location);

             RecentHistory::where($copyd)->delete();
             
             
               $data=array('user_id'=>$request->userid,
                        'pickup_location'=>$request->pickup_location,
                        'drop_location'=>$request->drop_location,
                        'pickup_lat'=>$request->pickup_lat,
                        'pickup_long'=>$request->pickup_long,
                        'drop_lat'=>$request->drop_lat,
                        'drop_long'=>$request->drop_long,
                        'passenger_count'=>$request->passenger_count,
                        'date'=>$request->date,
                        'created_at'=>date('Y-m-d H:i:s')); 
              RecentHistory::create($data);
       $countsss=Alert_notification::where($copyd)->count();
// **************************************************************
        $desto=$this->distance($request->pickup_lat,$request->pickup_long,$request->drop_lat,$request->drop_long);  
        
          if($desto['distance']>1)
          {


         $curr_lat=$request->curr_lat;
         $currlong=$request->curr_long;
         $passeng_count1=$request->passenger_count;
         $useridsd=$request->userid;
         $lat=$request->pickup_lat;
         $lon=$request->pickup_long;
         $dte= date('Y-m-d',strtotime($request->date));
         $latitude=$request->drop_lat;
         $longitude=$request->drop_long;
         $newData=[];

          $milesss=[];
          $milesss1=[];
          $nearby=DB::table("sotpage");
          $nearby=$nearby->select('*',DB::raw("6371 * acos(cos(radians(" . $lat . "))
                                * cos(radians(stop_lat)) * cos(radians(stop_long) - radians(" . $lon . "))
                                + sin(radians(" .$lat. ")) * sin(radians(stop_lat))) AS distance"));
                $nearby=$nearby->having('distance', '<=',30);
                $nearby=$nearby->whereDate('sotpage.date', '=',$dte);
                $nearby=$nearby->orderBy('distance', 'asc');
                $nearby=$nearby->get()->toArray();
                $nearby = json_decode(json_encode($nearby), true);



            
                $nearby2=DB::table("sotpage");
                $nearby2=$nearby2->select('*',DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(stop_lat)) * cos(radians(stop_long) - radians(" . $longitude . "))
                                + sin(radians(" .$latitude. ")) * sin(radians(stop_lat))) AS distance"));
                $nearby2=$nearby2->having('distance', '<=',30);
                $nearby2=$nearby2->whereDate('sotpage.date', '=',$dte);
                $nearby2=$nearby2->orderBy('distance', 'asc');
                $nearby2=$nearby2->get()->toArray();
                $nearby2 = json_decode(json_encode($nearby2), true);
          
            
                $first_names=array_column($nearby, 'addride_id');
                $first_namesId=array_column($nearby, 'id');
                $last_names=array_column($nearby2, 'addride_id');
                $last_namesId=array_column($nearby2, 'id');
                $ids=array_unique(array_intersect($first_names, $last_names));
            
         
                    $final=[];
                        foreach ($ids as $key => $value) {
                        $idp=array_search($value, $first_names);  
                        $idp2=array_search($value, $last_names);  

                        if($first_namesId[$idp]<$last_namesId[$idp2])
                        {
                        $final[]=$value;
                        }
                     }
           
        $nearby5=DB::table("add_rides");
        $nearby5=$nearby5->select('add_rides.id as ride_id','add_rides.userid as driver_id','users.offer_notifications_status','users.name','users.lname','users.mobile','users.image','add_rides.pick_location','add_rides.drop_location','add_rides.pick_lat', 'add_rides.pick_long','add_rides.drop_lat','add_rides.drop_long','add_rides.date','add_rides.time','add_rides.passenger_count','add_rides.price','users.is_verifyId','users.avg_rating', 'add_rides.instruction','vehicles.plate_number','vehicles.vehicle_brand','vehicles.country','vehicles.vehicle_model','vehicles.vehicle_type','vehicles.vechicle_color','vehicles.vechicle_madeyear','vehicles.vehicle_img','add_rides.total_passenger as bookedsheet','add_rides.stoppage','add_rides.small_bag','add_rides.hand_bag','add_rides.regular_bag','add_rides.smoking_allowed','add_rides.pets_allowed','add_rides.verified_profile','add_rides.instant_booking','add_rides.food_allow','add_rides.ac_allow','add_rides.oversize_bag','add_rides.pending_small_bag','add_rides.pending_hand_bag','add_rides.pending_regular_bag','add_rides.pending_oversize_bag');
                $nearby5=$nearby5->join('users','users.id','=','add_rides.userid');
                $nearby5=$nearby5->join('vehicles','vehicles.id','=','add_rides.vehicle_id');
                $nearby5=$nearby5->whereDate('add_rides.date', '=',$dte);
                $nearby5=$nearby5->whereIn('add_rides.id',$final);
                // $nearby5=$nearby5->where('add_rides.complete_status',0);
                $nearby5=$nearby5->where('add_rides.delete_status','active');
                $nearby5=$nearby5->get()->toArray();
                $nearby5 = json_decode(json_encode($nearby5), true);
         
       //    $today=date('h:i  a');
        

        //   $dtett=strtotime($dte.' '.$today);

     // $dte=date('Y-m-d'); 
        $today=date('h:i a'); 
        $aaj=date('Y-m-d');
        if($aaj==$dte)
        {
        $today12=strtotime($dte.''.$today);
        }
        else
        {
         $today12=strtotime($dte);   
        }
            $newarrarry=[];
            if(!empty($nearby5 ))
            {
                  $datedb1='';
                foreach ($nearby5 as $key=>$value) {
                       
                 $datedb1=strtotime($value['date'].' '.$value['time']);
                 if($datedb1>$today12)
                      { 

                        $newArr=[];
                        $newarr=array('ride_id'=>$value['ride_id'],
                           'mobile'=>$value['mobile'],
                           'driver_id'=>$value['driver_id'],
                           'offer_notifications_status'=>$value['offer_notifications_status'],
                           'name'=>$value['name'],
                           'lname'=>$value['lname'],
                           'pick_location'=>$value['pick_location'],
                           'drop_location'=>$value['drop_location'],
                           'pick_lat'=>$value['pick_lat'],
                           'pick_long'=>$value['pick_long'],
                           'drop_long'=>$value['drop_long'],
                           'date'=>$value['date'],
                               'time' =>$value['time'],
                               'passenger_count'=>$value['passenger_count'],
                               'price'=>$value['price'],
                               'is_verifyId'=>$value['is_verifyId'],
                               'instruction'=>$value['instruction'],
                               'plate_number'=>$value['plate_number'],
                               'vehicle_brand'=>$value['vehicle_brand'],
                               'country'=>$value['country'],
                               'vehicle_model'=>$value['vehicle_model'],
                               'vehicle_type'=>$value['vehicle_type'],
                               'vechicle_color'=>$value['vechicle_color'],
                               'vechicle_madeyear'=>$value['vechicle_madeyear'],
                               'vehicle_img'=>$value['vehicle_img'],
                               'bookedsheet'=>$value['bookedsheet'],
                               'stoppage'=>$value['stoppage'],
                               'small_bag'=>$value['small_bag'],
                               'hand_bag'=>$value['hand_bag'],
                               'regular_bag'=>$value['regular_bag'],
                               'smoking_allowed'=>$value['smoking_allowed'],
                               'pets_allowed'=>$value['pets_allowed'],
                               'verified_profile'=>$value['verified_profile'],
                               'instant_booking'=>$value['instant_booking'],
                               'food_allow'=>$value['food_allow'],
                               'ac_allow'=>$value['ac_allow'],
                               'oversize_bag'=>$value['oversize_bag'],
                               'image'=>$value['image'],
                                'pending_small_bag'=>$value['pending_small_bag'],
                                'pending_hand_bag'=>$value['pending_hand_bag'],
                                'pending_regular_bag'=>$value['pending_regular_bag'],
                                'pending_oversize_bag'=>$value['pending_oversize_bag']
                           );


                               $srchArrrr=array('passenger_id'=>$useridsd,'ride_id'=>$value['ride_id'],'cancel_booking'=>'true');
                                $rating=Rating_reviews::where('receiver_id',$value['driver_id'])->avg('rating');
                                //$newData[$key]['avg_rating']=round($rating,1);
                                $newarr['avg_rating']=round($rating,1);
                                    if($newarr['avg_rating']==null)
                                    {
                                    $newarr['avg_rating']=0;
                                    }
                                    $count=Apply_ride::where($srchArrrr)->count();
                                    if($count>0)
                                    {
                                    $newarr['booking_status']=1;   
                                    }
                                    else
                                    {   
                                    $newarr['booking_status']=0;   
                                    }
                                    $newarr['updated_price']=$value['price']*$passeng_count1; 
                                    $milesss=$this->distance($lat,$lon,$latitude,$longitude);
                                    $newarr['distance']=(string)$milesss['distance'].'KM'; 
                                    $newarr['passenger_count']=(int)$value['passenger_count']; 
                                    $newarr['hours']=$milesss['time']; 

                                    if(!empty($lat) && !empty($lon))
                                    {

                                    $milesss1=$this->distance($lat,$lon,$latitude,$longitude);
                                    $newarr['estimate_distance']=(string)$milesss1['distance'].'KM'; 
                                    $newarr['estimate_hours']=$milesss1['time']; 
                                    $thredate=date('H:i',strtotime($value['date'].' '.$value['time']));
                                    $timess=strtotime($thredate.'+'.$milesss1['time']); 
                                    $newfff=date('h:i A',$timess);
                                    $newarr['estimate_hours']=$newfff;
                                    $newarr['check_km']=(string)$milesss1['distance'];

                                   } 
                                else
                                  {
                                   $newarr['estimate_distance']=''; 
                                   $newarr['estimate_hours']=''; 
                                   $newarr['estimate_hours']='';
                                  }
                                    // if(empty($value['image']) || $value['image']='') 
                                    // {

                                    //    $newarr['image']='public/image/userimage/1748281240793284.jpeg';    

                                    // }
                         $newarrarry[]=$newarr;
             }
            }
       

          }
         

          if(!empty($newarrarry))
                    {
                    $lang_mess=$request->lang=='en' ? 'Fetch all rides' : 'Obtener todos los viajes';

                     if(!empty($request->departure_time))
                     {
                         $newarrarry=$this->filter_ride($request->departure_time,$newarrarry,$request->short_by,$curr_lat,$currlong);
                        $response=['status'=>'success','message'=>$lang_mess,'data'=>$newarrarry,'alertcount'=>$countsss]; 
                     }
                     else
                     {
                        $response=['status'=>'success','message'=>$lang_mess,'data'=>$newarrarry,'alertcount'=>$countsss]; 
                     }
                    }
                    else
                    {
                    $lang_mess=$request->lang=='en' ? 'Rides Not available' : 'Paseos No disponible';
                    $response=['status'=>'success','message'=>$lang_mess,'data'=>[],'alertcount'=>$countsss];  
                    } 
                    }
                    else
                    {
                      $lang_mess=$request->lang=='en' ? 'Rides Not available' : 'Paseos No disponible';

                       $response=['status'=>'success','message'=>$lang_mess,'data'=>[],'alertcount'=>$countsss];   
                    } 

                return response()->json($response);      
     }

     public function filter_ride($time,$newarrarry,$short_by,$curr_lat,$currlong)
     {   
          $short_by1='';
        if($short_by==="Earliest departure")
          {
              $short_by1='Earliest_departure';
          }
          if($short_by==="Lowest price")
          {
             $short_by1='Lowest_price';

          }
          if($short_by==="Close to departure point")
          {
            $short_by1='Close_to_departure_point';

          }
          if($short_by==="Close to arrival point")
          {
             $short_by1='Close_to_arrival_point';

          }
          if($short_by==="Shortest Ride")
          {
              $short_by1='Shortest_Ride';

          } 

        $time=trim($time); 
          $times=explode('-',$time);
          $t1=$times[0];
          $t2=$times[1];

          $result = array();
           if($times[0]=='After')
           { 
                $endTimeStamp = strtotime($t2);
                foreach ($newarrarry as $ride) {
                $rideTimeStamp = strtotime($ride['time']);
                if ($endTimeStamp >= $rideTimeStamp) {
                $result[] = $ride;
                }
                }
           }
            if($times[0]=='Before')
           {
            
                $endTimeStamp = strtotime($t2);
                foreach ($newarrarry as $ride) {
                $rideTimeStamp = strtotime($ride['time']);
                if ($endTimeStamp <= $rideTimeStamp) {
                $result[] = $ride;
                }
                }

           }
            if($times[0]!=='Before' && $times[0]!=='After')
           {  
             

                
                $startTimeStamp = strtotime($t1);
                $endTimeStamp = strtotime($t2);

                foreach ($newarrarry as $ride) {
                $rideTimeStamp = strtotime($ride['time']);
                if ($rideTimeStamp >= $startTimeStamp && $rideTimeStamp <= $endTimeStamp) {
                $result[] = $ride;
                }
                }
           }

        return $result;



     }

     public function getsearch_history(Request $request)
     {
        if(!empty($request->userid))
        {

            $date=date('Y-m-d');
            $data=RecentHistory::select('id',
                                  'user_id',
                                  'pickup_location',
                                  'drop_location',
                                  'pickup_lat',
                                  'pickup_long',
                                  'drop_lat',
                                  'drop_long',
                                  'passenger_count',
                                  'date')
                                  ->where('user_id',$request->userid)->orderBy('id', 'DESC')->limit(3)
                                  // ->whereDate('date','>',$date)
                                  ->get()
                                   ->toArray(); 
                  $today=date('Y-m-d');
                  $today=strtotime($today);
                foreach($data as $key=>$value)
                {
                   $dbdate=strtotime($value['date']);
                   if($dbdate < $today)
                   {
                       $data[$key]['showDate']=0;
                   }  
                   else
                   {
                       $data[$key]['showDate']=1;
                   }
                }                  


              $verified_status=User::where('id',$request->userid)->pluck('is_verifyId')->first();

              if($verified_status==1)
              {  


                 $mata['is_verifyId']=$verified_status;
                 $mata['is_verifyId_message']='Your request is already taken please wait for admin approval';
              }
              elseif($verified_status==2){
                 $mata['is_verifyId']=$verified_status;
                 $mata['is_verifyId_message']='';
              }
              else
              {
                 $mata['is_verifyId']=0;
                 $mata['is_verifyId_message']='';

              }                    

           $lang_mess=$request->lang=='en' ? 'Data fetch Successfully' : 'Obtención de datos con éxito';

            $response=['status'=>'success','message'=>$lang_mess,'is_verifyId'=>$mata['is_verifyId'],'is_verifyId_message'=>$mata['is_verifyId_message'],'data'=>$data];           
        } 
        else
        { 
         $lang_mess=$request->lang=='en' ? 'User id must' : 'La identificación del usuario debe';

            $response=['status'=>'failure','message'=>$lang_mess,'data'=>[]];           

        }
        return response()->json($response);
     }


     public function cancel_ride(Request $request)
     {
        //driver
          if(!empty($request->ride_id))
           {
              
             
            $delArr=array('delete_status'=>'delete');
           $res=Ride::where('id',$request->ride_id)->update($delArr);
           // $res1=Apply_ride::where('ride_id',$request->ride_id)->delete();
           if($res)
           {
             $noti=Ride::select('id','userid')->where('id',$request->ride_id)->first();

              $datag=Apply_ride::select('*')->where('ride_id',$noti->id)->get()->toArray();
      
              foreach($datag as $value)
              {
                $st=$this->send_notification1($noti->userid,$value['passenger_id'],'Canceled Ride','cancel Ride');

              }
                $notinfo=User::select('name as fname','lname')->where('id',$noti->userid)->first();
                $noti_arr=array('user_id'=>$noti->userid,'messages'=>$notinfo->fname.' '.$notinfo->fname.'Cancel Ride');
                 Notifications::create($noti_arr);
               $lang_mess=$request->lang=='en' ? 'Ride Canceled Successfully' : 'Viaje cancelado con éxito';

               $response=['status'=>'success','message'=>$lang_mess];    
           }
           else
           {

             $lang_mess=$request->lang=='en' ? 'Unable to delete ride' : 'No se puede eliminar el viaje';

              $response=['status'=>'success','message'=>$lang_mess];    

           }

        }
        else
        {

             $lang_mess=$request->lang=='en' ? 'User id Must' : 'ID de usuario debe';

            $response=['status'=>'failure','message'=>$lang_mess];           
  
        }

        return response()->json($response);

     }
     public function update_ride(Request $request)
     {

        if(!empty($request->ride_id))
        {
              $passenger_count=$request->passenger_count;
              $instruction=$request->instruction;
              $price=$request->price;
              $updateArr=array();

              if(!empty($request->date))
              {
              $updateArr['date']=$request->date;

              $this->send_notification_to_all('Ride',$request->ride_id,$request->pick_location,$request->drop_location,$request->date);
              $upArr=array('date'=>$request->date);
              Stop::where('addride_id',$request->ride_id)->update($upArr);

              }
              if(!empty($request->time))
              {
              $updateArr['time']=$request->time;

              }
              if(!empty($request->pick_location))
              {
              $updateArr['pick_location']=$request->pick_location;

              }
              if(!empty($request->drop_location))
              {
              $updateArr['drop_location']=$request->drop_location;

              }
              if(!empty($request->drop_lat))
              {
              $updateArr['drop_lat']=$request->drop_lat;

              }
              if(!empty($request->drop_long))
              {
              $updateArr['drop_long']=$request->drop_long;

              }
              if(!empty($request->pick_lat))
              {
              $updateArr['pick_lat']=$request->pick_lat;

              }
              if(!empty($request->pick_long))
              {
              $updateArr['pick_long']=$request->pick_long;

              }
              if(!empty($request->stoppage))
              {
                
               $updateArr['stoppage']=$request->stoppage;


                 $stoppage1=json_decode($request->stoppage);

                
                    Stop::where('addride_id',$request->ride_id)->delete();
                    if(count($stoppage1)>0)
                    {
                    foreach($stoppage1 as $value)
                    {

                    $newarr=array('date'=>$request->date,'addride_id'=>$request->ride_id,'stop_location'=>$value->name,'stop_lat'=>$value->lat,'stop_long'=>$value->long,'created_at'=>date('Y-m-d H:i:s'));
                    Stop::create($newarr);

                    }
                    }
              }



               if(!empty($passenger_count))
               {
                $updateArr['passenger_count']=$passenger_count;
               }
               if(!empty($instruction))
               {
                $updateArr['instruction']=$instruction;
               }


               if(!empty($price))
               {
                $updateArr['price']=$price;

               }
                
                if(isset($request->small_bag))
                {
                 $updateArr['small_bag']=$request->small_bag;
                 $updateArr['pending_small_bag']=$request->small_bag;

 
                }
                  if(isset($request->hand_bag))
                {
                 $updateArr['hand_bag']=$request->hand_bag;
                  $updateArr['pending_hand_bag']=$request->hand_bag;

 
                }  

                if(isset($request->regular_bag))
                {

                 $updateArr['regular_bag']=$request->regular_bag;
                $updateArr['pending_regular_bag']=$request->regular_bag;

 
                } 
                if(isset($request->oversize_bag))
                {

                 $updateArr['oversize_bag']=$request->oversize_bag;
                 $updateArr['pending_oversize_bag']=$request->oversize_bag;
 
                }

                
                $resp=Ride::where('id',$request->ride_id)->update($updateArr);
                if($resp)
                {
                    
                   $lang_mess=$request->lang=='en' ? 'Update Ride Successfully' : 'Actualizar viaje con éxito';

                     $response=['status'=>'success','message'=>$lang_mess];           

                }
                else
                {
                  $lang_mess=$request->lang=='en' ? 'unable to Update plase try again' : 'no se puede actualizar por favor inténtalo de nuevo';

                     $response=['status'=>'failure','message'=>$lang_mess];           

                }
           
        }
        else
        {

              $lang_mess=$request->lang=='en' ? 'Ride_id id Must' : 'Ride_id id Debe';

             $response=['status'=>'failure','message'=>$lang_mess];           
  
        }
                return response()->json($response);


     }

      public function send_notification_to_all($type1='Ride',$data1=null,$pick_location,$drop_location,$date)
        {
         
              
              $where=array('pickup_location'=>$pick_location,'drop_location'=>$drop_location,'date'=>$date);
              $datass=RecentHistory::select('user_id')->where($where)->get()->toArray();
            if(count($datass)<1)
              {
                  return false;
              }
              else
              {
               
                    foreach($datass as $val)
                    {
                    $this->call_back($val['user_id'],$where,$data1,$date);
                    }
               
              }

       }

    public function call_back($receiver_id,$where,$data1,$date)
    {
    
               $metaid=Ride::select('userid')->where('id',$data1)->first();
               $senderdata=User::select('name','lname')->where('id',$metaid->userid)->first();
               $receiver_iddata=User::select('name','lname')->where('id',$receiver_id)->first();
                 if($metaid->userid==$receiver_id)
                 {
                    return false;
                 }

              $alertdata=Alert_notification::where($where)->first();
              $alertdata->date=$date;
            //   $checkArr=User::select('ride_notification','messages_notification','news_deals_stuff_notification')->where('id',$receiver_id)->first();
            
            // if($checkArr->ride_notification===0 || $checkArr->ride_notification===null)
            //   {
            //       return false;
            //   } 
           
            // if($checkArr->messages_notification===0 || $checkArr->messages_notification===null)
            //   {
            //      return false;
            //   }
            
            //  if($checkArr->news_deals_stuff_notification===0 || $checkArr->news_deals_stuff_notification===null)
            //   {
            //      return false;
            //   }

              $title='Hi '.@$receiver_iddata->name .' '. @$receiver_iddata->lname;
              $desc='You have new ride available on your searched route !';
         
                $arrayNames=array('user_id'=>$receiver_id,'title'=>$title,'description'=>$desc);
                $getting_token_info=Token::select('*')->where('user_id',$receiver_id)->get()->toArray();

                if(!empty($getting_token_info) && count($getting_token_info)>0)
                {
                $API_SERVER_KEY="AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
                foreach ($getting_token_info as $key => $valuedata) {
                $registrationIds=$valuedata['token'];
              $data=(object)array('type'=>'New ride','ridedata'=>$alertdata);
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
                // return  $response=['status'=>'success','message'=>'test ','rss'=>$arrayName]; 
                return $arrayName;


    }
public function booking(Request $request)
{
   $response=[];
   $updateRideArr1='';
    if(!empty($request->passenger_id) && !empty($request->passenger_count) && !empty($request->ride_id))
       {

              // $check_bags=DB::table('add_rides')->where('id',$request->ride_id)->first();
              $check_bags=Ride::select('*')->where('id',$request->ride_id)->get()->first();

              $sbag=$check_bags['pending_small_bag']-intval($request->small_bag);
              $hn_bag=$check_bags['pending_hand_bag']-intval($request->hand_bag);
              $regular_bag=$check_bags['pending_regular_bag']-intval($request->regular_bag);
              $oversize_bag=$check_bags['pending_oversize_bag']-intval($request->oversize_bag);
              



              $updateRideArr1=array('pending_small_bag'=>$sbag,
                'pending_hand_bag'=>$hn_bag,
                'pending_regular_bag'=>$regular_bag,
                'pending_oversize_bag'=>$oversize_bag);
          

                $checkRide=array('passenger_id'=>$request->passenger_id,
                'ride_id'=>$request->ride_id,
                'driver_id'=>$request->driver_id); 
                $counts=Apply_ride::where($checkRide)->count();
                $Apply_rideid=Apply_ride::select('id')->where($checkRide)->get()->first();
                $ridersRr=Ride::select('id','instant_booking')->where('id',$request->ride_id)->get()->first();
                $instant=$ridersRr->instant_booking;
                $passscount= Ride::select('passenger_count','total_passenger','price')->where('id',$request->ride_id)->get()->first();
               $total_counss= $request->passenger_count+$passscount->total_passenger;
        if($instant==1)  //if instant booking true 
           {
      
           //  if  else //new booking 
             

                    if($passscount->passenger_count == $passscount->total_passenger)
                    {    
                             $lang_mess=$request->lang=='en' ? 'All sheet are booked try  for new ride' : 'Todas las hojas están reservadas para intentar un nuevo viaje.';
        
                       $response=['status'=>'failure','message'=>$lang_mess]; 
                    }
                    else
                    { 
                        $addcount=$request->passenger_count+$passscount->total_passenger;

                            if($total_counss > $passscount->passenger_count)
                            {       
                                $lang_mess=$request->lang=='en' ? 'You are exceeded limit' : 'Estás excedido el límite';
                                 
                               $response=['status'=>'failure','message'=>$lang_mess]; 
                            }
                            else
                            {
                                $newprice=$passscount->price*$request->passenger_count;
                                $boookArr=array('passenger_id'=>$request->passenger_id,
                                'passenger_count'=>$request->passenger_count,
                                'ride_id'=>$request->ride_id,
                                'driver_id'=>$request->driver_id,
                                'price'=>$newprice,
                                'small_bag'=>@$request->small_bag,
                                'hand_bag'=>@$request->hand_bag,
                                'regular_bag'=>@$request->regular_bag,
                                'oversize_bag'=>@$request->oversize_bag,
                                'instant_status'=>1,
                                );  
                                $updateRideArr=array('total_passenger'=>$addcount);
                                  $res=Apply_ride::create($boookArr);
                                    $res=Ride::where('id',$request->ride_id)->update($updateRideArr);
                                    if($res)
                                    {       
                                        $notdata=$this->get_single_ridedata($request->ride_id,$request->driver_id);
                                             $this->send_notification1($request->passenger_id,$request->driver_id,'Booked Your Ride RD$'.$newprice,'Ride',$notdata);
                                            $notinfo=User::select('name as fname','lname')->where('id',$request->driver_id)->first();

                                             $noti_arr=array('user_id'=>$request->passenger_id,'messages'=>'Booked a Ride with' .$notinfo->fname.' '.$notinfo->lname);
                                              Notifications::create($noti_arr);
                                                  $lang_mess=$request->lang=='en' ? 'Booking Added Successfully' : 'Reserva añadida con éxito';
                                             Ride::where('id',$request->ride_id)->update($updateRideArr1);
                                             $response=['status'=>'success','message'=>$lang_mess]; 
                                    }
                                    else
                                    {

                                         $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                                            $response=['status'=>'failure','message'=>$lang_mess]; 
                                    }
                            }
                    }

            
          }
         else  // if intant booking false driver will accept request 
         {

              
             // $passscount= Ride::select('passenger_count','total_passenger','price')->where('id',$request->ride_id)->get()->first();
                          // 
                         if($passscount->passenger_count==$passscount->total_passenger)
                     {         

                      $lang_mess=$request->lang=='en' ? 'All sheet are booked try  for new ride.' : 'Todas las hojas están reservadas para intentar un nuevo viaje';
   
                       $response=['status'=>'failure','message'=>$lang_mess]; 
                    }
                    else
                    { 
                        $addcount=$request->passenger_count+$passscount->total_passenger;

                            // if($request->passenger_count > $passscount->passenger_count)
                               if($total_counss > $passscount->passenger_count)

                            {  
                            $lang_mess=$request->lang=='en' ? 'You are exceeded limit' : 'Estás excedido el límite';
                                      
                               $response=['status'=>'failure','message'=>$lang_mess]; 
                            }
                            else
                            {
                                $newprice=$passscount->price*$request->passenger_count;
                                $boookArr=array('passenger_id'=>$request->passenger_id,
                                'passenger_count'=>$request->passenger_count,
                                'ride_id'=>$request->ride_id,
                                'driver_id'=>$request->driver_id,
                                'price'=>$newprice,
                                'small_bag'=>@$request->small_bag,
                                'hand_bag'=>@$request->hand_bag,
                                'regular_bag'=>@$request->regular_bag,
                                'oversize_bag'=>@$request->oversize_bag,
                                'instant_status'=>0,
                                'cancel_booking'=>'true'
                                );  
                                 // $updateRideArr=array('total_passenger'=>$addcount);
                                 $res=Apply_ride::create($boookArr); 
                                 if($res)
                                    {   
                                      // Ride::where('id',$request->ride_id)->update($updateRideArr);
                                            $notdata=$this->get_single_ridedata($request->ride_id,$request->driver_id);
                                            $this->send_notification1($request->passenger_id,$request->driver_id,' has sent you request for a ride for  RD$'.$newprice,'Ride',$notdata);
                                            $notinfo=User::select('name as fname','lname')->where('id',$request->driver_id)->first();
                                         
                                             $noti_arr=array('user_id'=>$request->passenger_id,'messages'=>' has sent you request for a ride for '.$notinfo->fname.' '.$notinfo->lname);
                                              Notifications::create($noti_arr);
                                          $lang_mess=$request->lang=='en' ? 'Hold on ! While your driver accept your request' : 'Esperar ! Mientras su conductor acepta su solicitud';

                                             $response=['status'=>'success','message'=>$lang_mess]; 
                                    }
                                    else
                                    {

                                       $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                                            $response=['status'=>'failure','message'=>$lang_mess]; 
                                    }

                                }

                                
                            }
            }     
        }
        else
         {
           $lang_mess=$request->lang=='en' ? 'passenger_id or passenger_count or ride_id must' : 'id_pasajero o id_pasajero o id_viaje deben';

           $response=['status'=>'failure','message'=>$lang_mess]; 
         }
       return response()->json($response);
    }

    public function vehicle_brand(Request $request)
    {
        $data=Imported_brand::select('brand_name')->distinct('brand_name')->get();
        foreach($data as $key=>$value)
        {
            $data[$key]['model']=Imported_brand::select('model_name')
                    ->where('brand_name',$value->brand_name)->get()->toArray();
        }
        $datad=Color::select('id','color_name')->get()->toArray();

        $response=['status'=>'success','message'=>'fetch','data'=>$data,'color'=>$datad];
      return response()->json($response);
    }
    public function vehicle_list(Request $request)
    {
      if(!empty($request->userid))
      {  
        $is_verifyId=User::where('id',$request->userid)->pluck('is_verifyId')->first();
           $data=Vehicle::select('*')->where('user_id',$request->userid)->get()->toArray();
       $lang_mess=$request->lang=='en' ? 'fetch Successfully data' : 'obtener datos con éxito';
 
           $response=['status'=>'success','message'=>$lang_mess,'data'=>$data,'is_verifyId'=>$is_verifyId];
      }
      else
      {
          $lang_mess=$request->lang=='en' ? 'User id must' : 'La identificación del usuario debe';

          $response=['status'=>'failure','message'=>'User id must','data'=>[],'is_verifyId'=>''];

      }
     return response()->json($response);


    }
    public function delete_vehicle(Request $request)
    {

        if(!empty($request->vehicle_id))
        {
              // $del_rec1=Vehicle::where('user_id',$request->vehicle_id)->first();
              $del_res=Vehicle::where('id',$request->vehicle_id)->delete();
              if($del_res)
              {
                // $notinfo=User::select('fname','lname')->where('id',$userid)->first();
                // $noti_arr=array('user_id'=>$del_rec1->user_id,'messages'=>'Delete Vehicle');
                  // Notifications::create($noti_arr);

              $lang_mess=$request->lang=='en' ? 'Vehicle Delete Successfully' : 'Eliminar vehículo con éxito';

                 $response=['status'=>'success','message'=>$lang_mess];
               }
              else
              {

                   $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                 $response=['status'=>'failure','message'=>$lang_mess];

              }
        }
        else
        {
              $response=['status'=>'failure','message'=>' id must'];
 
        }
             return response()->json($response);

    }

    public function Delete_applybooking(Request $request)
    {
        
        $Arr=array('passenger_id'=>$request->passenger_id,'ride_id'=>$request->ride_id);
        $updateArr=array('cancel_booking'=>'Cancelled');
        $delres=Apply_ride::where($Arr)->update($updateArr);
       $meta=Ride::select('userid')->where('id',$request->ride_id)->first();
        if($delres)
        {

          if($meta['instant_status']===1)
           {
              $metadata=Apply_ride::where($Arr)->get()->first();
              $check_bags=Ride::select('*')->where('id',$request->ride_id)->get()->first();
              $sbag=$check_bags['pending_small_bag']-$metadata['small_bag'];
              $hn_bag=$check_bags['pending_hand_bag']-$metadata['hand_bag'];
              $regular_bag=$check_bags['pending_regular_bag']-$metadata['regular_bag'];
              $oversize_bag=$check_bags['pending_oversize_bag']-$metadata['oversize_bag'];
                $updateRideArr1=array('pending_small_bag'=>$sbag,
                'pending_hand_bag'=>$hn_bag,
                'pending_regular_bag'=>$regular_bag,
                'pending_oversize_bag'=>$oversize_bag);
                 Ride::where('id',$request->ride_id)->update($updateRideArr1);
           }  
         

                 $this->send_notification1($meta['userid'],$request->passenger_id,'Canceled Your Ride','delete Ride');
                $notinfo=User::select('name as fname','lname')->where('id',$meta['userid'])->first();
                 $noti_arr=array('user_id'=>$request->passenger_id,'messages'=>'Cancel  Ride of '.$notinfo->fname.''.$notinfo->lname);
                  Notifications::create($noti_arr);

                      $lang_mess=$request->lang=='en' ? 'Ride Cancelled Successfully' : 'Viaje cancelado con éxito';

            $response=['status'=>'success','message'=>$lang_mess];

        }
        else
        {

           $lang_mess=$request->lang=='en' ? 'Ride Cancelled Successfully' : 'Viaje cancelado con éxito';

            $response=['status'=>'failure','message'=>$lang_mess];

        }
         return response()->json($response);   
    }

    public function Accept_ride(Request $request)
    {
                $Arr=array('passenger_id'=>$request->passenger_id,'ride_id'=>$request->ride_id);
             // $check_bags=DB::table('add_rides')->where('id',$request->ride_id)->first();
             //  $sbag=$request->small_bag-$check_bags->pending_small_bag;
             //  $hn_bag=$request->hand_bag-$check_bags->pending_hand_bag;
             //  $regular_bag=$request->regular_bag-$check_bags->pending_regular_bag;
             //  $oversize_bag=$request->oversize_bag-$check_bags->pending_oversize_bag;

            $check_bags=Ride::select('*')->where('id',$request->ride_id)->get()->first();
            $check_bags1=Apply_ride::select('*')->where($Arr)->get()->first();
              $sbag=$check_bags['pending_small_bag']-intval($check_bags1['small_bag']);
              $hn_bag=$check_bags['pending_hand_bag']-intval($check_bags1['hand_bag']);
              $regular_bag=$check_bags['pending_regular_bag']-intval($check_bags1['regular_bag']);
              $oversize_bag=$check_bags['pending_oversize_bag']-intval($check_bags1['oversize_bag']);
              
              
              $updateRideArr1=array('pending_small_bag'=>$sbag,
                'pending_hand_bag'=>$hn_bag,
                'pending_regular_bag'=>$regular_bag,
                'pending_oversize_bag'=>$oversize_bag);

          $passscount1= Ride::select('passenger_count','total_passenger','price','userid')->where('id',$request->ride_id)->get()->first();  //ride 
              $countuser= Apply_ride::select('passenger_count','id')->where('passenger_id',$request->passenger_id)->get()->first(); //apply ride 
               $total_passenger_count=$passscount1->total_passenger+$countuser->passenger_count;   
      
                if($total_passenger_count > $passscount1->passenger_count )
                    {
                         
                    $lang_mess=$request->lang=='en' ? 'You are exceeded limit1' : 'Estás excedido el límite';
            
                      $response=['status'=>'failure','message'=>$lang_mess]; 
                      
                    }
                    elseif(($total_passenger_count  <= $passscount1->passenger_count ))
                    {
                                    // $checkRide['id']=$countuser['id'];
                                    $updateRideArr=array('total_passenger'=>$total_passenger_count);
                                    $boookArr=array('instant_status'=>1,'confirm_book'=>1);
                                    $res=Apply_ride::where($Arr)->update($boookArr);
                                    $res=Ride::where('id',$request->ride_id)->update($updateRideArr);
                            if($res)
                            {   

                             $this->send_notification1($passscount1['userid'],$request->passenger_id,'Accept Your Ride','acept Ride');
                       $notinfo=User::select('name as fname','lname')->where('id',$request->passenger_id)->first();

                                $noti_arr=array('user_id'=>$passscount1['userid'],'messages'=>'Accept Ride Request Of'.$notinfo->fname.''.$notinfo->lname);
                                Notifications::create($noti_arr);
                                Ride::where('id',$request->ride_id)->update($updateRideArr1);

                        $lang_mess=$request->lang=='en' ? 'Booking update Successfully' : 'Actualización de reserva con éxito';

                            $response=['status'=>'success','message'=>$lang_mess]; 
                            }
                            else
                            {

                           $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                            $response=['status'=>'failure','message'=>$lang_mess]; 
                            }
                    }else
                    {

                     $lang_mess=$request->lang=='en' ? 'All sheet are booked try  for new ride1' : 'Todas las hojas están reservadas para probar un nuevo viaje';
                                      
                       $response=['status'=>'failure','message'=>$lang_mess]; 
                    }
                 return response()->json($response);
    }
    public function postal_address(Request $request)
    {
        $postal_id=$request->id;
        $userid=$request->userid;
        $postal_address=$request->postal_address;
        $postal_lat=$request->postal_lat;
        $postal_long=$request->postal_long;

        $postatArr=array('postal_address'=>$postal_address,
                         'postal_lat'=>$postal_lat,
                         'postal_long'=>$postal_long);

      if(empty($postal_id))
       {
            $postatArr['userid']=$userid;
            $postatArr['created_at']=date('Y-m-d H:i:s');
            $res=Postal_address::create($postatArr);
            if($res)
            {

                $noti_arr=array('user_id'=>$userid,'messages'=>'Add Postal Address');
                Notifications::create($noti_arr);

            $lang_mess=$request->lang=='en' ? 'Added Successfully' : 'Agregado exitosamente';

            $response=['status'=>'success','message'=>$lang_mess]; 
            }
            else
            {

         $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again1' : 'Algo más está mal, inténtalo de nuevo1';

            $response=['status'=>'failure','message'=>$lang_mess]; 
            }
        }
        else
        {
          
            $where=array('id'=>$postal_id,'userid'=>$userid);
           $postatArr['updated_at']=date('Y-m-d H:i:s');
            $res=Postal_address::where($where)->update($postatArr);
            if($res)
            {

                $noti_arr=array('user_id'=>$userid,'messages'=>'Update Postal Address');
                Notifications::create($noti_arr);

     $lang_mess=$request->lang=='en' ? 'Updated Successfully' : 'Actualizado con éxito';

            $response=['status'=>'success','message'=>$lang_mess]; 

            }
            else
            {

       $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again2' : 'Algo más está mal, inténtalo de nuevo2';

            $response=['status'=>'failure','message'=>$lang_mess]; 

            }
 
        }
         return response()->json($response);
    }

    public function postal_addresslist(Request $request)
    {
    
       $data=Postal_address::select('*')->where('userid',$request->userid)->get()->toArray();
       if($data)
       {          

     $lang_mess=$request->lang=='en' ? 'Data Found' : 'Datos encontrados';

         $response=['status'=>'success','message'=>$lang_mess,'data'=>$data]; 
       }
       else
       {

     $lang_mess=$request->lang=='en' ? 'Data Not Found' : 'Datos no encontrados';

         $response=['status'=>'success','message'=>$lang_mess,'data'=>[]]; 
       }

     return response()->json($response);


    }

    public function delete_booking(Request $request)//user end delete booking
    {
        $where=array('passenger_id'=>$request->passenger_id,'ride_id'=>$request->ride_id);
        // $rado_count=Apply_ride::where('ride_id',$request->ride_id)->where('cancel_booking','true')->count();

        if(!empty($request->passenger_id) && !empty($request->ride_id))
        {  
             $check_bags=DB::table('add_rides')->where('id',$request->ride_id)->first();
             $instant_status=Apply_ride::select('*')->where($where)->first()->toArray();
           
           if($check_bags->pending_small_bag !==$check_bags->small_bag && $check_bags->pending_small_bag!==$check_bags->hand_bag && $check_bags->pending_small_bag!==$check_bags->regular_bag && $check_bags->pending_small_bag!==$check_bags->regular_bag && $check_bags->pending_small_bag!==$check_bags->oversize_bag)
           {
               $sbag=$instant_status['small_bag']+$check_bags->pending_small_bag;
              $hn_bag=$instant_status['hand_bag']+$check_bags->pending_small_bag;
              $regular_bag=$instant_status['regular_bag']+$check_bags->pending_small_bag;
              $oversize_bag=$instant_status['oversize_bag']+$check_bags->pending_small_bag;
               $new_passengerArr=array('pending_small_bag'=>$sbag,
                'pending_hand_bag'=>$hn_bag,
                'pending_regular_bag'=>$regular_bag,
                'pending_oversize_bag'=>$oversize_bag);
           }
           
            
        
              $updateArr=array('cancel_booking'=>'Cancelledby');

           
         $ride_data=Ride::select('userid','passenger_count','total_passenger')->where('id',$instant_status['ride_id'])->first()->toArray();  
           if($instant_status['instant_status']==1)  //this is instant booking 
           {
             $updateArr['confirm_book']=0;

                      $total_passenger=$ride_data['total_passenger']-$instant_status['passenger_count'];

                      $new_passengerArr=array('total_passenger'=>$total_passenger);

                      Ride::where('id',$instant_status['ride_id'])->update($new_passengerArr);

                      $res=Apply_ride::where($where)->update($updateArr);

                      if($res)
                      {
                         $this->send_notification1($request->passenger_id,$ride_data['userid'],'Canceled Your Ride','Cancel Ride');
                        $notinfo=User::select('name as fname','lname')->where('id',$ride_data['userid'])->first();

                         $noti_arr=array('user_id'=>$ride_data['userid'],'messages'=>'Cancel  Ride'.$notinfo->fname.''.$notinfo->lname);
                         Notifications::create($noti_arr);
                      
                      
                    $metadata=Apply_ride::where($where)->get()->first();
                    $check_bags=Ride::select('*')->where('id',$request->ride_id)->get()->first();
                    $sbag=$check_bags['pending_small_bag']+$metadata['small_bag'];
                    $hn_bag=$check_bags['pending_hand_bag']+$metadata['hand_bag'];
                    $regular_bag=$check_bags['pending_regular_bag']+$metadata['regular_bag'];
                    $oversize_bag=$check_bags['pending_oversize_bag']+$metadata['oversize_bag'];


                    $updateRideArr1=array('pending_small_bag'=>$sbag,
                    'pending_hand_bag'=>$hn_bag,
                    'pending_regular_bag'=>$regular_bag,
                    'pending_oversize_bag'=>$oversize_bag);
                    Ride::where('id',$request->ride_id)->update($updateRideArr1);
                   
                         // Apply_ride::where($where)->delete();
                        $lang_mess=$request->lang=='en' ? 'Ride Canceled Successfully' : 'Cancelar con éxito';


                         $response=['status'=>'success','message'=>$lang_mess]; 
                      }
                      else
                      {

                         $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';
                         $response=['status'=>'failure','message'=>$lang_mess]; 
                      }
           }
           elseif($instant_status['instant_status']==0) //this is not  instant booking 
           {
                $updateArr['confirm_book']=0;
               $res=Apply_ride::where($where)->update($updateArr);
                   if($res)
                   {

                  $this->send_notification1($request->passenger_id,$ride_data['userid'],'Canceled Your Ride','Cancel Ride');
                        $notinfo=User::select('name as fname','lname')->where('id',$ride_data['userid'])->first();

                    $noti_arr=array('user_id'=>$ride_data['userid'],'messages'=>'Cancel Ride'.$notinfo->fname.''.$notinfo->lname);
                         Notifications::create($noti_arr);

                    $metadata=Apply_ride::where($where)->get()->first();
                    $check_bags=Ride::select('*')->where('id',$request->ride_id)->get()->first();
                    $sbag=$check_bags['pending_small_bag']+$metadata['small_bag'];
                    $hn_bag=$check_bags['pending_hand_bag']+$metadata['hand_bag'];
                    $regular_bag=$check_bags['pending_regular_bag']+$metadata['regular_bag'];
                    $oversize_bag=$check_bags['pending_oversize_bag']+$metadata['oversize_bag'];


                    $updateRideArr1=array('pending_small_bag'=>$sbag,
                    'pending_hand_bag'=>$hn_bag,
                    'pending_regular_bag'=>$regular_bag,
                    'pending_oversize_bag'=>$oversize_bag);
                    Ride::where('id',$request->ride_id)->update($updateRideArr1);

                     $lang_mess=$request->lang=='en' ? 'Ride Canceled Successfully' : 'Cancelar con éxito';

                     $response=['status'=>'success','message'=>$lang_mess]; 
                   }
                   else
                   {


                    $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                       $response=['status'=>'failure','message'=>$lang_mess]; 
                   }
           }
           else
           {
                                $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

              $response=['status'=>'failure','message'=>$lang_mess]; 

           }
           
    }
    else
    {
      $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

      $response=['status'=>'failure','message'=> $lang_mess]; 
    }
    return response()->json($response);

    }

    public function sendsms()
    {  
            $otp=rand(9999,1000);
            $account_sid = 'AC393748e4f5bf292b72bb09897ed9d04b';
            // $account_sid = 'AC393748e4f5bf292b72bb09897ed9d04b';
            // $auth_token = 'C027d3a1e91b62940fa95604959921638';
            $auth_token = '7612f0fb2dc5c14fbe6d2661165e4062';

            $url = "https://api.twilio.com/2010-04-01/Accounts/$account_sid/SMS/Messages";
            $to = "+919917827522";
            $from = "+12232176267"; // twilio trial verified number
            $body = "Your OTP".' '.$otp;
            $data = array (
            'From' => $from,
            'To' => $to,
            'Body' => $body,
            );
            $post = http_build_query($data);
            $x = curl_init($url );
            curl_setopt($x, CURLOPT_POST, true);
            curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($x, CURLOPT_USERPWD, "$account_sid:$auth_token");
            curl_setopt($x, CURLOPT_POSTFIELDS, $post);
            $y = curl_exec($x);
            curl_close($x);

            var_dump($post);
            var_dump($y);
    }
    public function updateprofileimg(Request $request)
    {
       
        $userimage=$request->file('profile_img');
        if($request->file('profile_img'))
            {
                // $userimage='';
            $name_gen=hexdec(uniqid());
            $img_ext=strtolower($request->file('profile_img')->getClientOriginalExtension());
            $img_name=$name_gen.'.'.$img_ext;
            $up_location='public/image/userimage/';
            // return $up_location;
            $lastimage=$up_location.$img_name;
            // return $lastimage;
            $userimage->move($up_location,$img_name);


     $lang_mess=$request->lang=='en' ? 'Update profile img' : 'Actualizar imagen de perfil';

             $response=['status'=>'success','message'=>$lang_mess,'url'=>$lastimage]; 

            }
            else
            {

              $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again ' : 'Algo más está mal, inténtalo de nuevo.';

              $response=['status'=>'failure','message'=>$lang_mess,'url'=>'']; 
 
            }
         return response()->json($response);
    }
                public function history(Request $request)
                {

                        $date=date('Y-m-d');
                        if(!empty($request->userid))
                        {
                        $data=RecentHistory::select('*')->where('user_id',$request->userid)->orderBy('id','desc')->get()->toArray();
                        if($data)
                        {
                                $today=date('Y-m-d');
                                $today=strtotime($today);
                                foreach($data as $key=>$value)
                                {
                                $dbdate=strtotime($value['date']);
                                if($dbdate < $today)
                                {
                                $data[$key]['showDate']=0;
                                }  
                                else
                                {
                                $data[$key]['showDate']=1;
                                }
                                } 

                          $lang_mess=$request->lang=='en' ? 'Search history found' : 'Historial de búsqueda encontrado';

                        $response=['status'=>'success','message'=> $lang_mess,'data'=>$data]; 

                        }
                        else
                        {



                       $lang_mess=$request->lang=='en' ? 'No Search history' : 'Sin historial de búsqueda';

                        $response=['status'=>'success','message'=>$lang_mess,'data'=>[]]; 

                        }
                        }
                        else
                        {

                        $lang_mess=$request->lang=='en' ? 'No Data Found' : 'Datos no encontrados';

                        $response=['status'=>'failure','message'=>$lang_mess,'data'=>[]]; 

                        }
                        return response()->json($response);
                }
                public function complete_ride(Request $request)
                {
                    $ride_id=$request->ride_id;
                    $driver_id=$request->driver_id;
                   
                    $Arr=array('id'=>$ride_id,'userid'=>$driver_id);
                    $updateArr=array('complete_status'=>1);

                    $res=Ride::where($Arr)->update($updateArr);
                    if($res)
                    {

                        $notinfo=User::select('name as fname','lname')->where('id',$request->driver_id)->first();

                        $datag=Apply_ride::select('*')->where('ride_id',$ride_id)->get()->toArray();

                        foreach($datag as $value)
                        {
                        $this->send_notification1($driver_id,$value['passenger_id'],'Ride Successfully Completed','complete Ride');

                        }
                        $noti_arr=array('user_id'=>$request->driver_id,'messages'=>'Completed Ride');
                         Notifications::create($noti_arr);


                              $lang_mess=$request->lang=='en' ? 'Your ride Successfully Completed' : 'Su viaje completado con éxito';

                      $response=['status'=>'success','message'=>$lang_mess]; 

                    }
                    else
                    {


                     $lang_mess=$request->lang=='en' ? 'Something else wrong please try again' : 'Algo más está mal, inténtalo de nuevo.';

                      $response=['status'=>'failure','message'=>$lang_mess]; 

                    }

                        return response()->json($response);

                }
                public function send_rating_reviews(Request $request)
                {
                
                    $reviews=array('sender_id'=>$request->sender_id,
                    'receiver_id'=>$request->receiver_id,
                    'rating'=>$request->rating,
                    'reviews'=>$request->reviews,
                    'ride_id'=>$request->ride_id,
                    'created_at'=>date('Y-m-d H:i:s'));

                    if(!empty($request->sender_id) && !empty($request->receiver_id))
                    {
                    $res=Rating_reviews::create($reviews); 
                    if($res)
                    {
                       $this->send_notification1($request->sender_id,$request->receiver_id,'You received review from',$type1='rating',$data1='');
                     $noti_arr=array('user_id'=>$request->sender_id,'messages'=>'Rating Reviews Add');
                     Notifications::create($noti_arr);
                    $lang_mess=$request->lang=='en' ? 'Rating Reviews Added Successfully' : 'Reseñas de calificación añadidas con éxito';
                    $response=['status'=>'success','message'=>$lang_mess]; 
                    }
                    else
                    {
                    $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';
                    $response=['status'=>'failure','message'=>$lang_mess]; 
                    }  

                    }
                    else
                    {

            $lang_mess=$request->lang=='en' ? 'sender or receiver must' : 'emisor o receptor debe';

                    $response=['status'=>'failure','message'=>$lang_mess]; 

                    }

                 return response()->json($response);

                }


 

                public function rating_reviews_list(Request $request)
                {
                     
                    if($request->type=='sender')
                    {

                        $res=Rating_reviews::join('users','users.id','=','rating_reviews.receiver_id')
                        ->select('rating_reviews.rating','rating_reviews.reviews','name','lname','image','rating_reviews.created_at')
                        ->where('sender_id',$request->uid)
                        ->get()->toArray();
                        // foreach($res as $key=>$val)
                        // {         
                        // $vg= Rating_reviews::where('sender_id',$val['sender_id'])->avg('rating');
                        // $res[$key]['avg']=round($vg,0);
                        // }

                        if($res)
                        {

                         $lang_mess=$request->lang=='en' ? 'Fetch' : 'Ha podido recuperar';

                        $response=['status'=>'success','message'=>$lang_mess,'data'=>$res]; 
                        }
                        else
                        {


                        $lang_mess=$request->lang=='en' ? 'No Rating & Reviews Found' : 'No se encontraron calificaciones ni reseñas';

                        $response=['status'=>'success','message'=>$lang_mess,'data'=>[]]; 
                        }

                    }
                    elseif($request->type=='receiver')
                    {
                            $res=Rating_reviews::join('users','users.id','=','rating_reviews.sender_id')
                             ->select('rating_reviews.rating','rating_reviews.reviews','name','lname','image','rating_reviews.created_at')
                             ->where('receiver_id',$request->uid)
                             ->get()->toArray();
                           
                             if($res)
                             {
                                        $lang_mess=$request->lang=='en' ? 'Fetch' : 'Ha podido recuperar';

                                  $response=['status'=>'success','message'=>$lang_mess,'data'=>$res]; 
                             }
                             else
                             {

                                   $lang_mess=$request->lang=='en' ? 'No Rating & Reviews Found' : 'No se encontraron calificaciones ni reseñas';

                               $response=['status'=>'success','message'=>$lang_mess,'data'=>[]]; 
    
                             }
                    }

                     return response()->json($response);

                }


     public function send_notification(Request $request,$userid=null,$receiver_id=null,$desc=null)
        {

              $userid=$request->userid;
              $receiver_id=$request->receiver_id;
              // $title=$request->title;
              $desc=$request->desc;
              $type='Chat';
              $senderdata=User::select('id','name','lname','image')->where('id',$userid)->first();
              $receiver_iddata=User::select('name','lname','image')->where('id',$receiver_id)->first();

                $checkArr=User::select('ride_notification','messages_notification','news_deals_stuff_notification')->where('id',$receiver_id)->first();

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
              $title=$senderdata->name .' '. $senderdata->lname;
              // $gettokn_Arr=array('user_id'=>$request->userid);

                $arrayNames = array('user_id' =>$receiver_id,'title'=>$title,'description'=>$desc);
                // $result2=$this->init2()->insert('notifications_history',$arrayNames);
                // $userData=$this->init2()->device_token_fetch($userid,$utype);
              $getting_token_info=Token::select('*')->where('user_id',$receiver_id)->get()->toArray();

                if(!empty($getting_token_info) && count($getting_token_info)>0)
                {
                $API_SERVER_KEY="AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
                foreach ($getting_token_info as $key => $valuedata) {
                $registrationIds=$valuedata['token'];

                $data=(object)array('type'=>'chat','id'=>$senderdata->id,'image'=>$senderdata->image,'name'=>$senderdata->name,'lname'=>$senderdata->lname);
                $msg = array
                (
                'body'  => $desc,
                // 'payload'=>'ttest',
                'title' => $title,
                'icon'  => 'myicon',
                'sound' => 'mySound'
                );
                $fields = array
                (
                'to' => $registrationIds,
                'notification'=> $msg,
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
                  // $response=['status'=>'success','message'=>'test ','rss'=>$arrayName]; 
                return $arrayName;


    }
     public function send_notification1($userid=null,$receiver_id=null,$desc=null,$type1=null,$data1=null)
        {
         


              // $userid=$request->userid;
              // $receiver_id=$request->receiver_id;
              // // $title=$request->title;
              // $desc=$request->desc;
              $senderdata=User::select('name','lname')->where('id',$userid)->first();
              $receiver_iddata=User::select('name','lname')->where('id',$receiver_id)->first();

              $checkArr=User::select('ride_notification','messages_notification','news_deals_stuff_notification')->where('id',$receiver_id)->first();
            
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

              $title='Ride';
              // $title=$senderdata->name .' '. $senderdata->lname;
              if($type1=='rating')
              {
                 $desc=$desc.' '.$senderdata->name .' '. $senderdata->lname.' ';
              }
              else
              {

              $desc=$senderdata->name .' '. $senderdata->lname .' '.$desc;
              }

              if($type1=='New ride')
              {
                $desc=$desc;
              }
              // $gettokn_Arr=array('user_id'=>$request->userid);
                  // $arrayNames='';
                $arrayNames=array('user_id'=>$receiver_id,'title'=>$title,'description'=>$desc);
                // $result2=$this->init2()->insert('notifications_history',$arrayNames);
                // $userData=$this->init2()->device_token_fetch($userid,$utype);
              $getting_token_info=Token::select('*')->where('user_id',$receiver_id)->get()->toArray();

                if(!empty($getting_token_info) && count($getting_token_info)>0)
                {
                $API_SERVER_KEY="AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
                foreach ($getting_token_info as $key => $valuedata) {
                $registrationIds=$valuedata['token'];
              $data=(object)array('type'=>$type1,'ridedata'=>$data1);
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
                // return  $response=['status'=>'success','message'=>'test ','rss'=>$arrayName]; 
                return $arrayName;


    }
    public function delete_history(Request $request)
    {
      
        if(!empty($request->id) && !empty($request->userid))
        {

             $delArr=array('id'=>$request->id,'user_id'=>$request->userid);
             $res=RecentHistory::where($delArr)->delete();   
             if($res)
             {
               $lang_mess=$request->lang=='en' ? 'Delete Successfully' : 'Eliminar con éxito';

                  $response=['status'=>'success','message'=>$lang_mess]; 

             }  
             else
             {

              $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                 $response=['status'=>'failure','message'=>$lang_mess];
             }
        } 
        else
        {
         $response=['status'=>'failure','message'=>'id must ']; 

        }
          return response()->json($response);

    }

    public function Faq(Request $request)
    {
        $res=Faq::select('questions','answers','id')->orderBy('id','desc')->get()->toArray();
        if($res)
        {

     $lang_mess=$request->lang=='en' ? 'Data Fetch ' : 'Obtención de datos';

         $response=['status'=>'success','message'=>$lang_mess, 'data'=>$res]; 

        }
        else
        {

                 $lang_mess=$request->lang=='en' ? 'Data  Not Fetch ' : 'Datos no obtenidos';

           $response=['status'=>'failure','message'=>$lang_mess, 'data'=>[]];
        }
          return response()->json($response);


    }
    public function logout(Request $request)
    {

          $lang_mess=$request->lang=='en' ? 'Logout Successfully ' : 'Cerrar sesión con éxito';

             $token= $request->token;
             // if($token==undefined)
            if(!empty($request->token) && !empty($request->userid))
            {
            $where=array('token'=>$token,'user_id'=>$request->userid);
            $res=Token::where($where)->delete();
            if($res)
            {


            $response=['status'=>'success','message'=>$lang_mess]; 
            }
            else
            {
            $response=['status'=>'success','message'=>$lang_mess]; 
            }
            }
            else
            {
            $response=['status'=>'success','message'=>$lang_mess]; 
            }
        return response()->json($response);


    }
    public function verify_status(Request $request)
    {    
         $now=date('Y-m-d');
        $today=strtotime($now);
        if(!empty($request->userid))
        {
            $Verify_idata=[];
            $Verify_idata=Verify_id::where('user_id',$request->userid)->first();
            $approved=User::select('is_verifyId')->where('id',$request->userid)->first();

      
           
            if($Verify_idata && !empty($Verify_idata))
            {

            $Verify_idata=array('insurance'=>$Verify_idata['insurance']==null ? '' :  $Verify_idata['insurance'],
            'id_proof'=>$Verify_idata['id_proof']==null ? '' :  $Verify_idata['id_proof'],
            'id_proff_status'=>$Verify_idata['id_proff_status'],
            'driving_licence_status'=>$Verify_idata['driving_licence_status'],
            'vehicle_plate_status'=>$Verify_idata['vehicle_plate_status'],
            'insurance_status'=>$Verify_idata['insurance_status'],
            'vehicle_plate'=>$Verify_idata['vehicle_plate']==null ? '' :  $Verify_idata['vehicle_plate'],
            'driving_licence'=>$Verify_idata['driving_licence']==null ? '' :  $Verify_idata['driving_licence'],
            'image'=>$Verify_idata['image']==null ? '' :  $Verify_idata['image'],
            'type'=>$Verify_idata['type']==null ? '' :  $Verify_idata['type'],
            'otp'=>$Verify_idata['otp']==null ? '' :  $Verify_idata['otp'],
            'id_proof_expdate'=>$Verify_idata['id_proof_expdate']==null ? '' :  $Verify_idata['id_proof_expdate'],
            'driving_licence_expdate'=>$Verify_idata['driving_licence_expdate']==null ? '' :  $Verify_idata['driving_licence_expdate'],
            'vehicle_plate_expdate'=>"",
            // 'vehicle_plate_expdate'=>$Verify_idata['vehicle_plate_expdate']==null ? '' :  $Verify_idata['vehicle_plate_expdate'],
            'insurance_expdate'=>$Verify_idata['insurance_expdate']==null ? '' :  $Verify_idata['insurance_expdate'],
            'id_proof_renewdate'=>$Verify_idata['id_proof_renewdate']==null ? '' :  $Verify_idata['id_proof_renewdate'],
            'driving_licence_renewdate'=>$Verify_idata['driving_licence_renewdate']==null ? '' :  $Verify_idata['driving_licence_renewdate'],
            'vehicle_plate_renewdate'=>$Verify_idata['vehicle_plate_renewdate']==null ? '' :  $Verify_idata['vehicle_plate_renewdate'],
            'insurance_renewdate'=>$Verify_idata['insurance_renewdate']==null ? '' :  $Verify_idata['insurance_renewdate']);



             
            // $Verify_idata=array('insurance'=>$Verify_idata['insurance']==null ? '' :  $Verify_idata['insurance'],
            // 'id_proof'=>$Verify_idata['id_proof']==null ? '' :  $Verify_idata['id_proof'],
            // 'id_proff_status'=>$Verify_idata['id_proff_status']==null ? '' :  $Verify_idata['id_proff_status'],
            // 'driving_licence_status'=>$Verify_idata['driving_licence_status']==null ? '' :  $Verify_idata['driving_licence_status'],
            // 'vehicle_plate_status'=>$Verify_idata['vehicle_plate_status']==null ? '' :  $Verify_idata['vehicle_plate_status'],
            // 'insurance_status'=>$Verify_idata['insurance_status']==null ? '' :  $Verify_idata['insurance_status'],
            // 'vehicle_plate'=>$Verify_idata['vehicle_plate']==null ? '' :  $Verify_idata['vehicle_plate'],
            // 'driving_licence'=>$Verify_idata['driving_licence']==null ? '' :  $Verify_idata['driving_licence'],
            // 'image'=>$Verify_idata['image']==null ? '' :  $Verify_idata['image'],
            // 'type'=>$Verify_idata['type']==null ? '' :  $Verify_idata['type'],
            // 'otp'=>$Verify_idata['otp']==null ? '' :  $Verify_idata['otp'],
            // 'id_proof_expdate'=>$Verify_idata['id_proof_expdate']==null ? '' :  $Verify_idata['id_proof_expdate'],
            // 'driving_licence_expdate'=>$Verify_idata['driving_licence_expdate']==null ? '' :  $Verify_idata['driving_licence_expdate'],
            // 'vehicle_plate_expdate'=>$Verify_idata['vehicle_plate_expdate']==null ? '' :  $Verify_idata['vehicle_plate_expdate'],
            // 'insurance_expdate'=>$Verify_idata['insurance_expdate']==null ? '' :  $Verify_idata['insurance_expdate'],
            // 'id_proof_renewdate'=>$Verify_idata['id_proof_renewdate']==null ? '' :  $Verify_idata['id_proof_renewdate'],
            // 'driving_licence_renewdate'=>$Verify_idata['driving_licence_renewdate']==null ? '' :  $Verify_idata['driving_licence_renewdate'],
            // 'vehicle_plate_renewdate'=>$Verify_idata['vehicle_plate_renewdate']==null ? '' :  $Verify_idata['vehicle_plate_renewdate'],
            // 'insurance_renewdate'=>$Verify_idata['insurance_renewdate']==null ? '' :  $Verify_idata['insurance_renewdate']);
           
             // if($Verify_idata['id_proff_status']==0)
             // {
             //    $Verify_idata['id_proff_status']   
             // }

           // foreach($Verify_idata as $value)
           // {

           // }




           $lang_mess=$request->lang=='en' ? 'fetch ' : 'ha podido recuperar';
           $response=['status'=>'success','message'=>$lang_mess,'data'=>$Verify_idata,'is_verifyId'=>$approved->is_verifyId]; 

            }
            else
            {
                $lang_mess=$request->lang=='en' ? 'No result found ' : 'No se han encontrado resultados';
                $response=['status'=>'success','message'=> $lang_mess,'data'=>[],'is_verifyId'=>'0']; 
            }

        }
        else
        {

                  $lang_mess=$request->lang=='en' ? 'No result found ' : 'No se han encontrado resultados';
                  $response=['status'=>'success','message'=>$lang_mess,'data'=>[],'is_verifyId'=>'0']; 
        }
         return response()->json($response);

    }

    public function get_distance(Request $request)
    {    

    $pickup_time=$request->pickup_time;
    $pickup_date=$request->pickup_date;
    $milesss=$this->distance($request->lat,$request->lon,$request->latitude,$request->longitude);
      $thredate=date('H:i',strtotime($pickup_date.' '.$pickup_time));
                            $timess=strtotime($thredate.'+'.$milesss['time']); 
                            $newfff=date('h:i A',$timess);
                            $milesss['estimate_hours']=$newfff;
      if($milesss)
      {

      $lang_mess=$request->lang=='en' ? ' result found ' : 'resultado encontrado';

         $response=['status'=>'success','message'=>$lang_mess,'data'=>$milesss]; 

      }
      else
      {

              $lang_mess=$request->lang=='en' ? 'No result found ' : 'No se han encontrado resultados';

          $response=['status'=>'success','message'=>$lang_mess,'data'=>[]]; 

      }
           return response()->json($response);

      

    }

    public function push_notification(Request $request)
    {
        $ride_notification=$request->ride_notification;
        $messages_notification=$request->messages_notification;
        $news_deals_stuff_notification=$request->news_deals_stuff_notification;
        $userid=$request->userid;

        $pushArr=array('ride_notification'=>$ride_notification,
                        'messages_notification'=>$messages_notification,
                        'news_deals_stuff_notification'=>$news_deals_stuff_notification);

      
           $res=User::where('id',$userid)->update($pushArr);
           if($res)
           {

                $noti_arr=array('user_id'=>$request->userid,'messages'=>'Update Push Notification Status');
                Notifications::create($noti_arr);


                              $lang_mess=$request->lang=='en' ? 'Push Notification Updated Successfully' : 'Notificación push actualizada con éxito';

                 $response=['status'=>'success','message'=>$lang_mess]; 

           }
           else
           {

              $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo!';

                 $response=['status'=>'failure','message'=>$lang_mess]; 

           }
          return response()->json($response); 
    }
    public function delete_user(Request $request)
    {
       $id=$request->userid;

       if(!empty($id))
       {
         // $statusArr=array('status'=>1,'account_status'=>1); 
         $res=User::where('id',$id)->delete();
         if($res)
         {

                          $lang_mess=$request->lang=='en' ? 'Account Delete Successfully' : 'Eliminación de cuenta con éxito';

                 $response=['status'=>'success','message'=>$lang_mess]; 

         }
         else
         {


            $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

              $response=['status'=>'failure','message'=>$lang_mess]; 

         }
       }
       else
       {

                $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                $response=['status'=>'failure','message'=>$lang_mess]; 
       }

          return response()->json($response); 


    }

    public function reminder_notification(Request $request)
    {
                        
                        // $userid=$request->userid;
                        // $data=Verify_id::select('id_proof_expdate','driving_licence_expdate','insurance_expdate')->where('user_id',$userid)->first();
                        // if(!empty($data))
                        // {
                        //     $date=date('Y-m-d'); 
                        //     $date=strtotime($date);
                        //     if(!empty($data->id_proof_expdate))
                        //     {
                        //        $days_ago1= date('Y-m-d', strtotime('-7 days', strtotime($data->id_proof_expdate)));
                        //       $days_ago1str=strtotime($days_ago1);
                        //      if($date==$days_ago1str)
                        //      {
                        //         echo "Eqaul";
                        //      } 
                        //      else
                        //      {
                        //          echo "Not Eqaul";
                        //      }

                           

                        //     }


                        //     if(!empty($data->driving_licence_expdate))
                        //     {

                        //     $days_ago2= date('Y-m-d', strtotime('-7 days', strtotime($data->driving_licence_expdate))); 
                        //     $days_agostr=strtotime($days_ago2);

                        //     if($date==$days_agostr)
                        //     {
                        //     echo "Eqaul";
                        //     } 
                        //     else
                        //     {
                        //     echo "Not Eqaul";
                        //     }
                              
                        //     } 

                        //        if(!empty($data->insurance_expdate))
                        //        {
                        //         $days_ago3= date('Y-m-d', strtotime('-7 days', strtotime($data->insurance_expdate))); 
                        //         $days_ago3strt=strtotime($days_ago3);

                        //         if($date==$days_ago3strt)
                        //         {
                        //         echo "Eqaul";
                        //         } 
                        //         else
                        //         {
                        //         echo "Not Eqaul";
                        //         }
                                                
                        //        }
                        // }
                        // else
                        // {

                        // }
    }

    public function alert_notification(Request $request)
    {
   
                if(!empty($request->userid))
                {
                $alert=array('user_id'=>$request->userid,
                'pickup_location'=>$request->pickup_location,
                'drop_location'=>$request->drop_location,
                'pickup_lat'=>$request->pickup_lat,
                'pickup_long'=>$request->pickup_long,
                'drop_lat'=>$request->drop_lat,
                'drop_long'=>$request->drop_long,
                'date'=>$request->date);

                $count=Alert_notification::where($alert)->count();
                if($count>0)
                {
                    
                     if($request->type=='enable')
                       {
                         
                            $lang_mess=$request->lang=='en' ? 'You have already created an alert for this ride and date' : 'Ya has creado una alerta para este viaje y fecha';
                           $response=['status'=>'success','message'=>$lang_mess];
                        }
                     else
                       {
                         Alert_notification::where($alert)->delete();
                           $lang_mess=$request->lang=='en' ? 'Removed successfully' : 'Eliminado con éxito';

                           $response=['status'=>'success','message'=>$lang_mess];
                       }
                //      $lang_mess=$request->lang=='en' ? 'You have already created an alert for this ride and date' : 'Ya has creado una alerta para este viaje y fecha';

                // $response=['status'=>'success','message'=>$lang_mess]; 
                
                }
                else
                {


                 $res=Alert_notification::create($alert);
                if($res)
                {


                     $lang_mess=$request->lang=='en' ? 'Alert Create Successfully' : 'Alerta creada con éxito';

                $response=['status'=>'success','message'=>$lang_mess]; 

                }
                else
                {

                     $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                $response=['status'=>'failure','message'=>$lang_mes]; 
                }

                }
                }
                else
                {

            $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                $response=['status'=>'failure','message'=>$lang_mess]; 

                }
      
    return response()->json($response); 


        

    

    }

    public function  duplicate_ride(Request $request)
    {
           // $request->userid;
           // $request->date;

           $data=Ride::select('*')->where('id',$request->ride_id)->first();
           $stop=Stop::select('*')->where('addride_id',$request->ride_id)->get()->toArray();

        $newArr=array('userid'=>$data->userid,
            'vehicle_id'=>$data->vehicle_id,
             'rideid'=>rand(10000,50000),
            'pick_location'=>$data->pick_location,
            'drop_location'=>$data->drop_location,
            'drop_lat'=>$data->drop_lat,
            'drop_long'=>$data->drop_long,
            'date'=>$request->date,
            'pick_lat'=>$data->pick_lat,
            'pick_long'=>$data->pick_long,
            'time'=>$data->time,
            'passenger_count'=>$data->passenger_count, 
            'total_passenger'=>$data->total_passenger,
            'stoppage'=>$data->stoppage,
            'small_bag'=>$data->small_bag,
            'hand_bag'=>$data->hand_bag, 
            'regular_bag'=>$data->regular_bag,
            'oversize_bag'=>$data->oversize_bag,
            'price'=>$data->price,
            'instruction'=>$data->instruction,
            'smoking_allowed '=>$data->smoking_allowed, 
            'pets_allowed'=>$data->pets_allowed,
            'backsheet'=>$data->backsheet,
            'music'=>$data->music,
            'instant_booking'=>$data->instant_booking,
            'verified_profile'=>$data->verified_profile,
            'ac_allow'=>$data->ac_allow,
            'pending_small_bag'=>$data->small_bag,
            'pending_hand_bag'=>$data->hand_bag,
            'pending_regular_bag'=>$data->regular_bag,
            'pending_oversize_bag'=>$data->oversize_bag,
            'food_allow'=>$data->food_allow);
            

        if(!empty($newArr))
        {
           $res=Ride::create($newArr);
           if(count($stop)>0)
           {   
              foreach($stop as $value)
              {

                   $newarr=array('date'=>$request->date,'addride_id'=>$res->id,'stop_location'=>$value['stop_location'],'stop_lat'=>$value['stop_lat'],'stop_long'=>$value['stop_long'],'created_at'=>date('Y-m-d H:i:s'));
                   Stop::create($newarr);

              }
           }

           if($res)
           {

             $lang_mess=$request->lang=='en' ? 'Ride Create Successfully' : 'Paseo creado con éxito';

                $response=['status'=>'success','message'=>$lang_mess]; 

           }
           else
           {

              $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

              $response=['status'=>'failure','message'=>$lang_mess]; 

           }
        }
        else
        {

         $lang_mess=$request->lang=='en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

              $response=['status'=>'failure','message'=>$lang_mess]; 

        }
           
             return response()->json($response); 



    }
  public function filter_program(Request $request)
   {
    
     $time=$request->time;
     $temtime=explode('-',$time);
     $t1=$temtime[0];
     $t2=$temtime[1];


     $dte='2023-01-12';

    

    //  return $t1;
    //  return $t2;

    $curr_lat=$request->curr_lat;
    $currlong=$request->curr_long;
//     // $passeng_count1=$request->passenger_count;
//     $useridsd=$request->userid;

// // serach ride data 
  $lat=$request->pickup_lat;
  $lon=$request->pickup_long;

  $latitude=$request->drop_lat;
  $longitude=$request->drop_long;
  // DB::enableQueryLog();

  $nearby=DB::table("add_rides");
  $nearby=$nearby->select('add_rides.id as ride_id','add_rides.userid as driver_id',
  'users.name','users.lname','users.mobile','users.image','add_rides.pick_location',
  'add_rides.drop_location','add_rides.pick_lat', 'add_rides.pick_long','add_rides.drop_lat'
  ,'add_rides.drop_long','add_rides.date','add_rides.time','add_rides.passenger_count',
  'add_rides.price','users.is_verifyId','users.avg_rating', 'add_rides.instruction',
  'vehicles.plate_number','vehicles.vehicle_brand','vehicles.country','vehicles.vehicle_model'
  ,'vehicles.vehicle_type','vehicles.vechicle_color','vehicles.vechicle_madeyear','vehicles.vehicle_img',
  'add_rides.total_passenger as bookedsheet','add_rides.stoppage','add_rides.small_bag','add_rides.hand_bag'
  ,'add_rides.regular_bag','add_rides.smoking_allowed','add_rides.pets_allowed','add_rides.verified_profile'
  ,'add_rides.instant_booking','add_rides.food_allow','add_rides.ac_allow','add_rides.oversize_bag'
  ,DB::raw("6371 * acos(cos(radians(" . $lat . "))* cos(radians(pick_lat)) * cos(radians(pick_long) - radians(" . $lon . "))
   + sin(radians(" .$lat. "))
   * sin(radians(pick_lat))) AS distance"));
          $nearby=$nearby->join('users','users.id','=','add_rides.userid');
          $nearby=$nearby->join('vehicles','vehicles.id','=','add_rides.vehicle_id');
          $nearby=$nearby->having('distance', '<=', 20);
          $nearby=$nearby->whereDate('add_rides.date', '=',$dte);
          $nearby=$nearby->where('add_rides.complete_status',0);
          $nearby=$nearby->where('add_rides.delete_status','active');
          $nearby=$nearby->orderBy('distance', 'asc');
          $nearby=$nearby->get()->toArray();
          $nearby = json_decode(json_encode($nearby), true);

      // return  DB::getQueryLog();
      $t1=date('Y-m-d H:i A',strtotime($dte.' '.$t1));
      $t2=date('Y-m-d H:i A',strtotime($dte.' '.$t2));
      $newarrarry=[];
          foreach ($nearby as $key=>$value) {

            // echo "<pre>";
            // print_r($value);
                    
            $desto=$this->distance($latitude,$longitude,$value['drop_lat'],$value['drop_long']); 
            $newArr=[];
            if($desto['distance']<=20)
            { 

              $dbtime=date('Y-m-d H:i A',strtotime($value['date'].' '.$value['time']));
                 if($t1 >=  $dbtime && $t2 <= $dbtime)
                 {

                 
                  
                 
                  $newarr=array('ride_id'=>$value['ride_id'],
                     'mobile'=>$value['mobile'],
                     'driver_id'=>$value['driver_id'],
                     'name'=>$value['name'],
                     'lname'=>$value['lname'],
                     'pick_location'=>$value['pick_location'],
                     'drop_location'=>$value['drop_location'],
                     'pick_lat'=>$value['pick_lat'],
                     'pick_long'=>$value['pick_long'],
                     'drop_long'=>$value['drop_long'],
                     'date'=>$value['date'],
                         'time' =>$value['time'],
                         'passenger_count'=>$value['passenger_count'],
                         'price'=>$value['price'],
                         'is_verifyId'=>$value['is_verifyId'],
                         'instruction'=>$value['instruction'],
                         'plate_number'=>$value['plate_number'],
                         'vehicle_brand'=>$value['vehicle_brand'],
                         'country'=>$value['country'],
                         'vehicle_model'=>$value['vehicle_model'],
                         'vehicle_type'=>$value['vehicle_type'],
                         'vechicle_color'=>$value['vechicle_color'],
                         'vechicle_madeyear'=>$value['vechicle_madeyear'],
                         'vehicle_img'=>$value['vehicle_img'],
                         'bookedsheet'=>$value['bookedsheet'],
                         'stoppage'=>$value['stoppage'],
                         'small_bag'=>$value['small_bag'],
                         'hand_bag'=>$value['hand_bag'],
                         'regular_bag'=>$value['regular_bag'],
                         'smoking_allowed'=>$value['smoking_allowed'],
                         'pets_allowed'=>$value['pets_allowed'],
                         'verified_profile'=>$value['verified_profile'],
                         'instant_booking'=>$value['instant_booking'],
                         'food_allow'=>$value['food_allow'],
                         'ac_allow'=>$value['ac_allow'],
                         'oversize_bag'=>$value['oversize_bag'],
                         'image'=>$value['image']);
          

                    //  $srchArrrr=array('passenger_id'=>,'ride_id'=>$value['ride_id'],'cancel_booking'=>'true');
                    //  $rating=Rating_reviews::where('receiver_id',$value['driver_id'])->avg('rating');
                    //  //$newData[$key]['avg_rating']=round($rating,1);
                    //  $newarr['avg_rating']=round($rating,1);
                    //      if($newarr['avg_rating']==null)
                    //      {
                    //      $newarr['avg_rating']=0;
                    //      }
                        //  $count=Apply_ride::where($srchArrrr)->count();
                        //  if($count>0)
                        //  {
                        //  $newarr['booking_status']=1;   
                        //  }
                        //  else
                        //  {   
                        //  $newarr['booking_status']=0;   
                        //  }
                        //  $newarr['updated_price']=$value['price']*$passeng_count1; 
                         $milesss=$this->distance($lat,$lon,$latitude,$longitude);
                         $newarr['distance']=(string)$milesss['distance'].'KM'; 
                         $newarr['passenger_count']=(int)$value['passenger_count']; 
                         $newarr['hours']=$milesss['time']; 

                         if(!empty($lat) && !empty($lon))
                         {

                         $milesss1=$this->distance($lat,$lon,$latitude,$longitude);
                         $newarr['estimate_distance']=(string)$milesss1['distance'].'KM'; 
                         $newarr['estimate_hours']=$milesss1['time']; 
                         $thredate=date('H:i',strtotime($value['date'].' '.$value['time']));
                         $timess=strtotime($thredate.'+'.$milesss1['time']); 
                         $newfff=date('h:i A',$timess);
                         $newarr['estimate_hours']=$newfff;

                        } 
                     else
                       {
                        $newarr['estimate_distance']=''; 
                        $newarr['estimate_hours']=''; 
                        $newarr['estimate_hours']='';
                       }
                         // if(empty($value['image']) || $value['image']='') 
                         // {

                         //    $newarr['image']='public/image/userimage/1748281240793284.jpeg';    

                         // }
                   $newarrarry[]=$newarr;
            }
          }


          }
        
   return response()->json($newarrarry);

   }

  public function get_single_ridedata($id,$userid)
   {
      $data=Ride::join('users','users.id','=','add_rides.userid')
                ->join('verify_id','verify_id.user_id','=','users.id','left')
                ->join('vehicles','vehicles.id','=','add_rides.vehicle_id')

                // ->join('rating_reviews','rating_reviews.sender_id','=','add_rides.userid')
                ->select('users.profile_status','users.is_verifyId','users.id as driver_id','add_rides.created_at','add_rides.id','users.name','users.lname','users.mobile','users.image','add_rides.pick_location','add_rides.drop_location','add_rides.pick_lat','add_rides.pick_long','add_rides.drop_lat','add_rides.drop_long','add_rides.date','add_rides.time','add_rides.passenger_count','add_rides.price','add_rides.instruction','add_rides.total_passenger as bookedsheet', 'add_rides.smoking_allowed',
                'add_rides.pets_allowed',
               // 'rating_reviews.review_status',
                'add_rides.ac_allow',
                'add_rides.food_allow',
                'add_rides.stoppage',
                'add_rides.complete_status',
                'vehicles.plate_number',
                    'vehicles.vehicle_brand',
                    'vehicles.country',
                    'vehicles.vehicle_model',
                    'vehicles.vehicle_type',
                    'vehicles.vechicle_color',
                    'vehicles.vechicle_madeyear',
                    'vehicles.vehicle_img',
                'add_rides.instant_booking','add_rides.small_bag','add_rides.hand_bag','add_rides.regular_bag','add_rides.oversize_bag','add_rides.pending_small_bag','add_rides.pending_hand_bag','add_rides.pending_regular_bag','add_rides.pending_oversize_bag')
                ->where('add_rides.id',$id)
                ->where('add_rides.delete_status','=','active') 
                ->orderBy('add_rides.date', 'DESC')
                ->get()
                ->toArray();

               if($data)
                 {

                        // $count=0;
                        foreach($data as $key=>$value)
                        {

                            $count=Apply_ride::where('ride_id',$value['id'])->where('instant_status',1)->count();
                            if($count>0)
                            {
                            $data[$key]['booking_count']=1;
                            }
                            else
                            {

                            $data[$key]['booking_count']=0;
                            }   

                        $milesss=$this->distance($value['pick_lat'],$value['pick_long'],$value['drop_lat'],$value['drop_long']);
                        $data[$key]['distance']=(string)$milesss['distance'].'KM'; 
                        $data[$key]['hours']=$milesss['time'];
                        // $milesss1=$this->distance($curr_lat,$currlong,$latitude,$longitude);
                            //            $newData[$key]['estimate_distance']=(string)$milesss1['distance'].'KM'; 
                            //            $newData[$key]['estimate_hours']=$milesss1['time']; 
                            $thredate=date('H:i',strtotime($value['date'].' '.$value['time']));
                            $timess=strtotime($thredate.'+'.$milesss['time']); 
                            $newfff=date('h:i A',$timess);
                            $data[$key]['estimate_hours']=$newfff;

                        if(empty($value['stoppage']) || $value['stoppage']==null )
                        {
                        $data[$key]['stoppage']=[];

                        }
                        if($value['is_verifyId']==null || $value['is_verifyId']=='')
                        {
                        $data[$key]['is_verifyId']=0;
                        }
                        $data[$key]['my_rating_review_status']=Rating_reviews::where('sender_id',$userid)->where('ride_id',$value['id'])->count();

                        $booked_users=Apply_ride::select(
                        'apply_ride.ride_id',
                        'apply_ride.small_bag',
                        'apply_ride.hand_bag',
                        'apply_ride.regular_bag',
                        'apply_ride.oversize_bag',
                            'apply_ride.id','apply_ride.passenger_id','users.name','users.lname','users.mobile','users.gender','users.bio','users.dob','users.email','users.image','apply_ride.passenger_count as usersheetcount','apply_ride.instant_status','apply_ride.confirm_book')
                        ->join('users','users.id','=','apply_ride.passenger_id')
                        // ->join('rating_reviews','rating_reviews.sender_id','=','apply_ride.passenger_id')
                        ->where('apply_ride.cancel_booking','=','true')
                        ->where('apply_ride.ride_id',$value['id'])->get()->toArray();

                         foreach ($booked_users as $eky=>$value123) {
                                   
                                     $dfgg=Rating_reviews::select('rating','reviews')->where('ride_id',$value123['ride_id'])->where('receiver_id',$value123['passenger_id'])->first();
                                     $ratingavg=Rating_reviews::where('receiver_id',$value123['passenger_id'])->avg('rating');

                               $booked_users[$eky]['rating_avg']=round(@$ratingavg);

                                    if(!empty($dfgg))
                                    {
                                      $booked_users[$eky]['rating']=@$dfgg->rating;
                                    $booked_users[$eky]['reviews']=@$dfgg->reviews;  
                                    }
                                    else
                                    {
                                    $booked_users[$eky]['reviews']=false;
                                    $booked_users[$eky]['rating']=false;

                                    }
                                    
                             
                      }


                        $data[$key]['booked_users']=$booked_users;

                        }

                        
                      

               

                 }

                   return $data;
   }

   public function send_meessage_email(Request $request)
    {


      $uid=$request->userid;
      $checkforuserexist=User::select('*')->where('id',$uid)->first();

        $info = array(
            'name' =>@$checkforuserexist->name.' '.@$checkforuserexist->lname,
            'message'=>$request->message,
            'id'=>$checkforuserexist->id
        );
        $rmm='denisadolnakova@gmail.com';
        $rmm1='michal.obeda@gmail.com';
        Mail::send('pdf_temp.message_tem',compact('info'), function ($message) use($rmm) {
            $message->to($rmm)
                ->subject('Caco Users Messages');
            $message->from('Caco@gmail.com', 'Caco@gmail.com');
        });
         Mail::send('pdf_temp.message_tem',compact('info'), function ($message) use($rmm1) {
            $message->to($rmm1)
                ->subject('Caco Users Messages');
            $message->from('Caco@gmail.com', 'Caco@gmail.com');
        });

       $response=['status'=>'success'];
        return  response()->json($response);



    }

    public function cron_jobs(Request $request)
     {
        
        // $schedule->command('apicontroller:cron_jobs')->cron('*/5 * * * *');
          // $this->auto_complete_ride();
         // echo "cron jobs";  
         // $this->send_notification1(82,86,'new text','d');\
        $update=rand(2123,23323);

      $update=['name'=>$update];
       DB::table('users')->where('id',86)->update($update);

    echo 'ok';
     }

     public function democron()
     {

         $this->auto_complete_ride();

     }
 public function send_email_documents_verifyed($id)
    {
      $admin_ifo=User::select('*')->where('user_type','admin')->get()->toArray();
      $checkforuserexist=User::select('*')->where('id',$id)->first();
   
   $uname=$checkforuserexist->name.' '.$checkforuserexist->lname;
      foreach($admin_ifo as $value)
      {
        $aname=$value['name'].' '.$value['lname'];
        $info['user'] = array(
            'name' =>$uname,
            'adminame'=>$aname,
            'id'=>@$id
        );
      $rt= $value['email'];
        Mail::send('doc_temp.document_email',$info, function ($message) use($rt) {
            $message->to($rt)
                ->subject('Document verification');
            $message->from('Caco@gmail.com', 'Caco@gmail.com');
        });
           $this->send_notification_to_admin($value['id'],$checkforuserexist->id);
        } 
     }

     public function send_notification_to_admin($adminid,$userid)
        {
         
              $senderdata=User::select('name','lname')->where('id',$userid)->first();
              $receiver_iddata=User::select('name','lname')->where('id',$adminid)->first();
              $title='Documents verification';
              $type1='Verifyid';
           
                 $desc='Hi '.@$receiver_iddata->name .' '. @$receiver_iddata->lname.' ,'.@$senderdata->name.' '.$senderdata->lname.' '.'uploaded document check admin and approve pending user documents';
          
                // $desc="";
              // $gettokn_Arr=array('user_id'=>$request->userid);
                  // $arrayNames='';
                $arrayNames=array('user_id'=>$userid,'title'=>$title,'description'=>$desc);
                // $result2=$this->init2()->insert('notifications_history',$arrayNames);
                // $userData=$this->init2()->device_token_fetch($userid,$utype);
              $getting_token_info=Token::select('*')->where('user_id',$adminid)->get()->toArray();

                if(!empty($getting_token_info) && count($getting_token_info)>0)
                {
                $API_SERVER_KEY="AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
                foreach ($getting_token_info as $key => $valuedata) {
                $registrationIds=@$valuedata['token'];
              $data=(object)array('type'=>$type1,'ridedata'=>'');
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
                return $arrayName;


    }

    public function check_expiredate()
     {
      $today=date('Y-m-d');
      $today1=strtotime($today);
       $Arr=Verify_id::select('*')->get()->toArray();
             
          $a1='';
          $a2='';
          $a3='';
          $a4='';
          foreach($Arr as $val)
             {
               
               $a1=strtotime(@$val['id_proof_expdate']);
               // $a2=strtotime(@$val['vehicle_plate_expdate']);
               $a3=strtotime(@$val['driving_licence_expdate']);
               $a4=strtotime(@$val['insurance_expdate']);

               // if($a2 && $today1 > $a2)
               // {
                
               //   $this->expire_notification($val['user_id'],'Vehicle plate','vehicle_plate_status');
               // }
              

               if($a3 && $today1 > $a3)
               {
                 $this->expire_notification($val['user_id'],'Driving licence','driving_licence_status');
               }
               if($a4 && $today1 > $a4)
               {
                $this->expire_notification($val['user_id'],'Insurance','insurance_status');
               }
                if($a1 && $today1 > $a1)
               {
                  $this->expire_notification($val['user_id'],'Id proof','id_proff_status');
               }
            
             }
            
     }
      public function expire_notification($receiver_id,$st,$status)
    {
                $receiver_iddata=User::select('name','lname')->where('id',$receiver_id)->first();
                 $idArr=array('is_verifyId'=>1);
                 User::where('id',$receiver_id)->update($idArr);
                 $uparr=array($status=>1);
                Verify_id::where('user_id',$receiver_id)->update($uparr); 

         $checkforuserexist=User::select('*')->where('id',$receiver_id)->first();
   
       $uname=@$checkforuserexist->name.' '.@$checkforuserexist->lname;
     
        $info['user'] = array(
            'name' =>@$uname
        );
         $rt=@$checkforuserexist->email;
      
                if(!empty($rt))

                {

                $message='';
                Mail::send('doc_temp.document_expired',$info, function ($message) use($rt) {
                $message->to($rt)
                ->subject('Document verification');
                $message->from('Caco@gmail.com', 'Caco@gmail.com');
                });  
                }   

              $title='Hi '.@$receiver_iddata->name .' '. @$receiver_iddata->lname;
              $desc='Your '.$st.' Document Has Been Expired Please reupload Documents';
         
                $arrayNames=array('user_id'=>$receiver_id,'title'=>$title,'description'=>$desc);
                $getting_token_info=Token::select('*')->where('user_id',$receiver_id)->get()->toArray();

                if(!empty($getting_token_info) && count($getting_token_info)>0)
                {
                $API_SERVER_KEY="AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
                foreach ($getting_token_info as $key => $valuedata) {
                $registrationIds=$valuedata['token'];
              $data=(object)array('type'=>'Verifyid','ridedata'=>'');
                $msg = array
                (
                'body'  => $desc,
                'title' => $title,
                'icon'  => 'myicon',/*Default Icon*/
                'sound' => 'mySound'/*Default sound*/
                );
                $fields = array
                (
                'to'=> $registrationIds,
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
                // return  $response=['status'=>'success','message'=>'test ','rss'=>$arrayName]; 
                // return $arrayName;
                // echo $arrayName;


    }
            public function offer_submit(Request $request)
            {
                   $udata=User::select('name','lname')->where('id',$request->driver_id)->get()->first();
                    $name=$udata->name.' '.$udata->lname;

                        // Firebase Realtime Database URL
                        $databaseURL = 'https://caco-d2903-default-rtdb.firebaseio.com/';
                        // Data to be sent to Firebase (replace with your data)
                        $rand=time();
                        $data = [
                        'ride_id'=>$request->ride_id,
                        'price' =>$request->price,
                        'users_id' =>$request->users_id,
                        'driver_id' =>$request->driver_id,
                        'status'=>'pending',
                        'timestamp'=>time()
                        ];

                        // Convert the data to JSON
                        $jsonData = json_encode($data);

                        // Initialize cURL session
                        $ch = curl_init();

                        // Set cURL options
                        curl_setopt($ch, CURLOPT_URL, $databaseURL .'offers/'.$rand.'.json'); // Replace 'path/to/resource' with the desired path in the database
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // Use 'PUT' for updating data, or 'POST' to add new data
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        // Execute cURL request and get the response

                        $response = curl_exec($ch);

                        // Check if cURL request was successful
                        if ($response === false) {
                        // cURL error occurred
                        $error = curl_error($ch);
                        return response()->json(['error' => 'cURL Error: ' . $error], 500);
                        } else {
                        // cURL request was successful
                        $this->offer_notification($name,$request->users_id);
                        return response()->json(['status'=>'success','message' => 'Offer send successfully wait for passenger response!']);
                        }

                        // Close cURL session
                        curl_close($ch);
            }
       public function offer_notification($name,$users_id)
       {
              $title='Ride Offer';
              $desc=$name.' send you a ride offer';
              $getting_token_info=Token::select('*')->where('user_id',86)->get()->toArray();
                if(!empty($getting_token_info) && count($getting_token_info)>0)
                {
                $API_SERVER_KEY="AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
                foreach ($getting_token_info as $key => $valuedata) {
                $registrationIds=$valuedata['token'];
              $data=(object)array('type'=>'offer','ridedata'=>[]);
                $msg = array
                (
                'body'  => $desc,
                'title' => $title,
                'icon'  => 'myicon',/*Default Icon*/
                'sound' => 'mySound'/*Default sound*/
                );
                $fields = array
                (
                'to'=>$registrationIds,
                'notification'=>$msg,
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
               }
               return true;
            

       }     
       public function offer_notifications_status(Request $request)
        {
                    try {
                        $userid=$request->userid;
                        $status=$request->status;
                        $update=array('offer_notifications_status'=>$status);
                        $res=User::where('id',$userid)->update($update);
                        if($res)
                        {
                        $lang_mess=$request->lang=='en' ? 'Update successfully' : 'Estás excedido el límite';
                        $response=['status'=>'success','message'=>$lang_mess]; 

                        }
                        else
                        {
                        $lang_mess=$request->lang=='en' ? 'omething else wrong please try again !' : 'algo más está mal por favor inténtalo de nuevo!';
                        $response=['status'=>'error','message'=>$lang_mess]; 

                        }
                    } catch (Exception $e) {
                         $response=['status'=>'error','message'=>$this->commonErrorCodes($e->getCode())]; 

                    }
             return response()->json($response); 


        }

        public function myoffers_ride($userid)
        {
            // $count=User::where('offer_notifications_status',1)->count();
            // if($count>0)
            // {
            $data=$this->get_offerd_ride($userid);
            // if($data)
            // {
            // $lang_mess=$request->lang=='en' ? 'data found' : 'datos encontrados';
            // $response=['status'=>'success','message'=> $lang_mess,'data'=>$data,'approval'=>'Expired approval'];  
            // }
            // else
            // {
            // $lang_mess=$request->lang=='en' ? 'There Is No Ride' : 'no hay paseo';
            // $response=['status'=>'success','message'=>$lang_mess,'data'=>[],'approval'=>''];  
            // }


            // }  
            // else
            // {
            // $lang_mess=$request->lang=='en' ? 'There Is No Ride' : 'no hay paseo';

            // $response=['status'=>'success','message'=>$lang_mess,'data'=>[],'approval'=>''];  
            // }
         return $data; 
 
        }
        public function get_offerd_ride($userid)
        {

                $data=Ride::join('users','users.id','=','add_rides.userid')
                ->join('verify_id','verify_id.user_id','=','users.id','left')
                ->join('vehicles','vehicles.id','=','add_rides.vehicle_id')
                ->select('users.profile_status','users.is_verifyId','users.id as driver_id','add_rides.created_at','add_rides.id','users.name','users.lname','users.mobile','users.image','add_rides.pick_location','add_rides.drop_location','add_rides.pick_lat','add_rides.pick_long','add_rides.drop_lat','add_rides.drop_long','add_rides.date','add_rides.time','add_rides.passenger_count','add_rides.price','add_rides.instruction','add_rides.total_passenger as bookedsheet', 'add_rides.smoking_allowed','add_rides.pets_allowed','add_rides.ac_allow','add_rides.food_allow','add_rides.stoppage','add_rides.complete_status', 'vehicles.plate_number','vehicles.vehicle_brand','vehicles.country', 'vehicles.vehicle_model','vehicles.vehicle_type','vehicles.vechicle_color','vehicles.vechicle_madeyear','vehicles.vehicle_img','add_rides.instant_booking','add_rides.small_bag','add_rides.hand_bag','add_rides.regular_bag','add_rides.oversize_bag','add_rides.pending_small_bag','add_rides.pending_hand_bag','add_rides.pending_regular_bag','add_rides.pending_oversize_bag')
                ->where('add_rides.userid',$userid)
                ->where('add_rides.delete_status','=','active') 
                ->orderBy('add_rides.date', 'DESC')
                ->get()
                ->toArray();

                    foreach($data as $key=>$value)
                    {
                    $exdate=strtotime($value['date'].' '.$value['time']);
                    if($today1>$exdate)
                    {
                    $data[$key]['expiredate']=true;
                    }
                    else
                    {
                    $data[$key]['expiredate']=false; 
                    }
                    $count=Apply_ride::where('ride_id',$value['id'])->where('instant_status',1)->count();
                    if($count>0)
                    {
                    $data[$key]['booking_count']=1;
                    }
                    else
                    {
                    $data[$key]['booking_count']=0;
                    }
                    $milesss=$this->distance($value['pick_lat'],$value['pick_long'],$value['drop_lat'],$value['drop_long']);
                    $data[$key]['distance']=(string)$milesss['distance'].'KM'; 
                    $data[$key]['hours']=$milesss['time'];
                  
                    $thredate=date('H:i',strtotime($value['date'].' '.$value['time']));
                    $timess=strtotime($thredate.'+'.$milesss['time']); 
                    $newfff=date('H:i A',$timess);
                    $data[$key]['estimate_hours']=$newfff;

                    if(empty($value['stoppage']) || $value['stoppage']==null )
                    {
                    $data[$key]['stoppage']=[];

                    }
                    if($value['is_verifyId']==null || $value['is_verifyId']=='')
                    {
                    $data[$key]['is_verifyId']=0;
                    }
                   // booked user function 
                    $booked_users=$this->booked_users($value['id']);
                    $data[$key]['booked_users']=$booked_users;

                    }
                    return $data;


        }
        public function booked_users($id)
        {
           $booked_users=Apply_ride::select(
                    'apply_ride.small_bag',
                    'apply_ride.hand_bag',
                    'apply_ride.regular_bag',
                    'apply_ride.oversize_bag','apply_ride.id','apply_ride.ride_id',
                    'apply_ride.driver_id','apply_ride.id','apply_ride.passenger_id','users.name','users.lname','users.mobile','users.gender','users.bio','users.dob','users.email','users.image','apply_ride.passenger_count as usersheetcount','apply_ride.instant_status','apply_ride.confirm_book')
                    ->join('users','users.id','=','apply_ride.passenger_id')
                    ->where('apply_ride.cancel_booking','=','true')
                    ->where('apply_ride.ride_id',$id)->get()->toArray();

                    foreach ($booked_users as $eky=>$value123) {
                    $dfgg=Rating_reviews::select('rating','reviews')->where('ride_id',$value123['ride_id'])->where('receiver_id',$value123['passenger_id'])->first();
                    $ratingavg=Rating_reviews::where('receiver_id',$value123['passenger_id'])->avg('rating');

                    $booked_users[$eky]['rating_avg']=round(@$ratingavg);

                    if(!empty($dfgg))
                    {
                    $booked_users[$eky]['rating']=@$dfgg->rating;
                    $booked_users[$eky]['reviews']=@$dfgg->reviews;  
                    }
                    else
                    {
                    $booked_users[$eky]['reviews']=false;
                    $booked_users[$eky]['rating']=false;
                    }
              
                }
              return $booked_users;
        }
          
            

}
