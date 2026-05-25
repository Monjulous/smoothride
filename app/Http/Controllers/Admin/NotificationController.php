<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
class NotificationController extends Controller
{
     public function index()
    {
        $notifications = AdminNotification::latest()->paginate(15);
        $users = User::get();
        return view('admin.notification.index', compact('notifications','users'));
    }

    public function create()
    {
        $users = User::all(); // if sending to specific users
        return view('admin.notification.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            
        ]);


        AdminNotification::create($request->all());

        return redirect()->route('notifications.index')->with('success', 'Notification sent.');
    }



        public function update(Request $request, $id)
        {
            $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
            ]);

            $notification = AdminNotification::findOrFail($id);
            $notification->title = $request->title;
            $notification->message = $request->message;
            $notification->save();

            return redirect()->route('notifications.index')->with('success', 'Notification updated successfully.');
        }

   

    public function destroy($id)
    {
        AdminNotification::findOrFail($id)->delete();
        return back()->with('success', 'Notification deleted.');
    }



   public function send(Request $request)
        {
            $request->validate([
                'notify_id' => 'required|exists:admin_notifications,id',
                'send_in'   => 'required|in:app,email',
                'users'     => 'required|array|min:1',
            ]);

            $notification = AdminNotification::findOrFail($request->notify_id);

            // Load users
            $usersToNotify = in_array('all', $request->users)
                ? User::with('deviceTokens')->get()
                : User::with('deviceTokens')->whereIn('id', $request->users)->get();

            if ($usersToNotify->isEmpty()) {
                return back()->with('error', 'No users found to notify.');
            }

            foreach ($usersToNotify as $user) {

                // ---------------------------------------------------------------------
                // SEND APP (IN-APP + FCM)
                // ---------------------------------------------------------------------
                if ($request->send_in === 'app') {

                    // Optional Laravel in-app notification
                    // $user->notify(new CustomNotification($notification->title, $notification->message));

                    // FCM payload
                    $data = [
                        'type'   => 'home',
                        'chatId' => 'test',
                    ];

                    foreach ($user->deviceTokens as $deviceToken) {

                        if (!empty($deviceToken->token)) {

                            $fcmResponse = $this->sendFCMViaJson(
                                deviceToken: $deviceToken->token,
                                title: $notification->title,
                                body: $notification->message,
                                // notifyId: $notification->id,
                                data: $data
                            );

                            // Log FCM response
                            \Log::info("FCM sent to {$deviceToken->token}", [
                                'response' => $fcmResponse
                            ]);
                        }
                    }
                }

                // ---------------------------------------------------------------------
                // SEND EMAIL
                // ---------------------------------------------------------------------
                elseif ($request->send_in === 'email') {

                   Mail::send('emails.notification', [ 'title' => $notification->title, 'content' => $notification->message, ], function ($mail) use ($user, $notification) { $mail->to($user->email) ->subject($notification->title); });
                }
            }

            return back()->with('success', 'Notification sent successfully!');
        }


  
     public function sendFCMViaJson($deviceToken, $title, $body,$data = [])
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
}





