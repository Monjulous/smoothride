<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ride;
use App\Models\Apply_ride;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Verify_id;
use App\Models\RecentHistory;
use App\Models\Color;
use App\Models\Car_brand;
use App\Models\Car_model;
use App\Models\Postal_address;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

       public function distance($lat1, $long1, $lat2, $long2) {
            $token=env('Google_Token');
            
            
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
                return array('distance' => round((float)$km,2), 'time' => $time ,'token'=>$token );
            }

    public function dashboard()
    {

         
        $user['user_count'] = User::where('user_type','!=','admin')->count();
        $user['Apply_ride'] = Apply_ride::count();
        $ride_latlong=Ride::select('pick_lat','pick_long','drop_lat','drop_long')->where('complete_status',1)->get()->toArray();
          $thetmain=[];
         $sum = 0;
         if(!empty($ride_latlong))
         {
           foreach ($ride_latlong as $key => $value) { 
           $thetmain[]=$this->distance($value['pick_lat'],$value['pick_long'],$value['drop_lat'],$value['drop_long']);
           }

            foreach($thetmain as $key => $value) {
            $sum += $value['distance'];
         }
        }
       
         $user['route_count']= Ride::where('date',date("Y-m-d"))->count();
         $user['total_km']=$sum;
         return view('admin.dashboard',compact('user'));
    }
}
