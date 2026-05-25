<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
use App\Models\Payment_history;
use App\Models\Request_ride;
use App\Models\Push_ride;
use App\Models\Locations;
use App\Models\Setting;
use App\Models\payment;
use App\Models\offer;
use Exception;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Service\AppNotify;
use App\Service\SmsService;
use App\Helpers\PhonePeHelper;


class apicontroller extends Controller
{
  //
    public function __construct()
  {
    // date_default_timezone_set("America/Santo_Domingo");
    date_default_timezone_set('Asia/Kolkata');

  }
/**
 * This API Sends theTest Sms  to the system.
 */
  

        public function sendTestSms(Request  $request , SmsService $smsService)
          {
            
          try {
                  $mobile =$request->mobile;

                  $otp = rand(100000, 999999);

                  // $message = "Dear user your OTP for login from SMOOTHRIDE is {$otp}. Please do not share it with anyone.";
                    $message = "SMOOTHRIDE Alert: Your ride from {#var#} to {#var#} is confirmed.
                                Driver:{#var#},
                                Contact: {#var#}.";
      
                $smsService = new SmsService();
                $templateId=1007069034521817674;
                $response = $smsService->sendOtp($mobile, $message,$templateId);

                  return response()->json(['response' => $response]);

              } catch (\Exception $e) {
                  return response()->json([
                      'error' => true,
                      'message' => $e->getMessage()
                  ], 500);
              }
          }



  // *************************************************************************************************************************************************************
/**
 * This are some common error codes.
 */
  
  public function commonErrorCodes($code)
  {
    $commonErrorCodes = array(
      '00000' => 'Success',
      '01004' => 'String data right-truncated',
      '21000' => 'Cardinality violation',
      '22001' => 'String data right-truncated',
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

    $value = 'Unkownerror';
    foreach ($commonErrorCodes as $key => $error_code) {
      if ($code == $key) {
        $value = $error_code;

      }

    }
    return $value;
  }
  // *************************************************************************************************************************************************************



  // ----------------------------------------------------------Notifcation Send ------------------------------------------------------------
/**
 * This API Sends a FCM via Json.
 */
  
public function sendFCMViaJson($deviceToken, $title, $body, $data = [])
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

  // ----------------------------------------------------------End Notification send ------------------------------------------------------
  /**
 * This API Sends a LoginOPT  to the system.
 */
  
  public function loginotp(Request $request)
  {

            // $validator = Validator::make($request->all(), [
            //   'mobile_email' => 'required',
            //   'lang' => 'required',
            //   'type' => 'required',

            // ]);


            $messages = [
            'mobile_email.required' => 'Phone Number is required.',
            'mobile_email.email' => 'Please provide a valid phone number .',
            'lang.required' => 'Language is required.',
            'type.required' => 'Type is required.',
              ];

            $validator = Validator::make($request->all(), [
                'mobile_email' => 'required',
                'lang' => 'required',
                'type' => 'required',
            ], $messages);

            if ($validator->fails()) {
              return response()->json([
                'message' => $validator->errors()->first(),
              ], 422);
            }
          $data = $request->all();

    // $mobile= $data['mobile'];
          if($data['type'] == 'mobile'){


            $where = array('mobile' => $data['mobile_email'], 'status' => 0);

          }else{

          $where = array('email' => $data['mobile_email'], 'status' => 0);

          }

          $checkforuserexist = DB::table('users')->where($where)->count();

          $lang_mess ='';
          if ($checkforuserexist > 0) {
                // if ($data['mobile_email'] == 9015069749 || $data['mobile_email'] == 9015069749 ||  $data['mobile_email']='kartikt.digittrix@gmail.com') {
                //   $otp = 4567;
                // } else {
                //   $otp = rand(1000, 9999);
                // }

                $otp =rand(1000, 9999);
                  // $otp = 4567;
              if($data['type'] == 'mobile'){

           
                    try {

                  $message = "Dear user your OTP for login from SMOOTHRIDE is {$otp}. Please do not share it with anyone.";
        
                  $SmsService = new SmsService();
                  $templateId=1007342144967878207;
                  $response = $SmsService->sendOtp($request->input('mobile_email'), $message,$templateId);
                      $msg='Otp Sent to '.$request->input('mobile_email');
                        $lang_mess =$msg ;

                    } catch (\Exception $e) {
                    return response()->json([
                        'error' => true,
                        'message' => $e->getMessage()
                    ], 500);
                }

         }else{

          $email=$request->input('mobile_email');

          $info = [
          'name' => $request->input('mobile_email'), 
              'otp'  => $otp
          ];

          Mail::send('emails.otp', compact('info'), function ($message) use ($email) {
              $message->to($email)
                      ->subject('Your OTP Code');
          });


           $lang_mess = $request->lang == 'en' ? 'Send otp to your emailll' : 'Enviar otp a su número de móvil';
          
         }
         

      $response = ['status' => 'success', 'message' => $lang_mess, 'otp' => $otp];

      return response()->json($response);
    } else {

      if($data['type'] == 'mobile'){
      $where = array('mobile' => $data['mobile_email'], 'status' => 1);

      }else{
      $where = array('email' => $data['mobile_email'], 'status' => 1);

      }
      $checkforuserexist = DB::table('users')->where($where)->count();
          if ($checkforuserexist > 0) {

            $lang_mess = $request->lang == 'en' ? 'Your account has been blocked' : 'Tu cuenta ha sido bloqueada';
            $response = ['status' => 'failure', 'message' => $lang_mess, 'otp' => ''];
          } else {

            $lang_mess = $request->lang == 'en' ? 'User Not Exist' : 'Número de móvil no existe';
            $response = ['status' => 'failure', 'message' => $lang_mess, 'otp' => ''];
          }

    }
    return response()->json($response);
  }
/**
 * This API Sends the OPT to the system.
 */
      public function otpsend(Request $request) //register
      {


              $messages = [
              'mobile_email.required' => 'Phone Number is required.',
              'lang.required' => 'Language is required.',
              'type.required' => 'Type is required.',
              ];

              $validator = Validator::make($request->all(), [
                'mobile_email' => 'required',
                  'lang' => 'required',
                  'type' => 'required',
              ], $messages);

            
            // $validator = Validator::make($request->all(), [
            //   'mobile_email' => 'required',
            //   'lang' => 'required',
            //   'type' => 'required',

            // ]);

            if ($validator->fails()) {
              return response()->json([
                'message' => $validator->errors()->first(),
              ], 422);
            }
            $data = $request->all();

            $mobile = $data['mobile_email'];
          
            if($data['type'] &&  $data['type'] =='mobile'){

                $checkforuserexist = DB::table('users')->where('mobile', $mobile)->count();

            }else{

              $checkforuserexist = DB::table('users')->where('email', $mobile)->count();

            }
            if ($checkforuserexist > 0) {

              $lang_mess = $request->lang == 'en' ? 'User already exist' : 'El usuario ya existe';

              $response = ['status' => 'failure', 'message' => $lang_mess];
              return response()->json($response);
            } else {

       
            $otp = rand(1000, 9999);

              if($data['type'] == 'mobile'){

                try {

                        $message = "Dear user your OTP for login from SMOOTHRIDE is {$otp}. Please do not share it with anyone.";
              
                        $SmsService = new SmsService();
                         $templateId=1007342144967878207;
                        $response = $SmsService->sendOtp($request->input('mobile_email'), $message ,$templateId);
                          } catch (\Exception $e) {
                          return response()->json([
                              'error' => true,
                              'message' => $e->getMessage()
                          ], 500);
                      }

              }else{

                $info = [
                'name' => $request->input('mobile_email'), 
                    'otp'  => $otp
                ];

                Mail::send('emails.otp', compact('info'), function ($message) use ($mobile) {
                    $message->to($mobile)
                            ->subject('Your OTP Code');
                });


              }

          $response = ['status' => 'success', 'otp' => $otp];
            return response()->json($response);
        }
  }
/**
 * This API Send Otp To Reset The Password.
 */
  public function otpsendforforgotpassword(Request $request)
  {
    $data = $request->all();
    $email = $data['email'];
    $checkforuserexist = DB::table('users')->where('email', $email)->first();
    if (isset($checkforuserexist)) {
      $otp = rand(1000, 9999);
      $info = array(
        'name' => @$checkforuserexist->name,
        'otp' => $otp
      );
      Mail::send('emails.otp', compact('info'), function ($message) use ($email) {
        $message->to($email)
          ->subject('OTP Send');
        // $message->from('Caco@gmail.com', 'Caco@gmail.com');
      });
      $response = ['status' => 'success', 'otp' => $otp];
      return response()->json($response);
    } else {
      $lang_mess = $request->lang == 'en' ? 'user not exist' : 'El usuario no existe';

      $response = ['status' => 'failure', 'message' => $lang_mess];
      return response()->json($response);
    }
  }
/**
 * This API creates a new user in the system and returns the user details.
 */
  public function register(Request $request)
  {
    // $otp=rand(1000,9999);

    $validator = Validator::make($request->all(), [
      'fname' => 'required',
      'lname' => 'required',
      'mobile_email' => 'required',
      'mobile' => 'required|digits:10',
      'type' => 'required',
      'country_code' => 'required',
      'deviceToken' => 'required',
      'deviceType' => 'required',

    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 422);
    }
    $data = $request->all();
    // check if user already registered in db
     if($data['type'] == 'mobile'){

        $cachk = array('mobile' => $data['mobile_email']);

     }else{

        $cachk = array('email' => $data['mobile_email']);

     }
    // $cachk1=array('email'=>$data['email']);
    $checkuserexistindb = DB::table('users')->where($cachk)->first();
    // $checkuserexistindb1=DB::table('users')->where($cachk1)->first();
        if (isset($checkuserexistindb)) {

          $lang_mess ='User Already Exist';

          $response = ['status' => 'false', 'message' => $lang_mess];

          return response()->json($response);
        } else {
          $no = rand(1234, 5677);
          $isMobileType = ($data['type'] == 'mobile');
          $mobileNumber = $isMobileType ? $data['mobile_email'] : $request->mobile;
            $emailAddress = ($data['type'] == 'email') ? $data['mobile_email'] : $request->email;
          $checkforinserted = DB::table('users')->insertGetId([
            'name' => $data['fname'],
            'lname' => $data['lname'],
            'password' => Hash::make($request->password),
            'gender' => null, // Set to null if gender is optional
            'bio' => null, // Set to null if bio is optional
            'dob' => null, // Set to null if dob is optional
            'country_code' => $data['country_code'],
            'mobile' => $mobileNumber,
            'email' => ($data['type'] == 'email') ? $data['mobile_email'] : null,
            'image' => 'public/image/userimage/1748281240793284.jpeg', // Assuming a default image
            'remember_token' => null, // Or omit if you're using Laravel's built-in auth
            'is_verifyEmail' => ($data['type'] == 'email') ? 1 : 0,
            'is_verifyNumber' => ($data['type'] == 'mobile') ? 1 : 0,
            'status'          => 1, // Ensure the user is active so they show in dashboard
            'created_at'      => now(), // Always add timestamps when using DB::table
             'updated_at'      => now(),
        ]);
return response()->json(['status' => 'true', 'message' => 'User registered successfully']);
      if ($checkforinserted) {
        Session::put('user', $checkforinserted);
        $data['id'] = $checkforinserted;
 
        $adminId=1;
        $noti_arr = array('user_id' => $data['id'], 'messages' => 'New user registered' . $data['fname'] . ' ' . $data['lname'],'admin_id'=>$adminId ,'type'=>'newuser');
        Notifications::create($noti_arr);

        $adminId=1;
        $userid=$data['id'];
        $checkforuserexist = User::select('*')->where('id', $userid)->first();
    
        $url=url('user_info/'.$userid);
       
    
           $firebaseResponse = Http::post('https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/Notification/'.$adminId.'.json', [
              'user_id' => $userid,
              'name' => $checkforuserexist->name,
              'message' => 'New user registered ' . $data['fname'] . ' ' . $data['lname'],
              'admin_id' => $adminId,
              'seen' => false,
              'url' => $url,
              'created_at' => date('d-m-y h:i:a'),
          
          ]);
         
        $lang_mess = $request->lang == 'en' ? 'User Registered Successfully' : 'Registro de usuario con éxito';


        $token = array(
          'user_id' => $data['id'],
          'number' =>($data['type'] == 'mobile') ? $data['mobile_email'] : null,
          'token' => $data['deviceToken'],
          'd_type' => $data['deviceType'],
          'created_at' => date('Y-m-d H:i:s')
        );
        Token::create($token);

        $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $data];
      } else {

        $lang_mess = $request->lang == 'en' ? 'Error in Registration' : 'Error en el Registro';

        $response = ['status' => 'failure', 'message' => $lang_mess, 'data' => []];
      }
      return response()->json($response);
    }

  }
/**
 * The New Registered  User Login  to the system.
 */
  public function login(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'mobile_email' => 'required',
      'type' => 'required',
      'lang' => 'required',
      'deviceToken' => 'required',
      'deviceType' => 'required',

    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 422);
    }
    $data = $request->all();
     if($data['type'] == 'mobile'){

       $where = array('mobile' => $data['mobile_email'], 'status' => 0);

     }else{

       $where = array('email' => $data['mobile_email'], 'status' => 0);

     }



    $checkforemailexist = DB::table('users')->where($where)->first();
    if (!empty($checkforemailexist)) {
      // $checkforpassword =  $checkforemailexist->otp;
      // $userotp = $data['otp'];
      // if($checkforemailexist)
      // {
      $token = array(
        'user_id' => $checkforemailexist->id,
        'number' => ($data['type'] =='mobile') ? $data['mobile_email'] : null,
        'token' => $data['deviceToken'],
        'd_type' => $data['deviceType'],
        'created_at' => date('Y-m-d H:i:s')
      );
      Token::create($token);

      $detail = Session::put('user', $checkforemailexist);
      $lang_mess = $request->lang == 'en' ? 'Login Successfully' : 'Iniciar sesión con éxito';

      $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $checkforemailexist];
      return response()->json($response);
      // }
      // else{
      //    $response=['status'=>'failure','message'=>'Credentials are incorrect'];
      //    return response()->json($response);
      // }
    } else {
      $lang_mess = $request->lang == 'en' ? 'Credentials are incorrect' : 'Las credenciales son incorrectas';

      $response = ['status' => 'failure', 'message' => $lang_mess];
      return response()->json($response);
    }
  }
/**
 * In case the User Forgotpassword they can reset it.
 */
  public function forgotpassword(Request $request)
  {
    $data = $request->all();
    $email = $data['email'];
    $password = $data['password'];
    $checkforemailexist = DB::table('users')->where('email', $email)->first();
    if ($checkforemailexist) {
      DB::table('users')->where('id', $checkforemailexist->id)->update([
        'password' => Hash::make($data['password']),
      ]);

      $lang_mess = $request->lang == 'en' ? 'Updated Successfully' : 'Actualizado con éxito';

      $response = ['status' => 'success', 'message' => $lang_mess];
      return response()->json($response);
    } else {

      $lang_mess = $request->lang == 'en' ? 'Email not found' : 'El correo electrónico no encontrado';
      $response = ['status' => 'failure', 'message' => $lang_mess];
      return response()->json($response);
    }
  }

  public function distance($lat1, $long1, $lat2, $long2)
  {
    // $token='AIzaSyCjjdzpr0bET9HsEMsWfEPmA54tuxdiF2E';
    // $token='AIzaSyD3Bmj00bwemXp7TX5RjzWLBs7xt7xUuPI';
    // $token='AIzaSyD3Bmj00bwemXp7TX5RjzWLBs7xt7xUuPI';
    $token = 'AIzaSyAjAyM3lm5no-5MGdh3Rfw8PNQhpRn0TTY';
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=" . $token . "&origins=" . $lat1 . "," . $long1 . "&destinations=" . $lat2 . "," . $long2 . "&mode=driving";

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
    $km = $dist / 1000;
    $aaa = $km / 1.609344;
    $time = @$response_a['rows'][0]['elements'][0]['duration']['text'];
    return array('distance' => round((float) $km, 2), 'time' => $time, 'km' => 'KM');
  }
/**
 *  User profile details.
 */
  public function getprofile(Request $request)
  {
    // http://172.105.172.161/caco/public/image/userimage/1748281240793284.jpeg
    // $Verify_idata=[];
    $data = $request->all();
    $userid = $data['userid'];
    // fetching vehicle list
    $distance = [];
    $rating = '';
    $datamv = Vehicle::select('*')->where('user_id', $userid)->get()->toArray();
    $total_count_ride = Ride::where('userid', $userid)->where('complete_status', 1)->count();
    $ride_latlong = Ride::select('pick_lat', 'pick_long', 'drop_lat', 'drop_long', 'complete_status')->where('complete_status', 1)->where('userid', $userid)->get()->toArray();
    $Postal_address = Postal_address::select('*')->where('userid', $userid)->get()->toArray();
    if (isset($userid)) {

      $Verify_idata = Verify_id::select('*')->where('user_id', $userid)->first();
      $rating = Rating_reviews::where('receiver_id', $userid)->avg('rating');

    }
    // $Verify_idata['']==
    if (empty($Verify_idata)) {
      $Verify_idata = [];
    } else {
      // if(empty($Verify_idata['insurance'])) 
      // {

      $Verify_idata = array(
        'insurance' => $Verify_idata['insurance'] == Null ? '' : $Verify_idata['insurance'],
        'id_proof' => $Verify_idata['id_proof'] == Null ? '' : $Verify_idata['id_proof'],
        'vehicle_plate' => $Verify_idata['vehicle_plate'] == Null ? '' : $Verify_idata['vehicle_plate'],
        'driving_licence' => $Verify_idata['driving_licence'] == Null ? '' : $Verify_idata['driving_licence'],
        'image' => $Verify_idata['image'] == NULL ? '' : $Verify_idata['image'],
        'type' => $Verify_idata['type'] == NULL ? '' : $Verify_idata['type'],
        'otp' => $Verify_idata['otp'] == NULL ? '' : $Verify_idata['otp'],
        'id_proof_expdate' => $Verify_idata['id_proof_expdate'] == NULL ? '' : $Verify_idata['id_proof_expdate'],
        'driving_licence_expdate' => $Verify_idata['driving_licence_expdate'] == NULL ? '' : $Verify_idata['driving_licence_expdate'],
        'vehicle_plate_expdate' => "",
        // 'vehicle_plate_expdate'=>$Verify_idata['vehicle_plate_expdate']==NULL ? '' :  $Verify_idata['vehicle_plate_expdate'],
        'insurance_expdate' => $Verify_idata['insurance_expdate'] == NULL ? '' : $Verify_idata['insurance_expdate'],
        'id_proof_renewdate' => $Verify_idata['id_proof_renewdate'] == NULL ? '' : $Verify_idata['id_proof_renewdate'],
        'driving_licence_renewdate' => $Verify_idata['driving_licence_renewdate'] == NULL ? '' : $Verify_idata['driving_licence_renewdate'],
        'vehicle_plate_renewdate' => $Verify_idata['vehicle_plate_renewdate'] == NULL ? '' : $Verify_idata['vehicle_plate_renewdate'],
        'insurance_renewdate' => $Verify_idata['insurance_renewdate'] == NULL ? '' : $Verify_idata['insurance_renewdate']
      );
    }
    $thetmain = [];
    if (count($ride_latlong) > 0) {
      foreach ($ride_latlong as $key => $value) {
        $thetmain[] = $this->distance($value['pick_lat'], $value['pick_long'], $value['drop_lat'], $value['drop_long']);
      }
    }

    $sum = 0;
    if (!empty($thetmain)) {
      foreach ($thetmain as $key => $value) {
        $sum += round($value['distance']);
      }
    }

    foreach ($datamv as $key => $value) {

      if ($value['vehicle_type'] == 'Hatchback') {
        $data[$key]['vehicle_type_img'] = 'public/image/userimage/Vector (2).png';

      }
      if ($value['vehicle_type'] == 'Sedan') {
        $data[$key]['vehicle_type_img'] = 'public/image/userimage/Vector.png';

      }
      if ($value['vehicle_type'] == 'Convertible') {
        $data[$key]['vehicle_type_img'] = 'public/image/userimage/Vector (3).png';

      }
      if ($value['vehicle_type'] == 'Estate') {
        $data[$key]['vehicle_type_img'] = 'public/image/userimage/Vector (4).png';

      }
      if ($value['vehicle_type'] == 'SUV') {
        $data[$key]['vehicle_type_img'] = 'public/image/userimage/Vector (5).png';

      }
      if ($value['vehicle_type'] == 'Station Wagon') {
        $data[$key]['vehicle_type_img'] = 'public/image/userimage/Vector (6).png';

      }
    }
    // end
    $userdata = [];
    $userdata = User::select('ride_notification', 'messages_notification', 'news_deals_stuff_notification', 'img_status', 'id', 'name', 'lname', 'mobile', 'gender', 'bio', 'dob', 'email', 'is_verifyId', 'is_verifyNumber', 'image', 'profile_status', 'is_verifyEmail', 'offer_notifications_status','offer_ride_status', 'created_at')->where('id', $userid)->first()->toArray();


    $userdata['total_count_ride'] = $total_count_ride;
    $userdata['total_driven_km'] = $sum;

    $userdata['rating_avg'] = round($rating);
    // $verified_status=Verify_id::where('user_id',$userid)->pluck('is_verifyId')->first();

    if ($userdata['is_verifyId'] == 1) {
      $userdata['is_verifyId'] = 1;
      $userdata['is_verifyId_message'] = 'Your request is already taken please wait for admin approval';
    } elseif ($userdata['is_verifyId'] == 2) {
      $userdata['is_verifyId'] = 2;
      $userdata['is_verifyId_message'] = '';
    } else {
      $userdata['is_verifyId'] = 0;
      $userdata['is_verifyId_message'] = '';

    }


    if ($userdata) {
      if (isset($userdata['image']) && empty($userdata['image'])) {
        $userdata['image'] = 'public/image/userimage/1748281240793284.jpeg';
      }

      $response = ['status' => 'success', 'data' => $userdata, 'vehicleslist' => $datamv, 'postal_address' => $Postal_address, 'Verify_idata' => @$Verify_idata];
      return response()->json($response);
    } else {
      $response = ['status' => 'failure', 'message' => 'Data not found', 'vehicleslist' => [], 'postal_address' => [], 'Verify_idata' => []];
      return response()->json($response);
    }
  }
/**
 * The User can Update their Profile.
 */
  public function updateprofile(Request $request)
  {
    $lastimage = '';
    $data = $request->all();
    $email = @$data['email'];
    // $phone=@$data['phone'];
    $userid = $data['userid'];
    $newpassword = @$data['newpassword'];
    $oldpassword = @$data['oldpassword'];
    $firstname = @$data['fname'];
    $lastname = @$data['lname'];
    $gender = @$data['gender'];
    $dob = @$data['dob'];
    $userimage = @$data['userimage'];
    $mobile = @$data['mobile'];
    $bio = @$data['bio'];
    $userdetails = DB::table('users')->where('id', $userid)->first();

    if (isset($newpassword) && isset($oldpassword)) {
      if (!(Hash::check($oldpassword, $userdetails->password))) {
        //when user enter wrong password


        $lang_mess = $request->lang == 'en' ? 'Password you entered is incorrect' : 'La contraseña que ingresaste es incorrecta';

        $response = ['status' => 'failure', 'message' => $lang_mess];
        return response()->json($response);
      } else {
        $updatedpassword = Hash::make($newpassword);
      }
    }
    // else
    // {
    //     $updatedpassword=DB::table('users')->where('id',$userid)->pluck('password')->first();
    // }
    if (isset($userimage) && !empty($userimage)) {
      $name_gen = hexdec(uniqid());
      $img_ext = strtolower($userimage->getClientOriginalExtension());
      $img_name = $name_gen . '.' . $img_ext;
      $up_location = 'public/image/userimage/';
      // return $up_location;
      $lastimage = $up_location . $img_name;
      // return $lastimage;
      $userimage->move($up_location, $img_name);
      $updateArr['img_status'] = 1;
    } else {
      $lastimage = DB::table('users')->where('id', $userid)->pluck('image')->first();
    }
    $userdetails = DB::table('users')->where('id', $userid)->first();
    if (!isset($firstname)) {
      $firstname = $userdetails->name;
    }
    if (!isset($lastname)) {
      $lastname = $userdetails->lname;
    }
    if (!isset($gender)) {
      $gender = $userdetails->gender;
    }
    if (!isset($bio)) {
      $bio = $userdetails->bio;
    }
    if (!isset($dob)) {
      $dob = $userdetails->dob;
    }
    if (!isset($mobile)) {
      $mobile = $userdetails->mobile;
    }
    // update password
    $updateArr['name'] = @$firstname;
    $updateArr['lname'] = @$lastname;
    $updateArr['gender'] = @$gender;
    $updateArr['bio'] = @$bio;
    $updateArr['dob'] = @$dob;
    $updateArr['image'] = @$lastimage;
    $updateArr['mobile'] = @$mobile;
    if (!empty($lastimage)) {
      $updateArr['profile_status'] = 1;
    }
    $userupdate = DB::table('users')->where('id', $userid)
      ->update($updateArr);
    $lang_mess = $request->lang == 'en' ? 'Profile Updated Successfully' : 'Perfil actualizado con éxito';

    $response = ['status' => 'success', 'message' => $lang_mess, 'img' => $lastimage];
    return response()->json($response);


  }
/**
 * The User can Change the Password.
 */
  public function changepassword(Request $request)
  {
    $data = $request->all();
    $password = $data['password'];
    $userid = $data['userid'];
    $newpassword = @$data['newpassword'];
    $oldpassword = @$data['oldpassword'];
    if (isset($newpassword) && isset($oldpassword)) {
      if (!(Hash::check($oldpassword, $password))) {
        //when user enter wrong password

        $lang_mess = $request->lang == 'en' ? 'Password you entered is incorrect' : 'La contraseña que ingresaste es incorrecta';

        $response = ['status' => 'failure', 'message' => $lang_mess];
        return response()->json($response);
      } else {
        $updatedpassword = Hash::make($newpassword);
      }
      $userupdate = DB::table('users')->where('id', $userid)
        ->update([
          'password' => $updatedpassword,

        ]);
    }
    if ($userupdate) {

      $lang_mess = $request->lang == 'en' ? 'Password changed Successfully' : 'Contraseña cambiada con éxito';

      $response = ['status' => 'success', 'message' => $lang_mess];
      return response()->json($response);
    } else {

      $lang_mess = $request->lang == 'en' ? 'Error in password update' : 'Error en la actualización de contraseña';

      $response = ['status' => 'failure', 'message' => $lang_mess];
      return response()->json($response);
    }
  }
/**
 * The User can Add a new Ride .
 */
          public function addride(Request $request)
            {
                $this->check_expiredate();

                // ---------------- VALIDATION ----------------
                $validator = Validator::make($request->all(), [
                    'userid'          => 'required|exists:users,id',
                    'driver_id'       => 'nullable|exists:users,id',
                    'vehicle_id'      => 'nullable|integer',
                    'pick_location'   => 'required|string',
                    'drop_location'   => 'required|string',
                    'pick_lat'        => 'required|numeric',
                    'pick_long'       => 'required|numeric',
                    'drop_lat'        => 'required|numeric',
                    'drop_long'       => 'required|numeric',
                    'date'            => 'required|date',
                    'time'            => 'required',
                    'passenger_count' => 'required|integer|min:1',
                    'stoppage'        => 'nullable|string',
                    'small_bag'       => 'nullable|integer|min:0',
                    'hand_bag'        => 'nullable|integer|min:0',
                    'regular_bag'     => 'nullable|integer|min:0',
                    'oversize_bag'    => 'nullable|integer|min:0',
                    'price'           => 'nullable|numeric|min:0',
                    'instruction'     => 'nullable|string',
                    'instant_booking' => 'nullable|in:true,false',
                    'smoking_allowed' => 'nullable|in:true,false',
                    'pets_allowed'    => 'nullable|in:true,false',
                    'ac_allow'        => 'nullable|in:true,false',
                    'food_allow'      => 'nullable|in:true,false',
                    'ride_type'       => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'failure',
                        'message' => $validator->errors()->first()
                    ], 422);
                }

                // ---------------- PREPARE DATA ----------------
                $userid = $request->userid;
                $driver_id = $request->driver_id ?? $userid;

                  $rideData = [
                      'userid' => $driver_id,
                      'ride_type' => $request->ride_type ?? 'normal',
                      'pick_location' => $request->pick_location,
                      'drop_location' => $request->drop_location,
                      'pick_lat' => $request->pick_lat,
                      'pick_long' => $request->pick_long,
                      'drop_lat' => $request->drop_lat,
                      'drop_long' => $request->drop_long,
                      'vehicle_id' => $request->vehicle_id ?? 66,
                      'pending_small_bag' => $request->small_bag ?? 0,
                      'pending_hand_bag' => $request->hand_bag ?? 0,
                      'pending_regular_bag' => $request->regular_bag ?? 0,
                      'pending_oversize_bag' => $request->oversize_bag ?? 0,
                      'small_bag' => $request->small_bag ?? 0,
                      'hand_bag' => $request->hand_bag ?? 0,
                      'regular_bag' => $request->regular_bag ?? 0,
                      'oversize_bag' => $request->oversize_bag ?? 0,
                      'price' => $request->price ?? 0,
                      'instruction' => $request->instruction ?? '',
                      'date' => $request->date,
                      'time' => $request->time,
                      'rideid' => rand(10000, 50000),
                      'passenger_count' => $request->passenger_count,
                      'stoppage' => $request->stoppage ?? '',
                      'instant_booking' => $request->instant_booking === 'true' ? 1 : 0,
                      'smoking_allowed' => $request->smoking_allowed === 'true' ? 1 : 0,
                      'pets_allowed' => $request->pets_allowed === 'true' ? 1 : 0,
                      'ac_allow' => $request->ac_allow === 'true' ? 1 : 0,
                      'food_allow' => $request->food_allow === 'true' ? 1 : 0,
                      'created_at' => now(),
                      'updated_at' => now(),
                  ];

                  // ---------------- INSERT RIDE ----------------
                  $rideId = DB::table('add_rides')->insertGetId($rideData);

                  if (!$rideId) {
                      return response()->json([
                          'status' => 'failure',
                          'message' => $request->lang == 'en' ? 'Ride not posted, please try again' : 'Viaje no agregado, inténtelo de nuevo'
                      ]);
                  }

                // ---------------- INSERT PICKUP & DROPOFF STOPS ----------------
                Stop::create([
                    'addride_id' => $rideId,
                    'date' => $request->date,
                    'stop_location' => $request->pick_location,
                    'stop_lat' => $request->pick_lat,
                    'stop_long' => $request->pick_long,
                    'created_at' => now(),
                ]);

                Stop::create([
                    'addride_id' => $rideId,
                    'date' => $request->date,
                    'stop_location' => $request->drop_location,
                    'stop_lat' => $request->drop_lat,
                    'stop_long' => $request->drop_long,
                    'created_at' => now(),
                ]);

                // ---------------- INSERT OTHER STOPS ----------------
                if ($request->stoppage) {
                    $stoppages = json_decode($request->stoppage);
                    foreach ($stoppages as $stop) {
                        if (!empty($stop->lat)) {
                            Stop::create([
                                'addride_id' => $rideId,
                                'date' => $request->date,
                                'stop_location' => $stop->name ?? '',
                                'stop_lat' => $stop->lat,
                                'stop_long' => $stop->long ?? 0,
                                'created_at' => now(),
                            ]);
                        }
                    }
                }

                // ---------------- CREATE NOTIFICATION ----------------
                $userInfo = User::select('name', 'lname')->where('id', $userid)->first();
                if ($userInfo) {
                    Notifications::create([
                        'user_id' => $userid,
                        'messages' => $userInfo->name . ' ' . $userInfo->lname . ' created a new ride',
                        'type'=>'newride'
                    ]);
                }

                // ---------------- SEND FIREBASE NOTIFICATION ----------------
                $adminId = 1;
                $firebaseUrl = url('single_ride/'.$rideId);

                Http::post("https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/Notification/{$adminId}.json", [
                    'user_id' => $userid,
                    'name' => $userInfo->name ?? '',
                    'message' => $userInfo->name . ' ' . $userInfo->lname . ' created a new ride',
                    'admin_id' => $adminId,
                    'seen' => false,
                    'url' => $firebaseUrl,
                    'created_at' => now()->format('d-m-Y h:i:s A'),
                ]);

                // ---------------- ALERT NOTIFICATIONS TO USERS ----------------
                $alertCheck = [
                    'pickup_location' => $request->pick_location,
                    'drop_location' => $request->drop_location,
                    'date' => $request->date,
                ];

                    $alertData = Alert_notification::where($alertCheck)->get();
                    foreach ($alertData as $alert) {
                        if ($alert->user_id != $userid) {
                            $tokens = Token::where('user_id', $alert->user_id)->pluck('token')->toArray();
                            foreach ($tokens as $token) {
                                $this->sendFCMViaJson(
                                    $token,
                                    'Ride Search Result!',
                                    'You have new ride available on your searched route!',
                                    (object)['type' => 'ride', 'rideId' => $rideId]
                                );
                            }
                        }
                    }

                    // ---------------- RETURN RESPONSE ----------------
                    return response()->json([
                        'status' => 'success',
                        'message' => $request->lang == 'en' ? 'Ride posted successfully! It will be visible to users once approved by the admin ' : 'Ride posted successfully! It will be visible to users once approved by the admin',
                        'ride_id' => $rideId,
                    ]);
                }

              public function offer_ride_booking($passenger_id, $passenger_count, $ride_id, $driver_id, $newprice, $offer_ride_id, $lang)
              {
                $boookArr = array(
                  'passenger_id' => $passenger_id,
                  'passenger_count' => $passenger_count,
                  'ride_id' => $ride_id,
                  'driver_id' => $driver_id,
                  'price' => $newprice * $passenger_count,
                  'small_bag' => 0,
                  'hand_bag' => 0,
                  'regular_bag' => 0,
                  'oversize_bag' => 0,
                  'instant_status' => 1,
                  'booking_type' => 'offer'
                );
                $res = Apply_ride::create($boookArr);
                if ($res) {
                  $notdata = $this->get_single_ridedata($ride_id, $driver_id);
                  $this->send_notification1($passenger_id, $driver_id, 'Booked Your Ride Rs' . $newprice, 'Ride', $notdata);
                  $notinfo = User::select('name as fname', 'lname')->where('id', $driver_id)->first();

                  $noti_arr = array('user_id' => $passenger_id, 'messages' => 'Booked a Ride with' . $notinfo->fname . ' ' . $notinfo->lname,'type'=>'ridebook');
                  Notifications::create($noti_arr);
                  $lang_mess = $lang == 'en' ? 'Booking Added Successfully' : 'Reserva añadida con éxito';
                  $response = ['status' => 'success', 'message' => $lang_mess];
                  DB::table('push_ride')->where('id', $offer_ride_id)->delete();
                  DB::table('request_ride')->where('ride_id', $offer_ride_id)->delete();
                  DB::table('add_rides')->where('id', $ride_id)->update(['total_passenger' => $passenger_count]);
                  $this->delete_firebase_rides($passenger_id, $offer_ride_id);

                } else {
                  $lang_mess = $lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';
                  $response = ['status' => 'failure', 'message' => $lang_mess];
                }
                return $response;
              }
/**
 * The User can get all the Rides.
 */
              public function getallride(Request $request)
              {
                try {
                  $today = date('Y-m-d h:i a');
                  $today1 = strtotime($today);
                  $data1 = $request->all();
                  $status = @$data1['status'];
                  $userid = $data1['userid'];
                  $milesss = [];
                  $curr_date = strtotime(date('Y-m-d H:i a'));
                  if ($status == 'booked') {
                    $data = $this->booked_rides($userid);
                    if ($data) {
                      $lang_mess = $request->lang == 'en' ? 'data found' : 'datos encontrados';
                      $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $data, 'request' => []];
                    } else {
                      $lang_mess = $request->lang == 'en' ? 'There Is No Ride' : 'no hay paseo';
                      $response = ['status' => 'success', 'message' => $lang_mess, 'data' => [], 'request' => []];
                    }
                  } elseif ($status == 'offerd') {
                    $this->delete_old_pushRide();
                    $data = $this->myoffers_ride($userid);  //New updates add new status  submit offers 
                    $data1 = $this->push_offer_ride_request($userid);  //New updates add new status  submit offers 
                    $lang_mess = $request->lang == 'en' ? 'data found' : 'datos encontrados';
                    $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $data, 'approval' => 'Expired approval', 'request' => $data1];
                  } else {
                    $lang_mess = $request->lang == 'en' ? 'There Is No Ride' : 'no hay paseo';
                    $response = ['status' => 'error', 'message' => $lang_mess, 'data' => [], 'approval' => '', 'request' => []];
                  }
                } catch (Exception $e) {
                  $response = ['status' => 'error', 'message' => $this->commonErrorCodes($e->getCode()), 'data' => [], 'approval' => ''];
                }
                return response()->json($response);
              }
/**
 * The User can Delete Old Ride.
 */
            public function delete_old_pushRide()
            {
              $date = date('Y-m-d');
              Push_ride::where('date', '<', $date)->delete();
              Request_ride::where('date', '<', $date)->delete();
              return true;
            }
            public function push_offer_ride_request($userid)
            {

              $nearby = [];
              $user = DB::table('users')->where('id', $userid)->get()->first();
              if ($user->offer_notifications_status == '1' || $user->offer_notifications_status == 1) {
                $data = Push_ride::whereRaw('NOT FIND_IN_SET(' . $userid . ',deleted_ids)')->where('user_id', '!=', $userid)
                  ->get()->toArray();
                if ($data) {
                  foreach ($data as $value) {
                    $nearby = DB::table("push_ride")
                      ->select('*', DB::raw("6371 * acos(cos(radians(" . @$value['pick_latitude'] . "))
                                  * cos(radians(pick_latitude)) * cos(radians(pick_longitude) - radians(" . @$value['pick_longitude'] . "))
                                  + sin(radians(" . @$value['pick_latitude'] . ")) * sin(radians(pick_latitude))) AS distance"))
                      ->whereRaw('NOT FIND_IN_SET(' . $userid . ',deleted_ids)')
                      ->where('user_id', '!=', $userid)
                      ->having('distance', '<=', 30)
                      ->orderBy('distance', 'asc')
                      ->get()->toArray();
                  }

                  if ($nearby) {
                    $p = '';
                    foreach ($nearby as $key => $val) {
                      $user1 = DB::table('users')->where('id', $val->user_id)->get()->first();
                      $price = DB::table('request_ride')->select('price')->where('ride_id', $val->id)->get()->first();
                      if ($price) {
                        $p = strval($price->price);
                      }
                      $nearby[$key]->user_id = $val->user_id;
                      $nearby[$key]->myprice = $p;
                      $nearby[$key]->passenger_count = $val->passeger_count;
                      $nearby[$key]->pick_location = $val->pickup_location;
                      $nearby[$key]->drop_location = $val->drop_location;
                      $nearby[$key]->pick_lat = $val->pick_latitude;
                      $nearby[$key]->pick_long = $val->pick_longitude;
                      $nearby[$key]->drop_lat = $val->drop_lat;
                      $nearby[$key]->drop_long = $val->drop_long;
                      $nearby[$key]->date = $val->date;
                      $nearby[$key]->name = $user1->name;
                      $nearby[$key]->lname = $user1->lname;
                      $nearby[$key]->image = $user1->image;
                      $nearby[$key]->mobile = $user1->mobile;
                      $nearby[$key]->distance = round($val->distance);
                      $nearby[$key]->request_status = 1;
                      $nearby[$key]->offer_ride_id = $val->id;
                    }
                    return array_values($nearby);
                  } else {
                    return $nearby = [];
                  }
                } else {
                  return $nearby = [];
                }
              } else {
                return $nearby = [];
              }
              // $userdata=User::select('first_name','last_name')->where('id',$userid)->get()->first(); 
              // $name=$userdata->first_name.' '.$userdata->last_name;


            }
            /**
 * The Ride gets Auto Completed.
 */
            public function auto_complete_ride()
            {
              // date_default_timezone_set("America/Santo_Domingo");
              date_default_timezone_set('Asia/Kolkata');

              $today = date('Y-m-d h:i a');
              $data = Ride::select('id', 'userid', 'date', 'time', 'pick_lat', 'pick_long', 'drop_lat', 'drop_long', 'complete_status')->where('complete_status', 0)->get()->toArray();

              // $esti=0;
              foreach ($data as $key => $value) {
                $milesss = $this->distance($value['pick_lat'], $value['pick_long'], $value['drop_lat'], $value['drop_long']);
                $data[$key]['distance'] = (string) $milesss['distance'] . 'KM';
                $data[$key]['hours'] = $milesss['time'];
                $thredate = date('H:i', strtotime($value['date'] . ' ' . $value['time']));
                $timess = strtotime($thredate . '+' . $milesss['time']);
                $newfff = date('h:i A', $timess);
                $data[$key]['estimate_hours'] = $newfff;
              }
              $today1 = strtotime($today);
              foreach ($data as $key => $value) {



                $passenger_Arr = 0;
                $esti = strtotime($value['date'] . '' . $value['estimate_hours']);
                // $esti=strtotime('+2 hours',$esti);
                // $esti=strtotime($esti);

                if ($today1 > $esti) {

                  $update = array('complete_status' => 1);
                  Ride::where('id', $value['id'])->update($update);
                  // if($res) 
                  // {

                  $passenger_Arr = Apply_ride::select('id', 'passenger_id', 'driver_id', 'ride_id')->where('ride_id', $value['id'])->where('instant_status', 1)->where('cancel_booking', 'true')->get()->toArray();
                  if (count($passenger_Arr) > 0) {
                    // echo 'yes1';

                    foreach ($passenger_Arr as $val) {
                      // echo 'yes2';
                      $desc = "Your ride has been completed, it's time to give a review to driver";
                      $this->send_notification_auto_complete_ride('booked', $val['passenger_id'], $val['driver_id'], $value['id'], $desc);

                    }
                    $desc = "Your ride has been completed, please give a review your passenger";
                    $this->send_notification_auto_complete_ride('Ride', $value['userid'], $value['userid'], $value['id'], $desc);
                  } else {
                    $desc = "Your ride has been completed";
                    $this->send_notification_auto_complete_ride('Ride', $value['userid'], $value['userid'], $value['id'], $desc);
                  }


                }




              }

            }


/**
 * The User can get Notification about the Ride.
 */
            public function send_notification_auto_complete_ride($type, $receiver_id, $userid, $ride_id, $desc)
            {
              // date_default_timezone_set("America/Santo_Domingo");
              date_default_timezone_set('Asia/Kolkata');

              $senderdata = User::select('id', 'name', 'lname', 'image')->where('id', $userid)->first();
              $receiver_iddata = User::select('name', 'lname', 'image')->where('id', $receiver_id)->first();

              $checkArr = User::select('ride_notification', 'messages_notification', 'news_deals_stuff_notification')->where('id', $receiver_id)->first();

              if ($checkArr->ride_notification == 0) {
                return false;
              }

              if ($checkArr->messages_notification == 0) {
                return false;
              }

              if ($checkArr->news_deals_stuff_notification == 0) {
                return false;
              }
              $title = 'Hi ' . ' ' . @$receiver_iddata->name . ' ' . @$receiver_iddata->lname;
              // $gettokn_Arr=array('user_id'=>$request->userid);

              $arrayNames = array('user_id' => $receiver_id, 'title' => $title, 'description' => $desc);
              // $result2=$this->init2()->insert('notifications_history',$arrayNames);
              // $userData=$this->init2()->device_token_fetch($userid,$utype);
              $getting_token_info = Token::select('*')->where('user_id', $receiver_id)->get()->toArray();
              $notdata = $this->get_single_ridedata($ride_id, $receiver_id);
              if (!empty($getting_token_info) && count($getting_token_info) > 0) {

              $sender=(string) $senderdata->id;

                foreach ($getting_token_info as $key => $valuedata) {
                  $deviceToken = $valuedata['token'];
                  $data = (object) array('type' => $type, 'id' =>$sender, 'image' => $senderdata->image, 'name' => $senderdata->name, 'lname' => $senderdata->lname, 'ridedata' => $notdata);

                  $response = $this->sendFCMViaJson(
                    $deviceToken,
                    $title . $key,
                    $desc,
                    $data
                );
                }


                
                return true;
              }
            }

          // public function verify_id(Request $request)
          // {

          //   $getU = User::select('name', 'lname')->where('id', $request->userid)->first();
          //   $data['first_name'] = $getU->name;
          //   $data['last_name'] = $getU->lname;
          //   $data['type'] = @$request->type;

          //   if ($files = $request->file('image')) {
          //     $name = $files->getClientOriginalName();
          //     $files->move('public/image/caco_media/', $name);
          //     $data['image'] = 'public/image/caco_media/' . $name;

          //     // $notinfo=User::select('fname','lname')->where('id',$userid)->first();

          //     $noti_arr = array('user_id' => $request->userid, 'messages' => $getU->name . ' ' . $getU->lname . ' submitted new documents to verify');
          //     Notifications::create($noti_arr);

              
          //   }

          //   if ($files = $request->file('id_proof')) {
          //     $name1 = $files->getClientOriginalName();
          //     $files->move('public/image/caco_media/', $name1);
          //     $data['id_proof'] = 'public/image/caco_media/' . $name1;
          //     $noti_arr = array('user_id' => $request->userid, 'messages' => $getU->name . ' ' . $getU->lname . ' submitted new documents to verify');
          //     Notifications::create($noti_arr);


          //     $adminId=1;
          //     $userid=$request->userid;
          //     $checkforuserexist = User::select('*')->where('id', $userid)->first();

          //     $url=url('user_info/'.$userid);
            

          //       $firebaseResponse = Http::post('https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/Notification/'.$adminId.'.json', [
          //           'user_id' => $userid,
          //           'name' => $checkforuserexist->name,
          //           'message' =>  $getU->name . ' ' . $getU->lname . ' submitted Passport/ID documents to verify',
          //           'admin_id' => $adminId,
          //           'seen' => false,
          //           'url' => $url,
          //           'created_at' => date('d-m-y h:i:a'),
                
          //       ]);
          //   }

          //   if ($files = $request->file('driving_licence')) {
          //     $name2 = $files->getClientOriginalName();
          //     $files->move('public/image/caco_media/', $name2);
          //     $data['driving_licence'] = 'public/image/caco_media/' . $name2;
          //     $noti_arr = array('user_id' => $request->userid, 'messages' => $getU->name . ' ' . $getU->lname . ' submitted Driving licence documents to verify');
          //     Notifications::create($noti_arr);
          //     $data['driving_licence_status'] = 1;

          //     $adminId=1;
          //     $userid=$request->userid;
          //     $checkforuserexist = User::select('*')->where('id', $userid)->first();

          //     $url=url('user_info/'.$userid);
            

          //       $firebaseResponse = Http::post('https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/Notification/'.$adminId.'.json', [
          //           'user_id' => $userid,
          //           'name' => $checkforuserexist->name,
          //           'message' =>  $getU->name . ' ' . $getU->lname . 'submitted Driving licence documents to verify',
          //           'admin_id' => $adminId,
          //           'seen' => false,
          //           'url' => $url,
          //           'created_at' => date('d-m-y h:i:a'),
                
          //       ]);

          //   }

          //   if ($files = $request->file('vehicle_plate')) {
          //     $name3 = $files->getClientOriginalName();
          //     $files->move('public/image/caco_media/', $name3);
          //     $data['vehicle_plate'] = 'public/image/caco_media/' . $name3;
          //     $noti_arr = array('user_id' => $request->userid, 'messages' => $getU->name . ' ' . $getU->lname . ' submitted Vehicle plate documents to verify');
          //     Notifications::create($noti_arr);

          //     $adminId=1;
          //     $userid=$request->userid;
          //     $checkforuserexist = User::select('*')->where('id', $userid)->first();

          //     $url=url('user_info/'.$userid);
            

          //       $firebaseResponse = Http::post('https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/Notification/'.$adminId.'.json', [
          //           'user_id' => $userid,
          //           'name' => $checkforuserexist->name,
          //           'message' =>  $getU->name . ' ' . $getU->lname . 'submitted Vehicle plate documents to verify',
          //           'admin_id' => $adminId,
          //           'seen' => false,
          //           'url' => $url,
          //           'created_at' => date('d-m-y h:i:a'),
                
          //       ]);

          //     $data['vehicle_plate_status'] = 1;

          //   }
          //       if ($files = $request->file('insurance')) {
          //         $name4 = $files->getClientOriginalName();
          //         $files->move('public/image/caco_media/', $name4);
          //         $data['insurance'] = 'public/image/caco_media/' . $name4;
          //         $noti_arr = array('user_id' => $request->userid, 'messages' => $getU->name . ' ' . $getU->lname . ' submitted Vehicle Insurance documents to verify');
          //         Notifications::create($noti_arr);

          //         $adminId=1;
          //         $userid=$request->userid;
          //         $checkforuserexist = User::select('*')->where('id', $userid)->first();

          //         $url=url('user_info/'.$userid);
                

          //           $firebaseResponse = Http::post('https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/Notification/'.$adminId.'.json', [
          //               'user_id' => $userid,
          //               'name' => $checkforuserexist->name,
          //               'message' =>  $getU->name . ' ' . $getU->lname . 'submitted Vehicle Insurance documents to verify',
          //               'admin_id' => $adminId,
          //               'seen' => false,
          //               'url' => $url,
          //               'created_at' => date('d-m-y h:i:a'),
                    
          //           ]);
          //       }

          //       if (!empty($request->id_proof_expdate)) {
          //         $data['id_proof_expdate'] = date('Y-m-d', strtotime($request->id_proof_expdate));
          //         $data['id_proff_status'] = 1;

          //       }

          //       if (!empty($request->driving_licence_expdate)) {
          //         $data['driving_licence_expdate'] = date('Y-m-d', strtotime($request->driving_licence_expdate));
          //         $data['driving_licence_status'] = 1;

          //       }

          //       if (!empty($request->vehicle_plate_expdate)) {
          //         $data['vehicle_plate_expdate'] = date('Y-m-d', strtotime($request->vehicle_plate_expdate));

          //       }


          //       if (!empty($request->insurance_expdate)) {
          //         $data['insurance_expdate'] = date('Y-m-d', strtotime($request->insurance_expdate));
          //         $data['insurance_status'] = 1;
          //       }





          //   $count = Verify_id::where('user_id', $request->userid)->count();
          //   if (!empty($request->userid)) {
          //     if ($count > 0) {
          //       $data['updated_at'] = date('Y-m-d H:i:s');
          //       $res = Verify_id::where('user_id', $request->userid)->update($data);

          //       if ($res) {
          //         $idArr = array('is_verifyId' => 1);
          //         User::where('id', $request->userid)->update($idArr);

          //         $this->send_email_documents_verifyed($request->userid);
          //         $lang_mess = $request->lang == 'en' ? 'Please Wait for Admin Approval' : 'Espere la aprobación del administrador';

          //         $response = ['status' => 'success', 'message' => $lang_mess];

          //       } else {

          //         $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo';

          //         $response = ['status' => 'failure', 'message' => $lang_mess];

          //       }
          //     } else {
          //       $data['user_id'] = $request->userid;
          //       $data['created_at'] = date('Y-m-d H:i:s');

          //       $idArr = array('is_verifyId' => 1);
          //       User::where('id', $request->userid)->update($idArr);
          //       $res = Verify_id::create($data);

          //       if ($res) {
          //         $lang_mess = $request->lang == 'en' ? 'Please Wait for Admin Approval ' : 'Espere la aprobación del administrador';

          //         $response = ['status' => 'success', 'message' => $lang_mess];

          //       } else {

          //         $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again ' : 'Algo más está mal, inténtalo de nuevo.';

          //         $response = ['status' => 'failure', 'message' => $lang_mess];

          //       }
          //     }
          //   } else {

          //     $lang_mess = $request->lang == 'en' ? 'User id must ' : 'La identificación del usuario debe';

          //     $response = ['status' => 'failure', 'message' => $lang_mess];

          //   }
          //   return response()->json($response);

          // }

/**
 * It verifies the Id of the User.
 */
            public function verify_id(Request $request)
            {

                
                $userId = $request->userid;

                $getU = User::select('name', 'lname')->where('id', $userId)->first();
                if (!$getU) {
                    return response()->json(['status' => 'failure', 'message' => 'User not found']);
                }

              $key = $request->key; 
              $keyExp = $request->key_exp; 
              // $hasfile = $request->hasFile('file');
              $exp = $request->exp; 
              $status = $request->key_status; 
              $label = $request->label ?? ucfirst(str_replace('_', ' ', $key));

              $data = [
                  'first_name' => $getU->name,
                  'last_name' => $getU->lname,
                  'type' => $request->type,
              ];

              $adminId = 1;
              $basePath = 'public/image/caco_media/';

                  
              if ($request->hasFile('file')) {

                  $file = $request->file('file');
                  $fileName = time() . '_' . $file->getClientOriginalName();
                  $file->move($basePath, $fileName);
                  $data[$key] = $basePath . $fileName;

                  if ($request->has('status')) {
                      $data[$status] = $request->status;
                  }

                  // Notification
                  $message = "{$getU->name} {$getU->lname} submitted {$label} document for verification";
                  $url = url("user_info/{$userId}");

                      Notifications::create(['user_id' => $userId, 'messages' => $message,'type'=>'documentVerify']);

                      Http::post("https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/Notification/{$adminId}.json", [
                          'user_id' => $userId,
                          'name' => $getU->name,
                          'message' => $message,
                          'admin_id' => $adminId,
                          'seen' => false,
                          'url' => $url,
                          'created_at' => now()->format('d-m-y h:i:a'),
                      ]);
                  }

                      if (!empty($exp)) {
                          $data[$keyExp] = date('Y-m-d', strtotime($exp));
                      }

                    $verify = Verify_id::where('user_id', $userId);
                    $exists = $verify->exists();

                    User::where('id', $userId)->update(['is_verifyId' => 1]);

                    if ($exists) {
                        $data['updated_at'] = now();
                        $res = $verify->update($data);
                    } else {
                        $data['user_id'] = $userId;
                        $data['created_at'] = now();
                        $res = Verify_id::create($data);
                    }

                      if ($res) {
                          $this->send_email_documents_verifyed($userId);
                          $msg = $request->lang === 'en' ? 'Document uploaded successfully. You’ll be notified after admin verification.' : 'Document uploaded successfully. You’ll be notified after admin verification.';
                          return response()->json(['status' => 'success', 'message' => $msg]);
                      } else {
                          $msg = $request->lang === 'en' ? 'Something went wrong, please try again' : 'Algo salió mal, inténtalo de nuevo.';
                          return response()->json(['status' => 'failure', 'message' => $msg]);
                      }
                  }

  // **********************************************************************
  /**
 * It verifies the User Details.
 */
              public function verify_details(Request $request)
              {


                if (!empty($request->userid)) {

                  if (!empty($request->email) && !empty($request->userid)) {

                    $detARR = array('email' => $request->email, 'is_verifyEmail' => 1);


                    $res = User::where('id', $request->userid)->update($detARR);
                    if ($res) {

                      $lang_mess = $request->lang == 'en' ? 'Email verified Successfully ' : 'Correo electrónico verificado con éxito';

                      $response = ['status' => 'success', 'message' => $lang_mess];
                    } else {
                      $lang_mess = $request->lang == 'en' ? 'user not match ' : 'el usuario no coincide';

                      $response = ['status' => 'failure', 'message' => $lang_mess];
                    }

                  } elseif (!empty($request->phone_no) && !empty($request->userid)) {

                    $numArr = array('is_verifyNumber' => 1, 'mobile' => $request->phone_no);

                    $res = User::where('id', $request->userid)->update($numArr);

                    // Verify_id::where('user_id',$request->userid)->update($otparr);
                    if ($res) {

                      $lang_mess = $request->lang == 'en' ? 'Number verified Successfully' : 'Número verificado con éxito';

                      $response = ['status' => 'success', 'message' => $lang_mess];
                    } else {
                      $lang_mess = $request->lang == 'en' ? 'user not match' : 'el usuario no coincide';

                      $response = ['status' => 'failure', 'message' => $lang_mess];
                    }
                  }

                } else {
                  $lang_mess = $request->lang == 'en' ? 'user not match' : 'el usuario no coincide';

                  $response = ['status' => 'failure', 'message' => $lang_mess, 'otp' => ''];
                }

                return response()->json($response);
              }

/**
 * Saves the API.
 */
            public function saveapi(Request $request)
            {
              if (!empty($request->userid)) {
                $data['email'] = @$request->email;
                $data['phone_no'] = @$request->phone_no;
                $res = Verify_id::where('user_id', $request->userid)->update($data);
                if ($res) {
                  $lang_mess = $request->lang == 'en' ? 'updated Successfully ' : 'Actualizar con éxito';

                  $response = ['status' => 'success', 'message' => $lang_mess];

                } else {
                  $lang_mess = $request->lang == 'en' ? 'user not match' : 'el usuario no coincide';

                  $response = ['status' => 'failure', 'message' => $lang_mess];

                }
              } else {

                $lang_mess = $request->lang == 'en' ? 'user not match' : 'el usuario no coincide';

                $response = ['status' => 'failure', 'message' => $lang_mess];
              }
              return response()->json($response);
            }

/**
 * It shows the Pages.
 */
              public function pages(Request $request)
            {

              $base = url('/');
              $pagename = $request->val;
              if ($pagename == 'tnc') {
                // $url=asset('/').'page?val=tnc';

                $url = $base . "/termscondition";
                $lang_mess = $request->lang == 'en' ? 'tnc web url' : 'url web de tnc';

                $response = ['status' => 'success', 'message' => $lang_mess, 'url' => $url];
              } elseif ($pagename == 'privacy') {
                // $url=asset('/').'page?val=privacy';
                $url = $base . "/privacy_policy";
                $lang_mess = $request->lang == 'en' ? 'tnc web url' : 'url web de tnc';

                $response = ['status' => 'success', 'message' => $lang_mess, 'url' => $url];
              }

              $data = \App\Models\Page::all();
              return response()->json($data);
            }
/**
 * The User can Add Vehicles.
 */
     public function addvehicle(Request $request)
      {


              if (!empty($request->userid) && !empty($request->country)) {
                $data = array(
                  'plate_number' => @$request->plate_number,
                  'vehicle_model' => @$request->vehicle_model,
                  'vehicle_brand' => @$request->vehicle_brand,
                  'vechicle_color' => @$request->vechicle_color,
                  'vehicle_type' => @$request->vehicle_type,
                  'vechicle_madeyear' => @$request->vechicle_madeyear,
                  'user_id' => $request->userid,
                  'country' => $request->country,
                  'created_at' => date('Y-m-d H:i:s')
                );
                $userimage = $request->file('vehicle_img');
                if ($request->file('vehicle_img')) {
                  // $vehicle_img=$request->file('vehicle_img');
                  $name_gen = hexdec(uniqid());
                  $img_ext = strtolower($request->file('vehicle_img')->getClientOriginalExtension());
                  $img_name = $name_gen . '.' . $img_ext;
                  $up_location = 'public/image/userimage/';
                  // return $up_location;
                  $lastimage = $up_location . $img_name;
                  // return $lastimage;
                  $userimage->move($up_location, $img_name);
                  $data['vehicle_img'] = $lastimage;
                } else {
                  $data['vehicle_img'] = 'public/image/userimage/noimg.png';

                }
                $notinfo = User::select('name as fname', 'lname')->where('id', $request->userid)->first();
                if (empty($request->vehicle_id)) {
                  $res = Vehicle::create($data);
                  $noti_arr = array('user_id' => $request->userid, 'messages' => $notinfo->fname . ' ' . $notinfo->lname . 'Add New Vechicle','type'=>'newVehicle');
                  Notifications::create($noti_arr);

                  $adminId=1;
                  $userid=$request->userid;
                  $checkforuserexist = User::select('*')->where('id', $userid)->first();
            
                  $url=url('vehicle');
                
            
                    $firebaseResponse = Http::post('https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/Notification/'.$adminId.'.json', [
                        'user_id' => $userid,
                        'name' => $checkforuserexist->name,
                        'message' =>  $notinfo->name . ' ' . $notinfo->lname . ' Added New Vehicle',
                        'admin_id' => $adminId,
                        'seen' => false,
                        'url' => $url,
                        'created_at' => date('d-m-y h:i:a'),
                    
                    ]);
                  // $message='Vechicle Added Successfully';
                  $lang_mess = $request->lang == 'en' ? 'Vehicle Added Successfully' : 'Vehiculo Agregado Exitosamente';


                  } else {

                    $noti_arr = array('user_id' => $request->userid, 'messages' => $notinfo->fname . ' ' . $notinfo->lname . 'Update Vechicle' ,'type'=>'updateVehicle');
                    Notifications::create($noti_arr);

                    $adminId=1;
                    $userid=$request->userid;
                    $checkforuserexist = User::select('*')->where('id', $userid)->first();
              
                    $url=url('vehicle/');
                  
              
                      $firebaseResponse = Http::post('https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/Notification/'.$adminId.'.json', [
                          'user_id' => $userid,
                          'name' => $checkforuserexist->name,
                          'message' =>  $notinfo->name . ' ' . $notinfo->lname . ' Update  Vehicle Details',
                          'admin_id' => $adminId,
                          'seen' => false,
                          'url' => $url,
                          'created_at' => date('d-m-y h:i:a'),
                      
                      ]);
                    $res = Vehicle::where('id', $request->vehicle_id)->update($data);
                    // $message='Vechicle update Successfully';
                    $lang_mess = $request->lang == 'en' ? 'Vehicle Updated Successfully' : 'Actualización del vehículo con éxito';

                  }
                  if ($res) {
                    $response = ['status' => 'success', 'message' => $lang_mess];
                  } else {
                    $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                    $response = ['status' => 'failure', 'message' => $lang_mess];
                  }
                } else {

                  $lang_mess = $request->lang == 'en' ? 'user id and country must' : 'ID de usuario y país deben';

                  $response = ['status' => 'failure', 'message' => $lang_mess];
                }
                return response()->json($response);
     }

/**
 * The User can Update the Vehicles.
 */
  public function updateVehicle(Request $request)
  {
    if (!empty($request->vid) && !empty($request->country)) {
      $data = array(
        'plate_number' => @$request->plate_number,
        'vehicle_model' => @$request->vehicle_model,
        'vehicle_brand' => @$request->vehicle_brand,
        'vechicle_color' => @$request->vechicle_color,
        'vehicle_type' => @$request->vehicle_type,
        'vechicle_madeyear' => @$request->vechicle_madeyear,
        'country' => @$request->country,
        'updated_at' => date('Y-m-d H:i:s')
      );
      $res = Vehicle::where('id', $request->vid)->update($data);
      if ($res) {
        $lang_mess = $request->lang == 'en' ? 'Vehicle Updated Successfully' : 'Actualización del vehículo con éxito';

        $response = ['status' => 'success', 'message' => $lang_mess];
      } else {

        $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again ' : 'Algo más está mal, inténtalo de nuevo.';

        $response = ['status' => 'failure', 'message' => $lang_mess];
      }
    } else {
      $lang_mess = $request->lang == 'en' ? 'user id and country must' : 'ID de usuario y país deben';

      $response = ['status' => 'failure', 'message' => $lang_mess];
    }
    return response()->json($response);
  }

/**
 * Sends the Verification OTP.
 */
  public function sentverifyotp(Request $request)
  {
    // $request->email;
    // $request->phone_no;
    $otp = rand(1000, 9999);
    $otparr = array('otp' => $otp);
    if (!empty($request->email)) {
      $info = array(
        'EMail' => @$request->email,
        'otp' => $otp
      );
      $message = '';
      $email = $request->email;
      Mail::send('emails.otp', compact('info'), function ($message) use ($email) {
        $message->to($email)
          ->subject('OTP Send');
        $message->from('onam@gmail.com', 'onam@gmail.com');
      });

      // $res=Verify_id::where('email',$request->email)->update($otparr);
      // if($res)
      // {
      $lang_mess = $request->lang == 'en' ? 'Email sent successfully' : 'Algo más está mal, inténtalo de nuevo.';

      $response = ['status' => 'success', 'message' => $lang_mess, 'otp' => $otp];
      // }
      // else
      // {
      //   $response=['status'=>'failure','message'=>'wrong email ','otp'=>''];      

      // }
    } elseif (!empty($request->phone_no)) {
      // $res=Verify_id::where('phone_no',$request->phone_no)->update($otparr);;
      // if($res)
      // {
      $lang_mess = $request->lang == 'en' ? 'OTP sent successfully to your number' : 'Enviar con éxito otp al número';

      $response = ['status' => 'success', 'message' => $lang_mess, 'otp' => $otp];
      // }
      // else
      // {
      // $response=['status'=>'failure','message'=>'wrong Number ','otp'=>''];      

      // }


    } else {

      $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

      $response = ['status' => 'failure', 'message' => $lang_mess, 'otp' => ''];

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
  /**
 * News Search.
 */
  public function newserach(Request $request)
  {
    $lat = $request->pickup_lat;
    $lon = $request->pickup_long;
    $latitude = $request->drop_lat;
    $longitude = $request->drop_long;
    $dte = $request->date;
    $nearby = DB::table("add_rides");
    $nearby = $nearby->whereDate('date', $dte);
    $nearby = $nearby->get()->toArray();
    $nearby = json_decode(json_encode($nearby), true);


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


/**
 * It shows the Distance.
 */
  public function yoyoyodistance($lat1, $lon1, $lat2, $lon2, $unit = 'k')
  {
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
      return 0;
    } else {
      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);

      $round = round($miles * 1.609344);
      echo "<pre>";
      print_r($round);
      die();

    }
  }
  /**
 * The User can see the Recent History.
 */
  public function recent_history(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'userid' => 'required',
        'pickup_location' => 'required',
        'drop_location' => 'required',
        'date' => 'required',
        'pickup_lat' => 'required',
        'pickup_long' => 'required',
        'drop_lat' => 'required',
        'drop_long' => 'required',
        'passenger_count' => 'required',
      
         ]);

      if ($validator->fails()) {
          return response()->json([
              'message' => $validator->errors()->first(),
          ], 422); 
       }
      $datex = date('Y-m-d');
      $arrdd = array(
        'date' => $datex,
        'user_id' => $request->userid,
        'pickup_location' => $request->pickup_location,
        'drop_location' => $request->drop_location
      );
      $count = Push_ride::where($arrdd)->count();
      $counts = $count > 0 ? 'Yes' : 'No';




    $dte = date('Y-m-d', strtotime($request->date));
    $copyd = array(
      'user_id' => $request->userid,
      'date' => $request->date,
      'pickup_location' => $request->pickup_location,
      'drop_location' => $request->drop_location
    );

    RecentHistory::where($copyd)->delete();


    $data = array(
      'user_id' => $request->userid,
      'pickup_location' => $request->pickup_location,
      'drop_location' => $request->drop_location,
      'pickup_lat' => $request->pickup_lat,
      'pickup_long' => $request->pickup_long,
      'drop_lat' => $request->drop_lat,
      'drop_long' => $request->drop_long,
      'passenger_count' => $request->passenger_count,
      'date' => $request->date,
      'created_at' => date('Y-m-d H:i:s')
    );
    $notify =[];
    $notify=RecentHistory::create($data);
   
    $countsss = Alert_notification::where($copyd)->count();
    // **************************************************************
    $desto = $this->distance($request->pickup_lat, $request->pickup_long, $request->drop_lat, $request->drop_long);


    if ($desto['distance'] > 1) {

      $curr_lat = $request->curr_lat;
      $currlong = $request->curr_long;
      $passeng_count1 = $request->passenger_count;
      $useridsd = $request->userid;
      $lat = $request->pickup_lat;
      $lon = $request->pickup_long;
      $dte = date('Y-m-d', strtotime($request->date));
      $latitude = $request->drop_lat;
      $longitude = $request->drop_long;
      $newData = [];

      $milesss = [];
      $milesss1 = [];
      $nearby = DB::table("sotpage");
      $nearby = $nearby->select('*', DB::raw("6371 * acos(cos(radians(" . $lat . "))
                                * cos(radians(stop_lat)) * cos(radians(stop_long) - radians(" . $lon . "))
                                + sin(radians(" . $lat . ")) * sin(radians(stop_lat))) AS distance"));

      $nearby = $nearby->having('distance', '<=', 30);

      $nearby = $nearby->whereDate('sotpage.date', '=', $dte);

      $nearby = $nearby->orderBy('distance', 'asc');

      $nearby = $nearby->get()->toArray();


      $nearby = json_decode(json_encode($nearby), true);

      $nearby2 = DB::table("sotpage");

      $nearby2 = $nearby2->select('*', DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(stop_lat)) * cos(radians(stop_long) - radians(" . $longitude . "))
                                + sin(radians(" . $latitude . ")) * sin(radians(stop_lat))) AS distance"));
      $nearby2 = $nearby2->having('distance', '<=', 30);
      $nearby2 = $nearby2->whereDate('sotpage.date', '=', $dte);
      $nearby2 = $nearby2->orderBy('distance', 'asc');
      $nearby2 = $nearby2->get()->toArray();

      $nearby2 = json_decode(json_encode($nearby2), true);


      $first_names = array_column($nearby, 'addride_id');
      $first_namesId = array_column($nearby, 'id');
      $last_names = array_column($nearby2, 'addride_id');
      $last_namesId = array_column($nearby2, 'id');
      $ids = array_unique(array_intersect($first_names, $last_names));


      $final = [];
      foreach ($ids as $key => $value) {
        $idp = array_search($value, $first_names);
        $idp2 = array_search($value, $last_names);

        if ($first_namesId[$idp] < $last_namesId[$idp2]) {
          $final[] = $value;
        }
      }

      $nearby5 = DB::table("add_rides");
      $nearby5 = $nearby5->select('add_rides.id as ride_id', 'add_rides.userid as driver_id', 'users.offer_notifications_status', 'users.name', 'users.lname', 'users.mobile', 'users.image', 'add_rides.pick_location', 'add_rides.drop_location', 'add_rides.pick_lat', 'add_rides.pick_long', 'add_rides.drop_lat', 'add_rides.drop_long', 'add_rides.date', 'add_rides.time', 'add_rides.passenger_count', 'add_rides.price', 'users.is_verifyId', 'users.avg_rating', 'add_rides.instruction', 'vehicles.plate_number', 'vehicles.vehicle_brand', 'vehicles.country', 'vehicles.vehicle_model', 'vehicles.vehicle_type', 'vehicles.vechicle_color', 'vehicles.vechicle_madeyear', 'vehicles.vehicle_img', 'add_rides.total_passenger as bookedsheet', 'add_rides.stoppage', 'add_rides.small_bag', 'add_rides.hand_bag', 'add_rides.regular_bag', 'add_rides.smoking_allowed', 'add_rides.pets_allowed', 'add_rides.verified_profile', 'add_rides.instant_booking', 'add_rides.food_allow', 'add_rides.ac_allow', 'add_rides.oversize_bag', 'add_rides.pending_small_bag', 'add_rides.pending_hand_bag', 'add_rides.pending_regular_bag', 'add_rides.pending_oversize_bag');
      $nearby5 = $nearby5->join('users', 'users.id', '=', 'add_rides.userid');
      $nearby5 = $nearby5->join('vehicles', 'vehicles.id', '=', 'add_rides.vehicle_id');
      $nearby5 = $nearby5->whereDate('add_rides.date', '=', $dte);
      $nearby5 = $nearby5->whereIn('add_rides.id', $final);
      $nearby5 = $nearby5->where('add_rides.complete_status', 0);
      $nearby5 = $nearby5->where('add_rides.admin_status', 1);
      $nearby5 = $nearby5->where('add_rides.delete_status', 'active');
      $nearby5 = $nearby5->get()->toArray();
      $nearby5 = json_decode(json_encode($nearby5), true);

      //    $today=date('h:i  a');


      //   $dtett=strtotime($dte.' '.$today);

      // $dte=date('Y-m-d'); 
      $today = date('h:i a');
      $aaj = date('Y-m-d');
      if ($aaj == $dte) {
        $today12 = strtotime($dte . '' . $today);
      } else {
        $today12 = strtotime($dte);
      }
      $newarrarry = [];
      if (!empty($nearby5)) {
        $datedb1 = '';
        foreach ($nearby5 as $key => $value) {

          $datedb1 = strtotime($value['date'] . ' ' . $value['time']);
          if ($datedb1 > $today12) {

            $newArr = [];
            $newarr = array(
              'ride_id' => $value['ride_id'],
              'mobile' => $value['mobile'],
              'driver_id' => $value['driver_id'],
              'offer_notifications_status' => $value['offer_notifications_status'],
              'name' => $value['name'],
              'lname' => $value['lname'],
              'pick_location' => $value['pick_location'],
              'drop_location' => $value['drop_location'],
              'pick_lat' => $value['pick_lat'],
              'pick_long' => $value['pick_long'],
              'drop_long' => $value['drop_long'],
              'drop_lat' => $value['drop_lat'],
              'date' => $value['date'],
              'time' => $value['time'],
              'passenger_count' => $value['passenger_count'],
              'price' => $value['price'],
              'is_verifyId' => $value['is_verifyId'],
              'instruction' => $value['instruction'],
              'plate_number' => $value['plate_number'],
              'vehicle_brand' => $value['vehicle_brand'],
              'country' => $value['country'],
              'vehicle_model' => $value['vehicle_model'],
              'vehicle_type' => $value['vehicle_type'],
              'vechicle_color' => $value['vechicle_color'],
              'vechicle_madeyear' => $value['vechicle_madeyear'],
              'vehicle_img' => $value['vehicle_img'],
              'bookedsheet' => $value['bookedsheet'],
              'stoppage' => $value['stoppage'],
              'small_bag' => $value['small_bag'],
              'hand_bag' => $value['hand_bag'],
              'regular_bag' => $value['regular_bag'],
              'smoking_allowed' => $value['smoking_allowed'],
              'pets_allowed' => $value['pets_allowed'],
              'verified_profile' => $value['verified_profile'],
              'instant_booking' => $value['instant_booking'],
              'food_allow' => $value['food_allow'],
              'ac_allow' => $value['ac_allow'],
              'oversize_bag' => $value['oversize_bag'],
              'image' => $value['image'],
              'pending_small_bag' => $value['pending_small_bag'],
              'pending_hand_bag' => $value['pending_hand_bag'],
              'pending_regular_bag' => $value['pending_regular_bag'],
              'pending_oversize_bag' => $value['pending_oversize_bag']
            );


            $srchArrrr = array('passenger_id' => $useridsd, 'ride_id' => $value['ride_id'], 'cancel_booking' => 'true');
            $rating = Rating_reviews::where('receiver_id', $value['driver_id'])->avg('rating');
            //$newData[$key]['avg_rating']=round($rating,1);
            $newarr['avg_rating'] = round($rating, 1);
            if ($newarr['avg_rating'] == null) {
              $newarr['avg_rating'] = 0;
            }
            $count = Apply_ride::where($srchArrrr)->count();
            if ($count > 0) {
              $newarr['booking_status'] = 1;
            } else {
              $newarr['booking_status'] = 0;
            }
            $newarr['updated_price'] = $value['price'] * $passeng_count1;
            $milesss = $this->distance($lat, $lon, $latitude, $longitude);
            $newarr['distance'] = (string) $milesss['distance'] . 'KM';
            $newarr['passenger_count'] = (int) $value['passenger_count'];
            $newarr['hours'] = $milesss['time'];

            if (!empty($lat) && !empty($lon)) {

              $milesss1 = $this->distance($lat, $lon, $latitude, $longitude);
              $newarr['estimate_distance'] = (string) $milesss1['distance'] . 'KM';
              $newarr['estimate_hours'] = $milesss1['time'];
              $thredate = date('H:i', strtotime($value['date'] . ' ' . $value['time']));
              $timess = strtotime($thredate . '+' . $milesss1['time']);
              $newfff = date('h:i A', $timess);
              $newarr['estimate_hours'] = $newfff;
              $newarr['check_km'] = (string) $milesss1['distance'];

            } else {
              $newarr['estimate_distance'] = '';
              $newarr['estimate_hours'] = '';
              $newarr['estimate_hours'] = '';
            }
            // if(empty($value['image']) || $value['image']='') 
            // {

            //    $newarr['image']='public/image/userimage/1748281240793284.jpeg';    

            // }
            $newarrarry[] = $newarr;
          }
        }


      }
      if (!empty($newarrarry)) {
        $lang_mess = $request->lang == 'en' ? 'Fetch all rides' : 'Obtener todos los viajes';

        if (!empty($request->departure_time)) {
          $newarrarry = $this->filter_ride($request->departure_time, $newarrarry, $request->short_by, $curr_lat, $currlong);
          $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $newarrarry, 'alertcount' => $countsss, 'push_ride_status' => $counts, 'recent_searchId'=>$notify];
        } else {
          $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $newarrarry, 'alertcount' => $countsss, 'push_ride_status' => $counts, 'recent_searchId'=>$notify];
        }
      } else {
        $lang_mess = $request->lang == 'en' ? 'Rides Not available' : 'Paseos No disponible';
        $response = ['status' => 'success', 'message' => $lang_mess, 'data' => [], 'alertcount' => $countsss, 'push_ride_status' => $counts, 'recent_searchId'=>$notify];
      }
    } else {
      $lang_mess = $request->lang == 'en' ? 'Rides Not available' : 'Paseos No disponible';

      $response = ['status' => 'success', 'message' => $lang_mess, 'data' => [], 'alertcount' => $countsss, 'push_ride_status' => $counts, 'recent_searchId'=>$notify];
    }

    return response()->json($response);
  }
/**
 * The User can Filter Ride as per their need.
 */
  public function filter_ride($time, $newarrarry, $short_by, $curr_lat, $currlong)
  {
    $short_by1 = '';
    if ($short_by === "Earliest departure") {
      $short_by1 = 'Earliest_departure';
    }
    if ($short_by === "Lowest price") {
      $short_by1 = 'Lowest_price';

    }
    if ($short_by === "Close to departure point") {
      $short_by1 = 'Close_to_departure_point';

    }
    if ($short_by === "Close to arrival point") {
      $short_by1 = 'Close_to_arrival_point';

    }
    if ($short_by === "Shortest Ride") {
      $short_by1 = 'Shortest_Ride';

    }

    $time = trim($time);
    $times = explode('-', $time);
    $t1 = $times[0];
    $t2 = $times[1];

    $result = array();
    if ($times[0] == 'After') {
      $endTimeStamp = strtotime($t2);
      foreach ($newarrarry as $ride) {
        $rideTimeStamp = strtotime($ride['time']);
        if ($endTimeStamp >= $rideTimeStamp) {
          $result[] = $ride;
        }
      }
    }
    if ($times[0] == 'Before') {

      $endTimeStamp = strtotime($t2);
      foreach ($newarrarry as $ride) {
        $rideTimeStamp = strtotime($ride['time']);
        if ($endTimeStamp <= $rideTimeStamp) {
          $result[] = $ride;
        }
      }

    }
    if ($times[0] !== 'Before' && $times[0] !== 'After') {



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
/**
 * The User can get the search History.
 */
  public function getsearch_history(Request $request)
  {

        $validator = Validator::make($request->all(), [
          'userid' => 'required',
          'lang' => 'required',
            
        ]);

      if ($validator->fails()) {
          return response()->json([
              'message' => $validator->errors()->first(),
          ], 422); 
      }
    $location_arr = Locations::get()->toArray();


    if (!empty($request->userid)) {


      $where = array('id' => $request->userid);


       $offer = Offer::where('is_active', 1)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->get()->toArray();

     

      // if (!empty($request->latitude)) {
      //   $arr = array('latitude' => $request->latitude, 'longitude' => $request->longitude);
      //   User::where($where)->update($arr);
      // }
      $date = date('Y-m-d');
      $data = RecentHistory::select(
        'id',
        'user_id',
        'pickup_location',
        'drop_location',
        'pickup_lat',
        'pickup_long',
        'drop_lat',
        'drop_long',
        'passenger_count',
        'date'
      )
        ->where('user_id', $request->userid)->orderBy('id', 'DESC')->limit(3)
        // ->whereDate('date','>',$date)
        ->get()
        ->toArray();
      $today = date('Y-m-d');
      $today = strtotime($today);
      foreach ($data as $key => $value) {
        $dbdate = strtotime($value['date']);
        if ($dbdate < $today) {
          $data[$key]['showDate'] = 0;
        } else {
          $data[$key]['showDate'] = 1;
        }
      }


      $verified_status = User::where('id', $request->userid)->pluck('is_verifyId')->first();

        if ($verified_status == 1) {
          $mata['is_verifyId'] = $verified_status;
          $mata['is_verifyId_message'] = 'Your request is already taken please wait for admin approval';
        } elseif ($verified_status == 2) {
          $mata['is_verifyId'] = $verified_status;
          $mata['is_verifyId_message'] = '';
        } else {
          $mata['is_verifyId'] = 0;
          $mata['is_verifyId_message'] = '';

        }

        $lang_mess = $request->lang == 'en' ? 'Data fetched successfully' : 'Obtención de datos con éxito';

        $response = ['status' => 'success', 'message' => $lang_mess, 'is_verifyId' => $mata['is_verifyId'], 'is_verifyId_message' => $mata['is_verifyId_message'], 'data' => $data, 'location' => $location_arr ,'offer'=>$offer];
        } else {
        $lang_mess = $request->lang == 'en' ? 'User id must' : 'La identificación del usuario debe';

        $response = ['status' => 'failure', 'message' => $lang_mess, 'data' => [], 'location' => $location_arr,'offer'=> $offer ];

      }
    return response()->json($response);
  }

/**
 * The User can Cancle Ride.
 */
  public function cancel_ride(Request $request)
  {
    //driver
    if (!empty($request->ride_id)) {


      $delArr = array('delete_status' => 'delete');
      $res = Ride::where('id', $request->ride_id)->update($delArr);
      // $res1=Apply_ride::where('ride_id',$request->ride_id)->delete();
      if ($res) {
        $noti = Ride::select('id', 'userid')->where('id', $request->ride_id)->first();

        $datag = Apply_ride::select('*')->where('ride_id', $noti->id)->get()->toArray();

        foreach ($datag as $value) {
          $st = $this->send_notification1($noti->userid, $value['passenger_id'], 'Canceled Ride', 'cancel Ride');

        }
        $notinfo = User::select('name as fname', 'lname')->where('id', $noti->userid)->first();
        $noti_arr = array('user_id' => $noti->userid, 'messages' => $notinfo->fname . ' ' . $notinfo->fname . 'Cancel Ride','type'=>'cancelRide');
        Notifications::create($noti_arr);
        $lang_mess = $request->lang == 'en' ? 'Ride Canceled Successfully' : 'Viaje cancelado con éxito';

        $response = ['status' => 'success', 'message' => $lang_mess];
      } else {

        $lang_mess = $request->lang == 'en' ? 'Unable to delete ride' : 'No se puede eliminar el viaje';

        $response = ['status' => 'success', 'message' => $lang_mess];

      }

    } else {

      $lang_mess = $request->lang == 'en' ? 'User id Must' : 'ID de usuario debe';

      $response = ['status' => 'failure', 'message' => $lang_mess];

    }

    return response()->json($response);

  }
  /**
 * The User can Update their Ride.
 */
  public function update_ride(Request $request)
  {

    if (!empty($request->ride_id)) {
      $passenger_count = $request->passenger_count;
      $instruction = $request->instruction;
      $price = $request->price;
      $updateArr = array();

      if (!empty($request->date)) {
        $updateArr['date'] = $request->date;

        $this->send_notification_to_all('Ride', $request->ride_id, $request->pick_location, $request->drop_location, $request->date);
        $upArr = array('date' => $request->date);
        Stop::where('addride_id', $request->ride_id)->update($upArr);

      }
      if (!empty($request->time)) {
        $updateArr['time'] = $request->time;
      }
      if (!empty($request->pick_location)) {
        $updateArr['pick_location'] = $request->pick_location;
      }
      if (!empty($request->drop_location)) {
        $updateArr['drop_location'] = $request->drop_location;
      }
      if (!empty($request->drop_lat)) {
        $updateArr['drop_lat'] = $request->drop_lat;
      }
      if (!empty($request->drop_long)) {
        $updateArr['drop_long'] = $request->drop_long;
      }
      if (!empty($request->pick_lat)) {
        $updateArr['pick_lat'] = $request->pick_lat;
      }
      if (!empty($request->pick_long)) {
        $updateArr['pick_long'] = $request->pick_long;
      }
      if (!empty($request->stoppage)) {
        $updateArr['stoppage'] = $request->stoppage;
        $stoppage1 = json_decode($request->stoppage);
        Stop::where('addride_id', $request->ride_id)->delete();
        if (count($stoppage1) > 0) {
          foreach ($stoppage1 as $value) {
            $newarr = array('date' => $request->date, 'addride_id' => $request->ride_id, 'stop_location' => $value->name, 'stop_lat' => $value->lat, 'stop_long' => $value->long, 'created_at' => date('Y-m-d H:i:s'));
            Stop::create($newarr);
          }
        }
      }
      if (!empty($passenger_count)) {
        $updateArr['passenger_count'] = $passenger_count;
      }
      if (!empty($instruction)) {
        $updateArr['instruction'] = $instruction;
      }

      if (!empty($price)) {
        $updateArr['price'] = $price;
      }
      if (isset($request->small_bag)) {
        $updateArr['small_bag'] = $request->small_bag;
        $updateArr['pending_small_bag'] = $request->small_bag;
      }
      if (isset($request->hand_bag)) {
        $updateArr['hand_bag'] = $request->hand_bag;
        $updateArr['pending_hand_bag'] = $request->hand_bag;
      }

      if (isset($request->regular_bag)) {
        $updateArr['regular_bag'] = $request->regular_bag;
        $updateArr['pending_regular_bag'] = $request->regular_bag;
      }
      if (isset($request->oversize_bag)) {
        $updateArr['oversize_bag'] = $request->oversize_bag;
        $updateArr['pending_oversize_bag'] = $request->oversize_bag;
      }
      $resp = Ride::where('id', $request->ride_id)->update($updateArr);
      if ($resp) {
        $lang_mess = $request->lang == 'en' ? 'Update Ride Successfully' : 'Actualizar viaje con éxito';
        $response = ['status' => 'success', 'message' => $lang_mess];
      } else {
        $lang_mess = $request->lang == 'en' ? 'Unable to update plase try again' : 'no se puede actualizar por favor inténtalo de nuevo';
        $response = ['status' => 'failure', 'message' => $lang_mess];
      }
    } else {
      $lang_mess = $request->lang == 'en' ? 'Ride_id id Must' : 'Ride_id id Debe';
      $response = ['status' => 'failure', 'message' => $lang_mess];
    }
    return response()->json($response);


  }
/**
 * Send Notification to All Users.
 */
  public function send_notification_to_all($type1 = 'Ride', $data1 = null, $pick_location, $drop_location, $date)
  {


    $where = array('pickup_location' => $pick_location, 'drop_location' => $drop_location, 'date' => $date);
    $datass = RecentHistory::select('user_id')->where($where)->get()->toArray();
    if (count($datass) < 1) {
      return false;
    } else {

      foreach ($datass as $val) {
        $this->call_back($val['user_id'], $where, $data1, $date);
      }

    }

  }
/**
 * Users can Call back.
 */
  public function call_back($receiver_id, $where, $data1, $date)
  {

    $metaid = Ride::select('userid')->where('id', $data1)->first();
    $senderdata = User::select('name', 'lname')->where('id', $metaid->userid)->first();
    $receiver_iddata = User::select('name', 'lname')->where('id', $receiver_id)->first();
    if ($metaid->userid == $receiver_id) {
      return false;
    }

    $alertdata = Alert_notification::where($where)->first();
    $alertdata->date = $date;
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

    $title = 'Hi ' . @$receiver_iddata->name . ' ' . @$receiver_iddata->lname;
    $desc = 'You have new ride available on your searched route !';

    $arrayNames = array('user_id' => $receiver_id, 'title' => $title, 'description' => $desc);
    $getting_token_info = Token::select('*')->where('user_id', $receiver_id)->get()->toArray();

    if (!empty($getting_token_info) && count($getting_token_info) > 0) {
      $API_SERVER_KEY = "AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
      foreach ($getting_token_info as $key => $valuedata) {
        $registrationIds = $valuedata['token'];
        $data = (object) array('type' => 'New ride', 'ridedata' => $alertdata);
        $msg = array
        (
          'body' => $desc,
          'title' => $title,
          'icon' => 'myicon',/*Default Icon*/
          'sound' => 'mySound'/*Default sound*/
        );
        $fields = array
        (
          'to' => $registrationIds,
          'notification' => $msg,
          'data' => $data

        );
        $headers = array
        (
          'Authorization: key=' . $API_SERVER_KEY,
          'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result[] = curl_exec($ch);
        curl_close($ch);
      }
      if (!empty($result)) {
        $arrayName = true;
      } else {
        $arrayName = false;
      }
    } else {
      $arrayName = false;
    }
    // return  $response=['status'=>'success','message'=>'test ','rss'=>$arrayName]; 
    return $arrayName;


  }
  /**
 * The User can Book a Ride.
 */
            public function booking(Request $request)
{
    // ---------------- VALIDATION ----------------
    $validator = Validator::make($request->all(), [
        'passenger_id'   => 'required|exists:users,id',
        'driver_id'      => 'required|exists:users,id',
        'ride_id'        => 'required|exists:add_rides,id',
        'passenger_count'=> 'required|integer|min:1',

        'small_bag'      => 'nullable|integer|min:0',
        'hand_bag'       => 'nullable|integer|min:0',
        'regular_bag'    => 'nullable|integer|min:0',
        'oversize_bag'   => 'nullable|integer|min:0',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => 'failure',
            'message' => $validator->errors()->first()
        ], 422);
    }

    $lang = 'en';

    // ---------------- FETCH RIDE ----------------
    $ride = Ride::find($request->ride_id);
    if (!$ride) {
        return response()->json(['status'=>'failure','message'=>'Ride not found']);
    }

    // ---------------- BAG CALC ----------------
    $updatedBags = [
        'pending_small_bag'     => $ride->pending_small_bag - intval($request->small_bag ?? 0),
        'pending_hand_bag'      => $ride->pending_hand_bag - intval($request->hand_bag ?? 0),
        'pending_regular_bag'   => $ride->pending_regular_bag - intval($request->regular_bag ?? 0),
        'pending_oversize_bag'  => $ride->pending_oversize_bag - intval($request->oversize_bag ?? 0),
    ];

    foreach ($updatedBags as $bagType => $value) {
        if ($value < 0) {
            return response()->json([
                'status'  => 'failure',
                'message' => "Bag limit exceeded: $bagType"
            ]);
        }
    }

    // ---------------- SEAT VALIDATION ----------------
    $totalAfterBooking = $ride->total_passenger + $request->passenger_count;

    if ($totalAfterBooking > $ride->passenger_count) {
        return response()->json([
            'status' => 'failure',
            'message' => "You have exceeded seat limit"
        ]);
    }

    // ---------------- PRICE CALC ----------------
    $bookingPrice = $ride->price * $request->passenger_count;

    // ---------------- STORE BOOKING DATA ----------------
    $applyData = [
        'passenger_id'     => $request->passenger_id,
        'driver_id'        => $request->driver_id,
        'ride_id'          => $request->ride_id,
        'passenger_count'  => $request->passenger_count,
        'price'            => $bookingPrice,
        'small_bag'        => $request->small_bag ?? 0,
        'hand_bag'         => $request->hand_bag ?? 0,
        'regular_bag'      => $request->regular_bag ?? 0,
        'oversize_bag'     => $request->oversize_bag ?? 0,
    ];

    // Fetch user and ride data
    $rideData   = $ride;
    $driverData = User::find($request->driver_id);
    $passenger  = User::find($request->passenger_id);
    $notdata    = $this->get_single_ridedata($ride->id, $request->driver_id);

    // ---------------- INSTANT BOOKING ----------------
    if ($ride->instant_booking == 1) {

        $applyData['instant_status'] = 1;

        // Insert booking
        Apply_ride::create($applyData);

        // SAFE RIDE UPDATE
        $ride->total_passenger      = $totalAfterBooking;
        $ride->pending_small_bag    = $updatedBags['pending_small_bag'];
        $ride->pending_hand_bag     = $updatedBags['pending_hand_bag'];
        $ride->pending_regular_bag  = $updatedBags['pending_regular_bag'];
        $ride->pending_oversize_bag = $updatedBags['pending_oversize_bag'];
        $ride->save();

        // Notification
        $this->send_notification1(
            $request->passenger_id,
            $request->driver_id,
            "Received New Booking!",
            'Ride',
            $notdata
        );

        // SMS
        $smsMessage = "SMOOTHRIDE Alert: Your ride from {$rideData->pick_location} "
                    . "to {$rideData->drop_location} is confirmed. "
                    . "Driver: {$driverData->name}, Contact: {$driverData->mobile}.";

        try {
            $SmsService = new SmsService();
            $templateId = 1007069034521817674;
            $response = $SmsService->sendOtp($passenger->mobile, $smsMessage, $templateId);

            if (!$response || ($response['status'] ?? '') != 'success') {
                \Log::warning("SMS failed for passenger_id {$passenger->id}", [
                    'mobile' => $passenger->mobile,
                    'message' => $smsMessage,
                    'response' => $response
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("SMS exception for passenger_id {$passenger->id}: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Booking Added Successfully'
        ]);
    }

    // ---------------- NON-INSTANT BOOKING ----------------
    $applyData['instant_status'] = 0;
    $applyData['cancel_booking'] = 'true';

    Apply_ride::create($applyData);

    $notifymsg = $passenger->name . " sent you a request for a ride: Rs " . $bookingPrice;

    $this->send_notification1(
        $request->passenger_id,
        $request->driver_id,
        $notifymsg,
        'Ride',
        $notdata
    );

    return response()->json([
        'status' => 'success',
        'message' => 'Your request has been submitted. The driver will respond shortly.'
    ]);
}

/**
 * Vehicle Brand.
 */  
              public function vehicle_brand(Request $request)
              {
                $data = Imported_brand::select('brand_name')->distinct('brand_name')->get();
                foreach ($data as $key => $value) {
                  $data[$key]['model'] = Imported_brand::select('model_name')
                    ->where('brand_name', $value->brand_name)->where('country','India')->get()->toArray();
                }
                $datad = Color::select('id', 'color_name')->get()->toArray();

                $response = ['status' => 'success', 'message' => 'fetch', 'data' => $data, 'color' => $datad];
                return response()->json($response);
              }
              public function vehicle_list(Request $request)
              {
                if (!empty($request->userid)) {
                  $is_verifyId = User::where('id', $request->userid)->pluck('is_verifyId')->first();
                  $data = Vehicle::select('*')->where('user_id', $request->userid)->get()->toArray();
                  $lang_mess = $request->lang == 'en' ? 'Data fetched successfully' : 'obtener datos con éxito';
                  $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $data, 'is_verifyId' => $is_verifyId];
                } else {
                  $lang_mess = $request->lang == 'en' ? 'User id must' : 'La identificación del usuario debe';
                  $response = ['status' => 'failure', 'message' => 'User id must', 'data' => [], 'is_verifyId' => ''];
                }
                return response()->json($response);
              }
              public function delete_vehicle(Request $request)
              {

                if (!empty($request->vehicle_id)) {
                  $del_res = Vehicle::where('id', $request->vehicle_id)->delete();
                  if ($del_res) {
                    $lang_mess = $request->lang == 'en' ? 'Vehicle Deleted Successfully' : 'Eliminar vehículo con éxito';
                    $response = ['status' => 'success', 'message' => $lang_mess];
                  } else {
                    $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';
                    $response = ['status' => 'failure', 'message' => $lang_mess];
                  }
                } else {
                  $response = ['status' => 'failure', 'message' => ' id must'];

                }
                return response()->json($response);

              }
/**
 * The User can Delete the Booking.
 */
              public function Delete_applybooking(Request $request)
              {
                $response=[];
                $Arr = array('passenger_id' => $request->passenger_id, 'ride_id' => $request->ride_id ,'id' =>$request->request_id);
                $updateArr = array('cancel_booking' => 'Cancelled','cancel_reason'=>'Request has been cancelled by driver');
                $delres = Apply_ride::where($Arr)->update($updateArr);

                $meta = Ride::select('userid')->where('id', $request->ride_id)->first();
                if ($delres) {
                  if ($meta['instant_status'] === 1) {
                    $metadata = Apply_ride::where($Arr)->get()->first();
                    $check_bags = Ride::select('*')->where('id', $request->ride_id)->get()->first();
                    $sbag = $check_bags['pending_small_bag'] - $metadata['small_bag'];
                    $hn_bag = $check_bags['pending_hand_bag'] - $metadata['hand_bag'];
                    $regular_bag = $check_bags['pending_regular_bag'] - $metadata['regular_bag'];
                    $oversize_bag = $check_bags['pending_oversize_bag'] - $metadata['oversize_bag'];
                    $updateRideArr1 = array(
                      'pending_small_bag' => $sbag,
                      'pending_hand_bag' => $hn_bag,
                      'pending_regular_bag' => $regular_bag,
                      'pending_oversize_bag' => $oversize_bag
                    );
                    Ride::where('id', $request->ride_id)->update($updateRideArr1);
                  }
                  $this->send_notification1($meta['userid'], $request->passenger_id, 'Canceled Your Ride', 'delete Ride');

                  $notinfo = User::select('name as fname', 'lname')->where('id', $meta['userid'])->first();
                  $noti_arr = array('user_id' => $request->passenger_id, 'messages' => 'Cancel  Ride of ' . $notinfo->fname . '' . $notinfo->lname,'type'=>'cancelRide');
                  Notifications::create($noti_arr);
                  $lang_mess = $request->lang == 'en' ? 'Ride Cancelled Successfully' : 'Viaje cancelado con éxito';
                  $response = ['status' => 'success', 'message' => $lang_mess];
                } else {
                  $lang_mess = $request->lang == 'en' ? 'Ride Cancelled Successfully' : 'Viaje cancelado con éxito';
                  $response = ['status' => 'failure', 'message' => $lang_mess];
                }
                return response()->json($response);
              }

      /**
 * The User can Accept Ride.
 */
             public function Accept_ride(Request $request)
                {
                    // ---------------- VALIDATION ----------------
                    $validator = Validator::make($request->all(), [
                        'passenger_id' => 'required|exists:users,id',
                        'request_id' => 'required|exists:apply_ride,id',
                        'ride_id'      => 'required|exists:add_rides,id',
                        'lang'         => 'nullable|in:en,es'
                    ]);

                    if ($validator->fails()) {
                        return response()->json([
                            'status' => 'failure',
                            'message' => $validator->errors()->first()
                        ], 422);
                    }

                    // ---------------- FETCH DATA ----------------
                    $ride = Ride::find($request->ride_id);
                    $applyRide = Apply_ride::where('passenger_id', $request->passenger_id)
                                            ->where('ride_id', $request->ride_id)
                                            ->where('id',$request->request_id)
                                            ->first();

                    if (!$ride || !$applyRide) {
                        $lang_mess = $request->lang == 'en' ? 'Ride or booking not found' : 'Ride or booking not found';
                        return response()->json(['status' => 'failure', 'message' => $lang_mess]);
                    }

                    // ---------------- CALCULATE BAG COUNTS ----------------
                    $ride->pending_small_bag    -= intval($applyRide->small_bag);
                    $ride->pending_hand_bag     -= intval($applyRide->hand_bag);
                    $ride->pending_regular_bag  -= intval($applyRide->regular_bag);
                    $ride->pending_oversize_bag -= intval($applyRide->oversize_bag);

                    // ---------------- CHECK TOTAL PASSENGERS ----------------
                    $total_passengers = $ride->total_passenger + $applyRide->passenger_count;

                    if ($total_passengers > $ride->passenger_count) {
                        $lang_mess = $request->lang == 'en' ? 'You have exceeded the limit' : 'Estás excedido el límite';
                        return response()->json(['status' => 'failure', 'message' => $lang_mess]);
                    }

                    // ---------------- UPDATE BOOKING ----------------
                    $applyRide->update([
                        'instant_status' => 1,
                        'confirm_book'   => 1
                    ]);

                    // ---------------- UPDATE RIDE ----------------
                    $ride->total_passenger = $total_passengers;
                    $ride->save();

                    // ---------------- SEND NOTIFICATIONS ----------------
                    $this->send_notification1($ride->userid, $request->passenger_id, 'Great news! The driver has accepted your ride.', 'Accept Ride');

                    
                    $passengerInfo = User::find($request->passenger_id);
                    $driverData = User::find($ride->userid);
                    if ($passengerInfo) {
                        Notifications::create([
                            'user_id' => $ride->userid,
                            'messages' => 'Accepted ride request of ' . $passengerInfo->name . ' ' . $passengerInfo->lname,
                            'type'=>'acceptRide'
                        ]);
                    }
                     $smsMessage = "SMOOTHRIDE Alert: Your ride from {$ride->pick_location} to {$ride->drop_location} is confirmed. Driver: {$driverData->name}, Contact: {$driverData->mobile}.";

                        try {
                            $SmsService = new SmsService();
                            $templateId=1007069034521817674;
                            $response = $SmsService->sendOtp($passengerInfo->mobile, $smsMessage,$templateId);

                            if (!$response || ($response['status'] ?? '') != 'success') {
                                \Log::warning("SMS failed for passenger_id {$passengerInfo->id}", [
                                    'mobile' => $passengerInfo->mobile,
                                    'messag: mixed: mixede' => $smsMessage,
                                    'response' => $response
                                ]);
                            }
                        } catch (\Exception $e) {
                            \Log::error("SMS exception for passenger_id {$passengerInfo->id}: " . $e->getMessage());
                        }


                    
                    // ---------------- UPDATE RIDE BAGS ----------------
                    $ride->save(); // Save updated bag counts


                   
                    // ---------------- SUCCESS RESPONSE ----------------
                    $lang_mess = $request->lang == 'en' ? 'Booking updated successfully' : 'Actualización de reserva con éxito';
                    return response()->json([
                        'status' => 'success',
                        'message' => $lang_mess
                    ]);
              }



/**
 * The User can add Postal Address.
 */

              public function postal_address(Request $request)
            {
              $postal_id = $request->id;
              $userid = $request->userid;
              $postal_address = $request->postal_address;
              $postal_lat = $request->postal_lat;
              $postal_long = $request->postal_long;

              $postatArr = array(
                'postal_address' => $postal_address,
                'postal_lat' => $postal_lat,
                'postal_long' => $postal_long
              );

              if (empty($postal_id)) {
                $postatArr['userid'] = $userid;
                $postatArr['created_at'] = date('Y-m-d H:i:s');
                $res = Postal_address::create($postatArr);
                if ($res) {

                  $noti_arr = array('user_id' => $userid, 'messages' => 'Add Postal Address','type'=>'postalAddress');
                  Notifications::create($noti_arr);

                  $lang_mess = $request->lang == 'en' ? 'Added Successfully' : 'Agregado exitosamente';

                  $response = ['status' => 'success', 'message' => $lang_mess];
                } else {

                  $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again1' : 'Algo más está mal, inténtalo de nuevo1';

                  $response = ['status' => 'failure', 'message' => $lang_mess];
                }
              } else {

                $where = array('id' => $postal_id, 'userid' => $userid);
                $postatArr['updated_at'] = date('Y-m-d H:i:s');
                $res = Postal_address::where($where)->update($postatArr);
                if ($res) {

                  $noti_arr = array('user_id' => $userid, 'messages' => 'Update Postal Address' ,'type'=>'updatePostalAddress');
                  Notifications::create($noti_arr);

                  $lang_mess = $request->lang == 'en' ? 'Updated Successfully' : 'Actualizado con éxito';

                  $response = ['status' => 'success', 'message' => $lang_mess];

                } else {

                  $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo2';

                  $response = ['status' => 'failure', 'message' => $lang_mess];

                }

              }
              return response()->json($response);
            }
/**
 * Postal Address List.
 */
            public function postal_addresslist(Request $request)
            {

              $data = Postal_address::select('*')->where('userid', $request->userid)->get()->toArray();
              if ($data) {

                $lang_mess = $request->lang == 'en' ? 'Data Found' : 'Datos encontrados';

                $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $data];
              } else {

                $lang_mess = $request->lang == 'en' ? 'Data Not Found' : 'Datos no encontrados';

                $response = ['status' => 'success', 'message' => $lang_mess, 'data' => []];
              }

              return response()->json($response);


            }
/**
 * The User can Delete Booking.
 */

            public function delete_booking(Request $request)//user end delete booking
            {
              $where = array('passenger_id' => $request->passenger_id, 'ride_id' => $request->ride_id);
              // $rado_count=Apply_ride::where('ride_id',$request->ride_id)->where('cancel_booking','true')->count();

              if (!empty($request->passenger_id) && !empty($request->ride_id)) {
                $check_bags = DB::table('add_rides')->where('id', $request->ride_id)->first();
                $instant_status = Apply_ride::select('*')->where($where)->first()->toArray();

                if ($check_bags->pending_small_bag !== $check_bags->small_bag && $check_bags->pending_small_bag !== $check_bags->hand_bag && $check_bags->pending_small_bag !== $check_bags->regular_bag && $check_bags->pending_small_bag !== $check_bags->regular_bag && $check_bags->pending_small_bag !== $check_bags->oversize_bag) {
                  $sbag = $instant_status['small_bag'] + $check_bags->pending_small_bag;
                  $hn_bag = $instant_status['hand_bag'] + $check_bags->pending_small_bag;
                  $regular_bag = $instant_status['regular_bag'] + $check_bags->pending_small_bag;
                  $oversize_bag = $instant_status['oversize_bag'] + $check_bags->pending_small_bag;
                  $new_passengerArr = array(
                    'pending_small_bag' => $sbag,
                    'pending_hand_bag' => $hn_bag,
                    'pending_regular_bag' => $regular_bag,
                    'pending_oversize_bag' => $oversize_bag
                  );
                }



                $updateArr = array('cancel_booking' => 'Cancelled');


                $ride_data = Ride::select('userid', 'passenger_count', 'total_passenger')->where('id', $instant_status['ride_id'])->first()->toArray();
                if ($instant_status['instant_status'] == 1)  //this is instant booking 
                {
                  $updateArr['confirm_book'] = 0;

                  $total_passenger = $ride_data['total_passenger'] - $instant_status['passenger_count'];

                  $new_passengerArr = array('total_passenger' => $total_passenger);

                  Ride::where('id', $instant_status['ride_id'])->update($new_passengerArr);

                  $res = Apply_ride::where($where)->update($updateArr);

                  if ($res) {
                    $this->send_notification1($request->passenger_id, $ride_data['userid'], 'Canceled Your Ride', 'Cancel Ride');
                    $notinfo = User::select('name as fname', 'lname')->where('id', $ride_data['userid'])->first();

                    $noti_arr = array('user_id' => $ride_data['userid'], 'messages' => 'Cancel  Ride' . $notinfo->fname . '' . $notinfo->lname ,'type'=>'cancelRide');
                    Notifications::create($noti_arr);


                    $metadata = Apply_ride::where($where)->get()->first();
                    $check_bags = Ride::select('*')->where('id', $request->ride_id)->get()->first();
                    $sbag = $check_bags['pending_small_bag'] + $metadata['small_bag'];
                    $hn_bag = $check_bags['pending_hand_bag'] + $metadata['hand_bag'];
                    $regular_bag = $check_bags['pending_regular_bag'] + $metadata['regular_bag'];
                    $oversize_bag = $check_bags['pending_oversize_bag'] + $metadata['oversize_bag'];


                    $updateRideArr1 = array(
                      'pending_small_bag' => $sbag,
                      'pending_hand_bag' => $hn_bag,
                      'pending_regular_bag' => $regular_bag,
                      'pending_oversize_bag' => $oversize_bag
                    );
                    Ride::where('id', $request->ride_id)->update($updateRideArr1);

                    // Apply_ride::where($where)->delete();
                    $lang_mess = $request->lang == 'en' ? 'Ride Canceled Successfully' : 'Cancelar con éxito';


                    $response = ['status' => 'success', 'message' => $lang_mess];
                  } else {

                    $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';
                    $response = ['status' => 'failure', 'message' => $lang_mess];
                  }
                } elseif ($instant_status['instant_status'] == 0) //this is not  instant booking 
                {
                  $updateArr['confirm_book'] = 0;
                  $res = Apply_ride::where($where)->update($updateArr);
                  if ($res) {

                    $this->send_notification1($request->passenger_id, $ride_data['userid'], 'Canceled Your Ride', 'Cancel Ride');
                    $notinfo = User::select('name as fname', 'lname')->where('id', $ride_data['userid'])->first();

                    $noti_arr = array('user_id' => $ride_data['userid'], 'messages' => 'Cancel Ride' . $notinfo->fname . '' . $notinfo->lname,'type'=>'cancelRide');
                    Notifications::create($noti_arr);

                    $metadata = Apply_ride::where($where)->get()->first();
                    $check_bags = Ride::select('*')->where('id', $request->ride_id)->get()->first();
                    $sbag = $check_bags['pending_small_bag'] + $metadata['small_bag'];
                    $hn_bag = $check_bags['pending_hand_bag'] + $metadata['hand_bag'];
                    $regular_bag = $check_bags['pending_regular_bag'] + $metadata['regular_bag'];
                    $oversize_bag = $check_bags['pending_oversize_bag'] + $metadata['oversize_bag'];


                    $updateRideArr1 = array(
                      'pending_small_bag' => $sbag,
                      'pending_hand_bag' => $hn_bag,
                      'pending_regular_bag' => $regular_bag,
                      'pending_oversize_bag' => $oversize_bag
                    );
                    Ride::where('id', $request->ride_id)->update($updateRideArr1);

                    $lang_mess = $request->lang == 'en' ? 'Ride Canceled Successfully' : 'Cancelar con éxito';

                    $response = ['status' => 'success', 'message' => $lang_mess];
                  } else {


                    $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                    $response = ['status' => 'failure', 'message' => $lang_mess];
                  }
                } else {
                  $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                  $response = ['status' => 'failure', 'message' => $lang_mess];

                }

              } else {
                $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                $response = ['status' => 'failure', 'message' => $lang_mess];
              }
              return response()->json($response);

            }

 /**
 * The User can Update Profile Image.
 */
          public function updateprofileimg(Request $request)
          {

            $userimage = $request->file('profile_img');
            if ($request->file('profile_img')) {
              // $userimage='';
              $name_gen = hexdec(uniqid());
              $img_ext = strtolower($request->file('profile_img')->getClientOriginalExtension());
              $img_name = $name_gen . '.' . $img_ext;
              $up_location = 'public/image/userimage/';
              // return $up_location;
              $lastimage = $up_location . $img_name;
              // return $lastimage;
              $userimage->move($up_location, $img_name);


              $lang_mess = $request->lang == 'en' ? 'Profile image updated' : 'Actualizar imagen de perfil';

              $response = ['status' => 'success', 'message' => $lang_mess, 'url' => $lastimage];

            } else {

              $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again ' : 'Algo más está mal, inténtalo de nuevo.';

              $response = ['status' => 'failure', 'message' => $lang_mess, 'url' => ''];

            }
            return response()->json($response);
          }
/**
 * History.
 */
          public function history(Request $request)
          {

            $date = date('Y-m-d');
            if (!empty($request->userid)) {
              $data = RecentHistory::select('*')->where('user_id', $request->userid)->orderBy('id', 'desc')->get()->toArray();
              if ($data) {
                $today = date('Y-m-d');
                $today = strtotime($today);
                foreach ($data as $key => $value) {
                  $dbdate = strtotime($value['date']);
                  if ($dbdate < $today) {
                    $data[$key]['showDate'] = 0;
                  } else {
                    $data[$key]['showDate'] = 1;
                  }
                }

                $lang_mess = $request->lang == 'en' ? 'Search history ' : 'Historial de búsqueda encontrado';

                $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $data];

              } else {



                $lang_mess = $request->lang == 'en' ? 'No Search history' : 'Sin historial de búsqueda';

                $response = ['status' => 'success', 'message' => $lang_mess, 'data' => []];

              }
            } else {

              $lang_mess = $request->lang == 'en' ? 'No Data Found' : 'Datos no encontrados';

              $response = ['status' => 'failure', 'message' => $lang_mess, 'data' => []];

            }
            return response()->json($response);
          }

/**
 * Complete Ride Status.
 */
          public function complete_ride(Request $request)
          {
            $ride_id = $request->ride_id;
            $driver_id = $request->driver_id;

            $Arr = array('id' => $ride_id, 'userid' => $driver_id);
            $updateArr = array('complete_status' => 1);

            $res = Ride::where($Arr)->update($updateArr);
            if ($res) {

              $notinfo = User::select('name as fname', 'lname')->where('id', $request->driver_id)->first();

              $datag = Apply_ride::select('*')->where('ride_id', $ride_id)->get()->toArray();

              foreach ($datag as $value) {
                $this->send_notification1($driver_id, $value['passenger_id'], 'Ride Successfully Completed', 'complete Ride');

                 $passengerInfo=User::find($value['passenger_id']);
                   $smsMessage = "Your SMOOTHRIDE ride is complete. We hope you had a great experience! - Team SMOOTHRIDE";

                        try {
                            $SmsService = new SmsService();
                            $templateId=1007069034521817674;
                            $response = $SmsService->sendOtp($passengerInfo->mobile, $smsMessage,$templateId);

                            if (!$response || ($response['status'] ?? '') != 'success') {
                                \Log::warning("SMS failed for passenger_id {$passengerInfo->id}", [
                                    'mobile' => $passengerInfo->mobile,
                                    'messag: mixed: mixede' => $smsMessage,
                                    'response' => $response
                                ]);
                            }
                        } catch (\Exception $e) {
                            \Log::error("SMS exception for passenger_id {$passengerInfo->id}: " . $e->getMessage());
                        }
              }
              $noti_arr = array('user_id' => $request->driver_id, 'messages' => 'Completed Ride','type'=>'completeRide');
              Notifications::create($noti_arr);


              $lang_mess = $request->lang == 'en' ? 'Your ride has been successfully completed' : 'Su viaje completado con éxito';

              $response = ['status' => 'success', 'message' => $lang_mess];

             


            } else {


              $lang_mess = $request->lang == 'en' ? 'Something else wrong please try again' : 'Algo más está mal, inténtalo de nuevo.';

              $response = ['status' => 'failure', 'message' => $lang_mess];

            }

            return response()->json($response);

          }

/**
 * The User can Send Rating Reviews.
 */
        public function send_rating_reviews(Request $request)
        {

                $reviews = array(
                  'sender_id' => $request->sender_id,
                  'receiver_id' => $request->receiver_id,
                  'rating' => $request->rating,
                  'reviews' => $request->reviews,
                  'ride_id' => $request->ride_id,
                  'created_at' => date('Y-m-d H:i:s')
                );

                if (!empty($request->sender_id) && !empty($request->receiver_id)) {
                  $res = Rating_reviews::create($reviews);
                  if ($res) {
                    $this->send_notification1($request->sender_id, $request->receiver_id, 'You received review from', $type1 = 'rating', $data1 = '');
                    $noti_arr = array('user_id' => $request->sender_id, 'messages' => 'Rating Reviews Add','type'=>'review');
                    Notifications::create($noti_arr);
                    $lang_mess = $request->lang == 'en' ? 'Thank you for reviewing the ride' : 'Reseñas de calificación añadidas con éxito';
                    $response = ['status' => 'success', 'message' => $lang_mess];
                  } else {
                    $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';
                    $response = ['status' => 'failure', 'message' => $lang_mess];
                  }

                } else {

                  $lang_mess = $request->lang == 'en' ? 'Sender or receiver must' : 'emisor o receptor debe';

                  $response = ['status' => 'failure', 'message' => $lang_mess];

                }
          return response()->json($response);
        }

/**
 * Rating Reviews List.
 */
        public function rating_reviews_list(Request $request)
        {

              if ($request->type == 'sender') {
                $res = Rating_reviews::join('users', 'users.id', '=', 'rating_reviews.receiver_id')
                  ->select('rating_reviews.rating', 'rating_reviews.reviews', 'name', 'lname', 'image', 'rating_reviews.created_at')
                  ->where('sender_id', $request->uid)
                  ->get()->toArray();
                // foreach($res as $key=>$val)
                // {         
                // $vg= Rating_reviews::where('sender_id',$val['sender_id'])->avg('rating');
                // $res[$key]['avg']=round($vg,0);
                // }

                if ($res) {
                  $lang_mess = $request->lang == 'en' ? 'Fetch' : 'Ha podido recuperar';
                  $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $res];
                } else {
                  $lang_mess = $request->lang == 'en' ? 'No Reviews Found' : 'No se encontraron calificaciones ni reseñas';
                  $response = ['status' => 'success', 'message' => $lang_mess, 'data' => []];
                }

              } elseif ($request->type == 'receiver') {
                $res = Rating_reviews::join('users', 'users.id', '=', 'rating_reviews.sender_id')
                  ->select('rating_reviews.rating', 'rating_reviews.reviews', 'name', 'lname', 'image', 'rating_reviews.created_at')
                  ->where('receiver_id', $request->uid)
                  ->get()->toArray();
                if ($res) {
                  $lang_mess = $request->lang == 'en' ? 'Fetch data' : 'Ha podido recuperar';
                  $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $res];
                } else {
                  $lang_mess = $request->lang == 'en' ? 'No Reviews Found' : 'No se encontraron calificaciones ni reseñas';
                  $response = ['status' => 'success', 'message' => $lang_mess, 'data' => []];
                }
              }

          return response()->json($response);

        }
/**
 * Send Notification.
 */

      public function send_notification(Request $request, $userid = null, $receiver_id = null, $desc = null)
      {

              $userid = $request->userid;
              $receiver_id = $request->receiver_id;
              $desc = $request->desc;
              $type = 'Chat';
              $senderdata = User::select('id', 'name', 'lname', 'image')->where('id', $userid)->first();
              $receiver_iddata = User::select('name', 'lname', 'image')->where('id', $receiver_id)->first();

              $checkArr = User::select('ride_notification', 'messages_notification', 'news_deals_stuff_notification')->where('id', $receiver_id)->first();



              $noti_arr = array('user_id' => 1, 'messages' => 'New user registered', 'type'=>'newuser');
              Notifications::create($noti_arr);

              $adminId=1;
              $checkforuserexist = User::select('*')->where('id', $userid)->first();

              // $url=url('user_info/'.$userid);
            

              //   $firebaseResponse = Http::post('https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/Notification/'.$adminId.'.json', [
              //       'user_id' => $userid,
              //       'name' => $checkforuserexist->name,
              //       'message' => 'New User Registered',
              //       'admin_id' => $adminId,
              //       'seen' => false,
              //       'url' => $url,
              //       'created_at' => date('d-m-y h:i:a'),
                
              //   ]);

              if ($checkArr->ride_notification == 0) {
                return false;
              }

              if ($checkArr->messages_notification == 0) {
                return false;
              }

              if ($checkArr->news_deals_stuff_notification == 0) {
                return false;
              }
              $title = $senderdata->name . ' ' . $senderdata->lname;
              
              $noti_arr = array('user_id' => $receiver_id, 'messages' => 'New mssage received from' .$title ,'type'=>'newmsg');
              Notifications::create($noti_arr);



              // firebase
      
                // End Firebase
                $arrayNames = array('user_id' => $receiver_id, 'title' => $title, 'description' => $desc);
                $getting_token_info = Token::select('*')->where('user_id', $receiver_id)->get()->toArray();

                $sender=(string) $senderdata->id;
                if (!empty($getting_token_info) && count($getting_token_info) > 0) {
                  foreach ($getting_token_info as $key => $valuedata) {
                    $deviceToken = $valuedata['token'];
                    $data = (object) array('type' => 'chat', 'chatId' => $sender, 'image' => $senderdata->image, 'name' => $title);

                    $response = $this->sendFCMViaJson(
                      $deviceToken,
                      $title,
                      $desc,
                      $data
                  );
                  }

                }
      
      }

/**
 * Send Notification.
 */
      public function send_notification1($userid = null, $receiver_id = null, $desc = null, $type1 = null, $data1 = null)
      {


        $senderdata = User::select('name', 'lname')->where('id', $userid)->first();
        $receiver_iddata = User::select('name', 'lname')->where('id', $receiver_id)->first();

        $checkArr = User::select('ride_notification', 'messages_notification', 'news_deals_stuff_notification')->where('id', $receiver_id)->first();

        if ($checkArr->ride_notification == 0) {
          return false;
        }

        if ($checkArr->messages_notification == 0) {
          return false;
        }

        if ($checkArr->news_deals_stuff_notification == 0) {
          return false;
        }

        $title = 'Ride';
        // $title=$senderdata->name .' '. $senderdata->lname;
        if ($type1 == 'rating') {
          $desc = $desc . ' ' . $senderdata->name . ' ' . $senderdata->lname . ' ';
        } else {

          $desc = $senderdata->name . ' ' . $senderdata->lname . ' ' . $desc;
        }

        if ($type1 == 'New ride') {
          $desc = $desc;
        }

        $arrayNames = array('user_id' => $receiver_id, 'title' => $title, 'description' => $desc);
        $getting_token_info = Token::select('*')->where('user_id', $receiver_id)->get()->toArray();
        $response=[];

        if (!empty($getting_token_info) && count($getting_token_info) > 0) {

        
                foreach ($getting_token_info as $key => $valuedata) {
                    $deviceToken = $valuedata['token'];
                    $data = (object) array('type' => $type1, 'ridedata' => $data1);
                    $response = $this->sendFCMViaJson(
                      $deviceToken,
                      $title,
                      $desc,
                      $data
                  );
                  }
            
        }

        return $response;


        // if (!empty($getting_token_info) && count($getting_token_info) > 0) {
        //   $API_SERVER_KEY = "AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
        //   foreach ($getting_token_info as $key => $valuedata) {
        //     $registrationIds = $valuedata['token'];
        //     $data = (object) array('type' => $type1, 'ridedata' => $data1);
        //     $msg = array
        //     (
        //       'body' => $desc,
        //       'title' => $title,
        //       'icon' => 'myicon',/*Default Icon*/
        //       'sound' => 'mySound'/*Default sound*/
        //     );
        //     $fields = array
        //     (
        //       'to' => $registrationIds,
        //       'notification' => $msg,
        //       'data' => $data

        //     );
        //     $headers = array
        //     (
        //       'Authorization: key=' . $API_SERVER_KEY,
        //       'Content-Type: application/json'
        //     );

        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        //     curl_setopt($ch, CURLOPT_POST, true);
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        //     $result[] = curl_exec($ch);
        //     curl_close($ch);
        //   }
        //   if (!empty($result)) {
        //     $arrayName = true;
        //   } else {
        //     $arrayName = false;
        //   }
        // } else {
        //   $arrayName = false;
        // }
        // return  $response=['status'=>'success','message'=>'test ','rss'=>$arrayName]; 
        // return $arrayName;


      }
/**
 * Delete History.
 */
      public function delete_history(Request $request)
      {

        if (!empty($request->id) && !empty($request->userid)) {
          $delArr = array('id' => $request->id, 'user_id' => $request->userid);
          $res = RecentHistory::where($delArr)->delete();
          if ($res) {
            $lang_mess = $request->lang == 'en' ? 'Deleted Successfully' : 'Eliminar con éxito';
            $response = ['status' => 'success', 'message' => $lang_mess];
          } else {
            $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';
            $response = ['status' => 'failure', 'message' => $lang_mess];
          }
        } else {
          $response = ['status' => 'failure', 'message' => 'id must '];
        }
        return response()->json($response);
      }
/**
 * FAQ.
 */
      public function Faq(Request $request)
      {
        $res = Faq::select('questions', 'answers', 'id')->orderBy('id', 'desc')->get()->toArray();
        if ($res) {
          $lang_mess = $request->lang == 'en' ? 'Data Fetched ' : 'Obtención de datos';
          $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $res];
        } else {
          $lang_mess = $request->lang == 'en' ? 'Data Not Fetched ' : 'Datos no obtenidos';

          $response = ['status' => 'failure', 'message' => $lang_mess, 'data' => []];
        }
        return response()->json($response);
      }

/**
 * The User can Logout.
 */
      public function logout(Request $request)
      {

            $validator = Validator::make($request->all(), [
              'userid' => 'required',
              'token' => 'required',
              'lang' => 'required',
            
                ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->first(),
                ], 422); 
            }
            $lang_mess = $request->lang == 'en' ? 'Loged out Successfully ' : 'Cerrar sesión con éxito';

            $token = $request->token;
            // if($token==undefined)
            if (!empty($request->token) && !empty($request->userid)) {
              $where = array('token' => $token, 'user_id' => $request->userid);
              $res = Token::where($where)->delete();
              if ($res) {


                $response = ['status' => 'success', 'message' => $lang_mess];
              } else {
                $response = ['status' => 'success', 'message' => $lang_mess];
              }
            } else {
              $response = ['status' => 'success', 'message' => $lang_mess];
            }

            return response()->json($response);
      }
      
/**
 * The User can Verify Status.
 */
       public function verify_status(Request $request)
        {
          $now = date('Y-m-d');
          $today = strtotime($now);
          if (!empty($request->userid)) {
            $Verify_idata = [];
            $Verify_idata = Verify_id::where('user_id', $request->userid)->first();
            $approved = User::select('is_verifyId')->where('id', $request->userid)->first();
            if ($Verify_idata && !empty($Verify_idata)) {
                $Verify_idata = array(
                'id_proof' => $Verify_idata['id_proof'] == null ? '' : $Verify_idata['id_proof'],
                'driving_licence' => $Verify_idata['driving_licence'] == null ? '' : $Verify_idata['driving_licence'],
                'vehicle_plate' => $Verify_idata['vehicle_plate'] == null ? '' : $Verify_idata['vehicle_plate'],
                'insurance' => $Verify_idata['insurance'] == null ? '' : $Verify_idata['insurance'],

                'vehicle_rc' => $Verify_idata['vehicle_rc'] == null ? '' : $Verify_idata['vehicle_rc'],
                'fitness_certificate' => $Verify_idata['fitness_certificate'] == null ? '' : $Verify_idata['fitness_certificate'],
                'tax_receipt' => $Verify_idata['tax_receipt'] == null ? '' : $Verify_idata['tax_receipt'],
                'registration_slip' => $Verify_idata['registration_slip'] == null ? '' : $Verify_idata['registration_slip'],
                'tourist_permit' => $Verify_idata['tourist_permit'] == null ? '' : $Verify_idata['tourist_permit'],
                'driving_licence_tr' => $Verify_idata['driving_licence_tr'] == null ? '' : $Verify_idata['driving_licence_tr'],
                'puc' => $Verify_idata['puc'] == null ? '' : $Verify_idata['puc'],
              
                'id_proff_status' => $Verify_idata['id_proff_status'],
                'driving_licence_status' => $Verify_idata['driving_licence_status'],
                'vehicle_plate_status' => $Verify_idata['vehicle_plate_status'],
                'insurance_status' => $Verify_idata['insurance_status'],

                'vehicle_rc_status' => $Verify_idata['vehicle_rc_status'],
                'fitness_certificate_status' => $Verify_idata['fitness_certificate_status'],
                'tourist_permit_status' => $Verify_idata['tourist_permit_status'],
                'driving_licence_tr_status' => $Verify_idata['driving_licence_tr_status'],
                'puc_status' => $Verify_idata['puc_status'],

                'image' => $Verify_idata['image'] == null ? '' : $Verify_idata['image'],
                'type' => $Verify_idata['type'] == null ? '' : $Verify_idata['type'],
                'otp' => $Verify_idata['otp'] == null ? '' : $Verify_idata['otp'],

                'id_proof_expdate' => $Verify_idata['id_proof_expdate'] == null ? '' : $Verify_idata['id_proof_expdate'],
                'driving_licence_expdate' => $Verify_idata['driving_licence_expdate'] == null ? '' : $Verify_idata['driving_licence_expdate'],
                'vehicle_plate_expdate' => "",

                //  'vehicle_plate_expdate'=>$Verify_idata['vehicle_plate_expdate']==null ? '' :  $Verify_idata['vehicle_plate_expdate'],
                      'insurance_expdate' => $Verify_idata['insurance_expdate'] == null ? '' : $Verify_idata['insurance_expdate'],

                      'vehicle_rc_expdate' => $Verify_idata['vehicle_rc_expdate'] == null ? '' : $Verify_idata['vehicle_rc_expdate'],
                      'fitness_certificate_expdate' => $Verify_idata['fitness_certificate_expdate'] == null ? '' : $Verify_idata['fitness_certificate_expdate'],
                      'tourist_permit_expdate' => $Verify_idata['tourist_permit_expdate'] == null ? '' : $Verify_idata['tourist_permit_expdate'],
                      'driving_licence_tr_expdate' => $Verify_idata['driving_licence_tr_expdate'] == null ? '' : $Verify_idata['driving_licence_tr_expdate'],
                      'puc_expdate' => $Verify_idata['puc_expdate'] == null ? '' : $Verify_idata['puc_expdate'],

                      'id_proof_renewdate' => $Verify_idata['id_proof_renewdate'] == null ? '' : $Verify_idata['id_proof_renewdate'],
                      'driving_licence_renewdate' => $Verify_idata['driving_licence_renewdate'] == null ? '' : $Verify_idata['driving_licence_renewdate'],
                      'vehicle_plate_renewdate' => $Verify_idata['vehicle_plate_renewdate'] == null ? '' : $Verify_idata['vehicle_plate_renewdate'],
                      'insurance_renewdate' => $Verify_idata['insurance_renewdate'] == null ? '' : $Verify_idata['insurance_renewdate']
                    );
                    $lang_mess = $request->lang == 'en' ? 'fetch ' : 'ha podido recuperar';
                    $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $Verify_idata, 'is_verifyId' => $approved->is_verifyId];
                  } else {
                    $lang_mess = $request->lang == 'en' ? 'No result found ' : 'No se han encontrado resultados';
                    $response = ['status' => 'success', 'message' => $lang_mess, 'data' => [], 'is_verifyId' => '0'];
                  }
                } else {
                  $lang_mess = $request->lang == 'en' ? 'No result found ' : 'No se han encontrado resultados';
                  $response = ['status' => 'success', 'message' => $lang_mess, 'data' => [], 'is_verifyId' => '0'];
                }
                return response()->json($response);
      }
/**
 * Get Distance.
 */
        public function get_distance(Request $request)
        {
          $pickup_time = $request->pickup_time;
          $pickup_date = $request->pickup_date;
          $milesss = $this->distance($request->lat, $request->lon, $request->latitude, $request->longitude);
          $thredate = date('H:i', strtotime($pickup_date . ' ' . $pickup_time));
          $timess = strtotime($thredate . '+' . $milesss['time']);
          $newfff = date('h:i A', $timess);
          $milesss['estimate_hours'] = $newfff;
          if ($milesss) {
            $lang_mess = $request->lang == 'en' ? ' result found ' : 'resultado encontrado';
            $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $milesss];
          } else {
            $lang_mess = $request->lang == 'en' ? 'No result found ' : 'No se han encontrado resultados';
            $response = ['status' => 'success', 'message' => $lang_mess, 'data' => []];
          }
          return response()->json($response);
        }
/**
 * Push Notification.
 */
        public function push_notification(Request $request)
        {
          $ride_notification = $request->ride_notification;
          $messages_notification = $request->messages_notification;
          $news_deals_stuff_notification = $request->news_deals_stuff_notification;
          $userid = $request->userid;
          $pushArr = array(
            'ride_notification' => $ride_notification,
            'messages_notification' => $messages_notification,
            'news_deals_stuff_notification' => $news_deals_stuff_notification
          );
          $res = User::where('id', $userid)->update($pushArr);
          if ($res) {
            $noti_arr = array('user_id' => $request->userid, 'messages' => 'Update Push Notification Status','type'=>'pushNotification');
            Notifications::create($noti_arr);
            $lang_mess = $request->lang == 'en' ? 'Push Notification Updated Successfully' : 'Notificación push actualizada con éxito';
            $response = ['status' => 'success', 'message' => $lang_mess];
          } else {
            $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo!';
            $response = ['status' => 'failure', 'message' => $lang_mess];
          }
          return response()->json($response);
        }
        /**
 * Delete User.
 */
        public function delete_user(Request $request)
        {
          $id = $request->userid;
          if (!empty($id)) {
            // $statusArr=array('status'=>1,'account_status'=>1); 
            $res = User::where('id', $id)->delete();
            if ($res) {
              $lang_mess = $request->lang == 'en' ? 'Account Deleted Successfully' : 'Eliminación de cuenta con éxito';
              $response = ['status' => 'success', 'message' => $lang_mess];
            } else {
              $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';
              $response = ['status' => 'failure', 'message' => $lang_mess];
            }
          } else {
            $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';
            $response = ['status' => 'failure', 'message' => $lang_mess];
          }
          return response()->json($response);
        }
/**
 * Notification Reminder.
 */
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
/**
 * Alert Notification To the User.
 */
          public function alert_notification(Request $request)
          {
            if (!empty($request->userid)) {
              $alert = array(
                'user_id' => $request->userid,
                'pickup_location' => $request->pickup_location,
                'drop_location' => $request->drop_location,
                'pickup_lat' => $request->pickup_lat,
                'pickup_long' => $request->pickup_long,
                'drop_lat' => $request->drop_lat,
                'drop_long' => $request->drop_long,
                'date' => $request->date
              );
              $count = Alert_notification::where($alert)->count();
              if ($count > 0) {
                if ($request->type == 'enable') {
                  $lang_mess = $request->lang == 'en' ? 'Notifications are already enabled for this search.' : 'Ya has creado una alerta para este viaje y fecha';
                  $response = ['status' => 'success', 'message' => $lang_mess];
                } else {
                  Alert_notification::where($alert)->delete();
                  $lang_mess = $request->lang == 'en' ? 'Notifications for ride match disabled ' : 'Eliminado con éxito';
                  $response = ['status' => 'success', 'message' => $lang_mess];
                }
              } else {
                $res = Alert_notification::create($alert);
                if ($res) {
                  $lang_mess = $request->lang == 'en' ? 'You will be notified when a ride matches your search.' : 'Alerta creada con éxito';
                  $response = ['status' => 'success', 'message' => $lang_mess];

                } else {
                  $lang_mess = $request->lang == 'en' ? 'Something went wrong please try again' : 'Algo más está mal, inténtalo de nuevo.';
                  $response = ['status' => 'failure', 'message' => $lang_mess];
                }
              }
            } else {
              $lang_mess = $request->lang == 'en' ? 'Something went wrong please try again' : 'Algo más está mal, inténtalo de nuevo.';
              $response = ['status' => 'failure', 'message' => $lang_mess];
            }
            return response()->json($response);
          }
/**
 * The Duplicate Ride.
 */
          public function duplicate_ride(Request $request)
          {
            $data = Ride::select('*')->where('id', $request->ride_id)->first();
            $stop = Stop::select('*')->where('addride_id', $request->ride_id)->get()->toArray();

            $newArr = array(
              'userid' => $data->userid,
              'vehicle_id' => $data->vehicle_id,
              'rideid' => rand(10000, 50000),
              'pick_location' => $data->pick_location,
              'drop_location' => $data->drop_location,
              'drop_lat' => $data->drop_lat,
              'drop_long' => $data->drop_long,
              'date' => $request->date,
              'pick_lat' => $data->pick_lat,
              'pick_long' => $data->pick_long,
              'time' => $data->time,
              'passenger_count' => $data->passenger_count,
              'total_passenger' => $data->total_passenger,
              'stoppage' => $data->stoppage,
              'small_bag' => $data->small_bag,
              'hand_bag' => $data->hand_bag,
              'regular_bag' => $data->regular_bag,
              'oversize_bag' => $data->oversize_bag,
              'price' => $data->price,
              'instruction' => $data->instruction,
              'smoking_allowed ' => $data->smoking_allowed,
              'pets_allowed' => $data->pets_allowed,
              'backsheet' => $data->backsheet,
              'music' => $data->music,
              'instant_booking' => $data->instant_booking,
              'verified_profile' => $data->verified_profile,
              'ac_allow' => $data->ac_allow,
              'pending_small_bag' => $data->small_bag,
              'pending_hand_bag' => $data->hand_bag,
              'pending_regular_bag' => $data->regular_bag,
              'pending_oversize_bag' => $data->oversize_bag,
              'food_allow' => $data->food_allow
            );
            if (!empty($newArr)) {
              $res = Ride::create($newArr);
              if (count($stop) > 0) {
                foreach ($stop as $value) {
                  $newarr = array('date' => $request->date, 'addride_id' => $res->id, 'stop_location' => $value['stop_location'], 'stop_lat' => $value['stop_lat'], 'stop_long' => $value['stop_long'], 'created_at' => date('Y-m-d H:i:s'));
                  Stop::create($newarr);
                }
              }

              if ($res) {

                $lang_mess = $request->lang == 'en' ? 'Ride Created Successfully' : 'Paseo creado con éxito';

                $response = ['status' => 'success', 'message' => $lang_mess];

              } else {

                $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

                $response = ['status' => 'failure', 'message' => $lang_mess];

              }
            } else {
              $lang_mess = $request->lang == 'en' ? 'Something else wrong plase try again' : 'Algo más está mal, inténtalo de nuevo.';

              $response = ['status' => 'failure', 'message' => $lang_mess];
            }
            return response()->json($response);
          }
          /**
 * The Filter Program.
 */
          public function filter_program(Request $request)
          {
            $time = $request->time;
            $temtime = explode('-', $time);
            $t1 = $temtime[0];
            $t2 = $temtime[1];
            $dte = '2023-01-12';
            $curr_lat = $request->curr_lat;
            $currlong = $request->curr_long;


            // // serach ride data 
            $lat = $request->pickup_lat;
            $lon = $request->pickup_long;

            $latitude = $request->drop_lat;
            $longitude = $request->drop_long;
            // DB::enableQueryLog();

            $nearby = DB::table("add_rides");
            $nearby = $nearby->select(
              'add_rides.id as ride_id',
              'add_rides.userid as driver_id',
              'users.name',
              'users.lname',
              'users.mobile',
              'users.image',
              'add_rides.pick_location',
              'add_rides.drop_location',
              'add_rides.pick_lat',
              'add_rides.pick_long',
              'add_rides.drop_lat'
              ,
              'add_rides.drop_long',
              'add_rides.date',
              'add_rides.time',
              'add_rides.passenger_count',
              'add_rides.price',
              'users.is_verifyId',
              'users.avg_rating',
              'add_rides.instruction',
              'vehicles.plate_number',
              'vehicles.vehicle_brand',
              'vehicles.country',
              'vehicles.vehicle_model'
              ,
              'vehicles.vehicle_type',
              'vehicles.vechicle_color',
              'vehicles.vechicle_madeyear',
              'vehicles.vehicle_img',
              'add_rides.total_passenger as bookedsheet',
              'add_rides.stoppage',
              'add_rides.small_bag',
              'add_rides.hand_bag'
              ,
              'add_rides.regular_bag',
              'add_rides.smoking_allowed',
              'add_rides.pets_allowed',
              'add_rides.verified_profile'
              ,
              'add_rides.instant_booking',
              'add_rides.food_allow',
              'add_rides.ac_allow',
              'add_rides.oversize_bag'
              ,
              DB::raw("6371 * acos(cos(radians(" . $lat . "))* cos(radians(pick_lat)) * cos(radians(pick_long) - radians(" . $lon . "))
                + sin(radians(" . $lat . "))
                * sin(radians(pick_lat))) AS distance")
            );
            $nearby = $nearby->join('users', 'users.id', '=', 'add_rides.userid');
            $nearby = $nearby->join('vehicles', 'vehicles.id', '=', 'add_rides.vehicle_id');
            $nearby = $nearby->having('distance', '<=', 20);
            $nearby = $nearby->whereDate('add_rides.date', '=', $dte);
            $nearby = $nearby->where('add_rides.complete_status', 0);
            $nearby = $nearby->where('add_rides.delete_status', 'active');
            $nearby = $nearby->orderBy('distance', 'asc');
            $nearby = $nearby->get()->toArray();
            $nearby = json_decode(json_encode($nearby), true);
            $t1 = date('Y-m-d H:i A', strtotime($dte . ' ' . $t1));
            $t2 = date('Y-m-d H:i A', strtotime($dte . ' ' . $t2));
            $newarrarry = [];
            foreach ($nearby as $key => $value) {
              $desto = $this->distance($latitude, $longitude, $value['drop_lat'], $value['drop_long']);
              $newArr = [];
              if ($desto['distance'] <= 20) {

                $dbtime = date('Y-m-d H:i A', strtotime($value['date'] . ' ' . $value['time']));
                if ($t1 >= $dbtime && $t2 <= $dbtime) {
                  $newarr = array(
                    'ride_id' => $value['ride_id'],
                    'mobile' => $value['mobile'],
                    'driver_id' => $value['driver_id'],
                    'name' => $value['name'],
                    'lname' => $value['lname'],
                    'pick_location' => $value['pick_location'],
                    'drop_location' => $value['drop_location'],
                    'pick_lat' => $value['pick_lat'],
                    'pick_long' => $value['pick_long'],
                    'drop_long' => $value['drop_long'],
                    'date' => $value['date'],
                    'time' => $value['time'],
                    'passenger_count' => $value['passenger_count'],
                    'price' => $value['price'],
                    'is_verifyId' => $value['is_verifyId'],
                    'instruction' => $value['instruction'],
                    'plate_number' => $value['plate_number'],
                    'vehicle_brand' => $value['vehicle_brand'],
                    'country' => $value['country'],
                    'vehicle_model' => $value['vehicle_model'],
                    'vehicle_type' => $value['vehicle_type'],
                    'vechicle_color' => $value['vechicle_color'],
                    'vechicle_madeyear' => $value['vechicle_madeyear'],
                    'vehicle_img' => $value['vehicle_img'],
                    'bookedsheet' => $value['bookedsheet'],
                    'stoppage' => $value['stoppage'],
                    'small_bag' => $value['small_bag'],
                    'hand_bag' => $value['hand_bag'],
                    'regular_bag' => $value['regular_bag'],
                    'smoking_allowed' => $value['smoking_allowed'],
                    'pets_allowed' => $value['pets_allowed'],
                    'verified_profile' => $value['verified_profile'],
                    'instant_booking' => $value['instant_booking'],
                    'food_allow' => $value['food_allow'],
                    'ac_allow' => $value['ac_allow'],
                    'oversize_bag' => $value['oversize_bag'],
                    'image' => $value['image']
                  );
                  $milesss = $this->distance($lat, $lon, $latitude, $longitude);
                  $newarr['distance'] = (string) $milesss['distance'] . 'KM';
                  $newarr['passenger_count'] = (int) $value['passenger_count'];
                  $newarr['hours'] = $milesss['time'];
                  if (!empty($lat) && !empty($lon)) {
                    $milesss1 = $this->distance($lat, $lon, $latitude, $longitude);
                    $newarr['estimate_distance'] = (string) $milesss1['distance'] . 'KM';
                    $newarr['estimate_hours'] = $milesss1['time'];
                    $thredate = date('H:i', strtotime($value['date'] . ' ' . $value['time']));
                    $timess = strtotime($thredate . '+' . $milesss1['time']);
                    $newfff = date('h:i A', $timess);
                    $newarr['estimate_hours'] = $newfff;
                  } else {
                    $newarr['estimate_distance'] = '';
                    $newarr['estimate_hours'] = '';
                    $newarr['estimate_hours'] = '';
                  }
                  $newarrarry[] = $newarr;
                }
              }
            }
            return response()->json($newarrarry);
          }
/**
 * The User Get a Single Ride.
 */
        public function get_single_ridedata($id, $userid)
        {
          $data = Ride::join('users', 'users.id', '=', 'add_rides.userid')
            ->join('verify_id', 'verify_id.user_id', '=', 'users.id', 'left')
            ->join('vehicles', 'vehicles.id', '=', 'add_rides.vehicle_id')

            // ->join('rating_reviews','rating_reviews.sender_id','=','add_rides.userid')
            ->select(
              'users.profile_status',
              'users.is_verifyId',
              'users.id as driver_id',
              'add_rides.created_at',
              'add_rides.id',
              'users.name',
              'users.lname',
              'users.mobile',
              'users.image',
              'add_rides.pick_location',
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
              'add_rides.instant_booking',
              'add_rides.small_bag',
              'add_rides.hand_bag',
              'add_rides.regular_bag',
              'add_rides.oversize_bag',
              'add_rides.pending_small_bag',
              'add_rides.pending_hand_bag',
              'add_rides.pending_regular_bag',
              'add_rides.pending_oversize_bag'
            )
            ->where('add_rides.id', $id)
            ->where('add_rides.delete_status', '=', 'active')
            ->orderBy('add_rides.date', 'DESC')
            ->get()
            ->toArray();

          if ($data) {

            // $count=0;
            foreach ($data as $key => $value) {

              $count = Apply_ride::where('ride_id', $value['id'])->where('instant_status', 1)->count();
              if ($count > 0) {
                $data[$key]['booking_count'] = 1;
              } else {

                $data[$key]['booking_count'] = 0;
              }

              $milesss = $this->distance($value['pick_lat'], $value['pick_long'], $value['drop_lat'], $value['drop_long']);
              $data[$key]['distance'] = (string) $milesss['distance'] . 'KM';
              $data[$key]['hours'] = $milesss['time'];
              // $milesss1=$this->distance($curr_lat,$currlong,$latitude,$longitude);
              //            $newData[$key]['estimate_distance']=(string)$milesss1['distance'].'KM'; 
              //            $newData[$key]['estimate_hours']=$milesss1['time']; 
              $thredate = date('H:i', strtotime($value['date'] . ' ' . $value['time']));
              $timess = strtotime($thredate . '+' . $milesss['time']);
              $newfff = date('h:i A', $timess);
              $data[$key]['estimate_hours'] = $newfff;

              if (empty($value['stoppage']) || $value['stoppage'] == null) {
                $data[$key]['stoppage'] = [];

              }
              if ($value['is_verifyId'] == null || $value['is_verifyId'] == '') {
                $data[$key]['is_verifyId'] = 0;
              }
              $data[$key]['my_rating_review_status'] = Rating_reviews::where('sender_id', $userid)->where('ride_id', $value['id'])->count();

              $booked_users = Apply_ride::select(
                'apply_ride.ride_id',
                'apply_ride.small_bag',
                'apply_ride.hand_bag',
                'apply_ride.regular_bag',
                'apply_ride.oversize_bag',
                'apply_ride.id',
                'apply_ride.passenger_id',
                'users.name',
                'users.lname',
                'users.mobile',
                'users.gender',
                'users.bio',
                'users.dob',
                'users.email',
                'users.image',
                'apply_ride.passenger_count as usersheetcount',
                'apply_ride.instant_status',
                'apply_ride.confirm_book'
              )
                ->join('users', 'users.id', '=', 'apply_ride.passenger_id')
                // ->join('rating_reviews','rating_reviews.sender_id','=','apply_ride.passenger_id')
                ->where('apply_ride.cancel_booking', '=', 'true')
                ->where('apply_ride.ride_id', $value['id'])->get()->toArray();

              foreach ($booked_users as $eky => $value123) {

                $dfgg = Rating_reviews::select('rating', 'reviews')->where('ride_id', $value123['ride_id'])->where('receiver_id', $value123['passenger_id'])->first();
                $ratingavg = Rating_reviews::where('receiver_id', $value123['passenger_id'])->avg('rating');

                $booked_users[$eky]['rating_avg'] = round(@$ratingavg);

                if (!empty($dfgg)) {
                  $booked_users[$eky]['rating'] = @$dfgg->rating;
                  $booked_users[$eky]['reviews'] = @$dfgg->reviews;
                } else {
                  $booked_users[$eky]['reviews'] = false;
                  $booked_users[$eky]['rating'] = false;
                }
              }
              $data[$key]['booked_users'] = $booked_users;
            }
          }
          return $data;
        }
/**
 * The User can send Message.
 */
          public function send_meessage_email(Request $request)
          {

            $adminId=$request->adminId;
            $userid=$request->userid;
          
            $checkforuserexist = User::select('*')->where('id', $userid)->first();

            $url=url('chat/'.$userid);
          

              $firebaseResponse = Http::post('https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/Notification/'.$adminId.'.json', [
                  'user_id' => $userid,
                  'name' => $checkforuserexist->name,
                  'message' => 'New message received from '.$checkforuserexist->name,
                  'admin_id' => $adminId,
                  'seen' => false,
                  'url' => $url,
                  'created_at' => date('d-m-y h:i:a'),
              
              ]);
            // $uid = $request->userid;
            // $checkforuserexist = User::select('*')->where('id', $uid)->first();

            // $info = array(
            //   'name' => @$checkforuserexist->name . ' ' . @$checkforuserexist->lname,
            //   'message' => $request->message,
            //   'id' => $checkforuserexist->id
            // );
            // $rmm = 'denisadolnakova@gmail.com';
            // $rmm1 = 'michal.obeda@gmail.com';
            // Mail::send('pdf_temp.message_tem', compact('info'), function ($message) use ($rmm) {
            //   $message->to($rmm)
            //     ->subject('SmoothRide Users Messages');
              
            // });
            // Mail::send('pdf_temp.message_tem', compact('info'), function ($message) use ($rmm1) {
            //   $message->to($rmm1)
            //     ->subject('SmoothRide Users Messages');
            
            // });

            $response = ['status' => 'success'];
            return response()->json($response);

          }
/**
 * The Cron Jobs.
 */
          public function cron_jobs(Request $request)
          {

            // $schedule->command('apicontroller:cron_jobs')->cron('*/5 * * * *');
            // $this->auto_complete_ride();
            // echo "cron jobs";  
            // $this->send_notification1(82,86,'new text','d');\
            $update = rand(2123, 23323);

            $update = ['name' => $update];
            DB::table('users')->where('id', 86)->update($update);

            echo 'ok';
          }

          public function democron()
          {

            $this->auto_complete_ride();

          }
          /**
 * The User can send Documents.
 */
          public function send_email_documents_verifyed($id)
          {
            $admin_ifo = User::select('*')->where('user_type', 'admin')->get()->toArray();
            $checkforuserexist = User::select('*')->where('id', $id)->first();

            $uname = $checkforuserexist->name . ' ' . $checkforuserexist->lname;
            foreach ($admin_ifo as $value) {
              $aname = $value['name'] . ' ' . $value['lname'];
              $info['user'] = array(
                'name' => $uname,
                'adminame' => $aname,
                'id' => @$id
              );
              $rt = $value['email'];
              Mail::send('doc_temp.document_email', $info, function ($message) use ($rt) {
                $message->to($rt)
                  ->subject('Document verification');
                // $message->from('Caco@gmail.com', 'Caco@gmail.com');
              });
              $this->send_notification_to_admin($value['id'], $checkforuserexist->id);
            }
          }
/**
 * Sends Notification to the Admin.
 */
          public function send_notification_to_admin($adminid, $userid)
          {

            $senderdata = User::select('name', 'lname')->where('id', $userid)->first();
            $receiver_iddata = User::select('name', 'lname')->where('id', $adminid)->first();
            $title = 'Documents verification';
            $type1 = 'Verifyid';

            $desc = 'Hi ' . @$receiver_iddata->name . ' ' . @$receiver_iddata->lname . ' ,' . @$senderdata->name . ' ' . $senderdata->lname . ' ' . 'uploaded document check admin and approve pending user documents';

            // $desc="";
            // $gettokn_Arr=array('user_id'=>$request->userid);
            // $arrayNames='';
            $arrayNames = array('user_id' => $userid, 'title' => $title, 'description' => $desc);
            // $result2=$this->init2()->insert('notifications_history',$arrayNames);
            // $userData=$this->init2()->device_token_fetch($userid,$utype);
            $getting_token_info = Token::select('*')->where('user_id', $adminid)->get()->toArray();

            if (!empty($getting_token_info) && count($getting_token_info) > 0) {
              $API_SERVER_KEY = "AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
              foreach ($getting_token_info as $key => $valuedata) {
                $registrationIds = @$valuedata['token'];
                $data = (object) array('type' => $type1, 'ridedata' => '');
                $msg = array
                (
                  'body' => $desc,
                  'title' => $title,
                  'icon' => 'myicon',/*Default Icon*/
                  'sound' => 'mySound'/*Default sound*/
                );
                $fields = array
                (
                  'to' => $registrationIds,
                  'notification' => $msg,
                  'data' => $data

                );
                $headers = array
                (
                  'Authorization: key=' . $API_SERVER_KEY,
                  'Content-Type: application/json'
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result[] = curl_exec($ch);
                curl_close($ch);
              }
              if (!empty($result)) {
                $arrayName = true;
              } else {
                $arrayName = false;
              }
            } else {
              $arrayName = false;
            }
            return $arrayName;


          }
/**
 * The User can Check the ExpiryDate.
 */
        public function check_expiredate()
        {
          $today = date('Y-m-d');
          $today1 = strtotime($today);
          $Arr = Verify_id::select('*')->get()->toArray();

          $a1 = '';
          $a2 = '';
          $a3 = '';
          $a4 = '';
          foreach ($Arr as $val) {

            $a1 = strtotime(@$val['id_proof_expdate']);
            // $a2=strtotime(@$val['vehicle_plate_expdate']);
            $a3 = strtotime(@$val['driving_licence_expdate']);
            $a4 = strtotime(@$val['insurance_expdate']);

            // if($a2 && $today1 > $a2)
            // {

            //   $this->expire_notification($val['user_id'],'Vehicle plate','vehicle_plate_status');
            // }


            if ($a3 && $today1 > $a3) {
              $this->expire_notification($val['user_id'], 'Driving licence', 'driving_licence_status');
            }
            if ($a4 && $today1 > $a4) {
              $this->expire_notification($val['user_id'], 'Insurance', 'insurance_status');
            }
            if ($a1 && $today1 > $a1) {
              $this->expire_notification($val['user_id'], 'Id proof', 'id_proff_status');
            }

          }
          return true;

        }
        /**
 * The User get the Notification of the Expiry Date.
 */
        public function expire_notification($receiver_id, $st, $status)
        {
          $receiver_iddata = User::select('name', 'lname')->where('id', $receiver_id)->first();
          $idArr = array('is_verifyId' => 1);
          User::where('id', $receiver_id)->update($idArr);
          $uparr = array($status => 1);
          Verify_id::where('user_id', $receiver_id)->update($uparr);

          $checkforuserexist = User::select('*')->where('id', $receiver_id)->first();

          $uname = @$checkforuserexist->name . ' ' . @$checkforuserexist->lname;

          $info['user'] = array(
            'name' => @$uname
          );
          $rt = @$checkforuserexist->email;

          if (!empty($rt)) {

            $message = '';
            Mail::send('doc_temp.document_expired', $info, function ($message) use ($rt) {
              $message->to($rt)
                ->subject('Document verification');
              // $message->from('Caco@gmail.com', 'Caco@gmail.com');
            });
          }

          $title = 'Hi ' . @$receiver_iddata->name . ' ' . @$receiver_iddata->lname;
          $desc = 'Your ' . $st . ' Document Has Been Expired Please reupload Documents';

          $arrayNames = array('user_id' => $receiver_id, 'title' => $title, 'description' => $desc);
          $getting_token_info = Token::select('*')->where('user_id', $receiver_id)->get()->toArray();

          if (!empty($getting_token_info) && count($getting_token_info) > 0) {
            $API_SERVER_KEY = "AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
            foreach ($getting_token_info as $key => $valuedata) {
              $registrationIds = $valuedata['token'];
              $data = (object) array('type' => 'Verifyid', 'ridedata' => '');
              $msg = array
              (
                'body' => $desc,
                'title' => $title,
                'icon' => 'myicon',/*Default Icon*/
                'sound' => 'mySound'/*Default sound*/
              );
              $fields = array
              (
                'to' => $registrationIds,
                'notification' => $msg,
                'data' => $data

              );
              $headers = array
              (
                'Authorization: key=' . $API_SERVER_KEY,
                'Content-Type: application/json'
              );

              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
              $result[] = curl_exec($ch);
              curl_close($ch);
            }
            if (!empty($result)) {
              $arrayName = true;
            } else {
              $arrayName = false;
            }
          } else {
            $arrayName = false;
          }
          // return  $response=['status'=>'success','message'=>'test ','rss'=>$arrayName]; 
          // return $arrayName;
          // echo $arrayName;


        }
        /**
 * The User can Push Ride.
 */
  public function push_ride(Request $request)
  {
    try {
      $Arr = array(
        'user_id' => $request->userid,
        'pickup_location' => $request->pickup_location,
        'drop_location' => $request->drop_location,
        'pick_latitude' => @$request->pick_latitude,
        'pick_longitude' => @$request->pick_longitude,
        'drop_lat' => @$request->drop_lat,
        'drop_long' => @$request->drop_long,
        'timestamp' => time(),
        'status' => 'push',
        'passeger_count' => @$request->passeger_count,
        'date' => date('Y-m-d', strtotime($request->date)),
        'created_at' => date('Y-m-d H:i:s')
      );

      $check_duplicate = array(
        'date' => date('Y-m-d', strtotime($request->date)),
        'user_id' => $request->userid,
        'deleted_ids' => ' ',
        'pickup_location' => $request->pickup_location,
        'drop_location' => $request->drop_location
      );
      $count = Push_ride::where($check_duplicate)->count();
      if ($count < 1) {
        $res = Push_ride::create($Arr);
        if ($res) {
          $this->push_ride_notification($request->pick_latitude, $request->pick_longitude, $request->userid);
          $response = ['status' => 'success', 'message' => 'Request sent successfully!'];
        } else {
          $response = ['status' => 'error', 'message' => 'Something else wrong plase try again !'];
        }

      } else {
        $response = ['status' => 'success', 'message' => 'Request already sent !'];
      }

    } catch (Exception $e) {
      $response = ['status' => 'error', 'message' => $this->commonErrorCodes($e->getCode())];
    }
    return response()->json($response);
  }
  // ************************************************cancel_push_ride**************************************************************************
 /**
 * The User can Cancle Push Ride.
 */
  public function cancel_push_ride(Request $request)
  {
    try {
      $where = array(
        'date' => date('Y-m-d', strtotime($request->date)),
        'user_id' => $request->userid,
        'pickup_location' => $request->pickup_location,
        'drop_location' => $request->drop_location
      );
      $res = Push_ride::where($where)->delete();
      if ($res) {
        $response = ['status' => 'success', 'message' => 'Request Removed Successfully'];
      } else {
        $response = ['status' => 'error', 'message' => 'Something else wrong plase try again !'];
      }
    } catch (Exception $e) {
      $response = ['status' => 'error', 'message' => $this->commonErrorCodes($e->getCode())];
    }
    return response()->json($response);
  }
  /**
 * The User gets Push Ride Notification.
 */
  public function push_ride_notification($lat, $lon, $userid)
  {
    // $nearby/=[];
    $userdata = User::select('name', 'lname')->where('id', $userid)->get()->first();
    $name = $userdata->name . ' ' . $userdata->lname;
    $nearby = DB::table("users");
    $nearby = $nearby->select('id', DB::raw("6371 * acos(cos(radians(" . $lat . "))
                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $lon . "))
                + sin(radians(" . $lat . ")) * sin(radians(latitude))) AS distance"));
    // $nearby=$nearby->where('offer_notifications_status',1);
    $nearby = $nearby->where('users.id', '!=', $userid);
    $nearby = $nearby->having('distance', '<=', 30);
    // $nearby=$nearby->orderBy('distance', 'asc');
    $nearby = $nearby->get()->toArray();



    foreach ($nearby as $value) {
      $this->offer_notification($name, $value->id, 'push');
    }
    return true;
  }
/**
 * The Offer Submit.
 */
  public function offer_submit(Request $request)
  {

    $upda_arr = array('ride_id' => $request->offer_ride_id, 'driver_id' => $request->driver_id);
    $req_count = Request_ride::where($upda_arr)->count();
    if ($req_count < 1) {

      $keys = '';
      $udata = User::select('*')->where('id', $request->driver_id)->get()->first();
      $name = $udata->name . ' ' . $udata->lname;

      // Firebase Realtime Database URL
      $databaseURL = 'https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/';
      // Data to be sent to Firebase (replace with your data)
      $rand = time();
      $data = [
        'driver_name' => @$udata->name . ' ' . $udata->lname,
        'ride_id' => @$request->offer_ride_id,
        'price' => @$request->price,
        'users_id' => @$request->users_id,
        'driver_id' => @$request->driver_id,
        'reach_mint' => @$request->reach_mint,
        'status' => 'pending',
        'image' => $udata->image,
        'mobile' => $udata->mobile,
        'pick_latitude' => @$request->pick_latitude,
        'pick_longitude' => @$request->pick_longitude,
        'pick_locations' => @$request->pick_locations,
        'drop_latitude' => @$request->drop_latitude,
        'drop_longitude' => @$request->drop_longitude,
        'drop_locations' => @$request->drop_locations,
        'date' => $request->date,
        'driver_lat' => $udata->latitude,
        'driver_long' => $udata->longitude,
        'timestamp' => $rand
      ];

      // Convert the data to JSON
      $jsonData = json_encode($data);

      // Initialize cURL session
      $ch = curl_init();

      // Set cURL options
      curl_setopt($ch, CURLOPT_URL, $databaseURL . 'offers/' . $request->users_id . '.json'); // Replace 'path/to/resource' with the desired path in the database
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // Use 'PUT' for updating data, or 'POST' to add new data
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
        $keys = json_decode($response);
        // cURL request was successful
        $this->offer_notification_offer_submit($name, $request->users_id, 'push', $jsonData);
        $this->request_ride_db($data, $rand, $keys, $request->pick_latitude, $request->pick_longitude, $request->pick_locations, $request->drop_latitude, $request->drop_longitude, $request->drop_locations, $request->date);
        return response()->json(['status' => 'success', 'message' => 'Offer sent successfully wait for passenger response!']);
      }

      // Close cURL session
      curl_close($ch);
    } else {
      return response()->json(['status' => 'success', 'message' => 'Your request is under process please wait a moment !🚀']);

    }
  }
  /**
 * The Request Ride Database.
 */
  public function request_ride_db($data, $rand, $keys, $pick_latitude, $pick_longitude, $pick_locations, $drop_latitude, $drop_longitude, $drop_locations, $date)
  {
    $request_arr = array(
      'price' => $data['price'],
      'users_id' => $data['users_id'],
      'ride_id' => $data['ride_id'],
      'driver_id' => $data['driver_id'],
      'time' => $rand,
      'pick_latitude' => $pick_latitude,
      'pick_longitude' => $pick_longitude,
      'pick_locations' => $pick_locations,
      'drop_latitude' => $drop_latitude,
      'drop_longitude' => $drop_longitude,
      'drop_locations' => $drop_locations,
      'date' => date('Y-m-d', strtotime($date)),
      'firebase_key' => $keys->name,
      'reach_mint' => $data['reach_mint'],
      'status' => $data['status'],
      'created_at' => date('Y-m-d H:i:s')
    );
    $res = Request_ride::create($request_arr);
    if ($res) {
      return true;
    } else {
      return true;
    }
  }
  /**
 * sending push notification to near by user and or drivers 
 */
  public function offer_notification_offer_submit($name, $users_id, $status = '', $jsonData)  //sending push notification to near by user and or drivers 
  {
    $API_SERVER_KEY = "AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
    $registrationIds = array();
    $title = 'Ride Offer';
    $desc = $name . ' send you a ride offer';

    $getting_token_info = Token::select('*')->where('user_id', $users_id)->get()->toArray();
    if (!empty($getting_token_info) && count($getting_token_info) > 0) {
      foreach ($getting_token_info as $key => $valuedata) {
        $registrationIds[] = $valuedata['token'];
      }
      $data = (object) array('type' => 'offer submit', 'side' => 'user', 'ridedata' => @$jsonData);
      $msg = array
      (
        'body' => $desc,
        'title' => $title,
        'icon' => 'myicon',/*Default Icon*/
        'sound' => 'mySound'/*Default sound*/
      );
      $fields = array
      (
        'registration_ids' => $registrationIds,
        'notification' => $msg,
        'data' => $data

      );
      $headers = array
      (
        'Authorization: key=' . $API_SERVER_KEY,
        'Content-Type: application/json'
      );

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
      curl_close($ch);

    }
    return true;

  }
/**
 * sending push notification to near by user and or drivers
 */
  public function offer_notification($name, $users_id, $status = '')  //sending push notification to near by user and or drivers 
  {
    $API_SERVER_KEY = "AAAAYzb8jp4:APA91bEDjUHBZpKp3pTCUiBCBR3uztZmLP3DIIXtcUGL5Zb_aL8Kpi62pazVsM7U_NXnnbhwtyBk1NNXgMgVxSzyHvlEe5chrQ5ICK6y2MpQguEl-rdNtoeDuAJCnfkCROQQE1Biw2Vh";
    $registrationIds = array();
    $title = 'Ride Offer';
    $desc = $name . ' send you a ride offer';

    $getting_token_info = Token::select('*')->where('user_id', $users_id)->get()->toArray();
    if (!empty($getting_token_info) && count($getting_token_info) > 0) {
      foreach ($getting_token_info as $key => $valuedata) {
        $registrationIds[] = $valuedata['token'];
      }
      $data = (object) array('type' => 'send request', 'side' => 'driver', 'ridedata' => []);
      $msg = array
      (
        'body' => $desc,
        'title' => $title,
        'icon' => 'myicon',/*Default Icon*/
        'sound' => 'mySound'/*Default sound*/
      );
      $fields = array
      (
        'registration_ids' => $registrationIds,
        'notification' => $msg,
        'data' => $data

      );
      $headers = array
      (
        'Authorization: key=' . $API_SERVER_KEY,
        'Content-Type: application/json'
      );

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
      curl_close($ch);

    }
    return true;

  }
   /**
 * status of the push notification to near by user and or drivers 
 */
  public function offer_notifications_status(Request $request)
  {
    try {
      $userid = $request->userid;
      $status = $request->status;
      $update = array('offer_notifications_status' => $status);
      $res = User::where('id', $userid)->update($update);
      if ($res) {
        $lang_mess = $request->lang == 'en' ? 'Updated successfully' : 'Estás excedido el límite';
        $response = ['status' => 'success', 'message' => $lang_mess];

      } else {
        $lang_mess = $request->lang == 'en' ? 'Something else wrong please try again !' : 'algo más está mal por favor inténtalo de nuevo!';
        $response = ['status' => 'error', 'message' => $lang_mess];

      }
    } catch (Exception $e) {
      $response = ['status' => 'error', 'message' => $this->commonErrorCodes($e->getCode())];

    }
    return response()->json($response);
  }
 /**
 * Offers Rides to the User.
 */
  public function myoffers_ride($userid)
  {
    $data = $this->get_offerd_ride($userid);
    return $data;

  }
   /**
 * User get Offered Rides 
 */
  public function get_offerd_ride($userid)
  {
    $today = date('Y-m-d h:i a');
    $today1 = strtotime($today);
    $data = Ride::join('users', 'users.id', '=', 'add_rides.userid')
      ->join('verify_id', 'verify_id.user_id', '=', 'users.id', 'left')
      ->join('vehicles', 'vehicles.id', '=', 'add_rides.vehicle_id')
      ->select(
        'users.profile_status',
        'users.is_verifyId',
        'users.id as driver_id',
        'add_rides.ride_status',
        'add_rides.ride_type',
        'add_rides.created_at',
        'add_rides.id',
        'users.name',
        'users.lname',
        'users.mobile',
        'users.image',
        'add_rides.pick_location',
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
        'add_rides.stoppage',
        'add_rides.admin_status',
        'add_rides.complete_status',
        'vehicles.plate_number',
        'vehicles.vehicle_brand',
        'vehicles.country',
        'vehicles.vehicle_model',
        'vehicles.vehicle_type',
        'vehicles.vechicle_color',
        'vehicles.vechicle_madeyear',
        'vehicles.vehicle_img',
        'add_rides.instant_booking',
        'add_rides.small_bag',
        'add_rides.hand_bag',
        'add_rides.regular_bag',
        'add_rides.oversize_bag',
        'add_rides.pending_small_bag',
        'add_rides.pending_hand_bag',
        'add_rides.pending_regular_bag',
        'add_rides.pending_oversize_bag',
        'add_rides.ride_type',
        'add_rides.paid_status'
      )
      ->where('add_rides.userid', $userid)
      ->where('add_rides.delete_status', '=', 'active')
      ->orderBy('add_rides.date', 'DESC')
      ->get()
      ->toArray();
    $percentage = 5;
    foreach ($data as $key => $value) {
      $data[$key]['commission'] = ($percentage / 100) * $value['price'];   //admin commission of offer ride  the 5% commission  
      $data[$key]['reminder_price'] = $value['price'] - $data[$key]['commission'];
      $data[$key]['request_status'] = 0;
      $exdate = strtotime($value['date'] . ' ' . $value['time']);
      if ($today1 > $exdate) {
        $data[$key]['expiredate'] = true;
      } else {
        $data[$key]['expiredate'] = false;
      }
      $count = Apply_ride::where('ride_id', $value['id'])->where('instant_status', 1)->count();
      if ($count > 0) {
        $data[$key]['booking_count'] = 1;
      } else {
        $data[$key]['booking_count'] = 0;
      }
      $milesss = $this->distance($value['pick_lat'], $value['pick_long'], $value['drop_lat'], $value['drop_long']);
      $data[$key]['distance'] = (string) $milesss['distance'] . 'KM';
      $data[$key]['hours'] = $milesss['time'];

      $thredate = date('H:i', strtotime($value['date'] . ' ' . $value['time']));
      $timess = strtotime($thredate . '+' . $milesss['time']);
      $newfff = date('H:i A', $timess);
      $data[$key]['estimate_hours'] = $newfff;

      if (empty($value['stoppage']) || $value['stoppage'] == null) {
        $data[$key]['stoppage'] = [];

      }
      if ($value['is_verifyId'] == null || $value['is_verifyId'] == '') {
        $data[$key]['is_verifyId'] = 0;
      }
      // booked user function 
      $data[$key]['offer_notifications_status'] = $this->offer_notifications_status_check($userid);
      $booked_users = $this->booked_users($value['id']);
      $data[$key]['booked_users'] = $booked_users;

    }
    return $data;


  }
   /**
 * sending offer notification status check to near by user and or drivers 
 */
  public function offer_notifications_status_check($uid)
  {
    $res = User::select('offer_notifications_status')->where('id', $uid)->get()->first();
    return $res->offer_notifications_status;
  }
   /**
 * Imformation of the Booked User. 
 */
  public function booked_users($id)
  {
    $booked_users = Apply_ride::select(
      'apply_ride.small_bag',
      'apply_ride.hand_bag',
      'apply_ride.regular_bag',
      'apply_ride.oversize_bag',
      'apply_ride.id',
      'apply_ride.ride_id',
      'apply_ride.driver_id',
      'apply_ride.id',
      'apply_ride.passenger_id',
      'users.name',
      'users.lname',
      'users.mobile',
      'users.gender',
      'users.bio',
      'users.dob',
      'users.email',
      'users.image',
      'apply_ride.passenger_count as usersheetcount',
      'apply_ride.instant_status',
      'apply_ride.confirm_book'
    )
      ->join('users', 'users.id', '=', 'apply_ride.passenger_id')
      ->where('apply_ride.cancel_booking', '=', 'true')
      ->where('apply_ride.ride_id', $id)->get()->toArray();

    foreach ($booked_users as $eky => $value123) {
      $dfgg = Rating_reviews::select('rating', 'reviews')->where('ride_id', $value123['ride_id'])->where('receiver_id', $value123['passenger_id'])->first();
      $ratingavg = Rating_reviews::where('receiver_id', $value123['passenger_id'])->avg('rating');

      $booked_users[$eky]['rating_avg'] = round(@$ratingavg);

      if (!empty($dfgg)) {
        $booked_users[$eky]['rating'] = @$dfgg->rating;
        $booked_users[$eky]['reviews'] = @$dfgg->reviews;
      } else {
        $booked_users[$eky]['reviews'] = false;
        $booked_users[$eky]['rating'] = false;
      }

    }
    return $booked_users;
  }
   /**
 * sending Imformation of the Booked Rides.
 */
  public function booked_rides($userid)   //main calling function 
  {

    //all booking user and drivers 
    $data = Apply_ride::join('add_rides', 'add_rides.id', '=', 'apply_ride.ride_id')
      ->join('users', 'users.id', '=', 'apply_ride.driver_id')
      ->join('vehicles', 'vehicles.id', '=', 'add_rides.vehicle_id')
      ->join('verify_id', 'verify_id.user_id', '=', 'users.id', 'left')
      // ->join('rating_reviews','rating_reviews.sender_id','=','apply_ride.driver_id')
      ->select(
        'users.is_verifyId',
        'add_rides.id',
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
        'add_rides.ride_type',
        'add_rides.paid_status',
        'add_rides.ride_status',
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
        'add_rides.admin_status',
        'apply_ride.passenger_count as usersheetcount',
        'apply_ride.price as updated_price',
        'add_rides.small_bag',
        'add_rides.hand_bag',
        'add_rides.regular_bag',
        'add_rides.oversize_bag',
        'add_rides.pending_small_bag',
        'add_rides.pending_hand_bag',
        'add_rides.pending_regular_bag',
        'add_rides.pending_oversize_bag'
      )
      ->where('apply_ride.passenger_id', $userid)
      ->where('add_rides.delete_status', '=', 'active')
      //the instant_status 1 mean it mean users can directly booking    
      // ->groupBy('apply_ride.ride_id')
      ->orderBy('add_rides.date', 'DESC')
      ->get()
      ->toArray();
    if ($data) {
      $data = $this->booked_rides_data($data, $userid);
      return $data;
    } else {
      return $data;
    }

  }
   /**
 * Sending Booked Ride Data.
 */
  public function booked_rides_data($data, $userid)
  {
    $today = date('Y-m-d h:i a');
    $today1 = strtotime($today);
    foreach ($data as $key => $value) {
      $round = Rating_reviews::where('receiver_id', $value['driver_id'])->avg('rating');
      $data[$key]['rating_avg'] = round(@$round);
      $data[$key]['booked_users'] = Apply_ride::select(
        'apply_ride.id',
        'apply_ride.passenger_id',
        'users.name',
        'users.lname',
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
        'apply_ride.confirm_book'
      )
        ->join('users', 'users.id', '=', 'apply_ride.passenger_id')
        ->where('cancel_booking', '=', 'true')
        ->where('apply_ride.ride_id', $value['id'])->get()->toArray();
      foreach ($data[$key]['booked_users'] as $i => $vald) {
        $rating_avg = Rating_reviews::where('receiver_id', $vald['passenger_id'])->avg('rating');
        $data[$key]['booked_users'][$i]['rating_avg'] = round(@$rating_avg);

      }
      $milesss = $this->distance($value['pick_lat'], $value['pick_long'], $value['drop_lat'], $value['drop_long']);
      $data[$key]['distance'] = (string) $milesss['distance'] . 'KM';
      $data[$key]['hours'] = $milesss['time'];
      $thredate = date('h:i', strtotime($value['date'] . ' ' . $value['time']));
      $timess = strtotime($thredate . '+' . $milesss['time']);
      $newfff = date('h:i A', $timess);
      $data[$key]['estimate_hours'] = $newfff;
      if ($value['is_verifyId'] == null || $value['is_verifyId'] == '') {
        $data[$key]['is_verifyId'] = 0;
      }
      $exdate = strtotime($value['date'] . ' ' . $value['time']);
      $data[$key]['expiredate'] = $today1 > $exdate ? true : false;
      $data[$key]['stoppage'] = empty($value['stoppage']) || $value['stoppage'] == null ? [] : $value['stoppage'];

      if ($value['instant_status'] == 0) {
        $data[$key]['instant_status'] = 'Hold on ! While your driver accept your request';
        $data[$key]['confirm_book'] = 0;

      }

      if ($value['cancel_booking'] == 'Cancelled')//from driver side canceled
      {
        $data[$key]['instant_status'] = 'Request has been canceled by driver';
        $data[$key]['confirm_book'] = 0;
      }

      if ($value['cancel_booking'] == 'Cancelled' && $value['confirm_book'] == 1) //cancel by user
      {
        // $data[$key]['cancel_booking']='Booked';
        $data[$key]['instant_status'] = 'Booking has been canceled';
        $data[$key]['confirm_book'] = 0;

      }

      if ($value['cancel_booking'] == 'Cancelled') {
        $data[$key]['instant_status'] = 'Booking has been canceled';
      }

      if ($value['instant_status'] == 1 && $value['cancel_booking'] == 'true') {
        $data[$key]['instant_status'] = 'Booked';
      }
      if ($value['instruction'] == null) {
        $data[$key]['instruction'] = '';
      }
      $ddf = Rating_reviews::select('users.name', 'users.lname', 'rating_reviews.rating', 'rating_reviews.reviews')
        ->join('apply_ride', 'apply_ride.ride_id', '=', 'rating_reviews.ride_id')
        ->join('users', 'users.id', '=', 'apply_ride.driver_id')
        ->where('apply_ride.passenger_id', $userid)
        ->where('apply_ride.ride_id', $value['id'])->get()->first();
      if ($ddf) {
        $data[$key]['rating_reviews'] = $ddf;
      } else {
        $data[$key]['rating_reviews'] = [];
      }
      $data[$key]['my_rating_review_status'] = Rating_reviews::where('sender_id', $userid)->where('ride_id', $value['id'])->count();
    }
    return $data;


  }
   /**
 * Show the Payment History. 
 */
  public function payment_history(Request $request)
  {
    $userid = $request->userid;
    $data = Payment_history::select('amount', 'payment_mode', 'payment_status', 'created_at')->where('user_id', $userid)->get()->toArray();
    if ($data) {
      $lang_mess = $request->lang == 'en' ? 'data found' : 'datos encontrados';
      $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $data];
    } else {
      $lang_mess = $request->lang == 'en' ? 'No data found' : 'Datos no encontrados';
      $response = ['status' => 'success', 'message' => $lang_mess, 'data' => $data];
    }
    return response()->json($response);
  }
   /**
 * User Delete Offered Ride.
 */
  public function delete_offered_ride_user_end(Request $request) //
  {

    $userid = $request->userid;
    $offer_ride_id1 = $request->offer_ride_id;
    try {
      $newarr = [];
      $newarr4 = [];
      $newarr3 = [];
      $driver_id = $request->driver_id;  // push ride users 
      $offer_ride_id = $request->offer_ride_id;
      $data = Push_ride::select('deleted_ids', 'id')->where('id', $offer_ride_id)->get()->first();

      $deleted_ids = $data->deleted_ids;
      if (!empty($deleted_ids)) {
        $id_arr = explode(',', $deleted_ids);
        array_push($newarr, $driver_id);
        $newarr3 = array_merge($newarr, $id_arr);
        $newarr3 = array_unique($newarr3);
        $newarr4 = implode(',', $newarr3);

        $res1 = Push_ride::where('id', $offer_ride_id)->update(['deleted_ids' => $newarr4]);
        $arrays = ['ride_id' => $offer_ride_id, 'driver_id' => $driver_id];
        DB::table('request_ride')->where($arrays)->delete();

        $this->delete_firebase_rides($userid, $offer_ride_id1);
        if ($res1) {
          $response = ['status' => 'success', 'message' => 'Removed successfully'];
        } else {
          $response = ['status' => 'error', 'message' => 'Something else wrong plase try again !'];
        }
      } else {

        $res1 = Push_ride::where('id', $offer_ride_id)->update(['deleted_ids' => $request->driver_id]);
        if ($res1) {
          $arrays = ['ride_id' => $offer_ride_id, 'driver_id' => $driver_id];
          DB::table('request_ride')->where($arrays)->delete();

          $this->delete_firebase_rides($userid, $offer_ride_id1);
          $response = ['status' => 'success', 'message' => 'Removed successfully'];
        } else {
          $response = ['status' => 'error', 'message' => 'Something else wrong plase try again !'];
        }
      }


    } catch (Exception $e) {
      $response = ['status' => 'error', 'message' => $this->commonErrorCodes($e->getCode())];
    }
    return response()->json($response);

  }
 /**
 * Driver Delete Offered Ride.
 */
  public function delete_offered_ride_driver_end(Request $request) //
  {


    $driver_id = $request->driver_id;
    $offer_ride_id1 = $request->offer_ride_id;
    try {
      $newarr = [];
      $newarr4 = [];
      $newarr3 = [];
      $userid = $request->userid;  // push ride users 
      $offer_ride_id = $request->offer_ride_id;

      $data = Push_ride::select('deleted_ids', 'id')->where('id', $offer_ride_id)->get()->first();

      $deleted_ids = $data->deleted_ids;
      if (!empty($deleted_ids)) {
        $id_arr = explode(',', $deleted_ids);
        array_push($newarr, $driver_id);
        $newarr3 = array_merge($newarr, $id_arr);
        $newarr3 = array_unique($newarr3);
        $newarr4 = implode(',', $newarr3);

        $res1 = Push_ride::where('id', $offer_ride_id)->update(['deleted_ids' => $newarr4]);
        $arrays = ['ride_id' => $offer_ride_id, 'driver_id' => $driver_id];
        DB::table('request_ride')->where($arrays)->delete();
        if ($res1) {
          $this->delete_firebase_rides($userid, $offer_ride_id1);
          $response = ['status' => 'success', 'message' => 'Removed successfully'];
        } else {
          $response = ['status' => 'error', 'message' => 'Something else wrong plase try again !'];
        }
      } else {

        $res1 = Push_ride::where('id', $offer_ride_id)->update(['deleted_ids' => $request->driver_id]);
        if ($res1) {
          $arrays = ['ride_id' => $offer_ride_id, 'driver_id' => $driver_id];
          DB::table('request_ride')->where($arrays)->delete();
          $response = ['status' => 'success', 'message' => 'Removed successfully'];
        } else {
          $response = ['status' => 'error', 'message' => 'Something else wrong plase try again !'];
        }
      }

    } catch (Exception $e) {
      $response = ['status' => 'error', 'message' => $this->commonErrorCodes($e->getCode())];
    }
    return response()->json($response);

  }
   /**
 * Delete FireBase Rides. 
 */
  public function delete_firebase_rides($userid, $offer_ride_id1)
  {
    $databaseURL = 'https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/' . 'offers/' . $userid . '.json?print=pretty';
    $data = file_get_contents($databaseURL);
    $datas = json_decode($data, true);
    if ($datas) {
      foreach ($datas as $key => $value) {
        if ($value['ride_id'] == $offer_ride_id1) {
          $this->delete_firebase_row($userid, $key);

        }

      }
    }
    return true;

  }
   /**
 * Delete FireBase Row. 
 */
  public function delete_firebase_row($userid, $key)
  {
    // delete row from firebase data base 
    $databaseURL = 'https://seismic-vista-462507-f7-default-rtdb.firebaseio.com/' . 'offers/' . $userid . '/' . $key . '.json';
    $url = $databaseURL;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
    $result = curl_exec($ch);
    curl_close($ch);
    return true;

  }
   /**
 * sending Payment Request. 
 */
  public function pay(Request $request)
  {
    $amount = $request->amount ? $request->amount : 1;
    $passenger_id = $request->passenger_id ? $request->passenger_id : 4;
    $driver_id = $request->driver_id ? $request->driver_id : 8;
    $ride_id = $request->ride_id ? $request->ride_id : 0;
    $location = Push_ride::select('pickup_location as pick_location', 'drop_location')->where('id', $ride_id)->first();


    $array['data'] = array('amount' => $amount, 'passenger_id' => $passenger_id, 'ride_id' => $ride_id, 'driver_id' => $driver_id, 'pick_location' => @$location->pick_location, 'drop_location' => @$location->drop_location);

    // echo"<pre>";print_r($array);die;
    return view('paypal/paypal', $array);
  }
 /**
 * Saving the Payment  Information.
 */
  public function save_payment_into(Request $request)
  {
    $amount = $request->amount;
    $driver_id = $request->driver_id;
    $passenger_id = $request->passenger_id;
    $ride_id = $request->ride_id;

    $array0 = array(
      'ride_id' => $ride_id,
      'amount' => $amount,
      'commission' => 0,
      'user_id' => $driver_id,
      'uid' => $passenger_id,
      'payment_mode' => 'Paypal',
      'payment_status' => 'Paid',
      'status' => 0,
      'user_type' => 'driver',
      'created_at' => date('Y-m-d H:i:s')
    );

    $array1 = array(
      'ride_id' => $ride_id,
      'amount' => $amount,
      'commission' => 0,
      'user_id' => $passenger_id,
      'uid' => $driver_id,
      'payment_mode' => 'Paypal',
      'payment_status' => 'Pending',
      'status' => 0,
      'user_type' => 'user',
      'created_at' => date('Y-m-d H:i:s')
    );



    $res2 = Payment_history::create($array0);
    $res = Payment_history::create($array1);
    if ($res2) {
      return true;
    } else {
      return false;
    }

  }
   /**
 * Complete Offer Ride.
 */
  public function complete_offer_ride(Request $request)
  {
    try {
      $id = $request->ride_id;
      $driver_id = $request->driver_id;
      $passenger_id = $request->passenger_id;
      $update = array('complete_status' => 1, 'ride_status' => 'complete');
      $res = Ride::where('id', $id)->update($update);
      if ($res) {
        $desc = "Your ride has been completed, it's time to give a review to driver";
        $this->send_notification_auto_complete_ride('booked', $passenger_id, $driver_id, $id, $desc);
        $response = ['status' => 'success', 'message' => 'Ride successfully completed !'];
      } else {
        $response = ['status' => 'error', 'message' => 'Something else wrong please try again !'];
      }
    } catch (\PDOException $e) {
      $response = ['status' => 'error', 'message' => 'Something else wrong please try again !'];
    }

    return response()->json($response);
  }
   /**
 * Starting the Ride. 
 */
  public function start_ride(Request $request)
  {
    try {
      $id = $request->ride_id;
      $driver_id = $request->driver_id;
      $passenger_id = $request->passenger_id;
      $update = array('ride_status' => 'start');
      $res = Ride::where('id', $id)->update($update);
      if ($res) {
        $desc = "Let's Go 🚙, Happy Journey 👋!";
        $this->send_notification_auto_complete_ride('booked', $passenger_id, $driver_id, $id, $desc);
        $response = ['status' => 'success', 'message' => 'Ride started successfully !'];
      } else {
        $response = ['status' => 'error', 'message' => 'Something went wrong please try again !'];
      }
    } catch (\PDOException $e) {
      $response = ['status' => 'error', 'message' => 'Something went wrong please try again !'];
    }
    return response()->json($response);
  }

 
 /**
 * Generating the Access Key.
 */

  public function access_keys()
  {

    $data = Setting::get()->first();
     $data['client_id']= $data  ? (env('PHONEPE_CLIENT_ID')) : null;
     $data['client_secret']= $data  ? (env('PHONEPE_CLIENT_SECRET')) : null;

    return response()->json(['message' => 'Keys Fetch', 'status' => 'success', 'data' => $data]);

  }
 /**
 * sending  notification to near by user  to search ride.
 */
  public function notify_search_ride(Request $request)
  {
      // Validate the request
      $validator = Validator::make($request->all(), [
          'search_id' => 'required',
      ]);
  
      if ($validator->fails()) {
          return response()->json([
              'status' => 'error',
              'message' => $validator->errors()->first(),
          ], 422);
      }
  
      $history = RecentHistory::find($request->search_id);
  
      if ($history->notify == '1') {
          return response()->json([
              'status' => 'success',
              'message' => 'Notifications are already enabled for this search.',
          ]);
      }
  
      $history->notify = 1;
      $history->save();
  
      return response()->json([
          'status' => 'success',
          'message' => 'You will be notified when a ride matches your search.',
      ]);
  }
   /**
 * sending request to offer ride.
 */
  public function req_to_offer_ride(Request $request)
{
    // Validate the request
    $validator = Validator::make($request->all(), [
        'id' => 'required|exists:users,id', 
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first(),
        ], 422);
    }

    $user = User::find($request->id);

    if ($user->offer_ride_status == 0) {
        $user->offer_ride_status = 1; 
        $user->save();

         $adminId=1;
         $noti_arr = array('user_id' =>$request->id, 'messages' => $user->fname . '' . $user->lname.' User has Requested to Offer Rides', 'admin_id'=>$adminId,'type'=>'offerRideAccess');
          Notifications::create($noti_arr);

        return response()->json([
            'status' => 'success',
            'message' => 'Your request has been sent to the admin successfully. After approval, you can publish your ride.',
        ]);
        } elseif ($user->offer_ride_status == 1) {
            return response()->json([
                'status' => 'success',
                'message' => 'Your request is in the queue. We will notify you once it is reviewed.',
            ]);
        } elseif ($user->offer_ride_status == 3) {
            return response()->json([
                'status' => 'success',
                'message' => 'Your request to offer a ride has been declined by the admin. Please contact the admin for more information.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'You will be notified when a ride matches your request.',
        ]);
    }


       /**
 * User Documents. 
 */

    public function user_documents(Request $request)
{
    // Validate the request
    $validator = Validator::make($request->all(), [
        'user_id' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first(),
        ], 422);
    }

    // Fetch user documents
    $userData = Verify_id::where('user_id', $request->user_id)->first();

    // if (!$userData) {
    //     return response()->json([
    //         'status' => 'error',
    //         'message' => 'No document data found for the user.',
    //     ], 404);
    // }

    // Define the documents and their mapping
    $documents = [
        [
            'key' => 'id_proof',
            'key_exp' => 'id_proof_expdate',
            'label' => 'ID Proof',
            'status' => 'id_proff_status',
            'exp' => 'id_proof_expdate',
        ],
        [
            'key' => 'driving_licence',
            'key_exp' => 'driving_licence_expdate',
            'label' => 'Driving Licence',
            'status' => 'driving_licence_status',
            'exp' => 'driving_licence_expdate',
           
        ],
        [
            'key' => 'vehicle_plate',
            'key_exp' => 'vehicle_plate_expdate',
            'label' => 'Vehicle Plate',
            'status' => 'vehicle_plate_status',
            'exp' => 'vehicle_plate_expdate',
        ],
        [
            'key' => 'insurance',
            'key_exp' => 'insurance_expdate',
            'label' => 'Insurance',
            'status' => 'insurance_status',
            'exp' => 'insurance_expdate',
        ],
        [
            'key' => 'vehicle_rc',
            'key_exp' => 'vehicle_rc_expdate',
            'label' => 'Vehicle RC',
            'status' => 'vehicle_rc_status',
            'exp' => 'vehicle_rc_expdate',
        ],
        [
            'key' => 'fitness_certificate',
            'key_exp' => 'fitness_certificate_expdate',
            'label' => 'Vehicle Fitness',
            'status' => 'fitness_certificate_status',
            'exp' => 'fitness_certificate_expdate',
        ],
        [
            'key' => 'tax_receipt',
            'key_exp' => '',
            'label' => 'Vehicle Tax Receipt',
            'status' => '',
            'exp' => null,
        ],
        [
            'key' => 'registration_slip',
            'key_exp' => '',
            'label' => 'Vehicle Registration Slip',
            'status' => '',
            'exp' => null,
        ],
        [
            'key' => 'tourist_permit',
            'key_exp' => 'tourist_permit_expdate',
            'label' => 'Vehicle Tourist Permit',
            'status' => 'tourist_permit_status',
            'exp' => 'tourist_permit_expdate',
        ],
        [
            'key' => 'driving_licence_tr',
            'key_exp' => 'driving_licence_tr_expdate',
            'label' => 'Driving Licence TR',
            'status' => 'driving_licence_tr_status',
            'exp' => 'driving_licence_tr_expdate',
        ],
        [
            'key' => 'puc',
            'key_exp' => 'puc_expdate',
            'label' => 'PUC Certificate',
            'status' => 'puc_status',
            'exp' => 'puc_expdate',
        ],
    ];

    // Build response data
    $response = [];
    foreach ($documents as $doc) {
      if($userData){

        $response[] = [
            'key' => $doc['key'],
            'key_exp' => $doc['key_exp'],
            'key_status' => $doc['status'],
            'file' =>$userData[$doc['key']] ? url($userData[$doc['key']]) : null,
            'label' => $doc['label'],
            'status' => $userData[$doc['status']] ?? null,
            'exp' => $doc['exp'] ? ($userData[$doc['exp']] ?? null) : null,
        ];
      }
            else{

              $response[] = [
                  'key' => $doc['key'],
                  'key_exp' => $doc['key_exp'],
                  'key_status' => $doc['status'],
                  'file' => null,
                  'label' => $doc['label'],
                  'status' =>  null,
                  'exp' => null,
              ];
            }
              
          }

          return response()->json([
              'status' => 'success',
              'documents' => $response,
          ]);
      }
 /**
 * User Apply Coupon.
 */

      public function applyCoupon(Request $request)
        {
          // Validate the request
          $validator = Validator::make($request->all(), [
              'code' => 'required',
          ]);

          if ($validator->fails()) {
              return response()->json([
                  'status' => 'error',
                  'message' => $validator->errors()->first(),
              ], 422);
          }

            $code=$request->code;
            $offer = Offer::where('coupon_code', $code)
                ->where('type', 'coupon')
                ->where('is_active', 1)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            if (!$offer) {
                return ['success' => false, 'message' => 'Invalid or expired coupon'];
            }

            return ['success' => true, 'discount' => $offer->discount, 'discount_type' => $offer->discount_type];
        }

       

    //  /////////////////////////////Payment Process with phonepe ///////////////////////

    
    //   private function getBaseUrl()
    // {
    //     return env('PHONEPE_ENV') === 'production'
    //         ? 'https://api.phonepe.com'
    //         : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
    // }
      


// public function payment(Request $request)
// {

     
//      $amount= $request->amount;
//      $merchantOrderId  = $request->merchantOrderId;
//      $baseUrl = $this->getBaseUrl();

//     // 1. Get OAuth Token
//     $tokenUrl = $baseUrl . '/v1/oauth/token';

//     $tokenResponse = Http::asForm()->post($tokenUrl, [
//         'client_id' => env('PHONEPE_CLIENT_ID'),
//         'client_secret' => env('PHONEPE_CLIENT_SECRET'),
//         'client_version' => env('PHONEPE_CLIENT_VERSION'),
//         'grant_type' => 'client_credentials',
//     ]);

//     if (!$tokenResponse->successful()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Failed to get access token',
//             'error' => $tokenResponse->json(),
//         ], 500);
//     }


    
//     $accessToken = $tokenResponse->json('access_token');
//     $expires_in = $tokenResponse->json('expires_in');

//     // 2. Create Order
//     $orderUrl = $baseUrl . '/checkout/v2/sdk/order';

//     $orderPayload = [
//         'amount' => $amount,
//         'expireAfter' => $expires_in,
//         'metaInfo' => [
//             'udf1' => '',
//             'udf2' => '',
//             'udf3' => '',
//             'udf4' => '',
//             'udf5' => '',
//         ],
//         'paymentFlow' => [
//             'type' => 'PG_CHECKOUT',
//         ],
//     ];

//     $orderResponse = Http::withHeaders([
//         'Content-Type' => 'application/json',
//         'Authorization' => 'Bearer ' . $accessToken,
//     ])->post($orderUrl, $orderPayload);

//     if (!$orderResponse->successful()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Order creation failed',
//             'error' => $orderResponse->json(),
//         ], $orderResponse->status());
//     }

//     // 3. Check Order Status
//         $statusUrl = $baseUrl . "/checkout/v2/order/{$merchantOrderId}/status";

//         $statusResponse = Http::withHeaders([
//             'Content-Type' => 'application/json',
//             'Authorization' => 'Bearer ' . $accessToken,
//         ])->get($statusUrl);

//         if (!$statusResponse->successful()) {
//             return response()->json([
//                 'status' => false,
//                 'message' => 'Failed to fetch order status',
//                 'error' => $statusResponse->json(),
//             ], $statusResponse->status());
//         }

//         return response()->json([
//             'status' => true,
//             'token' => $accessToken,
//             'order_response' => $orderResponse->json(),
//             'order_status' => $statusResponse->json(),
//         ]);
// }



    



 /**
 * Get BaseUrl.
 */

    private function getBaseUrl()
    {
        return env('PHONEPE_ENV') === 'production'
            ? 'https://api.phonepe.com/apis/identity-manager/v1'
            : 'https://api-preprod.phonepe.com/apis/pg-sandbox/v1';
    }
 /**
 * Get Checkout BaseUrl.
 */
    private function getCheckoutBaseUrl()
    {
        return env('PHONEPE_ENV') === 'production'
            ? 'https://api.phonepe.com/apis/pg/checkout/v2'
            : 'https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2';
    }
/**
 * Get Access Token.
 */
    private function getAccessToken()
    {
        $tokenUrl = $this->getBaseUrl() . '/oauth/token';

        $response = Http::asForm()->post($tokenUrl, [
            'client_id' => env('PHONEPE_CLIENT_ID'),
            'client_secret' => env('PHONEPE_CLIENT_SECRET'),
            'client_version' => env('PHONEPE_CLIENT_VERSION'),
            'grant_type' => 'client_credentials',
        ]);

        if ($response->successful()) {

            return $response->json('access_token');
        }

        return null;
    }
/**
 * Initiate the Request.
 */
    // POST /payment/initiate
    public function initiate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'user_id' => 'required',
        ]);

        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to get access token',
            ], 500);
        }

        // Create unique order ID (could customize more)
        $orderId = 'ORDER_' . time();

        // Save initial payment record
        $payment = payment::create([
            'name' => $request->name,
            'email' => $request->email ?  $request->email : 'testing@gmail.com',
            'phone' => $request->phone,
            'amount' => $request->amount,
            'order_id' => $orderId,
            'status' => 0,
            'user_id' => $request->user_id,
        ]);

        // Prepare order creation payload
        $orderPayload = [
            'amount' => $request->amount * 100,
            'expireAfter' => 1200,
            'metaInfo' => [
                'udf1' => '',
                'udf2' => '',
                'udf3' => '',
                'udf4' => '',
                'udf5' => '',
            ],
            'paymentFlow' => [
                'type' => 'PG_CHECKOUT',
            ],
            'merchantOrderId' => $orderId, // Important to send your order id here
        ];

        $orderUrl = $this->getCheckoutBaseUrl() . '/sdk/order';


        $orderResponse = Http::withHeaders([
            'Authorization' => 'O-Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post($orderUrl, $orderPayload);


        \Log::info('PhonePe Order Payload New', [
        'url' => $orderUrl,
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ],
        'body' => $orderPayload
    ]);

        // echo"<pre>"; print_r($orderResponse);die;


        if (!$orderResponse->successful()) {
            return response()->json([
                'status' => false,
                'message' => 'Order creation failed',
                'error' => $orderResponse->json(),
            ], $orderResponse->status());
        }

        $responseData = $orderResponse->json();

        return response()->json([
            'status' => true,
            'order_id' => $orderId,
            'merchantOrderId' => env('PHONEPE_CLIENT_ID'),
            // 'payment_url' => $responseData['data']['instrumentResponse']['redirectInfo']['url'] ?? null,
            'raw_response' => $responseData,
        ]);
    }

    // POST /payment/callback

/**
 * User can Callback.
 */
    
    public function callback(Request $request)
{
    $transactionId = $request->input('transactionId');
    $merchantOrderId = $request->input('merchantOrderId');

    if (!$transactionId || !$merchantOrderId) {
        return response()->json([
            'message' => 'Transaction ID or Merchant Order ID is missing.'
        ], 400);
    }

    // Get access token (from method or config)
    $accessToken = $this->getAccessToken();

    if (!$accessToken) {
        return response()->json([
            'message' => 'Unable to retrieve access token.'
        ], 500);
    }

    // Build status URL
    $statusUrl = $this->getCheckoutBaseUrl() . "/order/{$merchantOrderId}/status";

    // Call PhonePe API to get order status
    $statusResponse = Http::withHeaders([
        'Authorization' => 'O-Bearer ' . $accessToken,
        'Content-Type'  => 'application/json',
    ])->get($statusUrl);

    if (!$statusResponse->successful()) {
        return response()->json([
            'message' => 'Failed to get payment status.',
            'error'   => $statusResponse->json(),
        ], $statusResponse->status());
    }

    // Parse the status response
    $statusData = $statusResponse->json();
    $state = $statusData['state'] ?? 'PENDING';
    // echo"<pre>";print_r($state );die;
    // Update payment in the database
    payment::where('order_id', $merchantOrderId)->update([
        // 'status'     => $state === 'COMPLETED' ? 1 : 0,
        'status'     => $state,
        'payment_id' => $transactionId,
        // 'other'      => json_encode($statusData['data']),
    ]);

    return response()->json([
        'message' => 'Callback processed successfully.',
        // 'status'  => $state === 'COMPLETED' ? 'Success' : 'Pending/Failed',
        'details' => $statusData,
    ]);
}
/**
 * User Status.
 */
    // GET /payment/status/{order_id}
    public function status(Request $request)
    {
        $orderId=$request->orderId;
        $payment = payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['status' => false, 'message' => 'Payment not found'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'order_id' => $payment->order_id,
                'payment_id' => $payment->payment_id,
                'amount' => $payment->amount,
                'status' => $payment->status == 1 ? 'Success' : 'Pending/Failed',
                'details' => json_decode($payment->other, true),
            ],
        ]);
    }
}



