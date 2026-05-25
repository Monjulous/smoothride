<?php


namespace App\Service;

use Google_Client;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
// use Illuminate\Support\Facades\Http;

class AppNotify
{
    protected $serviceAccountPath;
    protected $httpClient;


    protected $apiToken;
    protected $apiUrl;
    protected $senderId;

    

    public function __construct()
    {
        $this->serviceAccountPath = __DIR__ . '/../../config/notification.json';
        $this->httpClient = new Client();

    }

    protected function getAccessToken()
    {
        if (!file_exists($this->serviceAccountPath)) {
            die('Service account file not found at ' . $this->serviceAccountPath);
        }

        $client = new Google_Client();
        $client->setAuthConfig($this->serviceAccountPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        return $client->fetchAccessTokenWithAssertion()['access_token'];
    }


    public function sendNotification($device_token, $title, $bodyMessage, $navigate_data)
    {

      
        $notification = [
            'title' => $title,
            'body' => $bodyMessage,
        ];

        // Define additional data payload (optional)
        $data = [
            'status' => 'true',
            'key2' => 'value2',
            'navigate_data'=> json_encode($navigate_data)
        ];

        $token = $this->getAccessToken();

        
        // Construct the JSON payload
        $payload = [
            'message' => [ 
                'token' => $device_token,
                'notification' => $notification,
                'data' =>$data,
            ],
        ];
          //    echo"<pre>";print_r($payload);die;
      
                try {
                    $response = $this->httpClient->post('https://fcm.googleapis.com/v1/projects/seismic-vista-462507-f7/messages:send', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json',
                        ],
                        'json' => $payload,
                    ]);

                    //  echo" notify!";
                return true;


                } catch (ClientException $e) { 
                //    echo"<pre>";print_r($e);
                    return false;
                }
    }
  
   
}



