<?php

namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


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
class Kernel extends ConsoleKernel
{
    // public function __construct()
    // {
       
        // date_default_timezone_set('Asia/Kolkata');


    // }
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

        public function distance($lat1, $long1, $lat2, $long2) {
             date_default_timezone_set("America/Santo_Domingo");
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

     public function auto_complete_ride()
        {
             date_default_timezone_set("America/Santo_Domingo");
        $today=date('Y-m-d h:i a');
        $data=Ride::select('id','userid','date','time','pick_lat','pick_long','drop_lat','drop_long','complete_status')->where('complete_status',0)->where('ride_type','!=','offer')->get()->toArray();
             
       // $esti=0;ride_type
        $passdate=date('Y-m-d');
        Alert_notification::whereDate('date', '<',$passdate)->delete();

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
                                       
                                        $update=array('complete_status'=>1,'ride_status'=>'complete');
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
               
             
     }
               


    }
     public function get_single_ridedata($id,$userid)
    {
      $data=Ride::join('users','users.id','=','add_rides.userid')
                ->join('verify_id','verify_id.user_id','=','users.id','left')
                ->join('vehicles','vehicles.id','=','add_rides.vehicle_id')
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

                        $count=0;
                        foreach($data as $key=>$value)
                        {

                            $count=Apply_ride::where('ride_id',$value['id'])->where('instant_status',1)->count();
                            $cancelb=Apply_ride::select('cancel_booking')->where('ride_id',$value['id'])->get()->first();
                            $data[$key]['cancel_booking']=@$cancelb->cancel_booking;
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
                          $data[$key]['rating_reviews']=Apply_ride::join('users','users.id','=','apply_ride.driver_id')
                          ->join('rating_reviews','rating_reviews.receiver_id','=','apply_ride.driver_id')
                          ->select('users.name','users.lname','rating_reviews.rating','rating_reviews.reviews')
                          ->where('apply_ride.passenger_id',$userid)
                          ->where('apply_ride.ride_id',$value['id'])->get()->first();

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

    protected function schedule(Schedule $schedule)
    {

        $schedule->call(function () {
       $this->auto_complete_ride();
        })->everyMinute();
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

     }
