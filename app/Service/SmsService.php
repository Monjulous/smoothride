<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $host;
    protected $user;
    protected $authkey;
    protected $sender;
    protected $entityId;
    protected $templateId;
    protected $rpt;

    public function __construct()
    {
        $this->host = config('services.sms.host');
        $this->user = config('services.sms.user');
        $this->authkey = config('services.sms.authkey');
        $this->sender = config('services.sms.sender');
        $this->entityId = config('services.sms.entity_id');
        $this->templateId = config('services.sms.template_id');
        $this->rpt = config('services.sms.rpt', 1);
    }

    public function sendOtp($mobile, $message, $templateId)
    {
        $url = "http://{$this->host}/api/pushsms?";

       

        $response = Http::get($url, [
            'user' => $this->user,
            'authkey' => $this->authkey,
            'sender' => $this->sender,
            'mobile' => $mobile,
            'text' => $message,
            'rpt' => $this->rpt,
            'entityid' => $this->entityId,
            'templateid' => $templateId,
        ]);

        // echo"<pre>";print_r($response);die;
       
        return $response->body();
    }
}
