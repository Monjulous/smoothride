<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class Sendsms
{
    public static function Sendmsg($msg, $contact)
    {

        
        try {
   
            $account_sid ='';
            $auth_token = '';
            
           
            $url = "https://api.twilio.com/2010-04-01/Accounts/$account_sid/Messages.json";
            $to = $contact;
            $from = ""; 
            $body = $msg;
        
            $data = [
                'From' => $from,
                'To' => $to,
                'Body' => $body,
            ];
        
            $post = http_build_query($data);
        
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, "$account_sid:$auth_token");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        
            $response = curl_exec($curl);
        
            if ($response === false) {
                $error = curl_error($curl);
                curl_close($curl);
                throw new Exception("Curl error: $error");
            }
        
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
        
            if ($httpCode !== 201) {
                throw new Exception("Failed to send OTP: HTTP code $httpCode, Response: $response");
            }
        
            return true;

            } catch (Exception $e) {
                Log::error('Error sending SMS: ' . $e->getMessage());
                // Optionally display error for debugging
                if (app()->environment('local')) {
                    echo 'Error sending SMS: ' . htmlspecialchars($e->getMessage());
                } else {
                    echo 'There was an error sending your message. Please try again later.';
                }
            }
        

        }

}



